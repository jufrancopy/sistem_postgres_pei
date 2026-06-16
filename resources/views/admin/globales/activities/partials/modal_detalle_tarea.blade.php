<div class="modal fade" id="modalDetalleTarea" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none">

            {{-- Header dinámico --}}
            <div id="detalle-header" style="padding:20px 24px 16px;position:relative">
                <div class="d-flex align-items-start justify-content-between">
                    <div style="flex:1;min-width:0">
                        <div id="detalle-etiqueta-wrap" class="mb-2"></div>
                        <h5 id="detalle-title" style="font-size:1.1rem;font-weight:800;color:#fff;margin:0;line-height:1.3"></h5>
                        <div id="detalle-status-wrap" class="mt-2"></div>
                    </div>
                    <button type="button" class="close text-white ml-3" data-dismiss="modal" style="opacity:.8;font-size:1.3rem">&times;</button>
                </div>
            </div>

            <div class="modal-body" style="padding:0">

                <div class="row no-gutters">

                    {{-- Columna principal --}}
                    <div class="col-md-8" style="padding:20px 24px;border-right:1px solid #f1f5f9">

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px">
                                <i class="fa fa-align-left mr-1"></i>Descripción
                            </div>
                            <div id="detalle-desc" style="font-size:.88rem;color:#334155;line-height:1.6;white-space:pre-wrap">
                                <span class="text-muted fst-italic">Sin descripción</span>
                            </div>
                        </div>

                        {{-- Nota de cierre --}}
                        <div id="detalle-cierre-wrap" class="mb-4" style="display:none">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px">
                                <i class="fa fa-check-circle mr-1 text-success"></i>Nota de cierre
                            </div>
                            <div style="background:#f0fff4;border-left:3px solid #10b981;border-radius:0 8px 8px 0;padding:10px 14px">
                                <div id="detalle-cierre-nota" style="font-size:.85rem;color:#065f46;font-style:italic"></div>
                                <div id="detalle-cierre-meta" style="font-size:.72rem;color:#94a3b8;margin-top:4px"></div>
                            </div>
                        </div>

                        {{-- Evidencias --}}
                        <div class="mb-4">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px">
                                <i class="fa fa-paperclip mr-1"></i>Evidencias
                            </div>
                            <div id="detalle-evidencias">
                                <span class="text-muted small">Sin evidencias</span>
                            </div>
                        </div>

                        {{-- Comentarios --}}
                        <div>
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px">
                                <i class="fa fa-comments mr-1"></i>Comentarios
                            </div>
                            <div id="detalle-comentarios-lista" style="max-height:220px;overflow-y:auto;margin-bottom:10px">
                                <div class="text-muted small text-center py-2" id="detalle-no-comments">Sin comentarios aún</div>
                            </div>
                            <div class="d-flex" style="gap:8px">
                                <input type="text" id="detalle-comment-input" class="form-control form-control-sm"
                                       placeholder="Escribí un comentario... (Enter para enviar)"
                                       style="border-radius:20px;font-size:.82rem">
                                <button class="btn btn-primary btn-sm" id="detalle-comment-send"
                                        style="border-radius:50%;width:32px;height:32px;padding:0;flex-shrink:0">
                                    <i class="fa fa-paper-plane" style="font-size:.7rem"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Columna lateral --}}
                    <div class="col-md-4" style="padding:20px 20px;background:#fafbfc">

                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:14px">
                            Detalles
                        </div>

                        {{-- Responsable --}}
                        <div class="mb-3">
                            <div style="font-size:.72rem;color:#94a3b8;margin-bottom:4px"><i class="fa fa-user mr-1"></i>Responsable</div>
                            <div id="detalle-responsable" style="font-size:.85rem;font-weight:600;color:#1e293b">—</div>
                        </div>

                        {{-- Vencimiento --}}
                        <div class="mb-3">
                            <div style="font-size:.72rem;color:#94a3b8;margin-bottom:4px"><i class="fa fa-clock mr-1"></i>Fecha límite</div>
                            <div id="detalle-vencimiento" style="font-size:.85rem;font-weight:600">—</div>
                        </div>

                        {{-- Estado --}}
                        <div class="mb-4">
                            <div style="font-size:.72rem;color:#94a3b8;margin-bottom:4px"><i class="fa fa-flag mr-1"></i>Estado</div>
                            <div id="detalle-estado-badge"></div>
                        </div>

                        {{-- Acciones --}}
                        <div id="detalle-acciones" style="display:flex;flex-direction:column;gap:7px"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
var _detalleTaskId = null;
var _statusBase    = "{{ url('admin/globales/activities/tareas') }}";
var _comentBase    = "{{ url('admin/globales/activities/tareas') }}";
var _esGestor      = {{ auth()->user()->hasAnyRole(['Administrador', 'Gestor de Actividades']) ? 'true' : 'false' }};

var _statusMap = {
    0: { label: 'Pendiente',   bg: '#f59e0b' },
    1: { label: 'En progreso', bg: '#3b82f6' },
    3: { label: 'En revisión', bg: '#8b5cf6' },
    2: { label: 'Finalizado',  bg: '#10b981' },
    4: { label: 'Priorizado',  bg: '#f97316' },
};

function abrirDetalleTask(taskId) {
    _detalleTaskId = taskId;
    // Limpiar
    $('#detalle-title').text('Cargando...');
    $('#detalle-desc').html('<span class="text-muted small"><i class="fa fa-spinner fa-spin mr-1"></i>Cargando...</span>');
    $('#detalle-evidencias').html('<span class="text-muted small">...</span>');
    $('#detalle-comentarios-lista').html('<div class="text-center text-muted small py-2"><i class="fa fa-spinner fa-spin"></i></div>');
    $('#detalle-acciones').html('');
    $('#modalDetalleTarea').modal('show');

    // Cargar datos
    $.get(_statusBase + '/' + taskId + '/comentarios', function(r) {
        renderDetalleTask(r);
    }).fail(function() {
        // Si no hay ruta de detalle completo, usar la info del card
        renderDetalleBasico(taskId);
    });
}

function renderDetalleTask(r) {
    // Título y etiqueta
    var task = r; // usamos los comentarios para obtener task_title
    $('#detalle-title').text(r.task_title || 'Tarea');

    // Comentarios
    renderComentariosDetalle(r.comentarios || []);

    // Limpiar loading de desc/evidencias
    $('#detalle-desc').html('<span class="text-muted fst-italic small">Haz clic en Editar para ver más detalles</span>');
    $('#detalle-evidencias').html('<span class="text-muted small">Ver en el tablero</span>');
}

function renderDetalleBasico(taskId) {
    // Tomar datos del card DOM
    var $card = $('[data-id="' + taskId + '"]').first();
    var title   = $card.find('.task-title').clone().find('.fa-check-circle').remove().end().text().trim();
    var desc    = $card.find('.task-desc').text().trim();
    var etiq    = $card.find('.task-etiqueta').text().trim();
    var etiqBg  = $card.find('.task-etiqueta').css('background-color');
    var nombre  = $card.find('.task-avatar').next('small').text().trim();
    var vencEl  = $card.find('[style*="fa-clock"]').closest('span').text().trim();
    var status  = parseInt($card.closest('[data-status]').data('status')) || 0;
    var cardColor = $card.css('border-left-color');

    // Header
    var hdrColor = 'linear-gradient(135deg, #1e3a5f, #2563eb)';
    if (etiqBg && etiqBg !== 'rgba(0, 0, 0, 0)') hdrColor = 'linear-gradient(135deg,' + etiqBg + ', #1e3a5f)';
    $('#detalle-header').css('background', hdrColor);

    // Etiqueta
    if (etiq) {
        $('#detalle-etiqueta-wrap').html('<span style="font-size:.7rem;font-weight:700;padding:2px 10px;border-radius:20px;background:rgba(255,255,255,.2);color:#fff">' + etiq + '</span>');
    }

    $('#detalle-title').text(title || 'Sin título');

    // Status
    var st = _statusMap[status] || _statusMap[0];
    $('#detalle-status-wrap').html('<span style="font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(255,255,255,.2);color:#fff">' + st.label + '</span>');
    $('#detalle-estado-badge').html('<span style="font-size:.78rem;font-weight:700;padding:4px 12px;border-radius:20px;background:' + st.bg + '20;color:' + st.bg + ';border:1px solid ' + st.bg + '40">' + st.label + '</span>');

    // Descripción
    $('#detalle-desc').text(desc || '').closest('div').find('.fst-italic').remove();
    if (!desc) $('#detalle-desc').html('<span class="text-muted fst-italic small">Sin descripción</span>');

    // Responsable
    $('#detalle-responsable').text(nombre || 'Sin asignar');

    // Comentarios
    $.get(_comentBase + '/' + taskId + '/comentarios', function(r) {
        renderComentariosDetalle(r.comentarios || []);
    });

    // Acciones
    var accionesHtml = '';
    if (_esGestor) {
        accionesHtml += '<button class="btn btn-sm btn-block editTaskBtn-detalle" data-id="' + taskId + '" style="background:#ede9fe;color:#5b21b6;border:none;border-radius:8px;font-size:.8rem;font-weight:600;padding:8px"><i class="fa fa-pen mr-2"></i>Editar tarea</button>';
        accionesHtml += '<button class="btn btn-sm btn-block btn-add-evidence-detalle" data-id="' + taskId + '" style="background:#f0fdf4;color:#065f46;border:none;border-radius:8px;font-size:.8rem;font-weight:600;padding:8px"><i class="fa fa-paperclip mr-2"></i>Agregar evidencia</button>';
        accionesHtml += '<button class="btn btn-sm btn-block btn-delete-task-detalle" data-id="' + taskId + '" style="background:#fee2e2;color:#991b1b;border:none;border-radius:8px;font-size:.8rem;font-weight:600;padding:8px"><i class="fa fa-trash mr-2"></i>Eliminar tarea</button>';
    }
    $('#detalle-acciones').html(accionesHtml);
}

function renderComentariosDetalle(comentarios) {
    if (!comentarios.length) {
        $('#detalle-comentarios-lista').html('<div class="text-muted small text-center py-2" id="detalle-no-comments">Sin comentarios aún</div>');
        return;
    }
    var html = '';
    comentarios.forEach(function(c) {
        html += '<div class="d-flex mb-3" style="gap:8px" data-comment-id="' + c.id + '">'
            + '<div style="width:28px;height:28px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;flex-shrink:0">' + c.initials + '</div>'
            + '<div style="flex:1;min-width:0">'
            + '<div style="background:#f8fafc;border-radius:0 10px 10px 10px;padding:8px 12px;font-size:.82rem;color:#1e293b">' + $('<div>').text(c.comentario).html() + '</div>'
            + '<div style="font-size:.68rem;color:#94a3b8;margin-top:3px;display:flex;justify-content:space-between;align-items:center">'
            + '<span><strong>' + c.autor + '</strong> · ' + c.fecha + '</span>'
            + (c.es_mio ? '<a href="javascript:void(0)" class="text-danger detalle-del-comment" data-id="' + c.id + '" style="font-size:.7rem">× eliminar</a>' : '')
            + '</div></div></div>';
    });
    $('#detalle-comentarios-lista').html(html);
    $('#detalle-comentarios-lista').scrollTop($('#detalle-comentarios-lista')[0].scrollHeight);
}

// Enviar comentario desde modal detalle
$('#detalle-comment-send, #detalle-comment-input').on('click keydown', function(e) {
    if (e.type === 'keydown' && e.key !== 'Enter') return;
    if (e.type === 'click' && $(this).attr('id') !== 'detalle-comment-send') return;
    var texto = $('#detalle-comment-input').val().trim();
    if (!texto || !_detalleTaskId) return;
    $.ajax({
        url: _comentBase + '/' + _detalleTaskId + '/comentarios', type: 'POST',
        data: { _token: $('meta[name="csrf-token"]').attr('content'), comentario: texto },
        success: function(r) {
            $('#detalle-comment-input').val('');
            $('#detalle-no-comments').remove();
            var c = r.item;
            $('#detalle-comentarios-lista').append(
                '<div class="d-flex mb-3" style="gap:8px" data-comment-id="' + c.id + '">'
                + '<div style="width:28px;height:28px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;flex-shrink:0">' + c.initials + '</div>'
                + '<div style="flex:1;min-width:0">'
                + '<div style="background:#dbeafe;border-radius:0 10px 10px 10px;padding:8px 12px;font-size:.82rem;color:#1e293b">' + $('<div>').text(c.comentario).html() + '</div>'
                + '<div style="font-size:.68rem;color:#94a3b8;margin-top:3px;display:flex;justify-content:space-between">'
                + '<span><strong>' + c.autor + '</strong> · ' + c.fecha + '</span>'
                + '<a href="javascript:void(0)" class="text-danger detalle-del-comment" data-id="' + c.id + '" style="font-size:.7rem">× eliminar</a>'
                + '</div></div></div>'
            );
            $('#detalle-comentarios-lista').scrollTop($('#detalle-comentarios-lista')[0].scrollHeight);
        },
        error: function() { toastr.error('Error al guardar comentario'); }
    });
});

// Eliminar comentario desde modal detalle
$(document).on('click', '.detalle-del-comment', function() {
    var cId = $(this).data('id');
    var $row = $(this).closest('[data-comment-id]');
    $.ajax({
        url: _comentBase + '/comentarios/' + cId, type: 'DELETE',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function() { $row.fadeOut(200, function() { $(this).remove(); }); }
    });
});

// Acciones desde modal detalle
$(document).on('click', '.editTaskBtn-detalle', function() {
    var id = $(this).data('id');
    $('#modalDetalleTarea').modal('hide');
    setTimeout(function() { $('.editTaskBtn[data-id="' + id + '"]').trigger('click'); }, 300);
});
$(document).on('click', '.btn-add-evidence-detalle', function() {
    var id = $(this).data('id');
    $('#evidence_task_id').val(id);
    $('#ev_label').val(''); $('#ev_url').val(''); $('#ev_file').val('');
    $('#modalDetalleTarea').modal('hide');
    setTimeout(function() { $('#evidenceModal').modal('show'); }, 300);
});
$(document).on('click', '.btn-delete-task-detalle', function() {
    var id = $(this).data('id');
    $('#modalDetalleTarea').modal('hide');
    setTimeout(function() {
        Swal.fire({ title:'¿Eliminar tarea?', icon:'warning', showCancelButton:true,
            confirmButtonColor:'#d33', confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar'
        }).then(function(r) {
            if (r.value) $.ajax({ url: _statusBase + '/' + id, type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function() { location.reload(); }
            });
        });
    }, 300);
});

// Al abrir el modal cargar los datos del card
$('#modalDetalleTarea').on('show.bs.modal', function() {
    if (_detalleTaskId) renderDetalleBasico(_detalleTaskId);
});
</script>
