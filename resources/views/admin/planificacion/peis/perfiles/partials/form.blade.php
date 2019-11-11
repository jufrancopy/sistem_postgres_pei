{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
    {{ Form::label('nombre', 'Nombre:')	}}
    {{ Form::text('nombre', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">
    {{ Form::label('responsable', 'Responsable:')	}}
    {{ Form::text('responsable', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">
    {{ Form::label('vigencia_desde', 'Inico:')	}}
    {{ Form::text('vigencia_desde', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

<div class="form-group">
    {{ Form::label('vigencia_hasta', 'Fin:')	}}
    {{ Form::text('vigencia_hasta', null,['class'=>'form-control','id'=>'nombre'], 'required')	}}
</div>

<div class="form-group">
    {{ Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>