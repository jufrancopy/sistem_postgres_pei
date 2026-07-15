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
</div>

<div class="wrap">
<table class="matriz">
    <thead>
        <tr>
            <th rowspan="2" style="min-width:130px">Resultados Intermedios Institucionales (1)</th>
            <th rowspan="2" style="min-width:175px">{{ $niveles['axi'] ?? 'Objetivo Estratégico' }} (2)</th>
            <th rowspan="2" style="min-width:170px">{{ $niveles['action'] ?? 'Acción Estratégica' }} (3)</th>
            <th rowspan="2" style="min-width:170px">Nombre del Indicador (4)</th>
            <th rowspan="2" style="min-width:80px">Unidad de medida (5)</th>
            <th rowspan="2" style="min-width:170px">Fórmula (6)</th>
            <th rowspan="2" style="min-width:120px">Línea de base (7)</th>
            <th colspan="{{ count($anios) }}">Metas anuales (8)</th>
            <th rowspan="2" style="min-width:110px">Responsable</th>
            <th rowspan="2" style="min-width:120px">Fuente sugerida</th>
        </tr>
        <tr>
            @foreach($anios as $anio)
            <th style="min-width:60px">{{ $anio }}</th>
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
            <td class="td-ri">{{ $axi->resultado_intermedio ?? '—' }}</td>
            <td class="td-obj">
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
                <td class="td-ri" rowspan="{{ $acciones->count() }}">
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
                <td class="td-obj" rowspan="{{ $acciones->count() }}">
                    <div class="obj-inner">
                        <span class="num-obj">{{ $axiNum }}</span>
                        <span>{!! strip_tags($axi->name) !!}</span>
                    </div>
                </td>
                @endif

                <td class="td-acc">
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
                <td class="td-ind">{{ $ind->nombre }}</td>
                <td style="text-align:center">{{ $ind->unidad_medida ?? '—' }}</td>
                <td class="td-formula">{{ $ind->formula ?? '—' }}</td>
                <td class="td-lb">
                    {{ $ind->linea_base_anio ? $ind->linea_base_anio.': ' : '' }}{{ $ind->linea_base_valor ?? '—' }}
                </td>
                @foreach($anios as $anio)
                <td class="td-meta">{{ $metas[$anio]['valor'] ?? '—' }}</td>
                @endforeach
                <td style="font-size:11px">
                    @foreach($action->responsibles as $r)
                        <div>{{ $r->dependency }}</div>
                    @endforeach
                </td>
                <td class="td-fuente">{{ $ind->fuente ?? '—' }}</td>

                @else
                <td class="td-ind" style="color:#999;font-style:italic">{{ $action->indicator ?? '—' }}</td>
                <td style="text-align:center">—</td>
                <td class="td-formula">—</td>
                <td class="td-lb">{{ $action->baseline ?? '—' }}</td>
                @foreach($anios as $anio)
                <td class="td-meta">—</td>
                @endforeach
                <td style="font-size:11px">
                    @foreach($action->responsibles as $r)
                        <div>{{ $r->dependency }}</div>
                    @endforeach
                </td>
                <td class="td-fuente">—</td>
                @endif
            </tr>
            @endforeach
        @endif
        @endforeach
    </tbody>
</table>
</div>

</body>
</html>
