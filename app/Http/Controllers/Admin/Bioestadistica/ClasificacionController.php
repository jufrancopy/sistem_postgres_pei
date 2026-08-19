<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\AreaGestion;
use App\Models\Bioestadistica\EstructuraDepartamento;
use App\Models\Bioestadistica\EstructuraServicio;
use App\Models\Bioestadistica\GradoComplejidad;
use App\Models\Bioestadistica\Microred;
use App\Models\Bioestadistica\TipoEstablecimiento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClasificacionController extends Controller
{
    private const MODELS = [
        'microredes' => Microred::class,
        'tipos-establecimiento' => TipoEstablecimiento::class,
        'grados-complejidad' => GradoComplejidad::class,
        'areas-gestion' => AreaGestion::class,
        'departamentos' => EstructuraDepartamento::class,
        'servicios' => EstructuraServicio::class,
    ];

    public function index(): View
    {
        return view('admin.bioestadistica.clasificaciones.index', [
            'microredes' => Microred::orderBy('nombre')->get(),
            'tipos' => TipoEstablecimiento::orderBy('nombre')->get(),
            'grados' => GradoComplejidad::orderBy('codigo')->get(),
            'areas' => AreaGestion::orderBy('nombre')->get(),
            'departamentos' => EstructuraDepartamento::with(['servicios' => fn ($q) => $q->orderBy('nombre')])
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function servicios(Request $request): JsonResponse
    {
        $request->validate(['departamento_id' => ['required', 'integer']]);

        return response()->json([
            'data' => EstructuraServicio::where('departamento_id', $request->integer('departamento_id'))
                ->where('activo', true)
                ->orderBy('nombre')
                ->get(['id', 'nombre']),
        ]);
    }

    public function store(Request $request, string $tipo): RedirectResponse
    {
        $model = $this->modelFor($tipo);
        $validated = $request->validate($this->rules($tipo));
        $validated['activo'] = $request->boolean('activo', true);

        if ($tipo === 'grados-complejidad') {
            $model::firstOrCreate(
                ['codigo' => $validated['codigo'], 'descripcion' => $validated['descripcion']],
                ['activo' => $validated['activo']]
            );
        } else {
            $model::create($validated);
        }

        return back()->with('success', 'Clasificación creada.');
    }

    public function update(Request $request, string $tipo, int $id): RedirectResponse
    {
        $model = $this->modelFor($tipo);
        $item = $model::findOrFail($id);
        $validated = $request->validate($this->rules($tipo, $id));
        $validated['activo'] = $request->boolean('activo');

        $item->update($validated);

        return back()->with('success', 'Clasificación actualizada.');
    }

    public function destroy(string $tipo, int $id): RedirectResponse
    {
        $model = $this->modelFor($tipo);
        $model::findOrFail($id)->delete();

        return back()->with('success', 'Clasificación eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(string $tipo, ?int $id = null): array
    {
        $model = $this->modelFor($tipo);

        return match ($tipo) {
            'grados-complejidad' => [
                'codigo' => ['required', 'string', 'max:10'],
                'descripcion' => [
                    'required',
                    'string',
                    'max:200',
                    Rule::unique($model, 'descripcion')
                        ->where(fn ($query) => $query->where('codigo', (string) request('codigo')))
                        ->ignore($id)
                        ->withoutTrashed(),
                ],
            ],
            'tipos-establecimiento' => [
                'nombre' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique($model, 'nombre')->ignore($id)->withoutTrashed(),
                ],
                'descripcion' => ['nullable', 'string', 'max:1000'],
            ],
            'servicios' => [
                'departamento_id' => [
                    'required',
                    'integer',
                    Rule::exists(EstructuraDepartamento::class, 'id')->withoutTrashed(),
                ],
                'nombre' => [
                    'required',
                    'string',
                    'max:200',
                    Rule::unique($model, 'nombre')
                        ->where('departamento_id', request('departamento_id'))
                        ->ignore($id)
                        ->withoutTrashed(),
                ],
            ],
            'departamentos' => [
                'nombre' => [
                    'required',
                    'string',
                    'max:200',
                    Rule::unique($model, 'nombre')->ignore($id)->withoutTrashed(),
                ],
            ],
            default => [
                'nombre' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique($model, 'nombre')->ignore($id)->withoutTrashed(),
                ],
            ],
        };
    }

    private function modelFor(string $tipo): string
    {
        abort_unless(isset(self::MODELS[$tipo]), 404);

        return self::MODELS[$tipo];
    }
}
