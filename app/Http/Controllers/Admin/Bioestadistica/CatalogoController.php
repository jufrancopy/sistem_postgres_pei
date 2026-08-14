<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\CatalogItem;
use App\Models\Bioestadistica\Catalogo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    public function index(): View
    {
        return view('admin.bioestadistica.catalogos.index', [
            'catalogos' => Catalogo::withCount('items')->orderBy('nombre')->paginate(30),
        ]);
    }

    public function show(Catalogo $catalogo): View
    {
        $catalogo->load('items');

        return view('admin.bioestadistica.catalogos.show', compact('catalogo'));
    }

    public function store(Request $request): RedirectResponse
    {
        $catalogo = Catalogo::create($this->validateCatalogo($request));

        return redirect()->route('bioestadistica.catalogos.show', $catalogo)
            ->with('success', 'Catálogo creado.');
    }

    public function update(Request $request, Catalogo $catalogo): RedirectResponse
    {
        $catalogo->update($this->validateCatalogo($request, $catalogo));

        return back()->with('success', 'Catálogo actualizado.');
    }

    public function destroy(Catalogo $catalogo): RedirectResponse
    {
        $catalogo->delete();

        return redirect()->route('bioestadistica.catalogos.index')
            ->with('success', 'Catálogo eliminado.');
    }

    public function storeItem(Request $request, Catalogo $catalogo): RedirectResponse
    {
        $data = $request->validate([
            'codigo' => [
                'nullable', 'string', 'max:80',
                Rule::unique('bioestadistica.catalog_items', 'codigo')->where('catalogo_id', $catalogo->id),
            ],
            'label' => ['required', 'string', 'max:400'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'domain_code' => ['nullable', 'string', 'max:10'],
            'tipo_registro' => ['nullable', 'string', 'max:250'],
            'prestacion' => ['nullable', 'string', 'max:400'],
        ]);
        $data['activo'] = $request->boolean('activo', true);
        $catalogo->items()->create($data);

        return back()->with('success', 'Ítem agregado.');
    }

    public function destroyItem(CatalogItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('success', 'Ítem eliminado.');
    }

    private function validateCatalogo(Request $request, ?Catalogo $catalogo = null): array
    {
        $data = $request->validate([
            'codigo' => [
                'required', 'alpha_dash', 'max:80',
                Rule::unique('bioestadistica.catalogos', 'codigo')->ignore($catalogo?->id),
            ],
            'nombre' => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'activo' => ['nullable', 'boolean'],
        ]);
        $data['activo'] = $request->boolean('activo', true);

        return $data;
    }
}
