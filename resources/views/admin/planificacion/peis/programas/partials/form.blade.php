{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('estrategia_id', $estrategia->id) }}

<div class="form-group">
	{{	Form::label('programa', 'Programa:')	}}
	{{	Form::text('programa', null,['class'=>'form-control','id'=>'formulario'])	}}
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
			placeholder: "Seleccione Gerencias",
			language: "es",
		});
	})
</script>
@endsection