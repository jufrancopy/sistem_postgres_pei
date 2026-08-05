<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Admin\Globales\Organigrama;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

Use App\Models\User;
use App\Models\HomeConfiguration;


class GlobalesController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'role:Administrador']);
    }
    
    public function dashboard()
    {
        // ── Usuarios y Roles ──────────────────────────────────────────────────
        $totalUsuarios    = User::count();
        $totalAdmins      = User::role('Administrador')->count();
        $totalParticipantes = User::role('Participantes')->count();

        // ── Organigramas ──────────────────────────────────────────────────────
        $totalOrganigramas   = Organigrama::whereIsRoot()->count();
        $totalDependencias   = Organigrama::count();
        $totalEstablecimientos = Organigrama::whereNotNull('tipo_establecimiento')->count();
        $establecimientos_aop  = Organigrama::where('tiene_aop', true)->count();
        $establecimientos_por_tenencia = Organigrama::whereNotNull('tipo_establecimiento')
            ->selectRaw('tenencia, COUNT(*) as total')
            ->groupBy('tenencia')->pluck('total', 'tenencia');

        // ── PEI ───────────────────────────────────────────────────────────────
        $totalPeis      = \App\Admin\Planificacion\Pei\PeiProfile::whereIsRoot()->count();
        $totalObjetivos = \App\Admin\Planificacion\Pei\PeiProfile::where('level','axi')->count();
        $totalAcciones  = \App\Admin\Planificacion\Pei\PeiProfile::where('level','action')->count();

        // ── SIESS ─────────────────────────────────────────────────────────────
        $totalExtractos   = \App\Models\Estadistica\SiessExtracto::count();
        $extractosAprobados = \App\Models\Estadistica\SiessExtracto::aprobados()->count();
        $extractosPendientes = \App\Models\Estadistica\SiessExtracto::pendientes()->count();

        // ── Proyectos Institucionales ─────────────────────────────────────────
        $totalProyectos    = \App\Models\Proyectos\ProyectoInstitucional::count();
        $proyectosActivos  = \App\Models\Proyectos\ProyectoInstitucional::activos()->count();
        $proyectosEjecucion = \App\Models\Proyectos\ProyectoInstitucional::enEjecucion()->count();

        // ── Roles y Permisos ──────────────────────────────────────────────────
        $totalRoles    = \Spatie\Permission\Models\Role::count();
        $totalPermisos = \Spatie\Permission\Models\Permission::count();

        // ── Configuración del Dashboard ───────────────────────────────────────
        $config = HomeConfiguration::firstOrNew([]);
        if (!$config->exists) $config->save();

        // Listas para selectores
        $fodaPerfiles = \App\Admin\Planificacion\Foda\FodaPerfil::where('type', 'consolidado')->orderBy('name')->get(['id','name']);
        $peiPerfiles  = \App\Admin\Planificacion\Pei\PeiProfile::whereIsRoot()->where('level','master')->whereNull('deleted_at')->orderByDesc('year_start')->get(['id','name','year_start','year_end']);

        return view('admin.globales.dashboard', get_defined_vars());
    }
}
