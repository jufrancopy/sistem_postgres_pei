@extends('layouts.master')
@section('title', 'Bioestadística — Detalle de auditoría')

@php
    $formatAuditValue = static function (mixed $value): string {
        if ($value === null) {
            return '—';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value) || is_object($value)) {
            return (string) json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        return (string) $value;
    };
@endphp

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._configuraciones_tabs')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">compare</i> Asiento #{{ $log->id }}</h4>
        <p class="card-category">{{ class_basename($log->entity_type) }} #{{ $log->entity_id }} · {{ $log->accion }}</p>
    </div>
    <div class="card-body">
        <p>
            <a class="btn btn-default btn-sm" href="{{ route('bioestadistica.auditoria.index') }}">Volver</a>
        </p>
        <dl class="row">
            <dt class="col-sm-2">Fecha</dt>
            <dd class="col-sm-10">{{ optional($log->created_at)->format('d/m/Y H:i:s') }}</dd>
            <dt class="col-sm-2">Actor</dt>
            <dd class="col-sm-10">{{ $log->actor->name ?? 'Sistema' }}</dd>
            <dt class="col-sm-2">Acción</dt>
            <dd class="col-sm-10">{{ $log->accion }}</dd>
            <dt class="col-sm-2">IP</dt>
            <dd class="col-sm-10">{{ $log->ip ?: '—' }}</dd>
            <dt class="col-sm-2">Cliente</dt>
            <dd class="col-sm-10">{{ $log->user_agent ?: '—' }}</dd>
            @if(!empty($log->metadata))
                <dt class="col-sm-2">Metadata</dt>
                <dd class="col-sm-10"><pre class="mb-0">{{ json_encode($log->metadata, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre></dd>
            @endif
        </dl>

        <h5 class="mt-4">Comparación valor anterior / valor nuevo</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Atributo</th>
                        <th>Anterior</th>
                        <th>Nuevo</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($keys as $key)
                    <tr>
                        <td><code>{{ $key }}</code></td>
                        <td><pre class="mb-0">{{ $formatAuditValue($log->old_values[$key] ?? null) }}</pre></td>
                        <td><pre class="mb-0">{{ $formatAuditValue($log->new_values[$key] ?? null) }}</pre></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted">Este asiento no incluye atributos comparables.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
