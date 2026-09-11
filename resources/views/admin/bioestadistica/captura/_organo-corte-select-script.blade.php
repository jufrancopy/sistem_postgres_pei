{{-- Rellena un <select> de dependencias vía /captura/cortes --}}
<script>
window.BioOrganoCorteSelect = window.BioOrganoCorteSelect || (function () {
    function fillSelect(selectEl, payload, preferredId, modalParent) {
        if (!selectEl) {
            return;
        }
        var required = !!(payload && payload.required);
        var opciones = (payload && payload.opciones) || [];
        var html = '<option value="">' + (required ? 'Seleccione' : 'Sin dependencia (establecimiento completo)') + '</option>';
        opciones.forEach(function (item) {
            html += '<option value="' + item.id + '">' + String(item.label || '').replace(/</g, '&lt;') + '</option>';
        });
        selectEl.innerHTML = html;
        selectEl.required = required;
        selectEl.disabled = false;
        if (preferredId) {
            selectEl.value = String(preferredId);
            if (selectEl.value !== String(preferredId)) {
                selectEl.value = '';
            }
        } else {
            selectEl.value = '';
        }

        var wrap = selectEl.closest('[data-bio-corte-wrap]');
        var mark = wrap ? wrap.querySelector('[data-bio-corte-required-mark]') : null;
        var help = wrap ? wrap.querySelector('[data-bio-corte-help]') : null;
        if (mark) {
            mark.classList.toggle('d-none', !required);
        }
        if (help) {
            help.textContent = required || opciones.length
                ? 'Busque y elija la dependencia (departamento, servicio, sección, etc.).'
                : 'Este establecimiento no tiene organigrama vinculado; la carga queda a nivel establecimiento.';
        }

        if (window.jQuery && window.jQuery.fn.select2) {
            var $el = window.jQuery(selectEl);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            var opts = {
                width: '100%',
                placeholder: $el.data('placeholder') || 'Buscar dependencia…',
                allowClear: !required
            };
            if (modalParent) {
                opts.dropdownParent = window.jQuery(modalParent);
            }
            $el.select2(opts);
        }
    }

    function load(selectEl, cortesUrl, establecimientoId, preferredId, modalParent) {
        if (!selectEl) {
            return;
        }
        if (!establecimientoId) {
            fillSelect(selectEl, { required: false, opciones: [] }, '', modalParent);
            return;
        }
        selectEl.disabled = true;
        fetch(cortesUrl + '?establecimiento_id=' + encodeURIComponent(establecimientoId), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (response) { return response.json(); })
            .then(function (data) { fillSelect(selectEl, data, preferredId || '', modalParent); })
            .catch(function () { fillSelect(selectEl, { required: false, opciones: [] }, '', modalParent); });
    }

    return { fillSelect: fillSelect, load: load };
})();
</script>
