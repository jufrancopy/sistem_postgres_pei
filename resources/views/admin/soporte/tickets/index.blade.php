@extends('layouts.master')

@section('content')
<div class="container-fluid py-3">

    {{-- Encabezado Principal --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap" style="gap: 1rem;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge badge-danger font-weight-bold px-2.5 py-1" style="border-radius: 6px; font-size: 0.75rem;">
                    ADMINISTRACIÓN Y SOPORTE
                </span>
                <span class="text-muted small">Atención rápida de incidencias de usuarios</span>
            </div>
            <h3 class="font-weight-bold text-dark mb-0 d-flex align-items-center" style="letter-spacing: -0.5px;">
                <i class="fa fa-ticket-alt text-danger mr-2.5"></i> Bandeja de Tickets & Reportes de Falla
            </h3>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-3 shadow-xs" onclick="abrirModalReportarFalla()">
                <i class="fa fa-plus-circle mr-1"></i> Crear Ticket Interno
            </button>
        </div>
    </div>

    {{-- Tarjetas KPI de Resumen --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm rounded-lg" style="border-left: 4px solid #64748b !important;">
                <div class="card-body py-3 px-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Registrados</div>
                        <h4 class="font-weight-bold text-dark mb-0 mt-1">{{ number_format($counts['total']) }}</h4>
                    </div>
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; color: #64748b;">
                        <i class="fa fa-layer-group fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm rounded-lg" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body py-3 px-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-warning text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Pendientes por Resolver</div>
                        <h4 class="font-weight-bold text-warning mb-0 mt-1">{{ number_format($counts['pendiente']) }}</h4>
                    </div>
                    <div class="rounded-circle bg-warning-soft d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; color: #d97706; background: #fef3c7;">
                        <i class="fa fa-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm rounded-lg" style="border-left: 4px solid #3b82f6 !important;">
                <div class="card-body py-3 px-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-primary text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">En Proceso</div>
                        <h4 class="font-weight-bold text-primary mb-0 mt-1">{{ number_format($counts['en_proceso']) }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; color: #2563eb; background: #dbeafe;">
                        <i class="fa fa-spinner fa-spin fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-lg" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body py-3 px-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-success text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Resueltos</div>
                        <h4 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($counts['resuelto']) }}</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; color: #059669; background: #d1fae5;">
                        <i class="fa fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros y Tabla principal --}}
    <div class="card border-0 shadow-sm rounded-lg" style="border-radius: 12px; overflow: hidden;">
        
        {{-- Barra de Filtros --}}
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <form method="GET" action="{{ route('admin.soporte.tickets.index') }}" class="row align-items-center" style="gap: 0.5rem 0;">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light text-muted border-right-0"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm border-left-0" placeholder="Buscar por código, usuario o texto...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-control form-control-sm font-weight-bold" onchange="this.form.submit()">
                        <option value="">-- Todos los Estados --</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>🟡 Pendientes</option>
                        <option value="en_proceso" {{ request('estado') === 'en_proceso' ? 'selected' : '' }}>🔵 En Proceso</option>
                        <option value="resuelto" {{ request('estado') === 'resuelto' ? 'selected' : '' }}>🟢 Resueltos</option>
                        <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>🔴 Rechazados</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="prioridad" class="form-control form-control-sm font-weight-bold" onchange="this.form.submit()">
                        <option value="">-- Todas las Prioridades --</option>
                        <option value="urgente" {{ request('prioridad') === 'urgente' ? 'selected' : '' }}>🔥 Urgentes / Bloqueantes</option>
                        <option value="alta" {{ request('prioridad') === 'alta' ? 'selected' : '' }}>🔴 Alta Prioridad</option>
                        <option value="media" {{ request('prioridad') === 'media' ? 'selected' : '' }}>🟡 Prioridad Media</option>
                        <option value="baja" {{ request('prioridad') === 'baja' ? 'selected' : '' }}>🟢 Prioridad Baja</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-right">
                    @if(request()->anyFilled(['q', 'estado', 'prioridad']))
                        <a href="{{ route('admin.soporte.tickets.index') }}" class="btn btn-sm btn-light font-weight-bold">
                            <i class="fa fa-sync-alt mr-1"></i> Limpiar Filtros
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabla de Tickets --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.86rem;">
                <thead class="bg-light text-uppercase text-muted font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.04em;">
                    <tr>
                        <th class="py-3 px-4">Código & Prioridad</th>
                        <th class="py-3">Usuario que Reportó</th>
                        <th class="py-3">Falla / Asunto</th>
                        <th class="py-3">Ruta de la Falla</th>
                        <th class="py-3 text-center">Estado</th>
                        <th class="py-3 text-right px-4">Acción Rápida</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $tk)
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
                            {{-- Código & Prioridad --}}
                            <td class="px-4 py-3 align-top">
                                <div class="font-weight-bold text-dark mb-1" style="font-size: 0.9rem;">
                                    {{ $tk->codigo }}
                                </div>
                                <span class="badge {{ $prioBadge }}" style="font-size: 0.68rem;">
                                    {{ $prioLabel }}
                                </span>
                                <div class="text-muted small mt-1" style="font-size: 0.72rem;">
                                    {{ $tk->created_at->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Usuario --}}
                            <td class="py-3 align-top">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $tk->user->avatar_url ?? asset('material/img/faces/avatar.jpg') }}" class="rounded-circle mr-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    <div>
                                        <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                            {{ $tk->user->name ?? 'Usuario Desconocido' }}
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">
                                            {{ $tk->user->email ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Asunto & Descripción --}}
                            <td class="py-3 align-top" style="max-width: 280px;">
                                <div class="font-weight-bold text-dark mb-1">
                                    {{ $tk->titulo }}
                                </div>
                                <div class="text-muted text-truncate" style="font-size: 0.8rem;" title="{{ $tk->descripcion }}">
                                    {{ Str::limit($tk->descripcion, 90) }}
                                </div>
                            </td>

                            {{-- Ruta de Origen / Link a Pantalla del Error --}}
                            <td class="py-3 align-top" style="max-width: 240px;">
                                @if($tk->url_origen)
                                    <div class="mb-1 text-truncate" style="font-size: 0.75rem; font-family: monospace;" title="{{ $tk->url_origen }}">
                                        {{ $tk->ruta_origen ?: Str::after($tk->url_origen, request()->getSchemeAndHttpHost()) }}
                                    </div>
                                    <a href="{{ $tk->url_origen }}" target="_blank" class="btn btn-xs btn-outline-danger font-weight-bold rounded-pill px-2.5 py-1 shadow-xs" style="font-size: 0.72rem;" title="Abrir la pantalla exacta donde ocurrió la falla">
                                        <i class="fa fa-external-link-alt mr-1"></i> Abrir Pantalla de Falla
                                    </a>
                                @else
                                    <span class="text-muted italic" style="font-size: 0.75rem;">Ruta no capturada</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="py-3 align-top text-center">
                                <span class="badge {{ $estadoBadge }} px-2.5 py-1" style="font-size: 0.73rem;">
                                    {{ strtoupper(str_replace('_', ' ', $tk->estado)) }}
                                </span>
                                @if($tk->resolved_at)
                                    <div class="text-muted small mt-1" style="font-size: 0.68rem;">
                                        Resuelto {{ $tk->resolved_at->format('d/m H:i') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="py-3 align-top text-right px-4" style="white-space: nowrap;">
                                <button type="button" class="btn btn-sm btn-primary font-weight-bold px-2.5 py-1 btnGestionarTicket"
                                        data-ticket="{{ json_encode($tk) }}"
                                        title="Atender o cambiar estado del ticket">
                                    <i class="fa fa-tools mr-1"></i> Gestionar
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 btnEliminarTicket"
                                        data-id="{{ $tk->id }}" data-codigo="{{ $tk->codigo }}"
                                        title="Eliminar ticket">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa fa-ticket-alt fa-3x mb-3 text-muted" style="opacity: 0.4;"></i>
                                <h5 class="font-weight-bold text-secondary">No hay tickets registrados</h5>
                                <p class="small text-muted mb-0">Los reportes de falla que realicen los usuarios aparecerán aquí inmediatamente.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($tickets->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4">
                {{ $tickets->links() }}
            </div>
        @endif

    </div>
</div>

{{-- Modal de Gestión de Ticket --}}
<div class="modal fade" id="modalGestionarTicket" tabindex="-1" role="dialog" aria-hidden="true">
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
                    
                    {{-- Info del usuario --}}
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

                    {{-- Detalle del problema --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Detalle del Inconveniente:</label>
                        <div class="p-3 bg-white border rounded text-dark" id="mg_ticket_descripcion" style="white-space: pre-wrap; font-size: 0.85rem; max-height: 150px; overflow-y: auto;"></div>
                    </div>

                    {{-- Cambiar Estado --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Estado de Atención:</label>
                        <select id="mg_ticket_estado" name="estado" class="form-control form-control-sm font-weight-bold" style="border-radius: 8px;">
                            <option value="pendiente">🟡 Pendiente de atención</option>
                            <option value="en_proceso">🔵 En Proceso de solución</option>
                            <option value="resuelto">🟢 Resuelto (Problema corregido)</option>
                            <option value="rechazado">🔴 Rechazado / No procede</option>
                        </select>
                    </div>

                    {{-- Respuesta del Administrador --}}
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
                        <i class="fa fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Abrir modal de gestión
    $(document).on('click', '.btnGestionarTicket', function() {
        var tk = $(this).data('ticket');
        $('#mg_ticket_id').val(tk.id);
        $('#mg_ticket_codigo').text(tk.codigo);
        $('#mg_ticket_titulo').text(tk.titulo);
        $('#mg_ticket_descripcion').text(tk.descripcion);
        $('#mg_user_name').text(tk.user ? tk.user.name : 'Usuario Desconocido');
        $('#mg_user_email').text(tk.user ? tk.user.email : '');
        $('#mg_ticket_estado').val(tk.estado);
        $('#mg_respuesta_admin').val(tk.respuesta_admin || '');

        if (tk.url_origen) {
            $('#mg_btn_abrir_url').attr('href', tk.url_origen).show();
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
                setTimeout(function() { window.location.reload(); }, 800);
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
