<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Dictionary\CatalogItemAdminService;
use App\Application\Bioestadistica\Dictionary\CatalogType;
use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DiccionarioController extends Controller
{
    public function __construct(private CatalogItemAdminService $catalogAdmin)
    {
    }

    public function index(): View
    {
        $variables = Variable::query()
            ->withCount('detalles')
            ->with(['detalles.catalogoItems'])
            ->orderBy('codigo')
            ->orderBy('nombre')
            ->get()
            ->each(function (Variable $variable) {
                $variable->setAttribute(
                    'catalogo_items_count',
                    $variable->detalles->sum(fn (VariableDetalle $detalle) => $detalle->catalogoItems->count())
                );
            });

        return view('admin.bioestadistica.diccionario.index', compact('variables'));
    }

    public function show(Variable $variable): View
    {
        $variable->load(['detalles.catalogoItems', 'detalles.variable']);
        $catalogRowsByDetalle = $variable->detalles->mapWithKeys(
            fn (VariableDetalle $detalle) => [$detalle->id => $this->catalogAdmin->rowsForDetalle($detalle)]
        );
        $catalogTypes = CatalogType::options();
        $layoutOptions = [
            '' => 'Tabla estándar',
            'tabla' => 'Tabla',
            'matriz' => 'Matriz (SP9)',
            'cruce' => 'Cruce (SP8 legacy)',
        ];

        return view('admin.bioestadistica.diccionario.show', compact(
            'variable',
            'catalogRowsByDetalle',
            'catalogTypes',
            'layoutOptions'
        ));
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
        $variable->load('detalles.catalogoItems');
        foreach ($variable->detalles as $detalle) {
            foreach ($detalle->catalogoItems as $bridge) {
                $bridge->delete();
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
        $data['catalogo_tipo'] = ($data['catalogo_tipo'] ?? '') !== '' ? $data['catalogo_tipo'] : null;
        $data['layout_captura'] = ($data['layout_captura'] ?? '') !== '' ? $data['layout_captura'] : null;
        $data['orden'] = (int) ($data['orden'] ?? 0);
        $nombre = trim((string) $data['nombre']);
        $data['nombre'] = $nombre;

        $existing = VariableDetalle::withTrashed()
            ->where('variable_id', $variable->id)
            ->whereRaw('upper(trim(nombre)) = ?', [mb_strtoupper($nombre)])
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                $existing->fill($data + ['activo' => true])->save();

                return back()->with('success', 'Tipo de registro restaurado (estaba eliminado).');
            }

            return back()
                ->withInput()
                ->withErrors(['nombre' => 'Ya existe el tipo de registro «'.$nombre.'» en esta variable.']);
        }

        try {
            $ordenItems = (string) ($data['orden_items'] ?? VariableDetalle::ORDEN_ITEMS_MANUAL);
            unset($data['orden_items']);
            $detalle = $variable->detalles()->make($data + ['activo' => true]);
            $detalle->setOrdenItemsMode($ordenItems);
            $detalle->save();
        } catch (UniqueConstraintViolationException) {
            return back()
                ->withInput()
                ->withErrors(['nombre' => 'Ya existe el tipo de registro «'.$nombre.'» en esta variable.']);
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'nombre' => 'No se pudo crear el tipo de registro. '
                        .class_basename($e).': '.$e->getMessage(),
                ]);
        }

        return back()->with('success', 'Tipo de registro creado.');
    }

    public function updateDetalle(Request $request, VariableDetalle $detalle): RedirectResponse
    {
        $data = $this->validateDetalle($request, $detalle->variable, $detalle);
        $data['activo'] = $request->boolean('activo', true);
        $data['catalogo_tipo'] = ($data['catalogo_tipo'] ?? '') !== '' ? $data['catalogo_tipo'] : null;
        $data['layout_captura'] = ($data['layout_captura'] ?? '') !== '' ? $data['layout_captura'] : null;
        $ordenItems = (string) ($data['orden_items'] ?? VariableDetalle::ORDEN_ITEMS_MANUAL);
        unset($data['orden_items']);
        $detalle->fill($data);
        $detalle->setOrdenItemsMode($ordenItems);
        $detalle->save();

        return back()->with('success', 'Tipo de registro actualizado.');
    }

    public function reordenarPrestacionesAlfabetico(VariableDetalle $detalle): RedirectResponse
    {
        $count = $this->catalogAdmin->applyAlphabeticalOrden($detalle);

        return back()->with(
            'success',
            "Prestaciones reordenadas A→Z ({$count} ítems). Modo de orden: manual (valores de orden actualizados)."
        );
    }

    public function destroyDetalle(VariableDetalle $detalle): RedirectResponse
    {
        $detalle->load('catalogoItems');
        foreach ($detalle->catalogoItems as $bridge) {
            $bridge->delete();
        }
        $detalle->delete();

        return back()->with('success', 'Tipo de registro eliminado.');
    }

    public function storePrestacion(Request $request, VariableDetalle $detalle): RedirectResponse
    {
        $data = $this->validatePrestacionNombre($request);
        $detalle->loadMissing('variable');
        (new HealthVariableDictionary)->remember(
            $detalle->variable->codigo,
            $detalle->variable->nombre,
            $detalle->nombre,
            $data['nombre'],
            (int) ($data['orden'] ?? 0)
        );

        return back()->with('success', 'Prestación agregada al catálogo.');
    }

    public function linkPrestacion(Request $request, VariableDetalle $detalle): RedirectResponse
    {
        $data = $request->validate([
            'catalogo_item_id' => ['required', 'integer', 'min:1'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
        $this->catalogAdmin->linkExisting($detalle, (int) $data['catalogo_item_id'], (int) ($data['orden'] ?? 0));

        return back()->with('success', 'Prestación vinculada al tipo de registro.');
    }

    public function updatePrestacion(Request $request, DetalleCatalogoItem $bridge): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:400'],
            'activo' => ['nullable', 'boolean'],
            'bridge_orden' => ['nullable', 'integer', 'min:0'],
            'bridge_activo' => ['nullable', 'boolean'],
            'especialidad_base' => ['nullable', 'string', 'max:200'],
            'familia' => ['nullable', 'string', 'max:40'],
            'modalidad' => ['nullable', 'string', 'max:120'],
            'es_agregado' => ['nullable', 'boolean'],
            'categoria' => ['nullable', 'string', 'max:40'],
            'requiere_pacientes' => ['nullable', 'boolean'],
            'requiere_prestaciones' => ['nullable', 'boolean'],
            'abreviatura' => ['nullable', 'string', 'max:20'],
            'requiere_lote' => ['nullable', 'boolean'],
            'es_indicador' => ['nullable', 'boolean'],
        ]);
        $data['activo'] = $request->boolean('activo', true);
        $data['bridge_activo'] = $request->boolean('bridge_activo', true);
        $this->catalogAdmin->updateItem($bridge, $data);

        return back()->with('success', 'Prestación actualizada.');
    }

    public function destroyPrestacion(Request $request, DetalleCatalogoItem $bridge): RedirectResponse
    {
        if ($request->boolean('delete_master')) {
            $this->catalogAdmin->deleteMasterItem($bridge);

            return back()->with('success', 'Prestación eliminada del catálogo maestro.');
        }

        $this->catalogAdmin->unlink($bridge);

        return back()->with('success', 'Prestación desvinculada del tipo de registro.');
    }

    public function searchLinkable(VariableDetalle $detalle, Request $request): JsonResponse
    {
        $term = $request->string('q')->toString();
        $items = $this->catalogAdmin->searchLinkable($detalle, $term !== '' ? $term : null);

        return response()->json([
            'catalogo_tipo' => $this->catalogAdmin->resolveCatalogType($detalle)->value,
            'data' => $items,
        ]);
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
            'catalogo_tipo' => ['nullable', 'string', Rule::in(array_column(CatalogType::cases(), 'value'))],
            'layout_captura' => ['nullable', 'string', Rule::in(['', 'matriz', 'cruce', 'tabla'])],
            'orden_items' => ['nullable', 'string', Rule::in([
                VariableDetalle::ORDEN_ITEMS_MANUAL,
                VariableDetalle::ORDEN_ITEMS_ALFABETICO,
            ])],
        ]);
    }

    private function validatePrestacionNombre(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:400'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
