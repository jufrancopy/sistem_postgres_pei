<div class="form-group">
	{{	Form::label('item', 'Item:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'consulta' => 'Consulta',
			'cirugia' => 'Cirugía',
			'fisioterapia' => 'Fisioterapia',
			'diagnostico' => 'Diagnóstico'),
			null, ['class' => 'form-control', 'id'=>'type', 'placeholder'=>'Seleccione el Tipo'])	}}

<div class="form-group">
	{{	Form::label('cost', 'Costo:')	}}
	{{	Form::text('cost', null,['class'=>'form-control'])	}}
</div>

<div class="col-md-12">
	{{	Form::label('detail', 'Detalle:')	}}
	{!! Form::textarea('detail', null, ['class'=>'form-control', 'id'=>'detail']) !!}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary', 'style'=>'width:100%'])	}}
</div>

@section('scripts')
<script>
$(document).ready(function() {
	$('#type').select2({
		placeholder: "Seleccione el Tipo",
	});
		CKEDITOR.replace( 'detail' );
	
	});
</script>
@endsection