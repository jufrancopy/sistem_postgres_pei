<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables as DataTables;

use App\Admin\Proyecto\EPC\TalentoHumano;

class TalentoHumanoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TalentoHumano::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editTalentoHumano">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deleteTalentoHumano">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.tthh.index');
    }

    public function get(Request $request)
    {
        $rows = TalentoHumano::where(DB::raw("lower(item)"), 'like', '%' . mb_strtolower($request->q) . '%')
            ->select('item AS text', 'id')
            ->orderBy('item')->limit(25)->get();

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $id = $request->tthh_id;

        $returnValue = TalentoHumano::updateOrCreate(
            ['id' => $request->tthh_id],
            [
                'item'     => $request->item,
                'type'     => $request->type,
                'hours'    => $request->hours,
                'cost'     => $request->cost
            ]
        );

        return response()->json(['data' => $returnValue, 'id' => $id]);
    }

    public function edit($id)
    {
        $tthh = TalentoHumano::find($id);

        return response()->json($tthh);
    }

    public function destroy($id)
    {
        TalentoHumano::find($id)->delete();

        return response()->json();
    }
}
