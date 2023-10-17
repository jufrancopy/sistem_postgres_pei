@extends('layouts.master')
@section('title', 'Tipos de Tareas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Tipos de Tareas</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tipos de Tareas</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewTypeTasks">
                            Agregar Nuevo Tipo de Tarea</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Grupo</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="typeTaskForm" name="typeTaskForm" class="form-horizontal">
                                        {{ Form::hidden('typeTask_id', null, ['id' => 'typeTask_id']) }}
                                        {{ Form::hidden('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        {{ Form::hidden('typetaskable_type', null, ['class' => 'form-control', 'id' => 'model']) }}

                                        <div class="form-group">
                                            {{ Form::label('task', 'Ruta de la Tarea:') }}
                                            {!! Form::select('task_id', [], null, [
                                                'id' => 'tasks',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtn"
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('scripts')
        {{-- My custom scripts --}}
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
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fa fa-copy"></i>',
                            titleAttr: 'Copy'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fa fa-file-excel"></i>',
                            titleAttr: 'Excel'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i>',
                            titleAttr: 'CSV'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa fa-file-pdf"></i>',
                            titleAttr: 'PDF'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i>',
                            titleAttr: 'Imprimir'
                        }
                    ],
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ Entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    ajax: "{{ route('tasks-type.index') }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'task_value',
                        name: 'task_value'
                    }, {
                        data: 'model',
                        name: 'model'
                    }, {
                        data: 'group',
                        name: 'group'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, ]
                });
                $('#createNewTypeTasks').click(function() {
                    $('#saveBtn').val("create-user");
                    $('#group_id').val('');
                    $('#typeTaskForm').trigger("reset");
                    $('#modalHeading').html("Nuevo Tipo de Tarea");
                    $('#ajaxModal').modal('show');

                    //Type Tasks
                    var url = '{{ route('get-tasks') }}';
                    $("#tasks").val([]).change();
                    $("#tasks").trigger("change");

                    $('#tasks').select2({
                        allowClear: true,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name + ' (' + item.model + ')',
                                            id: item.id
                                        };
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                    // Agregar un oyente de eventos al selector
                    $('#tasks').on('select2:select', function(e) {
                        // Obtener el valor seleccionado
                        var selectedValue = e.params.data;

                        // Verificar si se ha seleccionado algún valor
                        if (selectedValue) {
                            // Obtener el valor de item.model de la cadena completa
                            var modelName = selectedValue.text.match(/\(([^)]+)\)/)[1];

                            // Colocar el valor de item.model en el campo oculto "model"
                            $('#model').val(modelName);

                            // Colocar el valor en el input de texto
                            $('#name').val(selectedValue.text);
                        }
                    });


                });

                $('body').on('click', '.editTypeTask', function() {
                    var typeTaskID = $(this).data('id');
                    $.get("{{ route('tasks-type.index') }}" + '/' + typeTaskID + '/edit', function(data) {
                        $('#modalHeading').html("Editar Tipo de Tarea " + data.name);
                        $('#saveBtn').val("edit-type_task");
                        $('#ajaxModal').modal('show');
                        $('#typeTaskForm').trigger("reset");
                        $('#typeTask_id').val(data.id);
                        $('#name').val(data.name);
                        $('#routes').select2();
                        $('#routes').val(data.route).trigger('change');
                    });
                });

                $('#saveBtn').click(function(e) {
                    e.preventDefault();
                    $(this).html('Enviando..');
                    $.ajax({
                        data: $('#typeTaskForm').serialize(),
                        url: "{{ route('tasks-type.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('#typeTaskForm').trigger("reset");
                            $('#ajaxModal').modal('hide');
                            table.draw();
                        },

                        error: function(data) {
                            var obj = data.responseJSON.errors;
                            $.each(obj, function(key, value) {
                                // Alert Toastr
                                toastr.options = {
                                    closeButton: true,
                                    progressBar: true,
                                };
                                toastr.error("Atención: " + value);
                            });
                            $('#saveBtn').html('Guardar Cambios');
                        }

                    });
                });

                $('body').on('click', '.deleteTypeTask', function() {
                    Swal.fire({
                        title: 'Estás seguro de eliminarlo?',
                        text: "Si lo haces, no podras revertirlo!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Estoy seguro!'
                    }).then((isConfirm) => {
                        if (isConfirm.value) {
                            Swal.fire(
                                'Borrado!',
                                'El registro ha sido eliminado correctamente.',
                                'success'
                            )
                            var typeTaskID = $(this).data("id");
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('tasks-type.store') }}" + '/' + typeTaskID,
                                success: function(data) {
                                    table.draw();
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    })
                });
            });
        </script>
    @stop
