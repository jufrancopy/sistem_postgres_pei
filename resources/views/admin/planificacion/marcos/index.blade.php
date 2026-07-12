@extends('layouts.master')
@section('title', 'Marcos Referenciales')

@php
// Helper para calcular si el texto sobre un color hex debe ser blanco o negro
function colorTexto(string $hex): string {
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return ((0.299*$r + 0.587*$g + 0.114*$b)/255) > 0.55 ? '#000' : '#fff';
}
@endphp

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex align-items-center">
        <div>
            <h4 class="card-title mb-0">Marcos Referenciales</h4>
            <small class="text-white" style="opacity:.8">PND · ODS · MECIP · PAM y más</small>
        </div>
        <button class="btn btn-sm btn-light ml-auto" id="btnNuevoMarco">
            <i class="fa fa-plus mr-1"></i> Nuevo Marco
        </button>
    </div>

    <div class="card-body">

        {{-- Filtro por tipo --}}
        <div class="d-flex flex-wrap align-items-center mb-3" style="gap:.4rem">
            <a href="{{ route('pei.marcos.index') }}"
               class="badge {{ !$filtro ? 'badge-dark' : 'badge-secondary' }}"
               style="font-size:.8rem; padding:.4em .8em; cursor:pointer">Todos</a>
            @foreach($tipos as $tipo)
            <a href="{{ route('pei.marcos.index', ['tipo' => $tipo->clave]) }}"
               class="badge"
               style="font-size:.8rem; padding:.4em .8em; cursor:pointer;
                      background:{{ $tipo->color }};
                      color:{{ colorTexto($tipo->color) }};
                      {{ $filtro === $tipo->clave ? 'outline:2px solid #1a237e; outline-offset:2px;' : 'opacity:.85' }}">
                {{ strtoupper($tipo->clave) }}
            </a>
            @endforeach
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th style="width:120px">Tipo</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th style="width:80px" class="text-center">Estado</th>
                        <th style="width:110px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marcos as $marco)
                    @php
                        $tipoObj = $tipos->firstWhere('clave', $marco->tipo);
                        $bg      = $tipoObj?->color ?? '#6c757d';
                        $fg      = colorTexto($bg);
                    @endphp
                    <tr id="row-{{ $marco->id }}" class="{{ !$marco->activo ? 'text-muted' : '' }}">
                        <td>
                            <span class="badge"
                                  style="background:{{ $bg }};color:{{ $fg }};font-size:.72rem">
                                {{ strtoupper($marco->tipo) }}
                            </span>
                        </td>
                        <td style="font-size:.88rem">{{ $marco->nombre }}</td>
                        <td style="font-size:.82rem;color:#6c757d">{{ $marco->descripcion ?? '—' }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-link p-0 btnToggle"
                                    data-id="{{ $marco->id }}"
                                    title="{{ $marco->activo ? 'Desactivar' : 'Activar' }}">
                                <i class="fa fa-circle {{ $marco->activo ? 'text-success' : 'text-secondary' }}"></i>
                            </button>
                        </td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-outline-primary py-0 px-2 btnEditar"
                                    data-id="{{ $marco->id }}"
                                    data-nombre="{{ $marco->nombre }}"
                                    data-tipo="{{ $marco->tipo }}"
                                    data-descripcion="{{ $marco->descripcion }}"
                                    data-activo="{{ $marco->activo ? '1' : '0' }}"
                                    title="Editar">
                                <i class="fa fa-edit" style="font-size:.75rem"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger py-0 px-2 btnEliminar"
                                    data-id="{{ $marco->id }}"
                                    data-nombre="{{ $marco->nombre }}"
                                    title="Eliminar">
                                <i class="fa fa-trash" style="font-size:.75rem"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fa fa-inbox mr-2"></i> Sin marcos registrados para este filtro.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">{{ $marcos->links() }}</div>
    </div>
</div>

{{-- ══ Modal Crear / Editar Marco ═════════════════════════════════════════ --}}
<div class="modal fade" id="modalMarco" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white mb-0" id="modalMarcoTitulo">Nuevo Marco Referencial</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="marco_id">
                <div class="form-group">
                    <label class="font-weight-bold">Tipo <span class="text-danger">*</span></label>
                    <small class="form-text text-muted mb-1">
                        Seleccioná un tipo o escribí uno nuevo — se te pedirá el color.
                    </small>
                    <select id="marco_tipo" style="width:100%"></select>
                    <div id="tipoBadgePreview" class="mt-2" style="display:none">
                        <span id="tipoBadge" class="badge" style="font-size:.8rem"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" id="marco_nombre" class="form-control"
                           placeholder="Ej: PND OE 1.2 - Garantizar acceso universal a salud">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">
                        Descripción <span class="text-muted" style="font-weight:400">(opcional)</span>
                    </label>
                    <textarea id="marco_descripcion" class="form-control" rows="2"
                              placeholder="Descripción breve o referencia normativa..."></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="marco_activo" checked>
                    <label class="form-check-label" for="marco_activo">
                        Marco activo (visible en selectores del sistema)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnGuardarMarco">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ Mini-modal Nuevo Tipo ═══════════════════════════════════════════════ --}}
<div class="modal fade" id="modalNuevoTipo" tabindex="-1" aria-hidden="true" style="z-index:1060">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title mb-0"><i class="fa fa-tag mr-1"></i> Nuevo Tipo</h6>
                <button type="button" class="close text-white" id="btnCerrarNuevoTipo"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label class="font-weight-bold" style="font-size:.85rem">
                        Clave <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="tipo_clave" class="form-control form-control-sm"
                           placeholder="ej: pam, legal, bsc">
                    <small class="text-muted">Solo letras minúsculas, números y guión bajo.</small>
                </div>
                <div class="form-group mb-2">
                    <label class="font-weight-bold" style="font-size:.85rem">
                        Nombre completo <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="tipo_label" class="form-control form-control-sm"
                           placeholder="ej: Plan de Alcance Medio">
                </div>
                <div class="form-group mb-1">
                    <label class="font-weight-bold" style="font-size:.85rem">
                        Color <span class="text-danger">*</span>
                    </label>
                    {{-- Paleta de 20 colores ──────────────────────────────── --}}
                    @php
                    $paleta = \App\Models\Planificacion\MarcoTipo::COLORES;
                    @endphp
                    <div class="d-flex flex-wrap mt-1" style="gap:.35rem">
                        @foreach($paleta as $hex => $nombre)
                        <button type="button"
                                class="colorSwatch"
                                data-color="{{ $hex }}"
                                title="{{ $nombre }}"
                                style="width:28px;height:28px;border-radius:50%;border:2px solid transparent;
                                       background:{{ $hex }};cursor:pointer;transition:transform .12s,border-color .12s">
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="tipo_color">
                    {{-- También permitir hex personalizado --}}
                    <div class="d-flex align-items-center mt-2" style="gap:.5rem">
                        <label style="font-size:.78rem;margin:0;white-space:nowrap">Hex personalizado:</label>
                        <input type="color" id="tipo_color_custom"
                               class="form-control form-control-sm p-0 border-0"
                               style="width:36px;height:28px;cursor:pointer">
                        <input type="text" id="tipo_color_hex_text"
                               class="form-control form-control-sm"
                               style="width:90px;font-size:.78rem"
                               placeholder="#1a237e" maxlength="7">
                    </div>
                </div>
                {{-- Preview --}}
                <div id="colorPreview" class="mt-2" style="display:none">
                    <span class="badge" id="colorPreviewBadge"
                          style="font-size:.82rem;padding:.4em .7em"></span>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnCancelarNuevoTipo">Cancelar</button>
                <button type="button" class="btn btn-sm btn-dark" id="btnGuardarNuevoTipo">
                    <i class="fa fa-check mr-1"></i> Crear tipo
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var tiposMap = {};

    // ── Calcular contraste ──
    function contrastColor(hex) {
        hex = hex.replace('#','');
        var r = parseInt(hex.substr(0,2),16),
            g = parseInt(hex.substr(2,2),16),
            b = parseInt(hex.substr(4,2),16);
        return ((0.299*r + 0.587*g + 0.114*b)/255) > 0.55 ? '#000' : '#fff';
    }

    function actualizarPreviewColor(hex, label) {
        if (!hex) { $('#colorPreview').hide(); return; }
        var badge = $('#colorPreviewBadge');
        badge.attr('style', 'font-size:.82rem;padding:.4em .7em;background:' + hex + ';color:' + contrastColor(hex));
        badge.text((label || hex).toUpperCase());
        $('#colorPreview').show();
    }

    // ── Swatches de la paleta ──
    $(document).on('click', '.colorSwatch', function() {
        var color = $(this).data('color');
        $('.colorSwatch').css({'border-color':'transparent','transform':'scale(1)'});
        $(this).css({'border-color':'#1a237e','transform':'scale(1.25)'});
        $('#tipo_color').val(color);
        $('#tipo_color_custom').val(color);
        $('#tipo_color_hex_text').val(color);
        actualizarPreviewColor(color, $('#tipo_clave').val());
    });

    // Color picker nativo
    $('#tipo_color_custom').on('input', function() {
        var hex = $(this).val();
        $('#tipo_color').val(hex);
        $('#tipo_color_hex_text').val(hex);
        $('.colorSwatch').css({'border-color':'transparent','transform':'scale(1)'});
        actualizarPreviewColor(hex, $('#tipo_clave').val());
    });

    // Input hex manual
    $('#tipo_color_hex_text').on('input', function() {
        var hex = $(this).val();
        if (/^#[0-9a-fA-F]{6}$/.test(hex)) {
            $('#tipo_color').val(hex);
            $('#tipo_color_custom').val(hex);
            $('.colorSwatch').css({'border-color':'transparent','transform':'scale(1)'});
            actualizarPreviewColor(hex, $('#tipo_clave').val());
        }
    });

    // Actualizar preview al escribir clave
    $(document).on('input', '#tipo_clave', function() {
        var v = $(this).val().toLowerCase().replace(/[^a-z0-9_]/g,'');
        $(this).val(v);
        var color = $('#tipo_color').val();
        if (color) actualizarPreviewColor(color, v);
    });

    // ── Cargar tipos desde servidor ──
    function cargarTipos(callback) {
        $.getJSON('{{ route("pei.marcos.tipos") }}', function(data) {
            tiposMap = {};
            data.forEach(function(t) { tiposMap[t.id] = t; });
            if (callback) callback(data);
        });
    }

    // Renderizar badge con color hex
    function renderBadge(item) {
        if (!item.id) return item.text;
        var t     = tiposMap[item.id] || item;
        var color = t.color || '#6c757d';
        var fg    = contrastColor(color);
        return $('<span>' +
            '<span class="badge mr-2" style="background:' + color + ';color:' + fg + ';font-size:.7rem">' +
                item.id.toUpperCase() +
            '</span>' +
            (t.label || item.text) +
        '</span>');
    }

    // ── Inicializar Select2 tipo ──
    function initSelect2Tipo(valorSeleccionado) {
        cargarTipos(function(data) {
            var $sel = $('#marco_tipo');
            if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');

            $sel.select2({
                dropdownParent: $('#modalMarco'),
                placeholder: '— Seleccioná o escribí un tipo —',
                allowClear: true,
                data: data,
                templateResult:    renderBadge,
                templateSelection: renderBadge,
                language: {
                    noResults: function() {
                        var term = $sel.data('select2')?.dropdown?.$search?.val() || '';
                        if (term) {
                            return $('<span class="text-primary" style="cursor:pointer" id="crearTipoLink">' +
                                '<i class="fa fa-plus mr-1"></i> Crear tipo "<strong>' + term + '</strong>"' +
                            '</span>');
                        }
                        return 'Sin resultados';
                    }
                }
            });

            $(document).on('click', '#crearTipoLink', function() {
                var term = $sel.data('select2')?.dropdown?.$search?.val() || '';
                $('#tipo_clave').val(term.toLowerCase().replace(/\s+/g,'_'));
                $('#tipo_label').val('');
                $('#tipo_color').val('');
                $('#tipo_color_custom').val('#ffffff');
                $('#tipo_color_hex_text').val('');
                $('.colorSwatch').css({'border-color':'transparent','transform':'scale(1)'});
                $('#colorPreview').hide();
                $('#modalNuevoTipo').modal('show');
            });

            if (valorSeleccionado) {
                $sel.val(valorSeleccionado).trigger('change');
                var t = tiposMap[valorSeleccionado];
                if (t) {
                    var fg = contrastColor(t.color);
                    $('#tipoBadge').attr('style','background:'+t.color+';color:'+fg+';font-size:.8rem')
                                  .text(t.id.toUpperCase() + ' — ' + t.label);
                    $('#tipoBadgePreview').show();
                }
            }
        });
    }

    $('#marco_tipo').on('select2:select select2:clear', function() {
        var clave = $(this).val();
        if (clave && tiposMap[clave]) {
            var t  = tiposMap[clave];
            var fg = contrastColor(t.color);
            $('#tipoBadge').attr('style','background:'+t.color+';color:'+fg+';font-size:.8rem')
                          .text(clave.toUpperCase() + ' — ' + t.label);
            $('#tipoBadgePreview').show();
        } else {
            $('#tipoBadgePreview').hide();
        }
    });

    // ── Abrir modales ──
    $('#btnNuevoMarco').on('click', function() {
        $('#marco_id').val(''); $('#marco_nombre').val('');
        $('#marco_descripcion').val(''); $('#marco_activo').prop('checked',true);
        $('#tipoBadgePreview').hide();
        $('#modalMarcoTitulo').text('Nuevo Marco Referencial');
        $('#modalMarco').modal('show');
        initSelect2Tipo(null);
    });

    $(document).on('click', '.btnEditar', function() {
        var btn = $(this);
        $('#marco_id').val(btn.data('id'));
        $('#marco_nombre').val(btn.data('nombre'));
        $('#marco_descripcion').val(btn.data('descripcion') || '');
        $('#marco_activo').prop('checked', btn.data('activo') == 1);
        $('#modalMarcoTitulo').text('Editar Marco Referencial');
        $('#modalMarco').modal('show');
        initSelect2Tipo(btn.data('tipo'));
    });

    // ── Guardar marco ──
    $('#btnGuardarMarco').on('click', function() {
        var id     = $('#marco_id').val();
        var nombre = $.trim($('#marco_nombre').val());
        var tipo   = $('#marco_tipo').val();
        if (!nombre || !tipo) { toastr.warning('El nombre y el tipo son obligatorios.'); return; }

        $.ajax({
            url:    id ? '{{ url("pei/marcos") }}/' + id : '{{ route("pei.marcos.store") }}',
            type:   id ? 'PUT' : 'POST',
            data: { nombre, tipo, descripcion: $.trim($('#marco_descripcion').val()), activo: $('#marco_activo').is(':checked') ? 1 : 0 },
            success: function(res) {
                toastr.success(res.nuevo ? 'Marco creado correctamente.' : 'Marco actualizado.');
                $('#modalMarco').modal('hide');
                setTimeout(() => location.reload(), 800);
            },
            error: function(xhr) {
                var e = xhr.responseJSON?.errors;
                if (e) $.each(e, (k,v) => toastr.error(v[0]));
                else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
            }
        });
    });

    // ── Guardar nuevo tipo ──
    $('#btnGuardarNuevoTipo').on('click', function() {
        var clave = $.trim($('#tipo_clave').val());
        var label = $.trim($('#tipo_label').val());
        var color = $('#tipo_color').val();
        if (!clave || !label || !color) { toastr.warning('Completá clave, nombre y color.'); return; }

        $.ajax({
            url: '{{ route("pei.marcos.tipos.store") }}', type: 'POST',
            data: { clave, label, color },
            success: function(res) {
                if (!res.ok) { toastr.error('Error al crear tipo.'); return; }
                tiposMap[res.tipo.id] = res.tipo;
                toastr.success('Tipo "' + clave.toUpperCase() + '" creado.');
                $('#modalNuevoTipo').modal('hide');
                initSelect2Tipo(res.tipo.id);
            },
            error: function(xhr) {
                var e = xhr.responseJSON?.errors;
                if (e) $.each(e, (k,v) => toastr.error(v[0]));
                else toastr.error('Error al crear tipo.');
            }
        });
    });

    $('#btnCerrarNuevoTipo, #btnCancelarNuevoTipo').on('click', function() {
        $('#modalNuevoTipo').modal('hide');
    });

    // ── Toggle ──
    $(document).on('click', '.btnToggle', function() {
        var btn = $(this), id = btn.data('id');
        $.ajax({ url: '{{ url("pei/marcos") }}/' + id + '/toggle', type: 'PATCH',
            success: function(res) {
                btn.find('i').toggleClass('text-success', res.activo).toggleClass('text-secondary', !res.activo);
                btn.attr('title', res.activo ? 'Desactivar' : 'Activar');
                $('#row-' + id).toggleClass('text-muted', !res.activo);
                toastr.success(res.activo ? 'Marco activado.' : 'Marco desactivado.');
            }
        });
    });

    // ── Eliminar ──
    $(document).on('click', '.btnEliminar', function() {
        var btn = $(this), id = btn.data('id'), nombre = btn.data('nombre');
        Swal.fire({
            title: '¿Eliminar este marco?',
            html: '<strong>' + nombre + '</strong><br><small class="text-muted">Si está vinculado a objetivos, no se podrá eliminar.</small>',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
        }).then(function(result) {
            if (!result.isConfirmed) return;
            $.ajax({ url: '{{ url("pei/marcos") }}/' + id, type: 'DELETE',
                success: function(res) {
                    if (res.ok) { $('#row-' + id).fadeOut(300, function() { $(this).remove(); }); toastr.success('Marco eliminado.'); }
                    else toastr.warning(res.mensaje);
                },
                error: function(xhr) { toastr.error(xhr.responseJSON?.mensaje || 'Error al eliminar.'); }
            });
        });
    });
});
</script>
@stop
