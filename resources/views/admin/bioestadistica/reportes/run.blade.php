@extends('layouts.master')
@section('title', "Reporte {$reporte->codigo}")

@php
    $query = request()->except('_token');
    $desde = $filters['periodo_desde'] ?? $result['meta']['periodo_desde'] ?? ['anio' => now()->year, 'mes' => 1];
    $hasta = $filters['periodo_hasta'] ?? $result['meta']['periodo_hasta'] ?? ['anio' => now()->year, 'mes' => now()->month];
@endphp

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $reporte->nombre }}</h4>
        <p class="card-category">{{ $reporte->codigo }} · Período de los datos: {{ $result['meta']['periodo_label'] }}</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="GET" class="form-row align-items-end mb-3">
            <div class="form-group col-md-2 mb-2">
                <label>Desde año</label>
                @include('admin.bioestadistica._periodo-anio-select', [
                    'name' => 'periodo_desde_anio',
                    'value' => $desde['anio'],
                    'required' => true,
                ])
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Desde mes</label>
                <select class="form-control bio-select2" name="periodo_desde_mes">
                    @foreach($months as $number => $label)
                        <option value="{{ $number }}" @selected((int) $desde['mes'] === $number)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Hasta año</label>
                @include('admin.bioestadistica._periodo-anio-select', [
                    'name' => 'periodo_hasta_anio',
                    'value' => $hasta['anio'],
                    'required' => true,
                ])
            </div>
            <div class="form-group col-md-2 mb-2">
                <label>Hasta mes</label>
                <select class="form-control" name="periodo_hasta_mes">
                    @foreach($months as $number => $label)
                        <option value="{{ $number }}" @selected((int) $hasta['mes'] === $number)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <button class="btn btn-info btn-block">Aplicar período</button>
            </div>
        </form>

        <p class="text-muted">
            Cobertura: {{ $result['meta']['cobertura']['informados'] ?? 0 }} de {{ $result['meta']['cobertura']['esperados'] ?? 0 }}
            ({{ $result['meta']['cobertura']['porcentaje'] ?? 0 }}%).
            @if($result['meta']['truncated'])
                <span class="text-danger">Resultado truncado a {{ $result['meta']['limit'] }} filas. La exportación síncrona no está disponible; la cola se habilitará más adelante.</span>
            @endif
        </p>

        @can('bio.report.export')
            @unless($result['meta']['truncated'])
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('bioestadistica.reportes.export.csv', $reporte) }}?{{ http_build_query(request()->query()) }}">CSV</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('bioestadistica.reportes.export.xlsx', $reporte) }}?{{ http_build_query(request()->query()) }}">Excel</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('bioestadistica.reportes.export.pdf', $reporte) }}?{{ http_build_query(request()->query()) }}">PDF</a>
            @endunless
        @endcan
        @can('bio.report.manage')
            <a class="btn btn-sm btn-outline-primary" href="{{ route('bioestadistica.reportes.edit', $reporte) }}">Editar definición</a>
            <form method="POST" action="{{ route('bioestadistica.reportes.destroy', $reporte) }}" class="d-inline bio-confirm-form" data-confirm="¿Archivar este reporte?">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" type="submit">Archivar</button>
            </form>
        @endcan
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('bioestadistica.reportes.index') }}">Volver al listado</a>

        <div class="table-responsive mt-3">
            <table id="reporteTabla" class="table table-striped table-sm">
                <thead>
                    <tr>
                        @foreach($result['columns'] as $column)
                            <th>{{ $column['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['rows'] as $row)
                        <tr>
                            @foreach($result['columns'] as $column)
                                <td>{{ $row[$column['key']] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                @if($result['totals'])
                    <tfoot>
                        <tr>
                            @foreach($result['columns'] as $index => $column)
                                <th>{{ $column['key'] === 'valor' ? $result['totals']['valor'] : ($index === 0 ? 'Total' : '') }}</th>
                            @endforeach
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
<script>
$(function () {
    if ($.fn.DataTable) {
        $('#reporteTabla').DataTable({ pageLength: 25, order: [] });
    }
});
</script>
@endsection
