<?php

namespace App\Application\Bioestadistica\Indicators;

use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\IndicadorCache;
use App\Models\Bioestadistica\IndicadorFormula;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IndicatorEngine
{
    public function __construct(private FormulaAstValidator $validator)
    {
    }

    public function evaluate(Indicador $indicator, array $context): array
    {
        $context = $this->normalizeContext($context);
        $formula = $this->formulaFor($indicator, $context['periodo_hasta']);
        $this->validator->validate($formula->expresion, $indicator);

        $cached = $this->cachedValue($indicator, $formula, $context);
        if ($cached !== false) {
            return [
                'valor' => $cached,
                'cache' => true,
                'formula_id' => $formula->id,
                'cobertura' => $this->coverage($formula->expresion, $context),
            ];
        }

        $value = $this->evaluateNode($formula->expresion, $context, [$indicator->codigo]);
        $value = $value === null ? null : round($value, $indicator->decimales);
        $this->storeCache($indicator, $formula, $context, $value);

        return [
            'valor' => $value,
            'cache' => false,
            'formula_id' => $formula->id,
            'cobertura' => $this->coverage($formula->expresion, $context),
        ];
    }

    public function evaluateNode(array $node, array $context, array $stack = []): ?float
    {
        if (array_key_exists('const', $node)) {
            return (float) $node['const'];
        }
        if (isset($node['indicator'])) {
            if (in_array($node['indicator'], $stack, true)) {
                throw ValidationException::withMessages(['expresion' => 'Referencia circular detectada al evaluar.']);
            }
            $dependency = Indicador::activos()->where('codigo', $node['indicator'])->firstOrFail();
            return $this->evaluate($dependency, $context)['valor'];
        }

        $op = $node['op'];
        if (in_array($op, ['sum', 'avg', 'count', 'count_distinct', 'max', 'min'], true)) {
            return $this->aggregate($node, $context);
        }
        if ($op === 'hosp_count') {
            return app(\App\Application\Bioestadistica\Hospitalization\HospitalizationService::class)
                ->countMetric($node['metric'], $context, $node['filter'] ?? []);
        }

        $args = array_map(
            fn (array $arg) => $this->evaluateNode($arg, $context, $stack),
            $node['args'] ?? []
        );

        return match ($op) {
            'add' => $this->sumNullable($args),
            'sub' => $args[0] === null || $args[1] === null ? null : $args[0] - $args[1],
            'mul' => in_array(null, $args, true) ? null : array_product($args),
            'div' => $this->divide($args[0], $args[1]),
            'pct' => ($result = $this->divide($args[0], $args[1])) === null ? null : $result * 100,
            'rate' => ($result = $this->divide($args[0], $args[1])) === null
                ? null : $result * (float) $node['factor'],
            'round' => $args[0] === null ? null : round($args[0], $node['decimals']),
            default => throw ValidationException::withMessages(['expresion' => "Operador {$op} no permitido."]),
        };
    }

    private function aggregate(array $node, array $context): ?float
    {
        $query = DB::connection('pgsql')
            ->table('bioestadistica.v_valores_numericos as v')
            ->join(
                'bioestadistica.v_establecimientos_geo as g',
                'g.establecimiento_id',
                '=',
                'v.establecimiento_id'
            )
            ->where('v.formulario_codigo', $node['form'])
            ->where('v.field_code', $node['field'])
            ->where('v.estado', $node['filter']['estado_record'] ?? 'aprobado');

        if (array_key_exists('metric', $node)) {
            $query->where('v.metric_code', $node['metric']);
        } else {
            $query->whereNull('v.metric_code');
        }
        $this->applyContext($query, array_merge($context, $node['filter'] ?? []));
        if (! empty($node['filter']['catalog_item_ids'])) {
            $query->whereIn('v.catalog_item_id', $node['filter']['catalog_item_ids']);
        }

        $expression = match ($node['op']) {
            'sum' => 'SUM(v.valor)',
            'avg' => 'AVG(v.valor)',
            'count' => 'COUNT(v.valor)',
            'count_distinct' => 'COUNT(DISTINCT v.valor)',
            'max' => 'MAX(v.valor)',
            'min' => 'MIN(v.valor)',
        };
        $value = $query->selectRaw("{$expression} AS result")->value('result');

        return $value === null ? null : (float) $value;
    }

    private function applyContext(Builder $query, array $context): void
    {
        [$fromYear, $fromMonth] = $context['periodo_desde'];
        [$toYear, $toMonth] = $context['periodo_hasta'];
        $query->whereRaw('(v.periodo_anio * 100 + v.periodo_mes) BETWEEN ? AND ?', [
            $fromYear * 100 + $fromMonth,
            $toYear * 100 + $toMonth,
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
            if (isset($context[$filter])) {
                $query->where($column, $context[$filter]);
            }
        }
    }

    private function formulaFor(Indicador $indicator, array $period): IndicadorFormula
    {
        $date = CarbonImmutable::create($period[0], $period[1], 1)->endOfMonth();
        $formula = $indicator->formulas()
            ->where(fn ($query) => $query->whereNull('vigente_desde')->orWhere('vigente_desde', '<=', $date))
            ->where(fn ($query) => $query->whereNull('vigente_hasta')->orWhere('vigente_hasta', '>=', $date))
            ->first();
        if (! $formula) {
            throw ValidationException::withMessages(['periodo' => 'El indicador no tiene fórmula vigente para el período.']);
        }
        return $formula;
    }

    private function normalizeContext(array $context): array
    {
        $to = $this->period($context['periodo_hasta'] ?? null, 'periodo_hasta');
        $from = $this->period($context['periodo_desde'] ?? $to, 'periodo_desde');
        if ($from[0] * 100 + $from[1] > $to[0] * 100 + $to[1]) {
            throw ValidationException::withMessages(['periodo_desde' => 'El período desde no puede ser posterior al período hasta.']);
        }
        return array_merge($context, ['periodo_desde' => $from, 'periodo_hasta' => $to]);
    }

    private function period(mixed $value, string $key): array
    {
        $year = is_array($value) ? ($value['anio'] ?? $value[0] ?? null) : null;
        $month = is_array($value) ? ($value['mes'] ?? $value[1] ?? null) : null;
        if (! is_numeric($year) || ! is_numeric($month) || $year < 1990 || $year > 2100 || $month < 1 || $month > 12) {
            throw ValidationException::withMessages([$key => 'El período debe incluir año y mes válidos.']);
        }
        return [(int) $year, (int) $month];
    }

    private function divide(?float $left, ?float $right): ?float
    {
        return $left === null || $right === null || $right == 0.0 ? null : $left / $right;
    }

    /**
     * @param  array<int, float|null>  $args
     */
    private function sumNullable(array $args): ?float
    {
        $present = array_values(array_filter($args, fn ($value) => $value !== null));
        if ($present === []) {
            return null;
        }

        return array_sum($present);
    }

    private function cachedValue(Indicador $indicator, IndicadorFormula $formula, array $context): float|false|null
    {
        if (! $this->cacheable($context)) {
            return false;
        }
        $cache = IndicadorCache::where([
            'indicador_id' => $indicator->id,
            'formula_id' => $formula->id,
            'periodo_anio' => $context['periodo_desde'][0],
            'periodo_mes' => $context['periodo_desde'][1],
            'establecimiento_id' => $context['establecimiento_id'],
        ])->first();

        return $cache ? ($cache->valor === null ? null : (float) $cache->valor) : false;
    }

    private function storeCache(
        Indicador $indicator,
        IndicadorFormula $formula,
        array $context,
        ?float $value
    ): void {
        if (! $this->cacheable($context)) {
            return;
        }
        IndicadorCache::updateOrCreate([
            'indicador_id' => $indicator->id,
            'formula_id' => $formula->id,
            'periodo_anio' => $context['periodo_desde'][0],
            'periodo_mes' => $context['periodo_desde'][1],
            'establecimiento_id' => $context['establecimiento_id'],
        ], ['valor' => $value, 'calculado_at' => now()]);
    }

    private function cacheable(array $context): bool
    {
        return isset($context['establecimiento_id'])
            && $context['periodo_desde'] === $context['periodo_hasta'];
    }

    private function coverage(array $ast, array $context): array
    {
        $forms = $this->formReferences($ast);
        if ($forms === []) {
            return ['esperados' => null, 'informados' => null, 'porcentaje' => null];
        }

        $establishments = DB::connection('pgsql')->table('bioestadistica.v_establecimientos_geo as g');
        foreach ([
            'establecimiento_id', 'departamento_id', 'distrito_id', 'microred_id',
            'tipo_establecimiento_id', 'grado_complejidad_id', 'area_gestion_id',
        ] as $filter) {
            if (isset($context[$filter])) {
                $establishments->where("g.{$filter}", $context[$filter]);
            }
        }
        $expectedEstablishments = $establishments->distinct()->count('g.establecimiento_id');
        $periodCount = (($context['periodo_hasta'][0] - $context['periodo_desde'][0]) * 12)
            + $context['periodo_hasta'][1] - $context['periodo_desde'][1] + 1;
        $expected = $expectedEstablishments * count($forms) * max(1, $periodCount);

        $reportedQuery = DB::connection('pgsql')
            ->table('bioestadistica.records as r')
            ->join('bioestadistica.formularios as f', 'f.id', '=', 'r.formulario_id')
            ->join('bioestadistica.v_establecimientos_geo as g', 'g.establecimiento_id', '=', 'r.establecimiento_id')
            ->whereNull('r.deleted_at')
            ->where('r.estado', 'aprobado')
            ->whereIn('f.codigo', $forms)
            ->whereRaw('(r.periodo_anio * 100 + r.periodo_mes) BETWEEN ? AND ?', [
                $context['periodo_desde'][0] * 100 + $context['periodo_desde'][1],
                $context['periodo_hasta'][0] * 100 + $context['periodo_hasta'][1],
            ]);
        foreach ([
            'establecimiento_id' => 'r.establecimiento_id',
            'departamento_id' => 'g.departamento_id',
            'distrito_id' => 'g.distrito_id',
            'microred_id' => 'g.microred_id',
            'tipo_establecimiento_id' => 'g.tipo_establecimiento_id',
            'grado_complejidad_id' => 'g.grado_complejidad_id',
            'area_gestion_id' => 'g.area_gestion_id',
        ] as $filter => $column) {
            if (isset($context[$filter])) {
                $reportedQuery->where($column, $context[$filter]);
            }
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

    private function formReferences(array $node): array
    {
        $forms = isset($node['form']) ? [$node['form']] : [];
        foreach ($node['args'] ?? [] as $arg) {
            if (is_array($arg)) {
                $forms = [...$forms, ...$this->formReferences($arg)];
            }
        }
        if (isset($node['indicator'])) {
            $dependency = Indicador::with('formulas')->where('codigo', $node['indicator'])->first();
            if ($dependency?->formulas->first()) {
                $forms = [...$forms, ...$this->formReferences($dependency->formulas->first()->expresion)];
            }
        }
        return array_values(array_unique($forms));
    }
}
