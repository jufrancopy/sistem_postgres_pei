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
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #4caf50 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Especialidades Validadas</div>
                            <div class="h3 font-weight-bold text-success mb-0 mt-1">{{ $totalRegistrosValidados }}</div>
                            <small class="text-success font-weight-bold">Confirmadas Activas</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-success">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #f44336 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Especialidades Inactivadas</div>
                            <div class="h3 font-weight-bold text-danger mb-0 mt-1">{{ $totalRegistrosInactivos }}</div>
                            <small class="text-muted">{{ $totalConRevision }} Centros Auditados</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-danger">
                            <i class="fa fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Principal con Tabla de Enlaces --}}
        {{-- Card Principal con Tabla de Enlaces con DataTables --}}
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
                    <button type="button" class="btn btn-outline-danger font-weight-bold" onclick="confirmarReinicioValidaciones()" style="border-radius: 6px;" title="Reiniciar datos de prueba a 0">
                        <i class="fa fa-sync-alt mr-1"></i> Reiniciar a 0
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
                                <th style="width: 130px;" class="text-center">Registros Guardados</th>
                                <th style="width: 100px;" class="text-center">Estado</th>
                                <th style="width: 180px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sesiones as $idx => $s)
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="badge badge-info px-2 py-1 font-weight-bold btn-share-validador border-0 shadow-xs" 
                                                style="font-size:11.5px; letter-spacing:0.5px; cursor:pointer;"
                                                data-url="{{ $s->url_acceso }}"
                                                data-codigo="{{ $s->codigo_acceso }}"
                                                data-analista="{{ $s->analista_nombre }}"
                                                data-cargo="{{ $s->analista_cargo ?: 'Analista Técnico' }}"
                                                data-telefono="{{ $s->analista_telefono ?? '' }}"
                                                data-area="{{ $s->area_gestion }}"
                                                data-depto="{{ $s->departamento_filtro ?? 'Todos los Dptos. del Área' }}"
                                                title="Ver y Compartir Acceso de {{ $s->analista_nombre }}">
                                            <i class="fa fa-key mr-1"></i> {{ $s->codigo_acceso }}
                                        </button>
                                        <div class="text-muted small mt-1" style="font-size:10.5px;">
                                            {{ $s->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $s->analista_nombre }}</div>
                                        <div class="text-muted small">{{ $s->analista_cargo ?: 'Analista Técnico' }}</div>
                                        @if($s->analista_documento || $s->analista_telefono)
                                            <div class="text-muted small" style="font-size:10.5px;">
                                                @if($s->analista_documento) C.I.: {{ $s->analista_documento }} @endif
                                                @if($s->analista_telefono) · Tel: {{ $s->analista_telefono }} @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($s->area_gestion === 'AREA CENTRAL')
                                            <span class="badge badge-primary px-2 py-1 font-weight-bold">
                                                <i class="fa fa-city mr-1"></i> Área Central
                                            </span>
                                        @else
                                            <span class="badge badge-info px-2 py-1 font-weight-bold" style="background-color: #00bcd4;">
                                                <i class="fa fa-hospital mr-1"></i> Área Interior
                                            </span>
                                        @endif

                                        <div class="mt-1" style="font-size:11px;">
                                            @if($s->departamento_filtro)
                                                <span class="text-dark font-weight-bold">
                                                    <i class="fa fa-map-marker-alt text-danger mr-1"></i> {{ $s->departamento_filtro }}
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fa fa-globe-americas mr-1"></i> Todos los Dptos. del Área
                                                </span>
                                            @endif
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
                                        <div class="d-flex justify-content-center" style="gap: 6px;">
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
                        <div class="col-md-7">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                                </div>
                                <input type="text" id="buscarEstablecimientoClasif" class="form-control" placeholder="Buscar establecimiento por nombre o código...">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <select id="filtroAreaClasif" class="form-control select2-modal">
                                <option value="">📋 Filtrar por Área (Todas las Áreas)</option>
                                <option value="AREA INTERIOR">🏥 Solo Hospitales Área Interior ({{ $totalInterior }})</option>
                                <option value="AREA CENTRAL">🏙️ Solo Centros Área Central ({{ $totalCentral }})</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <table class="table table-bordered table-sm table-hover align-middle mb-0" id="tablaClasificacionEst">
                            <thead class="sticky-top" style="background: #1e293b; color: #ffffff; font-size:11.5px;">
                                <tr>
                                    <th style="width: 12%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Código</th>
                                    <th style="width: 38%; background: #1e293b; color: #ffffff; border-color: #334155;">Establecimiento de Salud</th>
                                    <th style="width: 22%; background: #1e293b; color: #ffffff; border-color: #334155;">Departamento / Tipología</th>
                                    <th style="width: 28%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Área de Gestión Asignada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todosEstablecimientos as $e)
                                    <tr class="fila-est-clasif" 
                                        data-nombre="{{ strtolower($e->nombre_oficial . ' ' . $e->id_establecimiento) }}"
                                        data-area="{{ $e->area_gestion }}">
                                        <td class="text-center font-weight-bold text-muted" style="font-size:11px;">
                                            {{ $e->id_establecimiento }}
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark" style="font-size:12.5px;">
                                                {{ $e->nombre_oficial }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border text-dark">{{ $e->departamento }}</span>
                                            <div class="text-muted small" style="font-size:10.5px;">{{ $e->tipologia_clasificacion }}</div>
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
            $(`tr[data-nombre*="${estId.toLowerCase()}"]`).attr('data-area', nuevaArea);
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

    // Filtros modal clasificación
    $('#buscarEstablecimientoClasif').on('input', function() {
        filtrarTablaModal();
    });

    $('#filtroAreaClasif').on('change', function() {
        filtrarTablaModal();
    });

    function filtrarTablaModal() {
        const query = $('#buscarEstablecimientoClasif').val().toLowerCase().trim();
        const areaFiltro = $('#filtroAreaClasif').val();

        $('.fila-est-clasif').each(function() {
            const nombre = $(this).data('nombre');
            const area = $(this).attr('data-area');

            const matchQuery = !query || nombre.includes(query);
            const matchArea = !areaFiltro || area === areaFiltro;

            if (matchQuery && matchArea) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // ── MODAL COMPARTIR VALIDADOR POR WHATSAPP Y CÓDIGO ──
    var _ultimoCodigoValidador = '';
    var _ultimoMensajeWhatsAppValidador = '';

    window.abrirModalCompartirValidador = function(data) {
        _ultimoCodigoValidador = data.codigo || '';
        
        $('#shareAnalistaNombre').text(data.analista || 'Analista Responsable');
        $('#shareAnalistaCargo').text((data.cargo || 'Analista Técnico') + (data.telefono ? ' · Tel: ' + data.telefono : ''));
        $('#shareUrlPortal').val(data.url || '');
        $('#shareCodigoAcceso').text(data.codigo || 'VAL-XXXXXX');
        $('#shareAlcanceTexto').text((data.area || 'Área Interior') + ' — ' + (data.depto || 'Todos los Departamentos'));

        var mensaje = `*SIPLAN GO — MÓDULO DE VALIDACIÓN DE ESPECIALIDADES MÉDICAS*\n🏛️ *Instituto de Previsión Social (IPS)*\n\nEstimado/a *${data.analista || 'Analista'}*, se le ha asignado el acceso oficial para el relevamiento y validación de especialidades médicas:\n\n📍 *Área / Jurisdicción:* ${data.area || 'Área Interior'}\n📋 *Alcance Asignado:* ${data.depto || 'Todos los Departamentos'}\n🔑 *CÓDIGO DE ACCESO:* *${data.codigo || ''}*\n\n🔗 *Enlace Directo al Portal:*\n${data.url || ''}\n\n_Por favor ingrese al enlace para validar o inactivar las especialidades de los establecimientos asignados._`;

        _ultimoMensajeWhatsAppValidador = mensaje;

        var urlWa = 'https://api.whatsapp.com/send?';
        if (data.telefono) {
            var cleanPhone = data.telefono.replace(/\D/g, '');
            if (cleanPhone.length >= 9 && !cleanPhone.startsWith('595')) {
                cleanPhone = '595' + cleanPhone.replace(/^0+/, '');
            }
            urlWa += 'phone=' + cleanPhone + '&';
        }
        urlWa += 'text=' + encodeURIComponent(mensaje);

        $('#btnShareWhatsAppDirecto').attr('href', urlWa);
        $('#btnShareAbrirPortal').attr('href', data.url || '#');

        $('#modalCompartirAccesoValidador').modal('show');
    };

    $(document).on('click', '.btn-share-validador', function(e) {
        e.preventDefault();
        var data = {
            url: $(this).data('url'),
            codigo: $(this).data('codigo'),
            analista: $(this).data('analista'),
            cargo: $(this).data('cargo'),
            telefono: $(this).data('telefono'),
            area: $(this).data('area'),
            depto: $(this).data('depto')
        };
        abrirModalCompartirValidador(data);
    });

    window.copiarTextoInput = function(elemId, msg) {
        var input = document.getElementById(elemId);
        if (!input) return;
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: msg || 'Copiado al portapapeles 📋',
                showConfirmButton: false,
                timer: 1800
            });
        });
    };

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

    // ── Inicializar DataTable para Sesiones de Validador ──
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

    // Filtro interactivo por Área
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
