<?php

namespace App\Http\Controllers;

use App\Admin\Globales\Activity;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\Group;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Estadistica\SiessExtracto;
use App\Models\Estadistica\SiessModulo;
use App\Models\HomeConfiguration;
use App\Models\Riiss\ComplejidadTipo;
use App\Models\Riiss\Evaluacion;
use App\Services\GamificationService;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $config = HomeConfiguration::first() ?? new HomeConfiguration([
            'show_foda'  => true,
            'show_pei'   => true,
            'show_riiss' => true,
        ]);

        $taskQuery = ActivityTask::query();
        $actQuery  = Activity::with(['tasks', 'responsibles', 'peiProfile']);

        if ($config->show_pei && $config->pei_profile_id) {
            $peiProfile = PeiProfile::find($config->pei_profile_id);
            if ($peiProfile) {
                $peiProfileIds = $peiProfile->descendants()->pluck('id')->push($peiProfile->id);
                $actQuery->whereIn('pei_profile_id', $peiProfileIds);
                $taskQuery->whereHas('activity', function($q) use ($peiProfileIds) {
                    $q->whereIn('pei_profile_id', $peiProfileIds);
                });
            }
        }

        $activities = $actQuery->latest()->take(6)->get();

        // Estadísticas de tareas consolidadas en una sola consulta
        $taskStats = (clone $taskQuery)
            ->selectRaw('
                COUNT(*) as total,
                COUNT(CASE WHEN status = 1 THEN 1 END) as en_curso,
                COUNT(CASE WHEN status = 2 THEN 1 END) as hechas,
                COUNT(CASE WHEN status != 2 AND fecha_vencimiento IS NOT NULL AND fecha_vencimiento < NOW() THEN 1 END) as vencidas
            ')
            ->first();

        $totalTareas    = (int) ($taskStats->total ?? 0);
        $tareasEnCurso  = (int) ($taskStats->en_curso ?? 0);
        $tareasHechas   = (int) ($taskStats->hechas ?? 0);
        $tareasVencidas = (int) ($taskStats->vencidas ?? 0);

        $evalRecientes    = collect();
        $evalTotal        = 0;
        $evalCompletadas  = 0;
        $evalEnCurso      = 0;
        $complejidadTipos = collect();

        if ($config->show_riiss) {
            $evalRecientes = Evaluacion::with('establecimiento.complejidadTipo')
                ->latest('fecha_evaluacion')->take(10)->get();

            $evalStats = Evaluacion::selectRaw('
                COUNT(*) as total,
                COUNT(CASE WHEN estado = \'completada\' THEN 1 END) as completadas,
                COUNT(CASE WHEN estado = \'en_curso\' THEN 1 END) as en_curso
            ')->first();

            $evalTotal       = (int) ($evalStats->total ?? 0);
            $evalCompletadas = (int) ($evalStats->completadas ?? 0);
            $evalEnCurso     = (int) ($evalStats->en_curso ?? 0);

            $complejidadTipos = ComplejidadTipo::activos()->get();
        }

        // SIESS y estadísticas agrupadas
        $siessModulos = SiessModulo::where('activo', true)->orderBy('orden')->get();

        if ($siessModulos->isNotEmpty()) {
            $extractosCounts = DB::table('estadistica.siess_extractos')
                ->whereIn('modulo_id', $siessModulos->pluck('id'))
                ->select('modulo_id', 'estado', DB::raw('COUNT(*) as total'))
                ->groupBy('modulo_id', 'estado')
                ->get()
                ->groupBy('modulo_id');

            foreach ($siessModulos as $mod) {
                $c = $extractosCounts->get($mod->id)?->pluck('total', 'estado')->toArray() ?? [];
                $mod->cached_resumen = [
                    'borrador'             => $c['borrador']             ?? 0,
                    'pendiente_validacion' => $c['pendiente_validacion'] ?? 0,
                    'aprobado'             => $c['aprobado']             ?? 0,
                    'objetado'             => $c['objetado']             ?? 0,
                    'aprobado_silencio'    => $c['aprobado_silencio']    ?? 0,
                ];
            }
        }

        $siessStats = DB::table('estadistica.siess_extractos')
            ->selectRaw('
                COUNT(CASE WHEN estado IN (\'aprobado\', \'aprobado_silencio\') THEN 1 END) as aprobados,
                COUNT(CASE WHEN estado = \'pendiente_validacion\' THEN 1 END) as pendientes,
                COUNT(CASE WHEN estado = \'objetado\' THEN 1 END) as objetados
            ')->first();

        $siessAprobados  = (int) ($siessStats->aprobados ?? 0);
        $siessPendientes = (int) ($siessStats->pendientes ?? 0);
        $siessObjetados  = (int) ($siessStats->objetados ?? 0);

        // FODA
        $fodaPerfiles      = 0;
        $fodaAnalisis      = 0;
        $fodaFortalezas    = 0;
        $fodaDebilidades   = 0;
        $fodaOportunidades = 0;
        $fodaAmenazas      = 0;
        $fodaEstrategias   = 0;
        $fodaIeaResumen    = collect();

        if ($config->show_foda && $config->foda_profile_id) {
            $perfilFoda = FodaPerfil::find($config->foda_profile_id);
            if ($perfilFoda) {
                $fodaPerfiles = 1;
                $perfilIds = collect([$perfilFoda->id]);
                if ($perfilFoda->group_id) {
                    $groupIds = Group::descendantsOf($perfilFoda->group_id)->pluck('id')->push($perfilFoda->group_id);
                    $groupPerfiles = FodaPerfil::whereIn('group_id', $groupIds)->pluck('id');
                    $perfilIds = $perfilIds->merge($groupPerfiles)->unique();
                }

                $fodaCounts = FodaAnalisis::whereIn('perfil_id', $perfilIds)
                    ->selectRaw('LOWER(tipo) as tipo_lower, count(*) as total')
                    ->groupBy(DB::raw('LOWER(tipo)'))
                    ->pluck('total', 'tipo_lower');

                $fodaFortalezas    = (int) ($fodaCounts['fortaleza'] ?? 0);
                $fodaDebilidades   = (int) ($fodaCounts['debilidad'] ?? 0);
                $fodaOportunidades = (int) ($fodaCounts['oportunidad'] ?? 0);
                $fodaAmenazas      = (int) ($fodaCounts['amenaza'] ?? 0);
                $fodaAnalisis      = $fodaFortalezas + $fodaDebilidades + $fodaOportunidades + $fodaAmenazas;

                $crucesQuery = FodaCruceAmbiente::whereIn('perfil_id', $perfilIds);
                if ($config->foda_analisis_id) {
                    $crucesQuery->where('analisis_id', $config->foda_analisis_id);
                }
                $fodaEstrategias = $crucesQuery->count();

                $fodaIeaResumen = FodaAnalisis::whereIn('perfil_id', $perfilIds)
                    ->whereNotNull('iea_clasificacion')
                    ->selectRaw('iea_clasificacion, count(*) as total')
                    ->groupBy('iea_clasificacion')
                    ->pluck('total', 'iea_clasificacion');
            }
        } elseif ($config->show_foda) {
            $fodaPerfiles = FodaPerfil::count();

            $fodaCounts = FodaAnalisis::selectRaw('LOWER(tipo) as tipo_lower, count(*) as total')
                ->groupBy(DB::raw('LOWER(tipo)'))
                ->pluck('total', 'tipo_lower');

            $fodaFortalezas    = (int) ($fodaCounts['fortaleza'] ?? 0);
            $fodaDebilidades   = (int) ($fodaCounts['debilidad'] ?? 0);
            $fodaOportunidades = (int) ($fodaCounts['oportunidad'] ?? 0);
            $fodaAmenazas      = (int) ($fodaCounts['amenaza'] ?? 0);
            $fodaAnalisis      = $fodaFortalezas + $fodaDebilidades + $fodaOportunidades + $fodaAmenazas;

            $fodaEstrategias   = FodaCruceAmbiente::count();
            $fodaIeaResumen    = FodaAnalisis::whereNotNull('iea_clasificacion')
                ->selectRaw('iea_clasificacion, count(*) as total')
                ->groupBy('iea_clasificacion')
                ->pluck('total', 'iea_clasificacion');
        }

        // PEI
        $peiPlanes    = 0;
        $peiAcciones  = 0;
        $peiSemaforo  = collect();
        $peiRecientes = collect();

        $peiSeleccionado = null;
        if ($config->show_pei && $config->pei_profile_id) {
            $peiSeleccionado = PeiProfile::find($config->pei_profile_id);
            if ($peiSeleccionado) {
                $descendantIds = $peiSeleccionado->descendants()->pluck('id')->push($peiSeleccionado->id);

                $peiPlanes   = 1;
                $peiAcciones = PeiProfile::whereIn('id', $descendantIds)->where('level', 'action')->count();
                $peiSemaforo = PeiProfile::whereIn('id', $descendantIds)
                    ->whereNotNull('semaforo')
                    ->selectRaw('semaforo, count(*) as total')
                    ->groupBy('semaforo')
                    ->pluck('total', 'semaforo');
                $peiRecientes = collect([$peiSeleccionado]);
            }
        } elseif ($config->show_pei) {
            $peiPlanes   = PeiProfile::whereNull('parent_id')->where('level', 'master')->count();
            $peiAcciones = PeiProfile::where('level', 'action')->count();
            $peiSemaforo = PeiProfile::whereNotNull('semaforo')
                ->selectRaw('semaforo, count(*) as total')
                ->groupBy('semaforo')
                ->pluck('total', 'semaforo');
            $peiRecientes = PeiProfile::whereNull('parent_id')
                ->where('level', 'master')
                ->whereNull('deleted_at')
                ->latest()
                ->take(10)
                ->get(['id', 'name', 'year_start', 'year_end', 'semaforo', 'public_token']);
        }

        // Top 5 Líderes de Gamificación y Reputación (Optimizado con SQL directo)
        $activePeiId = ($config->show_pei && $config->pei_profile_id) ? $config->pei_profile_id : null;

        $leaderQuery = DB::table('gamification_points')
            ->select('user_id', DB::raw('SUM(points) as total_points'))
            ->groupBy('user_id');

        if ($activePeiId) {
            $gamificationService = app(GamificationService::class);
            $allowedProfileIds = $gamificationService->getPeiTreeProfileIds($activePeiId);
            $leaderQuery->where(function ($q) use ($allowedProfileIds) {
                $q->whereIn('pei_profile_id', $allowedProfileIds)
                  ->orWhereNull('pei_profile_id');
            });
        }

        $topPoints = $leaderQuery->orderByDesc('total_points')->take(5)->get();
        $topUserIds = $topPoints->pluck('user_id')->toArray();

        if (!empty($topUserIds)) {
            $users = \App\Models\User::with('group')
                ->whereIn('id', $topUserIds)
                ->get()
                ->keyBy('id');

            $topLeaderboard = $topPoints->map(function ($p) use ($users) {
                $u = $users->get($p->user_id);
                if (!$u) return null;
                $u->total_points = (int) $p->total_points;

                $currentLevel = GamificationService::LEVELS[1];
                foreach (GamificationService::LEVELS as $lvl => $info) {
                    if ($u->total_points >= $info['min']) {
                        $currentLevel = $info;
                    }
                }
                $u->gamification = [
                    'level_name' => $currentLevel['name'],
                    'badge'      => $currentLevel['badge'],
                    'icon'       => $currentLevel['icon'],
                ];
                return $u;
            })->filter()->values();
        } else {
            $topLeaderboard = collect();
        }

        return view('welcome', compact(
            'config',
            'activities', 'totalTareas', 'tareasEnCurso', 'tareasHechas', 'tareasVencidas',
            'evalTotal', 'evalCompletadas', 'evalEnCurso',
            'evalRecientes', 'complejidadTipos',
            'siessModulos', 'siessAprobados', 'siessPendientes', 'siessObjetados',
            'fodaPerfiles', 'fodaAnalisis', 'fodaFortalezas', 'fodaDebilidades',
            'fodaOportunidades', 'fodaAmenazas', 'fodaEstrategias', 'fodaIeaResumen',
            'peiPlanes', 'peiAcciones', 'peiSemaforo', 'peiRecientes', 'peiSeleccionado', 'topLeaderboard'
        ));
    }
}
