@extends('layouts.master')
@section('title', 'Monitoreo RIISS')

@push('styles')
<style>
.pct-bar  { height:8px; border-radius:4px; background:#e5e7eb; overflow:hidden; }
.pct-fill { height:100%; border-radius:4px; transition:width .6s ease; }
.estado-badge { font-size:.7rem; padding:3px 9px; border-radius:20px; font-weight:600; }
.estado-borrador    { background:#e2e8f0; color:#475569; }
.estado-en_progreso { background:#fef3c7; color:#92400e; }
.estado-completada  { background:#d1fae5; color:#065f46; }
.eval-card { border-radius:12px; border:1px solid #e5e7eb; transition:box-shadow .2s; }
.eval-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.1); }
.pulse { animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
#ultimaActualizacion { font-size:.75rem; }
</style>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header card-header-info py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0">
                    <i class="fa fa-chart-line mr-2"></i>Monitoreo de Evaluaciones RIISS
                </h4>
                <p class="card-category mb-0">Seguimiento en tiempo real del progreso de evaluaciones</p>
            </div>
            <div class="text-right text-white">
                <div id="ultimaActualizacion" class="text-white-50">Actualizando...</div>
                <small class="text-white-50">
                    <i class="fa fa-sync-alt pulse mr-1"></i>Actualiza cada 30s
                </small>
            </div>
        </div>
    </div>
    <nav class="bg-light px-3 py-2">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Monitoreo</li>
        </ol>
    </nav>
</div>

{{-- KPIs --}}
<div class="row mb-4" id="kpiRow">
    <div class="col-6 col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-left-danger">
            <div class="card-body py-3">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total evaluaciones</div>
                <div class="h3 font-weight-bold mb-0" id="kpi-total">—</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-left-warning">
            <div class="card-body py-3">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En progreso</div>
                <div class="h3 font-weight-bold mb-0" id="kpi-progreso">—</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-left-success">
            <div class="card-body py-3">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completadas</div>
                <div class="h3 font-weight-bold mb-0" id="kpi-completadas">—</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-left-info">
            <div class="card-body py-3">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Promedio completado</div>
                <div class="h3 font-weight-bold mb-0" id="kpi-promedio">—</div>
            </div>
        </div>
    </div>
</div>

{{-- Filtro rápido --}}
<div class="d-flex align-items-center mb-3 flex-wrap" style="gap:8px">
    <span class="small font-weight-bold text-muted">Filtrar:</span>
    <button class="btn btn-sm btn-danger filtro-estado active" onclick="filtrar(this,'')">Todos</button>
    <button class="btn btn-sm btn-outline-secondary filtro-estado" onclick="filtrar(this,'en_progreso')">En progreso</button>
    <button class="btn btn-sm btn-outline-secondary filtro-estado" onclick="filtrar(this,'completada')">Completadas</button>
    <button class="btn btn-sm btn-outline-secondary filtro-estado" onclick="filtrar(this,'borrador')">Borradores</button>
    <div class="ml-auto">
        <a href="{{ route('riiss.evaluaciones.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-list mr-1"></i>Ver todas
        </a>
    </div>
</div>

{{-- Cards de evaluaciones --}}
<div id="evalCards">
    <div class="text-center py-5">
        <div class="spinner-border text-danger mb-2"></div>
        <p class="text-muted">Cargando evaluaciones...</p>
    </div>
</div>

@endsection

@section('scripts')
<script>
var DATOS_URL   = '{{ route("riiss.dashboard.datos") }}';
var filtroActivo = '';
var todasEvals   = [];
var intervalo;

$(document).ready(function() {
    cargarDatos();
    intervalo = setInterval(cargarDatos, 30000);
});

function filtrar(btn, estado) {
    filtroActivo = estado;
    $('.filtro-estado').removeClass('btn-danger').addClass('btn-outline-secondary');
    $(btn).removeClass('btn-outline-secondary').addClass('btn-danger');
    renderCards(todasEvals);
}

function cargarDatos() {
    $.get(DATOS_URL, function(r) {
        if (!r.ok) return;
        todasEvals = r.evaluaciones;

        // KPIs
        $('#kpi-total').text(r.resumen.total);
        $('#kpi-progreso').text(r.resumen.en_progreso);
        $('#kpi-completadas').text(r.resumen.completadas);
        $('#kpi-promedio').text(r.resumen.promedio_pct + '%');
        $('#ultimaActualizacion').text('Última actualización: ' + r.timestamp);

        renderCards(todasEvals);
    });
}

function renderCards(evals) {
    var filtradas = filtroActivo
        ? evals.filter(function(e) { return e.estado === filtroActivo; })
        : evals;

    if (!filtradas.length) {
        $('#evalCards').html('<div class="text-center py-5 text-muted"><i class="fa fa-clipboard fa-2x mb-2 d-block" style="opacity:.3"></i>Sin evaluaciones</div>');
        return;
    }

    var html = '<div class="row">';
    filtradas.forEach(function(e) {
        var pctColor = e.progreso >= 90 ? '#22c55e' : e.progreso >= 50 ? '#f97316' : '#ef4444';
        var estadoBadge = '<span class="estado-badge estado-' + e.estado + '">' + e.estado.replace('_',' ') + '</span>';

        var clasifHtml = '';
        if (e.clasificacion) {
            var clasifColor = { CUMPLE: 'success', CUMPLE_PARCIALMENTE: 'warning', NO_CUMPLE: 'danger' }[e.clasificacion] || 'secondary';
            clasifHtml = '<span class="badge badge-' + clasifColor + ' ml-2" style="font-size:.65rem">' + e.clasificacion.replace(/_/g,' ') + '</span>';
        }

        html += '<div class="col-md-6 col-lg-4 mb-3">'
            + '<div class="eval-card p-3 h-100">'

            // Header
            + '<div class="d-flex align-items-start justify-content-between mb-2">'
            + '<div style="flex:1;min-width:0">'
            + '<div class="font-weight-bold text-truncate" title="' + e.establecimiento + '">' + e.establecimiento + '</div>'
            + '<small class="text-muted">' + e.tipologia + '</small>'
            + '</div>'
            + '<div class="ml-2 text-right">'
            + estadoBadge + clasifHtml
            + '</div>'
            + '</div>'

            // Complejidad
            + '<div class="mb-2">'
            + '<span class="badge" style="background:' + e.complejidad_color + ';color:#fff;font-size:.65rem;border-radius:20px;padding:2px 8px">' + e.complejidad + '</span>'
            + '</div>'

            // Progreso
            + '<div class="mb-2">'
            + '<div class="d-flex justify-content-between small mb-1">'
            + '<span class="text-muted">Progreso del formulario</span>'
            + '<strong style="color:' + pctColor + '">' + e.progreso + '%</strong>'
            + '</div>'
            + '<div class="pct-bar"><div class="pct-fill" style="width:' + e.progreso + '%;background:' + pctColor + '"></div></div>'
            + '<small class="text-muted">' + e.respondidas + ' de ' + e.total_preguntas + ' preguntas respondidas</small>'
            + '</div>'

            // Footer
            + '<div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">'
            + '<div>'
            + '<small class="text-muted"><i class="fa fa-user mr-1"></i>' + e.evaluador + '</small><br>'
            + '<small class="text-muted"><i class="fa fa-calendar mr-1"></i>' + (e.fecha || '—') + '</small>'
            + '</div>'
            + '<div class="text-right">'
            + '<small class="text-muted d-block">' + (e.updated_at || '') + '</small>'
            + '<a href="/riiss/evaluaciones/' + e.id + '" class="btn btn-sm btn-outline-danger mt-1">'
            + '<i class="fa fa-eye mr-1"></i>Ver detalle'
            + '</a>'
            + '</div>'
            + '</div>'

            + '</div></div>';
    });
    html += '</div>';
    $('#evalCards').html(html);
}
</script>
@endsection
