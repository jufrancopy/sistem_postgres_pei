{{-- ══ Modal Documentos — solo HTML, JS en show.blade.php @push('scripts') ══ --}}
<div class="modal fade" id="modalDocumentos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width:80vw">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden">
            <div class="modal-header py-3" style="background:linear-gradient(135deg,#78350f,#d97706)">
                <div>
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-file-alt mr-2"></i>Documentos de la Actividad
                    </h5>
                    <small class="text-white" style="opacity:.8;font-size:.75rem" id="documentosSubtitulo"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            {{-- Barra de filtros --}}
            <div class="px-3 pt-3 pb-2 d-flex align-items-center flex-wrap" style="gap:.5rem;background:#fffbeb;border-bottom:1px solid #fde68a">
                <span class="font-weight-bold text-uppercase" style="font-size:.72rem;color:#92400e">
                    <i class="fa fa-filter mr-1"></i>Filtrar:
                </span>
                <button type="button" class="btn btn-sm btn-warning active" id="docFiltroTodos" style="font-size:.75rem;border-radius:20px">
                    Todos
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" id="docFiltroPendientes" style="font-size:.75rem;border-radius:20px">
                    <i class="fa fa-clock mr-1"></i>Pendientes
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" id="docFiltroFinalizados" style="font-size:.75rem;border-radius:20px">
                    <i class="fa fa-check mr-1"></i>Finalizados
                </button>
                <span class="badge ml-auto" style="background:#d97706;color:#fff;font-size:.75rem" id="documentosCount">0</span>
            </div>

            <div class="modal-body p-0">
                <div id="documentosVacio" class="text-center py-5" style="display:none">
                    <i class="fa fa-folder-open fa-3x mb-3" style="color:#d97706;opacity:.4"></i>
                    <p class="text-muted mb-0">No hay documentos registrados en esta actividad.</p>
                    <small class="text-muted">Al crear una tarea, marcá la opción <strong>"Esta tarea es un Documento"</strong>.</small>
                </div>

                <div class="table-responsive px-3 pb-3" id="documentosTablaWrap">
                    <table class="table table-hover table-sm mb-0" style="font-size:.86rem">
                        <thead>
                            <tr style="background:#fef3c7">
                                <th style="width:240px;color:#78350f">Título</th>
                                <th style="color:#78350f">Descripción</th>
                                <th style="width:110px;color:#78350f">Etiqueta</th>
                                <th style="width:140px;color:#78350f">Responsable</th>
                                <th style="width:80px;text-align:center;color:#78350f">Estado</th>
                                <th style="width:90px;text-align:center;color:#78350f">Creado</th>
                                <th style="width:140px;text-align:center;color:#78350f">Evidencias</th>
                            </tr>
                        </thead>
                        <tbody id="documentosBody">
                            <tr id="documentosLoading">
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fa fa-spinner fa-spin mr-2"></i>Cargando documentos...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer py-2" style="background:#fffbeb;border-top:1px solid #fde68a">
                <small class="text-muted mr-auto" style="font-size:.75rem">
                    <i class="fa fa-info-circle mr-1"></i>
                    Tareas marcadas como "Documento" al momento de creación.
                </small>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
