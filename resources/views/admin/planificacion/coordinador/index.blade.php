@extends('layouts.master')
@section('title', 'Coordinación de Planificación — Panel Estratégico')

@section('css')
<style>
    /* ── Tarjetas y Estilo General ── */
    .hero-context-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(30, 60, 114, 0.2);
        padding: 1.5rem;
        margin-bottom: 1.75rem;
    }
    .hero-context-card .badge-context {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        backdrop-filter: blur(4px);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
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
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .nav-pills-custom .nav-link {
        color: #555;
        font-weight: 600;
        border-radius: 30px;
        padding: 0.6rem 1.4rem;
        margin-right: 0.5rem;
        transition: all 0.3s;
        border: 1px solid transparent;
    }
    .nav-pills-custom .nav-link.active {
        background: #007bff;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    .nav-pills-custom .nav-link:hover:not(.active) {
        background: #f1f5f9;
        color: #007bff;
    }
    .avatar-circle-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
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

    /* ── Reset Robusto para Form-Group y Labels (Evitar solapamiento de Material Dashboard) ── */
    .form-group,
    .bmd-form-group,
    .modal .form-group,
    .tab-content .form-group {
        position: relative !important;
        margin-bottom: 1.25rem !important;
        padding-top: 0 !important;
    }

    .form-group label,
    .bmd-form-group label,
    .modal .form-group label,
    .tab-content .form-group label,
    label.control-label,
    label.bmd-label-floating,
    label.bmd-label-static {
        position: static !important;
        display: block !important;
        font-size: 0.84rem !important;
        font-weight: 600 !important;
        color: #334155 !important;
        margin-bottom: 0.35rem !important;
        pointer-events: auto !important;
        transform: none !important;
        top: auto !important;
        left: auto !important;
        line-height: 1.3 !important;
    }

    .form-control,
    .modal .form-control,
    .tab-content .form-control {
        display: block !important;
        width: 100% !important;
        height: 40px !important;
        padding: 0.45rem 0.85rem !important;
        font-size: 0.88rem !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
        color: #0f172a !important;
        background-color: #ffffff !important;
        background-image: none !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        box-shadow: none !important;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
    }

    .form-control:focus,
    .modal .form-control:focus,
    .tab-content .form-control:focus {
        border-color: #2563eb !important;
        background-color: #ffffff !important;
        outline: 0 !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }

    .form-control::placeholder,
    .modal .form-control::placeholder {
        color: #94a3b8 !important;
        font-size: 0.84rem !important;
        opacity: 1 !important;
    }

    /* ── Select2: Estilos Globales (Solo para fuera del Chat Drawer) ── */
    .select2-container:not(.chat-select2-container) .select2-selection--single,
    .select2-container--default:not(.chat-select2-container) .select2-selection--single {
        height: 40px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        display: flex !important;
        align-items: center !important;
    }

    .select2-container:not(.chat-select2-container) .select2-selection--multiple,
    .select2-container--default:not(.chat-select2-container) .select2-selection--multiple {
        min-height: 40px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
    }

    /* ── Select2 en DataTables: Evitar conflictos con el layout ── */
    .dataTables_wrapper .select2-container {
        width: 100% !important;
        max-width: 100% !important;
        display: block !important;
    }
    .dataTables_wrapper .select2-container .select2-selection {
        height: 38px !important;
        border-radius: 6px !important;
    }

    /* ── Estilos específicos para la pestaña de Planes ── */
    #tab-planes .table-custom {
        width: 100% !important;
    }
    #tab-planes .dataTables_wrapper {
        padding: 0.5rem 0;
    }
    #tab-planes .dataTables_length {
        margin-bottom: 1rem;
    }
    #tab-planes .dataTables_filter {
        margin-bottom: 1rem;
    }
    #tab-planes .dataTables_filter input {
        margin-left: 0.5rem;
    }
    #tab-planes .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    #tab-planes .btn-group .btn {
        white-space: nowrap;
    }

    /* ── Botones Circulares Perfectos (Sin Deformación) ── */
    .btn.btn-circle, button.btn-circle, a.btn-circle {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        max-width: 32px !important;
        padding: 0 !important;
        border-radius: 50% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 12px !important;
        line-height: 1 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15) !important;
        margin: 1px !important;
        text-align: center !important;
    }
    .btn.btn-circle i, button.btn-circle i, a.btn-circle i,
    .btn.btn-circle .fa, button.btn-circle .fa, a.btn-circle .fa {
        font-size: 12px !important;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1 !important;
        width: auto !important;
        height: auto !important;
        display: inline-block !important;
    }
    .btn.btn-circle:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
    }

    /* ── Modales Optimizados, Espaciosos y 100% Responsivos ── */
    .modal-dialog-xl-custom {
        max-width: 1050px !important;
        width: 94% !important;
        margin: 1.75rem auto !important;
    }
    .modal-dialog-lg-custom {
        max-width: 800px !important;
        width: 92% !important;
        margin: 1.75rem auto !important;
    }
    .modal-content {
        border-radius: 14px !important;
        border: none !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.25) !important;
        overflow: hidden;
    }
    .modal-header {
        padding: 1.2rem 1.5rem !important;
        border-bottom: 1px solid rgba(0,0,0,0.06) !important;
    }
    .modal-body {
        padding: 1.5rem !important;
    }
    .modal-footer {
        padding: 1rem 1.5rem !important;
        border-top: 1px solid #f1f5f9 !important;
    }

    /* Navegación por pestañas dentro de Modales */
    .modal-nav-pills {
        background: #e2e8f0;
        padding: 4px;
        border-radius: 10px;
    }
    .modal-nav-pills .nav-link {
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        color: #475569;
        padding: 0.6rem 1.2rem;
        text-align: center;
        transition: all 0.2s ease;
        border: none;
    }
    .modal-nav-pills .nav-link.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
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
    .nivel-badge-0 { background: #3b82f6; }
    .nivel-badge-1 { background: #06b6d4; }
    .nivel-badge-2 { background: #10b981; }
    .nivel-badge-3 { background: #f59e0b; }
    .nivel-badge-4 { background: #8b5cf6; }

    .sortable-ghost {
        opacity: 0.45;
        background: #dbeafe !important;
        border: 2px dashed #2563eb !important;
        border-radius: 8px !important;
    }
    .sortable-chosen {
        background: #eff6ff;
    }

    /* ── Animación Moderna para Iluminar el Elemento Movido ── */
    @keyframes nodoDestacadoGlow {
        0% {
            background-color: #dbeafe !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.35), 0 4px 12px rgba(59, 130, 246, 0.2) !important;
            transform: scale(1.015);
        }
        35% {
            background-color: #eff6ff !important;
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.2), 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }
        100% {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            transform: scale(1);
        }
    }

    .nodo-item.nodo-movido-exito > .nodo-row {
        animation: nodoDestacadoGlow 3s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        z-index: 10;
        position: relative;
    }

    /* ── Contenedores receptores durante el Drag & Drop ── */
    body.is-organigrama-dragging .nodo-children {
        display: block !important;
    }
    body.is-organigrama-dragging .nodo-children > ul.sortable-group {
        min-height: 26px !important;
        background: rgba(241, 245, 249, 0.7);
        border: 1px dashed #94a3b8;
        border-radius: 6px;
        margin-top: 4px;
        transition: background 0.2s;
    }
    body.is-organigrama-dragging .nodo-children > ul.sortable-group:hover {
        background: rgba(219, 234, 254, 0.6);
        border-color: #3b82f6;
    }

    #moveIndicator {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        display: none;
    }
    .select2-container { width: 100% !important; }

    /* ── Estilos Adaptativos para Móviles ── */
    @media (max-width: 768px) {
        .modal-dialog-xl-custom,
        .modal-dialog-lg-custom,
        .modal-dialog {
            max-width: 98% !important;
            width: 98% !important;
            margin: 0.5rem auto !important;
        }
        .modal-body {
            padding: 1rem !important;
        }
        .modal-header {
            padding: 1rem !important;
        }
        .modal-nav-pills {
            flex-direction: column;
        }
        .modal-nav-pills .nav-link {
            margin-bottom: 4px;
            padding: 0.5rem;
        }
        .hero-context-card {
            padding: 1.25rem;
        }
        .kpi-card {
            margin-bottom: 0.75rem;
        }
        .nav-pills-custom .nav-link {
            padding: 0.5rem 0.9rem;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }
    }

    /* ── Ajuste específico para DataTables en pestañas ── */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
        padding: 0.5rem 0;
    }
    .dataTables_wrapper .dataTables_filter {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .dataTables_wrapper .dataTables_filter label {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        width: 100%;
        max-width: 250px;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- ── Breadcrumbs ── --}}
    <nav aria-label="breadcrumb" class="bg-white rounded shadow-sm p-3 mb-4">
        <ol class="breadcrumb mb-0 bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}" class="text-primary font-weight-bold"><i class="fa fa-home mr-1"></i>Inicio</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">Coordinación de Planificación</li>
        </ol>
    </nav>

    {{-- ── Banner de Contexto Jerárquico ── --}}
    <div class="hero-context-card">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-1 mr-2" style="font-size: 0.85rem; border-radius: 20px;">
                        <i class="fa fa-shield-alt mr-1"></i> Panel de Coordinación
                    </span>
                    @if($user->hasRole('Administrador'))
                        <span class="badge badge-light text-dark font-weight-bold px-2 py-1" style="font-size: 0.75rem;">Modo Administrador Global</span>
                    @endif
                </div>
                <h2 class="font-weight-bold text-white mb-2" style="letter-spacing: -0.5px;">
                    {{ $grupoPadre ? $grupoPadre->name : 'Coordinación Institucional' }}
                </h2>
                <p class="text-white-50 mb-3" style="font-size: 0.95rem;">
                    Bienvenido, <strong>{{ $user->name }}</strong>. Desde este panel administrás los usuarios de tus subgrupos, organizás las instancias operativas, supervisás la estructura orgánica y monitoreás los planes PEI vinculados a tu coordinación.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @if($grupoPadre)
                        <span class="badge-context mr-2 mb-2">
                            <i class="fa fa-users text-warning mr-1"></i> Grupo Padre: <strong>{{ $grupoPadre->name }}</strong>
                        </span>
                    @endif
                    @if($organigramaRaiz)
                        <span class="badge-context mr-2 mb-2">
                            <i class="fa fa-sitemap text-info mr-1"></i> Estructura: <strong>{{ $organigramaRaiz->dependency }}</strong>
                        </span>
                    @endif
                    @if($pei)
                        <span class="badge-context mr-2 mb-2">
                            <i class="fa fa-chart-pie text-success mr-1"></i> PEI Activo: <strong>{{ $pei->name }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                @if($pei)
                    <a href="{{ url('pei-profiles/' . $pei->id) }}" class="btn btn-light btn-round font-weight-bold shadow-sm px-3 py-2 mr-2" target="_blank">
                        <i class="fa fa-th text-primary mr-1"></i> Matriz PEI
                    </a>
                @endif
                <button type="button" class="btn btn-outline-light btn-round px-3 py-2" onclick="location.reload();">
                    <i class="fa fa-sync-alt mr-1"></i> Refrescar
                </button>
            </div>
        </div>
    </div>

    {{-- ── Fila de KPIs del Ámbito ── --}}
    <div class="row mb-4">
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box bg-soft-primary text-primary mr-3" style="background: #e0e7ff; color: #4338ca;">
                        <i class="fa fa-user-friends"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Usuarios Subgrupos</div>
                        <div class="h3 font-weight-bold text-dark mb-0">{{ $kpis['total_usuarios'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box mr-3" style="background: #e0f2fe; color: #0369a1;">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Grupos Subordinados</div>
                        <div class="h3 font-weight-bold text-dark mb-0">{{ $kpis['total_grupos'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box mr-3" style="background: #fef3c7; color: #b45309;">
                        <i class="fa fa-sitemap"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Dependencias Rama</div>
                        <div class="h3 font-weight-bold text-dark mb-0">{{ $kpis['total_dependencias'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box mr-3" style="background: #dcfce7; color: #15803d;">
                        <i class="fa fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Planes PEI</div>
                        <div class="h3 font-weight-bold text-dark mb-0">{{ $kpis['total_planes'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="kpi-icon-box mr-3" style="background: #ede9fe; color: #6d28d9;">
                        <i class="fa fa-project-diagram"></i>
                    </div>
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Proyectos PEI</div>
                        <div class="h3 font-weight-bold text-dark mb-0">{{ $kpis['total_proyectos'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pestañas de Gestión Integral ── --}}
    <div class="card shadow border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white border-bottom p-3">
            <ul class="nav nav-pills nav-pills-custom" id="coordinatorTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-usuarios-link" data-toggle="pill" href="#tab-usuarios" role="tab" aria-controls="tab-usuarios" aria-selected="true">
                        <i class="fa fa-user-cog mr-2"></i> Gestión de Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-grupos-link" data-toggle="pill" href="#tab-grupos" role="tab" aria-controls="tab-grupos" aria-selected="false">
                        <i class="fa fa-users mr-2"></i> Grupos de Trabajo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-organigrama-link" data-toggle="pill" href="#tab-organigrama" role="tab" aria-controls="tab-organigrama" aria-selected="false">
                        <i class="fa fa-sitemap mr-2"></i> Estructura Orgánica
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-planes-link" data-toggle="pill" href="#tab-planes" role="tab" aria-controls="tab-planes" aria-selected="false">
                        <i class="fa fa-layer-group mr-2"></i> Planes Institucionales (PEI)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-proyectos-link" data-toggle="pill" href="#tab-proyectos" role="tab" aria-controls="tab-proyectos" aria-selected="false">
                        <i class="fa fa-project-diagram mr-2"></i> Proyectos
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="coordinatorTabsContent">

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 1: GESTIÓN DE USUARIOS (SOLO HIJOS)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade show active" id="tab-usuarios" role="tabpanel" aria-labelledby="tab-usuarios-link">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-users text-primary mr-2"></i>
                                Usuarios de: <span class="text-primary">{{ $grupoPadre ? $grupoPadre->name : 'Ámbito de Planificación' }}</span>
                            </h4>
                            <p class="text-muted mb-0 small">
                                Listado exclusivo de integrantes asignados a los grupos de trabajo e instancias operativas subordinadas.
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0 d-flex align-items-center">
                            <button type="button" class="btn btn-outline-info btn-round shadow-sm px-3 mr-2" data-toggle="modal" data-target="#modalGuiaRoles">
                                <i class="fa fa-book-open mr-1"></i> Guía de Roles
                            </button>
                            <button type="button" class="btn btn-primary btn-round shadow-sm px-3" id="btnNuevoUsuario">
                                <i class="fa fa-user-plus mr-1"></i> Nuevo Usuario en Ámbito
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100" id="tablaUsuarios">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 35%;">Usuario / Identificación</th>
                                    <th style="width: 25%;">Grupo Asignado</th>
                                    <th style="width: 20%;">Roles en el Sistema</th>
                                    <th style="width: 15%; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 2: GRUPOS DE TRABAJO (SOLO SUBGRUPOS HIJOS)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-grupos" role="tabpanel" aria-labelledby="tab-grupos-link">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-users-cog text-info mr-2"></i>
                                Grupos de Trabajo de: <span class="text-info">{{ $grupoPadre ? $grupoPadre->name : 'Ámbito Institucional' }}</span>
                            </h4>
                            <p class="text-muted mb-0 small">
                                Instancias subordinadas y equipos operativos. Podés gestionar, incorporar o desvincular integrantes con el botón <i class="fa fa-user-plus text-info"></i>.
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <button type="button" class="btn btn-info btn-round shadow-sm px-3 text-white" id="btnNuevoGrupo">
                                <i class="fa fa-plus-circle mr-1"></i> Nuevo Grupo de Trabajo
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100" id="tablaGrupos">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 30%;">Nombre del Grupo</th>
                                    <th style="width: 15%;">Tipo</th>
                                    <th style="width: 15%;">Integrantes</th>
                                    <th style="width: 20%;">Miembros Asignados</th>
                                    <th style="width: 15%; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 3: ESTRUCTURA ORGÁNICA (ÁRBOL TIPO GESTIONAR + DRAG & DROP)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-organigrama" role="tabpanel" aria-labelledby="tab-organigrama-link">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-sitemap text-warning mr-2"></i>
                                Estructura Orgánica de: <span class="text-warning">{{ $organigramaRaiz ? $organigramaRaiz->dependency : 'Estructura Institucional' }}</span>
                            </h4>
                            <p class="text-muted mb-0 small">
                                Árbol jerárquico interactivo. Arrastrá por el ícono <i class="fa fa-grip-vertical text-muted"></i> para reorganizar las dependencias en tiempo real.
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0">
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
                                <div class="alert alert-warning">No se encontró una dependencia raíz configurada para tu coordinación.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 4: PLANES INSTITUCIONALES (PEI)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-planes" role="tabpanel" aria-labelledby="tab-planes-link">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-layer-group text-success mr-2"></i>
                                Planes Estratégicos Institucionales (PEI)
                            </h4>
                            <p class="text-muted mb-0 small">
                                Planes PEI asociados a tu grupo de coordinación y dependencias para consulta, certificación MEF y análisis FODA.
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100" id="tablaPlanes">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 35%;">Nombre del Plan Estratégico</th>
                                    <th style="width: 25%;">Dependencia / Grupo</th>
                                    <th style="width: 15%;">Progreso</th>
                                    <th style="width: 20%; text-align: center;">Acciones Directas</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════════════════════
                     PESTAÑA 5: PROYECTOS INSTITUCIONALES (CONTEXTO PEI)
                     ════════════════════════════════════════════════════════════════════════════ --}}
                <div class="tab-pane fade" id="tab-proyectos" role="tabpanel" aria-labelledby="tab-proyectos-link">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">
                                <i class="fa fa-project-diagram text-info mr-2"></i>
                                Proyectos Institucionales vinculados al Contexto PEI
                            </h4>
                            <p class="text-muted mb-0 small">
                                Listado y seguimiento de proyectos estratégicos asociados a los planes y dependencias de tu ámbito institucional.
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0 d-flex align-items-center">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="proyectosEstadoFiltros">
                                <label class="btn btn-sm btn-outline-secondary active font-weight-bold">
                                    <input type="radio" name="proyecto_estado_filter" value="" checked> Todos
                                </label>
                                <label class="btn btn-sm btn-outline-secondary font-weight-bold">
                                    <input type="radio" name="proyecto_estado_filter" value="FORMULACION"> Formulación
                                </label>
                                <label class="btn btn-sm btn-outline-secondary font-weight-bold">
                                    <input type="radio" name="proyecto_estado_filter" value="EN_EJECUCION"> En Ejecución
                                </label>
                                <label class="btn btn-sm btn-outline-secondary font-weight-bold">
                                    <input type="radio" name="proyecto_estado_filter" value="COMPLETADO"> Completados
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom w-100" id="tablaProyectos">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 30%;">Proyecto</th>
                                    <th style="width: 25%;">Dependencia</th>
                                    <th style="width: 20%;">Vinculación PEI</th>
                                    <th style="width: 10%; text-align: center;">Estado</th>
                                    <th style="width: 10%; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: GESTIÓN DE USUARIO (CREAR / EDITAR)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalUsuario" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-lg-custom" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold mb-0" id="modalUsuarioTitulo">
                    <i class="fa fa-user-plus mr-2"></i> Nuevo Usuario en Ámbito
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; font-size: 1.5rem; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUsuario">
                @csrf
                <input type="hidden" name="user_id" id="usuario_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="user_name" class="form-control" placeholder="Ej: Lic. María González" required>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="user_email" class="form-control" placeholder="ejemplo@ips.gov.py" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="font-weight-bold small">Grupo de Trabajo Asignado <span class="text-danger">*</span></label>
                            <select name="group_id" id="user_group_id" class="form-control select2" required style="width: 100%;">
                                <option value="">-- Seleccionar Grupo --</option>
                                @foreach($gruposPermitidos as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }} {{ $grupoPadre && $g->id === $grupoPadre->id ? '(Grupo Raíz)' : '' }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">El usuario pertenecerá a este grupo en tu árbol de coordinación.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="font-weight-bold small">Roles Asignados <span class="text-danger">*</span></label>
                            <select name="roles[]" id="user_roles" class="form-control select2" multiple required style="width: 100%;">
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row" id="seccionPasswords">
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small" id="labelPassword">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="user_password" class="form-control" placeholder="Mínimo 6 caracteres">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label class="font-weight-bold small">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="user_password_confirmation" class="form-control" placeholder="Repita la contraseña">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-round shadow-sm" id="btnGuardarUsuario">
                        <i class="fa fa-save mr-1"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: GESTIÓN DE GRUPO DE TRABAJO (CREAR / EDITAR)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalGrupo" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold mb-0" id="modalGrupoTitulo">
                    <i class="fa fa-users mr-2"></i> Nuevo Grupo de Trabajo
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; font-size: 1.5rem; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formGrupo">
                @csrf
                <input type="hidden" name="group_id" id="grupo_id">
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold small">Nombre del Grupo <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="grupo_name" class="form-control" placeholder="Ej: Equipo Técnico de Salud" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small">Depende de (Grupo Padre)</label>
                        <select name="parent_id" id="grupo_parent_id" class="form-control select2" style="width: 100%;">
                            @if($grupoPadre)
                                <option value="{{ $grupoPadre->id }}">{{ $grupoPadre->name }} (Grupo Raíz)</option>
                            @endif
                            @foreach($subgruposPermitidos as $sg)
                                <option value="{{ $sg->id }}">{{ $sg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info btn-round text-white shadow-sm" id="btnGuardarGrupo">
                        <i class="fa fa-save mr-1"></i> Guardar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: GESTIÓN DE INTEGRANTES DE UN GRUPO (ENSANCHADO & RESPONSIVE)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalMiembrosGrupo" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="mr-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 1rem;">
                        <i class="fa fa-users-cog"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" style="font-size: 1.15rem;">
                            Integrantes del Grupo
                        </h5>
                        <small class="text-white-50">
                            Grupo: <span id="nombreGrupoMiembros" class="text-warning font-weight-bold"></span>
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; font-size: 1.5rem; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <input type="hidden" id="miembros_grupo_id">

                {{-- Tarjeta de Incorporación con Tabs Modernas --}}
                <div class="card border mb-4 shadow-sm" style="border-radius: 12px; background: #f8fafc;">
                    <div class="card-body p-3">
                        <ul class="nav nav-pills nav-fill modal-nav-pills mb-3" id="pillsMiembrosAccion" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-asignar-existente-link" data-toggle="pill" href="#tab-asignar-existente" role="tab">
                                    <i class="fa fa-search mr-1 text-primary"></i> 1. Asignar Usuario Existente
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-crear-nuevo-link" data-toggle="pill" href="#tab-crear-nuevo" role="tab">
                                    <i class="fa fa-user-plus mr-1 text-success"></i> 2. Registrar y Asignar Nuevo
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">
                            {{-- Tab 1: Asignar Existente --}}
                            <div class="tab-pane fade show active" id="tab-asignar-existente" role="tabpanel">
                                <form id="formAsignarExistente">
                                    <div class="row align-items-end">
                                        <div class="col-12 col-md-8 col-lg-9 mb-3 mb-md-0">
                                            <label class="small font-weight-bold text-dark mb-1">
                                                <i class="fa fa-user-check text-primary mr-1"></i> Buscar Usuario del Sistema:
                                            </label>
                                            <select id="selectUsuarioExistente" class="form-control select2" style="width: 100%;">
                                                <option value="">-- Buscar por nombre o correo --</option>
                                                @foreach($todosLosUsuarios as $tu)
                                                    <option value="{{ $tu->id }}">{{ $tu->name }} ({{ $tu->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <button type="button" class="btn btn-primary btn-block shadow-sm" id="btnEjecutarAsignacion" style="height: 40px; border-radius: 8px;">
                                                <i class="fa fa-plus-circle mr-1"></i> Asignar al Grupo
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- Tab 2: Crear Nuevo --}}
                            <div class="tab-pane fade" id="tab-crear-nuevo" role="tabpanel">
                                <form id="formCrearYAsignar">
                                    <div class="row">
                                        <div class="col-12 col-md-4 form-group mb-2">
                                            <label class="small font-weight-bold">Nombre Completo <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="nuevo_user_name" class="form-control form-control-sm" placeholder="Ej: Lic. Juan Pérez" required>
                                        </div>
                                        <div class="col-12 col-md-4 form-group mb-2">
                                            <label class="small font-weight-bold">Correo Electrónico <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="nuevo_user_email" class="form-control form-control-sm" placeholder="correo@ips.gov.py" required>
                                        </div>
                                        <div class="col-12 col-md-4 form-group mb-2">
                                            <label class="small font-weight-bold">Roles <span class="text-danger">*</span></label>
                                            <select name="roles[]" id="nuevo_user_roles" class="form-control select2" multiple required style="width: 100%;">
                                                @foreach($roles as $r)
                                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12 col-md-4 form-group mb-2">
                                            <label class="small font-weight-bold">Contraseña <span class="text-danger">*</span></label>
                                            <input type="password" name="password" id="nuevo_user_password" class="form-control form-control-sm" placeholder="Mínimo 6 caracteres" required>
                                        </div>
                                        <div class="col-12 col-md-4 form-group mb-2">
                                            <label class="small font-weight-bold">Confirmar Contraseña <span class="text-danger">*</span></label>
                                            <input type="password" name="password_confirmation" id="nuevo_user_password_confirmation" class="form-control form-control-sm" placeholder="Repita la contraseña" required>
                                        </div>
                                        <div class="col-12 col-md-4 form-group mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-success btn-block shadow-sm" id="btnEjecutarCrearYAsignar" style="height: 38px; border-radius: 8px;">
                                                <i class="fa fa-save mr-1"></i> Crear y Asignar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DataTable de Miembros del Grupo --}}
                <div class="card border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-header bg-white border-bottom py-2 px-3">
                        <span class="font-weight-bold text-dark small">
                            <i class="fa fa-users text-info mr-1"></i> Miembros Actuales en este Grupo
                        </span>
                    </div>
                    <div class="card-body p-2 p-md-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom w-100" id="tablaMiembrosGrupo">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 45%;">Integrante</th>
                                        <th style="width: 35%;">Roles</th>
                                        <th style="width: 15%; text-align: center;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <small class="text-muted"><i class="fa fa-info-circle mr-1"></i> Los cambios se aplican inmediatamente.</small>
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: AGREGAR / EDITAR DEPENDENCIA EN EL ORGANIGRAMA
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalDependencia" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-lg-custom" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold mb-0" id="modalDepTitulo">
                    <i class="fa fa-sitemap mr-2"></i> Agregar Dependencia
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; font-size: 1.5rem; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formDependencia">
                @csrf
                <input type="hidden" id="dep_id" name="dependency_id">
                <input type="hidden" id="dep_parent_id" name="parent_id">
                <div class="modal-body p-4">
                    {{-- Checkbox Inicial: ¿Es un Establecimiento de Salud? --}}
                    <div class="p-3 mb-3 rounded border" style="background: #f0fdf4; border-color: #86efac !important;">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="custom-control-input" id="dep_es_establecimiento" name="es_establecimiento" value="1">
                            <label class="custom-control-label font-weight-bold text-dark mb-0 ml-1" for="dep_es_establecimiento" style="font-size: 0.92rem; cursor:pointer;">
                                <i class="fa fa-hospital text-success mr-1"></i> ¿Es un Establecimiento de Salud? <span class="text-muted font-weight-normal">(Conectar con RIISS)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Selector de Establecimiento de Salud (Bioestadística / Geografía) --}}
                    <div class="form-group mb-3" id="grupo_establecimiento_riiss" style="display:none;">
                        <label class="font-weight-bold small text-success">
                            <i class="fa fa-search mr-1"></i> Seleccionar Establecimiento de Salud (Bioestadística / Geografía) <span class="text-danger">*</span>
                        </label>
                        <select name="establecimiento_id" id="dep_establecimiento_id" class="form-control select2" style="width:100%">
                            <option value="">-- Buscar por código o nombre del establecimiento --</option>
                            @foreach($establecimientosRiiss ?? [] as $est)
                                @php
                                    $tipologiaNom = $est->tipoEstablecimiento ? $est->tipoEstablecimiento->nombre : '';
                                    $deptoNom = ($est->distrito && $est->distrito->departamento) ? $est->distrito->departamento->nombre : '';
                                    $regionFull = $deptoNom ? ($deptoNom . ($est->distrito ? ' / ' . $est->distrito->nombre : '')) : '';
                                @endphp
                                <option value="{{ $est->id }}"
                                    data-nombre="{{ $est->nombre }}"
                                    data-tipologia="{{ $tipologiaNom }}"
                                    data-region="{{ $regionFull ?: $deptoNom }}">
                                    {{ $est->codigo ? '[' . $est->codigo . '] ' : '' }}{{ $est->nombre }} @if($tipologiaNom) ({{ $tipologiaNom }}) @endif @if($deptoNom) — {{ $deptoNom }} @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">
                            Al seleccionar el establecimiento, se asocian y vinculan automáticamente su denominación, tipología y región oficial de Bioestadística.
                        </small>
                    </div>

                    <div id="bloque_campos_dependencia">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Nombre de la Dependencia <span class="text-danger">*</span></label>
                            <input type="text" name="dependency" id="dep_dependency" class="form-control font-weight-bold" required placeholder="Ej: Departamento de Estadística y Control">
                        </div>

                        <div class="row">
                            <div class="col-12 form-group">
                                <label class="font-weight-bold small">Responsable / Encargado <span class="text-muted">(Usuarios del Sistema)</span></label>
                                <select name="user_id" id="dep_user_id" class="form-control select2" style="width:100%">
                                    <option value="">-- Sin responsable asignado --</option>
                                    @foreach($todosLosUsuarios as $u)
                                        <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}">{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="manager" id="dep_manager">
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
                            <div class="col-12 col-md-6 form-group">
                                <label class="font-weight-bold small">Tipo de Establecimiento <span class="text-muted">(Tipología RIISS)</span></label>
                                <select name="tipo_establecimiento" id="dep_tipo_establecimiento" class="form-control select2" style="width:100%">
                                    <option value="">-- Ninguno / Administrativo --</option>
                                    @foreach($tipologiasRiiss ?? [] as $tipo)
                                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label class="font-weight-bold small">Dirección / Región</label>
                                <input type="text" name="address" id="dep_address" class="form-control" placeholder="Dirección o Región física">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning btn-round text-dark font-weight-bold shadow-sm" id="btnGuardarDep">
                        <i class="fa fa-save mr-1"></i> Guardar Dependencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: GUÍA INSTITUCIONAL DE ROLES Y PERMISOS
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalGuiaRoles" tabindex="-1" role="dialog" aria-labelledby="modalGuiaRolesTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 90vw;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalGuiaRolesTitulo">
                    <i class="fa fa-book-open text-warning mr-2"></i> Guía Institucional de Roles y Permisos — SIPLAN IPS
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background-color: #f8fafc; max-height: 80vh; overflow-y: auto;">
                @include('admin.roles.partials.guide-content')
            </div>
            <div class="modal-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                <span class="small text-muted font-italic">
                    <i class="fa fa-info-circle text-primary mr-1"></i> Manual y matriz de asignación de roles para el control de acceso en Planificación y Actividades.
                </span>
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar Guía</button>
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
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
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
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
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

{{-- Indicador flotante al mover nodos --}}
<div id="moveIndicator">
    <div class="badge badge-dark p-3 shadow-lg" style="font-size: 0.95rem; border-radius: 30px;">
        <i class="fa fa-arrows-alt text-warning mr-2"></i> <span id="moveText">Moviendo dependencia...</span>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     CHAT DEL PEI FLOTANTE EN TIEMPO REAL (MODERACIÓN EN VIVO)
     ════════════════════════════════════════════════════════════════════════════ --}}
@include('admin.planificacion.peis.peis.partials.chat_drawer', ['profile' => $pei])

@endsection

@section('scripts')
{{-- SortableJS CDN para drag and drop en organigrama --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    if (!window.jQuery && typeof jQuery !== 'undefined') {
        window.$ = jQuery;
    }
    var $ = window.jQuery;

    $(document).ready(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}' }
        });

        // Desactivar enforceFocus de Bootstrap para permitir escribir libremente en el buscador de Select2 dentro de Modales
        if ($.fn.modal && $.fn.modal.Constructor) {
            $.fn.modal.Constructor.prototype._enforceFocus = function() {};
            if ($.fn.modal.Constructor.prototype.enforceFocus) {
                $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            }
        }

        // Inicializar Select2 en cada modal vinculando dropdownParent al modal correspondiente
        $('#modalUsuario').on('shown.bs.modal', function () {
            $('#modalUsuario .select2').select2({
                dropdownParent: $('#modalUsuario'),
                width: '100%'
            });
        });

        $('#modalGrupo').on('shown.bs.modal', function () {
            $('#modalGrupo .select2').select2({
                dropdownParent: $('#modalGrupo'),
                width: '100%'
            });
        });

        $('#modalMiembrosGrupo').on('shown.bs.modal', function () {
            $('#modalMiembrosGrupo .select2').select2({
                dropdownParent: $('#modalMiembrosGrupo'),
                width: '100%'
            });
        });

        $('#modalDependencia').on('shown.bs.modal', function () {
            $('#modalDependencia .select2').select2({
                dropdownParent: $('#modalDependencia'),
                width: '100%'
            });
        });

        // Inicialización general
        $('.select2').select2({
            width: '100%'
        });

        // Idioma español local para DataTables (Sin peticiones externas ni bloqueos CORS)
        var datatablesSpanish = {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando del _START_ al _END_ de _TOTAL_ registros",
            infoEmpty:      "Mostrando del 0 al 0 de 0 registros",
            infoFiltered:   "(filtrado de _MAX_ registros)",
            infoPostFix:    "",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron resultados",
            emptyTable:     "Ningún dato disponible en esta tabla",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Último"
            },
            aria: {
                sortAscending:  ": Ordenar ascendente",
                sortDescending: ": Ordenar descendente"
            }
        };

        // ═════════════════════════════════════════════════════════════════════════
        // 1. DATATABLE: USUARIOS EN ÁMBITO (SOLO HIJOS)
        // ═════════════════════════════════════════════════════════════════════════
        var tablaUsuarios = $('#tablaUsuarios').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('coordinador.usuarios.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'user_info', name: 'name' },
                { data: 'group_name', name: 'group.name' },
                { data: 'roles_list', name: 'roles.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: datatablesSpanish,
            pageLength: 10,
            responsive: true
        });

        // Abrir Modal Crear Usuario
        $('#btnNuevoUsuario').on('click', function () {
            $('#formUsuario')[0].reset();
            $('#usuario_id').val('');
            $('#modalUsuarioTitulo').html('<i class="fa fa-user-plus mr-2"></i> Nuevo Usuario en Ámbito');
            $('#user_roles').val([]).trigger('change');
            $('#user_group_id').val('').trigger('change');
            $('#user_password').prop('required', true);
            $('#user_password_confirmation').prop('required', true);
            $('#labelPassword').html('Contraseña <span class="text-danger">*</span>');
            $('#modalUsuario').modal('show');
        });

        // Guardar Usuario (Crear / Editar)
        $('#formUsuario').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnGuardarUsuario');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

            $.ajax({
                url: "{{ route('coordinador.usuarios.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    $('#modalUsuario').modal('hide');
                    toastr.success(res.message);
                    tablaUsuarios.ajax.reload(null, false);
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON?.message || 'Ocurrió un error al guardar el usuario.';
                    if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    toastr.error(msg);
                },
                complete: function () {
                    btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Usuario');
                }
            });
        });

        // Editar Usuario
        $(document).on('click', '.editUser', function () {
            var id = $(this).data('id');
            var editUrl = "{{ route('coordinador.usuarios.edit', ':id') }}".replace(':id', id);

            $.get(editUrl, function (res) {
                if (res.success) {
                    $('#usuario_id').val(res.user.id);
                    $('#user_name').val(res.user.name);
                    $('#user_email').val(res.user.email);
                    $('#user_group_id').val(res.user.group_id).trigger('change');
                    $('#user_roles').val(res.roles).trigger('change');
                    $('#user_password').val('').prop('required', false);
                    $('#user_password_confirmation').val('').prop('required', false);
                    $('#labelPassword').html('Contraseña <small class="text-muted">(Dejar en blanco para mantener actual)</small>');
                    $('#modalUsuarioTitulo').html('<i class="fa fa-user-edit mr-2"></i> Editar Usuario');
                    $('#modalUsuario').modal('show');
                }
            }).fail(function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al cargar los datos del usuario.');
            });
        });

        // Eliminar Usuario
        $(document).on('click', '.deleteUser', function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var delUrl = "{{ route('coordinador.usuarios.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: '¿Eliminar Usuario?',
                html: '¿Estás seguro de eliminar al usuario <strong>' + name + '</strong> de tu ámbito?',
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
                        success: function (res) {
                            toastr.success(res.message);
                            tablaUsuarios.ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Error al eliminar el usuario.');
                        }
                    });
                }
            });
        });


        // ═════════════════════════════════════════════════════════════════════════
        // 2. DATATABLE: GRUPOS DE TRABAJO (SOLO SUBGRUPOS HIJOS)
        // ═════════════════════════════════════════════════════════════════════════
        var tablaGrupos = $('#tablaGrupos').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('coordinador.grupos.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'name', name: 'name', className: 'font-weight-bold text-dark' },
                { data: 'tipo_badge', name: 'tipo_badge', orderable: false },
                { data: 'members_count', name: 'members_count', orderable: false },
                { data: 'members_names', name: 'members_names', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: datatablesSpanish,
            pageLength: 10,
            responsive: true
        });

        // Abrir Modal Crear Grupo
        $('#btnNuevoGrupo').on('click', function () {
            $('#formGrupo')[0].reset();
            $('#grupo_id').val('');
            $('#modalGrupoTitulo').html('<i class="fa fa-plus-circle mr-2"></i> Nuevo Grupo de Trabajo');
            $('#modalGrupo').modal('show');
        });

        // Guardar Grupo (Crear / Editar)
        $('#formGrupo').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnGuardarGrupo');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

            $.ajax({
                url: "{{ route('coordinador.grupos.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    $('#modalGrupo').modal('hide');
                    toastr.success(res.message);
                    tablaGrupos.ajax.reload(null, false);
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON?.message || 'Error al guardar el grupo.';
                    toastr.error(msg);
                },
                complete: function () {
                    btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Grupo');
                }
            });
        });

        // Editar Grupo
        $(document).on('click', '.editGroup', function () {
            var id = $(this).data('id');
            var editUrl = "{{ route('coordinador.grupos.edit', ':id') }}".replace(':id', id);

            $.get(editUrl, function (res) {
                if (res.success) {
                    $('#grupo_id').val(res.group.id);
                    $('#grupo_name').val(res.group.name);
                    $('#grupo_parent_id').val(res.group.parent_id).trigger('change');
                    $('#modalGrupoTitulo').html('<i class="fa fa-edit mr-2"></i> Editar Grupo de Trabajo');
                    $('#modalGrupo').modal('show');
                }
            }).fail(function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al cargar datos del grupo.');
            });
        });

        // Eliminar Grupo
        $(document).on('click', '.deleteGroup', function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var delUrl = "{{ route('coordinador.grupos.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: '¿Eliminar Grupo de Trabajo?',
                html: '¿Estás seguro de eliminar el grupo <strong>' + name + '</strong>?',
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
                        success: function (res) {
                            toastr.success(res.message);
                            tablaGrupos.ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Error al eliminar el grupo.');
                        }
                    });
                }
            });
        });


        // ═════════════════════════════════════════════════════════════════════════
        // 2.1. MODAL: GESTIÓN DE INTEGRANTES DE UN GRUPO
        // ═════════════════════════════════════════════════════════════════════════
        var tablaMiembrosGrupo = null;

        $(document).on('click', '.btnMiembrosGrupo', function () {
            var groupId = $(this).data('id');
            var groupName = $(this).data('name');

            $('#miembros_grupo_id').val(groupId);
            $('#nombreGrupoMiembros').text(groupName);
            $('#formAsignarExistente')[0].reset();
            $('#formCrearYAsignar')[0].reset();
            $('#selectUsuarioExistente').val('').trigger('change');
            $('#nuevo_user_roles').val([]).trigger('change');

            // Resetear a la pestaña 1
            $('#tab-asignar-existente-link').tab('show');

            var miembrosUrl = "{{ route('coordinador.grupos.miembros', ':id') }}".replace(':id', groupId);

            if ($.fn.DataTable.isDataTable('#tablaMiembrosGrupo')) {
                tablaMiembrosGrupo.ajax.url(miembrosUrl).load();
            } else {
                tablaMiembrosGrupo = $('#tablaMiembrosGrupo').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: miembrosUrl,
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                        { data: 'user_info', name: 'name' },
                        { data: 'roles_list', name: 'roles.name', orderable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    language: datatablesSpanish,
                    pageLength: 5,
                    responsive: true
                });
            }

            $('#modalMiembrosGrupo').modal('show');
            setTimeout(function() {
                if (tablaMiembrosGrupo) {
                    tablaMiembrosGrupo.columns.adjust();
                }
            }, 300);
        });

        // Asignar Usuario Existente al Grupo
        $('#btnEjecutarAsignacion').on('click', function () {
            var groupId = $('#miembros_grupo_id').val();
            var userId = $('#selectUsuarioExistente').val();

            if (!userId) {
                toastr.warning('Por favor seleccioná un usuario de la lista.');
                return;
            }

            var url = "{{ route('coordinador.grupos.miembros.asignar', ':id') }}".replace(':id', groupId);

            $.post(url, { user_id: userId }, function (res) {
                toastr.success(res.message);
                $('#selectUsuarioExistente').val('').trigger('change');
                tablaMiembrosGrupo.ajax.reload(null, false);
                tablaGrupos.ajax.reload(null, false);
                tablaUsuarios.ajax.reload(null, false);
            }).fail(function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al asignar usuario.');
            });
        });

        // Crear Nuevo Usuario y Asignarlo al Grupo
        $('#btnEjecutarCrearYAsignar').on('click', function () {
            var groupId = $('#miembros_grupo_id').val();
            var url = "{{ route('coordinador.grupos.miembros.crear', ':id') }}".replace(':id', groupId);
            var data = $('#formCrearYAsignar').serialize();

            $.post(url, data, function (res) {
                toastr.success(res.message);
                $('#formCrearYAsignar')[0].reset();
                $('#nuevo_user_roles').val([]).trigger('change');
                $('#tab-asignar-existente-link').tab('show');
                tablaMiembrosGrupo.ajax.reload(null, false);
                tablaGrupos.ajax.reload(null, false);
                tablaUsuarios.ajax.reload(null, false);
            }).fail(function (xhr) {
                var msg = xhr.responseJSON?.message || 'Error al crear y asignar usuario.';
                if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                toastr.error(msg);
            });
        });

        // Remover / Desvincular Miembro del Grupo
        $(document).on('click', '.btnRemoverMiembro', function () {
            var groupId = $(this).data('group-id');
            var userId = $(this).data('user-id');
            var name = $(this).data('name');
            var url = "{{ route('coordinador.grupos.miembros.remover', [':id', ':userId']) }}"
                .replace(':id', groupId)
                .replace(':userId', userId);

            Swal.fire({
                title: '¿Desvincular Integrante?',
                html: '¿Estás seguro de desvincular a <strong>' + name + '</strong> de este grupo de trabajo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, desvincular',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (res) {
                            toastr.success(res.message);
                            tablaMiembrosGrupo.ajax.reload(null, false);
                            tablaGrupos.ajax.reload(null, false);
                            tablaUsuarios.ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Error al desvincular integrante.');
                        }
                    });
                }
            });
        });


        // ═════════════════════════════════════════════════════════════════════════
        // ═════════════════════════════════════════════════════════════════════════
        // 3. ESTRUCTURA ORGÁNICA (ÁRBOL TIPO GESTIONAR + DRAG & DROP)
        // ═════════════════════════════════════════════════════════════════════════
        function recalcularNivelesArbol() {
            var colores = ['nivel-badge-0', 'nivel-badge-1', 'nivel-badge-2', 'nivel-badge-3', 'nivel-badge-4'];

            function procesarUl($ul, nivel) {
                $ul.children('li.nodo-item').each(function () {
                    var $li = $(this);
                    var colorBadge = colores[Math.min(nivel, colores.length - 1)];
                    var $badge = $li.find('> .nodo-row .badge').first();

                    colores.forEach(function (c) { $badge.removeClass(c); });
                    $badge.addClass(colorBadge).text('N' + (nivel + 1));

                    var $childrenContainer = $li.find('> .nodo-children');
                    var $childrenUl = $childrenContainer.find('> ul.sortable-group');
                    var $toggleBtn = $li.find('> .nodo-row .btn-toggle');
                    var $emptySpacer = $li.find('> .nodo-row .empty-toggle-spacer');

                    var totalHijos = $childrenUl.children('li.nodo-item').length;
                    if (totalHijos > 0) {
                        $childrenContainer.show();
                        if ($toggleBtn.length === 0 && $emptySpacer.length) {
                            $emptySpacer.replaceWith(
                                '<button class="btn btn-link btn-toggle p-0 mr-2" style="font-size:.75rem;color:#6c757d;min-width:16px" title="Expandir/Colapsar">' +
                                '<i class="fa fa-chevron-down"></i>' +
                                '</button>'
                            );
                        }
                        procesarUl($childrenUl, nivel + 1);
                    } else {
                        if ($toggleBtn.length > 0) {
                            $toggleBtn.replaceWith('<span class="empty-toggle-spacer" style="min-width:24px;display:inline-block"></span>');
                        }
                    }
                });
            }

            var $rootUl = $('#arbolOrganigramaCoordinador > ul.sortable-group');
            if ($rootUl.length) {
                procesarUl($rootUl, 0);
            }
        }

        function initOrganigramaSortable() {
            document.querySelectorAll('#arbolOrganigramaCoordinador .sortable-group').forEach(function (el) {
                if (el._sortable) return;

                el._sortable = Sortable.create(el, {
                    group: 'organigramaCoordinador',
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    emptyInsertThreshold: 5,

                    onStart: function (evt) {
                        $('body').addClass('is-organigrama-dragging');
                        var nombre = $(evt.item).find('.dep-nombre').first().text().trim();
                        $('#moveText').text('Moviendo: ' + nombre);
                        $('#moveIndicator').fadeIn(150);
                    },

                    onEnd: function (evt) {
                        $('body').removeClass('is-organigrama-dragging');
                        $('#moveIndicator').fadeOut(150);

                        var $item = $(evt.item);
                        var nodoId = $item.data('id');
                        var $toUl = $(evt.to);
                        var $parentLi = $toUl.closest('li.nodo-item');
                        var rootId = $('#arbolOrganigramaCoordinador').data('root-id');
                        var rootName = $('#arbolOrganigramaCoordinador').data('root-name') || 'Dependencia Raíz';

                        var nuevoParentId = $parentLi.length ? $parentLi.data('id') : rootId;
                        var nombrePadre = $parentLi.length ? $parentLi.find('> .nodo-row .dep-nombre').text().trim() : rootName;

                        // Si no cambió de posición
                        if (evt.from === evt.to && evt.oldIndex === evt.newIndex) return;

                        // No mover sobre sí mismo
                        if (nuevoParentId == nodoId) {
                            revertirNodo(evt);
                            return;
                        }

                        // Calcular hermanos anterior y siguiente en la nueva posición
                        var $prev = $item.prev('li.nodo-item');
                        var $next = $item.next('li.nodo-item');
                        var beforeId = $next.length ? $next.data('id') : null;
                        var afterId = $prev.length ? $prev.data('id') : null;

                        var moverUrl = "{{ route('coordinador.organigrama.mover', ':id') }}".replace(':id', nodoId);

                        $.ajax({
                            url: moverUrl,
                            type: 'POST',
                            data: {
                                parent_id: nuevoParentId,
                                before_id: beforeId,
                                after_id: afterId
                            },
                            success: function (res) {
                                // 1. Recalcular niveles, badges (N1, N2, N3...) y botones toggle
                                recalcularNivelesArbol();

                                // 2. Iluminar / destacar el nodo movido con animación visual
                                $item.removeClass('nodo-movido-exito');
                                void $item[0].offsetWidth; // trigger reflow
                                $item.addClass('nodo-movido-exito');

                                setTimeout(function () {
                                    $item.removeClass('nodo-movido-exito');
                                }, 3200);

                                // 3. Notificación moderna tipo toast sin recargar la página
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: res.message || 'Dependencia reubicada correctamente.',
                                    showConfirmButton: false,
                                    timer: 3500,
                                    timerProgressBar: true
                                });
                            },
                            error: function (xhr) {
                                revertirNodo(evt);
                                recalcularNivelesArbol();

                                var errMsg = xhr.responseJSON?.error || 'No se pudo guardar la nueva posición.';
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: errMsg,
                                    showConfirmButton: false,
                                    timer: 4500
                                });
                            }
                        });

                        function revertirNodo(e) {
                            var target = e.item;
                            if (e.from !== e.to) {
                                if (e.oldIndex === 0) {
                                    $(e.from).prepend(target);
                                } else {
                                    var prevSib = $(e.from).children('li.nodo-item').eq(e.oldIndex > 0 ? e.oldIndex - 1 : 0);
                                    if (prevSib.length) {
                                        prevSib.after(target);
                                    } else {
                                        $(e.from).append(target);
                                    }
                                }
                            } else {
                                var siblings = $(e.from).children('li.nodo-item').not(target);
                                if (e.oldIndex === 0) {
                                    $(e.from).prepend(target);
                                } else {
                                    siblings.eq(e.oldIndex - 1).after(target);
                                }
                            }
                        }
                    }
                });
            });
        }

        initOrganigramaSortable();

        // Toggle expandir / colapsar nodo
        $('body').on('click', '.btn-toggle', function (e) {
            e.stopPropagation();
            var $children = $(this).closest('.nodo-item').find('> .nodo-children');
            var $icon = $(this).find('i');
            $children.slideToggle(150);
            $icon.toggleClass('fa-chevron-down fa-chevron-right');
        });

        // Abrir Modal Agregar Sub-dependencia Raíz
        $('#btnAgregarSubRaiz').on('click', function () {
            var rootId = $(this).data('id');
            var rootName = $(this).data('nombre');
            $('#formDependencia')[0].reset();
            $('#dep_id').val('');
            $('#dep_parent_id').val(rootId);
            $('#dep_manager').val('');
            $('#dep_es_establecimiento').prop('checked', false);
            $('#dep_establecimiento_id').val('').trigger('change');
            toggleEsEstablecimiento(false);
            $('#dep_user_id').val('').trigger('change');
            $('#dep_tipo_establecimiento').val('').trigger('change');
            $('#modalDepTitulo').html('<i class="fa fa-plus-circle mr-2"></i> Agregar Sub-dependencia a: ' + rootName);
            $('#modalDependencia').modal('show');
        });

        // Inicializar Select2 en Modal Dependencia
        $('#dep_establecimiento_id').select2({
            dropdownParent: $('#modalDependencia'),
            placeholder: "-- Buscar por código o nombre del establecimiento --",
            allowClear: true,
            width: '100%'
        });

        $('#dep_user_id').select2({
            dropdownParent: $('#modalDependencia'),
            placeholder: "-- Buscar y seleccionar responsable --",
            allowClear: true,
            width: '100%'
        });

        $('#dep_tipo_establecimiento').select2({
            dropdownParent: $('#modalDependencia'),
            placeholder: "-- Seleccionar tipología RIISS --",
            allowClear: true,
            width: '100%'
        });

        // Toggle de Establecimiento de Salud (RIISS)
        function toggleEsEstablecimiento(isEst) {
            if (isEst) {
                $('#grupo_establecimiento_riiss').slideDown(150);
                $('#dep_dependency').prop('readonly', true).addClass('bg-light');
                $('#dep_tipo_establecimiento').prop('disabled', true);
                $('#dep_address').prop('readonly', true).addClass('bg-light');
                syncEstablecimientoSeleccionado();
            } else {
                $('#grupo_establecimiento_riiss').slideUp(150);
                $('#dep_establecimiento_id').val('').trigger('change');
                $('#dep_dependency').prop('readonly', false).removeClass('bg-light');
                $('#dep_tipo_establecimiento').prop('disabled', false);
                $('#dep_address').prop('readonly', false).removeClass('bg-light');
            }
        }

        $('#dep_es_establecimiento').on('change', function () {
            toggleEsEstablecimiento($(this).is(':checked'));
        });

        $('#dep_establecimiento_id').on('change', function () {
            syncEstablecimientoSeleccionado();
        });

        function syncEstablecimientoSeleccionado() {
            if (!$('#dep_es_establecimiento').is(':checked')) return;
            var opt = $('#dep_establecimiento_id').find('option:selected');
            if (opt.val()) {
                var nombre = opt.data('nombre') || '';
                var tipologia = opt.data('tipologia') || '';
                var region = opt.data('region') || '';
                if (nombre) $('#dep_dependency').val(nombre);
                if (tipologia) $('#dep_tipo_establecimiento').val(tipologia).trigger('change');
                if (region) $('#dep_address').val(region);
            }
        }

        // Auto-asignar nombre y sugerir email al seleccionar Responsable
        $('#dep_user_id').on('change', function () {
            var opt = $(this).find('option:selected');
            var uname = opt.data('name') || '';
            var uemail = opt.data('email') || '';
            if (uname) {
                $('#dep_manager').val(uname);
            }
            if (uemail && !$('#dep_email').val()) {
                $('#dep_email').val(uemail);
            }
        });

        // Abrir Modal Agregar Sub-dependencia a un Nodo Específico
        $(document).on('click', '.btnAgregarSub', function () {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            $('#formDependencia')[0].reset();
            $('#dep_id').val('');
            $('#dep_parent_id').val(id);
            $('#dep_manager').val('');
            $('#dep_es_establecimiento').prop('checked', false);
            $('#dep_establecimiento_id').val('').trigger('change');
            toggleEsEstablecimiento(false);
            $('#dep_user_id').val('').trigger('change');
            $('#dep_tipo_establecimiento').val('').trigger('change');
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
                    $('#dep_address').val(dep.address);
                    $('#dep_user_id').val(dep.user_id).trigger('change');
                    $('#dep_tipo_establecimiento').val(dep.tipo_establecimiento).trigger('change');

                    if (dep.establecimiento_id || dep.es_establecimiento) {
                        $('#dep_es_establecimiento').prop('checked', true);
                        $('#dep_establecimiento_id').val(dep.establecimiento_id).trigger('change');
                        toggleEsEstablecimiento(true);
                    } else {
                        $('#dep_es_establecimiento').prop('checked', false);
                        $('#dep_establecimiento_id').val('').trigger('change');
                        toggleEsEstablecimiento(false);
                    }

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

            $('#formDependencia').find(':input').prop('disabled', false);

            $.ajax({
                url: "{{ route('coordinador.organigrama.store') }}",
                method: "POST",
                data: $('#formDependencia').serialize(),
                success: function (res) {
                    $('#modalDependencia').modal('hide');
                    toastr.success(res.message || 'Dependencia guardada correctamente.');
                    btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Dependencia');
                    setTimeout(function () { location.reload(); }, 600);
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON?.message || 'Error al guardar la dependencia.';
                    if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    toastr.error(msg);
                    btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Dependencia');
                    if ($('#dep_es_establecimiento').is(':checked')) {
                        $('#dep_tipo_establecimiento').prop('disabled', true);
                    }
                }
            });
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
                        success: function (res) {
                            toastr.success(res.message);
                            setTimeout(function () { location.reload(); }, 600);
                        },
                        error: function (xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Error al eliminar la dependencia.');
                        }
                    });
                }
            });
        });


        // ═════════════════════════════════════════════════════════════════════════
        // 4. DATATABLE: PLANES INSTITUCIONALES (PEI)
        // ═════════════════════════════════════════════════════════════════════════
        var tablaPlanes = $('#tablaPlanes').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('coordinador.planes.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'plan_info', name: 'name' },
                { data: 'dependencia_info', name: 'dependency.dependency' },
                { data: 'progreso', name: 'progress', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: datatablesSpanish,
            pageLength: 10,
            responsive: true
        });

        // ═════════════════════════════════════════════════════════════════════════
        // 5. DATATABLE: PROYECTOS INSTITUCIONALES (CONTEXTO PEI)
        // ═════════════════════════════════════════════════════════════════════════
        var tablaProyectos = $('#tablaProyectos').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('coordinador.proyectos.data') }}",
                data: function (d) {
                    d.estado = $('input[name="proyecto_estado_filter"]:checked').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'nombre_info', name: 'nombre' },
                { data: 'dependencia_info', name: 'dependenciaSolicitante.dependency' },
                { data: 'pei_vinculo', name: 'peiProfile.name', orderable: false },
                { data: 'estado_badge', name: 'estado', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: datatablesSpanish,
            pageLength: 10,
            responsive: true
        });

        $('input[name="proyecto_estado_filter"]').on('change', function () {
            tablaProyectos.ajax.reload();
        });

        // ═════════════════════════════════════════════════════════════════════════
        // 6. MODAL DINÁMICO: CERTIFICACIÓN MEF
        // ═════════════════════════════════════════════════════════════════════════
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

        // ═════════════════════════════════════════════════════════════════════════
        // 7. MODAL DINÁMICO: ANÁLISIS FODA Y CRUCE DE AMBIENTES
        // ═════════════════════════════════════════════════════════════════════════
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

        // Ajustar columnas de DataTables al cambiar de pestaña y recordar pestaña activa
        var storedTab = sessionStorage.getItem('coordinador_active_tab') || window.location.hash;
        if (storedTab && $('.nav-pills-custom a[href="' + storedTab + '"]').length) {
            $('.nav-pills-custom a[href="' + storedTab + '"]').tab('show');
        }

        $('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var tabHref = $(e.target).attr('href');
            if (tabHref && tabHref.startsWith('#')) {
                sessionStorage.setItem('coordinador_active_tab', tabHref);
                if (history.replaceState) {
                    history.replaceState(null, null, tabHref);
                }
                // Forzar ajuste de columnas para todos los DataTables visibles
                setTimeout(function() {
                    if ($.fn.dataTable) {
                        var tables = $.fn.dataTable.tables({ visible: true, api: true });
                        if (tables) {
                            tables.columns.adjust();
                        }
                    }
                }, 100);
            }
        });

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
    });
</script>

{{-- Modal Lectura Cómoda de Aportes de Asesoría --}}
<div class="modal fade" id="modalLecturaAportes" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 1100px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div id="modalLecturaAportesBody" class="p-0"></div>
        </div>
    </div>
</div>
@endsection
