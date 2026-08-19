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
use App\Models\Riiss\Evaluacion;
use App\Models\Planificacion\PeiProfileEdit;

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
            $this->warn('Reseteando historial de puntos e insignias (preservando puntos manuales)...');
            if ($userFilter) {
                $userIds = $users->pluck('id');
                GamificationPoint::whereIn('user_id', $userIds)
                    ->where('action_type', '!=', 'manual_admin')
                    ->delete();
                \App\Models\Gamification\GamificationBadge::whereIn('user_id', $userIds)->delete();
            } else {
                GamificationPoint::where('action_type', '!=', 'manual_admin')->delete();
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
        $this->collectChatMessages();
        $this->collectPeiEdits();
        $this->collectAccionesOperativas();

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
        DB::statement("
            UPDATE activity_tasks t
            SET created_by = gp.user_id
            FROM gamification_points gp
            WHERE gp.reference_id = t.id::text
              AND gp.reference_type = 'App\\\\Admin\\\\Globales\\\\ActivityTask'
              AND gp.action_type = 'task_created'
              AND t.created_by IS NULL
        ");
    }

    protected function queuePoint(
        int $userId,
        string $actionType,
        string $description,
        int $points,
        ?string $referenceType = null,
        $referenceId = null,
        ?string $peiProfileId = null,
        ?string $createdAt = null
    ): void {
        if (!$this->usersById->has($userId)) {
            return;
        }

        $dedupeKey = null;
        if ($referenceType && $referenceId !== null) {
            $dedupeKey = "{$userId}|{$actionType}|{$referenceType}|{$referenceId}";
        } elseif ($actionType === 'daily_login' && $createdAt) {
            $dateStr = substr($createdAt, 0, 10);
            $dedupeKey = "{$userId}|daily_login|{$dateStr}";
        }

        if ($dedupeKey) {
            if (isset($this->dedupeKeys[$dedupeKey])) {
                return;
            }
            $this->dedupeKeys[$dedupeKey] = true;
        }

        $row = [
            'user_id'        => $userId,
            'pei_profile_id' => $peiProfileId ?: $this->defaultPeiId,
            'points'         => $points,
            'action_type'    => $actionType,
            'description'    => $description,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId !== null ? (string) $referenceId : null,
        ];

        if ($createdAt) {
            $row['created_at'] = $createdAt;
            $row['updated_at'] = $createdAt;
        }

        $this->pendingRows[] = $row;
    }

    protected function collectDailyLogins(?string $userFilter): void
    {
        $this->info('Recopilando accesos diarios...');
        $query = UserLogin::query()->orderBy('login_date');
        if ($userFilter) {
            $query->whereIn('user_id', $this->usersById->keys());
        }

        foreach ($query->cursor() as $login) {
            $loginDate = \Carbon\Carbon::parse($login->login_date)->setTime(8, 0, 0)->toDateTimeString();

            $this->queuePoint(
                $login->user_id,
                'daily_login',
                'Acceso diario al sistema',
                5,
                null,
                null,
                null,
                $loginDate
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
        $skipped = 0;

        foreach (FodaCruceAmbiente::whereNotNull('user_id')->cursor() as $cruce) {
            if (!$this->usersById->has($cruce->user_id)) {
                $skipped++;
                continue;
            }

            $this->queuePoint(
                $cruce->user_id,
                'foda_cruce',
                'Estrategia Cruce FODA: ' . ($cruce->tipo ?? 'Estrategia'),
                30,
                FodaCruceAmbiente::class,
                $cruce->id,
                $cruce->perfil_id ?: $this->defaultPeiId
            );
        }

        if ($skipped > 0) {
            $this->comment("  ↳ {$skipped} cruces omitidos (user_id inexistente o fuera del alcance).");
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

    protected function collectChatMessages(): void
    {
        $this->info('Recopilando interacción en el chat...');

        $userDailyCounts = [];

        foreach (\App\Models\Planificacion\PeiChatMessage::where('is_system', false)->cursor() as $msg) {
            $peiProfileId = $msg->pei_profile_id ?: $this->defaultPeiId;

            if ($msg->reference_title) {
                $this->queuePoint(
                    $msg->user_id,
                    'chat_context_query',
                    'Consulta vinculada sobre: ' . Str::limit($msg->reference_title, 30),
                    10,
                    \App\Models\Planificacion\PeiChatMessage::class,
                    $msg->id,
                    $peiProfileId
                );
            } else {
                $dateKey = $msg->created_at->format('Y-m-d');
                $userKey = "{$msg->user_id}|{$dateKey}";
                $userDailyCounts[$userKey] = ($userDailyCounts[$userKey] ?? 0) + 1;

                if ($userDailyCounts[$userKey] <= 5) {
                    $this->queuePoint(
                        $msg->user_id,
                        'chat_message',
                        'Aporte en el chat de equipo PEI',
                        3,
                        \App\Models\Planificacion\PeiChatMessage::class,
                        $msg->id,
                        $peiProfileId
                    );
                }
            }
        }
    }

    protected function collectPeiEdits(): void
    {
        $this->info('Recopilando ediciones, autorías y asignaciones de elementos PEI...');

        // 1. Histórico de la tabla pei_profile_edits
        foreach (PeiProfileEdit::with('user')->cursor() as $edit) {
            if (!$this->usersById->has($edit->user_id)) {
                continue;
            }

            $this->queuePoint(
                $edit->user_id,
                'pei_edit',
                'Edición de elemento PEI',
                10,
                PeiProfileEdit::class,
                $edit->id,
                (string) $edit->pei_profile_id,
                $edit->created_at?->toDateTimeString()
            );
        }

        // 2. Nodos PEI asignados/creados directamente (user_id en pei_profiles - 353 nodos)
        foreach (\App\Admin\Planificacion\Pei\PeiProfile::whereNotNull('user_id')->cursor() as $node) {
            if (!$this->usersById->has($node->user_id)) {
                continue;
            }

            $this->queuePoint(
                $node->user_id,
                'pei_edit',
                'Autoría/Gestión de elemento PEI: ' . Str::limit(strip_tags($node->name), 40),
                10,
                \App\Admin\Planificacion\Pei\PeiProfile::class,
                $node->id,
                (string) $node->id,
                $node->created_at?->toDateTimeString()
            );
        }

        // 3. Edición de nodos en pei_profiles (updated_by)
        foreach (\App\Admin\Planificacion\Pei\PeiProfile::whereNotNull('updated_by')->cursor() as $node) {
            if (!$this->usersById->has($node->updated_by)) {
                continue;
            }

            $this->queuePoint(
                $node->updated_by,
                'pei_edit',
                'Actualización de elemento PEI: ' . Str::limit(strip_tags($node->name), 40),
                10,
                \App\Admin\Planificacion\Pei\PeiProfile::class,
                'upd_' . $node->id,
                (string) $node->id,
                $node->updated_at?->toDateTimeString()
            );
        }

        // 4. Analistas asignados a elementos PEI (tabla pivot peis_profiles_has_analysts)
        $analistasPivot = DB::table('planificacion.peis_profiles_has_analysts')->get();
        foreach ($analistasPivot as $row) {
            if (!$this->usersById->has($row->analyst_id)) {
                continue;
            }

            $this->queuePoint(
                $row->analyst_id,
                'pei_edit',
                'Asignación como Analista en elemento PEI #' . $row->pei_profile_id,
                10,
                \App\Admin\Planificacion\Pei\PeiProfile::class,
                'analyst_pivot_' . $row->pei_profile_id . '_' . $row->analyst_id,
                (string) $row->pei_profile_id
            );
        }

        // 5. Reportes de avance en acciones PEI (pei_accion_reportes)
        foreach (\App\Models\Planificacion\PeiAccionReporte::whereNotNull('user_id')->cursor() as $reporte) {
            if (!$this->usersById->has($reporte->user_id)) {
                continue;
            }

            $this->queuePoint(
                $reporte->user_id,
                'pei_reporte_avance',
                'Reporte de Avance en Acción PEI',
                15,
                \App\Models\Planificacion\PeiAccionReporte::class,
                $reporte->id,
                (string) $reporte->pei_profile_id,
                $reporte->created_at?->toDateTimeString()
            );
        }
    }

    protected function collectAccionesOperativas(): void
    {
        $this->info('Recopilando Acciones Operativas registradas...');

        foreach (\App\Models\PlanMaestro\PlanAccion::whereNotNull('user_id')->cursor() as $accion) {
            if (!$this->usersById->has($accion->user_id)) {
                continue;
            }

            $this->queuePoint(
                $accion->user_id,
                'accion_operativa_created',
                'Aporte/Registro de Acción Operativa: ' . Str::limit($accion->accion, 35),
                15,
                \App\Models\PlanMaestro\PlanAccion::class,
                $accion->id,
                (string) ($accion->pei_profile_id ?: $this->defaultPeiId),
                $accion->created_at?->toDateTimeString()
            );
        }
    }
}
