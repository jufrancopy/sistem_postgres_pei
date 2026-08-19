@extends('layouts.master')
@section('title', 'Bioestadística — '.$variable->etiqueta())

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $variable->etiqueta() }}</h4>
        <p class="card-category">
            <a href="{{ route('bioestadistica.diccionario.index') }}" class="text-white">Diccionario</a>
            · Detalles y prestaciones
        </p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        @can('bio.catalog.update')
        <form method="POST" action="{{ route('bioestadistica.diccionario.detalles.store', $variable) }}" class="form-row mb-4">
            @csrf
            <div class="col-md-9"><input class="form-control" name="nombre" placeholder="Nuevo detalle (tipo de prestación)" required></div>
            <div class="col-md-3"><button class="btn btn-info btn-sm">Agregar detalle</button></div>
        </form>
        @endcan

        @forelse($variable->detalles as $detalle)
            <div class="mb-4">
                <h5>{{ $detalle->nombre }}</h5>
                @can('bio.catalog.update')
                <form method="POST" action="{{ route('bioestadistica.diccionario.prestaciones.store', $detalle) }}" class="form-row mb-2">
                    @csrf
                    <div class="col-md-9"><input class="form-control form-control-sm" name="nombre" placeholder="Nueva prestación" required></div>
                    <div class="col-md-3"><button class="btn btn-success btn-sm">Agregar prestación</button></div>
                </form>
                @endcan
                <ul class="list-group">
                    @forelse($detalle->prestaciones as $prestacion)
                        <li class="list-group-item py-2">{{ $prestacion->nombre }}</li>
                    @empty
                        <li class="list-group-item text-muted">Sin prestaciones.</li>
                    @endforelse
                </ul>
            </div>
        @empty
            <p class="text-muted">Esta variable aún no tiene detalles.</p>
        @endforelse
    </div>
</div>
@endsection
