<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Reunión MECIP - {{ $acta->numero_acta ?? 'IPS' }}</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .header-table td {
            vertical-align: top;
        }
        .title-institucion {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 2px;
        }
        .title-dependencia {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #333333;
            margin-bottom: 4px;
        }
        .title-acta {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .table-mecip {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table-mecip th, .table-mecip td {
            border: 1px solid #000000;
            padding: 5px 7px;
            font-size: 9.5pt;
        }
        .table-mecip th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            font-size: 10.5pt;
            text-transform: uppercase;
            margin-top: 14px;
            margin-bottom: 4px;
            text-decoration: underline;
        }
        .content-block {
            text-align: justify;
            white-space: pre-wrap;
            font-size: 10pt;
            line-height: 1.4;
            margin-bottom: 10px;
        }
        .firma-box {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: center;
            background: #fafafa;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    {{-- Encabezado --}}
    <table class="header-table">
        <tr>
            <td style="width: 25%;">
                @if(!empty($acta->logo_url))
                    <img src="{{ $acta->logo_url }}" style="max-width: 130px; max-height: 55px;">
                @else
                    <span style="font-size: 8pt; font-weight: bold; border: 1px solid #000; padding: 2px 5px; display: inline-block;">MECIP:2015</span>
                @endif
            </td>
            <td style="width: 50%; text-align: center;">
                <div class="title-institucion">{{ $acta->institucion ?? 'INSTITUTO DE PREVISIÓN SOCIAL' }}</div>
                <div class="title-dependencia">{{ $acta->dependencia ?? 'DIRECCIÓN DE PLANIFICACIÓN' }}</div>
                <div class="title-acta">ACTA DE REUNIÓN Nº {{ $acta->numero_acta }}</div>
            </td>
            <td style="width: 25%; text-align: right;">
                @if(!empty($qrBase64))
                    <img src="{{ $qrBase64 }}" style="width: 75px; height: 75px;">
                @endif
            </td>
        </tr>
    </table>

    {{-- Tabla de Metadatos --}}
    <table class="table-mecip">
        <tr>
            <td style="width: 20%; font-weight: bold; background: #f2f2f2;">LUGAR:</td>
            <td colspan="3">{{ $acta->lugar ?? 'REUNIÓN VIRTUAL' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background: #f2f2f2;">FECHA:</td>
            <td style="width: 30%;">{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : '' }}</td>
            <td style="font-weight: bold; background: #f2f2f2; width: 15%;">HORA:</td>
            <td>Desde: {{ $acta->hora_desde }} &nbsp;&nbsp; Hasta: {{ $acta->hora_hasta }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background: #f2f2f2;">CONVOCADOS:</td>
            <td colspan="3">{!! nl2br(e($acta->convocados_texto)) !!}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background: #f2f2f2;">TEMAS A TRATAR:</td>
            <td colspan="3">{!! nl2br(e($acta->temas_tratar)) !!}</td>
        </tr>
    </table>

    @if(!empty($acta->objetivo))
        <div class="section-title">OBJETIVO:</div>
        <div class="content-block">{!! nl2br(e($acta->objetivo)) !!}</div>
    @endif

    @if(!empty($acta->desarrollo))
        <div class="section-title">DESARROLLO (PUNTOS TRATADOS):</div>
        <div class="content-block">{!! nl2br(e($acta->desarrollo)) !!}</div>
    @endif

    @if(!empty($acta->acuerdos))
        <div class="section-title">COMPROMISOS / ACUERDOS:</div>
        <div class="content-block">{!! nl2br(e($acta->acuerdos)) !!}</div>
    @endif

    {{-- Participantes y Firmas --}}
    @if($acta->participantes->count() > 0)
        <div class="section-title" style="margin-top: 20px;">PARTICIPANTES Y ASISTENCIA REGISTRADA:</div>
        <table class="table-mecip">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Nombre y Apellido</th>
                    <th style="width: 30%;">Dependencia / Unidad</th>
                    <th style="width: 35%;">Firma Digital / Registro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($acta->participantes as $index => $part)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $part->nombre }} {{ $part->apellido }}</strong>
                            @if($part->cargo)<br><small style="color:#555;">{{ $part->cargo }}</small>@endif
                        </td>
                        <td>{{ $part->dependencia ?? '-' }}</td>
                        <td style="text-align: center;">
                            @if(!empty($part->firma_base64))
                                <img src="{{ $part->firma_base64 }}" style="max-height: 35px; max-width: 120px;">
                            @else
                                <span style="color: #16a34a; font-weight: bold; font-size: 8pt;">✓ Presente (Registrado)</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
