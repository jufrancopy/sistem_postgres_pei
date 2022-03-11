<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables as DataTables;

use App\Admin\Proyecto\EPC\OtroServicio;


class OtroServicioController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = OtroServicio::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editOtroServicio">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteOtroServicio">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.otros_servicios.index');
    }

    public function get(Request $request)
    {
        $rows = OtroServicio::where(DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $id = $request->otroServicio_id;

        $returnValue = OtroServicio::updateOrCreate(
            ['id' => $request->otroServicio_id],
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
        $otroServicio = OtroServicio::find($id);

        return response()->json($otroServicio);
    }

    public function destroy($id)
    {
        OtroServicio::find($id)->delete();

        return response()->json();
    }
}
