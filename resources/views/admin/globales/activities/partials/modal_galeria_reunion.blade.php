{{-- ══ Modal Galería de Fotos — Reunión ════════════════════════════════════ --}}
<div class="modal fade" id="modalGaleriaReunion" tabindex="-1" aria-hidden="true" style="z-index:1055">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width:800px">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden">

            {{-- Header --}}
            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#0f2027,#203a43,#2c5364)">
                <div>
                    <h5 class="modal-title text-white mb-0 font-weight-bold">
                        <i class="fa fa-camera mr-2"></i>Galería de Fotos
                    </h5>
                    <small class="text-white" style="opacity:.7;font-size:.75rem" id="galeriaTituloReunion"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:.8"><span>&times;</span></button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="background:#f8fafc;min-height:320px">

                {{-- Contador / Indicador de límite --}}
                <div class="d-flex align-items-center mb-3" style="gap:.5rem">
                    <span class="badge badge-pill badge-dark" id="galeriaContador" style="font-size:.78rem;padding:5px 12px">
                        <i class="fa fa-images mr-1"></i><span id="galeriaCount">0</span> / 3 fotos
                    </span>
                    <div class="flex-fill">
                        <div class="progress" style="height:6px;border-radius:4px">
                            <div id="galeriaProgressBar" class="progress-bar bg-info" style="width:0%;transition:width .4s ease"></div>
                        </div>
                    </div>
                    <small class="text-muted" style="font-size:.7rem"><i class="fa fa-leaf mr-1 text-success"></i>Imagen convertida a WebP</small>
                </div>

                {{-- Zona de Upload --}}
                <div id="galeriaUploadZone" class="mb-3 p-3 text-center"
                     style="border:2px dashed #94a3b8;border-radius:12px;cursor:pointer;background:#fff;transition:border-color .2s"
                     ondragover="event.preventDefault();this.style.borderColor='#2563eb'"
                     ondragleave="this.style.borderColor='#94a3b8'"
                     ondrop="galeriaHandleDrop(event)">
                    <div id="galeriaUploadContent">
                        <i class="fa fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                        <p class="mb-1 text-muted" style="font-size:.85rem">
                            Arrastrá una imagen aquí o
                            <label for="galeriaFileInput" class="text-primary font-weight-bold" style="cursor:pointer;margin:0">hacé clic para seleccionar</label>
                        </p>
                        <small class="text-muted" style="font-size:.72rem">JPG, PNG, WebP · Máx. 10 MB · Se convierte automáticamente a WebP</small>
                    </div>
                    <div id="galeriaUploadProgress" class="d-none">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                        <p class="mb-0 text-primary font-weight-bold" style="font-size:.85rem">Optimizando y subiendo imagen...</p>
                    </div>
                    <input type="file" id="galeriaFileInput" accept="image/*" class="d-none">
                </div>

                {{-- Grid de Fotos --}}
                <div id="galeriaGrid" class="d-flex flex-wrap" style="gap:12px;min-height:80px">
                    {{-- Fotos renderizadas por JS --}}
                </div>

                {{-- Estado vacío --}}
                <div id="galeriaVacia" class="text-center py-4 text-muted" style="display:none">
                    <i class="fa fa-camera-retro fa-3x mb-3" style="opacity:.25"></i>
                    <p class="mb-0" style="font-size:.88rem">No hay fotos registradas para esta reunión.</p>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer py-2 px-4" style="background:#f1f5f9;border-top:1px solid #e2e8f0">
                <small class="text-muted mr-auto" style="font-size:.72rem">
                    <i class="fa fa-info-circle mr-1"></i>Las imágenes se comprimen y convierten a WebP automáticamente para optimizar el espacio.
                </small>
                <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

{{-- ══ Lightbox nativo ═════════════════════════════════════════════════════ --}}
<div id="galeriaLightbox" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.92);z-index:9999;cursor:pointer;align-items:center;justify-content:center"
     onclick="galeriaCloseLightbox()">
    <button onclick="galeriaCloseLightbox();event.stopPropagation()" style="position:absolute;top:16px;right:20px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:10000">&times;</button>
    <img id="galeriaLightboxImg" src="" alt="Foto reunión"
         style="max-width:92vw;max-height:88vh;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,.6);object-fit:contain">
    <div id="galeriaLightboxCaption" style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);color:#fff;font-size:.8rem;opacity:.8;text-align:center;max-width:80%"></div>
</div>

<script>
var _galeriaTaskId = null;
var _galeriaCsrf   = "{{ csrf_token() }}";
var _galeriaBase   = "{{ url('admin/globales/activities/reuniones') }}";
var _galeriaDelBase = "{{ url('admin/globales/activities/reuniones/fotos') }}";
var _galeriaCanUpload = false;

// ── Abrir Galería ───────────────────────────────────────────────────────────
function abrirGaleriaReunion(taskId, titulo, canUpload) {
    _galeriaTaskId    = taskId;
    _galeriaCanUpload = canUpload;
    document.getElementById('galeriaTituloReunion').textContent = titulo || 'Fotos de la Reunión';
    document.getElementById('galeriaUploadZone').style.display = canUpload ? '' : 'none';
    galeriaCargarFotos();
    $('#modalGaleriaReunion').modal('show');
}

// ── Cargar fotos vía AJAX ───────────────────────────────────────────────────
function galeriaCargarFotos() {
    var url = _galeriaBase + '/' + _galeriaTaskId + '/fotos';
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => galeriaRenderizar(data.photos, data.count))
        .catch(() => { if (window.toastr) toastr.error('No se pudieron cargar las fotos.'); });
}

// ── Renderizar grid ─────────────────────────────────────────────────────────
function galeriaRenderizar(photos, count) {
    var grid   = document.getElementById('galeriaGrid');
    var vacia  = document.getElementById('galeriaVacia');
    var ccount = document.getElementById('galeriaCount');
    var pbar   = document.getElementById('galeriaProgressBar');
    var zone   = document.getElementById('galeriaUploadZone');

    count  = count || photos.length;
    ccount.textContent = count;
    pbar.style.width   = Math.round((count / 3) * 100) + '%';
    pbar.className     = 'progress-bar ' + (count >= 3 ? 'bg-danger' : 'bg-info');

    // Mostrar/ocultar zona de upload según límite
    if (_galeriaCanUpload) {
        zone.style.display = (count >= 3) ? 'none' : '';
    }

    grid.innerHTML = '';
    if (!photos || photos.length === 0) {
        vacia.style.display = '';
        return;
    }
    vacia.style.display = 'none';

    photos.forEach(function(p) {
        var card = document.createElement('div');
        card.style.cssText = 'position:relative;width:220px;height:150px;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.15);flex-shrink:0;background:#e2e8f0';
        card.innerHTML = '\
            <img src="' + p.thumb_url + '" alt="' + p.original_name + '"\
                 style="width:100%;height:100%;object-fit:cover;cursor:zoom-in;transition:transform .25s"\
                 onclick="galeriaOpenLightbox(\'' + p.url + '\', \'' + (p.original_name || '') + ' — Subida por ' + (p.uploader || '') + ' ' + (p.created_at || '') + '\')" \
                 onmouseover="this.style.transform=\'scale(1.04)\'" onmouseout="this.style.transform=\'\'">\
            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.65));padding:6px 8px">\
                <p class="mb-0 text-white" style="font-size:.68rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">\
                    <i class="fa fa-image mr-1" style="opacity:.7"></i>' + (p.original_name || 'foto') + '</p>\
                <p class="mb-0 text-white" style="font-size:.63rem;opacity:.7">' + (p.size_human || '') + ' WebP · ' + (p.created_at || '') + '</p>\
            </div>\
            ' + (_galeriaCanUpload ? '\
            <button onclick="galeriaEliminarFoto(' + p.id + ',this)" title="Eliminar foto"\
                    style="position:absolute;top:6px;right:6px;background:rgba(220,38,38,.85);border:none;color:#fff;border-radius:50%;width:26px;height:26px;cursor:pointer;font-size:.75rem;display:flex;align-items:center;justify-content:center">\
                <i class="fa fa-trash"></i></button>' : '') + '\
        ';
        grid.appendChild(card);
    });
}

// ── Upload via click / drag-drop ────────────────────────────────────────────
document.getElementById('galeriaFileInput').addEventListener('change', function() {
    if (this.files[0]) galeriaSubirFoto(this.files[0]);
    this.value = '';
});

document.getElementById('galeriaUploadZone').addEventListener('click', function(e) {
    if (e.target.tagName !== 'LABEL') document.getElementById('galeriaFileInput').click();
});

function galeriaHandleDrop(e) {
    e.preventDefault();
    document.getElementById('galeriaUploadZone').style.borderColor = '#94a3b8';
    var file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) galeriaSubirFoto(file);
    else if (window.toastr) toastr.warning('Solo se aceptan imágenes.');
}

function galeriaSubirFoto(file) {
    var content  = document.getElementById('galeriaUploadContent');
    var progress = document.getElementById('galeriaUploadProgress');
    content.classList.add('d-none');
    progress.classList.remove('d-none');

    var formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', _galeriaCsrf);

    fetch(_galeriaBase + '/' + _galeriaTaskId + '/fotos', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        content.classList.remove('d-none');
        progress.classList.add('d-none');
        if (data.error) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'No se pudo subir la foto', text: data.error });
            } else if (window.toastr) toastr.error(data.error);
        } else {
            if (window.toastr) toastr.success(data.success || 'Foto subida y optimizada.');
            galeriaCargarFotos();
        }
    })
    .catch(() => {
        content.classList.remove('d-none');
        progress.classList.add('d-none');
        if (window.toastr) toastr.error('Error al subir la imagen.');
    });
}

// ── Eliminar foto ────────────────────────────────────────────────────────────
function galeriaEliminarFoto(photoId, btn) {
    if (typeof Swal === 'undefined') {
        if (!confirm('¿Eliminar esta foto?')) return;
        _galeriaDoDelete(photoId);
        return;
    }
    Swal.fire({
        title: '¿Eliminar esta foto?',
        text: 'La imagen se borrará permanentemente del servidor.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then(function(res) {
        if (res.isConfirmed) _galeriaDoDelete(photoId);
    });
}

function _galeriaDoDelete(photoId) {
    fetch(_galeriaDelBase + '/' + photoId, {
        method: 'DELETE',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': _galeriaCsrf }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            if (window.toastr) toastr.error(data.error);
        } else {
            if (window.toastr) toastr.success(data.success || 'Foto eliminada.');
            galeriaCargarFotos();
        }
    })
    .catch(() => { if (window.toastr) toastr.error('Error al eliminar la foto.'); });
}

// ── Lightbox ─────────────────────────────────────────────────────────────────
function galeriaOpenLightbox(url, caption) {
    document.getElementById('galeriaLightboxImg').src = url;
    document.getElementById('galeriaLightboxCaption').textContent = caption || '';
    var lb = document.getElementById('galeriaLightbox');
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function galeriaCloseLightbox() {
    document.getElementById('galeriaLightbox').style.display = 'none';
    document.getElementById('galeriaLightboxImg').src = '';
    document.body.style.overflow = '';
}

// Cerrar lightbox con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') galeriaCloseLightbox();
});
</script>
