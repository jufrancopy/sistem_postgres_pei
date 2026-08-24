<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Admin\Globales\Group;
use App\Admin\Planificacion\Foda\FodaModelo;;

use Kalnoy\Nestedset\NodeTrait;
use Yajra\DataTables\DataTables;
use App\Models\User;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:Administrador|Super Admin|Coordinador de Planificación|Coordinación de Planificación|Analista de Planificación|Analista PEI'])->except(['getRootGroups', 'getGroupsFromRoot', 'dataGroupParent', 'dataGroup']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::where('parent_id', null)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return '<span class="badge bg-success">Activo</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Editar" class="btn btn-primary btn-circle editGroup"><i class="far fa-edit"></i></a>';
                    $btn .= ' <a href="' . route('globales.groups.show', $row->id) . '" class="btn btn-success btn-circle" title="Miembros"><i class="fa fa-users"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Eliminar" class="btn btn-danger btn-circle deleteGroup"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.globales.groups.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['Administrador', 'Super Admin']);

        if (!$isAdmin) {
            $targetGroupId = $request->group_id ?? $request->parent_id;
            $userGroupId = $user->group_id;
            $isMember = $targetGroupId && \DB::table('globales.groups_has_users')->where('group_id', $targetGroupId)->where('user_id', $user->id)->exists();

            if ($targetGroupId != $userGroupId && !$isMember) {
                return response()->json(['error' => 'No tienes permisos para modificar integrantes de otro grupo.'], 403);
            }

            if ($request->has('name') && !$request->group_id) {
                return response()->json(['error' => 'No tienes permisos para crear nuevos grupos de trabajo.'], 403);
            }
        }

        if ($request->ajax() && (!$request->group_id || $request->has('name'))) {
            $request->validate(
                ['name' => 'required'],
                ['name.required' => 'El campo Nombre es requerido']
            );
        }

        try {
            if (!$request->group_id && $request->parent_id) {
                $parent = Group::findOrFail($request->parent_id);
                $group = new Group(['name' => $request->name]);
                $parent->appendNode($group);
            } else {
                $group = Group::findOrFail($request->group_id);
                if ($request->has('name') && !empty($request->name)) {
                    if (!$isAdmin) {
                        // Coordinadores solo gestionan integrantes, no renombran el grupo
                    } else {
                        $group->name = $request->name;
                        $group->save();
                    }
                }
            }

            if ($request->has('user_id')) {
                $members = $request->user_id ?? [];
                $syncResult = $group->members()->sync($members);
                
                // Si hubo nuevos integrantes asociados, sincronizar sus bonos retroactivos de equipo
                if (!empty($syncResult['attached'])) {
                    $gamificationService = app(\App\Services\GamificationService::class);
                    foreach ($syncResult['attached'] as $newUserId) {
                        $newUser = User::find($newUserId);
                        if ($newUser) {
                            $gamificationService->syncNewMemberGroupRewards($group, $newUser);
                        }
                    }
                }
            }

            return response()->json([
                'success' => 'Información del grupo e integrantes guardada correctamente.',
                'group'   => $group
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Otorgar puntos masivos de reconocimiento a todos los miembros de un equipo de trabajo (Grupo).
     */
    public function otorgarPuntosGrupo(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['Administrador', 'Super Admin'])) {
            return response()->json(['error' => 'La asignación de puntos está reservada exclusivamente al Administrador del Sistema.'], 403);
        }

        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'points'         => 'required|integer|min:1|max:10000',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'is_retroactive' => 'nullable|boolean',
        ], [
            'points.required' => 'La cantidad de puntos es requerida.',
            'points.min'      => 'La cantidad mínima es 1 punto.',
            'title.required'  => 'El título o motivo del premio es requerido.',
        ]);

        $points        = (int)$validated['points'];
        $title         = $validated['title'];
        $description   = $validated['description'] ?? null;
        $isRetroactive = $request->has('is_retroactive') ? (bool)$request->input('is_retroactive') : true;

        $gamificationService = app(\App\Services\GamificationService::class);
        $reward = $gamificationService->rewardGroup(
            $group,
            $points,
            $title,
            $description,
            $isRetroactive,
            auth()->user()
        );

        return response()->json([
            'success' => true,
            'message' => "¡Se han otorgado exitosamente {$points} puntos a los integrantes del equipo {$group->name}!",
            'reward'  => $reward
        ]);
    }

    /**
     * Otorgar puntos masivos de reconocimiento por Cierre de Semana Exitoso a todos los integrantes de un equipo y sus subgrupos.
     */
    public function otorgarPuntosCierreSemana(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['Administrador', 'Super Admin'])) {
            return response()->json(['error' => 'La asignación de puntos está reservada exclusivamente al Administrador del Sistema.'], 403);
        }

        $group = Group::findOrFail($id);

        $points = (int) $request->input('points', 100);
        $title  = $request->input('title', '🎉 Cierre de Semana Exitoso — Aporte en SIPLAN GO!');
        $desc   = $request->input('description', 'Reconocimiento al esfuerzo, compromiso y colaboración en la gestión institucional.');

        $gamificationService = app(\App\Services\GamificationService::class);
        $reward = $gamificationService->rewardGroup(
            $group,
            $points,
            $title,
            $desc,
            true,
            auth()->user()
        );

        return response()->json([
            'success' => true,
            'message' => "¡Se otorgaron +{$points} Pts por Cierre de Semana Exitoso a los integrantes de {$group->name} y sus subgrupos subordinados!",
            'reward'  => $reward
        ]);
    }

    /**
     * Otorgar puntos manuales de reconocimiento a un funcionario individual.
     */
    public function otorgarPuntosUsuario(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['Administrador', 'Super Admin'])) {
            return response()->json(['error' => 'La asignación manual de puntos está reservada exclusivamente al Administrador del Sistema.'], 403);
        }

        $targetUser = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'points'         => 'required|integer|min:1|max:10000',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'pei_profile_id' => 'nullable|string',
        ], [
            'points.required' => 'La cantidad de puntos es requerida.',
            'points.min'      => 'La cantidad mínima es 1 punto.',
            'title.required'  => 'El motivo o título del reconocimiento es requerido.',
        ]);

        $points      = (int) $validated['points'];
        $title       = trim($validated['title']);
        $description = trim($validated['description'] ?? '');
        $fullDesc    = $description ? "{$title} — {$description}" : $title;
        $peiId       = $validated['pei_profile_id'] ?? session('selected_pei_id') ?? \App\Models\HomeConfiguration::first()?->pei_profile_id;

        $gamificationService = app(\App\Services\GamificationService::class);
        $pointRecord = $gamificationService->awardPoints(
            $targetUser,
            'manual_admin',
            $fullDesc,
            $points,
            null,
            $peiId,
            true
        );

        return response()->json([
            'success' => true,
            'message' => "¡Se han otorgado exitosamente +{$points} Pts a {$targetUser->name}!",
            'point'   => $pointRecord,
            'user'    => [
                'id'     => $targetUser->id,
                'name'   => $targetUser->name,
                'points' => $gamificationService->getUserTotalPoints($targetUser, $peiId),
            ]
        ]);
    }

    /**
     * Crear un nuevo funcionario en el sistema y asignarlo automáticamente al grupo actual.
     */
    public function crearYAsignarFuncionario(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador de Planificación', 'Coordinación de Planificación'])) {
            return response()->json(['error' => 'No tienes permisos para registrar nuevos funcionarios.'], 403);
        }

        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
            'role'     => 'nullable|string',
        ], [
            'name.required'  => 'El nombre completo del funcionario es requerido.',
            'email.required' => 'El correo electrónico es requerido.',
            'email.email'    => 'Debe ser un correo electrónico válido.',
            'email.unique'   => 'El correo electrónico ya se encuentra registrado en el sistema.',
            'password.min'   => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $rawPassword = $validated['password'] ?: '12345678';
        $user = User::create([
            'name'     => trim($validated['name']),
            'email'    => strtolower(trim($validated['email'])),
            'password' => \Illuminate\Support\Facades\Hash::make($rawPassword),
            'group_id' => $group->id,
        ]);

        // Asignar rol por defecto o el seleccionado
        $roleName = $validated['role'] ?? 'Usuario';
        if (\Spatie\Permission\Models\Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        }

        // Asociar al grupo
        if (!$group->members()->where('user_id', $user->id)->exists()) {
            $group->members()->attach($user->id);
            $gamificationService = app(\App\Services\GamificationService::class);
            $gamificationService->syncNewMemberGroupRewards($group, $user);
        }

        return response()->json([
            'success' => true,
            'message' => "¡Funcionario {$user->name} registrado e integrado exitosamente al grupo!",
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function getRootGroups(Request $request)
    {
        $search = $request->get('q', '');
        $data = Group::select("id", "name")
            ->where('name', 'LIKE', "%$search%")
            ->whereNull('parent_id')
            ->get();
        return response()->json($data);
    }

    public function getGroupsFromRoot(Request $request, $idRoot)
    {
        $search = $request->get('q', '');
        $data = Group::select("id", "name")
            ->where('name', 'LIKE', "%$search%")
            ->where('parent_id', $idRoot)
            ->get();
        return response()->json($data);
    }

    public function dataGroupParent(Request $request, $idSelection)
    {
        $data = Group::findOrFail($idSelection)->parent;

        return response()->json($data);
    }

    public function dataGroup(Request $request, $idSelection)
    {
        $data = Group::findOrFail($idSelection);

        return response()->json($data);
    }

    public function edit($id)
    {
        $group = Group::with('members')->findOrFail($id);

        $membersChecked = [];

        foreach ($group->members as $member) {
            $membersChecked[] = ['id' => $member->id, 'text' => $member->name];
        }

        return response()->json(['group' => $group, 'membersChecked' => $membersChecked]);
    }

    public function show(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if ($request->ajax()) {
            $data = Group::descendantsOf($id);
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editGroup"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteSubGroup"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })

                ->addColumn('members', function (Group $group) {
                    $memberNames = $group->members->pluck('name')->implode(', ');
                    return $memberNames;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.globales.groups.show', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function destroy(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['Administrador', 'Super Admin'])) {
            return response()->json(['error' => 'No tienes permisos para eliminar grupos.'], 403);
        }

        $profile = Group::find($id)->delete();

        return response()->json([$profile]);
    }
}
