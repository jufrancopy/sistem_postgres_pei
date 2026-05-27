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

{{-- ── Banner de flujo ── --}}
<div class="card mb-3 border-0 shadow-sm" style="background:linear-gradient(135deg,#c62828,#e91e63)">
    <div class="card-body py-3 text-white">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-1 font-weight-bold"><i class="material-icons mr-2" style="vertical-align:middle;font-size:1.2rem">local_hospital</i>Evaluación de Cartera de Servicios</h5>
                <p class="mb-0 small" style="opacity:.85">Verificá si cada establecimiento cumple con los servicios que debe ofrecer según su nivel de complejidad</p>
            </div>
            <a href="{{ route('riiss.evaluaciones.index') }}" class="btn btn-light btn-sm">
                <i class="fa fa-history mr-1"></i>Ver evaluaciones anteriores
            </a>
        </div>
    </div>
</div>

                <div class="card shadow-sm">
        <div class="card-header card-header-danger">
            <h4 class="card-title mb-0"><i class="fa fa-hospital mr-2"></i>Listado de establecimientos</h4>
            <p class="card-category mb-0 small">Explorar y evaluar establecimientos</p>
        </div>

        <nav class="bg-light rounded px-3 py-2 mb-0">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">RIISS</a></li>
                <li class="breadcrumb-item active">Establecimientos</li>
            </ol>
        </nav>
        <div class="card-body">

        {{-- ¿Cómo funciona? (diseño embebido) --}}
        <div class="mb-4">
            <p class="small font-weight-bold text-uppercase text-muted mb-2">¿Cómo funciona?</p>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="flujo-paso">
                    <div class="paso-num bg-danger text-white">1</div>
                    <div>
                        <div class="font-weight-bold small">Buscá el establecimiento</div>
                        <div class="text-muted" style="font-size:.75rem">Filtrá por nombre, departamento o tipo</div>
                    </div>
                </div>
                <div class="flujo-arrow d-none d-md-block">›</div>
                <div class="flujo-paso">
                    <div class="paso-num bg-warning text-white">2</div>
                    <div>
                        <div class="font-weight-bold small">Hacé clic en <i class="fa fa-clipboard-check text-danger"></i></div>
                        <div class="text-muted" style="font-size:.75rem">Abre el formulario de evaluación</div>
                    </div>
                </div>
                <div class="flujo-arrow d-none d-md-block">›</div>
                <div class="flujo-paso">
                    <div class="paso-num bg-info text-white">3</div>
                    <div>
                        <div class="font-weight-bold small">Respondé el formulario</div>
                        <div class="text-muted" style="font-size:.75rem">Sí / No / No Aplica por sección</div>
                    </div>
                </div>
                <div class="flujo-arrow d-none d-md-block">›</div>
                <div class="flujo-paso">
                    <div class="paso-num bg-success text-white">4</div>
                    <div>
                        <div class="font-weight-bold small">Obtené el resultado</div>
                        <div class="text-muted" style="font-size:.75rem">CUMPLE / PARCIAL / NO CUMPLE + brechas</div>
                    </div>
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

    

{{-- ── Modal detalle ── --}}
<div class="modal fade" id="modalEst" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#c62828,#e91e63)">
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
                    <button class="btn btn-info btn-circle btn-sm mr-1" onclick="verDetalle('${e.id}','${e.nombre.replace(/'/g,"\\'")}')" title="Ver detalle">
                        <i class="fa fa-eye"></i>
                    </button>
                    <a href="/riiss/evaluaciones/nueva/${e.id}" class="btn btn-sm btn-danger" title="Iniciar evaluación">
                        <i class="fa fa-clipboard-check mr-1"></i>Evaluar
                    </a>
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
                <button class="btn btn-info btn-circle btn-sm mr-1" onclick="verDetalle('${e.id}','${e.nombre.replace(/'/g,"\\'")}')" title="Ver detalle">
                    <i class="fa fa-eye"></i>
                </button>
                <a href="/riiss/evaluaciones/nueva/${e.id}" class="btn btn-danger btn-circle btn-sm" title="Evaluar">
                    <i class="fa fa-clipboard-check"></i>
                </a>
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
