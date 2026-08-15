<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\AreaGestion;
use App\Models\Bioestadistica\GradoComplejidad;
use App\Models\Bioestadistica\Microred;
use App\Models\Bioestadistica\TipoEstablecimiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClasificacionController extends Controller
{
    private const MODELS = [
        'microredes' => Microred::class,
        'tipos-establecimiento' => TipoEstablecimiento::class,
        'grados-complejidad' => GradoComplejidad::class,
        'areas-gestion' => AreaGestion::class,
    ];

    public function index(): View
    {
        return view('admin.bioestadistica.clasificaciones.index', [
            'microredes' => Microred::orderBy('nombre')->get(),
            'tipos' => TipoEstablecimiento::orderBy('nombre')->get(),
            'grados' => GradoComplejidad::orderBy('codigo')->get(),
            'areas' => AreaGestion::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request, string $tipo): RedirectResponse
    {
        $model = $this->modelFor($tipo);
        $rules = match ($tipo) {
            'grados-complejidad' => [
                'codigo' => ['required', 'string', 'max:10'],
                'descripcion' => ['required', 'string', 'max:200'],
            ],
            'tipos-establecimiento' => [
                'nombre' => ['required', 'string', 'max:150'],
                'descripcion' => ['nullable', 'string', 'max:1000'],
            ],
            default => ['nombre' => ['required', 'string', 'max:150']],
        };

        $validated = $request->validate($rules);
        if ($tipo === 'grados-complejidad') {
            $model::firstOrCreate(
                ['codigo' => $validated['codigo'], 'descripcion' => $validated['descripcion']],
                ['activo' => true]
            );
        } else {
            $model::updateOrCreate(['nombre' => $validated['nombre']], $validated);
        }

        return back()->with('success', 'Clasificación guardada.');
    }

    public function destroy(string $tipo, int $id): RedirectResponse
    {
        $model = $this->modelFor($tipo);
        $model::findOrFail($id)->delete();

        return back()->with('success', 'Clasificación eliminada.');
    }

    private function modelFor(string $tipo): string
    {
        abort_unless(isset(self::MODELS[$tipo]), 404);

        return self::MODELS[$tipo];
    }
}
