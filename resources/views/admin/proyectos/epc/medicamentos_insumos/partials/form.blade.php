{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('item', 'Item:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'medicamento' => 'Medicamento',
			'insumo' => 'Insumo',
			'util_de_laboratorio' => 'Util de Laboratorio',
			'material_quirurgico' => 'Material Quirúrgico',
			'producto_quimico' => 'Producto Químico'),
			null, ['class' => 'form-control', 'placeholder'=>'', 'id'=>'type'])	}}
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
			placeholder:"Seleccion el Tipo"
		});
	</script>
@endsection