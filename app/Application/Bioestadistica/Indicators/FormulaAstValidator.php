<?php

namespace App\Application\Bioestadistica\Indicators;

use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use Illuminate\Validation\ValidationException;

class FormulaAstValidator
{
    private const AGGREGATES = ['sum', 'avg', 'count', 'count_distinct', 'max', 'min', 'hosp_count'];
    private const ARITHMETIC = ['add', 'sub', 'mul', 'div', 'pct', 'rate', 'round'];
    private const FILTERS = [
        'departamento_id', 'distrito_id', 'establecimiento_id', 'microred_id',
        'tipo_establecimiento_id', 'grado_complejidad_id', 'area_gestion_id',
        'catalog_item_ids', 'estado_record',
    ];

    public function validate(array $ast, ?Indicador $owner = null): array
    {
        $errors = [];
        $this->validateNode($ast, '$', 1, $errors);
        if ($owner) {
            $this->validateCycles($ast, $owner, $errors);
        }

        if ($errors) {
            throw ValidationException::withMessages(['expresion' => $errors]);
        }

        return $ast;
    }

    private function validateNode(mixed $node, string $path, int $depth, array &$errors): void
    {
        if (! is_array($node) || array_is_list($node)) {
            $errors[] = "{$path}: cada nodo debe ser un objeto.";
            return;
        }
        if ($depth > 10) {
            $errors[] = "{$path}: la fórmula supera la profundidad máxima de 10.";
            return;
        }

        if (array_key_exists('const', $node)) {
            if (count($node) !== 1 || ! is_int($node['const']) && ! is_float($node['const'])) {
                $errors[] = "{$path}: una constante debe ser un número y no admite otras propiedades.";
            }
            return;
        }

        if (array_key_exists('indicator', $node)) {
            if (count($node) !== 1 || ! is_string($node['indicator'])
                || ! Indicador::activos()->where('codigo', $node['indicator'])->exists()) {
                $errors[] = "{$path}: la referencia de indicador no existe o es inválida.";
            }
            return;
        }

        $op = $node['op'] ?? null;
        if (! is_string($op) || ! in_array($op, [...self::AGGREGATES, ...self::ARITHMETIC], true)) {
            $errors[] = "{$path}: operador desconocido.";
            return;
        }

        if (in_array($op, self::AGGREGATES, true)) {
            if ($op === 'hosp_count') {
                $this->validateHospCount($node, $path, $errors);
                return;
            }
            $this->validateAggregate($node, $path, $errors);
            return;
        }

        $allowed = $op === 'rate'
            ? ['op', 'args', 'factor']
            : ($op === 'round' ? ['op', 'args', 'decimals'] : ['op', 'args']);
        $this->unknownKeys($node, $allowed, $path, $errors);
        $args = $node['args'] ?? null;
        if (! is_array($args) || ! array_is_list($args)) {
            $errors[] = "{$path}.args: debe ser una lista.";
            return;
        }
        $count = count($args);
        if (in_array($op, ['sub', 'div', 'pct'], true) && $count !== 2) {
            $errors[] = "{$path}: {$op} requiere exactamente dos argumentos.";
        } elseif (in_array($op, ['add', 'mul'], true) && $count < 2) {
            $errors[] = "{$path}: {$op} requiere al menos dos argumentos.";
        } elseif (in_array($op, ['rate'], true) && $count !== 2) {
            $errors[] = "{$path}: rate requiere exactamente dos argumentos.";
        } elseif ($op === 'round' && $count !== 1) {
            $errors[] = "{$path}: round requiere exactamente un argumento.";
        }
        if ($op === 'rate' && (! is_numeric($node['factor'] ?? null)
            || ! in_array((float) $node['factor'], [100.0, 1000.0, 10000.0, 100000.0], true))) {
            $errors[] = "{$path}.factor: use 100, 1000, 10000 o 100000.";
        }
        if ($op === 'round' && (! is_int($node['decimals'] ?? null)
            || $node['decimals'] < 0 || $node['decimals'] > 4)) {
            $errors[] = "{$path}.decimals: debe estar entre 0 y 4.";
        }
        foreach ($args as $index => $arg) {
            $this->validateNode($arg, "{$path}.args[{$index}]", $depth + 1, $errors);
        }
    }

    private function validateHospCount(array $node, string $path, array &$errors): void
    {
        $this->unknownKeys($node, ['op', 'metric', 'filter'], $path, $errors);
        $allowed = ['ingresos', 'egresos', 'dias_estancia', 'fallecidos', 'cirugias', 'cesareas', 'partos', 'recien_nacidos'];
        if (! is_string($node['metric'] ?? null) || ! in_array($node['metric'], $allowed, true)) {
            $errors[] = "{$path}.metric: use una métrica hospitalaria permitida.";
        }
        $filter = $node['filter'] ?? [];
        if ($filter !== [] && (! is_array($filter) || array_is_list($filter))) {
            $errors[] = "{$path}.filter: debe ser un objeto.";
            return;
        }
        foreach (array_keys($filter) as $key) {
            if (! in_array($key, self::FILTERS, true)) {
                $errors[] = "{$path}.filter: filtro desconocido «{$key}».";
            }
        }
    }

    private function validateAggregate(array $node, string $path, array &$errors): void
    {
        $this->unknownKeys($node, ['op', 'form', 'field', 'metric', 'filter'], $path, $errors);
        $formCode = $node['form'] ?? null;
        $fieldCode = $node['field'] ?? null;
        if (! is_string($formCode) || ! is_string($fieldCode)) {
            $errors[] = "{$path}: una agregación requiere form y field.";
            return;
        }

        $form = Formulario::where('codigo', $formCode)->first();
        $field = $form?->secciones()->with('fields')->get()
            ->flatMap(fn ($section) => $section->fields)
            ->firstWhere('code', $fieldCode);
        if (! $field) {
            $errors[] = "{$path}: no existe {$formCode}.{$fieldCode}.";
            return;
        }
        $this->validateNumericSource($field, $node['metric'] ?? null, $path, $errors);

        $filter = $node['filter'] ?? [];
        if (! is_array($filter) || ($filter !== [] && array_is_list($filter))) {
            $errors[] = "{$path}.filter: debe ser un objeto.";
            return;
        }
        foreach (array_keys($filter) as $key) {
            if (! in_array($key, self::FILTERS, true)) {
                $errors[] = "{$path}.filter: filtro desconocido «{$key}».";
            }
        }
        if (isset($filter['estado_record'])
            && ! in_array($filter['estado_record'], ['aprobado', 'enviado'], true)) {
            $errors[] = "{$path}.filter.estado_record: estado no permitido.";
        }
        if (isset($filter['catalog_item_ids'])
            && (! is_array($filter['catalog_item_ids'])
                || collect($filter['catalog_item_ids'])->contains(fn ($id) => ! is_int($id)))) {
            $errors[] = "{$path}.filter.catalog_item_ids: debe ser una lista de IDs enteros.";
        }
    }

    private function validateNumericSource(Field $field, mixed $metric, string $path, array &$errors): void
    {
        if (in_array($field->type, ['integer', 'decimal'], true)) {
            if ($metric !== null) {
                $errors[] = "{$path}: un campo escalar no admite metric.";
            }
            return;
        }
        if ($field->type === 'matriz') {
            $rows = $field->config['rows'] ?? [];
            if (! is_string($metric) || ! in_array($metric, $rows, true)) {
                $errors[] = "{$path}: la métrica de matriz «{$metric}» no existe.";
            }
            return;
        }
        if ($field->type !== 'tabla' || ! is_string($metric)) {
            $errors[] = "{$path}: el campo no es una fuente numérica o requiere metric.";
            return;
        }
        $column = collect($field->config['columns'] ?? [])->firstWhere('code', $metric);
        if (! $column || ! in_array($column['type'] ?? null, ['integer', 'decimal'], true)) {
            $errors[] = "{$path}: la métrica «{$metric}» no existe o no es numérica.";
        }
    }

    private function validateCycles(array $candidate, Indicador $owner, array &$errors): void
    {
        $graph = [];
        foreach (Indicador::with('formulas')->get() as $indicator) {
            $formula = $indicator->id === $owner->id
                ? $candidate
                : $indicator->formulas->first()?->expresion;
            $graph[$indicator->codigo] = $formula ? $this->indicatorReferences($formula) : [];
        }
        $graph[$owner->codigo] = $this->indicatorReferences($candidate);
        if ($this->hasCycle($owner->codigo, $graph, [], [])) {
            $errors[] = 'La fórmula genera una referencia circular entre indicadores.';
        }
    }

    public function indicatorCodesIn(array $node): array
    {
        return $this->indicatorReferences($node);
    }

    private function indicatorReferences(array $node): array
    {
        $references = isset($node['indicator']) ? [$node['indicator']] : [];
        foreach ($node['args'] ?? [] as $arg) {
            if (is_array($arg)) {
                $references = [...$references, ...$this->indicatorReferences($arg)];
            }
        }
        return array_values(array_unique($references));
    }

    private function hasCycle(string $code, array $graph, array $visited, array $stack): bool
    {
        if (isset($stack[$code])) {
            return true;
        }
        if (isset($visited[$code])) {
            return false;
        }
        $visited[$code] = true;
        $stack[$code] = true;
        foreach ($graph[$code] ?? [] as $dependency) {
            if ($this->hasCycle($dependency, $graph, $visited, $stack)) {
                return true;
            }
        }
        return false;
    }

    private function unknownKeys(array $node, array $allowed, string $path, array &$errors): void
    {
        foreach (array_diff(array_keys($node), $allowed) as $key) {
            $errors[] = "{$path}: propiedad desconocida «{$key}».";
        }
    }
}
