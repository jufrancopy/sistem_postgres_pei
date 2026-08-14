<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Reunión MECIP - {{ $acta->numero_acta ?? 'IPS' }}</title>
    
    {{-- CSS para Impresión y Vista Oficial MECIP --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Arial:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @page {
            size: A4 portrait;
            margin: 1.8cm 1.5cm 1.8cm 1.5cm;
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Arial', 'Times New Roman', serif;
            color: #000000;
            font-size: 11pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .no-print-bar {
            background: #1e293b;
            color: white;
            padding: 12px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .paper-sheet {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 20mm 20mm;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            box-sizing: border-box;
            position: relative;
        }

        .table-mecip {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table-mecip th, 
        .table-mecip td {
            border: 1.5px solid #000000;
            padding: 6px 8px;
            font-size: 10pt;
        }

        .table-mecip th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 5px;
            text-decoration: underline;
        }

        .content-paragraph {
            text-align: justify;
            white-space: pre-wrap;
            font-size: 10.5pt;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        @media print {
            body {
                background-color: white !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .paper-sheet {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    {{-- Barra flotante de acciones (no imprimible) --}}
    <div class="no-print-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center" style="gap: 10px;">
            <i class="fa fa-file-alt text-warning"></i>
            <span class="font-weight-bold">Vista de Impresión Oficial MECIP - {{ $acta->numero_acta }}</span>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
            <button onclick="window.close()" class="btn btn-sm btn-outline-light font-weight-bold px-3">
                <i class="fa fa-times mr-1"></i> Cerrar
            </button>
            <button onclick="window.print()" class="btn btn-sm btn-success font-weight-bold px-4 shadow-sm">
                <i class="fa fa-print mr-1"></i> Imprimir / Guardar PDF
            </button>
        </div>
    </div>

    {{-- Hoja A4 --}}
    <div class="paper-sheet">
        
        {{-- Encabezado Institucional --}}
        <div class="text-center mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div style="width: 130px; text-align: left;">
                    <img src="{{ !empty($acta->logo_url) ? $acta->logo_url : asset('material/img/new_logo.png') }}" style="max-width: 100%; height: auto; max-height: 55px; margin-bottom: 5px;">
                    @if(empty($acta->logo_url))
                        <br>
                        <small class="font-weight-bold" style="font-size: 8pt; border: 1px solid #000; padding: 2px 4px; display: inline-block; margin-top: 5px;">MECIP:2015</small>
                    @endif
                </div>
                <div class="text-center flex-grow-1" style="padding-top: 10px;">
                    <h5 class="font-weight-bold mb-0 text-uppercase" style="font-size: 13pt;">
                        {{ $acta->institucion ?? 'INSTITUTO DE PREVISIÓN SOCIAL' }}
                    </h5>
                    <p class="mb-1 text-uppercase" style="font-size: 9.5pt; font-weight: 600;">
                        {{ $acta->dependencia ?? 'DIRECCIÓN DE PLANIFICACIÓN' }}
                    </p>
                    <h4 class="font-weight-bold mb-0 text-uppercase" style="font-size: 14pt; letter-spacing: 0.5px;">
                        ACTA DE REUNIÓN Nº {{ $acta->numero_acta }}
                    </h4>
                </div>
                <div style="width: 80px; text-align: right;">
                    {{-- Espacio para código o control --}}
                </div>
            </div>
        </div>

        {{-- Tabla de Metadatos --}}
        <table class="table-mecip">
            <tbody>
                <tr>
                    <td style="width: 18%; font-weight: bold; background: #f2f2f2;">LUGAR:</td>
                    <td colspan="3">{{ $acta->lugar ?? 'REUNIÓN VIRTUAL' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background: #f2f2f2;">FECHA:</td>
                    <td style="width: 32%;">{{ $acta->fecha ? $acta->fecha->format('d/m/Y') : date('d/m/Y') }}</td>
                    <td style="width: 15%; font-weight: bold; background: #f2f2f2; text-align: center;">HORA:</td>
                    <td style="width: 35%;">Desde: {{ $acta->hora_desde ?? '15:00' }} &nbsp;&nbsp;&nbsp; Hasta: {{ $acta->hora_hasta ?? '16:00' }}</td>
                </tr>
                @if($acta->convocados_texto)
                <tr>
                    <td style="font-weight: bold; background: #f2f2f2; vertical-align: top;">CONVOCADOS:</td>
                    <td colspan="3" style="white-space: pre-wrap;">{{ $acta->convocados_texto }}</td>
                </tr>
                @endif
                @if($acta->temas_tratar)
                <tr>
                    <td style="font-weight: bold; background: #f2f2f2; vertical-align: top;">TEMAS A TRATAR:</td>
                    <td colspan="3" style="white-space: pre-wrap;">{{ $acta->temas_tratar }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- OBJETIVO --}}
        <div class="section-title">OBJETIVO:</div>
        <div class="content-paragraph">{{ $acta->objetivo }}</div>

        {{-- DESARROLLO --}}
        <div class="section-title">DESARROLLO (PUNTOS TRATADOS):</div>
        <div class="content-paragraph">{{ $acta->desarrollo }}</div>

        {{-- ACUERDOS --}}
        <div class="section-title">ACUERDOS:</div>
        <div class="content-paragraph">{{ $acta->acuerdos }}</div>

        {{-- COMPROMISOS --}}
        @php
            $compromisos = is_array($acta->compromisos) ? $acta->compromisos : (json_decode($acta->compromisos, true) ?? []);
        @endphp
        @if(count($compromisos) > 0)
            <div class="section-title">COMPROMISOS:</div>
            <table class="table-mecip">
                <thead>
                    <tr>
                        <th style="width: 35%;">RESPONSABLE</th>
                        <th style="width: 65%;">COMPROMISO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compromisos as $comp)
                        <tr>
                            <td style="font-weight: bold;">{{ $comp['responsable'] ?? '—' }}</td>
                            <td>{{ $comp['compromiso'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- TABLA DE PARTICIPANTES / FIRMANTES --}}
        <div class="section-title" style="margin-top: 20px;">PARTICIPANTES Y ASISTENCIA REGISTRADA:</div>
        <table class="table-mecip" style="margin-bottom: 25px;">
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
                @forelse($acta->participantes as $idx => $part)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 600;">{{ $part->nombre_completo }}</td>
                        <td>
                            <div>{{ $part->dependencia ?? '—' }}</div>
                            <small style="font-size: 8.5pt; color: #444;">{{ $part->cargo }}</small>
                        </td>
                        <td>
                            <div>{{ $part->correo ?? '—' }}</div>
                            <small style="font-size: 8.5pt; color: #444;">{{ $part->telefono }}</small>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            @if($part->firma)
                                <img src="{{ $part->firma }}" style="max-height: 40px; display: block; margin: 0 auto;">
                                <div style="font-size: 6.5pt; color: #166534; font-weight: bold; margin-top: 2px;">✔ FIRMA DIGITAL VÁLIDA</div>
                            @elseif($part->registrado_via_qr)
                                <span style="color: #166534; font-weight: bold; font-size: 8pt;">✔ Verificado Digital (QR)</span>
                            @else
                                <span style="color: #1e3a8a; font-weight: bold; font-size: 8pt;">✔ Registrado</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #666; font-style: italic;">
                            Sin participantes registrados en el acta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- FIRMA DEL MODERADOR / ENCARGADO --}}
        @if($acta->firma_moderador && $acta->estado === 'finalizada')
        <div style="margin-top: 40px; margin-bottom: 20px; text-align: center;">
            <div style="display: inline-block; text-align: center;">
                <img src="{{ $acta->firma_moderador }}" style="max-height: 90px; margin-bottom: 5px;">
                <div style="border-top: 1px solid #333; width: 250px; padding-top: 5px; margin: 0 auto; font-size: 9pt; font-weight: bold;">
                    MODERADOR / ENCARGADO
                </div>
                <div style="font-size: 7.5pt; color: #444; margin-top: 3px;">
                    Firmado digitalmente el {{ $acta->fecha_firma_moderador ? $acta->fecha_firma_moderador->format('d/m/Y H:i') : '' }}
                </div>
            </div>
        </div>
        @endif

        {{-- DECLARACIÓN DE INTEGRIDAD --}}
        @if($acta->hash_seguridad)
        <div style="margin-top: 20px; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 8pt; color: #334155; line-height: 1.5; text-align: justify;">
            <strong>DECLARACIÓN DE INTEGRIDAD CRIPTOGRÁFICA:</strong> Las firmas trazadas en este documento han sido capturadas digitalmente de forma consentida por los participantes y el moderador. Al finalizar la reunión, el sistema genera automáticamente un Hash criptográfico SHA-256 de seguridad, que sella el contenido del acta de forma inmutable. Cualquier alteración posterior del texto, participantes o rúbricas invalidará automáticamente el Código Verificador y el QR de validación en tiempo real.
        </div>
        @endif

        {{-- PIE DE PÁGINA CON CÓDIGO QR Y VALIDACIÓN --}}
        <div class="mt-4 pt-2 border-top d-flex justify-content-between align-items-center" style="border-top: 1px solid #ccc !important;">
            <div style="font-size: 8.5pt; color: #444; line-height: 1.4;">
                <strong>DOCUMENTO DIGITAL REGISTRADO EN EL SISTEMA PEI - IPS</strong><br>
                Generado el {{ date('d/m/Y H:i') }} hs.<br>
                Validez oficial según estándares de control interno MECIP:2015.<br>
                @if($acta->hash_seguridad)
                <div style="font-family: monospace; font-size: 7.5pt; background: #f8fafc; padding: 4px 6px; border: 1px solid #cbd5e1; display: inline-block; margin-top: 4px; font-weight: bold; color: #0f172a;">
                    CÓDIGO VERIFICADOR: {{ $acta->hash_seguridad }}
                </div>
                @endif
                <div style="font-size: 7.5pt; color: #666; margin-top: 4px;">Enlace de verificación: {{ $publicUrl }}</div>
            </div>
            <div class="text-center" style="width: 100px;">
                <div style="display: inline-block; padding: 2px; border: 1px solid #ccc; background: white;">
                    {!! $qrSvg !!}
                </div>
                <div style="font-size: 7pt; font-weight: bold; margin-top: 2px;">ESCANEAR QR</div>
            </div>
        </div>

    </div>

</body>
</html>
