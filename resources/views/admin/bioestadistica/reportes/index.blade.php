@extends('layouts.master')
@section('title', 'Bioestadística — Reportes')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">table_chart</i> Reportes configurables</h4>
        <p class="card-category">Definiciones sobre fuentes numéricas de Bioestadística. Período del dato, no fecha de carga.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @can('bio.report.manage')
            <a class="btn btn-info btn-sm mb-3" href="{{ route('bioestadistica.reportes.create') }}">
                <i class="material-icons">add</i> Nuevo reporte
            </a>
        @endcan
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Formulario</th>
                        <th>Dimensiones</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reportes as $reporte)
                    <tr>
                        <td><strong>{{ $reporte->codigo }}</strong></td>
                        <td>{{ $reporte->nombre }}</td>
                        <td>{{ $reporte->formulario->codigo ?? ($reporte->definicion['form'] ?? '—') }}</td>
                        <td>{{ implode(', ', $reporte->definicion['dimensions'] ?? []) ?: 'total' }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('bioestadistica.reportes.run', $reporte) }}">Ejecutar</a>
                            @can('bio.report.manage')
                                <a class="btn btn-secondary btn-sm" href="{{ route('bioestadistica.reportes.edit', $reporte) }}">Diseñar</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Sin reportes configurados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $reportes->links() }}
    </div>
</div>
@endsection
