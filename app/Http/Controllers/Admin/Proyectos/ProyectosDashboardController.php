<?php

namespace App\Http\Controllers\Admin\Proyectos;

use App\Http\Controllers\Controller;
use App\Models\Proyectos\ProyectoInstitucional;
use App\Admin\Planificacion\Pei\PeiProfile;

class ProyectosDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // KPIs globales
        $total       = ProyectoInstitucional::count();
        $activos     = ProyectoInstitucional::activos()->count();
        $enEjecucion = ProyectoInstitucional::enEjecucion()->count();
        $finalizados = ProyectoInstitucional::where('estado', 'finalizado')->count();

        $presupuestoTotal     = ProyectoInstitucional::sum('presupuesto_aprobado');
        $presupuestoEjecutado = ProyectoInstitucional::sum('presupuesto_ejecutado');
        $pctEjecucionPres     = $presupuestoTotal > 0
            ? round($presupuestoEjecutado / $presupuestoTotal * 100) : 0;

        $porEstado = ProyectoInstitucional::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')->pluck('total', 'estado');

        // Planes PEI con sus proyectos agrupados
        $accionIds = ProyectoInstitucional::whereNotNull('pei_profile_id')
            ->pluck('pei_profile_id')->unique();

        $planesPei = PeiProfile::whereNull('parent_id')
            ->where('level', 'master')
            ->where('is_active', true)
            ->whereHas('descendants', fn($q) => $q->whereIn('id', $accionIds))
            ->with(['dependency'])
            ->orderByDesc('year_start')
            ->get()
            ->map(function($plan) {
                $accionIdsDelPlan = $plan->descendants()->where('level', 'action')->pluck('id');
                $proyectos = ProyectoInstitucional::whereIn('pei_profile_id', $accionIdsDelPlan)
                    ->with(['peiProfile', 'dependenciaSolicitante'])
                    ->latest()->get();
                $plan->proyectos        = $proyectos;
                $plan->total_proyectos  = $proyectos->count();
                $plan->en_ejecucion     = $proyectos->where('estado', 'en_ejecucion')->count();
                $plan->finalizados      = $proyectos->where('estado', 'finalizado')->count();
                $plan->solicitudes      = $proyectos->where('estado', 'solicitud')->count();
                return $plan;
            });

        // Proyectos sin vincular a ningún plan
        $sinPlan = ProyectoInstitucional::whereNull('pei_profile_id')->latest()->get();

        return view('admin.proyectos.dashboard', compact(
            'total', 'activos', 'enEjecucion', 'finalizados',
            'presupuestoTotal', 'pctEjecucionPres',
            'porEstado', 'planesPei', 'sinPlan'
        ));
    }
}
