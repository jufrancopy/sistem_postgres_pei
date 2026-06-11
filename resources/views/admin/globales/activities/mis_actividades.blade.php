@extends('layouts.master')
@section('title', 'Mis Actividades')

@push('styles')
<style>
.act-card { border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; transition:box-shadow .2s, transform .1s; }
.act-card:hover { box-shadow:0 6px 20px rgba(0,0,0,.1); transform:translateY(-2px); }
.act-header { padding:16px 18px 12px; background:linear-gradient(135deg,#1e3a5f,#2563eb); color:#fff; }
.act-body   { padding:14px 18px; }
.act-footer { padding:10px 18px; background:#f8fafc; border-top:1px solid #f0f0f0; display:flex; gap:8px; align-items:center; }
.pct-bar  { height:6px; border-radius:3px; background:#e2e8f0; overflow:hidden; margin-top:6px; }
.pct-fill { height:100%; border-radius:3px; background:#10b981; transition:width .4s; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-tasks mr-2"></i>Mis Actividades
        </h4>
        <p class="card-category">Actividades donde sos responsable</p>
    </div>
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active">Mis Actividades</li>
        </ol>
    </nav>
    <div class="card-body">

        @if($actividades->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa fa-inbox fa-3x mb-3 d-block" style="opacity:.3"></i>
            <p>No tenés actividades asignadas todavía.</p>
            <small>Cuando el administrador te asigne como responsable de una actividad, aparecerá aquí.</small>
        </div>
        @else
        <div class="row">
            @foreach($actividades as $act)
            @php
                $misTareas   = $act->tasks->where('assigned_to', $userId);
                $total       = $misTareas->count();
                $completadas = $misTareas->where('status', 2)->count();
                $pct         = $total > 0 ? round(($completadas / $total) * 100) : 0;
                $pendientes  = $misTareas->whereIn('status', [0, 1, 3])->count();
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="act-card h-100">
                    <div class="act-header">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <div style="font-size:.7rem;opacity:.75;margin-bottom:2px">
                                    <i class="fa {{ $act->type === 'scrum' ? 'fa-sync-alt' : 'fa-stream' }} mr-1"></i>
                                    {{ $act->type === 'scrum' ? 'Scrum' : 'Kanban' }}
                                </div>
                                <div style="font-weight:700;font-size:.95rem">{{ $act->name }}</div>
                            </div>
                            @if($pendientes > 0)
                            <span class="badge badge-warning ml-2" style="flex-shrink:0">{{ $pendientes }} pendiente(s)</span>
                            @endif
                        </div>
                        @if($act->description)
                        <div style="font-size:.75rem;opacity:.75;margin-top:4px">{{ Str::limit($act->description, 70) }}</div>
                        @endif
                        <div class="pct-bar">
                            <div class="pct-fill" style="width:{{ $pct }}%"></div>
                        </div>
                        <small style="opacity:.7;font-size:.7rem">{{ $pct }}% de mis tareas completadas</small>
                    </div>
                    <div class="act-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h5 font-weight-bold text-muted">{{ $total }}</div>
                                <small class="text-muted" style="font-size:.7rem">Mis tareas</small>
                            </div>
                            <div class="col-4">
                                <div class="h5 font-weight-bold text-success">{{ $completadas }}</div>
                                <small class="text-muted" style="font-size:.7rem">Completadas</small>
                            </div>
                            <div class="col-4">
                                <div class="h5 font-weight-bold text-warning">{{ $pendientes }}</div>
                                <small class="text-muted" style="font-size:.7rem">Pendientes</small>
                            </div>
                        </div>
                    </div>
                    <div class="act-footer">
                        @if($pct === 100 && $total > 0)
                        <span class="text-success small"><i class="fa fa-check-circle mr-1"></i>¡Todo listo! 🎉</span>
                        @else
                        <span class="text-muted small">
                            @if($act->date_end)
                            <i class="fa fa-calendar mr-1"></i>Vence: {{ \Carbon\Carbon::parse($act->date_end)->format('d/m/Y') }}
                            @endif
                        </span>
                        @endif
                        <a href="{{ route('globales.mis-tareas', $act->id) }}" class="btn btn-info btn-sm ml-auto">
                            <i class="fa fa-eye mr-1"></i>Ver tablero
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
