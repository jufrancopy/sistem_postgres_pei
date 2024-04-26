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
                    $taskNames = $task->typeTasks->pluck('typetaskable_type');

                    // Array para almacenar los nombres modificados de los tipos de tarea
                    $modifiedTaskNames = [];

                    foreach ($taskNames as $taskName) {
                        if ($taskName == 'App\Admin\Planificacion\Pei\PeiProfile') {
                            $modifiedTaskNames[] = "PEI";
                        } elseif ($taskName == 'App\Admin\Planificacion\Foda\FodaPerfil') {
                            $modifiedTaskNames[] = "FODA";
                        } else {
                            // Si no coincide con ninguna condición, mantener el nombre original
                            $modifiedTaskNames[] = $taskName;
                        }
                    }

                    // Convertir el array de nombres modificados a una cadena separada por comas
                    $formattedTaskNames = implode(', ', $modifiedTaskNames);

                    return $formattedTaskNames;
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
            $request->validate([
                'group_id' => 'required',
                'details' => 'required',
                'typetaskable_type' => 'required|array', // Asegurar que typetaskable_type sea un arreglo
                'status' => 'required', // Asegurar que el campo status esté presente
            ], [
                'group_id.required' => 'El campo grupo es requerido',
                'details.required' => 'Describa brevemente la actividad',
                'typetaskable_type.required' => 'El campo typetaskable_type es requerido',
                'status.required' => 'El campo status es requerido',
            ]);
        }

        // Crear o actualizar la tarea
        $taskData = [
            'group_id' => $request->group_id,
            'details' => $request->details,
            'status' => $request->status,
        ];
        $task = Task::updateOrCreate(['id' => $request->task_id], $taskData);

        // Asociar los analistas
        $analysts = $request->analyst_id;
        $task->analysts()->sync($analysts);


        // Asociar los tipos de tareas
        $typetaskData = [];
        foreach ($request->typetaskable_type as $jsonString) {
            $decodedArray = json_decode($jsonString, true); // Decodificar el JSON en un array asociativo
            foreach ($decodedArray as $item) {
                $typetaskData[] = [
                    'typetaskable_type' => $item['model'], // Acceder al modelo desde el arreglo de modelos
                    'typetaskable_id' => $item['id'], // Acceder al ID desde el arreglo de IDs
                ];
            }
        }

        // Obtener los IDs de los typetaskable que se deben mantener
        $typetaskableIdsToKeep = collect($typetaskData)->pluck('typetaskable_id');

        // Eliminar los registros que no están en la lista de IDs a mantener
        $task->typeTasks()->whereNotIn('typetaskable_id', $typetaskableIdsToKeep)->delete();

        // Crear o actualizar los registros según sea necesario
        foreach ($typetaskData as $data) {
            // Verificar si ya existe un registro con typetaskable_id igual al que estamos tratando de crear
            $existingTypeTask = $task->typeTasks()->where('typetaskable_id', $data['typetaskable_id'])->first();

            if ($existingTypeTask) {
                // Si existe, actualiza el registro existente
                $existingTypeTask->update($data);
            } else {
                // Si no existe, crea un nuevo registro
                $task->typeTasks()->create($data);
            }
        }



        // Retornar una respuesta apropiada
        if ($task->wasRecentlyCreated) {
            return response()->json(['success' => 'Tarea creada con éxito']);
        } else {
            return response()->json(['success' => 'Tarea actualizada con éxito']);
        }
    }


    public function getTask(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;

            $fodaData = FodaPerfil::select("id", "name", DB::raw("'" . FodaPerfil::class . "' as model"))
                ->where('name', 'LIKE', "%$search%")
                ->get();

            $peiData = PeiProfile::select("id", "name", DB::raw("'" . PeiProfile::class . "' as model"))
                ->where('name', 'LIKE', "%$search%")
                ->get();

            $data = $fodaData->concat($peiData);
        }

        return response()->json($data);
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

        $typeTasksCheckedInput = [];

        foreach ($task->typeTasks as $typeTask) {
            $typeTasksCheckedInput[] = ['id' => $typeTask->typetaskable_id, 'text' => $typeTask->typetaskable_type];
        }

        $typeTasksChecked = [];

        foreach ($task->typeTasks as $typeTask) {
            // Cargar el modelo al que pertenece el typetaskable_id
            $model = app($typeTask->typetaskable_type)->find($typeTask->typetaskable_id);

            // Verificar si el modelo se encontró y tiene el atributo 'name'
            if ($model && $model->name) {
                $typeTasksChecked[] = ['id' => $typeTask->typetaskable_id, 'text' => $model->name];
            } else {
                // Si el modelo no se encontró o no tiene el atributo 'name', puedes usar algún valor por defecto
                $typeTasksChecked[] = ['id' => $typeTask->typetaskable_id, 'text' => 'Sin nombre'];
            }
        }

        $analystsChecked = [];

        foreach ($task->analysts as $analyst) {
            $analystsChecked[] = ['id' => $analyst->id, 'text' => $analyst->name];
        }

        return response()->json([
            'task' => $task,
            'analystsChecked' => $analystsChecked,
            'typeTasksChecked' => $typeTasksChecked,
            'typeTasksCheckedInput' => $typeTasksCheckedInput
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
                    if ($typeTask->typetaskable_type == 'App\Admin\Planificacion\Foda\FodaPerfil') {
                        $typeTaskableId = $typeTask->typetaskable_id;
                        $idName = 'showMatrizFoda';
                        $routeName = 'foda-analisis-matriz';
                        $label = 'FODA';
                    } elseif ($typeTask->typetaskable_type == 'App\Admin\Planificacion\Pei\PeiProfile') {
                        $typeTaskableId = $typeTask->typetaskable_id;
                        $idName = 'showPeiDetailes';
                        $routeName = 'pei-profiles.show';
                        $label = 'PEI';
                    }

                    $typeTaskIds[] = '<a href="' . route($routeName, $typeTaskableId) . '" data-id="' . $typeTaskableId . '" id="' . $idName . '"><span class="badge badge-secondary">' . $label . '</span></a>';
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

            $task = Task::with(['typeTasks' => function ($query) {
                $query->with('typetaskable'); // Carga los detalles de los tipos de tarea relacionados
            }])->findOrFail($id);

            foreach ($task->typeTasks as $typeTask) {
                if ($typeTask->typetaskable_type == "App\Admin\Planificacion\Pei\PeiProfile") {
                    $data[] = [
                        'task' => $typeTask->typetaskable->name . " (PEI)", // Accede al nombre del tipo de tarea relacionado
                        'status' => $typeTask->status, // Accede al estado del tipo de tarea
                        'action' => '<a href="' . route('pei-profiles.show', $typeTask->typetaskable_id) . '" class="btn btn-success btn-circle"><i class="fas fa-tasks"></i></a>',
                    ];
                } else if ($typeTask->typetaskable_type == "App\Admin\Planificacion\Foda\FodaPerfil") {
                    $data[] = [
                        'task' => $typeTask->typetaskable->name . " (FODA)", // Accede al nombre del tipo de tarea relacionado
                        'status' => $typeTask->status, // Accede al estado del tipo de tarea
                        'action' => '<a href="' . route('foda.show.details', $typeTask->typetaskable_id) . '" class="btn btn-success btn-circle"><i class="fas fa-tasks"></i></a>',
                    ];
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
