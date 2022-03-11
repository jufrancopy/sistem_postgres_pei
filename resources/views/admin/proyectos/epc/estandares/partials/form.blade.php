<div class="form-group">
    {{ Form::label('item', 'Nombre:') }}
    {{ Form::text('item', null, ['class' => 'form-control']) }}
</div>

<div class="form-group">
    {{ Form::label('type', 'Nivel') }}
    {{ Form::select('type', array(
    'Complejidad 1' => 'Complejidad 1',
    'Complejidad 2' => 'Complejidad 2',
    'Complejidad 3' => 'Complejidad 3',
    'Complejidad 4' => 'Complejidad 4',
    'Complejidad 5' => 'Complejidad 5',
    'Complejidad 6' => 'Complejidad 6' ),
    null, ['class' => 'form-control', 'placeholder'=>'', 'id'=>'type','style'=>'width:100%', 'selected'])
    }}
</div>

<div class="form-group">
    {{ Form::label('detail', 'Detalles:') }}
    {{ Form::textarea('detail', null, ['class' => 'form-control', 'id'=>'detail']) }}
</div>

<div class="form-group">
    <label for="servicios">Servicios:
        <button type="button" class="btn btn-success btn-circle" id="addServicioCantidad"><i class="fa fa-plus"></i>
        </button>

    </label>
    <div data-servicio-cantidad='@json($servicioCantidad)'>
        <div>
            <div id="servicioCantidadFields"></div>
        </div>
    </div>
</div>

<div class="form-group">
    {{ Form::submit('Guardar', ['class' => 'bt btn-sm btn-primary']) }}
</div>

@section('scripts')
<script>
    $('#type').select2({
            placeholder: "Seleccion el Tipo",
        });
        CKEDITOR.replace('detail');
        
        $(document).ready(function() {
            var addServicioCantidad = function(data) {
                var $divRow = $('<div>').addClass('row')
                    .css({
                        marginTop: '4px'
                    }).appendTo('#servicioCantidadFields');

                var marginTopBtn = {
                    marginTop: 0
                };
                var $servicio = $('<select>').addClass('form-control servicio-field')
                    .prop('name', 'servicios[]')
                    .css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

                var $cantidad = $('<input>').attr('type', 'number').addClass('form-control cantidad-field')
                    .attr('placeholder', 'Ingrese Cantidad')
                    .prop('name', 'cantidadesServicios[]')
                    .css(marginTopBtn).appendTo($('<div>').addClass('col-md-5').appendTo($divRow));

                var $remove = $(
                        '<button class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>')
                    .css(marginTopBtn).appendTo($('<div>').addClass('col-md-2').appendTo($divRow));
                if (data) {
                    $('<option>').val(data.id).text(data.item).appendTo($servicio);
                    $cantidad.val(data.pivot.cantidad).appendTo($cantidad);
                }

                $remove.click(function(e) {
                    e.preventDefault();
                    $divRow.remove();
                })

                addSelect2($servicio, 'servicios', '/servicios/get', 'Seleccionar Servicio');
            }

            var addSelect2 = function($element, name, url, placeholder) {
                var parameters = {};

                $element.select2({
                    width: 'resolve',
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            parameters.q = params.term;

                            return parameters;
                        },
                        processResults: function(data, params) {
                            return {
                                results: data
                            };
                        },
                    },
                    placeholder: placeholder,
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    minimumInputLength: 1,
                    templateResult: function(data) {
                        if (data.loading) {
                            return data.text;
                        }
                        var markup = data.text;
                        return markup;
                    },
                    templateSelection: function(data) {
                        return data.text;
                    }
                });
            }

            $(window).on("load", function() {
                var servicioCantidad = $('[data-servicio-cantidad]').data('servicioCantidad');
                $(servicioCantidad).each(function(index, data) {
                    addServicioCantidad(data);
                })
            });

            $('#addServicioCantidad').on('click', function(event) {
                event.preventDefault();
                addServicioCantidad();
            });
        });
</script>
@endsection