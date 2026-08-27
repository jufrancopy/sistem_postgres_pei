<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function () {
    const chartTypes = { barras: 'bar', lineas: 'line', pastel: 'pie' };
    const palette = ['#17a2b8', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997'];

    function coverageText(cobertura, periodo) {
        const cov = cobertura
            ? `Cobertura: ${cobertura.informados ?? 0} de ${cobertura.esperados ?? 0} (${cobertura.porcentaje ?? 0}%)`
            : '';
        return [periodo, cov].filter(Boolean).join(' · ');
    }

    function renderTable(table) {
        if (!table || !table.columns) return '<p class="text-muted">Sin datos</p>';
        let html = '<div class="table-responsive"><table class="table table-sm table-striped"><thead><tr>';
        table.columns.forEach(col => { html += `<th>${col.label}</th>`; });
        html += '</tr></thead><tbody>';
        (table.rows || []).forEach(row => {
            html += '<tr>';
            table.columns.forEach(col => { html += `<td>${row[col.key] ?? ''}</td>`; });
            html += '</tr>';
        });
        html += '</tbody></table></div>';
        return html;
    }

    function renderWidget(el, payload) {
        const period = el.querySelector('.bio-widget-period');
        const body = el.querySelector('.bio-widget-body');
        const fuenteEl = el.querySelector('.bio-widget-fuente');
        period.textContent = coverageText(payload.cobertura, payload.periodo_label);
        if (fuenteEl) {
            if (payload.fuente) {
                fuenteEl.hidden = false;
                fuenteEl.textContent = 'Fuente: ' + payload.fuente;
            } else {
                fuenteEl.hidden = true;
                fuenteEl.textContent = '';
            }
        }
        if (payload.tipo === 'kpi' || payload.tipo === 'indicador') {
            const color = payload.semaforo ? `<span class="bio-semaforo ${payload.semaforo}"></span>` : '';
            const unidad = payload.unidad ? `<small class="text-muted">${payload.unidad}</small>` : '';
            body.innerHTML = `${color}<div class="bio-kpi">${payload.valor ?? '—'}</div>${unidad}`;
            return;
        }
        if (payload.tipo === 'tabla' || payload.tipo === 'heatmap') {
            body.innerHTML = renderTable(payload.table);
            return;
        }
        const canvas = document.createElement('canvas');
        body.innerHTML = '';
        body.appendChild(canvas);
        const chart = payload.chart || { labels: [], datasets: [] };
        const dataset = chart.datasets[0] || { data: [], label: payload.titulo };
        dataset.backgroundColor = dataset.data.map((_, i) => palette[i % palette.length]);
        dataset.borderColor = '#17a2b8';
        dataset.fill = false;
        new Chart(canvas, {
            type: chartTypes[payload.tipo] || 'bar',
            data: { labels: chart.labels, datasets: [dataset] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: payload.tipo === 'pastel' } } }
        });
    }

    document.querySelectorAll('.bio-widget[data-url]').forEach(el => {
        fetch(el.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(payload => renderWidget(el, payload))
            .catch(() => {
                el.querySelector('.bio-widget-body').innerHTML = '<span class="text-danger">No se pudieron cargar los datos.</span>';
            });
    });

    const grid = document.getElementById('bioDashGrid');
    if (!grid || grid.dataset.editable !== '1') return;
    let dragged = null;
    grid.querySelectorAll('.bio-widget').forEach(el => {
        el.addEventListener('dragstart', () => { dragged = el; });
        el.addEventListener('dragover', e => { e.preventDefault(); el.classList.add('bio-drag-over'); });
        el.addEventListener('dragleave', () => el.classList.remove('bio-drag-over'));
        el.addEventListener('drop', e => {
            e.preventDefault();
            el.classList.remove('bio-drag-over');
            if (!dragged || dragged === el) return;
            const widgets = [...grid.querySelectorAll('.bio-widget')];
            const from = widgets.indexOf(dragged);
            const to = widgets.indexOf(el);
            if (from < to) el.after(dragged); else el.before(dragged);
            persistLayout();
        });
    });

    function persistLayout() {
        const widgets = [...grid.querySelectorAll('.bio-widget')].map((el, index) => ({
            id: Number(el.dataset.widgetId),
            pos_x: Number(el.dataset.posX || 0),
            pos_y: index,
            ancho: Number(el.dataset.ancho || 4),
            alto: Number(el.dataset.alto || 3),
        }));
        fetch(grid.dataset.layoutUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ widgets }),
        });
    }
})();
</script>
