<?php

namespace App\Models\Riiss;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asignacion extends Model
{
    use SoftDeletes;

    protected $table = 'riiss_asignaciones';

    protected $fillable = [
        'id_establecimiento', 'evaluacion_id', 'asignado_por',
        'evaluador_id', 'fecha_limite', 'estado',
        'instrucciones', 'notificado_at', 'pei_profile_id',
    ];

    protected $casts = [
        'fecha_limite'   => 'date',
        'notificado_at'  => 'datetime',
    ];

    public function peiProfile(): BelongsTo
    {
        return $this->belongsTo(\App\Admin\Planificacion\Pei\PeiProfile::class, 'pei_profile_id');
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento', 'id_establecimiento');
    }

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    public function evaluador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluador_id');
    }

    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['pendiente', 'en_progreso']);
    }

    public function scopeDelEvaluador($query, int $userId)
    {
        return $query->where('evaluador_id', $userId);
    }

    public function estaVencida(): bool
    {
        return $this->fecha_limite && $this->fecha_limite->isPast()
            && !in_array($this->estado, ['completada', 'cancelada']);
    }

    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            'pendiente'   => '#f97316',
            'en_progreso' => '#3b82f6',
            'completada'  => '#22c55e',
            'vencida'     => '#ef4444',
            'cancelada'   => '#94a3b8',
            default       => '#6b7280',
        };
    }
}
