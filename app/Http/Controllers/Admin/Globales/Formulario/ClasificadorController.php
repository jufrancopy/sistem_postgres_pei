<?php

namespace App\Http\Controllers\Admin\Globales\Formulario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Globales\Formulario\Clasificador;

class ClasificadorController extends Controller
{
    public function index(Request $request)
    {
        $clasificadores = Clasificador::whereNull('clasificador_id')
            ->with('childrenClasificadores')
            ->get();

        return view('admin.globales.formularios.clasificadores', get_defined_vars());
    }

    public function listaClasificadores()
    {

        $clasificadores = Clasificador::whereNull('clasificador_id')->paginate(10);

        return view('admin.globales.formularios.clasificadores.index', get_defined_vars())
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function verOrganigrama($id)
    {
        $dependencias = Organigrama::whereNull('dependency_id')
            ->where('id', $id)
            ->with('childrenDependencies')
            ->get();

        return view('admin.globales.organigramas.organigrama', get_defined_vars());
    }

    public function crearSubClasificador(Request $request, $idClasificador)
    {
        $clasificador_id = $request->idClasificador;
        $clasificador = Clasificador::where('clasificador_id', $clasificador_id)->get();

        return view('admin.globales.formularios.clasificadores.crear_sub_clasificador', get_defined_vars());
    }

    public function editarSubClasificador(Request $request, $idClasificador)
    {
        $idClasificador = $request->idClasificador;
        $clasificador = Clasificador::find($idClasificador);
        $clasificador_id = $clasificador->clasificador_id;

        return view('admin.globales.formularios.clasificadores.editar_sub_clasificador', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $clasificador = Clasificador::orderBy('id', 'ASC')->get();

        return view('admin.globales.formularios.clasificadores.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clasificador = Clasificador::create($request->all());
        $idClasificador = $clasificador->clasificador_id;

        if (is_null($idClasificador)) {
            return redirect()->route('formulario-clasificadores-listado')
                ->with('success', 'Clasificador eliminado correctamente.');
        } else {
            return redirect()->route('formulario-clasificadores.show', $idClasificador)
                ->with('success', 'Clasificador creado correctamente.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clasificadores = Clasificador::where('clasificador_id', '=', $id)
            ->with('childrenClasificadores')
            ->get();

        $clasificador = Clasificador::find($id);


        return view('admin.globales.formularios.clasificadores.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $clasificador = Clasificador::find($id);
        $idDependencia = $id;

        return view('admin.globales.formularios.clasificadores.edit', get_defined_vars());
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
        $clasificador = Clasificador::find($id);
        $clasificador->fill($request->all())->save();

        return redirect()->action('Admin\Globales\Formulario\ClasificadorController@listaClasificadores');
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clasificador = Clasificador::find($id);
        $idClasificador = $clasificador->clasificador_id;
        $clasificador->delete();

        return back()->with('success', 'Eliminado correctamente.');
        
    }
}
