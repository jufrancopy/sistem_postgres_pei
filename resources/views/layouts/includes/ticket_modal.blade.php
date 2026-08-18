{{-- ══ MODAL GLOBAL Y BOTÓN FLOTANTE PARA REPORTE DE FALLAS Y SOPORTE TÉCNICO ══ --}}
<style>
.btn-ticket-float {
    position: fixed;
    bottom: 24px;
    left: 24px;
    z-index: 99990;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
    border: none;
    border-radius: 50px;
    padding: 10px 18px;
    font-size: 0.8rem;
    font-weight: 800;
    box-shadow: 0 8px 24px rgba(220, 38, 38, 0.4);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.btn-ticket-float:hover {
    transform: translateY(-3px) scale(1.04);
    box-shadow: 0 12px 30px rgba(220, 38, 38, 0.55);
    color: #ffffff;
}
.btn-ticket-float i {
    font-size: 1.05rem;
}
/* Material Dashboard Form Reset para evitar encimado de labels e inputs */
#modalReportarFalla .form-group,
#modalGestionarTicket .form-group {
    position: relative !important;
    margin-bottom: 1.1rem !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}
#modalReportarFalla label,
#modalGestionarTicket label {
    position: static !important;
    display: block !important;
    margin-bottom: 0.35rem !important;
    top: auto !important;
    left: auto !important;
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    transform: none !important;
    pointer-events: auto !important;
    line-height: 1.3 !important;
}
#modalReportarFalla input.form-control,
#modalReportarFalla select.form-control,
#modalReportarFalla textarea.form-control,
#modalGestionarTicket input.form-control,
#modalGestionarTicket select.form-control,
#modalGestionarTicket textarea.form-control {
    position: static !important;
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    height: auto !important;
    color: #0f172a !important;
    box-shadow: none !important;
    font-size: 0.85rem !important;
}
#modalReportarFalla input.form-control[readonly],
#modalGestionarTicket input.form-control[readonly] {
    background-color: #f1f5f9 !important;
    color: #2563eb !important;
}
#modalReportarFalla input.form-control:focus,
#modalReportarFalla select.form-control:focus,
#modalReportarFalla textarea.form-control:focus,
#modalGestionarTicket input.form-control:focus,
#modalGestionarTicket select.form-control:focus,
#modalGestionarTicket textarea.form-control:focus {
    border-color: #ef4444 !important;
    background-color: #ffffff !important;
    outline: none !important;
}
</style>

{{-- Botón Flotante en la esquina inferior izquierda --}}
<button type="button" class="btn-ticket-float" onclick="abrirModalReportarFalla()" title="¿Encontraste un error o falla? Reportalo al Administrador aquí">
    <i class="fa fa-exclamation-triangle"></i>
    <span class="d-none d-md-inline">Reportar Falla</span>
</button>

{{-- Modal de Ticket de Falla --}}
<div class="modal fade" id="modalReportarFalla" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            
            {{-- Header --}}
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%); color: white;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-warning text-dark font-weight-bold px-2 py-0.5" style="border-radius: 6px; font-size: 0.68rem;">
                            SOPORTE TÉCNICO DIRECTO
                        </span>
                        <span class="text-white-50 small">Generador de Ticket de Incidencia</span>
                    </div>
                    <h5 class="modal-title font-weight-bold text-white mb-0" style="font-size: 1.15rem;">
                        <i class="fa fa-bug mr-2"></i> Reportar Falla o Problema Técnico
                    </h5>
                </div>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal">&times;</button>
            </div>

            {{-- Formulario --}}
            <form id="formReportarFalla">
                @csrf
                <input type="hidden" id="ticket_url_origen" name="url_origen">
                <input type="hidden" id="ticket_pei_profile_id" name="pei_profile_id">
                <input type="hidden" id="ticket_nodo_contexto" name="nodo_contexto">

                <div class="modal-body p-4" style="background: #f8fafc;">
                    
                    {{-- Alerta informativa --}}
                    <div class="p-3 mb-3 bg-white rounded border d-flex align-items-start gap-3" style="border-left: 4px solid #ef4444 !important;">
                        <i class="fa fa-map-marker-alt text-danger mt-1" style="font-size: 1.2rem;"></i>
                        <div class="small text-muted" style="line-height: 1.45;">
                            Se capturará automáticamente la <strong>ruta exacta y pantalla actual</strong> en la que te encontrás para que el administrador la abra directamente y solucione el inconveniente.
                        </div>
                    </div>

                    {{-- Ruta Capturada (Readonly) --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">
                            <i class="fa fa-link text-primary mr-1"></i> Ruta / Pantalla Capturada
                        </label>
                        <input type="text" id="ticket_url_display" class="form-control form-control-sm bg-light font-weight-bold text-primary" readonly style="font-size: 0.8rem; border-radius: 8px;">
                    </div>

                    {{-- Título del problema --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">
                            Título o Asunto del Problema <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="ticket_titulo" name="titulo" class="form-control form-control-sm font-weight-bold" placeholder="Ej: No guarda la Ficha de Indicador o Botón no responde" required style="border-radius: 8px;">
                    </div>

                    {{-- Prioridad --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">
                            <i class="fa fa-signal text-warning mr-1"></i> Prioridad de Atención
                        </label>
                        <select id="ticket_prioridad" name="prioridad" class="form-control form-control-sm font-weight-bold" style="border-radius: 8px;">
                            <option value="baja">🟢 Baja — Sugerencia o consulta menor</option>
                            <option value="media" selected>🟡 Media — Inconveniente parcial que no bloquea todo</option>
                            <option value="alta">🔴 Alta — Falla importante en una funcionalidad</option>
                            <option value="urgente">🔥 Urgente / Bloqueante — No puedo continuar mi trabajo</option>
                        </select>
                    </div>

                    {{-- Descripción Detallada --}}
                    <div class="form-group mb-2">
                        <label class="font-weight-bold text-dark small mb-1">
                            Descripción de lo sucedido o lo que necesitás <span class="text-danger">*</span>
                        </label>
                        <textarea id="ticket_descripcion" name="descripcion" class="form-control form-control-sm" rows="4" placeholder="Explicá brevemente qué estabas haciendo y qué mensaje o comportamiento incorrecto observaste..." required style="border-radius: 8px; font-size: 0.85rem;"></textarea>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-white py-2.5 px-4 justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 font-weight-bold" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 font-weight-bold shadow-sm" id="btnSubmitTicket">
                        <i class="fa fa-paper-plane mr-1"></i> Enviar Ticket al Administrador
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function abrirModalReportarFalla(peiProfileId, nodoContexto) {
    var currentUrl = window.location.href;
    $('#ticket_url_origen').val(currentUrl);
    $('#ticket_url_display').val(currentUrl);
    $('#ticket_pei_profile_id').val(peiProfileId || '');
    $('#ticket_nodo_contexto').val(nodoContexto || '');
    
    $('#ticket_titulo').val('');
    $('#ticket_descripcion').val('');
    $('#ticket_prioridad').val('media');
    
    $('#modalReportarFalla').modal('show');
}

$(document).ready(function() {
    $('#formReportarFalla').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSubmitTicket');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Enviando Ticket...');

        $.ajax({
            url: '{{ route("soporte.tickets.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Enviar Ticket al Administrador');
                $('#modalReportarFalla').modal('hide');
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Ticket Generado!',
                        html: 'Código: <strong class="badge badge-danger px-2 py-1" style="font-size:0.9rem">' + res.ticket.codigo + '</strong><br><br>' + res.message,
                        confirmButtonColor: '#dc2626'
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr.success(res.message, 'Ticket ' + res.ticket.codigo);
                } else {
                    alert(res.message);
                }

                // Actualizar contador si es admin
                if (typeof checkPendingTicketsCount === 'function') {
                    checkPendingTicketsCount();
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Enviar Ticket al Administrador');
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al enviar el ticket de falla.';
                if (typeof toastr !== 'undefined') toastr.error(msg);
                else alert(msg);
            }
        });
    });
});
</script>
