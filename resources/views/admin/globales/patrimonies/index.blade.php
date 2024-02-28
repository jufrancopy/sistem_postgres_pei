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
                    <a class="nav-link active" id="pills-realSstates-tab" data-toggle="pill" href="#pills-realSstates"
                        role="tab" aria-controls="pills-realSstates" aria-selected="true">Inmuebles</a>
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
            <div class="tab-pane fade show active" id="pills-realSstates" role="tabpanel"
                aria-labelledby="pills-realSstates-tab">
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
                                <div class="modal-dialog modal-lg" style="overflow: auto">
                                    <div class="modal-content">
                                        <div class="card-header card-header-info">
                                            <h4 class="modal-title" id="modalHeading"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="patrimonyForm" name="patrimonyForm" class="form-horizontal">
                                                <div class="alert alert-danger errors" role="alert"></div>
                                                @include('admin.globales.patrimonies.partials.form')
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
@section('css')
    <style>
        .holder {
            position: relative;
            width: 100%;
            height: 200px;
            /* Altura deseada */
            overflow: hidden;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ajusta según tus necesidades: cover, contain, etc. */
        }
    </style>
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

            var patrimonyEditor;

            ClassicEditor

                .create(document.querySelector('#description'))
                .then(editor => {
                    patrimonyEditor  = editor;
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
                // var imgPreview = "{{ asset('images/students/web/sin_foto.png') }}";

                var imgPreview = "https://placebear.com/640/360";
                $('.holder img').attr('src', imgPreview);

                $('#mainPhotoFile').change(function() {
                    const file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function(event) {
                            $('.holder img').attr('src', event.target.result);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Initialize all Select2 elements
            function setupSelect2() {
                $('#type, #state, #city, #locality, #infrastructureType').select2();
                $('#state, #city, #locality, #infrastructureType').val([]).trigger('change.select2');
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

            $('body').on('click', '.editProfile', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeading').html("Editar Perfil " + data.profile.name);
                    $('#saveBtn').val("edit-profile");
                    $('#ajaxModal').modal('show');
                    $('#profileForm').trigger("reset");
                    $('.errors').removeClass("alert alert-danger")
                    $('#profile_id').val(data.profile.id);
                    $('#group_id').val(data.profile.group_id);
                    $('#name').val(data.profile.name);
                    $('#year_start').val(data.profile.year_start);
                    $('#year_end').val(data.profile.year_end);
                    $('#mision').val(data.profile.mision);
                    $('#vision').val(data.profile.vision);
                    $('#values').val(data.profile.values);

                    // Inicializa los selectores de dependencia y grupo raíz
                    initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz de trabajo',
                        '{{ route('globales.get-root-groups') }}');


                    var profileType = data.profile.type;

                    var selectTypeProfile = $('#type_profile').select2()
                    selectTypeProfile.val(profileType).trigger('change');;
                    selectTypeProfile.change(function() {
                        if ($(this).val() === 'corporative') {
                            $('.form-group.dependencies').show();
                            $('.form-group.groups').hide();
                            $('#type').val('corporative');
                        } else if ($(this).val() === 'group') {
                            $('.form-group.dependencies').hide();
                            $('.form-group.groups').show();
                            $('#type').val('group');
                        }
                    });

                    // Luego, condiciona la visibilidad según el valor de 'profileType'.
                    if (profileType === 'corporative') {
                        $('.form-group.dependencies').show();
                        $('.form-group.groups').hide();
                        $('#type').val('corporative');
                    } else if (profileType === 'group') {
                        $('.form-group.dependencies').hide();
                        $('.form-group.groups').show();
                        $('#type').val('group');
                    }

                    //Función para precargar selectores relacionados...
                    function initSelect2WithRelationship(control, key, value) {
                        var data = {
                            id: key,
                            text: value
                        };
                        var initOption = new Option(data.text, data.id, true,
                            true); // Establece el tercer y cuarto parámetro en "true"
                        control.empty().append(initOption).trigger('change');
                    }

                    //Inizialización de selector con función de datos relacionales
                    initSelect2WithRelationship($('#dependencies'), data.profile.dependency_id, data
                        .profile
                        .dependency.dependency);

                    //Selector que busca dependencias si se requiere asociar
                    $('#dependencies').select2({
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

                    //Inizialización de selector con función de datos relacionales

                    initSelect2WithRelationship($('#group_roots'), data.profile.group_id, data
                        .profile
                        .group.name);

                    // Cuando se cambia el grupo raíz
                    $('#group_roots').on('change', function() {
                        var groupRootID = $(this).val();
                        //Buscamos los grupos asociados al Grupo Raíz o Evento
                        var url = 'admin/globales/get-groups/' + groupRootID;

                        // Reinicializar el selector de grupos
                        initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                    });

                    //Clearing selections
                    $('#analysts').empty()
                    $('#analysts').select2()
                    var selectAnalysts = $('#analysts');
                    data.analystsChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectAnalysts.append(option).trigger('change');
                        selectAnalysts.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    //Analysts
                    var url = '{{ route('globales.get-users') }}';
                    var analysts = $('#analysts').select2({
                        placeholder: 'Seleccione Analistas',
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.name,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                    selectAnalysts.val(data.analystsChecked.map(function(d) {
                        return d.id;
                    })).trigger('change');

                });
            });

            $('#saveBtnPatrimony').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var data = new FormData();
                var form_data = $('#patrimonyForm').serializeArray();
                
                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);  
                });

                //evidenceFile data
                var fileEvidence = $('input[name="evidenceFile"]')[0].files;
                for (var i = 0; i < fileEvidence.length; i++) {
                    data.append("evidenceFile", fileEvidence[i]);
                }

                //mainPhotoFile data
                var mainPhotoFile = $('input[name="mainPhotoFile"]')[0].files;
                for (var i = 0; i < mainPhotoFile.length; i++) {
                    data.append("mainPhotoFile", mainPhotoFile[i]);
                }

                //Append to input "description" data textarea
                data.append('description', patrimonyEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('globales.patrimonies.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.fire(
                            'Excelente!',
                            'Inmueble agregado Satisfactoriamente.',
                            'success'
                        )
                        $('#patrimonyForm').trigger("reset");
                        $('#patrimonyModal').modal('hide');
                        table.draw();

                    },
                    error: function(data) {
                        var obj = data.responseJSON.errors;
                        $.each(obj, function(key, value) {
                            // Alert Toastr
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.error("Atención: " + value);
                        });

                        $('#saveBtnPatrimony').html('Guardar Cambios');
                    }
                });
            });

        });
    </script>
@stop
