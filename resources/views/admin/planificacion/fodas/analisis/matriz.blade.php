@extends('layouts.master')
@section('title', 'Matriz FODA')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-th mr-2"></i>
            Matriz FODA
            @if(isset($perfil) && $perfil) — {{ strip_tags($perfil->name) }} @endif
        </h4>
        <p class="card-category">Análisis interno y externo · Umbral de ponderación: 0.17</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('foda-perfiles.index') }}">Perfiles FODA</a></li>
            @if(isset($perfil) && $perfil)
            <li class="breadcrumb-item active">{{ strip_tags($perfil->name) }}</li>
            @else
            <li class="breadcrumb-item active">Matriz</li>
            @endif
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Perfil info ── --}}
        @if(isset($perfil) && $perfil)
        <div class="mb-3">
            <small class="text-muted font-weight-bold text-uppercase" style="font-size:.7rem;letter-spacing:.05em">
                <i class="fa fa-user mr-1"></i> Perfil analizado
            </small>
            <div class="mt-1">
                <span class="badge badge-info mr-1 mb-1" style="font-size:.8rem">{{ strip_tags($perfil->name) }}</span>
                @if($perfil->context)
                <small class="text-muted">· {{ Str::limit(strip_tags($perfil->context), 80) }}</small>
                @endif
            </div>
        </div>
        @endif

        {{-- ── KPIs ── --}}
        @php
            $totalD = count($debilidades);
            $totalF = count($fortalezas);
            $totalO = count($oportunidades);
            $totalA = count($amenazas);

            $conMecip = collect(array_merge($debilidades->all(), $amenazas->all()))
                ->filter(fn($v) => $v->causa_raiz || $v->accion_mejora)->count();
        @endphp
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-2">
                <div class="card shadow-sm text-center" style="border-left:4px solid #28a745">
                    <div class="card-body py-2">
                        <div class="h3 font-weight-bold text-success mb-0">{{ $totalF }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem">Fortalezas</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card shadow-sm text-center" style="border-left:4px solid #dc3545">
                    <div class="card-body py-2">
                        <div class="h3 font-weight-bold text-danger mb-0">{{ $totalD }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem">Debilidades</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card shadow-sm text-center" style="border-left:4px solid #17a2b8">
                    <div class="card-body py-2">
                        <div class="h3 font-weight-bold text-info mb-0">{{ $totalO }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem">Oportunidades</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card shadow-sm text-center" style="border-left:4px solid #fd7e14">
                    <div class="card-body py-2">
                        <div class="h3 font-weight-bold text-warning mb-0">{{ $totalA }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem">Amenazas</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Alerta MECIP ── --}}
        @if(($totalD + $totalA) > 0 && $conMecip < ($totalD + $totalA))
        <div class="alert py-2 px-3 mb-3"
             style="background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:6px">
            <small style="color:#92400e">
                <i class="fa fa-shield-alt mr-1"></i>
                <strong>MECIP 2015:</strong>
                {{ ($totalD + $totalA) - $conMecip }} debilidad(es)/amenaza(s) sin análisis de riesgo completado.
                Editá cada aspecto para agregar causa raíz y acción de mejora.
            </small>
        </div>
        @endif

        {{-- ── Matriz 2×2 ── --}}
        <div class="table-responsive">
            <table class="table table-bordered mb-0" style="table-layout:fixed">
                <thead>
                    <tr>
                        <th style="width:50%;background:#f8f9fa">
                            <i class="fa fa-building mr-1 text-primary"></i> Análisis Interno
                        </th>
                        <th style="width:50%;background:#f8f9fa">
                            <i class="fa fa-globe mr-1 text-info"></i> Análisis Externo
                        </th>
                    </tr>
                </thead>
                <tbody>

                    {{-- ── Debilidades | Amenazas ── --}}
                    <tr>
                        <td style="background:#fff5f5;vertical-align:top;padding:0">
                            <div style="background:#dc3545;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-arrow-down mr-1"></i> Debilidades
                                <span class="badge badge-light text-danger ml-1">{{ $totalD }}</span>
                            </div>
                            <div style="padding:12px 14px">
                                @forelse($debilidades as $i => $v)
                                <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <span class="badge badge-danger mr-2 mt-1" style="font-size:.7rem;min-width:22px">D{{ $i+1 }}</span>
                                        <div style="flex:1">
                                            <div style="font-size:.88rem;font-weight:600;color:#343a40">
                                                {{ $v->aspecto?->name ?? '(sin nombre)' }}
                                            </div>
                                            <div class="mt-1">
                                                @php $pond = $v->ocurrencia * $v->impacto; @endphp
                                                <span class="badge badge-{{ $pond >= 0.28 ? 'danger' : ($pond >= 0.09 ? 'warning' : 'secondary') }}" style="font-size:.68rem">
                                                    Pond: {{ number_format($pond, 2) }}
                                                </span>
                                            </div>
                                            @if($v->causa_raiz || $v->accion_mejora)
                                            <div class="mt-1 p-1 rounded" style="background:#fffbeb;border:1px solid #fde68a;font-size:.75rem">
                                                @if($v->causa_raiz)
                                                <div><span class="text-muted">Causa:</span>
                                                    <span class="badge badge-warning" style="font-size:.65rem">
                                                        {{ \App\Admin\Planificacion\Foda\FodaAnalisis::CAUSAS_RAIZ[$v->causa_raiz] ?? $v->causa_raiz }}
                                                    </span>
                                                </div>
                                                @endif
                                                @if($v->accion_mejora)
                                                <div class="mt-1"><span class="text-muted">Acción:</span>
                                                    <span style="color:#374151">{{ Str::limit($v->accion_mejora, 80) }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            @else
                                            <div class="mt-1" style="font-size:.72rem;color:#9ca3af;font-style:italic">
                                                <i class="fa fa-exclamation-circle mr-1"></i>Sin análisis MECIP
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin debilidades sobre el umbral</p>
                                @endforelse
                            </div>
                        </td>

                        <td style="background:#fff5f5;vertical-align:top;padding:0">
                            <div style="background:#fd7e14;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-exclamation-triangle mr-1"></i> Amenazas
                                <span class="badge badge-light text-warning ml-1">{{ $totalA }}</span>
                            </div>
                            <div style="padding:12px 14px">
                                @forelse($amenazas as $i => $v)
                                <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <span class="badge badge-warning mr-2 mt-1" style="font-size:.7rem;min-width:22px">A{{ $i+1 }}</span>
                                        <div style="flex:1">
                                            <div style="font-size:.88rem;font-weight:600;color:#343a40">
                                                {{ $v->aspecto?->name ?? '(sin nombre)' }}
                                            </div>
                                            <div class="mt-1">
                                                @php $pond = $v->ocurrencia * $v->impacto; @endphp
                                                <span class="badge badge-{{ $pond >= 0.28 ? 'danger' : ($pond >= 0.09 ? 'warning' : 'secondary') }}" style="font-size:.68rem">
                                                    Pond: {{ number_format($pond, 2) }}
                                                </span>
                                            </div>
                                            @if($v->causa_raiz || $v->accion_mejora)
                                            <div class="mt-1 p-1 rounded" style="background:#fffbeb;border:1px solid #fde68a;font-size:.75rem">
                                                @if($v->causa_raiz)
                                                <div><span class="text-muted">Causa:</span>
                                                    <span class="badge badge-warning" style="font-size:.65rem">
                                                        {{ \App\Admin\Planificacion\Foda\FodaAnalisis::CAUSAS_RAIZ[$v->causa_raiz] ?? $v->causa_raiz }}
                                                    </span>
                                                </div>
                                                @endif
                                                @if($v->accion_mejora)
                                                <div class="mt-1"><span class="text-muted">Acción:</span>
                                                    <span style="color:#374151">{{ Str::limit($v->accion_mejora, 80) }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            @else
                                            <div class="mt-1" style="font-size:.72rem;color:#9ca3af;font-style:italic">
                                                <i class="fa fa-exclamation-circle mr-1"></i>Sin análisis MECIP
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin amenazas sobre el umbral</p>
                                @endforelse
                            </div>
                        </td>
                    </tr>

                    {{-- ── Fortalezas | Oportunidades ── --}}
                    <tr>
                        <td style="background:#f0fff4;vertical-align:top;padding:0">
                            <div style="background:#28a745;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-arrow-up mr-1"></i> Fortalezas
                                <span class="badge badge-light text-success ml-1">{{ $totalF }}</span>
                            </div>
                            <div style="padding:12px 14px">
                                @forelse($fortalezas as $i => $v)
                                <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <span class="badge badge-success mr-2 mt-1" style="font-size:.7rem;min-width:22px">F{{ $i+1 }}</span>
                                        <div style="flex:1">
                                            <div style="font-size:.88rem;font-weight:600;color:#343a40">
                                                {{ $v->aspecto?->name ?? '(sin nombre)' }}
                                            </div>
                                            @php $pond = $v->ocurrencia * $v->impacto; @endphp
                                            <span class="badge badge-success" style="font-size:.68rem">
                                                Pond: {{ number_format($pond, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin fortalezas sobre el umbral</p>
                                @endforelse
                            </div>
                        </td>

                        <td style="background:#f0fff4;vertical-align:top;padding:0">
                            <div style="background:#17a2b8;color:#fff;padding:8px 14px;font-weight:700;font-size:.82rem">
                                <i class="fa fa-star mr-1"></i> Oportunidades
                                <span class="badge badge-light text-info ml-1">{{ $totalO }}</span>
                            </div>
                            <div style="padding:12px 14px">
                                @forelse($oportunidades as $i => $v)
                                <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <span class="badge badge-info mr-2 mt-1" style="font-size:.7rem;min-width:22px">O{{ $i+1 }}</span>
                                        <div style="flex:1">
                                            <div style="font-size:.88rem;font-weight:600;color:#343a40">
                                                {{ $v->aspecto?->name ?? '(sin nombre)' }}
                                            </div>
                                            @php $pond = $v->ocurrencia * $v->impacto; @endphp
                                            <span class="badge badge-info" style="font-size:.68rem">
                                                Pond: {{ number_format($pond, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted small font-italic">Sin oportunidades sobre el umbral</p>
                                @endforelse
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- ── Acciones ── --}}
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small">
                @if(isset($perfil) && $perfil)
                <i class="fa fa-info-circle mr-1"></i>
                Perfil: <strong>{{ strip_tags($perfil->name) }}</strong>
                @if($perfil->context) · {{ Str::limit(strip_tags($perfil->context), 60) }} @endif
                @endif
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('foda-cruce-ambientes', $idPerfil) }}" class="btn btn-info btn-sm mr-1">
                    <i class="fa fa-random mr-1"></i> Cruce de Ambientes
                </a>
                <a href="{{ route('foda-cruce-pdf', $idPerfil) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-file-pdf mr-1"></i> Descargar PDF
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
