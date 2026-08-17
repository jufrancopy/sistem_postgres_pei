<?php

namespace App\Application\Bioestadistica\Statistics;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StatisticsEngine
{
    public function describe(array $source, array $filters = []): array
    {
        $query = $this->sourceQuery($source, $filters);
        $row = $query->selectRaw(<<<'SQL'
            COUNT(valor)::integer AS frecuencia_absoluta,
            AVG(valor)::numeric AS media,
            PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY valor)::numeric AS mediana,
            MODE() WITHIN GROUP (ORDER BY valor)::numeric AS moda,
            STDDEV_SAMP(valor)::numeric AS desviacion_estandar,
            VAR_SAMP(valor)::numeric AS varianza,
            PERCENTILE_CONT(0.25) WITHIN GROUP (ORDER BY valor)::numeric AS q1,
            PERCENTILE_CONT(0.75) WITHIN GROUP (ORDER BY valor)::numeric AS q3,
            (MAX(valor) - MIN(valor))::numeric AS rango,
            CASE WHEN AVG(valor) = 0 THEN NULL
                 ELSE STDDEV_SAMP(valor) / AVG(valor) END::numeric AS coeficiente_variacion
            SQL)->first();

        return collect((array) $row)->map(
            fn ($value) => is_numeric($value) ? (float) $value : $value
        )->all();
    }

    public function trend(array $source, array $filters = []): array
    {
        $rows = $this->sourceQuery($source, $filters)
            ->selectRaw('periodo_anio, periodo_mes, SUM(valor)::numeric AS valor')
            ->groupBy('periodo_anio', 'periodo_mes')
            ->orderBy('periodo_anio')
            ->orderBy('periodo_mes')
            ->get();

        $previous = null;
        return $rows->map(function ($row) use (&$previous) {
            $value = (float) $row->valor;
            $result = [
                'anio' => $row->periodo_anio,
                'mes' => $row->periodo_mes,
                'valor' => $value,
                'variacion_absoluta' => $previous === null ? null : $value - $previous,
                'variacion_porcentual' => $previous === null || $previous == 0.0
                    ? null : (($value - $previous) / $previous) * 100,
            ];
            $previous = $value;
            return $result;
        })->all();
    }

    private function sourceQuery(array $source, array $filters): Builder
    {
        foreach (['form', 'field'] as $required) {
            if (empty($source[$required])) {
                throw ValidationException::withMessages(['source' => "Falta {$required} en la fuente estadística."]);
            }
        }
        $query = DB::connection('pgsql')
            ->table('bioestadistica.v_valores_numericos as v')
            ->join('bioestadistica.v_establecimientos_geo as g', 'g.establecimiento_id', '=', 'v.establecimiento_id')
            ->where('v.formulario_codigo', $source['form'])
            ->where('v.field_code', $source['field'])
            ->where('v.estado', 'aprobado');
        isset($source['metric'])
            ? $query->where('v.metric_code', $source['metric'])
            : $query->whereNull('v.metric_code');

        foreach ([
            'establecimiento_id' => 'v.establecimiento_id',
            'departamento_id' => 'g.departamento_id',
            'distrito_id' => 'g.distrito_id',
            'microred_id' => 'g.microred_id',
        ] as $key => $column) {
            if (isset($filters[$key])) {
                $query->where($column, $filters[$key]);
            }
        }
        if (isset($filters['periodo_desde'], $filters['periodo_hasta'])) {
            $from = $filters['periodo_desde'];
            $to = $filters['periodo_hasta'];
            $query->whereRaw('(v.periodo_anio * 100 + v.periodo_mes) BETWEEN ? AND ?', [
                $from['anio'] * 100 + $from['mes'],
                $to['anio'] * 100 + $to['mes'],
            ]);
        }
        return $query;
    }
}
