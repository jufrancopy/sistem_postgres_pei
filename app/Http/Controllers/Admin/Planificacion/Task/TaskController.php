<?php

namespace App\Http\Controllers\Admin\Planificacion\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

//Models
use App\Admin\Planificacion\Task\Task;
use App\Admin\Planificacion\Task\TypeTask;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Pei\PeiProfile;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;
use App\Admin\Globales\Group;

use Illuminate\Support\Facades\Cookie;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Administrador|Analista']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Obtener el usuario autenticado
            $user = auth()->user();

            // Comprobar si el usuario es un administrador
            if ($user->hasRole('Administrador')) {
                $data = Task::latest()->get();
            } else {
                // Filtrar tareas asignadas al usuario analista actual
                $data = Task::whereHas('analysts', function ($query) use ($user) {
                    $query->where('analyst_id', $user->id);
                })->latest()->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $buttons = '';

                    // Verifica si el usuario es Administrador
                    if ($user->hasRole('Administrador')) {
                        // Si es Administrador, muestra los botones de edición y eliminación
                        $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editTask"><i class="far fa-edit"></i></a>';
                        $buttons .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteTask"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    // Siempre muestra el botón 'View' sin importar el rol
                    $buttons .= ' <a href="' . route('tasks.show', $row->id) . '" class="btn btn-success btn-circle"><i class="fas fa-tasks"></i></a>';

                    return $buttons;
                })
                ->addColumn('group', function (Task $task) {
                    return $task->group->name;
                })

                ->addColumn('analysts', function (Task $task) {
                    $analystNames = $task->analysts->pluck('name')->implode(', ');
                    return $analystNames;
                })

                ->addColumn('tasks', function (Task $task) {
                    // $taskNames = $task->typeTasks->pluck('name')->implode(', ');
                    $taskNames = $task->typeTasks->pluck('typetaskable_id')->implode(', ');
                    return $taskNames;
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

        $tasks = $request->typetask_id;
        $task->typeTasks()->sync($tasks);

        if ($task->wasRecentlyCreated) {
            return response()->json(['success' => 'Tarea creado con éxito']);
        } else {
            return response()->json(['success' => 'Tarea actualizada con éxito']);
        }
    }

    public function getTasks(Request $request)
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
        $data = Task::findOrFail($idSelection);

        return response()->json($data);
    }

    public function edit($id)
    {
        $task = Task::with(['group', 'analysts'])->find($id);

        $typeTasksChecked = [];

        foreach ($task->typeTasks as $typeTask) {
            $typeTasksChecked[] = ['id' => $typeTask->id, 'text' => $typeTask->name];
        }

        $analystsChecked = [];

        foreach ($task->analysts as $analyst) {
            $analystsChecked[] = ['id' => $analyst->id, 'text' => $analyst->name];
        }

        return response()->json([
            'task' => $task,
            'analystsChecked' => $analystsChecked,
            'typeTasksChecked' => $typeTasksChecked
        ]);
    }

    public function getTasksForGroup()
    {
        return view('admin.planificacion.tasks.tasks.list_tasks_for_group');
    }

    public function dataTreeGroup()
    {
        $groups = Group::orderBy('id', 'ASC')->withDepth()->get()->linkNodes();
        $result = [];
        foreach ($groups as $group) {
            $parent = $group->parent_id ?: '#';
            $text = $group->name;

            // Obtén las tareas asociadas al grupo
            $tasks = $group->tasks;
            // Si hay tareas asociadas
            if ($tasks->isNotEmpty()) {
                $typeTaskIds = [];
                foreach ($tasks[0]->typeTasks as $typeTask) {
                    if ($typeTask->typetaskable_type == 'FODA') {
                        $typeTaskableId = $typeTask->typetaskable_id;
                        $idName = 'showMatrizFoda';
                        $routeName = 'foda-analisis-matriz';
                    } elseif ($typeTask->typetaskable_type == 'PEI') {
                        $typeTaskableId = $typeTask->typetaskable_id;
                        $idName = 'showPeiDetailes';
                        $routeName = 'pei-profiles.show';
                    }

                    $typeTaskIds[] = '<a href="' . route($routeName, $typeTaskableId) . '" data-id="' . $typeTaskableId . '" id="' . $idName . '"><span class="badge badge-secondary">' . $typeTask->typetaskable_type . '</span></a>';
                }

                if (!empty($typeTaskIds)) {
                    $text .= ' (Tareas: ' . implode(', ', $typeTaskIds) . ')';
                }
            }

            $node = [
                'id' => $group->id,
                'state' => ['opened' => true],
                'parent' => $parent,
                'text' => $text,
            ];
            array_push($result, $node);
        }
        return response()->json($result);
    }

    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $tasks = Task::with(['typeTasks'])->where('id', $id)->latest()->get();

            $data = [];
            foreach ($tasks as $task) {
                foreach ($task->typeTasks as $typeTask) {
                    if ($typeTask->typetaskable_type == "PEI") {

                        $data[] = [
                            'task' => $typeTask->name,
                            'status' => $typeTask->pivot->status, // Supongo que todas las tareas relacionadas comparten el mismo estado
                            'action' =>
                            ' <a href="' . route('pei-profiles.show', $typeTask->typetaskable_id) . '" class="btn btn-success btn-circle" data-task-id="' . $id . '"><i class="fas fa-tasks"></i></a>' .
                                ' <a href="' . route('pei-profiles.details', $typeTask->typetaskable_id) . '" class="btn btn-info btn-circle" data-task-id="' . $id . '"><i class="fas fa-tree"></i></a>',

                        ];
                    } else {
                        $data[] = [
                            'task' => $typeTask->name,
                            'status' => $typeTask->pivot->status, // Supongo que todas las tareas relacionadas comparten el mismo estado
                            'action' =>
                            ' <a href="' . route('foda.get.tree', $typeTask->typetaskable_id) . '" class="btn btn-success btn-circle routeId" data-task-id="' . $id . '"><i class="fas fa-tasks"></i></a>' .
                                ' <a href="' . route('foda-analisis-matriz', $typeTask->typetaskable_id) . '" class="btn btn-warning btn-circle routeId" data-task-id="' . $id . '"><i class="fas fa-eye"></i></a>',
                        ];
                    }
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        $task = Task::with('typeTasks')->where('id', $id)->latest()->first();

        $analysts = [];

        $analystsNames = [];

        foreach ($task->analysts as $analyst) {
            $analystsNames[] = $analyst->name;
        }

        if ($task) {
            $group = $task->group; // Obtén el grupo asociado a la tarea.
            $members = $group->members; // Obtén los miembros del grupo.
        }
        $taskShowUrl = route('tasks.show', $id);
        Cookie::queue('tasks', $taskShowUrl, 60);

        return view('admin.planificacion.tasks.tasks.show', get_defined_vars());
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::find($id)->delete();

        return response()->json([$task]);
    }
}
