<?php

namespace App\Application\Bioestadistica\Reports;

use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportBuilder
{
    public const MAX_ROWS = 5000;

    public const MAX_ROWS_CONSOLIDADO = 20000;

    private const DIMENSIONS = [
        'departamento' => [
            'select' => 'g.departamento_nombre AS departamento',
            'group' => 'g.departamento_id, g.departamento_nombre',
            'order' => 'g.departamento_nombre',
            'label' => 'Departamento-región',
        ],
        'distrito' => [
            'select' => 'g.distrito_nombre AS distrito',
            'group' => 'g.distrito_id, g.distrito_nombre',
            'order' => 'g.distrito_nombre',
            'label' => 'Distrito',
        ],
        'establecimiento' => [
            'select' => 'g.establecimiento_nombre AS establecimiento',
            'group' => 'g.establecimiento_id, g.establecimiento_nombre',
            'order' => 'g.establecimiento_nombre',
            'label' => 'Establecimiento',
        ],
        'microred' => [
            'select' => 'g.microred_nombre AS microred',
            'group' => 'g.microred_id, g.microred_nombre',
            'order' => 'g.microred_nombre',
            'label' => 'Microrred',
        ],
        'tipo_establecimiento' => [
            'select' => 'g.tipo_establecimiento_nombre AS tipo_establecimiento',
            'group' => 'g.tipo_establecimiento_id, g.tipo_establecimiento_nombre',
            'order' => 'g.tipo_establecimiento_nombre',
            'label' => 'Tipo de establecimiento',
        ],
        'grado_complejidad' => [
            'select' => 'g.grado_complejidad_codigo AS grado_complejidad',
            'group' => 'g.grado_complejidad_id, g.grado_complejidad_codigo',
            'order' => 'g.grado_complejidad_codigo',
            'label' => 'Grado de complejidad',
        ],
        'area_gestion' => [
            'select' => 'g.area_gestion_nombre AS area_gestion',
            'group' => 'g.area_gestion_id, g.area_gestion_nombre',
            'order' => 'g.area_gestion_nombre',
            'label' => 'Área de gestión',
        ],
        'periodo' => [
            'select' => "LPAD(v.periodo_mes::text, 2, '0') || '/' || v.periodo_anio::text AS periodo",
            'group' => 'v.periodo_anio, v.periodo_mes',
            'order' => 'v.periodo_anio, v.periodo_mes',
            'label' => 'Período',
        ],
        'anio' => [
            'select' => 'v.periodo_anio AS anio',
            'group' => 'v.periodo_anio',
            'order' => 'v.periodo_anio',
            'label' => 'Año',
        ],
        'mes' => [
            'select' => 'v.periodo_mes AS mes',
            'group' => 'v.periodo_mes',
            'order' => 'v.periodo_mes',
            'label' => 'Mes',
        ],
        'catalogo_item' => [
            'select' => 'COALESCE(pr.nombre, v.catalog_item_id::text) AS catalogo_item',
            'group' => 'v.catalog_item_id, pr.nombre',
            'order' => 'pr.nombre',
            'label' => 'Prestaciones',
        ],
        'estructura_departamento' => [
            'select' => 'COALESCE(ed.nombre, \'—\') AS estructura_departamento',
            'group' => 'ed.id, ed.nombre',
            'order' => 'ed.nombre',
            'label' => 'Departamento',
        ],
        'estructura_servicio' => [
            'select' => 'COALESCE(es.nombre, \'—\') AS estructura_servicio',
            'group' => 'es.id, es.nombre',
            'order' => 'es.nombre',
            'label' => 'Servicio',
        ],
        'variable' => [
            'select' => "COALESCE(var.codigo || ' — ' || var.nombre, '—') AS variable",
            'group' => 'var.id, var.codigo, var.nombre',
            'order' => 'var.codigo, var.nombre',
            'label' => 'Variable',
        ],
        'tipo_prestacion' => [
            'select' => 'COALESCE(vd.nombre, \'—\') AS tipo_prestacion',
            'group' => 'vd.id, vd.nombre',
            'order' => 'vd.nombre',
            'label' => 'Tipo de prestaciones',
        ],
        'campo' => [
            'select' => 'COALESCE(fld.label, v.field_code) AS campo',
            'group' => 'v.field_id, fld.label, v.field_code',
            'order' => 'fld.label, v.field_code',
            'label' => 'Campo / especialidad',
        ],
        'prestador' => [
            'select' => 'COALESCE(est.situacion_inmueble, \'—\') AS prestador',
            'group' => 'est.situacion_inmueble',
            'order' => 'est.situacion_inmueble',
            'label' => 'Prestador',
        ],
    ];

    private const AGGREGATIONS = [
        'sum' => 'SUM(v.valor)',
        'avg' => 'AVG(v.valor)',
        'count' => 'COUNT(v.valor)',
        'count_distinct' => 'COUNT(DISTINCT v.valor)',
        'max' => 'MAX(v.valor)',
        'min' => 'MIN(v.valor)',
    ];

    public function __construct(private ReportDefinitionValidator $validator)
    {
    }

    public function execute(array $definition, User $user, array $overrides = []): array
    {
        $definition = $this->validator->validate($this->mergeOverrides($definition, $overrides));
        $consolidado = (bool) ($definition['consolidado'] ?? false);
        if (! $consolidado) {
            $definition = $this->resolveIndicatorSource($definition);
            if (empty($definition['form']) || empty($definition['field'])) {
                throw ValidationException::withMessages([
                    'definicion' => 'No se pudo resolver una fuente numérica ejecutable.',
                ]);
            }
        }

        $from = PeriodContext::normalize($definition['filtros']['periodo_desde'] ?? null);
        $to = PeriodContext::normalize($definition['filtros']['periodo_hasta'] ?? $from);
        if ($from['anio'] * 100 + $from['mes'] > $to['anio'] * 100 + $to['mes']) {
            throw ValidationException::withMessages([
                'periodo_desde' => 'El período desde no puede ser posterior al período hasta.',
            ]);
        }
        $definition['filtros']['periodo_desde'] = $from;
        $definition['filtros']['periodo_hasta'] = $to;

        $maxRows = $consolidado ? self::MAX_ROWS_CONSOLIDADO : self::MAX_ROWS;
        $limit = min((int) $definition['limit'], $maxRows);
        $query = $this->baseQuery($definition, $user);
        $dimensions = $definition['dimensions'];
        $selects = [];
        $groups = [];
        foreach ($dimensions as $dimension) {
            $selects[] = self::DIMENSIONS[$dimension]['select'];
            $groups[] = self::DIMENSIONS[$dimension]['group'];
        }
        $agg = self::AGGREGATIONS[$definition['agg']];
        $selects[] = "{$agg} AS valor";
        $query->selectRaw(implode(', ', $selects));
        if ($groups) {
            $query->groupByRaw(implode(', ', $groups));
        }
        $this->applyOrder($query, $definition);

        $totalQuery = $this->baseQuery($definition, $user);
        $totalValue = $totalQuery->selectRaw("{$agg} AS valor")->value('valor');
        $unbounded = (clone $query)->limit($limit + 1)->get();
        $truncated = $unbounded->count() > $limit;
        $rows = $unbounded->take($limit)->map(function ($row) use ($dimensions) {
            $item = [];
            foreach ($dimensions as $dimension) {
                $item[$dimension] = $row->{$dimension};
            }
            $item['valor'] = $row->valor === null ? null : (float) $row->valor;

            return $item;
        })->values()->all();

        $columns = [];
        foreach ($dimensions as $dimension) {
            $columns[] = ['key' => $dimension, 'label' => self::DIMENSIONS[$dimension]['label']];
        }
        $columns[] = ['key' => 'valor', 'label' => $definition['label'] ?: 'Valor'];

        return [
            'columns' => $columns,
            'rows' => $rows,
            'totals' => $definition['totales']
                ? ['valor' => $totalValue === null ? null : (float) $totalValue]
                : null,
            'meta' => [
                'periodo_label' => PeriodContext::label($from, $to),
                'periodo_desde' => $from,
                'periodo_hasta' => $to,
                'filtros' => $this->describeFilters($definition['filtros']),
                'cobertura' => $this->coverage($definition, $user),
                'truncated' => $truncated,
                'limit' => $limit,
                'max_rows' => $maxRows,
                'row_count' => count($rows),
                'agg' => $definition['agg'],
                'consolidado' => $consolidado,
                'form' => $definition['form'],
                'field' => $definition['field'],
                'metric' => $definition['metric'],
                'indicator' => $definition['indicator'],
            ],
        ];
    }

    public function chartPayload(array $result, string $label, string $dimension): array
    {
        return [
            'labels' => array_map(
                fn (array $row) => (string) ($row[$dimension] ?? ''),
                $result['rows']
            ),
            'datasets' => [[
                'label' => $label,
                'data' => array_map(fn (array $row) => $row['valor'], $result['rows']),
            ]],
        ];
    }

    private function baseQuery(array $definition, User $user): Builder
    {
        $consolidado = (bool) ($definition['consolidado'] ?? false);
        $dimensions = $definition['dimensions'] ?? [];

        $query = DB::connection('pgsql')
            ->table('bioestadistica.v_valores_numericos as v')
            ->join('bioestadistica.v_establecimientos_geo as g', 'g.establecimiento_id', '=', 'v.establecimiento_id')
            ->where('v.estado', $definition['filtros']['estado_record'] ?? 'aprobado');

        if ($consolidado) {
            $query->whereNotNull('v.metric_code')
                ->whereNotNull('v.catalog_item_id');
        } else {
            $query->where('v.formulario_codigo', $definition['form'])
                ->where('v.field_code', $definition['field']);
            if (($definition['metric'] ?? null) === null) {
                $query->whereNull('v.metric_code');
            } else {
                $query->where('v.metric_code', $definition['metric']);
            }
        }

        $needsRecord = (bool) array_intersect($dimensions, [
            'estructura_departamento', 'estructura_servicio', 'campo', 'variable', 'tipo_prestacion', 'catalogo_item',
        ]);
        if ($needsRecord || $consolidado) {
            $query->leftJoin('bioestadistica.records as rec', 'rec.id', '=', 'v.record_id');
        }
        if (array_intersect($dimensions, ['estructura_departamento', 'estructura_servicio'])) {
            $query->leftJoin('bioestadistica.estructura_departamentos as ed', 'ed.id', '=', 'rec.estructura_departamento_id')
                ->leftJoin('bioestadistica.estructura_servicios as es', 'es.id', '=', 'rec.estructura_servicio_id');
        }
        if (array_intersect($dimensions, ['catalogo_item', 'variable', 'tipo_prestacion'])) {
            $query->leftJoin('bioestadistica.prestaciones as pr', 'pr.id', '=', 'v.catalog_item_id');
        }
        if (array_intersect($dimensions, ['variable', 'tipo_prestacion'])) {
            $query->leftJoin('bioestadistica.variable_detalles as vd', 'vd.id', '=', 'pr.detalle_id')
                ->leftJoin('bioestadistica.variables as var', 'var.id', '=', 'vd.variable_id');
        }
        if (in_array('campo', $dimensions, true)) {
            $query->leftJoin('bioestadistica.fields as fld', 'fld.id', '=', 'v.field_id');
        }
        if (in_array('prestador', $dimensions, true)) {
            $query->leftJoin('bioestadistica.establecimientos as est', 'est.id', '=', 'v.establecimiento_id');
        }

        $from = $definition['filtros']['periodo_desde'];
        $to = $definition['filtros']['periodo_hasta'];
        $query->whereRaw('(v.periodo_anio * 100 + v.periodo_mes) BETWEEN ? AND ?', [
            $from['anio'] * 100 + $from['mes'],
            $to['anio'] * 100 + $to['mes'],
        ]);

        foreach ([
            'establecimiento_id' => 'v.establecimiento_id',
            'departamento_id' => 'g.departamento_id',
            'distrito_id' => 'g.distrito_id',
            'microred_id' => 'g.microred_id',
            'tipo_establecimiento_id' => 'g.tipo_establecimiento_id',
            'grado_complejidad_id' => 'g.grado_complejidad_id',
            'area_gestion_id' => 'g.area_gestion_id',
        ] as $filter => $column) {
            if (isset($definition['filtros'][$filter])) {
                $query->where($column, $definition['filtros'][$filter]);
            }
        }
        if (! empty($definition['filtros']['catalog_item_ids'])) {
            $query->whereIn('v.catalog_item_id', $definition['filtros']['catalog_item_ids']);
        }

        $this->applyScope($query, $user);

        return $query;
    }

    public function applyScope(Builder $query, User $user, string $column = 'v.establecimiento_id'): void
    {
        if (Record::userHasGlobalAccess($user)) {
            return;
        }
        $ids = Record::assignedEstablishmentIds($user);
        $query->whereIn($column, $ids ?: [0]);
    }

    private function applyOrder(Builder $query, array $definition): void
    {
        $orders = $definition['order_by'] ?: [];
        if ($orders === []) {
            if ($definition['dimensions']) {
                foreach ($definition['dimensions'] as $dimension) {
                    $query->orderByRaw(self::DIMENSIONS[$dimension]['order']);
                }
            }

            return;
        }
        foreach ($orders as $order) {
            $dir = strtolower($order['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
            if ($order['ref'] === 'valor') {
                $query->orderBy('valor', $dir);
                continue;
            }
            $sql = self::DIMENSIONS[$order['ref']]['order'] ?? null;
            if ($sql) {
                $query->orderByRaw("{$sql} {$dir}");
            }
        }
    }

    private function resolveIndicatorSource(array $definition): array
    {
        if (empty($definition['indicator']) || (! empty($definition['form']) && ! empty($definition['field']))) {
            return $definition;
        }
        $indicator = Indicador::activos()->where('codigo', $definition['indicator'])->first();
        $expression = $indicator?->formulas()->first()?->expresion ?? [];
        if (in_array($expression['op'] ?? null, ReportDefinitionValidator::AGGREGATIONS, true)
            && ! empty($expression['form']) && ! empty($expression['field'])) {
            $definition['form'] = $expression['form'];
            $definition['field'] = $expression['field'];
            $definition['metric'] = $expression['metric'] ?? null;
            $definition['agg'] = $expression['op'];
        }

        return $definition;
    }

    private function mergeOverrides(array $definition, array $overrides): array
    {
        if ($overrides === []) {
            return $definition;
        }
        $definition['filtros'] = array_merge($definition['filtros'] ?? [], $overrides);

        return $definition;
    }

    private function describeFilters(array $filters): array
    {
        $labels = [];
        foreach ($filters as $key => $value) {
            if (in_array($key, ['periodo_desde', 'periodo_hasta'], true)) {
                continue;
            }
            $labels[$key] = is_array($value) ? implode(', ', $value) : (string) $value;
        }

        return $labels;
    }

    private function coverage(array $definition, User $user): array
    {
        $from = $definition['filtros']['periodo_desde'];
        $to = $definition['filtros']['periodo_hasta'];
        $establishments = DB::connection('pgsql')->table('bioestadistica.v_establecimientos_geo as g');
        foreach ([
            'establecimiento_id', 'departamento_id', 'distrito_id', 'microred_id',
            'tipo_establecimiento_id', 'grado_complejidad_id', 'area_gestion_id',
        ] as $filter) {
            if (isset($definition['filtros'][$filter])) {
                $establishments->where("g.{$filter}", $definition['filtros'][$filter]);
            }
        }
        if (! Record::userHasGlobalAccess($user)) {
            $ids = Record::assignedEstablishmentIds($user);
            $establishments->whereIn('g.establecimiento_id', $ids ?: [0]);
        }
        $expectedEstablishments = $establishments->distinct()->count('g.establecimiento_id');
        $periodCount = (($to['anio'] - $from['anio']) * 12) + $to['mes'] - $from['mes'] + 1;
        $expected = $expectedEstablishments * max(1, $periodCount);

        $reportedQuery = DB::connection('pgsql')
            ->table('bioestadistica.records as r')
            ->join('bioestadistica.formularios as f', 'f.id', '=', 'r.formulario_id')
            ->join('bioestadistica.v_establecimientos_geo as g', 'g.establecimiento_id', '=', 'r.establecimiento_id')
            ->whereNull('r.deleted_at')
            ->where('r.estado', $definition['filtros']['estado_record'] ?? 'aprobado')
            ->whereRaw('(r.periodo_anio * 100 + r.periodo_mes) BETWEEN ? AND ?', [
                $from['anio'] * 100 + $from['mes'],
                $to['anio'] * 100 + $to['mes'],
            ]);
        if (! empty($definition['form']) && empty($definition['consolidado'])) {
            $reportedQuery->where('f.codigo', $definition['form']);
        }
        foreach ([
            'establecimiento_id' => 'r.establecimiento_id',
            'departamento_id' => 'g.departamento_id',
            'distrito_id' => 'g.distrito_id',
            'microred_id' => 'g.microred_id',
            'tipo_establecimiento_id' => 'g.tipo_establecimiento_id',
            'grado_complejidad_id' => 'g.grado_complejidad_id',
            'area_gestion_id' => 'g.area_gestion_id',
        ] as $filter => $column) {
            if (isset($definition['filtros'][$filter])) {
                $reportedQuery->where($column, $definition['filtros'][$filter]);
            }
        }
        if (! Record::userHasGlobalAccess($user)) {
            $ids = Record::assignedEstablishmentIds($user);
            $reportedQuery->whereIn('r.establecimiento_id', $ids ?: [0]);
        }
        $reported = (int) $reportedQuery
            ->selectRaw('COUNT(DISTINCT (r.establecimiento_id, r.formulario_id, r.periodo_anio, r.periodo_mes)) AS total')
            ->value('total');

        return [
            'esperados' => $expected,
            'informados' => $reported,
            'porcentaje' => $expected > 0 ? round(($reported / $expected) * 100, 2) : null,
        ];
    }
}
