@extends('layouts.master')
@section('title', $dashboard->exists ? 'Editar dashboard' : 'Nueva plantilla')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $dashboard->exists ? 'Editar dashboard' : 'Nueva plantilla institucional' }}</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $dashboard->exists ? route('bioestadistica.dashboards.update', $dashboard) : route('bioestadistica.dashboards.store') }}">
            @csrf
            @if($dashboard->exists) @method('PUT') @endif
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Código</label>
                    <input class="form-control" name="codigo" value="{{ old('codigo', $dashboard->codigo) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Nombre</label>
                    <input class="form-control" name="nombre" value="{{ old('nombre', $dashboard->nombre) }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label class="d-block">Predeterminado</label>
                    <label class="mt-2"><input type="checkbox" name="es_default" value="1" @checked(old('es_default', $dashboard->es_default))> Usar en el inicio de Bioestadística</label>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" name="descripcion">{{ old('descripcion', $dashboard->descripcion) }}</textarea>
            </div>
            <button class="btn btn-success">Guardar</button>
            <a class="btn btn-link" href="{{ route('bioestadistica.dashboards.index') }}">Cancelar</a>
        </form>
    </div>
</div>
@endsection
