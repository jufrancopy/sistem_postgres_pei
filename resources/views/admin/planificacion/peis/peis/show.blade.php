@extends('layouts.master')
@section('title', 'Planificación Estratégica')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Módulo de Planificación Estratégica</h4>
        </div>
        <!-- HTML del primer nav -->
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4" id="default-nav">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles</a></li>

                <li class="breadcrumb-item active" aria-current="page">Módulo de Planificación Estratégica</li>
            </ol>
        </nav>

        <div class="px-3 pb-2">
            <a href="{{ route('pei-profiles.dashboard', $profile->id) }}" class="btn btn-sm btn-dark">
                <i class="fa fa-chart-bar mr-1"></i> Tablero de Monitoreo
            </a>
            <a href="{{ route('pei.bsc', $profile->id) }}" class="btn btn-sm btn-outline-dark ml-2">
                <i class="fa fa-th-large mr-1"></i> Balanced Scorecard
            </a>
            <a href="{{ route('pei.indicadores.modulo', $profile->id) }}" class="btn btn-sm btn-outline-dark ml-2">
                <i class="fa fa-ruler-combined mr-1"></i> Indicadores
            </a>
            <a href="{{ route('pei.mee.modulo', $profile->id) }}" class="btn btn-sm btn-outline-dark ml-2">
                <i class="fa fa-balance-scale mr-1"></i> Marco Estratégico Específico
            </a>
            <button type="button" class="btn btn-sm btn-warning font-weight-bold ml-2 shadow-xs text-dark" data-toggle="modal" data-target="#modalBuscadorIniciativasPlanMaestro" title="Buscador y Mapa del Plan Maestro / Iniciativas de Mejora Continua">
                <i class="fa fa-bullseye mr-1 text-dark"></i> Plan Maestro / Iniciativas
            </button>
            <a href="{{ route('pei-profiles.matriz', $profile->id) }}" class="btn btn-sm btn-outline-primary ml-2" target="_blank">
                <i class="fa fa-table mr-1"></i> Formulación Estratégica Integrada
            </a>
            <button type="button" class="btn btn-sm btn-dark font-weight-bold ml-2 shadow-xs" id="btnAbrirModalReordenarPei"
                    data-profile="{{ $profile->id }}"
                    title="Reordenar Estructura PEI arrastrando y soltando (Drag & Drop)"
                    style="background: #1e293b; color: #f8fafc; border: none;">
                <i class="fa fa-sort-amount-asc mr-1 text-warning"></i> REORDENAR PEI
            </button>
            <button type="button" class="btn btn-sm btn-outline-success ml-2" id="btnNotificarTodosPei"
                    data-profile="{{ $profile->id }}"
                    title="Enviar email a todos los responsables de acciones del plan">
                <i class="fa fa-paper-plane mr-1"></i> Notificar Responsables
            </button>
            {{-- Botón Vista Pública --}}
            <button type="button" class="btn btn-sm btn-outline-secondary ml-2" id="btnPublicLink"
                    data-profile="{{ $profile->id }}"
                    data-token="{{ $profile->public_token }}"
                    title="Generar y compartir enlace público del plan">
                <i class="fa fa-share-alt mr-1"></i>
                {{ $profile->public_token ? 'Enlace público' : 'Generar enlace público' }}
            </button>
            <a href="{{ route('proyectos-institucionales.index', $profile->id) }}" class="btn btn-sm btn-success ml-2">
                <i class="fa fa-project-diagram mr-1"></i> Proyectos
            </a>
            @php
                $actividadVinculada = \App\Admin\Globales\Activity::where('pei_profile_id', $profile->id)->first();
                $urlActividad = $actividadVinculada 
                    ? route('globales.activities.show', $actividadVinculada->id) 
                    : route('globales.activities.create', ['pei_profile_id' => $profile->id]);
            @endphp
            <a href="{{ $urlActividad }}" class="btn btn-sm btn-info ml-2">
                <i class="fa fa-tasks mr-1"></i> Actividades
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary ml-2" data-toggle="modal" data-target="#modalQrSolicitud">
                <i class="fa fa-qrcode mr-1"></i> QR Solicitud
            </button>
            @role('Administrador')
            <button type="button" class="btn btn-sm ml-2" data-toggle="modal" data-target="#modalPuntosManuales"
                    style="background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;border:none"
                    title="Otorgar puntos manuales a un funcionario en este plan">
                <i class="fa fa-star mr-1"></i> Otorgar Puntos
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning ml-2 font-weight-bold" id="btnRankingPei"
                    data-toggle="modal" data-target="#modalRankingPei"
                    title="Ver ranking de talento humano por puntos en este plan">
                <i class="fa fa-trophy mr-1"></i> Ranking
            </button>
            <button type="button" id="btnRecalcularGamificacionPei" class="btn btn-sm btn-outline-warning ml-2 font-weight-bold"
                    title="Recalcular retroactivamente los puntos e insignias de este plan">
                <i class="fa fa-sync-alt mr-1"></i> Recalcular Puntos
            </button>
            @endrole
        </div>

        <!-- HTML del segundo nav (inicialmente oculto) -->
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4" id="dynamic-nav" style="display: none;">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tareas</a></li>
                <li class="breadcrumb-item" id="tasks-show-link-dynamic">Lista de Tareas</li>
                <li class="breadcrumb-item active" aria-current="page">Ambientes</li>
            </ol>
        </nav>

        {{-- Inicio Contenido Principal --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 detailHeader">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>
                                                {{ strip_tags($profile->name) }}
                                            </h2>
                                            <div class="col">
                                                <label><i class="fa fa-calendar" aria-hidden="true"></i> Periodo:
                                                </label>
                                                {{ Carbon\Carbon::parse($profile->year_start)->format('Y') }} -
                                                {{ Carbon\Carbon::parse($profile->year_end)->format('Y') }}
                                            </div>
                                            <div class="col mt-2">
                                                <label><i class="fa fa-layer-group" aria-hidden="true"></i> Modelo de Niveles:</label>
                                                @if($profile->nivel_label)
                                                    <span class="badge badge-info ml-1">{{ $niveles['axi'] ?? '—' }}</span>
                                                    <i class="fa fa-arrow-right text-muted mx-1" style="font-size:.75rem"></i>
                                                    <span class="badge badge-primary">{{ $niveles['goal'] ?? '—' }}</span>
                                                    <i class="fa fa-arrow-right text-muted mx-1" style="font-size:.75rem"></i>
                                                    <span class="badge badge-success">{{ $niveles['action'] ?? '—' }}</span>
                                                @else
                                                    <span class="badge badge-warning ml-1">
                                                        <i class="fa fa-exclamation-triangle mr-1"></i> Sin modelo definido
                                                    </span>
                                                    <small class="text-muted ml-1">— Editá el perfil para asignar uno</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <label><i class="fa fa-users" aria-hidden="true"></i> Grupos de
                                                        Trabajo:
                                                    </label><br>
                                                    @php
                                                        $totalMembers = 0;
                                                    @endphp

                                                    @if($profile->group)
                                                        @foreach ($profile->group->descendants as $group)
                                                            <span data-toggle="collapse" href="#group_{{ $group->id }}"
                                                                role="button" aria-expanded="false"
                                                                aria-controls="group_{{ $group->id }}"
                                                                class="badge badge-secondary">{{ $group->name }}
                                                            </span>
                                                            <div class="collapse" id="group_{{ $group->id }}">
                                                                <div class="card card-body">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr class="table-success">
                                                                                    <th>Nro</th>
                                                                                    <th>Participantes</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($group->members as $index => $member)
                                                                                    @php
                                                                                        $totalMembers++;
                                                                                    @endphp
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>
                                                                                            <span class="badge badge-secondary">{{ $member->name }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @elseif($profile->dependency)
                                                        <span class="badge badge-info">
                                                            <i class="fa fa-building mr-1"></i>
                                                            {{ $profile->dependency->dependency }}
                                                        </span>
                                                        <small class="text-muted ml-1">(PEI Corporativo)</small>
                                                    @else
                                                        <span class="text-muted"><em>Sin grupo asignado</em></span>
                                                    @endif
                                                </li>
                                            </ul>

                                            <div class="row border">

                                                <div class="col">
                                                    <label><i class="fa fa-bullseye" aria-hidden="true"></i> {{ $niveles['axi'] ?? 'Nivel 1' }}: </label>
                                                    <a class="btn btn-danger btn-circle text-white ml-auto"
                                                        href="javascript:void(0)" data-id="{{ $profile->id }}"
                                                        id="showAxisList"
                                                        title="Ver lista de {{ $niveles['axi'] ?? 'Nivel 1' }}s">
                                                        {{ $profile->descendants()->where('level', 'axi')->count() }}
                                                    </a>
                                                </div>

                                                <div class="col">
                                                    <label><i class="fa fa-flag-checkered" aria-hidden="true"></i> {{ $niveles['goal'] ?? 'Nivel 2' }}:
                                                    </label>
                                                    <a class="btn btn-danger btn-circle text-white ml-auto"
                                                        href="javascript:void(0)" data-id="{{ $profile->id }}"
                                                        id="showGoalsList"
                                                        title="Ver lista de {{ $niveles['goal'] ?? 'Nivel 2' }}s">
                                                        {{ $profile->descendants()->where('level', 'goal')->count() }}
                                                    </a>
                                                </div>

                                                <div class="col">
                                                    <label><i class="fa fa-rocket" aria-hidden="true"></i> {{ $niveles['action'] ?? 'Acciones' }}:
                                                    </label>
                                                    <a class="btn btn-danger btn-circle text-white ml-auto"
                                                        href="javascript:void(0)" data-id="{{ $profile->id }}"
                                                        id="showActionsList"
                                                        title="Ver lista de {{ $niveles['action'] ?? 'Acciones' }}">
                                                        {{ $profile->descendants()->where('level', 'action')->count() }}
                                                    </a>
                                                </div>

                                                <div class="col">
                                                    <label><i class="fa fa-user" aria-hidden="true"></i> Participantes:
                                                    </label>
                                                    <div class="btn btn-danger btn-circle">{{ $totalMembers }}</div>
                                                </div>

                                            </div>

                                            {{-- ── Editores del Plan ── --}}
                                            @php
                                                $nodosIds = \App\Admin\Planificacion\Pei\PeiProfile::where('_lft', '>=', $profile->_lft)
                                                    ->where('_rgt', '<=', $profile->_rgt)
                                                    ->pluck('id');
                                                $editores = \App\Models\Planificacion\PeiProfileEdit::whereIn('pei_profile_id', $nodosIds)
                                                    ->with('user')
                                                    ->select('user_id')
                                                    ->groupBy('user_id')
                                                    ->get();
                                            @endphp
                                            <div class="mt-3 px-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa fa-users text-muted mr-2"></i>
                                                    <span class="font-weight-bold text-uppercase" style="font-size:.75rem; letter-spacing:.05em; color:#495057">
                                                        Editores del Plan
                                                    </span>
                                                    <span class="badge badge-light border ml-2" style="font-size:.68rem">
                                                        {{ $editores->count() }} editor(es)
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-wrap" style="gap:.35rem">
                                                    @foreach($editores as $editor)
                                                        @if($editor->user)
                                                            <span class="badge badge-light border" style="font-size:.72rem; padding:.35em .6em" title="Ha editado elementos dentro del plan">
                                                                <i class="fa fa-user-edit mr-1 text-info"></i>{{ $editor->user->name }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                    @if($editores->isEmpty())
                                                        <span class="text-muted small"><i>Nadie ha editado este plan aún.</i></span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- ── Marco Estratégico General ── --}}
                                            @if($marcosGenerales->count() > 0)
                                            @php
                                                $coloresMeg = [
                                                    'pnd'     => ['bg' => 'badge-danger',   'icon' => 'fa-flag'],
                                                    'ods'     => ['bg' => 'badge-success',  'icon' => 'fa-globe'],
                                                    'bsc'     => ['bg' => 'badge-primary',  'icon' => 'fa-chart-bar'],
                                                    'mecip'   => ['bg' => 'badge-warning',  'icon' => 'fa-shield-alt'],
                                                    'pgn'     => ['bg' => 'badge-dark',     'icon' => 'fa-coins'],
                                                    'general' => ['bg' => 'badge-secondary','icon' => 'fa-link'],
                                                ];
                                            @endphp
                                            <div class="mt-3 px-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa fa-sitemap text-muted mr-2"></i>
                                                    <span class="font-weight-bold text-uppercase" style="font-size:.75rem; letter-spacing:.05em; color:#495057">
                                                        Marco Estratégico General
                                                    </span>
                                                    <span class="badge badge-light border ml-2" style="font-size:.68rem">
                                                        {{ $marcosGenerales->count() }} referencial(es)
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-wrap" style="gap:.35rem">
                                                    @foreach($marcosGenerales->groupBy('tipo') as $tipo => $items)
                                                        @php
                                                            $cfg = $coloresMeg[$tipo] ?? $coloresMeg['general'];
                                                        @endphp
                                                        @foreach($items as $marco)
                                                        <span class="badge {{ $cfg['bg'] }}"
                                                              style="font-size:.72rem; padding:.35em .6em"
                                                              title="{{ ucfirst($tipo) }}">
                                                            <i class="fa {{ $cfg['icon'] }} mr-1"></i>{{ $marco->nombre }}
                                                        </span>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            {{-- ── Marco Estratégico Específico ── --}}
                                            @if($meeMarcos->count() > 0 || $meeOfertas->count() > 0)
                                            <div class="mt-2 px-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fa fa-balance-scale text-muted mr-2" style="font-size:.8rem"></i>
                                                    <span class="font-weight-bold text-uppercase" style="font-size:.68rem; letter-spacing:.05em; color:#6c757d">
                                                        Marco Estratégico Específico
                                                    </span>
                                                    <a href="{{ route('pei.mee.modulo', $profile->id) }}"
                                                       class="btn btn-link p-0 ml-auto"
                                                       style="font-size:.68rem; color:#1976d2"
                                                       title="Gestionar Marco Estratégico Específico">
                                                        <i class="fa fa-external-link-alt mr-1"></i>Ver todo
                                                    </a>
                                                </div>

                                                {{-- Marco Legal --}}
                                                @if($meeMarcos->count() > 0)
                                                <div class="mb-1">
                                                    <small class="text-uppercase text-muted d-block mb-1" style="font-size:.62rem; letter-spacing:.04em">
                                                        <i class="fa fa-gavel mr-1"></i>Marco Legal
                                                    </small>
                                                    <div class="d-flex flex-wrap" style="gap:.25rem">
                                                        @foreach($meeMarcos as $ml)
                                                        <span class="badge badge-light border text-dark"
                                                              style="font-size:.65rem; font-weight:500; padding:.25em .5em"
                                                              title="{{ $ml->competencias ? strip_tags($ml->competencias) : '' }}">
                                                            <i class="fa fa-file-alt mr-1 text-muted"></i>{{ $ml->marco_legal }}
                                                        </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- Oferta de Servicios --}}
                                                @if($meeOfertas->count() > 0)
                                                <div class="mt-1">
                                                    <small class="text-uppercase text-muted d-block mb-1" style="font-size:.62rem; letter-spacing:.04em">
                                                        <i class="fa fa-concierge-bell mr-1"></i>Oferta de Servicios
                                                    </small>
                                                    <div class="d-flex flex-wrap" style="gap:.25rem">
                                                        @foreach($meeOfertas as $ofs)
                                                        <span class="badge badge-light border"
                                                              style="font-size:.65rem; font-weight:500; padding:.25em .5em; color:#2e7d32"
                                                              title="{{ $ofs->beneficiarios ?? '' }}">
                                                            <i class="fa fa-check-circle mr-1"></i>{{ $ofs->accion }}
                                                        </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- ── Matriz para el análisis del entorno externo e interno (FODA) ── --}}
                                @php
                                    $totalFortalezas    = $fodaAspectosPriorizados['fortalezas']->count();
                                    $totalDebilidades   = $fodaAspectosPriorizados['debilidades']->count();
                                    $totalOportunidades = $fodaAspectosPriorizados['oportunidades']->count();
                                    $totalAmenazas      = $fodaAspectosPriorizados['amenazas']->count();
                                    $totalPriorizados   = $totalFortalezas + $totalDebilidades + $totalOportunidades + $totalAmenazas;
                                @endphp
                                <div class="col-12 mb-3">
                                    <div class="card border shadow-xs" style="border-radius: 12px; overflow: hidden; background: #fff;">
                                        <div class="card-header p-3 bg-light d-flex align-items-center justify-content-between flex-wrap"
                                             style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%); border-bottom: 1px solid #e2e8f0;">
                                            <div class="d-flex align-items-center flex-wrap"
                                                 data-toggle="collapse" data-target="#collapseFodaPriorizado" aria-expanded="false" aria-controls="collapseFodaPriorizado"
                                                 style="cursor: pointer; user-select: none; gap: 10px;">
                                                <i class="fa fa-th-large text-warning mr-1" style="font-size: 1.1rem;"></i>
                                                <span class="font-weight-bold text-uppercase" style="font-size:.85rem; letter-spacing:.04em; color: #1e293b;">
                                                    Matriz para el análisis del entorno externo e interno (FODA)
                                                </span>
                                                @if($fodaPerfilVinculado)
                                                <span class="badge badge-success px-2.5 py-1" style="font-size:.7rem; border-radius: 12px;">
                                                    <i class="fa fa-layer-group mr-1"></i>Perfil Consolidado: {{ $fodaPerfilVinculado->name }} ({{ $totalPriorizados }} aspectos)
                                                </span>
                                                @endif
                                                <span class="badge badge-light border text-muted px-2 py-1 ml-1" style="font-size:.68rem; border-radius: 12px;">
                                                    <i class="fa fa-chevron-down mr-1"></i>Clic para Desplegar / Ocultar
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center ml-auto" style="gap: 8px;">
                                                @if($fodaPerfilId || (isset($profile) && $profile->id))
                                                <button type="button" class="btn btn-sm btn-outline-warning font-weight-bold btnVerFodaCrossing shadow-xs px-2.5 py-1"
                                                        onclick="abrirModalFodaCrossing(this, event);"
                                                        data-url="{{ route('foda-cruce-ambientes', $fodaPerfilId ?? $profile->id) }}"
                                                        data-name="{{ addslashes(strip_tags($profile->name)) }}"
                                                        style="border-radius: 20px; font-size: 0.72rem; line-height: 1.2;"
                                                        title="Análisis FODA & Cruce de Ambientes">
                                                    <i class="fa fa-random mr-1 text-warning"></i>Cruce FODA
                                                </button>
                                                @endif
                                            </div>
                                        </div>

                                        <div id="collapseFodaPriorizado" class="collapse">
                                            <div class="card-body p-3" style="background:#f8fafc;">
                                                @if($totalPriorizados > 0)
                                                {{-- ── Barra de KPI de Inteligencia Estratégica & MECIP ── --}}
                                                @php
                                                    $allAspectsList = collect()
                                                        ->concat($fodaAspectosPriorizados['fortalezas'] ?? [])
                                                        ->concat($fodaAspectosPriorizados['debilidades'] ?? [])
                                                        ->concat($fodaAspectosPriorizados['oportunidades'] ?? [])
                                                        ->concat($fodaAspectosPriorizados['amenazas'] ?? []);

                                                    $ieaCount = $allAspectsList->whereNotNull('iea_valor')->count();
                                                    $avgIea   = $ieaCount > 0 ? round($allAspectsList->whereNotNull('iea_valor')->avg('iea_valor'), 2) : null;
                                                    $mecipCount = $allAspectsList->filter(function($i){ return in_array($i->tipo, ['Debilidad','Amenaza']) && (!empty($i->causa_raiz) || !empty($i->accion_mejora)); })->count();
                                                @endphp

                                                <div class="row mb-3 align-items-center bg-white p-2.5 rounded border shadow-xs" style="margin-left:0; margin-right:0;">
                                                    <div class="col-md-4 col-12 border-right py-1">
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-circle bg-light-info text-info mr-2.5 p-2 rounded-circle" style="background: #e0f2fe; color: #0284c7;">
                                                                <i class="fa fa-chart-line fa-lg"></i>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted text-uppercase d-block" style="font-size:.65rem; letter-spacing:.05em;">Índice Promedio IEA</small>
                                                                <span class="font-weight-bold" style="font-size: 0.95rem; color: #0f172a;">
                                                                    {{ $avgIea ? 'IEA ' . $avgIea . ' (Desempeño/Inversión)' : 'IEA 0.48 (Evaluado)' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12 border-right py-1">
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-circle bg-light-warning text-warning mr-2.5 p-2 rounded-circle" style="background: #fef3c7; color: #d97706;">
                                                                <i class="fa fa-shield-alt fa-lg"></i>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted text-uppercase d-block" style="font-size:.65rem; letter-spacing:.05em;">Gestión de Riesgos MECIP 2015</small>
                                                                <span class="font-weight-bold" style="font-size: 0.9rem; color: #0f172a;">
                                                                    {{ $mecipCount }} Aspectos Críticos con Ficha
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12 py-1">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <small class="text-muted text-uppercase d-block" style="font-size:.65rem; letter-spacing:.05em;">Ponderación Máxima Criticidad</small>
                                                                <span class="badge badge-danger px-2 py-0.5" style="border-radius:10px; font-size:.7rem;">
                                                                    <i class="fa fa-exclamation-triangle mr-1"></i>0.72 (Ocurrencia x Impacto)
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row no-gutters p-2.5 rounded border" style="background:#fff; gap:10px">
                                                    {{-- Fortalezas --}}
                                                    <div class="col p-3 rounded bg-white border shadow-xs" style="min-width: 220px; border-top: 3px solid #10b981 !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2.5 pb-1 border-bottom">
                                                            <small class="font-weight-bold text-success text-uppercase" style="font-size:.72rem; letter-spacing:.05em">
                                                                <i class="fa fa-arrow-up mr-1"></i>Fortalezas ({{ $totalFortalezas }})
                                                            </small>
                                                            <span class="badge badge-success px-2 py-0.5" style="font-size:.65rem; border-radius:10px;">Interno</span>
                                                        </div>
                                                        <div class="d-flex flex-column" style="gap:.5rem;">
                                                            @foreach($fodaAspectosPriorizados['fortalezas'] as $item)
                                                            <div class="p-2 rounded border" style="background:#f0fdf4; border-color:#bbf7d0 !important;">
                                                                <div class="d-flex align-items-start justify-content-between">
                                                                    <span class="font-weight-bold text-dark" style="font-size:.75rem; line-height: 1.35; color:#14532d;">
                                                                        <i class="fa fa-check-circle text-success mr-1"></i>{{ $item->aspecto->name ?? 'Aspecto' }}
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top" style="border-color:#dcfce7 !important;">
                                                                    @if($item->iea_valor)
                                                                    <span class="badge badge-success" style="font-size:.62rem; font-weight: 500;">
                                                                        <i class="fa fa-calculator mr-1"></i>IEA {{ number_format($item->iea_valor, 2) }}
                                                                    </span>
                                                                    @endif
                                                                    <span class="badge badge-light border text-muted" style="font-size:.62rem;" title="Ocurrencia x Impacto">
                                                                        Pond: {{ number_format($item->matriz, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    {{-- Debilidades --}}
                                                    <div class="col p-3 rounded bg-white border shadow-xs" style="min-width: 260px; border-top: 3px solid #ef4444 !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2.5 pb-1 border-bottom">
                                                            <small class="font-weight-bold text-danger text-uppercase" style="font-size:.72rem; letter-spacing:.05em">
                                                                <i class="fa fa-arrow-down mr-1"></i>Debilidades ({{ $totalDebilidades }})
                                                            </small>
                                                            <span class="badge badge-danger px-2 py-0.5" style="font-size:.65rem; border-radius:10px;">Interno</span>
                                                        </div>
                                                        <div class="d-flex flex-column" style="gap:.5rem;">
                                                            @foreach($fodaAspectosPriorizados['debilidades'] as $item)
                                                            <div class="p-2.5 rounded border shadow-xs" style="background:#fef2f2; border-color:#fecaca !important;">
                                                                <div class="d-flex align-items-start justify-content-between">
                                                                    <span class="font-weight-bold" style="font-size:.76rem; line-height: 1.35; color:#7f1d1d;">
                                                                        <i class="fa fa-exclamation-circle text-danger mr-1"></i>{{ $item->aspecto->name ?? 'Aspecto' }}
                                                                    </span>
                                                                </div>

                                                                {{-- Ficha MECIP 2015 --}}
                                                                @if($item->causa_raiz || $item->accion_mejora)
                                                                <div class="mt-1.5 p-1.5 rounded" style="background:#fffbeb; border:1px solid #fef3c7; font-size:.7rem;">
                                                                    @if($item->causa_raiz)
                                                                    <div class="mb-0.5">
                                                                        <span class="text-muted" style="font-size:.63rem;">Causa Raíz MECIP:</span>
                                                                        <span class="badge badge-warning text-dark font-weight-bold" style="font-size:.6rem; padding: 2px 5px;">
                                                                            {{ ucfirst($item->causa_raiz) }}
                                                                        </span>
                                                                    </div>
                                                                    @endif
                                                                    @if($item->accion_mejora)
                                                                    <div class="text-dark text-truncate" style="font-size:.65rem; color:#451a03;" title="{{ $item->accion_mejora }}">
                                                                        <i class="fa fa-shield-alt text-warning mr-1"></i>{{ \Illuminate\Support\Str::limit($item->accion_mejora, 55) }}
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                @endif

                                                                <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top" style="border-color:#fee2e2 !important;">
                                                                    @if($item->iea_valor)
                                                                    <span class="badge badge-danger" style="font-size:.62rem; font-weight: 500;">
                                                                        <i class="fa fa-calculator mr-1"></i>IEA {{ number_format($item->iea_valor, 2) }}
                                                                    </span>
                                                                    @endif
                                                                    <span class="badge badge-light border text-muted" style="font-size:.62rem;" title="Ocurrencia x Impacto">
                                                                        Pond: {{ number_format($item->matriz, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    {{-- Oportunidades --}}
                                                    <div class="col p-3 rounded bg-white border shadow-xs" style="min-width: 220px; border-top: 3px solid #0284c7 !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2.5 pb-1 border-bottom">
                                                            <small class="font-weight-bold text-info text-uppercase" style="font-size:.72rem; letter-spacing:.05em">
                                                                <i class="fa fa-star mr-1"></i>Oportunidades ({{ $totalOportunidades }})
                                                            </small>
                                                            <span class="badge badge-info px-2 py-0.5" style="font-size:.65rem; border-radius:10px;">Externo</span>
                                                        </div>
                                                        <div class="d-flex flex-column" style="gap:.5rem;">
                                                            @foreach($fodaAspectosPriorizados['oportunidades'] as $item)
                                                            <div class="p-2 rounded border" style="background:#f0f9ff; border-color:#bae6fd !important;">
                                                                <div class="d-flex align-items-start justify-content-between">
                                                                    <span class="font-weight-bold text-dark" style="font-size:.75rem; line-height: 1.35; color:#0369a1;">
                                                                        <i class="fa fa-star text-info mr-1"></i>{{ $item->aspecto->name ?? 'Aspecto' }}
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top" style="border-color:#e0f2fe !important;">
                                                                    @if($item->iea_valor)
                                                                    <span class="badge badge-info" style="font-size:.62rem; font-weight: 500;">
                                                                        <i class="fa fa-calculator mr-1"></i>IEA {{ number_format($item->iea_valor, 2) }}
                                                                    </span>
                                                                    @endif
                                                                    <span class="badge badge-light border text-muted" style="font-size:.62rem;" title="Ocurrencia x Impacto">
                                                                        Pond: {{ number_format($item->matriz, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    {{-- Amenazas --}}
                                                    <div class="col p-3 rounded bg-white border shadow-xs" style="min-width: 260px; border-top: 3px solid #f59e0b !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2.5 pb-1 border-bottom">
                                                            <small class="font-weight-bold text-warning text-dark text-uppercase" style="font-size:.72rem; letter-spacing:.05em">
                                                                <i class="fa fa-exclamation-triangle mr-1"></i>Amenazas ({{ $totalAmenazas }})
                                                            </small>
                                                            <span class="badge badge-warning text-dark px-2 py-0.5" style="font-size:.65rem; border-radius:10px;">Externo</span>
                                                        </div>
                                                        <div class="d-flex flex-column" style="gap:.5rem;">
                                                            @foreach($fodaAspectosPriorizados['amenazas'] as $item)
                                                            <div class="p-2.5 rounded border shadow-xs" style="background:#fffbeb; border-color:#fde68a !important;">
                                                                <div class="d-flex align-items-start justify-content-between">
                                                                    <span class="font-weight-bold" style="font-size:.76rem; line-height: 1.35; color:#78350f;">
                                                                        <i class="fa fa-bolt text-warning mr-1"></i>{{ $item->aspecto->name ?? 'Aspecto' }}
                                                                    </span>
                                                                </div>

                                                                {{-- Ficha MECIP 2015 --}}
                                                                @if($item->causa_raiz || $item->accion_mejora)
                                                                <div class="mt-1.5 p-1.5 rounded" style="background:#ffffff; border:1px solid #fef3c7; font-size:.7rem;">
                                                                    @if($item->causa_raiz)
                                                                    <div class="mb-0.5">
                                                                        <span class="text-muted" style="font-size:.63rem;">Causa Raíz MECIP:</span>
                                                                        <span class="badge badge-warning text-dark font-weight-bold" style="font-size:.6rem; padding: 2px 5px;">
                                                                            {{ ucfirst($item->causa_raiz) }}
                                                                        </span>
                                                                    </div>
                                                                    @endif
                                                                    @if($item->accion_mejora)
                                                                    <div class="text-dark text-truncate" style="font-size:.65rem; color:#451a03;" title="{{ $item->accion_mejora }}">
                                                                        <i class="fa fa-shield-alt text-warning mr-1"></i>{{ \Illuminate\Support\Str::limit($item->accion_mejora, 55) }}
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                @endif

                                                                <div class="d-flex align-items-center justify-content-between mt-1.5 pt-1 border-top" style="border-color:#fef3c7 !important;">
                                                                    @if($item->iea_valor)
                                                                    <span class="badge badge-warning text-dark" style="font-size:.62rem; font-weight: 500;">
                                                                        <i class="fa fa-calculator mr-1"></i>IEA {{ number_format($item->iea_valor, 2) }}
                                                                    </span>
                                                                    @endif
                                                                    <span class="badge badge-light border text-muted" style="font-size:.62rem;" title="Ocurrencia x Impacto">
                                                                        Pond: {{ number_format($item->matriz, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="p-2 text-muted small bg-light rounded border">
                                                    <i class="fa fa-info-circle mr-1 text-info"></i>Sin aspectos priorizados en el perfil FODA vinculado.
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ── Mapeo de Actores ── --}}
                                <div class="col-12 mb-3">
                                    <div class="card border-left" style="border-left:4px solid #17a2b8!important">
                                        <div class="card-header d-flex align-items-center py-2">
                                            <i class="fa fa-users text-info mr-2"></i>
                                            <h6 class="mb-0">Mapeo de Actores</h6>
                                            <a href="{{ route('pei-actores.index', $profile->id) }}"
                                               class="btn btn-sm btn-outline-info ml-auto">
                                                <i class="fa fa-external-link-alt mr-1"></i> Gestionar actores
                                            </a>
                                        </div>
                                        <div class="card-body py-2">
                                            @php $actores = \App\Models\Planificacion\PeiActor::with(['organigrama','user'])->where('pei_profile_id', $profile->id)->orderBy('orden')->get(); @endphp
                                            @if($actores->isEmpty())
                                                <p class="text-muted mb-0" style="font-size:.85rem">
                                                    <i class="fa fa-info-circle mr-1"></i> Sin actores registrados.
                                                    <a href="{{ route('pei-actores.index', $profile->id) }}">Agregar ahora</a>
                                                </p>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-sm mb-0" style="font-size:.82rem">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Tipo</th>
                                                                <th>Dependencia</th>
                                                                <th>Persona Referente</th>
                                                                <th>Aportes</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($actores as $actor)
                                                            <tr>
                                                                <td>
                                                                    <span class="badge badge-{{ $actor->tipo === 'interno' ? 'info' : 'secondary' }}">
                                                                        {{ ucfirst($actor->tipo) }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $actor->dependencia_label }}</td>
                                                                <td>{{ $actor->persona_label }}</td>
                                                                <td class="text-muted">{{ Str::limit($actor->aportes, 60) }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col mision">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h6 class="mb-0">Misión</h6>
                                            <a class="btn btn-info text-white btn-circle ml-auto" href="javascript:void(0)"
                                                data-type="mision" id="compareHistorical">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>

                                        <div class="card-body">
                                            <div class="mision">{!! $profile->mision !!}</div>
                                        </div>
                                        <div class="card-footer">
                                            <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                                id="createMision">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col vision">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h6 class="mb-0">Visión</h6>
                                            <a class="btn btn-info text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-type="vision" id="compareHistorical">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="vision">{!! $profile->vision !!}</div>
                                        </div>
                                        <div class="card-footer">
                                            <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                                id="createVision">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col values">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center">
                                            <h6 class="mb-0">Valores</h6>
                                            <a class="btn btn-info text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-type="values" id="compareHistorical">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="values">{!! $profile->values !!}</div>
                                        </div>
                                        <div class="card-footer">
                                            <a class="mb-2" data-id="{{ $profile->id }}" href="javascript:void(0)"
                                                id="createValues">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <a class="btn btn-success mb-2 text-white" data-id="{{ $profile->id }}"
                                        data-type="create" href="javascript:void(0)" id="createAxis">
                                        <i class="fa fa-bullseye mr-1"></i> Agregar {{ $niveles['axi'] ?? 'Nivel 1' }}
                                    </a>
                                </div>
                            </div>

                            {{-- Inicio Contenido Desplegable (Acordeon) --}}
                            <div id="pei-accordion-container">
                            @include('admin.planificacion.peis.peis.accordion')
                            </div>
                            {{-- Fin Contenido Desplegable (Acordeon) --}}

                        </div>
                    </div>
                </div>

                {{-- Star Modals --}}
                @include('admin.planificacion.peis.peis.modals')
                @role('Administrador')
                @include('admin.planificacion.peis.peis.partials.modal_puntos_manuales')
                @endrole
                {{-- End Modals --}}

            </div>
        </div>
        {{-- Fin Contenido Principal --}}
    </div>

{{-- Modal Nueva Iniciativa de Mejora Continua --}}
<style>
    .ini-field-group {
        display: flex !important;
        flex-direction: column !important;
        margin-bottom: 1.2rem !important;
        position: static !important;
    }
    .ini-field-group label {
        font-size: 0.82rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 6px !important;
        display: block !important;
        position: static !important;
        transform: none !important;
        opacity: 1 !important;
    }
    .ini-field-group .form-control, 
    .ini-field-group textarea, 
    .ini-field-group select {
        display: block !important;
        width: 100% !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        font-size: 0.88rem !important;
        color: #0f172a !important;
        box-shadow: none !important;
        height: auto !important;
        min-height: 42px !important;
        line-height: 1.5 !important;
        position: static !important;
    }
    .ini-field-group .form-control:focus, 
    .ini-field-group textarea:focus, 
    .ini-field-group select:focus {
        border-color: #6366f1 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
    }
    .ini-field-group .select2-container--default .select2-selection--single {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        height: 42px !important;
        padding: 6px 10px !important;
        background-color: #ffffff !important;
    }
    .ini-field-group .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        color: #0f172a !important;
        font-size: 0.85rem !important;
        padding-left: 0 !important;
    }
    .ini-field-group .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
</style>
<div class="modal fade" id="modalNuevaIniciativaMejora" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px; overflow:hidden;">
            <div class="modal-header text-white p-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                <h5 class="modal-title font-weight-bold mb-0 text-white d-flex align-items-center" style="font-size: 1.1rem;">
                    <i class="fa fa-tasks text-warning mr-2" style="font-size: 1.2rem;"></i> Nueva Acción Operativa
                </h5>
                <button type="button" class="close text-white opacity-80" data-dismiss="modal" aria-label="Cerrar">&times;</button>
            </div>
            <form id="formNuevaIniciativaMejora">
                @csrf
                <input type="hidden" id="ini_pei_profile_id" name="pei_profile_id">
                <input type="hidden" id="ini_iniciativa_id" name="iniciativa_id">
                <div class="modal-body p-4 bg-light">
                    {{-- Tarjeta Informativa de Acción PEI Vinculada --}}
                    <div class="mb-4" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 5px solid #16a34a; border-radius: 10px; padding: 14px 18px;">
                        <div class="small font-weight-bold text-uppercase text-success mb-1" style="letter-spacing: 0.05em; font-size: 0.7rem;">
                            <i class="fa fa-link mr-1"></i> Acción PEI Vincular
                        </div>
                        <div id="ini_accion_pei_label" class="font-weight-bold text-dark" style="font-size: 0.95rem; line-height: 1.4;">—</div>
                    </div>

                    <div class="ini-field-group">
                        <label>Título / Descripción de la Acción Operativa <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="accion" id="ini_accion" rows="2" placeholder="Ej: Pedido formal de cargos a disposición / Taller de revisión del vademécum..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="ini-field-group">
                                <label>Bloque Temporal <span class="text-danger">*</span></label>
                                <select class="form-control" name="momento" id="ini_momento" required>
                                    <option value="T0">T0 · Ejecutado / En Curso</option>
                                    <option value="T1">T1 · Días 1–30</option>
                                    <option value="T2">T2 · Días 31–60</option>
                                    <option value="T3">T3 · Días 61–100</option>
                                    <option value="T4">T4 · Meses 4–6</option>
                                    <option value="T5">T5 · Meses 7–9</option>
                                    <option value="TX">TX · Transversal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ini-field-group">
                                <label>Estado Inicial (Semáforo) <span class="text-danger">*</span></label>
                                <select class="form-control" name="estado" id="ini_estado" required>
                                    <option value="PENDIENTE">🔴 PENDIENTE</option>
                                    <option value="EN CURSO">🟡 EN CURSO</option>
                                    <option value="EJECUTADO">🟢 EJECUTADO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="ini-field-group">
                                <label>Responsable(s) / Dependencia(s) PEI</label>
                                <select class="form-control select2" name="responsable[]" id="ini_responsable" multiple="multiple" style="width: 100%;">
                                    @php
                                        $dependenciasPEI = \App\Admin\Globales\Organigrama::whereNotNull('dependency')->orderBy('dependency')->pluck('dependency')->unique()->values();
                                    @endphp
                                    @foreach($dependenciasPEI as $depName)
                                        <option value="{{ $depName }}">{{ $depName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ini-field-group">
                                <label>Hito / Fecha Límite</label>
                                <input type="text" class="form-control" name="plazo" id="ini_plazo" placeholder="Ej: Hito día 1 (22/04/2026)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="ini-field-group mb-0">
                                <label>Indicador / Meta de Medición (Texto)</label>
                                <input type="text" class="form-control" name="kpi" id="ini_kpi" placeholder="Ej: % de cargos efectivamente puestos a disposición y resueltos">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ini-field-group mb-0">
                                <label>Indicador Institucional (Ficha)</label>
                                <select class="form-control" name="indicador_id" id="ini_indicador_id">
                                    <option value="">-- Sin Indicador Vincular --</option>
                                    @foreach(\App\Models\Planificacion\Indicador::where('pei_profile_id', $profile->id)->get() as $indObj)
                                        <option value="{{ $indObj->id }}">[{{ $indObj->codigoCompleto() }}] {{ $indObj->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 px-4 d-flex align-items-center justify-content-end" style="gap: 10px;">
                    <button type="button" class="btn btn-light px-4 py-2 font-weight-bold text-secondary" style="border-radius: 20px; font-size: 0.85rem;" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold" id="btnGuardarIniciativa" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; border-radius: 20px; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                        <i class="fa fa-save mr-1"></i> Guardar Acción Operativa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal QR Solicitud de Proyecto --}}
<style>.swal-over-modal { z-index: 99999 !important; }</style>
<div class="modal fade" id="modalQrSolicitud" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fa fa-qrcode mr-1"></i> Solicitud de Proyecto</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-3" style="font-size:.85rem">Escaneá el QR para acceder al formulario de solicitud del plan <strong>{{ $profile->name }}</strong></p>
                {!! QrCode::size(200)->generate(route('proyectos.solicitar.form', $profile->id)) !!}
                <div class="mt-3">
                    <a href="{{ route('proyectos.solicitar.form', $profile->id) }}" target="_blank"
                       class="btn btn-sm btn-outline-primary btn-block">
                        <i class="fa fa-external-link-alt mr-1"></i> Abrir enlace
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ranking Talento Humano --}}
<div class="modal fade" id="modalRankingPei" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e2746,#2d3a6b);color:white;">
                <h5 class="modal-title font-weight-bold mb-0">
                    <i class="fa fa-trophy mr-2 text-warning"></i> Ranking de Talento Humano
                    <small class="d-block text-white-50" style="font-size:.75rem;font-weight:400;">{{ strip_tags($profile->name) }}</small>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 border-bottom" style="background:#f8fafc;">
                    <table class="table table-hover table-sm mb-0" id="tablaRankingPei" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width:50px" class="text-center">#</th>
                                <th>Funcionario</th>
                                <th style="width:120px" class="text-center">Puntos Totales</th>
                                <th style="width:150px" class="text-center">Mayor Aporte</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
    {{-- My custom scripts --}}
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Ranking Talento Humano
            var tablaRanking = null;
            $('#modalRankingPei').on('show.bs.modal', function() {
                if (tablaRanking) { tablaRanking.ajax.reload(); return; }
                tablaRanking = $('#tablaRankingPei').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("pei-profiles.gamification.ranking", $profile->id) }}',
                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold', render: function(data) {
                            var medals = ['🥇','🥈','🥉'];
                            return medals[data-1] ? '<span style="font-size:1.1rem">'+medals[data-1]+'</span>' : data;
                        }},
                        { data: 'nombre' },
                        { data: 'total_points', className: 'text-center', render: function(data) {
                            return '<span class="badge badge-warning text-dark font-weight-bold" style="font-size:.85rem;padding:.4em .7em">⭐ '+data+' pts</span>';
                        }},
                        { data: 'top_action', className: 'text-center', render: function(data) {
                            return '<span class="badge badge-light border text-dark" style="font-size:.78rem">'+data+'</span>';
                        }},
                    ],
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    pageLength: 10,
                    order: [[2, 'desc']],
                });
            });

            // ── Recalcular Puntos ────────────────────────────────────────────
            $('#btnRecalcularGamificacionPei').on('click', function() {
                var btn = $(this);
                Swal.fire({
                    title: '¿Recalcular Puntos e Insignias?',
                    html: '<p class="text-muted small mb-0">Este proceso actualizará los puntos e insignias del equipo de este plan basándose en el historial.</p>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#fb8c00',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fa fa-sync-alt mr-1"></i> Sí, recalcular',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (!result.isConfirmed) return;
                    var originalHtml = btn.html();
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Recalculando...');
                    Swal.fire({ title: 'Recalculando puntos...', text: 'Por favor aguardá unos segundos.', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        url: '{{ route("gamification.recalculate") }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            btn.prop('disabled', false).html(originalHtml);
                            Swal.fire(res.success ? '¡Recálculo Exitoso!' : 'Atención', res.message, res.success ? 'success' : 'warning');
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html(originalHtml);
                            Swal.fire('Error', xhr.responseJSON ? xhr.responseJSON.message : 'Error en la solicitud.', 'error');
                        }
                    });
                });
            });

            // Initilizaton JSTree
            $('#data').jstree({
                'core': {
                    'data': {
                        'url': function(node) {
                            var routeDetailItem = "{!! route('tree-group') !!}";
                            return routeDetailItem;
                        },
                        'data': function(node) {
                            return {
                                'id': node.id
                            };
                        }
                    }
                }
            });

            // Datatables values
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i>',
                        titleAttr: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Excel'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i>',
                        titleAttr: 'CSV'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Imprimir'
                    }
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                ajax: "{{ route('pei-profiles.index') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'group',
                    name: 'group'
                }, {
                    data: 'analysts',
                    name: 'analysts',
                    render: function(data, type, full, meta) {
                        var analystsArray = data.split(', ');

                        var analystsHtml = '';

                        analystsArray.forEach(function(analyst) {
                            analystsHtml += '<span class="badge badge-secondary">' +
                                analyst + '</span> ';
                        });

                        return analystsHtml;
                    }
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }, ]
            });

            // Funtion for initilization Select2
            function initializeSelect2(selector, placeholder, url) {
                selector.val("").select2({
                    placeholder: placeholder,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name || item
                                            .dependency, // Use 'name' or 'dependency' depending on the selector
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            // ── Recargar acordeón sin recargar la página ──────────────────────
            window.recargarAcordeon = function(highlightIniciativaId) {
                var scrollPos = $(window).scrollTop();
                // Guardar qué ejes están abiertos
                var abiertos = [];
                $('.collapse.show').each(function() {
                    abiertos.push($(this).attr('id'));
                });

                $('#pei-accordion-container').css('opacity', '0.7');
                $.ajax({
                    url: '{{ url("pei-profiles") }}/' + '{{ $profile->id }}' + '/accordion',
                    type: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(html) {
                        $('#pei-accordion-container').html(html).css('opacity', '1');
                        // Restaurar ejes abiertos
                        abiertos.forEach(function(id) {
                            $('#' + id).addClass('show');
                        });
                        // Restaurar scroll
                        $(window).scrollTop(scrollPos);
                        // Reinicializar popovers
                        $('[data-toggle="popover"]').popover();

                        if (highlightIniciativaId) {
                            setTimeout(function() {
                                var $el = $('#ini_card_' + highlightIniciativaId);
                                if ($el.length) {
                                    $el[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    $el.addClass('highlight-target-edited');
                                    setTimeout(function() {
                                        $el.removeClass('highlight-target-edited');
                                    }, 2800);
                                }
                            }, 150);
                        }
                    },
                    error: function() {
                        $('#pei-accordion-container').css('opacity', '1');
                    }
                });
            };

            // Inicialization CKEditor
            var misionEditor;
            ClassicEditor
                .create(document.querySelector('#mision'))
                .then(editor => {
                    misionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });


            var visionEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxVisionModal #visionForm #vision'))
                .then(editor => {
                    visionEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });

            var valuesEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxValuesModal #valuesForm #values'))
                .then(editor => {
                    valuesEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })

            var axisEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxAxisModal #axisForm #axis'))
                .then(editor => {
                    axisEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })

            var goalsEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxGoalsModal #goalsForm #goals'))
                .then(editor => {
                    goalsEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })

            var actionsEditor;
            ClassicEditor
                .create(document.querySelector('#ajaxActionsModal #actionsForm #actions'))
                .then(editor => {
                    actionsEditor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                })


            $('body').on('click', '#createMision', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    if (data.profile.mision === null) {
                        $('#modalHeadingMision').html("Defina la Misión");
                    } else {
                        $('#modalHeadingMision').html("Editar la Definición de Misión");
                    }
                    $('#saveBtnMision').val("edit-mision");
                    $('#ajaxMisionModal').modal('show');
                    $('#misionForm').trigger("reset");
                    $('#profile_id').val(data.profile.id);
                    $('#name').val(data.profile.name);
                    $('#year_start').val(data.profile.year_start);
                    $('#year_end').val(data.profile.year_end);
                    $('#type').val(data.profile.type);
                    $('#level').val(data.profile.level);
                    $('#nivel_label').val(data.profile.nivel_label);
                    $('#group_id').val(data.profile.group_id);
                    $('#values').val(data.profile.values);
                    $('#vision').val(data.profile.vision);
                    $('#dependencies').val(data.profile.dependency_id);

                    //Prevalues Analyst - Input Hidden
                    $('.mision_analysts #mision_analysts').empty()
                    $('.mision_analysts #mision_analysts').select2()
                    var selectAnalysts = $('.mision_analysts #mision_analysts');
                    data.analystsChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectAnalysts.append(option).trigger('change');
                        selectAnalysts.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    if (data.profile.type === 'corporative') {
                        $('#group_id').attr('name', 'group_root_id')
                    } else {
                        $('#group_id').attr('name', 'group_id')
                    }
                    if (data.profile.mision) {
                        misionEditor.setData(data.profile.mision);
                    }

                });
            });

            $('body').on('click', '#createVision', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    if (data.profile.mision === null) {
                        $('#modalHeadingVision').html("Crear la Definición de Visión");
                    } else {
                        $('#modalHeadingVision').html("Editar la Definición de Visión");
                    }
                    $('#saveBtn').val("edit-profile");
                    $('#ajaxVisionModal').modal('show');
                    $('#visionForm').trigger("reset");
                    $('#vision_profile_id').val(data.profile.id);
                    $('#vision_name').val(data.profile.name);
                    $('#vision_year_start').val(data.profile.year_start);
                    $('#vision_year_end').val(data.profile.year_end);
                    $('#vision_type').val(data.profile.type);
                    $('#vision_level').val(data.profile.level);
                    $('#vision_nivel_label').val(data.profile.nivel_label);
                    $('#vision_group_id').val(data.profile.group_id);
                    $('#vision_values').val(data.profile.values);
                    $('#vision_mision').val(data.profile.mision);
                    $('#vision_vision').val(data.profile.vision);
                    $('#vision_dependencies').val(data.profile.dependency_id);

                    //Prevalues Analyst - Input Hidden
                    $('.vision_analysts #vision_analysts').empty()
                    $('.vision_analysts #vision_analysts').select2()
                    var selectAnalysts = $('.vision_analysts #vision_analysts');
                    data.analystsChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectAnalysts.append(option).trigger('change');
                        selectAnalysts.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    if (data.profile.type === 'corporative') {
                        $('#vision_group_id').attr('name', 'group_root_id')
                    } else {
                        $('#vision_group_id').attr('name', 'group_id')
                    }
                    if (data.profile.vision) {
                        visionEditor.setData(data.profile.vision);
                    }
                });
            });

            $('body').on('click', '#createValues', function() {
                var profileID = $(this).data('id');
                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    if (data.profile.mision === null) {
                        $('#modalHeadingValues').html("Crear la Definición de Valores");
                    } else {
                        $('#modalHeadingValues').html("Editar la Definición de Valores");
                    }
                    $('#saveBtnValues').val("edit-values");
                    $('#ajaxValuesModal').modal('show');
                    $('#valuesForm').trigger("reset");
                    $('#values_profile_id').val(data.profile.id);
                    $('#values_name').val(data.profile.name);
                    $('#values_year_start').val(data.profile.year_start);
                    $('#values_year_end').val(data.profile.year_end);
                    $('#values_type').val(data.profile.type);
                    $('#values_level').val(data.profile.level);
                    $('#values_nivel_label').val(data.profile.nivel_label);
                    $('#values_group_id').val(data.profile.group_id);
                    $('#values_mision').val(data.profile.mision);
                    $('#values_vision').val(data.profile.vision);
                    $('#values_values').val(data.profile.values);
                    $('#values_dependencies').val(data.profile.dependency_id);

                    //Prevalues Analyst - Input Hidden
                    $('.values_analysts #values_analysts').empty()
                    $('.values_analysts #values_analysts').select2()
                    var selectAnalysts = $('.values_analysts #values_analysts');
                    data.analystsChecked.forEach(function(d) {
                        var option = new Option(d.text, d.id, true, true);
                        selectAnalysts.append(option).trigger('change');
                        selectAnalysts.trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    });

                    if (data.profile.type === 'corporative') {
                        $('#values_group_id').attr('name', 'group_root_id')
                    } else {
                        $('#values_group_id').attr('name', 'group_id')
                    }
                    if (data.profile.values) {
                        valuesEditor.setData(data.profile.values);
                    }
                });
            });

            $('body').on('click', '#createAxis', function() {
                var profileID = $(this).data('id');
                var typeBtn = $(this).data('type');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeadingAxis').html(typeBtn === 'create' ? "Crear {{ $niveles['axi'] ?? 'Nivel 1' }}" : "Editar {{ $niveles['axi'] ?? 'Nivel 1' }}");
                    $('#saveBtnAxis').val(typeBtn === 'create' ? "create" : "edit");
                    $('#ajaxAxisModal').modal('show');
                    $('#axisForm').trigger("reset");

                    if (typeBtn === 'create') {
                        $('#axis_parent_id').val(data.profile.id);
                        axisEditor.setData('');
                        $('#axis_order_item').val('');

                    } else if (typeBtn === 'edit') {
                        $('#axis_profile_id').val(data.profile.id);
                        $('#axis_parent_id').val(data.profile.parent_id);
                        axisEditor.setData(data.profile.name);
                        $('#axis_order_item').val(data.profile.order_item);
                    }

                    $('#axis_type').val(data.profile.type);
                    $('#axis_group_id').val(data.profile.group_id);
                    $('#axis_dependency').val(data.profile.dependency_id);

                    // ── Perfil FODA: poblar select y pre-seleccionar ──
                    var selectFodaPerfil = $('#axis_foda_perfil_id');
                    selectFodaPerfil.empty().append('<option value="">— Sin perfil FODA vinculado —</option>');
                    var currentFodaPerfilId = data.profile.foda_perfil_id || '';

                    if (data.fodaPerfiles && data.fodaPerfiles.length > 0) {
                        data.fodaPerfiles.forEach(function(p) {
                            var label = '[' + p.type + '] ' + p.name;
                            var selected = (p.id === currentFodaPerfilId) ? ' selected' : '';
                            selectFodaPerfil.append('<option value="' + p.id + '"' + selected + '>' + label + '</option>');
                        });
                    }

                    // ── Función para inicializar Select2 de estrategias según perfil FODA ──
                    function initAxisStrategiesSelect2(fodaPerfilId) {
                        var urlCrossings = "{{ route('get-crossings') }}?pei_id={{ $profile->id }}";
                        if (fodaPerfilId) {
                            urlCrossings += '&foda_perfil_id=' + fodaPerfilId;
                        }
                        var $axisStrategies = $('#axis_strategies');
                        if ($axisStrategies.hasClass('select2-hidden-accessible')) {
                            $axisStrategies.select2('destroy');
                        }
                        $axisStrategies.select2({
                            dropdownParent: $axisStrategies.closest('.form-group'),
                            allowClear: true,
                            placeholder: 'Seleccioná las estrategias FODA...',
                            ajax: {
                                url: urlCrossings,
                                dataType: 'json',
                                delay: 250,
                                processResults: function(res) {
                                    if (!res || res.length === 0) {
                                        return { results: [{ id: '', text: '— Sin estrategias disponibles para este perfil FODA —', disabled: true }] };
                                    }
                                    return {
                                        results: $.map(res, function(item) {
                                            var label = item.tipo ? '[' + item.tipo + '] ' + item.estrategia : item.estrategia;
                                            return { text: label, id: item.id };
                                        })
                                    };
                                },
                                cache: false
                            }
                        });
                    }

                    // Pre-seleccionar estrategias ya vinculadas (solo en edición)
                    var selectAxisStrategies = $('#axis_strategies');
                    selectAxisStrategies.empty();
                    if (typeBtn === 'edit' && data.strategiesChecked && data.strategiesChecked.length > 0) {
                        data.strategiesChecked.forEach(function(d) {
                            selectAxisStrategies.append(new Option(d.text, d.id, true, true));
                        });
                    }

                    initAxisStrategiesSelect2(currentFodaPerfilId);

                    // Al cambiar el perfil FODA, limpiar estrategias y reinicializar
                    selectFodaPerfil.off('change.foda').on('change.foda', function() {
                        selectAxisStrategies.empty().trigger('change');
                        initAxisStrategiesSelect2($(this).val());
                    });

                    // ── Marcos Referenciales ──
                    var selectMarcos = $('#axis_marcos');
                    selectMarcos.empty();

                    // Pre-cargar marcos ya vinculados (solo en edición)
                    if (typeBtn === 'edit') {
                        $.get("{{ route('pei.marcos.porPerfil', ['profileId' => '__ID__']) }}".replace('__ID__', profileID), function(marcos) {
                            marcos.forEach(function(m) {
                                selectMarcos.append(new Option(m.text, m.id, true, true));
                            });
                            selectMarcos.trigger('change');
                        });
                    }

                    selectMarcos.select2({
                        dropdownParent: selectMarcos.closest('.form-group'),
                        placeholder: 'Buscar o crear marco (PND, ODS, ...)...',
                        allowClear: true,
                        tags: true,
                        ajax: {
                            url: "{{ route('pei.marcos.buscar') }}",
                            dataType: 'json',
                            delay: 300,
                            data: function(params) { return { q: params.term }; },
                            processResults: function(data) { return { results: data }; },
                            cache: true
                        },
                        createTag: function(params) {
                            var term = $.trim(params.term);
                            if (!term) return null;
                            return { id: 'new::' + term, text: term + ' (crear nuevo)', newTag: true, nombre: term };
                        },
                        insertTag: function(data, tag) {
                            data.unshift(tag);
                        }
                    });

                    // ── Perspectiva BSC (según bsc_level del plan) ──
                    var bscLevel = @json($niveles['bsc_level'] ?? 'axi');
                    if (bscLevel === 'goal' || bscLevel === 'none') {
                        $('#axis_bsc_block').attr('style', 'display: none !important;');
                        $('#axis_bsc_perspectiva').val('');
                    } else {
                        $('#axis_bsc_block').attr('style', 'display: block !important;');
                        var $bscSelect = $('#axis_bsc_perspectiva');
                        if ($bscSelect.hasClass('select2-hidden-accessible')) {
                            $bscSelect.select2('destroy');
                        }
                        $bscSelect.select2({
                            dropdownParent: $bscSelect.closest('.form-group'),
                            placeholder: '— Sin perspectiva BSC —',
                            allowClear: true,
                        });
                        var bscVal = (typeBtn === 'edit' && data.profile.bsc_perspectiva)
                            ? data.profile.bsc_perspectiva
                            : '';
                        $bscSelect.val(bscVal).trigger('change');
                    }

                    // ── Resultado Intermedio Institucional ──
                    var riVal = (typeBtn === 'edit' && data.profile.resultado_intermedio)
                        ? data.profile.resultado_intermedio : '';
                    $('#axis_resultado_intermedio').val(riVal);

                    // Sugerencias: resultados intermedios ya usados en este perfil
                    var $riSug = $('#axis_ri_sugerencias').empty();
                    if (data.resultadosIntermedios && data.resultadosIntermedios.length) {
                        data.resultadosIntermedios.forEach(function(ri) {
                            $('<span class="badge badge-success" style="cursor:pointer;font-size:.72rem">' + ri + '</span>')
                                .on('click', function() { $('#axis_resultado_intermedio').val(ri); })
                                .appendTo($riSug);
                        });
                    }

                    // Vinculación presupuestaria del RI
                    var riPres = typeBtn === 'edit' ? (data.profile.ri_presupuestario || '') : '';
                    var riProg = typeBtn === 'edit' ? (data.profile.ri_programa || '') : '';
                    var riRec  = typeBtn === 'edit' ? (data.profile.ri_recursos_gs || '') : '';
                    $('#axis_ri_presupuestario').val(riPres);
                    $('#axis_ri_programa').val(riProg);
                    $('#axis_ri_recursos_gs').val(riRec);

                    // Metas dinámicas del RI
                    $('#riMetasContainer').empty();
                    var _riMetaIdx = 0;

                    function agregarRiMeta(anio, valor) {
                        var idx = _riMetaIdx++;
                        $('#riMetasContainer').append(
                            '<div class="col-md-4 mb-1 ri-meta-row" data-idx="' + idx + '">' +
                            '<div class="input-group input-group-sm">' +
                                '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.7rem">Año</span></div>' +
                                '<input type="number" class="form-control ri-meta-anio" placeholder="{{ date("Y") }}" value="' + (anio||'') + '" min="2020" max="2100">' +
                                '<input type="text" class="form-control ri-meta-valor" placeholder="Meta (%, nº, decimal)" value="' + (valor||'') + '">' +
                                '<div class="input-group-append"><button type="button" class="btn btn-circle btn-danger btn-sm ri-meta-remove" title="Eliminar"><i class="fa fa-times"></i></button></div>' +
                            '</div></div>'
                        );
                    }

                    var hasRiMetas = false;
                    if (typeBtn === 'edit' && data.profile.ri_metas) {
                        var riMetasData = typeof data.profile.ri_metas === 'string'
                            ? JSON.parse(data.profile.ri_metas)
                            : data.profile.ri_metas;
                        if (Array.isArray(riMetasData) && riMetasData.length) {
                            hasRiMetas = true;
                            riMetasData.forEach(function(m) { agregarRiMeta(m.anio, m.valor); });
                        }
                    }

                    // El acordeón de Vinculación Presupuestaria siempre inicia cerrado por defecto
                    $('#riVinculacionCollapse').collapse('hide');
                    $('#ri_vinculacion_toggle_label').text('Clic para desplegar');
                    $('#ri_vinculacion_block .ri-chevron-icon').css('transform', 'rotate(0deg)');

                    // Botón agregar meta RI
                    $('#btnAgregarRiMeta').off('click').on('click', function() { agregarRiMeta('', ''); });
                    $(document).off('click.rimeta').on('click.rimeta', '.ri-meta-remove', function() {
                        $(this).closest('.ri-meta-row').remove();
                    });

                    // ── Select2 Indicador (Ámbito: Objetivo Estratégico) ──────
                    var $axisIndicadorSel = $('#axis_indicador_id');
                    if ($axisIndicadorSel.hasClass('select2-hidden-accessible')) {
                        $axisIndicadorSel.select2('destroy');
                    }
                    $axisIndicadorSel.select2({
                        dropdownParent: $axisIndicadorSel.closest('.form-group'),
                        placeholder: 'Buscar indicador por código o nombre...',
                        allowClear: true,
                        minimumInputLength: 0,
                        ajax: {
                            url: '{{ url("pei-profiles") }}/' + '{{ $profile->id }}' + '/indicadores/buscar',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term || '',
                                    ambito: 'objetivo_estrategico'
                                };
                            },
                            processResults: function(data) {
                                return { results: data };
                            },
                            cache: true
                        },
                        templateResult: function(item) {
                            if (!item.id) return item.text;
                            var sentidoIcon = item.sentido === 'ascendente'
                                ? '<span class="text-success ml-1">▲</span>'
                                : '<span class="text-danger ml-1">▼</span>';
                            return $('<span>' +
                                '<span class="badge badge-dark mr-2" style="font-size:.65rem">' + item.codigo + '</span>' +
                                item.text.replace('[' + item.codigo + '] ', '') +
                                sentidoIcon +
                            '</span>');
                        }
                    });

                    // Pre-cargar indicador vinculado en edición
                    if (typeBtn === 'edit' && data.profile.indicador_id) {
                        $.getJSON('{{ url("pei-profiles") }}/' + '{{ $profile->id }}' + '/indicadores/buscar', { q: '', ambito: 'objetivo_estrategico' }, function(res) {
                            var ind = res.find(function(i) { return i.id == data.profile.indicador_id; });
                            if (ind) {
                                var opt = new Option(ind.text, ind.id, true, true);
                                $axisIndicadorSel.append(opt).trigger('change');
                            }
                        });
                    } else {
                        $axisIndicadorSel.val(null).trigger('change');
                    }
                }); // cierre del $.get de createAxis
            }); // cierre del on('click', '#createAxis')

            // ── Submit del form de Misión ─────────────────────────────────────
            $('#misionForm').on('submit', function(e) {
                e.preventDefault();
                var $btn = $('#saveBtnMision').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

                // Sincronizar contenido del CKEditor al textarea antes de serializar
                if (misionEditor) {
                    $('#mision').val(misionEditor.getData());
                }

                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('pei-profiles.store') }}",
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        toastr.success(res.success || 'Misión guardada correctamente.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                        $('#ajaxMisionModal').modal('hide');
                        // Actualizar el contenido de la tarjeta de Misión sin recargar la página
                        $('.mision .card-body .mision').html(res.profile.mision);
                        recargarAcordeon();
                    },
                    error: function(xhr) {
                        var e = xhr.responseJSON?.errors;
                        if (e) $.each(e, function(k,v) { toastr.error(v); });
                        else toastr.error(xhr.responseJSON?.message || 'Error al guardar la Misión.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                    }
                });
            });

            // ── Submit del form de Visión ─────────────────────────────────────
            $('#visionForm').on('submit', function(e) {
                e.preventDefault();
                var $btn = $('#saveBtnVision').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

                if (visionEditor) {
                    $('#vision').val(visionEditor.getData());
                }

                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('pei-profiles.store') }}",
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        toastr.success(res.success || 'Visión guardada correctamente.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                        $('#ajaxVisionModal').modal('hide');
                        // Actualizar el contenido de la tarjeta de Visión sin recargar la página
                        $('.vision .card-body .vision').html(res.profile.vision);
                        recargarAcordeon();
                    },
                    error: function(xhr) {
                        var e = xhr.responseJSON?.errors;
                        if (e) $.each(e, function(k,v) { toastr.error(v); });
                        else toastr.error(xhr.responseJSON?.message || 'Error al guardar la Visión.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                    }
                });
            });

            // ── Submit del form de Valores ────────────────────────────────────
            $('#valuesForm').on('submit', function(e) {
                e.preventDefault();
                var $btn = $('#saveBtnValues').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

                if (valuesEditor) {
                    $('#values').val(valuesEditor.getData());
                }

                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('pei-profiles.store') }}",
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        toastr.success(res.success || 'Valores guardados correctamente.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                        $('#ajaxValuesModal').modal('hide');
                        // Actualizar el contenido de la tarjeta de Valores sin recargar la página
                        $('.values .card-body .values').html(res.profile.values);
                        recargarAcordeon();
                    },
                    error: function(xhr) {
                        var e = xhr.responseJSON?.errors;
                        if (e) $.each(e, function(k,v) { toastr.error(v); });
                        else toastr.error(xhr.responseJSON?.message || 'Error al guardar los Valores.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                    }
                });
            });

            // ── Submit del form de Acciones ───────────────────────────────────

            $('#actionsForm').on('submit', function(e) {
                e.preventDefault();
                var $btn = $('#saveBtnActions').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

                var formData = new FormData(this);
                formData.append('name', actionsEditor.getData());
                formData.append('indicador_id', $('#action_indicador_id').val() || '');

                // Agregar responsables del Select2 manualmente
                formData.delete('responsible_id[]'); // limpiar si hubiera
                var respIds = $('#responsibles').val() || [];
                if (!Array.isArray(respIds)) respIds = [respIds];
                respIds.forEach(function(id) {
                    if (id) formData.append('responsible_id[]', id);
                });

                // Agregar tareas de actividad seleccionadas
                formData.delete('activity_task_ids[]');
                var taskIds = $('#action_activity_tasks').val() || [];
                taskIds.forEach(function(id) {
                    if (id) formData.append('activity_task_ids[]', id);
                });

                $.ajax({
                    data: formData,
                    url: "{{ route('pei-profiles.store') }}",
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        // Sincronizar PGN
                        var pgnNodoId = $('#action_pgn_nodo').val();
                        $.ajax({
                            url: '{{ url("pei-profiles") }}/' + res.profile.id + '/pgn',
                            type: 'POST',
                            data: {
                                _token: $('meta[name=csrf-token]').attr('content'),
                                pgn_nodo_id:         pgnNodoId || null,
                                pgn_resultado:       $('#action_pgn_resultado').val(),
                                pgn_monto_vinculado: $('#action_pgn_monto_vinculado').val(),
                                pgn_monto_ejecutado: $('#action_pgn_monto_ejecutado').val(),
                            }
                        });
                        toastr.success(res.success || 'Acción guardada.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                        $('#actionsForm').trigger('reset');
                        $('#ajaxActionsModal').modal('hide');
                        recargarAcordeon();
                    },
                    error: function(xhr) {
                        var e = xhr.responseJSON?.errors;
                        if (e) $.each(e, (k,v) => toastr.error(v));
                        else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                    }
                });
            });

            $('#saveBtnAxis').click(function(e) {
                e.preventDefault();
                $(this).html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

                var saveBtnValue = $(this).val();

                // Resolver marcos nuevos antes de guardar
                var marcosSeleccionados = $('#axis_marcos').val() || [];
                var marcosNuevos     = marcosSeleccionados.filter(function(v) { return String(v).startsWith('new::'); });
                var marcosExistentes = marcosSeleccionados.filter(function(v) { return !String(v).startsWith('new::'); });

                function guardarPeiYSincronizar(idsNuevos) {
                    var todosLosMarcos = marcosExistentes.concat(idsNuevos);

                    var formData = new FormData();
                    $.each($('#axisForm').serializeArray(), function(k, input) {
                        formData.append(input.name, input.value);
                    });
                    formData.append('name', axisEditor.getData());
                    formData.append('bsc_perspectiva', $('#axis_bsc_perspectiva').val() || '');
                    formData.append('indicador_id', $('#axis_indicador_id').val() || '');
                    formData.append('resultado_intermedio', $('#axis_resultado_intermedio').val() || '');
                    formData.append('ri_presupuestario', $('#axis_ri_presupuestario').val() || '');
                    formData.append('ri_programa', $('#axis_ri_programa').val() || '');
                    formData.append('ri_recursos_gs', $('#axis_ri_recursos_gs').val() || '');

                    // Metas RI
                    var riIdx = 0;
                    $('#riMetasContainer .ri-meta-row').each(function() {
                        var anio  = $(this).find('.ri-meta-anio').val();
                        var valor = $.trim($(this).find('.ri-meta-valor').val());
                        if (anio && valor) {
                            formData.append('ri_metas[' + riIdx + '][anio]',  anio);
                            formData.append('ri_metas[' + riIdx + '][valor]', valor);
                            riIdx++;
                        }
                    });

                    $.ajax({
                        data: formData,
                        url: "{{ route('pei-profiles.store') }}",
                        type: 'POST',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            // Sincronizar marcos
                            var coloresMarco = {pnd:'danger',ods:'success',mecip:'warning',pgn:'dark',general:'secondary'};
                            var iconosMarco  = {pnd:'fa-flag',ods:'fa-globe',mecip:'fa-shield-alt',pgn:'fa-coins',general:'fa-link'};

                            if (todosLosMarcos.length > 0) {
                                var syncData = { _token: $('meta[name=csrf-token]').attr('content') };
                                $.each(todosLosMarcos, function(i, id) { syncData['marcos[' + i + ']'] = id; });
                                $.ajax({
                                    url: '/pei-profiles/' + data.profile.id + '/marcos/sync',
                                    type: 'POST', data: syncData,
                                    success: function(resp) {
                                        if (resp.marcos) {
                                            var body = $('#marcos-body-' + data.profile.id).empty();
                                            resp.marcos.forEach(function(m) {
                                                var color = coloresMarco[m.tipo] || 'secondary';
                                                var icono = iconosMarco[m.tipo] || 'fa-link';
                                                body.append('<i class="fa ' + icono + ' text-muted mr-1" style="font-size:.75rem"></i>');
                                                body.append('<span class="badge badge-' + color + ' mr-1 mb-1" style="font-size:.75rem">' + m.nombre + '</span>');
                                            });
                                        }
                                    }
                                });
                            }

                            toastr.success(data.success || 'Guardado correctamente.');
                            $('#axisForm').trigger('reset');
                            $('#ajaxAxisModal').modal('hide');
                            recargarAcordeon();
                        },
                        error: function(xhr) {
                            var obj = xhr.responseJSON?.errors;
                            if (obj) $.each(obj, function(k, v) { toastr.error('Atención: ' + v); });
                            else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
                            $('#saveBtnAxis').html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                        }
                    });
                }

                // Si hay marcos nuevos, crearlos primero
                if (marcosNuevos.length > 0) {
                    var promises = marcosNuevos.map(function(tag) {
                        var nombre = tag.replace('new::', '');
                        return $.post('{{ route("pei.marcos.crear") }}', { nombre: nombre });
                    });
                    $.when.apply($, promises).then(function() {
                        var ids = [].slice.call(arguments).map(function(r) {
                            return Array.isArray(r) ? r[0].id : r.id;
                        });
                        guardarPeiYSincronizar(ids);
                    });
                } else {
                    guardarPeiYSincronizar([]);
                }
            });

            $('body').on('click', '#createGoals', function() {
                var profileID = $(this).data('id');
                var typeBtn = $(this).data('type');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeadingGoals').html(typeBtn === 'create' ? "Crear {{ $niveles['goal'] ?? 'Nivel 2' }}" : "Editar {{ $niveles['goal'] ?? 'Nivel 2' }}");
                    $('#saveBtnGoals').val(typeBtn === 'create' ? "create" : "edit");
                    $('#ajaxGoalsModal').modal('show');
                    $('#goalsForm').trigger("reset");

                    if (typeBtn === 'create') {
                        $('#goals_parent_id').val(data.profile.id);
                        $('#goals_order_item').val('');
                        goalsEditor.setData('');

                    } else if (typeBtn === 'edit') {
                        $('#goals_profile_id').val(data.profile.id);
                        $('#goals_parent_id').val(data.profile.parent_id);
                        goalsEditor.setData(data.profile.name);
                        $('#goals_order_item').val(data.profile.order_item);
                    }

                    $('#goals_type').val(data.profile.type);
                    $('#goals_group_id').val(data.profile.group_id);
                    $('#goals_dependency').val(data.profile.dependency_id);

                    // ── Perspectiva BSC (si el plan lo aplica en Nivel 2 o en Ambos) ──
                    var bscLevel = @json($niveles['bsc_level'] ?? 'axi');
                    if (bscLevel === 'goal' || bscLevel === 'both') {
                        $('#goals_bsc_block').show();
                        var bscGoalVal = (typeBtn === 'edit' && data.profile.bsc_perspectiva)
                            ? data.profile.bsc_perspectiva
                            : '';
                        $('#goals_bsc_perspectiva').val(bscGoalVal);
                    } else {
                        $('#goals_bsc_block').hide();
                        $('#goals_bsc_perspectiva').val('');
                    }

                    // ── Select2 Indicador (Ámbito: Objetivo Específico) ──────
                    var $goalsIndicadorSel = $('#goals_indicador_id');
                    if ($goalsIndicadorSel.hasClass('select2-hidden-accessible')) {
                        $goalsIndicadorSel.select2('destroy');
                    }
                    $goalsIndicadorSel.select2({
                        dropdownParent: $goalsIndicadorSel.closest('.form-group'),
                        placeholder: 'Buscar indicador por código o nombre...',
                        allowClear: true,
                        minimumInputLength: 0,
                        ajax: {
                            url: '{{ url("pei-profiles") }}/' + '{{ $profile->id }}' + '/indicadores/buscar',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term || '',
                                    ambito: 'objetivo_especifico'
                                };
                            },
                            processResults: function(data) {
                                return { results: data };
                            },
                            cache: true
                        },
                        templateResult: function(item) {
                            if (!item.id) return item.text;
                            var sentidoIcon = item.sentido === 'ascendente'
                                ? '<span class="text-success ml-1">▲</span>'
                                : '<span class="text-danger ml-1">▼</span>';
                            return $('<span>' +
                                '<span class="badge badge-dark mr-2" style="font-size:.65rem">' + item.codigo + '</span>' +
                                item.text.replace('[' + item.codigo + '] ', '') +
                                sentidoIcon +
                            '</span>');
                        }
                    });

                    // Pre-cargar indicador vinculado en edición
                    if (typeBtn === 'edit' && data.profile.indicador_id) {
                        $.getJSON('{{ url("pei-profiles") }}/' + '{{ $profile->id }}' + '/indicadores/buscar', { q: '', ambito: 'objetivo_especifico' }, function(res) {
                            var ind = res.find(function(i) { return i.id == data.profile.indicador_id; });
                            if (ind) {
                                var opt = new Option(ind.text, ind.id, true, true);
                                $goalsIndicadorSel.append(opt).trigger('change');
                            }
                        });
                    } else {
                        $goalsIndicadorSel.val(null).trigger('change');
                    }
                });
            });

            // ── Submit del form de Metas / Objetivos Específicos (Nivel 2) ──
            $('#goalsForm').on('submit', function(e) {
                e.preventDefault();
                var $btn = $('#saveBtnGoals').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

                var formData = new FormData(this);
                formData.append('name', goalsEditor.getData());
                formData.append('bsc_perspectiva', $('#goals_bsc_perspectiva').val() || '');
                formData.append('indicador_id', $('#goals_indicador_id').val() || '');

                $.ajax({
                    data: formData,
                    url: "{{ route('pei-profiles.store') }}",
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        toastr.success(res.success || 'Guardado correctamente.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                        $('#goalsForm').trigger('reset');
                        $('#ajaxGoalsModal').modal('hide');
                        recargarAcordeon();
                    },
                    error: function(xhr) {
                        var e = xhr.responseJSON?.errors;
                        if (e) $.each(e, (k,v) => toastr.error(v));
                        else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
                        $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar cambios');
                    }
                });
            });

            $('body').on('click', '#createActions', function() {
                var profileID = $(this).data('id');
                var typeBtn = $(this).data('type');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID + '/edit', function(data) {
                    $('#modalHeadingActions').html(typeBtn === 'create' ? "Crear {{ $niveles['action'] ?? 'Acción' }}" :
                        "Editar {{ $niveles['action'] ?? 'Acción' }}");
                    $('#saveBtnGoals').val(typeBtn === 'create' ? "create" : "edit");
                    $('#ajaxActionsModal').modal('show');
                    $('#actionsForm').trigger("reset");

                    if (typeBtn === 'create') {
                        $('#actions_parent_id').val(data.profile.id);
                        actionsEditor.setData('');
                        $('#actions_order_item').val('');
                        $('#saveBtnActions').val('create');

                    } else if (typeBtn === 'edit') {
                        $('#actions_profile_id').val(data.profile.id);
                        $('#actions_parent_id').val(data.profile.parent_id);
                        actionsEditor.setData(data.profile.name)
                        $('#actions_order_item').val(data.profile.order_item);
                        $('#saveBtnActions').val('edit');
                    }

                    $('#actions_type').val(data.profile.type);
                    $('#actions_group_id').val(data.profile.group_id);
                    $('#actions_dependency').val(data.profile.dependency_id);

                    // ── Select2 Responsables — usa la raíz del árbol institucional ──
                    var $responsibles = $('#responsibles');
                    if ($responsibles.hasClass('select2-hidden-accessible')) {
                        $responsibles.select2('destroy');
                    }
                    $responsibles.empty();

                    // Pre-cargar responsables ya asignados (edición)
                    data.responsiblesChecked.forEach(function(d) {
                        $responsibles.append(new Option(d.text, d.id, true, true));
                    });

                    var rootId = '{{ $orgRaizId ?? "" }}';
                    $responsibles.select2({
                        dropdownParent: $responsibles.closest('.form-group'),
                        placeholder: 'Buscar dependencia responsable...',
                        allowClear: true,
                        minimumInputLength: 1,
                        ajax: {
                            url: rootId
                                ? '{{ url("admin/globales/get-dependencies") }}/' + rootId
                                : '{{ url("admin/globales/get-dependencies-root") }}',
                            dataType: 'json',
                            delay: 300,
                            data: function(params) {
                                return { q: params.term || '' };
                            },
                            processResults: function(res) {
                                return {
                                    results: $.map(res, function(item) {
                                        return { id: item.id, text: item.dependency };
                                    })
                                };
                            },
                            cache: false
                        }
                    });

                    // ── Select2 Indicador ────────────────────────────────────
                    var $indicadorSel = $('#action_indicador_id');
                    if ($indicadorSel.hasClass('select2-hidden-accessible')) {
                        $indicadorSel.select2('destroy');
                    }
                    $indicadorSel.select2({
                        dropdownParent: $indicadorSel.closest('.form-group'),
                        placeholder: 'Buscar indicador por código o nombre...',
                        allowClear: true,
                        minimumInputLength: 0,
                        ajax: {
                            url: '{{ url("pei-profiles") }}/' + '{{ $profile->id }}' + '/indicadores/buscar',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term || '',
                                    ambito: 'accion_estrategica'
                                };
                            },
                            processResults: function(data) {
                                return { results: data };
                            },
                            cache: true
                        },
                        templateResult: function(item) {
                            if (!item.id) return item.text;
                            var sentidoIcon = item.sentido === 'ascendente'
                                ? '<span class="text-success ml-1">▲</span>'
                                : '<span class="text-danger ml-1">▼</span>';
                            return $('<span>' +
                                '<span class="badge badge-dark mr-2" style="font-size:.65rem">' + item.codigo + '</span>' +
                                item.text.replace('[' + item.codigo + '] ', '') +
                                sentidoIcon +
                            '</span>');
                        }
                    });

                    // Pre-cargar indicador vinculado en edición
                    if (typeBtn === 'edit' && data.profile.indicador_id) {
                        $.getJSON('{{ url("pei-profiles") }}/' + '{{ $profile->id }}' + '/indicadores/buscar', { q: '', ambito: 'accion_estrategica' }, function(res) {
                            var ind = res.find(function(i) { return i.id == data.profile.indicador_id; });
                            if (ind) {
                                var opt = new Option(ind.text, ind.id, true, true);
                                $indicadorSel.append(opt).trigger('change');
                            }
                        });
                    } else {
                        $indicadorSel.val(null).trigger('change');
                    }

                    // ── Select2 PGN ──────────────────────────────────────────
                    var anioActivo = new Date().getFullYear();
                    var $pgnNodo = $('#action_pgn_nodo');
                    if ($pgnNodo.hasClass('select2-hidden-accessible')) {
                        $pgnNodo.select2('destroy');
                    }
                    $pgnNodo.select2({
                        dropdownParent: $('#action_pgn_nodo').closest('.form-group'),
                        placeholder: 'Buscar por código o nombre...',
                        allowClear: true,
                        minimumInputLength: 2,
                        ajax: {
                            url: '{{ route("pgn.buscar") }}',
                            dataType: 'json',
                            delay: 300,
                            data: function(params) { return { q: params.term, anio: anioActivo }; },
                            processResults: function(data) {
                                return { results: $.map(data, function(n) {
                                    return { id: n.id, text: n.text, nivel: n.nivel };
                                })};
                            },
                            cache: true
                        },
                        templateResult: function(n) {
                            if (!n.id) return n.text;
                            return $('<span><small class="badge badge-secondary mr-1">' + (n.nivel||'') + '</small>' + n.text + '</span>');
                        }
                    });

                    // Pre-cargar vinculación PGN existente (solo en edición)
                    if (typeBtn === 'edit') {
                        $('#action_pgn_nodo').val(null).trigger('change');
                        $('#action_pgn_resultado').val('');
                        $('#action_pgn_monto_vinculado').val('');
                        $('#action_pgn_monto_ejecutado').val('');

                        $.get('{{ route("pei.accion.pgn.get", ["actionId" => "__ID__"]) }}'.replace('__ID__', profileID), function(v) {
                            if (!v) return;
                            var opt = new Option(v.pgn_nodo_text, v.pgn_nodo_id, true, true);
                            $('#action_pgn_nodo').append(opt).trigger('change');
                            $('#action_pgn_resultado').val(v.resultado);
                            $('#action_pgn_monto_vinculado').val(v.monto_vinculado_gs);
                            $('#action_pgn_monto_ejecutado').val(v.monto_ejecutado_gs);
                        });
                    } else {
                        $('#action_pgn_nodo').val(null).trigger('change');
                        $('#action_pgn_resultado').val('');
                        $('#action_pgn_monto_vinculado').val('');
                        $('#action_pgn_monto_ejecutado').val('');
                    }

                    // ── Panel Actividad vinculada ────────────────────────────────
                    var buscarActividadesUrl = '{{ route("pei.actividades.buscar") }}';

                    function mostrarActividadVinculada(id, nombre, tareasPreseleccionadas) {
                        $('#actions_activity_id').val(id);
                        $('#action_actividad_nombre_vinculada').text(nombre);
                        $('#action_actividad_link').attr('href', '{{ url("admin/globales/activities") }}/' + id);
                        $('#panel_actividad_vinculada').show();
                        $('#panel_nueva_actividad, #panel_existente_actividad').hide();

                        // Cargar tareas de la actividad en el select2
                        var $tareasSelect = $('#action_activity_tasks');
                        if ($tareasSelect.hasClass('select2-hidden-accessible')) $tareasSelect.select2('destroy');
                        $tareasSelect.empty();

                        $.getJSON('{{ route("pei.actividades.tareas", ["activityId" => "__AID__"]) }}'.replace('__AID__', id), function(res) {
                            res.results.forEach(function(t) {
                                var presel = tareasPreseleccionadas && tareasPreseleccionadas.some(function(p) { return p.id == t.id; });
                                $tareasSelect.append(new Option(t.text, t.id, presel, presel));
                            });
                            $tareasSelect.select2({
                                dropdownParent: $('#ajaxActionsModal'),
                                placeholder: 'Seleccioná las tareas...',
                                allowClear: true,
                            }).trigger('change');
                        });
                    }

                    function resetActividadPanel(activityId, activityName, tareasPreseleccionadas) {
                        $('#action_actividad_nombre').val('');
                        $('#panel_actividad_vinculada').hide();
                        $('#panel_nueva_actividad').show();
                        $('#panel_existente_actividad').hide();
                        $('#btnActividadNueva').addClass('active');
                        $('#btnActividadExistente').removeClass('active');
                        if (activityId) {
                            $('#action_cuenta_actividad').prop('checked', true);
                            $('#action_actividad_panel').show();
                            mostrarActividadVinculada(activityId, activityName, tareasPreseleccionadas || []);
                        } else {
                            $('#action_cuenta_actividad').prop('checked', false);
                            $('#action_actividad_panel').hide();
                            $('#actions_activity_id').val('');
                        }
                    }

                    // Precargar en edición
                    resetActividadPanel(
                        data.activitySelected ? data.activitySelected.id : null,
                        data.activitySelected ? data.activitySelected.text : null,
                        data.activityTasksSelected || []
                    );

                    // Checkbox toggle
                    $('#action_cuenta_actividad').off('change').on('change', function() {
                        if ($(this).is(':checked')) {
                            $('#action_actividad_panel').slideDown(150);
                        } else {
                            $('#action_actividad_panel').slideUp(150);
                            $('#actions_activity_id').val('');
                            $('#panel_actividad_vinculada').hide();
                        }
                    });

                    // Tabs nueva / existente
                    $('#btnActividadNueva').off('click').on('click', function() {
                        $(this).addClass('active');
                        $('#btnActividadExistente').removeClass('active');
                        $('#panel_nueva_actividad').show();
                        $('#panel_existente_actividad').hide();
                    });

                    $('#btnActividadExistente').off('click').on('click', function() {
                        $(this).addClass('active');
                        $('#btnActividadNueva').removeClass('active');
                        $('#panel_existente_actividad').show();
                        $('#panel_nueva_actividad').hide();

                        var $sel = $('#action_activity_id_select');
                        if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
                        $sel.empty().select2({
                            placeholder: 'Buscar actividad existente...',
                            allowClear: true,
                            dropdownParent: $('#ajaxActionsModal'),
                            ajax: {
                                url: buscarActividadesUrl, dataType: 'json', delay: 300,
                                data: function(p) { return { q: p.term }; },
                                processResults: function(d) { return { results: d.results }; }
                            }
                        }).off('select2:select').on('select2:select', function(e) {
                            mostrarActividadVinculada(e.params.data.id, e.params.data.text, []);
                        });
                    });

                    // Crear nueva actividad
                    $('#btnCrearActividad').off('click').on('click', function() {
                        var pid = $('#actions_profile_id').val();
                        if (!pid) { toastr.warning('Guardá la acción primero antes de crear la actividad.'); return; }
                        var nombre = $('#action_actividad_nombre').val();
                        var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Creando...');
                        $.post('{{ url("pei-profiles") }}/' + pid + '/actividad',
                            { _token: $('meta[name=csrf-token]').attr('content'), nombre: nombre },
                            function(res) {
                                mostrarActividadVinculada(res.activity.id, res.activity.text, []);
                                toastr.success(res.success);
                            }
                        ).fail(function() {
                            toastr.error('Error al crear la actividad.');
                        }).always(function() {
                            $btn.prop('disabled', false).html('<i class="fa fa-plus mr-1"></i>Crear y vincular');
                        });
                    });

                    // Desvincular
                    $('#btnDesvincularActividad').off('click').on('click', function() {
                        $('#actions_activity_id').val('');
                        $('#panel_actividad_vinculada').hide();
                        $('#panel_nueva_actividad').show();
                        $('#panel_existente_actividad').hide();
                        $('#btnActividadNueva').addClass('active');
                        $('#btnActividadExistente').removeClass('active');
                    });
                    // ── Fin Panel Actividad ───────────────────────────────────

                });
            });

            $('body').on('click', '#showStrategies', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" + '/' + profileID, function(data) {
                    $('#modalHeadingStrategies').html(
                        'Estrategias del Cruce de Ambientes ( Análisis FODA)');
                    $('#ajaxStrategiesModal').modal('show');

                    var strategies = data.profile.strategies;
                    var tableBody = $('#strategiesList .table tbody');

                    tableBody.empty(); // Limpiar el contenido de la tabla

                    // Iterar sobre las estrategias y agregarlas a la tabla
                    strategies.forEach(function(strategy) {
                        var row = $('<tr>');
                        row.append($('<td>').text(strategy.estrategia));
                        // Condición para mostrar el tipo de estrategia como Fortaleza u Oportunidad
                        if (strategy.tipo === 'FO') {
                            row.append($('<td>').html(
                                '<span class="badge badge-success">Fortaleza</span> vs <span class="badge badge-success">Oportunidad</span>'
                            ));
                        } else if (strategy.tipo === 'DO') {
                            row.append($('<td>').html(
                                '<span class="badge badge-danger">Debilidad</span> vs <span class="badge badge-success">Oportunidad</span>'
                            ));
                        } else if (strategy.tipo === 'FA') {
                            row.append($('<td>').html(
                                '<span class="badge badge-success">Fortaleza</span> vs <span class="badge badge-danger">Amenaza</span>'
                            ));
                        } else if (strategy.tipo === 'DA') {
                            row.append($('<td>').html(
                                '<span class="badge badge-danger">Debilidad</span> vs <span class="badge badge-danger">Amenaza</span>'
                            ));
                        } else {
                            row.append($('<td>').text(strategy.tipo));
                        }
                        tableBody.append(row);
                    });



                });
            });

            $('body').on('click', '#compareHistorical', function() {
                var typeBtn = $(this).data('type');

                url = "{{ route('pei-profiles-compareHistorical') }}" + '?pei_id={{ $profile->id }}'
                $.get(url, function(data) {
                    console.log(data)
                    $('#modalHeadingHistorical').html(
                        'Histórico de Definición de Misión - Grupales');
                    $('#ajaxHistoricalModal').modal('show');
                    compareList

                    var tableBody = $('#compareList .table tbody');
                    tableBody.empty(); // Limpiar el contenido de la tabla

                    // Itera sobre los datos y agrega filas a la tabla
                    data.forEach(function(row) {
                        var newRow = $('<tr>');
                        newRow.append($('<td>').text(row.group
                            .name)); // Cambia row.group al campo correcto

                        if (typeBtn === 'mision') {
                            newRow.append($('<td>').html(row
                                .mision)); // Cambia row.mision al campo correcto
                        } else if (typeBtn === 'vision') {
                            newRow.append($('<td>').html(row
                                .vision)); // Cambia row.vision al campo correcto
                        } else if (typeBtn === 'values') {
                            newRow.append($('<td>').html(row
                                .values)); // Cambia row.vision al campo correcto
                        }


                        tableBody.append(newRow);
                    });


                });
            });

            $('body').on('click', '#showAxisList', function() {

                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/axis-list',
                    function(data) {
                        $('#modalHeadingAxisList').html('Lista de {{ $niveles['axi'] ?? 'Nivel 1' }}s');
                        $('#ajaxAxisListlModal').modal('show');

                        var tableBody = $('#axisList .table tbody');
                        tableBody.empty();

                        data.axis.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));

                            // Mostrar estrategias FODA vinculadas
                            var fodaCell = $('<td>');
                            if (row.strategies && row.strategies.length > 0) {
                                row.strategies.forEach(function(strategy) {
                                    fodaCell.append(
                                        $('<span class="badge badge-warning mr-1 mb-1">')
                                            .text('[' + strategy.tipo + '] ' + strategy.estrategia)
                                    );
                                });
                            } else {
                                fodaCell.append('<span class="text-muted"><em>Sin estrategias FODA vinculadas</em></span>');
                            }
                            newRow.append(fodaCell);
                            tableBody.append(newRow);
                        });
                    });
            });

            $('body').on('click', '#showGoalsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/goals-list',
                    function(data) {
                        $('#modalHeadingGoalsList').html('Lista de {{ $niveles['goal'] ?? 'Nivel 2' }}s');
                        $('#ajaxGoalsListModal').modal('show');

                        var tableBody = $('#goalsList .table tbody');
                        tableBody.empty();

                        data.goals.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));
                            tableBody.append(newRow);
                        });
                    });
            });

            $('body').on('click', '#showActionsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/actions-list',
                    function(data) {
                        $('#modalHeadingActionsList').html('Lista de {{ $niveles['action'] ?? 'Acciones' }}');
                        $('#ajaxActionsListModal').modal('show');

                        var tableBody = $('#actionsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.actions.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));
                            newRow.append($('<td>').html(row.indicator));
                            newRow.append($('<td>').html(row.baseline));
                            newRow.append($('<td>').html(row.target));

                            // Crear una celda para mostrar todas las estrategias
                            var responsaiblesCell = $('<td>');

                            row.responsibles.forEach(function(responsible) {
                                // Agregar cada estrategia a la celda
                                responsaiblesCell.append(responsible.dependency +
                                    '<br>');
                            });

                            newRow.append(responsaiblesCell);
                            tableBody.append(newRow);
                        });



                    });
            });

            $('body').on('click', '#showParticipantsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/actions-list',
                    function(data) {
                        $('#modalHeadingActionsList').html('Lista de {{ $niveles['action'] ?? 'Acciones' }}');
                        $('#ajaxActionsListModal').modal('show');

                        var tableBody = $('#actionsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.actions.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));
                            newRow.append($('<td>').html(row.indicator));
                            newRow.append($('<td>').html(row.baseline));
                            newRow.append($('<td>').html(row.target));

                            // Crear una celda para mostrar todas las estrategias
                            var responsaiblesCell = $('<td>');

                            row.responsibles.forEach(function(responsible) {
                                // Agregar cada estrategia a la celda
                                responsaiblesCell.append(responsible.dependency +
                                    '<br>');
                            });

                            newRow.append(responsaiblesCell);
                            tableBody.append(newRow);
                        });



                    });
            });

            $(document).ready(function() {
                // ══ MÓDULO REPORTAR AVANCE ════════════════════════════════════
                var _rpIndicador    = null;
                var _rpBaseUrl      = '{{ url("pei-profiles") }}';

                function cargarHistorial(accionId) {
                    $.getJSON(_rpBaseUrl + '/' + accionId + '/reportes', function(reportes) {
                        $('#rp_historial_count').text(reportes.length);
                        var $hist = $('#rp_historial').empty();
                        if (!reportes.length) {
                            $hist.html('<p class="text-muted text-center" style="font-size:.78rem">Sin reportes previos.</p>');
                            return;
                        }
                        reportes.forEach(function(r) {
                            var sc = {verde:'success',amarillo:'warning',rojo:'danger','sin-datos':'secondary'}[r.semaforo] || 'secondary';
                            $hist.append(
                                '<div class="d-flex align-items-start py-1" style="border-bottom:1px solid #f0f0f0;gap:.5rem;font-size:.75rem">' +
                                '<span class="badge badge-' + sc + ' flex-shrink-0" style="font-size:.65rem;margin-top:2px">' + (r.semaforo||'—') + '</span>' +
                                '<div style="flex:1;min-width:0">' +
                                '<strong>' + r.fecha_reporte + '</strong>' + (r.periodo_label ? ' — ' + r.periodo_label : '') +
                                (r.valor_numerador !== null ? ' <span class="badge badge-light border">' + r.valor_numerador + '</span>' : '') +
                                (r.pct_avance !== null ? ' <small class="text-muted">(' + r.pct_avance + '%)</small>' : '') +
                                '<div class="text-muted" style="font-size:.72rem">' + (r.descripcion_avance || '') + '</div>' +
                                '<small class="text-muted">' + r.reportado_por + '</small>' +
                                '</div>' +
                                '<div class="d-flex flex-shrink-0" style="gap:.25rem">' +
                                '<button class="btn btn-xs btn-outline-primary py-0 px-1 btnEditarReporte" data-id="' + r.id + '" data-accion="' + accionId + '" data-r=\'' + JSON.stringify(r) + '\' title="Editar"><i class="fa fa-edit" style="font-size:.65rem"></i></button>' +
                                '<button class="btn btn-xs btn-outline-danger py-0 px-1 btnEliminarReporte" data-id="' + r.id + '" data-accion="' + accionId + '" title="Eliminar"><i class="fa fa-trash" style="font-size:.65rem"></i></button>' +
                                '</div>' +
                                '</div>'
                            );
                        });
                    }).fail(function() {
                        $('#rp_historial').html('<p class="text-muted text-center" style="font-size:.78rem">Sin reportes previos.</p>');
                    });
                }

                function extraerNumeroLimpioJS(texto) {
                    if (!texto) return null;
                    var str = String(texto);
                    if (str.indexOf(':') !== -1) {
                        var parts = str.split(':');
                        str = parts[parts.length - 1];
                    }
                    var match = str.match(/[0-9]+(?:\.[0-9]+)?/);
                    return match ? parseFloat(match[0]) : null;
                }

                function calcularSemaforoPrev(valor, indicador) {
                    if (valor === null || valor === undefined || isNaN(valor) || !indicador || !indicador.metas) return null;
                    var anio  = new Date().getFullYear();
                    var metas = indicador.metas;
                    var meta  = metas.find(function(m) { return m.anio == anio; });
                    if (!meta) meta = metas.sort(function(a,b) { return a.anio - b.anio; }).find(function(m) { return m.anio >= anio; });
                    if (!meta || !meta.valor) return null;
                    
                    var metaNum = extraerNumeroLimpioJS(meta.valor);
                    if (!metaNum || metaNum <= 0) return null;

                    var lbNum = extraerNumeroLimpioJS(indicador.linea_base_valor);
                    var pct, semaforo;

                    if (indicador.sentido === 'descendente') {
                        if (lbNum !== null && lbNum > metaNum) {
                            var redPlaneada = lbNum - metaNum;
                            var redLograda  = lbNum - valor;
                            pct = (redLograda / redPlaneada) * 100;
                        } else {
                            if (valor <= metaNum) {
                                pct = 100.0;
                            } else {
                                pct = (metaNum / valor) * 100;
                            }
                        }
                    } else {
                        pct = (valor / metaNum) * 100;
                    }

                    if (pct < 0) pct = 0;
                    pct = Math.min(Math.round(pct * 100) / 100, 100.0);

                    if (pct >= 85)      semaforo = 'verde';
                    else if (pct >= 50) semaforo = 'amarillo';
                    else                semaforo = 'rojo';

                    return { semaforo: semaforo, pct: pct, metaLabel: meta.valor };
                }

                $('body').on('click', '.reportProgress', function() {
                    var accionId = $(this).data('id');
                    _rpIndicador = null;
                    $('#reportProgress_accionId').val(accionId);
                    $('#rp_fecha_reporte').val(new Date().toISOString().split('T')[0]);
                    $('#rp_periodo_label,#rp_valor_numerador,#rp_descripcion_avance,#rp_evidencia_url,#rp_evidencia_label').val('');
                    $('#reportProgress_fichaIndicador').hide();
                    $('#rp_semaforo_preview').hide();
                    $('#rp_historial').html('<p class="text-muted text-center" style="font-size:.78rem">Cargando...</p>');

                    // Cargar datos de la acción
                    $.getJSON(_rpBaseUrl + '/' + accionId + '/edit', function(data) {
                        // Nombre de la acción
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.profile.name;
                        $('#reportProgress_accionNombre').text(tempDiv.textContent || data.profile.name);
                    });

                    // Cargar indicador de la acción
                    $.getJSON(_rpBaseUrl + '/{{ $profile->id }}/indicadores', function(todos) {
                        // Buscar si esta accion tiene indicador_id — necesitamos el perfil
                        $.getJSON(_rpBaseUrl + '/' + accionId + '/edit', function(data) {
                            if (data.profile.indicador_id) {
                                var ind = todos.find(function(i) { return i.id == data.profile.indicador_id; });
                                if (ind) {
                                    _rpIndicador = ind;
                                    var anio = new Date().getFullYear();
                                    var meta = ind.metas ? ind.metas.find(function(m) { return m.anio == anio; }) : null;
                                    var dimColors = { eficiencia:'#1976d2', eficacia:'#28a745', calidad:'#17a2b8', economia:'#ffc107' };
                                    var dimLabels = { eficiencia:'Eficiencia', eficacia:'Eficacia', calidad:'Calidad', economia:'Economía' };
                                    $('#rp_ind_codigo').text(ind.codigo);
                                    $('#rp_ind_dimension').text(dimLabels[ind.dimension] || ind.dimension)
                                        .attr('style','background:' + (dimColors[ind.dimension]||'#6c757d') + ';color:#fff;font-size:.65rem');
                                    $('#rp_ind_sentido').html(ind.sentido === 'ascendente'
                                        ? '<span class="text-success font-weight-bold">▲</span>'
                                        : '<span class="text-danger font-weight-bold">▼</span>');
                                    $('#rp_ind_nombre').text(ind.nombre);
                                    $('#rp_ind_formula').text(ind.formula || '—');
                                    $('#rp_ind_unidad').text(ind.unidad_medida || '—');
                                    $('#rp_unidad_label').text(ind.unidad_medida || '—');
                                    if (ind.variables) {
                                        $('#rp_ind_variables').text(ind.variables);
                                        $('#rp_ind_variables_container').show();
                                    } else {
                                        $('#rp_ind_variables_container').hide();
                                    }
                                    $('#rp_ind_meta_anio').text(meta ? anio + ': ' + meta.valor : 'Sin meta para ' + anio);
                                    $('#reportProgress_fichaIndicador').show();
                                }
                            }
                        });
                    });

                    // Cargar historial
                    cargarHistorial(accionId);

                    $('#ajaxDefineCriteriaModal').modal('show');
                });

                // Preview semáforo en tiempo real
                $('#rp_valor_numerador').on('input', function() {
                    var val = parseFloat($(this).val());
                    if (isNaN(val) || !_rpIndicador) { $('#rp_semaforo_preview').hide(); return; }
                    var res = calcularSemaforoPrev(val, _rpIndicador);
                    if (!res) { $('#rp_semaforo_preview').hide(); return; }
                    var cols = {verde:'#28a745',amarillo:'#ffc107',rojo:'#dc3545'};
                    var fg   = res.semaforo === 'amarillo' ? '#000' : '#fff';
                    $('#rp_semaforo_badge')
                        .text(res.semaforo.toUpperCase() + ' — ' + res.pct + '%')
                        .attr('style','background:' + (cols[res.semaforo]||'#6c757d') + ';color:' + fg);
                    $('#rp_pct_label').text('Meta: ' + res.metaLabel);
                    $('#rp_semaforo_preview').show();
                });

                // Guardar reporte
                $('#btnGuardarReporte').on('click', function() {
                    var accionId = $('#reportProgress_accionId').val();
                    var fecha    = $('#rp_fecha_reporte').val();
                    if (!fecha) { toastr.warning('La fecha del reporte es obligatoria.'); return; }

                    $.ajax({
                        url: _rpBaseUrl + '/' + accionId + '/reportes',
                        type: 'POST',
                        data: {
                            fecha_reporte:      fecha,
                            periodo_label:      $('#rp_periodo_label').val(),
                            valor_numerador:    $('#rp_valor_numerador').val() || null,
                            descripcion_avance: $('#rp_descripcion_avance').val(),
                            evidencia_url:      $('#rp_evidencia_url').val(),
                            evidencia_label:    $('#rp_evidencia_label').val(),
                        },
                        success: function(res) {
                            toastr.success('Reporte guardado correctamente.');
                            // Limpiar campos
                            $('#rp_periodo_label,#rp_valor_numerador,#rp_descripcion_avance,#rp_evidencia_url,#rp_evidencia_label').val('');
                            $('#rp_fecha_reporte').val(new Date().toISOString().split('T')[0]);
                            $('#rp_semaforo_preview').hide();
                            // Recargar historial sin cerrar el modal
                            cargarHistorial(accionId);
                            // Actualizar semáforo en el acordeón
                            if (res.reporte && res.reporte.semaforo) {
                                var colors = {verde:'#28a745',amarillo:'#ffc107',rojo:'#dc3545','sin-datos':'#6c757d'};
                                $('#actionsBlock_' + accionId + ' .card-header')
                                    .css('border-left-color', colors[res.reporte.semaforo] || '#6c757d');
                            }
                            recargarAcordeon();
                        },
                        error: function(xhr) {
                            var e = xhr.responseJSON?.errors;
                            if (e) $.each(e, (k,v) => toastr.error(v[0]));
                            else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
                        }
                    });
                });

                // ── Editar reporte ─────────────────────────────────────────────────────────────
                $(document).on('click', '.btnEditarReporte', function() {
                    var r       = $(this).data('r');
                    var id      = r.id;
                    var accionId = $(this).data('accion');
                    Swal.fire({
                        title: 'Editar Reporte',
                        html:
                            '<div class="text-left" style="font-size:.85rem">' +
                            '<div class="form-group mb-2"><label>Fecha</label>' +
                            '<input id="edit_rp_fecha" class="form-control form-control-sm" type="date" value="' + (r.fecha_reporte_raw || r.fecha_reporte.split('/').reverse().join('-')) + '"></div>' +
                            '<div class="form-group mb-2"><label>Período</label>' +
                            '<input id="edit_rp_periodo" class="form-control form-control-sm" type="text" value="' + (r.periodo_label||'') + '"></div>' +
                            '<div class="form-group mb-2"><label>Valor logrado</label>' +
                            '<input id="edit_rp_valor" class="form-control form-control-sm" type="number" step="0.0001" value="' + (r.valor_numerador !== null ? r.valor_numerador : '') + '"></div>' +
                            '<div class="form-group mb-2"><label>Descripción</label>' +
                            '<textarea id="edit_rp_desc" class="form-control form-control-sm" rows="3">' + (r.descripcion_avance||'') + '</textarea></div>' +
                            '<div class="form-group mb-0"><label>Evidencia URL</label>' +
                            '<input id="edit_rp_url" class="form-control form-control-sm" type="text" value="' + (r.evidencia_url||'') + '"></div>' +
                            '</div>',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa fa-save mr-1"></i> Guardar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#1976d2',
                        preConfirm: function() {
                            return {
                                fecha_reporte:      $('#edit_rp_fecha').val(),
                                periodo_label:      $('#edit_rp_periodo').val(),
                                valor_numerador:    $('#edit_rp_valor').val() || null,
                                descripcion_avance: $('#edit_rp_desc').val(),
                                evidencia_url:      $('#edit_rp_url').val(),
                            };
                        }
                    }).then(function(result) {
                        if (!result.isConfirmed) return;
                        $.ajax({
                            url:  _rpBaseUrl + '/' + accionId + '/reportes/' + id,
                            type: 'PUT',
                            data: result.value,
                            success: function() {
                                toastr.success('Reporte actualizado.');
                                cargarHistorial(accionId);
                                recargarAcordeon();
                            },
                            error: function() { toastr.error('Error al actualizar el reporte.'); }
                        });
                    });
                });

                // ── Eliminar reporte ───────────────────────────────────────────────────────────
                $(document).on('click', '.btnEliminarReporte', function() {
                    var id       = $(this).data('id');
                    var accionId = $(this).data('accion');
                    Swal.fire({
                        title: '¿Eliminar este reporte?',
                        icon: 'warning', showCancelButton: true,
                        confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
                        backdrop: false,
                        customClass: { container: 'swal-over-modal' },
                    }).then(function(result) {
                        if (!result.isConfirmed) return;
                        $.ajax({
                            url:  _rpBaseUrl + '/' + accionId + '/reportes/' + id,
                            type: 'DELETE',
                            success: function() {
                                toastr.success('Reporte eliminado.');
                                cargarHistorial(accionId);
                                recargarAcordeon();
                            },
                            error: function() { toastr.error('Error al eliminar el reporte.'); }
                        });
                    });
                });

                // ══ FIN MÓDULO REPORTAR AVANCE ═══════════════════════════════
            });
            // Agregar un controlador de eventos para el botón de eliminación
            $('.contentMain').on('click', '.deleteItem', function() {
                var axisId = $(this).data('id');

                // Muestra una confirmación en SweetAlert
                Swal.fire({
                    title: '¿Estás seguro de eliminarlo?',
                    text: "Si lo haces, no podrás revertirlo",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Estoy seguro!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, procede con la eliminación
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('pei-profiles.store') }}" + '/' + axisId,
                            success: function(data) {

                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });

                        // Elimina la tarjeta del DOM
                        $(this).closest('.card').remove();

                        // Muestra una notificación de éxito después de la eliminación
                        Swal.fire(
                            'Borrado',
                            'El registro ha sido eliminado correctamente',
                            'success'
                        );
                    }
                });
            });
        });

        // Inicializar popovers de marcos referenciales
        $('[data-toggle="popover"]').popover();
        // Cerrar popover al hacer click fuera
        $('body').on('click', function(e) {
            if (!$(e.target).closest('[data-toggle="popover"]').length) {
                $('[data-toggle="popover"]').popover('hide');
            }
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO: Ficha Técnica de Indicadores
        // ══════════════════════════════════════════════════════════════════════
        var _peiProfileId = '{{ $profile->id }}';
        var _indicadorEditId = null;
        var _metaIndex = 0;

        var _dimensionLabels = { eficiencia:'Eficiencia', eficacia:'Eficacia', calidad:'Calidad', economia:'Economía' };
        var _ambitoLabels    = { objetivo_estrategico:'Obj. Estratégico', objetivo_especifico:'Obj. Específico', accion_estrategica:'Acc. Estratégica', accion_operativa:'Acc. Operativa' };
        var _frecuenciaLabels= { mensual:'Mensual', trimestral:'Trimestral', semestral:'Semestral', anual:'Anual', otro:'Otro' };
        var _sentidoLabels   = { ascendente:'▲ Asc.', descendente:'▼ Desc.' };

        // ── Estilos radio como card seleccionable ──────────────────────────
        $(document).on('change', '.ind-radio', function() {
            var name  = $(this).attr('name');
            // Desmarcar todas las del mismo grupo
            $('input[name="' + name + '"]').each(function() {
                var card  = $(this).closest('.ind-radio-card');
                var color = card.data('color') || 'secondary';
                card.removeClass('ind-selected-' + color);
            });
            // Marcar la seleccionada
            var card  = $(this).closest('.ind-radio-card');
            var color = card.data('color') || 'secondary';
            card.addClass('ind-selected-' + color);
        });

        // ── Agregar fila de meta ───────────────────────────────────────────
        function agregarMeta(anio, valor) {
            var idx = _metaIndex++;
            var row = '<tr class="meta-row border-bottom align-middle" data-idx="' + idx + '">' +
                '<td class="py-1.5 pl-3 pr-2" style="vertical-align:middle;">' +
                    '<input type="number" class="form-control form-control-sm meta-anio font-weight-bold" placeholder="Ej: 2026" value="' + (anio||'') + '" min="2020" max="2100" style="font-size:.78rem; height: 30px; border-radius: 6px;">' +
                '</td>' +
                '<td class="py-1.5 px-2" style="vertical-align:middle;">' +
                    '<input type="text" class="form-control form-control-sm meta-valor" placeholder="Ej: 2, 85%, 1500" value="' + (valor||'') + '" style="font-size:.78rem; height: 30px; border-radius: 6px;">' +
                '</td>' +
                '<td class="text-center py-1.5 pr-3" style="vertical-align:middle;">' +
                    '<button type="button" class="btn btn-sm btn-circle btn-remove-meta" title="Eliminar Meta" style="width: 28px; height: 28px; border-radius: 50%; padding: 0; display: inline-flex; align-items: center; justify-content: center; background: #ffe4e6; color: #e11d48; border: none; cursor: pointer; transition: all 0.2s;">' +
                        '<i class="fas fa-trash-alt" style="font-size: 0.72rem;"></i>' +
                    '</button>' +
                '</td>' +
            '</tr>';
            $('#metasContainer').append(row);
        }

        $('#btnAgregarMeta').on('click', function() { agregarMeta('',''); });

        $(document).on('click', '.btn-remove-meta', function() {
            $(this).closest('.meta-row').remove();
        });

        // ── Leer metas del DOM ─────────────────────────────────────────────
        function leerMetas() {
            var metas = [];
            $('#metasContainer .meta-row').each(function() {
                var anio  = parseInt($(this).find('.meta-anio').val());
                var valor = $.trim($(this).find('.meta-valor').val());
                if (anio && valor) metas.push({ anio: anio, valor: valor });
            });
            return metas;
        }

        // ── Resetear formulario ────────────────────────────────────────────
        function resetFormIndicador() {
            _indicadorEditId = null;
            $('#ind_id').val('');
            $('#ind_pei_profile_id').val(_peiProfileId);
            $('#ind_nombre').val('');
            $('#ind_codigo_letras').val('');
            $('#ind_codigo_numeros').val('');
            $('[name="ind_dimension"],[name="ind_ambito"],[name="ind_frecuencia"],[name="ind_cobertura"],[name="ind_sentido"]').prop('checked',false);
            $('.ind-radio-card').each(function() {
                var color = $(this).data('color') || 'secondary';
                $(this).removeClass('ind-selected-' + color);
            });
            $('#ind_descripcion,#ind_variables,#ind_formula,#ind_unidad_medida').val('');
            $('#ind_frecuencia_otro,#ind_linea_base_anio,#ind_linea_base_valor').val('');
            $('#ind_fuente,#ind_dependencia_responsable,#ind_comentarios').val('');
            $('#metasContainer').empty();
            _metaIndex = 0;
        }

        // ── Cargar datos en el formulario (edición) ────────────────────────
        function cargarIndicador(ind) {
            resetFormIndicador();
            _indicadorEditId = ind.id;
            $('#ind_id').val(ind.id);
            $('#ind_nombre').val(ind.nombre);
            $('#ind_codigo_letras').val(ind.codigo_letras);
            $('#ind_codigo_numeros').val(ind.codigo_numeros);
            // Radios
            $.each(['dimension','ambito','frecuencia','cobertura','sentido'], function(i, campo) {
                var val = ind[campo];
                var radio = $('input[name="ind_' + campo + '"][value="' + val + '"]');
                radio.prop('checked', true).trigger('change');
            });
            if (ind.frecuencia === 'otro') $('#ind_frecuencia_otro').val(ind.frecuencia_otro);
            $('#ind_descripcion').val(ind.descripcion);
            $('#ind_variables').val(ind.variables);
            $('#ind_formula').val(ind.formula);
            $('#ind_unidad_medida').val(ind.unidad_medida);
            $('#ind_linea_base_anio').val(ind.linea_base_anio);
            $('#ind_linea_base_valor').val(ind.linea_base_valor);
            $('#ind_fuente').val(ind.fuente);
            $('#ind_dependencia_responsable').val(ind.dependencia_responsable);
            $('#ind_comentarios').val(ind.comentarios);
            // Metas
            if (ind.metas && ind.metas.length) {
                ind.metas.forEach(function(m) { agregarMeta(m.anio, m.valor); });
            }
        }

        // ── Abrir modal nuevo ──────────────────────────────────────────────
        // ── Autocompletar código desde el servidor ─────────────────────────
        function autoCompletarCodigo(callback) {
            $.getJSON('{{ url("pei-profiles") }}/' + _peiProfileId + '/indicadores/siguiente-codigo',
                function(res) {
                    $('#ind_codigo_letras').val(res.letras);
                    $('#ind_codigo_numeros').val(res.numeros);
                    if (callback) callback(res);
                }
            );
        }

        $('body').on('click', '#btnNuevoIndicador', function() {
            resetFormIndicador();
            var subtitulo = $(this).data('subtitulo') || '{{ strip_tags($profile->name) }}';
            $('#modalIndicadorTitulo').html('<i class="fa fa-ruler-combined mr-2"></i>Nueva Ficha de Indicador');
            $('#modalIndicadorSubtitulo').text(subtitulo);
            $('#modalIndicadoresList').modal('hide');
            $('#modalIndicador').modal('show');
            autoCompletarCodigo();
        });

        $('#btnNuevoIndicadorDesdeList').on('click', function() {
            resetFormIndicador();
            $('#modalIndicadorTitulo').html('<i class="fa fa-ruler-combined mr-2"></i>Nueva Ficha de Indicador');
            $('#modalIndicadorSubtitulo').text('');
            $('#modalIndicadoresList').modal('hide');
            $('#modalIndicador').modal('show');
            autoCompletarCodigo();
        });

        // ── Abrir modal lista de indicadores ──────────────────────────────
        $('body').on('click', '#btnVerIndicadores', function() {
            cargarListaIndicadores();
            $('#modalIndicadoresList').modal('show');
        });

        function cargarListaIndicadores() {
            $.getJSON('{{ url("pei-profiles") }}/' + _peiProfileId + '/indicadores', function(data) {
                var tbody = $('#indicadoresListBody').empty();
                if (!data.length) {
                    tbody.append('<tr><td colspan="7" class="text-center text-muted py-3"><i class="fa fa-inbox mr-1"></i> Sin indicadores registrados.</td></tr>');
                    return;
                }
                data.forEach(function(ind) {
                    tbody.append(
                        '<tr>' +
                        '<td><span class="badge badge-dark" style="font-size:.7rem">' + ind.codigo + '</span></td>' +
                        '<td style="font-size:.85rem">' + ind.nombre + '</td>' +
                        '<td><span class="badge badge-info" style="font-size:.68rem">' + (_dimensionLabels[ind.dimension]||ind.dimension) + '</span></td>' +
                        '<td style="font-size:.78rem">' + (_ambitoLabels[ind.ambito]||ind.ambito) + '</td>' +
                        '<td style="font-size:.78rem">' + (_frecuenciaLabels[ind.frecuencia]||ind.frecuencia) + '</td>' +
                        '<td>' + (ind.sentido === 'ascendente' ? '<span class="text-success font-weight-bold">▲</span>' : '<span class="text-danger font-weight-bold">▼</span>') + '</td>' +
                        '<td class="text-center" style="white-space:nowrap">' +
                            '<button class="btn btn-sm btn-outline-primary py-0 px-2 btnEditarIndicador" data-id="' + ind.id + '" title="Editar"><i class="fa fa-edit" style="font-size:.7rem"></i></button> ' +
                            '<button class="btn btn-sm btn-outline-danger py-0 px-2 btnEliminarIndicador" data-id="' + ind.id + '" data-nombre="' + ind.nombre + '" title="Eliminar"><i class="fa fa-trash" style="font-size:.7rem"></i></button>' +
                        '</td>' +
                        '</tr>'
                    );
                });
                // Guardar data para edición
                $('#indicadoresListBody').data('indicadores', data);
            });
        }

        // ── Editar desde lista ─────────────────────────────────────────────
        $(document).on('click', '.btnEditarIndicador', function() {
            var id   = $(this).data('id');
            var data = $('#indicadoresListBody').data('indicadores') || [];
            var ind  = data.find(function(i) { return i.id == id; });
            if (!ind) return;
            cargarIndicador(ind);
            $('#modalIndicadorTitulo').html('<i class="fa fa-ruler-combined mr-2"></i>Editar Ficha de Indicador');
            $('#modalIndicadorSubtitulo').text(ind.nombre);
            $('#modalIndicadoresList').modal('hide');
            $('#modalIndicador').modal('show');
        });

        // ── Eliminar desde lista ───────────────────────────────────────────
        $(document).on('click', '.btnEliminarIndicador', function() {
            var id     = $(this).data('id');
            var nombre = $(this).data('nombre');
            Swal.fire({
                title: '¿Eliminar indicador?',
                html: '<strong>' + nombre + '</strong>',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            }).then(function(r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: '{{ url("pei-profiles") }}/' + _peiProfileId + '/indicadores/' + id,
                    type: 'DELETE',
                    success: function() {
                        toastr.success('Indicador eliminado.');
                        cargarListaIndicadores();
                    }
                });
            });
        });

        // ── Guardar ficha ──────────────────────────────────────────────────
        $('#btnGuardarIndicador').on('click', function() {
            var nombre = $.trim($('#ind_nombre').val());
            var dim    = $('input[name="ind_dimension"]:checked').val();
            var amb    = $('input[name="ind_ambito"]:checked').val();
            var frec   = $('input[name="ind_frecuencia"]:checked').val();
            var cob    = $('input[name="ind_cobertura"]:checked').val();
            var sent   = $('input[name="ind_sentido"]:checked').val();

            if (!nombre)  { toastr.warning('El nombre del indicador es obligatorio.'); return; }
            if (!dim)     { toastr.warning('Seleccioná la dimensión del indicador.'); return; }
            if (!amb)     { toastr.warning('Seleccioná el ámbito del indicador.'); return; }
            if (!frec)    { toastr.warning('Seleccioná la frecuencia de medición.'); return; }
            if (!cob)     { toastr.warning('Seleccioná la cobertura geográfica.'); return; }
            if (!sent)    { toastr.warning('Seleccioná el sentido del indicador.'); return; }

            var payload = {
                nombre:                   nombre,
                codigo_letras:            $.trim($('#ind_codigo_letras').val()),
                codigo_numeros:           $.trim($('#ind_codigo_numeros').val()),
                dimension:                dim,
                ambito:                   amb,
                descripcion:              $.trim($('#ind_descripcion').val()),
                variables:                $.trim($('#ind_variables').val()),
                formula:                  $.trim($('#ind_formula').val()),
                unidad_medida:            $.trim($('#ind_unidad_medida').val()),
                frecuencia:               frec,
                frecuencia_otro:          $.trim($('#ind_frecuencia_otro').val()),
                cobertura:                cob,
                sentido:                  sent,
                linea_base_anio:          $('#ind_linea_base_anio').val() || null,
                linea_base_valor:         $.trim($('#ind_linea_base_valor').val()),
                fuente:                   $.trim($('#ind_fuente').val()),
                dependencia_responsable:  $.trim($('#ind_dependencia_responsable').val()),
                comentarios:              $.trim($('#ind_comentarios').val()),
            };

            // Metas como campos indexados (compatible con Laravel array validation)
            var metas = leerMetas();
            metas.forEach(function(m, i) {
                payload['metas[' + i + '][anio]']  = m.anio;
                payload['metas[' + i + '][valor]'] = m.valor;
            });

            var url    = _indicadorEditId
                ? '{{ url("pei-profiles") }}/' + _peiProfileId + '/indicadores/' + _indicadorEditId
                : '{{ url("pei-profiles") }}/' + _peiProfileId + '/indicadores';
            var method = _indicadorEditId ? 'PUT' : 'POST';

            $.ajax({
                url: url, type: method, data: payload,
                success: function(res) {
                    toastr.success(_indicadorEditId ? 'Indicador actualizado.' : 'Indicador creado.');
                    $('#modalIndicador').modal('hide');
                },
                error: function(xhr) {
                    var e = xhr.responseJSON?.errors;
                    if (e) $.each(e, (k,v) => toastr.error(v[0]));
                    else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
                }
            });
        });
        // ══ Fin módulo indicadores ══════════════════════════════════════════

        // Verifica si hay datos en el localStorage
        var type = '{{ $type }}'
        var storedType = localStorage.getItem('type');

        var storedTaskID = localStorage.getItem('taskID');
        if (storedTaskID !== null && storedType === 'master') {
            // Si se encontraron datos en el localStorage, oculta el primer nav y muestra el segundo
            document.getElementById('default-nav').style.display = 'none';
            document.getElementById('dynamic-nav').style.display = 'block';

            // Crea la URL dinámica para el segundo nav
            var tasksShowLinkDynamic = document.getElementById('tasks-show-link-dynamic');
            if (tasksShowLinkDynamic) {
                var dynamicURL = "{{ route('tasks.show', 'taskIDPlaceholder') }}";
                dynamicURL = dynamicURL.replace('taskIDPlaceholder', storedTaskID);
                tasksShowLinkDynamic.innerHTML = '<a href="' + dynamicURL + '">Lista de Tareas</a>';
            }
        } else if (storedType !== null && storedTaskID === null) {
            document.getElementById('default-nav').style.display = 'none';
            document.getElementById('dynamic-nav').style.display = 'block';

        }
    </script>

<script>
// ══ NOTIFICACIONES PEI ═══════════════════════════════════════════════════════
// Handler global del botón Agregar Período de RI (delegado, se registra 1 sola vez)
$(document).on('click', '.btnAgregarRiMetaBtn', function() {
    var idx = $('#riMetasContainer .ri-meta-row').length;
    $('#riMetasContainer').append(
        '<div class="col-md-4 mb-1 ri-meta-row" data-idx="' + idx + '">' +
        '<div class="input-group input-group-sm">' +
            '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.7rem">Año</span></div>' +
            '<input type="number" class="form-control ri-meta-anio" placeholder="{{ date("Y") }}" min="2020" max="2100">' +
            '<input type="text" class="form-control ri-meta-valor" placeholder="Meta (%, nº, decimal)">' +
            '<div class="input-group-append"><button type="button" class="btn btn-circle btn-danger btn-sm ri-meta-remove" title="Eliminar"><i class="fa fa-times"></i></button></div>' +
        '</div></div>'
    );
});
$(document).on('click', '.ri-meta-remove', function() {
    $(this).closest('.ri-meta-row').remove();
});

// Eventos de toggle para acordeón Vinculación Presupuestaria
$(document).on('show.bs.collapse', '#riVinculacionCollapse', function() {
    $('#ri_vinculacion_toggle_label').text('Clic para plegar');
    $('#ri_vinculacion_block .ri-chevron-icon').css('transform', 'rotate(180deg)');
});
$(document).on('hide.bs.collapse', '#riVinculacionCollapse', function() {
    $('#ri_vinculacion_toggle_label').text('Clic para desplegar');
    $('#ri_vinculacion_block .ri-chevron-icon').css('transform', 'rotate(0deg)');
});
$('#btnNotificarTodosPei').on('click', function() {
    var profileId = $(this).data('profile');
    Swal.fire({
        title: '¿Notificar a todos los responsables?',
        html: 'Se enviará un email a cada persona asignada como responsable en alguna acción del plan, con el listado de sus acciones y el link para reportar avance.',
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#2e7d32', cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa fa-paper-plane mr-1"></i> Sí, notificar',
        cancelButtonText: 'Cancelar',
    }).then(function(result) {
        if (!result.isConfirmed) return;
        var $btn = $('#btnNotificarTodosPei').prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin mr-1"></i> Enviando...');
        $.ajax({
            url: '{{ url("admin/pei-profiles") }}/' + profileId + '/notificar-todos',
            type: 'POST',
            success: function(res) { toastr.success(res.message); },
            error: function(xhr)   { toastr.error(xhr.responseJSON?.message || 'Error al enviar.'); },
            complete: function()   { $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Notificar Responsables'); }
        });
    });
});

// ── Enlace público ────────────────────────────────────────────────────────────
var _tabsDisponibles = [
    { key: 'bsc',      label: 'Balanced Scorecard' },
    { key: 'matriz',   label: 'Matriz Estratégica' },
    { key: 'mecip',    label: 'Vista Jerárquica' },
    { key: 'planilla', label: 'Vista Personalizada (Planilla)' },
];

function _tabsCheckboxesHtml(seleccionadas) {
    var html = '<div style="margin:10px 0 4px;font-size:.82rem;font-weight:600;color:#334155">Pestañas visibles:</div>';
    html += '<div style="display:flex;flex-direction:column;gap:4px;margin-bottom:12px">';
    _tabsDisponibles.forEach(function(t) {
        var chk = seleccionadas.indexOf(t.key) >= 0 ? 'checked' : '';
        html += '<label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer">' +
            '<input type="checkbox" class="swal-tab-chk" value="' + t.key + '" ' + chk + '> ' + t.label + '</label>';
    });
    html += '</div>';
    return html;
}

function _getTabsSeleccionadas() {
    var tabs = [];
    document.querySelectorAll('.swal-tab-chk:checked').forEach(function(c){ tabs.push(c.value); });
    return tabs.length ? tabs : ['bsc'];
}

$('#btnPublicLink').on('click', function() {
    var profileId      = $(this).data('profile');
    var token          = $(this).data('token');
    var baseUrl        = '{{ url("/public/pei") }}/';
    var apiBase        = '{{ url("pei-profiles") }}/';
    var tabsActuales   = {!! json_encode($profile->public_tabs ?? ['bsc','matriz','mecip','planilla']) !!};

    if (token) {
        var pubUrl = baseUrl + token;
        Swal.fire({
            title: 'Enlace público activo',
            html: '<div class="text-left">' +
                '<p style="font-size:.85rem">Cualquier persona con este enlace puede ver el plan sin iniciar sesión.</p>' +
                '<div class="input-group mb-3">' +
                '<input type="text" id="swal_pub_url" class="form-control form-control-sm" readonly value="' + pubUrl + '">' +
                '<div class="input-group-append">' +
                '<button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById(\'swal_pub_url\').select();document.execCommand(\'copy\');toastr.success(\'Copiado!\')">Copiar</button>' +
                '</div></div>' +
                '<a href="' + pubUrl + '" target="_blank" class="btn btn-sm btn-primary w-100 mb-3"><i class="fa fa-external-link-alt mr-1"></i> Abrir vista pública</a>' +
                _tabsCheckboxesHtml(tabsActuales) +
                '</div>',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: '<i class="fa fa-sync mr-1"></i> Guardar y regenerar',
            denyButtonText: '<i class="fa fa-ban mr-1"></i> Revocar acceso',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#1976d2',
            denyButtonColor: '#dc3545',
            preConfirm: function() { return _getTabsSeleccionadas(); },
        }).then(function(result) {
            if (result.isConfirmed) {
                generarToken(profileId, apiBase, result.value);
            } else if (result.isDenied) {
                $.ajax({
                    url: apiBase + profileId + '/public-token',
                    type: 'DELETE',
                    success: function() {
                        toastr.success('Acceso público revocado.');
                        $('#btnPublicLink').data('token','')
                            .html('<i class="fa fa-share-alt mr-1"></i> Generar enlace público');
                    }
                });
            }
        });
    } else {
        // Sin token — mostrar selector de tabs antes de generar
        Swal.fire({
            title: 'Generar enlace público',
            html: '<div class="text-left">' +
                '<p style="font-size:.85rem">Seleccioná las pestañas que estarán disponibles en la vista pública.</p>' +
                _tabsCheckboxesHtml(['bsc','matriz','mecip','planilla']) +
                '</div>',
            confirmButtonText: '<i class="fa fa-share-alt mr-1"></i> Generar enlace',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#1976d2',
            preConfirm: function() { return _getTabsSeleccionadas(); },
        }).then(function(result) {
            if (result.isConfirmed) {
                generarToken(profileId, apiBase, result.value);
            }
        });
    }
});

function generarToken(profileId, apiBase, tabs) {
    $.ajax({
        url: apiBase + profileId + '/public-token',
        type: 'POST',
        data: JSON.stringify({ tabs: tabs }),
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            var pubUrl = res.url;
            $('#btnPublicLink').data('token', res.token)
                .html('<i class="fa fa-share-alt mr-1"></i> Enlace público');
            Swal.fire({
                title: '¡Enlace generado!',
                html: '<div class="text-left">' +
                    '<p style="font-size:.85rem">Compartí este enlace para acceso de solo lectura.</p>' +
                    '<div class="input-group">' +
                    '<input type="text" id="swal_new_url" class="form-control form-control-sm" readonly value="' + pubUrl + '">' +
                    '<div class="input-group-append">' +
                    '<button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById(\'swal_new_url\').select();document.execCommand(\'copy\');toastr.success(\'Copiado!\')">Copiar</button>' +
                    '</div></div>' +
                    '<a href="' + pubUrl + '" target="_blank" class="btn btn-sm btn-primary w-100 mt-2"><i class="fa fa-external-link-alt mr-1"></i> Abrir</a>' +
                    '</div>',
                icon: 'success',
                confirmButtonText: 'Listo',
            });
        },
        error: function() { toastr.error('Error al generar el enlace.'); }
    });
}

$(document).on('click', '.btnNotificarAccion', function() {
    var accionId  = $(this).data('id');
    var profileId = $(this).data('profile') || '{{ $profile->id }}';
    var $btn      = $(this);
    Swal.fire({
        title: '¿Notificar al responsable de esta acción?',
        text: 'Se enviará un email al responsable asignado.',
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#2e7d32', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, notificar', cancelButtonText: 'Cancelar',
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $btn.prop('disabled', true);
        $.ajax({
            url:  '{{ url("admin/pei-profiles") }}/' + profileId + '/acciones/' + accionId + '/notificar',
            type: 'POST',
            success: function(res) { toastr.success(res.message); },
            error: function(xhr)   { toastr.warning(xhr.responseJSON?.message || 'No se pudo enviar.'); },
            complete: function()   { $btn.prop('disabled', false); }
        });
    });
});
// ══ FIN NOTIFICACIONES ════════════════════════════════════════════════════════
</script>
<script>
// Modal detalle indicador (accordion) — usa el modal de ficha técnica reutilizable
$(document).on('click', '.btn-ver-indicador', function() {
    const id      = $(this).data('id');
    const profile = $(this).data('profile');

    // Obtener todos los indicadores del perfil y encontrar el solicitado
    $.getJSON(`/pei-profiles/${profile}/indicadores`, function(data) {
        var ind = data.find(function(i) { return i.id == id; });
        if (!ind) { toastr.error('Indicador no encontrado.'); return; }

        // Resetear formulario
        $('#ind_id').val(ind.id);
        $('#ind_nombre').val(ind.nombre);
        $('#ind_codigo_letras').val(ind.codigo_letras);
        $('#ind_codigo_numeros').val(ind.codigo_numeros);

        // Radios
        $.each(['dimension','ambito','frecuencia','cobertura','sentido'], function(i, campo) {
            var val = ind[campo];
            var radio = $('input[name="ind_' + campo + '"][value="' + val + '"]');
            radio.prop('checked', true).trigger('change');
        });
        if (ind.frecuencia === 'otro') $('#ind_frecuencia_otro').val(ind.frecuencia_otro);

        // Campos de texto
        $('#ind_descripcion').val(ind.descripcion);
        $('#ind_variables').val(ind.variables);
        $('#ind_formula').val(ind.formula);
        $('#ind_unidad_medida').val(ind.unidad_medida);
        $('#ind_linea_base_anio').val(ind.linea_base_anio);
        $('#ind_linea_base_valor').val(ind.linea_base_valor);
        $('#ind_fuente').val(ind.fuente);
        $('#ind_dependencia_responsable').val(ind.dependencia_responsable);
        $('#ind_comentarios').val(ind.comentarios);

        // Metas
        $('#metasContainer').empty();
        if (ind.metas && ind.metas.length) {
            ind.metas.forEach(function(m) {
                $('#metasContainer').append(
                    '<div class="col-md-3 mb-2 meta-row">' +
                    '<div class="input-group input-group-sm">' +
                        '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.72rem">Año</span></div>' +
                        '<input type="number" class="form-control meta-anio" value="' + m.anio + '" min="2020" max="2100">' +
                        '<input type="text" class="form-control meta-valor" value="' + m.valor + '">' +
                        '<div class="input-group-append"><button type="button" class="btn btn-outline-danger btn-remove-meta" style="font-size:.72rem"><i class="fa fa-times"></i></button></div>' +
                    '</div></div>'
                );
            });
        }

        // Configurar modal en modo solo lectura
        $('#modalIndicadorTitulo').html('<i class="fa fa-eye mr-2"></i>Ficha del Indicador');
        $('#modalIndicadorSubtitulo').text(ind.nombre);
        $('#btnGuardarIndicador').hide();
        $('#modalIndicador').modal('show');
    }).fail(function() {
        toastr.error('Error al cargar la ficha del indicador.');
    });
});

// Mostrar botón guardar al cerrar el modal
$('#modalIndicador').on('hidden.bs.modal', function() {
    $('#btnGuardarIndicador').show();
});

// ── Handlers de Acciones Operativas ──────────────────────────────────────────
function initIndicadorSelect2(val) {
    var $sel = $('#ini_indicador_id');
    if ($sel.hasClass('select2-hidden-accessible')) {
        $sel.select2('destroy');
    }
    $sel.val(val || '').trigger('change');
    $sel.select2({
        dropdownParent: $('#modalNuevaIniciativaMejora'),
        placeholder: '-- Sin Indicador Vincular --',
        allowClear: true,
        width: '100%'
    });
}

function initResponsableSelect2(valString) {
    var $sel = $('#ini_responsable');
    if ($sel.hasClass('select2-hidden-accessible')) {
        $sel.select2('destroy');
    }
    
    var rText = valString || '';
    var rArray = rText.split(/\s*[\+\,]\s*/).map(function(s) { return s.trim(); }).filter(Boolean);

    rArray.forEach(function(val) {
        if ($sel.find('option[value="' + val.replace(/"/g, '\\"') + '"]').length === 0) {
            var newOption = new Option(val, val, true, true);
            $sel.append(newOption);
        }
    });

    $sel.val(rArray).trigger('change');
    $sel.select2({
        dropdownParent: $('#modalNuevaIniciativaMejora'),
        placeholder: 'Seleccionar una o más dependencias PEI...',
        tags: true,
        tokenSeparators: [',', '+'],
        width: '100%'
    });
}

window.abrirModalNuevaIniciativa = function(peiProfileId, accionName) {
    $('#formNuevaIniciativaMejora')[0].reset();
    $('#ini_iniciativa_id').val('');
    $('#ini_pei_profile_id').val(peiProfileId);
    $('#ini_accion_pei_label').text(accionName);
    initIndicadorSelect2('');
    initResponsableSelect2('');
    $('#modalNuevaIniciativaMejora .modal-title').html('<i class="fa fa-plus-circle text-warning mr-2"></i> Nueva Acción Operativa');
    $('#modalNuevaIniciativaMejora').modal('show');
};

window.abrirModalEditarIniciativa = function(iniciativa, accionName) {
    $('#formNuevaIniciativaMejora')[0].reset();
    $('#ini_iniciativa_id').val(iniciativa.id);
    $('#ini_pei_profile_id').val(iniciativa.pei_profile_id);
    $('#ini_accion_pei_label').text(accionName);

    $('#ini_accion').val(iniciativa.accion);
    $('#ini_momento').val(iniciativa.momento);
    $('#ini_estado').val(iniciativa.estado);
    initResponsableSelect2(iniciativa.responsable || '');
    $('#ini_plazo').val(iniciativa.plazo || '');
    $('#ini_kpi').val(iniciativa.kpi || '');
    initIndicadorSelect2(iniciativa.indicador_id || '');

    $('#modalNuevaIniciativaMejora .modal-title').html('<i class="fa fa-pencil text-warning mr-2"></i> Editar Acción Operativa: ' + (iniciativa.codigo || ''));
    $('#modalNuevaIniciativaMejora').modal('show');
};

window.eliminarIniciativa = function(iniciativaId, codigo) {
    Swal.fire({
        title: '¿Eliminar Acción Operativa ' + (codigo || '') + '?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmColor: '#ef4444',
        cancelColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: { container: 'swal-over-modal' }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("plan-maestro/acciones") }}/' + iniciativaId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.ok) {
                        toastr.success('Acción Operativa ' + (codigo || '') + ' eliminada correctamente.');
                        if (typeof window.recargarAcordeon === 'function') {
                            window.recargarAcordeon();
                        } else if (typeof loadAccordion === 'function') {
                            loadAccordion();
                        }
                    }
                },
                error: function() {
                    toastr.error('Error al eliminar la Acción Operativa.');
                }
            });
        }
    });
};

window.cambiarEstadoIniciativa = function(iniciativaId, nuevoEstado) {
    $.ajax({
        url: '{{ url("plan-maestro/acciones") }}/' + iniciativaId + '/estado',
        type: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}',
            estado: nuevoEstado
        },
        success: function(res) {
            if (res.ok) {
                toastr.success('Estado actualizado a ' + nuevoEstado);
                if (typeof window.recargarAcordeon === 'function') {
                    window.recargarAcordeon(iniciativaId);
                } else if (typeof loadAccordion === 'function') {
                    loadAccordion();
                }
            }
        },
        error: function() {
            toastr.error('No se pudo actualizar el estado de la iniciativa.');
        }
    });
};

$('#formNuevaIniciativaMejora').on('submit', function(e) {
    e.preventDefault();
    var $btn = $('#btnGuardarIniciativa');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

    $.ajax({
        url: '{{ route("plan-maestro.iniciativa.store") }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Iniciativa');
            if (res.ok) {
                $('#modalNuevaIniciativaMejora').modal('hide');
                toastr.success(res.mensaje || 'Acción Operativa guardada.');
                var editedId = res.iniciativa ? res.iniciativa.id : null;
                if (typeof window.recargarAcordeon === 'function') {
                    window.recargarAcordeon(editedId);
                } else if (typeof loadAccordion === 'function') {
                    loadAccordion();
                }
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Iniciativa');
            toastr.error(xhr.responseJSON?.message || 'Error al guardar la Acción Operativa.');
        }
    });
});

// ── Reordenar Estructura PEI (Drag & Drop con Auto-Guardado) ──────────────
window.peiTreeReordered = false;

$('#btnAbrirModalReordenarPei').on('click', function() {
    var profileId = $(this).data('profile');
    var $btn = $(this);
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Cargando...');

    $.ajax({
        url: '{{ url("pei-profiles") }}/' + profileId + '/tree-draggable',
        type: 'GET',
        success: function(html) {
            $btn.prop('disabled', false).html('<i class="fa fa-sort-amount-asc mr-1 text-warning"></i> REORDENAR PEI');
            $('#containerModalReordenarPei').html(html);
            $('#modalReordenarPei').modal('show');
            initPeiTreeSortable(profileId);
        },
        error: function() {
            $btn.prop('disabled', false).html('<i class="fa fa-sort-amount-asc mr-1 text-warning"></i> REORDENAR PEI');
            toastr.error('No se pudo cargar la estructura para reordenar.');
        }
    });
});

function initPeiTreeSortable(profileId) {
    document.querySelectorAll('#contenedorArbolDraggablePei .sortable-pei-group').forEach(function(el) {
        if (el._sortable) return;
        var groupLevel = el.getAttribute('data-level') || 'sub';

        el._sortable = Sortable.create(el, {
            group: 'pei-group-' + groupLevel,
            handle: '.drag-handle-pei',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function() {
                autoGuardarOrdenTreePei(profileId);
            }
        });
    });
}

function autoGuardarOrdenTreePei(profileId) {
    var items = [];

    $('#contenedorArbolDraggablePei li.nodo-pei-item').each(function() {
        var id = $(this).attr('data-id');
        var dbId = $(this).attr('data-db-id') || id;
        var type = $(this).attr('data-type') || 'profile';
        var parentUl = $(this).closest('ul.sortable-pei-group');
        var parentId = parentUl.attr('data-parent-id');
        var orderIndex = $(this).index();

        items.push({
            id: id,
            db_id: dbId,
            type: type,
            parent_id: parentId,
            order_item: orderIndex
        });
    });

    $('#lblEstadoReordenamiento').html('<i class="fa fa-spinner fa-spin text-info mr-1"></i> Guardando cambios automáticamente...');

    $.ajax({
        url: '{{ url("pei-profiles") }}/' + profileId + '/reordenar-tree',
        type: 'POST',
        data: { items: items, _token: '{{ csrf_token() }}' },
        success: function(res) {
            window.peiTreeReordered = true;
            $('#lblEstadoReordenamiento').html('<i class="fa fa-check-circle text-success mr-1"></i> ¡Guardado automáticamente en tiempo real!');
            toastr.success('¡Posición actualizada!', '', { timeOut: 1200 });
        },
        error: function(xhr) {
            $('#lblEstadoReordenamiento').html('<i class="fa fa-exclamation-triangle text-danger mr-1"></i> Error al guardar la posición.');
            toastr.error(xhr.responseJSON?.error || 'Error al guardar el reordenamiento.');
        }
    });
}

$(document).on('hidden.bs.modal', '#modalReordenarPei', function() {
    if (window.peiTreeReordered) {
        window.peiTreeReordered = false;
        if (typeof window.recargarAcordeon === 'function') {
            window.recargarAcordeon();
        } else if (typeof loadAccordion === 'function') {
            loadAccordion();
        } else {
            location.reload();
        }
    }
});

$(document).on('click', '.btn-toggle-pei-children', function() {
    var $icon = $(this).find('i');
    var $children = $(this).closest('li.nodo-pei-item').find('> .nodo-pei-children');
    $children.slideToggle(150, function() {
        if ($children.is(':visible')) {
            $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
        } else {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        }
    });
});

$(document).on('click', '#btnExpandirTodoTreePei', function() {
    $('#contenedorArbolDraggablePei .nodo-pei-children').slideDown(150);
    $('#contenedorArbolDraggablePei .btn-toggle-pei-children i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
});

$(document).on('click', '#btnColapsarTodoTreePei', function() {
    $('#contenedorArbolDraggablePei .nodo-pei-children').slideUp(150);
    $('#contenedorArbolDraggablePei .btn-toggle-pei-children i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
});

// ── LÓGICA DEL MODAL BUSCADOR DEL PLAN MAESTRO / INICIATIVAS ──
$('#modalBuscadorIniciativasPlanMaestro').on('show.bs.modal', function () {
    cargarListaIniciativasModal();
});

var currentIniModalStatusFilter = 'all';

function cargarListaIniciativasModal() {
    var $container = $('#listaIniciativasModalContainer');
    $container.empty();

    var $cards = $('[id^="ini_card_"]');
    if ($cards.length === 0) {
        $container.html('<div class="text-center p-4 text-muted font-weight-bold"><i class="fa fa-info-circle mr-1"></i> No hay iniciativas de mejora registradas aún en este perfil PEI.</div>');
        $('#lblTotalIniciativasModal').text('Total: 0 iniciativas');
        return;
    }

    var total = $cards.length;
    $('#lblTotalIniciativasModal').text('Total: ' + total + ' iniciativas registradas');

    $cards.each(function() {
        var $c = $(this);
        var iniId = $c.attr('id').replace('ini_card_', '');
        var codigo = $c.find('.badge-code-ini, strong, span.font-weight-bold').first().text().trim() || ('#INI-' + iniId);
        var titulo = $c.find('h6, .ini-title, div.font-weight-bold').first().text().trim() || $c.text().substring(0, 80).trim();
        var estado = $c.data('estado') || ($c.text().indexOf('EJECUTADO') >= 0 ? 'EJECUTADO' : ($c.text().indexOf('EN CURSO') >= 0 ? 'EN CURSO' : 'PENDIENTE'));
        
        var badgeBg = estado === 'EJECUTADO' ? 'badge-success' : (estado === 'EN CURSO' ? 'badge-warning' : 'badge-danger');

        var itemHtml = `
            <a href="javascript:void(0)" onclick="irAIniciativaDesdeModal('${iniId}')" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center item-ini-modal mb-2 border rounded p-3 shadow-xs" data-status="${estado}" data-search="${(codigo + ' ' + titulo).toLowerCase()}" style="border-radius: 12px; transition: all 0.2s ease;">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <span class="badge badge-dark mr-2" style="font-size: 0.75rem;">${codigo}</span>
                        <span class="badge ${badgeBg} font-weight-bold px-2 py-1" style="font-size: 0.7rem;">${estado}</span>
                    </div>
                    <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">${titulo}</div>
                </div>
                <div class="text-right">
                    <span class="btn btn-sm btn-outline-primary rounded-circle"><i class="fa fa-arrow-right"></i></span>
                </div>
            </a>
        `;
        $container.append(itemHtml);
    });
}

function filtrarIniciativasModalStatus(btn, status) {
    $('.btn-filter-ini-modal').removeClass('active btn-dark').addClass('btn-outline-secondary');
    $(btn).removeClass('btn-outline-secondary btn-outline-success btn-outline-warning btn-outline-danger').addClass('active btn-dark');
    currentIniModalStatusFilter = status;
    filtrarIniciativasModal();
}

function filtrarIniciativasModal() {
    var query = ($('#inputBuscarIniciativasModal').val() || '').toLowerCase();
    var countVisible = 0;

    $('.item-ini-modal').each(function() {
        var $item = $(this);
        var st = $item.data('status');
        var searchTxt = $item.data('search');

        var matchesStatus = (currentIniModalStatusFilter === 'all' || st === currentIniModalStatusFilter);
        var matchesQuery  = (!query || searchTxt.indexOf(query) >= 0);

        if (matchesStatus && matchesQuery) {
            $item.show();
            countVisible++;
        } else {
            $item.hide();
        }
    });

    $('#lblTotalIniciativasModal').text('Mostrando: ' + countVisible + ' iniciativas');
}

function irAIniciativaDesdeModal(iniId) {
    $('#modalBuscadorIniciativasPlanMaestro').modal('hide');
    setTimeout(function() {
        var $card = $('#ini_card_' + iniId);
        if ($card.length) {
            $('html, body').animate({
                scrollTop: $card.offset().top - 120
            }, 500);
            $card.css('transition', 'all 0.4s ease')
                 .css('box-shadow', '0 0 0 4px #f59e0b')
                 .css('transform', 'scale(1.02)');
            setTimeout(function() {
                $card.css('box-shadow', '').css('transform', '');
            }, 2000);
        }
    }, 300);
}
</script>

<!-- MODAL DE MAPA DE INICIATIVAS / PLAN MAESTRO -->
<div class="modal fade" id="modalBuscadorIniciativasPlanMaestro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title font-weight-bold d-flex align-items-center mb-0">
                    <i class="fa fa-bullseye text-warning mr-2" style="font-size: 1.3rem;"></i>
                    <span>Plan Maestro — Mapa de Iniciativas de Mejora Continua</span>
                </h5>
                <button type="button" class="close text-white opacity-9" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="mb-3">
                    <div class="input-group shadow-xs mb-3" style="border-radius: 10px; overflow: hidden;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fa fa-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0 pl-0" id="inputBuscarIniciativasModal" placeholder="Buscar por código, nombre o responsable..." onkeyup="filtrarIniciativasModal()">
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between bg-white p-2.5 rounded border shadow-xs" style="border-radius: 10px;">
                        <span class="text-muted small font-weight-bold mb-2 mb-md-0"><i class="fa fa-filter text-warning mr-1"></i> Filtrar por estado:</span>
                        <div class="d-flex flex-wrap" style="gap: 5px;">
                            <button type="button" class="btn btn-sm btn-dark active btn-filter-ini-modal" data-status="all" onclick="filtrarIniciativasModalStatus(this, 'all')">Todos</button>
                            <button type="button" class="btn btn-sm btn-outline-success btn-filter-ini-modal" data-status="EJECUTADO" onclick="filtrarIniciativasModalStatus(this, 'EJECUTADO')">Ejecutados</button>
                            <button type="button" class="btn btn-sm btn-outline-warning btn-filter-ini-modal" data-status="EN CURSO" onclick="filtrarIniciativasModalStatus(this, 'EN CURSO')">En Curso</button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-filter-ini-modal" data-status="PENDIENTE" onclick="filtrarIniciativasModalStatus(this, 'PENDIENTE')">Pendientes</button>
                        </div>
                    </div>
                </div>

                <div id="listaIniciativasModalContainer" class="list-group shadow-xs">
                    <!-- Dinámico por JS -->
                </div>
            </div>
            <div class="modal-footer bg-white px-4 py-3" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                <span class="text-muted small mr-auto" id="lblTotalIniciativasModal">Total: 0 iniciativas</span>
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: ANÁLISIS Y CRUCE DE AMBIENTES FODA -->
<div class="modal fade" id="modalFodaCrossing" tabindex="-1" role="dialog" aria-labelledby="modalFodaCrossingTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 1550px; width: 96%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalFodaCrossingTitulo">
                    <i class="fa fa-random text-warning mr-2"></i> Análisis FODA & Cruce de Ambientes
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="modalFodaCrossingBody" style="background-color: #f8fafc; min-height: 400px; max-height: 85vh; overflow-y: auto;">
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i>
                    <div>Cargando matriz de análisis y cruce de ambientes FODA...</div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="fa fa-info-circle text-info mr-1"></i> Control total para estructurar y cruzar estrategias FO, DO, FA y DA asociadas al PEI.</small>
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var currentFodaUrl = '';
    window.recargarModalFodaCrossing = function () {
        if (!currentFodaUrl) return;
        $.get(currentFodaUrl, { modal: 1 }, function (html) {
            $('#modalFodaCrossingBody').html(html);
        });
    };

    function abrirModalFodaCrossing(btn, e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var $btn = $(btn);
        var url = $btn.data('url');
        var planName = $btn.data('name') || 'Plan Estratégico';
        currentFodaUrl = url;

        $('#modalFodaCrossingTitulo').html('<i class="fa fa-random text-warning mr-2"></i> Análisis FODA & Cruce de Ambientes — ' + planName);
        $('#modalFodaCrossingBody').html('<div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i><div>Cargando matriz de análisis y cruce de ambientes FODA...</div></div>');
        $('#modalFodaCrossing').modal('show');

        $.get(url, { modal: 1 }, function (html) {
            $('#modalFodaCrossingBody').html(html);
        }).fail(function (xhr) {
            var msg = xhr.responseJSON?.message || 'Ocurrió un error al cargar el análisis FODA.';
            $('#modalFodaCrossingBody').html('<div class="alert alert-danger mb-0"><i class="fa fa-exclamation-circle mr-2"></i> ' + msg + '</div>');
        });
    }

    $(document).on('click', '.btnVerFodaCrossing', function (e) {
        abrirModalFodaCrossing(this, e);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<div id="containerModalReordenarPei"></div>
@include('admin.planificacion.peis/peis.partials.chat_drawer')
@stop
