@extends('layouts.master')
@section('title', 'Planificación Estratégica')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Módulo de Planificación Estratégica</h4>
        </div>
        <!-- HTML del primer nav -->
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4" id="default-nav">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles</a></li>

                <li class="breadcrumb-item active" aria-current="page">Módulo de Planificación Estratégica</li>
            </ol>
        </nav>

        <!-- HTML del segundo nav (inicialmente oculto) -->
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4" id="dynamic-nav" style="display: none;">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tareas</a></li>
                <li class="breadcrumb-item" id="tasks-show-link-dynamic">Lista de Tareas</li>
                <li class="breadcrumb-item active" aria-current="page">Ambientes</li>
            </ol>
        </nav>

        {{-- Inicio Contenido Principal --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 detailHeader">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>
                                                {{ $profile->name }}
                                            </h2>
                                            <div class="col">
                                                <label><i class="fa fa-calendar" aria-hidden="true"></i> Periodo:
                                                </label>
                                                {{ Carbon\Carbon::parse($profile->year_start)->format('Y') }} -
                                                {{ Carbon\Carbon::parse($profile->year_end)->format('Y') }}
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <label><i class="fa fa-users" aria-hidden="true"></i> Grupos de
                                                        Trabajo:
                                                    </label><br>
                                                    @php
                                                        $totalMembers = 0; // Inicializa el contador de miembros
                                                    @endphp

                                                    @foreach ($profile->group->descendants as $group)
                                                        <span data-toggle="collapse" href="#group_{{ $group->id }}"
                                                            role="button" aria-expanded="false"
                                                            aria-controls="group_{{ $group->id }}"
                                                            class="badge badge-secondary">{{ $group->name }}
                                                        </span>
                                                        <div class="collapse" id="group_{{ $group->id }}">
                                                            <div class="card card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr class="table-success">
                                                                                <th>Nro</th>
                                                                                <th>Participantes</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($group->members as $index => $member)
                                                                                @php
                                                                                    $totalMembers++; // Incrementa el contador de miembros
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>{{ $index + 1 }}</td>
                                                                                    <td>
                                                                                        <span
                                                                                            class="badge badge-secondary">{{ $member->name }}</span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </li>
                                            </ul>

                                            <div class="row border">

                                                <div class="col">
                                                    <label><i class="fa fa-arrows-h" aria-hidden="true"></i> Ejes: </label>
                                                    <a class="btn btn-danger btn-circle text-white btn-circle ml-auto"
                                                        href="javascript:void(0)" data-id="{{ $profile->id }}"
                                                        id="showAxisList">
                                                        {{ $profile->descendants()->where('level', 'axi')->count() }}
                                                    </a>
                                                </div>

                                                <div class="col">
                                                    <label><i class="fa fa-arrows-h" aria-hidden="true"></i> Objetivos:
                                                    </label>
                                                    <a class="btn btn-danger btn-circle text-white btn-circle ml-auto"
                                                        href="javascript:void(0)" data-id="{{ $profile->id }}"
                                                        id="showGoalsList">
                                                        {{ $profile->descendants()->where('level', 'goal')->count() }}
                                                    </a>
                                                </div>

                                                <div class="col">
                                                    <label><i class="fa fa-arrows-h" aria-hidden="true"></i> Acciones:
                                                    </label>
                                                    <a class="btn btn-danger btn-circle text-white btn-circle ml-auto"
                                                        href="javascript:void(0)" data-id="{{ $profile->id }}"
                                                        id="showActionsList">
                                                        {{ $profile->descendants()->where('level', 'action')->count() }}
                                                    </a>
                                                </div>

                                                <div class="col">
                                                    <label><i class="fa fa-user" aria-hidden="true"></i>
                                                        Participantes:
                                                    </label>
                                                    <div class="btn btn-danger btn-circle">{{ $totalMembers }}</div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col mision">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h6 class="mb-0">Misión</h6>
                                            <a class="btn btn-info text-white btn-circle ml-auto" href="javascript:void(0)"
                                                data-type="mision" id="compareHistorical">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>

                                        <div class="card-body">
                                            <div class="mision">{!! $profile->mision !!}</div>
                                        </div>
                                        <div class="card-footer">
                                            <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                                id="createMision">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col vision">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h6 class="mb-0">Visión</h6>
                                            <a class="btn btn-info text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-type="vision" id="compareHistorical">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="vision">{!! $profile->vision !!}</div>
                                        </div>
                                        <div class="card-footer">
                                            <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                                id="createVision">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col values">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h6 class="mb-0">Valores</h6>
                                            <a class="btn btn-info text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-type="values" id="compareHistorical">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="values">{!! $profile->values !!}</div>
                                        </div>
                                        <div class="card-footer">
                                            <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                                id="createValues">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <a class="btn btn-success mb-2 text-white" data-id="{{ $profile->id }}"
                                        data-type="create" href="javascript:void(0)" id="createAxis">
                                        Agregar Ejes
                                    </a>
                                </div>
                            </div>

                            {{-- Inicio Contenido Desplegable (Acordeon) --}}
                            @include('admin.planificacion.peis.peis.accordion')
                            {{-- Fin Contenido Desplegable (Acordeon) --}}

                        </div>
                    </div>
                </div>

                {{-- Star Modals --}}
                @include('admin.planificacion.peis.peis.modals')
                {{-- End Modals --}}

            </div>
        </div>
        {{-- Fin Contenido Principal --}}
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

            // Initilizaton JSTree
            $('#data').jstree({
                'core': {
                    'data': {
                        'url': function(node) {
                            var routeDetailItem = "{!! route('tree-group') !!}";
                            return routeDetailItem;
                        },
                        'data': function(node) {
                            return {
                                'id': node.id
                            };
                        }
                    }
                }
            });

            // Datatables values
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
                ajax: "{{ route('pei-profiles.index') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'group',
                    name: 'group'
                }, {
                    data: 'analysts',
                    name: 'analysts',
                    render: function(data, type, full, meta) {
                        var analystsArray = data.split(', ');

                        var analystsHtml = '';

                        analystsArray.forEach(function(analyst) {
                            analystsHtml += '<span class="badge badge-secondary">' +
                                analyst + '</span> ';
                        });

                        return analystsHtml;
                    }
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }, ]
            });

            // Funtion for initilization Select2
            function initializeSelect2(selector, placeholder, url) {
                selector.val("").select2({
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
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            // Inicialization CKEditor
            var misionEditor;
            ClassicEditor
                .create(document.querySelector('#mision'))
                .then(editor => {
                    misionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });


            var visionEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxVisionModal #visionForm #vision'))
                .then(editor => {
                    visionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });

            var valuesEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxValuesModal #valuesForm #values'))
                .then(editor => {
                    valuesEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })

            var axisEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxAxisModal #axisForm #axis'))
                .then(editor => {
                    axisEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })

            var goalsEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxGoalsModal #goalsForm #goals'))
                .then(editor => {
                    goalsEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })

            var actionsEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxActionsModal #actionsForm #actions'))
                .then(editor => {
                    actionsEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })


            $('body').on('click', '#createMision', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    if (data.profile.mision === null) {
                        $('#modalHeadingMision').html("Defina la Misión");
                    } else {
                        $('#modalHeadingMision').html("Editar la Definición de Misión");
                    }
                    $('#saveBtnMision').val("edit-mision");
                    $('#ajaxMisionModal').modal('show');
                    $('#misionForm').trigger("reset");
                    $('#profile_id').val(data.profile.id);
                    $('#name').val(data.profile.name);
                    $('#year_start').val(data.profile.year_start);
                    $('#year_end').val(data.profile.year_end);
                    $('#type').val(data.profile.type);
                    $('#level').val(data.profile.level);
                    $('#group_id').val(data.profile.group_id);
                    $('#values').val(data.profile.values);
                    $('#vision').val(data.profile.vision);
                    $('#dependencies').val(data.profile.dependency_id);

                    //Prevalues Analyst - Input Hidden
                    $('.mision_analysts #mision_analysts').empty()
                    $('.mision_analysts #mision_analysts').select2()
                    var selectAnalysts = $('.mision_analysts #mision_analysts');
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

                    if (data.profile.type === 'corporative') {
                        $('#group_id').attr('name', 'group_root_id')
                    } else {
                        $('#group_id').attr('name', 'group_id')
                    }
                    if (data.profile.mision) {
                        misionEditor.setData(data.profile.mision);
                    }

                });
            });

            $('body').on('click', '#createVision', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    if (data.profile.mision === null) {
                        $('#modalHeadingVision').html("Crear la Definición de Visión");
                    } else {
                        $('#modalHeadingVision').html("Editar la Definición de Visión");
                    }
                    $('#saveBtn').val("edit-profile");
                    $('#ajaxVisionModal').modal('show');
                    $('#visionForm').trigger("reset");
                    $('#vision_profile_id').val(data.profile.id);
                    $('#vision_name').val(data.profile.name);
                    $('#vision_year_start').val(data.profile.year_start);
                    $('#vision_year_end').val(data.profile.year_end);
                    $('#vision_type').val(data.profile.type);
                    $('#vision_level').val(data.profile.level);
                    $('#vision_group_id').val(data.profile.group_id);
                    $('#vision_values').val(data.profile.values);
                    $('#vision_mision').val(data.profile.mision);
                    $('#vision_vision').val(data.profile.vision);
                    $('#vision_dependencies').val(data.profile.dependency_id);

                    //Prevalues Analyst - Input Hidden
                    $('.vision_analysts #vision_analysts').empty()
                    $('.vision_analysts #vision_analysts').select2()
                    var selectAnalysts = $('.vision_analysts #vision_analysts');
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

                    if (data.profile.type === 'corporative') {
                        $('#vision_group_id').attr('name', 'group_root_id')
                    } else {
                        $('#vision_group_id').attr('name', 'group_id')
                    }
                    if (data.profile.vision) {
                        visionEditor.setData(data.profile.vision);
                    }
                });
            });

            $('body').on('click', '#createValues', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    if (data.profile.mision === null) {
                        $('#modalHeadingValues').html("Crear la Definición de Valores");
                    } else {
                        $('#modalHeadingValues').html("Editar la Definición de Valores");
                    }
                    $('#saveBtnValues').val("edit-values");
                    $('#ajaxValuesModal').modal('show');
                    $('#valuesForm').trigger("reset");
                    $('#values_profile_id').val(data.profile.id);
                    $('#values_name').val(data.profile.name);
                    $('#values_year_start').val(data.profile.year_start);
                    $('#values_year_end').val(data.profile.year_end);
                    $('#values_type').val(data.profile.type);
                    $('#values_level').val(data.profile.level);
                    $('#values_group_id').val(data.profile.group_id);
                    $('#values_mision').val(data.profile.mision);
                    $('#values_vision').val(data.profile.vision);
                    $('#values_values').val(data.profile.values);
                    $('#values_dependencies').val(data.profile.dependency_id);

                    //Prevalues Analyst - Input Hidden
                    $('.values_analysts #values_analysts').empty()
                    $('.values_analysts #values_analysts').select2()
                    var selectAnalysts = $('.values_analysts #values_analysts');
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

                    if (data.profile.type === 'corporative') {
                        $('#values_group_id').attr('name', 'group_root_id')
                    } else {
                        $('#values_group_id').attr('name', 'group_id')
                    }
                    if (data.profile.values) {
                        valuesEditor.setData(data.profile.values);
                    }
                });
            });

            $('body').on('click', '#createAxis', function() {
                var profileID = $(this).data('id');
                var typeBtn = $(this).data('type');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeadingAxis').html(typeBtn === 'create' ? "Crear Eje" : "Editar Eje");
                    $('#saveBtnAxis').val(typeBtn === 'create' ? "create" : "edit");
                    $('#ajaxAxisModal').modal('show');
                    $('#axisForm').trigger("reset");

                    if (typeBtn === 'create') {
                        $('#axis_parent_id').val(data.profile.id);
                        axisEditor.setData('');
                        $('#axis_order_item').val('');

                    } else if (typeBtn === 'edit') {
                        $('#axis_profile_id').val(data.profile.id);
                        $('#axis_parent_id').val(data.profile.parent_id);
                        axisEditor.setData(data.profile.name)
                        $('#axis_order_item').val(data.profile.order_item);
                    }

                    $('#axis_type').val(data.profile.type);
                    $('#axis_group_id').val(data.profile.group_id);
                    $('#axis_dependency').val(data.profile.dependency_id);


                });
            });

            $('body').on('click', '#createGoals', function() {
                var profileID = $(this).data('id');
                var typeBtn = $(this).data('type');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeadingGoals').html(typeBtn === 'create' ? "Crear Objetivo" :
                        "Editar Objetivo");
                    $('#saveBtnGoals').val(typeBtn === 'create' ? "create" : "edit");
                    $('#ajaxGoalsModal').modal('show');
                    $('#goalsForm').trigger("reset");

                    if (typeBtn === 'create') {
                        $('#goals_parent_id').val(data.profile.id);
                        $('#goals_order_item').val('');
                        goalsEditor.setData('');

                    } else if (typeBtn === 'edit') {
                        $('#goals_profile_id').val(data.profile.id);
                        $('#goals_parent_id').val(data.profile.parent_id);
                        goalsEditor.setData(data.profile.name)
                        $('#goals_order_item').val(data.profile.order_item);
                    }

                    $('#goals_type').val(data.profile.type);
                    $('#goals_group_id').val(data.profile.group_id);
                    $('#goals_dependency').val(data.profile.dependency_id);


                    //Straetegis from crossing
                    var url = '{{ route('get-crossings') }}';

                    var selectStrategies = $('#strategies').select2();
                    selectStrategies.empty();
                    data.strategiesChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectStrategies.append(option).trigger('change');
                        selectStrategies.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    $('#strategies').select2({
                        allowClear: true,
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                console.log(data)
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.estrategia,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                });
            });

            $('body').on('click', '#createActions', function() {
                var profileID = $(this).data('id');
                var typeBtn = $(this).data('type');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeadingActions').html(typeBtn === 'create' ? "Crear Acción" :
                        "Editar Acción");
                    $('#saveBtnGoals').val(typeBtn === 'create' ? "create" : "edit");
                    $('#ajaxActionsModal').modal('show');
                    $('#actionsForm').trigger("reset");

                    if (typeBtn === 'create') {
                        $('#actions_parent_id').val(data.profile.id);
                        actionsEditor.setData('');
                        $('#actions_order_item').val('');
                        $('#saveBtnActions').val('create');

                    } else if (typeBtn === 'edit') {
                        $('#actions_profile_id').val(data.profile.id);
                        $('#actions_parent_id').val(data.profile.parent_id);
                        actionsEditor.setData(data.profile.name)
                        $('#actions_order_item').val(data.profile.order_item);
                        $('#saveBtnActions').val('edit');
                    }

                    $('#actions_type').val(data.profile.type);
                    $('#actions_group_id').val(data.profile.group_id);
                    $('#actions_indicator').val(data.profile.indicator);
                    $('#actions_baseline').val(data.profile.baseline);
                    $('#actions_target').val(data.profile.target);
                    $('#actions_dependency').val(data.profile.dependency_id);

                    var selectResponsibles = $('#responsibles').select2();
                    selectResponsibles.empty();
                    data.responsiblesChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectResponsibles.append(option).trigger('change');
                        selectResponsibles.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    var url = '/admin/globales/get-dependencies/' + data.profile.dependency_id;
                    $('#responsibles').select2({
                        allowClear: true,
                        ajax: {
                            url: url,
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


                });
            });

            $('body').on('click', '#showStrategies', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID, function(data) {
                    $('#modalHeadingStrategies').html(
                        'Estrategias del Cruce de Ambientes ( Análisis FODA)');
                    $('#ajaxStrategiesModal').modal('show');

                    var strategies = data.profile.strategies;
                    var tableBody = $('#strategiesList .table tbody');

                    tableBody.empty(); // Limpiar el contenido de la tabla

                    // Iterar sobre las estrategias y agregarlas a la tabla
                    strategies.forEach(function(strategy) {
                        var row = $('<tr>');
                        row.append($('<td>').text(strategy.estrategia));
                        // Condición para mostrar el tipo de estrategia como Fortaleza u Oportunidad
                        if (strategy.tipo === 'FO') {
                            row.append($('<td>').html(
                                '<span class="badge badge-success">Fortaleza</span> vs <span class="badge badge-success">Oportunidad</span>'
                            ));
                        } else if (strategy.tipo === 'DO') {
                            row.append($('<td>').html(
                                '<span class="badge badge-danger">Debilidad</span> vs <span class="badge badge-success">Oportunidad</span>'
                            ));
                        } else if (strategy.tipo === 'FA') {
                            row.append($('<td>').html(
                                '<span class="badge badge-success">Fortaleza</span> vs <span class="badge badge-danger">Amenaza</span>'
                            ));
                        } else if (strategy.tipo === 'DA') {
                            row.append($('<td>').html(
                                '<span class="badge badge-danger">Debilidad</span> vs <span class="badge badge-danger">Amenaza</span>'
                            ));
                        } else {
                            row.append($('<td>').text(strategy.tipo));
                        }
                        tableBody.append(row);
                    });



                });
            });

            $('body').on('click', '#compareHistorical', function() {
                var typeBtn = $(this).data('type');

                url = '{{ route('pei-profiles-compareHistorical') }}'
                $.get(url, function(data) {
                    console.log(data)
                    $('#modalHeadingHistorical').html(
                        'Histórico de Definición de Misión - Grupales');
                    $('#ajaxHistoricalModal').modal('show');
                    compareList

                    var tableBody = $('#compareList .table tbody');
                    tableBody.empty(); // Limpiar el contenido de la tabla

                    // Itera sobre los datos y agrega filas a la tabla
                    data.forEach(function(row) {
                        var newRow = $('<tr>');
                        newRow.append($('<td>').text(row.group
                            .name)); // Cambia row.group al campo correcto

                        if (typeBtn === 'mision') {
                            newRow.append($('<td>').html(row
                                .mision)); // Cambia row.mision al campo correcto
                        } else if (typeBtn === 'vision') {
                            newRow.append($('<td>').html(row
                                .vision)); // Cambia row.vision al campo correcto
                        } else if (typeBtn === 'values') {
                            newRow.append($('<td>').html(row
                                .values)); // Cambia row.vision al campo correcto
                        }


                        tableBody.append(newRow);
                    });


                });
            });

            $('body').on('click', '#showAxisList', function() {

                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/axis-list',
                    function(data) {
                        $('#modalHeadingAxisList').html(
                            'Lista de Ejes');
                        $('#ajaxAxisListlModal').modal('show');
                        compareList

                        var tableBody = $('#axisList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.axis.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));


                            tableBody.append(newRow);
                        });


                    });
            });

            $('body').on('click', '#showGoalsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/goals-list',
                    function(data) {
                        $('#modalHeadingGoalsList').html(
                            'Lista de Objetivos');
                        $('#ajaxGoalsListModal').modal('show');
                        compareList

                        var tableBody = $('#goalsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.goals.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));

                            // Crear una celda para mostrar todas las estrategias
                            var strategiesCell = $('<td>');

                            row.strategies.forEach(function(strategy) {
                                // Agregar cada estrategia a la celda
                                strategiesCell.append(strategy.estrategia + '<br>');
                            });

                            newRow.append(strategiesCell);
                            tableBody.append(newRow);
                        });



                    });
            });

            $('body').on('click', '#showActionsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/actions-list',
                    function(data) {
                        console.log(data)
                        $('#modalHeadingActionsList').html(
                            'Lista de Acciones');
                        $('#ajaxActionsListModal').modal('show');

                        var tableBody = $('#actionsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.actions.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));
                            newRow.append($('<td>').html(row.indicator));
                            newRow.append($('<td>').html(row.baseline));
                            newRow.append($('<td>').html(row.target));

                            // Crear una celda para mostrar todas las estrategias
                            var responsaiblesCell = $('<td>');

                            row.responsibles.forEach(function(responsible) {
                                // Agregar cada estrategia a la celda
                                responsaiblesCell.append(responsible.dependency +
                                    '<br>');
                            });

                            newRow.append(responsaiblesCell);
                            tableBody.append(newRow);
                        });



                    });
            });

            $('body').on('click', '#showParticipantsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/actions-list',
                    function(data) {
                        console.log(data)
                        $('#modalHeadingActionsList').html(
                            'Lista de Acciones');
                        $('#ajaxActionsListModal').modal('show');

                        var tableBody = $('#actionsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.actions.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));
                            newRow.append($('<td>').html(row.indicator));
                            newRow.append($('<td>').html(row.baseline));
                            newRow.append($('<td>').html(row.target));

                            // Crear una celda para mostrar todas las estrategias
                            var responsaiblesCell = $('<td>');

                            row.responsibles.forEach(function(responsible) {
                                // Agregar cada estrategia a la celda
                                responsaiblesCell.append(responsible.dependency +
                                    '<br>');
                            });

                            newRow.append(responsaiblesCell);
                            tableBody.append(newRow);
                        });



                    });
            });

            $(document).ready(function() {
                $('.quantitative, .qualitative').hide();

                // Controlador de evento para el botón #reportProgress
                $('#insertCheckbox').click(function() {
                    var description = $('#description').val().trim();
                    var value = $('#value').val().trim();
                    var color = $('#color').val();

                    // Validar que se hayan ingresado los datos requeridos
                    if (description === '' || value === '') {
                        alert('Por favor ingresa una descripción y un valor.');
                        return;
                    }
                    var checkboxHTML = `
                    <div class="form-check">
                        <div class="input-group">
                            <input type="hidden" id="checkbox${Date.now()}" name="parameters[]" value="${description} (${value}%) ${color}" readonly class="form-control text-white ${color}" /> 
                            <label class="badge ${color}">${description} (${value}%)</label>
                            <div class="input-group-append">
                                <a href="#" class="delete-checkbox input-group-text"><i class="fa fa-trash text-danger"></i></a>
                            </div>
                        </div>
                    </div>`;

                    // Evento click para eliminar el checkbox
                    $('body').on('click', '.delete-checkbox', function(e) {
                        e
                            .preventDefault(); // Evitar que el enlace actúe como un enlace normal

                        // Obtener el contenedor del checkbox y eliminarlo
                        $(this).closest('.form-check').remove();
                    });

                    // Agregar el checkbox al contenedor
                    $('#checkboxContainer').append(checkboxHTML);

                    // Limpiar los campos después de insertar el checkbox
                    $('#description').val('');
                    $('#value').val('');
                    $('#color').val('bg-danger'); // Reiniciar el color al predeterminado
                });

                $('body').on('click', '#reportProgress', function() {
                    var profileID = $(this).data('id');

                    $.get("{{ route('pei-profiles.index') }}" + '/' + profileID +
                        '/report-progress',
                        function(data) {
                            $('#modalReportProgress').html(
                                'Definición de Criterios de Evaluación');
                            $('#ajaxDefineCriteriaModal').modal('show');

                            function formatNumber(num) {
                                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }

                            // Llenar los campos del formulario con los datos recibidos
                            $('#progress_profile_id').val(data.profile.id);
                            $('#progress_action').val(data.profile.name);
                            $('#progress_group_id').val(data.profile.group_id);
                            $('#progress_mision').val(data.profile.mision);
                            $('#progress_indicator').val(data.profile.indicator);
                            $('#progress_vision').val(data.profile.vision);
                            $('#progress_type').val(data.profile.progress_type);
                            $('#progress_level').val(data.profile.level);
                            $('#progress_period').val(data.profile.period);
                            $('#progress_denominator').val(formatNumber(Math.round(data.profile
                                .denominator)));
                            $('#progress_numerator').val(data.profile.numerator);
                            $('#progress_goal').val(data.profile.goal);
                            $('#progress_baseline').val(data.profile.baseline);
                            $('#progress_target').val(data.profile.target);
                            $('#progress_dependency').val(data.profile.dependency_id);
                            $('#progress_parent_id').val(data.profile.parent_id);
                            $('#progress_order_item').val(data.profile.order_item);

                            // Mostrar responsables en el contenedor
                            var responsablesContainer = $('#responsiblesContainer');
                            responsablesContainer.empty();
                            data.responsiblesChecked.forEach(function(item) {
                                var badge = $('<span></span>')
                                    .addClass('badge badge-primary')
                                    .text(item.text);
                                responsablesContainer.append(badge);
                            });

                            // Llenar los campos del formulario con los datos recibidos
                            $('#progress_profile_id').val(data.profile.id);
                            // $('#progress_parent_id').val(data.profile.parent_id);

                            $('#progress_report_type').val(null).trigger('change');

                            $('#progress_report_type').select2({
                                placeholder: 'Seleccione el Tipo de Reporte'
                            });

                            var responsiblesContainer = $('#responsiblesContainer')
                            responsiblesContainer.empty();
                            data.responsiblesChecked.forEach(function(item) {
                                var badge = $('<span></span>')
                                    .addClass(
                                        'badge badge-primary'
                                    ) // Clase Bootstrap para badge
                                    .text(item.text); // Texto del badge
                                responsiblesContainer.append(badge);
                            });

                            var selectResponsibles = $('#progress_responsibles').select2();
                            selectResponsibles.empty();
                            data.responsiblesChecked.forEach(function(d) {
                                var option = new Option(d.text, d.id, true, true);
                                selectResponsibles.append(option).trigger('change');
                                selectResponsibles.trigger({
                                    type: 'select2:select',
                                    params: {
                                        data: data
                                    }
                                });
                            });

                            var url = '/admin/globales/get-dependencies/' + data.profile
                                .dependency_id;

                            $('#progress_responsibles').select2({
                                allowClear: true,
                                ajax: {
                                    url: url,
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

                            $('.progress_responsibles').hide()

                            $('#color').select2({
                                placeholder: 'Seleccione el Color'
                            });

                            if (data.profile) {
                                var reportType = data.profile.report_type;
                                $('#progress_report_type').val(reportType).trigger('change');
                                // Mostrar el div correspondiente basado en el tipo de reporte
                                if (reportType === 'quantitative') {
                                    $('.quantitative').show();
                                    $('.qualitative').hide();
                                } else if (reportType === 'qualitative') {
                                    $('.quantitative').hide();
                                    $('.qualitative').show();
                                }

                                // Procesar el valor de parameters
                                var parameters = JSON.parse(data.profile.parameters);
                                parameters.forEach(function(param) {
                                    var parts = param.split(' ');
                                    var value = parts.slice(0, -1).join(
                                        ' '); // Extraer el valor
                                    console.log("🚀 ~ parameters.forEach ~ value:",
                                        value)
                                    var color = parts.slice(-1)[0]; // Extraer el color

                                    var checkboxHTML = `
                                    <div class="form-check">
                                        <div class="input-group">
                                        <input type="hidden" id="checkbox${Date.now()}" name="parameters[]" value="${value} (${value}%) ${color}" checked/> 
                                        <label class="badge ${color} mr-2">${value}</label>
                                        <a href="#" class="delete-checkbox"><i class="fa fa-trash text-danger"></i></a>
                                        </div>
                                    </div>`;

                                    // Evento click para eliminar el checkbox
                                    $('body').on('click', '.delete-checkbox', function(
                                        e) {
                                        e
                                            .preventDefault(); // Evitar que el enlace actúe como un enlace normal

                                        // Obtener el contenedor del checkbox y eliminarlo
                                        $(this).closest('.form-check').remove();
                                    });

                                    // Agregar el checkbox al contenedor
                                    $('#checkboxContainer').append(checkboxHTML);

                                });

                            }

                            // Extraer el texto del contenido HTML
                            var tempDiv = document.createElement("div");
                            tempDiv.innerHTML = data.profile.name;
                            var plainText = tempDiv.textContent || tempDiv.innerText || "";

                            // Mostrar el texto limpio en el campo de texto
                            $('#progress_action').val(plainText);
                            $('#progress_group_id').val(data.profile.group_id);
                            $('#progress_mision').val(data.profile.mision);
                            $('#progress_indicator').val(data.profile.indicator);
                            $('#progress_vision').val(data.profile.vision);
                            $('#progress_type').val(data.profile.progress_type);
                            $('#progress_level').val(data.profile.level);
                            $('#progress_period').val(data.profile.period);
                            $('#progress_denominator').val(data.profile.denominator);
                            $('#progress_numerator').val(data.profile.numerator);
                            $('#progress_goal').val(data.profile.goal);
                            $('#progress_baseline').val(data.profile.baseline);
                            $('#progress_target').val(data.profile.target);
                            $('#progress_dependency').val(data.profile.dependency_id);
                            $('#progress_parent_id').val(data.profile.parent_id);
                            $('#progress_order_item').val(data.profile.order_item);
                            console.log('Denominador: ', data.profile.denominator)

                        }).fail(function() {
                        alert('Error al cargar los datos del perfil.');
                    });



                });
                // Controlador de evento para la calculadora
                $('.calculator').on('click', 'button', function() {
                    var num1 = parseFloat($('#progress_numerator').val());
                    var num2 = parseFloat($('#progress_denominator').val());

                    // Obtener la operación a realizar desde el atributo data-operation del botón clickeado
                    var operation = $(this).data('operation');
                    var result = 0;

                    // Realizar la operación matemática correspondiente
                    switch (operation) {
                        case 'add':
                            result = num1 + num2;
                            break;
                        case 'subtract':
                            result = num1 - num2;
                            break;
                        case 'multiply':
                            result = num1 * num2;
                            break;
                        case 'divide':
                            if (num2 !== 0) {
                                result = num1 / num2;
                            } else {
                                alert('No se puede dividir por cero.');
                            }
                            break;
                        case 'percentage':
                            var porcentaje = (num1 / num2) * 100; // Calcula el porcentaje

                            // Redondear el resultado si no es entero
                            if (!Number.isInteger(porcentaje)) {
                                porcentaje = Math.round(
                                    porcentaje); // Redondea el resultado a entero
                            }
                            result = porcentaje; // Agrega '%' al final
                            break;
                        default:
                            result = 0;
                            break;
                    }


                    // Mostrar el resultado en el campo de texto de resultado
                    $('#progress_progress').val(result);
                });

                $('#progress_report_type').change(function() {
                    var selectedOption = $(this).val();
                    // Ocultar todos los divs
                    $('.quantitative, .qualitative').hide();

                    // Mostrar el div correspondiente al tipo seleccionado
                    if (selectedOption === 'quantitative') {
                        $('.quantitative').show();
                    } else if (selectedOption === 'qualitative') {
                        $('.qualitative').show();
                    }
                });
            });

            $('#saveBtnMision').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var data = new FormData();
                var form_data = $('#misionForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('mision', misionEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data.profile.mision)
                        Swal.fire(
                            'Excelente!',
                            'Has Agregado una Misión.',
                            'success'
                        )

                        //Actaulizacmos La mision en el DOM
                        $('.mision .card-body').html(data.profile.mision);

                        $('#misionForm').trigger("reset");
                        $('#ajaxMisionModal').modal('hide');

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

                        $('#saveBtnMision').html('Guardar Cambios');
                    }
                });
            });

            $('#saveBtnVision').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var data = new FormData();
                var form_data = $('#visionForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('vision', visionEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.fire(
                            'Excelente!',
                            'Has Agregado una Visión.',
                            'success'
                        )

                        //Actaulizacmos La mision en el DOM
                        $('.vision .card-body').html(data.profile.vision);

                        $('#visionForm').trigger("reset");
                        $('#ajaxVisionModal').modal('hide');
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
                        $('#saveBtnVision').html('Guardar Cambios');
                    }

                });
            });

            $('#saveBtnValues').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var data = new FormData();
                var form_data = $('#valuesForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('values', valuesEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.fire(
                            'Excelente!',
                            'Has Agregado Valores.',
                            'success'
                        )

                        //Actaulizacmos La mision en el DOM
                        $('.values .card-body').html(data.profile.values);

                        $('#valuesForm').trigger("reset");
                        $('#ajaxValuesModal').modal('hide');
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
                        $('#saveBtnVision').html('Guardar Cambios');
                    }

                });
            });

            $('#saveBtnAxis').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var saveBtnValue = $(this).val();

                var data = new FormData();
                var form_data = $('#axisForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('name', axisEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.fire(
                            'Excelente!',
                            'Has Agregado un Nuevo Eje.',
                            'success'
                        )

                        $('#axisForm').trigger("reset");
                        $('#ajaxAxisModal').modal('hide');

                        var axisId = data.profile.id;
                        var axisParentId = data.profile.parent_id;
                        var axisName = data.profile.name;
                        var accordionAxiID = data.profile.id

                        if (saveBtnValue === "create") {
                            var newAxisElement = '<div class="col-12 mb-3">' +
                                '<div class="card bg-primary">' +
                                '<div class="card-header bg-light" id="headingAxi_' + axisId +
                                '">' +
                                '<h2 class="mb-0" id="axisBlock_' + axisId + '">' +
                                '<div class="d-flex justify-content-between">' +
                                '<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#' +
                                axisId + '" aria-expanded="false" aria-controls="' + axisId +
                                '" style="max-height: 100px; overflow-y: auto; white-space: pre-line; text-align: left; font-weight: bold;">' +
                                axisName +
                                '</button>' +
                                '<a class="btn btn-success text-white btn-circle createAxis" data-id="' +
                                axisId +
                                '" data-type="edit" href="javascript:void(0)" id="createAxis"> <i class="fa fa-edit"></i>' +
                                '</a>' +
                                '<a class="btn btn-info text-white btn-circle createGoalsButton" data-id="' +
                                axisId +
                                '" data-type="create" href="javascript:void(0)" id="createGoals"> <i class="fa-solid fa-circle-plus fa-beat"></i>' +
                                '</a>' +
                                '<a class="btn btn-danger text-white btn-circle deleteItem" data-id="' +
                                axisId +
                                '" href="javascript:void(0)" id="deleteItem"> <i class="fa fa-trash"></i>' +
                                '</a>' +
                                '</div>' +
                                '</h2>' +
                                '</div>' +
                                '<div id="' + axisId +
                                '" class="collapse" aria-labelledby="headingAxi_' + axisId +
                                '" data-parent="#accordionGoal_' + axisId + '">' +
                                '<div class="row contentGoals">' +
                                '<div class="col-12">' +
                                '<div class="card-body">' +
                                '<div id="accordionGoal_' + axisId + '">' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            // Agrega el nuevo elemento al primer nivel de contentMain
                            $('.contentMain').prepend(
                                newAxisElement);

                        } else if (saveBtnValue === "edit") {

                            $('#headingAxi_' + axisId + ' button').html(axisName);

                        }

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
                        $('#saveBtnAxis').html('Guardar Cambios');
                    }

                });
            });

            $('#saveBtnGoals').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var saveBtnValue = $(this).val();

                var data = new FormData();
                var form_data = $('#goalsForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('name', goalsEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.fire(
                            'Excelente!',
                            'Has Agregado un nuevo Objetivo.',
                            'success'
                        )
                        var parentId = data.profile.parent_id;
                        var goalsId = data.profile.id;
                        var goalsName = data.profile.name;
                        var accordionGoalID = data.profile.id
                        if (saveBtnValue === "create") {
                            var newGoalsElement = '<div class="container">' +
                                '<div class="card">' +
                                '<div class="card-header bg-light" id="headingAxi">' +
                                '<h2 class="mb-0" id="goalsBlock_' + goalsId + '">' +
                                '<div class="d-flex justify-content-between">' +
                                '<button class="btn btn-link btn-block text-left collapsed" ' +
                                'type="button" data-toggle="collapse" ' +
                                'data-target="#' + goalsId + '" aria-expanded="false" ' +
                                'aria-controls="' + goalsId +
                                '" style="max-height: 100px; overflow-y: auto; white-space: pre-line; text-align: left; font-weight: bold;">' +
                                goalsName +
                                '</button>' +
                                '<a class="btn btn-warning text-white btn-circle showStrategies" ' +
                                'data-id="' + goalsId +
                                '" data-type="edit" href="javascript:void(0)" ' +
                                'id="showStrategies"> <i class="fa fa-eye"></i>' +
                                '</a>' +
                                '<a class="btn btn-success text-white btn-circle createGoals" ' +
                                'data-id="' + goalsId +
                                '" data-type="edit" href="javascript:void(0)" ' +
                                'id="createGoals"> <i class="fa fa-edit"></i>' +
                                '</a>' +
                                '<a class="btn btn-info text-white btn-circle createActionsButton" ' +
                                'data-id="' + goalsId +
                                '" data-type="create" href="javascript:void(0)" ' +
                                'id="createActions"> <i class="fa-solid fa-circle-plus fa-beat"></i>' +
                                '</a>' +
                                '<a class="btn btn-danger text-white btn-circle deleteItem" ' +
                                'data-id="' + goalsId + '" href="javascript:void(0)" ' +
                                'id="deleteItem"> <i class="fa fa-trash"></i>' +
                                '</a>' +
                                '</div>' +
                                '</h2>' +
                                '</div>' +
                                '<div id="' + goalsId + '" class="collapse" ' +
                                'aria-labelledby="headingGoal_' + goalsId + '" ' +
                                'data-parent="#accordionGoal_' + goalsId + '">' +
                                '<div class="row contentActions">' +
                                '<div class="card-body">';
                            $('.contentGoals #accordionGoal_' + parentId).prepend(
                                newGoalsElement);

                        } else if (saveBtnValue === "edit") {
                            $('#headingGoal_' + goalsId + ' button').html(goalsName);
                        }
                        $('#goalsForm').trigger("reset");
                        $('#ajaxGoalsModal').modal('hide');
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
                        $('#saveBtnVision').html('Guardar Cambios');
                    }

                });
            });

            $('#saveBtnActions').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var saveBtnValue = $(this).val();
                var data = new FormData();
                var form_data = $('#actionsForm').serializeArray();

                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                data.append('name', actionsEditor.getData());

                $.ajax({
                    data: data,
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        Swal.fire(
                            'Excelente!',
                            'Has Agregado una nueva Acción.',
                            'success'
                        )

                        var parentId = data.profile.parent_id;
                        var actionsId = data.profile.id;
                        var actionsName = data.profile.name;

                        var goalId = data.profile
                            .id; // Supongo que contiene el ID del objetivo al que deseas agregar o actualizar acciones

                        if (saveBtnValue === "create") {
                            // Crear una nueva fila de acción (action) y agregarla a la tabla existente
                            var newRowHtml = `
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <div class="card-body" id="actionsBlock_${ actionsId }">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Nro.</th>
                                                            <th>Acción</th>
                                                            <th>Indicador</th>
                                                            <th>Línea de Base</th>
                                                            <th>Meta</th>
                                                            <th>Responsable</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>                                  
                                                    <tbody>
                                                        <tr>
                                                            <td>${data.profile.order_item}</td>
                                                            <td>${data.profile.name}</td>
                                                            <td>${data.profile.indicator}</td>
                                                            <td>${data.profile.baseline}</td>
                                                            <td>${data.profile.target}</td>
                                                            <td>${data.profile.responsibles.map(responsible => `<span class="badge badge-secondary">${responsible.dependency}</span>`).join(', ')}</td>
                                                            <td>
                                                                <a class="btn btn-success text-white btn-circle" data-id="${data.profile.id}" data-type="edit" href="javascript:void(0)" id="createActions">
                                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                                </a>
                                                                <a class="btn btn-danger text-white btn-circle deleteItem" data-id="${data.profile.id}" href="javascript:void(0)" id="deleteProfile">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </h5>
                                </div>
                            </div>`;

                            // Agregar la nueva fila al final de la tabla del objetivo correspondiente
                            $(`.actionDetail #actionDetail`).append(
                                newRowHtml);
                        } else if (saveBtnValue === "edit") {
                            // Actualiza una fila de acción existente
                            var updatedHtml = `
                                <td>${data.profile.order_item}</td>
                                <td>${data.profile.name}</td>
                                <td>${data.profile.indicator}</td>
                                <td>${data.profile.baseline}</td>
                                <td>${data.profile.target}</td>
                                <td>${data.profile.responsibles.map(responsible => `<span class="badge badge-secondary">${responsible.dependency}</span>`).join(', ')}</td>
                                <td>
                                    <a class="btn btn-success text-white btn-circle" data-id="${data.profile.id}" data-type="edit" href="javascript:void(0)" id="createActions">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-danger text-white btn-circle deleteItem" data-id="${data.profile.id}" href="javascript:void(0)" id="deleteProfile">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>                            
                        `;
                            $(`#actionsBlock_${actionsId} table tbody tr`).html(updatedHtml);


                        }

                        $('#actionsForm').trigger("reset");
                        $('#ajaxActionsModal').modal('hide');
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
                        $('#saveBtnVision').html('Guardar Cambios');
                    }

                });
            });

            $('#saveBtnMonitoringType').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');

                var saveBtnValue = $(this).val();

                var data = new FormData();
                var form_data = $('#monitoringType').serializeArray();
                $.each(form_data, function(key, input) {
                    data.append(input.name, input.value);
                });

                $.ajax({
                    data: data,
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        Swal.fire(
                            'Excelente!',
                            'Has Agregado una nueva Acción.',
                            'success'
                        )

                        var parentId = data.profile.parent_id;
                        var actionsId = data.profile.id;
                        var actionsName = data.profile.name;

                        var goalId = data.profile
                            .id; // Supongo que contiene el ID del objetivo al que deseas agregar o actualizar acciones

                        if (saveBtnValue === "create") {
                            // Crear una nueva fila de acción (action) y agregarla a la tabla existente
                            var newRowHtml = `
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <div class="card-body" id="actionsBlock_${ actionsId }">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Nro.</th>
                                                            <th>Acción</th>
                                                            <th>Indicador</th>
                                                            <th>Línea de Base</th>
                                                            <th>Meta</th>
                                                            <th>Responsable</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>                                  
                                                    <tbody>
                                                        <tr>
                                                            <td>${data.profile.order_item}</td>
                                                            <td>${data.profile.name}</td>
                                                            <td>${data.profile.indicator}</td>
                                                            <td>${data.profile.baseline}</td>
                                                            <td>${data.profile.target}</td>
                                                            <td>${data.profile.responsibles.map(responsible => `<span class="badge badge-secondary">${responsible.dependency}</span>`).join(', ')}</td>
                                                            <td>
                                                                <a class="btn btn-success text-white btn-circle" data-id="${data.profile.id}" data-type="edit" href="javascript:void(0)" id="createActions">
                                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                                </a>
                                                                <a class="btn btn-danger text-white btn-circle deleteItem" data-id="${data.profile.id}" href="javascript:void(0)" id="deleteProfile">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </h5>
                                </div>
                            </div>`;

                            // Agregar la nueva fila al final de la tabla del objetivo correspondiente
                            $(`.actionDetail #actionDetail`).append(
                                newRowHtml);
                        } else if (saveBtnValue === "edit") {
                            // Actualiza una fila de acción existente
                            var updatedHtml = `
                                <td>${data.profile.order_item}</td>
                                <td>${data.profile.name}</td>
                                <td>${data.profile.indicator}</td>
                                <td>${data.profile.baseline}</td>
                                <td>${data.profile.target}</td>
                                <td>${data.profile.responsibles.map(responsible => `<span class="badge badge-secondary">${responsible.dependency}</span>`).join(', ')}</td>
                                <td>
                                    <a class="btn btn-success text-white btn-circle" data-id="${data.profile.id}" data-type="edit" href="javascript:void(0)" id="createActions">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-danger text-white btn-circle deleteItem" data-id="${data.profile.id}" href="javascript:void(0)" id="deleteProfile">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>                            
                        `;
                            $(`#actionsBlock_${actionsId} table tbody tr`).html(updatedHtml);
                        }

                        $('#actionsForm').trigger("reset");
                        $('#ajaxActionsModal').modal('hide');
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
                        $('#saveBtnVision').html('Guardar Cambios');
                    }

                });
            });

            // Agregar un controlador de eventos para el botón de eliminación
            $('.contentMain').on('click', '.deleteItem', function() {
                var axisId = $(this).data('id');

                // Muestra una confirmación en SweetAlert
                Swal.fire({
                    title: '¿Estás seguro de eliminarlo?',
                    text: "Si lo haces, no podrás revertirlo",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Estoy seguro!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, procede con la eliminación
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('pei-profiles.store') }}" + '/' + axisId,
                            success: function(data) {

                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });

                        // Elimina la tarjeta del DOM
                        $(this).closest('.card').remove();

                        // Muestra una notificación de éxito después de la eliminación
                        Swal.fire(
                            'Borrado',
                            'El registro ha sido eliminado correctamente',
                            'success'
                        );
                    }
                });
            });
        });

        // Verifica si hay datos en el localStorage
        var type = '{{ $type }}'
        var storedType = localStorage.getItem('type');

        var storedTaskID = localStorage.getItem('taskID');
        if (storedTaskID !== null && storedType === 'master') {
            // Si se encontraron datos en el localStorage, oculta el primer nav y muestra el segundo
            document.getElementById('default-nav').style.display = 'none';
            document.getElementById('dynamic-nav').style.display = 'block';

            // Crea la URL dinámica para el segundo nav
            var tasksShowLinkDynamic = document.getElementById('tasks-show-link-dynamic');
            if (tasksShowLinkDynamic) {
                var dynamicURL = "{{ route('tasks.show', 'taskIDPlaceholder') }}";
                dynamicURL = dynamicURL.replace('taskIDPlaceholder', storedTaskID);
                tasksShowLinkDynamic.innerHTML = '<a href="' + dynamicURL + '">Lista de Tareas</a>';
            }
        } else if (storageType !== null && storedTaskID === null) {
            document.getElementById('default-nav').style.display = 'none';
            document.getElementById('dynamic-nav').style.display = 'block';

        }
    </script>
@stop
