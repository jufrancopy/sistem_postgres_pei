@extends('layouts.master')
@section('title', 'Roles y Permisos')

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex align-items-center">
        <div>
            <h4 class="card-title mb-0"><i class="fa fa-shield-alt mr-2"></i>Roles y Permisos</h4>
            <small class="text-white" style="opacity:.8">Gestión de roles del sistema</small>
        </div>
        @can('role-create')
        <button class="btn btn-sm btn-light ml-auto" id="btnNuevoRol">
            <i class="fa fa-plus mr-1"></i> Nuevo Rol
        </button>
        @endcan
    </div>

    <div class="card-body p-0">

        {{-- Buscador --}}
        <div class="px-3 pt-3 pb-2 d-flex align-items-center" style="gap:.5rem">
            <div class="input-group input-group-sm" style="max-width:280px">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
                <input type="text" id="buscarRol" class="form-control" placeholder="Buscar rol…">
            </div>
            <small class="text-muted ml-2">
                <span id="totalRoles">{{ $roles->total() }}</span> roles registrados
            </small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" id="tablaRoles">
                <thead class="thead-light">
                    <tr>
                        <th style="width:50px" class="text-center">#</th>
                        <th>Nombre del Rol</th>
                        <th class="text-center" style="width:100px">Permisos</th>
                        <th class="text-center" style="width:130px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $role)
                    @php
                        $permCount = $role->permissions->count();
                        $colorBadge = $permCount > 10 ? 'danger' : ($permCount > 5 ? 'warning' : ($permCount > 0 ? 'success' : 'secondary'));
                    @endphp
                    <tr id="row-{{ $role->id }}">
                        <td class="text-center text-muted" style="font-size:.8rem">{{ $loop->iteration + ($roles->currentPage()-1)*$roles->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:.5rem">
                                <span class="role-icon d-flex align-items-center justify-content-center"
                                      style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#1a237e,#283593);flex-shrink:0">
                                    <i class="fa fa-user-tag text-white" style="font-size:.7rem"></i>
                                </span>
                                <span class="font-weight-bold" style="font-size:.88rem">{{ $role->name }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-link p-0 btnVerPermisos"
                                    data-id="{{ $role->id }}"
                                    data-nombre="{{ $role->name }}"
                                    title="Ver permisos">
                                <span class="badge badge-{{ $colorBadge }}">
                                    {{ $permCount }} {{ $permCount == 1 ? 'permiso' : 'permisos' }}
                                </span>
                            </button>
                        </td>
                        <td class="text-center" style="white-space:nowrap">
                            @can('role-edit')
                            <button class="btn btn-sm btn-outline-primary py-0 px-2 btnEditarRol"
                                    data-id="{{ $role->id }}"
                                    title="Editar">
                                <i class="fa fa-edit" style="font-size:.75rem"></i>
                            </button>
                            @endcan
                            @can('role-delete')
                            <button class="btn btn-sm btn-outline-danger py-0 px-2 btnEliminarRol"
                                    data-id="{{ $role->id }}"
                                    data-nombre="{{ $role->name }}"
                                    title="Eliminar">
                                <i class="fa fa-trash" style="font-size:.75rem"></i>
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-3 py-2 d-flex justify-content-end">
            {{ $roles->links() }}
        </div>
    </div>
</div>

{{-- ══ Modal Crear / Editar Rol ═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalRol" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white mb-0" id="modalRolTitulo">
                    <i class="fa fa-shield-alt mr-2"></i> Nuevo Rol
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
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
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnGuardarRol">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ Modal Ver Permisos ══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalVerPermisos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
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
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                @can('role-edit')
                <button type="button" class="btn btn-sm btn-primary" id="btnEditarDesdeVer">
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
        var $pills  = $('.perm-pill[data-perm-id]').filter(function() {
            return $('#perm_' + $(this).data('perm-id')).data('grupo') === grupo;
        });
        var todosChecked = $checks.filter(':checked').length === $checks.length;
        $checks.prop('checked', !todosChecked);
        // Actualizar pills del grupo
        $checks.each(function() {
            var pid = $(this).val();
            $('.perm-pill[data-perm-id="' + pid + '"]').toggleClass('activo', !todosChecked);
        });
        actualizarContador();
    });

    // ── Filtro búsqueda ──────────────────────────────────────────────────────
    $('#buscarRol').on('input', function() {
        var q = $(this).val().toLowerCase();
        $('#tablaRoles tbody tr').each(function() {
            var nombre = $(this).find('td:eq(1)').text().toLowerCase();
            $(this).toggle(nombre.indexOf(q) !== -1);
        });
    });

    // ── Abrir modal nuevo ────────────────────────────────────────────────────
    $('#btnNuevoRol').on('click', function() {
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
            // Limpiar todo
            $('.perm-check').prop('checked', false);
            $('.perm-pill').removeClass('activo');
            // Marcar los que tiene el rol
            data.rolePermissions.forEach(function(pid) {
                $('#perm_' + pid).prop('checked', true);
                $('.perm-pill[data-perm-id="' + pid + '"]').addClass('activo');
            });
            actualizarContador();
            $('#modalRolTitulo').html('<i class="fa fa-edit mr-2"></i> Editar: <strong>' + data.role.name + '</strong>');
            $('#modalRol').modal('show');
        });
    }

    // ── Guardar (crear o actualizar) ─────────────────────────────────────────
    $('#btnGuardarRol').on('click', function() {
        var id     = $('#rol_id').val();
        var nombre = $.trim($('#rol_nombre').val());
        if (!nombre) { toastr.warning('El nombre del rol es obligatorio.'); return; }

        var permisos = [];
        $('.perm-check:checked').each(function() { permisos.push($(this).val()); });

        var url    = id ? '{{ route("globales.roles.update", ":id") }}'.replace(':id', id) : '{{ route("globales.roles.store") }}';
        var method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url, type: method,
            data: { name: nombre, permission: permisos },
            success: function(res) {
                toastr.success(res.message || 'Guardado correctamente.');
                $('#modalRol').modal('hide');
                // Actualizar fila o agregar nueva
                if (res.row) {
                    if (id && $('#row-' + id).length) {
                        $('#row-' + id).replaceWith(res.row);
                    } else {
                        $('#tablaRoles tbody').prepend(res.row);
                        $('#totalRoles').text(parseInt($('#totalRoles').text()) + 1);
                    }
                } else {
                    setTimeout(() => location.reload(), 600);
                }
            },
            error: function(xhr) {
                var e = xhr.responseJSON?.errors;
                if (e) $.each(e, (k,v) => toastr.error(v[0]));
                else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
            }
        });
    });

    // ── Ver permisos ─────────────────────────────────────────────────────────
    $(document).on('click', '.btnVerPermisos', function() {
        var id     = $(this).data('id');
        var nombre = $(this).data('nombre');
        _rolIdViendo = id;
        $('#modalVerPermisosTitulo').html(
            '<i class="fa fa-shield-alt mr-1 text-primary"></i> ' + nombre
        );
        $('#modalVerPermisosBody').html(
            '<div class="text-center py-3"><i class="fa fa-spinner fa-spin text-muted"></i></div>'
        );
        $('#modalVerPermisos').modal('show');

        $.getJSON('{{ route("globales.roles.show-ajax", ":id") }}'.replace(':id', id), function(data) {
            var $body = $('#modalVerPermisosBody').empty();
            if (!data.permissions.length) {
                $body.html('<p class="text-muted text-center py-2">Este rol no tiene permisos asignados.</p>');
                return;
            }
            // Agrupar por prefijo
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
                    $tags.append(
                        $('<span class="badge badge-light border" style="font-size:.75rem">').text(p)
                    );
                });
            });
        });
    });

    $('#btnEditarDesdeVer').on('click', function() {
        if (_rolIdViendo) abrirEditar(_rolIdViendo);
    });

    // ── Eliminar ─────────────────────────────────────────────────────────────
    $(document).on('click', '.btnEliminarRol', function() {
        var id     = $(this).data('id');
        var nombre = $(this).data('nombre');
        Swal.fire({
            title: '¿Eliminar el rol?',
            html: '<strong>' + nombre + '</strong><br><small class="text-muted">Se eliminarán todas las asignaciones asociadas.</small>',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
        }).then(function(result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: '{{ route("globales.roles.destroy", ":id") }}'.replace(':id', id), type: 'DELETE',
                success: function(res) {
                    if (res.ok) {
                        $('#row-' + id).fadeOut(300, function() { $(this).remove(); });
                        $('#totalRoles').text(Math.max(0, parseInt($('#totalRoles').text()) - 1));
                        toastr.success('Rol eliminado correctamente.');
                    } else {
                        toastr.error(res.message || 'No se pudo eliminar.');
                    }
                },
                error: function() { toastr.error('Error al eliminar.'); }
            });
        });
    });
});
</script>
@stop
