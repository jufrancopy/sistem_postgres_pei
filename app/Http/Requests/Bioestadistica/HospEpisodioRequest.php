<?php

namespace App\Http\Requests\Bioestadistica;

use App\Models\Bioestadistica\Establecimiento;
use App\Application\Bioestadistica\Reports\PeriodContext;
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
            'cirugia_mayor' => $this->boolean('cirugia_mayor'),
            'cirugia_menor' => $this->boolean('cirugia_menor'),
        ]);
    }

    public function rules(): array
    {
        return [
            'establecimiento_id' => ['required', 'integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
            'periodo_anio' => PeriodContext::yearValidationRules(true),
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'cedula' => ['nullable', 'string', 'max:30'],
            'nro_patronal' => ['nullable', 'string', 'max:40'],
            'sexo' => ['nullable', 'string'],
            'seguro' => ['nullable', 'string', 'max:80'],
            'edad' => ['nullable', 'integer', 'between:0,130'],
            'ciudad_residencia' => ['nullable', 'string', 'max:150'],
            'fecha_ingreso' => ['required', 'date'],
            'fecha_egreso' => ['nullable', 'date', 'after_or_equal:fecha_ingreso'],
            'servicio' => ['nullable', 'string', 'max:150'],
            'diagnostico' => ['nullable', 'string', 'max:400'],
            'cie10' => ['nullable', 'string', 'max:10'],
            'tipo_alta' => ['nullable', 'string', 'max:50'],
            'cirugia' => ['nullable', 'boolean'],
            'cirugia_mayor' => ['nullable', 'boolean'],
            'cirugia_menor' => ['nullable', 'boolean'],
            'tipo_cirugia' => ['nullable', 'string', 'max:150'],
            'recien_nacido' => ['nullable', 'boolean'],
            'rn_sexo' => ['nullable', 'string', 'max:1'],
            'rn_peso' => ['nullable', 'integer', 'between:200,9000'],
            'cesarea' => ['nullable', 'boolean'],
        ];
    }
}
