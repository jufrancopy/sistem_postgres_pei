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


class PeiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PeiProfile::where('parent_id', null)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // if (auth()->user()->hasRole('Administrador')) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-type="' . $row->type . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfile"><i class="far fa-edit"></i></a>';

                    $btn .= ' <a href="' . route('pei-profiles.proceso', $row->id) . '" class="btn btn-success btn-circle" title="Proceso"><i class="fa fa-tasks"></i></a>';

                    $btn .= ' <a href="' . route('pei-profiles.details', $row->id) . '" class="btn btn-info btn-circle showTree"><i class="fa fa-tree" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    // } elseif (auth()->user()->hasRole('Participantes')) {
                    // $btn = ' <a href="' . route('pei-profiles.details', $row->id) . '" class="btn btn-info btn-circle showTree"><i class="fa fa-tree" aria-hidden="true"></i></a>';
                    // }

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

                ->rawColumns(['action'])
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

        // Generar UUID si no se proporciona profile_id
        $profileId = $request->profile_id ?? Str::uuid();

        // Lógica de almacenamiento basada en el tipo de perfil
        if ($request->type == 'corporative') {
            $profile = PeiProfile::updateOrCreate(
                ['id' => $profileId],
                [
                    'name' => $request->name,
                    'year_start' => $request->year_start,
                    'year_end' => $request->year_end,
                    'type' => $request->type,
                    'level' => $request->level,
                    'mision' => $request->mision,
                    'vision' => $request->vision,
                    'values' => $request->values,
                    'period' => $request->period,
                    'numerator' => $request->numerator,
                    'operator' => $request->operator,
                    'denominator' => $denominator,
                    'goal' => $request->goal,
                    'progress' => $request->progress,
                    'group_id' => $request->group_id,
                    'dependency_id' => $request->dependency_id,
                    'action' => $request->action,
                    'indicator' => $request->indicator,
                    'baseline' => $request->baseline,
                    'target' => $request->target,
                    'user_id' => $user->id,
                    'order_item' => $request->order_item,
                    'report_type' => $request->report_type,
                    'parameters' => $parametersJson,
                    'nivel_label' => $request->nivel_label ?: null,
                    'foda_perfil_id' => $request->foda_perfil_id ?: null,
                    'bsc_perspectiva' => $request->bsc_perspectiva ?: null,
                    'indicador_id'    => $request->indicador_id ?: null,
                    'resultado_intermedio' => $request->resultado_intermedio ?: null,
                    'ri_presupuestario'    => $request->ri_presupuestario ?: null,
                    'ri_programa'          => $request->ri_programa ?: null,
                    'ri_recursos_gs'       => $request->ri_recursos_gs ?: null,
                    'ri_metas'             => json_encode($request->input('ri_metas', [])),
                ]
            );
        } else {
            $profile = PeiProfile::updateOrCreate(
                ['id' => $profileId],
                [
                    'name' => $request->name,
                    'type' => $request->type,
                    'year_start' => $request->year_start,
                    'year_end' => $request->year_end,
                    'level' => $request->level,
                    'mision' => $request->mision,
                    'vision' => $request->vision,
                    'values' => $request->values,
                    'period' => $request->period,
                    'numerator' => $request->numerator,
                    'operator' => $request->operator,
                    'denominator' => $denominator,
                    'goal' => $request->goal,
                    'progress' => $request->progress,
                    'group_id' => $request->group_id,
                    'dependency_id' => $request->dependency_id,
                    'action' => $request->action,
                    'indicator' => $request->indicator,
                    'baseline' => $request->baseline,
                    'target' => $request->target,
                    'user_id' => $user->id,
                    'order_item' => $request->order_item,
                    'report_type' => $request->report_type,
                    'parameters' => $parametersJson,
                    'nivel_label' => $request->nivel_label ?: null,
                    'foda_perfil_id' => $request->foda_perfil_id ?: null,
                    'bsc_perspectiva' => $request->bsc_perspectiva ?: null,
                    'indicador_id'    => $request->indicador_id ?: null,
                    'resultado_intermedio' => $request->resultado_intermedio ?: null,
                    'ri_presupuestario'    => $request->ri_presupuestario ?: null,
                    'ri_programa'          => $request->ri_programa ?: null,
                    'ri_recursos_gs'       => $request->ri_recursos_gs ?: null,
                    'ri_metas'             => json_encode($request->input('ri_metas', [])),
                ]
            );
        }

        // Manejo de relaciones
        $profile->analysts()->sync($request->analyst_id);
        $profile->strategies()->sync($request->strategy_id);
        $profile->responsibles()->sync($request->responsible_id);

        // Si se envía foda_perfil_id, guardarlo en el PEI raíz
        if ($request->has('foda_perfil_id')) {
            $peiRaiz = $profile->level === 'master'
                ? $profile
                : PeiProfile::where('_lft', '<=', $profile->_lft)
                    ->where('_rgt', '>=', $profile->_rgt)
                    ->where('level', 'master')
                    ->first();
            if ($peiRaiz) {
                $peiRaiz->foda_perfil_id = $request->foda_perfil_id ?: null;
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

    public function edit($id)
    {
        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles'])->find($id);

        // Cargar el grupo padre (Evento) si existe
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
        $profile = PeiProfile::with([
                'analysts', 'descendants', 'dependency', 'group', 'responsibles', 'strategies',
                'children.marcos', 'children.strategies',
                'children.children.children.indicador', // acciones (level=action) con su indicador
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

        if ($request->ajax()) {
            return response()->json(['profile' => $profile]);
        } else {
            return view('admin.planificacion.peis.peis.show', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    }

    public function showDetailsTree($idProfile)
    {
        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles', 'strategies'])->descendantsAndSelf($idProfile)->toTree();
        $responsiblesActionsCount = [];

        $allDescendants = PeiProfile::with('responsibles')
            ->whereIn('id', PeiProfile::findOrFail($idProfile)->descendants()->pluck('id'))
            ->where('level', 'action')
            ->get();

        foreach ($allDescendants as $action) {
            foreach ($action->responsibles as $responsible) {
                $responsiblesId = $responsible->id;
                $responsiblesActionsCount[$responsiblesId] = ($responsiblesActionsCount[$responsiblesId] ?? 0) + 1;
            }
        }

        foreach ($responsiblesActionsCount as $responsibleId => $actionsCount) {
            $responsible = Organigrama::find($responsibleId);
            ['dependency' => $responsible->dependency, 'actionsCount' => $actionsCount];
        }

        // Etiquetas dinámicas
        $root = PeiProfile::findOrFail($idProfile);
        $nivelesDefault = ['master' => 'PEI', 'axi' => 'Nivel 1', 'goal' => 'Nivel 2', 'action' => 'Acción'];
        $niveles = $nivelesDefault;
        if ($root->nivel_label) {
            $decoded = json_decode($root->nivel_label, true);
            if (is_array($decoded)) {
                $niveles = array_merge($nivelesDefault, $decoded);
            }
        }

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
}