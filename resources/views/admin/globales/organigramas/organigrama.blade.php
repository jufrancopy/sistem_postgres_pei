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
                <div id="arbolOrganigrama" data-root-id="{{ $dependencia->id }}" data-root-name="{{ $dependencia->dependency }}">
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

                    @php
                        $tipologiasRiissOrg = \App\Models\Riiss\ReglaSeccionFormulario::select('tipologia_clasificacion')->distinct()->whereNotNull('tipologia_clasificacion')->where('tipologia_clasificacion', '!=', '')->orderBy('tipologia_clasificacion')->pluck('tipologia_clasificacion');
                        if ($tipologiasRiissOrg->isEmpty()) {
                            $tipologiasRiissOrg = \App\Models\Riiss\Establecimiento::select('tipologia_clasificacion')->distinct()->whereNotNull('tipologia_clasificacion')->where('tipologia_clasificacion', '!=', '')->orderBy('tipologia_clasificacion')->pluck('tipologia_clasificacion');
                        }
                        $establecimientosRiissOrg = \App\Models\Riiss\Establecimiento::where('activo', true)->orWhereNull('activo')->orderBy('nombre_oficial')->get(['id_establecimiento', 'nombre_oficial', 'codigo', 'tipologia_clasificacion', 'departamento']);
                    @endphp

                    {{-- Checkbox Inicial: ¿Es un Establecimiento de Salud? --}}
                    <div class="p-3 mb-3 rounded border" style="background: #f0fdf4; border-color: #86efac !important;">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="custom-control-input" id="dep_es_establecimiento" name="es_establecimiento" value="1">
                            <label class="custom-control-label font-weight-bold text-dark mb-0 ml-1" for="dep_es_establecimiento" style="font-size: 0.92rem; cursor:pointer;">
                                <i class="fa fa-hospital text-success mr-1"></i> ¿Es un Establecimiento de Salud? <span class="text-muted font-weight-normal">(Conectar con RIISS)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Selector de Establecimiento de Salud RIISS --}}
                    <div class="form-group mb-3" id="grupo_establecimiento_riiss" style="display:none;">
                        <label class="font-weight-bold small text-success">
                            <i class="fa fa-search mr-1"></i> Seleccionar Establecimiento de Salud (RIISS) <span class="text-danger">*</span>
                        </label>
                        <select name="establecimiento_id" id="dep_establecimiento_id" class="form-control select2" style="width:100%">
                            <option value="">-- Buscar por código o nombre del establecimiento --</option>
                            @foreach($establecimientosRiissOrg as $est)
                                <option value="{{ $est->id_establecimiento }}"
                                    data-nombre="{{ $est->nombre_oficial }}"
                                    data-tipologia="{{ $est->tipologia_clasificacion }}"
                                    data-region="{{ $est->departamento }}">
                                    {{ $est->codigo ? '[' . $est->codigo . '] ' : '' }}{{ $est->nombre_oficial }} @if($est->tipologia_clasificacion) ({{ $est->tipologia_clasificacion }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">
                            Al seleccionar el establecimiento, se asocian y vinculan automáticamente su denominación, tipología y región oficial.
                        </small>
                    </div>

                    <div id="bloque_campos_dependencia">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="dependency" id="dep_dependency" class="form-control" required
                                   placeholder="Ej: Dirección de Tecnología">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Responsable / Encargado <span class="text-muted">(Usuarios del Sistema)</span></label>
                            <select name="user_id" id="dep_user_id" class="form-control select2" style="width:100%">
                                <option value="">-- Sin responsable asignado --</option>
                                @foreach(\App\Models\User::orderBy('name')->get() as $u)
                                    <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="manager" id="dep_manager">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Email</label>
                                    <input type="email" name="email" id="dep_email" class="form-control"
                                           placeholder="correo@ips.gov.py">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Teléfono / Interno</label>
                                    <input type="text" name="phone" id="dep_phone" class="form-control"
                                           placeholder="021-xxxxxx / Int. 123">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tipo de Establecimiento <span class="text-muted">(Tipología RIISS)</span></label>
                            <select name="tipo_establecimiento" id="dep_tipo_establecimiento" class="form-control select2" style="width:100%">
                                <option value="">-- Ninguno / Administrativo --</option>
                                @foreach($tipologiasRiissOrg as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Dirección / Región</label>
                            <input type="text" name="address" id="dep_address" class="form-control"
                                   placeholder="Dirección o Región física">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" id="btnGuardarDep">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
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

@endsection

@section('scripts')
<script>
    if (!window.jQuery && typeof jQuery !== 'undefined') {
        window.$ = jQuery;
    }

    var $ = window.jQuery;

    $(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

        function recalcularNivelesArbol() {
            var colores = ['nivel-badge-0', 'nivel-badge-1', 'nivel-badge-2', 'nivel-badge-3', 'nivel-badge-4'];

            function procesarUl($ul, nivel) {
                $ul.children('li.nodo-item').each(function () {
                    var $li = $(this);
                    var colorBadge = colores[Math.min(nivel, colores.length - 1)];
                    var $badge = $li.find('> .nodo-row .badge').first();

                    colores.forEach(function (c) { $badge.removeClass(c); });
                    $badge.addClass(colorBadge).text('N' + (nivel + 1));

                    var $childrenContainer = $li.find('> .nodo-children');
                    var $childrenUl = $childrenContainer.find('> ul.sortable-group');
                    var $toggleBtn = $li.find('> .nodo-row .btn-toggle');
                    var $emptySpacer = $li.find('> .nodo-row .empty-toggle-spacer');

                    var totalHijos = $childrenUl.children('li.nodo-item').length;
                    if (totalHijos > 0) {
                        $childrenContainer.show();
                        if ($toggleBtn.length === 0 && $emptySpacer.length) {
                            $emptySpacer.replaceWith(
                                '<button class="btn btn-link btn-toggle p-0 mr-2" style="font-size:.75rem;color:#6c757d;min-width:16px" title="Expandir/Colapsar">' +
                                '<i class="fa fa-chevron-down"></i>' +
                                '</button>'
                            );
                        }
                        procesarUl($childrenUl, nivel + 1);
                    } else {
                        if ($toggleBtn.length > 0) {
                            $toggleBtn.replaceWith('<span class="empty-toggle-spacer" style="min-width:24px;display:inline-block"></span>');
                        }
                    }
                });
            }

            var $rootUl = $('#arbolOrganigrama > ul.sortable-group');
            if ($rootUl.length) {
                procesarUl($rootUl, 0);
            }
        }

        // ── Inicializar drag & drop en todos los grupos ───────────────────────────
        function initSortable() {
            document.querySelectorAll('.sortable-group').forEach(function(el) {
                if (el._sortable) return; // evitar doble init

                el._sortable = Sortable.create(el, {
                    group: 'organigrama',          // permite mover entre grupos
                    handle: '.drag-handle',         // solo arrastrando el ícono
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    emptyInsertThreshold: 5,

                    onStart: function(evt) {
                        $('body').addClass('is-organigrama-dragging');
                        var nombre = $(evt.item).find('.dep-nombre').first().text().trim();
                        $('#moveText').text('Moviendo: ' + nombre);
                        $('#moveIndicator').fadeIn(150);
                    },

                    onEnd: function(evt) {
                        $('body').removeClass('is-organigrama-dragging');
                        $('#moveIndicator').fadeOut(150);

                        var $item = $(evt.item);
                        var nodoId = $item.data('id');

                        // evt.to es el <ul> destino — su padre <li> tiene el data-id del nodo padre
                        var $toUl        = $(evt.to);
                        var $parentLi    = $toUl.closest('li.nodo-item');
                        var rootId       = $('#arbolOrganigrama').data('root-id') || {{ $dependencia->id }};
                        var rootName     = $('#arbolOrganigrama').data('root-name') || '{{ e($dependencia->dependency) }} (Raíz)';

                        var nuevoParentId = $parentLi.length ? $parentLi.data('id') : rootId;
                        var nombrePadre   = $parentLi.length ? $parentLi.find('> .nodo-row .dep-nombre').text().trim() : rootName;

                        // Si no cambió nada, ignorar
                        if (evt.from === evt.to && evt.oldIndex === evt.newIndex) return;

                        // No mover sobre sí mismo
                        if (nuevoParentId == nodoId) {
                            revertirNodo(evt);
                            return;
                        }

                        var $prev = $item.prev('li.nodo-item');
                        var $next = $item.next('li.nodo-item');
                        var beforeId = $next.length ? $next.data('id') : null;
                        var afterId = $prev.length ? $prev.data('id') : null;

                        var moverUrl = '{{ route('organigramas.mover', ['id' => ':id']) }}'.replace(':id', nodoId);

                        $.ajax({
                            url: moverUrl,
                            type: 'POST',
                            data: {
                                parent_id: nuevoParentId,
                                before_id: beforeId,
                                after_id: afterId
                            },
                            success: function(res) {
                                recalcularNivelesArbol();

                                $item.removeClass('nodo-movido-exito');
                                void $item[0].offsetWidth; // trigger reflow
                                $item.addClass('nodo-movido-exito');

                                setTimeout(function () {
                                    $item.removeClass('nodo-movido-exito');
                                }, 3200);

                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: res.message || 'Nodo reubicado con éxito.',
                                    showConfirmButton: false,
                                    timer: 3500,
                                    timerProgressBar: true
                                });
                            },
                            error: function(xhr) {
                                revertirNodo(evt);
                                recalcularNivelesArbol();

                                var errMsg = xhr.responseJSON?.error || 'Error al mover el nodo.';
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: errMsg,
                                    showConfirmButton: false,
                                    timer: 4500
                                });
                            }
                        });

                        function revertirNodo(e) {
                            var target = e.item;
                            if (e.from !== e.to) {
                                if (e.oldIndex === 0) {
                                    $(e.from).prepend(target);
                                } else {
                                    var prevSib = $(e.from).children('li.nodo-item').eq(e.oldIndex > 0 ? e.oldIndex - 1 : 0);
                                    if (prevSib.length) {
                                        prevSib.after(target);
                                    } else {
                                        $(e.from).append(target);
                                    }
                                }
                            } else {
                                var siblings = $(e.from).children('li.nodo-item').not(target);
                                if (e.oldIndex === 0) {
                                    $(e.from).prepend(target);
                                } else {
                                    siblings.eq(e.oldIndex - 1).after(target);
                                }
                            }
                        }
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

        // ── Inicializar Select2 ───────────────────────────────────────────────────
        $('#dep_establecimiento_id').select2({
            dropdownParent: $('#modalDependencia'),
            placeholder: '-- Buscar por código o nombre del establecimiento --',
            allowClear: true,
            width: '100%'
        });

        $('#dep_tipo_establecimiento').select2({
            dropdownParent: $('#modalDependencia'),
            placeholder: '-- Seleccionar tipología RIISS --',
            allowClear: true,
            width: '100%'
        });

        // Toggle de Establecimiento de Salud (RIISS)
        function toggleEsEstablecimiento(isEst) {
            if (isEst) {
                $('#grupo_establecimiento_riiss').slideDown(150);
                $('#dep_dependency').prop('readonly', true).addClass('bg-light');
                $('#dep_tipo_establecimiento').prop('disabled', true);
                $('#dep_address').prop('readonly', true).addClass('bg-light');
                syncEstablecimientoSeleccionado();
            } else {
                $('#grupo_establecimiento_riiss').slideUp(150);
                $('#dep_establecimiento_id').val('').trigger('change');
                $('#dep_dependency').prop('readonly', false).removeClass('bg-light');
                $('#dep_tipo_establecimiento').prop('disabled', false);
                $('#dep_address').prop('readonly', false).removeClass('bg-light');
            }
        }

        $('#dep_es_establecimiento').on('change', function () {
            toggleEsEstablecimiento($(this).is(':checked'));
        });

        $('#dep_establecimiento_id').on('change', function () {
            syncEstablecimientoSeleccionado();
        });

        function syncEstablecimientoSeleccionado() {
            if (!$('#dep_es_establecimiento').is(':checked')) return;
            var opt = $('#dep_establecimiento_id').find('option:selected');
            if (opt.val()) {
                var nombre = opt.data('nombre') || '';
                var tipologia = opt.data('tipologia') || '';
                var region = opt.data('region') || '';
                if (nombre) $('#dep_dependency').val(nombre);
                if (tipologia) $('#dep_tipo_establecimiento').val(tipologia).trigger('change');
                if (region) $('#dep_address').val(region);
            }
        }

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
            $('#dep_es_establecimiento').prop('checked', false);
            $('#dep_establecimiento_id').val('').trigger('change');
            toggleEsEstablecimiento(false);
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
                $('#dep_address').val(dep.address || '');

                if (dep.establecimiento_id || dep.tipo_establecimiento) {
                    $('#dep_es_establecimiento').prop('checked', true);
                    $('#dep_establecimiento_id').val(dep.establecimiento_id).trigger('change');
                    toggleEsEstablecimiento(true);
                } else {
                    $('#dep_es_establecimiento').prop('checked', false);
                    $('#dep_establecimiento_id').val('').trigger('change');
                    toggleEsEstablecimiento(false);
                }

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
})();
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
.sortable-ghost  { opacity: .45; background: #dbeafe !important; border: 2px dashed #2563eb !important; border-radius: 6px !important; }
.sortable-chosen { box-shadow: 0 4px 16px rgba(0,0,0,.2) !important; background: #eff6ff !important; }
.sortable-drag   { opacity: .9; }

/* ── Animación Moderna para Iluminar el Elemento Movido ── */
@keyframes nodoDestacadoGlow {
    0% {
        background-color: #dbeafe !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.35), 0 4px 12px rgba(59, 130, 246, 0.2) !important;
        transform: scale(1.015);
    }
    35% {
        background-color: #eff6ff !important;
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.2), 0 2px 8px rgba(0, 0, 0, 0.08) !important;
    }
    100% {
        background-color: #ffffff !important;
        border-color: #e9ecef !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        transform: scale(1);
    }
}

.nodo-item.nodo-movido-exito > .nodo-row {
    animation: nodoDestacadoGlow 3s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
    z-index: 10;
    position: relative;
}

body.is-organigrama-dragging .nodo-children {
    display: block !important;
}
body.is-organigrama-dragging .nodo-children > ul.sortable-group {
    min-height: 26px !important;
    background: rgba(241, 245, 249, 0.7);
    border: 1px dashed #94a3b8;
    border-radius: 6px;
    margin-top: 4px;
    transition: background 0.2s;
}
body.is-organigrama-dragging .nodo-children > ul.sortable-group:hover {
    background: rgba(219, 234, 254, 0.6);
    border-color: #3b82f6;
}
</style>
@stop
