<?php

namespace App\Http\Controllers\Admin\Planificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Group;
use App\Models\Proyectos\ProyectoInstitucional;
use App\Models\HomeConfiguration;

class PlanificacionController extends Controller
{
    public function dashboard(Request $request)
    {
        return redirect()->route('globales.dashboard', $request->all());
    }

        // ── Solo PEIs corporativos activos (planes estratégicos institucionales) ──
        $user = auth()->user();
        $isAdminOrCoordinator = $user->hasAnyRole(['Administrador', 'Coordinador de Planificación']);

        $queryPEI = PeiProfile::whereNull('parent_id')
            ->where('level', 'master')
            ->where('type', 'corporative')
            ->where('is_active', true);

        if (!$isAdminOrCoordinator) {
            $userId = $user->id;

            // IDs asignados como Analista
            $analystPeiIds = \DB::table('planificacion.peis_profiles_has_analysts')
                ->where('analyst_id', $userId)
                ->pluck('pei_profile_id')
                ->toArray();

            // IDs donde es responsable de acciones
            $orgId = \App\Admin\Globales\Organigrama::where('user_id', $userId)->value('id');
            $responsibleActionIds = $orgId ? \DB::table('planificacion.peis_profiles_has_responsibles')
                ->where('responsible_id', $orgId)
                ->pluck('profile_id')
                ->toArray() : [];

            $masterIdsFromActions = [];
            if (!empty($responsibleActionIds)) {
                $masterIdsFromActions = PeiProfile::whereIn('id', $responsibleActionIds)
                    ->get()
                    ->map(function($p) {
                        return PeiProfile::where('_lft', '<=', $p->_lft)
                            ->where('_rgt', '>=', $p->_rgt)
                            ->where('level', 'master')
                            ->value('id');
                    })
                    ->filter()
                    ->unique()
                    ->toArray();
            }

            // IDs vinculados por Actividades
            $activityPeiIds = \App\Admin\Globales\Activity::whereHas('responsibles', fn($q) => $q->where('users.id', $userId))
                ->whereNotNull('pei_profile_id')
                ->pluck('pei_profile_id')
                ->toArray();

            $allowedMasterIds = array_unique(array_merge($analystPeiIds, $masterIdsFromActions, $activityPeiIds));
            $queryPEI->whereIn('id', $allowedMasterIds);
        }

        $peisCorporativos = $queryPEI->orderByDesc('year_start')->get();

        // PEI seleccionado — por defecto el más reciente o el configurado
        $peiSeleccionadoId = $request->pei_id ?? ($config->pei_profile_id ?? $peisCorporativos->first()?->id);
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

        $fodaProfileId = $peiActual?->foda_profile_id ?? ($config->show_foda ? $config->foda_profile_id : null);
        if ($fodaProfileId) {
            $fodaProfile = FodaPerfil::find($fodaProfileId);
            if ($fodaProfile) {
                $totalFodaPerfiles = 1;
                $fodaConsolidados  = $fodaProfile->type === 'consolidado' ? 1 : 0;
                $perfilIds = collect([$fodaProfile->id]);
                if ($fodaProfile->group_id) {
                    $groupIds = Group::descendantsOf($fodaProfile->group_id)->pluck('id')->push($fodaProfile->group_id);
                    $groupPerfiles = FodaPerfil::whereIn('group_id', $groupIds)->pluck('id');
                    $perfilIds = $perfilIds->merge($groupPerfiles)->unique();
                    $totalFodaPerfiles = $perfilIds->count();
                    $fodaConsolidados = FodaPerfil::whereIn('id', $perfilIds)->where('type','consolidado')->count();
                }

                $totalAnalisis     = FodaAnalisis::whereIn('perfil_id', $perfilIds)->count();
                $analisisConIea    = FodaAnalisis::whereIn('perfil_id', $perfilIds)
                    ->whereNotNull('iea_valor')->count();

                $totalCruces       = FodaCruceAmbiente::whereIn('perfil_id', $perfilIds)->count();
                $crucesPorTipo     = FodaCruceAmbiente::whereIn('perfil_id', $perfilIds)
                    ->selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->pluck('total','tipo');
            }
        } else {
            $totalFodaPerfiles = FodaPerfil::count();
            $fodaConsolidados  = FodaPerfil::where('type','consolidado')->count();
            $totalAnalisis     = FodaAnalisis::count();
            $analisisConIea    = FodaAnalisis::whereNotNull('iea_valor')->count();
            $totalCruces       = FodaCruceAmbiente::count();
            $crucesPorTipo     = FodaCruceAmbiente::selectRaw('tipo, COUNT(*) as total')
                ->groupBy('tipo')->pluck('total','tipo');
        }

        // ── Proyectos vinculados al PEI seleccionado ──────────────────────────
        $proyectosQuery = ProyectoInstitucional::query();
        if ($peiActual) {
            $proyectosQuery->where('pei_profile_id', $peiActual->id);
        }
        $totalProyectos     = (clone $proyectosQuery)->count();
        $proyectosActivos   = (clone $proyectosQuery)->activos()->count();
        $proyectosEjecucion = (clone $proyectosQuery)->enEjecucion()->count();
        $proyectosSinPei    = ProyectoInstitucional::whereNull('pei_profile_id')->count();
        $proyectosPorEstado = (clone $proyectosQuery)->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')->pluck('total','estado');

        return view('admin.planificacion.dashboard', compact(
            'peisCorporativos', 'peiActual', 'peiSeleccionadoId',
            'totalObjetivos', 'totalMetas', 'totalAcciones', 'accionesSinResp',
            'semaforo', 'peisRecientes',
            'totalFodaPerfiles', 'fodaConsolidados', 'totalAnalisis',
            'analisisConIea', 'totalCruces', 'crucesPorTipo',
            'totalProyectos', 'proyectosActivos', 'proyectosEjecucion',
            'proyectosSinPei', 'proyectosPorEstado', 'config'
        ));
    }

    /**
     * Guarda el PEI seleccionado en la configuración del dashboard para que persista entre sesiones.
     */
    public function guardarPeiSeleccionado(Request $request)
    {
        $request->validate(['pei_id' => 'required|string']);

        // Verificar que el PEI existe usando el modelo (evita problemas de schema en PostgreSQL)
        $pei = PeiProfile::find($request->pei_id);
        if (!$pei) {
            return response()->json(['success' => false, 'message' => 'Plan Estratégico no encontrado.'], 422);
        }

        $config = HomeConfiguration::firstOrNew([]);
        $config->pei_profile_id = $request->pei_id;
        $config->save();

        return response()->json(['success' => true, 'pei_id' => $request->pei_id]);
    }

    /**
     * Ejecuta manualmente el respaldo de PostgreSQL y envío de reporte por correo a jucfra23@gmail.com
     */
    public function ejecutarDiagnostico(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('siplan:health-and-backup');
            $output = \Illuminate\Support\Facades\Artisan::output();

            return response()->json([
                'success' => true,
                'message' => '¡Diagnóstico y Respaldo de Base de Datos ejecutados con éxito! El reporte ha sido enviado a jucfra23@gmail.com.',
                'output'  => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al ejecutar el diagnóstico: ' . $e->getMessage()
            ], 500);
        }
    }
}
