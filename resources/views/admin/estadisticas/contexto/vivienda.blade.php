@extends('layouts.master')
@section('title', 'Vivienda EPHC — Determinantes de Salud')

@section('content')
<div class="card">
    <div class="card-header card-header-warning">
        <h4 class="card-title"><i class="fa fa-home mr-2"></i>Vivienda EPHC — Determinantes Ambientales de Salud</h4>
        <p class="card-category">Predictores de enfermedad por departamento · Cruce con subsidios IPS (CP1)</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">Estadísticas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siess.contexto.index') }}">Contexto Nacional</a></li>
            <li class="breadcrumb-item active">Vivienda EPHC</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Procesar ── --}}
        @if($datasets->isNotEmpty())
        <div class="alert alert-light border mb-3">
            <h6 class="font-weight-bold"><i class="fa fa-cogs mr-1"></i> Procesar dataset de Vivienda</h6>
            @foreach($datasets as $ds)
            <form action="{{ route('siess.contexto.vivienda.procesar', $ds->id) }}" method="POST" class="d-inline-block mr-2 mb-1">
                @csrf
                <input type="hidden" name="anio" value="{{ $ds->anio }}">
                <button type="submit" class="btn btn-sm btn-warning">
                    <i class="fa fa-play mr-1"></i> Procesar "{{ Str::limit($ds->titulo, 30) }}" ({{ $ds->total_filas }} filas)
                </button>
            </form>
            @endforeach
        </div>
        @else
        <div class="alert alert-warning mb-3">
            <i class="fa fa-info-circle mr-1"></i>
            No hay datasets de Vivienda cargados.
            <a href="{{ route('siess.eph.create') }}" class="alert-link">Cargar el CSV de Vivienda EPHC</a>
            como categoría "Vivienda".
        </div>
        @endif

        {{-- ── Leyenda de indicadores ── --}}
        <div class="row mb-3">
            @foreach($indicadores as $campo => $info)
            <div class="col-md-2 col-sm-4 mb-2">
                <div class="card border-left-{{ $info['color'] }} shadow py-1">
                    <div class="card-body py-1 px-2">
                        <div class="text-xs font-weight-bold text-{{ $info['color'] }} text-uppercase">{{ $info['label'] }}</div>
                        <small class="text-muted d-block">{{ $info['variable'] }}</small>
                        <small class="text-danger">{{ $info['riesgo'] }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Filtro ── --}}
        <form method="GET" class="row mb-4 align-items-end">
            <div class="col-md-2">
                <select name="anio" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($aniosDisponibles as $a)
                        <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if($datos->isEmpty())
        <div class="alert alert-info">Sin datos de Vivienda procesados para {{ $anio }}.</div>
        @else

        {{-- ── Gráficos ── --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-warning">Determinantes Ambientales por Departamento (%)</h6>
                    </div>
                    <div class="card-body"><canvas id="chartVivienda" height="250"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold">NSE: Bajo vs Alto por Departamento (%)</h6>
                    </div>
                    <div class="card-body"><canvas id="chartNse" height="250"></canvas></div>
                </div>
            </div>
        </div>

        {{-- ── Tabla ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Departamento</th>
                        <th class="text-center text-danger">Sin agua potable<br><small class="font-weight-normal">V08∈{7,9,13}</small></th>
                        <th class="text-center text-warning">Cocina leña<br><small class="font-weight-normal">V14B=1</small></th>
                        <th class="text-center text-danger">Sin desagüe<br><small class="font-weight-normal">V13∈{3,4}</small></th>
                        <th class="text-center">Hacinados<br><small class="font-weight-normal">TOTAL/V02B>3</small></th>
                        <th class="text-center">Sin electricidad<br><small class="font-weight-normal">V10=6</small></th>
                        <th class="text-center">Bajo NSE</th>
                        <th class="text-center">Alto NSE</th>
                        <th class="text-center">Pobreza</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $d)
                    <tr class="{{ $d->departamento_codigo == 0 ? 'table-warning font-weight-bold' : '' }}">
                        <td>{{ $d->departamento_nombre }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $d->pct_sin_agua_potable >= 20 ? 'danger' : ($d->pct_sin_agua_potable >= 10 ? 'warning' : 'success') }}">
                                {{ number_format($d->pct_sin_agua_potable, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $d->pct_cocina_lena >= 30 ? 'danger' : ($d->pct_cocina_lena >= 15 ? 'warning' : 'success') }}">
                                {{ number_format($d->pct_cocina_lena, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $d->pct_sin_desague >= 20 ? 'danger' : ($d->pct_sin_desague >= 10 ? 'warning' : 'success') }}">
                                {{ number_format($d->pct_sin_desague, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">{{ number_format($d->pct_hacinados, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->pct_sin_electricidad, 1) }}%</td>
                        <td class="text-center text-danger">{{ number_format($d->pct_bajo_nse, 1) }}%</td>
                        <td class="text-center text-success">{{ number_format($d->pct_alto_nse, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->pct_pobreza, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="alert alert-info small mt-3">
            <i class="fa fa-info-circle mr-1"></i>
            <strong>Cruce con IPS:</strong> Departamentos con alto % de cocina a leña deberían mostrar mayor demanda de
            subsidios por enfermedades respiratorias en la tabla <code>rl_subsidios</code>.
            Departamentos con alto Bajo NSE y baja penetración IPS son zonas prioritarias de expansión.
        </div>
        @endif
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    @if(!$datos->isEmpty())
    $.get('{{ route('siess.contexto.chart.vivienda') }}?anio={{ $anio }}', function(data) {
        if (!data.labels) return;

        new Chart(document.getElementById('chartVivienda'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    { label: 'Sin agua potable', data: data.sin_agua,    backgroundColor: 'rgba(220,53,69,.7)' },
                    { label: 'Cocina con leña',  data: data.cocina_lena, backgroundColor: 'rgba(255,193,7,.7)' },
                    { label: 'Sin desagüe',      data: data.sin_desague, backgroundColor: 'rgba(23,162,184,.7)' },
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => v + '%' } },
                    x: { ticks: { font: { size: 9 } } }
                }
            }
        });

        // NSE
        var datosNse = @json($datos->where('departamento_codigo', '>', 0)->values());
        new Chart(document.getElementById('chartNse'), {
            type: 'bar',
            data: {
                labels: datosNse.map(d => d.departamento_nombre),
                datasets: [
                    { label: 'Bajo NSE', data: datosNse.map(d => d.pct_bajo_nse), backgroundColor: 'rgba(220,53,69,.7)' },
                    { label: 'Alto NSE', data: datosNse.map(d => d.pct_alto_nse), backgroundColor: 'rgba(40,167,69,.7)' },
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => v + '%' } },
                    x: { ticks: { font: { size: 9 } } }
                }
            }
        });
    });
    @endif
});
</script>
@stop
