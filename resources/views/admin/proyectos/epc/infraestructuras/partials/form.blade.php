{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('item', 'Nombre:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'servicio' => 'Servicio',
			'ambulatorio' => 'Ambulatorio',
			'administrativo' => 'Administrativo',
			'hospitalizacion' => 'Hospitalización',
			'quirurgico' => 'Quirúrgico'),
			null, ['class' => 'form-control'])	}}
</div>

<div class="form-group">
	{{	Form::label('measurement', 'Dimensiones:')	}}
	{{	Form::number('measurement', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('cost', 'Costo:')	}}
	{{	Form::text('cost', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>