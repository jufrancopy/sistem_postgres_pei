<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Gamification\UserLogin;
use App\Models\Gamification\GamificationPoint;
use App\Services\GamificationService;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\ActivityTaskComment;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Models\Riiss\Evaluacion;

class RecalculateGamificationPoints extends Command
{
    protected $signature = 'gamification:recalculate
                            {--reset : Borra los puntos existentes antes de recalcular}
                            {--user= : Recalcular solo para un usuario (ID o email)}';

    protected $description = 'Recalcula de forma retroactiva los puntos de gamificación e insignias basándose en datos históricos verificables.';

    /** @var Collection<int, User> */
    protected Collection $usersById;

    protected ?string $defaultPeiId = null;

    /** @var array<int, array<string, mixed>> */
    protected array $pendingRows = [];

    /** @var array<string, true> */
    protected array $dedupeKeys = [];

    public function handle(GamificationService $gamificationService): int
    {
        $started = microtime(true);
        $this->info('Iniciando recálculo de puntos de gamificación...');

        $userFilter = $this->option('user');
        $usersQuery = User::query();
        if ($userFilter) {
            $usersQuery->where(function ($q) use ($userFilter) {
                $q->where('id', $userFilter)->orWhere('email', $userFilter);
            });
        }
        $users = $usersQuery->get();
        $this->usersById = $users->keyBy('id');

        if ($users->isEmpty()) {
            $this->error('No se encontraron usuarios para procesar.');
            return Command::FAILURE;
        }

        $this->defaultPeiId = $gamificationService->resolvePeiProfileId(null);

        if ($this->option('reset')) {
            $this->warn('Reseteando historial de puntos e insignias...');
            if ($userFilter) {
                $userIds = $users->pluck('id');
                GamificationPoint::whereIn('user_id', $userIds)->delete();
                \App\Models\Gamification\GamificationBadge::whereIn('user_id', $userIds)->delete();
            } else {
                GamificationPoint::truncate();
                \App\Models\Gamification\GamificationBadge::truncate();
            }
        } else {
            $removed = $gamificationService->purgeOrphanedPoints(
                $userFilter ? (int) $users->first()->id : null
            );
            if ($removed > 0) {
                $this->warn("Se eliminaron {$removed} registros de puntos huérfanos.");
            }

            $this->backfillTaskCreators();
        }

        $this->info('Procesando ' . $users->count() . ' usuario(s)...');

        $this->collectDailyLogins($userFilter);
        $this->collectTasks();
        $this->collectComments();
        $this->collectFodaAnalisis();
        $this->collectFodaCruces();
        $this->collectRiissAsignaciones();
        $this->collectRiissEvaluaciones($gamificationService);

        $this->info('Insertando ' . count($this->pendingRows) . ' registros de puntos...');
        $inserted = $gamificationService->insertPointsBatch($this->pendingRows);
        $this->info("  ↳ {$inserted} filas insertadas.");

        $this->info('Evaluando insignias finales...');
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        foreach ($users as $user) {
            $gamificationService->evaluateBadgesFast($user, $this->defaultPeiId);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        $elapsed = round(microtime(true) - $started, 1);
        $this->info("¡Recálculo completado en {$elapsed}s!");

        return Command::SUCCESS;
    }

    protected function backfillTaskCreators(): void
    {
        DB::table('activity_tasks as t')
            ->join('gamification_points as gp', function ($join) {
                $join->on(DB::raw('gp.reference_id'), '=', DB::raw('t.id::text'))
                    ->where('gp.reference_type', ActivityTask::class)
                    ->where('gp.action_type', 'task_created');
            })
            ->whereNull('t.created_by')
            ->update(['t.created_by' => DB::raw('gp.user_id')]);
    }

    protected function queuePoint(
        int $userId,
        string $actionType,
        string $description,
        int $points,
        ?string $referenceType = null,
        $referenceId = null,
        ?string $peiProfileId = null
    ): void {
        if (!$this->usersById->has($userId)) {
            return;
        }

        if ($referenceType && $referenceId !== null) {
            $key = "{$userId}|{$actionType}|{$referenceType}|{$referenceId}";
            if (isset($this->dedupeKeys[$key])) {
                return;
            }
            $this->dedupeKeys[$key] = true;
        }

        $this->pendingRows[] = [
            'user_id'        => $userId,
            'pei_profile_id' => $peiProfileId ?: $this->defaultPeiId,
            'points'         => $points,
            'action_type'    => $actionType,
            'description'    => $description,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId !== null ? (string) $referenceId : null,
        ];
    }

    protected function collectDailyLogins(?string $userFilter): void
    {
        $this->info('Recopilando accesos diarios...');
        $query = UserLogin::query()->orderBy('login_date');
        if ($userFilter) {
            $query->whereIn('user_id', $this->usersById->keys());
        }

        foreach ($query->cursor() as $login) {
            $this->queuePoint(
                $login->user_id,
                'daily_login',
                'Acceso diario al sistema',
                5
            );
        }
    }

    protected function collectTasks(): void
    {
        $this->info('Recopilando tareas de actividades...');
        $skippedCreated = 0;

        foreach (ActivityTask::with('activity:id,pei_profile_id')->cursor() as $task) {
            $peiProfileId = $task->activity?->pei_profile_id ?: $this->defaultPeiId;

            if ($task->created_by) {
                $this->queuePoint(
                    $task->created_by,
                    'task_created',
                    'Creación de tarea: ' . Str::limit($task->title, 30),
                    15,
                    ActivityTask::class,
                    $task->id,
                    $peiProfileId
                );
            } else {
                $skippedCreated++;
            }

            if ((int) $task->status === 2 && $task->completed_by) {
                $this->queuePoint(
                    $task->completed_by,
                    'task_completed',
                    'Tarea completada: ' . Str::limit($task->title, 30),
                    25,
                    ActivityTask::class,
                    $task->id,
                    $peiProfileId
                );
            }
        }

        if ($skippedCreated > 0) {
            $this->comment("  ↳ {$skippedCreated} tareas sin created_by omitidas.");
        }
    }

    protected function collectComments(): void
    {
        $this->info('Recopilando comentarios...');

        foreach (ActivityTaskComment::with('task.activity:id,pei_profile_id')->cursor() as $comment) {
            if (!$comment->task) {
                continue;
            }

            $this->queuePoint(
                $comment->user_id,
                'comment_created',
                'Comentario en tarea: ' . Str::limit($comment->task->title, 30),
                5,
                ActivityTaskComment::class,
                $comment->id,
                $comment->task->activity?->pei_profile_id ?: $this->defaultPeiId
            );
        }
    }

    protected function collectFodaAnalisis(): void
    {
        $this->info('Recopilando análisis FODA...');

        foreach (FodaAnalisis::whereNotNull('user_id')->cursor() as $foda) {
            $this->queuePoint(
                $foda->user_id,
                'foda_analisis',
                'Análisis FODA: ' . ($foda->tipo ?? 'Aspecto'),
                15,
                FodaAnalisis::class,
                $foda->id,
                $foda->perfil_id ?: $this->defaultPeiId
            );
        }
    }

    protected function collectFodaCruces(): void
    {
        $this->info('Recopilando estrategias de cruce FODA...');

        $perfilUserMap = FodaPerfil::query()
            ->whereNotNull('user_id')
            ->pluck('user_id', 'id');

        foreach (FodaCruceAmbiente::cursor() as $cruce) {
            $userId = $cruce->user_id ?: ($cruce->perfil_id ? $perfilUserMap->get($cruce->perfil_id) : null);
            if (!$userId) {
                continue;
            }

            $this->queuePoint(
                $userId,
                'foda_cruce',
                'Estrategia Cruce FODA: ' . ($cruce->tipo ?? 'Estrategia'),
                30,
                FodaCruceAmbiente::class,
                $cruce->id,
                $cruce->perfil_id ?: $this->defaultPeiId
            );
        }
    }

    protected function collectRiissAsignaciones(): void
    {
        $this->info('Recopilando asignaciones RIISS...');

        foreach (\App\Models\Riiss\Asignacion::with('establecimiento:id_establecimiento,nombre_oficial')->cursor() as $asignacion) {
            if (!$asignacion->evaluador_id || !$this->usersById->has($asignacion->evaluador_id)) {
                continue;
            }

            $this->queuePoint(
                $asignacion->evaluador_id,
                'riiss_asignacion',
                'Asignación RIISS recibida: ' . ($asignacion->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                50,
                \App\Models\Riiss\Asignacion::class,
                $asignacion->id,
                $asignacion->pei_profile_id ?: $this->defaultPeiId
            );
        }
    }

    protected function collectRiissEvaluaciones(GamificationService $gamificationService): void
    {
        $this->info('Recopilando evaluaciones RIISS...');
        $skippedEval = 0;

        foreach (Evaluacion::with('establecimiento:id_establecimiento,nombre_oficial')->where('estado', 'completada')->cursor() as $eval) {
            $user = $gamificationService->resolveEvaluacionUser($eval, $this->usersById->values());
            if (!$user) {
                $skippedEval++;
                continue;
            }

            $this->queuePoint(
                $user->id,
                'riiss_evaluacion',
                'Evaluación RIISS completada: ' . ($eval->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                100,
                Evaluacion::class,
                $eval->id,
                $eval->pei_profile_id ?: $this->defaultPeiId
            );
        }

        if ($skippedEval > 0) {
            $this->comment("  ↳ {$skippedEval} evaluaciones omitidas (evaluador no identificable).");
        }
    }
}
