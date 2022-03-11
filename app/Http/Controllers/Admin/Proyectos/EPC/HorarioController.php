<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Horario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Horario::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editHorario">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteHorario">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.horarios.index');
    }

    public function store(Request $request)
    {
        $id = $request->horario_id;

        $returnValue = Horario::updateOrCreate(
            ['id' => $request->horario_id],
            [
                'item'          => $request->item,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time
            ]
        );

        return response()->json(['data' => $returnValue, 'id' => $id]);
    }

    public function edit($id)
    {
        $horario = Horario::find($id);

        return response()->json($horario);
    }

    public function destroy($id)
    {
        Horario::find($id)->delete();

        return response()->json();
    }
}
