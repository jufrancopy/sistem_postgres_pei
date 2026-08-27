@php
    $layoutUrl = $editable ? route('bioestadistica.dashboards.layout', $dashboard) : null;
    $periodQuery = http_build_query([
        'periodo_desde_anio' => $period['desde']['anio'],
        'periodo_desde_mes' => $period['desde']['mes'],
        'periodo_hasta_anio' => $period['hasta']['anio'],
        'periodo_hasta_mes' => $period['hasta']['mes'],
    ]);
@endphp
<div class="bio-dash-grid" id="bioDashGrid" data-layout-url="{{ $layoutUrl }}" data-editable="{{ $editable ? '1' : '0' }}">
    @forelse($dashboard->widgets as $widget)
        <article
            class="bio-widget card"
            draggable="{{ $editable ? 'true' : 'false' }}"
            data-widget-id="{{ $widget->id }}"
            data-tipo="{{ $widget->tipo }}"
            data-url="{{ route('bioestadistica.dashboards.widgets.data', [$dashboard, $widget]) }}?{{ $periodQuery }}"
            style="--w: {{ $widget->ancho }}; --h: {{ $widget->alto }}; grid-column: span {{ $widget->ancho }};"
            data-pos-x="{{ $widget->pos_x }}"
            data-pos-y="{{ $widget->pos_y }}"
            data-ancho="{{ $widget->ancho }}"
            data-alto="{{ $widget->alto }}"
        >
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <strong>{{ $widget->titulo }}</strong>
                <span class="badge badge-light text-uppercase">{{ $widget->tipo }}</span>
            </div>
            <div class="card-body">
                <p class="bio-widget-period text-muted small mb-2">Cargando período y cobertura…</p>
                <div class="bio-widget-body">
                    <div class="text-muted">Cargando datos…</div>
                </div>
                <p class="bio-widget-fuente text-muted small mb-0 mt-2" hidden></p>
            </div>
        </article>
    @empty
        <p class="text-muted">Este tablero todavía no tiene widgets.</p>
    @endforelse
</div>
<style>
.bio-dash-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 12px; }
.bio-widget { min-height: calc(var(--h, 3) * 70px); }
.bio-widget[draggable="true"] { cursor: grab; }
.bio-widget.bio-drag-over { outline: 2px dashed #17a2b8; }
.bio-kpi { font-size: 2rem; font-weight: 700; }
.bio-widget-fuente { font-size: 0.75rem; opacity: 0.85; }
.bio-semaforo { display: inline-block; width: 14px; height: 14px; border-radius: 50%; margin-right: 6px; }
.bio-semaforo.verde { background: #28a745; }
.bio-semaforo.amarillo { background: #ffc107; }
.bio-semaforo.rojo { background: #dc3545; }
.bio-semaforo.gris { background: #adb5bd; }
@media (max-width: 768px) {
    .bio-dash-grid { grid-template-columns: 1fr; }
    .bio-widget { grid-column: span 1 !important; }
}
</style>
