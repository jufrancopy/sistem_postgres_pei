<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use App\Admin\Planificacion\Pei\PeiPerfil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeiPerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $perfiles = PeiPerfil::nombre($request->get('nombre'))->orderBy('id', 'ASC')->paginate(10);
      
      return view('admin.planificacion.peis.perfiles.index', get_defined_vars())
      ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.planificacion.peis.perfiles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $perfil = PeiPerfil::create($request->all());

        return redirect()->route('peis-perfiles.index')
            ->with('success','Perfil de Planificación Estratégica creado exitosamente');
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
        $perfilPei = PeiPerfil::find($id);
        
        return view ('admin.planificacion.peis.perfiles.edit', get_defined_vars());
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
        $perfilPei = PeiPerfil::find($id);
        $perfilPei->fill($request->all())->save();

        return redirect()->route('peis-perfiles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        
        
        PeiPerfil::find($id)->delete();
        
        return back()->with('success', 'Perfil de Planificación eliminado correctamente.');
    }
}
