<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\FodaCategoria;
use App\Admin\FodaAsignarCategoria;
use App\Admin\FodaPerfil;

class FodaAsignarCategoriaController extends Controller
{
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
        
        $perfilCategorias = FodaAsignarCategoria::orderBy('id', 'ASC')->paginate(10);
        dd($perfilCategorias);
        return view ('admin.fodas.asignar-categorias.index', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $perfiles =FodaPerfil::orderBy('id', 'ASC')->pluck('nombre', 'id');
        
        $perfilCategorias = FodaAsignarCategoria::orderBy('id', 'ASC')->get();
        dd($perfilCategorias->categorias->nombre);
        
        $categorias =FodaCategoria::orderBy('id','ASC')->pluck('nombre', 'id');
        $categoriasChecked=[];

        // Obtener las materias relacionada a la matriculacion actual.
        foreach ($perfilCategorias->categorias as $categoria) {
            // Acumular las materias en el array '$materiasChecked'.
            $categoriasChecked[] = $categoria->id;
        }
        
        return view('admin.fodas.asignar-categorias.create', get_defined_vars());        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
