<?php

namespace App\Http\Controllers\Admin\Planificacion\Monitoreo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Planificacion\Monitoreo\TipoEvaluacion;

class TipoEvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        {
            $tipoevaluaciones= TipoEvaluacion::nombre($request->get('nombre'))->orderBy('id', 'DESC')->paginate(10);
            
            return view('admin.planificacion.monitoreo.tipo_evaluaciones.index', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.planificacion.monitoreo.tipo_evaluaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipoEvaluacion= TipoEvaluacion::create($request->all());
        
        return redirect()->route('monitoreo-tipo_evaluaciones.index')
            ->with('success','Tipo de Evaluacón creada satisfactoriamente');
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
        $tipoEvaluacion = TipoEvaluacion::find($id);

        return view('admin.planificacion.monitoreo.tipo_evaluaciones.edit', get_defined_vars());
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
        $tipoEvaluacion = TipoEvaluacion::find($id);
        $tipoEvaluacion->fill($request->all())->save();

        return redirect()->route('monitoreo-tipo_evaluaciones.index')
            ->with('success', 'Tipo de Evaluación actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoEvaluacion = TipoEvaluacion::find($id)->delete();
        return redirect()->route('monitoreo-tipo_evaluaciones.index')
            ->with('success', 'Eliminado satisfactoriamente');
    }
}
