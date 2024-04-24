@extends('layouts.master')
@section('title', 'Tareas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Tareas</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tareas</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @hasanyrole('Administrador')
                            <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)" id="createNewTasks">
                                Agregar Nueva Tarea</a>
                        @endhasanyrole
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Grupo</th>
                                        <th>Analista</th>
                                        {{-- <th>IDTAREA</th> --}}
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
                                        {{ Form::text('typetaskable_type[]', null, ['class' => 'form-control', 'id' => 'model']) }}

                                        <div class="form-group">
                                            {!! Form::label('typetasks', 'Asignar Tareas:') !!}
                                            {!! Form::select('typetaskable_id[]', [], null, [
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
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('analysts', 'Asignar Analistas:') }}
                                            {!! Form::select('analyst_id[]', [], null, [
                                                'placeholder' => '',
                                                'id' => 'analysts',
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
                ajax: "{{ route('tasks.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'group',
                        name: 'group'
                    }, {
                        data: 'analysts',
                        name: 'analysts',
                        render: function(data, type, full, meta) {
                            var analystsArray = data.split(', ');

                            var analystsHtml = '';

                            analystsArray.forEach(function(task) {
                                analystsHtml += '<span class="badge badge-secondary">' +
                                    task + '</span> ';
                            });

                            return analystsHtml;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Función para inicializar Select2
            function initializeSelect2(selector, placeholder, url, defaultOption) {
                selector.select2({
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
                                    };
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

                //Type Tasks
                var url = '{{ route('get-tasks') }}';
                $('#typetasks').empty().trigger('change');

                var selectedModels = []; // Arreglo para almacenar los modelos seleccionados

                $('#typetasks').select2({
                    allowClear: false,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    // Lógica para obtener la abreviatura del modelo
                                    var abbreviation;
                                    if (item.model ===
                                        'App\\Admin\\Planificacion\\Foda\\FodaPerfil') {
                                        abbreviation = 'FODA';
                                    } else if (item.model ===
                                        'App\\Admin\\Planificacion\\Pei\\PeiProfile') {
                                        abbreviation = 'PEI';
                                    } else {
                                        // Si hay otros modelos, se pueden agregar más condiciones aquí
                                        abbreviation = item.model;
                                    }

                                    return {
                                        text: item.name + ' (' + abbreviation + ')',
                                        id: item.id,
                                        model: item.model
                                    };
                                })
                            };
                        },
                        cache: true
                    }

                }).on('select2:select', function(e) {
                    var selectedModel = e.params.data.model; // Obtener el modelo seleccionado
                    selectedModels.push(
                        selectedModel); // Agregar el modelo al arreglo de modelos seleccionados

                });

                //Agregar un oyente de eventos al selector
                $('#typetasks').on('select2:select', function(e) {

                    // Limpiar el arreglo selectedModels
                    selectedModels = [];

                    // Obtener los valores seleccionados
                    var selectedValues = $('#typetasks').select2('data');

                    // Recorrer los valores seleccionados y agregarlos al arreglo selectedModels
                    selectedValues.forEach(function(value) {
                        var typetaskId = value.id;
                        var modelName = value.text.match(/\(([^)]+)\)/)[1];
                        console.log(modelName)
                        var modelPath = value.model;
                        selectedModels.push({
                            id: typetaskId,
                            model: modelPath
                        });
                    });

                    // Actualizar el valor del campo oculto "typetaskable_type[]"
                    $('#model').val(JSON.stringify(selectedModels));
                });

                // Agregar un oyente de eventos cuando se deseleccione un elemento
                $('#typetasks').on('select2:unselect', function(e) {

                    // Obtener el ID del elemento deseleccionado
                    var unselectedId = e.params.data.id;

                    // Eliminar el elemento correspondiente del arreglo selectedModels
                    selectedModels = selectedModels.filter(function(item) {
                        return item.id !== unselectedId;
                    });

                    // Actualizar el valor del campo oculto "typetaskable_type[]"
                    $('#model').val(JSON.stringify(selectedModels));
                });

                var groupRoots = $('#group_roots').select2();
                groupRoots.empty();
                initializeSelect2(groupRoots, 'Seleccione Grupo Raíz de trabajo',
                    '{{ route('globales.get-root-groups') }}');



                // Cuando se cambia el grupo raíz
                groupRoots.on('change', function() {
                    var groupRootID = $(this).val();
                    var url = 'admin/globales/get-groups/' + groupRootID;

                    // Reinicializar el selector de grupos

                    initializeSelect2(groupSelect, 'Seleccione el Grupo', url);
                });

                var groupSelect = $("#groups").select2({
                    placeholder: 'Seleccione el Grupo'
                });
                groupSelect.empty();

                //Analysts
                $("#analysts").select2({
                    placeholder: 'Seleccione Analista'
                })
                $("#analysts").val([]).change();
                $("#analysts").trigger("change");
                var url = '{{ route('globales.get-users') }}';

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
                    //Details
                    detailsEditor.setData(data.task.details);

                    //Clearing selections
                    var selectTypeTasks = $('#typetasks').select2();
                    selectTypeTasks.empty().trigger('change');

                    data.typeTasksChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectTypeTasks.append(option).trigger('change');
                        selectTypeTasks.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    //Type Tasks
                    var url = '{{ route('get-tasks') }}';
                    var selectedModels = []; // Arreglo para almacenar los modelos seleccionados

                    $('#typetasks').select2({
                        allowClear: false,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        // Lógica para obtener la abreviatura del modelo
                                        var abbreviation;
                                        if (item.model ===
                                            'App\\Admin\\Planificacion\\Foda\\FodaPerfil'
                                        ) {
                                            abbreviation = 'FODA';
                                        } else if (item.model ===
                                            'App\\Admin\\Planificacion\\Pei\\PeiProfile'
                                        ) {
                                            abbreviation = 'PEI';
                                        } else {
                                            // Si hay otros modelos, se pueden agregar más condiciones aquí
                                            abbreviation = item.model;
                                        }

                                        return {
                                            text: item.name + ' (' +
                                                abbreviation + ')',
                                            id: item.id,
                                            model: item.model
                                        };
                                    })
                                };
                            },
                            cache: true
                        }
                    }).on('select2:select', function(e) {
                        var selectedModel = e.params.data
                            .model; // Obtener el modelo seleccionado
                        selectedModels.push(
                            selectedModel
                        ); // Agregar el modelo al arreglo de modelos seleccionados
                    });

                    // RootGroup
                    var groupRoots = $('#group_roots').select2();
                    groupRoots.empty();
                    var urlGroup = 'admin/globales/get-group-parent/' + data.task.group_id
                    initSelectWithRelationshipGroupParent(urlGroup, groupRoots)

                    function initSelectWithRelationshipGroupParent(url, control) {
                        $.ajax({
                            type: 'GET',
                            url: url
                        }).then(function(data) {
                            var option = new Option(data.name, data.id, true, true);
                            control.append(option).trigger('change');
                            control.trigger({
                                type: 'select2:select',
                                params: {
                                    data: data
                                }
                            });
                        });
                    }

                    //Change RootGroup
                    $('#group_roots').on('change', function() {
                        var groupRootID = $(this).val();
                        var url = 'admin/globales/get-groups/' + groupRootID;
                        // Reinicializar el selector de grupos
                        initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                    });

                    // RootGroup
                    var group = $('#groups').select2();

                    //Clear Select
                    group.empty();

                    //Initialization Select2 with Relationship
                    var urlGroupSelected = 'admin/globales/get-group/' + data.task.group_id
                    initSelectWithRelationshipGroup(urlGroupSelected, group)

                    function initSelectWithRelationshipGroup(url, control) {
                        $.ajax({
                            type: 'GET',
                            url: url
                        }).then(function(data) {
                            var option = new Option(data.name, data.id, true, true);
                            control.append(option).trigger('change');
                            control.trigger({
                                type: 'select2:select',
                                params: {
                                    data: data
                                }
                            });
                        });
                    }

                    //Clearing selections
                    var selectAnalysts = $('#analysts');
                    selectAnalysts.val([]).trigger("change");
                    data.analystsChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectAnalysts.append(option).trigger('change');
                        selectAnalysts.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    //Analysts
                    var url = '{{ route('globales.get-users') }}';
                    var analysts = $('#analysts').select2({
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
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


                    $('#modalHeading').html("Editar Tarea");
                    $('#saveBtn').val("edit-tasks");
                    $('#ajaxModal').modal('show');
                    $('#taskForm').trigger("reset");
                    $('#task_id').val(data.task.id);
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

            $('body').on('click', '.deleteTask', function() {
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
