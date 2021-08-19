<?php

namespace App\Http\Controllers\Admin\Globales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Globales\Organigrama;

class OrganigramaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dependencias = Organigrama::whereNull('dependency_id')
            ->with('childrenDependencies')
            ->get();
        
        return view('admin.globales.organigramas.organigrama', get_defined_vars());
    }

    public function listaOrganigramas(){

        $dependencias = Organigrama::whereNull('dependency_id')->paginate(10);

        return view('admin.globales.organigramas.index', get_defined_vars())
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

    public function crearSubDependencia(Request $request, $idDependencia)
    {
        $dependencia_id = $request->idDependencia;
        $dependencia = Organigrama::where('dependency_id', $dependencia_id)->get();
        
        
        return view('admin.globales.organigramas.crear_sub_dependencia', get_defined_vars());

    }

    public function editarSubDependencia(Request $request, $idDependencia)
    {
        $idDependencia = $request->idDependencia;
        $dependencia = Organigrama::find($idDependencia);
        $dependencia_id = $dependencia->dependency_id;
        
        return view('admin.globales.organigramas.editar_sub_dependencia', get_defined_vars());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $dependencia = Organigrama::orderBy('id', 'ASC')->get();

        return view('admin.globales.organigramas.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dependencia = Organigrama::create($request->all());
        
        return back()->with('success', 'Dependencia creada correctamente.');
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dependencias = Organigrama::where('dependency_id', '=', $id)
            ->with('childrenDependencies')
            ->get();
        $id = $id;
        
        return view('admin.globales.organigramas.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dependencia = Organigrama::find($id);
        $idDependencia = $id;

        return view('admin.globales.organigramas.edit', get_defined_vars());
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
        $dependencia = Organigrama::find($id);
        $dependencia->fill($request->all())->save();
        
        return back()->with('success', 'Dependencia actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Organigrama::find($id)->delete();

        return back()->with('success', 'Dependencia eliminada correctamente.');
    }
}
