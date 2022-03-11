<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <!-- Campos ocultos que se insertan en la tabla foda_analisis. -->
        {{ Form::hidden('user_id', auth()->user()->id) }}
        {{ Form::hidden('perfil_id') }}
        {{ Form::hidden('tipo') }}

        
        @if ($cruce->tipo == 'FO')
        <!-- Array de Fortalezas -->
        <strong>Fortalezas:</strong>
        <br /> @foreach($fortalezas as $value)
        <label>{{ Form::checkbox('fortaleza_id[]',  $value->id, in_array($value->id, $fortalezasChecked ) ? true : false, array('class' => 'name')) }}
            F{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach


        <!-- Array de Oportunidades -->
        <strong>Oportunidades:</strong>
        <br /> @foreach($oportunidades as $value)
        <label>{{ Form::checkbox('oportunidad_id[]',  $value->id, in_array($value->id, $oportunidadesChecked ) ? true : false, array('class' => 'name')) }}
            O{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach

        @elseif ($cruce->tipo == 'DO')
         <!-- Array de Debilidades -->
         <strong>Debilidades:</strong>
        <br /> @foreach($debilidades as $value)
        <label>{{ Form::checkbox('debilidad_id[]',  $value->id, in_array($value->id, $debilidadesChecked ) ? true : false, array('class' => 'name')) }}
            D{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach


        <!-- Array de Oportunidades -->
        <strong>Oportunidades:</strong>
        <br /> @foreach($oportunidades as $value)
        <label>{{ Form::checkbox('oportunidad_id[]',  $value->id, in_array($value->id, $oportunidadesChecked ) ? true : false, array('class' => 'name')) }}
            O{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach

        @elseif ($cruce->tipo == 'FA')
        <!-- Array de Debilidades -->
        <strong>Fortalezas:</strong>
        <br /> @foreach($fortalezas as $value)
        <label>{{ Form::checkbox('fortaleza_id[]',  $value->id, in_array($value->id, $fortalezasChecked ) ? true : false, array('class' => 'name')) }}
            F{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach


        <!-- Array de Amenazas -->
        <strong>Amenazas:</strong>
        <br /> @foreach($amenazas as $value)
        <label>{{ Form::checkbox('amenaza_id[]',  $value->id, in_array($value->id, $amenazasChecked ) ? true : false, array('class' => 'name')) }}
            A{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach

        @else 

        <!-- Array de Debilidades -->
        <strong>Debilidades:</strong>
        <br /> @foreach($debilidades as $value)
        <label>{{ Form::checkbox('debilidad_id[]',  $value->id, in_array($value->id, $debilidadesChecked ) ? true : false, array('class' => 'name')) }}
            D{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach

        <!-- Array de Oportunidades -->
        <strong>Amenazas:</strong>
        <br /> @foreach($amenazas as $value)
        <label>{{ Form::checkbox('amenaza_id[]',  $value->id, in_array($value->id, $amenazasChecked ) ? true : false, array('class' => 'name')) }}
            A{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
        <br /> @endforeach
        @endif

    </div>
    <div class="form-group">
        {{ Form::label('estrategia', 'Estrategia:')	}}
        {{ Form::text('estrategia', null,['class'=>'form-control','id'=>'nombre'])	}}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 text-center">
    <button type="submit" class="btn btn-success">Crear Estrategia DA</button>