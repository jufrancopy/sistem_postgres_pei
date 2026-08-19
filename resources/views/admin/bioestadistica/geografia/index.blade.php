@extends('layouts.master')
@section('title', 'Bioestadística — Geografía')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">place</i> Maestro geográfico propio</h4>
        <p class="card-category">Departamento/región → Distrito → Establecimiento (aislado de RIISS)</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Nuevo departamento/región</h5>
                <form method="POST" action="{{ route('bioestadistica.geografia.departamentos.store') }}" class="form-row">
                    @csrf
                    <div class="col-3"><input class="form-control" name="codigo" placeholder="Código" required></div>
                    <div class="col-6"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
                    <div class="col-3"><button class="btn btn-info btn-sm">Agregar</button></div>
                </form>
            </div>
            <div class="col-md-6">
                <h5>Nuevo distrito</h5>
                <form method="POST" action="{{ route('bioestadistica.geografia.distritos.store') }}" class="form-row">
                    @csrf
                    <div class="col-5">
                        <select class="form-control" name="departamento_id" required>
                            <option value="">Departamento/región</option>
                            @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5"><input class="form-control" name="nombre" placeholder="Distrito" required></div>
                    <div class="col-2"><button class="btn btn-info btn-sm">Agregar</button></div>
                </form>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <h4>Establecimientos</h4>
            @can('bio.geo.create')
                <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#nuevoEstablecimiento">
                    <i class="material-icons">add</i> Nuevo
                </button>
            @endcan
        </div>

        @can('bio.geo.create')
        <div class="collapse mb-4" id="nuevoEstablecimiento">
            <div class="card card-body bg-light">
                <form method="POST" action="{{ route('bioestadistica.geografia.establecimientos.store') }}">
                    @csrf
                    @include('admin.bioestadistica.geografia._establecimiento-form', [
                        'establecimiento' => null,
                        'formId' => 'nuevo-establecimiento',
                        'submitLabel' => 'Guardar establecimiento',
                    ])
                </form>
            </div>
        </div>
        @endcan

        <form method="GET" class="form-row mb-3">
            <div class="col-md-5"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o código"></div>
            <div class="col-md-4">
                <select class="form-control" name="departamento_id">
                    <option value="">Todos los departamentos/región</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}" @selected(request('departamento_id') == $departamento->id)>{{ $departamento->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><button class="btn btn-primary btn-sm">Filtrar</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID establecimiento</th>
                        <th>Establecimiento</th>
                        <th>Código SIH</th>
                        <th>Tipo</th>
                        <th>Nivel</th>
                        <th>Grado de complejidad</th>
                        <th>Departamento/región</th>
                        <th>Distrito</th>
                        <th>Microred</th>
                        <th>Prestador</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>Área gestión</th>
                        <th>Situación</th>
                        <th>Observación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($establecimientos as $establecimiento)
                    <tr>
                        <td>{{ $establecimiento->codigo }}</td>
                        <td>{{ $establecimiento->nombre }}</td>
                        <td>{{ $establecimiento->codigo_sih }}</td>
                        <td>{{ $establecimiento->tipoEstablecimiento?->nombre }}</td>
                        <td>{{ $establecimiento->nivel_atencion }}</td>
                        <td>
                            @if($establecimiento->gradoComplejidad)
                                Complejidad {{ $establecimiento->gradoComplejidad->codigo }} — {{ $establecimiento->gradoComplejidad->descripcion }}
                            @endif
                        </td>
                        <td>{{ $establecimiento->distrito?->departamento?->nombre }}</td>
                        <td>
                            @if($establecimiento->distrito)
                                {{ $establecimiento->distrito->nombre }}
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                        </td>
                        <td>{{ $establecimiento->microred?->nombre }}</td>
                        <td>{{ $establecimiento->prestador }}</td>
                        <td>{{ $establecimiento->latitud }}</td>
                        <td>{{ $establecimiento->longitud }}</td>
                        <td>{{ $establecimiento->areaGestion?->nombre }}</td>
                        <td>{{ $establecimiento->situacion_inmueble }}</td>
                        <td class="text-wrap" style="min-width:180px">{{ $establecimiento->observacion }}</td>
                        <td>
                            @can('bio.geo.update')
                                <a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.geografia.establecimientos.edit', $establecimiento) }}" title="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                            @endcan
                            @can('bio.geo.delete')
                                <form method="POST" action="{{ route('bioestadistica.geografia.establecimientos.destroy', $establecimiento) }}" class="d-inline" onsubmit="return confirm('¿Eliminar este establecimiento?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Eliminar"><i class="material-icons">delete</i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="18" class="text-center text-muted">Sin establecimientos.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $establecimientos->links() }}
    </div>
</div>
@endsection
