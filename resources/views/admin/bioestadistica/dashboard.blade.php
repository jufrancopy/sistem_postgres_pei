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
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_desde_anio',
                        'value' => $period['desde']['anio'],
                        'required' => true,
                    ])
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label>Desde mes</label>
                    <select class="form-control bio-select2" name="periodo_desde_mes">
                        @foreach($months as $number => $label)
                            <option value="{{ $number }}" @selected((int) $period['desde']['mes'] === $number)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label>Hasta año</label>
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_hasta_anio',
                        'value' => $period['hasta']['anio'],
                        'required' => true,
                    ])
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
                <div class="form-group col-md-2 mb-2">
                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-block"
                        id="bioDashboardExportJpg"
                        data-filename="bio_dashboard_{{ \Illuminate\Support\Str::slug($dashboard->nombre ?? 'bioestadistica') }}_{{ $period['desde']['anio'] }}{{ str_pad((string) $period['desde']['mes'], 2, '0', STR_PAD_LEFT) }}-{{ $period['hasta']['anio'] }}{{ str_pad((string) $period['hasta']['mes'], 2, '0', STR_PAD_LEFT) }}"
                    >
                        <i class="material-icons align-middle" style="font-size:16px;">image</i> Exportar JPG
                    </button>
                </div>
            </form>

            <div id="bioDashboardExportArea" class="bio-dashboard-export bg-white p-2 rounded">
                <div class="mb-3 px-1">
                    <h5 class="mb-1">{{ $dashboard->nombre }}</h5>
                    <p class="text-muted small mb-0">
                        Período:
                        {{ $months[$period['desde']['mes']] ?? $period['desde']['mes'] }}/{{ $period['desde']['anio'] }}
                        —
                        {{ $months[$period['hasta']['mes']] ?? $period['hasta']['mes'] }}/{{ $period['hasta']['anio'] }}
                        · Generado {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
                @php $editable = false; @endphp
                @include('admin.bioestadistica.dashboards._grid')
            </div>
        @else
            <div class="alert alert-warning">Todavía no hay una plantilla institucional activa. Configure un dashboard predeterminado.</div>
        @endif

        <hr>
        <p class="text-muted mb-2">Administración del módulo</p>
        <div class="row">
            <div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.formularios.index') }}">Formularios</a></div>
            <div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.diccionario.index') }}">Variables</a></div>
            <div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.geografia.index') }}">Establecimientos</a></div>
            <div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.organos.index') }}">Organigrama</a></div>
            @can('bio.indicator.view')<div class="col-md-3"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.indicadores.index') }}">Indicadores</a></div>@endcan
            @can('bio.report.view')<div class="col-md-3 mt-2"><a class="btn btn-outline-info btn-block" href="{{ route('bioestadistica.reportes.index') }}">Reportes</a></div>@endcan
            @can('bio.dashboard.view')<div class="col-md-3 mt-2"><a class="btn btn-outline-info btn-block" href="{{ route('bioestadistica.dashboards.index') }}">Dashboards</a></div>@endcan
        </div>
        <p class="small text-muted mt-3 mb-0">
            Variables: {{ $stats['variables'] }} · Prestaciones: {{ $stats['prestaciones'] }} ·
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
        @include('admin.bioestadistica.dashboards._export-jpg')
    @endif
@endsection
