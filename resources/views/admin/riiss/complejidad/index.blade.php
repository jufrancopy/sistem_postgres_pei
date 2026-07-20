@extends('layouts.master')
@section('title', 'Grados de Complejidad')

@push('styles')
<style>
.grado-card { border-left: 5px solid; border-radius: 8px; transition: box-shadow .2s; }
.grado-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); }
.flag-badge { font-size: .7rem; padding: 2px 8px; border-radius: 10px; font-weight: 600; }
.flag-si  { background: #d1fae5; color: #065f46; }
.flag-no  { background: #f1f5f9; color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title"><i class="fa fa-layer-group mr-2"></i>Grados de Complejidad</h4>
        <p class="card-category">Configuración de los 6 grados de complejidad de la Red IPS</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Grados de Complejidad</li>
        </ol>
    </nav>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            @foreach($tipos as $tipo)
            <div class="col-md-6 mb-4">
                <div class="card grado-card h-100" style="border-left-color: {{ $tipo->color }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge badge-pill text-white font-weight-bold px-3 py-2"
                                      style="background: {{ $tipo->color }}; font-size: .85rem;">
                                    Grado {{ $tipo->grado }}
                                </span>
                                <span class="ml-2 small text-muted">Nivel de Atención {{ $tipo->nivel_atencion }}</span>
                            </div>
                            <a href="{{ route('riiss.complejidad.edit', $tipo) }}"
                               class="btn btn-sm btn-outline-danger">
                                <i class="fa fa-edit mr-1"></i>Editar
                            </a>
                        </div>

                        <h6 class="font-weight-bold mt-2">{{ $tipo->nombre }}</h6>
                        @if($tipo->tipo_establecimiento)
                            <p class="text-muted small mb-2">
                                <i class="fa fa-hospital-o mr-1"></i>{{ $tipo->tipo_establecimiento }}
                            </p>
                        @endif

                        <div class="d-flex flex-wrap mt-2" style="gap: 6px">
                            <span class="flag-badge {{ $tipo->es_hospitalario ? 'flag-si' : 'flag-no' }}">
                                <i class="fa fa-{{ $tipo->es_hospitalario ? 'check' : 'times' }} mr-1"></i>Hospitalario
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

                        <div class="mt-2 small text-muted">
                            <i class="fa fa-building mr-1"></i>
                            {{ $tipo->establecimientos()->whereNull('deleted_at')->count() }} establecimientos asignados
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
