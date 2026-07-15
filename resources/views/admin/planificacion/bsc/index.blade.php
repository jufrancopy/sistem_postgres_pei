@extends('layouts.master')
@section('title', 'Balanced Scorecard — ' . strip_tags($profile->name))

@push('styles')
<style>
.bsc-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
@media (max-width: 768px) {
    .bsc-grid { grid-template-columns: 1fr; }
}
.bsc-cuadrante {
    border-radius: .75rem;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
}
.bsc-cuadrante-header {
    padding: 1rem 1.25rem .75rem;
    color: #fff;
}
.bsc-cuadrante-body {
    background: #fff;
    padding: .75rem 1rem;
}
.bsc-eje {
    border-left: 4px solid transparent;
    border-radius: 0 .5rem .5rem 0;
    background: #f8f9fa;
    padding: .6rem .75rem;
    margin-bottom: .5rem;
}
.bsc-eje:last-child { margin-bottom: 0; }
.semaforo-dot {
    width: 10px; height: 10px; border-radius: 50%;
    display: inline-block; flex-shrink: 0;
}
.bsc-objetivo {
    font-size: .78rem; color: #495057;
    padding: .2rem 0 .2rem .5rem;
    border-left: 2px solid #dee2e6;
    margin-bottom: .2rem;
}
.bsc-accion {
    font-size: .72rem; color: #6c757d;
    padding-left: .75rem;
    display: flex; align-items: center; gap: .3rem;
}
.bsc-stats {
    display: flex; gap: .3rem; align-items: center; flex-wrap: wrap;
    margin-top: .3rem;
}
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex align-items-center py-2">
        <div>
            <h4 class="card-title mb-0">
                <i class="fa fa-chart-bar mr-2"></i>Balanced Scorecard
            </h4>
            <small class="text-white" style="opacity:.8">{{ strip_tags($profile->name) }}
                · {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
            </small>
        </div>
        <div class="ml-auto d-flex" style="gap:.5rem">
            <a href="{{ route('pei-profiles.show', $profile->id) }}" class="btn btn-sm btn-light">
                <i class="fa fa-arrow-left mr-1"></i> Volver al PEI
            </a>
        </div>
    </div>

    <div class="card-body">

        {{-- Cabecera del mapa --}}
        <div class="text-center mb-4">
            <div class="d-inline-block px-4 py-2 rounded" style="background:#1a237e;color:#fff;font-size:.85rem;font-weight:600;letter-spacing:.04em">
                <i class="fa fa-bullseye mr-2"></i>MAPA ESTRATÉGICO INSTITUCIONAL
            </div>
            <div class="mt-2 text-muted" style="font-size:.8rem">
                Cuatro perspectivas del Balanced Scorecard · Kaplan & Norton
            </div>
        </div>

        {{-- Grid de 4 cuadrantes --}}
        <div class="bsc-grid">
            @php
            $orden = ['financiera','clientes','procesos','aprendizaje','sin_bsc'];
            @endphp

            @foreach($orden as $clave)
            @if(!isset($perspectivas[$clave])) @continue @endif
            @php $p = $perspectivas[$clave]; @endphp

            <div class="bsc-cuadrante">
                {{-- Header del cuadrante --}}
                <div class="bsc-cuadrante-header" style="background:{{ $p['color'] }}">
                    <div class="d-flex align-items-center">
                        <i class="fa {{ $p['icon'] }} fa-lg mr-2"></i>
                        <div>
                            <div class="font-weight-bold" style="font-size:.95rem">
                                {{ $p['label'] }}
                            </div>
                            <small style="opacity:.8;font-size:.72rem">
                                {{ $p['ejes']->count() }} eje(s) estratégico(s)
                            </small>
                        </div>
                        {{-- Semáforo agregado del cuadrante --}}
                        @php
                            $totV = $p['ejes']->sum('verde');
                            $totA = $p['ejes']->sum('amarillo');
                            $totR = $p['ejes']->sum('rojo');
                            $totT = $p['ejes']->sum('total');
                        @endphp
                        @if($totT > 0)
                        <div class="ml-auto text-right">
                            <div class="d-flex" style="gap:.25rem">
                                @if($totV > 0)<span class="badge" style="background:rgba(255,255,255,.25);font-size:.65rem">✅ {{ $totV }}</span>@endif
                                @if($totA > 0)<span class="badge" style="background:rgba(255,255,255,.25);font-size:.65rem">⚠️ {{ $totA }}</span>@endif
                                @if($totR > 0)<span class="badge" style="background:rgba(255,255,255,.25);font-size:.65rem">🔴 {{ $totR }}</span>@endif
                            </div>
                            <small style="opacity:.7;font-size:.65rem">{{ $totT }} acción(es)</small>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Ejes del cuadrante --}}
                <div class="bsc-cuadrante-body">
                    @foreach($p['ejes'] as $eje)
                    @php
                        $scColors = ['verde'=>'#28a745','amarillo'=>'#ffc107','rojo'=>'#dc3545','sin-datos'=>'#adb5bd'];
                        $scColor  = $scColors[$eje['semaforo']] ?? '#adb5bd';
                    @endphp
                    <div class="bsc-eje" style="border-left-color:{{ $scColor }}">
                        {{-- Nombre del eje --}}
                        <div class="d-flex align-items-start" style="gap:.4rem">
                            <span class="semaforo-dot mt-1 flex-shrink-0" style="background:{{ $scColor }}"></span>
                            <div style="flex:1;min-width:0">
                                <div class="font-weight-bold" style="font-size:.82rem;color:#1a237e;line-height:1.3">
                                    {{ $eje['name'] }}
                                </div>

                                {{-- Resultado Intermedio --}}
                                @if($eje['ri'])
                                <div class="text-muted mt-1" style="font-size:.72rem;font-style:italic">
                                    <i class="fa fa-flag mr-1" style="color:{{ $p['color'] }}"></i>{{ $eje['ri'] }}
                                </div>
                                @endif

                                {{-- Stats de acciones --}}
                                @if($eje['total'] > 0)
                                <div class="bsc-stats mt-1">
                                    @if($eje['verde']   > 0)<span class="badge badge-success"   style="font-size:.6rem">{{ $eje['verde'] }} verde</span>@endif
                                    @if($eje['amarillo']> 0)<span class="badge badge-warning"   style="font-size:.6rem">{{ $eje['amarillo'] }} amarillo</span>@endif
                                    @if($eje['rojo']    > 0)<span class="badge badge-danger"    style="font-size:.6rem">{{ $eje['rojo'] }} rojo</span>@endif
                                    <span class="text-muted" style="font-size:.65rem">/ {{ $eje['total'] }} acciones</span>
                                </div>
                                @endif

                                {{-- Recursos del RI --}}
                                @if($eje['ri_recursos'])
                                <div class="mt-1" style="font-size:.68rem;color:#6c757d">
                                    <i class="fa fa-coins mr-1"></i>Gs. {{ number_format($eje['ri_recursos'], 0, ',', '.') }}
                                </div>
                                @endif

                                {{-- Metas del RI --}}
                                @if($eje['ri_metas'] && count($eje['ri_metas']) > 0)
                                <div class="d-flex flex-wrap mt-1" style="gap:.2rem">
                                    @foreach($eje['ri_metas'] as $meta)
                                    <span class="badge" style="background:{{ $p['color'] }}22;color:{{ $p['color'] }};font-size:.62rem;border:1px solid {{ $p['color'] }}44">
                                        {{ $meta['anio'] }}: {{ $meta['valor'] }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif

                                {{-- Objetivos y acciones (colapsable) --}}
                                @php
                                    $totalAccEje = collect($eje['objetivos'])->sum(fn($o) => count($o['acciones']));
                                @endphp
                                @if($totalAccEje > 0)
                                <div class="mt-2">
                                    <a href="javascript:void(0)"
                                       data-toggle="collapse"
                                       data-target="#bsc-eje-{{ $eje['id'] }}"
                                       style="font-size:.68rem;color:{{ $p['color'] }};text-decoration:none">
                                        <i class="fa fa-chevron-down mr-1"></i>
                                        Ver {{ $totalAccEje }} acción(es)
                                    </a>
                                    <div class="collapse mt-1" id="bsc-eje-{{ $eje['id'] }}">
                                        @foreach($eje['objetivos'] as $obj)
                                        <div class="bsc-objetivo">
                                            <div class="font-weight-bold" style="color:#343a40">{{ $obj['name'] }}</div>
                                            @foreach($obj['acciones'] as $acc)
                                            @php
                                                $asc = ['verde'=>'#28a745','amarillo'=>'#ffc107','rojo'=>'#dc3545','sin-datos'=>'#adb5bd'][$acc['semaforo']] ?? '#adb5bd';
                                            @endphp
                                            <div class="bsc-accion">
                                                <span class="semaforo-dot" style="background:{{ $asc }};width:7px;height:7px"></span>
                                                {{ $acc['name'] }}
                                                @if($acc['pct'] !== null)
                                                <span class="badge badge-light border ml-1" style="font-size:.6rem">{{ $acc['pct'] }}%</span>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- Leyenda --}}
        <div class="d-flex justify-content-center mt-4" style="gap:1rem;flex-wrap:wrap">
            <small class="text-muted d-flex align-items-center" style="gap:.3rem;font-size:.75rem">
                <span class="semaforo-dot" style="background:#28a745"></span> Verde — ≥85% de avance
            </small>
            <small class="text-muted d-flex align-items-center" style="gap:.3rem;font-size:.75rem">
                <span class="semaforo-dot" style="background:#ffc107"></span> Amarillo — 50–84%
            </small>
            <small class="text-muted d-flex align-items-center" style="gap:.3rem;font-size:.75rem">
                <span class="semaforo-dot" style="background:#dc3545"></span> Rojo — &lt;50%
            </small>
            <small class="text-muted d-flex align-items-center" style="gap:.3rem;font-size:.75rem">
                <span class="semaforo-dot" style="background:#adb5bd"></span> Sin datos
            </small>
        </div>

    </div>
</div>
@stop
