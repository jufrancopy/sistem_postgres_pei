<div class="modal fade" id="questionModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header card-header-info">
                <h4 class="modal-title" id="modalHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="questionForm" name="questionForm" class="form-horizontal">

                    {{ Form::hidden('survey_id', $survey->id, ['id' => 'survey_id']) }}
                    {{ Form::hidden('question_id', null, ['id' => 'question_id']) }}

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Pregunta:</label>
                        {{ Form::textarea('question', null, [
                            'class' => 'form-control editor',
                            'id' => 'question',
                            'rows' => 3,
                            'placeholder' => 'Escribe tu pregunta aquí...'
                        ]) }}
                        <small class="form-text text-muted">Usa CKEditor para formatear tu pregunta</small>
                    </div>

                    <!-- Contenedor para las respuestas dinámicas -->
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">
                            <i class="fa fa-list mr-2"></i>Respuestas
                        </h6>
                        <div id="answersContainer">
                            <div class="form-group answer-group mb-2">
                                <div class="d-flex align-items-center">
                                    <label class="mb-0 mr-2 font-weight-bold">1.</label>
                                    {{ Form::text('answer_id[]', null, ['class' => 'form-control mr-2', 'placeholder' => 'Respuesta', 'style' => 'flex-grow: 1;']) }}
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="is_correct_1" name="is_correct[]" value="1">
                                        <label class="form-check-label" for="is_correct_1">
                                            Correcta
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm ml-2 removeAnswer" title="Eliminar">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Botón para añadir más respuestas -->
                        <button type="button" class="btn btn-success btn-sm mt-3" id="addAnswer">
                            <i class="fa fa-plus-circle mr-1"></i>Agregar Respuesta
                        </button>
                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i>Cerrar
                        </button>
                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">
                            <i class="fa fa-save mr-1"></i>Guardar Cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="questionModalIA" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header card-header-info">
                <h4 class="modal-title" id="modalHeadingIA"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="questionFormIA" name="questionFormIA" class="form-horizontal">

                    {{ Form::hidden('survey_id', $survey->id, ['id' => 'ia_survey_id']) }}
                    {{ Form::hidden('question_id', null, ['id' => 'ia_question_id']) }}
                    {{ Form::hidden('type', 'generate_ia') }}

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Asunto de la Pregunta:</label>
                        {{ Form::text('ia_subject', null, [
                            'class' => 'form-control editor',
                            'id' => 'ia_subject',
                            'placeholder' => 'Ej: Gestión de recursos humanos en el IPS'
                        ]) }}
                        <small class="form-text text-muted">Describe el tema sobre el cual quieres generar preguntas</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Cantidad de Preguntas:</label>
                            {{ Form::number('ia_number_question', 3, [
                                'class' => 'form-control',
                                'id' => 'ia_number_question',
                                'min' => 1,
                                'max' => 10
                            ]) }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Respuestas por Pregunta:</label>
                            {{ Form::number('ia_number_answers_for_question', 4, [
                                'class' => 'form-control',
                                'id' => 'ia_number_answers_for_question',
                                'min' => 2,
                                'max' => 6
                            ]) }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Idioma:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="language" id="language_es" value="language_es" checked>
                            <label class="form-check-label" for="language_es">
                                Español
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="language" id="language_en" value="language_en">
                            <label class="form-check-label" for="language_en">
                                Inglés
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i>Cerrar
                        </button>
                        <button type="submit" class="btn btn-info" id="saveBtnGenerateQuestionIA" value="create">
                            <i class="fa fa-robot mr-1"></i>Generar con IA
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
