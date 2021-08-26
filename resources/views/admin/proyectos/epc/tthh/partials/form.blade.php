{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('item', 'Nombre:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('especialidades', 'Especialidades')	}}
	{!! Form::select('specialty_id', $especialidades, old('specialty_id'), ['class' => 'form-control',
	'id'=>'especialidades', 'placeholder'=>'Elija Dirección']) !!}
</div>

<div class="form-group">
	{{	Form::label('hours', 'Horas:')	}}
	{{	Form::text('hours', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('cost', 'Costo:')	}}
	{{	Form::text('cost', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'medico_de_consultorio' => 'Médico de Consultorio',
			'medico_de_guardia' => 'Médico de Guardia',
			'auxiliar_de_consultorio' => 'Auxiliar de Consultorio',),
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