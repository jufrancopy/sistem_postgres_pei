<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Administrador']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editUser"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteUser"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.users.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getTask(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $fodaData = FodaPerfil::select("id", "name", DB::raw("'FODA' as model"))
                ->where('name', 'LIKE', "%$search%")
                ->get();

            $peiData = PeiProfile::select("id", "name", DB::raw("'PEI' as model"))
                ->where('name', 'LIKE', "%$search%")
                ->get();

            $data = $fodaData->concat($peiData);
        }


        return response()->json($data);
    }

    public function getTaskType(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = TypeTask::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                    'email'           => 'required',
                    'type'              => 'required',
                    'model_id'          => 'required',
                ],
                [
                    'name.required'             => 'Agregue el nombre del Modelo',
                    'context.required'          => 'Indique el Contexto',
                    'type.required'             => 'Indique el Tipo',
                    'model_id.required'         => 'Seleccione el Modelo de Análisis'

                ]
            );
        };

        
        $user = User::updateOrCreate(
            ['id' => $request->user_id],
            [
                'name' => $request->name,
                'email' => $request->email,
                'type' => $request->type,
                'model_id' => $request->model_id,
                'dependency_id' => $request->dependency_id,
                'group_id' => $request->group_id,
            ]
        );

        //Insert into pivot table 
        $categories = $request->category_id;
        $profile->categories()->sync($categories);

        if ($profile->wasRecentlyCreated) {
            return response()->json(['success' => 'Perfil creado correctamente.']);
        } else {
            return response()->json(['success' => 'Perfil actualizado correctamente.']);
        }
    }

    public function getTypeTasks(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = TypeTask::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }

    public function dataTypeTask(Request $request, $idSelection)
    {
        $data = TypeTask::findOrFail($idSelection);

        return response()->json($data);
    }

    public function edit($id)
    {
        $typeTask = TypeTask::findOrFail($id);

        return response()->json($typeTask);
    }


    public function destroy(Request $request, $id)
    {
        $typeTask = TypeTask::find($id)->delete();

        return response()->json([$typeTask]);
    }
}
