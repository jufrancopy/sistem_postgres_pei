{{-- Modal para Reordenar Estructura PEI por Drag & Drop --}}
<style>
    @media (min-width: 768px) {
        .modal-dialog-pei-reordenar {
            max-width: 93vw !important;
            width: 93vw !important;
            margin: 1.25rem auto !important;
        }
    }
    .badge-purple {
        background-color: #7e22ce !important;
        color: #ffffff !important;
    }
    .nodo-pei-card:hover {
        border-color: #94a3b8 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }
    .sortable-ghost .nodo-pei-card {
        background-color: #f1f5f9 !important;
        border: 2px dashed #3b82f6 !important;
        opacity: 0.7;
    }
    .sortable-chosen .nodo-pei-card {
        background-color: #eff6ff !important;
        border-color: #2563eb !important;
    }
</style>

<div class="modal fade" id="modalReordenarPei" tabindex="-1" role="dialog" aria-labelledby="modalReordenarPeiLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-pei-reordenar" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <span class="badge badge-warning font-weight-bold px-2.5 py-1" style="font-size: .75rem; letter-spacing: .04em;">
                        <i class="fa fa-sort-amount-asc mr-1"></i> DRAG & DROP
                    </span>
                    <h5 class="modal-title font-weight-bold text-white mb-0" id="modalReordenarPeiLabel" style="font-size: 1.08rem;">
                        Reordenar Estructura PEI: <span class="text-warning">{{ trim(strip_tags($master->name)) }}</span>
                    </h5>
                </div>
                <button type="button" class="close text-white opacity-80" data-dismiss="modal" aria-label="Cerrar" style="font-size: 1.4rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4 bg-light">
                {{-- Banner explicativo --}}
                <div class="p-3 mb-3 rounded-lg border bg-white shadow-xs d-flex align-items-center" style="border-left: 4px solid #f59e0b !important; gap: 12px;">
                    <div class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                        <i class="fa fa-info-circle" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: .88rem;">Instrucciones para Reordenar</h6>
                        <small class="text-muted" style="font-size: .78rem;">
                            Arrastrá cualquier elemento por el ícono <i class="fa fa-grip-vertical text-muted"></i> para modificar su posición.
                            Los elementos se mantienen estrictamente protegidos dentro de su mismo tipo jerárquico.
                        </small>
                    </div>
                </div>

                {{-- Leyenda de Niveles Jerárquicos --}}
                <div class="d-flex align-items-center flex-wrap mb-3 px-2" style="gap: 8px; font-size: 0.72rem;">
                    <span class="text-muted font-weight-bold mr-1">Jerarquía del Plan:</span>
                    <span class="badge badge-primary px-2 py-1"><i class="fa fa-layer-group mr-1"></i>Ejes</span>
                    <span class="badge badge-info px-2 py-1"><i class="fa fa-bullseye mr-1"></i>Obj. Estratégicos</span>
                    <span class="badge badge-warning text-dark px-2 py-1"><i class="fa fa-crosshairs mr-1"></i>Obj. Específicos</span>
                    <span class="badge badge-purple text-white px-2 py-1"><i class="fa fa-bolt mr-1"></i>Acc. Estratégicas</span>
                    <span class="badge badge-success px-2 py-1"><i class="fa fa-tasks mr-1"></i>Acc. Operativas (Plan 100 Días)</span>
                </div>

                {{-- Árbol Draggable --}}
                <div class="card border shadow-xs rounded-lg overflow-hidden">
                    <div class="card-header bg-white py-2.5 px-3 border-bottom d-flex align-items-center justify-content-between">
                        <span class="font-weight-bold text-dark" style="font-size: .85rem;">
                            <i class="fa fa-sitemap mr-1 text-primary"></i> Estructura Jerárquica Completa
                        </span>
                        <div style="gap: 6px;" class="d-flex align-items-center">
                            <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2.5" id="btnExpandirTodoTreePei">
                                <i class="fa fa-plus-square mr-1"></i> Expandir Todo
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2.5" id="btnColapsarTodoTreePei">
                                <i class="fa fa-minus-square mr-1"></i> Colapsar Todo
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3 bg-white" id="contenedorArbolDraggablePei" style="max-height: 60vh; overflow-y: auto;">
                        @php
                            $childrenTree = $treeArray['children'] ?? [];
                        @endphp
                        @if(!empty($childrenTree))
                            @include('admin.planificacion.peis.peis.partials.nodo_pei_draggable', [
                                'nodos' => $childrenTree,
                                'level' => $childrenTree[0]['level'] ?? 'eje',
                                'parentId' => $master->id
                            ])
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa fa-folder-open fa-3x mb-2" style="opacity: .3;"></i>
                                <p class="mb-0">Este plan aún no posee elementos o ejes registrados.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white py-2 px-4 d-flex align-items-center justify-content-between">
                <small class="text-muted" id="lblEstadoReordenamiento">
                    <i class="fa fa-check-circle text-success mr-1"></i> Cambios en memoria. Haz clic en guardar para aplicar.
                </small>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary px-3 mr-2" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold px-4 shadow-sm" id="btnGuardarOrdenTreePei">
                        <i class="fa fa-save mr-1"></i> GUARDAR NUEVO ORDEN
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
