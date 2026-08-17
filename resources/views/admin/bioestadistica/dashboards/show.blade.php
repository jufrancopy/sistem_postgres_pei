@extends('layouts.master')
@section('title', $dashboard->nombre)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $dashboard->nombre }}</h4>
        <p class="card-category">
            {{ $dashboard->isInstitutional() ? 'Plantilla institucional' : 'Copia personal' }}
            · Los widgets muestran el período estadístico, no la fecha de consulta.
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
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

        @if($dashboard->isInstitutional())
            @can('bio.dashboard.personalize')
                <form method="POST" action="{{ route('bioestadistica.dashboards.clone', $dashboard) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary mb-3">Copiar a mi tablero</button>
                </form>
            @endcan
        @endif

        @include('admin.bioestadistica.dashboards._grid')

        @if($editable)
            <hr>
            <h5>Agregar widget</h5>
            <form method="POST" action="{{ route('bioestadistica.dashboards.widgets.store', $dashboard) }}" class="card card-body bg-light">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Título</label>
                        <input class="form-control" name="titulo" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Tipo</label>
                        <select class="form-control" name="tipo">
                            @foreach($widgetTypes as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fuente</label>
                        <select class="form-control" name="source">
                            <option value="">—</option>
                            @foreach($sources as $source)
                                <option value="{{ $source['key'] }}">{{ $source['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Indicador</label>
                        <select class="form-control" name="indicator">
                            <option value="">—</option>
                            @foreach($indicators as $indicator)
                                <option value="{{ $indicator->codigo }}">{{ $indicator->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Reporte</label>
                        <select class="form-control" name="reporte_id">
                            <option value="">—</option>
                            @foreach($reportes as $reporte)
                                <option value="{{ $reporte->id }}">{{ $reporte->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>Dimensión</label>
                        <select class="form-control" name="dimension">
                            <option value="">—</option>
                            @foreach($dimensions as $dimension)
                                <option value="{{ $dimension }}">{{ $dimension }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Heatmap X</label>
                        <select class="form-control" name="dimension_x">
                            <option value="">—</option>
                            @foreach($dimensions as $dimension)
                                <option value="{{ $dimension }}">{{ $dimension }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Heatmap Y</label>
                        <select class="form-control" name="dimension_y">
                            <option value="">—</option>
                            @foreach($dimensions as $dimension)
                                <option value="{{ $dimension }}">{{ $dimension }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-1"><label>Ancho</label><input class="form-control" type="number" name="ancho" min="1" max="12" value="4"></div>
                    <div class="form-group col-md-1"><label>Alto</label><input class="form-control" type="number" name="alto" min="1" max="12" value="3"></div>
                    <div class="form-group col-md-2"><label>Agg</label>
                        <select class="form-control" name="agg">
                            <option value="sum">sum</option>
                            <option value="avg">avg</option>
                            <option value="count">count</option>
                            <option value="max">max</option>
                            <option value="min">min</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button class="btn btn-success btn-block">Agregar</button>
                    </div>
                </div>
            </form>

            @if($dashboard->widgets->isNotEmpty())
                <h5 class="mt-4">Widgets existentes</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Título</th><th>Tipo</th><th></th></tr></thead>
                        <tbody>
                        @foreach($dashboard->widgets as $widget)
                            <tr>
                                <td>{{ $widget->titulo }}</td>
                                <td>{{ $widget->tipo }}</td>
                                <td>
                                    <form method="POST" action="{{ route('bioestadistica.dashboards.widgets.destroy', [$dashboard, $widget]) }}" onsubmit="return confirm('¿Eliminar widget?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($dashboard->isInstitutional() || auth()->id() === $dashboard->user_id)
                <form method="POST" action="{{ route('bioestadistica.dashboards.destroy', $dashboard) }}" class="mt-3" onsubmit="return confirm('¿Archivar este dashboard?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">Archivar dashboard</button>
                </form>
            @endif
        @endif
    </div>
</div>
@endsection

@section('scripts')
    @include('admin.bioestadistica.dashboards._scripts')
@endsection
