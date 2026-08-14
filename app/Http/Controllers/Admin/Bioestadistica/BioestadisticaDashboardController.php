<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Catalogo;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDefinition;

class BioestadisticaDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrador|Analista de Bioestadística|Digitador Bioestadística|Consultor Bioestadística|Auditor Bioestadística');
    }

    public function index()
    {
        return view('admin.bioestadistica.dashboard', [
            'stats' => [
                'formularios' => Formulario::count(),
                'formularios_activos' => Formulario::where('estado', 'activo')->count(),
                'catalogos' => Catalogo::count(),
                'variables' => VariableDefinition::count(),
                'establecimientos' => Establecimiento::count(),
                'distrito_pendiente' => Establecimiento::whereNull('distrito_id')->count(),
            ],
        ]);
    }
}
