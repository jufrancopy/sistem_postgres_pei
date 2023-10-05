@extends('layouts.master')
@section('title', 'Perfiles')

@section('content_header')
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Globales</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ciclos</li>
                </ol>
            </nav>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="success"></div>
                    <a class="btn btn-success mb-2" href="javascript:void(0)" id="createNewProfile"> Agregar Perfil</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered data-table display nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nombre</th>
                                    <th>Contexto</th>
                                    <th>Dependencia</th>
                                    <th>Modelo</th>
                                    <th>Categorías</th>
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
                            <div class="modal-header">
                                <h4 class="modal-title" id="modalProfilelHeading"></h4>
                            </div>
                            <div class="modal-body">
                                <form id="profileForm" name="profileForm" class="form-horizontal">
                                    <div class="alert alert-danger errors" role="alert"></div>

                                    {{ Form::hidden('profile_id', null, ['profile_id' => 'profile_id']) }}

                                    <div class="form-group">
                                        {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('context', 'Contexto:') }}
                                        {{ Form::text('context', null, ['class' => 'form-control', 'id' => 'context']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('dependency_id', 'Seleccione Dependencia Responsable:') }}
                                        {!! Form::select('dependency_id', null, null, [
                                            'id' => 'dependency',
                                            'style' => 'width:100%',
                                        ]) !!}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('model_id', 'Elija el Modelo:') }}
                                        {!! Form::select('model_id', null, null, [
                                            'placeholder' => 'Seleccione el Modelo',
                                            'id' => 'models',
                                            'style' => 'width:100%',
                                        ]) !!}
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('categories', 'Asigne una o varias Categorias:') !!}
                                        {!! Form::select('category_id[]', [], null, [
                                            'class' => 'form-control',
                                            'style' => 'width:100%',
                                            'id' => 'categories',
                                            'multiple',
                                        ]) !!}
                                    </div>

                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">Guardar
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
                ajax: "{{ route('foda-perfiles.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'context',
                        name: 'context'
                    }, {
                        data: 'dependency',
                        name: 'dependency'
                    }, {
                        data: 'model',
                        name: 'model'
                    }, {
                        data: 'categories',
                        name: 'categories',
                        render: function(data, type, full, meta) {
                            var categoriesArray = data.split(', ');

                            var categoriesHtml = '';

                            // Recorremos el arreglo de categorías y aplicamos la clase "badge" a cada una
                            categoriesArray.forEach(function(category) {
                                categoriesHtml += '<span class="badge badge-secondary">' +
                                    category + '</span> ';
                            });

                            return categoriesHtml; // Devolvemos el HTML personalizado para la columna de categorías
                        }
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

            $('#createNewProfile').click(function() {
                $('#saveBtn').val("create-user");
                $('#profile_id').val('');
                $('#profileForm').trigger("reset");
                $('#modalProfilelHeading').html("Nuevo Perfil");
                $('#modalProfile').modal('show');
                $('.errors').removeClass("alert alert-danger")

                //Dependency
                $("#dependency").val("");
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

                $("#categories").select2({
                    placeholder: "Seleccione los Factores"
                });

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
                        console.log(modelId)
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
