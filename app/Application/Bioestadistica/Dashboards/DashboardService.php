<?php

namespace App\Application\Bioestadistica\Dashboards;

use App\Application\Bioestadistica\Indicators\IndicatorEngine;
use App\Application\Bioestadistica\Reports\PeriodContext;
use App\Application\Bioestadistica\Reports\ReportBuilder;
use App\Application\Bioestadistica\Reports\ReportDefinitionValidator;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\DashboardWidget;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\Reporte;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class DashboardService
{
    public function __construct(
        private ReportBuilder $builder,
        private ReportDefinitionValidator $validator,
        private IndicatorEngine $indicators
    ) {
    }

    public function resolveForUser(User $user, ?Dashboard $preferred = null): ?Dashboard
    {
        if ($preferred) {
            $this->assertVisible($preferred, $user);
            return $preferred->load('widgets');
        }

        $personalDefault = Dashboard::query()
            ->where('user_id', $user->id)
            ->where('es_default', true)
            ->with('widgets')
            ->first();
        if ($personalDefault) {
            return $personalDefault;
        }

        $personal = Dashboard::query()
            ->where('user_id', $user->id)
            ->with('widgets')
            ->orderBy('nombre')
            ->first();
        if ($personal) {
            return $personal;
        }

        return Dashboard::query()
            ->whereNull('user_id')
            ->where('es_default', true)
            ->with('widgets')
            ->first()
            ?? Dashboard::query()->whereNull('user_id')->with('widgets')->orderBy('nombre')->first();
    }

    public function cloneTemplate(Dashboard $template, User $user): Dashboard
    {
        if (! $template->isInstitutional()) {
            throw ValidationException::withMessages([
                'dashboard' => 'Solo se pueden copiar plantillas institucionales.',
            ]);
        }

        $existing = Dashboard::withTrashed()
            ->where('codigo', $template->codigo)
            ->where('user_id', $user->id)
            ->first();
        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
            }
            $existing->widgets()->delete();
            $this->copyWidgets($template, $existing);
            return $existing->fresh('widgets');
        }

        $copy = $template->replicate();
        $copy->user_id = $user->id;
        $copy->es_default = false;
        $copy->nombre = $template->nombre.' (personal)';
        $copy->save();
        $this->copyWidgets($template, $copy);

        return $copy->load('widgets');
    }

    public function widgetPayload(DashboardWidget $widget, User $user, array $filters = []): array
    {
        $config = $this->validator->validateWidgetConfig($widget->tipo, $widget->query_config ?? []);
        $filters = $this->defaultFilters($widget->tipo, $config['filtros'] ?? [], $filters);
        $config['filtros'] = $filters;

        $payload = [
            'id' => $widget->id,
            'tipo' => $widget->tipo,
            'titulo' => $widget->titulo,
            'periodo_label' => PeriodContext::label($filters['periodo_desde'], $filters['periodo_hasta']),
            'cobertura' => null,
            'valor' => null,
            'semaforo' => null,
            'chart' => null,
            'table' => null,
            'heatmap' => $widget->tipo === 'heatmap',
            'fuente' => $this->resolveFuente($config),
        ];

        $assigned = Record::userHasGlobalAccess($user) ? null : Record::assignedEstablishmentIds($user);
        $useIndicatorEngine = ! empty($config['indicator'])
            && in_array($widget->tipo, ['kpi', 'indicador'], true)
            && ($assigned === null || count($assigned) <= 1);
        if ($useIndicatorEngine) {
            return $this->indicatorPayload($payload, $config, $user, $filters);
        }

        $definition = $this->definitionFromConfig($config, $widget->tipo);
        $result = $this->builder->execute($definition, $user);
        $payload['cobertura'] = $result['meta']['cobertura'];
        $payload['periodo_label'] = $result['meta']['periodo_label'];

        if (in_array($widget->tipo, ['kpi', 'indicador'], true)) {
            $payload['valor'] = $result['totals']['valor'] ?? ($result['rows'][0]['valor'] ?? null);
            if (! empty($config['umbrales'])) {
                $payload['semaforo'] = $this->semaforo($payload['valor'], $config['umbrales']);
            }
            return $payload;
        }

        if (in_array($widget->tipo, ['tabla', 'heatmap'], true)) {
            $payload['table'] = [
                'columns' => $result['columns'],
                'rows' => $result['rows'],
                'totals' => $result['totals'],
            ];
            if ($widget->tipo === 'heatmap') {
                $payload['table'] = $this->crossTable(
                    $result,
                    $config['dimension_x'],
                    $config['dimension_y']
                );
            }
            return $payload;
        }

        $dimension = $config['dimension'] ?? $definition['dimensions'][0] ?? 'periodo';
        $payload['chart'] = $this->builder->chartPayload(
            $result,
            $config['label'] ?? $widget->titulo,
            $dimension
        );
        $payload['table'] = [
            'columns' => $result['columns'],
            'rows' => $result['rows'],
            'totals' => $result['totals'],
        ];

        return $payload;
    }

    public function assertVisible(Dashboard $dashboard, User $user): void
    {
        if ($dashboard->isInstitutional()) {
            return;
        }
        abort_unless((int) $dashboard->user_id === (int) $user->id, 403);
    }

    public function assertEditable(Dashboard $dashboard, User $user): void
    {
        if ($dashboard->isInstitutional()) {
            abort_unless($user->can('bio.dashboard.manage'), 403);
            return;
        }
        abort_unless(
            (int) $dashboard->user_id === (int) $user->id && $user->can('bio.dashboard.personalize'),
            403
        );
    }

    private function indicatorPayload(array $payload, array $config, User $user, array $filters): array
    {
        $indicator = Indicador::activos()->where('codigo', $config['indicator'])->firstOrFail();
        $context = [
            'periodo_desde' => $filters['periodo_desde'],
            'periodo_hasta' => $filters['periodo_hasta'],
        ];
        foreach ([
            'establecimiento_id', 'departamento_id', 'distrito_id', 'microred_id',
            'tipo_establecimiento_id', 'grado_complejidad_id', 'area_gestion_id',
        ] as $key) {
            if (isset($filters[$key])) {
                $context[$key] = $filters[$key];
            }
        }
        if (! Record::userHasGlobalAccess($user)) {
            $ids = Record::assignedEstablishmentIds($user);
            if (count($ids) === 1) {
                $context['establecimiento_id'] = $ids[0];
            } elseif (empty($context['establecimiento_id'])) {
                $payload['valor'] = null;
                $payload['cobertura'] = ['esperados' => 0, 'informados' => 0, 'porcentaje' => null];
                $payload['semaforo'] = 'gris';
                return $payload;
            }
        }

        $evaluation = $this->indicators->evaluate($indicator, $context);
        $payload['valor'] = $evaluation['valor'];
        $payload['cobertura'] = $evaluation['cobertura'];
        $payload['unidad'] = $indicator->unidad;
        if (! empty($config['umbrales'])) {
            $payload['semaforo'] = $this->semaforo($evaluation['valor'], $config['umbrales']);
        }

        return $payload;
    }

    private function resolveFuente(array $config): ?string
    {
        $override = trim((string) ($config['fuente'] ?? ''));
        if ($override !== '') {
            return $override;
        }

        if (! empty($config['indicator'])) {
            return 'Indicador '.$config['indicator'];
        }

        if (! empty($config['form'])) {
            $parts = array_values(array_filter([
                (string) $config['form'],
                ! empty($config['metric']) ? (string) $config['metric'] : null,
            ]));

            return implode(' · ', $parts);
        }

        if (! empty($config['reporte_id'])) {
            $codigo = Reporte::whereKey($config['reporte_id'])->value('codigo');

            return $codigo ? 'Reporte '.$codigo : 'Reporte';
        }

        return null;
    }

    private function definitionFromConfig(array $config, string $tipo): array
    {
        if (! empty($config['reporte_id'])) {
            $reporte = Reporte::findOrFail($config['reporte_id']);
            $definition = $reporte->definicion;
            $definition['filtros'] = array_merge($definition['filtros'] ?? [], $config['filtros'] ?? []);
            return $definition;
        }

        $dimensions = match ($tipo) {
            'kpi', 'indicador' => [],
            'heatmap' => array_values(array_filter([
                $config['dimension_x'] ?? null,
                $config['dimension_y'] ?? null,
            ])),
            default => array_values(array_filter([$config['dimension'] ?? ($tipo === 'lineas' ? 'periodo' : null)])),
        };

        return [
            'form' => $config['form'] ?? null,
            'field' => $config['field'] ?? null,
            'metric' => $config['metric'] ?? null,
            'agg' => $config['agg'] ?? 'sum',
            'indicator' => $config['indicator'] ?? null,
            'dimensions' => $dimensions,
            'filtros' => $config['filtros'] ?? [],
            'order_by' => $tipo === 'lineas'
                ? [['ref' => $dimensions[0] ?? 'periodo', 'dir' => 'asc']]
                : [['ref' => 'valor', 'dir' => 'desc']],
            'limit' => 500,
            'totales' => true,
            'label' => $config['label'] ?? null,
        ];
    }

    private function defaultFilters(string $tipo, array $configured, array $overrides): array
    {
        $filters = array_merge($configured, array_filter($overrides, fn ($value) => $value !== null && $value !== ''));
        $to = PeriodContext::normalize($filters['periodo_hasta'] ?? null);
        if ($tipo === 'lineas') {
            $from = isset($filters['periodo_desde'])
                ? PeriodContext::normalize($filters['periodo_desde'], $to)
                : null;
            if ($from === null || $from === $to) {
                $filters['periodo_desde'] = PeriodContext::monthsBack($to, 12);
            } else {
                $filters['periodo_desde'] = $from;
            }
            $filters['periodo_hasta'] = $to;
        } else {
            $filters['periodo_desde'] = PeriodContext::normalize($filters['periodo_desde'] ?? $to, $to);
            $filters['periodo_hasta'] = $to;
        }

        return $filters;
    }

    private function crossTable(array $result, string $x, string $y): array
    {
        $columns = [];
        $index = [];
        foreach ($result['rows'] as $row) {
            $xLabel = (string) ($row[$x] ?? '');
            $yLabel = (string) ($row[$y] ?? '');
            $columns[$xLabel] = $xLabel;
            $index[$yLabel][$xLabel] = $row['valor'];
        }
        $columnKeys = array_values($columns);
        $tableColumns = [['key' => 'y', 'label' => $y]];
        foreach ($columnKeys as $key) {
            $tableColumns[] = ['key' => $key, 'label' => $key];
        }
        $rows = [];
        foreach ($index as $yLabel => $values) {
            $item = ['y' => $yLabel];
            foreach ($columnKeys as $key) {
                $item[$key] = $values[$key] ?? null;
            }
            $rows[] = $item;
        }

        return [
            'columns' => $tableColumns,
            'rows' => $rows,
            'totals' => $result['totals'],
        ];
    }

    private function semaforo(?float $value, array $umbrales): string
    {
        if ($value === null) {
            return 'gris';
        }
        foreach (['rojo', 'amarillo', 'verde'] as $color) {
            $range = $umbrales[$color] ?? null;
            if (is_array($range) && count($range) >= 2 && $value >= $range[0] && $value <= $range[1]) {
                return $color;
            }
        }

        return 'gris';
    }

    private function copyWidgets(Dashboard $from, Dashboard $to): void
    {
        foreach ($from->widgets as $widget) {
            $to->widgets()->create([
                'tipo' => $widget->tipo,
                'titulo' => $widget->titulo,
                'query_config' => $widget->query_config,
                'pos_x' => $widget->pos_x,
                'pos_y' => $widget->pos_y,
                'ancho' => $widget->ancho,
                'alto' => $widget->alto,
            ]);
        }
    }
}
