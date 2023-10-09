@extends('layouts.master')
@section('title', $profile->name)

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Añadir grupo a {{ $profile->name }}</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('foda-perfiles.index') }}">Perfiles</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $profile->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewGroup"> <i
                                class="material-icons ">add_box</i> Nuevo Grupo</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre</th>
                                        <th>Integrantes</th>
                                        <th width="280px">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="modalAddGroup" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalAddGrouplHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="profileAddGroupForm" name="profileAddGroupForm" class="form-horizontal">
                                        <div class="alert alert-danger errors" role="alert"></div>

                                        {{ Form::text('profile_id', $profile->id, ['id' => 'profile_id']) }}
                                        {{ Form::text('name', $profile->name, ['id' => 'name']) }}
                                        {{ Form::text('context', $profile->context, ['id' => 'context']) }}
                                        {{ Form::text('type', $profile->type, ['id' => 'type']) }}
                                        {{ Form::text('dependency_id', $profile->dependency_id, ['id' => 'dependency_id']) }}
                                        {{ Form::text('model_id', $profile->model_id, ['id' => 'model_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('group-name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('group-name', null, ['class' => 'form-control', 'id' => 'group-name']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('members', 'Miembros del Grupo:') }}
                                            {{ Form::text('members', null, ['class' => 'form-control', 'id' => 'members']) }}
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
                ajax: "#",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'group_name',
                        name: 'group_name'
                    }, {
                        data: 'members',
                        name: 'members'
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

            $('#createNewGroup').click(function() {
                $('#saveBtn').val("create-group");
                $('#profile_id').val('');
                $('#profileAddGroupForm').trigger("reset");
                $('#modalAddGrouplHeading').html("Nuevo Perfil");
                $('#modalAddGroup').modal('show');
                $('.errors').removeClass("alert alert-danger")

            });

            $('body').on('click', 'editProfile', function() {
                var profileId = $(this).data('id');

                $.get("{{ route('foda-perfiles.index') }}" + '/' + profileId + '/edit', function(data) {
                    $('#modalAddGrouplHeading').html("Editar Perfil");
                    $('#saveBtn').val("edit-profile");
                    $('#modalAddGroup').modal('show');
                    $('#profileAddGroupForm').trigger("reset");
                    $('.errors').removeClass("alert alert-danger")
                    $('#profile_id').val(data.profile.id);
                    $('#name').val(data.profile.name);
                    $('#context').val(data.profile.context);


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

                    //Precargar Dependencia
                    initSelect2($('#dependency'), data.profile.dependency.id, data.profile
                        .dependency.dependency);

                    function initSelect2(control, key, value) {
                        var data = {
                            id: key,
                            text: value
                        };
                        var initOption = new Option(data.text, data.id, true,
                            true); // Establece el tercer y cuarto parámetro en "true"
                        control.empty().append(initOption).trigger('change');
                    }

                    //Models
                    $("#models").val("");
                    $('#models').select2({
                        placeholder: 'Seleccione el Modelo',
                        ajax: {
                            url: '{{ route('get-models') }}',
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.nombre,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });


                    //Precargar Modelo
                    $('#models').on('change', function() {
                        var modelId = $(this).val();
                        var url = 'get-foda-categories/' + modelId

                        //Categories
                        $("#categories").val([]).change();
                        $("#categories").val("");
                        $("#categories").trigger("change");


                        var dependencies = $('#categories').select2({
                            placeholder: 'Seleccione las Categorías para Analizar',
                            ajax: {
                                url: url,
                                dataType: 'json',
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                text: item.nombre,
                                                id: item.id
                                            }
                                        })
                                    };
                                },
                                cache: true
                            }
                        });

                    });

                    initSelect2($('#models'), data.profile.model.id, data.profile.model.nombre);

                    function initSelect2(control, key, value) {
                        var data = {
                            id: key,
                            text: value
                        };
                        var initOption = new Option(data.text, data.id, true,
                            true); // Establece el tercer y cuarto parámetro en "true"
                        control.empty().append(initOption).trigger('change');
                    }

                    //Clearing selections
                    $('#categories').select2()
                    $("#categories").val([]).change();
                    var selectCategories = $('#categories');
                    data.categoriesChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectCategories.append(option).trigger('change');
                        selectCategories.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                });
            });


            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Enviando..');
                $.ajax({
                    data: $('#profileAddGroupForm').serialize(),
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
                        $('#profileAddGroupForm').trigger("reset");
                        $('#modalAddGroup').modal('hide');
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
