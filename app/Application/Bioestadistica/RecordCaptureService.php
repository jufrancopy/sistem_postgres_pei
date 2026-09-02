<?php

namespace App\Application\Bioestadistica;

use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Record;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordCaptureService
{
    public function saveDraft(Record $record, array $values, ?int $userId = null): Record
    {
        return $this->save($record, $values, false, false, $userId);
    }

    public function save(
        Record $record,
        array $values,
        bool $system = false,
        bool $strict = true,
        ?int $userId = null
    ): Record {
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
            ->with('fields.detalle.catalogoItems')
            ->get()
            ->flatMap(fn ($section) => $section->fields)
            ->keyBy('code');

        $normalized = $this->validate($fields, $values, $record, $strict);
        $actorId = $userId ?? Auth::id();

        DB::transaction(function () use ($record, $fields, $normalized, $strict, $actorId) {
            foreach ($fields as $code => $field) {
                if (! $strict && ! array_key_exists($code, $normalized)) {
                    continue;
                }
                $payload = $normalized[$code] ?? null;
                if ($payload === null) {
                    $record->values()->where('field_id', $field->id)->delete();
                    continue;
                }

                $value = $record->values()->firstOrNew(['field_id' => $field->id]);
                $value->fill(array_merge([
                    'value_text' => null,
                    'value_num' => null,
                    'value_date' => null,
                    'value_bool' => null,
                    'value_json' => null,
                ], $payload));

                if ($actorId) {
                    if (! $value->exists) {
                        $value->created_by = (int) $actorId;
                    }
                    $value->updated_by = (int) $actorId;
                }

                $value->save();
            }

            if ($actorId) {
                $record->forceFill(['updated_by' => (int) $actorId])->save();
            }
        });
        if ($strict) {
            app(IndicatorCacheService::class)->invalidateForRecord($record);
        }

        return $record->fresh(['values.field']);
    }

    public function validate(Collection $fields, array $values, ?Record $record = null, bool $strict = true): array
    {
        $normalized = [];
        $errors = [];

        foreach ($fields as $code => $field) {
            $value = $values[$code] ?? null;
            $empty = $value === null || $value === '' || $value === [];

            if ($field->required && $empty && $strict) {
                $errors["values.{$code}"][] = "{$field->label} es obligatorio.";
                continue;
            }
            if ($empty) {
                $normalized[$code] = null;
                continue;
            }

            try {
                $payload = $this->normalizeValue($field, $value, $record, $strict);
                if ($payload === null) {
                    // Borrador: valor inválido a medio escribir; se conserva lo ya guardado.
                    continue;
                }
                $normalized[$code] = $payload;
            } catch (ValidationException $exception) {
                if ($strict) {
                    $errors["values.{$code}"] = $exception->errors()['value'] ?? [$exception->getMessage()];
                }
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        return $normalized;
    }

    private function normalizeValue(Field $field, mixed $value, ?Record $record = null, bool $strict = true): ?array
    {
        $this->validateCatalog($field, $value);

        $payload = match ($field->type) {
            'integer' => ['value_num' => $this->number($field, $value, true, $strict)],
            'decimal' => ['value_num' => $this->number($field, $value, false, $strict)],
            'date' => ['value_date' => $this->date($field, $value, $strict)],
            'time' => ['value_text' => $this->time($field, $value, $strict)],
            'boolean' => ['value_bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false],
            'tabla' => ['value_json' => $this->tabla($field, $value, $strict)],
            'matriz' => ['value_json' => $this->matriz($field, $value, $record)],
            'multiselect', 'subtabla' => ['value_json' => $this->json($field, $value)],
            default => ['value_text' => $this->text($field, $value, $strict)],
        };

        if (! $strict && array_key_exists(array_key_first($payload), $payload) && reset($payload) === null) {
            return null;
        }

        return $payload;
    }

    private function number(Field $field, mixed $value, bool $integer, bool $strict = true): int|float|null
    {
        if (! is_numeric($value) || ($integer && filter_var($value, FILTER_VALIDATE_INT) === false)) {
            if (! $strict) {
                return null;
            }
            $this->fail("{$field->label} debe ser " . ($integer ? 'un número entero.' : 'numérico.'));
        }
        $number = $integer ? (int) $value : (float) $value;
        if ($field->min_value !== null && $number < (float) $field->min_value) {
            if (! $strict) {
                return null;
            }
            $this->fail("{$field->label} no puede ser menor a {$field->min_value}.");
        }
        if ($field->max_value !== null && $number > (float) $field->max_value) {
            if (! $strict) {
                return null;
            }
            $this->fail("{$field->label} no puede ser mayor a {$field->max_value}.");
        }

        return $number;
    }

    private function text(Field $field, mixed $value, bool $strict = true): ?string
    {
        $value = trim((string) $value);
        if ($field->validation_regex && @preg_match($field->validation_regex, '') !== false
            && ! preg_match($field->validation_regex, $value)) {
            if (! $strict) {
                return null;
            }
            $this->fail("{$field->label} no cumple el formato requerido.");
        }

        return $value;
    }

    private function date(Field $field, mixed $value, bool $strict = true): ?string
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', (string) $value);
        if (! $date || $date->format('Y-m-d') !== $value) {
            if (! $strict) {
                return null;
            }
            $this->fail("{$field->label} debe ser una fecha válida.");
        }

        return $value;
    }

    private function time(Field $field, mixed $value, bool $strict = true): ?string
    {
        $time = (string) $value;
        if (! preg_match('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/', $time)) {
            if (! $strict) {
                return null;
            }
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
     * Normaliza una tabla a {"rows": {"<fila_id>": {"<columna>": número}}}.
     * El id es prestación (diccionario) o ítem de catálogo auxiliar.
     * descartando las filas sin datos para no almacenar el catálogo completo en vacío.
     */
    private function tabla(Field $field, mixed $value, bool $strict = true): array
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
        $validItems = $field->rowItems()->pluck('id')->map(fn ($id) => (string) $id)->all();

        $normalized = [];
        foreach ($rows as $itemId => $cells) {
            if ($validItems && ! in_array((string) $itemId, $validItems, true)) {
                if ($strict) {
                    $this->fail("{$field->label} contiene una fila que no pertenece al diccionario o catálogo.");
                }
                continue;
            }
            if (! is_array($cells)) {
                if ($strict) {
                    $this->fail("{$field->label} contiene una fila con formato inválido.");
                }
                continue;
            }

            $row = [];
            foreach ($cells as $columnCode => $cell) {
                $column = $columns->get($columnCode);
                if (! $column) {
                    if ($strict) {
                        $this->fail("{$field->label} contiene la columna desconocida «{$columnCode}».");
                    }
                    continue;
                }
                if ($cell === null || $cell === '') {
                    continue;
                }
                $parsed = $this->tablaCell($field, $column, $cell, $strict);
                if ($parsed === null) {
                    continue;
                }
                $row[$columnCode] = $parsed;
            }

            $row = $this->applyRowTotal($row, $field);

            if ($row !== []) {
                $normalized[(string) $itemId] = $row;
            }
        }

        if ($normalized === [] && $field->required && $strict) {
            $this->fail("{$field->label} requiere al menos una fila con datos.");
        }

        // json_encode([]) is a JSON array; the numeric view uses jsonb_each, which needs an object.
        return ['rows' => $normalized === [] ? new \stdClass() : $normalized];
    }

    /**
     * @param  array<string, int|float|string>  $row
     * @return array<string, int|float|string>
     */
    private function applyRowTotal(array $row, Field $field): array
    {
        $config = $field->config['row_total'] ?? null;
        if (! is_array($config)) {
            return $row;
        }

        $totalCode = (string) ($config['code'] ?? 'total');
        $sumColumns = $config['sum_columns'] ?? [];
        if (! is_array($sumColumns) || $sumColumns === []) {
            return $row;
        }

        $breakdownSum = 0;
        foreach ($sumColumns as $columnCode) {
            $breakdownSum += (int) ($row[$columnCode] ?? 0);
        }

        $manualTotal = isset($row[$totalCode]) && $row[$totalCode] !== '' ? (int) $row[$totalCode] : null;

        if ($breakdownSum > 0) {
            $row[$totalCode] = $breakdownSum;
        } elseif ($manualTotal !== null && $manualTotal > 0) {
            $row[$totalCode] = $manualTotal;
        } else {
            unset($row[$totalCode]);
        }

        return $row;
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

    private function tablaCell(Field $field, array $column, mixed $cell, bool $strict = true): int|float|string|null
    {
        $type = $column['type'] ?? 'integer';
        $label = "{$field->label} — " . ($column['label'] ?? $column['code']);

        if (in_array($type, ['integer', 'decimal'], true)) {
            $integer = $type === 'integer';
            if (! is_numeric($cell) || ($integer && filter_var($cell, FILTER_VALIDATE_INT) === false)) {
                if (! $strict) {
                    return null;
                }
                $this->fail("{$label} debe ser " . ($integer ? 'un número entero.' : 'numérico.'));
            }
            $number = $integer ? (int) $cell : (float) $cell;
            if (isset($column['min']) && $number < (float) $column['min']) {
                if (! $strict) {
                    return null;
                }
                $this->fail("{$label} no puede ser menor a {$column['min']}.");
            }
            if (isset($column['max']) && $number > (float) $column['max']) {
                if (! $strict) {
                    return null;
                }
                $this->fail("{$label} no puede ser mayor a {$column['max']}.");
            }

            return $number;
        }

        return trim((string) $cell);
    }

    private function validateCatalog(Field $field, mixed $value): void
    {
        if (! $field->detalle_id || ! in_array($field->type, ['select', 'radio', 'multiselect'], true)) {
            return;
        }

        $valid = $field->rowItems()->pluck('id')->map(fn ($id) => (string) $id)->all();
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
