@extends('layouts.master')
@section('title', $activity->name)

@push('styles')
<style>
/* ── Variables ── */
:root {
    --col-pending:  #f59e0b;
    --col-progress: #3b82f6;
    --col-review:   #8b5cf6;
    --col-done:     #10b981;
}

/* ── Header ── */
.activity-hero {
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
    border-radius: 0 0 16px 16px;
    padding: 24px 28px 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.activity-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 4px; }
.hero-meta  { font-size: .82rem; opacity: .8; }

/* ── Barra de progreso global ── */
.progress-track { height: 8px; border-radius: 4px; background: rgba(255,255,255,.25); overflow: hidden; margin-top: 12px; }
.progress-fill  { height: 100%; border-radius: 4px; background: #10b981; transition: width .6s ease; }

/* ── Chips de estado ── */
.chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }

/* ── Toggle vista ── */
.view-toggle .btn { border-radius: 20px; font-size: .78rem; }

/* ── Columnas del tablero ── */
.board-col { min-width: 0; }
.col-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-radius: 10px 10px 0 0; color: #fff; font-weight: 700; font-size: .85rem;
}
.col-body { background: #f8fafc; border-radius: 0 0 10px 10px; min-height: 120px; padding: 10px; }

/* ── Etiqueta grupo ── */
.etiqueta-group-header {
    display: flex; align-items: center; gap: 8px;
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: #64748b; margin: 8px 0 4px;
}
.etiqueta-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

/* ── Task card ── */
.task-card {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #e2e8f0;
    margin-bottom: 8px;
    transition: box-shadow .15s, transform .1s;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.task-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); transform: translateY(-1px); }
.task-card.completed-card { opacity: .7; }
.task-card .card-inner { padding: 10px 12px; }

.task-title { font-size: .84rem; font-weight: 600; color: #1e293b; margin-bottom: 3px; line-height: 1.3; }
.task-title.done-title { text-decoration: line-through; color: #94a3b8; }
.task-desc  { font-size: .75rem; color: #64748b; margin-bottom: 6px; }

.task-etiqueta {
    display: inline-block; font-size: .65rem; font-weight: 700;
    padding: 2px 7px; border-radius: 20px; color: #fff; margin-bottom: 5px;
}

.task-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 6px; }
.task-avatar {
    width: 22px; height: 22px; border-radius: 50%; background: #e2e8f0;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .65rem; font-weight: 700; color: #475569; flex-shrink: 0;
}
.task-actions { display: flex; gap: 3px; }
.task-actions .btn { padding: 2px 6px; font-size: .7rem; border-radius: 6px; }

/* ── Drag & Drop ── */
.col-body { transition: background .15s; }
.col-body.sortable-over { background: #e0f2fe !important; }
.sortable-ghost  { opacity: .4; transform: rotate(2deg); }
.sortable-chosen { box-shadow: 0 8px 24px rgba(0,0,0,.2) !important; transform: scale(1.02); cursor: grabbing; }
.task-card { cursor: default; }
.drag-handle { cursor: grab; }
.drag-handle:active { cursor: grabbing; }

/* ── Vencida pulsante ── */
@keyframes pulse-red { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)} 50%{box-shadow:0 0 0 4px rgba(239,68,68,.0)} }
.task-vencida { animation: pulse-red 2s infinite; }

/* ── Empty col ── */
.col-empty { text-align: center; padding: 24px 12px; color: #cbd5e1; font-size: .8rem; }

/* ── Paleta de colores ── */
.color-palette { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
.color-swatch {
    width: 26px; height: 26px; border-radius: 50%; cursor: pointer;
    border: 2px solid transparent; transition: transform .15s, border-color .15s;
}
.color-swatch:hover { transform: scale(1.2); }
.color-swatch.selected { border-color: #1e293b; transform: scale(1.15); }

/* ── Tabla Cronograma Simple ── */
.table-cronograma {
    width: 100%;
    border-collapse: collapse;
    font-size: .85rem;
}
.table-cronograma thead {
    background: #f1f5f9;
    border-bottom: 2px solid #1e293b;
}
.table-cronograma th {
    padding: 12px;
    text-align: left;
    font-weight: 700;
    color: #1e293b;
    white-space: nowrap;
}
.table-cronograma td {
    padding: 10px 12px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}
.table-cronograma tbody tr:hover {
    background: #f8fafc;
}
.table-cronograma tbody tr:last-child td {
    border-bottom: none;
}
.tarea-titulo {
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
}
.tarea-titulo:hover {
    color: #3b82f6;
    text-decoration: underline;
}
.tarea-responsable {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    background: #dbeafe;
    border-radius: 6px;
    font-size: .8rem;
}
.tarea-responsable::before {
    content: '';
    display: inline-block;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #3b82f6;
    color: #fff;
    font-size: .7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}
.tarea-fecha {
    color: #475569;
    font-size: .8rem;
    white-space: nowrap;
}
.tarea-fecha.vencida {
    color: #ef4444;
    font-weight: 600;
}
.tarea-fecha.proximo {
    color: #f97316;
    font-weight: 600;
}

/* ── Responsive ── */
@media(max-width:768px) {
    .board-row { overflow-x: auto; flex-wrap: nowrap !important; padding-bottom: 12px; }
    .board-col { min-width: 240px; }
}
</style>
@endpush

@section('content')
@php
    $isScrumActivity = $activity->type === 'scrum';

    // Columnas según tipo
    $columnas = $isScrumActivity ? [
        0 => ['label' => 'Backlog / Por hacer', 'icon' => 'fa-inbox',        'color' => '#f59e0b'],
        1 => ['label' => 'En Progreso',          'icon' => 'fa-spinner',      'color' => '#3b82f6'],
        3 => ['label' => 'En Revisión',           'icon' => 'fa-search',       'color' => '#8b5cf6'],
        2 => ['label' => 'Hecho',                 'icon' => 'fa-check-circle', 'color' => '#10b981'],
    ] : [
        0 => ['label' => 'Backlog',       'icon' => 'fa-inbox',        'color' => '#94a3b8'],
        4 => ['label' => 'Priorizado',    'icon' => 'fa-sort-amount-up','color' => '#f59e0b'],
        1 => ['label' => 'En Ejecución',  'icon' => 'fa-spinner',      'color' => '#3b82f6'],
        3 => ['label' => 'En Revisión',   'icon' => 'fa-search',       'color' => '#8b5cf6'],
        2 => ['label' => 'Finalizado',    'icon' => 'fa-check-circle', 'color' => '#10b981'],
    ];

    $totalTareas    = $activity->tasks->count();
    $tareasHechas   = $activity->tasks->where('status', 2)->count();
    $pctGlobal      = $totalTareas > 0 ? round(($tareasHechas / $totalTareas) * 100) : 0;
    $responsables   = $activity->responsibles->pluck('name')->implode(', ');
@endphp

{{-- ── Hero ── --}}
<div class="activity-hero mb-4">
    <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap:12px">
        <div style="flex:1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="chip" style="background:rgba(255,255,255,.2);color:#fff">
                    <i class="fa {{ $isScrumActivity ? 'fa-sync-alt' : 'fa-stream' }} mr-1"></i>
                    {{ $isScrumActivity ? 'Scrum' : 'Kanban' }}
                </span>
            </div>
            <div class="hero-title">{{ $activity->name }}</div>
            @if($activity->description)
            <div class="hero-meta mb-1">{{ $activity->description }}</div>
            @endif
            <div class="hero-meta">
                @if($activity->date_start)
                <i class="fa fa-calendar mr-1"></i>
                {{ \Carbon\Carbon::parse($activity->date_start)->format('d/m/Y') }} —
                {{ \Carbon\Carbon::parse($activity->date_end)->format('d/m/Y') }} &nbsp;|&nbsp;
                @endif
                <i class="fa fa-users mr-1"></i>{{ $responsables ?: 'Sin responsables' }}
            </div>
            <div class="progress-track" style="max-width:320px">
                <div class="progress-fill" style="width:{{ $pctGlobal }}%"></div>
            </div>
            <small style="opacity:.75;font-size:.73rem">
                {{ $pctGlobal }}% completado &nbsp;·&nbsp; {{ $tareasHechas }}/{{ $totalTareas }} tareas
            </small>
        </div>
        <div class="d-flex flex-column gap-2" style="flex-shrink:0">
            @hasanyrole('Administrador|Gestor de Actividades')
            <button class="btn btn-light btn-sm font-weight-bold" id="btnNuevaTarea">
                <i class="fa fa-plus mr-1"></i>Nueva Tarea
            </button>
            <button class="btn btn-sm font-weight-bold" style="background:rgba(255,255,255,.9);color:#1e3a5f;border:none"
                    id="btnVerReuniones">
                <i class="fa fa-users mr-1"></i>Reuniones
            </button>
            <button class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3)"
                    id="btnNotificarTodos" data-id="{{ $activity->id }}">
                <i class="fa fa-paper-plane mr-1"></i>Notificar a todos
            </button>
            @endhasanyrole
            <button class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2)"
                    data-toggle="modal" data-target="#modalAyuda" title="Cómo usar el tablero">
                <i class="fa fa-question-circle mr-1"></i>Ayuda
            </button>
        </div>
    </div>
</div>

{{-- Breadcrumb + controles --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap" style="gap:8px">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('globales.activities.index') }}">Actividades</a></li>
            <li class="breadcrumb-item active">{{ $activity->name }}</li>
        </ol>
    </nav>
    <div class="view-toggle d-flex gap-1">
        <button class="btn btn-sm btn-primary active" id="btnVistaEstado">
            <i class="fa fa-columns mr-1"></i>Por estado
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="btnVistaEtiqueta">
            <i class="fa fa-tag mr-1"></i>Por etiqueta
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="btnVistaGantt">
            <i class="fa fa-stream mr-1"></i>Cronograma
        </button>
    </div>
</div>

{{-- Alerta tareas vencidas/por vencer --}}
@php
    $vencidas  = $activity->tasks->filter(fn($t) => $t->status !== 2 && $t->fecha_vencimiento && \Carbon\Carbon::parse($t->fecha_vencimiento)->isPast());
    $porVencer = $activity->tasks->filter(fn($t) => $t->status !== 2 && $t->fecha_vencimiento && !\Carbon\Carbon::parse($t->fecha_vencimiento)->isPast() && \Carbon\Carbon::parse($t->fecha_vencimiento)->diffInDays(now()) <= 3);
@endphp
@if($vencidas->count() > 0)
<div class="alert alert-danger d-flex align-items-center mb-3 py-2" style="border-radius:10px;cursor:pointer;transition:all .2s" 
     id="alertVencidas" 
     data-task-ids="{{ $vencidas->pluck('id')->join(',') }}"
     onmouseover="this.style.opacity='0.85'" 
     onmouseout="this.style.opacity='1'">
    <i class="fa fa-exclamation-circle fa-lg mr-3"></i>
    <div style="flex:1">
        <strong>{{ $vencidas->count() }} tarea(s) vencida(s)</strong>
        — {{ $vencidas->pluck('title')->implode(', ') }}
    </div>
    <small style="opacity:.7;margin-left:12px;white-space:nowrap">(click para filtrar)</small>
</div>
@endif
@if($porVencer->count() > 0)
<div class="alert alert-warning d-flex align-items-center mb-3 py-2" style="border-radius:10px;cursor:pointer;transition:all .2s" 
     id="alertPorVencer" 
     data-task-ids="{{ $porVencer->pluck('id')->join(',') }}"
     onmouseover="this.style.opacity='0.85'" 
     onmouseout="this.style.opacity='1'">
    <i class="fa fa-clock fa-lg mr-3"></i>
    <div style="flex:1">
        <strong>{{ $porVencer->count() }} tarea(s) próximas a vencer</strong>
        — {{ $porVencer->pluck('title')->implode(', ') }}
    </div>
    <small style="opacity:.7;margin-left:12px;white-space:nowrap">(click para filtrar)</small>
</div>
@endif

{{-- Badge filtro activo --}}
<div id="filtroActivoContainer" style="display:none;margin-bottom:12px">
    <span class="badge badge-info" style="font-size:.9rem;padding:8px 12px">
        <i class="fa fa-filter mr-2"></i>Filtrando tareas... 
        <a href="#" id="btnLimpiarFiltro" style="color:#fff;margin-left:8px;text-decoration:underline">Limpiar</a>
    </span>
</div>

{{-- ── TABLERO POR ESTADO ── --}}
<div id="vistaEstado">
    <div class="row board-row">
        @foreach($columnas as $status => $col)
        @php $colTareas = $activity->tasks->where('status', $status); @endphp
        <div class="col-md board-col mb-4" data-status="{{ $status }}">
            <div class="col-header" style="background:{{ $col['color'] }}">
                <span><i class="fa {{ $col['icon'] }} mr-2"></i>{{ $col['label'] }}</span>
                <span class="badge badge-light text-dark">{{ $colTareas->count() }}</span>
            </div>
            <div class="col-body">
                @forelse($colTareas->sortByDesc('created_at') as $task)
                    @include('admin.globales.activities.partials.task_card', [
                        'task'            => $task,
                        'status'          => $status,
                        'isScrumActivity' => $isScrumActivity,
                    ])
                @empty
                <div class="col-empty"><i class="fa fa-inbox fa-lg d-block mb-1"></i>Sin tareas</div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── TABLERO POR ETIQUETA ── --}}
<div id="vistaEtiqueta" style="display:none">
    @php
        $porEtiqueta = $activity->tasks->groupBy(fn($t) => $t->etiqueta ?: 'Sin etiqueta');
    @endphp
    @forelse($porEtiqueta as $etiqueta => $tareas)
    @php $colorEtiqueta = $tareas->first()->color ?? '#6b7280'; @endphp
    <div class="card mb-3 shadow-sm">
        <div class="card-body py-3">
            <div class="d-flex align-items-center mb-3">
                <span class="etiqueta-dot mr-2" style="background:{{ $colorEtiqueta }};width:12px;height:12px;border-radius:50%"></span>
                <strong style="color:{{ $colorEtiqueta }}">{{ $etiqueta }}</strong>
                <span class="badge badge-secondary ml-2">{{ $tareas->count() }} tareas</span>
            </div>
            <div class="row">
                @foreach($tareas as $task)
                <div class="col-md-4 mb-2">
                    @include('admin.globales.activities.partials.task_card', [
                        'task'            => $task,
                        'status'          => $task->status,
                        'isScrumActivity' => $isScrumActivity,
                    ])
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">Sin tareas</div>
    @endforelse
</div>

{{-- ── TABLERO GANTT ── --}}
<div id="vistaGantt" style="display:none">
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; overflow-x: auto; max-height: 600px; overflow-y: auto;">
        <div style="font-size: 1rem; font-weight: 700; margin-bottom: 16px; color: #1e293b;">Cronograma de tareas</div>
        <div id="tablaCronograma"></div>
        <div id="timelineEmpty" style="display:none; text-align:center; color:#94a3b8; font-size:.9rem; padding:32px;">
            No hay tareas con fecha de inicio o vencimiento. Agregá fechas para ver el cronograma.
        </div>
    </div>
</div>

{{-- ── MODALES ── --}}
@include('admin.globales.activities.partials.modal_tarea', ['activity' => $activity])
@include('admin.globales.activities.partials.modal_completion')
@include('admin.globales.activities.partials.modal_evidencia')
@include('admin.globales.activities.partials.modal_comentarios')
@include('admin.globales.activities.partials.modal_ayuda', ['isScrumActivity' => $isScrumActivity])
@include('admin.globales.activities.partials.modal_detalle_tarea')
@include('admin.globales.activities.partials.modal_reuniones')

@endsection

@push('scripts')
<script>
var activityId   = {{ $activity->id }};
var storeUrl     = "{{ route('globales.activities.tareas.store', $activity->id) }}";
var statusBase   = "{{ url('admin/globales/activities/tareas') }}";
var notifAllUrl  = "{{ route('globales.activities.notificar-todos', $activity->id) }}";
var getUsersUrl  = "{{ route('globales.get-users') }}";

$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

// ── Toggle vista ──────────────────────────────────────────────────────────────
$('#btnVistaEstado').click(function() {
    $('#vistaEstado').show(); $('#vistaEtiqueta').hide(); $('#vistaGantt').hide();
    $(this).addClass('btn-primary active').removeClass('btn-outline-secondary');
    $('#btnVistaEtiqueta, #btnVistaGantt').removeClass('btn-primary active').addClass('btn-outline-secondary');
});
$('#btnVistaEtiqueta').click(function() {
    $('#vistaEtiqueta').show(); $('#vistaEstado').hide(); $('#vistaGantt').hide();
    $(this).addClass('btn-primary active').removeClass('btn-outline-secondary');
    $('#btnVistaEstado, #btnVistaGantt').removeClass('btn-primary active').addClass('btn-outline-secondary');
});
$('#btnVistaGantt').click(function() {
    $('#vistaGantt').show(); $('#vistaEstado').hide(); $('#vistaEtiqueta').hide();
    $(this).addClass('btn-primary active').removeClass('btn-outline-secondary');
    $('#btnVistaEstado, #btnVistaEtiqueta').removeClass('btn-primary active').addClass('btn-outline-secondary');
    renderTimeline();
});

@php
    $ganttTasks = $activity->tasks
        ->filter(fn($t) => $t->fecha_inicio || $t->fecha_vencimiento)
        ->map(fn($t) => [
            'id'       => 'task_' . $t->id,
            'name'     => Str::limit($t->title, 50),
            'resource' => $t->assignedTo?->name ?? 'Sin responsable',
            'start'    => $t->fecha_inicio?->format('Y-m-d'),
            'end'      => $t->fecha_vencimiento?->format('Y-m-d'),
            'color'    => $t->color ?? '#6b7280',
        ])->values();
@endphp

var ganttTasks = @json($ganttTasks);

var ganttRendered = false;

function renderTimeline() {
    if (!ganttTasks.length) {
        $('#tablaCronograma').hide();
        $('#timelineEmpty').show();
        return;
    }

    $('#timelineEmpty').hide();
    $('#tablaCronograma').show();

    // Ordenar por fecha inicio o vencimiento
    var tasksOrdenadas = ganttTasks.sort(function(a, b) {
        var dateA = a.start || a.end;
        var dateB = b.start || b.end;
        return new Date(dateA) - new Date(dateB);
    });

    // Generar tabla
    var html = '<table class="table-cronograma"><thead><tr>' +
        '<th style="width:35%">Tarea</th>' +
        '<th style="width:20%">Responsable</th>' +
        '<th style="width:15%">Inicio</th>' +
        '<th style="width:15%">Vencimiento</th>' +
        '<th style="width:15%">Duración</th>' +
        '</tr></thead><tbody>';

    tasksOrdenadas.forEach(function(task) {
        var start = task.start ? new Date(task.start) : null;
        var end = task.end ? new Date(task.end) : null;

        var startStr = start ? start.toLocaleDateString('es-ES') : '—';
        var endStr = end ? end.toLocaleDateString('es-ES') : '—';
        var duration = '—';

        if (start && end) {
            var days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            duration = days + ' día' + (days !== 1 ? 's' : '');
        }

        // Detectar si está vencida
        var endClass = '';
        if (end && end < new Date() && !task.completed) {
            endClass = 'vencida';
        } else if (end && !task.completed) {
            var daysUntil = Math.ceil((end - new Date()) / (1000 * 60 * 60 * 24));
            if (daysUntil <= 3 && daysUntil > 0) {
                endClass = 'proximo';
            }
        }

        html += '<tr style="cursor:pointer" class="task-row" data-id="' + task.id + '">' +
            '<td><div class="tarea-titulo">' + task.name + '</div></td>' +
            '<td><div class="tarea-responsable">' + task.resource + '</div></td>' +
            '<td><div class="tarea-fecha">' + startStr + '</div></td>' +
            '<td><div class="tarea-fecha ' + endClass + '">' + endStr + '</div></td>' +
            '<td><div class="tarea-fecha">' + duration + '</div></td>' +
            '</tr>';
    });

    html += '</tbody></table>';

    $('#tablaCronograma').html(html);

    // Evento click en fila
    $('.task-row').on('click', function() {
        var taskId = $(this).data('id');
        // Podría abrir un modal, pero por ahora solo mostramos la tabla
    });
}

// ── Etiquetas existentes (reutilizables) ─────────────────────────────────────
var etiquetasActividad = @json(
    $activity->tasks
        ->whereNotNull('etiqueta')
        ->groupBy('etiqueta')
        ->map(fn($group) => ['etiqueta' => $group->first()->etiqueta, 'color' => $group->first()->color])
        ->values()
);

function cargarEtiquetasExistentes() {
    var $cont = $('#etiquetasSugeridas').empty();
    if (!etiquetasActividad.length) {
        $cont.append('<small class="text-muted">Sin etiquetas previas</small>');
        return;
    }
    etiquetasActividad.forEach(function(e) {
        $cont.append(
            $('<span>')
                .text(e.etiqueta)
                .css({ background: e.color, color: '#fff', fontSize: '.68rem', fontWeight: '600',
                       padding: '2px 9px', borderRadius: '20px', cursor: 'pointer', display: 'inline-block' })
                .on('click', function() {
                    $('#task_etiqueta').val(e.etiqueta);
                    colorSeleccionado = e.color;
                    renderPaleta();
                })
        );
    });
}

// ── Paleta de colores ─────────────────────────────────────────────────────────
var COLORES = ['#ef4444','#f97316','#f59e0b','#22c55e','#10b981','#14b8a6',
               '#3b82f6','#6366f1','#8b5cf6','#ec4899','#64748b','#1e293b'];
var colorSeleccionado = '#6b7280';

function renderPaleta() {
    var html = '';
    COLORES.forEach(function(c) {
        var sel = c === colorSeleccionado ? ' selected' : '';
        html += '<div class="color-swatch' + sel + '" style="background:' + c + '" data-color="' + c + '" title="' + c + '"></div>';
    });
    $('#colorPaleta').html(html);
    $('#color_input').val(colorSeleccionado);
    $('#colorPreview').css('background', colorSeleccionado);
}

$(document).on('click', '.color-swatch', function() {
    colorSeleccionado = $(this).data('color');
    renderPaleta();
});

// ── Select2 responsable ───────────────────────────────────────────────────────
function initResponsableSelect(selectedId, selectedText) {
    var $select = $('#task_assigned_to').empty();
    if ($select.select2) {
        $select.select2({
            placeholder: 'Seleccioná responsable',
            allowClear: true,
            dropdownParent: $('#tareaModal'),
            ajax: {
                url: getUsersUrl, dataType: 'json', delay: 250,
                processResults: function(data) {
                    return { results: $.map(data, function(u) { return { id: u.id, text: u.name }; }) };
                }
            }
        });
    }
    if (selectedId) {
        $select.append(new Option(selectedText, selectedId, true, true));
        if ($select.select2) {
            $select.trigger('change');
        }
    }
}


// ── Nueva tarea ───────────────────────────────────────────────────────────────
$('#btnNuevaTarea').click(function() {
    $('#tareaHeading').text('Nueva Tarea');
    $('#tareaForm')[0].reset();
    $('#task_id').val('');
    colorSeleccionado = '#6b7280';
    renderPaleta();
    initResponsableSelect(null, null);
    cargarEtiquetasExistentes();
    $('#tareaModal').modal('show');
});

$('#tareaForm').submit(function(e) {
    e.preventDefault();
    $('#saveTareaBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');
    $.ajax({
        url: storeUrl, type: 'POST',
        data: $(this).serialize() + '&color=' + encodeURIComponent(colorSeleccionado),
        success: function(res) {
            $('#tareaModal').modal('hide');
            toastr.success(res.success);
            location.reload();
        },
        error: function(xhr) {
            var errors = xhr.responseJSON?.errors;
            if (errors) $.each(errors, function(k,v) { toastr.error(v); });
            $('#saveTareaBtn').prop('disabled', false).html('Guardar');
        }
    });
});

// ── Editar tarea ─────────────────────────────────────────────────────────────
$('body').on('click', '.editTaskBtn', function() {
    var taskId = $(this).data('id');
    $.get(statusBase + '/' + taskId + '/detalle', function(res) {
        if (res?.ok && res?.data) {
            var task = res.data;
            $('#tareaHeading').text('Editar Tarea');
            $('#task_id').val(taskId);
            $('#task_title').val(task.title);
            $('#task_details').val(task.details || '');
            $('#task_etiqueta').val(task.etiqueta || '');
            $('#task_fecha_inicio').val(task.fecha_inicio_raw || '');
            $('#task_fecha_vencimiento').val(task.fecha_vencimiento_raw || '');
            $('#task_status').val(task.status);
            $('#task_es_reunion').prop('checked', task.es_reunion == 1);
            initResponsableSelect(task.assigned_to, task.responsable || '');
            colorSeleccionado = task.color || '#6b7280';
            renderPaleta();
            cargarEtiquetasExistentes();
            $('#tareaModal').modal('show');
        }
    }).fail(function() {
        // fallback: tomar datos del DOM si falla la API
        var $card = $('[data-id="' + taskId + '"]').first();
        $('#tareaHeading').text('Editar Tarea');
        $('#task_id').val(taskId);
        $('#task_title').val($card.find('.task-title').clone().find('.fa-check-circle').remove().end().text().trim());
        $('#task_details').val($card.find('.task-desc').text().trim());
        $('#task_etiqueta').val($card.find('.task-etiqueta').text().trim());
        $('#task_fecha_inicio').val($card.data('fecha-inicio') || '');
        $('#task_es_reunion').prop('checked', false);
        colorSeleccionado = $card.css('border-left-color') || '#6b7280';
        renderPaleta();
        initResponsableSelect(null, null);
        cargarEtiquetasExistentes();
        $('#tareaModal').modal('show');
    });
});

// ── Editar tarea (POST con datos reales) ──────────────────────────────────────
$('body').on('click', '.btn-move-right', function() {
    var taskId = $(this).data('id');
    var curStatus = parseInt($(this).data('status'));
    var statuses = @json(array_keys($columnas));
    var idx = statuses.indexOf(curStatus);
    var newStatus = statuses[idx + 1] !== undefined ? statuses[idx + 1] : curStatus;
    if (newStatus === 2) {
        $('#completion_task_id').val(taskId);
        $('#completion_new_status').val(newStatus);
        $('#completion_note').val('');
        $('#completionModal').modal('show');
    } else {
        doMoveTask(taskId, newStatus, null);
    }
});

$('body').on('click', '.btn-move-left', function() {
    var taskId = $(this).data('id');
    var curStatus = parseInt($(this).data('status'));
    var statuses = @json(array_keys($columnas));
    var idx = statuses.indexOf(curStatus);
    var newStatus = statuses[idx - 1] !== undefined ? statuses[idx - 1] : curStatus;
    doMoveTask(taskId, newStatus, null);
});

$('#btnConfirmComplete').click(function() {
    var note = $('#completion_note').val().trim();
    if (!note) { toastr.error('El comentario de cierre es obligatorio'); return; }
    doMoveTask($('#completion_task_id').val(), $('#completion_new_status').val(), note);
    $('#completionModal').modal('hide');
});

function doMoveTask(taskId, newStatus, note) {
    $.ajax({
        url: statusBase + '/' + taskId + '/status', type: 'PATCH',
        data: { status: newStatus, completion_note: note },
        success: function() { location.reload(); },
        error: function(xhr) { toastr.error(xhr.responseJSON?.errors?.completion_note?.[0] || 'Error al actualizar'); }
    });
}

// ── Eliminar tarea ────────────────────────────────────────────────────────────
$('body').on('click', '.btn-delete-task', function() {
    var taskId = $(this).data('id');
    Swal.fire({ title:'¿Eliminar tarea?', icon:'warning', showCancelButton:true,
        confirmButtonColor:'#d33', confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar'
    }).then(function(r) {
        if (r.value) $.ajax({ url: statusBase + '/' + taskId, type: 'DELETE',
            success: function() { location.reload(); } });
    });
});

// ── Notificar tarea individual ────────────────────────────────────────────────
$('body').on('click', '.btn-notificar-tarea', function() {
    var taskId = $(this).data('id');
    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    $.ajax({
        url: '/admin/globales/activities/tareas/' + taskId + '/notificar',
        type: 'POST',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function(r) { toastr.success(r.success); },
        error:   function(xhr) { toastr.error(xhr.responseJSON?.error || 'Error al notificar'); },
        complete: function() { $btn.prop('disabled', false).html('<i class="fa fa-envelope"></i>'); }
    });
});

// ── Notificar a todos ─────────────────────────────────────────────────────────
$('#btnNotificarTodos').click(function() {
    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Enviando...');
    $.ajax({
        url: notifAllUrl, type: 'POST',
        success: function(r) { toastr.success(r.success); },
        error:   function()  { toastr.error('Error al enviar notificaciones'); },
        complete: function() { $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i>Notificar a todos'); }
    });
});

// ── Evidencias ────────────────────────────────────────────────────────────────
$('body').on('click', '.btn-add-evidence', function() {
    $('#evidence_task_id').val($(this).data('id'));
    $('#ev_label').val(''); $('#ev_url').val(''); $('#ev_file').val('');
    $('#evidenceModal').modal('show');
});

$('#evidenceModal').on('shown.bs.modal', function() { toggleEvidenceSection('url'); });

$('body').on('change', '#ev_file', function() {
    var type = $('#evidence_type').val();
    var maxMB = type === 'image' ? 2 : 5;
    var file = this.files[0];
    if (file && file.size > maxMB * 1024 * 1024) {
        $('#ev_size_warning').show(); $(this).val('');
        $('#ev_file_name').text('Haga clic para seleccionar un archivo');
    } else {
        $('#ev_size_warning').hide();
        $('#ev_file_name').text(file ? file.name : 'Haga clic para seleccionar un archivo');
    }
});

$('#btnSaveEvidence').click(function() {
    var taskId = $('#evidence_task_id').val();
    var type   = $('#evidence_type').val();
    var formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('type', type);
    if (type === 'url') {
        formData.append('label', $('#ev_label').val());
        formData.append('value', $('#ev_url').val());
    } else {
        var file = $('#ev_file')[0].files[0];
        if (!file) { toastr.error('Seleccione un archivo'); return; }
        formData.append('file', file);
    }
    $(this).text('Guardando...');
    $.ajax({
        url: statusBase + '/' + taskId + '/evidencias', type: 'POST',
        data: formData, processData: false, contentType: false,
        success: function(r) { $('#evidenceModal').modal('hide'); toastr.success(r.success); location.reload(); },
        error:   function(xhr) {
            var errors = xhr.responseJSON?.errors;
            if (errors) $.each(errors, function(k,v) { toastr.error(v); });
            else toastr.error('Error al guardar');
            $('#btnSaveEvidence').text('Guardar');
        }
    });
});

$('body').on('click', '.btn-delete-evidence', function() {
    var evId = $(this).data('id');
    Swal.fire({ title:'¿Eliminar evidencia?', icon:'warning', showCancelButton:true,
        confirmButtonColor:'#d33', confirmButtonText:'Sí', cancelButtonText:'Cancelar'
    }).then(function(r) {
        if (r.value) $.ajax({ url: statusBase + '/evidencias/' + evId, type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function() { location.reload(); }
        });
    });
});

@include('admin.globales.activities.partials.scripts_comentarios')

// ── Comentarios inline ───────────────────────────────────────────────────────
var comentariosBase = "{{ url('admin/globales/activities/tareas') }}";

// Abrir/cerrar sección de comentarios desde el botón en el footer
$(document).on('click', '.btn-toggle-comments', function(e) {
    e.stopPropagation();
    var taskId = $(this).data('task-id');
    $('#comments-' + taskId).slideToggle(200);
    // Scroll al fondo al abrir
    $('#comments-' + taskId).promise().done(function() {
        if ($(this).is(':visible')) {
            var $l = $('#comments-lista-' + taskId);
            $l.scrollTop($l[0].scrollHeight);
            $('#comment-input-' + taskId).focus();
        }
    });
});

$(document).on('keydown', '.comment-input', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); enviarComentario($(this).data('task-id')); }
});
$(document).on('click', '.comment-send', function() {
    enviarComentario($(this).data('task-id'));
});

function enviarComentario(taskId) {
    var $input = $('#comment-input-' + taskId);
    var texto  = $input.val().trim();
    if (!texto) return;
    $.ajax({
        url: comentariosBase + '/' + taskId + '/comentarios', type: 'POST',
        data: { _token: $('meta[name="csrf-token"]').attr('content'), comentario: texto },
        success: function(r) {
            $input.val('');
            var c = r.item;
            var $lista = $('#comments-lista-' + taskId);
            $lista.find('#no-comments-' + taskId).remove();
            $lista.append(
                '<div class="d-flex mb-2" style="gap:8px" data-comment-id="' + c.id + '">'
                + '<div style="width:24px;height:24px;border-radius:50%;background:#dcfce7;color:#166534;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0">' + c.initials + '</div>'
                + '<div style="flex:1;min-width:0">'
                + '<div style="background:#dbeafe;border-radius:0 8px 8px 8px;padding:6px 10px;font-size:.78rem">' + $('<div>').text(c.comentario).html() + '</div>'
                + '<div style="font-size:.65rem;color:#94a3b8;margin-top:2px;display:flex;justify-content:space-between">'
                + '<span>' + c.autor + ' · ' + c.fecha + '</span>'
                + '<a href="javascript:void(0)" class="btn-delete-comment text-danger" data-id="' + c.id + '" style="font-size:.65rem">×</a>'
                + '</div></div></div>'
            );
            // Actualizar contador
            var $card = $('[data-id="' + taskId + '"]').first();
            var $cont = $card.find('.fa-comment-alt').closest('span');
            if ($cont.length) {
                var cur = parseInt($cont.text().trim()) || 0;
                $cont.html('<i class="fa fa-comment-alt mr-1"></i>' + (cur + 1));
            }
        },
        error: function() { toastr.error('Error al guardar'); }
    });
}

$(document).on('click', '.btn-delete-comment', function(e) {
    e.stopPropagation();
    var cId = $(this).data('id');
    var $row = $(this).closest('[data-comment-id]');
    $.ajax({
        url: comentariosBase + '/comentarios/' + cId, type: 'DELETE',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function() { $row.fadeOut(200, function(){ $(this).remove(); }); },
        error: function() { toastr.error('Error'); }
    });
});

// Init paleta
renderPaleta();
</script>

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// ── Drag & Drop entre columnas ────────────────────────────────────────────────
var statuses    = @json(array_keys($columnas));
var statusDone  = {{ $isScrumActivity ? 2 : 2 }}; // siempre 2 = hecho/finalizado
var pendingDrag = null; // {taskId, newStatus} — esperando nota de cierre

$(document).ready(function() {
    initDragDrop();
});

function initDragDrop() {
    document.querySelectorAll('.col-body').forEach(function(col) {
        Sortable.create(col, {
            group:       'tablero',
            animation:   150,
            ghostClass:  'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass:   'sortable-drag',
            handle:      '.drag-handle',
            filter:      '.task-actions, button, a, input, .task-comments-section',
            preventOnFilter: true,
            onEnd: function(evt) {
                var taskId    = $(evt.item).data('id');
                var newColBody = evt.to;
                var newStatus  = parseInt($(newColBody).closest('[data-status]').data('status'));

                // Si no cambió de columna, no hacer nada
                if (evt.from === evt.to) return;

                if (newStatus === statusDone) {
                    // Pedir nota de cierre antes de confirmar
                    pendingDrag = { taskId: taskId, newStatus: newStatus, fromCol: evt.from, toCol: evt.to, item: evt.item };
                    $('#completion_task_id').val(taskId);
                    $('#completion_new_status').val(newStatus);
                    $('#completion_note').val('');
                    $('#completionModal').modal('show');

                    // Revertir visualmente hasta confirmar
                    evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] || null);
                } else {
                    doMoveTask(taskId, newStatus, null);
                }
            }
        });
    });
}

// Al confirmar nota de cierre desde drag
$('#btnConfirmComplete').off('click').on('click', function() {
    var note = $('#completion_note').val().trim();
    if (!note) { toastr.error('El comentario de cierre es obligatorio'); return; }

    var taskId    = $('#completion_task_id').val();
    var newStatus = $('#completion_new_status').val();

    doMoveTask(taskId, newStatus, note);
    $('#completionModal').modal('hide');
    pendingDrag = null;
});

// Cancelar drag que pedía nota de cierre
$('#completionModal').on('hidden.bs.modal', function() {
    pendingDrag = null;
});

// ── FILTRO TAREAS VENCIDAS ────────────────────────────────────────────────────
var filtroActivo = null;

function aplicarFiltroVencidas(taskIds) {
    if (!taskIds || taskIds.length === 0) {
        limpiarFiltro();
        return;
    }
    
    filtroActivo = taskIds.split(',').map(id => parseInt(id));
    
    // Ocultar todas las tareas que NO están en la lista
    $('.task-card').each(function() {
        var taskId = $(this).data('id');
        if (filtroActivo.includes(parseInt(taskId))) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    // Mostrar badge de filtro activo
    $('#filtroActivoContainer').show();
}

function limpiarFiltro() {
    filtroActivo = null;
    
    // Mostrar todas las tareas
    $('.task-card').show();
    
    // Ocultar badge de filtro
    $('#filtroActivoContainer').hide();
}

// Evento click en alerta de vencidas
$('#alertVencidas').on('click', function(e) {
    e.preventDefault();
    var taskIds = $(this).data('task-ids');
    aplicarFiltroVencidas(taskIds);
    // Scroll al tablero
    $('html, body').animate({ scrollTop: $('#vistaEstado').offset().top - 100 }, 300);
});

// Evento click en alerta de por vencer
$('#alertPorVencer').on('click', function(e) {
    e.preventDefault();
    var taskIds = $(this).data('task-ids');
    aplicarFiltroVencidas(taskIds);
    // Scroll al tablero
    $('html, body').animate({ scrollTop: $('#vistaEstado').offset().top - 100 }, 300);
});

// Botón limpiar filtro
$('#btnLimpiarFiltro').on('click', function(e) {
    e.preventDefault();
    limpiarFiltro();
});

// ══ REUNIONES ════════════════════════════════════════════════════════════════
(function() {
    var _reunionesData = [];
    var _filtroActivo  = 'todas';
    var _reunionesUrl  = "{{ url('admin/globales/activities') }}/" + activityId + '/reuniones';

    var _statusLabels = {
        0: { label: 'Pendiente',   cls: 'badge-warning'   },
        1: { label: 'En Progreso', cls: 'badge-primary'   },
        3: { label: 'En Revisión', cls: 'badge-secondary' },
        2: { label: 'Finalizada',  cls: 'badge-success'   },
    };

    $('#btnVerReuniones').on('click', function() {
        $('#reunionesSubtitulo').text('');
        $('#reunionesBody').html('<tr id="reunionesLoading"><td colspan="7" class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin mr-2"></i>Cargando...</td></tr>');
        $('#modalReuniones').modal('show');
        $.getJSON(_reunionesUrl, function(data) {
            _reunionesData = data;
            $('#reunionesSubtitulo').text(data.length + ' reunión(es) registrada(s)');
            renderReuniones(_filtroActivo);
        }).fail(function() {
            $('#reunionesBody').html('<tr><td colspan="7" class="text-center text-danger py-3"><i class="fa fa-exclamation-triangle mr-1"></i>Error al cargar reuniones.</td></tr>');
        });
    });

    function renderReuniones(filtro) {
        var data = _reunionesData.slice();
        if (filtro === 'pendientes')  data = data.filter(function(r) { return r.status !== 2; });
        if (filtro === 'finalizadas') data = data.filter(function(r) { return r.status === 2; });

        $('#reunionesCount').text(data.length);
        var tbody = $('#reunionesBody').empty();

        if (!data.length) {
            tbody.append('<tr><td colspan="7" class="text-center text-muted py-4"><i class="fa fa-inbox mr-1"></i>Sin reuniones' + (filtro !== 'todas' ? ' en este estado' : '') + '.</td></tr>');
            return;
        }

        data.forEach(function(r) {
            var st    = _statusLabels[r.status] || _statusLabels[0];
            var fecha = r.fecha_inicio || '—';
            var acta  = '';

            if (r.evidencias && r.evidencias.length) {
                r.evidencias.forEach(function(e) {
                    if (e.es_pdf) {
                        acta += '<button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btnVerActa mr-1 mb-1" ' +
                            'data-url="' + e.url + '" data-titulo="' + (e.label || r.title) + '" style="font-size:.7rem">' +
                            '<i class="fa fa-file-pdf mr-1"></i>' + (e.label || 'Acta') + '</button>';
                    } else {
                        acta += '<a href="' + e.url + '" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2 mr-1 mb-1" style="font-size:.7rem">' +
                            '<i class="fa fa-link mr-1"></i>' + (e.label || 'Adjunto') + '</a>';
                    }
                });
            } else {
                acta = '<button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btnSubirActa" data-task-id="' + r.id + '" style="font-size:.7rem">'
                     + '<i class="fa fa-upload mr-1"></i>Subir acta</button>';
            }

            tbody.append(
                '<tr>' +
                '<td class="font-weight-bold" style="font-size:.83rem;vertical-align:middle">' + r.title + '</td>' +
                '<td style="font-size:.8rem;color:#6c757d;vertical-align:middle">' + (r.details || '—') + '</td>' +
                '<td style="vertical-align:middle">' + (r.etiqueta ? '<span class="badge badge-info" style="font-size:.68rem">' + r.etiqueta + '</span>' : '<span class="text-muted" style="font-size:.75rem">—</span>') + '</td>' +
                '<td style="font-size:.8rem;vertical-align:middle">' + r.responsable + '</td>' +
                '<td class="text-center" style="vertical-align:middle"><span class="badge ' + st.cls + '" style="font-size:.68rem">' + st.label + '</span></td>' +
                '<td class="text-center" style="font-size:.78rem;vertical-align:middle">' + fecha + '</td>' +
                '<td class="text-center" style="vertical-align:middle">' + acta + '</td>' +
                '</tr>'
            );
        });
    }

    $('#filtroTodas').on('click', function() {
        _filtroActivo = 'todas';
        $(this).addClass('btn-dark active').removeClass('btn-outline-secondary');
        $('#filtroPendientes,#filtroFinalizadas').addClass('btn-outline-secondary').removeClass('btn-warning btn-success active');
        renderReuniones('todas');
    });
    $('#filtroPendientes').on('click', function() {
        _filtroActivo = 'pendientes';
        $(this).addClass('btn-warning active').removeClass('btn-outline-secondary');
        $('#filtroTodas,#filtroFinalizadas').addClass('btn-outline-secondary').removeClass('btn-dark btn-success active');
        renderReuniones('pendientes');
    });
    $('#filtroFinalizadas').on('click', function() {
        _filtroActivo = 'finalizadas';
        $(this).addClass('btn-success active').removeClass('btn-outline-secondary');
        $('#filtroTodas,#filtroPendientes').addClass('btn-outline-secondary').removeClass('btn-dark btn-warning active');
        renderReuniones('finalizadas');
    });

    $(document).on('click', '.btnSubirActa', function() {
        $('#evidence_task_id').val($(this).data('task-id'));
        $('#modalReuniones').modal('hide');
        $('#evidenceModal').modal('show');
    });

    $('#evidenceModal').on('hidden.bs.modal', function() {
        if ($('#modalReuniones').data('bs.modal')) {
            $('#modalReuniones').modal('show');
            $.getJSON(_reunionesUrl, function(data) {
                _reunionesData = data;
                renderReuniones(_filtroActivo);
            });
        }
    });

    $(document).on('click', '.btnVerActa', function() {
        var url    = $(this).data('url');
        var titulo = $(this).data('titulo');
        $('#actaPdfTitulo').text(titulo);
        $('#actaPdfDescargar').attr('href', url);
        $('#actaPdfFrame').attr('src', url);
        $('#modalReuniones').modal('hide');
        $('#modalActaPdf').modal('show');
    });

    $('#modalActaPdf').on('hidden.bs.modal', function() {
        $('#actaPdfFrame').attr('src', '');
        $('#modalReuniones').modal('show');
    });
})();
// ══ FIN REUNIONES ════════════════════════════════════════════════════════════
</script>
@endpush
