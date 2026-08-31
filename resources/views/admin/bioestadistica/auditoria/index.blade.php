@extends('layouts.master')
@section('title', 'Bioestadística — Auditoría')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Auditoría']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">history</i> Auditoría</h4>
        <p class="card-category">Consulta append-only. No hay edición ni eliminación de asientos.</p>
    </div>
    <div class="card-body">
        <form method="GET" class="bio-filters" id="bio-audit-filters">
            <div class="form-row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Entidad</label>
                    <select name="entity_type" class="form-control bio-select2" data-placeholder="Todas" data-allow-clear="1">
                        <option value="">Todas</option>
                        @foreach($entityTypes as $type)
                            <option value="{{ $type }}" @selected(($filters['entity_type'] ?? '') === $type)>{{ class_basename($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Acción</label>
                    <select name="accion" class="form-control bio-select2" data-placeholder="Todas" data-allow-clear="1">
                        <option value="">Todas</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" @selected(($filters['accion'] ?? '') === $action)>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Actor</label>
                    <select name="user_id" class="form-control bio-select2" data-placeholder="Todos" data-allow-clear="1">
                        <option value="">Todos</option>
                        @foreach($actors as $actor)
                            <option value="{{ $actor->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $actor->id)>{{ $actor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Desde</label>
                    <input type="date" name="desde" class="form-control" value="{{ $filters['desde'] ?? '' }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Hasta</label>
                    <input type="date" name="hasta" class="form-control" value="{{ $filters['hasta'] ?? '' }}">
                </div>
                <div class="col-12 mt-1">
                    <button class="btn btn-info btn-sm" type="submit">Filtrar</button>
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('bioestadistica.auditoria.index') }}">Limpiar</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table
                class="table table-bordered table-hover table-sm bio-data-table-ajax"
                data-page-length="25"
                data-order-false="1,5"
                data-url="{{ route('bioestadistica.auditoria.datatable') }}"
                data-filter-form="#bio-audit-filters"
                data-columns='[{"data":"fecha"},{"data":"actor","orderable":false},{"data":"accion","html":true},{"data":"entidad"},{"data":"ip"},{"data":"acciones","html":true,"orderable":false,"searchable":false}]'
            >
                <thead class="thead-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Actor</th>
                        <th>Acción</th>
                        <th>Entidad</th>
                        <th>IP</th>
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
