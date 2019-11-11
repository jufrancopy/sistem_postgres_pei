{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('variable', 'Variable:')	}}
	{{	Form::text('variable', null,['class'=>'form-control','id'=>'variable'])	}}
</div>

<div class="form-group">
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>
