<?php

namespace App\Http\Controllers\Admin\Estadistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Estadistica\SiessExtracto;
use App\Models\Estadistica\SiessModulo;
use App\Models\Estadistica\SiessPeriodo;
use App\Models\Estadistica\AopTrabajador;
use App\Models\Estadistica\JuBeneficiario;
use App\Models\Estadistica\DcpPresupuesto;
use App\Models\Estadistica\PlFinanciero;
use App\Models\Estadistica\RlSubsidio;
use App\Models\Estadistica\DiPortafolio;
use App\Models\Estadistica\RhNomina;
use App\Models\Estadistica\CauAtencion;

class SiessModuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── AOP — Aportes y Trabajadores ─────────────────────────────────────────

    public function aop(Request $request)
    {
        $periodos = SiessPeriodo::where('tipo', 'mensual')
            ->orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();

        $periodoId = $request->periodo_id
            ?? SiessPeriodo::where('tipo', 'mensual')
                ->orderByDesc('anio')->orderByDesc('mes')
                ->value('id');

        $periodo = SiessPeriodo::find($periodoId);

        // KPIs
        $resumen = AopTrabajador::resumenPorPeriodo($periodoId);

        // Gráfico: trabajadores por departamento (top 10)
        $porDepartamento = AopTrabajador::porDepartamento($periodoId)->take(10);

        // Gráfico: público vs privado por mes (últimos 12 meses)
        $evolucion = DB::connection('pgsql')
            ->table('estadistica.aop_trabajadores as t')
            ->join('estadistica.siess_periodos as p', 'p.id', '=', 't.periodo_id')
            ->where('p.tipo', 'mensual')
            ->where('p.anio', '>=', now()->subYear()->year)
            ->selectRaw('p.anio, p.mes, tipo_empleado, COUNT(*) as total')
            ->groupBy('p.anio', 'p.mes', 'tipo_empleado')
            ->orderBy('p.anio')->orderBy('p.mes')
            ->get();

        // Recaudación del período
        $recaudacion = DB::connection('pgsql')
            ->table('estadistica.aop_recaudacion')
            ->where('periodo_id', $periodoId)
            ->selectRaw('SUM(monto_empleado) as empleado, SUM(monto_empleador) as empleador, SUM(monto_total) as total')
            ->first();

        // Mora del período
        $mora = DB::connection('pgsql')
            ->table('estadistica.aop_mora')
            ->where('periodo_id', $periodoId)
            ->selectRaw('COUNT(*) as empleadores_en_mora,
                         SUM(monto_planillas_normales + monto_complementarias + monto_fraccionamiento) as monto_total,
                         AVG(dias_mora) as dias_promedio')
            ->first();

        return view('admin.estadisticas.siess.modulos.aop', compact(
            'periodos', 'periodo', 'resumen', 'porDepartamento', 'evolucion', 'recaudacion', 'mora'
        ));
    }

    // ── JU — Jubilaciones y Pensiones ────────────────────────────────────────

    public function ju(Request $request)
    {
        $periodos = SiessPeriodo::where('tipo', 'mensual')
            ->orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();

        $periodoId = $request->periodo_id
            ?? SiessPeriodo::where('tipo', 'mensual')
                ->orderByDesc('anio')->orderByDesc('mes')
                ->value('id');

        $periodo = SiessPeriodo::find($periodoId);

        $resumen        = JuBeneficiario::resumenPorPeriodo($periodoId);
        $porConcepto    = JuBeneficiario::porConcepto($periodoId);
        $porDepartamento= JuBeneficiario::porDepartamento($periodoId)->take(10);

        // Relación activo/pasivo
        $financiero = PlFinanciero::where('periodo_id', $periodoId)->first();

        // Evolución anual de la relación activo/pasivo
        $evolucionFinanciero = PlFinanciero::evolucionAnual(now()->year);

        // Altas y solicitudes del período
        $altas = DB::connection('pgsql')
            ->table('estadistica.ju_altas_solicitudes')
            ->where('periodo_id', $periodoId)
            ->get();

        return view('admin.estadisticas.siess.modulos.ju', compact(
            'periodos', 'periodo', 'resumen', 'porConcepto',
            'porDepartamento', 'financiero', 'evolucionFinanciero', 'altas'
        ));
    }

    // ── DT — Tesorería y Contabilidad ────────────────────────────────────────

    public function dt(Request $request)
    {
        $anio = $request->anio ?? now()->year;
        $anios = range(now()->year, 2020);

        $periodoId = $request->periodo_id
            ?? SiessPeriodo::where('tipo', 'mensual')
                ->orderByDesc('anio')->orderByDesc('mes')
                ->value('id');

        $periodos = SiessPeriodo::where('tipo', 'mensual')
            ->orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();

        $periodo = SiessPeriodo::find($periodoId);

        // Ejecución presupuestaria del período
        $resumenPresupuesto = DcpPresupuesto::resumenPorPeriodo($periodoId);

        // Detalle por concepto
        $detalleIngresos = DcpPresupuesto::where('periodo_id', $periodoId)
            ->where('tipo', 'ingreso')
            ->orderByDesc('ejecutado')
            ->get();

        $detalleEgresos = DcpPresupuesto::where('periodo_id', $periodoId)
            ->where('tipo', 'egreso')
            ->orderByDesc('ejecutado')
            ->get();

        // Evolución anual
        $evolucion = DcpPresupuesto::evolucionAnual($anio);

        // Tesorería del período
        $tesoreria = DB::connection('pgsql')
            ->table('estadistica.dt_tesoreria')
            ->where('periodo_id', $periodoId)
            ->selectRaw('tipo, SUM(monto) as monto_total, SUM(cantidad_operaciones) as operaciones')
            ->groupBy('tipo')
            ->orderByDesc('monto_total')
            ->get();

        return view('admin.estadisticas.siess.modulos.dt', compact(
            'periodos', 'periodo', 'anio', 'anios',
            'resumenPresupuesto', 'detalleIngresos', 'detalleEgresos',
            'evolucion', 'tesoreria'
        ));
    }

    // ── RL — Subsidios y Riesgo Laboral ──────────────────────────────────────

    public function rl(Request $request)
    {
        $periodos  = SiessPeriodo::where('tipo', 'mensual')->orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();
        $periodoId = $request->periodo_id ?? SiessPeriodo::where('tipo','mensual')->orderByDesc('anio')->orderByDesc('mes')->value('id');
        $periodo   = SiessPeriodo::find($periodoId);
        $resumen   = RlSubsidio::resumenPorPeriodo($periodoId);

        $porDiagnostico = DB::connection('pgsql')
            ->table('estadistica.rl_subsidios')
            ->where('periodo_id', $periodoId)
            ->selectRaw('diagnostico, COUNT(*) as total, SUM(monto) as monto_total, AVG(dias_reposo) as dias_promedio')
            ->groupBy('diagnostico')->orderByDesc('total')->limit(10)->get();

        $evolucion = DB::connection('pgsql')
            ->table('estadistica.rl_subsidios as r')
            ->join('estadistica.siess_periodos as p', 'p.id', '=', 'r.periodo_id')
            ->where('p.tipo', 'mensual')->where('p.anio', '>=', now()->subYear()->year)
            ->selectRaw('p.anio, p.mes, COUNT(*) as total, SUM(r.monto) as monto_total')
            ->groupBy('p.anio', 'p.mes')->orderBy('p.anio')->orderBy('p.mes')->get();

        return view('admin.estadisticas.siess.modulos.rl', compact('periodos','periodo','resumen','porDiagnostico','evolucion'));
    }

    // ── DI — Inversiones ─────────────────────────────────────────────────────

    public function di(Request $request)
    {
        $periodos  = SiessPeriodo::where('tipo', 'mensual')->orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();
        $periodoId = $request->periodo_id ?? SiessPeriodo::where('tipo','mensual')->orderByDesc('anio')->orderByDesc('mes')->value('id');
        $periodo   = SiessPeriodo::find($periodoId);
        $resumen   = DiPortafolio::resumenPorPeriodo($periodoId);

        $prestamos = DB::connection('pgsql')
            ->table('estadistica.di_prestamos_caja')
            ->where('periodo_id', $periodoId)
            ->selectRaw('tipo_cliente, SUM(monto_total) as monto, SUM(monto_morosidad) as morosidad, SUM(cantidad_prestamos) as cantidad')
            ->groupBy('tipo_cliente')->get();

        return view('admin.estadisticas.siess.modulos.di', compact('periodos','periodo','resumen','prestamos'));
    }

    // ── RH — Recursos Humanos ─────────────────────────────────────────────────

    public function rh(Request $request)
    {
        $periodos  = SiessPeriodo::where('tipo', 'mensual')->orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();
        $periodoId = $request->periodo_id ?? SiessPeriodo::where('tipo','mensual')->orderByDesc('anio')->orderByDesc('mes')->value('id');
        $periodo   = SiessPeriodo::find($periodoId);
        $resumen   = RhNomina::resumenPorPeriodo($periodoId);

        $movimientos = DB::connection('pgsql')
            ->table('estadistica.rh_movimientos')
            ->where('periodo_id', $periodoId)
            ->selectRaw('tipo, SUM(cantidad) as total, SUM(valor_adicional) as valor')
            ->groupBy('tipo')->orderByDesc('total')->get();

        return view('admin.estadisticas.siess.modulos.rh', compact('periodos','periodo','resumen','movimientos'));
    }

    // ── CAU — Atención al Usuario ─────────────────────────────────────────────

    public function cau(Request $request)
    {
        $periodos  = SiessPeriodo::where('tipo', 'mensual')->orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();
        $periodoId = $request->periodo_id ?? SiessPeriodo::where('tipo','mensual')->orderByDesc('anio')->orderByDesc('mes')->value('id');
        $periodo   = SiessPeriodo::find($periodoId);
        $resumen   = CauAtencion::resumenPorPeriodo($periodoId);

        $suministros = DB::connection('pgsql')
            ->table('estadistica.sal_suministros')
            ->where('periodo_id', $periodoId)
            ->selectRaw('tipo, COUNT(*) as items, SUM(cantidad_producida) as producida, SUM(cantidad_entregada) as entregada')
            ->groupBy('tipo')->get();

        $evolucion = DB::connection('pgsql')
            ->table('estadistica.cau_atencion as c')
            ->join('estadistica.siess_periodos as p', 'p.id', '=', 'c.periodo_id')
            ->where('p.tipo', 'mensual')->where('p.anio', '>=', now()->subYear()->year)
            ->selectRaw('p.mes, SUM(c.total_contactos) as contactos, SUM(c.abandonos) as abandonos')
            ->groupBy('p.mes')->orderBy('p.mes')->get();

        return view('admin.estadisticas.siess.modulos.cau', compact('periodos','periodo','resumen','suministros','evolucion'));
    }

    // ── API: carga masiva de datos por módulo ─────────────────────────────────

    public function storeDatos(Request $request, string $modulo)
    {
        $request->validate([
            'extracto_id' => 'required|integer',
            'datos'       => 'required|array|min:1',
        ]);

        $extracto = SiessExtracto::findOrFail($request->extracto_id);

        if ($extracto->estaAprobado()) {
            return response()->json(['error' => 'No se pueden modificar datos de un extracto aprobado.'], 422);
        }

        $periodoId  = $extracto->periodo_id;
        $extractoId = $extracto->id;
        $insertados = 0;

        DB::connection('pgsql')->transaction(function () use ($modulo, $request, $periodoId, $extractoId, &$insertados) {
            $now = now();

            switch ($modulo) {
                case 'aop_trabajadores':
                    AopTrabajador::where('extracto_id', $extractoId)->delete();
                    foreach ($request->datos as $row) {
                        AopTrabajador::create(array_merge($row, [
                            'extracto_id' => $extractoId,
                            'periodo_id'  => $periodoId,
                        ]));
                        $insertados++;
                    }
                    break;

                case 'ju_beneficiarios':
                    JuBeneficiario::where('extracto_id', $extractoId)->delete();
                    foreach ($request->datos as $row) {
                        JuBeneficiario::create(array_merge($row, [
                            'extracto_id' => $extractoId,
                            'periodo_id'  => $periodoId,
                        ]));
                        $insertados++;
                    }
                    break;

                case 'dcp_presupuesto':
                    DcpPresupuesto::where('extracto_id', $extractoId)->delete();
                    foreach ($request->datos as $row) {
                        DcpPresupuesto::create(array_merge($row, [
                            'extracto_id' => $extractoId,
                            'periodo_id'  => $periodoId,
                        ]));
                        $insertados++;
                    }
                    break;

                case 'pl_financiero':
                    PlFinanciero::where('extracto_id', $extractoId)->delete();
                    foreach ($request->datos as $row) {
                        PlFinanciero::create(array_merge($row, [
                            'extracto_id' => $extractoId,
                            'periodo_id'  => $periodoId,
                        ]));
                        $insertados++;
                    }
                    break;
            }
        });

        return response()->json([
            'success'   => "$insertados registros cargados en $modulo.",
            'extracto'  => $extracto->fresh(),
        ]);
    }
}
