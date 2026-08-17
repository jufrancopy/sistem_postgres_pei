@extends('layouts.master')
@section('title', 'Bioestadísticas — Dashboard')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-heartbeat mr-2"></i>{{ $dashboard->nombre ?? 'Bioestadísticas' }}</h4>
        <p class="card-category">
            {{ $dashboard?->descripcion ?: 'Tablero institucional de Bioestadística' }}
            · Período de los datos, no fecha de consulta.
        </p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Bioestadísticas</li>
        </ol>
    </nav>

    <div class="card-body">
        @if($dashboard)
            <form method="GET" class="form-row align-items-end mb-3">
                <div class="form-group col-md-2 mb-2">
                    <label>Desde año</label>
                    <input class="form-control" type="number" name="periodo_desde_anio" value="{{ $period['desde']['anio'] }}">
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label>Desde mes</label>
                    <select class="form-control" name="periodo_desde_mes">
                        @foreach($months as $number => $label)
                            <option value="{{ $number }}" @selected((int) $period['desde']['mes'] === $number)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label>Hasta año</label>
                    <input class="form-control" type="number" name="periodo_hasta_anio" value="{{ $period['hasta']['anio'] }}">
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label>Hasta mes</label>
                    <select class="form-control" name="periodo_hasta_mes">
                        @foreach($months as $number => $label)
                            <option value="{{ $number }}" @selected((int) $period['hasta']['mes'] === $number)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <button class="btn btn-info">Aplicar período</button>
                </div>
            </form>
            @php $editable = false; @endphp
            @include('admin.bioestadistica.dashboards._grid')
        @else
            <div class="alert alert-warning">Todavía no hay una plantilla institucional activa. Configure un dashboard predeterminado.</div>
        @endif

        <hr>
        <p class="text-muted mb-2">Administración del módulo</p>
        <div class="row">
            <div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.formularios.index') }}">Formularios</a></div>
            <div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.catalogos.index') }}">Catálogos</a></div>
            <div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.geografia.index') }}">Geografía</a></div>
            @can('bio.indicator.view')<div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.indicadores.index') }}">Indicadores</a></div>@endcan
            @can('bio.report.view')<div class="col-md-3 mt-2"><a class="btn btn-outline-info btn-block" href="{{ route('bioestadistica.reportes.index') }}">Reportes</a></div>@endcan
            @can('bio.dashboard.view')<div class="col-md-3 mt-2"><a class="btn btn-outline-info btn-block" href="{{ route('bioestadistica.dashboards.index') }}">Dashboards</a></div>@endcan
        </div>
        <p class="small text-muted mt-3 mb-0">
            Catálogo: {{ $stats['catalogos'] }} · Variables: {{ $stats['variables'] }} ·
            Formularios: {{ $stats['formularios_activos'] }}/{{ $stats['formularios'] }} ·
            Indicadores: {{ $stats['indicadores_activos'] }}/{{ $stats['indicadores'] }} ·
            Establecimientos: {{ $stats['establecimientos'] }}
            @if($stats['distrito_pendiente']) ({{ $stats['distrito_pendiente'] }} sin distrito) @endif
        </p>
    </div>
</div>
@endsection

@section('scripts')
    @if($dashboard)
        @include('admin.bioestadistica.dashboards._scripts')
    @endif
@endsection
