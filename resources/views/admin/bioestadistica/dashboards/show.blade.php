@extends('layouts.master')
@section('title', $dashboard->nombre)

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', [
    'items' => [
        ['label' => 'Dashboards', 'url' => route('bioestadistica.dashboards.index')],
        ['label' => $dashboard->nombre],
    ],
    'showConfigTabs' => true,
])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $dashboard->nombre }}</h4>
        <p class="card-category">
            {{ $dashboard->isInstitutional() ? 'Plantilla institucional' : 'Copia personal' }}
            · Los widgets muestran el período estadístico, no la fecha de consulta.
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        <div class="d-flex bio-toolbar mb-3">
            <a href="{{ route('bioestadistica.dashboards.index') }}" class="btn btn-outline-secondary btn-sm">Volver al listado</a>
            @can('update', $dashboard)
                <a href="{{ route('bioestadistica.dashboards.edit', $dashboard) }}" class="btn btn-outline-primary btn-sm">Editar datos</a>
            @endcan
            @if($dashboard->isInstitutional())
                @can('bio.dashboard.personalize')
                    <form method="POST" action="{{ route('bioestadistica.dashboards.clone', $dashboard) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Copiar a mi tablero</button>
                    </form>
                @endcan
            @endif
        </div>

        <form method="GET" class="bio-filters">
            <div class="form-row align-items-end">
                <div class="form-group col-md-2 mb-2">
                    <label class="small text-muted mb-1">Desde año</label>
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_desde_anio',
                        'value' => $period['desde']['anio'],
                        'required' => true,
                    ])
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="small text-muted mb-1">Desde mes</label>
                    <select class="form-control bio-select2" name="periodo_desde_mes">
                        @foreach($months as $number => $label)
                            <option value="{{ $number }}" @selected((int) $period['desde']['mes'] === $number)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="small text-muted mb-1">Hasta año</label>
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_hasta_anio',
                        'value' => $period['hasta']['anio'],
                        'required' => true,
                    ])
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="small text-muted mb-1">Hasta mes</label>
                    <select class="form-control" name="periodo_hasta_mes">
                        @foreach($months as $number => $label)
                            <option value="{{ $number }}" @selected((int) $period['hasta']['mes'] === $number)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <button class="btn btn-info btn-sm btn-block">Aplicar período</button>
                </div>
            </div>
        </form>

        @include('admin.bioestadistica.dashboards._grid')

        @if($editable)
            <hr>
            @include('admin.bioestadistica.dashboards._widget-form', ['widget' => $editingWidget])

            @if($dashboard->widgets->isNotEmpty())
                <h5 class="mt-4">Widgets existentes</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Fuente / indicador</th>
                                <th style="width:180px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dashboard->widgets as $widget)
                            @php $cfg = $widget->query_config ?? []; @endphp
                            <tr class="{{ optional($editingWidget)->id === $widget->id ? 'table-info' : '' }}">
                                <td>{{ $widget->titulo }}</td>
                                <td>{{ $widget->tipo }}</td>
                                <td class="small text-muted">
                                    @if(!empty($cfg['indicator']))
                                        Indicador {{ $cfg['indicator'] }}
                                    @elseif(!empty($cfg['form']))
                                        {{ $cfg['form'] }}:{{ $cfg['field'] ?? '' }}:{{ $cfg['metric'] ?? '' }}
                                    @elseif(!empty($cfg['reporte_id']))
                                        Reporte #{{ $cfg['reporte_id'] }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="bio-actions">
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.dashboards.show', [$dashboard, 'edit_widget' => $widget->id]) }}#bio-widget-form">Editar</a>
                                        <form method="POST" action="{{ route('bioestadistica.dashboards.widgets.destroy', [$dashboard, $widget]) }}" class="d-inline bio-confirm-form" data-confirm="¿Eliminar este widget?">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @can('delete', $dashboard)
                <form method="POST" action="{{ route('bioestadistica.dashboards.destroy', $dashboard) }}" class="mt-3 bio-confirm-form" data-confirm="{{ $dashboard->isInstitutional() ? '¿Archivar esta plantilla institucional?' : '¿Quitar este tablero personal?' }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        {{ $dashboard->isInstitutional() ? 'Archivar plantilla' : 'Quitar de mis tableros' }}
                    </button>
                </form>
            @endcan
        @endif
    </div>
</div>
@endsection

@section('scripts')
    @include('admin.bioestadistica._siplan-scripts')
    @include('admin.bioestadistica.dashboards._scripts')
@endsection
