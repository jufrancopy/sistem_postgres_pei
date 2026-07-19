<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulación Estratégica Integrada — {{ strip_tags($profile->name) }}</title>
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 13px;
        background: #f4f6f9;
        color: #212529;
        padding: 16px;
    }

    /* ── Barra superior ── */
    .topbar {
        display: flex;
        align-items: center;
        background: #1a237e;
        color: #fff;
        padding: 10px 16px;
        border-radius: 6px 6px 0 0;
        gap: 12px;
        flex-wrap: wrap;
    }
    .topbar h1 { font-size: 15px; font-weight: 700; flex: 1; }
    .topbar .meta { font-size: 11px; opacity: .8; }
    .btn-top {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 4px; font-size: 12px;
        font-weight: 600; text-decoration: none; cursor: pointer;
        border: none;
    }
    .btn-back  { background: rgba(255,255,255,.15); color: #fff; }
    .btn-back:hover { background: rgba(255,255,255,.28); color: #fff; }
    .btn-pdf   { background: #c62828; color: #fff; }
    .btn-pdf:hover { background: #b71c1c; color: #fff; }
    .btn-print { background: rgba(255,255,255,.15); color: #fff; }
    .btn-print:hover { background: rgba(255,255,255,.28); }

    /* ── Contenedor tabla ── */
    .wrap {
        background: #fff;
        border-radius: 0 0 6px 6px;
        padding: 14px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }

    /* ── TABLA ── */
    table.matriz {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #1a237e;
        font-size: 12px;
    }
    table.matriz th,
    table.matriz td {
        border: 1px solid #9e9e9e;
        padding: 7px 9px;
        vertical-align: middle;
        line-height: 1.45;
    }

    /* Cabecera */
    table.matriz thead th {
        background: #1a237e;
        color: #fff;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-color: #3949ab;
        padding: 8px 9px;
    }
    table.matriz thead tr:nth-child(2) th {
        background: #283593;
        font-size: 10.5px;
    }

    /* Columna Resultado Intermedio */
    table.matriz td.td-ri {
        background: #e3f2fd;
        color: #0d47a1;
        font-weight: 600;
        text-align: center;
        font-size: 11.5px;
        border-left: 4px solid #1565c0 !important;
        border-right: 2px solid #90caf9 !important;
        border-top: 2px solid #64b5f6 !important;
    }

    /* Columna Objetivo */
    table.matriz td.td-obj {
        background: #e8eaf6;
        color: #1a237e;
        font-weight: 700;
        text-align: center;
        font-size: 12px;
        border-left: 4px solid #1a237e !important;
        border-right: 2px solid #9fa8da !important;
        border-top: 2px solid #9fa8da !important;
    }

    /* Separador entre grupos de objetivo */
    table.matriz tr.grupo-inicio td {
        border-top: 2px solid #7986cb !important;
    }

    /* Columna Acción */
    table.matriz td.td-acc {
        min-width: 170px;
        border-right: 2px solid #bdbdbd !important;
    }

    /* Metas */
    table.matriz td.td-meta {
        text-align: center;
        min-width: 62px;
        background: #fafafa;
    }

    /* Línea base */
    table.matriz td.td-lb {
        text-align: center;
        min-width: 120px;
        font-size: 11.5px;
        border-right: 2px solid #bdbdbd !important;
    }

    /* Fuente */
    table.matriz td.td-fuente {
        min-width: 120px;
        font-size: 11px;
        color: #555;
        border-left: 2px solid #bdbdbd !important;
    }

    /* Fórmula */
    table.matriz td.td-formula {
        font-style: italic;
        font-size: 11.5px;
        min-width: 170px;
    }

    /* Indicador */
    table.matriz td.td-ind { min-width: 170px; }

    /* ── Badges de numeración ── */
    .num-obj {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1a237e;
        color: #fff;
        border-radius: 50%;
        width: 24px; height: 24px;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
        margin-right: 7px;
    }
    .num-acc {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #37474f;
        color: #fff;
        border-radius: 4px;
        min-width: 26px;
        height: 22px;
        padding: 0 6px;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
        margin-right: 9px;
    }
    .obj-inner, .acc-inner {
        display: flex;
        align-items: flex-start;
    }
    .obj-inner span, .acc-inner span { flex: 1; }

    /* ── Selector de columnas ── */
    .col-toggle-btn {
        background: rgba(255,255,255,.15); color: #fff;
        border: none; border-radius: 4px; padding: 5px 12px;
        font-size: 12px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .col-toggle-btn:hover { background: rgba(255,255,255,.28); }
    .col-panel-wrap { position: relative; }
    .col-panel {
        position: absolute; top: calc(100% + 6px); right: 0;
        background: #fff; border: 1px solid #e2e8f0;
        border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.15);
        padding: 12px 16px; min-width: 240px; z-index: 999;
        display: none;
    }
    .col-panel.open { display: block; }
    .col-panel-title { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 8px; }
    .col-panel label { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #334155; padding: 5px 0; cursor: pointer; border-bottom: 1px solid #f1f5f9; }
    .col-panel label:last-child { border-bottom: none; }
    .col-panel input[type=checkbox] { width: 14px; height: 14px; cursor: pointer; accent-color: #1a237e; }
    .col-panel-actions { display: flex; gap: 6px; margin-top: 10px; }
    .col-panel-actions button { flex: 1; font-size: 11px; padding: 4px; border-radius: 4px; border: 1px solid #e2e8f0; cursor: pointer; background: #f8faff; color: #334155; }
    .col-panel-actions button:hover { background: #e8eaf6; }

    /* ── Print ── */
    @media print {
        body { background: #fff; padding: 0; font-size: 9px; }
        .topbar .btn-top { display: none; }
        table.matriz th, table.matriz td { padding: 4px 5px; font-size: 8px; }
        .num-obj { width: 16px; height: 16px; font-size: 8px; margin-right: 4px; }
        .num-acc { min-width: 18px; height: 15px; font-size: 8px; margin-right: 5px; }
    }
</style>
</head>
<body>

<div class="topbar">
    <div style="flex:1">
        <h1><i>Matriz para la Formulación Estratégica Integrada</i></h1>
        <div class="meta">
            {{ strip_tags($profile->name) }} &nbsp;·&nbsp;
            {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }} – {{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
        </div>
    </div>
    <a href="{{ route('pei-profiles.show', $profile->id) }}" class="btn-top btn-back">
        ← Volver
    </a>
    <a href="{{ route('pei-profiles.matriz.pdf', $profile->id) }}" class="btn-top btn-pdf" target="_blank">
        ⬇ Descargar PDF
    </a>
    <button onclick="window.print()" class="btn-top btn-print">
        🖨 Imprimir
    </button>
    <div class="col-panel-wrap">
        <button class="col-toggle-btn" id="btnColumnas">
            &#9776; Columnas
        </button>
        <div class="col-panel" id="colPanel">
            <div class="col-panel-title">Columnas visibles</div>
            <label><input type="checkbox" data-col="ri" checked> Resultados Intermedios</label>
            <label><input type="checkbox" data-col="obj" checked> {{ $niveles['axi'] ?? 'Objetivo Estratégico' }}</label>
            <label><input type="checkbox" data-col="acc" checked> {{ $niveles['action'] ?? 'Acción' }}</label>
            <label><input type="checkbox" data-col="ind" checked> Indicador</label>
            <label><input type="checkbox" data-col="unidad" checked> Unidad de medida</label>
            <label><input type="checkbox" data-col="formula" checked> Fórmula</label>
            <label><input type="checkbox" data-col="lb" checked> Línea de base</label>
            <label><input type="checkbox" data-col="metas" checked> Metas anuales</label>
            <label><input type="checkbox" data-col="resp" checked> Responsable</label>
            <label><input type="checkbox" data-col="fuente" checked> Fuente sugerida</label>
            <div class="col-panel-actions">
                <button id="btnMarcarTodas">Marcar todas</button>
                <button id="btnDesmarcarTodas">Desmarcar todas</button>
            </div>
        </div>
    </div>
</div>

<div class="wrap">
<table class="matriz">
    <thead>
        <tr>
            <th rowspan="2" style="min-width:130px" data-col="ri">Resultados Intermedios Institucionales (1)</th>
            <th rowspan="2" style="min-width:175px" data-col="obj">{{ $niveles['axi'] ?? 'Objetivo Estratégico' }} (2)</th>
            <th rowspan="2" style="min-width:170px" data-col="acc">{{ $niveles['action'] ?? 'Acción Estratégica' }} (3)</th>
            <th rowspan="2" style="min-width:170px" data-col="ind">Nombre del Indicador (4)</th>
            <th rowspan="2" style="min-width:80px" data-col="unidad">Unidad de medida (5)</th>
            <th rowspan="2" style="min-width:170px" data-col="formula">Fórmula (6)</th>
            <th rowspan="2" style="min-width:120px" data-col="lb">Línea de base (7)</th>
            <th colspan="{{ count($anios) }}" data-col="metas">Metas anuales (8)</th>
            <th rowspan="2" style="min-width:110px" data-col="resp">Responsable</th>
            <th rowspan="2" style="min-width:120px" data-col="fuente">Fuente sugerida</th>
        </tr>
        <tr>
            @foreach($anios as $anio)
            <th style="min-width:60px" data-col="metas">{{ $anio }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $axiNum = 0; $accNum = 0; @endphp
        @foreach($profile->children->sortBy('order_item') as $axi)
        @php
            $axiNum++;
            $acciones = $axi->descendants()->where('level','action')->with('indicador','responsibles')->get();
        @endphp

        @if($acciones->count() === 0)
        <tr class="grupo-inicio">
                <td class="td-ri" data-col="ri">{{ $axi->resultado_intermedio ?? '—' }}</td>
                    <td class="td-obj" data-col="obj">
                <div class="obj-inner">
                    <span class="num-obj">{{ $axiNum }}</span>
                    <span>{!! strip_tags($axi->name) !!}</span>
                </div>
            </td>
            <td colspan="{{ 5 + count($anios) + 2 }}" style="text-align:center;color:#999;font-style:italic">Sin acciones registradas</td>
        </tr>
        @else
            @foreach($acciones as $idx => $action)
            @php $accNum++; @endphp
            <tr class="{{ $idx === 0 ? 'grupo-inicio' : '' }}">
                @if($idx === 0)
                <td class="td-ri" rowspan="{{ $acciones->count() }}" data-col="ri">
                    @if($axi->resultado_intermedio)
                        <div style="font-weight:700;margin-bottom:4px">{{ $axi->resultado_intermedio }}</div>
                        @if($axi->ri_presupuestario)
                        <div style="font-size:10.5px;color:#555;margin-top:2px">{{ $axi->ri_presupuestario }}</div>
                        @endif
                        @if($axi->ri_programa)
                        <div style="font-size:10.5px;color:#555">{{ $axi->ri_programa }}</div>
                        @endif
                        @if($axi->ri_recursos_gs)
                        <div style="font-size:11px;font-weight:700;margin-top:4px">
                            Gs. {{ number_format($axi->ri_recursos_gs, 0, ',', '.') }}
                        </div>
                        @endif
                    @else
                        <span style="color:#999">—</span>
                    @endif
                </td>
                <td class="td-obj" rowspan="{{ $acciones->count() }}" data-col="obj">
                    <div class="obj-inner">
                        <span class="num-obj">{{ $axiNum }}</span>
                        <span>{!! strip_tags($axi->name) !!}</span>
                    </div>
                </td>
                @endif

                <td class="td-acc" data-col="acc">
                    <div class="acc-inner">
                        <span class="num-acc">{{ $accNum }}</span>
                        <span>{!! strip_tags($action->name) !!}</span>
                    </div>
                </td>

                @if($action->indicador)
                @php
                    $ind = $action->indicador;
                    $metas = collect($ind->metas ?? [])->keyBy('anio');
                @endphp
                <td class="td-ind" data-col="ind">{{ $ind->nombre }}</td>
                <td style="text-align:center" data-col="unidad">{{ $ind->unidad_medida ?? '—' }}</td>
                <td class="td-formula" data-col="formula">{{ $ind->formula ?? '—' }}</td>
                <td class="td-lb" data-col="lb">
                    {{ $ind->linea_base_anio ? $ind->linea_base_anio.': ' : '' }}{{ $ind->linea_base_valor ?? '—' }}
                </td>
                @foreach($anios as $anio)
                <td class="td-meta" data-col="metas">{{ $metas[$anio]['valor'] ?? '—' }}</td>
                @endforeach
                <td style="font-size:11px" data-col="resp">
                    @foreach($action->responsibles as $r)
                        <div>{{ $r->dependency }}</div>
                    @endforeach
                </td>
                <td class="td-fuente" data-col="fuente">{{ $ind->fuente ?? '—' }}</td>

                @else
                <td class="td-ind" style="color:#999;font-style:italic" data-col="ind">{{ $action->indicator ?? '—' }}</td>
                <td style="text-align:center" data-col="unidad">—</td>
                <td class="td-formula" data-col="formula">—</td>
                <td class="td-lb" data-col="lb">{{ $action->baseline ?? '—' }}</td>
                @foreach($anios as $anio)
                <td class="td-meta" data-col="metas">—</td>
                @endforeach
                <td style="font-size:11px" data-col="resp">
                    @foreach($action->responsibles as $r)
                        <div>{{ $r->dependency }}</div>
                    @endforeach
                </td>
                <td class="td-fuente" data-col="fuente">—</td>
                @endif
            </tr>
            @endforeach
        @endif
        @endforeach
    </tbody>
</table>
</div>

<script>
(function() {
    var STORAGE_KEY = 'matriz_cols_{{ $profile->id }}';
    var ALL_COLS = ['ri','obj','acc','ind','unidad','formula','lb','metas','resp','fuente'];

    function getCols() {
        try {
            var saved = localStorage.getItem(STORAGE_KEY);
            return saved ? JSON.parse(saved) : ALL_COLS.slice();
        } catch(e) { return ALL_COLS.slice(); }
    }

    function saveCols(cols) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cols));
    }

    function applyVisibility(cols) {
        ALL_COLS.forEach(function(col) {
            var visible = cols.indexOf(col) >= 0;
            document.querySelectorAll('[data-col="' + col + '"]').forEach(function(el) {
                el.style.display = visible ? '' : 'none';
            });
        });
    }

    function syncCheckboxes(cols) {
        document.querySelectorAll('#colPanel input[type=checkbox]').forEach(function(chk) {
            chk.checked = cols.indexOf(chk.dataset.col) >= 0;
        });
    }

    // Init
    var cols = getCols();
    applyVisibility(cols);

    document.addEventListener('DOMContentLoaded', function() {
        syncCheckboxes(cols);

        // Toggle panel
        document.getElementById('btnColumnas').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('colPanel').classList.toggle('open');
        });

        // Cerrar al click fuera
        document.addEventListener('click', function() {
            document.getElementById('colPanel').classList.remove('open');
        });
        document.getElementById('colPanel').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Cambio de checkbox
        document.querySelectorAll('#colPanel input[type=checkbox]').forEach(function(chk) {
            chk.addEventListener('change', function() {
                var cols = [];
                document.querySelectorAll('#colPanel input[type=checkbox]:checked').forEach(function(c) {
                    cols.push(c.dataset.col);
                });
                saveCols(cols);
                applyVisibility(cols);
            });
        });

        // Marcar / desmarcar todas
        document.getElementById('btnMarcarTodas').addEventListener('click', function() {
            saveCols(ALL_COLS.slice());
            syncCheckboxes(ALL_COLS.slice());
            applyVisibility(ALL_COLS.slice());
        });
        document.getElementById('btnDesmarcarTodas').addEventListener('click', function() {
            // Siempre dejar acc visible mínimo
            var min = ['acc'];
            saveCols(min);
            syncCheckboxes(min);
            applyVisibility(min);
        });
    });
})();
</script>
</body>
</html>
