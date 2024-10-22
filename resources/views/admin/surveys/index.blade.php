@extends('layouts.master')
@section('title', 'Encuestas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Módulo Encuestas y Preguntas</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Módulo Encuestas y Preguntas</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewProfile">
                            Nueva Encuesta</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Detalle</th>
                                        <th>Analista</th>
                                        <th>Participantes</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Inicio Modal --}}
                    <div class="modal fade" id="ajaxSurveyModal" aria-hidden="true">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header card-header-info">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="surveyForm" name="surveyForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group type">
                                            {{ Form::label('type', 'Tipo:') }}
                                            {!! Form::select('type', ['examen' => 'Examen', 'group' => 'Grupal', 'corporative' => 'Corporativo'], null, [
                                                'id' => 'type',
                                                'style' => 'width:100%',
                                                'placeholder' => '',
                                            ]) !!}
                                        </div>

                                        <div class="mb-2">
                                            {{ Form::label('description', 'Descripción:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('description', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'description',
                                            ]) }}
                                        </div>

                                        <div class="form-group analysts"">
                                            {{ Form::label('analyst', 'Analista:') }}
                                            {!! Form::select('analyst_id[]', [], null, [
                                                'id' => 'analysts',
                                                'style' => 'width:100%',
                                                'multiple',
                                            ]) !!}
                                        </div>

                                        <div class="form-group participants"">
                                            {{ Form::label('participants', 'Participantes:') }}
                                            {!! Form::select('participant_id[]', [], null, [
                                                'id' => 'participants',
                                                'style' => 'width:100%',
                                                'multiple',
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
                    {{-- Fin Modal --}}
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

            // Datatables values
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
                ajax: "{{ route('surveys.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'type',
                        name: 'type'
                    }, {
                        data: 'description',
                        name: 'description',
                        render: function(data, type, full, meta) {
                            if (type === 'display') {
                                // Crear un elemento temporal para obtener solo el texto
                                var tempDiv = document.createElement("div");
                                tempDiv.innerHTML = data; // Asignar el HTML a un div
                                return tempDiv.textContent || tempDiv.innerText ||
                                    ""; // Devolver solo el texto
                            }
                            return data; // Devuelve el dato original en otros tipos
                        }
                    }, {
                        data: 'analysts',
                        name: 'analysts',
                        render: function(data, type, full, meta) {
                            var analystsArray = data.split(', ');

                            var analystsHtml = '';

                            analystsArray.forEach(function(analyst) {
                                analystsHtml += '<span class="badge badge-secondary">' +
                                    analyst + '</span> ';
                            });

                            return analystsHtml;
                        }
                    }, {
                        data: 'participants',
                        name: 'participants',
                        render: function(data, type, full, meta) {
                            var participantsArray = data.split(', ');

                            var participantsHtml = '';

                            participantsArray.forEach(function(participant) {
                                participantsHtml += '<span class="badge badge-secondary">' +
                                    participant + '</span> ';
                            });

                            return participantsHtml;
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

            // Funtion for initilization Select2
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

            // Inicialization CKEditor
            var surveyEditor;
            ClassicEditor
                .create(document.querySelector('#description'))
                .then(editor => {
                    surveyEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });


            $('body').on('click', '#createNewProfile', function() {
                var profileID = $(this).data('id');

                $('#saveBtnSurvey');
                $('#ajaxSurveyModal').modal('show');
                $('#profile_id').val('');
                $('#surveyForm').trigger("reset");
                $('#modalHeading').text('Nueva Encuesta')
                $('#type').select2({
                    placeholder: 'Tipo de Encuesta'
                });
                if (surveyEditor) {
                    surveyEditor.setData('')
                }

                //Analysts
                var url = '{{ route('globales.get-users') }}';
                $('#analysts').empty()
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

                //Participants
                var url = '{{ route('globales.get-users') }}';
                $('#participants').empty()
                $("#participants").trigger("change");
                $('#participants').select2({
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

            $('body').on('click', '.editSurvey', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('surveys.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeading').html("Editar Perfil " + data.survey.name);
                    // Mostrar modal
                    $('#ajaxSurveyModal').modal('show');

                    // Limpiar el formulario
                    $('#surveyForm')[0].reset();
                    $('#profile_id').val(data.survey.id);
                    $('#name').val(data.survey.name)

                    // Inicializar Select2 y establecer el valor del tipo
                    $('#type').select2();
                    $('#type').val(data.survey.type).trigger(
                        'change'); // Asignar el valor del servidor y actualizar

                    // Verificar que CKEditor esté completamente inicializado antes de establecer datos
                    if (surveyEditor) {
                        surveyEditor.setData(data.survey
                            .description); // Establecemos el contenido en CKEditor
                    }

                    //Clearing selections
                    $('#analysts').empty()
                    $('#analysts').select2()
                    var selectAnalysts = $('#analysts');
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
                        placeholder: 'Seleccione Analistas',
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

                    selectAnalysts.val(data.analystsChecked.map(function(d) {
                        return d.id;
                    })).trigger('change');


                    //Obtenermos los usuarios que están asociados al grupo

                    var selectParticipants = $('#participants');
                    console.log(data)
                    var groupId = data.group.parent_id 
                    var urlParticipants = '/admin/globales/get-users/' + groupId;
                    initializeSelect2(selectParticipants, 'Seleccione Participantes', urlParticipants);


                    //Llamamos los datos precargados desde el controlador
                    data.participantsChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        membersSelect.append(option).trigger('change');
                        membersSelect.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });


                });
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Guardando..');

                var data = new FormData();
                var form_data = $('#surveyForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('description', surveyEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('surveys.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.fire(
                            'Excelente!',
                            'Has Agregado una Nueva Encuesta.',
                            'success'
                        )

                        $('#surveyForm').trigger("reset");
                        $('#ajaxSurveyModal').modal('hide');
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

            $('body').on('click', '.deleteSurvey', function() {
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
                        var profileID = $(this).data("id");
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('surveys.store') }}" + '/' + profileID,
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
