@extends('layouts.master')
@section('title', 'Nueva carga — Bioestadística')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">add_chart</i> Iniciar carga estadística</h4>
        <p class="card-category">Seleccione el mes y año al que pertenecen los datos, no la fecha de digitación.</p>
    </div>
    <div class="card-body">
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if($establecimientos->isEmpty())
            <div class="alert alert-warning">No tiene establecimientos asignados. Solicite al Analista de Bioestadística que realice la asignación.</div>
        @else
            <form method="POST" action="{{ route('bioestadistica.captura.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Formulario *</label>
                        <select class="form-control" name="formulario_id" required><option value="">Seleccione</option>@foreach($formularios as $formulario)<option value="{{ $formulario->id }}" @selected(old('formulario_id') == $formulario->id)>{{ $formulario->codigo }} — {{ $formulario->nombre }}</option>@endforeach</select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Establecimiento *</label>
                        <select class="form-control" name="establecimiento_id" required><option value="">Seleccione</option>@foreach($establecimientos as $establecimiento)<option value="{{ $establecimiento->id }}" @selected(old('establecimiento_id') == $establecimiento->id)>{{ $establecimiento->distrito?->departamento?->nombre }} / {{ $establecimiento->distrito?->nombre }} — {{ $establecimiento->nombre }}</option>@endforeach</select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3"><label>Año del período *</label><input class="form-control" name="periodo_anio" type="number" min="1990" max="2100" value="{{ old('periodo_anio', now()->year) }}" required></div>
                    <div class="form-group col-md-4"><label>Mes del período *</label><select class="form-control" name="periodo_mes" required><option value="">Seleccione</option>@foreach($months as $number => $month)<option value="{{ $number }}" @selected(old('periodo_mes', now()->subMonth()->month) == $number)>{{ $month }}</option>@endforeach</select></div>
                </div>
                <button class="btn btn-success">Crear borrador</button>
                <a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        @endif
    </div>
</div>
@endsection
