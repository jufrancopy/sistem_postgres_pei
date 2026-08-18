<?php

namespace App\Http\Requests\Bioestadistica;

use App\Models\Bioestadistica\Establecimiento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HospEpisodioBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('bio.hosp.manage') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $rows = collect($this->input('rows', []))
            ->map(function (array $row) {
                foreach (['cirugia', 'cesarea', 'recien_nacido', 'eliminar'] as $field) {
                    $row[$field] = filter_var($row[$field] ?? false, FILTER_VALIDATE_BOOLEAN);
                }

                return $row;
            })
            ->filter(function (array $row) {
                if (! empty($row['id']) || ! empty($row['eliminar'])) {
                    return true;
                }

                return collect($row)
                    ->except(['cirugia', 'cesarea', 'recien_nacido', 'eliminar'])
                    ->contains(fn ($value) => $value !== null && trim((string) $value) !== '');
            })
            ->values()
            ->all();

        $this->merge(['rows' => $rows]);
    }

    public function rules(): array
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
                Rule::exists(Establecimiento::class, 'id')->withoutTrashed(),
            ],
            'periodo_anio' => ['required', 'integer', 'between:1990,2100'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'rows' => ['required', 'array', 'min:1', 'max:200'],
            'rows.*.id' => ['nullable', 'integer'],
            'rows.*.eliminar' => ['nullable', 'boolean'],
            'rows.*.cedula' => ['nullable', 'string', 'max:30'],
            'rows.*.sexo' => ['nullable', Rule::in(['M', 'F'])],
            'rows.*.seguro' => ['nullable', 'string', 'max:80'],
            'rows.*.edad' => ['nullable', 'integer', 'between:0,130'],
            'rows.*.fecha_ingreso' => ['nullable', 'date'],
            'rows.*.fecha_egreso' => [
                'nullable',
                'date',
                'after_or_equal:rows.*.fecha_ingreso',
            ],
            'rows.*.servicio' => ['nullable', 'string', 'max:150'],
            'rows.*.diagnostico' => ['nullable', 'string', 'max:400'],
            'rows.*.cie10' => ['nullable', 'string', 'max:10'],
            'rows.*.tipo_alta' => ['nullable', 'string', 'max:50'],
            'rows.*.cirugia' => ['nullable', 'boolean'],
            'rows.*.tipo_cirugia' => ['nullable', 'string', 'max:150'],
            'rows.*.recien_nacido' => ['nullable', 'boolean'],
            'rows.*.cesarea' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'rows.*.fecha_ingreso' => 'fecha de ingreso',
            'rows.*.fecha_egreso' => 'fecha de egreso',
            'rows.*.tipo_alta' => 'tipo de alta',
        ];
    }
}
