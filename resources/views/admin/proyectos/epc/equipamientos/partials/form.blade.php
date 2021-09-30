<div class="form-group">
	{{	Form::label('item', 'Nombre:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'equipo_biomedico' => 'Equipo Biomédico',
			'equipo_informatico' => 'Equipo Informático',
			'equipo_mobiliario' => 'Equipo Mobiliario'),
			null, ['class' => 'form-control', 'placeholder'=>'', 'id'=>'detail'])	}}
</div>

<div class="form-group">
	{{	Form::label('cost', 'Costo:')	}}
	{{	Form::text('cost', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')
<script>	
	$('#detail').select2({
		placeholder: "Seleccione el Tipo"
	});
</script>
@endsection