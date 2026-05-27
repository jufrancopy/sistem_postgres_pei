@extends('layouts.master')
@section('title', $dataset->titulo)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-table mr-2"></i>{{ $dataset->titulo }}
        </h4>
        <p class="card-category">
            {{ $dataset->categoriaLabel() }} · {{ $dataset->anio }} · {{ $dataset->fuente }}
        </p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.eph.index') }}">EPH</a></li>
            <li class="breadcrumb-item active">{{ $dataset->titulo }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Info del dataset ── --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="card border-left-info shadow py-2">
                            <div class="card-body py-1">
                                <div class="text-xs text-info text-uppercase">Registros</div>
                                <div class="h4 font-weight-bold mb-0">{{ number_format($dataset->total_filas) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="card border-left-primary shadow py-2">
                            <div class="card-body py-1">
                                <div class="text-xs text-primary text-uppercase">Columnas</div>
                                <div class="h4 font-weight-bold mb-0">{{ count($dataset->columnas ?? []) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="card border-left-warning shadow py-2">
                            <div class="card-body py-1">
                                <div class="text-xs text-warning text-uppercase">Año</div>
                                <div class="h4 font-weight-bold mb-0">{{ $dataset->anio }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="card border-left-success shadow py-2">
                            <div class="card-body py-1">
                                <div class="text-xs text-success text-uppercase">Categoría</div>
                                <div style="font-size:.8rem;font-weight:bold">{{ $dataset->categoriaLabel() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($dataset->descripcion)
                <p class="text-muted mt-2 mb-0"><i class="fa fa-info-circle mr-1"></i>{{ $dataset->descripcion }}</p>
                @endif
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('siess.dgeec.mapear', $dataset->id) }}" class="btn btn-warning btn-sm mr-1">
                    <i class="fa fa-cogs mr-1"></i> Interpretar con diccionario EPHC
                </a>
                <button class="btn btn-success btn-sm mr-1" id="btnExportCsv">
                    <i class="fa fa-file-csv mr-1"></i> Exportar CSV
                </button>
                <a href="{{ route('siess.eph.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        {{-- ── Columnas detectadas ── --}}
        @if($dataset->columnas)
        <div class="mb-3">
            <small class="text-muted font-weight-bold text-uppercase">Columnas detectadas:</small>
            <div class="mt-1">
                @foreach($dataset->columnas as $col)
                <span class="badge badge-light border mr-1 mb-1" style="font-size:.75rem">{{ $col }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Tabla dinámica ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="tablaDatos" style="font-size:.8rem">
                <thead class="thead-light">
                    <tr>
                        @foreach($dataset->columnas ?? [] as $col)
                        <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        {{-- ── Datasets relacionados ── --}}
        @if($relacionados->isNotEmpty())
        <div class="mt-4">
            <h6 class="font-weight-bold"><i class="fa fa-link mr-1"></i> Otros datasets de {{ $dataset->anio }}</h6>
            <div class="row">
                @foreach($relacionados as $rel)
                <div class="col-md-4 mb-2">
                    <a href="{{ route('siess.eph.show', $rel->id) }}" class="card border shadow-sm text-decoration-none">
                        <div class="card-body py-2">
                            <div class="font-weight-bold" style="font-size:.85rem">{{ $rel->titulo }}</div>
                            <small class="text-muted">{{ $rel->categoriaLabel() }} · {{ number_format($rel->total_filas) }} registros</small>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    var columnas = @json($dataset->columnas ?? []);

    // Construir columnas para DataTable
    var dtColumns = columnas.map(function(col) {
        return { data: col, name: col, defaultContent: '—' };
    });

    var table = $('#tablaDatos').DataTable({
        processing: true,
        serverSide: true,
        columns: dtColumns,
        language: {
            emptyTable: 'Sin datos', search: 'Buscar:',
            info: 'Mostrando _START_ a _END_ de _TOTAL_',
            paginate: { next: 'Siguiente', previous: 'Anterior' }
        },
        ajax: {
            url: '{{ route('siess.eph.show.data', $dataset->id) }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX error:', xhr.responseText);
                toastr.error('Error al cargar los datos. Revisá la consola.');
            }
        },
        pageLength: 25,
        scrollX: true,
    });

    // ── Exportar CSV ──────────────────────────────────────────────────────────
    $('#btnExportCsv').on('click', function() {
        var datos = @json($dataset->datos);
        if (!datos || datos.length === 0) { toastr.warning('Sin datos para exportar.'); return; }

        var cols = Object.keys(datos[0]);
        var csv  = cols.join(';') + '\n';
        datos.forEach(function(fila) {
            csv += cols.map(function(c) {
                var v = fila[c] !== null && fila[c] !== undefined ? fila[c] : '';
                return '"' + String(v).replace(/"/g, '""') + '"';
            }).join(';') + '\n';
        });

        var blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = '{{ $dataset->titulo }}-{{ $dataset->anio }}.csv';
        link.click();
    });
});
</script>
@stop
