{{-- ══ Modal Seguimiento de Expedientes ════════════════════════════════════════════ --}}
<div class="modal fade" id="modalSeguimientos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width:90vw">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;overflow:hidden">

            {{-- Header --}}
            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#312e81,#4f46e5)">
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <div class="rounded-circle bg-white text-indigo d-inline-flex align-items-center justify-content-center" style="width:38px;height:38px;color:#4f46e5">
                        <i class="fa fa-folder-open fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white mb-0 font-weight-bold" style="font-size:1.1rem">
                            Control y Seguimiento de Expedientes Salientes
                        </h5>
                        <small class="text-white" style="opacity:.85;font-size:.78rem" id="seguimientosSubtitulo">
                            Monitoreo de trámites institucionales y fechas de alerta de respuesta
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap:10px">
                    <button type="button" class="btn btn-sm btn-light font-weight-bold text-indigo" id="btnCrearSeguimientoModal" style="color:#4f46e5; border-radius:20px;">
                        <i class="fa fa-plus-circle mr-1"></i>+ Nuevo Seguimiento
                    </button>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity:.8"><span>&times;</span></button>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="px-4 py-2.5 d-flex align-items-center flex-wrap" style="gap:.5rem;background:#f1f5f9;border-bottom:1px solid #e2e8f0">
                <span class="font-weight-bold text-uppercase mr-1" style="font-size:.7rem;color:#64748b;letter-spacing:.05em">Filtrar Trámites:</span>
                <button type="button" class="btn btn-sm btn-dark active" id="filtroSeguimientoTodos" style="font-size:.73rem;border-radius:20px;padding:3px 14px">
                    <i class="fa fa-list mr-1"></i>Todos
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" id="filtroSeguimientoVencidos" style="font-size:.73rem;border-radius:20px;padding:3px 14px">
                    <i class="fa fa-exclamation-triangle mr-1"></i>⚠️ Alerta Vencida (<span id="countSeguimientosVencidos">0</span>)
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" id="filtroSeguimientoPendientes" style="font-size:.73rem;border-radius:20px;padding:3px 14px">
                    <i class="fa fa-clock mr-1"></i>En Respuesta / Pendientes
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" id="filtroSeguimientoFinalizados" style="font-size:.73rem;border-radius:20px;padding:3px 14px">
                    <i class="fa fa-check-circle mr-1"></i>Finalizados / Con Respuesta
                </button>
                <span class="badge badge-pill badge-indigo ml-auto text-white" id="seguimientosCount" style="background:#4f46e5;font-size:.78rem;padding:6px 12px"></span>
            </div>

            {{-- Body con Tabla Interactiva --}}
            <div class="modal-body p-0" style="overflow:hidden">
                <div class="px-4 pt-3 pb-4">
                    <table id="tblSeguimientos" class="table table-hover table-sm w-100" style="font-size:.83rem">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th style="min-width:140px">N° Expediente</th>
                                <th style="min-width:180px">Asunto / Título</th>
                                <th style="min-width:160px">Dependencia Destino</th>
                                <th style="width:100px" class="text-center">Fecha Salida</th>
                                <th style="width:110px" class="text-center">Fecha Alerta</th>
                                <th style="width:120px" class="text-center">Estado Alerta</th>
                                <th style="width:130px">Responsable / Creador</th>
                                <th style="width:120px" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="seguimientosBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer py-2 px-4 d-flex align-items-center justify-content-between" style="background:#f8fafc;border-top:1px solid #e2e8f0">
                <small class="text-muted">
                    <i class="fa fa-info-circle text-info mr-1"></i>
                    Los expedientes salientes alertan automáticamente al responsable al vencer la fecha estimada de respuesta.
                </small>
                <button type="button" class="btn btn-sm btn-secondary px-4" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
