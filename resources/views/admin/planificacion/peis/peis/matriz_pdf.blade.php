<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 7.5px; color: #111; }

    .header-doc {
        text-align: center;
        margin-bottom: 6px;
        border-bottom: 2px solid #1a237e;
        padding-bottom: 4px;
    }
    .header-doc h1 { font-size: 11px; color: #1a237e; font-weight: bold; }
    .header-doc p  { font-size: 8px; color: #555; margin-top: 2px; }

    table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #1a237e;
    }
    th, td {
        border: 1px solid #666;
        padding: 3px 4px;
        vertical-align: middle;
    }
    thead th {
        background-color: #1a237e;
        color: #ffffff;
        text-align: center;
        font-size: 7px;
        font-weight: bold;
        text-transform: uppercase;
    }
    thead tr:nth-child(2) th {
        background-color: #283593;
        font-size: 6.5px;
    }
    .td-ri {
        background-color: #dce8f8;
        color: #0d47a1;
        font-weight: bold;
        text-align: center;
        font-size: 7px;
        border-left: 3px solid #0d47a1;
    }
    .td-objetivo {
        background-color: #e8eaf6;
        color: #1a237e;
        font-weight: bold;
        text-align: center;
        font-size: 7px;
        border-left: 3px solid #1a237e;
    }
    .td-meta { text-align: center; }
    .num-obj {
        display: inline-block;
        background: #1a237e;
        color: #fff;
        border-radius: 50%;
        width: 13px; height: 13px;
        line-height: 13px;
        text-align: center;
        font-size: 6px;
        font-weight: bold;
        margin-right: 2px;
    }
    .num-acc {
        display: inline-block;
        background: #455a64;
        color: #fff;
        border-radius: 2px;
        padding: 0 3px;
        font-size: 6px;
        font-weight: bold;
        margin-right: 2px;
        line-height: 12px;
    }
    .footer-doc {
        margin-top: 6px;
        font-size: 6.5px;
        color: #888;
        text-align: right;
    }
</style>
</head>
<body>

<div class="header-doc">
    <h1>Matriz para la Formulación Estratégica Integrada</h1>
    <p>
        {{ strip_tags($profile->name) }} &nbsp;|&nbsp;
        {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }} – {{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
        &nbsp;|&nbsp; Generado: {{ now()->format('d/m/Y H:i') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th rowspan="2" style="width:9%">Resultados Intermedios (1)</th>
            <th rowspan="2" style="width:10%">{{ $niveles['axi'] ?? 'Objetivo Estratégico' }} (2)</th>
            <th rowspan="2" style="width:11%">{{ $niveles['action'] ?? 'Acción Estratégica' }} (3)</th>
            <th rowspan="2" style="width:11%">Indicador (4)</th>
            <th rowspan="2" style="width:5%">Unidad (5)</th>
            <th rowspan="2" style="width:12%">Fórmula (6)</th>
            <th rowspan="2" style="width:9%">Línea base (7)</th>
            <th colspan="{{ count($anios) }}">Metas anuales (8)</th>
            <th rowspan="2" style="width:8%">Responsable</th>
            <th rowspan="2" style="width:8%">Fuente sugerida</th>
        </tr>
        <tr>
            @foreach($anios as $anio)
            <th style="width:{{ intval(10 / count($anios)) }}%">{{ $anio }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $axiNum = 0; $actionGlobal = 0; @endphp
        @foreach($profile->children->sortBy('order_item') as $axi)
        @php
            $axiNum++;
            $acciones = $axi->descendants()->where('level','action')->with('indicador','responsibles')->get();
        @endphp

        @if($acciones->count() === 0)
        <tr>
            <td class="td-ri">{{ $axi->resultado_intermedio ?? '—' }}</td>
            <td class="td-objetivo"><span class="num-obj">{{ $axiNum }}</span>{!! strip_tags($axi->name) !!}</td>
            <td colspan="{{ 5 + count($anios) + 2 }}" style="text-align:center;font-style:italic;color:#999">Sin acciones registradas</td>
        </tr>
        @else
            @foreach($acciones as $idx => $action)
            @php $actionGlobal++; @endphp
            <tr>
                @if($idx === 0)
                <td class="td-ri" rowspan="{{ $acciones->count() }}">
                    {{ $axi->resultado_intermedio ?? '—' }}
                    @if($axi->ri_programa)<br><span style="font-size:6px;color:#555">{{ $axi->ri_programa }}</span>@endif
                    @if($axi->ri_recursos_gs)<br><strong style="font-size:6.5px">Gs. {{ number_format($axi->ri_recursos_gs,0,',','.') }}</strong>@endif
                </td>
                <td class="td-objetivo" rowspan="{{ $acciones->count() }}">
                    <span class="num-obj">{{ $axiNum }}</span>{!! strip_tags($axi->name) !!}
                </td>
                @endif

                <td><span class="num-acc">{{ $actionGlobal }}</span>{!! strip_tags($action->name) !!}</td>

                @if($action->indicador)
                @php
                    $ind = $action->indicador;
                    $metasInd = collect($ind->metas ?? [])->keyBy('anio');
                @endphp
                <td>{{ $ind->nombre }}</td>
                <td class="td-meta">{{ $ind->unidad_medida ?? '—' }}</td>
                <td style="font-style:italic">{{ $ind->formula ?? '—' }}</td>
                <td class="td-meta">{{ $ind->linea_base_anio ? $ind->linea_base_anio.': ' : '' }}{{ $ind->linea_base_valor ?? '—' }}</td>
                @foreach($anios as $anio)
                <td class="td-meta">{{ $metasInd[$anio]['valor'] ?? '—' }}</td>
                @endforeach
                <td style="font-size:6.5px">
                    @foreach($action->responsibles as $r){{ $r->dependency }}<br>@endforeach
                </td>
                <td style="font-size:6.5px">{{ $ind->fuente ?? '—' }}</td>

                @else
                <td style="font-style:italic;color:#999">{{ $action->indicator ?? '—' }}</td>
                <td class="td-meta">—</td>
                <td>—</td>
                <td class="td-meta">{{ $action->baseline ?? '—' }}</td>
                @foreach($anios as $anio)<td class="td-meta">—</td>@endforeach
                <td style="font-size:6.5px">
                    @foreach($action->responsibles as $r){{ $r->dependency }}<br>@endforeach
                </td>
                <td>—</td>
                @endif
            </tr>
            @endforeach
        @endif
        @endforeach
    </tbody>
</table>

<div class="footer-doc">
    {{ $axiNum }} {{ $niveles['axi'] ?? 'objetivos' }} &nbsp;·&nbsp; {{ $actionGlobal }} {{ $niveles['action'] ?? 'acciones' }}
</div>

</body>
</html>
