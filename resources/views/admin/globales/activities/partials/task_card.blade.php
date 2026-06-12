@php
    $isDone    = $task->status === 2;
    $cardColor = $task->color ?? '#6b7280';
    $initials  = $task->assignedTo ? strtoupper(substr($task->assignedTo->name, 0, 2)) : '?';

    // Vencimiento
    $venc = null; $vencColor = null; $vencLabel = null;
    if ($task->fecha_vencimiento && !$isDone) {
        $dias = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($task->fecha_vencimiento)->startOfDay(), false);
        if ($dias < 0)      { $vencColor = '#ef4444'; $vencLabel = 'Vencida hace ' . abs($dias) . 'd'; }
        elseif ($dias === 0){ $vencColor = '#ef4444'; $vencLabel = '¡Vence hoy!'; }
        elseif ($dias <= 3) { $vencColor = '#f97316'; $vencLabel = 'Vence en ' . $dias . 'd'; }
        else                { $vencColor = '#22c55e'; $vencLabel = \Carbon\Carbon::parse($task->fecha_vencimiento)->format('d/m/Y'); }
        $venc = $vencLabel;
    }
@endphp

<div class="task-card {{ $isDone ? 'completed-card' : '' }} {{ ($vencColor === '#ef4444') ? 'task-vencida' : '' }}"
     data-id="{{ $task->id }}"
     style="border-left-color: {{ $cardColor }};{{ ($vencColor === '#ef4444' && !$isDone) ? 'box-shadow:0 0 0 1px #ef444440;' : '' }}">
    <div class="card-inner">

        {{-- Etiqueta --}}
        @if($task->etiqueta)
        <div>
            <span class="task-etiqueta" style="background:{{ $cardColor }}">
                {{ $task->etiqueta }}
            </span>
        </div>
        @endif

        {{-- Título --}}
        <div class="task-title {{ $isDone ? 'done-title' : '' }}">
            @if($isDone)
            <i class="fa fa-check-circle text-success mr-1 done-check"></i>
            @endif
            {{ $task->title }}
        </div>

        {{-- Descripción --}}
        @if($task->details)
        <div class="task-desc">{{ Str::limit($task->details, 80) }}</div>
        @endif

        {{-- Fecha de vencimiento --}}
        @if($venc)
        <div class="mb-1">
            <span style="font-size:.68rem;font-weight:600;padding:2px 8px;border-radius:20px;background:{{ $vencColor }}15;color:{{ $vencColor }};border:1px solid {{ $vencColor }}40">
                <i class="fa fa-clock mr-1"></i>{{ $venc }}
            </span>
        </div>
        @endif

        {{-- Nota de cierre --}}
        @if($isDone && $task->completion_note)
        <div class="mt-1 p-1 rounded small" style="background:#f0fff4;border-left:3px solid #10b981;font-size:.72rem">
            <i class="fa fa-comment-alt text-success mr-1"></i>
            <em>{{ $task->completion_note }}</em>
            @if($task->completedBy)
            <br><small class="text-muted">{{ $task->completedBy->name }} · {{ \Carbon\Carbon::parse($task->completed_at)->format('d/m H:i') }}</small>
            @endif
        </div>
        @endif

        {{-- Evidencias --}}
        @if($task->evidences->count())
        <div class="mt-1 d-flex flex-wrap" style="gap:4px">
            @foreach($task->evidences as $ev)
            <span class="badge badge-light border small">
                @if($ev->type === 'url')
                    <i class="fa fa-link text-primary mr-1"></i>
                    <a href="{{ $ev->value }}" target="_blank" class="text-truncate" style="max-width:100px">{{ $ev->label }}</a>
                @elseif($ev->type === 'image')
                    <a href="{{ asset('storage/'.$ev->value) }}" target="_blank">
                        <img src="{{ asset('storage/'.$ev->value) }}" style="height:20px;width:20px;object-fit:cover;border-radius:3px">
                    </a>
                @else
                    <i class="fa fa-file-alt text-warning mr-1"></i>
                    <a href="{{ asset('storage/'.$ev->value) }}" target="_blank">{{ $ev->label }}</a>
                @endif
                <a href="javascript:void(0)" class="text-danger btn-delete-evidence ml-1" data-id="{{ $ev->id }}">×</a>
            </span>
            @endforeach
        </div>
        @endif

        {{-- Footer: avatar + acciones --}}
        <div class="task-footer mt-2">
            <div class="d-flex align-items-center" style="gap:6px">
                @if($task->assignedTo)
                <div class="task-avatar" style="background:{{ $cardColor }}20;color:{{ $cardColor }}">
                    {{ $initials }}
                </div>
                <small class="text-muted" style="font-size:.72rem">{{ $task->assignedTo->name }}</small>
                @else
                <small class="text-muted" style="font-size:.72rem">Sin asignar</small>
                @endif
            </div>
            <div class="task-actions">
                @php
                    // Secuencia de estados según tipo de actividad
                    $secuencia = $isScrumActivity ?? false
                        ? [0, 1, 3, 2]
                        : [0, 4, 1, 3, 2];
                    $posActual  = array_search($status, $secuencia);
                    $esUltimo   = $posActual === count($secuencia) - 1;
                    $esPrimero  = $posActual === 0;
                @endphp
                @if(!$esPrimero)
                <button class="btn btn-xs btn-outline-secondary btn-move-left"
                        data-id="{{ $task->id }}" data-status="{{ $status }}" title="Retroceder">
                    <i class="fa fa-arrow-left"></i>
                </button>
                @endif
                @if(!$esUltimo)
                <button class="btn btn-xs btn-outline-primary btn-move-right"
                        data-id="{{ $task->id }}" data-status="{{ $status }}" title="Avanzar">
                    <i class="fa fa-arrow-right"></i>
                </button>
                @endif
                {{-- Evidencia --}}
                <button class="btn btn-xs btn-outline-secondary btn-add-evidence"
                        data-id="{{ $task->id }}" title="Agregar evidencia">
                    <i class="fa fa-paperclip"></i>
                </button>
                {{-- Notificar tarea --}}
                @if($task->assignedTo)
                <button class="btn btn-xs btn-outline-info btn-notificar-tarea"
                        data-id="{{ $task->id }}" title="Notificar a {{ $task->assignedTo->name }}">
                    <i class="fa fa-envelope"></i>
                </button>
                @endif
                {{-- Eliminar --}}
                <button class="btn btn-xs btn-outline-danger btn-delete-task"
                        data-id="{{ $task->id }}" title="Eliminar">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>
