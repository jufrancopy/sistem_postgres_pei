{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('variable_id', $idVariable) }}

<div class="form-group">
	{{	Form::label('item', 'Item:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'item'])	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')

<script>
	// Select2
	$(document).ready(function() {
	$('.variable').select2({
		placeholder: 'Seleccione la Variable',
		language: 'es',
	});
})
</script>
@endsection