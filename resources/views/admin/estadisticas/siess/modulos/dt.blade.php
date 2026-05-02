@extends('layouts.master')
@section('title', 'DT — Tesorería y Contabilidad')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-balance-scale mr-2"></i>DT — Tesorería y Contabilidad</h4>
        <p class="card-category">Módulo 6 — Ejecución Presupuestaria</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Tesorería y Contabilidad</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- Selectores --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <form method="GET" id="formFiltro">
                    <div class="input-group">
                        <select name="periodo_id" class="form-control" onchange="this.form.submit()">
                            @foreach($periodos as $p)
                                <option value="{{ $p->id }}" {{ $p->id == $periodo?->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET">
                    <div class="input-group">
                        <select name="anio" class="form-control" onchange="this.form.submit()">
                            @foreach($anios as $a)
                                <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append"><span class="input-group-text">Año</span></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- KPIs Presupuestarios --}}
        <div class="row mb-4">
            @php
            $r = $resumenPresupuesto;
            $kpis = [
                ['label' => 'Ingresos Presupuestados', 'valor' => 'Gs. ' . number_format($r['ingresos_presupuestados'], 0, ',', '.'), 'color' => 'info',    'icon' => 'fa-arrow-down'],
                ['label' => 'Ingresos Ejecutados',     'valor' => 'Gs. ' . number_format($r['ingresos_ejecutados'], 0, ',', '.'),     'color' => 'success', 'icon' => 'fa-check'],
                ['label' => '% Ejecución Ingresos',    'valor' => $r['pct_ejecucion_ingresos'] . '%',                                 'color' => $r['pct_ejecucion_ingresos'] >= 80 ? 'success' : 'warning', 'icon' => 'fa-percentage'],
                ['label' => 'Egresos Presupuestados',  'valor' => 'Gs. ' . number_format($r['egresos_presupuestados'], 0, ',', '.'),  'color' => 'warning', 'icon' => 'fa-arrow-up'],
                ['label' => 'Egresos Ejecutados',      'valor' => 'Gs. ' . number_format($r['egresos_ejecutados'], 0, ',', '.'),      'color' => 'danger',  'icon' => 'fa-minus'],
                ['label' => '% Ejecución Egresos',     'valor' => $r['pct_ejecucion_egresos'] . '%',                                  'color' => $r['pct_ejecucion_egresos'] >= 80 ? 'success' : 'warning', 'icon' => 'fa-percentage'],
            ];
            @endphp
            @foreach($kpis as $kpi)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-{{ $kpi['color'] }} shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-{{ $kpi['color'] }} text-uppercase mb-1">{{ $kpi['label'] }}</div>
                        <div class="h6 mb-0 font-weight-bold">{{ $kpi['valor'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Gráfico evolución anual --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-line mr-1"></i> Evolución Presupuestaria {{ $anio }}</h6></div>
            <div class="card-body"><canvas id="chartEvolucion" height="80"></canvas></div>
        </div>

        {{-- Tesorería --}}
        @if($tesoreria->isNotEmpty())
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-pie mr-1"></i> Pagos por Tipo</h6></div>
                    <div class="card-body"><canvas id="chartTesoreria" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-table mr-1"></i> Detalle Tesorería</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light"><tr><th>Tipo</th><th class="text-right">Monto</th><th class="text-right">Operaciones</th></tr></thead>
                            <tbody>
                                @foreach($tesoreria as $t)
                                <tr>
                                    <td>{{ ucfirst($t->tipo) }}</td>
                                    <td class="text-right">Gs. {{ number_format($t->monto_total, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($t->operaciones ?? 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Detalle presupuestario --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white"><h6 class="mb-0">Ingresos — Detalle</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light"><tr><th>Concepto</th><th class="text-right">Presupuestado</th><th class="text-right">Ejecutado</th><th class="text-right">%</th></tr></thead>
                            <tbody>
                                @forelse($detalleIngresos as $d)
                                <tr>
                                    <td><small>{{ $d->concepto }}</small></td>
                                    <td class="text-right"><small>{{ number_format($d->presupuestado, 0, ',', '.') }}</small></td>
                                    <td class="text-right"><small>{{ number_format($d->ejecutado, 0, ',', '.') }}</small></td>
                                    <td class="text-right">
                                        <span class="badge {{ $d->pct_ejecucion >= 80 ? 'badge-success' : ($d->pct_ejecucion >= 50 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $d->pct_ejecucion }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-2"><em>Sin datos</em></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white"><h6 class="mb-0">Egresos — Detalle</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light"><tr><th>Concepto</th><th class="text-right">Presupuestado</th><th class="text-right">Ejecutado</th><th class="text-right">%</th></tr></thead>
                            <tbody>
                                @forelse($detalleEgresos as $d)
                                <tr>
                                    <td><small>{{ $d->concepto }}</small></td>
                                    <td class="text-right"><small>{{ number_format($d->presupuestado, 0, ',', '.') }}</small></td>
                                    <td class="text-right"><small>{{ number_format($d->ejecutado, 0, ',', '.') }}</small></td>
                                    <td class="text-right">
                                        <span class="badge {{ $d->pct_ejecucion >= 80 ? 'badge-success' : ($d->pct_ejecucion >= 50 ? 'badge-warning' : 'badge-danger') }}">
                                            {{ $d->pct_ejecucion }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-2"><em>Sin datos</em></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    // Evolución anual
    @php
    $meses = $evolucion->pluck('mes')->unique()->sort()->values();
    $ingPres  = $evolucion->where('tipo','ingreso')->pluck('presupuestado','mes');
    $ingEjec  = $evolucion->where('tipo','ingreso')->pluck('ejecutado','mes');
    $egrPres  = $evolucion->where('tipo','egreso')->pluck('presupuestado','mes');
    $egrEjec  = $evolucion->where('tipo','egreso')->pluck('ejecutado','mes');
    @endphp
    new Chart(document.getElementById('chartEvolucion'), {
        type: 'bar',
        data: {
            labels: @json($meses),
            datasets: [
                { label: 'Ing. Presupuestado', data: @json($ingPres->values()), backgroundColor: 'rgba(23,162,184,0.4)', borderColor: '#17a2b8', borderWidth: 1 },
                { label: 'Ing. Ejecutado',     data: @json($ingEjec->values()), backgroundColor: 'rgba(40,167,69,0.7)',  borderColor: '#28a745', borderWidth: 1 },
                { label: 'Egr. Presupuestado', data: @json($egrPres->values()), backgroundColor: 'rgba(255,193,7,0.4)',  borderColor: '#ffc107', borderWidth: 1 },
                { label: 'Egr. Ejecutado',     data: @json($egrEjec->values()), backgroundColor: 'rgba(220,53,69,0.7)',  borderColor: '#dc3545', borderWidth: 1 },
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
    });

    // Tesorería
    @if($tesoreria->isNotEmpty())
    new Chart(document.getElementById('chartTesoreria'), {
        type: 'doughnut',
        data: {
            labels: @json($tesoreria->pluck('tipo')->map(fn($t) => ucfirst($t))),
            datasets: [{ data: @json($tesoreria->pluck('monto_total')), backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545','#17a2b8'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    @endif
});
</script>
@stop
