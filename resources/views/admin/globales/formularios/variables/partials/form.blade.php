{{ Form::hidden('user_id', auth()->user()->id) }}

{{ Form::hidden('parent_id', null) }}

<div class="form-group">
    {{ Form::label('name', 'Nombre de Variable:') }}
    {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
</div>

<div class="form-group">
    {{ Form::label('type', 'Tipo') }}
    {{ Form::select(
        'type',
        [
            'level' => 'Nivel',
            'service' => 'Servicio',
            'require' => 'Requerimiento',
            'item' => 'Item',
            'response' => 'Respuesta',
        ],
        null,
        ['class' => 'form-control', 'placeholder' => '', 'id' => 'level'],
    ) }}
</div>

<div class="form-group">
    {{ Form::submit('Guardar', ['class' => 'bt btn-sm btn-primary']) }}
</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#type').select2({
                placeholder: "Seleccion el tipo"
            });
            $('#level').select2({
                placeholder: "Seleccion el nivel"
            });
        });
    </script>
@endsection
