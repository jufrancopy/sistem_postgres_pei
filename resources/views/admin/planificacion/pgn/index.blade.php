@extends('layouts.master')
@section('title', 'Módulo PGN')

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex align-items-center">
        <h4 class="card-title mb-0">Presupuesto General de la Nación (PGN)</h4>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item active">PGN</li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="row">

            {{-- ── Panel izquierdo: Selector de año + Estructura de niveles ── --}}
            <div class="col-md-3">
                <div class="card border">
                    <div class="card-header bg-dark text-white py-2">
                        <i class="fa fa-calendar mr-1"></i> Año Presupuestario
                    </div>
                    <div class="card-body p-2">
                        <select id="selectAnio" class="form-control form-control-sm">
                            @foreach($anios as $a)
                                <option value="{{ $a }}" {{ $a == $anioActual ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-dark btn-block mt-2" id="btnNuevoAnio">
                            <i class="fa fa-plus mr-1"></i> Nuevo año
                        </button>
                    </div>
                </div>

                {{-- Estructura de niveles del año --}}
                <div class="card border mt-3">
                    <div class="card-header bg-secondary text-white py-2 d-flex justify-content-between align-items-center">
                        <span><i class="fa fa-layer-group mr-1"></i> Niveles del año</span>
                        @role('Administrador')
                        <button class="btn btn-sm btn-light" id="btnAddNivel" title="Agregar nivel">
                            <i class="fa fa-plus"></i>
                        </button>
                        @endrole
                    </div>
                    <div class="card-body p-2" id="listaNiveles">
                        @foreach($estructura as $nivel)
                        <div class="d-flex align-items-center justify-content-between mb-1 nivel-item" data-id="{{ $nivel->id }}">
                            <span class="badge badge-secondary mr-1">{{ $nivel->orden }}</span>
                            <span class="flex-grow-1">{{ $nivel->nombre }}</span>
                            @role('Administrador')
                            <button class="btn btn-xs btn-outline-info btn-edit-nivel ml-1" data-id="{{ $nivel->id }}" data-nombre="{{ $nivel->nombre }}" data-orden="{{ $nivel->orden }}" data-descripcion="{{ $nivel->descripcion }}" title="Editar">
                                <i class="fa fa-edit" style="font-size:.7rem"></i>
                            </button>
                            <button class="btn btn-xs btn-outline-danger btn-del-nivel ml-1" data-id="{{ $nivel->id }}" title="Eliminar">
                                <i class="fa fa-trash" style="font-size:.7rem"></i>
                            </button>
                            @endrole
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Importar CSV --}}
                <div class="card border mt-3">
                    <div class="card-header bg-success text-white py-2">
                        <i class="fa fa-file-csv mr-1"></i> Importar CSV
                    </div>
                    <div class="card-body p-2">
                        <small class="text-muted d-block mb-2">
                            Columnas: <code>nivel, codigo, nombre, monto_asignado_gs, codigo_padre</code>
                        </small>
                        <form id="formImportar" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="anio" id="importAnio" value="{{ $anioActual }}">
                            <div class="custom-file mb-2">
                                <input type="file" class="custom-file-input" id="archivoCsv" name="archivo" accept=".csv,.txt">
                                <label class="custom-file-label" for="archivoCsv">Elegir archivo...</label>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success btn-block">
                                <i class="fa fa-upload mr-1"></i> Importar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── Panel derecho: Árbol de nodos ── --}}
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0"><i class="fa fa-sitemap mr-1"></i> Nodos del PGN <span id="anioLabel" class="badge badge-info ml-1">{{ $anioActual }}</span></h6>
                    <button class="btn btn-sm btn-primary" id="btnAddNodo">
                        <i class="fa fa-plus mr-1"></i> Agregar nodo raíz
                    </button>
                </div>
                <div id="arbolPgn">
                    <div class="text-center text-muted py-4">
                        <i class="fa fa-spinner fa-spin mr-1"></i> Cargando...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal: Nuevo/Editar nivel ── --}}
<div class="modal fade" id="modalNivel" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNivelTitle">Nivel</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="nivelId">
                <input type="hidden" id="nivelAnio">
                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" class="form-control form-control-sm" id="nivelOrden" min="1">
                </div>
                <div class="form-group">
                    <label>Nombre del nivel</label>
                    <input type="text" class="form-control form-control-sm" id="nivelNombre" placeholder="ej: Programa, Subprograma...">
                </div>
                <div class="form-group">
                    <label>Descripción <small class="text-muted">(opcional)</small></label>
                    <input type="text" class="form-control form-control-sm" id="nivelDescripcion">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-sm btn-primary" id="btnGuardarNivel">Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal: Nuevo/Editar nodo ── --}}
<div class="modal fade" id="modalNodo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNodoTitle">Nodo PGN</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="nodoId">
                <input type="hidden" id="nodoAnio">
                <input type="hidden" id="nodoParentId">
                <div class="form-group">
                    <label>Nivel <span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm" id="nodoEstructuraId"></select>
                </div>
                <div class="form-group">
                    <label>Código</label>
                    <input type="text" class="form-control form-control-sm" id="nodoCodigo" placeholder="ej: 14-01-001">
                </div>
                <div class="form-group">
                    <label>Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="nodoNombre">
                </div>
                <div class="form-group">
                    <label>Monto Asignado (Gs.)</label>
                    <input type="number" class="form-control form-control-sm" id="nodoMonto" min="0" step="1">
                </div>
                <div id="nodoParentInfo" class="alert alert-info py-1 px-2 small" style="display:none">
                    <i class="fa fa-level-up-alt mr-1"></i> Subnodo de: <strong id="nodoParentNombre"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-sm btn-primary" id="btnGuardarNodo">Guardar</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var anioActual = {{ $anioActual }};
    var nivelesCache = {};

    // ── Cargar árbol al iniciar ───────────────────────────────────────────────
    cargarArbol(anioActual);
    cargarNiveles(anioActual);

    function cargarArbol(anio) {
        $('#anioLabel').text(anio);
        $('#importAnio').val(anio);
        $('#arbolPgn').html('<div class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin mr-1"></i> Cargando...</div>');
        $.get('{{ route("pgn.nodos.anio", ["anio" => "__ANIO__"]) }}'.replace('__ANIO__', anio), function (nodos) {
            if (!nodos.length) {
                $('#arbolPgn').html('<div class="text-center text-muted py-5"><i class="fa fa-inbox fa-2x mb-2 d-block"></i>Sin nodos para este año. Importá un CSV o agregá manualmente.</div>');
                return;
            }
            $('#arbolPgn').html(renderArbol(nodos, 0));
        });
    }

    function renderArbol(nodos, nivel) {
        var html = '<ul class="list-unstyled ' + (nivel > 0 ? 'ml-3 border-left pl-2' : '') + '">';
        nodos.forEach(function (n) {
            var tieneHijos = n.hijos && n.hijos.length > 0;
            var monto = n.monto_asignado_gs ? ' <small class="text-muted ml-1">Gs. ' + Number(n.monto_asignado_gs).toLocaleString('es-PY') + '</small>' : '';
            var nivelBadge = n.estructura ? '<span class="badge badge-secondary mr-1" style="font-size:.65rem">' + n.estructura.nombre + '</span>' : '';
            var codigo = n.codigo ? '<code class="mr-1">' + n.codigo + '</code>' : '';
            html += '<li class="py-1">';
            html += '<div class="d-flex align-items-center">';
            if (tieneHijos) {
                html += '<i class="fa fa-caret-right mr-1 toggle-nodo" style="cursor:pointer;width:12px" data-id="' + n.id + '"></i>';
            } else {
                html += '<i class="fa fa-minus mr-1 text-muted" style="width:12px;font-size:.6rem"></i>';
            }
            html += nivelBadge + codigo + '<span>' + n.nombre + '</span>' + monto;
            html += '<div class="ml-auto">';
            html += '<button class="btn btn-xs btn-outline-success btn-add-hijo ml-1" data-id="' + n.id + '" data-nombre="' + n.nombre + '" title="Agregar subnodo"><i class="fa fa-plus" style="font-size:.65rem"></i></button>';
            html += '<button class="btn btn-xs btn-outline-info btn-edit-nodo ml-1" data-nodo=\'' + JSON.stringify(n) + '\' title="Editar"><i class="fa fa-edit" style="font-size:.65rem"></i></button>';
            html += '<button class="btn btn-xs btn-outline-danger btn-del-nodo ml-1" data-id="' + n.id + '" title="Eliminar"><i class="fa fa-trash" style="font-size:.65rem"></i></button>';
            html += '</div></div>';
            if (tieneHijos) {
                html += '<div class="hijos-' + n.id + '" style="display:none">' + renderArbol(n.hijos, nivel + 1) + '</div>';
            }
            html += '</li>';
        });
        html += '</ul>';
        return html;
    }

    function cargarNiveles(anio) {
        $.get('{{ route("pgn.estructura.anio", ["anio" => "__ANIO__"]) }}'.replace('__ANIO__', anio), function (niveles) {
            nivelesCache[anio] = niveles;
            var html = '';
            niveles.forEach(function (n) {
                html += '<div class="d-flex align-items-center justify-content-between mb-1 nivel-item" data-id="' + n.id + '">';
                html += '<span class="badge badge-secondary mr-1">' + n.orden + '</span>';
                html += '<span class="flex-grow-1">' + n.nombre + '</span>';
                html += '<button class="btn btn-xs btn-outline-info btn-edit-nivel ml-1" data-id="' + n.id + '" data-nombre="' + n.nombre + '" data-orden="' + n.orden + '" data-descripcion="' + (n.descripcion||'') + '" title="Editar"><i class="fa fa-edit" style="font-size:.7rem"></i></button>';
                html += '<button class="btn btn-xs btn-outline-danger btn-del-nivel ml-1" data-id="' + n.id + '" title="Eliminar"><i class="fa fa-trash" style="font-size:.7rem"></i></button>';
                html += '</div>';
            });
            $('#listaNiveles').html(html || '<small class="text-muted">Sin niveles definidos</small>');
            // Actualizar select del modal nodo
            var opts = '<option value="">-- Seleccioná el nivel --</option>';
            niveles.forEach(function (n) { opts += '<option value="' + n.id + '">' + n.orden + '. ' + n.nombre + '</option>'; });
            $('#nodoEstructuraId').html(opts);
        });
    }

    // ── Cambio de año ─────────────────────────────────────────────────────────
    $('#selectAnio').change(function () {
        anioActual = $(this).val();
        cargarArbol(anioActual);
        cargarNiveles(anioActual);
    });

    // ── Nuevo año ─────────────────────────────────────────────────────────────
    $('#btnNuevoAnio').click(function () {
        Swal.fire({
            title: 'Nuevo año presupuestario',
            input: 'number',
            inputLabel: 'Año',
            inputValue: new Date().getFullYear() + 1,
            showCancelButton: true,
            confirmButtonText: 'Crear',
        }).then(function (r) {
            if (r.isConfirmed && r.value) {
                var anio = parseInt(r.value);
                // Agregar al select si no existe
                if (!$('#selectAnio option[value="' + anio + '"]').length) {
                    $('#selectAnio').prepend('<option value="' + anio + '">' + anio + '</option>');
                }
                $('#selectAnio').val(anio).trigger('change');
            }
        });
    });

    // ── Toggle árbol ──────────────────────────────────────────────────────────
    $('#arbolPgn').on('click', '.toggle-nodo', function () {
        var id = $(this).data('id');
        var hijos = $('.hijos-' + id);
        hijos.toggle();
        $(this).toggleClass('fa-caret-right fa-caret-down');
    });

    // ── Modal Nivel: Agregar ──────────────────────────────────────────────────
    $('#btnAddNivel').click(function () {
        $('#nivelId').val('');
        $('#nivelAnio').val(anioActual);
        $('#nivelOrden').val('');
        $('#nivelNombre').val('');
        $('#nivelDescripcion').val('');
        $('#modalNivelTitle').text('Agregar nivel');
        $('#modalNivel').modal('show');
    });

    // ── Modal Nivel: Editar ───────────────────────────────────────────────────
    $('#listaNiveles').on('click', '.btn-edit-nivel', function () {
        var btn = $(this);
        $('#nivelId').val(btn.data('id'));
        $('#nivelAnio').val(anioActual);
        $('#nivelOrden').val(btn.data('orden'));
        $('#nivelNombre').val(btn.data('nombre'));
        $('#nivelDescripcion').val(btn.data('descripcion'));
        $('#modalNivelTitle').text('Editar nivel');
        $('#modalNivel').modal('show');
    });

    // ── Guardar nivel ─────────────────────────────────────────────────────────
    $('#btnGuardarNivel').click(function () {
        var id     = $('#nivelId').val();
        var url    = id ? '{{ route("pgn.estructura.update", ["estructura" => "__ID__"]) }}'.replace('__ID__', id)
                       : '{{ route("pgn.estructura.store") }}';
        var method = id ? 'PUT' : 'POST';
        $.ajax({
            url: url, type: method,
            data: {
                anio: $('#nivelAnio').val(),
                orden: $('#nivelOrden').val(),
                nombre: $('#nivelNombre').val(),
                descripcion: $('#nivelDescripcion').val(),
            },
            success: function () {
                $('#modalNivel').modal('hide');
                cargarNiveles(anioActual);
                toastr.success('Nivel guardado');
            },
            error: function (r) {
                var errs = r.responseJSON?.errors || {};
                Object.values(errs).forEach(function (e) { toastr.error(e[0]); });
            }
        });
    });

    // ── Eliminar nivel ────────────────────────────────────────────────────────
    $('#listaNiveles').on('click', '.btn-del-nivel', function () {
        var id = $(this).data('id');
        Swal.fire({ title: '¿Eliminar nivel?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar' })
            .then(function (r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: '{{ route("pgn.estructura.destroy", ["estructura" => "__ID__"]) }}'.replace('__ID__', id),
                    type: 'DELETE',
                    success: function () { cargarNiveles(anioActual); toastr.success('Nivel eliminado'); },
                    error: function (r) { toastr.error(r.responseJSON?.error || 'Error'); }
                });
            });
    });

    // ── Modal Nodo: Agregar raíz ──────────────────────────────────────────────
    $('#btnAddNodo').click(function () {
        abrirModalNodo(null, null);
    });

    // ── Modal Nodo: Agregar hijo ──────────────────────────────────────────────
    $('#arbolPgn').on('click', '.btn-add-hijo', function () {
        abrirModalNodo(null, { id: $(this).data('id'), nombre: $(this).data('nombre') });
    });

    // ── Modal Nodo: Editar ────────────────────────────────────────────────────
    $('#arbolPgn').on('click', '.btn-edit-nodo', function () {
        var n = $(this).data('nodo');
        abrirModalNodo(n, null);
    });

    function abrirModalNodo(nodo, padre) {
        $('#nodoId').val(nodo ? nodo.id : '');
        $('#nodoAnio').val(anioActual);
        $('#nodoParentId').val(padre ? padre.id : (nodo ? nodo.parent_id : ''));
        $('#nodoCodigo').val(nodo ? nodo.codigo : '');
        $('#nodoNombre').val(nodo ? nodo.nombre : '');
        $('#nodoMonto').val(nodo ? nodo.monto_asignado_gs : '');
        $('#nodoEstructuraId').val(nodo ? nodo.pgn_estructura_id : '');
        if (padre) {
            $('#nodoParentInfo').show();
            $('#nodoParentNombre').text(padre.nombre);
        } else {
            $('#nodoParentInfo').hide();
        }
        $('#modalNodoTitle').text(nodo ? 'Editar nodo' : 'Agregar nodo');
        $('#modalNodo').modal('show');
    }

    // ── Guardar nodo ──────────────────────────────────────────────────────────
    $('#btnGuardarNodo').click(function () {
        var id     = $('#nodoId').val();
        var url    = id ? '{{ route("pgn.nodos.update", ["nodo" => "__ID__"]) }}'.replace('__ID__', id)
                       : '{{ route("pgn.nodos.store") }}';
        var method = id ? 'PUT' : 'POST';
        $.ajax({
            url: url, type: method,
            data: {
                anio:               $('#nodoAnio').val(),
                pgn_estructura_id:  $('#nodoEstructuraId').val(),
                parent_id:          $('#nodoParentId').val() || null,
                codigo:             $('#nodoCodigo').val(),
                nombre:             $('#nodoNombre').val(),
                monto_asignado_gs:  $('#nodoMonto').val() || null,
            },
            success: function () {
                $('#modalNodo').modal('hide');
                cargarArbol(anioActual);
                toastr.success('Nodo guardado');
            },
            error: function (r) {
                var errs = r.responseJSON?.errors || {};
                Object.values(errs).forEach(function (e) { toastr.error(e[0]); });
            }
        });
    });

    // ── Eliminar nodo ─────────────────────────────────────────────────────────
    $('#arbolPgn').on('click', '.btn-del-nodo', function () {
        var id = $(this).data('id');
        Swal.fire({ title: '¿Eliminar nodo?', text: 'Se eliminarán también sus subnodos.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar' })
            .then(function (r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: '{{ route("pgn.nodos.destroy", ["nodo" => "__ID__"]) }}'.replace('__ID__', id),
                    type: 'DELETE',
                    success: function () { cargarArbol(anioActual); toastr.success('Nodo eliminado'); },
                    error: function (r) { toastr.error(r.responseJSON?.error || 'Error'); }
                });
            });
    });

    // ── Importar CSV ──────────────────────────────────────────────────────────
    $('#archivoCsv').change(function () {
        var name = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').text(name || 'Elegir archivo...');
    });

    $('#formImportar').submit(function (e) {
        e.preventDefault();
        var fd = new FormData(this);
        $.ajax({
            url: '{{ route("pgn.importar") }}', type: 'POST',
            data: fd, processData: false, contentType: false,
            success: function (r) {
                var msg = r.creados + ' nodo(s) importado(s).';
                if (r.errores && r.errores.length) msg += ' Errores: ' + r.errores.join(', ');
                Swal.fire('Importación completada', msg, r.errores.length ? 'warning' : 'success');
                cargarArbol(anioActual);
            },
            error: function (r) { toastr.error(r.responseJSON?.error || 'Error al importar'); }
        });
    });
});
</script>
@stop
