<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\OtroServicio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class OtroServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $otrosServicios = OtroServicio::all();

        return view('admin.proyectos.epc.otros_servicios.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function get(Request $request)
    {
        $rows = OtroServicio::where(\DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }

    public function getForType(Request $request, $type)
    {

        switch ($type) {
            case 'limpieza':
                $otrosServicios = OtroServicio::where('type', 'limpieza')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'seguridad':
                $otrosServicios = OtroServicio::where('type', 'seguridad')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'gastronomia':
                $otrosServicios = OtroServicio::where('type', 'gastronomia')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'ambulancia':
                $otrosServicios = OtroServicio::where('type', 'ambulancia')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'all':
                $otrosServicios = OtroServicio::orderBy('id', 'DESC')->paginate(10);
                break;
        }

        $data = ['otrosServicios' => $otrosServicios];

        return view('admin.proyectos.epc.otros_servicios.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.otros_servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        OtroServicio::create($request->all());

        return redirect()->route('proyectos-epc-otros_servs.index')
            ->with('info', 'Apoyo Administrativo agregado con éxito');
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
        $otroServicio = OtroServicio::find($id);

        return view('admin.proyectos.epc.otros_servicios.edit', get_defined_vars());
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
        $otroServicio = OtroServicio::find($id);
        $otroServicio->fill($request->all())->save();

        return redirect()->route('proyectos-epc-otros_servs.index')->with('info', 'Apoyo Administrativo editado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $otroServicio = OtroServicio::find($id)->delete();

        return back()->with('info', 'Otro Servicio se ha eliminado correctamente.');
    }
}
