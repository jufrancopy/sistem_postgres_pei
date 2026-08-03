@extends('layouts.master')
@section('title', 'Patrimonios')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title">
                <i class="fas fa-landmark"></i> Gestión de Patrimonios - {{ $patrimonyProfile->dependency->dependency }}
            </h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}"><i class="fas fa-home"></i>
                        Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('globales.patrimony-profiles.index') }}"><i class="fas fa-landmark"></i> Patrimonios</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-building"></i> {{ $patrimonyProfile->dependency->dependency }}</li>
            </ol>
        </nav>

        <div class="container-fluid px-4">
            <!-- Tarjetas de Resumen -->
            <div class="row mb-4">
                <!-- Total Patrimonios -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Patrimonios</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">1,248</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-landmark fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inmuebles Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Inmuebles</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inmueblesCount ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipos/Vehículos Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Equipos y Vehículos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $equiposCount ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-laptop-medical fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mantenimiento Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        En Mantenimiento</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tools fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End of row -->

            <!-- Pestañas de Navegación -->
            <ul class="nav nav-pills mb-4" id="main-patrimony-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="true">
                        <i class="fas fa-chart-pie mr-2"></i>Resumen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-realStates-tab" data-toggle="pill" href="#pills-realStates" role="tab" aria-controls="pills-realStates" aria-selected="false">
                        <i class="fas fa-building mr-2"></i>Inmuebles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-equipment-tab" data-toggle="pill" href="#pills-equipment" role="tab" aria-controls="pills-equipment" aria-selected="false">
                        <i class="fas fa-laptop-medical mr-2"></i>Equipos y Vehículos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-maps-tab" data-toggle="pill" href="#pills-maps" role="tab" aria-controls="pills-maps" aria-selected="false">
                        <i class="fas fa-map-marked-alt mr-2"></i>Mapas
                    </a>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="pills-tabContent">
                <!-- Pestaña de Resumen -->
                <div class="tab-pane fade show active" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-chart-line mr-2"></i>Distribución de Patrimonios
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="patrimonyPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Inmuebles
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Equipos Médicos
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Mobiliario
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-warning"></i> Vehículos
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-bell mr-2"></i>Alertas Recientes
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <strong>5 inmuebles</strong> con contratos próximos a vencer
                                    </div>
                                    <div class="alert alert-danger" role="alert">
                                        <i class="fas fa-tools mr-2"></i>
                                        <strong>12 equipos</strong> requieren mantenimiento urgente
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        <strong>3 documentos</strong> de propiedad próximos a expirar
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña de Inmuebles -->
                <div class="tab-pane fade" id="pills-realStates" role="tabpanel" aria-labelledby="pills-realStates-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-building mr-2"></i>Listado de Inmuebles
                                    </h6>
                                    @hasanyrole('Administrador')
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary" id="createNewPatrimony">
                                        <i class="fas fa-plus mr-1"></i> Nuevo Inmueble
                                    </a>
                                    @endhasanyrole
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="realStatesTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Registro/Chapa</th>
                                                    <th>Nombre/Descripción</th>
                                                    <th>Departamento</th>
                                                    <th>Ubicación</th>
                                                    <th>Valor Estimado</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña de Equipos -->
                <div class="tab-pane fade" id="pills-equipment" role="tabpanel" aria-labelledby="pills-equipment-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-car mr-2"></i>Inventario de Equipos y Vehículos
                                    </h6>
                                    @hasanyrole('Administrador')
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary" id="createNewPatrimony2">
                                        <i class="fas fa-plus mr-1"></i> Agregar Bien
                                    </a>
                                    @endhasanyrole
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="equipmentTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Registro/Chapa</th>
                                                    <th>Nombre/Descripción</th>
                                                    <th>Departamento</th>
                                                    <th>Ubicación</th>
                                                    <th>Valor Estimado</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña de Mapa -->
                <div class="tab-pane fade" id="pills-maps" role="tabpanel" aria-labelledby="pills-maps-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-map-marked-alt mr-2"></i>Ubicación de Inmuebles
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="mapPais" style="height: 500px; border-radius: 8px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End tab-content -->
        </div>

        <!-- Modales de Formulario y Detalle -->
        <div class="modal fade" id="patrimonyModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="card-header card-header-info">
                        <h4 class="modal-title" id="modalHeading">Nuevo Patrimonio</h4>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <form id="patrimonyForm" name="patrimonyForm" class="form-horizontal">
                            <div class="alert alert-danger errors" role="alert" style="display:none;"></div>
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
                        <h4 class="modal-title" id="modalDetailHeading">Detalle de Patrimonio</h4>
                    </div>
                    <div class="modal-body">
                        <div class="map" id="map" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('styles')
    <style>
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            background-color: #f8f9fc;
        }

        .card-header-info {
            background: linear-gradient(60deg, #26c6da, #00acc1);
            color: white;
        }

        .chart-pie {
            position: relative;
            height: 15rem;
            width: 100%;
        }

        .nav-pills .nav-link.active {
            background-color: #4e73df;
        }

        .badge {
            font-size: 0.85em;
            font-weight: 600;
            padding: 0.35em 0.65em;
        }

        .bg-success {
            background-color: #1cc88a !important;
        }

        .bg-warning {
            background-color: #f6c23e !important;
        }

        .bg-info {
            background-color: #36b9cc !important;
        }

        .bg-danger {
            background-color: #e74a3b !important;
        }

        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
    </style>
@stop
@section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById("patrimonyPieChart");
        var patrimonyPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Inmuebles", "Equipos Médicos", "Mobiliario", "Vehículos", "Otros"],
                datasets: [{
                    data: [342, 576, 215, 78, 37],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
        // Improved block by ChatGPT
        $('#showDetailPatrimony').click(function() {
            initializeDetailPatrimony();
        });

        $('body').on('click', '#createNewPatrimony, #createNewPatrimony2', function() {
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
            $('#type, #state, #city, #locality, #infrastructureType, #estateStatus, #rentAmountPeriod, #statusDocumentation')
                .select2();
            $('#state, #city, #locality, #infrastructureType, #estateStatus, #rentAmountPeriod, #statusDocumentation')
                .val([]).trigger('change.select2');
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
                    "{{ route('globales.get-root-groups') }}");


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
                        url: "{{ route('globales.get-dependencies') }}",
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
                    var url = "{{ route('globales.get-groups', ':id') }}".replace(':id', groupRootID);

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
                var url = "{{ route('globales.get-users') }}";
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


        // Inicialización de DataTables
        $(document).ready(function() {
            // Inmuebles Table
            $('#realStatesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('globales.patrimonies.detail-profile', $patrimonyProfile->id) }}",
                    data: function (d) {
                        d.type = 'BIEN DE RENTA';
                        d.dependency_id = "{{ $patrimonyProfile->dependency_id ?? '' }}";
                    }
                },
                columns: [
                    { data: 'quantity_account_current', name: 'quantity_account_current', render: function(data) { return data ? data : '-'; } },
                    { data: 'description', name: 'description', render: function(data) { return data ? data : '-'; } },
                    { data: 'department', name: 'department', render: function(data) { return data ? data : '-'; } },
                    { data: 'detail_location', name: 'detail_location', render: function(data) { return data ? data : '-'; } },
                    { data: 'estate_quantity', name: 'estate_quantity', render: function(data) { return data ? data : '-'; } },
                    { data: 'estate_status', name: 'estate_status', render: function(data) { return data ? '<span class="badge badge-info">' + data + '</span>' : '-'; } },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' }
            });
            
            // Equipos y Vehículos Table
            $('#equipmentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('globales.patrimonies.detail-profile', $patrimonyProfile->id) }}",
                    data: function (d) {
                        d.type = 'BIEN DE USO';
                        d.dependency_id = "{{ $patrimonyProfile->dependency_id ?? '' }}";
                    }
                },
                columns: [
                    { data: 'quantity_account_current', name: 'quantity_account_current', render: function(data) { return data ? data : '-'; } },
                    { data: 'description', name: 'description', render: function(data) { return data ? data : '-'; } },
                    { data: 'department', name: 'department', render: function(data) { return data ? data : '-'; } },
                    { data: 'detail_location', name: 'detail_location', render: function(data) { return data ? data : '-'; } },
                    { data: 'estate_quantity', name: 'estate_quantity', render: function(data) { return data ? data : '-'; } },
                    { data: 'estate_status', name: 'estate_status', render: function(data) { return data ? '<span class="badge badge-info">' + data + '</span>' : '-'; } },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' }
            });
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
    </script>
@stop
