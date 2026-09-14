@extends('layouts.master')
@section('title', 'Panel hospitalario SP10')

@php
    $actual = $panel['actual'] ?? [];
    $anterior = $panel['anterior'] ?? [];
    $kpis = [
        ['egresos_total', 'Egresos', ''],
        ['ingresos_total', 'Ingresos', ''],
        ['estancia_media', 'Estancia media', ' días'],
        ['mortalidad', 'Mortalidad', ' %'],
        ['cirugias', 'Cirugías', ''],
        ['cesareas', 'Cesáreas', ''],
        ['porcentaje_cesareas', '% cesáreas', ' %'],
        ['recien_nacidos', 'Recién nacidos', ''],
        ['ocupacion', 'Ocupación', ' %'],
        ['rotacion', 'Rotación de camas', ''],
        ['intervalo_sustitucion', 'Intervalo de sustitución', ''],
    ];
@endphp

@section('content')
<div class="card">
    <div class="card-header card-header-rose">
        <h4 class="card-title"><i class="material-icons">monitor_heart</i> Panel hospitalario</h4>
        <p class="card-category">Indicadores del período versus el mes anterior. Ocupación y rotación combinan SP10 con SP11.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form class="form-row align-items-end mb-4">
            <div class="form-group col-md-4">
                <label>Establecimiento</label>
                <select class="form-control" name="establecimiento_id" required>
                    @foreach($establecimientos as $establecimiento)
                        <option value="{{ $establecimiento->id }}" @selected($establecimientoId == $establecimiento->id)>{{ $establecimiento->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Año</label>
                @include('admin.bioestadistica._periodo-anio-select', [
                    'name' => 'periodo_anio',
                    'value' => $periodo_anio,
                    'required' => true,
                ])
            </div>
            <div class="form-group col-md-2">
                <label>Mes</label>
                <select class="form-control" name="periodo_mes">@foreach($months as $number => $month)<option value="{{ $number }}" @selected($periodo_mes == $number)>{{ $month }}</option>@endforeach</select>
            </div>
            <div class="form-group col-md-2"><button class="btn btn-info">Actualizar</button></div>
        </form>

        @can('bio.hosp.manage')
        <form method="POST" action="{{ route('bioestadistica.hospitalizacion.consolidate') }}" class="mb-4">
            @csrf
            <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
            <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
            <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">
            <button class="btn btn-warning">Consolidar SP10 del período</button>
        </form>
        @endcan

        <div class="row">
            @foreach($kpis as [$key, $label, $suffix])
                @php
                    $now = $actual[$key] ?? null;
                    $prev = $anterior[$key] ?? null;
                    $delta = is_numeric($now) && is_numeric($prev) ? $now - $prev : null;
                @endphp
                <div class="col-md-3">
                    <div class="card card-stats">
                        <div class="card-header card-header-icon card-header-info">
                            <div class="card-icon"><i class="material-icons">analytics</i></div>
                            <p class="card-category">{{ $label }}</p>
                            <h3 class="card-title">{{ $now === null ? '—' : $now }}{{ $now === null ? '' : $suffix }}</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                Mes anterior: {{ $prev === null ? '—' : $prev }}{{ $prev === null ? '' : $suffix }}
                                @if($delta !== null)
                                    <span class="{{ $delta >= 0 ? 'text-success' : 'text-danger' }}">({{ $delta >= 0 ? '+' : '' }}{{ round($delta, 2) }})</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h5 class="mt-3">Egresos por servicio</h5>
        <ul>
            @forelse($actual['egresos_por_servicio'] ?? [] as $servicio => $count)
                <li>{{ $servicio ?: 'Sin servicio' }}: {{ $count }}</li>
            @empty
                <li class="text-muted">Sin egresos en el período.</li>
            @endforelse
        </ul>

        <canvas id="hospTrend" height="90"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trend = @json($panel['tendencia'] ?? []);
    const ctx = document.getElementById('hospTrend');
    if (!ctx || !trend.length) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: trend.map(p => p.periodo),
            datasets: [
                { label: 'Egresos', data: trend.map(p => p.egresos), borderColor: '#26c6da', tension: 0.2 },
                { label: 'Estancia media', data: trend.map(p => p.estancia_media), borderColor: '#ab47bc', tension: 0.2, yAxisID: 'y1' },
                { label: 'Ocupación %', data: trend.map(p => p.ocupacion), borderColor: '#ffa726', tension: 0.2, yAxisID: 'y1' }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true }, y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } } }
        }
    });
});
</script>
@endsection
