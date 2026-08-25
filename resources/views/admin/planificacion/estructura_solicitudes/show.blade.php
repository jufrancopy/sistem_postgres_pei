@extends('layouts.master')

@section('title', 'Detalle de Solicitud ' . $solicitud->codigo)

@section('content')
<div class="container-fluid">

    {{-- Botón Volver y Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ route('admin.estructura-solicitudes.index') }}" class="btn btn-outline-secondary btn-sm btn-round mb-2">
                <i class="fa fa-arrow-left mr-1"></i> Volver a la Bandeja
            </a>
            <h3 class="font-weight-bold text-dark mb-0">
                Expediente: <span class="text-primary">{{ $solicitud->codigo }}</span>
            </h3>
            <p class="text-muted small mb-0">
                Radicado el {{ $solicitud->fecha_solicitud->format('d/m/Y') }} — Solicitud de Ajuste de Estructura Organizacional
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('solicitud-estructura.consulta', $solicitud->token_qr) }}" target="_blank" class="btn btn-info btn-round font-weight-bold shadow-sm mr-2">
                <i class="fa fa-qrcode mr-1"></i> Ver Comprobante Digital (QR)
            </a>
            <button onclick="window.print()" class="btn btn-dark btn-round shadow-sm">
                <i class="fa fa-print mr-1"></i> Imprimir
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        {{-- Columna Izquierda: Información de la Solicitud y Tabla de Renglones --}}
        <div class="col-lg-8">
            {{-- Card Datos Generales --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                <div class="card-header bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fa fa-info-circle text-primary mr-1"></i> Datos de Radicación y Contacto
                    </h6>
                    <span class="badge {{ \App\Models\Estructura\SolicitudAjusteEstructura::estadoBadge($solicitud->estado) }} font-weight-bold px-3 py-1" style="font-size:0.8rem;">
                        {{ \App\Models\Estructura\SolicitudAjusteEstructura::estadoLabel($solicitud->estado) }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="text-muted small font-weight-bold text-uppercase">Dependencia Solicitante</div>
                            <div class="h6 font-weight-bold text-dark mb-0">
                                <i class="fa fa-sitemap text-info mr-1"></i>
                                {{ $solicitud->dependenciaSolicitante?->dependency ?: ($solicitud->dependencia_solicitante_texto ?: 'No especificada') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small font-weight-bold text-uppercase">Funcionario Solicitante</div>
                            <div class="h6 font-weight-bold text-dark mb-0">
                                <i class="fa fa-user-tie text-secondary mr-1"></i>
                                {{ $solicitud->solicitante_nombre }}
                                @if($solicitud->solicitante_cargo)
                                    <small class="text-muted">({{ $solicitud->solicitante_cargo }})</small>
                                @endif
                            </div>
                            <small class="text-muted"><i class="fa fa-envelope mr-1"></i>{{ $solicitud->solicitante_email }} | <i class="fa fa-phone mr-1"></i>{{ $solicitud->solicitante_telefono ?: 'S/D' }}</small>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded border mb-3">
                        <div class="text-muted small font-weight-bold text-uppercase text-primary mb-1">
                            <i class="fa fa-bullseye mr-1"></i> Acción / Objetivo del Plan Estratégico (PEI) que la Respalda
                        </div>
                        <div class="font-weight-bold text-dark" style="font-size:0.95rem;">
                            {{ $solicitud->peiProfile ? strip_tags($solicitud->peiProfile->name) : 'Sin vinculación directa con el PEI' }}
                        </div>
                    </div>

                    @if($solicitud->fundamentacion_general)
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase mb-1">Fundamentación General / Justificación de Impacto</div>
                            <div class="p-3 rounded bg-white border text-dark" style="font-size:0.9rem; line-height:1.6;">
                                {{ $solicitud->fundamentacion_general }}
                            </div>
                        </div>
                    @endif

                    {{-- Documentos Adjuntos --}}
                    @if($solicitud->documento_respaldo_path || $solicitud->organigrama_adjunto_path)
                        <div class="mt-3 pt-3 border-top">
                            <div class="text-muted small font-weight-bold text-uppercase mb-2">Archivos Adjuntos de Respaldo</div>
                            <div class="d-flex flex-wrap gap-2">
                                @if($solicitud->documento_respaldo_path)
                                    <a href="{{ asset('storage/' . $solicitud->documento_respaldo_path) }}" target="_blank" class="btn btn-outline-danger btn-sm btn-round mr-2">
                                        <i class="fa fa-file-pdf mr-1"></i> Ver Nota / Dictamen Adjunto
                                    </a>
                                @endif
                                @if($solicitud->organigrama_adjunto_path)
                                    <a href="{{ asset('storage/' . $solicitud->organigrama_adjunto_path) }}" target="_blank" class="btn btn-outline-info btn-sm btn-round">
                                        <i class="fa fa-project-diagram mr-1"></i> Ver Organigrama Propuesto
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Renglones de Modificación Estructural --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden;">
                <div class="card-header bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fa fa-table text-primary mr-1"></i> Modificaciones Estructurales Solicitadas ({{ $solicitud->items->count() }})
                    </h6>
                    <span class="badge badge-primary font-weight-bold">Formato Oficial IPS</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size:0.88rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width:160px;">Tipo de Reorganización</th>
                                    <th>Denominación Actual</th>
                                    <th>Denominación Propuesta</th>
                                    <th>Objetivo y Motivos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($solicitud->items as $it)
                                    <tr>
                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-bold">{{ $it->tipo_label }}</span>
                                        </td>
                                        <td>{{ $it->denominacion_actual ?: '—' }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $it->denominacion_propuesta }}</strong>
                                            @if($it->observaciones)
                                                <br><small class="text-muted"><em>Obs: {{ $it->observaciones }}</em></small>
                                            @endif
                                        </td>
                                        <td>
                                            <div><strong>Objetivo:</strong> {{ $it->objetivo_dependencia_propuesta }}</div>
                                            <div class="text-muted mt-1"><strong>Motivos:</strong> {{ $it->descripcion_motivos }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Panel de Dictamen y Código QR --}}
        <div class="col-lg-4">
            {{-- Panel de Dictamen y Cambio de Estado --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius:12px; overflow:hidden; border-top: 4px solid #0284c7 !important;">
                <div class="card-header bg-white p-3 border-bottom">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fa fa-clipboard-check text-primary mr-1"></i> Dictamen & Resolución Técnica
                    </h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('admin.estructura-solicitudes.estado', $solicitud->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-uppercase">Estado de la Solicitud</label>
                            <select name="estado" class="form-control font-weight-bold" required>
                                @foreach($estados as $key => $lbl)
                                    <option value="{{ $key }}" {{ $solicitud->estado == $key ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-uppercase">Dictamen Técnico / Observaciones</label>
                            <textarea name="dictamen_tecnico" class="form-control" rows="5" placeholder="Fundamente el dictamen técnico de la Dirección de Planificación (cumplimiento de criterios PEI, impacto de costos, viabilidad organizativa)...">{{ old('dictamen_tecnico', $solicitud->dictamen_tecnico) }}</textarea>
                        </div>

                        @if($solicitud->analista)
                            <div class="small text-muted mb-3 p-2 bg-light rounded">
                                <i class="fa fa-user-check mr-1"></i> Último dictamen por: <strong>{{ $solicitud->analista->name }}</strong>
                                <br><i class="fa fa-clock mr-1"></i> Fecha: {{ $solicitud->fecha_dictamen?->format('d/m/Y H:i') }}
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold btn-round shadow-sm">
                            <i class="fa fa-save mr-1"></i> Guardar Dictamen y Estado
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card Código QR --}}
            <div class="card shadow-sm border-0 text-center" style="border-radius:12px; overflow:hidden;">
                <div class="card-header bg-white p-3 border-bottom">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fa fa-qrcode text-primary mr-1"></i> Código QR de Verificación
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 bg-light rounded border d-inline-block shadow-sm mb-2">
                        {!! $qrSvg !!}
                    </div>
                    <div class="font-weight-bold text-dark small mt-2">{{ $solicitud->codigo }}</div>
                    <p class="text-muted small mb-0 mt-1">
                        Escaneá para acceder a la consulta pública o compartir el comprobante digital.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
