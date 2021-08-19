{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('nombre', 'Nombre:')	}}
	{{	Form::text('nombre', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">
	{{	Form::label('autor', 'Propietario:')	}}
	{{	Form::text('autor', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">

	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>
