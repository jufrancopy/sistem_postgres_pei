{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('nivel_id', NULL) }}

<div class="form-group">
	{{	Form::label('nivel', 'Perfil:')	}}
	{{	Form::text('nivel', null,['class'=>'form-control','id'=>'nivel'])	}}
</div>

<div class="form-group">
	{{	Form::label('tipo', 'Tipo')	}}
	{{	Form::select('tipo', array(
			'plan' => 'Plan',
			'objetivo' => 'Objetivo',
			'estrategia' => 'Estrategia',
			'programa' => 'Programa',
			'proyecto' => 'Proyecto',
			'actividad' => 'Actividad'),
			null, ['class' => 'form-control'])	}}
</div>

<div class="form-group">
	{{	Form::label('mision', 'Misión:')	}}
	{{	Form::text('mision', null,['class'=>'form-control','id'=>'mision'])	}}
</div>

<div class="form-group">
	{{	Form::label('vision', 'Visión:')	}}
	{{	Form::text('vision', null,['class'=>'form-control','id'=>'vision'])	}}
</div>

<div class="form-group">
	{{	Form::label('valores', 'Valores:')	}}
	{{	Form::text('valores', null,['class'=>'form-control','id'=>'valores'])	}}
</div>

<div class="form-group">
	{{	Form::label('periodo', 'Periodo:')	}}
	{{	Form::text('periodo', null,['class'=>'form-control','id'=>'periodo'])	}}
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
