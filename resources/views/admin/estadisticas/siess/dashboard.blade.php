@extends('layouts.master')
@section('title', 'SIESS — Dashboard Estadístico')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-chart-bar mr-2"></i>SIESS — Sistema de Estadísticas e Información</h4>
        <p class="card-category">Resolución Nº 266/2022 — Instituto de Previsión Social</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">SIESS Dashboard</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPIs Globales ── --}}
        <div class="row mb-4">
            @php
            $kpiCards = [
                ['label' => 'Total Extractos',       'valor' => $kpis['total'],              'color' => 'info',    'icon' => 'fa-database'],
                ['label' => 'Pendientes Validación', 'valor' => $kpis['pendientes'],          'color' => 'warning', 'icon' => 'fa-clock'],
                ['label' => 'Aprobados',             'valor' => $kpis['aprobados'],           'color' => 'success', 'icon' => 'fa-check-circle'],
                ['label' => 'Objetados',             'valor' => $kpis['objetados'],           'color' => 'danger',  'icon' => 'fa-times-circle'],
                ['label' => 'Vencidos Hoy',          'valor' => $kpis['vencidos_hoy'],        'color' => 'danger',  'icon' => 'fa-exclamation-triangle'],
                ['label' => 'Aprobados por Silencio','valor' => $kpis['aprobados_silencio'],  'color' => 'secondary','icon' => 'fa-volume-mute'],
            ];
            @endphp

            @foreach($kpiCards as $kpi)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-{{ $kpi['color'] }} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-{{ $kpi['color'] }} text-uppercase mb-1">
                                    {{ $kpi['label'] }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kpi['valor'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa {{ $kpi['icon'] }} fa-2x text-{{ $kpi['color'] }} opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Alertas: extractos próximos a vencer ── --}}
        @if($alertas->isNotEmpty())
        <div class="alert alert-warning mb-4">
            <h6 class="font-weight-bold"><i class="fa fa-exclamation-triangle mr-1"></i> Extractos pendientes de validación</h6>
            <div class="table-responsive">
                <table class="table table-sm table-borderless mb-0">
                    <thead><tr>
                        <th>Módulo</th><th>Indicador</th><th>Período</th><th>Días restantes</th><th>Estado</th>
                    </tr></thead>
                    <tbody>
                        @foreach($alertas as $a)
                        <tr>
                            <td><small>{{ $a['modulo'] }}</small></td>
                            <td><small>{{ $a['indicador'] }}</small></td>
                            <td><small>{{ $a['periodo'] }}</small></td>
                            <td>
                                @if($a['dias_restantes'] === 0)
                                    <span class="badge badge-danger">Vence HOY</span>
                                @elseif($a['dias_restantes'] <= 2)
                                    <span class="badge badge-warning">{{ $a['dias_restantes'] }}d</span>
                                @else
                                    <span class="badge badge-info">{{ $a['dias_restantes'] }}d</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $a['badge'] }}">Pendiente</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ── Gráfico de barras por módulo ── --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="font-weight-bold mb-0"><i class="fa fa-chart-bar mr-1"></i> Extractos por Módulo y Estado</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartModulos" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="font-weight-bold mb-0"><i class="fa fa-chart-pie mr-1"></i> Distribución Global</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEstados" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tabla de módulos con estado ── --}}
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0"><i class="fa fa-list mr-1"></i> Estado por Módulo</h6>
                <div>
                    <a href="{{ route('siess.reportes.index') }}" class="btn btn-sm btn-danger mr-1">
                        <i class="fa fa-file-pdf mr-1"></i> Reportes Gerenciales
                    </a>
                    <a href="{{ route('siess.extractos.index') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-database mr-1"></i> Gestionar Extractos
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Módulo</th>
                                <th>Periodicidad</th>
                                <th class="text-center">Borrador</th>
                                <th class="text-center">Pendiente</th>
                                <th class="text-center">Aprobado</th>
                                <th class="text-center">Objetado</th>
                                <th class="text-center">Silencio</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Ver</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumenModulos as $item)
                            @php
                                $e = $item['estados'];
                                $total = array_sum($e);
                                $rutaModulo = match($item['modulo']->codigo) {
                                    'AOP' => route('siess.modulos.aop'),
                                    'JU'  => route('siess.modulos.ju'),
                                    'DT'  => route('siess.modulos.dt'),
                                    'RL'  => route('siess.modulos.rl'),
                                    'DI'  => route('siess.modulos.di'),
                                    'RH'  => route('siess.modulos.rh'),
                                    'CAU' => route('siess.modulos.cau'),
                                    default => null,
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge badge-secondary mr-1">{{ $item['modulo']->codigo }}</span>
                                    {{ $item['modulo']->nombre }}
                                </td>
                                <td><small class="text-muted">{{ ucfirst($item['modulo']->periodicidad) }}</small></td>
                                <td class="text-center"><span class="badge badge-secondary">{{ $e['borrador'] }}</span></td>
                                <td class="text-center"><span class="badge badge-warning">{{ $e['pendiente_validacion'] }}</span></td>
                                <td class="text-center"><span class="badge badge-success">{{ $e['aprobado'] }}</span></td>
                                <td class="text-center"><span class="badge badge-danger">{{ $e['objetado'] }}</span></td>
                                <td class="text-center"><span class="badge badge-info">{{ $e['aprobado_silencio'] }}</span></td>
                                <td class="text-center"><strong>{{ $total }}</strong></td>
                                <td class="text-center">
                                    @if($rutaModulo)
                                        <a href="{{ $rutaModulo }}" class="btn btn-info btn-circle" title="Ver módulo">
                                            <i class="fa fa-chart-bar"></i>
                                        </a>
                                    @else
                                        <span class="badge badge-light">Próximamente</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- card-body --}}
</div>{{-- card --}}
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    // ── Gráfico de barras por módulo ──────────────────────────────────────────
    var chartData = @json($chartData);

    new Chart(document.getElementById('chartModulos'), {
        type: 'bar',
        data: {
            labels: chartData.map(d => d.label),
            datasets: [
                {
                    label: 'Aprobados',
                    data: chartData.map(d => d.aprobado),
                    backgroundColor: 'rgba(40,167,69,0.7)',
                },
                {
                    label: 'Pendientes',
                    data: chartData.map(d => d.pendiente),
                    backgroundColor: 'rgba(255,193,7,0.7)',
                },
                {
                    label: 'Objetados',
                    data: chartData.map(d => d.objetado),
                    backgroundColor: 'rgba(220,53,69,0.7)',
                },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { x: { stacked: false }, y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // ── Gráfico de torta global ───────────────────────────────────────────────
    new Chart(document.getElementById('chartEstados'), {
        type: 'doughnut',
        data: {
            labels: ['Borrador', 'Pendiente', 'Aprobado', 'Objetado', 'Silencio'],
            datasets: [{
                data: [
                    {{ $kpis['total'] - $kpis['pendientes'] - $kpis['aprobados'] - $kpis['objetados'] - $kpis['aprobados_silencio'] }},
                    {{ $kpis['pendientes'] }},
                    {{ $kpis['aprobados'] }},
                    {{ $kpis['objetados'] }},
                    {{ $kpis['aprobados_silencio'] }},
                ],
                backgroundColor: ['#6c757d','#ffc107','#28a745','#dc3545','#17a2b8'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>
@stop
