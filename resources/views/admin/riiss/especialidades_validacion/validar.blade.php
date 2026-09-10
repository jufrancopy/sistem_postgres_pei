@extends('layouts.master')
@section('title', 'Validación de Especialidades — ' . ($est->nombre_oficial ?? 'Establecimiento'))

@push('styles')
<style>
.esp-card {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    transition: all 0.25s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
}
.esp-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    border-color: #cbd5e1;
}
.esp-card.card-validada {
    border-left: 5px solid #10b981 !important;
    background: #f0fdf4;
}
.esp-card.card-inactiva {
    border-left: 5px solid #ef4444 !important;
    background: #fef2f2;
}
.esp-card.card-pendiente {
    border-left: 5px solid #f59e0b !important;
    background: #ffffff;
}
.btn-val-action {
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 6px 12px;
}
.kpi-box {
    border-radius: 12px;
    padding: 12px 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}

/* Modal SignaturePad CSS fix */
#modalCierreValidacion .bmd-form-group,
#modalCierreValidacion .form-group,
#modalInactivar .bmd-form-group,
#modalInactivar .form-group,
#modalAgregarEsp .bmd-form-group,
#modalAgregarEsp .form-group {
    position: relative !important;
    margin-bottom: 1.15rem !important;
    padding-top: 0 !important;
    margin-top: 0 !important;
}
#modalCierreValidacion label,
#modalInactivar label,
#modalAgregarEsp label {
    position: static !important;
    transform: none !important;
    top: auto !important;
    left: auto !important;
    display: block !important;
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    margin-bottom: 0.35rem !important;
    pointer-events: auto !important;
    opacity: 1 !important;
}
#modalCierreValidacion .form-control,
#modalInactivar .form-control,
#modalAgregarEsp .form-control {
    position: static !important;
    display: block !important;
    width: 100% !important;
    height: 38px !important;
    padding: 0.45rem 0.75rem !important;
    font-size: 0.88rem !important;
    line-height: 1.5 !important;
    color: #0f172a !important;
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    box-shadow: none !important;
}
#modalCierreValidacion textarea.form-control,
#modalInactivar textarea.form-control {
    height: auto !important;
}
#modalCierreValidacion .form-control:focus,
#modalInactivar .form-control:focus,
#modalAgregarEsp .form-control:focus {
    border-color: #0284c7 !important;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
    outline: none !important;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-0 px-md-3">
    {{-- Header Establecimiento --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
        <div class="card-header text-white py-4 px-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-radius: 16px 16px 0 0;">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 15px;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white p-3 mr-3 shadow-sm d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                        <i class="fa fa-hospital-user fa-2x" style="color:#0284c7;"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <h4 class="font-weight-bold mb-0 text-white">{{ $est->nombre_oficial }}</h4>
                            <span class="badge badge-light text-dark font-weight-bold" style="font-size:0.75rem;">{{ $est->id_establecimiento }}</span>
                            <span class="badge badge-warning text-dark font-weight-bold" style="font-size:0.75rem;">{{ $est->tipologia_clasificacion }}</span>
                        </div>
                        <p class="mb-0 text-white-50 small mt-1">
                            <i class="fa fa-map-marker-alt mr-1"></i>{{ $est->departamento }} {{ $est->distrito ? ' — ' . $est->distrito : '' }} · Dirección de Hospitales del Área Interior
                        </p>
                    </div>
                </div>
                
                {{-- Botones de Acción Global --}}
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                    <a href="{{ route('riiss.validaciones.index') }}" class="btn btn-white btn-sm font-weight-bold shadow-sm" style="background: rgba(255,255,255,0.9); color:#0284c7; border-radius:8px;">
                        <i class="fa fa-arrow-left mr-1"></i> Volver a la Lista
                    </a>

                    @if($validacion->estado !== 'firmada')
                        <button type="button" class="btn btn-warning btn-sm font-weight-bold shadow-sm px-3" onclick="abrirModalCierreValidacion()" style="border-radius:8px;">
                            <i class="fa fa-file-signature mr-1"></i> Cerrar y Firmar Validación
                        </button>
                    @else
                        <a href="{{ route('riiss.validaciones.acta-pdf', $validacion->id) }}" target="_blank" class="btn btn-danger btn-sm font-weight-bold shadow-sm px-3" style="border-radius:8px;">
                            <i class="fa fa-file-pdf mr-1"></i> Descargar Acta PDF
                        </a>
                        <a href="{{ route('riiss.validaciones.acta-imprimir', $validacion->id) }}" target="_blank" class="btn btn-light btn-sm font-weight-bold shadow-sm px-3 text-dark" style="border-radius:8px;">
                            <i class="fa fa-print mr-1"></i> Imprimir
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Estado y Progreso --}}
        <div class="card-body p-4 bg-light">
            <div class="row align-items-center">
                <div class="col-xl-8 mb-3 mb-xl-0">
                    <div class="row" style="gap: 0px;">
                        <div class="col-sm-3 col-6 mb-2 mb-sm-0">
                            <div class="kpi-box text-center">
                                <span class="text-muted small font-weight-bold d-block">TOTAL</span>
                                <h4 class="font-weight-bold text-dark mb-0" id="kpiTotalEsp">{{ $validacion->total_especialidades }}</h4>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6 mb-2 mb-sm-0">
                            <div class="kpi-box text-center border-left" style="border-left: 3px solid #10b981 !important;">
                                <span class="text-success small font-weight-bold d-block"><i class="fa fa-check-circle mr-1"></i>VALIDADAS</span>
                                <h4 class="font-weight-bold text-success mb-0" id="kpiValidadas">{{ $validacion->total_validadas }}</h4>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="kpi-box text-center border-left" style="border-left: 3px solid #ef4444 !important;">
                                <span class="text-danger small font-weight-bold d-block"><i class="fa fa-times-circle mr-1"></i>INACTIVAS</span>
                                <h4 class="font-weight-bold text-danger mb-0" id="kpiInactivadas">{{ $validacion->total_inactivadas }}</h4>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="kpi-box text-center border-left" style="border-left: 3px solid #f59e0b !important;">
                                <span class="text-warning small font-weight-bold d-block"><i class="fa fa-hourglass-half mr-1"></i>PENDIENTES</span>
                                <h4 class="font-weight-bold text-warning mb-0" id="kpiPendientes">{{ $validacion->total_pendientes }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 text-center text-xl-right">
                    <div class="d-inline-block text-left" style="min-width: 220px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small font-weight-bold text-muted">Progreso de Validación:</span>
                            <span class="small font-weight-bold text-primary" id="txtProgresoPct">{{ $validacion->progreso_porcentaje }}%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 6px;">
                            <div class="progress-bar bg-success" id="barProgreso" style="width: {{ $validacion->progreso_porcentaje }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if($validacion->estado === 'firmada')
                <div class="alert alert-success border-0 shadow-sm mt-3 mb-0 d-flex align-items-center justify-content-between flex-wrap" style="border-radius:12px; background:#dcfce7; color:#166534;">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-double fa-2x mr-3 text-success"></i>
                        <div>
                            <strong class="d-block">Acta de Validación Cerrada y Rubricada</strong>
                            <span class="small">Validador: <strong>{{ $validacion->validador_nombre }}</strong> ({{ $validacion->validador_cargo }}) · Firmado el {{ $validacion->validador_firmado_at?->format('d/m/Y H:i') }} hs</span>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <a href="{{ route('riiss.validaciones.acta-pdf', $validacion->id) }}" target="_blank" class="btn btn-success btn-sm font-weight-bold" style="border-radius:8px;">
                            <i class="fa fa-file-signature mr-1"></i> Ver Constancia con Firma
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Buscador y Botón de Agregar Especialidad Faltante --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
        <div class="card-body p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
                <div class="flex-grow-1" style="max-width: 450px;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="filtroEspecialidad" class="form-control border-left-0" placeholder="Filtrar especialidad en la lista..." onkeyup="filtrarTarjetas()">
                    </div>
                </div>

                <div class="d-flex align-items-center" style="gap: 8px;">
                    @if($validacion->estado !== 'firmada')
                        <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold shadow-sm" onclick="abrirModalAgregarEsp()" style="border-radius:8px;">
                            <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad Faltante
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Lista Interactiva de Especialidades --}}
    <div class="row" id="contenedorEspecialidades">
        @forelse($items as $item)
            @php
                $esp = $item->especialidad;
                $meds = $item->medicamentos;
            @endphp
            <div class="col-xl-6 col-12 mb-3 item-esp-wrapper" data-nombre="{{ mb_strtolower($esp?->nombre ?? '') }}" id="wrapper-item-{{ $item->id }}">
                <div class="card esp-card card-{{ $item->estado }} h-100 p-3">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-2 mr-2 text-primary d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                <i class="fa fa-stethoscope"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-0" style="font-size:0.95rem;">
                                    {{ $esp?->nombre ?? 'Especialidad #' . $item->especialidad_id }}
                                </h6>
                                <div class="d-flex align-items-center flex-wrap mt-1" style="gap: 4px;">
                                    <span class="badge badge-light border text-muted" style="font-size:0.68rem;">ID #{{ $esp?->id }}</span>
                                    @if($item->es_agregada_en_terreno)
                                        <span class="badge badge-info text-white" style="font-size:0.68rem;"><i class="fa fa-plus mr-1"></i>Agregada en Terreno</span>
                                    @endif
                                    <span class="badge badge-{{ $item->estado === 'validada' ? 'success' : ($item->estado === 'inactiva' ? 'danger' : 'warning') }} badge-estado-item" style="font-size:0.68rem;">
                                        {{ $item->estado === 'validada' ? '✓ Activa / Validada' : ($item->estado === 'inactiva' ? '✗ Inactivada' : '⏳ Pendiente de Revisión') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Botón colapsar medicamentos --}}
                        @if($meds->count() > 0)
                            <button class="btn btn-outline-info btn-xs py-1 px-2 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseMeds-{{ $item->id }}" aria-expanded="false" style="border-radius:6px; font-size:0.75rem;">
                                <i class="fa fa-pills mr-1"></i>{{ $meds->count() }} Medicamentos
                            </button>
                        @endif
                    </div>

                    {{-- Justificación si está inactiva --}}
                    <div class="box-justificacion {{ $item->estado === 'inactiva' ? '' : 'd-none' }} p-2 rounded mb-2" style="background:#fee2e2; border-left:3px solid #ef4444; font-size:0.8rem; color:#991b1b;">
                        <strong><i class="fa fa-exclamation-triangle mr-1"></i>Motivo de Inactivación:</strong>
                        <span class="txt-justificacion">{{ $item->justificacion_inactivacion }}</span>
                    </div>

                    {{-- Desplegable de Medicamentos --}}
                    @if($meds->count() > 0)
                        <div class="collapse mb-2" id="collapseMeds-{{ $item->id }}">
                            <div class="p-2 border rounded bg-white" style="max-height:160px; overflow-y:auto; font-size:0.78rem;">
                                <strong class="text-muted small d-block mb-1">Medicamentos asignados a esta especialidad:</strong>
                                <ul class="list-unstyled mb-0 pl-1">
                                    @foreach($meds as $m)
                                        <li class="mb-1 d-flex align-items-center justify-content-between border-bottom pb-1">
                                            <span><i class="fa fa-tablets mr-1 text-primary"></i>{{ $m->nombre }}</span>
                                            <span class="badge badge-light border" style="font-size:0.68rem;">{{ $m->codigo }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Botones de Acción por Especialidad --}}
                    @if($validacion->estado !== 'firmada')
                        <div class="d-flex align-items-center justify-content-end mt-2 pt-2 border-top" style="gap: 6px;">
                            <button type="button" class="btn btn-success btn-sm btn-val-action btn-validar" onclick="validarEspecialidad({{ $item->id }})" title="Confirmar que esta especialidad funciona activamente en el establecimiento">
                                <i class="fa fa-check mr-1"></i> Validar Activa
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-val-action btn-inactivar" onclick="abrirModalInactivar({{ $item->id }}, '{{ addslashes($esp?->nombre ?? '') }}')" title="Inactivar si esta especialidad no existe en el hospital">
                                <i class="fa fa-times mr-1"></i> Inactivar / No Existe
                            </button>
                            @if($item->estado !== 'pendiente')
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="restablecerPendiente({{ $item->id }})" title="Restablecer a pendiente">
                                    <i class="fa fa-undo"></i>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted bg-white rounded shadow-sm">
                <i class="fa fa-stethoscope fa-3x text-muted mb-3 d-block"></i>
                <h5>No hay especialidades asignadas a este establecimiento</h5>
                <p class="small mb-3">Podés utilizar el botón superior para agregar especialidades encontradas en terreno.</p>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" onclick="abrirModalAgregarEsp()" style="border-radius:8px;">
                    <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad Ahora
                </button>
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL 1: Inactivar Especialidad con Justificación Obligatoria --}}
<div class="modal fade" id="modalInactivar" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size:1.05rem;">
                    <i class="fa fa-exclamation-circle mr-2"></i>Inactivar Especialidad Médica
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                <input type="hidden" id="inactivar_item_id">
                <div class="alert alert-warning border-0 p-3 mb-3" style="border-radius:10px; font-size:0.85rem;">
                    <i class="fa fa-info-circle mr-1"></i> Se asentará la inactivación de la especialidad:
                    <strong class="d-block mt-1 text-dark font-weight-bold" id="inactivar_esp_nombre" style="font-size:0.95rem;"></strong>
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark small mb-1">
                        Motivo / Justificación de la Inactivación <span class="text-danger">* (Obligatorio)</span>
                    </label>
                    <textarea class="form-control" id="inactivar_justificacion" rows="3" placeholder="Ej: Especialista renunció / Sin consultorio habilitado / Traslado de servicios a otra dependencia..." required></textarea>
                    <small class="text-muted mt-1 d-block" style="font-size:0.72rem;">Esta observación quedará registrada en el Acta Oficial para la Dirección de Hospitales del Área Interior.</small>
                </div>
            </div>
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" data-dismiss="modal" style="border-radius:8px;">
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger btn-sm font-weight-bold px-4 shadow-sm" onclick="confirmarInactivacion()" style="border-radius:8px;">
                    <i class="fa fa-ban mr-1"></i> Confirmar Inactivación
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: Agregar Especialidad Faltante --}}
<div class="modal fade" id="modalAgregarEsp" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
                <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size:1.05rem;">
                    <i class="fa fa-plus-circle mr-2"></i>Agregar Especialidad Faltante
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark small mb-1">Buscar en el Catálogo de Bioestadística / Escribir Nombre <span class="text-danger">*</span></label>
                    <select class="form-control" id="select_agregar_especialidad" style="width:100%"></select>
                </div>
                <small class="text-muted" style="font-size:0.74rem;"><i class="fa fa-info-circle mr-1"></i>La especialidad agregada se marcará automáticamente como validada y activa en el hospital.</small>
            </div>
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" data-dismiss="modal" style="border-radius:8px;">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold px-4 shadow-sm" onclick="guardarNuevaEspecialidad()" style="border-radius:8px; background:#6d28d9; border-color:#6d28d9;">
                    <i class="fa fa-save mr-1"></i> Agregar y Validar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 3: Cerrar y Firmar Validación con SignaturePad --}}
<div class="modal fade" id="modalCierreValidacion" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 650px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-info mr-2 d-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fa fa-pen-nib" style="color:#0284c7;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size:1.05rem;">
                            Cierre y Rúbrica Digital del Validador
                        </h5>
                        <small class="text-white-50" style="font-size:0.75rem;">Dirección de Hospitales del Área Interior — IPS</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light">
                <form id="formCierreValidacion" onsubmit="return false;">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Nombre y Apellido del Validador <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cierre_validador_nombre" value="{{ auth()->user() ? auth()->user()->name : '' }}" placeholder="Ej: Dr. Juan Pérez" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Cargo / Función en el Área Interior <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cierre_validador_cargo" value="Validador Técnico — Dirección de Hospitales Área Interior" placeholder="Cargo del profesional" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Cédula de Identidad (C.I.)</label>
                            <input type="text" class="form-control" id="cierre_validador_documento" placeholder="Ej: 1.234.567">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-1">Teléfono de Contacto</label>
                            <input type="text" class="form-control" id="cierre_validador_telefono" placeholder="Ej: 0981 123 456">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Observaciones Finales del Acta (Opcional)</label>
                        <textarea class="form-control" id="cierre_observaciones" rows="2" placeholder="Notas sobre el relevamiento de especialidades y vademécum..."></textarea>
                    </div>

                    {{-- Signature Pad --}}
                    <div class="form-group mb-0">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="font-weight-bold text-dark small mb-0"><i class="fa fa-pen-alt mr-1 text-primary"></i>Firma Digital del Validador <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" id="btnClearFirmaValidador" style="font-size:0.72rem; border-radius:6px;">
                                <i class="fa fa-eraser mr-1"></i>Limpiar
                            </button>
                        </div>
                        <div class="border rounded bg-white d-flex align-items-center justify-content-center p-1" style="border: 2px dashed #94a3b8 !important; border-radius: 10px;">
                            <canvas id="canvasFirmaValidador" width="580" height="150" style="touch-action: none; width: 100%; height: 150px; background: #ffffff; border-radius: 8px; cursor: crosshair;"></canvas>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size:0.72rem;"><i class="fa fa-info-circle mr-1"></i>Dibujar la firma con el cursor o en pantalla táctil en señal de conformidad técnica.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" data-dismiss="modal" style="border-radius:8px;">
                    Cancelar
                </button>
                <button type="button" class="btn btn-success btn-sm font-weight-bold px-4 shadow-sm" id="btnConfirmarCierreVal" onclick="ejecutarCierreValidacion()" style="border-radius:8px;">
                    <i class="fa fa-stamp mr-1"></i> Sellar y Cerrar Acta
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const VALIDACION_ID = {{ $validacion->id }};
let padValidador = null;

function filtrarTarjetas() {
    var query = $('#filtroEspecialidad').val().toLowerCase().trim();
    $('.item-esp-wrapper').each(function() {
        var nombre = $(this).data('nombre');
        if (!query || nombre.includes(query)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function actualizarKPIs(data) {
    $('#kpiValidadas').text(data.total_validadas);
    $('#kpiInactivadas').text(data.total_inactivadas);
    $('#kpiPendientes').text(data.total_pendientes);
    $('#txtProgresoPct').text(data.progreso + '%');
    $('#barProgreso').css('width', data.progreso + '%');
}

function validarEspecialidad(itemId) {
    $.ajax({
        url: '/riiss/validar-especialidades/' + VALIDACION_ID + '/item/' + itemId + '/estado',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            estado: 'validada'
        },
        success: function(resp) {
            var $card = $('#wrapper-item-' + itemId + ' .esp-card');
            $card.removeClass('card-pendiente card-inactiva').addClass('card-validada');
            $card.find('.badge-estado-item').removeClass('badge-warning badge-danger').addClass('badge-success').text('✓ Activa / Validada');
            $card.find('.box-justificacion').addClass('d-none');
            actualizarKPIs(resp.data);
            mostrarToast('Especialidad validada como activa.', 'success');
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al validar';
            mostrarToast(msg, 'error');
        }
    });
}

function abrirModalInactivar(itemId, nombreEsp) {
    $('#inactivar_item_id').val(itemId);
    $('#inactivar_esp_nombre').text(nombreEsp);
    $('#inactivar_justificacion').val('');
    $('#modalInactivar').modal('show');
}

function confirmarInactivacion() {
    var itemId = $('#inactivar_item_id').val();
    var justificacion = $('#inactivar_justificacion').val().trim();

    if (!justificacion) {
        mostrarToast('Por favor, ingresá el motivo de la inactivación.', 'error');
        $('#inactivar_justificacion').focus();
        return;
    }

    $.ajax({
        url: '/riiss/validar-especialidades/' + VALIDACION_ID + '/item/' + itemId + '/estado',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            estado: 'inactiva',
            justificacion: justificacion
        },
        success: function(resp) {
            $('#modalInactivar').modal('hide');
            var $card = $('#wrapper-item-' + itemId + ' .esp-card');
            $card.removeClass('card-pendiente card-validada').addClass('card-inactiva');
            $card.find('.badge-estado-item').removeClass('badge-warning badge-success').addClass('badge-danger').text('✗ Inactivada');
            $card.find('.txt-justificacion').text(justificacion);
            $card.find('.box-justificacion').removeClass('d-none');
            actualizarKPIs(resp.data);
            mostrarToast('Especialidad inactivada con justificación.', 'warning');
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al inactivar';
            mostrarToast(msg, 'error');
        }
    });
}

function restablecerPendiente(itemId) {
    $.ajax({
        url: '/riiss/validar-especialidades/' + VALIDACION_ID + '/item/' + itemId + '/estado',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            estado: 'pendiente'
        },
        success: function(resp) {
            var $card = $('#wrapper-item-' + itemId + ' .esp-card');
            $card.removeClass('card-validada card-inactiva').addClass('card-pendiente');
            $card.find('.badge-estado-item').removeClass('badge-success badge-danger').addClass('badge-warning').text('⏳ Pendiente de Revisión');
            $card.find('.box-justificacion').addClass('d-none');
            actualizarKPIs(resp.data);
            mostrarToast('Estado restablecido a pendiente.', 'info');
        }
    });
}

function abrirModalAgregarEsp() {
    $('#select_agregar_especialidad').empty();
    $('#select_agregar_especialidad').select2({
        dropdownParent: $('#modalAgregarEsp'),
        placeholder: 'Escribí para buscar o crear especialidad...',
        ajax: {
            url: '{{ route("riiss.especialidades.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) { return { q: params.term }; },
            processResults: function(data) { return data; }
        },
        tags: true,
        width: '100%'
    });
    $('#modalAgregarEsp').modal('show');
}

function guardarNuevaEspecialidad() {
    var data = $('#select_agregar_especialidad').select2('data');
    if (!data || !data[0]) {
        mostrarToast('Seleccioná o escribí una especialidad.', 'error');
        return;
    }

    var item = data[0];
    var isNew = isNaN(item.id);

    $.ajax({
        url: '/riiss/validar-especialidades/' + VALIDACION_ID + '/agregar',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            especialidad_id: isNew ? null : item.id,
            nombre: isNew ? item.text : null
        },
        success: function(resp) {
            $('#modalAgregarEsp').modal('hide');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Especialidad Agregada!',
                    text: 'La especialidad ha sido agregada y validada en el hospital.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                window.location.reload();
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al agregar especialidad';
            mostrarToast(msg, 'error');
        }
    });
}

function abrirModalCierreValidacion() {
    $('#modalCierreValidacion').modal('show');
    setTimeout(function() {
        var canvas = document.getElementById('canvasFirmaValidador');
        if (canvas) {
            if (!padValidador) {
                padValidador = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(15, 23, 42)'
                });
            }
            var r = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * r;
            canvas.height = canvas.offsetHeight * r;
            canvas.getContext("2d").scale(r, r);
            padValidador.clear();
        }
    }, 350);
}

$(document).on('click', '#btnClearFirmaValidador', function() {
    if (padValidador) padValidador.clear();
});

function ejecutarCierreValidacion() {
    var nombre = $('#cierre_validador_nombre').val().trim();
    var cargo  = $('#cierre_validador_cargo').val().trim();

    if (!nombre) {
        mostrarToast('Indique el nombre del validador', 'error');
        $('#cierre_validador_nombre').focus();
        return;
    }

    if (!cargo) {
        mostrarToast('Indique el cargo del validador', 'error');
        $('#cierre_validador_cargo').focus();
        return;
    }

    if (!padValidador || padValidador.isEmpty()) {
        mostrarToast('La firma digital del validador es obligatoria.', 'error');
        return;
    }

    var $btn = $('#btnConfirmarCierreVal');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Sellando Acta...');

    var firmaBase64 = padValidador.toDataURL('image/png');

    $.ajax({
        url: '/riiss/validar-especialidades/' + VALIDACION_ID + '/cerrar',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: '{{ csrf_token() }}',
            validador_nombre:    nombre,
            validador_cargo:     cargo,
            validador_documento: $('#cierre_validador_documento').val().trim(),
            validador_telefono:  $('#cierre_validador_telefono').val().trim(),
            validador_firma:     firmaBase64,
            observaciones_cierre:$('#cierre_observaciones').val().trim(),
        }),
        success: function(resp) {
            $btn.prop('disabled', false).html('<i class="fa fa-stamp mr-1"></i> Sellar y Cerrar Acta');
            $('#modalCierreValidacion').modal('hide');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Validación Cerrada y Firmada!',
                    html: `
                        <div class="text-center p-2">
                            <p class="mb-2 text-dark font-weight-bold">El relevamiento de especialidades ha sido sellado con éxito.</p>
                            <p class="mb-0 text-muted small">Se ha generado el Acta Oficial de Validación para <strong>${nombre}</strong>.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#0284c7',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fa fa-file-pdf mr-1"></i> Ver Acta PDF',
                    cancelButtonText: 'Permanecer aquí'
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.open(resp.data.acta_url, '_blank');
                    }
                    window.location.reload();
                });
            } else {
                mostrarToast('Validación cerrada con éxito', 'success');
                setTimeout(() => window.location.reload(), 1200);
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html('<i class="fa fa-stamp mr-1"></i> Sellar y Cerrar Acta');
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al sellar el acta';
            mostrarToast(msg, 'error');
        }
    });
}

function mostrarToast(msg, tipo) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: tipo,
            title: msg,
            showConfirmButton: false,
            timer: 2500
        });
    } else {
        alert(msg);
    }
}
</script>
@endsection
