<?php

namespace App\Http\Controllers\Admin\Globales\Formulario;

use App\Admin\Globales\EstructuraControl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Globales\Formulario\Formulario;
use App\Admin\Globales\Formulario\Item;
use App\Admin\Globales\Formulario\Variable;
use App\Admin\Globales\Organigrama;

class FormularioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $formularios = Formulario::orderBy('id', 'ASC')->paginate(10);
        
        return view ('admin.globales.formularios.formularios.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $variables = Variable::orderBy('id', 'ASC')->pluck('variable', 'id');
        $variablesChecked = [];
        $dependencias = Organigrama::orderBy('id', 'ASC')->pluck('dependency', 'id');
        
        return view ('admin.globales.formularios.formularios.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $formulario = Formulario::create($request->except(['variable_id']));
        $formulario->variables()->attach($request->variable_id);

        return redirect()->route('formulario-formularios.index')
            ->with('success', 'Formulario creado exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $formulario = Formulario::find($id);

        return view('admin.globales.formularios.formularios.show', get_defined_vars())
        ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formulario=Formulario::find($id);
        $variables=Variable::orderBy('id', 'ASC')->pluck('variable', 'id');
        $dependencias = Organigrama::orderBy('id', 'ASC')->pluck('dependency', 'id');
        
        $variablesChecked = [];
        foreach($formulario->variables as $variable){
            $variablesChecked[] = $variable->id;
        }
        
        $dependenciasChecked = [];
        // foreach($formulario->childrenDependencies as $dependencia){
        //     $dependenciasChecked[] = $dependencia->childrenDependencies->;
        // }
        // return $dependenciasChecked;
        
        
        return view ('admin.globales.formularios.formularios.edit', get_defined_vars());
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
        $formulario = Formulario::find($id);
        $formulario->variables()->sync($request->variable_id);
        
        $formulario->fill($request->except(['variable_id']))->save();

        return redirect()->route('formulario-formularios.index')
            ->with('success', 'Formulario actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $formulario = Formulario::find($id);
        $formulario->delete();

        return redirect()->route('formulario-formularios.index');
    }
}
