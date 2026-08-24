@extends('layouts.master')
@section('title', 'Bioestadística — '.$variable->etiqueta())

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', [
    'items' => [
        ['label' => 'Variables', 'url' => route('bioestadistica.diccionario.index')],
        ['label' => $variable->etiqueta()],
    ],
])
<div class="card bio-siplan">
    <div class="card-header card-header-info d-flex justify-content-between align-items-start">
        <div>
            <h4 class="card-title">{{ $variable->etiqueta() }}</h4>
            <p class="card-category mb-0">Detalles (tipos) y prestaciones (filas de captura)</p>
        </div>
        @canany(['bio.catalog.update', 'bio.catalog.delete'])
        <div class="text-nowrap ml-2">
            @can('bio.catalog.update')
            <button type="button" class="btn btn-outline-light btn-sm" data-toggle="collapse" data-target="#edit-variable" title="Editar variable">
                <i class="material-icons" style="font-size:16px">edit</i>
            </button>
            @endcan
            @can('bio.catalog.delete')
            <form method="POST" action="{{ route('bioestadistica.diccionario.variables.destroy', $variable) }}" class="d-inline" onsubmit="return confirm('¿Eliminar la variable «{{ $variable->nombre }}», sus detalles y prestaciones?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" title="Eliminar variable"><i class="material-icons" style="font-size:16px">delete</i></button>
            </form>
            @endcan
        </div>
        @endcanany
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        @can('bio.catalog.update')
        <div class="collapse mb-4" id="edit-variable">
            <form method="POST" action="{{ route('bioestadistica.diccionario.variables.update', $variable) }}" class="bg-light border rounded p-3">
                @csrf @method('PUT')
                <div class="form-row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1">Código</label>
                        <input class="form-control" name="codigo" value="{{ $variable->codigo }}" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted mb-1">Nombre</label>
                        <input class="form-control" name="nombre" value="{{ $variable->nombre }}" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="d-block"><input type="checkbox" name="activo" value="1" @checked($variable->activo)> Activo</label>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary btn-sm btn-block">Guardar variable</button>
                    </div>
                </div>
            </form>
        </div>
        @endcan

        @can('bio.catalog.update')
        <form method="POST" action="{{ route('bioestadistica.diccionario.detalles.store', $variable) }}" class="form-row mb-4">
            @csrf
            <div class="col-md-9"><input class="form-control" name="nombre" placeholder="Nuevo detalle (tipo de prestación)" required></div>
            <div class="col-md-3"><button class="btn btn-info btn-sm">Agregar detalle</button></div>
        </form>
        @endcan

        @forelse($variable->detalles as $detalle)
            <div class="card border mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <div>
                        <strong>{{ $detalle->nombre }}</strong>
                        @unless($detalle->activo)<span class="badge badge-secondary ml-1">Inactivo</span>@endunless
                        <small class="text-muted ml-2">{{ $detalle->prestaciones->count() }} prestaciones</small>
                    </div>
                    @canany(['bio.catalog.update', 'bio.catalog.delete'])
                    <div class="text-nowrap">
                        @can('bio.catalog.update')
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="collapse" data-target="#edit-detalle-{{ $detalle->id }}" title="Editar detalle">
                            <i class="material-icons" style="font-size:16px">edit</i>
                        </button>
                        @endcan
                        @can('bio.catalog.delete')
                        <form method="POST" action="{{ route('bioestadistica.diccionario.detalles.destroy', $detalle) }}" class="d-inline" onsubmit="return confirm('¿Eliminar el detalle «{{ $detalle->nombre }}» y sus prestaciones?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Eliminar detalle"><i class="material-icons" style="font-size:16px">delete</i></button>
                        </form>
                        @endcan
                    </div>
                    @endcanany
                </div>

                @can('bio.catalog.update')
                <div class="collapse" id="edit-detalle-{{ $detalle->id }}">
                    <form method="POST" action="{{ route('bioestadistica.diccionario.detalles.update', $detalle) }}" class="bg-light border-bottom p-3">
                        @csrf @method('PUT')
                        <div class="form-row align-items-end">
                            <div class="col-md-7 mb-2">
                                <label class="small text-muted mb-1">Nombre del detalle</label>
                                <input class="form-control" name="nombre" value="{{ $detalle->nombre }}" required>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="small text-muted mb-1">Orden</label>
                                <input class="form-control" type="number" min="0" name="orden" value="{{ $detalle->orden }}">
                            </div>
                            <div class="col-md-1 mb-2">
                                <label class="d-block"><input type="checkbox" name="activo" value="1" @checked($detalle->activo)> Activo</label>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-primary btn-sm btn-block">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
                @endcan

                <div class="card-body py-3">
                    @can('bio.catalog.update')
                    <form method="POST" action="{{ route('bioestadistica.diccionario.prestaciones.store', $detalle) }}" class="form-row mb-2">
                        @csrf
                        <div class="col-md-9"><input class="form-control form-control-sm" name="nombre" placeholder="Nueva prestación" required></div>
                        <div class="col-md-3"><button class="btn btn-success btn-sm">Agregar prestación</button></div>
                    </form>
                    @endcan
                    <ul class="list-group">
                        @forelse($detalle->prestaciones as $prestacion)
                            <li class="list-group-item py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $prestacion->nombre }}
                                        @unless($prestacion->activo)<span class="badge badge-secondary ml-1">Inactivo</span>@endunless
                                    </div>
                                    @canany(['bio.catalog.update', 'bio.catalog.delete'])
                                    <div class="text-nowrap">
                                        @can('bio.catalog.update')
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="collapse" data-target="#edit-prestacion-{{ $prestacion->id }}" title="Editar prestación">
                                            <i class="material-icons" style="font-size:16px">edit</i>
                                        </button>
                                        @endcan
                                        @can('bio.catalog.delete')
                                        <form method="POST" action="{{ route('bioestadistica.diccionario.prestaciones.destroy', $prestacion) }}" class="d-inline" onsubmit="return confirm('¿Eliminar la prestación «{{ $prestacion->nombre }}»?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar prestación"><i class="material-icons" style="font-size:16px">delete</i></button>
                                        </form>
                                        @endcan
                                    </div>
                                    @endcanany
                                </div>
                                @can('bio.catalog.update')
                                <div class="collapse mt-2" id="edit-prestacion-{{ $prestacion->id }}">
                                    <form method="POST" action="{{ route('bioestadistica.diccionario.prestaciones.update', $prestacion) }}" class="bg-light border rounded p-2">
                                        @csrf @method('PUT')
                                        <div class="form-row align-items-end">
                                            <div class="col-md-7 mb-1">
                                                <input class="form-control form-control-sm" name="nombre" value="{{ $prestacion->nombre }}" required>
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <input class="form-control form-control-sm" type="number" min="0" name="orden" value="{{ $prestacion->orden }}" placeholder="Orden">
                                            </div>
                                            <div class="col-md-1 mb-1">
                                                <label class="mb-0 small"><input type="checkbox" name="activo" value="1" @checked($prestacion->activo)> Activo</label>
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <button class="btn btn-primary btn-sm btn-block">Guardar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endcan
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Sin prestaciones.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">Esta variable aún no tiene detalles.</p>
        @endforelse
    </div>
</div>
@endsection
