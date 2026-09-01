<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\PeiActor;
use App\Admin\Globales\Organigrama;
use App\Models\User;
use App\Models\InstitucionParaguay;

class PeiActorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $peiId)
    {
        $profile = PeiProfile::findOrFail($peiId);
        $actores = PeiActor::with(['organigrama', 'user'])
            ->where('pei_profile_id', $peiId)
            ->orderBy('orden')
            ->get();

        if ($request->ajax()) {
            return response()->json(['actores' => $actores->map(fn($a) => [
                'id'           => $a->id,
                'tipo'         => $a->tipo,
                'dependencia'  => $a->dependencia_label,
                'persona'      => $a->persona_label,
                'email'        => $a->tipo === 'interno' ? $a->user?->email : $a->email_externo,
                'aportes'      => $a->aportes,
                'orden'        => $a->orden,
            ])]);
        }

        $organigramas = Organigrama::orderBy('dependency')->get(['id', 'dependency']);
        $users        = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.planificacion.peis.actores.index', compact('profile', 'actores', 'organigramas', 'users'));
    }

    public function store(Request $request, $peiId)
    {
        PeiProfile::findOrFail($peiId);

        $data = ['pei_profile_id' => $peiId, 'tipo' => $request->tipo, 'aportes' => $request->aportes, 'orden' => $request->orden ?? 0];

        if ($request->tipo === 'interno') {
            $data += [
                'organigrama_id'      => $request->organigrama_id ?: null,
                'user_id'             => $request->user_id ?: null,
                'dependencia_externa' => $request->dependencia_externa,
                'persona_referente'   => $request->persona_referente,
                'email_externo'       => $request->email_externo,
            ];
        } else {
            $data += [
                'institucion_id'      => $request->institucion_id ?: null,
                'dependencia_externa' => $request->dependencia_externa,
                'persona_referente'   => $request->persona_referente,
                'email_externo'       => $request->email_externo,
            ];
        }

        $actor = $request->actor_id
            ? tap(PeiActor::findOrFail($request->actor_id))->update($data)
            : PeiActor::create($data);

        return response()->json(['success' => true, 'actor' => $actor->load(['organigrama', 'user'])]);
    }

    public function buscarInstituciones(Request $request)
    {
        $q = $request->get('q', '');
        return InstitucionParaguay::where('activo', true)
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'ilike', "%{$q}%")
                      ->orWhere('sigla', 'ilike', "%{$q}%");
            })
            ->orderBy('nombre')
            ->limit(20)
            ->get(['id', 'nombre', 'sigla', 'tipo'])
            ->map(fn($i) => [
                'id'   => $i->id,
                'text' => $i->sigla ? "{$i->nombre} ({$i->sigla})" : $i->nombre,
                'tipo' => $i->tipo,
            ]);
    }

    public function destroy($peiId, $actorId)
    {
        PeiActor::where('pei_profile_id', $peiId)->findOrFail($actorId)->delete();
        return response()->json(['success' => true]);
    }
}
