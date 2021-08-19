<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\ApoyoAdministrativo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApoyoAdministrativoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $apoyoAdministrativos = ApoyoAdministrativo::all();
        
        return view('admin.proyectos.epc.apoyo_administrativos.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);

    }

    public function getForType(Request $request, $type)
    {
        
        switch ($type) {
            case 'servicio_agendamiento':
                $apoyoAdministrativos = ApoyoAdministrativo::where('type', 'servicio_agendamiento')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'servicio_archivo_fichero':
                $apoyoAdministrativos = ApoyoAdministrativo::where('type', 'servicio_archivo_fichero')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'servicio_farmacia':
                $apoyoAdministrativos = ApoyoAdministrativo::where('type', 'servicio_farmacia')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'all':
                $apoyoAdministrativos = ApoyoAdministrativo::orderBy('id', 'DESC')->paginate(10);
                break;
        }

        $data = ['apoyoAdministrativos' => $apoyoAdministrativos];

        return view('admin.proyectos.epc.apoyo_administrativos.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.apoyo_administrativos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ApoyoAdministrativo::create($request->all());

        return redirect()->route('proyectos-epc-ap_admins.index')
            ->with('success','Apoyo Administrativo agregado con éxito');
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
        $apoyoAdministrativo = ApoyoAdministrativo::find($id);

        return view('admin.proyectos.epc.apoyo_administrativos.edit', get_defined_vars());
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
        $apoyoAdministrativo=ApoyoAdministrativo::find($id);
        $apoyoAdministrativo->fill($request->all())->save();

        return redirect()->route('proyectos-epc-ap_admins.index')->with('info','Apoyo Administrativo editado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apoyoAdministrativo = ApoyoAdministrativo::find($id)->delete();

        return back()->with('info', 'Apoyo Administrativo eliminado correctamente.');
    }
}
