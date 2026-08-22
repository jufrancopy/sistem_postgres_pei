@extends('layouts.master')

@section('title', 'Expediente MECIP IPS Nº ' . $caso->numero_caso)

@push('styles')
<style>
/* CSS para el Diagrama de Nodos de Actividades en Cascada */
.node-flow-container {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 20px 10px;
    gap: 20px;
    align-items: stretch;
    background: #0f172a;
    border-radius: 14px;
    margin-bottom: 24px;
}
.node-flow-item {
    min-width: 260px;
    max-width: 290px;
    background: #1e293b;
    border: 2px solid #334155;
    border-radius: 12px;
    color: #f8fafc;
    padding: 16px;
    position: relative;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.node-flow-item.active-node {
    border-color: #38bdf8;
    background: #0f172a;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
}
.node-flow-connector {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #38bdf8;
    font-size: 1.5rem;
    align-self: center;
}
.node-task-list {
    background: #0f172a;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.78rem;
    margin-top: 10px;
    border: 1px dashed #475569;
}
</style>
@endpush

@section('content')
<div class="content-wrapper p-3 p-md-4 bg-light">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
        <div class="card-header card-header-info d-flex flex-wrap align-items-center justify-content-between">
            <h4 class="card-title text-white font-weight-bold mb-0">
                <i class="fas fa-folder-open mr-2"></i> Expediente MECIP IPS: Caso #{{ $caso->numero_caso }}
            </h4>
            <span class="badge {{ $caso->estado_badge }} font-weight-bold px-3 py-1.5" style="font-size: 0.88rem;">
                {{ $caso->estado_label }}
            </span>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.mecip.control.index') }}">Control MECIP</a></li>
                <li class="breadcrumb-item active" aria-current="page">Caso #{{ $caso->numero_caso }}</li>
            </ol>
        </nav>

        {{-- Banner de Acciones y Dictamen --}}
        <div class="row px-3 mb-4">
            <div class="col-md-12">
                <div class="p-4 rounded-3 shadow-sm text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border-radius: 14px; gap: 15px;">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1" style="gap: 8px;">
                            <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1" style="border-radius: 6px; font-size: 0.72rem;">
                                COD: {{ $caso->codigo_subproceso }}
                            </span>
                            <span class="text-white-50 small">Versión {{ $caso->version }} &bull; Elaborado: {{ $caso->fecha_elaboracion?->format('d/m/Y') ?: 'Sin fecha' }}</span>
                        </div>
                        <h3 class="font-weight-bold text-white mb-1" style="font-size: 1.35rem;">
                            {{ $caso->subproceso }}
                        </h3>
                        <p class="text-white-50 mb-0 small" style="max-width: 800px; line-height: 1.4;">
                            <i class="fas fa-sitemap mr-1 text-info"></i> {{ $caso->macroproceso }} &bull; <strong>Proceso:</strong> {{ $caso->proceso }} &bull; <strong>Responsable de Análisis:</strong> {{ $caso->responsable_analisis ?: 'IPS' }}
                        </p>
                    </div>

                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        @if($isAdmin)
                            <button type="button" class="btn btn-warning text-dark font-weight-bold px-3 shadow-sm" data-toggle="modal" data-target="#modalRemitirLider">
                                <i class="fas fa-paper-plane mr-1"></i> Remitir a Líder MECIP
                            </button>
                        @endif

                        @if($isLider || $isAdmin)
                            <button type="button" class="btn btn-info font-weight-bold px-3 shadow-sm text-white" data-toggle="modal" data-target="#modalResolverElemento">
                                <i class="fas fa-check-double mr-1"></i> Resolver & Justificar Camino
                            </button>
                        @endif

                        @if($isAdmin)
                            <button type="button" class="btn btn-success font-weight-bold px-3 shadow-sm" data-toggle="modal" data-target="#modalCerrarAnalisis">
                                <i class="fas fa-lock mr-1"></i> Aprobar & Cerrar Análisis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Bloque de Detalle Completo & Antecedentes del Expediente BPM IPS --}}
        @if($caso->comentarios->count() > 0)
        <div class="row px-3 mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #ffffff; border-left: 5px solid #0d6efd !important;">
                    <div class="card-body p-3.5">
                        <h6 class="font-weight-bold text-primary mb-1.5" style="font-size: 0.98rem;">
                            <i class="fas fa-file-alt mr-1"></i> Detalle Completo & Observaciones del Expediente BPM IPS:
                        </h6>
                        <p class="text-dark mb-0 font-italic bg-light p-3 rounded border" style="font-size: 0.93rem; line-height: 1.5; white-space: pre-wrap;">
                            "{{ $caso->comentarios->first()->comentario }}"
                        </p>
                        <div class="text-muted small mt-2.5 d-flex flex-wrap align-items-center justify-content-between">
                            <span><i class="fas fa-user-circle text-info mr-1"></i> Origen: <strong>{{ $caso->comentarios->first()->rol_usuario }}</strong></span>
                            <span><i class="far fa-clock text-info mr-1"></i> Sincronizado: {{ $caso->comentarios->first()->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- DIAGRAMA DE NODOS DE ACTIVIDADES EN CASCADA --}}
        <div class="row px-3">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-project-diagram text-info mr-2"></i> Diagrama Visual de Nodos de Actividades en Cascada
                    </h5>
                    <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" data-toggle="modal" data-target="#modalAgregarActividad">
                        <i class="fas fa-plus mr-1"></i> Agregar Actividad
                    </button>
                </div>

                <div class="node-flow-container">
                    @forelse($caso->actividades as $idx => $act)
                        <div class="node-flow-item {{ $act->estado_revision === 'resuelto' ? 'active-node' : '' }}">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge badge-info font-mono font-weight-bold px-2 py-0.5" style="font-family: monospace;">
                                        {{ $act->codigo_actividad ?: ('ACT_' . ($idx + 1)) }}
                                    </span>
                                    <span class="small font-weight-bold text-warning">
                                        <i class="far fa-clock mr-1"></i> {{ $act->tiempo_total_minutos }} min
                                    </span>
                                </div>
                                <h6 class="font-weight-bold text-white mb-1" style="font-size: 0.95rem; line-height: 1.3;">
                                    {{ $act->nombre }}
                                </h6>
                                @if($act->responsable)
                                    <small class="text-info d-block mb-2 font-weight-bold" style="font-size: 0.76rem;">
                                        <i class="fas fa-user-tie mr-1"></i> {{ $act->responsable }}
                                    </small>
                                @endif

                                @if($act->objetivo)
                                    <p class="text-white-50 small mb-2 font-italic" style="font-size: 0.75rem; line-height: 1.3;">
                                        "{{ \Illuminate\Support\Str::limit($act->objetivo, 80) }}"
                                    </p>
                                @endif

                                {{-- Lista de Tareas --}}
                                <div class="node-task-list">
                                    <strong class="text-warning d-block mb-1 font-weight-bold" style="font-size: 0.72rem;">
                                        <i class="fas fa-tasks mr-1"></i> Tareas ({{ $act->tareas->count() }}):
                                    </strong>
                                    @forelse($act->tareas as $t)
                                        <div class="d-flex justify-content-between text-slate-300 py-0.5 border-bottom border-slate-700" style="font-size: 0.72rem; color: #cbd5e1;">
                                            <span>&bull; {{ $t->descripcion }}</span>
                                            <span class="text-warning font-weight-bold ml-1">{{ $t->tiempo_estimado_minutos }}m</span>
                                        </div>
                                    @empty
                                        <span class="text-muted font-italic" style="font-size: 0.7rem;">Sin tareas cargadas</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-3 pt-2 border-top border-slate-700 text-right">
                                <button type="button" class="btn btn-xs btn-outline-info text-white font-weight-bold px-2 py-0.5" onclick="abrirModalAgregarTarea({{ $act->id }}, '{{ addslashes($act->nombre) }}')">
                                    <i class="fas fa-plus mr-1"></i> Tarea
                                </button>
                            </div>
                        </div>

                        @if(!$loop->last)
                            <div class="node-flow-connector">
                                <i class="fas fa-long-arrow-alt-right"></i>
                            </div>
                        @endif
                    @empty
                        <div class="p-4 text-center text-white-50 w-100">
                            <i class="fas fa-info-circle fa-2x mb-2 text-warning d-block"></i>
                            Sin actividades registradas en esta secuencia. Utilizá el botón "Agregar Actividad" o la importación rápida JSON.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TABLAS ESTRUCTURADAS COMPLETAS MECIP BPM --}}
        
        {{-- 1. TABLA COMPLETA DE ACTIVIDADES Y TAREAS --}}
        <div class="row px-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white font-weight-bold d-flex align-items-center justify-content-between py-3">
                        <span><i class="fas fa-tasks text-info mr-2"></i> 📋 Matriz Completa de Actividades y Tareas del Subproceso</span>
                        <span class="badge badge-light text-dark font-weight-bold">{{ $caso->actividades->count() }} Actividad(es)</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-dark data-table mb-0" id="tblMatrizActividades" style="width:100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 100px;" class="text-center">Código</th>
                                        <th>Nombre de la Actividad / Procedimiento</th>
                                        <th style="width: 180px;">Responsable</th>
                                        <th>Tareas e Instrucciones Asociadas</th>
                                        <th style="width: 120px;" class="text-center">Tiempo Est.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($caso->actividades as $act)
                                        @php
                                            $tiempoTotalAct = $act->tareas->sum('tiempo_estimado_minutos');
                                        @endphp
                                        <tr>
                                            <td class="text-center align-middle font-weight-bold">
                                                <span class="badge badge-info px-2 py-1">{{ $act->codigo_actividad }}</span>
                                            </td>
                                            <td class="align-middle font-weight-bold text-dark">{{ $act->nombre }}</td>
                                            <td class="align-middle text-muted small"><i class="fas fa-user-tag text-info mr-1"></i> {{ $act->responsable ?: 'Analista IPS' }}</td>
                                            <td class="align-middle">
                                                @if($act->tareas->count() > 0)
                                                    <ul class="pl-3 mb-0 text-dark small" style="line-height: 1.5;">
                                                        @foreach($act->tareas as $tar)
                                                            <li>
                                                                <strong class="text-dark">{{ $tar->descripcion }}</strong>
                                                                @if($tar->tiempo_estimado_minutos)
                                                                    <span class="badge badge-light border text-muted ml-1">{{ $tar->tiempo_estimado_minutos }}m</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted font-italic small">Sin tareas secundarias desglosadas.</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle font-weight-bold text-dark">
                                                {{ $tiempoTotalAct ?: 30 }} min
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted p-4">No hay actividades registradas en este subproceso.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. TABLAS DE PRODUCTOS (CLIENTES) E INSUMOS (PROVEEDORES) SEPARADAS --}}
        <div class="row px-3 mb-4">
            {{-- Productos (Clientes) --}}
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white font-weight-bold text-dark border-bottom py-3 d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-box-open text-success mr-2"></i> 📦 Productos Salientes (Clientes / Entregables)</span>
                        <span class="badge badge-success font-weight-bold">{{ $caso->productos->count() }} Producto(s)</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover data-table text-dark mb-0" id="tblProductosClientes" style="width:100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nombre del Producto / Entregable</th>
                                        <th>Cliente / Entidad Destino</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($caso->productos as $prod)
                                        <tr>
                                            <td class="align-middle font-weight-bold text-dark">
                                                <i class="fas fa-file-alt text-success mr-1"></i> {{ $prod->nombre }}
                                            </td>
                                            <td class="align-middle text-dark small font-weight-bold">{{ $prod->entidad_origen_destino ?: 'Consejo de Administración / Dirección' }}</td>
                                            <td class="align-middle text-muted small">{{ $prod->descripcion ?: 'Documento final del procedimiento' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted p-3">No hay productos registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Insumos (Proveedores) --}}
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white font-weight-bold text-dark border-bottom py-3 d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-file-import text-info mr-2"></i> 📥 Insumos Entrantes (Proveedores / Requisitos)</span>
                        <span class="badge badge-info font-weight-bold">{{ $caso->insumos->count() }} Insumo(s)</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover data-table text-dark mb-0" id="tblInsumosProveedores" style="width:100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nombre del Insumo / Requisito</th>
                                        <th>Proveedor / Entidad Origen</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($caso->insumos as $ins)
                                        <tr>
                                            <td class="align-middle font-weight-bold text-dark">
                                                <i class="fas fa-folder-open text-info mr-1"></i> {{ $ins->nombre }}
                                            </td>
                                            <td class="align-middle text-dark small font-weight-bold">{{ $ins->entidad_origen_destino ?: 'Unidad Solicitante IPS' }}</td>
                                            <td class="align-middle text-muted small">{{ $ins->descripcion ?: 'Antecedentes de entrada para el procedimiento' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted p-3">No hay insumos registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dictamen Final --}}
        <div class="row px-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white font-weight-bold text-dark border-bottom py-3">
                        <i class="fas fa-clipboard-check text-success mr-2"></i> Dictamen Final & Cierre del Análisis
                    </div>
                    <div class="card-body p-4">
                        @if($caso->dictamen_final)
                            <div class="alert alert-success border-0 shadow-sm mb-0" style="border-radius: 10px; border-left: 5px solid #22c55e !important;">
                                <h6 class="font-weight-bold text-success mb-1"><i class="fas fa-award mr-1"></i> Dictamen Aprobado por Administrador:</h6>
                                <p class="mb-0 text-dark font-italic" style="font-size: 0.92rem;">
                                    "{{ $caso->dictamen_final }}"
                                </p>
                            </div>
                        @else
                            <div class="alert alert-warning border-0 shadow-sm text-dark mb-0" style="border-radius: 10px; border-left: 5px solid #eab308 !important;">
                                <i class="fas fa-hourglass-half mr-2 text-warning"></i>
                                Expediente en proceso de análisis y revisión con el Líder MECIP. El dictamen final se registrará al cerrar el caso.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- DEBATE DE JUSTIFICACIÓN DE DECISIONES Y AUDITORÍA DE CAMBIOS (DATATABLES) --}}
        <div class="row px-3 mb-4">
            <div class="col-md-7 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-3">
                        <h6 class="font-weight-bold text-white mb-0">
                            <i class="fas fa-comments text-warning mr-2"></i> Justificaciones de Decisiones & Argumentaciones (Líder MECIP)
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover data-table text-dark" id="tblHistorialComentarios" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 140px;">Usuario / Rol</th>
                                        <th>Comentario & Justificación del Camino Tomado</th>
                                        <th style="width: 110px;" class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($caso->comentarios as $com)
                                        <tr>
                                            <td class="align-middle">
                                                <strong class="text-dark d-block small">{{ $com->user->name ?? 'Usuario' }}</strong>
                                                <span class="badge badge-info font-weight-bold px-2 py-0.5" style="font-size: 0.68rem;">
                                                    {{ $com->rol_usuario }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <p class="mb-1 text-dark font-weight-bold" style="font-size: 0.88rem;">{{ $com->comentario }}</p>
                                                @if($com->justificacion_camino)
                                                    <div class="p-2 rounded bg-light border-left border-warning" style="border-left-width: 4px !important; font-size: 0.8rem;">
                                                        <strong class="text-warning font-weight-bold d-block"><i class="fas fa-lightbulb mr-1"></i> Justificación de Decisión:</strong>
                                                        <span class="text-dark font-italic">{{ $com->justificacion_camino }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center small text-muted font-weight-bold">
                                                {{ $com->created_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white font-weight-bold text-dark border-bottom py-3">
                        <i class="fas fa-history text-info mr-2"></i> Bitácora de Auditoría & Transiciones de Estado
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover data-table text-dark" id="tblAuditoriaCambios" style="width:100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Usuario</th>
                                        <th style="width: 120px;" class="text-center">Transición</th>
                                        <th style="width: 100px;" class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($caso->cambios as $cam)
                                        <tr>
                                            <td class="align-middle small font-weight-bold">{{ $cam->user->name ?? 'Sistema' }}</td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-light border text-dark px-2 py-0.5 small">
                                                    {{ $cam->estado_anterior }} &rarr; {{ $cam->estado_nuevo }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center small text-muted font-weight-bold">
                                                {{ $cam->created_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Remitir a Líder MECIP (*Select2) --}}
<div class="modal fade" id="modalRemitirLider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.mecip.control.remitirALider', $caso->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-warning text-dark p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold mb-0">
                    <i class="fas fa-paper-plane mr-2"></i> Remitir Caso a Líder MECIP
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small"><i class="fas fa-user-shield text-info mr-1"></i> Seleccionar Líder MECIP (* Select2)</label>
                    <select name="lider_mecip_id" id="selectLiderRemitir" class="form-control select2" style="width: 100%;" required>
                        <option value="">-- Seleccionar Líder MECIP --</option>
                        @foreach($lideresMecip as $lider)
                            <option value="{{ $lider->id }}" {{ $caso->lider_mecip_id == $lider->id ? 'selected' : '' }}>{{ $lider->name }} ({{ $lider->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small">Observaciones o Instrucciones adicionales</label>
                    <textarea name="observacion" class="form-control" rows="3" placeholder="Ingresá observaciones para el Líder MECIP..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning text-dark font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-paper-plane mr-1"></i> Remitir Expediente
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Resolver & Justificar Camino (Líder MECIP) --}}
<div class="modal fade" id="modalResolverElemento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.mecip.control.resolverElemento', $caso->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-info text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fas fa-check-double mr-2"></i> Resolver Elemento & Argumentar Decisión (Líder MECIP)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small">Actividad / Elemento (* Select2)</label>
                    <select name="actividad_id" id="selectActividadResolver" class="form-control select2" style="width: 100%;">
                        <option value="">-- Aplica a todo el expediente --</option>
                        @foreach($caso->actividades as $act)
                            <option value="{{ $act->id }}">{{ $act->codigo_actividad ?: 'ACT' }} - {{ $act->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small"><i class="fas fa-comment-alt text-info mr-1"></i> Dictamen / Comentario del Ajuste (*)</label>
                    <textarea name="comentario" class="form-control" rows="3" placeholder="Describí el ajuste realizado..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small"><i class="fas fa-lightbulb text-warning mr-1"></i> Justificación de la Decisión / Camino Tomado (*)</label>
                    <textarea name="justificacion_camino" class="form-control font-weight-bold text-dark" rows="4" placeholder="Argumentá técnicamente por qué se eligió determinado camino o automatización..." required></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-info font-weight-bold px-4 shadow-sm text-white">
                    <i class="fas fa-check mr-1"></i> Registrar Resolución & Justificación
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Aprobar & Cerrar Análisis (Administrador) --}}
<div class="modal fade" id="modalCerrarAnalisis" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.mecip.control.cerrarAnalisis', $caso->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-success text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fas fa-lock mr-2"></i> Aprobar & Cerrar Análisis de Expediente
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small"><i class="fas fa-award text-success mr-1"></i> Dictamen Final de Aprobación (*)</label>
                    <textarea name="dictamen_final" class="form-control font-weight-bold text-dark" rows="4" placeholder="Ingresá el visto bueno final y dictamen de cierre del análisis..." required></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-file-signature mr-1"></i> Aprobar & Cerrar Análisis
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Agregar Actividad --}}
<div class="modal fade" id="modalAgregarActividad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.mecip.control.agregarActividad', $caso->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-primary text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0"><i class="fas fa-plus-circle mr-2"></i> Agregar Actividad</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small">Nombre de la Actividad (*)</label>
                    <input type="text" name="nombre" class="form-control text-dark font-weight-bold" placeholder="Ej. Validación de Requisitos IPS" required>
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small">Responsable</label>
                    <input type="text" name="responsable" class="form-control text-dark" placeholder="Ej. Analista de Calidad">
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small">Objetivo / Descripción</label>
                    <textarea name="objetivo" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm">Agregar Actividad</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Agregar Tarea a Actividad --}}
<div class="modal fade" id="modalAgregarTarea" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formAgregarTarea" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-dark text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="titleAgregarTarea"><i class="fas fa-tasks mr-2"></i> Agregar Tarea</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small">Descripción de la Tarea (*)</label>
                    <input type="text" name="descripcion" class="form-control text-dark font-weight-bold" placeholder="Ej. Verificar firma digital del dictamen" required>
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small">Tiempo Estimado (Minutos) (*)</label>
                    <input type="number" name="tiempo_estimado_minutos" class="form-control text-dark font-weight-bold" value="15" min="1" required>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-dark font-weight-bold px-4 shadow-sm">Guardar Tarea</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Inicializar DataTables en todas las tablas
    if ($.fn.DataTable) {
        $('#tblInsumosProductos').DataTable({
            pageLength: 5,
            lengthMenu: [[5, 10, -1], [5, 10, "Todos"]],
            language: { search: "Buscar componente:", lengthMenu: "Mostrar _MENU_" }
        });

        $('#tblHistorialComentarios').DataTable({
            pageLength: 5,
            order: [],
            lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "Todos"]],
            language: { search: "Buscar justificación:", lengthMenu: "Mostrar _MENU_" }
        });

        $('#tblAuditoriaCambios').DataTable({
            pageLength: 5,
            order: [],
            lengthMenu: [[5, 10, -1], [5, 10, "Todos"]],
            language: { search: "Buscar cambio:", lengthMenu: "Mostrar _MENU_" }
        });
    }

    // Escuchar notificaciones de Redis en tiempo real
    if (window.Echo) {
        window.Echo.channel('mecip-notificaciones')
            .listen('.mecip.notificacion', (e) => {
                if (e.casoId == {{ $caso->id }}) {
                    toastr.success(e.mensaje, '🚀 Actualización MECIP IPS (Redis)');
                    setTimeout(() => location.reload(), 1500);
                }
            });
    }
});

function abrirModalAgregarTarea(actividadId, nombreActividad) {
    const actionUrl = "{{ url('admin/mecip/control/actividades') }}/" + actividadId + "/tareas";
    document.getElementById('formAgregarTarea').action = actionUrl;
    document.getElementById('titleAgregarTarea').innerHTML = '<i class="fas fa-tasks mr-2"></i> Agregar Tarea a: ' + nombreActividad;
    $('#modalAgregarTarea').modal('show');
}

$('#formAgregarTarea').on('submit', function(e) {
    e.preventDefault();
    var actionUrl = $(this).attr('action');
    var $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            $btn.prop('disabled', false).html('Guardar Tarea');
            $('#modalAgregarTarea').modal('hide');
            toastr.success('Tarea agregada exitosamente al expediente.');
            setTimeout(function() {
                location.reload();
            }, 800);
        },
        error: function(err) {
            $btn.prop('disabled', false).html('Guardar Tarea');
            var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error al guardar la tarea.';
            toastr.error(msg);
        }
    });
});

$('#modalAgregarActividad form').on('submit', function(e) {
    e.preventDefault();
    var actionUrl = $(this).attr('action');
    var $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Agregando...');

    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            $btn.prop('disabled', false).html('Agregar Actividad');
            $('#modalAgregarActividad').modal('hide');
            toastr.success('Actividad agregada al diagrama de procesos.');
            setTimeout(function() {
                location.reload();
            }, 800);
        },
        error: function(err) {
            $btn.prop('disabled', false).html('Agregar Actividad');
            var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error al agregar la actividad.';
            toastr.error(msg);
        }
    });
});
</script>
@endpush
@endsection
