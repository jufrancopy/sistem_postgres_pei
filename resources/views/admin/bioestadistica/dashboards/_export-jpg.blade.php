<script>
(function () {
    const btn = document.getElementById('bioDashboardExportJpg');
    const target = document.getElementById('bioDashboardExportArea');
    if (!btn || !target || typeof html2canvas === 'undefined') {
        return;
    }

    function widgetsReady() {
        return [...document.querySelectorAll('.bio-widget[data-url]')].every(function (widget) {
            const body = widget.querySelector('.bio-widget-body');
            if (!body) {
                return true;
            }
            const text = body.textContent.trim();
            return text !== 'Cargando datos…'
                && text !== 'Cargando datos...'
                && !text.startsWith('No se pudieron');
        });
    }

    function waitForWidgets(maxAttempts) {
        return new Promise(function (resolve) {
            let attempts = 0;
            (function tick() {
                if (widgetsReady() || attempts >= maxAttempts) {
                    resolve(widgetsReady());
                    return;
                }
                attempts += 1;
                setTimeout(tick, 200);
            })();
        });
    }

    btn.addEventListener('click', async function () {
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="material-icons align-middle" style="font-size:16px;">hourglass_empty</i> Generando…';

        const ready = await waitForWidgets(40);
        if (!ready) {
            btn.disabled = false;
            btn.innerHTML = original;
            window.alert('Espere a que terminen de cargar los widgets antes de exportar.');
            return;
        }

        html2canvas(target, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            ignoreElements: function (el) {
                return el.classList && el.classList.contains('bio-no-export');
            },
        }).then(function (canvas) {
            const link = document.createElement('a');
            const safeName = (btn.dataset.filename || 'bioestadistica-dashboard')
                .replace(/[^\w\-]+/g, '_')
                .replace(/_+/g, '_');
            link.download = safeName + '.jpg';
            link.href = canvas.toDataURL('image/jpeg', 0.92);
            link.click();
        }).catch(function () {
            window.alert('No se pudo generar la imagen. Intente nuevamente.');
        }).finally(function () {
            btn.disabled = false;
            btn.innerHTML = original;
        });
    });
})();
</script>
