<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Admin\Planificacion\Foda\FodaGroup;
use Kalnoy\Nestedset\NodeTrait;
use Yajra\DataTables\DataTables;
use App\Models\User;


class FodaGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FodaGroup::where('parent_id', null)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editGroup"><i class="far fa-edit"></i></a>';

                    $btn .= ' <a href="' . route('foda-groups.show', $row->id) . '" class="btn btn-success btn-circle"><i class="fa fa-users" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteGroup"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.fodas.groups.index', get_defined_vars())
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

        $group = FodaGroup::updateOrCreate(
            ['id' => $request->group_id],
            [
                'name' => $request->name,
            ]
        );
        if ($request->parent_id) {
            $node = FodaGroup::find($request->parent_id);
            $node->appendNode($group);
        }

        //Insert into pivot table 
        $members = $request->user_id;
        if ($members) {
            $group->members()->sync($members);
        } else {
        }


        if ($group->parent_id == null) {
            return redirect()->route('foda-groups.index');
        } else {
            return redirect()->route('foda-groups.show', 12);
        }
    }

    public function edit($id)
    {
        $profile = FodaGroup::find($id);

        return response()->json(['profile' => $profile]);
    }

    public function show(Request $request, $id)
    {
        $group = FodaGroup::findOrFail($id);

        if ($request->ajax()) {
            $data = FodaGroup::descendantsOf($id);
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editGroup"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteSubGroup"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })

                ->addColumn('members', function (FodaGroup $group) {
                    $memberNames = $group->members->pluck('name')->implode(', ');
                    return $memberNames;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.fodas.groups.show', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function destroy(Request $request, $id)
    {
        $profile = FodaGroup::find($id)->delete();

        return response()->json([$profile]);
    }
}
