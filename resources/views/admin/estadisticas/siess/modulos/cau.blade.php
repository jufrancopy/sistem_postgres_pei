@extends('layouts.master')
@section('title', 'CAU — Atención al Usuario')
@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-headset mr-2"></i>CAU — Suministros de Salud y Atención al Usuario</h4>
        <p class="card-category">Módulo 9 — Periodicidad Mensual</p>
    </div>
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Atención al Usuario</li>
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
                ['label'=>'Total Contactos',       'valor'=>number_format($resumen['total_contactos']),                                    'color'=>'info',    'icon'=>'fa-phone'],
                ['label'=>'Abandonos',             'valor'=>number_format($resumen['total_abandonos']),                                    'color'=>'danger',  'icon'=>'fa-phone-slash'],
                ['label'=>'Tasa de Abandono',      'valor'=>$resumen['tasa_abandono_global'].'%',                                         'color'=>$resumen['tasa_abandono_global']<=5?'success':'danger', 'icon'=>'fa-percentage'],
                ['label'=>'Transferencias',        'valor'=>number_format($resumen['total_transferencias']),                               'color'=>'warning', 'icon'=>'fa-exchange-alt'],
                ['label'=>'T. Espera Prom.',       'valor'=>$resumen['tiempo_espera_prom'].'s',                                           'color'=>'secondary','icon'=>'fa-clock'],
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
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Contactos por Canal</h6></div>
                    <div class="card-body"><canvas id="chartCanal" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Evolución Mensual — Contactos vs Abandonos</h6></div>
                    <div class="card-body"><canvas id="chartEvol" height="200"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Detalle por canal --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h6 class="mb-0">Detalle por Canal</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>Canal</th><th class="text-right">Contactos</th><th class="text-right">Abandonos</th><th class="text-right">Tasa Abandono</th><th class="text-right">T. Espera Prom.</th></tr></thead>
                    <tbody>
                        @forelse($resumen['por_canal'] as $c)
                        <tr>
                            <td><span class="badge badge-info">{{ ucfirst($c->canal) }}</span></td>
                            <td class="text-right">{{ number_format($c->total_contactos) }}</td>
                            <td class="text-right">{{ number_format($c->abandonos) }}</td>
                            <td class="text-right"><span class="badge {{ $c->tasa_abandono<=5?'badge-success':($c->tasa_abandono<=15?'badge-warning':'badge-danger') }}">{{ $c->tasa_abandono }}%</span></td>
                            <td class="text-right">{{ round($c->tiempo_espera_promedio_seg??0) }}s</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3"><em>Sin datos para este período.</em></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Suministros de Salud --}}
        @if($suministros->isNotEmpty())
        <div class="card shadow">
            <div class="card-header"><h6 class="mb-0"><i class="fa fa-pills mr-1"></i> Suministros de Salud</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>Tipo</th><th class="text-right">Items</th><th class="text-right">Producido</th><th class="text-right">Entregado</th></tr></thead>
                    <tbody>
                        @foreach($suministros as $s)
                        <tr>
                            <td>{{ ucfirst(str_replace('_',' ',$s->tipo)) }}</td>
                            <td class="text-right">{{ number_format($s->items) }}</td>
                            <td class="text-right">{{ number_format($s->producida??0) }}</td>
                            <td class="text-right">{{ number_format($s->entregada??0) }}</td>
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
    new Chart(document.getElementById('chartCanal'), {
        type: 'doughnut',
        data: { labels: @json($resumen['por_canal']->pluck('canal')->map(fn($c)=>ucfirst($c))), datasets: [{ data: @json($resumen['por_canal']->pluck('total_contactos')), backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545','#17a2b8'] }] },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    new Chart(document.getElementById('chartEvol'), {
        type: 'bar',
        data: {
            labels: @json($evolucion->pluck('mes')),
            datasets: [
                { label: 'Contactos', data: @json($evolucion->pluck('contactos')), backgroundColor: 'rgba(23,162,184,0.7)' },
                { label: 'Abandonos', data: @json($evolucion->pluck('abandonos')), backgroundColor: 'rgba(220,53,69,0.7)' },
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@stop
