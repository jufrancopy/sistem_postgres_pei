<?php

namespace App\Http\Controllers\Admin\Planificacion\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Admin\Globales\Group;
use App\Admin\Globales\Organigrama;
use App\Models\User;
use App\Admin\Globales\Activity;
use App\Admin\Planificacion\Pei\PeiProfile;
use Spatie\Permission\Models\Role;

class CoordinadorPlanificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Coordinador de Planificación|Analista de Planificación');
    }

    private function requirePlanificacionScope($permission = 'planificacion.scope')
    {
        abort_unless(Gate::allows($permission), 403, 'No tenés acceso a este alcance de planificación.');
    }

    /**
     * Dashboard del Coordinador de Planificación
     */
    public function index(Request $request)
    {
        $this->requirePlanificacionScope();

        $user = auth()->user();

        $organigrama = $user->organigramaDelPei;
        $grupoPadre = $user->grupoPadre;
        $gruposHijos = $grupoPadre ? $grupoPadre->children : collect();

        $usuarios = collect();
        if ($gruposHijos->isNotEmpty()) {
            $usuarios = User::whereIn('group_id', $gruposHijos->pluck('id'))->get();
        }

        $actividades = collect();
        if ($organigrama) {
            $actividades = Activity::whereHas('peiProfile', function($q) use ($organigrama) {
                $q->where('dependency_id', $organigrama->id);
            })->get();
        }

        return view('admin.planificacion.coordinador.index', compact(
            'organigrama', 'grupoPadre', 'gruposHijos', 'usuarios', 'actividades'
        ));
    }

    /**
     * Gestionar Grupos y Usuarios
     */
    public function gestionarGrupos(Request $request)
    {
        $this->requirePlanificacionScope('planificacion.manage-groups');

        $user = auth()->user();
        $grupoPadre = $user->grupoPadre;

        if (!$grupoPadre) {
            return redirect()->back()->with('error', 'No se encontró el grupo padre.');
        }

        $gruposHijos = $grupoPadre->children()->with('members')->get();

        $usuarios = collect();
        foreach ($gruposHijos as $grupo) {
            $usuarios = $usuarios->merge($grupo->members);
        }

        return view('admin.planificacion.coordinador.gestionar-grupos', compact(
            'grupoPadre', 'gruposHijos', 'usuarios'
        ));
    }

    /**
     * Crear Nuevas Actividades
     */
    public function crearActividad(Request $request)
    {
        $this->requirePlanificacionScope('planificacion.create-activity');

        $user = auth()->user();
        $organigrama = $user->organigramaDelPei;

        if (!$organigrama) {
            return redirect()->back()->with('error', 'No se encontró el organigrama asociado.');
        }

        $pei = PeiProfile::where('dependency_id', $organigrama->id)->where('level', 'master')->first();

        return view('admin.planificacion.coordinador.crear-actividad', compact('pei'));
    }

    /**
     * Ver todos los grupos y Usuarios del grupo Padre
     */
    public function verGruposYUsuarios(Request $request)
    {
        $this->requirePlanificacionScope('planificacion.view-tree');

        $user = auth()->user();
        $grupoPadre = $user->grupoPadre;

        if (!$grupoPadre) {
            return redirect()->back()->with('error', 'No se encontró el grupo padre.');
        }

        $gruposHijos = $grupoPadre->children()->with('members')->get();

        $usuarios = collect();
        foreach ($gruposHijos as $grupo) {
            $usuarios = $usuarios->merge($grupo->members);
        }

        return view('admin.planificacion.coordinador.ver-grupos-usuarios', compact(
            'grupoPadre', 'gruposHijos', 'usuarios'
        ));
    }

    /**
     * Ver Organigrama
     */
    public function verOrganigrama(Request $request)
    {
        $this->requirePlanificacionScope('planificacion.manage-organigrama');

        $user = auth()->user();
        $organigrama = $user->organigramaDelPei;

        if (!$organigrama) {
            return redirect()->back()->with('error', 'No se encontró el organigrama asociado.');
        }

        $estructura = Organigrama::descendantsAndSelf($organigrama->id)->toTree();

        return view('admin.planificacion.coordinador.ver-organigrama', compact('estructura'));
    }
}
