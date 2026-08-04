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
        $this->middleware('auth');
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
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Editar" class="btn btn-primary btn-sm editGroup"><i class="far fa-edit"></i></a>';
                    $btn .= ' <a href="' . route('globales.groups.show', $row->id) . '" class="btn btn-success btn-sm" title="Miembros"><i class="fa fa-users"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Eliminar" class="btn btn-danger btn-sm deleteGroup"><i class="fa fa-trash"></i></a>';
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
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                ],
                [
                    'name.required'     => 'El campo Nombre es requerido',
                ]
            );
        };

        try {
            if (!$request->group_id && $request->parent_id) {
                $parent = Group::findOrFail($request->parent_id);
                $group = new Group(['name' => $request->name]);
                $parent->appendNode($group);
            } else {
                $group = Group::updateOrCreate(
                    ['id' => $request->group_id],
                    ['name' => $request->name]
                );
            }

            $members = $request->user_id ?? [];
            $group->members()->sync($members);

            if ($group->parent_id == null) {
                return response()->json(['success' => 'Evento creado con éxito']);
            } else {
                return response()->json(['success' => 'Grupo agregado al Evento correctamente', 'parent_id' => $request->parent_id]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        $profile = Group::find($id)->delete();

        return response()->json([$profile]);
    }
}
