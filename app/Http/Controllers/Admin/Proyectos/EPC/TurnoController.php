<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Turno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class TurnoController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Turno::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editTurno">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteTurno">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.turnos.index');
    }

    public function store(Request $request)
    {
        $id = $request->turno_id;

        $returnValue = Turno::updateOrCreate(
            ['id' => $request->turno_id],
            [
                'item'     => $request->item,
            ]
        );

        return response()->json(['data' => $returnValue, 'id' => $id]);
    }

    public function edit($id)
    {
        $turno = Turno::find($id);

        return response()->json($turno);
    }

    public function destroy($id)
    {
        Turno::find($id)->delete();

        return response()->json();
    }
}
