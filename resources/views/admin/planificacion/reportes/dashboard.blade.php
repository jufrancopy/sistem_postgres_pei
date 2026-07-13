@extends('layouts.master')
@section('title', 'Mi Panel de Monitoreo PEI')

@section('content')
@php
$anioActual = date('Y');
@endphp

{{-- ══ CABECERA ══════════════════════════════════════════════════════════════ --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-4 px-4"
         style="background:linear-gradient(135deg,#1a237e 0%,#283593 100%);border-radius:.5rem">
        <div class="d-flex align-items-center flex-wrap" style="gap:1rem">
            <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fa fa-chart-line text-white" style="font-size:1.4rem"></i>
            </div>
            <div style="flex:1;min-width:0">
                <div class="text-white-50 mb-1" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em">
                    Panel de Monitoreo
                </div>
                <h4 class="text-white mb-0 font-weight-bold">
                    Bienvenido, {{ $usuario->name }}
                </h4>
                <div class="text-white-50 mt-1" style="font-size:.8rem">
                    <i class="fa fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::now()->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                </div>
            </div>
            {{-- Resumen global --}}
            <div class="d-flex" style="gap:1rem;flex-shrink:0">
                <div class="text-center">
                    <div class="text-white font-weight-bold" style="font-size:1.8rem;line-height:1">{{ $totalAcciones }}</div>
                    <div class="text-white-50" style="font-size:.72rem">Acciones asignadas</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.2)"></div>
                <div class="text-center">
                    <div class="font-weight-bold" style="font-size:1.8rem;line-height:1;color:#69f0ae">{{ $totalReportadas }}</div>
                    <div class="text-white-50" style="font-size:.72rem">Reportadas</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,.2)"></div>
                <div class="text-center">
                    <div class="font-weight-bold" style="font-size:1.8rem;line-height:1;color:{{ ($totalAcciones - $totalReportadas) > 0 ? '#ff5252' : '#69f0ae' }}">
                        {{ $totalAcciones - $totalReportadas }}
                    </div>
                    <div class="text-white-50" style="font-size:.72rem">Sin reporte</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ PLANES ════════════════════════════════════════════════════════════════ --}}
@if($planes->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="fa fa-inbox fa-3x mb-3 d-block" style="color:#dee2e6"></i>
        <p class="mb-1 font-weight-bold">No tenés planes asignados.</p>
        <small>Si creés que es un error, contactá al administrador del sistema.</small>
    </div>
</div>
@else

<div class="row">
@foreach($planes as $plan)
@php
    $pct = $plan['total'] > 0 ? round(($plan['reportadas'] / $plan['total']) * 100) : 0;
    $pctColor = $pct >= 80 ? '#28a745' : ($pct >= 50 ? '#ffc107' : '#dc3545');
    $yearStart = \Carbon\Carbon::parse($plan['year_start'])->format('Y');
    $yearEnd   = \Carbon\Carbon::parse($plan['year_end'])->format('Y');
    $activo    = $anioActual >= $yearStart && $anioActual <= $yearEnd;
@endphp
<div class="col-md-6 col-lg-4 mb-4">
    <div class="card border-0 shadow-sm h-100" style="border-top:4px solid {{ $pctColor }} !important">
        <div class="card-body pb-2">

            {{-- Badge período --}}
            <div class="d-flex align-items-center mb-2" style="gap:.4rem">
                <span class="badge" style="background:#e8eaf6;color:#1a237e;font-size:.72rem">
                    {{ $yearStart }}–{{ $yearEnd }}
                </span>
                @if($activo)
                <span class="badge badge-success" style="font-size:.65rem">Activo</span>
                @endif
                @if($plan['sin_reporte'] > 0)
                <span class="badge badge-danger ml-auto" style="font-size:.65rem">
                    {{ $plan['sin_reporte'] }} sin reporte
                </span>
                @else
                <span class="badge badge-success ml-auto" style="font-size:.65rem">
                    <i class="fa fa-check mr-1"></i>Al día
                </span>
                @endif
            </div>

            {{-- Nombre del plan --}}
            <h6 class="font-weight-bold mb-3" style="font-size:.9rem;color:#1a237e;line-height:1.3">
                {{ $plan['name'] }}
            </h6>

            {{-- Barra de progreso --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span style="font-size:.72rem;color:#888">Avance de reportes</span>
                    <span class="font-weight-bold" style="font-size:.82rem;color:{{ $pctColor }}">{{ $pct }}%</span>
                </div>
                <div class="progress" style="height:8px;border-radius:4px;background:#e9ecef">
                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $pctColor }};border-radius:4px;transition:width .5s"></div>
                </div>
                <div class="d-flex justify-content-between mt-1" style="font-size:.7rem;color:#aaa">
                    <span>{{ $plan['reportadas'] }} reportadas</span>
                    <span>{{ $plan['total'] }} totales</span>
                </div>
            </div>

            {{-- Semáforos --}}
            <div class="d-flex" style="gap:.4rem;margin-bottom:.75rem">
                <div class="flex-fill text-center py-1 rounded" style="background:#e8f5e9">
                    <div class="font-weight-bold" style="color:#28a745;font-size:1rem">{{ $plan['verde'] }}</div>
                    <div style="font-size:.65rem;color:#6c757d">Verde</div>
                </div>
                <div class="flex-fill text-center py-1 rounded" style="background:#fffde7">
                    <div class="font-weight-bold" style="color:#f57f17;font-size:1rem">{{ $plan['amarillo'] }}</div>
                    <div style="font-size:.65rem;color:#6c757d">Amarillo</div>
                </div>
                <div class="flex-fill text-center py-1 rounded" style="background:#ffebee">
                    <div class="font-weight-bold" style="color:#dc3545;font-size:1rem">{{ $plan['rojo'] }}</div>
                    <div style="font-size:.65rem;color:#6c757d">Rojo</div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
            <a href="{{ route('pei.reportes.mis-acciones', $plan['id']) }}"
               class="btn btn-block font-weight-bold"
               style="background:#1a237e;color:#fff;border-radius:8px;font-size:.85rem;padding:.55rem">
                <i class="fa fa-chart-line mr-2"></i>Ver mis acciones y reportar
            </a>
        </div>
    </div>
</div>
@endforeach
</div>
@endif

@endsection
