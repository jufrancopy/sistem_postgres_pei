@extends('layouts.master')
@section('title', 'Formularios RIISS por Nivel')

@push('styles')
<style>
.seccion-card { border:1px solid #e3f2fd; border-radius:10px; margin-bottom:12px; overflow:hidden; background:#ffffff; box-shadow:0 10px 30px rgba(14, 63, 99, 0.04); }
.seccion-header { background:#f4fbff; padding:12px 16px; cursor:pointer; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #dbeaf4; }
.seccion-header:hover { background:#e6f7ff; }
.seccion-body { display:none; padding:0; }
.seccion-body.open { display:block; }
.badge-requerida { background:#d1f5ff; color:#0c4a6e; font-size:.7rem; padding:2px 8px; border-radius:10px; font-weight:600; }
.badge-opcional { background:#eff6ff; color:#334155; font-size:.7rem; padding:2px 8px; border-radius:10px; font-weight:600; }
.badge-tipo { background:#dbeafe; color:#1e40af; font-size:.7rem; padding:2px 8px; border-radius:10px; }
.badge-mapeada { background:#d1fae5; color:#065f46; font-size:.68rem; padding:2px 6px; border-radius:8px; }
.badge-sin-mapeo { background:#fef9c3; color:#854d0e; font-size:.68rem; padding:2px 6px; border-radius:8px; }
.tipologia-btn { border:2px solid #dbeaf4; border-radius:8px; padding:8px 14px; cursor:pointer; background:#ffffff; font-size:.82rem; transition:all .15s; }
.tipologia-btn:hover { border-color:#00acc1; color:#0f172a; background:#f0fbff; }
.tipologia-btn.active { border-color:#00acc1; background:linear-gradient(135deg, #e0f7ff, #d1f2ff); color:#0369a1; font-weight:700; }
.pregunta-row { padding:10px 16px; border-bottom:1px solid #eaf4fb; display:flex; align-items:flex-start; gap:12px; }
.pregunta-row:last-child { border-bottom:none; }
.pregunta-row:hover { background:#f8fbff; }
.mapeo-edit { display:none; padding:8px 16px 12px 44px; background:#f8fafc; border-top:1px solid #e5e7eb; }
.riiss-action-btn { background: linear-gradient(135deg, #00acc1, #26c6da); color: #ffffff !important; border-radius: 10px; border: none; box-shadow: 0 8px 18px rgba(6, 78, 126, 0.12); transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease; }
.riiss-action-btn:hover, .riiss-action-btn:focus { transform: translateY(-1px); box-shadow: 0 12px 22px rgba(6, 78, 126, 0.18); opacity: .95; }
.btn-circle { border-radius: 50% !important; padding: 0 !important; width: 38px !important; height: 38px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info" style="background: linear-gradient(135deg, #00acc1, #26c6da); border-radius: 12px; box-shadow: 0 12px 26px rgba(0, 172, 193, 0.16);">
        <h4 class="card-title text-white"><i class="fa fa-wpforms mr-2"></i>Formularios RIISS por Nivel y Tipología</h4>
        <p class="card-category text-white-75">Gestión de secciones y mapeo de preguntas a la cartera de servicios</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Formularios por Nivel</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- Selector de tipología --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="font-weight-bold mb-0"><i class="fa fa-filter mr-1"></i>Filtrar por tipología</h6>
                <div id="btnEditarTipologia" style="display:none">
                    <a id="linkEditarTipologia" href="#" class="btn btn-sm riiss-action-btn">
                        <i class="fa fa-edit mr-1"></i>Editar reglas de esta tipología
                    </a>
                </div>
            </div>
            <div class="d-flex flex-wrap" style="gap:8px" id="tipologiaBtns">
                <button class="tipologia-btn active" onclick="seleccionarTipologia('')">
                    <i class="fa fa-th mr-1"></i>Todas
                </button>
            </div>
        </div>

        {{-- Resumen --}}
        <div class="row mb-3" id="resumenCards">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-2">
                        <h3 class="mb-0 font-weight-bold text-danger" id="totalSecciones">—</h3>
                        <small class="text-muted">Secciones</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-2">
                        <h3 class="mb-0 font-weight-bold text-primary" id="totalPreguntas">—</h3>
                        <small class="text-muted">Preguntas activas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-2">
                        <h3 class="mb-0 font-weight-bold text-success" id="totalMapeadas">—</h3>
                        <small class="text-muted">Con mapeo</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-2">
                        <h3 class="mb-0 font-weight-bold text-warning" id="totalSinMapeo">—</h3>
                        <small class="text-muted">Sin mapeo</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Listado de secciones --}}
        <div id="listaSecciones">
            <div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>
        </div>

    </div>
</div>

{{-- Modal editar mapeo --}}
<div class="modal fade" id="modalMapeo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-header-info" style="background: linear-gradient(135deg, #00acc1, #26c6da);">
                <h5 class="modal-title text-white"><i class="fa fa-link mr-2"></i>Mapear pregunta a cartera</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small" id="mapeoTextoPregunta"></p>
                <div class="form-group">
                    <label class="small font-weight-bold">Grupo de servicio (cartera)</label>
                    <input type="text" id="mapeoGrupo" class="form-control" placeholder="Ej: Promoción y Prevención">
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold">Especialidad relacionada</label>
                    <input type="text" id="mapeoEspecialidad" class="form-control" placeholder="Ej: MEDICINA PREVENTIVA">
                </div>
                <div id="mapeoMsg"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" onclick="guardarMapeo()"><i class="fa fa-save mr-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var DATOS_URL    = '{{ route("riiss.formularios.datos") }}';
var TIPOLOGIAS_URL = '{{ route("riiss.formularios.tipologias") }}';
var MAPEO_URL    = '/riiss/formularios/preguntas/';
var tipologiaActual = '';
var preguntaEditandoId = null;

$(document).ready(function() {
    cargarTipologias();
    cargarSecciones();
});

function cargarTipologias() {
    $.get(TIPOLOGIAS_URL, function(r) {
        if (!r.ok) return;
        var html = '<button class="tipologia-btn active" onclick="seleccionarTipologia(\'\')"><i class="fa fa-th mr-1"></i>Todas</button>';
        r.data.forEach(function(t) {
            html += '<button class="tipologia-btn" onclick="seleccionarTipologia(\'' + t + '\')">' + t + '</button>';
        });
        $('#tipologiaBtns').html(html);
    });
}

function seleccionarTipologia(tip) {
    tipologiaActual = tip;
    $('.tipologia-btn').removeClass('active');
    event.target.classList.add('active');
    if (tip) {
        $('#linkEditarTipologia').attr('href', '/riiss/formularios/tipologias/' + encodeURIComponent(tip));
        $('#btnEditarTipologia').show();
    } else {
        $('#btnEditarTipologia').hide();
    }
    cargarSecciones();
}

function cargarSecciones() {
    $('#listaSecciones').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $.get(DATOS_URL, { tipologia: tipologiaActual }, function(r) {
        if (!r.ok) return;
        renderSecciones(r.data);
    });
}

function renderSecciones(secciones) {
    var totalPreguntas = 0, totalMapeadas = 0;

    var html = '';
    secciones.forEach(function(s) {
        totalPreguntas += s.total_preguntas;
        var mapeadasEnSec = s.preguntas.filter(function(p) { return p.servicio_cartera_grupo; }).length;
        totalMapeadas += mapeadasEnSec;

        var badgeReq = s.requerida
            ? '<span class="badge-requerida ml-2">Requerida</span>'
            : '<span class="badge-opcional ml-2">Opcional</span>';
        var condHtml = s.condicion ? '<span class="badge-tipo ml-2"><i class="fa fa-code-branch mr-1"></i>' + s.condicion + '</span>' : '';

        html += '<div class="seccion-card">';
        html += '<div class="seccion-header" onclick="toggleSeccion(this)">';
        html +=   '<div>';
        html +=     '<i class="fa fa-chevron-right mr-2 toggle-icon" style="font-size:.75rem;transition:transform .2s"></i>';
        html +=     '<strong>' + s.nombre_completo + '</strong>';
        html +=     badgeReq + condHtml;
        html +=   '</div>';
        html +=   '<div class="d-flex align-items-center" style="gap:8px">';
        html +=     '<small class="text-muted">' + s.total_preguntas + ' pregunta' + (s.total_preguntas !== 1 ? 's' : '') + '</small>';
        html +=     '<small class="text-success">' + mapeadasEnSec + ' mapeadas</small>';
        html +=     '<a href="/riiss/formularios/secciones/' + s.id + '" class="btn btn-sm btn-circle riiss-action-btn" style="font-size:.75rem; width:38px; height:38px;" onclick="event.stopPropagation()"><i class="fa fa-edit"></i></a>';
        html +=   '</div>';
        html += '</div>';
        html += '<div class="seccion-body">';

        if (s.preguntas.length === 0) {
            html += '<div class="p-3 text-muted text-center small">Sin preguntas activas</div>';
        } else {
            s.preguntas.forEach(function(p, idx) {
                var tieneMapeoClas = p.servicio_cartera_grupo
                    ? '<span class="badge-mapeada ml-2"><i class="fa fa-link mr-1"></i>' + p.servicio_cartera_grupo + '</span>'
                    : '<span class="badge-sin-mapeo ml-2"><i class="fa fa-unlink mr-1"></i>Sin mapeo</span>';
                var espHtml = p.especialidad_relacionada
                    ? '<small class="text-info ml-1">· ' + p.especialidad_relacionada + '</small>'
                    : '';

                html += '<div class="pregunta-row">';
                html +=   '<span class="text-muted small" style="min-width:24px">' + (idx + 1) + '.</span>';
                html +=   '<div style="flex:1">';
                html +=     '<div>' + p.pregunta + tieneMapeoClas + espHtml + '</div>';
                html +=     '<small class="text-muted"><span class="badge-tipo">' + p.tipo_respuesta + '</span></small>';
                html +=   '</div>';
                html +=   '<button class="btn btn-sm btn-outline-secondary" onclick="abrirMapeo(' + p.id + ', \'' + (p.servicio_cartera_grupo||'') + '\', \'' + (p.especialidad_relacionada||'') + '\', this)" title="Editar mapeo">';
                html +=     '<i class="fa fa-edit"></i>';
                html +=   '</button>';
                html += '</div>';
            });
        }

        html += '</div></div>';
    });

    $('#listaSecciones').html(html || '<div class="alert alert-info">No hay secciones para esta tipología.</div>');
    $('#totalSecciones').text(secciones.length);
    $('#totalPreguntas').text(totalPreguntas);
    $('#totalMapeadas').text(totalMapeadas);
    $('#totalSinMapeo').text(totalPreguntas - totalMapeadas);
}

function toggleSeccion(header) {
    var body = $(header).next('.seccion-body');
    var icon = $(header).find('.toggle-icon');
    body.toggleClass('open');
    icon.css('transform', body.hasClass('open') ? 'rotate(90deg)' : '');
}

function abrirMapeo(id, grupo, esp, btn) {
    preguntaEditandoId = id;
    var preguntaTexto = $(btn).closest('.pregunta-row').find('div > div:first').text();
    $('#mapeoTextoPregunta').text(preguntaTexto.substring(0, 120) + '...');
    $('#mapeoGrupo').val(grupo);
    $('#mapeoEspecialidad').val(esp);
    $('#mapeoMsg').html('');
    $('#modalMapeo').modal('show');
}

function guardarMapeo() {
    if (!preguntaEditandoId) return;
    $.ajax({
        url: MAPEO_URL + preguntaEditandoId + '/mapeo',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: '{{ csrf_token() }}',
            _method: 'PATCH',
            servicio_cartera_grupo:   $('#mapeoGrupo').val() || null,
            especialidad_relacionada: $('#mapeoEspecialidad').val() || null,
        }),
        success: function(r) {
            if (r.ok) {
                $('#modalMapeo').modal('hide');
                cargarSecciones();
            } else {
                $('#mapeoMsg').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
            }
        },
        error: function() {
            $('#mapeoMsg').html('<div class="alert alert-danger py-2">Error al guardar.</div>');
        }
    });
}
</script>
@endsection
