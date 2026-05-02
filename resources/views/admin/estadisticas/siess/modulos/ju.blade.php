@extends('layouts.master')
@section('title', 'JU — Jubilaciones y Pensiones')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-hand-holding-usd mr-2"></i>JU — Jubilaciones y Pensiones</h4>
        <p class="card-category">Módulo 3 — Periodicidad Mensual/Anual</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Jubilaciones y Pensiones</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- Selector período --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <form method="GET">
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
        </div>

        {{-- KPIs --}}
        <div class="row mb-4">
            @php
            $kpis = [
                ['label' => 'Total Beneficiarios', 'valor' => number_format($resumen['total']),                                          'color' => 'info',    'icon' => 'fa-users'],
                ['label' => 'Mujeres',             'valor' => number_format($resumen['mujeres']),                                        'color' => 'warning', 'icon' => 'fa-female'],
                ['label' => 'Hombres',             'valor' => number_format($resumen['hombres']),                                        'color' => 'primary', 'icon' => 'fa-male'],
                ['label' => 'Monto Total',         'valor' => 'Gs. ' . number_format($resumen['monto_total'] ?? 0, 0, ',', '.'),         'color' => 'success', 'icon' => 'fa-money-bill-wave'],
                ['label' => 'Monto Promedio',      'valor' => 'Gs. ' . number_format($resumen['monto_promedio'] ?? 0, 0, ',', '.'),      'color' => 'secondary','icon' => 'fa-calculator'],
                ['label' => 'Edad Promedio',       'valor' => round($resumen['edad_promedio'] ?? 0) . ' años',                           'color' => 'dark',    'icon' => 'fa-birthday-cake'],
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

        {{-- Relación Activo/Pasivo --}}
        @if($financiero)
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-1" style="font-size:.75rem">Relación Activo/Pasivo</h6>
                        <h2 class="font-weight-bold {{ ($financiero->relacion_activo_pasivo ?? 0) >= 4 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($financiero->relacion_activo_pasivo ?? 0, 2) }}
                        </h2>
                        <small class="text-muted">{{ number_format($financiero->total_activos) }} activos / {{ number_format($financiero->total_pasivos) }} pasivos</small>
                        <div class="mt-2">
                            @if(($financiero->relacion_activo_pasivo ?? 0) >= 4)
                                <span class="badge badge-success">Sostenible</span>
                            @elseif(($financiero->relacion_activo_pasivo ?? 0) >= 2)
                                <span class="badge badge-warning">En observación</span>
                            @else
                                <span class="badge badge-danger">Crítico</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-line mr-1"></i> Evolución Relación Activo/Pasivo {{ now()->year }}</h6></div>
                    <div class="card-body"><canvas id="chartRelacion" height="100"></canvas></div>
                </div>
            </div>
        </div>
        @endif

        {{-- Gráficos --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-pie mr-1"></i> Beneficiarios por Concepto</h6></div>
                    <div class="card-body"><canvas id="chartConcepto" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-bar mr-1"></i> Top 10 Departamentos</h6></div>
                    <div class="card-body"><canvas id="chartDepartamentos" height="200"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Altas y Solicitudes --}}
        @if($altas->isNotEmpty())
        <div class="card shadow mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fa fa-clock mr-1"></i> Tiempos de Concesión</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>Tipo</th><th>Concepto</th><th class="text-right">Cantidad</th><th class="text-right">Tiempo Promedio</th><th class="text-right">Mín.</th><th class="text-right">Máx.</th></tr>
                    </thead>
                    <tbody>
                        @foreach($altas as $a)
                        <tr>
                            <td><span class="badge {{ $a->tipo === 'alta' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($a->tipo) }}</span></td>
                            <td>{{ $a->concepto }}</td>
                            <td class="text-right">{{ number_format($a->cantidad) }}</td>
                            <td class="text-right">{{ $a->tiempo_promedio_dias ? round($a->tiempo_promedio_dias) . ' días' : '—' }}</td>
                            <td class="text-right">{{ $a->tiempo_minimo_dias ? $a->tiempo_minimo_dias . 'd' : '—' }}</td>
                            <td class="text-right">{{ $a->tiempo_maximo_dias ? $a->tiempo_maximo_dias . 'd' : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    // Por concepto
    new Chart(document.getElementById('chartConcepto'), {
        type: 'doughnut',
        data: {
            labels: @json($porConcepto->pluck('concepto')),
            datasets: [{ data: @json($porConcepto->pluck('total')), backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545','#17a2b8','#6c757d'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Por departamento
    new Chart(document.getElementById('chartDepartamentos'), {
        type: 'bar',
        data: {
            labels: @json($porDepartamento->pluck('departamento_nombre')),
            datasets: [{ label: 'Beneficiarios', data: @json($porDepartamento->pluck('total')), backgroundColor: 'rgba(40,167,69,0.7)' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // Relación activo/pasivo
    @if($evolucionFinanciero->isNotEmpty())
    new Chart(document.getElementById('chartRelacion'), {
        type: 'line',
        data: {
            labels: @json($evolucionFinanciero->pluck('mes')),
            datasets: [{
                label: 'Relación Activo/Pasivo',
                data: @json($evolucionFinanciero->pluck('relacion_activo_pasivo')),
                borderColor: '#007bff', tension: 0.3, fill: false,
                pointBackgroundColor: '#007bff',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: false, suggestedMin: 0 } }
        }
    });
    @endif
});
</script>
@stop
