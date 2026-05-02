<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #333; }

        /* Encabezado */
        .header { background: #1a3a5c; color: white; padding: 12px 15px; margin-bottom: 15px; }
        .header h1 { font-size: 14px; font-weight: bold; }
        .header h2 { font-size: 10px; font-weight: normal; margin-top: 3px; opacity: .85; }
        .header .meta { font-size: 8px; margin-top: 6px; opacity: .7; }

        /* Secciones */
        .section { margin-bottom: 14px; }
        .section-title {
            background: #2c5f8a; color: white;
            padding: 5px 10px; font-size: 10px; font-weight: bold;
            margin-bottom: 8px;
        }
        .section-title span { font-size: 8px; font-weight: normal; opacity: .8; margin-left: 8px; }

        /* KPI cards */
        .kpi-row { width: 100%; margin-bottom: 8px; }
        .kpi-row td { width: 25%; padding: 0 4px 0 0; vertical-align: top; }
        .kpi-card {
            border: 1px solid #dee2e6; border-radius: 3px;
            padding: 6px 8px; background: #f8f9fa;
        }
        .kpi-card .kpi-label { font-size: 7px; color: #6c757d; text-transform: uppercase; }
        .kpi-card .kpi-value { font-size: 13px; font-weight: bold; color: #1a3a5c; margin-top: 2px; }
        .kpi-card .kpi-sub   { font-size: 7px; color: #6c757d; margin-top: 1px; }

        /* Tablas */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table.data th {
            background: #e9ecef; padding: 4px 6px;
            font-size: 8px; text-align: left; border-bottom: 1px solid #dee2e6;
        }
        table.data td { padding: 3px 6px; font-size: 8px; border-bottom: 1px solid #f0f0f0; }
        table.data tr:nth-child(even) td { background: #fafafa; }

        /* Barra de progreso */
        .progress-bar-wrap { background: #e9ecef; border-radius: 2px; height: 8px; width: 100%; }
        .progress-bar-fill { background: #28a745; border-radius: 2px; height: 8px; }
        .progress-bar-fill.warning { background: #ffc107; }
        .progress-bar-fill.danger  { background: #dc3545; }

        /* Badges */
        .badge { padding: 2px 5px; border-radius: 2px; font-size: 7px; font-weight: bold; }
        .badge-success  { background: #d4edda; color: #155724; }
        .badge-warning  { background: #fff3cd; color: #856404; }
        .badge-danger   { background: #f8d7da; color: #721c24; }
        .badge-info     { background: #d1ecf1; color: #0c5460; }
        .badge-secondary{ background: #e2e3e5; color: #383d41; }

        /* Alerta */
        .alert { padding: 6px 10px; border-radius: 3px; margin-bottom: 8px; font-size: 8px; }
        .alert-warning { background: #fff3cd; border-left: 3px solid #ffc107; }
        .alert-success { background: #d4edda; border-left: 3px solid #28a745; }

        /* Pie de página */
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            border-top: 1px solid #dee2e6; padding: 4px 15px;
            font-size: 7px; color: #6c757d;
            background: white;
        }
        .footer .left  { float: left; }
        .footer .right { float: right; }

        /* Separador */
        hr { border: none; border-top: 1px solid #dee2e6; margin: 10px 0; }

        /* Columnas */
        .col-half { width: 49%; display: inline-block; vertical-align: top; }
        .col-half + .col-half { margin-left: 2%; }

        /* Semáforo */
        .semaforo { display: inline-block; width: 10px; height: 10px; border-radius: 50%; }
        .semaforo.verde    { background: #28a745; }
        .semaforo.amarillo { background: #ffc107; }
        .semaforo.rojo     { background: #dc3545; }
    </style>
</head>
<body>

{{-- ── Pie de página fijo ── --}}
<div class="footer">
    <span class="left">IPS — SIESS | Informe Gerencial Confidencial | Res. 266/2022</span>
    <span class="right">Generado: {{ now()->format('d/m/Y H:i') }} | Página <span class="pagenum"></span></span>
</div>

{{-- ── Encabezado ── --}}
<div class="header">
    <h1>INFORME GERENCIAL ESTADÍSTICO — SIESS</h1>
    <h2>Instituto de Previsión Social — Dirección de Planificación Estratégica</h2>
    <div class="meta">
        Período: <strong>{{ $periodo->nombre }}</strong> &nbsp;|&nbsp;
        Generado por: <strong>{{ $usuario }}</strong> &nbsp;|&nbsp;
        Clasificación: <strong>USO INTERNO</strong>
    </div>
</div>

{{-- ── Resumen Ejecutivo ── --}}
<div class="section">
    <div class="section-title">RESUMEN EJECUTIVO <span>Estado general del sistema al cierre del período</span></div>

    <table class="kpi-row">
        <tr>
            <td>
                <div class="kpi-card">
                    <div class="kpi-label">Total Extractos</div>
                    <div class="kpi-value">{{ $kpisGlobales['total'] }}</div>
                    <div class="kpi-sub">Registros en el sistema</div>
                </div>
            </td>
            <td>
                <div class="kpi-card">
                    <div class="kpi-label">Aprobados</div>
                    <div class="kpi-value" style="color:#155724">{{ $kpisGlobales['aprobados'] }}</div>
                    <div class="kpi-sub">Disponibles para reportes</div>
                </div>
            </td>
            <td>
                <div class="kpi-card">
                    <div class="kpi-label">Pendientes</div>
                    <div class="kpi-value" style="color:#856404">{{ $kpisGlobales['pendientes'] }}</div>
                    <div class="kpi-sub">Esperando validación</div>
                </div>
            </td>
            <td>
                <div class="kpi-card">
                    <div class="kpi-label">% Completitud</div>
                    <div class="kpi-value" style="color:{{ $kpisGlobales['pct_completitud'] >= 80 ? '#155724' : ($kpisGlobales['pct_completitud'] >= 50 ? '#856404' : '#721c24') }}">
                        {{ $kpisGlobales['pct_completitud'] }}%
                    </div>
                    <div class="kpi-sub">Módulos con datos aprobados</div>
                </div>
            </td>
        </tr>
    </table>

    @if($kpisGlobales['pendientes'] > 0)
    <div class="alert alert-warning">
        ⚠ Hay {{ $kpisGlobales['pendientes'] }} extracto(s) pendientes de validación. Los datos correspondientes no están disponibles para este informe.
    </div>
    @endif
</div>

{{-- ── AOP: Aportes y Trabajadores ── --}}
@if(isset($aop) && $aop['tiene_datos'])
<div class="section">
    <div class="section-title">MÓDULO AOP — APORTES Y TRABAJADORES <span>Periodicidad Mensual</span></div>

    <table class="kpi-row">
        <tr>
            <td><div class="kpi-card">
                <div class="kpi-label">Total Trabajadores Activos</div>
                <div class="kpi-value">{{ number_format($aop['total']) }}</div>
                <div class="kpi-sub">Asegurados cotizantes</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Sector Público</div>
                <div class="kpi-value">{{ number_format($aop['publicos']) }}</div>
                <div class="kpi-sub">{{ $aop['total'] > 0 ? round($aop['publicos']/$aop['total']*100,1) : 0 }}% del total</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Sector Privado</div>
                <div class="kpi-value">{{ number_format($aop['privados']) }}</div>
                <div class="kpi-sub">{{ $aop['total'] > 0 ? round($aop['privados']/$aop['total']*100,1) : 0 }}% del total</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Salario Promedio</div>
                <div class="kpi-value">Gs. {{ number_format($aop['salario_promedio'] ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Promedio mensual</div>
            </div></td>
        </tr>
    </table>

    @if($aop['recaudacion'])
    <table class="data">
        <thead><tr><th colspan="2">Recaudación del Período</th></tr></thead>
        <tbody>
            <tr><td>Aportes Empleados</td><td style="text-align:right">Gs. {{ number_format($aop['recaudacion']->empleado ?? 0, 0, ',', '.') }}</td></tr>
            <tr><td>Aportes Patronales</td><td style="text-align:right">Gs. {{ number_format($aop['recaudacion']->empleador ?? 0, 0, ',', '.') }}</td></tr>
            <tr><td><strong>Total Recaudado</strong></td><td style="text-align:right"><strong>Gs. {{ number_format($aop['recaudacion']->total ?? 0, 0, ',', '.') }}</strong></td></tr>
        </tbody>
    </table>
    @endif

    @if($aop['mora'] && $aop['mora']->empleadores_en_mora > 0)
    <div class="alert alert-warning">
        ⚠ Mora: {{ number_format($aop['mora']->empleadores_en_mora) }} empleadores con Gs. {{ number_format($aop['mora']->monto_total ?? 0, 0, ',', '.') }} en mora (promedio {{ round($aop['mora']->dias_promedio ?? 0) }} días).
    </div>
    @endif

    <table class="data">
        <thead><tr><th>Departamento</th><th style="text-align:right">Trabajadores</th><th style="text-align:right">Salario Promedio</th></tr></thead>
        <tbody>
            @foreach($aop['por_departamento'] as $dep)
            <tr>
                <td>{{ $dep->departamento_nombre ?? '—' }}</td>
                <td style="text-align:right">{{ number_format($dep->total) }}</td>
                <td style="text-align:right">Gs. {{ number_format($dep->salario_promedio ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── JU: Jubilaciones ── --}}
@if(isset($ju) && $ju['tiene_datos'])
<div class="section">
    <div class="section-title">MÓDULO JU — JUBILACIONES Y PENSIONES <span>Periodicidad Mensual/Anual</span></div>

    <table class="kpi-row">
        <tr>
            <td><div class="kpi-card">
                <div class="kpi-label">Total Beneficiarios</div>
                <div class="kpi-value">{{ number_format($ju['total']) }}</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Monto Total Pagado</div>
                <div class="kpi-value">Gs. {{ number_format($ju['monto_total'] ?? 0, 0, ',', '.') }}</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Relación Activo/Pasivo</div>
                <div class="kpi-value" style="color:{{ ($ju['relacion'] ?? 0) >= 4 ? '#155724' : (($ju['relacion'] ?? 0) >= 2 ? '#856404' : '#721c24') }}">
                    {{ number_format($ju['relacion'] ?? 0, 2) }}
                </div>
                <div class="kpi-sub">
                    @if(($ju['relacion'] ?? 0) >= 4) Sostenible
                    @elseif(($ju['relacion'] ?? 0) >= 2) En observación
                    @else Crítico @endif
                </div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Edad Promedio</div>
                <div class="kpi-value">{{ round($ju['edad_promedio'] ?? 0) }} años</div>
            </div></td>
        </tr>
    </table>

    <table class="data">
        <thead><tr><th>Concepto</th><th style="text-align:right">Beneficiarios</th><th style="text-align:right">Monto Total</th></tr></thead>
        <tbody>
            @foreach($ju['por_concepto'] as $c)
            <tr>
                <td>{{ $c->concepto }}</td>
                <td style="text-align:right">{{ number_format($c->total) }}</td>
                <td style="text-align:right">Gs. {{ number_format($c->monto_total ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── DT: Presupuesto ── --}}
@if(isset($dt) && $dt['tiene_datos'])
<div class="section">
    <div class="section-title">MÓDULO DT — EJECUCIÓN PRESUPUESTARIA <span>Periodicidad Mensual</span></div>

    <table class="kpi-row">
        <tr>
            <td><div class="kpi-card">
                <div class="kpi-label">Ingresos Presupuestados</div>
                <div class="kpi-value">Gs. {{ number_format($dt['ingresos_presupuestados'], 0, ',', '.') }}</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Ingresos Ejecutados</div>
                <div class="kpi-value">Gs. {{ number_format($dt['ingresos_ejecutados'], 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $dt['pct_ingresos'] }}% de ejecución</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Egresos Presupuestados</div>
                <div class="kpi-value">Gs. {{ number_format($dt['egresos_presupuestados'], 0, ',', '.') }}</div>
            </div></td>
            <td><div class="kpi-card">
                <div class="kpi-label">Egresos Ejecutados</div>
                <div class="kpi-value">Gs. {{ number_format($dt['egresos_ejecutados'], 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $dt['pct_egresos'] }}% de ejecución</div>
            </div></td>
        </tr>
    </table>

    {{-- Barra de ejecución --}}
    <table style="width:100%; margin-bottom:8px;">
        <tr>
            <td style="width:50%; padding-right:8px;">
                <div style="font-size:8px; margin-bottom:2px;">Ejecución Ingresos: {{ $dt['pct_ingresos'] }}%</div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill {{ $dt['pct_ingresos'] >= 80 ? '' : ($dt['pct_ingresos'] >= 50 ? 'warning' : 'danger') }}"
                         style="width:{{ min($dt['pct_ingresos'], 100) }}%"></div>
                </div>
            </td>
            <td style="width:50%;">
                <div style="font-size:8px; margin-bottom:2px;">Ejecución Egresos: {{ $dt['pct_egresos'] }}%</div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill {{ $dt['pct_egresos'] >= 80 ? '' : ($dt['pct_egresos'] >= 50 ? 'warning' : 'danger') }}"
                         style="width:{{ min($dt['pct_egresos'], 100) }}%"></div>
                </div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead><tr><th>Concepto</th><th>Tipo</th><th style="text-align:right">Presupuestado</th><th style="text-align:right">Ejecutado</th><th style="text-align:right">%</th></tr></thead>
        <tbody>
            @foreach($dt['detalle'] as $d)
            <tr>
                <td>{{ $d->concepto }}</td>
                <td><span class="badge {{ $d->tipo === 'ingreso' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($d->tipo) }}</span></td>
                <td style="text-align:right">{{ number_format($d->presupuestado, 0, ',', '.') }}</td>
                <td style="text-align:right">{{ number_format($d->ejecutado, 0, ',', '.') }}</td>
                <td style="text-align:right">
                    <span class="badge {{ $d->pct_ejecucion >= 80 ? 'badge-success' : ($d->pct_ejecucion >= 50 ? 'badge-warning' : 'badge-danger') }}">
                        {{ $d->pct_ejecucion }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── Firma y validación ── --}}
<div class="section">
    <hr>
    <table style="width:100%; margin-top:10px;">
        <tr>
            <td style="width:50%; text-align:center; padding:10px;">
                <div style="border-top:1px solid #333; padding-top:4px; font-size:8px;">
                    Dirección de Planificación Estratégica<br>
                    <em>Generado automáticamente por SIESS</em>
                </div>
            </td>
            <td style="width:50%; text-align:center; padding:10px;">
                <div style="border-top:1px solid #333; padding-top:4px; font-size:8px;">
                    Presidencia / Alta Gerencia<br>
                    <em>Destinatario del informe</em>
                </div>
            </td>
        </tr>
    </table>
    <div style="text-align:center; font-size:7px; color:#6c757d; margin-top:8px;">
        Documento generado conforme Resolución Nº 266/2022 — IPS — Sistema SIESS v1.0
    </div>
</div>

</body>
</html>
