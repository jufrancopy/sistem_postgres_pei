@extends('layouts.master')
@section('title', 'EPH — Contexto Nacional')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-database mr-2"></i>Contexto Estadístico Nacional — EPH</h4>
        <p class="card-category">Encuesta Permanente de Hogares — INE Paraguay</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">Estadísticas</a></li>
            <li class="breadcrumb-item active">EPH / Contexto Nacional</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Datasets</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalDatasets }}</div>
                    </div>
                </div>
            </div>
            @foreach(\App\Models\Estadistica\EphDataset::CATEGORIAS as $key => $label)
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1" style="font-size:.65rem">
                            {{ Str::limit($label, 20) }}
                        </div>
                        <div class="h3 mb-0 font-weight-bold">{{ $porCategoria->get($key, 0) }}</div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-md-2 col-sm-6 mb-3 d-flex align-items-center">
                <a href="{{ route('siess.eph.create') }}" class="btn btn-success btn-block">
                    <i class="fa fa-upload mr-1"></i> Cargar JSON
                </a>
            </div>
        </div>

        {{-- ── Filtros ── --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <select id="filtroCategoria" class="form-control form-control-sm">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filtroAnio" class="form-control form-control-sm">
                    <option value="">Todos los años</option>
                    @foreach($aniosDisponibles as $anio)
                    <option value="{{ $anio }}">{{ $anio }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── DataTable ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tablaEph">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th class="text-center">Año</th>
                        <th class="text-center">Registros</th>
                        <th>Fuente</th>
                        <th>Cargado por</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    var table = $('#tablaEph').DataTable({
        processing: true, serverSide: true,
        language: { emptyTable: 'Sin datasets cargados', search: 'Buscar:', zeroRecords: 'Sin resultados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_', paginate: { next: 'Siguiente', previous: 'Anterior' } },
        ajax: {
            url: '{{ route('siess.eph.index') }}',
            data: function(d) {
                d.categoria = $('#filtroCategoria').val();
                d.anio      = $('#filtroAnio').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',        name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'titulo',             name: 'titulo' },
            { data: 'categoria_label',    name: 'categoria' },
            { data: 'anio',               name: 'anio', className: 'text-center' },
            { data: 'total_filas',        name: 'total_filas', className: 'text-center' },
            { data: 'fuente',             name: 'fuente' },
            { data: 'cargado_por_nombre', name: 'cargado_por_nombre', orderable: false },
            { data: 'action',             name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    $('#filtroCategoria, #filtroAnio').on('change', function() { table.draw(); });

    $('body').on('click', '.deleteEph', function() {
        var id = $(this).data('id');
        Swal.fire({ title: '¿Eliminar dataset?', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc3545', confirmButtonText: 'Eliminar' })
        .then(function(r) {
            if (r.isConfirmed) {
                $.ajax({ url: '{{ url('siess/eph') }}/' + id, type: 'DELETE',
                    success: function() { toastr.success('Eliminado.'); table.draw(); },
                    error: function() { toastr.error('Error.'); }
                });
            }
        });
    });
});
</script>
@stop
