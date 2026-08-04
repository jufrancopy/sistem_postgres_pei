@extends('layouts.master')
@section('title', 'Grupos')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fa fa-users text-primary me-2"></i>Grupos de Trabajo
                            </h5>
                            <a class="btn btn-primary btn-sm" data-group-id="null" href="javascript:void(0)" id="createNewGroup">
                                <i class="fa fa-plus me-1"></i>Nuevo Grupo
                            </a>
                        </div>
                    </div>
                    
                    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-0">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Grupos</li>
                        </ol>
                    </nav>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="data-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">ID</th>
                                        <th>Nombre</th>
                                        <th class="text-center" style="width: 120px;">Estado</th>
                                        <th class="text-center" style="width: 180px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalHeading">
                        <i class="fa fa-users me-2"></i>Nuevo Grupo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="groupForm" name="groupForm" class="row g-3">
                        {{ Form::hidden('group_id', null, ['id' => 'group_id']) }}
                        {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}

                        <div class="col-12">
                            <label for="name" class="form-label fw-bold">
                                <i class="fa fa-tag text-primary me-1"></i>Nombre del Grupo
                            </label>
                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Ej: Comité de Planificación', 'required']) }}
                            <div class="form-text">Nombre descriptivo del grupo de trabajo</div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>Cerrar
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                <i class="fa fa-save me-1"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: "<'row mb-3'<'col-md-6'l><'col-md-6'f>>" +
                     "<'row'<'col-12'tr>>" +
                     "<'row align-items-center'<'col-md-6'i><'col-md-6'p>>" +
                     "<'row mt-3'<'col-12'B>>",
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copiar',
                        className: 'btn btn-outline-secondary btn-sm me-1',
                        titleAttr: 'Copiar al portapapeles'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: 'btn btn-outline-success btn-sm me-1',
                        titleAttr: 'Exportar a Excel'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-outline-info btn-sm me-1',
                        titleAttr: 'Exportar a CSV'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        className: 'btn btn-outline-danger btn-sm me-1',
                        titleAttr: 'Exportar a PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Imprimir',
                        className: 'btn btn-outline-secondary btn-sm',
                        titleAttr: 'Imprimir tabla'
                    }
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando <strong>_START_</strong> a <strong>_END_</strong> de <strong>_TOTAL_</strong> grupos",
                    "infoEmpty": "Mostrando 0 a 0 de 0 grupos",
                    "infoFiltered": "(filtrado de <strong>_MAX_</strong> total)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar <strong>_MENU_</strong> grupos",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron grupos",
                    "paginate": {
                        "first": "«",
                        "last": "»",
                        "next": "›",
                        "previous": "‹"
                    }
                },
                ajax: "{{ route('globales.groups.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return '<div class="fw-bold text-dark">' + data + '</div>' +
                                   '<small class="text-muted">ID: ' + row.id + '</small>';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        render: function(data) {
                            return '<span class="badge bg-success">Activo</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });

            $('#createNewGroup').click(function() {
                $('#saveBtn').val("create-user");
                $('#group_id').val('');
                $('#groupForm').trigger("reset");
                $('#modalHeading').html('<i class="fa fa-users me-2"></i>Nuevo Grupo');
                $('#ajaxModal').modal('show');
            });

            $('body').on('click', '.editGroup', function() {
                var groupID = $(this).data('id');
                $.get("{{ route('globales.groups.index') }}" + '/' + groupID + '/edit', function(data) {
                    $('#modalHeading').html('<i class="fa fa-edit me-2"></i>Editar Grupo');
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModal').modal('show');
                    $('#groupForm').trigger("reset");
                    $('#group_id').val(data.group.id);
                    $('#name').val(data.group.name);
                });
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Enviando...');
                
                $.ajax({
                    data: $('#groupForm').serialize(),
                    url: "{{ route('globales.groups.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#groupForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        table.draw();
                        toastr.success(data.success || 'Grupo guardado correctamente.');
                        $('#saveBtn').html('<i class="fa fa-save me-1"></i>Guardar Cambios');
                    },
                    error: function(data) {
                        var obj = data.responseJSON.errors;
                        $.each(obj, function(key, value) {
                            toastr.error("Atención: " + value);
                        });
                        $('#saveBtn').html('<i class="fa fa-save me-1"></i>Guardar Cambios');
                    }
                });
            });

            $('body').on('click', '.deleteGroup', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var groupId = $(this).data("id");
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('globales.groups.store') }}" + '/' + groupId,
                            success: function(data) {
                                table.draw();
                                toastr.success('Grupo eliminado correctamente.');
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                toastr.error('Error al eliminar el grupo.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
