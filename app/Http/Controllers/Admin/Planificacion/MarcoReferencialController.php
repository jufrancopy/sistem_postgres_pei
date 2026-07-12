<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use App\Models\Planificacion\MarcoReferencial;
use App\Models\Planificacion\MarcoTipo;
use App\Admin\Planificacion\Pei\PeiProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcoReferencialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Módulo de Gestión (CRUD) ──────────────────────────────────────────────

    // GET /pei/marcos
    public function index(Request $request)
    {
        $tipos  = MarcoTipo::where('activo', true)->orderBy('label')->get();
        $filtro = $request->get('tipo');

        $marcos = MarcoReferencial::when($filtro, fn($q) => $q->where('tipo', $filtro))
            ->orderBy('tipo')->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('admin.planificacion.marcos.index', compact('marcos', 'tipos', 'filtro'));
    }

    // POST /pei/marcos
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'tipo'        => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $marco = MarcoReferencial::firstOrCreate(
            ['nombre' => trim($request->nombre), 'tipo' => trim($request->tipo)],
            ['descripcion' => $request->descripcion, 'activo' => true]
        );

        return response()->json([
            'ok'    => true,
            'marco' => $marco,
            'nuevo' => $marco->wasRecentlyCreated,
        ]);
    }

    // PUT /pei/marcos/{id}
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'tipo'        => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:500',
            'activo'      => 'boolean',
        ]);

        $marco = MarcoReferencial::findOrFail($id);
        $marco->update([
            'nombre'      => trim($request->nombre),
            'tipo'        => trim($request->tipo),
            'descripcion' => $request->descripcion,
            'activo'      => $request->boolean('activo', true),
        ]);

        return response()->json(['ok' => true, 'marco' => $marco]);
    }

    // DELETE /pei/marcos/{id}
    public function destroy(int $id)
    {
        $marco = MarcoReferencial::findOrFail($id);

        // Verificar si está vinculado a algún perfil PEI
        $usos = \DB::table('planificacion.pei_profile_marcos')
            ->where('marco_id', $id)->count();

        if ($usos > 0) {
            return response()->json([
                'ok'      => false,
                'mensaje' => "No se puede eliminar: está vinculado a {$usos} objetivo(s) estratégico(s).",
            ], 422);
        }

        $marco->delete();
        return response()->json(['ok' => true]);
    }

    // PATCH /pei/marcos/{id}/toggle
    public function toggle(int $id)
    {
        $marco = MarcoReferencial::findOrFail($id);
        $marco->update(['activo' => !$marco->activo]);
        return response()->json(['ok' => true, 'activo' => $marco->activo]);
    }

    // GET /pei/marcos/tipos  — lista de tipos únicos (para select dinámico)
    public function tipos()
    {
        $tipos = MarcoTipo::where('activo', true)->orderBy('label')->get();
        return response()->json($tipos->map->toSelect2());
    }

    // POST /pei/marcos/tipos  — crear nuevo tipo con color
    public function storeTipo(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:50|regex:/^[a-z0-9_]+$/|unique:planificacion.marco_tipos,clave',
            'label' => 'required|string|max:100',
            'color' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
        ], [
            'clave.unique'  => 'Ya existe un tipo con esa clave.',
            'clave.regex'   => 'La clave solo puede contener letras minúsculas, números y guión bajo.',
            'color.regex'   => 'El color debe ser un valor hexadecimal válido (#rrggbb).',
        ]);

        $tipo = MarcoTipo::create([
            'clave'  => strtolower(trim($request->clave)),
            'label'  => trim($request->label),
            'color'  => $request->color,
            'activo' => true,
        ]);

        return response()->json(['ok' => true, 'tipo' => $tipo->toSelect2()]);
    }
    // Usado por Select2 AJAX para buscar marcos existentes
    public function buscar(Request $request)
    {
        $q    = $request->get('q', '');
        $tipo = $request->get('tipo');

        $query = MarcoReferencial::buscar($q);

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        return response()->json(
            $query->orderBy('tipo')->orderBy('nombre')
                  ->limit(30)
                  ->get()
                  ->map(fn($m) => $m->toSelect2())
        );
    }

    // POST /pei/marcos/crear
    // Crea el marco si no existe (firstOrCreate) y lo retorna en formato Select2
    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo'   => 'nullable|string|max:50',
        ]);

        $marco = MarcoReferencial::firstOrCreate(
            ['nombre' => trim($request->nombre), 'tipo' => $request->tipo ?? 'general'],
            ['descripcion' => $request->descripcion, 'activo' => true]
        );

        return response()->json($marco->toSelect2());
    }

    // POST /pei-profiles/{id}/marcos/sync
    // Sincroniza los marcos vinculados a un nodo del PEI
    public function sync(Request $request, string $profileId)
    {
        $request->validate([
            'marcos'   => 'nullable|array',
            'marcos.*' => 'integer',
        ]);

        $profile = PeiProfile::findOrFail($profileId);
        $profile->marcos()->sync($request->marcos ?? []);

        return response()->json([
            'ok'     => true,
            'marcos' => $profile->marcos()->get(['nombre','tipo'])->toArray(),
        ]);
    }

    // GET /pei-profiles/{id}/marcos
    // Retorna los marcos ya vinculados a un nodo (para pre-cargar el Select2 en edición)
    public function porPerfil(string $profileId)
    {
        $profile = PeiProfile::findOrFail($profileId);

        return response()->json(
            $profile->marcos->map(fn($m) => $m->toSelect2())
        );
    }
}
