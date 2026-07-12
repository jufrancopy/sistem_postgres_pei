<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\MeeMarcoLegal;
use App\Models\Planificacion\MeeOfertaServicio;
use App\Admin\Globales\Organigrama;
use Illuminate\Http\Request;

class MeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Vista principal ───────────────────────────────────────────────────────
    public function modulo(string $profileId)
    {
        $profile  = PeiProfile::findOrFail($profileId);
        $marcos   = MeeMarcoLegal::with('responsables')
            ->where('pei_profile_id', $profileId)
            ->orderBy('orden')->orderBy('id')
            ->get();
        $ofertas  = MeeOfertaServicio::where('pei_profile_id', $profileId)
            ->orderBy('orden')->orderBy('id')
            ->get();

        // Raíz del organigrama para el selector
        $orgRaizId = $this->resolverRaizOrg($profile);

        $marcosJson = $marcos->map(function ($m) {
            return [
                'id'           => $m->id,
                'marco_legal'  => $m->marco_legal,
                'competencias' => $m->competencias,
                'responsables' => $m->responsables->map(function ($r) {
                    return ['id' => $r->id, 'text' => $r->dependency];
                })->values(),
            ];
        });

        return view('admin.planificacion.mee.modulo', compact(
            'profile', 'marcos', 'ofertas', 'orgRaizId', 'marcosJson'
        ));
    }

    // ── Marco Legal CRUD ──────────────────────────────────────────────────────
    public function indexMarcos(string $profileId)
    {
        $marcos = MeeMarcoLegal::with('responsables')
            ->where('pei_profile_id', $profileId)
            ->orderBy('orden')->orderBy('id')
            ->get();

        return response()->json($marcos->map(fn($m) => [
            'id'            => $m->id,
            'marco_legal'   => $m->marco_legal,
            'competencias'  => $m->competencias,
            'orden'         => $m->orden,
            'responsables'  => $m->responsables->map(fn($r) => [
                'id'   => $r->id,
                'text' => $r->dependency,
            ]),
        ]));
    }

    public function storeMarco(Request $request, string $profileId)
    {
        PeiProfile::findOrFail($profileId);
        $data = $request->validate([
            'marco_legal'  => 'required|string|max:500',
            'competencias' => 'nullable|string',
            'orden'        => 'nullable|integer',
            'responsables' => 'nullable|array',
            'responsables.*' => 'integer|exists:organigramas,id',
        ]);

        $marco = MeeMarcoLegal::create([
            'pei_profile_id' => $profileId,
            'marco_legal'    => $data['marco_legal'],
            'competencias'   => $data['competencias'] ?? null,
            'orden'          => $data['orden'] ?? 0,
        ]);

        if (!empty($data['responsables'])) {
            $marco->responsables()->sync($data['responsables']);
        }

        return response()->json(['ok' => true, 'id' => $marco->id], 201);
    }

    public function updateMarco(Request $request, string $profileId, int $id)
    {
        $marco = MeeMarcoLegal::where('pei_profile_id', $profileId)->findOrFail($id);
        $data  = $request->validate([
            'marco_legal'  => 'required|string|max:500',
            'competencias' => 'nullable|string',
            'orden'        => 'nullable|integer',
            'responsables' => 'nullable|array',
            'responsables.*' => 'integer|exists:organigramas,id',
        ]);

        $marco->update([
            'marco_legal'  => $data['marco_legal'],
            'competencias' => $data['competencias'] ?? null,
            'orden'        => $data['orden'] ?? $marco->orden,
        ]);

        $marco->responsables()->sync($data['responsables'] ?? []);

        return response()->json(['ok' => true]);
    }

    public function destroyMarco(string $profileId, int $id)
    {
        MeeMarcoLegal::where('pei_profile_id', $profileId)->findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    // ── Oferta de Servicios CRUD ──────────────────────────────────────────────
    public function indexOfertas(string $profileId)
    {
        $ofertas = MeeOfertaServicio::where('pei_profile_id', $profileId)
            ->orderBy('orden')->orderBy('id')
            ->get();

        return response()->json($ofertas);
    }

    public function storeOferta(Request $request, string $profileId)
    {
        PeiProfile::findOrFail($profileId);
        $data = $request->validate([
            'accion'        => 'required|string|max:300',
            'descripcion'   => 'nullable|string',
            'beneficiarios' => 'nullable|string',
            'orden'         => 'nullable|integer',
        ]);

        $oferta = MeeOfertaServicio::create(array_merge($data, [
            'pei_profile_id' => $profileId,
            'orden'          => $data['orden'] ?? 0,
        ]));

        return response()->json(['ok' => true, 'id' => $oferta->id], 201);
    }

    public function updateOferta(Request $request, string $profileId, int $id)
    {
        $oferta = MeeOfertaServicio::where('pei_profile_id', $profileId)->findOrFail($id);
        $data   = $request->validate([
            'accion'        => 'required|string|max:300',
            'descripcion'   => 'nullable|string',
            'beneficiarios' => 'nullable|string',
            'orden'         => 'nullable|integer',
        ]);

        $oferta->update($data);
        return response()->json(['ok' => true]);
    }

    public function destroyOferta(string $profileId, int $id)
    {
        MeeOfertaServicio::where('pei_profile_id', $profileId)->findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function resolverRaizOrg(PeiProfile $profile): ?int
    {
        if ($profile->dependency_id) {
            $nodo = Organigrama::find($profile->dependency_id);
            if ($nodo) {
                return $nodo->isRoot()
                    ? $nodo->id
                    : (Organigrama::whereAncestorOf($nodo)->whereIsRoot()->first()?->id ?? $nodo->id);
            }
        }
        return Organigrama::whereIsRoot()->value('id');
    }
}
