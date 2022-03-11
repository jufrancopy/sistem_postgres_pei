<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use App\Admin\Proyecto\EPC\Prestacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class PrestacionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Prestacion::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPrestacion">Editar</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '"");"  data-original-title="Delete" class="btn btn-danger btn-sm deletePrestacion">Borrar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.proyectos.epc.prestaciones.index');
    }

    public function store(Request $request)
    {
        $id = $request->prestacion_id;

        $returnValue = Prestacion::updateOrCreate(
            ['id' => $request->prestacion_id],
            [
                'item'      => $request->item,
                'type'      => $request->type,
                'cost'      => $request->cost,
                'detail'    => $request->detail,
            ]
        );

        return response()->json(['data' => $returnValue, 'id' => $id]);
    }

    public function edit($id)
    {
        $prestacion = Prestacion::find($id);

        return response()->json($prestacion);
    }

    public function destroy($id)
    {
        Prestacion::find($id)->delete();

        return response()->json();
    }
}
