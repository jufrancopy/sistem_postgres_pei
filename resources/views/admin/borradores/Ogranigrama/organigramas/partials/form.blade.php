	{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
    {{  Form::label('alumno', 'Alumno:')    }}
    {{ Form::select('alumno_id', $alumnos, null, ['class'=>'js-example-responsive', 'style'=>'width:100%']) }}
</div>

<div class="form-group">
	{{	Form::label('carrera_id', 'Carrera a la que pertenece:')	}}
	{!! Form::select('carrera_id', $carreras ,null,['placeholder'=>'Seleccione Carrera','class'=>'js-example-responsive', 'style'=>'width:100%'])!!}

</div>

<div class="form-group">
	{{	Form::label('modalidad_id', 'Modalidad:')	}}
	{!! Form::select('modalidad_id', $modalidades,null,['placeholder'=>'...','class'=>'js-example-responsive', 'style'=>'width:100%'])!!}

</div>

<div class="form-group">
	{{	Form::label('ciclo_id', 'Ciclo:')	}}
	{!! Form::select('ciclo_id', $ciclos,null,['placeholder'=>'Seleccione Ciclo','class'=>'js-example-responsive', 'style'=>'width:100%'])!!}

</div>

<div class="form-group">
	{{	Form::label('curso_id', 'Curso:')	}}
	{!! Form::select('curso_id', $cursos,null,['placeholder'=>'...','class'=>'js-example-responsive', 'style'=>'width:100%'])!!}

<div class="form-group">
    {{ Form::label('materias', 'Materias:') }} 
    <select multiple="multiple" name="materia_id[]" id="materia_id" class="js-example-responsive" style="width:100%">
    @foreach($materias as $key => $value)
        {-- in_array verifica el valor (llave => valor) este contenido en el array --}
        <option value="{{ $key }}" {{ in_array($key, $materiasChecked) ? 'selected' : null }}>{{ $value }}</option>
    @endforeach
    </select>    
</div>

<div class="form-group">
    {{  Form::label('nro_matricula', 'Nro de Matrícula:')   }}
    {{  Form::text('nro_matricula', null,['class'=>'form-control','id'=>'nro_matricula'])   }}
</div>

<div class="form-group">
	{{	Form::label('periodo', 'Periodo')	}}
	{{	Form::select('periodo', array('2019' => '2019'), NULL, array('class' => 'form-control')) }}
</div>

<div class="form-group">
    {{  Form::label('estado', 'Estado:')    }}
    <label>
        {{  Form::radio('estado', 'MATRICULADO')}} MATRICULADO
    </label>
    <label>
        {{  Form::radio('estado', 'PENDIENTE')}} PENDIENTE
    </label>
</div>


<div class="form-group">
	
	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts') 
<script>
var carrera_id;
$(function(){
        $('#carrera_id').on('change', onSelectCarreraChange);
    });
    function onSelectCarreraChange(){
        carrera_id = $(this).val();
        
        //Ajax
        $.get('/carrera/'+carrera_id+'/modalidades', function(data){
            var html_select ='<option value="">Seleccione Modalidad</option>'
            for (var i=0; i<data.length; ++i)
            html_select += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>';
            $('#modalidad_id').html(html_select);
        });
    }

$(function(){
        $('#ciclo_id').on('change', onSelectCicloChange);
    });
    function onSelectCicloChange(){
        var ciclo_id = $(this).val();
        
        //Ajax
        $.get('/ciclo/'+ciclo_id+'/cursos', function(data){
            var html_select ='<option value="">Seleccione el Curso</option>'
            for (var i=0; i<data.length; ++i)
            html_select += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>';
            $('#curso_id').html(html_select);
        });
    }
    
    
$(function(){
        $('#curso_id').on('change', onSelectCursoChange);
    });
    function onSelectCursoChange(){
        var curso_id = $(this).val();
        
        //Ajax
        $.get('/curso/'+curso_id+'/materias?carrera_id='+carrera_id, function(data){
            var html_select ='<option value="">Elija las materias</option>'
            for (var i=0; i<data.length; ++i)
            html_select += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>';
            $('#materia_id').html(html_select);
        });
    }
    
</script>
@endsection
