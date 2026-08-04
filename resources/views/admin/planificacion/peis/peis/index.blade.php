@extends('layouts.master')
@section('title', 'Planificación Estratégica')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Módulo de Planificación Estratégica</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Módulo de Planificación Estratégica</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-2">
                            <label class="font-weight-bold mr-2 mb-0 text-dark small"><i class="fa fa-filter text-info mr-1"></i> Estado:</label>
                            <select id="filterEstadoPei" class="form-control form-control-sm font-weight-bold" style="width: auto; min-width: 170px;">
                                <option value="activos" selected>🟢 Sólo Activos</option>
                                <option value="inactivos">🔴 Sólo Inactivos</option>
                                <option value="todos">📋 Todos los Planes</option>
                            </select>
                        </div>
                        <div>
                            <a class="btn btn-outline-info mb-2 mr-2 font-weight-bold" href="{{ route('globales.roles.guide') }}" title="Ver guía de permisos y roles">
                                <i class="fa fa-book-open mr-1"></i> Guía de Roles y Permisos
                            </a>
                            <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                                id="createNewProfile">
                                <i class="fa fa-plus mr-1"></i> Nuevo Perfil
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Periodo</th>
                                        <th>Tipo</th>
                                        <th>Grupo</th>
                                        <th>Analista</th>
                                        <th>Estado</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Inicio Modal --}}
                    <div class="modal fade" id="ajaxModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="profileForm" name="profileForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}
                                        {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                                        {{ Form::hidden('type', 'group', ['id' => 'type']) }}
                                        {{ Form::hidden('level', 'master', ['id' => 'level']) }}
                                        {{ Form::hidden('mision', null, ['class' => 'form-control', 'id' => 'mision']) }}
                                        {{ Form::hidden('vision', null, ['class' => 'form-control', 'id' => 'vision']) }}
                                        {{ Form::hidden('values', null, ['class' => 'form-control', 'id' => 'values']) }}
                                        {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'period']) }}
                                        {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                                        {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'denominator']) }}
                                        {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goal']) }}
                                        {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('year_start', 'Año de Inicio') !!}
                                            {!! Form::date('year_start', null, [
                                                'class' => 'form-control',
                                                'placeholder' => '2023',
                                                'data-date-format' => 'yyyy',
                                                'max' => date('Y'),
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('year_end', 'Año de Finalización') !!}
                                            {!! Form::date('year_end', null, [
                                                'class' => 'form-control',
                                                'placeholder' => '2028',
                                                'data-date-format' => 'yyyy',
                                                'max' => date('Y'),
                                            ]) !!}
                                        </div>

                                        <div class="form-group type_profile">
                                            {{ Form::label('type_profile', 'Tipo:') }}
                                            {!! Form::select('type_profile', ['group' => 'Grupal', 'corporative' => 'Corporativo'], null, [
                                                'id' => 'type_profile',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group dependencies" style="display: none;">
                                            {{ Form::label('dependency_id', 'Elija Corporación:') }}
                                            {!! Form::select('dependency_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'dependencies',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group group_roots">
                                            {{ Form::label('group_root_id', 'Evento:') }}
                                            {!! Form::select('group_root_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'group_roots',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group groups">
                                            {{ Form::label('groups', 'Asignar Grupo de Trabajo:') }}
                                            {!! Form::select('group_id', [], null, [
                                                'id' => 'groups',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('analysts', 'Asignar Analista:') }}
                                            {!! Form::select('analyst_id[]', [], null, [
                                                'id' => 'analysts',
                                                'placeholder' => '',
                                                'style' => 'width:100%',
                                                'multiple',
                                            ]) !!}
                                        </div>

                                        {{-- ── Perfil FODA vinculado ── --}}
                                        <div class="form-group">
                                            {{ Form::label('foda_perfil_id', 'Perfil FODA vinculado:') }}
                                            {!! Form::select('foda_perfil_id', [], null, [
                                                'id'    => 'foda_perfil_id',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        {{-- ── Modelo de Niveles ── --}}
                                        <div class="form-group">
                                            {{ Form::label('modelo_niveles', 'Modelo de Niveles del Plan:') }}
                                            {!! Form::select('modelo_niveles', [
                                                'MECIP' => 'MECIP 2015 — Objetivo Estratégico / Meta / Acción',
                                                'IPS'   => 'IPS 2023-2028 — Eje Estratégico / Objetivo / Acción',
                                                'A'     => 'Clásico — Eje / Objetivo / Acción',
                                                'B'     => 'Proyectos — Programa / Proyecto / Actividad',
                                                'C'     => 'Estratégico — Eje / Meta / Tarea',
                                                'D'     => 'Institucional — Estrategia / Plan / Acción',
                                                'custom'=> 'Personalizado...',
                                            ], null, [
                                                'id'    => 'modelo_niveles',
                                                'style' => 'width:100%',
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>

                                        {{-- Campos personalizados (solo visibles si elige "Personalizado") --}}
                                        <div id="custom_niveles" style="display:none;">
                                            <div class="card card-body bg-light mb-2">
                                                <small class="text-muted mb-2">
                                                    Definí cómo se llamará cada nivel en este plan:
                                                </small>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        {{ Form::label('label_axi', 'Nivel 1 (ej: Eje)') }}
                                                        {{ Form::text('label_axi', null, ['class' => 'form-control', 'id' => 'label_axi', 'placeholder' => 'Eje Estratégico']) }}
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        {{ Form::label('label_goal', 'Nivel 2 (ej: Objetivo)') }}
                                                        {{ Form::text('label_goal', null, ['class' => 'form-control', 'id' => 'label_goal', 'placeholder' => 'Objetivo']) }}
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        {{ Form::label('label_action', 'Nivel 3 (ej: Acción)') }}
                                                        {{ Form::text('label_action', null, ['class' => 'form-control', 'id' => 'label_action', 'placeholder' => 'Acción']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Campo oculto que guarda el JSON final --}}
                                        {{ Form::hidden('nivel_label', null, ['id' => 'nivel_label']) }}

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
                    {{-- Fin Modal --}}
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
                ajax: {
                    url: "{{ route('pei-profiles.index') }}",
                    data: function(d) {
                        d.estado = $('#filterEstadoPei').val();
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'period',
                    name: 'period'
                }, {
                    data: 'type',
                    name: 'type',
                    render: function(data, type, full, meta) {
                        if (data === 'group') {
                            return 'Grupal';
                        } else if (data === 'corporative') {
                            return 'Corporación';
                        } else {
                            return data;
                        }
                    }
                }, {
                    data: 'group',
                    name: 'group'
                }, {
                    data: 'analysts',
                    name: 'analysts',
                    render: function(data, type, full, meta) {
                        var analystsArray = data ? data.split(', ') : [];
                        var analystsHtml = '';
                        analystsArray.forEach(function(analyst) {
                            if (analyst) {
                                analystsHtml += '<span class="badge badge-secondary">' + analyst + '</span> ';
                            }
                        });
                        return analystsHtml;
                    }
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }, ]
            });

            // Redibujar tabla al cambiar el filtro de estado
            $('#filterEstadoPei').on('change', function() {
                table.draw();
            });

            // Handler para alternar estado Activo / Inactivo vía AJAX
            $('body').on('click', '.toggleStatus', function() {
                var id = $(this).data('id');
                var btn = $(this);
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ url("pei-profiles") }}/' + id + '/toggle-status',
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        table.draw(false);
                    },
                    error: function() {
                        btn.prop('disabled', false);
                        alert('No se pudo cambiar el estado del Plan Estratégico.');
                    }
                });
            });

            // Función para inicializar Select2
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

            $('#createNewProfile').click(function() {
                $('#saveBtn').val("create-user");
                $('#profile_id').val('');
                $('#profileForm').trigger("reset");
                // Limpiar explícitamente campos que no deben heredarse de una edición anterior
                $('#mision').val('');
                $('#vision').val('');
                $('#values').val('');
                $('#nivel_label').val('');
                $('#modalHeading').html("Nuevo Perfil de Planificación Estratégica");
                $('#ajaxModal').modal('show');

                $('.form-group.dependencies').hide();
                $('#custom_niveles').hide();

                // Selector de tipo de perfil
                $('#type_profile').select2();
                $('#type_profile').change(function() {
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

                // Selector de modelo de niveles
                $('#modelo_niveles').select2().off('change.niveles').on('change.niveles', function() {
                    if ($(this).val() === 'custom') {
                        $('#custom_niveles').show();
                    } else {
                        $('#custom_niveles').hide();
                    }
                });

                // Inicializar el selector de dependencia
                initializeSelect2($("#dependencies"), 'Seleccione la dependencia',
                    '{{ route('globales.get-dependencies') }}');

                // Inicializar el selector de grupo raíz
                initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz de trabajo',
                    '{{ route('globales.get-root-groups') }}');

                // Cuando se cambia el grupo raíz
                $('#group_roots').on('change', function() {
                    var groupRootID = $(this).val();
                    var url = 'admin/globales/get-groups/' + groupRootID;
                    $.getJSON(url, function(data) {
                        if (data.length === 0) {
                            var rootText = $('#group_roots').select2('data')[0].text;
                            var opt = new Option(rootText, groupRootID, true, true);
                            $('#groups').empty().append(opt).trigger('change');
                        } else {
                            initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                        }
                    });
                });

                //Analysts
                var url = '{{ route('globales.get-users') }}';
                $("#analysts").val([]).change();
                $("#analysts").trigger("change");

                $('#analysts').select2({
                    allowClear: true,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return { text: item.name, id: item.id }
                                })
                            };
                        },
                        cache: true
                    }
                });

                // Perfil FODA
                $('#foda_perfil_id').empty().select2({
                    placeholder: 'Seleccioná el perfil FODA...',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('get-foda-perfiles') }}',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(res) {
                            return { results: res };
                        },
                        cache: true
                    }
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
                    if (data.profile.dependency) {
                        initSelect2WithRelationship($('#dependencies'), data.profile.dependency_id, data.profile.dependency.dependency);
                    }

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
                                        return { text: item.dependency, id: item.id }
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                    // Precargar grupo raíz (Evento) y grupo — para cualquier tipo que tenga grupo asignado
                    if (data.profile.group) {
                        // El Evento es el padre del grupo
                        if (data.groupParent) {
                            initSelect2WithRelationship($('#group_roots'), data.groupParent.id, data.groupParent.name);
                        } else {
                            // Si el grupo no tiene padre, él mismo es el evento raíz
                            initSelect2WithRelationship($('#group_roots'), data.profile.group_id, data.profile.group.name);
                        }
                        initSelect2WithRelationship($('#groups'), data.profile.group_id, data.profile.group.name);
                    }

                    // Cuando se cambia el grupo raíz
                    $('#group_roots').off('change').on('change', function() {
                        var groupRootID = $(this).val();
                        var url = 'admin/globales/get-groups/' + groupRootID;
                        $.getJSON(url, function(data) {
                            if (data.length === 0) {
                                var rootText = $('#group_roots').select2('data')[0].text;
                                var opt = new Option(rootText, groupRootID, true, true);
                                $('#groups').empty().append(opt).trigger('change');
                            } else {
                                initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                            }
                        });
                    });

                    // ── Analistas: primero inicializar select2 con ajax, luego precargar valores ──
                    var urlAnalysts = '{{ route('globales.get-users') }}';
                    $('#analysts').empty();
                    $('#analysts').select2({
                        placeholder: 'Seleccione Analistas',
                        allowClear: true,
                        ajax: {
                            url: urlAnalysts,
                            dataType: 'json',
                            delay: 250,
                            processResults: function(res) {
                                return {
                                    results: $.map(res, function(item) {
                                        return { text: item.name, id: item.id };
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                    // Precargar analistas ya asignados
                    data.analystsChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        $('#analysts').append(option);
                    });
                    $('#analysts').trigger('change');

                    // ── Perfil FODA ──
                    $('#foda_perfil_id').empty().select2({
                        placeholder: 'Seleccioná el perfil FODA...',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('get-foda-perfiles') }}',
                            dataType: 'json',
                            delay: 250,
                            processResults: function(res) {
                                return { results: res };
                            },
                            cache: true
                        }
                    });
                    if (data.profile.foda_perfil_id && data.fodaPerfilNombre) {
                        var opt = new Option(data.fodaPerfilNombre, data.profile.foda_perfil_id, true, true);
                        $('#foda_perfil_id').append(opt).trigger('change');
                    }

                    // ── Precargar modelo de niveles ──
                    $('#custom_niveles').hide();

                    // Reinicializar select2 del modelo de niveles
                    $('#modelo_niveles').select2({
                        width: '100%',
                        minimumResultsForSearch: Infinity
                    });

                    if (data.profile.nivel_label) {
                        try {
                            var savedLabels = JSON.parse(data.profile.nivel_label);
                            // Detectar si coincide con algún modelo predefinido
                            var modelos = {
                                'MECIP': { axi: 'Objetivo Estratégico', goal: 'Meta',     action: 'Acción' },
                                'IPS':   { axi: 'Eje Estratégico',      goal: 'Objetivo', action: 'Acción' },
                                'A':     { axi: 'Eje',                  goal: 'Objetivo', action: 'Acción' },
                                'B':     { axi: 'Programa',             goal: 'Proyecto', action: 'Actividad' },
                                'C':     { axi: 'Eje',                  goal: 'Meta',     action: 'Tarea' },
                                'D':     { axi: 'Estrategia',           goal: 'Plan',     action: 'Acción' },
                            };
                            var matchedKey = null;
                            $.each(modelos, function(key, m) {
                                if (m.axi === savedLabels.axi && m.goal === savedLabels.goal && m.action === savedLabels.action) {
                                    matchedKey = key;
                                    return false;
                                }
                            });
                            if (matchedKey) {
                                $('#modelo_niveles').val(matchedKey).trigger('change');
                            } else {
                                $('#modelo_niveles').val('custom').trigger('change');
                                $('#label_axi').val(savedLabels.axi || '');
                                $('#label_goal').val(savedLabels.goal || '');
                                $('#label_action').val(savedLabels.action || '');
                                $('#custom_niveles').show();
                            }
                            $('#nivel_label').val(data.profile.nivel_label);
                        } catch(e) {}
                    }

                    // Listener para mostrar/ocultar campos personalizados en edición
                    $('#modelo_niveles').off('change.niveles').on('change.niveles', function() {
                        $('#custom_niveles').toggle($(this).val() === 'custom');
                    });

                }); // cierre del $.get
            }); // cierre del $('body').on('.editProfile')

            $('#saveBtn').click(function(e) {
                e.preventDefault();

                // ── Armar nivel_label JSON antes de enviar ──
                var modelos = {
                    'MECIP': { axi: 'Objetivo Estratégico', goal: 'Meta',      action: 'Acción' },
                    'IPS':   { axi: 'Eje Estratégico',      goal: 'Objetivo',  action: 'Acción' },
                    'A':     { axi: 'Eje',                  goal: 'Objetivo',  action: 'Acción' },
                    'B':     { axi: 'Programa',             goal: 'Proyecto',  action: 'Actividad' },
                    'C':     { axi: 'Eje',                  goal: 'Meta',      action: 'Tarea' },
                    'D':     { axi: 'Estrategia',           goal: 'Plan',      action: 'Acción' },
                };
                var modeloSel = $('#modelo_niveles').val();
                var labels;
                if (modeloSel === 'custom') {
                    labels = {
                        master: 'PEI',
                        axi:    $('#label_axi').val()    || 'Nivel 1',
                        goal:   $('#label_goal').val()   || 'Nivel 2',
                        action: $('#label_action').val() || 'Acción',
                    };
                } else if (modelos[modeloSel]) {
                    labels = Object.assign({ master: 'PEI' }, modelos[modeloSel]);
                }
                if (labels) {
                    $('#nivel_label').val(JSON.stringify(labels));
                }

                // Si es corporativo, el grupo raíz (Evento) es el group_id
                if ($('#type_profile').val() === 'corporative' && $('#group_roots').val()) {
                    var rootVal  = $('#group_roots').val();
                    var rootText = $('#group_roots').select2('data')[0].text;
                    var opt = new Option(rootText, rootVal, true, true);
                    $('#groups').empty().append(opt).trigger('change');
                }

                $(this).html('Enviando..');
                $.ajax({
                    data: $('#profileForm').serialize(),
                    url: "{{ route('pei-profiles.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $(".success").text(data.success).addClass('alert alert-success');
                            setTimeout(function() {
                                $(".success").hide().html('');
                            }, 5000);
                        }
                        $('#profileForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        var obj = data.responseJSON.errors;
                        $.each(obj, function(key, value) {
                            toastr.options = { closeButton: true, progressBar: true };
                            toastr.error("Atención: " + value);
                        });
                        $('#saveBtn').html('Guardar Cambios');
                    }
                });
            });

            $('body').on('click', '.deleteProfile', function() {
                Swal.fire({
                    title: 'Estás seguro de eliminarlo?',
                    text: "Si lo haces, no podras revertirlo!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Estoy seguro!'
                }).then((isConfirm) => {
                    if (isConfirm.value) {
                        Swal.fire(
                            'Borrado!',
                            'El registro ha sido eliminado correctamente.',
                            'success'
                        )
                        var profileID = $(this).data("id");
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('pei-profiles.store') }}" + '/' + profileID,
                            success: function(data) {
                                table.draw();
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                })
            });

        });
    </script>
@stop
