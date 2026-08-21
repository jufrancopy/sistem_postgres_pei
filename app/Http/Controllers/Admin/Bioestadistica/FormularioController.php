<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\FormSeccion;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FormularioController extends Controller
{
    public function index(): View
    {
        return view('admin.bioestadistica.formularios.index', [
            'formularios' => Formulario::withCount('secciones')->ordenSp()->limit(500)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $formulario = Formulario::create($this->validateFormulario($request));

        return redirect()->route('bioestadistica.formularios.edit', $formulario)
            ->with('success', 'Formulario creado.');
    }

    public function edit(Formulario $formulario): View
    {
        $formulario->load(['secciones.fields.detalle.variable']);

        return view('admin.bioestadistica.formularios.edit', [
            'formulario' => $formulario,
            'detalles' => VariableDetalle::with('variable')
                ->where('activo', true)
                ->orderBy('nombre')
                ->get()
                ->sortBy(fn ($detalle) => $detalle->variable->codigo.' '.$detalle->nombre)
                ->values(),
            'fieldTypes' => [
                'text' => 'Texto', 'textarea' => 'Texto largo', 'integer' => 'Número entero',
                'decimal' => 'Decimal', 'date' => 'Fecha', 'time' => 'Hora', 'boolean' => 'Booleano',
                'select' => 'Lista desplegable', 'multiselect' => 'Selección múltiple',
                'radio' => 'Radio button', 'tabla' => 'Tabla', 'subtabla' => 'Subtabla', 'matriz' => 'Matriz',
            ],
        ]);
    }

    public function update(Request $request, Formulario $formulario): RedirectResponse
    {
        $formulario->update($this->validateFormulario($request, $formulario));

        return back()->with('success', 'Formulario actualizado.');
    }

    public function publish(Formulario $formulario): RedirectResponse
    {
        abort_if($formulario->secciones()->whereHas('fields')->doesntExist(), 422, 'El formulario debe tener al menos un campo.');

        $formulario->update([
            'estado' => 'activo',
            'version' => $formulario->estado === 'activo'
                ? $formulario->version + 1
                : $formulario->version,
        ]);

        return back()->with('success', 'Formulario publicado.');
    }

    public function destroy(Formulario $formulario): RedirectResponse
    {
        $formulario->delete();

        return redirect()->route('bioestadistica.formularios.index')
            ->with('success', 'Formulario archivado.');
    }

    public function storeSeccion(Request $request, Formulario $formulario): RedirectResponse
    {
        $formulario->secciones()->create($request->validate([
            'titulo' => ['required', 'string', 'max:250'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]));

        return back()->with('success', 'Sección agregada.');
    }

    public function destroySeccion(FormSeccion $seccion): RedirectResponse
    {
        $seccion->delete();

        return back()->with('success', 'Sección eliminada.');
    }

    public function storeField(Request $request, FormSeccion $seccion): RedirectResponse
    {
        $data = $this->validateField($request, $seccion);
        $data['config'] = $this->parseConfig($data['config'] ?? null);
        $data['required'] = $request->boolean('required');
        $data = $this->syncFieldSource($data);
        $seccion->fields()->create($data);

        return back()->with('success', 'Campo agregado.');
    }

    public function updateField(Request $request, Field $field): RedirectResponse
    {
        $data = $this->validateField($request, $field->seccion, $field);
        $data['config'] = $this->parseConfig($data['config'] ?? null);
        $data['required'] = $request->boolean('required');
        $data = $this->syncFieldSource($data);
        $field->update($data);

        return back()->with('success', 'Campo actualizado.');
    }

    public function destroyField(Field $field): RedirectResponse
    {
        $field->delete();

        return back()->with('success', 'Campo eliminado.');
    }

    private function validateFormulario(Request $request, ?Formulario $formulario = null): array
    {
        return $request->validate([
            'codigo' => [
                'required', 'string', 'max:20',
                Rule::unique(Formulario::class, 'codigo')->ignore($formulario?->id)->withoutTrashed(),
            ],
            'nombre' => ['required', 'string', 'max:250'],
            'descripcion' => ['nullable', 'string', 'max:3000'],
            'estado' => ['nullable', Rule::in(['borrador', 'activo', 'archivado'])],
            'periodicidad' => ['required', Rule::in(['diaria', 'semanal', 'mensual', 'trimestral', 'anual', 'ad_hoc'])],
            'layout_type' => ['required', Rule::in(['tabular', 'nominativo', 'matriz'])],
        ]);
    }

    private function validateField(Request $request, FormSeccion $seccion, ?Field $field = null): array
    {
        return $request->validate([
            'code' => [
                'required', 'alpha_dash', 'max:100',
                Rule::unique(Field::class, 'code')
                    ->where('seccion_id', $seccion->id)
                    ->ignore($field?->id)
                    ->withoutTrashed(),
            ],
            'label' => ['required', 'string', 'max:400'],
            'type' => ['required', Rule::in([
                'text', 'textarea', 'integer', 'decimal', 'date', 'time', 'boolean',
                'select', 'multiselect', 'radio', 'tabla', 'subtabla', 'matriz',
            ])],
            'required' => ['nullable', 'boolean'],
            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric', 'gte:min_value'],
            'validation_regex' => ['nullable', 'string', 'max:500'],
            'tooltip' => ['nullable', 'string', 'max:400'],
            'help_text' => ['nullable', 'string', 'max:2000'],
            'detalle_id' => ['nullable', 'integer', Rule::exists(VariableDetalle::class, 'id')->withoutTrashed()],
            'parent_field_id' => ['nullable', 'integer', Rule::exists(Field::class, 'id')->withoutTrashed()],
            'config' => ['nullable'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function syncFieldSource(array $data): array
    {
        if (! empty($data['detalle_id'])) {
            $data['config'] = array_merge($data['config'] ?? [], [
                'row_source' => 'diccionario',
                'row_detalle_id' => (int) $data['detalle_id'],
            ]);
        }

        return $data;
    }

    private function parseConfig(mixed $config): ?array
    {
        if (blank($config)) {
            return null;
        }
        if (is_array($config)) {
            return $config;
        }

        try {
            return json_decode($config, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw ValidationException::withMessages([
                'config' => 'La configuración debe ser un JSON válido.',
            ]);
        }
    }
}
