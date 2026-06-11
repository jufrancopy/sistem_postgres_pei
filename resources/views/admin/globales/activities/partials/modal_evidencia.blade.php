<div class="modal fade" id="evidenceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e3a5f,#2563eb)">
                <h5 class="modal-title text-white"><i class="fa fa-paperclip mr-2"></i>Agregar Evidencia</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="evidence_task_id">

                <div class="form-group">
                    <label class="small font-weight-bold">Tipo de evidencia</label>
                    <div class="d-flex flex-wrap" style="gap:6px;margin-top:6px">
                        <button type="button" class="btn btn-primary btn-sm ev-type-btn" data-type="url"
                                onclick="toggleEvidenceSection('url')">
                            <i class="fa fa-link mr-1"></i>Enlace URL
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm ev-type-btn" data-type="image"
                                onclick="toggleEvidenceSection('image')">
                            <i class="fa fa-image mr-1"></i>Imagen (máx. 2MB)
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm ev-type-btn" data-type="document"
                                onclick="toggleEvidenceSection('document')">
                            <i class="fa fa-file-alt mr-1"></i>Documento (máx. 5MB)
                        </button>
                    </div>
                    <input type="hidden" id="evidence_type" value="url">
                </div>

                <div id="ev_url_section">
                    <div class="form-group">
                        <label class="small font-weight-bold">Descripción del enlace</label>
                        <input type="text" id="ev_label" class="form-control" placeholder="Ej: Carpeta compartida">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">URL</label>
                        <input type="url" id="ev_url" class="form-control" placeholder="https://...">
                    </div>
                </div>

                <div id="ev_file_section" style="display:none">
                    <div class="form-group">
                        <label for="ev_file" id="ev_file_label"
                               style="display:flex;align-items:center;gap:10px;border:2px dashed #cbd5e1;border-radius:8px;padding:16px;cursor:pointer">
                            <i class="fa fa-upload fa-lg text-primary"></i>
                            <span id="ev_file_name" class="text-muted small">Hacé clic para seleccionar un archivo</span>
                            <input type="file" id="ev_file" style="display:none">
                        </label>
                        <small id="ev_file_hint" class="text-muted d-block mt-1"></small>
                    </div>
                    <div id="ev_size_warning" class="alert alert-warning small" style="display:none">
                        <i class="fa fa-exclamation-triangle mr-1"></i>
                        El archivo supera el límite. Subilo a tu carpeta compartida y registrá el enlace.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSaveEvidence">
                    <i class="fa fa-save mr-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEvidenceSection(type) {
    document.getElementById('evidence_type').value = type;
    document.querySelectorAll('.ev-type-btn').forEach(function(btn) {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary');
    });
    var activeBtn = document.querySelector('.ev-type-btn[data-type="' + type + '"]');
    if (activeBtn) { activeBtn.classList.remove('btn-outline-primary'); activeBtn.classList.add('btn-primary'); }
    document.getElementById('ev_url_section').style.display  = type === 'url'  ? 'block' : 'none';
    document.getElementById('ev_file_section').style.display = type !== 'url'  ? 'block' : 'none';
    if (type !== 'url') {
        document.getElementById('ev_file_hint').textContent = type === 'image'
            ? 'Máximo 2MB. Formatos: jpg, png, gif, webp'
            : 'Máximo 5MB. Formatos: pdf, doc, docx, xls, xlsx';
    }
}
</script>
