<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaModelo;
use App\Admin\Globales\Organigrama;

use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;
use Yajra\DataTables\DataTables;


class FodaPerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FodaPerfil::latest()->get();
            // Determinar si hay elementos tipo grupo
            $groupType = FodaPerfil::where('type', 'grupal')->exists();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('dependency', function (FodaPerfil $profile) {
                    // Comprobar si el campo "dependency" es nulo o contiene 'null'
                    if ($profile->dependency === null || $profile->dependency->dependency === 'null') {
                        return 'Análisis Grupal';
                    } else {
                        return $profile->dependency->dependency;
                    }
                })

                ->addColumn('model', function (FodaPerfil $profile) {
                    return $profile->model->name;
                })

                ->addColumn('categories', function (FodaPerfil $profile) {
                    $categoryNames = $profile->categories->pluck('name')->implode(', '); // Cambia 'nombre' al nombre del campo de categoría en tu modelo
                    return $categoryNames;
                })

                ->addColumn('action', function ($row) use ($groupType) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfile"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="/foda-analisis-ambientes/' . $row->id . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-warning btn-circle"><i class="far fa-eye" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="/foda-profiles/' . $row->id . "/details" . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-info btn-circle"><i class="fa fa-search" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    // Agregar el botón "Crear Grupo" solo si existe tipo grupo
                    if ($row->type === 'grupal') {
                        $btn .= ' <a href="' . route('foda.add.group', $row->id) . '" class="btn btn-success btn-circle"><i class="fa fa-users" aria-hidden="true"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $dependencies = Organigrama::whereIsRoot()->pluck('dependency', 'id')->toArray();

        $categoriasChecked = [];

        return view('admin.planificacion.fodas.perfiles.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function addGroup(Request $request, $idPerfil)
    {
        $profile = FodaPerfil::findOrFail($idPerfil);
        if ($request->ajax()) {
            $data = FodaPerfil::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfile"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="/foda-analisis-ambientes/' . $row->id . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-info btn-circle"><i class="far fa-eye" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->addColumn('group_name', function (FodaPerfil $profile) {
                    return $profile->group->name;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.fodas.perfiles.add_group', get_defined_vars());
    }

    public function getCategorias($id)
    {
        return FodaCategoria::where('modelo_id', $id)->get();
    }

    public function create()
    {
        $modelos = FodaModelo::orderBy('id', 'ASC')->pluck('nombre', 'id');
        $categorias = FodaCategoria::orderBy('nombre', 'ASC')->pluck('nombre', 'id')->toArray();

        $categoriasChecked = [];

        return view('admin.planificacion.fodas.perfiles.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'context' => 'required',
            'type' => 'required',
            'model_id' => 'required',
            $request->type == 'individual' ? 'dependency_id' : 'group_id' => 'required',
        ], [
            'name.required' => 'Agregue el nombre del Modelo',
            'context.required' => 'Indique el Contexto',
            'type.required' => 'Indique el Tipo',
            $request->type == 'individual' ? 'dependency_id.required' : 'group_id.required' => $request->type == 'individual' ? 'Indique la Dependencia Responsable' : 'Seleccione un Grupo de Análisis',
            'model_id.required' => 'Seleccione el Modelo de Análisis'
        ]);

        $profileId = $request->profile_id;
        $data = [
            'name' => $request->name,
            'context' => $request->context,
            'type' => $request->type,
            'model_id' => $request->model_id,
        ];

        if ($request->type == 'individual') {
            $data['dependency_id'] = $request->dependency_id;
        } else {
            $data['group_id'] = $request->group_id;
        }

        $profile = $profileId ? FodaPerfil::find($profileId) : new FodaPerfil(['id' => Str::uuid()]);

        $profile->fill($data)->save();

        //Insert into pivot table 
        $categories = $request->category_id;
        $profile->categories()->sync($categories);

        $message = $profileId ? 'Perfil actualizado correctamente.' : 'Perfil creado correctamente.';
        return response()->json(['success' => $message]);
    }

    public function createGroupRootProfile(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                    'context'           => 'required',
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

        $profileId = Str::uuid();
        $profile = FodaPerfil::updateOrCreate(
            ['id' => $profileId],
            [
                'name' => $request->name,
                'context' => $request->context,
                'type' => $request->type,
                'model_id' => $request->model_id,
                'dependency_id' => $request->dependency_id,
                'group_id' => $request->group_id,
            ]
        );
        $groupRootId = $profile->group->id;

        //Insert into pivot table 
        $categories = $request->category_id;
        $profile->categories()->sync($categories);

        if ($profile->wasRecentlyCreated) {
            // return response()->json(['redirect' => route('foda-matriz-groups', $profile->group_id), 'message' => 'Perfil Grupal creado correctamente.']);
            return redirect()->route('foda-matriz-groups', $groupRootId)->with(['success' => 'Perfil asignado correctamente']);
        } else {
            // return response()->json(['redirect' => route('foda-matriz-groups', $profile->group_id), 'message' => 'Perfil Grupal actualizado correctamente.']);
            return redirect()->route('foda-matriz-groups', $groupRootId)->with(['success' => 'Perfil asignado correctamente']);
        }
    }

    public function showDetails(Request $request, $idProfile)
    {
        // $profile = FodaPerfil::findOrFail($idProfile);
        // $modelId = $profile->model_id;
        // $model = FodaModelo::findOrFail($modelId);

        // $query = DB::table('planificacion.foda_perfiles AS profile')
        //     ->join('planificacion.foda_models AS model', 'profile.model_id', '=', 'model.id')
        //     ->join('groups AS group', 'profile.group_id', '=', 'group.id')
        //     ->join('organigramas AS dependency', 'profile.dependency_id', '=', 'dependency.id')
        //     ->select(DB::raw('model.id, model.parent_id, model.type, model.name, model.environment, model.description, ARRAY[model.id] as ruta, 0 as deph'))
        //     ->where('profile.id', $idProfile);

        // $subQuery = DB::table('planificacion.foda_models as m')
        //     ->select(DB::raw('m.id, m.parent_id, m.type, m.name, m.environment, m.description,
        // t.ruta || ARRAY[m.id] as ruta, t.deph + 1 as deph'))
        //     ->join('tree as t', 't.id', '=', 'm.parent_id');
        // $query->unionAll($subQuery);

        // $collection = DB::table('tree')
        //     ->select(['tree.*', DB::raw('array_to_json(ruta) as path')])
        //     ->withRecursiveExpression('tree', $query)
        //     ->orderBy('tree.ruta')
        //     ->get();

        // $closure = (function ($item) use (&$closure, $collection) {
        //     $key = $item->keys()->shift();
        //     if (!empty($key) || $key === 0) {
        //         if (!isset($collection[$key]->defined)) {
        //             $collection[$key]->defined = true;
        //             $collection[$key]->rowspan = 1;
        //         } else {
        //             $collection[$key]->rowspan++;
        //         }
        //         $closure($collection
        //             ->where('id', $collection[$key]->parent_id));
        //     }
        // });

        // $collection->reverse()->map(function ($item, $key) use ($closure, $collection) {
        //     $item->last = false;
        //     $last = $collection->last();
        //     if (($last->id != $item->id && $collection[$key + 1]->deph <= $item->deph)
        //         || $last->id == $item->id
        //     ) {
        //         $item->last = true;
        //         $item->colspan = $collection->max('deph') - $item->deph + 1;
        //         $closure($collection->where('id', $item->parent_id));
        //     }
        //     unset($item->defined);
        // });

        $fodaProfile = FodaPerfil::findOrFail($idProfile);
        $modeId =  $fodaProfile->model_id;
        $model = FodaModelo::with(['descendants'])->descendantsAndSelf($modeId)->toTree();

        // $responsiblesActionsCount = [];

        // foreach ($profile->first()->children as $axi) {
        //     foreach ($axi->children as $goal) {
        //         foreach ($goal->children as $action) {
        //             $responsibles = $action->responsibles;

        //             foreach ($responsibles as $responsible) {
        //                 $responsiblesId = $responsible->id;
        //                 // Si tenemos valor, sumamos 1, si no tenemos valor, valor es 0
        //                 $responsiblesActionsCount[$responsiblesId] = ($responsiblesActionsCount[$responsiblesId] ?? 0) + 1;
        //             }
        //         }
        //     }
        // }

        // foreach ($responsiblesActionsCount as $responsibleId => $actionsCount) {
        //     $responsible = Organigrama::find($responsibleId);
        //     ['dependency' => $responsible->dependency, 'actionsCount' => $actionsCount];
        // }


        return view('admin.planificacion.fodas.perfiles.details', get_defined_vars());
    }
    public function show($id)
    {
        $perfil = FodaPerfil::find($id);
        $categorias = $perfil->categorias()->orderBy('nombre', 'ASC')->get();
        $categoriasChecked = [];

        foreach ($perfil->categorias as $categoria) {
            $categoriasChecked[] = $categoria->id;
        }
        return view('admin.planificacion.fodas.perfiles.show', get_defined_vars());
    }

    public function edit($id)
    {
        $profile = FodaPerfil::with(['dependency', 'model', 'categories', 'group'])->find($id);

        $rootGroup = $profile->group->root()->first();

        $categoriesChecked = [];

        foreach ($profile->categories as $category) {
            $categoriesChecked[] = ['id' => $category->id, 'text' => $category->name];
        }

        return response()->json(['profile' => $profile, 'categoriesChecked' => $categoriesChecked, 'rootGroup' => $rootGroup]);
    }

    public function destroy(Request $request, $id)
    {
        $profile = FodaPerfil::find($id)->delete();

        return response()->json([$profile]);
    }
}
