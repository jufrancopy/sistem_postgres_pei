{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('item', 'Nombre:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'servicio_agendamiento' => 'Agendamiento',
			'servicio_archivo_fichero' => 'Arhivos y Ficheros',
			'servicio_farmacia' => 'Farmacia'),
			null, ['class' => 'form-control', 'placeholder'=>'', 'id'='type'])	}}
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
		$('#type').select2({
			placeholder: "Seleccione el Tipo"
		})
	</script>
@endsection