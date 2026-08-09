{{-- ══ Modal Reuniones ══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalReuniones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width:85vw">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;overflow:hidden">

            {{-- Header --}}
            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#1e3a5f,#2563eb)">
                <div>
                    <h5 class="modal-title text-white mb-0 font-weight-bold">
                        <i class="fa fa-users mr-2"></i>Reuniones
                    </h5>
                    <small class="text-white" style="opacity:.7;font-size:.75rem" id="reunionesSubtitulo"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:.8"><span>&times;</span></button>
            </div>

            {{-- Filtros --}}
            <div class="px-4 py-2 d-flex align-items-center flex-wrap" style="gap:.5rem;background:#f1f5f9;border-bottom:1px solid #e2e8f0">
                <span class="font-weight-bold text-uppercase mr-1" style="font-size:.7rem;color:#64748b;letter-spacing:.05em">Filtrar:</span>
                <button type="button" class="btn btn-sm btn-dark active" id="filtroTodas" style="font-size:.73rem;border-radius:20px;padding:2px 14px">
                    <i class="fa fa-list mr-1"></i>Todas
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" id="filtroPendientes" style="font-size:.73rem;border-radius:20px;padding:2px 14px">
                    <i class="fa fa-clock mr-1"></i>Pendientes
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" id="filtroFinalizadas" style="font-size:.73rem;border-radius:20px;padding:2px 14px">
                    <i class="fa fa-check mr-1"></i>Finalizadas
                </button>
                <span class="badge badge-pill badge-secondary ml-auto" id="reunionesCount" style="font-size:.75rem;padding:5px 10px"></span>
            </div>

            {{-- Body con DataTable --}}
            <div class="modal-body p-0" style="overflow:hidden">
                <div class="px-4 pt-3 pb-4">
                    <table id="tblReuniones" class="table table-hover table-sm w-100" style="font-size:.83rem">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th style="min-width:180px">Título</th>
                                <th>Descripción</th>
                                <th style="width:100px">Etiqueta</th>
                                <th style="width:140px">Responsable</th>
                                <th style="width:90px" class="text-center">Estado</th>
                                <th style="width:90px" class="text-center">Fecha</th>
                                <th style="min-width:180px" class="text-center">Acta MECIP / Evidencia</th>
                            </tr>
                        </thead>
                        <tbody id="reunionesBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer py-2 px-4" style="background:#f8fafc;border-top:1px solid #e2e8f0">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

{{-- ══ Modal Visor PDF ══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalActaPdf" tabindex="-1" aria-hidden="true" style="z-index:1060">
    <div class="modal-dialog" style="max-width:85vw">
        <div class="modal-content border-0 shadow-lg" style="border-radius:10px;overflow:hidden">
            <div class="modal-header py-2 px-3" style="background:#1e293b">
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
