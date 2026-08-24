{{-- ══ Modal Reuniones ══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalReuniones" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width:85vw">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;overflow:hidden">

            {{-- ── VISTA 1: TABLA DE REUNIONES ── --}}
            <div id="reunionesViewTable">
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
                                    <th style="width:90px" class="text-center">
                                        <i class="fa fa-camera mr-1 text-info"></i>Fotos
                                    </th>
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

            {{-- ── VISTA 2: GALERÍA DE FOTOS (INNER VIEW) ── --}}
            <div id="reunionesViewGaleria" class="d-none">
                {{-- Header Galería --}}
                <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#0f2027,#203a43,#2c5364)">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-light font-weight-bold text-dark mr-3 btnVolverReunionesList" style="border-radius:20px;padding:4px 14px;font-size:.78rem">
                            <i class="fa fa-arrow-left mr-1"></i>Volver al Listado
                        </button>
                        <div>
                            <h5 class="modal-title text-white mb-0 font-weight-bold">
                                <i class="fa fa-camera mr-2"></i>Galería de Fotos de Reunión
                            </h5>
                            <small class="text-white" style="opacity:.8;font-size:.78rem" id="galeriaTituloReunion"></small>
                        </div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity:.8"><span>&times;</span></button>
                </div>

                {{-- Body Galería --}}
                <div class="modal-body px-4 py-3" style="background:#f8fafc;min-height:360px">
                    {{-- Contador / Indicador de límite --}}
                    <div class="d-flex align-items-center mb-3" style="gap:.75rem">
                        <span class="badge badge-pill badge-dark" id="galeriaContador" style="font-size:.8rem;padding:6px 14px">
                            <i class="fa fa-images mr-1"></i><span id="galeriaCount">0</span> / 3 fotos
                        </span>
                        <div class="flex-fill">
                            <div class="progress" style="height:8px;border-radius:4px">
                                <div id="galeriaProgressBar" class="progress-bar bg-info" style="width:0%;transition:width .4s ease"></div>
                            </div>
                        </div>
                        <small class="text-muted" style="font-size:.75rem"><i class="fa fa-leaf mr-1 text-success"></i>Optimización WebP automática</small>
                    </div>

                    {{-- Zona de Upload --}}
                    <div id="galeriaUploadZone" class="mb-4 p-4 text-center"
                         style="border:2px dashed #3b82f6;border-radius:12px;cursor:pointer;background:#fff;transition:border-color .2s,background .2s"
                         ondragover="event.preventDefault();this.style.borderColor='#1d4ed8';this.style.background='#eff6ff'"
                         ondragleave="this.style.borderColor='#3b82f6';this.style.background='#fff'"
                         ondrop="galeriaHandleDrop(event)"
                         onclick="document.getElementById('galeriaFileInput').click()">
                        <div id="galeriaUploadContent">
                            <i class="fa fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                            <p class="mb-1 text-dark font-weight-bold" style="font-size:.95rem">
                                Arrastrá una imagen aquí o <span class="text-primary text-underline">hacé clic para seleccionar</span>
                            </p>
                            <small class="text-muted" style="font-size:.78rem">JPG, PNG, WebP · Máx. 10 MB · Se convierte automáticamente a WebP de alta velocidad</small>
                        </div>
                        <div id="galeriaUploadProgress" class="d-none">
                            <i class="fa fa-spinner fa-spin fa-3x text-primary mb-2"></i>
                            <p class="mb-0 text-primary font-weight-bold" style="font-size:.95rem">Optimizando y subiendo imagen WebP...</p>
                        </div>
                    </div>
                    <input type="file" id="galeriaFileInput" accept="image/*" class="d-none">

                    {{-- Grid de Fotos --}}
                    <div id="galeriaGrid" class="d-flex flex-wrap" style="gap:16px;min-height:100px"></div>

                    {{-- Estado vacío --}}
                    <div id="galeriaVacia" class="text-center py-5 text-muted" style="display:none">
                        <i class="fa fa-camera-retro fa-4x mb-3" style="opacity:.2"></i>
                        <p class="mb-0 font-weight-bold" style="font-size:.95rem">No hay fotos registradas para esta reunión todavía.</p>
                        <small class="text-muted">Podés subir hasta 3 fotografías testimoniales de la reunión.</small>
                    </div>
                </div>

                {{-- Footer Galería --}}
                <div class="modal-footer py-2 px-4 d-flex justify-content-between" style="background:#f1f5f9;border-top:1px solid #e2e8f0">
                    <small class="text-muted" style="font-size:.75rem">
                        <i class="fa fa-shield-alt mr-1 text-info"></i>Las fotos se conservan de forma segura en formato WebP ultraligero.
                    </small>
                    <div>
                        <button type="button" class="btn btn-sm btn-primary px-3 mr-2 btnVolverReunionesList">
                            <i class="fa fa-arrow-left mr-1"></i>Volver al Listado
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══ Lightbox nativo ═════════════════════════════════════════════════════ --}}
<div id="galeriaLightbox" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.92);z-index:99999;cursor:pointer;align-items:center;justify-content:center"
     onclick="galeriaCloseLightbox()">
    <button onclick="galeriaCloseLightbox();event.stopPropagation()" style="position:absolute;top:16px;right:20px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:100000">&times;</button>
    <img id="galeriaLightboxImg" src="" alt="Foto reunión"
         style="max-width:92vw;max-height:88vh;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,.6);object-fit:contain">
    <div id="galeriaLightboxCaption" style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);color:#fff;font-size:.85rem;opacity:.9;text-align:center;max-width:80%"></div>
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
