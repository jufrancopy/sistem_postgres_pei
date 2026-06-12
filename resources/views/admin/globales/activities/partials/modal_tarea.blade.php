<div class="modal fade" id="tareaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e3a5f,#2563eb)">
                <h5 class="modal-title text-white" id="tareaHeading">Nueva Tarea</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="tareaForm">
                    {{ csrf_field() }}
                    <input type="hidden" id="task_id" name="task_id">

                    <div class="form-group">
                        <label class="small font-weight-bold">Título <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="task_title" class="form-control" placeholder="Nombre de la tarea">
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Descripción</label>
                        <textarea name="details" id="task_details" class="form-control" rows="2" placeholder="Detalle de la tarea..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="small font-weight-bold">
                                    <i class="fa fa-tag mr-1"></i>Etiqueta
                                </label>
                                <input type="text" name="etiqueta" id="task_etiqueta" class="form-control"
                                       placeholder="Ej: Frontend, Marketing, RRHH..." autocomplete="off">
                                {{-- Etiquetas existentes en esta actividad --}}
                                <div id="etiquetasSugeridas" class="d-flex flex-wrap mt-1" style="gap:4px"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
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
                            <div class="form-group">
                                <label class="small font-weight-bold">Responsable</label>
                                <select name="assigned_to" id="task_assigned_to" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">
                                    <i class="fa fa-clock mr-1 text-warning"></i>Fecha de vencimiento
                                </label>
                                <input type="date" name="fecha_vencimiento" id="task_fecha_vencimiento" class="form-control">
                                <small class="text-muted">Opcional — genera alertas visuales al acercarse</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Estado</label>
                        <select name="status" id="task_status" class="form-control">
                            <option value="0">Pendiente / Backlog</option>
                            <option value="1">En Progreso / Ejecución</option>
                            <option value="3">En Revisión</option>
                            <option value="2">Hecho / Finalizado</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="tareaForm" class="btn btn-primary" id="saveTareaBtn">
                    <i class="fa fa-save mr-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>
