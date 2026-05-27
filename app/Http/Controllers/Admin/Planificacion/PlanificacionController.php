<?php

namespace App\Http\Controllers\Admin\Planificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Proyectos\ProyectoInstitucional;

class PlanificacionController extends Controller
{
    public function dashboard(Request $request)
    {
        // ── Solo PEIs corporativos (los que consolidan grupos) ────────────────
        $peisCorporativos = PeiProfile::whereIsRoot()
            ->where('type', 'corporative')
            ->whereNotNull('year_start')
            ->orderByDesc('year_start')
            ->get();

        // PEI seleccionado — por defecto el más reciente
        $peiSeleccionadoId = $request->pei_id ?? $peisCorporativos->first()?->id;
        $peiActual = $peiSeleccionadoId
            ? PeiProfile::with(['group', 'analysts'])->find($peiSeleccionadoId)
            : null;

        // ── Métricas del PEI seleccionado ─────────────────────────────────────
        $totalObjetivos  = 0;
        $totalMetas      = 0;
        $totalAcciones   = 0;
        $accionesSinResp = 0;
        $semaforo        = collect();
        $peisRecientes   = collect();

        if ($peiActual) {
            $descendantIds = $peiActual->descendants()->pluck('id');

            $totalObjetivos  = PeiProfile::whereIn('id', $descendantIds)->where('level','axi')->count();
            $totalMetas      = PeiProfile::whereIn('id', $descendantIds)->where('level','goal')->count();
            $totalAcciones   = PeiProfile::whereIn('id', $descendantIds)->where('level','action')->count();
            $accionesSinResp = PeiProfile::whereIn('id', $descendantIds)
                ->where('level','action')->doesntHave('responsibles')->count();

            $semaforo = PeiProfile::whereIn('id', $descendantIds)
                ->where('level','action')->whereNotNull('semaforo')
                ->selectRaw('semaforo, COUNT(*) as total')
                ->groupBy('semaforo')->pluck('total','semaforo');

            // Progreso por objetivo estratégico (nivel axi)
            $peisRecientes = PeiProfile::whereIn('id', $descendantIds)
                ->where('level','axi')
                ->with(['children'])
                ->orderBy('order_item')
                ->limit(8)
                ->get()
                ->map(function($obj) {
                    $acciones = $obj->descendants()->where('level','action')->count();
                    $conSem   = $obj->descendants()->where('level','action')->whereNotNull('semaforo')->count();
                    return [
                        'pei'          => $obj,
                        'total_acc'    => $acciones,
                        'con_semaforo' => $conSem,
                        'pct'          => $acciones > 0 ? round($conSem / $acciones * 100) : 0,
                    ];
                });
        }

        // ── FODA vinculado al PEI seleccionado ───────────────────────────────
        $totalFodaPerfiles = 0;
        $fodaConsolidados  = 0;
        $totalAnalisis     = 0;
        $analisisConIea    = 0;
        $totalCruces       = 0;
        $crucesPorTipo     = collect();

        if ($peiActual && $peiActual->group_id) {
            $groupIds = collect([$peiActual->group_id]);
            if ($peiActual->group) {
                $groupIds = $groupIds->merge($peiActual->group->descendants()->pluck('id'));
            }
            $totalFodaPerfiles = FodaPerfil::whereIn('group_id', $groupIds)->count();
            $fodaConsolidados  = FodaPerfil::whereIn('group_id', $groupIds)->where('type','consolidado')->count();
            $perfilIds         = FodaPerfil::whereIn('group_id', $groupIds)->pluck('id');
            $totalAnalisis     = FodaAnalisis::whereIn('perfil_id', $perfilIds)->count();
            $analisisConIea    = FodaAnalisis::whereIn('perfil_id', $perfilIds)->whereNotNull('iea_valor')->count();

            $perfilConsolidadoId = FodaPerfil::whereIn('group_id', $groupIds)->where('type','consolidado')->value('id');
            if ($perfilConsolidadoId) {
                $totalCruces   = FodaCruceAmbiente::where('perfil_id', $perfilConsolidadoId)->count();
                $crucesPorTipo = FodaCruceAmbiente::where('perfil_id', $perfilConsolidadoId)
                    ->selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->pluck('total','tipo');
            }
        } else {
            // Sin filtro de grupo — totales globales
            $totalFodaPerfiles = FodaPerfil::count();
            $fodaConsolidados  = FodaPerfil::where('type','consolidado')->count();
            $totalAnalisis     = FodaAnalisis::count();
            $analisisConIea    = FodaAnalisis::whereNotNull('iea_valor')->count();
            $totalCruces       = FodaCruceAmbiente::count();
            $crucesPorTipo     = FodaCruceAmbiente::selectRaw('tipo, COUNT(*) as total')
                ->groupBy('tipo')->pluck('total','tipo');
        }

        // ── Proyectos vinculados al PEI seleccionado ──────────────────────────
        $totalProyectos     = ProyectoInstitucional::count();
        $proyectosActivos   = ProyectoInstitucional::activos()->count();
        $proyectosEjecucion = ProyectoInstitucional::enEjecucion()->count();
        $proyectosSinPei    = ProyectoInstitucional::whereNull('pei_profile_id')->count();
        $proyectosPorEstado = ProyectoInstitucional::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')->pluck('total','estado');

        return view('admin.planificacion.dashboard', compact(
            'peisCorporativos', 'peiActual', 'peiSeleccionadoId',
            'totalObjetivos', 'totalMetas', 'totalAcciones', 'accionesSinResp',
            'semaforo', 'peisRecientes',
            'totalFodaPerfiles', 'fodaConsolidados', 'totalAnalisis',
            'analisisConIea', 'totalCruces', 'crucesPorTipo',
            'totalProyectos', 'proyectosActivos', 'proyectosEjecucion',
            'proyectosSinPei', 'proyectosPorEstado'
        ));
    }
}
