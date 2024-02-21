<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use App\Models\Patrimony;


class PatrimonyController extends Controller
{
    public function index(Request $request){
        
        // return Patrimony::all();
        if ($request->ajax()) {
            $data = Patrimony::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editPatrimony"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Show" class="btn btn-info btn-circle showDetailPatrimony"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deletePatrimony"><i class="fa fa-trash" aria-hidden="true"></i></a>';

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

    public function show(){
        return Patrimony::all();
    }
}
