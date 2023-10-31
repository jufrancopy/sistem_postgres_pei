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
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)"
                            id="createNewProfile">
                            Nuevo Perfil</a>
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
                ajax: "{{ route('pei-profiles.index') }}",
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
                $('#modalHeading').html("Nuevo Perfil de Planificación Estratégica");
                $('#ajaxModal').modal('show');

                $('.form-group.dependencies').hide();
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

                    // Reinicializar el selector de grupos
                    initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
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
                            console.log(data)
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

            $('#saveBtn').click(function(e) {
                e.preventDefault();
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
                            // Alert Toastr
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
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
