@extends('layouts.master')
@section('title', 'Usuarios')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Usuarios</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="null" href="javascript:void(0)" id="createNewUsers">
                            Agregar Usuario</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Roles</th>
                                        <th>Grupo</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="userModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="card-header card-header-info">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="userForm" name="userForm" class="form-horizontal">
                                        {{ Form::hidden('user_id', null, ['id' => 'user_id']) }}
                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('email', 'Correo:', ['class' => 'control-label']) }}
                                            {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('password', 'Contraseña:', ['class' => 'control-label']) }}
                                            {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('confirm-password', 'Confirmar Contraseña:', ['class' => 'control-label']) }}
                                            {{ Form::password('confirm-password', ['class' => 'form-control', 'id' => 'confirm-password']) }}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('group_id', 'Grupo:') }}
                                            {!! Form::select('group_id', [], null, [
                                                'placeholder' => '',
                                                'id' => 'groups',
                                                'style' => 'width:100%',
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('roles', 'Roles:') }}
                                            {!! Form::select('roles[]', [], null, [
                                                'id' => 'roles',
                                                'style' => 'width:100%',
                                                'multiple' => 'multiple', // Agregado para permitir selección múltiple
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
                    ajax: "{{ route('globales.users.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'roles',
                            name: 'roles',
                            render: function(data, type, full, meta) {
                                var rolesArray = data.split(', ');

                                var rolesHtml = '';

                                // Recorremos el arreglo de categorías y aplicamos la clase "badge" a cada una
                                rolesArray.forEach(function(role) {
                                    rolesHtml += '<span class="badge badge-secondary">' +
                                        role + '</span> ';
                                });

                                return rolesHtml; // Devolvemos el HTML personalizado para la columna de categorías
                            }
                        },
                        {
                            data: 'group',
                            name: 'group',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    if (data && data !== '-') {
                                        return '<div style="white-space: normal;" title="' + data +
                                            '">' + data + '</div>';
                                    } else {
                                        return '<span class="badge badge-warning">Pendiente</span>';
                                        
                                    }
                                } else {
                                    return data;
                                }
                            }
                        },



                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]

                });

                // Función para inicializar Select2
                function initializeSelect2WithSearch(selector, placeholder, url, defaultOption) {
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
                                            text: item.name,
                                            id: item.id
                                        };
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                }

                // Función para inicializar selectores con relación AJAX
                function initializeSelect2WithValues(url, control, selectedData) {
                    $.ajax({
                        type: 'GET',
                        url: url
                    }).then(function(data) {
                        var option = new Option(data.name, data.id, true, true);
                        control.append(option).trigger('change');
                        control.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });
                }

                $('#createNewUsers').click(function() {
                    $('#saveBtn').val("create-user");
                    $('#user_id').val('');
                    $('#userForm').trigger("reset");
                    $('#modalHeading').html("Nuevo Usuario");
                    $('#userModal').modal('show');

                    //Grupo
                    var groups = $('#groups').select2();
                    groups.empty();
                    initializeSelect2WithSearch(groups, 'Seleccione Grupo de Trabajo trabajo',
                        '{{ route('globales.get-root-groups') }}');

                    // Roles
                    var roles = $('#roles').select2();
                    roles.empty();
                    initializeSelect2WithSearch(roles, 'Seleccione los Roles',
                        '{{ route('globales.get-roles') }}');

                });

                $('body').on('click', '.editUser', function() {
                    var userID = $(this).data('id');
                    $.get("{{ route('globales.users.index') }}" + '/' + userID + '/edit', function(data) {
                        console.log(data)
                        $('#modalHeading').html("Editar Usuario " + data.name);
                        $('#saveBtn').val("edit-type_task");
                        $('#userModal').modal('show');
                        $('#userForm').trigger("reset");
                        $('#user_id').val(data.user.id);
                        $('#name').val(data.user.name);
                        $('#email').val(data.user.email);

                        if (data.password) {
                            $('#password').val("********"); // Mostrar un valor genérico
                            $('#confirm-password').val("********"); // Mostrar un valor genérico
                        } else {
                            $('#password').val(""); // Vaciar el campo
                            $('#confirm-password').val(""); // Vaciar el campo
                        }

                        // Group initial Value
                        var groupsSelect = $('#groups').select2();

                        //Ruta para buscar detalles de un grupo
                        var urlGroup = '/admin/globales/get-group/' + data.user.group_id;
                        initializeSelect2WithValues(urlGroup, groupsSelect)

                        //Ruta para cargar todos los Grupos 
                        var routeGroups = '{{ route('globales.get-root-groups') }}'
                        groupsSelect.empty();
                        initializeSelect2WithSearch(groupsSelect, 'Seleccione Grupo de Trabajo trabajo',
                            routeGroups
                        );

                        //Roles
                        var rolesSelect = $('#roles').select2();
                        rolesSelect.empty();

                        //Inicializamos el selector para buscar grupos
                        initializeSelect2WithSearch(rolesSelect, 'Seleccione los Roles',
                            '{{ route('globales.get-roles') }}');

                        // Roles - Selector Múltiple - Se obtiene un arreglo con los valores preseleccionados
                        //desde el controlador
                        data.rolesChecked.forEach(function(d) {
                            var option = new Option(d.text, d.id, true, true);
                            rolesSelect.append(option).trigger('change');
                            rolesSelect.trigger({
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

                    var data = $('#userForm').serialize();
                    var url = "{{ route('globales.users.store') }}"
                    $.ajax({
                        data: data,
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('#userForm').trigger("reset");
                            $('#userModal').modal('hide');
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

                $('body').on('click', '.deleteUser', function() {
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
                            var typeTaskID = $(this).data("id");
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('globales.users.store') }}" + '/' + typeTaskID,
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
