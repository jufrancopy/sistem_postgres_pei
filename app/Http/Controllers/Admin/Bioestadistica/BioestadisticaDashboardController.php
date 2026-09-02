<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Dashboards\DashboardService;
use App\Application\Bioestadistica\Reports\PeriodContext;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Indicador;
use App\Application\Bioestadistica\Dictionary\CatalogType;
use App\Models\Bioestadistica\Variable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BioestadisticaDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrador|Analista de Bioestadística|Digitador Bioestadística|Consultor Bioestadística|Auditor Bioestadística');
    }

    public function index(Request $request, DashboardService $service): View
    {
        $closed = PeriodContext::lastClosed();
        $dashboard = $service->resolveForUser($request->user());

        return view('admin.bioestadistica.dashboard', [
            'dashboard' => $dashboard,
            'months' => PeriodContext::MONTHS,
            'period' => [
                'desde' => [
                    'anio' => $request->integer('periodo_desde_anio', $closed['anio']),
                    'mes' => $request->integer('periodo_desde_mes', $closed['mes']),
                ],
                'hasta' => [
                    'anio' => $request->integer('periodo_hasta_anio', $closed['anio']),
                    'mes' => $request->integer('periodo_hasta_mes', $closed['mes']),
                ],
            ],
            'stats' => [
                'formularios' => Formulario::count(),
                'formularios_activos' => Formulario::where('estado', 'activo')->count(),
                'variables' => Variable::count(),
                'prestaciones' => CatalogType::totalItemsCount(),
                'establecimientos' => Establecimiento::count(),
                'distrito_pendiente' => Establecimiento::whereNull('distrito_id')->count(),
                'indicadores' => Indicador::count(),
                'indicadores_activos' => Indicador::where('activo', true)->count(),
            ],
        ]);
    }
}
