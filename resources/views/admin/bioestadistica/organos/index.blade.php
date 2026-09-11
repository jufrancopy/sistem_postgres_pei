@extends('layouts.master')
@section('title', 'Bioestadística — Organigrama')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Organigrama']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">account_tree</i> Organigrama institucional</h4>
        <p class="card-category">
            Fase 1 desde Gerencia de Salud.
            Tipos: {{ $stats['tipos'] }} · Órganos: {{ number_format($stats['organos'], 0, ',', '.') }} · Enlaces a establecimientos: {{ $stats['enlaces'] }}
        </p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <form method="GET" action="{{ route('bioestadistica.organos.index') }}" class="bio-filters mb-3">
            <div class="form-row align-items-end">
                <div class="col-md-8 mb-2">
                    <label class="small text-muted mb-1">Buscar órgano</label>
                    <input class="form-control" name="q" value="{{ $q }}" placeholder="Nombre (ej. Nanawa, Cardiología, Luque)">
                </div>
                <div class="col-md-2 mb-2"><button class="btn btn-primary btn-sm btn-block">Buscar</button></div>
                <div class="col-md-2 mb-2"><a class="btn btn-secondary btn-sm btn-block" href="{{ route('bioestadistica.organos.index') }}">Árbol</a></div>
            </div>
        </form>

        @if($modo === 'busqueda')
            <h5 class="mb-3">Resultados para «{{ $q }}»</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Padre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($resultados as $item)
                        <tr>
                            <td><span class="badge badge-light">{{ $item->tipo?->nombre }}</span></td>
                            <td>{{ $item->nombre }}</td>
                            <td class="text-muted small">{{ $item->parent?->nombre ?? '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('bioestadistica.organos.show', $item) }}" class="btn btn-outline-info btn-sm">Ver</a>
                                <a href="{{ route('bioestadistica.organos.index', ['parent_id' => $item->id]) }}" class="btn btn-outline-primary btn-sm">Entrar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">Sin coincidencias.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <nav aria-label="ruta organigrama" class="mb-3">
                <ol class="breadcrumb mb-0 py-2">
                    <li class="breadcrumb-item"><a href="{{ route('bioestadistica.organos.index') }}">Raíz</a></li>
                    @foreach($ancestros as $ancestro)
                        <li class="breadcrumb-item">
                            <a href="{{ route('bioestadistica.organos.index', ['parent_id' => $ancestro->id]) }}">{{ $ancestro->nombre }}</a>
                        </li>
                    @endforeach
                    @if($actual)
                        <li class="breadcrumb-item active" aria-current="page">{{ $actual->nombre }}</li>
                    @endif
                </ol>
            </nav>

            @if($actual)
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">{{ $actual->nombre }}</h5>
                        <div class="small text-muted">
                            <span class="badge badge-info">{{ $actual->tipo?->nombre }}</span>
                            @if(! $actual->es_jerarquico) <span class="badge badge-secondary">sin nivel jerárquico</span> @endif
                            @if(! $actual->activo) <span class="badge badge-warning">inactivo</span> @endif
                        </div>
                    </div>
                    <a href="{{ route('bioestadistica.organos.show', $actual) }}" class="btn btn-info btn-sm">Detalle / enlaces</a>
                </div>
            @endif

            <div class="list-group mb-4">
                @forelse($hijos as $hijo)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-light mr-1">{{ $hijo->tipo?->nombre }}</span>
                            <strong>{{ $hijo->nombre }}</strong>
                            @if($hijo->children->isNotEmpty())
                                <small class="text-muted">({{ $hijo->children->count() }} hijos)</small>
                            @endif
                            @if(! $hijo->activo) <span class="badge badge-warning">inactivo</span> @endif
                        </div>
                        <div>
                            <a href="{{ route('bioestadistica.organos.show', $hijo) }}" class="btn btn-outline-info btn-sm">Ver</a>
                            <a href="{{ route('bioestadistica.organos.index', ['parent_id' => $hijo->id]) }}" class="btn btn-outline-primary btn-sm">Entrar</a>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Sin hijos en este nivel.</div>
                @endforelse
            </div>

            @can('bio.geo.create')
                @if($actual)
                <h6>Agregar hijo bajo «{{ $actual->nombre }}»</h6>
                <form method="POST" action="{{ route('bioestadistica.organos.store') }}" class="bio-filters">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $actual->id }}">
                    <div class="form-row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label class="small text-muted mb-1">Tipo</label>
                            <select class="form-control" name="tipo_id" required>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" @selected(old('tipo_id') == $tipo->id)>{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 mb-2">
                            <label class="small text-muted mb-1">Nombre</label>
                            <input class="form-control" name="nombre" value="{{ old('nombre') }}" required maxlength="300">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small text-muted mb-1">Orden</label>
                            <input class="form-control" type="number" name="orden" value="{{ old('orden', 0) }}" min="0">
                        </div>
                        <div class="col-md-2 mb-2"><button class="btn btn-success btn-sm btn-block">Crear</button></div>
                    </div>
                    <div class="form-check mb-2">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="es_jerarquico" value="1" @checked(old('es_jerarquico', true))>
                            Es jerárquico
                            <span class="form-check-sign"><span class="check"></span></span>
                        </label>
                    </div>
                </form>
                @endif
            @endcan
        @endif
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
