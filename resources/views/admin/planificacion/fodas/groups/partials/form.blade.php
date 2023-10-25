{{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}
{{ Form::hidden('type', 'consolidado', ['id' => 'type']) }}
{{ Form::hidden('group_id', $idGroup, ['id' => 'group_id']) }}
{{ Form::hidden('model_id', $modelId, ['id' => 'model_id']) }}
@if (isset($fodaProfile) && count($fodaProfile) > 0)
    <div class="form-group">
        {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
        {{ Form::text('name', $fodaProfile->name, ['class' => 'form-control', 'id' => 'name']) }}
    </div>

    <div class="form-group">
        {{ Form::label('context', 'Contexto:') }}
        {{ Form::text('context', $fodaProfile->context, ['class' => 'form-control', 'id' => 'context']) }}
    </div>

    <div class="form-group dependencies">
        {{ Form::label('dependency_id', 'Seleccione Dependencia Responsable:') }}
        {!! Form::select('dependency_id', [], null, [
            'id' => 'dependency',
            'style' => 'width:100%',
        ]) !!}
    </div>

    <div class="form-group">
        {{ Form::submit('Generar Perfil', ['class' => 'bt btn-sm btn-primary']) }}
    </div>
@else
    <div class="form-group">
        {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
    </div>

    <div class="form-group">
        {{ Form::label('context', 'Contexto:') }}
        {{ Form::text('context', null, ['class' => 'form-control', 'id' => 'context']) }}
    </div>

    <div class="form-group dependencies">
        {{ Form::label('dependency_id', 'Seleccione Dependencia Responsable:') }}
        {!! Form::select('dependency_id', [], null, [
            'id' => 'dependency',
            'style' => 'width:100%',
        ]) !!}
    </div>

    <div class="form-group">
        {{ Form::submit('Generar Perfil', ['class' => 'bt btn-sm btn-primary']) }}
    </div>
@endif

@section('scripts')
    <script>
        $('#dependency').select2({
            placeholder: 'Seleccione la dependencia',
            ajax: {
                url: '{{ route('globales.get-dependencies') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.dependency,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endsection
