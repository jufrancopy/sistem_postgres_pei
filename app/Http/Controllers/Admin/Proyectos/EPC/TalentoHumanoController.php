<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Especialidad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Proyecto\EPC\TalentoHumano;


class TalentoHumanoController extends Controller
{
    public function index(Request $request)
    {
        $tthhs = TalentoHumano::all();

        return view('admin.proyectos.epc.tthh.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function get(Request $request)
    {
        $rows = TalentoHumano::where(\DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }

    public function create()
    {
        $especialidades = Especialidad::orderBy('id', 'ASC')->pluck('item', 'id');

        $espcialidadesChecked = [];

        return view('admin.proyectos.epc.tthh.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        TalentoHumano::create($request->all());

        return redirect()->route('proyectos-epc-tthh.index')
            ->with('success', 'Talento Humano agregado con éxito');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $tthh = TalentoHumano::find($id);
        $especialidades = Especialidad::orderBy('id', 'ASC')->pluck('item', 'id');

        return view('admin.proyectos.epc.tthh.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $tthh = TalentoHumano::find($id);
        $tthh->fill($request->all())->save();

        return redirect()->route('proyectos-epc-tthh.index')->with('info', 'Talento Humano editado con éxito');
    }

    public function destroy($id)
    {
        $tthh = TalentoHumano::find($id)->delete();

        return back()->with('info', 'Talento Humano eliminado correctamente.');
    }
}
