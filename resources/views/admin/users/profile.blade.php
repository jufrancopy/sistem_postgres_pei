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
                    <div class="d-flex align-items-center">
                        {{-- Avatar con botón de cámara flotante --}}
                        <div class="position-relative mr-3" style="width: 84px; height: 84px; flex-shrink: 0;">
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
                        <div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: .5rem">
                                <h4 class="font-weight-bold mb-0" style="color: #0f172a !important; font-size: 1.4rem;">{{ $targetUser->name }}</h4>
                                <span class="badge badge-pill badge-info py-1 px-2 font-weight-bold text-white" style="font-size: .75rem; background-color: #00acc1 !important;">
                                    {{ $targetUser->roles->pluck('name')->implode(', ') ?: 'Usuario' }}
                                </span>
                            </div>
                            <div class="mt-1" style="color: #475569 !important; font-size: .9rem;">
                                <span class="mr-3"><i class="fa fa-envelope text-info mr-1"></i> {{ $targetUser->email }}</span>
                                @if($targetUser->group)
                                <span><i class="fa fa-sitemap text-info mr-1"></i> {{ $targetUser->group->name }}</span>
                                @endif
                            </div>
                            @if(Auth::id() === $targetUser->id)
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-info py-1 px-3 font-weight-bold" style="font-size:.78rem; text-transform: none; border-color: #00acc1; color: #00acc1;" data-toggle="modal" data-target="#modalAvatar">
                                    <i class="fa fa-upload mr-1"></i> Cambiar Foto de Perfil
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Resumen de Nivel y Puntos --}}
                <div class="col-md-5 text-md-right border-left pl-md-4" style="border-color: #e2e8f0 !important;">
                    <div class="d-inline-block text-center p-2 rounded mr-3" style="background: #fff8e1; border: 1px solid #ffe082; min-width: 110px;">
                        <small class="text-uppercase d-block font-weight-bold" style="font-size: .65rem; color: #b8860b;">Reputación</small>
                        <span class="h3 font-weight-bold text-dark mb-0">⭐ {{ number_format($gamification['total_points']) }}</span>
                        <small class="d-block text-muted" style="font-size: .65rem">puntos acumulados</small>
                    </div>
                    <div class="d-inline-block text-center p-2 rounded" style="background: #e0f7fa; border: 1px solid #b2ebf2; min-width: 130px;">
                        <small class="text-uppercase d-block font-weight-bold" style="font-size: .65rem; color: #00838f;">Rango Actual</small>
                        <span class="h5 font-weight-bold text-dark mb-0">
                            <i class="fa {{ $gamification['level_icon'] }} text-info mr-1"></i> {{ $gamification['level_name'] }}
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
            <form method="GET" action="{{ route('user.profile', $targetUser->id) }}" class="form-inline my-1">
                <select name="pei_id" id="select2_pei_id" class="form-control form-control-sm select2 mr-2" style="width: 280px;">
                    <option value="">— Todo el Historial Global —</option>
                    @foreach($peiPlanes as $p)
                    <option value="{{ $p->id }}" {{ request('pei_id') == $p->id || ($peiSeleccionado && $peiSeleccionado->id == $p->id && !request()->has('pei_id')) ? 'selected' : '' }}>
                        PEI: {{ strip_tags($p->name) }} @if($p->year_start)({{ \Carbon\Carbon::parse($p->year_start)->format('Y') }})@endif
                    </option>
                    @endforeach
                </select>
                @if(request('pei_id'))
                <a href="{{ route('user.profile', $targetUser->id) }}" class="btn btn-sm btn-outline-secondary ml-2">Limpiar</a>
                @endif
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
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-award text-warning mr-2"></i>Vitrina de Insignias (Medallas de Honor)
                    </h6>
                    <div class="d-flex" style="gap: .8rem">
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
            <div class="card border-0 shadow-sm bg-white">
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
                                    <th>Fecha</th>
                                    <th>Acción realizada</th>
                                    <th>Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gamification['recent_history'] as $item)
                                <tr>
                                    <td class="text-muted" style="width: 140px">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-dark d-block">{{ $item->description }}</span>
                                        <small class="text-muted text-uppercase" style="font-size: .68rem">{{ str_replace('_',' ', $item->action_type) }}</small>
                                    </td>
                                    <td class="text-success font-weight-bold">
                                        +{{ $item->points }} pts
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fa fa-ghost fa-2x mb-2"></i>
                        <p class="mb-0">Aún no hay registro de puntos en este periodo.</p>
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
                                <small class="text-muted" style="font-size: .68rem">{{ $cnt }} acciones ({{ $meta['pts'] }})</small>
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
                    <small class="text-muted font-weight-bold" style="font-size:.68rem">Ranking</small>
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
                                    <small class="d-block text-muted" style="font-size: .68rem">{{ $u->group->name }}</small>
                                    @endif
                                </div>
                            </div>
                            <span class="badge badge-pill badge-warning text-dark font-weight-bold" style="font-size: .78rem">
                                ⭐ {{ number_format($u->total_points) }}
                            </span>
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
                    <div class="custom-file mb-2 text-left">
                        <input type="file" class="custom-file-input" id="avatar_input" name="avatar" accept="image/*" required>
                        <label class="custom-file-label" for="avatar_input" style="font-size: .85rem;">Seleccionar foto...</label>
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
@endsection

@section('scripts')
<script>
$(function() {
    // Inicializar Select2 en el selector PEI
    if ($.fn.select2) {
        $('#select2_pei_id').select2({
            placeholder: '— Todo el Historial Global —',
            allowClear: true,
            width: '280px'
        }).on('change', function() {
            $(this).closest('form').submit();
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
            $(this).next('.custom-file-label').html(file.name);
        }
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
