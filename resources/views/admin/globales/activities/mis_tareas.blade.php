@extends('layouts.master')
@section('title', 'Mis Tareas — ' . $activity->name)

@push('styles')
<style>
.hero-mis-tareas {
    background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
    border-radius: 0 0 16px 16px;
    padding: 24px 28px 20px;
    color: #fff;
    position: relative; overflow: hidden;
}
.hero-mis-tareas::before {
    content:''; position:absolute; inset:0;
    background:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M20 20c0-5.523-4.477-10-10-10S0 14.477 0 20s4.477 10 10 10 10-4.477 10-10zm10 0c0 5.523 4.477 10 10 10s10-4.477 10-10-4.477-10-10-10-10 4.477-10 10z'/%3E%3C/g%3E%3C/svg%3E");
}
.personal-stats { display:flex; gap:16px; flex-wrap:wrap; margin-top:16px; }
.stat-chip {
    background:rgba(255,255,255,.15); border-radius:12px; padding:10px 16px;
    text-align:center; min-width:80px;
}
.stat-chip .num  { font-size:1.5rem; font-weight:800; line-height:1; }
.stat-chip .lbl  { font-size:.68rem; opacity:.8; text-transform:uppercase; margin-top:2px; }

.progress-track { height:8px; border-radius:4px; background:rgba(255,255,255,.2); overflow:hidden; margin-top:12px; }
.progress-fill  { height:100%; border-radius:4px; background:#10b981; transition:width .6s ease; }

/* ── Sección propia / ajena ── */
.task-card {
    background:#fff; border-radius:10px; border:1px solid #e2e8f0;
    border-left:4px solid #e2e8f0; margin-bottom:8px;
    transition:box-shadow .15s; position:relative;
}
.task-card.mine      { box-shadow:0 2px 8px rgba(59,130,246,.15); }
.task-card.mine:hover { box-shadow:0 4px 16px rgba(59,130,246,.25); }
.task-card.others    { opacity:.6; }
.task-card .card-inner { padding:10px 14px; }

.task-title { font-size:.85rem; font-weight:600; color:#1e293b; margin-bottom:3px; }
.task-title.done-title { text-decoration:line-through; color:#94a3b8; }
.task-desc  { font-size:.75rem; color:#64748b; margin-bottom:6px; }
.task-etiqueta { font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:20px; color:#fff; display:inline-block; margin-bottom:5px; }
.task-avatar { width:24px; height:24px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.68rem; font-weight:700; flex-shrink:0; }
.mine-badge { position:absolute; top:8px; right:8px; font-size:.62rem; background:#dbeafe; color:#1e40af; padding:2px 6px; border-radius:20px; font-weight:600; }

/* ── Mensaje motivacional ── */
.motivacional { background:linear-gradient(135deg,#059669,#10b981); color:#fff; border-radius:12px; padding:16px 20px; text-align:center; }

@keyframes confetti { 0%{transform:translateY(0) rotate(0)} 100%{transform:translateY(-20px) rotate(360deg); opacity:0} }
.confetti-emoji { display:inline-block; animation:confetti .8s ease forwards; font-size:1.5rem; }
</style>
@endpush

@section('content')
@php
    $misTareas    = $activity->tasks->where('assigned_to', $userId);
    $total        = $misTareas->count();
    $pendientes   = $misTareas->where('status', 0)->count();
    $enProgreso   = $misTareas->where('status', 1)->count();
    $completadas  = $misTareas->where('status', 2)->count();
    $pct          = $total > 0 ? round(($completadas / $total) * 100) : 0;
    $todoListo    = $total > 0 && $completadas === $total;

    $columnas = [
        0 => ['label' => 'Pendiente',   'color' => '#f59e0b', 'icon' => 'fa-inbox'],
        1 => ['label' => 'En Progreso', 'color' => '#3b82f6', 'icon' => 'fa-spinner'],
        3 => ['label' => 'En Revisión', 'color' => '#8b5cf6', 'icon' => 'fa-search'],
        2 => ['label' => 'Completado',  'color' => '#10b981', 'icon' => 'fa-check-circle'],
    ];
@endphp

{{-- Hero personal --}}
<div class="hero-mis-tareas mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px">
        <div>
            <div style="font-size:.8rem;opacity:.7;margin-bottom:4px">
                <i class="fa fa-rocket mr-1"></i>{{ $activity->name }}
            </div>
            <div style="font-size:1.3rem;font-weight:800">Mis Tareas</div>
            <div style="font-size:.82rem;opacity:.75">
                {{ auth()->user()->name }} &nbsp;·&nbsp;
                {{ $activity->responsibles->firstWhere('id', $userId) ? 'Responsable de la actividad' : 'Colaborador' }}
            </div>
            <div class="progress-track" style="max-width:280px">
                <div class="progress-fill" style="width:{{ $pct }}%"></div>
            </div>
            <small style="opacity:.7;font-size:.72rem">{{ $pct }}% completado · {{ $completadas }}/{{ $total }} tareas tuyas</small>
        </div>
        <div class="personal-stats">
            <div class="stat-chip">
                <div class="num text-warning">{{ $pendientes }}</div>
                <div class="lbl">Pendientes</div>
            </div>
            <div class="stat-chip">
                <div class="num" style="color:#93c5fd">{{ $enProgreso }}</div>
                <div class="lbl">En progreso</div>
            </div>
            <div class="stat-chip">
                <div class="num text-success">{{ $completadas }}</div>
                <div class="lbl">Completadas</div>
            </div>
        </div>
    </div>
</div>

{{-- Mensaje motivacional --}}
@if($todoListo && $total > 0)
<div class="motivacional mb-4">
    <div style="font-size:1.8rem" class="mb-1">
        <span class="confetti-emoji">🎉</span> <span class="confetti-emoji" style="animation-delay:.1s">✨</span> <span class="confetti-emoji" style="animation-delay:.2s">🎊</span>
    </div>
    <h5 class="font-weight-bold mb-1">¡Completaste todas tus tareas!</h5>
    <p class="mb-0 small" style="opacity:.9">Excelente trabajo. Tu contribución hace la diferencia en el equipo.</p>
</div>
@elseif($pct >= 50)
<div class="alert alert-success mb-4 d-flex align-items-center" style="border-radius:12px">
    <span style="font-size:1.5rem;margin-right:12px">💪</span>
    <div>
        <strong>Vas por buen camino.</strong>
        <span class="d-block small text-muted">Ya completaste el {{ $pct }}% de tus tareas. ¡Seguí así!</span>
    </div>
</div>
@endif

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Mis Tareas — {{ $activity->name }}</li>
    </ol>
</nav>

{{-- Tablero completo --}}
<div class="row">
    @foreach($columnas as $status => $col)
    @php $colTareas = $activity->tasks->where('status', $status); @endphp
    <div class="col-md-3 mb-4">
        <div style="border-radius:10px 10px 0 0;background:{{ $col['color'] }};padding:10px 14px;color:#fff;font-weight:700;font-size:.84rem;display:flex;align-items:center;justify-content:space-between">
            <span><i class="fa {{ $col['icon'] }} mr-2"></i>{{ $col['label'] }}</span>
            <span class="badge badge-light text-dark">{{ $colTareas->count() }}</span>
        </div>
        <div style="background:#f8fafc;border-radius:0 0 10px 10px;min-height:100px;padding:10px">
            @forelse($colTareas->sortByDesc('created_at') as $task)
            @php
                $esMia     = $task->assigned_to === $userId;
                $cardColor = $task->color ?? '#6b7280';
                $initials  = $task->assignedTo ? strtoupper(substr($task->assignedTo->name, 0, 2)) : '?';
                $isDone    = $task->status === 2;
            @endphp
            <div class="task-card {{ $esMia ? 'mine' : 'others' }}" style="border-left-color:{{ $cardColor }}">
                @if($esMia)
                <span class="mine-badge">Mi tarea</span>
                @endif
                <div class="card-inner">
                    @if($task->etiqueta)
                    <span class="task-etiqueta" style="background:{{ $cardColor }}">{{ $task->etiqueta }}</span>
                    @endif
                    <div class="task-title {{ $isDone ? 'done-title' : '' }}">
                        @if($isDone)<i class="fa fa-check-circle text-success mr-1"></i>@endif
                        {{ $task->title }}
                    </div>
                    @if($task->details)
                    <div class="task-desc">{{ Str::limit($task->details, 70) }}</div>
                    @endif

                    @if($isDone && $task->completion_note)
                    <div class="mt-1 p-1 rounded small" style="background:#f0fff4;border-left:3px solid #10b981;font-size:.7rem">
                        <i class="fa fa-comment-alt text-success mr-1"></i><em>{{ $task->completion_note }}</em>
                    </div>
                    @endif

                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px">
                        <div style="display:flex;align-items:center;gap:5px">
                            <div class="task-avatar" style="background:{{ $cardColor }}20;color:{{ $cardColor }}">
                                {{ $initials }}
                            </div>
                            <small class="text-muted" style="font-size:.7rem">{{ $task->assignedTo->name ?? '—' }}</small>
                        </div>
                        {{-- Solo puede mover sus propias tareas --}}
                        @if($esMia)
                        <div style="display:flex;gap:3px">
                            @if($status > 0)
                            <button class="btn btn-xs btn-outline-secondary btn-move-left"
                                    data-id="{{ $task->id }}" data-status="{{ $status }}" title="Retroceder">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                            @endif
                            @if($status < 2)
                            <button class="btn btn-xs btn-outline-primary btn-move-right"
                                    data-id="{{ $task->id }}" data-status="{{ $status }}" title="Avanzar">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px 8px;color:#cbd5e1;font-size:.78rem">
                <i class="fa fa-inbox fa-lg d-block mb-1"></i>Sin tareas
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

{{-- Modal nota de cierre --}}
@include('admin.globales.activities.partials.modal_completion')

@endsection

@section('scripts')
<script>
var statusBase = "{{ url('admin/globales/activities/tareas') }}";
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$('body').on('click', '.btn-move-right', function() {
    var taskId = $(this).data('id');
    var curStatus = parseInt($(this).data('status'));
    var statuses = [0, 1, 3, 2];
    var idx = statuses.indexOf(curStatus);
    var newStatus = statuses[idx + 1] !== undefined ? statuses[idx + 1] : curStatus;
    if (newStatus === 2) {
        $('#completion_task_id').val(taskId);
        $('#completion_new_status').val(newStatus);
        $('#completion_note').val('');
        $('#completionModal').modal('show');
    } else {
        moveTask(taskId, newStatus, null);
    }
});

$('body').on('click', '.btn-move-left', function() {
    var taskId = $(this).data('id');
    var curStatus = parseInt($(this).data('status'));
    var statuses = [0, 1, 3, 2];
    var idx = statuses.indexOf(curStatus);
    var newStatus = statuses[idx - 1] !== undefined ? statuses[idx - 1] : curStatus;
    moveTask(taskId, newStatus, null);
});

$('#btnConfirmComplete').click(function() {
    var note = $('#completion_note').val().trim();
    if (!note) { toastr.error('El comentario de cierre es obligatorio'); return; }
    moveTask($('#completion_task_id').val(), $('#completion_new_status').val(), note);
    $('#completionModal').modal('hide');
});

function moveTask(taskId, newStatus, note) {
    $.ajax({
        url: statusBase + '/' + taskId + '/status', type: 'PATCH',
        data: { status: newStatus, completion_note: note },
        success: function() { location.reload(); },
        error: function(xhr) { toastr.error(xhr.responseJSON?.errors?.completion_note?.[0] || 'Error'); }
    });
}
</script>
@endsection
