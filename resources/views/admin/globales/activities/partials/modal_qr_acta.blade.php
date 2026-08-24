<div class="modal fade" id="modalQrActa" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 520px;">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
            
            <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #ecfdf5; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-qrcode text-success" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bolder text-dark mb-0" style="font-size: 1.15rem; letter-spacing: -0.3px;">
                            Código QR de Asistencia
                        </h5>
                        <small class="text-muted" id="modalQrSubtitle">Escanear para registrarse y leer el acta</small>
                    </div>
                </div>
                <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Cerrar" style="font-size: 1.5rem; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center px-4 py-4">
                
                {{-- Contenedor del Código QR --}}
                <div class="p-3 mx-auto mb-3 bg-white d-inline-block rounded-2xl shadow-sm" 
                     id="qrCodeDisplayContainer"
                     style="border: 2px solid #e2e8f0; border-radius: 16px; min-height: 240px; min-width: 240px; display: flex; align-items: center; justify-content: center;">
                    <div id="qrCodeSvgHolder">
                        {{-- SVG inyectado vía JS --}}
                    </div>
                </div>

                {{-- Instrucciones rápidas --}}
                <p class="text-muted small mb-3 px-2">
                    Proyectá este código en la sala o compartí el enlace para que los participantes lean el acta y completen sus datos en tiempo real.
                </p>

                {{-- Input con Enlace Público y Botón Copiar --}}
                <div class="input-group mb-3 shadow-sm" style="border-radius: 10px; overflow: hidden; border: 1px solid #cbd5e1;">
                    <input type="text" class="form-control form-control-sm border-0 font-weight-medium bg-light" 
                           id="txtActaPublicUrl" readonly style="font-size: 0.8rem; color: #334155;">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm px-3 font-weight-bold" type="button" id="btnCopiarUrlQr">
                            <i class="fa fa-copy mr-1"></i> <span id="btnCopiarText">Copiar</span>
                        </button>
                    </div>
                </div>

                {{-- Contador de Asistentes en Vivo --}}
                <div class="p-2 px-3 rounded-lg d-flex align-items-center justify-content-between mb-2" 
                     style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <span class="position-relative d-flex" style="width: 10px; height: 10px;">
                            <span class="position-absolute w-100 h-100 rounded-circle bg-success" style="opacity: 0.75; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                            <span class="rounded-circle bg-success w-100 h-100"></span>
                        </span>
                        <span class="small font-weight-bold text-dark" id="modalQrCountText">0 participantes registrados</span>
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-secondary font-weight-bold" id="btnRefreshQrCount" style="font-size: 0.72rem;">
                        <i class="fa fa-sync-alt"></i> Actualizar
                    </button>
                </div>

            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-between">
                <a href="#" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold px-3" id="btnOpenPublicUrl">
                    <i class="fa fa-external-link-alt mr-1"></i> Abrir Vista
                </a>
                <button type="button" class="btn btn-sm btn-secondary font-weight-bold px-4" data-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ══ Modal Agregar Participante Manual ══════════════════════════════════════ --}}
<div class="modal fade" id="modalAddParticipanteManual" tabindex="-1" role="dialog" aria-hidden="true" style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            
            <div class="modal-header border-bottom py-3 px-4" style="background: #0f172a; color: white;">
                <h6 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-user-plus mr-2 text-success"></i>Registrar Participante
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formAddParticipanteManual" autocomplete="off">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="part_nombre" name="nombre" required placeholder="Ej: Juan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="part_apellido" name="apellido" required placeholder="Ej: Pérez">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Correo Electrónico</label>
                        <input type="email" class="form-control form-control-sm" id="part_correo" name="correo" placeholder="ejemplo@ips.gov.py">
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold small text-dark mb-1">Dependencia / Dirección / Servicio</label>
                        <input type="text" class="form-control form-control-sm" id="part_dependencia" name="dependencia" placeholder="Ej: CEDESS / Dirección de Planificación">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Cargo</label>
                            <input type="text" class="form-control form-control-sm" id="part_cargo" name="cargo" placeholder="Ej: Jefe de Departamento">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small text-dark mb-1">Teléfono de Contacto</label>
                            <input type="text" class="form-control form-control-sm" id="part_telefono" name="telefono" placeholder="Ej: 0981 123456">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-4 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm btn-success font-weight-bold px-4" id="btnGuardarParticipanteManual">
                        <i class="fa fa-check mr-1"></i> Guardar Participante
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
