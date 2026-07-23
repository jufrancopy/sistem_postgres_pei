@extends('layouts.master')
@section('title', 'Encuestas')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header card-header-info py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0">
                        <i class="fa fa-list-ol mr-2"></i>{{ $survey->name }}
                    </h4>
                    <p class="card-category mb-0 text-muted">
                        {{ $survey->description ?? 'Sin descripción' }}
                    </p>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge badge-info mr-2">
                        <i class="fa fa-users mr-1"></i> {{ $survey->group->name ?? 'Sin grupo' }}
                    </span>
                    <span class="badge badge-primary">
                        <i class="fa fa-question-circle mr-1"></i> {{ $survey->questions->count() }} Preguntas
                    </span>
                </div>
            </div>
        </div>

        <nav aria-label="breadcrumb" class="bg-white rounded-0 p-3 mb-0">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('surveys.index') }}" class="text-decoration-none">Encuestas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gestión de Preguntas</li>
            </ol>
        </nav>

        <div class="card-body">
            {{-- Accordion de Participantes --}}
            <div class="card border-0 mb-4 shadow-sm">
                <div class="card-header bg-light" id="headingParticipants">
                    <h5 class="mb-0">
                        <button class="btn btn-link text-decoration-none w-100 text-left" type="button" data-toggle="collapse"
                            data-target="#collapseParticipants" aria-expanded="false"
                            aria-controls="collapseParticipants">
                            <i class="fa fa-users mr-2 text-primary"></i> Lista de Participantes
                            <i class="fa fa-chevron-down float-right text-muted"></i>
                        </button>
                    </h5>
                </div>

                <div id="collapseParticipants" class="collapse" aria-labelledby="headingParticipants">
                    <div class="card-body p-0">
                        @if($survey->group)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th class="text-center" style="width: 100px;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($survey->group->members as $index => $participant)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $participant->name }}</td>
                                            <td>{{ $participant->email }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-success">Activo</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">
                                                No hay participantes registrados para este grupo
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning m-3">
                            <i class="fa fa-exclamation-triangle mr-2"></i>
                            Esta encuesta no tiene un grupo de trabajo asignado
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Header de Acciones --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fa fa-question-circle mr-2 text-info"></i>Preguntas de la Encuesta
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-success" id="createNewQuestion">
                        <i class="fa fa-plus mr-2"></i>Nueva Pregunta
                    </button>
                    <button class="btn btn-info" id="createNewQuestionWithIA">
                        <i class="fa fa-robot mr-2"></i>Generar con IA
                    </button>
                </div>
            </div>

            {{-- Lista de Preguntas --}}
            <div class="accordion" id="accordionExample">
                @forelse($survey->questions as $key => $question)
                    <div class="card border shadow-sm mb-3">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mb-0" style="flex-grow: 1;">
                                    <button class="btn btn-link text-decoration-none text-left w-100" type="button" data-toggle="collapse"
                                        data-target="#collapse{{ $key }}" aria-expanded="true"
                                        aria-controls="collapse{{ $key }}"
                                        style="white-space: normal; overflow-wrap: break-word;">
                                        <span class="badge badge-secondary mr-2">{{ $key + 1 }}</span>
                                        {!! $question->question !!}
                                    </button>
                                </h2>

                                <div class="ml-3 d-flex align-items-center">
                                    @if ($question->countAnswers() > 0)
                                        <button class="btn btn-success btn-sm mr-2" title="Tiene respuestas">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-warning btn-circle mr-2" title="Sin respuestas">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </button>
                                    @endif

                                    <button class="btn btn-danger btn-circle delete-question"
                                        data-id="{{ $question->id }}" title="Eliminar pregunta">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="collapse{{ $key }}" class="collapse" aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">
                                    <i class="fa fa-list mr-2"></i>Respuestas disponibles:
                                </h6>
                                <ul class="list-group list-group-flush">
                                    @php
                                        $answersArray = $question->answersHasQuestions;
                                    @endphp

                                    @if ($answersArray->isNotEmpty())
                                        @foreach ($answersArray as $ans)
                                            @php
                                                $decodedAnswers = is_array($ans->answers)
                                                    ? $ans->answers
                                                    : json_decode($ans->answers, true);
                                            @endphp

                                            @if (!empty($decodedAnswers))
                                                @foreach ($decodedAnswers as $item)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span class="mb-0">
                                                            @if (isset($item['is_correct']) && $item['is_correct'])
                                                                <i class="fa fa-check-circle text-success mr-2"></i>
                                                                <strong>{{ is_string($item['answer']) ? $item['answer'] : json_encode($item['answer']) }}</strong>
                                                                <span class="badge badge-success ml-2">Correcta</span>
                                                            @else
                                                                <i class="fa fa-circle text-muted mr-2"></i>
                                                                {{ is_string($item['answer']) ? $item['answer'] : json_encode($item['answer']) }}
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item text-muted">
                                                    No hay respuestas disponibles para esta pregunta.
                                                </li>
                                            @endif
                                        @endforeach
                                    @else
                                        <li class="list-group-item text-muted">
                                            No hay respuestas disponibles para esta pregunta.
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fa fa-question-circle fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay preguntas en esta encuesta</h5>
                        <p class="text-muted">Haz clic en "Nueva Pregunta" o "Generar con IA" para comenzar</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal Preguntas --}}
    @include('admin.surveys.partials.modals.create')
@stop

@section('css')
    <style>
        .list-group-item {
            border-left: none;
            border-right: none;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .card-header {
            transition: all 0.3s ease;
        }
        .card-header:hover {
            background-color: #f8f9fa;
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

            //ia_subject
            var iaSubjectEditor;
            ClassicEditor
                .create(document.querySelector('#ia_subject'))
                .then(editor => {
                    iaSubjectEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });

            var answerCount = 2;

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

            $('body').on('click', '#createNewQuestionWithIA', function() {
                var questionID = $(this).data('id');

                $('#questionModalIA').modal('show');

                $('#questionFormIA').trigger("reset");

                $('#modalHeadingIA').text('Generación de Preguntas con IA');

                //Limpiamos el Editor
                if (iaSubjectEditor) {
                    iaSubjectEditor.setData('');
                }
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
                        $('#totalQuestions').text('Total de Preguntas: ' +
                            data.totalQuestions);
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
                            console.log(obj)
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

            $('#saveBtnGenerateQuestionIA').click(function(e) {
                e.preventDefault();
                $(this).html('Generando..');

                var data = new FormData();
                var form_data = $('#questionFormIA').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('ia_subject', iaSubjectEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('questions.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#totalQuestions').text('Total de Preguntas: ' +
                            data.totalQuestions);
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

                                $('#totalQuestions').text(
                                    'Total de Preguntas: ' +
                                    response.totalQuestions);

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
@endsection
