<?php

namespace App\Http\Requests\Bioestadistica;

use App\Models\Bioestadistica\Establecimiento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HospEpisodioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('bio.hosp.manage') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cirugia' => $this->boolean('cirugia'),
            'cesarea' => $this->boolean('cesarea'),
            'recien_nacido' => $this->boolean('recien_nacido'),
        ]);
    }

    public function rules(): array
    {
        return [
            'establecimiento_id' => ['required', 'integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
            'cedula' => ['nullable', 'string', 'max:30'],
            'sexo' => ['nullable', 'string'],
            'seguro' => ['nullable', 'string', 'max:80'],
            'edad' => ['nullable', 'integer', 'between:0,130'],
            'fecha_ingreso' => ['required', 'date'],
            'fecha_egreso' => ['nullable', 'date', 'after_or_equal:fecha_ingreso'],
            'servicio' => ['nullable', 'string', 'max:150'],
            'diagnostico' => ['nullable', 'string', 'max:400'],
            'cie10' => ['nullable', 'string', 'max:10'],
            'tipo_alta' => ['nullable', 'string', 'max:50'],
            'cirugia' => ['nullable', 'boolean'],
            'tipo_cirugia' => ['nullable', 'string', 'max:150'],
            'recien_nacido' => ['nullable', 'boolean'],
            'cesarea' => ['nullable', 'boolean'],
        ];
    }
}
