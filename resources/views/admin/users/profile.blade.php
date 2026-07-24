@extends('layouts.master')

@section('title', 'Perfil de Usuario — Gamificación')

@section('content')
<div class="container-fluid py-3">

    {{-- ── Encabezado del Perfil y Banner de Gamificación ── --}}
    <div class="card border-0 shadow-sm mb-4 text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 12px; overflow: hidden;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-7 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        {{-- Avatar con botón overlay para cambiar la foto --}}
                        <div class="position-relative mr-3" style="width: 80px; height: 80px;">
                            <img id="user_avatar_img" src="{{ $targetUser->avatar_url }}" alt="{{ $targetUser->name }}"
                                 class="rounded-circle shadow-lg border"
                                 style="width: 80px; height: 80px; border: 3px solid rgba(255,255,255,0.3) !important; object-fit: cover;">
                            
                            @if(Auth::id() === $targetUser->id)
                            <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute bottom-0 right-0 p-0 shadow"
                                    style="width: 28px; height: 28px; font-size: 12px; line-height: 28px; text-align: center; border: 1px solid #ccc;"
                                    data-toggle="modal" data-target="#modalAvatar" title="Cambiar foto de perfil">
                                <i class="fa fa-camera text-primary"></i>
                            </button>
                            @endif
                        </div>
                        <div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: .5rem">
                                <h4 class="font-weight-bold mb-0 text-white">{{ $targetUser->name }}</h4>
                                <span class="badge badge-pill badge-primary py-1 px-2 font-weight-normal" style="font-size: .75rem">
                                    {{ $targetUser->roles->pluck('name')->implode(', ') ?: 'Usuario' }}
                                </span>
                            </div>
                            <div class="text-muted small mt-1">
                                <i class="fa fa-envelope mr-1"></i> {{ $targetUser->email }}
                                @if($targetUser->group)
                                <span class="ml-2"><i class="fa fa-sitemap mr-1"></i> {{ $targetUser->group->name }}</span>
                                @endif
                            </div>
                            @if(Auth::id() === $targetUser->id)
                            <button type="button" class="btn btn-xs btn-outline-light mt-2 py-0 px-2" style="font-size:.72rem" data-toggle="modal" data-target="#modalAvatar">
                                <i class="fa fa-upload mr-1"></i> Subir foto de perfil
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Resumen de Nivel y Puntos --}}
                <div class="col-md-5 text-md-right border-left border-secondary pl-md-4">
                    <div class="d-inline-block text-center p-2 rounded mr-3" style="background: rgba(255,255,255,0.05); min-width: 110px;">
                        <small class="text-muted text-uppercase d-block font-weight-bold" style="font-size: .65rem">Reputación</small>
                        <span class="h3 font-weight-bold text-warning mb-0">⭐ {{ number_format($gamification['total_points']) }}</span>
                        <small class="d-block text-muted" style="font-size: .65rem">puntos acumulados</small>
                    </div>
                    <div class="d-inline-block text-center p-2 rounded" style="background: rgba(255,255,255,0.05); min-width: 130px;">
                        <small class="text-muted text-uppercase d-block font-weight-bold" style="font-size: .65rem">Rango Actual</small>
                        <span class="h5 font-weight-bold text-white mb-0">
                            <i class="fa {{ $gamification['level_icon'] }} text-info mr-1"></i> {{ $gamification['level_name'] }}
                        </span>
                        <small class="d-block text-info font-weight-bold" style="font-size: .65rem">{{ $gamification['level_badge'] }}</small>
                    </div>
                </div>
            </div>

            {{-- Barra de Progreso de Nivel --}}
            <div class="mt-4 pt-3 border-top border-secondary">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted">
                        <strong class="text-white">Nivel {{ $gamification['level_number'] }}:</strong> {{ $gamification['level_name'] }}
                    </small>
                    <small class="text-muted">
                        Próximo Nivel: <strong class="text-info">{{ $gamification['next_level_name'] }}</strong> ({{ $gamification['progress_pct'] }}%)
                    </small>
                </div>
                <div class="progress bg-secondary" style="height: 8px; border-radius: 4px;">
                    <div class="progress-bar bg-gradient-info progress-bar-striped progress-bar-animated" role="progressbar"
                         style="width: {{ $gamification['progress_pct'] }}%;"
                         aria-valuenow="{{ $gamification['progress_pct'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filtro por Plan PEI Activo ── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2 px-3 bg-white d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center mr-3 my-1">
                <i class="fa fa-filter text-primary mr-2"></i>
                <span class="font-weight-bold text-dark small">Contexto de Gamificación:</span>
                <span class="badge badge-light border ml-2 text-dark font-weight-normal">
                    {{ $peiSeleccionado ? strip_tags($peiSeleccionado->name) : 'Consolidado Global (Todos los Planes PEI)' }}
                </span>
            </div>
            <form method="GET" action="{{ route('user.profile', $targetUser->id) }}" class="form-inline my-1">
                <select name="pei_id" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <option value="">— Todo el Historial Global —</option>
                    @foreach($peiPlanes as $p)
                    <option value="{{ $p->id }}" {{ request('pei_id') == $p->id || ($peiSeleccionado && $peiSeleccionado->id == $p->id && !request()->has('pei_id')) ? 'selected' : '' }}>
                        PEI: {{ strip_tags($p->name) }} @if($p->year_start)({{ \Carbon\Carbon::parse($p->year_start)->format('Y') }})@endif
                    </option>
                    @endforeach
                </select>
                @if(request('pei_id'))
                <a href="{{ route('user.profile', $targetUser->id) }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                @endif
            </form>
        </div>
    </div>

    <div class="row">

        {{-- ── Columna Izquierda: Vitrina de Insignias y Desglose ── --}}
        <div class="col-lg-8 mb-4">

            {{-- Resumen de Insignias Estilo Stack Overflow --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
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
                                     style="width: 48px; height: 48px; background: {{ $b['color'] }}20; color: {{ $b['color'] }}; border: 2px solid {{ $b['color'] }};">
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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-history text-primary mr-2"></i>Historial Reciente de Actividad y Puntos
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
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
                        <li class="list-group-item d-flex align-items-center justify-content-between py-2 px-3 {{ $isMe ? 'bg-primary-50 border-left border-primary font-weight-bold' : '' }}">
                            <div class="d-flex align-items-center">
                                <span class="font-weight-bold mr-2 text-center" style="width: 22px; color: {{ $rankColor }}">
                                    #{{ $index + 1 }}
                                </span>
                                <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}"
                                     class="rounded-circle mr-2 border shadow-sm"
                                     style="width: 36px; height: 36px; object-fit: cover;">
                                <div>
                                    <a href="{{ route('user.profile', $u->id) }}" class="text-dark {{ $isMe ? 'font-weight-bold text-primary' : '' }}" style="font-size: .82rem">
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

{{-- ── Modal Subir Foto de Perfil ── --}}
@if(Auth::id() === $targetUser->id)
<div class="modal fade" id="modalAvatar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow">
            <form id="formAvatar" action="{{ route('user.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header py-2 bg-primary text-white">
                    <h6 class="modal-title font-weight-bold mb-0"><i class="fa fa-camera mr-1"></i> Cambiar Foto de Perfil</h6>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <img id="avatar_preview" src="{{ $targetUser->avatar_url }}" class="rounded-circle border shadow-sm" style="width: 110px; height: 110px; object-fit: cover;">
                    </div>
                    <div class="custom-file mb-2 text-left">
                        <input type="file" class="custom-file-input" id="avatar_input" name="avatar" accept="image/*" required>
                        <label class="custom-file-label" for="avatar_input">Elegir foto...</label>
                    </div>
                    <small class="text-muted d-block" style="font-size: .75rem">Formatos permitidos: JPG, PNG, WEBP (máx. 3 MB)</small>
                </div>
                <div class="modal-footer py-2 bg-light justify-content-between">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="btn_save_avatar">
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
    // Vista previa de la foto seleccionada
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
