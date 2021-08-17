{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('objetivo_id', $objetivo->id) }}

<div class="form-group">
	{{	Form::label('estrategia', 'Estrategia:')	}}
	{{	Form::text('estrategia', null,['class'=>'form-control','id'=>'estrategia'])	}}
</div>

<div class="form-group">
    {{ Form::label('dependency_id', 'Agregue dependencias vinculadas:') }} 
    <select multiple="multiple" name="dependency_id[]" id="dependency_id" class="dependencias" style="width:100%">
		@foreach($dependencies as $key => $value)
			<option value="{{ $key }}" {{ in_array($key, $dependenciesChecked) ? 'selected' : null }}>{{ $value }}</option>
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
		$('.dependencias').select2({
			placeholder: "Seleccione Direcciones",
			language: "es",
		});
	})
</script>
@endsection