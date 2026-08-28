@extends('layouts.master')
@section('title', 'Bioestadística — Importaciones Excel')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Importaciones']]])
<style>
    /* Material Dashboard oculta input[type=file] dentro de .form-group */
    .bio-file-picker {
        border: 1px dashed #94a3b8;
        border-radius: 8px;
        background: #fff;
        padding: 0.85rem 1rem;
    }
    .bio-file-picker input[type=file] {
        display: block !important;
        width: 100% !important;
        opacity: 1 !important;
        position: static !important;
        height: auto !important;
        z-index: auto !important;
        font-size: 0.9rem;
        padding: 0.35rem 0;
    }
</style>
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">upload_file</i> Importaciones Excel</h4>
        <p class="card-category">Analice, confirme el mapeo y ejecute una importación trazable.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @can('bio.import.execute')
        <form method="POST" action="{{ route('bioestadistica.importaciones.store') }}" enctype="multipart/form-data" class="bio-filters mb-3">
            @csrf
            <div class="form-row align-items-end">
                <div class="col-md-8 mb-2">
                    <label class="small text-muted mb-1" for="bio-import-archivo">Archivo Excel (.xls o .xlsx)</label>
                    <div class="bio-file-picker">
                        <input id="bio-import-archivo" type="file" name="archivo" accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                    </div>
                    <small class="text-muted">Máximo 20 MB. El sistema detecta automáticamente variables, establecimientos, formularios SP o una planilla genérica.</small>
                    @error('archivo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-2">
                    <button class="btn btn-info btn-sm btn-block" type="submit">
                        <i class="material-icons">analytics</i> Subir y analizar
                    </button>
                </div>
            </div>
        </form>
        @endcan

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="25" data-order-false="5">
                <thead class="thead-light">
                    <tr>
                        <th>Archivo</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($imports as $importacion)
                    <tr>
                        <td>{{ $importacion->archivo_original }}</td>
                        <td>{{ str_replace('_', ' ', $importacion->tipo) }}</td>
                        <td><span class="badge badge-{{ $importacion->estado === 'completado' ? 'success' : ($importacion->estado === 'error' ? 'danger' : 'info') }}">{{ $importacion->estado }}</span></td>
                        <td>{{ $importacion->creator->name ?? '—' }}</td>
                        <td data-order="{{ $importacion->created_at?->timestamp }}">{{ $importacion->created_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="bio-actions">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.importaciones.show', $importacion) }}">Ver</a>
                                @can('bio.import.execute')
                                    <form method="POST" action="{{ route('bioestadistica.importaciones.destroy', $importacion) }}" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar esta importación y su archivo? No se revierten datos ya confirmados.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                    </form>
                                @endcan
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
