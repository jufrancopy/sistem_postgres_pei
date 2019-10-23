<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Foda\FodaModelo;
use App\Admin\Foda\FodaCategoria;
use App\Admin\Foda\FodaAspecto;

class FodaModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelos=FodaModelo::nombre($request->get('nombre'))->orderBy('id','DESC')->paginate(10);
        
        return view('admin.fodas.modelos.index', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    public function listadoAspectos(Request $request, $idModelo, $idCategoria){
        $idModelo = $request->idModelo;
        $idCategoria = $request->idCategoria;
        $categoria = FodaCategoria::where('id', '=', $idCategoria)->first();
        $aspectos=FodaAspecto::nombre($request->get('nombre'))->orderBy('id', 'DESC')->where('categoria_id', '=', $idCategoria)->get();
        
        return view ('admin.fodas.modelos.aspectos', get_defined_vars())
        ->with('i', ($request->input('page', 1) - 1) * 5);;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.fodas.modelos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $modelo = FodaModelo::create($request->all());
        
        return redirect()->route('foda-modelos.index')
            ->with('success','Modelo creado satisfactoriamente');
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
        $modelo = FodaModelo::find($id);
        return view ('admin.fodas.modelos.edit', get_defined_vars());
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
        $modelo = FodaModelo::find($id);
        $modelo->fill($request->all())->save();

        return redirect()->route('foda-modelos.index')
            ->with('success','Modelo actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = FodaModelo::find($id)->delete();
        return back()->with('success', 'Modelo eliminada correctamente.');
    }
}
