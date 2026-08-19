<?php

namespace App\Models\Bioestadistica;

use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Record extends BioestadisticaModel
{
    public const ESTADO_BORRADOR = 'borrador';
    public const ESTADO_ENVIADO = 'enviado';
    public const ESTADO_APROBADO = 'aprobado';
    public const ESTADO_OBJETADO = 'objetado';

    protected $table = 'bioestadistica.records';

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function formulario(): BelongsTo
    {
        return $this->belongsTo(Formulario::class);
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function estructuraDepartamento(): BelongsTo
    {
        return $this->belongsTo(EstructuraDepartamento::class, 'estructura_departamento_id');
    }

    public function estructuraServicio(): BelongsTo
    {
        return $this->belongsTo(EstructuraServicio::class, 'estructura_servicio_id');
    }

    public function corteLabel(): ?string
    {
        if (! $this->estructura_servicio_id) {
            return null;
        }

        return trim(($this->estructuraDepartamento?->nombre ?? '').' / '.($this->estructuraServicio?->nombre ?? ''), ' /')
            ?: null;
    }

    public function values(): HasMany
    {
        return $this->hasMany(RecordValue::class)->with('field');
    }

    public function isEditable(): bool
    {
        return in_array($this->estado, [self::ESTADO_BORRADOR, self::ESTADO_OBJETADO], true);
    }

    public static function estadoLabel(string $estado): string
    {
        return match ($estado) {
            self::ESTADO_BORRADOR => 'Borrador',
            self::ESTADO_ENVIADO => 'Enviado',
            self::ESTADO_APROBADO => 'Aprobado',
            self::ESTADO_OBJETADO => 'Objetado',
            default => ucfirst($estado),
        };
    }

    public static function estadoBadge(string $estado): string
    {
        return match ($estado) {
            self::ESTADO_BORRADOR => 'badge-secondary',
            self::ESTADO_ENVIADO => 'badge-warning',
            self::ESTADO_APROBADO => 'badge-success',
            self::ESTADO_OBJETADO => 'badge-danger',
            default => 'badge-light',
        };
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if (self::userHasGlobalAccess($user)) {
            return $query;
        }

        return $query->whereIn('establecimiento_id', self::assignedEstablishmentIds($user));
    }

    public function isAccessibleBy(User $user): bool
    {
        return self::userHasGlobalAccess($user)
            || in_array($this->establecimiento_id, self::assignedEstablishmentIds($user), true);
    }

    public function submit(int $userId): void
    {
        if (! $this->isEditable()) {
            throw ValidationException::withMessages([
                'record' => 'Solo se pueden enviar registros en borrador u objetados.',
            ]);
        }

        $this->update([
            'estado' => self::ESTADO_ENVIADO,
            'submitted_by' => $userId,
            'submitted_at' => now(),
        ]);
        app(IndicatorCacheService::class)->invalidateForRecord($this);
    }

    public function approve(int $userId): void
    {
        if ($this->estado !== self::ESTADO_ENVIADO) {
            throw ValidationException::withMessages([
                'record' => 'Solo se pueden aprobar registros enviados.',
            ]);
        }

        $this->update([
            'estado' => self::ESTADO_APROBADO,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
        app(IndicatorCacheService::class)->invalidateForRecord($this);
    }

    public function reject(int $userId, string $observacion): void
    {
        if ($this->estado !== self::ESTADO_ENVIADO) {
            throw ValidationException::withMessages([
                'record' => 'Solo se pueden objetar registros enviados.',
            ]);
        }

        $this->update([
            'estado' => self::ESTADO_OBJETADO,
            'observacion' => $observacion,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
        app(IndicatorCacheService::class)->invalidateForRecord($this);
    }

    public static function userHasGlobalAccess(User $user): bool
    {
        return $user->hasAnyRole([
            'Administrador',
            'Analista de Bioestadística',
            'Consultor Bioestadística',
            'Auditor Bioestadística',
        ]);
    }

    public static function assignedEstablishmentIds(User $user): array
    {
        return UsuarioEstablecimiento::where('user_id', $user->id)
            ->pluck('establecimiento_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
