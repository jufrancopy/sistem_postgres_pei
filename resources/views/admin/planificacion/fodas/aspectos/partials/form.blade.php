{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('categoria_id', $categoria->id) }}

{{--
<div class="form-group">
	{{	Form::label('categoria_id', 'Categoria a la que pertenece:')	}}
	{!! Form::select('categoria_id', $categorias ,null,['placeholder'=>'Seleccione una Categoria','class'=>'js-example-responsive', 'style'=>'width:100%'])!!}
</div>
--}}

<div class="form-group">
	{{	Form::label('nombre', 'Nombre:')	}}
	{{	Form::text('nombre', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">

	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>
