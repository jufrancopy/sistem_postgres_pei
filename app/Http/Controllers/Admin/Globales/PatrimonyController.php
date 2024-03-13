<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;

use App\Models\Patrimony;


class PatrimonyController extends Controller
{
    public function index(Request $request)
    {
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

        $departments = DB::table('localities')
            ->select(DB::raw('count(*) as states, desc_dpto'))
            ->groupBy('desc_dpto')
            ->pluck('desc_dpto', 'desc_dpto');

        return view('admin.globales.patrimonies.index', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'              => 'required',
            'department'        => 'required',
            'mainPhotoFile'     => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'evidenceFile'      => 'nullable|mimes:pdf|max:2048', // Validación para PDF
        ], [
            'type.required'         => 'Indique el Tipo de Inmueble',
            'department.required'   => 'Indique en qué Departamento se ubica el Inmueble',
            'mainPhotoFile.image'   => 'El archivo principal debe ser una imagen',
            'mainPhotoFile.mimes'   => 'El archivo principal debe ser de tipo: jpeg, png, jpg, gif',
            'mainPhotoFile.max'     => 'El archivo principal no debe ser mayor de 2MB',
            'evidenceFile.mimes'    => 'El archivo de evidencia debe ser de tipo PDF',
            'evidenceFile.max'      => 'El archivo de evidencia no debe ser mayor de 2MB',
        ]);

        // Image Validations
        if ($request->file('mainPhotoFile')) {
            $mainPhotoFile = '/' . date('d-m-Y');
            $mainPhotoExt = trim($request->file('mainPhotoFile')->getClientOriginalExtension());
            $evidenceUploadPath = Config::get('filesystems.disks.patrimonies.root');
            $name = Str::slug(str_replace($mainPhotoExt, '', $request->file('mainPhotoFile')->getClientOriginalName()));
            $mainPhotoFileName = rand(1, 999) . '-' . $name . '.' . $mainPhotoExt;
            $mainPhotoName = $evidenceUploadPath . '/' . $mainPhotoFile . '/' . $mainPhotoFileName;
            $mainPhotoPath = date('d-m-Y');
        } else {
            // Insertion to DB
            $patrimony = Patrimony::findOrFail($request->patrimony_id);
            $mainPhotoPath = $patrimony->main_photo_file; //crear migracion
            $mainPhotoName = $patrimony->main_photo_file_path;
        }

        $pdfFile = $request->file('evidenceFile');

        // Verificar si se ha cargado correctamente
        if ($pdfFile !== null) {
            if ($pdfFile->isValid()) {
                // Generar un nombre único para el archivo PDF
                $pdfFileName = time() . '_' . $pdfFile->getClientOriginalName();
                $pdfFilePath = date('d-m-Y');

                // Guardar el archivo PDF en el sistema de archivos de Laravel (en la carpeta de almacenamiento 'public', por ejemplo)
                $pdfFile->storeAs('pdfs/patrimonies', $pdfFileName);

                $patrimony = Patrimony::updateOrCreate(
                    ['id' => $request->patrimony_id],
                    [
                        'type' => $request->type,
                        'quantity_account_current' => $request->quantityAccountCurrent,
                        'detail_location' => $request->detailLocation,
                        'estate_quantity' => $request->estateQuantity,
                        'department' => $request->department,
                        'city' => $request->city,
                        'locality' => $request->locality,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'location_address' => $request->locationAddress,
                        'infrastructure_type' => $request->infrastructureType,
                        'description' => $request->description,
                        'registry_number' => $request->registryNumber,
                        'estate_status' => $request->estateStatus,
                        'committed_investment' => $request->committedInvestment,
                        'transfer' => $request->transfer,
                        'balance_for_transfer' => $request->balanceForTransfer,
                        'tenant' => $request->tenant,
                        'rent_amount' => $request->rentAmount,
                        'contract_resolution' => $request->contractResolution,
                        'contract_number' => $request->contractNumber,
                        'current_period_start' => $request->currentPeriodStart,
                        'current_period_end' => $request->currentPeriodEnd,
                        'status_documentation' => $request->statusDocumentation,
                        'land_area_mt2' => $request->landAreaMt2,
                        'land_area_hectares' => $request->landAreaHectares,
                        'land_sub_area' => $request->landSubArea,
                        'built_area_m2' => $request->landAreaMt2,
                        'built_value_gs' => $request->builtValueGs,
                        'property_value_gs' => $request->property_value_gs,
                        'total_value_gs' => $request->total_value_gs,
                        'possession_rent_without_title' => $request->possession_rent_without_title,
                        'main_photo_file' => $mainPhotoName,
                        'main_photo_file_path' => $mainPhotoPath,
                        'evidence_file' => $pdfFileName,
                        'evidence_file_path' => $pdfFilePath,
                        
                    ]
                );
            }
        }
    }

    public function mapPais()
    {
        $patrimonies = Patrimony::all();
        $map_makes = array();
        foreach ($patrimonies as $key => $patrimony) {
            $map_makes[] = (object)array(
                'type' => $patrimony->type,
                'quantityAccount' => $patrimony->quantityAccount,
                'detailLocation' => $patrimony->detailLocation,
                'estateQuantity' => $patrimony->estateQuantity,
                'department' => $patrimony->department,
                'city' => $patrimony->city,
                'locality' => $patrimony->locality,
                'department' => $patrimony->department,
                'latitude' => $patrimony->latitude,
                'longitude' => $patrimony->longitude,
            );
        }

        return response()->json($map_makes);
    }

    public function show($id)
    {
        $patrimony = Patrimony::findOrFail($id);

        return response()->json($patrimony);
    }
}
