@extends('layouts.master')
@section('title', 'Dashboard del Administrador — Gestión Global')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/orgchart/dist/css/jquery.orgchart.min.css') }}">
<style>
    /* ── Estilos Premium para Diagrama Visual de Organigrama In-Situ ── */
    #chart-modal-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        min-height: 520px;
        overflow: auto;
        padding: 35px 20px;
        position: relative;
    }
    .orgchart { background: transparent !important; }
    .orgchart .node {
        width: 175px !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        transition: all .25s ease !important;
        border: none !important;
    }
    .orgchart .node:hover {
        transform: translateY(-4px) scale(1.02) !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        z-index: 20 !important;
    }
    .orgchart .node .title {
        border-radius: 12px 12px 0 0 !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        padding: 8px 10px !important;
        text-transform: uppercase !important;
        line-height: 1.3 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 100% !important;
        color: #ffffff !important;
    }
    .orgchart .node.nivel-0 .title { background: linear-gradient(135deg, #1e3a8a, #3b82f6) !important; }
    .orgchart .node.nivel-1 .title { background: linear-gradient(135deg, #065f46, #10b981) !important; }
    .orgchart .node.nivel-2 .title { background: linear-gradient(135deg, #0e7490, #06b6d4) !important; }
    .orgchart .node.nivel-3 .title { background: linear-gradient(135deg, #b45309, #f59e0b) !important; }
    .orgchart .node.nivel-4 .title { background: linear-gradient(135deg, #9f1239, #f43f5e) !important; }
    .orgchart .node.nivel-5 .title { background: linear-gradient(135deg, #374151, #6b7280) !important; }

    .orgchart .node .content {
        border-radius: 0 0 12px 12px !important;
        font-size: 0.72rem !important;
        padding: 8px 10px !important;
        color: #334155 !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-top: none !important;
        min-height: 32px !important;
        font-weight: 600 !important;
    }
    .orgchart .lines .downLine { background-color: #94a3b8 !important; width: 2px !important; }
    .orgchart .lines .topLine  { border-top: 2px solid #94a3b8 !important; }
    .orgchart .lines .rightLine{ border-right: 1px solid #94a3b8 !important; }
    .orgchart .lines .leftLine { border-left: 1px solid #94a3b8 !important; }

    /* ── Tarjetas y Estilo Hero Blue ── */
    .hero-admin-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #254d8c 50%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border-radius: 14px;
        box-shadow: 0 10px 28px rgba(30, 58, 138, 0.35);
        padding: 1.75rem;
        margin-bottom: 1.75rem;
    }
    .hero-admin-card .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.85) !important;
    }
    .hero-admin-card .breadcrumb-item a:hover {
        color: #ffffff !important;
        text-decoration: underline !important;
    }
    .hero-admin-card .breadcrumb-item.active {
        color: #ffffff !important;
    }
    .hero-admin-card .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.45) !important;
    }
    .hero-admin-card .badge-context {
        background: rgba(255, 255, 255, 0.15);
        color: #f8fafc;
        backdrop-filter: blur(6px);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .hero-admin-card .select2-container--default .select2-selection--single {
        background-color: #ffffff !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        height: 38px !important;
    }
    .hero-admin-card .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        color: #0f172a !important;
        font-weight: 700 !important;
    }
    .hero-admin-card .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .kpi-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #fff;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .kpi-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .nav-pills-admin .nav-link {
        color: #475569;
        font-weight: 600;
        border-radius: 30px;
        padding: 0.6rem 1.4rem;
        margin-right: 0.5rem;
        transition: all 0.3s;
        border: 1px solid transparent;
        font-size: 0.9rem;
    }
    .nav-pills-admin .nav-link.active {
        background: #2563eb;
        color: #fff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .nav-pills-admin .nav-link:hover:not(.active) {
        background: #f1f5f9;
        color: #2563eb;
    }
    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    /* ── Árbol Jerárquico de Organigrama ── */
    .sortable-group {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .nodo-item {
        margin: 4px 0;
    }
    .nodo-row {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .nodo-row:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .nodo-children {
        padding-left: 28px;
        border-left: 2px dashed #cbd5e1;
        margin-left: 14px;
        margin-top: 4px;
    }
    .drag-handle {
        cursor: grab;
        color: #94a3b8;
        padding: 4px 8px;
        font-size: 14px;
    }
    .drag-handle:active {
        cursor: grabbing;
        color: #3b82f6;
    }
    .btn.btn-circle {
        width: 30px !important;
        height: 30px !important;
        border-radius: 50% !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Modales con Scrollbar Interno Perfecto */
    .modal-dialog-scrollable .modal-body {
        max-height: 75vh !important;
        overflow-y: auto !important;
    }

    /* Permisos Badges Estilizados */
    .perm-badge-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.8rem;
        cursor: pointer;
        user-select: none;
    }
    .perm-badge-item:hover {
        background: #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- ── Banner Hero de Contexto Institucional ── --}}
    <div class="hero-admin-card">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-1 mr-2" style="font-size: 0.85rem; border-radius: 20px;">
                        <i class="fa fa-crown mr-1"></i> Administrador Global
                    </span>
                    <span class="badge badge-light text-dark font-weight-bold px-2 py-1" style="font-size: 0.75rem;">
                        SIPLAN — IPS
                    </span>
                </div>

                <h2 class="font-weight-bold text-white mb-2" style="letter-spacing: -0.5px;">
                    {{ $selectedPei ? strip_tags($selectedPei->name) : 'Gestión Institucional Global' }}
                </h2>

                <p class="text-white-50 mb-3" style="font-size: 0.95rem;">
                    Bienvenido, <strong>{{ auth()->user()->name }}</strong>. Desde este panel central supervisás los usuarios, grupos de trabajo, organigramas orgánicos, planes PEI y la visibilidad de los módulos públicos del sistema.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    @if($selectedPei)
                        <span class="badge-context mr-2 mb-2">
                            <i class="fa fa-calendar-alt text-warning mr-1"></i> Período: <strong>{{ \Carbon\Carbon::parse($selectedPei->year_start)->format('Y') }} – {{ \Carbon\Carbon::parse($selectedPei->year_end)->format('Y') }}</strong>
                        </span>
                        <span class="badge-context mr-2 mb-2">
                            <i class="fa fa-bullseye text-info mr-1"></i> Objetivos: <strong>{{ $totalObjetivos }}</strong>
                        </span>
                        <span class="badge-context mr-2 mb-2">
                            <i class="fa fa-rocket text-success mr-1"></i> Acciones: <strong>{{ $totalAcciones }}</strong>
                        </span>
                    @else
                        <span class="badge-context mr-2 mb-2">
                            <i class="fa fa-layer-group text-warning mr-1"></i> Planes PEI Raíz: <strong>{{ $totalPeis }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Selector de Plan PEI & Accesos Directos ── --}}
            <div class="col-lg-5 text-lg-right mt-3 mt-lg-0">
                <div class="p-3 rounded-lg border text-left mb-3 shadow-sm" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(8px); border-color: rgba(255,255,255,0.25) !important;">
                    <label class="text-white small font-weight-bold text-uppercase mb-2 d-block" style="letter-spacing:.05em">
                        <i class="fa fa-filter text-warning mr-1"></i> Filtrar Panel por Plan PEI Raíz:
                    </label>
                    <select id="peiPlanFilterSelect" class="form-control select2 font-weight-bold text-dark w-100">
                        <option value="">— Todos los Planes PEI —</option>
                        @foreach($peiPerfiles as $pp)
                            <option value="{{ $pp->id }}" {{ $peiProfileId == $pp->id ? 'selected' : '' }}>
                                {{ strip_tags($pp->name) }} ({{ \Carbon\Carbon::parse($pp->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($pp->year_end)->format('Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-lg-end flex-wrap" style="gap: 0.5rem;">
                    @if($selectedPei)
                        <a href="{{ route('pei-profiles.matriz', $selectedPei->id) }}" class="btn btn-light btn-round font-weight-bold shadow-sm px-3 py-2" target="_blank">
                            <i class="fa fa-table text-primary mr-1"></i> Matriz PEI
                        </a>
                    @endif
                    <button type="button" class="btn btn-outline-light btn-round px-3 py-2" onclick="location.reload();">
                        <i class="fa fa-sync-alt mr-1"></i> Refrescar
                    </button>
                </div>
            </div>
        </div>

        {{-- Breadcrumb Integrado al pie del Card Header --}}
        <div class="pt-3 mt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(255,255,255,0.18) !important;">
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb mb-0 p-0" style="background: transparent;">
                    <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}" class="text-white-50 font-weight-bold text-decoration-none"><i class="fa fa-home mr-1 text-warning"></i>Inicio</a></li>
                    <li class="breadcrumb-item active text-white font-weight-bold" aria-current="page">Panel del Administrador Global</li>
                </ol>
            </nav>
            <span class="text-white-50 small font-weight-bold d-none d-md-inline">
                <i class="fa fa-shield-alt mr-1 text-warning"></i> Sistema de Planificación Institucional (SIPLAN)
            </span>
        </div>
    </div>

    {{-- ── Fila de KPIs Principales ── --}}
    <div class="row mb-4">
        {{-- Usuarios --}}
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <a href="javascript:void(0)" onclick="$('#tab-usuarios-link').tab('show');" class="text-decoration-none">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-box mr-3" style="background: #dbeafe; color: #1e40af;">
                            <i class="fa fa-users"></i>
                        </div>
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Usuarios Totales</div>
                            <div class="h3 font-weight-bold text-dark mb-0">{{ $totalUsuarios }}</div>
                            <small class="text-muted">{{ $totalAdmins }} admins</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Grupos de Trabajo --}}
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <a href="javascript:void(0)" onclick="$('#tab-grupos-link').tab('show');" class="text-decoration-none">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-box mr-3" style="background: #e0f2fe; color: #0369a1;">
                            <i class="fa fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Grupos de Trabajo</div>
                            <div class="h3 font-weight-bold text-dark mb-0">{{ $totalGrupos }}</div>
                            <small class="text-muted">Instancias operativas</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Estructura Orgánica --}}
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <a href="javascript:void(0)" onclick="$('#tab-organigrama-link').tab('show');" class="text-decoration-none">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-box mr-3" style="background: #fef3c7; color: #b45309;">
                            <i class="fa fa-sitemap"></i>
                        </div>
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Dependencias Orgánicas</div>
                            <div class="h3 font-weight-bold text-dark mb-0">{{ $totalDependencias }}</div>
                            <small class="text-muted">{{ $totalOrganigramas }} raíces jerárquicas</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Objetivos y Acciones del Plan --}}
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <a href="javascript:void(0)" onclick="$('#tab-planes-link').tab('show');" class="text-decoration-none">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-box mr-3" style="background: #dcfce7; color: #15803d;">
                            <i class="fa fa-bullseye"></i>
                        </div>
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Objetivos PEI</div>
                            <div class="h3 font-weight-bold text-dark mb-0">{{ $totalObjetivos }}</div>
                            <small class="text-muted">{{ $totalAcciones }} acciones del plan</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Proyectos --}}
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box mr-3" style="background: #ede9fe; color: #6d28d9;">
                        <i class="fa fa-project-diagram"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Proyectos</div>
                        <div class="h3 font-weight-bold text-dark mb-0">{{ $totalProyectos }}</div>
                        <small class="text-muted">{{ $proyectosEjecucion }} en ejecución</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pestañas de Gestión Integral ── --}}
    <div class="card shadow border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white border-bottom p-3">
            <ul class="nav nav-pills nav-pills-admin" id="adminTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-usuarios-link" data-toggle="pill" href="#tab-usuarios" role="tab" aria-selected="true">
                        <i class="fa fa-user-shield mr-2"></i> Usuarios y Accesos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-grupos-link" data-toggle="pill" href="#tab-grupos" role="tab" aria-selected="false">
                        <i class="fa fa-layer-group mr-2"></i> Grupos de Trabajo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-organigrama-link" data-toggle="pill" href="#tab-organigrama" role="tab" aria-selected="false">
                        <i class="fa fa-sitemap mr-2"></i> Estructura Orgánica
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-planes-link" data-toggle="pill" href="#tab-planes" role="tab" aria-selected="false">
                        <i class="fa fa-chart-line mr-2"></i> Planes Institucionales (PEI)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-actividades-link" data-toggle="pill" href="#tab-actividades" role="tab" aria-selected="false">
                        <i class="fa fa-rocket mr-2"></i> Actividades del PEI
                        <span class="badge badge-pill badge-primary ml-1" style="font-size:0.7rem;">{{ $totalActividadesPei }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-publico-link" data-toggle="pill" href="#tab-publico" role="tab" aria-selected="false">
                        <i class="fa fa-globe mr-2"></i> Visibilidad & Sitio Público
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-juntas-link" data-toggle="pill" href="#tab-juntas" role="tab" aria-selected="false">
                        <i class="fa fa-balance-scale mr-2"></i> Juntas Consultivas
                        <span class="badge badge-pill badge-primary ml-1" style="font-size:0.7rem;">{{ $totalJuntas }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="adminTabsContent">

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 1: USUARIOS Y ACCESOS (GESTIÓN EN MODALES)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade show active" id="tab-usuarios" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-users text-primary mr-2"></i> Usuarios y Accesos
                            </h4>
                            <p class="text-muted mb-0 small">
                                @if(!$filterAllUsers && isset($selectedGroup) && $selectedGroup)
                                    Mostrando <strong class="text-primary">{{ $usuariosList->count() }} integrantes</strong> asignados al PEI: <strong class="text-dark">{{ $selectedGroup->name }}</strong>
                                @else
                                    Mostrando <strong class="text-primary">todos los {{ $usuariosList->count() }} usuarios</strong> registrados en el sistema.
                                @endif
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0 d-flex flex-wrap align-items-center" style="gap:.5rem">
                            {{-- Switch de Ámbito: Integrantes PEI vs Todos --}}
                            <div class="btn-group btn-group-toggle shadow-sm mr-2" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-primary font-weight-bold {{ !$filterAllUsers ? 'active' : '' }}" onclick="filtrarUsuariosContexto(0)" title="Ver integrantes del PEI actual">
                                    <input type="radio" name="user_scope" {{ !$filterAllUsers ? 'checked' : '' }}>
                                    <i class="fa fa-user-check mr-1"></i> Integrantes PEI
                                </label>
                                <label class="btn btn-sm btn-outline-primary font-weight-bold {{ $filterAllUsers ? 'active' : '' }}" onclick="filtrarUsuariosContexto(1)" title="Ver todos los usuarios del sistema">
                                    <input type="radio" name="user_scope" {{ $filterAllUsers ? 'checked' : '' }}>
                                    <i class="fa fa-globe mr-1"></i> Todos los Usuarios
                                </label>
                            </div>

                            <button type="button" class="btn btn-outline-info btn-round px-3" onclick="abrirModalGestionRoles()">
                                <i class="fa fa-shield-alt mr-1"></i> Roles (<span id="cantRolesMainBtn">{{ $totalRoles }}</span>)
                            </button>
                            <button type="button" class="btn btn-outline-warning text-dark btn-round px-3" onclick="abrirModalGestionPermisos()">
                                <i class="fa fa-key mr-1"></i> Permisos (<span id="cantPermisosMainBtn">{{ count($permissionsList) }}</span>)
                            </button>
                            <button type="button" class="btn btn-primary btn-round px-3" onclick="abrirModalNuevoUsuario()">
                                <i class="fa fa-user-plus mr-1"></i> Nuevo Usuario
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100 dataTableInit" id="tablaUsuariosGlobal">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 35%;">Usuario / Email</th>
                                    <th style="width: 25%;">Grupo / Dependencia</th>
                                    <th style="width: 20%;">Roles Asignados</th>
                                    <th style="width: 15%; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosList as $idx => $u)
                                <tr id="user_row_{{ $u->id }}">
                                    <td>{{ count($usuariosList) - $idx }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="font-weight-bold text-dark">{{ $u->name }}</span>
                                            @if($u->isOnline() || (!session()->has('impersonator_id') && auth()->id() == $u->id))
                                                <span class="badge badge-success border border-white ml-2 px-2 py-1 shadow-sm" style="font-size: 0.65rem; border-radius: 12px; background-color: #10b981;" title="En Línea (Activo en los últimos 5 min)">
                                                    <i class="fa fa-circle text-white mr-1 pulse-green" style="font-size: 0.45rem;"></i> En Línea
                                                </span>
                                            @else
                                                <span class="badge badge-light border text-muted ml-2 px-2 py-1" style="font-size: 0.65rem; border-radius: 12px; background-color: #f1f5f9;" title="Desconectado">
                                                    <i class="fa fa-circle text-secondary mr-1" style="font-size: 0.45rem;"></i> Desconectado
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted"><i class="fa fa-envelope mr-1"></i>{{ $u->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-dark">
                                            <i class="fa fa-users mr-1 text-info"></i>{{ $u->group->name ?? ($u->dependency->dependency ?? '— Sin asignación —') }}
                                        </span>
                                    </td>
                                    <td>
                                        @forelse($u->roles as $r)
                                            <span class="badge badge-primary">{{ $r->name }}</span>
                                        @empty
                                            <span class="badge badge-secondary">Sin rol</span>
                                        @endforelse
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center" style="gap: 4px;">
                                            @if(auth()->id() != $u->id)
                                                <a href="{{ route('impersonate.take', $u->id) }}" class="btn btn-circle btn-warning text-dark font-weight-bold" title="👁️ Ver como {{ $u->name }} (Simular Rol)">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-circle" style="background:#6366f1; border-color:#6366f1; color:#fff;" onclick="abrirModalTelemetriaUsuario('{{ $u->id }}')" title="Telemetría & Analítica del Funcionario">
                                                <i class="fa fa-chart-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-circle btn-info" onclick="abrirModalEditarUsuario('{{ $u->id }}')" title="Editar Usuario In-Situ">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-circle btn-danger" onclick="eliminarUsuario('{{ $u->id }}', '{{ addslashes($u->name) }}')" title="Eliminar Usuario">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 2: GRUPOS DE TRABAJO (GESTIÓN EN MODALES)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-grupos" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-users-cog text-info mr-2"></i>
                                Grupos de Trabajo de: <span class="text-info">{{ $selectedGroup ? $selectedGroup->name : 'Ámbito Institucional' }}</span>
                            </h4>
                            <p class="text-muted mb-0 small">
                                Instancias subordinadas y equipos operativos. Podés gestionar, incorporar o desvincular integrantes con el botón <i class="fa fa-user-plus text-info"></i>.
                            </p>
                        </div>
                        <div class="d-flex align-items-center mt-3 mt-md-0" style="gap: 10px;">
                            @if(isset($eventosRaizList) && $eventosRaizList->count() > 0)
                            <div class="form-group mb-0">
                                <select id="eventoGroupFilterSelect" class="form-control select2 font-weight-bold border-info" style="min-width: 240px;" title="Cambiar Evento Raíz">
                                    @foreach($eventosRaizList as $ev)
                                        <option value="{{ $ev->id }}" {{ (isset($selectedGroup) && $selectedGroup && $selectedGroup->id == $ev->id) ? 'selected' : '' }}>
                                            Evento: {{ $ev->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <button type="button" class="btn btn-info btn-round shadow-sm px-3 text-white font-weight-bold" onclick="abrirModalNuevoGrupo()">
                                <i class="fa fa-plus-circle mr-1"></i> NUEVO GRUPO DE TRABAJO
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100 dataTableInit" id="tablaGruposGlobal">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">NOMBRE DEL GRUPO</th>
                                    <th style="width: 15%;">TIPO</th>
                                    <th style="width: 15%;">INTEGRANTES</th>
                                    <th style="width: 25%;">MIEMBROS ASIGNADOS</th>
                                    <th style="width: 15%; text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gruposList as $gIdx => $g)
                                <tr id="group_row_{{ $g->id }}">
                                    <td class="font-weight-bold text-center">{{ count($gruposList) - $gIdx }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark" style="font-size:0.92rem;">{{ $g->name }}</div>
                                        @if($g->description)
                                            <small class="text-muted d-block">{{ $g->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info px-2 py-1" style="font-size: 0.75rem;"><i class="fa fa-users mr-1"></i>Grupo de Trabajo</span>
                                    </td>
                                    <td style="min-width: 210px;">
                                        @if(isset($g->top_miembros) && $g->top_miembros->count() > 0)
                                            <div class="d-flex flex-column" style="gap: 3px;">
                                                @foreach($g->top_miembros as $tIdx => $topMember)
                                                    @php
                                                        $medals = ['🥇', '🥈', '🥉'];
                                                        $styles = [
                                                            'background: #fef3c7; color: #92400e; border: 1px solid #f59e0b;',
                                                            'background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;',
                                                            'background: #ffedd5; color: #9a3412; border: 1px solid #fb923c;'
                                                        ];
                                                        $medal = $medals[$tIdx] ?? '⭐';
                                                        $st = $styles[$tIdx] ?? 'background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;';
                                                    @endphp
                                                    <div class="badge d-inline-flex align-items-center justify-content-between p-1 px-2" style="{{ $st }} font-size: 0.72rem; font-weight: 600; border-radius: 6px;" title="{{ $topMember->name }} — {{ number_format($topMember->puntos_gamificacion) }} pts">
                                                        <span class="text-truncate" style="max-width: 130px;">{{ $medal }} {{ $topMember->name }}</span>
                                                        <span class="badge badge-pill badge-dark ml-2" style="font-size: 0.65rem;">{{ number_format($topMember->puntos_gamificacion) }} pts</span>
                                                    </div>
                                                @endforeach
                                                @if($g->members_count > 3)
                                                    <small class="text-muted font-weight-bold ml-1 mt-1" style="font-size: 0.68rem;">
                                                        +{{ $g->members_count - 3 }} más (Total: <span id="badge_group_members_count_{{ $g->id }}">{{ $g->members_count }}</span>)
                                                    </small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge badge-light border font-weight-bold text-muted px-2 py-1" style="font-size:0.75rem;">
                                                <i class="fa fa-users text-muted mr-1"></i> <span id="badge_group_members_count_{{ $g->id }}">{{ $g->members_count }}</span> integrantes
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($g->members->isEmpty())
                                            <span class="text-muted small">Sin miembros asignados</span>
                                        @else
                                            @foreach($g->members as $m)
                                                <span class="badge badge-light text-dark border mr-1 mb-1" style="font-size:0.75rem; font-weight:500;">
                                                    {{ $m->name }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center" style="gap: 5px;">
                                            <button type="button" class="btn btn-circle btn-info text-white" onclick="abrirModalDetalleGrupo('{{ $g->id }}', '{{ addslashes($g->name) }}')" title="Gestionar Integrantes">
                                                <i class="fa fa-user-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-circle" style="background:#8b5cf6; border-color:#8b5cf6; color:#fff;" onclick="abrirModalEditarGrupo('{{ $g->id }}')" title="Editar Grupo">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-circle btn-danger" onclick="eliminarGrupo('{{ $g->id }}', '{{ addslashes($g->name) }}')" title="Eliminar Grupo">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 3: ESTRUCTURA ORGÁNICA (ORGANIGRAMA INTERACTIVO)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-organigrama" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-sitemap text-warning mr-2"></i>
                                Estructura Orgánica de: <span class="text-warning">{{ $organigramaRaiz ? $organigramaRaiz->dependency : 'Estructura Institucional' }}</span>
                            </h4>
                            <p class="text-muted mb-0 small">
                                Árbol jerárquico interactivo. Arrastrá por el ícono <i class="fa fa-grip-vertical text-muted"></i> para reorganizar dependencias.
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0 d-flex align-items-center flex-wrap" style="gap: 6px;">
                            @if($organigramasRaizList->count() > 0)
                                <div class="mr-1" style="min-width: 240px;">
                                    <select id="organigramaSelect" class="form-control select2 font-weight-bold">
                                        @foreach($organigramasRaizList as $oRaiz)
                                            <option value="{{ $oRaiz->id }}" {{ $organigramaId == $oRaiz->id ? 'selected' : '' }}>
                                                {{ $oRaiz->dependency }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <button type="button" class="btn btn-info btn-round shadow-sm px-3 text-white mr-1" id="btnNuevoOrganigramaRaiz">
                                <i class="fa fa-plus-circle mr-1"></i> Nuevo Organigrama Raíz
                            </button>
                            <button type="button" class="btn btn-outline-info btn-round px-3 text-dark font-weight-bold mr-1 shadow-sm" onclick="abrirModalVisualOrganigrama()">
                                <i class="fa fa-sitemap mr-1 text-primary"></i> Diagrama Visual
                            </button>
                            @if($organigramaRaiz)
                                <button type="button" class="btn btn-success btn-round shadow-sm px-3" id="btnAgregarSubRaiz" data-id="{{ $organigramaRaiz->id }}" data-nombre="{{ $organigramaRaiz->dependency }}">
                                    <i class="fa fa-plus-circle mr-1"></i> Agregar Sub-dependencia
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Tarjeta del Árbol Drag & Drop --}}
                    <div class="card shadow-sm border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                            <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                <i class="fa fa-project-diagram mr-1 text-primary"></i> Estructura Jerárquica Subordinada
                                <span class="badge badge-secondary ml-1">{{ $suborganigramasPermitidos->count() }} sub-dependencias</span>
                            </span>
                            <small class="text-muted">
                                <i class="fa fa-mouse-pointer mr-1"></i> Arrastrá y soltá para modificar la dependencia superior
                            </small>
                        </div>
                        <div class="card-body p-3">
                            @if($organigramaRaiz && $organigramaRaiz->children->isNotEmpty())
                                <div id="arbolOrganigramaCoordinador" data-root-id="{{ $organigramaRaiz->id }}" data-root-name="{{ $organigramaRaiz->dependency }}">
                                    @include('admin.globales.organigramas.partials.nodo_draggable', [
                                        'nodos' => $organigramaRaiz->children,
                                        'nivel' => 0,
                                    ])
                                </div>
                            @elseif($organigramaRaiz)
                                <div class="text-center py-5">
                                    <div class="mb-3 text-muted" style="font-size: 3rem;"><i class="fa fa-sitemap"></i></div>
                                    <h5 class="text-dark font-weight-bold">No hay sub-dependencias subordinadas aún</h5>
                                    <p class="text-muted small">Hacé clic en el botón superior para agregar la primera sub-dependencia bajo <strong>{{ $organigramaRaiz->dependency }}</strong>.</p>
                                    <button type="button" class="btn btn-outline-success btn-round px-3" onclick="$('#btnAgregarSubRaiz').click();">
                                        <i class="fa fa-plus mr-1"></i> Agregar Sub-dependencia
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning">No se encontró una dependencia raíz configurada.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 4: PLANES INSTITUCIONALES (PEI RAÍZ)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-planes" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-chart-line text-success mr-2"></i> Planes Estratégicos Institucionales (PEI Raíz)
                            </h4>
                            <p class="text-muted mb-0 small">
                                Gestión global e in-situ de perfiles maestros, certificación MEF y cruce de ambientes FODA.
                            </p>
                        </div>
                        <div class="d-flex align-items-center mt-3 mt-md-0" style="gap: 10px;">
                            {{-- Switch de Ámbito: PEI Seleccionado vs Todos los Planes --}}
                            <div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
                                <label class="btn btn-sm btn-outline-primary font-weight-bold active" id="lblScopePeiSelected" onclick="filtrarPlanesScope(0)" title="Ver solo el PEI activo seleccionado">
                                    <input type="radio" name="planes_scope" checked>
                                    <i class="fa fa-bullseye text-success mr-1"></i> PEI Seleccionado
                                </label>
                                <label class="btn btn-sm btn-outline-primary font-weight-bold" id="lblScopePeiAll" onclick="filtrarPlanesScope(1)" title="Ver todos los planes del sistema">
                                    <input type="radio" name="planes_scope">
                                    <i class="fa fa-globe mr-1"></i> Todos los Planes Raíz
                                </label>
                            </div>

                            <button type="button" class="btn btn-success btn-round px-3 text-white font-weight-bold shadow-sm" id="createNewProfile">
                                <i class="fa fa-plus-circle mr-1"></i> NUEVO PERFIL PEI
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100 dataTableInit" id="tablaPlanesGlobal">
                            <thead>
                                <tr>
                                    <th style="width: 4%;">#</th>
                                    <th style="width: 30%;">NOMBRE DEL PLAN ESTRATÉGICO</th>
                                    <th style="width: 22%;">DEPENDENCIA / GRUPO</th>
                                    <th style="width: 14%;">PROGRESO</th>
                                    <th style="width: 10%;">ESTADO</th>
                                    <th style="width: 20%; text-align: center;">ACCIONES DIRECTAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peiPerfiles as $pIdx => $plan)
                                <tr id="pei_row_{{ $plan->id }}" class="pei-row {{ ($selectedPei && $selectedPei->id == $plan->id) ? 'pei-row-active' : 'pei-row-other' }}">
                                    <td class="font-weight-bold text-center">{{ $pIdx + 1 }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark" style="font-size:0.93rem;">{{ strip_tags($plan->name) }}</div>
                                        <div class="d-flex align-items-center flex-wrap mt-1" style="gap:5px;">
                                            <span class="badge badge-info" style="font-size:0.7rem;">
                                                <i class="fa fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($plan->year_start)->format('Y-m-d') }} - {{ \Carbon\Carbon::parse($plan->year_end)->format('Y-m-d') }}
                                            </span>
                                            @if($selectedPei && $selectedPei->id == $plan->id)
                                                <span class="badge badge-success font-weight-bold" style="font-size:0.7rem;"><i class="fa fa-check-circle mr-1"></i> PEI Seleccionado</span>
                                            @else
                                                <span class="badge badge-secondary" style="font-size:0.7rem;">Disponible</span>
                                            @endif
                                        </div>
                                        @if($plan->description)
                                            <small class="text-muted d-block mt-1 text-truncate" style="max-width:350px;">{{ strip_tags($plan->description) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="badge badge-light border text-dark p-2 mb-1" style="font-size:0.75rem;">
                                            <i class="fa fa-sitemap text-info mr-1"></i> {{ $plan->dependency->dependency ?? 'Monitoreo de Plan Estratégico' }}
                                        </div>
                                        <div class="small text-muted font-weight-bold">
                                            <i class="fa fa-users text-primary mr-1"></i> {{ $plan->group->name ?? 'Dirección de Planificación - IPS' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 mr-2" style="height: 8px; border-radius: 10px; background-color: #e2e8f0;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="font-weight-bold small text-muted">0%</span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $isActive = isset($plan->is_active) ? (bool)$plan->is_active : true;
                                        @endphp
                                        <span id="badge_status_pei_{{ $plan->id }}" class="badge {{ $isActive ? 'badge-success' : 'badge-warning' }} px-2 py-1 font-weight-bold" style="font-size: 0.75rem;">
                                            <i class="fa {{ $isActive ? 'fa-check-circle' : 'fa-eye-slash' }} mr-1"></i> {{ $isActive ? 'Activo' : 'Oculto' }}
                                        </span>
                                    </td>
                                    <td class="text-center" style="white-space: nowrap;">
                                        <div class="d-flex justify-content-center align-items-center flex-nowrap" style="gap: 4px;">
                                            {{-- 1. Seleccionar PEI Activo --}}
                                            <a href="?pei_id={{ $plan->id }}#tab-planes" class="btn btn-circle" style="background:#2563eb; border-color:#2563eb; color:#fff;" title="Seleccionar como PEI Activo en Panel">
                                                <i class="fa fa-check-circle"></i>
                                            </a>

                                            {{-- 2. Editar Perfil PEI (Modal Original pei-profiles) --}}
                                            <button type="button" class="btn btn-circle editProfile" style="background:#8b5cf6; border-color:#8b5cf6; color:#fff;" data-id="{{ $plan->id }}" title="Editar Perfil PEI In-Situ">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            {{-- 3. Ver y Gestionar Estructura PEI (Icono Números Verde) --}}
                                            <a href="{{ url('pei-profiles/' . $plan->id) }}" class="btn btn-circle" style="background:#10b981; border-color:#10b981; color:#fff;" title="Ver y Gestionar Estructura PEI">
                                                <i class="fa fa-list-ol"></i>
                                            </a>

                                            {{-- 4. Certificación MEF --}}
                                            <button type="button" class="btn btn-circle btn-info text-white btnVerCertificacionMef" data-id="{{ $plan->id }}" data-name="{{ addslashes(strip_tags($plan->name)) }}" title="Certificación MEF">
                                                <i class="fa fa-certificate"></i>
                                            </button>

                                            @hasanyrole('Administrador|Super Admin|Coordinador de Planificación|Coordinación de Planificación|Analista de Planificación|Analista PEI')
                                            {{-- Lectura Cómoda de Aportes de Asesoría --}}
                                            <button type="button" class="btn btn-circle btn-dark text-warning btnVerReporteAportes" data-pei-id="{{ $plan->id }}" title="Lectura Cómoda de Aportes y Dictámenes de Asesoría">
                                                <i class="fa fa-book-open"></i>
                                            </button>
                                            @endhasanyrole

                                            {{-- 5. Cruce de Ambientes FODA --}}
                                            <button type="button" class="btn btn-circle btn-warning text-white btnVerFodaCrossing" data-url="{{ route('foda-cruce-ambientes', $plan->id) }}" data-name="{{ addslashes(strip_tags($plan->name)) }}" title="Análisis FODA & Cruce de Ambientes">
                                                <i class="fa fa-random"></i>
                                            </button>

                                            {{-- 6. Visibilidad / Alternar Estado --}}
                                            @php
                                                $isVis = isset($plan->is_active) ? (bool)$plan->is_active : true;
                                            @endphp
                                            <button type="button" class="btn btn-circle toggleShowRiiss" style="background: {{ $isVis ? '#06b6d4' : '#f59e0b' }}; border-color: {{ $isVis ? '#06b6d4' : '#f59e0b' }}; color:#fff;" data-id="{{ $plan->id }}" title="{{ $isVis ? 'Visible / Activo — Clic para Ocultar' : 'Oculto — Clic para Activar' }}">
                                                <i class="fa {{ $isVis ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            </button>

                                            {{-- 7. Eliminar Perfil PEI --}}
                                            <button type="button" class="btn btn-circle btn-danger deleteProfile" data-id="{{ $plan->id }}" data-name="{{ addslashes(strip_tags($plan->name)) }}" title="Eliminar Perfil PEI">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 6: ACTIVIDADES DEL PEI (CONTEXTO PEI ACTIVO)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-actividades" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-rocket text-primary mr-2"></i> Actividades Institucionales del PEI
                            </h4>
                            <p class="text-muted mb-0 small">
                                Monitoreo operativo de actividades, tareas y responsables vinculados al plan: 
                                <strong class="text-primary">{{ $selectedPei ? strip_tags($selectedPei->name) : 'Todos los Planes' }}</strong>
                            </p>
                        </div>
                        <div class="d-flex align-items-center mt-3 mt-md-0" style="gap: 10px;">
                            <a href="{{ route('globales.activities.create') }}{{ $selectedPei ? '?pei_profile_id='.$selectedPei->id : '' }}" class="btn btn-primary btn-round px-3 text-white font-weight-bold shadow-sm">
                                <i class="fa fa-plus-circle mr-1"></i> NUEVA ACTIVIDAD PEI
                            </a>
                        </div>
                    </div>

                    {{-- Cards de Resumen de Estado de Actividades --}}
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card border-0 shadow-xs p-3 text-center" style="background:#f8fafc; border-radius:12px; border-left:4px solid #2563eb !important;">
                                <small class="text-muted text-uppercase font-weight-bold" style="font-size:0.68rem;">Total Actividades PEI</small>
                                <div class="h3 font-weight-bold text-dark mb-0">{{ $totalActividadesPei }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card border-0 shadow-xs p-3 text-center" style="background:#ecfdf5; border-radius:12px; border-left:4px solid #059669 !important;">
                                <small class="text-uppercase font-weight-bold" style="font-size:0.68rem; color:#047857;">Ejecutadas / Completadas</small>
                                <div class="h3 font-weight-bold text-success mb-0">{{ $actividadesEjecutadasPei }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card border-0 shadow-xs p-3 text-center" style="background:#fffbeb; border-radius:12px; border-left:4px solid #f59e0b !important;">
                                <small class="text-uppercase font-weight-bold" style="font-size:0.68rem; color:#b45309;">En Curso / En Proceso</small>
                                <div class="h3 font-weight-bold text-warning mb-0">{{ $actividadesEnCursoPei }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card border-0 shadow-xs p-3 text-center" style="background:#f1f5f9; border-radius:12px; border-left:4px solid #64748b !important;">
                                <small class="text-muted text-uppercase font-weight-bold" style="font-size:0.68rem;">Pendientes</small>
                                <div class="h3 font-weight-bold text-secondary mb-0">{{ $actividadesPendientesPei }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Barra de Filtros In-Situ --}}
                    <div class="card shadow-xs border mb-3" style="border-radius:12px; background:#ffffff;">
                        <div class="card-body p-2.5 d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                            <div class="d-flex align-items-center flex-wrap" style="gap:5px;">
                                <span class="text-muted small font-weight-bold mr-2"><i class="fa fa-filter text-warning mr-1"></i> Estado:</span>
                                <button type="button" class="btn btn-sm btn-dark active btn-filter-act-tab" data-status="all" onclick="filtrarActividadesTabStatus(this, 'all')">Todas ({{ $totalActividadesPei }})</button>
                                <button type="button" class="btn btn-sm btn-outline-success btn-filter-act-tab" data-status="EJECUTADO" onclick="filtrarActividadesTabStatus(this, 'EJECUTADO')">Ejecutadas ({{ $actividadesEjecutadasPei }})</button>
                                <button type="button" class="btn btn-sm btn-outline-warning btn-filter-act-tab" data-status="EN CURSO" onclick="filtrarActividadesTabStatus(this, 'EN CURSO')">En Curso ({{ $actividadesEnCursoPei }})</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-filter-act-tab" data-status="PENDIENTE" onclick="filtrarActividadesTabStatus(this, 'PENDIENTE')">Pendientes ({{ $actividadesPendientesPei }})</button>
                            </div>
                        </div>
                    </div>

                    {{-- Listado de Actividades --}}
                    @if($actividadesPeiList->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100 dataTableInit" id="tablaActividadesPeiDashboard">
                            <thead>
                                <tr>
                                    <th style="width: 4%;">#</th>
                                    <th style="width: 32%;">ACTIVIDAD INSTITUCIONAL / DESCRIPCIÓN</th>
                                    <th style="width: 20%;">PLAN / GRUPO VINCULADO</th>
                                    <th style="width: 16%;">AVANCE DE TAREAS</th>
                                    <th style="width: 10%;">ESTADO</th>
                                    <th style="width: 18%; text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($actividadesPeiList as $aIdx => $act)
                                <tr class="act-tab-row" data-status="{{ $act->estado_label }}" data-text="{{ strtolower($act->name . ' ' . $act->description . ' ' . ($act->peiProfile->name ?? '') . ' ' . ($act->group->name ?? '')) }}">
                                    <td class="font-weight-bold text-center">{{ $aIdx + 1 }}</td>
                                    <td>
                                        <a href="{{ route('globales.activities.show', $act->id) }}" class="font-weight-bold text-dark d-block" style="font-size:0.92rem; text-decoration:none;">
                                            <i class="fa fa-rocket text-primary mr-1"></i> {{ $act->name }}
                                        </a>
                                        @if($act->description)
                                            <small class="text-muted d-block mt-0.5 text-truncate" style="max-width:400px;">{{ strip_tags($act->description) }}</small>
                                        @endif
                                        <div class="d-flex align-items-center mt-1 flex-wrap" style="gap:6px;">
                                            @if($act->date_start)
                                                <span class="badge badge-light border text-muted" style="font-size:0.68rem;">
                                                    <i class="fa fa-calendar-alt mr-1 text-info"></i>{{ \Carbon\Carbon::parse($act->date_start)->format('d/m/Y') }}
                                                    {{ $act->date_end ? ' - ' . \Carbon\Carbon::parse($act->date_end)->format('d/m/Y') : '' }}
                                                </span>
                                            @endif
                                            @if($act->responsibles->isNotEmpty())
                                                <span class="badge badge-light border text-dark" style="font-size:0.68rem;" title="{{ $act->responsibles->pluck('name')->implode(', ') }}">
                                                    <i class="fa fa-user-check text-success mr-1"></i> {{ $act->responsibles->first()->name }} {{ $act->responsibles->count() > 1 ? '(+' . ($act->responsibles->count() - 1) . ')' : '' }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-light border text-primary p-1.5 mb-1 d-block text-truncate" style="font-size:0.72rem; max-width:240px;" title="{{ $act->peiProfile->name ?? 'Sin PEI asignado' }}">
                                            <i class="fa fa-chart-line mr-1"></i> {{ $act->peiProfile ? strip_tags($act->peiProfile->name) : 'Sin PEI' }}
                                        </div>
                                        <small class="text-muted font-weight-bold d-block">
                                            <i class="fa fa-users text-info mr-1"></i> {{ $act->group->name ?? 'Sin grupo' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <small class="font-weight-bold text-dark" style="font-size:0.75rem;">{{ $act->tasks_completed_count }} de {{ $act->tasks_count }} tareas</small>
                                            <strong class="small text-info">{{ $act->progreso_pct }}%</strong>
                                        </div>
                                        <div class="progress" style="height: 7px; border-radius: 10px; background-color: #e2e8f0;">
                                            <div class="progress-bar {{ $act->progreso_pct == 100 ? 'bg-success' : 'bg-info' }}" role="progressbar" style="width: {{ $act->progreso_pct }}%;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $act->estado_badge }} px-2 py-1 font-weight-bold" style="font-size: 0.73rem;">
                                            {{ $act->estado_label }}
                                        </span>
                                    </td>
                                    <td class="text-center" style="white-space: nowrap;">
                                        <div class="d-flex justify-content-center align-items-center flex-nowrap" style="gap: 4px;">
                                            <a href="{{ route('globales.activities.show', $act->id) }}" class="btn btn-circle btn-info" title="Ver Actividad y Tareas Operativas">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('globales.activities.edit', $act->id) }}" class="btn btn-circle btn-warning text-dark" title="Editar Actividad">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="card border-0 shadow-xs p-5 text-center bg-white" style="border-radius:12px;">
                        <div class="text-muted mb-3" style="font-size:3rem;"><i class="fa fa-rocket text-primary" style="opacity:0.5;"></i></div>
                        <h5 class="font-weight-bold text-dark">No hay actividades vinculadas a este plan PEI aún</h5>
                        <p class="text-muted small max-w-md mx-auto">Creá la primera actividad institucional para comenzar a cargar tareas y dar seguimiento operativo al PEI.</p>
                        <div>
                            <a href="{{ route('globales.activities.create') }}{{ $selectedPei ? '?pei_profile_id='.$selectedPei->id : '' }}" class="btn btn-primary btn-round px-4 font-weight-bold">
                                <i class="fa fa-plus-circle mr-1"></i> Crear Actividad PEI
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 5: VISIBILIDAD & SITIO PÚBLICO (FODA, PEI, RIISS)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-publico" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-globe text-primary mr-2"></i> Configuración del Sitio Público
                            </h4>
                            <p class="text-muted mb-0 small">
                                Control de visibilidad de módulos públicos (FODA, PEI, RIISS).
                            </p>
                        </div>
                        <div>
                            <small class="text-muted"><i class="fa fa-bolt mr-1 text-success"></i>Los cambios se aplican al instante</small>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Switch Módulo FODA --}}
                        <div class="col-md-4 mb-4">
                            <div class="card border shadow-sm h-100 {{ $config->show_foda ? 'border-success' : 'border-secondary' }}" id="card_foda">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center" style="gap:.6rem">
                                            <i class="fa fa-search fa-2x text-danger mr-2"></i>
                                            <div>
                                                <div class="font-weight-bold text-dark h5 mb-0">Módulo FODA</div>
                                                <small class="text-muted">Análisis estratégico en sitio público</small>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input cfg-toggle"
                                                   id="sw_foda" data-campo="show_foda"
                                                   {{ $config->show_foda ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="sw_foda"></label>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="small font-weight-bold text-muted text-uppercase" style="font-size:.72rem">Perfil FODA Activo</label>
                                        <select id="sel_foda_profile" class="form-control form-control-sm cfg-select" data-campo="foda_profile_id" style="width:100%">
                                            <option value="">— Seleccionar perfil —</option>
                                            @foreach($fodaPerfiles as $fp)
                                            <option value="{{ $fp->id }}" {{ $config->foda_profile_id == $fp->id ? 'selected' : '' }}>
                                                {{ $fp->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Switch Módulo PEI --}}
                        <div class="col-md-4 mb-4">
                            <div class="card border shadow-sm h-100 {{ $config->show_pei ? 'border-success' : 'border-secondary' }}" id="card_pei">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center" style="gap:.6rem">
                                            <i class="fa fa-chart-line fa-2x text-info mr-2"></i>
                                            <div>
                                                <div class="font-weight-bold text-dark h5 mb-0">Módulo PEI</div>
                                                <small class="text-muted">Plan Estratégico en sitio público</small>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input cfg-toggle"
                                                   id="sw_pei" data-campo="show_pei"
                                                   {{ $config->show_pei ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="sw_pei"></label>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="small font-weight-bold text-muted text-uppercase" style="font-size:.72rem">Plan PEI Activo</label>
                                        <select id="sel_pei_profile" class="form-control form-control-sm cfg-select" data-campo="pei_profile_id" style="width:100%">
                                            <option value="">— Seleccionar plan —</option>
                                            @foreach($peiPerfiles as $pp)
                                            <option value="{{ $pp->id }}" {{ $config->pei_profile_id == $pp->id ? 'selected' : '' }}>
                                                {{ strip_tags($pp->name) }} ({{ \Carbon\Carbon::parse($pp->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($pp->year_end)->format('Y') }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Switch Módulo RIISS --}}
                        <div class="col-md-4 mb-4">
                            <div class="card border shadow-sm h-100 {{ $config->show_riiss ? 'border-success' : 'border-secondary' }}" id="card_riiss">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center" style="gap:.6rem">
                                            <i class="fa fa-hospital fa-2x text-success mr-2"></i>
                                            <div>
                                                <div class="font-weight-bold text-dark h5 mb-0">Módulo RIISS</div>
                                                <small class="text-muted">Red Integrada de Servicios de Salud</small>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input cfg-toggle"
                                                   id="sw_riiss" data-campo="show_riiss"
                                                   {{ $config->show_riiss ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="sw_riiss"></label>
                                        </div>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fa fa-info-circle mr-1"></i> Muestra u oculta la evaluación de RIISS en la portada pública.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 7: JUNTAS CONSULTIVAS (CONSEJO DE SABIOS)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-juntas" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-balance-scale text-primary mr-2"></i> Juntas Consultivas (Consejo de Sabios)
                            </h4>
                            <p class="text-muted mb-0 small">
                                Gestión de Comités Consultivos Institucionales, Fines, Atribuciones y Miembros Asignados del IPS.
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0 d-flex flex-wrap align-items-center" style="gap: 8px;">
                            <a href="{{ route('admin.juntas.intervenciones') }}" class="btn btn-outline-info btn-round px-3 font-weight-bold">
                                <i class="fa fa-inbox mr-1"></i> Bandeja de Dictámenes / Intervenciones
                            </a>
                            <button type="button" class="btn btn-primary btn-round px-3 shadow-sm font-weight-bold" onclick="abrirModalNuevaJunta()">
                                <i class="fa fa-plus-circle mr-1"></i> Nueva Junta Consultiva
                            </button>
                        </div>
                    </div>

                    {{-- KPIs de Juntas --}}
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card kpi-card p-3 border-left border-primary" style="border-left-width:4px !important;">
                                <div class="d-flex align-items-center">
                                    <div class="kpi-icon-box mr-3" style="background:#e0e7ff; color:#3730a3;">
                                        <i class="fa fa-landmark"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Juntas Configuradas</div>
                                        <div class="h3 font-weight-bold text-dark mb-0">{{ $totalJuntas }}</div>
                                        <small class="text-muted">{{ $totalJuntasActivas }} activas en el sistema</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card kpi-card p-3 border-left border-success" style="border-left-width:4px !important;">
                                <div class="d-flex align-items-center">
                                    <div class="kpi-icon-box mr-3" style="background:#dcfce7; color:#15803d;">
                                        <i class="fa fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Integrantes / Consultores</div>
                                        <div class="h3 font-weight-bold text-dark mb-0">{{ $juntasList->pluck('integrantes')->flatten()->unique('id')->count() }}</div>
                                        <small class="text-muted">Especialistas asignados</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card kpi-card p-3 border-left border-warning" style="border-left-width:4px !important;">
                                <div class="d-flex align-items-center">
                                    <div class="kpi-icon-box mr-3" style="background:#fef3c7; color:#b45309;">
                                        <i class="fa fa-file-signature"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small font-weight-bold text-uppercase">Expedientes Intervenidos</div>
                                        <div class="h3 font-weight-bold text-dark mb-0">{{ $totalIntervencionesJuntas }}</div>
                                        <small class="text-muted">Dictámenes en alertas rojas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de Juntas --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100 dataTableInit" id="tablaJuntasGlobal">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Junta / Programa</th>
                                    <th style="width: 25%;">Fines y Atribuciones</th>
                                    <th style="width: 15%;">Ámbito Competencia</th>
                                    <th style="width: 20%;">Presidente & Integrantes</th>
                                    <th style="width: 10%; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($juntasList as $jIdx => $jta)
                                <tr id="junta_row_{{ $jta->id }}">
                                    <td class="font-weight-bold text-center">{{ count($juntasList) - $jIdx }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark" style="font-size: 0.93rem;">{{ $jta->nombre }}</div>
                                        <div class="d-flex align-items-center mt-1" style="gap:4px;">
                                            <span class="badge badge-light border text-muted" style="font-size:0.7rem;">{{ $jta->codigo }}</span>
                                            @php
                                                $progColors = ['salud'=>'danger','jubilaciones'=>'warning','finanzas'=>'success','institucional'=>'info'];
                                            @endphp
                                            <span class="badge badge-{{ $progColors[$jta->programa] ?? 'secondary' }} text-uppercase px-2" style="font-size:0.68rem;">
                                                {{ $jta->programa }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($jta->fines)
                                            <div class="small text-dark font-weight-bold"><i class="fa fa-bullseye text-primary mr-1"></i>{{ \Illuminate\Support\Str::limit($jta->fines, 70) }}</div>
                                        @endif
                                        @if($jta->atribuciones)
                                            <div class="small text-muted"><i class="fa fa-gavel text-warning mr-1"></i>{{ \Illuminate\Support\Str::limit($jta->atribuciones, 70) }}</div>
                                        @elseif($jta->descripcion)
                                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit($jta->descripcion, 70) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-dark font-weight-bold">
                                            <i class="fa fa-compass mr-1 text-info"></i>{{ $jta->ambito_competencia ?: 'Institucional Global' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small font-weight-bold text-dark">
                                            <i class="fa fa-user-tie text-primary mr-1"></i>{{ $jta->presidente_nombre }}
                                        </div>
                                        <small class="text-muted d-block" style="font-size:0.72rem;">{{ $jta->presidente_cargo }}</small>
                                        <div class="mt-1">
                                            @if($jta->integrantes->isNotEmpty())
                                                @foreach($jta->integrantes->take(3) as $m)
                                                    <span class="badge badge-light border text-dark mr-1 mb-1" style="font-size:0.7rem;">
                                                        <i class="fa fa-user text-success mr-1"></i>{{ $m->name }}
                                                    </span>
                                                @endforeach
                                                @if($jta->integrantes->count() > 3)
                                                    <span class="badge badge-secondary" style="font-size:0.68rem;">+{{ $jta->integrantes->count() - 3 }} más</span>
                                                @endif
                                            @else
                                                <small class="text-muted italic"><i class="fa fa-user-slash mr-1"></i>Sin integrantes asignados</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center" style="gap: 5px;">
                                            <button type="button" class="btn btn-circle btn-info text-white" onclick="abrirModalEditarJunta('{{ $jta->id }}')" title="Editar Junta Consultiva">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.juntas.intervenciones', ['junta_id' => $jta->id]) }}" class="btn btn-circle btn-warning text-dark" title="Ver Bandeja de Dictámenes">
                                                <i class="fa fa-inbox"></i>
                                            </a>
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

</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE GESTIÓN DE USUARIO IN-SITU
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalUsuarioDashboard" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0 rounded-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e293b, #334155);">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalUserHeading">
                    <i class="fa fa-user-plus mr-2"></i> Nuevo Usuario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="formUsuarioDashboard">
                    @csrf
                    <input type="hidden" name="user_id" id="modal_user_id">

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="modal_user_name" class="form-control" required placeholder="Ej: Dr. Juan Pérez">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="modal_user_email" class="form-control" required placeholder="ejemplo@ips.gov.py">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Contraseña</label>
                            <input type="password" name="password" id="modal_user_password" class="form-control" placeholder="••••••••">
                            <small class="text-muted d-block" style="font-size:0.72rem">Dejar en blanco para mantener la actual</small>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Confirmar Contraseña</label>
                            <input type="password" name="confirm-password" id="modal_user_confirm_password" class="form-control" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Grupo de Trabajo</label>
                        <select name="group_id" id="modal_user_group_id" class="form-control select2InModalGroup" style="width: 100%;">
                            <option value="">— Ninguno / Sin asignación —</option>
                            @foreach($allGroups as $grp)
                                <option value="{{ $grp->id }}">{{ $grp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark small mb-1">Roles Asignados</label>
                        <select name="roles[]" id="modal_user_roles" class="form-control select2InModalRoles" multiple style="width: 100%;">
                            @foreach($allRoles as $rl)
                                <option value="{{ $rl->name }}">{{ $rl->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end" style="gap:.5rem">
                        <button type="button" class="btn btn-light btn-round px-4" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-round px-4" id="btnGuardarUserModal">
                            <i class="fa fa-save mr-1"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE CREACIÓN / EDICIÓN DE JUNTA CONSULTIVA IN-SITU
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalJuntaConsultiva" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0 rounded-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalJuntaHeading">
                    <i class="fa fa-balance-scale mr-2"></i> Nueva Junta Consultiva
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="formJuntaConsultiva" onsubmit="guardarJuntaConsultiva(event)">
                    @csrf
                    <input type="hidden" name="id" id="modal_junta_id">

                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Nombre de la Junta Consultiva <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="modal_junta_nombre" class="form-control" required placeholder="Ej: Junta Consultiva de Salud y Servicios Médicos">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Programa / Eje <span class="text-danger">*</span></label>
                            <select name="programa" id="modal_junta_programa" class="form-control font-weight-bold">
                                <option value="salud">Salud y Servicios Médicos</option>
                                <option value="jubilaciones">Jubilaciones y Pensiones</option>
                                <option value="finanzas">Administración y Finanzas</option>
                                <option value="institucional">Institucional / General</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Ámbito de Competencia</label>
                        <input type="text" name="ambito_competencia" id="modal_junta_ambito" class="form-control" placeholder="Ej: Red de Hospitales Nacionales, Dirección de Jubilaciones, Infraestructura...">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Fines para los que se creó</label>
                            <textarea name="fines" id="modal_junta_fines" class="form-control" rows="3" placeholder="Describir los fines principales y objetivos de la Junta..."></textarea>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Atribuciones de la Junta</label>
                            <textarea name="atribuciones" id="modal_junta_atribuciones" class="form-control" rows="3" placeholder="Describir atribuciones, facultades de dictamen y alcance..."></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Nombre del Presidente <span class="text-danger">*</span></label>
                            <input type="text" name="presidente_nombre" id="modal_junta_presi_nombre" class="form-control" required placeholder="Ej: Dr. Carlos Gustavo Benítez">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Cargo del Presidente <span class="text-danger">*</span></label>
                            <input type="text" name="presidente_cargo" id="modal_junta_presi_cargo" class="form-control" required placeholder="Ej: Presidente de la Junta Consultiva de Salud">
                        </div>
                    </div>

                    {{-- Cargar Integrantes desde SIPLAN + Botón Crear Nuevo Usuario --}}
                    <div class="card border shadow-sm mb-4" style="border-radius:10px;">
                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-dark small text-uppercase">
                                <i class="fa fa-users text-primary mr-1"></i> Integrantes de la Junta (Usuarios SIPLAN)
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold py-0" data-toggle="collapse" data-target="#collapseNuevoUsuarioJunta" aria-expanded="false">
                                <i class="fa fa-user-plus mr-1"></i> + Cargar Nuevo Usuario en SIPLAN
                            </button>
                        </div>
                        <div class="card-body p-3">
                            {{-- Formulario Desplegable Inline para Crear Usuario al Vuelo --}}
                            <div class="collapse mb-3 p-3 bg-light border border-success rounded" id="collapseNuevoUsuarioJunta">
                                <h6 class="font-weight-bold text-success mb-2" style="font-size:0.85rem;">
                                    <i class="fa fa-user-plus mr-1"></i> Registrar Nuevo Usuario en el Sistema SIPLAN
                                </h6>
                                <div class="row">
                                    <div class="col-md-5 form-group mb-2">
                                        <label class="small font-weight-bold mb-1">Nombre y Apellido</label>
                                        <input type="text" id="quick_user_name" class="form-control form-control-sm" placeholder="Ej: Dra. María Solís">
                                    </div>
                                    <div class="col-md-5 form-group mb-2">
                                        <label class="small font-weight-bold mb-1">Correo Electrónico</label>
                                        <input type="email" id="quick_user_email" class="form-control form-control-sm" placeholder="msolis@ips.gov.py">
                                    </div>
                                    <div class="col-md-2 form-group mb-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-success font-weight-bold w-100" id="btnGuardarQuickUser" onclick="guardarNuevoUsuarioInline()">
                                            <i class="fa fa-save"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted"><i class="fa fa-info-circle mr-1"></i>Se creará la cuenta en el sistema y se agregará inmediatamente a la lista de integrantes de la Junta.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Seleccionar Integrantes</label>
                                <select name="integrantes[]" id="modal_junta_integrantes" class="form-control select2InModalJunta" multiple style="width: 100%;">
                                    @foreach($usuariosList as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="gap:.5rem">
                        <button type="button" class="btn btn-light btn-round px-4" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-round px-4" id="btnGuardarJuntaModal">
                            <i class="fa fa-save mr-1"></i> Guardar Junta Consultiva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE GESTIÓN DE GRUPO IN-SITU
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalGrupoDashboard" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0 rounded-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalGroupHeading">
                    <i class="fa fa-layer-group mr-2"></i> Nuevo Grupo de Trabajo
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="formGrupoDashboard">
                    @csrf
                    <input type="hidden" name="group_id" id="modal_group_id">
                    <input type="hidden" name="parent_id" id="modal_group_parent_id" value="{{ $selectedGroup->id ?? '' }}">

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Nombre del Grupo <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="modal_group_name" class="form-control" required placeholder="Ej: Comité de Evaluación PEI">
                    </div>

                    <div class="d-flex justify-content-end" style="gap:.5rem">
                        <button type="button" class="btn btn-light btn-round px-4" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info btn-round px-4" id="btnGuardarGroupModal">
                            <i class="fa fa-save mr-1"></i> Guardar Grupo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE INTEGRANTES Y SUBGRUPOS (DETALLE DE GRUPO IN-SITU)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalDetalleGrupoSub" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0 rounded-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-users mr-2"></i> Grupo: <span id="nombreGrupoSubHeading" class="text-warning"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <ul class="nav nav-tabs mb-3" id="grupoDetalleTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="tab-miembros-grupo-link" data-toggle="tab" href="#tab-miembros-grupo" role="tab">
                            <i class="fa fa-users text-info mr-1"></i> Integrantes Directos (<span id="cantMiembrosGrupo">0</span>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="tab-subgrupos-grupo-link" data-toggle="tab" href="#tab-subgrupos-grupo" role="tab">
                            <i class="fa fa-layer-group text-warning mr-1"></i> Subgrupos Hijos (<span id="cantSubgruposGrupo">0</span>)
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="grupoDetalleTabsContent">
                    {{-- 1. Integrantes Directos con Select2 y DataTables --}}
                    <div class="tab-pane fade show active" id="tab-miembros-grupo" role="tabpanel">
                        <form id="formAsignarIntegrantesSubgrupo" class="mb-4">
                            @csrf
                            <input type="hidden" name="group_id" id="modal_detalle_subgrupo_id">
                            
                            <div class="card border shadow-sm mb-3">
                                <div class="card-header bg-light py-2 px-3">
                                    <span class="font-weight-bold text-dark small text-uppercase">
                                        <i class="fa fa-user-plus text-info mr-1"></i> Asignar Integrantes al Subgrupo (Select2)
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark small mb-1">Buscar y Seleccionar Integrantes</label>
                                        <select name="user_id[]" id="modal_subgrupo_users_select2" class="form-control" multiple style="width: 100%;">
                                            @foreach($usuariosList as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1">Selecciona o remueve usuarios para gestionar el equipo del subgrupo.</small>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info font-weight-bold text-white btn-round px-4" id="btnGuardarIntegrantesSubgrupo">
                                            <i class="fa fa-save mr-1"></i> Guardar Integrantes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="card border shadow-sm">
                            <div class="card-header bg-light py-2 px-3">
                                <span class="font-weight-bold text-dark small text-uppercase">
                                    <i class="fa fa-list text-primary mr-1"></i> Integrantes Asignados (DataTables)
                                </span>
                            </div>
                            <div class="card-body p-3">
                                <div id="contenedorMiembrosGrupo" class="table-responsive">
                                    <table class="table table-hover table-custom w-100 mb-0" id="tablaMiembrosGrupoModal">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">#</th>
                                                <th style="width: 50%;">Nombre Completo</th>
                                                <th style="width: 40%;">Correo Electrónico</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Subgrupos Hijos --}}
                    <div class="tab-pane fade" id="tab-subgrupos-grupo" role="tabpanel">
                        <div class="card border shadow-sm mb-3">
                            <div class="card-body p-3">
                                <form id="formCrearSubgrupoRapido">
                                    @csrf
                                    <input type="hidden" name="parent_id" id="subgrupo_parent_id">
                                    <div class="input-group">
                                        <input type="text" name="name" id="input_nuevo_subgrupo_nombre" class="form-control font-weight-bold" placeholder="Nombre del Subgrupo Hijo" required style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-info font-weight-bold text-nowrap px-4" id="btnCrearSubgrupoRapido" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                                <i class="fa fa-plus-circle mr-1"></i> Crear Subgrupo
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="contenedorSubgruposGrupo" class="table-responsive">
                            <table class="table table-hover table-custom w-100 mb-0" id="tablaSubgruposGrupoModal">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">#</th>
                                        <th style="width: 50%;">Nombre del Subgrupo</th>
                                        <th style="width: 40%;">Integrantes</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-3">
                    <button type="button" class="btn btn-light btn-round px-4" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE CREACIÓN / EDICIÓN DE DEPENDENCIA DEL ORGANIGRAMA IN-SITU
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalDependencia" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow rounded-lg">
            <div class="modal-header bg-warning text-dark d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold mb-0" id="modalDepTitulo">
                    <i class="fa fa-sitemap mr-2"></i> Agregar Dependencia
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formDependencia">
                @csrf
                <input type="hidden" id="dep_id" name="dependency_id">
                <input type="hidden" id="dep_parent_id" name="parent_id">
                <input type="hidden" id="is_root_dep" name="is_root" value="0">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Nombre de la Dependencia <span class="text-danger">*</span></label>
                        <input type="text" name="dependency" id="dep_dependency" class="form-control font-weight-bold" required placeholder="Ej: Departamento de Estadística y Control">
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small">Responsable / Encargado</label>
                            <input type="text" name="manager" id="dep_manager" class="form-control" placeholder="Ej: Dr. Roberto Benítez">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small">Usuario asignado del Sistema</label>
                            <select name="user_id" id="dep_user_id" class="form-control select2" style="width:100%">
                                <option value="">-- Sin usuario asignado --</option>
                                @foreach($usuariosList as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small">Email</label>
                            <input type="email" name="email" id="dep_email" class="form-control" placeholder="correo@ips.gov.py">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small">Teléfono / Interno</label>
                            <input type="text" name="phone" id="dep_phone" class="form-control" placeholder="021-xxxxxx / Int. 123">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 form-group mb-0">
                            <label class="font-weight-bold small">Tipo de Establecimiento</label>
                            <select name="tipo_establecimiento" id="dep_tipo_establecimiento" class="form-control">
                                <option value="">-- Ninguno / Administrativo --</option>
                                <option value="HOSPITAL">Hospital</option>
                                <option value="CLINICA">Clínica</option>
                                <option value="PUESTO_SANITARIO">Puesto Sanitario</option>
                                <option value="CENTRO_ATENCION">Centro de Atención</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 form-group mb-0">
                            <label class="font-weight-bold small">Región / Ubicación</label>
                            <input type="text" name="region" id="dep_region" class="form-control" placeholder="Ej: ASUNCIÓN Y CENTRAL">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning btn-round text-dark font-weight-bold shadow-sm px-4" id="btnGuardarDep">
                        <i class="fa fa-save mr-1"></i> Guardar Dependencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE DIAGRAMA VISUAL DEL ORGANIGRAMA ESTRUCTURAL
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalVisualOrganigrama" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 94%;">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header text-white d-flex align-items-center justify-content-between py-3 px-4" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalVisualTitulo">
                    <i class="fa fa-sitemap text-warning mr-2"></i> Diagrama Visual de la Estructura Orgánica:
                    <span class="text-warning font-weight-bold" id="modalVisualOrgNombre">{{ $organigramaRaiz ? $organigramaRaiz->dependency : 'Global' }}</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                {{-- Toolbar de Controles de Zoom / Exportación --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 bg-white p-3 rounded-lg border shadow-sm" style="gap: 10px;">
                    <div>
                        <span class="font-weight-bold text-dark small text-uppercase">
                            <i class="fa fa-layer-group text-primary mr-1"></i> Jerarquía de Dependencias Orgánicas
                        </span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" id="btnOrgZoomIn" title="Acercar (+)">
                            <i class="fa fa-search-plus mr-1"></i> Acercar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" id="btnOrgZoomOut" title="Alejar (-)">
                            <i class="fa fa-search-minus mr-1"></i> Alejar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" id="btnOrgZoomReset" title="Restablecer Zoom">
                            <i class="fa fa-sync-alt mr-1"></i> Restablecer
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info font-weight-bold" id="btnOrgExportImg" title="Exportar como Imagen PNG">
                            <i class="fa fa-camera mr-1"></i> Exportar PNG
                        </button>
                    </div>
                </div>

                {{-- Contenedor del Diagrama Visual jquery.orgchart --}}
                <div id="chart-modal-container" class="shadow-sm border">
                    <div class="text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                        <p class="text-muted font-weight-bold">Generando diagrama visual del organigrama...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white py-2 px-4">
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: CERTIFICACIÓN MEF (CUMPLIMIENTO DE MATRICES)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalCertificacionMef" tabindex="-1" role="dialog" aria-labelledby="modalCertificacionMefTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 900px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalCertificacionMefTitulo">
                    <i class="fa fa-certificate text-warning mr-2"></i> Certificación MEF
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="modalCertificacionMefBody" style="background-color: #f8fafc; min-height: 260px; max-height: 80vh; overflow-y: auto;">
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i>
                    <div>Cargando verificación de certificación MEF...</div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="fa fa-info-circle text-info mr-1"></i> Verificación oficial de cumplimiento de estándares y matrices MEF.</small>
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: ANÁLISIS Y CRUCE DE AMBIENTES FODA
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalFodaCrossing" tabindex="-1" role="dialog" aria-labelledby="modalFodaCrossingTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 1550px; width: 96%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalFodaCrossingTitulo">
                    <i class="fa fa-random text-warning mr-2"></i> Análisis FODA & Cruce de Ambientes
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="modalFodaCrossingBody" style="background-color: #f8fafc; min-height: 400px; max-height: 85vh; overflow-y: auto;">
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i>
                    <div>Cargando matriz de análisis y cruce de ambientes FODA...</div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="fa fa-info-circle text-info mr-1"></i> Control total para estructurar y cruzar estrategias FO, DO, FA y DA asociadas al PEI.</small>
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE CREACIÓN Y EDICIÓN ORIGINAL DE PERFIL PEI (ORIGINAL DE PEI-PROFILES)
     ════════════════════════════════════════════════════════════════════════════ --}}
<!-- MODAL DE CREACIÓN Y EDICIÓN DE PERFIL PEI (DISEÑO PREMIUM) -->
<div class="modal fade" id="ajaxModal" aria-hidden="true" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%); border-bottom: 2px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title font-weight-bold text-white mb-0 d-flex align-items-center" id="modalHeading">
                    <i class="fa fa-edit text-warning mr-2"></i> Perfil de Planificación Estratégica
                </h5>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc;">
                <form id="profileForm" name="profileForm" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="profile_id" id="profile_id">
                    <input type="hidden" name="parent_id" id="parent_id">
                    <input type="hidden" name="type" id="type" value="group">
                    <input type="hidden" name="level" id="level" value="master">
                    <input type="hidden" name="mision" id="mision">
                    <input type="hidden" name="vision" id="vision">
                    <input type="hidden" name="values" id="values">
                    <input type="hidden" name="period" id="period">
                    <input type="hidden" name="numerator" id="numerator">
                    <input type="hidden" name="operator" id="operator">
                    <input type="hidden" name="denominator" id="denominator">
                    <input type="hidden" name="goal" id="goal">
                    <input type="hidden" name="progress" id="progress">
                    <input type="hidden" name="nivel_label" id="nivel_label">

                    {{-- ── SECCIÓN 1: DATOS PRINCIPALES DEL PLAN ── --}}
                    <div class="card border-0 shadow-xs mb-3" style="border-radius: 12px; background: #ffffff;">
                        <div class="card-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                            <i class="fa fa-flag text-primary mr-2"></i>
                            <span class="font-weight-bold text-dark small text-uppercase" style="letter-spacing: 0.03em;">1. Identificación del Plan Estratégico</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="form-group mb-3">
                                <label for="name" class="font-weight-bold text-dark small">Nombre del Plan Estratégico <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control font-weight-bold shadow-none" required placeholder="Ej: Plan Estratégico Institucional IPS 2026-2028" style="border-radius: 8px;">
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 mb-0">
                                    <label for="year_start" class="font-weight-bold text-dark small">Año de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" name="year_start" id="year_start" class="form-control shadow-none" required style="border-radius: 8px;">
                                </div>
                                <div class="form-group col-md-6 mb-0">
                                    <label for="year_end" class="font-weight-bold text-dark small">Año de Finalización <span class="text-danger">*</span></label>
                                    <input type="date" name="year_end" id="year_end" class="form-control shadow-none" required style="border-radius: 8px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECCIÓN 2: ALCANCE ORGANIZATIVO & ASIGNACIONES ── --}}
                    <div class="card border-0 shadow-xs mb-3" style="border-radius: 12px; background: #ffffff;">
                        <div class="card-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                            <i class="fa fa-sitemap text-info mr-2"></i>
                            <span class="font-weight-bold text-dark small text-uppercase" style="letter-spacing: 0.03em;">2. Alcance Organigrama & Asignaciones</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="form-group type_profile mb-3">
                                <label for="type_profile" class="font-weight-bold text-dark small">Tipo de Perfil</label>
                                <select name="type_profile" id="type_profile" class="form-control font-weight-bold" style="width:100%;">
                                    <option value="group">Grupal (Grupo de Trabajo)</option>
                                    <option value="corporative">Corporativo (Dependencia Institucional)</option>
                                </select>
                            </div>

                            <div class="form-group dependencies mb-3" style="display: none;">
                                <label for="dependencies" class="font-weight-bold text-dark small"><i class="fa fa-building text-secondary mr-1"></i> Elija Corporación / Dependencia</label>
                                <select name="dependency_id" id="dependencies" class="form-control" style="width:100%;">
                                </select>
                            </div>

                            <div class="form-group group_roots mb-3">
                                <label for="group_roots" class="font-weight-bold text-dark small"><i class="fa fa-layer-group text-secondary mr-1"></i> Evento / Grupo Raíz</label>
                                <select name="group_root_id" id="group_roots" class="form-control" style="width:100%;">
                                </select>
                            </div>

                            <div class="form-group groups mb-3">
                                <label for="groups" class="font-weight-bold text-dark small"><i class="fa fa-users text-secondary mr-1"></i> Asignar Grupo de Trabajo</label>
                                <select name="group_id" id="groups" class="form-control" style="width:100%;">
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label for="analysts" class="font-weight-bold text-dark small"><i class="fa fa-user-shield text-indigo mr-1"></i> Asignar Analistas Responsables</label>
                                <select name="analyst_id[]" id="analysts" class="form-control" multiple style="width:100%;">
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECCIÓN 3: METODOLOGÍA & BALANCED SCORECARD ── --}}
                    <div class="card border-0 shadow-xs mb-3" style="border-radius: 12px; background: #ffffff;">
                        <div class="card-header py-2.5 px-3 bg-light border-bottom d-flex align-items-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                            <i class="fa fa-cogs text-warning mr-2"></i>
                            <span class="font-weight-bold text-dark small text-uppercase" style="letter-spacing: 0.03em;">3. Diagnóstico, Metodología & BSC</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="form-group mb-3">
                                <label for="foda_perfil_id" class="font-weight-bold text-dark small"><i class="fa fa-chart-pie text-success mr-1"></i> Perfil FODA vinculado</label>
                                <select name="foda_perfil_id" id="foda_perfil_id" class="form-control" style="width:100%;">
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="modelo_niveles" class="font-weight-bold text-dark small"><i class="fa fa-list-ol text-primary mr-1"></i> Modelo de Niveles del Plan</label>
                                <select name="modelo_niveles" id="modelo_niveles" class="form-control font-weight-bold" style="width:100%;">
                                    <option value="MECIP">MECIP 2015 — Objetivo Estratégico / Meta / Acción</option>
                                    <option value="IPS" selected>IPS 2023-2028 — Eje Estratégico / Objetivo / Acción</option>
                                    <option value="A">Clásico — Eje / Objetivo / Acción</option>
                                    <option value="B">Proyectos — Programa / Proyecto / Actividad</option>
                                    <option value="C">Estratégico — Eje / Meta / Tarea</option>
                                    <option value="D">Institucional — Estrategia / Plan / Acción</option>
                                    <option value="custom">Personalizado...</option>
                                </select>
                            </div>

                            <div id="custom_niveles" style="display:none;" class="mb-3">
                                <div class="card card-body bg-light border">
                                    <small class="text-muted mb-2 font-weight-bold">Definí cómo se llamará cada nivel en este plan:</small>
                                    <div class="form-row">
                                        <div class="form-group col-md-4 mb-0">
                                            <label for="label_axi" class="small font-weight-bold">Nivel 1 (ej: Eje)</label>
                                            <input type="text" name="label_axi" id="label_axi" class="form-control form-control-sm" placeholder="Eje Estratégico">
                                        </div>
                                        <div class="form-group col-md-4 mb-0">
                                            <label for="label_goal" class="small font-weight-bold">Nivel 2 (ej: Objetivo)</label>
                                            <input type="text" name="label_goal" id="label_goal" class="form-control form-control-sm" placeholder="Objetivo">
                                        </div>
                                        <div class="form-group col-md-4 mb-0">
                                            <label for="label_action" class="small font-weight-bold">Nivel 3 (ej: Acción)</label>
                                            <input type="text" name="label_action" id="label_action" class="form-control form-control-sm" placeholder="Acción">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="bsc_level" class="font-weight-bold text-dark small"><i class="fa fa-bullseye text-danger mr-1"></i> Nivel de Aplicación del Balanced Scorecard (BSC)</label>
                                <select name="bsc_level" id="bsc_level" class="form-control" style="width:100%;">
                                    <option value="axi">Nivel 1 — Objetivo Estratégico / Eje</option>
                                    <option value="goal" selected>Nivel 2 — Objetivo Específico / Meta</option>
                                    <option value="both">Ambos Niveles (Nivel 1 y Nivel 2)</option>
                                    <option value="none">Desactivado (Sin Perspectiva BSC)</option>
                                </select>
                                <small class="form-text text-muted">Define en qué nivel de la estructura jerárquica se habilitará el selector de las 4 Perspectivas BSC.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-2" style="gap:.5rem">
                        <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary btn-round px-4 font-weight-bold shadow-sm" id="saveBtn" value="create" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none;">
                            <i class="fa fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE GESTIÓN COMPLETA DE ROLES IN-SITU (EDICIÓN EN LÍNEA)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalRolDashboard" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0 rounded-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f766e, #0d9488);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-shield-alt mr-2"></i> Gestión de Roles de Usuario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">

                {{-- Formulario para Crear Rol Rápido --}}
                <div class="card border shadow-sm mb-4">
                    <div class="card-header bg-light py-2 px-3">
                        <span class="font-weight-bold text-dark small text-uppercase">
                            <i class="fa fa-plus-circle text-success mr-1"></i> Crear Nuevo Rol
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <form id="formCrearRolRapido">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="name" id="input_nuevo_rol_nombre" class="form-control font-weight-bold" placeholder="Ej: Coordinador de Proyectos" required style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-success font-weight-bold text-nowrap px-4" id="btnCrearRolRapido" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                        <i class="fa fa-plus-circle mr-1"></i> Crear Rol
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabla DataTables de Roles Configurados --}}
                <div class="card border shadow-sm">
                    <div class="card-header bg-light py-2 px-3">
                        <span class="font-weight-bold text-dark small text-uppercase">
                            <i class="fa fa-list text-primary mr-1"></i> Roles Configurados en el Sistema (<span id="cantRolesModalHeader">{{ count($rolesWithPermissions) }}</span>)
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom mb-0 w-100" id="tablaRolesModal">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 45%;">Nombre del Rol</th>
                                        <th style="width: 30%;">Permisos Asignados</th>
                                        <th style="width: 20%; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rolesWithPermissions as $rIdx => $r)
                                    <tr id="rol_row_{{ $r->id }}">
                                        <td>{{ count($rolesWithPermissions) - $rIdx }}</td>
                                        <td>
                                            <span id="span_role_name_{{ $r->id }}" class="font-weight-bold text-dark">{{ $r->name }}</span>
                                            <input type="text" id="input_role_name_{{ $r->id }}" class="form-control form-control-sm font-weight-bold d-none" value="{{ $r->name }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-info btn-round px-2 py-1 font-weight-bold" onclick="abrirModalPermisosRol('{{ $r->id }}', '{{ addslashes($r->name) }}')" title="Gestionar Permisos del Rol">
                                                <i class="fa fa-key mr-1"></i> <span id="badge_perm_count_{{ $r->id }}">{{ $r->permissions->count() }}</span> permisos
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <div id="actions_default_{{ $r->id }}" class="d-flex justify-content-center" style="gap: 4px;">
                                                <button type="button" class="btn btn-circle btn-info" onclick="activarEdicionInlineRol('{{ $r->id }}')" title="Editar Nombre En Línea">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-circle btn-primary" onclick="abrirModalPermisosRol('{{ $r->id }}', '{{ addslashes($r->name) }}')" title="Gestionar Permisos">
                                                    <i class="fa fa-key"></i>
                                                </button>
                                                <button type="button" class="btn btn-circle btn-danger" onclick="eliminarRolInModal('{{ $r->id }}', '{{ addslashes($r->name) }}')" title="Eliminar Rol">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>

                                            <div id="actions_editing_{{ $r->id }}" class="d-none justify-content-center" style="gap: 4px;">
                                                <button type="button" class="btn btn-circle btn-success" onclick="guardarEdicionInlineRol('{{ $r->id }}')" title="Guardar Cambios">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-circle btn-secondary" onclick="cancelarEdicionInlineRol('{{ $r->id }}', '{{ addslashes($r->name) }}')" title="Cancelar">
                                                    <i class="fa fa-times"></i>
                                                </button>
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
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     SUB-MODAL DE GESTIÓN DE PERMISOS PARA UN ROL ESPECÍFICO
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalPermisosRolSub" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0 rounded-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f766e, #0d9488);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-key mr-2"></i> Permisos del Rol: <span id="nombreRolSubModalHeading" class="text-warning"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="formPermisosRolSubModal">
                    @csrf
                    <input type="hidden" name="role_id" id="sub_modal_role_id">
                    <input type="hidden" name="name" id="sub_modal_role_name">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="font-weight-bold text-dark small">Marcar permisos del sistema:</span>
                        <div>
                            <button type="button" class="btn btn-xs btn-outline-success mr-1" id="btnSelTodosPermsSub" style="font-size:0.7rem; padding: 2px 6px;">
                                <i class="fa fa-check-square mr-1"></i> Todos
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" id="btnDeselTodosPermsSub" style="font-size:0.7rem; padding: 2px 6px;">
                                <i class="fa fa-square mr-1"></i> Ninguno
                            </button>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-1 p-3 bg-light rounded border mb-4" style="max-height: 250px; overflow-y: auto;">
                        @foreach($allPermissions as $p)
                        <label class="perm-badge-item">
                            <input type="checkbox" name="permission[]" value="{{ $p->name }}" class="chk-permiso-submodal mr-1" id="sub_perm_{{ $p->id }}">
                            <span>{{ $p->name }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end" style="gap:.5rem">
                        <button type="button" class="btn btn-light btn-round px-4" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info btn-round px-4 font-weight-bold" id="btnGuardarPermisosSubModal">
                            <i class="fa fa-save mr-1"></i> Guardar Permisos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE GESTIÓN COMPLETA DE PERMISOS IN-SITU
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalPermisoDashboard" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow border-0 rounded-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #b45309, #d97706);">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalPermisoHeading">
                    <i class="fa fa-key mr-2"></i> Gestión de Permisos del Sistema
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">

                {{-- Formulario para Crear Permiso --}}
                <div class="card border shadow-sm mb-4">
                    <div class="card-header bg-light py-2 px-3">
                        <span class="font-weight-bold text-dark small text-uppercase">
                            <i class="fa fa-plus-circle text-warning mr-1"></i> Registrar Nuevo Permiso
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <form id="formPermisoDashboard">
                            @csrf
                            <input type="hidden" name="permiso_id" id="modal_permiso_id">

                            <div class="form-group mb-1">
                                <label class="font-weight-bold text-dark small mb-1">Nombre del Permiso <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="name" id="modal_permiso_name" class="form-control font-weight-bold" required placeholder="Ej: pei-edit" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4" id="btnGuardarPermisoModal" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                            <i class="fa fa-plus-circle mr-1"></i> Registrar Permiso
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2">Sugerencias de formato: <code>user-list</code>, <code>role-create</code>, <code>pei-edit</code>, <code>foda-view</code></small>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabla DataTables de Permisos Configurados --}}
                <div class="card border shadow-sm">
                    <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold text-dark small text-uppercase">
                            <i class="fa fa-list text-warning mr-1"></i> Permisos Registrados (<span id="cantPermisosModalHeader">{{ count($permissionsList) }}</span>)
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom mb-0 w-100" id="tablaPermisosModal">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">#</th>
                                        <th style="width: 70%;">Nombre del Permiso</th>
                                        <th style="width: 20%; text-align: center;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissionsList as $pIdx => $perm)
                                    <tr id="perm_row_{{ $perm->id }}">
                                        <td>{{ count($permissionsList) - $pIdx }}</td>
                                        <td>
                                            <code id="span_perm_name_{{ $perm->id }}" class="font-weight-bold text-dark" style="font-size:0.85rem">{{ $perm->name }}</code>
                                            <input type="text" id="input_perm_name_{{ $perm->id }}" class="form-control form-control-sm font-weight-bold d-none" value="{{ $perm->name }}">
                                        </td>
                                        <td class="text-center">
                                            <div id="perm_actions_default_{{ $perm->id }}" class="d-flex justify-content-center" style="gap: 4px;">
                                                <button type="button" class="btn btn-circle btn-info" onclick="activarEdicionInlinePermiso('{{ $perm->id }}')" title="Editar Permiso">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-circle btn-danger" onclick="eliminarPermisoInModal('{{ $perm->id }}', '{{ addslashes($perm->name) }}')" title="Eliminar Permiso">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                            <div id="perm_actions_editing_{{ $perm->id }}" class="d-none justify-content-center" style="gap: 4px;">
                                                <button type="button" class="btn btn-circle btn-success" onclick="guardarEdicionInlinePermiso('{{ $perm->id }}')" title="Guardar Cambios">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-circle btn-secondary" onclick="cancelarEdicionInlinePermiso('{{ $perm->id }}', '{{ addslashes($perm->name) }}')" title="Cancelar">
                                                    <i class="fa fa-times"></i>
                                                </button>
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
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL DE TELEMETRÍA Y ANALÍTICA DE FUNCIONARIO (IN-SITU)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalTelemetriaUsuario" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg" style="background-color: #f8fafc;">
            <div class="modal-header bg-dark text-white d-flex align-items-center justify-content-between p-3" style="border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalTelHeading">
                    <i class="fa fa-chart-line text-warning mr-2"></i> Telemetría y Analítica de Funcionario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                {{-- Tarjeta Perfil Funcionario --}}
                <div class="card border shadow-sm mb-4" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-body p-3 bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <img id="tel_user_avatar" src="{{ asset('assets/images/user-avatar.png') }}" class="rounded-circle border shadow-sm mr-3" style="width: 54px; height: 54px; object-fit: cover;">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0" id="tel_user_name">—</h5>
                                <div class="small text-muted mb-1" id="tel_user_email">—</div>
                                <span class="badge badge-light border text-dark font-weight-bold" id="tel_user_group"><i class="fa fa-users text-info mr-1"></i> —</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span id="tel_user_online_badge"></span>
                            <span class="badge badge-warning text-dark font-weight-bold p-2 px-3 shadow-sm" style="font-size: 0.82rem; border-radius: 20px;" id="tel_user_points">
                                🏆 0 pts
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Rejilla de KPIs --}}
                <div class="row mb-4">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="card border-0 shadow-sm p-3 bg-white text-center" style="border-radius: 10px;">
                            <div class="text-primary font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem;">Total Interacciones</div>
                            <h3 class="font-weight-bold text-dark mb-0" id="tel_kpi_total">0</h3>
                            <small class="text-muted">Acciones registradas</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="card border-0 shadow-sm p-3 bg-white text-center" style="border-radius: 10px;">
                            <div class="text-success font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem;">Última Actividad</div>
                            <h6 class="font-weight-bold text-dark mb-0 mt-1" id="tel_kpi_last_act">—</h6>
                            <small class="text-muted">Sello de tiempo</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm p-3 bg-white text-center" style="border-radius: 10px;">
                            <div class="text-info font-weight-bold text-uppercase mb-1" style="font-size: 0.75rem;">Conexión IP Reciente</div>
                            <h6 class="font-weight-bold text-dark mb-0 mt-1" id="tel_kpi_ip">—</h6>
                            <small class="text-muted">Dirección IP</small>
                        </div>
                    </div>
                </div>

                {{-- Cronología Reciente --}}
                <div class="card border shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-light py-2 px-3 font-weight-bold text-dark small text-uppercase">
                        <i class="fa fa-history text-info mr-1"></i> Cronología Transaccional Reciente (Últimas Actividades)
                    </div>
                    <div class="card-body p-3 bg-white" style="max-height: 280px; overflow-y: auto;">
                        <ul class="list-group list-group-flush" id="tel_timeline_list">
                            <li class="list-group-item text-center text-muted small py-4">Cargando datos de telemetría...</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
{{-- SortableJS CDN para drag and drop en organigrama --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
{{-- orgchart plugin & html2canvas --}}
<script src="{{ asset('assets/orgchart/dist/js/jquery.orgchart.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
$(document).ready(function() {
    // ── Toggle Acordeón / Expandir / Colapsar Árbol de Organigrama ──
    $(document).on('click', '.btn-toggle', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var $icon = $btn.find('i');
        var $children = $btn.closest('.nodo-item').find('> .nodo-children');

        $children.slideToggle(150);
        if ($icon.hasClass('fa-chevron-down')) {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        } else {
            $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
        }
    });

    // ── Filtro por Estado en Pestaña Actividades del PEI (Integración con DataTables) ──
    window.filtrarActividadesTabStatus = function(btn, status) {
        $('.btn-filter-act-tab').removeClass('btn-dark active').addClass('btn-outline-secondary btn-outline-success btn-outline-warning');
        $(btn).addClass('btn-dark active');
        
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tablaActividadesPeiDashboard')) {
            var dt = $('#tablaActividadesPeiDashboard').DataTable();
            if (status === 'all') {
                dt.column(4).search('').draw();
            } else {
                dt.column(4).search(status).draw();
            }
        }
    };

    // ── Scope Filter para Planes PEI (PEI Seleccionado vs Todos los Planes) ──
    var currentPlanesScope = 0; // 0 = solo PEI seleccionado, 1 = todos los planes

    if ($.fn.dataTable) {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            if (settings.nTable && settings.nTable.id === 'tablaPlanesGlobal') {
                if (currentPlanesScope === 1) {
                    return true;
                }
                var rowNode = settings.aoData[dataIndex] ? settings.aoData[dataIndex].nTr : null;
                if (!rowNode) return true;
                if ($('#tablaPlanesGlobal tr.pei-row-active').length > 0) {
                    return $(rowNode).hasClass('pei-row-active');
                }
                return true;
            }
            return true;
        });
    }

    window.filtrarPlanesScope = function(scope) {
        currentPlanesScope = scope;
        if (scope === 0) {
            $('#lblScopePeiSelected').addClass('active btn-primary').removeClass('btn-outline-primary');
            $('#lblScopePeiAll').removeClass('active btn-primary').addClass('btn-outline-primary');
        } else {
            $('#lblScopePeiSelected').removeClass('active btn-primary').addClass('btn-outline-primary');
            $('#lblScopePeiAll').addClass('active btn-primary').removeClass('btn-outline-primary');
        }

        if ($.fn.dataTable && $.fn.dataTable.isDataTable('#tablaPlanesGlobal')) {
            $('#tablaPlanesGlobal').DataTable().draw();
        }
    };

    // ── Lectura Cómoda de Aportes de Asesoría Handler ──
    $(document).on('click', '.btnVerReporteAportes', function () {
        var peiId = $(this).data('pei-id');
        if (!peiId) return;
        var url = "{{ url('pei-profiles') }}/" + peiId + "/asesorias/reporte";

        $('#modalLecturaAportesBody').html('<div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i><div>Cargando reporte consolidado de aportes...</div></div>');
        $('#modalLecturaAportes').modal('show');

        $.get(url, function (html) {
            $('#modalLecturaAportesBody').html(html);
        }).fail(function () {
            $('#modalLecturaAportesBody').html('<div class="alert alert-danger mb-0 p-4">Ocurrió un error al cargar el reporte de aportes.</div>');
        });
    });

    // ── Certificación MEF Modal Handler ──
    $(document).on('click', '.btnVerCertificacionMef', function () {
        var peiId = $(this).data('id');
        var peiName = $(this).data('name') || 'Plan Estratégico';
        var url = "{{ url('pei-profiles') }}/" + peiId + "/certificacion-mef";

        $('#modalCertificacionMefTitulo').html('<i class="fa fa-certificate text-warning mr-2"></i> Certificación MEF — ' + peiName);
        $('#modalCertificacionMefBody').html('<div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i><div>Cargando verificación de certificación MEF...</div></div>');
        $('#modalCertificacionMef').modal('show');

        $.get(url, function (html) {
            $('#modalCertificacionMefBody').html(html);
        }).fail(function (xhr) {
            var msg = xhr.responseJSON?.message || 'Ocurrió un error al cargar la certificación MEF.';
            $('#modalCertificacionMefBody').html('<div class="alert alert-danger mb-0"><i class="fa fa-exclamation-circle mr-2"></i> ' + msg + '</div>');
        });
    });

    // ── Análisis FODA & Cruce de Ambientes Modal Handler ──
    var currentFodaUrl = '';
    window.recargarModalFodaCrossing = function () {
        if (!currentFodaUrl) return;
        $.get(currentFodaUrl, { modal: 1 }, function (html) {
            $('#modalFodaCrossingBody').html(html);
        });
    };

    $(document).on('click', '.btnVerFodaCrossing', function () {
        var url = $(this).data('url');
        var planName = $(this).data('name') || 'Plan Estratégico';
        currentFodaUrl = url;

        $('#modalFodaCrossingTitulo').html('<i class="fa fa-random text-warning mr-2"></i> Análisis FODA & Cruce de Ambientes — ' + planName);
        $('#modalFodaCrossingBody').html('<div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i><div>Cargando matriz de análisis y cruce de ambientes FODA...</div></div>');
        $('#modalFodaCrossing').modal('show');

        $.get(url, { modal: 1 }, function (html) {
            $('#modalFodaCrossingBody').html(html);
        }).fail(function (xhr) {
            var msg = xhr.responseJSON?.message || 'Ocurrió un error al cargar el análisis FODA.';
            $('#modalFodaCrossingBody').html('<div class="alert alert-danger mb-0"><i class="fa fa-exclamation-circle mr-2"></i> ' + msg + '</div>');
        });
    });
    // ── Lógica Idéntica Original de pei-profiles (#ajaxModal & Select2s) ──
    function initializeSelect2(selector, placeholder, url) {
        selector.val("").select2({
            dropdownParent: $('#ajaxModal'),
            placeholder: placeholder,
            allowClear: true,
            width: '100%',
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name || item.dependency || item.nombre || item.text,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            }
        });
    }

    function initSelect2WithRelationship(control, key, value) {
        var dataOption = { id: key, text: value };
        var initOption = new Option(dataOption.text, dataOption.id, true, true);
        control.empty().append(initOption).trigger('change');
    }

    // Botón Nuevo Perfil
    $('#createNewProfile').click(function() {
        $('#saveBtn').val("create-user");
        $('#profile_id').val('');
        $('#profileForm').trigger("reset");
        $('#mision').val('');
        $('#vision').val('');
        $('#values').val('');
        $('#nivel_label').val('');
        $('#modalHeading').html("<i class='fa fa-plus-circle mr-2 text-white'></i> Nuevo Perfil de Planificación Estratégica");
        $('#ajaxModal').modal('show');

        $('.form-group.dependencies').hide();
        $('#custom_niveles').hide();

        $('#type_profile').select2({ dropdownParent: $('#ajaxModal') }).off('change').on('change', function() {
            if ($(this).val() === 'corporative') {
                $('.form-group.dependencies').show();
                $('.form-group.groups').hide();
                $('#type').val('corporative');
            } else if ($(this).val() === 'group') {
                $('.form-group.dependencies').hide();
                $('.form-group.groups').show();
                $('#type').val('group');
            }
        });

        $('#modelo_niveles').select2({ dropdownParent: $('#ajaxModal') }).off('change.niveles').on('change.niveles', function() {
            if ($(this).val() === 'custom') {
                $('#custom_niveles').show();
            } else {
                $('#custom_niveles').hide();
            }
        });

        initializeSelect2($("#dependencies"), 'Seleccione la dependencia', '{{ route('globales.get-dependencies') }}');
        initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz de trabajo', '{{ route('globales.get-root-groups') }}');

        $('#group_roots').off('change').on('change', function() {
            var groupRootID = $(this).val();
            if (!groupRootID) return;
            var url = '{{ url("admin/globales/get-groups") }}/' + groupRootID;
            $.getJSON(url, function(data) {
                if (!data || data.length === 0) {
                    var rootText = $('#group_roots').select2('data')[0] ? $('#group_roots').select2('data')[0].text : 'Grupo Principal';
                    var opt = new Option(rootText, groupRootID, true, true);
                    $('#groups').empty().append(opt).trigger('change');
                } else {
                    initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                }
            });
        });

        var urlUsers = '{{ route('globales.get-users') }}';
        $("#analysts").val([]).trigger("change");
        $('#analysts').select2({
            dropdownParent: $('#ajaxModal'),
            allowClear: true,
            width: '100%',
            ajax: {
                url: urlUsers,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return { text: item.name, id: item.id };
                        })
                    };
                },
                cache: true
            }
        });

        $('#foda_perfil_id').empty().select2({
            dropdownParent: $('#ajaxModal'),
            placeholder: 'Seleccioná el perfil FODA...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: '{{ route('get-foda-perfiles') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(res) {
                    return {
                        results: $.map(res, function(item) {
                            return { text: item.nombre || item.name || item.text, id: item.id };
                        })
                    };
                },
                cache: true
            }
        });
    });

    // Botón Editar Perfil (Original)
    $(document).on('click', '.editProfile', function() {
        var profileID = $(this).data('id');
        $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
            $('#modalHeading').html("<i class='fa fa-edit mr-2 text-white'></i> Editar Perfil: " + data.profile.name);
            $('#saveBtn').val("edit-profile");
            $('#ajaxModal').modal('show');
            $('#profileForm').trigger("reset");
            $('.errors').removeClass("alert alert-danger");
            $('#profile_id').val(data.profile.id);
            $('#name').val(data.profile.name);
            $('#year_start').val(data.profile.year_start);
            $('#year_end').val(data.profile.year_end);
            $('#mision').val(data.profile.mision);
            $('#vision').val(data.profile.vision);
            $('#values').val(data.profile.values);

            initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz de trabajo', '{{ route('globales.get-root-groups') }}');

            var profileType = data.profile.type || 'group';
            var selectTypeProfile = $('#type_profile').select2({ dropdownParent: $('#ajaxModal') });
            selectTypeProfile.val(profileType).trigger('change');
            selectTypeProfile.off('change').on('change', function() {
                if ($(this).val() === 'corporative') {
                    $('.form-group.dependencies').show();
                    $('.form-group.groups').hide();
                    $('#type').val('corporative');
                } else if ($(this).val() === 'group') {
                    $('.form-group.dependencies').hide();
                    $('.form-group.groups').show();
                    $('#type').val('group');
                }
            });

            if (profileType === 'corporative') {
                $('.form-group.dependencies').show();
                $('.form-group.groups').hide();
                $('#type').val('corporative');
            } else {
                $('.form-group.dependencies').hide();
                $('.form-group.groups').show();
                $('#type').val('group');
            }

            initializeSelect2($('#dependencies'), 'Seleccione la dependencia', '{{ route('globales.get-dependencies') }}');
            if (data.profile.dependency_id && data.profile.dependency) {
                initSelect2WithRelationship($('#dependencies'), data.profile.dependency_id, data.profile.dependency.dependency);
            }

            if (data.profile.group) {
                if (data.groupParent) {
                    initSelect2WithRelationship($('#group_roots'), data.groupParent.id, data.groupParent.name);
                } else {
                    initSelect2WithRelationship($('#group_roots'), data.profile.group_id, data.profile.group.name);
                }
                initSelect2WithRelationship($('#groups'), data.profile.group_id, data.profile.group.name);
            }

            $('#group_roots').off('change').on('change', function() {
                var groupRootID = $(this).val();
                if (!groupRootID) return;
                var url = '{{ url("admin/globales/get-groups") }}/' + groupRootID;
                $.getJSON(url, function(dataRes) {
                    if (!dataRes || dataRes.length === 0) {
                        var rootText = $('#group_roots').select2('data')[0] ? $('#group_roots').select2('data')[0].text : 'Grupo';
                        var opt = new Option(rootText, groupRootID, true, true);
                        $('#groups').empty().append(opt).trigger('change');
                    } else {
                        initializeSelect2($("#groups"), 'Seleccione el Grupo', url);
                    }
                });
            });

            var urlAnalysts = '{{ route('globales.get-users') }}';
            $('#analysts').empty().select2({
                dropdownParent: $('#ajaxModal'),
                placeholder: 'Seleccione Analistas',
                allowClear: true,
                ajax: {
                    url: urlAnalysts,
                    dataType: 'json',
                    delay: 250,
                    processResults: function(res) {
                        return {
                            results: $.map(res, function(item) {
                                return { text: item.name, id: item.id };
                            })
                        };
                    },
                    cache: true
                }
            });

            if (data.analystsChecked) {
                data.analystsChecked.forEach(function(d) {
                    var option = new Option(d.text, d.id, true, true);
                    $('#analysts').append(option);
                });
                $('#analysts').trigger('change');
            }

            $('#foda_perfil_id').empty().select2({
                dropdownParent: $('#ajaxModal'),
                placeholder: 'Seleccioná el perfil FODA...',
                allowClear: true,
                ajax: {
                    url: '{{ route('get-foda-perfiles') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(res) {
                        return {
                            results: $.map(res, function(item) {
                                return { text: item.nombre || item.name || item.text, id: item.id };
                            })
                        };
                    },
                    cache: true
                }
            });

            if (data.profile.foda_perfil_id && data.fodaPerfilNombre) {
                var optFoda = new Option(data.fodaPerfilNombre, data.profile.foda_perfil_id, true, true);
                $('#foda_perfil_id').append(optFoda).trigger('change');
            }

            $('#custom_niveles').hide();
            $('#modelo_niveles').select2({ dropdownParent: $('#ajaxModal'), width: '100%', minimumResultsForSearch: Infinity });

            if (data.profile.nivel_label) {
                try {
                    var savedLabels = JSON.parse(data.profile.nivel_label);
                    if (savedLabels.bsc_level) {
                        $('#bsc_level').val(savedLabels.bsc_level).trigger('change');
                    } else {
                        $('#bsc_level').val('axi').trigger('change');
                    }
                    var modelos = {
                        'MECIP': { axi: 'Objetivo Estratégico', goal: 'Meta', action: 'Acción' },
                        'IPS': { axi: 'Eje Estratégico', goal: 'Objetivo', action: 'Acción' },
                        'A': { axi: 'Eje', goal: 'Objetivo', action: 'Acción' },
                        'B': { axi: 'Programa', goal: 'Proyecto', action: 'Actividad' },
                        'C': { axi: 'Eje', goal: 'Meta', action: 'Tarea' },
                        'D': { axi: 'Estrategia', goal: 'Plan', action: 'Acción' }
                    };
                    var matchedKey = null;
                    $.each(modelos, function(key, m) {
                        if (m.axi === savedLabels.axi && m.goal === savedLabels.goal && m.action === savedLabels.action) {
                            matchedKey = key;
                            return false;
                        }
                    });
                    if (matchedKey) {
                        $('#modelo_niveles').val(matchedKey).trigger('change');
                    } else {
                        $('#modelo_niveles').val('custom').trigger('change');
                        $('#label_axi').val(savedLabels.axi || '');
                        $('#label_goal').val(savedLabels.goal || '');
                        $('#label_action').val(savedLabels.action || '');
                        $('#custom_niveles').show();
                    }
                    $('#nivel_label').val(data.profile.nivel_label);
                } catch (e) {
                    $('#bsc_level').val('axi').trigger('change');
                }
            } else {
                $('#bsc_level').val('axi').trigger('change');
            }

            $('#modelo_niveles').off('change.niveles').on('change.niveles', function() {
                $('#custom_niveles').toggle($(this).val() === 'custom');
            });
        });
    });

    // Guardar Perfil Formulario (#profileForm)
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#saveBtn');
        $btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        var modelos = {
            'MECIP': { axi: 'Objetivo Estratégico', goal: 'Meta', action: 'Acción' },
            'IPS': { axi: 'Eje Estratégico', goal: 'Objetivo', action: 'Acción' },
            'A': { axi: 'Eje', goal: 'Objetivo', action: 'Acción' },
            'B': { axi: 'Programa', goal: 'Proyecto', action: 'Actividad' },
            'C': { axi: 'Eje', goal: 'Meta', action: 'Tarea' },
            'D': { axi: 'Estrategia', goal: 'Plan', action: 'Acción' }
        };
        var modeloSel = $('#modelo_niveles').val();
        var labels;
        if (modeloSel === 'custom') {
            labels = {
                master: 'PEI',
                axi: $('#label_axi').val() || 'Nivel 1',
                goal: $('#label_goal').val() || 'Nivel 2',
                action: $('#label_action').val() || 'Acción'
            };
        } else if (modelos[modeloSel]) {
            labels = Object.assign({ master: 'PEI' }, modelos[modeloSel]);
        } else {
            labels = { master: 'PEI', axi: 'Nivel 1', goal: 'Nivel 2', action: 'Acción' };
        }
        labels.bsc_level = $('#bsc_level').val() || 'axi';
        $('#nivel_label').val(JSON.stringify(labels));

        if ($('#type_profile').val() === 'corporative' && $('#group_roots').val()) {
            var rootVal = $('#group_roots').val();
            var rootData = $('#group_roots').select2('data')[0];
            if (rootData) {
                var opt = new Option(rootData.text, rootVal, true, true);
                $('#groups').empty().append(opt).trigger('change');
            }
        }

        $.ajax({
            data: $(this).serialize(),
            url: "{{ route('pei-profiles.store') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
                toastr.success(data.success || 'Perfil PEI guardado exitosamente.');
                $btn.html('Guardar cambios').prop('disabled', false);
                $('#profileForm').trigger("reset");
                $('#ajaxModal').modal('hide');

                if (data.profile) {
                    var p = data.profile;
                    var $row = $('#pei_row_' + p.id);
                    if ($row.length) {
                        $row.find('td:nth-child(2) .font-weight-bold').text(p.name);
                        if (p.year_start && p.year_end) {
                            $row.find('.badge-info').html('<i class="fa fa-calendar-alt mr-1"></i>' + p.year_start.substring(0,10) + ' - ' + p.year_end.substring(0,10));
                        }
                    }
                }
            },
            error: function(data) {
                $btn.html('Guardar cambios').prop('disabled', false);
                var obj = data.responseJSON?.errors || {};
                $.each(obj, function(key, value) {
                    toastr.error("Atención: " + value);
                });
                if (!data.responseJSON?.errors) {
                    toastr.error(data.responseJSON?.message || 'Error al guardar el perfil PEI.');
                }
            }
        });
    });

    // Alternar Visibilidad / Ocultar PEI
    $(document).on('click', '.toggleShowRiiss', function() {
        var profileId = $(this).data('id');
        var $btn = $(this);
        $.ajax({
            url: "{{ url('pei-profiles') }}/" + profileId + "/toggle-status",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', _method: 'PATCH' },
            success: function(res) {
                toastr.success(res.message || 'Estado del perfil actualizado correctamente.');
                var $badge = $('#badge_status_pei_' + profileId);
                var isAct = (res.status === 1 || res.status === true || res.is_active === true || res.is_active === 1);
                if (isAct) {
                    $badge.removeClass('badge-warning').addClass('badge-success').html('<i class="fa fa-check-circle mr-1"></i> Activo');
                    $btn.css({'background': '#06b6d4', 'border-color': '#06b6d4'}).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                    $btn.attr('title', 'Visible — Clic para Ocultar');
                } else {
                    $badge.removeClass('badge-success').addClass('badge-warning').html('<i class="fa fa-eye-slash mr-1"></i> Oculto');
                    $btn.css({'background': '#f59e0b', 'border-color': '#f59e0b'}).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                    $btn.attr('title', 'Oculto — Clic para Activar');
                }
            },
            error: function() {
                toastr.error('Error al cambiar el estado del perfil.');
            }
        });
    });

    // Eliminar Perfil PEI (Original)
    $(document).on('click', '.deleteProfile', function() {
        var profileID = $(this).data("id");
        var profileName = $(this).data("name") || 'Perfil PEI';

        Swal.fire({
            title: '¿Estás seguro de eliminarlo?',
            text: 'Si eliminas "' + profileName + '", no podrás revertirlo.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '¡Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value || result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('pei-profiles.store') }}" + '/' + profileID,
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(data) {
                        toastr.success('El registro ha sido eliminado correctamente.');
                        $('#pei_row_' + profileID).fadeOut(400, function() { $(this).remove(); });
                    },
                    error: function(data) {
                        toastr.error('Error al eliminar el perfil PEI.');
                    }
                });
            }
        });
    });
    $('#modal_user_group_id').select2({
        dropdownParent: $('#modalUsuarioDashboard'),
        placeholder: "— Ninguno / Sin asignación —",
        allowClear: true,
        width: '100%'
    });

    $('#modal_user_roles').select2({
        dropdownParent: $('#modalUsuarioDashboard'),
        placeholder: "— Seleccionar uno o más roles —",
        allowClear: true,
        width: '100%'
    });

    // Abrir Modal para Crear Nuevo Organigrama Raíz
    $(document).on('click', '#btnNuevoOrganigramaRaiz', function () {
        $('#formDependencia')[0].reset();
        $('#dep_id').val('');
        $('#dep_parent_id').val('');
        $('#is_root_dep').val('1');
        $('#dep_user_id').val('').trigger('change');
        $('#modalDepTitulo').html('<i class="fa fa-sitemap text-info mr-2"></i> Crear Nuevo Organigrama Raíz');
        $('#modalDependencia').modal('show');
    });

    // Abrir Modal Agregar Sub-dependencia a la Raíz Activa
    $(document).on('click', '#btnAgregarSubRaiz', function () {
        var rootId = $(this).data('id');
        var rootName = $(this).data('nombre');
        $('#formDependencia')[0].reset();
        $('#dep_id').val('');
        $('#dep_parent_id').val(rootId);
        $('#is_root_dep').val('0');
        $('#dep_user_id').val('').trigger('change');
        $('#modalDepTitulo').html('<i class="fa fa-plus-circle mr-2"></i> Agregar Sub-dependencia a: ' + rootName);
        $('#modalDependencia').modal('show');
    });

    // Select2 en Selector de Organigrama de Estructura
    $('#organigramaSelect').select2({
        placeholder: "— Seleccionar Estructura Orgánica —",
        allowClear: false,
        width: '260px'
    }).on('change', function() {
        cambiarOrganigramaEstructura($(this).val());
    });

    // Select2 en Selector de Plan PEI Hero
    $('#peiPlanFilterSelect').select2({
        placeholder: "— Todos los Planes PEI —",
        allowClear: true,
        width: '100%'
    }).on('change', function() {
        var selectedId = $(this).val();
        var activeHash = localStorage.getItem('activeDashboardTab') || '#tab-usuarios';
        window.location.href = '?pei_id=' + selectedId + activeHash;
    });

    // ── Diagrama Visual de Organigrama en Modal In-Situ ──
    var organigramaTreeData = @json($organigramaDatasource);
    var modalOrgZoom = 1;

    window.abrirModalVisualOrganigrama = function() {
        $('#modalVisualOrganigrama').modal('show');
    };

    $('#modalVisualOrganigrama').on('shown.bs.modal', function() {
        renderVisualOrgChartModal();
    });

    function renderVisualOrgChartModal() {
        if (!organigramaTreeData) {
            $('#chart-modal-container').html('<div class="alert alert-warning">No hay datos de organigrama disponibles.</div>');
            return;
        }

        $('#chart-modal-container').empty();
        modalOrgZoom = 1;

        $('#chart-modal-container').orgchart({
            'data': organigramaTreeData,
            'nodeContent': 'title',
            'pan': true,
            'zoom': true,
            'zoominLimit': 2,
            'zoomoutLimit': 0.3,
            'exportFilename': 'organigrama-institucional',
            'exportFileextension': 'png',
            'createNode': function($node, data) {
                $node.find('.title').attr('title', data.name);

                if (data.tipo) {
                    var colorNivel = { 'N1':'#6c757d','N2':'#17a2b8','N3':'#28a745','N4':'#dc3545' };
                    var badges = '<div style="text-align:center;padding:3px 4px;background:#fef3c7;border-bottom:1px solid #f59e0b;font-size:0.65rem;">';
                    badges += '<span class="badge badge-dark mr-1">' + data.tipo + '</span>';
                    if (data.nivel) {
                        badges += '<span class="badge" style="background:' + (colorNivel[data.nivel]||'#0284c7') + ';color:#fff">' + data.nivel + '</span>';
                    }
                    if (data.aop) {
                        badges += '<span class="badge badge-warning ml-1">AOP</span>';
                    }
                    badges += '</div>';
                    $node.find('.title').after(badges);
                }

                var extra = '<div class="node-extra p-2 bg-white" style="font-size:0.68rem; border-top:1px solid #f1f5f9;">';
                if (data.phone) {
                    extra += '<div class="text-muted mb-1"><i class="fa fa-phone mr-1 text-info"></i>' + data.phone + '</div>';
                }
                if (data.email) {
                    extra += '<div class="text-muted text-truncate" style="max-width:150px;"><i class="fa fa-envelope mr-1 text-warning"></i>' + data.email + '</div>';
                }
                extra += '</div>';
                $node.find('.content').after(extra);
            }
        });
    }

    $('#btnOrgZoomIn').on('click', function() {
        modalOrgZoom = Math.min(modalOrgZoom + 0.15, 2);
        $('#chart-modal-container .orgchart').css('transform', 'scale(' + modalOrgZoom + ')');
    });

    $('#btnOrgZoomOut').on('click', function() {
        modalOrgZoom = Math.max(modalOrgZoom - 0.15, 0.3);
        $('#chart-modal-container .orgchart').css('transform', 'scale(' + modalOrgZoom + ')');
    });

    $('#btnOrgZoomReset').on('click', function() {
        modalOrgZoom = 1;
        $('#chart-modal-container .orgchart').css('transform', 'scale(1)');
    });

    $('#btnOrgExportImg').on('click', function() {
        var $btn = $(this);
        $btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Exportando...').prop('disabled', true);
        if (typeof html2canvas !== 'undefined') {
            html2canvas(document.getElementById('chart-modal-container'), {
                backgroundColor: '#f8fafc',
                scale: 2,
            }).then(function(canvas) {
                var link = document.createElement('a');
                link.download = 'organigrama-diagrama.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                $btn.html('<i class="fa fa-camera mr-1"></i> Exportar PNG').prop('disabled', false);
            }).catch(function() {
                toastr.error('No se pudo generar la imagen.');
                $btn.html('<i class="fa fa-camera mr-1"></i> Exportar PNG').prop('disabled', false);
            });
        } else {
            toastr.info('Preparando módulo de captura...');
            $btn.html('<i class="fa fa-camera mr-1"></i> Exportar PNG').prop('disabled', false);
        }
    });

    // Abrir Modal Agregar Sub-dependencia Raíz
    $(document).on('click', '#btnAgregarSubRaiz', function () {
        var rootId = $(this).data('id');
        var rootName = $(this).data('nombre');
        $('#formDependencia')[0].reset();
        $('#dep_id').val('');
        $('#dep_parent_id').val(rootId);
        $('#dep_user_id').val('').trigger('change');
        $('#modalDepTitulo').html('<i class="fa fa-plus-circle mr-2"></i> Agregar Sub-dependencia a: ' + rootName);
        $('#modalDependencia').modal('show');
    });

    // Abrir Modal Agregar Sub-dependencia a un Nodo Específico
    $(document).on('click', '.btnAgregarSub', function () {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        $('#formDependencia')[0].reset();
        $('#dep_id').val('');
        $('#dep_parent_id').val(id);
        $('#dep_user_id').val('').trigger('change');
        $('#modalDepTitulo').html('<i class="fa fa-plus-circle mr-2"></i> Agregar Sub-dependencia a: ' + nombre);
        $('#modalDependencia').modal('show');
    });

    // Abrir Modal Editar Dependencia
    $(document).on('click', '.btnEditarDep', function () {
        var id = $(this).data('id');
        var editUrl = "{{ route('coordinador.organigrama.edit', ':id') }}".replace(':id', id);

        $.get(editUrl, function (res) {
            if (res.success) {
                var dep = res.dependencia;
                $('#dep_id').val(dep.id);
                $('#dep_parent_id').val(dep.parent_id);
                $('#dep_dependency').val(dep.dependency);
                $('#dep_manager').val(dep.manager);
                $('#dep_email').val(dep.email);
                $('#dep_phone').val(dep.phone);
                $('#dep_region').val(dep.region);
                $('#dep_tipo_establecimiento').val(dep.tipo_establecimiento);
                $('#dep_user_id').val(dep.user_id).trigger('change');
                $('#modalDepTitulo').html('<i class="fa fa-edit mr-2"></i> Editar Dependencia');
                $('#modalDependencia').modal('show');
            }
        }).fail(function (xhr) {
            toastr.error(xhr.responseJSON?.message || 'Error al cargar datos de la dependencia.');
        });
    });

    // Guardar Dependencia (Crear / Editar)
    $('#btnGuardarDep').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: "{{ route('coordinador.organigrama.store') }}",
            method: "POST",
            data: $('#formDependencia').serialize(),
            success: function (res) {
                $('#modalDependencia').modal('hide');
                toastr.success(res.message || 'Dependencia guardada correctamente.');
                btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Dependencia');
            },
            error: function (xhr) {
                var msg = xhr.responseJSON?.message || 'Error al guardar la dependencia.';
                if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                toastr.error(msg);
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Dependencia');
            }
        });
    });

    // Eliminar Dependencia
    $(document).on('click', '.btnEliminarDep', function () {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var delUrl = "{{ route('coordinador.organigrama.destroy', ':id') }}".replace(':id', id);

        Swal.fire({
            title: '¿Eliminar Dependencia?',
            html: '¿Estás seguro de eliminar <strong>' + nombre + '</strong> de la estructura orgánica?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: delUrl,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        toastr.success(res.message || 'Dependencia eliminada correctamente.');
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Error al eliminar la dependencia.');
                    }
                });
            }
        });
    });

    // Cambiar Organigrama manteniendo PEI y Hash
    window.cambiarOrganigramaEstructura = function(orgId) {
        var peiParam = '{{ $selectedPei ? $selectedPei->id : "" }}';
        var activeHash = localStorage.getItem('activeDashboardTab') || '#tab-organigrama';
        var url = '?organigrama_id=' + orgId;
        if (peiParam) url += '&pei_id=' + peiParam;
        window.location.href = url + activeHash;
    };

    // Conmutador de Ámbito de Usuarios (Integrantes PEI vs Todos los Usuarios)
    window.filtrarUsuariosContexto = function(allUsersVal) {
        var peiParam = '{{ $selectedPei ? $selectedPei->id : "" }}';
        var evParam  = '{{ request()->get("evento_id") }}';
        var activeHash = localStorage.getItem('activeDashboardTab') || '#tab-usuarios';
        
        var url = '?all_users=' + allUsersVal;
        if (peiParam) url += '&pei_id=' + peiParam;
        if (evParam)  url += '&evento_id=' + evParam;
        
        window.location.href = url + activeHash;
    };

    // ── Persistencia de Pestañas Activas vía LocalStorage + Hash URL ──
    $('#adminTabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        var targetHash = $(e.target).attr('href');
        localStorage.setItem('activeDashboardTab', targetHash);
        if (history.pushState) {
            history.pushState(null, null, targetHash);
        } else {
            location.hash = targetHash;
        }
    });

    // Restaurar pestaña activa al cargar la página (Soporta location.hash o localStorage)
    var activeTab = location.hash || localStorage.getItem('activeDashboardTab');
    if (activeTab) {
        if (!activeTab.startsWith('#')) activeTab = '#' + activeTab;
        var $tabLink = $('#adminTabs a[href="' + activeTab + '"]');
        if ($tabLink.length > 0) {
            $tabLink.tab('show');
        }
    }

    var datatablesSpanish = {
        sProcessing:     "Procesando...",
        sLengthMenu:     "Mostrar _MENU_ entradas",
        sZeroRecords:    "No se encontraron resultados",
        sEmptyTable:     "Ningún dato disponible en esta tabla",
        sInfo:           "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        sInfoEmpty:      "Mostrando 0 a 0 de 0 entradas",
        sInfoFiltered:   "(filtrado de _MAX_ entradas en total)",
        sInfoPostFix:    "",
        sSearch:         "Buscar:",
        sUrl:            "",
        sInfoThousands:  ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst:    "Primero",
            sLast:     "Último",
            sNext:     "Siguiente",
            sPrevious: "Anterior"
        },
        oAria: {
            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    };

    // Inicializar DataTables en pestañas principales (Ordenados descendentes para ver registros nuevos primero)
    if ($.fn.DataTable) {
        $('.dataTableInit').DataTable({
            language: datatablesSpanish,
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']]
        });
    }

    // ── Switches AJAX en tiempo real ──
    $('.cfg-toggle').on('change', function() {
        var campo = $(this).data('campo');
        var val   = $(this).is(':checked') ? 1 : 0;
        var card  = $(this).closest('.card');

        $.ajax({
            url: '{{ route("home-config.update") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', campo: campo, valor: val },
            success: function(res) {
                if (res.success) {
                    toastr.success(res.mensaje);
                    if (val) {
                        card.removeClass('border-secondary').addClass('border-success');
                    } else {
                        card.removeClass('border-success').addClass('border-secondary');
                    }
                }
            },
            error: function() { toastr.error('Error al actualizar.'); }
        });
    });

    // ── Selects AJAX en tiempo real ──
    $('.cfg-select').on('change', function() {
        var campo = $(this).data('campo');
        var val   = $(this).val();

        $.ajax({
            url: '{{ route("home-config.update") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', campo: campo, valor: val },
            success: function(res) {
                if (res.success) toastr.success(res.mensaje);
            },
            error: function() { toastr.error('Error al actualizar la selección.'); }
        });
    });

    // ── Drag and drop en Organigrama ──
    function initOrganigramaSortable() {
        document.querySelectorAll('#arbolOrganigramaCoordinador .sortable-group').forEach(function(el) {
            if (!el._sortable) {
                el._sortable = Sortable.create(el, {
                    group: 'nested-organigrama',
                    handle: '.drag-handle',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onEnd: function(evt) {
                        var itemEl = evt.item;
                        var nodoId = itemEl.getAttribute('data-id');
                        var newParentGroup = evt.to;
                        var parentNodoItem = newParentGroup.closest('.nodo-item');
                        var rootContainer = document.getElementById('arbolOrganigramaCoordinador');
                        var rootId = rootContainer ? rootContainer.getAttribute('data-root-id') : null;
                        var newParentId = parentNodoItem ? parentNodoItem.getAttribute('data-id') : rootId;

                        var moverUrl = "{{ route('globales.organigramas.mover', ':id') }}".replace(':id', nodoId);

                        $.ajax({
                            url: moverUrl,
                            type: 'POST',
                            data: { _token: '{{ csrf_token() }}', parent_id: newParentId },
                            success: function(res) {
                                toastr.success(res.message || 'Dependencia reordenada.');
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Error al mover la dependencia.');
                                location.reload();
                            }
                        });
                    }
                });
            }
        });
    }

    initOrganigramaSortable();

    // ════════════════════════════════════════════════════════════════════════════
    // HANDLERS AJAX PARA MODAL DE TELEMETRÍA DE USUARIO IN-SITU
    // ════════════════════════════════════════════════════════════════════════════
    window.abrirModalTelemetriaUsuario = function(userId) {
        $('#tel_user_name').text('Cargando...');
        $('#tel_user_email').text('');
        $('#tel_user_group').text('—');
        $('#tel_user_online_badge').html('');
        $('#tel_user_points').text('🏆 0 pts');
        $('#tel_kpi_total').text('0');
        $('#tel_kpi_last_act').text('—');
        $('#tel_kpi_ip').text('—');
        $('#tel_timeline_list').html('<li class="list-group-item text-center text-muted small py-4"><i class="fa fa-spinner fa-spin mr-1"></i> Consultando telemetría...</li>');
        $('#modalTelemetriaUsuario').modal('show');

        $.ajax({
            url: '{{ url("admin/globales/users") }}/' + userId + '/telemetry',
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    var u = res.user;
                    var k = res.kpis;

                    $('#tel_user_name').text(u.name);
                    $('#tel_user_email').text(u.email);
                    if (u.avatar_url) $('#tel_user_avatar').attr('src', u.avatar_url);
                    $('#tel_user_group').html('<i class="fa fa-users text-info mr-1"></i> ' + u.group_name);
                    $('#tel_user_points').text('🏆 ' + (u.points || 0).toLocaleString() + ' pts');

                    if (u.is_online) {
                        $('#tel_user_online_badge').html('<span class="badge badge-success px-3 py-2 font-weight-bold" style="border-radius:15px; background:#10b981;"><i class="fa fa-circle text-white mr-1" style="font-size:0.5rem"></i> En Línea</span>');
                    } else {
                        $('#tel_user_online_badge').html('<span class="badge badge-light border text-muted px-3 py-2" style="border-radius:15px;"><i class="fa fa-circle text-secondary mr-1" style="font-size:0.5rem"></i> Desconectado</span>');
                    }

                    $('#tel_kpi_total').text((k.total_activities || 0).toLocaleString());
                    $('#tel_kpi_last_act').text(k.last_activity);
                    $('#tel_kpi_ip').text(k.last_ip);

                    var $list = $('#tel_timeline_list');
                    $list.empty();
                    if (res.timeline && res.timeline.length > 0) {
                        $.each(res.timeline, function(i, item) {
                            var modBadge = '<span class="badge badge-info mr-2" style="font-size:0.7rem">' + item.module + '</span>';
                            var html = '<li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">' +
                                '<div>' + modBadge + '<strong class="small text-dark">' + item.description + '</strong><div class="small text-muted" style="font-size:0.72rem"><i class="fa fa-laptop mr-1"></i>IP: ' + item.ip + '</div></div>' +
                                '<span class="badge badge-light border text-muted" style="font-size:0.7rem"><i class="fa fa-clock mr-1"></i>' + item.hace + '</span>' +
                                '</li>';
                            $list.append(html);
                        });
                    } else {
                        $list.html('<li class="list-group-item text-center text-muted small py-4"><i class="fa fa-info-circle mr-1"></i> El funcionario aún no registra interacciones en el módulo.</li>');
                    }
                }
            },
            error: function() {
                toastr.error('No se pudo consultar la información de telemetría del funcionario.');
            }
        });
    };

    // ════════════════════════════════════════════════════════════════════════════
    // HANDLERS AJAX PARA MODAL DE USUARIOS IN-SITU
    // ════════════════════════════════════════════════════════════════════════════
    window.abrirModalNuevoUsuario = function() {
        $('#formUsuarioDashboard')[0].reset();
        $('#modal_user_id').val('');
        $('#modal_user_group_id').val('').trigger('change');
        $('#modal_user_roles').val([]).trigger('change');
        $('#modalUserHeading').html('<i class="fa fa-user-plus mr-2"></i> Nuevo Usuario');
        $('#modalUsuarioDashboard').modal('show');
    };

    window.abrirModalEditarUsuario = function(userId) {
        $.ajax({
            url: '{{ url("admin/globales/users") }}/' + userId + '/edit',
            type: 'GET',
            success: function(res) {
                var u = res.user;
                $('#modal_user_id').val(u.id);
                $('#modal_user_name').val(u.name);
                $('#modal_user_email').val(u.email);
                $('#modal_user_password').val('');
                $('#modal_user_confirm_password').val('');
                $('#modal_user_group_id').val(u.group_id).trigger('change');

                var assignedRoleNames = [];
                if (res.rolesChecked) {
                    assignedRoleNames = res.rolesChecked.map(function(r) { return r.text; });
                }
                $('#modal_user_roles').val(assignedRoleNames).trigger('change');

                $('#modalUserHeading').html('<i class="fa fa-user-edit mr-2"></i> Editar Usuario: ' + u.name);
                $('#modalUsuarioDashboard').modal('show');
            },
            error: function() {
                toastr.error('No se pudo cargar la información del usuario.');
            }
        });
    };

    $('#formUsuarioDashboard').on('submit', function(e) {
        e.preventDefault();
        $('#btnGuardarUserModal').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '{{ route("globales.users.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#modalUsuarioDashboard').modal('hide');
                $('#btnGuardarUserModal').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Usuario');
                toastr.success(res.success || 'Usuario guardado correctamente.');
            },
            error: function(xhr) {
                $('#btnGuardarUserModal').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Usuario');
                var msg = xhr.responseJSON?.message || 'Error al guardar el usuario.';
                toastr.error(msg);
            }
        });
    });

    // ════════════════════════════════════════════════════════════════════════════
    // HANDLERS AJAX PARA MODAL DE GRUPOS IN-SITU
    // ════════════════════════════════════════════════════════════════════════════
    window.abrirModalNuevoGrupo = function() {
        $('#formGrupoDashboard')[0].reset();
        $('#modal_group_id').val('');
        $('#modalGroupHeading').html('<i class="fa fa-layer-group mr-2"></i> Nuevo Grupo de Trabajo');
        $('#modalGrupoDashboard').modal('show');
    };

    // Selector de Evento Raíz en Pestaña Grupos (con Select2)
    $('#eventoGroupFilterSelect').select2({
        placeholder: "— Seleccionar Evento Raíz —",
        allowClear: false,
        width: '240px'
    }).on('change', function() {
        var peiParam = '{{ $selectedPei ? $selectedPei->id : "" }}';
        var activeHash = localStorage.getItem('activeDashboardTab') || '#tab-grupos';
        var url = '?evento_id=' + $(this).val();
        if (peiParam) url += '&pei_id=' + peiParam;
        window.location.href = url + activeHash;
    });

    window.abrirModalEditarGrupo = function(groupId) {
        $.ajax({
            url: '{{ url("admin/globales/groups") }}/' + groupId + '/edit',
            type: 'GET',
            success: function(res) {
                var g = res.group ? res.group : res;
                $('#modal_group_id').val(g.id);
                $('#modal_group_name').val(g.name);
                $('#modalGroupHeading').html('<i class="fa fa-edit mr-2"></i> Editar Grupo: ' + g.name);
                $('#modalGrupoDashboard').modal('show');
            },
            error: function() {
                toastr.error('No se pudo cargar la información del grupo.');
            }
        });
    };

    $('#formGrupoDashboard').on('submit', function(e) {
        e.preventDefault();
        $('#btnGuardarGroupModal').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '{{ route("globales.groups.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#modalGrupoDashboard').modal('hide');
                $('#btnGuardarGroupModal').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Grupo');
                toastr.success(res.success || 'Grupo guardado correctamente.');
            },
            error: function(xhr) {
                $('#btnGuardarGroupModal').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Grupo');
                var msg = xhr.responseJSON?.message || 'Error al guardar el grupo.';
                toastr.error(msg);
            }
        });
    });

    // Select2 para Asignación de Integrantes a Subgrupos
    $('#modal_subgrupo_users_select2').select2({
        dropdownParent: $('#modalDetalleGrupoSub'),
        placeholder: "— Seleccionar integrantes para el subgrupo —",
        allowClear: true,
        width: '100%'
    });

    // Abrir Modal Detalle de Grupo (Integrantes y Subgrupos In-Situ)
    window.abrirModalDetalleGrupo = function(groupId, groupName) {
        $('#nombreGrupoSubHeading').text(groupName);
        $('#subgrupo_parent_id').val(groupId);
        $('#modal_detalle_subgrupo_id').val(groupId);
        $('#tab-miembros-grupo-link').tab('show');

        $.ajax({
            url: '{{ url("admin/globales/groups") }}/' + groupId + '/edit',
            type: 'GET',
            success: function(res) {
                var members = res.group && res.group.members ? res.group.members : [];
                $('#cantMiembrosGrupo').text(members.length);

                // Preseleccionar en Select2
                var memberIds = members.map(function(m) { return m.id; });
                $('#modal_subgrupo_users_select2').val(memberIds).trigger('change');

                // Renderizar DataTables de Integrantes
                if ($.fn.DataTable.isDataTable('#tablaMiembrosGrupoModal')) {
                    $('#tablaMiembrosGrupoModal').DataTable().clear().destroy();
                }

                var tbodyM = $('#tablaMiembrosGrupoModal tbody');
                tbodyM.empty();

                members.forEach(function(m, idx) {
                    tbodyM.append('<tr>' +
                        '<td>' + (idx + 1) + '</td>' +
                        '<td class="font-weight-bold text-dark">' + $('<div>').text(m.name).html() + '</td>' +
                        '<td><span class="text-muted"><i class="fa fa-envelope mr-1"></i>' + $('<div>').text(m.email).html() + '</span></td>' +
                        '</tr>');
                });

                if (members.length > 0) {
                    $('#tablaMiembrosGrupoModal').DataTable({
                        language: datatablesSpanish,
                        pageLength: 5,
                        responsive: true,
                        order: [[0, 'asc']]
                    });
                }

                $.ajax({
                    url: '{{ url("admin/globales/groups") }}/' + groupId,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    type: 'GET',
                    success: function(subRes) {
                        var subData = subRes.data ? subRes.data : (Array.isArray(subRes) ? subRes : []);
                        $('#cantSubgruposGrupo').text(subData.length);

                        if ($.fn.DataTable.isDataTable('#tablaSubgruposGrupoModal')) {
                            $('#tablaSubgruposGrupoModal').DataTable().clear().destroy();
                        }

                        var tbodyS = $('#tablaSubgruposGrupoModal tbody');
                        tbodyS.empty();

                        subData.forEach(function(sg, idx) {
                            var memNames = sg.members ? sg.members : (sg.members_count ? sg.members_count + ' miembros' : 'Sin integrantes');
                            tbodyS.append('<tr>' +
                                '<td>' + (idx + 1) + '</td>' +
                                '<td class="font-weight-bold text-dark">' + $('<div>').text(sg.name).html() + '</td>' +
                                '<td><span class="badge badge-info">' + $('<div>').text(memNames).html() + '</span></td>' +
                                '</tr>');
                        });

                        if (subData.length > 0) {
                            $('#tablaSubgruposGrupoModal').DataTable({
                                language: datatablesSpanish,
                                pageLength: 5,
                                responsive: true,
                                order: [[0, 'asc']]
                            });
                        }

                        $('#modalDetalleGrupoSub').modal('show');
                    },
                    error: function() {
                        $('#modalDetalleGrupoSub').modal('show');
                    }
                });
            },
            error: function() {
                toastr.error('No se pudo cargar la información del grupo.');
            }
        });
    };

    // Guardar Asignación de Integrantes vía Select2
    $('#formAsignarIntegrantesSubgrupo').on('submit', function(e) {
        e.preventDefault();
        var groupId = $('#modal_detalle_subgrupo_id').val();
        $('#btnGuardarIntegrantesSubgrupo').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '{{ route("globales.groups.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#btnGuardarIntegrantesSubgrupo').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Integrantes');
                toastr.success(res.success || 'Integrantes actualizados correctamente.');
                
                var selectedIds = $('#modal_subgrupo_users_select2').val() || [];
                $('#cantMiembrosGrupo').text(selectedIds.length);
                $('#badge_group_members_count_' + groupId).html('<i class="fa fa-users mr-1"></i> ' + selectedIds.length + ' Miembros');

                abrirModalDetalleGrupo(groupId, $('#nombreGrupoSubHeading').text());
            },
            error: function(xhr) {
                $('#btnGuardarIntegrantesSubgrupo').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Integrantes');
                var msg = xhr.responseJSON?.message || 'Error al guardar los integrantes.';
                toastr.error(msg);
            }
        });
    });

    // Crear Subgrupo Hijo en Tiempo Real
    $('#formCrearSubgrupoRapido').on('submit', function(e) {
        e.preventDefault();
        var parentId = $('#subgrupo_parent_id').val();
        $('#btnCrearSubgrupoRapido').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '{{ route("globales.groups.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#btnCrearSubgrupoRapido').prop('disabled', false).html('<i class="fa fa-plus-circle mr-1"></i> Crear Subgrupo');
                toastr.success(res.success || 'Subgrupo creado correctamente.');
                $('#input_nuevo_subgrupo_nombre').val('');
                abrirModalDetalleGrupo(parentId, $('#nombreGrupoSubHeading').text());
            },
            error: function(xhr) {
                $('#btnCrearSubgrupoRapido').prop('disabled', false).html('<i class="fa fa-plus-circle mr-1"></i> Crear Subgrupo');
                var msg = xhr.responseJSON?.message || 'Error al crear el subgrupo.';
                toastr.error(msg);
            }
        });
    });

    // ════════════════════════════════════════════════════════════════════════════
    // HANDLERS AJAX PARA MODALES DE ROLES (EDICIÓN EN LÍNEA + DATATABLES)
    // ════════════════════════════════════════════════════════════════════════════
    window.abrirModalGestionRoles = function() {
        $('#modalRolDashboard').modal('show');
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#tablaRolesModal')) {
                var dt = $('#tablaRolesModal').DataTable();
                dt.columns.adjust();
                if (dt.responsive) dt.responsive.recalc();
            } else {
                $('#tablaRolesModal').DataTable({
                    language: datatablesSpanish,
                    pageLength: 5,
                    responsive: true,
                    order: [[0, 'desc']]
                });
            }
        }, 200);
    };

    // Crear Rol Rápido sin cerrar modal ni recargar página
    $('#formCrearRolRapido').on('submit', function(e) {
        e.preventDefault();
        $('#btnCrearRolRapido').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '{{ route("globales.roles.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#btnCrearRolRapido').prop('disabled', false).html('<i class="fa fa-plus-circle mr-1"></i> Crear Rol');
                toastr.success(res.message || 'Rol creado correctamente.');
                $('#input_nuevo_rol_nombre').val('');

                if (res.role) {
                    var r = res.role;
                    var escapedName = $('<div>').text(r.name).html();
                    var safeName = r.name.replace(/'/g, "\\'");

                    // Actualizar Select2 de roles en Modal de Usuarios
                    if ($('#modal_user_roles option[value="' + r.name + '"]').length === 0) {
                        $('#modal_user_roles').append(new Option(r.name, r.name, false, false));
                    }

                    if ($.fn.DataTable.isDataTable('#tablaRolesModal')) {
                        var dt = $('#tablaRolesModal').DataTable();
                        var newRowNode = dt.row.add([
                            dt.rows().count() + 1,
                            '<span id="span_role_name_' + r.id + '" class="font-weight-bold text-dark">' + escapedName + '</span>' +
                            '<input type="text" id="input_role_name_' + r.id + '" class="form-control form-control-sm font-weight-bold d-none" value="' + escapedName + '">',
                            '<button type="button" class="btn btn-sm btn-outline-info btn-round px-2 py-1 font-weight-bold" onclick="abrirModalPermisosRol(\'' + r.id + '\', \'' + safeName + '\')" title="Gestionar Permisos del Rol">' +
                            '<i class="fa fa-key mr-1"></i> <span id="badge_perm_count_' + r.id + '">0</span> permisos</button>',
                            '<div class="text-center">' +
                            '<div id="actions_default_' + r.id + '" class="d-flex justify-content-center" style="gap: 4px;">' +
                            '<button type="button" class="btn btn-circle btn-info" onclick="activarEdicionInlineRol(\'' + r.id + '\')" title="Editar Nombre En Línea"><i class="fa fa-edit"></i></button> ' +
                            '<button type="button" class="btn btn-circle btn-primary" onclick="abrirModalPermisosRol(\'' + r.id + '\', \'' + safeName + '\')" title="Gestionar Permisos"><i class="fa fa-key"></i></button> ' +
                            '<button type="button" class="btn btn-circle btn-danger" onclick="eliminarRolInModal(\'' + r.id + '\', \'' + safeName + '\')" title="Eliminar Rol"><i class="fa fa-trash"></i></button>' +
                            '</div>' +
                            '<div id="actions_editing_' + r.id + '" class="d-none justify-content-center" style="gap: 4px;">' +
                            '<button type="button" class="btn btn-circle btn-success" onclick="guardarEdicionInlineRol(\'' + r.id + '\')" title="Guardar Cambios"><i class="fa fa-check"></i></button> ' +
                            '<button type="button" class="btn btn-circle btn-secondary" onclick="cancelarEdicionInlineRol(\'' + r.id + '\', \'' + safeName + '\')" title="Cancelar"><i class="fa fa-times"></i></button>' +
                            '</div>' +
                            '</div>'
                        ]).draw(false).node();

                        $(newRowNode).attr('id', 'rol_row_' + r.id);
                        dt.page('first').draw('page');
                    }
                    actualizarContadorRolesModal();
                }
            },
            error: function(xhr) {
                $('#btnCrearRolRapido').prop('disabled', false).html('<i class="fa fa-plus-circle mr-1"></i> Crear Rol');
                var msg = xhr.responseJSON?.message || xhr.responseJSON?.errors?.name?.[0] || 'Error al crear el rol.';
                toastr.error(msg);
            }
        });
    });

    // Activar Edición Inline en el renglón
    window.activarEdicionInlineRol = function(roleId) {
        $('#span_role_name_' + roleId).addClass('d-none');
        $('#input_role_name_' + roleId).removeClass('d-none').focus();
        $('#actions_default_' + roleId).addClass('d-none').removeClass('d-flex');
        $('#actions_editing_' + roleId).removeClass('d-none').addClass('d-flex');
    };

    window.cancelarEdicionInlineRol = function(roleId, originalName) {
        $('#input_role_name_' + roleId).val(originalName).addClass('d-none');
        $('#span_role_name_' + roleId).removeClass('d-none');
        $('#actions_editing_' + roleId).addClass('d-none').removeClass('d-flex');
        $('#actions_default_' + roleId).removeClass('d-none').addClass('d-flex');
    };

    window.guardarEdicionInlineRol = function(roleId) {
        var newName = $('#input_role_name_' + roleId).val();
        if (!newName) {
            toastr.error('El nombre del rol no puede estar vacío.');
            return;
        }

        $.ajax({
            url: '{{ route("globales.roles.store") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', role_id: roleId, name: newName },
            success: function(res) {
                toastr.success(res.message || 'Rol actualizado correctamente.');
                $('#span_role_name_' + roleId).text(newName).removeClass('d-none');
                $('#input_role_name_' + roleId).addClass('d-none');
                $('#actions_editing_' + roleId).addClass('d-none').removeClass('d-flex');
                $('#actions_default_' + roleId).removeClass('d-none').addClass('d-flex');
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.message || xhr.responseJSON?.errors?.name?.[0] || 'Error al actualizar el rol.';
                toastr.error(msg);
            }
        });
    };

    // Sub-Modal de Permisos de un Rol
    window.abrirModalPermisosRol = function(roleId, roleName) {
        $('#sub_modal_role_id').val(roleId);
        $('#sub_modal_role_name').val(roleName);
        $('#nombreRolSubModalHeading').text(roleName);
        $('.chk-permiso-submodal').prop('checked', false);

        $.ajax({
            url: '{{ url("admin/globales/roles") }}/' + roleId + '/edit-ajax',
            type: 'GET',
            success: function(res) {
                if (res.rolePermissions) {
                    res.rolePermissions.forEach(function(permId) {
                        $('#sub_perm_' + permId).prop('checked', true);
                    });
                }
                $('#modalPermisosRolSub').modal('show');
            },
            error: function() {
                toastr.error('No se pudieron obtener los permisos del rol.');
            }
        });
    };

    $('#btnSelTodosPermsSub').on('click', function() {
        $('.chk-permiso-submodal').prop('checked', true);
    });

    $('#btnDeselTodosPermsSub').on('click', function() {
        $('.chk-permiso-submodal').prop('checked', false);
    });

    $('#formPermisosRolSubModal').on('submit', function(e) {
        e.preventDefault();
        var roleId = $('#sub_modal_role_id').val();
        $('#btnGuardarPermisosSubModal').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '{{ route("globales.roles.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#btnGuardarPermisosSubModal').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Permisos');
                $('#modalPermisosRolSub').modal('hide');
                toastr.success('Permisos asignados correctamente al rol.');

                // Actualizar badge de permisos en la tabla
                var countChecked = $('.chk-permiso-submodal:checked').length;
                $('#badge_perm_count_' + roleId).text(countChecked);
            },
            error: function(xhr) {
                $('#btnGuardarPermisosSubModal').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Permisos');
                var msg = xhr.responseJSON?.message || 'Error al guardar los permisos.';
                toastr.error(msg);
            }
        });
    });

    // Modales de Permisos Generales (Creación dinámica en tabla sin recarga)
    window.abrirModalGestionPermisos = function() {
        $('#formPermisoDashboard')[0].reset();
        $('#modal_permiso_id').val('');
        $('#modalPermisoDashboard').modal('show');
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#tablaPermisosModal')) {
                var dt = $('#tablaPermisosModal').DataTable();
                dt.columns.adjust();
                if (dt.responsive) dt.responsive.recalc();
            } else {
                $('#tablaPermisosModal').DataTable({
                    language: datatablesSpanish,
                    pageLength: 5,
                    responsive: true,
                    order: [[0, 'desc']]
                });
            }
        }, 200);
    };

    // Inline Permission Editing JS Functions
    window.activarEdicionInlinePermiso = function(permId) {
        $('#span_perm_name_' + permId).addClass('d-none');
        $('#input_perm_name_' + permId).removeClass('d-none').focus();
        $('#perm_actions_default_' + permId).addClass('d-none').removeClass('d-flex');
        $('#perm_actions_editing_' + permId).removeClass('d-none').addClass('d-flex');
    };

    window.cancelarEdicionInlinePermiso = function(permId, originalName) {
        $('#input_perm_name_' + permId).val(originalName).addClass('d-none');
        $('#span_perm_name_' + permId).removeClass('d-none');
        $('#perm_actions_editing_' + permId).addClass('d-none').removeClass('d-flex');
        $('#perm_actions_default_' + permId).removeClass('d-none').addClass('d-flex');
    };

    window.guardarEdicionInlinePermiso = function(permId) {
        var newName = $('#input_perm_name_' + permId).val().trim();
        if (!newName) {
            toastr.error('El nombre del permiso no puede estar vacío.');
            return;
        }

        $.ajax({
            url: '{{ route("globales.permisos.store") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', permiso_id: permId, name: newName },
            success: function(res) {
                toastr.success(res.success || 'Permiso actualizado correctamente.');
                $('#span_perm_name_' + permId).text(newName).removeClass('d-none');
                $('#input_perm_name_' + permId).addClass('d-none');
                $('#perm_actions_editing_' + permId).addClass('d-none').removeClass('d-flex');
                $('#perm_actions_default_' + permId).removeClass('d-none').addClass('d-flex');
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.message || 'Error al actualizar el permiso.';
                toastr.error(msg);
            }
        });
    };

    $('#formPermisoDashboard').on('submit', function(e) {
        e.preventDefault();
        $('#btnGuardarPermisoModal').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: '{{ route("globales.permisos.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#btnGuardarPermisoModal').prop('disabled', false).html('<i class="fa fa-plus-circle mr-1"></i> Registrar Permiso');
                toastr.success(res.success || 'Permiso guardado correctamente.');
                var nameVal = $('#modal_permiso_name').val().trim();
                $('#modal_permiso_name').val('');

                var p = res.permission || { id: Date.now(), name: nameVal };
                var safePermName = p.name.replace(/'/g, "\\'");
                var escapedName = $('<div>').text(p.name).html();

                if ($.fn.DataTable.isDataTable('#tablaPermisosModal')) {
                    var dt = $('#tablaPermisosModal').DataTable();
                    var newRowNode = dt.row.add([
                        dt.rows().count() + 1,
                        '<code id="span_perm_name_' + p.id + '" class="font-weight-bold text-dark" style="font-size:0.85rem">' + escapedName + '</code>' +
                        '<input type="text" id="input_perm_name_' + p.id + '" class="form-control form-control-sm font-weight-bold d-none" value="' + escapedName + '">',
                        '<div class="text-center">' +
                        '<div id="perm_actions_default_' + p.id + '" class="d-flex justify-content-center" style="gap: 4px;">' +
                        '<button type="button" class="btn btn-circle btn-info" onclick="activarEdicionInlinePermiso(\'' + p.id + '\')" title="Editar Permiso"><i class="fa fa-edit"></i></button> ' +
                        '<button type="button" class="btn btn-circle btn-danger" onclick="eliminarPermisoInModal(\'' + p.id + '\', \'' + safePermName + '\')" title="Eliminar Permiso"><i class="fa fa-trash"></i></button>' +
                        '</div>' +
                        '<div id="perm_actions_editing_' + p.id + '" class="d-none justify-content-center" style="gap: 4px;">' +
                        '<button type="button" class="btn btn-circle btn-success" onclick="guardarEdicionInlinePermiso(\'' + p.id + '\')" title="Guardar Cambios"><i class="fa fa-check"></i></button> ' +
                        '<button type="button" class="btn btn-circle btn-secondary" onclick="cancelarEdicionInlinePermiso(\'' + p.id + '\', \'' + safePermName + '\')" title="Cancelar"><i class="fa fa-times"></i></button>' +
                        '</div>' +
                        '</div>'
                    ]).draw(false).node();

                    $(newRowNode).attr('id', 'perm_row_' + p.id);
                    dt.page('first').draw('page');
                }
                actualizarContadorPermisosModal();
            },
            error: function(xhr) {
                $('#btnGuardarPermisoModal').prop('disabled', false).html('<i class="fa fa-plus-circle mr-1"></i> Registrar Permiso');
                var msg = xhr.responseJSON?.message || 'Error al guardar el permiso.';
                toastr.error(msg);
            }
        });
    });

    // ════════════════════════════════════════════════════════════════════════════
    // ELIMINACIONES VÍA SWEETALERT2 + AJAX IN-SITU
    // ════════════════════════════════════════════════════════════════════════════
    window.eliminarUsuario = function(id, name) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            html: 'Estás a punto de eliminar a <strong>' + name + '</strong>. Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/globales/users") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Usuario eliminado correctamente.');
                        $('#user_row_' + id).fadeOut(300, function() { $(this).remove(); });
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Error al eliminar el usuario.';
                        toastr.error(msg);
                    }
                });
            }
        });
    };

    window.eliminarGrupo = function(id, name) {
        Swal.fire({
            title: '¿Eliminar grupo de trabajo?',
            html: 'Estás a punto de eliminar el grupo <strong>' + name + '</strong>.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/globales/groups") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Grupo eliminado correctamente.');
                        $('#group_row_' + id).fadeOut(300, function() { $(this).remove(); });
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Error al eliminar el grupo.';
                        toastr.error(msg);
                    }
                });
            }
        });
    };

    window.eliminarRolInModal = function(id, name) {
        Swal.fire({
            title: '¿Eliminar rol?',
            html: 'Estás a punto de eliminar el rol <strong>' + name + '</strong>.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/globales/roles") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Rol eliminado correctamente.');
                        if ($.fn.DataTable.isDataTable('#tablaRolesModal')) {
                            var dt = $('#tablaRolesModal').DataTable();
                            dt.row('#rol_row_' + id).remove().draw(false);
                            actualizarContadorRolesModal();
                        } else {
                            $('#rol_row_' + id).fadeOut(300, function() { $(this).remove(); });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('No se pudo eliminar el rol.');
                    }
                });
            }
        });
    };

    window.eliminarPermisoInModal = function(id, name) {
        Swal.fire({
            title: '¿Eliminar permiso?',
            html: 'Estás a punto de eliminar el permiso <code>' + name + '</code>.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/globales/permisos") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Permiso eliminado correctamente.');
                        if ($.fn.DataTable.isDataTable('#tablaPermisosModal')) {
                            var dt = $('#tablaPermisosModal').DataTable();
                            dt.row('#perm_row_' + id).remove().draw(false);
                            actualizarContadorPermisosModal();
                        } else {
                            $('#perm_row_' + id).fadeOut(300, function() { $(this).remove(); });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('No se pudo eliminar el permiso.');
                    }
                });
            }
        });
    };

    function actualizarContadorRolesModal() {
        if ($.fn.DataTable.isDataTable('#tablaRolesModal')) {
            var count = $('#tablaRolesModal').DataTable().rows().count();
            $('#cantRolesModalHeader').text(count);
            $('#cantRolesMainBtn').text(count);
        }
    }

    function actualizarContadorPermisosModal() {
        if ($.fn.DataTable.isDataTable('#tablaPermisosModal')) {
            var count = $('#tablaPermisosModal').DataTable().rows().count();
            $('#cantPermisosModalHeader').text(count);
            $('#cantPermisosMainBtn').text(count);
        }
    }
});

function abrirModalPremiarGrupo(groupId, groupName, membersCount) {
    $('#premiar_group_id').val(groupId);
    $('#premiar_group_name').text(groupName);
    $('#premiar_group_members_count').text(membersCount);
    $('#premiar_points').val(100);
    $('#premiar_title').val('Premio por Cierre de Semana Productiva');
    $('#premiar_description').val('');
    $('#premiar_is_retroactive').prop('checked', true);
    $('#modalOtorgarPuntosGrupo').modal('show');
}

function guardarPremiacionGrupo(e) {
    e.preventDefault();
    var groupId = $('#premiar_group_id').val();
    var $btn = $('#btnSubmitPremiarGrupo');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Acreditando...');

    $.ajax({
        url: "{{ url('admin/globales/groups') }}/" + groupId + "/otorgar-puntos",
        type: "POST",
        data: $('#formOtorgarPuntosGrupo').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(resp) {
            $btn.prop('disabled', false).html('<i class="fa fa-trophy mr-1"></i> Acreditar Puntos al Equipo');
            if (resp.success) {
                $('#modalOtorgarPuntosGrupo').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Equipo Premiado Exitosamente!',
                    text: resp.message,
                    confirmButtonColor: '#f59e0b'
                }).then(function() {
                    location.reload();
                });
            } else {
                Swal.fire('Error', resp.message || 'No se pudo procesar la acreditación.', 'error');
            }
        },
        error: function(err) {
            $btn.prop('disabled', false).html('<i class="fa fa-trophy mr-1"></i> Acreditar Puntos al Equipo');
            var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Ocurrió un error inesperado al procesar la acreditación.';
            Swal.fire('Error', msg, 'error');
        }
    });
}
</script>

<!-- MODAL DE PREMIACIÓN MASIVA A GRUPO DE TRABAJO -->
<div class="modal fade" id="modalOtorgarPuntosGrupo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title font-weight-bold d-flex align-items-center mb-0">
                    <i class="fa fa-trophy mr-2" style="font-size: 1.3rem;"></i>
                    <span>Premiar Equipo de Trabajo</span>
                </h5>
                <button type="button" class="close text-white opacity-9" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formOtorgarPuntosGrupo" onsubmit="guardarPremiacionGrupo(event)">
                @csrf
                <input type="hidden" id="premiar_group_id" name="group_id">
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 shadow-sm mb-3" style="border-radius: 10px; background-color: #fffbeb; color: #92400e;">
                        <i class="fa fa-info-circle mr-1"></i>
                        Vas a premiar a los integrantes del equipo: <strong id="premiar_group_name"></strong> (<span id="premiar_group_members_count">0</span> integrantes registrados).
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Puntos a Otorgar a Cada Integrante <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light text-warning font-weight-bold">⭐</span>
                            </div>
                            <input type="number" class="form-control font-weight-bold" id="premiar_points" name="points" value="100" min="1" max="10000" required style="font-size: 1.1rem;">
                        </div>
                        <small class="text-muted">Por defecto: 100 puntos por cerrar una semana productiva.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Título / Motivo del Premio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="premiar_title" name="title" value="Premio por Cierre de Semana Productiva" placeholder="Ej: Reconocimiento Semana Productiva 10 al 14 de Agosto" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Observación o Detalles Adicionales</label>
                        <textarea class="form-control" id="premiar_description" name="description" rows="2" placeholder="Ej: Excelente desempeño en el cierre de metas y coordinación de proyectos."></textarea>
                    </div>

                    <div class="card border border-warning bg-light p-3 mb-0" style="border-radius: 10px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="premiar_is_retroactive" name="is_retroactive" value="1" checked>
                            <label class="custom-control-label font-weight-bold text-dark cursor-pointer" for="premiar_is_retroactive">
                                🔄 Aplicar Acreditación Retroactiva Automática
                            </label>
                        </div>
                        <small class="text-muted mt-1 d-block" style="line-height: 1.35;">
                            Al dejar activada esta casilla, cuando incorpores un nuevo funcionario a este equipo en el futuro, el sistema le acreditará automáticamente este bono de 100 pts para no dejarlo en desventaja.
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark font-weight-bold btn-round shadow-sm px-4" id="btnSubmitPremiarGrupo">
                        <i class="fa fa-trophy mr-1"></i> Acreditar Puntos al Equipo
                    </button>
                </div>
            </form>
</div>
</div>

{{-- Modal Lectura Cómoda de Aportes de Asesoría --}}
<div class="modal fade" id="modalLecturaAportes" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 1100px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div id="modalLecturaAportesBody" class="p-0"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    if ($.fn.select2) {
        $('.select2InModalJunta').select2({
            dropdownParent: $('#modalJuntaConsultiva'),
            width: '100%'
        });
    }
});

const juntasDataMap = @json($juntasList->keyBy('id'));

window.abrirModalNuevaJunta = function() {
    $('#modal_junta_id').val('');
    $('#modalJuntaHeading').html('<i class="fa fa-balance-scale mr-2"></i> Nueva Junta Consultiva');
    $('#modal_junta_nombre').val('');
    $('#modal_junta_programa').val('salud');
    $('#modal_junta_ambito').val('');
    $('#modal_junta_fines').val('');
    $('#modal_junta_atribuciones').val('');
    $('#modal_junta_presi_nombre').val('');
    $('#modal_junta_presi_cargo').val('Presidente de la Junta Consultiva');
    $('#modal_junta_integrantes').val([]).trigger('change');
    $('#collapseNuevoUsuarioJunta').collapse('hide');
    $('#modalJuntaConsultiva').modal('show');
};

window.abrirModalEditarJunta = function(id) {
    const jta = juntasDataMap[id];
    if (!jta) return;

    $('#modal_junta_id').val(jta.id);
    $('#modalJuntaHeading').html('<i class="fa fa-edit mr-2"></i> Editar Junta Consultiva: ' + jta.nombre);
    $('#modal_junta_nombre').val(jta.nombre);
    $('#modal_junta_programa').val(jta.programa);
    $('#modal_junta_ambito').val(jta.ambito_competencia || '');
    $('#modal_junta_fines').val(jta.fines || '');
    $('#modal_junta_atribuciones').val(jta.atribuciones || '');
    $('#modal_junta_presi_nombre').val(jta.presidente_nombre || '');
    $('#modal_junta_presi_cargo').val(jta.presidente_cargo || '');

    const integranteIds = (jta.integrantes || []).map(function(m) { return m.id; });
    $('#modal_junta_integrantes').val(integranteIds).trigger('change');
    $('#collapseNuevoUsuarioJunta').collapse('hide');
    $('#modalJuntaConsultiva').modal('show');
};

window.guardarJuntaConsultiva = function(e) {
    e.preventDefault();
    const $btn = $('#btnGuardarJuntaModal');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

    $.ajax({
        url: '{{ route("admin.juntas.store") }}',
        type: 'POST',
        data: $('#formJuntaConsultiva').serialize(),
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Junta Consultiva');
            if (res.success) {
                $('#modalJuntaConsultiva').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Junta Consultiva Guardada!',
                    text: res.message,
                    confirmButtonColor: '#2563eb'
                }).then(function() {
                    location.reload();
                });
            } else {
                Swal.fire('Error', res.message || 'No se pudo guardar la Junta.', 'error');
            }
        },
        error: function(err) {
            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Junta Consultiva');
            var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error al procesar la solicitud.';
            Swal.fire('Error', msg, 'error');
        }
    });
};

window.guardarNuevoUsuarioInline = function() {
    const name = $('#quick_user_name').val().trim();
    const email = $('#quick_user_email').val().trim();
    if (!name || !email) {
        toastr.error('Por favor ingrese Nombre y Correo Electrónico.');
        return;
    }

    const $btn = $('#btnGuardarQuickUser');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
        url: '{{ route("admin.juntas.crearUsuarioRapido") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            name: name,
            email: email
        },
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Guardar');
            if (res.success) {
                toastr.success(res.message);
                const newOption = new Option(res.user.name + ' (' + res.user.email + ')', res.user.id, true, true);
                $('#modal_junta_integrantes').append(newOption).trigger('change');

                $('#quick_user_name').val('');
                $('#quick_user_email').val('');
                $('#collapseNuevoUsuarioJunta').collapse('hide');
            } else {
                toastr.error(res.message || 'Error al crear el usuario.');
            }
        },
        error: function(err) {
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Guardar');
            var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error al crear el usuario.';
            toastr.error(msg);
        }
    });
};
</script>
@endsection
