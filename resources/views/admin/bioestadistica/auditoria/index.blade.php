@extends('layouts.master')
@section('title', 'Bioestadística — Auditoría')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">history</i> Auditoría</h4>
        <p class="card-category">Consulta append-only. No hay edición ni eliminación de asientos.</p>
    </div>
    <div class="card-body">
        <form method="GET" class="form-row align-items-end mb-3">
            <div class="col-md-3">
                <label>Entidad</label>
                <select name="entity_type" class="form-control">
                    <option value="">Todas</option>
                    @foreach($entityTypes as $type)
                        <option value="{{ $type }}" @selected(($filters['entity_type'] ?? '') === $type)>{{ class_basename($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Acción</label>
                <select name="accion" class="form-control">
                    <option value="">Todas</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(($filters['accion'] ?? '') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Actor</label>
                <select name="user_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($actors as $actor)
                        <option value="{{ $actor->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $actor->id)>{{ $actor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Desde</label>
                <input type="date" name="desde" class="form-control" value="{{ $filters['desde'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label>Hasta</label>
                <input type="date" name="hasta" class="form-control" value="{{ $filters['hasta'] ?? '' }}">
            </div>
            <div class="col-12 mt-2">
                <button class="btn btn-info btn-sm">Filtrar</button>
                <a class="btn btn-default btn-sm" href="{{ route('bioestadistica.auditoria.index') }}">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Actor</th>
                        <th>Acción</th>
                        <th>Entidad</th>
                        <th>IP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ optional($log->created_at)->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->actor->name ?? 'Sistema' }}</td>
                        <td><span class="badge badge-info">{{ $log->accion }}</span></td>
                        <td>{{ class_basename($log->entity_type) }} #{{ $log->entity_id }}</td>
                        <td>{{ $log->ip ?: '—' }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.auditoria.show', $log) }}">Detalle</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin asientos para los filtros indicados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
