<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

use Yajra\DataTables\DataTables;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Organigrama;

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

                    $btn .= ' <a href="' . route('pei-profiles.show', $row->id) . '" data-type="' . $row->type . '" class="btn btn-success btn-circle showType"><i class="fas fa-tasks"></i></a>';

                    $btn .= ' <a href="' . route('pei-profiles.details', $row->id) . '" class="btn btn-info btn-circle showTree"><i class="fa fa-tree" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    // } elseif (auth()->user()->hasRole('Participantes')) {
                    // $btn = ' <a href="' . route('pei-profiles.details', $row->id) . '" class="btn btn-info btn-circle showTree"><i class="fa fa-tree" aria-hidden="true"></i></a>';
                    // }

                    return $btn;
                })
                ->addColumn('group', function (PeiProfile $profile) {
                    return $profile->group->name;
                })

                ->addColumn('period', function (PeiProfile $profile) {
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

    public function showDetailsTree($idProfile)
    {
        $idProfile = $idProfile;
        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles', 'strategies'])->descendantsAndSelf($idProfile)->toTree();

        $responsiblesActionsCount = [];

        foreach ($profile->first()->children as $axi) {
            foreach ($axi->children as $goal) {
                foreach ($goal->children as $action) {
                    $responsibles = $action->responsibles;

                    foreach ($responsibles as $responsible) {
                        $responsiblesId = $responsible->id;
                        // Si tenemos valor, sumamos 1, si no tenemos valor, valor es 0
                        $responsiblesActionsCount[$responsiblesId] = ($responsiblesActionsCount[$responsiblesId] ?? 0) + 1;
                    }
                }
            }
        }

        foreach ($responsiblesActionsCount as $responsibleId => $actionsCount) {
            $responsible = Organigrama::find($responsibleId);
            ['dependency' => $responsible->dependency, 'actionsCount' => $actionsCount];
        }

        

        return view('admin.planificacion.peis.peis.details_tree', get_defined_vars());
    }

    public function showAxisList($idProfile)
    {

        $profile = PeiProfile::findOrFail($idProfile);

        $axis = $profile->where('level', 'axi')->get();

        return response()->json(['axis' => $axis]);
    }

    public function showGoalsList($idProfile)
    {

        $profile = PeiProfile::findOrFail($idProfile);

        $goals = $profile->where('level', 'goal')->with(['strategies'])->get();

        return response()->json(['goals' => $goals]);
    }

    public function showActionsList($idProfile)
    {

        $profile = PeiProfile::findOrFail($idProfile);

        $actions = $profile->where('level', 'action')->with(['responsibles'])->get();

        return response()->json(['actions' => $actions]);
    }


    public function showMembersList($idProfile)
    {

        $profile = PeiProfile::findOrFail($idProfile);

        $members = [];

        foreach($profile->group->descendants as $group){
            foreach($group->members as $member){
                $members[] = ['name'=>$member->name, 'email'=>$member->email];  
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

        $profile = PeiProfile::with('group')->whereIsRoot()->where('level', 'master')->where('type', 'group')->get();

        return response()->json($profile);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                ],
                [
                    'name.required'     => 'Campo nombre es requerido',
                ]
            );
        };

        $user = Auth::user();

        // If exist value in $request->profile_id save value, else create uuid
        if ($request->profile_id) {
            $profileId =  $request->profile_id;
        } else {
            $profileId = Str::uuid();
        }

        if ($request->type == 'corporative') {
            $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles'])
                ->updateOrCreate(
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
                        'denominator' => $request->denominator,
                        'goal' => $request->goal,
                        'progress' => $request->progress,
                        'group_id' => $request->group_root_id,
                        'dependency_id' => $request->dependency_id,
                        'action' => $request->action,
                        'indicator' => $request->indicator,
                        'baseline' => $request->baseline,
                        'target' => $request->target,
                        'user_id' => $user->id,
                        'order_item' => $request->order_item
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
                    'denominator' => $request->denominator,
                    'goal' => $request->goal,
                    'progress' => $request->progress,
                    'group_id' => $request->group_id,
                    'dependency_id' => $request->dependency_id,
                    'action' => $request->action,
                    'indicator' => $request->indicator,
                    'baseline' => $request->baseline,
                    'target' => $request->target,
                    'user_id' => $user->id,
                    'order_item' => $request->order_item
                ]
            );
        }

        if ($request->parent_id) {
            $node = PeiProfile::find($request->parent_id);
            $node->appendNode($profile);
        }

        $analysts = $request->analyst_id;
        $profile->analysts()->sync($analysts);

        $strategies = $request->strategy_id;
        $profile->strategies()->sync($strategies);

        $responsibles = $request->responsible_id;
        $profile->responsibles()->sync($responsibles);

        $strategiesChecked = [];
        foreach ($profile->strategies as $strategy) {
            $strategiesChecked[] = ['id' => $strategy->id, 'text' => $strategy->estrategia];
        }

        $responsiblesChecked = [];
        foreach ($profile->responsibles as $responsible) {
            $responsiblesChecked[] = ['id' => $responsible->id, 'name' => $responsible->dependency];
        }

        if ($profile->parent_id == null) {
            return response()->json(['success' => 'Creado creado exitosamente', 'profile' => $profile, 'strategiesChecked' => $strategiesChecked, 'responsiblesChecked' => $responsiblesChecked]);
        } else {
            return response()->json(['success' => 'Actuazalido con éxito', 'profile' => $profile, 'parent_id' => $request->parent_id, 'strategiesChecked' => $strategiesChecked, 'responsiblesChecked' => $responsiblesChecked]);
        }
    }

    public function edit($id)
    {
        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles'])->find($id);

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
            'profile' => $profile,
            'analystsChecked' => $analystsChecked,
            'strategiesChecked' => $strategiesChecked,
            'responsiblesChecked' => $responsiblesChecked
        ]);
    }

    public function show(Request $request, $id)
    {
        $profile = PeiProfile::with(['analysts', 'descendants', 'dependency', 'group', 'responsibles', 'strategies'])
            ->findOrFail($id);

        $type = $profile->type;

        if ($request->ajax()) {
            $responseData = [
                'profile' => $profile
            ];
            return response()->json($responseData);
        } else {
            return view('admin.planificacion.peis.peis.show', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    }

    public function destroy(Request $request, $id)
    {
        $profile = PeiProfile::find($id)->delete();

        return response()->json([$profile]);
    }
}
