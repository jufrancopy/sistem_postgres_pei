<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\EstablecimientoServicio;
use App\Models\Bioestadistica\EstructuraDepartamento;
use App\Models\Bioestadistica\EstructuraServicio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EstablecimientoEstructuraController extends Controller
{
    public function index(Request $request): View
    {
        $establecimientos = Establecimiento::query()
            ->with(['distrito.departamento', 'unidades.departamento', 'unidades.servicio'])
            ->buscar($request->string('q')->toString())
            ->orderBy('nombre')
            ->paginate(25)
            ->withQueryString();

        return view('admin.bioestadistica.estructura.index', [
            'establecimientos' => $establecimientos,
            'establecimientosLista' => Establecimiento::orderBy('nombre')->get(['id', 'codigo', 'nombre']),
            'departamentos' => EstructuraDepartamento::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'establecimiento_id' => ['required', 'integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
            'departamento_id' => ['required', 'integer', Rule::exists(EstructuraDepartamento::class, 'id')->withoutTrashed()],
            'servicio_id' => [
                'required',
                'integer',
                Rule::exists(EstructuraServicio::class, 'id')
                    ->where('departamento_id', $request->input('departamento_id'))
                    ->withoutTrashed(),
            ],
        ]);

        $unidad = EstablecimientoServicio::withTrashed()->firstOrNew([
            'establecimiento_id' => $data['establecimiento_id'],
            'departamento_id' => $data['departamento_id'],
            'servicio_id' => $data['servicio_id'],
        ]);
        if ($unidad->exists && $unidad->trashed()) {
            $unidad->restore();
        }
        $unidad->save();

        return back()->with('success', 'Departamento y servicio asociados al establecimiento.');
    }

    public function destroy(EstablecimientoServicio $unidad): RedirectResponse
    {
        $unidad->delete();

        return back()->with('success', 'Asociación eliminada.');
    }

    public function cortes(Request $request): JsonResponse
    {
        $request->validate([
            'establecimiento_id' => ['required', 'integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
        ]);

        $cortes = EstablecimientoServicio::query()
            ->with(['departamento', 'servicio'])
            ->where('establecimiento_id', $request->integer('establecimiento_id'))
            ->orderBy('id')
            ->get()
            ->map(fn (EstablecimientoServicio $unidad) => [
                'id' => $unidad->id,
                'departamento_id' => $unidad->departamento_id,
                'servicio_id' => $unidad->servicio_id,
                'etiqueta' => $unidad->etiqueta(),
            ]);

        return response()->json(['data' => $cortes]);
    }
}
