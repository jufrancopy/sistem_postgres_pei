@extends('layouts.master')
@section('title', 'Períodos pendientes — Bioestadística')

@section('content')
<div class="card">
    <div class="card-header card-header-warning">
        <h4 class="card-title"><i class="material-icons">pending_actions</i> Períodos pendientes</h4>
        <p class="card-category">Formularios activos sin registro para el período estadístico seleccionado.</p>
    </div>
    <div class="card-body">
        <form class="form-row mb-3">
            <div class="col-md-2"><input class="form-control" name="periodo_anio" type="number" min="1990" max="2100" value="{{ $periodo_anio }}"></div>
            <div class="col-md-3">
                <select class="form-control" name="periodo_mes">
                    @foreach($months as $number => $month)
                        <option value="{{ $number }}" @selected($periodo_mes == $number)>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary">Consultar</button></div>
            <div class="col-md-3"><a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-secondary">Volver al listado</a></div>
        </form>
        @forelse($rows as $row)
            <div class="card border mb-3">
                <div class="card-header bg-light">
                    <strong>{{ $row['establecimiento']->nombre }}</strong>
                    <small class="text-muted">{{ $row['establecimiento']->distrito?->departamento?->nombre }} /
                        {{ $row['establecimiento']->distrito?->nombre }}
                        @if($row['unidad']) · {{ $row['unidad']->etiqueta() }} @endif
                    </small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                            @foreach($row['missing'] as $formulario)
                                <tr>
                                    <td>{{ $formulario->codigo }} — {{ $formulario->nombre }}</td>
                                    <td class="text-right">
                                        @if($formulario->codigo === 'SP10' || $formulario->layout_type === 'nominativo')
                                            @can('bio.record.create')
                                            <form method="POST" action="{{ route('bioestadistica.captura.store') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="formulario_id" value="{{ $formulario->id }}">
                                                <input type="hidden" name="establecimiento_id" value="{{ $row['establecimiento']->id }}">
                                                <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
                                                <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">
                                                @if($row['unidad'])
                                                    <input type="hidden" name="estructura_servicio_id" value="{{ $row['unidad']->servicio_id }}">
                                                @endif
                                                <button class="btn btn-info btn-sm">Cargar planilla</button>
                                            </form>
                                            @endcan
                                        @else
                                            @can('bio.record.create')
                                            <form method="POST" action="{{ route('bioestadistica.captura.store') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="formulario_id" value="{{ $formulario->id }}">
                                                <input type="hidden" name="establecimiento_id" value="{{ $row['establecimiento']->id }}">
                                                <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
                                                <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">
                                                @if($row['unidad'])
                                                    <input type="hidden" name="estructura_servicio_id" value="{{ $row['unidad']->servicio_id }}">
                                                @endif
                                                <button class="btn btn-info btn-sm">Iniciar carga</button>
                                            </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-success">No hay formularios pendientes para {{ $months[$periodo_mes] }}/{{ $periodo_anio }}.</div>
        @endforelse
    </div>
</div>
@endsection
