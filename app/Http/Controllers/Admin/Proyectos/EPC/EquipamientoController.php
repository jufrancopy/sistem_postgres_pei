<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables as DataTables;

use App\Admin\Proyecto\EPC\Equipamiento;

class EquipamientoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Equipamiento::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editEquipamiento">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteEquipamiento">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.equipamientos.index');
    }

    public function get(Request $request)
    {
        $rows = Equipamiento::where(DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $id = $request->equipamiento_id;

        $returnValue = Equipamiento::updateOrCreate(
            ['id' => $request->equipamiento_id],
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
        $equipamiento = Equipamiento::find($id);

        return response()->json($equipamiento);
    }

    public function destroy($id)
    {
        Equipamiento::find($id)->delete();

        return response()->json();
    }
}
