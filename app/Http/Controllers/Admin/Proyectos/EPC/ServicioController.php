<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Servicio;

use App\Admin\Proyecto\EPC\Equipamiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $servicios = Servicio::all();

        return view('admin.proyectos.epc.servicios.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detailEquipamientos = Equipamiento::orderBy('id', 'ASC')->pluck('item', 'id');
        $detailEquipamientosChecked = [];
        
        return view('admin.proyectos.epc.servicios.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'item' => 'required',
            'type' => 'required',
            'description' => 'required',
            'detail_equipamientos' => 'required'
        ]);
        
        $servicio = Servicio::create($request->except(['detail_equipamiento_id']));

        $servicio->fortalezas()->attach($request->fortaleza_id);

        return redirect()->route('users.index')
                        ->with('success','Usuario creado satisfactoriamente');
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
