<?php

namespace App\Services;

use App\Models\User;
use App\Models\HomeConfiguration;
use App\Models\Gamification\GamificationPoint;
use App\Models\Gamification\GamificationBadge;
use App\Models\Gamification\UserLogin;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\ActivityTaskComment;
use App\Models\Riiss\Evaluacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Definición de Rangos / Niveles estilo Stack Overflow
     */
    const LEVELS = [
        1 => ['name' => 'Aprendiz PEI',           'min' => 0,    'max' => 49,   'icon' => 'fa-seedling', 'badge' => '🥉 Bronce I'],
        2 => ['name' => 'Colaborador Activo',      'min' => 50,   'max' => 199,  'icon' => 'fa-user-check','badge' => '🥈 Plata I'],
        3 => ['name' => 'Evaluador de Campo',      'min' => 200,  'max' => 499,  'icon' => 'fa-compass',  'badge' => '🥇 Oro I'],
        4 => ['name' => 'Estratega Institucional', 'min' => 500,  'max' => 999,  'icon' => 'fa-chess',    'badge' => '💎 Platino'],
        5 => ['name' => 'Maestro del PEI',         'min' => 1000, 'max' => 999999, 'icon' => 'fa-crown',  'badge' => '👑 Leyenda'],
    ];

    /**
     * Otorga puntos a un usuario y evalúa si desbloquea nuevas insignias
     */
    public function awardPoints(
        User $user,
        string $actionType,
        string $description,
        int $points,
        $referenceModel = null,
        ?string $peiProfileId = null
    ): ?GamificationPoint {
        if ($points <= 0) {
            return null;
        }

        // Si no se especifica peiProfileId, intentar obtener de la configuración global activa
        if (!$peiProfileId) {
            $config = HomeConfiguration::first();
            $peiProfileId = $config?->pei_profile_id;
        }

        $refType = $referenceModel ? get_class($referenceModel) : null;
        $refId   = $referenceModel ? (string)$referenceModel->id : null;

        // Evitar duplicar puntos por la misma referencia exacta si aplica
        if ($refType && $refId) {
            $exists = GamificationPoint::where('user_id', $user->id)
                ->where('action_type', $actionType)
                ->where('reference_type', $refType)
                ->where('reference_id', $refId)
                ->exists();
            if ($exists) {
                return null;
            }
        }

        $record = GamificationPoint::create([
            'user_id'        => $user->id,
            'pei_profile_id' => $peiProfileId,
            'points'         => $points,
            'action_type'    => $actionType,
            'description'    => $description,
            'reference_type' => $refType,
            'reference_id'   => $refId,
        ]);

        // Evaluar insignias a desbloquear
        $this->evaluateBadges($user, $peiProfileId);

        return $record;
    }

    /**
     * Verifica si el registro referenciado aún existe en la base de datos.
     */
    public function referenceExists(?string $referenceType, ?string $referenceId): bool
    {
        if (!$referenceType || !$referenceId || !class_exists($referenceType)) {
            return true;
        }

        $model = app($referenceType);

        return $model->newQuery()
            ->where($model->getKeyName(), $referenceId)
            ->exists();
    }

    /**
     * Elimina puntos cuyo registro de referencia ya no existe (tareas borradas, etc.).
     */
    public function purgeOrphanedPoints(?int $userId = null): int
    {
        $query = GamificationPoint::query()
            ->whereNotNull('reference_type')
            ->whereNotNull('reference_id');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $removed = 0;
        foreach ($query->get() as $point) {
            if (!$this->referenceExists($point->reference_type, $point->reference_id)) {
                $point->delete();
                $removed++;
            }
        }

        return $removed;
    }

    /**
     * Resuelve el usuario evaluador de una evaluación RIISS sin coincidencias ambiguas por nombre.
     */
    public function resolveEvaluacionUser(Evaluacion $eval, $users = null): ?User
    {
        $users = $users ?? User::all();

        if ($eval->evaluador_usuario_institucional) {
            $byEmail = $users->firstWhere('email', $eval->evaluador_usuario_institucional);
            if ($byEmail) {
                return $byEmail;
            }
        }

        if (is_array($eval->evaluadores)) {
            foreach ($eval->evaluadores as $evItem) {
                if (!is_string($evItem) || !str_contains($evItem, '@')) {
                    continue;
                }
                $byEmail = $users->firstWhere('email', $evItem);
                if ($byEmail) {
                    return $byEmail;
                }
            }
        }

        return null;
    }

    /**
     * Resuelve el PEI asociado a una acción de gamificación.
     */
    public function resolvePeiProfileId(?string $peiProfileId = null): ?string
    {
        if ($peiProfileId) {
            return $peiProfileId;
        }

        return HomeConfiguration::first()?->pei_profile_id;
    }

    /**
     * Registra el ingreso diario del usuario y otorga puntos
     */
    public function recordDailyLogin(User $user, ?string $ipAddress = null): void
    {
        $today = Carbon::today();
        $exists = UserLogin::where('user_id', $user->id)
            ->where('login_date', $today)
            ->exists();

        if (!$exists) {
            UserLogin::create([
                'user_id'    => $user->id,
                'login_date' => $today,
                'ip_address' => $ipAddress,
            ]);

            // Puntos por ingreso diario (+5 pts)
            $this->awardPoints($user, 'daily_login', 'Acceso diario al sistema', 5);

            // Verificar racha de 5 días seguidos (+20 pts)
            $fiveDaysAgo = Carbon::today()->subDays(4);
            $consecutiveLogins = UserLogin::where('user_id', $user->id)
                ->where('login_date', '>=', $fiveDaysAgo)
                ->count();

            if ($consecutiveLogins >= 5) {
                $alreadyAwarded = GamificationPoint::where('user_id', $user->id)
                    ->where('action_type', 'login_streak')
                    ->whereDate('created_at', $today)
                    ->exists();

                if (!$alreadyAwarded) {
                    $this->awardPoints($user, 'login_streak', 'Bonificación: Racha de 5 días de acceso continuo', 20);
                }
            }
        }
    }

    /**
     * Evalúa las insignias del usuario y desbloquea las correspondientes
     */
    public function evaluateBadges(User $user, ?string $peiProfileId = null): void
    {
        $catalog = GamificationBadge::getCatalog();
        $userPoints = $this->getUserTotalPoints($user, $peiProfileId);

        // Conteos de acciones del usuario (solo referencias válidas)
        $commentsCount   = $this->countValidActions($user, 'comment_created');
        $fodaCount       = $this->countValidActions($user, 'foda_analisis');
        $cruceCount      = $this->countValidActions($user, 'foda_cruce');
        $tasksCompleted  = $this->countValidActions($user, 'task_completed');
        $riissCount      = $this->countValidActions($user, 'riiss_evaluacion');
        $loginsCount     = UserLogin::where('user_id', $user->id)->count();

        $badgeConditions = [
            'primer_paso'            => $commentsCount >= 1,
            'diagnostico_inicial'    => $fodaCount >= 1,
            'evaluador_novato'       => $riissCount >= 1,
            'ingreso_diario'         => $loginsCount >= 1,
            'tactico_eficiente'      => $tasksCompleted >= 10,
            'analista_foda'          => $fodaCount >= 15,
            'formulador_estrategico' => $cruceCount >= 5,
            'evaluador_experto'      => $riissCount >= 10,
            'comunicador'            => $commentsCount >= 20,
            'arquitecto_estrategico' => ($cruceCount >= 15 && $fodaCount >= 30),
            'guardian_ejecucion'     => $tasksCompleted >= 50,
            'inspector_salud'        => $riissCount >= 25,
            'leyenda_estrategica'    => $userPoints >= 1000,
        ];

        foreach ($badgeConditions as $badgeKey => $conditionMet) {
            if ($conditionMet && isset($catalog[$badgeKey])) {
                $alreadyUnlocked = GamificationBadge::where('user_id', $user->id)
                    ->where('badge_key', $badgeKey)
                    ->where(function($q) use ($peiProfileId) {
                        if ($peiProfileId) {
                            $q->where('pei_profile_id', $peiProfileId)->orWhereNull('pei_profile_id');
                        }
                    })->exists();

                if (!$alreadyUnlocked) {
                    GamificationBadge::create([
                        'user_id'        => $user->id,
                        'pei_profile_id' => $peiProfileId,
                        'badge_key'      => $badgeKey,
                        'unlocked_at'    => Carbon::now(),
                    ]);
                }
            }
        }
    }

    /**
     * Cuenta acciones de un tipo solo cuando la referencia sigue existiendo.
     */
    public function countValidActions(User $user, string $actionType): int
    {
        return GamificationPoint::where('user_id', $user->id)
            ->where('action_type', $actionType)
            ->get()
            ->filter(fn (GamificationPoint $point) => $this->referenceExists(
                $point->reference_type,
                $point->reference_id
            ))
            ->count();
    }

    /**
     * Query base de puntos del usuario, opcionalmente filtrado por PEI.
     */
    protected function pointsQuery(User $user, ?string $peiProfileId = null)
    {
        $query = GamificationPoint::where('user_id', $user->id);

        if ($peiProfileId) {
            $query->where(function ($q) use ($peiProfileId) {
                $q->where('pei_profile_id', $peiProfileId)->orWhereNull('pei_profile_id');
            });
        }

        return $query;
    }

    /**
     * Puntos con referencia verificada (excluye huérfanos).
     */
    public function getValidPointsForUser(User $user, ?string $peiProfileId = null): \Illuminate\Support\Collection
    {
        return $this->pointsQuery($user, $peiProfileId)
            ->get()
            ->filter(fn (GamificationPoint $point) => $this->referenceExists(
                $point->reference_type,
                $point->reference_id
            ));
    }

    /**
     * Obtiene los puntos totales del usuario (filtrado por PEI, sin huérfanos).
     */
    public function getUserTotalPoints(User $user, ?string $peiProfileId = null): int
    {
        return (int) $this->getValidPointsForUser($user, $peiProfileId)->sum('points');
    }

    /**
     * Retorna la información completa de Gamificación y Perfil estilo Stack Overflow
     */
    public function getUserGamificationSummary(User $user, ?string $peiProfileId = null): array
    {
        $totalPoints = $this->getUserTotalPoints($user, $peiProfileId);

        // Determinar Nivel
        $currentLevelNum = 1;
        $currentLevel = self::LEVELS[1];
        foreach (self::LEVELS as $lvl => $info) {
            if ($totalPoints >= $info['min']) {
                $currentLevelNum = $lvl;
                $currentLevel = $info;
            }
        }

        // Progreso hacia el siguiente nivel
        $nextLevelNum = min($currentLevelNum + 1, 5);
        $nextLevel = self::LEVELS[$nextLevelNum];
        $pointsInLevel = $totalPoints - $currentLevel['min'];
        $levelRange = max($currentLevel['max'] - $currentLevel['min'] + 1, 1);
        $progressPct = $currentLevelNum === 5 ? 100 : min(round(($pointsInLevel / $levelRange) * 100), 100);

        // Insignias obtenidas
        $catalog = GamificationBadge::getCatalog();
        $unlockedBadgesQuery = GamificationBadge::where('user_id', $user->id);
        if ($peiProfileId) {
            $unlockedBadgesQuery->where(function($q) use ($peiProfileId) {
                $q->where('pei_profile_id', $peiProfileId)->orWhereNull('pei_profile_id');
            });
        }
        $unlockedRecords = $unlockedBadgesQuery->get();

        $unlockedKeys = $unlockedRecords->pluck('badge_key')->toArray();

        $badgesSummary = [
            'oro'    => 0,
            'plata'  => 0,
            'bronce' => 0,
            'list'   => [],
        ];

        foreach ($catalog as $key => $bInfo) {
            $unlocked = in_array($key, $unlockedKeys);
            $unlockedAt = null;
            if ($unlocked) {
                $rec = $unlockedRecords->firstWhere('badge_key', $key);
                $unlockedAt = $rec?->unlocked_at?->format('d/m/Y');
                $badgesSummary[$bInfo['tier']]++;
            }
            $badgesSummary['list'][] = array_merge($bInfo, [
                'key'         => $key,
                'unlocked'    => $unlocked,
                'unlocked_at' => $unlockedAt,
            ]);
        }

        // Desglose de puntos por categoría (solo referencias válidas)
        $allPoints = $this->pointsQuery($user, $peiProfileId)->get();
        $validPoints = $allPoints->filter(fn (GamificationPoint $point) => $this->referenceExists(
            $point->reference_type,
            $point->reference_id
        ));
        $orphanedCount = $allPoints->count() - $validPoints->count();

        $byAction = $validPoints
            ->groupBy('action_type')
            ->map(fn ($group) => (object) [
                'total' => $group->sum('points'),
                'count' => $group->count(),
            ]);

        // Historial reciente (incluye inválidos marcados para auditoría)
        $recentHistory = $this->pointsQuery($user, $peiProfileId)
            ->latest()
            ->take(10)
            ->get();

        return [
            'total_points'    => $totalPoints,
            'orphaned_count'  => $orphanedCount,
            'level_number'    => $currentLevelNum,
            'level_name'      => $currentLevel['name'],
            'level_icon'      => $currentLevel['icon'],
            'level_badge'     => $currentLevel['badge'],
            'next_level_name' => $nextLevel['name'],
            'progress_pct'    => $progressPct,
            'badges_summary'  => $badgesSummary,
            'by_action'       => $byAction,
            'recent_history'  => $recentHistory,
        ];
    }
}
