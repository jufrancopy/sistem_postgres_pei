{{ Form::hidden('tipo', 'debilidad') }}
{{ Form::hidden('ambiente', 'interno') }}
{{	Form::hidden('nivel', null,['class'=>'form-control','id'=>'nivel'])	}}
<div class="form-group">
	{{	Form::label('aspecto', 'Aspecto:')	}}
	{{	Form::text('aspecto', null,['class'=>'form-control','id'=>'aspecto', 'readonly'])	}}
</div>

<div class="form-group">
	{{	Form::label('ocurrencia', 'Nivel de Ocurrencia')	}}
	{{	Form::select('ocurrencia', array('0.1' => 'Baja', '0.3' => 'Media', '0.5' => 'Alta', '0.7' => 'Muy Alta', '0.9' => 'Cierta'), NULL, array('class' => 'form-control', 'placeholder'=>'Seleccione una opcion')) }}
</div>

<div class="form-group">
	{{	Form::label('impacto', 'Nivel de Impacto')	}}
	{{	Form::select('impacto', array('0.05' => 'Muy Bajo', '0.1' => 'Bajo', '0.2' => 'Moderado', '0.4' => 'Alto', '0.8' => 'Muy Alto'), NULL, array('class' => 'form-control', 'placeholder'=>'Seleccione una opcion')) }}
</div>

<div class="form-group">
	
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('script')

@endsection