@extends('layouts.master')
@section('title', 'Gestión de Eventos Institucionales & Hitos')

@section('css')
<link rel="stylesheet" href="{{ asset('material/css/plugins/fullcalendar.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
<style>
/* FullCalendar Custom Theme & Material Styles */
.fc-toolbar {
    margin-bottom: 1.25rem !important;
}
.fc-toolbar h2 {
    font-size: 1.35rem !important;
    font-weight: 700 !important;
    color: #1a202c !important;
    text-transform: capitalize !important;
}
.fc-button {
    border-radius: 6px !important;
    box-shadow: none !important;
    text-shadow: none !important;
    font-weight: 600 !important;
    padding: 0.4rem 0.85rem !important;
    background: #ffffff !important;
    color: #4a5568 !important;
    border: 1px solid #cbd5e1 !important;
}
.fc-button.fc-state-active, .fc-button.fc-state-down {
    background: #00bcd4 !important;
    color: #ffffff !important;
    border-color: #00bcd4 !important;
    box-shadow: 0 2px 4px rgba(0,188,212,0.3) !important;
}
.fc-event {
    border-radius: 6px !important;
    padding: 3px 7px !important;
    font-size: 0.82rem !important;
    border: none !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12) !important;
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.fc-event:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
}
.fc-evento-principal {
    font-weight: 700 !important;
    border-left: 4px solid #1e1b4b !important;
}
.fc-evento-paso {
    font-weight: 600 !important;
    border-left: 4px solid #78350f !important;
}
.fc-evento-tarea {
    font-size: 0.76rem !important;
    border-left: 4px solid #0369a1 !important;
}
.fc-tarea-completada {
    text-decoration: line-through !important;
    opacity: 0.65 !important;
}
.fc-day-header {
    background: #f8fafc;
    padding: 8px 0 !important;
    font-weight: 700 !important;
    color: #475569 !important;
    font-size: 0.8rem !important;
    text-transform: uppercase;
}
.btn-circle {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    max-width: 32px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.8rem !important;
    line-height: 1 !important;
    margin: 1px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}
.btn-circle i {
    font-size: 0.82rem !important;
    line-height: 1 !important;
    margin: 0 !important;
}
</style>
@endsection

@section('content')
<div class="card">
    {{-- Header Estándar de la Plataforma --}}
    <div class="card-header card-header-info d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h4 class="card-title font-weight-bold">
                @if(isset($selectedPei) && $selectedPei)
                    Eventos & Hitos: {{ strip_tags($selectedPei->name) }}
                @else
                    Módulo de Gestión de Eventos Institucionales & Hitos
                @endif
            </h4>
            <p class="card-category">
                @if(isset($selectedPei) && $selectedPei)
                    Talleres, Jornadas de Socialización y Cronograma Operativo del Plan Estratégico
                @else
                    Planificación por Fases, Asignación de Responsables, Checklist Operativo y Calendario
                @endif
            </p>
        </div>
        <div>
            <button type="button" class="btn btn-info btn-sm font-weight-bold mr-2" id="btnAbrirCalendarioModal">
                <i class="fa fa-calendar-alt mr-1"></i> Calendario
            </button>
            <button type="button" class="btn btn-success btn-sm font-weight-bold" id="btnCrearEventoModal">
                <i class="fa fa-plus mr-1"></i> + Nuevo Evento
            </button>
        </div>
    </div>

    {{-- Breadcrumbs Estándar --}}
    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4 mx-3 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
            @if(isset($selectedPei) && $selectedPei)
                <li class="breadcrumb-item"><a href="{{ route('pei-profiles.show', $selectedPei->id) }}">PEI: {{ strip_tags($selectedPei->name) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Eventos & Jornadas</li>
            @else
                <li class="breadcrumb-item active" aria-current="page">Eventos Institucionales</li>
            @endif
        </ol>
    </nav>

    <div class="card-body px-3">

        {{-- Tarjetas KPI --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #6366f1 !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Total Eventos</div>
                            <h2 class="font-weight-bold text-dark mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiTotalEventos }}</h2>
                            <small class="text-primary font-weight-bold"><i class="fa fa-calendar-day mr-1"></i>{{ $kpiEventosEnCurso }} en curso</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#e0e7ff; width:54px; height:54px;">
                            <i class="fa fa-calendar-alt fa-2x text-primary" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #0ea5e9 !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Avance Global</div>
                            <h2 class="font-weight-bold text-dark mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiAvanceGlobal }}%</h2>
                            <small class="text-info font-weight-bold"><i class="fa fa-tasks mr-1"></i>Checklist de Tareas</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#e0f2fe; width:54px; height:54px;">
                            <i class="fa fa-chart-line fa-2x text-info" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #f59e0b !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Tareas Pendientes</div>
                            <h2 class="font-weight-bold text-dark mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiTareasPend }}</h2>
                            <small class="text-warning font-weight-bold"><i class="fa fa-clock mr-1"></i>En ejecución activa</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#fef3c7; width:54px; height:54px;">
                            <i class="fa fa-hourglass-half fa-2x text-warning" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #ef4444 !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Tareas en Alerta / Vencidas</div>
                            <h2 class="font-weight-bold text-danger mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiTareasVencidas }}</h2>
                            <small class="text-danger font-weight-bold"><i class="fa fa-exclamation-triangle mr-1"></i>Requieren atención</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#fee2e2; width:54px; height:54px;">
                            <i class="fa fa-bell fa-2x text-danger" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros y Tabla Principal --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                    {{-- Barra de Filtros --}}
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <div class="row align-items-center">
                            @if(isset($selectedPei) && $selectedPei)
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-muted small text-uppercase mb-1">Plan PEI Activo</label>
                                    <div class="p-2 rounded bg-light border d-flex align-items-center" style="min-height:38px;">
                                        <i class="fa fa-bullseye text-primary mr-2"></i>
                                        <span class="text-dark font-weight-bold text-truncate" style="font-size:0.85rem;" title="{{ strip_tags($selectedPei->name) }}">{{ strip_tags($selectedPei->name) }}</span>
                                        <span class="badge badge-info ml-auto px-2 py-1" style="font-size:0.68rem;"><i class="fa fa-lock mr-1"></i>Fijo</span>
                                    </div>
                                    <input type="hidden" id="filtroPei" value="{{ $selectedPei->id }}">
                                </div>
                            @else
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-muted small text-uppercase mb-1">Filtrar por Plan PEI</label>
                                    <select id="filtroPei" class="form-control select2" style="width:100%;">
                                        <option value="">-- Todos los Planes PEI --</option>
                                        @foreach($peiPerfiles as $pei)
                                            <option value="{{ $pei->id }}" {{ $selectedPeiId == $pei->id ? 'selected' : '' }}>
                                                {{ strip_tags($pei->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="font-weight-bold text-muted small text-uppercase mb-1">Filtrar por Estado</label>
                                <select id="filtroEstado" class="form-control select2" style="width:100%;">
                                    <option value="">-- Todos los Estados --</option>
                                    <option value="planificado">Planificado</option>
                                    <option value="en_curso">En Curso</option>
                                    <option value="completado">Completado</option>
                                    <option value="en_alerta">En Alerta</option>
                                    <option value="pospuesto">Pospuesto</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="font-weight-bold text-muted small text-uppercase mb-1">Filtrar por Tipo</label>
                                <select id="filtroTipo" class="form-control select2" style="width:100%;">
                                    <option value="">-- Todos los Tipos --</option>
                                    <option value="Taller">Taller</option>
                                    <option value="Jornada">Jornada</option>
                                    <option value="Reunión">Reunión de Alto Nivel</option>
                                    <option value="Congreso">Congreso / Simposio</option>
                                    <option value="Socialización">Socialización PEI</option>
                                    <option value="Evaluación">Evaluación de Avance</option>
                                    <option value="Capacitación">Capacitación</option>
                                </select>
                            </div>
                            <div class="col-md-2 text-md-right mt-3 mt-md-0">
                                <button type="button" class="btn btn-light btn-sm font-weight-bold text-secondary" id="btnLimpiarFiltros" style="border-radius:8px;">
                                    <i class="fa fa-undo mr-1"></i> Resetear
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla DataTables --}}
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100" id="tablaEventos">
                                <thead style="background:#f8fafc; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 28%;">Evento / Sede</th>
                                        <th style="width: 20%;">Plan PEI Vinculado</th>
                                        <th style="width: 14%;">Período</th>
                                        <th style="width: 15%;">Avance Tareas</th>
                                        <th style="width: 10%;">Estado</th>
                                        <th style="width: 8%;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Calendario Integral de Eventos e Hitos --}}
<div class="modal fade" id="modalCalendarioGeneral" tabindex="-1" aria-hidden="true" style="z-index: 1050;">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 94%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div>
                    <span class="badge badge-light text-dark font-weight-bold mb-1" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">
                        <i class="fa fa-calendar-alt text-info mr-1"></i> CRONOGRAMA EJECUTIVO
                    </span>
                    <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size: 1.2rem;">
                        Calendario Integral de Eventos & Hitos PEI
                    </h5>
                </div>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <button type="button" class="btn btn-sm btn-success font-weight-bold" id="btnCrearDesdeCalendario" style="border-radius: 8px;">
                        <i class="fa fa-plus mr-1"></i> + Nuevo Evento
                    </button>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9; font-size: 1.5rem;"><span>&times;</span></button>
                </div>
            </div>

            {{-- Barra de Filtros del Calendario y Leyenda --}}
            <div class="p-3 border-bottom" style="background: #f1f5f9;">
                <div class="row align-items-center">
                    @if(isset($selectedPei) && $selectedPei)
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label class="font-weight-bold text-dark small text-uppercase mb-1">Plan PEI Activo</label>
                            <div class="p-2 rounded bg-white border d-flex align-items-center" style="min-height:38px;">
                                <i class="fa fa-bullseye text-primary mr-2"></i>
                                <span class="text-dark font-weight-bold text-truncate" style="font-size:0.85rem;" title="{{ strip_tags($selectedPei->name) }}">{{ strip_tags($selectedPei->name) }}</span>
                            </div>
                            <input type="hidden" id="calFilterPei" value="{{ $selectedPei->id }}">
                        </div>
                    @else
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label class="font-weight-bold text-dark small text-uppercase mb-1">Filtrar por Plan PEI</label>
                            <select id="calFilterPei" class="form-control select2Cal" style="width:100%;">
                                <option value="">-- Todos los Planes PEI --</option>
                                @foreach($peiPerfiles as $pei)
                                    <option value="{{ $pei->id }}" {{ $selectedPeiId == $pei->id ? 'selected' : '' }}>
                                        {{ strip_tags($pei->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="font-weight-bold text-dark small text-uppercase mb-1">Filtrar por Responsable</label>
                        <select id="calFilterResp" class="form-control select2Cal" style="width:100%;">
                            <option value="">-- Todos los Responsables --</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 text-md-right mt-2 mt-md-0">
                        <label class="font-weight-bold text-dark small text-uppercase mb-1 d-block">Convención de Colores</label>
                        <span class="badge badge-pill shadow-sm" style="background:#6366f1; color:#fff; font-size:0.75rem; padding: 4px 8px;"><i class="fa fa-star mr-1"></i> Eventos</span>
                        <span class="badge badge-pill shadow-sm" style="background:#f59e0b; color:#fff; font-size:0.75rem; padding: 4px 8px;"><i class="fa fa-play mr-1"></i> Fases/Hitos</span>
                        <span class="badge badge-pill shadow-sm" style="background:#0ea5e9; color:#fff; font-size:0.75rem; padding: 4px 8px;"><i class="fa fa-tasks mr-1"></i> Tareas</span>
                        <span class="badge badge-pill shadow-sm" style="background:#10b981; color:#fff; font-size:0.75rem; padding: 4px 8px;"><i class="fa fa-check mr-1"></i> Hechas</span>
                    </div>
                </div>
            </div>

            <div class="modal-body p-4 bg-white" style="min-height: 580px; max-height: 78vh; overflow-y: auto;">
                <div id="fullcalendarGeneral" style="min-height: 520px;"></div>
            </div>
            
            <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between">
                <small class="text-muted"><i class="fa fa-info-circle mr-1"></i> Haz clic sobre cualquier evento o tarea en el calendario para acceder directamente a su centro de mando.</small>
                <button type="button" class="btn btn-secondary btn-sm px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear / Editar Evento --}}
<div class="modal fade" id="modalEventoForm" tabindex="-1" aria-hidden="true" style="z-index: 1050;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div>
                    <span class="badge badge-light text-dark font-weight-bold mb-1" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">
                        <i class="fa fa-calendar-check text-info mr-1"></i> GESTIÓN INSTITUCIONAL
                    </span>
                    <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalEventoTitulo" style="font-size: 1.15rem;">
                        Nuevo Evento Institucional
                    </h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;"><span>&times;</span></button>
            </div>
            <form id="formEvento">
                <input type="hidden" id="eventoId" name="evento_id" value="">
                <div class="modal-body p-4" style="background: #f8fafc; max-height: 75vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eventoNombre" name="nombre" placeholder="Ej: Taller de Socialización del Plan Estratégico IPS 2026-2028" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Tipo de Evento</label>
                            <select class="form-control select2InModal" id="eventoTipo" name="tipo" style="width:100%;">
                                <option value="Taller">Taller</option>
                                <option value="Jornada">Jornada</option>
                                <option value="Reunión">Reunión de Alto Nivel</option>
                                <option value="Congreso">Congreso / Simposio</option>
                                <option value="Socialización">Socialización PEI</option>
                                <option value="Evaluación">Evaluación de Avance</option>
                                <option value="Capacitación">Capacitación</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        @if(isset($selectedPei) && $selectedPei)
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold text-dark mb-1">Plan Estratégico Asociado</label>
                                <div class="p-2.5 rounded border d-flex align-items-center" style="background:#f0f9ff; border-color:#bae6fd !important; min-height: 42px;">
                                    <i class="fa fa-bullseye text-primary mr-2"></i>
                                    <div class="text-truncate mr-2">
                                        <strong class="text-dark d-block" style="font-size:0.85rem;" title="{{ strip_tags($selectedPei->name) }}">{{ strip_tags($selectedPei->name) }}</strong>
                                        <small class="text-info font-weight-bold" style="font-size:0.7rem;"><i class="fa fa-link mr-1"></i>Vinculado automáticamente al Plan</small>
                                    </div>
                                    <span class="badge badge-info ml-auto px-2 py-1" style="font-size:0.68rem;"><i class="fa fa-lock mr-1"></i>PEI Activo</span>
                                </div>
                                <input type="hidden" id="eventoPeiId" name="pei_profile_id" value="{{ $selectedPei->id }}">
                            </div>
                        @else
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold text-dark mb-1">Plan PEI Vinculado (Opcional)</label>
                                <select class="form-control select2InModal" id="eventoPeiId" name="pei_profile_id" style="width:100%;">
                                    <option value="">-- Ninguno / Institucional General --</option>
                                    @foreach($peiPerfiles as $pei)
                                        <option value="{{ $pei->id }}" {{ (isset($selectedPeiId) && $selectedPeiId == $pei->id) ? 'selected' : '' }}>
                                            {{ strip_tags($pei->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Lugar / Sede</label>
                            <input type="text" class="form-control" id="eventoLugarSede" name="lugar_sede" placeholder="Ej: Centro de Eventos Ykua Satí / Auditorio Central">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="eventoFechaInicio" name="fecha_inicio">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Fecha de Finalización</label>
                            <input type="date" class="form-control" id="eventoFechaFin" name="fecha_fin">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Estado</label>
                            <select class="form-control select2InModal" id="eventoEstado" name="estado" style="width:100%;">
                                <option value="planificado">Planificado</option>
                                <option value="en_curso">En Curso</option>
                                <option value="completado">Completado</option>
                                <option value="en_alerta">En Alerta</option>
                                <option value="pospuesto">Pospuesto</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1"><i class="fas fa-coins text-warning mr-1"></i> Presupuesto Estimado (Gs.)</label>
                            <input type="number" step="0.01" class="form-control" id="eventoPresupuestoEstimado" name="presupuesto_estimado" value="0">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1"><i class="fas fa-receipt text-secondary mr-1"></i> Presupuesto Ejecutado (Gs.)</label>
                            <input type="number" step="0.01" class="form-control" id="eventoPresupuestoEjecutado" name="presupuesto_ejecutado" value="0">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Color Distintivo</label>
                            <input type="color" class="form-control" id="eventoColor" name="color" value="#00bcd4" style="height:38px; padding:2px;">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1"><i class="fas fa-users text-primary mr-1"></i> Equipo / Responsables del Evento</label>
                        <select class="form-control select2InModal" id="eventoResponsables" name="responsables[]" multiple="multiple" style="width:100%;">
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1">Descripción / Objetivos del Evento</label>
                        <textarea class="form-control" id="eventoDescripcion" name="descripcion" rows="3" placeholder="Detalla los objetivos, público meta y alcance..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 font-weight-bold" id="btnGuardarEvento" style="border-radius: 8px;">
                        <i class="fa fa-save mr-1"></i> Guardar Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('material/js/plugins/moment.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="{{ asset('material/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('material/js/plugins/locale/es.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/es.js"></script>

<script>
$(function() {
    $('.select2').select2({ width: '100%' });
    $('.select2InModal').select2({ dropdownParent: $('#modalEventoForm'), width: '100%' });

    var tablaEventos = $('#tablaEventos').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("eventos.index") }}',
            data: function(d) {
                d.pei_profile_id = $('#filtroPei').val();
                d.estado = $('#filtroEstado').val();
                d.tipo = $('#filtroTipo').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold text-muted' },
            { data: 'nombre_html', name: 'nombre' },
            { data: 'pei_profile', name: 'peiProfile.name' },
            { data: 'fechas', name: 'fecha_inicio' },
            { data: 'avance_html', name: 'porcentaje_avance', orderable: false },
            { data: 'estado_html', name: 'estado', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: window.dtSpanishEs || {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ eventos",
            infoEmpty: "Sin eventos registrados",
            infoFiltered: "(filtrado de _MAX_ eventos)",
            zeroRecords: "No se encontraron eventos",
            paginate: { first: "Primero", previous: "Anterior", next: "Siguiente", last: "Último" }
        },
        pageLength: 10,
        order: [[3, 'desc']]
    });

    $('#filtroPei, #filtroEstado, #filtroTipo').on('change', function() {
        tablaEventos.ajax.reload();
    });

    $('#btnLimpiarFiltros').on('click', function() {
        $('#filtroPei').val('').trigger('change');
        $('#filtroEstado').val('').trigger('change');
        $('#filtroTipo').val('').trigger('change');
        tablaEventos.ajax.reload();
    });

    // ── GESTIÓN DE CALENDARIO EN MODAL ─────────────────────────
    var calInitialized = false;

    function initGeneralCalendar() {
        if (!calInitialized) {
            calInitialized = true;
            $('#fullcalendarGeneral').fullCalendar({
                locale: 'es',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,listMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    list: 'Agenda'
                },
                defaultDate: '{{ date("Y-m-d") }}',
                events: function(start, end, timezone, callback) {
                    $.ajax({
                        url: '{{ route("eventos.feed") }}',
                        data: {
                            pei_profile_id: $('#calFilterPei').val(),
                            responsable_id: $('#calFilterResp').val()
                        },
                        success: function(doc) {
                            callback(doc);
                        }
                    });
                },
                eventRender: function(event, element) {
                    if (event.extendedProps) {
                        var tooltipHtml = '<strong>' + event.title + '</strong>';
                        if (event.extendedProps.lugar) tooltipHtml += '<br><small><i class="fa fa-map-marker-alt text-danger mr-1"></i>' + event.extendedProps.lugar + '</small>';
                        if (event.extendedProps.responsable) tooltipHtml += '<br><small><i class="fa fa-user text-primary mr-1"></i>' + event.extendedProps.responsable + '</small>';
                        if (event.extendedProps.avance) tooltipHtml += '<br><small>Avance: <strong>' + event.extendedProps.avance + '</strong></small>';

                        $(element).tooltip({
                            title: tooltipHtml,
                            html: true,
                            container: 'body',
                            placement: 'top'
                        });
                    }
                },
                eventClick: function(calEvent, jsEvent, view) {
                    if (calEvent.url) {
                        window.location.href = calEvent.url;
                        return false;
                    }
                }
            });
        } else {
            $('#fullcalendarGeneral').fullCalendar('refetchEvents');
        }
    }

    // Abrir modal de calendario
    $('#btnAbrirCalendarioModal').on('click', function() {
        $('#modalCalendarioGeneral').modal('show');
    });

    $('#modalCalendarioGeneral').on('shown.bs.modal', function() {
        initGeneralCalendar();
        $('#fullcalendarGeneral').fullCalendar('render');
        $('.select2Cal').select2({ dropdownParent: $('#modalCalendarioGeneral'), width: '100%' });
    });

    $('#calFilterPei, #calFilterResp').on('change', function() {
        if (calInitialized) {
            $('#fullcalendarGeneral').fullCalendar('refetchEvents');
        }
    });

    $('#btnCrearDesdeCalendario').on('click', function() {
        $('#modalCalendarioGeneral').modal('hide');
        setTimeout(function() {
            $('#btnCrearEventoModal').click();
        }, 350);
    });

    // ── GESTIÓN DE EVENTOS (CREAR / EDITAR) ────────────────────
    $('#btnCrearEventoModal').on('click', function() {
        $('#formEvento')[0].reset();
        $('#eventoId').val('');
        $('#eventoPeiId').val($('#filtroPei').val() || '{{ $selectedPeiId ?? "" }}').trigger('change');
        $('#eventoTipo').val('Taller').trigger('change');
        $('#eventoEstado').val('planificado').trigger('change');
        $('#eventoResponsables').val([]).trigger('change');
        $('#eventoColor').val('#00bcd4');
        $('#eventoPresupuestoEstimado').val(0);
        $('#eventoPresupuestoEjecutado').val(0);
        $('#modalEventoTitulo').html('<i class="fa fa-plus-circle text-white mr-1"></i> Nuevo Evento Institucional');
        $('#modalEventoForm').modal('show');
    });

    // Auto abrir si viene ?action=create o ?action=calendario
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'create') {
        $('#btnCrearEventoModal').click();
    } else if (urlParams.get('action') === 'calendario') {
        $('#btnAbrirCalendarioModal').click();
    }

    // Abrir modal para Editar
    $(document).on('click', '.btnEditarEvento', function() {
        var id = $(this).data('id');
        $.get('/admin/eventos/' + id + '/edit', function(data) {
            $('#eventoId').val(data.id);
            $('#eventoNombre').val(data.nombre);
            $('#eventoTipo').val(data.tipo).trigger('change');
            $('#eventoLugarSede').val(data.lugar_sede);
            $('#eventoPeiId').val(data.pei_profile_id).trigger('change');
            $('#eventoFechaInicio').val(data.fecha_inicio ? data.fecha_inicio.substring(0, 10) : '');
            $('#eventoFechaFin').val(data.fecha_fin ? data.fecha_fin.substring(0, 10) : '');
            $('#eventoEstado').val(data.estado).trigger('change');
            $('#eventoColor').val(data.color || '#00bcd4');
            $('#eventoPresupuestoEstimado').val(data.presupuesto_estimado || 0);
            $('#eventoPresupuestoEjecutado').val(data.presupuesto_ejecutado || 0);
            $('#eventoDescripcion').val(data.descripcion);

            var respIds = data.responsables ? data.responsables.map(function(r) { return r.id; }) : [];
            $('#eventoResponsables').val(respIds).trigger('change');

            $('#modalEventoTitulo').html('<i class="fa fa-edit text-white mr-1"></i> Editar Evento');
            $('#modalEventoForm').modal('show');
        });
    });

    // Guardar Evento (Crear o Actualizar)
    $('#formEvento').on('submit', function(e) {
        e.preventDefault();
        var id = $('#eventoId').val();
        var url = id ? '/admin/eventos/' + id : '{{ route("eventos.store") }}';
        var method = id ? 'PUT' : 'POST';

        var formData = $(this).serialize();
        if (id) formData += '&_method=PUT';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(resp) {
                $('#modalEventoForm').modal('hide');
                tablaEventos.ajax.reload();
                if (calInitialized) $('#fullcalendarGeneral').fullCalendar('refetchEvents');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: resp.message,
                    showConfirmButton: false,
                    timer: 2500
                });
                if (!id && resp.redirect) {
                    setTimeout(function() {
                        window.location.href = resp.redirect;
                    }, 800);
                }
            },
            error: function(xhr) {
                var msg = 'Ocurrió un error al guardar el evento.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Atención', msg, 'warning');
            }
        });
    });

    // Eliminar Evento
    $(document).on('click', '.btnEliminarEvento', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');

        Swal.fire({
            title: '¿Eliminar Evento?',
            text: 'Se eliminará "' + nombre + '" junto con sus fases y checklist asociado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/eventos/' + id,
                    type: 'DELETE',
                    success: function(resp) {
                        tablaEventos.ajax.reload();
                        if (calInitialized) $('#fullcalendarGeneral').fullCalendar('refetchEvents');
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: resp.message,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar el evento.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
