@extends('layouts.master')
@section('title', 'Asignaciones RIISS')

@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
<style>
.estado-badge { font-size:.7rem; padding:3px 9px; border-radius:20px; font-weight:600; }
.estado-pendiente   { background:#fef3c7; color:#92400e; }
.estado-en_progreso { background:#dbeafe; color:#1e40af; }
.estado-completada  { background:#d1fae5; color:#065f46; }
.estado-vencida     { background:#fee2e2; color:#991b1b; }
.estado-cancelada   { background:#f1f5f9; color:#64748b; }
.vencida-row { background:#fff5f5 !important; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title">
            <i class="fa fa-user-check mr-2"></i>Asignaciones de Evaluación
        </h4>
        <p class="card-category">Gestión de evaluadores por establecimiento</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Asignaciones</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- Botón nueva asignación --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 font-weight-bold">
                <i class="fa fa-list mr-2 text-danger"></i>Todas las asignaciones
            </h5>
            <button class="btn btn-danger" data-toggle="modal" data-target="#modalNuevaAsignacion">
                <i class="fa fa-plus mr-1"></i>Nueva Asignación
            </button>
        </div>

        {{-- Filtros --}}
        <div class="row mb-3">
            <div class="col-md-4 mb-2">
                <input type="text" id="fBuscar" class="form-control" placeholder="Buscar establecimiento...">
            </div>
            <div class="col-md-3 mb-2">
                <select id="fEstado" class="form-control">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_progreso">En progreso</option>
                    <option value="completada">Completada</option>
                    <option value="vencida">Vencida</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select id="fEvaluador" class="form-control" style="width:100%">
                    <option value="">Todos los evaluadores</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button class="btn btn-danger btn-block" onclick="cargarAsignaciones()">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Establecimiento</th>
                        <th>Evaluador</th>
                        <th>Asignado por</th>
                        <th>Fecha límite</th>
                        <th>Estado</th>
                        <th>Progreso</th>
                        <th>Notificado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyAsignaciones">
                    <tr><td colspan="8" class="text-center py-4 text-muted">Cargando...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted" id="infoAsignaciones"></small>
            <div id="paginaBtns"></div>
        </div>

    </div>
</div>

{{-- Modal Gap Analysis --}}
<div class="modal fade" id="modalGap" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e40af,#3b82f6)">
                <h5 class="modal-title text-white">
                    <i class="fa fa-chart-bar mr-2"></i>Comparación: Evaluación vs Cartera de Servicios
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="modalGapBody">
                <div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal nueva asignación --}}

<div class="modal fade" id="modalNuevaAsignacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-danger" style="background:linear-gradient(135deg,#c62828,#e91e63)">
                <h5 class="modal-title text-white">
                    <i class="fa fa-user-check mr-2"></i>Nueva Asignación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Establecimiento <span class="text-danger">*</span></label>
                        <select id="selEstablecimiento" class="form-control" style="width:100%"></select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Evaluador <span class="text-danger">*</span></label>
                        <select id="selEvaluador" class="form-control" style="width:100%"></select>
                        <small class="text-muted">El evaluador recibirá un email de notificación</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Fecha límite</label>
                        <input type="date" id="selFechaLimite" class="form-control"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Instrucciones (opcional)</label>
                        <textarea id="selInstrucciones" class="form-control" rows="3"
                                  placeholder="Indicaciones especiales para el evaluador..."></textarea>
                    </div>
                </div>
                <div id="msgAsignacion"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" onclick="crearAsignacion()">
                    <i class="fa fa-paper-plane mr-1"></i>Asignar y notificar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var ASIG_URL  = '{{ route("riiss.asignaciones.datos") }}';
var STORE_URL = '{{ route("riiss.asignaciones.store") }}';
var USERS_URL = '{{ route("riiss.evaluaciones.usuarios") }}';
var BUSCAR_URL= '{{ route("riiss.establecimientos.buscar") }}';

$(document).ready(function() {
    // Select2 evaluador filtro
    $('#fEvaluador').select2({
        placeholder: 'Todos los evaluadores', allowClear: true, width: '100%',
        ajax: { url: USERS_URL, dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; } }
    });

    // Select2 establecimiento en modal
    $('#selEstablecimiento').select2({
        placeholder: 'Buscar establecimiento...', allowClear: true, width: '100%',
        dropdownParent: $('#modalNuevaAsignacion'),
        ajax: { url: BUSCAR_URL, dataType: 'json', delay: 250,
            data: function(p) { return { buscar: p.term || '', per_page: 20 }; },
            processResults: function(d) {
                return { results: d.data.data.map(function(e) {
                    return { id: e.id, text: e.nombre + ' (' + e.tipologia + ')' };
                })};
            }
        }
    });

    // Select2 evaluador en modal
    $('#selEvaluador').select2({
        placeholder: 'Buscar evaluador...', allowClear: true, width: '100%',
        dropdownParent: $('#modalNuevaAsignacion'),
        ajax: { url: USERS_URL, dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; },
        },
        templateResult: function(u) {
            if (u.loading) return u.text;
            return $('<div>' + u.text + '<br><small class="text-muted">' + (u.email||'') + '</small></div>');
        }
    });

    // Limpiar modal al abrir
    $('#modalNuevaAsignacion').on('show.bs.modal', function() {
        $('#selEstablecimiento').val(null).trigger('change');
        $('#selEvaluador').val(null).trigger('change');
        $('#selFechaLimite').val('');
        $('#selInstrucciones').val('');
        $('#msgAsignacion').html('');
    });

    // Filtros
    var timer;
    $('#fBuscar').on('input', function() {
        clearTimeout(timer);
        timer = setTimeout(cargarAsignaciones, 350);
    });
    $('#fEstado, #fEvaluador').on('change', cargarAsignaciones);

    cargarAsignaciones();
});

function cargarAsignaciones(pagina) {
    $.get(ASIG_URL, {
        buscar:    $('#fBuscar').val(),
        estado:    $('#fEstado').val(),
        evaluador: $('#fEvaluador').val(),
        page:      pagina || 1,
    }, function(r) {
        if (!r.ok) return;
        renderTabla(r.data.data);
        $('#infoAsignaciones').text('Total: ' + r.data.total + ' asignaciones');
        renderPaginacion(r.data, pagina || 1);
    });
}

function renderTabla(items) {
    if (!items.length) {
        $('#tbodyAsignaciones').html('<tr><td colspan="8" class="text-center py-4 text-muted">Sin asignaciones</td></tr>');
        return;
    }

    var html = '';
    items.forEach(function(a) {
        var rowClass = a.vencida ? 'vencida-row' : '';
        var pctHtml = a.evaluacion_progreso
            ? '<div style="height:5px;background:#e5e7eb;border-radius:3px;overflow:hidden;width:80px"><div style="height:100%;width:' + a.evaluacion_progreso + '%;background:#22c55e"></div></div><small>' + a.evaluacion_progreso + '%</small>'
            : '<small class="text-muted">—</small>';
        var notifHtml = a.notificado_at
            ? '<small class="text-success"><i class="fa fa-check mr-1"></i>' + a.notificado_at + '</small>'
            : '<small class="text-muted">No enviado</small>';

        html += '<tr class="' + rowClass + '">'
            + '<td><strong>' + a.establecimiento + '</strong><br><small class="text-muted">' + a.tipologia + '</small></td>'
            + '<td><small>' + a.evaluador + '</small><br><small class="text-muted">' + a.evaluador_email + '</small></td>'
            + '<td><small>' + a.asignado_por + '</small></td>'
            + '<td><small class="' + (a.vencida ? 'text-danger font-weight-bold' : '') + '">' + (a.fecha_limite || '—') + (a.vencida ? ' ⚠️' : '') + '</small></td>'
            + '<td><span class="estado-badge estado-' + a.estado + '">' + a.estado.replace('_',' ') + '</span></td>'
            + '<td>' + pctHtml + '</td>'
            + '<td>' + notifHtml + '</td>'
            + '<td class="text-center">'
            + (a.evaluacion_id
                ? '<a href="/riiss/evaluaciones/' + a.evaluacion_id + '" class="circle-btn circle-btn-success btn-sm mr-1" title="Ver evaluación"><i class="fa fa-eye"></i></a>'
                + '<button class="circle-btn circle-btn-primary btn-sm mr-1" onclick="verGap(' + a.evaluacion_id + ')" title="Gap Analysis"><i class="fa fa-chart-bar"></i></button>'
                : '')
            + '<button class="circle-btn circle-btn-info btn-sm mr-1" onclick="renotificar(' + a.id + ')" title="Reenviar email"><i class="fa fa-envelope"></i></button>'
            + (a.estado !== 'cancelada' && a.estado !== 'completada'
                ? '<button class="circle-btn circle-btn-danger btn-sm" onclick="cancelar(' + a.id + ')" title="Cancelar"><i class="fa fa-times"></i></button>'
                : '')
            + '</td>'
            + '</tr>';
    });
    $('#tbodyAsignaciones').html(html);
}

function renderPaginacion(d) {
    var btns = '';
    if (d.current_page > 1) btns += '<button class="btn btn-sm btn-outline-secondary mr-1" onclick="cargarAsignaciones(' + (d.current_page-1) + ')">‹</button>';
    for (var i = Math.max(1, d.current_page-2); i <= Math.min(d.last_page, d.current_page+2); i++) {
        btns += '<button class="btn btn-sm ' + (i===d.current_page?'btn-danger':'btn-outline-secondary') + ' mr-1" onclick="cargarAsignaciones(' + i + ')">' + i + '</button>';
    }
    if (d.current_page < d.last_page) btns += '<button class="btn btn-sm btn-outline-secondary" onclick="cargarAsignaciones(' + (d.current_page+1) + ')">›</button>';
    $('#paginaBtns').html(btns);
}

function crearAsignacion() {
    var estId = $('#selEstablecimiento').val();
    var evalId = $('#selEvaluador').val();
    if (!estId || !evalId) {
        $('#msgAsignacion').html('<div class="alert alert-warning py-2">Seleccioná establecimiento y evaluador</div>');
        return;
    }

    $.ajax({
        url: STORE_URL, method: 'POST', contentType: 'application/json',
        data: JSON.stringify({
            _token:              '{{ csrf_token() }}',
            id_establecimiento:  estId,
            evaluador_id:        evalId,
            fecha_limite:        $('#selFechaLimite').val() || null,
            instrucciones:       $('#selInstrucciones').val() || null,
        }),
        success: function(r) {
            if (r.ok) {
                $('#modalNuevaAsignacion').modal('hide');
                cargarAsignaciones();
                mostrarToast('Asignación creada y evaluador notificado ✅', 'success');
            } else {
                $('#msgAsignacion').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al crear';
            $('#msgAsignacion').html('<div class="alert alert-danger py-2">' + msg + '</div>');
        }
    });
}

function renotificar(id) {
    $.ajax({
        url: '/riiss/asignaciones/' + id + '/renotificar', method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(r) {
            mostrarToast(r.ok ? 'Email reenviado ✅' : r.message, r.ok ? 'success' : 'error');
            if (r.ok) cargarAsignaciones();
        }
    });
}

function cancelar(id) {
    if (!confirm('¿Cancelar esta asignación?')) return;
    $.ajax({
        url: '/riiss/asignaciones/' + id, method: 'POST',
        data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function(r) {
            mostrarToast('Asignación cancelada', 'success');
            cargarAsignaciones();
        }
    });
}

function verGap(evaluacionId) {
    $('#modalGapBody').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#modalGap').modal('show');

    $.get('/riiss/evaluaciones/' + evaluacionId + '/gap', function(r) {
        if (!r.ok) { $('#modalGapBody').html('<div class="alert alert-danger">Error al cargar el análisis.</div>'); return; }
        var d = r.data;
        var colorClasif = { CUMPLE: '#065f46', CUMPLE_PARCIALMENTE: '#1e40af', NO_CUMPLE: '#991b1b' };
        var bgClasif    = { CUMPLE: '#d1fae5', CUMPLE_PARCIALMENTE: '#dbeafe', NO_CUMPLE: '#fee2e2' };
        var clasif      = d.clasificacion || '—';

        var html = '<div class="row mb-4">';
        // Tarjetas resumen
        html += '<div class="col-md-3"><div class="card text-center border-0 shadow-sm"><div class="card-body py-3">'
             + '<h2 class="mb-0 font-weight-bold" style="color:' + (colorClasif[clasif]||'#374151') + '">' + (d.porcentaje||0) + '%</h2>'
             + '<small class="text-muted">Cumplimiento</small></div></div></div>';
        html += '<div class="col-md-3"><div class="card text-center border-0 shadow-sm"><div class="card-body py-3">'
             + '<h2 class="mb-0 font-weight-bold text-success">' + (d.resumen.cumple||0) + '</h2>'
             + '<small class="text-muted">Cumplen</small></div></div></div>';
        html += '<div class="col-md-3"><div class="card text-center border-0 shadow-sm"><div class="card-body py-3">'
             + '<h2 class="mb-0 font-weight-bold text-danger">' + (d.resumen.no_cumple||0) + '</h2>'
             + '<small class="text-muted">No cumplen</small></div></div></div>';
        html += '<div class="col-md-3"><div class="card text-center border-0 shadow-sm"><div class="card-body py-3">'
             + '<h2 class="mb-0 font-weight-bold text-warning">' + (d.resumen.no_verificable||0) + '</h2>'
             + '<small class="text-muted">No verificables</small></div></div></div>';
        html += '</div>';

        // Badge clasificación
        html += '<div class="text-center mb-4"><span class="px-4 py-2 rounded font-weight-bold" style="background:' + (bgClasif[clasif]||'#f3f4f6') + ';color:' + (colorClasif[clasif]||'#374151') + ';font-size:1rem">'
             + clasif.replace(/_/g,' ') + '</span></div>';

        // Tabla por grupos
        if (d.por_grupo && d.por_grupo.length) {
            html += '<h6 class="font-weight-bold mb-2"><i class="fa fa-layer-group mr-1"></i>Por grupo de servicios</h6>';
            html += '<div class="table-responsive mb-4"><table class="table table-sm table-hover">';
            html += '<thead class="thead-light"><tr><th>Grupo</th><th class="text-center">Total</th><th class="text-center text-success">Cumple</th><th class="text-center text-danger">No cumple</th><th class="text-center text-warning">No verif.</th><th>Barra</th></tr></thead><tbody>';
            d.por_grupo.forEach(function(g) {
                var pct = g.total > 0 ? Math.round((g.cumple / g.total) * 100) : 0;
                var barColor = pct >= 90 ? '#22c55e' : pct >= 70 ? '#3b82f6' : '#ef4444';
                html += '<tr><td><strong>' + (g.grupo||'Sin grupo') + '</strong></td>'
                     + '<td class="text-center">' + g.total + '</td>'
                     + '<td class="text-center text-success font-weight-bold">' + g.cumple + '</td>'
                     + '<td class="text-center text-danger font-weight-bold">' + g.no_cumple + '</td>'
                     + '<td class="text-center text-warning font-weight-bold">' + g.no_verificable + '</td>'
                     + '<td style="min-width:100px"><div style="height:8px;background:#e5e7eb;border-radius:4px;overflow:hidden"><div style="height:100%;width:' + pct + '%;background:' + barColor + ';transition:width .4s"></div></div><small>' + pct + '%</small></td>'
                     + '</tr>';
            });
            html += '</tbody></table></div>';
        }

        // Acciones críticas
        if (d.acciones_criticas && d.acciones_criticas.length) {
            html += '<h6 class="font-weight-bold mb-2"><i class="fa fa-exclamation-triangle mr-1 text-danger"></i>Servicios críticos faltantes</h6>';
            html += '<div class="table-responsive"><table class="table table-sm">';
            html += '<thead class="thead-light"><tr><th>Servicio</th><th>Grupo</th><th>Acción recomendada</th></tr></thead><tbody>';
            d.acciones_criticas.forEach(function(a) {
                html += '<tr><td><strong>' + a.servicio + '</strong></td><td><small>' + (a.grupo||'—') + '</small></td><td><small class="text-muted">' + (a.accion||'—') + '</small></td></tr>';
            });
            html += '</tbody></table></div>';
        }

        if (!d.por_grupo || !d.por_grupo.length) {
            html += '<div class="alert alert-info">No hay datos de gap analysis. Ejecute el análisis primero desde la vista de evaluación.</div>';
        }

        $('#modalGapBody').html(html);
    }).fail(function() {
        $('#modalGapBody').html('<div class="alert alert-danger">Error al conectar con el servidor.</div>');
    });
}

function mostrarToast(msg, tipo) {
    var color = tipo === 'success' ? '#22c55e' : '#ef4444';
    var $t = $('<div style="position:fixed;bottom:24px;right:24px;background:' + color + ';color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.2)">' + msg + '</div>');
    $('body').append($t);
    setTimeout(function() { $t.fadeOut(400, function() { $t.remove(); }); }, 3000);
}
</script>
@endsection
