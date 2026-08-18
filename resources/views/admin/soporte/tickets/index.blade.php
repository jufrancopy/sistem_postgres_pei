@extends('layouts.master')

@section('content')
<div class="container-fluid">

    {{-- Tarjeta Principal con Diseño Material Dashboard Unificado --}}
    <div class="card">
        <div class="card-header card-header-danger card-header-icon">
            <div class="card-icon">
                <i class="material-icons">confirmation_number</i>
            </div>
            <p class="card-category text-dark font-weight-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                ADMINISTRACIÓN & SOPORTE TÉCNICO
            </p>
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title font-weight-bold text-dark mb-0">
                    Bandeja de Tickets & Reportes de Falla
                </h3>
                <button type="button" class="btn btn-sm btn-danger font-weight-bold rounded-pill px-3 shadow-sm" onclick="abrirModalReportarFalla()">
                    <i class="fa fa-plus-circle mr-1"></i> Crear Ticket Interno
                </button>
            </div>
        </div>

        <div class="card-body">

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
                    <thead class="bg-light text-uppercase text-muted font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.04em;">
                        <tr>
                            <th style="width: 140px;">Código & Fecha</th>
                            <th style="width: 120px;">Prioridad</th>
                            <th>Usuario que Reportó</th>
                            <th>Falla / Asunto</th>
                            <th>Ruta de la Falla</th>
                            <th class="text-center" style="width: 110px;">Estado</th>
                            <th class="text-right" style="width: 150px;">Acciones</th>
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
                                    <strong class="text-dark d-block" style="font-size: 0.88rem;">{{ $tk->codigo }}</strong>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $tk->created_at->format('d/m/Y H:i') }}</small>
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
                                    <button type="button" class="btn btn-xs btn-primary font-weight-bold px-2.5 py-1 btnGestionarTicket"
                                            data-id="{{ $tk->id }}"
                                            data-codigo="{{ $tk->codigo }}"
                                            data-titulo="{{ e($tk->titulo) }}"
                                            data-descripcion="{{ e($tk->descripcion) }}"
                                            data-user-name="{{ e($tk->user->name ?? 'Usuario') }}"
                                            data-user-email="{{ e($tk->user->email ?? '') }}"
                                            data-estado="{{ $tk->estado }}"
                                            data-url="{{ e($tk->url_origen) }}"
                                            data-respuesta="{{ e($tk->respuesta_admin) }}"
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

{{-- Modal de Gestión de Ticket --}}
<div class="modal fade" id="modalGestionarTicket" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100050;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px;">
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
                            <div class="col-md-5 text-md-right mt-2 mt-md-0">
                                <a id="mg_btn_abrir_url" href="#" target="_blank" class="btn btn-xs btn-outline-danger font-weight-bold rounded-pill px-3 py-1">
                                    <i class="fa fa-external-link-alt mr-1"></i> Abrir Pantalla de Falla
                                </a>
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

<script>
$(document).ready(function() {
    // 1. Inicializar DataTables con diseño en español
    var tablaTickets = $('#tablaTicketsAdmin').DataTable({
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
                
                // Lanzar Alerta Flotante de Alta Prioridad
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

        var url = $btn.data('url');
        if (url) {
            $('#mg_btn_abrir_url').attr('href', url).show();
        } else {
            $('#mg_btn_abrir_url').hide();
        }

        $('#modalGestionarTicket').modal('show');
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
                toastr.success(res.message);
                setTimeout(function() { window.location.reload(); }, 600);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON ? xhr.responseJSON.message : 'Error al actualizar ticket.');
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
});
</script>
@endsection
