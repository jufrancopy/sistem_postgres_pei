<?php

namespace App\Models\PlanMaestro;

use Illuminate\Database\Eloquent\Model;

class PlanAccion extends Model
{
    protected $table = 'plan_acciones';

    protected $fillable = [
        'plan_id', 'eje_id', 'codigo', 'momento', 'accion',
        'justificacion', 'kpi', 'plazo', 'responsable', 'estado', 'detalle', 'orden',
    ];

    // Momentos disponibles
    const MOMENTOS = [
        'T0' => ['label' => 'Ejecutado / En Curso', 'short' => 'T0', 'color' => '#34d399'],
        'T1' => ['label' => 'Días 1–30',            'short' => 'T1', 'color' => '#67e8f9'],
        'T2' => ['label' => 'Días 31–60',           'short' => 'T2', 'color' => '#a78bfa'],
        'T3' => ['label' => 'Días 61–100',          'short' => 'T3', 'color' => '#fbbf24'],
        'T4' => ['label' => 'Meses 4–6',            'short' => 'T4', 'color' => '#fb923c'],
        'T5' => ['label' => 'Meses 7–9',            'short' => 'T5', 'color' => '#f87171'],
        'TX' => ['label' => 'Transversal',          'short' => 'TX', 'color' => '#94a3b8'],
    ];

    // Clasificación de estados
    const ESTADO_GRUPOS = [
        'EJECUTADO'  => ['label' => 'Ejecutado',  'color' => '#34d399'],
        'EN CURSO'   => ['label' => 'En Curso',   'color' => '#fbbf24'],
        'PENDIENTE'  => ['label' => 'Pendiente',  'color' => '#f87171'],
    ];

    public function getEstadoGrupoAttribute(): string
    {
        $e = strtoupper($this->estado ?? '');
        if (str_starts_with($e, 'EJECUTADO') || str_starts_with($e, 'CERRADO')) return 'EJECUTADO';
        if (str_starts_with($e, 'EN ') || str_starts_with($e, 'COMPROMETIDO') ||
            str_starts_with($e, 'BAJO ') || str_starts_with($e, 'INTERVENCIÓN') ||
            str_starts_with($e, 'ANÁLISIS') || str_starts_with($e, 'ORDENAD')) return 'EN CURSO';
        return 'PENDIENTE';
    }

    public function plan()
    {
        return $this->belongsTo(PlanMaestro::class, 'plan_id');
    }

    public function eje()
    {
        return $this->belongsTo(PlanEje::class, 'eje_id');
    }
}
