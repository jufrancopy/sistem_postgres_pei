<?php

namespace App\Application\Bioestadistica;

use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Record;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordCaptureService
{
    public function save(Record $record, array $values, bool $system = false): Record
    {
        if (! $system && ! $record->isEditable()) {
            throw ValidationException::withMessages([
                'record' => 'Solo se pueden editar registros en borrador u objetados.',
            ]);
        }
        if (! $system && $record->formulario?->layout_type === 'nominativo') {
            throw ValidationException::withMessages([
                'record' => 'SP10 nominativo se edita en Hospitalización, no como captura EAV.',
            ]);
        }

        $fields = $record->formulario->secciones()
            ->with('fields.catalogo.items')
            ->get()
            ->flatMap(fn ($section) => $section->fields)
            ->keyBy('code');

        $normalized = $this->validate($fields, $values, $record);

        DB::transaction(function () use ($record, $fields, $normalized) {
            foreach ($fields as $code => $field) {
                $payload = $normalized[$code] ?? null;
                if ($payload === null) {
                    $record->values()->where('field_id', $field->id)->delete();
                    continue;
                }

                $record->values()->updateOrCreate(
                    ['field_id' => $field->id],
                    array_merge([
                        'value_text' => null,
                        'value_num' => null,
                        'value_date' => null,
                        'value_bool' => null,
                        'value_json' => null,
                    ], $payload)
                );
            }
        });
        app(IndicatorCacheService::class)->invalidateForRecord($record);

        return $record->fresh(['values.field']);
    }

    public function validate(Collection $fields, array $values, ?Record $record = null): array
    {
        $normalized = [];
        $errors = [];

        foreach ($fields as $code => $field) {
            $value = $values[$code] ?? null;
            $empty = $value === null || $value === '' || $value === [];

            if ($field->required && $empty) {
                $errors["values.{$code}"][] = "{$field->label} es obligatorio.";
                continue;
            }
            if ($empty) {
                continue;
            }

            try {
                $normalized[$code] = $this->normalizeValue($field, $value, $record);
            } catch (ValidationException $exception) {
                $errors["values.{$code}"] = $exception->errors()['value'] ?? [$exception->getMessage()];
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        return $normalized;
    }

    private function normalizeValue(Field $field, mixed $value, ?Record $record = null): array
    {
        $this->validateCatalog($field, $value);

        return match ($field->type) {
            'integer' => ['value_num' => $this->number($field, $value, true)],
            'decimal' => ['value_num' => $this->number($field, $value, false)],
            'date' => ['value_date' => $this->date($field, $value)],
            'time' => ['value_text' => $this->time($field, $value)],
            'boolean' => ['value_bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false],
            'tabla' => ['value_json' => $this->tabla($field, $value)],
            'matriz' => ['value_json' => $this->matriz($field, $value, $record)],
            'multiselect', 'subtabla' => ['value_json' => $this->json($field, $value)],
            default => ['value_text' => $this->text($field, $value)],
        };
    }

    private function number(Field $field, mixed $value, bool $integer): int|float
    {
        if (! is_numeric($value) || ($integer && filter_var($value, FILTER_VALIDATE_INT) === false)) {
            $this->fail("{$field->label} debe ser " . ($integer ? 'un número entero.' : 'numérico.'));
        }
        $number = $integer ? (int) $value : (float) $value;
        if ($field->min_value !== null && $number < (float) $field->min_value) {
            $this->fail("{$field->label} no puede ser menor a {$field->min_value}.");
        }
        if ($field->max_value !== null && $number > (float) $field->max_value) {
            $this->fail("{$field->label} no puede ser mayor a {$field->max_value}.");
        }

        return $number;
    }

    private function text(Field $field, mixed $value): string
    {
        $value = trim((string) $value);
        if ($field->validation_regex && @preg_match($field->validation_regex, '') !== false
            && ! preg_match($field->validation_regex, $value)) {
            $this->fail("{$field->label} no cumple el formato requerido.");
        }

        return $value;
    }

    private function date(Field $field, mixed $value): string
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', (string) $value);
        if (! $date || $date->format('Y-m-d') !== $value) {
            $this->fail("{$field->label} debe ser una fecha válida.");
        }

        return $value;
    }

    private function time(Field $field, mixed $value): string
    {
        $time = (string) $value;
        if (! preg_match('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/', $time)) {
            $this->fail("{$field->label} debe ser una hora válida.");
        }

        return $time;
    }

    private function json(Field $field, mixed $value): array
    {
        if (is_string($value)) {
            try {
                $value = json_decode($value, true, flags: JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                $this->fail("{$field->label} debe contener JSON válido.");
            }
        }
        if (! is_array($value)) {
            $this->fail("{$field->label} debe ser una estructura válida.");
        }

        return $value;
    }

    /**
     * Normaliza una tabla de catálogo a {"rows": {"<catalog_item_id>": {"<columna>": número}}},
     * descartando las filas sin datos para no almacenar el catálogo completo en vacío.
     */
    private function tabla(Field $field, mixed $value): array
    {
        $payload = $this->json($field, $value);
        $rows = $payload['rows'] ?? $payload;
        if (! is_array($rows)) {
            $this->fail("{$field->label} debe enviar filas válidas.");
        }

        $columns = collect($field->config['columns'] ?? [])->keyBy('code');
        if ($columns->isEmpty()) {
            $this->fail("{$field->label} no tiene columnas configuradas.");
        }
        $validItems = $field->catalogo?->items->pluck('id')->map(fn ($id) => (string) $id)->all() ?? [];

        $normalized = [];
        foreach ($rows as $itemId => $cells) {
            if ($validItems && ! in_array((string) $itemId, $validItems, true)) {
                $this->fail("{$field->label} contiene una fila que no pertenece al catálogo.");
            }
            if (! is_array($cells)) {
                $this->fail("{$field->label} contiene una fila con formato inválido.");
            }

            $row = [];
            foreach ($cells as $columnCode => $cell) {
                $column = $columns->get($columnCode);
                if (! $column) {
                    $this->fail("{$field->label} contiene la columna desconocida «{$columnCode}».");
                }
                if ($cell === null || $cell === '') {
                    continue;
                }
                $row[$columnCode] = $this->tablaCell($field, $column, $cell);
            }

            if ($row !== []) {
                $normalized[(string) $itemId] = $row;
            }
        }

        if ($normalized === [] && $field->required) {
            $this->fail("{$field->label} requiere al menos una fila con datos.");
        }

        return ['rows' => $normalized];
    }

    private function matriz(Field $field, mixed $value, ?Record $record): array
    {
        $payload = $this->json($field, $value);
        $year = (int) ($record?->periodo_anio ?: now()->year);
        $month = (int) ($record?->periodo_mes ?: now()->month);
        if (($field->config['cols'] ?? null) === 'dias_mes' || ($field->config['contract'] ?? null) === 'sp11_v1') {
            return Sp11Matrix::normalize($payload, $year, $month, true);
        }

        return $payload;
    }

    private function tablaCell(Field $field, array $column, mixed $cell): int|float|string
    {
        $type = $column['type'] ?? 'integer';
        $label = "{$field->label} — " . ($column['label'] ?? $column['code']);

        if (in_array($type, ['integer', 'decimal'], true)) {
            $integer = $type === 'integer';
            if (! is_numeric($cell) || ($integer && filter_var($cell, FILTER_VALIDATE_INT) === false)) {
                $this->fail("{$label} debe ser " . ($integer ? 'un número entero.' : 'numérico.'));
            }
            $number = $integer ? (int) $cell : (float) $cell;
            if (isset($column['min']) && $number < (float) $column['min']) {
                $this->fail("{$label} no puede ser menor a {$column['min']}.");
            }
            if (isset($column['max']) && $number > (float) $column['max']) {
                $this->fail("{$label} no puede ser mayor a {$column['max']}.");
            }

            return $number;
        }

        return trim((string) $cell);
    }

    private function validateCatalog(Field $field, mixed $value): void
    {
        if (! $field->catalogo_id || ! in_array($field->type, ['select', 'radio', 'multiselect'], true)) {
            return;
        }

        $valid = $field->catalogo?->items->pluck('id')->map(fn ($id) => (string) $id)->all() ?? [];
        $selected = is_array($value) ? $value : [$value];
        foreach ($selected as $item) {
            if (! in_array((string) $item, $valid, true)) {
                $this->fail("{$field->label} contiene una opción no válida.");
            }
        }
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['value' => $message]);
    }
}
