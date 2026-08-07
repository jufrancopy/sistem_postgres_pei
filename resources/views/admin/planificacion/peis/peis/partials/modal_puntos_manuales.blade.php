<div class="modal fade" id="modalPuntosManuales" tabindex="-1" role="dialog" aria-labelledby="modalPuntosManualesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff">
                <h5 class="modal-title" id="modalPuntosManualesLabel">
                    <i class="fa fa-star mr-2"></i>Asignar Puntos Manuales
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">

                {{-- Formulario --}}
                <div class="form-group">
                    <label class="font-weight-bold small"><i class="fa fa-user mr-1"></i>Funcionario</label>
                    <select id="pm_user" style="width:100%" class="form-control">
                        <option value="">Buscar funcionario...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold small"><i class="fa fa-tag mr-1"></i>Motivo / Tarea extra</label>
                    <input type="text" id="pm_motivo" class="form-control" placeholder="Ej: Cargó datos en sistema institucional..." autocomplete="off">
                    <div id="pm_motivos_sugeridos" class="d-flex flex-wrap mt-1" style="gap:5px"></div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold small"><i class="fa fa-coins mr-1"></i>Puntos a otorgar</label>
                    <div class="d-flex" style="gap:10px">
                        <button type="button" class="btn btn-outline-secondary btn-puntos flex-fill py-3" data-pts="5">
                            <div style="font-size:1.5rem;font-weight:800;color:#f59e0b">5</div>
                            <div style="font-size:.7rem" class="text-muted">Tarea simple</div>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-puntos flex-fill py-3" data-pts="10">
                            <div style="font-size:1.5rem;font-weight:800;color:#10b981">10</div>
                            <div style="font-size:.7rem" class="text-muted">Tarea moderada</div>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-puntos flex-fill py-3" data-pts="50">
                            <div style="font-size:1.5rem;font-weight:800;color:#6366f1">50</div>
                            <div style="font-size:.7rem" class="text-muted">Tarea destacada</div>
                        </button>
                    </div>
                    <input type="hidden" id="pm_puntos" value="">
                    <div id="pm_puntos_preview" class="mt-2 text-center" style="display:none">
                        <span class="badge badge-pill px-4 py-2" id="pm_puntos_badge" style="font-size:1rem;background:#7c3aed;color:#fff"></span>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Historial --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="font-weight-bold small text-muted"><i class="fa fa-history mr-1"></i>Historial de puntos otorgados en este plan</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnRefreshHistorial" title="Actualizar">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
                <table id="tblHistorialManual" class="table table-sm table-hover mb-0" style="width:100%">
                    <thead style="background:#f3f0ff">
                        <tr>
                            <th style="font-size:.75rem;color:#5b21b6">Fecha</th>
                            <th style="font-size:.75rem;color:#5b21b6">Funcionario</th>
                            <th style="font-size:.75rem;color:#5b21b6">Motivo</th>
                            <th style="font-size:.75rem;color:#5b21b6;text-align:right">Pts</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarPuntos" disabled>
                    <i class="fa fa-star mr-1"></i> Otorgar Puntos
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var $ = jQuery;
    var peiProfileId = '{{ $profile->id }}';
    var motivosSugeridos = [];
    var dtHistorial = null;

    // Inicializar DataTable del historial
    function initDtHistorial() {
        if (dtHistorial) return;
        dtHistorial = $('#tblHistorialManual').DataTable({
            serverSide: true,
            ajax: {
                url: '{{ route("pei-profiles.gamification.historial-manual", $profile->id) }}',
                type: 'GET'
            },
            columns: [
                { data: 'fecha',       width: '18%' },
                { data: 'funcionario', width: '22%' },
                { data: 'motivo',      render: function(d) { return '<span title="'+d+'">'+d+'</span>'; } },
                { data: 'puntos',      width: '8%', className: 'text-right',
                  render: function(d) {
                      var color = d >= 50 ? '#6366f1' : (d >= 10 ? '#10b981' : '#f59e0b');
                      return '<span style="font-weight:800;color:'+color+'">+'+d+'</span>';
                  }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 8,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                emptyTable: 'Aún no hay puntos manuales otorgados en este plan.'
            },
            dom: '<"d-flex justify-content-between align-items-center mb-2"f>rt<"d-flex justify-content-between align-items-center mt-2"ip>'
        });
    }

    // Cargar motivos sugeridos al abrir el modal
    $('#modalPuntosManuales').on('show.bs.modal', function() {
        $.getJSON('{{ route("pei-profiles.gamification.motivos", $profile->id) }}', function(data) {
            motivosSugeridos = data;
            renderMotivosSugeridos('');
        });
    });

    // Inicializar Select2 y DataTable cuando el modal ya está visible
    $('#modalPuntosManuales').on('shown.bs.modal', function() {
        initDtHistorial();
        if ($('#pm_user').hasClass('select2-hidden-accessible')) return;
        $('#pm_user').select2({
            dropdownParent: $('#modalPuntosManuales'),
            placeholder: 'Buscar funcionario por nombre...',
            allowClear: true,
            minimumInputLength: 2,
            language: { inputTooShort: function() { return 'Escribí al menos 2 caracteres...'; } },
            ajax: {
                url: '{{ route("pei-profiles.gamification.buscar-usuarios", $profile->id) }}',
                dataType: 'json',
                delay: 300,
                data: function(p) { return { q: p.term }; },
                processResults: function(data) {
                    return { results: $.map(data, function(u) { return { id: u.id, text: u.name }; }) };
                },
                cache: true
            }
        }).on('change', validarFormularioPM);
    });

    // Refresh manual
    $('#btnRefreshHistorial').on('click', function() {
        if (dtHistorial) dtHistorial.ajax.reload(null, false);
    });

    // Autocomplete de motivos
    $('#pm_motivo').on('input', function() {
        renderMotivosSugeridos($(this).val().toLowerCase());
        validarFormularioPM();
    });

    function renderMotivosSugeridos(filtro) {
        var $cont = $('#pm_motivos_sugeridos').empty();
        var lista = filtro
            ? motivosSugeridos.filter(function(m) { return m.toLowerCase().includes(filtro); })
            : motivosSugeridos;
        lista.slice(0, 8).forEach(function(m) {
            $('<span>')
                .text(m)
                .css({ background:'#ede9fe', color:'#5b21b6', fontSize:'.72rem', fontWeight:'600',
                       padding:'3px 10px', borderRadius:'20px', cursor:'pointer', display:'inline-block' })
                .on('click', function() {
                    $('#pm_motivo').val(m);
                    validarFormularioPM();
                })
                .appendTo($cont);
        });
    }

    // Selección de puntos
    $(document).on('click', '#modalPuntosManuales .btn-puntos', function() {
        $('#modalPuntosManuales .btn-puntos').removeClass('active').css({'border-color':'', 'box-shadow':''});
        $(this).addClass('active').css({'border-color':'#7c3aed', 'box-shadow':'0 0 0 3px rgba(124,58,237,.25)'});
        var pts = $(this).data('pts');
        $('#pm_puntos').val(pts);
        $('#pm_puntos_badge').text('+ ' + pts + ' puntos');
        $('#pm_puntos_preview').show();
        validarFormularioPM();
    });

    function validarFormularioPM() {
        var ok = $('#pm_user').val() && $('#pm_motivo').val().trim().length >= 3 && $('#pm_puntos').val();
        $('#btnConfirmarPuntos').prop('disabled', !ok);
    }

    // Enviar
    $('#btnConfirmarPuntos').on('click', function() {
        var btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Otorgando...');
        $.ajax({
            url: '{{ route("pei-profiles.gamification.award-manual", $profile->id) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                user_id: $('#pm_user').val(),
                motivo: $('#pm_motivo').val().trim(),
                puntos: $('#pm_puntos').val()
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fa fa-star mr-1"></i> Otorgar Puntos');
                toastr.success('<b>' + res.user_name + '</b> recibió <b>+' + res.puntos + ' pts</b>');
                resetFormPM();
                if (dtHistorial) dtHistorial.ajax.reload(null, false);
                // Actualizar motivos sugeridos
                if (!motivosSugeridos.includes(res.motivo)) motivosSugeridos.unshift(res.motivo);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-star mr-1"></i> Otorgar Puntos');
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error al otorgar puntos.';
                toastr.error(msg);
            }
        });
    });

    function resetFormPM() {
        if ($('#pm_user').hasClass('select2-hidden-accessible')) {
            $('#pm_user').val(null).trigger('change');
        }
        $('#pm_motivo').val('');
        $('#pm_puntos').val('');
        $('#pm_puntos_preview').hide();
        $('#modalPuntosManuales .btn-puntos').removeClass('active').css({'border-color':'', 'box-shadow':''});
        $('#pm_motivos_sugeridos').empty();
        $('#btnConfirmarPuntos').prop('disabled', true);
    }

    function resetModalPM() {
        resetFormPM();
        if ($('#pm_user').hasClass('select2-hidden-accessible')) {
            $('#pm_user').select2('destroy');
        }
    }

    $('#modalPuntosManuales').on('hidden.bs.modal', resetModalPM);
});
</script>
