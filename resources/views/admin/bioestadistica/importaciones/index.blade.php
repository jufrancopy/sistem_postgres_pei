@extends('layouts.master')
@section('title', 'Bioestadística — Importaciones Excel')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Importaciones']]])
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
                <div class="form-group col-md-8 mb-0">
                    <label class="small text-muted mb-1">Archivo Excel (.xls o .xlsx)</label>
                    <input class="form-control-file" type="file" name="archivo" accept=".xls,.xlsx" required>
                    <small class="text-muted">Máximo 20 MB. El sistema detecta automáticamente variables, establecimientos, formularios SP o una planilla genérica.</small>
                </div>
                <div class="form-group col-md-4 mb-0">
                    <button class="btn btn-info btn-sm"><i class="material-icons">analytics</i> Subir y analizar</button>
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
