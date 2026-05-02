<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CauAtencion extends Model
{
    protected $table = 'estadistica.cau_atencion';

    protected $fillable = [
        'extracto_id', 'periodo_id', 'canal', 'total_contactos',
        'tiempo_espera_promedio_seg', 'tiempo_espera_max_seg',
        'transferencias', 'abandonos',
    ];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    public static function resumenPorPeriodo(int $periodoId): array
    {
        $base = self::where('periodo_id', $periodoId);
        $total = $base->sum('total_contactos');
        return [
            'total_contactos'      => $total,
            'total_abandonos'      => $base->sum('abandonos'),
            'total_transferencias' => $base->sum('transferencias'),
            'tasa_abandono_global' => $total > 0
                ? round($base->sum('abandonos') / $total * 100, 2) : 0,
            'tiempo_espera_prom'   => round($base->avg('tiempo_espera_promedio_seg') ?? 0),
            'por_canal'            => self::where('periodo_id', $periodoId)
                ->selectRaw('canal, total_contactos, abandonos, tasa_abandono, tiempo_espera_promedio_seg')
                ->orderByDesc('total_contactos')->get(),
        ];
    }
}
