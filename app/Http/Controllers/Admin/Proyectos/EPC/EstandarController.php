<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Admin\Proyecto\EPC\Estandar;
use App\Admin\Proyecto\EPC\Servicio;

class EstandarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estandares = Estandar::all();

        return view('admin.proyectos.epc.estandares.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }



    public function create()
    {
        return view('admin.proyectos.epc.estandares.create');
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
            'item'                          => 'required',
            'type'                          => 'required',
            'detail'                        => 'required',
        ];

        $messages = [
            'item.required'                 => 'Debe completar el campo Ítem',
            'type.required'                 => 'Debe especificar el tipo.',
            'detail.required'               => 'Describa los detalles del Servicio',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('proyectos-epc-estandares/create')
                ->withErrors($validator)
                ->withInput();
        }

        $servicioCantidadAttach = [];
        foreach ($request->servicios as $key => $servicio) {
            $servicioCantidadAttach[$servicio] = ['cantidad' => $request->cantidadesServicios[$key]];
        }


        $estandar = Estandar::create($request->all());

        $estandar->servicios()->attach($servicioCantidadAttach);

        return redirect()->route('proyectos-epc-estandares.show', $estandar->id)
            ->with('info', 'Estándar creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estandar = Estandar::findOrfail($id);

        
        return view('admin.proyectos.epc.estandares.show', get_defined_vars());
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
