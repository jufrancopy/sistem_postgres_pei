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
.task-card.others    { opacity:.85; }
.task-card .card-inner { padding:10px 14px; }

.task-title { font-size:.85rem; font-weight:600; color:#1e293b; margin-bottom:3px; }
.task-title.done-title { text-decoration:line-through; color:#94a3b8; }
.task-desc  { font-size:.75rem; color:#64748b; margin-bottom:6px; }
.task-etiqueta { font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:20px; color:#fff; display:inline-block; margin-bottom:5px; }
.task-avatar { width:24px; height:24px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.68rem; font-weight:700; flex-shrink:0; }
.task-footer { display:flex; align-items:center; justify-content:space-between; margin-top:6px; }
.task-actions { display:flex; gap:3px; flex-wrap:wrap; justify-content:flex-end; }
.task-actions .btn { padding:2px 6px; font-size:.7rem; border-radius:6px; }
.mine-badge { position:absolute; top:8px; right:8px; font-size:.62rem; background:#dbeafe; color:#1e40af; padding:2px 6px; border-radius:20px; font-weight:600; z-index:1; }

/* ── Mensaje motivacional ── */
.motivacional { background:linear-gradient(135deg,#059669,#10b981); color:#fff; border-radius:12px; padding:16px 20px; text-align:center; }

@keyframes confetti { 0%{transform:translateY(0) rotate(0)} 100%{transform:translateY(-20px) rotate(360deg); opacity:0} }
.confetti-emoji { display:inline-block; animation:confetti .8s ease forwards; font-size:1.5rem; }
</style>
@endpush

@section('content')
@php
    $isScrumActivity = $activity->type === 'scrum';
    $misTareas    = $activity->tasks->where('assigned_to', $userId);
    $total        = $misTareas->count();
    $pendientes   = $misTareas->where('status', 0)->count();
    $enProgreso   = $misTareas->where('status', 1)->count();
    $completadas  = $misTareas->where('status', 2)->count();
    $pct          = $total > 0 ? round(($completadas / $total) * 100) : 0;
    $todoListo    = $total > 0 && $completadas === $total;

    $columnas = $isScrumActivity ? [
        0 => ['label' => 'Pendiente',   'color' => '#f59e0b', 'icon' => 'fa-inbox'],
        1 => ['label' => 'En Progreso', 'color' => '#3b82f6', 'icon' => 'fa-spinner'],
        3 => ['label' => 'En Revisión', 'color' => '#8b5cf6', 'icon' => 'fa-search'],
        2 => ['label' => 'Completado',  'color' => '#10b981', 'icon' => 'fa-check-circle'],
    ] : [
        0 => ['label' => 'Backlog',       'color' => '#94a3b8', 'icon' => 'fa-inbox'],
        4 => ['label' => 'Priorizado',    'color' => '#f59e0b', 'icon' => 'fa-sort-amount-up'],
        1 => ['label' => 'En Ejecución',  'color' => '#3b82f6', 'icon' => 'fa-spinner'],
        3 => ['label' => 'En Revisión',   'color' => '#8b5cf6', 'icon' => 'fa-search'],
        2 => ['label' => 'Finalizado',    'color' => '#10b981', 'icon' => 'fa-check-circle'],
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
    <div class="col-md mb-4" style="min-width:220px">
        <div style="border-radius:10px 10px 0 0;background:{{ $col['color'] }};padding:10px 14px;color:#fff;font-weight:700;font-size:.84rem;display:flex;align-items:center;justify-content:space-between">
            <span><i class="fa {{ $col['icon'] }} mr-2"></i>{{ $col['label'] }}</span>
            <span class="badge badge-light text-dark">{{ $colTareas->count() }}</span>
        </div>
        <div style="background:#f8fafc;border-radius:0 0 10px 10px;min-height:100px;padding:10px">
            @forelse($colTareas->sortByDesc('created_at') as $task)
                @include('admin.globales.activities.partials.task_card', [
                    'task'            => $task,
                    'status'          => $status,
                    'isScrumActivity' => $isScrumActivity,
                    'modoColaborador' => true,
                    'esMia'           => $task->assigned_to === $userId,
                ])
            @empty
            <div style="text-align:center;padding:20px 8px;color:#cbd5e1;font-size:.78rem">
                <i class="fa fa-inbox fa-lg d-block mb-1"></i>Sin tareas
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

{{-- Modales --}}
@include('admin.globales.activities.partials.modal_completion')
@include('admin.globales.activities.partials.modal_comentarios')
@include('admin.globales.activities.partials.modal_detalle_tarea')

@endsection

@section('scripts')
<script>
var statusBase = "{{ url('admin/globales/activities/tareas') }}";
var statuses   = @json(array_keys($columnas));
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$('body').on('click', '.btn-move-right', function() {
    var taskId = $(this).data('id');
    var curStatus = parseInt($(this).data('status'));
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
@include('admin.globales.activities.partials.scripts_comentarios')
</script>
@endsection
