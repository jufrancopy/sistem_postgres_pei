<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Indicators\FormulaAstValidator;
use App\Application\Bioestadistica\Reports\ReportDefinitionValidator;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\Reporte;

class BioestadisticaAnalyticsSupport
{
    /**
     * @return array<string, mixed>|null
     */
    public static function sumFormMetric(string $form, string $metric): ?array
    {
        $args = [];
        foreach (self::tabularFields($form) as $field) {
            if (! self::hasColumn($field, $metric)) {
                continue;
            }
            $args[] = [
                'op' => 'sum',
                'form' => $form,
                'field' => $field->code,
                'metric' => $metric,
            ];
        }
        if ($args === []) {
            return null;
        }

        return count($args) === 1 ? $args[0] : ['op' => 'add', 'args' => $args];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function sumAllNumericColumns(string $form): ?array
    {
        $args = [];
        foreach (self::tabularFields($form) as $field) {
            foreach ($field->config['columns'] ?? [] as $column) {
                if (! in_array($column['type'] ?? null, ['integer', 'decimal'], true)) {
                    continue;
                }
                $args[] = [
                    'op' => 'sum',
                    'form' => $form,
                    'field' => $field->code,
                    'metric' => $column['code'],
                ];
            }
        }
        if ($args === []) {
            return null;
        }

        return count($args) === 1 ? $args[0] : ['op' => 'add', 'args' => $args];
    }

    /**
     * @return array{form: string, field: string, metric: string}|null
     */
    public static function firstTableSource(string $form, string $metric): ?array
    {
        foreach (self::tabularFields($form) as $field) {
            if (self::hasColumn($field, $metric)) {
                return ['form' => $form, 'field' => $field->code, 'metric' => $metric];
            }
        }

        return null;
    }

    public static function upsertIndicator(
        string $code,
        string $name,
        string $description,
        string $unit,
        array $expression,
        int $decimals = 0
    ): ?Indicador {
        $indicator = Indicador::updateOrCreate(
            ['codigo' => $code],
            [
                'nombre' => $name,
                'descripcion' => $description,
                'unidad' => $unit,
                'ambito' => 'establecimiento',
                'decimales' => $decimals,
                'activo' => true,
            ]
        );
        app(FormulaAstValidator::class)->validate($expression, $indicator);
        $indicator->formulas()->updateOrCreate(
            ['vigente_desde' => null, 'vigente_hasta' => null],
            ['expresion' => $expression]
        );

        return $indicator;
    }

    /**
     * @param  array<string, mixed>  $definition
     */
    public static function upsertReport(
        string $code,
        string $name,
        string $description,
        array $definition
    ): Reporte {
        $normalized = app(ReportDefinitionValidator::class)->validate($definition);
        $formulario = Formulario::where('codigo', $normalized['form'])->first();

        return Reporte::withTrashed()->updateOrCreate(
            ['codigo' => $code],
            [
                'nombre' => $name,
                'descripcion' => $description,
                'formulario_id' => $formulario?->id,
                'definicion' => $normalized,
                'publico' => true,
                'deleted_at' => null,
            ]
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $widgets
     */
    public static function upsertDashboard(
        string $code,
        string $name,
        string $description,
        array $widgets,
        bool $default = false
    ): Dashboard {
        $dashboard = Dashboard::withTrashed()
            ->where('codigo', $code)
            ->whereNull('user_id')
            ->first();
        if (! $dashboard) {
            $dashboard = Dashboard::create([
                'codigo' => $code,
                'nombre' => $name,
                'descripcion' => $description,
                'user_id' => null,
                'es_default' => $default,
            ]);
        } else {
            $dashboard->restore();
            $dashboard->update([
                'nombre' => $name,
                'descripcion' => $description,
                'es_default' => $default ? true : $dashboard->es_default,
            ]);
        }
        $dashboard->widgets()->delete();
        foreach ($widgets as $widget) {
            $dashboard->widgets()->create($widget);
        }

        return $dashboard;
    }

    private static function tabularFields(string $form): iterable
    {
        $formulario = Formulario::with('secciones.fields')->where('codigo', $form)->first();
        foreach ($formulario?->secciones ?? [] as $seccion) {
            foreach ($seccion->fields as $field) {
                if ($field->type === 'tabla') {
                    yield $field;
                }
            }
        }
    }

    private static function hasColumn($field, string $metric): bool
    {
        return (bool) collect($field->config['columns'] ?? [])->firstWhere('code', $metric);
    }
}
