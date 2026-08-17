<?php

namespace App\Application\Bioestadistica;

use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;

class NumericSourceCatalog
{
    public function all(): array
    {
        $sources = [];
        $forms = Formulario::with('secciones.fields')->orderBy('codigo')->get();
        foreach ($forms as $form) {
            foreach ($form->secciones->flatMap->fields as $field) {
                if (in_array($field->type, ['integer', 'decimal'], true)) {
                    $sources[] = $this->source($form, $field, null, $field->label);
                }
                if ($field->type === 'tabla') {
                    foreach ($field->config['columns'] ?? [] as $column) {
                        if (in_array($column['type'] ?? null, ['integer', 'decimal'], true)) {
                            $sources[] = $this->source(
                                $form,
                                $field,
                                $column['code'] ?? null,
                                ($field->label).' / '.($column['label'] ?? $column['code'] ?? '')
                            );
                        }
                    }
                }
                if ($field->type === 'matriz') {
                    foreach ($field->config['rows'] ?? [] as $index => $row) {
                        $label = $field->config['row_labels'][$index] ?? $row;
                        $sources[] = $this->source($form, $field, $row, ($field->label).' / '.$label);
                    }
                }
            }
        }

        return $sources;
    }

    public function exists(string $form, string $field, ?string $metric): bool
    {
        return collect($this->all())->contains(
            fn (array $source) => $source['form'] === $form
                && $source['field'] === $field
                && ($source['metric'] ?? null) === $metric
        );
    }

    private function source(Formulario $form, Field $field, ?string $metric, string $label): array
    {
        $key = $metric
            ? "{$form->codigo}:{$field->code}:{$metric}"
            : "{$form->codigo}:{$field->code}";

        return [
            'key' => $key,
            'label' => "{$form->codigo} — {$label}",
            'form' => $form->codigo,
            'field' => $field->code,
            'metric' => $metric,
        ];
    }
}
