<?php

namespace App\Http\Controllers\Admin\Planificacion\Task;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use App\Admin\Planificacion\Task\TypeTask;

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

                ->addColumn('task_value', function ($row) {
                    $taskValue = null;

                    // Verifica si el task_id coincide con un registro en FodaPerfil
                    $fodaProfile = FodaPerfil::where('id', $row->typetaskable_id)->first();

                    if ($fodaProfile) {
                        $taskValue = $fodaProfile->name;
                    } else {
                        // Si no coincide con FodaPerfil, verifica en PeiProfile
                        $peiProfile = PeiProfile::where('id', $row->typetaskable_id)->first();

                        if ($peiProfile) {
                            $taskValue = $peiProfile->name;
                        }
                    }

                    return $taskValue;
                })

                ->addColumn('model', function ($row) {
                    $modelValue = null;

                    if ($row->typetaskable_type === 'App\Admin\Planificacion\Foda\FodaPerfil') {
                        // Si es de tipo FodaPerfil
                        $modelValue = 'Análisis FODA';
                    } elseif ($row->typetaskable_type === 'App\Admin\Planificacion\Pei\PeiProfile') {
                        // Si es de tipo PeiProfile
                        $modelValue = 'Definción de Visión, Misión y Valores';
                    }

                    return $modelValue;
                })

                ->addColumn('group', function ($row) {
                    $groupValue = null;

                    $fodaProfile = FodaPerfil::where('id', $row->typetaskable_id)->first();

                    if ($fodaProfile) {
                        $groupValue = $fodaProfile->group->name;
                    } else {
                        $peiProfile = PeiProfile::where('id', $row->typetaskable_id)->first();

                        if ($peiProfile) {
                            $groupValue = $peiProfile->group->name;
                        }
                    }

                    return $groupValue;
                })


                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.tasks.type_tasks.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getTask(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $fodaData = FodaPerfil::select("id", "name", DB::raw("'App\Admin\Planificacion\Foda\FodaPerfil' as model"))
                ->where('name', 'LIKE', "%$search%")
                ->get();

            $peiData = PeiProfile::select("id", "name", DB::raw("'App\Admin\Planificacion\Pei\PeiProfile' as model"))
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
                    'task_id'              => 'required',
                ],
                [
                    'task_id.required'     => 'Campo nombre es requerido',
                ]
            );
        };


        $typeTask = TypeTask::updateOrCreate(
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
