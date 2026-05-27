<?php

namespace App\Http\Controllers\Admin\Estadistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estadistica\EphDataset;
use App\Models\Estadistica\DimMpiDgeec;
use App\Models\Estadistica\DimIndicadoresViviendaDgeec;
use App\Models\Estadistica\DimDemografiaDgeec;

/**
 * Dashboard de Contexto Nacional — cruza DGEEC con datos IPS.
 *
 * Módulos:
 * - MPI 2024: Índice de Pobreza Multidimensional
 * - Vivienda EPHC: Determinantes ambientales de salud
 * - Demografía EPHC: PEA, informalidad, penetración IPS
 */
class ContextoNacionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ════════════════════════════════════════════════════════════════════════
    // DASHBOARD PRINCIPAL
    // ════════════════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        $anio = $request->anio ?? 2024;

        // KPIs rápidos — sin cargar datos pesados
        $kpis = [
            'datasets_cargados' => EphDataset::count(),
            'mpi_registros'     => DimMpiDgeec::where('anio', $anio)->count(),
            'vivienda_registros'=> DimIndicadoresViviendaDgeec::where('anio', $anio)->count(),
            'demografia_registros' => DimDemografiaDgeec::where('anio', $anio)->count(),
        ];

        // Total país para el año seleccionado
        $mpiPais     = DimMpiDgeec::where('anio', $anio)->where('departamento_codigo', 0)->where('area', 'total')->first();
        $viviendaPais= DimIndicadoresViviendaDgeec::where('anio', $anio)->where('departamento_codigo', 0)->where('area', 'total')->first();
        $demoPais    = DimDemografiaDgeec::where('anio', $anio)->where('departamento_codigo', 0)->where('area', 'total')->first();

        // Mapa de riesgo por departamento
        $mapaRiesgo = DimMpiDgeec::where('anio', $anio)
            ->where('departamento_codigo', '>', 0)
            ->where('area', 'total')
            ->orderByDesc('mpi_m0')
            ->get();

        $aniosDisponibles = DimMpiDgeec::selectRaw('DISTINCT anio')
            ->orderByDesc('anio')->pluck('anio')
            ->merge(DimIndicadoresViviendaDgeec::selectRaw('DISTINCT anio')->orderByDesc('anio')->pluck('anio'))
            ->unique()->sort()->values();

        return view('admin.estadisticas.contexto.index', compact(
            'kpis', 'mpiPais', 'viviendaPais', 'demoPais',
            'mapaRiesgo', 'anio', 'aniosDisponibles'
        ));
    }

    // ════════════════════════════════════════════════════════════════════════
    // PROCESAMIENTO MPI
    // ════════════════════════════════════════════════════════════════════════

    public function procesarMpi(Request $request, $datasetId)
    {
        $request->validate([
            'anio' => 'required|integer|min:2000|max:' . (now()->year + 1),
        ]);

        $dataset = EphDataset::select(['id', 'archivo_path', 'archivo_extension', 'total_filas'])
            ->findOrFail($datasetId);

        $guardados = 0;

        if ($dataset->archivo_path) {
            // Procesar en streaming desde archivo físico
            $ruta = storage_path('app/' . $dataset->archivo_path);
            $guardados = DimMpiDgeec::procesarStream($ruta, $dataset->archivo_extension, $request->anio, Auth::id());
        } else {
            // Fallback para datasets con datos en BD
            $datasetConDatos = EphDataset::withDatos()->find($datasetId);
            $guardados = DimMpiDgeec::procesarCsv($datasetConDatos->datos ?? [], $request->anio, Auth::id());
        }

        return redirect()->route('siess.contexto.index')
            ->with('success', "MPI procesado: {$guardados} registros por departamento/área calculados.");
    }

    // ════════════════════════════════════════════════════════════════════════
    // PROCESAMIENTO VIVIENDA
    // ════════════════════════════════════════════════════════════════════════

    public function procesarVivienda(Request $request, $datasetId)
    {
        $request->validate([
            'anio' => 'required|integer|min:2000|max:' . (now()->year + 1),
        ]);

        $dataset = EphDataset::select(['id', 'archivo_path', 'archivo_extension', 'total_filas'])
            ->findOrFail($datasetId);

        $guardados = 0;

        if ($dataset->archivo_path) {
            $ruta = storage_path('app/' . $dataset->archivo_path);
            $guardados = DimIndicadoresViviendaDgeec::procesarStream($ruta, $dataset->archivo_extension, $request->anio, Auth::id());
        } else {
            $datasetConDatos = EphDataset::withDatos()->find($datasetId);
            $guardados = DimIndicadoresViviendaDgeec::procesarCsv($datasetConDatos->datos ?? [], $request->anio, Auth::id());
        }

        return redirect()->route('siess.contexto.index')
            ->with('success', "Vivienda procesada: {$guardados} registros de determinantes ambientales calculados.");
    }

    // ════════════════════════════════════════════════════════════════════════
    // VISTA DETALLE MPI
    // ════════════════════════════════════════════════════════════════════════

    public function mpi(Request $request)
    {
        $anio = $request->anio ?? 2024;

        $datos = DimMpiDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->orderBy('departamento_codigo')
            ->get();

        $aniosDisponibles = DimMpiDgeec::selectRaw('DISTINCT anio')
            ->orderByDesc('anio')->pluck('anio');

        // Datasets disponibles para procesar
        $datasets = EphDataset::select(['id', 'titulo', 'categoria', 'anio', 'total_filas'])
            ->where('categoria', 'ipm')
            ->orWhere('titulo', 'like', '%MPI%')
            ->orWhere('titulo', 'like', '%mpi%')
            ->latest()->get();

        return view('admin.estadisticas.contexto.mpi', compact(
            'datos', 'anio', 'aniosDisponibles', 'datasets'
        ));
    }

    // ════════════════════════════════════════════════════════════════════════
    // VISTA DETALLE VIVIENDA
    // ════════════════════════════════════════════════════════════════════════

    public function vivienda(Request $request)
    {
        $anio = $request->anio ?? 2021;

        $datos = DimIndicadoresViviendaDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->orderBy('departamento_codigo')
            ->get();

        $aniosDisponibles = DimIndicadoresViviendaDgeec::selectRaw('DISTINCT anio')
            ->orderByDesc('anio')->pluck('anio');

        $datasets = EphDataset::select(['id', 'titulo', 'categoria', 'anio', 'total_filas'])
            ->where('categoria', 'vivienda')
            ->latest()->get();

        $indicadores = DimIndicadoresViviendaDgeec::INDICADORES;

        return view('admin.estadisticas.contexto.vivienda', compact(
            'datos', 'anio', 'aniosDisponibles', 'datasets', 'indicadores'
        ));
    }

    // ════════════════════════════════════════════════════════════════════════
    // API CHARTS
    // ════════════════════════════════════════════════════════════════════════

    public function chartMpi(Request $request)
    {
        $anio = $request->anio ?? 2024;

        $datos = DimMpiDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->where('departamento_codigo', '>', 0)
            ->orderByDesc('mpi_m0')
            ->get();

        return response()->json([
            'labels'     => $datos->pluck('departamento_nombre'),
            'mpi_m0'     => $datos->pluck('mpi_m0'),
            'incidencia' => $datos->pluck('incidencia_h'),
            'd_no_afil'  => $datos->pluck('d_no_afil'),
            'd_sin_salud'=> $datos->pluck('d_sin_salud'),
            'nivel'      => $datos->map(fn($d) => $d->nivel_riesgo),
        ]);
    }

    public function chartVivienda(Request $request)
    {
        $anio = $request->anio ?? 2021;

        $datos = DimIndicadoresViviendaDgeec::where('anio', $anio)
            ->where('area', 'total')
            ->where('departamento_codigo', '>', 0)
            ->orderByDesc('pct_sin_agua_potable')
            ->get();

        return response()->json([
            'labels'           => $datos->pluck('departamento_nombre'),
            'sin_agua'         => $datos->pluck('pct_sin_agua_potable'),
            'cocina_lena'      => $datos->pluck('pct_cocina_lena'),
            'sin_desague'      => $datos->pluck('pct_sin_desague'),
            'hacinados'        => $datos->pluck('pct_hacinados'),
        ]);
    }

    public function chartRiesgo(Request $request)
    {
        $anio = $request->anio ?? 2024;

        // Score de riesgo desde la vista PostgreSQL
        $datos = \Illuminate\Support\Facades\DB::connection('pgsql')
            ->table('estadistica.vw_mapa_riesgo_sanitario')
            ->where('anio', $anio)
            ->where('area', 'total')
            ->where('departamento_codigo', '>', 0)
            ->orderByDesc('score_riesgo_sanitario')
            ->get();

        $deptos = DimMpiDgeec::DEPARTAMENTOS;

        return response()->json([
            'labels' => $datos->map(fn($d) => $deptos[$d->departamento_codigo] ?? "Dpto {$d->departamento_codigo}"),
            'score'  => $datos->pluck('score_riesgo_sanitario'),
            'mpi'    => $datos->pluck('mpi_incidencia'),
            'no_afil'=> $datos->pluck('pct_sin_afiliacion'),
            'sin_agua'=> $datos->pluck('pct_sin_agua_potable'),
        ]);
    }
}
