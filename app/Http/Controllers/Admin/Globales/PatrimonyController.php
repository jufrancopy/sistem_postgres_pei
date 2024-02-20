<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use App\Models\Patromony;


class PatrimonyController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Patromony::get();
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

        $states = DB::table('localities')
            ->select(DB::raw('count(*) as states, desc_dpto'))
            ->groupBy('desc_dpto')
            ->pluck('desc_dpto', 'desc_dpto');

        return view('admin.globales.patrimonies.index', get_defined_vars());
    }
}
