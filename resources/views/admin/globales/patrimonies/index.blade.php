@extends('layouts.master')
@section('title', 'Tipos de Tareas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Departamentos, Ciudades y Localidades</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lista de Cidudades por Departamentos</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewPatrimony">
                            Nuevo Patrimoio</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Departamento</th>
                                        <th>Ciudad</th>
                                        <th>Localidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxModalPatrimony" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">

                                    <form id="patrimonyForm" name="patrimonyForm" class="form-horizontal">
                                        <div class="alert alert-danger errors" role="alert"></div>

                                        {{ Form::hidden('patrimony_id', null, ['id' => 'group_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('type', 'Tipo') }}
                                            {{ Form::select(
                                                'type',
                                                [
                                                    'BIEN DE USO' => 'BIEN DE USO',
                                                    'BIEN DE RENTA' => 'BIEN DE RENTA',
                                                ],
                                                null,
                                                ['class' => 'form-control', 'placeholder' => '', 'id' => 'type', 'style' => 'width:100%'],
                                            ) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('quantityAccountCurrent', 'Cantidad de Cuenta Corriente:', ['class' => 'control-label']) }}
                                            {{ Form::number('quantityAccountCurrent', null, ['class' => 'form-control', 'id' => 'quantityAccountCurrent']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('detailLocation', 'Detalle de Ubicación:', ['class' => 'control-label']) }}
                                            {{ Form::text('detailLocation', null, ['class' => 'form-control', 'id' => 'detailLocation']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('estateQuantity', 'Cantidad de Fincas:', ['class' => 'control-label']) }}
                                            {{ Form::number('estateQuantity', null, ['class' => 'form-control', 'id' => 'estateQuantity']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('currentAccount', 'Cuenta Corriente:', ['class' => 'control-label']) }}
                                            {{ Form::number('currentAccount', null, ['class' => 'form-control', 'id' => 'currentAccount']) }}
                                        </div>

                                        {{-- <div class="form-group">
                                            {{ Form::label('department', 'Departamento:', ['class' => 'control-label']) }}
                                            {{ Form::text('department', null, ['class' => 'form-control', 'id' => 'department']) }}
                                        </div> --}}

                                        <div class="form-group">
                                            {!! Form::label('state', 'Departamento:') !!}
                                            {!! Form::select('state', $states, null, ['class' => 'form-control', 'id' => 'state', 'style' => 'width:100%']) !!}
                                        </div>
                                        
                                        <div class="form-group city">
                                            {!! Form::label('city', 'Ciudad:') !!}
                                            {!! Form::select('city', [], null, ['class' => 'form-control', 'id' => 'city', 'style' => 'width:100%']) !!}
                                        </div>

                                        <div class="form-group locality">
                                            {!! Form::label('locality', 'Barrio:') !!}
                                            {!! Form::select('locality', [], null, ['class' => 'form-control', 'id' => 'locality', 'style' => 'width:100%']) !!}
                                        </div>

                                        <div class="description mb-2">
                                            {{ Form::label('description', 'Descripción:', ['class' => 'control-label']) }}
                                            {{ Form::textarea('description', null, [
                                                'class' => 'form-control editor',
                                                'id' => 'description',
                                            ]) }}
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtn"
                                                value="create">Guardar
                                                cambios
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('scripts')
        {{-- My custom scripts --}}
        <script type="text/javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fa fa-copy"></i>',
                            titleAttr: 'Copy'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fa fa-file-excel"></i>',
                            titleAttr: 'Excel'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i>',
                            titleAttr: 'CSV'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa fa-file-pdf"></i>',
                            titleAttr: 'PDF'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i>',
                            titleAttr: 'Imprimir'
                        }
                    ],
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ Entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    ajax: "{{ route('globales.patrimonies.index') }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'desc_dpto',
                        name: 'desc_dpto'
                    }, {
                        data: 'desc_dist',
                        name: 'desc_dist'
                    }, {
                        data: 'desc_barrio_loc',
                        name: 'desc_barrio_loc'
                    }]
                });
                $('#').click(function() {
                    $('#saveBtn').val("create-user");
                    $('#group_id').val('');
                    $('#typeTaskForm').trigger("reset");
                    $('#modalHeading').html("Nuevo Tipo de Tarea");
                    $('#ajaxModal').modal('show');

                });
            });

              // Inicialization CKEditor
              var descriptionEditor;
            ClassicEditor
                .create(document.querySelector('#description'))
                .then(editor => {
                    descriptionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });


            // Improved block by ChatGPT
            // When clicked, initialize the functions
            $('#createNewPatrimony').click(function() {
                initializeForm();
                setupSelect2();
                setupEventListeners();
            });

            // Function to initialize the form
            function initializeForm() {
                $('#saveBtn').val("create-patrimony");
                $('#patrimony_id').val('');
                $('#patrimonyForm').trigger("reset");
                $('#modalHeading').html("Nuevo Patrimonio");
                $('#ajaxModalPatrimony').modal('show');
                $('.errors').removeClass("alert alert-danger");
            }

            // Initialize all Select2 elements
            function setupSelect2() {
                $('#type, #state, #city, #locality').select2();
                $('#state, #city, #locality').val([]).trigger('change.select2');
                // $('#state, #city, #locality').empty().trigger('change.select2');
            }

            // Function to capture changes in the selectors
            function setupEventListeners() {
                $('#state').on('change', onSelectStateChange);
                $('#city').on('change', onSelectCityChange);
            }

            // Function to get data when choosing a department
            function onSelectStateChange() {
                var state = $(this).val();
                console.log('Department:', state);

                // Route to fetch cities based on the selected department
                $.get('/admin/globales/locality/' + state + '/cities', function(res) {
                    updateSelectOptions('#city', res, 'Selecciona una Ciudad');
                    $('#locality').change();
                });
            }

            // Capture a chosen city
            function onSelectCityChange() {
                var city = $(this).val();
                console.log('City:', city);

                // When selecting a city, look for associated localities or neighborhoods
                $.get('/admin/globales/locality/' + city + '/localities', function(data) {
                    updateSelectOptions('#locality', data, 'Selecciona un Barrio  o Localidad');
                });
            }

            // Load data with the required parameters into the selector
            function updateSelectOptions(selectId, data, defaultOption) {
                var html_select = '<option value="">' + defaultOption + '</option>';

                for (var i = 0; i < data.length; ++i) {
                    var optionValue = data[i].desc_dist || data[i].desc_barrio_loc; // Check both properties
                    var optionText = optionValue || 'Undefined'; // Undefined

                    html_select += '<option value="' + optionValue + '">' + optionText + '</option>';
                }

                $(selectId).html(html_select).trigger('change');
            }
        </script>
    @stop
