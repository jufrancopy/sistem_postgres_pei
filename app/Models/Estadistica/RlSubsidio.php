<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RlSubsidio extends Model
{
    protected $table = 'estadistica.rl_subsidios';

    protected $fillable = [
        'extracto_id', 'periodo_id', 'nro_certificado',
        'fecha_registro', 'fecha_verificacion', 'fecha_liquidacion', 'fecha_pago',
        'diagnostico', 'dias_reposo', 'tipo_reposo', 'es_covid',
        'medio_pago', 'monto', 'empleador_ruc', 'empleador_descripcion', 'actividad',
    ];

    protected $casts = [
        'es_covid'           => 'boolean',
        'fecha_registro'     => 'date',
        'fecha_verificacion' => 'date',
        'fecha_liquidacion'  => 'date',
        'fecha_pago'         => 'date',
    ];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    public static function resumenPorPeriodo(int $periodoId): array
    {
        $base = self::where('periodo_id', $periodoId);
        return [
            'total'           => $base->count(),
            'monto_total'     => $base->sum('monto'),
            'dias_promedio'   => round($base->avg('dias_reposo') ?? 0),
            'covid'           => $base->where('es_covid', true)->count(),
            'primer_reposo'   => $base->where('tipo_reposo', 'primer_reposo')->count(),
            'extensiones'     => $base->where('tipo_reposo', 'extension')->count(),
            'monto_promedio'  => round($base->avg('monto') ?? 0),
        ];
    }
}
