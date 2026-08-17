<?php

namespace App\Application\Bioestadistica\Reports;

use App\Application\Bioestadistica\NumericSourceCatalog;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Reporte;
use Illuminate\Validation\ValidationException;

class ReportDefinitionValidator
{
    public const DIMENSIONS = [
        'departamento', 'distrito', 'establecimiento', 'microred',
        'tipo_establecimiento', 'grado_complejidad', 'area_gestion',
        'periodo', 'anio', 'mes', 'catalogo_item',
    ];

    public const AGGREGATIONS = ['sum', 'avg', 'count', 'count_distinct', 'max', 'min'];

    public const FILTERS = [
        'periodo_desde', 'periodo_hasta', 'departamento_id', 'distrito_id',
        'establecimiento_id', 'microred_id', 'tipo_establecimiento_id',
        'grado_complejidad_id', 'area_gestion_id', 'estado_record', 'catalog_item_ids',
    ];

    public const WIDGET_TYPES = ['kpi', 'tabla', 'barras', 'lineas', 'pastel', 'heatmap', 'indicador'];

    public function __construct(private NumericSourceCatalog $sources)
    {
    }

    public function validate(array $definition): array
    {
        $errors = [];
        $unknown = array_diff(array_keys($definition), [
            'form', 'field', 'metric', 'agg', 'indicator', 'dimensions',
            'filtros', 'order_by', 'limit', 'totales', 'label', 'formulario_id',
        ]);
        if ($unknown) {
            $errors[] = 'La definición contiene claves no permitidas: '.implode(', ', $unknown).'.';
        }

        $hasSource = ! empty($definition['form']) && ! empty($definition['field']);
        $hasIndicator = ! empty($definition['indicator']);
        if (! $hasSource && ! $hasIndicator) {
            $errors[] = 'Debe indicar una fuente numérica (form, field, metric) o un indicador.';
        }

        if ($hasSource) {
            $metric = $definition['metric'] ?? null;
            if (! is_string($definition['form']) || ! is_string($definition['field'])
                || ($metric !== null && ! is_string($metric))) {
                $errors[] = 'La fuente numérica debe usar códigos de formulario, campo y métrica.';
            } elseif (! $this->sources->exists($definition['form'], $definition['field'], $metric)) {
                $errors[] = 'La fuente numérica no existe o no es un campo numérico publicado.';
            }
            $agg = $definition['agg'] ?? 'sum';
            if (! in_array($agg, self::AGGREGATIONS, true)) {
                $errors[] = 'La agregación no está permitida.';
            }
        }

        if ($hasIndicator) {
            if (! is_string($definition['indicator'])
                || ! Indicador::activos()->where('codigo', $definition['indicator'])->exists()) {
                $errors[] = 'El indicador referenciado no existe o está inactivo.';
            }
        }

        $dimensions = $definition['dimensions'] ?? [];
        if (! is_array($dimensions) || ! array_is_list($dimensions)) {
            $errors[] = 'dimensions debe ser una lista.';
            $dimensions = [];
        }
        foreach ($dimensions as $dimension) {
            if (! in_array($dimension, self::DIMENSIONS, true)) {
                $errors[] = "Dimensión no permitida: {$dimension}.";
            }
        }

        $this->validateFilters($definition['filtros'] ?? [], $errors);
        $this->validateOrder($definition['order_by'] ?? [], $dimensions, $errors);

        $limit = $definition['limit'] ?? 500;
        if (! is_int($limit) && ! ctype_digit((string) $limit)) {
            $errors[] = 'El límite debe ser un entero.';
        } elseif ((int) $limit < 1 || (int) $limit > ReportBuilder::MAX_ROWS) {
            $errors[] = 'El límite debe estar entre 1 y '.ReportBuilder::MAX_ROWS.'.';
        }

        if (array_key_exists('totales', $definition) && ! is_bool($definition['totales'])) {
            $errors[] = 'totales debe ser verdadero o falso.';
        }

        if ($errors) {
            throw ValidationException::withMessages(['definicion' => $errors]);
        }

        $normalized = [
            'form' => $definition['form'] ?? null,
            'field' => $definition['field'] ?? null,
            'metric' => $definition['metric'] ?? null,
            'agg' => $definition['agg'] ?? 'sum',
            'indicator' => $definition['indicator'] ?? null,
            'dimensions' => array_values(array_unique($dimensions)),
            'filtros' => $this->normalizeFilters($definition['filtros'] ?? []),
            'order_by' => $definition['order_by'] ?? [],
            'limit' => (int) ($definition['limit'] ?? 500),
            'totales' => (bool) ($definition['totales'] ?? true),
            'label' => isset($definition['label']) ? (string) $definition['label'] : null,
        ];

        if ($normalized['form']) {
            $form = Formulario::where('codigo', $normalized['form'])->first();
            $normalized['formulario_id'] = $form?->id;
        }

        return $normalized;
    }

    public function validateWidgetConfig(string $tipo, array $config): array
    {
        if (! in_array($tipo, self::WIDGET_TYPES, true)) {
            throw ValidationException::withMessages(['tipo' => 'Tipo de widget no permitido.']);
        }

        $unknown = array_diff(array_keys($config), [
            'form', 'field', 'metric', 'agg', 'indicator', 'dimension',
            'dimension_x', 'dimension_y', 'reporte_id', 'filtros', 'umbrales',
            'label', 'comparar_con',
        ]);
        if ($unknown) {
            throw ValidationException::withMessages([
                'query_config' => 'Configuración con claves no permitidas: '.implode(', ', $unknown).'.',
            ]);
        }

        $errors = [];
        if (in_array($tipo, ['kpi', 'barras', 'lineas', 'pastel', 'heatmap', 'tabla'], true)
            && empty($config['indicator']) && empty($config['reporte_id'])
            && (empty($config['form']) || empty($config['field']))) {
            $errors[] = 'El widget necesita una fuente numérica, un indicador o un reporte.';
        }
        if ($tipo === 'indicador' && empty($config['indicator'])) {
            $errors[] = 'El widget de indicador requiere el código del indicador.';
        }
        if (in_array($tipo, ['barras', 'lineas', 'pastel'], true)) {
            $dimension = $config['dimension'] ?? ($tipo === 'lineas' ? 'periodo' : null);
            if (! $dimension || ! in_array($dimension, self::DIMENSIONS, true)) {
                $errors[] = 'El gráfico requiere una dimensión permitida.';
            }
        }
        if ($tipo === 'heatmap') {
            foreach (['dimension_x', 'dimension_y'] as $key) {
                if (empty($config[$key]) || ! in_array($config[$key], self::DIMENSIONS, true)) {
                    $errors[] = "heatmap requiere {$key} permitida.";
                }
            }
        }
        if (! empty($config['reporte_id'])
            && ! Reporte::whereKey($config['reporte_id'])->exists()) {
            $errors[] = 'El reporte referenciado no existe.';
        }
        if (! empty($config['form']) && ! empty($config['field'])) {
            if (! $this->sources->exists($config['form'], $config['field'], $config['metric'] ?? null)) {
                $errors[] = 'La fuente numérica del widget no existe.';
            }
        }
        if (! empty($config['indicator'])
            && ! Indicador::activos()->where('codigo', $config['indicator'])->exists()) {
            $errors[] = 'El indicador del widget no existe o está inactivo.';
        }
        $this->validateFilters($config['filtros'] ?? [], $errors);
        if (isset($config['umbrales']) && ! is_array($config['umbrales'])) {
            $errors[] = 'Los umbrales deben ser un objeto con rangos verde, amarillo y rojo.';
        }

        if ($errors) {
            throw ValidationException::withMessages(['query_config' => $errors]);
        }

        $config['filtros'] = $this->normalizeFilters($config['filtros'] ?? []);
        if (isset($config['agg']) && ! in_array($config['agg'], self::AGGREGATIONS, true)) {
            throw ValidationException::withMessages(['query_config' => 'Agregación no permitida.']);
        }

        return $config;
    }

    private function validateFilters(mixed $filters, array &$errors): void
    {
        if ($filters === [] || $filters === null) {
            return;
        }
        if (! is_array($filters) || array_is_list($filters)) {
            $errors[] = 'filtros debe ser un objeto.';
            return;
        }
        foreach (array_keys($filters) as $key) {
            if (! in_array($key, self::FILTERS, true)) {
                $errors[] = "Filtro no permitido: {$key}.";
            }
        }
        foreach (['periodo_desde', 'periodo_hasta'] as $key) {
            if (! isset($filters[$key])) {
                continue;
            }
            $period = $filters[$key];
            $year = is_array($period) ? ($period['anio'] ?? null) : null;
            $month = is_array($period) ? ($period['mes'] ?? null) : null;
            if (! is_numeric($year) || ! is_numeric($month) || $month < 1 || $month > 12) {
                $errors[] = "{$key} debe incluir año y mes válidos.";
            }
        }
        if (isset($filters['estado_record'])
            && ! in_array($filters['estado_record'], ['borrador', 'enviado', 'aprobado', 'objetado'], true)) {
            $errors[] = 'estado_record no es válido.';
        }
        if (isset($filters['catalog_item_ids'])) {
            if (! is_array($filters['catalog_item_ids'])) {
                $errors[] = 'catalog_item_ids debe ser una lista de enteros.';
            } else {
                foreach ($filters['catalog_item_ids'] as $id) {
                    if (! is_int($id) && ! ctype_digit((string) $id)) {
                        $errors[] = 'catalog_item_ids contiene un valor no entero.';
                        break;
                    }
                }
            }
        }
        foreach ([
            'departamento_id', 'distrito_id', 'establecimiento_id', 'microred_id',
            'tipo_establecimiento_id', 'grado_complejidad_id', 'area_gestion_id',
        ] as $key) {
            if (isset($filters[$key]) && ! is_numeric($filters[$key])) {
                $errors[] = "{$key} debe ser numérico.";
            }
        }
    }

    private function validateOrder(mixed $order, array $dimensions, array &$errors): void
    {
        if ($order === [] || $order === null) {
            return;
        }
        if (! is_array($order) || ! array_is_list($order)) {
            $errors[] = 'order_by debe ser una lista.';
            return;
        }
        $allowed = array_merge($dimensions, ['valor']);
        foreach ($order as $item) {
            if (! is_array($item) || empty($item['ref'])) {
                $errors[] = 'Cada orden debe indicar ref y dir.';
                continue;
            }
            if (! in_array($item['ref'], $allowed, true)) {
                $errors[] = "No se puede ordenar por {$item['ref']}.";
            }
            if (isset($item['dir']) && ! in_array(strtolower((string) $item['dir']), ['asc', 'desc'], true)) {
                $errors[] = 'La dirección de orden debe ser asc o desc.';
            }
        }
    }

    private function normalizeFilters(array $filters): array
    {
        $normalized = [];
        foreach ($filters as $key => $value) {
            if ($value === null || $value === '' || $value === []) {
                continue;
            }
            if (in_array($key, ['periodo_desde', 'periodo_hasta'], true) && is_array($value)) {
                $normalized[$key] = PeriodContext::normalize($value, PeriodContext::lastClosed());
                continue;
            }
            if ($key === 'catalog_item_ids') {
                $normalized[$key] = array_map('intval', $value);
                continue;
            }
            if (str_ends_with($key, '_id')) {
                $normalized[$key] = (int) $value;
                continue;
            }
            $normalized[$key] = $value;
        }

        return $normalized;
    }
}
