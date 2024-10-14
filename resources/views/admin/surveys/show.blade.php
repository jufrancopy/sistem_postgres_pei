@extends('layouts.master')
@section('title', 'Encuestas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Preguntas de {{ $survey->name }}</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('surveys.index') }}">Encuestas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Preguntas de {{ $survey->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewQuestion">
                            Nueva Pregunta</a>
                    </div>

                    {{-- Inicio Lista de Preguntas --}}
                    <div class="card-body">
                        <div class="accordion" id="accordionExample">
                            @foreach ($survey->questions as $key => $question)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $key }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button"
                                                data-toggle="collapse" data-target="#collapse{{ $key }}"
                                                aria-expanded="true" aria-controls="collapse{{ $key }}">
                                                {!! $question->question !!}
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapse{{ $key }}" class="collapse"
                                        aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                @php
                                                    $answersArray = json_decode(
                                                        $question->answers()->first()->answers,
                                                        true,
                                                    ); // Obtener el primer registro de respuestas
                                                @endphp
                                                @foreach ($answersArray as $ans)
                                                    <li>
                                                        {{ $ans['answer'] }}
                                                        @if (isset($ans['is_correct']) && $ans['is_correct'])
                                                            (Correcta)
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    {{-- Fin Lista de Preguntas --}}

                    {{-- Inicio Modal Preguntas --}}
                    <div class="modal fade" id="questionModal" aria-hidden="true">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header card-header-info">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="questionForm" name="questionForm" class="form-horizontal">

                                        {{ Form::hidden('survey_id', $survey->id, ['id' => 'survey_id']) }}

                                        <div class="mb-2">
                                            {{ Form::label('question', 'Pregunta:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('question', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'question',
                                            ]) }}
                                        </div>

                                        <!-- Contenedor para las respuestas dinámicas -->
                                        <div id="answersContainer" class="mt-4">
                                            <div class="form-group">
                                                {{ Form::label('answer_id[]', 'Respuesta 1:', ['class' => 'control-label']) }}
                                                {{ Form::text('answer_id[]', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una respuesta']) }}
                                                <div>
                                                    <label for="is_correct_1">¿Es correcta?</label>
                                                    {{ Form::checkbox('is_correct[]', 1, false, ['id' => 'is_correct_1']) }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botón para añadir más respuestas -->
                                        <button type="button" class="btn btn-success btn-circle mb-2" id="addAnswer"><i
                                                class="fa fa-plus-circle" aria-hidden="true"></i>
                                        </button>


                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtn"s
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin Modal Preguntas --}}
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

            // Inicialization CKEditor
            var questionEditor;
            ClassicEditor
                .create(document.querySelector('#question'))
                .then(editor => {
                    questionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });
            var answerCount = 2; // Comenzamos con 2 porque ya hay una respuesta por defecto.

            // Función para agregar nuevas respuestas
            $('#addAnswer').on('click', function() {
                var newAnswer = `
        <div class="form-group answer-group">
            <label for="answer[]">Respuesta ${answerCount}:</label>
            <input type="text" name="answer_id[]" class="form-control" placeholder="Ingrese una respuesta" />
            <div>
                <label for="is_correct_${answerCount}">¿Es correcta?</label>
                <input type="checkbox" name="is_correct[]" value="${answerCount}" id="is_correct_${answerCount}" />
            </div>
            <button type="button" class="btn btn-danger btn-circle removeAnswer mt-1">
                <i class="fa fa-trash" aria-hidden="true"></i>
            </button>
        </div>
    `;

                $('#answersContainer').append(newAnswer);
                answerCount++; // Incrementar el contador después de añadir
            });

            // Función para eliminar una respuesta dinámica
            $('body').on('click', '.removeAnswer', function() {
                $(this).closest('.answer-group').remove();
                updateAnswerLabels(); // Actualiza los números de las respuestas después de eliminar
            });

            // Función para actualizar los números de las respuestas
            function updateAnswerLabels() {
                answerCount = 1; // Reiniciar el contador
                $('#answersContainer .answer-group').each(function() {
                    $(this).find('label').text('Respuesta ' + answerCount + ':');
                    answerCount++;
                });
            }

            // Función para eliminar una respuesta dinámica
            $('body').on('click', '.removeAnswer', function() {
                $(this).closest('.form-group').remove();
            });


            $('body').on('click', '#createNewQuestion', function() {
                var questionID = $(this).data('id');

                $('#saveBtnQuestion');
                $('#questionModal').modal('show');
                $('#profile_id').val('');
                $('#questionForm').trigger("reset");
                $('#modalHeading').text('Nueva Pregunta')

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

                });
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Guardando..');

                var data = new FormData();
                var form_data = $('#questionForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('question', questionEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('questions.store') }}",
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
