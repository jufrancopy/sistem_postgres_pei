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
}

/* ── Modal RIISS Ultra-Spacious & Executive Tabs ── */
.modal-riiss-xl .modal-dialog {
    max-width: 95vw !important;
    width: 95vw !important;
    margin: 20px auto !important;
}
#modalEditarEstablecimiento .modal-nav-tabs,
.modal-nav-tabs {
    border-bottom: 2px solid #e2e8f0 !important;
    display: flex !important;
    gap: 8px !important;
}
#modalEditarEstablecimiento .modal-nav-tabs .nav-item,
.modal-nav-tabs .nav-item {
    margin-bottom: -2px !important;
}
#modalEditarEstablecimiento .modal-nav-tabs .nav-link,
.modal-nav-tabs .nav-link,
#modalEditarEstablecimiento .nav-tabs .nav-link {
    color: #1e293b !important;
    background-color: #f1f5f9 !important;
    border: 1px solid #cbd5e1 !important;
    border-bottom: 2px solid #cbd5e1 !important;
    font-size: 0.95rem !important;
    font-weight: 700 !important;
    padding: 10px 20px !important;
    border-radius: 8px 8px 0 0 !important;
    transition: all 0.2s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    text-decoration: none !important;
}
#modalEditarEstablecimiento .modal-nav-tabs .nav-link i,
.modal-nav-tabs .nav-link i {
    font-size: 1.05rem !important;
}
#modalEditarEstablecimiento .modal-nav-tabs .nav-link:hover,
.modal-nav-tabs .nav-link:hover {
    color: #0f172a !important;
    background-color: #e2e8f0 !important;
    border-color: #94a3b8 !important;
}
#modalEditarEstablecimiento .modal-nav-tabs .nav-link.active,
.modal-nav-tabs .nav-link.active,
#modalEditarEstablecimiento .nav-tabs .nav-link.active {
    color: #ffffff !important;
    background-color: #4f46e5 !important;
    border-color: #4f46e5 !important;
    border-bottom: 2px solid #4f46e5 !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35) !important;
}
#modalEditarEstablecimiento .modal-nav-tabs .nav-link.active i,
.modal-nav-tabs .nav-link.active i {
    color: #ffffff !important;
}
.esp-card {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #ffffff;
    margin-bottom: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    overflow: hidden;
    transition: transform 0.15s, box-shadow 0.15s;
}
.esp-card:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
}

/* ── Modern Chronic Switch Cards ── */
.chronic-switch-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    user-select: none;
    margin-bottom: 0;
    height: 100%;
}
.chronic-switch-card:hover {
    border-color: #94a3b8;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.chronic-switch-card.active-blue {
    background: #eff6ff !important;
    border-color: #2563eb !important;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15) !important;
}
.chronic-switch-card.active-amber {
    background: #fffbeb !important;
    border-color: #f59e0b !important;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.15) !important;
}
.switch-icon-box {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
/* Modern iOS Switch Toggle */
.ios-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    margin-bottom: 0;
    flex-shrink: 0;
}
.ios-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.ios-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 24px;
}
.ios-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.ios-switch input:checked + .ios-slider {
    background-color: #2563eb;
}
.ios-switch.switch-warning input:checked + .ios-slider {
    background-color: #f59e0b;
}
.ios-switch input:checked + .ios-slider:before {
    transform: translateX(20px);
}
.esp-header-btn {
    background: #f8fafc;
    border: none;
    width: 100%;
    padding: 12px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    text-align: left;
    text-decoration: none !important;
    outline: none !important;
}
.esp-header-btn:hover {
    background: #f1f5f9;
}
.esp-header-btn.cronico {
    background: #fffdf5;
    border-left: 5px solid #f59e0b;
}    cursor: pointer;
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
@keyframes pulseGlowAudit {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}
/* ── Modal RIISS Acta Ultra-Spacious & Responsive ── */
.modal-riiss-acta-xl {
    max-width: 92vw !important;
    width: 92vw !important;
    margin: 20px auto !important;
}
@media (max-width: 768px) {
    .modal-riiss-acta-xl {
        max-width: 98vw !important;
        width: 98vw !important;
        margin: 5px auto !important;
    }
}
/* ── Estilos de Impresión para Acta RIISS ── */
@media print {
    body * {
        visibility: hidden !important;
    }
    #modalActaCierreRiiss,
    #modalActaCierreRiiss * {
        visibility: visible !important;
    }
    #modalActaCierreRiiss {
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        z-index: 999999 !important;
        overflow: visible !important;
    }
    #modalActaCierreRiiss .modal-dialog {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    #modalActaCierreRiiss .modal-content {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        background: transparent !important;
    }
    #modalActaBodyWrapper {
        background: #ffffff !important;
        padding: 0 !important;
    }
    #actaDocumentoImprimible {
        box-shadow: none !important;
        border: none !important;
        padding: 15px !important;
        max-width: 100% !important;
        margin: 0 !important;
    }
    .no-print {
        display: none !important;
    }
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
            @hasanyrole('Administrador|Super Admin|Coordinador RIISS|Coordinador - RIISS|Coordinación RIISS')
            <div class="text-right mt-3 mt-md-0 d-flex align-items-center justify-content-end flex-wrap" style="gap: 8px;">
                <button type="button" class="btn btn-dark btn-sm font-weight-bold shadow-sm" onclick="abrirModalMonitoreoAuditores()" style="border-radius: 8px; background: #0f172a; border-color: #334155;">
                    <i class="fa fa-user-shield text-warning mr-1"></i> Auditores Externos <span class="badge badge-success ml-1" id="badgeAuditoresOnlineHeader">0 online</span>
                </button>
                <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm" onclick="abrirModalGenerarAccesoAuditor('', '🌐 Toda la Red Nacional (Todos los Establecimientos)')" style="border-radius: 8px;">
                    <i class="fab fa-whatsapp mr-1"></i> Compartir Acceso Global
                </button>
                <button class="btn btn-white btn-sm font-weight-bold" onclick="abrirModalNuevaAsignacion()" style="color: #00acc1; border: 1px solid rgba(255,255,255,.35); background: rgba(255,255,255,.95); border-radius: 8px;">
                    <i class="fa fa-plus-circle mr-1 text-info"></i> Nueva Asignación
                </button>
            </div>
            @endhasanyrole
        </div>
    </div>
    
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Centro RIISS</li>
        </ol>
    </nav>
    <div class="card-body pb-2">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            {{-- Pestañas de navegación principal --}}
            <ul class="nav nav-pills riiss-tabs border-0 mb-2 mb-md-0" id="riissMainTabs" role="tablist">
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

            {{-- Selector Territorial Rápido --}}
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span class="mr-2 small font-weight-bold text-muted text-uppercase d-none d-sm-inline" style="font-size:0.75rem; letter-spacing:0.5px;">
                    <i class="fa fa-map-marker-alt text-info mr-1"></i>Dirección / Área:
                </span>
                <select id="filtroGlobalAreaGestion" class="form-control form-control-sm font-weight-bold" style="border-radius: 8px; border: 1.5px solid #00acc1; background: #f0fdfa; color: #0f172a; width: auto; min-width: 200px; height: 36px;">
                    <option value="">🌐 Toda la Red (141)</option>
                    <option value="AREA INTERIOR">🏥 Hospitales Área Interior (106)</option>
                    <option value="AREA CENTRAL">🏙️ Hospitales Área Central (35)</option>
                </select>
            </div>
        </div>
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
                        <input id="fBuscarUnificado" type="text" class="form-control" style="width:100%" placeholder="🔍 Buscar establecimiento o depto...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="fAreaGestion" class="form-control" style="width:100%">
                            <option value="">Área / Dirección (Todas)</option>
                            <option value="AREA INTERIOR">🏥 Área Interior (106)</option>
                            <option value="AREA CENTRAL">🏙️ Área Central (35)</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="fTipologiaUnificada" class="form-control" style="width:100%">
                            <option value="">Todas las Tipologías</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="fProgramaCronico" class="form-control" style="width:100%">
                            <option value="">Patologías Crónicas (Todas)</option>
                            <option value="farmacia">💙 Con Farmacia Crónicos</option>
                            <option value="empadronamiento">🩺 Con Empadronamiento SIH</option>
                            <option value="ambos">🌟 Farmacia + Empadronamiento</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="fConMedicamentos" class="form-control" style="width:100%">
                            <option value="">Medicamentos (Todos)</option>
                            <option value="con">Con Medicamentos</option>
                            <option value="sin">Sin Medicamentos</option>
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
{{-- Modal Detalle y Gestión del Establecimiento (Ultra Spacious) --}}
<div class="modal fade modal-riiss-xl" id="modalEditarEstablecimiento" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: none; overflow: hidden;">
            
            {{-- Header Impactante con degradado oscuro --}}
            <div class="modal-header py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 2px solid #334155;">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                        <i class="fa fa-hospital fa-lg text-info"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center flex-wrap">
                            <h5 class="modal-title text-white font-weight-bold mb-0 mr-2" id="modalEstNombreTitulo">Establecimiento</h5>
                            <span id="modalEstIdBadge" class="badge badge-light text-dark font-weight-bold mr-1" style="font-family: monospace; font-size: 0.85rem;"></span>
                            <span id="modalEstDeptoBadge" class="badge badge-info text-white font-weight-bold mr-1"></span>
                        </div>
                        <small class="text-white-50">Redes Integradas e Integrales de Servicios de Salud (RIISS) — IPS</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    {{-- Botón Compartir a Auditor / WhatsApp --}}
                    <button type="button" class="btn btn-success font-weight-bold px-3 py-2 shadow-sm mr-2" style="border-radius: 8px; font-size: 0.88rem;" onclick="abrirModalGenerarAccesoAuditor($('#editEstId').val(), $('#modalEstNombreTitulo').text())">
                        <i class="fab fa-whatsapp mr-1"></i> Compartir a Auditor
                    </button>
                    {{-- Botón Descargar PDF Destacado para el Técnico --}}
                    <a href="#" id="btnDescargarPdfModal" target="_blank" class="btn btn-danger font-weight-bold px-3 py-2 shadow-sm mr-2" style="border-radius: 8px; font-size: 0.88rem;">
                        <i class="fa fa-file-pdf mr-1"></i> Descargar Listado en PDF
                    </a>
                    <button type="button" class="close text-white p-2" data-dismiss="modal" style="opacity: 0.85; outline: none;">
                        <span style="font-size: 1.6rem;">&times;</span>
                    </button>
                </div>
            </div>

            {{-- Navegación por Tabs --}}
            <div class="bg-white border-bottom px-4 pt-2 pb-0">
                <ul class="nav nav-tabs border-0 modal-nav-tabs" role="tablist">
                    <li class="nav-item mr-2">
                        <a class="nav-link active font-weight-bold" id="tabLinkCartera" data-toggle="tab" href="#tabContenidoCartera" role="tab">
                            <i class="fa fa-pills mr-2 text-primary"></i> Cartera de Medicamentos por Especialidad
                        </a>
                    </li>
                    <li class="nav-item mr-2">
                        <a class="nav-link font-weight-bold" data-toggle="tab" href="#tabContenidoInmueble" role="tab">
                            <i class="fa fa-building mr-2 text-secondary"></i> Información General & Inmueble
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" data-toggle="tab" href="#tabContenidoContratos" role="tab">
                            <i class="fa fa-file-contract mr-2 text-warning"></i> Contratos (Ampliación / Mantenimiento)
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Contenido del Modal --}}
            <div class="modal-body p-4" style="background-color: #f8fafc; max-height: calc(88vh - 140px); overflow-y: auto;">
                <input type="hidden" id="editEstId">

                <div class="tab-content">
                    
                    {{-- ── TAB 1: CARTERA DE MEDICAMENTOS (PROTAGONISTA) ── --}}
                    <div class="tab-pane fade show active" id="tabContenidoCartera" role="tabpanel">
                        
                        {{-- Banner Ejecutivo y Botonera Dual de PDF para el Técnico --}}
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-left: 5px solid #4f46e5 !important;">
                            <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-dark" style="font-size: 1.15rem;">
                                        <i class="fa fa-pills mr-1 text-primary"></i> Catálogo Oficial de Medicamentos y Especialidades
                                    </h6>
                                    <p class="small text-muted mb-0">
                                        Catálogo de la Dirección de Logística de Suministros para este establecimiento según modelo RIISS.
                                    </p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-md-0">
                                    <div class="d-flex mr-3">
                                        <span class="badge badge-success px-3 py-2 mr-2 shadow-sm font-weight-bold" id="badgeTotalUnicos" style="font-size: 0.9rem; border-radius: 20px;">0 Medicamentos Únicos</span>
                                        <span class="badge badge-primary px-3 py-2 shadow-sm font-weight-bold" id="badgeTotalEsp" style="font-size: 0.9rem; border-radius: 20px;">0 Especialidades</span>
                                    </div>
                                    <div class="btn-group">
                                        <a href="#" id="btnDescargarPdfAuditoria" target="_blank" class="btn btn-danger btn-sm font-weight-bold px-3 py-2 shadow-sm">
                                            <i class="fa fa-file-pdf mr-1"></i> PDF Planilla de Auditoría (Sin duplicados)
                                        </a>
                                        <a href="#" id="btnDescargarPdfEspecialidad" target="_blank" class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 bg-white shadow-sm" title="Descargar agrupado por servicio médico">
                                            <i class="fa fa-list-alt mr-1"></i> PDF por Especialidad
                                        </a>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm font-weight-bold px-3 py-2 shadow-sm ml-2" onclick="abrirModalGenerarAccesoAuditor($('#editEstId').val(), $('#modalEstNombreTitulo').text())" title="Compartir acceso seguro a Auditor por WhatsApp">
                                        <i class="fab fa-whatsapp mr-1"></i> Enlace Auditor
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Selector de Modo de Visualización (Sub-Pestañas) --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                            <ul class="nav nav-pills" id="carteraSubtabs" role="tablist" style="gap: 8px;">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-bold btn-sm py-2 px-3 shadow-sm" id="subtabLinkConsolidado" data-toggle="pill" href="#subtabContentConsolidado" role="tab" style="border-radius: 6px;">
                                        <i class="fa fa-boxes mr-1"></i> Vademécum Único / Farmacia (Consolidado)
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold btn-sm py-2 px-3 shadow-sm" id="subtabLinkEspecialidades" data-toggle="pill" href="#subtabContentEspecialidades" role="tab" style="border-radius: 6px;">
                                        <i class="fa fa-stethoscope mr-1"></i> Desglose por Especialidad Médica
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="carteraSubtabsContent">
                            
                            {{-- SUB-TAB A: VADEMÉCUM CONSOLIDADO ÚNICO (TABLA SIN DUPLICADOS) --}}
                            <div class="tab-pane fade show active" id="subtabContentConsolidado" role="tabpanel">
                                <div class="card border shadow-sm" style="border-radius: 8px; background: #ffffff;">
                                    <div class="card-body p-3">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover table-striped w-100" id="tablaMedicamentosConsolidados">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="width: 5%; text-align: center;">#</th>
                                                        <th style="width: 18%;">Código Medicamento</th>
                                                        <th style="width: 45%;">Medicamento / Presentación</th>
                                                        <th style="width: 32%;">Especialidades que lo Utilizan</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SUB-TAB B: DESGLOSE POR ESPECIALIDAD (BLOQUES) --}}
                            <div class="tab-pane fade" id="subtabContentEspecialidades" role="tabpanel">
                                <div class="card border shadow-sm mb-3" style="border-radius: 8px; background: #ffffff;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 mb-2 mb-md-0">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-white border-right-0"><i class="fa fa-search text-muted"></i></span>
                                                    </div>
                                                    <input type="text" id="buscadorCarteraLive" class="form-control border-left-0" placeholder="Buscar en especialidades...">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2 mb-md-0">
                                                <select id="filtroCarteraEspecialidad" class="form-control">
                                                    <option value="">Todas las Especialidades</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 text-md-right">
                                                <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold mr-1" onclick="expandirTodasEspecialidades(true)">
                                                    <i class="fa fa-expand-alt mr-1"></i> Expandir
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" onclick="expandirTodasEspecialidades(false)">
                                                    <i class="fa fa-compress-alt mr-1"></i> Colapsar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="contenedorEspecialidadesBloques">
                                    <!-- Se renderiza por JS -->
                                </div>
                            </div>

                        </div>

                        <div id="sinCarteraAlert" style="display: none;" class="alert alert-light border py-5 text-center text-muted" style="border-radius: 10px;">
                            <i class="fa fa-info-circle fa-3x mb-3 d-block text-secondary"></i>
                            <h5 class="font-weight-bold">Sin Cartera de Medicamentos Registrada</h5>
                            <p class="small text-muted mb-0">Este establecimiento aún no cuenta con medicamentos ni especialidades vinculadas en el sistema RIISS.</p>
                        </div>
                    </div>

                    {{-- ── TAB 2: INFORMACIÓN GENERAL & INMUEBLE ── --}}
                    <div class="tab-pane fade" id="tabContenidoInmueble" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 10px;">
                            <h6 class="font-weight-bold border-bottom pb-2 mb-3 text-primary">
                                <i class="fa fa-info-circle mr-1"></i> Datos Básicos del Establecimiento
                            </h6>
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
                                    <textarea id="editEstObservacion" class="form-control" rows="2"></textarea>
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
                                    <h6 class="font-weight-bold border-bottom pb-2 text-primary" style="font-size: 0.95rem;">
                                        <i class="fa fa-heartbeat mr-1"></i> Programas de Patologías Crónicas (RCA N° 007-043/2022)
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="chronic-switch-card" id="cardHabilitaFarmaciaCronicos" for="editEstHabilitaFarmaciaCronicos">
                                        <div class="d-flex align-items-center mr-3">
                                            <div class="switch-icon-box mr-3" style="background: #dbeafe; color: #2563eb;">
                                                <i class="fas fa-prescription-bottle-alt"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark" style="font-size: 0.92rem; line-height: 1.2;">
                                                    Farmacia para Crónicos
                                                </div>
                                                <div class="text-muted" style="font-size: 0.75rem; line-height: 1.3; margin-top: 2px;">
                                                    Provisión según vademécum de 238 medicamentos.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ios-switch">
                                            <input type="checkbox" id="editEstHabilitaFarmaciaCronicos">
                                            <span class="ios-slider"></span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="chronic-switch-card" id="cardHabilitaEmpadronamientoCronicos" for="editEstHabilitaEmpadronamientoCronicos">
                                        <div class="d-flex align-items-center mr-3">
                                            <div class="switch-icon-box mr-3" style="background: #fef3c7; color: #d97706;">
                                                <i class="fas fa-id-card-alt"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark" style="font-size: 0.92rem; line-height: 1.2;">
                                                    Empadronamiento SIH
                                                </div>
                                                <div class="text-muted" style="font-size: 0.75rem; line-height: 1.3; margin-top: 2px;">
                                                    Registro y certificación médica en el sistema.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ios-switch switch-warning">
                                            <input type="checkbox" id="editEstHabilitaEmpadronamientoCronicos">
                                            <span class="ios-slider"></span>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="col-md-12 mt-3 mb-2">
                                    <h6 class="font-weight-bold border-bottom pb-2 text-primary">
                                        <i class="fa fa-building mr-1"></i> Información del Inmueble
                                    </h6>
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
                                            <label class="small font-weight-bold">Áreas/Locales cubiertas</label>
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
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB 3: CONTRATOS DE AMPLIACIÓN / MANTENIMIENTO ── --}}
                    <div class="tab-pane fade" id="tabContenidoContratos" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 10px;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold mb-0 text-primary">
                                    <i class="fa fa-file-contract mr-1"></i> Contratos de Ampliación y Mantenimiento
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" onclick="agregarFilaContrato()">
                                    <i class="fa fa-plus mr-1"></i> Agregar Contrato
                                </button>
                            </div>
                            <div class="table-responsive">
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

                </div>

                <div id="msgEditEst" class="mt-3"></div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white border-top px-4 py-3 d-flex justify-content-between">
                <small class="text-muted"><i class="fa fa-info-circle mr-1"></i> Información actualizada según la Red RIISS de IPS.</small>
                <div>
                    <button class="btn btn-secondary mr-2" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-dark" onclick="guardarEdicionEstablecimiento()">
                        <i class="fa fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
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

{{-- Modal Generar Enlace Seguro para Auditor Externo / WhatsApp --}}
<div class="modal fade" id="modalGenerarAccesoAuditor" tabindex="-1" role="dialog" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px;">
                        <i class="fab fa-whatsapp fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white">Compartir Acceso a Auditor / Asesor</h5>
                        <small class="text-white-50">Enlace temporal de solo lectura protegido con PIN</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="outline: none;">
                    <span style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-4 bg-light">
                <div id="seccionConfigurarAuditor">
                    <input type="hidden" id="auditorEstId">
                    
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark"><i class="fa fa-sitemap text-primary mr-1"></i> Ámbito de Acceso para el Auditor</label>
                        <select id="auditorAmbitoSelect" class="form-control" onchange="cambiarAmbitoAuditorModal()">
                            <option value="global" selected>🌐 Toda la Red Nacional (Todos los Establecimientos)</option>
                            <option value="especifico">🏛️ Establecimiento Específico</option>
                        </select>
                    </div>

                    <div id="divAuditorEstIndividual" class="p-3 mb-3 bg-white rounded border" style="display: none;">
                        <div class="small font-weight-bold text-muted text-uppercase">Establecimiento Seleccionado</div>
                        <div id="auditorEstNombre" class="font-weight-bold text-dark font-size-1">--</div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark"><i class="fa fa-clock text-success mr-1"></i> Duración de Validez del Enlace</label>
                        <select id="auditorDuracionHoras" class="form-control">
                            <option value="24" selected>24 Horas (Recomendado)</option>
                            <option value="48">48 Horas (2 Días)</option>
                            <option value="72">72 Horas (3 Días)</option>
                            <option value="168">7 Días (1 Semana)</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-dark"><i class="fa fa-user text-muted mr-1"></i> Destinatario / Asesor (Opcional)</label>
                        <input type="text" id="auditorDestinatario" class="form-control" placeholder="Ej: Dr. Fernando Galeano / Auditor Externo">
                    </div>

                    <button type="button" id="btnEjecutarGenerarToken" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm" onclick="ejecutarGeneracionTokenAuditor()">
                        <i class="fa fa-key mr-1"></i> Generar Enlace Seguro & PIN
                    </button>
                </div>

                {{-- Resultado Generado --}}
                <div id="seccionResultadoAuditor" style="display: none;">
                    <div class="alert alert-success d-flex align-items-center mb-3 py-2 px-3">
                        <i class="fa fa-check-circle fa-lg mr-2 text-success"></i>
                        <span class="small font-weight-bold">¡Enlace y PIN generados con éxito!</span>
                    </div>

                    <div class="p-3 mb-3 bg-white rounded border">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fa fa-link text-primary mr-1"></i> Enlace de Acceso Directo</label>
                        <div class="input-group mb-2">
                            <input type="text" id="resUrlPortal" class="form-control form-control-sm bg-light" readonly style="font-size: 0.82rem;">
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-primary" type="button" onclick="copiarTextoAlPortapapeles('resUrlPortal', '¡Enlace copiado!')">
                                    <i class="fa fa-copy mr-1"></i> Copiar
                                </button>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-2 rounded mb-2" style="background: #f0fdf4; border: 1px dashed #86efac;">
                            <div>
                                <small class="text-muted font-weight-bold d-block">PIN DE SEGURIDAD</small>
                                <span id="resPinSeguridad" class="font-weight-bold text-success" style="font-size: 1.4rem; letter-spacing: 0.15em; font-family: monospace;">------</span>
                            </div>
                            <button class="btn btn-sm btn-success px-3" type="button" onclick="copiarPinSeguridad()">
                                <i class="fa fa-copy mr-1"></i> Copiar PIN
                            </button>
                        </div>

                        <div class="small text-muted text-center mt-2">
                            <i class="fa fa-hourglass-half text-warning mr-1"></i> Validez: <span id="resExpiraTexto" class="font-weight-bold text-dark"></span>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="#" id="btnAbrirWhatsAppDirecto" target="_blank" class="btn btn-success font-weight-bold py-2 shadow-sm mb-2 text-center">
                            <i class="fab fa-whatsapp fa-lg mr-2"></i> Enviar Mensaje por WhatsApp
                        </a>

                        <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold mb-2" onclick="copiarMensajeCompletoWhatsApp()">
                            <i class="fa fa-clipboard mr-1"></i> Copiar Mensaje Completo para Pegar
                        </button>

                        <a href="#" id="btnProbarPortal" target="_blank" class="btn btn-link btn-sm text-muted text-center mt-1">
                            <i class="fa fa-external-link-alt mr-1"></i> Abrir Portal de Auditoría para verificar
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Monitoreo de Auditores Externos y Estado En Línea --}}
<div class="modal fade" id="modalMonitoreoAuditores" tabindex="-1" role="dialog" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="max-width: 92vw;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="d-flex align-items-center justify-content-between w-100 pr-3 flex-wrap" style="gap: 10px;">
                    <div class="d-flex align-items-center">
                        <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255,255,255,0.12); border-radius: 10px;">
                            <i class="fa fa-user-shield text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0 text-white">Monitoreo de Auditores & Asesores Externos</h5>
                            <small class="text-white-50">Visualización en tiempo real de accesos, vigencia y estado de conexión</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                        <button type="button" class="btn btn-sm btn-success font-weight-bold" onclick="abrirModalGenerarAccesoAuditor('', '🌐 Toda la Red Nacional (Todos los Establecimientos)')">
                            <i class="fa fa-plus mr-1"></i> Generar Nuevo Acceso
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-light font-weight-bold" onclick="cargarListaTokensAuditores()">
                            <i class="fa fa-sync-alt mr-1"></i> Actualizar Lista
                        </button>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="outline: none;">
                    <span style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3 p-md-4 bg-light">
                <!-- Mini KPIs de Conexión -->
                <div class="row mb-3">
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="card border-0 shadow-sm rounded-lg p-3 h-100 bg-white">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: #dcfce7; color: #16a34a;">
                                    <i class="fa fa-signal"></i>
                                </div>
                                <div>
                                    <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem;">En Línea Ahora</div>
                                    <div class="h4 font-weight-bold text-success mb-0" id="monKpiOnline">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="card border-0 shadow-sm rounded-lg p-3 h-100 bg-white">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: #e0f2fe; color: #0284c7;">
                                    <i class="fa fa-key"></i>
                                </div>
                                <div>
                                    <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem;">Accesos Activos</div>
                                    <div class="h4 font-weight-bold text-primary mb-0" id="monKpiActivos">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-lg p-3 h-100 bg-white">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: #fef3c7; color: #d97706;">
                                    <i class="fa fa-hourglass-half"></i>
                                </div>
                                <div>
                                    <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem;">Expirados / Revocados</div>
                                    <div class="h4 font-weight-bold text-warning mb-0" id="monKpiExpirados">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-lg p-3 h-100 bg-white">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: #f1f5f9; color: #475569;">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div>
                                    <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 0.72rem;">Total Generados</div>
                                    <div class="h4 font-weight-bold text-dark mb-0" id="monKpiTotal">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Tokens y Conexión -->
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden bg-white">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="tblMonitoreoAuditores" class="table table-hover table-striped mb-0 w-100" style="font-size: 0.88rem;">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 22%;">Auditor / Destinatario</th>
                                        <th style="width: 24%;">Ámbito de Acceso</th>
                                        <th style="width: 14%; text-align: center;">Estado Conexión</th>
                                        <th style="width: 14%;">Último Acceso / IP</th>
                                        <th style="width: 12%; text-align: center;">Vigencia / PIN</th>
                                        <th style="width: 14%; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyMonitoreoAuditores">
                                    <tr><td colspan="6" class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white py-2 justify-content-between">
                <div class="small text-muted">
                    <i class="fa fa-circle text-success mr-1" style="font-size: 0.65rem;"></i> Auto-actualización activa cada 10 segundos
                </div>
                <button type="button" class="btn btn-secondary btn-sm font-weight-bold" data-dismiss="modal">Cerrar</button>
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
                <input type="hidden" id="editEstIdSimple" value="">
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
            <div class="modal-footer d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                <div id="modalEstCierreTag"></div>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px">
                    <button type="button" id="btnVerActaModal" class="btn btn-success d-none" onclick="">
                        <i class="fa fa-file-signature mr-1"></i>Ver Acta de Cierre
                    </button>
                    <a href="#" id="btnIniciarEval" class="btn btn-danger">
                        <i class="fa fa-clipboard-check mr-1"></i>Iniciar evaluación ahora
                    </a>
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Acta Oficial de Cierre y Firmas RIISS --}}
<div class="modal fade" id="modalActaCierreRiiss" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-riiss-acta-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white d-flex align-items-center justify-content-between no-print" style="background:linear-gradient(135deg,#0d47a1 0%,#1a237e 100%); padding:16px 24px;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-primary p-2 mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width:44px; height:44px;">
                        <i class="fa fa-file-signature fa-lg" style="color:#0d47a1;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size:1.2rem; letter-spacing:0.3px;">
                            Acta Institucional de Cierre de Relevamiento en Terreno
                        </h5>
                        <small class="text-white-50">
                            Política RIISS · Módulo 1: Cartera de Servicios de Salud y Capacidad Resolutiva (1 de 9)
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <a id="actaBtnPdfHeader" href="#" target="_blank" class="btn btn-danger btn-sm font-weight-bold shadow-sm">
                        <i class="fa fa-file-pdf mr-1"></i>Descargar PDF
                    </a>
                    <a id="actaBtnImprimirHeader" href="#" target="_blank" class="btn btn-warning btn-sm font-weight-bold shadow-sm">
                        <i class="fa fa-print mr-1"></i>Vista Imprimible
                    </a>
                    <button type="button" class="close text-white opacity-90 ml-2" data-dismiss="modal" style="font-size:1.8rem; line-height:1;">
                        <span>&times;</span>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0" id="modalActaBodyWrapper" style="background:#f4f6f9;">
                <div id="modalActaCargando" class="text-center py-5">
                    <div class="spinner-border text-primary mb-2" style="width:3rem; height:3rem;"></div>
                    <div class="text-muted font-weight-bold">Cargando Acta Oficial y Firmas Digitales...</div>
                </div>
                <div id="modalActaContenido" class="d-none p-2 p-md-4">
                    {{-- Hoja Documental A4 Espaciosa y Responsiva --}}
                    <div id="actaDocumentoImprimible" class="bg-white p-3 p-md-4 p-lg-5 mx-auto shadow-sm w-100" style="max-width:1050px; border-radius:12px; border:1px solid #e2e8f0; color:#1e293b; font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
                        
                        {{-- Membrete Oficial --}}
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:15px;">
                                <div class="d-flex align-items-center">
                                    <div id="actaLogoInstitucionalContainer" class="mr-3 d-flex align-items-center justify-content-center" style="max-height:55px; max-width:130px;">
                                        <img id="actaLogoInstitucionalImg" src="" alt="Logo Institucional" style="max-height:55px; max-width:130px; object-fit:contain;" class="d-none">
                                        <div id="actaLogoInstitucionalFallback" class="bg-light p-2 rounded border text-center d-flex align-items-center justify-content-center" style="width:55px; height:55px;">
                                            <i class="fa fa-hospital-alt fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                    <div style="border-left: 2px solid #e2e8f0; padding-left: 12px;">
                                        <div class="font-weight-bold text-uppercase" id="actaInstitucionNombre" style="font-size:1.05rem; color:#0f172a; letter-spacing:0.5px;">INSTITUTO DE PREVISIÓN SOCIAL</div>
                                        <div class="text-primary font-weight-bold small text-uppercase" id="actaDependenciaNombre" style="letter-spacing:0.5px;">DIRECCIÓN DE PLANIFICACIÓN</div>
                                        <div class="text-muted small" style="font-size:0.75rem;">Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN)</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-primary px-3 py-2 text-uppercase font-weight-bold shadow-xs" style="background:#1a237e; font-size:0.75rem; letter-spacing:0.5px;">
                                        POLÍTICA RIISS (1 de 9)
                                    </span>
                                    <div class="text-muted small mt-1 font-weight-bold" id="actaCodigoDoc">ACTA-RIISS-#000</div>
                                </div>
                            </div>
                        </div>

                        {{-- Título Principal --}}
                        <div class="text-center my-3 py-2" style="background:#f8fafc; border-radius:8px; border-top:2px solid #1a237e; border-bottom:2px solid #1a237e;">
                            <h4 class="font-weight-bold text-uppercase mb-1" style="color:#0f172a; letter-spacing:0.5px; font-size:1.25rem;">
                                ACTA DE CONSTANCIA DE VISITA Y RELEVAMIENTO TÉCNICO EN TERRENO
                            </h4>
                            <div class="font-weight-bold text-primary small text-uppercase" style="letter-spacing:0.4px;">
                                POLÍTICA DE REDES INTEGRADAS E INTEGRALES DE SERVICIOS DE SALUD (RIISS)
                            </div>
                            <div class="text-muted font-weight-bold" style="font-size:0.85rem;">
                                MÓDULO N° 1: RELEVAMIENTO DE CARTERA DE SERVICIOS Y CAPACIDAD RESOLUTIVA
                            </div>
                        </div>

                        {{-- I. Datos del Establecimiento --}}
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-uppercase text-dark border-bottom pb-1 mb-2" style="font-size:0.88rem; letter-spacing:0.3px;">
                                I. IDENTIFICACIÓN DEL ESTABLECIMIENTO Y DE LA VISITA
                            </h6>
                            <div class="row" style="font-size: 0.88rem;">
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="text-muted small d-block font-weight-bold text-uppercase">Establecimiento de Salud</span>
                                        <span class="font-weight-bold text-dark" id="actaEstNombre" style="font-size: 1.05rem;">—</span>
                                        <div class="text-muted small mt-2">
                                            <strong>ID:</strong> <span id="actaEstId" class="text-dark">—</span> · 
                                            <strong>Red / Microred:</strong> <span id="actaEstRed" class="text-dark">—</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="text-muted small d-block font-weight-bold text-uppercase">Marco PEI & Equipo Relevador</span>
                                        <div class="font-weight-bold text-primary" id="actaPeiNombre">Plan Estratégico Institucional (PEI 2024–2028)</div>
                                        <div class="text-dark small mt-1">
                                            <strong>Comisión:</strong> <span id="actaEvaluadoresTexto">—</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="text-muted small d-block font-weight-bold text-uppercase">Clasificación Declarada</span>
                                        <div class="d-flex align-items-center flex-wrap my-1" style="gap:6px;">
                                            <span id="actaEstComplejidad">—</span>
                                            <span class="badge badge-secondary px-2 py-1" id="actaEstTipologia">—</span>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <strong>Nivel/Grado:</strong> <span id="actaEstNivelGrado" class="text-dark">—</span> · 
                                            <strong>Dpto:</strong> <span id="actaEstUbicacion" class="text-dark">—</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="text-muted small d-block font-weight-bold text-uppercase">Fecha y Hora de Cierre</span>
                                        <div class="font-weight-bold text-primary mt-1" id="actaFechaCierre" style="font-size:1rem;">—</div>
                                        <small class="text-muted">Jornada técnica presencial formalizada</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- II. Marco Institucional y Recepción --}}
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-uppercase text-dark border-bottom pb-1 mb-2" style="font-size:0.88rem; letter-spacing:0.3px;">
                                II. RECEPCIÓN Y CONSTANCIA DE LA VISITA TÉCNICA
                            </h6>
                            <p class="text-justify mb-2" style="font-size:0.88rem; line-height:1.55; color:#334155;">
                                En el marco de la ejecución del <strong class="text-primary" id="actaDeclaracionPei">Plan Estratégico Institucional (PEI 2024–2028)</strong> y la implementación técnica de la <strong>Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)</strong>, en la fecha y hora indicadas, la autoridad o responsable del establecimiento de salud recibió formalmente a la comisión técnica integrada por los profesionales comisionados: <strong class="text-dark" id="actaDeclaracionEvaluadores">—</strong>, pertenecientes a la <strong>Dirección de Planificación del Instituto de Previsión Social (IPS)</strong>. Ambas partes procedieron de manera conjunta al recorrido de las instalaciones, la verificación in situ de los servicios en funcionamiento y el levantamiento de información para el <strong>Módulo 1: Cartera de Servicios de Salud y Capacidad Resolutiva</strong>.
                            </p>
                        </div>

                        {{-- III. Alcance del Relevamiento --}}
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-uppercase text-dark border-bottom pb-1 mb-2" style="font-size:0.88rem; letter-spacing:0.3px;">
                                III. ALCANCE DEL RELEVAMIENTO DE CAMPO
                            </h6>
                            <div class="row align-items-center my-2">
                                <div class="col-12 col-md-4 text-center mb-2 mb-md-0">
                                    <div class="p-3 border rounded bg-light">
                                        <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size:0.75rem;">Ítems Auditados</small>
                                        <div class="h4 font-weight-bold text-primary mb-0 mt-1" id="actaPreguntasResp">0 de 0</div>
                                        <small class="text-muted">Preguntas técnicas verificadas</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 text-center mb-2 mb-md-0">
                                    <div class="p-3 border rounded bg-light">
                                        <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size:0.75rem;">Cobertura del Relevamiento</small>
                                        <div class="h3 font-weight-bold text-primary mb-0" id="actaProgresoPct">0%</div>
                                        <small class="text-muted">Cuestionario completado</small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 text-center">
                                    <div class="p-3 border rounded bg-light">
                                        <small class="text-muted font-weight-bold text-uppercase d-block" style="font-size:0.75rem;">Estado de la Visita</small>
                                        <div class="h5 font-weight-bold text-success text-uppercase mb-0 mt-1">
                                            RELEVAMIENTO CONCLUIDO
                                        </div>
                                        <small class="text-muted">Jornada presencial en terreno</small>
                                    </div>
                                </div>
                            </div>
                            <div id="actaObservacionesBox" class="p-3 rounded bg-white border mt-2 d-none" style="border-left:4px solid #1a237e !important;">
                                <strong class="text-dark small d-block mb-1"><i class="fa fa-comment-dots text-primary mr-1"></i>Observaciones y Acuerdos Asentados en Terreno:</strong>
                                <span class="small text-muted" id="actaObservacionesTexto">—</span>
                            </div>
                        </div>

                        {{-- IV. Rúbricas y Constancia de Conformidad --}}
                        <div class="mb-3">
                            <h6 class="font-weight-bold text-uppercase text-dark border-bottom pb-1 mb-3" style="font-size:0.88rem; letter-spacing:0.3px;">
                                IV. CONSTANCIA DE CONFORMIDAD Y RÚBRICAS DIGITALES DE LA VISITA
                            </h6>
                            <p class="small text-muted mb-3" style="font-size:0.82rem; line-height:1.4;">
                                Las partes intervinientes ratifican la realización efectiva de la visita técnica presencial y la recepción conforme del equipo comisionado, rubricando al pie en señal de constancia y validación de la jornada de relevamiento de campo:
                            </p>

                            <div class="row">
                                {{-- Firma Receptor Local --}}
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="p-3 border rounded h-100 d-flex flex-column justify-content-between text-center" style="background:#f8fafc; border-color:#cbd5e1 !important; border-radius:10px;">
                                        <div class="border-bottom pb-2 mb-2">
                                            <span class="badge badge-warning text-dark font-weight-bold px-2 py-1 text-uppercase" style="font-size:0.72rem;">
                                                <i class="fa fa-user-tie mr-1"></i>POR EL ESTABLECIMIENTO (RECEPCIÓN Y CONFORMIDAD)
                                            </span>
                                        </div>
                                        <div class="my-2 p-2 bg-white rounded border d-flex align-items-center justify-content-center" style="min-height:100px;">
                                            <img id="actaFirmaRespImg" src="" alt="Firma del Responsable" style="max-height:90px; max-width:100%; object-fit:contain;" class="d-none">
                                            <span id="actaFirmaRespVacia" class="text-muted small font-italic">Sin firma estampada</span>
                                        </div>
                                        <div class="pt-2 border-top text-center" style="font-size:0.85rem;">
                                            <div class="font-weight-bold text-dark" id="actaRespNombre">—</div>
                                            <div class="text-primary font-weight-bold small" id="actaRespCargo">—</div>
                                            <div class="text-muted small" id="actaRespDoc">—</div>
                                            <div class="text-muted small" id="actaRespTel">—</div>
                                            <div class="text-muted font-italic mt-1" style="font-size:0.75rem;" id="actaRespFechaFirma">—</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Firma Evaluador IPS --}}
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="p-3 border rounded h-100 d-flex flex-column justify-content-between text-center" style="background:#f8fafc; border-color:#cbd5e1 !important; border-radius:10px;">
                                        <div class="border-bottom pb-2 mb-2">
                                            <span class="badge badge-info text-white font-weight-bold px-2 py-1 text-uppercase" style="font-size:0.72rem;">
                                                <i class="fa fa-user-check mr-1"></i>EQUIPO EVALUADOR — PLANIFICACIÓN IPS
                                            </span>
                                        </div>
                                        <div class="my-2 p-2 bg-white rounded border d-flex align-items-center justify-content-center" style="min-height:100px;">
                                            <img id="actaFirmaEvalImg" src="" alt="Firma del Evaluador" style="max-height:90px; max-width:100%; object-fit:contain;" class="d-none">
                                            <span id="actaFirmaEvalVacia" class="text-muted small font-italic">Sin firma estampada</span>
                                        </div>
                                        <div class="pt-2 border-top text-center" style="font-size:0.85rem;">
                                            <div class="font-weight-bold text-dark" id="actaEvalNombre">—</div>
                                            <div class="text-info font-weight-bold small" id="actaEvalCargo">Equipo Técnico Relevador — Dirección de Planificación</div>
                                            <div class="text-muted small" id="actaEvalCerradoPor">—</div>
                                            <div class="text-muted font-italic mt-1" style="font-size:0.75rem;" id="actaEvalFechaFirma">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pie del Documento --}}
                        <div class="border-top pt-3 mt-3 text-center text-muted" style="font-size:0.75rem; line-height:1.5;">
                            <div class="font-weight-bold text-secondary" id="actaFooterText">© Instituto de Previsión Social (IPS) — Dirección de Planificación.</div>
                            <div id="actaFooterMetadata" class="small text-muted my-1"></div>
                            <div class="font-italic" style="font-size:0.7rem;">Implementación del Marco Técnico de la Política de Redes Integradas e Integrales de Servicios de Salud (RIISS) — Módulo N° 1.</div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white border-top d-flex justify-content-between align-items-center flex-wrap no-print" style="padding:14px 24px; gap:10px;">
                <div class="small text-muted">
                    <i class="fa fa-shield-alt text-success mr-1"></i>Acta oficial certificada con firma digital en terreno.
                </div>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <a href="#" id="actaBtnIrFormulario" class="btn btn-outline-primary btn-sm font-weight-bold">
                        <i class="fa fa-clipboard-list mr-1"></i>Ver Formulario Completo
                    </a>
                    <a id="actaBtnPdfFooter" href="#" target="_blank" class="btn btn-danger btn-sm font-weight-bold shadow-sm">
                        <i class="fa fa-file-pdf mr-1"></i>Descargar PDF Oficial
                    </a>
                    <a id="actaBtnImprimirFooter" href="#" target="_blank" class="btn btn-warning btn-sm font-weight-bold shadow-sm">
                        <i class="fa fa-print mr-1"></i>Vista Imprimible
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>
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
    // Carga inicial de estado de auditores en línea
    cargarListaTokensAuditores(true);

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

    // Select2 Medicamentos en filtro
    $('#fConMedicamentos').select2({
        placeholder: 'Medicamentos (Todos)', allowClear: true, width: '100%'
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
                d.area_gestion = $('#fAreaGestion').val() || $('#filtroGlobalAreaGestion').val() || '';
                d.tipologia = $('#fTipologiaUnificada').val() || '';
                d.evaluador_id = $('#fEvaluadorUnificado').val() || '';
                d.con_asignacion = $('#fConAsignacion').val() || '';
                d.con_medicamentos = $('#fConMedicamentos').val() || '';
                d.programa_cronico = $('#fProgramaCronico').val() || '';
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
    $('#fTipologiaUnificada, #fConAsignacion, #fConMedicamentos, #fEvaluadorUnificado, #fProgramaCronico').on('change', function() { tablaUnificada.draw(); });

    $('#filtroGlobalAreaGestion').on('change', function() {
        var val = $(this).val();
        $('#fAreaGestion').val(val);
        cargarDashboard();
        cargarHistorial();
        if (window.tablaUnificada) window.tablaUnificada.draw();
    });

    $('#fAreaGestion').on('change', function() {
        var val = $(this).val();
        $('#filtroGlobalAreaGestion').val(val);
        cargarDashboard();
        cargarHistorial();
        if (window.tablaUnificada) window.tablaUnificada.draw();
    });

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
    var area = $('#filtroGlobalAreaGestion').val() || $('#fAreaGestion').val() || '';
    $.get(DASH_URL, { area_gestion: area }, function(r) {
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
    var area = $('#filtroGlobalAreaGestion').val() || $('#fAreaGestion').val() || '';
    $.get(DASH_URL, { area_gestion: area }, function(r) {
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
            var evalNombre = (ev.evaluador && ev.evaluador !== '—') ? ev.evaluador : (ev.responsable_nombre ? ev.responsable_nombre : 'Sin evaluador asignado');
            var badgeEstado = '';
            if (ev.cerrado_con_firmas) {
                badgeEstado = '<span class="badge badge-success px-2 py-1"><i class="fa fa-file-signature mr-1"></i>Firmado & Cerrado</span>';
            } else if (ev.estado === 'completada') {
                badgeEstado = '<span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Completada</span>';
            } else if (ev.estado === 'en_progreso') {
                badgeEstado = '<span class="badge badge-primary px-2 py-1"><i class="fa fa-spinner fa-spin mr-1"></i>En Progreso</span>';
            } else {
                badgeEstado = '<span class="badge badge-warning text-dark px-2 py-1">' + estadoLabel + '</span>';
            }

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
                    ${ev.cerrado_con_firmas || ev.id ? `
                        <button type="button" class="btn btn-circle ${ev.cerrado_con_firmas ? 'btn-success' : 'btn-info'}" onclick="abrirModalActa(${ev.id})" title="${ev.cerrado_con_firmas ? 'Ver Acta Oficial de Cierre y Firmas' : 'Ver Informe de Evaluación'}"><i class="fa ${ev.cerrado_con_firmas ? 'fa-file-signature' : 'fa-file-alt'}"></i></button>
                    ` : ''}
                    <a href="/riiss/evaluaciones/nueva/${ev.id_establecimiento}?evaluacion=${ev.id}" class="btn btn-circle btn-primary" title="${ev.cerrado_con_firmas ? 'Ver Formulario Completo' : 'Continuar / Firmar Evaluación'}"><i class="fa fa-arrow-right"></i></a>
                    <button type="button" class="btn btn-circle btn-info" onclick="verDetalle('${ev.id_establecimiento || ''}', '${nombreEsc}', '${ev.id || ''}')" title="Ver Detalle y Ficha"><i class="fa fa-eye"></i></button>
                </div>
            `;

            var evaluadorHtml = `
                <div>
                    <div class="d-flex align-items-center">
                        <i class="fa fa-user-circle text-info mr-1"></i>
                        <span class="small font-weight-bold text-dark">${evalNombre}</span>
                    </div>
                    ${ev.cerrado_con_firmas && ev.responsable_nombre ? `
                        <div class="mt-1" style="font-size:0.75rem; line-height:1.2;">
                            <span class="badge badge-light border text-success font-weight-normal px-1 py-1" title="Firmado por receptor local">
                                <i class="fa fa-file-signature text-success mr-1"></i>Resp: <strong class="text-dark">${ev.responsable_nombre}</strong>${ev.responsable_cargo ? ' (' + ev.responsable_cargo + ')' : ''}
                            </span>
                        </div>
                    ` : ''}
                </div>
            `;

            return {
                num: idx + 1,
                establecimiento: `<div class="font-weight-bold text-dark" style="font-size:.9rem">${ev.establecimiento}</div><small class="text-muted">${ev.fecha || '—'}</small>`,
                complejidad: `<div><span class="badge" style="background:${ev.complejidad_color || '#64748b'};color:#fff">${ev.complejidad || 'N/A'}</span></div><small class="text-muted">${ev.tipologia || '—'}</small>`,
                evaluador: evaluadorHtml,
                progreso: progresoHtml,
                estado: badgeEstado,
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
    window.currentEditingEstId = id;
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
        $('#editEstHabilitaFarmaciaCronicos').prop('checked', !!d.habilita_farmacia_cronicos);
        $('#editEstHabilitaEmpadronamientoCronicos').prop('checked', !!d.habilita_empadronamiento_cronicos);
        actualizarEstiloSwitchCronicos();
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
        $('#editEstFechaPago').val(d.fecha_pago_alquiler || '');
        
        $('#tablaContratos tbody').empty();
        if (d.inmueble_contratos && d.inmueble_contratos.length > 0) {
            d.inmueble_contratos.forEach(function(c) {
                agregarFilaContrato(c);
            });
        }

        // Configurar Título, Badges y Enlaces PDF
        $('#modalEstNombreTitulo').text(d.nombre_oficial || 'Establecimiento');
        $('#modalEstIdBadge').text('ID: ' + d.id_establecimiento);
        $('#modalEstDeptoBadge').text(d.departamento || 'Sin Depto');
        
        var basePdfUrl = '/riiss/establecimientos/' + encodeURIComponent(d.id_establecimiento) + '/medicamentos-pdf';
        $('#btnDescargarPdfModal').attr('href', basePdfUrl + '?tipo=consolidado');
        $('#btnDescargarPdfAuditoria').attr('href', basePdfUrl + '?tipo=consolidado');
        $('#btnDescargarPdfEspecialidad').attr('href', basePdfUrl + '?tipo=especialidad');

        // Activar pestañas principales y sub-pestaña por defecto
        $('#tabLinkCartera').tab('show');
        $('#subtabLinkConsolidado').tab('show');

        // 1. Renderizar Vademécum Consolidado Único (DataTables)
        var $tblConsolidado = $('#tablaMedicamentosConsolidados');
        if ($.fn.DataTable.isDataTable($tblConsolidado)) {
            $tblConsolidado.DataTable().clear().destroy();
        }
        $tblConsolidado.find('tbody').empty();

        var totalUnicos = (r.medicamentos_consolidados && r.medicamentos_consolidados.length) ? r.medicamentos_consolidados.length : 0;
        var totalEspecialidades = (r.cartera_servicios && r.cartera_servicios.length) ? r.cartera_servicios.length : 0;

        $('#badgeTotalUnicos').text(totalUnicos + ' Medicamentos Únicos');
        $('#badgeTotalEsp').text(totalEspecialidades + ' Especialidades');

        if (totalUnicos > 0 || totalEspecialidades > 0) {
            $('#sinCarteraAlert').hide();
            $('#carteraSubtabs, #carteraSubtabsContent').show();

            var rowsConsolidados = [];
            if (r.medicamentos_consolidados) {
                r.medicamentos_consolidados.forEach(function(m, idx) {
                    var espBadges = '';
                    if (m.especialidades && m.especialidades.length > 0) {
                        m.especialidades.forEach(function(esp) {
                            var isCronico = esp.includes('Crónicos') || esp.includes('Otra');
                            var badgeStyle = isCronico 
                                ? 'background:#fef3c7; color:#92400e; border:1px solid #fde68a;' 
                                : 'background:#e0e7ff; color:#3730a3; border:1px solid #c7d2fe;';
                            espBadges += '<span class="badge mr-1 mb-1 font-weight-normal py-1 px-2" style="' + badgeStyle + '; font-size:0.75rem; border-radius:4px;">' + esp + '</span>';
                        });
                    } else {
                        espBadges = '<span class="text-muted font-italic">General</span>';
                    }

                    rowsConsolidados.push([
                        '<span class="text-muted font-weight-bold">' + (idx + 1) + '</span>',
                        '<span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-family: monospace; font-size: 0.85rem;">' + (m.codigo || 'S/C') + '</span>',
                        '<span class="font-weight-bold text-dark">' + m.nombre + '</span>',
                        espBadges
                    ]);
                });
            }

            var dtConsolidado = $tblConsolidado.DataTable({
                data: rowsConsolidados,
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                language: {
                    search: "Buscar en medicamentos únicos:",
                    lengthMenu: "Mostrar _MENU_ por página",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ medicamentos únicos",
                    infoEmpty: "0 medicamentos",
                    infoFiltered: "(filtrado de _MAX_ totales)",
                    paginate: { first: "«", last: "»", next: "›", previous: "‹" },
                    zeroRecords: "No se encontraron medicamentos para esta búsqueda"
                },
                order: [[2, 'asc']]
            });

            // 2. Renderizar Cartera Organizada por Especialidad (Bloques)
            var $contenedor = $('#contenedorEspecialidadesBloques');
            $contenedor.empty();

            var $filtroEsp = $('#filtroCarteraEspecialidad');
            $filtroEsp.empty().append('<option value="">Todas las Especialidades</option>');
            $('#buscadorCarteraLive').val('');

            if (r.cartera_servicios && r.cartera_servicios.length > 0) {
                r.cartera_servicios.forEach(function(esp, index) {
                    var isCronico = esp.nombre.includes('Crónicos') || esp.nombre.includes('Otra');
                    var numMeds = esp.medicamentos ? esp.medicamentos.length : 0;

                    var collapseId = 'collapseEspModal_' + index;
                    var espIdSafe = 'espCard_' + index;

                    $filtroEsp.append(
                        $('<option>').val(esp.nombre).text(esp.nombre + ' (' + numMeds + ' meds)')
                    );

                    var badgeClass = isCronico ? 'badge-warning' : 'badge-primary';
                    var iconHtml = isCronico 
                        ? '<i class="fa fa-exclamation-triangle text-warning mr-2"></i>' 
                        : '<i class="fa fa-stethoscope mr-2" style="color: #4f46e5;"></i>';

                    var cardHtml = '<div class="esp-card" id="' + espIdSafe + '" data-esp-nombre="' + esp.nombre.toLowerCase() + '">';
                    cardHtml += '<button type="button" class="esp-header-btn ' + (isCronico ? 'cronico' : '') + '" data-toggle="collapse" data-target="#' + collapseId + '" aria-expanded="true">';
                    cardHtml += '  <div class="d-flex align-items-center">';
                    cardHtml += '    ' + iconHtml;
                    cardHtml += '    <span class="font-weight-bold text-dark" style="font-size: 1rem; text-transform: uppercase;">' + esp.nombre + '</span>';
                    cardHtml += '  </div>';
                    cardHtml += '  <div class="d-flex align-items-center">';
                    cardHtml += '    <span class="badge ' + badgeClass + ' px-3 py-2 mr-2" style="border-radius: 20px; font-size: 0.8rem;">' + numMeds + ' ' + (numMeds === 1 ? 'Med' : 'Medicamentos') + '</span>';
                    cardHtml += '    <i class="fa fa-chevron-down text-muted small"></i>';
                    cardHtml += '  </div>';
                    cardHtml += '</button>';

                    cardHtml += '<div id="' + collapseId + '" class="collapse collapse-esp show">';
                    cardHtml += '  <div class="p-0 border-top bg-white">';

                    if (esp.medicamentos && esp.medicamentos.length > 0) {
                        cardHtml += '<div class="table-responsive mb-0">';
                        cardHtml += '  <table class="table table-sm table-hover table-striped mb-0">';
                        cardHtml += '    <thead style="background-color: #f1f5f9;">';
                        cardHtml += '      <tr>';
                        cardHtml += '        <th style="width: 7%; text-align: center; color: #475569;">#</th>';
                        cardHtml += '        <th style="width: 23%; color: #475569;">Código Medicamento</th>';
                        cardHtml += '        <th style="width: 70%; color: #475569;">Descripción del Medicamento / Presentación</th>';
                        cardHtml += '      </tr>';
                        cardHtml += '    </thead>';
                        cardHtml += '    <tbody>';

                        esp.medicamentos.forEach(function(med, mIdx) {
                            var codStr = med.codigo || 'S/C';
                            var nomStr = med.nombre || '';
                            cardHtml += '<tr class="fila-med-item" data-search-text="' + (codStr + ' ' + nomStr).toLowerCase() + '">';
                            cardHtml += '  <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">' + (mIdx + 1) + '</td>';
                            cardHtml += '  <td><span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-family: monospace; font-size: 0.85rem;">' + codStr + '</span></td>';
                            cardHtml += '  <td class="font-weight-500 text-dark">' + nomStr + '</td>';
                            cardHtml += '</tr>';
                        });

                        cardHtml += '    </tbody>';
                        cardHtml += '  </table>';
                        cardHtml += '</div>';
                    } else {
                        cardHtml += '<div class="p-3 text-muted small font-italic text-center">No hay medicamentos asignados para esta especialidad.</div>';
                    }

                    cardHtml += '  </div>';
                    cardHtml += '</div>';
                    cardHtml += '</div>';

                    $contenedor.append(cardHtml);
                });

                // Filtro desplegable por especialidad
                $filtroEsp.off('change').on('change', function() {
                    var selected = $(this).val().toLowerCase();
                    if (!selected) {
                        $('.esp-card').show();
                    } else {
                        $('.esp-card').each(function() {
                            var cardEsp = $(this).attr('data-esp-nombre');
                            if (cardEsp === selected) {
                                $(this).show();
                                $(this).find('.collapse-esp').collapse('show');
                            } else {
                                $(this).hide();
                            }
                        });
                    }
                });

                // Buscador en vivo en bloques
                $('#buscadorCarteraLive').off('input').on('input', function() {
                    var q = $(this).val().toLowerCase().trim();
                    if (!q) {
                        $('.esp-card').show();
                        $('.fila-med-item').show();
                        return;
                    }

                    $('.esp-card').each(function() {
                        var $card = $(this);
                        var matchedRows = 0;

                        $card.find('.fila-med-item').each(function() {
                            var text = $(this).attr('data-search-text') || '';
                            if (text.indexOf(q) !== -1) {
                                $(this).show();
                                matchedRows++;
                            } else {
                                $(this).hide();
                            }
                        });

                        if (matchedRows > 0) {
                            $card.show();
                            $card.find('.collapse-esp').collapse('show');
                        } else {
                            $card.hide();
                        }
                    });
                });
            }

            // Ajustar columnas de la tabla al cambiar de subtab
            $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                if ($.fn.DataTable.isDataTable($tblConsolidado)) {
                    $tblConsolidado.DataTable().columns.adjust().responsive.recalc();
                }
            });

        } else {
            $('#carteraSubtabs, #carteraSubtabsContent').hide();
            $('#sinCarteraAlert').show();
            $('#badgeTotalUnicos').text('0 Medicamentos');
            $('#badgeTotalEsp').text('0 Especialidades');
        }
    });
}

function expandirTodasEspecialidades(expand) {
    if (expand) {
        $('.collapse-esp').collapse('show');
    } else {
        $('.collapse-esp').collapse('hide');
    }
}

function actualizarEstiloSwitchCronicos() {
    if ($('#editEstHabilitaFarmaciaCronicos').is(':checked')) {
        $('#cardHabilitaFarmaciaCronicos').addClass('active-blue');
    } else {
        $('#cardHabilitaFarmaciaCronicos').removeClass('active-blue');
    }

    if ($('#editEstHabilitaEmpadronamientoCronicos').is(':checked')) {
        $('#cardHabilitaEmpadronamientoCronicos').addClass('active-amber');
    } else {
        $('#cardHabilitaEmpadronamientoCronicos').removeClass('active-amber');
    }
}

$(document).on('change', '#editEstHabilitaFarmaciaCronicos, #editEstHabilitaEmpadronamientoCronicos', function() {
    actualizarEstiloSwitchCronicos();
});

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
    var id = $('#editEstId').val() || window.currentEditingEstId || $('#editEstCodigo').val();
    
    if (!id) {
        $('#msgEditEst').html('<div class="alert alert-danger py-2"><i class="fa fa-exclamation-triangle mr-1"></i> Error: No se pudo identificar el ID del establecimiento a guardar.</div>');
        return;
    }
    
    var formData = new FormData();
    formData.append('_method', 'PATCH');
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('id_establecimiento', id);
    formData.append('id', id);
    
    formData.append('nombre_oficial', $('#editEstNombre').val());
    formData.append('tipologia_clasificacion', $('#editEstTipologia').val());
    formData.append('departamento', $('#editEstDepto').val());
    formData.append('observacion', $('#editEstObservacion').val());
    
    formData.append('latitude', $('#editEstLat').val());
    formData.append('longitude', $('#editEstLng').val());
    formData.append('condicion_inmueble', $('#editEstCondicion').val());
    formData.append('habilita_farmacia_cronicos', $('#editEstHabilitaFarmaciaCronicos').is(':checked') ? 1 : 0);
    formData.append('habilita_empadronamiento_cronicos', $('#editEstHabilitaEmpadronamientoCronicos').is(':checked') ? 1 : 0);
    
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

// ── Floating Toast Notification System ──
function mostrarToast(mensaje, tipo) {
    tipo = tipo || 'success';
    var bgColors = {
        success: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
        danger: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
        error: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
        warning: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
        info: 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)'
    };
    var icons = {
        success: 'fa-check-circle',
        danger: 'fa-exclamation-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    var toast = $('<div class="riiss-toast-notification shadow-lg">' +
        '<i class="fa ' + (icons[tipo] || 'fa-bell') + ' fa-lg mr-2"></i>' +
        '<span class="font-weight-bold" style="font-size:0.92rem;">' + mensaje + '</span>' +
        '</div>');

    toast.css({
        position: 'fixed',
        bottom: '28px',
        right: '28px',
        background: bgColors[tipo] || bgColors.success,
        color: '#ffffff',
        padding: '14px 22px',
        borderRadius: '12px',
        zIndex: 999999,
        display: 'flex',
        alignItems: 'center',
        boxShadow: '0 12px 28px rgba(0,0,0,0.3)',
        transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        opacity: 0,
        transform: 'translateY(20px)'
    });

    $('body').append(toast);
    setTimeout(function() {
        toast.css({ opacity: 1, transform: 'translateY(0)' });
    }, 40);

    setTimeout(function() {
        toast.css({ opacity: 0, transform: 'translateY(20px)' });
        setTimeout(function() { toast.remove(); }, 350);
    }, 3800);
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
    $('#modalEstCierreTag').html('');
    $('#btnVerActaModal').addClass('d-none').removeAttr('onclick');

    var activeEvalId = evaluacionId;
    var foundEval = null;
    if (typeof _datosEvaluacionesGlobal !== 'undefined' && _datosEvaluacionesGlobal.length) {
        if (activeEvalId) {
            foundEval = _datosEvaluacionesGlobal.find(function(ev) { return String(ev.id) === String(activeEvalId); });
        }
        if (!foundEval && id) {
            foundEval = _datosEvaluacionesGlobal.find(function(ev) { return ev.id_establecimiento === id; });
            if (foundEval) activeEvalId = foundEval.id;
        }
    }

    if (activeEvalId && activeEvalId !== '') {
        if (foundEval && (foundEval.cerrado_con_firmas || foundEval.responsable_nombre)) {
            $('#btnVerActaModal').removeClass('d-none').attr('onclick', `abrirModalActa(${activeEvalId})`);
            $('#modalEstCierreTag').html('<span class="badge badge-success px-2 py-1"><i class="fa fa-file-signature mr-1"></i>Firmado & Cerrado</span>');
        }
        $('#btnIniciarEval').attr('href', `/riiss/evaluaciones/nueva/${id}?evaluacion=${activeEvalId}`).removeClass('btn-danger').addClass('btn-info').html('<i class="fa fa-clipboard-list mr-1"></i>Ver Formulario');
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

        var cierreBannerHtml = '';
        if (foundEval && (foundEval.cerrado_con_firmas || foundEval.responsable_nombre)) {
            cierreBannerHtml = `
            <div class="card border-0 shadow-sm mb-3" style="background:#f0fdf4; border-left:5px solid #10b981 !important; border-radius:10px;">
                <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                    <div>
                        <h6 class="font-weight-bold text-success mb-1">
                            <i class="fa fa-file-signature mr-2"></i>Acta de Cierre en Terreno Formalmente Firmada
                        </h6>
                        <div class="small text-dark">
                            <strong>Receptor del Centro:</strong> ${foundEval.responsable_nombre || 'Responsable'} ${foundEval.responsable_cargo ? '<span class="text-muted">(' + foundEval.responsable_cargo + ')</span>' : ''}
                        </div>
                        <div class="small text-dark">
                            <strong>Evaluadores IPS:</strong> ${foundEval.evaluador || 'Equipo de Planificación'}
                        </div>
                        <div class="small text-muted mt-1">
                            <i class="fa fa-check-circle text-success mr-1"></i>Relevamiento cerrado con <strong>${foundEval.progreso || 100}%</strong> de avance.
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm" onclick="abrirModalActa(${activeEvalId})">
                            <i class="fa fa-file-signature mr-1"></i>Ver Acta & Dictamen
                        </button>
                    </div>
                </div>
            </div>`;
        }

        $('#modalEstBody').html(`
        ${cierreBannerHtml}
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

// ── Modal de Acta Oficial de Cierre RIISS (Módulo 1) ──────────────────────────
function abrirModalActa(evalId) {
    if (!evalId) return;
    $('#modalActaCargando').removeClass('d-none');
    $('#modalActaContenido').addClass('d-none');
    $('#modalActaCierreRiiss').modal('show');

    $.get('/riiss/evaluaciones/' + evalId + '/acta-datos', function(r) {
        if (!r || !r.ok || !r.data) {
            $('#modalActaCargando').html('<div class="text-danger py-4"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><div>Error al cargar los datos del acta institucional.</div></div>');
            return;
        }

        var d = r.data;
        var est = d.establecimiento || {};
        var vis = d.visita || {};
        var resp = d.responsable || {};
        var eva = d.evaluador || {};
        var inst = d.marco_institucional || {};

        // Branding & Membrete Institucional
        if (inst.logo_institucional) {
            $('#actaLogoInstitucionalImg').attr('src', inst.logo_institucional).removeClass('d-none');
            $('#actaLogoInstitucionalFallback').addClass('d-none');
        } else {
            $('#actaLogoInstitucionalImg').addClass('d-none').attr('src', '');
            $('#actaLogoInstitucionalFallback').removeClass('d-none');
        }
        $('#actaInstitucionNombre').text(inst.institucion || 'INSTITUTO DE PREVISIÓN SOCIAL');
        $('#actaDependenciaNombre').text(inst.dependencia || 'DIRECCIÓN DE PLANIFICACIÓN');
        $('#actaFooterText').text(inst.footer_text || '© Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.');
        $('#actaPeiNombre').text(inst.pei_nombre || 'Plan Estratégico Institucional (PEI 2024–2028)');
        $('#actaEvaluadoresTexto').text(inst.evaluadores_texto || 'Equipo Técnico Relevador — Dirección de Planificación');
        $('#actaDeclaracionPei').text(inst.pei_nombre || 'Plan Estratégico Institucional (PEI 2024–2028)');
        $('#actaDeclaracionEvaluadores').text(inst.evaluadores_texto || 'profesionales comisionados');

        var metaParts = [];
        if (inst.address) metaParts.push(inst.address);
        if (inst.contact_phone) metaParts.push('Tel: ' + inst.contact_phone);
        if (inst.contact_email) metaParts.push(inst.contact_email);
        if (metaParts.length > 0) {
            $('#actaFooterMetadata').text(metaParts.join(' · ')).removeClass('d-none');
        } else {
            $('#actaFooterMetadata').addClass('d-none');
        }

        $('#actaCodigoDoc').text('ACTA-RIISS-#' + d.id);
        $('#actaEstNombre').text(est.nombre || '—');
        $('#actaEstId').text(est.id || '—');
        $('#actaEstTipologia').text(est.tipologia || '—');
        $('#actaEstComplejidad').html('<span class="badge" style="background:' + (est.complejidad_color || '#1a237e') + '; color:#fff;">' + (est.complejidad || '—') + '</span>');
        $('#actaEstNivelGrado').text((est.nivel_atencion ? est.nivel_atencion : '—') + (est.grado_complejidad ? ' / Grado ' + est.grado_complejidad : ''));
        $('#actaEstUbicacion').text((est.departamento || '—') + (est.distrito ? ' / ' + est.distrito : ''));
        $('#actaEstRed').text((est.red || est.microred || 'Red Integrada IPS'));
        $('#actaFechaCierre').text(vis.fecha_hora_cierre || vis.fecha || '—');

        $('#actaProgresoPct').text((vis.progreso || 0) + '%');
        $('#actaPreguntasResp').text((vis.respondidas || 0) + ' de ' + (vis.total_preguntas || 0));

        if (vis.observaciones && vis.observaciones.trim() !== '') {
            $('#actaObservacionesTexto').text(vis.observaciones);
            $('#actaObservacionesBox').removeClass('d-none');
        } else {
            $('#actaObservacionesBox').addClass('d-none');
        }

        // Firmas Digitales
        if (resp.firma) {
            $('#actaFirmaRespImg').attr('src', resp.firma).removeClass('d-none');
            $('#actaFirmaRespVacia').addClass('d-none');
        } else {
            $('#actaFirmaRespImg').addClass('d-none').attr('src', '');
            $('#actaFirmaRespVacia').removeClass('d-none');
        }
        $('#actaRespNombre').text(resp.nombre || 'Responsable del Establecimiento');
        $('#actaRespCargo').text(resp.cargo || 'Receptor Local');
        $('#actaRespDoc').text(resp.documento ? 'C.I.: ' + resp.documento : '');
        $('#actaRespTel').text(resp.telefono ? 'Tel: ' + resp.telefono : '');
        $('#actaRespFechaFirma').text(resp.firmado_at ? 'Firmado el: ' + resp.firmado_at : (vis.fecha_hora_cierre ? 'Firmado el: ' + vis.fecha_hora_cierre : ''));

        if (eva.firma) {
            $('#actaFirmaEvalImg').attr('src', eva.firma).removeClass('d-none');
            $('#actaFirmaEvalVacia').addClass('d-none');
        } else {
            $('#actaFirmaEvalImg').addClass('d-none').attr('src', '');
            $('#actaFirmaEvalVacia').removeClass('d-none');
        }
        $('#actaEvalNombre').text(inst.evaluadores_texto || eva.nombre || 'Equipo Técnico Relevador — Dirección de Planificación');
        $('#actaEvalCargo').text(eva.cargo || 'Comisión Técnica Relevadora — Dirección de Planificación');
        $('#actaEvalCerradoPor').text(eva.cerrado_por ? 'Usuario: ' + eva.cerrado_por : '');
        $('#actaEvalFechaFirma').text(vis.fecha_hora_cierre ? 'Cerrado el: ' + vis.fecha_hora_cierre : '');

        $('#actaBtnIrFormulario').attr('href', '/riiss/evaluaciones/nueva/' + est.id + '?evaluacion=' + d.id);
        $('#actaBtnPdfHeader').attr('href', '/riiss/evaluaciones/' + d.id + '/acta-pdf');
        $('#actaBtnPdfFooter').attr('href', '/riiss/evaluaciones/' + d.id + '/acta-pdf');
        $('#actaBtnImprimirHeader').attr('href', '/riiss/evaluaciones/' + d.id + '/acta-imprimir');
        $('#actaBtnImprimirFooter').attr('href', '/riiss/evaluaciones/' + d.id + '/acta-imprimir');

        $('#modalActaCargando').addClass('d-none');
        $('#modalActaContenido').removeClass('d-none');
    }).fail(function() {
        $('#modalActaCargando').html('<div class="text-danger py-4"><i class="fa fa-exclamation-circle fa-2x mb-2"></i><div>No se pudo obtener el acta institucional.</div></div>');
    });
}

function imprimirActaRiiss() {
    window.print();
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

// ── GESTIÓN DE ACCESOS TEMPORALES PARA AUDITORES / WHATSAPP ───────────────────
var _ultimoMensajeWhatsAppAuditor = '';
var _ultimoPinAuditor = '';

function cambiarAmbitoAuditorModal() {
    var ambito = $('#auditorAmbitoSelect').val();
    if (ambito === 'global') {
        $('#divAuditorEstIndividual').slideUp(150);
        $('#auditorEstId').val('');
    } else {
        $('#divAuditorEstIndividual').slideDown(150);
        var currentEst = $('#editEstId').val();
        if (currentEst) {
            $('#auditorEstId').val(currentEst);
        }
    }
}

function abrirModalGenerarAccesoAuditor(estId, estNombre) {
    if (estId && estId !== '') {
        $('#auditorAmbitoSelect').val('especifico');
        $('#auditorEstId').val(estId);
        $('#auditorEstNombre').text(estNombre || $('#modalEstNombreTitulo').text() || 'Establecimiento RIISS');
        $('#divAuditorEstIndividual').show();
    } else {
        $('#auditorAmbitoSelect').val('global');
        $('#auditorEstId').val('');
        $('#auditorEstNombre').text('🌐 Toda la Red Nacional (Todos los Establecimientos)');
        $('#divAuditorEstIndividual').hide();
    }

    $('#auditorDestinatario').val('');
    $('#auditorDuracionHoras').val('24');

    $('#seccionConfigurarAuditor').show();
    $('#seccionResultadoAuditor').hide();
    $('#btnEjecutarGenerarToken').prop('disabled', false).html('<i class="fa fa-key mr-1"></i> Generar Enlace Seguro & PIN');

    $('#modalGenerarAccesoAuditor').modal('show');
}

function ejecutarGeneracionTokenAuditor() {
    var ambito = $('#auditorAmbitoSelect').val();
    var estId = (ambito === 'especifico') ? $('#auditorEstId').val() : '';
    var duracion = $('#auditorDuracionHoras').val();
    var destinatario = $('#auditorDestinatario').val();

    var $btn = $('#btnEjecutarGenerarToken');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando Enlace Criptográfico...');

    $.ajax({
        url: '{{ route("riiss.auditoria.generar-token") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            establecimiento_id: estId,
            duracion_horas: duracion,
            destinatario: destinatario
        },
        success: function(resp) {
            $btn.prop('disabled', false).html('<i class="fa fa-key mr-1"></i> Generar Enlace Seguro & PIN');
            if (resp.ok) {
                _ultimoMensajeWhatsAppAuditor = resp.mensaje_whatsapp;
                _ultimoPinAuditor = resp.pin;

                $('#resUrlPortal').val(resp.url_portal);
                $('#resPinSeguridad').text(resp.pin);
                $('#resExpiraTexto').text(resp.expira_en + ' hs (' + resp.duracion_horas + ' horas)');
                $('#btnAbrirWhatsAppDirecto').attr('href', resp.url_whatsapp);
                $('#btnProbarPortal').attr('href', resp.url_portal);

                $('#seccionConfigurarAuditor').slideUp(200);
                $('#seccionResultadoAuditor').slideDown(200);
                mostrarToast('¡Enlace temporal de auditoría generado! 🔒', 'success');
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html('<i class="fa fa-key mr-1"></i> Generar Enlace Seguro & PIN');
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al generar enlace';
            mostrarToast(msg, 'danger');
        }
    });
}

function copiarTextoAlPortapapeles(elementId, toastMsg) {
    var copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    mostrarToast(toastMsg || 'Copiado al portapapeles', 'info');
}

function copiarPinSeguridad() {
    if (!_ultimoPinAuditor) return;
    navigator.clipboard.writeText(_ultimoPinAuditor).then(function() {
        mostrarToast('¡PIN (' + _ultimoPinAuditor + ') copiado! 📋', 'info');
    });
}

function copiarMensajeCompletoWhatsApp() {
    if (!_ultimoMensajeWhatsAppAuditor) return;
    navigator.clipboard.writeText(_ultimoMensajeWhatsAppAuditor).then(function() {
        mostrarToast('¡Mensaje completo copiado! Listo para pegar en WhatsApp 💬', 'success');
    });
}

// ── MONITOREO DE AUDITORES Y ESTADO EN LÍNEA ─────────────────────────────────
var _intervalMonitoreoAuditores = null;
var _cacheTokensAuditores = [];

function abrirModalMonitoreoAuditores() {
    $('#modalMonitoreoAuditores').modal('show');
    cargarListaTokensAuditores();

    if (_intervalMonitoreoAuditores) clearInterval(_intervalMonitoreoAuditores);
    _intervalMonitoreoAuditores = setInterval(function() {
        if ($('#modalMonitoreoAuditores').is(':visible')) {
            cargarListaTokensAuditores(true); // Silent refresh
        } else {
            clearInterval(_intervalMonitoreoAuditores);
        }
    }, 10000);
}

var _dtMonitoreoAuditores = null;

function cargarListaTokensAuditores(isSilent) {
    if (!isSilent && !_dtMonitoreoAuditores) {
        $('#tbodyMonitoreoAuditores').html('<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-2x mr-2"></i>Cargando accesos en tiempo real...</td></tr>');
    }

    $.ajax({
        url: '{{ route("riiss.auditoria.tokens.index") }}',
        method: 'GET',
        success: function(resp) {
            if (!resp.ok) return;

            // Actualizar contadores
            $('#monKpiOnline').text(resp.online_count);
            $('#monKpiActivos').text(resp.activos_count);
            $('#monKpiExpirados').text(resp.expirados_count);
            $('#monKpiTotal').text(resp.total_tokens);
            $('#badgeAuditoresOnlineHeader').text(resp.online_count + ' online');
            if (resp.online_count > 0) {
                $('#badgeAuditoresOnlineHeader').addClass('badge-online-live').removeClass('badge-success');
            } else {
                $('#badgeAuditoresOnlineHeader').removeClass('badge-online-live').addClass('badge-success');
            }

            _cacheTokensAuditores = resp.data;

            if ($.fn.DataTable.isDataTable('#tblMonitoreoAuditores')) {
                $('#tblMonitoreoAuditores').DataTable().clear().destroy();
            }

            if (resp.data.length === 0) {
                $('#tbodyMonitoreoAuditores').html('<tr><td colspan="6" class="text-center py-5 text-muted"><i class="fa fa-info-circle fa-2x mb-2 d-block"></i>Aún no se han generado enlaces temporales para auditores.</td></tr>');
                return;
            }

            var html = '';
            resp.data.forEach(function(t) {
                var badgeEstado = '';
                if (t.estado_auditor === 'online') {
                    badgeEstado = '<span class="badge badge-online-live px-2 py-1"><i class="fa fa-circle mr-1" style="font-size: 0.55rem;"></i> EN LÍNEA</span>';
                } else if (t.estado_auditor === 'desconectado') {
                    badgeEstado = '<span class="badge badge-light border text-secondary px-2 py-1"><i class="fa fa-circle text-muted mr-1" style="font-size: 0.55rem;"></i> Desconectado</span>';
                } else if (t.estado_auditor === 'pendiente') {
                    badgeEstado = '<span class="badge badge-warning text-dark px-2 py-1"><i class="fa fa-hourglass-start mr-1" style="font-size: 0.55rem;"></i> Pendiente</span>';
                } else if (t.estado_auditor === 'revocado') {
                    badgeEstado = '<span class="badge badge-danger px-2 py-1"><i class="fa fa-ban mr-1" style="font-size: 0.55rem;"></i> Revocado</span>';
                } else {
                    badgeEstado = '<span class="badge badge-secondary px-2 py-1"><i class="fa fa-clock mr-1" style="font-size: 0.55rem;"></i> Expirado</span>';
                }

                var ambitoBadge = t.es_global 
                    ? '<span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size:0.75rem;"><i class="fa fa-globe mr-1"></i> Red Nacional (Global)</span>' 
                    : '<span class="badge badge-info px-2 py-1 font-weight-bold" style="font-size:0.75rem;"><i class="fa fa-hospital mr-1"></i> ' + (t.establecimiento_id || '') + '</span> <div class="small text-dark font-weight-600 mt-1">' + (t.establecimiento_nombre || '') + '</div>';

                var accionesHtml = '<div class="d-inline-flex align-items-center justify-content-center flex-wrap" style="gap: 5px;">' +
                    '<a href="' + t.url_whatsapp + '" target="_blank" class="circle-btn shadow-sm" style="background:#22c55e; color:#ffffff;" title="Reenviar WhatsApp">' +
                        '<i class="fab fa-whatsapp"></i>' +
                    '</a>' +
                    '<button type="button" class="circle-btn shadow-sm" style="background:#e0f2fe; color:#0284c7;" title="Copiar Enlace Directo" onclick="navigator.clipboard.writeText(\'' + t.url_portal + '\'); mostrarToast(\'Enlace copiado al portapapeles\', \'info\');">' +
                        '<i class="fa fa-link"></i>' +
                    '</button>' +
                    '<a href="' + t.url_portal + '" target="_blank" class="circle-btn shadow-sm" style="background:#f1f5f9; color:#475569;" title="Abrir Portal Auditor">' +
                        '<i class="fa fa-external-link-alt"></i>' +
                    '</a>' +
                    (!t.is_expirado && t.estado_auditor !== 'revocado' ? 
                        '<button type="button" class="circle-btn shadow-sm" style="background:#fef3c7; color:#d97706;" title="Extender +24 Horas" onclick="extenderAccesoAuditor(' + t.id + ', 24)">' +
                            '<i class="fa fa-clock"></i>' +
                        '</button>' +
                        '<button type="button" class="circle-btn shadow-sm" style="background:#fee2e2; color:#dc2626;" title="Revocar Acceso Inmediatamente" onclick="revocarAccesoAuditor(' + t.id + ')">' +
                            '<i class="fa fa-ban"></i>' +
                        '</button>'
                    :
                        '<button type="button" class="circle-btn shadow-sm" style="background:#dcfce7; color:#16a34a;" title="Reactivar +24 Horas" onclick="extenderAccesoAuditor(' + t.id + ', 24)">' +
                            '<i class="fa fa-redo"></i>' +
                        '</button>'
                    ) +
                '</div>';

                html += '<tr>' +
                    '<td>' +
                        '<div class="font-weight-bold text-dark font-size-1">' + (t.destinatario || 'Auditor Externo') + '</div>' +
                        '<div class="small text-muted mt-1"><i class="fa fa-user-edit mr-1"></i>Por: ' + (t.creado_por_nombre || 'Sistema') + ' <span class="d-none d-md-inline">(' + t.created_at_texto + ')</span></div>' +
                    '</td>' +
                    '<td>' + ambitoBadge + '</td>' +
                    '<td class="text-center align-middle">' + badgeEstado + '</td>' +
                    '<td class="align-middle">' +
                        '<div class="font-weight-600 text-dark small"><i class="fa fa-eye text-primary mr-1"></i> ' + t.visitas_count + ' visitas</div>' +
                        '<div class="small text-muted" title="' + (t.ultimo_acceso_fecha || '') + '"><i class="fa fa-history mr-1"></i> ' + t.ultimo_acceso_humano + '</div>' +
                        '<div class="small text-muted" style="font-family: monospace; font-size: 0.72rem;">IP: ' + t.ip_ultimo_acceso + '</div>' +
                    '</td>' +
                    '<td class="text-center align-middle">' +
                        '<div class="font-weight-bold ' + (t.is_expirado ? 'text-danger' : 'text-success') + '">' + t.tiempo_restante_texto + '</div>' +
                        '<div class="d-inline-flex align-items-center mt-1 px-2 py-0 bg-light rounded border" style="font-family: monospace; font-size: 0.8rem;">' +
                            '<span class="mr-2 font-weight-bold text-dark">' + t.pin + '</span>' +
                            '<button type="button" class="btn btn-xs btn-link p-0 text-muted" title="Copiar PIN" onclick="navigator.clipboard.writeText(\'' + t.pin + '\'); mostrarToast(\'PIN copiado: ' + t.pin + '\', \'info\');"><i class="fa fa-copy"></i></button>' +
                        '</div>' +
                    '</td>' +
                    '<td class="text-center align-middle">' + accionesHtml + '</td>' +
                '</tr>';
            });

            $('#tbodyMonitoreoAuditores').html(html);

            _dtMonitoreoAuditores = $('#tblMonitoreoAuditores').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                language: {
                    search: "🔍 Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    zeroRecords: "No se encontraron auditores",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ auditores",
                    infoEmpty: "0 auditores",
                    infoFiltered: "(de _MAX_ totales)",
                    paginate: { first: "«", last: "»", next: "›", previous: "‹" }
                },
                responsive: true
            });
        }
    });
}

function revocarAccesoAuditor(id) {
    if (!confirm('¿Estás seguro de que deseas REVOCAR este acceso inmediatamente? El auditor ya no podrá ingresar.')) {
        return;
    }
    $.ajax({
        url: '/riiss/auditoria/tokens/' + id + '/revocar',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(r) {
            if (r.ok) {
                mostrarToast('Acceso revocado correctamente 🚫', 'warning');
                cargarListaTokensAuditores();
            }
        },
        error: function(xhr) {
            mostrarToast('Error al revocar acceso', 'danger');
        }
    });
}

function extenderAccesoAuditor(id, horas) {
    $.ajax({
        url: '/riiss/auditoria/tokens/' + id + '/extender',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', horas: horas || 24 },
        success: function(r) {
            if (r.ok) {
                mostrarToast('Vigencia extendida +' + (horas || 24) + ' horas ⏳', 'success');
                cargarListaTokensAuditores();
            }
        },
        error: function(xhr) {
            mostrarToast('Error al extender vigencia', 'danger');
        }
    });
}
</script>
@endsection
