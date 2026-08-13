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
    
    public function dashboard(Request $request)
    {
        // Listado de Planes PEI maestros (Solo Raíces / parent_id NULL y Activos)
        $peiPerfiles = \App\Admin\Planificacion\Pei\PeiProfile::whereIsRoot()
            ->whereNull('parent_id')
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->orderByDesc('year_start')
            ->get();

        $peiProfileId = $request->get('pei_id') ?? session('selected_pei_id');
        $selectedPei  = null;

        if ($peiProfileId) {
            $selectedPei = \App\Admin\Planificacion\Pei\PeiProfile::whereNull('parent_id')->where('is_active', true)->find($peiProfileId);
        }
        if (!$selectedPei && $peiPerfiles->count() > 0) {
            $selectedPei  = $peiPerfiles->first();
        }

        if ($selectedPei) {
            $peiProfileId = $selectedPei->id;
            session(['selected_pei_id' => $selectedPei->id]);
        }

        // ── Organigramas & Selector de Estructura (Por defecto Organigrama del PEI) ──
        $organigramasRaizList = Organigrama::whereIsRoot()->orderBy('dependency')->get();
        $organigramaId        = $request->get('organigrama_id');
        $organigramaRaiz      = null;

        if ($organigramaId) {
            $organigramaRaiz = Organigrama::find($organigramaId);
        } elseif ($selectedPei && $selectedPei->dependency_id) {
            $organigramaRaiz = Organigrama::find($selectedPei->dependency_id);
        }

        if (!$organigramaRaiz && $organigramasRaizList->count() > 0) {
            $organigramaRaiz = $organigramasRaizList->first();
        }

        $suborganigramasPermitidos = collect();
        $organigramaDatasource     = null;
        if ($organigramaRaiz) {
            $organigramaId             = $organigramaRaiz->id;
            $organigramasPermitidos    = Organigrama::descendantsAndSelf($organigramaRaiz->id);
            $suborganigramasPermitidos = $organigramasPermitidos->where('id', '!=', $organigramaRaiz->id);
            $organigramaDatasource     = $this->buildOrgChartTreeData($organigramaRaiz);
        }

        // ── Usuarios y Roles ──────────────────────────────────────────────────
        $totalUsuarios      = User::count();
        $totalAdmins        = User::role('Administrador')->count();
        $totalParticipantes = User::role('Participantes')->count();

        // ── Organigramas ──────────────────────────────────────────────────────
        $totalOrganigramas     = Organigrama::whereIsRoot()->count();
        $totalDependencias     = Organigrama::count();
        $totalEstablecimientos = Organigrama::whereNotNull('tipo_establecimiento')->count();
        $establecimientos_aop  = Organigrama::where('tiene_aop', true)->count();
        $establecimientos_por_tenencia = Organigrama::whereNotNull('tipo_establecimiento')
            ->selectRaw('tenencia, COUNT(*) as total')
            ->groupBy('tenencia')->pluck('total', 'tenencia');

        // ── PEI (Filtrado por Plan Seleccionado o Global) ──────────────────────
        $totalPeis = $peiPerfiles->count();
        if ($selectedPei) {
            $descendantIds  = $selectedPei->descendants()->pluck('id')->push($selectedPei->id);
            $totalObjetivos = \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $descendantIds)->where('level','axi')->count();
            $totalMetas     = \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $descendantIds)->where('level','goal')->count();
            $totalAcciones  = \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $descendantIds)->where('level','action')->count();
        } else {
            $totalObjetivos = \App\Admin\Planificacion\Pei\PeiProfile::where('level','axi')->count();
            $totalMetas     = \App\Admin\Planificacion\Pei\PeiProfile::where('level','goal')->count();
            $totalAcciones  = \App\Admin\Planificacion\Pei\PeiProfile::where('level','action')->count();
        }

        // ── SIESS ─────────────────────────────────────────────────────────────
        $totalExtractos      = \App\Models\Estadistica\SiessExtracto::count();
        $extractosAprobados  = \App\Models\Estadistica\SiessExtracto::aprobados()->count();
        $extractosPendientes = \App\Models\Estadistica\SiessExtracto::pendientes()->count();

        // ── Proyectos Institucionales ─────────────────────────────────────────
        $totalProyectos     = \App\Models\Proyectos\ProyectoInstitucional::count();
        $proyectosActivos   = \App\Models\Proyectos\ProyectoInstitucional::activos()->count();
        $proyectosEjecucion = \App\Models\Proyectos\ProyectoInstitucional::enEjecucion()->count();

        // ── Roles, Permisos, Grupos ───────────────────────────────────────────
        $totalRoles    = Role::count();
        $totalPermisos = Permission::count();
        $totalGrupos   = \App\Admin\Globales\Group::count();

        // ── Configuración del Dashboard / Sitio Público ──────────────────────
        $config = HomeConfiguration::firstOrNew([]);
        if (!$config->exists) $config->save();

        $fodaPerfiles = \App\Admin\Planificacion\Foda\FodaPerfil::where('type', 'consolidado')->orderBy('name')->get(['id','name']);

        // ── Grupos de Trabajo (Subgrupos del Evento Raíz del PEI) ─────────────
        $eventosRaizList = \App\Admin\Globales\Group::whereNull('parent_id')->orderBy('name')->get();
        $eventoIdReq     = $request->get('evento_id');
        $selectedGroup   = null;

        if ($eventoIdReq) {
            $selectedGroup = \App\Admin\Globales\Group::find($eventoIdReq);
        } elseif ($selectedPei && $selectedPei->group_id) {
            $selectedGroup = \App\Admin\Globales\Group::find($selectedPei->group_id);
        }

        if (!$selectedGroup && $eventosRaizList->count() > 0) {
            $selectedGroup = $eventosRaizList->first();
        }

        if ($selectedGroup) {
            $gruposList = \App\Admin\Globales\Group::where('parent_id', $selectedGroup->id)
                ->with('members')
                ->withCount('members')
                ->orderBy('id', 'desc')
                ->get();

            // Calcular Top 3 miembros por Puntos de Gamificación para cada grupo
            $allMemberIds = $gruposList->pluck('members')->flatten()->pluck('id')->unique();
            $pointsPerUser = \App\Models\Gamification\GamificationPoint::whereIn('user_id', $allMemberIds)
                ->selectRaw('user_id, SUM(points) as total_points')
                ->groupBy('user_id')
                ->pluck('total_points', 'user_id');

            $gruposList->each(function($g) use ($pointsPerUser) {
                $sortedMembers = $g->members->map(function($m) use ($pointsPerUser) {
                    $m->puntos_gamificacion = (int) ($pointsPerUser[$m->id] ?? 0);
                    return $m;
                })->sortByDesc('puntos_gamificacion')->values();

                $g->top_miembros = $sortedMembers->take(3);
            });
        } else {
            $gruposList = collect();
        }

        // ── Usuarios (Integrantes del PEI vs Todos los Usuarios) ──────────────
        $filterAllUsers = (int) $request->get('all_users', 0);

        if (!$filterAllUsers && $selectedGroup) {
            $subgroupIds   = \App\Admin\Globales\Group::descendantsOf($selectedGroup->id)->pluck('id')->push($selectedGroup->id);
            $pivotUserIds  = \DB::table('groups_has_members')->whereIn('group_id', $subgroupIds)->pluck('user_id');
            $directUserIds = User::whereIn('group_id', $subgroupIds)->pluck('id');
            $allPeiUserIds = $pivotUserIds->merge($directUserIds)->unique();

            $usuariosList = User::with(['roles', 'group'])
                ->whereIn('id', $allPeiUserIds)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $usuariosList = User::with(['roles', 'group'])
                ->orderBy('id', 'desc')
                ->get();
        }

        // Data de Listados para Pestañas y Modales
        $rolesList            = Role::withCount('permissions', 'users')->orderBy('id', 'desc')->get();
        $rolesWithPermissions = Role::with('permissions')->orderBy('id', 'desc')->get();
        $permissionsList      = Permission::orderBy('id', 'desc')->get();
        $allRoles             = Role::orderBy('name')->get();
        $allPermissions       = Permission::orderBy('name')->get();
        $allGroups            = \App\Admin\Globales\Group::orderBy('name')->get(['id', 'name']);
        $organigramaRaiz      = Organigrama::whereIsRoot()->first();

        return view('admin.globales.dashboard', get_defined_vars());
    }

    private function buildOrgChartTreeData($nodo, $nivel = 0): array
    {
        return [
            'name'      => $nodo->dependency,
            'title'     => $nodo->manager ?? 'Sin encargado',
            'className' => 'nivel-' . min($nivel, 5),
            'id'        => $nodo->id,
            'phone'     => $nodo->phone ?? '',
            'email'     => $nodo->email ?? '',
            'tipo'      => $nodo->tipo_establecimiento ?? '',
            'nivel'     => $nodo->nivel_complejidad ?? '',
            'tenencia'  => $nodo->tenencia ?? '',
            'aop'       => $nodo->tiene_aop ? true : false,
            'region'    => $nodo->region ?? '',
            'children'  => $nodo->children ? $nodo->children->map(fn($c) => $this->buildOrgChartTreeData($c, $nivel + 1))->values()->toArray() : [],
        ];
    }
}
