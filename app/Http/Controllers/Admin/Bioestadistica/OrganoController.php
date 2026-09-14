<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\EstablecimientoOrgano;
use App\Models\Bioestadistica\Organo;
use App\Models\Bioestadistica\OrganoTipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrganoController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim($request->string('q')->toString());
        $parentId = $request->integer('parent_id') ?: null;

        $tipos = OrganoTipo::query()->where('activo', true)->orderBy('orden')->get();

        if ($q !== '') {
            $resultados = Organo::query()
                ->with(['tipo', 'parent.tipo'])
                ->where('nombre', 'ILIKE', '%'.$q.'%')
                ->orderBy('nombre')
                ->limit(80)
                ->get();

            return view('admin.bioestadistica.organos.index', [
                'modo' => 'busqueda',
                'q' => $q,
                'resultados' => $resultados,
                'actual' => null,
                'ancestros' => collect(),
                'hijos' => collect(),
                'tipos' => $tipos,
                'stats' => $this->stats(),
            ]);
        }

        $actual = $parentId
            ? Organo::query()->with('tipo')->findOrFail($parentId)
            : Organo::query()->with('tipo')->raices()->orderBy('orden')->orderBy('nombre')->first();

        $ancestros = $actual ? $this->ancestros($actual) : collect();
        $hijos = $actual
            ? $actual->children()->with(['tipo', 'children' => fn ($q) => $q->select('id', 'parent_id')])->get()
            : Organo::query()->with('tipo')->raices()->orderBy('orden')->orderBy('nombre')->get();

        return view('admin.bioestadistica.organos.index', [
            'modo' => 'arbol',
            'q' => '',
            'resultados' => collect(),
            'actual' => $actual,
            'ancestros' => $ancestros,
            'hijos' => $hijos,
            'tipos' => $tipos,
            'stats' => $this->stats(),
        ]);
    }

    public function show(Organo $organo): View
    {
        $organo->load(['tipo', 'parent.tipo', 'children.tipo', 'establecimientoLinks.establecimiento']);

        return view('admin.bioestadistica.organos.show', [
            'organo' => $organo,
            'ancestros' => $this->ancestros($organo),
            'tipos' => OrganoTipo::query()->where('activo', true)->orderBy('orden')->get(),
            'establecimientos' => Establecimiento::query()->orderBy('nombre')->get(['id', 'codigo', 'nombre']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, creating: true);

        Organo::query()->create($data);

        return redirect()
            ->route('bioestadistica.organos.index', ['parent_id' => $data['parent_id']])
            ->with('success', 'Órgano creado.');
    }

    public function update(Request $request, Organo $organo): RedirectResponse
    {
        $data = $this->validated($request, creating: false, organo: $organo);
        $organo->update($data);

        return redirect()
            ->route('bioestadistica.organos.show', $organo)
            ->with('success', 'Órgano actualizado.');
    }

    public function destroy(Organo $organo): RedirectResponse
    {
        if ($organo->children()->exists()) {
            return back()->withErrors(['organo' => 'No se puede eliminar: tiene órganos hijos. Mueva o elimine los hijos primero.']);
        }
        if ($organo->establecimientoLinks()->exists()) {
            return back()->withErrors(['organo' => 'No se puede eliminar: tiene establecimientos enlazados.']);
        }

        $parentId = $organo->parent_id;
        $organo->delete();

        return redirect()
            ->route('bioestadistica.organos.index', array_filter(['parent_id' => $parentId]))
            ->with('success', 'Órgano eliminado.');
    }

    public function linkEstablecimiento(Request $request, Organo $organo): RedirectResponse
    {
        $data = $request->validate([
            'establecimiento_id' => ['required', 'integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
            'es_principal' => ['nullable', 'boolean'],
            'vigente_desde' => ['nullable', 'date'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ]);

        $link = EstablecimientoOrgano::withTrashed()->firstOrNew([
            'establecimiento_id' => $data['establecimiento_id'],
            'organo_id' => $organo->id,
        ]);
        if ($link->exists && $link->trashed()) {
            $link->restore();
        }
        $link->fill([
            'es_principal' => $request->boolean('es_principal', true),
            'vigente_desde' => $data['vigente_desde'] ?? null,
            'vigente_hasta' => null,
            'notas' => $data['notas'] ?? null,
        ])->save();

        return back()->with('success', 'Establecimiento enlazado al órgano.');
    }

    public function unlinkEstablecimiento(Organo $organo, EstablecimientoOrgano $link): RedirectResponse
    {
        if ((int) $link->organo_id !== (int) $organo->id) {
            abort(404);
        }
        $link->delete();

        return back()->with('success', 'Enlace eliminado.');
    }

    /**
     * @return array{tipos: int, organos: int, enlaces: int}
     */
    private function stats(): array
    {
        return [
            'tipos' => OrganoTipo::query()->count(),
            'organos' => Organo::query()->count(),
            'enlaces' => EstablecimientoOrgano::query()->count(),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection<int, Organo>
     */
    private function ancestros(Organo $organo)
    {
        $path = collect();
        $current = $organo->relationLoaded('parent') ? $organo->parent : $organo->parent()->with('tipo')->first();
        $guard = 0;
        while ($current && $guard < 30) {
            $path->prepend($current);
            $current = $current->parent()->with('tipo')->first();
            $guard++;
        }

        return $path;
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, bool $creating, ?Organo $organo = null): array
    {
        $parentId = $request->input('parent_id');

        return $request->validate([
            'tipo_id' => ['required', 'integer', Rule::exists(OrganoTipo::class, 'id')->withoutTrashed()],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists(Organo::class, 'id')->withoutTrashed(),
                Rule::when($organo !== null, fn () => Rule::notIn([(string) $organo->id])),
            ],
            'nombre' => [
                'required',
                'string',
                'max:300',
                Rule::unique('bioestadistica.organos', 'nombre')
                    ->where(fn ($q) => $parentId ? $q->where('parent_id', $parentId) : $q->whereNull('parent_id'))
                    ->ignore($organo?->id)
                    ->whereNull('deleted_at'),
            ],
            'codigo' => ['nullable', 'string', 'max:40'],
            'orden' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'es_jerarquico' => ['nullable', 'boolean'],
            'activo' => ['nullable', 'boolean'],
            'notas' => ['nullable', 'string', 'max:2000'],
        ]) + [
            'orden' => (int) $request->input('orden', 0),
            'es_jerarquico' => $request->boolean('es_jerarquico', true),
            'activo' => $creating ? $request->boolean('activo', true) : $request->boolean('activo'),
        ];
    }
}
