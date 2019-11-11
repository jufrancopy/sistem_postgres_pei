<?php

namespace App\Http\Controllers\Admin\Globales\Formulario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Globales\Formulario\Item;
use App\Admin\Globales\Formulario\Variable;

class VariableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $variables = Variable::variable($request->get('variable'))->orderBy('id', 'ASC')->paginate(10);

        return view('admin.globales.formularios.variables.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) -1 ) *5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.globales.formularios.variables.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $variable = Variable::create($request->all());
        $variable->save();

        return redirect()->route('formulario-variables.index')->with('success', 'Variable agregada a la Base de Datos');
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
        $variable = Variable::find($id);

        return view('admin.globales.formularios.variables.edit', get_defined_vars());
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
        $variable = Variable::find($id);
        $variable->fill($request->all())->save();

        return redirect()->route('formulario-variables.index')
            ->with('success', 'Actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $variable= Variable::find($id)->delete();

        return redirect()->route('formulario-variables.index')->with('success', 'Eliminado correctamente!');
    }
}
