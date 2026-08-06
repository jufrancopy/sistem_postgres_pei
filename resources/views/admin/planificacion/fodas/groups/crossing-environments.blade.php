@extends('layouts.master')
@section('title', 'Cruce de Ambientes FODA')

@section('css')
<style>
/* Contener texto en celdas FODA */
.table-responsive table td {
    overflow: hidden;
    word-break: break-word;
    overflow-wrap: break-word;
    vertical-align: top;
}
.table-responsive table td > div {
    max-width: 100%;
    overflow: hidden;
}
</style>
@endsection

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 14px;">
    <div class="card-header card-header-info">
        <h4 class="card-title text-white font-weight-bold">
            <i class="fa fa-random mr-2"></i>
            Cruce de Ambientes FODA
            @if(isset($profile) && $profile) — {{ strip_tags($profile->name) }} @endif
        </h4>
        <p class="card-category text-white-50">Estrategias FO · FA · DO · DA</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('foda-list-groups') }}">Análisis FODA</a></li>
            @if(isset($profile) && $profile)
            <li class="breadcrumb-item">
                @if($profile->group_id)
                <a href="{{ route('foda-matriz-groups', $profile->group_id) }}">Matriz</a>
                @else
                <span>Matriz</span>
                @endif
            </li>
            @endif
            <li class="breadcrumb-item active">Cruce de Ambientes</li>
        </ol>
    </nav>

    <div class="card-body p-4">
        @include('admin.planificacion.fodas.groups.partials.crossing_content')
    </div>
</div>
@endsection