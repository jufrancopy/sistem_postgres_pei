@extends('layouts.master')
@section('title', 'Centro de Control RIISS')
@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
<style>
.riiss-tabs .nav-link {
    font-weight: 600;
}
.eval-card-accent { box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06); }
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
    margin-right: 0.5rem;
    flex-shrink: 0;
}
.circle-btn:hover { opacity: .92; transform: scale(1.08); }
.circle-btn-success { background:#d1fae5; color:#065f46; }
.circle-btn-primary { background:#93c5fd; color:#1d4ed8; }
.circle-btn-info    { background:#bfdbfe; color:#0c4a6e; }
.circle-btn-danger  { background:#fecaca; color:#991b1b; }
.circle-btn-warning { background:#fde68a; color:#92400e; }
/* Mobile responsiveness for action buttons */
@media (max-width: 576px) {
    .circle-btn {
        width: 28px !important;
        height: 28px !important;
        font-size: .7rem !important;
        margin-right: 0.25rem;
    }
    .circle-btn:hover { transform: scale(1.06); }
}
@media (max-width: 768px) {
    .circle-btn {
        margin-right: 0.35rem;
    }
}
/* Action buttons container responsiveness */
.dt-body-center {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-wrap: nowrap !important;
    gap: 0.25rem !important;
    min-width: 0;
}
@media (max-width: 576px) {
    .dt-body-center {
        gap: 0.1rem !important;
    }
}
/* DataTables modal responsiveness */
#modalGap .dataTables_wrapper {
    font-size: 0.875rem;
}
#tablaGapServicios {
    width: 100% !important;
    margin-bottom: 1rem;
}
#tablaGapServicios thead th {
    vertical-align: middle;
    padding: 10px 8px !important;
}
#tablaGapServicios tbody td {
    padding: 8px;
    vertical-align: middle;
}
#tablaGapServicios tbody td:nth-child(n+2):nth-child(-n+4) {
    text-align: center;
}
@media (max-width: 768px) {
    #tablaGapServicios {
        font-size: 0.8rem;
    }
    #tablaGapServicios thead th {
        padding: 6px 4px !important;
        font-size: 0.75rem;
    }
    #tablaGapServicios tbody td {
        padding: 6px 4px;
    }
}
.dataTables_filter {
    margin-bottom: 1rem;
}
.dataTables_filter input {
    margin-left: 0.5rem;
    padding: 6px 12px;
    border: 1px solid #cbd5e0;
    border-radius: 4px;
}
/* Modal GAP personalizado - más ancho */
.modal-gap-wide {
    max-width: 1200px !important;
    width: 95% !important;
}
@media (max-width: 768px) {
    .modal-gap-wide {
        max-width: calc(100% - 2rem) !important;
        width: 100% !important;
    }
}
}
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
                    <i class="fa fa-sync-alt mr-2 text-danger"></i>Evaluaciones en Tiempo Real
                </h6>
                <small class="text-muted">Actualiza automáticamente cada 30s</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm w-100" id="tablaMonitoreoEvaluaciones">
                        <thead class="thead-light">
                            <tr>
                                <th width="30">#</th>
                                <th>Establecimiento</th>
                                <th>Tipología / Complejidad</th>
                                <th>Evaluador Asignado</th>
                                <th width="170">Nivel de Avance</th>
                                <th width="110" class="text-center">Estado</th>
                                <th width="120" class="text-center">Última Act.</th>
                                <th width="130" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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

                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Latitud</label>
                        <input type="number" step="any" id="editEstLat" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Longitud</label>
                        <input type="number" step="any" id="editEstLng" class="form-control">
                    </div>
                    
                    <div class="col-md-12 mt-3 mb-2">
                        <h6 class="font-weight-bold border-bottom pb-2 text-primary">Información del Inmueble</h6>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Condición del Inmueble</label>
                        <select id="editEstCondicion" class="form-control" onchange="toggleCondicionInmueble()">
                            <option value="">Seleccione...</option>
                            <option value="CONVENIO">CONVENIO</option>
                            <option value="ALQUILADO">ALQUILADO</option>
                            <option value="PROPIO">PROPIO</option>
                        </select>
                    </div>

                    <!-- Campos PROPIO -->
                    <div id="seccionPropio" class="col-12" style="display:none;">
                        <div class="row bg-light p-3 rounded mb-3 mx-1 border">
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold">Superficie Terreno (m²)</label>
                                <input type="number" step="0.01" id="editEstSupTerreno" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold">Sup. Construida (m²)</label>
                                <input type="number" step="0.01" id="editEstSupConstruida" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold">Plano (PDF)</label>
                                <input type="file" id="editEstPlanoFile" class="form-control-file" accept=".pdf">
                                <small id="planoLink" class="d-block mt-1"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Campos CONVENIO -->
                    <div id="seccionConvenio" class="col-12" style="display:none;">
                        <div class="row bg-light p-3 rounded mb-3 mx-1 border">
                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold">Nro. de Resolución de Convenio</label>
                                <input type="text" id="editEstNroResolucionConv" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Vigencia Desde</label>
                                <input type="date" id="editEstVigConvDesde" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Vigencia Hasta</label>
                                <input type="date" id="editEstVigConvHasta" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold">Descripción del Convenio</label>
                                <textarea id="editEstDescConv" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold">Áreas/Locales cubiertas (Contexto)</label>
                                <textarea id="editEstLocalesConv" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold">Documento del Convenio (PDF)</label>
                                <input type="file" id="editEstConvenioFile" class="form-control-file" accept=".pdf">
                                <small id="convenioLink" class="d-block mt-1"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Campos ALQUILADO -->
                    <div id="seccionAlquilado" class="col-12" style="display:none;">
                        <div class="row bg-light p-3 rounded mb-3 mx-1 border">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Superficie Terreno (m²)</label>
                                <input type="number" step="0.01" id="editEstSupTerrenoAlq" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Sup. Construida (m²)</label>
                                <input type="number" step="0.01" id="editEstSupConstruidaAlq" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Nro. de Llamado</label>
                                <input type="text" id="editEstNroLlamado" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Nro. de Contrato</label>
                                <input type="text" id="editEstNroContrato" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold">Propietario</label>
                                <input type="text" id="editEstPropietario" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold">Vigencia Desde</label>
                                <input type="date" id="editEstVigDesde" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold">Vigencia Hasta</label>
                                <input type="date" id="editEstVigHasta" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold">Canon Mensual</label>
                                <input type="number" step="0.01" id="editEstCanon" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold">Días de Pago de Alquiler</label>
                                <input type="text" id="editEstFechaPago" class="form-control" placeholder="Ej: 1 al 10 de cada mes">
                            </div>
                        </div>
                    </div>

                    <!-- Contratos de Ampliación y Mantenimiento -->
                    <div class="col-md-12 mt-3 mb-2">
                        <h6 class="font-weight-bold border-bottom pb-2 text-primary">Contratos (Ampliación / Mantenimiento)</h6>
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarFilaContrato()">
                            <i class="fa fa-plus"></i> Agregar Contrato
                        </button>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-bordered" id="tablaContratos">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width:20%">Tipo</th>
                                        <th style="width:20%">Nro. Contrato</th>
                                        <th style="width:25%">Descripción</th>
                                        <th style="width:25%">Costo / % Avance</th>
                                        <th style="width:10%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
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
            <div class="modal-header card-header-info" style="background:linear-gradient(135deg,#00acc1,#26c6da)">
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
    <div class="modal-dialog modal-xl modal-gap-wide">
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

{{-- Modal reutilizable: Editar establecimiento (usado también en /riiss) --}}
<div class="modal fade" id="modalEditEst" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-info" style="background:linear-gradient(135deg,#00acc1,#26c6da)">
                <h5 class="modal-title text-white"><i class="fa fa-edit mr-2"></i>Editar establecimiento</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editEstId" value="">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="small font-weight-bold">Nombre oficial</label>
                        <span class="bmd-form-group"><input type="text" id="editNombre" class="form-control"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Complejidad</label>
                        <select id="editComplejidad" class="form-control">
                            <option value="1">No Hospitalaria de Baja Complejidad</option>
                            <option value="2">No Hospitalaria de Mediana Complejidad</option>
                            <option value="3">Hospitalaria de Baja Complejidad</option>
                            <option value="4">Hospitalaria de Mediana Complejidad</option>
                            <option value="5">Hospitalaria de Alta Complejidad</option>
                            <option value="6">Hospitalaria Especializada de Alta Complejidad</option>
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
                        <span class="bmd-form-group"><input type="text" id="editDepartamento" class="form-control"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Microred</label>
                        <span class="bmd-form-group"><input type="text" id="editMicrored" class="form-control"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Prestador</label>
                        <span class="bmd-form-group"><input type="text" id="editPrestador" class="form-control"></span>
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
                        <span class="bmd-form-group"><textarea id="editObservacion" class="form-control" rows="2"></textarea></span>
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

{{-- Modal detalle (misma que en establecimientos/index) --}}
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

    if (historialTable && historialTable.buttons) {
        historialTable.buttons().container().addClass('mb-3').prependTo('#tablaHistorial_wrapper');
    }

    // Cargar Monitoreo e Historial
    cargarDashboard();
    cargarHistorial();

    setInterval(cargarDashboard, 30000);
});

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

var _dtMonitoreo = null;
var _datosEvaluacionesGlobal = [];

function cargarDashboard() {
    $.get(DASH_URL, function(r) {
        if (!r || !r.ok) return;
        var res = r.resumen || {};
        var evs = r.evaluaciones || [];
        _datosEvaluacionesGlobal = evs;

        $('#kpi-total').text(res.total || 0);
        $('#kpi-progreso').text(res.en_progreso || 0);
        $('#kpi-completadas').text(res.completadas || 0);
        $('#kpi-promedio').text((res.promedio_pct || 0) + '%');

        var rowsData = evs.map(function(ev, idx) {
            var pct = ev.progreso || ev.porcentaje_cumplimiento || 0;
            var estadoLabel = (ev.estado || '').replace(/_/g,' ');
            var evalNombre = ev.evaluador && ev.evaluador !== '—' ? ev.evaluador : 'Sin evaluador asignado';
            var badgeClass = '';
            if (ev.estado === 'pendiente' || ev.estado === 'borrador') badgeClass = 'badge-warning text-dark';
            else if (ev.estado === 'en_progreso') badgeClass = 'badge-primary';
            else badgeClass = 'badge-success';

            var nombreEsc = addslashes(ev.establecimiento || '');
            
            var progresoHtml = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="font-weight-bold text-primary small">${pct}%</span>
                    <small class="text-muted" style="font-size:.7rem">${ev.respondidas||0}/${ev.total_preguntas||0}</small>
                </div>
                <div class="progress" style="height:6px;border-radius:10px">
                    <div class="progress-bar bg-info" style="width:${Math.min(Math.max(pct,0),100)}%"></div>
                </div>
            `;

            var accionesHtml = `
                <div class="d-flex justify-content-center align-items-center" style="gap:4px">
                    <a href="/riiss/evaluaciones/nueva/${ev.id_establecimiento}?evaluacion=${ev.id}" class="btn btn-circle btn-primary" title="Continuar Evaluación"><i class="fa fa-arrow-right"></i></a>
                    <button type="button" class="btn btn-circle btn-info" onclick="verDetalle('${ev.id_establecimiento || ''}', '${nombreEsc}', '${ev.id || ''}')" title="Ver Detalle"><i class="fa fa-eye"></i></button>
                </div>
            `;

            return {
                num: idx + 1,
                establecimiento: `<div class="font-weight-bold text-dark" style="font-size:.9rem">${ev.establecimiento}</div><small class="text-muted">${ev.fecha || '—'}</small>`,
                complejidad: `<div><span class="badge" style="background:${ev.complejidad_color || '#64748b'};color:#fff">${ev.complejidad || 'N/A'}</span></div><small class="text-muted">${ev.tipologia || '—'}</small>`,
                evaluador: `<div class="d-flex align-items-center"><i class="fa fa-user-circle text-info mr-1"></i><span class="small font-weight-bold text-dark">${evalNombre}</span></div>`,
                progreso: progresoHtml,
                estado: `<span class="badge ${badgeClass} text-capitalize px-2 py-1">${estadoLabel}</span>`,
                updated_at: `<span class="small text-muted">${ev.updated_at || '—'}</span>`,
                acciones: accionesHtml
            };
        });

        if ($.fn.DataTable.isDataTable('#tablaMonitoreoEvaluaciones')) {
            _dtMonitoreo.clear().rows.add(rowsData).draw(false);
        } else {
            _dtMonitoreo = $('#tablaMonitoreoEvaluaciones').DataTable({
                data: rowsData,
                language: {
                    search: 'Buscar en monitoreo:',
                    zeroRecords: 'No se encontraron evaluaciones registradas.',
                    emptyTable: 'No hay evaluaciones activas en tiempo real.'
                },
                columns: [
                    { data: 'num', className: 'text-center' },
                    { data: 'establecimiento' },
                    { data: 'complejidad' },
                    { data: 'evaluador' },
                    { data: 'progreso' },
                    { data: 'estado', className: 'text-center' },
                    { data: 'updated_at', className: 'text-center' },
                    { data: 'acciones', className: 'text-center', orderable: false }
                ],
                dom: '<"d-flex align-items-center justify-content-between mb-2"f>t<"d-flex align-items-center justify-content-between mt-2"ip>',
                pageLength: 10
            });
        }
    });
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

function renderGapModal(data) {
    if (!data) {
        $('#modalGapBody').html('<div class="alert alert-info">Sin datos disponibles</div>');
        return;
    }

    var html = '';
    
    // Sección Cartera de Servicios
    var cartera = data.cartera || {};
    var carteraResumen = cartera.resumen || {};
    var carteraPorGrupo = cartera.por_grupo || [];
    var carteraAcciones = cartera.acciones_criticas || [];

    // Encabezado con color
    html += '<div style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 20px; margin: -15px -15px 20px -15px; border-radius: 8px 8px 0 0;">';
    html += '<div class="row">';
    html += '<div class="col-md-6"><h6 style="font-weight: bold; margin: 0; font-size: 1rem;"><i class="fa fa-hospital mr-2"></i>COMPARACIÓN: EVALUACIÓN VS CARTERA DE SERVICIOS</h6></div>';
    html += '</div>';
    html += '</div>';

    // Tabla principal con información del establecimiento y resultado
    html += '<table class="table table-bordered table-sm mb-4" style="margin-top: 0;">';
    html += '<tbody>';
    
    if (data.establecimiento) {
        var est = data.establecimiento;
        html += '<tr style="background: #f8f9fa;">';
        html += '<td class="font-weight-bold text-dark" width="30%">Establecimiento</td>';
        html += '<td>' + (est.nombre_oficial || est.nombre || '—') + '</td>';
        html += '</tr>';
    }
    
    if (data.declara_ser) {
        var decl = data.declara_ser;
        html += '<tr>';
        html += '<td class="font-weight-bold text-dark" width="30%">Tipo / Nivel</td>';
        html += '<td>' + (decl.tipologia || '—') + ' | Nivel: ' + (decl.nivel || '—') + ' | Grado: ' + (decl.grado || '—') + '</td>';
        html += '</tr>';
        
        html += '<tr style="background: #f8f9fa;">';
        html += '<td class="font-weight-bold text-dark">Complejidad</td>';
        html += '<td><span class="badge badge-info">' + (decl.complejidad || '—') + '</span></td>';
        html += '</tr>';
    }
    
    html += '<tr>';
    html += '<td class="font-weight-bold text-dark">% Cumplimiento</td>';
    html += '<td><div style="font-size: 1.3rem; font-weight: bold; color: #8b5cf6;">' + (cartera.porcentaje || 0) + '%</div></td>';
    html += '</tr>';
    
    html += '<tr style="background: #f8f9fa;">';
    html += '<td class="font-weight-bold text-dark">Clasificación</td>';
    html += '<td>';
    html += '<span class="badge ' + 
            (cartera.clasificacion === 'CUMPLE' ? 'badge-success' : cartera.clasificacion === 'PARCIALMENTE_CUMPLE' ? 'badge-warning' : 'badge-danger') + 
            '" style="font-size: 1rem; padding: 8px 12px;">' + (cartera.clasificacion || '—').replace(/_/g, ' ') + '</span>';
    html += '</td>';
    html += '</tr>';
    
    html += '</tbody>';
    html += '</table>';
    
    // Indicadores principales
    html += '<div class="row text-center mb-4" style="padding: 20px; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-radius: 8px; border: 2px solid #0284c7;">';
    html += '<div class="col-3">';
    html += '<div style="font-size: 2rem; font-weight: bold; color: #10b981;">' + (carteraResumen.cumple || 0) + '</div>';
    html += '<small class="text-dark font-weight-bold">Cumplen</small>';
    html += '</div>';
    html += '<div class="col-3">';
    html += '<div style="font-size: 2rem; font-weight: bold; color: #ef4444;">' + (carteraResumen.no_cumple || 0) + '</div>';
    html += '<small class="text-dark font-weight-bold">No cumplen</small>';
    html += '</div>';
    html += '<div class="col-3">';
    html += '<div style="font-size: 2rem; font-weight: bold; color: #f59e0b;">' + (carteraResumen.no_verificable || 0) + '</div>';
    html += '<small class="text-dark font-weight-bold">No verificables</small>';
    html += '</div>';
    html += '<div class="col-3">';
    html += '<div style="font-size: 2rem; font-weight: bold; color: #6b7280;">' + (carteraResumen.total || 0) + '</div>';
    html += '<small class="text-dark font-weight-bold">Total</small>';
    html += '</div>';
    html += '</div>';
    
    // Sección: Por Grupo/Tipo de Prestación en tabla
    if (carteraPorGrupo && carteraPorGrupo.length > 0) {
        html += '<h6 style="font-weight: bold; color: #0891b2; margin-bottom: 15px; margin-top: 20px; font-size: 1rem; border-bottom: 3px solid #06b6d4; padding-bottom: 8px;"><i class="fa fa-list-ul mr-2"></i>DESGLOSE POR TIPO DE SERVICIO</h6>';
        
        html += '<div style="overflow-x: auto;">';
        html += '<table class="table table-hover table-sm table-bordered" id="tablaGapServicios">';
        html += '<thead style="background: #0891b2; color: white;">';
        html += '<tr>';
        html += '<th>Tipo de Servicio</th>';
        html += '<th class="text-center">Cumplen</th>';
        html += '<th class="text-center">No Cumplen</th>';
        html += '<th class="text-center">No Verificables</th>';
        html += '<th class="text-center">Total</th>';
        html += '<th>Detalles</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        
        carteraPorGrupo.forEach(function(grupo, idx) {
            var rowColor = idx % 2 === 0 ? '#ffffff' : '#f8f9fa';
            
            html += '<tr style="background: ' + rowColor + ';">';
            html += '<td class="font-weight-bold text-dark">' + (grupo.grupo || 'General') + '</td>';
            html += '<td class="text-center"><span class="badge badge-success" style="font-size: 0.9rem;">' + (grupo.cumple || 0) + '</span></td>';
            html += '<td class="text-center"><span class="badge badge-danger" style="font-size: 0.9rem;">' + (grupo.no_cumple || 0) + '</span></td>';
            html += '<td class="text-center"><span class="badge badge-warning" style="font-size: 0.9rem;">' + (grupo.no_verificable || 0) + '</span></td>';
            html += '<td class="text-center"><span class="badge badge-secondary" style="font-size: 0.9rem;">' + (grupo.total || 0) + '</span></td>';
            
            html += '<td>';
            if (grupo.items && grupo.items.length > 0) {
                var cumpleItems = grupo.items.filter(i => i.estado === 'cumple');
                var noCumpleItems = grupo.items.filter(i => i.estado === 'no_cumple');
                
                if (cumpleItems.length > 0) {
                    html += '<small class="d-block mb-2"><strong style="color: #10b981;">✓ Cumplen:</strong> ' + cumpleItems.map(i => i.servicio).join(', ') + '</small>';
                }
                if (noCumpleItems.length > 0) {
                    html += '<small class="d-block" style="color: #dc2626;"><strong>✗ No cumplen:</strong> ' + noCumpleItems.map(i => i.servicio).join(', ') + '</small>';
                }
            }
            html += '</td>';
            html += '</tr>';
        });
        
        html += '</tbody>';
        html += '</table>';
        html += '</div>';
    }
    
    // Sección: Acciones críticas
    if (carteraAcciones && carteraAcciones.length > 0) {
        html += '<div class="alert alert-danger mt-4" style="border-left: 5px solid #dc3545; background: #fee2e2; border-radius: 8px;">';
        html += '<h6 class="font-weight-bold mb-3" style="color: #991b1b; font-size: 1rem;"><i class="fa fa-exclamation-triangle mr-2"></i>⚠️ ACCIONES CRÍTICAS RECOMENDADAS</h6>';
        
        html += '<table class="table table-sm table-borderless" style="margin-bottom: 0; color: #7f1d1d;">';
        carteraAcciones.forEach(function(accion, idx) {
            html += '<tr>';
            html += '<td width="30" class="text-center"><span class="badge badge-danger">●</span></td>';
            html += '<td>';
            html += '<strong>' + (accion.servicio || '—') + ':</strong> ' + (accion.accion || 'Revisar y mejorar');
            html += '</td>';
            html += '</tr>';
        });
        html += '</table>';
        html += '</div>';
    }
    
    html += '<div class="alert alert-info mt-3" style="background: #dbeafe; border-left: 4px solid #0284c7; color: #0c4a6e; border-radius: 6px;">';
    html += '<i class="fa fa-info-circle mr-2"></i><small><strong>Nota:</strong> Este análisis compara lo que el establecimiento debe tener según su nivel/grado versus lo que efectivamente cuenta según la evaluación realizada.</small>';
    html += '</div>';
    
    $('#modalGapBody').html(html);
    
    // Inicializar DataTables para la tabla de servicios
    setTimeout(function() {
        if ($.fn.dataTable.isDataTable('#tablaGapServicios')) {
            $('#tablaGapServicios').DataTable().destroy();
        }
        
        $('#tablaGapServicios').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Buscar:",
                info: "Mostrando _START_ a _END_ de _TOTAL_ servicios",
                infoEmpty: "0 servicios",
                infoFiltered: "(filtrado de _MAX_ totales)",
                paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" },
                zeroRecords: "No se encontraron servicios"
            },
            columnDefs: [
                { targets: [1, 2, 3, 4], orderable: true },
                { targets: 5, orderable: false }
            ],
            order: [[0, 'asc']]
        });
    }, 100);
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
        
        // Cargar nuevos campos
        $('#editEstLat').val(d.latitude || '');
        $('#editEstLng').val(d.longitude || '');
        $('#editEstCondicion').val(d.condicion_inmueble || '');
        toggleCondicionInmueble();
        
        $('#editEstSupTerreno').val(d.superficie_terreno || '');
        $('#editEstSupConstruida').val(d.superficie_construida || '');
        if (d.plano_url) {
            $('#planoLink').html('<a href="/storage/' + d.plano_url + '" target="_blank">Ver plano actual</a>');
        } else {
            $('#planoLink').html('');
        }
        $('#editEstPlanoFile').val('');
        
        // CONVENIO
        $('#editEstNroResolucionConv').val(d.nro_resolucion_convenio || '');
        $('#editEstVigConvDesde').val(d.vigencia_convenio_desde || '');
        $('#editEstVigConvHasta').val(d.vigencia_convenio_hasta || '');
        $('#editEstDescConv').val(d.descripcion_convenio || '');
        $('#editEstLocalesConv').val(d.locales_convenio || '');
        if (d.archivo_convenio_url) {
            $('#convenioLink').html('<a href="/storage/' + d.archivo_convenio_url + '" target="_blank">Ver documento convenio</a>');
        } else {
            $('#convenioLink').html('');
        }
        $('#editEstConvenioFile').val('');
        
        // ALQUILADO
        $('#editEstSupTerrenoAlq').val(d.superficie_terreno || '');
        $('#editEstSupConstruidaAlq').val(d.superficie_construida || '');
        $('#editEstNroLlamado').val(d.nro_llamado || '');
        $('#editEstNroContrato').val(d.nro_contrato_alquiler || '');
        $('#editEstPropietario').val(d.propietario || '');
        $('#editEstVigDesde').val(d.vigencia_desde || '');
        $('#editEstVigHasta').val(d.vigencia_hasta || '');
        $('#editEstCanon').val(d.canon_mensual || '');
        $('#editEstFechaPago').val(d.fecha_pago_alquiler || '');
        
        $('#tablaContratos tbody').empty();
        if (d.inmueble_contratos && d.inmueble_contratos.length > 0) {
            d.inmueble_contratos.forEach(function(c) {
                agregarFilaContrato(c);
            });
        }
    });
}

function toggleCondicionInmueble() {
    var val = $('#editEstCondicion').val();
    $('#seccionPropio').hide();
    $('#seccionAlquilado').hide();
    $('#seccionConvenio').hide();
    if (val === 'PROPIO') $('#seccionPropio').show();
    if (val === 'ALQUILADO') $('#seccionAlquilado').show();
    if (val === 'CONVENIO') $('#seccionConvenio').show();
}

function agregarFilaContrato(c = {}) {
    var tr = $('<tr>').addClass('fila-contrato');
    
    var selTipo = $('<select>').addClass('form-control form-control-sm c-tipo').append(
        $('<option>').val('AMPLIACION').text('AMPLIACIÓN'),
        $('<option>').val('MANTENIMIENTO').text('MANTENIMIENTO')
    ).val(c.tipo_contrato || 'AMPLIACION');
    
    var inpNro = $('<input>').attr('type', 'text').addClass('form-control form-control-sm c-nro').val(c.nro_contrato || '');
    var inpDesc = $('<input>').attr('type', 'text').addClass('form-control form-control-sm c-desc').val(c.descripcion || '');
    
    var inpCosto = $('<input>').attr('type', 'number').attr('step', '0.01').attr('placeholder', 'Costo $').addClass('form-control form-control-sm c-costo mb-1').val(c.costo_total || '');
    var inpAvance = $('<input>').attr('type', 'number').attr('placeholder', '% Avance').addClass('form-control form-control-sm c-avance').val(c.porcentaje_avance || '');
    var divCosto = $('<div>').append(inpCosto, inpAvance);

    var btnDel = $('<button>').attr('type', 'button').addClass('btn btn-sm btn-danger').html('<i class="fa fa-trash"></i>').click(function() {
        $(this).closest('tr').remove();
    });

    tr.append(
        $('<td>').append(selTipo),
        $('<td>').append(inpNro),
        $('<td>').append(inpDesc),
        $('<td>').append(divCosto),
        $('<td>').addClass('text-center').append(btnDel)
    );
    $('#tablaContratos tbody').append(tr);
}

function guardarEdicionEstablecimiento() {
    var id = $('#editEstId').val();
    
    var formData = new FormData();
    formData.append('_method', 'PATCH');
    formData.append('_token', '{{ csrf_token() }}');
    
    formData.append('nombre_oficial', $('#editEstNombre').val());
    formData.append('tipologia_clasificacion', $('#editEstTipologia').val());
    formData.append('departamento', $('#editEstDepto').val());
    formData.append('observacion', $('#editEstObservacion').val());
    
    formData.append('latitude', $('#editEstLat').val());
    formData.append('longitude', $('#editEstLng').val());
    formData.append('condicion_inmueble', $('#editEstCondicion').val());
    
    if ($('#editEstCondicion').val() === 'PROPIO') {
        formData.append('superficie_terreno', $('#editEstSupTerreno').val());
        formData.append('superficie_construida', $('#editEstSupConstruida').val());
        var file = $('#editEstPlanoFile')[0].files[0];
        if (file) formData.append('plano_file', file);
    } else if ($('#editEstCondicion').val() === 'ALQUILADO') {
        formData.append('superficie_terreno', $('#editEstSupTerrenoAlq').val());
        formData.append('superficie_construida', $('#editEstSupConstruidaAlq').val());
        formData.append('nro_llamado', $('#editEstNroLlamado').val());
        formData.append('nro_contrato_alquiler', $('#editEstNroContrato').val());
        formData.append('propietario', $('#editEstPropietario').val());
        formData.append('vigencia_desde', $('#editEstVigDesde').val());
        formData.append('vigencia_hasta', $('#editEstVigHasta').val());
        formData.append('canon_mensual', $('#editEstCanon').val());
        formData.append('fecha_pago_alquiler', $('#editEstFechaPago').val());
    } else if ($('#editEstCondicion').val() === 'CONVENIO') {
        formData.append('nro_resolucion_convenio', $('#editEstNroResolucionConv').val());
        formData.append('vigencia_convenio_desde', $('#editEstVigConvDesde').val());
        formData.append('vigencia_convenio_hasta', $('#editEstVigConvHasta').val());
        formData.append('descripcion_convenio', $('#editEstDescConv').val());
        formData.append('locales_convenio', $('#editEstLocalesConv').val());
        var cfile = $('#editEstConvenioFile')[0].files[0];
        if (cfile) formData.append('archivo_convenio_file', cfile);
    }
    
    // Contratos
    var idx = 0;
    $('#tablaContratos tbody tr').each(function() {
        formData.append('contratos[' + idx + '][tipo_contrato]', $(this).find('.c-tipo').val());
        formData.append('contratos[' + idx + '][nro_contrato]', $(this).find('.c-nro').val());
        formData.append('contratos[' + idx + '][descripcion]', $(this).find('.c-desc').val());
        formData.append('contratos[' + idx + '][costo_total]', $(this).find('.c-costo').val() || '');
        formData.append('contratos[' + idx + '][porcentaje_avance]', $(this).find('.c-avance').val() || '');
        idx++;
    });

    $.ajax({
        url: '/riiss/establecimientos/' + id,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
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

// Delegador para abrir el modal desde las tarjetas (global)
function abrirModalEditEstFromCard(id) {
    abrirEditarEstablecimiento(id);
}

function addslashes(str) {
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

// ── Modal detalle (versión riiss) ─────────────────────────────────────────────
function verDetalle(id, nombre, evaluacionId) {
    $('#modalEstNombre').text(nombre || '—');
    $('#modalEstBody').html('<div class="text-center py-4"><div class="spinner-border text-danger"></div></div>');

    var activeEvalId = evaluacionId;
    if ((!activeEvalId || activeEvalId === '') && typeof _datosEvaluacionesGlobal !== 'undefined') {
        var found = _datosEvaluacionesGlobal.find(function(ev) { return ev.id_establecimiento === id; });
        if (found) activeEvalId = found.id;
    }

    if (activeEvalId && activeEvalId !== '') {
        $('#btnIniciarEval').attr('href', `/riiss/evaluaciones/nueva/${id}?evaluacion=${activeEvalId}`).removeClass('btn-danger').addClass('btn-info').html('<i class="fa fa-eye mr-1"></i>Ver evaluación');
    } else {
        $('#btnIniciarEval').attr('href', `/riiss/evaluaciones/nueva/${id}`).removeClass('btn-info').addClass('btn-danger').html('<i class="fa fa-clipboard-check mr-1"></i>Iniciar evaluación ahora');
    }
    $('#modalEst').modal('show');

    $.get(`/riiss/establecimientos/${id}`, function(r) {
        if (!r.ok) return;
        const e   = r.data;
        const req = r.cartera_requisitos || {};
        const infra = (req.infraestructura_requerida) ? req.infraestructura_requerida : {};

        const infraHtml = Object.entries(infra).map(([k, v]) => {
            const labels = { internacion:'Internación', urgencias:'Urgencias', quirofano:'Quirófano',
                             uti:'UTI', laboratorio:'Laboratorio', imagenes:'Imágenes',
                             farmacia:'Farmacia', vacunatorio:'Vacunatorio' };
            return `<span class="badge badge-${v?'success':'light'} mr-1 mb-1" style="${!v?'color:#94a3b8':''}">${v?'✅':'⬜'} ${labels[k]||k}</span>`;
        }).join('');

        const porTipoHtml = (req.por_tipo_prestacion ? Object.entries(req.por_tipo_prestacion).map(([k,v]) =>
            `<div class="d-flex justify-content-between small py-1 border-bottom"><span class="text-muted">${k}</span><strong>${v} servicios</strong></div>`
        ).join('') : '');

        $('#modalEstBody').html(`
        <div class="row">
            <div class="col-md-5">
                <h6 class="font-weight-bold text-danger mb-3">Datos del establecimiento</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted small py-1" style="width:40%">ID</th><td class="small">${e.id_establecimiento}</td></tr>
                    <tr><th class="text-muted small py-1">Tipo</th><td class="small">${e.tipo_est_label}</td></tr>
                    <tr><th class="text-muted small py-1">Tipología</th><td class="small">${e.tipologia_clasificacion}</td></tr>
                    <tr><th class="text-muted small py-1">Complejidad</th><td><span class="badge" style="background:${e.complejidad_color};color:#fff;font-size:.7rem">${e.complejidad}</span></td></tr>
                    <tr><th class="text-muted small py-1">Nivel / Grado</th><td class="small">${e.nivel_atencion??'—'} / ${e.grado_complejidad??'—'}</td></tr>
                    <tr><th class="text-muted small py-1">Departamento</th><td class="small">${e.departamento}</td></tr>
                    <tr><th class="text-muted small py-1">Microred</th><td class="small">${e.microred??'—'}</td></tr>
                    <tr><th class="text-muted small py-1">Prestador</th><td class="small">${e.prestador}</td></tr>
                </table>
            </div>
            <div class="col-md-7">
                <h6 class="font-weight-bold text-danger mb-2">Debe tener según su nivel</h6>
                <div class="mb-3">
                    <span class="badge badge-danger mr-1">${(req.totales||{}).servicios_requeridos || 0} servicios obligatorios</span>
                    <span class="badge badge-secondary">${(req.totales||{}).servicios_opcionales || 0} opcionales</span>
                </div>
                <div class="mb-3">${infraHtml}</div>
                <h6 class="font-weight-bold text-muted small mb-1">Por tipo de prestación</h6>
                ${porTipoHtml}
                <div class="alert alert-info mt-3 py-2 small mb-0"><i class="fa fa-info-circle mr-1"></i>Al evaluar, el sistema verificará si el establecimiento cuenta con estos servicios.</div>
            </div>
        </div>`);
    });
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
</script>
@endsection
