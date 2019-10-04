{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('modelo_id', $modelo->id) }}

<div class="form-group">
    {{ Form::label('nombre', 'Nombre:')	}}
    {{ Form::text('nombre', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>

{{--
<div class="form-group">
	{{	Form::label('modelo_id', 'Modelo:')	}}
	{{ Form::select('modelo_id', $modelos,null,['class'=>'js-example-responsive', 'placeholder'=>'Seleccione el Modelo','style'=>'width:100%']) }}
	
</div>
--}}

<div class="form-group">
    {{ Form::label('ambiente', 'Ambiente:')    }}
    <label>
        {{ Form::radio('ambiente', 'Interno')}} INTERNO
    </label>
    <label>
        {{ Form::radio('ambiente', 'Externo')}} EXTERNO
    </label>
</div>

<div class="form-group">

    {{ Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>