{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
    {{ Form::label('query', 'Pregunta:') }}
    {{ Form::text('query', null, ['class' => 'form-control', 'id' => 'item']) }}
</div>

{{-- <div class="form-group">
    {{ Form::label('detail', 'Detalles de la pregunta:') }}
    {{ Form::text('detail', null, ['class' => 'form-control', 'id' => 'item']) }}
</div> --}}

{{-- {!! Form::label('type_response', 'Tipo de Respuestas:') !!}
{!! Form::select(
    'type_response',
    ['yes_no' => 'Si - No', 'integer_field' => 'Campo Numérico Entero', 'float_field' => 'Campo Numérico Flotante'],
    null,
    ['class' => 'form-control parent', 'placeholder' => ''],
) !!} --}}

{{-- <div class="form-group">
    {!! Form::label('type_response', 'Tipo de Respuestas:') !!}
    {!! Form::select('type_response', $responses, null, [
        'placeholder' => '',
        'class' => 'parent',
        'style' => 'width:100%',
        'id' => 'parent',
    ]) !!}
</div> --}}

<div class="form-group">
    <label for="questions">Preguntas:
        <button type="button" class="btn btn-success btn-circle" id="addQuestionQuantity"><i class="fa fa-plus"></i>
        </button>
    </label>

    <div data-question-quantity='@json($questionQuantity)'>
        <div>
            <div id="questionQuantityFields"></div>
        </div>
    </div>
</div>

{{-- <div class="form-group">
    {{ Form::label('', 'Es Obligatorio:') }}
    {{ Form::radio('isRequired', 'Obligatorio') }}
</div> --}}





<div class="form-group">
    {{ Form::submit('Guardar', ['class' => 'bt btn-sm btn-primary']) }}
</div>

{{-- @section('scripts')
    <script>
        $(document).ready(function() {
            $(".parent").select2({
                placeholder: "Seleccione el tipo de respuesta",
                allowClear: true,
                language: "es"
            });
        });
    </script>
@endsection --}}


@section('scripts')
    <script>
        CKEDITOR.replace('detail');

        $(document).ready(function() {
            var addQuestionQuantity = function(data) {
                var $divRow = $('<div>').addClass('row')
                    .css({
                        marginTop: '4px'
                    }).appendTo('#questionQuantityFields');

                var marginTopBtn = {
                    marginTop: 0
                };
                var $question = $('<input>').addClass('form-control question-field')
                    .prop('name', 'questions[]').css(marginTopBtn).appendTo($('<div>').addClass('col-md-5')
                        .appendTo($divRow));

                var $label = $("<label>").text('Date:');
				var $label2 = $("<label>").text('Date:');
                
					var $input = $('<input type="text">').attr({
                    id: 'txtName',
                    name: 'quantitiesQuestions'
                });
                var $txtName = $('<input type="text">').attr({
                    id: 'cb1'
                });
                var $response = $input.appendTo($label).css(marginTopBtn).appendTo($('<div>').addClass('cblist')
                    .appendTo($divRow));
				$txtName.appendTo($label2)
                // https://stackoverflow.com/questions/2055459/dynamically-create-checkbox-with-jquery-from-text-input


                // $('<input>').val('Si').attr('type', 'checkbox').attr('id', 'cb1').addClass('cblist')
                // .prop('name', 'quantitiesQuestions[]').css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));
                // $('<input>').attr('type', 'checkbox').attr('id', 'txtName' )
                // $('<input>').attr('type', 'button').val('ok').attr('id', 'btnSave' )							

                var $remove = $(
                        '<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
                    .css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));

                $('#btnSave').click(function() {
                    addCheckbox($('#txtName').val());
                });

                function addCheckbox(name) {
                    var container = $('.quantity-field');
                    var inputs = container.find('input');
                    var id = inputs.length + 1;

                    $('<input />', {
                        type: 'checkbox',
                        id: 'cb' + id,
                        value: name
                    }).appendTo(container);
                    $('<label />', {
                        'for': 'cb' + id,
                        text: name
                    }).appendTo(container);
                }

                $remove.click(function(e) {
                    e.preventDefault();
                    $divRow.remove();
                })

            }

            $('#addQuestionQuantity').on('click', function(event) {
                event.preventDefault();
                addQuestionQuantity();
            });



        });
    </script>
@endsection
