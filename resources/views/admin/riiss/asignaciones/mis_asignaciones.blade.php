@extends('layouts.master')
@section('title', 'Mis Asignaciones')

@push('styles')
<style>
.asig-card { border-radius:12px; border:1px solid #e5e7eb; overflow:hidden; transition:box-shadow .2s; }
.asig-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.1); }
.asig-card .asig-header { padding:14px 16px 10px; }
.asig-card .asig-body { padding:0 16px 14px; }
.asig-card .asig-footer { padding:10px 16px; background:#f9fafb; border-top:1px solid #f0f0f0; }
.estado-badge { font-size:.7rem; padding:3px 9px; border-radius:20px; font-weight:600; }
.estado-pendiente   { background:#fef3c7; color:#92400e; }
.estado-en_progreso { background:#dbeafe; color:#1e40af; }
.vencida-card { border-color:#fca5a5 !important; background:#fff5f5; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title">
            <i class="fa fa-tasks mr-2"></i>Mis Asignaciones
        </h4>
        <p class="card-category">Establecimientos asignados para evaluar</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Mis Asignaciones</li>
        </ol>
    </nav>

    <div class="card-body">

        @if($asignaciones->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa fa-clipboard fa-3x mb-3 d-block" style="opacity:.3"></i>
            <p>No tenés asignaciones pendientes.</p>
            <p class="small">Cuando el administrador te asigne una evaluación, aparecerá aquí.</p>
        </div>
        @else

        <div class="alert alert-info py-2 mb-4">
            <i class="fa fa-info-circle mr-2"></i>
            Tenés <strong>{{ $asignaciones->count() }}</strong> asignación(es) pendiente(s).
            Hacé clic en <strong>"Iniciar evaluación"</strong> para comenzar.
        </div>

        <div class="row">
            @foreach($asignaciones as $a)
            @php
                $vencida = $a['vencida'] ?? false;
                $color   = $a['estado_color'] ?? '#6b7280';
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="asig-card {{ $vencida ? 'vencida-card' : '' }} h-100">
                    <div class="asig-header">
                        <div class="d-flex align-items-start justify-content-between">
                            <div style="flex:1;min-width:0">
                                <div class="font-weight-bold text-truncate" title="{{ $a['establecimiento'] }}">
                                    {{ $a['establecimiento'] }}
                                </div>
                                <small class="text-muted">{{ $a['tipologia'] }}</small>
                            </div>
                            <span class="estado-badge estado-{{ $a['estado'] }} ml-2">
                                {{ str_replace('_', ' ', $a['estado']) }}
                            </span>
                        </div>
                    </div>
                    <div class="asig-body">
                        <span class="badge mb-2" style="background:{{ $color }};color:#fff;font-size:.65rem;border-radius:20px;padding:2px 8px">
                            {{ $a['complejidad'] }}
                        </span>

                        @if($a['instrucciones'])
                        <div class="alert alert-light py-2 small mb-2">
                            <i class="fa fa-comment mr-1 text-muted"></i>{{ $a['instrucciones'] }}
                        </div>
                        @endif

                        <div class="small text-muted">
                            <i class="fa fa-user mr-1"></i>Asignado por: {{ $a['asignado_por'] }}<br>
                            <i class="fa fa-calendar mr-1"></i>Asignado el: {{ $a['created_at'] }}
                            @if($a['fecha_limite'])
                            <br><i class="fa fa-clock mr-1 {{ $vencida ? 'text-danger' : '' }}"></i>
                            <span class="{{ $vencida ? 'text-danger font-weight-bold' : '' }}">
                                Límite: {{ $a['fecha_limite'] }} {{ $vencida ? '⚠️ VENCIDA' : '' }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="asig-footer">
                        @if($a['evaluacion_id'])
                        <a href="{{ url('riiss/evaluaciones/' . $a['evaluacion_id']) }}"
                           class="btn btn-sm btn-outline-info mr-2">
                            <i class="fa fa-eye mr-1"></i>Ver evaluación
                        </a>
                        @endif
                        <a href="{{ url('riiss/evaluaciones/nueva/' . $a['id_establecimiento']) }}"
                           class="btn btn-sm btn-danger">
                            <i class="fa fa-clipboard-check mr-1"></i>
                            {{ $a['evaluacion_id'] ? 'Continuar' : 'Iniciar evaluación' }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
