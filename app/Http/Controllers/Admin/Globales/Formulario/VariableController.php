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

        $variables = Variable::whereIsRoot()->paginate(10);

        return view('admin.globales.formularios.variables.index', get_defined_vars())
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function verVariable($id)
    {
        $variable = Variable::findOrFail($id);

        return view('admin.globales.formularios.variables.variable', get_defined_vars());
    }

    public function crearItem(Request $request, $idVariable)
    {
        $idVariable = $request->idVariable;
        $variable = Variable::find($idVariable);
        $parentId = $variable->first()->id;
        $rootId = Variable::whereAncestorOf($variable)->whereIsRoot()->first()->id;

        return view('admin.globales.formularios.variables.crear_item', get_defined_vars());
    }

    public function editarItem(Request $request, $idVariable)
    {
        $idVariable = $request->idVariable;
        $variable = Variable::find($idVariable);
        $parentId = $variable->first()->id;
        $rootId = Variable::whereAncestorOf($variable)->whereIsRoot()->first()->id;

        return view('admin.globales.formularios.variables.editar_item', get_defined_vars());
    }

    public function create()
    {
        $parents = Variable::pluck('name', 'id');

        return view('admin.globales.formularios.variables.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $variable = Variable::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'type' => $request->type
        ]);

        if ($request->parent_id) {
            $node = Variable::find($request->parent_id);
            $node->appendNode($variable);
        }

        $rootId = Variable::whereAncestorOf($variable)->whereIsRoot()->first()->id;


        if ($variable->parent_id == null) {
            return redirect()->route('globales.gestionar-variable', $variable->id);
        } else
            return redirect()->route('globales.gestionar-variable', $rootId);

        // dd($request);
        // $variable = Variable::create($request->all());
        // $variable->save();

        // return redirect()->route('formulario-variables.index')->with('success', 'Variable agregada a la Base de Datos');
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
        $idVariable = $id;

        return view('admin.globales.formularios.variables.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $variable = Variable::find($id);

        $ancestro = $variable->ancestorsAndSelf($id)->where('parent_id', null)->first();
        $parentRootId = $ancestro->id;
        $parentId = $request->parent_id;

        $variable->fill($request->all())->save();

        if ($variable->id) {
            return redirect()->route('globales.gestionar-variable', $parentRootId);
        } else {
            return back()->with('success', 'Creado correctamente.');
        }
    }

    public function destroy($id)
    {
        $variable = Variable::find($id);
        
        $rootId = Variable::whereAncestorOf($variable)->whereIsRoot()->first()->id;

        $variable->delete();
        
        if ($rootId) {
            return redirect()->route('globales.gestionar-variable', $rootId);
        } else {
            return redirect()->route('globales.variables.index')->with('success', 'Eliminado correctamente!');
        }
        
    }
}
