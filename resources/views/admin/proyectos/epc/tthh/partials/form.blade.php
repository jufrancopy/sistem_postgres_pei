{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('item', 'Item:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

{{-- <div class="form-group">
	{{	Form::label('especialidades', 'Especialidades')	}}
	{!! Form::select('specialty_id', $especialidades, old('specialty_id'), ['class' => 'form-control',
	'id'=>'especialidades', 'placeholder'=>'Elija Dirección']) !!}
</div> --}}

<div class="form-group">
	{{	Form::label('hours', 'Carga horaria:')	}}
	{{	Form::text('hours', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('cost', 'Remuneración:')	}}
	{{	Form::text('cost', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'auxilliar_area_administrativa' => 'AUXILIAR DE AREA ADMINISTRATIVA',
			'auxiliar_area_salud' => 'AUXILIAR DE AREA SALUD',
			'auxiliar_apoyo_salud' => 'AUXILIAR DE APOYO EN SALUD',
			'directivo' => 'DIRECTIVO',
			'profesional_area_administraiva' => 'PROFESIONAL AREA ADMINISTRATIVA', 
			'profesional_area_salud' => 'PROFESIONAL AREA SALUD', 
			'tecnico_area_administraiva' => 'TÉCNICO AREA ADMINISTRATIVA',
			'tecnico_area_salud' => 'TÉCNICO AREA SALUD'),
			null, ['class' => 'form-control', 'id'=>'type'])	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')
<script>
	$(document).ready(function() {
    	$('#especialidades').select2({
			placeholder: "Elija un Nivel",
	});

	    $('#type').select2({
			placeholder: "Elija un Tipo",
	});
});
</script>
@endsection