{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('variable_id', $idVariable) }}

<div class="form-group">
    {{ Form::label('item', 'Item:') }}
    {{ Form::text('item', null, ['class' => 'form-control', 'id' => 'item']) }}
</div>

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
<div class="form-group">
    {{ Form::submit('Guardar', ['class' => 'bt btn-sm btn-primary']) }}
</div>

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

                var marginRightBtn = {
                    marginRight: 2
                };

                var $question = $('<input>').addClass('form-control question-field').prop('name', 'questions[]')
                    .css(marginTopBtn)
                    .appendTo($('<div>').addClass('col-md-5').appendTo($divRow))

                var $remove = $(
                        '<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
                    .css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));

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