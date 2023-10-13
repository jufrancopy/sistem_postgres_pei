<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Planificacion\Foda\FodaModelo;
use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaAspecto;

use Yajra\DataTables\DataTables;

class FodaModeloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FodaModelo::where('parent_id', null)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editModel"><i class="far fa-edit"></i></a>';

                    $btn .= ' <a href="' . route('foda-models.show', $row->id) . '" class="btn btn-success btn-circle"><i class="fa fa-plus" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteModel"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.fodas.models.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getModels(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = FodaModelo::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }

    public function dataGroup(Request $request, $idSelection)
    {
        $data = FodaModelo::findOrFail($idSelection);

        return response()->json($data);
    }

    public function getCategories(Request $request, $modelId)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = FodaModelo::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->where('parent_id', $modelId)
                ->get();
        }
        return response()->json($data);
    }

    public function getAspects(Request $request, $categoryId)
    {
        $category = FodaModelo::find($categoryId);
        $modelId = $category->parent_id;

        if ($request->ajax()) {
            $data = FodaModelo::where('parent_id', $categoryId)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editAspect"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteAspect"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.fodas.models.aspects', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'                  => 'required',
                    'owner'                 => 'required',
                ],
                [
                    'name.required'         => 'Campor Nombre es requerido',
                    'owner.required'        => 'Indique el Propietario del Modelo',
                ]
            );
        };

        $model = FodaModelo::updateOrCreate(
            ['id' => $request->model_id],
            [
                'type' => $request->type,
                'name' => $request->name,
                'owner' => $request->owner,
                'description' => $request->description,
                'environment' => $request->environment,
            ]
        );

        if ($request->parent_id) {
            $node = FodaModelo::find($request->parent_id);
            $node->appendNode($model);
        }

        if ($model->parent_id == null) {
            return response()->json(['success' => 'Modelo creado con éxito']);
        } else {
            return response()->json(['success' => 'Aspecto creado con éxito', 'parent_id' => $request->parent_id]);
        }
    }


    public function showAspects($categoryId)
    {
        $category = FodaModelo::findOrFail($categoryId);
        $aspects = FodaModelo::where('parent_id', $categoryId)->get();

        return response()->json(['category' => $category, 'aspects' => $aspects]);
    }

    public function edit($id)
    {
        $modal = FodaModelo::find($id);

        return response()->json($modal);
    }


    public function show(Request $request, $id)
    {
        $category = FodaModelo::findOrFail($id);

        if ($request->ajax()) {
            $data = FodaModelo::where('parent_id', $id)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editCategory"><i class="far fa-edit"></i></a>';

                    $btn .= ' <a href="' . route('foda-models-getAspects', $row->id) . '" class="btn btn-success btn-circle"><i class="fa fa-plus" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-info btn-circle showAspects"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteCategory"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.fodas.models.show', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function destroy(Request $request, $id)
    {
        $profile = FodaModelo::find($id)->delete();

        return response()->json([$profile]);
    }
}
