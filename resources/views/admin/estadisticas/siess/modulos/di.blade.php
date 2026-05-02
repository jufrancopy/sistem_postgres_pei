@extends('layouts.master')
@section('title', 'DI — Inversiones')
@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-chart-line mr-2"></i>DI — Ingresos, Gastos e Inversiones</h4>
        <p class="card-category">Módulo 5 — Periodicidad Mensual</p>
    </div>
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Inversiones</li>
        </ol>
    </nav>
    <div class="card-body">
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

        {{-- KPIs Portafolio --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-success shadow">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Portafolio Total</div>
                        <div class="h5 mb-0 font-weight-bold">Gs. {{ number_format($resumen['monto_total']??0,0,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-info shadow">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rentabilidad Promedio</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $resumen['rentabilidad_prom'] }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-warning shadow">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Instrumentos</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $resumen['por_modalidad']->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Portafolio por Modalidad</h6></div>
                    <div class="card-body"><canvas id="chartModalidad" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Distribución por Divisa</h6></div>
                    <div class="card-body"><canvas id="chartDivisa" height="200"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Préstamos de Caja --}}
        @if($prestamos->isNotEmpty())
        <div class="card shadow">
            <div class="card-header"><h6 class="mb-0"><i class="fa fa-hand-holding-usd mr-1"></i> Préstamos de Caja</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>Tipo Cliente</th><th class="text-right">Cantidad</th><th class="text-right">Monto Total</th><th class="text-right">Morosidad</th></tr></thead>
                    <tbody>
                        @foreach($prestamos as $p)
                        <tr>
                            <td>{{ ucfirst($p->tipo_cliente) }}</td>
                            <td class="text-right">{{ number_format($p->cantidad) }}</td>
                            <td class="text-right">Gs. {{ number_format($p->monto??0,0,',','.') }}</td>
                            <td class="text-right {{ $p->morosidad > 0 ? 'text-danger font-weight-bold' : '' }}">Gs. {{ number_format($p->morosidad??0,0,',','.') }}</td>
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
    new Chart(document.getElementById('chartModalidad'), {
        type: 'doughnut',
        data: { labels: @json($resumen['por_modalidad']->pluck('modalidad')), datasets: [{ data: @json($resumen['por_modalidad']->pluck('monto_total')), backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545','#17a2b8','#6c757d'] }] },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    new Chart(document.getElementById('chartDivisa'), {
        type: 'pie',
        data: { labels: @json($resumen['por_divisa']->pluck('divisa')), datasets: [{ data: @json($resumen['por_divisa']->pluck('monto_total')), backgroundColor: ['#1a3a5c','#2c5f8a','#4a90d9','#7ab3e0'] }] },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
});
</script>
@stop
