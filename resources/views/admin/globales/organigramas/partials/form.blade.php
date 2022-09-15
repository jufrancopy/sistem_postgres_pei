{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('dependency_id', null) }}

<div class="form-group">
    {{ Form::label('dependency', 'Nombre:') }}
    {{ Form::text('dependency', null, ['class' => 'form-control', 'id' => 'nombre']) }}
</div>


<div class="form-group">
    {!! Form::label('parent', 'Dependencia  Emisora:') !!}
    {!! Form::select('parent', $parents, null, [
        'placeholder' => '',
        'class' => 'parent',
        'style' => 'width:100%',
        'id' => 'parent',
    ]) !!}
</div>

<div class="form-group">
    {{ Form::label('responsable', 'Responsable:') }}
    {{ Form::text('responsable', null, ['class' => 'form-control', 'id' => 'responsable']) }}
</div>

<div class="form-group">
    {{ Form::label('telefono', 'Telefono:') }}
    {{ Form::text('telefono', null, ['class' => 'form-control', 'id' => 'telefono']) }}
</div>

<div class="form-group">
    {{ Form::label('email', 'Correo:') }}
    {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}
</div>

<div class="form-group">

    {{ Form::submit('Guardar', ['class' => 'bt btn-sm btn-primary']) }}
</div>

<script>
    $(document).ready(function() {
        $(".parent").select2({
            placeholder: "Seleccione Parent",
            language: "es"
        });
    });
</script>
