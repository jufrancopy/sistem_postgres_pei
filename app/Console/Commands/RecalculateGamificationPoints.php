<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Gamification\UserLogin;
use App\Services\GamificationService;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\ActivityTaskComment;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Models\Riiss\Evaluacion;
use Illuminate\Support\Str;

class RecalculateGamificationPoints extends Command
{
    protected $signature = 'gamification:recalculate
                            {--reset : Borra los puntos existentes antes de recalcular}
                            {--user= : Recalcular solo para un usuario (ID o email)}';

    protected $description = 'Recalcula de forma retroactiva los puntos de gamificación e insignias basándose en datos históricos verificables.';

    public function handle(GamificationService $gamificationService): int
    {
        $this->info('Iniciando recálculo de puntos de gamificación...');

        $usersQuery = User::query();
        if ($userFilter = $this->option('user')) {
            $usersQuery->where(function ($q) use ($userFilter) {
                $q->where('id', $userFilter)->orWhere('email', $userFilter);
            });
        }
        $users = $usersQuery->get();

        if ($users->isEmpty()) {
            $this->error('No se encontraron usuarios para procesar.');
            return Command::FAILURE;
        }

        if ($this->option('reset')) {
            $this->warn('Reseteando historial de puntos e insignias...');
            if ($userFilter) {
                $userIds = $users->pluck('id');
                \App\Models\Gamification\GamificationPoint::whereIn('user_id', $userIds)->delete();
                \App\Models\Gamification\GamificationBadge::whereIn('user_id', $userIds)->delete();
            } else {
                \App\Models\Gamification\GamificationPoint::truncate();
                \App\Models\Gamification\GamificationBadge::truncate();
            }
        } else {
            $removed = $gamificationService->purgeOrphanedPoints(
                $userFilter ? (int) $users->first()->id : null
            );
            if ($removed > 0) {
                $this->warn("Se eliminaron {$removed} registros de puntos huérfanos (referencia inexistente).");
            }
        }

        $this->info('Procesando ' . $users->count() . ' usuario(s)...');

        // 1. Accesos diarios registrados
        $this->info('Procesando accesos diarios...');
        $loginsQuery = UserLogin::query();
        if ($userFilter) {
            $loginsQuery->whereIn('user_id', $users->pluck('id'));
        }
        foreach ($loginsQuery->orderBy('login_date')->get() as $login) {
            $user = $users->firstWhere('id', $login->user_id);
            if ($user) {
                $gamificationService->awardPoints(
                    $user,
                    'daily_login',
                    'Acceso diario al sistema',
                    5
                );
            }
        }

        // 2. Tareas — solo acciones verificables por campo de auditoría
        $this->info('Procesando tareas de actividades...');

        // Backfill: inferir creador desde puntos históricos correctos (live awards)
        \App\Models\Gamification\GamificationPoint::query()
            ->where('action_type', 'task_created')
            ->where('reference_type', ActivityTask::class)
            ->each(function ($point) {
                $task = ActivityTask::find($point->reference_id);
                if ($task && !$task->created_by) {
                    $task->update(['created_by' => $point->user_id]);
                }
            });

        $tasks = ActivityTask::with('activity')->get();
        $skippedCreated = 0;

        foreach ($tasks as $task) {
            $peiProfileId = $gamificationService->resolvePeiProfileId($task->activity?->pei_profile_id);

            if ($task->created_by) {
                $creator = $users->firstWhere('id', $task->created_by);
                if ($creator) {
                    $gamificationService->awardPoints(
                        $creator,
                        'task_created',
                        'Creación de tarea: ' . Str::limit($task->title, 30),
                        15,
                        $task,
                        $peiProfileId
                    );
                }
            } else {
                $skippedCreated++;
            }

            if ((int) $task->status === 2 && $task->completed_by) {
                $completer = $users->firstWhere('id', $task->completed_by);
                if ($completer) {
                    $gamificationService->awardPoints(
                        $completer,
                        'task_completed',
                        'Tarea completada: ' . Str::limit($task->title, 30),
                        25,
                        $task,
                        $peiProfileId
                    );
                }
            }
        }

        if ($skippedCreated > 0) {
            $this->comment("  ↳ {$skippedCreated} tareas sin created_by omitidas para 'task_created' (datos históricos).");
        }

        // 3. Comentarios en tareas
        $this->info('Procesando comentarios...');
        foreach (ActivityTaskComment::with('task.activity')->get() as $comment) {
            $user = $users->firstWhere('id', $comment->user_id);
            if (!$user || !$comment->task) {
                continue;
            }

            $peiProfileId = $gamificationService->resolvePeiProfileId($comment->task->activity?->pei_profile_id);
            $gamificationService->awardPoints(
                $user,
                'comment_created',
                'Comentario en tarea: ' . Str::limit($comment->task->title, 30),
                5,
                $comment,
                $peiProfileId
            );
        }

        // 4. Análisis FODA
        $this->info('Procesando análisis FODA...');
        foreach (FodaAnalisis::whereNotNull('user_id')->get() as $foda) {
            $user = $users->firstWhere('id', $foda->user_id);
            if ($user) {
                $gamificationService->awardPoints(
                    $user,
                    'foda_analisis',
                    'Análisis FODA: ' . ($foda->tipo ?? 'Aspecto'),
                    15,
                    $foda,
                    $gamificationService->resolvePeiProfileId($foda->perfil_id)
                );
            }
        }

        // 5. Estrategias de cruce FODA
        $this->info('Procesando estrategias de cruce FODA...');
        foreach (FodaCruceAmbiente::all() as $cruce) {
            $user = $cruce->user_id ? $users->firstWhere('id', $cruce->user_id) : null;
            if (!$user && $cruce->perfil_id) {
                $perfil = FodaPerfil::find($cruce->perfil_id);
                $user = $perfil?->user_id ? $users->firstWhere('id', $perfil->user_id) : null;
            }
            if ($user) {
                $gamificationService->awardPoints(
                    $user,
                    'foda_cruce',
                    'Estrategia Cruce FODA: ' . ($cruce->tipo ?? 'Estrategia'),
                    30,
                    $cruce,
                    $gamificationService->resolvePeiProfileId($cruce->perfil_id)
                );
            }
        }

        // 6. Asignaciones RIISS
        $this->info('Procesando asignaciones RIISS...');
        foreach (\App\Models\Riiss\Asignacion::with('establecimiento', 'evaluador')->get() as $asignacion) {
            if (!$asignacion->evaluador || !$users->contains('id', $asignacion->evaluador->id)) {
                continue;
            }

            $gamificationService->awardPoints(
                $asignacion->evaluador,
                'riiss_asignacion',
                'Asignación RIISS recibida: ' . ($asignacion->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                50,
                $asignacion,
                $gamificationService->resolvePeiProfileId($asignacion->pei_profile_id)
            );
        }

        // 7. Evaluaciones RIISS completadas — solo match exacto por email
        $this->info('Procesando evaluaciones RIISS...');
        $skippedEval = 0;
        foreach (Evaluacion::with('establecimiento')->where('estado', 'completada')->get() as $eval) {
            $user = $gamificationService->resolveEvaluacionUser($eval, $users);
            if (!$user) {
                $skippedEval++;
                continue;
            }

            $gamificationService->awardPoints(
                $user,
                'riiss_evaluacion',
                'Evaluación RIISS completada: ' . ($eval->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                100,
                $eval,
                $gamificationService->resolvePeiProfileId($eval->pei_profile_id)
            );
        }

        if ($skippedEval > 0) {
            $this->comment("  ↳ {$skippedEval} evaluaciones omitidas por evaluador no identificable (sin email exacto).");
        }

        $this->info('Evaluando insignias finales...');
        foreach ($users as $user) {
            $gamificationService->evaluateBadges($user);
        }

        $this->info('¡Recálculo completado con éxito!');
        $this->line('Ejecutá con --reset para reconstruir todo el historial desde cero.');

        return Command::SUCCESS;
    }
}
