<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use App\Admin\Planificacion\Pei\Pei;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Kalnoy\Nestedset\NodeTrait;
use Yajra\DataTables\DataTables;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;


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

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfile"><i class="far fa-edit"></i></a>';

                    $btn .= ' <a href="' . route('globales.groups.show', $row->id) . '" class="btn btn-success btn-circle"><i class="fas fa-tasks"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->addColumn('group', function (PeiProfile $profile) {
                    return $profile->group->name;
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


    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                ],
                [
                    'name.required'     => 'Campor Nombre es requerido',
                ]
            );
        };

        $profile = PeiProfile::updateOrCreate(
            ['id' => $request->group_id],
            [
                'name' => $request->name,
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
                'group_id' => $request->group_id
            ]
        );

        if ($request->parent_id) {
            $node = PeiProfile::find($request->parent_id);
            $node->appendNode($profile);
        }

        $analysts = $request->analyst_id;

        $profile->analysts()->sync($analysts);

        if ($profile->parent_id == null) {
            return response()->json(['success' => 'Perfil creado exitosamente']);
        } else {
            return response()->json(['success' => 'Elemento creado con éxito', 'parent_id' => $request->parent_id]);
        }
    }

    public function edit($id)
    {
        $profile = PeiProfile::with('analysts')->find($id);

        $analystsChecked = [];

        foreach ($profile->analysts as $analyst) {
            $analystsChecked[] = ['id' => $analyst->id, 'text' => $analyst->name];
        }

        return response()->json(['profile' => $profile, 'analystsChecked' => $analystsChecked]);
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
        $profile = Group::find($id)->delete();

        return response()->json([$profile]);
    }
}
