@extends('layouts.master')
@section('title', 'AOP — Aportes y Trabajadores')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-users mr-2"></i>AOP — Aportes y Trabajadores</h4>
        <p class="card-category">Módulo 1 — Periodicidad Mensual</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Aportes y Trabajadores</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Selector de período ── --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <form method="GET">
                    <div class="input-group">
                        <select name="periodo_id" class="form-control" onchange="this.form.submit()">
                            @foreach($periodos as $p)
                                <option value="{{ $p->id }}" {{ $p->id == $periodo?->id ? 'selected' : '' }}>
                                    {{ $p->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8 text-right">
                <a href="{{ route('siess.extractos.index') }}?modulo_id=1" class="btn btn-sm btn-outline-info">
                    <i class="fa fa-database mr-1"></i> Ver Extractos AOP
                </a>
            </div>
        </div>

        @if(!$periodo)
            <div class="alert alert-warning">No hay períodos disponibles. Generá períodos primero.</div>
        @else

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            @php
            $kpis = [
                ['label' => 'Total Trabajadores',  'valor' => number_format($resumen['total']),           'color' => 'info',    'icon' => 'fa-users'],
                ['label' => 'Sector Público',      'valor' => number_format($resumen['publicos']),        'color' => 'primary', 'icon' => 'fa-building'],
                ['label' => 'Sector Privado',      'valor' => number_format($resumen['privados']),        'color' => 'success', 'icon' => 'fa-industry'],
                ['label' => 'Mujeres',             'valor' => number_format($resumen['mujeres']),         'color' => 'warning', 'icon' => 'fa-female'],
                ['label' => 'Hombres',             'valor' => number_format($resumen['hombres']),         'color' => 'secondary','icon' => 'fa-male'],
                ['label' => 'Salario Promedio',    'valor' => 'Gs. ' . number_format($resumen['salario_promedio'] ?? 0, 0, ',', '.'), 'color' => 'dark', 'icon' => 'fa-money-bill'],
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

        {{-- ── Recaudación y Mora ── --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-coins mr-1"></i> Recaudación del Período</h6></div>
                    <div class="card-body">
                        @if($recaudacion && $recaudacion->total > 0)
                        <table class="table table-sm mb-0">
                            <tr><td>Aportes Empleados</td><td class="text-right font-weight-bold">Gs. {{ number_format($recaudacion->empleado, 0, ',', '.') }}</td></tr>
                            <tr><td>Aportes Patronales</td><td class="text-right font-weight-bold">Gs. {{ number_format($recaudacion->empleador, 0, ',', '.') }}</td></tr>
                            <tr class="table-success"><td><strong>Total Recaudado</strong></td><td class="text-right font-weight-bold">Gs. {{ number_format($recaudacion->total, 0, ',', '.') }}</td></tr>
                        </table>
                        @else
                        <p class="text-muted mb-0"><em>Sin datos de recaudación para este período.</em></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-exclamation-triangle mr-1 text-danger"></i> Mora del Período</h6></div>
                    <div class="card-body">
                        @if($mora && $mora->empleadores_en_mora > 0)
                        <table class="table table-sm mb-0">
                            <tr><td>Empleadores en mora</td><td class="text-right font-weight-bold text-danger">{{ number_format($mora->empleadores_en_mora) }}</td></tr>
                            <tr><td>Monto total en mora</td><td class="text-right font-weight-bold text-danger">Gs. {{ number_format($mora->monto_total, 0, ',', '.') }}</td></tr>
                            <tr><td>Días promedio de mora</td><td class="text-right">{{ round($mora->dias_promedio) }} días</td></tr>
                        </table>
                        @else
                        <p class="text-muted mb-0"><em>Sin datos de mora para este período.</em></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Gráficos ── --}}
        <div class="row mb-4">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-bar mr-1"></i> Top 10 Departamentos por Trabajadores</h6></div>
                    <div class="card-body"><canvas id="chartDepartamentos" height="120"></canvas></div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-pie mr-1"></i> Público vs Privado</h6></div>
                    <div class="card-body"><canvas id="chartSector" height="200"></canvas></div>
                </div>
            </div>
        </div>

        {{-- ── Evolución mensual ── --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fa fa-chart-line mr-1"></i> Evolución Mensual (últimos 12 meses)</h6></div>
            <div class="card-body"><canvas id="chartEvolucion" height="80"></canvas></div>
        </div>

        {{-- ── Tabla por departamento ── --}}
        <div class="card shadow">
            <div class="card-header"><h6 class="mb-0"><i class="fa fa-table mr-1"></i> Detalle por Departamento</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr><th>Departamento</th><th class="text-right">Trabajadores</th><th class="text-right">Salario Promedio</th></tr>
                        </thead>
                        <tbody>
                            @forelse($porDepartamento as $dep)
                            <tr>
                                <td>{{ $dep->departamento_nombre ?? 'Sin datos' }}</td>
                                <td class="text-right">{{ number_format($dep->total) }}</td>
                                <td class="text-right">Gs. {{ number_format($dep->salario_promedio ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3"><em>Sin datos para este período. Cargá un extracto AOP5.</em></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    // Departamentos
    var depLabels = @json($porDepartamento->pluck('departamento_nombre'));
    var depData   = @json($porDepartamento->pluck('total'));
    new Chart(document.getElementById('chartDepartamentos'), {
        type: 'bar',
        data: {
            labels: depLabels,
            datasets: [{ label: 'Trabajadores', data: depData, backgroundColor: 'rgba(23,162,184,0.7)' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // Sector
    new Chart(document.getElementById('chartSector'), {
        type: 'doughnut',
        data: {
            labels: ['Público', 'Privado'],
            datasets: [{ data: [{{ $resumen['publicos'] }}, {{ $resumen['privados'] }}], backgroundColor: ['#007bff','#28a745'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Evolución
    @php
    $mesesLabel = $evolucion->map(fn($r) => $r->anio . '-' . str_pad($r->mes, 2, '0', STR_PAD_LEFT))->unique()->values();
    $pubData = $evolucion->where('tipo_empleado','publico')->pluck('total','mes')->toArray();
    $privData = $evolucion->where('tipo_empleado','privado')->pluck('total','mes')->toArray();
    @endphp
    new Chart(document.getElementById('chartEvolucion'), {
        type: 'line',
        data: {
            labels: @json($mesesLabel),
            datasets: [
                { label: 'Público',  data: @json(array_values($pubData)),  borderColor: '#007bff', tension: 0.3, fill: false },
                { label: 'Privado',  data: @json(array_values($privData)), borderColor: '#28a745', tension: 0.3, fill: false },
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@stop
