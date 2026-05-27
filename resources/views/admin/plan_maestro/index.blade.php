@extends('layouts.master')
@section('title', 'Plan Maestro')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-shield-halved mr-2"></i> Plan Maestro Institucional
        </h4>
        <p class="card-category">Planes de gestión disponibles</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item active">Plan Maestro</li>
        </ol>
    </nav>

    <div class="card-body">
        @if($planes->isEmpty())
            <div class="alert alert-warning">
                <i class="fa fa-info-circle mr-2"></i> No hay planes disponibles.
            </div>
        @else
        <div class="row">
            @foreach($planes as $plan)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm" style="border-left:4px solid #1a6b5a;border-radius:10px">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-lg p-2 mr-3 flex-shrink-0"
                                 style="background:rgba(26,107,90,0.1)">
                                <i class="fa fa-shield-halved text-success" style="font-size:1.4rem"></i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold mb-1" style="font-size:.95rem">{{ $plan->nombre }}</h5>
                                <small class="text-muted">{{ $plan->institucion }}</small>
                            </div>
                        </div>

                        @if($plan->descripcion)
                        <p class="text-muted small mb-3">{{ Str::limit($plan->descripcion, 80) }}</p>
                        @endif

                        <div class="row text-center mb-3">
                            <div class="col-3">
                                <div class="font-weight-bold text-dark">{{ $plan->acciones_count }}</div>
                                <small class="text-muted" style="font-size:.7rem">Acciones</small>
                            </div>
                            <div class="col-3">
                                <div class="font-weight-bold text-dark">{{ $plan->diagnosticos_count }}</div>
                                <small class="text-muted" style="font-size:.7rem">Diagnóst.</small>
                            </div>
                            <div class="col-3">
                                <div class="font-weight-bold text-dark">{{ $plan->canillas_count }}</div>
                                <small class="text-muted" style="font-size:.7rem">Canillas</small>
                            </div>
                            <div class="col-3">
                                <div class="font-weight-bold text-dark">{{ $plan->citas_count }}</div>
                                <small class="text-muted" style="font-size:.7rem">Citas</small>
                            </div>
                        </div>

                        @if($plan->periodo)
                        <div class="mb-3">
                            <span class="badge badge-info" style="font-size:.75rem">
                                <i class="fa fa-calendar mr-1"></i>{{ $plan->periodo }}
                            </span>
                        </div>
                        @endif

                        <a href="{{ route('plan-maestro.show', $plan) }}"
                           class="btn btn-success btn-sm btn-block">
                            <i class="fa fa-search mr-1"></i> Abrir Buscador
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
