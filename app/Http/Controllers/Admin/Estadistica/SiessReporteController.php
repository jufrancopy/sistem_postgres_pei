<?php

namespace App\Http\Controllers\Admin\Estadistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Estadistica\SiessExtracto;
use App\Models\Estadistica\SiessModulo;
use App\Models\Estadistica\SiessPeriodo;
use App\Models\Estadistica\AopTrabajador;
use App\Models\Estadistica\JuBeneficiario;
use App\Models\Estadistica\DcpPresupuesto;
use App\Models\Estadistica\PlFinanciero;

class SiessReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Vista de selección de reporte ─────────────────────────────────────────

    public function index()
    {
        $periodos = SiessPeriodo::where('tipo', 'mensual')
            ->orderByDesc('anio')->orderByDesc('mes')
            ->limit(24)->get();

        $modulos = SiessModulo::where('activo', true)->orderBy('orden')->get();

        return view('admin.estadisticas.siess.reportes.index', compact('periodos', 'modulos'));
    }

    // ── PDF Informe Gerencial ─────────────────────────────────────────────────

    public function pdfGerencial(Request $request)
    {
        $request->validate(['periodo_id' => 'required|integer']);

        $periodo = SiessPeriodo::findOrFail($request->periodo_id);
        $usuario = Auth::user()->name;

        // ── KPIs globales ──────────────────────────────────────────────────
        $totalExtractos  = SiessExtracto::where('periodo_id', $periodo->id)->count();
        $aprobados       = SiessExtracto::where('periodo_id', $periodo->id)->aprobados()->count();
        $pendientes      = SiessExtracto::where('periodo_id', $periodo->id)->pendientes()->count();
        $pctCompletitud  = $totalExtractos > 0 ? round($aprobados / $totalExtractos * 100) : 0;

        $kpisGlobales = [
            'total'           => $totalExtractos,
            'aprobados'       => $aprobados,
            'pendientes'      => $pendientes,
            'pct_completitud' => $pctCompletitud,
        ];

        // ── AOP ────────────────────────────────────────────────────────────
        $resumenAop = AopTrabajador::resumenPorPeriodo($periodo->id);
        $aop = [
            'tiene_datos'     => ($resumenAop['total'] ?? 0) > 0,
            'total'           => $resumenAop['total'] ?? 0,
            'publicos'        => $resumenAop['publicos'] ?? 0,
            'privados'        => $resumenAop['privados'] ?? 0,
            'salario_promedio'=> $resumenAop['salario_promedio'] ?? 0,
            'por_departamento'=> AopTrabajador::porDepartamento($periodo->id)->take(10),
            'recaudacion'     => DB::connection('pgsql')
                ->table('estadistica.aop_recaudacion')
                ->where('periodo_id', $periodo->id)
                ->selectRaw('SUM(monto_empleado) as empleado, SUM(monto_empleador) as empleador, SUM(monto_total) as total')
                ->first(),
            'mora'            => DB::connection('pgsql')
                ->table('estadistica.aop_mora')
                ->where('periodo_id', $periodo->id)
                ->selectRaw('COUNT(*) as empleadores_en_mora, SUM(monto_planillas_normales + monto_complementarias + monto_fraccionamiento) as monto_total, AVG(dias_mora) as dias_promedio')
                ->first(),
        ];

        // ── JU ─────────────────────────────────────────────────────────────
        $resumenJu  = JuBeneficiario::resumenPorPeriodo($periodo->id);
        $financiero = PlFinanciero::where('periodo_id', $periodo->id)->first();
        $ju = [
            'tiene_datos'   => ($resumenJu['total'] ?? 0) > 0,
            'total'         => $resumenJu['total'] ?? 0,
            'monto_total'   => $resumenJu['monto_total'] ?? 0,
            'edad_promedio' => $resumenJu['edad_promedio'] ?? 0,
            'relacion'      => $financiero?->relacion_activo_pasivo ?? 0,
            'activos'       => $financiero?->total_activos ?? 0,
            'pasivos'       => $financiero?->total_pasivos ?? 0,
            'por_concepto'  => JuBeneficiario::porConcepto($periodo->id),
        ];

        // ── DT ─────────────────────────────────────────────────────────────
        $resumenDt = DcpPresupuesto::resumenPorPeriodo($periodo->id);
        $dt = [
            'tiene_datos'            => $resumenDt['ingresos_presupuestados'] > 0 || $resumenDt['egresos_presupuestados'] > 0,
            'ingresos_presupuestados'=> $resumenDt['ingresos_presupuestados'],
            'ingresos_ejecutados'    => $resumenDt['ingresos_ejecutados'],
            'egresos_presupuestados' => $resumenDt['egresos_presupuestados'],
            'egresos_ejecutados'     => $resumenDt['egresos_ejecutados'],
            'pct_ingresos'           => $resumenDt['pct_ejecucion_ingresos'],
            'pct_egresos'            => $resumenDt['pct_ejecucion_egresos'],
            'detalle'                => DcpPresupuesto::where('periodo_id', $periodo->id)
                ->orderBy('tipo')->orderByDesc('ejecutado')->get(),
        ];

        $pdf = Pdf::loadView('admin.estadisticas.siess.pdf.informe_gerencial', compact(
            'periodo', 'usuario', 'kpisGlobales', 'aop', 'ju', 'dt'
        ))
        ->setPaper('a4', 'portrait')
        ->setOption('isPhpEnabled', true)
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('defaultFont', 'DejaVu Sans');

        $filename = 'informe-gerencial-siess-' . $periodo->anio . '-' . str_pad($periodo->mes, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    // ── Exportación Excel/CSV ─────────────────────────────────────────────────

    public function exportarCsv(Request $request)
    {
        $request->validate([
            'modulo'     => 'required|in:aop_trabajadores,ju_beneficiarios,dcp_presupuesto,rl_subsidios,rh_nomina',
            'periodo_id' => 'required|integer',
        ]);

        $periodo = SiessPeriodo::findOrFail($request->periodo_id);
        $modulo  = $request->modulo;

        $tablas = [
            'aop_trabajadores' => [
                'tabla'    => 'estadistica.aop_trabajadores',
                'columnas' => 'edad, sexo, salario, tipo_empleado, departamento_nombre, zona, aporte_empleado, aporte_patronal',
                'nombre'   => 'AOP5-Trabajadores',
            ],
            'ju_beneficiarios' => [
                'tabla'    => 'estadistica.ju_beneficiarios',
                'columnas' => 'sexo, edad, ciudad, departamento_nombre, monto_bruto, concepto, fecha_concesion',
                'nombre'   => 'JU1-Beneficiarios',
            ],
            'dcp_presupuesto' => [
                'tabla'    => 'estadistica.dcp_presupuesto',
                'columnas' => 'tipo, concepto, objeto_gasto, presupuestado, ejecutado, pct_ejecucion',
                'nombre'   => 'DCP1-Presupuesto',
            ],
            'rl_subsidios' => [
                'tabla'    => 'estadistica.rl_subsidios',
                'columnas' => 'diagnostico, dias_reposo, tipo_reposo, es_covid, monto, medio_pago',
                'nombre'   => 'RL1-Subsidios',
            ],
            'rh_nomina' => [
                'tabla'    => 'estadistica.rh_nomina',
                'columnas' => 'cargo, grupo_ocupacional, dependencia, sexo, remuneracion_presupuestada, remuneracion_devengada, tiene_discapacidad',
                'nombre'   => 'RH1-Nomina',
            ],
        ];

        $config = $tablas[$modulo];

        $datos = DB::connection('pgsql')
            ->table($config['tabla'])
            ->where('periodo_id', $periodo->id)
            ->selectRaw($config['columnas'])
            ->get();

        if ($datos->isEmpty()) {
            return back()->with('error', 'No hay datos aprobados para exportar en este período.');
        }

        $filename = $config['nombre'] . '-' . $periodo->anio . '-' . str_pad($periodo->mes ?? 0, 2, '0', STR_PAD_LEFT) . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($datos) {
            $handle = fopen('php://output', 'w');
            // BOM para Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            // Encabezados
            fputcsv($handle, array_keys((array) $datos->first()), ';');
            // Datos
            foreach ($datos as $row) {
                fputcsv($handle, (array) $row, ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
