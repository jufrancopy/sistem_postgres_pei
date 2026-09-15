<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Admin\Bioestadistica\Concerns\RespondsWithDataTables;
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
    use RespondsWithDataTables;

    public function index(Request $request): View
    {
        return view('admin.bioestadistica.geografia.index', $this->formOptions());
    }

    public function datatable(Request $request): JsonResponse
    {
        $canUpdate = $request->user()->can('bio.geo.update');
        $canDelete = $request->user()->can('bio.geo.delete');

        $base = Establecimiento::query()
            ->with([
                'distrito.departamento',
                'microred',
                'tipoEstablecimiento',
                'gradoComplejidad',
                'areaGestion',
            ])
            ->when($request->filled('q'), fn ($query) => $query->buscar($request->string('q')->toString()))
            ->when($request->filled('departamento_id'), fn ($query) => $query
                ->whereHas('distrito', fn ($distritos) => $distritos
                    ->where('departamento_id', $request->integer('departamento_id'))))
            ->when($request->filled('distrito_id'), fn ($query) => $query
                ->where('distrito_id', $request->integer('distrito_id')))
            ->when($request->filled('area_gestion_id'), fn ($query) => $query
                ->where('area_gestion_id', $request->integer('area_gestion_id')));

        return $this->dataTablesJson(
            $request,
            $base,
            function ($query, string $search): void {
                $query->buscar($search);
            },
            [
                0 => 'codigo',
                1 => 'nombre',
                2 => 'codigo_sih',
                3 => null,
                4 => 'nivel_atencion',
                5 => null,
                6 => null,
                7 => null,
                8 => null,
                9 => 'prestador',
                10 => 'latitud',
                11 => 'longitud',
                12 => null,
                13 => 'situacion_inmueble',
                14 => 'observacion',
                15 => null,
            ],
            function (Establecimiento $establecimiento) use ($canUpdate, $canDelete) {
                $complejidad = $establecimiento->gradoComplejidad
                    ? 'Complejidad '.$establecimiento->gradoComplejidad->codigo.' — '.$establecimiento->gradoComplejidad->descripcion
                    : '';
                $distrito = $establecimiento->distrito
                    ? e($establecimiento->distrito->nombre)
                    : '<span class="badge badge-warning">Pendiente</span>';

                $actions = '<div class="bio-actions">';
                if ($canUpdate) {
                    $actions .= '<a class="btn btn-outline-primary btn-sm" href="'.e(route('bioestadistica.geografia.establecimientos.edit', $establecimiento)).'" title="Editar"><i class="material-icons">edit</i></a>';
                }
                if ($canDelete) {
                    $actions .= '<form method="POST" action="'.e(route('bioestadistica.geografia.establecimientos.destroy', $establecimiento)).'" class="d-inline bio-confirm-form" data-confirm="¿Eliminar este establecimiento?">'
                        .csrf_field().method_field('DELETE')
                        .'<button class="btn btn-outline-danger btn-sm" type="submit" title="Eliminar"><i class="material-icons">delete</i></button></form>';
                }
                $actions .= '</div>';

                return [
                    'codigo' => e($establecimiento->codigo),
                    'nombre' => e($establecimiento->nombre),
                    'codigo_sih' => e((string) $establecimiento->codigo_sih),
                    'tipo' => e($establecimiento->tipoEstablecimiento?->nombre ?? ''),
                    'nivel' => e((string) $establecimiento->nivel_atencion),
                    'complejidad' => e($complejidad),
                    'departamento' => e($establecimiento->distrito?->departamento?->nombre ?? ''),
                    'distrito' => $distrito,
                    'microred' => e($establecimiento->microred?->nombre ?? ''),
                    'prestador' => e((string) $establecimiento->prestador),
                    'latitud' => e((string) $establecimiento->latitud),
                    'longitud' => e((string) $establecimiento->longitud),
                    'area' => e($establecimiento->areaGestion?->nombre ?? ''),
                    'situacion' => e((string) $establecimiento->situacion_inmueble),
                    'observacion' => e((string) $establecimiento->observacion),
                    'acciones' => $actions,
                ];
            },
            'nombre',
            'asc'
        );
    }

    public function editEstablecimiento(Establecimiento $establecimiento): View
    {
        $establecimiento->load([
            'distrito.departamento',
            'microred',
            'tipoEstablecimiento',
            'gradoComplejidad',
            'areaGestion',
        ]);

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
            'codigo' => ['required', 'string', 'max:10', Rule::unique(Departamento::class, 'codigo')->withoutTrashed()],
            'nombre' => ['required', 'string', 'max:150', Rule::unique(Departamento::class, 'nombre')->withoutTrashed()],
            'activo' => ['nullable', 'boolean'],
        ]));

        return back()->with('success', 'Departamento/región creado.');
    }

    public function storeDistrito(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'departamento_id' => ['required', 'integer', Rule::exists(Departamento::class, 'id')->withoutTrashed()],
            'codigo' => ['nullable', 'string', 'max:20'],
            'nombre' => [
                'required', 'string', 'max:150',
                Rule::unique(Distrito::class, 'nombre')
                    ->where('departamento_id', $request->input('departamento_id'))
                    ->withoutTrashed(),
            ],
            'activo' => ['nullable', 'boolean'],
        ]);
        Distrito::create($data);

        return back()->with('success', 'Distrito creado.');
    }

    public function storeEstablecimiento(Request $request): RedirectResponse
    {
        $establecimiento = Establecimiento::create($this->validateEstablecimiento($request));

        if ($establecimiento->codigo) {
            $riissEst = \App\Models\Riiss\Establecimiento::where('id_establecimiento', $establecimiento->codigo)->first();
            if ($riissEst) {
                $areaNombre = $establecimiento->areaGestion?->nombre;
                if ($areaNombre) {
                    $riissEst->area_gestion = $areaNombre;
                }
                $riissEst->nombre_oficial = $establecimiento->nombre;
                $riissEst->departamento = $establecimiento->distrito?->departamento?->nombre ?? $riissEst->departamento;
                $riissEst->latitude = $establecimiento->latitud ?? $riissEst->latitude;
                $riissEst->longitude = $establecimiento->longitud ?? $riissEst->longitude;
                $riissEst->save();
            }
        }

        return back()->with('success', 'Establecimiento creado.');
    }

    public function updateEstablecimiento(Request $request, Establecimiento $establecimiento): RedirectResponse
    {
        $establecimiento->update($this->validateEstablecimiento($request, $establecimiento));

        if ($establecimiento->codigo) {
            $riissEst = \App\Models\Riiss\Establecimiento::where('id_establecimiento', $establecimiento->codigo)->first();
            if ($riissEst) {
                $areaNombre = $establecimiento->areaGestion?->nombre;
                if ($areaNombre) {
                    $riissEst->area_gestion = $areaNombre;
                }
                $riissEst->nombre_oficial = $establecimiento->nombre;
                $riissEst->departamento = $establecimiento->distrito?->departamento?->nombre ?? $riissEst->departamento;
                $riissEst->latitude = $establecimiento->latitud ?? $riissEst->latitude;
                $riissEst->longitude = $establecimiento->longitud ?? $riissEst->longitude;
                $riissEst->save();
            }
        }

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
                Rule::unique(Establecimiento::class, 'codigo')->ignore($establecimiento?->id)->withoutTrashed(),
            ],
            'nombre' => ['required', 'string', 'max:250'],
            'departamento_id' => ['required', 'integer', Rule::exists(Departamento::class, 'id')->withoutTrashed()],
            'distrito_id' => [
                'required',
                'integer',
                Rule::exists(Distrito::class, 'id')
                    ->where('departamento_id', $request->input('departamento_id'))
                    ->withoutTrashed(),
            ],
            'microred_id' => ['nullable', 'integer', Rule::exists(Microred::class, 'id')->withoutTrashed()],
            'tipo_establecimiento_id' => ['nullable', 'integer', Rule::exists(TipoEstablecimiento::class, 'id')->withoutTrashed()],
            'grado_complejidad_id' => ['nullable', 'integer', Rule::exists(GradoComplejidad::class, 'id')->withoutTrashed()],
            'area_gestion_id' => ['nullable', 'integer', Rule::exists(AreaGestion::class, 'id')->withoutTrashed()],
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
