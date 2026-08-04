@extends('layouts.master')
@section('title', 'Grupos de Trabajo')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Grupos de Trabajo - {{ $group->name }}</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('globales.groups.index') }}">Eventos</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Lista de Grupos</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="success"></div>
                        <a class="btn btn-success mb-2" data-group-id="{{ $group->id }}" href="javascript:void(0)"
                            id="createNewGroup">
                            Nuevo Grupo</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table display nowrap" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Miembros</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="ajaxModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="subGroupForm" name="subGroupForm" class="form-horizontal">

                                        {{ Form::hidden('group_id', null, ['id' => 'group_id']) }}
                                        {{ Form::hidden('parent_id', $group->id, ['id' => 'parent_id']) }}

                                        <div class="form-group">
                                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                                        </div>

                                         <div class="form-group">
                                             <div class="d-flex align-items-center justify-content-between mb-1">
                                                 {!! Form::label('members', 'Miembros:', ['class' => 'control-label font-weight-bold mb-0']) !!}
                                                 <button type="button" class="btn btn-sm btn-outline-success py-0 px-2" id="btnOpenQuickUserModal" style="font-size:.75rem">
                                                     <i class="fa fa-user-plus mr-1"></i> + Crear Nuevo Usuario
                                                 </button>
                                             </div>
                                             {!! Form::select('user_id[]', [], null, [
                                                 'class' => 'form-control',
                                                 'style' => 'width:100%',
                                                 'id' => 'members',
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

                     {{-- Modal de Creación Rápida "In-Situ" de Usuario --}}
                     <div class="modal fade" id="quickUserModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
                         <div class="modal-dialog modal-dialog-centered">
                             <div class="modal-content border-0 shadow">
                                 <div class="modal-header bg-success text-white py-2">
                                     <h5 class="modal-title font-weight-bold" style="font-size:1rem;">
                                         <i class="fa fa-user-plus mr-2"></i>Crear Nuevo Usuario In-Situ
                                     </h5>
                                     <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                 </div>
                                 <div class="modal-body">
                                     <form id="quickUserForm">
                                         <div class="form-group mb-3">
                                             <label class="font-weight-bold small">Nombre y Apellido <span class="text-danger">*</span></label>
                                             <input type="text" name="name" id="quick_user_name" class="form-control" placeholder="Ej: Maria Gonzalez" required>
                                         </div>
                                         <div class="form-group mb-3">
                                             <label class="font-weight-bold small">Correo Electrónico <span class="text-danger">*</span></label>
                                             <input type="email" name="email" id="quick_user_email" class="form-control" placeholder="ejemplo@institucion.gov.py" required>
                                         </div>
                                         <div class="form-group mb-3">
                                             <label class="font-weight-bold small">Rol Principal <span class="text-danger">*</span></label>
                                             <select name="roles[]" id="quick_user_role" class="form-control" style="width:100%">
                                                 <option value="Analista de Planificación">Analista de Planificación</option>
                                                 <option value="Participantes" selected>Participantes / Miembro</option>
                                                 <option value="Coordinador de Planificación">Coordinador de Planificación</option>
                                                 <option value="Gestor de Actividades">Gestor de Actividades</option>
                                                 <option value="Colaborador de Actividades">Colaborador de Actividades</option>
                                                 <option value="Analista de Monitoreo PEI">Analista de Monitoreo PEI</option>
                                             </select>
                                         </div>
                                         <div class="form-group mb-3">
                                             <label class="font-weight-bold small">Contraseña Inicial</label>
                                             <input type="password" name="password" id="quick_user_password" class="form-control" value="12345678" placeholder="Contraseña">
                                             <small class="text-muted">Por defecto: <code>12345678</code> (el usuario podrá cambiarla).</small>
                                         </div>
                                         <div class="text-right">
                                             <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                                             <button type="submit" class="btn btn-sm btn-success px-3" id="saveQuickUserBtn">
                                                 <i class="fa fa-save mr-1"></i> Guardar y Seleccionar
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
                    ajax: "{{ route('globales.groups.show', $group->id) }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'members',
                        name: 'members',
                        render: function(data, type, full, meta) {
                            var membersArray = data.split(', ');

                            var membersHtml = '';

                            membersArray.forEach(function(member) {
                                membersHtml += '<span class="badge badge-secondary">' +
                                    member + '</span> ';
                            });

                            return membersHtml;
                        }
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, ]
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

                $('#createNewGroup').click(function() {
                    $('#saveBtn').val("create-user");
                    $('#group_id').val('');
                    $('#subGroupForm').trigger("reset");
                    $('#modalHeading').html("Nuevo Grupo");
                    $('#ajaxModal').modal('show');

                    //Limpiamos el Selector
                    var membersSelect = $("#members");
                    membersSelect.empty();

                    //Obtenermos los usuarios que están asociados al grupo
                    var url = '/admin/globales/get-users/'

                    initializeSelect2(membersSelect, 'Seleccione Miembros', url);
                });

                $('body').on('click', '.editGroup', function() {
                    var groupID = $(this).data('id');
                    $.get("{{ route('globales.groups.index') }}" + '/' + groupID + '/edit', function(data) {
                        $('#modalHeading').html("Editar Grupo");
                        $('#saveBtn').val("edit-user");
                        $('#ajaxModal').modal('show');
                        $('#subGroupForm').trigger("reset");
                        $('.errors').removeClass("alert alert-danger")
                        $('#group_id').val(data.group.id);
                        $('#name').val(data.group.name);

                        //Limpiamos el Selector
                        var membersSelect = $("#members");
                        membersSelect.empty();

                        //Obtenermos los usuarios que están asociados al grupo
                        var groupId = data.group.parent_id
                        var url = '/admin/globales/get-users/';
                        initializeSelect2(membersSelect, 'Seleccione Miembros', url);

                        //Llamamos los datos precargados desde el controlador
                        data.membersChecked.forEach(function(d) {
                            var option = new Option(d.text, d.id, true, true);
                            membersSelect.append(option).trigger('change');
                            membersSelect.trigger({
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
                        data: $('#subGroupForm').serialize(),
                        url: "{{ route('globales.groups.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('#subGroupForm').trigger("reset");
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

                $('body').on('click', '.deleteSubGroup', function() {
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
                            var subGroupId = $(this).data("id");
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('globales.groups.store') }}" + '/' + subGroupId,
                                success: function(data) {
                                    table.draw();
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                });

                // ── Abrir modal de Creación Rápida ──
                $('#btnOpenQuickUserModal').click(function() {
                    $('#quickUserForm').trigger('reset');
                    $('#quick_user_password').val('12345678');
                    $('#quickUserModal').modal('show');
                });

                // ── Submit de Creación Rápida de Usuario ──
                $('#quickUserForm').on('submit', function(e) {
                    e.preventDefault();
                    var $btn = $('#saveQuickUserBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

                    $.ajax({
                        url: "{{ route('globales.users.store') }}",
                        type: "POST",
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(res) {
                            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar y Seleccionar');
                            if (res.user) {
                                var newOption = new Option(res.user.name + ' (' + res.user.email + ')', res.user.id, true, true);
                                $('#members').append(newOption).trigger('change');
                                $('#quickUserModal').modal('hide');
                                if (typeof toastr !== 'undefined') {
                                    toastr.success('Usuario ' + res.user.name + ' creado y agregado al grupo.');
                                } else {
                                    alert('Usuario ' + res.user.name + ' creado y agregado al grupo.');
                                }
                            }
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar y Seleccionar');
                            var errs = xhr.responseJSON?.errors;
                            if (errs) {
                                $.each(errs, function(k, v) {
                                    if (typeof toastr !== 'undefined') toastr.error(v);
                                    else alert(v);
                                });
                            } else {
                                var msg = xhr.responseJSON?.message || 'Error al crear usuario.';
                                if (typeof toastr !== 'undefined') toastr.error(msg);
                                else alert(msg);
                            }
                        }
                    });
                });
            });
        </script>
    @stop
