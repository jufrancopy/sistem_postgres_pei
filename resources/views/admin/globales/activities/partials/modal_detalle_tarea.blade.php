<div class="modal fade" id="modalDetalleTarea" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;border:none">

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

                        <div class="mb-4">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px">
                                <i class="fa fa-align-left mr-1"></i>Descripción
                            </div>
                            <div id="detalle-desc" style="font-size:.88rem;color:#334155;line-height:1.6;white-space:pre-wrap">
                                <span class="text-muted fst-italic">Sin descripción</span>
                            </div>
                        </div>

                        <div id="detalle-cierre-wrap" class="mb-4" style="display:none">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px">
                                <i class="fa fa-check-circle mr-1 text-success"></i>Nota de cierre
                            </div>
                            <div style="background:#f0fff4;border-left:3px solid #10b981;border-radius:0 8px 8px 0;padding:10px 14px">
                                <div id="detalle-cierre-nota" style="font-size:.85rem;color:#065f46;font-style:italic"></div>
                                <div id="detalle-cierre-meta" style="font-size:.72rem;color:#94a3b8;margin-top:4px"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px">
                                <i class="fa fa-paperclip mr-1"></i>Evidencias
                            </div>
                            <div id="detalle-evidencias"><span class="text-muted small">Sin evidencias</span></div>
                        </div>

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

                        {{-- Reasignar --}}
                        <div class="mb-3" id="detalle-reasignar-wrap" style="display:none">
                            <div style="font-size:.72rem;color:#94a3b8;margin-bottom:4px"><i class="fa fa-exchange-alt mr-1"></i>Reasignar a</div>
                            <select id="detalle-reasignar-select" class="form-control form-control-sm" style="width:100%"></select>
                            <button class="btn btn-sm btn-block mt-1" id="detalle-reasignar-btn"
                                    style="background:#ede9fe;color:#5b21b6;border:none;border-radius:8px;font-size:.78rem;font-weight:600">
                                <i class="fa fa-check mr-1"></i>Confirmar reasignación
                            </button>
                        </div>

                        {{-- Fecha límite --}}
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
var _getUsersUrl   = "{{ route('globales.get-users') }}";
var _esGestor      = {{ auth()->user()->hasAnyRole(['Administrador', 'Gestor de Actividades']) ? 'true' : 'false' }};
var _userId        = {{ auth()->id() }};

var _statusMap = {
    0: { label: 'Pendiente',   bg: '#f59e0b' },
    1: { label: 'En progreso', bg: '#3b82f6' },
    3: { label: 'En revisión', bg: '#8b5cf6' },
    2: { label: 'Finalizado',  bg: '#10b981' },
    4: { label: 'Priorizado',  bg: '#f97316' },
};

function abrirDetalleTask(taskId) {
    _detalleTaskId = taskId;
    $('#modalDetalleTarea').modal('show');
}

$('#modalDetalleTarea').on('show.bs.modal', function() {
    if (_detalleTaskId) renderDetalleBasico(_detalleTaskId);
});

function renderDetalleBasico(taskId) {
    // Limpiar
    $('#detalle-title').text('Cargando...');
    $('#detalle-desc').html('<span class="text-muted small"><i class="fa fa-spinner fa-spin mr-1"></i>Cargando...</span>');
    $('#detalle-comentarios-lista').html('<div class="text-center text-muted small py-2"><i class="fa fa-spinner fa-spin"></i></div>');
    $('#detalle-acciones').html('');
    $('#detalle-cierre-wrap').hide();
    $('#detalle-reasignar-wrap').hide();

    $.get(_statusBase + '/' + taskId + '/detalle', function(response) {
        if (!response.ok || !response.data) {
            $('#detalle-title').text('Error cargando tarea');
            $('#detalle-desc').html('<span class="text-danger small">No se pudo obtener la información de la tarea.</span>');
            return;
        }

        var data = response.data;
        var st = _statusMap[data.status] || _statusMap[0];
        var cardColor = data.color || '#2563eb';

        $('#detalle-header').css('background', 'linear-gradient(135deg,' + cardColor + ',#1e3a5f)');
        if (data.etiqueta) {
            $('#detalle-etiqueta-wrap').html('<span style="font-size:.7rem;font-weight:700;padding:2px 10px;border-radius:20px;background:rgba(255,255,255,.2);color:#fff">' + $('<div>').text(data.etiqueta).html() + '</span>');
        } else {
            $('#detalle-etiqueta-wrap').html('');
        }

        $('#detalle-title').text(data.title || 'Sin título');
        $('#detalle-status-wrap').html('<span style="font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(255,255,255,.2);color:#fff">' + st.label + '</span>');
        $('#detalle-estado-badge').html('<span style="font-size:.78rem;font-weight:700;padding:4px 12px;border-radius:20px;background:' + st.bg + '20;color:' + st.bg + ';border:1px solid ' + st.bg + '40">' + st.label + '</span>');

        if (data.details) {
            $('#detalle-desc').text(data.details);
        } else {
            $('#detalle-desc').html('<span class="text-muted fst-italic small">Sin descripción</span>');
        }

        $('#detalle-responsable').text(data.responsable || 'Sin asignar');
        $('#detalle-vencimiento').text(data.fecha_vencimiento || 'Sin fecha');

        if (_esGestor || data.assigned_to === _userId) {
            $('#detalle-reasignar-wrap').show();
            iniciarSelectReasignar(data.responsable || 'Sin asignar');
        }

        if (data.completion_note) {
            $('#detalle-cierre-wrap').show();
            $('#detalle-cierre-nota').text(data.completion_note);
            $('#detalle-cierre-meta').text(data.completed_by ? 'Completada por ' + data.completed_by + ' · ' + (data.completed_at || '') : 'Completada');
        }

        if (data.evidencias && data.evidencias.length) {
            var evidenciasHtml = '<div class="list-group list-group-flush">';
            data.evidencias.forEach(function(evidence) {
                evidenciasHtml += '<a href="' + evidence.url + '" target="_blank" class="d-flex justify-content-between align-items-start list-group-item list-group-item-action p-2" style="border-radius:10px;margin-bottom:6px">'
                    + '<div><div style="font-weight:600;color:#0f172a">' + $('<div>').text(evidence.label).html() + '</div>'
                    + '<small class="text-muted">' + $('<div>').text(evidence.type).html() + ' · ' + $('<div>').text(evidence.autor || 'Usuario').html() + '</small></div>'
                    + '<i class="fa fa-external-link-alt" style="font-size:.72rem;color:#64748b"></i>'
                    + '</a>';
            });
            evidenciasHtml += '</div>';
            $('#detalle-evidencias').html(evidenciasHtml);
        } else {
            $('#detalle-evidencias').html('<span class="text-muted small">Sin evidencias</span>');
        }

        renderComentariosDetalle(data.comentarios || []);

        var accionesHtml = '';
        if (_esGestor) {
            accionesHtml += '<button class="btn btn-sm btn-block editTaskBtn-detalle" data-id="' + taskId + '" style="background:#ede9fe;color:#5b21b6;border:none;border-radius:8px;font-size:.8rem;font-weight:600;padding:8px"><i class="fa fa-pen mr-2"></i>Editar tarea</button>';
            accionesHtml += '<button class="btn btn-sm btn-block btn-add-evidence-detalle" data-id="' + taskId + '" style="background:#f0fdf4;color:#065f46;border:none;border-radius:8px;font-size:.8rem;font-weight:600;padding:8px"><i class="fa fa-paperclip mr-2"></i>Agregar evidencia</button>';
            accionesHtml += '<button class="btn btn-sm btn-block btn-delete-task-detalle" data-id="' + taskId + '" style="background:#fee2e2;color:#991b1b;border:none;border-radius:8px;font-size:.8rem;font-weight:600;padding:8px"><i class="fa fa-trash mr-2"></i>Eliminar tarea</button>';
        }
        $('#detalle-acciones').html(accionesHtml);
    }).fail(function() {
        $('#detalle-title').text('Error cargando tarea');
        $('#detalle-desc').html('<span class="text-danger small">No se pudo obtener la información de la tarea.</span>');
        $('#detalle-evidencias').html('<span class="text-muted small">Sin evidencias</span>');
        $('#detalle-comentarios-lista').html('<div class="text-center text-danger small py-2">Error al cargar comentarios</div>');
    });
}

// ── Reasignación ──────────────────────────────────────────────────────────────
function iniciarSelectReasignar(responsableActual) {
    var $sel = $('#detalle-reasignar-select');
    $sel.empty();

    if ($.fn.select2) {
        if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
        $sel.select2({
            placeholder: 'Buscar usuario...',
            allowClear: true,
            dropdownParent: $('#modalDetalleTarea'),
            ajax: {
                url: _getUsersUrl, dataType: 'json', delay: 250,
                processResults: function(data) {
                    return { results: $.map(data, function(u) { return { id: u.id, text: u.name }; }) };
                }
            }
        });
    }
}

$('#detalle-reasignar-btn').on('click', function() {
    var nuevoId = $('#detalle-reasignar-select').val();
    var nuevoNombre = $('#detalle-reasignar-select option:selected').text();
    if (!nuevoId || !_detalleTaskId) return;

    var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Reasignando...');

    $.ajax({
        url: '/admin/globales/activities/' + window.activityId + '/tareas',
        method: 'POST',
        data: {
            _token:      $('meta[name="csrf-token"]').attr('content'),
            _method:     'PUT',
            task_id:     _detalleTaskId,
            assigned_to: nuevoId,
        },
        success: function(r) {
            if (r.success) {
                $('#detalle-responsable').text(nuevoNombre);
                $('#detalle-reasignar-wrap').hide();
                toastr.success('Tarea reasignada a ' + nuevoNombre);
                // Actualizar el card en el tablero
                var $card = $('[data-id="' + _detalleTaskId + '"]').first();
                $card.find('[style*="text-overflow"]').text(nuevoNombre);
                var initials = nuevoNombre.substring(0,2).toUpperCase();
                $card.find('.task-avatar').text(initials);
            }
        },
        error: function() { toastr.error('Error al reasignar'); },
        complete: function() { $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i>Confirmar reasignación'); }
    });
});

// ── Comentarios ───────────────────────────────────────────────────────────────
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

$(document).on('click', '.detalle-del-comment', function() {
    var cId = $(this).data('id');
    var $row = $(this).closest('[data-comment-id]');
    $.ajax({
        url: _comentBase + '/comentarios/' + cId, type: 'DELETE',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function() { $row.fadeOut(200, function() { $(this).remove(); }); }
    });
});

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
</script>
