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

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                    'owner'              => 'required',
                ],
                [
                    'name.required'     => 'Campor Nombre es requerido',
                    'owner.required'     => 'Indique el Propietario del Modelo',
                ]
            );
        };

        $model = FodaModelo::updateOrCreate(
            ['id' => $request->model_id],
            [
                'name' => $request->name,
                'owner' => $request->owner,
                'description' => $request->description,
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

    public function edit($id)
    {
        $modal = FodaModelo::find($id);

        return response()->json($modal);
    }


    public function show(Request $request, $id)
    {
        $model = FodaModelo::findOrFail($id);

        if ($request->ajax()) {
            $data = FodaModelo::descendantsOf($id);
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editCategory"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-success btn-circle addAspect"><i class="fa fa-plus" aria-hidden="true"></i></a>';

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
