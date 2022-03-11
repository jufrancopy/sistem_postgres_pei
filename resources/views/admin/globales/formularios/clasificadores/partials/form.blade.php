{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('clasificador_id', NULL) }}

<div class="form-group">
	{{	Form::label('clasificador', 'Clasificador:')	}}
	{{	Form::text('clasificador', null,['class'=>'form-control','id'=>'clasificador'])	}}
</div>

<div class="form-group">

	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>
