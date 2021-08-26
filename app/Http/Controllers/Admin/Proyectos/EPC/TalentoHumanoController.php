<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Especialidad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Proyecto\EPC\TalentoHumano;


class TalentoHumanoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tthhs = TalentoHumano::all();

        return view('admin.proyectos.epc.tthh.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $especialidades = Especialidad::orderBy('id', 'ASC')->pluck('item', 'id');
        
        $espcialidadesChecked = [];

        return view('admin.proyectos.epc.tthh.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        TalentoHumano::create($request->all());

        return redirect()->route('proyectos-epc-tthh.index')
            ->with('success', 'Talento Humano agregado con éxito');
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
        $tthh = TalentoHumano::find($id);

        $especialidades = Especialidad::orderBy('id', 'ASC')->pluck('item', 'id');

        
        return view('admin.proyectos.epc.tthh.edit', get_defined_vars());
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
        $tthh = TalentoHumano::find($id);
        $tthh->fill($request->all())->save();

        return redirect()->route('proyectos-epc-tthh.index')->with('info', 'Talento Humano editado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tthh = TalentoHumano::find($id)->delete();

        return back()->with('info', 'Talento Humano eliminado correctamente.');
    }
}
