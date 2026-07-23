<?php

namespace App\Http\Controllers;

use App\Admin\Globales\Activity;
use App\Admin\Globales\ActivityTask;
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
                $analisisQuery = FodaAnalisis::where('perfil_id', $config->foda_profile_id);
                $fodaAnalisis      = (clone $analisisQuery)->count();
                $fodaFortalezas    = (clone $analisisQuery)->where('tipo', 'fortaleza')->count();
                $fodaDebilidades   = (clone $analisisQuery)->where('tipo', 'debilidad')->count();
                $fodaOportunidades = (clone $analisisQuery)->where('tipo', 'oportunidad')->count();
                $fodaAmenazas      = (clone $analisisQuery)->where('tipo', 'amenaza')->count();

                $crucesQuery = FodaCruceAmbiente::where('perfil_id', $config->foda_profile_id);
                if ($config->foda_analisis_id) {
                    $crucesQuery->where('analisis_id', $config->foda_analisis_id);
                }
                $fodaEstrategias = $crucesQuery->count();

                $fodaIeaResumen = FodaAnalisis::where('perfil_id', $config->foda_profile_id)
                    ->whereNotNull('iea_clasificacion')
                    ->selectRaw('iea_clasificacion, count(*) as total')
                    ->groupBy('iea_clasificacion')
                    ->pluck('total', 'iea_clasificacion');
            }
        } elseif ($config->show_foda) {
            $fodaPerfiles      = FodaPerfil::count();
            $fodaAnalisis      = FodaAnalisis::count();
            $fodaFortalezas    = FodaAnalisis::where('tipo', 'fortaleza')->count();
            $fodaDebilidades   = FodaAnalisis::where('tipo', 'debilidad')->count();
            $fodaOportunidades = FodaAnalisis::where('tipo', 'oportunidad')->count();
            $fodaAmenazas      = FodaAnalisis::where('tipo', 'amenaza')->count();
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

        return view('welcome', compact(
            'config',
            'activities', 'totalTareas', 'tareasEnCurso', 'tareasHechas', 'tareasVencidas',
            'evalTotal', 'evalCompletadas', 'evalEnCurso',
            'evalRecientes', 'complejidadTipos',
            'siessModulos', 'siessAprobados', 'siessPendientes', 'siessObjetados',
            'fodaPerfiles', 'fodaAnalisis', 'fodaFortalezas', 'fodaDebilidades',
            'fodaOportunidades', 'fodaAmenazas', 'fodaEstrategias', 'fodaIeaResumen',
            'peiPlanes', 'peiAcciones', 'peiSemaforo', 'peiRecientes'
        ));
    }
}
