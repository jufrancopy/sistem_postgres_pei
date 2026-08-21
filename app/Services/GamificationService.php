<?php

namespace App\Services;

use App\Models\User;
use App\Models\HomeConfiguration;
use App\Models\Gamification\GamificationPoint;
use App\Models\Gamification\GamificationBadge;
use App\Models\Gamification\UserLogin;
use App\Admin\Planificacion\Pei\PeiProfile;
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
        ?string $peiProfileId = null,
        bool $evaluateBadges = true
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

        // Evitar otorgar más de 1 acceso diario por usuario por día calendario
        if ($actionType === 'daily_login') {
            $today = Carbon::today()->toDateString();
            $existsToday = GamificationPoint::where('user_id', $user->id)
                ->where('action_type', 'daily_login')
                ->whereDate('created_at', $today)
                ->exists();
            if ($existsToday) {
                return null;
            }
        }

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

        if ($evaluateBadges) {
            $this->evaluateBadges($user, $peiProfileId);
        }

        return $record;
    }

    /**
     * Inserta puntos en lote (recálculo masivo). No evalúa insignias por fila.
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function insertPointsBatch(array $rows): int
    {
        if ($rows === []) {
            return 0;
        }

        $now = now()->toDateTimeString();
        $prepared = [];

        foreach ($rows as $row) {
            $prepared[] = [
                'user_id'        => $row['user_id'],
                'pei_profile_id' => $row['pei_profile_id'] ?? null,
                'points'         => $row['points'],
                'action_type'    => $row['action_type'],
                'description'    => $row['description'],
                'reference_type' => $row['reference_type'] ?? null,
                'reference_id'   => isset($row['reference_id']) ? (string) $row['reference_id'] : null,
                'created_at'     => $row['created_at'] ?? $now,
                'updated_at'     => $row['updated_at'] ?? $now,
            ];
        }

        $inserted = 0;
        foreach (array_chunk($prepared, 500) as $chunk) {
            GamificationPoint::insert($chunk);
            $inserted += count($chunk);
        }

        return $inserted;
    }

    /**
     * Evalúa insignias usando agregados SQL (rápido post-recálculo).
     */
    public function evaluateBadgesFast(User $user, ?string $peiProfileId = null): void
    {
        $catalog = GamificationBadge::getCatalog();
        $userPoints = (int) GamificationPoint::where('user_id', $user->id)->sum('points');

        $counts = GamificationPoint::where('user_id', $user->id)
            ->selectRaw('action_type, COUNT(*) as total')
            ->groupBy('action_type')
            ->pluck('total', 'action_type');

        $commentsCount  = (int) ($counts['comment_created'] ?? 0);
        $fodaCount      = (int) ($counts['foda_analisis'] ?? 0);
        $cruceCount     = (int) ($counts['foda_cruce'] ?? 0);
        $tasksCompleted = (int) ($counts['task_completed'] ?? 0);
        $riissCount     = (int) ($counts['riiss_evaluacion'] ?? 0);
        $chatCount      = (int) (($counts['chat_message'] ?? 0) + ($counts['chat_context_query'] ?? 0));
        $loginsCount    = UserLogin::where('user_id', $user->id)->count();

        $badgeConditions = [
            'primer_paso'            => $commentsCount >= 1,
            'diagnostico_inicial'    => $fodaCount >= 1,
            'evaluador_novato'       => $riissCount >= 1,
            'ingreso_diario'         => $loginsCount >= 1,
            'colaborador_chat'       => $chatCount >= 1,
            'tactico_eficiente'      => $tasksCompleted >= 10,
            'analista_foda'          => $fodaCount >= 15,
            'formulador_estrategico' => $cruceCount >= 5,
            'evaluador_experto'      => $riissCount >= 10,
            'comunicador'            => $commentsCount >= 20,
            'master_chat'            => $chatCount >= 15,
            'arquitecto_estrategico' => ($cruceCount >= 15 && $fodaCount >= 30),
            'guardian_ejecucion'     => $tasksCompleted >= 50,
            'inspector_salud'        => $riissCount >= 25,
            'leyenda_estrategica'    => $userPoints >= 1000,
        ];

        $existingBadges = GamificationBadge::where('user_id', $user->id)
            ->pluck('badge_key')
            ->all();

        $badgesToInsert = [];
        $unlockedAt = Carbon::now()->toDateTimeString();

        foreach ($badgeConditions as $badgeKey => $conditionMet) {
            if (!$conditionMet || !isset($catalog[$badgeKey]) || in_array($badgeKey, $existingBadges, true)) {
                continue;
            }

            $badgesToInsert[] = [
                'user_id'        => $user->id,
                'pei_profile_id' => $peiProfileId,
                'badge_key'      => $badgeKey,
                'unlocked_at'    => $unlockedAt,
                'created_at'     => $unlockedAt,
                'updated_at'     => $unlockedAt,
            ];
            $existingBadges[] = $badgeKey;
        }

        if ($badgesToInsert !== []) {
            foreach (array_chunk($badgesToInsert, 100) as $chunk) {
                GamificationBadge::insert($chunk);
            }
        }
    }

    /**
     * Verifica si el registro referenciado aún existe en la base de datos.
     */
    public function referenceExists(?string $referenceType, ?string $referenceId): bool
    {
        if (!$referenceType || !$referenceId || !class_exists($referenceType)) {
            return true;
        }

        try {
            // Si el modelo usa UUID y el ID no es un UUID válido de 36 caracteres
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $referenceId)) {
                if (preg_match('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/i', $referenceId, $matches)) {
                    $referenceId = $matches[0];
                } else {
                    return true;
                }
            }

            $model = app($referenceType);

            return $model->newQuery()
                ->where($model->getKeyName(), $referenceId)
                ->exists();
        } catch (\Throwable $e) {
            return true;
        }
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
        $chatCount       = $this->countValidActions($user, 'chat_message') + $this->countValidActions($user, 'chat_context_query');
        $loginsCount     = UserLogin::where('user_id', $user->id)->count();

        $badgeConditions = [
            'primer_paso'            => $commentsCount >= 1,
            'diagnostico_inicial'    => $fodaCount >= 1,
            'evaluador_novato'       => $riissCount >= 1,
            'ingreso_diario'         => $loginsCount >= 1,
            'colaborador_chat'       => $chatCount >= 1,
            'tactico_eficiente'      => $tasksCompleted >= 10,
            'analista_foda'          => $fodaCount >= 15,
            'formulador_estrategico' => $cruceCount >= 5,
            'evaluador_experto'      => $riissCount >= 10,
            'comunicador'            => $commentsCount >= 20,
            'master_chat'            => $chatCount >= 15,
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
    /**
     * Obtiene el ID del PEI master y todos los IDs descendientes de su árbol jerárquico.
     */
    public function getPeiTreeProfileIds(string $masterPeiId): array
    {
        $allIds = [$masterPeiId];
        $currentBatch = [$masterPeiId];

        for ($i = 0; $i < 5; $i++) {
            $nextBatch = PeiProfile::whereIn('parent_id', $currentBatch)->pluck('id')->toArray();
            if (empty($nextBatch)) {
                break;
            }
            $allIds = array_merge($allIds, $nextBatch);
            $currentBatch = $nextBatch;
        }

        return array_unique($allIds);
    }

    /**
     * Query base de puntos del usuario, filtrado por el contexto del árbol PEI activo (o todos si es nulo).
     */
    protected function pointsQuery(User $user, ?string $peiProfileId = null)
    {
        $query = GamificationPoint::where('user_id', $user->id);

        if ($peiProfileId) {
            $allowedProfileIds = $this->getPeiTreeProfileIds($peiProfileId);
            $query->where(function ($q) use ($allowedProfileIds) {
                $q->whereIn('pei_profile_id', $allowedProfileIds)
                  ->orWhereNull('pei_profile_id');
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

    /**
     * Transfiere puntos de reputación entre dos usuarios.
     */
    public function transferPoints(User $donor, User $recipient, int $points, ?string $peiProfileId = null): bool
    {
        if ($points <= 0 || $donor->id === $recipient->id) {
            return false;
        }

        if (!$peiProfileId) {
            $config = HomeConfiguration::first();
            $peiProfileId = $config?->pei_profile_id;
        }

        $donorBalance = $this->getUserTotalPoints($donor, $peiProfileId);
        if ($donorBalance < $points) {
            return false;
        }

        DB::transaction(function () use ($donor, $recipient, $points, $peiProfileId) {
            // Descuenta puntos al donante
            GamificationPoint::create([
                'user_id'        => $donor->id,
                'pei_profile_id' => $peiProfileId,
                'points'         => -$points,
                'action_type'    => 'donacion_enviada',
                'description'    => "Regalo de {$points} pts enviado a {$recipient->name}",
                'reference_type' => User::class,
                'reference_id'   => (string)$recipient->id,
            ]);

            // Suma puntos al destinatario
            GamificationPoint::create([
                'user_id'        => $recipient->id,
                'pei_profile_id' => $peiProfileId,
                'points'         => $points,
                'action_type'    => 'donacion_recibida',
                'description'    => "Regalo de {$points} pts recibido de {$donor->name}",
                'reference_type' => User::class,
                'reference_id'   => (string)$donor->id,
            ]);

            $this->evaluateBadges($donor, $peiProfileId);
            $this->evaluateBadges($recipient, $peiProfileId);
        });

        return true;
    }

    /**
     * Otorgar puntos masivos de reconocimiento a un equipo de trabajo (Grupo).
     */
    public function rewardGroup(
        \App\Admin\Globales\Group $group,
        int $points,
        string $title,
        ?string $description,
        bool $isRetroactive,
        User $admin
    ): \App\Models\Gamification\GroupReward {
        return DB::transaction(function () use ($group, $points, $title, $description, $isRetroactive, $admin) {
            $reward = \App\Models\Gamification\GroupReward::create([
                'group_id'       => $group->id,
                'created_by'     => $admin->id,
                'points'         => $points,
                'title'          => $title,
                'description'    => $description,
                'is_retroactive' => $isRetroactive,
            ]);

            // Obtener todos los integrantes del grupo y sus subgrupos subordinados
            $subGroupIds = \App\Admin\Globales\Group::where('parent_id', $group->id)
                ->orWhere('id', $group->id)
                ->pluck('id');

            $groups = \App\Admin\Globales\Group::with('members')->whereIn('id', $subGroupIds)->get();
            $memberIds = collect();
            foreach ($groups as $g) {
                $memberIds = $memberIds->merge($g->members->pluck('id'));
            }
            $directMemberIds = User::whereIn('group_id', $subGroupIds)->pluck('id');
            $allUserIds = $memberIds->merge($directMemberIds)->unique();

            $members = User::whereIn('id', $allUserIds)->get();

            foreach ($members as $member) {
                $exists = GamificationPoint::where('user_id', $member->id)
                    ->where('reference_type', \App\Models\Gamification\GroupReward::class)
                    ->where('reference_id', (string)$reward->id)
                    ->exists();

                if (!$exists) {
                    $point = GamificationPoint::create([
                        'user_id'        => $member->id,
                        'pei_profile_id' => null,
                        'points'         => $points,
                        'action_type'    => 'group_award',
                        'description'    => "Premio de Equipo ({$group->name}): {$title}" . ($description ? " — {$description}" : ""),
                        'reference_type' => \App\Models\Gamification\GroupReward::class,
                        'reference_id'   => (string)$reward->id,
                    ]);

                    \App\Models\SystemNotification::crearPuntosManual(
                        $member->id,
                        $points,
                        "Premio de Reconocimiento a tu Equipo {$group->name}: {$title}",
                        "PEI IPS",
                        $admin->name,
                        $point->id
                    );

                    $this->evaluateBadges($member);
                }
            }

            return $reward;
        });
    }

    /**
     * Acredita automáticamente los premios de grupo retroactivos a un nuevo integrante sumado al equipo.
     */
    public function syncNewMemberGroupRewards(\App\Admin\Globales\Group $group, User $user): int
    {
        $rewards = \App\Models\Gamification\GroupReward::where('group_id', $group->id)
            ->where('is_retroactive', true)
            ->get();

        $countAwarded = 0;

        foreach ($rewards as $reward) {
            $alreadyHas = GamificationPoint::where('user_id', $user->id)
                ->where('reference_type', \App\Models\Gamification\GroupReward::class)
                ->where('reference_id', (string)$reward->id)
                ->exists();

            if (!$alreadyHas) {
                $point = GamificationPoint::create([
                    'user_id'        => $user->id,
                    'pei_profile_id' => null,
                    'points'         => $reward->points,
                    'action_type'    => 'group_award',
                    'description'    => "Premio de Equipo ({$group->name}): {$reward->title} (Acreditación por incorporación al grupo)",
                    'reference_type' => \App\Models\Gamification\GroupReward::class,
                    'reference_id'   => (string)$reward->id,
                ]);

                \App\Models\SystemNotification::crearPuntosManual(
                    $user->id,
                    $reward->points,
                    "Reconocimiento de Equipo ({$group->name}): Acreditado por incorporación al grupo",
                    "PEI IPS",
                    "Sistema de Gamificación",
                    $point->id
                );

                $countAwarded++;
            }
        }

        if ($countAwarded > 0) {
            $this->evaluateBadges($user);
        }

        return $countAwarded;
    }
}
