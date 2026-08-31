@extends('layouts.master')
@section('title', 'Bioestadística — Formularios')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Formularios']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">dynamic_form</i> Constructor de formularios</h4>
        <p class="card-category">SP1–SPx como configuración, sin tablas específicas</p>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        <div class="d-flex bio-toolbar mb-3">
            <button class="btn btn-info btn-sm" data-toggle="collapse" data-target="#nuevoFormulario">
                <i class="material-icons">add</i> Nuevo formulario
            </button>
        </div>
        <div class="collapse mb-4" id="nuevoFormulario">
            <form method="POST" action="{{ route('bioestadistica.formularios.store') }}" class="card card-body bg-light">
                @csrf
                <div class="form-row">
                    <div class="col-md-2"><input class="form-control" name="codigo" placeholder="SP15" required></div>
                    <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
                    <div class="col-md-3">
                        <select class="form-control bio-select2" name="periodicidad" data-placeholder="Periodicidad" required>
                            @foreach(['diaria','semanal','mensual','trimestral','anual','ad_hoc'] as $value)
                                <option value="{{ $value }}" @selected($value === 'mensual')>{{ ucfirst(str_replace('_', ' ', $value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control bio-select2" name="layout_type" data-placeholder="Diseño" required>
                            <option value="tabular">Tabular</option>
                            <option value="nominativo">Nominativo</option>
                            <option value="matriz">Matriz</option>
                        </select>
                    </div>
                </div>
                <textarea class="form-control mt-2" name="descripcion" placeholder="Descripción"></textarea>
                <div><button class="btn btn-success btn-sm mt-2">Crear y diseñar</button></div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="25" data-order-false="6">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Diseño</th>
                        <th>Periodicidad</th>
                        <th>Estado</th>
                        <th>Versión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($formularios as $formulario)
                    <tr>
                        <td><strong>{{ $formulario->codigo }}</strong></td>
                        <td>{{ $formulario->nombre }} <small class="text-muted">({{ $formulario->secciones_count }} secciones)</small></td>
                        <td>{{ ucfirst($formulario->layout_type) }}</td>
                        <td>{{ ucfirst($formulario->periodicidad) }}</td>
                        <td><span class="badge badge-{{ $formulario->estado === 'activo' ? 'success' : 'secondary' }}">{{ $formulario->estado }}</span></td>
                        <td>{{ $formulario->version }}</td>
                        <td>
                            <div class="bio-actions">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.formularios.edit', $formulario) }}">Diseñar</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
