{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('name', 'Variable:')	}}
	{{	Form::text('name', null,['class'=>'form-control','id'=>'name'])	}}
</div>

<div class="form-group">
    {{ Form::label('type', 'Tipo') }}
    {{ Form::select(
    'type',
    [
        'final' => 'Final',
        'de_apoyo' => 'De Apoyo',
    ],
    null,
    ['class' => 'form-control', 'placeholder'=>'', 'id'=>'type'],
) }}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>


@section('scripts')
<script>

$(document).ready(function() {
    $('#type').select2();
});
</script>
@endsection