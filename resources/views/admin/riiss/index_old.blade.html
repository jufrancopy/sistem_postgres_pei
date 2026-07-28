@extends('layouts.master')
@section('title', 'Centro de Control RIISS')

@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
<style>
.riiss-tabs .nav-link {
    font-weight: 600;
    color: #475569;
    border-radius: 12px;
    padding: 10px 18px;
    margin-right: 8px;
    transition: all .2s;
    background: #f8fbfe;
    border: 1px solid #dbeaf4;
}
.riiss-tabs .nav-link.active {
    background: linear-gradient(135deg, #00acc1, #26c6da) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    box-shadow: 0 8px 18px rgba(6, 78, 126, .15);
}
.riiss-tabs .nav-link.active i {
    color: rgba(255,255,255,.85) !important;
}
.riiss-tabs .nav-link:hover:not(.active) {
    background: #e2f5fb;
    color: #0f172a;
    border-color: #b8e2f5;
}
.eval-card {
    border-radius: 20px !important;
    border: 1px solid #e8eff8 !important;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
    box-shadow: 0 18px 46px rgba(15, 23, 42, 0.06) !important;
    overflow: hidden !important;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease !important;
    position: relative;
}
.eval-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 22px 56px rgba(15, 23, 42, 0.12) !important;
    border-color: #d1dce6 !important;
}
.eval-card-accent {
    position: absolute;
    top: 0;
    left: 0;
    width: 6px;
    height: 100%;
    background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
}
.eval-card-body {
    position: relative;
    padding: 1.6rem 1.6rem 1.2rem !important;
}
.eval-card-info {
    min-height: 150px;
}
.eval-card-meta {
    background: rgba(255,255,255,0.95);
    border-radius: 16px;
    padding: 0.9rem 1rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
}
.eval-card-progress {
    height: 10px;
    border-radius: 999px;
    background-color: #e2e8f0;
    overflow: hidden;
    position: relative;
}
.eval-card-progress .progress-bar {
    display: block;
    width: 0;
    height: 100%;
    min-width: 4px;
    border-radius: 999px;
    transition: width .45s ease, background .45s ease;
    box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.18);
}
.eval-btn-continue {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 999px !important;
    font-weight: 700 !important;
    padding: 11px 16px !important;
    font-size: .88rem !important;
    transition: all .2s !important;
    text-transform: uppercase;
    letter-spacing: .5px;
    display: block;
}
.eval-btn-continue:hover {
    opacity: .95;
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.24) !important;
}
.estado-badge { font-size:.72rem; padding:6px 11px; border-radius:22px; font-weight:700; letter-spacing:.35px; }
.estado-pendiente   { background:#fef3c7; color:#92400e; }
.estado-en_progreso { background:#dbeafe; color:#1e40af; }
.estado-completada  { background:#d1fae5; color:#065f46; }
.estado-vencida     { background:#fee2e2; color:#991b1b; }
.estado-cancelada   { background:#f1f5f9; color:#64748b; }
.estado-sin_iniciar { background:#f1f5f9; color:#64748b; }

.circle-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    border: 1px solid rgba(15, 23, 42, 0.08);
    cursor: pointer;
    font-size: .8rem;
    transition: opacity .15s, transform .1s;
}
.circle-btn:hover { opacity: .92; transform: scale(1.08); }
.circle-btn-success { background:#d1fae5; color:#065f46; }
.circle-btn-primary { background:#93c5fd; color:#1d4ed8; }
.circle-btn-info    { background:#bfdbfe; color:#0c4a6e; }
.circle-btn-danger  { background:#fecaca; color:#991b1b; }
.circle-btn-warning { background:#fde68a; color:#92400e; }
.riiss-action-btn {
    border-radius: 999px !important;
    padding: 7px 12px !important;
    font-size: .78rem !important;
    font-weight: 700 !important;
    letter-spacing: .3px;
    border: 1px solid transparent !important;
    transition: all .2s ease;
    box-shadow: none !important;
}
.riiss-action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(15, 23, 42, 0.12) !important;
}
.riiss-action-btn-view {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd) !important;
    color: #0369a1 !important;
    border-color: #7dd3fc !important;
}
.riiss-action-btn-gap {
    background: linear-gradient(135deg, #fef3c7, #fde68a) !important;
    color: #92400e !important;
    border-color: #f59e0b !important;
}
</style>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header card-header-info py-3" style="background: linear-gradient(135deg, #00acc1, #26c6da); border-radius: 12px; box-shadow: 0 12px 26px rgba(0, 172, 193, 0.18);">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h4 class="card-title mb-0 text-white">
                    <i class="fa fa-hospital mr-2"></i>Centro de Control RIISS
                </h4>
                <p class="card-category mb-0 text-white-75">Red Integrada e Integral de Servicios de Salud — Gestión & Monitoreo</p>
            </div>
            <div class="text-right mt-3 mt-md-0">
                <button class="btn btn-white btn-sm font-weight-bold" onclick="abrirModalNuevaAsignacion()" style="color: #00acc1; border: 1px solid rgba(255,255,255,.35); background: rgba(255,255,255,.95);">
                    <i class="fa fa-plus-circle mr-1 text-info"></i> Nueva Asignación
                </button>
            </div>
        </div>
    </div>
    
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Centro RIISS</li>
        </ol>
    </nav>
    <div class="card-body pb-2">
        {{-- Pestañas de navegación principal --}}
        <ul class="nav nav-pills riiss-tabs border-0" id="riissMainTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-dashboard-tab" data-toggle="tab" href="#tab-dashboard" role="tab">
                    <i class="fa fa-chart-line mr-1"></i>Monitoreo & KPIs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-establecimientos-tab" data-toggle="tab" href="#tab-establecimientos" role="tab">
                    <i class="fa fa-building mr-1"></i>Establecimientos & Asignaciones
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-historial-tab" data-toggle="tab" href="#tab-historial" role="tab">
                    <i class="fa fa-history mr-1"></i>Historial de Evaluaciones
                </a>
            </li>
        </ul>
    </div>
</div>

{{-- Contenido de las pestañas --}}
<div class="tab-content" id="riissMainTabsContent">

    {{-- TAB 1: Monitoreo & KPIs --}}
    <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel">
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

        <div class="card shadow-sm">
            <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
                <h6 class="font-weight-bold mb-0 text-dark">
                    <i class="fa fa-sync-alt mr-2 text-danger"></i>Evaluaciones en tiempo real
                </h6>
                <small class="text-muted">Actualiza automáticamente cada 30s</small>
            </div>
            <div class="card-body">
                <div id="evalCards">
                    <div class="text-center py-5">
                        <div class="spinner-border text-danger mb-2"></div>
                        <p class="text-muted">Cargando monitoreo...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: Establecimientos & Asignaciones --}}
    <div class="tab-pane fade" id="tab-establecimientos" role="tabpanel">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                {{-- Filtros --}}
                <div class="row mb-3 align-items-center">
                    <div class="col-md-3 mb-2">
                        <input id="fBuscarUnificado" type="text" class="form-control" style="width:100%" placeholder="Buscar establecimiento...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="fTipologiaUnificada" class="form-control" style="width:100%">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="fConAsignacion" class="form-control" style="width:100%">
                            <option value=""></option>
                            <option value="con">Con Evaluador Asignado</option>
                            <option value="sin">Sin Evaluador Asignado</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="fEvaluadorUnificado" class="form-control" style="width:100%">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <button class="btn btn-primary btn-block font-weight-bold px-0" onclick="tablaUnificada.draw()" title="Buscar" style="border-radius:8px;">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

                {{-- Tabla Unificada DataTable --}}
                <div class="table-responsive">
                    <table class="table table-hover table-sm w-100" id="tablaUnificada">
                        <thead class="thead-light">
                            <tr>
                                <th width="30">#</th>
                                <th>Establecimiento</th>
                                <th>Tipología / Ubicación</th>
                                <th>Evaluador Asignado</th>
                                <th>Plan PEI</th>
                                <th>Fecha Límite</th>
                                <th>Estado Supervisión</th>
                                <th>Cumplimiento</th>
                                <th class="text-center" width="130">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: Historial de Evaluaciones --}}
    <div class="tab-pane fade" id="tab-historial" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-weight-bold mb-0 text-dark">
                        <i class="fa fa-history mr-2 text-info"></i>Historial consolidado de evaluaciones
                    </h6>
                    <small class="text-muted">Tabla con búsqueda, orden y exportación</small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm w-100" id="tablaHistorial">
                        <thead class="thead-light">
                            <tr>
                                <th>Establecimiento</th>
                                <th>Evaluadores</th>
                                <th>Fecha Evaluación</th>
                                <th>Estado</th>
                                <th>Cumplimiento %</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal Editar Datos del Establecimiento --}}
<div class="modal fade" id="modalEditarEstablecimiento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-info" style="background:linear-gradient(135deg,#0f172a,#1e293b)">
                <h5 class="modal-title text-white">
                    <i class="fa fa-hospital mr-2"></i>Editar Datos del Establecimiento
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editEstId">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="small font-weight-bold">Nombre Oficial del Establecimiento</label>
                        <input type="text" id="editEstNombre" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">Código ID</label>
                        <input type="text" id="editEstCodigo" class="form-control" readonly style="background:#f8fafc">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Tipología / Clasificación</label>
                        <input type="text" id="editEstTipologia" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Departamento</label>
                        <input type="text" id="editEstDepto" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Observación / Notas</label>
                        <textarea id="editEstObservacion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div id="msgEditEst"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-dark" onclick="guardarEdicionEstablecimiento()">
                    <i class="fa fa-save mr-1"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Asignación --}}
<div class="modal fade" id="modalAsignacionUnificada" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-danger" style="background:linear-gradient(135deg,#c62828,#e91e63)">
                <h5 class="modal-title text-white" id="modalAsignacionTitulo">
                    <i class="fa fa-user-check mr-2"></i>Asignación de Evaluación
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
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold"><i class="fa fa-bullseye text-info mr-1"></i> Plan PEI / Marco Estratégico Asociado</label>
                        <select id="selPeiProfile" class="form-control" style="width:100%"></select>
                        <small class="text-muted d-block mt-1">Asocia esta evaluación a un Plan PEI específico para el conteo de metas institucionales.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Fecha límite</label>
                        <input type="date" id="selFechaLimite" class="form-control">
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
                <button class="btn btn-danger" id="btnGuardarAsignacion" onclick="guardarAsignacion()">
                    <i class="fa fa-paper-plane mr-1"></i>Guardar Asignación
                </button>
            </div>
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
@endsection

@section('scripts')
<script>
var DASH_URL  = '{{ route("riiss.dashboard.datos") }}';
var UNIF_URL  = '{{ route("riiss.datos-unificados") }}';
var STORE_URL = '{{ route("riiss.asignaciones.store") }}';
var USERS_URL = '{{ route("riiss.evaluaciones.usuarios") }}';
var BUSCAR_URL= '{{ route("riiss.establecimientos.buscar") }}';
var TARGET_PEI= '{{ $targetPeiId }}';

var editAsignacionId = null;
var historialTable = null;

$(document).ready(function() {
    // Select2 en modal asignaciones
    $('#selEstablecimiento').select2({
        placeholder: 'Buscar establecimiento...', allowClear: true, width: '100%',
        dropdownParent: $('#modalAsignacionUnificada'),
        ajax: { url: BUSCAR_URL, dataType: 'json', delay: 250,
            data: function(p) { return { buscar: p.term || '', per_page: 20 }; },
            processResults: function(d) {
                return { results: d.data.data.map(function(e) {
                    return { id: e.id_establecimiento, text: e.nombre + ' (' + e.tipologia + ')' };
                })};
            }
        }
    });

    $('#selEvaluador').select2({
        placeholder: 'Buscar evaluador...', allowClear: true, width: '100%',
        dropdownParent: $('#modalAsignacionUnificada'),
        ajax: { url: USERS_URL, dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; },
        }
    });

    $('#selPeiProfile').select2({
        placeholder: '— Plan PEI 2024–2028 (Default) —', allowClear: true, width: '100%',
        dropdownParent: $('#modalAsignacionUnificada'),
        ajax: {
            url: '{{ route("globales.get-pei-profiles") }}', dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d }; }
        }
    });

    // Select2 Con Asignación en filtro
    $('#fConAsignacion').select2({
        placeholder: 'Todas las asignaciones', allowClear: true, width: '100%'
    });

    // Select2 Tipología en filtro
    $('#fTipologiaUnificada').select2({
        placeholder: 'Todas las tipologías', allowClear: true, width: '100%'
    });

    // Select2 Evaluador en filtro
    $('#fEvaluadorUnificado').select2({
        placeholder: 'Todos los evaluadores', allowClear: true, width: '100%',
        ajax: {
            url: USERS_URL, dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; },
        }
    });

    // Cargar tipologías en el filtro correctamente
    $.get('{{ route("riiss.formularios.tipologias") }}', function(r) {
        if (r.ok && r.data) {
            $('#fTipologiaUnificada').empty().append(new Option('', '', true, true));
            r.data.forEach(function(t) {
                var val = typeof t === 'string' ? t : (t.tipologia_clasificacion || t.tipologia || '');
                if (val) {
                    $('#fTipologiaUnificada').append(new Option(val, val, false, false));
                }
            });
            $('#fTipologiaUnificada').trigger('change.select2');
        }
    });

    // ── DataTable Unificada ──────────────────────────────────────────
    var tablaUnificada = $('#tablaUnificada').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', text: '<i class="fa fa-file-excel mr-1"></i>Excel', className: 'btn btn-sm btn-outline-success', title: 'RIISS - Establecimientos' },
            { extend: 'pdf',   text: '<i class="fa fa-file-pdf mr-1"></i>PDF', className: 'btn btn-sm btn-outline-danger', title: 'RIISS - Establecimientos' },
            { extend: 'print', text: '<i class="fa fa-print mr-1"></i>Imprimir', className: 'btn btn-sm btn-outline-secondary' },
        ],
        language: {
            emptyTable:     'Sin establecimientos registrados',
            info:           'Mostrando _START_ a _END_ de _TOTAL_ establecimientos',
            infoEmpty:      '0 establecimientos',
            infoFiltered:   '(filtrado de _MAX_ totales)',
            search:         'Buscar:',
            zeroRecords:    'No se encontraron establecimientos',
            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
        },
        ajax: {
            url: UNIF_URL,
            data: function(d) {
                d.buscar = $('#fBuscarUnificado').val();
                d.tipologia = $('#fTipologiaUnificada').val() || '';
                d.evaluador_id = $('#fEvaluadorUnificado').val() || '';
                d.con_asignacion = $('#fConAsignacion').val() || '';
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'establecimiento', name: 'nombre_oficial' },
            { data: 'tipologia_ubicacion', name: 'tipologia_clasificacion' },
            { data: 'evaluador', name: 'evaluador', orderable: false, searchable: false },
            { data: 'pei_plan', name: 'pei_plan', orderable: false, searchable: false },
            { data: 'fecha_limite', name: 'fecha_limite', orderable: false, searchable: false },
            { data: 'estado_supervision', name: 'estado_supervision', orderable: false, searchable: false },
            { data: 'cumplimiento', name: 'cumplimiento', orderable: false, searchable: false },
            { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    window.tablaUnificada = tablaUnificada;

    $('#fBuscarUnificado').on('keyup change', function() { tablaUnificada.draw(); });
    $('#fTipologiaUnificada, #fConAsignacion, #fEvaluadorUnificado').on('change', function() { tablaUnificada.draw(); });

    historialTable = $('#tablaHistorial').DataTable({
        processing: true,
        pageLength: 10,
        order: [[2, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', text: '<i class="fa fa-file-excel mr-1"></i>Excel', className: 'btn btn-sm btn-outline-success', title: 'RIISS - Historial de Evaluaciones' },
            { extend: 'pdf', text: '<i class="fa fa-file-pdf mr-1"></i>PDF', className: 'btn btn-sm btn-outline-danger', title: 'RIISS - Historial de Evaluaciones' },
            { extend: 'print', text: '<i class="fa fa-print mr-1"></i>Imprimir', className: 'btn btn-sm btn-outline-secondary' },
        ],
        language: {
            emptyTable: 'Sin evaluaciones registradas',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ evaluaciones',
            infoEmpty: '0 evaluaciones',
            infoFiltered: '(filtrado de _MAX_ totales)',
            search: 'Buscar:',
            zeroRecords: 'No se encontraron evaluaciones',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        columns: [
            { data: 'establecimiento', orderable: false, searchable: true },
            { data: 'evaluadores', orderable: false, searchable: false },
            { data: 'fecha', orderable: true, searchable: false },
            { data: 'estado', orderable: false, searchable: false },
            { data: 'cumplimiento', orderable: false, searchable: false },
            { data: 'acciones', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    // Cargar Monitoreo e Historial
    cargarDashboard();
    cargarHistorial();

    setInterval(cargarDashboard, 30000);
});

function cargarDashboard() {
    $.get(DASH_URL, function(r) {
        if (!r || !r.ok) return;
        var res = r.resumen || {};
        var evs = r.evaluaciones || [];

        $('#kpi-total').text(res.total || 0);
        $('#kpi-progreso').text(res.en_progreso || 0);
        $('#kpi-completadas').text(res.completadas || 0);
        $('#kpi-promedio').text((res.promedio_pct || 0) + '%');

        if (!evs.length) {
            $('#evalCards').html('<div class="text-center py-4 text-muted">Sin evaluaciones registradas</div>');
            return;
        }

        var html = '<div class="row">';
        evs.forEach(function(ev) {
            var pct = ev.progreso || ev.porcentaje_cumplimiento || 0;
            var estadoLabel = (ev.estado || '').replace(/_/g,' ');
            var evalNombre = ev.evaluador && ev.evaluador !== '—' ? ev.evaluador : 'Sin evaluador asignado';
            
            var badgeClass = '';
            if (ev.estado === 'pendiente') badgeClass = 'estado-pendiente';
            else if (ev.estado === 'en_progreso') badgeClass = 'estado-en_progreso';
            else badgeClass = 'estado-completada';

            html += '<div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">'
                + '<div class="card eval-card h-100">'
                + '<div class="eval-card-accent"></div>'
                + '<div class="card-body eval-card-body d-flex flex-column justify-content-between">'
                + '<div class="eval-card-info">'
                + '<div class="d-flex align-items-start justify-content-between mb-3">'
                + '<div>'
                + '<h5 class="font-weight-bold text-dark mb-1" style="font-size:1.05rem; line-height:1.2;">' + ev.establecimiento + '</h5>'
                + '<small class="text-muted" style="font-size:0.82rem;">' + ev.tipologia + '</small>'
                + '</div>'
                + '<span class="estado-badge ' + badgeClass + ' text-capitalize">' + estadoLabel + '</span>'
                + '</div>'
                + '<div class="eval-card-meta mb-3 d-flex align-items-center">'
                + '<div class="rounded-circle bg-info d-flex align-items-center justify-content-center mr-3" style="width:38px; height:38px; color:#0369a1;">'
                + '<i class="fa fa-user" style="font-size:0.9rem;"></i></div>'
                + '<div>'
                + '<div class="font-weight-bold text-dark" style="font-size:0.9rem;">' + evalNombre + '</div>'
                + '<small class="text-muted" style="font-size:0.78rem; letter-spacing:0.3px;">Evaluador asignado</small>'
                + '</div>'
                + '</div>'
                + '<div>'
                + '<div class="d-flex justify-content-between align-items-center mb-2">'
                + '<span class="text-uppercase text-muted small" style="letter-spacing:0.6px;">Nivel de avance</span>'
                + '<span class="font-weight-bold text-primary" style="font-size:1rem;">' + pct + '%</span>'
                + '</div>'
                + '<div class="eval-card-progress mb-3"><div class="progress-bar" role="progressbar" style="width:' + Math.min(Math.max(pct, 0), 100) + '%; height:100%; background:linear-gradient(90deg, #3b82f6, #8b5cf6);"></div></div>'
                + '</div>'
                + '</div>'
                + '<div class="mt-2 d-flex justify-content-center">'
                + '<button type="button" class="circle-btn circle-btn-primary btn-sm" onclick="verDetalleRiiss(\'' + encodeURIComponent(ev.id_establecimiento || '') + '\',' + ev.id + ', \'' + (ev.establecimiento || '').replace(/\\/g,'\\\\').replace(/'/g,'\\\'') + '\', \'' + (ev.estado || '').replace(/\\/g,'\\\\').replace(/'/g,'\\\'') + '\', ' + (ev.progreso || 0) + ', \'' + (ev.evaluador || '').replace(/\\/g,'\\\\').replace(/'/g,'\\\'') + '\', \'' + (ev.fecha || '').replace(/\\/g,'\\\\').replace(/'/g,'\\\'') + '\')" title="Ver detalle de evaluación"><i class="fa fa-eye"></i></button>'
                + '</div>'
                + '</div>'
                + '</div>'
                + '</div>';
        });
        html += '</div>';
        $('#evalCards').html(html);
    });
}



function cargarHistorial() {
    $.get(DASH_URL, function(r) {
        var evs = (r && r.evaluaciones) ? r.evaluaciones : [];
        var rows = evs.map(function(ev) {
            var pct = ev.progreso || ev.porcentaje_cumplimiento || 0;
            var estadoKey = (ev.estado || 'sin_estado').replace(/ /g, '_');
            var estadoLabel = (ev.estado || 'sin estado').replace(/_/g, ' ');
            var establecimientoHtml = '<div class="font-weight-bold text-dark">' + (ev.establecimiento || '—') + '</div><small class="text-muted">' + (ev.tipologia || '') + '</small>';
            var cumplimientoHtml = '<div class="d-flex align-items-center"><div class="progress mr-2" style="width:90px;height:6px"><div class="progress-bar bg-info" style="width:' + Math.min(Math.max(pct, 0), 100) + '%"></div></div><span class="font-weight-bold text-dark">' + pct + '%</span></div>';
            var actionsHtml = '<div class="d-flex justify-content-center flex-nowrap" style="gap:8px; white-space:nowrap">'
                + '<a href="/riiss/evaluaciones/nueva/' + encodeURIComponent(ev.id_establecimiento || '') + '?evaluacion=' + ev.id + '" class="circle-btn circle-btn-primary btn-sm mr-1" title="Revisar evaluación"><i class="fa fa-play"></i></a>'
                + '<a href="/riiss/evaluaciones/' + ev.id + '" class="circle-btn circle-btn-success btn-sm" title="Ver evaluación"><i class="fa fa-eye"></i></a>'
                + '<button type="button" class="circle-btn circle-btn-warning btn-sm" onclick="verGap(' + ev.id + ', \'' + (ev.id_establecimiento || '') + '\')" title="Gap Analysis"><i class="fa fa-chart-bar"></i></button>'
                + '</div>';

            return {
                establecimiento: establecimientoHtml,
                evaluadores: '<small>' + (ev.evaluador || '—') + '</small>',
                fecha: '<small>' + (ev.fecha || '—') + '</small>',
                estado: '<span class="estado-badge estado-' + estadoKey + '">' + estadoLabel + '</span>',
                cumplimiento: cumplimientoHtml,
                acciones: actionsHtml
            };
        });

        if (historialTable) {
            historialTable.clear();
            historialTable.rows.add(rows);
            historialTable.draw();
        }
    });
}

function abrirModalNuevaAsignacion() {
    editAsignacionId = null;
    $('#modalAsignacionTitulo').html('<i class="fa fa-user-check mr-2"></i>Nueva Asignación');
    $('#selEstablecimiento').prop('disabled', false).val(null).trigger('change');
    $('#selEvaluador').val(null).trigger('change');
    $('#selPeiProfile').val(null).trigger('change');
    $('#selFechaLimite').val('');
    $('#selInstrucciones').val('');
    $('#msgAsignacion').html('');
    $('#modalAsignacionUnificada').modal('show');
}

function abrirModalAsignacion(asigId, estId, estNombre) {
    if (asigId) {
        editAsignacionId = asigId;
        $('#modalAsignacionTitulo').html('<i class="fa fa-edit mr-2"></i>Editar Asignación');
        $('#msgAsignacion').html('<div class="text-center py-3"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
        $('#modalAsignacionUnificada').modal('show');

        $.get('/riiss/asignaciones/' + asigId + '/edit', function(r) {
            if (!r.ok) return;
            $('#msgAsignacion').html('');
            var d = r.data;
            if (d.establecimiento) {
                $('#selEstablecimiento').html(new Option(d.establecimiento.text, d.id_establecimiento, true, true)).trigger('change').prop('disabled', true);
            }
            if (d.evaluador) {
                $('#selEvaluador').html(new Option(d.evaluador.text, d.evaluador_id, true, true)).trigger('change');
            }
            if (d.pei_profile) {
                $('#selPeiProfile').html(new Option(d.pei_profile.text, d.pei_profile_id, true, true)).trigger('change');
            }
            $('#selFechaLimite').val(d.fecha_limite || '');
            $('#selInstrucciones').val(d.instrucciones || '');
        });
    } else {
        editAsignacionId = null;
        $('#modalAsignacionTitulo').html('<i class="fa fa-user-check mr-2"></i>Asignar Evaluador a ' + estNombre);
        $('#selEstablecimiento').html(new Option(estNombre, estId, true, true)).trigger('change').prop('disabled', true);
        $('#selEvaluador').val(null).trigger('change');
        $('#selPeiProfile').val(null).trigger('change');
        $('#selFechaLimite').val('');
        $('#selInstrucciones').val('');
        $('#msgAsignacion').html('');
        $('#modalAsignacionUnificada').modal('show');
    }
}

function guardarAsignacion() {
    var estId = $('#selEstablecimiento').val();
    var evalId = $('#selEvaluador').val();
    if (!estId || !evalId) {
        $('#msgAsignacion').html('<div class="alert alert-warning py-2">Seleccioná establecimiento y evaluador</div>');
        return;
    }

    var isEdit = editAsignacionId !== null;
    var url = isEdit ? '/riiss/asignaciones/' + editAsignacionId : STORE_URL;
    var method = isEdit ? 'PUT' : 'POST';

    $.ajax({
        url: url, method: method, contentType: 'application/json',
        data: JSON.stringify({
            _token:              '{{ csrf_token() }}',
            id_establecimiento:  estId,
            evaluador_id:        evalId,
            pei_profile_id:      $('#selPeiProfile').val() || TARGET_PEI,
            fecha_limite:        $('#selFechaLimite').val() || null,
            instrucciones:       $('#selInstrucciones').val() || null,
        }),
        success: function(r) {
            if (r.ok) {
                $('#modalAsignacionUnificada').modal('hide');
                if (window.tablaUnificada) window.tablaUnificada.draw(false);
                mostrarToast(isEdit ? 'Asignación actualizada ✅' : 'Asignación creada y evaluador notificado ✅', 'success');
            } else {
                $('#msgAsignacion').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al guardar';
            $('#msgAsignacion').html('<div class="alert alert-danger py-2">' + msg + '</div>');
        }
    });
}

function verGap(evaluacionId, idEstablecimiento) {
    $('#modalGapBody').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#modalGap').modal('show');

    var url = evaluacionId
        ? '{{ url('riiss/evaluaciones') }}/' + evaluacionId + '/gap'
        : '{{ url('riiss/evaluaciones/requisitos') }}/' + idEstablecimiento;

    $.get(url, function(r) {
        if (!r.ok) return;
        renderGapModal(r.data);
    });
}

function verDetalleRiiss(idEstablecimiento, evaluacionId, nombre, estado, progreso, evaluador, fecha) {
    $('#modalEstNombre').text(nombre || 'Detalle del establecimiento');
    $('#modalEstBody').html('<div class="text-center py-4"><div class="spinner-border text-danger"></div></div>');
    $('#btnIniciarEval').attr('href', evaluacionId
        ? '/riiss/evaluaciones/nueva/' + encodeURIComponent(idEstablecimiento) + '?evaluacion=' + evaluacionId
        : '/riiss/evaluaciones/nueva/' + encodeURIComponent(idEstablecimiento)
    );
    $('#modalEst').modal('show');

    $.get('/riiss/establecimientos/' + encodeURIComponent(idEstablecimiento), function(r) {
        if (!r || !r.ok) {
            $('#modalEstBody').html('<div class="alert alert-danger">No se pudieron cargar los datos del establecimiento.</div>');
            return;
        }

        var e = r.data;
        var req = r.cartera_requisitos || {};
        var infra = req.infraestructura_requerida || {};

        var infraHtml = Object.entries(infra).map(function(item) {
            var k = item[0];
            var v = item[1];
            var labels = {
                internacion: 'Internación',
                urgencias: 'Urgencias',
                quirofano: 'Quirófano',
                uti: 'UTI',
                laboratorio: 'Laboratorio',
                imagenes: 'Imágenes',
                farmacia: 'Farmacia',
                vacunatorio: 'Vacunatorio'
            };
            return '<span class="badge badge-' + (v ? 'success' : 'light') + ' mr-1 mb-1" style="' + (v ? '' : 'color:#94a3b8;') + '">' + (v ? '✅' : '⬜') + ' ' + (labels[k] || k) + '</span>';
        }).join('');

        var porTipoHtml = Object.entries(req.por_tipo_prestacion || {}).map(function(item) {
            return '<div class="d-flex justify-content-between small py-1 border-bottom">'
                 + '<span class="text-muted">' + item[0] + '</span>'
                 + '<strong>' + item[1] + ' servicios</strong>'
                 + '</div>';
        }).join('');

        $('#modalEstBody').html(
            '<div class="row">'
          + '  <div class="col-md-5">'
          + '    <h6 class="font-weight-bold text-danger mb-3">Datos del establecimiento</h6>'
          + '    <table class="table table-sm table-borderless mb-0">'
          + '      <tr><th class="text-muted small py-1" style="width:40%">ID</th><td class="small">' + (e.id_establecimiento || '—') + '</td></tr>'
          + '      <tr><th class="text-muted small py-1">Tipo</th><td class="small">' + (e.tipo_est_label || '—') + '</td></tr>'
          + '      <tr><th class="text-muted small py-1">Tipología</th><td class="small">' + (e.tipologia_clasificacion || '—') + '</td></tr>'
          + '      <tr><th class="text-muted small py-1">Complejidad</th><td>'
          + '          <span class="badge" style="background:' + (e.complejidad_color || '#6b7280') + ';color:#fff;font-size:.7rem">' + (e.complejidad || '—') + '</span>'
          + '      </td></tr>'
          + '      <tr><th class="text-muted small py-1">Nivel / Grado</th><td class="small">' + (e.nivel_atencion || '—') + ' / ' + (e.grado_complejidad || '—') + '</td></tr>'
          + '      <tr><th class="text-muted small py-1">Departamento</th><td class="small">' + (e.departamento || '—') + '</td></tr>'
          + '      <tr><th class="text-muted small py-1">Microred</th><td class="small">' + (e.microred || '—') + '</td></tr>'
          + '      <tr><th class="text-muted small py-1">Prestador</th><td class="small">' + (e.prestador || '—') + '</td></tr>'
          + '    </table>'
          + '  </div>'
          + '  <div class="col-md-7">'
          + '    <h6 class="font-weight-bold text-danger mb-2">Resumen de evaluación</h6>'
          + '    <div class="mb-3">'
          + '      <div class="small text-muted">Estado</div>'
          + '      <strong>' + (estado || '—').toString().replace(/_/g, ' ') + '</strong>'
          + '    </div>'
          + '    <div class="mb-3">'
          + '      <div class="small text-muted">Evaluador</div>'
          + '      <strong>' + (evaluador || '—') + '</strong>'
          + '    </div>'
          + '    <div class="mb-3">'
          + '      <div class="small text-muted">Fecha</div>'
          + '      <strong>' + (fecha || '—') + '</strong>'
          + '    </div>'
          + '    <div class="mb-3">'
          + '      <div class="small text-muted">Avance</div>'
          + '      <strong>' + (progreso || 0) + '%</strong>'
          + '      <div class="progress mt-2" style="height:8px">'
          + '        <div class="progress-bar bg-info" role="progressbar" style="width:' + Math.min(Math.max(progreso || 0, 0), 100) + '%"></div>'
          + '      </div>'
          + '    </div>'
          + '    <h6 class="font-weight-bold text-muted small mb-1">Debe tener según su nivel</h6>'
          + '    <div class="mb-3">'
          + '      <span class="badge badge-danger mr-1">' + ((req.totales && req.totales.servicios_requeridos) || 0) + ' servicios obligatorios</span>'
          + '      <span class="badge badge-secondary">' + ((req.totales && req.totales.servicios_opcionales) || 0) + ' opcionales</span>'
          + '    </div>'
          + '    <div class="mb-3">' + infraHtml + '</div>'
          + '    <div class="mb-3">' + porTipoHtml + '</div>'
          + '    <div class="alert alert-info mt-3 py-2 small mb-0">'
          + '      <i class="fa fa-info-circle mr-1"></i> Al evaluar, el sistema verificará si el establecimiento cuenta con estos servicios.'
          + '    </div>'
          + '  </div>'
          + '</div>'
        );
    });
}

function abrirEditarEstablecimiento(id) {
    $('#editEstId').val(id);
    $('#msgEditEst').html('<div class="text-center py-3"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#modalEditarEstablecimiento').modal('show');

    $.get('/riiss/establecimientos/' + id, function(r) {
        if (!r || !r.ok || !r.data) {
            $('#msgEditEst').html('<div class="alert alert-danger py-2">No se pudieron cargar los datos</div>');
            return;
        }
        $('#msgEditEst').html('');
        var d = r.data;
        $('#editEstCodigo').val(d.id_establecimiento);
        $('#editEstNombre').val(d.nombre_oficial || '');
        $('#editEstTipologia').val(d.tipologia_clasificacion || d.tipo_est || '');
        $('#editEstDepto').val(d.departamento || d.depto_nc || '');
        $('#editEstObservacion').val(d.observacion || '');
    });
}

function guardarEdicionEstablecimiento() {
    var id = $('#editEstId').val();
    $.ajax({
        url: '/riiss/establecimientos/' + id,
        method: 'PATCH',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: '{{ csrf_token() }}',
            nombre_oficial: $('#editEstNombre').val(),
            tipologia_clasificacion: $('#editEstTipologia').val(),
            departamento: $('#editEstDepto').val(),
            observacion: $('#editEstObservacion').val()
        }),
        success: function(r) {
            if (r && r.ok) {
                $('#modalEditarEstablecimiento').modal('hide');
                if (window.tablaUnificada) window.tablaUnificada.draw(false);
                mostrarToast('Establecimiento actualizado ✅', 'success');
            } else {
                $('#msgEditEst').html('<div class="alert alert-danger py-2">' + (r.message || 'Error al guardar') + '</div>');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al guardar';
            $('#msgEditEst').html('<div class="alert alert-danger py-2">' + msg + '</div>');
        }
    });
}

function addslashes(str) {
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}
</script>
@endsection
