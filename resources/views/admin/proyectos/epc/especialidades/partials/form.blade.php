<div class="form-group">
	{{	Form::label('item', 'Item:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Nivel')	}}
	{{	Form::select('type', array(
			'primer_nivel' => 'Primer Nivel',
			'segundo_nivel' => 'Segundo Nivel',
			'tercer_nivel' => 'Tercer Nivel'),
			null, ['class' => 'form-control', 'id'=>'especialidades', 'placeholder'=>'Elija Dirección'])	}}
</div>

<div class="form-group">
	{{	Form::label('detail', 'Detalle:')	}}
	{{	Form::text('detail', null,['class'=>'form-control','id'=>'formulario'])	}}
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
	    $(document).ready(function() {
    	$('#especialidades').select2({
			placeholder: "Elija un Nivel",
	});
});
</script>
@endsection