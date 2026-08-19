<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Prestacion;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DiccionarioController extends Controller
{
    public function index(): View
    {
        $variables = Variable::query()
            ->withCount(['detalles', 'prestaciones'])
            ->orderBy('codigo')
            ->orderBy('nombre')
            ->get();

        return view('admin.bioestadistica.diccionario.index', compact('variables'));
    }

    public function show(Variable $variable): View
    {
        $variable->load(['detalles.prestaciones']);

        return view('admin.bioestadistica.diccionario.show', compact('variable'));
    }

    public function storeVariable(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:20'],
            'nombre' => [
                'required',
                'string',
                'max:200',
                Rule::unique(Variable::class, 'nombre')
                    ->where('codigo', $request->input('codigo'))
                    ->withoutTrashed(),
            ],
        ]);
        $variable = Variable::create($data + ['activo' => true]);

        return redirect()
            ->route('bioestadistica.diccionario.show', $variable)
            ->with('success', 'Variable creada.');
    }

    public function storeDetalle(Request $request, Variable $variable): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:250',
                Rule::unique(VariableDetalle::class, 'nombre')
                    ->where('variable_id', $variable->id)
                    ->withoutTrashed(),
            ],
        ]);
        $variable->detalles()->create($data + ['activo' => true]);

        return back()->with('success', 'Detalle creado.');
    }

    public function storePrestacion(Request $request, VariableDetalle $detalle): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:400',
                Rule::unique(Prestacion::class, 'nombre')
                    ->where('detalle_id', $detalle->id)
                    ->withoutTrashed(),
            ],
        ]);
        $detalle->prestaciones()->create($data + ['activo' => true]);

        return back()->with('success', 'Prestación creada.');
    }
}
