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
        $data = $this->validateVariable($request);
        $variable = Variable::create($data + ['activo' => true]);

        return redirect()
            ->route('bioestadistica.diccionario.show', $variable)
            ->with('success', 'Variable creada.');
    }

    public function updateVariable(Request $request, Variable $variable): RedirectResponse
    {
        $data = $this->validateVariable($request, $variable);
        $data['activo'] = $request->boolean('activo', true);
        $variable->update($data);

        return back()->with('success', 'Variable actualizada.');
    }

    public function destroyVariable(Variable $variable): RedirectResponse
    {
        $variable->load('detalles.prestaciones');
        foreach ($variable->detalles as $detalle) {
            foreach ($detalle->prestaciones as $prestacion) {
                $prestacion->delete();
            }
            $detalle->delete();
        }
        $variable->delete();

        return redirect()
            ->route('bioestadistica.diccionario.index')
            ->with('success', 'Variable eliminada.');
    }

    public function storeDetalle(Request $request, Variable $variable): RedirectResponse
    {
        $data = $this->validateDetalle($request, $variable);
        $variable->detalles()->create($data + ['activo' => true]);

        return back()->with('success', 'Detalle creado.');
    }

    public function updateDetalle(Request $request, VariableDetalle $detalle): RedirectResponse
    {
        $data = $this->validateDetalle($request, $detalle->variable, $detalle);
        $data['activo'] = $request->boolean('activo', true);
        $detalle->update($data);

        return back()->with('success', 'Detalle actualizado.');
    }

    public function destroyDetalle(VariableDetalle $detalle): RedirectResponse
    {
        $detalle->load('prestaciones');
        foreach ($detalle->prestaciones as $prestacion) {
            $prestacion->delete();
        }
        $detalle->delete();

        return back()->with('success', 'Detalle eliminado.');
    }

    public function storePrestacion(Request $request, VariableDetalle $detalle): RedirectResponse
    {
        $data = $this->validatePrestacion($request, $detalle);
        $detalle->prestaciones()->create($data + ['activo' => true]);

        return back()->with('success', 'Prestación creada.');
    }

    public function updatePrestacion(Request $request, Prestacion $prestacion): RedirectResponse
    {
        $data = $this->validatePrestacion($request, $prestacion->detalle, $prestacion);
        $data['activo'] = $request->boolean('activo', true);
        $prestacion->update($data);

        return back()->with('success', 'Prestación actualizada.');
    }

    public function destroyPrestacion(Prestacion $prestacion): RedirectResponse
    {
        $prestacion->delete();

        return back()->with('success', 'Prestación eliminada.');
    }

    private function validateVariable(Request $request, ?Variable $variable = null): array
    {
        return $request->validate([
            'codigo' => ['required', 'string', 'max:20'],
            'nombre' => [
                'required',
                'string',
                'max:200',
                Rule::unique(Variable::class, 'nombre')
                    ->where('codigo', $request->input('codigo'))
                    ->ignore($variable?->id)
                    ->withoutTrashed(),
            ],
        ]);
    }

    private function validateDetalle(Request $request, Variable $variable, ?VariableDetalle $detalle = null): array
    {
        return $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:250',
                Rule::unique(VariableDetalle::class, 'nombre')
                    ->where('variable_id', $variable->id)
                    ->ignore($detalle?->id)
                    ->withoutTrashed(),
            ],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function validatePrestacion(Request $request, VariableDetalle $detalle, ?Prestacion $prestacion = null): array
    {
        return $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:400',
                Rule::unique(Prestacion::class, 'nombre')
                    ->where('detalle_id', $detalle->id)
                    ->ignore($prestacion?->id)
                    ->withoutTrashed(),
            ],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
