{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('perfil_id', $analisis->perfil_id) }}
{{ Form::hidden('aspecto_id', $analisis->aspecto_id) }}

<div class="form-group">
{{  Form::label('tipo', 'Tipo:')    }}<br>
 
@if ($ambiente == 'Interno')
 <label>
        {{  Form::radio('tipo', 'Fortaleza')}} <p class="badge badge-success">Fortaleza</p>
    </label>
    <label>
        {{  Form::radio('tipo', 'Debilidad')}} <p class="badge badge-danger">Debilidad</p>
    </label>
@else
<label>
        {{  Form::radio('tipo', 'Oportunidad')}} <p class="badge badge-success">Oportunidad</p>
    </label>
    <label>
        {{  Form::radio('tipo', 'Amenaza')}} <p class="badge badge-danger">Amenaza</p>
    </label>
@endif
</div>

<div class="form-group">
	{{	Form::label('ocurrencia', 'Nivel de Ocurrencia')	}}
	{{	Form::select('ocurrencia', array('0.10' => 'Baja', '0.30' => 'Media', '0.50' => 'Alta', '0.70' => 'Muy Alta', '0.90' => 'Cierta'), NULL, array('class' => 'form-control', 'placeholder'=>'Seleccione una opcion')) }}
</div>

<div class="form-group">
	{{	Form::label('impacto', 'Nivel de Impacto')	}}
	{{	Form::select('impacto', array('0.05' => 'Muy Bajo', '0.10' => 'Bajo', '0.20' => 'Moderado', '0.40' => 'Alto', '0.80' => 'Muy Alto'), NULL, array('class' => 'form-control', 'placeholder'=>'Seleccione una opcion')) }}
</div>

<div class="form-group">
	
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('script')

@endsection