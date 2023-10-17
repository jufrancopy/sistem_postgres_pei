@extends('layouts.master')
@section('title', 'Tipos de Tareas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Listado de Tareas de Tareas {{ $task->group->name }}</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tareas</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $task->group->name }} - Lista Tareas</li>
            </ol>
        </nav>
        <div class="card-header">
            <h5 class="card-title ">Ficha Técnica</h5>
            <ul class="list-group list-group-flush">
                @foreach ($analystsNames as $analystsName)
                    <li class="list-group-item"><b class="">Analista: </b>{{ $analystsName }}</li>
                @endforeach
                <li class="list-group-item"><b class="">Grupo de Trabajo: </b>
                    {{ $task->group->name }}</li>
                <li class="list-group-item"><b class="">Integrantes: </b> </li>
            </ul>


            <table class="table table-bordered">
                <thead>
                    <th class="">Nombre</th>
                    <th class="">Correo</th>
                </thead>
                @foreach ($members as $member)
                    <tbody>

                        <td class="">{{ $member->name }}</td>
                        <td class="">{{ $member->email }}</td>

                    </tbody>
                @endforeach
            </table>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tarea</th>
                                        <th>Status</th>
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
                                    <form id="taskForm" name="taskForm" class="form-horizontal">
                                        {{ Form::hidden('task_id', null, ['id' => 'task_id']) }}
                                        {{ Form::hidden('status', 0, ['id' => 'status']) }}

                                        <div class="form-group">
                                            {!! Form::label('typetasks', 'Asignar Tareas:') !!}
                                            {!! Form::select('task_id[]', [], null, [
                                                'class' => 'form-control',
                                                'style' => 'width:100%',
                                                'id' => 'typetasks',
                                                'multiple',
                                            ]) !!}
                                        </div>

                                        <div class="form-group group_roots">
                                            {{ Form::label('group_root_id', 'Elija Grupo Raíz:') }}
                                            {!! Form::select('group_root_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'group_roots',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('groups', 'Asignar Grupo de Trabajo:') }}
                                            {!! Form::select('group_id', [], null, [
                                                'id' => 'groups',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('analysts', 'Asignar Analista Grupal:') }}
                                            {!! Form::select('analyst_id[]', [], null, [
                                                'id' => 'analysts',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                                'multiple',
                                            ]) !!}
                                        </div>

                                        <div class="details mb-2">
                                            {{ Form::label('details', 'Descripción:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('details', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'details',
                                            ]) }}
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
                    ajax: "{{ route('tasks.show', $task->id) }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'task', // Usamos la columna 'tasks' creada en el controlador
                            name: 'task',
                            orderable: false, // Si no deseas que esta columna sea ordenable
                            searchable: false // Si no deseas que esta columna sea buscable
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                // Función para inicializar Select2
                function initializeSelect2(selector, placeholder, url) {
                    selector.val("").select2({
                        placeholder: placeholder,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name || item
                                                .dependency, // Use 'name' or 'dependency' depending on the selector
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                }

                var detailsEditor;

                ClassicEditor
                    .create(document.querySelector('#details'))
                    .then(editor => {
                        detailsEditor = editor;
                    })
                    .catch(err => {
                        console.error(err.stack);
                    });

                $('#createNewTasks').click(function() {
                    $('#saveBtn').val("create-user");
                    $('#group_id').val('');
                    $('#taskForm').trigger("reset");
                    $('#modalHeading').html("Nueva Tarea");
                    $('#ajaxModal').modal('show');
                    detailsEditor.setData('');

                    //Tasks
                    var url = '{{ route('get-tasks') }}';
                    $("#tasks").val([]).change();
                    $("#tasks").trigger("change");

                    var taskSelect = $('#typetasks').select2({
                        allowClear: true,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                console.log(data)
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                    // Inicializar el selector de grupo raíz
                    initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz de trabajo',
                        '{{ route('globales.get-root-groups') }}');

                    // Cuando se cambia el grupo raíz
                    $('#group_roots').on('change', function() {
                        var groupRootID = $(this).val();
                        var url = 'admin/globales/get-groups/' + groupRootID;

                        // Reinicializar el selector de grupos
                        initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                    });


                    //Analysts
                    var url = '{{ route('globales.get-users') }}';
                    $("#analysts").val([]).change();
                    $("#analysts").trigger("change");

                    $('#analysts').select2({
                        allowClear: true,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                console.log(data)
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                });

                $('body').on('click', '.editTask', function() {
                    var taskID = $(this).data('id');
                    $.get("{{ route('tasks.index') }}" + '/' + taskID + '/edit', function(data) {
                        $('#modalHeading').html("Editar Tarea ");
                        $('#saveBtn').val("edit-tasks");
                        $('#ajaxModal').modal('show');
                        $('#taskForm').trigger("reset");
                        $('#task_id').val(data.id);
                        $('#name').val(data.name);
                        $('#routes').select2();
                        $('#routes').val(data.route).trigger('change');
                    });
                });

                $('#saveBtn').click(function(e) {
                    e.preventDefault();
                    $(this).html('Procesando..');

                    var data = new FormData();
                    var form_data = $('#taskForm').serializeArray();

                    $.each(form_data, function(key, input) {
                        data.append(input.name, input.value);
                    });

                    data.append('details', detailsEditor.getData());

                    $.ajax({
                        data: data,
                        url: "{{ route('tasks.store') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $('#taskForm').trigger("reset");
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
                                url: "{{ route('tasks.store') }}" + '/' + typeTaskID,
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
