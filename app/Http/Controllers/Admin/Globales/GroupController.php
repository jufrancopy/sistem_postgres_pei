<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Admin\Globales\Group;;

use Kalnoy\Nestedset\NodeTrait;
use Yajra\DataTables\DataTables;
use App\Models\User;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::where('parent_id', null)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editGroup"><i class="far fa-edit"></i></a>';

                    $btn .= ' <a href="' . route('globales.groups.show', $row->id) . '" class="btn btn-success btn-circle"><i class="fa fa-users" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteGroup"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->addColumn('categories', function (Group $group) {
                    $membersNames = $group->members->pluck('name')->implode(', '); // Cambia 'nombre' al nombre del campo de categoría en tu modelo
                    return $membersNames;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.globales.groups.index', get_defined_vars())
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

        $group = Group::updateOrCreate(
            ['id' => $request->group_id],
            [
                'name' => $request->name,
            ]
        );

        if ($request->parent_id) {
            $node = Group::find($request->parent_id);
            $node->appendNode($group);
        }

        $members = $request->user_id;

        $group->members()->sync($members);

        if ($group->parent_id == null) {
            return response()->json(['success' => 'Grupo Padre creado con éxito']);
        } else {
            return response()->json(['success' => 'Grupo Hijo creado con éxito', 'parent_id' => $request->parent_id]);
        }
    }

    public function getGroups(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Group::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->where('parent_id', null)
                ->get();
        }
        return response()->json($data);
    }

    public function dataGroup(Request $request, $idSelection)
    {
        $data = Group::findOrFail($idSelection);

        return response()->json($data);
    }

    public function edit($id)
    {
        $group = Group::with('members')->find($id);

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
        $profile = Group::find($id)->delete();

        return response()->json([$profile]);
    }
}
