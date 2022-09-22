{{ Form::hidden('parent_id', $idDependencia) }}

<div class="form-group">
	{{	Form::label('dependency', 'Nombre:')	}}
	{{	Form::text('dependency', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">
	{{	Form::label('manager', 'Responsable:')	}}
	{{	Form::text('manager', null,['class'=>'form-control','id'=>'manager'])	}}
</div>

<div class="form-group">
	{{	Form::label('phone', 'Telefono:')	}}
	{{	Form::text('phone', null,['class'=>'form-control','id'=>'phone'])	}}
</div>

<div class="form-group">
	{{	Form::label('email', 'Correo:')	}}
	{{	Form::text('email', null,['class'=>'form-control','id'=>'email'])	}}
</div>

<div class="form-group">

	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>