<footer class="footer">
    <div class="container-fluid">
        <nav class="float-left">
            <small class="text-muted">
                {{ \App\Models\HomeConfiguration::getSetting('contact_email') ? 'Contacto: ' . \App\Models\HomeConfiguration::getSetting('contact_email') : '' }}
                {{ \App\Models\HomeConfiguration::getSetting('contact_phone') ? ' · Tel: ' . \App\Models\HomeConfiguration::getSetting('contact_phone') : '' }}
            </small>
        </nav>
        <div class="copyright float-right small text-muted">
            {{ \App\Models\HomeConfiguration::getSetting('footer_text', '© ' . date('Y') . ' Instituto de Previsión Social (IPS) — Dirección de Planificación.') }}
        </div>
    </div>
</footer>
</div>
</div>
<!--   Core JS Files   -->
{{-- <script src="{{ asset('js/app.js') }}" type="text/javascript"></script> --}}

{{-- JavaScripts --}}
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/tree.jquery.js') }}"></script>
<script src="{{ asset('master/assets/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('assets/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/jstree/jstree.js') }}"></script>
<script src="{{ asset('assets/jstree/jstreetable.min.js') }}"></script>
<script src="{{ asset('assets/googleCharts/loader.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src="{{ asset('master/assets/js/core/bootstrap-material-design.min.js') }}" type="text/javascript"></script>
<script>
(function() {
    if (window.jQuery && $.fn && $.fn.modal && $.fn.modal.Constructor) {
        var Modal = $.fn.modal.Constructor;
        var origShowElement = Modal.prototype._showElement;

        Modal.prototype._showElement = function(relatedTarget) {
            if (!this._element) return;
            if (!this._dialog) this._dialog = this._element.querySelector('.modal-dialog');
            
            if (this._dialog && $(this._dialog).hasClass('modal-dialog-scrollable')) {
                var body = this._dialog.querySelector('.modal-body');
                if (!body) {
                    $(this._dialog).removeClass('modal-dialog-scrollable');
                    try {
                        origShowElement.call(this, relatedTarget);
                    } finally {
                        $(this._dialog).addClass('modal-dialog-scrollable');
                    }
                    return;
                }
            }
            try {
                origShowElement.call(this, relatedTarget);
            } catch (err) {
                if (this._element) {
                    try { this._element.style.display = 'block'; } catch (e) {}
                }
            }
        };
    }
})();
</script>
<script src="{{ asset('master/assets/js/plugins/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>

<!-- Toastr JS -->
<script src="{{ asset('assets/toastr/toastr.min.js') }}"></script>

<!-- Toastr JS -->
<script src="{{ asset('assets/sweetAlert2/sweetalert2.all.min.js') }}"></script>


<!-- Chartist JS -->
<script src="{{ asset('master/assets/js/plugins/chartist.min.js') }}" type="text/javascript"></script>

<!--  Notifications Plugin    -->
<script src="{{ asset('master/assets/js/plugins/bootstrap-notify.js') }}" type="text/javascript"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc  -->
<script src="{{ asset('master/assets/js/material-dashboard.min.js?v=2.1.0') }}" type="text/javascript"></script>
<script>
    // En Windows Material Dashboard inicializa perfectScrollbar en .main-panel, rompiendo scrollIntoView, position:sticky y eventos scroll nativos
    if (window.isWindows || (navigator.platform && navigator.platform.indexOf('Win') > -1)) {
        try {
            if (window.jQuery && $.fn && $.fn.perfectScrollbar) {
                $('.main-panel').perfectScrollbar('destroy');
            }
        } catch(e) {}
        $('html').removeClass('perfect-scrollbar-on').addClass('perfect-scrollbar-off');
    }
</script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="{{ asset('js/cursos.js') }}"></script>
<script src="{{ asset('assets/bootbox/bootbox.all.js') }}"></script>

<script src="{{ asset('assets/orgchart/demo/js/jquery.orgchart.js') }}" defer></script>
<script src="{{ asset('js/orgchart.js') }}"></script>
<script src="{{ asset('js/config.js') }}"></script>

<script>
    window.CKEDITOR_BASEPATH = "{{ asset('assets/ckeditor') }}/";
</script>
<script src="{{ asset('assets/ckeditor5/classic_ckeditor.js') }}"></script>
<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>

<!-- prettier-ignore -->
  <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
({key: "{{ env('MAPS_GOOGLE_MAPS_ACCESS_TOKEN') }}", v: "beta"});</script>

@yield('scripts')
@stack('scripts')

</body>
<script>
    $(document).ready(function() {
        $('.js-example-responsive').select2();

        // ── Fix global Select2: dropdown siempre hacia abajo ────────
        $(document).on('select2:open', function() {
            setTimeout(function() {
                var $above = $('.select2-dropdown--above');
                if ($above.length) {
                    $above.removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                    $above.css('margin-top', '0');
                }
                var $field = $('.select2-container--open .select2-search__field');
                if ($field.length) $field.first().focus();
            }, 50);
        });

        // ── Gestor Global de Modales (Resuelve congelamiento de pantalla y apilamiento de backdrops) ──
        $(document).on('show.bs.modal', '.modal', function () {
            // 1. Mover siempre el modal a document.body para evitar trampas de z-index y overflow:hidden
            if ($(this).parent()[0] !== document.body) {
                $(this).appendTo('body');
            }

            // 2. Apilar z-index para soportar múltiples modales abiertos simultáneamente
            var openModals = $('.modal:visible').length;
            var zIndex = 1050 + (10 * openModals);
            $(this).css('z-index', zIndex);

            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').each(function() {
                    $(this).css('z-index', zIndex - 5).addClass('modal-stack');
                });
            }, 10);
        });

        $(document).on('hidden.bs.modal', '.modal', function () {
            // 3. Al cerrar un modal, verificar si quedan otros abiertos
            if ($('.modal:visible').length > 0) {
                $(document.body).addClass('modal-open');
            } else {
                // Limpiar todos los backdrops huérfanos y desbloquear el scroll del body
                $('.modal-backdrop').remove();
                $(document.body).removeClass('modal-open').css('padding-right', '');
            }
        });
    });
</script>
