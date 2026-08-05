@php
    $isDone          = $task->status === 2;
    $cardColor       = $task->color ?? '#6b7280';
    $initials        = $task->assignedTo ? strtoupper(substr($task->assignedTo->name, 0, 2)) : '?';
    $modoColaborador = $modoColaborador ?? false;
    $esMia           = $modoColaborador ? ($task->assigned_to === ($userId ?? null)) : true;
    $esGestor        = auth()->user()->hasAnyRole(['Administrador', 'Gestor de Actividades', 'Analista de Planificación', 'Analista PEI', 'Analista']);
    $puedeGestionar  = $esGestor;
    $puedeMover      = $esGestor || $esMia;

    // Vencimiento
    $venc = null; $vencColor = null;
    if ($task->fecha_vencimiento && !$isDone) {
        $dias = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($task->fecha_vencimiento)->startOfDay(), false);
        if ($dias < 0)      { $vencColor = '#ef4444'; $venc = 'Vencida hace ' . abs($dias) . 'd'; }
        elseif ($dias === 0){ $vencColor = '#ef4444'; $venc = '¡Vence hoy!'; }
        elseif ($dias <= 3) { $vencColor = '#f97316'; $venc = 'Vence en ' . $dias . 'd'; }
        else                { $vencColor = '#22c55e'; $venc = \Carbon\Carbon::parse($task->fecha_vencimiento)->format('d/m/Y'); }
    }

    $comentariosCount = isset($task->comments) ? $task->comments->count() : 0;
    $evidenciasCount  = isset($task->evidences) ? $task->evidences->count() : 0;

    $secuencia = ($isScrumActivity ?? false) ? [0,1,3,2] : [0,4,1,3,2];
    $posActual = array_search($status, $secuencia);
    $esUltimo  = $posActual === count($secuencia) - 1;
    $esPrimero = $posActual === 0;

    $statusLabel = match((int)$task->status) {
        0 => ['label' => 'Pendiente',   'bg' => '#f59e0b'],
        1 => ['label' => 'En progreso', 'bg' => '#3b82f6'],
        3 => ['label' => 'En revisión', 'bg' => '#8b5cf6'],
        2 => ['label' => 'Finalizado',  'bg' => '#10b981'],
        4 => ['label' => 'Priorizado',  'bg' => '#f97316'],
        default => ['label' => '—', 'bg' => '#6b7280'],
    };
@endphp

{{-- ── CARD ── --}}
<div id="task-{{ $task->id }}" class="task-card {{ $isDone ? 'completed-card' : '' }} {{ $vencColor === '#ef4444' ? 'task-vencida' : '' }}"
     data-id="{{ $task->id }}"
     data-assigned-to="{{ $task->assigned_to }}"
     data-fecha-inicio="{{ $task->fecha_inicio?->format('Y-m-d') }}"
     style="border-left-color:{{ $cardColor }};{{ $vencColor === '#ef4444' && !$isDone ? 'box-shadow:0 0 0 2px #ef444430;' : '' }}">

    @if($modoColaborador && $esMia)
    <span class="mine-badge">Mi tarea</span>
    @endif

    {{-- Cuerpo clickeable → abre modal detalle --}}
    <div class="card-inner drag-handle" style="cursor:pointer"
         onclick="abrirDetalleTask({{ $task->id }})">

        @if($task->etiqueta)
        <div class="mb-1">
            <span class="task-etiqueta" style="background:{{ $cardColor }}">{{ $task->etiqueta }}</span>
        </div>
        @endif

        <div class="task-title {{ $isDone ? 'done-title' : '' }}">
            @if($isDone)<i class="fa fa-check-circle text-success mr-1"></i>@endif
            {{ $task->title }}
        </div>

        @if($task->details)
        <div class="task-desc">{{ Str::limit($task->details, 70) }}</div>
        @endif

        @if($venc)
        <div class="mb-1">
            <span style="font-size:.65rem;font-weight:600;padding:2px 7px;border-radius:20px;background:{{ $vencColor }}15;color:{{ $vencColor }};border:1px solid {{ $vencColor }}30">
                <i class="fa fa-clock mr-1"></i>{{ $venc }}
            </span>
        </div>
        @endif

        @if($isDone && $task->completion_note)
        <div class="mt-1 p-1 rounded" style="background:#f0fff4;border-left:3px solid #10b981;font-size:.7rem">
            <i class="fa fa-comment-alt text-success mr-1"></i><em>{{ Str::limit($task->completion_note, 60) }}</em>
        </div>
        @endif

        {{-- Footer info --}}
        <div class="d-flex align-items-center justify-content-between mt-2" style="gap:6px">
            <div class="d-flex align-items-center" style="gap:5px">
                @if($task->assignedTo)
                <div class="task-avatar" style="background:{{ $cardColor }}20;color:{{ $cardColor }}">{{ $initials }}</div>
                <small class="text-muted" style="font-size:.68rem;max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $task->assignedTo->name }}</small>
                @else
                <small class="text-muted" style="font-size:.68rem">Sin asignar</small>
                @endif
            </div>
            <div class="d-flex align-items-center" style="gap:4px">
                @if($comentariosCount > 0)
                <span style="font-size:.65rem;color:#94a3b8"><i class="fa fa-comment-alt mr-1"></i>{{ $comentariosCount }}</span>
                @endif
                @if($evidenciasCount > 0)
                <span style="font-size:.65rem;color:#94a3b8"><i class="fa fa-paperclip mr-1"></i>{{ $evidenciasCount }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Barra de acciones ── --}}
    <div class="task-action-bar">

        {{-- Consultar en Chat --}}
        <button type="button" class="tac-btn tac-blue"
                onclick="event.stopPropagation(); openChatWithContext('ActivityTask', '{{ $task->id }}', 'Tarea: {{ e($task->title) }}', '{{ url()->current() }}#task-{{ $task->id }}')"
                title="Consultar sobre esta tarea en el Chat">
            <i class="fa fa-comment-dots"></i>
            <span>Consultar</span>
        </button>

        {{-- Mover --}}
        @if($puedeMover)
            @if(!$esPrimero)
            <button class="tac-btn tac-gray btn-move-left" data-id="{{ $task->id }}" data-status="{{ $status }}" title="Retroceder">
                <i class="fa fa-arrow-left"></i>
            </button>
            @endif
            @if(!$esUltimo)
            <button class="tac-btn tac-blue btn-move-right" data-id="{{ $task->id }}" data-status="{{ $status }}" title="Avanzar">
                <i class="fa fa-arrow-right"></i>
            </button>
            @endif
        @endif

        {{-- Comentarios --}}
        <button class="tac-btn {{ $comentariosCount > 0 ? 'tac-amber' : 'tac-gray' }} btn-toggle-comments"
                data-task-id="{{ $task->id }}"
                title="Comentarios{{ $comentariosCount > 0 ? ' ('.$comentariosCount.')' : '' }}">
            <i class="fa fa-comment-alt"></i>
            @if($comentariosCount > 0)<span style="font-size:.6rem">{{ $comentariosCount }}</span>@endif
        </button>

        @if($puedeGestionar)
        {{-- Evidencia --}}
        <button class="tac-btn tac-gray btn-add-evidence" data-id="{{ $task->id }}" title="Evidencia">
            <i class="fa fa-paperclip"></i>
        </button>
        {{-- Notificar --}}
        @if($task->assignedTo)
        <button class="tac-btn tac-teal btn-notificar-tarea" data-id="{{ $task->id }}" title="Notificar">
            <i class="fa fa-paper-plane"></i>
        </button>
        @endif
        {{-- Editar --}}
        <button class="tac-btn tac-violet editTaskBtn" data-id="{{ $task->id }}" title="Editar">
            <i class="fa fa-pen"></i>
        </button>
        {{-- Eliminar --}}
        <button class="tac-btn tac-red btn-delete-task" data-id="{{ $task->id }}" title="Eliminar">
            <i class="fa fa-trash"></i>
        </button>
        @endif
    </div>

    {{-- Comentarios expandibles --}}
    <div class="task-comments-section" id="comments-{{ $task->id }}"
         style="display:none;border-top:1px solid #f1f5f9" onclick="event.stopPropagation()">
        <div style="padding:10px 12px">
            <div class="comments-lista" id="comments-lista-{{ $task->id }}" style="max-height:160px;overflow-y:auto">
                @forelse(isset($task->comments) ? $task->comments : [] as $c)
                <div class="d-flex mb-2" style="gap:8px" data-comment-id="{{ $c->id }}">
                    <div style="width:22px;height:22px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($c->user->name ?? '?', 0, 2)) }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="background:#f8fafc;border-radius:0 8px 8px 8px;padding:5px 9px;font-size:.76rem;color:#1e293b">{{ $c->comentario }}</div>
                        <div style="font-size:.63rem;color:#94a3b8;margin-top:2px;display:flex;justify-content:space-between">
                            <span>{{ $c->user->name ?? '' }} · {{ $c->created_at->format('d/m H:i') }}</span>
                            @if($c->user_id === auth()->id())
                            <a href="javascript:void(0)" class="text-danger btn-delete-comment" data-id="{{ $c->id }}" style="font-size:.63rem">×</a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted small py-1" id="no-comments-{{ $task->id }}">Sin comentarios aún</div>
                @endforelse
            </div>
            <div class="d-flex mt-2" style="gap:5px">
                <input type="text" class="form-control form-control-sm comment-input"
                       id="comment-input-{{ $task->id }}"
                       data-task-id="{{ $task->id }}"
                       placeholder="Escribí un comentario..."
                       style="border-radius:20px;font-size:.76rem">
                <button class="btn btn-primary btn-sm comment-send" data-task-id="{{ $task->id }}"
                        style="border-radius:50%;width:28px;height:28px;padding:0;flex-shrink:0">
                    <i class="fa fa-paper-plane" style="font-size:.65rem"></i>
                </button>
            </div>
        </div>
    </div>

</div>

<style>
/* ── Action bar ── */
.task-action-bar {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    border-radius: 0 0 10px 10px;
    flex-wrap: wrap;
}
.tac-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 3px;
    padding: 4px 9px; border-radius: 6px; font-size: .68rem; font-weight: 600;
    border: none; cursor: pointer; transition: filter .15s, transform .1s;
    line-height: 1;
}
.tac-btn:hover { filter: brightness(.9); transform: translateY(-1px); }
.tac-btn:active { transform: translateY(0); }
.tac-gray   { background: #f1f5f9; color: #475569; }
.tac-blue   { background: #dbeafe; color: #1e40af; }
.tac-amber  { background: #fef3c7; color: #92400e; }
.tac-red    { background: #fee2e2; color: #991b1b; }
.tac-violet { background: #ede9fe; color: #5b21b6; }
.tac-teal   { background: #ccfbf1; color: #065f46; }
</style>
