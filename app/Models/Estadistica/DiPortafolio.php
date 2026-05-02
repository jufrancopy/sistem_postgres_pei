<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiPortafolio extends Model
{
    protected $table = 'estadistica.di_portafolio';

    protected $fillable = [
        'extracto_id', 'periodo_id', 'instrumento', 'modalidad',
        'monto', 'divisa', 'tasa', 'plazo_dias', 'rentabilidad', 'fecha_vencimiento',
    ];

    protected $casts = ['fecha_vencimiento' => 'date'];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    public static function resumenPorPeriodo(int $periodoId): array
    {
        return [
            'monto_total'       => self::where('periodo_id', $periodoId)->sum('monto'),
            'rentabilidad_prom' => round(self::where('periodo_id', $periodoId)->avg('rentabilidad') ?? 0, 4),
            'por_modalidad'     => self::where('periodo_id', $periodoId)
                ->selectRaw('modalidad, COUNT(*) as cantidad, SUM(monto) as monto_total')
                ->groupBy('modalidad')->orderByDesc('monto_total')->get(),
            'por_divisa'        => self::where('periodo_id', $periodoId)
                ->selectRaw('divisa, SUM(monto) as monto_total')
                ->groupBy('divisa')->orderByDesc('monto_total')->get(),
        ];
    }
}
