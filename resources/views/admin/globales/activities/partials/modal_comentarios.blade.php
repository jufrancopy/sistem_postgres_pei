<style>
.comentario-burbuja {
    display: flex; gap: 10px; align-items: flex-start; margin-bottom: 12px;
}
.comentario-burbuja.mio { flex-direction: row-reverse; }
.comentario-avatar {
    width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: .72rem; font-weight: 700; flex-shrink: 0;
    background: #dbeafe; color: #1e40af;
}
.comentario-burbuja.mio .comentario-avatar { background: #dcfce7; color: #166534; }
.comentario-cuerpo { max-width: 80%; }
.comentario-texto {
    background: #f1f5f9; border-radius: 0 12px 12px 12px;
    padding: 8px 12px; font-size: .83rem; color: #1e293b; line-height: 1.4;
}
.comentario-burbuja.mio .comentario-texto {
    background: #dbeafe; border-radius: 12px 0 12px 12px;
}
.comentario-meta { font-size: .68rem; color: #94a3b8; margin-top: 3px; }
.comentario-burbuja.mio .comentario-meta { text-align: right; }
#comentariosLista { max-height: 340px; overflow-y: auto; padding: 4px 0; }
</style>

<div class="modal fade" id="modalComentarios" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e3a5f,#2563eb)">
                <h5 class="modal-title text-white">
                    <i class="fa fa-comment-alt mr-2"></i>
                    Comentarios — <span id="comentariosTareaTitle" class="font-weight-normal"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="comentariosTaskId">

                {{-- Lista de comentarios --}}
                <div id="comentariosLista">
                    <div class="text-center py-4 text-muted" id="comentariosLoading">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                    </div>
                </div>

                {{-- Nuevo comentario --}}
                <div class="border-top pt-3 mt-2">
                    <div class="d-flex gap-2" style="gap:8px">
                        <textarea id="nuevoComentario" class="form-control form-control-sm"
                                  rows="2" placeholder="Escribí un comentario..."
                                  style="border-radius:8px;resize:none"></textarea>
                        <button class="btn btn-primary btn-sm align-self-end" id="btnEnviarComentario"
                                style="border-radius:8px;padding:6px 12px;flex-shrink:0">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
