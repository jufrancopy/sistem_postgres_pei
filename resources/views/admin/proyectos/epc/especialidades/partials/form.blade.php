{{ Form::hidden('user_id', auth()->user()->id) }}

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
			null, ['class' => 'form-control'])	}}
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