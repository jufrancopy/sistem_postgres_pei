<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;


use App\Admin\Proyecto\EPC\Especialidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $especialidades = Especialidad::all();

        return view('admin.proyectos.epc.especialidades.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getForType(Request $request, $type)
    {

        switch ($type) {
            case 'primer_nivel':
                $especialidades = Especialidad::where('type', 'primer_nivel')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'segundo_nivel':
                $especialidades = Especialidad::where('type', 'segundo_nivel')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'tecer_nivel':
                $especialidades = Especialidad::where('type', 'tecer_nivel')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'all':
                $especialidades = Especialidad::orderBy('id', 'DESC')->paginate(10);
                break;
        }

        $data = ['especialidades' => $especialidades];

        return view('admin.proyectos.epc.especialidades.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.especialidades.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Especialidad::create($request->all());

        return redirect()->route('proyectos-epc-especialidades.index')
            ->with('info', 'Especialidad agregada con éxito');
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
        $especialidad = Especialidad::find($id);

        return view('admin.proyectos.epc.especialidades.edit', get_defined_vars());
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
        $especialidad = Especialidad::find($id);
        $especialidad->fill($request->all())->save();

        return redirect()->route('proyectos-epc-especialidades.index')->with('info', 'Especialidad editada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $especialidad = Especialidad::find($id)->delete();

        return back()->with('info', 'Especialidad eliminada correctamente.');
    }
}
