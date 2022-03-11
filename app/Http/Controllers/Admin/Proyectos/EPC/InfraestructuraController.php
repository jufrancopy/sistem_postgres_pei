<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as DataTables;
use Illuminate\Support\Facades\DB;

use App\Admin\Proyecto\EPC\Infraestructura;

class InfraestructuraController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Infraestructura::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editInfraestructura">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteInfraestructura">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.infraestructuras.index');
    }

    public function get(Request $request)
    {
        $rows = Infraestructura::where(DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $id = $request->infraestructura_id;

        $returnValue = Infraestructura::updateOrCreate(
            ['id' => $request->infraestructura_id],
            [
                'item'              => $request->item,
                'type'              => $request->type,
                'measurement'       => $request->measurement,
                'cost'              => $request->cost
            ]
        );

        return response()->json(['data' => $returnValue, 'id' => $id]);
    }

    public function edit($id)
    {
        $infraestructura = Infraestructura::find($id);

        return response()->json($infraestructura);
    }

    public function destroy($id)
    {
        Infraestructura::find($id)->delete();

        return response()->json();
    }
}
