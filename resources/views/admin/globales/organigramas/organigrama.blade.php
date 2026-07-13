@extends('layouts.master')
@section('title', 'Gestionar — ' . $dependencia->dependency)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-sitemap mr-2"></i>{{ $dependencia->dependency }}
        </h4>
        <p class="card-category">Arrastrá los nodos para reorganizar la estructura</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('globales.organigramas.index') }}">Organigramas</a></li>
            <li class="breadcrumb-item active">{{ $dependencia->dependency }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Acciones ── --}}
        <div class="d-flex flex-wrap mb-4 align-items-center">
            <button class="btn btn-success mr-2 mb-2" id="btnAgregarHijo"
                    data-id="{{ $dependencia->id }}"
                    data-nombre="{{ $dependencia->dependency }}">
                <i class="fa fa-plus mr-1"></i> Agregar dependencia
            </button>
            <a href="{{ route('globales.organigramas.show', $dependencia->id) }}" class="btn btn-info mr-2 mb-2">
                <i class="fa fa-eye mr-1"></i> Ver visual
            </a>
            <a href="{{ route('globales.organigramas.edit', $dependencia->id) }}" class="btn btn-primary mr-2 mb-2">
                <i class="fa fa-edit mr-1"></i> Editar raíz
            </a>
            <div class="ml-auto mb-2">
                <span class="badge badge-light border p-2">
                    <i class="fa fa-info-circle text-info mr-1"></i>
                    Arrastrá por el ícono <i class="fa fa-grip-vertical"></i> para mover nodos
                </span>
            </div>
        </div>

        {{-- ── Árbol drag & drop ── --}}
        <div class="card shadow">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fa fa-project-diagram mr-1"></i>
                    Estructura jerárquica
                    <span class="badge badge-secondary ml-1">{{ $dependencia->descendants()->count() }} dependencias</span>
                </h6>
                <small class="text-muted">
                    <i class="fa fa-mouse-pointer mr-1"></i> Soltá sobre el nombre de la dependencia destino
                </small>
            </div>
            <div class="card-body p-2">
                <div id="arbolOrganigrama">
                    @include('admin.globales.organigramas.partials.nodo_draggable', [
                        'nodos' => $dependencia->children,
                        'nivel' => 0,
                    ])
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ── Modal Agregar/Editar ── --}}
<div class="modal fade" id="modalDependencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalDepTitulo">Agregar Dependencia</h4>
            </div>
            <div class="modal-body">
                <form id="formDependencia">
                    @csrf
                    <input type="hidden" id="dep_id" name="dep_id">
                    <input type="hidden" id="dep_parent_id" name="parent_id">
                    <input type="hidden" id="dep_method" name="_method" value="POST">

                    <div class="form-group">
                        <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="dependency" id="dep_dependency" class="form-control" required
                               placeholder="Ej: Dirección de Tecnología">
                    </div>

                    {{-- Selector de usuario del sistema --}}
                    <div class="form-group">
                        <label class="font-weight-bold">
                            Responsable del sistema
                            <span class="text-muted font-weight-normal" style="font-size:.8rem">
                                (vinculá un usuario del sistema)
                            </span>
                        </label>
                        <select id="dep_user_id" name="user_id" style="width:100%"></select>
                        <small class="text-muted">Escribí al menos 2 letras para buscar por nombre o correo.</small>
                    </div>

                    <div class="form-group">
                        <label>Nombre del responsable</label>
                        <input type="text" name="manager" id="dep_manager" class="form-control"
                               placeholder="Se completa al seleccionar usuario, o escribí manualmente">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="phone" id="dep_phone" class="form-control" placeholder="000000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correo</label>
                                <input type="email" name="email" id="dep_email" class="form-control"
                                       placeholder="correo@ips.gov.py">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="btnGuardarDep">
                            <i class="fa fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Indicador de movimiento ── --}}
<div id="moveIndicator" style="display:none;position:fixed;bottom:20px;right:20px;z-index:9999">
    <div class="alert alert-info shadow mb-0 py-2 px-3">
        <i class="fa fa-arrows-alt mr-2"></i>
        <span id="moveText">Moviendo...</span>
    </div>
</div>

@stop

@section('scripts')
{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    // ── Inicializar drag & drop en todos los grupos ───────────────────────────
    function initSortable() {
        document.querySelectorAll('.sortable-group').forEach(function(el) {
            if (el._sortable) return; // evitar doble init

            el._sortable = Sortable.create(el, {
                group: 'organigrama',          // permite mover entre grupos
                handle: '.drag-handle',         // solo arrastrando el ícono
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                fallbackOnBody: true,
                swapThreshold: 0.65,

                onStart: function(evt) {
                    var nombre = $(evt.item).find('.dep-nombre').first().text().trim();
                    $('#moveText').text('Moviendo: ' + nombre);
                    $('#moveIndicator').fadeIn(200);
                },

                onEnd: function(evt) {
                    $('#moveIndicator').fadeOut(200);

                    var nodoId = $(evt.item).data('id');

                    // evt.to es el <ul> destino — su padre <li> tiene el data-id del nodo padre
                    var $toUl        = $(evt.to);
                    var $parentLi    = $toUl.closest('li.nodo-item');
                    var nuevoParentId = $parentLi.length ? $parentLi.data('id') : null;

                    // Si no cambió nada, ignorar
                    if (evt.from === evt.to && evt.oldIndex === evt.newIndex) return;

                    // No permitir soltar en nivel raíz (fuera de cualquier li)
                    if (!nuevoParentId) {
                        toastr.warning('No se puede mover a nivel raíz. Soltá dentro de una dependencia.');
                        location.reload();
                        return;
                    }

                    // No mover sobre sí mismo
                    if (nuevoParentId == nodoId) {
                        location.reload();
                        return;
                    }

                    var nombreNodo  = $(evt.item).find('> .nodo-row .dep-nombre').text().trim();
                    var nombrePadre = $parentLi.find('> .nodo-row .dep-nombre').text().trim();

                    Swal.fire({
                        title: '¿Confirmar movimiento?',
                        html: '<strong>' + nombreNodo + '</strong><br><i class="fa fa-arrow-down text-muted"></i> Nuevo padre: <strong>' + nombrePadre + '</strong>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, mover',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#28a745',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.post('{{ url('admin/globales/organigramas') }}/' + nodoId + '/mover', {
                                parent_id: nuevoParentId
                            }, function(res) {
                                toastr.success(res.success);
                                setTimeout(function() { location.reload(); }, 600);
                            }).fail(function(xhr) {
                                toastr.error(xhr.responseJSON?.error || 'Error al mover.');
                                location.reload();
                            });
                        } else {
                            location.reload();
                        }
                    });
                }
            });
        });
    }

    initSortable();

    // ── Expandir/colapsar hijos ───────────────────────────────────────────────
    $('body').on('click', '.btn-toggle', function(e) {
        e.stopPropagation();
        var $children = $(this).closest('.nodo-item').find('> .nodo-children');
        var $icon = $(this).find('i');
        $children.slideToggle(150);
        $icon.toggleClass('fa-chevron-down fa-chevron-right');
    });

    // ── Agregar hijo del nodo raíz ────────────────────────────────────────────
    $('#btnAgregarHijo').on('click', function() {
        abrirModalCrear($(this).data('id'), $(this).data('nombre'));
    });

    $('body').on('click', '.btnAgregarSub', function(e) {
        e.stopPropagation();
        abrirModalCrear($(this).data('id'), $(this).data('nombre'));
    });

    // ── Inicializar Select2 de usuario ────────────────────────────────────────
    function initUserSelect(userId, userName, userEmail) {
        var $sel = $('#dep_user_id');
        if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
        $sel.empty();

        $sel.select2({
            dropdownParent: $('#modalDependencia'),
            placeholder: '— Buscar por nombre o correo —',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("globales.usuarios.buscar") }}',
                dataType: 'json',
                delay: 250,
                data: function(p) { return { q: p.term }; },
                processResults: function(data) {
                    return { results: data.map(function(u) {
                        return { id: u.id, text: u.name, email: u.email, name: u.name };
                    })};
                }
            },
            templateResult: function(u) {
                if (u.loading) return u.text;
                return $('<span><i class="fa fa-user mr-1 text-muted"></i><strong>' + u.text + '</strong>'
                    + (u.email ? ' <small class="text-muted ml-1">— ' + u.email + '</small>' : '') + '</span>');
            }
        });

        if (userId) {
            var label = (userName || 'Usuario #' + userId) + (userEmail ? ' — ' + userEmail : '');
            $sel.append(new Option(label, userId, true, true)).trigger('change');
        }

        $sel.off('select2:select').on('select2:select', function(e) {
            var d = e.params.data;
            $('#dep_manager').val(d.name || '');
            $('#dep_email').val(d.email || '');
        });
    }

    function abrirModalCrear(parentId, parentNombre) {
        $('#modalDepTitulo').text('Agregar en: ' + parentNombre);
        $('#formDependencia')[0].reset();
        $('#dep_id').val('');
        $('#dep_parent_id').val(parentId);
        $('#dep_method').val('POST');
        $('#modalDependencia').modal('show');
        initUserSelect(null, null, null);
    }

    // ── Editar ────────────────────────────────────────────────────────────────
    $('body').on('click', '.btnEditarDep', function(e) {
        e.stopPropagation();
        var id = $(this).data('id');
        $.get('{{ url('admin/globales/get-dependency') }}/' + id, function(dep) {
            $('#modalDepTitulo').text('Editar: ' + dep.dependency);
            $('#dep_id').val(dep.id);
            $('#dep_parent_id').val(dep.parent_id);
            $('#dep_method').val('PUT');
            $('#dep_dependency').val(dep.dependency);
            $('#dep_manager').val(dep.manager || '');
            $('#dep_phone').val(dep.phone || '');
            $('#dep_email').val(dep.email || '');
            $('#modalDependencia').modal('show');
            initUserSelect(dep.user_id, dep.manager, dep.email);
        });
    });

    // ── Guardar ───────────────────────────────────────────────────────────────
    $('#formDependencia').on('submit', function(e) {
        e.preventDefault();
        var id  = $('#dep_id').val();
        var url = id
            ? '{{ url('admin/globales/organigramas') }}/' + id
            : '{{ route('globales.organigramas.store') }}';

        $('#btnGuardarDep').html('<i class="fa fa-spinner fa-spin mr-1"></i>').prop('disabled', true);

        $.ajax({
            url: url, type: 'POST', data: $(this).serialize(),
            success: function() {
                toastr.success(id ? 'Actualizado.' : 'Creado.');
                $('#modalDependencia').modal('hide');
                setTimeout(function() { location.reload(); }, 600);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error.');
                $('#btnGuardarDep').html('<i class="fa fa-save mr-1"></i> Guardar').prop('disabled', false);
            }
        });
    });

    // ── Eliminar ──────────────────────────────────────────────────────────────
    $('body').on('click', '.btnEliminarDep', function(e) {
        e.stopPropagation();
        var id = $(this).data('id'), nombre = $(this).data('nombre');
        Swal.fire({
            title: '¿Eliminar ' + nombre + '?',
            text: 'Se eliminarán también todas sus sub-dependencias.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc3545', confirmButtonText: 'Sí, eliminar',
        }).then(function(r) {
            if (r.isConfirmed) {
                $.ajax({
                    url: '{{ url('admin/globales/organigramas') }}/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                    success: function() { toastr.success('Eliminado.'); setTimeout(function() { location.reload(); }, 600); },
                    error: function() { toastr.error('Error al eliminar.'); }
                });
            }
        });
    });
});
</script>

<style>
/* ── Árbol ── */
.nodo-item { list-style: none; }
.nodo-children { padding-left: 24px; border-left: 2px dashed #dee2e6; margin-left: 12px; }

/* ── Fila del nodo ── */
.nodo-row {
    display: flex; align-items: center;
    padding: 6px 8px; margin: 3px 0;
    background: #fff; border: 1px solid #e9ecef;
    border-radius: 6px; transition: box-shadow .15s;
}
.nodo-row:hover { box-shadow: 0 2px 8px rgba(0,0,0,.1); border-color: #adb5bd; }

/* ── Handle de arrastre ── */
.drag-handle {
    cursor: grab; color: #adb5bd; padding: 0 8px;
    font-size: 1rem; flex-shrink: 0;
}
.drag-handle:hover { color: #495057; }
.drag-handle:active { cursor: grabbing; }

/* ── Colores por nivel ── */
.nivel-badge-0 { background: #1a3a5c; }
.nivel-badge-1 { background: #2c5f8a; }
.nivel-badge-2 { background: #28a745; }
.nivel-badge-3 { background: #17a2b8; }
.nivel-badge-4 { background: #fd7e14; }

/* ── Estados drag ── */
.sortable-ghost  { opacity: .4; background: #e3f2fd !important; border: 2px dashed #2196f3 !important; }
.sortable-chosen { box-shadow: 0 4px 16px rgba(0,0,0,.2) !important; }
.sortable-drag   { opacity: .9; }
</style>
@stop
