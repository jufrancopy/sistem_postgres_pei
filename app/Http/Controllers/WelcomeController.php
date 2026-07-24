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

class WelcomeController extends Controller
{
    public function index()
    {
        $config = HomeConfiguration::first() ?? new HomeConfiguration([
            'show_foda'  => true,
            'show_pei'   => true,
            'show_riiss' => true,
        ]);

        $activities = Activity::with(['tasks', 'responsibles'])->latest()->take(6)->get();
        $totalTareas    = ActivityTask::count();
        $tareasEnCurso  = ActivityTask::where('status', 1)->count();
        $tareasHechas   = ActivityTask::where('status', 2)->count();
        $tareasVencidas = ActivityTask::where('status', '!=', 2)
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', now())
            ->count();

        $evalRecientes    = collect();
        $evalTotal        = 0;
        $evalCompletadas  = 0;
        $evalEnCurso      = 0;
        $complejidadTipos = collect();

        if ($config->show_riiss) {
            $evalRecientes = Evaluacion::with('establecimiento.complejidadTipo')
                ->latest('fecha_evaluacion')->take(10)->get();
            $evalTotal       = Evaluacion::count();
            $evalCompletadas = Evaluacion::where('estado', 'completada')->count();
            $evalEnCurso     = Evaluacion::where('estado', 'en_curso')->count();
            $complejidadTipos = ComplejidadTipo::activos()->get();
        }

        $siessModulos    = SiessModulo::where('activo', true)->orderBy('orden')->get();
        $siessAprobados  = SiessExtracto::aprobados()->count();
        $siessPendientes = SiessExtracto::pendientes()->count();
        $siessObjetados  = SiessExtracto::where('estado', 'objetado')->count();

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

                $analisisQuery = FodaAnalisis::whereIn('perfil_id', $perfilIds);
                $fodaAnalisis      = (clone $analisisQuery)->count();
                $fodaFortalezas    = (clone $analisisQuery)->whereIn('tipo', ['fortaleza', 'Fortaleza', 'FORTALEZA'])->count();
                $fodaDebilidades   = (clone $analisisQuery)->whereIn('tipo', ['debilidad', 'Debilidad', 'DEBILIDAD'])->count();
                $fodaOportunidades = (clone $analisisQuery)->whereIn('tipo', ['oportunidad', 'Oportunidad', 'OPORTUNIDAD'])->count();
                $fodaAmenazas      = (clone $analisisQuery)->whereIn('tipo', ['amenaza', 'Amenaza', 'AMENAZA'])->count();

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
            $fodaPerfiles      = FodaPerfil::count();
            $fodaAnalisis      = FodaAnalisis::count();
            $fodaFortalezas    = FodaAnalisis::whereIn('tipo', ['fortaleza', 'Fortaleza', 'FORTALEZA'])->count();
            $fodaDebilidades   = FodaAnalisis::whereIn('tipo', ['debilidad', 'Debilidad', 'DEBILIDAD'])->count();
            $fodaOportunidades = FodaAnalisis::whereIn('tipo', ['oportunidad', 'Oportunidad', 'OPORTUNIDAD'])->count();
            $fodaAmenazas      = FodaAnalisis::whereIn('tipo', ['amenaza', 'Amenaza', 'AMENAZA'])->count();
            $fodaEstrategias   = FodaCruceAmbiente::count();
            $fodaIeaResumen    = FodaAnalisis::whereNotNull('iea_clasificacion')
                ->selectRaw('iea_clasificacion, count(*) as total')
                ->groupBy('iea_clasificacion')
                ->pluck('total', 'iea_clasificacion');
        }

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

        // Top 5 Líderes de Gamificación y Reputación
        $gamificationService = app(\App\Services\GamificationService::class);
        $activePeiId = ($config->show_pei && $config->pei_profile_id) ? $config->pei_profile_id : null;

        $topLeaderboard = \App\Models\User::with('group')
            ->get()
            ->map(function($u) use ($gamificationService, $activePeiId) {
                $points = $gamificationService->getUserTotalPoints($u, $activePeiId);
                $u->total_points = $points;
                $u->gamification = $gamificationService->getUserGamificationSummary($u, $activePeiId);
                return $u;
            })
            ->sortByDesc('total_points')
            ->take(5)
            ->values();

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
