<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\Globales\PatrimonyProfile;
use App\Models\Patrimony;

class PatrimonyProfileController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->ajax()) {
                $data = PatrimonyProfile::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfilePatrimony" ><i class="far fa-edit"></i></a>';

                        $btn .= ' <a href="' . route('globales.patrimonies.detail-profile', $row->id) . '" data-toggle="tooltip" data-original-title="Show" class="btn btn-info btn-circle detailProfile"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                        $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfilePatrimony"><i class="fa fa-trash" aria-hidden="true"></i></a>';


                        return $btn;
                    })

                    ->addColumn('dependency', function ($row) {
                        return $row->dependency ? $row->dependency->dependency : 'Sin dependencia';
                    })


                    ->rawColumns(['action'])
                    ->make(true);
            }
        }

        return view('admin.globales.patrimonies.profiles', get_defined_vars());
    }

    public function store(Request $request)
    {
        // Validaciones de los campos de entrada
        $request->validate([
            'dependency_id'      => 'required',
            'description'        => 'required',
        ], [
            'dependency_id.required' => 'Indique Dependencia como Perfil de Patrimonio',
            'description.required'   => 'Debe realizar una breve description del contexto de su Perfil de Patrimonio',
        ]);

        // Inserción o actualización en la base de datos
        $patrimony = PatrimonyProfile::updateOrCreate(
            ['id' => $request->patrimony_profile_id],
            [
                'dependency_id' => $request->dependency_id,
                'description' => $request->description,
            ]
        );

        // Devolver respuesta (puedes ajustar según sea necesario)
        return response()->json(['success' => 'Nuevo Pefirl de Patrimonio creado exitosamente', 'patrimony' => $patrimony]);
    }

    public function detailPatrimonyProfile(Request $request, $idPatrimonyProfile)
    {
        $patrimonyProfile = PatrimonyProfile::findOrFail($idPatrimonyProfile);
        $dependecyId = $patrimonyProfile->dependency_id;

        if ($request->ajax()) {
            $data = Patrimony::where('dependency_id', $dependecyId)->get();
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

        $departments = DB::table('localities')
            ->select(DB::raw('count(*) as states, desc_dpto'))
            ->groupBy('desc_dpto')
            ->pluck('desc_dpto', 'desc_dpto');

        return view('admin.globales.patrimonies.index', get_defined_vars());
    }
}
