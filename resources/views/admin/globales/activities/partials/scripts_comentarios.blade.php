{{-- JS compartido para comentarios de tareas (show + mis_tareas). Incluir dentro de un <script>. --}}
var comentariosBase = "{{ $comentariosBase ?? url('admin/globales/activities/tareas') }}";
var comentariosPollTimer = null;

$('body').on('click', '.btn-add-comment', function() {
    var taskId = $(this).data('id');
    var titulo = $(this).data('titulo');
    $('#comentariosTaskId').val(taskId);
    $('#comentariosTareaTitle').text(titulo);
    $('#nuevoComentario').val('');
    cargarComentarios(taskId);
    $('#modalComentarios').modal('show');
});

function cargarComentarios(taskId) {
    $('#comentariosLista').html('<div class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary"></div></div>');
    $.get(comentariosBase + '/' + taskId + '/comentarios', function(r) {
        if (!r.ok) return;
        renderComentarios(r.comentarios);
        $('#comentariosTareaTitle').text(r.task_title);
    }).fail(function() {
        $('#comentariosLista').html('<div class="text-center py-3 text-danger small">No se pudieron cargar los comentarios.</div>');
        toastr.error('Error al cargar comentarios');
    });
}

function burbujaComentarioHtml(c) {
    var cls = c.es_mio ? ' mio' : '';
    var avatarStyle = c.es_mio ? ' style="background:#dcfce7;color:#166534"' : '';
    var textoStyle  = c.es_mio ? ' style="background:#dbeafe;border-radius:12px 0 12px 12px"' : '';
    var metaAlign   = c.es_mio ? 'end' : 'start';
    return '<div class="comentario-burbuja' + cls + '" data-comment-id="' + c.id + '">'
        + '<div class="comentario-avatar"' + avatarStyle + '>' + c.initials + '</div>'
        + '<div class="comentario-cuerpo">'
        + '<div class="comentario-texto"' + textoStyle + '>' + $('<div>').text(c.comentario).html() + '</div>'
        + '<div class="comentario-meta d-flex align-items-center justify-content-' + metaAlign + '" style="gap:6px">'
        + '<span>' + c.autor + ' · ' + c.fecha + '</span>'
        + (c.es_mio ? '<a href="javascript:void(0)" class="text-danger btn-delete-comment" data-id="' + c.id + '" style="font-size:.65rem">Eliminar</a>' : '')
        + '</div></div></div>';
}

function renderComentarios(lista) {
    if (!lista.length) {
        $('#comentariosLista').html('<div class="text-center py-3 text-muted small">Aún no hay comentarios. ¡Sé el primero!</div>');
        return;
    }
    var html = '';
    lista.forEach(function(c) { html += burbujaComentarioHtml(c); });
    $('#comentariosLista').html(html);
    var $lista = $('#comentariosLista');
    $lista.scrollTop($lista[0].scrollHeight);
}

function syncComentarios(lista) {
    var existentes = {};
    $('#comentariosLista .comentario-burbuja').each(function() {
        existentes[$(this).data('comment-id')] = true;
    });
    var nuevos = lista.filter(function(c) { return !existentes[c.id]; });
    if (!nuevos.length) return;

    var $lista = $('#comentariosLista');
    if ($lista.find('.text-center').length) $lista.empty();

    var cercaDelFondo = $lista[0].scrollHeight - $lista.scrollTop() - $lista.outerHeight() < 80;
    nuevos.forEach(function(c) { $lista.append(burbujaComentarioHtml(c)); });
    actualizarContadorComentarios($('#comentariosTaskId').val(), nuevos.length);
    if (cercaDelFondo) $lista.scrollTop($lista[0].scrollHeight);
}

function actualizarContadorComentarios(taskId, delta) {
    var $btn = $('.btn-add-comment[data-id="' + taskId + '"]');
    var $badge = $btn.find('span');
    var count = Math.max(0, parseInt($badge.text() || '0', 10) + delta);
    if (count > 0) {
        if ($badge.length) $badge.text(count);
        else $btn.append('<span style="font-size:.65rem">' + count + '</span>');
        $btn.attr('title', 'Comentarios (' + count + ')');
    } else {
        $badge.remove();
        $btn.attr('title', 'Comentarios');
    }
}

$('#btnEnviarComentario').click(function() {
    var taskId = $('#comentariosTaskId').val();
    var texto  = $('#nuevoComentario').val().trim();
    if (!texto) return;
    var $btn = $(this).prop('disabled', true);
    $.ajax({
        url: comentariosBase + '/' + taskId + '/comentarios',
        type: 'POST',
        data: { _token: $('meta[name="csrf-token"]').attr('content'), comentario: texto },
        success: function(r) {
            $('#nuevoComentario').val('');
            var $lista = $('#comentariosLista');
            if ($lista.find('.text-center').length) $lista.empty();
            $lista.append(burbujaComentarioHtml(r.item));
            $lista.scrollTop($lista[0].scrollHeight);
            actualizarContadorComentarios(taskId, 1);
        },
        error: function() { toastr.error('Error al guardar comentario'); },
        complete: function() { $btn.prop('disabled', false); }
    });
});

$('#nuevoComentario').on('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        $('#btnEnviarComentario').click();
    }
});

$('body').on('click', '.btn-delete-comment', function() {
    var commentId = $(this).data('id');
    var $burbuja  = $(this).closest('.comentario-burbuja');
    var taskId    = $('#comentariosTaskId').val();
    $.ajax({
        url: comentariosBase + '/comentarios/' + commentId,
        type: 'DELETE',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function() {
            $burbuja.fadeOut(300, function() {
                $(this).remove();
                if (!$('#comentariosLista .comentario-burbuja').length) {
                    $('#comentariosLista').html('<div class="text-center py-3 text-muted small">Aún no hay comentarios. ¡Sé el primero!</div>');
                }
            });
            actualizarContadorComentarios(taskId, -1);
        },
        error: function() { toastr.error('Error al eliminar'); }
    });
});

// Polling: actualiza comentarios nuevos mientras el modal está abierto
$('#modalComentarios').on('shown.bs.modal', function() {
    if (comentariosPollTimer) clearInterval(comentariosPollTimer);
    comentariosPollTimer = setInterval(function() {
        var taskId = $('#comentariosTaskId').val();
        if (!taskId || !$('#modalComentarios').hasClass('show')) return;
        $.get(comentariosBase + '/' + taskId + '/comentarios', function(r) {
            if (r.ok) syncComentarios(r.comentarios);
        });
    }, 8000);
});

$('#modalComentarios').on('hidden.bs.modal', function() {
    if (comentariosPollTimer) {
        clearInterval(comentariosPollTimer);
        comentariosPollTimer = null;
    }
});
