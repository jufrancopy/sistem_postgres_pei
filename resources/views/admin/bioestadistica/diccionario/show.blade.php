@extends('layouts.master')
@section('title', 'Bioestadística — '.$variable->etiqueta())

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', [
    'items' => [
        ['label' => 'Variables', 'url' => route('bioestadistica.diccionario.index')],
        ['label' => $variable->etiqueta()],
    ],
    'showConfigTabs' => true,
])
<div class="card bio-siplan">
    <div class="card-header card-header-info d-flex justify-content-between align-items-start">
        <div>
            <h4 class="card-title">{{ $variable->etiqueta() }}</h4>
            <p class="card-category mb-0">Variable → tipo de registro → prestación (catálogo maestro)</p>
        </div>
        @canany(['bio.catalog.update', 'bio.catalog.delete'])
        <div class="text-nowrap ml-2">
            @can('bio.catalog.update')
            <button type="button" class="btn btn-outline-light btn-sm" data-toggle="collapse" data-target="#edit-variable" title="Editar variable">
                <i class="material-icons" style="font-size:16px">edit</i>
            </button>
            @endcan
            @can('bio.catalog.delete')
            <form method="POST" action="{{ route('bioestadistica.diccionario.variables.destroy', $variable) }}" class="d-inline" onsubmit="return confirm('¿Eliminar la variable «{{ $variable->nombre }}» y todo su contenido?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" title="Eliminar variable"><i class="material-icons" style="font-size:16px">delete</i></button>
            </form>
            @endcan
        </div>
        @endcanany
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

        @can('bio.catalog.update')
        <div class="collapse mb-4" id="edit-variable">
            <form method="POST" action="{{ route('bioestadistica.diccionario.variables.update', $variable) }}" class="bg-light border rounded p-3">
                @csrf @method('PUT')
                <div class="form-row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1">Código</label>
                        <input class="form-control" name="codigo" value="{{ $variable->codigo }}" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted mb-1">Nombre</label>
                        <input class="form-control" name="nombre" value="{{ $variable->nombre }}" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="d-block"><input type="checkbox" name="activo" value="1" @checked($variable->activo)> Activo</label>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary btn-sm btn-block">Guardar variable</button>
                    </div>
                </div>
            </form>
        </div>
        @endcan

        @can('bio.catalog.update')
        <form method="POST" action="{{ route('bioestadistica.diccionario.detalles.store', $variable) }}" class="form-row mb-4">
            @csrf
            <div class="col-md-9"><input class="form-control" name="nombre" placeholder="Nuevo tipo de registro" required></div>
            <div class="col-md-3"><button class="btn btn-info btn-sm btn-block">Agregar tipo de registro</button></div>
        </form>
        @endcan

        @forelse($variable->detalles as $detalle)
            @php
                $catalogRows = $catalogRowsByDetalle[$detalle->id] ?? collect();
                $resolvedType = $detalle->catalogo_tipo ?: 'prestacion';
            @endphp
            <div class="card border mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <div>
                        <strong>{{ $detalle->nombre }}</strong>
                        @unless($detalle->activo)<span class="badge badge-secondary ml-1">Inactivo</span>@endunless
                        @if($detalle->catalogo_tipo)
                            <span class="badge badge-info ml-1">{{ $catalogTypes[$detalle->catalogo_tipo] ?? $detalle->catalogo_tipo }}</span>
                        @endif
                        @if($detalle->layout_captura)
                            <span class="badge badge-warning ml-1">{{ $detalle->layout_captura }}</span>
                        @endif
                        @if($detalle->ordenaItemsAlfabeticamente())
                            <span class="badge badge-success ml-1" title="Las prestaciones se listan A→Z en captura">A→Z</span>
                        @endif
                        <small class="text-muted ml-2">{{ $catalogRows->count() }} ítems</small>
                    </div>
                    @canany(['bio.catalog.update', 'bio.catalog.delete'])
                    <div class="text-nowrap">
                        @can('bio.catalog.update')
                        <form method="POST" action="{{ route('bioestadistica.diccionario.detalles.reordenar-alfabetico', $detalle) }}" class="d-inline"
                              onsubmit="return confirm('¿Reescribir el orden de las prestaciones A→Z? Quedará en modo manual con números 10, 20, 30…')">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm" title="Reordenar prestaciones A→Z">
                                <i class="material-icons" style="font-size:16px">sort_by_alpha</i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-outline-secondary btn-sm btn-edit-detalle" title="Editar tipo de registro"
                            data-update-url="{{ route('bioestadistica.diccionario.detalles.update', $detalle) }}"
                            data-nombre="{{ $detalle->nombre }}"
                            data-orden="{{ $detalle->orden }}"
                            data-activo="{{ $detalle->activo ? '1' : '0' }}"
                            data-catalogo-tipo="{{ $detalle->catalogo_tipo }}"
                            data-layout="{{ $detalle->layout_captura }}"
                            data-orden-items="{{ $detalle->ordenItemsMode() }}">
                            <i class="material-icons" style="font-size:16px">edit</i>
                        </button>
                        @endcan
                        @can('bio.catalog.delete')
                        <form method="POST" action="{{ route('bioestadistica.diccionario.detalles.destroy', $detalle) }}" class="d-inline" onsubmit="return confirm('¿Eliminar el tipo de registro «{{ $detalle->nombre }}»?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Eliminar"><i class="material-icons" style="font-size:16px">delete</i></button>
                        </form>
                        @endcan
                    </div>
                    @endcanany
                </div>

                <div class="card-body py-3">
                    @can('bio.catalog.update')
                    <div class="d-flex flex-wrap mb-2" style="gap:.5rem">
                        <form method="POST" action="{{ route('bioestadistica.diccionario.prestaciones.store', $detalle) }}" class="form-inline flex-grow-1" style="gap:.5rem">
                            @csrf
                            <input class="form-control form-control-sm" name="nombre" placeholder="Nueva prestación" required style="min-width:200px;flex:1">
                            <button class="btn btn-success btn-sm">Crear</button>
                        </form>
                        <button type="button" class="btn btn-outline-primary btn-sm btn-link-prestacion"
                            data-detalle-id="{{ $detalle->id }}"
                            data-search-url="{{ route('bioestadistica.diccionario.catalogo.buscar', $detalle) }}"
                            data-link-url="{{ route('bioestadistica.diccionario.prestaciones.link', $detalle) }}"
                            data-catalogo-label="{{ $catalogTypes[$resolvedType] ?? $resolvedType }}">
                            <i class="material-icons align-middle" style="font-size:16px">link</i> Vincular existente
                        </button>
                    </div>
                    @endcan

                    <ul class="list-group list-group-flush border rounded">
                        @forelse($catalogRows as $row)
                            <li class="list-group-item py-2 d-flex justify-content-between align-items-center {{ !$row->activo || !$row->bridge_activo ? 'bg-light text-muted' : '' }}">
                                <div>
                                    <span>{{ $row->label }}</span>
                                    <small class="text-muted ml-2">#{{ $row->id }}</small>
                                    @unless($row->activo)<span class="badge badge-secondary ml-1">Ítem inactivo</span>@endunless
                                    @unless($row->bridge_activo)<span class="badge badge-warning ml-1">Vínculo inactivo</span>@endunless
                                </div>
                                @canany(['bio.catalog.update', 'bio.catalog.delete'])
                                <div class="text-nowrap">
                                    @can('bio.catalog.update')
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-edit-prestacion" title="Editar prestación"
                                        data-update-url="{{ route('bioestadistica.diccionario.prestaciones.update', $row->bridge_id) }}"
                                        data-unlink-url="{{ route('bioestadistica.diccionario.prestaciones.destroy', $row->bridge_id) }}"
                                        data-delete-url="{{ route('bioestadistica.diccionario.prestaciones.destroy', $row->bridge_id) }}"
                                        data-catalogo-tipo="{{ $row->catalogo_tipo }}"
                                        data-nombre="{{ e($row->label) }}"
                                        data-activo="{{ $row->activo ? '1' : '0' }}"
                                        data-bridge-orden="{{ $row->bridge_orden }}"
                                        data-bridge-activo="{{ $row->bridge_activo ? '1' : '0' }}"
                                        data-attrs='@json($row->attributes)'>
                                        <i class="material-icons" style="font-size:16px">edit</i>
                                    </button>
                                    @endcan
                                </div>
                                @endcanany
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Sin prestaciones vinculadas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">Esta variable aún no tiene tipos de registro.</p>
        @endforelse
    </div>
</div>

@can('bio.catalog.update')
<div class="modal fade" id="modalEditDetalle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" class="modal-content" id="formEditDetalle">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar tipo de registro</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre</label>
                    <input class="form-control" name="nombre" id="detalleNombre" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Catálogo maestro</label>
                        <select class="form-control" name="catalogo_tipo" id="detalleCatalogoTipo">
                            <option value="">Automático (según variable)</option>
                            @foreach($catalogTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Layout de captura</label>
                        <select class="form-control" name="layout_captura" id="detalleLayout">
                            @foreach($layoutOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Orden de prestaciones</label>
                        <select class="form-control" name="orden_items" id="detalleOrdenItems">
                            <option value="manual">Manual (campo Orden de cada ítem)</option>
                            <option value="alfabetico">Alfabético A→Z (automático en captura)</option>
                        </select>
                        <small class="form-text text-muted">Ej.: Consultas por especialidad en A→Z.</small>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Orden tipo</label>
                        <input class="form-control" type="number" min="0" name="orden" id="detalleOrden">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <label class="mb-0"><input type="checkbox" name="activo" value="1" id="detalleActivo"> Activo</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditPrestacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" class="modal-content" id="formEditPrestacion">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar prestación</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3">Catálogo: <strong id="prestacionCatalogoLabel">—</strong></p>
                <div class="form-group">
                    <label>Nombre</label>
                    <input class="form-control" name="nombre" id="prestacionNombre" required>
                </div>
                <div class="form-row mb-2">
                    <div class="form-group col-md-3">
                        <label>Orden (vínculo)</label>
                        <input class="form-control" type="number" min="0" name="bridge_orden" id="prestacionBridgeOrden">
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <label class="mb-0"><input type="checkbox" name="activo" value="1" id="prestacionActivo"> Ítem activo</label>
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <label class="mb-0"><input type="checkbox" name="bridge_activo" value="1" id="prestacionBridgeActivo"> Vínculo activo</label>
                    </div>
                </div>

                <div class="catalog-fields" data-tipo="especialidad_medica">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Especialidad base</label>
                            <input class="form-control" name="especialidad_base" data-field="especialidad_base">
                        </div>
                    </div>
                </div>
                <div class="catalog-fields" data-tipo="determinacion">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Familia</label>
                            <select class="form-control" name="familia" data-field="familia">
                                @foreach(['laboratorio','alta_complejidad','baja_complejidad'] as $f)
                                    <option value="{{ $f }}">{{ str_replace('_', ' ', ucfirst($f)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Modalidad</label>
                            <input class="form-control" name="modalidad" data-field="modalidad">
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-end">
                            <label class="mb-0"><input type="checkbox" name="es_agregado" value="1" data-field="es_agregado"> Es agregado</label>
                        </div>
                    </div>
                </div>
                <div class="catalog-fields" data-tipo="procedimiento">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Categoría</label>
                            <input class="form-control" name="categoria" data-field="categoria">
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-end">
                            <label class="mb-0"><input type="checkbox" name="requiere_pacientes" value="1" data-field="requiere_pacientes"> Requiere pacientes</label>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-end">
                            <label class="mb-0"><input type="checkbox" name="requiere_prestaciones" value="1" data-field="requiere_prestaciones"> Requiere prestaciones</label>
                        </div>
                    </div>
                </div>
                <div class="catalog-fields" data-tipo="vacuna">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Abreviatura</label>
                            <input class="form-control" name="abreviatura" data-field="abreviatura">
                        </div>
                        <div class="form-group col-md-6 d-flex align-items-end">
                            <label class="mb-0"><input type="checkbox" name="requiere_lote" value="1" data-field="requiere_lote"> Requiere lote</label>
                        </div>
                    </div>
                </div>
                <div class="catalog-fields" data-tipo="prestacion">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Familia</label>
                            <input class="form-control" name="familia" data-field="familia">
                        </div>
                        <div class="form-group col-md-6 d-flex align-items-end">
                            <label class="mb-0"><input type="checkbox" name="es_indicador" value="1" data-field="es_indicador"> Es indicador</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                @can('bio.catalog.delete')
                <div>
                    <button type="button" class="btn btn-outline-warning btn-sm" id="btnUnlinkPrestacion">Desvincular</button>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnDeletePrestacion">Eliminar del catálogo</button>
                </div>
                @else
                <div></div>
                @endcan
                <div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form method="POST" action="" id="formUnlinkPrestacion" class="d-none">
    @csrf @method('DELETE')
</form>
<form method="POST" action="" id="formDeletePrestacion" class="d-none" data-confirm="¿Eliminar esta prestación del catálogo maestro en todos los tipos de registro?">
    @csrf @method('DELETE')
    <input type="hidden" name="delete_master" value="1">
</form>

<div class="modal fade" id="modalLinkPrestacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" class="modal-content" id="formLinkPrestacion">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Vincular prestación existente</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">Catálogo: <strong id="linkCatalogoLabel">—</strong></p>
                <div class="form-group">
                    <label>Buscar</label>
                    <input type="text" class="form-control" id="linkSearchInput" placeholder="Escriba para filtrar...">
                </div>
                <div class="form-group">
                    <label>Prestación</label>
                    <select class="form-control" name="catalogo_item_id" id="linkItemSelect" required size="8">
                        <option value="">Cargando...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Vincular</button>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@can('bio.catalog.update')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const catalogLabels = @json($catalogTypes);

    function showModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            jQuery(el).modal('show');
            return;
        }
        el.classList.add('show');
        el.style.display = 'block';
        el.removeAttribute('aria-hidden');
    }

    document.querySelectorAll('.btn-edit-detalle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('formEditDetalle').action = btn.dataset.updateUrl;
            document.getElementById('detalleNombre').value = btn.dataset.nombre || '';
            document.getElementById('detalleOrden').value = btn.dataset.orden || 0;
            document.getElementById('detalleActivo').checked = btn.dataset.activo === '1';
            document.getElementById('detalleCatalogoTipo').value = btn.dataset.catalogoTipo || '';
            document.getElementById('detalleLayout').value = btn.dataset.layout || '';
            document.getElementById('detalleOrdenItems').value = btn.dataset.ordenItems || 'manual';
            showModal('modalEditDetalle');
        });
    });

    function showCatalogFields(tipo) {
        document.querySelectorAll('#modalEditPrestacion .catalog-fields').forEach(function (block) {
            const visible = block.dataset.tipo === tipo;
            block.style.display = visible ? 'block' : 'none';
            block.querySelectorAll('input, select, textarea').forEach(function (field) {
                field.disabled = !visible;
            });
        });
        document.getElementById('prestacionCatalogoLabel').textContent = catalogLabels[tipo] || tipo;
    }

    function parseAttrs(raw) {
        if (!raw) return {};
        try {
            return JSON.parse(raw);
        } catch (e) {
            return {};
        }
    }

    document.querySelectorAll('.btn-edit-prestacion').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const tipo = btn.dataset.catalogoTipo;
            const attrs = parseAttrs(btn.dataset.attrs);
            const form = document.getElementById('formEditPrestacion');
            form.action = btn.dataset.updateUrl || '';

            const unlinkForm = document.getElementById('formUnlinkPrestacion');
            const deleteForm = document.getElementById('formDeletePrestacion');
            if (unlinkForm) unlinkForm.action = btn.dataset.unlinkUrl || '';
            if (deleteForm) deleteForm.action = btn.dataset.deleteUrl || '';

            document.getElementById('prestacionNombre').value = btn.dataset.nombre || '';
            document.getElementById('prestacionActivo').checked = btn.dataset.activo === '1';
            document.getElementById('prestacionBridgeOrden').value = btn.dataset.bridgeOrden || 0;
            document.getElementById('prestacionBridgeActivo').checked = btn.dataset.bridgeActivo === '1';
            showCatalogFields(tipo);

            form.querySelectorAll('[data-field]').forEach(function (field) {
                const key = field.dataset.field;
                if (field.type === 'checkbox') {
                    field.checked = !!attrs[key];
                } else if (attrs[key] !== undefined && attrs[key] !== null) {
                    field.value = attrs[key];
                } else if (field.tagName === 'SELECT') {
                    field.selectedIndex = 0;
                } else {
                    field.value = '';
                }
            });

            showModal('modalEditPrestacion');
        });
    });

    const unlinkBtn = document.getElementById('btnUnlinkPrestacion');
    if (unlinkBtn) {
        unlinkBtn.addEventListener('click', function () {
            document.getElementById('formUnlinkPrestacion')?.submit();
        });
    }

    const deleteBtn = document.getElementById('btnDeletePrestacion');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            const form = document.getElementById('formDeletePrestacion');
            if (!form) return;
            const msg = form.dataset.confirm || '¿Confirmar eliminación?';
            if (window.confirm(msg)) {
                form.submit();
            }
        });
    }

    let linkSearchUrl = '';
    let linkDebounce = null;
    const linkSelect = document.getElementById('linkItemSelect');
    const linkSearchInput = document.getElementById('linkSearchInput');

    function loadLinkOptions() {
        if (!linkSearchUrl || !linkSelect) return;
        linkSelect.innerHTML = '<option value="">Cargando...</option>';
        fetch(linkSearchUrl + '?q=' + encodeURIComponent(linkSearchInput.value || ''), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.json())
            .then(payload => {
                linkSelect.innerHTML = '';
                if (!payload.data.length) {
                    linkSelect.innerHTML = '<option value="">Sin resultados</option>';
                    return;
                }
                payload.data.forEach(function (item) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.label + ' (#' + item.id + ')';
                    linkSelect.appendChild(opt);
                });
            })
            .catch(function () {
                linkSelect.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    document.querySelectorAll('.btn-link-prestacion').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('formLinkPrestacion').action = btn.dataset.linkUrl;
            document.getElementById('linkCatalogoLabel').textContent = btn.dataset.catalogoLabel || '—';
            linkSearchUrl = btn.dataset.searchUrl;
            linkSearchInput.value = '';
            loadLinkOptions();
            showModal('modalLinkPrestacion');
        });
    });

    if (linkSearchInput) {
        linkSearchInput.addEventListener('input', function () {
            clearTimeout(linkDebounce);
            linkDebounce = setTimeout(loadLinkOptions, 250);
        });
    }
});
</script>
@endcan
@endsection
