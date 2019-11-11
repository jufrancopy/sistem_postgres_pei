<?php

namespace App\Http\Controllers\Admin\Globales\Formulario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Globales\Formulario\Item;
use App\Admin\Globales\Formulario\Variable;
use App\Admin\Globales\Organigrama;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Item::item($request->get('item'))->orderBy('id', 'DESC')->paginate(10);
        
        return view ('admin.globales.formularios.items.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function itemsVariable(Request $request, $idVariable)
    {
        $items = Item::where('variable_id', $idVariable)->paginate(10);
        $idVariable = $request->idVariable;
        $variable = Variable::find($idVariable);

        
        return view('admin.globales.formularios.items.index', get_defined_vars())
        ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function agregarItem (Request $request, $idVariable){
        
        $idVariable = $request->idVariable;
        $variable = Variable::where('id', $idVariable)->first();
        
        return view('admin.globales.formularios.items.create', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $variable = Variable::orderBy('id', 'ASC')->pluck('variable', 'id');
        
        return view('admin.globales.formularios.items.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = Item::create($request->all());
        $idVariable = $item->variable_id;
        $items = Item::where('id', $idVariable)->paginate(10);

        return redirect()->route('formulario-variables-items', $idVariable);
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
        $item = Item::find($id);
        $idVariable = $item->variable_id;
        $variable = Variable::where('id', $idVariable)->first();    

        return view ('admin.globales.formularios.items.edit', get_defined_vars());
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
        $item = Item::find($id);
        $item->fill($request->all())->save();

        return redirect()->route('formulario-items.index')->with('success', 'Actualización exitosa ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $variableId = Item::find($id);
        $idVariable = $variableId->variable_id;
        $item = Item::find($id)->delete();
        
        return redirect()->route('formulario-variables-items', $idVariable);
    }
}
