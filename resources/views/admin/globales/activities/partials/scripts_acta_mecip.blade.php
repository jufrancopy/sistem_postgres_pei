{{-- ══ Scripts para el Módulo de Acta de Reunión MECIP ════════════════════════ --}}
<script>
(function() {
    var _actaCurrentTaskId = null;
    var _actaCurrentData = null;
    var _actaBaseUrl = "{{ url('admin/globales/activities/tareas') }}";
    var signaturePadModerador = null;
    var canvasModerador = null;

    function initSignaturePadModerador() {
        if (!canvasModerador) {
            canvasModerador = document.getElementById('signature-pad-moderador');
            if (canvasModerador && typeof SignaturePad !== 'undefined') {
                signaturePadModerador = new SignaturePad(canvasModerador, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(15, 23, 42)'
                });
            }
        }
    }

    window.resizeCanvasModerador = function() {
        if (canvasModerador) {
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvasModerador.width = canvasModerador.offsetWidth * ratio;
            canvasModerador.height = canvasModerador.offsetHeight * ratio;
            canvasModerador.getContext("2d").scale(ratio, ratio);
            if (signaturePadModerador) {
                signaturePadModerador.clear();
            }
        }
    }

    // ── Agregar fila de compromiso ────────────────────────────────────────────
    function addCompromisoRow(responsable, compromiso) {
        responsable = responsable || '';
        compromiso  = compromiso  || '';

        var tr = '<tr>' +
            '<td class="p-1">' +
                '<input type="text" class="form-control form-control-sm border-0 font-weight-medium comp-responsable" ' +
                       'placeholder="Nombre / Cargo del responsable..." value="' + $('<div>').text(responsable).html() + '">' +
            '</td>' +
            '<td class="p-1">' +
                '<input type="text" class="form-control form-control-sm border-0 comp-compromiso" ' +
                       'placeholder="Descripción del compromiso asumido..." value="' + $('<div>').text(compromiso).html() + '">' +
            '</td>' +
            '<td class="p-1 text-center" style="vertical-align: middle;">' +
                '<button type="button" class="btn btn-xs btn-outline-danger btnRemoveCompromiso" style="font-size: 0.72rem; padding: 2px 6px;">' +
                    '<i class="fa fa-trash"></i>' +
                '</button>' +
            '</td>' +
        '</tr>';

        $('#tbodyCompromisos').append(tr);
    }

    // ── Renderizar lista de participantes ─────────────────────────────────────
    function renderParticipantes(participantes) {
        var tbody = $('#tbodyParticipantesActa');
        tbody.empty();

        if (!participantes || participantes.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center text-muted py-3" style="font-size: 0.82rem;"><i class="fa fa-users mr-1"></i>No hay participantes registrados aún. Compartí el QR para que se registren.</td></tr>');
            $('#actaParticipantesCountBadge').text('0 participantes');
            $('#modalQrCountText').text('0 participantes registrados');
            return;
        }

        $('#actaParticipantesCountBadge').text(participantes.length + ' participante(s)');
        $('#modalQrCountText').text(participantes.length + ' participante(s) registrado(s)');

        participantes.forEach(function(p) {
            var badgeQr = p.registrado_via_qr 
                ? '<span class="badge badge-success font-weight-normal px-2 py-1" style="font-size:0.7rem;"><i class="fa fa-qrcode mr-1"></i>QR</span>'
                : '<span class="badge badge-light border text-muted px-2 py-1" style="font-size:0.7rem;">Manual</span>';

            var tr = '<tr>' +
                '<td class="font-weight-bold text-dark">' + $('<div>').text(p.nombre_completo || (p.nombre + ' ' + p.apellido)).html() + ' ' + badgeQr + '</td>' +
                '<td>' + $('<div>').text(p.correo || '—').html() + '</td>' +
                '<td>' + $('<div>').text(p.dependencia || '—').html() + '</td>' +
                '<td>' + $('<div>').text(p.cargo || '—').html() + '</td>' +
                '<td>' + $('<div>').text(p.telefono || '—').html() + '</td>' +
                '<td class="text-center">' +
                    '<button type="button" class="btn btn-xs btn-outline-danger btnDeleteParticipanteActa" data-id="' + p.id + '" style="padding: 2px 6px; font-size: 0.7rem;" title="Eliminar">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                '</td>' +
            '</tr>';
            tbody.append(tr);
        });
    }

    // ── Cargar y Abrir Editor Proforma MECIP ───────────────────────────────────
    window.openEditorActaMecip = function(taskId) {
        _actaCurrentTaskId = taskId;
        $('#acta_task_id').val(taskId);
        $('#actaAlertContainer').hide().empty();
        $('#tbodyCompromisos').empty();
        $('#tbodyParticipantesActa').html('<tr><td colspan="6" class="text-center text-muted py-2"><i class="fa fa-spinner fa-spin mr-1"></i>Cargando participantes...</td></tr>');

        var url = _actaBaseUrl + '/' + taskId + '/acta-mecip';

        $.getJSON(url, function(res) {
            if (!res.ok) {
                alert('Error: ' + (res.message || 'No se pudo cargar el acta.'));
                return;
            }

            var a = res.acta;
            var t = res.task;
            _actaCurrentData = res;

            $('#actaSubtituloTarea').text('Tarea: ' + (t.title || 'Reunión'));
            $('#acta_uuid').val(a.uuid || '');
            $('#acta_estado').val(a.estado || 'borrador');
            $('#acta_numero').val(a.numero_acta || '');
            var logoUrl = a.logo_url || '';
            $('#acta_logo_url').val(logoUrl);
            
            // Ocultar MECIP:2015 badge si existe un logo (personalizado o heredado)
            if (logoUrl) {
                $('#badge_mecip_2015').hide();
            } else {
                $('#badge_mecip_2015').show();
            }
            
            $('#acta_institucion').val(a.institucion || 'INSTITUTO DE PREVISIÓN SOCIAL');
            $('#acta_dependencia').val(a.dependencia || 'CENTRO DE ENSEÑANZA, DOCUMENTACIÓN Y ESTUDIOS DE LA SEGURIDAD SOCIAL - CEDESS');
            $('#acta_lugar').val(a.lugar || 'REUNIÓN VIRTUAL');
            $('#acta_fecha').val(a.fecha ? a.fecha.substring(0, 10) : '');
            $('#acta_hora_desde').val(a.hora_desde || '15:00');
            $('#acta_hora_hasta').val(a.hora_hasta || '16:00');
            $('#acta_convocados').val(a.convocados_texto || '');
            $('#acta_temas').val(a.temas_tratar || '');
            $('#acta_objetivo').val(a.objetivo || '');
            $('#acta_desarrollo').val(a.desarrollo || '');
            $('#acta_acuerdos').val(a.acuerdos || '');

            // Estado y badges
            var isFinalizada = (a.estado === 'finalizada');
            $('#actaEstadoBadge')
                .text(isFinalizada ? 'Oficial / Finalizada' : 'Borrador en Línea')
                .toggleClass('badge-success', isFinalizada)
                .toggleClass('badge-warning text-dark', !isFinalizada);
            $('#actaFooterStatus').text(isFinalizada ? 'Finalizada' : 'Borrador');

            // Compromisos
            var compromisos = a.compromisos || [];
            if (typeof compromisos === 'string') {
                try { compromisos = JSON.parse(compromisos); } catch(e) { compromisos = []; }
            }
            if (compromisos.length > 0) {
                compromisos.forEach(function(c) {
                    addCompromisoRow(c.responsable, c.compromiso);
                });
            } else {
                addCompromisoRow('', '');
            }

            // Participantes
            renderParticipantes(a.participantes || []);

            // QR Info
            if (res.qr_svg) {
                $('#qrCodeSvgHolder').html(res.qr_svg);
                $('#actaQrThumbnail').html(res.qr_svg);
            }
            if (res.public_url) {
                $('#txtActaPublicUrl').val(res.public_url);
                $('#btnOpenPublicUrl').attr('href', res.public_url);
            }

            $('#modalEditorActaMecip').modal('show');
        }).fail(function() {
            alert('Error de conexión al cargar el acta.');
        });
    };

    // ── Dinamismo UI ────────────────────────────────────────────────────────
    $('#acta_logo_url').on('input', function() {
        if ($(this).val().trim() !== '') {
            $('#badge_mecip_2015').hide();
        } else {
            $('#badge_mecip_2015').show();
        }
    });

    // ── Guardar Acta ──────────────────────────────────────────────────────────
    function guardarActa(callback) {
        if (!_actaCurrentTaskId) return;

        var $btn = $('#btnGuardarActaMecip, #btnGuardarActaMecipFooter');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        // Recolectar compromisos
        var compromisos = [];
        $('#tbodyCompromisos tr').each(function() {
            var resp = $(this).find('.comp-responsable').val();
            var comp = $(this).find('.comp-compromiso').val();
            if (resp || comp) {
                compromisos.push({ responsable: resp, compromiso: comp });
            }
        });

        var formData = {
            institucion:      $('#acta_institucion').val(),
            dependencia:      $('#acta_dependencia').val(),
            numero_acta:      $('#acta_numero').val(),
            logo_url:         $('#acta_logo_url').val(),
            lugar:            $('#acta_lugar').val(),
            fecha:            $('#acta_fecha').val(),
            hora_desde:       $('#acta_hora_desde').val(),
            hora_hasta:       $('#acta_hora_hasta').val(),
            convocados_texto: $('#acta_convocados').val(),
            temas_tratar:     $('#acta_temas').val(),
            objetivo:         $('#acta_objetivo').val(),
            desarrollo:       $('#acta_desarrollo').val(),
            acuerdos:         $('#acta_acuerdos').val(),
            compromisos:      compromisos,
            estado:           $('#acta_estado').val(),
        };

        var url = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip';

        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Acta');
                if (res.ok) {
                    $('#actaAlertContainer').html(
                        '<div class="alert alert-success alert-dismissible fade show p-2 px-3 mb-0" style="font-size:0.85rem; border-radius:8px;">' +
                            '<i class="fa fa-check-circle mr-1"></i>' + (res.message || 'Acta guardada correctamente.') +
                            '<button type="button" class="close p-2" data-dismiss="alert">&times;</button>' +
                        '</div>'
                    ).slideDown();

                    if (res.acta && res.acta.uuid) {
                        $('#acta_uuid').val(res.acta.uuid);
                    }
                    if (res.qr_svg) {
                        $('#qrCodeSvgHolder').html(res.qr_svg);
                        $('#actaQrThumbnail').html(res.qr_svg);
                    }
                    if (res.public_url) {
                        $('#txtActaPublicUrl').val(res.public_url);
                        $('#btnOpenPublicUrl').attr('href', res.public_url);
                    }

                    // Recargar datos de reuniones en la tabla principal
                    if (typeof _reunionesUrl !== 'undefined') {
                        $.getJSON(_reunionesUrl, function(data) {
                            if (typeof _reunionesData !== 'undefined') _reunionesData = data;
                            if (typeof filtrarDt === 'function' && typeof _filtroActivo !== 'undefined') {
                                filtrarDt(_filtroActivo);
                            }
                        });
                    }

                    if (typeof callback === 'function') callback(res);
                } else {
                    alert('Error: ' + (res.message || 'No se pudo guardar el acta.'));
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Acta');
                var msg = 'Ocurrió un error al guardar el acta.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    }

    // ── Finalizar Acta ────────────────────────────────────────────────────────
    function finalizarActa(firmaBase64) {
        if (!_actaCurrentTaskId) return;

        var url = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip/finalizar';

        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify({ firma_moderador: firmaBase64 }),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.ok) {
                    $('#acta_estado').val('finalizada');
                    $('#actaEstadoBadge').text('Oficial / Finalizada').removeClass('badge-warning text-dark').addClass('badge-success');
                    $('#actaFooterStatus').text('Finalizada');
                    
                    alert('¡Reunión finalizada y Acta MECIP oficializada!');

                    if (typeof _reunionesUrl !== 'undefined') {
                        $.getJSON(_reunionesUrl, function(data) {
                            if (typeof _reunionesData !== 'undefined') _reunionesData = data;
                            if (typeof filtrarDt === 'function' && typeof _filtroActivo !== 'undefined') {
                                filtrarDt(_filtroActivo);
                            }
                        });
                    }
                } else {
                    alert('Error: ' + (res.message || 'No se pudo finalizar el acta.'));
                }
            },
            error: function() {
                alert('Error al procesar la finalización del acta.');
            }
        });
    }

    // ── Imprimir Acta ─────────────────────────────────────────────────────────
    function imprimirActa() {
        if (!_actaCurrentTaskId) return;
        var printUrl = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip/imprimir';
        window.open(printUrl, '_blank');
    }

    // ── Abrir Modal QR ────────────────────────────────────────────────────────
    function openQrModal() {
        if (!_actaCurrentTaskId) return;
        var url = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip';
        
        $.getJSON(url, function(res) {
            if (res.ok) {
                if (res.qr_svg) {
                    $('#qrCodeSvgHolder').html(res.qr_svg);
                }
                if (res.public_url) {
                    $('#txtActaPublicUrl').val(res.public_url);
                    $('#btnOpenPublicUrl').attr('href', res.public_url);
                }
                if (res.acta && res.acta.participantes) {
                    $('#modalQrCountText').text(res.acta.participantes.length + ' participante(s) registrado(s)');
                }
                $('#modalQrActa').modal('show');
            }
        });
    }

    // ── Eventos y Delegaciones ────────────────────────────────────────────────

    // Botones de abrir editor
    $(document).on('click', '.btnRedactarActaMecip', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var taskId = $(this).data('task-id');
        if (!taskId) {
            console.warn('btnRedactarActaMecip: no data-task-id found');
            return;
        }

        // Si se abrió desde el detalle de tarea o modal de reuniones, cerrarlos limpiamente
        $('#modalDetalleTask').modal('hide');
        $('#modalReuniones').modal('hide');
        $('#modalDocumentos').modal('hide');

        setTimeout(function() {
            openEditorActaMecip(taskId);
        }, 150);
    });

    // Mantener scroll del body si hay otros modales abiertos al cerrar este
    $('#modalEditorActaMecip, #modalQrActa').on('hidden.bs.modal', function() {
        if ($('.modal.show').length > 0) {
            $('body').addClass('modal-open');
        }
    });

    // Botón QR Directo desde la tabla
    $(document).on('click', '.btnVerQrDirecto', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var taskId = $(this).data('task-id');
        _actaCurrentTaskId = taskId;
        openQrModal();
    });

    // Botones del toolbar del editor
    $('#btnGuardarActaMecip, #btnGuardarActaMecipFooter').on('click', function(e) {
        e.preventDefault();
        guardarActa();
    });

    $('#btnFinalizarReunionMecip').on('click', function(e) {
        e.preventDefault();
        $('#modalFirmaModerador').modal('show');
    });

    $('#modalFirmaModerador').on('shown.bs.modal', function() {
        initSignaturePadModerador();
        resizeCanvasModerador();
    });

    $('#btnClearSignatureModerador').on('click', function() {
        if (signaturePadModerador) {
            signaturePadModerador.clear();
        }
    });

    $('#btnConfirmarFirmaModerador').on('click', function() {
        if (!signaturePadModerador || signaturePadModerador.isEmpty()) {
            alert("Por favor, dibuje su firma digital en el recuadro para oficializar el acta.");
            return;
        }
        var firmaBase64 = signaturePadModerador.toDataURL();
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Sellando...');
        
        guardarActa(function() {
            finalizarActa(firmaBase64);
            $btn.prop('disabled', false).html('<i class="fa fa-lock mr-1"></i> Firmar y Sellar Acta');
            $('#modalFirmaModerador').modal('hide');
        });
    });

    $('#btnImprimirActaMecip').on('click', function(e) {
        e.preventDefault();
        imprimirActa();
    });

    $('#btnCompartirQrActa, #btnProyectarQr').on('click', function(e) {
        e.preventDefault();
        openQrModal();
    });

    // Copiar URL QR
    $('#btnCopiarUrlQr').on('click', function() {
        var url = $('#txtActaPublicUrl').val();
        if (!url) return;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url);
        } else {
            var temp = $('<input>');
            $('body').append(temp);
            temp.val(url).select();
            document.execCommand('copy');
            temp.remove();
        }

        $('#btnCopiarText').text('¡Copiado!');
        setTimeout(function() {
            $('#btnCopiarText').text('Copiar');
        }, 2000);
    });

    // Actualizar conteo en modal QR
    $('#btnRefreshQrCount').on('click', function() {
        if (!_actaCurrentTaskId) return;
        var url = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip';
        $.getJSON(url, function(res) {
            if (res.ok && res.acta) {
                renderParticipantes(res.acta.participantes || []);
            }
        });
    });

    // Agregar / Quitar filas de compromisos
    $('#btnAddCompromisoRow').on('click', function() {
        addCompromisoRow('', '');
    });

    $(document).on('click', '.btnRemoveCompromiso', function() {
        $(this).closest('tr').remove();
    });

    // Participantes Manuales
    $('#btnAddParticipanteManual').on('click', function() {
        $('#formAddParticipanteManual')[0].reset();
        $('#modalAddParticipanteManual').modal('show');
    });

    $('#formAddParticipanteManual').on('submit', function(e) {
        e.preventDefault();
        if (!_actaCurrentTaskId) return;

        var $btn = $('#btnGuardarParticipanteManual');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        var url = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip/participantes';

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Guardar Participante');
                if (res.ok) {
                    $('#modalAddParticipanteManual').modal('hide');
                    // Recargar participantes
                    var getUrl = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip';
                    $.getJSON(getUrl, function(r) {
                        if (r.ok && r.acta) {
                            renderParticipantes(r.acta.participantes || []);
                        }
                    });
                } else {
                    alert('Error: ' + (res.message || 'No se pudo registrar al participante.'));
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Guardar Participante');
                var msg = 'Ocurrió un error al registrar al participante.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    });

    // Eliminar participante
    $(document).on('click', '.btnDeleteParticipanteActa', function() {
        if (!_actaCurrentTaskId) return;
        var pId = $(this).data('id');
        if (!confirm('¿Eliminar este participante del acta?')) return;

        var url = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip/participantes/' + pId;

        $.ajax({
            url: url,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.ok) {
                    var getUrl = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip';
                    $.getJSON(getUrl, function(r) {
                        if (r.ok && r.acta) {
                            renderParticipantes(r.acta.participantes || []);
                        }
                    });
                }
            }
        });
    });

    // Botón refrescar lista de participantes
    $('#btnRefreshParticipantes').on('click', function() {
        if (!_actaCurrentTaskId) return;
        var getUrl = _actaBaseUrl + '/' + _actaCurrentTaskId + '/acta-mecip';
        $.getJSON(getUrl, function(r) {
            if (r.ok && r.acta) {
                renderParticipantes(r.acta.participantes || []);
            }
        });
    });

})();
</script>
