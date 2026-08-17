<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

use Yajra\DataTables\DataTables;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaPerfil;

use App\Charts\ActionForDependencies;
use App\Models\Planificacion\PeiProfileEdit;
use App\Services\GamificationService;


class PeiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user  = auth()->user();
            $query = PeiProfile::whereNull('parent_id')->where('level', 'master');

            // Filtro por Estado (Activos / Inactivos / Todos)
            $estado = $request->get('estado', 'activos');
            if ($estado === 'activos') {
                $query->where('is_active', true);
            } elseif ($estado === 'inactivos') {
                $query->where('is_active', false);
            }

            if (!$user->hasRole('Administrador')) {
                $userId = $user->id;

                // 1. IDs asignados como Analista
                $analystPeiIds = \DB::table('planificacion.peis_profiles_has_analysts')
                    ->where('analyst_id', $userId)
                    ->pluck('pei_profile_id')
                    ->toArray();

                // 2. IDs donde es responsable de acciones
                $orgId = \App\Admin\Globales\Organigrama::where('user_id', $userId)->value('id');
                $responsibleActionIds = $orgId ? \DB::table('planificacion.peis_profiles_has_responsibles')
                    ->where('responsible_id', $orgId)
                    ->pluck('profile_id')
                    ->toArray() : [];

                $masterIdsFromActions = [];
                if (!empty($responsibleActionIds)) {
                    $masterIdsFromActions = PeiProfile::whereIn('id', $responsibleActionIds)
                        ->get()
                        ->map(function($p) {
                            return PeiProfile::where('_lft', '<=', $p->_lft)
                                ->where('_rgt', '>=', $p->_rgt)
                                ->where('level', 'master')
                                ->value('id');
                        })
                        ->filter()
                        ->unique()
                        ->toArray();
                }

                // 3. IDs vinculados por Actividades
                $activityPeiIds = \App\Admin\Globales\Activity::whereHas('responsibles', fn($q) => $q->where('users.id', $userId))
                    ->whereNotNull('pei_profile_id')
                    ->pluck('pei_profile_id')
                    ->toArray();

                // 4. Grupos de los que forma parte
                $userGroupIds = \DB::table('groups_has_members')
                    ->where('user_id', $userId)
                    ->pluck('group_id')
                    ->toArray();

                $allowedMasterIds = array_unique(array_merge(
                    $analystPeiIds,
                    $masterIdsFromActions,
                    $activityPeiIds
                ));

                $query->where(function($q) use ($userId, $allowedMasterIds, $userGroupIds) {
                    $q->where('user_id', $userId);
                    if (!empty($allowedMasterIds)) {
                        $q->orWhereIn('id', $allowedMasterIds);
                    }
                    if (!empty($userGroupIds)) {
                        $q->orWhereIn('group_id', $userGroupIds);
                    }
                });
            }

            $data = $query->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function (PeiProfile $profile) {
                    return $profile->is_active
                        ? '<span class="badge badge-success px-2 py-1" style="font-size:0.78rem;"><i class="fa fa-check-circle mr-1"></i>Activo</span>'
                        : '<span class="badge badge-secondary px-2 py-1" style="font-size:0.78rem;"><i class="fa fa-eye-slash mr-1"></i>Inactivo</span>';
                })
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $isAdminOrCoordinator = $user->hasAnyRole(['Administrador', 'Coordinador de Planificación']);
                    $btn = '';

                    // 1. Editar PEI (Solo Administrador y Coordinador)
                    if ($isAdminOrCoordinator) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-type="' . $row->type . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfile" title="Editar Perfil PEI"><i class="far fa-edit"></i></a> ';
                    }

                    // 2. Proceso PEI (Disponible para todos, incluyendo Analistas)
                    $btn .= '<a href="' . route('pei-profiles.proceso', $row->id) . '" class="btn btn-success btn-circle" title="Proceso PEI"><i class="fa fa-tasks"></i></a>';

                    // 3. Ver Estructura Árbol (Solo Administrador y Coordinador)
                    if ($isAdminOrCoordinator) {
                        $btn .= ' <a href="' . route('pei-profiles.details', $row->id) . '" class="btn btn-info btn-circle showTree" title="Estructura Completa PEI"><i class="fa fa-tree" aria-hidden="true"></i></a>';
                    }

                    // 4. Activar / Inactivar PEI (Solo Administrador y Coordinador)
                    if ($isAdminOrCoordinator) {
                        $toggleColor = $row->is_active ? 'btn-outline-warning' : 'btn-outline-success';
                        $toggleIcon  = $row->is_active ? 'fa-eye-slash' : 'fa-eye';
                        $toggleTitle = $row->is_active ? 'Inactivar PEI (Ocultar)' : 'Activar PEI';
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" title="' . $toggleTitle . '" class="btn ' . $toggleColor . ' btn-circle toggleStatus"><i class="fa ' . $toggleIcon . '"></i></a>';
                    }

                    // 5. Eliminar (Solo Administrador)
                    if ($user->hasRole('Administrador')) {
                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $btn;
                })
                ->addColumn('group', function (PeiProfile $profile) {
                    return $profile->group ? $profile->group->name : '—';
                })

                ->addColumn('period', function (PeiProfile $profile) {
                    if (!$profile->year_start || !$profile->year_end) {
                        return '—';
                    }
                    $year_start = Carbon::parse($profile->year_start)->format('Y');
                    $year_end = Carbon::parse($profile->year_end)->format('Y');
                    return $year_start . ' - ' . $year_end;
                })

                ->addColumn('analysts', function (PeiProfile $profile) {
                    $analystNames = $profile->analysts->pluck('name')->implode(', ');
                    return $analystNames;
                })

                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.planificacion.peis.peis.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function showAxisList($idProfile)
    {
        $profile = PeiProfile::findOrFail($idProfile);

        // Scoped al PEI actual — solo descendientes directos con level=axi
        $axis = PeiProfile::whereIn('id', $profile->descendants()->pluck('id'))
            ->where('level', 'axi')
            ->with(['strategies'])
            ->get();

        return response()->json(['axis' => $axis]);
    }

    public function showGoalsList($idProfile)
    {
        $profile = PeiProfile::findOrFail($idProfile);

        // Scoped al PEI actual
        $goals = PeiProfile::whereIn('id', $profile->descendants()->pluck('id'))
            ->where('level', 'goal')
            ->get();

        return response()->json(['goals' => $goals]);
    }

    public function showActionsList($idProfile)
    {
        $profile = PeiProfile::findOrFail($idProfile);

        // Scoped al PEI actual
        $actions = PeiProfile::whereIn('id', $profile->descendants()->pluck('id'))
            ->where('level', 'action')
            ->with(['responsibles'])
            ->get();

        return response()->json(['actions' => $actions]);
    }


    public function showMembersList($idProfile)
    {

        $profile = PeiProfile::findOrFail($idProfile);

        $members = [];

        foreach ($profile->group->descendants as $group) {
            foreach ($group->members as $member) {
                $members[] = ['name' => $member->name, 'email' => $member->email];
            }
        }

        return response()->json(['members' => $members]);
    }

    public function showDetailForGroup($idPerfil)
    {
        $profile = PeiProfile::with('group')->findOrFail($idPerfil);
        $members = $profile->group->members;

        return response()->json(['profile' => $profile, 'members' => $members]);
    }

    public function compareHistorical(Request $request)
    {
        // Si se pasa un pei_id, filtrar solo perfiles del mismo grupo raíz
        if ($request->pei_id) {
            $pei = PeiProfile::find($request->pei_id);
            $profile = PeiProfile::with('group')
                ->whereIsRoot()
                ->where('level', 'master')
                ->where('type', 'group')
                ->where('group_id', $pei ? $pei->group_id : null)
                ->get();
        } else {
            $profile = PeiProfile::with('group')
                ->whereIsRoot()
                ->where('level', 'master')
                ->where('type', 'group')
                ->get();
        }

        return response()->json($profile);
    }

    public function store(Request $request)
    {
        $denominator = $request->denominator;
        $reportType = $request->report_type;
        if ($reportType == 'qualitative') {
            $parameters = $request->input('parameters', []); // Obtener los parámetros del request
            $parametersJson = json_encode($parameters); // Convertir a JSON
        } else {
            $parametersJson = null; // Convertir a JSON
        }

        // Validación si la petición es AJAX
        if ($request->ajax()) {
            $request->validate(
                [
                    'name' => 'required',
                    // Agrega más validaciones según sea necesario para otros campos
                ],
                [
                    'name.required' => 'El campo nombre es requerido',
                    // Mensajes de validación personalizados para otros campos
                ]
            );
        }

        $user = Auth::user();

        $rawProfileId = $request->profile_id ? trim($request->profile_id) : null;
        $isCreateAction = $request->saveBtn === 'create' || $request->saveBtnGoals === 'create' || $request->saveBtnActions === 'create';
        $profileId = ($isCreateAction || empty($rawProfileId)) ? null : $rawProfileId;

        // Campos exclusivos del nodo master — solo se actualizan si vienen
        // explícitamente en el request (evita que ediciones de nodos hijos los pisen)
        $masterOnlyFields = ['group_id', 'dependency_id', 'nivel_label', 'bsc_perspectiva',
                             'foda_perfil_id', 'mision', 'vision', 'values', 'year_start', 'year_end'];

        $existing = $profileId ? PeiProfile::where('id', $profileId)
            ->first($masterOnlyFields) : null;

        $resolve = fn(string $field, $requestValue) =>
            $request->has($field) ? ($requestValue ?: null) : ($existing?->$field ?? null);

        $attributes = [
                'name'                 => $request->name,
                'year_start'           => $resolve('year_start', $request->year_start),
                'year_end'             => $resolve('year_end', $request->year_end),
                'type'                 => $request->type,
                'level'                => $request->level,
                'mision'               => $resolve('mision', $request->mision),
                'vision'               => $resolve('vision', $request->vision),
                'values'               => $resolve('values', $request->values),
                'period'               => $request->period,
                'numerator'            => $request->numerator,
                'operator'             => $request->operator,
                'denominator'          => $denominator,
                'goal'                 => $request->goal,
                'progress'             => $request->progress,
                'group_id'             => $resolve('group_id', $request->group_id),
                'dependency_id'        => $resolve('dependency_id', $request->dependency_id),
                'action'               => $request->action,
                'indicator'            => $request->indicator,
                'baseline'             => $request->baseline,
                'target'               => $request->target,
                'user_id'              => $user->id,
                'order_item'           => $request->order_item,
                'report_type'          => $request->report_type,
                'parameters'           => $parametersJson,
                'nivel_label'          => $resolve('nivel_label', $request->nivel_label),
                'foda_perfil_id'       => $resolve('foda_perfil_id', $request->foda_perfil_id),
                'bsc_perspectiva'      => $resolve('bsc_perspectiva', $request->bsc_perspectiva),
                'indicador_id'         => $request->indicador_id ?: null,
                'activity_id'          => $request->activity_id ?: null,
                'resultado_intermedio' => $request->resultado_intermedio ?: null,
                'ri_presupuestario'    => $request->ri_presupuestario ?: null,
                'ri_programa'          => $request->ri_programa ?: null,
                'ri_recursos_gs'       => $request->ri_recursos_gs ?: null,
                'ri_metas'             => json_encode($request->input('ri_metas', [])),
        ];

        // Si es un nodo nuevo con parent_id, lo insertamos directamente en el
        // árbol NestedSet. updateOrCreate no maneja parent_id porque no está en $fillable.
        if (!$profileId && $request->parent_id) {
            $parent  = PeiProfile::findOrFail($request->parent_id);
            $profile = new PeiProfile($attributes);
            $profile->appendToNode($parent)->save();
        } else {
            $profile = PeiProfile::updateOrCreate(['id' => $profileId ?: Str::uuid()], $attributes);
        }

        $wasChanged = $profile->wasChanged();

        // Manejo de relaciones
        $syncAnalysts = $profile->analysts()->sync($request->analyst_id);
        $syncStrategies = $profile->strategies()->sync($request->strategy_id);
        $syncResponsibles = $profile->responsibles()->sync($request->responsible_id);
        $syncActivityTasks = $profile->activityTasks()->sync($request->input('activity_task_ids', []));

        $relationsChanged = 
            !empty($syncAnalysts['attached']) || !empty($syncAnalysts['detached']) || !empty($syncAnalysts['updated']) ||
            !empty($syncStrategies['attached']) || !empty($syncStrategies['detached']) || !empty($syncStrategies['updated']) ||
            !empty($syncResponsibles['attached']) || !empty($syncResponsibles['detached']) || !empty($syncResponsibles['updated']) ||
            !empty($syncActivityTasks['attached']) || !empty($syncActivityTasks['detached']) || !empty($syncActivityTasks['updated']);

        // Registrar editor y otorgar puntos solo en actualizaciones reales (no en creación ni en guardados sin cambios)
        if (!$profile->wasRecentlyCreated && ($wasChanged || $relationsChanged)) {
            $profile->updated_by = $user->id;
            $profile->saveQuietly();

            $peiEditRecord = PeiProfileEdit::create([
                'pei_profile_id' => $profile->id,
                'user_id'        => $user->id,
            ]);

            // Resolver el PEI raíz para asociar el pei_profile_id correcto al punto
            $peiRaizId = $profile->level === 'master'
                ? $profile->id
                : (PeiProfile::where('_lft', '<=', $profile->_lft)
                    ->where('_rgt', '>=', $profile->_rgt)
                    ->where('level', 'master')
                    ->value('id') ?? $profile->id);

            app(GamificationService::class)->awardPoints(
                $user,
                'pei_edit',
                'Edición de elemento PEI: ' . \Illuminate\Support\Str::limit(strip_tags($profile->name), 40),
                100,
                $peiEditRecord,
                (string) $peiRaizId
            );
        }

        // Guardar foda_perfil_id en el PEI raíz de forma segura sin pisar con null al editar sub-nodos
        if ($profile->level === 'master') {
            if ($request->has('foda_perfil_id')) {
                $profile->foda_perfil_id = $request->foda_perfil_id ?: null;
                $profile->save();
            }
        } elseif ($request->filled('foda_perfil_id')) {
            $peiRaiz = PeiProfile::where('_lft', '<=', $profile->_lft)
                ->where('_rgt', '>=', $profile->_rgt)
                ->where('level', 'master')
                ->first();
            if ($peiRaiz) {
                $peiRaiz->foda_perfil_id = $request->foda_perfil_id;
                $peiRaiz->save();
            }
        }
        // Construir arrays para retornar en la respuesta JSON
        $strategiesChecked = $profile->strategies->map(function ($strategy) {
            return ['id' => $strategy->id, 'text' => $strategy->estrategia];
        });

        $responsiblesChecked = $profile->responsibles->map(function ($responsible) {
            return ['id' => $responsible->id, 'name' => $responsible->dependency];
        });

        // Construir respuesta JSON
        $responseData = [
            'success' => $profile->wasRecentlyCreated ? 'Creado exitosamente' : 'Actualizado con éxito',
            'profile' => $profile,
            'strategiesChecked' => $strategiesChecked,
            'responsiblesChecked' => $responsiblesChecked,
        ];

        // Retornar respuesta JSON adecuada según el caso
        if ($request->parent_id) {
            $responseData['parent_id'] = $request->parent_id;
        }

        return response()->json($responseData);
    }

    public function tareasDeActividad(Request $request, $activityId)
    {
        $tasks = \App\Admin\Globales\ActivityTask::where('activity_id', $activityId)
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get(['id', 'title'])
            ->map(fn($t) => ['id' => $t->id, 'text' => $t->title]);
        return response()->json(['results' => $tasks]);
    }

    public function buscarActividades(Request $request)
    {
        $q = $request->get('q', '');
        $actividades = \App\Admin\Globales\Activity::whereNull('deleted_at')
            ->where('name', 'ilike', "%{$q}%")
            ->limit(20)->get()
            ->map(fn($a) => ['id' => $a->id, 'text' => $a->name]);
        return response()->json(['results' => $actividades]);
    }

    public function crearActividadParaAccion(Request $request, $profileId)
    {
        $profile  = PeiProfile::findOrFail($profileId);
        $nombre   = $request->get('nombre') ?: strip_tags($profile->name);
        $activity = \App\Admin\Globales\Activity::create([
            'name'       => $nombre,
            'type'       => 'kanba',
            'date_start' => $profile->year_start,
            'date_end'   => $profile->year_end,
        ]);
        $profile->update(['activity_id' => $activity->id]);
        return response()->json([
            'success'  => 'Actividad creada y vinculada',
            'activity' => ['id' => $activity->id, 'text' => $activity->name],
        ]);
    }

    public function edit($id)
    {
        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles', 'activity'])->find($id);
        $groupParent = null;
        if ($profile->group && $profile->group->parent_id) {
            $groupParent = \App\Admin\Globales\Group::find($profile->group->parent_id);
        }

        // Obtener foda_perfil_id del PEI raíz (nivel master)
        $peiRaiz = $profile->level === 'master'
            ? $profile
            : PeiProfile::where('_lft', '<=', $profile->_lft)
                ->where('_rgt', '>=', $profile->_rgt)
                ->where('level', 'master')
                ->first();
        $fodaPerfilId = $peiRaiz ? $peiRaiz->foda_perfil_id : null;
        // Inyectar en el profile para que el JS lo lea
        $profile->foda_perfil_id = $fodaPerfilId;
        $strategiesChecked = [];
        foreach ($profile->strategies as $strategy) {
            $strategiesChecked[] = ['id' => $strategy->id, 'text' => $strategy->estrategia];
        }

        $responsiblesChecked = [];
        foreach ($profile->responsibles as $responsible) {
            $responsiblesChecked[] = ['id' => $responsible->id, 'text' => $responsible->dependency];
        }

        $analystsChecked = [];
        foreach ($profile->analysts as $analyst) {
            $analystsChecked[] = ['id' => $analyst->id, 'text' => $analyst->name];
        }

        return response()->json([
            'profile'             => $profile,
            'groupParent'         => $groupParent,
            'analystsChecked'     => $analystsChecked,
            'strategiesChecked'   => $strategiesChecked,
            'responsiblesChecked' => $responsiblesChecked,
            'activitySelected'    => $profile->activity
                                        ? ['id' => $profile->activity->id, 'text' => $profile->activity->name]
                                        : null,
            'activityTasksSelected' => $profile->activityTasks->map(fn($t) => ['id' => $t->id, 'text' => $t->title])->values(),
            'fodaPerfiles'        => \App\Admin\Planificacion\Foda\FodaPerfil::whereIn('type', ['individual', 'consolidado'])
                                        ->orderBy('name')
                                        ->get(['id', 'name', 'type']),
            'fodaPerfilNombre'     => $profile->fodaPerfil ? '[' . $profile->fodaPerfil->type . '] ' . $profile->fodaPerfil->name : null,
            'resultadosIntermedios' => \App\Admin\Planificacion\Pei\PeiProfile::where('level', 'axi')
                                        ->whereNotNull('resultado_intermedio')
                                        ->distinct()
                                        ->pluck('resultado_intermedio'),
        ]);
    }

    public function show(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['Administrador', 'Coordinador de Planificación'])) {
            return redirect()->route('pei-profiles.proceso', $id);
        }

        $profile = PeiProfile::with([
                'analysts', 'descendants', 'dependency', 'group', 'responsibles', 'strategies',
                'children.marcos', 'children.strategies',
                'children.indicador', // nivel 1 (axi) con su indicador
                'children.children.indicador', // nivel 2 (goal) con su indicador
                'children.children.children.indicador', // nivel 3 (action) con su indicador
            ])
            ->findOrFail($id);
        $type = $profile->type;

        // Resolver etiquetas dinámicas del modelo de niveles
        $nivelesDefault = ['master' => 'PEI', 'axi' => 'Nivel 1', 'goal' => 'Nivel 2', 'action' => 'Acción'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) {
                $niveles = array_merge($nivelesDefault, $decoded);
            }
        }

        // Raíz del organigrama para el selector de responsables
        // Si el perfil tiene dependency_id, usamos su raíz; si no, la primera raíz disponible
        $orgRaizId = null;
        if ($profile->dependency_id) {
            $orgNodo = \App\Admin\Globales\Organigrama::find($profile->dependency_id);
            if ($orgNodo) {
                $orgRaizId = $orgNodo->isRoot()
                    ? $orgNodo->id
                    : (\App\Admin\Globales\Organigrama::whereAncestorOf($orgNodo)->whereIsRoot()->first()?->id ?? $orgNodo->id);
            }
        }
        if (!$orgRaizId) {
            $orgRaizId = \App\Admin\Globales\Organigrama::whereIsRoot()->value('id');
        }

        // Marco Estratégico General: todos los marcos de los ejes (axi), deduplicados por id
        $marcosGenerales = $profile->children
            ->flatMap(fn($axi) => $axi->marcos)
            ->unique('id')
            ->sortBy('tipo');

        // Marco Estratégico Específico: marcos legales y oferta de servicios del perfil
        $meeMarcos  = \App\Models\Planificacion\MeeMarcoLegal::where('pei_profile_id', $id)
            ->orderBy('orden')->get();
        $meeOfertas = \App\Models\Planificacion\MeeOfertaServicio::where('pei_profile_id', $id)
            ->orderBy('orden')->get();
        // Aspectos FODA Priorizados del Perfil FODA vinculado (Consolidado/Grupal/Individual)
        $fodaPerfilId = $profile->foda_perfil_id;
        $fodaAspectosPriorizados = [
            'fortalezas'    => collect(),
            'debilidades'   => collect(),
            'oportunidades' => collect(),
            'amenazas'      => collect(),
        ];
        $fodaPerfilVinculado = null;

        if ($fodaPerfilId) {
            $fodaPerfilVinculado = \App\Admin\Planificacion\Foda\FodaPerfil::find($fodaPerfilId);
            if ($fodaPerfilVinculado) {
                $perfilIds = [$fodaPerfilId];
                if ($fodaPerfilVinculado->group_id || in_array($fodaPerfilVinculado->type, ['consolidado', 'grupal'])) {
                    $groupId = $fodaPerfilVinculado->group_id;
                    if ($groupId) {
                        $groups = \App\Admin\Globales\Group::descendantsOf($groupId);
                        $groupIds = array_merge([$groupId], $groups->pluck('id')->toArray());
                        $subPerfilIds = \App\Admin\Planificacion\Foda\FodaPerfil::whereIn('group_id', $groupIds)->pluck('id')->toArray();
                        if (!empty($subPerfilIds)) {
                            $perfilIds = array_unique(array_merge($perfilIds, $subPerfilIds));
                        }
                    }
                }
                if ($fodaPerfilVinculado->dependency_id) {
                    $subPerfilDepIds = \App\Admin\Planificacion\Foda\FodaPerfil::where('dependency_id', $fodaPerfilVinculado->dependency_id)->pluck('id')->toArray();
                    if (!empty($subPerfilDepIds)) {
                        $perfilIds = array_unique(array_merge($perfilIds, $subPerfilDepIds));
                    }
                }

                $matrizUmbral = config('foda.umbral_matriz') ?? 0.17;
                $allAnalisis = \App\Admin\Planificacion\Foda\FodaAnalisis::with('aspecto')
                    ->whereIn('perfil_id', $perfilIds)
                    ->select(
                        \DB::raw('planificacion.foda_analisis.*'),
                        \DB::raw('(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz')
                    )
                    ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matrizUmbral")
                    ->whereIn('tipo', ['Debilidad', 'Fortaleza', 'Oportunidad', 'Amenaza'])
                    ->get();

                if ($allAnalisis->isNotEmpty()) {
                    $uniqueAspects = $allAnalisis->unique('aspecto_id')->map(function ($item) use ($allAnalisis) {
                        $maxScoreAspect = $allAnalisis->where('aspecto_id', $item->aspecto_id)->max('matriz');
                        return $allAnalisis->where('aspecto_id', $item->aspecto_id)->firstWhere('matriz', $maxScoreAspect);
                    })->filter();

                    $fodaAspectosPriorizados['fortalezas']    = $uniqueAspects->where('tipo', 'Fortaleza')->sortByDesc('matriz')->values();
                    $fodaAspectosPriorizados['debilidades']   = $uniqueAspects->where('tipo', 'Debilidad')->sortByDesc('matriz')->values();
                    $fodaAspectosPriorizados['oportunidades'] = $uniqueAspects->where('tipo', 'Oportunidad')->sortByDesc('matriz')->values();
                    $fodaAspectosPriorizados['amenazas']      = $uniqueAspects->where('tipo', 'Amenaza')->sortByDesc('matriz')->values();
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['profile' => $profile]);
        } else {
            return view('admin.planificacion.peis.peis.show', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    }

    public function showDetailsTree($idProfile)
    {
        $root = PeiProfile::with([
            'dependency', 'group', 'group.descendants', 'group.descendants.members',
            'strategies',
        ])->findOrFail($idProfile);

        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles', 'strategies'])
            ->descendantsAndSelf($idProfile)->toTree();

        // Etiquetas dinámicas
        $nivelesDefault = ['master' => 'PEI', 'axi' => 'Nivel 1', 'goal' => 'Nivel 2', 'action' => 'Acción'];
        $niveles = $nivelesDefault;
        if ($root->nivel_label) {
            $decoded = json_decode($root->nivel_label, true);
            if (is_array($decoded)) $niveles = array_merge($nivelesDefault, $decoded);
        }

        // Acciones con responsables
        $allActions = PeiProfile::with(['responsibles', 'indicador'])
            ->whereIn('id', $root->descendants()->pluck('id'))
            ->where('level', 'action')
            ->get();

        // Acciones por responsable (para el pie chart)
        $responsiblesActionsCount = [];
        foreach ($allActions as $action) {
            foreach ($action->responsibles as $responsible) {
                $responsiblesActionsCount[$responsible->id] = ($responsiblesActionsCount[$responsible->id] ?? 0) + 1;
            }
        }

        // Métricas generales
        $totalEjes     = $root->descendants()->where('level', 'axi')->count();
        $totalMetas    = $root->descendants()->where('level', 'goal')->count();
        $totalAcciones = $root->descendants()->where('level', 'action')->count();

        // Semáforos
        $verdes    = $allActions->where('semaforo', 'verde')->count();
        $amarillos = $allActions->where('semaforo', 'amarillo')->count();
        $rojos     = $allActions->where('semaforo', 'rojo')->count();
        $sinDatos  = $allActions->whereNotIn('semaforo', ['verde','amarillo','rojo'])->count();

        // Indicadores
        $accionesConIndicador = $allActions->whereNotNull('indicador_id')->count();
        $pctIndicadores = $totalAcciones > 0 ? round(($accionesConIndicador / $totalAcciones) * 100) : 0;

        // Responsables únicos
        $responsablesUnicos = $allActions->flatMap(fn($a) => $a->responsibles)->unique('id')->count();

        // Participantes del grupo
        $totalMembers = 0;
        if ($root->group) {
            foreach ($root->group->descendants as $g) {
                $totalMembers += $g->members->count();
            }
        }

        // Misión / Visión / Valores
        $tieneMision  = !empty(strip_tags($root->mision ?? ''));
        $tieneVision  = !empty(strip_tags($root->vision ?? ''));
        $tieneValores = !empty(strip_tags($root->values ?? ''));

        // Marcos referenciales (de los ejes)
        $axisNodes = $root->descendants()->where('level', 'axi')->pluck('id');
        $totalMarcos = \DB::table('planificacion.pei_profile_marcos')->whereIn('pei_profile_id', $axisNodes)->count();

        // MEE
        $totalMeeMarcos  = \DB::table('planificacion.mee_marco_legal')->where('pei_profile_id', $idProfile)->count();
        $totalMeeOfertas = \DB::table('planificacion.mee_oferta_servicios')->where('pei_profile_id', $idProfile)->count();

        // Ejes con resultado intermedio
        $ejesConRI = $root->descendants()->where('level', 'axi')->whereNotNull('resultado_intermedio')->count();

        // Presupuesto total vinculado (PGN)
        $presupuestoTotal = \DB::table('planificacion.pei_accion_pgn')
            ->whereIn('pei_profile_id', $root->descendants()->pluck('id'))
            ->sum('monto_vinculado_gs');

        // Reportes de avance
        $totalReportes = \DB::table('planificacion.pei_accion_reportes')
            ->whereIn('pei_profile_id', $root->descendants()->pluck('id'))
            ->count();

        return view('admin.planificacion.peis.peis.details', get_defined_vars());
    }

    public function getDetails($idProfile)
    {
        $profile = PeiProfile::findOrFail($idProfile);

        $goals = $profile->where('level', 'goal')->with(['strategies'])->get();

        $responsiblesChecked = [];

        foreach ($profile->responsibles as $responsible) {
            $responsiblesChecked[] = ['id' => $responsible->id, 'text' => $responsible->dependency];
        }

        return response()->json(['profile' => $profile, 'goals' => $goals, 'responsiblesChecked' => $responsiblesChecked]);
    }

    public function exportPdf($idProfile)
    {
        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles', 'strategies'])
            ->descendantsAndSelf($idProfile)->toTree();

        $pdf = Pdf::loadView('admin.planificacion.peis.peis.pdf', compact('profile'))
            ->setPaper('a4', 'landscape')
            ->setOption(['isPhpEnabled' => true, 'isHtml5ParserEnabled' => true]);

        return $pdf->download('consolidado-pei-' . $profile->first()->name . '.pdf');
    }

    public function rankingTalentoHumano(Request $request, $idProfile)
    {
        PeiProfile::findOrFail($idProfile);

        $ranking = \App\Models\Gamification\GamificationPoint::with('user')
            ->where('pei_profile_id', $idProfile)
            ->selectRaw('user_id, SUM(points) as total_points, MAX(action_type) as top_action')
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->get()
            ->map(function ($row, $index) {
                $labels = [
                    'task_completed'     => 'Tarea completada',
                    'manual_admin'       => 'Asignación manual',
                    'chat_context_query' => 'Consulta en chat',
                    'pei_edit'           => 'Edición PEI',
                    'donacion_recibida'  => 'Donación recibida',
                    'foda_analisis'      => 'Análisis FODA',
                ];
                return [
                    'DT_RowIndex'  => $index + 1,
                    'nombre'       => $row->user?->name ?? 'Usuario eliminado',
                    'total_points' => (int) $row->total_points,
                    'top_action'   => $labels[$row->top_action] ?? ucfirst(str_replace('_', ' ', $row->top_action)),
                ];
            });

        return DataTables::of($ranking)->make(true);
    }

    public function proceso($idProfile)
    {
        $profile = PeiProfile::with(['group', 'analysts', 'dependency', 'strategies'])
            ->findOrFail($idProfile);

        // Etiquetas dinámicas
        $nivelesDefault = ['master' => 'PEI', 'axi' => 'Nivel 1', 'goal' => 'Nivel 2', 'action' => 'Acción'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) {
                $niveles = array_merge($nivelesDefault, $decoded);
            }
        }

        return view('admin.planificacion.peis.peis.proceso', compact('profile', 'niveles'));
    }

    public function certificacionMef(Request $request, $idProfile)
    {
        $profile = PeiProfile::with(['group', 'fodaPerfil'])->findOrFail($idProfile);

        $nivelesDefault = ['master' => 'PEI', 'axi' => 'Nivel 1', 'goal' => 'Nivel 2', 'action' => 'Acción'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) $niveles = array_merge($nivelesDefault, $decoded);
        }

        $fodaPerfil   = $profile->fodaPerfil;
        $fodaPerfilId = $fodaPerfil?->id;
        $fodaTipoGrupal = $fodaPerfil && in_array($fodaPerfil->type, ['consolidado', 'grupal']);

        // ── 1. Mapeo de actores ───────────────────────────────────────────
        $totalActores = \DB::table('planificacion.pei_actores')->where('pei_profile_id', $idProfile)->count();

        // ── 2+13. Marco Estratégico General / Vinculación PND-PAM-ODS ────
        $axisIds      = $profile->descendants()->where('level', 'axi')->pluck('id');
        $totalMarcos  = \DB::table('planificacion.pei_profile_marcos')
            ->whereIn('pei_profile_id', $axisIds)
            ->count();

        // ── 3. Marco Estratégico Específico ──────────────────────────────
        $totalMeeMarcos  = \DB::table('planificacion.mee_marco_legal')->where('pei_profile_id', $idProfile)->count();
        $totalMeeOfertas = \DB::table('planificacion.mee_oferta_servicios')->where('pei_profile_id', $idProfile)->count();

        // ── 4. FODA Análisis ──────────────────────────────────────────────
        $fodaAnalizados = 0;
        if ($fodaPerfilId) {
            if ($fodaTipoGrupal) {
                $subgroupIds  = \App\Admin\Globales\Group::where('parent_id', $fodaPerfil->group_id)->pluck('id');
                $subPerfilIds = \App\Admin\Planificacion\Foda\FodaPerfil::whereIn('group_id', $subgroupIds)->pluck('id');
                $fodaAnalizados = \App\Admin\Planificacion\Foda\FodaAnalisis::whereIn('perfil_id', $subPerfilIds)
                    ->whereIn('tipo', ['Fortaleza','Debilidad','Oportunidad','Amenaza'])->count();
            } else {
                $fodaAnalizados = \App\Admin\Planificacion\Foda\FodaAnalisis::where('perfil_id', $fodaPerfilId)
                    ->whereIn('tipo', ['Fortaleza','Debilidad','Oportunidad','Amenaza'])->count();
            }
        }

        // ── 5. FODA Integrado / Cruce ─────────────────────────────────────
        $totalCruces = $fodaPerfilId
            ? \App\Admin\Planificacion\Foda\FodaCruceAmbiente::where('perfil_id', $fodaPerfilId)->count()
            : 0;

        // ── 6-8. Misión, Visión, Valores ──────────────────────────────────
        $tieneMision  = !empty(strip_tags($profile->mision ?? ''));
        $tieneVision  = !empty(strip_tags($profile->vision ?? ''));
        $tieneValores = !empty(strip_tags($profile->values ?? ''));

        // ── 9. Objetivos Estratégicos (axi) ───────────────────────────────
        $totalEjes          = $profile->descendants()->where('level', 'axi')->count();
        $ejesConEstrategia  = $profile->descendants()->where('level', 'axi')->whereHas('strategies')->count();
        $ejesConRI          = $profile->descendants()->where('level', 'axi')->whereNotNull('resultado_intermedio')->count();

        // ── 10. Acciones Estratégicas ─────────────────────────────────────
        $totalAcciones = $profile->descendants()->where('level', 'action')->count();

        // ── 11. Indicadores ───────────────────────────────────────────────
        $accionesConIndicador = $profile->descendants()->where('level', 'action')->whereNotNull('indicador_id')->count();

        // ── 12. Formulación Estratégica Integrada ─────────────────────────
        $accionesConPresupuesto = $profile->descendants()->where('level', 'action')->whereNotNull('presupuesto_asignado')->count();

        // ── Checklist ─────────────────────────────────────────────────────
        $checklist = [
            ['num' => 1,    'label' => 'Mapeo de Actores',                          'ok' => $totalActores > 0,          'valor' => $totalActores . ' actores',          'url' => route('pei-actores.index', $idProfile),                                                                  'pendiente' => false],
            ['num' => '2+13','label' => 'Marco Estratégico General / PND-PAM-ODS',  'ok' => $totalMarcos > 0,           'valor' => $totalMarcos . ' marcos vinculados', 'url' => route('pei-profiles.show', $profile->id),                              'pendiente' => false],
            ['num' => 3,    'label' => 'Marco Estratégico Específico',              'ok' => $totalMeeMarcos > 0,        'valor' => $totalMeeMarcos . ' marcos legales, ' . $totalMeeOfertas . ' servicios', 'url' => route('pei-profiles.show', $profile->id), 'pendiente' => false],
            ['num' => 4,    'label' => 'Análisis Situacional FODA',                 'ok' => $fodaAnalizados > 0,        'valor' => $fodaAnalizados . ' aspectos analizados', 'url' => $fodaPerfilId ? ($fodaTipoGrupal ? route('foda-matriz-groups', $fodaPerfil->group_id) : route('foda-cruce-ambientes', $fodaPerfilId)) : null, 'pendiente' => false],
            ['num' => 5,    'label' => 'FODA Integrado (Cruce de Ambientes)',       'ok' => $totalCruces > 0,           'valor' => $totalCruces . ' estrategias de cruce', 'url' => $fodaPerfilId ? ($fodaTipoGrupal ? route('foda-matriz-groups-crossing', $fodaPerfil->group_id) : route('foda-cruce-ambientes', $fodaPerfilId)) : null, 'pendiente' => false],
            ['num' => 6,    'label' => 'Misión',                                    'ok' => $tieneMision,               'valor' => $tieneMision ? 'Definida' : 'Pendiente',  'url' => route('pei-profiles.show', $profile->id),                          'pendiente' => false],
            ['num' => 7,    'label' => 'Visión',                                    'ok' => $tieneVision,               'valor' => $tieneVision ? 'Definida' : 'Pendiente',  'url' => route('pei-profiles.show', $profile->id),                          'pendiente' => false],
            ['num' => 8,    'label' => 'Valores',                                   'ok' => $tieneValores,              'valor' => $tieneValores ? 'Definidos' : 'Pendiente', 'url' => route('pei-profiles.show', $profile->id),                          'pendiente' => false],
            ['num' => 9,    'label' => 'Objetivos Estratégicos',                    'ok' => $ejesConEstrategia > 0,     'valor' => $ejesConEstrategia . '/' . $totalEjes . ' con estrategia FODA · ' . $ejesConRI . ' con resultado intermedio', 'url' => route('pei-profiles.show', $profile->id), 'pendiente' => false],
            ['num' => 10,   'label' => 'Acciones Estratégicas',                     'ok' => $totalAcciones > 0,         'valor' => $totalAcciones . ' acciones',         'url' => route('pei-profiles.show', $profile->id),                              'pendiente' => false],
            ['num' => 11,   'label' => 'Indicadores',                               'ok' => $accionesConIndicador > 0,  'valor' => $accionesConIndicador . '/' . $totalAcciones . ' acciones con indicador', 'url' => route('pei-profiles.show', $profile->id), 'pendiente' => false],
            ['num' => 12,   'label' => 'Formulación Estratégica Integrada',         'ok' => $accionesConPresupuesto > 0,'valor' => $accionesConPresupuesto . '/' . $totalAcciones . ' acciones con presupuesto', 'url' => route('pei-profiles.show', $profile->id), 'pendiente' => false],
        ];

        $completados = collect($checklist)->where('ok', true)->count();
        $total       = collect($checklist)->count();
        $pct         = round(($completados / $total) * 100);

        if ($request->ajax()) {
            return view('admin.planificacion.peis.peis.partials.certificacion_mef_content', compact(
                'profile', 'niveles', 'checklist', 'completados', 'total', 'pct'
            ));
        }

        return view('admin.planificacion.peis.peis.certificacion-mef', compact(
            'profile', 'niveles', 'checklist', 'completados', 'total', 'pct'
        ));
    }

    public function matrizPdf($idProfile)
    {
        $profile = PeiProfile::with([
            'children.children.children.indicador',
            'children.children.children.responsibles',
        ])->findOrFail($idProfile);

        $nivelesDefault = ['master' => 'PEI', 'axi' => 'Objetivo Estratégico', 'goal' => 'Meta', 'action' => 'Acción Estratégica'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) $niveles = array_merge($nivelesDefault, $decoded);
        }

        $anioInicio = (int) \Carbon\Carbon::parse($profile->year_start)->format('Y');
        $anioFin    = (int) \Carbon\Carbon::parse($profile->year_end)->format('Y');
        $anios      = range($anioInicio, $anioFin);

        $pdf = Pdf::loadView('admin.planificacion.peis.peis.matriz_pdf', compact('profile', 'niveles', 'anios'))
            ->setPaper('a3', 'landscape')
            ->setOption(['isPhpEnabled' => true, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'DejaVu Sans']);

        return $pdf->download('matriz-pei-' . \Illuminate\Support\Str::slug(strip_tags($profile->name)) . '.pdf');
    }

    public function matriz(Request $request, $idProfile)
    {
        $profile = PeiProfile::with([
            'children.children.children.indicador',
            'children.children.children.responsibles',
        ])->findOrFail($idProfile);

        $nivelesDefault = ['master' => 'PEI', 'axi' => 'Objetivo Estratégico', 'goal' => 'Meta', 'action' => 'Acción Estratégica'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) $niveles = array_merge($nivelesDefault, $decoded);
        }

        $anioInicio = (int) \Carbon\Carbon::parse($profile->year_start)->format('Y');
        $anioFin    = (int) \Carbon\Carbon::parse($profile->year_end)->format('Y');
        $anios      = range($anioInicio, $anioFin);

        return view('admin.planificacion.peis.peis.matriz', compact('profile', 'niveles', 'anios'));
    }

    public function accordion(Request $request, string $profileId)
    {
        $profile = \App\Admin\Planificacion\Pei\PeiProfile::with([
            'children.marcos',
            'children.strategies',
            'children.edits.user',
            'children.children.edits.user',
            'children.children.children.indicador',
            'children.children.children.responsibles',
            'children.children.children.activityTasks',
            'children.children.children.edits.user',
        ])->findOrFail($profileId);

        $nivelesDefault = ['master'=>'PEI','axi'=>'Nivel 1','goal'=>'Nivel 2','action'=>'Acción'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) $niveles = array_merge($nivelesDefault, $decoded);
        }

        return view('admin.planificacion.peis.peis.accordion', compact('profile', 'niveles'));
    }

    public function dashboard($idProfile)
    {
        $profile = PeiProfile::with(['group', 'analysts', 'dependency'])->findOrFail($idProfile);

        // Análisis FODA con IEA del grupo vinculado al PEI
        $analisisFoda = \App\Admin\Planificacion\Foda\FodaAnalisis::with('aspecto')
            ->whereNotNull('iea_valor')
            ->whereHas('perfil', fn($q) => $q->where('group_id', $profile->group_id))
            ->get(['id', 'aspecto_id', 'tipo', 'iea_valor', 'iea_clasificacion']);

        $perfilFodaId = \App\Admin\Planificacion\Foda\FodaPerfil::where('group_id', $profile->group_id)
            ->value('id');

        return view('admin.planificacion.peis.peis.dashboard', compact('profile', 'analisisFoda', 'perfilFodaId'));
    }

    public function reordenar(Request $request, $id)
    {
        $node = PeiProfile::findOrFail($id);
        $direction = $request->input('direction'); // 'up' | 'down'

        $siblings = PeiProfile::where('parent_id', $node->parent_id)
            ->orderBy('order_item')
            ->get();

        $currentIndex = $siblings->search(fn($s) => $s->id === $node->id);

        $swapIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;

        if ($swapIndex < 0 || $swapIndex >= $siblings->count()) {
            return response()->json(['error' => 'No se puede mover en esa dirección.'], 422);
        }

        $swap = $siblings[$swapIndex];

        // Intercambiar order_item
        $tempOrder = $node->order_item;
        $node->order_item = $swap->order_item ?? $swapIndex;
        $swap->order_item = $tempOrder ?? $currentIndex;

        // Si ambos tienen el mismo order_item, forzar diferencia
        if ($node->order_item === $swap->order_item) {
            $node->order_item = $swapIndex;
            $swap->order_item = $currentIndex;
        }

        $node->save();
        $swap->save();

        return response()->json(['success' => true]);
    }

    public function getTreeDraggable($idProfile)
    {
        $master = PeiProfile::findOrFail($idProfile);
        $treeNode = PeiProfile::defaultOrder()
            ->with(['children', 'iniciativas'])
            ->descendantsAndSelf($idProfile)
            ->toTree()
            ->first();

        $treeArray = $this->buildDraggableTreeArray($treeNode);

        return view('admin.planificacion.peis.peis.partials.modal_reordenar_tree', [
            'master' => $master,
            'treeArray' => $treeArray
        ]);
    }

    private function buildDraggableTreeArray($node)
    {
        $hijos = [];

        // 1. Agregar hijos PeiProfile
        if ($node->children && $node->children->isNotEmpty()) {
            foreach ($node->children as $child) {
                $hijos[] = $this->buildDraggableTreeArray($child);
            }
        }

        // 2. Si el nodo es una Acción Estratégica (action / accion_estrategica), incluir sus Acciones Operativas (iniciativas)
        if (in_array($node->level, ['action', 'accion_estrategica']) && $node->relationLoaded('iniciativas') && $node->iniciativas->isNotEmpty()) {
            foreach ($node->iniciativas->sortBy('orden') as $ini) {
                $hijos[] = [
                    'id'        => 'ini_' . $ini->id,
                    'db_id'     => $ini->id,
                    'type'      => 'iniciativa',
                    'level'     => 'accion_operativa',
                    'name'      => ($ini->codigo ? '[' . $ini->codigo . '] ' : '') . trim(strip_tags($ini->accion)),
                    'parent_id' => $node->id,
                    'children'  => [],
                ];
            }
        }

        $levelKey = $node->level;
        if ($levelKey === 'axi') $levelKey = 'eje';
        if ($levelKey === 'goal') $levelKey = 'objetivo';
        if ($levelKey === 'action') $levelKey = 'accion_estrategica';

        return [
            'id'        => $node->id,
            'db_id'     => $node->id,
            'type'      => 'profile',
            'level'     => $levelKey,
            'name'      => trim(strip_tags($node->name)),
            'parent_id' => $node->parent_id,
            'children'  => $hijos,
        ];
    }

    public function reordenarTree(Request $request, $idProfile)
    {
        $master = PeiProfile::findOrFail($idProfile);
        $items  = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['error' => 'No se enviaron elementos para reordenar.'], 422);
        }

        \DB::beginTransaction();
        try {
            foreach ($items as $item) {
                if (empty($item['id'])) continue;

                $type = $item['type'] ?? 'profile';
                $dbId = $item['db_id'] ?? $item['id'];
                $parentId = $item['parent_id'] ?? null;
                $orderItem = (int)($item['order_item'] ?? 0);

                if ($type === 'iniciativa' || str_starts_with((string)$item['id'], 'ini_')) {
                    $cleanId = str_replace('ini_', '', $dbId);
                    $iniciativa = \App\Models\PlanMaestro\PlanAccion::find($cleanId);
                    if ($iniciativa) {
                        $dirty = false;
                        if ($parentId && $iniciativa->pei_profile_id !== $parentId) {
                            $iniciativa->pei_profile_id = $parentId;
                            $dirty = true;
                        }
                        if ((int)$iniciativa->orden !== $orderItem) {
                            $iniciativa->orden = $orderItem;
                            $dirty = true;
                        }
                        if ($dirty) {
                            $iniciativa->save();
                        }
                    }
                } else {
                    $node = PeiProfile::find($dbId);
                    if ($node) {
                        $dirty = false;
                        if ($parentId && $node->parent_id !== $parentId) {
                            $node->parent_id = $parentId;
                            $dirty = true;
                        }
                        if ((int)$node->order_item !== $orderItem) {
                            $node->order_item = $orderItem;
                            $dirty = true;
                        }
                        if ($dirty) {
                            $node->save();
                        }
                    }
                }
            }

            \DB::commit();

            try {
                PeiProfile::fixTree();
            } catch (\Throwable $t) {
                // Ignore nested set fix warning
            }

            return response()->json([
                'success' => true,
                'message' => '¡Estructura PEI y Acciones Operativas reordenadas exitosamente!'
            ]);
        } catch (\Throwable $e) {
            \DB::rollBack();
            return response()->json([
                'error' => 'Error al guardar reordenamiento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $profile = PeiProfile::find($id)->delete();

        return response()->json([$profile]);
    }

    public function getSemaforo(Request $request, $idProfile)
    {
        $master = PeiProfile::findOrFail($idProfile);

        // Incluir acciones con tipo_indicador legacy O con indicador_id vinculado
        $actions = PeiProfile::with('indicador')
            ->whereIn('id', $master->descendants()->pluck('id'))
            ->where('level', 'action')
            ->where(function($q) {
                $q->whereNotNull('tipo_indicador')
                  ->orWhereNotNull('indicador_id');
            })
            ->get(['id', 'name', 'tipo_indicador', 'progress', 'target', 'semaforo', 'numerator', 'denominator', 'indicador_id']);

        $resultado = $actions->map(function ($action) {
            // Si tiene indicador vinculado, calcular desde ahí
            if ($action->indicador && $action->semaforo) {
                $pct = ($action->denominator && $action->denominator > 0)
                    ? round(($action->numerator / $action->denominator) * 100, 1)
                    : null;
                return [
                    'id'             => $action->id,
                    'name'           => strip_tags($action->name),
                    'tipo_indicador' => $action->indicador->sentido === 'descendente' ? 'lag' : 'lead',
                    'semaforo'       => $action->semaforo,
                    'avance_pct'     => $pct,
                ];
            }

            // Flujo legacy
            $action->calcularSemaforo();
            return [
                'id'             => $action->id,
                'name'           => strip_tags($action->name),
                'tipo_indicador' => $action->tipo_indicador,
                'semaforo'       => $action->semaforo,
                'avance_pct'     => $action->target > 0
                    ? round(($action->progress / $action->target) * 100, 1)
                    : null,
            ];
        });

        $resumen = [
            'verde'    => $resultado->where('semaforo', 'verde')->count(),
            'amarillo' => $resultado->where('semaforo', 'amarillo')->count(),
            'rojo'     => $resultado->where('semaforo', 'rojo')->count(),
        ];

        return response()->json([
            'acciones' => $resultado->values(),
            'resumen'  => $resumen,
        ]);
    }

    public function syncRaci(Request $request, $idProfile)
    {
        $request->validate([
            'responsibles'         => 'required|array|min:1',
            'responsibles.*.id'    => 'required|integer|exists:organigramas,id',
            'responsibles.*.rol'   => 'required|in:R,A,C,I',
        ]);

        $accountables = collect($request->responsibles)->where('rol', 'A');

        if ($accountables->count() !== 1) {
            return response()->json([
                'error' => 'Debe existir exactamente un Accountable (A) por estrategia.',
            ], 422);
        }

        $profile = PeiProfile::findOrFail($idProfile);

        $syncData = collect($request->responsibles)
            ->keyBy('id')
            ->map(fn($r) => ['rol' => $r['rol']])
            ->toArray();

        $profile->responsibles()->sync($syncData);

        return response()->json([
            'success'      => 'RACI sincronizado correctamente.',
            'responsibles' => $profile->responsibles()->withPivot('rol')->get(['organigramas.id', 'organigramas.dependency']),
        ]);
    }

    public function getAlertasPresupuestarias(Request $request, $idProfile)
    {
        $master = PeiProfile::findOrFail($idProfile);

        $acciones = PeiProfile::whereIn('id', $master->descendants()->pluck('id'))
            ->where('level', 'action')
            ->whereNotNull('presupuesto_asignado')
            ->whereNotNull('presupuesto_ejecutado')
            ->get(['id', 'name', 'progress', 'target', 'presupuesto_asignado', 'presupuesto_ejecutado']);

        $alertas = $acciones->filter(fn($a) => $a->alertaPresupuestaria() === 'subejecucion')
            ->map(fn($a) => [
                'id'              => $a->id,
                'name'            => strip_tags($a->name),
                'pct_meta'        => $a->target > 0 ? round(($a->progress / $a->target) * 100, 1) : 0,
                'pct_presupuesto' => $a->presupuesto_asignado > 0
                    ? round(($a->presupuesto_ejecutado / $a->presupuesto_asignado) * 100, 1)
                    : 0,
                'alerta' => 'subejecucion',
            ])->values();

        return response()->json([
            'total_alertas' => $alertas->count(),
            'acciones'      => $alertas,
        ]);
    }

    /**
     * Alterna el estado activo/inactivo (is_active) de un Plan Estratégico.
     */
    public function toggleStatus($id)
    {
        $pei = PeiProfile::findOrFail($id);
        $pei->is_active = !$pei->is_active;
        $pei->save();

        return response()->json([
            'success'   => true,
            'is_active' => $pei->is_active,
            'status'    => $pei->is_active ? 1 : 0,
            'message'   => $pei->is_active
                ? 'El Plan Estratégico "' . strip_tags($pei->name) . '" fue ACTIVADO.'
                : 'El Plan Estratégico "' . strip_tags($pei->name) . '" fue INACTIVADO (oculto).',
        ]);
    }

    /**
     * Actualiza las variables globales (parameters) del PEI e impacta en las Actas vinculadas.
     */
    public function updateParameters(Request $request, $id)
    {
        $profile = PeiProfile::findOrFail($id);
        
        $params = json_decode($profile->parameters, true) ?? [];
        
        if ($request->has('acta_logo_url')) {
            $params['acta_logo_url'] = $request->input('acta_logo_url');
        }
        if ($request->has('acta_institucion')) {
            $params['acta_institucion'] = $request->input('acta_institucion');
        }
        if ($request->has('acta_dependencia')) {
            $params['acta_dependencia'] = $request->input('acta_dependencia');
        }
        
        $profile->parameters = json_encode($params);
        $profile->save();

        // ── Sincronizar masivamente con todas las Actas MECIP del plan ──
        try {
            $allProfileIds = PeiProfile::whereIn('id', $profile->descendantsAndSelf($profile->id)->pluck('id'))->pluck('id')->toArray();
            if (empty($allProfileIds)) $allProfileIds = [$profile->id];

            $activityIds = \App\Admin\Globales\Activity::whereIn('pei_profile_id', $allProfileIds)->pluck('id')->toArray();
            if (!empty($activityIds)) {
                $taskIds = \App\Admin\Globales\ActivityTask::whereIn('activity_id', $activityIds)->pluck('id')->toArray();
                if (!empty($taskIds)) {
                    $updateActaData = [];
                    if (!empty($params['acta_institucion'])) {
                        $updateActaData['institucion'] = $params['acta_institucion'];
                    }
                    if (!empty($params['acta_dependencia'])) {
                        $updateActaData['dependencia'] = $params['acta_dependencia'];
                    }
                    if (isset($params['acta_logo_url'])) {
                        $updateActaData['logo_url'] = $params['acta_logo_url'];
                    }

                    if (!empty($updateActaData)) {
                        \App\Admin\Globales\ActivityTaskActa::whereIn('activity_task_id', $taskIds)->update($updateActaData);
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Error al sincronizar actas MECIP tras actualizar variables: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Variables del plan y Actas de Reunión actualizadas correctamente.');
    }

    // ── Vista de Asesor Externo (Admin / Validaciones) ──────────────────────
    public function vistaAsesor($idProfile)
    {
        $profile = PeiProfile::where('id', $idProfile)
            ->whereNull('parent_id')
            ->where('level', 'master')
            ->firstOrFail();

        $descendants = $profile->descendants()->get();
        $treeNodes   = $descendants->toTree();

        // Mapear iniciativas (Acciones Operativas)
        $nodeIds = $descendants->pluck('id')->push($profile->id)->map(fn($v) => (string)$v)->toArray();
        $stringIds = array_values(array_filter($nodeIds, fn($id) => !is_numeric($id)));
        $numericIds = array_values(array_filter($nodeIds, 'is_numeric'));

        $query = \App\Models\PlanMaestro\PlanAccion::query();
        if (!empty($stringIds)) {
            $query->whereIn('pei_profile_id', $stringIds);
        }
        if (!empty($numericIds)) {
            $query->orWhereIn('plan_id', $numericIds)->orWhereIn('eje_id', $numericIds);
        }
        $iniciativas = $query->orderBy('orden')->get();

        return view('admin.planificacion.peis.peis.vista_asesor', compact('profile', 'treeNodes', 'descendants', 'iniciativas'));
    }

    public function guardarComentarioAsesor(Request $request, $idProfile)
    {
        $request->validate([
            'node_id' => 'required',
            'comentario' => 'nullable|string',
            'type' => 'nullable|string'
        ]);

        $nodeId = $request->input('node_id');
        $comentario = trim($request->input('comentario') ?? '');
        $type = $request->input('type', 'node');

        if ($type === 'iniciativa' || is_numeric($nodeId)) {
            $ini = \App\Models\PlanMaestro\PlanAccion::find($nodeId);
            if ($ini) {
                $ini->comentario_asesor = $comentario;
                $ini->save();
                return response()->json(['success' => true, 'message' => 'Comentario del Asesor guardado en la Acción Operativa.']);
            }
        }

        $node = PeiProfile::find($nodeId);
        if ($node) {
            $node->comentario_asesor = $comentario;
            $node->save();
            return response()->json(['success' => true, 'message' => 'Comentario del Asesor guardado en el elemento.']);
        }

        return response()->json(['success' => false, 'message' => 'No se encontró el elemento a comentar.'], 404);
    }

    public function generarTokenAsesor($idProfile)
    {
        $profile = PeiProfile::where('id', $idProfile)->whereNull('parent_id')->firstOrFail();
        $token = $profile->generateAsesorToken();
        return redirect()->back()->with('success', 'Enlace Seguro para Asesor Externo generado exitosamente.');
    }

    public function revocarTokenAsesor($idProfile)
    {
        $profile = PeiProfile::where('id', $idProfile)->whereNull('parent_id')->firstOrFail();
        $profile->revokeAsesorToken();
        return redirect()->back()->with('success', 'Enlace Seguro para Asesor Externo revocado.');
    }

    // ── Basurero de Elementos (SoftDeletes / Recuperación) ───────────────────
    public function basureroList($idProfile)
    {
        $profile = PeiProfile::where('id', $idProfile)->firstOrFail();

        // 1. Nodos soft-deleted en la jerarquía PEI
        $trashedNodes = PeiProfile::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get(['id', 'name', 'level', 'type', 'deleted_at']);

        // 2. Acciones Operativas soft-deleted
        $trashedInis = \App\Models\PlanMaestro\PlanAccion::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get(['id', 'codigo', 'accion', 'estado', 'deleted_at']);

        return response()->json([
            'ok' => true,
            'trashed_nodes' => $trashedNodes,
            'trashed_inis'  => $trashedInis,
            'total'         => $trashedNodes->count() + $trashedInis->count(),
        ]);
    }

    public function restaurarNodo(Request $request, $idProfile, $nodeId)
    {
        $node = PeiProfile::onlyTrashed()->where('id', $nodeId)->first();
        if ($node) {
            $node->restore();
            return response()->json(['ok' => true, 'message' => 'Elemento del PEI restaurado con éxito.']);
        }
        return response()->json(['ok' => false, 'message' => 'No se encontró el elemento eliminado.'], 404);
    }

    public function restaurarIniciativa(Request $request, $idProfile, $iniId)
    {
        $ini = \App\Models\PlanMaestro\PlanAccion::onlyTrashed()->where('id', $iniId)->first();
        if ($ini) {
            $ini->restore();
            return response()->json(['ok' => true, 'message' => 'Acción Operativa restaurada con éxito.']);
        }
        return response()->json(['ok' => false, 'message' => 'No se encontró la Acción Operativa eliminada.'], 404);
    }
}