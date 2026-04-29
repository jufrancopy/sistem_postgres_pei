@php $root = $profile->first(); @endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; margin: 0; }
        h1 { font-size: 15px; text-align: center; margin-bottom: 4px; }
        h2 { font-size: 12px; margin: 10px 0 3px; color: #1a5276; border-bottom: 1px solid #1a5276; page-break-after: avoid; }
        h3 { font-size: 10px; margin: 7px 0 2px; color: #117a65; page-break-after: avoid; }
        .subtitle { text-align: center; font-size: 10px; color: #555; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        th { background-color: #1a5276; color: #fff; padding: 4px 5px; text-align: left; font-size: 8.5px; }
        td { padding: 3px 5px; border: 1px solid #ccc; vertical-align: top; font-size: 8.5px; }
        tr:nth-child(even) td { background-color: #f4f4f4; }
        .badge-eje { background: #1e8449; color: #fff; padding: 1px 4px; border-radius: 3px; font-size: 7.5px; }
        .badge-obj { background: #1a5276; color: #fff; padding: 1px 4px; border-radius: 3px; font-size: 7.5px; }
        .section-meta td { border: none; padding: 2px 5px; }
        .responsible { display: inline-block; background: #eee; border-radius: 3px; padding: 1px 3px; margin: 1px; font-size: 7.5px; }
        .goal-block { page-break-inside: avoid; }
        @page { margin: 22mm 12mm 18mm 12mm; }
    </style>
</head>
<body>

<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script(function($pageNum, $pageCount, $canvas, $fontMetrics) {
            $font     = $fontMetrics->getFont("DejaVu Sans", "normal");
            $fontBold = $fontMetrics->getFont("DejaVu Sans", "bold");
            $w        = $canvas->get_width();
            $h        = $canvas->get_height();

            // --- Línea superior ---
            $canvas->line(15, 16, $w - 15, 16, [0.1, 0.32, 0.47], 0.4);

            // --- Encabezado: nombre del plan (izquierda) ---
            $canvas->text(15, 6, "PEI: {{ strip_tags($root->name) }}", $fontBold, 7, [0.1, 0.32, 0.47]);

            // --- Encabezado: período (derecha) ---
            $periodo = "Período: {{ \Carbon\Carbon::parse($root->year_start)->format('Y') }} - {{ \Carbon\Carbon::parse($root->year_end)->format('Y') }}";
            $canvas->text($w - 80, 6, $periodo, $font, 7, [0.33, 0.33, 0.33]);

            // --- Línea inferior ---
            $canvas->line(15, $h - 14, $w - 15, $h - 14, [0.6, 0.6, 0.6], 0.4);

            // --- Pie: info sistema (izquierda) ---
            $canvas->text(15, $h - 11, "Informe elaborado por SIPLAN Versión 01 - 2026  |  Desarrollado por ThePyDeveloper", $font, 6.5, [0.55, 0.55, 0.55]);

            // --- Pie: número de página (derecha) ---
            $canvas->text($w - 50, $h - 11, "Página " . $pageNum . " de " . $pageCount, $font, 6.5, [0.55, 0.55, 0.55]);
        });
    }
</script>

<h1>Consolidado del Plan Estratégico Institucional</h1>
<p class="subtitle">
    {{ strip_tags($root->name) }} &mdash;
    Período: {{ \Carbon\Carbon::parse($root->year_start)->format('Y') }} - {{ \Carbon\Carbon::parse($root->year_end)->format('Y') }}
</p>

{{-- Misión / Visión / Valores --}}
@if($root->mision || $root->vision || $root->values)
<table class="section-meta">
    @if($root->mision)
    <tr><td style="width:12%"><strong>Misión:</strong></td><td>{!! strip_tags($root->mision) !!}</td></tr>
    @endif
    @if($root->vision)
    <tr><td><strong>Visión:</strong></td><td>{!! strip_tags($root->vision) !!}</td></tr>
    @endif
    @if($root->values)
    <tr><td><strong>Valores:</strong></td><td>{!! strip_tags($root->values) !!}</td></tr>
    @endif
</table>
@endif

{{-- Resumen --}}
<table>
    <tr>
        <th>Ejes Estratégicos</th>
        <th>Objetivos</th>
        <th>Acciones</th>
        <th>Grupo de Trabajo</th>
    </tr>
    <tr>
        <td>{{ $root->where('level','axi')->count() }}</td>
        <td>{{ $root->where('level','goal')->count() }}</td>
        <td>{{ $root->where('level','action')->count() }}</td>
        <td>{{ $root->group->name ?? '-' }}</td>
    </tr>
</table>

{{-- Árbol del Plan --}}
@foreach($root->children->sortBy('order_item') as $axi)

<h2><span class="badge-eje">EJE</span> {{ strip_tags($axi->name) }}</h2>

    @foreach($axi->children->sortBy('order_item') as $goal)
    <div class="goal-block">
        <h3><span class="badge-obj">Objetivo</span> {{ strip_tags($goal->name) }}</h3>

        @if($goal->strategies->count())
        <p style="font-size:8px; margin:1px 0 3px 8px; color:#555;">
            <em>Estrategias: {{ $goal->strategies->pluck('estrategia')->implode(' | ') }}</em>
        </p>
        @endif

        @if($goal->children->count())
        <table>
            <thead>
                <tr>
                    <th style="width:4%">Nro.</th>
                    <th style="width:28%">Acción</th>
                    <th style="width:22%">Indicador</th>
                    <th style="width:13%">Línea de Base</th>
                    <th style="width:10%">Meta</th>
                    <th style="width:23%">Responsable(s)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($goal->children->sortBy('order_item') as $action)
                <tr>
                    <td>{{ $action->order_item }}</td>
                    <td>{{ strip_tags($action->name) }}</td>
                    <td>{{ strip_tags($action->indicator) }}</td>
                    <td>{{ strip_tags($action->baseline) }}</td>
                    <td>{{ strip_tags($action->target) }}</td>
                    <td>
                        @foreach($action->responsibles as $resp)
                            <span class="responsible">{{ $resp->dependency }}</span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endforeach

@endforeach

</body>
</html>
