@extends('layouts.master')

@section('title', 'Perfil de Usuario y Gamificación')

@section('content')
<div class="container-fluid py-3">

    {{-- ── 1. Encabezado Unificado del Módulo (Estilo Cyan Card de Material Dashboard) ── --}}
    <div class="card my-3" style="border: none; background: transparent; box-shadow: none;">
        <div class="card-header card-header-info p-3" style="background: linear-gradient(60deg, #26c6da, #00acc1); border-radius: 6px; box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(0, 188, 212, .4);">
            <h4 class="card-title text-white font-weight-bold mb-1" style="font-size: 1.3rem;">
                <i class="fa fa-user-circle mr-2"></i>Perfil de Usuario & Gamificación
            </h4>
            <p class="card-category text-white-50 mb-0" style="font-size: .85rem;">
                Gestión de datos personales, contraseña, nivel de reputación, insignias e historial de actividad
            </p>
        </div>
    </div>

    {{-- ── 2. Breadcrumbs Unificados ── --}}
    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perfil de Usuario</li>
        </ol>
    </nav>

    {{-- ── 3. Banner Principal de Perfil (Alto Contraste y Colores Nítidos) ── --}}
    <div class="card border mb-4 bg-white shadow-sm" style="border-radius: 12px; border-color: #cbd5e1 !important; background-color: #ffffff !important;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-7 mb-3 mb-md-0">
                            <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start text-center text-md-left">
                                {{-- Avatar con botón de cámara flotante --}}
                                <div class="position-relative mb-3 mb-md-0 mr-md-3 mx-md-0 mx-auto" style="width: 84px; height: 84px; flex-shrink: 0;">
                                    <img id="user_avatar_img" src="{{ $targetUser->avatar_url }}" alt="{{ $targetUser->name }}"
                                         class="rounded-circle shadow"
                                         style="width: 84px; height: 84px; border: 3px solid #00acc1 !important; object-fit: cover;">

                                    @if(Auth::id() === $targetUser->id)
                                    <button type="button" class="btn btn-sm btn-info rounded-circle shadow position-absolute"
                                            style="bottom: 0; right: 0; width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center; border: 2px solid #ffffff; z-index: 5; background-color: #00acc1 !important;"
                                            data-toggle="modal" data-target="#modalAvatar" title="Cambiar foto de perfil">
                                        <i class="fa fa-camera text-white" style="font-size: 13px;"></i>
                                    </button>
                                    @endif
                                </div>
                                <div class="w-100">
                                    <div class="d-flex align-items-center justify-content-center justify-content-md-start flex-wrap" style="gap: .5rem">
                                        <h4 class="font-weight-bold mb-0" style="color: #0f172a !important; font-size: 1.4rem;">{{ $targetUser->name }}</h4>
                                        <span class="badge badge-pill badge-info py-1 px-2 font-weight-bold text-white" style="font-size: .75rem; background-color: #00acc1 !important;">
                                            {{ $targetUser->roles->pluck('name')->implode(', ') ?: 'Usuario' }}
                                        </span>
                                    </div>
                                    <div class="mt-1" style="color: #475569 !important; font-size: .9rem;">
                                        <div class="d-block mb-1"><i class="fa fa-envelope text-info mr-1"></i> {{ $targetUser->email }}</div>
                                        @if($targetUser->group)
                                        <div class="d-block"><i class="fa fa-sitemap text-info mr-1"></i> {{ $targetUser->group->name }}</div>
                                        @endif
                                    </div>
                                    @if(Auth::id() === $targetUser->id)
                                    <div class="mt-2 d-flex justify-content-center justify-content-md-start">
                                        <button type="button" class="btn btn-sm btn-outline-info py-1 px-3 font-weight-bold w-100 w-sm-auto" style="font-size:.78rem; text-transform: none; border-color: #00acc1; color: #00acc1; max-width: 240px;" data-toggle="modal" data-target="#modalAvatar">
                                            <i class="fa fa-upload mr-1"></i> Cambiar Foto de Perfil
                                        </button>
                                    </div>
                                    @endif
                        </div>
                    </div>
                </div>

                {{-- Resumen de Nivel y Puntos --}}
                <div class="col-md-5 text-md-right border-left pl-md-4" style="border-color: #e2e8f0 !important;">
                    <div class="card border-0 rounded mb-3" style="background: #fff4c7; border: 1px solid #ffd25d; width: 100%; box-shadow: 0 4px 15px rgba(255, 214, 46, .12);">
                        <div class="card-body text-center py-3 px-3" style="background: linear-gradient(180deg, rgba(255,244,199,.95), rgba(255,238,166,.95));">
                            <small class="text-uppercase d-block font-weight-bold mb-2" style="font-size: .65rem; color: #b8860b;">Reputación</small>
                            <span class="h3 font-weight-bold text-dark mb-0">⭐ {{ number_format($gamification['total_points']) }}</span>
                            <small class="d-block text-muted mt-1" style="font-size: .65rem">puntos acumulados (válidos)</small>
                            @if(($gamification['orphaned_count'] ?? 0) > 0)
                            <small class="d-block text-warning mt-1" style="font-size: .62rem">
                                <i class="fa fa-exclamation-triangle"></i>
                                {{ $gamification['orphaned_count'] }} registro(s) excluido(s) por referencia inválida
                            </small>
                            @endif
                        </div>
                    </div>
                    <div class="card border-0 rounded" style="background: #c9eef8; border: 1px solid #64d4ed; width: 100%; box-shadow: 0 4px 15px rgba(33, 150, 243, .08);">
                        <div class="card-body text-center py-3 px-3" style="background: linear-gradient(180deg, rgba(201,238,248,.96), rgba(181,231,245,.96));">
                            <small class="text-uppercase d-block font-weight-bold mb-2" style="font-size: .65rem; color: #007a90;">Rango Actual</small>
                            </span>
                            <small class="d-block font-weight-bold text-info" style="font-size: .65rem">{{ $gamification['level_badge'] }}</small>
                        </div>
                    </div>
            </div>

            {{-- Barra de Progreso de Nivel --}}
            <div class="mt-4 pt-3 border-top" style="border-color: #e2e8f0 !important;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small style="color: #334155 !important;">
                        <strong style="color: #0f172a !important;">Nivel {{ $gamification['level_number'] }}:</strong> {{ $gamification['level_name'] }}
                    </small>
                    <small style="color: #475569 !important;">
                        Próximo Nivel: <strong class="text-info font-weight-bold">{{ $gamification['next_level_name'] }}</strong> ({{ $gamification['progress_pct'] }}%)
                    </small>
                </div>
                <div class="progress bg-light" style="height: 10px; border-radius: 5px; border: 1px solid #e2e8f0;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         style="width: {{ $gamification['progress_pct'] }}%; background-color: #00acc1 !important;"
                         aria-valuenow="{{ $gamification['progress_pct'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 4. Filtro por Plan PEI con Select2 ── --}}
    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center mr-3 my-1">
                <i class="fa fa-filter text-info mr-2"></i>
                <span class="font-weight-bold text-dark small">Contexto de Gamificación:</span>
                <span class="badge badge-light border ml-2 text-dark font-weight-normal">
                    {{ $peiSeleccionado ? strip_tags($peiSeleccionado->name) : 'Consolidado Global (Todos los Planes PEI)' }}
                </span>
            </div>
            @if(auth()->user()->hasRole('Administrador'))
            <button type="button" id="btnRecalcularGamificacion" class="btn btn-sm btn-outline-warning mb-2 mb-sm-0 mr-sm-2 font-weight-bold" title="Recalcular retroactivamente todos los puntos e insignias de los equipos de trabajo">
                <i class="fa fa-sync-alt mr-1"></i> Recalcular Puntos Equipos
            </button>
            @endif
            <form method="GET" action="{{ route('user.profile', $targetUser->id) }}" class="form-inline my-1 w-100">
                <div class="input-group input-group-sm w-100">
                    <select name="pei_id" id="select2_pei_id" class="form-control form-control-sm select2" style="min-width: 220px; max-width: 100%; width: 100%;">
                        <option value="">— Todo el Historial Global —</option>
                        @foreach($peiPlanes as $p)
                        <option value="{{ $p->id }}" {{ ($selectedPeiId == $p->id) ? 'selected' : '' }}>
                            PEI: {{ strip_tags($p->name) }} @if($p->year_start)({{ \Carbon\Carbon::parse($p->year_start)->format('Y') }})@endif
                        </option>
                        @endforeach
                    </select>
                    @if($selectedPeiId)
                    <div class="input-group-append ml-2 mt-2 mt-sm-0">
                        <a href="{{ route('user.profile', ['id' => $targetUser->id, 'pei_id' => '']) }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row">

        {{-- ── Columna Izquierda: Datos Personales, Cambio de Contraseña e Insignias ── --}}
        <div class="col-lg-8 mb-4">

            {{-- Formulario para Datos Personales (Nombre y Correo) --}}
            @if(Auth::id() === $targetUser->id)
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-user-edit text-info mr-2"></i>Datos Personales — Nombre y Correo
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if(session('success_details'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-1"></i> {{ session('success_details') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.details') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right font-weight-bold small text-dark">Nombre Completo:</label>
                            <div class="col-md-7">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $targetUser->name) }}" required style="color: #0f172a !important;">
                                @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right font-weight-bold small text-dark">Correo Electrónico:</label>
                            <div class="col-md-7">
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $targetUser->email) }}" required style="color: #0f172a !important;">
                                @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right font-weight-bold small text-dark">Rol(es) en el Sistema:</label>
                            <div class="col-md-7 d-flex align-items-center">
                                <span class="badge badge-info py-1.5 px-3 font-weight-bold text-white shadow-xs" style="font-size: .82rem; background-color: #00acc1 !important; border-radius: 6px;">
                                    <i class="fa fa-user-shield mr-1.5"></i> {{ $targetUser->roles->pluck('name')->implode(', ') ?: 'Sin Rol Asignado' }}
                                </span>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-7 offset-md-4">
                                <button type="submit" class="btn btn-info font-weight-bold" style="background-color: #00acc1 !important; border-color: #00acc1 !important; text-transform: none;">
                                    <i class="fa fa-save mr-1"></i> Guardar Datos Personales
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Formulario para Cambio de Contraseña --}}
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-key text-info mr-2"></i>Seguridad de la Cuenta — Cambiar Contraseña
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if(session('success_password'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-1"></i> {{ session('success_password') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.password') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="old_password" class="col-md-4 col-form-label text-md-right font-weight-bold small text-dark">Contraseña Actual:</label>
                            <div class="col-md-7">
                                <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Ingresa tu contraseña actual" required style="color: #0f172a !important;">
                                @error('old_password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right font-weight-bold small text-dark">Nueva Contraseña:</label>
                            <div class="col-md-7">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 8 caracteres" required style="color: #0f172a !important;">
                                @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right font-weight-bold small text-dark">Confirmar Nueva Contraseña:</label>
                            <div class="col-md-7">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repite la nueva contraseña" required style="color: #0f172a !important;">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-7 offset-md-4">
                                <button type="submit" class="btn btn-info font-weight-bold" style="background-color: #00acc1 !important; border-color: #00acc1 !important; text-transform: none;">
                                    <i class="fa fa-lock mr-1"></i> Actualizar Contraseña
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Vitrina de Insignias Estilo Stack Overflow --}}
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between flex-wrap border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark mb-2 mb-md-0">
                        <i class="fa fa-award text-warning mr-2"></i>Vitrina de Insignias (Medallas de Honor)
                    </h6>
                    <div class="d-flex flex-wrap justify-content-end" style="gap: .5rem;">
                        <span class="badge p-2 font-weight-bold" style="background:#fff8dc; color:#b8860b; border:1px solid #ffd700">
                            🥇 {{ $gamification['badges_summary']['oro'] }} Oro
                        </span>
                        <span class="badge p-2 font-weight-bold" style="background:#f5f5f5; color:#708090; border:1px solid #c0c0c0">
                            🥈 {{ $gamification['badges_summary']['plata'] }} Plata
                        </span>
                        <span class="badge p-2 font-weight-bold" style="background:#fff5ee; color:#a0522d; border:1px solid #cd7f32">
                            🥉 {{ $gamification['badges_summary']['bronce'] }} Bronce
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($gamification['badges_summary']['list'] as $b)
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded h-100 d-flex align-items-center {{ $b['unlocked'] ? 'bg-white shadow-sm' : 'bg-light opacity-60' }}"
                                 style="transition: all .2s; {{ !$b['unlocked'] ? 'filter: grayscale(80%); opacity: 0.6;' : '' }}">
                                <div class="rounded-circle p-3 mr-3 text-center d-flex align-items-center justify-content-center shadow-sm"
                                     style="width: 48px; height: 48px; background: {{ $b['color'] }}20; color: {{ $b['color'] }}; border: 2px solid {{ $b['color'] }}; flex-shrink: 0;">
                                    <i class="fa {{ $b['icon'] }} fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: .9rem">{{ $b['name'] }}</h6>
                                        <span class="badge badge-pill text-uppercase font-weight-bold"
                                              style="font-size:.6rem; background: {{ $b['color'] }}25; color: {{ $b['color'] }}">
                                            {{ $b['tier'] }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-1" style="font-size: .75rem; line-height: 1.2">{{ $b['description'] }}</p>
                                    @if($b['unlocked'])
                                    <small class="text-success font-weight-bold" style="font-size: .68rem">
                                        <i class="fa fa-check-circle mr-1"></i> Desbloqueada {{ $b['unlocked_at'] }}
                                    </small>
                                    @else
                                    <small class="text-muted" style="font-size: .68rem"><i class="fa fa-lock mr-1"></i> Bloqueada</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Historial Reciente de Puntos --}}
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-history text-info mr-2"></i>Historial Reciente de Actividad y Puntos
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($gamification['recent_history']->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: .85rem">
                            <thead class="bg-light">
                                <tr>
                                    <th style="color: #475569 !important; font-weight: 700;">Fecha</th>
                                    <th style="color: #475569 !important; font-weight: 700;">Acción realizada</th>
                                    <th style="color: #475569 !important; font-weight: 700;">Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gamification['recent_history'] as $item)
                                @php $validRef = $item->isReferenceValid(); @endphp
                                <tr class="{{ $validRef ? '' : 'bg-light' }}" style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="width: 140px; color: #64748b !important;">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <span class="font-weight-bold d-block" style="{{ $validRef ? 'color: #0f172a !important;' : 'color: #94a3b8 !important; text-decoration: line-through;' }}">{{ $item->description }}</span>
                                        <small class="text-uppercase font-weight-bold d-block mt-1" style="font-size: .68rem; {{ $validRef ? 'color: #475569 !important;' : 'color: #94a3b8 !important;' }}">
                                            {{ $item->getActionTypeLabel() }}
                                            @unless($validRef)
                                            <span class="badge badge-warning ml-1" style="font-size: .6rem">Referencia inválida</span>
                                            @endunless
                                        </small>
                                    </td>
                                    <td class="font-weight-bold" style="{{ $validRef ? 'color: #10b981 !important;' : 'color: #94a3b8 !important; text-decoration: line-through;' }}">
                                        +{{ $item->points }} pts
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4" style="color: #64748b !important;">
                        <i class="fa fa-ghost fa-2x mb-2" style="color: #94a3b8 !important;"></i>
                        <p class="mb-0 font-weight-bold" style="color: #64748b !important;">Aún no hay registro de puntos en este periodo.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ── Columna Derecha: Desglose y Leaderboard ── --}}
        <div class="col-lg-4 mb-4">

            {{-- Tarjeta Desglose de Fuentes de Puntos --}}
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-chart-pie text-info mr-2"></i>Fuentes de Puntos Obtenidos
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $actMap = [
                            'task_created'    => ['label' => 'Tareas Creadas',      'pts' => '+15 pts', 'icon' => 'fa-tasks',          'color' => 'primary'],
                            'task_completed'  => ['label' => 'Tareas Completadas',    'pts' => '+25 pts', 'icon' => 'fa-check-double',   'color' => 'success'],
                            'foda_analisis'   => ['label' => 'Análisis FODA',        'pts' => '+15 pts', 'icon' => 'fa-search',         'color' => 'warning'],
                            'foda_cruce'      => ['label' => 'Estrategias Cruce',    'pts' => '+30 pts', 'icon' => 'fa-chess',          'color' => 'info'],
                            'riiss_evaluacion'=> ['label' => 'Evaluaciones RIISS',   'pts' => '+50 pts', 'icon' => 'fa-hospital',       'color' => 'danger'],
                            'comment_created' => ['label' => 'Comentarios',          'pts' => '+5 pts',  'icon' => 'fa-comment',        'color' => 'secondary'],
                            'daily_login'     => ['label' => 'Accesos Diarios',      'pts' => '+5 pts',  'icon' => 'fa-key',            'color' => 'dark'],
                            'login_streak'    => ['label' => 'Racha de Accesos',     'pts' => '+20 pts', 'icon' => 'fa-fire',           'color' => 'warning'],
                            'riiss_asignacion'=> ['label' => 'Asignaciones RIISS',   'pts' => '+50 pts', 'icon' => 'fa-clipboard-list', 'color' => 'info'],
                        ];
                    @endphp

                    @foreach($actMap as $key => $meta)
                    @php
                        $entry = $gamification['by_action']->get($key);
                        $pts   = $entry ? $entry->total : 0;
                        $cnt   = $entry ? $entry->count : 0;
                    @endphp
                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded bg-light border-left border-{{ $meta['color'] }}">
                        <div class="d-flex align-items-center">
                            <i class="fa {{ $meta['icon'] }} text-{{ $meta['color'] }} mr-2" style="width: 16px"></i>
                            <div>
                                <span class="font-weight-bold text-dark d-block" style="font-size: .8rem">{{ $meta['label'] }}</span>
                                <small class="text-muted" style="font-size: .68rem; color: #64748b !important;">{{ $cnt }} acciones ({{ $meta['pts'] }})</small>
                            </div>
                        </div>
                        <span class="font-weight-bold text-dark" style="font-size: .85rem">+{{ $pts }} pts</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Leaderboard / Tabla de Posiciones --}}
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-trophy text-warning mr-2"></i>Tabla de Posiciones (Top 10)
                    </h6>
                    <small class="font-weight-bold" style="font-size:.68rem; color: #64748b !important;">Ranking</small>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($leaderboard as $index => $u)
                        @php
                            $isMe = $u->id === $targetUser->id;
                            $rankColor = match($index) {
                                0 => '#ffd700', // Oro
                                1 => '#c0c0c0', // Plata
                                2 => '#cd7f32', // Bronce
                                default => '#6b7280'
                            };
                        @endphp
                        <li class="list-group-item d-flex align-items-center justify-content-between py-2 px-3 {{ $isMe ? 'bg-light border-left border-info font-weight-bold' : '' }}">
                            <div class="d-flex align-items-center">
                                <span class="font-weight-bold mr-2 text-center" style="width: 22px; color: {{ $rankColor }}">
                                    #{{ $index + 1 }}
                                </span>
                                <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}"
                                     class="rounded-circle mr-2 border shadow-sm"
                                     style="width: 36px; height: 36px; object-fit: cover; flex-shrink: 0;">
                                <div>
                                    <a href="{{ route('user.profile', $u->id) }}" class="text-dark {{ $isMe ? 'font-weight-bold text-info' : '' }}" style="font-size: .82rem">
                                        {{ $u->name }}
                                    </a>
                                    @if($u->group)
                                    <small class="d-block text-muted" style="font-size: .68rem; color: #64748b !important;">{{ $u->group->name }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-pill badge-warning text-dark font-weight-bold" style="font-size: .78rem; white-space: nowrap;">
                                    ⭐ {{ number_format($u->total_points) }}
                                </span>
                                <button type="button" class="btn btn-circle btn-info ml-2 btn-show-user-points" data-user-id="{{ $u->id }}" data-user-name="{{ $u->name }}" title="Ver detalle de puntos" style="width: 34px; height: 34px; padding: 0; box-shadow: 0 2px 8px rgba(0, 172, 193, .24);">
                                    <i class="fa fa-search text-white" style="font-size: .88rem;"></i>
                                </button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- ── 5. Modal Limpio y Estilizado para Subir Foto de Perfil ── --}}
@if(Auth::id() === $targetUser->id)
<div class="modal fade" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="modalAvatarTitle" aria-hidden="true" style="z-index: 1050;">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px; overflow: hidden; background-color: #ffffff !important;">
            <form id="formAvatar" action="{{ route('user.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header py-3 text-white" style="background-color: #00acc1 !important;">
                    <h6 class="modal-title font-weight-bold mb-0 text-white" id="modalAvatarTitle">
                        <i class="fa fa-camera mr-2"></i> Cambiar Foto de Perfil
                    </h6>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4 bg-white">
                    <div class="mb-3">
                        <img id="avatar_preview" src="{{ $targetUser->avatar_url }}" class="rounded-circle border shadow-sm" style="width: 110px; height: 110px; object-fit: cover;">
                    </div>
                    <div class="form-group mb-2 text-left">
                        <input type="file" id="avatar_input" name="avatar" accept="image/*" required class="d-none">
                        <label for="avatar_input" class="btn btn-outline-info btn-block btn-sm font-weight-bold" style="text-transform: none; font-size: .95rem; border-color: #00acc1; color: #00acc1;">
                            <i class="fa fa-image mr-1"></i> Seleccionar foto...
                        </label>
                        <div id="avatar_input_name" class="text-muted small mt-2" style="font-size: .82rem;">Ningún archivo seleccionado</div>
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size: .72rem">Formatos permitidos: JPG, PNG, WEBP (máx. 3 MB)</small>
                </div>
                <div class="modal-footer py-2 bg-light d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold px-3" data-dismiss="modal" style="text-transform: none; border-radius: 4px;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm btn-info font-weight-bold px-3 text-white" id="btn_save_avatar" style="text-transform: none; border-radius: 4px; background-color: #00acc1 !important; border-color: #00acc1 !important;">
                        <i class="fa fa-upload mr-1"></i> Guardar Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal detalle de puntos por usuario --}}
<div class="modal fade" id="modalPointsDetail" tabindex="-1" role="dialog" aria-labelledby="modalPointsDetailTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-4 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <div class="w-100">
                    <h5 class="modal-title font-weight-bold d-flex align-items-center" id="modalPointsDetailTitle" style="font-size: 1.3rem;">
                        <i class="fa fa-star mr-2" style="font-size: 1.2rem;"></i>
                        Historial de Puntos
                    </h5>
                    <small class="text-white" style="opacity: 0.9; margin-top: 4px; display: block;">Seguimiento de tu actividad y progresión</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="p-4" style="background: linear-gradient(to bottom, #f8f9ff, #ffffff);">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Usuario</small>
                            <div id="modalPointsDetailUser" class="font-weight-bold text-dark" style="font-size: 1.1rem; margin-top: 4px;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Plan PEI</small>
                            <div id="modalPointsDetailPei" class="font-weight-bold text-dark" style="font-size: 0.95rem; margin-top: 4px; word-break: break-word; white-space: normal; overflow-wrap: anywhere;"><span class="badge badge-info">Global</span></div>
                        </div>
                    </div>
                </div>
                <div style="border-top: 1px solid rgba(0,0,0,0.08);">
                <div class="p-4">
                    <div id="modalPointsDetailLoading" class="text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-2x" style="color: #667eea;"></i>
                        <div class="mt-3 text-muted" style="font-size: 0.95rem;">Cargando historial de puntos...</div>
                    </div>
                    <div id="modalPointsDetailEmpty" class="text-center py-5 d-none">
                        <i class="fa fa-inbox fa-2x text-muted" style="opacity: 0.4; margin-bottom: 12px;"></i>
                        <div class="text-muted" style="font-size: 0.95rem;">No se encontraron aportes con puntuación para este usuario.</div>
                    </div>
                    <div id="modalPointsDetailTable" class="table-responsive d-none" style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead style="background: #f8f9ff; border-bottom: 2px solid #667eea;">
                                <tr>
                                    <th style="width: 18%; padding: 12px 8px; color: #667eea; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Fecha</th>
                                    <th style="width: 20%; padding: 12px 8px; color: #667eea; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Acción</th>
                                    <th style="padding: 12px 8px; color: #667eea; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Descripción</th>
                                    <th style="width: 12%; padding: 12px 8px; color: #667eea; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Puntos</th>
                                </tr>
                            </thead>
                            <tbody id="modalPointsDetailBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-3" style="background: #f8f9ff; border-top: 1px solid rgba(102, 126, 234, 0.1);">
                <button type="button" class="btn btn-sm" style="background: #667eea; color: white; border: none; border-radius: 6px;" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(function() {
    // Inicializar Select2 en el selector PEI
    if ($.fn.select2) {
        $('#select2_pei_id').select2({
            placeholder: '— Todo el Historial Global —',
            allowClear: true,
            width: '100%'
        }).on('change', function() {
            $(this).closest('form').submit();
        });
    }

    // Handler para Recalcular Puntos de Equipos (Administrador con SweetAlert2)
    $('#btnRecalcularGamificacion').on('click', function() {
        var btn = $(this);

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Recalcular Puntos e Insignias?',
                html: '<p class="text-muted small mb-0">Este proceso actualizará los puntos de todos los equipos de trabajo basándose en datos históricos y PEIs activos.</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#fb8c00',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-sync-alt mr-1"></i> Sí, recalcular',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarRecalculoGamificacion(btn);
                }
            });
        } else {
            if (confirm('¿Deseas recalcular retroactivamente todos los puntos e insignias de tus equipos de trabajo?')) {
                ejecutarRecalculoGamificacion(btn);
            }
        }
    });

    function ejecutarRecalculoGamificacion(btn) {
        var originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Recalculando...');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Recalculando puntos...',
                text: 'Por favor aguarda unos segundos mientras procesamos el historial.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading(); }
            });
        }

        $.ajax({
            url: '{{ route("gamification.recalculate") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.prop('disabled', false).html(originalHtml);
                if (res.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¡Recálculo Exitoso!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonColor: '#00acc1'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(res.message);
                        window.location.reload();
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Atención', res.message, 'warning');
                    } else {
                        alert('Atención: ' + res.message);
                    }
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalHtml);
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error en la solicitud.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', msg, 'error');
                } else {
                    alert('Error: ' + msg);
                }
            }
        });
    }

    // Vista previa de la foto seleccionada en el modal
    $('#avatar_input').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#avatar_preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
            $('#avatar_input_name').text(file.name);
        }
    });

    const selectedPeiId = @json($selectedPeiId);
    const peiNamesMap = {
        @foreach($peiPlanes as $p)
            '{{ $p->id }}': '{{ strip_tags($p->name) }}{{ $p->year_start ? " (" . \Carbon\Carbon::parse($p->year_start)->format('Y') . ")" : "" }}',
        @endforeach
    };
    const pointsDetailUrlTemplate = '{{ route('user.profile.points', ['id' => '__ID__']) }}';

    function resetPointsDetailModal() {
        $('#modalPointsDetailUser').text('');
        const peiLabel = selectedPeiId 
            ? (peiNamesMap[selectedPeiId] || selectedPeiId) 
            : 'Global';
        $('#modalPointsDetailPei').html(selectedPeiId 
            ? '<span class="badge badge-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); white-space: normal; word-break: break-word; display: inline-block; max-width: 100%;">' + peiLabel + '</span>'
            : '<span class="badge badge-info">Global</span>');
        $('#modalPointsDetailBody').empty();
        $('#modalPointsDetailLoading').removeClass('d-none');
        $('#modalPointsDetailEmpty').addClass('d-none');
        $('#modalPointsDetailTable').addClass('d-none');
    }

    function formatDateTime(value) {
        if (!value) {
            return '-';
        }
        const date = new Date(value);
        return date.toLocaleString('es-AR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function loadPointsDetails(userId, userName) {
        resetPointsDetailModal();
        $('#modalPointsDetailUser').text(userName);
        $('#modalPointsDetail').modal('show');

        const url = pointsDetailUrlTemplate.replace('__ID__', userId) + (selectedPeiId ? '?pei_id=' + encodeURIComponent(selectedPeiId) : '');

        $.getJSON(url)
            .done(function(response) {
                $('#modalPointsDetailLoading').addClass('d-none');

                // Actualizar nombre del PEI si viene en la respuesta
                if (response.pei_name) {
                    $('#modalPointsDetailPei').html('<span class="badge badge-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); white-space: normal; word-break: break-word; display: inline-block; max-width: 100%;">' + response.pei_name + '</span>');
                } else if (response.selected_pei_id) {
                    const peiLabel = peiNamesMap[response.selected_pei_id] || response.selected_pei_id;
                    $('#modalPointsDetailPei').html('<span class="badge badge-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); white-space: normal; word-break: break-word; display: inline-block; max-width: 100%;">' + peiLabel + '</span>');
                }

                if (!response.ok || !Array.isArray(response.points) || response.points.length === 0) {
                    $('#modalPointsDetailEmpty').removeClass('d-none');
                    return;
                }

                response.points.forEach(function(item) {
                    const isPositive = item.points > 0;
                    const isValid = item.reference_valid !== false;
                    const pointsColor = !isValid ? '#a0aec0' : (isPositive ? '#667eea' : '#f56565');
                    const pointsIcon = isPositive ? 'fa-arrow-up' : 'fa-arrow-down';
                    const rowStyle = isValid ? '' : 'opacity: 0.65; background: #fafafa;';
                    const descStyle = isValid ? '' : 'text-decoration: line-through;';
                    const invalidBadge = isValid ? '' : '<span class="badge badge-warning ml-1" style="font-size: 0.65rem;">Referencia inválida</span>';
                    const row = `<tr style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: background 0.2s; ${rowStyle}">
                        <td style="padding: 12px 8px; font-size: 0.85rem;">${formatDateTime(item.created_at)}</td>
                        <td style="padding: 12px 8px; font-size: 0.85rem;">
                            <span class="badge" style="background: rgba(102, 126, 234, 0.1); color: #667eea; font-weight: 600; font-size: 0.75rem;">${item.action_type_label}</span>
                            ${invalidBadge}
                        </td>
                        <td style="padding: 12px 8px; font-size: 0.85rem; ${descStyle}">${item.description || '-'}</td>
                        <td style="padding: 12px 8px; text-align: right; font-weight: 700; font-size: 0.95rem; color: ${pointsColor}; ${descStyle}">
                            <i class="fa ${pointsIcon} mr-1" style="font-size: 0.8rem; opacity: 0.7;"></i>${isPositive ? '+' : ''}${item.points}
                        </td>
                    </tr>`;
                    $('#modalPointsDetailBody').append(row);
                });

                $('#modalPointsDetailTable').removeClass('d-none');
            })
            .fail(function() {
                $('#modalPointsDetailLoading').addClass('d-none');
                $('#modalPointsDetailEmpty').removeClass('d-none').text('Error al cargar el detalle de puntos.');
            });
    }

    $(document).on('click', '.btn-show-user-points', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name') || 'Usuario';
        loadPointsDetails(userId, userName);
    });

    // Envío del formulario por AJAX
    $('#formAvatar').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var btn = $('#btn_save_avatar');

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fa fa-upload mr-1"></i> Guardar Foto');
                if (res.ok) {
                    $('#modalAvatar').modal('hide');
                    $('#user_avatar_img').attr('src', res.avatar_url);
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message);
                    }
                    setTimeout(function() { location.reload(); }, 600);
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-upload mr-1"></i> Guardar Foto');
                var msg = 'Error al subir la imagen.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }
        });
    });
});
</script>
@endsection
