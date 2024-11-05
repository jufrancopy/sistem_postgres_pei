<div class="modal fade" id="questionModal" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header card-header-info">
                <h4 class="modal-title" id="modalHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="questionForm" name="questionForm" class="form-horizontal">

                    {{ Form::hidden('survey_id', $survey->id, ['id' => 'survey_id']) }}
                    {{ Form::hidden('question_id', null, ['id' => 'question_id']) }}

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

                                {{ Form::checkbox('is_correct[]', 1, false, ['id' => 'is_correct_1', 'value' => 1]) }}

                            </div>
                        </div>
                    </div>

                    <!-- Botón para añadir más respuestas -->
                    <button type="button" class="btn btn-success btn-circle mb-2" id="addAnswer"><i
                            class="fa fa-plus-circle" aria-hidden="true"></i>
                    </button>


                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="questionModalIA" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header card-header-info">
                <h4 class="modal-title" id="modalHeadingIA"></h4>
            </div>

            <div class="modal-body">
                <form id="questionFormIA" name="questionFormIA" class="form-horizontal">

                    {{ Form::hidden('survey_id', $survey->id, ['id' => 'ia_survey_id']) }}
                    {{ Form::hidden('question_id', null, ['id' => 'ia_question_id']) }}
                    {{ Form::hidden('type', 'generate_ia') }}

                    <div class="mb-2">
                        {{ Form::label('ia_subject', 'Asunto:', ['class' => 'control-label']) }}
                        {{ Form::text('ia_subject', null, [
                            'class' => 'form-control editor',
                            'id' => 'ia_subject',
                        ]) }}
                    </div>

                    <div class="mb-2">
                        {{ Form::label('number', 'Cantidad de Preguntas:', ['class' => 'control-label']) }}
                        {{ Form::number('ia_number_question', null, [
                            'class' => 'form-control editor',
                            'id' => 'ia_number_question',
                        ]) }}
                    </div>

                    <div class="mb-2">
                        {{ Form::label('number', 'Cantidad de Respuestas por preguntas:', ['class' => 'control-label']) }}
                        {{ Form::number('ia_number_anwers_for_question', null, [
                            'class' => 'form-control editor',
                            'id' => 'ia_number_anwers_for_question',
                        ]) }}
                    </div>


                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnGenerateQuestionIA"
                            value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
