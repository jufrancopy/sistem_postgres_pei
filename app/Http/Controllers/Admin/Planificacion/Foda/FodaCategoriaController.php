<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;

use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaAspecto;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Foda\FodaModelo;

class FodaCategoriaController extends Controller
{
    //antes de procesar el index() ejecuta el metodo consturctor
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $categorias = FodaCategoria::nombre($request->get('nombre'))->orderBy('id', 'DESC')->paginate(10);

        return view('admin.planificacion.fodas.categorias.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function dataCategories(Request $request, $modelId)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = FodaCategoria::select("id", "nombre")
                ->where('nombre', 'LIKE', "%$search%")
                ->where('modelo_id', $modelId)
                ->get();
        }
        return response()->json($data);
    }

    public function dataCategory(Request $request, $idSelection)
    {
        $data = FodaCategoria::findOrFail($idSelection);

        return response()->json($data);
    }

    public function create()
    {
        $modelos = FodaModelo::orderBy('id', 'ASC')->pluck('nombre', 'id');
        return view('admin.planificacion.fodas.categorias.create', get_defined_vars());
    }

    public function listadoCategorias(Request $request, $idModelo)
    {
        $modelo = FodaModelo::find($idModelo);
        $categorias = FodaCategoria::nombre($request->get('nombre'))->where('modelo_id', '=', $idModelo)->paginate(10);

        return view('admin.planificacion.fodas.modelos.listado-categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);;
    }


    public function crearCategoria(Request $request, $idModelo)
    {
        $modelo = FodaModelo::find($idModelo);
        $modelo_id = $modelo->id;
        return view('admin.planificacion.fodas.categorias.create', get_defined_vars());
    }


    public function store(Request $request)
    {
        $categoria = FodaCategoria::create($request->all());

        return redirect()->route('foda-modelo-categorias', $categoria->modelo_id)
            ->with('success', 'Categoria creada satisfactoriamente');
    }

    public function show($id)
    {
        $categoria = FodaCategoria::find($id);
        return view('admin.planificacion.fodas.categorias.show', get_defined_vars());
    }

    public function listaAspectosCategoria(Request $request, $idCategoria)
    {
        $aspectos = FodaAspecto::nombre($request->get('nombre'))->where('categoria_id', '=', $idCategoria)->get();

        return view('admin.planificacion.fodas.categorias.aspectos', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function edit($id)
    {

        $categoria = FodaCategoria::find($id);
        $modelo_id = $categoria->modelo_id;




        return view('admin.planificacion.fodas.categorias.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $categoria = FodaCategoria::find($id);
        $categoria->fill($request->all())->save();

        return redirect()->route('foda-modelo-categorias', $categoria->modelo_id)
            ->with('success', 'Categoria actualizada satisfactoriamente');
    }

    public function destroy($id)
    {
        FodaCategoria::find($id)->delete();
        return back()->with('success', 'Categoria eliminada correctamente.');
    }
}
