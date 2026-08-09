{{-- ══ Modal Editor Proforma Acta MECIP (Word Interno) ════════════════════════ --}}
<div class="modal fade" id="modalEditorActaMecip" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" style="background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 1055;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document" style="max-width: 1100px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #f8fafc;">
            
            {{-- Toolbar superior tipo Word / Suite Ofimática --}}
            <div class="modal-header border-bottom d-flex align-items-center justify-content-between px-4 py-3" 
                 style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff;">
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(96, 165, 250, 0.3); display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-file-signature text-primary" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size: 1.1rem; letter-spacing: -0.2px;">
                                Proforma de Acta de Reunión MECIP
                            </h5>
                            <span id="actaEstadoBadge" class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                Borrador
                            </span>
                        </div>
                        <small class="text-white-50" style="font-size: 0.78rem;" id="actaSubtituloTarea">Redactando acta en línea</small>
                    </div>
                </div>

                {{-- Botones de Acción en Barra Superior --}}
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-info border-info text-info font-weight-bold px-3" id="btnCompartirQrActa" title="Ver Código QR para compartir con participantes">
                        <i class="fa fa-qrcode mr-1"></i> QR Participantes
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light font-weight-bold px-3" id="btnImprimirActaMecip" title="Imprimir / Exportar a formato oficial">
                        <i class="fa fa-print mr-1"></i> Imprimir / PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold px-3 shadow-sm" id="btnGuardarActaMecip">
                        <i class="fa fa-save mr-1"></i> Guardar Acta
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold px-2 ml-2" id="btnFinalizarReunionMecip" title="Finalizar Reunión y Oficializar Acta">
                        <i class="fa fa-check-double mr-1"></i> Finalizar
                    </button>
                    <button type="button" class="close text-white ml-2" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.85; font-size: 1.5rem; text-shadow: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            {{-- Cuerpo: Hoja tipo Documento Word (A4 Paper Aesthetic) --}}
            <div class="modal-body p-3 p-md-4" style="background: #e2e8f0; overflow-y: auto;">
                
                {{-- Alerta de Notificación / Auto-guardado --}}
                <div id="actaAlertContainer" class="mb-3" style="display: none;"></div>

                {{-- Hoja Word Centrada --}}
                <div class="card mx-auto shadow border-0 bg-white" style="max-width: 960px; border-radius: 6px; padding: 40px 48px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05) !important;">
                    
                    <form id="formActaMecip" autocomplete="off">
                        <input type="hidden" id="acta_task_id" name="task_id" value="">
                        <input type="hidden" id="acta_uuid" name="uuid" value="">
                        <input type="hidden" id="acta_estado" name="estado" value="borrador">

                        {{-- ── 1. ENCABEZADO OFICIAL MECIP ── --}}
                        <div class="text-center mb-4 pb-3 border-bottom" style="border-bottom-width: 2px !important; border-bottom-color: #0f172a !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div style="text-align: left;">
                                    <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                        MECIP:2015
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        <label class="mb-0 font-weight-bold text-muted small">Nº DE ACTA:</label>
                                        <input type="text" class="form-control form-control-sm font-weight-bold text-dark text-right" 
                                               id="acta_numero" name="numero_acta" placeholder="Ej: 35/2026" 
                                               style="width: 140px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.85rem;">
                                    </div>
                                </div>
                            </div>

                            <input type="text" class="form-control form-control-sm text-center font-weight-bold text-uppercase border-0 bg-transparent p-0 mb-1" 
                                   id="acta_institucion" name="institucion" value="INSTITUTO DE PREVISIÓN SOCIAL"
                                   style="font-size: 1.15rem; color: #0f172a; letter-spacing: 0.5px;">

                            <input type="text" class="form-control form-control-sm text-center font-weight-semibold border-0 bg-transparent p-0 mb-2 text-muted" 
                                   id="acta_dependencia" name="dependencia" value="CENTRO DE ENSEÑANZA, DOCUMENTACIÓN Y ESTUDIOS DE LA SEGURIDAD SOCIAL - CEDESS"
                                   style="font-size: 0.9rem; color: #475569;">

                            <h4 class="font-weight-bolder text-dark mb-0 text-uppercase" style="letter-spacing: 1px; font-size: 1.25rem;">
                                ACTA DE REUNIÓN
                            </h4>
                        </div>

                        {{-- ── 2. TABLA METADATOS DE LA REUNIÓN ── --}}
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered mb-0" style="border: 2px solid #334155; font-size: 0.85rem;">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold bg-light" style="width: 18%; vertical-align: middle; color: #1e293b;">LUGAR:</td>
                                        <td colspan="3" class="p-1">
                                            <input type="text" class="form-control form-control-sm border-0 font-weight-medium" 
                                                   id="acta_lugar" name="lugar" value="REUNIÓN VIRTUAL" placeholder="Lugar o medio de la reunión">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold bg-light" style="vertical-align: middle; color: #1e293b;">FECHA:</td>
                                        <td class="p-1" style="width: 32%;">
                                            <input type="date" class="form-control form-control-sm border-0" 
                                                   id="acta_fecha" name="fecha">
                                        </td>
                                        <td class="font-weight-bold bg-light text-center" style="width: 15%; vertical-align: middle; color: #1e293b;">HORA:</td>
                                        <td class="p-1" style="width: 35%;">
                                            <div class="d-flex align-items-center" style="gap: 4px;">
                                                <span class="text-muted small">Desde:</span>
                                                <input type="text" class="form-control form-control-sm border-0 p-1 text-center" 
                                                       id="acta_hora_desde" name="hora_desde" placeholder="15:00" style="width: 70px;">
                                                <span class="text-muted small ml-2">Hasta:</span>
                                                <input type="text" class="form-control form-control-sm border-0 p-1 text-center" 
                                                       id="acta_hora_hasta" name="hora_hasta" placeholder="16:00" style="width: 70px;">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold bg-light" style="vertical-align: top; padding-top: 8px; color: #1e293b;">CONVOCADOS:</td>
                                        <td colspan="3" class="p-2">
                                            <textarea class="form-control border-0 p-0" id="acta_convocados" name="convocados_texto" rows="2" 
                                                      placeholder="Lista de personas e instancias convocadas a la reunión..." style="font-size: 0.85rem; resize: vertical;"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold bg-light" style="vertical-align: top; padding-top: 8px; color: #1e293b;">TEMAS A TRATAR:</td>
                                        <td colspan="3" class="p-2">
                                            <textarea class="form-control border-0 p-0" id="acta_temas" name="temas_tratar" rows="2" 
                                                      placeholder="Temario u orden del día..." style="font-size: 0.85rem; resize: vertical;"></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- ── 3. SECCIONES DEL ACTA MECIP ── --}}
                        
                        {{-- OBJETIVO --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2" style="border-left: 4px solid #2563eb; padding-left: 10px;">
                                <h6 class="font-weight-bold text-dark text-uppercase mb-0" style="letter-spacing: 0.5px; font-size: 0.95rem;">
                                    OBJETIVO
                                </h6>
                            </div>
                            <div class="p-2 rounded" style="border: 1px solid #cbd5e1; background: #fafbfc;">
                                <textarea class="form-control border-0 bg-transparent p-1" id="acta_objetivo" name="objetivo" rows="2" 
                                          placeholder="La reunión tuvo por objeto..." style="font-size: 0.88rem; line-height: 1.6;"></textarea>
                            </div>
                        </div>

                        {{-- DESARROLLO --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2" style="border-left: 4px solid #2563eb; padding-left: 10px;">
                                <h6 class="font-weight-bold text-dark text-uppercase mb-0" style="letter-spacing: 0.5px; font-size: 0.95rem;">
                                    DESARROLLO (PUNTOS TRATADOS)
                                </h6>
                                <small class="text-muted"><i class="fa fa-info-circle mr-1"></i>Redacte los puntos abordados y deliberaciones</small>
                            </div>
                            <div class="p-2 rounded" style="border: 1px solid #cbd5e1; background: #ffffff;">
                                <textarea class="form-control border-0 p-2" id="acta_desarrollo" name="desarrollo" rows="6" 
                                          placeholder="1. Validación...&#10;2. Análisis técnico...&#10;3. Acuerdos operativos..." 
                                          style="font-size: 0.88rem; line-height: 1.7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;"></textarea>
                            </div>
                        </div>

                        {{-- ACUERDOS --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2" style="border-left: 4px solid #2563eb; padding-left: 10px;">
                                <h6 class="font-weight-bold text-dark text-uppercase mb-0" style="letter-spacing: 0.5px; font-size: 0.95rem;">
                                    ACUERDOS
                                </h6>
                            </div>
                            <div class="p-2 rounded" style="border: 1px solid #cbd5e1; background: #fafbfc;">
                                <textarea class="form-control border-0 bg-transparent p-1" id="acta_acuerdos" name="acuerdos" rows="3" 
                                          placeholder="Resumen de acuerdos tomados en consenso..." style="font-size: 0.88rem; line-height: 1.6;"></textarea>
                            </div>
                        </div>

                        {{-- COMPROMISOS (TABLA DINÁMICA) --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2" style="border-left: 4px solid #2563eb; padding-left: 10px;">
                                <h6 class="font-weight-bold text-dark text-uppercase mb-0" style="letter-spacing: 0.5px; font-size: 0.95rem;">
                                    COMPROMISOS ASUMIDOS
                                </h6>
                                <button type="button" class="btn btn-xs btn-primary font-weight-bold px-2 py-1" id="btnAddCompromisoRow" style="font-size: 0.75rem; border-radius: 6px;">
                                    <i class="fa fa-plus mr-1"></i> Agregar Compromiso
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="tblCompromisos" style="border: 1.5px solid #64748b; font-size: 0.83rem;">
                                    <thead class="bg-light text-dark font-weight-bold">
                                        <tr>
                                            <th style="width: 35%; border-color: #64748b;">RESPONSABLE</th>
                                            <th style="width: 55%; border-color: #64748b;">COMPROMISO</th>
                                            <th style="width: 10%; border-color: #64748b; text-align: center;">ACCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyCompromisos">
                                        {{-- Inyectado dinámicamente --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ── 4. PARTICIPANTES Y FIRMAS DIGITALES / QR ── --}}
                        <div class="mb-4 pt-3 border-top" style="border-top-color: #cbd5e1 !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2" style="border-left: 4px solid #10b981; padding-left: 10px;">
                                <div>
                                    <h6 class="font-weight-bold text-dark text-uppercase mb-0" style="letter-spacing: 0.5px; font-size: 0.95rem;">
                                        PARTICIPANTES Y ASISTENCIA REGISTRADA
                                    </h6>
                                    <small class="text-muted">Participantes que firmaron o se registraron mediante Código QR / Manual</small>
                                </div>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <button type="button" class="btn btn-xs btn-outline-success font-weight-bold px-2 py-1" id="btnRefreshParticipantes" style="font-size: 0.75rem; border-radius: 6px;" title="Actualizar lista">
                                        <i class="fa fa-sync-alt mr-1"></i> Actualizar
                                    </button>
                                    <button type="button" class="btn btn-xs btn-success font-weight-bold px-2 py-1" id="btnAddParticipanteManual" style="font-size: 0.75rem; border-radius: 6px;">
                                        <i class="fa fa-user-plus mr-1"></i> Agregar Manual
                                    </button>
                                </div>
                            </div>

                            {{-- Tabla de Participantes --}}
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-bordered table-hover mb-0" id="tblParticipantesActa" style="border: 1.5px solid #cbd5e1; font-size: 0.8rem;">
                                    <thead class="bg-light text-dark font-weight-bold">
                                        <tr>
                                            <th style="width: 22%;">Nombre y Apellido</th>
                                            <th style="width: 20%;">Correo Electrónico</th>
                                            <th style="width: 20%;">Dependencia</th>
                                            <th style="width: 18%;">Cargo</th>
                                            <th style="width: 12%;">Teléfono</th>
                                            <th style="width: 8%; text-align: center;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyParticipantesActa">
                                        {{-- Inyectado dinámicamente --}}
                                    </tbody>
                                </table>
                            </div>

                            {{-- Banner Informativo QR para la reunión --}}
                            <div class="p-3 rounded d-flex align-items-center justify-content-between" 
                                 style="background: #f0fdf4; border: 1px solid #86efac;">
                                <div class="d-flex align-items-center" style="gap: 12px;">
                                    <div id="actaQrThumbnail" style="width: 48px; height: 48px; background: white; border-radius: 6px; padding: 2px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                        <i class="fa fa-qrcode text-success" style="font-size: 1.8rem;"></i>
                                    </div>
                                    <div>
                                        <strong class="text-success" style="font-size: 0.88rem;">Código QR activo para esta reunión</strong>
                                        <div class="text-muted small" id="actaQrStatusText">Los participantes pueden escanear el QR para leer el acta y registrar su presencia.</div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-success font-weight-bold px-3" id="btnProyectarQr" style="border-radius: 8px;">
                                    <i class="fa fa-expand mr-1"></i> Proyectar / Compartir QR
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Footer con atajos y estado --}}
            <div class="modal-footer d-flex align-items-center justify-content-between px-4 py-2 bg-white border-top">
                <div class="text-muted small">
                    <i class="fa fa-clock mr-1"></i> Estado del Acta: <span id="actaFooterStatus" class="font-weight-bold text-dark">Borrador</span>
                    <span class="mx-2">•</span>
                    <span id="actaParticipantesCountBadge" class="badge badge-secondary">0 participantes</span>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold px-3" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold px-4" id="btnGuardarActaMecipFooter">
                        <i class="fa fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal para la Firma del Moderador --}}
<div class="modal fade" id="modalFirmaModerador" tabindex="-1" role="dialog" aria-hidden="true" style="background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
            <div class="modal-header border-0 pb-2 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-dark w-100 text-center" style="font-size: 1.15rem; letter-spacing: -0.2px;">
                    <i class="fa fa-file-signature text-primary mr-2"></i> Firma del Moderador
                </h5>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="small text-muted text-center mb-3">
                    Dibuje su firma en el recuadro para oficializar y sellar criptográficamente el Acta MECIP. Una vez sellada, no podrá ser modificada.
                </p>
                
                <div class="border rounded p-2 bg-light text-center" style="border-color: #cbd5e1 !important; touch-action: none;">
                    <canvas id="signature-pad-moderador" class="signature-pad w-100 bg-white rounded" style="border: 1px dashed #94a3b8; height: 180px; cursor: crosshair;"></canvas>
                    <div class="d-flex justify-content-between align-items-center mt-2 px-2">
                        <span class="small text-muted"><i class="fa fa-info-circle mr-1"></i> Área de firma</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="btnClearSignatureModerador" style="font-size: 0.75rem; border-radius: 6px;">Limpiar</button>
                    </div>
                </div>
                <div class="mt-3 p-2 rounded text-left" style="background: #f0fdf4; border: 1px solid #bbf7d0; font-size: 0.75rem; color: #166534; line-height: 1.3;">
                    <i class="fa fa-shield-alt mr-1"></i> <strong>Proceso de Sellado Criptográfico:</strong> Al firmar y sellar, el sistema genera un Hash SHA-256 único combinando su firma, las firmas de los participantes y el contenido del acta. Cualquier intento de alteración posterior romperá el sello de seguridad.
                </div>
                
                <input type="hidden" id="firmaModeradorInput">
            </div>
            <div class="modal-footer border-0 px-4 pb-4 d-flex" style="gap: 10px;">
                <button type="button" class="btn btn-light font-weight-bold flex-fill" data-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                <button type="button" class="btn btn-primary font-weight-bold flex-fill" id="btnConfirmarFirmaModerador" style="border-radius: 8px;">
                    <i class="fa fa-lock mr-1"></i> Firmar y Sellar Acta
                </button>
            </div>
        </div>
    </div>
</div>
