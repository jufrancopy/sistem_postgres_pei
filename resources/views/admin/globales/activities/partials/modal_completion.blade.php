<div class="modal fade" id="completionModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#059669,#10b981)">
                <h5 class="modal-title text-white"><i class="fa fa-check-circle mr-2"></i>Completar Tarea</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Describí cómo se completó la tarea. Este comentario queda registrado.</p>
                <input type="hidden" id="completion_task_id">
                <input type="hidden" id="completion_new_status">
                <div class="form-group">
                    <label class="small font-weight-bold">Comentario de cierre <span class="text-danger">*</span></label>
                    <textarea id="completion_note" class="form-control" rows="3"
                              placeholder="Ej: Se envió el informe al director el día..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-sm" id="btnConfirmComplete">
                    <i class="fa fa-check mr-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
