<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaModelo;
use App\Admin\Globales\Organigrama;

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
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('dependency', function (FodaPerfil $profile) {
                    return $profile->dependency->dependency;
                })

                ->addColumn('model', function (FodaPerfil $profile) {
                    return $profile->model->nombre;
                })

                ->addColumn('categories', function (FodaPerfil $profile) {
                    $categoryNames = $profile->categories->pluck('nombre')->implode(', '); // Cambia 'nombre' al nombre del campo de categoría en tu modelo
                    return $categoryNames;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfile"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="/foda-analisis-ambientes/' . $row->id . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-info btn-circle"><i class="far fa-eye" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $modelos = FodaModelo::orderBy('nombre', 'ASC')->pluck('nombre', 'id')->toArray();
        $categorias = FodaCategoria::orderBy('nombre', 'ASC')->pluck('nombre', 'id')->toArray();
        $dependencies = Organigrama::whereIsRoot()->pluck('dependency', 'id')->toArray();

        $categoriasChecked = [];

        return view('admin.planificacion.fodas.perfiles.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }



    public function getCategorias($id)
    {
        return FodaCategoria::where('modelo_id', $id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modelos = FodaModelo::orderBy('id', 'ASC')->pluck('nombre', 'id');
        $categorias = FodaCategoria::orderBy('nombre', 'ASC')->pluck('nombre', 'id')->toArray();

        $categoriasChecked = [];

        return view('admin.planificacion.fodas.perfiles.create', get_defined_vars());
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                    'context'          => 'required',
                    'model_id'            => 'required',
                    'dependency_id'            => 'required',
                ],
                [
                    'name.required'             => 'Agregue el nombre del Modelo',
                    'context.required'          => 'Indique el Contexto',
                    'dependency_id.required'    => 'Debe seleccionar la Dependencia responsable',
                    'model_id.required'         => 'Seleccione el Modelo de Análisis'

                ]
            );
        };

        $profile = FodaPerfil::updateOrCreate(
            ['id' => $request->profile_id],
            [
                'name' => $request->name,
                'context' => $request->context,
                'model_id' => $request->model_id,
                'dependency_id' => $request->dependency_id
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
        $profile = FodaPerfil::with(['dependency', 'model', 'categories'])->find($id);

        $categoriesChecked = [];

        foreach ($profile->categories as $category) {
            $categoriesChecked[] = ['id' => $category->id, 'text' => $category->nombre];
        }

        return response()->json(['profile' => $profile, 'categoriesChecked' => $categoriesChecked]);
    }

    public function destroy(Request $request, $id)
    {
        $profile = FodaPerfil::find($id)->delete();

        return response()->json([$profile]);
    }
}
