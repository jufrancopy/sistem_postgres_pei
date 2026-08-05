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
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles PEI</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.proceso', $profile->id) }}">Proceso</a></li>
            <li class="breadcrumb-item active">Certificación MEF</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- Progreso global --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-1">
                <small class="font-weight-bold text-muted text-uppercase" style="letter-spacing:.05em">
                    Cumplimiento de matrices MEF
                </small>
                <small class="font-weight-bold">{{ $completados }}/{{ $total }} matrices completadas ({{ $pct }}%)</small>
            </div>
            <div class="progress" style="height:12px;border-radius:6px;">
                <div class="progress-bar {{ $pct == 100 ? 'bg-success' : ($pct >= 60 ? 'bg-warning' : 'bg-danger') }}"
                     style="width:{{ $pct }}%;transition:width .6s ease"></div>
            </div>
            @if($pct == 100)
                <div class="alert alert-success mt-3 mb-0">
                    <i class="fa fa-check-circle mr-2"></i>
                    <strong>¡PEI listo para certificación MEF!</strong> Todas las matrices están completas.
                </div>
            @else
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fa fa-exclamation-triangle mr-2"></i>
                    Faltan <strong>{{ $total - $completados }}</strong> matrices para completar la certificación.
                </div>
            @endif
        </div>

        {{-- Checklist --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Matriz MEF</th>
                        <th>Estado</th>
                        <th>Detalle</th>
                        <th style="width:120px">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checklist as $item)
                    <tr class="{{ $item['ok'] ? 'table-success' : ($item['pendiente'] ? 'table-secondary' : 'table-warning') }}">
                        <td class="text-center font-weight-bold">{{ $item['num'] }}</td>
                        <td>{{ $item['label'] }}</td>
                        <td class="text-center">
                            @if($item['pendiente'])
                                <span class="badge badge-secondary"><i class="fa fa-wrench mr-1"></i>Por construir</span>
                            @elseif($item['ok'])
                                <span class="badge badge-success"><i class="fa fa-check mr-1"></i>Completo</span>
                            @else
                                <span class="badge badge-warning"><i class="fa fa-clock mr-1"></i>Pendiente</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $item['valor'] }}</small></td>
                        <td>
                            @if($item['url'])
                                <a href="{{ $item['url'] }}" class="btn btn-xs btn-outline-primary" target="_blank">
                                    <i class="fa fa-external-link-alt"></i> Ir
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route('pei-profiles.proceso', $profile->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Proceso
            </a>
        </div>

    </div>
</div>
@stop
