<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Infraestructura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class InfraestructuraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $infraestructuras = Infraestructura::all();

        return view('admin.proyectos.epc.infraestructuras.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function get(Request $request)
    {
        $rows = Infraestructura::where(\DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }

    public function getForType(Request $request, $type)
    {

        switch ($type) {
            case 'servicio':
                $infraestructuras = Infraestructura::where('type', 'servicio')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'ambulatorio':
                $infraestructuras = Infraestructura::where('type', 'ambulatorio')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'administrativo':
                $infraestructuras = Infraestructura::where('type', 'administrativo')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'hospitalizacion':
                $infraestructuras = Infraestructura::where('type', 'hospitalizacion')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'quirurgico':
                $infraestructuras = Infraestructura::where('type', 'quirurgico')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'all':
                $infraestructuras = Infraestructura::orderBy('id', 'DESC')->paginate(10);
                break;
        }

        $data = ['infraestructuras' => $infraestructuras];

        return view('admin.proyectos.epc.infraestructuras.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.infraestructuras.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Infraestructura::create($request->all());

        return redirect()->route('proyectos-epc-infraestructuras.index')
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
        $infraestructura = Infraestructura::find($id);
        
        return view('admin.proyectos.epc.infraestructuras.edit', get_defined_vars());
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
        $infraestructura = Infraestructura::find($id);
        $infraestructura->fill($request->all())->save();

        return redirect()->route('proyectos-epc-infraestructuras.index')->with('info', 'Apoyo Administrativo editado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $infraestructura = Infraestructura::find($id)->delete();

        return back()->with('info', 'Apoyo Administrativo eliminado correctamente.');
    }
}
