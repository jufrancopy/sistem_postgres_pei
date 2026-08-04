@extends('layouts.master')
@section('title', 'Establecimientos RIISS')

@push('styles')
<style>
/* ── Flujo de pasos ── */
.flujo-paso { display:flex; align-items:center; gap:10px; padding:12px 16px; border-radius:10px; background:#fff; border:1px solid #e5e7eb; flex:1; }
.flujo-paso .paso-num { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.9rem; flex-shrink:0; }
.flujo-arrow { color:#cbd5e1; font-size:1.2rem; flex-shrink:0; }

/* ── Cards de establecimiento ── */
.est-card { border-radius:12px; border:1px solid #e5e7eb; transition:box-shadow .2s, transform .15s; overflow:hidden; }
.est-card:hover { box-shadow:0 6px 24px rgba(0,0,0,.12); transform:translateY(-2px); }
.est-card .est-header { padding:14px 16px 10px; }
.est-card .est-body { padding:0 16px 14px; }
.est-card .est-footer { padding:10px 16px; background:#f9fafb; border-top:1px solid #f0f0f0; display:flex; gap:8px; align-items:center; }

/* ── Badge complejidad ── */
.badge-complejidad { font-size:.68rem; padding:3px 8px; border-radius:20px; color:#fff; font-weight:600; }

/* ── Estado evaluación ── */
.eval-dot { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:4px; }
.eval-dot.evaluado { background:#22c55e; }
.eval-dot.sin-eval  { background:#e5e7eb; }

/* ── Loading ── */
#loadingOverlay { display:none; position:fixed; inset:0; background:rgba(255,255,255,.65); z-index:9999; align-items:center; justify-content:center; }

/* ── Filtros ── */
.filtro-activo { background:#fce4ec !important; border-color:#e91e63 !important; color:#c62828 !important; }
/* Forzar botones circulares en esta vista */
.btn-circle {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 34px !important;
    height: 34px !important;
    padding: 0 !important;
    border-radius: 50% !important;
}
.btn-circle.btn-sm { width: 30px !important; height: 30px !important; }
</style>
@endpush

@section('content')
<div id="loadingOverlay"><div class="spinner-border text-danger"></div></div>

<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="material-icons mr-2" style="vertical-align:middle">local_hospital</i>Establecimientos RIISS
        </h4>
        <p class="card-category">Red Integrada e Integral de Servicios de Salud — IPS</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">RIISS / Establecimientos</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ¿Cómo funciona? --}}
        <div class="mb-4">
            <p class="small font-weight-bold text-uppercase text-muted mb-2">¿Cómo funciona?</p>
            <div class="d-flex align-items-center flex-wrap" style="gap:8px">
                <div class="flujo-paso">
                    <div class="paso-num bg-danger text-white">1</div>
                    <div><div class="font-weight-bold small">Buscá el establecimiento</div><div class="text-muted" style="font-size:.75rem">Filtrá por nombre, departamento o tipo</div></div>
                </div>
                <div class="flujo-arrow d-none d-md-block">›</div>
                <div class="flujo-paso">
                    <div class="paso-num bg-warning text-white">2</div>
                    <div><div class="font-weight-bold small">Hacé clic en <i class="fa fa-clipboard-check text-danger"></i></div><div class="text-muted" style="font-size:.75rem">Abre el formulario de evaluación</div></div>
                </div>
                <div class="flujo-arrow d-none d-md-block">›</div>
                <div class="flujo-paso">
                    <div class="paso-num bg-info text-white">3</div>
                    <div><div class="font-weight-bold small">Respondé el formulario</div><div class="text-muted" style="font-size:.75rem">Sí / No / No Aplica por sección</div></div>
                </div>
                <div class="flujo-arrow d-none d-md-block">›</div>
                <div class="flujo-paso">
                    <div class="paso-num bg-success text-white">4</div>
                    <div><div class="font-weight-bold small">Obtené el resultado</div><div class="text-muted" style="font-size:.75rem">CUMPLE / PARCIAL / NO CUMPLE + brechas</div></div>
                </div>
            </div>
        </div>

        {{-- KPIs --}}
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-danger shadow-sm h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total establecimientos</div>
                        <div class="h4 mb-0 font-weight-bold" id="kpi-total">{{ $stats['total'] ?? '—' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Hospitalarios</div>
                        <div class="h4 mb-0 font-weight-bold" id="kpi-hosp">{{ $stats['hospitalarios'] ?? '—' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ya evaluados</div>
                        <div class="h4 mb-0 font-weight-bold" id="kpi-eval">{{ $stats['con_evaluacion'] ?? '—' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-secondary shadow-sm h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Pendientes de evaluar</div>
                        <div class="h4 mb-0 font-weight-bold" id="kpi-sin">{{ $stats['sin_evaluacion'] ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="row align-items-end mb-4">
            <div class="col-md-4 mb-2">
                <label class="small font-weight-bold text-uppercase text-muted"><i class="fa fa-search mr-1"></i>Buscar establecimiento</label>
                <input type="text" id="fBuscar" class="form-control" placeholder="Nombre, código...">
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold text-uppercase text-muted">Departamento</label>
                <select id="fDepto" class="form-control">
                    <option value="">Todos</option>
                    @foreach($departamentos as $d)
                    <option>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="small font-weight-bold text-uppercase text-muted">Tipo</label>
                <select id="fTipo" class="form-control">
                    <option value="">Todos</option>
                    @foreach($tipos as $t)
                    <option>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-uppercase text-muted">Complejidad</label>
                <select id="fComplejidad" class="form-control">
                    <option value="">Todas</option>
                    @foreach($complejidades as $c)
                    <option>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 mb-2">
                <button class="btn btn-danger btn-block" onclick="buscar(1)" title="Buscar">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>

        {{-- Info resultados --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-muted" id="infoResultados">Cargando...</small>
            <div class="d-flex gap-2 align-items-center">
                <small class="text-muted mr-2">Vista:</small>
                <button class="btn btn-sm btn-outline-secondary" id="btnVistaTarjeta" onclick="setVista('tarjeta')" title="Tarjetas">
                    <i class="fa fa-th-large"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" id="btnVistaTabla" onclick="setVista('tabla')" title="Tabla">
                    <i class="fa fa-list"></i>
                </button>
            </div>
        </div>

        {{-- Contenido (tarjetas o tabla) --}}
        <div id="vistaContenido"></div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-between align-items-center mt-4" id="paginacion" style="display:none!important">
            <small class="text-muted" id="paginaInfo"></small>
            <div id="paginaBtns"></div>
        </div>

    </div>
</div>

{{-- ── Modal editar establecimiento ── --}}
<div class="modal fade" id="modalEditEst" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-info" style="background:linear-gradient(135deg,#00acc1,#26c6da)">
                <h5 class="modal-title text-white"><i class="fa fa-edit mr-2"></i>Editar establecimiento</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editEstId">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Nombre oficial</label>
                        <input type="text" id="editNombre" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Complejidad</label>
                        <select id="editComplejidad" class="form-control">
                            @foreach($complejidadTipos as $ct)
                            <option value="{{ $ct->id }}">{{ $ct->nombre }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Al cambiar esto se recalculan nivel, grado y requisitos automáticamente</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Tipología</label>
                        <select id="editTipologia" class="form-control">
                            <option>PUESTO SANITARIO</option>
                            <option>UNIDAD SANITARIA</option>
                            <option>CLINICA PERIFERICA</option>
                            <option>CENTROS</option>
                            <option>HOSPITAL REGIONAL</option>
                            <option>HOSPITAL</option>
                            <option>HOSPITAL ESPECIALIZADO</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Departamento</label>
                        <input type="text" id="editDepartamento" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Microred</label>
                        <input type="text" id="editMicrored" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Prestador</label>
                        <input type="text" id="editPrestador" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Servicios disponibles</label>
                        <div class="d-flex flex-wrap" style="gap:12px">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="editInternacion">
                                <label class="custom-control-label small" for="editInternacion">Internación</label>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="editQuirofano">
                                <label class="custom-control-label small" for="editQuirofano">Quirófano</label>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="editUti">
                                <label class="custom-control-label small" for="editUti">UTI</label>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="editUrgencias">
                                <label class="custom-control-label small" for="editUrgencias">Urgencias</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="small font-weight-bold">Observación</label>
                        <textarea id="editObservacion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div id="editEstMsg" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" onclick="guardarEstablecimiento()"><i class="fa fa-save mr-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal detalle ── --}}
<div class="modal fade" id="modalEst" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-info" style="background:linear-gradient(135deg,#00acc1,#26c6da)">
                <h5 class="modal-title text-white">
                    <i class="fa fa-hospital mr-2"></i><span id="modalEstNombre">—</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="modalEstBody">
                <div class="text-center py-4"><div class="spinner-border text-danger"></div></div>
            </div>
            <div class="modal-footer">
                <a href="#" id="btnIniciarEval" class="btn btn-danger">
                    <i class="fa fa-clipboard-check mr-1"></i>Iniciar evaluación ahora
                </a>
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let paginaActual = 1;
let vistaActual  = 'tarjeta';

const COLORES = {
    'No Hospitalario de Baja Complejidad':    '#22c55e',
    'No Hospitalario de Mediana Complejidad': '#84cc16',
    'Hospitalario 1 Baja Complejidad':        '#eab308',
    'Hospitalario 2 Mediana Complejidad':     '#f97316',
    'Hospitalario 3 Alta Complejidad':        '#ef4444',
};

$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    cargarEstadisticas();
    buscar(1);

    let timer;
    $('#fBuscar').on('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => buscar(1), 350);
    });
    $('#fDepto, #fTipo, #fComplejidad').on('change', () => buscar(1));

    // Vista por defecto
    setVista('tarjeta');
});

function setVista(v) {
    vistaActual = v;
    $('#btnVistaTarjeta').toggleClass('btn-danger btn-outline-secondary', v === 'tarjeta');
    $('#btnVistaTabla').toggleClass('btn-danger btn-outline-secondary', v === 'tabla');
    buscar(paginaActual);
}

function cargarEstadisticas() {
    $.get('{{ route("riiss.establecimientos.estadisticas") }}', function(r) {
        if (!r.ok) return;
        $('#kpi-total').text(r.data.total);
        $('#kpi-hosp').text(r.data.hospitalarios);
        $('#kpi-eval').text(r.data.con_evaluacion);
        $('#kpi-sin').text(r.data.sin_evaluacion);
    });
}

function buscar(pagina) {
    paginaActual = pagina || 1;
    $('#loadingOverlay').css('display','flex');

    $.get('{{ route("riiss.establecimientos.buscar") }}', {
        buscar:      $('#fBuscar').val(),
        departamento:$('#fDepto').val(),
        tipo:        $('#fTipo').val(),
        complejidad: $('#fComplejidad').val(),
        page:        paginaActual,
        per_page:    vistaActual === 'tarjeta' ? 12 : 25,
    }, function(r) {
        $('#loadingOverlay').hide();
        if (!r.ok) return;
        const d = r.data;
        $('#infoResultados').text(`${d.total} establecimientos encontrados`);
        vistaActual === 'tarjeta' ? renderTarjetas(d.data) : renderTabla(d.data);
        renderPaginacion(d);
        $('#paginacion').show();
    }).fail(() => $('#loadingOverlay').hide());
}

// ── Vista tarjetas ────────────────────────────────────────────────────────────
function renderTarjetas(items) {
    if (!items.length) {
        $('#vistaContenido').html('<div class="text-center py-5 text-muted"><i class="fa fa-search fa-2x mb-3 d-block" style="opacity:.3"></i>Sin resultados</div>');
        return;
    }

    let html = '<div class="row">';
    items.forEach(e => {
        const color   = COLORES[e.complejidad] || '#6b7280';
        const evalBadge = e.tiene_ultima_evaluacion
            ? '<span class="eval-dot evaluado"></span><span class="text-success small font-weight-bold">Evaluado</span>'
            : '<span class="eval-dot sin-eval"></span><span class="text-muted small">Sin evaluar</span>';

        html += `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="est-card h-100">
                <div class="est-header">
                    <div class="d-flex align-items-start justify-content-between">
                        <div style="flex:1;min-width:0">
                            <div class="font-weight-bold text-truncate" title="${e.nombre}">${e.nombre}</div>
                            <small class="text-muted">${e.id}</small>
                        </div>
                        <span class="badge ml-2" style="background:#f1f5f9;color:#475569;font-size:.65rem;flex-shrink:0">${e.tipo}</span>
                    </div>
                </div>
                <div class="est-body">
                    <span class="badge-complejidad d-inline-block mb-2" style="background:${color}">${e.complejidad_label ?? e.complejidad}</span>
                    <div class="d-flex gap-3 small text-muted">
                        <span><i class="fa fa-map-marker-alt mr-1"></i>${e.departamento}</span>
                        ${e.microred ? `<span><i class="fa fa-network-wired mr-1"></i>${e.microred}</span>` : ''}
                    </div>
                </div>
                <div class="est-footer">
                    <div class="flex-grow-1">${evalBadge}</div>
                    <button class="btn btn-info btn-circle btn-sm mr-1" onclick="verDetalle('${e.id}','${e.nombre.replace(/'/g,"\\'")}')" title="Ver detalle"><i class="fa fa-eye"></i></button>
                    <button class="btn btn-warning btn-circle btn-sm mr-1" onclick="abrirEditar('${e.id}')" title="Editar"><i class="fa fa-edit"></i></button>
                    <a href="/riiss/evaluaciones/nueva/${e.id}" class="btn btn-sm btn-danger" title="Iniciar evaluacion"><i class="fa fa-clipboard-check mr-1"></i>Evaluar</a>
                </div>
            </div>
        </div>`;
    });
    html += '</div>';
    $('#vistaContenido').html(html);
}

// ── Vista tabla ───────────────────────────────────────────────────────────────
function renderTabla(items) {
    if (!items.length) {
        $('#vistaContenido').html('<div class="text-center py-5 text-muted">Sin resultados</div>');
        return;
    }

    let rows = items.map(e => {
        const color = COLORES[e.complejidad] || '#6b7280';
        const evalBadge = e.tiene_ultima_evaluacion
            ? '<span class="badge badge-success">Evaluado</span>'
            : '<span class="badge badge-light text-muted">Sin evaluar</span>';
        return `<tr>
            <td><strong>${e.nombre}</strong><br><small class="text-muted">${e.id}</small></td>
            <td><span class="badge" style="background:#f1f5f9;color:#475569">${e.tipo}</span></td>
            <td><span class="badge-complejidad" style="background:${color}">${e.complejidad_label ?? e.complejidad}</span></td>
            <td><small>${e.departamento}</small></td>
            <td>${evalBadge}</td>
                <td class="text-center">
                <button class="btn btn-info btn-circle btn-sm mr-1" onclick="verDetalle('${e.id}','${e.nombre.replace(/'/g,"\\'")}')" title="Ver detalle"><i class="fa fa-eye"></i></button>
                <button class="btn btn-warning btn-circle btn-sm mr-1" onclick="abrirEditar('${e.id}')" title="Editar"><i class="fa fa-edit"></i></button>
                <a href="/riiss/evaluaciones/nueva/${e.id}" class="btn btn-danger btn-circle btn-sm" title="Evaluar"><i class="fa fa-clipboard-check"></i></a>
            </td>
        </tr>`;
    }).join('');

    $('#vistaContenido').html(`
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Establecimiento</th><th>Tipo</th><th>Complejidad</th>
                        <th>Departamento</th><th>Estado</th><th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>`);
}

// ── Paginación ────────────────────────────────────────────────────────────────
function renderPaginacion(d) {
    $('#paginaInfo').text(`Mostrando ${d.from ?? 0}–${d.to ?? 0} de ${d.total}`);
    let btns = '';
    if (d.current_page > 1)
        btns += `<button class="btn btn-sm btn-outline-secondary mr-1" onclick="buscar(${d.current_page-1})">‹</button>`;
    const s = Math.max(1, d.current_page-2), end = Math.min(d.last_page, d.current_page+2);
    for (let i = s; i <= end; i++)
        btns += `<button class="btn btn-sm ${i===d.current_page?'btn-danger':'btn-outline-secondary'} mr-1" onclick="buscar(${i})">${i}</button>`;
    if (d.current_page < d.last_page)
        btns += `<button class="btn btn-sm btn-outline-secondary" onclick="buscar(${d.current_page+1})">›</button>`;
    $('#paginaBtns').html(btns);
}

// ── Editar establecimiento ───────────────────────────────────────────────────
function abrirEditar(id) {
    $('#editEstId').val(id);
    $('#editEstMsg').html('');
    $('#modalEditEst').modal('show');
    $.get('/riiss/establecimientos/' + id, function(r) {
        if (!r.ok) return;
        var e = r.data;
        $('#editNombre').val(e.nombre_oficial);
        $('#editComplejidad').val(e.complejidad_tipo_id);
        $('#editTipologia').val(e.tipologia_clasificacion);
        $('#editDepartamento').val(e.departamento);
        $('#editMicrored').val(e.microred || '');
        $('#editPrestador').val(e.prestador || '');
        $('#editInternacion').prop('checked', e.tiene_internacion);
        $('#editQuirofano').prop('checked', e.tiene_quirofano_req);
        $('#editUti').prop('checked', e.tiene_uti_req);
        $('#editUrgencias').prop('checked', e.tiene_urgencias_req);
        $('#editObservacion').val(e.observacion || '');
    });
}

function guardarEstablecimiento() {
    var id = $('#editEstId').val();
    $.ajax({
        url: '/riiss/establecimientos/' + id,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token:                  '{{ csrf_token() }}',
            _method:                 'PATCH',
            nombre_oficial:          $('#editNombre').val(),
            complejidad_tipo_id:      $('#editComplejidad').val(),
            tipologia_clasificacion: $('#editTipologia').val(),
            departamento:            $('#editDepartamento').val(),
            microred:                $('#editMicrored').val() || null,
            prestador:               $('#editPrestador').val() || null,
            tiene_internacion:       $('#editInternacion').is(':checked') ? 1 : 0,
            tiene_quirofano_req:     $('#editQuirofano').is(':checked') ? 1 : 0,
            tiene_uti_req:           $('#editUti').is(':checked') ? 1 : 0,
            tiene_urgencias_req:     $('#editUrgencias').is(':checked') ? 1 : 0,
            observacion:             $('#editObservacion').val() || null,
        }),
        success: function(r) {
            if (r.ok) {
                $('#modalEditEst').modal('hide');
                buscar(paginaActual);
            } else {
                $('#editEstMsg').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
            }
        },
        error: function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error al guardar.';
            $('#editEstMsg').html('<div class="alert alert-danger py-2">' + msg + '</div>');
        }
    });
}

// ── Modal detalle ─────────────────────────────────────────────────────────────
function verDetalle(id, nombre) {
    $('#modalEstNombre').text(nombre);
    $('#modalEstBody').html('<div class="text-center py-4"><div class="spinner-border text-danger"></div></div>');
    $('#btnIniciarEval').attr('href', `/riiss/evaluaciones/nueva/${id}`);
    $('#modalEst').modal('show');

    $.get(`/riiss/establecimientos/${id}`, function(r) {
        if (!r.ok) return;
        const e   = r.data;
        const req = r.cartera_requisitos;
        const infra = req.infraestructura_requerida;

        const infraHtml = Object.entries(infra).map(([k, v]) => {
            const labels = { internacion:'Internación', urgencias:'Urgencias', quirofano:'Quirófano',
                             uti:'UTI', laboratorio:'Laboratorio', imagenes:'Imágenes',
                             farmacia:'Farmacia', vacunatorio:'Vacunatorio' };
            return `<span class="badge badge-${v?'success':'light'} mr-1 mb-1" style="${!v?'color:#94a3b8':''}">${v?'✅':'⬜'} ${labels[k]||k}</span>`;
        }).join('');

        const porTipoHtml = Object.entries(req.por_tipo_prestacion).map(([k,v]) =>
            `<div class="d-flex justify-content-between small py-1 border-bottom">
                <span class="text-muted">${k}</span>
                <strong>${v} servicios</strong>
            </div>`
        ).join('');

        $('#modalEstBody').html(`
        <div class="row">
            <div class="col-md-5">
                <h6 class="font-weight-bold text-danger mb-3">Datos del establecimiento</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted small py-1" style="width:40%">ID</th><td class="small">${e.id_establecimiento}</td></tr>
                    <tr><th class="text-muted small py-1">Tipo</th><td class="small">${e.tipo_est_label}</td></tr>
                    <tr><th class="text-muted small py-1">Tipología</th><td class="small">${e.tipologia_clasificacion}</td></tr>
                    <tr><th class="text-muted small py-1">Complejidad</th><td>
                        <span class="badge" style="background:${e.complejidad_color};color:#fff;font-size:.7rem">${e.complejidad}</span>
                    </td></tr>
                    <tr><th class="text-muted small py-1">Nivel / Grado</th><td class="small">${e.nivel_atencion??'—'} / ${e.grado_complejidad??'—'}</td></tr>
                    <tr><th class="text-muted small py-1">Departamento</th><td class="small">${e.departamento}</td></tr>
                    <tr><th class="text-muted small py-1">Microred</th><td class="small">${e.microred??'—'}</td></tr>
                    <tr><th class="text-muted small py-1">Prestador</th><td class="small">${e.prestador}</td></tr>
                </table>
            </div>
            <div class="col-md-7">
                <h6 class="font-weight-bold text-danger mb-2">Debe tener según su nivel</h6>
                <div class="mb-3">
                    <span class="badge badge-danger mr-1">${req.totales.servicios_requeridos} servicios obligatorios</span>
                    <span class="badge badge-secondary">${req.totales.servicios_opcionales} opcionales</span>
                </div>
                <div class="mb-3">${infraHtml}</div>
                <h6 class="font-weight-bold text-muted small mb-1">Por tipo de prestación</h6>
                ${porTipoHtml}
                <div class="alert alert-info mt-3 py-2 small mb-0">
                    <i class="fa fa-info-circle mr-1"></i>
                    Al evaluar, el sistema verificará si el establecimiento cuenta con estos servicios.
                </div>
            </div>
        </div>`);
    });
}
</script>
@endsection
