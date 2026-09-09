@extends('layouts.master')
@section('title', 'Evaluación #' . $evaluacion->id)

@push('styles')
<style>
.gap-item { padding:10px 14px; border-radius:8px; margin-bottom:6px; border:1px solid #e5e7eb; }
.gap-cumple        { border-left:4px solid #22c55e; background:#f0fdf4; }
.gap-no_cumple     { border-left:4px solid #ef4444; background:#fef2f2; }
.gap-no_verificable{ border-left:4px solid #f97316; background:#fff7ed; }
.gap-pendiente     { border-left:4px solid #94a3b8; background:#f8fafc; }
.gap-no_aplica     { border-left:4px solid #e2e8f0; background:#f8fafc; }
.grupo-header { background:#f9fafb; border-radius:8px; padding:10px 14px; margin-bottom:8px; cursor:pointer; }
.pct-ring { width:80px; height:80px; }
#evalTabs .nav-link { color:#343a40 !important; }
#evalTabs .nav-link.active { color:#1a237e !important; font-weight:600; }
#evalTabs .nav-link:hover { color:#1a237e !important; }

/* Modal Estampar Firma fixes for Material Dashboard */
#modalFirmarEvaluador .bmd-form-group,
#modalFirmarEvaluador .form-group {
    position: relative !important;
    margin-bottom: 1.15rem !important;
    padding-top: 0 !important;
    margin-top: 0 !important;
}
#modalFirmarEvaluador label,
#modalFirmarEvaluador .form-label-custom,
#modalFirmarEvaluador .bmd-label-floating,
#modalFirmarEvaluador .bmd-label-static,
#modalFirmarEvaluador .control-label {
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
#modalFirmarEvaluador .form-control,
#modalFirmarEvaluador .form-control-custom {
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
#modalFirmarEvaluador .form-control:focus,
#modalFirmarEvaluador .form-control-custom:focus {
    border-color: #0284c7 !important;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
    outline: none !important;
}
#modalFirmarEvaluador select.form-control,
#modalFirmarEvaluador select.form-control-custom {
    height: 38px !important;
    cursor: pointer !important;
}
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-chart-bar mr-2"></i>Evaluación #{{ $evaluacion->id }}
            — {{ $evaluacion->establecimiento->nombre_oficial ?? '—' }}
        </h4>
        <p class="card-category">
            {{ $evaluacion->fecha_evaluacion?->format('d/m/Y') }}
            @if($evaluacion->evaluador_nombre) · {{ $evaluacion->evaluador_nombre }} @endif
        </p>
    </div>

    <nav class="bg-light px-3 py-2">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('riiss.evaluaciones.index') }}">Evaluaciones</a></li>
            <li class="breadcrumb-item active">#{{ $evaluacion->id }}</li>
        </ol>
    </nav>

    <div class="card-body">

        @php
            $pct    = $evaluacion->porcentaje_cumplimiento ?? 0;
            $clasif = $evaluacion->clasificacion_resultado;
            $est    = $evaluacion->establecimiento;
            $clasifColor = match($clasif) {
                'CUMPLE'              => 'success',
                'CUMPLE_PARCIALMENTE' => 'warning',
                'NO_CUMPLE'           => 'danger',
                default               => 'secondary',
            };
            $clasifIcon = match($clasif) {
                'CUMPLE'              => '✅',
                'CUMPLE_PARCIALMENTE' => '⚠️',
                'NO_CUMPLE'           => '❌',
                default               => '❓',
            };
        @endphp

        {{-- Resumen ejecutivo --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size:2.5rem">{{ $clasifIcon }}</div>
                        <h5 class="font-weight-bold text-{{ $clasifColor }} mt-2">
                            {{ str_replace('_', ' ', $clasif ?? 'PENDIENTE') }}
                        </h5>
                        <div class="h2 font-weight-bold text-{{ $clasifColor }}">{{ $pct }}%</div>
                        <small class="text-muted">cumplimiento</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">Establecimiento</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><th class="text-muted small py-1">Nombre</th><td class="small">{{ $est->nombre_oficial }}</td></tr>
                            <tr><th class="text-muted small py-1">Tipología</th><td class="small">{{ $est->tipologia_clasificacion }}</td></tr>
                            <tr><th class="text-muted small py-1">Complejidad</th><td>
                                <span class="badge" style="background:{{ $est->complejidad_color }};color:#fff;font-size:.7rem">
                                    {{ $est->complejidad }}
                                </span>
                            </td></tr>
                            <tr><th class="text-muted small py-1">Nivel</th><td class="small">{{ $est->nivel_atencion }} / Grado {{ $est->grado_complejidad }}</td></tr>
                            <tr><th class="text-muted small py-1">Departamento</th><td class="small">{{ $est->departamento }}</td></tr>
                            <tr><th class="text-muted small py-1">Especialidades</th><td><span class="badge badge-pill badge-primary" style="font-size:.72rem"><i class="fa fa-stethoscope mr-1"></i>{{ $est->especialidades->count() }} registradas</span></td></tr>
                        </table>
                        <button class="btn btn-sm btn-outline-info btn-block mt-3" onclick="abrirModalDetalles()">
                            <i class="fa fa-info-circle mr-1"></i> Ver Detalles y Mapa
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body" id="resumenGap">
                        <h6 class="font-weight-bold mb-3">Gap Analysis</h6>
                        <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card de Acta de Cierre y Firmas Digitales --}}
        @if($evaluacion->responsable_firma || $evaluacion->cerrado_at)
        @php
            $firmasExistentes = is_array($evaluacion->firmas_evaluadores) ? $evaluacion->firmas_evaluadores : [];
            if (empty($firmasExistentes) && $evaluacion->evaluador_nombre) {
                $firmasExistentes[] = [
                    'nombre' => $evaluacion->evaluador_nombre,
                    'cargo'  => $evaluacion->cerradoPor ? 'Evaluador Técnico — Dirección de Planificación' : 'Evaluador IPS',
                    'firma'  => null,
                    'firmado_at' => $evaluacion->cerrado_at?->format('Y-m-d H:i:s'),
                ];
            }
            $evaluadoresComision = is_array($evaluacion->evaluadores) ? $evaluacion->evaluadores : [];
        @endphp
        <div class="card border-0 shadow-sm mb-4" style="border-radius:16px; overflow:hidden; border-left: 6px solid #10b981 !important;">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-light p-2 mr-3 text-success d-flex align-items-center justify-content-center" style="width:42px; height:42px;">
                        <i class="fa fa-file-signature fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                            Acta de Cierre & Firmas Digitales en Terreno
                        </h5>
                        <small class="text-muted">Relevamiento técnico auditado y formalmente cerrado en el establecimiento.</small>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size:0.8rem; border-radius:8px;">
                        <i class="fa fa-check-double mr-1"></i> Cerrado el {{ $evaluacion->cerrado_at ? $evaluacion->cerrado_at->format('d/m/Y H:i') : ($evaluacion->responsable_firmado_at ? $evaluacion->responsable_firmado_at->format('d/m/Y H:i') : date('d/m/Y')) }} hs
                    </span>
                    <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold shadow-sm" onclick="abrirModalFirmarEvaluador()" style="border-radius:8px;">
                        <i class="fa fa-pen-alt mr-1"></i> Estampar Firma de Evaluador
                    </button>
                    <a href="{{ route('riiss.evaluaciones.acta-pdf', $evaluacion->id) }}" target="_blank" class="btn btn-danger btn-sm font-weight-bold shadow-sm" style="border-radius:8px;">
                        <i class="fa fa-file-pdf mr-1"></i> Descargar PDF Oficial
                    </a>
                    <a href="{{ route('riiss.evaluaciones.acta-imprimir', $evaluacion->id) }}" target="_blank" class="btn btn-warning btn-sm font-weight-bold shadow-sm" style="border-radius:8px;">
                        <i class="fa fa-print mr-1"></i> Vista Imprimible
                    </a>
                </div>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="row">
                    {{-- Firma del Responsable del Establecimiento --}}
                    <div class="col-lg-5 col-md-6 mb-3">
                        <div class="p-3 rounded border h-100 d-flex flex-column justify-content-between" style="background:#f8fafc; border-color:#e2e8f0 !important; border-radius:12px;">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="font-size:0.7rem;">
                                        <i class="fa fa-user-tie mr-1"></i>RECEPTOR DEL CENTRO
                                    </span>
                                    <small class="text-muted"><i class="fa fa-clock mr-1"></i>{{ $evaluacion->responsable_firmado_at ? $evaluacion->responsable_firmado_at->format('d/m/Y H:i') : '—' }}</small>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-0">{{ $evaluacion->responsable_nombre ?: 'Sin especificar' }}</h6>
                                <p class="text-primary font-weight-bold small mb-1">{{ $evaluacion->responsable_cargo ?: 'Responsable del Establecimiento' }}</p>
                                @if($evaluacion->responsable_documento)
                                    <small class="text-muted d-block"><i class="fa fa-id-card mr-1"></i>C.I.: {{ $evaluacion->responsable_documento }}</small>
                                @endif
                                @if($evaluacion->responsable_telefono)
                                    <small class="text-muted d-block"><i class="fa fa-phone mr-1"></i>Tel: {{ $evaluacion->responsable_telefono }}</small>
                                @endif
                            </div>
                            <div class="mt-3 text-center pt-2 border-top bg-white p-2 rounded border">
                                @if($evaluacion->responsable_firma)
                                    <img src="{{ $evaluacion->responsable_firma }}" alt="Firma del Responsable" style="max-height: 90px; max-width: 100%; object-fit: contain;">
                                    <div class="text-muted small border-top pt-1 mt-1" style="font-size:0.7rem;">Firma Digital Estampada</div>
                                @else
                                    <span class="text-muted small font-italic py-3 d-block"><i class="fa fa-pen-slash mr-1"></i>Sin firma digital registrada</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Firmas de Evaluadores / Equipo IPS --}}
                    <div class="col-lg-7 col-md-6 mb-3">
                        <div class="row">
                            @forelse($firmasExistentes as $idx => $fEval)
                                <div class="col-{{ count($firmasExistentes) > 1 ? '12 col-xl-6' : '12' }} mb-2">
                                    <div class="p-3 rounded border h-100 d-flex flex-column justify-content-between" style="background:#f8fafc; border-color:#e2e8f0 !important; border-radius:12px;">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="badge badge-info text-white font-weight-bold px-2 py-1" style="font-size:0.7rem;">
                                                    <i class="fa fa-user-check mr-1"></i>EVALUADOR IPS {{ count($firmasExistentes) > 1 ? '#' . ($idx + 1) : '' }}
                                                </span>
                                                <small class="text-muted"><i class="fa fa-clock mr-1"></i>{{ !empty($fEval['firmado_at']) ? \Carbon\Carbon::parse($fEval['firmado_at'])->format('d/m/Y H:i') : ($evaluacion->cerrado_at ? $evaluacion->cerrado_at->format('d/m/Y H:i') : '—') }}</small>
                                            </div>
                                            <h6 class="font-weight-bold text-dark mb-0">{{ $fEval['nombre'] ?? 'Evaluador IPS' }}</h6>
                                            <p class="text-info font-weight-bold small mb-1">{{ $fEval['cargo'] ?? 'Evaluador / Analista RIISS' }}</p>
                                            @if(!empty($fEval['email']))
                                                <small class="text-muted d-block"><i class="fa fa-envelope mr-1"></i>{{ $fEval['email'] }}</small>
                                            @endif
                                        </div>
                                        <div class="mt-3 text-center pt-2 border-top bg-white p-2 rounded border">
                                            @if(!empty($fEval['firma']))
                                                <img src="{{ $fEval['firma'] }}" alt="Firma del Evaluador" style="max-height: 90px; max-width: 100%; object-fit: contain;">
                                                <div class="text-muted small border-top pt-1 mt-1" style="font-size:0.7rem;">Firma Digital Estampada</div>
                                            @else
                                                <span class="text-muted small font-italic py-3 d-block"><i class="fa fa-pen-slash mr-1"></i>Sin firma digital registrada</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light border text-center py-4">
                                        <i class="fa fa-user-clock fa-2x text-muted mb-2"></i>
                                        <p class="small text-muted mb-0">Sin firmas de evaluadores técnicos registradas.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if($evaluacion->cierre_observaciones)
                    <div class="p-3 rounded bg-light border mt-2">
                        <strong class="text-dark small d-block mb-1"><i class="fa fa-sticky-note text-warning mr-1"></i>Observaciones y Acuerdos de Cierre:</strong>
                        <p class="text-muted small mb-0">{{ $evaluacion->cierre_observaciones }}</p>
                    </div>
                @endif
            </div>
        </div>
        @else
        {{-- Alerta si aún no tiene cierre con firmas --}}
        <div class="alert bg-white border shadow-sm mb-4 d-flex align-items-center justify-content-between flex-wrap" style="border-radius:14px; border-left: 5px solid #f59e0b !important; gap:12px;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-light p-2 mr-3 text-warning d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                    <i class="fa fa-exclamation-triangle fa-lg"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold text-dark mb-0">Relevamiento Pendiente de Cierre Formal y Firmas</h6>
                    <small class="text-muted">Esta evaluación no cuenta con el acta de cierre digital firmada por el receptor y el evaluador.</small>
                </div>
            </div>
            <a href="{{ route('riiss.evaluaciones.nueva', $evaluacion->id_establecimiento) }}" class="btn btn-warning btn-sm font-weight-bold shadow-sm" style="border-radius:8px;">
                <i class="fa fa-file-signature mr-1"></i> Ir a Firmar y Cerrar
            </a>
        </div>
        @endif

        @if($evaluacion->aspectos_positivos || $evaluacion->observaciones_generales)
        <div class="row mb-4">
            @if($evaluacion->aspectos_positivos)
            <div class="col-md-{{ $evaluacion->observaciones_generales ? '6' : '12' }}">
                <div class="card border-success shadow-sm">
                    <div class="card-header bg-success text-white py-2">
                        <h6 class="mb-0 font-weight-bold text-white" style="font-size:0.88rem"><i class="fa fa-star mr-1"></i>Aspectos Positivos Observados</h6>
                    </div>
                    <div class="card-body py-3" style="font-size:.9rem;line-height:1.6">
                        {!! $evaluacion->aspectos_positivos !!}
                    </div>
                </div>
            </div>
            @endif
            @if($evaluacion->observaciones_generales)
            <div class="col-md-{{ $evaluacion->aspectos_positivos ? '6' : '12' }}">
                <div class="card border-info shadow-sm">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 font-weight-bold text-dark" style="font-size:0.88rem"><i class="fa fa-comment-dots mr-1 text-info"></i>Sugerencias u Observaciones Generales</h6>
                    </div>
                    <div class="card-body py-3" style="font-size:.9rem;line-height:1.6">
                        {!! $evaluacion->observaciones_generales !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4" id="evalTabs" style="border-bottom:2px solid #dee2e6">
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabGap">
                    <i class="fa fa-chart-bar mr-1"></i>Cartera de Servicios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabHabilitacion">
                    <i class="fa fa-building mr-1"></i>Condiciones Habilitantes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabEspecialidades">
                    <i class="fa fa-stethoscope mr-1 text-primary"></i>Especialidades Médicas
                    <span class="badge badge-pill badge-primary ml-1" id="badgeEspecialidadesTab">{{ $est->especialidades->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabRespuestas">
                    <i class="fa fa-list mr-1"></i>Respuestas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabAcciones">
                    <i class="fa fa-exclamation-triangle mr-1 text-danger"></i>Acciones Críticas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active text-dark" data-toggle="tab" href="#tabMatriz">
                    <i class="fa fa-th mr-1"></i>Matriz de Servicios
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- Tab Especialidades Médicas --}}
            <div class="tab-pane fade" id="tabEspecialidades">
                <div class="card shadow-sm border-0" style="border-radius:12px;">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-2 mr-3 text-primary d-flex align-items-center justify-content-center" style="width:38px; height:38px;">
                                <i class="fa fa-stethoscope fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-0" style="font-size:1rem;">
                                    Especialidades Médicas Registradas
                                </h6>
                                <small class="text-muted">Servicios y prestaciones de especialidades médicas activas en {{ $est->nombre_oficial }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                            <input type="text" id="filtroEspecialidadesShow" class="form-control form-control-sm" placeholder="🔍 Filtrar especialidad..." style="max-width:220px; border-radius:8px;">
                            <span class="badge badge-primary px-3 py-2 font-weight-bold" style="font-size:0.8rem; border-radius:8px;">
                                <i class="fa fa-user-md mr-1"></i> Total: {{ $est->especialidades->count() }} Especialidades
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if($est->especialidades->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fa fa-user-md fa-3x mb-3 text-secondary" style="opacity: 0.35;"></i>
                                <h6 class="font-weight-bold mb-1">Sin especialidades registradas</h6>
                                <small>Este establecimiento no posee especialidades médicas registradas actualmente.</small>
                            </div>
                        @else
                            <div class="row" id="gridEspecialidadesShow">
                                @foreach($est->especialidades->sortBy('nombre') as $esp)
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3 item-especialidad-show" data-nombre="{{ strtolower($esp->nombre) }}">
                                        <div class="p-3 rounded border bg-light h-100 d-flex align-items-center shadow-xs" style="border-radius:10px; transition: all 0.2s;">
                                            <div class="rounded-circle bg-white text-primary p-2 mr-3 shadow-xs d-flex align-items-center justify-content-center" style="width:36px; height:36px; flex-shrink:0;">
                                                <i class="fa fa-stethoscope"></i>
                                            </div>
                                            <div style="min-width:0;">
                                                <div class="font-weight-bold text-dark small text-truncate" title="{{ $esp->nombre }}">{{ $esp->nombre }}</div>
                                                <small class="text-success" style="font-size:0.72rem;"><i class="fa fa-check-circle mr-1"></i>Activa en centro</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tab Gap Cartera --}}
            <div class="tab-pane fade" id="tabGap">
                <div id="gapContenido">
                    <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>

            {{-- Tab Habilitación --}}
            <div class="tab-pane fade" id="tabHabilitacion">
                <div id="habilitacionContenido">
                    <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>

            {{-- Tab Respuestas --}}
            <div class="tab-pane fade" id="tabRespuestas">
                @forelse($evaluacion->respuestas as $resp)
                <div class="d-flex align-items-start border-bottom py-2">
                    <div class="flex-grow-1">
                        <small class="text-muted">{{ $resp->pregunta->pregunta ?? '—' }}</small>
                        <div class="font-weight-bold">{{ $resp->respuesta }}</div>
                    </div>
                    <span class="badge badge-{{ $resp->estado_cumplimiento === 'cumple' ? 'success' : ($resp->estado_cumplimiento === 'no_cumple' ? 'danger' : 'secondary') }} ml-3">
                        {{ $resp->estado_cumplimiento }}
                    </span>
                </div>
                @empty
                <p class="text-muted text-center py-4">Sin respuestas registradas</p>
                @endforelse
            </div>

            {{-- Tab Acciones --}}
            <div class="tab-pane fade" id="tabAcciones">
                <div id="accionesContenido">
                    <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>

            {{-- Tab Matriz de Servicios --}}
            <div class="tab-pane fade show active" id="tabMatriz">
            @php
                use App\Models\Riiss\CarteraServicio;
                use App\Models\Riiss\ComplejidadTipo;

                // Mapeo dinámico: grado → columna aplica_* de CarteraServicio
                $mapGradoColumna = [
                    1 => 'aplica_puesto_sanitario',
                    2 => 'aplica_clinica_periferica',
                    3 => 'aplica_hospital_baja',
                    4 => 'aplica_hospital_mediana',
                    5 => 'aplica_hospital_alta',
                    6 => 'aplica_hospital_alta',
                ];

                $tiposComplejidad = ComplejidadTipo::activos()->get();

                $columnas = $tiposComplejidad->map(function($tipo) use ($mapGradoColumna) {
                    return [
                        'key'        => $mapGradoColumna[$tipo->grado] ?? null,
                        'label'      => $tipo->nombre,
                        'tipo_label' => $tipo->tipo_establecimiento,
                        'nivel'      => $tipo->nivel_atencion,
                        'grado'      => $tipo->grado,
                        'color'      => $tipo->color ?? '#1a237e',
                    ];
                })->filter(fn($c) => $c['key'] !== null)->values();

                $servicios = CarteraServicio::orderBy('tipo_prestacion')->orderBy('grupo_servicio')->orderBy('servicio')->get();
                $porTipo   = $servicios->groupBy('tipo_prestacion');

                // Columna del establecimiento evaluado — basada en complejidad_tipo_id
                $complejidadTipoEst = $evaluacion->establecimiento->complejidadTipo;
                $gradoEst   = $complejidadTipoEst?->grado ?? 0;
                $colActual  = $mapGradoColumna[$gradoEst] ?? null;

                // Gap analysis
                $gapItems = $evaluacion->gapAnalysis()->where('dimension','cartera_servicios')->get()->keyBy('servicio_nombre');

                $nombreNivel = $complejidadTipoEst?->nombre ?? 'Sin clasificación';
                $tipoLabel   = $complejidadTipoEst?->tipo_establecimiento ?? '';
            @endphp

            {{-- Encabezado del nivel --}}
            <div class="d-flex align-items-center mb-3 p-3 rounded"
                 style="background:linear-gradient(135deg,#1a237e,#283593);color:#fff">
                <div style="flex:1">
                    <div style="font-size:.65rem;opacity:.65;text-transform:uppercase;letter-spacing:.07em">Nivel evaluado</div>
                    <div style="font-size:1rem;font-weight:700">{{ $nombreNivel }}</div>
                    @if($tipoLabel)<div style="font-size:.75rem;opacity:.75">{{ $tipoLabel }}</div>@endif
                </div>
                <div class="text-right">
                    <div style="font-size:.65rem;opacity:.65;text-transform:uppercase">Total servicios</div>
                    <div style="font-size:1.5rem;font-weight:800">{{ $servicios->count() }}</div>
                </div>
            </div>

            {{-- Selector de columnas --}}
            <div class="mb-2 p-2 rounded d-flex flex-wrap align-items-center"
                 style="background:#f8faff;border:1px solid #e2e8f0;gap:.5rem">
                <span style="font-size:.72rem;font-weight:600;color:#475569;margin-right:.25rem">
                    <i class="fa fa-columns mr-1"></i>Columnas visibles:
                </span>
                @foreach($columnas as $col)
                @php $esActual = $col['key'] === $colActual; @endphp
                <label class="mb-0 d-flex align-items-center"
                       style="gap:.3rem;cursor:pointer;font-size:.75rem;
                              background:{{ $esActual ? '#e8eaf6' : '#fff' }};
                              border:1px solid {{ $esActual ? '#9fa8da' : '#e2e8f0' }};
                              border-radius:20px;padding:.2rem .6rem;
                              font-weight:{{ $esActual ? '600' : '400' }};
                              color:{{ $esActual ? '#1a237e' : '#64748b' }}">
                    <input type="checkbox"
                           class="col-toggle"
                           data-col="col-grado-{{ $col['grado'] }}"
                           @if($esActual) checked @endif
                           style="cursor:pointer">
                    Grado {{ $col['grado'] }} · {{ $col['tipo_label'] }}
                    @if($esActual)<i class="fa fa-star ml-1" style="font-size:.6rem;color:#7986cb"></i>@endif
                </label>
                @endforeach
            </div>

            {{-- Filtros de filas + buscador --}}
            <div class="mb-2 d-flex flex-wrap align-items-center" style="gap:.4rem">
                <span style="font-size:.72rem;font-weight:600;color:#475569">
                    <i class="fa fa-filter mr-1"></i>Filtrar:
                </span>
                <button class="btn-filtro-matriz active" data-filtro="requeridos">
                    <i class="fa fa-star" style="font-size:.6rem"></i> Requeridos
                </button>
                <button class="btn-filtro-matriz" data-filtro="todos">
                    <i class="fa fa-list" style="font-size:.6rem"></i> Todos
                </button>
                <button class="btn-filtro-matriz" data-filtro="opcionales">
                    <i class="fa fa-circle" style="font-size:.6rem"></i> Opcionales
                </button>
                <button class="btn-filtro-matriz" data-filtro="cumple">
                    <i class="fa fa-check" style="font-size:.6rem;color:#15803d"></i> Cumple
                </button>
                <button class="btn-filtro-matriz" data-filtro="no_cumple">
                    <i class="fa fa-times" style="font-size:.6rem;color:#b91c1c"></i> No cumple
                </button>
                <button class="btn-filtro-matriz" data-filtro="no_verificable">
                    <i class="fa fa-question" style="font-size:.6rem;color:#c2410c"></i> No verificado
                </button>                <div style="margin-left:auto">
                    <div style="position:relative">
                        <i class="fa fa-search" style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:.65rem;color:#94a3b8"></i>
                        <input type="text" id="buscadorMatriz" placeholder="Buscar servicio…"
                               style="padding:.3rem .5rem .3rem 1.6rem;border:1px solid #e2e8f0;border-radius:20px;font-size:.75rem;width:180px;outline:none;color:#334155">
                    </div>
                </div>
            </div>

            <style>
            .btn-filtro-matriz{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .65rem;border-radius:20px;font-size:.72rem;font-weight:600;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;transition:all .15s}
            .btn-filtro-matriz:hover{border-color:#94a3b8;color:#334155}
            .btn-filtro-matriz.active{background:#1a237e;color:#fff;border-color:#1a237e}
            #matrizTable tbody tr.fila-oculta{display:none}
            </style>



            {{-- Tabla principal --}}
            <div class="table-responsive" style="max-height:62vh;overflow-y:auto;border:1px solid #e2e8f0;border-radius:.5rem">
            <table class="table table-bordered table-sm mb-0" id="matrizTable"
                   style="font-size:.78rem;border-collapse:collapse;color:#212529">
                <thead style="position:sticky;top:0;z-index:10">
                    {{-- Fila 1: nombre del nivel --}}
                    <tr style="background:#1a237e;color:#fff;text-align:center">
                        <th rowspan="3" style="text-align:left;min-width:120px;vertical-align:middle;background:#1a237e;border-color:#283593">Tipo de Prestación</th>
                        <th rowspan="3" style="text-align:left;min-width:220px;vertical-align:middle;background:#1a237e;border-color:#283593">Servicio</th>
                        @foreach($columnas as $col)
                        @php $esActual = $col['key'] === $colActual; @endphp
                        <th @if($esActual) colspan="2" @endif
                            class="col-grado-{{ $col['grado'] }}"
                            style="font-size:.68rem;font-weight:700;padding:.4rem .3rem;min-width:{{ $esActual ? '160px' : '70px' }};
                                   background:{{ $esActual ? '#1565c0' : '#283593' }};
                                   border-color:{{ $esActual ? '#1565c0' : '#3949ab' }};
                                   {{ $esActual ? 'border-left:3px solid #90caf9;border-right:3px solid #90caf9' : '' }}">
                            {{ $col['label'] }}
                        </th>
                        @endforeach
                    </tr>
                    {{-- Fila 2: tipo establecimiento --}}
                    <tr style="background:#283593;color:#fff;text-align:center">
                        @foreach($columnas as $col)
                        @php $esActual = $col['key'] === $colActual; @endphp
                        <th @if($esActual) colspan="2" @endif
                            class="col-grado-{{ $col['grado'] }}"
                            style="font-size:.62rem;font-weight:400;padding:.25rem;
                                   background:{{ $esActual ? '#1565c0' : '#283593' }};
                                   border-color:{{ $esActual ? '#1565c0' : '#3949ab' }}">
                            {{ $col['tipo_label'] }}
                            @if($esActual)<span style="display:block;font-size:.58rem;opacity:.8">★ Este establecimiento</span>@endif
                        </th>
                        @endforeach
                    </tr>
                    {{-- Fila 3: Aplica para otras / Cumple+No cumple para la actual --}}
                    <tr style="background:#3949ab;color:#fff;text-align:center">
                        @foreach($columnas as $col)
                        @php $esActual = $col['key'] === $colActual; @endphp
                        @if($esActual)
                        <th class="col-grado-{{ $col['grado'] }}" style="width:80px;font-size:.65rem;font-weight:700;background:#1b5e20;border-color:#2e7d32">
                            <i class="fa fa-check mr-1" style="font-size:.6rem"></i>Cumple
                        </th>
                        <th class="col-grado-{{ $col['grado'] }}" style="width:80px;font-size:.65rem;font-weight:700;background:#b71c1c;border-color:#c62828">
                            <i class="fa fa-times mr-1" style="font-size:.6rem"></i>No cumple
                        </th>
                        @else
                        <th class="col-grado-{{ $col['grado'] }}" style="font-size:.6rem;font-weight:400;background:#3949ab;border-color:#3949ab">
                            Aplica
                        </th>
                        @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @foreach($servicios->groupBy('tipo_prestacion') as $tipo => $items)
                    @foreach($items as $i => $srv)
                    @php
                        $gap           = $gapItems->get($srv->servicio);
                        $aplicaAlNivel = $colActual ? (bool)($srv->{$colActual} ?? false) : false;
                        $rowBg = $gap
                            ? ($gap->estado === 'cumple'    ? '#f0fdf4'
                            : ($gap->estado === 'no_cumple' ? '#fef2f2' : ''))
                            : '';
                    @endphp
                    <tr style="{{ $rowBg ? 'background:'.$rowBg : '' }}"
                        data-req="{{ $srv->requerido ? '1' : '0' }}"
                        data-eval="{{ $gap?->estado ?? '' }}"
                        data-nombre="{{ strtolower($srv->servicio) }}"
                        data-aplica="{{ $aplicaAlNivel ? '1' : '0' }}">
                        <td style="font-weight:{{ $i === 0 ? '700' : '400' }};font-size:.72rem;
                                   color:{{ $i === 0 ? '#1a237e' : 'transparent' }};
                                   vertical-align:middle;background:#e8eaf6;
                                   border-right:3px solid #9fa8da;white-space:nowrap;
                                   {{ $i === 0 ? 'border-top:2px solid #c5cae9' : 'border-top:1px solid #e8eaf6' }};
                                   user-select:none">
                            {{ $i === 0 ? $tipo : '&nbsp;' }}
                        </td>
                        <td style="vertical-align:middle">
                            {{ $srv->servicio }}
                            @if($srv->requerido)
                            <span style="font-size:.58rem;background:#e2e8f0;color:#475569;border-radius:3px;padding:1px 4px;margin-left:3px">req.</span>
                            @endif
                        </td>
                        @foreach($columnas as $col)
                        @php $aplica = (bool)($srv->{$col['key']} ?? false); @endphp
                        @if($col['key'] === $colActual)
                            {{-- Celda Cumple --}}
                            @if($gap && $gap->estado === 'no_verificable')
                            <td colspan="2" class="col-grado-{{ $col['grado'] }}"
                                style="text-align:center;vertical-align:middle;background:#fffbeb">
                                <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.68rem;color:#92400e;font-weight:600">
                                    <i class="fa fa-clock" style="color:#d97706"></i> Pendiente de verificar
                                </span>
                            </td>
                            @else
                            <td class="col-grado-{{ $col['grado'] }}" style="text-align:center;vertical-align:middle;background:#f0fdf4">
                                @if($gap && $gap->estado === 'cumple')
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:#dcfce7">
                                    <i class="fa fa-check" style="font-size:.6rem;color:#15803d"></i>
                                </span>
                                @elseif(!$aplica)
                                <span style="font-size:.65rem;color:#cbd5e1">—</span>
                                @endif
                            </td>
                            {{-- Celda No cumple --}}
                            <td class="col-grado-{{ $col['grado'] }}" style="text-align:center;vertical-align:middle;background:#fff5f5">
                                @if($gap && $gap->estado === 'no_cumple')
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:#fee2e2">
                                    <i class="fa fa-times" style="font-size:.6rem;color:#b91c1c"></i>
                                </span>
                                @elseif(!$aplica)
                                <span style="font-size:.65rem;color:#cbd5e1">—</span>
                                @endif
                            </td>
                            @endif
                        @else
                            {{-- Otras columnas: solo aplica o no --}}
                            <td class="col-grado-{{ $col['grado'] }}" style="text-align:center;vertical-align:middle">
                                @if($aplica)
                                <i class="fa fa-check" style="color:#15803d"></i>
                                @else
                                <i class="fa fa-times" style="color:#dc2626"></i>
                                @endif
                            </td>
                        @endif
                        @endforeach
                    </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            </div>

            <div class="mt-2 px-1 d-flex flex-wrap" style="gap:.75rem;font-size:.72rem;color:#64748b">
                <span><i class="fa fa-check" style="color:#15803d"></i> Aplica / Cumple</span>
                <span><i class="fa fa-times" style="color:#b91c1c"></i> No aplica / No cumple</span>
                <span><i class="fa fa-clock" style="color:#d97706"></i> Pendiente de verificar</span>
                <span style="background:#e2e8f0;padding:1px 5px;border-radius:3px">req.</span> = requerido para este nivel
                <span style="background:#e8eaf6;padding:1px 5px;border-radius:3px">★ Este est.</span> = columna del establecimiento evaluado
            </div>



        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('riiss.evaluaciones.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left mr-1"></i>Volver
            </a>
            @if(in_array($evaluacion->estado, ['borrador','en_progreso']))
            <a href="{{ route('riiss.evaluaciones.nueva', $evaluacion->id_establecimiento) }}" class="btn btn-warning">
                <i class="fa fa-edit mr-1"></i>Continuar evaluación
            </a>
            @endif
            <button class="btn btn-danger" onclick="reejecutarGap()">
                <i class="fa fa-sync mr-1"></i>Re-ejecutar análisis
            </button>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
const EVAL_ID = {{ $evaluacion->id }};

$(document).ready(function() {
    cargarGap();
});

function cargarGap() {
    $.get(`/riiss/evaluaciones/${EVAL_ID}/gap`, function(r) {
        if (!r.ok) return;
        renderResumenGap(r.data);
        renderGapPorGrupo(r.data.cartera, 'gapContenido', 'cartera-');
        renderGapPorGrupo(r.data.habilitacion, 'habilitacionContenido', 'hab-');
        renderAcciones(r.data);
    });
}

function renderResumenGap(data) {
    const c = data.cartera;
    const h = data.habilitacion;
    const badgeFinal = {
        'APTO_HABILITACION': 'success',
        'OBSERVADO':         'warning',
        'NO_APTO':           'danger',
    }[data.clasificacion_final] ?? 'secondary';

    $('#resumenGap').html(`
        <h6 class="font-weight-bold mb-2">Resultado</h6>
        <div class="text-center mb-3">
            <span class="badge badge-${badgeFinal}" style="font-size:.85rem;padding:6px 12px">
                ${(data.clasificacion_final ?? '—').replace(/_/g,' ')}
            </span>
        </div>
        <div class="row text-center">
            <div class="col-6 mb-2 border-right">
                <div class="small text-muted font-weight-bold mb-1">Cartera Servicios</div>
                <div class="h4 font-weight-bold text-${c?.porcentaje >= 90 ? 'success' : c?.porcentaje >= 70 ? 'warning' : 'danger'}">${c?.porcentaje ?? 0}%</div>
                <small class="text-muted">✅ ${c?.resumen?.cumple ?? 0} / ❌ ${c?.resumen?.no_cumple ?? 0} / ⏳ ${c?.resumen?.pendiente ?? 0} / ⚠️ ${c?.resumen?.no_verificable ?? 0}</small>
            </div>
            <div class="col-6 mb-2">
                <div class="small text-muted font-weight-bold mb-1">Habilitación</div>
                <div class="h4 font-weight-bold text-${h?.porcentaje >= 90 ? 'success' : h?.porcentaje >= 70 ? 'warning' : 'danger'}">${h?.porcentaje ?? 0}%</div>
                <small class="text-muted">✅ ${h?.resumen?.cumple ?? 0} / ❌ ${h?.resumen?.no_cumple ?? 0}</small>
            </div>
        </div>
    `);
}

function renderGapPorGrupo(dim, containerId, prefix) {
    if (!dim?.por_grupo?.length) {
        $(`#${containerId}`).html('<p class="text-muted text-center py-4">Sin datos. Ejecute el análisis primero.</p>');
        return;
    }

    let html = '';
    dim.por_grupo.forEach((grupo, i) => {
        const key = prefix + i;
        const pct = grupo.total > 0 ? Math.round((grupo.cumple / grupo.total) * 100) : 0;
        const color = pct >= 90 ? '#22c55e' : pct >= 70 ? '#f97316' : '#ef4444';

        html += `<div class="mb-3">
            <div class="grupo-header d-flex align-items-center" onclick="toggleGrupo('${key}')">
                <strong class="flex-grow-1">${grupo.grupo ?? 'Sin grupo'}</strong>
                <div style="width:80px;margin-right:12px">
                    <div style="height:6px;background:#e5e7eb;border-radius:3px;overflow:hidden">
                        <div style="height:100%;width:${pct}%;background:${color};border-radius:3px"></div>
                    </div>
                    <small style="color:${color};font-weight:600">${pct}%</small>
                </div>
                <span class="badge badge-success mr-1">${grupo.cumple}</span>
                <span class="badge badge-danger mr-1">${grupo.no_cumple}</span>
                <span class="badge badge-warning">${grupo.no_verificable}</span>
                <i class="fa fa-chevron-down ml-2 text-muted" id="chevron-${key}"></i>
            </div>
            <div id="grupo-items-${key}" style="display:none;padding-left:12px">
                ${grupo.items.map(item => `
                    <div class="gap-item gap-${item.estado}">
                        <div class="d-flex align-items-start">
                            <span style="font-size:1.1rem;margin-right:8px">${item.icono}</span>
                            <div class="flex-grow-1">
                                <strong class="small">${item.servicio}</strong>
                                ${item.accion ? `<p class="text-muted small mb-0 mt-1">${item.accion}</p>` : ''}
                            </div>
                            <span class="badge badge-${item.prioridad >= 2 ? 'danger' : item.prioridad === 1 ? 'warning' : 'secondary'} ml-2">
                                ${item.prioridad >= 2 ? 'Crítico' : item.prioridad === 1 ? 'Alto' : 'Normal'}
                            </span>
                        </div>
                    </div>`).join('')}
            </div>
        </div>`;
    });

    $(`#${containerId}`).html(html);
}

function renderAcciones(data) {
    const criticos = [
        ...(data.cartera?.acciones_criticas ?? []),
        ...(data.habilitacion?.acciones_criticas ?? [])
    ];

    if (!criticos.length) {
        $('#accionesContenido').html('<p class="text-muted text-center py-4">No hay acciones críticas pendientes. ✅</p>');
        return;
    }

    let html = '<div class="alert alert-danger mb-3"><i class="fa fa-exclamation-triangle mr-2"></i>Se requieren acciones inmediatas:</div>';
    criticos.forEach(a => {
        html += `<div class="gap-item gap-no_cumple mb-3">
            <strong>${a.servicio}</strong>
            <span class="badge badge-secondary ml-2">${a.grupo ?? ''}</span>
            <p class="text-muted small mt-2 mb-0">${a.accion ?? ''}</p>
        </div>`;
    });

    $('#accionesContenido').html(html);
}

function toggleGrupo(key) {
    const $items = $(`#grupo-items-${key}`);
    const $chev  = $(`#chevron-${key}`);
    $items.slideToggle(200);
    $chev.toggleClass('fa-chevron-down fa-chevron-up');
}

function reejecutarGap() {
    Swal.fire({
        title: '¿Re-ejecutar análisis?',
        text: 'Esto sobreescribirá el análisis anterior.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53e3e',
        cancelButtonColor: '#718096',
        confirmButtonText: 'Sí, re-ejecutar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (!result.isConfirmed) return;
        $.ajax({
            url: `/riiss/evaluaciones/${EVAL_ID}/ejecutar-gap`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(r) {
                if (r.ok) { cargarGap(); mostrarToast('Análisis re-ejecutado', 'success'); }
            }
        });
    });
}

// ── Matriz: filtros + buscador + col-toggle ─────────────────────────────
$(document).ready(function(){
    var filtroActual = 'requeridos';
    var busqueda = '';

    function aplicarFiltro(){
        document.querySelectorAll('#matrizTable tbody tr').forEach(function(tr){
            var req    = tr.dataset.req    === '1';
            var estado = tr.dataset.eval   || '';
            var aplica = tr.dataset.aplica === '1';
            var nom    = (tr.dataset.nombre || '').toLowerCase();
            var pasaBusqueda = busqueda === '' || nom.indexOf(busqueda) !== -1;
            var pasaFiltro;
            if(filtroActual === 'requeridos')          pasaFiltro = req && aplica;
            else if(filtroActual === 'opcionales')     pasaFiltro = !req && aplica;
            else if(filtroActual === 'cumple')         pasaFiltro = estado === 'cumple';
            else if(filtroActual === 'no_cumple')      pasaFiltro = estado === 'no_cumple';
            else if(filtroActual === 'no_verificable') pasaFiltro = estado === 'no_verificable';
            else pasaFiltro = true;
            tr.classList.toggle('fila-oculta', !(pasaFiltro && pasaBusqueda));
        });
    }

    document.querySelectorAll('.btn-filtro-matriz').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.btn-filtro-matriz').forEach(function(b){ b.classList.remove('active'); });
            this.classList.add('active');
            filtroActual = this.dataset.filtro;
            aplicarFiltro();
        });
    });

    var buscador = document.getElementById('buscadorMatriz');
    if(buscador) buscador.addEventListener('input', function(){
        busqueda = this.value.toLowerCase().trim();
        aplicarFiltro();
    });

    // col-toggle: oculta/muestra columnas por grado
    document.querySelectorAll('.col-toggle').forEach(function(cb){
        if(!cb.checked){
            document.querySelectorAll('.' + cb.dataset.col).forEach(function(el){
                el.style.display = 'none';
            });
        }
        cb.addEventListener('change', function(){
            var cls = this.dataset.col; // ej: col-grado-1
            var show = this.checked;
            document.querySelectorAll('.' + cls).forEach(function(el){
                el.style.display = show ? '' : 'none';
            });
        });
    });

    aplicarFiltro();

    // ── Filtro rápido de especialidades médicas ─────────────────────────────
    $('#filtroEspecialidadesShow').on('keyup input', function() {
        var query = $(this).val().toLowerCase().trim();
        $('#gridEspecialidadesShow .item-especialidad-show').each(function() {
            var nom = $(this).data('nombre') || '';
            $(this).toggle(nom.indexOf(query) >= 0);
        });
    });
});

function mostrarToast(msg, tipo) {
    const color = tipo === 'success' ? '#22c55e' : '#ef4444';
    const toast = $(`<div style="position:fixed;bottom:24px;right:24px;background:${color};color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.2)">${msg}</div>`);
    $('body').append(toast);
    setTimeout(() => toast.fadeOut(400, () => toast.remove()), 3000);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var map = null;
var marker = null;
var padModalEval = null;

function abrirModalDetalles() {
    $('#modalDetallesEst').modal('show');
    
    // Inicializar o refrescar el mapa al abrir el modal (setTimeout para asegurar que el DOM esté visible)
    setTimeout(function() {
        var lat = {{ $est->latitude ?? 'null' }};
        var lng = {{ $est->longitude ?? 'null' }};
        
        if (lat !== null && lng !== null) {
            if (!map) {
                map = L.map('mapaEstablecimiento').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                marker = L.marker([lat, lng]).addTo(map);
            } else {
                map.invalidateSize();
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
            }
        } else {
            $('#mapaEstablecimiento').html('<div class="alert alert-warning m-3 text-center">No hay coordenadas registradas para este establecimiento.</div>');
        }
    }, 300);
}

function abrirModalFirmarEvaluador() {
    $('#modalFirmarEvaluador').modal('show');
    
    setTimeout(function() {
        var canvas = document.getElementById('canvasFirmaModalEval');
        if (canvas) {
            if (!padModalEval) {
                padModalEval = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(15, 23, 42)'
                });
            }
            var r = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * r;
            canvas.height = canvas.offsetHeight * r;
            canvas.getContext("2d").scale(r, r);
            padModalEval.clear();
        }
    }, 350);
}

$(document).on('click', '#btnClearFirmaModalEval', function() {
    if (padModalEval) padModalEval.clear();
});

function guardarFirmaEvaluadorModal() {
    if (!padModalEval || padModalEval.isEmpty()) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Firma requerida', text: 'Por favor, estampe su firma en el recuadro digital.' });
        } else {
            alert('Por favor, estampe su firma en el recuadro digital.');
        }
        return;
    }

    var nombre = $('#modal_eval_nombre').val().trim();
    if (!nombre) {
        mostrarToast('Indique el nombre del evaluador', 'error');
        return;
    }

    var cargo = $('#modal_eval_cargo').val().trim() || 'Evaluador / Analista RIISS — Dirección de Planificación';
    var firmaBase64 = padModalEval.toDataURL('image/png');
    var selectedOption = $('#modal_eval_nombre_select option:selected');
    var userId = selectedOption.data('id') || {{ auth()->id() ?? 'null' }};

    var $btn = $('#btnGuardarFirmaModalEval');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

    $.ajax({
        url: '{{ route("riiss.evaluaciones.firmar-evaluador", $evaluacion->id) }}',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: '{{ csrf_token() }}',
            evaluador_nombre: nombre,
            evaluador_cargo: cargo,
            evaluador_user_id: userId,
            evaluador_firma: firmaBase64
        }),
        success: function(r) {
            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Registrar Firma');
            $('#modalFirmarEvaluador').modal('hide');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Firma Registrada!',
                    text: 'La rúbrica del evaluador ha sido agregada exitosamente al Acta.',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                mostrarToast('Firma registrada exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Registrar Firma');
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al registrar la firma';
            mostrarToast(msg, 'error');
        }
    });
}
</script>

<!-- Modal Firmar Evaluador Pendiente -->
<div class="modal fade" id="modalFirmarEvaluador" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 540px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-info mr-2 d-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fa fa-pen-nib" style="color:#0284c7; font-size:1rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size:1.05rem;">
                            Estampar Firma Digital de Evaluador
                        </h5>
                        <small class="text-white-50" style="font-size:0.75rem;">Equipo de la Dirección de Planificación — IPS</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity:0.9; text-shadow:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background:#f8fafc;">
                <form id="formFirmaEvaluadorPendiente" onsubmit="return false;">
                    @if(!empty($evaluadoresComision) && count($evaluadoresComision) > 0)
                        <div class="form-group mb-3">
                            <label class="form-label-custom font-weight-bold text-dark small mb-1">
                                <i class="fa fa-users text-primary mr-1"></i>Seleccionar Técnico de la Comisión:
                            </label>
                            <select class="form-control form-control-custom" id="modal_eval_nombre_select" onchange="if(this.value){ $('#modal_eval_nombre').val(this.value); }">
                                <option value="">— Elegir de la comisión comisionada —</option>
                                @foreach($evaluadoresComision as $ec)
                                    <option value="{{ $ec['text'] ?? $ec['nombre'] ?? '' }}" data-id="{{ $ec['id'] ?? '' }}">{{ $ec['text'] ?? $ec['nombre'] ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label class="form-label-custom font-weight-bold text-dark small mb-1">
                            <i class="fa fa-user-check text-info mr-1"></i>Nombre y Apellido del Evaluador <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-custom" id="modal_eval_nombre" placeholder="Nombre completo del evaluador..." value="{{ auth()->user() ? auth()->user()->name : '' }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-custom font-weight-bold text-dark small mb-1">
                            <i class="fa fa-briefcase text-secondary mr-1"></i>Cargo / Dependencia
                        </label>
                        <input type="text" class="form-control form-control-custom" id="modal_eval_cargo" value="Evaluador / Analista RIISS — Dirección de Planificación" required>
                    </div>

                    <div class="form-group mb-0">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label-custom font-weight-bold text-dark small mb-0">
                                <i class="fa fa-signature text-primary mr-1"></i>Rúbrica Digital <span class="text-danger">*</span>
                            </label>
                            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" id="btnClearFirmaModalEval" style="font-size:0.72rem; border-radius:6px;">
                                <i class="fa fa-eraser mr-1"></i>Limpiar
                            </button>
                        </div>
                        <div class="border rounded bg-white d-flex align-items-center justify-content-center p-1" style="border: 2px dashed #94a3b8 !important; border-radius: 10px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                            <canvas id="canvasFirmaModalEval" width="480" height="150" style="touch-action: none; width: 100%; height: 150px; background: #ffffff; border-radius: 8px; cursor: crosshair;"></canvas>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size:0.72rem;"><i class="fa fa-info-circle mr-1"></i>Dibujar la firma con el cursor del mouse o directamente en pantalla táctil.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius:8px;">
                    Cancelar
                </button>
                <button type="button" class="btn btn-success font-weight-bold btn-sm px-4 shadow-sm" id="btnGuardarFirmaModalEval" onclick="guardarFirmaEvaluadorModal()" style="border-radius:8px;">
                    <i class="fa fa-save mr-1"></i> Registrar Firma
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles Establecimiento -->
<div class="modal fade" id="modalDetallesEst" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-info">
                <h5 class="modal-title text-white">Detalles del Establecimiento</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div class="row m-0">
                    <div class="col-md-6 p-4 border-right">
                        <h6 class="font-weight-bold mb-3">Información General</h6>
                        <ul class="list-unstyled small mb-4">
                            <li><strong>Nombre:</strong> {{ $est->nombre_oficial }}</li>
                            <li><strong>Tipología:</strong> {{ $est->tipologia_clasificacion }}</li>
                            <li><strong>Condición:</strong> <span class="badge badge-info">{{ $est->condicion_inmueble ?? 'NO ESPECIFICADA' }}</span></li>
                            @if($est->superficie_terreno)
                                <li><strong>Sup. Terreno:</strong> {{ $est->superficie_terreno }} m²</li>
                            @endif
                            @if($est->superficie_construida)
                                <li><strong>Sup. Construida:</strong> {{ $est->superficie_construida }} m²</li>
                            @endif
                        </ul>
                        
                        @if($est->condicion_inmueble == 'ALQUILADO')
                        <h6 class="font-weight-bold mb-2 border-bottom pb-1">Datos de Alquiler</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Propietario:</strong> {{ $est->propietario }}</li>
                            <li><strong>Contrato:</strong> {{ $est->nro_contrato_alquiler }}</li>
                            <li><strong>Canon:</strong> {{ $est->canon_mensual ? '$'.number_format($est->canon_mensual, 2) : '-' }}</li>
                        </ul>
                        @endif

                        @if($est->condicion_inmueble == 'CONVENIO')
                        <h6 class="font-weight-bold mb-2 border-bottom pb-1">Datos de Convenio</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Nro Resolución:</strong> {{ $est->nro_resolucion_convenio }}</li>
                            <li><strong>Vigencia:</strong> {{ $est->vigencia_convenio_desde }} al {{ $est->vigencia_convenio_hasta }}</li>
                            <li><strong>Descripción:</strong> {{ $est->descripcion_convenio }}</li>
                        </ul>
                        @endif
                        
                        @if($est->inmuebleContratos && $est->inmuebleContratos->count() > 0)
                        <h6 class="font-weight-bold mt-3 border-bottom pb-1">Contratos Adicionales</h6>
                        <ul class="list-unstyled small">
                            @foreach($est->inmuebleContratos as $contrato)
                                <li class="mb-1">
                                    <span class="badge badge-secondary">{{ $contrato->tipo_contrato }}</span>
                                    {{ $contrato->descripcion }}
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class="col-md-6 p-0 bg-light position-relative" style="min-height: 400px;">
                        <!-- Contenedor del Mapa -->
                        <div id="mapaEstablecimiento" style="width: 100%; height: 100%; position: absolute; top:0; left:0;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
