{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('formulario', 'Formulario:')	}}
	{{	Form::text('formulario', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
		{{	Form::label('dependencia_emisor_id', 'Dependencia  Emisora:')	}}
		{!! Form::select('dependencia_emisor_id', $dependencias ,null,['placeholder'=>'','class'=>'dependencias', 'style'=>'width:100%'])!!}
</div>

<div class="form-group">
		{{	Form::label('dependencia_receptor_id', 'Dependencia  Receptora:')	}}
		{!! Form::select('dependencia_receptor_id', $dependencias ,null,['placeholder'=>'','class'=>'dependencias', 'style'=>'width:100%'])!!}
</div>

<div class="form-group">
    {{ Form::label('variable_id', 'Asingne Variables:') }} 
    <select multiple="multiple" name="variable_id[]" id="variable_id" class="variables" style="width:100%">
		@foreach($variables as $key => $value)
			<option value="{{ $key }}" {{ in_array($key, $variablesChecked) ? 'selected' : null }}>{{ $value }}</option>
		@endforeach
	</select>    
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')

<script>
	// Select2
	$(document).ready(function() {
		$('.variables').select2({
			placeholder: "Seleccione la Variable",
			language: "es",
		});
	})

	$(document).ready(function() {
		$('.dependencias').select2({
			placeholder: "Seleccione Dependencia",
			language: "es",
		});
	})
</script>
@endsection