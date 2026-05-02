@extends('layouts.master')
@section('title', 'Análisis FODA Grupal')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Análisis FODA Grupal</h4>
        <p class="card-category">Eventos con matrices grupales y consolidados</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item active">Análisis FODA Grupal</li>
        </ol>
    </nav>

    <div class="card-body">
        @if(isset($peiId) && $peiId)
        <div class="alert alert-info d-flex align-items-center mb-3">
            <i class="fa fa-info-circle mr-2"></i>
            Mostrando el evento vinculado al PEI seleccionado.
            <a href="{{ route('pei-profiles.proceso', $peiId) }}" class="btn btn-sm btn-outline-info ml-auto">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Proceso
            </a>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover" id="data-table">
                <thead class="text-info">
                    <tr>
                        <th>#</th>
                        <th>Evento / Grupo Raíz</th>
                        <th>Estado FODA</th>
                        <th>Acciones</th>
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
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var peiId = '{{ $peiId ?? "" }}';

    $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', text: '<i class="fa fa-file-excel"></i>', titleAttr: 'Excel' },
            { extend: 'pdf',   text: '<i class="fa fa-file-pdf"></i>',   titleAttr: 'PDF'   },
            { extend: 'print', text: '<i class="fa fa-print"></i>',      titleAttr: 'Imprimir' },
        ],
        language: {
            emptyTable: "No hay eventos registrados", search: "Buscar:",
            info: "Mostrando _START_ a _END_ de _TOTAL_",
            paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
        },
        ajax: {
            url: "{{ route('foda-list-groups') }}",
            data: function(d) { if (peiId) d.pei_id = peiId; }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name',   name: 'name' },
            { data: 'estado', name: 'estado', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});
</script>
@stop
