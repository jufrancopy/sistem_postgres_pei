{{ Form::hidden('user_id', auth()->user()->id) }}
{{ Form::hidden('status', 'PENDIENTE') }}

<div class="form-group">
    {{ Form::label('formulario', 'Formulario:') }}
    {{ Form::text('formulario', null, ['class' => 'form-control', 'id' => 'formulario']) }}
</div>

<div class="form-group">
    {!! Form::label('dependencia_emisor_id', 'Dependencia  Emisora:') !!}
    {!! Form::select('dependencia_emisor_id', $dependencias, null, [
        'placeholder' => '',
        'class' => 'dependencie',
        'style' => 'width:100%',
        'id' => 'dependencie',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('dependencia_receptor_id', 'Dependencia  Emisora:') !!}
    {!! Form::select('dependencia_receptor_id', $dependencias, null, [
        'placeholder' => '',
        'class' => 'subDependencies ',
        'style' => 'width:100%',
        'id' => 'dependencia_receptor_id',
    ]) !!}
</div>

<div class="form-group">
    {{ Form::label('variable_id', 'Asigne Variables:') }}
    <select multiple="multiple" name="variable_id[]" id="variable_id" class="variables" style="width:100%">
        @foreach ($variables as $key => $value)
            <option value="{{ $key }}" {{ in_array($key, $variablesChecked) ? 'selected' : null }}>
                {{ $value }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    {{ Form::submit('Guardar', ['class' => 'bt btn-sm btn-primary']) }}
</div>

@section('scripts')
    <script>
        // Select2
        $(document).ready(function() {
            $(".dependencie").select2({
                placeholder: "Seleccione Dependencia",
                language: "es"
            });

            $(".subDependencies").select2({
                placeholder: "Seleccione Dependencia",
                language: "es",
            });

            $(".variables").select2({
                placeholder: "Seleccione Dependencia",
                language: "es"
            });

            $('.dependencie').select2({
                placeholder: "Seleccione Dependencia",
            }).on('change', function() {
                var $dependencie = $('.dependencie')
                var dependency_id = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '{{ route('globales.formularios.get-dependencies') }}',
                    data: {
                        'dependency_id': dependency_id
                    },
                    success: function(data) {

                        var $subDependencias = $(".subDependencies");
                        $subDependencias.empty();

                        // $.each(data, function( key, value ) {
                        // console.log( key + ": " + value );
                        // });

                        const valores = data.childrenDependencies;


                        data.forEach(element => console.log(element.dependency));

                        $.each(valores, function(value, key) {

                            $subDependencias.append($("<option></option>").attr("value",
                                key.id).text(key.dependency));
                        });
                        $subDependencias.select2();
                    },
                    error: function() {

                    }
                });
            }).trigger('change');
        })
    </script>
@endsection
