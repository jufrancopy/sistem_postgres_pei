@extends('layouts.master')
@section('title', 'Ajustes del Módulo RIISS')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
    :root {
        --dim-cartera: #0284c7;
        --dim-infra: #d97706;
        --dim-talento: #7c3aed;
        --dim-medicamentos: #0d9488;
        --dim-gobernanza: #475569;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Nav Tabs Styling */
    .riiss-tabs .nav-link {
        font-weight: 700;
        font-size: 0.92rem;
        color: #475569;
        border-radius: 12px;
        padding: 11px 22px;
        margin-right: 10px;
        transition: all .2s ease;
        background: #f8fbfe;
        border: 1.5px solid #dbeaf4;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .riiss-tabs .nav-link.active {
        background: linear-gradient(135deg, #0284c7, #0369a1) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 8px 20px rgba(2, 132, 199, .22);
    }
    .riiss-tabs .nav-link.active i {
        color: rgba(255,255,255,.9) !important;
    }
    .riiss-tabs .nav-link:hover:not(.active) {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
        transform: translateY(-1px);
    }

    /* KPI Dimension Cards */
    .dim-kpi-card {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        padding: 14px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .dim-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }
    .dim-kpi-card.active {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .dim-kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 4px;
    }
    .kpi-cartera::before { background: var(--dim-cartera); }
    .kpi-infra::before { background: var(--dim-infra); }
    .kpi-talento::before { background: var(--dim-talento); }
    .kpi-medicamentos::before { background: var(--dim-medicamentos); }
    .kpi-gobernanza::before { background: var(--dim-gobernanza); }

    /* Tipología Buttons */
    .tipologia-pill {
        border: 1.5px solid #cbd5e1;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .tipologia-pill:hover {
        border-color: #0284c7;
        color: #0284c7;
        background: #f8fafc;
    }
    .tipologia-pill.active {
        border-color: #0284c7;
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }

    /* Equiparación Matrix Card Styles */
    .equiparacion-container {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 22px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    }
    .equiparacion-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }
    @media (max-width: 1200px) {
        .equiparacion-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .equiparacion-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .equiparacion-grid { grid-template-columns: 1fr; }
    }
    .eq-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .eq-card:hover {
        transform: translateY(-2px);
        border-color: #0284c7;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.12);
    }
    .eq-card.active {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25);
    }
    .eq-badge-mspbs {
        font-size: 0.70rem;
        font-weight: 700;
        border-radius: 6px;
        padding: 4px 6px;
        display: block;
        margin-bottom: 6px;
        text-transform: uppercase;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .eq-badge-ips {
        font-size: 0.76rem;
        font-weight: 700;
        color: #ffffff;
        border-radius: 6px;
        padding: 5px 6px;
        display: block;
        margin-bottom: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .eq-badge-level {
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 4px;
    }
    .eq-badge-details {
        font-size: 0.68rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Circular Action Buttons (btn-circle) */
    .btn-circle {
        width: 28px !important;
        height: 28px !important;
        min-width: 28px !important;
        max-width: 28px !important;
        padding: 0 !important;
        border-radius: 50% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12) !important;
        transition: all 0.15s ease !important;
        border: none !important;
    }
    .btn-circle:hover { transform: scale(1.1); }
    .btn-circle i { font-size: 0.72rem !important; line-height: 1 !important; margin: 0 !important; }
    .btn-circle.btn-xs { width: 24px !important; height: 24px !important; min-width: 24px !important; max-width: 24px !important; }
    .btn-circle.btn-xs i { font-size: 0.65rem !important; }
    .btn-circle.btn-sm { width: 28px !important; height: 28px !important; min-width: 28px !important; max-width: 28px !important; }
    .btn-circle.btn-sm i { font-size: 0.72rem !important; }
    .btn-circle.btn-info { background-color: #0284c7 !important; border-color: #0284c7 !important; color: #ffffff !important; }
    .btn-circle.btn-warning { background-color: #f59e0b !important; border-color: #f59e0b !important; color: #ffffff !important; }
    .btn-circle.btn-danger { background-color: #ef4444 !important; border-color: #ef4444 !important; color: #ffffff !important; }

    /* Split Workspace Layout */
    .builder-layout {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 1100px) {
        .builder-layout { grid-template-columns: 1fr; }
    }

    /* Left Pane: Bank of Questions */
    .bank-pane {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
    }
    .bank-scroll-area {
        overflow-y: auto;
        flex: 1;
        padding-right: 4px;
        margin-top: 12px;
    }

    /* Right Pane: Active Form Builder */
    .form-canvas-pane {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }

    /* Drag & Drop Cards */
    .question-drag-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 8px;
        cursor: grab;
        transition: all 0.15s ease;
        position: relative;
    }
    .question-drag-item:hover {
        border-color: #38bdf8;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.12);
        background: #fafcff;
    }
    .question-drag-item:active { cursor: grabbing; }
    .sortable-ghost {
        opacity: 0.4;
        background: #e0f2fe !important;
        border: 2px dashed #0284c7 !important;
    }
    .sortable-chosen {
        background: #f0f9ff;
        box-shadow: 0 8px 24px rgba(2, 132, 199, 0.15);
    }

    /* Section Accordion Card */
    .seccion-accordion-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .seccion-accordion-card:hover { border-color: #cbd5e1; }
    .seccion-header-bar {
        background: #f8fafc;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        border-bottom: 1px solid #e2e8f0;
    }
    .seccion-header-bar:hover { background: #f1f5f9; }
    .questions-dropzone { min-height: 48px; padding: 12px 16px; background: #ffffff; }
    .questions-dropzone.empty-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        margin: 10px 16px;
        padding: 16px;
        text-align: center;
        color: #94a3b8;
        font-size: 0.82rem;
    }

    /* Badges */
    .badge-dim-cartera { background: #e0f2fe; color: #0369a1; }
    .badge-dim-infra { background: #fef3c7; color: #b45309; }
    .badge-dim-talento { background: #f3e8ff; color: #6b21a8; }
    .badge-dim-medicamentos { background: #ccfbf1; color: #0f766e; }
    .badge-dim-gobernanza { background: #f1f5f9; color: #334155; }

    .grade-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #dbeafe;
    }

    /* Grados de Complejidad Cards (Tab 2) */
    .grado-card {
        border-left: 6px solid;
        border-radius: 12px;
        border-top: 1.5px solid #e2e8f0;
        border-right: 1.5px solid #e2e8f0;
        border-bottom: 1.5px solid #e2e8f0;
        transition: transform .2s ease, box-shadow .2s ease;
        background: #ffffff;
    }
    .grado-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.06);
    }
    .flag-badge {
        font-size: .74rem;
        padding: 3px 9px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .flag-si  { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .flag-no  { background: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; }
</style>
@endpush

@section('content')
<div class="card mb-3">
    {{-- Header Principal --}}
    <div class="card-header card-header-info py-3" style="background: linear-gradient(135deg, #0284c7, #0369a1); border-radius: 12px; box-shadow: 0 10px 24px rgba(2, 132, 199, 0.2);">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <div>
                <h4 class="card-title text-white font-weight-bold mb-0">
                    <i class="fa fa-cogs mr-2"></i> Ajustes del Módulo RIISS
                </h4>
                <p class="card-category text-white-75 mb-0" style="font-size: 0.88rem;">
                    Gestión integral de Formularios Dinámicos por Dimensión, Banco de Preguntas y Grados de Complejidad MSPBS ↔ IPS
                </p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <button type="button" class="btn btn-sm btn-light font-weight-bold shadow-xs text-dark" onclick="abrirModalNuevaSeccion()">
                    <i class="fa fa-folder-plus text-primary mr-1"></i> + Nueva Sección
                </button>
                <button type="button" class="btn btn-sm btn-light font-weight-bold shadow-xs text-dark" onclick="abrirModalNuevaPregunta()">
                    <i class="fa fa-circle-question text-success mr-1"></i> + Nueva Pregunta
                </button>
                <a href="{{ route('riiss.index') }}" class="btn btn-sm btn-outline-light font-weight-bold ml-2">
                    <i class="fa fa-arrow-left mr-1"></i> Centro RIISS
                </a>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ajustes & Configuración</li>
        </ol>
    </nav>

    {{-- Pestañas de Navegación --}}
    <div class="card-body pb-0 pt-3">
        <ul class="nav nav-pills riiss-tabs border-0" id="riissConfigTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-formularios-tab" data-toggle="tab" href="#tab-formularios" role="tab" aria-controls="tab-formularios" aria-selected="true">
                    <i class="fa fa-cubes-stacked"></i> Formularios Dinámicos & Preguntas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-complejidad-tab" data-toggle="tab" href="#tab-complejidad" role="tab" aria-controls="tab-complejidad" aria-selected="false">
                    <i class="fa fa-layer-group"></i> Grados de Complejidad & Criterios
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="riissConfigTabsContent">

    {{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
    {{-- PESTAÑA 1: FORMULARIOS DINÁMICOS & BANCO DE PREGUNTAS (DRAG & DROP) --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade show active" id="tab-formularios" role="tabpanel" aria-labelledby="tab-formularios-tab">

        {{-- 1. Tarjetas de Dimensiones Estratégicas (KPI / Filtros Rápidos) --}}
        <div class="row mb-4">
            {{-- Todas las dimensiones --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card active" onclick="filtrarPorDimension('all', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">Todas</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: #0f2744;">{{ $conteos['total_preguntas'] }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['total_secciones'] }} Secciones</small>
                        </div>
                        <i class="fa fa-layer-group fa-lg text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>

            {{-- 1. Cartera de Servicios --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-cartera" onclick="filtrarPorDimension('cartera_servicios', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">1. Cartera</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-cartera);">{{ $conteos['por_dimension']['cartera_servicios']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['cartera_servicios']['secciones'] ?? 0 }} Especialidades</small>
                        </div>
                        <i class="fa fa-stethoscope fa-lg" style="color: var(--dim-cartera); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 2. Infraestructura --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-infra" onclick="filtrarPorDimension('infraestructura', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">2. Infraestructura</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-infra);">{{ $conteos['por_dimension']['infraestructura']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['infraestructura']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-building fa-lg" style="color: var(--dim-infra); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 3. Talento Humano --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-talento" onclick="filtrarPorDimension('talento_humano', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">3. Talento Humano</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-talento);">{{ $conteos['por_dimension']['talento_humano']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['talento_humano']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-users-gear fa-lg" style="color: var(--dim-talento); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 4. Medicamentos e Insumos --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-medicamentos" onclick="filtrarPorDimension('medicamentos_insumos', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">4. Medicamentos</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-medicamentos);">{{ $conteos['por_dimension']['medicamentos_insumos']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['medicamentos_insumos']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-pills fa-lg" style="color: var(--dim-medicamentos); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 5. Gobernanza --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-gobernanza" onclick="filtrarPorDimension('gobernanza_procesos', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">5. Gobernanza</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-gobernanza);">{{ $conteos['por_dimension']['gobernanza_procesos']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['gobernanza_procesos']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-file-shield fa-lg" style="color: var(--dim-gobernanza); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. TABLERO DE EQUIPARACIÓN OFICIAL: RIISS MSPBS ↔ RIISS IPS --}}
        <div class="equiparacion-container">
            <div class="d-flex align-items-center justify-content-between flex-wrap pb-2 mb-2 border-bottom" style="gap: 10px;">
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <span class="font-weight-bold text-dark" style="font-size: 0.92rem;">
                        <i class="fa fa-scale-balanced text-primary mr-1"></i> Marco de Equiparación Oficial: <strong>RIISS MSPBS</strong> ↔ <strong>RIISS IPS</strong>
                    </span>
                    <span class="badge badge-light border text-muted" style="font-size: 10.5px;">
                        Estudio Consolidado Cartera de Servicios 2026
                    </span>
                </div>

                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button type="button" class="tipologia-pill active btn-eq-all" onclick="filtrarPorTipologia('', this)">
                        <i class="fa fa-layer-group mr-1"></i> Todas las Tipologías
                    </button>
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <input type="text" id="inputBuscarPreguntas" class="form-control" placeholder="🔍 Buscar en formulario...">
                    </div>
                </div>
            </div>

            {{-- Grid de los 6 Grados de Complejidad y Equiparación Directa --}}
            <div class="equiparacion-grid mb-2">
                @foreach($equiparaciones as $grado => $eq)
                    <div class="eq-card" data-tipologia="{{ $eq['ips'] }}" data-grado="{{ $grado }}" onclick="filtrarPorEquiparacion('{{ $eq['ips'] }}', this)">
                        <div>
                            <div class="d-flex justify-content-between align-items-center eq-badge-level">
                                <span>{{ $eq['nivel_atencion'] }}</span>
                                <span class="badge badge-light border" style="font-size: 9.5px;">Grado {{ $grado }}</span>
                            </div>

                            {{-- MSPBS --}}
                            <div class="eq-badge-mspbs" style="background: {{ $eq['color_bg_mspbs'] }}; color: {{ $eq['color_text_mspbs'] }};" title="Equiparación Oficial MSPBS">
                                <i class="fa fa-building-columns mr-1 opacity-75"></i> MSPBS: {{ $eq['mspbs'] }}
                            </div>

                            {{-- IPS --}}
                            <div class="eq-badge-ips" style="background: {{ $eq['color_bg_ips'] }};" title="Nomenclatura Vigente IPS">
                                <i class="fa fa-hospital mr-1"></i> IPS: {{ $eq['ips'] }}
                            </div>
                        </div>

                        <div class="eq-badge-details mt-1 pt-1 border-top">
                            <span class="d-block font-weight-600">{{ $eq['modalidad'] }}</span>
                            <small class="text-muted">{{ $eq['complejidad'] }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Otras Tipologías Complementarias IPS --}}
            @php
                $ipsEstandar = ['Puesto Sanitario', 'Clínica Periférica', 'Unidad Sanitaria', 'Hospital Regional', 'Hospital Interregional', 'Hospital Especializado'];
                $otrasTipologias = collect($tipologias)->filter(fn($t) => !in_array($t, $ipsEstandar))->values();
            @endphp
            @if($otrasTipologias->count() > 0)
                <div class="d-flex align-items-center flex-wrap pt-2 border-top" style="gap: 6px;">
                    <small class="text-muted font-weight-bold mr-1"><i class="fa fa-notes-medical text-secondary mr-1"></i>Otros Centros IPS:</small>
                    @foreach($otrasTipologias as $tip)
                        <button class="tipologia-pill py-1 px-2" style="font-size: 11px;" onclick="filtrarPorTipologia('{{ $tip }}', this)">
                            {{ $tip }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 3. Layout Split: Banco Izquierdo vs Formulario Activo Derecho --}}
        <div class="builder-layout">

            {{-- ── PANEL IZQUIERDO: BANCO DE PREGUNTAS Y SERVICIOS ── --}}
            <div class="bank-pane">
                <div class="d-flex align-items-center justify-content-between pb-2 border-bottom">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">
                            <i class="fa fa-boxes-stacked text-primary mr-1"></i> Banco de Preguntas
                        </h6>
                        <small class="text-muted" id="bancoTotalCount">Cargando catálogo...</small>
                    </div>
                    <button type="button" class="btn btn-circle btn-xs btn-outline-primary shadow-xs" onclick="recargarBancoPreguntas()" title="Recargar catálogo">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>

                {{-- Filtro de Dimensión del Banco (Independiente del formulario activo a la derecha) --}}
                <div class="mt-2">
                    <label class="small font-weight-bold text-muted mb-1 d-flex justify-content-between align-items-center">
                        <span><i class="fa fa-filter mr-1"></i> Catálogo de origen:</span>
                    </label>
                    <select id="selectDimensionBanco" class="form-control form-control-sm font-weight-bold" onchange="cambiarFiltroBancoDimension()">
                        <option value="all">🌐 Todas las Dimensiones (Catálogo General)</option>
                        <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                        <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                        <option value="talento_humano">👥 Talento Humano</option>
                        <option value="medicamentos_insumos">💊 Medicamentos e Insumos</option>
                        <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                    </select>
                </div>

                {{-- Buscador en vivo en el Banco --}}
                <div class="mt-2">
                    <div class="input-group input-group-sm">
                        <input type="text" id="inputBuscarBanco" class="form-control" placeholder="🔍 Buscar por palabra clave...">
                        <div class="input-group-append" id="btnLimpiarBuscarBanco" style="display: none;">
                            <button class="btn btn-outline-secondary" type="button" onclick="limpiarBuscarBanco()">&times;</button>
                        </div>
                    </div>
                </div>

                {{-- Opciones Rápidas: Solo Evaluadas & Modo Arrastre --}}
                <div class="mt-2 p-2 bg-light rounded border" style="font-size: 11px;">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="checkSoloEvaluadas" onchange="cambiarFiltroSoloEvaluadas()">
                        <label class="custom-control-label font-weight-600 text-dark" for="checkSoloEvaluadas" title="Muestra preguntas que ya fueron respondidas en visitas in situ anteriores">
                            ⭐ Respondidas en visitas previas
                        </label>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-1 border-top mt-1">
                        <span class="text-muted font-weight-bold">Al arrastrar:</span>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <div class="custom-control custom-radio custom-control-inline mb-0 mr-0">
                                <input type="radio" id="modoArrastreCopiar" name="modoArrastre" value="copiar" class="custom-control-input" checked>
                                <label class="custom-control-label" for="modoArrastreCopiar" title="Duplica/reutiliza la pregunta en la sección destino sin borrar la original">Reutilizar</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline mb-0 mr-0">
                                <input type="radio" id="modoArrastreMover" name="modoArrastre" value="mover" class="custom-control-input">
                                <label class="custom-control-label" for="modoArrastreMover" title="Mueve la pregunta original a la sección destino">Mover</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bank-scroll-area" id="bancoPreguntasContainer">
                    <div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                </div>

                <div id="bancoCargarMasContainer" class="text-center pt-2" style="display: none;">
                    <button type="button" class="btn btn-xs btn-light border btn-block text-muted font-weight-bold" onclick="cargarMasBancoPreguntas()">
                        <i class="fa fa-chevron-down mr-1"></i> Cargar más preguntas...
                    </button>
                </div>

                <div class="pt-2 border-top text-center">
                    <small class="text-muted" style="font-size: 11px;">
                        <i class="fa fa-hand-pointer mr-1"></i> Arrastre una pregunta o use el botón <strong>+ Vincular</strong>.
                    </small>
                </div>
            </div>

            {{-- ── PANEL DERECHO: CONSTRUCTOR DE FORMULARIO POR DIMENSIÓN Y SECCIÓN ── --}}
            <div class="form-canvas-pane">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom flex-wrap" style="gap: 10px;">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fa fa-wpforms text-info mr-2"></i> Estructura del Formulario de Visita In Situ
                        </h5>
                        <small class="text-muted">
                            Secciones y preguntas activas organizadas por dimensión para auditoría en terreno
                        </small>
                    </div>

                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <span id="saveStatusIndicator" class="badge badge-light border px-2 py-1 small text-muted" style="display: none;">
                            <i class="fa fa-check text-success mr-1"></i> Cambios guardados
                        </span>
                    </div>
                </div>

                {{-- Contenedor de Secciones y Dropzones --}}
                <div id="seccionesCanvasContainer">
                    <div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-3x"></i><br>Cargando estructura...</div>
                </div>
            </div>

        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
    {{-- PESTAÑA 2: GRADOS DE COMPLEJIDAD Y CRITERIOS ESTRUCTURALES --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
    {{-- PESTAÑA 2: GRADOS DE COMPLEJIDAD Y CRITERIOS ESTRUCTURALES (DATATABLES) --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="tab-complejidad" role="tabpanel" aria-labelledby="tab-complejidad-tab">
        <div class="card shadow-sm border-0" style="border-radius: 14px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fa fa-layer-group text-primary mr-2"></i> Matriz de Grados de Complejidad y Criterios Estructurales
                    </h5>
                    <small class="text-muted">Parámetros normativos MSPBS ↔ IPS para la clasificación automática de establecimientos</small>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <span class="badge badge-light border text-muted px-3 py-2 font-weight-bold" style="font-size: 0.85rem;">
                        <i class="fa fa-hospital mr-1 text-primary"></i> 6 Grados Normativos
                    </span>
                </div>
            </div>

            <div class="card-body p-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3">
                        <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="tablaComplejidades" class="table table-hover table-striped align-middle w-100" style="font-size: 0.88rem;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 110px;">Grado</th>
                                <th>Nombre & Nomenclatura</th>
                                <th style="width: 140px;">Nivel Atención</th>
                                <th>Requisitos Estructurales & Servicios</th>
                                <th style="width: 140px;" class="text-center">Establecimientos</th>
                                <th style="width: 90px;" class="text-center">Estado</th>
                                <th style="width: 80px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tipos as $tipo)
                            <tr id="fila-comp-{{ $tipo->id }}">
                                <td>
                                    <span class="badge badge-pill text-white font-weight-bold px-3 py-2" id="badge-grado-{{ $tipo->id }}"
                                          style="background: {{ $tipo->color }}; font-size: .82rem; box-shadow: 0 2px 6px rgba(0,0,0,0.12);">
                                        Grado {{ $tipo->grado }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark d-block" id="row-nombre-{{ $tipo->id }}">{{ $tipo->nombre }}</strong>
                                    <small class="text-muted" id="row-tipo-{{ $tipo->id }}">{{ $tipo->tipo_establecimiento ?: 'Estándar RIISS' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" id="row-nivel-{{ $tipo->id }}">
                                        Nivel {{ $tipo->nivel_atencion }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap" style="gap: 4px;" id="row-criterios-{{ $tipo->id }}">
                                        <span class="flag-badge {{ $tipo->es_hospitalario ? 'flag-si' : 'flag-no' }}">
                                            <i class="fa fa-{{ $tipo->es_hospitalario ? 'check' : 'times' }} mr-1"></i>Hosp
                                        </span>
                                        <span class="flag-badge {{ $tipo->requiere_internacion ? 'flag-si' : 'flag-no' }}">
                                            <i class="fa fa-{{ $tipo->requiere_internacion ? 'check' : 'times' }} mr-1"></i>Internación
                                        </span>
                                        <span class="flag-badge {{ $tipo->requiere_quirofano ? 'flag-si' : 'flag-no' }}">
                                            <i class="fa fa-{{ $tipo->requiere_quirofano ? 'check' : 'times' }} mr-1"></i>Quirófano
                                        </span>
                                        <span class="flag-badge {{ $tipo->requiere_uti ? 'flag-si' : 'flag-no' }}">
                                            <i class="fa fa-{{ $tipo->requiere_uti ? 'check' : 'times' }} mr-1"></i>UTI
                                        </span>
                                        <span class="flag-badge {{ $tipo->requiere_urgencias ? 'flag-si' : 'flag-no' }}">
                                            <i class="fa fa-{{ $tipo->requiere_urgencias ? 'check' : 'times' }} mr-1"></i>Urgencias
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center font-weight-bold text-dark">
                                    <span class="badge badge-light border text-primary px-2 py-1 font-weight-bold">
                                        <i class="fa fa-building mr-1"></i>{{ $tipo->establecimientos()->whereNull('deleted_at')->count() }} asignados
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $tipo->activo ? 'badge-success' : 'badge-secondary' }} px-2 py-1 font-weight-bold" id="row-activo-{{ $tipo->id }}">
                                        {{ $tipo->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-circle btn-sm btn-info shadow-sm"
                                            onclick="abrirModalEditarComplejidad({{ $tipo->id }}, '{{ addslashes($tipo->nombre) }}', {{ $tipo->nivel_atencion }}, '{{ $tipo->color }}', '{{ addslashes($tipo->tipo_establecimiento ?? '') }}', {{ $tipo->es_hospitalario ? 1 : 0 }}, {{ $tipo->requiere_internacion ? 1 : 0 }}, {{ $tipo->requiere_quirofano ? 1 : 0 }}, {{ $tipo->requiere_uti ? 1 : 0 }}, {{ $tipo->requiere_urgencias ? 1 : 0 }}, {{ $tipo->activo ? 1 : 0 }})"
                                            title="Editar Grado {{ $tipo->grado }}">
                                        <i class="fa fa-edit"></i>
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

</div>

{{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
{{-- MODALES: CREAR/EDITAR PREGUNTA, CREAR SECCIÓN, VINCULAR PREGUNTA, EDITAR COMPLEJIDAD --}}
{{-- ═══════════════════════════════════════════════════════════════════════════════ --}}

{{-- Modal Vincular / Reutilizar Pregunta en Sección --}}
<div class="modal fade" id="modalVincularPregunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-link mr-2"></i> Vincular / Reutilizar Pregunta en Sección
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formVincularPregunta" onsubmit="guardarVinculacionPregunta(event)">
                <input type="hidden" id="vincularPregId">
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded border mb-3">
                        <small class="text-muted font-weight-bold text-uppercase d-block mb-1">Pregunta Seleccionada:</small>
                        <div id="vincularPregTexto" class="font-weight-600 text-dark small"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Dimensión de Destino <span class="text-danger">*</span></label>
                        <select id="vincularDimension" class="form-control" onchange="actualizarSeccionesModalVincular()">
                            <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                            <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                            <option value="talento_humano">👥 Talento Humano</option>
                            <option value="medicamentos_insumos">💊 Medicamentos, Insumos y Equipamiento</option>
                            <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Sección de Destino <span class="text-danger">*</span></label>
                        <select id="vincularSeccionId" class="form-control" required>
                            {{-- Poblado dinámicamente --}}
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold small d-block">Acción a Realizar</label>
                        <div class="d-flex align-items-center" style="gap: 15px;">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="vincularModoCopiar" name="vincularModo" value="copiar" class="custom-control-input" checked>
                                <label class="custom-control-label small" for="vincularModoCopiar"><strong>Reutilizar / Duplicar</strong> (conserva la original)</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="vincularModoMover" name="vincularModo" value="mover" class="custom-control-input">
                                <label class="custom-control-label small" for="vincularModoMover"><strong>Mover</strong> (cambia de sección)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background:#0284c7; border:none;">
                        <i class="fa fa-link mr-1"></i> Vincular a Sección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Crear Pregunta --}}
<div class="modal fade" id="modalCrearPregunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-info text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-circle-question mr-2"></i> Agregar Nueva Pregunta al Formulario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formCrearPregunta" onsubmit="guardarNuevaPregunta(event)">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small">Dimensión Estratégica <span class="text-danger">*</span></label>
                            <select id="nuevaPregDimension" class="form-control" required onchange="actualizarSeccionesModal('nueva')">
                                <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                                <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                                <option value="talento_humano">👥 Talento Humano</option>
                                <option value="medicamentos_insumos">💊 Medicamentos, Insumos y Equipamiento</option>
                                <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small">Sección de Destino <span class="text-danger">*</span></label>
                            <select id="nuevaPregSeccionId" class="form-control" required>
                                {{-- Cargado dinámicamente --}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Enunciado / Pregunta del Formulario <span class="text-danger">*</span></label>
                        <textarea id="nuevaPregTexto" class="form-control" rows="2" required placeholder="Ej: ¿Dispone de consultorio exclusivo para atención cardiológica con electrocardiógrafo?"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Tipo de Respuesta</label>
                            <select id="nuevaPregTipo" class="form-control">
                                <option value="si_no_na" selected>Sí / No / No Aplica</option>
                                <option value="si_no">Sí / No</option>
                                <option value="texto">Texto Libre</option>
                                <option value="numero">Valor Numérico</option>
                                <option value="checklist">Lista de Verificación (Checklist)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Complejidad Mínima Requerida</label>
                            <select id="nuevaPregComplejidad" class="form-control">
                                <option value="1">Grado 1 (Puesto Sanitario)</option>
                                <option value="2">Grado 2 (Clínica Periférica)</option>
                                <option value="3">Grado 3 (Unidad Sanitaria)</option>
                                <option value="4">Grado 4 (Hospital Regional)</option>
                                <option value="5">Grado 5 (Hospital Interregional)</option>
                                <option value="6">Grado 6 (Hospital Especializado)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Ponderación (Peso)</label>
                            <input type="number" id="nuevaPregPeso" class="form-control" value="1.00" step="0.1" min="0.1" max="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background:#0284c7; border:none;">
                        <i class="fa fa-save mr-1"></i> Guardar Pregunta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar Pregunta --}}
<div class="modal fade" id="modalEditarPregunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-edit mr-2"></i> Editar Pregunta del Formulario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formEditarPregunta" onsubmit="guardarEdicionPregunta(event)">
                <input type="hidden" id="editPregId">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Enunciado de la Pregunta <span class="text-danger">*</span></label>
                        <textarea id="editPregTexto" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Tipo de Respuesta</label>
                            <select id="editPregTipo" class="form-control">
                                <option value="si_no_na">Sí / No / No Aplica</option>
                                <option value="si_no">Sí / No</option>
                                <option value="texto">Texto Libre</option>
                                <option value="numero">Valor Numérico</option>
                                <option value="checklist">Lista de Verificación</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Complejidad Mínima</label>
                            <select id="editPregComplejidad" class="form-control">
                                <option value="1">Grado 1 (Puesto Sanitario)</option>
                                <option value="2">Grado 2 (Clínica Periférica)</option>
                                <option value="3">Grado 3 (Unidad Sanitaria)</option>
                                <option value="4">Grado 4 (Hospital Regional)</option>
                                <option value="5">Grado 5 (Hospital Interregional)</option>
                                <option value="6">Grado 6 (Hospital Especializado)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Ponderación</label>
                            <input type="number" id="editPregPeso" class="form-control" step="0.1" min="0.1" max="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Crear Sección --}}
<div class="modal fade" id="modalCrearSeccion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-info text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-folder-plus mr-2"></i> Crear Nueva Sección
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formCrearSeccion" onsubmit="guardarNuevaSeccion(event)">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Dimensión <span class="text-danger">*</span></label>
                        <select id="nuevaSecDimension" class="form-control" required>
                            <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                            <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                            <option value="talento_humano">👥 Talento Humano</option>
                            <option value="medicamentos_insumos">💊 Medicamentos, Insumos y Equipamiento</option>
                            <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Nombre de la Sección (Grupo Principal) <span class="text-danger">*</span></label>
                        <input type="text" id="nuevaSecNombre" class="form-control" required placeholder="Ej: Consultas de Especialidades Médicas">
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Sub-Sección / Detalle</label>
                        <input type="text" id="nuevaSecSubNombre" class="form-control" placeholder="Ej: Cardiología y Métodos Auxiliares">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background:#0284c7; border:none;">
                        <i class="fa fa-save mr-1"></i> Crear Sección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar Grado de Complejidad (Tab 2) --}}
<div class="modal fade" id="modalEditarComplejidad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e3a5f, #2563eb);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-layer-group mr-2"></i> Editar Grado de Complejidad
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formEditarComplejidad" onsubmit="guardarEdicionComplejidad(event)">
                <input type="hidden" id="compModalId">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small">Nombre del Grado <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="compModalNombre" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold small">Nivel de Atención <span class="text-danger">*</span></label>
                            <select name="nivel_atencion" id="compModalNivel" class="form-control" required>
                                <option value="1">Nivel 1</option>
                                <option value="2">Nivel 2</option>
                                <option value="3">Nivel 3</option>
                                <option value="4">Nivel 4</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold small">Color Identificador</label>
                            <input type="color" name="color" id="compModalColor" class="form-control" style="height: 38px; padding: 2px 4px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="font-weight-bold small">Tipo de Establecimiento (Nomenclatura MSPBS/IPS)</label>
                            <input type="text" name="tipo_establecimiento" id="compModalTipoEst" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Estado</label>
                            <select name="activo" id="compModalActivo" class="form-control">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded border mt-2">
                        <label class="font-weight-bold small text-muted text-uppercase d-block mb-2">Requisitos de Planta y Servicios:</label>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="es_hospitalario" value="1" id="compModalHosp" class="custom-control-input">
                                    <label class="custom-control-label small font-weight-bold" for="compModalHosp">Hospitalario</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="requiere_internacion" value="1" id="compModalIntern" class="custom-control-input">
                                    <label class="custom-control-label small font-weight-bold" for="compModalIntern">Requiere Internación</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="requiere_quirofano" value="1" id="compModalQuirof" class="custom-control-input">
                                    <label class="custom-control-label small font-weight-bold" for="compModalQuirof">Requiere Quirófano</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="requiere_uti" value="1" id="compModalUti" class="custom-control-input">
                                    <label class="custom-control-label small font-weight-bold" for="compModalUti">Requiere UTI</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="requiere_urgencias" value="1" id="compModalUrg" class="custom-control-input">
                                    <label class="custom-control-label small font-weight-bold" for="compModalUrg">Requiere Urgencias</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background:#2563eb; border:none;">
                        <i class="fa fa-save mr-1"></i> Guardar Grado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Sortable.js para Drag & Drop fluido -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
let _currentDimension = 'all';
let _currentTipologia = '';
let _currentBuscar = '';
let _seccionesData = [];
let _sortableInstances = [];
let _bankSortable = null;

// Variables de estado del Banco Izquierdo
let _bancoDimension = 'all';
let _bancoBuscar = '';
let _bancoSoloEvaluadas = false;
let _bancoOffset = 0;
const _bancoLimit = 40;
let _bancoTotal = 0;
let _todasLasSeccionesCache = [];

$(document).ready(function() {
    // Escuchar hash de URL para activar la pestaña correcta (#tab-complejidad o #tab-formularios)
    const hash = window.location.hash;
    if (hash === '#tab-complejidad') {
        $('#tab-complejidad-tab').tab('show');
    } else {
        $('#tab-formularios-tab').tab('show');
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.getAttribute('href');
    });

    cargarEstructuraFormulario();
    cargarBancoPreguntas(true);
    cargarTodasLasSeccionesParaModales();

    // Búsqueda en vivo del canvas con debounce
    let timerBuscar = null;
    $('#inputBuscarPreguntas').on('input', function() {
        clearTimeout(timerBuscar);
        _currentBuscar = $(this).val().trim();
        timerBuscar = setTimeout(() => {
            cargarEstructuraFormulario();
        }, 300);
    });

    // Búsqueda en vivo del banco con debounce
    let timerBanco = null;
    $('#inputBuscarBanco').on('input', function() {
        clearTimeout(timerBanco);
        _bancoBuscar = $(this).val().trim();
        $('#btnLimpiarBuscarBanco').toggle(_bancoBuscar.length > 0);
        timerBanco = setTimeout(() => {
            cargarBancoPreguntas(true);
        }, 300);
    });
});

// ── Filtros por Dimensión (Canvas Derecho) ──
function filtrarPorDimension(dim, el) {
    $('.dim-kpi-card').removeClass('active');
    $(el).addClass('active');
    _currentDimension = dim;
    cargarEstructuraFormulario();
}

// ── Filtro por Tipología / Equiparación (Canvas Derecho) ──
function filtrarPorEquiparacion(tip, el) {
    if ($(el).hasClass('active')) {
        $('.eq-card').removeClass('active');
        $('.tipologia-pill').removeClass('active');
        $('.btn-eq-all').addClass('active');
        _currentTipologia = '';
    } else {
        $('.eq-card').removeClass('active');
        $('.tipologia-pill').removeClass('active');
        $(el).addClass('active');
        _currentTipologia = tip;
    }
    cargarEstructuraFormulario();
}

function filtrarPorTipologia(tip, el) {
    $('.eq-card').removeClass('active');
    $('.tipologia-pill').removeClass('active');
    $(el).addClass('active');
    _currentTipologia = tip;
    cargarEstructuraFormulario();
}

// ── Cargar Estructura del Formulario (Canvas Derecho) ──
function cargarEstructuraFormulario() {
    const $container = $('#seccionesCanvasContainer');
    $container.html('<div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i><br><small>Cargando secciones...</small></div>');

    // Destruir instancias previas de Sortable
    _sortableInstances.forEach(s => s.destroy());
    _sortableInstances = [];

    $.get('{{ route("riiss.formularios.datos") }}', {
        dimension: _currentDimension,
        tipologia: _currentTipologia,
        buscar: _currentBuscar
    }, function(res) {
        if (!res.ok || !res.data || res.data.length === 0) {
            $container.html(`
                <div class="text-center py-5 text-muted bg-light rounded border">
                    <i class="fa fa-folder-open fa-3x mb-2 text-secondary opacity-50"></i>
                    <p class="font-weight-bold mb-1">No se encontraron secciones para los filtros seleccionados.</p>
                    <small>Intente cambiar de dimensión o quitar los filtros de búsqueda.</small>
                </div>
            `);
            return;
        }

        _seccionesData = res.data;
        let html = '';

        res.data.forEach((sec) => {
            const dimClass = 'badge-dim-' + (sec.dimension || 'cartera_servicios').replace('_', '-');
            const dimInfo = sec.dimension_info || { nombre: 'Cartera', icono: 'fa-stethoscope', color: '#0284c7' };

            html += `
                <div class="seccion-accordion-card" data-seccion-id="${sec.id}" data-dimension="${sec.dimension}">
                    <div class="seccion-header-bar" onclick="toggleSeccionAccordion(${sec.id})">
                        <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                            <span class="badge ${dimClass} font-weight-bold px-2 py-1">
                                <i class="fa ${dimInfo.icono} mr-1"></i>${dimInfo.nombre}
                            </span>
                            <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                ${sec.seccion}
                            </span>
                            ${sec.sub_seccion ? `<small class="text-muted">· ${sec.sub_seccion}</small>` : ''}
                            <span class="badge badge-light border text-muted px-2 py-0" style="font-size: 11px;">
                                ${sec.preguntas ? sec.preguntas.length : 0} preguntas
                            </span>
                        </div>

                        <div class="d-flex align-items-center" style="gap: 6px;" onclick="event.stopPropagation()">
                            <button type="button" class="btn btn-circle btn-xs btn-outline-primary" onclick="abrirModalNuevaPreguntaEnSeccion(${sec.id}, '${sec.dimension}')" title="Añadir pregunta aquí">
                                <i class="fa fa-plus"></i>
                            </button>
                            <i class="fa fa-chevron-down text-muted ml-2 transition-transform" id="chevron-sec-${sec.id}"></i>
                        </div>
                    </div>

                    <div id="collapse-sec-${sec.id}" class="seccion-content-body">
                        <div class="questions-dropzone ${(!sec.preguntas || sec.preguntas.length === 0) ? 'empty-zone' : ''}" data-seccion-id="${sec.id}" data-dimension="${sec.dimension}">
                            ${renderPreguntasHTML(sec.preguntas, sec.dimension)}
                        </div>
                    </div>
                </div>
            `;
        });

        $container.html(html);
        inicializarSortableCanvas();
    });
}

function renderPreguntasHTML(preguntas, dimension) {
    if (!preguntas || preguntas.length === 0) {
        return `
            <div class="text-center py-2 text-muted drag-placeholder">
                <i class="fa fa-arrow-down mr-1"></i> Arrastre preguntas aquí desde el banco izquierdo
            </div>
        `;
    }

    let html = '';
    preguntas.forEach((p, idx) => {
        const compBadge = `<span class="grade-badge" title="Grado Mínimo de Complejidad Requerido"><i class="fa fa-hospital mr-1"></i>G${p.grado_complejidad_min || 1}</span>`;
        const tipoBadge = `<span class="badge badge-light border text-muted" style="font-size: 10px;">${(p.tipo_respuesta || 'si_no_na').toUpperCase()}</span>`;
        const pesoBadge = p.peso_ponderacion ? `<span class="badge badge-light border text-muted" style="font-size: 10px;">Peso: ${p.peso_ponderacion}</span>` : '';

        html += `
            <div class="question-drag-item" data-pregunta-id="${p.id}" data-dimension="${dimension || p.dimension}">
                <div class="d-flex justify-content-between align-items-start" style="gap: 10px;">
                    <div class="d-flex align-items-start" style="gap: 8px; flex: 1;">
                        <i class="fa fa-grip-vertical text-muted mt-1 opacity-50" style="cursor: grab;"></i>
                        <div>
                            <div class="font-weight-600 text-dark" style="font-size: 0.88rem; line-height: 1.35;">
                                ${p.pregunta}
                            </div>
                            <div class="d-flex align-items-center flex-wrap mt-1" style="gap: 6px;">
                                ${compBadge}
                                ${tipoBadge}
                                ${pesoBadge}
                                ${p.total_respuestas > 0 ? `<span class="badge badge-success px-1" style="font-size: 9.5px;">⭐ ${p.total_respuestas} respuestas</span>` : ''}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center flex-shrink-0" style="gap: 4px;" onclick="event.stopPropagation()">
                        <button type="button" class="btn btn-circle btn-xs btn-outline-secondary" onclick="abrirModalEditarPregunta(${p.id}, '${escapeHtml(p.pregunta)}', '${p.tipo_respuesta}', ${p.grado_complejidad_min || 1})" title="Editar pregunta">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button type="button" class="btn btn-circle btn-xs btn-outline-info" onclick="duplicarPreguntaAjax(${p.id})" title="Duplicar">
                            <i class="fa fa-copy"></i>
                        </button>
                        <button type="button" class="btn btn-circle btn-xs btn-outline-danger" onclick="eliminarPreguntaAjax(${p.id})" title="Desactivar">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    return html;
}

function toggleSeccionAccordion(id) {
    $(`#collapse-sec-${id}`).slideToggle(200);
    $(`#chevron-sec-${id}`).toggleClass('fa-chevron-down fa-chevron-up');
}

// ── Cargar Banco de Preguntas (Panel Izquierdo) ──
function cargarBancoPreguntas(reset = true) {
    if (reset) {
        _bancoOffset = 0;
        $('#bancoPreguntasContainer').html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
    }

    $.get('{{ route("riiss.formularios.banco-preguntas") }}', {
        dimension: _bancoDimension,
        buscar: _bancoBuscar,
        solo_evaluadas: _bancoSoloEvaluadas ? 1 : 0,
        offset: _bancoOffset,
        limit: _bancoLimit
    }, function(res) {
        if (!res.ok) return;

        _bancoTotal = res.total;
        $('#bancoTotalCount').text(`${_bancoTotal.toLocaleString()} preguntas disponibles`);

        let html = '';
        if (res.data.length === 0 && reset) {
            html = `<div class="text-center py-4 text-muted small">No se encontraron preguntas en el catálogo.</div>`;
        } else {
            res.data.forEach(p => {
                const dimClass = 'badge-dim-' + (p.dimension || 'cartera_servicios').replace('_', '-');
                const dimInfo = p.dimension_info || { nombre: 'Cartera', icono: 'fa-stethoscope' };
                const secNombre = p.seccion_nombre ? `<small class="text-muted d-block text-truncate" style="max-width: 250px;">📁 ${p.seccion_nombre}</small>` : '';

                html += `
                    <div class="question-drag-item bank-item" data-pregunta-id="${p.id}" data-dimension="${p.dimension}">
                        <div class="d-flex justify-content-between align-items-start" style="gap: 6px;">
                            <div class="d-flex align-items-start" style="gap: 6px; flex: 1;">
                                <i class="fa fa-grip-vertical text-muted mt-1 opacity-50" style="cursor: grab;"></i>
                                <div>
                                    <span class="badge ${dimClass} py-0 px-1 font-weight-bold" style="font-size: 9.5px;">
                                        <i class="fa ${dimInfo.icono} mr-1"></i>${dimInfo.nombre}
                                    </span>
                                    <div class="font-weight-600 text-dark mt-1" style="font-size: 0.82rem; line-height: 1.25;">
                                        ${p.pregunta}
                                    </div>
                                    ${secNombre}
                                    <div class="d-flex align-items-center flex-wrap mt-1" style="gap: 4px;">
                                        <span class="grade-badge" style="font-size: 9.5px;">G${p.grado_complejidad_min || 1}</span>
                                        ${p.total_respuestas > 0 ? `<span class="badge badge-success px-1" style="font-size: 9px;">⭐ ${p.total_respuestas} visitas</span>` : ''}
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-circle btn-xs btn-outline-primary flex-shrink-0" onclick="abrirModalVincularPregunta(${p.id}, '${escapeHtml(p.pregunta)}', '${p.dimension}')" title="Vincular a sección">
                                <i class="fa fa-link"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        if (reset) {
            $('#bancoPreguntasContainer').html(html);
        } else {
            $('#bancoPreguntasContainer').append(html);
        }

        $('#bancoCargarMasContainer').toggle((_bancoOffset + _bancoLimit) < _bancoTotal);
        inicializarSortableBanco();
    });
}

function cambiarFiltroBancoDimension() {
    _bancoDimension = $('#selectDimensionBanco').val();
    cargarBancoPreguntas(true);
}

function cambiarFiltroSoloEvaluadas() {
    _bancoSoloEvaluadas = $('#checkSoloEvaluadas').is(':checked');
    cargarBancoPreguntas(true);
}

function limpiarBuscarBanco() {
    $('#inputBuscarBanco').val('');
    _bancoBuscar = '';
    $('#btnLimpiarBuscarBanco').hide();
    cargarBancoPreguntas(true);
}

function recargarBancoPreguntas() {
    cargarBancoPreguntas(true);
}

function cargarMasBancoPreguntas() {
    _bancoOffset += _bancoLimit;
    cargarBancoPreguntas(false);
}

// ── Inicializar Sortable.js (Arrastre fluido entre Banco y Secciones) ──
function inicializarSortableBanco() {
    const bankEl = document.getElementById('bancoPreguntasContainer');
    if (!bankEl) return;

    if (_bankSortable) {
        _bankSortable.destroy();
    }

    _bankSortable = new Sortable(bankEl, {
        group: {
            name: 'riiss_questions',
            pull: function() {
                const modo = $('input[name="modoArrastre"]:checked').val();
                return modo === 'copiar' ? 'clone' : true;
            },
            put: false
        },
        animation: 180,
        sort: false,
        draggable: '.bank-item',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen'
    });
}

function inicializarSortableCanvas() {
    const dropzones = document.querySelectorAll('.questions-dropzone');
    dropzones.forEach(zone => {
        const sortable = new Sortable(zone, {
            group: 'riiss_questions',
            animation: 180,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            draggable: '.question-drag-item',
            onAdd: function(evt) {
                const seccionId = zone.getAttribute('data-seccion-id');
                const dimension = zone.getAttribute('data-dimension');
                const itemEl = evt.item;
                const preguntaId = itemEl.getAttribute('data-pregunta-id');
                const isCloned = itemEl.classList.contains('bank-item');

                // Eliminar placeholder de zona vacía si existía
                const placeholder = zone.querySelector('.drag-placeholder');
                if (placeholder) placeholder.remove();
                zone.classList.remove('empty-zone');

                // Notificar al backend sobre la nueva asignación
                const payload = {
                    _token: '{{ csrf_token() }}',
                    pregunta_id: preguntaId,
                    formulario_seccion_id: seccionId,
                    dimension: dimension,
                    accion: isCloned ? 'copiar' : 'mover',
                    nuevo_orden: evt.newIndex + 1
                };

                mostrarGuardando(true);
                $.post('{{ route("riiss.formularios.vincular-pregunta") }}', payload, function(res) {
                    mostrarGuardando(false);
                    cargarEstructuraFormulario();
                    cargarBancoPreguntas(false);
                });
            },
            onUpdate: function(evt) {
                // Reordenamiento dentro de la misma sección
                const seccionId = zone.getAttribute('data-seccion-id');
                guardarReordenamientoPreguntas(seccionId, zone);
            }
        });

        _sortableInstances.push(sortable);
    });
}

function guardarReordenamientoPreguntas(seccionId, zone) {
    const items = zone.querySelectorAll('.question-drag-item');
    const orden = [];
    items.forEach((item, idx) => {
        const pid = item.getAttribute('data-pregunta-id');
        if (pid) orden.push({ id: pid, orden: idx + 1 });
    });

    if (orden.length === 0) return;

    mostrarGuardando(true);
    $.post('{{ route("riiss.formularios.reordenar-preguntas") }}', {
        _token: '{{ csrf_token() }}',
        formulario_seccion_id: seccionId,
        orden: orden
    }, function(res) {
        mostrarGuardando(false);
    });
}

function mostrarGuardando(guardando) {
    const $ind = $('#saveStatusIndicator');
    if (guardando) {
        $ind.html('<i class="fa fa-spinner fa-spin text-info mr-1"></i> Guardando...').show();
    } else {
        $ind.html('<i class="fa fa-check text-success mr-1"></i> Cambios guardados').show();
        setTimeout(() => $ind.fadeOut(1000), 2000);
    }
}

// ── Cache de Secciones para los Modales ──
function cargarTodasLasSeccionesParaModales() {
    $.get('{{ route("riiss.formularios.datos") }}', { dimension: 'all' }, function(res) {
        if (res.ok && res.data) {
            _todasLasSeccionesCache = res.data;
        }
    });
}

// ── Modal Vincular / Reutilizar Pregunta ──
function abrirModalVincularPregunta(id, texto, dimension) {
    $('#vincularPregId').val(id);
    $('#vincularPregTexto').text(texto);
    $('#vincularDimension').val(dimension || 'cartera_servicios');
    actualizarSeccionesModalVincular();
    $('#modalVincularPregunta').modal('show');
}

function actualizarSeccionesModalVincular() {
    const dim = $('#vincularDimension').val();
    const $sel = $('#vincularSeccionId').empty();
    const secs = _todasLasSeccionesCache.filter(s => s.dimension === dim);

    if (secs.length === 0) {
        $sel.append('<option value="">(No hay secciones creadas en esta dimensión)</option>');
    } else {
        secs.forEach(s => {
            $sel.append(`<option value="${s.id}">${s.seccion} ${s.sub_seccion ? ' - ' + s.sub_seccion : ''}</option>`);
        });
    }
}

function guardarVinculacionPregunta(e) {
    e.preventDefault();
    const payload = {
        _token: '{{ csrf_token() }}',
        pregunta_id: $('#vincularPregId').val(),
        formulario_seccion_id: $('#vincularSeccionId').val(),
        dimension: $('#vincularDimension').val(),
        accion: $('input[name="vincularModo"]:checked').val()
    };

    $.post('{{ route("riiss.formularios.vincular-pregunta") }}', payload, function(res) {
        $('#modalVincularPregunta').modal('hide');
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Pregunta vinculada exitosamente',
            showConfirmButton: false,
            timer: 2000
        });
        cargarEstructuraFormulario();
        cargarBancoPreguntas(false);
    });
}

// ── Modal Crear Pregunta ──
function abrirModalNuevaPregunta() {
    actualizarSeccionesModal('nueva');
    $('#modalCrearPregunta').modal('show');
}

function abrirModalNuevaPreguntaEnSeccion(seccionId, dimension) {
    $('#nuevaPregDimension').val(dimension);
    actualizarSeccionesModal('nueva', seccionId);
    $('#modalCrearPregunta').modal('show');
}

function actualizarSeccionesModal(tipo, selectId = null) {
    const dim = $(`#${tipo}PregDimension`).val();
    const $sel = $(`#${tipo}PregSeccionId`).empty();
    const secs = _todasLasSeccionesCache.filter(s => s.dimension === dim);

    if (secs.length === 0) {
        $sel.append('<option value="">(No hay secciones en esta dimensión)</option>');
    } else {
        secs.forEach(s => {
            const isSel = (selectId && s.id == selectId) ? 'selected' : '';
            $sel.append(`<option value="${s.id}" ${isSel}>${s.seccion} ${s.sub_seccion ? ' - ' + s.sub_seccion : ''}</option>`);
        });
    }
}

function guardarNuevaPregunta(e) {
    e.preventDefault();
    const payload = {
        _token: '{{ csrf_token() }}',
        formulario_seccion_id: $('#nuevaPregSeccionId').val(),
        dimension: $('#nuevaPregDimension').val(),
        pregunta: $('#nuevaPregTexto').val().trim(),
        tipo_respuesta: $('#nuevaPregTipo').val(),
        grado_complejidad_min: $('#nuevaPregComplejidad').val(),
        peso_ponderacion: $('#nuevaPregPeso').val() || 1.0
    };

    $.post('{{ route("riiss.formularios.preguntas.store") }}', payload, function(res) {
        $('#modalCrearPregunta').modal('hide');
        $('#formCrearPregunta')[0].reset();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Pregunta creada exitosamente',
            showConfirmButton: false,
            timer: 2000
        });
        cargarEstructuraFormulario();
        cargarBancoPreguntas(false);
    });
}

// ── Modal Editar Pregunta ──
function abrirModalEditarPregunta(id, texto, tipo, comp) {
    $('#editPregId').val(id);
    $('#editPregTexto').val(texto);
    $('#editPregTipo').val(tipo);
    $('#editPregComplejidad').val(comp);
    $('#modalEditarPregunta').modal('show');
}

function guardarEdicionPregunta(e) {
    e.preventDefault();
    const id = $('#editPregId').val();
    const payload = {
        _token: '{{ csrf_token() }}',
        _method: 'PATCH',
        pregunta: $('#editPregTexto').val().trim(),
        tipo_respuesta: $('#editPregTipo').val(),
        grado_complejidad_min: $('#editPregComplejidad').val(),
        peso_ponderacion: $('#editPregPeso').val() || 1.0
    };

    $.post(`/riiss/formularios/preguntas/${id}`, payload, function(res) {
        $('#modalEditarPregunta').modal('hide');
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Pregunta actualizada',
            showConfirmButton: false,
            timer: 1800
        });
        cargarEstructuraFormulario();
        cargarBancoPreguntas(false);
    });
}

// ── Duplicar Pregunta ──
function duplicarPreguntaAjax(id) {
    $.post(`/riiss/formularios/preguntas/${id}/duplicar`, { _token: '{{ csrf_token() }}' }, function(res) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Pregunta duplicada',
            showConfirmButton: false,
            timer: 1800
        });
        cargarEstructuraFormulario();
        cargarBancoPreguntas(false);
    });
}

// ── Desactivar Pregunta ──
function eliminarPreguntaAjax(id) {
    Swal.fire({
        title: '¿Desactivar esta pregunta?',
        text: 'La pregunta se ocultará del formulario activo para visitas in situ.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash-alt mr-1"></i> Sí, desactivar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/riiss/formularios/preguntas/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    cargarEstructuraFormulario();
                    cargarBancoPreguntas(false);
                }
            });
        }
    });
}

// ── Modal Crear Sección ──
function abrirModalNuevaSeccion() {
    $('#modalCrearSeccion').modal('show');
}

function guardarNuevaSeccion(e) {
    e.preventDefault();
    const payload = {
        _token: '{{ csrf_token() }}',
        dimension: $('#nuevaSecDimension').val(),
        seccion: $('#nuevaSecNombre').val().trim(),
        sub_seccion: $('#nuevaSecSubNombre').val().trim()
    };

    $.post('{{ route("riiss.formularios.secciones.store") }}', payload, function(res) {
        $('#modalCrearSeccion').modal('hide');
        $('#formCrearSeccion')[0].reset();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Sección creada con éxito',
            showConfirmButton: false,
            timer: 2000
        });
        cargarEstructuraFormulario();
        cargarTodasLasSeccionesParaModales();
    }).fail(function(xhr) {
        Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo crear la sección.', 'error');
    });
}

// ── DataTables & Modal Editar Complejidad (Tab 2) ──
let _tablaComplejidades = null;

$(document).ready(function() {
    if ($('#tablaComplejidades').length && $.fn.DataTable) {
        _tablaComplejidades = $('#tablaComplejidades').DataTable({
            paging: false,
            info: false,
            searching: true,
            order: [[0, 'asc']],
            language: {
                search: "🔍 Buscar en la matriz:",
                emptyTable: "No hay registros disponibles",
                zeroRecords: "No se encontraron coincidencias"
            }
        });
    }
});

function abrirModalEditarComplejidad(id, nombre, nivel, color, tipoEst, hosp, intern, quirof, uti, urg, activo) {
    $('#compModalId').val(id);
    $('#compModalNombre').val(nombre);
    $('#compModalNivel').val(nivel);
    $('#compModalColor').val(color || '#0284c7');
    $('#compModalTipoEst').val(tipoEst);
    $('#compModalHosp').prop('checked', !!hosp);
    $('#compModalIntern').prop('checked', !!intern);
    $('#compModalQuirof').prop('checked', !!quirof);
    $('#compModalUti').prop('checked', !!uti);
    $('#compModalUrg').prop('checked', !!urg);
    $('#compModalActivo').val(activo ? '1' : '0');
    $('#modalEditarComplejidad').modal('show');
}

function guardarEdicionComplejidad(e) {
    e.preventDefault();
    const id = $('#compModalId').val();
    const payload = {
        _token: '{{ csrf_token() }}',
        _method: 'PUT',
        nombre: $('#compModalNombre').val().trim(),
        nivel_atencion: $('#compModalNivel').val(),
        color: $('#compModalColor').val(),
        tipo_establecimiento: $('#compModalTipoEst').val().trim(),
        activo: $('#compModalActivo').val(),
        es_hospitalario: $('#compModalHosp').is(':checked') ? 1 : 0,
        requiere_internacion: $('#compModalIntern').is(':checked') ? 1 : 0,
        requiere_quirofano: $('#compModalQuirof').is(':checked') ? 1 : 0,
        requiere_uti: $('#compModalUti').is(':checked') ? 1 : 0,
        requiere_urgencias: $('#compModalUrg').is(':checked') ? 1 : 0
    };

    $.post(`/riiss/complejidad/${id}`, payload, function(res) {
        $('#modalEditarComplejidad').modal('hide');
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: res.message || 'Grado de complejidad actualizado',
            showConfirmButton: false,
            timer: 2000
        });

        // Actualizar fila del DataTable en tiempo real
        $(`#row-nombre-${id}`).text(payload.nombre);
        $(`#row-tipo-${id}`).text(payload.tipo_establecimiento || 'Estándar RIISS');
        $(`#row-nivel-${id}`).text(`Nivel ${payload.nivel_atencion}`);
        $(`#badge-grado-${id}`).css('background-color', payload.color);
        $(`#row-activo-${id}`)
            .removeClass('badge-success badge-secondary')
            .addClass(payload.activo == '1' ? 'badge-success' : 'badge-secondary')
            .text(payload.activo == '1' ? 'Activo' : 'Inactivo');

        const criteriosHtml = `
            <span class="flag-badge ${payload.es_hospitalario ? 'flag-si' : 'flag-no'}"><i class="fa fa-${payload.es_hospitalario ? 'check' : 'times'} mr-1"></i>Hosp</span>
            <span class="flag-badge ${payload.requiere_internacion ? 'flag-si' : 'flag-no'}"><i class="fa fa-${payload.requiere_internacion ? 'check' : 'times'} mr-1"></i>Internación</span>
            <span class="flag-badge ${payload.requiere_quirofano ? 'flag-si' : 'flag-no'}"><i class="fa fa-${payload.requiere_quirofano ? 'check' : 'times'} mr-1"></i>Quirófano</span>
            <span class="flag-badge ${payload.requiere_uti ? 'flag-si' : 'flag-no'}"><i class="fa fa-${payload.requiere_uti ? 'check' : 'times'} mr-1"></i>UTI</span>
            <span class="flag-badge ${payload.requiere_urgencias ? 'flag-si' : 'flag-no'}"><i class="fa fa-${payload.requiere_urgencias ? 'check' : 'times'} mr-1"></i>Urgencias</span>
        `;
        $(`#row-criterios-${id}`).html(criteriosHtml);
    }).fail(function(xhr) {
        Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo guardar la modificación.', 'error');
    });
}

function escapeHtml(str) {
    return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}
</script>
@endpush
