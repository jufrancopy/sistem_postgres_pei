@php
    $isDone          = $task->status === 2;
    $cardColor       = $task->color ?? '#6b7280';
    $initials        = $task->assignedTo ? strtoupper(substr($task->assignedTo->name, 0, 2)) : '?';
    $modoColaborador = $modoColaborador ?? false;
    $esMia           = $modoColaborador ? ($task->assigned_to === ($userId ?? null)) : true;

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
@endphp

<div class="task-card {{ $isDone ? 'completed-card' : '' }} {{ $vencColor === '#ef4444' ? 'task-vencida' : '' }}"
     data-id="{{ $task->id }}"
     style="border-left-color:{{ $cardColor }};{{ $vencColor === '#ef4444' && !$isDone ? 'box-shadow:0 0 0 1px #ef444440;' : '' }}">

    @if($modoColaborador && $esMia)
    <span class="mine-badge">Mi tarea</span>
    @endif

    {{-- Cuerpo principal — drag handle --}}
    <div class="card-inner">

        @if($task->etiqueta)
        <div><span class="task-etiqueta" style="background:{{ $cardColor }}">{{ $task->etiqueta }}</span></div>
        @endif

        <div class="task-title {{ $isDone ? 'done-title' : '' }}">
            @if($isDone)<i class="fa fa-check-circle text-success mr-1 done-check"></i>@endif
            {{ $task->title }}
        </div>

        @if($task->details)
        <div class="task-desc">{{ Str::limit($task->details, 80) }}</div>
        @endif

        @if($venc)
        <div class="mb-1">
            <span style="font-size:.68rem;font-weight:600;padding:2px 8px;border-radius:20px;background:{{ $vencColor }}15;color:{{ $vencColor }};border:1px solid {{ $vencColor }}40">
                <i class="fa fa-clock mr-1"></i>{{ $venc }}
            </span>
        </div>
        @endif

        @if($isDone && $task->completion_note)
        <div class="mt-1 p-1 rounded small" style="background:#f0fff4;border-left:3px solid #10b981;font-size:.72rem">
            <i class="fa fa-comment-alt text-success mr-1"></i><em>{{ $task->completion_note }}</em>
            @if($task->completedBy)
            <br><small class="text-muted">{{ $task->completedBy->name }} · {{ \Carbon\Carbon::parse($task->completed_at)->format('d/m H:i') }}</small>
            @endif
        </div>
        @endif

        @if($task->evidences->count())
        <div class="mt-1 d-flex flex-wrap" style="gap:4px" onclick="event.stopPropagation()">
            @foreach($task->evidences as $ev)
            <span class="badge badge-light border small">
                @if($ev->type === 'url')
                    <i class="fa fa-link text-primary mr-1"></i>
                    <a href="{{ $ev->value }}" target="_blank" style="max-width:100px" class="text-truncate d-inline-block">{{ $ev->label }}</a>
                @elseif($ev->type === 'image')
                    <a href="{{ asset('storage/'.$ev->value) }}" target="_blank">
                        <img src="{{ asset('storage/'.$ev->value) }}" style="height:20px;width:20px;object-fit:cover;border-radius:3px">
                    </a>
                @else
                    <i class="fa fa-file-alt text-warning mr-1"></i>
                    <a href="{{ asset('storage/'.$ev->value) }}" target="_blank">{{ $ev->label }}</a>
                @endif
                <a href="javascript:void(0)" class="text-danger btn-delete-evidence ml-1" data-id="{{ $ev->id }}" onclick="event.stopPropagation()">×</a>
            </span>
            @endforeach
        </div>
        @endif

        {{-- Footer --}}
        <div class="task-footer mt-2" onclick="event.stopPropagation()">
            <div class="d-flex align-items-center" style="gap:6px">
                @if($task->assignedTo)
                <div class="task-avatar" style="background:{{ $cardColor }}20;color:{{ $cardColor }}">{{ $initials }}</div>
                <small class="text-muted" style="font-size:.72rem">{{ $task->assignedTo->name }}</small>
                @else
                <small class="text-muted" style="font-size:.72rem">Sin asignar</small>
                @endif

                {{-- Contador comentarios — clic para expandir --}}
                @if($comentariosCount > 0)
                <button class="btn btn-xs btn-outline-warning btn-toggle-comments"
                        data-task-id="{{ $task->id }}"
                        style="border-radius:20px;font-size:.68rem"
                        title="Ver comentarios">
                    <i class="fa fa-comment-alt mr-1"></i>{{ $comentariosCount }}
                </button>
                @else
                <button class="btn btn-xs btn-outline-secondary btn-toggle-comments"
                        data-task-id="{{ $task->id }}"
                        style="border-radius:20px;font-size:.68rem"
                        title="Comentar">
                    <i class="fa fa-comment-alt mr-1"></i>
                </button>
                @endif
            </div>
            <div class="task-actions">
                @if(!$modoColaborador || $esMia)
                @php
                    $secuencia = $isScrumActivity ?? false ? [0,1,3,2] : [0,4,1,3,2];
                    $posActual = array_search($status, $secuencia);
                    $esUltimo  = $posActual === count($secuencia) - 1;
                    $esPrimero = $posActual === 0;
                @endphp
                @if(!$esPrimero)
                <button class="btn btn-xs btn-outline-secondary btn-move-left" data-id="{{ $task->id }}" data-status="{{ $status }}" title="Retroceder">
                    <i class="fa fa-arrow-left"></i>
                </button>
                @endif
                @if(!$esUltimo)
                <button class="btn btn-xs btn-outline-primary btn-move-right" data-id="{{ $task->id }}" data-status="{{ $status }}" title="Avanzar">
                    <i class="fa fa-arrow-right"></i>
                </button>
                @endif
                @endif

                @if(!$modoColaborador)
                <button class="btn btn-xs btn-outline-secondary btn-add-evidence" data-id="{{ $task->id }}" title="Evidencia">
                    <i class="fa fa-paperclip"></i>
                </button>
                @if($task->assignedTo)
                <button class="btn btn-xs btn-outline-info btn-notificar-tarea" data-id="{{ $task->id }}" title="Notificar">
                    <i class="fa fa-envelope"></i>
                </button>
                @endif
                <button class="btn btn-xs btn-outline-danger btn-delete-task" data-id="{{ $task->id }}" title="Eliminar">
                    <i class="fa fa-trash"></i>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Sección expandible de comentarios --}}
    <div class="task-comments-section" id="comments-{{ $task->id }}" style="display:none;border-top:1px solid #f1f5f9">
        <div style="padding:10px 12px">
            {{-- Lista de comentarios existentes --}}
            <div class="comments-lista" id="comments-lista-{{ $task->id }}">
                @forelse(isset($task->comments) ? $task->comments : [] as $c)
                <div class="d-flex gap-2 mb-2" style="gap:8px" data-comment-id="{{ $c->id }}">
                    <div style="width:24px;height:24px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($c->user->name ?? '?', 0, 2)) }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="background:#f8fafc;border-radius:0 8px 8px 8px;padding:6px 10px;font-size:.78rem;color:#1e293b">
                            {{ $c->comentario }}
                        </div>
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:2px;display:flex;justify-content:space-between">
                            <span>{{ $c->user->name ?? '' }} · {{ $c->created_at->format('d/m H:i') }}</span>
                            @if($c->user_id === auth()->id())
                            <a href="javascript:void(0)" class="text-danger btn-delete-comment" data-id="{{ $c->id }}" style="font-size:.65rem">×</a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted small py-1" id="no-comments-{{ $task->id }}">Sin comentarios aún</div>
                @endforelse
            </div>

            {{-- Input nuevo comentario --}}
            <div class="d-flex mt-2" style="gap:6px" onclick="event.stopPropagation()">
                <input type="text" class="form-control form-control-sm comment-input"
                       id="comment-input-{{ $task->id }}"
                       placeholder="Escribí un comentario..."
                       data-task-id="{{ $task->id }}"
                       style="border-radius:20px;font-size:.78rem">
                <button class="btn btn-primary btn-sm comment-send"
                        data-task-id="{{ $task->id }}"
                        style="border-radius:50%;width:30px;height:30px;padding:0;flex-shrink:0">
                    <i class="fa fa-paper-plane" style="font-size:.7rem"></i>
                </button>
            </div>
        </div>
    </div>

</div>
