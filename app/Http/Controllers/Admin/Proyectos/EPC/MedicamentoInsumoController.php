<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\MedicamentoInsumo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\DataTables\DataTables as DataTables;

class MedicamentoInsumoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MedicamentoInsumo::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editMedicamentoInsumo">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteMedicamentoInsumo">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.medicamentos_insumos.index');
    }

    public function store(Request $request)
    {
        $id = $request->medicamentoInsumo_id;

        $returnValue = MedicamentoInsumo::updateOrCreate(
            ['id' => $request->medicamentoInsumo_id],
            [
                'item'     => $request->item,
                'type'     => $request->type,
                'cost'     => $request->cost
            ]
        );

        return response()->json(['data' => $returnValue, 'id' => $id]);
    }

    public function edit($id)
    {
        $medicamentoInsumo = MedicamentoInsumo::find($id);

        return response()->json($medicamentoInsumo);
    }

    public function destroy($id)
    {
        MedicamentoInsumo::find($id)->delete();

        return response()->json();
    }
}
