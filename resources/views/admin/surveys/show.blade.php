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
                    <div class="card-header d-flex justify-content-between align-items-center" id="questionsHeader">
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewQuestion">
                            Nueva Pregunta
                        </a>
                        <a class="btn btn-info mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewQuestionWithIA">
                            Generar con IA
                        </a>
                        <div class="d-flex align-items-center">
                            <span class="btn btn-primary" id="totalQuestions">
                                Total de Preguntas <i class="fa fa-question-circle mr-2" aria-hidden="true"></i>:
                                {{ $survey->questions->count() }}
                            </span>
                        </div>
                    </div>
                    {{-- Inicio Lista de Preguntas --}}
                    <div class="card-body">
                        <div class="accordion" id="accordionExample">

                            @foreach ($survey->questions as $key => $question)
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h2 class="mb-0" style="flex-grow: 1;">
                                            <button class="btn btn-link text-left" type="button" data-toggle="collapse"
                                                data-target="#collapse{{ $key }}" aria-expanded="true"
                                                aria-controls="collapse{{ $key }}"
                                                style="white-space: normal; overflow-wrap: break-word;">
                                                {!! $question->question !!}
                                            </button>
                                        </h2>

                                        <div class="ml-2 d-flex align-items-center">
                                            @if ($question->countAnswers() > 0)
                                                <button class="btn btn-success btn-circle mr-2">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-warning btn-circle mr-2">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            @endif

                                            <button class="btn btn-danger btn-circle delete-question"
                                                data-id="{{ $question->id }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="collapse{{ $key }}" class="collapse"
                                        aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
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
                                                                <li
                                                                    @if (isset($item['is_correct']) && $item['is_correct']) style="color: green;" @endif>
                                                                    {{ is_string($item['answer']) ? $item['answer'] : json_encode($item['answer']) }}
                                                                    @if (isset($item['is_correct']) && $item['is_correct'])
                                                                        (Correcta)
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li>No hay respuestas disponibles para esta pregunta.</li>
                                                        @endif
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
