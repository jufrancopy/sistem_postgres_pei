@extends('layouts.master')
@section('title', $evento->nombre)

@section('content')
<div class="card">
    {{-- Header Estándar de la Plataforma --}}
    <div class="card-header card-header-info d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h4 class="card-title font-weight-bold">{{ $evento->nombre }}</h4>
            <p class="card-category">Centro de Mando, Fases Cronológicas, Checklist Operativo y Calendario</p>
        </div>
        <div>
            <a href="{{ route('eventos.index') }}" class="btn btn-warning btn-sm font-weight-bold mr-2">
                <i class="fa fa-arrow-left mr-1"></i> Volver a Eventos
            </a>
            <a href="{{ route('eventos.edit', $evento->id) }}" class="btn btn-primary btn-sm font-weight-bold">
                <i class="fa fa-edit mr-1"></i> Editar Evento
            </a>
        </div>
    </div>

    {{-- Breadcrumbs Estándar --}}
    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4 mx-3 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('eventos.index') }}">Eventos Institucionales</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($evento->nombre, 35) }}</li>
        </ol>
    </nav>

    <div class="card-body px-3">
        {{-- Hero Header Ejecutivo --}}
        <div class="card border-0 shadow-sm text-white mb-4" style="border-radius:18px; overflow:hidden; background: linear-gradient(135deg, {{ $evento->color ?: '#4f46e5' }} 0%, #1e1b4b 100%);">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
                    <div>
                        <div class="d-flex align-items-center mb-2 flex-wrap" style="gap:8px;">
                            <span class="badge badge-light text-dark font-weight-bold px-2.5 py-1" style="font-size:0.75rem; border-radius:6px;">
                                <i class="fa fa-tag mr-1 text-primary"></i>{{ $evento->tipo }}
                            </span>
                            {!! $evento->estado_badge_html !!}
                            @if($evento->peiProfile)
                                <a href="{{ route('pei-profiles.show', $evento->peiProfile->id) }}" class="badge badge-warning text-dark font-weight-bold px-2.5 py-1 text-decoration-none" style="font-size:0.75rem; border-radius:6px;" title="Ver Plan PEI">
                                    <i class="fa fa-bullseye mr-1"></i>{{ \Illuminate\Support\Str::limit(strip_tags($evento->peiProfile->name), 38) }}
                                </a>
                            @endif
                        </div>
                        <h2 class="font-weight-bold mb-1 text-white" style="letter-spacing:-0.02em; font-size:1.85rem;">
                            {{ $evento->nombre }}
                        </h2>
                        <div class="d-flex align-items-center flex-wrap mt-2 text-white-50" style="gap:15px; font-size:0.85rem;">
                            <span><i class="fa fa-calendar-alt text-warning mr-1"></i>{{ $evento->rango_fechas_formateado }}</span>
                            @if($evento->lugar_sede)
                                <span><i class="fa fa-map-marker-alt text-danger mr-1"></i>{{ $evento->lugar_sede }}</span>
                            @endif
                            @if($evento->dias_restantes !== null)
                                @if($evento->dias_restantes > 0)
                                    <span class="badge badge-light text-dark font-weight-bold"><i class="fa fa-hourglass-start mr-1"></i>Faltan {{ $evento->dias_restantes }} días</span>
                                @elseif($evento->dias_restantes === 0)
                                    <span class="badge badge-warning text-dark font-weight-bold"><i class="fa fa-flag-checkered mr-1"></i>¡Finaliza hoy!</span>
                                @else
                                    <span class="badge badge-danger"><i class="fa fa-clock mr-1"></i>Finalizó hace {{ abs($evento->dias_restantes) }} días</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <a href="{{ route('eventos.index') }}" class="btn btn-outline-light btn-sm font-weight-bold px-3 shadow-sm" style="border-radius:8px;">
                            <i class="fa fa-arrow-left mr-1"></i> Volver
                        </a>
                        <button type="button" class="btn btn-warning text-dark font-weight-bold btn-sm px-3 shadow-sm" id="btnEditarEventoHub" style="border-radius:8px;">
                            <i class="fa fa-pencil-alt mr-1"></i> Editar Evento
                        </button>
                        <button type="button" class="btn btn-success btn-sm font-weight-bold px-3 shadow-sm" id="btnNuevoPasoModal" style="border-radius:8px;">
                            <i class="fa fa-plus mr-1"></i> Agregar Paso / Hito
                        </button>
                    </div>
                </div>

                {{-- Barra de Progreso Global del Evento --}}
                <div class="mt-4 pt-3 border-top" style="border-color: rgba(255,255,255,0.15) !important;">
                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size:0.82rem;">
                        <span class="font-weight-bold text-white"><i class="fa fa-chart-line mr-1 text-warning"></i>Avance Global del Evento: <span id="eventoAvancePctTexto">{{ $evento->porcentaje_avance }}%</span></span>
                        <span class="text-white-50" id="eventoTareasConteoTexto">{{ $evento->tareas_completadas }} de {{ $evento->total_tareas }} tareas completadas</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 6px; background: rgba(255,255,255,0.25);">
                        <div class="progress-bar bg-warning" id="eventoAvanceBar" role="progressbar" style="width: {{ $evento->porcentaje_avance }}%; border-radius: 6px; transition: width .4s ease;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alertas Diarias del Evento (Si existen tareas vencidas o próximas) --}}
        @if($tareasVencidas->isNotEmpty() || $tareasProximas->isNotEmpty())
        <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-start" style="border-radius:12px; background:#fffbeb; border-left:5px solid #f59e0b !important; color:#78350f;">
            <div class="mr-3 mt-1"><i class="fa fa-bell fa-2x text-warning"></i></div>
            <div class="flex-grow-1">
                <h6 class="font-weight-bold mb-1" style="color:#92400e;">
                    <i class="fa fa-exclamation-triangle mr-1"></i>Recordatorios Diarios & Alertas de Vencimiento
                </h6>
                <div class="small">
                    @if($tareasVencidas->isNotEmpty())
                        <div class="text-danger font-weight-bold mb-1">
                            <i class="fa fa-times-circle mr-1"></i>{{ $tareasVencidas->count() }} tarea(s) vencida(s):
                            @foreach($tareasVencidas->take(3) as $tv)
                                <span class="badge badge-danger mr-1">{{ $tv->nombre }} ({{ $tv->responsable ? $tv->responsable->name : 'Sin asignar' }})</span>
                            @endforeach
                        </div>
                    @endif
                    @if($tareasProximas->isNotEmpty())
                        <div class="text-warning font-weight-bold" style="color:#b45309;">
                            <i class="fa fa-clock mr-1"></i>{{ $tareasProximas->count() }} tarea(s) próxima(s) a vencer en los próximos 3 días:
                            @foreach($tareasProximas->take(3) as $tp)
                                <span class="badge badge-warning text-dark mr-1">{{ $tp->nombre }} (Vence: {{ \Carbon\Carbon::parse($tp->fecha_limite)->format('d/m') }})</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Pestañas de Navegación del Evento --}}
        <ul class="nav nav-pills mb-4 d-flex flex-wrap" id="eventoTabs" role="tablist" style="gap:8px;">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold shadow-xs" id="tab-pasos-btn" data-toggle="pill" href="#tab-pasos" role="tab" style="border-radius:10px; padding:8px 18px;">
                    <i class="fa fa-list-ol mr-1"></i> 1. Fases & Checklist Operativo ({{ $evento->pasos->count() }} Pasos)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold shadow-xs" id="tab-calendario-btn" data-toggle="pill" href="#tab-calendario" role="tab" style="border-radius:10px; padding:8px 18px;">
                    <i class="fa fa-calendar-alt mr-1"></i> 2. Calendario del Evento
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold shadow-xs" id="tab-equipo-btn" data-toggle="pill" href="#tab-equipo" role="tab" style="border-radius:10px; padding:8px 18px;">
                    <i class="fa fa-users-cog mr-1"></i> 3. Equipo & Carga de Trabajo
                </a>
            </li>
        </ul>

        {{-- Contenido de las Pestañas --}}
        <div class="tab-content" id="eventoTabsContent">
            
            {{-- TAB 1: Fases y Checklist --}}
            <div class="tab-pane fade show active" id="tab-pasos" role="tabpanel">
                @if($evento->pasos->isEmpty())
                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius:16px;">
                        <div class="card-body">
                            <i class="fa fa-calendar-plus fa-3x text-primary mb-3" style="opacity:0.4;"></i>
                            <h5 class="font-weight-bold text-dark mb-1">Aún no has agregado fases o pasos a este evento</h5>
                            <p class="text-muted small mb-3">Estructura tu evento en pasos cronológicos (ej: Paso 1: Aprobación del MEF, Paso 2: Reunión Alta Gerencia, Paso 3: Jornada Ykua Satí).</p>
                            <button type="button" class="btn btn-primary font-weight-bold btn-sm px-4" id="btnNuevoPasoVacio" style="border-radius:8px;">
                                <i class="fa fa-plus mr-1"></i> Agregar Primer Paso
                            </button>
                        </div>
                    </div>
                @else
                    <div class="d-flex flex-column" style="gap:18px;">
                        @foreach($evento->pasos as $index => $paso)
                        <div class="card border-0 shadow-sm" id="paso-card-{{ $paso->id }}" style="border-radius:16px; overflow:hidden; border-left: 6px solid {{ $paso->color ?: ($evento->color ?: '#4f46e5') }} !important;">
                            {{-- Cabecera del Paso --}}
                            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                    <span class="badge badge-dark font-weight-bold px-2.5 py-1" style="font-size:0.75rem; border-radius:6px;">
                                        Paso #{{ $paso->orden ?: ($index + 1) }}
                                    </span>
                                    <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.05rem;">
                                        {{ $paso->nombre }}
                                    </h5>
                                    {!! $paso->estado_badge_html !!}
                                </div>
                                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                                    <span class="badge badge-light border text-muted px-2 py-1 small">
                                        <i class="fa fa-calendar-day mr-1 text-primary"></i>{{ $paso->rango_fechas_formateado }}
                                    </span>
                                    @if($paso->responsablePrincipal)
                                        <span class="badge badge-light border text-dark px-2 py-1 small" title="Responsable Principal">
                                            <i class="fa fa-user-tie mr-1 text-success"></i>{{ $paso->responsablePrincipal->name }}
                                        </span>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-sm font-weight-bold py-1 px-2.5 btnNuevaTareaModal shadow-xs" data-paso-id="{{ $paso->id }}" data-paso-nombre="{{ e($paso->nombre) }}" style="border-radius:6px; font-size:0.75rem;">
                                        <i class="fa fa-plus mr-1"></i> Tarea
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm py-1 px-2 text-primary btnEditarPasoModal" data-paso="{{ json_encode($paso) }}" title="Editar Paso">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm py-1 px-2 text-danger btnEliminarPaso" data-paso-id="{{ $paso->id }}" data-paso-nombre="{{ e($paso->nombre) }}" title="Eliminar Paso">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Cuerpo del Paso: Checklist de Tareas --}}
                            <div class="card-body p-4 bg-white">
                                @if($paso->descripcion)
                                    <p class="text-muted small mb-3"><i class="fa fa-info-circle mr-1 text-info"></i>{{ $paso->descripcion }}</p>
                                @endif

                                {{-- Barra de Progreso del Paso --}}
                                <div class="d-flex align-items-center justify-content-between mb-2" style="font-size:0.75rem;">
                                    <span class="font-weight-bold text-muted text-uppercase" style="letter-spacing:0.04em;">Checklist de Tareas del Paso</span>
                                    <span class="font-weight-bold text-dark" id="pasoAvanceTexto-{{ $paso->id }}">{{ $paso->porcentaje_avance }}% completado ({{ $paso->tareas_completadas }}/{{ $paso->total_tareas }})</span>
                                </div>
                                <div class="progress mb-3" style="height: 6px; border-radius: 4px; background:#f1f5f9;">
                                    <div class="progress-bar bg-success" id="pasoAvanceBar-{{ $paso->id }}" role="progressbar" style="width: {{ $paso->porcentaje_avance }}%; border-radius: 4px; transition: width .3s ease;"></div>
                                </div>

                                {{-- Lista de Tareas --}}
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0" style="font-size:0.85rem;">
                                        <thead style="background:#f8fafc; font-size:0.72rem; text-transform:uppercase; color:#64748b;">
                                            <tr>
                                                <th style="width: 5%;" class="text-center">Estado</th>
                                                <th style="width: 38%;">Tarea / Actividad</th>
                                                <th style="width: 18%;">Responsable</th>
                                                <th style="width: 14%;">Fecha Límite</th>
                                                <th style="width: 12%;">Prioridad</th>
                                                <th style="width: 13%;" class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listaTareasPaso-{{ $paso->id }}">
                                            @forelse($paso->tareas as $tarea)
                                                <tr id="tarea-row-{{ $tarea->id }}" class="{{ $tarea->completada ? 'table-success-soft' : '' }}" style="transition: background .2s ease;">
                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox d-inline-block">
                                                            <input type="checkbox" class="custom-control-input checkboxTareaToggle" id="chk-tarea-{{ $tarea->id }}" data-tarea-id="{{ $tarea->id }}" {{ $tarea->completada ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="chk-tarea-{{ $tarea->id }}" style="cursor:pointer;"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-weight-bold tarea-nombre-text {{ $tarea->completada ? 'text-muted text-decoration-line-through' : 'text-dark' }}" id="tarea-nombre-{{ $tarea->id }}">
                                                            {{ $tarea->nombre }}
                                                        </div>
                                                        @if($tarea->descripcion)
                                                            <small class="text-muted d-block">{{ $tarea->descripcion }}</small>
                                                        @endif
                                                        <small class="text-success tarea-completada-meta" id="tarea-meta-{{ $tarea->id }}" style="{{ $tarea->completada ? '' : 'display:none;' }}">
                                                            <i class="fa fa-check-double mr-1"></i>Completada por {{ $tarea->completadaPor ? $tarea->completadaPor->name : 'Usuario' }} el {{ $tarea->completada_el ? $tarea->completada_el->format('d/m/Y H:i') : '' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($tarea->responsable)
                                                            <span class="badge badge-light border text-dark font-weight-normal" style="font-size:0.75rem;">
                                                                <i class="fa fa-user mr-1 text-muted"></i>{{ $tarea->responsable->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted small">Sin asignar</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($tarea->fecha_limite)
                                                            <span class="badge {{ $tarea->esta_vencida ? 'badge-danger' : ($tarea->es_proxima ? 'badge-warning text-dark' : 'badge-light border') }}" style="font-size:0.72rem;">
                                                                <i class="fa fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted small">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {!! $tarea->prioridad_badge_html !!}
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-outline-primary btnEditarTareaModal mr-1" data-tarea="{{ json_encode($tarea) }}" title="Editar Tarea">
                                                            <i class="fa fa-pencil-alt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-xs btn-outline-danger btnEliminarTarea" data-tarea-id="{{ $tarea->id }}" data-tarea-nombre="{{ e($tarea->nombre) }}" title="Eliminar Tarea">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr id="sin-tareas-{{ $paso->id }}">
                                                    <td colspan="6" class="text-center py-3 text-muted small">
                                                        <i class="fa fa-tasks mr-1"></i>No hay tareas asignadas en este paso. Haz clic en <strong>+ Tarea</strong> para agregar una.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- TAB 2: Calendario Integrado del Evento --}}
            <div class="tab-pane fade" id="tab-calendario" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:16px;">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="font-weight-bold mb-0 text-dark">
                                <i class="fa fa-calendar-alt text-primary mr-2"></i>Cronograma Temporal del Evento
                            </h5>
                            <small class="text-muted">Visualización interactiva de las fechas de las Fases y vencimientos de Tareas.</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div id="fullcalendarEvento" style="min-height: 550px;"></div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: Matriz de Equipo y Carga de Trabajo --}}
            <div class="tab-pane fade" id="tab-equipo" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:16px;">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="font-weight-bold mb-0 text-dark">
                            <i class="fa fa-users-cog text-primary mr-2"></i>Distribución de Responsabilidades y Tareas
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead style="background:#f8fafc; font-size:0.75rem; text-transform:uppercase;">
                                    <tr>
                                        <th>Miembro del Equipo</th>
                                        <th>Rol en el Evento</th>
                                        <th class="text-center">Total Tareas</th>
                                        <th class="text-center">Completadas</th>
                                        <th class="text-center">Pendientes</th>
                                        <th>Rendimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $todosResponsables = $evento->responsables->concat(
                                            $evento->tareas->map(fn($t) => $t->responsable)->filter()
                                        )->unique('id');
                                    @endphp
                                    @forelse($todosResponsables as $u)
                                        @php
                                            $tUser = $evento->tareas->where('responsable_id', $u->id);
                                            $totU = $tUser->count();
                                            $compU = $tUser->where('completada', true)->count();
                                            $pendU = $totU - $compU;
                                            $pctU = $totU > 0 ? (int) round(($compU / $totU) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bold text-dark">
                                                <i class="fa fa-user-circle text-primary mr-2"></i>{{ $u->name }}
                                                <small class="text-muted d-block">{{ $u->email }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light border text-dark">{{ $evento->responsables->contains('id', $u->id) ? 'Organizador Oficial' : 'Asignado a Tareas' }}</span>
                                            </td>
                                            <td class="text-center font-weight-bold">{{ $totU }}</td>
                                            <td class="text-center text-success font-weight-bold">{{ $compU }}</td>
                                            <td class="text-center text-warning font-weight-bold">{{ $pendU }}</td>
                                            <td style="min-width:140px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 mr-2" style="height:6px; border-radius:4px;">
                                                        <div class="progress-bar bg-success" style="width: {{ $pctU }}%;"></div>
                                                    </div>
                                                    <small class="font-weight-bold">{{ $pctU }}%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-3 text-muted">No hay responsables asignados aún.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Crear / Editar Paso --}}
<div class="modal fade" id="modalPasoForm" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                <div>
                    <span class="badge badge-light text-dark font-weight-bold mb-1" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">
                        <i class="fa fa-layer-group text-success mr-1"></i> FASE / HITO DEL EVENTO
                    </span>
                    <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalPasoTitulo">
                        Nuevo Paso / Hito
                    </h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;"><span>&times;</span></button>
            </div>
            <form id="formPaso">
                <input type="hidden" id="pasoId" name="paso_id" value="">
                <div class="modal-body p-4" style="background: #f8fafc; max-height: 75vh; overflow-y: auto;">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Nombre de la Fase / Paso <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pasoNombre" name="nombre" placeholder="Ej: Paso 1: Aprobar Actualización del Plan desde el MEF" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="pasoFechaInicio" name="fecha_inicio" value="{{ $evento->fecha_inicio ? $evento->fecha_inicio->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Fecha de Finalización</label>
                            <input type="date" class="form-control" id="pasoFechaFin" name="fecha_fin" value="{{ $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Responsable Principal</label>
                            <select class="form-control select2" id="pasoResponsablePrincipal" name="responsable_principal_id" style="width:100%;">
                                <option value="">-- Seleccionar Responsable Principal --</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Estado del Paso</label>
                            <select class="form-control select2" id="pasoEstado" name="estado" style="width:100%;">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="completado">Completado</option>
                                <option value="en_alerta">En Alerta / Vencido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Co-Responsables del Paso</label>
                        <select class="form-control select2" id="pasoCoResponsables" name="co_responsables[]" multiple="multiple" style="width:100%;">
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1">Descripción / Objetivos de este Paso</label>
                        <textarea class="form-control" id="pasoDescripcion" name="descripcion" rows="2" placeholder="Detalla qué se debe lograr en este paso..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 font-weight-bold" id="btnGuardarPaso" style="border-radius: 8px;">
                        <i class="fa fa-save mr-1"></i> Guardar Paso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Crear / Editar Tarea --}}
<div class="modal fade" id="modalTareaForm" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div>
                    <span class="badge badge-light text-dark font-weight-bold mb-1" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">
                        <i class="fa fa-tasks text-info mr-1"></i> CHECKLIST OPERATIVO
                    </span>
                    <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalTareaTitulo">
                        Nueva Tarea Operativa
                    </h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;"><span>&times;</span></button>
            </div>
            <form id="formTarea">
                <input type="hidden" id="tareaId" name="tarea_id" value="">
                <input type="hidden" id="tareaPasoId" name="paso_id" value="">
                <div class="modal-body p-4" style="background: #f8fafc;">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Nombre de la Tarea <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tareaNombre" name="nombre" placeholder="Ej: Comprar insumos y coffee break" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Responsable Asignado</label>
                        <select class="form-control select2" id="tareaResponsableId" name="responsable_id" style="width:100%;">
                            <option value="">-- Sin Responsable Asignado --</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Fecha Límite</label>
                            <input type="date" class="form-control" id="tareaFechaLimite" name="fecha_limite">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Prioridad</label>
                            <select class="form-control select2" id="tareaPrioridad" name="prioridad" style="width:100%;">
                                <option value="media" selected>Media</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1">Costo Estimado (Gs.)</label>
                        <input type="number" class="form-control" id="tareaCosto" name="costo_estimado" placeholder="0" min="0" step="1000">
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1">Detalle / Instrucciones</label>
                        <textarea class="form-control" id="tareaDescripcion" name="descripcion" rows="2" placeholder="Especificaciones para la realización de la tarea..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                    <button type="submit" class="btn btn-info btn-sm px-4 font-weight-bold" id="btnGuardarTarea" style="border-radius: 8px;">
                        <i class="fa fa-save mr-1"></i> Guardar Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('.select2').select2();

    var currentEventoId = '{{ $evento->id }}';

    // ── GESTIÓN DE PASOS ─────────────────────────────────────────────

    $('#btnNuevoPasoModal, #btnNuevoPasoVacio').on('click', function() {
        $('#formPaso')[0].reset();
        $('#pasoId').val('');
        $('#pasoResponsablePrincipal').val('').trigger('change');
        $('#pasoCoResponsables').val([]).trigger('change');
        $('#pasoEstado').val('pendiente').trigger('change');
        $('#modalPasoTitulo').html('<i class="fa fa-plus-circle text-white mr-1"></i> Nuevo Paso / Hito');
        $('#modalPasoForm').modal('show');
    });

    $(document).on('click', '.btnEditarPasoModal', function() {
        var paso = $(this).data('paso');
        $('#pasoId').val(paso.id);
        $('#pasoNombre').val(paso.nombre);
        $('#pasoFechaInicio').val(paso.fecha_inicio ? paso.fecha_inicio.substring(0, 10) : '');
        $('#pasoFechaFin').val(paso.fecha_fin ? paso.fecha_fin.substring(0, 10) : '');
        $('#pasoResponsablePrincipal').val(paso.responsable_principal_id || '').trigger('change');
        $('#pasoEstado').val(paso.estado).trigger('change');
        $('#pasoDescripcion').val(paso.descripcion);

        var coRespIds = paso.responsables ? paso.responsables.map(function(r) { return r.id; }) : [];
        $('#pasoCoResponsables').val(coRespIds).trigger('change');

        $('#modalPasoTitulo').html('<i class="fa fa-pencil-alt text-white mr-1"></i> Editar Paso');
        $('#modalPasoForm').modal('show');
    });

    $('#formPaso').on('submit', function(e) {
        e.preventDefault();
        var id = $('#pasoId').val();
        var url = id ? '/admin/eventos/' + currentEventoId + '/pasos/' + id : '/admin/eventos/' + currentEventoId + '/pasos';
        var formData = $(this).serialize();
        if (id) formData += '&_method=PUT';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(resp) {
                $('#modalPasoForm').modal('hide');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: resp.message,
                    showConfirmButton: false,
                    timer: 2000
                });
                setTimeout(function() { window.location.reload(); }, 600);
            },
            error: function(xhr) {
                Swal.fire('Atención', xhr.responseJSON ? xhr.responseJSON.message : 'Error al guardar el paso', 'warning');
            }
        });
    });

    $(document).on('click', '.btnEliminarPaso', function() {
        var pasoId = $(this).data('paso-id');
        var pasoNombre = $(this).data('paso-nombre');

        Swal.fire({
            title: '¿Eliminar Fase / Paso?',
            text: 'Se eliminará "' + pasoNombre + '" y todas sus tareas asociadas.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/eventos/' + currentEventoId + '/pasos/' + pasoId,
                    type: 'DELETE',
                    success: function(resp) {
                        $('#paso-card-' + pasoId).slideUp(300, function() { $(this).remove(); });
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: resp.message, showConfirmButton: false, timer: 2000 });
                    }
                });
            }
        });
    });

    // ── GESTIÓN DE TAREAS (CHECKLIST) ────────────────────────────────

    $(document).on('click', '.btnNuevaTareaModal', function() {
        var pasoId = $(this).data('paso-id');
        var pasoNombre = $(this).data('paso-nombre');

        $('#formTarea')[0].reset();
        $('#tareaId').val('');
        $('#tareaPasoId').val(pasoId);
        $('#tareaResponsableId').val('').trigger('change');
        $('#tareaPrioridad').val('media').trigger('change');
        $('#modalTareaTitulo').html('<i class="fa fa-plus-circle text-white mr-1"></i> Nueva Tarea (' + pasoNombre + ')');
        $('#modalTareaForm').modal('show');
    });

    $(document).on('click', '.btnEditarTareaModal', function() {
        var t = $(this).data('tarea');
        $('#tareaId').val(t.id);
        $('#tareaPasoId').val(t.evento_paso_id);
        $('#tareaNombre').val(t.nombre);
        $('#tareaResponsableId').val(t.responsable_id || '').trigger('change');
        $('#tareaFechaLimite').val(t.fecha_limite ? t.fecha_limite.substring(0, 10) : '');
        $('#tareaPrioridad').val(t.prioridad || 'media').trigger('change');
        $('#tareaCosto').val(t.costo_estimado || 0);
        $('#tareaDescripcion').val(t.descripcion);

        $('#modalTareaTitulo').html('<i class="fa fa-pencil-alt text-white mr-1"></i> Editar Tarea');
        $('#modalTareaForm').modal('show');
    });

    $('#formTarea').on('submit', function(e) {
        e.preventDefault();
        var id = $('#tareaId').val();
        var pasoId = $('#tareaPasoId').val();
        var url = id ? '/admin/eventos/tareas/' + id : '/admin/eventos/pasos/' + pasoId + '/tareas';
        var formData = $(this).serialize();
        if (id) formData += '&_method=PUT';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(resp) {
                $('#modalTareaForm').modal('hide');
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: resp.message, showConfirmButton: false, timer: 2000 });
                setTimeout(function() { window.location.reload(); }, 600);
            },
            error: function(xhr) {
                Swal.fire('Atención', xhr.responseJSON ? xhr.responseJSON.message : 'Error al guardar la tarea', 'warning');
            }
        });
    });

    // Toggle Checkbox Tarea Instantáneo
    $(document).on('change', '.checkboxTareaToggle', function() {
        var chk = $(this);
        var tareaId = chk.data('tarea-id');
        var isChecked = chk.is(':checked');

        $.ajax({
            url: '/admin/eventos/tareas/' + tareaId + '/toggle',
            type: 'PATCH',
            success: function(resp) {
                var row = $('#tarea-row-' + tareaId);
                var nombreText = $('#tarea-nombre-' + tareaId);
                var metaText = $('#tarea-meta-' + tareaId);

                if (resp.completada) {
                    row.addClass('table-success-soft');
                    nombreText.addClass('text-muted text-decoration-line-through').removeClass('text-dark');
                    metaText.html('<i class="fa fa-check-double mr-1"></i>Completada por ' + (resp.completada_por || 'Usuario') + ' el ' + (resp.completada_el || '')).show();
                } else {
                    row.removeClass('table-success-soft');
                    nombreText.removeClass('text-muted text-decoration-line-through').addClass('text-dark');
                    metaText.hide();
                }

                // Actualizar barras de avance
                if (resp.avance_evento !== undefined) {
                    $('#eventoAvanceBar').css('width', resp.avance_evento + '%');
                    $('#eventoAvancePctTexto').text(resp.avance_evento + '%');
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: resp.completada ? 'success' : 'info',
                    title: resp.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function() {
                chk.prop('checked', !isChecked);
                Swal.fire('Error', 'No se pudo actualizar el estado de la tarea.', 'error');
            }
        });
    });

    // Eliminar Tarea
    $(document).on('click', '.btnEliminarTarea', function() {
        var tareaId = $(this).data('tarea-id');
        var tareaNombre = $(this).data('tarea-nombre');

        Swal.fire({
            title: '¿Eliminar Tarea?',
            text: 'Se removerá "' + tareaNombre + '" del checklist.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/eventos/tareas/' + tareaId,
                    type: 'DELETE',
                    success: function(resp) {
                        $('#tarea-row-' + tareaId).fadeOut(300, function() { $(this).remove(); });
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: resp.message, showConfirmButton: false, timer: 2000 });
                    }
                });
            }
        });
    });

    // ── CALENDARIO FULLCALENDAR DEL EVENTO ─────────────────────────────
    var calendarLoaded = false;
    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr('id') === 'tab-calendario-btn' && !calendarLoaded) {
            calendarLoaded = true;
            $('#fullcalendarEvento').fullCalendar({
                locale: 'es',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,listMonth'
                },
                defaultDate: '{{ $evento->fecha_inicio ? $evento->fecha_inicio->format("Y-m-d") : date("Y-m-d") }}',
                events: '/admin/eventos/feed?evento_id=' + currentEventoId,
                eventClick: function(calEvent, jsEvent, view) {
                    if (calEvent.url && !calEvent.url.includes('#')) {
                        // Navegar o abrir modal
                    }
                }
            });
        }
    });
});
</script>
<style>
.table-success-soft {
    background-color: #f0fdf4 !important;
}
.text-decoration-line-through {
    text-decoration: line-through;
}
.fc-evento-principal {
    border-radius: 6px !important;
    font-weight: bold !important;
    font-size: 0.85rem !important;
    padding: 3px 6px !important;
}
.fc-evento-paso {
    border-radius: 4px !important;
    font-size: 0.8rem !important;
}
.fc-evento-tarea {
    border-radius: 3px !important;
    font-size: 0.75rem !important;
    opacity: 0.9;
}
.fc-tarea-completada {
    text-decoration: line-through;
    opacity: 0.65;
}
</style>
@endsection
