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
    //antes de procesar el index() ejecuta el metodo consturctor
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $perfil= FodaPerfil::create($request->all());
        $perfil = FodaPerfil::create($request->except(['categoria_id']));
        $perfil->categorias()->attach($request->categoria_id);

        return redirect()->route('foda-perfiles.index')
            ->with('success', 'Perfil creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $perfil = FodaPerfil::find($id);

        return response()->json($perfil);
    }

    // public function edit($id)
    // {
    //     $perfil = FodaPerfil::find($id);
    //     $modelos = FodaModelo::orderBy('id', 'ASC')->pluck('nombre', 'id');
    //     $categorias = FodaCategoria::orderBy('id', 'ASC')->where('modelo_id', $perfil->modelo_id)->pluck('nombre', 'id');

    //     $categoriasChecked = [];
    //     // Obtener las categorias relacionadas al PERFIL
    //     foreach ($perfil->categorias as $categoria) {
    //         // Acumular las categorias recolectadas en el array '$materiasChecked'.
    //         $categoriasChecked[] = $categoria->id;
    //     }

    //     return view('admin.planificacion.fodas.perfiles.edit', get_defined_vars());
    // }

    public function update(Request $request, $id)
    {
        $perfil = FodaPerfil::find($id);
        $perfil->categorias()->sync($request->categoria_id);
        $perfil->fill($request->except(['categoria']))->save();

        return redirect()->route('foda-perfiles.index')
            ->with('success', 'Perfil actualizado el perfil' . $perfil->nombre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FodaPerfil::find($id)->delete();
        return back()->with('success', 'Perfil Eliminado satisfactoriamente.');
    }
}
