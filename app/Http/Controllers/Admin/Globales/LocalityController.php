<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use App\Models\Backend\Locality;


class LocalityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Administrador']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Locality::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editLocality"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteLocality"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })


                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.globales.localities.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getCities($state)
    {
        $states = Locality::where('desc_dpto', $state)
            ->select(DB::raw('count(*) as cities, desc_dist'))
            ->groupBy('desc_dist')
            ->get();

        return $states;
    }

    public function getLocalities($city)
    {
        $localities = Locality::where('desc_dist', $city)
            ->select(DB::raw('count(*) as localities, desc_barrio_loc'))
            ->groupBy('desc_barrio_loc')
            ->get();

        return $localities;
    }

    public function edit($id)
    {
        $localities = Locality::findOrFail($id);

        return response()->json($localities);
    }


    public function destroy(Request $request, $id)
    {
        $locality = Locality::find($id)->delete();

        return response()->json([$locality]);
    }
}
