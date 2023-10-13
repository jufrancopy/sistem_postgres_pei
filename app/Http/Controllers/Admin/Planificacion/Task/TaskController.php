<?php

namespace App\Http\Controllers\Admin\Planificacion\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Admin\Planificacion\Task\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Task::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editTask"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteTask"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.tasks.tasks.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'group_id'              => 'required',
                    'details'                => 'required',
                ],
                [
                    'group_id.required'         => 'Campo nombre es requerido',
                    'details.required'          => 'Describa brevemente la acitividad',
                ]
            );
        };

        $task = Task::updateOrCreate(
            ['id' => $request->task_id],
            [
                'group_id' => $request->group_id,
                'details' => $request->details,
                'status' => $request->status,
            ]
        );

        $analysts = $request->analyst_id;
        $task->analysts()->sync($analysts);


        $tasks = $request->task_id;
        $task->typeTasks()->sync($tasks);

        if ($task->wasRecentlyCreated) {
            return response()->json(['success' => 'Tipo de Tarea creado con éxito']);
        } else {
            return response()->json(['success' => 'Tipo de Tarea actualizado con éxito']);
        }
    }

    public function getTasks(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Task::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }

    public function dataTypeTask(Request $request, $idSelection)
    {
        $data = Task::findOrFail($idSelection);

        return response()->json($data);
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);

        return response()->json($task);
    }


    public function destroy(Request $request, $id)
    {
        $task = Task::find($id)->delete();

        return response()->json([$task]);
    }
}
