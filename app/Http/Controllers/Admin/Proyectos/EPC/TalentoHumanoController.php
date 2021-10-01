<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

use App\Admin\Proyecto\EPC\TalentoHumano;

class TalentoHumanoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TalentoHumano::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editTalentoHumano">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  onclick="alertarEliminacion("data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteTalentoHumano">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.tthh.index');
    }


    public function store(Request $request)
    {
        TalentoHumano::updateOrCreate([
            'id'       => $request->tthh_id,
            'item'     => $request->item,
            'type'     => $request->type,
            'hours'     => $request->hours,
            'cost'     => $request->cost
        ]);


        return response()->json(['info' => 'Item creado correctamente.', 'typealert' => 'success']);
    }

    public function edit($id)
    {
        $tthh = TalentoHumano::find($id);
        return response()->json($tthh);
    }

    public function destroy($id)
    {
        TalentoHumano::find($id)->delete();

        return response()->json(['success' => 'Permiso eliminado correctamente.']);
    }
}
