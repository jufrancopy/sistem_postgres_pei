<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Proyecto\EPC\Horario;

use SweetAlert;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $horarios = Horario::paginate(10);

	    return view('admin.proyectos.epc.horarios.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.horarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $horario = Horario::create($request->all());

        return redirect()->route('proyectos-epc-horarios.index')->with('info', 'Horario creado con éxito');
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
        $horario = Horario::findOrFail($id);

        return view('admin.proyectos.epc.horarios.edit', get_defined_vars());
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
        $horario = Horario::findOrFail();
        $horario->fill($request->all())->save();

        return redirect()->route('proyectos-epc-horarios.index')->with('info', 'Horario editado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $horario = Horario::findOrFail($id);
        $horario->delete();
        
        return back()->with('info', 'Horario Eliminado con éxito');
        
    }
}
