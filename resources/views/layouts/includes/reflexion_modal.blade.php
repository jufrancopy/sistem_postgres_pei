{{-- ══ MODAL REFLEXIÓN DEL DÍA Y CÓDIGO DE ÉTICA IPS ══════════════════════════ --}}
<div class="modal fade" id="modalReflexionDiaria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1rem; overflow:hidden; background:#fafbff;">
            
            {{-- Header con gradiente elegante --}}
            <div class="modal-header border-0 py-3 text-white" id="reflexionHeader" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1a237e 100%);">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center bg-white text-dark rounded-circle shadow-sm" style="width:38px; height:38px; flex-shrink:0;">
                        <i class="fa fa-lightbulb text-warning" id="reflexionIcon" style="font-size:1.1rem;"></i>
                    </span>
                    <div>
                        <span class="badge badge-light text-uppercase font-weight-bold px-2 py-1 mb-1" id="reflexionCategory" style="font-size:.65rem; letter-spacing:.05em; border-radius:12px;">
                            Código de Ética IPS
                        </span>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="reflexionTitle" style="font-size:1.1rem; line-height:1.2;">
                            Inspiración para la Jornada
                        </h5>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-75" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>

            {{-- Body principal --}}
            <div class="modal-body p-4">
                
                {{-- Caja de Frase Destacada --}}
                <div class="p-4 mb-3 text-center position-relative rounded-lg shadow-sm" style="background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border-left: 5px solid #1e3a8a; border-radius: 12px;">
                    <i class="fa fa-quote-left position-absolute text-muted opacity-25" style="top:12px; left:16px; font-size:2rem;"></i>
                    <p class="font-weight-bold text-dark mb-0 font-italic" id="reflexionFrase" style="font-size: 1.15rem; line-height: 1.5; color: #0f172a;">
                        "Cargando reflexión..."
                    </p>
                    <i class="fa fa-quote-right position-absolute text-muted opacity-25" style="bottom:12px; right:16px; font-size:2rem;"></i>
                </div>

                {{-- Explicación / Cita textual del Código de Ética o Filósofo --}}
                <div class="bg-white p-3 rounded-lg border mb-3 shadow-xs" style="border-radius: 10px;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa fa-book-open text-primary mr-2" style="font-size:.85rem;"></i>
                        <strong class="text-dark small text-uppercase" style="letter-spacing:.04em;" id="reflexionSecTitulo">Definición y Alcance Ético</strong>
                    </div>
                    <p class="text-secondary small mb-2" id="reflexionDefinicion" style="line-height:1.5;">
                        ...
                    </p>
                    <div class="bg-light p-2.5 rounded border-left border-info small font-italic text-dark" id="reflexionDeclaracionBox" style="font-size:.78rem;">
                        <span id="reflexionDeclaracion">...</span>
                    </div>
                </div>

                {{-- Botones de Acción e IA --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top">
                    <div class="d-flex gap-2 mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" id="btnReflexionOtra" title="Ver otra frase del banco">
                            <i class="fa fa-sync-alt mr-1"></i> Otra Frase
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-purple font-weight-bold text-white" id="btnReflexionGroq" style="background: linear-gradient(135deg, #7c3aed, #4f46e5); border:none;" title="Generar reflexión personalizada con IA Groq">
                            <i class="fa fa-robot mr-1"></i> Generar con IA (Groq)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info font-weight-bold" id="btnReflexionCopiar" title="Copiar frase al portapapeles">
                            <i class="fa fa-copy mr-1"></i> Copiar
                        </button>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm px-4 font-weight-bold shadow-sm" data-dismiss="modal" id="btnCerrarReflexion">
                        <i class="fa fa-check-circle mr-1"></i> ¡Comenzar Jornada!
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Script de la Reflexión Diaria --}}
<script>
$(document).ready(function() {
    // Mover modal al body raíz para evitar atrapamiento de z-index
    if ($('#modalReflexionDiaria').length) {
        $('#modalReflexionDiaria').appendTo('body');
    }

    var hoyStr = new Date().toISOString().slice(0, 10);
    var userId = "{{ Auth::id() }}";
    var storageKey = 'ips_reflexion_fecha_' + userId;
    var ultimaFecha = localStorage.getItem(storageKey);
    var isImpersonating = {{ session()->has('impersonator_id') ? 'true' : 'false' }};

    function cargarReflexion(random = 0) {
        var url = "{{ route('globales.reflexion.diaria') }}";
        if (random === 1) url += "?random=1";

        $.get(url, function(res) {
            if (res.success && res.data) {
                renderReflexion(res.data);
            }
        });
    }

    function renderReflexion(d) {
        $('#reflexionCategory').text(d.categoria || 'Reflexión Diaria');
        $('#reflexionTitle').text(d.titulo || 'Inspiración Institucional');
        $('#reflexionFrase').text('"' + d.frase + '"');
        $('#reflexionDefinicion').text(d.definicion || '');
        $('#reflexionDeclaracion').text(d.declaracion || '');
        
        if (d.color) {
            $('#reflexionHeader').css('background', 'linear-gradient(135deg, ' + d.color + ', #0f172a)');
        }
    }

    // Evento al hacer clic en el botón de la Navbar
    $(document).on('click', '#btnOpenReflexion', function(e) {
        e.preventDefault();
        cargarReflexion(1);
        $('#modalReflexionDiaria').modal('show');
    });

    // Evento para obtener otra frase aleatoria
    $(document).on('click', '#btnReflexionOtra', function() {
        var $btn = $(this).addClass('disabled');
        cargarReflexion(1);
        setTimeout(function() { $btn.removeClass('disabled'); }, 400);
    });

    // Evento para generar con Groq AI
    $(document).on('click', '#btnReflexionGroq', function() {
        var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando con IA...');
        var tituloActual = $('#reflexionTitle').text();

        $.ajax({
            url: "{{ route('globales.reflexion.ia') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                valor: tituloActual
            },
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-robot mr-1"></i> Generar con IA (Groq)');
                if (res.success && res.data) {
                    renderReflexion(res.data);
                    if (typeof toastr !== 'undefined') toastr.success('¡Reflexión IA generada con éxito!');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-robot mr-1"></i> Generar con IA (Groq)');
                if (typeof toastr !== 'undefined') toastr.error('No se pudo conectar con el servicio de IA.');
            }
        });
    });

    // Copiar frase
    $(document).on('click', '#btnReflexionCopiar', function() {
        var texto = $('#reflexionFrase').text() + ' - ' + $('#reflexionTitle').text();
        navigator.clipboard.writeText(texto).then(function() {
            if (typeof toastr !== 'undefined') toastr.info('Frase copiada al portapapeles');
            else alert('Frase copiada: ' + texto);
        });
    });

    // Guardar fecha al cerrar modal
    $(document).on('click', '#btnCerrarReflexion, #modalReflexionDiaria .close', function() {
        localStorage.setItem(storageKey, hoyStr);
    });

    // Auto-mostrar la primera vez que ingresa en el día (solo si NO está simulando rol)
    if (!isImpersonating && ultimaFecha !== hoyStr) {
        setTimeout(function() {
            cargarReflexion(0);
            $('#modalReflexionDiaria').modal('show');
            localStorage.setItem(storageKey, hoyStr);
        }, 1000);
    }
});
</script>
