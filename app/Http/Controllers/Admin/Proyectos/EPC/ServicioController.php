<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Servicio;
use App\Admin\Proyecto\EPC\Equipamiento;
use App\Admin\Proyecto\EPC\TalentoHumano;
use App\Admin\Proyecto\EPC\MedicamentoInsumo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $equipamientos = Equipamiento::orderBy('id', 'ASC')->pluck('item', 'id');
        $tthh = TalentoHumano::orderBy('id', 'ASC')->pluck('item', 'id');
        $medicamentoInsumos = MedicamentoInsumo::orderBy('id', 'ASC')->pluck('item', 'id');
        
        
        $equipamientosChecked = [];
        $tthhChecked = [];
        $medicamentoInsumosChecked = [];

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
        $rules = [
            'item'                          => 'required|min:8',
            'type'                          => 'required',
            'description'                   => 'required',
            'detail_equipamiento_id'          => 'required'
        ];

        $messages = [
            'item.required'                 => 'El nombre de contener al menos 8 caracteres',
            'item.min'                      => 'El nombre de contener al menos 8 caracteres',
            'type.required'                 => 'Debe especificar el tipo.',
            'description.required'          => 'Describa los detalles del Servicio',
            'detail_equipamiento_id.required' => 'Debe asignar Equipamientos al Servicio',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('proyectos-epc-servicios/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        
        $servicio = Servicio::create($request->except(['detail_equipamiento_id', 'detail_tthh_id']));

        $servicio->equipamientos()->attach($request->detail_equipamiento_id);
        $servicio->tthh()->attach($request->detail_tthh_id);

        return redirect()->route('proyectos-epc-servicios.index')
            ->with('success', 'Servicio creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $servicio = Servicio::find($id);
        
        return view('admin.proyectos.epc.servicios.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $servicio = Servicio::find($id);
        
        $equipamientos = Equipamiento::orderBy('id', 'ASC')->pluck('item', 'id');
        $tthh = TalentoHumano::orderBy('id', 'ASC')->pluck('item', 'id');
        
        $equipamientosChecked = [];
        $tthhChecked = [];

        foreach ($servicio->equipamientos as $v) {
            $equipamientosChecked[] = $v->id;
        }

        foreach ($servicio->tthh as $v) {
            $tthhChecked[] = $v->id;
        }

        return view('admin.proyectos.epc.servicios.edit', get_defined_vars());
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
        $servicio = Servicio::find($id);
        $servicio->fill($request->except(['detail_equipamiento_id']))->save();
        $servicio->equipamientos()->sync($request->detail_equipamiento_id);
        

        return redirect()->route('proyectos-epc-servicios.index')
            ->with('info', 'Servicio Actualizado Satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio = Servicio::find($id);
        $servicio->delete();

        return back()->with('info', 'Servicio eliminado de la Base de Datos');
    }
}
