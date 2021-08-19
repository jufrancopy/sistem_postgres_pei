{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('item', 'Nombre:')	}}
	{{	Form::text('item', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::label('type', 'Tipo')	}}
	{{	Form::select('type', array(
			'limpieza' => 'Limpieza',
			'seguridad' => 'Seguridad',
			'gastronomia' => 'Gastronomía',
			'ambulancia' => 'Ambulancia'),
			null, ['class' => 'form-control'])	}}
</div>

<div class="form-group">
	{{	Form::label('cost', 'Costo:')	}}
	{{	Form::text('cost', null,['class'=>'form-control','id'=>'formulario'])	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>