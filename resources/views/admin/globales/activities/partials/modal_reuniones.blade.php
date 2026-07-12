{{-- ══ Modal Reuniones — solo HTML, el JS está en show.blade.php @push('scripts') ══ --}}
<div class="modal fade" id="modalReuniones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width:75vw">
        <div class="modal-content">
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#1e3a5f,#2563eb)">
                <div>
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-users mr-2"></i>Reuniones
                    </h5>
                    <small class="text-white" style="opacity:.75;font-size:.75rem" id="reunionesSubtitulo"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div class="px-3 pt-3 pb-2 d-flex align-items-center" style="gap:.5rem;background:#f8f9fa;border-bottom:1px solid #dee2e6">
                    <span class="font-weight-bold text-uppercase" style="font-size:.72rem;color:#6c757d">Filtrar:</span>
                    <button type="button" class="btn btn-sm btn-dark active" id="filtroTodas" style="font-size:.75rem;border-radius:20px">Todas</button>
                    <button type="button" class="btn btn-sm btn-outline-warning" id="filtroPendientes" style="font-size:.75rem;border-radius:20px">
                        <i class="fa fa-clock mr-1"></i>Pendientes
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" id="filtroFinalizadas" style="font-size:.75rem;border-radius:20px">
                        <i class="fa fa-check mr-1"></i>Finalizadas
                    </button>
                    <span class="badge badge-dark ml-auto" id="reunionesCount" style="font-size:.75rem"></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:200px">Título</th>
                                <th>Descripción</th>
                                <th style="width:90px">Etiqueta</th>
                                <th style="width:130px">Responsable</th>
                                <th style="width:80px" class="text-center">Estado</th>
                                <th style="width:80px" class="text-center">Fecha</th>
                                <th style="width:120px" class="text-center">Acta de Reunión</th>
                            </tr>
                        </thead>
                        <tbody id="reunionesBody">
                            <tr id="reunionesLoading">
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fa fa-spinner fa-spin mr-2"></i>Cargando...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ Modal Visor PDF ══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalActaPdf" tabindex="-1" aria-hidden="true" style="z-index:1060">
    <div class="modal-dialog" style="max-width:85vw">
        <div class="modal-content">
            <div class="modal-header py-2" style="background:#343a40">
                <h6 class="modal-title text-white mb-0">
                    <i class="fa fa-file-pdf mr-2 text-danger"></i>
                    <span id="actaPdfTitulo">Acta de Reunión</span>
                </h6>
                <div class="d-flex align-items-center" style="gap:.5rem">
                    <a id="actaPdfDescargar" href="#" target="_blank" download
                       class="btn btn-sm btn-outline-light py-0 px-2" style="font-size:.75rem">
                        <i class="fa fa-download mr-1"></i>Descargar
                    </a>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
            </div>
            <div class="modal-body p-0" style="height:80vh">
                <iframe id="actaPdfFrame" src="" style="width:100%;height:100%;border:none"></iframe>
            </div>
        </div>
    </div>
</div>
