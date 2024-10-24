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
        <div class="card-header">
            <div class="accordion" id="accordionParticipants">
                <div class="card">
                    <div class="card-header" id="headingParticipants">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapseParticipants" aria-expanded="false"
                                aria-controls="collapseParticipants">
                                Lista de Participantes
                                <i class="fa fa-chevron-down ml-2"></i>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseParticipants" class="collapse" aria-labelledby="headingParticipants"
                        data-parent="#accordionParticipants">
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($survey->group->members as $index => $participant)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $participant->name }}</td>
                                            <td>{{ $participant->email }}</td>
                                            <td>{{ $participant->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                    <div class="card-header d-flex justify-content-between align-items-center"
                                        id="heading{{ $key }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-left" type="button" data-toggle="collapse"
                                                data-target="#collapse{{ $key }}" aria-expanded="true"
                                                aria-controls="collapse{{ $key }}">
                                                {!! $question->question !!}
                                            </button>
                                        </h2>

                                        <div class="button-group d-flex">
                                            <button class="btn btn-info btn-circle edit-question mr-2"
                                                data-id="{{ $question->id }}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-danger btn-circle delete-question"
                                                data-id="{{ $question->id }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                            <a href="{{ route('surveys.answers', $question->survey_id) }}"
                                                class="btn btn-primary btn-circle view-questions">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>

                                        </div>
                                        
                                    </div>

                                    <div id="collapse{{ $key }}" class="collapse"
                                        aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                @php
                                                    $firstAnswerRecord = $question->answers()->first(); // Obtener el primer registro de respuestas
                                                    $answersArray = $firstAnswerRecord
                                                        ? json_decode($firstAnswerRecord->answers, true)
                                                        : [];
                                                @endphp
                                                @if (!empty($answersArray))
                                                    @foreach ($answersArray as $ans)
                                                        <li @if (isset($ans['is_correct']) && $ans['is_correct']) style="color: green;" @endif>
                                                            {{ $ans['answer'] }}
                                                            @if (isset($ans['is_correct']) && $ans['is_correct'])
                                                                (Correcta)
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li>No hay respuestas disponibles para esta pregunta.</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- Fin Lista de Preguntas --}}

                    {{-- Inicio Modal Preguntas --}}
                    @include('admin.surveys.partials.modals.create')
                    {{-- Fin Modal Preguntas --}}

                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <style>
        .correct-answer {
            color: green;
            font-weight: bold;
        }
    </style>
@endsection
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

                $('#questionForm').trigger("reset");

                $('#modalHeading').text('Nueva Pregunta');

                // Limpiamos el CKEditor
                if (questionEditor) {
                    questionEditor.setData('');
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
            });

            $('body').on('click', '.edit-question', function() {
                var questionID = $(this).data('id');

                $.get("{{ route('questions.index') }}" + '/' + questionID + '/edit', function(data) {

                    $('#modalHeading').html("Editar Preguntas " + data.survey.name);
                    // Mostrar modal
                    $('#questionModal').modal('show');


                    // Limpiar el formulario
                    $('#questionForm')[0].reset();
                    $('#question_id').val(data.question.id);
                    $('#name').val(data.question.name)
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
                        );

                        $('#surveyForm').trigger("reset");
                        $('#questionModal').modal('hide');

                        var surveyID = data
                            .surveyID; // Suponiendo que ya tienes data.surveyID definido
                        var url = '{{ route('surveys.show.details', ':id') }}'.replace(':id',
                            surveyID);

                        $.ajax({
                            url: url, // Asumiendo que tienes una ruta que retorna las preguntas de una encuesta
                            type: "GET",
                            success: function(response) {
                                // Asegúrate de que response.html contenga el HTML para el acordeón
                                $('#accordionExample').html(response
                                    .html
                                ); // Actualizamos el contenido del acordeón
                            },
                            error: function(error) {
                                toastr.error(
                                    "Hubo un problema actualizando las preguntas."
                                );
                            }
                        });
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

            $(document).ready(function() {
                // Captura el clic en los botones de eliminación
                $(document).on('click', '.delete-question', function() {
                    var questionId = $(this).data(
                        'id');
                    deleteQuestion(questionId); // Llama a la función con el ID
                });
            });

            // Define la función deleteQuestion
            function deleteQuestion(questionId) {
                // Confirmación antes de eliminar
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás recuperar esta pregunta!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí va la lógica para eliminar la pregunta
                        $.ajax({
                            url: '/questions/' + questionId,
                            type: 'DELETE',
                            dataType: 'json',
                            success: function(response) {

                                Swal.fire('¡Eliminado!', 'La pregunta ha sido eliminada.',
                                    'success');

                                // Actualizar la lista de preguntas si es necesario
                                const surveyID = response.surveyID;
                                var url = '{{ route('surveys.show.details', ':id') }}'
                                    .replace(':id', surveyID);
                                $.ajax({
                                    url: url,
                                    type: 'GET',
                                    success: function(response) {
                                        $('#accordionExample').html(response.html);
                                    },
                                    error: function(error) {
                                        toastr.error(
                                            "Hubo un problema actualizando las preguntas."
                                        );
                                    }
                                });
                            },
                            error: function(data) {
                                var obj = data.responseJSON.errors;
                                $.each(obj, function(key, value) {
                                    toastr.options = {
                                        closeButton: true,
                                        progressBar: true,
                                    };
                                    toastr.error("Atención: " + value);
                                });
                            }
                        });
                    }
                });
            }

        });
    </script>
@stop
