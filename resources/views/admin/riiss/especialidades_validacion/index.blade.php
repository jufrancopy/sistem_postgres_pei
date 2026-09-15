@extends('layouts.master')

@section('title', 'Validación de Especialidades Médicas')
@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
<style>
.circle-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    border: 1px solid rgba(15, 23, 42, 0.08);
    transition: all 0.2s;
}
.circle-btn:hover {
    transform: scale(1.08);
}
.kpi-stat-card {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: transform 0.2s ease;
}
.kpi-stat-card:hover {
    transform: translateY(-2px);
}

/* Material Floating Modal Card */
.modal-card-material {
    border-radius: 12px !important;
    border: none !important;
    overflow: visible !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
    background: #ffffff !important;
}
.modal-card-material .card-header-info {
    margin: -25px 15px 0 15px !important;
    padding: 16px 22px !important;
    border-radius: 8px !important;
    background: linear-gradient(60deg, #26c6da, #00acc1) !important;
    box-shadow: 0 10px 25px -5px rgba(0, 188, 212, 0.5), 0 4px 10px 0 rgba(0, 0, 0, 0.12) !important;
}

/* Modal Dialog Width Overrides */
#modalClasificacionTerritorial .modal-dialog {
    max-width: 1200px !important;
    width: 92vw !important;
    margin: 1.75rem auto !important;
}
#modalGenerarEnlace .modal-dialog {
    max-width: 850px !important;
    width: 90vw !important;
    margin: 1.75rem auto !important;
}
@media (min-width: 992px) {
    .modal-xl {
        max-width: 1200px !important;
    }
}

/* Select2 Material Theme */
.select2-container {
    width: 100% !important;
}
.select2-container--default .select2-selection--single {
    height: 42px !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 8px 14px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #ffffff !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default .select2-selection--single:focus {
    border-color: #00acc1 !important;
    box-shadow: 0 0 0 3px rgba(0, 172, 193, 0.15) !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1e293b !important;
    font-weight: 600 !important;
    font-size: 13.5px !important;
    padding-left: 0 !important;
    line-height: normal !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
}
.select2-dropdown {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    box-shadow: 0 12px 28px rgba(0,0,0,0.18) !important;
    z-index: 99999 !important;
}
.select2-results__option {
    padding: 9px 14px !important;
    font-size: 13px !important;
    color: #334155 !important;
}
.select2-results__option--highlighted[aria-selected] {
    background-color: #00acc1 !important;
    color: #ffffff !important;
}
.modal-clasif-body .table .select2-container--default .select2-selection--single {
    height: 34px !important;
    padding: 3px 10px !important;
    border-radius: 6px !important;
    font-size: 11.5px !important;
}
.modal-clasif-body .table .select2-container--default .select2-selection--single .select2-selection__rendered {
    font-size: 11.5px !important;
    font-weight: 600 !important;
    line-height: 26px !important;
}
.modal-clasif-body .table .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 32px !important;
}
.badge-kpi-area {
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    user-select: none;
}
.badge-kpi-area:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
    filter: brightness(0.97);
}
.badge-kpi-area:active {
    transform: translateY(0);
}
.form-section-title {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 700;
    color: #00acc1;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 6px;
}
.form-card-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

/* DataTables Styling Overrides */
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #00acc1 !important;
    color: #ffffff !important;
    border-color: #00acc1 !important;
    border-radius: 6px !important;
}
.dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    outline: none !important;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #00acc1 !important;
    box-shadow: 0 0 0 3px rgba(0, 172, 193, 0.15) !important;
}
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex align-items-center justify-content-between flex-wrap">
        <div>
            <h4 class="card-title font-weight-bold mb-0">Módulo de Validación de Especialidades Médicas</h4>
            <small class="text-white-50">Gobernanza RIISS — Doble Validación Remota y Homologación de Vademécum</small>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('riiss.validaciones.flujograma-documentacion') }}" target="_blank" class="btn btn-sm btn-light font-weight-bold shadow-xs text-dark" style="border-radius: 6px;">
                <i class="fa fa-project-diagram text-info mr-1"></i> Ver Flujograma & Documentación Oficial
            </a>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Validación de Especialidades Médicas (Hospitales Área Interior y Central)</li>
        </ol>
    </nav>

    <div class="container-fluid px-3">

        {{-- Alertas --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Tarjetas KPI de Resumen --}}
        <div class="row mb-3">
            {{-- 1. Área Interior --}}
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #00bcd4 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Área Interior</div>
                            <div class="h3 font-weight-bold text-dark mb-0 mt-1">{{ $totalInterior }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ count($deptosInterior) }} Dptos (Hospitales)</small>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-info" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-hospital fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Área Central / Capital --}}
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #2196f3 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Área Central</div>
                            <div class="h3 font-weight-bold text-primary mb-0 mt-1">{{ $totalCentral }}</div>
                            <small class="text-muted" style="font-size: 11px;">Central y Asunción</small>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-primary" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-city fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Especialidades Validadas (Activas) --}}
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Validadas Activas</div>
                            <div class="h3 font-weight-bold text-success mb-0 mt-1">{{ $totalRegistrosValidados }}</div>
                            <small class="text-success font-weight-bold" style="font-size: 11px;"><i class="fa fa-check mr-1"></i>En Centros Auditados</small>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-success" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Especialidades Inactivas --}}
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #ef4444 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Inactivadas / Bajas</div>
                            <div class="h3 font-weight-bold text-danger mb-0 mt-1">{{ $totalRegistrosInactivos }}</div>
                            <small class="text-danger font-weight-bold" style="font-size: 11px;"><i class="fa fa-times mr-1"></i>No Operativas</small>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-danger" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-times-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Catálogo de Especialidades --}}
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #0d9488 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Especialidades</div>
                            <div class="h3 font-weight-bold text-dark mb-0 mt-1">{{ $totalEspecialidades }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ number_format($totalVinculosVademecum, 0, ',', '.') }} Vínculos Med.</small>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-info" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; color: #0d9488 !important;">
                            <i class="fa fa-stethoscope fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. Vademécum Oficial IPS --}}
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">Vademécum IPS</div>
                            <div class="h3 font-weight-bold mb-0 mt-1" style="color: #8b5cf6;">{{ $totalMedicamentosVademecum }}</div>
                            <small class="text-muted" style="font-size: 11px;">Medicamentos 2026</small>
                        </div>
                        <div class="bg-light p-2 rounded-circle" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; color: #8b5cf6;">
                            <i class="fa fa-pills fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barra de Navegación por Pestañas (Pills) --}}
        <ul class="nav nav-pills mb-4 p-2 bg-white shadow-sm rounded-lg" id="riissMainTabs" role="tablist" style="border: 1px solid #e2e8f0; gap: 8px;">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="tab-enlaces-tab" data-toggle="pill" href="#tab-enlaces" role="tab" style="border-radius: 8px; padding: 10px 16px;">
                    <i class="fa fa-map-marked-alt mr-2 text-info"></i> 1. Validación Territorial (Hospitales)
                    <span class="badge badge-info ml-2 px-2 py-1">{{ count($sesiones) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-farmaceutica-tab" data-toggle="pill" href="#tab-farmaceutica" role="tab" style="border-radius: 8px; padding: 10px 16px;">
                    <i class="fa fa-prescription-bottle-alt mr-2 text-teal" style="color: #0d9488;"></i> 2. Regulación Farmacéutica (Vademécum)
                    <span class="badge text-white ml-2 px-2 py-1" style="background-color: #0d9488;">{{ count($sesionesFarmaceuticas) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-especialidades-tab" data-toggle="pill" href="#tab-especialidades" role="tab" style="border-radius: 8px; padding: 10px 16px;">
                    <i class="fa fa-stethoscope mr-2 text-primary"></i> 3. Especialidades Médicas
                    <span class="badge badge-primary ml-2 px-2 py-1">{{ $totalEspecialidades }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-vademecum-tab" data-toggle="pill" href="#tab-vademecum" role="tab" style="border-radius: 8px; padding: 10px 16px;">
                    <i class="fa fa-pills mr-2 text-purple" style="color: #8b5cf6;"></i> 4. Catálogo Vademécum IPS
                    <span class="badge badge-purple text-white ml-2 px-2 py-1" style="background-color: #8b5cf6;">{{ $totalMedicamentosVademecum }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-matriz-tab" data-toggle="pill" href="#tab-matriz" role="tab" style="border-radius: 8px; padding: 10px 16px; background: #ecfdf5; border: 1px solid #10b981; color: #065f46;">
                    <i class="fa fa-table mr-2 text-success"></i> 5. Matriz Consolidada de Control Cruzado
                    <span class="badge badge-success ml-2 px-2 py-1">RIISS Oficial</span>
                </a>
            </li>
        </ul>

        {{-- Contenido de las Pestañas --}}
        <div class="tab-content" id="riissMainTabsContent">

            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            {{-- PESTAÑA 1: ENLACES DE VALIDACIÓN TERRITORIAL --}}
            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade show active" id="tab-enlaces" role="tabpanel" aria-labelledby="tab-enlaces-tab">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white py-3">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <label class="font-weight-bold mr-2 mb-0 text-dark small">
                                <i class="fa fa-filter text-info mr-1"></i> Filtrar Área:
                            </label>
                            <select id="filtroAreaTabla" class="form-control form-control-sm font-weight-bold mr-2" style="min-width: 190px; border-radius: 6px;">
                                <option value="">📋 Todas las Áreas</option>
                                <option value="AREA INTERIOR">🏥 Área Interior ({{ $totalInterior }})</option>
                                <option value="AREA CENTRAL">🏙️ Área Central ({{ $totalCentral }})</option>
                            </select>
                        </div>

                        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                            <button type="button" class="btn btn-outline-danger font-weight-bold" onclick="confirmarReinicioValidaciones()" style="border-radius: 6px;" title="Reiniciar todas las validaciones de prueba a 0">
                                <i class="fa fa-sync-alt mr-1"></i> Reiniciar Todo a 0
                            </button>
                            <button type="button" class="btn btn-outline-info font-weight-bold" data-toggle="modal" data-target="#modalClasificacionTerritorial" style="border-radius: 6px;">
                                <i class="fa fa-map-marked-alt mr-1"></i> Clasificación Territorial ({{ $totalEstablecimientos }})
                            </button>
                            <button type="button" class="btn btn-success font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalGenerarEnlace" style="border-radius: 6px; background: linear-gradient(60deg, #66bb6a, #43a047); border: none;">
                                <i class="fa fa-plus mr-1"></i> Nuevo Enlace de Validador
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="tablaSesionesValidador" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 45px;" class="text-center">#</th>
                                        <th style="width: 140px;" class="text-center">Código Acceso</th>
                                        <th>Analista Responsable</th>
                                        <th style="width: 210px;">Dirección / Alcance Asignado</th>
                                        <th style="width: 110px;" class="text-center">Registros Guardados</th>
                                        <th style="width: 100px;" class="text-center">Estado</th>
                                        <th style="width: 210px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesiones as $index => $s)
                                        <tr data-area="{{ $s->area_gestion }}">
                                            <td class="text-center font-weight-bold text-muted">{{ $index + 1 }}</td>
                                            <td class="text-center">
                                                <div class="badge badge-info px-2 py-1 font-weight-bold" style="font-size:12px; letter-spacing:0.5px;">
                                                    <i class="fa fa-key mr-1"></i> {{ $s->codigo_acceso }}
                                                </div>
                                                <div class="text-muted small mt-1" style="font-size:10px;">
                                                    {{ $s->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size:13.5px;">
                                                    {{ $s->analista_nombre }}
                                                </div>
                                                <div class="text-muted small" style="font-size:11px;">
                                                    {{ $s->analista_cargo ?: 'Analista Técnico' }}
                                                    @if($s->analista_documento) · C.I.: {{ $s->analista_documento }} @endif
                                                    @if($s->analista_telefono) · Tel: {{ $s->analista_telefono }} @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    @if($s->area_gestion === 'AREA CENTRAL')
                                                        <span class="badge badge-primary px-2 py-1"><i class="fa fa-city mr-1"></i> Área Central</span>
                                                    @else
                                                        <span class="badge badge-info px-2 py-1"><i class="fa fa-hospital mr-1"></i> Área Interior</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted mt-1" style="font-size:11px;">
                                                    <i class="fa fa-globe-americas mr-1"></i> {{ $s->departamento_filtro ?: 'Todos los Dptos. del Área' }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="font-weight-bold text-info" style="font-size:15px;">
                                                    {{ $s->registros_count }}
                                                </span>
                                                <div class="text-muted small" style="font-size:10px;">especialidades</div>
                                            </td>
                                            <td class="text-center">
                                                @if($s->estado === 'finalizado')
                                                    <span class="badge badge-success px-2 py-1 font-weight-bold">
                                                        <i class="fa fa-check mr-1"></i> Finalizado
                                                    </span>
                                                @else
                                                    <span class="badge badge-light border border-success text-success px-2 py-1 font-weight-bold">
                                                        <i class="fa fa-spinner fa-pulse mr-1"></i> Activo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center" style="gap: 5px;">
                                                    {{-- Compartir WhatsApp y Código --}}
                                                    <button type="button" 
                                                            class="circle-btn btn btn-success text-white btn-share-validador shadow-xs" 
                                                            data-url="{{ $s->url_acceso }}"
                                                            data-codigo="{{ $s->codigo_acceso }}"
                                                            data-analista="{{ $s->analista_nombre }}"
                                                            data-cargo="{{ $s->analista_cargo ?: 'Analista Técnico' }}"
                                                            data-telefono="{{ $s->analista_telefono ?? '' }}"
                                                            data-area="{{ $s->area_gestion }}"
                                                            data-depto="{{ $s->departamento_filtro ?? 'Todos los Dptos. del Área' }}"
                                                            title="Compartir Acceso y Código por WhatsApp">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </button>

                                                    {{-- Copiar enlace --}}
                                                    <button type="button" 
                                                            class="circle-btn btn btn-outline-info btn-copy" 
                                                            data-url="{{ $s->url_acceso }}"
                                                            title="Copiar Enlace Directo">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                    
                                                    {{-- Abrir portal --}}
                                                    <a href="{{ $s->url_acceso }}" target="_blank" class="circle-btn btn btn-info text-white" title="Abrir Portal de Validación">
                                                        <i class="fa fa-external-link-alt"></i>
                                                    </a>

                                                    {{-- Imprimir acta --}}
                                                    <a href="{{ route('riiss.portal-validador.acta-imprimir', $s->token) }}" target="_blank" class="circle-btn btn btn-outline-secondary" title="Imprimir / Ver Acta Consolidada">
                                                        <i class="fa fa-print"></i>
                                                    </a>

                                                    {{-- Reiniciar a 0 este enlace individual --}}
                                                    <form action="{{ route('riiss.validaciones.reiniciar-enlace', $s->id) }}" method="POST" class="d-inline form-reiniciar-enlace">
                                                        @csrf
                                                        <button type="button" class="circle-btn btn btn-outline-warning btn-reset-enlace" 
                                                                data-analista="{{ $s->analista_nombre }}" 
                                                                data-codigo="{{ $s->codigo_acceso }}" 
                                                                data-count="{{ $s->registros_count }}"
                                                                title="Reiniciar a 0 este Enlace">
                                                            <i class="fa fa-sync-alt text-warning"></i>
                                                        </button>
                                                    </form>

                                                    {{-- Eliminar --}}
                                                    <form action="{{ route('riiss.validaciones.eliminar-enlace', $s->id) }}" method="POST" class="d-inline form-eliminar-enlace">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="circle-btn btn btn-danger text-white btn-delete-enlace" 
                                                                data-analista="{{ $s->analista_nombre }}" 
                                                                data-codigo="{{ $s->codigo_acceso }}" 
                                                                title="Eliminar Enlace">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            {{-- PESTAÑA 2: VALIDACIÓN DE LA UNIDAD DE REGULACIÓN FARMACÉUTICA --}}
            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-farmaceutica" role="tabpanel" aria-labelledby="tab-farmaceutica-tab">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white py-3">
                        <div class="mb-2 mb-md-0">
                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fa fa-prescription-bottle-alt mr-2 text-teal" style="color: #0d9488;"></i> Unidad de Regulación Farmacéutica — Validación Vademécum
                            </h5>
                            <small class="text-muted">
                                Accesos remotos oficiales para químicos farmacéuticos encargados de auditar y dictaminar la pertinencia de medicamentos por especialidad médica.
                            </small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                            <button type="button" class="btn btn-sm font-weight-bold text-white shadow-xs" data-toggle="modal" data-target="#modalGenerarEnlaceFarmaceutico" style="background: linear-gradient(60deg, #0d9488, #0f766e); border: none; border-radius: 6px;">
                                <i class="fa fa-plus-circle mr-1"></i> + Nuevo Enlace de Regulación Farmacéutica
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        {{-- Mini KPI Farmacéutico --}}
                        <div class="row mb-3">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="p-3 bg-light border rounded">
                                    <div class="small text-muted font-weight-bold text-uppercase">Especialidades Dictaminadas</div>
                                    <div class="h4 font-weight-bold text-success mb-0 mt-1">{{ $totalEspecialidadesFarmValidadas }} / {{ $totalEspecialidades }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="p-3 bg-light border rounded">
                                    <div class="small text-muted font-weight-bold text-uppercase">Medicamentos Validados (Aprobados)</div>
                                    <div class="h4 font-weight-bold mb-0 mt-1" style="color: #0d9488;">{{ $totalMedicamentosFarmValidados }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="p-3 bg-light border rounded">
                                    <div class="small text-muted font-weight-bold text-uppercase">Medicamentos Invalidados</div>
                                    <div class="h4 font-weight-bold text-danger mb-0 mt-1">{{ $totalMedicamentosFarmInvalidados }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="p-3 bg-light border rounded">
                                    <div class="small text-muted font-weight-bold text-uppercase">Accesos Activos</div>
                                    <div class="h4 font-weight-bold text-primary mb-0 mt-1">{{ count($sesionesFarmaceuticas) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="tablaSesionesFarmaceuticas" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 45px;" class="text-center">#</th>
                                        <th style="width: 140px;" class="text-center">Código Acceso</th>
                                        <th>Profesional / Farmacéutico Responsable</th>
                                        <th style="width: 180px;">Matrícula / Dependencia</th>
                                        <th style="width: 140px;" class="text-center">Dictámenes</th>
                                        <th style="width: 90px;" class="text-center">Estado</th>
                                        <th style="width: 180px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesionesFarmaceuticas as $idx => $sf)
                                        <tr>
                                            <td class="text-center font-weight-bold text-muted">{{ $idx + 1 }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-light border font-monospace font-weight-bold px-2 py-1" style="color: #0d9488; font-size: 12px;">
                                                    <i class="fa fa-key mr-1"></i> {{ $sf->codigo_acceso }}
                                                </span>
                                                <div class="text-muted small mt-1">{{ $sf->created_at->format('d/m/Y H:i') }}</div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark">{{ $sf->analista_nombre }}</div>
                                                @if($sf->analista_telefono)
                                                    <small class="text-muted"><i class="fab fa-whatsapp text-success mr-1"></i>{{ $sf->analista_telefono }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="font-weight-bold small text-dark">{{ $sf->analista_cargo ?? 'Regulación Farmacéutica' }}</div>
                                                <small class="text-muted">Mat: <strong>{{ $sf->matricula_profesional ?: 'N/D' }}</strong> | C.I.: {{ $sf->analista_documento ?: 'N/D' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light border border-success text-success px-2 py-1 font-weight-bold">
                                                    {{ $sf->validaciones_especialidades_count }} Especialidades
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light border border-success text-success px-2 py-1 font-weight-bold">Activo</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                                    @if($sf->analista_telefono)
                                                        @php
                                                            $waMsgFarm = urlencode("Estimado/a {$sf->analista_nombre},\nLe compartimos su enlace oficial para la Validación y Homologación Farmacológica de Especialidades Médicas (Vademécum IPS):\n\n🔗 Enlace: {$sf->url_acceso}\n🔑 Código PIN: {$sf->codigo_acceso}\n\nUnidad de Regulación Farmacéutica / Dirección de Planificación IPS.");
                                                            $waUrlFarm = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $sf->analista_telefono) . "&text={$waMsgFarm}";
                                                        @endphp
                                                        <a href="{{ $waUrlFarm }}" target="_blank" class="circle-btn btn btn-success text-white" title="Enviar por WhatsApp">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    @endif

                                                    <button type="button" class="circle-btn btn btn-outline-info btn-copy" data-url="{{ $sf->url_acceso }}" title="Copiar Enlace">
                                                        <i class="fa fa-copy"></i>
                                                    </button>

                                                    <a href="{{ $sf->url_acceso }}" target="_blank" class="circle-btn btn text-white" style="background-color: #0d9488;" title="Abrir Portal de Regulación Farmacéutica">
                                                        <i class="fa fa-external-link-alt"></i>
                                                    </a>

                                                    <form action="{{ route('riiss.validaciones.farmaceuticas.eliminar-enlace', $sf->id) }}" method="POST" class="d-inline form-eliminar-enlace">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="circle-btn btn btn-danger text-white btn-delete-enlace" data-analista="{{ $sf->analista_nombre }}" data-codigo="{{ $sf->codigo_acceso }}" title="Eliminar Enlace">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            {{-- PESTAÑA 3: ESPECIALIDADES MÉDICAS Y GESTIÓN DE MEDICAMENTOS (VADEMÉCUM) --}}
            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-especialidades" role="tabpanel" aria-labelledby="tab-especialidades-tab">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white py-3">
                        <div class="mb-2 mb-md-0">
                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fa fa-stethoscope text-primary mr-1"></i> Catálogo de Especialidades Médicas y Vínculos de Medicamentos
                            </h5>
                            <small class="text-muted">
                                Administre los medicamentos autorizados por Vademécum Institucional IPS y su correspondencia con cada especialidad.
                            </small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                            <div class="d-flex align-items-center">
                                <label class="font-weight-bold mr-2 mb-0 text-dark small">
                                    <i class="fa fa-filter text-primary mr-1"></i> Filtrar:
                                </label>
                                <select id="filtroEspecialidadesVademecum" class="form-control form-control-sm font-weight-bold" style="min-width: 210px; border-radius: 6px;">
                                    <option value="">📋 Todas las Especialidades ({{ $totalEspecialidades }})</option>
                                    <option value="CON_VADEMECUM">✅ Con Medicamentos Vademécum</option>
                                    <option value="SIN_VADEMECUM">⚠️ Sin Vademécum (0)</option>
                                    <option value="Activa">🟢 Solo Activas</option>
                                    <option value="Inactiva">🔴 Solo Inactivas</option>
                                </select>
                            </div>
                            <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                <i class="fa fa-link mr-1"></i> {{ number_format($totalVinculosVademecum, 0, ',', '.') }} Vínculos Vademécum
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="tablaEspecialidadesMedicamentos" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 45px;" class="text-center">#</th>
                                        <th>Especialidad Médica</th>
                                        <th style="width: 170px;" class="text-center">Vademécum Oficial IPS</th>
                                        <th style="width: 160px;" class="text-center">Histórico Dispensación</th>
                                        <th style="width: 140px;" class="text-center">Establecimientos</th>
                                        <th style="width: 90px;" class="text-center">Estado</th>
                                        <th style="width: 150px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($especialidades as $index => $esp)
                                        <tr id="fila-esp-{{ $esp->id }}" data-vademecum="{{ $esp->total_vademecum }}" data-estado="{{ $esp->activo ? 'Activa' : 'Inactiva' }}">
                                            <td class="text-center font-weight-bold text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                                    {{ $esp->nombre }}
                                                </div>
                                                @if($esp->codigo)
                                                    <small class="text-muted font-monospace">Cód: {{ $esp->codigo }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center" data-order="{{ $esp->total_vademecum }}">
                                                @if($esp->total_vademecum > 0)
                                                    <span class="badge badge-success px-3 py-1 font-weight-bold badge-vademecum-count-{{ $esp->id }}" style="font-size: 12.5px; letter-spacing: 0.3px;">
                                                        <i class="fa fa-pills mr-1"></i> {{ $esp->total_vademecum }} Autorizados
                                                    </span>
                                                @else
                                                    <span class="badge badge-light border border-secondary text-muted px-2 py-1 badge-vademecum-count-{{ $esp->id }}" style="font-size: 12px;">
                                                        0 Autorizados
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center" data-order="{{ $esp->total_historicos }}">
                                                <span class="badge badge-light border border-secondary text-secondary px-2 py-1 font-weight-bold" style="font-size: 12px;">
                                                    <i class="fa fa-history mr-1"></i> {{ $esp->total_historicos }} Medicamentos
                                                </span>
                                            </td>
                                            <td class="text-center" data-order="{{ $esp->total_establecimientos }}">
                                                <span class="badge badge-info px-2 py-1 font-weight-bold" style="font-size: 12px;">
                                                    <i class="fa fa-hospital mr-1"></i> {{ $esp->total_establecimientos }} Centros
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($esp->activo)
                                                    <span class="badge badge-light border border-success text-success px-2 py-1 font-weight-bold">
                                                        <i class="fa fa-check-circle mr-1"></i> Activa
                                                    </span>
                                                @else
                                                    <span class="badge badge-light border border-danger text-danger px-2 py-1 font-weight-bold">
                                                        <i class="fa fa-times-circle mr-1"></i> Inactiva
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-info font-weight-bold px-3 btn-gestionar-medicamentos shadow-xs" 
                                                        data-id="{{ $esp->id }}" 
                                                        data-nombre="{{ $esp->nombre }}"
                                                        style="border-radius: 6px; background: linear-gradient(60deg, #26c6da, #00acc1); border: none;">
                                                    <i class="fa fa-pills mr-1"></i> Gestionar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            {{-- PESTAÑA 3: CATÁLOGO OFICIAL VADEMÉCUM INSTITUCIONAL IPS (530 ÍTEMS) --}}
            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-vademecum" role="tabpanel" aria-labelledby="tab-vademecum-tab">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white py-3">
                        <div class="mb-2 mb-md-0">
                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fa fa-pills text-success mr-1"></i> Catálogo Oficial del Vademécum Institucional IPS (2026)
                            </h5>
                            <small class="text-muted">
                                Nómina de los {{ $totalMedicamentosVademecum }} medicamentos oficiales normados por el Instituto de Previsión Social.
                            </small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                            <div class="d-flex align-items-center">
                                <label class="font-weight-bold mr-2 mb-0 text-dark small">
                                    <i class="fa fa-filter text-success mr-1"></i> Tipo Uso:
                                </label>
                                <select id="filtroVademecumUso" class="form-control form-control-sm font-weight-bold" style="min-width: 190px; border-radius: 6px;">
                                    <option value="">📋 Todos los Usos ({{ $totalMedicamentosVademecum }})</option>
                                    <option value="AMBULATORIO">🏥 Ambulatorio</option>
                                    <option value="INTERNACION">🛏️ Internación</option>
                                    <option value="URGENCIAS">🚨 Urgencias</option>
                                </select>
                            </div>
                            <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                <i class="fa fa-certificate mr-1"></i> Vademécum IPS Oficial
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="tablaCatalogoVademecum" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 80px;" class="text-center">Código</th>
                                        <th>Medicamento / Principio Activo</th>
                                        <th>Concentración</th>
                                        <th>Forma Farmacéutica</th>
                                        <th style="width: 120px;" class="text-center">Vía</th>
                                        <th style="width: 140px;" class="text-center">Uso Vademécum</th>
                                        <th style="width: 130px;" class="text-center">Especialidades</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicamentosVademecum as $med)
                                        <tr data-uso="{{ strtoupper($med->uso_vademecum ?? '') }}">
                                            <td class="text-center font-weight-bold font-monospace text-primary" style="font-size: 12.5px;">
                                                {{ $med->codigo ?: '—' }}
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size: 13px;">
                                                    {{ $med->nombre }}
                                                </div>
                                                @if($med->presentacion)
                                                    <small class="text-muted">{{ $med->presentacion }}</small>
                                                @endif
                                            </td>
                                            <td style="font-size: 12.5px;">
                                                {{ $med->concentracion ?: '—' }}
                                            </td>
                                            <td style="font-size: 12.5px;">
                                                {{ $med->forma_farmaceutica ?: '—' }}
                                            </td>
                                            <td class="text-center" style="font-size: 12px;">
                                                {{ $med->via_administracion ?: '—' }}
                                            </td>
                                            <td class="text-center">
                                                @if($med->uso_vademecum)
                                                    <span class="badge badge-light border border-info text-info px-2 py-1 font-weight-bold" style="font-size: 11.5px;">
                                                        {{ $med->uso_vademecum }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center" data-order="{{ $med->total_especialidades }}">
                                                <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 12px;" title="{{ $med->especialidades_vademecum }}">
                                                    <i class="fa fa-user-md mr-1"></i> {{ $med->total_especialidades }} Esp.
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            {{-- PESTAÑA 5: MATRIZ CONSOLIDADA DE CONTROL CRUZADO RIISS IPS --}}
            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-matriz" role="tabpanel" aria-labelledby="tab-matriz-tab">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white py-3">
                        <div class="mb-2 mb-md-0">
                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fa fa-table mr-2 text-success"></i> Matriz Consolidada de Control Cruzado RIISS IPS (2026)
                            </h5>
                            <small class="text-muted">
                                Cruce integral: Establecimiento (Central e Interior) ↔ Especialidades Validadas en Terreno ↔ Medicamentos Aprobados por Regulación Farmacéutica.
                            </small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                            <a href="{{ route('riiss.validaciones.flujograma-documentacion') }}" target="_blank" class="btn btn-outline-success font-weight-bold px-3 py-2 shadow-xs" style="border-radius: 6px; font-size: 13px;">
                                <i class="fa fa-project-diagram mr-1"></i> Ver Flujograma Metodológico
                            </a>
                            <a href="{{ route('riiss.validaciones.matriz.exportar-excel') }}" class="btn btn-success font-weight-bold px-4 py-2 shadow-xs" style="border-radius: 6px; font-size: 13.5px;">
                                <i class="fa fa-file-excel mr-2"></i> Descargar Matriz Oficial (Excel / CSV)
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        {{-- Banner Explicativo de Gobernanza --}}
                        <div class="p-3 mb-4 rounded-lg" style="background: #f0fdf4; border: 1.5px solid #86efac;">
                            <div class="d-flex align-items-start">
                                <div class="p-2 rounded-circle bg-success text-white mr-3 mt-1" style="width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa fa-check-double"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-success" style="font-size: 14px;">
                                        Objetivo Estratégico: Control Cruzado y Optimización de la Distribución
                                    </div>
                                    <div class="text-dark small mt-1">
                                        Esta matriz permite a la <strong>Dirección de Planificación</strong> y a la <strong>Dirección de Logística de Suministros de Salud</strong> proyectar techos de consumo y auditar requerimientos farmacológicos en función de la <em>oferta médica real</em> de cada hospital y la <em>normativa terapéutica oficial aprobada por el Consejo</em>, sin interferir en los sistemas transaccionales locales de las dependencias.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Resumen de las 3 Dimensiones --}}
                        <div class="row text-center mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="p-3 rounded-lg bg-light border h-100">
                                    <div class="text-primary font-weight-bold h4 mb-0">{{ $totalEstablecimientos }}</div>
                                    <div class="font-weight-bold text-dark small mt-1">1. Establecimientos Auditables</div>
                                    <small class="text-muted">{{ $totalInterior }} Hospitales Interior + {{ $totalCentral }} Centros Central</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="p-3 rounded-lg bg-light border h-100">
                                    <div class="text-success font-weight-bold h4 mb-0">{{ $totalRegistrosValidados }}</div>
                                    <div class="font-weight-bold text-dark small mt-1">2. Especialidades Validadas Activas</div>
                                    <small class="text-muted">Relevadas en Terreno por Validadores</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-lg bg-light border h-100">
                                    <div class="text-teal font-weight-bold h4 mb-0" style="color:#0d9488;">{{ $totalMedicamentosVademecum }}</div>
                                    <div class="font-weight-bold text-dark small mt-1">3. Medicamentos del Vademécum IPS</div>
                                    <small class="text-muted">Aprobados por Resolución del Consejo</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-center py-5 bg-white rounded-lg border">
                            <i class="fa fa-file-excel fa-3x text-success mb-3" style="opacity: 0.85;"></i>
                            <h5 class="font-weight-bold text-dark mb-1">Exportación Consolidada de la Matriz RIISS</h5>
                            <p class="text-muted small mb-3" style="max-width: 600px; margin: 0 auto;">
                                Haga clic en el botón inferior para generar y descargar en tiempo real la planilla completa con todas las columnas de control cruzado formateada en UTF-8 para Microsoft Excel.
                            </p>
                            <a href="{{ route('riiss.validaciones.matriz.exportar-excel') }}" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm" style="border-radius: 8px;">
                                <i class="fa fa-download mr-1"></i> Descargar Planilla Consolidada (.CSV / Excel)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- Modal Generar Enlace con Segmentación Territorial (Card Header Info Flotante) --}}
<div class="modal fade" id="modalGenerarEnlace" tabindex="-1" role="dialog" aria-labelledby="modalGenerarEnlaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0" style="background: transparent; box-shadow: none;">
            <div class="card modal-card-material mb-0">
                <div class="card-header card-header-info d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title font-weight-bold text-white mb-0" style="font-size: 1.15rem;">
                            <i class="fa fa-key mr-2"></i> Generar Enlace Único de Validador
                        </h4>
                        <p class="card-category text-white mb-0" style="opacity: 0.92; font-size: 12.5px;">
                            Emisión de acceso oficial para relevamiento y auditoría de especialidades
                        </p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none; outline: none;">
                        <span aria-hidden="true" style="font-size: 1.6rem; color: #ffffff;">&times;</span>
                    </button>
                </div>

                <form action="{{ route('riiss.validaciones.generar-enlace') }}" method="POST">
                    @csrf
                    <div class="card-body p-4 pt-3">
                        <p class="text-muted small mb-3">
                            Configure la jurisdicción territorial y los datos del analista evaluador. Se generará un enlace criptográfico único.
                        </p>

                        {{-- Sección 1: Jurisdicción Territorial --}}
                        <div class="form-card-box">
                            <div class="form-section-title">
                                <i class="fa fa-map-marked-alt"></i> 1. Jurisdicción Territorial & Alcance
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        Dirección / Área de Gestión <span class="text-danger">*</span>
                                    </label>
                                    <select name="area_gestion" id="selectAreaGestion" class="form-control select2-modal" required>
                                        @foreach($todasAreasGestion as $a)
                                            <option value="{{ $a }}" @selected($a === 'AREA INTERIOR')>
                                                🏥 {{ $a }} ({{ $areasConteo[$a] ?? 0 }} Establecimientos)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        Alcance / Departamento Asignado
                                    </label>
                                    <select name="departamento_filtro" id="selectDeptoFiltro" class="form-control select2-modal">
                                        {{-- Inyectado dinámicamente --}}
                                    </select>
                                    <small class="text-muted d-block mt-1" style="font-size: 11px;" id="ayudaAlcance">
                                        <i class="fa fa-info-circle text-info mr-1"></i> Filtrará la lista visible para este analista.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Sección 2: Datos del Analista --}}
                        <div class="form-card-box">
                            <div class="form-section-title">
                                <i class="fa fa-user-check"></i> 2. Datos del Analista / Responsable
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-7 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        Nombre y Apellido <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-user text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_nombre" class="form-control border-left-0" required placeholder="Ej: Lic. Carlos Gómez">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="font-weight-bold text-dark small mb-1">Cargo / Función</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-briefcase text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_cargo" id="inputAnalistaCargo" class="form-control border-left-0" placeholder="Ej: Analista Técnico Área Interior" value="Analista Técnico Área Interior">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-dark small mb-1">Cédula de Identidad (C.I.)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-id-card text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_documento" class="form-control border-left-0" placeholder="Ej: 3.456.789">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold text-dark small mb-1">Teléfono / WhatsApp</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-phone text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_telefono" class="form-control border-left-0" placeholder="Ej: 0981 123456">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección 3: Notas --}}
                        <div class="form-card-box mb-0">
                            <div class="form-section-title">
                                <i class="fa fa-clipboard-list"></i> 3. Notas u Observaciones (Opcional)
                            </div>
                            <textarea name="notas" class="form-control" rows="2" placeholder="Indicaciones específicas para esta campaña de relevamiento..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center" style="border-radius: 0 0 12px 12px;">
                    <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center" style="border-radius: 0 0 12px 12px;">
                        <button type="button" class="btn btn-secondary font-weight-bold px-3" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-info font-weight-bold px-4 shadow-sm" style="background: linear-gradient(60deg, #26c6da, #00acc1); border: none;">
                            <i class="fa fa-link mr-1"></i> Generar y Emitir Enlace
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Generar Enlace de Regulación Farmacéutica (Vademécum IPS) --}}
<div class="modal fade" id="modalGenerarEnlaceFarmaceutico" tabindex="-1" role="dialog" aria-labelledby="modalGenerarEnlaceFarmaceuticoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0" style="background: transparent; box-shadow: none;">
            <div class="card modal-card-material mb-0">
                <div class="card-header d-flex align-items-center justify-content-between" style="background: linear-gradient(60deg, #0d9488, #0f766e); box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(13, 148, 136, 0.4); border-radius: 6px; margin: -20px 15px 0; padding: 15px;">
                    <div>
                        <h4 class="card-title font-weight-bold text-white mb-0" style="font-size: 1.15rem;">
                            <i class="fa fa-prescription-bottle-alt mr-2"></i> Generar Enlace Oficial — Unidad de Regulación Farmacéutica
                        </h4>
                        <p class="card-category text-white mb-0" style="opacity: 0.92; font-size: 12.5px;">
                            Emisión de acceso criptográfico para Químico Farmacéutico auditor de Vademécum
                        </p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none; outline: none;">
                        <span aria-hidden="true" style="font-size: 1.6rem; color: #ffffff;">&times;</span>
                    </button>
                </div>

                <form action="{{ route('riiss.validaciones.farmaceuticas.generar-enlace') }}" method="POST">
                    @csrf
                    <div class="card-body p-4 pt-3">
                        <div class="p-3 mb-3 rounded" style="background:#f0fdf4; border:1px solid #86efac;">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-info-circle text-teal mr-2" style="color:#0d9488; font-size:18px;"></i>
                                <span class="small text-dark font-weight-bold">
                                    El profesional asignado tendrá acceso a auditar, validar o invalidar (con justificación técnica obligatoria) los medicamentos del Vademécum para cada una de las 155 especialidades médicas.
                                </span>
                            </div>
                        </div>

                        {{-- Datos del Químico Farmacéutico --}}
                        <div class="form-card-box">
                            <div class="form-section-title" style="color: #0d9488;">
                                <i class="fa fa-user-md"></i> Datos del Profesional Farmacéutico
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-7 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        Nombre y Apellido <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-user text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_nombre" class="form-control border-left-0" required placeholder="Ej: Q.F. María José Benítez">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="font-weight-bold text-dark small mb-1">Reg. / Matrícula Profesional</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-id-badge text-muted"></i></span>
                                        </div>
                                        <input type="text" name="matricula_profesional" class="form-control border-left-0" placeholder="Ej: Reg. Prof. MSPyBS 2841">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-7 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-dark small mb-1">Cargo / Dependencia</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-briefcase text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_cargo" class="form-control border-left-0" value="Unidad de Regulación Farmacéutica - IPS" placeholder="Ej: Unidad de Regulación Farmacéutica">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="font-weight-bold text-dark small mb-1">Cédula de Identidad (C.I.)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-id-card text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_documento" class="form-control border-left-0" placeholder="Ej: 2.154.890">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-dark small mb-1">Teléfono / WhatsApp</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-phone text-muted"></i></span>
                                        </div>
                                        <input type="text" name="analista_telefono" class="form-control border-left-0" placeholder="Ej: 0981 654321">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold text-dark small mb-1">Correo Institucional</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-envelope text-muted"></i></span>
                                        </div>
                                        <input type="email" name="analista_email" class="form-control border-left-0" placeholder="Ej: mbenitez@ips.gov.py">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección: Notas --}}
                        <div class="form-card-box mb-0">
                            <div class="form-section-title" style="color: #0d9488;">
                                <i class="fa fa-clipboard-list"></i> Observaciones / Alcance de Auditoría (Opcional)
                            </div>
                            <textarea name="notas" class="form-control" rows="2" placeholder="Indicaciones para el farmacéutico..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center" style="border-radius: 0 0 12px 12px;">
                        <button type="button" class="btn btn-secondary font-weight-bold px-3" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn font-weight-bold px-4 shadow-sm text-white" style="background: linear-gradient(60deg, #0d9488, #0f766e); border: none;">
                            <i class="fa fa-key mr-1"></i> Emitir Acceso Farmacéutico
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Compartir Acceso y Código a Validador / Analista (WhatsApp) --}}
<div class="modal fade" id="modalCompartirAccesoValidador" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); border-radius: 10px;">
                        <i class="fab fa-whatsapp fa-2x text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white">Compartir Acceso a Validador</h5>
                        <small class="text-white-50">Enlace oficial con Código de Acceso asignado</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="outline: none;">
                    <span style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-4 bg-light">
                <div class="alert alert-success d-flex align-items-center mb-3 py-2 px-3 shadow-xs" style="background:#ecfdf5; border-color:#a7f3d0;">
                    <i class="fa fa-user-check fa-lg mr-2 text-success"></i>
                    <div>
                                       <button class="btn btn-sm btn-success px-3 font-weight-bold shadow-xs" type="button" onclick="copiarCodigoAccesoValidador()">
                            <i class="fa fa-copy mr-1"></i> Copiar Código
                        </button>
                    </div>

                    {{-- Alcance territorial --}}
                    <div class="small text-muted p-2 rounded bg-light border mt-2">
                        <i class="fa fa-map-marked-alt text-info mr-1"></i> <strong>Alcance:</strong> <span id="shareAlcanceTexto" class="text-dark"></span>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="#" id="btnShareWhatsAppDirecto" target="_blank" class="btn btn-success font-weight-bold py-2 shadow-sm mb-2 text-center" style="background: #25d366; border: none; font-size: 13.5px;">
                        <i class="fab fa-whatsapp fa-lg mr-2"></i> Enviar Mensaje por WhatsApp
                    </a>

                    <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold mb-2 py-2" onclick="copiarMensajeCompletoValidador()">
                        <i class="fa fa-clipboard mr-1"></i> Copiar Mensaje Completo para Pegar
                    </button>

                    <a href="#" id="btnShareAbrirPortal" target="_blank" class="btn btn-link btn-sm text-primary text-center mt-1 font-weight-bold">
                        <i class="fa fa-external-link-alt mr-1"></i> Abrir Portal de Validador para verificar
                    </a>
                </div>
            </div>

            <div class="modal-footer bg-white py-2">
                <button type="button" class="btn btn-secondary btn-sm font-weight-bold px-3" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Clasificación Territorial (Áreas de Gestión Bioestadística & RIISS) --}}
<div class="modal fade" id="modalClasificacionTerritorial" tabindex="-1" role="dialog" aria-labelledby="modalClasificacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0" style="background: transparent; box-shadow: none;">
            <div class="card modal-card-material mb-0">
                <div class="card-header card-header-info d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title font-weight-bold text-white mb-0" style="font-size: 1.15rem;">
                            <i class="fa fa-map-marked-alt mr-2"></i> Clasificación Territorial de Establecimientos
                        </h4>
                        <p class="card-category text-white mb-0" style="opacity: 0.92; font-size: 12.5px;">
                            Matriz oficial de asignación por Área de Gestión (Bioestadística & RIISS)
                        </p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none; outline: none;">
                        <span aria-hidden="true" style="font-size: 1.6rem; color: #ffffff;">&times;</span>
                    </button>
                </div>
                <div class="card-body p-4 pt-3 modal-clasif-body">
                    {{-- Mini KPI Badges Dinámicos por Área de Gestión --}}
                    <div class="d-flex flex-wrap align-items-stretch mb-3" style="gap: 8px;">
                        @php
                            $areaColores = [
                                'AREA INTERIOR' => ['bg' => '#e0f2fe', 'color' => '#0284c7', 'border' => '#bae6fd', 'icon' => 'fa-hospital'],
                                'AREA CENTRAL'  => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '#bbf7d0', 'icon' => 'fa-city'],
                                'GESTION MÉDICA'=> ['bg' => '#fef3c7', 'color' => '#d97706', 'border' => '#fde68a', 'icon' => 'fa-stethoscope'],
                                'GESTION MEDICA'=> ['bg' => '#fef3c7', 'color' => '#d97706', 'border' => '#fde68a', 'icon' => 'fa-stethoscope'],
                                'MEDICINA PREVENTIVA' => ['bg' => '#f3e8ff', 'color' => '#9333ea', 'border' => '#e9d5ff', 'icon' => 'fa-shield-heart'],
                                'HOSPITAL CENTRAL' => ['bg' => '#ffe4e6', 'color' => '#e11d48', 'border' => '#fecdd3', 'icon' => 'fa-hospital-user'],
                                'HOSPITALES DE ESPECIALIDADES QUIRURJICAS' => ['bg' => '#ffedd5', 'color' => '#ea580c', 'border' => '#fed7aa', 'icon' => 'fa-syringe'],
                                'HOSPITALES DE ESPECIALIDADES QUIRÚRGICAS' => ['bg' => '#ffedd5', 'color' => '#ea580c', 'border' => '#fed7aa', 'icon' => 'fa-syringe'],
                                'HOSPITALES DE ESPECIALIDADES QUIRURGICAS' => ['bg' => '#ffedd5', 'color' => '#ea580c', 'border' => '#fed7aa', 'icon' => 'fa-syringe'],
                            ];
                        @endphp

                        @foreach($todasAreasGestion as $areaItem)
                            @php
                                $conf = $areaColores[$areaItem] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#e2e8f0', 'icon' => 'fa-map-pin'];
                                $cant = $areasConteo[$areaItem] ?? 0;
                            @endphp
                            <div class="p-2 rounded border shadow-xs d-flex align-items-center badge-kpi-area" 
                                 data-area="{{ $areaItem }}"
                                 title="Clic para filtrar por {{ $areaItem }}"
                                 style="background: {{ $conf['bg'] }}; border-color: {{ $conf['border'] }} !important; min-width: 140px; flex: 1 1 auto;">
                                <div class="p-2 rounded-circle text-white mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: {{ $conf['color'] }}; flex-shrink: 0;">
                                    <i class="fa {{ $conf['icon'] }}" style="font-size: 13px;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div class="small font-weight-bold text-truncate" style="color: {{ $conf['color'] }}; font-size: 10px;" title="{{ $areaItem }}">
                                        {{ $areaItem }}
                                    </div>
                                    <div class="font-weight-bold text-dark" style="font-size: 13px;">
                                        <span class="kpi-count-val" id="badge-count-{{ \Illuminate\Support\Str::slug($areaItem) }}">{{ $cant }}</span> <span class="font-weight-normal text-muted" style="font-size: 10px;">establ.</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="p-2 rounded border shadow-xs d-flex align-items-center bg-dark text-white badge-kpi-area" 
                             data-area=""
                             title="Clic para ver todos los establecimientos"
                             style="min-width: 140px; flex: 1 1 auto; border-color: #334155 !important;">
                            <div class="p-2 rounded-circle bg-success text-white mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">
                                <i class="fa fa-globe-americas" style="font-size: 13px;"></i>
                            </div>
                            <div>
                                <div class="small font-weight-bold text-white-50" style="font-size: 10px;">TOTAL RED</div>
                                <div class="font-weight-bold text-white" style="font-size: 13px;">
                                    <span class="kpi-count-val" id="badge-count-total-red">{{ $totalEstablecimientos }}</span> <span class="font-weight-normal text-white-50" style="font-size: 10px;">Totales</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── BARRA DE MULTI-FILTROS CON SELECT2 ── --}}
                    <div class="card border mb-3" style="background: #f8fafc; border-color: #e2e8f0; border-radius: 8px;">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <label class="small text-muted font-weight-bold mb-1">
                                        <i class="fa fa-layer-group text-primary mr-1"></i> Filtrar por Área de Gestión:
                                    </label>
                                    <select id="filtroAreaClasif" class="form-control select2-modal-clasif">
                                        <option value="">📁 Todas las Áreas de Gestión</option>
                                        @foreach($todasAreasGestion as $a)
                                            <option value="{{ $a }}">{{ $a }} ({{ $areasConteo[$a] ?? 0 }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <label class="small text-muted font-weight-bold mb-1">
                                        <i class="fa fa-map-marker-alt text-danger mr-1"></i> Filtrar por Departamento:
                                    </label>
                                    <select id="filtroDeptoClasif" class="form-control select2-modal-clasif">
                                        <option value="">🏛️ Todos los Departamentos</option>
                                        @foreach($todosDepartamentos as $dep)
                                            <option value="{{ $dep }}">{{ $dep }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label class="small text-muted font-weight-bold mb-1">
                                        <i class="fa fa-clinic-medical text-success mr-1"></i> Filtrar por Tipología:
                                    </label>
                                    <select id="filtroTipoClasif" class="form-control select2-modal-clasif">
                                        <option value="">🏥 Todas las Tipologías</option>
                                        @foreach($todasTipologias as $tip)
                                            <option value="{{ $tip }}">{{ $tip }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm w-100 font-weight-bold" id="btnLimpiarFiltrosClasif" title="Restablecer filtros" style="height: 38px; border-radius: 8px;">
                                        <i class="fa fa-undo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="tablaClasificacionEst" style="width:100%;">
                            <thead style="background: #1e293b; color: #ffffff; font-size:12px;">
                                <tr>
                                    <th style="width: 12%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Código</th>
                                    <th style="width: 36%; background: #1e293b; color: #ffffff; border-color: #334155;">Establecimiento de Salud</th>
                                    <th style="width: 24%; background: #1e293b; color: #ffffff; border-color: #334155;">Departamento / Tipología</th>
                                    <th style="width: 28%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Área de Gestión Asignada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todosEstablecimientos as $e)
                                    <tr class="fila-est-clasif" 
                                        data-est-id="{{ $e->id_establecimiento }}"
                                        data-nombre="{{ strtolower($e->nombre_oficial . ' ' . $e->id_establecimiento) }}"
                                        data-area="{{ trim($e->area_gestion ?? '') }}"
                                        data-depto="{{ trim($e->departamento ?? '') }}"
                                        data-tipo="{{ trim($e->tipologia_clasificacion ?? '') }}">
                                        <td class="text-center font-weight-bold text-muted" style="font-size:11.5px; vertical-align: middle;">
                                            {{ $e->id_establecimiento }}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <div class="font-weight-bold text-dark" style="font-size:13px;">
                                                {{ $e->nombre_oficial }}
                                            </div>
                                            @if($e->sistema_hospitalario)
                                                <span class="badge badge-light border text-muted" style="font-size: 10px;">{{ $e->sistema_hospitalario }}</span>
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <span class="badge badge-light border text-dark font-weight-bold">{{ $e->departamento ?? 'SIN DEPTO' }}</span>
                                            <div class="text-muted small mt-1" style="font-size:11px;">{{ $e->tipologia_clasificacion ?? '—' }}</div>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <select class="form-control form-control-sm select-area-asignacion" 
                                                        data-est-id="{{ $e->id_establecimiento }}"
                                                        style="width: 100%;">
                                                    @foreach($todasAreasGestion as $areaOpt)
                                                        <option value="{{ $areaOpt }}" @selected(strtoupper(trim($e->area_gestion ?? '')) === strtoupper(trim($areaOpt)))>
                                                            {{ $areaOpt }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-end" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-4" data-dismiss="modal">
                        <i class="fa fa-check mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Gestión de Medicamentos por Especialidad --}}
<div class="modal fade" id="modalGestionMedicamentos" tabindex="-1" role="dialog" aria-labelledby="modalGestionMedicamentosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0" style="background: transparent; box-shadow: none;">
            <div class="card modal-card-material mb-0">
                <div class="card-header card-header-info d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title font-weight-bold text-white mb-0" id="modalEspecialidadTitulo" style="font-size: 1.2rem;">
                            <i class="fa fa-stethoscope mr-2"></i> Gestión de Medicamentos
                        </h4>
                        <p class="card-category text-white mb-0" id="modalEspecialidadSubtitulo" style="opacity: 0.92; font-size: 12.5px;">
                            Medicamentos autorizados por Vademécum Institucional IPS y registros históricos
                        </p>
                    </div>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <span class="badge badge-light px-3 py-2 font-weight-bold text-success shadow-xs" id="badgeModalTotalVademecum" style="font-size: 12.5px;">
                            0 Vademécum
                        </span>
                        <span class="badge badge-light px-3 py-2 font-weight-bold text-secondary shadow-xs" id="badgeModalTotalHistoricos" style="font-size: 12.5px;">
                            0 Históricos
                        </span>
                        <button type="button" class="close text-white ml-2" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none; outline: none;">
                            <span aria-hidden="true" style="font-size: 1.6rem; color: #ffffff;">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="card-body p-4 pt-3">
                    {{-- Vincular nuevo medicamento del catálogo al vademécum --}}
                    <div class="form-card-box mb-4">
                        <div class="form-section-title">
                            <i class="fa fa-plus-circle"></i> Vincular Medicamento del Catálogo al Vademécum Oficial
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-9 col-md-8 mb-2 mb-md-0">
                                <select id="selectMedicamentoVincular" class="form-control" style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-4">
                                <button type="button" id="btnEjecutarVinculacion" class="btn btn-info btn-block font-weight-bold py-2 shadow-xs" style="background: linear-gradient(60deg, #26c6da, #00acc1); border: none; border-radius: 6px;">
                                    <i class="fa fa-link mr-1"></i> Vincular al Vademécum
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla interactiva de medicamentos de la especialidad --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" id="tablaMedicamentosEspecialidad" style="width:100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 75px;" class="text-center">Código</th>
                                    <th>Medicamento / Principio Activo</th>
                                    <th>Concentración / Forma</th>
                                    <th style="width: 110px;" class="text-center">Vía</th>
                                    <th style="width: 120px;" class="text-center">Uso Vademécum</th>
                                    <th style="width: 150px;" class="text-center">Origen / Vademécum</th>
                                    <th style="width: 90px;" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyMedicamentosEspecialidad">
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Seleccione una especialidad para ver sus medicamentos.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-end" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-4" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const DEPTOS_POR_AREA = @json($deptosPorArea);

function actualizarOpcionesDepartamentos() {
    const area = $('#selectAreaGestion').val() || 'AREA INTERIOR';
    const $selectDepto = $('#selectDeptoFiltro');
    const $cargo = $('#inputAnalistaCargo');

    const deptos = DEPTOS_POR_AREA[area] || [];
    let html = '';
    
    $cargo.val('Analista Técnico - ' + area);

    if (deptos.length > 1) {
        html += `<option value="TODOS_${area.replace(/[^a-zA-Z0-9]/g, '_')}">TODOS LOS DEPARTAMENTOS (${deptos.length} Dptos)</option>`;
        deptos.forEach(d => {
            html += `<option value="${d}">${d}</option>`;
        });
    } else if (deptos.length === 1) {
        html += `<option value="${deptos[0]}">${deptos[0]}</option>`;
    } else {
        html += '<option value="">TODOS LOS DEPARTAMENTOS</option>';
    }

    $selectDepto.html(html).trigger('change');
}

function recalcularKpiBadges() {
    var counts = {};
    var total = 0;
    $('#tablaClasificacionEst tbody tr').each(function() {
        var a = $(this).attr('data-area');
        if (a) {
            counts[a] = (counts[a] || 0) + 1;
            total++;
        }
    });
    $('.badge-kpi-area[data-area]').each(function() {
        var a = $(this).data('area');
        if (a) {
            $(this).find('.kpi-count-val').text(counts[a] || 0);
        }
    });
    $('#badge-count-total-red').text(total);
}

function cambiarAreaEstablecimiento(estId, nuevaArea) {
    $.post('{{ route("riiss.validaciones.establecimiento.area-gestion") }}', {
        _token: '{{ csrf_token() }}',
        establecimiento_id: estId,
        area_gestion: nuevaArea
    }, function(res) {
        if (res.success) {
            var $row = $(`tr[data-est-id="${estId}"]`);
            if ($row.length === 0) {
                $row = $(`#tablaClasificacionEst tbody tr`).filter(function() {
                    return $(this).attr('data-est-id') === estId || $(this).find('td:first').text().trim() === estId;
                });
            }
            $row.attr('data-area', nuevaArea);

            recalcularKpiBadges();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Área de Gestión asignada: ' + nuevaArea,
                showConfirmButton: false,
                timer: 2000
            });
        }
    }).fail(function() {
        Swal.fire('Error', 'No se pudo actualizar el área de gestión del establecimiento', 'error');
    });
}

function copiarTextoInput(inputId, mensaje) {
    const copyText = document.getElementById(inputId);
    if (!copyText) return;
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value).then(function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: mensaje || '¡Texto copiado!',
            showConfirmButton: false,
            timer: 1800
        });
    });
}

let _ultimoCodigoValidador = '';
let _ultimoMensajeWhatsAppValidador = '';
let _especialidadGestionActualId = null;
let dtMedicamentosModal = null;

function abrirModalCompartirValidador(data) {
    _ultimoCodigoValidador = data.codigo || '';

    $('#shareAnalistaNombre').text(data.analista || 'Analista');
    $('#shareAnalistaCargo').text((data.cargo || 'Analista Técnico') + ' · ' + (data.area || ''));
    $('#shareUrlPortal').val(data.url || '');
    $('#shareCodigoAcceso').text(data.codigo || '');

    let alcanceTexto = (data.area === 'AREA CENTRAL' ? 'Dirección de Hospitales Área Central' : 'Dirección de Hospitales Área Interior');
    if (data.depto && data.depto !== 'TODOS_INTERIOR' && data.depto !== 'TODOS_CENTRAL') {
        alcanceTexto += ' (' + data.depto + ')';
    } else {
        alcanceTexto += ' (Todos los Departamentos)';
    }
    $('#shareAlcanceTexto').text(alcanceTexto);

    const mensaje = `🏛️ *INSTITUTO DE PREVISIÓN SOCIAL (IPS)*\n📋 *Módulo de Validación de Especialidades Médicas (RIISS)*\n\nEstimado/a *${data.analista || 'Analista'}*,\nSe ha emitido su enlace oficial de auditor y validador:\n\n🌐 *Acceso:* ${data.url || ''}\n🔑 *Código PIN:* \`${data.codigo || ''}\`\n📍 *Alcance:* ${alcanceTexto}\n\n_Por favor ingrese al enlace, valide su código de seguridad y proceda con la auditoría de especialidades de su jurisdicción._`;

    _ultimoMensajeWhatsAppValidador = mensaje;

    let telLimpio = (data.telefono || '').replace(/\D/g, '');
    if (telLimpio.startsWith('09')) {
        telLimpio = '595' + telLimpio.substring(1);
    } else if (telLimpio.startsWith('9')) {
        telLimpio = '595' + telLimpio;
    }

    const waUrl = telLimpio 
        ? `https://api.whatsapp.com/send?phone=${telLimpio}&text=${encodeURIComponent(mensaje)}`
        : `https://api.whatsapp.com/send?text=${encodeURIComponent(mensaje)}`;

    $('#btnShareWhatsAppDirecto').attr('href', waUrl);
    $('#btnShareAbrirPortal').attr('href', data.url || '#');

    $('#modalCompartirAccesoValidador').modal('show');
}

window.copiarCodigoAccesoValidador = function() {
    if (!_ultimoCodigoValidador) return;
    navigator.clipboard.writeText(_ultimoCodigoValidador).then(function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Código (' + _ultimoCodigoValidador + ') copiado 🔑',
            showConfirmButton: false,
            timer: 1800
        });
    });
};

window.copiarMensajeCompletoValidador = function() {
    if (!_ultimoMensajeWhatsAppValidador) return;
    navigator.clipboard.writeText(_ultimoMensajeWhatsAppValidador).then(function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Mensaje completo copiado para WhatsApp 💬',
            showConfirmButton: false,
            timer: 2000
        });
    });
};

window.confirmarReinicioValidaciones = function() {
    Swal.fire({
        title: '¿Reiniciar todas las validaciones a 0?',
        html: `
            <p class="text-muted mb-2" style="font-size: 14px;">
                Esta acción restablecerá los contadores y eliminará todos los registros de especialidades validadas/inactivadas durante las pruebas.
            </p>
            <div class="custom-control custom-checkbox text-left mt-3 p-2 bg-light rounded border">
                <input type="checkbox" class="custom-control-input" id="checkEliminarSesiones">
                <label class="custom-control-label font-weight-bold text-dark" for="checkEliminarSesiones" style="font-size: 13px; cursor: pointer;">
                    Eliminar también los enlaces y sesiones de validador creados
                </label>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash-alt mr-1"></i> Sí, reiniciar a 0',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const checkEl = document.getElementById('checkEliminarSesiones');
            const incluirSesiones = checkEl ? checkEl.checked : false;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('riiss.validaciones.reiniciar-registros') }}";

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = "{{ csrf_token() }}";
            form.appendChild(csrfInput);

            if (incluirSesiones) {
                const incInput = document.createElement('input');
                incInput.type = 'hidden';
                incInput.name = 'incluir_sesiones';
                incInput.value = '1';
                form.appendChild(incInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    });
};

$(document).ready(function() {
    // Select2 en modal Generar Enlace
    $('#selectAreaGestion').select2({ dropdownParent: $('#modalGenerarEnlace'), width: '100%' });
    $('#selectDeptoFiltro').select2({ dropdownParent: $('#modalGenerarEnlace'), width: '100%' });

    $('#modalGenerarEnlace').on('shown.bs.modal', function () {
        $('#selectAreaGestion').select2({ dropdownParent: $('#modalGenerarEnlace'), width: '100%' });
        $('#selectDeptoFiltro').select2({ dropdownParent: $('#modalGenerarEnlace'), width: '100%' });
    });

    $('#selectAreaGestion').on('change', actualizarOpcionesDepartamentos);
    actualizarOpcionesDepartamentos();

    // ── Select2 para Filtros de Clasificación Territorial ──
    function initSelect2ClasifFiltros() {
        $('#filtroAreaClasif').select2({ dropdownParent: $('#modalClasificacionTerritorial'), width: '100%' });
        $('#filtroDeptoClasif').select2({ dropdownParent: $('#modalClasificacionTerritorial'), width: '100%' });
        $('#filtroTipoClasif').select2({ dropdownParent: $('#modalClasificacionTerritorial'), width: '100%' });
    }

    // ── Select2 para cada Asignación en Filas de Tabla ──
    function initSelect2TableClasif() {
        $('#tablaClasificacionEst .select-area-asignacion').each(function() {
            if (!$(this).data('select2')) {
                $(this).select2({
                    dropdownParent: $('#modalClasificacionTerritorial'),
                    width: '100%',
                    minimumResultsForSearch: 6
                });
            }
        });
    }

    initSelect2ClasifFiltros();

    // ── Filtro Multicriterio personalizado en DataTables para Clasificación Territorial ──
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        if (settings.nTable.id !== 'tablaClasificacionEst') {
            return true;
        }

        var tr = $(settings.aoData[dataIndex].nTr);
        var areaFiltro = ($('#filtroAreaClasif').val() || '').toString().trim().toUpperCase();
        var deptoFiltro = ($('#filtroDeptoClasif').val() || '').toString().trim().toUpperCase();
        var tipoFiltro = ($('#filtroTipoClasif').val() || '').toString().trim().toUpperCase();

        var rowArea = (tr.attr('data-area') || '').toString().trim().toUpperCase();
        var rowDepto = (tr.attr('data-depto') || '').toString().trim().toUpperCase();
        var rowTipo = (tr.attr('data-tipo') || '').toString().trim().toUpperCase();

        if (areaFiltro !== '' && rowArea !== areaFiltro) {
            return false;
        }

        if (deptoFiltro !== '' && rowDepto !== deptoFiltro) {
            return false;
        }

        if (tipoFiltro !== '' && rowTipo !== tipoFiltro) {
            return false;
        }

        return true;
    });

    // ── Inicialización de DataTable para Clasificación Territorial ──
    var dtClasif = $('#tablaClasificacionEst').DataTable({
        language: {
            emptyTable: 'No hay establecimientos que coincidan con los filtros.',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ establecimientos',
            infoEmpty: '0 establecimientos encontrados',
            infoFiltered: '(filtrado de _MAX_ totales)',
            search: 'Buscar en tabla:',
            searchPlaceholder: 'Código o nombre...',
            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            lengthMenu: 'Mostrar _MENU_ registros'
        },
        pageLength: 10,
        order: [[1, 'asc']],
        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-3"lf>rt<"d-flex flex-wrap align-items-center justify-content-between mt-3"ip>',
        drawCallback: function() {
            initSelect2TableClasif();
        }
    });

    $('#modalClasificacionTerritorial').on('shown.bs.modal', function () {
        initSelect2ClasifFiltros();
        initSelect2TableClasif();
        if (dtClasif) {
            dtClasif.columns.adjust().draw();
        }
    });

    // Filtros interactivos vinculados a DataTables
    $('#filtroAreaClasif, #filtroDeptoClasif, #filtroTipoClasif').on('change', function() {
        dtClasif.draw();
    });

    // Clic en KPI Badges para filtrar rápidamente por Área
    $(document).on('click', '.badge-kpi-area', function() {
        var area = $(this).data('area') || '';
        $('#filtroAreaClasif').val(area).trigger('change');
    });

    $('#btnLimpiarFiltrosClasif').on('click', function() {
        $('#filtroAreaClasif').val('').trigger('change');
        $('#filtroDeptoClasif').val('').trigger('change');
        $('#filtroTipoClasif').val('').trigger('change');
        dtClasif.search('').draw();
    });

    // Cambio dinámico de Área de Gestión por Select2 en la fila
    $(document).on('change', '.select-area-asignacion', function() {
        var estId = $(this).data('est-id');
        var nuevaArea = $(this).val();
        var tr = $(this).closest('tr');
        
        tr.attr('data-area', nuevaArea);
        cambiarAreaEstablecimiento(estId, nuevaArea);
    });

    // Delegación de eventos para botones en tabla de enlaces
    $(document).on('click', '.btn-share-validador', function() {
        abrirModalCompartirValidador({
            url: $(this).data('url'),
            codigo: $(this).data('codigo'),
            analista: $(this).data('analista'),
            cargo: $(this).data('cargo'),
            telefono: $(this).data('telefono'),
            area: $(this).data('area'),
            depto: $(this).data('depto')
        });
    });

    $(document).on('click', '.btn-copy', function() {
        const url = $(this).data('url');
        navigator.clipboard.writeText(url).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Enlace copiado al portapapeles 📋',
                showConfirmButton: false,
                timer: 1800
            });
        });
    });

    $(document).on('click', '.btn-reset-enlace', function() {
        const form = $(this).closest('form');
        const analista = $(this).data('analista');
        const count = $(this).data('count');

        Swal.fire({
            title: '¿Reiniciar validaciones de este enlace?',
            text: `Se borrarán los ${count} registros guardados por "${analista}". Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-sync-alt mr-1"></i> Sí, reiniciar a 0',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $(document).on('click', '.btn-delete-enlace', function() {
        const form = $(this).closest('form');
        const analista = $(this).data('analista');
        const codigo = $(this).data('codigo');

        Swal.fire({
            title: '¿Eliminar enlace de validador?',
            text: `Se eliminará el acceso oficial (${codigo}) emitido para "${analista}".`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar enlace',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // ── Variables globales para modal de medicamentos ──
    var dtMedicamentosModal = null;
    var _especialidadGestionActualId = null;

    // ── 1. DataTable Sesiones de Validador ──
    var dtSesiones = $('#tablaSesionesValidador').DataTable({
        language: {
            emptyTable:     '<div class="py-4 text-muted"><i class="fa fa-link fa-2x mb-2 text-secondary" style="opacity:.4"></i><div>No hay enlaces de validadores generados aún.</div><small>Haga clic en "+ Nuevo Enlace de Validador" para emitir el primer acceso.</small></div>',
            info:           'Mostrando _START_ a _END_ de _TOTAL_ enlaces',
            infoEmpty:      '0 enlaces',
            infoFiltered:   '(filtrado de _MAX_ totales)',
            search:         'Buscar:',
            searchPlaceholder: 'Analista, código, área...',
            zeroRecords:    'No se encontraron enlaces coincidentes',
            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            lengthMenu:     'Mostrar _MENU_ registros por página'
        },
        order: [[0, 'asc']],
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [6] }
        ],
        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-3"lf>rt<"d-flex flex-wrap align-items-center justify-content-between mt-3"ip>'
    });

    // ── DataTable Sesiones Regulación Farmacéutica ──
    var dtSesionesFarm = $('#tablaSesionesFarmaceuticas').DataTable({
        language: {
            emptyTable:     '<div class="py-4 text-muted"><i class="fa fa-prescription-bottle-alt fa-2x mb-2 text-secondary" style="opacity:.4"></i><div>No hay enlaces de la Unidad de Regulación Farmacéutica generados aún.</div><small>Haga clic en "+ Nuevo Enlace de Regulación Farmacéutica" para emitir el primer acceso.</small></div>',
            info:           'Mostrando _START_ a _END_ de _TOTAL_ accesos farmacéuticos',
            infoEmpty:      '0 accesos',
            infoFiltered:   '(filtrado de _MAX_ totales)',
            search:         'Buscar:',
            searchPlaceholder: 'Químico, código, matrícula...',
            zeroRecords:    'No se encontraron enlaces coincidentes',
            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            lengthMenu:     'Mostrar _MENU_ registros por página'
        },
        order: [[0, 'asc']],
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [6] }
        ],
        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-3"lf>rt<"d-flex flex-wrap align-items-center justify-content-between mt-3"ip>'
    });

    $('#filtroAreaTabla').on('change', function() {
        var val = $(this).val();
        if (val === 'AREA CENTRAL') {
            dtSesiones.column(3).search('Área Central').draw();
        } else if (val === 'AREA INTERIOR') {
            dtSesiones.column(3).search('Área Interior').draw();
        } else {
            dtSesiones.column(3).search('').draw();
        }
    });

    // ── 2. DataTable Especialidades y Medicamentos ──
    var dtEspecialidades = $('#tablaEspecialidadesMedicamentos').DataTable({
        language: {
            emptyTable:     'No hay especialidades registradas.',
            info:           'Mostrando _START_ a _END_ de _TOTAL_ especialidades',
            infoEmpty:      '0 especialidades',
            infoFiltered:   '(filtrado de _MAX_ totales)',
            search:         'Buscar Especialidad:',
            searchPlaceholder: 'Especialidad, código...',
            zeroRecords:    'No se encontraron especialidades coincidentes',
            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            lengthMenu:     'Mostrar _MENU_ por página'
        },
        order: [[1, 'asc']],
        pageLength: 15,
        columnDefs: [
            { orderable: false, targets: [6] }
        ],
        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-3"lf>rt<"d-flex flex-wrap align-items-center justify-content-between mt-3"ip>'
    });

    var filtroEspecialidadVademecumValor = '';
    $('#filtroEspecialidadesVademecum').on('change', function() {
        filtroEspecialidadVademecumValor = $(this).val();
        dtEspecialidades.draw();
    });

    // ── 3. DataTable Catálogo Vademécum IPS ──
    var dtVademecum = $('#tablaCatalogoVademecum').DataTable({
        language: {
            emptyTable:     'No hay medicamentos en el Vademécum Oficial.',
            info:           'Mostrando _START_ a _END_ de _TOTAL_ medicamentos oficiales',
            infoEmpty:      '0 medicamentos',
            infoFiltered:   '(filtrado de _MAX_ totales)',
            search:         'Buscar en Vademécum:',
            searchPlaceholder: 'Principio activo, código, forma...',
            zeroRecords:    'No se encontraron medicamentos coincidentes',
            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            lengthMenu:     'Mostrar _MENU_ por página'
        },
        order: [[1, 'asc']],
        pageLength: 20,
        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-3"lf>rt<"d-flex flex-wrap align-items-center justify-content-between mt-3"ip>'
    });

    var filtroVademecumUsoValor = '';
    $('#filtroVademecumUso').on('change', function() {
        filtroVademecumUsoValor = $(this).val();
        dtVademecum.draw();
    });

    // Custom DataTables filter logic
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            // Filtro para tabla de Especialidades
            if (settings.nTable.id === 'tablaEspecialidadesMedicamentos') {
                if (!filtroEspecialidadVademecumValor) return true;
                var rowNode = dtEspecialidades.row(dataIndex).node();
                if (!rowNode) return true;
                var countV = parseInt($(rowNode).attr('data-vademecum')) || 0;
                var estado = $(rowNode).attr('data-estado') || '';

                if (filtroEspecialidadVademecumValor === 'CON_VADEMECUM') return countV > 0;
                if (filtroEspecialidadVademecumValor === 'SIN_VADEMECUM') return countV === 0;
                if (filtroEspecialidadVademecumValor === 'Activa') return estado === 'Activa';
                if (filtroEspecialidadVademecumValor === 'Inactiva') return estado === 'Inactiva';
                return true;
            }

            // Filtro para tabla de Catálogo Vademécum
            if (settings.nTable.id === 'tablaCatalogoVademecum') {
                if (!filtroVademecumUsoValor) return true;
                var rowNodeV = dtVademecum.row(dataIndex).node();
                if (!rowNodeV) return true;
                var uso = $(rowNodeV).attr('data-uso') || '';
                return uso.indexOf(filtroVademecumUsoValor) !== -1;
            }

            return true;
        }
    );

    // Ajustar columnas de DataTables al cambiar de pestaña
    $('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    // Ajustar columnas al abrir modales
    $('#modalGestionMedicamentos').on('shown.bs.modal', function () {
        if (dtMedicamentosModal) {
            dtMedicamentosModal.columns.adjust().draw();
        }
    });

    // ── Select2 para Vincular Medicamento en Modal ──
    $('#selectMedicamentoVincular').select2({
        dropdownParent: $('#modalGestionMedicamentos'),
        placeholder: '🔍 Buscar medicamento por código o nombre...',
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("riiss.validaciones.buscar-medicamentos") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
            cache: true
        }
    });

    // ── Cargar Medicamentos de Especialidad en Modal ──
    function cargarMedicamentosEspecialidad(especialidadId, especialidadNombre) {
        _especialidadGestionActualId = especialidadId;
        $('#modalEspecialidadTitulo').html(`<i class="fa fa-stethoscope mr-2"></i> ${especialidadNombre}`);
        $('#modalEspecialidadSubtitulo').text(`Gestión de medicamentos del Vademécum Oficial IPS y registros históricos`);
        $('#selectMedicamentoVincular').val(null).trigger('change');

        if (dtMedicamentosModal) {
            dtMedicamentosModal.destroy();
        }

        $('#tbodyMedicamentosEspecialidad').html(`
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x mb-2 text-info"></i>
                    <div>Cargando medicamentos asociados a ${especialidadNombre}...</div>
                </td>
            </tr>
        `);

        $('#modalGestionMedicamentos').modal('show');

        $.get(`{{ url('riiss/validaciones-especialidades/especialidad') }}/${especialidadId}/medicamentos`, function(res) {
            if (res.success) {
                $('#badgeModalTotalVademecum').text(`${res.especialidad.total_vademecum} Vademécum`);
                $('#badgeModalTotalHistoricos').text(`${res.especialidad.total_historicos} Históricos`);

                let html = '';

                // Medicamentos Vademécum Oficial
                res.vademecum.forEach(function(m) {
                    html += `
                        <tr id="fila-med-${m.id}" style="background-color: #f0fdf4;">
                            <td class="text-center font-monospace font-weight-bold text-success" style="font-size:12px;">
                                ${m.codigo || '—'}
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark" style="font-size:13px;">${m.nombre}</div>
                            </td>
                            <td style="font-size:12px;">
                                ${m.concentracion ? `<strong>${m.concentracion}</strong>` : ''} 
                                ${m.forma_farmaceutica ? `<div class="text-muted small">${m.forma_farmaceutica}</div>` : ''}
                            </td>
                            <td style="font-size:12px;">
                                ${m.via_administracion || '—'}
                            </td>
                            <td class="text-center">
                                ${m.uso_vademecum ? `<span class="badge badge-light border border-info text-info px-2 py-1 font-weight-bold" style="font-size:11px;">${m.uso_vademecum}</span>` : '<span class="text-muted small">—</span>'}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size:11.5px;">
                                    <i class="fa fa-check-circle mr-1"></i> VADEMÉCUM IPS
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="circle-btn btn btn-outline-danger btn-desvincular-med" 
                                        data-med-id="${m.id}" 
                                        data-med-nombre="${m.nombre}"
                                        title="Desvincular del Vademécum de esta especialidad">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                // Medicamentos Históricos
                res.historicos.forEach(function(m) {
                    html += `
                        <tr id="fila-med-${m.id}">
                            <td class="text-center font-monospace font-weight-bold text-muted" style="font-size:12px;">
                                ${m.codigo || '—'}
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark" style="font-size:13px;">${m.nombre}</div>
                            </td>
                            <td style="font-size:12px;">
                                ${m.concentracion ? `<strong>${m.concentracion}</strong>` : ''} 
                                ${m.forma_farmaceutica ? `<div class="text-muted small">${m.forma_farmaceutica}</div>` : ''}
                            </td>
                            <td style="font-size:12px;">
                                ${m.via_administracion || '—'}
                            </td>
                            <td class="text-center">
                                ${m.uso_vademecum ? `<span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size:11px;">${m.uso_vademecum}</span>` : '<span class="text-muted small">—</span>'}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light border border-secondary text-secondary px-2 py-1 font-weight-bold" style="font-size:11px;">
                                    <i class="fa fa-history mr-1"></i> Histórico
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="circle-btn btn btn-outline-success btn-vincular-directo-med" 
                                        data-med-id="${m.id}" 
                                        data-med-nombre="${m.nombre}"
                                        title="Vincular oficialmente al Vademécum de esta especialidad">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                if (res.vademecum.length === 0 && res.historicos.length === 0) {
                    html = `
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa fa-pills fa-2x mb-2 text-secondary" style="opacity:.4"></i>
                                <div>No hay medicamentos registrados aún para esta especialidad.</div>
                                <small>Use el buscador superior para vincular medicamentos del Vademécum IPS.</small>
                            </td>
                        </tr>
                    `;
                }

                $('#tbodyMedicamentosEspecialidad').html(html);

                // Inicializar DataTable dentro del modal si hay filas
                if (res.vademecum.length > 0 || res.historicos.length > 0) {
                    dtMedicamentosModal = $('#tablaMedicamentosEspecialidad').DataTable({
                        language: {
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ medicamentos',
                            infoEmpty: '0 medicamentos',
                            infoFiltered: '(filtrado de _MAX_ totales)',
                            search: 'Buscar Medicamento:',
                            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
                            lengthMenu: 'Mostrar _MENU_ registros'
                        },
                        pageLength: 10,
                        order: [[5, 'desc'], [1, 'asc']],
                        columnDefs: [
                            { orderable: false, targets: [6] }
                        ],
                        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-2"lf>rt<"d-flex flex-wrap align-items-center justify-content-between mt-2"ip>'
                    });
                }
            }
        }).fail(function() {
            $('#tbodyMedicamentosEspecialidad').html(`
                <tr>
                    <td colspan="7" class="text-center py-4 text-danger font-weight-bold">
                        <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                        <div>Ocurrió un error al consultar los medicamentos. Por favor reintente.</div>
                    </td>
                </tr>
            `);
        });
    }

    $(document).on('click', '.btn-gestionar-medicamentos', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        cargarMedicamentosEspecialidad(id, nombre);
    });

    // ── Ejecutar Vinculación de Medicamento ──
    $('#btnEjecutarVinculacion').on('click', function() {
        const medId = $('#selectMedicamentoVincular').val();
        if (!medId) {
            Swal.fire('Atención', 'Seleccione un medicamento del catálogo para vincular.', 'warning');
            return;
        }

        if (!_especialidadGestionActualId) return;

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Vinculando...');

        $.post('{{ route("riiss.validaciones.vincular-medicamento") }}', {
            _token: '{{ csrf_token() }}',
            especialidad_id: _especialidadGestionActualId,
            medicamento_id: medId
        }, function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-link mr-1"></i> Vincular al Vademécum');
            if (res.success) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 2200
                });

                // Actualizar badge en fila de especialidad principal
                $(`.badge-vademecum-count-${_especialidadGestionActualId}`).html(`<i class="fa fa-pills mr-1"></i> ${res.total_vademecum} Autorizados`);

                // Recargar tabla modal
                cargarMedicamentosEspecialidad(_especialidadGestionActualId, $('#modalEspecialidadTitulo').text().trim());
            }
        }).fail(function(xhr) {
            $btn.prop('disabled', false).html('<i class="fa fa-link mr-1"></i> Vincular al Vademécum');
            Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo vincular el medicamento.', 'error');
        });
    });

    // ── Vincular directo desde la lista histórica ──
    $(document).on('click', '.btn-vincular-directo-med', function() {
        const medId = $(this).data('med-id');
        const medNombre = $(this).data('med-nombre');

        if (!_especialidadGestionActualId) return;

        $.post('{{ route("riiss.validaciones.vincular-medicamento") }}', {
            _token: '{{ csrf_token() }}',
            especialidad_id: _especialidadGestionActualId,
            medicamento_id: medId
        }, function(res) {
            if (res.success) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `"${medNombre}" vinculado al Vademécum.`,
                    showConfirmButton: false,
                    timer: 2000
                });

                $(`.badge-vademecum-count-${_especialidadGestionActualId}`).html(`<i class="fa fa-pills mr-1"></i> ${res.total_vademecum} Autorizados`);
                cargarMedicamentosEspecialidad(_especialidadGestionActualId, $('#modalEspecialidadTitulo').text().trim());
            }
        });
    });

    // ── Desvincular Medicamento del Vademécum ──
    $(document).on('click', '.btn-desvincular-med', function() {
        const medId = $(this).data('med-id');
        const medNombre = $(this).data('med-nombre');

        if (!_especialidadGestionActualId) return;

        Swal.fire({
            title: '¿Desvincular del Vademécum?',
            text: `¿Desea quitar "${medNombre}" del Vademécum Oficial de esta especialidad?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-trash-alt mr-1"></i> Sí, desvincular',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("riiss.validaciones.desvincular-medicamento") }}', {
                    _token: '{{ csrf_token() }}',
                    especialidad_id: _especialidadGestionActualId,
                    medicamento_id: medId
                }, function(res) {
                    if (res.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        $(`.badge-vademecum-count-${_especialidadGestionActualId}`).html(`<i class="fa fa-pills mr-1"></i> ${res.total_vademecum} Autorizados`);
                        cargarMedicamentosEspecialidad(_especialidadGestionActualId, $('#modalEspecialidadTitulo').text().trim());
                    }
                }).fail(function() {
                    Swal.fire('Error', 'No se pudo desvincular el medicamento.', 'error');
                });
            }
        });
    });

    @if(session('nuevo_acceso'))
        var nuevoAccesoData = @json(session('nuevo_acceso'));
        abrirModalCompartirValidador({
            url: nuevoAccesoData.url_portal,
            codigo: nuevoAccesoData.codigo_acceso,
            analista: nuevoAccesoData.analista,
            cargo: nuevoAccesoData.cargo,
            telefono: nuevoAccesoData.telefono,
            area: nuevoAccesoData.area_gestion,
            depto: nuevoAccesoData.departamento
        });
    @endif

    @if(session('nuevo_acceso_farmaceutico'))
        var nuevoFarmData = @json(session('nuevo_acceso_farmaceutico'));
        Swal.fire({
            title: '¡Acceso Farmacéutico Emitido!',
            html: `
                <div class="text-left p-3 rounded mb-3" style="background:#f0fdf4; border:1.5px solid #86efac; font-size:13px;">
                    <div class="mb-2"><strong>Profesional:</strong> ${nuevoFarmData.analista}</div>
                    <div class="mb-2"><strong>Cargo:</strong> ${nuevoFarmData.cargo || 'Unidad de Regulación Farmacéutica'}</div>
                    <div class="mb-2"><strong>Matrícula:</strong> ${nuevoFarmData.matricula || 'N/D'}</div>
                    <div class="mb-2">
                        <strong>Código de Acceso PIN:</strong> 
                        <span class="badge badge-success font-monospace px-2 py-1" style="font-size:14px; letter-spacing:1px;">${nuevoFarmData.codigo_acceso}</span>
                    </div>
                    <div class="mb-0 text-truncate">
                        <strong>Enlace Directo:</strong><br>
                        <a href="${nuevoFarmData.url_portal}" target="_blank" class="text-info">${nuevoFarmData.url_portal}</a>
                    </div>
                </div>
                <div class="small text-muted">
                    El Químico Farmacéutico podrá ingresar con su PIN y auditar los medicamentos por especialidad médica.
                </div>
            `,
            icon: 'success',
            confirmButtonText: '<i class="fa fa-check mr-1"></i> Entendido',
            confirmButtonColor: '#0d9488'
        });
    @endif
});
</script>
@endpush
