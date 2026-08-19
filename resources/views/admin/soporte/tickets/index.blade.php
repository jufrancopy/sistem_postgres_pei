@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            
            {{-- Tarjeta Principal con Diseño Estándar del Sistema --}}
            <div class="card">
                <div class="card-header card-header-primary d-flex align-items-center justify-content-between flex-wrap py-3 px-4">
                    <div>
                        <h4 class="card-title font-weight-bold text-white mb-1">
                            <i class="fa fa-ticket-alt mr-2"></i>Bandeja de Tickets & Reportes de Falla
                        </h4>
                        <p class="card-category text-white-50 mb-0">Atención rápida de incidencias de usuarios del sistema</p>
                    </div>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <button type="button" class="btn btn-purple font-weight-bold shadow-sm" onclick="copiarPromptLotePendientesIa()" title="Copiar prompt con todas las fallas pendientes para pegar en el chat de Antigravity AI">
                            <i class="fa fa-robot mr-1"></i> 🤖 Copiar Pendientes para IA
                        </button>
                        <button type="button" class="btn btn-success font-weight-bold shadow-sm" onclick="abrirModalReportarFalla()">
                            <i class="fa fa-plus-circle mr-1"></i> Crear Ticket Interno
                        </button>
                    </div>
                </div>

                <style>
                    .btn-purple {
                        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
                        color: #ffffff !important;
                        border: none;
                    }
                    .btn-purple:hover {
                        background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
                        color: #ffffff !important;
                        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
                    }
                    .btn-outline-purple {
                        color: #7c3aed !important;
                        border: 1px solid #7c3aed;
                        background: transparent;
                    }
                    .btn-outline-purple:hover {
                        background: #7c3aed;
                        color: #ffffff !important;
                    }
                </style>

                <div class="card-body p-4">

                    {{-- Tarjetas KPI de Resumen --}}
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                            <div class="card card-stats border-0 shadow-xs rounded-lg" style="border-left: 4px solid #64748b !important; background: #f8fafc;">
                                <div class="card-header card-header-secondary card-header-icon py-2">
                                    <div class="card-icon bg-secondary text-white py-2 px-3 rounded shadow-xs">
                                        <i class="material-icons">layers</i>
                                    </div>
                                    <p class="card-category text-uppercase font-weight-bold text-muted" style="font-size: 0.68rem;">Total Registrados</p>
                                    <h3 class="card-title font-weight-bold text-dark mb-0">{{ number_format($counts['total']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                            <div class="card card-stats border-0 shadow-xs rounded-lg" style="border-left: 4px solid #f59e0b !important; background: #fefce8;">
                                <div class="card-header card-header-warning card-header-icon py-2">
                                    <div class="card-icon bg-warning text-dark py-2 px-3 rounded shadow-xs">
                                        <i class="material-icons">hourglass_empty</i>
                                    </div>
                                    <p class="card-category text-uppercase font-weight-bold text-warning" style="font-size: 0.68rem;">Pendientes de Atención</p>
                                    <h3 class="card-title font-weight-bold text-warning mb-0" id="kpiPendientesCount">{{ number_format($counts['pendiente']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                            <div class="card card-stats border-0 shadow-xs rounded-lg" style="border-left: 4px solid #3b82f6 !important; background: #eff6ff;">
                                <div class="card-header card-header-info card-header-icon py-2">
                                    <div class="card-icon bg-info text-white py-2 px-3 rounded shadow-xs">
                                        <i class="material-icons">autorenew</i>
                                    </div>
                                    <p class="card-category text-uppercase font-weight-bold text-primary" style="font-size: 0.68rem;">En Proceso de Solución</p>
                                    <h3 class="card-title font-weight-bold text-primary mb-0">{{ number_format($counts['en_proceso']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                            <div class="card card-stats border-0 shadow-xs rounded-lg" style="border-left: 4px solid #10b981 !important; background: #f0fdf4;">
                                <div class="card-header card-header-success card-header-icon py-2">
                                    <div class="card-icon bg-success text-white py-2 px-3 rounded shadow-xs">
                                        <i class="material-icons">check_circle</i>
                                    </div>
                                    <p class="card-category text-uppercase font-weight-bold text-success" style="font-size: 0.68rem;">Resueltos</p>
                                    <h3 class="card-title font-weight-bold text-success mb-0">{{ number_format($counts['resuelto']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Barra de Filtros Rápidos --}}
                    <div class="p-3 mb-3 bg-light rounded border">
                        <form method="GET" action="{{ route('admin.soporte.tickets.index') }}" class="form-row align-items-center">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select name="estado" class="form-control font-weight-bold" onchange="this.form.submit()">
                                    <option value="">-- Todos los Estados --</option>
                                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>🟡 Pendientes de Atención</option>
                                    <option value="en_proceso" {{ request('estado') === 'en_proceso' ? 'selected' : '' }}>🔵 En Proceso de Solución</option>
                                    <option value="resuelto" {{ request('estado') === 'resuelto' ? 'selected' : '' }}>🟢 Resueltos</option>
                                    <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>🔴 Rechazados</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select name="prioridad" class="form-control font-weight-bold" onchange="this.form.submit()">
                                    <option value="">-- Todas las Prioridades --</option>
                                    <option value="urgente" {{ request('prioridad') === 'urgente' ? 'selected' : '' }}>🔥 Urgentes / Bloqueantes</option>
                                    <option value="alta" {{ request('prioridad') === 'alta' ? 'selected' : '' }}>🔴 Alta Prioridad</option>
                                    <option value="media" {{ request('prioridad') === 'media' ? 'selected' : '' }}>🟡 Prioridad Media</option>
                                    <option value="baja" {{ request('prioridad') === 'baja' ? 'selected' : '' }}>🟢 Prioridad Baja</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-md-right">
                                @if(request()->anyFilled(['estado', 'prioridad']))
                                    <a href="{{ route('admin.soporte.tickets.index') }}" class="btn btn-sm btn-outline-secondary font-weight-bold">
                                        <i class="fa fa-sync-alt mr-1"></i> Limpiar Filtros
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Tabla Integrada con DataTables --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" id="tablaTicketsAdmin" style="font-size: 0.86rem;">
                            <thead class="text-primary font-weight-bold" style="font-size: 0.76rem;">
                                <tr>
                                    <th style="width: 130px;">Código & Fecha</th>
                                    <th style="width: 110px;">Prioridad</th>
                                    <th>Usuario que Reportó</th>
                                    <th>Falla / Asunto</th>
                                    <th>Ruta de la Falla</th>
                                    <th class="text-center" style="width: 100px;">Estado</th>
                                    <th class="text-right" style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $tk)
                                    @php
                                        $prioBadge = match($tk->prioridad) {
                                            'urgente' => 'badge-danger font-weight-bold px-2 py-1',
                                            'alta'    => 'badge-warning text-dark font-weight-bold',
                                            'media'   => 'badge-info',
                                            default   => 'badge-secondary',
                                        };
                                        $prioLabel = match($tk->prioridad) {
                                            'urgente' => '🔥 Urgente',
                                            'alta'    => '🔴 Alta',
                                            'media'   => '🟡 Media',
                                            default   => '🟢 Baja',
                                        };
                                        $estadoBadge = match($tk->estado) {
                                            'pendiente'  => 'badge-warning text-dark font-weight-bold',
                                            'en_proceso' => 'badge-primary',
                                            'resuelto'   => 'badge-success',
                                            'rechazado'  => 'badge-secondary',
                                        };
                                    @endphp
                                    <tr>
                                        {{-- Código --}}
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <strong class="text-dark" style="font-size: 0.88rem;">{{ $tk->codigo }}</strong>
                                                <span class="badge badge-success-light text-success font-weight-bold ml-1.5 px-1.5 py-0.5 d-none badge-ia-copiado" id="badge_copiado_{{ $tk->codigo }}" style="border-radius: 6px; font-size: 0.68rem; background: #dcfce7; color: #15803d !important; border: 1px solid #bbf7d0;" title="Este ticket ya fue copiado para Antigravity AI">
                                                    <i class="fa fa-check-circle mr-0.5"></i> IA ✓
                                                </span>
                                            </div>
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $tk->created_at->format('d/m/Y H:i') }}</small>
                                        </td>

                                        {{-- Prioridad --}}
                                        <td class="align-middle">
                                            <span class="badge {{ $prioBadge }}" style="font-size: 0.7rem;">
                                                {{ $prioLabel }}
                                            </span>
                                        </td>

                                        {{-- Usuario --}}
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-dark text-white font-weight-bold d-flex align-items-center justify-content-center mr-2" style="width:30px; height:30px; font-size:0.75rem;">
                                                    {{ strtoupper(substr($tk->user->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <strong class="text-dark d-block" style="line-height:1.2; font-size:0.84rem;">{{ $tk->user->name ?? 'Usuario Desconocido' }}</strong>
                                                    <small class="text-muted d-block" style="font-size:0.73rem;">{{ $tk->user->email ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Asunto & Descripción --}}
                                        <td class="align-middle" style="max-width: 250px;">
                                            <strong class="text-dark d-block mb-0" style="font-size: 0.85rem;">{{ $tk->titulo }}</strong>
                                            <small class="text-muted text-truncate d-block" style="font-size: 0.78rem;" title="{{ $tk->descripcion }}">
                                                {{ Str::limit($tk->descripcion, 75) }}
                                            </small>
                                            @if($tk->commit_hash)
                                                <a href="https://github.com/jufrancopy/sistem_postgres_pei/commit/{{ $tk->commit_hash }}" target="_blank" class="badge badge-dark font-mono mt-1 text-warning px-2 py-0.5" style="border-radius:6px; font-family:monospace; font-size:0.7rem;" title="Ver commit de solución en GitHub">
                                                    <i class="fa fa-code-branch text-warning mr-1"></i> Commit: {{ Str::limit($tk->commit_hash, 7, '') }}
                                                </a>
                                            @endif
                                        </td>

                                        {{-- Ruta Capturada --}}
                                        <td class="align-middle" style="max-width: 220px;">
                                            @if($tk->url_origen)
                                                <small class="text-muted text-truncate d-block font-weight-bold mb-1" style="font-family: monospace; font-size: 0.73rem;" title="{{ $tk->url_origen }}">
                                                    {{ $tk->ruta_origen ?: Str::after($tk->url_origen, request()->getSchemeAndHttpHost()) }}
                                                </small>
                                                <a href="{{ $tk->url_origen }}" target="_blank" class="btn btn-xs btn-outline-danger font-weight-bold rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                                                    <i class="fa fa-external-link-alt mr-1"></i> Abrir Pantalla de Falla
                                                </a>
                                            @else
                                                <small class="text-muted italic">Ruta no disponible</small>
                                            @endif
                                        </td>

                                        {{-- Estado --}}
                                        <td class="align-middle text-center">
                                            <span class="badge {{ $estadoBadge }} px-2.5 py-1" style="font-size: 0.72rem;">
                                                {{ strtoupper(str_replace('_', ' ', $tk->estado)) }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="align-middle text-right" style="white-space: nowrap;">
                                            <button type="button" class="btn btn-xs btn-purple font-weight-bold px-2.5 py-1 btnCopiarPromptIa mr-1"
                                                    data-codigo="{{ $tk->codigo }}"
                                                    data-fecha="{{ $tk->created_at->format('d/m/Y H:i') }}"
                                                    data-prioridad="{{ strtoupper($tk->prioridad) }}"
                                                    data-titulo="{{ e($tk->titulo) }}"
                                                    data-descripcion="{{ e($tk->descripcion) }}"
                                                    data-user-name="{{ e($tk->user->name ?? 'Usuario') }}"
                                                    data-user-email="{{ e($tk->user->email ?? '') }}"
                                                    data-url="{{ e($tk->url_origen) }}"
                                                    title="Copiar prompt listo para pegar en el chat de Antigravity AI">
                                                <i class="fa fa-robot mr-1"></i> Prompt IA
                                            </button>
                                            <button type="button" class="btn btn-xs btn-primary font-weight-bold px-2.5 py-1 btnGestionarTicket"
                                                    data-id="{{ $tk->id }}"
                                                    data-codigo="{{ $tk->codigo }}"
                                                    data-fecha="{{ $tk->created_at->format('d/m/Y H:i') }}"
                                                    data-prioridad="{{ strtoupper($tk->prioridad) }}"
                                                    data-titulo="{{ e($tk->titulo) }}"
                                                    data-descripcion="{{ e($tk->descripcion) }}"
                                                    data-user-name="{{ e($tk->user->name ?? 'Usuario') }}"
                                                    data-user-email="{{ e($tk->user->email ?? '') }}"
                                                    data-estado="{{ $tk->estado }}"
                                                    data-url="{{ e($tk->url_origen) }}"
                                                    data-respuesta="{{ e($tk->respuesta_admin) }}"
                                                    data-commit-hash="{{ e($tk->commit_hash) }}"
                                                    title="Gestionar Ticket">
                                                <i class="fa fa-tools mr-1"></i> Gestionar
                                            </button>
                                            <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1 btnEliminarTicket"
                                                    data-id="{{ $tk->id }}" data-codigo="{{ $tk->codigo }}" title="Eliminar Ticket">
                                                <i class="fa fa-trash"></i>
                                            </button>
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

{{-- Modal de Gestión de Ticket --}}
<div class="modal fade" id="modalGestionarTicket" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100050;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 680px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            
            <div class="modal-header py-3 px-4 bg-dark text-white">
                <div>
                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-0.5" id="mg_ticket_codigo">TK-0000</span>
                    <h5 class="modal-title font-weight-bold text-white mb-0 mt-1" id="mg_ticket_titulo"></h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="formGestionarTicket">
                @csrf
                @method('PUT')
                <input type="hidden" id="mg_ticket_id">

                <div class="modal-body p-4 bg-light">
                    
                    <div class="p-3 bg-white rounded border mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <small class="text-muted text-uppercase font-weight-bold d-block mb-1">Reportado por:</small>
                                <strong id="mg_user_name" class="text-dark"></strong>
                                <div class="small text-muted" id="mg_user_email"></div>
                            </div>
                            <div class="col-md-5 text-md-right mt-2 mt-md-0 d-flex flex-column align-items-md-end" style="gap:5px;">
                                <a id="mg_btn_abrir_url" href="#" target="_blank" class="btn btn-xs btn-outline-danger font-weight-bold rounded-pill px-3 py-1">
                                    <i class="fa fa-external-link-alt mr-1"></i> Abrir Pantalla de Falla
                                </a>
                                <button type="button" class="btn btn-xs btn-purple font-weight-bold rounded-pill px-3 py-1" id="btnMgCopiarPromptIa">
                                    <i class="fa fa-robot mr-1"></i> Copiar Prompt para Antigravity
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Detalle del Inconveniente:</label>
                        <div class="p-3 bg-white border rounded text-dark" id="mg_ticket_descripcion" style="white-space: pre-wrap; font-size: 0.85rem; max-height: 150px; overflow-y: auto;"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Estado de Atención:</label>
                        <select id="mg_ticket_estado" name="estado" class="form-control form-control-sm font-weight-bold" style="border-radius: 8px;">
                            <option value="pendiente">🟡 Pendiente de atención</option>
                            <option value="en_proceso">🔵 En Proceso de solución</option>
                            <option value="resuelto">🟢 Resuelto (Problema corregido)</option>
                            <option value="rechazado">🔴 Rechazado / No procede</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold small text-dark mb-1"><i class="fa fa-code-branch text-primary mr-1"></i> Commit de Solución (Hash / Código Git):</label>
                        <input type="text" id="mg_commit_hash" name="commit_hash" class="form-control form-control-sm font-weight-bold" placeholder="Ej: cc8732c o df59cae" style="border-radius: 8px; font-family: monospace;">
                        <small class="text-muted" style="font-size:0.71rem;">Ingresá el hash de commit generado al solucionar la falla (ej: <code>df59cae</code>)</small>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold small text-dark mb-1">Notas o Respuesta del Administrador al Usuario:</label>
                        <textarea id="mg_respuesta_admin" name="respuesta_admin" class="form-control form-control-sm" rows="3" placeholder="Ingresá detalles de la solución o respuesta al usuario..." style="border-radius: 8px; font-size: 0.85rem;"></textarea>
                    </div>

                </div>

                <div class="modal-footer bg-white py-2.5 px-4 justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 font-weight-bold" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 font-weight-bold shadow-sm">
                        <i class="fa fa-save mr-1"></i> Guardar y Notificar al Usuario
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Modal Vista Previa Prompt para Antigravity AI --}}
<div class="modal fade" id="modalPreviewPromptIa" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100060;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #6d28d9 0%, #4c1d95 100%);">
                <div class="d-flex align-items-center">
                    <i class="fa fa-robot fa-2x text-warning mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="promptModalTitle">Prompt para Antigravity AI</h5>
                        <small class="text-white-50">Copiá este texto estructurado y pegalo directamente en el chat con la IA</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="alert alert-info border-0 py-2.5 px-3 mb-3 small font-weight-500" style="border-radius: 8px; background: #f0fdf4; color: #166534; border-left: 4px solid #22c55e !important;">
                    <i class="fa fa-check-circle mr-1 text-success"></i> Este prompt contiene el código del ticket, contexto del usuario, ruta del error y las instrucciones para que Antigravity localice y corrija la falla.
                </div>
                <div class="position-relative">
                    <textarea id="textoPromptIaPreview" class="form-control font-mono p-3 bg-dark text-emerald-400 border-0" rows="12" style="font-family: monospace; font-size: 0.82rem; border-radius: 10px; color: #4ade80 !important; background: #0f172a !important; line-height: 1.5;" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer bg-white py-3 px-4 justify-content-between">
                <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-purple rounded-pill px-4 font-weight-bold shadow-sm" onclick="copiarContenidoPromptIa()">
                    <i class="fa fa-copy mr-1"></i> Copiar al Portapapeles
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // 1. Inicializar DataTables con diseño en español
    if ($.fn.DataTable && $('#tablaTicketsAdmin').length) {
        $('#tablaTicketsAdmin').DataTable({
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron tickets",
                "sEmptyTable":     "Ningún ticket disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ tickets",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 tickets",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sSearch":         "Buscar ticket:",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
            },
            "order": [[0, "desc"]],
            "pageLength": 10
        });
    }

    // 2. Mover modal de gestión a body
    if ($('#modalGestionarTicket').length) {
        $('#modalGestionarTicket').appendTo('body');
    }

    // 3. Monitoreo en tiempo real (Polling con Alerta Sonora / Toastr para Admin)
    var currentPending = parseInt($('#kpiPendientesCount').text()) || 0;
    setInterval(function() {
        $.getJSON('{{ route("admin.soporte.tickets.countPending") }}', function(res) {
            if (res.pending > currentPending) {
                currentPending = res.pending;
                $('#kpiPendientesCount').text(currentPending);
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: '🔥 ¡NUEVO TICKET DE FALLA!',
                        text: 'Un usuario acaba de registrar un reporte de incidencia en el sistema.',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Ver Bandeja'
                    }).then(function() {
                        window.location.reload();
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr.warning('Un nuevo ticket de falla ha sido registrado por un usuario.', '🔥 ¡ALERTA DE SOPORTE!');
                }
            }
        });
    }, 12000);

    // Abrir modal de gestión
    $(document).on('click', '.btnGestionarTicket', function() {
        var $btn = $(this);
        $('#mg_ticket_id').val($btn.data('id'));
        $('#mg_ticket_codigo').text($btn.data('codigo'));
        $('#mg_ticket_titulo').text($btn.data('titulo'));
        $('#mg_ticket_descripcion').text($btn.data('descripcion'));
        $('#mg_user_name').text($btn.data('user-name'));
        $('#mg_user_email').text($btn.data('user-email'));
        $('#mg_ticket_estado').val($btn.data('estado'));
        $('#mg_respuesta_admin').val($btn.data('respuesta') || '');
        $('#mg_commit_hash').val($btn.data('commit-hash') || '');

        var url = $btn.data('url');
        if (url) {
            $('#mg_btn_abrir_url').attr('href', url).show();
        } else {
            $('#mg_btn_abrir_url').hide();
        }

        $('#modalGestionarTicket').modal('show');
    });

    // Clic en botón "Prompt IA" de la fila
    $(document).on('click', '.btnCopiarPromptIa', function() {
        var $btn = $(this);
        var ticket = {
            codigo: $btn.data('codigo'),
            fecha: $btn.data('fecha'),
            prioridad: $btn.data('prioridad'),
            titulo: $btn.data('titulo'),
            descripcion: $btn.data('descripcion'),
            userName: $btn.data('user-name'),
            userEmail: $btn.data('user-email'),
            url: $btn.data('url')
        };
        mostrarPromptIaModal(construirPromptIaUnico(ticket), 'Prompt para Ticket ' + ticket.codigo, ticket.codigo);
    });

    // Clic en botón "Copiar Prompt para Antigravity" dentro del modal de gestión
    $('#btnMgCopiarPromptIa').on('click', function() {
        var ticket = {
            codigo: $('#mg_ticket_codigo').text(),
            fecha: 'Reciente',
            prioridad: 'ALTA',
            titulo: $('#mg_ticket_titulo').text(),
            descripcion: $('#mg_ticket_descripcion').text(),
            userName: $('#mg_user_name').text(),
            userEmail: $('#mg_user_email').text(),
            url: $('#mg_btn_abrir_url').attr('href') !== '#' ? $('#mg_btn_abrir_url').attr('href') : ''
        };
        mostrarPromptIaModal(construirPromptIaUnico(ticket), 'Prompt para Ticket ' + ticket.codigo, ticket.codigo);
    });

    // Guardar cambios del ticket
    $('#formGestionarTicket').on('submit', function(e) {
        e.preventDefault();
        var id = $('#mg_ticket_id').val();
        $.ajax({
            url: '{{ url("admin/soporte/tickets") }}/' + id + '/status',
            type: 'PUT',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $('#modalGestionarTicket').modal('hide');
                if (typeof toastr !== 'undefined') toastr.success(res.message);
                else alert(res.message);
                setTimeout(function() { window.location.reload(); }, 600);
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al actualizar ticket.';
                if (typeof toastr !== 'undefined') toastr.error(msg);
                else alert(msg);
            }
        });
    });

    // Eliminar ticket
    $(document).on('click', '.btnEliminarTicket', function() {
        var id = $(this).data('id');
        var codigo = $(this).data('codigo');
        Swal.fire({
            title: '¿Eliminar ticket ' + codigo + '?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/soporte/tickets") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        toastr.success('Ticket eliminado.');
                        setTimeout(function() { window.location.reload(); }, 600);
                    }
                });
            }
        });
    });

    // Inicializar checks de IA en tabla y DataTables draw
    actualizarCheckmarksUI();
    if ($.fn.DataTable && $('#tablaTicketsAdmin').length) {
        var dt = $('#tablaTicketsAdmin').DataTable();
        dt.on('draw', function() {
            actualizarCheckmarksUI();
        });
    }
});

// Helper Functions para Prompts de IA & Seguimiento de Checks Copiados
function getTicketsCopiadosArray() {
    try {
        return JSON.parse(localStorage.getItem('tickets_copiados_ia')) || [];
    } catch (e) {
        return [];
    }
}

function marcarTicketComoCopiado(codigo) {
    if (!codigo) return;
    var array = getTicketsCopiadosArray();
    if (!array.includes(codigo)) {
        array.push(codigo);
        try {
            localStorage.setItem('tickets_copiados_ia', JSON.stringify(array));
        } catch (e) {}
    }
    actualizarCheckmarksUI();
}

function actualizarCheckmarksUI() {
    var array = getTicketsCopiadosArray();
    array.forEach(function(codigo) {
        // Mostrar badge "IA ✓" al lado del código
        $('#badge_copiado_' + codigo).removeClass('d-none').addClass('d-inline-flex');

        // Cambiar botón de la fila a verde check
        var $btnRow = $('.btnCopiarPromptIa[data-codigo="' + codigo + '"]');
        if ($btnRow.length) {
            $btnRow.removeClass('btn-purple').addClass('btn-success')
                   .html('<i class="fa fa-check mr-1"></i> Copiado');
        }
    });
}

function limpiarCheckmarksIa() {
    try {
        localStorage.removeItem('tickets_copiados_ia');
    } catch (e) {}
    $('.badge-ia-copiado').addClass('d-none').removeClass('d-inline-flex');
    $('.btnCopiarPromptIa').removeClass('btn-success').addClass('btn-purple')
           .html('<i class="fa fa-robot mr-1"></i> Prompt IA');
    if (typeof toastr !== 'undefined') toastr.info('Se restablecieron los checks de tickets copiados.');
}

function construirPromptIaUnico(t) {
    return `<USER_REQUEST>\n` +
           `Por favor ayuda a resolver la siguiente falla reportada por un usuario en el sistema SIPLAN PEI:\n\n` +
           `- **Código Ticket**: ${t.codigo}\n` +
           `- **Prioridad**: ${t.prioridad}\n` +
           `- **Fecha**: ${t.fecha}\n` +
           `- **Usuario que Reportó**: ${t.userName} (${t.userEmail})\n` +
           `- **Falla / Asunto**: ${t.titulo}\n` +
           `- **Detalle del Inconveniente**: ${t.descripcion}\n` +
           `- **Ruta / Pantalla de la Falla**: ${t.url || 'No especificada'}\n\n` +
           `**Instrucción para Antigravity AI**:\n` +
           `Analizá el código fuente del proyecto asociado a esta ruta (${t.url || t.titulo}). Identificá la causa raíz del error ("${t.titulo} - ${t.descripcion}") y realizá las modificaciones de código necesarias en controladores, modelos o vistas para corregir esta falla.\n` +
           `</USER_REQUEST>`;
}

function mostrarPromptIaModal(promptText, titulo, codigoTicket) {
    $('#promptModalTitle').text(titulo || 'Prompt para Antigravity AI');
    $('#textoPromptIaPreview').val(promptText);
    if ($('#modalPreviewPromptIa').parent().is('body') === false) {
        $('#modalPreviewPromptIa').appendTo('body');
    }
    $('#modalPreviewPromptIa').modal('show');

    if (codigoTicket) {
        marcarTicketComoCopiado(codigoTicket);
    }

    copyTextToClipboard(promptText, '🤖 ¡Prompt copiado al portapapeles! Pegalo directamente en el chat con Antigravity.');
}

function copiarContenidoPromptIa() {
    var txt = $('#textoPromptIaPreview').val();
    if (txt) {
        copyTextToClipboard(txt, '🤖 ¡Prompt copiado al portapapeles! Pegalo en el chat con Antigravity.');
    }
}

function copiarPromptLotePendientesIa() {
    var tickets = [];
    $('.btnCopiarPromptIa').each(function() {
        var $btn = $(this);
        var cod = $btn.data('codigo');
        tickets.push({
            codigo: cod,
            fecha: $btn.data('fecha'),
            prioridad: $btn.data('prioridad'),
            titulo: $btn.data('titulo'),
            descripcion: $btn.data('descripcion'),
            userName: $btn.data('user-name'),
            userEmail: $btn.data('user-email'),
            url: $btn.data('url')
        });
        marcarTicketComoCopiado(cod);
    });

    if (tickets.length === 0) {
        if (typeof toastr !== 'undefined') toastr.info('No hay tickets visibles en esta vista.');
        else alert('No hay tickets visibles.');
        return;
    }

    var text = `<USER_REQUEST>\n` +
               `Por favor ayuda a resolver los siguientes ${tickets.length} reportes de fallas registrados en el sistema SIPLAN PEI:\n\n`;

    tickets.forEach(function(t, idx) {
        text += `---\n### ${idx + 1}. [${t.codigo}] ${t.titulo}\n` +
                `- **Prioridad**: ${t.prioridad} | **Fecha**: ${t.fecha}\n` +
                `- **Usuario**: ${t.userName} (${t.userEmail})\n` +
                `- **Ruta Afectada**: ${t.url || 'No especificada'}\n` +
                `- **Detalle**: ${t.descripcion}\n\n`;
    });

    text += `**Instrucción para Antigravity AI**:\n` +
            `Revisá el código del proyecto para cada una de las fallas descritas arriba, diagnosticá la causa raíz de cada inconveniente y aplicá los cambios necesarios en el repositorio para solucionar todos los reportes.\n` +
            `</USER_REQUEST>`;

    mostrarPromptIaModal(text, 'Prompt Lote (' + tickets.length + ' Tickets para Antigravity)');
}

function copyTextToClipboard(text, successMsg) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            if (typeof toastr !== 'undefined') toastr.success(successMsg || '¡Copiado al portapapeles!');
            else alert(successMsg || '¡Copiado!');
        }).catch(function() {
            fallbackCopyText(text, successMsg);
        });
    } else {
        fallbackCopyText(text, successMsg);
    }
}

function fallbackCopyText(text, successMsg) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        if (typeof toastr !== 'undefined') toastr.success(successMsg || '¡Copiado al portapapeles!');
        else alert(successMsg || '¡Copiado!');
    } catch (err) {
        alert('No se pudo copiar automáticamente. Por favor seleccioná el texto del cuadro.');
    }
    document.body.removeChild(textArea);
}
</script>
@endsection
