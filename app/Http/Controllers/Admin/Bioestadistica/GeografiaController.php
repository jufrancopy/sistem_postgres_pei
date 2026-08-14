<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\AreaGestion;
use App\Models\Bioestadistica\Departamento;
use App\Models\Bioestadistica\Distrito;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\GradoComplejidad;
use App\Models\Bioestadistica\Microred;
use App\Models\Bioestadistica\TipoEstablecimiento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GeografiaController extends Controller
{
    public function index(Request $request): View
    {
        $establecimientos = Establecimiento::query()
            ->with(['distrito.departamento', 'microred', 'tipoEstablecimiento', 'gradoComplejidad', 'areaGestion'])
            ->buscar($request->string('q')->toString())
            ->when($request->filled('departamento_id'), fn ($query) => $query
                ->whereHas('distrito', fn ($distritos) => $distritos
                    ->where('departamento_id', $request->integer('departamento_id'))))
            ->when($request->filled('distrito_id'), fn ($query) => $query
                ->where('distrito_id', $request->integer('distrito_id')))
            ->orderBy('nombre')
            ->paginate(30)
            ->withQueryString();

        return view('admin.bioestadistica.geografia.index', array_merge([
            'establecimientos' => $establecimientos,
        ], $this->formOptions()));
    }

    public function editEstablecimiento(Establecimiento $establecimiento): View
    {
        $establecimiento->load(['distrito.departamento', 'microred', 'tipoEstablecimiento', 'gradoComplejidad', 'areaGestion']);

        return view('admin.bioestadistica.geografia.edit-establecimiento', array_merge([
            'establecimiento' => $establecimiento,
        ], $this->formOptions()));
    }

    public function distritos(Request $request): JsonResponse
    {
        $request->validate(['departamento_id' => ['required', 'integer']]);

        return response()->json([
            'data' => Distrito::where('departamento_id', $request->integer('departamento_id'))
                ->where('activo', true)
                ->orderBy('nombre')
                ->get(['id', 'codigo', 'nombre']),
        ]);
    }

    public function establecimientos(Request $request): JsonResponse
    {
        $request->validate(['distrito_id' => ['required', 'integer']]);

        return response()->json([
            'data' => Establecimiento::where('distrito_id', $request->integer('distrito_id'))
                ->orderBy('nombre')
                ->get(['id', 'codigo', 'nombre']),
        ]);
    }

    public function storeDepartamento(Request $request): RedirectResponse
    {
        Departamento::create($request->validate([
            'codigo' => ['required', 'string', 'max:10', Rule::unique('bioestadistica.departamentos', 'codigo')],
            'nombre' => ['required', 'string', 'max:150', Rule::unique('bioestadistica.departamentos', 'nombre')],
            'activo' => ['nullable', 'boolean'],
        ]));

        return back()->with('success', 'Departamento creado.');
    }

    public function storeDistrito(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'departamento_id' => ['required', 'integer', Rule::exists('bioestadistica.departamentos', 'id')],
            'codigo' => ['nullable', 'string', 'max:20'],
            'nombre' => [
                'required', 'string', 'max:150',
                Rule::unique('bioestadistica.distritos', 'nombre')
                    ->where('departamento_id', $request->input('departamento_id')),
            ],
            'activo' => ['nullable', 'boolean'],
        ]);
        Distrito::create($data);

        return back()->with('success', 'Distrito creado.');
    }

    public function storeEstablecimiento(Request $request): RedirectResponse
    {
        Establecimiento::create($this->validateEstablecimiento($request));

        return back()->with('success', 'Establecimiento creado.');
    }

    public function updateEstablecimiento(Request $request, Establecimiento $establecimiento): RedirectResponse
    {
        $establecimiento->update($this->validateEstablecimiento($request, $establecimiento));

        return back()->with('success', 'Establecimiento actualizado.');
    }

    public function destroyEstablecimiento(Establecimiento $establecimiento): RedirectResponse
    {
        $establecimiento->delete();

        return back()->with('success', 'Establecimiento eliminado.');
    }

    private function validateEstablecimiento(Request $request, ?Establecimiento $establecimiento = null): array
    {
        $data = $request->validate([
            'codigo' => [
                'required', 'string', 'max:30',
                Rule::unique('bioestadistica.establecimientos', 'codigo')->ignore($establecimiento?->id),
            ],
            'nombre' => ['required', 'string', 'max:250'],
            'departamento_id' => ['required', 'integer', Rule::exists('bioestadistica.departamentos', 'id')],
            'distrito_id' => [
                'required',
                'integer',
                Rule::exists('bioestadistica.distritos', 'id')
                    ->where('departamento_id', $request->input('departamento_id')),
            ],
            'microred_id' => ['nullable', 'integer', Rule::exists('bioestadistica.microredes', 'id')],
            'tipo_establecimiento_id' => ['nullable', 'integer', Rule::exists('bioestadistica.tipos_establecimiento', 'id')],
            'grado_complejidad_id' => ['nullable', 'integer', Rule::exists('bioestadistica.grados_complejidad', 'id')],
            'area_gestion_id' => ['nullable', 'integer', Rule::exists('bioestadistica.areas_gestion', 'id')],
            'nivel_atencion' => ['nullable', 'string', 'max:50'],
            'prestador' => ['nullable', 'string', 'max:80'],
            'situacion_inmueble' => ['nullable', 'string', 'max:120'],
            'sistema' => ['nullable', 'string', 'max:30'],
            'codigo_sih' => ['nullable', 'string', 'max:30'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'observacion' => ['nullable', 'string', 'max:2000'],
        ]);

        unset($data['departamento_id']);

        return $data;
    }

    private function formOptions(): array
    {
        return [
            'departamentos' => Departamento::orderBy('nombre')->get(),
            'distritos' => Distrito::with('departamento')->orderBy('nombre')->get(),
            'microredes' => Microred::orderBy('nombre')->get(),
            'tipos' => TipoEstablecimiento::orderBy('nombre')->get(),
            'grados' => GradoComplejidad::orderBy('codigo')->get(),
            'areas' => AreaGestion::orderBy('nombre')->get(),
            'niveles' => Establecimiento::whereNotNull('nivel_atencion')->distinct()->orderBy('nivel_atencion')->pluck('nivel_atencion'),
            'prestadores' => Establecimiento::whereNotNull('prestador')->distinct()->orderBy('prestador')->pluck('prestador'),
            'situaciones' => Establecimiento::whereNotNull('situacion_inmueble')->distinct()->orderBy('situacion_inmueble')->pluck('situacion_inmueble'),
        ];
    }
}
