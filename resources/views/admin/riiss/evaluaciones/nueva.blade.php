@extends('layouts.master')
@section('title', 'Evaluación — ' . $est->nombre_oficial)

@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
<style>
.select2-container--default .select2-selection--multiple { 
    border:1px solid #ced4da; 
    border-radius:6px; 
    min-height:42px !important; 
    height: 42px !important; /* Force height for single row */
    padding: 0 8px !important; 
    display: flex !important; 
    align-items: center !important; 
    overflow: hidden !important;
}

.select2-container--default.select2-container--focus .select2-selection--multiple,
.select2-container--default.select2-container--open .select2-selection--multiple {
    height: auto !important; /* Allow growth only when active/focused if many choices */
    min-height: 42px !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered { 
    display: flex !important; 
    flex-wrap: nowrap !important; /* Keep single line by default */
    gap: 4px; 
    padding: 0 !important; 
    margin: 0 !important; 
    list-style: none; 
    align-items: center; 
    width: 100%;
}

.select2-container--default.select2-container--focus .select2-selection__rendered,
.select2-container--default.select2-container--open .select2-selection__rendered {
    flex-wrap: wrap !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice { 
    background:#e91e63; 
    border:none; 
    color:#fff; 
    border-radius:20px; 
    padding:1px 10px; 
    font-size:.78rem; 
    margin: 0 !important; 
    line-height: 1.5;
}

/* Search field idle state */
.select2-search--inline { margin: 0 !important; padding: 0 !important; height: 100% !important; display: flex !important; align-items: center !important; }
.select2-search__field { margin: 0 !important; height: 30px !important; line-height: 30px !important; }

.select2-container--default .select2-selection--single { border:1px solid #ced4da; border-radius:6px; height:42px !important; padding:6px 8px; display: flex; align-items: center; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: normal !important; padding-left: 0 !important; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height:40px !important; }

/* Standard inputs height sync */
#evalTelefono, #evalFecha { height: 42px !important; border: 1px solid #ced4da !important; border-radius: 6px !important; padding: 6px 8px !important; }

/* Fix for Select2 search on mobile and modals */
.select2-container--open { z-index: 99999 !important; }
.select2-dropdown { z-index: 99999 !important; border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 4px 16px rgba(0,0,0,.1); }

/* Responsive Fixes for Select2 */
.select2-container { width: 100% !important; display: block; }
.select2-selection { width: 100% !important; }

/* Force search field to be reachable but not intrusive */
.select2-container .select2-search--inline { 
    display: inline-block !important; 
    vertical-align: middle !important;
}
.select2-container .select2-search--inline .select2-search__field { 
    width: auto !important;
    min-width: 30px !important; /* Minimal touch area */
    max-width: 100% !important;
    margin-top: 0 !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

/* Grow and highlight ONLY when focused or searching */
.select2-container--focus .select2-search__field,
.select2-container--open .select2-search__field {
    min-width: 150px !important;
    background-color: #fff !important;
    border: 1px solid #e91e63 !important; /* Highlight color */
    border-radius: 4px !important;
    padding: 2px 8px !important;
}

/* Material Design Fixes */
.bmd-form-group { padding-top: 0 !important; margin-bottom: 0 !important; }
.form-group { margin-bottom: 0 !important; }

/* Reset Material Design backgrounds on Select2 inputs */
.select2-search__field {
    background-image: none !important;
    background-color: #fff !important;
    border: 1px solid #ced4da !important;
    padding: 6px 10px !important;
    margin-top: 5px !important;
    box-shadow: none !important;
    outline: none !important;
    width: 100% !important;
    pointer-events: auto !important;
    display: inline-block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Ensure the dropdown is above everything and interactive */
.select2-container--open { z-index: 999999 !important; pointer-events: auto !important; }
.select2-dropdown { 
    z-index: 999999 !important; 
    pointer-events: auto !important;
    border-radius: 8px; 
    border: 1px solid #e5e7eb; 
    box-shadow: 0 4px 16px rgba(0,0,0,0.15); 
}

/* Force interactivity on all Select2 parts */
.select2-selection, .select2-results, .select2-results__option {
    pointer-events: auto !important;
}

/* Responsive Fixes for Select2 */
.select2-container { width: 100% !important; display: block; }
.select2-selection { width: 100% !important; min-height: 42px !important; }

/* Force search field width in multi-select */
.select2-container .select2-search--inline { width: 100% !important; display: block !important; }
.select2-container .select2-search--inline .select2-search__field { 
    width: 100% !important; 
    min-width: 100px !important; 
    margin-left: 0 !important;
}

/* Neutralize Material Design focus/transition effects */
.bmd-form-group .select2-container { position: relative; }
.bmd-form-group .select2-container::before, 
.bmd-form-group .select2-container::after { display: none !important; }

@media (max-width: 768px) {
    .select2-container { margin-bottom: 15px; }
    .select2-search__field { font-size: 16px !important; height: 40px !important; }
}

#evalWrapper { display:flex; gap:16px; align-items:flex-start; }
#sidebarSecciones { width:260px; flex-shrink:0; position:sticky; top:80px; max-height:calc(100vh - 100px); overflow-y:auto; overflow-x:visible; }
#contenidoFormulario { flex:1; min-width:0; }

/* Ensure the card and rows don't clip the Select2 dropdown */
.card, .card-body, .row { overflow: visible !important; }

/* Critical: stable coordinate system for Select2 at 767px stacking */
.col-md-3, .col-md-4, .col-md-6, .col-12 { 
    position: relative !important; 
    overflow: visible !important; 
}

/* Prevent jumping to top on mobile */
.select2-container--open .select2-dropdown {
    margin-top: -1px; /* Align perfectly with input */
}

.sec-item { display:flex; align-items:center; gap:8px; padding:7px 12px; border-radius:8px; cursor:pointer; font-size:.82rem; transition:background .15s; border:none; background:none; width:100%; text-align:left; }
.sec-item:hover { background:#f8f8f8; }
.sec-item.active { background:#fce4ec; color:#c62828; font-weight:600; }
.sec-item .sec-dot { width:8px; height:8px; border-radius:50%; background:#e0e0e0; flex-shrink:0; }
.sec-item.completa .sec-dot { background:#22c55e; }
.sec-item.parcial  .sec-dot { background:#f97316; }

.seccion-card { border-radius:12px; border:1px solid #e5e7eb; margin-bottom:20px; overflow:hidden; }
.seccion-card .seccion-header { background:#f9fafb; padding:14px 18px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; gap:10px; }
.seccion-card .seccion-body { padding:18px; }

.pregunta-item { padding:12px 0; border-bottom:1px solid #f3f4f6; }
.pregunta-item:last-child { border-bottom:none; }
.pregunta-label { font-size:.88rem; font-weight:500; margin-bottom:8px; }
.pregunta-label .req-star { color:#e91e63; }

.resp-group { display:flex; gap:8px; flex-wrap:wrap; }
.resp-btn { padding:5px 14px; border-radius:20px; border:1.5px solid #e0e0e0; background:#fff; font-size:.8rem; cursor:pointer; transition:all .15s; }
.resp-btn:hover { border-color:#e91e63; color:#e91e63; }
.resp-btn.selected-si  { background:#d1fae5; border-color:#22c55e; color:#065f46; font-weight:600; }
.resp-btn.selected-no  { background:#fee2e2; border-color:#ef4444; color:#991b1b; font-weight:600; }
.resp-btn.selected-na  { background:#f1f5f9; border-color:#94a3b8; color:#475569; font-weight:600; }

#barraProgreso { height:8px; border-radius:4px; background:rgba(255,255,255,.3); overflow:hidden; }
#barraFill { height:100%; border-radius:4px; background:#fff; transition:width .3s; }

#panelResultado { display:none; }
.resultado-card { border-radius:16px; padding:24px; text-align:center; }
.resultado-CUMPLE              { background:linear-gradient(135deg,#d1fae5,#a7f3d0); }
.resultado-CUMPLE_PARCIALMENTE { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.resultado-NO_CUMPLE           { background:linear-gradient(135deg,#fee2e2,#fecaca); }

@media(max-width:768px) {
    #evalWrapper { flex-direction:column; }
    #sidebarSecciones { width:100%; position:static; max-height:none; }
}
</style>
@endpush

@section('content')
{{-- Header --}}
<div class="card mb-3">
    <div class="card-header card-header-danger py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0">
                    <i class="fa fa-clipboard-check mr-2"></i>{{ $est->nombre_oficial }}
                </h4>
                <p class="card-category mb-0">{{ $est->tipologia_clasificacion }} — {{ $est->complejidad }}</p>
            </div>
            <div class="text-right">
                <div id="barraProgreso" style="width:160px;margin-bottom:4px;height:8px;border-radius:4px;background:rgba(255,255,255,.3);overflow:hidden">
                    <div id="barraFill" style="height:100%;width:0%;border-radius:4px;background:#fff;transition:width .3s"></div>
                </div>
                <small class="text-white" id="txtProgreso">Cargando formulario...</small>
            </div>
        </div>
    </div>
    <nav class="bg-light px-3 py-2">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">Establecimientos</a></li>
            <li class="breadcrumb-item active">Evaluación</li>
        </ol>
    </nav>
</div>

<div id="evalWrapper">

    {{-- Sidebar --}}
    <div id="sidebarSecciones">
        <div class="card shadow-sm">
            <div class="card-body p-2">
                <div class="px-2 mb-2">
                    <select id="buscadorPreguntas" style="width:100%"></select>
                </div>
                <div class="small font-weight-bold text-uppercase text-muted px-2 mb-2">Secciones</div>
                <div id="listaSecciones">
                    <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <div id="contenidoFormulario">

        {{-- Card unificado: Datos del establecimiento + Visita --}}
        <div class="card shadow-sm mb-4" id="cardDatosEstablecimiento">
            <div class="card-body">
                <h6 class="font-weight-bold mb-3">
                    <i class="fa fa-hospital mr-2 text-danger"></i>Datos del establecimiento
                    <small class="text-muted font-weight-normal ml-2" id="evalEstado"></small>
                </h6>

                <div class="row">
                    {{-- Nombre (prellenado, solo lectura) --}}
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Nombre del establecimiento</label>
                        <input type="text" class="form-control" value="{{ $est->nombre_oficial }}" readonly
                               style="background:#f9fafb;color:#374151;font-weight:600">
                    </div>
                    {{-- Razón Social --}}
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Nombre o Razón Social</label>
                        <input type="text" id="estRazonSocial" class="form-control"
                               placeholder="Nombre oficial o razón social..."
                               value="{{ $est->nm_empresa_costos ?? '' }}">
                    </div>
                    {{-- Dirección --}}
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Dirección del establecimiento</label>
                        <input type="text" id="estDireccion" class="form-control" placeholder="Dirección completa...">
                    </div>
                    {{-- Teléfono --}}
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Teléfono</label>
                        <input type="text" id="evalTelefono" class="form-control" placeholder="0981...">
                    </div>
                    {{-- Email --}}
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Correo electrónico</label>
                        <input type="email" id="estEmail" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <hr class="my-3">

                {{-- Evaluadores y fecha --}}
                <h6 class="font-weight-bold mb-3 text-muted small text-uppercase">
                    <i class="fa fa-user-check mr-1"></i>Datos de la visita
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Evaluadores <span class="text-danger">*</span></label>
                        <select id="evalEvaluadores" class="form-control" multiple style="width:100%"></select>
                        <small class="text-muted">Uno o más evaluadores</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold"><i class="fa fa-bullseye text-info mr-1"></i> Plan PEI / Marco Estratégico</label>
                        <select id="evalPeiProfile" class="form-control" style="width:100%"></select>
                        <small class="text-muted">Asocia esta evaluación a un Plan PEI específico</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Fecha de evaluación <span class="text-danger">*</span></label>
                        <input type="date" id="evalFecha" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Enlace de ubicación</label>
                        <input type="text" id="estMapLink" class="form-control" placeholder="https://maps.google.com/...">
                    </div>
                </div>

                <hr class="my-3">

                {{-- Ubicación --}}
                <h6 class="font-weight-bold mb-2 text-muted small text-uppercase">
                    <i class="fa fa-map-marker-alt mr-1"></i>Ubicación del establecimiento
                </h6>
                @include('admin.riiss.partials.localidad_select', ['prefix' => 'eval', 'required' => false])
            </div>
        </div>

        {{-- Secciones del formulario --}}
        <div id="seccionesFormulario"></div>

        {{-- Botones --}}
        <div id="botonesAccion" class="card shadow-sm mb-4" style="display:none">
            <div class="card-body d-flex justify-content-between align-items-center">
                <span class="text-muted small" id="txtRespuestas">0 respuestas</span>
                <div>
                    <button class="btn btn-outline-secondary mr-2" onclick="guardarParcial()">
                        <i class="fa fa-save mr-1"></i>Guardar parcial
                    </button>
                    <button class="btn btn-danger" onclick="finalizarEvaluacion()">
                        <i class="fa fa-check-circle mr-1"></i>Finalizar y analizar
                    </button>
                </div>
            </div>
        </div>

        {{-- Resultado --}}
        <div id="panelResultado" class="mb-4"></div>

    </div>
</div>
@endsection

@section('scripts')
<script>
const EST_ID = '{{ $est->id_establecimiento }}';
let evaluacionId = null;
let formulario   = null;
let respuestas   = {};
let checklistState = {};

// ── Localidad Select2 encadenado ──────────────────────────────────────────────
function initLocalidadSelect(P) {
    var URL_DEPTOS  = '{{ route("riiss.localidades.departamentos") }}';
    var URL_DISTS   = '{{ route("riiss.localidades.distritos") }}';
    var URL_BARRIOS = '{{ route("riiss.localidades.barrios") }}';

    var S2 = {
        width: '100%', 
        allowClear: true, 
        dropdownParent: $('#contenidoFormulario'),
        language: {
            noResults:  function() { return 'Sin resultados'; },
            searching:  function() { return 'Buscando...'; },
        },
    };

    // 1. Inicializar los 3 campos inmediatamente
    $('#' + P + '-depto').select2($.extend({}, S2, {
        placeholder: 'Seleccioná departamento',
        ajax: {
            url: URL_DEPTOS, dataType: 'json', delay: 150,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; },
            cache: true,
        },
    }));

    $('#' + P + '-dist').select2($.extend({}, S2, {
        placeholder: 'Seleccioná ciudad/distrito',
        ajax: {
            url: URL_DISTS, dataType: 'json', delay: 150,
            data: function(p) { 
                return { q: p.term || '', cod_dpto: $('#' + P + '-depto').val() }; 
            },
            processResults: function(d) { return { results: d.results }; },
            cache: true,
        },
    }));

    $('#' + P + '-barrio').select2($.extend({}, S2, {
        placeholder: 'Seleccioná barrio/localidad',
        ajax: {
            url: URL_BARRIOS, dataType: 'json', delay: 150,
            data: function(p) { 
                return { q: p.term || '', cod_dpto: $('#' + P + '-depto').val(), cod_dist: $('#' + P + '-dist').val() }; 
            },
            processResults: function(d) { return { results: d.results }; },
            cache: true,
        },
    }));

    // Precargar departamentos para facilitar selección inicial
    $.get(URL_DEPTOS, { q: '' }, function(r) {
        if (!r.results) return;
        r.results.forEach(function(item) {
            if ($('#' + P + '-depto').find("option[value='" + item.id + "']").length === 0) {
                $('#' + P + '-depto').append(new Option(item.text, item.id, false, false));
            }
        });
        $('#' + P + '-depto').trigger('change.select2');
    });

    // 2. Manejar cascada (limpiar hijos cuando el padre cambia)
    $('#' + P + '-depto').on('change', function() {
        $('#' + P + '-dist').val(null).trigger('change');
        $('#' + P + '-barrio').val(null).trigger('change');
    });

    $('#' + P + '-dist').on('change', function() {
        $('#' + P + '-barrio').val(null).trigger('change');
    });

    // API pública para obtener valores
    window[P + 'GetLocalidad'] = function() {
        return {
            departamento: { 
                cod: $('#' + P + '-depto').val(), 
                text: $('#' + P + '-depto option:selected').text().trim() 
            },
            distrito: { 
                cod: $('#' + P + '-dist').val(),  
                text: $('#' + P + '-dist option:selected').text().trim() 
            },
            barrio: { 
                id:  $('#' + P + '-barrio').val(), 
                text: $('#' + P + '-barrio option:selected').text().trim() 
            },
        };
    };
}

$(document).ready(function() {
    // Inicializar localidad
    initLocalidadSelect('eval');

    // Autosave cuando cambia la ubicación
    $('#eval-depto, #eval-dist, #eval-barrio').on('change', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });

    // ── Select2 evaluadores ───────────────────────────────────────────────
    $('#evalEvaluadores').select2({
        placeholder: 'Buscar evaluador...', allowClear: true, multiple: true,
        minimumInputLength: 0, width: '100%',
        dropdownParent: $('#contenidoFormulario'),
        language: { noResults: function() { return 'Sin resultados'; }, searching: function() { return 'Buscando...'; } },
        ajax: {
            url: '{{ route("riiss.evaluaciones.usuarios") }}', dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; }, cache: true,
        },
    });

    // ── Select2 Plan PEI ───────────────────────────────────────────────
    $('#evalPeiProfile').select2({
        placeholder: '— Plan PEI 2024–2028 (Default) —', allowClear: true, width: '100%',
        dropdownParent: $('#contenidoFormulario'),
        ajax: {
            url: '{{ route("globales.get-pei-profiles") }}', dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d }; }
        }
    });

    // Evitar que BMD robe el foco del input de búsqueda inline
    $(document).on('focusin', '.select2-selection--multiple', function(e) {
        e.stopImmediatePropagation();
    });
    $(document).on('mousedown', '.select2-selection--multiple', function(e) {
        e.stopImmediatePropagation();
    });
// Preseleccionar usuario actual
@if(auth()->check())
$('#evalEvaluadores').append(new Option('{{ addslashes(auth()->user()->name) }}', {{ auth()->id() }}, true, true)).trigger('change');
@endif

// ── Cargar o Crear evaluación ─────────────────────────────────────────────
const urlParams = new URLSearchParams(window.location.search);
evaluacionId = urlParams.get('evaluacion');

// Si no hay en URL, buscar en localStorage para este establecimiento
if (!evaluacionId) {
    evaluacionId = localStorage.getItem('riiss_eval_' + EST_ID);
}

if (evaluacionId) {
    recuperarEvaluacionExistente(evaluacionId);
} else {
    crearEvaluacionYCargar();
}

// Guardar datos de visita al cambiar (debounce)
    var saveTimer;

    // Aggressive Fix for Select2 search focus and interaction
    $(document).on('select2:opening', function(e) {
        // Stop theme scripts from capturing this opening event
        e.stopPropagation();
    });

    $(document).on('select2:open', function(e) {
        const dropdown = $('.select2-container--open');
        if (dropdown.length) {
            const searchField = dropdown.find('.select2-search__field');
            if (searchField.length) {
                // Remove any focus-stealing attributes
                searchField.attr('readonly', false);
                searchField.css('pointer-events', 'auto');
                
                setTimeout(() => {
                    searchField[0].focus();
                    searchField[0].setSelectionRange(0, 999); // Force selection on some mobile browsers
                }, 200);
            }
        }
    });

    $('#evalFecha, #evalTelefono, #estEmail, #estDireccion, #estRazonSocial, #estMapLink').on('change input', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });
    $('#evalEvaluadores').on('change', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });
    $('#eval-depto, #eval-dist, #eval-barrio').on('change', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });
});

// ── Recuperar evaluación por ID ──────────────────────────────────────────────
function recuperarEvaluacionExistente(id) {
    $('#evalEstado').html('<span class="badge badge-info">Cargando evaluación #' + id + '...</span>');
    $.get('/riiss/evaluaciones/' + id, function(r) {
        if (!r.ok) {
            mostrarToast('No se pudo cargar la evaluación', 'error');
            return;
        }
        const ev = r.data;
        $('#evalFecha').val(ev.fecha_evaluacion.split('T')[0]);
        $('#evalTelefono').val(ev.evaluador_telefono);

        // Restaurar campos adicionales desde metadata
        if (ev.metadata) {
            if (ev.metadata.email)        $('#estEmail').val(ev.metadata.email);
            if (ev.metadata.direccion)    $('#estDireccion').val(ev.metadata.direccion);
            if (ev.metadata.razon_social) $('#estRazonSocial').val(ev.metadata.razon_social);
            if (ev.metadata.map_link)     $('#estMapLink').val(ev.metadata.map_link);
        }
        // Cargar evaluadores
        if (ev.evaluadores && ev.evaluadores.length) {
            $('#evalEvaluadores').empty();
            ev.evaluadores.forEach(function(u) {
                if ($('#evalEvaluadores').find("option[value='" + u.id + "']").length === 0) {
                    $('#evalEvaluadores').append(new Option(u.text, u.id, true, true));
                } else {
                    $('#evalEvaluadores').val(u.id).trigger('change.select2');
                }
            });
            $('#evalEvaluadores').trigger('change');
        }

        // Cargar ubicación si existe en metadata
        if (ev.metadata && ev.metadata.ubicacion) {
            const u = ev.metadata.ubicacion;
            if (u.departamento && u.departamento.cod) {
                if ($('#eval-depto').find("option[value='" + u.departamento.cod + "']").length === 0) {
                    $('#eval-depto').append(new Option(u.departamento.text, u.departamento.cod, true, true)).trigger('change');
                } else {
                    $('#eval-depto').val(u.departamento.cod).trigger('change');
                }
                
                setTimeout(function() {
                    if (u.distrito && u.distrito.cod) {
                        if ($('#eval-dist').find("option[value='" + u.distrito.cod + "']").length === 0) {
                            $('#eval-dist').append(new Option(u.distrito.text, u.distrito.cod, true, true)).trigger('change');
                        } else {
                            $('#eval-dist').val(u.distrito.cod).trigger('change');
                        }
                        
                        setTimeout(function() {
                            if (u.barrio && u.barrio.id) {
                                if ($('#eval-barrio').find("option[value='" + u.barrio.id + "']").length === 0) {
                                    $('#eval-barrio').append(new Option(u.barrio.text, u.barrio.id, true, true)).trigger('change');
                                } else {
                                    $('#eval-barrio').val(u.barrio.id).trigger('change');
                                }
                            }
                        }, 600);
                    }
                }, 600);
            }
        }

        // Cargar respuestas ya guardadas
        if (ev.respuestas && ev.respuestas.length) {
            ev.respuestas.forEach(function(res) {
                respuestas[res.formulario_pregunta_id] = res.respuesta;
            });
        }

        $('#evalEstado').html('<span class="badge badge-success">Evaluación #' + id + ' activa</span>');

        // Cargar formulario y DESPUÉS aplicar las respuestas visualmente
        cargarFormulario(function() {
            aplicarRespuestasVisuales(ev.respuestas || []);
        });
    });
}

// ── Crear evaluación al cargar la página ─────────────────────────────────────
function crearEvaluacionYCargar() {
    $('#evalEstado').html('<span class="badge badge-warning">Creando...</span>');

    $.ajax({
        url: '{{ route("riiss.evaluaciones.crear") }}',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token:             '{{ csrf_token() }}',
            id_establecimiento: EST_ID,
            fecha_evaluacion:   $('#evalFecha').val(),
            evaluadores:        [{ id: {{ auth()->id() ?? 0 }}, text: '{{ addslashes(auth()->user()?->name ?? "") }}' }],
            pei_profile_id:     $('#evalPeiProfile').val() || null,
        }),
        success: function(r) {
            if (!r.ok) return;
            evaluacionId = r.data.id;

            // Persistir en URL y localStorage para sobrevivir refresh
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?evaluacion=' + evaluacionId;
            window.history.replaceState({path:newUrl}, '', newUrl);
            localStorage.setItem('riiss_eval_' + EST_ID, evaluacionId);

            $('#evalEstado').html('<span class="badge badge-success">Evaluación #' + evaluacionId + ' activa</span>');
            cargarFormulario();

            // Enviar respuestas que se marcaron antes de que se creara la evaluación
            setTimeout(enviarColaRespuestas, 1000);
        },
        error: function(xhr) {
            $('#evalEstado').html('<span class="badge badge-danger">Error al crear</span>');
            console.error(xhr.responseJSON);
        }
    });
}

// ── Actualizar datos de visita en background ──────────────────────────────────
function actualizarDatosVisita() {
    if (!evaluacionId) return;
    const evalData   = $('#evalEvaluadores').select2('data');
    const evaluadores = evalData.map(function(e) { return { id: e.id, text: e.text }; });
    const ubicacion  = typeof evalGetLocalidad === 'function' ? evalGetLocalidad() : null;

    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/datos-visita',
        method: 'PATCH',
        contentType: 'application/json',
        data: JSON.stringify({
            _token:             '{{ csrf_token() }}',
            fecha_evaluacion:   $('#evalFecha').val(),
            evaluadores:        evaluadores,
            evaluador_telefono: $('#evalTelefono').val(),
            metadata: {
                ubicacion:    ubicacion,
                email:        $('#estEmail').val(),
                direccion:    $('#estDireccion').val(),
                razon_social: $('#estRazonSocial').val(),
                map_link:     $('#estMapLink').val(),
            },
        }),
        success: function() { mostrarToast('Datos guardados', 'success'); },
    });
}
</script>
@endsection

@push('scripts')
<script>
// ── Cargar formulario ─────────────────────────────────────────────────────────
function cargarFormulario(callback) {
    $('#listaSecciones').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>');
    $('#seccionesFormulario').html('<div class="text-center py-5"><div class="spinner-border text-danger"></div><p class="text-muted mt-2">Cargando formulario...</p></div>');

    $.get('/riiss/evaluaciones/formulario/' + EST_ID, function(r) {
        if (!r.ok) return;
        formulario = r.data;
        renderSidebar(formulario.secciones);
        renderSecciones(formulario.secciones);
        $('#botonesAccion').show();
        actualizarProgreso();
        initBuscadorPreguntas();

        // Ejecutar callback después de renderizar (para aplicar respuestas guardadas)
        if (typeof callback === 'function') {
            setTimeout(callback, 150);
        }
    });
}

// ── Aplicar respuestas guardadas visualmente ─────────────────────────────────
function aplicarRespuestasVisuales(listaRespuestas) {
    if (!listaRespuestas || !listaRespuestas.length) return;

    listaRespuestas.forEach(function(res) {
        var pid  = res.formulario_pregunta_id;
        var val  = (res.respuesta || '').toString().toLowerCase().trim();
        var $preg = $('#preg-' + pid);
        if (!$preg.length) return;

        var $btns = $preg.find('.resp-btn');
        if ($btns.length) {
            // Botones Sí/No/NA
            $btns.each(function() {
                var txt = $(this).text().toLowerCase();
                if (val === 'si' || val === 'sí') {
                    if (txt.indexOf('sí') >= 0 || txt.indexOf('si') >= 0) {
                        $(this).addClass('selected-si');
                    }
                } else if (val === 'no aplica') {
                    if (txt.indexOf('no aplica') >= 0) {
                        $(this).addClass('selected-na');
                    }
                } else if (val === 'no') {
                    if (txt.indexOf('no') >= 0 && txt.indexOf('aplica') < 0) {
                        $(this).addClass('selected-no');
                    }
                } else {
                    // Checklist
                    if (txt.indexOf(val) >= 0) {
                        $(this).addClass('selected-si');
                    }
                }
            });
        } else {
            // Input de texto/número
            var $input = $preg.find('input[type="text"], input[type="number"], textarea');
            if ($input.length) {
                $input.val(res.respuesta);
            }
        }

        // Actualizar objeto respuestas y sidebar
        respuestas[pid] = res.respuesta;
        actualizarSidebarSeccion(pid);
    });

    actualizarProgreso();
}

// ── Sidebar ───────────────────────────────────────────────────────────────────
function renderSidebar(secciones) {
    var html = '';
    secciones.forEach(function(s) {
        html += '<button class="sec-item" id="side-' + s.id + '" onclick="irSeccion(' + s.id + ')">'
            + '<span class="sec-dot"></span>'
            + '<span class="flex-grow-1">' + s.seccion + '</span>'
            + '<small class="text-muted">' + s.preguntas.length + '</small>'
            + '</button>';
    });
    $('#listaSecciones').html(html);
}

// ── Secciones ─────────────────────────────────────────────────────────────────
function renderSecciones(secciones) {
    var html = '';
    secciones.forEach(function(s) {
        var badge = s.requerida
            ? '<span class="badge badge-danger ml-auto">Requerida</span>'
            : '<span class="badge badge-secondary ml-auto">Opcional</span>';
        var sub = s.sub_seccion ? '<br><small class="text-muted">' + s.sub_seccion + '</small>' : '';
        html += '<div class="seccion-card" id="sec-' + s.id + '">'
            + '<div class="seccion-header"><i class="fa fa-folder-open text-danger"></i>'
            + '<div><strong>' + s.seccion + '</strong>' + sub + '</div>' + badge + '</div>'
            + '<div class="seccion-body">' + s.preguntas.map(renderPregunta).join('') + '</div>'
            + '</div>';
    });
    $('#seccionesFormulario').html(html);
}

// ── Pregunta ──────────────────────────────────────────────────────────────────
function renderPregunta(p) {
    var req = p.requerida ? '<span class="req-star">*</span>' : '';
    var vinculo = p.servicio_cartera_grupo
        ? '<small class="text-muted ml-2"><i class="fa fa-link"></i> ' + p.servicio_cartera_grupo + '</small>'
        : '';
    var input = '';

    if (p.tipo_respuesta === 'si_no') {
        input = '<div class="resp-group">'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'Si\',this,\'si\')">✅ Sí</button>'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'No\',this,\'no\')">❌ No</button>'
            + '</div>';
    } else if (p.tipo_respuesta === 'si_no_na') {
        input = '<div class="resp-group">'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'Si\',this,\'si\')">✅ Sí</button>'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'No\',this,\'no\')">❌ No</button>'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'No Aplica\',this,\'na\')">⬜ No Aplica</button>'
            + '</div>';
    } else if (p.tipo_respuesta === 'checklist' && p.opciones) {
        input = '<div class="resp-group">'
            + p.opciones.map(function(op) {
                return '<button class="resp-btn" onclick="toggleChecklist(' + p.id + ',\'' + op + '\',this)">' + op + '</button>';
            }).join('')
            + '</div>';
    } else if (p.tipo_respuesta === 'numero') {
        input = '<input type="number" class="form-control form-control-sm" style="max-width:120px" onchange="setRespTexto(' + p.id + ',this.value)" placeholder="0">';
    } else {
        var pre = p.valor_prellenado ? 'value="' + p.valor_prellenado + '"' : '';
        input = '<input type="text" class="form-control form-control-sm" ' + pre
            + ' onchange="setRespTexto(' + p.id + ',this.value)"'
            + ' placeholder="' + (p.respuesta_ejemplo || 'Ingrese respuesta...') + '">';
        if (p.valor_prellenado) {
            setTimeout(function() { setRespTexto(p.id, p.valor_prellenado); }, 100);
        }
    }

    return '<div class="pregunta-item" id="preg-' + p.id + '">'
        + '<div class="pregunta-label">' + req + ' ' + p.pregunta + vinculo + '</div>'
        + input + '</div>';
}

// ── Respuestas ────────────────────────────────────────────────────────────────
var autosaveTimers = {};
var colaRespuestas = {}; // respuestas pendientes mientras evaluacionId es null

function autosaveRespuesta(id, valor) {
    // Si aún no hay evaluación, encolar
    if (!evaluacionId) {
        colaRespuestas[id] = valor;
        return;
    }
    clearTimeout(autosaveTimers[id]);
    autosaveTimers[id] = setTimeout(function() {
        $.ajax({
            url: '/riiss/evaluaciones/' + evaluacionId + '/respuestas',
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify({
                _token: '{{ csrf_token() }}',
                respuestas: [{ formulario_pregunta_id: parseInt(id), respuesta: valor }]
            }),
            success: function() {
                $('#preg-' + id).find('.autosave-indicator').remove();
                $('#preg-' + id).append('<span class="autosave-indicator text-success" style="font-size:.7rem;margin-left:8px"><i class="fa fa-check"></i></span>');
                setTimeout(function() { $('#preg-' + id).find('.autosave-indicator').fadeOut(500, function(){ $(this).remove(); }); }, 2000);
            }
        });
    }, 500);
}

// Enviar respuestas encoladas cuando se crea la evaluación
function enviarColaRespuestas() {
    var ids = Object.keys(colaRespuestas);
    if (!ids.length || !evaluacionId) return;
    var payload = ids.map(function(id) {
        return { formulario_pregunta_id: parseInt(id), respuesta: colaRespuestas[id] };
    });
    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/respuestas',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({ _token: '{{ csrf_token() }}', respuestas: payload }),
        success: function() {
            colaRespuestas = {};
        }
    });
}

function setResp(id, valor, btn, tipo) {
    respuestas[id] = valor;
    $(btn).closest('.resp-group').find('.resp-btn').removeClass('selected-si selected-no selected-na');
    $(btn).addClass('selected-' + tipo);
    actualizarProgreso();
    actualizarSidebarSeccion(id);
    autosaveRespuesta(id, valor);
}

function setRespTexto(id, valor) {
    if (valor && valor.toString().trim()) {
        respuestas[id] = valor.toString().trim();
        actualizarProgreso();
        actualizarSidebarSeccion(id);
        autosaveRespuesta(id, valor);
    }
}

function toggleChecklist(id, opcion, btn) {
    if (!checklistState[id]) checklistState[id] = [];
    var idx = checklistState[id].indexOf(opcion);
    if (idx >= 0) { checklistState[id].splice(idx, 1); $(btn).removeClass('selected-si'); }
    else          { checklistState[id].push(opcion);   $(btn).addClass('selected-si'); }
    respuestas[id] = checklistState[id].join(', ');
    actualizarProgreso();
    autosaveRespuesta(id, respuestas[id]);
}

// ── Progreso ──────────────────────────────────────────────────────────────────
function actualizarProgreso() {
    if (!formulario) return;
    var total = formulario.resumen.total_preguntas;
    var respondidas = Object.keys(respuestas).length;
    var pct = total > 0 ? Math.round((respondidas / total) * 100) : 0;
    $('#barraFill').css('width', pct + '%');
    $('#txtProgreso').text(pct + '% completado');
    $('#txtRespuestas').text(respondidas + ' de ' + total + ' respuestas');
}

function actualizarSidebarSeccion(preguntaId) {
    if (!formulario) return;
    formulario.secciones.forEach(function(s) {
        var ids = s.preguntas.map(function(p) { return p.id; });
        if (ids.indexOf(preguntaId) < 0) return;
        var resp = ids.filter(function(id) { return respuestas[id]; }).length;
        var $dot = $('#side-' + s.id).find('.sec-dot').removeClass('completa parcial');
        if (resp === ids.length) $dot.addClass('completa');
        else if (resp > 0)       $dot.addClass('parcial');
    });
}

// ── Navegación ────────────────────────────────────────────────────────────────
function irSeccion(id) {
    var el = document.getElementById('sec-' + id);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    $('.sec-item').removeClass('active');
    $('#side-' + id).addClass('active');
}

$(window).on('scroll', function() {
    if (!formulario) return;
    formulario.secciones.forEach(function(s) {
        var el = document.getElementById('sec-' + s.id);
        if (!el) return;
        var rect = el.getBoundingClientRect();
        if (rect.top <= 120 && rect.bottom >= 120) {
            $('.sec-item').removeClass('active');
            $('#side-' + s.id).addClass('active');
        }
    });
});

// ── Guardar / Finalizar ───────────────────────────────────────────────────────
function guardarParcial() {
    if (!evaluacionId || !Object.keys(respuestas).length) {
        mostrarToast('No hay respuestas para guardar', 'error'); return;
    }
    enviarRespuestas(false);
}

function finalizarEvaluacion() {
    if (!evaluacionId) { mostrarToast('Esperá que se cree la evaluación', 'error'); return; }
    var total = formulario ? formulario.resumen.total_preguntas : 0;
    var respondidas = Object.keys(respuestas).length;
    if (respondidas < total * 0.5) {
        if (!confirm('Solo respondiste ' + respondidas + ' de ' + total + ' preguntas (' + Math.round(respondidas/total*100) + '%). ¿Continuar de todas formas?')) return;
    }
    enviarRespuestas(true);
}

function enviarRespuestas(ejecutarGap) {
    var payload = Object.keys(respuestas).map(function(id) {
        return { formulario_pregunta_id: parseInt(id), respuesta: respuestas[id] };
    });

    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/respuestas',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({ _token: '{{ csrf_token() }}', respuestas: payload }),
        success: function(r) {
            if (!r.ok) return;
            if (ejecutarGap) ejecutarAnalisis();
            else mostrarToast('Respuestas guardadas', 'success');
        },
        error: function(xhr) {
            mostrarToast('Error al guardar: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.status), 'error');
        }
    });
}

// ── Gap Analysis ──────────────────────────────────────────────────────────────
function ejecutarAnalisis() {
    $('#botonesAccion').html('<div class="card-body text-center py-4"><div class="spinner-border text-danger mb-2"></div><p class="text-muted">Analizando brechas con la cartera de servicios...</p></div>');
    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/ejecutar-gap',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(r) { if (r.ok) mostrarResultado(r.data); },
        error: function() { mostrarToast('Error al ejecutar el análisis', 'error'); }
    });
}

function mostrarResultado(data) {
    // Limpiar localStorage — evaluación completada
    localStorage.removeItem('riiss_eval_' + EST_ID);
    var res    = data.cartera.resumen;
    var clasif = data.clasificacion_final;
    var pct    = data.cartera.porcentaje;
    var iconos = { CUMPLE: '✅', CUMPLE_PARCIALMENTE: '⚠️', NO_CUMPLE: '❌' };
    var labels = { CUMPLE: 'CUMPLE', CUMPLE_PARCIALMENTE: 'CUMPLE PARCIALMENTE', NO_CUMPLE: 'NO CUMPLE' };

    const ahora = new Date();
    const fechaStr = ahora.toLocaleDateString('es-PY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    const horaStr  = ahora.toLocaleTimeString('es-PY', { hour: '2-digit', minute: '2-digit' });

    var acciones = '';
    if (data.cartera.acciones_criticas && data.cartera.acciones_criticas.length) {
        acciones = '<div class="mt-4 text-left"><h6 class="font-weight-bold"><i class="fa fa-exclamation-triangle text-danger mr-2"></i>Acciones críticas</h6><ul class="list-unstyled">'
            + data.cartera.acciones_criticas.slice(0,5).map(function(a) {
                return '<li class="mb-2 p-2 bg-white rounded" style="border-left:3px solid #ef4444"><strong>' + a.servicio + '</strong><small class="d-block text-muted">' + (a.accion_recomendada || '') + '</small></li>';
            }).join('') + '</ul></div>';
    }

    var html = '<div class="resultado-card resultado-' + clasif + '">'
        + '<div class="mb-2"><span class="badge badge-dark">Finalizado: ' + fechaStr + ' a las ' + horaStr + '</span></div>'
        + '<div style="font-size:3rem">' + (iconos[clasif] || '❓') + '</div>'
        + '<h3 class="font-weight-bold mt-2">' + (labels[clasif] || clasif) + '</h3>'
        + '<div style="font-size:2.5rem;font-weight:800">' + pct + '%</div>'
        + '<p class="text-muted">de cumplimiento con la cartera de servicios</p>'
        + '<div class="row mt-3 text-center">'
        + '<div class="col-3"><div class="h4 font-weight-bold text-success">' + res.cumple + '</div><small>Cumplen</small></div>'
        + '<div class="col-3"><div class="h4 font-weight-bold text-danger">' + res.no_cumple + '</div><small>No cumplen</small></div>'
        + '<div class="col-3"><div class="h4 font-weight-bold text-warning">' + res.no_verificable + '</div><small>No verificables</small></div>'
        + '<div class="col-3"><div class="h4 font-weight-bold text-muted">' + res.pendiente + '</div><small>Pendientes</small></div>'
        + '</div>' + acciones
        + '<div class="mt-4">'
        + '<a href="/riiss/evaluaciones/' + evaluacionId + '" class="btn btn-dark mr-2"><i class="fa fa-chart-bar mr-1"></i>Ver detalle</a>'
        + '<a href="{{ route("riiss.establecimientos.index") }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left mr-1"></i>Volver</a>'
        + '</div></div>';

    $('#panelResultado').html(html).show();
    $('#botonesAccion').hide();
    $('html, body').animate({ scrollTop: $('#panelResultado').offset().top - 80 }, 600);
}

// ── Buscador de preguntas con Select2 ────────────────────────────────────
function initBuscadorPreguntas() {
    if (!formulario) return;

    var datos = [];
    formulario.secciones.forEach(function(s) {
        s.preguntas.forEach(function(p) {
            datos.push({ id: p.id, text: p.pregunta, seccion: s.seccion });
        });
    });

    $('#buscadorPreguntas').select2({
        placeholder: '🔍 Buscar pregunta...',
        allowClear: true,
        width: '100%',
        data: datos,
        dropdownParent: $('#buscadorPreguntas').parent(),
        templateResult: function(item) {
            if (!item.id) return item.text;
            return $('<div><div style="font-size:.82rem;line-height:1.3">' + item.text + '</div><small style="color:#9ca3af">' + item.seccion + '</small></div>');
        },
        language: {
            noResults:  function() { return 'Sin resultados'; },
            searching:  function() { return 'Buscando...'; },
        },
    });

    $('#buscadorPreguntas').on('select2:select', function(e) {
        var pid = e.params.data.id;
        $('#cardDatosEstablecimiento').slideUp(150);
        setTimeout(function() {
            var el = document.getElementById('preg-' + pid);
            if (el) {
                // Compatible con Windows Chrome — fallback a scrollTop si smooth no funciona
                try {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } catch(err) {
                    el.scrollIntoView(true);
                }
                $(el).css('background', '#fef9c3');
                setTimeout(function() { $(el).css('background', ''); }, 1500);
            }
        }, 200);
    });

    $('#buscadorPreguntas').on('select2:clear', function() {
        $('#cardDatosEstablecimiento').slideDown(150);
        $(this).val(null).trigger('change');
    });
}

// ── Toast ─────────────────────────────────────────────────────────────────────
function mostrarToast(msg, tipo) {
    var color = tipo === 'success' ? '#22c55e' : '#ef4444';
    var $t = $('<div style="position:fixed;bottom:24px;right:24px;background:' + color + ';color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.2)">' + msg + '</div>');
    $('body').append($t);
    setTimeout(function() { $t.fadeOut(400, function() { $t.remove(); }); }, 3000);
}
</script>
@endpush