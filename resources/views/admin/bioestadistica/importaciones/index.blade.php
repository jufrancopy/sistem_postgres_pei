@extends('layouts.master')
@section('title', 'Bioestadística — Importaciones Excel')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">upload_file</i> Importaciones Excel</h4>
        <p class="card-category">Analice, confirme el mapeo y ejecute una importación trazable.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @can('bio.import.execute')
        <form method="POST" action="{{ route('bioestadistica.importaciones.store') }}" enctype="multipart/form-data" class="card card-body bg-light mb-4">
            @csrf
            <div class="form-row align-items-end">
                <div class="form-group col-md-8 mb-0">
                    <label>Archivo Excel (.xls o .xlsx)</label>
                    <input class="form-control-file" type="file" name="archivo" accept=".xls,.xlsx" required>
                    <small class="text-muted">Máximo 20 MB. El sistema detecta automáticamente variables, establecimientos, formularios SP o una planilla genérica.</small>
                </div>
                <div class="form-group col-md-4 mb-0">
                    <button class="btn btn-info"><i class="material-icons">analytics</i> Subir y analizar</button>
                </div>
            </div>
        </form>
        @endcan

        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Archivo</th><th>Tipo</th><th>Estado</th><th>Usuario</th><th>Fecha</th><th></th></tr></thead>
                <tbody>
                @forelse($imports as $importacion)
                    <tr>
                        <td>{{ $importacion->archivo_original }}</td>
                        <td>{{ str_replace('_', ' ', $importacion->tipo) }}</td>
                        <td><span class="badge badge-{{ $importacion->estado === 'completado' ? 'success' : ($importacion->estado === 'error' ? 'danger' : 'info') }}">{{ $importacion->estado }}</span></td>
                        <td>{{ $importacion->creator->name ?? '—' }}</td>
                        <td>{{ $importacion->created_at?->format('d/m/Y H:i') }}</td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.importaciones.show', $importacion) }}">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">No hay importaciones registradas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $imports->links() }}
    </div>
</div>
@endsection
