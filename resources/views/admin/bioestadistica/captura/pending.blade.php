@extends('layouts.master')
@section('title', 'Períodos pendientes — Bioestadística')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', [
    'items' => [
        ['label' => 'Carga de datos', 'url' => route('bioestadistica.captura.index')],
        ['label' => 'Pendientes'],
    ],
])
<style>
    .bio-pending-accordion .card-header {
        cursor: pointer;
        user-select: none;
    }
    .bio-pending-accordion .card-header .bio-pending-chevron {
        transition: transform .2s ease;
        font-size: 22px;
        line-height: 1;
        color: #64748b;
    }
    .bio-pending-accordion .card-header[aria-expanded="true"] .bio-pending-chevron {
        transform: rotate(180deg);
    }
    .bio-pending-accordion .bio-pending-slice {
        border-left: 3px solid #0288d1;
        padding-left: 0.75rem;
        margin-bottom: 1rem;
    }
</style>
<div class="card bio-siplan">
    <div class="card-header card-header-warning">
        <h4 class="card-title"><i class="material-icons">pending_actions</i> Períodos pendientes</h4>
        <p class="card-category">Formularios activos sin registro para el período estadístico seleccionado.</p>
    </div>
    <div class="card-body">
        <form method="GET" class="bio-filters">
            <div class="form-row align-items-end">
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Año</label>
                    <input class="form-control" name="periodo_anio" type="number" min="1990" max="2100" value="{{ $periodo_anio }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Mes</label>
                    <select class="form-control bio-select2" name="periodo_mes" data-placeholder="Mes">
                        @foreach($months as $number => $month)
                            <option value="{{ $number }}" @selected($periodo_mes == $number)>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button class="btn btn-primary btn-sm btn-block" type="submit">Consultar</button>
                </div>
                <div class="col-md-3 mb-2">
                    <a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-outline-secondary btn-sm btn-block">Volver al listado</a>
                </div>
            </div>
        </form>

        @forelse($groups as $index => $group)
            @php
                $collapseId = 'pending-est-'.$group['establecimiento']->id.'-'.$index;
                $est = $group['establecimiento'];
            @endphp
            <div class="card border mb-2 bio-pending-accordion">
                <div
                    class="card-header bg-light d-flex justify-content-between align-items-center"
                    data-toggle="collapse"
                    data-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    aria-controls="{{ $collapseId }}"
                >
                    <div>
                        <strong>{{ $est->nombre }}</strong>
                        <span class="badge badge-warning ml-2">{{ $group['pending_count'] }} SP</span>
                        <div>
                            <small class="text-muted">
                                {{ $est->distrito?->departamento?->nombre }} / {{ $est->distrito?->nombre }}
                            </small>
                        </div>
                    </div>
                    <i class="material-icons bio-pending-chevron">expand_more</i>
                </div>
                <div id="{{ $collapseId }}" class="collapse">
                    <div class="card-body pt-2">
                        @foreach($group['slices'] as $slice)
                            @if($slice['unidad'])
                                <div class="bio-pending-slice">
                                    <div class="mb-2">
                                        <small class="text-muted text-uppercase">Departamento / servicio</small>
                                        <div><strong>{{ $slice['unidad']->etiqueta() }}</strong></div>
                                    </div>
                            @endif
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Formulario</th>
                                                    <th style="width:160px" class="text-right">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($slice['missing'] as $formulario)
                                                <tr>
                                                    <td>{{ $formulario->codigo }} — {{ $formulario->nombre }}</td>
                                                    <td class="text-right">
                                                        @can('bio.record.create')
                                                            <form method="POST" action="{{ route('bioestadistica.captura.store') }}" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="formulario_id" value="{{ $formulario->id }}">
                                                                <input type="hidden" name="establecimiento_id" value="{{ $est->id }}">
                                                                <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
                                                                <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">
                                                                @if($slice['unidad'])
                                                                    <input type="hidden" name="estructura_servicio_id" value="{{ $slice['unidad']->servicio_id }}">
                                                                @endif
                                                                <button class="btn btn-outline-info btn-sm" type="submit">
                                                                    Iniciar carga
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                            @if($slice['unidad'])
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-success mb-0">No hay formularios pendientes para {{ $months[$periodo_mes] }}/{{ $periodo_anio }}.</div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
