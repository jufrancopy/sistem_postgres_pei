<div class="modal fade" id="tareaModal" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width: 680px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#1e3a5f,#2563eb)">
                <h5 class="modal-title text-white font-weight-bold" id="tareaHeading">Nueva Tarea</h5>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:.8"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <form id="tareaForm">
                    {{ csrf_field() }}
                    <input type="hidden" id="task_id" name="task_id">

                    {{-- ── Switch Principal: Tipo Seguimiento ── --}}
                    <div class="p-3 mb-3 rounded border" style="background:#f0f7ff; border-color:#bfdbfe !important;">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="es_seguimiento" value="0">
                            <input type="checkbox" class="custom-control-input" id="task_es_seguimiento" name="es_seguimiento" value="1">
                            <label class="custom-control-label font-weight-bold" for="task_es_seguimiento" style="color:#3730a3; cursor:pointer; font-size:.88rem">
                                <i class="fa fa-folder-open mr-1 text-indigo" style="color:#4f46e5"></i>
                                ¿Es un <strong>Seguimiento de Expediente Saliente</strong>?
                                <small class="text-muted font-weight-normal d-block" style="font-size:.74rem">
                                    Activa el control de trámites institucionales con número de expediente, destino y fecha de alerta.
                                </small>
                            </label>
                        </div>

                        {{-- ── Campos desplegables de Seguimiento de Expediente ── --}}
                        <div id="containerCamposSeguimiento" class="mt-3 pt-3 border-top" style="display:none; border-top-color:#cbd5e1 !important;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark mb-1">
                                            <i class="fa fa-hashtag mr-1 text-primary"></i>N° de Expediente / Trámite <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nro_expediente" id="task_nro_expediente" class="form-control bg-white" placeholder="Ej: EXP-2026-89412 o 12345/2026">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark mb-1">
                                            <i class="fa fa-paper-plane mr-1 text-primary"></i>Dependencia Destino
                                        </label>
                                        <input type="text" name="destino_dependencia" id="task_destino_dependencia" class="form-control bg-white" placeholder="Ej: Dirección Financiera / MEF" list="listDependenciasSugeridas">
                                        <datalist id="listDependenciasSugeridas">
                                            @if(isset($dependenciasOrganigrama) && count($dependenciasOrganigrama) > 0)
                                                @foreach($dependenciasOrganigrama as $dep)
                                                    <option value="{{ $dep }}">
                                                @endforeach
                                            @else
                                                <option value="DIRECCIÓN DE PLANIFICACIÓN">
                                                <option value="DIRECCIÓN FINANCIERA">
                                                <option value="DIRECCIÓN DE RECURSOS HUMANOS">
                                                <option value="DIRECCIÓN DE INFRAESTRUCTURA">
                                                <option value="DIRECCIÓN MÉDICA / SALUD">
                                                <option value="CONSEJO DE ADMINISTRACIÓN">
                                                <option value="PRESIDENCIA IPS">
                                                <option value="MINISTERIO DE ECONOMÍA Y FINANZAS (MEF)">
                                                <option value="CONTRALORÍA GENERAL DE LA REPÚBLICA">
                                            @endif
                                        </datalist>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold">Título / Asunto <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="task_title" class="form-control" placeholder="Nombre de la tarea o asunto del expediente">
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold">Descripción / Detalle</label>
                        <textarea name="details" id="task_details" class="form-control" rows="2" placeholder="Detalle amplio de la tarea o trámite..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-7" id="boxEtiquetaInput">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">
                                    <i class="fa fa-tag mr-1"></i>Etiqueta
                                </label>
                                <input type="text" name="etiqueta" id="task_etiqueta" class="form-control"
                                       placeholder="Ej: Frontend, Marketing, RRHH..." autocomplete="off">
                                <div id="etiquetasSugeridas" class="d-flex flex-wrap mt-1" style="gap:4px"></div>
                                <small id="helpEtiquetaSeguimiento" class="text-indigo font-weight-bold" style="display:none; color:#4f46e5; font-size:.72rem;">
                                    <i class="fa fa-lock mr-1"></i>Fijo en "SEGUIMIENTO" para control de expedientes salientes
                                </small>
                            </div>
                        </div>
                        <div class="col-md-5" id="boxColorPicker">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">
                                    Color &nbsp;
                                    <span id="colorPreview" style="display:inline-block;width:14px;height:14px;border-radius:50%;vertical-align:middle;background:#6b7280"></span>
                                </label>
                                <input type="hidden" name="color" id="color_input" value="#6b7280">
                                <div class="color-palette" id="colorPaleta"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">Responsable</label>
                                <select name="assigned_to" id="task_assigned_to" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">Estado</label>
                                <select name="status" id="task_status" class="form-control">
                                    <option value="0">Pendiente / Backlog</option>
                                    <option value="1">En Progreso / Ejecución</option>
                                    <option value="3">En Revisión</option>
                                    <option value="2">Hecho / Finalizado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">
                                    <i class="fa fa-calendar-plus mr-1 text-success"></i>Fecha de inicio
                                </label>
                                <input type="date" name="fecha_inicio" id="task_fecha_inicio" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold" id="lblFechaVencimiento">
                                    <i class="fa fa-clock mr-1 text-warning"></i>Fecha de vencimiento
                                </label>
                                <input type="date" name="fecha_vencimiento" id="task_fecha_vencimiento" class="form-control">
                                <small class="text-muted d-block" id="helpFechaVencimiento">Opcional — genera alertas visuales al acercarse</small>
                            </div>
                        </div>
                    </div>

                    {{-- ── Opciones adicionales: Reunión / Documento ── --}}
                    <div class="p-2.5 rounded bg-light border mt-2" id="boxOpcionesAdicionales">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="es_reunion" value="0">
                                    <input type="checkbox" class="custom-control-input" id="task_es_reunion" name="es_reunion" value="1">
                                    <label class="custom-control-label font-weight-bold small" for="task_es_reunion">
                                        <i class="fa fa-users mr-1 text-info"></i>Esta tarea es una <strong>Reunión</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="es_documento" value="0">
                                    <input type="checkbox" class="custom-control-input" id="task_es_documento" name="es_documento" value="1">
                                    <label class="custom-control-label font-weight-bold small" for="task_es_documento">
                                        <i class="fa fa-file-alt mr-1 text-warning"></i>Esta tarea es un <strong>Documento</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer py-2 px-4 bg-light">
                <button type="button" class="btn btn-sm btn-secondary px-4" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="tareaForm" class="btn btn-sm btn-primary font-weight-bold px-4 shadow-sm" id="saveTareaBtn">
                    <i class="fa fa-save mr-1"></i>Guardar Tarea
                </button>
            </div>
        </div>
    </div>
</div>
