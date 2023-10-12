<?php

namespace App\Http\Controllers\Admin\Planificacion\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

use App\Admin\Planificacion\Tasks\TypeTask;

class TypeTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TypeTask::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editTypeTask"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteTypeTask"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $routes = Route::getRoutes();
        $arrayRoutes = [];

        foreach ($routes as $route) {
            $arrayRoutes[$route->uri] = $route->uri;
        }

        return view('admin.planificacion.type_tasks.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                    'route'              => 'required',
                ],
                [
                    'name.required'     => 'Campo nombre es requerido',
                    'route.required'     => 'Seleccione la ruta vinculante a la tarea',
                ]
            );
        };

        $typeTask = TypeTask::updateOrCreate(
            ['id' => $request->typeTask_id],
            [
                'name' => $request->name,
                'route' => $request->route,
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
