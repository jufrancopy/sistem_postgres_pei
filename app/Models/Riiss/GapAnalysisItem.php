<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GapAnalysisItem extends Model
{
    protected $table = 'gap_analysis_items';

    protected $fillable = [
        'evaluacion_id', 'cartera_servicio_id', 'servicio_nombre',
        'grupo_servicio', 'dimension', 'tipo_prestacion', 'especialidad',
        'requerido_para_nivel', 'estado', 'criterio_evaluacion',
        'preguntas_relacionadas', 'respuestas_relacionadas',
        'accion_recomendada', 'prioridad',
    ];

    protected $casts = [
        'requerido_para_nivel'    => 'boolean',
        'preguntas_relacionadas'  => 'array',
        'respuestas_relacionadas' => 'array',
        'prioridad'               => 'integer',
    ];

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function carteraServicio(): BelongsTo
    {
        return $this->belongsTo(CarteraServicio::class);
    }

    public function scopeRequeridos($query)
    {
        return $query->where('requerido_para_nivel', true);
    }

    public function scopeNoCumplen($query)
    {
        return $query->whereIn('estado', ['no_cumple', 'no_verificable']);
    }

    public function scopeCriticos($query)
    {
        return $query->where('prioridad', '>=', 2);
    }

    public function getIconoAttribute(): string
    {
        return match($this->estado) {
            'cumple'         => '✅',
            'no_cumple'      => '❌',
            'no_verificable' => '⚠️',
            'no_aplica'      => '⬜',
            'pendiente'      => '⏳',
            default          => '❓',
        };
    }
}
