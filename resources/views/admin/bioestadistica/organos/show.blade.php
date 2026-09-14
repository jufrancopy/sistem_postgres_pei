@extends('layouts.master')
@section('title', 'Bioestadística — '.$organo->nombre)

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', [
    'items' => [
        ['label' => 'Organigrama', 'url' => route('bioestadistica.organos.index')],
        ['label' => \Illuminate\Support\Str::limit($organo->nombre, 40)],
    ],
    'showConfigTabs' => true,
])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">account_tree</i> {{ $organo->nombre }}</h4>
        <p class="card-category">
            <span class="badge badge-light">{{ $organo->tipo?->nombre }}</span>
            @if(! $organo->es_jerarquico) · sin nivel jerárquico @endif
        </p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <nav class="mb-3">
            <ol class="breadcrumb mb-0 py-2">
                <li class="breadcrumb-item"><a href="{{ route('bioestadistica.organos.index') }}">Raíz</a></li>
                @foreach($ancestros as $ancestro)
                    <li class="breadcrumb-item">
                        <a href="{{ route('bioestadistica.organos.index', ['parent_id' => $ancestro->id]) }}">{{ $ancestro->nombre }}</a>
                    </li>
                @endforeach
                <li class="breadcrumb-item active">{{ $organo->nombre }}</li>
            </ol>
        </nav>

        <div class="mb-3">
            <a href="{{ route('bioestadistica.organos.index', ['parent_id' => $organo->id]) }}" class="btn btn-primary btn-sm">Ver hijos en árbol</a>
            @if($organo->parent_id)
                <a href="{{ route('bioestadistica.organos.show', $organo->parent_id) }}" class="btn btn-secondary btn-sm">Ir al padre</a>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <h5>Datos</h5>
                @can('bio.geo.update')
                <form method="POST" action="{{ route('bioestadistica.organos.update', $organo) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Tipo</label>
                        <select class="form-control" name="tipo_id" required>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}" @selected(old('tipo_id', $organo->tipo_id) == $tipo->id)>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input class="form-control" name="nombre" value="{{ old('nombre', $organo->nombre) }}" required maxlength="300">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Código</label>
                            <input class="form-control" name="codigo" value="{{ old('codigo', $organo->codigo) }}" maxlength="40">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Orden</label>
                            <input class="form-control" type="number" name="orden" value="{{ old('orden', $organo->orden) }}" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notas</label>
                        <textarea class="form-control" name="notas" rows="2">{{ old('notas', $organo->notas) }}</textarea>
                    </div>
                    <input type="hidden" name="parent_id" value="{{ $organo->parent_id }}">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="es_jerarquico" value="1" @checked(old('es_jerarquico', $organo->es_jerarquico))>
                            Es jerárquico
                            <span class="form-check-sign"><span class="check"></span></span>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="activo" value="1" @checked(old('activo', $organo->activo))>
                            Activo
                            <span class="form-check-sign"><span class="check"></span></span>
                        </label>
                    </div>
                    <button class="btn btn-success btn-sm">Guardar</button>
                </form>
                @else
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Tipo</dt><dd class="col-sm-8">{{ $organo->tipo?->nombre }}</dd>
                        <dt class="col-sm-4">Código</dt><dd class="col-sm-8">{{ $organo->codigo ?: '—' }}</dd>
                        <dt class="col-sm-4">Orden</dt><dd class="col-sm-8">{{ $organo->orden }}</dd>
                        <dt class="col-sm-4">Notas</dt><dd class="col-sm-8">{{ $organo->notas ?: '—' }}</dd>
                    </dl>
                @endcan

                @can('bio.geo.delete')
                <form method="POST" action="{{ route('bioestadistica.organos.destroy', $organo) }}" class="mt-3 bio-confirm-form" data-confirm="¿Eliminar este órgano?">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit">Eliminar órgano</button>
                </form>
                @endcan
            </div>

            <div class="col-lg-6 mb-4">
                <h5>Hijos ({{ $organo->children->count() }})</h5>
                <ul class="list-group mb-4">
                    @forelse($organo->children as $hijo)
                        <li class="list-group-item py-2 d-flex justify-content-between align-items-center">
                            <span><span class="badge badge-light">{{ $hijo->tipo?->nombre }}</span> {{ $hijo->nombre }}</span>
                            <a href="{{ route('bioestadistica.organos.show', $hijo) }}" class="btn btn-link btn-sm">Abrir</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Sin hijos.</li>
                    @endforelse
                </ul>

                <h5>Establecimientos enlazados</h5>
                <p class="small text-muted">El maestro de establecimientos queda fuera del árbol; aquí se enlaza a este órgano (idealmente un servicio).</p>
                <ul class="list-group mb-3">
                    @forelse($organo->establecimientoLinks as $link)
                        <li class="list-group-item py-2 d-flex justify-content-between align-items-center">
                            <span>
                                {{ $link->establecimiento?->codigo }} — {{ $link->establecimiento?->nombre }}
                                @if($link->es_principal) <span class="badge badge-success">principal</span> @endif
                            </span>
                            @can('bio.geo.delete')
                            <form method="POST" action="{{ route('bioestadistica.organos.unlink', [$organo, $link]) }}" class="bio-confirm-form" data-confirm="¿Quitar enlace?">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit">Quitar</button>
                            </form>
                            @endcan
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Sin establecimientos enlazados.</li>
                    @endforelse
                </ul>

                @can('bio.geo.create')
                <form method="POST" action="{{ route('bioestadistica.organos.link', $organo) }}">
                    @csrf
                    <div class="form-group">
                        <label>Enlazar establecimiento</label>
                        <select class="form-control bio-select2" name="establecimiento_id" data-placeholder="Establecimiento" required>
                            <option value="">Seleccione…</option>
                            @foreach($establecimientos as $est)
                                <option value="{{ $est->id }}">{{ $est->nombre }} ({{ $est->codigo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check mb-2">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="es_principal" value="1" checked>
                            Enlace principal
                            <span class="form-check-sign"><span class="check"></span></span>
                        </label>
                    </div>
                    <button class="btn btn-success btn-sm">Enlazar</button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
