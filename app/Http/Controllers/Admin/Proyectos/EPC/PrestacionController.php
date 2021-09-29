<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Admin\Proyecto\EPC\Prestacion;

class PrestacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $prestaciones = Prestacion::all();
        
        return view('admin.proyectos.epc.prestaciones.index', get_defined_vars())
        ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.prestaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prestacion = Prestacion::create($request->all());
        $prestacion->save();

        return redirect()->route('proyectos-epc-prestaciones.index');
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
        $prestacion = Prestacion::findOrFail($id);

        return view('admin.proyectos.epc.prestaciones.edit', get_defined_vars());
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
        $prestacion = Prestacion::findOrFail($id);
        $prestacion->fill($request->all());
        $prestacion->save();

        return redirect()
            ->route('proyectos-epc-prestaciones.index')
            ->with('info', 'Actualizado')
            ->with('typealert', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prestacion = Prestacion::findOrFail($id);
        $prestacion->delete();

        return back()
            ->with('info', 'Eliminado con éxito')
            ->with('typealert', 'success');
    }
}
