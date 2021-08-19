{{ Form::hidden('user_id', auth()->user()->id) }}
<div class="form-group">
    {{  Form::label('dependencia', 'Elija la Dirección: ' ) }}
    {{  Form::select('dependencia', $dependencias, null, ['placeholder'=>'Elija la Dirección', 'class'=>'selector', 'style'=>'width:100%']) }}
</div>

<div class="form-group">
    {{ Form::label('dependency_id', 'Asingne Departamentos:') }}
    <select multiple="multiple" name="dependency_id[]" id="dependency_id" class="dependencies" style="width:100%">
        {{-- El siguiente foreach recorre dependencias si existen elegidas --}}
        @foreach($dependencies as $key => $value)
        <option value="{{ $key }}" {{ in_array($key, $dependenciasChecked) ? 'selected' : null }}>{{ $value }}</option>
        @endforeach

    </select>
</div>

<div class="form-group">
    {{	Form::submit('Guardar', ['class'=>'bt btn-sm btn-primary'])	}}
</div>

@section('scripts')
<script>
    //variable que guardará el ID de la Dependencia
var dependencia;
$(function(){
        $('#dependencia').on('change', onSelectDependenciaChange);
    });
    function onSelectDependenciaChange(){
        dependencia = $(this).val();
        
        //Aqui se envia el ID a la ruta y se almacena los datos en DATA
        $.get('/estructuras-control/'+dependencia+'/subdependencias', function(data){
            console.log(data);
            var html_select ='<option value="">Seleccione Dependencias</option>'
            for (var i=0; i<data.length; ++i)
            html_select += '<option value="'+data[i].id+'">'+data[i].dependency+'</option>';
            $('#dependency_id').html(html_select);
        });
    }
    $(".dependencies").select2({
        placeholder: "Seleccione los Departamentos",
    });

    $(document).ready(function() {
    $('.selector').select2({
        placeholder: "Elija Dirección",
    });
});
</script>
@endsection