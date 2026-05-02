@extends('layouts.master')
@section('title', 'RH — Recursos Humanos')
@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-id-badge mr-2"></i>RH — Recursos Humanos</h4>
        <p class="card-category">Módulo 7 — Periodicidad Mensual/Anual</p>
    </div>
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">SIESS</a></li>
            <li class="breadcrumb-item active">Recursos Humanos</li>
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
                ['label'=>'Total Funcionarios',     'valor'=>number_format($resumen['total']),                                                    'color'=>'info',    'icon'=>'fa-users'],
                ['label'=>'Mujeres',                'valor'=>number_format($resumen['mujeres']),                                                  'color'=>'warning', 'icon'=>'fa-female'],
                ['label'=>'Hombres',                'valor'=>number_format($resumen['hombres']),                                                  'color'=>'primary', 'icon'=>'fa-male'],
                ['label'=>'Con Discapacidad',       'valor'=>number_format($resumen['con_discapacidad']),                                         'color'=>'success', 'icon'=>'fa-wheelchair'],
                ['label'=>'Masa Salarial Presup.',  'valor'=>'Gs. '.number_format($resumen['masa_salarial_presup']??0,0,',','.'),                 'color'=>'secondary','icon'=>'fa-money-bill'],
                ['label'=>'Masa Salarial Deveng.',  'valor'=>'Gs. '.number_format($resumen['masa_salarial_devengada']??0,0,',','.'),              'color'=>'dark',    'icon'=>'fa-check-circle'],
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
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Funcionarios por Grupo Ocupacional</h6></div>
                    <div class="card-body"><canvas id="chartGrupo" height="180"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0">Movimientos del Período</h6></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light"><tr><th>Tipo</th><th class="text-right">Cantidad</th><th class="text-right">Valor</th></tr></thead>
                            <tbody>
                                @forelse($movimientos as $m)
                                <tr>
                                    <td><span class="badge badge-secondary">{{ str_replace('_',' ',ucfirst($m->tipo)) }}</span></td>
                                    <td class="text-right">{{ number_format($m->total) }}</td>
                                    <td class="text-right">{{ $m->valor ? 'Gs. '.number_format($m->valor,0,',','.') : '—' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-3"><em>Sin movimientos registrados.</em></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header"><h6 class="mb-0">Detalle por Grupo Ocupacional</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>Grupo</th><th class="text-right">Funcionarios</th><th class="text-right">Salario Promedio</th></tr></thead>
                    <tbody>
                        @forelse($resumen['por_grupo'] as $g)
                        <tr>
                            <td>{{ $g->grupo_ocupacional ?? '—' }}</td>
                            <td class="text-right">{{ number_format($g->total) }}</td>
                            <td class="text-right">Gs. {{ number_format($g->salario_promedio??0,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3"><em>Sin datos.</em></td></tr>
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
    new Chart(document.getElementById('chartGrupo'), {
        type: 'bar',
        data: { labels: @json($resumen['por_grupo']->pluck('grupo_ocupacional')), datasets: [{ label: 'Funcionarios', data: @json($resumen['por_grupo']->pluck('total')), backgroundColor: 'rgba(23,162,184,0.7)' }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@stop
