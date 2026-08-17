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
.col-body { transition: background .15s; min-height: 150px; padding-bottom: 25px; }
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
            @endhasanyrole
            <button class="btn btn-sm font-weight-bold" style="background:rgba(255,255,255,.9);color:#312e81;border:none"
                    id="btnVerSeguimientos">
                <i class="fa fa-folder-open mr-1"></i>Seguimientos
            </button>
            <button class="btn btn-sm font-weight-bold" style="background:rgba(255,255,255,.85);color:#1e3a5f;border:none"
                    id="btnVerReuniones">
                <i class="fa fa-users mr-1"></i>Reuniones
            </button>
            <button class="btn btn-sm font-weight-bold" style="background:rgba(255,255,255,.8);color:#78350f;border:none"
                    id="btnVerDocumentos">
                <i class="fa fa-file-alt mr-1"></i>Documentos
            </button>
            @hasanyrole('Administrador|Gestor de Actividades')
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
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap" style="gap:10px">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('globales.activities.index') }}">Actividades</a></li>
            <li class="breadcrumb-item active">{{ $activity->name }}</li>
        </ol>
    </nav>
    <div class="d-flex align-items-center flex-wrap" style="gap:10px">
        {{-- Toggle Visibilidad Transparente: Mis Tareas vs Todo el Equipo --}}
        <div class="btn-group btn-group-toggle" data-toggle="buttons" id="toggleFiltroAsignacion">
            <label class="btn btn-sm btn-info active font-weight-bold" id="btnFiltroMisTareas" style="border-radius:20px 0 0 20px;padding:5px 14px;cursor:pointer">
                <input type="radio" name="filtro_scope" value="mis_tareas" checked>
                <i class="fa fa-user mr-1"></i> Mis Tareas
            </label>
            <label class="btn btn-sm btn-outline-info font-weight-bold" id="btnFiltroEquipo" style="border-radius:0 20px 20px 0;padding:5px 14px;cursor:pointer">
                <input type="radio" name="filtro_scope" value="equipo">
                <i class="fa fa-users mr-1"></i> Ver Todo el Equipo
            </label>
        </div>

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
</div>

{{-- Alerta tareas vencidas/por vencer --}}
@php
    $expedientesVencidos = $activity->tasks->filter(fn($t) => $t->es_seguimiento && $t->status !== 2 && $t->fecha_vencimiento && \Carbon\Carbon::parse($t->fecha_vencimiento)->isPast());
    $vencidas  = $activity->tasks->filter(fn($t) => !$t->es_seguimiento && $t->status !== 2 && $t->fecha_vencimiento && \Carbon\Carbon::parse($t->fecha_vencimiento)->isPast());
    $porVencer = $activity->tasks->filter(fn($t) => $t->status !== 2 && $t->fecha_vencimiento && !\Carbon\Carbon::parse($t->fecha_vencimiento)->isPast() && \Carbon\Carbon::parse($t->fecha_vencimiento)->diffInDays(now()) <= 3);
@endphp
@if($expedientesVencidos->count() > 0)
<div class="alert alert-danger d-flex align-items-center mb-3 py-2.5 shadow-sm" style="border-radius:10px; border-left: 5px solid #dc2626 !important; background:#fef2f2; cursor:pointer;" 
     id="btnAbrirSeguimientosAlertados">
    <i class="fa fa-exclamation-triangle fa-lg mr-3 text-danger"></i>
    <div style="flex:1">
        <strong class="text-danger" style="font-size:0.92rem">⚠️ {{ $expedientesVencidos->count() }} Expediente(s) con Fecha de Alerta Vencida (Pendientes de Respuesta)</strong>
        <div class="small text-dark mt-0.5">
            — {{ $expedientesVencidos->map(fn($e) => ($e->nro_expediente ? 'EXP: '.$e->nro_expediente.' ('.$e->title.')' : $e->title))->implode(', ') }}
        </div>
    </div>
    <span class="badge badge-danger font-weight-bold ml-2 px-3 py-1" style="font-size:.78rem; border-radius:12px;">
        <i class="fa fa-search mr-1"></i>Ver Expedientes
    </span>
</div>
@endif
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

{{-- Buscador de tareas --}}
<div class="mb-3">
    <select id="buscadorTareas" style="width:100%" multiple="multiple"></select>
</div>

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
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;overflow:hidden">
        <div style="font-size:1rem;font-weight:700;margin-bottom:8px;color:#1e293b">Cronograma de tareas</div>
        <div id="gantt_chart" style="width:100%;min-height:300px"></div>
        <div id="timelineEmpty" style="display:none;text-align:center;color:#94a3b8;font-size:.9rem;padding:32px">
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
@include('admin.globales.activities.partials.modal_documentos')
@include('admin.globales.activities.partials.modal_seguimientos')
@include('admin.globales.activities.partials.modal_editor_acta_mecip')
@include('admin.globales.activities.partials.modal_qr_acta')

@include('admin.planificacion.peis.peis.partials.chat_drawer')
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
        $('#gantt_chart').hide();
        $('#timelineEmpty').show();
        return;
    }
    $('#timelineEmpty').hide();
    $('#gantt_chart').show();

    google.charts.load('current', { packages: ['gantt'] });
    google.charts.setOnLoadCallback(function() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'ID');
        data.addColumn('string', 'Tarea');
        data.addColumn('string', 'Responsable');
        data.addColumn('date',   'Inicio');
        data.addColumn('date',   'Fin');
        data.addColumn('number', 'Duración (días)');
        data.addColumn('number', '% Completado');
        data.addColumn('string', 'Dependencias');

        ganttTasks.forEach(function(t) {
            var start = t.start ? new Date(t.start) : null;
            var end   = t.end   ? new Date(t.end)   : null;
            if (!start && end) { start = new Date(end); start.setDate(start.getDate() - 1); }
            if (!end && start) { end   = new Date(start); end.setDate(end.getDate() + 1); }
            data.addRow([ t.id, t.name, t.resource, start, end, null, 0, null ]);
        });

        var rowHeight  = 42;
        var chartH     = Math.max(200, ganttTasks.length * rowHeight + 80);
        $('#gantt_chart').css('height', chartH + 'px');

        var chart = new google.visualization.Gantt(document.getElementById('gantt_chart'));
        chart.draw(data, {
            height:          chartH,
            gantt: {
                trackHeight:     rowHeight,
                labelStyle:      { fontName: 'inherit', fontSize: 12 },
                criticalPathEnabled: false,
                arrow:           { angle: 100, width: 0, color: 'transparent', radius: 0 },
            }
        });
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


// ── Select2 dependencia destino ──────────────────────────────────────────
function initDestinoDependenciaSelect(selectedVal) {
    var $select = $('#task_destino_dependencia').empty();
    if ($select.select2) {
        $select.select2({
            placeholder: 'Seleccioná o escribí la dependencia destino...',
            allowClear: true,
            tags: true,
            dropdownParent: $('#tareaModal'),
            ajax: {
                url: "{{ route('globales.get-dependencies') }}",
                dataType: 'json', delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return { id: item.dependency, text: item.dependency };
                        })
                    };
                }
            }
        });
    }
    if (selectedVal) {
        $select.append(new Option(selectedVal, selectedVal, true, true));
        if ($select.select2) {
            $select.trigger('change');
        }
    }
}

// ── Reset Formulario Tarea ──────────────────────────────────────────
function resetFormularioTarea() {
    $('#tareaForm')[0].reset();
    $('#task_id').val('');
    $('#task_es_seguimiento').prop('checked', false);
    $('#containerCamposSeguimiento').hide();
    $('#task_etiqueta').prop('readonly', false).removeClass('bg-light');
    $('#helpEtiquetaSeguimiento').hide();
    $('#etiquetasSugeridas').show();
    $('#boxEtiquetaInput').removeClass('col-md-12').addClass('col-md-7');
    $('#boxColorPicker').show();
    $('#boxOpcionesAdicionales').show();
    $('#lblFechaVencimiento').html('<i class="fa fa-clock mr-1 text-warning"></i>Fecha de vencimiento');
    $('#helpFechaVencimiento').text('Opcional — genera alertas visuales al acercarse');
    colorSeleccionado = '#6b7280';
    renderPaleta();
    initResponsableSelect(null, null);
    initDestinoDependenciaSelect(null);
    cargarEtiquetasExistentes();
}

// ── Switch Seguimiento Event Handler ────────────────────────────────────────
$('#task_es_seguimiento').on('change', function() {
    if ($(this).is(':checked')) {
        $('#containerCamposSeguimiento').slideDown(150);
        $('#lblFechaVencimiento').html('<i class="fa fa-bell text-warning mr-1"></i>Fecha de Alerta (Respuesta)');
        $('#helpFechaVencimiento').text('Fecha estimada para recibir respuesta y consultar el trámite');
        
        // Bloquear campo Etiqueta en SEGUIMIENTO y ocultar campos irrelevantes
        $('#task_etiqueta').val('SEGUIMIENTO').prop('readonly', true).addClass('bg-light');
        $('#helpEtiquetaSeguimiento').show();
        $('#etiquetasSugeridas').hide();
        $('#boxEtiquetaInput').removeClass('col-md-7').addClass('col-md-12');
        $('#boxColorPicker').hide();
        $('#boxOpcionesAdicionales').slideUp(150);

        var hoy = new Date().toISOString().split('T')[0];
        if (!$('#task_fecha_inicio').val()) {
            $('#task_fecha_inicio').val(hoy);
        }
        colorSeleccionado = '#4f46e5';
        renderPaleta();
    } else {
        $('#containerCamposSeguimiento').slideUp(150);
        $('#lblFechaVencimiento').html('<i class="fa fa-clock mr-1 text-warning"></i>Fecha de vencimiento');
        $('#helpFechaVencimiento').text('Opcional — genera alertas visuales al acercarse');

        // Restablecer campos
        $('#task_etiqueta').prop('readonly', false).removeClass('bg-light');
        $('#helpEtiquetaSeguimiento').hide();
        $('#etiquetasSugeridas').show();
        $('#boxEtiquetaInput').removeClass('col-md-12').addClass('col-md-7');
        $('#boxColorPicker').show();
        $('#boxOpcionesAdicionales').slideDown(150);
    }
});

// ── Nueva tarea ───────────────────────────────────────────────────────────────
$('#btnNuevaTarea').click(function() {
    $('#tareaHeading').text('Nueva Tarea');
    resetFormularioTarea();
    $('#tareaModal').modal('show');
});

// ── Nuevo Seguimiento de Expediente ────────────────────────────────────────
$(document).on('click', '#btnNuevoSeguimiento, #btnCrearSeguimientoModal', function() {
    if ($('#modalSeguimientos').is(':visible')) {
        $('#modalSeguimientos').modal('hide');
    }
    $('#tareaHeading').text('Nuevo Seguimiento de Expediente');
    resetFormularioTarea();
    $('#task_es_seguimiento').prop('checked', true).trigger('change');
    
    var hoy = new Date().toISOString().split('T')[0];
    $('#task_fecha_inicio').val(hoy);
    if (!$('#task_etiqueta').val()) {
        $('#task_etiqueta').val('SEGUIMIENTO');
    }
    colorSeleccionado = '#4f46e5';
    renderPaleta();
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
    if ($('#modalSeguimientos').is(':visible')) {
        $('#modalSeguimientos').modal('hide');
    }
    $.get(statusBase + '/' + taskId + '/detalle', function(res) {
        if (res?.ok && res?.data) {
            var task = res.data;
            $('#tareaHeading').text('Editar Tarea');
            resetFormularioTarea();
            $('#task_id').val(taskId);
            $('#task_title').val(task.title);
            $('#task_details').val(task.details || '');
            $('#task_etiqueta').val(task.etiqueta || '');
            $('#task_fecha_inicio').val(task.fecha_inicio_raw || '');
            $('#task_fecha_vencimiento').val(task.fecha_vencimiento_raw || '');
            $('#task_status').val(task.status);
            $('#task_es_reunion').prop('checked', task.es_reunion == 1);
            $('#task_es_documento').prop('checked', task.es_documento == 1);
            $('#task_es_seguimiento').prop('checked', task.es_seguimiento == 1).trigger('change');
            if (task.es_seguimiento == 1) {
                $('#task_nro_expediente').val(task.nro_expediente || '');
                initDestinoDependenciaSelect(task.destino_dependencia || '');
            } else {
                initDestinoDependenciaSelect(null);
            }
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
        resetFormularioTarea();
        $('#task_id').val(taskId);
        $('#task_title').val($card.find('.task-title').clone().find('.fa-check-circle').remove().end().text().trim());
        $('#task_details').val($card.find('.task-desc').text().trim());
        $('#task_etiqueta').val($card.find('.task-etiqueta').text().trim());
        $('#task_fecha_inicio').val($card.data('fecha-inicio') || '');
        $('#task_es_reunion').prop('checked', false);
        $('#task_es_documento').prop('checked', false);
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

// ── Buscador de tareas con Select2 ───────────────────────────────────────────
(function() {
    // Recolectar todas las tareas del DOM
    var tareas = [];
    $('.task-card').each(function() {
        var id    = $(this).data('id');
        var title = $(this).find('.task-title').clone().find('i').remove().end().text().trim();
        if (id && title) tareas.push({ id: id, text: title });
    });

    $('#buscadorTareas').select2({
        placeholder: 'Buscar tareas...',
        allowClear: true,
        data: tareas,
        language: {
            noResults: function() { return 'Sin resultados'; },
            searching: function() { return 'Buscando...'; }
        }
    });

    $('#buscadorTareas').on('change', function() {
        var selected = $(this).val(); // array de ids o null
        if (!selected || !selected.length) {
            $('.task-card').show();
            $('#filtroActivoContainer').hide();
            return;
        }
        selected = selected.map(String);
        $('.task-card').each(function() {
            var id = String($(this).data('id'));
            $(this).toggle(selected.includes(id));
        });
        $('#filtroActivoContainer').show();
    });

    // Limpiar filtro también resetea el select2
    $('#btnLimpiarFiltro').on('click', function(e) {
        e.preventDefault();
        $('#buscadorTareas').val(null).trigger('change');
    });
})();
</script>

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// ── Filter Scope: Mis Tareas vs Todo el Equipo ────────────────────────────────
var currentUserId = {{ auth()->id() }};
var filtroScope   = 'mis_tareas'; // Por defecto: Mis Tareas

function aplicarFiltroScope() {
    if (filtroScope === 'mis_tareas') {
        $('.task-card').each(function() {
            var assignedTo = $(this).data('assigned-to');
            if (parseInt(assignedTo) === currentUserId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    } else {
        $('.task-card').show();
    }
    actualizarContadoresColumnas();
}

function actualizarContadoresColumnas() {
    $('.col-body').each(function() {
        var visibleCount = $(this).find('.task-card:visible').length;
        $(this).closest('[data-status], .col-md').find('.badge-light').text(visibleCount);
    });
}

$('#btnFiltroMisTareas').on('click', function() {
    filtroScope = 'mis_tareas';
    $('#btnFiltroMisTareas').addClass('btn-info active').removeClass('btn-outline-info');
    $('#btnFiltroEquipo').addClass('btn-outline-info').removeClass('btn-info active');
    aplicarFiltroScope();
});

$('#btnFiltroEquipo').on('click', function() {
    filtroScope = 'equipo';
    $('#btnFiltroEquipo').addClass('btn-info active').removeClass('btn-outline-info');
    $('#btnFiltroMisTareas').addClass('btn-outline-info').removeClass('btn-info active');
    aplicarFiltroScope();
});

$(document).ready(function() {
    initDragDrop();

    // Si hay hash en la URL (referencia desde el chat), mostrar todo el equipo y scrollear
    if (window.location.hash) {
        const elementId = window.location.hash.substring(1);
        filtroScope = 'equipo';
        $('#btnFiltroEquipo').addClass('btn-info active').removeClass('btn-outline-info');
        $('#btnFiltroMisTareas').addClass('btn-outline-info').removeClass('btn-info active');
        aplicarFiltroScope();

        setTimeout(function() {
            const element = document.getElementById(elementId);
            if (!element) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: 'Tarea no encontrada', text: 'La tarea referenciada fue eliminada o modificada.', toast: true, position: 'top-end', timer: 3500, showConfirmButton: false });
                }
                return;
            }
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            element.style.transition = 'all 0.4s ease';
            element.style.boxShadow = '0 0 0 3px #22c55e, 0 0 20px rgba(34,197,94,0.5)';
            element.style.borderRadius = '8px';
            element.style.outline = '2px solid #16a34a';
            setTimeout(function() { element.style.boxShadow = 'none'; element.style.outline = 'none'; }, 3500);
        }, 400);
    } else {
        aplicarFiltroScope();
    }
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
    var _dt            = null;

    var _statusLabels = {
        0: { label: 'Pendiente',   cls: 'badge-warning'   },
        1: { label: 'En Progreso', cls: 'badge-primary'   },
        3: { label: 'En Revisión', cls: 'badge-secondary' },
        2: { label: 'Finalizada',  cls: 'badge-success'   },
    };

    function buildActa(r) {
        var acta = '<div class="d-flex align-items-center justify-content-center flex-wrap" style="gap:3px">';
        
        if (r.has_acta) {
            var labelText = r.acta_participantes_count > 0 
                ? '<i class="fa fa-file-signature mr-1"></i>Acta (' + r.acta_participantes_count + ' <i class="fa fa-users" style="font-size:0.65rem"></i>)' 
                : '<i class="fa fa-file-signature mr-1"></i>Acta MECIP';
            
            acta += '<button type="button" class="btn btn-sm btn-primary py-0 px-2 btnRedactarActaMecip" data-task-id="' + r.id + '" title="Editar Acta MECIP en línea" style="font-size:.7rem">' +
                    labelText + '</button>';

            acta += '<button type="button" class="btn btn-sm btn-outline-success py-0 px-2 btnVerQrDirecto" data-task-id="' + r.id + '" title="Ver Código QR de asistencia" style="font-size:.7rem">' +
                    '<i class="fa fa-qrcode"></i></button>';
        } else {
            acta += '<button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btnRedactarActaMecip" data-task-id="' + r.id + '" title="Redactar Acta MECIP en línea" style="font-size:.7rem">' +
                    '<i class="fa fa-file-signature mr-1"></i>+ Acta MECIP</button>';
        }

        if (r.evidencias && r.evidencias.length) {
            r.evidencias.forEach(function(e) {
                if (e.es_pdf) {
                    acta += '<button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btnVerActa" ' +
                        'data-url="' + e.url + '" data-titulo="' + (e.label || r.title) + '" title="Ver PDF Adjunto" style="font-size:.7rem">' +
                        '<i class="fa fa-file-pdf"></i></button>';
                } else {
                    acta += '<a href="' + e.url + '" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Ver Enlace Adjunto" style="font-size:.7rem">' +
                        '<i class="fa fa-link"></i></a>';
                }
            });
        } else if (!r.has_acta) {
            acta += '<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 btnSubirActa" data-task-id="' + r.id + '" title="Adjuntar archivo o evidencia" style="font-size:.7rem">' +
                   '<i class="fa fa-paperclip"></i></button>';
        }

        acta += '</div>';
        return acta;
    }

    function initDt(data) {
        if (_dt) {
            _dt.clear().rows.add(data).draw();
            return;
        }
        _dt = $('#tblReuniones').DataTable({
            data: data,
            scrollY: '55vh',
            scrollCollapse: true,
            paging: false,
            info: false,
            searching: true,
            ordering: true,
            language: {
                search: 'Buscar:',
                zeroRecords: 'Sin reuniones en este estado.',
                emptyTable: 'No hay reuniones registradas.',
            },
            columns: [
                { data: 'title',      title: 'Título',
                  render: function(d) { return '<span class="font-weight-bold">' + d + '</span>'; } },
                { data: 'details',    title: 'Descripción',
                  render: function(d) { return d ? '<span class="text-muted" style="font-size:.8rem">' + d + '</span>' : '<span class="text-muted">—</span>'; } },
                { data: 'etiqueta',   title: 'Etiqueta', width: '100px',
                  render: function(d) { return d ? '<span class="badge badge-info" style="font-size:.68rem">' + d + '</span>' : '<span class="text-muted">—</span>'; } },
                { data: 'responsable',title: 'Responsable', width: '140px',
                  render: function(d) { return '<span style="font-size:.8rem">' + (d || '—') + '</span>'; } },
                { data: 'status',     title: 'Estado', width: '90px', className: 'text-center',
                  render: function(d) { var s = _statusLabels[d] || _statusLabels[0]; return '<span class="badge ' + s.cls + '" style="font-size:.68rem">' + s.label + '</span>'; } },
                { data: 'fecha_inicio', title: 'Fecha', width: '90px', className: 'text-center',
                  render: function(d) { return '<span style="font-size:.78rem">' + (d || '—') + '</span>'; } },
                { data: null,         title: 'Acta MECIP / Evidencias', width: '180px', className: 'text-center', orderable: false,
                  render: function(d, t, r) { return buildActa(r); } },
            ],
            dom: '<"d-flex align-items-center mb-2"f>t',
            drawCallback: function() {
                $('#reunionesCount').text(this.api().rows({ search: 'applied' }).count());
            }
        });
    }

    function filtrarDt(filtro) {
        var data = _reunionesData.slice();
        if (filtro === 'pendientes')  data = data.filter(function(r) { return r.status !== 2; });
        if (filtro === 'finalizadas') data = data.filter(function(r) { return r.status === 2; });
        if (_dt) {
            _dt.clear().rows.add(data).draw();
        } else {
            initDt(data);
        }
        $('#reunionesCount').text(data.length);
    }

    $('#btnVerReuniones').on('click', function() {
        $('#reunionesSubtitulo').text('');
        $('#modalReuniones').modal('show');
    });

    $('#modalReuniones').on('shown.bs.modal', function() {
        $.getJSON(_reunionesUrl, function(data) {
            _reunionesData = data;
            $('#reunionesSubtitulo').text(data.length + ' reunión(es) registrada(s)');
            filtrarDt(_filtroActivo);
        }).fail(function() {
            $('#reunionesBody').html('<tr><td colspan="7" class="text-center text-danger py-3"><i class="fa fa-exclamation-triangle mr-1"></i>Error al cargar reuniones.</td></tr>');
        });
    });

    $('#filtroTodas').on('click', function() {
        _filtroActivo = 'todas';
        $(this).addClass('btn-dark active').removeClass('btn-outline-secondary');
        $('#filtroPendientes,#filtroFinalizadas').addClass('btn-outline-secondary').removeClass('btn-warning btn-success active');
        filtrarDt('todas');
    });
    $('#filtroPendientes').on('click', function() {
        _filtroActivo = 'pendientes';
        $(this).addClass('btn-warning active').removeClass('btn-outline-secondary');
        $('#filtroTodas,#filtroFinalizadas').addClass('btn-outline-secondary').removeClass('btn-dark btn-success active');
        filtrarDt('pendientes');
    });
    $('#filtroFinalizadas').on('click', function() {
        _filtroActivo = 'finalizadas';
        $(this).addClass('btn-success active').removeClass('btn-outline-secondary');
        $('#filtroTodas,#filtroPendientes').addClass('btn-outline-secondary').removeClass('btn-dark btn-warning active');
        filtrarDt('finalizadas');
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
                filtrarDt(_filtroActivo);
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

<script>
// ══ DOCUMENTOS ═══════════════════════════════════════════════════════════════
(function() {
    var _documentosData  = [];
    var _docFiltroActivo = 'todos';
    var _documentosUrl   = "{{ url('admin/globales/activities') }}/" + activityId + '/documentos';

    var _docStatusLabels = {
        0: { label: 'Pendiente',   cls: 'badge-warning'   },
        1: { label: 'En Progreso', cls: 'badge-primary'   },
        3: { label: 'En Revisión', cls: 'badge-secondary' },
        2: { label: 'Finalizado',  cls: 'badge-success'   },
    };

    $('#btnVerDocumentos').on('click', function() {
        $('#documentosSubtitulo').text('');
        $('#documentosVacio').hide();
        $('#documentosTablaWrap').show();
        $('#documentosBody').html('<tr id="documentosLoading"><td colspan="7" class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin mr-2"></i>Cargando documentos...</td></tr>');
        $('#modalDocumentos').modal('show');
        $.getJSON(_documentosUrl, function(data) {
            _documentosData = data;
            $('#documentosSubtitulo').text(data.length + ' documento(s) registrado(s)');
            renderDocumentos(_docFiltroActivo);
        }).fail(function() {
            $('#documentosBody').html('<tr><td colspan="7" class="text-center text-danger py-3"><i class="fa fa-exclamation-triangle mr-1"></i>Error al cargar documentos.</td></tr>');
        });
    });

    function renderDocumentos(filtro) {
        var data = _documentosData.slice();
        if (filtro === 'pendientes')  data = data.filter(function(d) { return d.status !== 2; });
        if (filtro === 'finalizados') data = data.filter(function(d) { return d.status === 2; });

        $('#documentosCount').text(data.length);
        var tbody = $('#documentosBody').empty();

        if (!data.length) {
            if (_documentosData.length === 0) {
                $('#documentosTablaWrap').hide();
                $('#documentosVacio').show();
            } else {
                tbody.append('<tr><td colspan="7" class="text-center text-muted py-4"><i class="fa fa-filter mr-1"></i>Sin documentos en este estado.</td></tr>');
            }
            return;
        }

        $('#documentosVacio').hide();
        $('#documentosTablaWrap').show();

        data.forEach(function(d) {
            var st      = _docStatusLabels[d.status] || _docStatusLabels[0];
            var fecha   = d.fecha_inicio || '—';
            var etiq    = d.etiqueta
                ? '<span class="badge badge-warning" style="font-size:.7rem">' + $('<div>').text(d.etiqueta).html() + '</span>'
                : '<span class="text-muted small">—</span>';

            var evidHtml = '';
            if (d.evidencias && d.evidencias.length) {
                d.evidencias.forEach(function(e) {
                    if (e.es_pdf) {
                        evidHtml += '<a href="#" class="btn btn-xs btn-outline-danger mr-1 mb-1 btnVerActa" style="font-size:.68rem;padding:1px 6px" '
                            + 'data-url="' + e.url + '" data-titulo="' + $('<div>').text(d.title).html() + '">'
                            + '<i class="fa fa-file-pdf mr-1"></i>PDF</a>';
                    } else if (e.type === 'url') {
                        evidHtml += '<a href="' + e.url + '" target="_blank" class="btn btn-xs btn-outline-info mr-1 mb-1" style="font-size:.68rem;padding:1px 6px">'
                            + '<i class="fa fa-link mr-1"></i>' + $('<div>').text(e.label || 'Enlace').html() + '</a>';
                    } else {
                        evidHtml += '<a href="' + e.url + '" target="_blank" class="btn btn-xs btn-outline-secondary mr-1 mb-1" style="font-size:.68rem;padding:1px 6px">'
                            + '<i class="fa fa-paperclip mr-1"></i>Archivo</a>';
                    }
                });
            } else {
                evidHtml = '<span class="text-muted small" style="font-size:.72rem">Sin evidencias</span>';
            }

            tbody.append(
                '<tr>'
                + '<td class="font-weight-bold" style="font-size:.82rem">' + $('<div>').text(d.title).html() + '</td>'
                + '<td class="text-muted" style="font-size:.8rem;max-width:220px">' + ($('<div>').text(d.details || '—').html()) + '</td>'
                + '<td class="text-center">' + etiq + '</td>'
                + '<td style="font-size:.8rem">' + $('<div>').text(d.responsable).html() + '</td>'
                + '<td class="text-center"><span class="badge ' + st.cls + '" style="font-size:.7rem">' + st.label + '</span></td>'
                + '<td class="text-center" style="font-size:.78rem">' + fecha + '</td>'
                + '<td class="text-center">' + evidHtml + '</td>'
                + '</tr>'
            );
        });
    }

    $('#docFiltroTodos').on('click', function() {
        _docFiltroActivo = 'todos';
        $(this).addClass('btn-warning active').removeClass('btn-outline-warning');
        $('#docFiltroPendientes,#docFiltroFinalizados').removeClass('btn-warning btn-success active').addClass('btn-outline-warning btn-outline-success');
        renderDocumentos('todos');
    });
    $('#docFiltroPendientes').on('click', function() {
        _docFiltroActivo = 'pendientes';
        $(this).addClass('btn-warning active').removeClass('btn-outline-warning');
        $('#docFiltroTodos,#docFiltroFinalizados').removeClass('btn-warning btn-success active').addClass('btn-outline-warning btn-outline-success');
        renderDocumentos('pendientes');
    });
    $('#docFiltroFinalizados').on('click', function() {
        _docFiltroActivo = 'finalizados';
        $(this).addClass('btn-success active').removeClass('btn-outline-success');
        $('#docFiltroTodos,#docFiltroPendientes').removeClass('btn-warning btn-success active').addClass('btn-outline-warning btn-outline-success');
        renderDocumentos('finalizados');
    });

    $(document).on('click', '#modalDocumentos .btnVerActa', function() {
        var url    = $(this).data('url');
        var titulo = $(this).data('titulo');
        $('#actaPdfTitulo').text(titulo);
        $('#actaPdfDescargar').attr('href', url);
        $('#actaPdfFrame').attr('src', url);
        $('#modalDocumentos').modal('hide');
        $('#modalActaPdf').modal('show');
        $('#modalActaPdf').one('hidden.bs.modal', function() {
            $('#actaPdfFrame').attr('src', '');
            $('#modalDocumentos').modal('show');
        });
    });
})();
</script>

<script>
// ══ SEGUIMIENTO DE EXPEDIENTES ════════════════════════════════════════════════
(function() {
    var _seguimientosData = [];
    var _seguimientosUrl  = "{{ url('admin/globales/activities') }}/" + activityId + '/seguimientos';
    var _dtSeguimientos   = null;
    var _filtroSeguimientoActivo = 'todos';

    function buildStatusBadgeSeguimiento(status, esVencida) {
        if (status === 2) {
            return '<span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Finalizado / Con Respuesta</span>';
        }
        if (esVencida) {
            return '<span class="badge badge-danger px-2 py-1"><i class="fa fa-exclamation-triangle mr-1"></i>⚠️ Alerta Vencida</span>';
        }
        if (status === 1) {
            return '<span class="badge badge-primary px-2 py-1"><i class="fa fa-sync fa-spin mr-1"></i>En Respuesta</span>';
        }
        if (status === 3) {
            return '<span class="badge badge-purple text-white px-2 py-1"><i class="fa fa-eye mr-1"></i>En Revisión</span>';
        }
        return '<span class="badge badge-warning text-dark px-2 py-1"><i class="fa fa-clock mr-1"></i>Pendiente</span>';
    }

    function buildAccionesSeguimiento(row) {
        var html = '<div class="d-flex align-items-center justify-content-center" style="gap:4px">';
        html += '<button type="button" class="btn btn-circle btn-warning text-dark editTaskBtn" data-id="' + row.id + '" title="Editar Trámite"><i class="fa fa-edit"></i></button>';
        html += '<button type="button" class="btn btn-circle btn-info" onclick="abrirDetalleTask(' + row.id + ')" title="Ver Detalle"><i class="fa fa-eye"></i></button>';
        html += '</div>';
        return html;
    }

    function initDtSeguimientos(data) {
        if ($.fn.DataTable.isDataTable('#tblSeguimientos')) {
            $('#tblSeguimientos').DataTable().destroy();
        }
        _dtSeguimientos = $('#tblSeguimientos').DataTable({
            data: data,
            language: {
                search: 'Buscar expediente/destino:',
                zeroRecords: 'No se encontraron expedientes con ese filtro.',
                emptyTable: 'No hay expedientes en seguimiento registrados.',
            },
            columns: [
                { data: 'nro_expediente', title: 'N° Expediente', width: '130px',
                  render: function(d, t, r) {
                      return '<span class="font-weight-bold text-indigo" style="color:#4f46e5"><i class="fa fa-folder-open mr-1"></i>' + (d || 'S/N') + '</span>';
                  } 
                },
                { data: 'title', title: 'Asunto / Título',
                  render: function(d, t, r) {
                      return '<div class="font-weight-bold text-dark">' + d + '</div>' + (r.details ? '<small class="text-muted d-block">' + r.details.substring(0, 50) + '</small>' : '');
                  }
                },
                { data: 'destino_dependencia', title: 'Dependencia Destino',
                  render: function(d) {
                      return '<span class="badge badge-light border text-primary px-2 py-1"><i class="fa fa-paper-plane mr-1 text-info"></i>' + (d || 'Sin especificar') + '</span>';
                  } 
                },
                { data: 'fecha_inicio', title: 'Fecha Salida', width: '95px', className: 'text-center',
                  render: function(d) { return '<span class="small text-muted">' + (d || '—') + '</span>'; } 
                },
                { data: 'fecha_alerta', title: 'Fecha Alerta', width: '105px', className: 'text-center',
                  render: function(d, t, r) {
                      var cls = r.es_vencida ? 'text-danger font-weight-bold' : 'text-dark';
                      return '<span class="' + cls + '"><i class="fa fa-bell mr-1 ' + (r.es_vencida ? 'text-danger' : 'text-warning') + '"></i>' + (d || '—') + '</span>';
                  } 
                },
                { data: 'status', title: 'Estado Alerta', width: '120px', className: 'text-center',
                  render: function(d, t, r) { return buildStatusBadgeSeguimiento(d, r.es_vencida); } 
                },
                { data: 'responsable', title: 'Responsable', width: '120px',
                  render: function(d, t, r) {
                      return '<small class="text-dark font-weight-bold d-block">' + (d || 'Sin asignar') + '</small><small class="text-muted" style="font-size:.68rem">Por: ' + (r.creador || '—') + '</small>';
                  } 
                },
                { data: null, title: 'Acciones', width: '90px', className: 'text-center', orderable: false,
                  render: function(d, t, r) { return buildAccionesSeguimiento(r); } 
                },
            ],
            dom: '<"d-flex align-items-center justify-content-between mb-2"f>t',
            drawCallback: function() {
                $('#seguimientosCount').text(this.api().rows({ search: 'applied' }).count() + ' expedientes');
            }
        });
    }

    function filtrarDtSeguimientos(filtro) {
        var data = _seguimientosData.slice();
        if (filtro === 'vencidos')    data = data.filter(function(r) { return r.es_vencida; });
        if (filtro === 'pendientes')  data = data.filter(function(r) { return r.status !== 2 && !r.es_vencida; });
        if (filtro === 'finalizados') data = data.filter(function(r) { return r.status === 2; });
        
        if (_dtSeguimientos) {
            _dtSeguimientos.clear().rows.add(data).draw();
        } else {
            initDtSeguimientos(data);
        }
        $('#seguimientosCount').text(data.length + ' expedientes');
    }

    $(document).on('click', '#btnVerSeguimientos, #btnAbrirSeguimientosAlertados', function() {
        var preFiltro = $(this).attr('id') === 'btnAbrirSeguimientosAlertados' ? 'vencidos' : 'todos';
        $('#modalSeguimientos').modal('show');
        cargarSeguimientosModal(preFiltro);
    });

    function cargarSeguimientosModal(filtroDeseado) {
        $.getJSON(_seguimientosUrl, function(data) {
            _seguimientosData = data;
            var vencidosCount = data.filter(function(r) { return r.es_vencida; }).length;
            $('#countSeguimientosVencidos').text(vencidosCount);
            
            if (filtroDeseado === 'vencidos') {
                $('#filtroSeguimientoVencidos').trigger('click');
            } else {
                filtrarDtSeguimientos(_filtroSeguimientoActivo);
            }
        }).fail(function() {
            $('#seguimientosBody').html('<tr><td colspan="8" class="text-center text-danger py-3"><i class="fa fa-exclamation-triangle mr-1"></i>Error al cargar los expedientes de seguimiento.</td></tr>');
        });
    }

    $('#filtroSeguimientoTodos').on('click', function() {
        _filtroSeguimientoActivo = 'todos';
        $(this).addClass('btn-dark active').removeClass('btn-outline-dark');
        $('#filtroSeguimientoVencidos,#filtroSeguimientoPendientes,#filtroSeguimientoFinalizados').removeClass('btn-danger btn-warning btn-success active').addClass('btn-outline-danger btn-outline-warning btn-outline-success');
        filtrarDtSeguimientos('todos');
    });
    $('#filtroSeguimientoVencidos').on('click', function() {
        _filtroSeguimientoActivo = 'vencidos';
        $(this).addClass('btn-danger active').removeClass('btn-outline-danger');
        $('#filtroSeguimientoTodos,#filtroSeguimientoPendientes,#filtroSeguimientoFinalizados').removeClass('btn-dark btn-warning btn-success active').addClass('btn-outline-dark btn-outline-warning btn-outline-success');
        filtrarDtSeguimientos('vencidos');
    });
    $('#filtroSeguimientoPendientes').on('click', function() {
        _filtroSeguimientoActivo = 'pendientes';
        $(this).addClass('btn-warning active').removeClass('btn-outline-warning');
        $('#filtroSeguimientoTodos,#filtroSeguimientoVencidos,#filtroSeguimientoFinalizados').removeClass('btn-dark btn-danger btn-success active').addClass('btn-outline-dark btn-outline-danger btn-outline-warning');
        filtrarDtSeguimientos('pendientes');
    });
    $('#filtroSeguimientoFinalizados').on('click', function() {
        _filtroSeguimientoActivo = 'finalizados';
        $(this).addClass('btn-success active').removeClass('btn-outline-success');
        $('#filtroSeguimientoTodos,#filtroSeguimientoVencidos,#filtroSeguimientoPendientes').removeClass('btn-dark btn-danger btn-warning active').addClass('btn-outline-dark btn-outline-danger btn-outline-warning');
        filtrarDtSeguimientos('finalizados');
    });

    // ── Manejador Genérico Multi-Modal (Evita que modales hijo queden detrás del modal o backdrop padre) ──
    $(document).on('show.bs.modal', '.modal', function () {
        var visibleModals = $('.modal:visible').length;
        if (visibleModals > 0) {
            var zIndex = 1050 + (20 * visibleModals);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 5).addClass('modal-stack');
            }, 0);
        }
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        if ($('.modal:visible').length > 0) {
            setTimeout(function() {
                $(document.body).addClass('modal-open');
            }, 0);
        }
    });
})();
</script>

{{-- Scripts de Acta MECIP --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
@include('admin.globales.activities.partials.scripts_acta_mecip')

{{-- Google Charts --}}
<script src="/assets/googleCharts/loader.js"></script>

@endpush
