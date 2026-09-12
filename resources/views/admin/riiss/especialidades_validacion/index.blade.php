@extends('layouts.master')

@section('title', 'Validación de Especialidades Médicas')
@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
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
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Módulo de Validación de Especialidades Médicas</h4>
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
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #00bcd4 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Área Interior</div>
                            <div class="h3 font-weight-bold text-dark mb-0 mt-1">{{ $totalInterior }}</div>
                            <small class="text-muted">{{ count($deptosInterior) }} Departamentos</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-info">
                            <i class="fa fa-hospital fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #2196f3 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Área Central / Capital</div>
                            <div class="h3 font-weight-bold text-primary mb-0 mt-1">{{ $totalCentral }}</div>
                            <small class="text-muted">Central y Asunción</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-primary">
                            <i class="fa fa-city fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Especialidades Médicas</div>
                            <div class="h3 font-weight-bold text-success mb-0 mt-1">{{ $totalEspecialidades }}</div>
                            <small class="text-success font-weight-bold">{{ number_format($totalVinculosVademecum, 0, ',', '.') }} Vínculos Vademécum</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-success">
                            <i class="fa fa-stethoscope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Vademécum Oficial IPS</div>
                            <div class="h3 font-weight-bold text-purple mb-0 mt-1" style="color: #8b5cf6;">{{ $totalMedicamentosVademecum }}</div>
                            <small class="text-muted">Medicamentos 2026</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle" style="color: #8b5cf6;">
                            <i class="fa fa-pills fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barra de Navegación por Pestañas (Pills) --}}
        <ul class="nav nav-pills mb-4 p-2 bg-white shadow-sm rounded-lg" id="riissMainTabs" role="tablist" style="border: 1px solid #e2e8f0; gap: 8px;">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="tab-enlaces-tab" data-toggle="pill" href="#tab-enlaces" role="tab" style="border-radius: 8px; padding: 10px 18px;">
                    <i class="fa fa-map-marked-alt mr-2 text-info"></i> Enlaces de Validación Territorial
                    <span class="badge badge-info ml-2 px-2 py-1">{{ count($sesiones) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-especialidades-tab" data-toggle="pill" href="#tab-especialidades" role="tab" style="border-radius: 8px; padding: 10px 18px;">
                    <i class="fa fa-stethoscope mr-2 text-primary"></i> Especialidades y Medicamentos (Vademécum)
                    <span class="badge badge-primary ml-2 px-2 py-1">{{ $totalEspecialidades }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-vademecum-tab" data-toggle="pill" href="#tab-vademecum" role="tab" style="border-radius: 8px; padding: 10px 18px;">
                    <i class="fa fa-pills mr-2 text-success"></i> Catálogo Oficial Vademécum IPS
                    <span class="badge badge-success ml-2 px-2 py-1">{{ $totalMedicamentosVademecum }}</span>
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
            {{-- PESTAÑA 2: ESPECIALIDADES MÉDICAS Y GESTIÓN DE MEDICAMENTOS (VADEMÉCUM) --}}
            {{-- ═════════════════════════════════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-especialidades" role="tabpanel" aria-labelledby="tab-especialidades-tab">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white py-3">
                        <div>
                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fa fa-stethoscope text-primary mr-1"></i> Catálogo de Especialidades Médicas y Vínculos de Medicamentos
                            </h5>
                            <small class="text-muted">
                                Administre los medicamentos autorizados por Vademécum Institucional IPS y su correspondencia con cada especialidad.
                            </small>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                <i class="fa fa-link mr-1"></i> {{ number_format($totalVinculosVademecum, 0, ',', '.') }} Vínculos Vademécum Activos
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
                                        <tr id="fila-esp-{{ $esp->id }}">
                                            <td class="text-center font-weight-bold text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                                    {{ $esp->nombre }}
                                                </div>
                                                @if($esp->codigo)
                                                    <small class="text-muted font-monospace">Cód: {{ $esp->codigo }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-success px-3 py-1 font-weight-bold badge-vademecum-count-{{ $esp->id }}" style="font-size: 12.5px; letter-spacing: 0.3px;">
                                                    <i class="fa fa-pills mr-1"></i> {{ $esp->total_vademecum }} Autorizados
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light border border-secondary text-secondary px-2 py-1 font-weight-bold" style="font-size: 12px;">
                                                    <i class="fa fa-history mr-1"></i> {{ $esp->total_historicos }} Medicamentos
                                                </span>
                                            </td>
                                            <td class="text-center">
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
                        <div>
                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fa fa-pills text-success mr-1"></i> Catálogo Oficial del Vademécum Institucional IPS (2026)
                            </h5>
                            <small class="text-muted">
                                Nómina de los {{ $totalMedicamentosVademecum }} medicamentos oficiales normados por el Instituto de Previsión Social.
                            </small>
                        </div>
                        <div>
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
                                        <tr>
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
                                            <td class="text-center">
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
                                        <option value="AREA INTERIOR" selected>🏥 DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR ({{ $totalInterior }} Hospitales)</option>
                                        <option value="AREA CENTRAL">🏙️ DIRECCIÓN DE HOSPITALES DEL ÁREA CENTRAL ({{ $totalCentral }} Centros)</option>
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
                        <span class="small font-weight-bold text-dark d-block" id="shareAnalistaNombre">Analista Responsable</span>
                        <small class="text-muted" id="shareAnalistaCargo">Cargo / Jurisdicción</small>
                    </div>
                </div>

                <div class="p-3 mb-3 bg-white rounded border">
                    {{-- Enlace de Acceso --}}
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">
                        <i class="fa fa-link text-primary mr-1"></i> Enlace de Acceso Directo
                    </label>
                    <div class="input-group mb-3">
                        <input type="text" id="shareUrlPortal" class="form-control form-control-sm bg-light font-weight-600" readonly style="font-size: 0.82rem;">
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-outline-primary font-weight-bold" type="button" onclick="copiarTextoInput('shareUrlPortal', '¡Enlace copiado!')">
                                <i class="fa fa-copy mr-1"></i> Copiar
                            </button>
                        </div>
                    </div>

                    {{-- Código de Acceso PIN --}}
                    <div class="d-flex align-items-center justify-content-between p-2 rounded mb-2" style="background: #f0fdf4; border: 1.5px dashed #86efac;">
                        <div>
                            <small class="text-muted font-weight-bold d-block" style="font-size: 10.5px;">CÓDIGO DE ACCESO OFICIAL</small>
                            <span id="shareCodigoAcceso" class="font-weight-bold text-success" style="font-size: 1.35rem; letter-spacing: 0.12em; font-family: monospace;">VAL-XXXXXX</span>
                        </div>
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

{{-- Modal Clasificación Territorial (Área Central vs Área Interior) --}}
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
                            Matriz oficial de asignación (Área Central vs Área Interior)
                        </p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none; outline: none;">
                        <span aria-hidden="true" style="font-size: 1.6rem; color: #ffffff;">&times;</span>
                    </button>
                </div>
                <div class="card-body p-4 pt-3">
                    {{-- Mini KPI Badges --}}
                    <div class="row mb-3">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="d-flex align-items-center p-2 rounded bg-light border">
                                <div class="p-2 rounded-circle bg-info text-white mr-2" style="background-color: #00bcd4 !important;">
                                    <i class="fa fa-hospital"></i>
                                </div>
                                <div>
                                    <div class="small text-muted font-weight-bold">ÁREA INTERIOR</div>
                                    <div class="font-weight-bold text-dark">{{ $totalInterior }} Hospitales ({{ count($deptosInterior) }} Dptos)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="d-flex align-items-center p-2 rounded bg-light border">
                                <div class="p-2 rounded-circle bg-primary text-white mr-2">
                                    <i class="fa fa-city"></i>
                                </div>
                                <div>
                                    <div class="small text-muted font-weight-bold">ÁREA CENTRAL</div>
                                    <div class="font-weight-bold text-dark">{{ $totalCentral }} Centros (Central y Asunción)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-2 rounded bg-light border">
                                <div class="p-2 rounded-circle bg-success text-white mr-2">
                                    <i class="fa fa-globe-americas"></i>
                                </div>
                                <div>
                                    <div class="small text-muted font-weight-bold">TOTAL RED</div>
                                    <div class="font-weight-bold text-dark">{{ $totalEstablecimientos }} Establecimientos</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6 col-lg-5">
                            <label class="small text-muted font-weight-bold mb-1"><i class="fa fa-filter mr-1"></i> Filtrar por Jurisdicción Territorial:</label>
                            <select id="filtroAreaClasif" class="form-control select2-modal">
                                <option value="">📋 Mostrar Todas las Áreas ({{ $totalEstablecimientos }})</option>
                                <option value="Área Interior">🏥 Solo Hospitales Área Interior ({{ $totalInterior }})</option>
                                <option value="Área Central">🏙️ Solo Centros Área Central ({{ $totalCentral }})</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="tablaClasificacionEst" style="width:100%;">
                            <thead style="background: #1e293b; color: #ffffff; font-size:12px;">
                                <tr>
                                    <th style="width: 10%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Código</th>
                                    <th style="width: 38%; background: #1e293b; color: #ffffff; border-color: #334155;">Establecimiento de Salud</th>
                                    <th style="width: 24%; background: #1e293b; color: #ffffff; border-color: #334155;">Departamento / Tipología</th>
                                    <th style="width: 28%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Área de Gestión Asignada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todosEstablecimientos as $e)
                                    <tr class="fila-est-clasif" 
                                        data-nombre="{{ strtolower($e->nombre_oficial . ' ' . $e->id_establecimiento) }}"
                                        data-area="{{ $e->area_gestion }}">
                                        <td class="text-center font-weight-bold text-muted" style="font-size:11.5px;">
                                            {{ $e->id_establecimiento }}
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark" style="font-size:13px;">
                                                {{ $e->nombre_oficial }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-bold">{{ $e->departamento }}</span>
                                            <div class="text-muted small" style="font-size:11px;">{{ $e->tipologia_clasificacion }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm btn-group-toggle shadow-xs" data-toggle="buttons">
                                                <label class="btn {{ $e->area_gestion === 'AREA INTERIOR' ? 'btn-success active' : 'btn-outline-secondary' }} btn-sm font-weight-bold" 
                                                       onclick="cambiarAreaEstablecimiento('{{ $e->id_establecimiento }}', 'AREA INTERIOR')"
                                                       title="Asignar a Dirección Área Interior">
                                                    <input type="radio" name="area_{{ $e->id_establecimiento }}" autocomplete="off" @checked($e->area_gestion === 'AREA INTERIOR')>
                                                    <i class="fa fa-hospital mr-1"></i> Área Interior
                                                </label>
                                                <label class="btn {{ $e->area_gestion === 'AREA CENTRAL' ? 'btn-info active' : 'btn-outline-secondary' }} btn-sm font-weight-bold" 
                                                       onclick="cambiarAreaEstablecimiento('{{ $e->id_establecimiento }}', 'AREA CENTRAL')"
                                                       title="Asignar a Dirección Área Central">
                                                    <input type="radio" name="area_{{ $e->id_establecimiento }}" autocomplete="off" @checked($e->area_gestion === 'AREA CENTRAL')>
                                                    <i class="fa fa-city mr-1"></i> Área Central
                                                </label>
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
@endsection

@push('scripts')
<script>
const DEPTOS_INTERIOR = @json($deptosInterior);
const DEPTOS_CENTRAL = @json($deptosCentral);

function actualizarOpcionesDepartamentos() {
    const area = $('#selectAreaGestion').val();
    const $selectDepto = $('#selectDeptoFiltro');
    const $cargo = $('#inputAnalistaCargo');

    let html = '';
    if (area === 'AREA CENTRAL') {
        $cargo.val('Analista Técnico Área Central');
        html += '<option value="TODOS_CENTRAL">TODOS LOS DEPARTAMENTOS DE ÁREA CENTRAL (Central y Asunción)</option>';
        DEPTOS_CENTRAL.forEach(d => {
            html += `<option value="${d}">${d}</option>`;
        });
    } else {
        $cargo.val('Analista Técnico Área Interior');
        html += '<option value="TODOS_INTERIOR">TODOS LOS DEPARTAMENTOS DEL ÁREA INTERIOR (' + DEPTOS_INTERIOR.length + ' Dptos)</option>';
        DEPTOS_INTERIOR.forEach(d => {
            html += `<option value="${d}">${d}</option>`;
        });
    }

    $selectDepto.html(html).trigger('change');
}

function cambiarAreaEstablecimiento(estId, nuevaArea) {
    $.post('{{ route("riiss.validaciones.establecimiento.area-gestion") }}', {
        _token: '{{ csrf_token() }}',
        establecimiento_id: estId,
        area_gestion: nuevaArea
    }, function(res) {
        if (res.success) {
            var $row = $(`tr[data-nombre*="${estId.toLowerCase()}"]`);
            $row.attr('data-area', nuevaArea);

            if (nuevaArea === 'AREA INTERIOR') {
                $row.find('label:first-child').addClass('btn-success active').removeClass('btn-outline-secondary');
                $row.find('label:last-child').addClass('btn-outline-secondary').removeClass('btn-info active');
            } else {
                $row.find('label:first-child').addClass('btn-outline-secondary').removeClass('btn-success active');
                $row.find('label:last-child').addClass('btn-info active').removeClass('btn-outline-secondary');
            }

            Swal.fire({
                icon: 'success',
                title: 'Área Actualizada',
                text: res.message || 'Se actualizó la jurisdicción del establecimiento.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    }).fail(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al actualizar el área de gestión del establecimiento.',
            confirmButtonColor: '#0284c7'
        });
    });
}

$(document).ready(function() {
    // Eliminar Enlace con SweetAlert2
    $(document).on('click', '.btn-delete-enlace', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var analista = $(this).data('analista') || 'este validador';
        var codigo = $(this).data('codigo') || '';

        Swal.fire({
            title: '¿Eliminar Enlace de Validación?',
            html: `Se revocará el acceso asignado a <strong>${analista}</strong> (${codigo}).<br><small class="text-muted">Esta acción no se puede deshacer.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar enlace',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Reiniciar a 0 un Enlace específico con SweetAlert2
    $(document).on('click', '.btn-reset-enlace', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var analista = $(this).data('analista') || 'este validador';
        var codigo = $(this).data('codigo') || '';
        var count = $(this).data('count') || 0;

        Swal.fire({
            title: '¿Reiniciar a 0 este Enlace?',
            html: `
                <p class="text-muted mb-2" style="font-size: 14px;">
                    Se restablecerán a <strong>0</strong> todas las especialidades validadas/inactivadas y firmas asociadas exclusivamente a <strong>${analista}</strong> (<span class="badge badge-dark">${codigo}</span>).
                </p>
                <div class="alert alert-warning py-2 px-3 small text-left mb-0" style="border-radius: 8px;">
                    <i class="fa fa-info-circle mr-1"></i> El enlace seguirá existiendo y activo, pero todas sus validaciones quedarán en 0 para iniciar de nuevo.
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa fa-sync-alt mr-1"></i> Sí, reiniciar enlace a 0',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    // Inicializar Select2 en los modales
    $('#selectAreaGestion').select2({
        dropdownParent: $('#modalGenerarEnlace'),
        width: '100%'
    });

    $('#selectDeptoFiltro').select2({
        dropdownParent: $('#modalGenerarEnlace'),
        width: '100%'
    });

    $('#filtroAreaClasif').select2({
        dropdownParent: $('#modalClasificacionTerritorial'),
        width: '100%'
    });

    // Re-ajustar al abrir modales
    $('#modalGenerarEnlace').on('shown.bs.modal', function () {
        $('#selectAreaGestion').select2({ dropdownParent: $('#modalGenerarEnlace'), width: '100%' });
        $('#selectDeptoFiltro').select2({ dropdownParent: $('#modalGenerarEnlace'), width: '100%' });
    });

    $('#modalClasificacionTerritorial').on('shown.bs.modal', function () {
        $('#filtroAreaClasif').select2({ dropdownParent: $('#modalClasificacionTerritorial'), width: '100%' });
    });

    $('#selectAreaGestion').on('change', function() {
        actualizarOpcionesDepartamentos();
    });

    actualizarOpcionesDepartamentos();

    // Copiar enlace
    $('.btn-copy').on('click', function() {
        var url = $(this).data('url');
        var $btn = $(this);
        navigator.clipboard.writeText(url).then(function() {
            var origHtml = $btn.html();
            $btn.removeClass('btn-outline-info').addClass('btn-success').html('<i class="fa fa-check"></i>');
            Swal.fire({
                icon: 'success',
                title: 'Enlace Copiado',
                text: 'El enlace de validación se copió al portapapeles 📋',
                timer: 1800,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            setTimeout(function() {
                $btn.removeClass('btn-success').addClass('btn-outline-info').html(origHtml);
            }, 2500);
        }).catch(function(err) {
            Swal.fire({
                icon: 'info',
                title: 'Enlace de Validación',
                text: url,
                confirmButtonColor: '#0284c7'
            });
        });
    });

    // ── DataTable para Clasificación Territorial de Establecimientos ──
    var dtClasif = $('#tablaClasificacionEst').DataTable({
        language: {
            emptyTable:     'No hay establecimientos cargados.',
            info:           'Mostrando _START_ a _END_ de _TOTAL_ establecimientos',
    // Filtro interactivo en Modal Clasificación
    $('#filtroAreaClasif').on('change', function() {
        const val = $(this).val();
        if (val === 'Área Interior') {
            $('#tablaClasificacionEst tbody tr').hide();
            $('tr[data-area="AREA INTERIOR"]').show();
        } else if (val === 'Área Central') {
            $('#tablaClasificacionEst tbody tr').hide();
            $('tr[data-area="AREA CENTRAL"]').show();
        } else {
            $('#tablaClasificacionEst tbody tr').show();
        }
    });

    // ── DataTables Inicialización ──

    // 1. Tabla Sesiones de Validador
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
            { orderable: false, targets: [6] } // Acciones
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

    // 2. Tabla Especialidades y Medicamentos
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
            { orderable: false, targets: [6] } // Acciones
        ],
        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-3"lf>rt<"d-flex flex-wrap align-items-center justify-content-between mt-3"ip>'
    });

    // 3. Tabla Catálogo Vademécum IPS
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

    // Ajustar columnas de DataTables al cambiar de pestaña
    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
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
                        order: [[5, 'desc'], [1, 'asc']], // Vademécum primero
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
});
</script>
@endpush
