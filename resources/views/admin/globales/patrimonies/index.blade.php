@extends('layouts.master')
@section('title', 'Tareas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title "> Patrimonios</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Patrimonios</li>
            </ol>
        </nav>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-realSstates-tab" data-toggle="pill" href="#pills-realSstates" role="tab"
                        aria-controls="pills-realSstates" aria-selected="true">Inmuebles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-maps-tab" data-toggle="pill" href="#pills-maps" role="tab"
                        aria-controls="pills-maps" aria-selected="false">Mapa</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Contact</a>
                </li> --}}
            </ul>
        </nav>


        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-realSstates" role="tabpanel" aria-labelledby="pills-realSstates-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                @hasanyrole('Administrador')
                                    <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                                        id="createNewPatrimony">
                                        Agregar Inmueble</a>
                                @endhasanyrole
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered data-table display nowrap" id="data-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>Ctas. Ctes.</th>
                                                <th>Detalle</th>
                                                <th>Fincas</th>
                                                <th>Departamento</th>
                                                <th width="280px">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>


                            <div class="modal fade" id="patrimonyModal" aria-hidden="true">
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

                            <div class="modal fade" id="patrimonyDetailModal" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="card-header card-header-info">
                                            <h4 class="modal-title" id="modalDetailHeading"></h4>
                                        </div>

                                        <div class="modal-body">
                                            <div class="map" id="map">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-maps" role="tabpanel" aria-labelledby="pills-maps-tab">
                <div class="row">
                    <div class="col-md-12">

                        <div id="mapPais" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div> --}}
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
                        data: 'type',
                        name: 'type'
                    }, {
                        data: 'quantityAccount',
                        name: 'quantityAccount'
                    },
                    {
                        data: 'detailLocation',
                        name: 'detailLocation'
                    },
                    {
                        data: 'estateQuantity',
                        name: 'estateQuantity'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Función para inicializar Select2
            function initializeSelect2(selector, placeholder, url, defaultOption) {
                selector.select2({
                    placeholder: placeholder,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name || item
                                            .dependency, // Use 'name' or 'dependency' depending on the selector
                                        id: item.id
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                });

            }

            var descriptionEditor;

            ClassicEditor

                .create(document.querySelector('#description'))
                .then(editor => {
                    descriptionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });


            let map;

            async function initMap() {
                try {
                    const {
                        Map
                    } = await google.maps.importLibrary("maps");
                    const {
                        AdvancedMarkerView
                    } = await google.maps.importLibrary("marker");

                    // Hacer una solicitud AJAX para obtener los detalles de los patrimonios
                    $.ajax({
                        url: "{{ url('api/patrimonies') }}",
                        method: 'GET',
                        success: function(data) {
                            // Verifica si la respuesta contiene datos
                            if (data && data.length > 0) {
                                const position = {
                                    lat: parseFloat(data[0].latitude),
                                    lng: parseFloat(data[0].longitude),
                                };

                                // El mapa, centrado en la posición del primer patrimonio
                                map = new Map(document.getElementById("mapPais"), {
                                    zoom: 2,
                                    center: position,
                                    mapId: "DEMO_MAP_ID",
                                });

                                // Itera sobre los datos y agrega marcadores al mapa
                                data.forEach(function(patrimony) {
                                    const markerPosition = {
                                        lat: parseFloat(patrimony.latitude),
                                        lng: parseFloat(patrimony.longitude),
                                    };

                                    // El marcador, posicionado en las coordenadas del patrimonio
                                    const marker = new AdvancedMarkerView({
                                        map: map,
                                        position: markerPosition,
                                        title: patrimony.type,
                                    });

                                    // Agrega un evento de clic al marcador para mostrar detalles
                                    marker.addListener('gmp-click', function() {
                                        // Crea una ventana de información para mostrar detalles
                                        const infowindow = new google.maps
                                            .InfoWindow({
                                                content: `
                                    <div>
                                        <p>Tipo de Inmueble: ${patrimony.type}</p>
                                        <p>Ctas.Corrientes: ${patrimony.quantityAccount}</p>
                                        <p>Detalles de Ubicación: ${patrimony.detailLocation}</p>
                                        <p>Fincas: ${patrimony.estateQuantity}</p>
                                        <p>Departamento: ${patrimony.department}</p>
                                        <p>Ciudad: ${patrimony.city}</p>
                                        <p>Localidad: ${patrimony.locality}</p>
                                    </div>
                                `,
                                            });

                                        // Abre la ventana de información en el mapa
                                        infowindow.open(map, marker);
                                    });
                                });
                            } else {
                                console.error('No se recibieron datos de la API.');
                            }
                        },
                        error: function() {
                            console.error('Error al hacer la solicitud AJAX a la API.');
                        }
                    });
                } catch (error) {
                    console.error("Error initializing map:", error);
                }
            }

            // Asegúrate de que la API de Google Maps se haya cargado antes de llamar a initMap
            $('#pills-maps-tab').on('click', function() {
                if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                    // La API de Google Maps ya está cargada, llama a initMap directamente
                    initMap();
                } else {
                    console.error('La API de Google Maps no se ha cargado.');
                }
            });




            // Improved block by ChatGPT
            // When clicked, initialize the functions
            $('#showDetailPatrimony').click(function() {
                initializeDetailPatrimony()
            });


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
                $('#patrimonyModal').modal('show');
                $('.errors').removeClass("alert alert-danger");
            }

            $('body').on('click', '.showDetailPatrimony', function() {
                var patrimonyID = $(this).data('id');

                $.get("{{ route('globales.patrimonies.index') }}" + '/' + patrimonyID, function(
                    data) {

                    $('#patrimonyDetailModal').modal('show')

                    // Initialize and add the map
                    let map;

                    const position = {
                        lat: parseFloat(data.latitude),
                        lng: parseFloat(data.longitude)
                    };

                    async function initMap() {
                        try {
                            const {
                                Map
                            } = await google.maps.importLibrary("maps");
                            const {
                                AdvancedMarkerView
                            } = await google.maps.importLibrary("marker");

                            // The map, centered at Uluru
                            map = new Map(document.getElementById("map"), {
                                zoom: 12,
                                center: position,
                                mapId: "DEMO_MAP_ID",
                            });

                            // The marker, positioned at Uluru
                            const marker = new AdvancedMarkerView({
                                map: map,
                                position: position,
                                title: "Uluru",
                            });
                        } catch (error) {
                            console.error("Error creating marker:", error);
                        }
                    }

                    initMap();
                });
            });

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
                console.log('Departmento:', state);

                // Route to fetch cities based on the selected department
                $.get('/admin/globales/locality/' + state + '/cities', function(res) {
                    updateSelectOptions('#city', res, 'Selecciona una ciudad');
                    $('#locality').change();
                });
            }

            // Capture a chosen city
            function onSelectCityChange() {
                var city = $(this).val();
                console.log('City:', city);

                // When selecting a city, look for associated localities or neighborhoods
                $.get('/admin/globales/locality/' + city + '/localities', function(data) {
                    updateSelectOptions('#locality', data, 'Selecciona un Barrio o Localidad');
                });
            }

            // Load data with the required parameters into the selector
            function updateSelectOptions(selectId, data, defaultOption) {
                var html_select = '<option value="">' + defaultOption + '</option>';

                for (var i = 0; i < data.length; ++i) {
                    var optionValue = data[i].desc_dist || data[i].desc_barrio_loc; // Check both properties
                    var optionText = optionValue || 'Indefinido'; // Undefined

                    html_select += '<option value="' + optionValue + '">' + optionText + '</option>';
                }

                $(selectId).html(html_select).trigger('change');
            }

        });
    </script>
@stop
