@extends('layouts.master')
@section('title', 'RL — Subsidios y Riesgo Laboral')
@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-medkit mr-2"></i>RL — Subsidios y Riesgo Laboral</h4>
        <p class="card-category">Módulo 4 — Periodicidad Mensual</p>
    </div>
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Subsidios</li>
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

        {{-- KPIs --}}
        <div class="row mb-4">
            @php $kpis = [
                ['label'=>'Total Subsidios',    'valor'=>number_format($resumen['total']),                                          'color'=>'info',    'icon'=>'fa-file-medical'],
                ['label'=>'Monto Total',        'valor'=>'Gs. '.number_format($resumen['monto_total']??0,0,',','.'),               'color'=>'success', 'icon'=>'fa-money-bill'],
                ['label'=>'Días Prom. Reposo',  'valor'=>$resumen['dias_promedio'].' días',                                        'color'=>'warning', 'icon'=>'fa-bed'],
                ['label'=>'Casos COVID',        'valor'=>number_format($resumen['covid']),                                         'color'=>'danger',  'icon'=>'fa-virus'],
                ['label'=>'Primer Reposo',      'valor'=>number_format($resumen['primer_reposo']),                                  'color'=>'primary', 'icon'=>'fa-plus-circle'],
                ['label'=>'Extensiones',        'valor'=>number_format($resumen['extensiones']),                                   'color'=>'secondary','icon'=>'fa-redo'],
            ]; @endphp
            @foreach($kpis as $k)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-{{ $k['color'] }} shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-{{ $k['color'] }} text-uppercase mb-1">{{ $k['label'] }}</div>
                        <div class="h6 mb-0 font-weight-bold">{{ $k['valor'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mb-4">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Top 10 Diagnósticos</h6></div>
                    <div class="card-body"><canvas id="chartDiag" height="150"></canvas></div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Evolución Mensual</h6></div>
                    <div class="card-body"><canvas id="chartEvol" height="150"></canvas></div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header"><h6 class="mb-0">Detalle por Diagnóstico</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>Diagnóstico</th><th class="text-right">Casos</th><th class="text-right">Monto Total</th><th class="text-right">Días Prom.</th></tr></thead>
                    <tbody>
                        @forelse($porDiagnostico as $d)
                        <tr>
                            <td>{{ $d->diagnostico ?? '—' }}</td>
                            <td class="text-right">{{ number_format($d->total) }}</td>
                            <td class="text-right">Gs. {{ number_format($d->monto_total??0,0,',','.') }}</td>
                            <td class="text-right">{{ round($d->dias_promedio??0) }}d</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3"><em>Sin datos para este período.</em></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    new Chart(document.getElementById('chartDiag'), {
        type: 'bar',
        data: { labels: @json($porDiagnostico->pluck('diagnostico')), datasets: [{ label: 'Casos', data: @json($porDiagnostico->pluck('total')), backgroundColor: 'rgba(220,53,69,0.7)' }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
    new Chart(document.getElementById('chartEvol'), {
        type: 'line',
        data: { labels: @json($evolucion->pluck('mes')), datasets: [{ label: 'Subsidios', data: @json($evolucion->pluck('total')), borderColor: '#dc3545', tension: 0.3, fill: false }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@stop
