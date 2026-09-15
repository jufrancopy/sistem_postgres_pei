@php
    $establecimientoNombre = $establecimientoNombre
        ?? $record->establecimiento->nombre
        ?? '';
    $periodLabel = \Carbon\Carbon::create($periodo_anio, $periodo_mes, 1)->translatedFormat('F Y');
    $spUrls = collect($siblingPlanillas)->mapWithKeys(function ($item) {
        $code = (string) $item['formulario']->codigo;
        if (! empty($item['current'])) {
            return [$code => '#current'];
        }

        return [$code => $item['url'] ?? null];
    })->all();
    $currentSibling = collect($siblingPlanillas)->firstWhere('current', true);
    $currentSp = $currentSibling['formulario']->codigo
        ?? ($record->formulario->codigo ?? null);
@endphp
<div class="card border mb-3" id="bio-sp-navigator">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-1" style="gap:8px">
            <span class="text-muted mr-1">
                Planillas de <strong>{{ $establecimientoNombre }}</strong>
                · {{ $periodLabel }}
            </span>
            <div class="bio-sp-locator position-relative" style="min-width:220px; max-width:340px; flex:1">
                <input
                    type="search"
                    id="bio-sp-locator-input"
                    class="form-control form-control-sm"
                    placeholder="¿En qué SP está una variable?"
                    autocomplete="off"
                    aria-label="Buscar variable en planillas SP"
                    aria-controls="bio-sp-locator-results"
                >
                <div
                    id="bio-sp-locator-results"
                    class="bio-sp-locator__results list-group shadow-sm"
                    hidden
                    role="listbox"
                ></div>
            </div>
        </div>
        <div class="d-flex flex-wrap" style="gap:6px">
            @foreach($siblingPlanillas as $item)
                @php
                    $spTitle = trim(($item['formulario']->codigo ?? '').' — '.($item['formulario']->nombre ?? ''));
                @endphp
                @if($item['current'])
                    <span class="btn btn-info btn-sm mb-0" disabled title="{{ $spTitle }}">{{ $item['formulario']->codigo }}</span>
                @elseif($item['url'])
                    <a class="btn btn-outline-info btn-sm mb-0" href="{{ $item['url'] }}" title="{{ $spTitle }}">{{ $item['formulario']->codigo }}</a>
                @elseif(auth()->user()->can('bio.record.create'))
                    <form method="POST" action="{{ route('bioestadistica.captura.store') }}" class="d-inline mb-0">
                        @csrf
                        <input type="hidden" name="formulario_id" value="{{ $item['formulario']->id }}">
                        <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
                        <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
                        <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">
                        @if(!empty($record->organo_id))
                            <input type="hidden" name="organo_id" value="{{ $record->organo_id }}">
                        @endif
                        <button class="btn btn-outline-secondary btn-sm mb-0" title="Iniciar {{ $spTitle }} en este establecimiento">
                            {{ $item['formulario']->codigo }}
                        </button>
                    </form>
                @else
                    <span class="btn btn-outline-secondary btn-sm mb-0 disabled" title="{{ $spTitle }}">{{ $item['formulario']->codigo }}</span>
                @endif
            @endforeach
        </div>
        <small class="text-muted d-block mt-1">Pase a otro SP del mismo establecimiento y período sin volver al listado. El botón con borde gris aún no tiene carga. Pase el mouse sobre un SP para ver su nombre.</small>
    </div>
</div>

<style>
    .bio-sp-locator__results {
        position: absolute;
        z-index: 1050;
        top: calc(100% + 2px);
        left: 0;
        right: 0;
        max-height: 280px;
        overflow-y: auto;
    }
    .bio-sp-locator__results .list-group-item {
        padding: .45rem .65rem;
        font-size: .85rem;
        cursor: pointer;
    }
    .bio-sp-locator__results .list-group-item:hover,
    .bio-sp-locator__results .list-group-item:focus {
        background: #e8f7fa;
    }
    .bio-sp-locator__meta {
        display: block;
        color: #6c757d;
        font-size: .75rem;
    }
    .bio-sp-locator__badge {
        display: inline-block;
        min-width: 2.4rem;
        font-weight: 600;
        color: #00838f;
    }
</style>

<script>
(function () {
    const input = document.getElementById('bio-sp-locator-input');
    const box = document.getElementById('bio-sp-locator-results');
    if (!input || !box) return;

    const searchUrl = @json(route('bioestadistica.captura.buscar-variable'));
    const spUrls = @json($spUrls);
    const currentSp = @json($currentSp);
    let timer = null;
    let seq = 0;

    function hide() {
        box.hidden = true;
        box.innerHTML = '';
    }

    function applyLocalItemSearch(term) {
        const local = document.getElementById('bio-item-search-input');
        if (!local) return;
        local.value = term || '';
        local.dispatchEvent(new Event('input', { bubbles: true }));
        local.focus();
    }

    function render(results) {
        if (!results.length) {
            box.innerHTML = '<div class="list-group-item text-muted">Sin coincidencias</div>';
            box.hidden = false;
            return;
        }

        box.innerHTML = results.map(function (row) {
            const url = spUrls[row.sp] || '';
            const isCurrent = url === '#current' || row.sp === currentSp;
            const term = row.item || row.campo || row.seccion || '';
            const meta = [row.formulario, row.seccion, row.campo].filter(Boolean).join(' · ');
            const hint = isCurrent
                ? 'En esta planilla'
                : (url ? 'Ir a ' + row.sp : 'Sin carga en este período');
            if (isCurrent) {
                return '<button type="button" class="list-group-item list-group-item-action text-left" data-local-term="'
                    + String(term).replace(/"/g, '&quot;') + '" role="option">'
                    + '<span class="bio-sp-locator__badge">' + row.sp + '</span> '
                    + '<span>' + (row.label || row.formulario) + '</span>'
                    + '<span class="bio-sp-locator__meta">' + hint + (meta ? ' · ' + meta : '') + '</span>'
                    + '</button>';
            }
            if (url) {
                return '<a class="list-group-item list-group-item-action" href="' + url + '" role="option">'
                    + '<span class="bio-sp-locator__badge">' + row.sp + '</span> '
                    + '<span>' + (row.label || row.formulario) + '</span>'
                    + '<span class="bio-sp-locator__meta">' + hint + (meta ? ' · ' + meta : '') + '</span>'
                    + '</a>';
            }
            return '<div class="list-group-item text-muted" role="option">'
                + '<span class="bio-sp-locator__badge">' + row.sp + '</span> '
                + '<span>' + (row.label || row.formulario) + '</span>'
                + '<span class="bio-sp-locator__meta">' + hint + (meta ? ' · ' + meta : '') + '</span>'
                + '</div>';
        }).join('');
        box.hidden = false;
    }

    box.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-local-term]');
        if (!btn) return;
        e.preventDefault();
        applyLocalItemSearch(btn.getAttribute('data-local-term') || '');
        hide();
    });

    function search(q) {
        const mySeq = ++seq;
        fetch(searchUrl + '?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (mySeq !== seq) return;
                render(data.results || []);
            })
            .catch(function () {
                if (mySeq !== seq) return;
                box.innerHTML = '<div class="list-group-item text-danger">No se pudo buscar</div>';
                box.hidden = false;
            });
    }

    input.addEventListener('input', function () {
        const q = (input.value || '').trim();
        clearTimeout(timer);
        if (q.length < 2) {
            hide();
            return;
        }
        timer = setTimeout(function () { search(q); }, 220);
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            hide();
            input.blur();
        }
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.bio-sp-locator')) {
            hide();
        }
    });
})();
</script>
