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
                $this->awardPoints($user, 'login_streak', 'Bonificación: Racha de 5 días de acceso continuo', 20);
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

        // Conteos de acciones del usuario
        $commentsCount   = GamificationPoint::where('user_id', $user->id)->where('action_type', 'comment_created')->count();
        $fodaCount       = GamificationPoint::where('user_id', $user->id)->where('action_type', 'foda_analisis')->count();
        $cruceCount      = GamificationPoint::where('user_id', $user->id)->where('action_type', 'foda_cruce')->count();
        $tasksCompleted  = GamificationPoint::where('user_id', $user->id)->where('action_type', 'task_completed')->count();
        $riissCount      = GamificationPoint::where('user_id', $user->id)->where('action_type', 'riiss_evaluacion')->count();
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
     * Obtiene los puntos totales del usuario (filtrado por PEI o global)
     */
    public function getUserTotalPoints(User $user, ?string $peiProfileId = null): int
    {
        $query = GamificationPoint::where('user_id', $user->id);
        if ($peiProfileId) {
            $query->where(function($q) use ($peiProfileId) {
                $q->where('pei_profile_id', $peiProfileId)->orWhereNull('pei_profile_id');
            });
        }
        return (int)$query->sum('points');
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

        // Desglose de puntos por categoría
        $pointsQuery = GamificationPoint::where('user_id', $user->id);
        if ($peiProfileId) {
            $pointsQuery->where(function($q) use ($peiProfileId) {
                $q->where('pei_profile_id', $peiProfileId)->orWhereNull('pei_profile_id');
            });
        }
        $byAction = (clone $pointsQuery)
            ->selectRaw('action_type, SUM(points) as total, COUNT(*) as count')
            ->groupBy('action_type')
            ->get()
            ->keyBy('action_type');

        // Historial reciente de transacciones de puntos
        $recentHistory = (clone $pointsQuery)
            ->latest()
            ->take(10)
            ->get();

        return [
            'total_points'    => $totalPoints,
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
