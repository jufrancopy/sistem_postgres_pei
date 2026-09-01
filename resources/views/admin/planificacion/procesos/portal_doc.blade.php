@extends('layouts.master')
@section('title', 'Portal Investigadores DOC - Organización y Calidad')

@section('content')
<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-title font-weight-bold mb-0 text-warning">
                <i class="fas fa-microscope mr-2"></i> Portal de Investigación — Dirección de Organización y Calidad (DOC)
            </h4>
            <p class="card-category text-white-50 mb-0">Repositorio Técnico de Relevamientos de Procesos y Circuitos para Auditoría de Calidad y Estandarización</p>
        </div>
        <div>
            <a href="{{ route('pei.procesos.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fas fa-list mr-1"></i> Ir a Lista General
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-info border-left border-info shadow-sm mb-4">
            <h6 class="font-weight-bold mb-1"><i class="fas fa-info-circle mr-1"></i> Espacio de Trabajo Compartido entre Planificación y la DOC</h6>
            <p class="mb-0 small">Los relevamientos registrados por el equipo de Planificación están disponibles en este portal para que los investigadores y auditores de la Dirección de Organización y Calidad puedan revisar las evidencias de campo, estudiar los cuellos de botella e imprimir el <strong>Reporte Técnico</strong> en PDF para la confección de los manuales de procedimiento oficiales.</p>
        </div>

        <div class="row">
            @forelse($procesos as $proceso)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-top border-primary">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge badge-info text-uppercase font-weight-bold p-2">
                                    <i class="fas fa-hospital mr-1"></i> {{ $proceso->organigrama ? $proceso->organigrama->nombre : 'Servicio General' }}
                                </span>
                                <span class="badge badge-pill {{ $proceso->eficiencia >= 70 ? 'badge-success' : ($proceso->eficiencia >= 50 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $proceso->eficiencia }}% Eficiencia
                                </span>
                            </div>

                            <h5 class="font-weight-bold text-dark mb-2">{{ $proceso->nombre }}</h5>

                            <p class="text-muted small mb-3">
                                <strong><i class="fas fa-gavel mr-1"></i> Contexto:</strong> {{ Str::limit($proceso->contexto_motivo ?: 'Sin antecedentes especificados.', 90) }}
                            </p>

                            <div class="bg-light p-2 rounded mb-3 border">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Lead Time</small>
                                        <strong class="text-dark">{{ $proceso->lead_time_total }}m</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Estaciones</small>
                                        <strong class="text-primary">{{ $proceso->pasos->count() }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Cuellos</small>
                                        <strong class="text-danger">{{ $proceso->conteo_cuellos_botella }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="font-weight-bold text-dark d-block mb-1"><i class="fas fa-users mr-1"></i> Equipo de Visita:</small>
                                @forelse($proceso->responsables as $resp)
                                    <span class="badge badge-secondary mr-1 mb-1">{{ $resp->name }}</span>
                                @empty
                                    <span class="text-muted small">Sin datos</span>
                                @endforelse
                            </div>

                            <div class="mt-auto pt-3 border-top d-flex justify-content-between">
                                <a href="{{ route('pei.procesos.show', $proceso->id) }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                                    <i class="fas fa-eye mr-1"></i> Ver Flujograma
                                </a>
                                <a href="{{ route('pei.procesos.exportPdf', $proceso->id) }}" target="_blank" class="btn btn-sm btn-danger font-weight-bold">
                                    <i class="fas fa-file-pdf mr-1"></i> Reporte PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                    <h5>No hay relevamientos disponibles aún para la DOC.</h5>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
