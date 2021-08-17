<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\MedicamentoInsumo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedicamentoInsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $medicamentoInsumos = MedicamentoInsumo::all();

        return view('admin.proyectos.epc.medicamento_insumos.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getForType(Request $request, $type)
    {

        switch ($type) {
            case 'medicamento':
                $medicamentoInsumos = MedicamentoInsumo::where('type', 'medicamento')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'insumo':
                $medicamentoInsumos = MedicamentoInsumo::where('type', 'insumo')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'util_de_laboratorio':
                $medicamentoInsumos = MedicamentoInsumo::where('type', 'util_de_laboratorio')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'material_quirurgico':
                $medicamentoInsumos = MedicamentoInsumo::where('type', 'material_quirurgico')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'producto_quimico':
                $medicamentoInsumos = MedicamentoInsumo::where('type', 'producto_quimico')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'all':
                $medicamentoInsumos = MedicamentoInsumo::orderBy('id', 'DESC')->paginate(10);
                break;
        }

        $data = ['medicamentoInsumos' => $medicamentoInsumos];

        return view('admin.proyectos.epc.medicamento_insumos.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.medicamento_insumos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MedicamentoInsumo::create($request->all());

        return redirect()->route('proyectos-epc-mds_ins.index')
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
        $medicamentoInsumo = MedicamentoInsumo::find($id);

        return view('admin.proyectos.epc.medicamento_insumos.edit', get_defined_vars());
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
        $medicamentoInsumo = MedicamentoInsumo::find($id);
        $medicamentoInsumo->fill($request->all())->save();

        return redirect()->route('proyectos-epc-mds_ins.index')->with('info', 'Apoyo Administrativo editado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $medicamentoInsumo = MedicamentoInsumo::find($id)->delete();

        return back()->with('info', 'Item eliminado correctamente.');
    }
}
