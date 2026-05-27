@extends('layouts.master')
@section('title', 'Evaluación — ' . $est->nombre_oficial)

@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
<style>
.select2-container--default .select2-selection--multiple { border:1px solid #ced4da; border-radius:6px; min-height:42px; padding:4px 8px; }
.select2-container--default .select2-selection--multiple .select2-selection__choice { background:#e91e63; border:none; color:#fff; border-radius:20px; padding:2px 10px; font-size:.78rem; }
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove { color:rgba(255,255,255,.8); margin-right:4px; }
.select2-container--default .select2-results__option--highlighted { background:#e91e63 !important; }
.select2-dropdown { border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 4px 16px rgba(0,0,0,.1); }
.select2-search--dropdown .select2-search__field { border-radius:6px; border:1px solid #e5e7eb; padding:6px 10px; }
.select2-results__option { padding:8px 12px; }
.select2-container--default .select2-selection--single { border:1px solid #ced4da; border-radius:6px; height:42px; padding:6px 8px; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height:42px; }

#evalWrapper { display:flex; gap:16px; align-items:flex-start; }
#sidebarSecciones { width:260px; flex-shrink:0; position:sticky; top:80px; max-height:calc(100vh - 100px); overflow-y:auto; }
#contenidoFormulario { flex:1; min-width:0; }

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
                <div class="small font-weight-bold text-uppercase text-muted px-2 mb-2">Secciones</div>
                <div id="listaSecciones">
                    <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <div id="contenidoFormulario">

        {{-- Datos de la visita --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="font-weight-bold mb-3">
                    <i class="fa fa-user-edit mr-2 text-danger"></i>Datos de la visita
                    <small class="text-muted font-weight-normal ml-2" id="evalEstado"></small>
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Evaluadores <span class="text-danger">*</span></label>
                        <select id="evalEvaluadores" class="form-control" multiple style="width:100%"></select>
                        <small class="text-muted">Uno o más evaluadores</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Teléfono</label>
                        <input type="text" id="evalTelefono" class="form-control" placeholder="0981...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Fecha <span class="text-danger">*</span></label>
                        <input type="date" id="evalFecha" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <hr class="my-3">
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

$(document).ready(function() {

    // ── Select2 evaluadores ───────────────────────────────────────────────
    $('#evalEvaluadores').select2({
        placeholder: 'Buscar evaluador...', allowClear: true, multiple: true,
        minimumInputLength: 0, width: '100%', dropdownParent: $('body'),
        language: { noResults: function() { return 'Sin resultados'; }, searching: function() { return 'Buscando...'; } },
        ajax: {
            url: '{{ route("riiss.evaluaciones.usuarios") }}', dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; }, cache: true,
        },
        templateResult: function(u) {
            if (u.loading) return u.text;
            return $('<div><strong>' + u.text + '</strong><br><small class="text-muted">' + (u.email || '') + '</small></div>');
        },
        templateSelection: function(u) { return u.text || u.id; },
    });

    // Preseleccionar usuario actual
    @if(auth()->check())
    $('#evalEvaluadores').append(new Option('{{ addslashes(auth()->user()->name) }}', {{ auth()->id() }}, true, true)).trigger('change');
    @endif

    // ── Crear evaluación automáticamente y cargar formulario ──────────────
    crearEvaluacionYCargar();

    // Guardar datos de visita al cambiar (debounce)
    var saveTimer;
    $('#evalFecha, #evalTelefono').on('change input', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });
    $('#evalEvaluadores').on('change', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });
});

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
        }),
        success: function(r) {
            if (!r.ok) return;
            evaluacionId = r.data.id;
            $('#evalEstado').html('<span class="badge badge-success">Evaluación #' + evaluacionId + ' creada</span>');
            cargarFormulario();
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
            metadata:           { ubicacion: ubicacion },
        }),
        success: function() { mostrarToast('Datos guardados', 'success'); },
    });
}
</script>
@endsection

@push('scripts')
<script>
// ── Cargar formulario ─────────────────────────────────────────────────────────
function cargarFormulario() {
    $('#listaSecciones').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>');
    $('#seccionesFormulario').html('<div class="text-center py-5"><div class="spinner-border text-danger"></div><p class="text-muted mt-2">Cargando formulario...</p></div>');

    $.get('/riiss/evaluaciones/formulario/' + EST_ID, function(r) {
        if (!r.ok) return;
        formulario = r.data;
        renderSidebar(formulario.secciones);
        renderSecciones(formulario.secciones);
        $('#botonesAccion').show();
        actualizarProgreso();
    });
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
function setResp(id, valor, btn, tipo) {
    respuestas[id] = valor;
    $(btn).closest('.resp-group').find('.resp-btn').removeClass('selected-si selected-no selected-na');
    $(btn).addClass('selected-' + tipo);
    actualizarProgreso();
    actualizarSidebarSeccion(id);
}

function setRespTexto(id, valor) {
    if (valor && valor.toString().trim()) {
        respuestas[id] = valor.toString().trim();
        actualizarProgreso();
        actualizarSidebarSeccion(id);
    }
}

function toggleChecklist(id, opcion, btn) {
    if (!checklistState[id]) checklistState[id] = [];
    var idx = checklistState[id].indexOf(opcion);
    if (idx >= 0) { checklistState[id].splice(idx, 1); $(btn).removeClass('selected-si'); }
    else          { checklistState[id].push(opcion);   $(btn).addClass('selected-si'); }
    respuestas[id] = checklistState[id].join(', ');
    actualizarProgreso();
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
    var res    = data.resumen;
    var clasif = res.clasificacion;
    var pct    = res.porcentaje_cumplimiento;
    var iconos = { CUMPLE: '✅', CUMPLE_PARCIALMENTE: '⚠️', NO_CUMPLE: '❌' };
    var labels = { CUMPLE: 'CUMPLE', CUMPLE_PARCIALMENTE: 'CUMPLE PARCIALMENTE', NO_CUMPLE: 'NO CUMPLE' };

    var acciones = '';
    if (data.acciones_criticas && data.acciones_criticas.length) {
        acciones = '<div class="mt-4 text-left"><h6 class="font-weight-bold"><i class="fa fa-exclamation-triangle text-danger mr-2"></i>Acciones críticas</h6><ul class="list-unstyled">'
            + data.acciones_criticas.slice(0,5).map(function(a) {
                return '<li class="mb-2 p-2 bg-white rounded" style="border-left:3px solid #ef4444"><strong>' + a.servicio + '</strong><small class="d-block text-muted">' + (a.accion_recomendada || '') + '</small></li>';
            }).join('') + '</ul></div>';
    }

    var html = '<div class="resultado-card resultado-' + clasif + '">'
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

// ── Toast ─────────────────────────────────────────────────────────────────────
function mostrarToast(msg, tipo) {
    var color = tipo === 'success' ? '#22c55e' : '#ef4444';
    var $t = $('<div style="position:fixed;bottom:24px;right:24px;background:' + color + ';color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.2)">' + msg + '</div>');
    $('body').append($t);
    setTimeout(function() { $t.fadeOut(400, function() { $t.remove(); }); }, 3000);
}
</script>
@endpush
