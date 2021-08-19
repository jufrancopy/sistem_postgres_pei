{{ Form::hidden('user_id', auth()->user()->id) }}

<div class="form-group">
	{{	Form::label('nombre', 'Nombre:')	}}
	{{	Form::text('nombre', null,['class'=>'form-control','id'=>'nombre'])	}}
</div>
<div class="form-group">
	{{	Form::label('contexto', 'Contexto:')	}}
	{{	Form::text('contexto', null,['class'=>'form-control','id'=>'contexto'])	}}
</div>

<div class="form-group">
	{{	Form::label('modelo_id', 'Elija el Modelo:')	}}
	{!! Form::select('modelo_id', $modelos ,null,['placeholder'=>'Seleccione el Modelo','class'=>'js-example-responsive', 'style'=>'width:100%'])!!}
</div>

<div class="form-group">
    {{ Form::label('categorias', 'Asingne una o variasCategorias:') }} 
    <select multiple="multiple" name="categoria_id[]" id="categoria_id" class="categorias" style="width:100%">
    @foreach($categorias as $key => $value)
        {-- in_array verifica el valor (llave => valor) este contenido en el array --}
        <option value="{{ $key }}" {{ in_array($key, $categoriasChecked) ? 'selected' : null }}>{{ $value }}</option>
    @endforeach
    <input type="checkbox" id="checkbox" >Seleccionar todo
    </select>    
</div>

<div class="form-group">

	{{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')
<script>
var modelo_id;
$(function(){
        $('#modelo_id').on('change', onSelectModeloChange);
    });
    function onSelectModeloChange(){
        modelo_id = $(this).val();
        
        //Ajax
        $.get('/foda-perfiles-modelo/'+modelo_id+'/categorias', function(data){
            var html_select ='<option value="">Seleccione Modelos</option>'
            for (var i=0; i<data.length; ++i)
            html_select += '<option value="'+data[i].id+'">'+data[i].nombre+'</option>';
            $('#categoria_id').html(html_select);
        });
    }
    
$(".categorias").select2();
    
</script>
@endsection


