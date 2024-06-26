@extends('layouts.master')
@section('title', 'Perfiles')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Perfiles de Análisis DAFO</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Perfiles Foda</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewProfile"> <i
                                class="material-icons ">add_box</i> Nuevo Perfil</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Dependencia</th>
                                        <th>Modelo</th>
                                        {{-- <th>Categorías</th> --}}
                                        <th width="280px">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="modalProfile" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalProfilelHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="profileForm" name="profileForm" class="form-horizontal">

                                        {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('context', 'Contexto:') }}
                                            {{ Form::text('context', null, ['class' => 'form-control', 'id' => 'context']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('type', 'Tipo:') }}
                                            {!! Form::select('type', ['individual' => 'Individual', 'grupal' => 'Grupal'], null, [
                                                'placeholder' => '',
                                                'id' => 'type',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group group_roots" style="display: none;">
                                            {{ Form::label('group_root_id', 'Elija Grupo Raíz:') }}
                                            {!! Form::select('group_root_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'group_roots',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group dependencies_roots" style="display: none;">
                                            {{ Form::label('dependencies_roots', 'Elija Dependencia Raíz:') }}
                                            {!! Form::select('dependencies_roots', [], null, [
                                                'placeholder' => '',
                                                'id' => 'dependencies_roots',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group groups" style="display: none;">
                                            {{ Form::label('groups', 'Grupo De Análisis:') }}
                                            {!! Form::select('group_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'groups',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group dependencies">
                                            {{ Form::label('dependency_id', 'Seleccione Dependencia Responsable:') }}
                                            {!! Form::select('dependency_id', [], null, [
                                                'id' => 'dependency',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('model_id', 'Elija el Modelo:') }}
                                            {!! Form::select('model_id', [], null, [
                                                'placeholder' => 'Seleccione el Modelo',
                                                'id' => 'models',
                                                'style' => 'width:100%',
                                            ]) !!}
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
                ajax: "{{ route('foda-perfiles.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'type',
                        name: 'type'
                    }, {
                        data: 'dependency',
                        name: 'dependency'
                    }, {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

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
                $('#profileForm').trigger("reset");
                $('#saveBtn').val("create-user");
                $('#profile_id').val('');
                $('#modalProfilelHeading').html("Nuevo Perfil");
                $('#modalProfile').modal('show');
                $('.form-group.group_roots').hide();
                $('.form-group.groups').hide();
                $('.form-group.dependencies').show();
                $('.form-group.dependencies_roots').show();

                //Inicializamos Select2 en Type
                var type = $('#type').select2({
                    placeholder: 'Seleccione Tipo'
                });
                type.change(function() {
                    if ($(this).val() === 'grupal') {
                        $('.form-group.groups').show();
                        $('.form-group.group_roots').show();
                        $('.form-group.dependencies').hide();
                        $('#group_roots').empty().trigger('change')
                        $('.form-group.dependencies_roots').hide();
                        $('#groups').empty().trigger('change')
                        $('#models').empty().trigger('change')
                    } else {
                        $('.form-group.groups').hide();
                        $('.form-group.group_roots').hide();
                        $('.form-group.dependencies_roots').show();
                        $('.form-group.dependencies').show();
                        $('#models').empty().trigger('change')
                    }
                });

                // Inicializar el selector de dependencia
                initializeSelect2($("#dependencies_roots"), 'Seleccione la dependencia Raiz',
                    '{{ route('globales.get-dependencies-root') }}');

                // Cuando se cambia Dependencia raíz
                $('#dependencies_roots').on('change', function() {
                    var dependencyRootID = $(this).val();
                    var url = 'admin/globales/get-dependencies/' + dependencyRootID;

                    // Reinicializar el selector de grupos
                    initializeSelect2($("#dependency"), 'Seleccione la Dependencia', url);
                });

                // Inicializar el selector de dependencia
                initializeSelect2($("#dependency"), 'Seleccione la dependencia',
                    '{{ route('globales.get-dependencies') }}');

                // Inicializar el selector de grupo raíz
                initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz de trabajo',
                    '{{ route('globales.get-root-groups') }}');

                // Cuando se cambia el grupo raíz
                $('#group_roots').on('change', function() {
                    var groupRootID = $(this).val();
                    var url = 'admin/globales/get-groups/' + groupRootID;

                    // Reinicializar el selector de grupos
                    initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                });

                $("#groups").select2({
                    placeholder: "Seleccionar Grupo"
                });

                // Inicializar el selector de modelos
                initializeSelect2($("#models"), 'Seleccione el Modelo', '{{ route('get-models') }}');
            });

            $('body').on('click', '.editProfile', function() {
                var profileId = $(this).data('id');
                $.get("{{ route('foda-perfiles.index') }}" + '/' + profileId + '/edit', function(data) {
                    $('#modalProfilelHeading').html("Editar Perfil");
                    $('#saveBtn').val("edit-profile");
                    $('#modalProfile').modal('show');
                    $('#profileForm').trigger("reset");
                    $('.errors').removeClass("alert alert-danger")
                    $('#profile_id').val(data.profile.id);
                    $('#name').val(data.profile.name);
                    $('#context').val(data.profile.context);
                    $('#group_roots').select2();
                    $('#dependencies_roots').select2();
                    $('#groups').select2();
                    $('#models').select2();
                    $('#categories').select2();

                    // Selectores dependientes
                    var $typeSelect = $('#type').select2();

                    //Valor Inicial Type
                    var initialTypeValue = data.profile.type;
                    $typeSelect.val(initialTypeValue).trigger('change');
                    toggleElementsBasedOnType(initialTypeValue);

                    // Función para mostrar u ocultar elementos dependiendo del valor de 'type'
                    function toggleElementsBasedOnType(typeValue) {
                        if (typeValue === 'grupal') {
                            $('.form-group.groups').show();
                            $('.form-group.group_roots').show();
                            $('.form-group.dependencies').hide();
                            $('.form-group.dependencies_roots').hide();

                            //Inicializamos Grupo Raiz con sus valores
                            initValueSelect2($('#group_roots'), data.rootGroup.id, data.rootGroup
                                .name);

                            //Inicializamos Grupos con sus valores 
                            initValueSelect2($('#groups'), data.profile.group.id, data.profile.group
                                .name);

                            //Inicializamos Selector Modelos
                            var models = $('#models')

                            // Inicializar el buscador de Modelos
                            initializeSelect2(models, 'Seleccione el Modelo',
                                '{{ route('get-models') }}');

                            //Inicializamos Selector Modelo con valor preseleccionado
                            initValueSelect2(models, data.profile.model_id, data.profile.model
                                .name);

                            //Selector de Categorias con el Cambio de Modelo
                            models.on('change', function() {
                                var modelId = $(this).val();
                                var url = 'get-foda-categories/' + modelId

                                //Listamos Categorias segùn modelo
                                $('#categories').select2({
                                    placeholder: 'Seleccione las Categorías para Analizar',
                                    ajax: {
                                        url: url,
                                        dataType: 'json',
                                        delay: 250,
                                        processResults: function(data) {
                                            return {
                                                results: $.map(data, function(
                                                    item) {
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
                            });
                        } else {
                            $('#group_roots').select2().val('').trigger('change');
                            $('#groups').select2().val('').trigger('change');
                            $('.form-group.groups').hide();
                            $('.form-group.group_roots').hide();
                            $('.form-group.dependencies').show();
                            $('.form-group.dependencies_roots').show();

                            //Inicializamos Selector Modelos
                            var models = $('#models')

                            // Inicializar el buscador de Modelos
                            initializeSelect2(models, 'Seleccione el Modelo',
                                '{{ route('get-models') }}');

                            //Inicializamos Selector Modelo con valor preseleccionado
                            initValueSelect2(models, data.profile.model_id, data.profile.model
                                .name);

                            // Inicializar el selector de dependencia Raíz
                            initializeSelect2($("#dependencies_roots"),
                                'Seleccione la dependencia Raiz',
                                '{{ route('globales.get-dependencies-root') }}');

                            // Cuando se cambia Dependencia raíz
                            $('#dependencies_roots').on('change', function() {
                                var dependencyRootID = $(this).val();
                                var url = 'admin/globales/get-dependencies/' +
                                    dependencyRootID;

                                // Reinicializar el selector de grupos
                                initializeSelect2($("#dependency"),
                                    'Seleccione la Dependencia', url);
                            });

                            //Inicializamos Grupo Raiz con sus valores
                            initValueSelect2($('#dependencies_roots'), data.rootDependency.id, data
                                .rootDependency
                                .dependency);

                            //Inicializamos Grupos con sus valores 
                            initValueSelect2($('#dependency'), data.profile.dependency.id, data
                                .profile.dependency
                                .dependency);

                            //Selector de Categorias con el Cambio de Modelo
                            models.on('change', function() {
                                var modelId = $(this).val();
                                var url = 'get-foda-categories/' + modelId

                                //Listamos Categorias segùn modelo
                                $('#categories').select2({
                                    placeholder: 'Seleccione las Categorías para Analizar',
                                    ajax: {
                                        url: url,
                                        dataType: 'json',
                                        delay: 250,
                                        processResults: function(data) {
                                            return {
                                                results: $.map(data, function(
                                                    item) {
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
                            });
                        }
                    }

                    // Cuando cambia el valor del select '#type'
                    $typeSelect.on('change', function() {
                        var selectedTypeValue = $(this).val();
                        toggleElementsBasedOnType(selectedTypeValue);

                    });

                    //Precargar Dependencia
                    initValueSelect2($('#dependency'), data.profile.dependency.id, data.profile
                        .dependency.dependency);

                    function initValueSelect2(control, key, value) {
                        var data = {
                            id: key,
                            text: value
                        };
                        var initOption = new Option(data.text, data.id, true, true);
                        control.empty().append(initOption).trigger('change');
                    }

                    // Inicializar el selector de grupo raíz
                    initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz de trabajo',
                        '{{ route('globales.get-root-groups') }}');
                });
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');
                $.ajax({
                    data: $('#profileForm').serialize(),
                    url: "{{ route('foda-perfiles.store') }}",
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
                        $('#modalProfile').modal('hide');
                        $(".success").removeAttr("style");
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
                        $("#saveBtn").html("Guardar Cambios");
                    },

                });
            });

            $('body').on('click', '.deleteProfile', function() {
                Swal.fire({
                    title: 'Estás seguro de eliminarlo?',
                    text: "Si lo haces, no podras revertirlo!",
                    type: 'warning',
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
                        var cicle_id = $(this).data("id");
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('foda-perfiles.store') }}" + '/' + cicle_id,
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
