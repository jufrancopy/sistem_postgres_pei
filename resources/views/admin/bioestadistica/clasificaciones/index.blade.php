@extends('layouts.master')
@section('title', 'Bioestadística — Clasificaciones')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">category</i> Clasificación de establecimientos</h4>
        <p class="card-category">Microredes, tipos, grados de complejidad y áreas de gestión</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="row">
            @foreach([
                ['microredes', 'Microredes', $microredes],
                ['tipos-establecimiento', 'Tipos de establecimiento', $tipos],
                ['areas-gestion', 'Áreas de gestión', $areas],
            ] as [$slug, $title, $items])
                <div class="col-md-4">
                    <h5>{{ $title }}</h5>
                    @can('bio.geo.create')
                    <form method="POST" action="{{ route('bioestadistica.clasificaciones.store', $slug) }}" class="input-group mb-3">
                        @csrf
                        <input class="form-control" name="nombre" placeholder="Nombre" required>
                        <div class="input-group-append"><button class="btn btn-info btn-sm" type="submit">+</button></div>
                    </form>
                    @endcan
                    <ul class="list-group mb-4">
                        @forelse($items as $item)
                            <li class="list-group-item py-2">
                                @can('bio.geo.update')
                                    <form method="POST" action="{{ route('bioestadistica.clasificaciones.update', [$slug, $item->id]) }}" class="form-row align-items-center">
                                        @csrf
                                        @method('PUT')
                                        <div class="col pr-1">
                                            <input class="form-control form-control-sm" name="nombre" value="{{ old('nombre', $item->nombre) }}" required>
                                        </div>
                                        <div class="col-auto px-1">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="activo" value="0">
                                                <input type="checkbox" class="custom-control-input" id="activo-{{ $slug }}-{{ $item->id }}" name="activo" value="1" @checked(old('activo', $item->activo))>
                                                <label class="custom-control-label" for="activo-{{ $slug }}-{{ $item->id }}">Activo</label>
                                            </div>
                                        </div>
                                        <div class="col-auto pl-1">
                                            <button class="btn btn-success btn-sm" type="submit" title="Guardar">
                                                <i class="material-icons" style="font-size:16px">save</i>
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <span class="{{ $item->activo ? '' : 'text-muted' }}">
                                        {{ $item->nombre }}
                                        @unless($item->activo)<small>(inactivo)</small>@endunless
                                    </span>
                                @endcan
                                @can('bio.geo.delete')
                                    <form method="POST" action="{{ route('bioestadistica.clasificaciones.destroy', [$slug, $item->id]) }}" class="mt-1 text-right" onsubmit="return confirm('¿Eliminar esta clasificación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link btn-sm text-danger p-0" type="submit" title="Eliminar">
                                            <i class="material-icons" style="font-size:16px">delete</i>
                                        </button>
                                    </form>
                                @endcan
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Sin registros.</li>
                        @endforelse
                    </ul>
                </div>
            @endforeach
        </div>

        <h5>Grados de complejidad</h5>
        @can('bio.geo.create')
        <form method="POST" action="{{ route('bioestadistica.clasificaciones.store', 'grados-complejidad') }}" class="form-row mb-3">
            @csrf
            <div class="col-md-2"><input class="form-control" name="codigo" placeholder="Grado" required></div>
            <div class="col-md-8"><input class="form-control" name="descripcion" placeholder="Descripción" required></div>
            <div class="col-md-2"><button class="btn btn-info btn-sm" type="submit">Agregar</button></div>
        </form>
        @endcan
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width:12%">Código</th>
                        <th>Descripción</th>
                        <th style="width:10%">Activo</th>
                        <th style="width:120px"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($grados as $grado)
                    @can('bio.geo.update')
                        <tr>
                            <td colspan="4" class="p-2">
                                <form method="POST" action="{{ route('bioestadistica.clasificaciones.update', ['grados-complejidad', $grado->id]) }}" class="form-row align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-md-2">
                                        <input class="form-control form-control-sm" name="codigo" value="{{ old('codigo', $grado->codigo) }}" required>
                                    </div>
                                    <div class="col-md-7">
                                        <input class="form-control form-control-sm" name="descripcion" value="{{ old('descripcion', $grado->descripcion) }}" required>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="hidden" name="activo" value="0">
                                        <input type="checkbox" name="activo" value="1" @checked(old('activo', $grado->activo)) title="Activo">
                                    </div>
                                    <div class="col-md-2 text-nowrap">
                                        <button class="btn btn-success btn-sm" type="submit" title="Guardar">
                                            <i class="material-icons" style="font-size:16px">save</i>
                                        </button>
                                        @can('bio.geo.delete')
                                            <button class="btn btn-link btn-sm text-danger p-0 ml-1" type="button" title="Eliminar"
                                                onclick="if(confirm('¿Eliminar este grado?')){ document.getElementById('delete-grado-{{ $grado->id }}').submit(); }">
                                                <i class="material-icons" style="font-size:16px">delete</i>
                                            </button>
                                        @endcan
                                    </div>
                                </form>
                                @can('bio.geo.delete')
                                    <form id="delete-grado-{{ $grado->id }}" method="POST" action="{{ route('bioestadistica.clasificaciones.destroy', ['grados-complejidad', $grado->id]) }}" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $grado->codigo }}</td>
                            <td class="{{ $grado->activo ? '' : 'text-muted' }}">
                                {{ $grado->descripcion }}
                                @unless($grado->activo)<small>(inactivo)</small>@endunless
                            </td>
                            <td>{{ $grado->activo ? 'Sí' : 'No' }}</td>
                            <td>
                                @can('bio.geo.delete')
                                    <form method="POST" action="{{ route('bioestadistica.clasificaciones.destroy', ['grados-complejidad', $grado->id]) }}" onsubmit="return confirm('¿Eliminar este grado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link btn-sm text-danger p-0" type="submit" title="Eliminar">
                                            <i class="material-icons" style="font-size:16px">delete</i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endcan
                @empty
                    <tr><td colspan="4" class="text-muted">Sin grados de complejidad.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
