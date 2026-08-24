@extends('layouts.master')
@section('title', 'Bioestadística — Reportes')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Reportes']]])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">table_chart</i> Reportes configurables</h4>
        <p class="card-category">Definiciones sobre fuentes numéricas de Bioestadística. Período del dato, no fecha de carga.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @can('bio.report.manage')
            <div class="d-flex bio-toolbar mb-3">
                <a class="btn btn-info btn-sm" href="{{ route('bioestadistica.reportes.create') }}">
                    <i class="material-icons">add</i> Nuevo reporte
                </a>
            </div>
        @else
            <div class="alert alert-light border mb-3">
                Solo puede <strong>ejecutar</strong> reportes. Para crear, editar o archivar se requiere el permiso de gestión de reportes.
            </div>
        @endcan
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="25" data-order-false="4">
                <thead class="thead-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Formulario</th>
                        <th>Dimensiones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($reportes as $reporte)
                    <tr>
                        <td><strong>{{ $reporte->codigo }}</strong></td>
                        <td>{{ $reporte->nombre }}</td>
                        <td>{{ $reporte->formulario->codigo ?? ($reporte->definicion['form'] ?? '—') }}</td>
                        <td>{{ implode(', ', $reporte->definicion['dimensions'] ?? []) ?: 'total' }}</td>
                        <td>
                            <div class="bio-actions">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.reportes.run', $reporte) }}">Ejecutar</a>
                                @can('bio.report.manage')
                                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('bioestadistica.reportes.edit', $reporte) }}">Editar</a>
                                    <form method="POST" action="{{ route('bioestadistica.reportes.destroy', $reporte) }}" class="d-inline bio-confirm-form" data-confirm="¿Archivar este reporte?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Archivar</button>
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
