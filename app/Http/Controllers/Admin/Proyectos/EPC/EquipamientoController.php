<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Equipamiento;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EquipamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $equipamientos = Equipamiento::item($request->item)->paginate(10);

        return view('admin.proyectos.epc.equipamientos.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
        }

    public function get(Request $request)
    {
        $rows = Equipamiento::where(DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }


    public function getForType($type)
    {
        switch ($type) {
            case 'equipo_informatico':
                $equipamientos = Equipamiento::where('type', 'equipo_informatico')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'equipo_biomedico':
                $equipamientos = Equipamiento::where('type', 'equipo_biomedico')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'equipo_mobiliario':
                $equipamientos = Equipamiento::where('type', 'equipo_mobiliario')->orderBy('id', 'DESC')->paginate(10);
                break;
            case 'all':
                $equipamientos = Equipamiento::orderBy('id', 'DESC')->paginate(10);
                break;
        }

        $data = ['equipamientos' => $equipamientos];

        return view('admin.proyectos.epc.equipamientos.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.proyectos.epc.equipamientos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Equipamiento::create($request->all());

        return redirect()
            ->route('proyectos-epc-equipamientos.index')
            ->with('info', 'Ítem creado con éxito')
            ->with('typealert', 'success');
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
        $equipamiento = Equipamiento::find($id);

        return view('admin.proyectos.epc.equipamientos.edit', get_defined_vars())
            ->with('info', 'Ítem editado con éxito')
            ->with('typealert', 'success');
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
        $equipamiento = Equipamiento::find($id);
        $equipamiento->fill($request->all())->save();

        return redirect()
            ->route('proyectos-epc-equipamientos.index')
            ->with('info', 'Ítem Actualizado con éxito')
            ->with('typealert', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipamiento = Equipamiento::find($id)->delete();

        return back()
            ->with('info', 'Ítem eliminado con éxito')
            ->with('typealert', 'success');
    }
}
