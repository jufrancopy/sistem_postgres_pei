<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Admin\Proyecto\EPC\Turno;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $turnos = Turno::nombre($request->nombre)->paginate(10);

        return view('admin.proyectos.epc.turnos.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.proyectos.epc.turnos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $turno = Turno::create($request->all());

        return redirect()->route('proyectos-epc-turnos.index');
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
        $turno = Turno::findOrFail($id);
        
        return view('admin.proyectos.epc.turnos.edit', get_defined_vars());
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
        $turno = Turno::findOrFail($id);
        $turno->fill($request->all())->save();

        return redirect()->route('proyectos-epc-turnos.index')->with('info', 'Actualizado')->with('typealert', 'success');

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
