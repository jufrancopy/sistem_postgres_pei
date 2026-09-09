<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class Evaluacion extends Model
{
    use SoftDeletes;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'id_establecimiento', 'fecha_evaluacion', 'evaluador_nombre',
        'evaluadores', 'evaluador_telefono', 'evaluador_usuario_institucional',
        'estado', 'porcentaje_cumplimiento', 'clasificacion_resultado',
        'pct_habilitacion', 'clasificacion_habilitacion',
        'observaciones_generales', 'aspectos_positivos', 'metadata', 'pei_profile_id',
        'responsable_nombre', 'responsable_cargo', 'responsable_documento',
        'responsable_telefono', 'responsable_firma', 'responsable_firmado_at',
        'firmas_evaluadores', 'cierre_observaciones', 'cerrado_at', 'cerrado_por_id',
    ];

    protected $casts = [
        'fecha_evaluacion'        => 'date',
        'porcentaje_cumplimiento' => 'decimal:2',
        'pct_habilitacion'        => 'decimal:2',
        'metadata'                => 'array',
        'evaluadores'             => 'array',
        'firmas_evaluadores'      => 'array',
        'responsable_firmado_at'  => 'datetime',
        'cerrado_at'              => 'datetime',
    ];

    public function cerradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cerrado_por_id');
    }

    public function isCerradaConFirmas(): bool
    {
        return !empty($this->responsable_firma) && !empty($this->cerrado_at);
    }

    public function peiProfile(): BelongsTo
    {
        return $this->belongsTo(\App\Admin\Planificacion\Pei\PeiProfile::class, 'pei_profile_id');
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(EvaluacionRespuesta::class);
    }

    public function gapAnalysis(): HasMany
    {
        return $this->hasMany(GapAnalysisItem::class);
    }

    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function getConteoRespuestasAttribute(): array
    {
        return $this->respuestas()
            ->select('estado_cumplimiento', DB::raw('count(*) as total'))
            ->groupBy('estado_cumplimiento')
            ->pluck('total', 'estado_cumplimiento')
            ->toArray();
    }

    public function getConteoGapAttribute(): array
    {
        return $this->gapAnalysis()
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();
    }
}
