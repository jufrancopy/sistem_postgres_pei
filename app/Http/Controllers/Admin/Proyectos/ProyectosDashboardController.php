<?php

namespace App\Http\Controllers\Admin\Proyectos;

use App\Http\Controllers\Controller;
use App\Models\Proyectos\ProyectoInstitucional;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProyectosDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // ── KPIs generales ────────────────────────────────────────────────────
        $total          = ProyectoInstitucional::count();
        $activos        = ProyectoInstitucional::activos()->count();
        $enEjecucion    = ProyectoInstitucional::enEjecucion()->count();
        $finalizados    = ProyectoInstitucional::where('estado', 'finalizado')->count();
        $sinPei         = ProyectoInstitucional::whereNull('pei_profile_id')->activos()->count();
        $rechazados     = ProyectoInstitucional::whereIn('estado', ['rechazado_docs','rechazado_tecnico'])->count();

        // ── Presupuesto ───────────────────────────────────────────────────────
        $presupuestoTotal    = ProyectoInstitucional::sum('presupuesto_aprobado');
        $presupuestoEjecutado= ProyectoInstitucional::sum('presupuesto_ejecutado');
        $pctEjecucionPres    = $presupuestoTotal > 0
            ? round($presupuestoEjecutado / $presupuestoTotal * 100) : 0;

        // ── Por estado ────────────────────────────────────────────────────────
        $porEstado = ProyectoInstitucional::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // ── Checklist: proyectos con checklist completo ───────────────────────
        $conChecklistCompleto = ProyectoInstitucional::activos()->get()
            ->filter(fn($p) => $p->checklistCompleto())->count();

        // ── Proyectos recientes ───────────────────────────────────────────────
        $recientes = ProyectoInstitucional::with([
            'dependenciaSolicitante', 'peiProfile', 'analista'
        ])->latest()->limit(8)->get();

        // ── Proyectos en ejecución con avance ────────────────────────────────
        $enEjecucionDetalle = ProyectoInstitucional::enEjecucion()
            ->with(['dependenciaEjecutora'])
            ->orderByDesc('avance_pct')
            ->limit(5)->get();

        // ── EPC (Estándares por Complejidad) ──────────────────────────────────
        $totalEspecialidades = DB::connection('pgsql')->table('proyecto.e_p_c_especialidads')->count();
        $totalEquipamientos  = DB::connection('pgsql')->table('proyecto.e_p_c_equipamientos')->count();
        $totalServicios      = DB::table('servicios')->count();

        return view('admin.proyectos.dashboard', compact(
            'total', 'activos', 'enEjecucion', 'finalizados',
            'sinPei', 'rechazados',
            'presupuestoTotal', 'presupuestoEjecutado', 'pctEjecucionPres',
            'porEstado', 'conChecklistCompleto',
            'recientes', 'enEjecucionDetalle',
            'totalEspecialidades', 'totalEquipamientos', 'totalServicios'
        ));
    }
}
