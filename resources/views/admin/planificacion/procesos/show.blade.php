@extends('layouts.master')
@section('title', 'Flujograma & Diagnóstico de Circuito')

@push('styles')
<!-- Mermaid JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<!-- Signature Pad CDN -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<style>
    .mermaid-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        overflow-x: auto;
    }
    .metric-card {
        border-left: 4px solid #17a2b8;
        border-radius: 8px;
    }
    .metric-card.danger {
        border-left-color: #dc3545;
    }
    .metric-card.success {
        border-left-color: #28a745;
    }
    .ai-report-box {
        background: #f0f7ff;
        border-left: 4px solid #0056b3;
        border-radius: 8px;
        padding: 20px;
    }
    /* ── Estilos y Espaciado de Inputs para Modal Registrar Estación ── */
    #modalPaso .modal-body {
        padding: 1.5rem !important;
        background-color: #f8fafc;
    }
    #modalPaso label {
        display: block !important;
        position: static !important;
        float: none !important;
        margin-bottom: 6px !important;
        transform: none !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 0.88rem !important;
    }
    #modalPaso input.form-control,
    #modalPaso select.form-control,
    #modalPaso textarea.form-control {
        position: static !important;
        display: block !important;
        width: 100% !important;
        margin-top: 0 !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        color: #0f172a !important;
        padding: 8px 12px !important;
        font-size: 0.9rem !important;
    }
    #modalPaso input.form-control {
        height: 42px !important;
    }
    #modalPaso textarea.form-control {
        height: auto !important;
    }
    #modalPaso ::placeholder {
        color: #94a3b8 !important;
        opacity: 1 !important;
    }
    #modalPaso .form-group {
        margin-bottom: 1.15rem !important;
    }
    /* ── Botones Circulares btn-circle ── */
    .btn-circle {
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        border-radius: 50% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 0.95rem !important;
        line-height: 1 !important;
        transition: all 0.2s ease-in-out;
    }
    .btn-circle:hover {
        transform: scale(1.12);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
    }
    .btn-circle.btn-sm {
        width: 34px !important;
        height: 34px !important;
        font-size: 0.85rem !important;
    }

    /* ── Optimización para Tablet 10" (Lenovo 10 / Dispositivos Táctiles) ── */
    @media (max-width: 1024px) {
        .metric-card {
            margin-bottom: 1rem;
        }
        .metric-card h3 {
            font-size: 1.4rem !important;
        }
        .mermaid-container {
            padding: 10px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        #tablaPasos th, #tablaPasos td {
            padding: 12px 8px !important;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado del Relevamiento -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title font-weight-bold mb-1">
                    <i class="fas fa-project-diagram text-info mr-2"></i> {{ $proceso->nombre }}
                </h3>
                <div class="d-flex flex-wrap align-items-center text-white-50">
                    <span class="mr-3"><i class="fas fa-hospital mr-1"></i> {{ $proceso->organigrama ? $proceso->organigrama->nombre : 'Servicio General' }}</span>
                    <span class="mr-3"><i class="fas fa-calendar-alt mr-1"></i> Visita: {{ $proceso->fecha_relevamiento ? $proceso->fecha_relevamiento->format('d/m/Y') : 'Hoy' }}</span>
                </div>
            </div>
            <div>
                <a href="{{ route('pei.procesos.exportPdf', $proceso->id) }}" target="_blank" class="btn btn-danger font-weight-bold shadow-sm mr-2">
                    <i class="fas fa-file-pdf mr-1"></i> Exportar Reporte PDF
                </a>
                <a href="{{ route('pei.procesos.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary"><i class="fas fa-file-contract mr-1"></i> Contexto / Móvil de la Visita:</h6>
                    <p class="text-dark bg-white p-2 rounded border">{{ $proceso->contexto_motivo ?: 'Relevamiento preventivo de eficiencia operativa.' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-info"><i class="fas fa-bullseye mr-1"></i> Acción / Meta del PEI Vinculada:</h6>
                    <p class="text-dark bg-white p-2 rounded border">
                        @if($proceso->peiProfile)
                            @php
                                $cleanPeiName = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($proceso->peiProfile->name, ENT_QUOTES, 'UTF-8'))));
                            @endphp
                            <span class="badge badge-info mr-1" style="font-size:0.8rem;">{{ mb_strtoupper($proceso->peiProfile->getLabelNivel()) }}</span>
                            <span class="font-weight-bold">{{ $cleanPeiName }}</span>
                        @else
                            <span class="text-muted">Sin vinculación específica al PEI.</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center mt-2">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-users mr-1"></i> Equipo Relevador (Responsables / Interventores):</span>
                @forelse($proceso->responsables as $resp)
                    @if($resp->pivot->firma_digital)
                        <span class="badge badge-success px-3 py-2 mr-2 mb-1 shadow-sm d-inline-flex align-items-center" style="font-size: 0.85rem;" title="Firma Digital Sellada: {{ $resp->pivot->firmado_at }}">
                            <i class="fas fa-check-circle text-white mr-1"></i> {{ $resp->name }} (Firmado)
                        </span>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold px-3 py-1 mr-2 mb-1 shadow-sm d-inline-flex align-items-center" onclick="abrirModalFirma('{{ $resp->id }}', '{{ addslashes($resp->name) }}')">
                            <i class="fas fa-file-signature text-primary mr-1"></i> Firmar Visita: {{ $resp->name }}
                        </button>
                    @endif
                @empty
                    <span class="badge badge-secondary">Sin responsables asignados</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Barra de Tarjetas Métricas KPIs (Optimizada Tablet / Lenovo 10) -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card metric-card shadow-sm p-3 bg-white h-100">
                <small class="text-muted font-weight-bold text-uppercase d-block mb-1">Lead Time Total Paciente</small>
                <h3 id="kpi_lead_time" class="font-weight-bold text-dark mb-0">{{ $proceso->lead_time_total }} min</h3>
                <small id="kpi_lead_time_horas" class="text-info font-weight-bold"><i class="fas fa-clock mr-1"></i> {{ round($proceso->lead_time_total / 60, 1) }} horas de recorrido</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card metric-card success shadow-sm p-3 bg-white h-100">
                <small class="text-muted font-weight-bold text-uppercase d-block mb-1">Tiempo Atención Efectiva</small>
                <h3 id="kpi_atencion" class="font-weight-bold text-success mb-0">{{ $proceso->tiempo_atencion_total }} min</h3>
                <small class="text-muted">Valor agregado al paciente</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card metric-card danger shadow-sm p-3 bg-white h-100">
                <small class="text-muted font-weight-bold text-uppercase d-block mb-1">Tiempo Espera / Latencia</small>
                <h3 id="kpi_espera" class="font-weight-bold text-danger mb-0">{{ $proceso->tiempo_espera_total }} min</h3>
                <small class="text-danger font-weight-bold"><i class="fas fa-hourglass-half mr-1"></i> Tiempo muerto en cola</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="card metric-card shadow-sm p-3 bg-white h-100">
                <small class="text-muted font-weight-bold text-uppercase d-block mb-1">Eficiencia & Cuellos</small>
                <div class="d-flex flex-wrap align-items-center justify-content-between mt-1 gap-1">
                    <span id="kpi_eficiencia" class="badge badge-pill badge-info px-2 py-2 mb-1" style="font-size: 0.92rem; max-width: 100%; white-space: normal;">
                        <i class="fas fa-chart-pie mr-1"></i>{{ $proceso->eficiencia }}% Eficiencia
                    </span>
                    <span id="kpi_cuellos" class="badge badge-pill badge-danger px-2 py-1 mb-1" style="font-size: 0.82rem;">
                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $proceso->conteo_cuellos_botella }} Cuello(s)
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Flujograma Visual Interactivo Mermaid -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-sitemap mr-2"></i> Flujograma Visual del Circuito de Atención
            </h5>
            <small class="text-white-50">🔴 Rojo = Cuello de Botella Crítico | 🟡 Amarillo = Demora | 🟢 Verde = Flujo Óptimo</small>
        </div>
        <div class="card-body">
            <div class="mermaid-container text-center">
                <pre class="mermaid" id="mermaidDiagram">
{!! $mermaidGraph !!}
                </pre>
            </div>
        </div>
    </div>

    <!-- Informe de Diagnóstico Inteligente de IA -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-robot mr-2"></i> Informe de Diagnóstico Asistido por Inteligencia Artificial (IA)
            </h5>
            <button id="btnRegenerarIa" class="btn btn-sm font-weight-bold shadow-sm px-3" style="background-color: #ffffff !important; color: #0369a1 !important; border: 1px solid #cbd5e1 !important; border-radius: 8px; font-size: 0.88rem;">
                <i class="fas fa-magic text-primary mr-1"></i> Regenerar Análisis IA
            </button>
        </div>
        <div class="card-body">
            <div class="ai-report-box" id="boxAnalisisIa">
                {!! \Illuminate\Support\Str::markdown($proceso->analisis_ia ?: 'Generando análisis de diagnóstico...') !!}
            </div>
        </div>
    </div>

    <!-- Estaciones / Pasos del Circuito (Tabla interactiva) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-list-ol mr-2"></i> Estaciones del Circuito (Relevamiento Paso a Paso)
            </h5>
            <button class="btn btn-success btn-sm font-weight-bold shadow-sm" onclick="abrirModalPaso()">
                <i class="fas fa-plus-circle mr-1"></i> Agregar Estación
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0" id="tablaPasos">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>Estación / Paso</th>
                            <th>Área / Dependencia</th>
                            <th>Rol Responsable</th>
                            <th>Atención</th>
                            <th>Espera</th>
                            <th>Sistema</th>
                            <th>Estado</th>
                            <th>Propuesta de Mejora</th>
                            <th class="text-center" style="width: 100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proceso->pasos as $paso)
                            <tr class="{{ $paso->es_cuello_botella ? 'table-danger' : '' }}">
                                <td class="font-weight-bold text-center align-middle">{{ $paso->orden }}</td>
                                <td class="align-middle">
                                    <strong class="text-dark d-block">{{ $paso->nombre }}</strong>
                                    @if($paso->descripcion)<small class="text-muted d-block">{{ $paso->descripcion }}</small>@endif
                                </td>
                                <td class="align-middle">{{ $paso->area_nombre }}</td>
                                <td class="align-middle"><span class="badge badge-secondary">{{ $paso->rol_responsable ?: 'N/A' }}</span></td>
                                <td class="text-success font-weight-bold align-middle">{{ $paso->tiempo_atencion_min }} min</td>
                                <td class="text-danger font-weight-bold align-middle">{{ $paso->tiempo_espera_min }} min</td>
                                <td class="align-middle"><small class="text-dark font-weight-bold">{{ $paso->herramienta_sistema ?: 'Manual' }}</small></td>
                                <td class="align-middle">
                                    @if($paso->es_cuello_botella)
                                        <span class="badge badge-danger badge-pill"><i class="fas fa-exclamation-triangle mr-1"></i>CUELLO BOTELLA</span>
                                    @else
                                        <span class="badge badge-success badge-pill"><i class="fas fa-check mr-1"></i>Normal</span>
                                    @endif
                                </td>
                                <td class="align-middle"><small class="text-dark font-italic">{{ $paso->propuesta_mejora ?: 'Sin observaciones' }}</small></td>
                                <td class="text-center align-middle" style="white-space: nowrap;">
                                    <div class="d-inline-flex align-items-center justify-content-center">
                                        <button type="button" class="btn btn-info btn-circle btn-sm mr-1 shadow-sm" title="Editar Estación" onclick='editarPaso(@json($paso))'>
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-circle btn-sm shadow-sm" title="Eliminar Estación" onclick="eliminarPaso('{{ $paso->id }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No hay estaciones registradas aún. ¡Haga clic en <strong>Agregar Estación</strong> para comenzar!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Estación / Paso -->
<div class="modal fade" id="modalPaso" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-xl overflow-hidden" style="border-radius: 14px;">
            <form id="formPaso" action="{{ route('pei.procesos.pasos.store', $proceso->id) }}" method="POST">
                @csrf
                <input type="hidden" name="paso_id" id="paso_id">
                <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                    <h5 class="modal-title font-weight-bold text-white mb-0" id="modalPasoTitle"><i class="fas fa-step-forward mr-2"></i> Registrar Estación del Circuito</h5>
                    <button type="button" class="close text-white opacity-8" data-dismiss="modal" style="outline:none;">&times;</button>
                </div>
                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="paso_orden" class="font-weight-bold text-dark mb-1">N° Orden</label>
                            <input type="number" name="orden" id="paso_orden" class="form-control" value="{{ $proceso->pasos->count() + 1 }}" required>
                        </div>
                        <div class="col-md-9 form-group">
                            <label for="paso_nombre" class="font-weight-bold text-dark mb-1">Nombre de la Estación <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="paso_nombre" class="form-control" placeholder="Ej: Verificación de Seguro y Aportes en Ventanilla" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="paso_area_dependencia_custom" class="font-weight-bold text-dark mb-1">
                                <i class="fas fa-building text-primary mr-1"></i> Área / Dependencia Local <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="area_dependencia_custom" id="paso_area_dependencia_custom" class="form-control" placeholder="Ej: Ventanilla de Admisión, Ventanilla de Agendamiento, Triage..." required>
                            <small class="form-text text-muted">Especificar área, ventanilla o sector físico.</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Rol Responsable de Atención</label>
                            <input type="text" name="rol_responsable" id="paso_rol_responsable" class="form-control" placeholder="Ej: Admisionista / Enfermero Triage">
                        </div>
                    </div>

                    <div class="row bg-light p-2 rounded mb-3 border">
                        <div class="col-md-4 form-group mb-0">
                            <label class="font-weight-bold text-success">Tiempo Atención (min)</label>
                            <input type="number" name="tiempo_atencion_min" id="paso_tiempo_atencion_min" class="form-control" value="5" min="0" required>
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="font-weight-bold text-danger">Tiempo Espera / Cola (min)</label>
                            <input type="number" name="tiempo_espera_min" id="paso_tiempo_espera_min" class="form-control" value="0" min="0" required>
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="font-weight-bold text-info">Tiempo Traslado (min)</label>
                            <input type="number" name="tiempo_traslado_min" id="paso_tiempo_traslado_min" class="form-control" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Sistema Informático / Herramienta</label>
                            <input type="text" name="herramienta_sistema" id="paso_herramienta_sistema" class="form-control" placeholder="Ej: SIH / Ficha Papel / Agendamiento Web">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="paso_es_cuello_botella" class="font-weight-bold text-dark mb-1 d-block">
                                <i class="fas fa-exclamation-triangle text-danger mr-1"></i> Diagnóstico de Fricción
                            </label>
                            <div class="p-2 border rounded bg-white d-flex align-items-center" style="border-radius: 8px; border-color: #cbd5e1 !important; height: 42px;">
                                <div class="custom-control custom-switch pl-4 w-100">
                                    <input type="checkbox" class="custom-control-input" name="es_cuello_botella" value="1" id="paso_es_cuello_botella">
                                    <label class="custom-control-label font-weight-bold text-danger cursor-pointer mb-0" for="paso_es_cuello_botella" style="cursor: pointer; font-size: 0.88rem; user-select: none;">
                                        🚨 Marcar como Cuello de Botella Crítico
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Criticidad</label>
                            <select name="criticidad" id="paso_criticidad" class="form-control select2-modal-paso" style="width: 100%;">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                                <option value="critica">Crítica</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Causa Raíz Principal</label>
                            <select name="causa_raiz" id="paso_causa_raiz" class="form-control select2-modal-paso" style="width: 100%;">
                                <option value="">-- Sin Causa Específica --</option>
                                <option value="sobredemanda">Sobredemanda de Pacientes</option>
                                <option value="falta_personal">Falta de Personal en Ventanilla</option>
                                <option value="falla_sistema">Caída / Lentitud de Sistema</option>
                                <option value="burocracia_papel">Burocracia Trámites Manuales</option>
                                <option value="espacio_fisico">Espacio Físico Reducido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Observaciones de Campo</label>
                        <textarea name="observacion_campo" id="paso_observacion_campo" class="form-control" rows="2" placeholder="Hallazgos durante la observación en vivo..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Propuesta de Mejora Sugerida</label>
                        <textarea name="propuesta_mejora" id="paso_propuesta_mejora" class="form-control" rows="2" placeholder="Acción correctiva propuesta para esta estación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-save mr-1"></i> Guardar Estación</button>
                </div>
            </form>
        </div>
</div>

<!-- Modal Firma Digital de Responsable / Interventor -->
<div class="modal fade" id="modalFirmaResponsable" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white p-3">
                <h5 class="modal-title font-weight-bold" id="modalFirmaTitle">
                    <i class="fas fa-signature text-info mr-2"></i> Firma Digital de Relevamiento / Visita
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formFirmaModal">
                @csrf
                <input type="hidden" name="user_id" id="firma_user_id">
                <input type="hidden" name="firma" id="firma_base64">

                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center" style="font-size: 0.88rem;">
                        <i class="fas fa-user-circle fa-2x mr-3 text-info"></i>
                        <div>
                            <small class="text-muted d-block text-uppercase font-weight-bold">Funcionario / Interventor:</small>
                            <strong id="firma_user_name_display" class="text-dark" style="font-size: 1rem;"></strong>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold text-dark small mb-1">Firma Digital (Dibuje con Dedo / Lápiz / Mouse)</label>
                        <div class="border rounded p-2 bg-white text-center" style="border-color: #cbd5e1 !important; touch-action: none;">
                            <canvas id="canvas-firma-proceso" class="w-100 bg-white rounded" style="border: 1px dashed #94a3b8; height: 160px; cursor: crosshair;"></canvas>
                            <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                <span class="small text-muted"><i class="fas fa-info-circle mr-1"></i> Dibuje su firma en el recuadro</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="btnClearFirmaCanvas" style="font-size: 0.75rem;">Limpiar Canvas</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small mb-1">Observaciones / Nota de Firma (Opcional)</label>
                        <input type="text" name="observaciones" id="firma_observaciones" class="form-control form-control-sm" placeholder="Ej: Visita de campo validada en sitio">
                    </div>

                    <div class="mt-3 text-muted" style="font-size: 0.76rem; line-height: 1.3;">
                        <i class="fas fa-shield-alt text-success mr-1"></i>
                        La firma digital será estampada criptográficamente en el Reporte Técnico PDF institucional.
                    </div>
                </div>
                <div class="modal-footer bg-white p-3">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardarFirmaModal" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Registrar Firma Digital
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Botón Flotante Táctil (FAB) Optimizado para Tablet / Lenovo 10 -->
<button type="button" class="btn btn-success btn-circle shadow-lg d-lg-none" style="position: fixed; bottom: 25px; right: 25px; width: 56px !important; height: 56px !important; z-index: 1040; font-size: 1.25rem !important;" onclick="abrirModalPaso()" title="Agregar Nueva Estación">
    <i class="fas fa-plus"></i>
</button>
@endsection

@push('scripts')
<script>
mermaid.initialize({ startOnLoad: true, theme: 'default' });

var signaturePadProceso = null;

function initSignaturePadProceso() {
    var canvas = document.getElementById('canvas-firma-proceso');
    if (!canvas) return;
    
    if (!signaturePadProceso) {
        signaturePadProceso = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(15, 23, 42)'
        });
    }

    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    signaturePadProceso.clear();
}

function abrirModalFirma(userId, userName) {
    $('#firma_user_id').val(userId);
    $('#firma_user_name_display').text(userName);
    $('#firma_observaciones').val('');
    $('#modalFirmaResponsable').modal('show');
    setTimeout(function() {
        initSignaturePadProceso();
    }, 250);
}

function initSelect2ModalPaso() {
    if ($.fn.select2) {
        $('#modalPaso .select2-modal-paso').select2({
            dropdownParent: $('#modalPaso'),
            width: '100%'
        });
    }
}

$(document).ready(function() {
    initSelect2ModalPaso();

    $('#btnClearFirmaCanvas').on('click', function() {
        if (signaturePadProceso) signaturePadProceso.clear();
    });

    $('#formFirmaModal').on('submit', function(e) {
        e.preventDefault();
        if (!signaturePadProceso || signaturePadProceso.isEmpty()) {
            toastr.warning('Por favor, dibuje su firma digital en el recuadro antes de registrar.');
            return;
        }

        var dataUrl = signaturePadProceso.toDataURL('image/png');
        $('#firma_base64').val(dataUrl);

        var btn = $('#btnGuardarFirmaModal');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Registrando...');

        $.ajax({
            url: "{{ route('pei.procesos.firmar', $proceso->id) }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Registrar Firma Digital');
                $('#modalFirmaResponsable').modal('hide');
                toastr.success(res.message);
                setTimeout(function() {
                    location.reload();
                }, 500);
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Registrar Firma Digital');
                toastr.error('Error al registrar la firma digital.');
            }
        });
    });

    $('#modalPaso').on('shown.bs.modal', function() {
        initSelect2ModalPaso();
    });

    $('#formPaso').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Estación');
                $('#modalPaso').modal('hide');
                toastr.success(res.message);
                updateProcesoView(res);
            },
            error: function(err) {
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar Estación');
                var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error al guardar la estación.';
                toastr.error(msg);
            }
        });
    });

    $('#btnRegenerarIa').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Analizando con IA...');
        $.ajax({
            url: "{{ route('pei.procesos.generarIa', $proceso->id) }}",
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-magic text-primary mr-1"></i> Regenerar Análisis IA');
                toastr.success('Análisis de IA actualizado correctamente.');
                updateProcesoView(res);
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-magic text-primary mr-1"></i> Regenerar Análisis IA');
                toastr.error('Error al solicitar el análisis de IA.');
            }
        });
    });
});

function updateProcesoView(res) {
    if (!res) return;

    // 1. Actualizar KPIs
    if (res.lead_time_total !== undefined) $('#kpi_lead_time').text(res.lead_time_total + ' min');
    if (res.lead_time_horas !== undefined) $('#kpi_lead_time_horas').html('<i class="fas fa-clock mr-1"></i> ' + res.lead_time_horas + ' horas de recorrido');
    if (res.tiempo_atencion_total !== undefined) $('#kpi_atencion').text(res.tiempo_atencion_total + ' min');
    if (res.tiempo_espera_total !== undefined) $('#kpi_espera').text(res.tiempo_espera_total + ' min');
    if (res.eficiencia !== undefined) $('#kpi_eficiencia').html('<i class="fas fa-chart-pie mr-1"></i>' + res.eficiencia + '% Eficiencia');
    if (res.conteo_cuellos_botella !== undefined) $('#kpi_cuellos').html('<i class="fas fa-exclamation-triangle mr-1"></i>' + res.conteo_cuellos_botella + ' Cuello(s)');

    // 2. Actualizar Informe de IA
    if (res.analisis_ia_html !== undefined) {
        $('#boxAnalisisIa').html(res.analisis_ia_html);
    }

    // 3. Actualizar Flujograma Mermaid sin recargar la página
    if (res.mermaid_graph) {
        var $mermaidDiv = $('#mermaidDiagram');
        $mermaidDiv.removeAttr('data-processed').text(res.mermaid_graph);
        if (window.mermaid) {
            try {
                mermaid.run ? mermaid.run({ nodes: [$mermaidDiv[0]] }) : mermaid.init(undefined, $mermaidDiv[0]);
            } catch(e) {
                console.log("Mermaid refresh: ", e);
            }
        }
    }

    // 4. Actualizar Tabla de Estaciones Dinámicamente
    if (res.pasos) {
        var tbodyHtml = '';
        if (res.pasos.length === 0) {
            tbodyHtml = '<tr><td colspan="10" class="text-center text-muted py-4">No hay estaciones registradas aún. ¡Haga clic en <strong>Agregar Estación</strong> para comenzar!</td></tr>';
        } else {
            res.pasos.forEach(function(paso) {
                var rowClass = paso.es_cuello_botella ? 'table-danger' : '';
                var descHtml = paso.descripcion ? '<small class="text-muted d-block">' + escapeHtml(paso.descripcion) + '</small>' : '';
                var cuelloBadge = paso.es_cuello_botella 
                    ? '<span class="badge badge-danger badge-pill"><i class="fas fa-exclamation-triangle mr-1"></i>CUELLO BOTELLA</span>'
                    : '<span class="badge badge-success badge-pill"><i class="fas fa-check mr-1"></i>Normal</span>';
                
                var pasoJsonStr = escapeAttribute(JSON.stringify(paso));

                tbodyHtml += '<tr class="' + rowClass + '">' +
                    '<td class="font-weight-bold text-center align-middle">' + paso.orden + '</td>' +
                    '<td class="align-middle"><strong class="text-dark d-block">' + escapeHtml(paso.nombre) + '</strong>' + descHtml + '</td>' +
                    '<td class="align-middle">' + escapeHtml(paso.area_nombre) + '</td>' +
                    '<td class="align-middle"><span class="badge badge-secondary">' + escapeHtml(paso.rol_responsable) + '</span></td>' +
                    '<td class="text-success font-weight-bold align-middle">' + paso.tiempo_atencion_min + ' min</td>' +
                    '<td class="text-danger font-weight-bold align-middle">' + paso.tiempo_espera_min + ' min</td>' +
                    '<td class="align-middle"><small class="text-dark font-weight-bold">' + escapeHtml(paso.herramienta_sistema) + '</small></td>' +
                    '<td class="align-middle">' + cuelloBadge + '</td>' +
                    '<td class="align-middle"><small class="text-dark font-italic">' + escapeHtml(paso.propuesta_mejora) + '</small></td>' +
                    '<td class="text-center align-middle" style="white-space: nowrap;">' +
                        '<div class="d-inline-flex align-items-center justify-content-center">' +
                            '<button type="button" class="btn btn-info btn-circle btn-sm mr-1 shadow-sm" title="Editar Estación" onclick=\'editarPaso(' + pasoJsonStr + ')\'><i class="fas fa-pencil-alt"></i></button>' +
                            '<button type="button" class="btn btn-danger btn-circle btn-sm shadow-sm" title="Eliminar Estación" onclick="eliminarPaso(\'' + paso.id + '\')"><i class="fas fa-trash-alt"></i></button>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
            });
        }
        $('#tablaPasos tbody').html(tbodyHtml);
    }

    if (res.next_orden !== undefined) {
        $('#paso_orden').val(res.next_orden);
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function escapeAttribute(str) {
    if (!str) return "{}";
    return String(str).replace(/'/g, "&#39;");
}

function abrirModalPaso() {
    $('#formPaso')[0].reset();
    $('#paso_id').val('');
    $('#paso_criticidad').val('baja').trigger('change');
    $('#paso_causa_raiz').val('').trigger('change');
    $('#modalPasoTitle').html('<i class="fas fa-step-forward mr-2"></i> Registrar Estación del Circuito');
    $('#modalPaso').modal('show');
}

function editarPaso(paso) {
    if (typeof paso === 'string') {
        try { paso = JSON.parse(paso); } catch(e) {}
    }
    $('#paso_id').val(paso.id);
    $('#paso_orden').val(paso.orden);
    $('#paso_nombre').val(paso.nombre);
    $('#paso_area_dependencia_custom').val(paso.area_dependencia_custom || paso.area_nombre || '');
    $('#paso_rol_responsable').val(paso.rol_responsable !== 'N/A' ? paso.rol_responsable : '');
    $('#paso_tiempo_atencion_min').val(paso.tiempo_atencion_min);
    $('#paso_tiempo_espera_min').val(paso.tiempo_espera_min);
    $('#paso_tiempo_traslado_min').val(paso.tiempo_traslado_min);
    $('#paso_herramienta_sistema').val(paso.herramienta_sistema !== 'Manual' ? paso.herramienta_sistema : '');
    $('#paso_es_cuello_botella').prop('checked', paso.es_cuello_botella);
    $('#paso_criticidad').val(paso.criticidad || 'baja').trigger('change');
    $('#paso_causa_raiz').val(paso.causa_raiz || '').trigger('change');
    $('#paso_observacion_campo').val(paso.observacion_campo || '');
    $('#paso_propuesta_mejora').val(paso.propuesta_mejora !== 'Sin observaciones' ? paso.propuesta_mejora : '');
    $('#modalPasoTitle').html('<i class="fas fa-edit mr-2"></i> Editar Estación #' + paso.orden);
    $('#modalPaso').modal('show');
}

function eliminarPaso(pasoId) {
    if (confirm('¿Está seguro de eliminar esta estación del circuito?')) {
        $.ajax({
            url: "{{ url('pei/procesos/' . $proceso->id . '/pasos') }}/" + pasoId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                toastr.success(res.message);
                updateProcesoView(res);
            },
            error: function() {
                toastr.error('Error al eliminar la estación.');
            }
        });
    }
}
</script>
@endpush
