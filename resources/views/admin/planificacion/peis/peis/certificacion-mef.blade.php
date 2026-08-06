@extends('layouts.master')
@section('title', 'Certificación MEF')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-certificate mr-2"></i>Certificación MEF - {{ strip_tags($profile->name) }}</h4>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            @hasanyrole('Administrador|Coordinador de Planificación')
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles PEI</a></li>
            @endhasanyrole
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.proceso', $profile->id) }}">Proceso</a></li>
            <li class="breadcrumb-item active">Certificación MEF</li>
        </ol>
    </nav>

    <div class="card-body">
        @include('admin.planificacion.peis.peis.partials.certificacion_mef_content')

        <div class="mt-4">
            <a href="{{ route('pei-profiles.proceso', $profile->id) }}" class="btn btn-outline-secondary btn-sm btn-round">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Proceso
            </a>
        </div>
    </div>
</div>
@stop
