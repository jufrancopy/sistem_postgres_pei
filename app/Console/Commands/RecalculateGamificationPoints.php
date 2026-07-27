<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\GamificationService;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\ActivityTaskComment;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Models\Riiss\Evaluacion;

class RecalculateGamificationPoints extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'gamification:recalculate {--reset : Borra los puntos existentes antes de recalcular}';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Recalcula de forma retroactiva los puntos de gamificación e insignias de todos los usuarios basándose en sus datos históricos.';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(GamificationService $gamificationService)
    {
        $this->info('Iniciando cálculo retroactivo de puntos de gamificación...');

        if ($this->option('reset')) {
            $this->warn('Reseteando historial de puntos e insignias...');
            \App\Models\Gamification\GamificationPoint::truncate();
            \App\Models\Gamification\GamificationBadge::truncate();
        }

        $users = User::all();
        $this->info('Procesando ' . $users->count() . ' usuarios...');

        $config = \App\Models\HomeConfiguration::first();
        $targetPeiId = $config?->pei_profile_id ?: '766eb883-fdd0-4723-8f75-cf689aa8f0fa';

        // 1. Tareas de Actividades Creadas y Completadas
        $this->info('Procesando tareas de actividades...');
        $tasks = ActivityTask::with('activity')->get();
        foreach ($tasks as $task) {
            $peiProfileId = $task->activity?->pei_profile_id ?: $targetPeiId;

            // Tarea Creada
            if ($task->assigned_to) {
                $user = $users->firstWhere('id', $task->assigned_to);
                if ($user) {
                    $gamificationService->awardPoints(
                        $user,
                        'task_created',
                        'Creación de tarea: ' . \Illuminate\Support\Str::limit($task->title, 30),
                        15,
                        $task,
                        $peiProfileId
                    );
                }
            }

            // Tarea Completada
            if ($task->status == 2 && ($task->completed_by || $task->assigned_to)) {
                $userId = $task->completed_by ?: $task->assigned_to;
                $user = $users->firstWhere('id', $userId);
                if ($user) {
                    $gamificationService->awardPoints(
                        $user,
                        'task_completed',
                        'Tarea completada: ' . \Illuminate\Support\Str::limit($task->title, 30),
                        25,
                        $task,
                        $peiProfileId
                    );
                }
            }
        }

        // 2. Comentarios en Tareas
        $this->info('Procesando comentarios...');
        $comments = ActivityTaskComment::with('task.activity')->get();
        foreach ($comments as $comment) {
            $user = $users->firstWhere('id', $comment->user_id);
            if ($user) {
                $taskTitle = $comment->task ? $comment->task->title : 'Tarea';
                $peiProfileId = $comment->task?->activity?->pei_profile_id ?: $targetPeiId;
                $gamificationService->awardPoints(
                    $user,
                    'comment_created',
                    'Comentario en tarea: ' . \Illuminate\Support\Str::limit($taskTitle, 30),
                    5,
                    $comment,
                    $peiProfileId
                );
            }
        }

        // 3. Análisis FODA
        $this->info('Procesando análisis FODA...');
        $fodaItems = FodaAnalisis::whereNotNull('user_id')->get();
        foreach ($fodaItems as $foda) {
            $user = $users->firstWhere('id', $foda->user_id);
            if ($user) {
                $gamificationService->awardPoints(
                    $user,
                    'foda_analisis',
                    'Análisis FODA: ' . ($foda->tipo ?? 'Aspecto'),
                    15,
                    $foda,
                    $foda->perfil_id ?: $targetPeiId
                );
            }
        }

        // 4. Estrategias de Cruce FODA
        $this->info('Procesando estrategias de cruce FODA...');
        $cruces = FodaCruceAmbiente::all();
        foreach ($cruces as $cruce) {
            $user = $cruce->user_id ? $users->firstWhere('id', $cruce->user_id) : null;
            if (!$user && $cruce->perfil_id) {
                $perfil = FodaPerfil::find($cruce->perfil_id);
                $user = $perfil ? $users->firstWhere('id', $perfil->user_id) : null;
            }
            if ($user) {
                $gamificationService->awardPoints(
                    $user,
                    'foda_cruce',
                    'Estrategia Cruce FODA: ' . ($cruce->tipo ?? 'Estrategia'),
                    30,
                    $cruce,
                    $cruce->perfil_id ?: $targetPeiId
                );
            }
        }

        // 4.5. Asignaciones RIISS
        $this->info('Procesando asignaciones RIISS...');
        $asignaciones = \App\Models\Riiss\Asignacion::with('establecimiento', 'evaluador')->get();
        foreach ($asignaciones as $asignacion) {
            if ($asignacion->evaluador) {
                $gamificationService->awardPoints(
                    $asignacion->evaluador,
                    'riiss_asignacion',
                    'Asignación RIISS recibida: ' . ($asignacion->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                    50,
                    $asignacion,
                    $asignacion->pei_profile_id ?: $targetPeiId
                );
            }
        }

        // 5. Evaluaciones RIISS
        $this->info('Procesando evaluaciones RIISS...');
        $evaluaciones = Evaluacion::with('establecimiento')->where('estado', 'completada')->get();
        foreach ($evaluaciones as $eval) {
            $user = null;
            if ($eval->evaluador_usuario_institucional) {
                $user = $users->firstWhere('email', $eval->evaluador_usuario_institucional);
            }
            if (!$user && $eval->evaluador_nombre) {
                $user = $users->first(function($u) use ($eval) {
                    return stripos($u->name, $eval->evaluador_nombre) !== false || stripos($eval->evaluador_nombre, $u->name) !== false;
                });
            }
            if (!$user && is_array($eval->evaluadores)) {
                foreach ($eval->evaluadores as $evItem) {
                    $uItem = $users->firstWhere('email', $evItem) ?: $users->firstWhere('name', $evItem);
                    if ($uItem) {
                        $user = $uItem;
                        break;
                    }
                }
            }

            if ($user) {
                $gamificationService->awardPoints(
                    $user,
                    'riiss_evaluacion',
                    'Evaluación RIISS completada: ' . ($eval->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                    100,
                    $eval,
                    $eval->pei_profile_id ?: $targetPeiId
                );
            }
        }

        // Reevaluar insignias para todos los usuarios
        $this->info('Evaluando insignias y niveles finales...');
        foreach ($users as $u) {
            $gamificationService->evaluateBadges($u);
        }

        $this->info('¡Recálculo completado con éxito!');
        return Command::SUCCESS;
    }
}
