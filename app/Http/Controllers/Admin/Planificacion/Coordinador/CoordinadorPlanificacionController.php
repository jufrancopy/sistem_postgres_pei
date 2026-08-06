<?php

namespace App\Http\Controllers\Admin\Planificacion\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Admin\Globales\Group;
use App\Admin\Globales\Organigrama;
use App\Models\User;
use App\Admin\Globales\Activity;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Proyectos\ProyectoInstitucional;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class CoordinadorPlanificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Coordinador de Planificación|Analista de Planificación|Administrador');
    }

    private function requirePlanificacionScope($permission = 'planificacion.scope')
    {
        abort_unless(Gate::allows($permission), 403, 'No tenés acceso a este alcance de planificación.');
    }

    /**
     * Resuelve de forma centralizada y jerárquica el contexto/alcance del coordinador autenticado.
     */
    private function getScopeContext()
    {
        $user = auth()->user();

        // 1. Grupo Raíz y descendientes
        $grupoPadre = $user->grupoPadre;
        if (!$grupoPadre && $user->group) {
            $grupoPadre = $user->group->isRoot()
                ? $user->group
                : ($user->group->ancestors()->whereNull('parent_id')->first() ?? $user->group->getRoot() ?? $user->group);
        }

        $gruposPermitidos = collect();
        $subgruposPermitidos = collect();
        $grupoIds = [];
        $subgrupoIds = [];

        if ($grupoPadre) {
            $gruposPermitidos = Group::descendantsAndSelf($grupoPadre->id);
            $grupoIds = $gruposPermitidos->pluck('id')->toArray();
            $subgruposPermitidos = $gruposPermitidos->where('id', '!=', $grupoPadre->id);
            $subgrupoIds = $subgruposPermitidos->pluck('id')->toArray();
        } elseif ($user->hasRole('Administrador')) {
            $gruposPermitidos = Group::all();
            $grupoIds = $gruposPermitidos->pluck('id')->toArray();
            $grupoPadre = Group::whereNull('parent_id')->first() ?? Group::first();
            if ($grupoPadre) {
                $subgruposPermitidos = $gruposPermitidos->where('id', '!=', $grupoPadre->id);
                $subgrupoIds = $subgruposPermitidos->pluck('id')->toArray();
            }
        }

        // 2. PEI del Ámbito
        $pei = $user->peiActual();
        if (!$pei && !empty($grupoIds)) {
            $pei = PeiProfile::where('level', 'master')->whereIn('group_id', $grupoIds)->first();
        }
        if (!$pei && $user->hasRole('Administrador')) {
            $pei = PeiProfile::where('level', 'master')->first();
        }

        // 3. Organigrama de la Rama
        $organigramaRaiz = null;
        if ($pei && $pei->dependency_id) {
            $organigramaRaiz = Organigrama::find($pei->dependency_id);
        }
        if (!$organigramaRaiz) {
            $organigramaRaiz = $user->organigramaDelPei;
        }
        if (!$organigramaRaiz && $user->hasRole('Administrador')) {
            $organigramaRaiz = Organigrama::whereNull('parent_id')->first() ?? Organigrama::first();
        }

        $organigramasPermitidos = collect();
        $suborganigramasPermitidos = collect();
        $organigramaIds = [];
        $suborganigramaIds = [];
        if ($organigramaRaiz) {
            $organigramasPermitidos = Organigrama::descendantsAndSelf($organigramaRaiz->id);
            $organigramaIds = $organigramasPermitidos->pluck('id')->toArray();
            $suborganigramasPermitidos = $organigramasPermitidos->where('id', '!=', $organigramaRaiz->id);
            $suborganigramaIds = $suborganigramasPermitidos->pluck('id')->toArray();
        }

        // 4. Usuarios de los Grupos Hijos (Excluyendo los asignados solo al grupo padre)
        $usuariosQuery = User::query();
        if (!empty($subgrupoIds)) {
            $usuariosQuery->where(function($q) use ($subgrupoIds) {
                $q->whereIn('group_id', $subgrupoIds)
                  ->orWhereHas('groups', function($g) use ($subgrupoIds) {
                      $g->whereIn('groups.id', $subgrupoIds);
                  });
            });
        } elseif (!empty($grupoIds)) {
            $usuariosQuery->whereIn('group_id', $grupoIds);
        }
        $usuarios = $usuariosQuery->with(['roles', 'group'])->get();

        // 5. Roles asignables
        $rolesQuery = Role::query();
        if (!$user->hasRole('Administrador')) {
            $rolesQuery->whereNotIn('name', ['Administrador']);
        }
        $roles = $rolesQuery->get();

        // 6. Planes Institucionales (PEI) vinculados al grupo padre / dependencias
        $planesQuery = PeiProfile::where('level', 'master')->with(['dependency', 'group', 'user', 'fodaPerfil']);
        if (!empty($grupoIds) || !empty($organigramaIds)) {
            $planesQuery->where(function($q) use ($grupoIds, $organigramaIds) {
                if (!empty($grupoIds)) {
                    $q->whereIn('group_id', $grupoIds);
                }
                if (!empty($organigramaIds)) {
                    $q->orWhereIn('dependency_id', $organigramaIds);
                }
            });
        }
        $planes = $planesQuery->latest()->get();
        if ($planes->isEmpty() && $user->hasRole('Administrador')) {
            $planes = PeiProfile::where('level', 'master')->with(['dependency', 'group', 'user', 'fodaPerfil'])->latest()->get();
        }

        // 7. Todos los usuarios del sistema disponibles para asignar en grupos
        $todosLosUsuarios = User::orderBy('name')->get(['id', 'name', 'email']);

        return [
            'user'                      => $user,
            'pei'                       => $pei,
            'grupoPadre'                => $grupoPadre,
            'subgruposPermitidos'       => $subgruposPermitidos,
            'subgrupoIds'               => $subgrupoIds,
            'gruposPermitidos'          => $gruposPermitidos,
            'grupoIds'                  => $grupoIds,
            'organigramaRaiz'           => $organigramaRaiz,
            'suborganigramasPermitidos' => $suborganigramasPermitidos,
            'suborganigramaIds'         => $suborganigramaIds,
            'organigramasPermitidos'    => $organigramasPermitidos,
            'organigramaIds'            => $organigramaIds,
            'usuarios'                  => $usuarios,
            'roles'                     => $roles,
            'planes'                    => $planes,
            'todosLosUsuarios'          => $todosLosUsuarios,
        ];
    }

    /**
     * Dashboard Principal del Coordinador de Planificación
     */
    public function index(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        // Obtener descendientes de los planes del ámbito para KPIs de proyectos
        $planesIds = $context['planes']->pluck('id')->toArray();
        $descendantsIds = [];
        if (!empty($planesIds)) {
            foreach ($context['planes'] as $pl) {
                $descendantsIds = array_merge($descendantsIds, $pl->descendants()->pluck('id')->toArray(), [$pl->id]);
            }
            $descendantsIds = array_unique($descendantsIds);
        }

        $proyectosBaseQuery = ProyectoInstitucional::query();
        if (!$context['user']->hasRole('Administrador') || !empty($descendantsIds)) {
            $proyectosBaseQuery->where(function($q) use ($descendantsIds, $context) {
                if (!empty($descendantsIds)) {
                    $q->whereIn('pei_profile_id', $descendantsIds);
                }
                if (!empty($context['organigramaIds'])) {
                    $q->orWhereIn('dependencia_solicitante_id', $context['organigramaIds'])
                      ->orWhereIn('dependencia_ejecutora_id', $context['organigramaIds']);
                }
            });
        }

        $kpisProyectos = [
            'total'        => (clone $proyectosBaseQuery)->count(),
            'activos'      => (clone $proyectosBaseQuery)->activos()->count(),
            'en_ejecucion' => (clone $proyectosBaseQuery)->enEjecucion()->count(),
            'sin_pei'      => (clone $proyectosBaseQuery)->whereNull('pei_profile_id')->count(),
            'finalizados'  => (clone $proyectosBaseQuery)->where('estado', 'finalizado')->count(),
        ];

        // Estadísticas KPIs
        $kpis = [
            'total_usuarios'     => $context['usuarios']->count(),
            'total_grupos'       => $context['subgruposPermitidos']->count(),
            'total_dependencias' => $context['suborganigramasPermitidos']->count(),
            'total_planes'       => $context['planes']->count(),
            'total_proyectos'    => $kpisProyectos['total'],
        ];

        return view('admin.planificacion.coordinador.index', array_merge($context, [
            'kpis'             => $kpis,
            'kpisProyectos'    => $kpisProyectos,
            'estadosProyectos' => ProyectoInstitucional::ESTADOS,
        ]));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GESTIÓN DE USUARIOS EN ÁMBITO (SOLO HIJOS)
    // ─────────────────────────────────────────────────────────────────────────

    public function getUsuariosData(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        return DataTables::of($context['usuarios'])
            ->addIndexColumn()
            ->addColumn('user_info', function (User $u) {
                $avatar = $u->avatar_url ?? null;
                $initial = strtoupper(substr($u->name, 0, 1));
                $avatarHtml = $avatar
                    ? '<img src="' . asset($avatar) . '" class="rounded-circle mr-2 border shadow-sm" style="width: 36px; height: 36px; object-fit: cover;">'
                    : '<span class="avatar-circle mr-2 bg-info text-white font-weight-bold d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 50%;">' . $initial . '</span>';

                return '<div class="d-flex align-items-center">' . $avatarHtml . '<div><div class="font-weight-bold text-dark">' . e($u->name) . '</div><small class="text-muted">' . e($u->email) . '</small></div></div>';
            })
            ->addColumn('group_name', function (User $u) {
                return $u->group
                    ? '<span class="badge badge-light border text-dark"><i class="fa fa-users text-primary mr-1"></i>' . e($u->group->name) . '</span>'
                    : '<span class="badge badge-secondary">Sin asignar</span>';
            })
            ->addColumn('roles_list', function (User $u) {
                if ($u->roles->isEmpty()) {
                    return '<span class="badge badge-light text-muted">Sin Rol</span>';
                }
                return $u->roles->map(function ($r) {
                    $color = match ($r->name) {
                        'Administrador' => 'badge-danger',
                        'Coordinador de Planificación' => 'badge-warning text-dark',
                        'Analista de Planificación' => 'badge-info',
                        'Gestor de Actividades' => 'badge-primary',
                        default => 'badge-success'
                    };
                    return '<span class="badge ' . $color . ' mr-1 mb-1">' . e($r->name) . '</span>';
                })->implode(' ');
            })
            ->addColumn('action', function (User $u) {
                $btn = '<button type="button" data-id="' . $u->id . '" class="btn btn-primary btn-circle editUser mr-1" title="Editar"><i class="fa fa-edit"></i></button>';
                if ($u->id !== auth()->id()) {
                    $btn .= '<button type="button" data-id="' . $u->id . '" data-name="' . e($u->name) . '" class="btn btn-danger btn-circle deleteUser" title="Eliminar"><i class="fa fa-trash"></i></button>';
                }
                return $btn;
            })
            ->rawColumns(['user_info', 'group_name', 'roles_list', 'action'])
            ->make(true);
    }

    public function storeUsuario(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $userId = $request->input('user_id');

        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . ($userId ?: 'NULL') . ',id',
            'group_id' => 'required|exists:groups,id',
            'roles'    => 'required|array',
        ];

        if (!$userId) {
            $rules['password'] = 'required|string|min:6|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }

        $request->validate($rules);

        // Validar que el group_id esté dentro de los grupos permitidos
        if (!in_array((int)$request->group_id, $context['grupoIds'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'El grupo seleccionado no pertenece al ámbito de tu coordinación.'
            ], 422);
        }

        if ($userId) {
            $userToEdit = User::findOrFail($userId);
            if (!$context['user']->hasRole('Administrador') && !$userToEdit->perteneceAlArbol($context['grupoPadre']) && !in_array($userToEdit->group_id, $context['grupoIds'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tenés permisos para modificar a este usuario.'
                ], 403);
            }
        } else {
            $userToEdit = new User();
        }

        $userToEdit->name = $request->name;
        $userToEdit->email = $request->email;
        $userToEdit->group_id = $request->group_id;

        if ($request->filled('password')) {
            $userToEdit->password = Hash::make($request->password);
        }

        $userToEdit->save();

        // Asignar roles (evitar otorgar Administrador si no es admin)
        $rolesToAssign = $request->roles;
        if (!$context['user']->hasRole('Administrador')) {
            $rolesToAssign = array_filter($rolesToAssign, fn($r) => $r !== 'Administrador');
        }
        $userToEdit->syncRoles($rolesToAssign);

        // Sincronizar en tabla pivote de miembros
        if (method_exists($userToEdit, 'groups')) {
            $userToEdit->groups()->syncWithoutDetaching([$request->group_id]);
        }

        return response()->json([
            'success' => true,
            'message' => $userId ? 'Usuario actualizado con éxito.' : 'Usuario creado con éxito en tu ámbito de planificación.'
        ]);
    }

    public function editUsuario($id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $user = User::with('roles')->findOrFail($id);

        if (!$context['user']->hasRole('Administrador') && !$user->perteneceAlArbol($context['grupoPadre']) && !in_array($user->group_id, $context['grupoIds'])) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no pertenece a tu árbol de coordinación.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'user'    => $user,
            'roles'   => $user->roles->pluck('name'),
        ]);
    }

    public function destroyUsuario($id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        if ((int)$id === (int)auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario.'
            ], 422);
        }

        $user = User::findOrFail($id);

        if (!$context['user']->hasRole('Administrador') && !$user->perteneceAlArbol($context['grupoPadre']) && !in_array($user->group_id, $context['grupoIds'])) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no pertenece a tu ámbito de coordinación.'
            ], 403);
        }

        $user->roles()->detach();
        if (method_exists($user, 'groups')) {
            $user->groups()->detach();
        }
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente del ámbito.'
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GESTIÓN DE GRUPOS DE TRABAJO EN ÁMBITO (SOLO SUBGRUPOS HIJOS)
    // ─────────────────────────────────────────────────────────────────────────

    public function getGruposData(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        // Solo listar subgrupos hijos (no listar el grupo padre)
        $grupos = !empty($context['subgrupoIds'])
            ? Group::whereIn('id', $context['subgrupoIds'])->with('members')->get()
            : collect();

        return DataTables::of($grupos)
            ->addIndexColumn()
            ->addColumn('tipo_badge', function (Group $g) {
                return '<span class="badge badge-info"><i class="fa fa-users mr-1"></i>Grupo de Trabajo</span>';
            })
            ->addColumn('members_count', function (Group $g) {
                $count = $g->members->count();
                return '<span class="badge badge-light border font-weight-bold text-primary">' . $count . ' integrantes</span>';
            })
            ->addColumn('members_names', function (Group $g) {
                if ($g->members->isEmpty()) {
                    return '<span class="text-muted small">Sin miembros asignados</span>';
                }
                return $g->members->pluck('name')->map(fn($n) => '<span class="badge badge-light text-dark border mr-1 mb-1">' . e($n) . '</span>')->implode(' ');
            })
            ->addColumn('action', function (Group $g) {
                $btn = '<button type="button" data-id="' . $g->id . '" data-name="' . e($g->name) . '" class="btn btn-info btn-circle btnMiembrosGrupo mr-1" title="Gestionar Integrantes"><i class="fa fa-user-plus"></i></button>';
                $btn .= '<button type="button" data-id="' . $g->id . '" class="btn btn-primary btn-circle editGroup mr-1" title="Editar Grupo"><i class="fa fa-edit"></i></button>';
                $btn .= '<button type="button" data-id="' . $g->id . '" data-name="' . e($g->name) . '" class="btn btn-danger btn-circle deleteGroup" title="Eliminar Grupo"><i class="fa fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['tipo_badge', 'members_count', 'members_names', 'action'])
            ->make(true);
    }

    public function storeGrupo(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $groupId = $request->input('group_id');

        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:groups,id',
            'members'   => 'nullable|array',
        ]);

        if ($groupId) {
            $group = Group::findOrFail($groupId);
            if (!in_array($group->id, $context['grupoIds'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tenés permisos sobre este grupo.'
                ], 403);
            }
        } else {
            $group = new Group();
            $group->parent_id = $request->parent_id ?: ($context['grupoPadre'] ? $context['grupoPadre']->id : null);
        }

        $group->name = $request->name;
        $group->save();

        if ($request->has('members')) {
            $group->members()->sync($request->members ?? []);
        }

        return response()->json([
            'success' => true,
            'message' => $groupId ? 'Grupo de trabajo actualizado con éxito.' : 'Nuevo grupo de trabajo creado en tu árbol institucional.'
        ]);
    }

    public function editGrupo($id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $group = Group::with('members')->findOrFail($id);

        if (!in_array($group->id, $context['grupoIds'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'El grupo no pertenece a tu árbol de coordinación.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'group'   => $group,
            'members' => $group->members->pluck('id'),
        ]);
    }

    public function destroyGrupo($id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        if ($context['grupoPadre'] && (int)$id === (int)$context['grupoPadre']->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar el Evento/Grupo Raíz de la coordinación.'
            ], 422);
        }

        $group = Group::findOrFail($id);

        if (!in_array($group->id, $context['grupoIds'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'El grupo no pertenece a tu árbol de coordinación.'
            ], 403);
        }

        $group->members()->detach();
        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Grupo de trabajo eliminado exitosamente.'
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MODAL DE GESTIÓN DE INTEGRANTES DE GRUPO (DATATABLES & VINCULACIÓN)
    // ─────────────────────────────────────────────────────────────────────────

    public function getGrupoMiembros($id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $group = Group::with(['members.roles'])->findOrFail($id);

        if (!in_array($group->id, $context['grupoIds'], true)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return DataTables::of($group->members)
            ->addIndexColumn()
            ->addColumn('user_info', function (User $u) {
                $avatar = $u->avatar_url ?? null;
                $initial = strtoupper(substr($u->name, 0, 1));
                $avatarHtml = $avatar
                    ? '<img src="' . asset($avatar) . '" class="rounded-circle mr-2 border shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">'
                    : '<span class="avatar-circle mr-2 bg-info text-white font-weight-bold d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; font-size: 12px;">' . $initial . '</span>';

                return '<div class="d-flex align-items-center">' . $avatarHtml . '<div><div class="font-weight-bold text-dark" style="font-size: 13px;">' . e($u->name) . '</div><small class="text-muted">' . e($u->email) . '</small></div></div>';
            })
            ->addColumn('roles_list', function (User $u) {
                if ($u->roles->isEmpty()) {
                    return '<span class="badge badge-light text-muted">Sin Rol</span>';
                }
                return $u->roles->map(fn($r) => '<span class="badge badge-secondary mr-1 mb-1">' . e($r->name) . '</span>')->implode(' ');
            })
            ->addColumn('action', function (User $u) use ($group) {
                return '<button type="button" data-user-id="' . $u->id . '" data-group-id="' . $group->id . '" data-name="' . e($u->name) . '" class="btn btn-danger btn-circle btnRemoverMiembro" title="Desvincular del grupo"><i class="fa fa-user-minus"></i></button>';
            })
            ->rawColumns(['user_info', 'roles_list', 'action'])
            ->make(true);
    }

    public function asignarMiembroGrupo(Request $request, $id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $group = Group::findOrFail($id);

        if (!in_array($group->id, $context['grupoIds'], true)) {
            return response()->json(['success' => false, 'message' => 'No autorizado en este grupo.'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Si el usuario no tiene grupo principal asignado, asignarle este grupo
        if (!$user->group_id) {
            $user->group_id = $group->id;
            $user->save();
        }

        $group->members()->syncWithoutDetaching([$user->id]);

        return response()->json([
            'success' => true,
            'message' => "Usuario {$user->name} asignado al grupo {$group->name}.",
        ]);
    }

    public function crearYAsignarMiembroGrupo(Request $request, $id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $group = Group::findOrFail($id);

        if (!in_array($group->id, $context['grupoIds'], true)) {
            return response()->json(['success' => false, 'message' => 'No autorizado en este grupo.'], 403);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles'    => 'required|array',
        ]);

        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->group_id = $group->id;
        $newUser->save();

        $rolesToAssign = $request->roles;
        if (!$context['user']->hasRole('Administrador')) {
            $rolesToAssign = array_filter($rolesToAssign, fn($r) => $r !== 'Administrador');
        }
        $newUser->syncRoles($rolesToAssign);

        $group->members()->syncWithoutDetaching([$newUser->id]);

        return response()->json([
            'success' => true,
            'message' => "Usuario {$newUser->name} creado y asignado exitosamente al grupo {$group->name}.",
        ]);
    }

    public function removerMiembroGrupo($id, $userId)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $group = Group::findOrFail($id);

        if (!in_array($group->id, $context['grupoIds'], true)) {
            return response()->json(['success' => false, 'message' => 'No autorizado en este grupo.'], 403);
        }

        $user = User::findOrFail($userId);
        $group->members()->detach($user->id);

        // Si su group_id principal era este, limpiarlo o pasarlo al grupo padre
        if ((int)$user->group_id === (int)$group->id) {
            $user->group_id = $context['grupoPadre'] ? $context['grupoPadre']->id : null;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => "Usuario {$user->name} desvinculado del grupo {$group->name}.",
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GESTIÓN DE ORGANIGRAMA Y DEPENDENCIAS EN ÁMBITO (ÁRBOL TIPO GESTIONAR)
    // ─────────────────────────────────────────────────────────────────────────

    public function getOrganigramaTree(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        if (!$context['organigramaRaiz']) {
            return response()->json([]);
        }

        $tree = Organigrama::descendantsAndSelf($context['organigramaRaiz']->id)->toTree();

        $formatter = function ($nodes) use (&$formatter) {
            $out = [];
            foreach ($nodes as $n) {
                $item = [
                    'id'        => $n->id,
                    'name'      => $n->dependency,
                    'title'     => $n->manager ?? 'Sin encargado',
                    'email'     => $n->email ?? '',
                    'phone'     => $n->phone ?? '',
                    'children'  => [],
                ];
                if ($n->children->isNotEmpty()) {
                    $item['children'] = $formatter($n->children);
                }
                $out[] = $item;
            }
            return $out;
        };

        return response()->json($formatter($tree)[0] ?? []);
    }

    public function storeDependencia(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $depId = $request->input('dependency_id');

        $request->validate([
            'dependency'           => 'required|string|max:255',
            'parent_id'            => 'nullable|exists:organigramas,id',
            'manager'              => 'nullable|string|max:255',
            'user_id'              => 'nullable|exists:users,id',
            'email'                => 'nullable|email|max:255',
            'phone'                => 'nullable|string|max:255',
            'address'              => 'nullable|string|max:255',
            'tipo_establecimiento' => 'nullable|string|max:255',
        ]);

        if ($depId) {
            $dep = Organigrama::findOrFail($depId);
            if (!in_array($dep->id, $context['organigramaIds'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tenés permisos para editar esta dependencia.'
                ], 403);
            }
        } else {
            $dep = new Organigrama();
            $parentId = $request->parent_id ?: ($context['organigramaRaiz'] ? $context['organigramaRaiz']->id : null);
            if ($parentId && !in_array((int)$parentId, $context['organigramaIds'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El nodo superior seleccionado no forma parte de tu organigrama.'
                ], 422);
            }
            $dep->parent_id = $parentId;
        }

        $dep->dependency = $request->dependency;
        $dep->manager = $request->manager;
        $dep->user_id = $request->user_id ?: null;
        $dep->email = $request->email;
        $dep->phone = $request->phone;
        $dep->address = $request->address;
        $dep->tipo_establecimiento = $request->tipo_establecimiento;
        $dep->save();

        return response()->json([
            'success' => true,
            'message' => $depId ? 'Dependencia actualizada correctamente.' : 'Sub-dependencia agregada a tu estructura orgánica.'
        ]);
    }

    public function editDependencia($id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $dep = Organigrama::with('user')->findOrFail($id);

        if (!in_array($dep->id, $context['organigramaIds'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'La dependencia no pertenece a tu rama orgánica.'
            ], 403);
        }

        return response()->json([
            'success'     => true,
            'dependencia' => $dep,
        ]);
    }

    public function destroyDependencia($id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        if ($context['organigramaRaiz'] && (int)$id === (int)$context['organigramaRaiz']->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar la Dependencia Raíz de la estructura.'
            ], 422);
        }

        $dep = Organigrama::findOrFail($id);

        if (!in_array($dep->id, $context['organigramaIds'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'La dependencia no pertenece a tu rama orgánica.'
            ], 403);
        }

        $dep->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dependencia eliminada de la estructura orgánica.'
        ]);
    }

    /**
     * Mover nodo en el árbol del organigrama (Drag & Drop en tiempo real)
     */
    public function moverOrganigrama(Request $request, $id)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        $request->validate([
            'parent_id' => 'nullable|integer',
            'before_id' => 'nullable|integer',
            'after_id'  => 'nullable|integer',
        ]);

        $nodo = Organigrama::findOrFail($id);

        // Validar que ambos estén en el ámbito
        if (!in_array($nodo->id, $context['organigramaIds'], true)) {
            return response()->json([
                'error' => 'No tenés permisos para mover dependencias fuera de tu árbol institucional.'
            ], 403);
        }

        $nuevoPadre = null;

        if ($request->filled('before_id')) {
            $sibling = Organigrama::findOrFail($request->before_id);
            if (!in_array($sibling->id, $context['organigramaIds'], true) || $sibling->id === $nodo->id || $sibling->isDescendantOf($nodo)) {
                return response()->json(['error' => 'Posición inválida en la jerarquía.'], 422);
            }
            $nodo->beforeNode($sibling)->save();
            $nodo->refresh();
            $nuevoPadre = $nodo->parent;
        } elseif ($request->filled('after_id')) {
            $sibling = Organigrama::findOrFail($request->after_id);
            if (!in_array($sibling->id, $context['organigramaIds'], true) || $sibling->id === $nodo->id || $sibling->isDescendantOf($nodo)) {
                return response()->json(['error' => 'Posición inválida en la jerarquía.'], 422);
            }
            $nodo->afterNode($sibling)->save();
            $nodo->refresh();
            $nuevoPadre = $nodo->parent;
        } elseif ($request->filled('parent_id')) {
            $nuevoPadre = Organigrama::findOrFail($request->parent_id);
            if (!in_array($nuevoPadre->id, $context['organigramaIds'], true) || $nuevoPadre->id === $nodo->id || $nuevoPadre->isDescendantOf($nodo)) {
                return response()->json([
                    'error' => 'No se puede mover una dependencia a sí misma o a uno de sus propios descendientes.'
                ], 422);
            }
            $nodo->appendToNode($nuevoPadre)->save();
            $nodo->refresh();
        } else {
            return response()->json(['error' => 'Faltan datos de destino para el movimiento.'], 422);
        }

        $padreNombre = $nuevoPadre ? $nuevoPadre->dependency : ($context['organigramaRaiz']->dependency ?? 'Raíz');

        return response()->json([
            'success' => true,
            'message' => "'{$nodo->dependency}' se reubicó correctamente.",
            'node_id' => $nodo->id,
            'parent_name' => $padreNombre,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PESTAÑA PLANES INSTITUCIONALES (PEI)
    // ─────────────────────────────────────────────────────────────────────────

    public function getPlanesData(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        return DataTables::of($context['planes'])
            ->addIndexColumn()
            ->addColumn('plan_info', function (PeiProfile $p) {
                $years = ($p->year_start && $p->year_end) ? "({$p->year_start} - {$p->year_end})" : '';
                return '<div><div class="font-weight-bold text-dark" style="font-size: 14px;">' . e($p->name) . ' <span class="badge badge-info ml-1">' . $years . '</span></div><small class="text-muted">' . e($p->mision ? substr(strip_tags($p->mision), 0, 90) . '...' : 'Plan Estratégico Institucional') . '</small></div>';
            })
            ->addColumn('dependencia_info', function (PeiProfile $p) {
                $dep = $p->dependency ? $p->dependency->dependency : 'Dependencia no vinculada';
                $group = $p->group ? $p->group->name : 'Sin grupo';
                return '<div><span class="badge badge-light border text-dark"><i class="fa fa-sitemap text-info mr-1"></i>' . e($dep) . '</span><br><small class="text-muted"><i class="fa fa-users mr-1"></i>' . e($group) . '</small></div>';
            })
            ->addColumn('progreso', function (PeiProfile $p) {
                $progress = (int)($p->progress ?? 0);
                $color = $progress >= 70 ? 'bg-success' : ($progress >= 40 ? 'bg-warning' : 'bg-danger');
                return '<div class="d-flex align-items-center"><div class="progress flex-grow-1 mr-2" style="height: 8px; border-radius: 4px;"><div class="progress-bar ' . $color . '" role="progressbar" style="width: ' . $progress . '%"></div></div><span class="font-weight-bold small">' . $progress . '%</span></div>';
            })
            ->addColumn('action', function (PeiProfile $p) {
                $urlPei = url("pei-profiles/{$p->id}");

                // Resolver enlace al cruce de ambientes FODA vinculado al contexto
                $groupIdFoda = $p->fodaPerfil?->group_id ?? $p->group_id;
                if ($groupIdFoda) {
                    $urlFoda = route('foda-matriz-groups-crossing', $groupIdFoda);
                } elseif ($p->foda_perfil_id) {
                    $urlFoda = route('foda-cruce-ambientes', $p->foda_perfil_id);
                } else {
                    $urlFoda = route('foda-list-groups');
                }

                return '<div class="d-flex justify-content-center" style="gap:4px;">' .
                    '<a href="' . $urlPei . '" class="btn btn-primary btn-circle" target="_blank" title="Ver PEI"><i class="fa fa-external-link-alt"></i></a>' .
                    '<button type="button" class="btn btn-info btn-circle btnVerCertificacionMef" data-id="' . $p->id . '" data-name="' . e($p->name) . '" title="Certificación MEF"><i class="fa fa-certificate"></i></button>' .
                    '<button type="button" class="btn btn-warning btn-circle text-dark btnVerFodaCrossing" data-url="' . $urlFoda . '" data-name="' . e($p->name) . '" title="Análisis FODA"><i class="fa fa-random"></i></button>' .
                    '</div>';
            })
            ->rawColumns(['plan_info', 'dependencia_info', 'progreso', 'action'])
            ->make(true);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PESTAÑA PROYECTOS INSTITUCIONALES (CONTEXTO PEI)
    // ─────────────────────────────────────────────────────────────────────────

    public function getProyectosData(Request $request)
    {
        $this->requirePlanificacionScope();
        $context = $this->getScopeContext();

        // Obtener todos los IDs de acciones y perfiles de los planes del ámbito
        $planesIds = $context['planes']->pluck('id')->toArray();
        $descendantsIds = [];
        if (!empty($planesIds)) {
            foreach ($context['planes'] as $pl) {
                $descendantsIds = array_merge($descendantsIds, $pl->descendants()->pluck('id')->toArray(), [$pl->id]);
            }
            $descendantsIds = array_unique($descendantsIds);
        }

        $query = ProyectoInstitucional::with([
            'dependenciaSolicitante', 'dependenciaEjecutora', 'analista', 'peiProfile'
        ]);

        if (!$context['user']->hasRole('Administrador') || !empty($descendantsIds)) {
            $query->where(function($q) use ($descendantsIds, $context) {
                if (!empty($descendantsIds)) {
                    $q->whereIn('pei_profile_id', $descendantsIds);
                }
                if (!empty($context['organigramaIds'])) {
                    $q->orWhereIn('dependencia_solicitante_id', $context['organigramaIds'])
                      ->orWhereIn('dependencia_ejecutora_id', $context['organigramaIds']);
                }
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('pei_profile_id')) {
            $perfil = PeiProfile::find($request->pei_profile_id);
            if ($perfil) {
                $accIds = $perfil->descendants()->pluck('id')->push($perfil->id)->toArray();
                $query->whereIn('pei_profile_id', $accIds);
            }
        }
        if ($request->filled('q')) {
            $qSearch = $request->q;
            $query->where(function($q) use ($qSearch) {
                $q->where('nombre', 'ilike', '%' . $qSearch . '%')
                  ->orWhere('codigo', 'ilike', '%' . $qSearch . '%');
            });
        }

        $query->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('codigo_badge', function (ProyectoInstitucional $p) {
                return '<span class="badge badge-dark font-weight-bold px-2 py-1">' . e($p->codigo) . '</span>';
            })
            ->addColumn('nombre_info', function (ProyectoInstitucional $p) {
                $desc = $p->descripcion ? substr(strip_tags($p->descripcion), 0, 75) . '...' : 'Sin descripción adicional';
                return '<div><div class="font-weight-bold text-dark" style="font-size: 13.5px;">' . e($p->nombre) . '</div><small class="text-muted">' . e($desc) . '</small></div>';
            })
            ->addColumn('dependencia_info', function (ProyectoInstitucional $p) {
                $depSol = $p->dependenciaSolicitante ? $p->dependenciaSolicitante->dependency : 'No asignada';
                $depEj = $p->dependenciaEjecutora ? $p->dependenciaEjecutora->dependency : null;
                $html = '<div><span class="badge badge-light border text-dark"><i class="fa fa-building text-primary mr-1"></i>' . e($depSol) . '</span>';
                if ($depEj && $depEj !== $depSol) {
                    $html .= '<br><small class="text-muted"><i class="fa fa-cogs mr-1"></i>Ejecuta: ' . e($depEj) . '</small>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('pei_vinculo', function (ProyectoInstitucional $p) {
                if ($p->peiProfile) {
                    return '<div><span class="badge badge-success mb-1"><i class="fa fa-link mr-1"></i>Vinculado</span><br><small class="text-muted font-weight-bold" style="font-size: 11px;">' . e(\Illuminate\Support\Str::limit($p->peiProfile->name, 35)) . '</small></div>';
                }
                return '<span class="badge badge-warning"><i class="fa fa-unlink mr-1"></i>Sin vincular</span>';
            })
            ->addColumn('estado_badge', function (ProyectoInstitucional $p) {
                return '<span class="badge ' . ProyectoInstitucional::estadoBadge($p->estado) . ' px-2 py-1 font-weight-bold">' .
                    ProyectoInstitucional::estadoLabel($p->estado) . '</span>';
            })
            ->addColumn('checklist_pct', function (ProyectoInstitucional $p) {
                $pct = $p->pctChecklist();
                $color = $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger');
                return '<div class="d-flex align-items-center justify-content-center flex-column" style="min-width: 90px;"><div class="progress w-100 mb-1" style="height: 7px; border-radius: 4px;"><div class="progress-bar ' . $color . '" style="width: ' . $pct . '%;"></div></div><small class="font-weight-bold text-dark">' . $pct . '% Completo</small></div>';
            })
            ->addColumn('action', function (ProyectoInstitucional $p) {
                $urlShow = route('proyectos-institucionales.show', $p->id);
                $urlEdit = route('proyectos-institucionales.edit', $p->id);
                return '<div class="btn-group text-center">' .
                    '<a href="' . $urlShow . '" class="btn btn-info btn-circle mr-1" target="_blank" title="Ver Detalle del Proyecto"><i class="fa fa-eye"></i></a>' .
                    '<a href="' . $urlEdit . '" class="btn btn-primary btn-circle" target="_blank" title="Editar Proyecto"><i class="fa fa-edit"></i></a>' .
                    '</div>';
            })
            ->rawColumns(['codigo_badge', 'nombre_info', 'dependencia_info', 'pei_vinculo', 'estado_badge', 'checklist_pct', 'action'])
            ->make(true);
    }

    // Métodos heredados/compatibilidad
    public function gestionarGrupos(Request $request)
    {
        return redirect()->route('coordinador.index');
    }

    public function crearActividad(Request $request)
    {
        return redirect()->route('coordinador.index');
    }

    public function verGruposYUsuarios(Request $request)
    {
        return redirect()->route('coordinador.index');
    }

    public function verOrganigrama(Request $request)
    {
        return redirect()->route('coordinador.index');
    }
}
