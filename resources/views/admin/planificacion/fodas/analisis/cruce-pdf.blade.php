<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body        { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #222; margin: 0; }
        h1          { font-size: 14px; text-align: center; margin: 0 0 3px; color: #1a3a5c; }
        .subtitle   { text-align: center; font-size: 9px; color: #555; margin-bottom: 12px; }

        /* ── Ficha de perfil ── */
        .ficha      { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .ficha td   { padding: 3px 7px; font-size: 8.5px; border: 1px solid #ddd; }
        .ficha .lbl { background: #1a3a5c; color: #fff; font-weight: bold; width: 22%; }

        /* ── Tabla FODA cruzada ── */
        .matriz          { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .matriz th, .matriz td { border: 1px solid #bbb; padding: 5px 7px; vertical-align: top; font-size: 8px; }

        /* Encabezados de cuadrante */
        .th-fo  { background: #1a7a4a; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-do  { background: #1a5fa0; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-fa  { background: #b45309; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-da  { background: #9b1c1c; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-f   { background: #28a745; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-d   { background: #dc3545; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-o   { background: #17a2b8; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-a   { background: #fd7e14; color: #fff; font-size: 8.5px; font-weight: bold; }
        .th-vacio { background: #f0f0f0; }

        /* Badges de aspecto */
        .badge-f  { background: #28a745; color: #fff; padding: 1px 4px; border-radius: 3px; font-size: 7px; }
        .badge-d  { background: #dc3545; color: #fff; padding: 1px 4px; border-radius: 3px; font-size: 7px; }
        .badge-o  { background: #17a2b8; color: #fff; padding: 1px 4px; border-radius: 3px; font-size: 7px; }
        .badge-a  { background: #fd7e14; color: #fff; padding: 1px 4px; border-radius: 3px; font-size: 7px; }

        /* Estrategias */
        .estrategia-item { margin-bottom: 6px; padding-bottom: 5px; border-bottom: 1px dashed #ddd; }
        .estrategia-item:last-child { border-bottom: none; margin-bottom: 0; }
        .estrategia-num  { font-weight: bold; color: #555; margin-right: 3px; }
        .estrategia-txt  { line-height: 1.4; }
        .badges-row      { margin-bottom: 3px; }

        /* Aspectos laterales */
        .aspecto-item { margin-bottom: 3px; font-size: 7.5px; line-height: 1.3; }

        /* Sección de resumen */
        .resumen        { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .resumen th     { background: #1a3a5c; color: #fff; padding: 4px 7px; font-size: 8.5px; text-align: center; }
        .resumen td     { border: 1px solid #ccc; padding: 4px 7px; text-align: center; font-size: 9px; }
        .resumen .total { font-weight: bold; background: #f4f4f4; }

        @page { margin: 20mm 12mm 18mm 12mm; size: A4 landscape; }
    </style>
</head>
<body>

{{-- ── Encabezado / pie de página ── --}}
<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script(function($pageNum, $pageCount, $canvas, $fontMetrics) {
            $font     = $fontMetrics->getFont("DejaVu Sans", "normal");
            $fontBold = $fontMetrics->getFont("DejaVu Sans", "bold");
            $w = $canvas->get_width();
            $h = $canvas->get_height();

            $canvas->line(15, 16, $w - 15, 16, [0.1, 0.23, 0.36], 0.4);
            $canvas->text(15, 6, "SIPLAN — Cruce de Ambientes FODA", $fontBold, 7, [0.1, 0.23, 0.36]);
            $canvas->text($w - 55, 6, "{{ now()->format('d/m/Y') }}", $font, 7, [0.4, 0.4, 0.4]);

            $canvas->line(15, $h - 14, $w - 15, $h - 14, [0.6, 0.6, 0.6], 0.4);
            $canvas->text(15, $h - 11, "Generado por SIPLAN v1.0 — IPS Paraguay", $font, 6.5, [0.55, 0.55, 0.55]);
            $canvas->text($w - 50, $h - 11, "Página " . $pageNum . " de " . $pageCount, $font, 6.5, [0.55, 0.55, 0.55]);
        });
    }
</script>

{{-- ── Título ── --}}
<h1>Análisis FODA — Cruce de Ambientes</h1>
<p class="subtitle">Estrategias Ofensivas · Reorientación · Defensivas · Supervivencia</p>

{{-- ── Ficha del perfil ── --}}
<table class="ficha">
    <tr>
        <td class="lbl">Perfil</td>
        <td>{{ strip_tags($perfil->name ?? '—') }}</td>
        <td class="lbl">Tipo</td>
        <td>{{ ucfirst($perfil->type ?? '—') }}</td>
        <td class="lbl">Fecha</td>
        <td>{{ now()->format('d/m/Y') }}</td>
    </tr>
    @if($perfil->context)
    <tr>
        <td class="lbl">Contexto</td>
        <td colspan="5">{{ strip_tags($perfil->context) }}</td>
    </tr>
    @endif
</table>

{{-- ── Resumen de estrategias ── --}}
<table class="resumen">
    <tr>
        <th style="background:#1a7a4a">FO — Ofensivas</th>
        <th style="background:#1a5fa0">DO — Reorientación</th>
        <th style="background:#b45309">FA — Defensivas</th>
        <th style="background:#9b1c1c">DA — Supervivencia</th>
        <th style="background:#1a3a5c">Total</th>
    </tr>
    <tr>
        <td>{{ count($FOs) }}</td>
        <td>{{ count($DOs) }}</td>
        <td>{{ count($FAs) }}</td>
        <td>{{ count($DAs) }}</td>
        <td class="total">{{ count($FOs) + count($DOs) + count($FAs) + count($DAs) }}</td>
    </tr>
</table>

{{-- ══ MATRIZ CRUZADA ══ --}}
<table class="matriz">
    {{-- Fila 1: vacío | Fortalezas | Debilidades --}}
    <tr>
        <td class="th-vacio" style="width:18%"></td>

        {{-- Fortalezas --}}
        <td style="width:41%;vertical-align:top;padding:0">
            <div class="th-f" style="padding:5px 7px">Fortalezas ({{ count($fortalezas) }})</div>
            <div style="padding:5px 7px">
                @foreach($fortalezas as $v)
                <div class="aspecto-item">
                    <span class="badge-f">F{{ $v->aspecto->id }}</span>
                    {{ $v->aspecto->name }}
                </div>
                @endforeach
            </div>
        </td>

        {{-- Debilidades --}}
        <td style="width:41%;vertical-align:top;padding:0">
            <div class="th-d" style="padding:5px 7px">Debilidades ({{ count($debilidades) }})</div>
            <div style="padding:5px 7px">
                @foreach($debilidades as $v)
                <div class="aspecto-item">
                    <span class="badge-d">D{{ $v->aspecto->id }}</span>
                    {{ $v->aspecto->name }}
                </div>
                @endforeach
            </div>
        </td>
    </tr>

    {{-- Fila 2: Oportunidades | FO | DO --}}
    <tr>
        {{-- Oportunidades --}}
        <td style="vertical-align:top;padding:0">
            <div class="th-o" style="padding:5px 7px">Oportunidades ({{ count($oportunidades) }})</div>
            <div style="padding:5px 7px">
                @foreach($oportunidades as $v)
                <div class="aspecto-item">
                    <span class="badge-o">O{{ $v->aspecto->id }}</span>
                    {{ $v->aspecto->name }}
                </div>
                @endforeach
            </div>
        </td>

        {{-- FO --}}
        <td style="vertical-align:top;padding:0">
            <div class="th-fo" style="padding:5px 7px">FO — Estrategias Ofensivas ({{ count($FOs) }})</div>
            <div style="padding:5px 7px">
                @forelse($FOs as $i => $vi)
                <div class="estrategia-item">
                    <div class="badges-row">
                        @foreach($vi->fortalezas as $b)<span class="badge-f">F{{ $b->id }}</span> @endforeach
                        @foreach($vi->oportunidades as $b)<span class="badge-o">O{{ $b->id }}</span> @endforeach
                    </div>
                    <span class="estrategia-num">{{ $i + 1 }}.</span>
                    <span class="estrategia-txt">{{ $vi->estrategia }}</span>
                </div>
                @empty
                <p style="color:#999;font-style:italic;font-size:8px">Sin estrategias FO</p>
                @endforelse
            </div>
        </td>

        {{-- DO --}}
        <td style="vertical-align:top;padding:0">
            <div class="th-do" style="padding:5px 7px">DO — Estrategias de Reorientación ({{ count($DOs) }})</div>
            <div style="padding:5px 7px">
                @forelse($DOs as $i => $vi)
                <div class="estrategia-item">
                    <div class="badges-row">
                        @foreach($vi->debilidades as $b)<span class="badge-d">D{{ $b->id }}</span> @endforeach
                        @foreach($vi->oportunidades as $b)<span class="badge-o">O{{ $b->id }}</span> @endforeach
                    </div>
                    <span class="estrategia-num">{{ $i + 1 }}.</span>
                    <span class="estrategia-txt">{{ $vi->estrategia }}</span>
                </div>
                @empty
                <p style="color:#999;font-style:italic;font-size:8px">Sin estrategias DO</p>
                @endforelse
            </div>
        </td>
    </tr>

    {{-- Fila 3: Amenazas | FA | DA --}}
    <tr>
        {{-- Amenazas --}}
        <td style="vertical-align:top;padding:0">
            <div class="th-a" style="padding:5px 7px">Amenazas ({{ count($amenazas) }})</div>
            <div style="padding:5px 7px">
                @foreach($amenazas as $v)
                <div class="aspecto-item">
                    <span class="badge-a">A{{ $v->aspecto->id }}</span>
                    {{ $v->aspecto->name }}
                </div>
                @endforeach
            </div>
        </td>

        {{-- FA --}}
        <td style="vertical-align:top;padding:0">
            <div class="th-fa" style="padding:5px 7px">FA — Estrategias Defensivas ({{ count($FAs) }})</div>
            <div style="padding:5px 7px">
                @forelse($FAs as $i => $vi)
                <div class="estrategia-item">
                    <div class="badges-row">
                        @foreach($vi->fortalezas as $b)<span class="badge-f">F{{ $b->id }}</span> @endforeach
                        @foreach($vi->amenazas as $b)<span class="badge-a">A{{ $b->id }}</span> @endforeach
                    </div>
                    <span class="estrategia-num">{{ $i + 1 }}.</span>
                    <span class="estrategia-txt">{{ $vi->estrategia }}</span>
                </div>
                @empty
                <p style="color:#999;font-style:italic;font-size:8px">Sin estrategias FA</p>
                @endforelse
            </div>
        </td>

        {{-- DA --}}
        <td style="vertical-align:top;padding:0">
            <div class="th-da" style="padding:5px 7px">DA — Estrategias de Supervivencia ({{ count($DAs) }})</div>
            <div style="padding:5px 7px">
                @forelse($DAs as $i => $vi)
                <div class="estrategia-item">
                    <div class="badges-row">
                        @foreach($vi->debilidades as $b)<span class="badge-d">D{{ $b->id }}</span> @endforeach
                        @foreach($vi->amenazas as $b)<span class="badge-a">A{{ $b->id }}</span> @endforeach
                    </div>
                    <span class="estrategia-num">{{ $i + 1 }}.</span>
                    <span class="estrategia-txt">{{ $vi->estrategia }}</span>
                </div>
                @empty
                <p style="color:#999;font-style:italic;font-size:8px">Sin estrategias DA</p>
                @endforelse
            </div>
        </td>
    </tr>
</table>

</body>
</html>
