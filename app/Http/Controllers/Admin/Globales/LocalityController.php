<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;

use App\Models\Backend\Locality;


class LocalityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Administrador']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Locality::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editLocality"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteLocality"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })


                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.globales.localities.index', get_defined_vars())
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
            $data = Locality::select("id", "name")
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
                    'task_id'              => 'required',
                ],
                [
                    'task_id.required'     => 'Campo nombre es requerido',
                ]
            );
        };


        $typeTask = Locality::updateOrCreate(
            ['id' => $request->typeTask_id],
            [
                'name' => $request->name,
                'typetaskable_id' => $request->task_id,
                'typetaskable_type' => $request->typetaskable_type,

            ]
        );

        if ($typeTask->wasRecentlyCreated) {
            return response()->json(['success' => 'Tipo de Tarea creado con éxito']);
        } else {
            return response()->json(['success' => 'Tipo de Tarea actualizado con éxito']);
        }
    }

    public function getTypeTasks(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Locality::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }

    public function dataTypeTask(Request $request, $idSelection)
    {
        $data = Locality::findOrFail($idSelection);

        return response()->json($data);
    }

    public function edit($id)
    {
        $typeTask = Locality::findOrFail($id);

        return response()->json($typeTask);
    }


    public function destroy(Request $request, $id)
    {
        $typeTask = Locality::find($id)->delete();

        return response()->json([$typeTask]);
    }
}
