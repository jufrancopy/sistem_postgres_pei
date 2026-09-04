@extends('layouts.master')
@section('title', 'Bioestadística — Establecimientos')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Establecimientos']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">place</i> Establecimientos</h4>
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
                        <select class="form-control bio-select2" name="departamento_id" data-placeholder="Departamento/región" required>
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
        <div class="d-flex justify-content-between align-items-center bio-toolbar mb-2">
            <h4 class="mb-0">Listado de establecimientos</h4>
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

        <form method="GET" class="bio-filters" id="bio-geo-filters">
            <div class="form-row align-items-end">
                <div class="col-md-5 mb-2">
                    <label class="small text-muted mb-1">Buscar</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Nombre o código">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small text-muted mb-1">Departamento/región</label>
                    <select class="form-control bio-select2" name="departamento_id" data-placeholder="Todos" data-allow-clear="1">
                        <option value="">Todos los departamentos/región</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" @selected(request('departamento_id') == $departamento->id)>{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button class="btn btn-primary btn-sm btn-block" type="submit">Aplicar filtro</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table
                class="table table-bordered table-hover table-sm text-nowrap bio-data-table-ajax"
                data-page-length="25"
                data-order-false="3,5,6,7,8,12,15"
                data-url="{{ route('bioestadistica.geografia.datatable') }}"
                data-filter-form="#bio-geo-filters"
                data-columns='[{"data":"codigo"},{"data":"nombre"},{"data":"codigo_sih"},{"data":"tipo","orderable":false},{"data":"nivel"},{"data":"complejidad","orderable":false},{"data":"departamento","orderable":false},{"data":"distrito","html":true,"orderable":false},{"data":"microred","orderable":false},{"data":"prestador"},{"data":"latitud"},{"data":"longitud"},{"data":"area","orderable":false},{"data":"situacion"},{"data":"observacion"},{"data":"acciones","html":true,"orderable":false,"searchable":false}]'
            >
                <thead class="thead-light">
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
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
