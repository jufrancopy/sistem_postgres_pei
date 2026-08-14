<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Reunión MECIP - {{ $acta->numero_acta ?? 'IPS' }}</title>
    <style>
        @page {
            margin: 12mm 12mm 12mm 12mm;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 9.5pt;
            line-height: 1.35;
            color: #000000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .title-institucion {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 2px;
        }
        .title-dependencia {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #333333;
            margin-bottom: 4px;
        }
        .title-acta {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .table-mecip {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .table-mecip th, .table-mecip td {
            border: 1px solid #000000;
            padding: 5px 6px;
            font-size: 9pt;
        }
        .table-mecip th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            margin-top: 12px;
            margin-bottom: 4px;
            text-decoration: underline;
        }
        .content-block {
            text-align: justify;
            white-space: pre-wrap;
            font-size: 9.5pt;
            line-height: 1.4;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    {{-- Encabezado Institucional --}}
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
                    <img src="{{ $qrBase64 }}" style="width: 70px; height: 70px;">
                @endif
            </td>
        </tr>
    </table>

    {{-- Tabla de Metadatos --}}
    <table class="table-mecip">
        <tr>
            <td style="width: 18%; font-weight: bold; background: #f2f2f2;">LUGAR:</td>
            <td colspan="3">{{ $acta->lugar ?? 'REUNIÓN VIRTUAL' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background: #f2f2f2;">FECHA:</td>
            <td style="width: 32%;">{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : '' }}</td>
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

    {{-- TABLA DE PARTICIPANTES Y FIRMAS DIGITALES --}}
    @if($acta->participantes->count() > 0)
        <div class="section-title" style="margin-top: 15px;">PARTICIPANTES Y ASISTENCIA REGISTRADA:</div>
        <table class="table-mecip" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">Nº</th>
                    <th style="width: 25%;">Nombre y Apellido</th>
                    <th style="width: 25%;">Dependencia / Cargo</th>
                    <th style="width: 25%;">Correo / Contacto</th>
                    <th style="width: 20%; text-align: center;">Firma / Asistencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($acta->participantes as $idx => $part)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                        <td style="font-weight: bold;">{{ $part->nombre_completo }}</td>
                        <td>
                            <div>{{ $part->dependencia ?? '—' }}</div>
                            @if($part->cargo)<small style="font-size: 8pt; color: #444;">{{ $part->cargo }}</small>@endif
                        </td>
                        <td>
                            <div>{{ $part->correo ?? '—' }}</div>
                            @if($part->telefono)<small style="font-size: 8pt; color: #444;">{{ $part->telefono }}</small>@endif
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            @if($part->firma)
                                <img src="{{ $part->firma }}" style="max-height: 38px; max-width: 110px; display: block; margin: 0 auto;">
                                <div style="font-size: 6.5pt; color: #166534; font-weight: bold; margin-top: 2px;">FIRMA DIGITAL VALIDA</div>
                            @elseif($part->registrado_via_qr)
                                <span style="color: #166534; font-weight: bold; font-size: 8pt;">Verificado Digital (QR)</span>
                            @else
                                <span style="color: #1e3a8a; font-weight: bold; font-size: 8pt;">Presente (Registrado)</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- FIRMA DEL MODERADOR --}}
    @if($acta->firma_moderador && $acta->estado === 'finalizada')
        <div style="margin-top: 25px; margin-bottom: 15px; text-align: center;">
            <div style="display: inline-block; text-align: center;">
                <img src="{{ $acta->firma_moderador }}" style="max-height: 80px; margin-bottom: 4px;">
                <div style="border-top: 1px solid #333; width: 230px; padding-top: 4px; margin: 0 auto; font-size: 9pt; font-weight: bold;">
                    MODERADOR / ENCARGADO
                </div>
            </div>
        </div>
    @endif

    {{-- DECLARACIÓN DE INTEGRIDAD Y PROCEDIMIENTO DE SEGURIDAD --}}
    <div style="margin-top: 15px; padding: 7px 9px; background: #f8fafc; border: 1px solid #cbd5e1; font-size: 7.5pt; color: #334155; line-height: 1.4; text-align: justify;">
        <strong>PROCEDIMIENTO DE SEGURIDAD E INTEGRIDAD DIGITAL (MECIP:2015):</strong>
        Este documento es un registro oficial generado por el Sistema PEI - IPS. Las asistencias y rúbricas fueron capturadas mediante autenticación digital de usuario o escaneo de código QR en tiempo real. 
        @if($acta->estado === 'finalizada' || $acta->hash_seguridad)
        Al ser finalizada el acta por el moderador, el sistema aplicó un sellado criptográfico inmutable SHA-256. Cualquier alteración posterior del texto o de las firmas invalida automáticamente el código de verificación y la autenticidad en el Portal Público.
        @else
        El acta se encuentra en estado de borrador / redacción preliminar hasta su firma y cierre definitivo.
        @endif
    </div>

    {{-- PIE DE PÁGINA CON CÓDIGO QR Y VALIDACIÓN --}}
    <table style="width: 100%; margin-top: 10px; border-top: 1px solid #000; padding-top: 5px;">
        <tr>
            <td style="width: 75%; vertical-align: top; font-size: 8pt; color: #333; line-height: 1.35;">
                <strong>DOCUMENTO DIGITAL REGISTRADO EN EL SISTEMA PEI - IPS</strong><br>
                Generado el {{ \Carbon\Carbon::now('America/Asuncion')->format('d/m/Y H:i') }} hs.<br>
                Validez oficial según estándares de control interno MECIP:2015.<br>
                @if($acta->hash_seguridad)
                <div style="font-family: monospace; font-size: 7pt; background: #f1f5f9; padding: 2px 4px; border: 1px solid #cbd5e1; display: inline-block; margin-top: 3px; font-weight: bold; color: #0f172a;">
                    CÓDIGO VERIFICADOR: {{ $acta->hash_seguridad }}
                </div><br>
                @endif
                <span style="font-size: 7pt; color: #555;">Enlace de verificación: {{ $publicUrl }}</span>
            </td>
            <td style="width: 25%; text-align: right; vertical-align: top;">
                @if(!empty($qrBase64))
                    <img src="{{ $qrBase64 }}" style="width: 65px; height: 65px; display: block; margin-left: auto;">
                    <div style="font-size: 6.5pt; font-weight: bold; text-align: right; margin-top: 2px;">ESCANEAR QR</div>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
