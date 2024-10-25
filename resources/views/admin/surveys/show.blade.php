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
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewQuestion">
                            Nueva Pregunta
                        </a>
                        <span class="badge badge-primary">
                            Total de Preguntas: {{ $survey->questions->count() }}
                        </span>
                    </div>

                    {{-- Inicio Lista de Preguntas --}}
                    <div class="card-body">
                        <div class="accordion" id="accordionExample">
                            @foreach ($survey->questions as $key => $question)
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-left" type="button" data-toggle="collapse"
                                                data-target="#collapse{{ $key }}" aria-expanded="true"
                                                aria-controls="collapse{{ $key }}">
                                                {!! $question->question !!}
                                            </button>
                                        </h2>

                                        {{-- Verificar el conteo de respuestas --}}
                                        <div class="ml-auto">
                                            @if ($question->countAnswers() > 0)
                                                <button class="btn btn-success btn-circle">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-warning btn-circle">
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
                                                    $answersArray = $question->answers()->get();
                                                @endphp
                                                @if (!$answersArray->isEmpty())
                                                    @foreach ($answersArray as $ans)
                                                        <li @if (isset($ans->is_correct) && $ans->is_correct) style="color: green;" @endif>
                                                            {{ $ans->answer }}
                                                            @if (isset($ans->is_correct) && $ans->is_correct)
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
                    $('#type').val(data.question.type).trigger('change');

                    // Limpiar CKEditor
                    if (questionEditor) {
                        questionEditor.setData(data.question.question);
                    }

                    // Llenar las respuestas
                    $('#answersContainer').empty();
                    $.each(data.question.answers, function(index, answer) {
                        var isCorrectChecked = answer.is_correct ? 'checked' : '';
                        var answerHtml = `
                            <div class="form-group answer-group">
                                <label for="answer[]">Respuesta ${index + 1}:</label>
                                <input type="text" name="answer_id[]" class="form-control" placeholder="Ingrese una respuesta" value="${answer.answer}" />
                                <div>
                                    <label for="is_correct_${index + 1}">¿Es correcta?</label>
                                    <input type="checkbox" name="is_correct[]" value="${index + 1}" id="is_correct_${index + 1}" ${isCorrectChecked} />
                                </div>
                                <button type="button" class="btn btn-danger btn-circle removeAnswer mt-1">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        `;
                        $('#answersContainer').append(answerHtml);
                    });
                    answerCount = data.question.answers.length + 1; // Actualizamos el contador

                });
            });

            $('body').on('click', '.delete-question', function() {
                var questionID = $(this).data("id");

                confirm("¿Estás seguro de que deseas eliminar esta pregunta?");
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('questions.index') }}" + '/' + questionID,
                    success: function(data) {
                        alert('Pregunta eliminada');
                        location.reload();
                    },
                    error: function(data) {
                        alert('Error al eliminar la pregunta');
                    }
                });
            });
        });
    </script>
@endsection
