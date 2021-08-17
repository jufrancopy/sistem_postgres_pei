{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('nivel_id', $nivel_id) }}
{{ Form::hidden('idNivelSuperior', $idNivelSuperior) }}

<div class="form-group">
	{{	Form::label('nivel', 'Nivel:')	}}
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
	{{	Form::label('numerador', 'Numerador:')	}}
	{{	Form::text('numerador', null,['class'=>'form-control','id'=>'numerador'])	}}
</div>

<div class="form-group">
	{{	Form::label('operador', 'Operador:')	}}
	{{	Form::text('operador', null,['class'=>'form-control','id'=>'operador'])	}}
</div>

<div class="form-group">
	{{	Form::label('denominador', 'Denominador:')	}}
	{{	Form::text('denominador', null,['class'=>'form-control','id'=>'denominador'])	}}
</div>

<div class="form-group">
	{{	Form::label('meta', 'Meta:')	}}
	{{	Form::text('meta', null,['class'=>'form-control','id'=>'meta'])	}}
</div>

<div class="form-group">
	{{	Form::label('avance', 'Avance:')	}}
	{{	Form::text('avance', null,['class'=>'form-control','id'=>'avance'])	}}
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
