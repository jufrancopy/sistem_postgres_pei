<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Especialidad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DataTables;
use Redirect, Response;

use App\Admin\Proyecto\EPC\TalentoHumano;
use Yajra\DataTables\Contracts\DataTable;

class TalentoHumanoController extends Controller
{
public function index(Request $request)
     {
         $tthhs = TalentoHumano::item($request->input('item'))->get();

         return view('admin.proyectos.epc.tthh.index', get_defined_vars())
             ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = TalentoHumano::latest()->get();
            
    //         return DataTable::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {

    //                 $action = '<a class="btn btn-info" id="show-user" data-toggle="modal" data-id=' . $row->id . '>Show</a>
    //     <a class="btn btn-success" id="edit-user" data-toggle="modal" data-id=' . $row->id . '>Edit </a>
    //     <meta name="csrf-token" content="{{ csrf_token() }}">
    //     <a id="delete-user" data-id=' . $row->id . ' class="btn btn-danger delete-user">Delete</a>';

    //                 return $action;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }

    //     return view('admin.proyectos.epc.tthh.index');
    // }

    public function getData()
    {
        $query = TalentoHumano::select('id', 'item', 'type', 'cost', 'hours');

        return datatables()->of(TalentoHumano::all())->toJson();
    }
    public function get(Request $request)
    {
        $rows = TalentoHumano::where(DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')
            ->limit(25)
            ->get();

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

        return redirect()
            ->route('proyectos-epc-tthh.index')
            ->with('info', 'Ítem creado con éxito')
            ->with('typealert', 'success');
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

        return redirect()
            ->route('proyectos-epc-tthh.index')
            ->with('info', 'Ítem actualizado con éxito')
            ->with('typealert', 'success');
    }

    public function destroy($id)
    {
        $tthh = TalentoHumano::find($id)->delete();

        return back()
            ->with('info', 'Ítem eliminado con éxito')
            ->with('typealert', 'success');
    }
}
