<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Estadistica\SiessTerritorialTrait;

class DcpPresupuesto extends Model
{
    use SiessTerritorialTrait;

    protected $table = 'estadistica.dcp_presupuesto';

    protected $fillable = [
        'extracto_id', 'periodo_id', 'organigrama_id', 'locality_id',
        'tipo', 'concepto',
        'objeto_gasto', 'presupuestado', 'ejecutado',
    ];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    public static function resumenPorPeriodo(int $periodoId): array
    {
        $ingresos = self::where('periodo_id', $periodoId)->where('tipo', 'ingreso');
        $egresos  = self::where('periodo_id', $periodoId)->where('tipo', 'egreso');

        return [
            'ingresos_presupuestados' => $ingresos->sum('presupuestado'),
            'ingresos_ejecutados'     => $ingresos->sum('ejecutado'),
            'egresos_presupuestados'  => $egresos->sum('presupuestado'),
            'egresos_ejecutados'      => $egresos->sum('ejecutado'),
            'pct_ejecucion_ingresos'  => $ingresos->sum('presupuestado') > 0
                ? round($ingresos->sum('ejecutado') / $ingresos->sum('presupuestado') * 100, 2) : 0,
            'pct_ejecucion_egresos'   => $egresos->sum('presupuestado') > 0
                ? round($egresos->sum('ejecutado') / $egresos->sum('presupuestado') * 100, 2) : 0,
        ];
    }

    public static function evolucionAnual(int $anio): \Illuminate\Support\Collection
    {
        return self::join('estadistica.siess_periodos as p', 'p.id', '=', 'estadistica.dcp_presupuesto.periodo_id')
            ->where('p.anio', $anio)
            ->where('p.tipo', 'mensual')
            ->selectRaw('p.mes, estadistica.dcp_presupuesto.tipo, SUM(presupuestado) as presupuestado, SUM(ejecutado) as ejecutado')
            ->groupBy('p.mes', 'estadistica.dcp_presupuesto.tipo')
            ->orderBy('p.mes')
            ->get();
    }
}
