@extends('layouts.master')
@section('title', 'Roles y Permisos')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title">Roles y Permisos</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-light p-3 mb-0">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Roles y Permisos</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-none">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('globales.roles.guide') }}" class="btn btn-outline-secondary font-weight-bold" title="Ver manual explicativo de roles">
                                <i class="fa fa-book-open mr-1"></i> Guía de Roles
                            </a>
                            @can('role-create')
                            <button class="btn btn-success font-weight-bold" id="btnNuevoRol">
                                <i class="fa fa-plus mr-1"></i> Nuevo Rol
                            </button>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped data-table display nowrap w-100" id="tablaRoles">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 60px;">ID</th>
                                        <th>Nombre del Rol</th>
                                        <th class="text-center" style="width: 120px;">Permisos</th>
                                        <th class="text-center" style="width: 150px;">Acciones</th>
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

{{-- ══ Modal Crear / Editar Rol ═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalRol" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalRolTitulo">
                    <i class="fa fa-shield-alt mr-2"></i> Nuevo Rol
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rol_id">

                <div class="form-group">
                    <label class="font-weight-bold">
                        Nombre del Rol <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="rol_nombre" class="form-control"
                           placeholder="Ej: Analista de Dependencias">
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold d-flex align-items-center justify-content-between">
                        <span>Permisos</span>
                        <div style="gap:.4rem" class="d-flex">
                            <button type="button" class="btn btn-xs btn-outline-success" id="btnSelTodos" style="font-size:.72rem;padding:2px 8px">
                                <i class="fa fa-check-square mr-1"></i>Todos
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" id="btnDeselTodos" style="font-size:.72rem;padding:2px 8px">
                                <i class="fa fa-square mr-1"></i>Ninguno
                            </button>
                        </div>
                    </label>

                    {{-- Agrupar permisos por prefijo --}}
                    @php
                        $permisosAgrupados = $permissions->groupBy(function($p) {
                            $parts = explode('-', $p->name);
                            return count($parts) > 1 ? $parts[0] : 'general';
                        })->sortKeys();
                    @endphp

                    <style>
                        .perm-pill {
                            display: inline-flex;
                            align-items: center;
                            gap: .3rem;
                            padding: .28rem .65rem;
                            border-radius: 20px;
                            font-size: .75rem;
                            cursor: pointer;
                            border: 1.5px solid #d0d0d0;
                            background: #f8f9fa;
                            color: #555;
                            transition: all .15s;
                            user-select: none;
                        }
                        .perm-pill:hover {
                            border-color: #1a237e;
                            color: #1a237e;
                        }
                        .perm-pill.activo {
                            background: #1a237e;
                            border-color: #1a237e;
                            color: #fff;
                        }
                        .perm-pill.activo .perm-pill-icon::before { content: '\f00c'; }
                        .perm-pill-icon { font-family: 'Font Awesome 5 Free'; font-weight: 900; font-size: .65rem; }
                        .perm-pill:not(.activo) .perm-pill-icon::before { content: '\f111'; color: #ccc; }
                        .perm-group-header {
                            display: flex;
                            align-items: center;
                            gap: .4rem;
                            padding: .3rem .5rem;
                            background: #f0f0f0;
                            border-radius: 6px;
                            margin-bottom: .4rem;
                            cursor: pointer;
                        }
                        .perm-group-header:hover { background: #e8eaf6; }
                        .perm-group-header .grupo-label {
                            font-size: .7rem;
                            font-weight: 700;
                            text-transform: uppercase;
                            letter-spacing: .04em;
                            color: #444;
                        }
                        .perm-group-header .grupo-count {
                            font-size: .68rem;
                            color: #888;
                            margin-left: auto;
                        }
                        .perm-group-header .grupo-check-icon {
                            font-size: .68rem;
                            color: #1a237e;
                        }
                    </style>

                    <div id="permisosContainer" style="max-height:380px;overflow-y:auto;border:1px solid #e0e0e0;border-radius:8px;padding:.5rem .75rem">
                        @foreach($permisosAgrupados as $grupo => $permisos)
                        <div class="mb-2 perm-group" data-grupo="{{ $grupo }}">
                            <div class="perm-group-header btnSelGrupo" data-grupo="{{ $grupo }}">
                                <i class="fa fa-layer-group" style="font-size:.7rem;color:#1a237e"></i>
                                <span class="grupo-label">{{ $grupo }}</span>
                                <span class="grupo-count">{{ $permisos->count() }} permisos</span>
                                <i class="fa fa-check-double grupo-check-icon" title="Seleccionar todos"></i>
                            </div>
                            <div class="d-flex flex-wrap" style="gap:.3rem .4rem;padding:.2rem .25rem .4rem">
                                @foreach($permisos as $perm)
                                {{-- checkbox oculto real --}}
                                <input type="checkbox"
                                       class="perm-check d-none"
                                       name="permission[]"
                                       data-grupo="{{ $grupo }}"
                                       value="{{ $perm->id }}"
                                       id="perm_{{ $perm->id }}">
                                {{-- pill visual --}}
                                <span class="perm-pill"
                                      data-perm-id="{{ $perm->id }}"
                                      title="{{ $perm->name }}">
                                    <i class="perm-pill-icon"></i>
                                    {{ $perm->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="fa fa-info-circle mr-1"></i>
                        <span id="permisosSelCount">0</span> permisos seleccionados — clic para activar/desactivar
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success font-weight-bold" id="btnGuardarRol">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ Modal Ver Permisos ══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalVerPermisos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content shadow">
            <div class="modal-header py-2" style="background:#f5f5f5;border-bottom:1px solid #e0e0e0">
                <h6 class="modal-title mb-0 font-weight-bold" id="modalVerPermisosTitulo">
                    <i class="fa fa-list mr-1 text-primary"></i> Permisos del Rol
                </h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="modalVerPermisosBody">
                <div class="text-center py-3">
                    <i class="fa fa-spinner fa-spin text-muted"></i>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
                @can('role-edit')
                <button type="button" class="btn btn-sm btn-primary font-weight-bold" id="btnEditarDesdeVer">
                    <i class="fa fa-edit mr-1"></i> Editar este rol
                </button>
                @endcan
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var _rolIdViendo = null;

    // ── Inicializar DataTables ───────────────────────────────────────────────
    var table = $('#tablaRoles').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        dom: "<'row mb-3'<'col-md-6'l><'col-md-6'f>>" +
             "<'row'<'col-12'tr>>" +
             "<'row align-items-center'<'col-md-6'i><'col-md-6'p>>",
        language: {
            "decimal": "",
            "emptyTable": "No hay roles registrados",
            "info": "Mostrando <strong>_START_</strong> a <strong>_END_</strong> de <strong>_TOTAL_</strong> roles",
            "infoEmpty": "Mostrando 0 a 0 de 0 roles",
            "infoFiltered": "(filtrado de <strong>_MAX_</strong> total)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar <strong>_MENU_</strong> roles",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron roles",
            "paginate": {
                "first": "«",
                "last": "»",
                "next": "›",
                "previous": "‹"
            }
        },
        ajax: "{{ route('globales.roles.index') }}",
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                className: 'text-center'
            },
            {
                data: 'name',
                name: 'name',
                render: function(data, type, row) {
                    return '<div class="font-weight-bold text-dark"><i class="fa fa-user-shield text-info mr-2"></i>' + data + '</div>';
                }
            },
            {
                data: 'permisos',
                name: 'permisos',
                className: 'text-center'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ]
    });

    // ── Contador de permisos seleccionados ──────────────────────────────────
    function actualizarContador() {
        var n = $('.perm-check:checked').length;
        $('#permisosSelCount').text(n);
    }

    // ── Toggle pill individual ───────────────────────────────────────────────
    $(document).on('click', '.perm-pill', function() {
        var permId = $(this).data('perm-id');
        var $check = $('#perm_' + permId);
        var checked = !$check.prop('checked');
        $check.prop('checked', checked);
        $(this).toggleClass('activo', checked);
        actualizarContador();
    });

    // ── Seleccionar / deseleccionar todos ───────────────────────────────────
    $('#btnSelTodos').on('click', function() {
        $('.perm-check').prop('checked', true);
        $('.perm-pill').addClass('activo');
        actualizarContador();
    });
    $('#btnDeselTodos').on('click', function() {
        $('.perm-check').prop('checked', false);
        $('.perm-pill').removeClass('activo');
        actualizarContador();
    });

    // ── Seleccionar grupo (clic en header) ───────────────────────────────────
    $(document).on('click', '.btnSelGrupo', function() {
        var grupo = $(this).data('grupo');
        var $checks = $('.perm-check[data-grupo="' + grupo + '"]');
        var todosChecked = $checks.filter(':checked').length === $checks.length;
        $checks.prop('checked', !todosChecked);
        $checks.each(function() {
            var pid = $(this).val();
            $('.perm-pill[data-perm-id="' + pid + '"]').toggleClass('activo', !todosChecked);
        });
        actualizarContador();
    });

    // ── Abrir modal nuevo ────────────────────────────────────────────────────
    $(document).on('click', '#btnNuevoRol', function() {
        $('#rol_id').val('');
        $('#rol_nombre').val('');
        $('.perm-check').prop('checked', false);
        $('.perm-pill').removeClass('activo');
        actualizarContador();
        $('#modalRolTitulo').html('<i class="fa fa-shield-alt mr-2"></i> Nuevo Rol');
        $('#modalRol').modal('show');
    });

    // ── Abrir modal editar ───────────────────────────────────────────────────
    $(document).on('click', '.btnEditarRol', function() {
        var id = $(this).data('id');
        abrirEditar(id);
    });

    function abrirEditar(id) {
        $('#modalVerPermisos').modal('hide');
        $.getJSON('{{ route("globales.roles.edit-ajax", ":id") }}'.replace(':id', id), function(data) {
            $('#rol_id').val(data.role.id);
            $('#rol_nombre').val(data.role.name);
            $('.perm-check').prop('checked', false);
            $('.perm-pill').removeClass('activo');
            if (data.rolePermissions && data.rolePermissions.length) {
                data.rolePermissions.forEach(function(pid) {
                    $('#perm_' + pid).prop('checked', true);
                    $('.perm-pill[data-perm-id="' + pid + '"]').addClass('activo');
                });
            }
            actualizarContador();
            $('#modalRolTitulo').html('<i class="fa fa-edit mr-2"></i> Editar: <strong>' + data.role.name + '</strong>');
            $('#modalRol').modal('show');
        });
    }

    // ── Guardar (crear o actualizar) ─────────────────────────────────────────
    $(document).on('click', '#btnGuardarRol', function() {
        var id     = $('#rol_id').val();
        var nombre = $.trim($('#rol_nombre').val());
        if (!nombre) {
            if (typeof toastr !== 'undefined') toastr.warning('El nombre del rol es obligatorio.');
            else alert('El nombre del rol es obligatorio.');
            return;
        }

        var permisos = [];
        $('.perm-check:checked').each(function() { permisos.push($(this).val()); });

        var url    = id ? '{{ route("globales.roles.update", ":id") }}'.replace(':id', id) : '{{ route("globales.roles.store") }}';
        var method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: { name: nombre, permission: permisos },
            success: function(res) {
                if (typeof toastr !== 'undefined') toastr.success(res.message || 'Guardado correctamente.');
                else alert(res.message || 'Guardado correctamente.');
                $('#modalRol').modal('hide');
                table.ajax.reload(null, false);
            },
            error: function(xhr) {
                var e = xhr.responseJSON?.errors;
                if (e) {
                    $.each(e, function(k, v) {
                        if (typeof toastr !== 'undefined') toastr.error(v[0]);
                        else alert(v[0]);
                    });
                } else {
                    var msg = xhr.responseJSON?.message || 'Error al guardar el rol.';
                    if (typeof toastr !== 'undefined') toastr.error(msg);
                    else alert(msg);
                }
            }
        });
    });

    // ── Ver permisos ─────────────────────────────────────────────────────────
    $(document).on('click', '.btnVerPermisos', function() {
        var id     = $(this).data('id');
        var nombre = $(this).data('nombre');
        _rolIdViendo = id;
        $('#modalVerPermisosTitulo').html('<i class="fa fa-shield-alt mr-1 text-primary"></i> Permisos de: ' + nombre);
        $('#modalVerPermisosBody').html('<div class="text-center py-3"><i class="fa fa-spinner fa-spin text-muted"></i> Cargando permisos...</div>');
        $('#modalVerPermisos').modal('show');

        $.getJSON('{{ route("globales.roles.show-ajax", ":id") }}'.replace(':id', id), function(data) {
            var $body = $('#modalVerPermisosBody').empty();
            if (!data.permissions || !data.permissions.length) {
                $body.html('<p class="text-muted text-center py-2">Este rol no tiene permisos asignados.</p>');
                return;
            }
            var grupos = {};
            data.permissions.forEach(function(p) {
                var key = p.name.split('-')[0] || 'general';
                if (!grupos[key]) grupos[key] = [];
                grupos[key].push(p.name);
            });
            $.each(grupos, function(grupo, perms) {
                var $grupo = $('<div class="mb-3">').appendTo($body);
                $('<div>').append(
                    $('<span class="badge badge-secondary mr-2" style="font-size:.7rem;text-transform:uppercase">').text(grupo)
                ).appendTo($grupo);
                var $tags = $('<div class="d-flex flex-wrap mt-1" style="gap:.3rem">').appendTo($grupo);
                perms.forEach(function(p) {
                    $tags.append($('<span class="badge badge-light border" style="font-size:.75rem">').text(p));
                });
            });
        });
    });

    $(document).on('click', '#btnEditarDesdeVer', function() {
        if (_rolIdViendo) abrirEditar(_rolIdViendo);
    });

    // ── Eliminar ─────────────────────────────────────────────────────────────
    $(document).on('click', '.btnEliminarRol', function() {
        var id     = $(this).data('id');
        var nombre = $(this).data('nombre');
        if (confirm('¿Estás seguro de eliminar el rol "' + nombre + '"?')) {
            $.ajax({
                url: '{{ route("globales.roles.destroy", ":id") }}'.replace(':id', id),
                type: 'DELETE',
                success: function(res) {
                    if (typeof toastr !== 'undefined') toastr.success('Rol eliminado correctamente.');
                    else alert('Rol eliminado correctamente.');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    if (typeof toastr !== 'undefined') toastr.error('No se pudo eliminar el rol.');
                    else alert('No se pudo eliminar el rol.');
                }
            });
        }
    });
});
</script>
@stop
