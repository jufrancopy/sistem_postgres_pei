<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Acta de Cierre RIISS - {{ $est->nombre_oficial }}</title>
    <style>
        @page {
            margin: 25px 30px 30px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.35;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-justify { text-align: justify; }
        .font-weight-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        .text-primary { color: #1a237e; }
        .text-muted { color: #64748b; }
        .text-success { color: #16a34a; }
        .text-warning { color: #d97706; }
        .text-danger { color: #dc2626; }

        /* Membrete */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1a237e;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
        }
        .header-subtitle {
            font-size: 10px;
            font-weight: bold;
            color: #1a237e;
        }
        .badge-tag {
            background-color: #1a237e;
            color: #ffffff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        /* Banner Titulo */
        .title-box {
            background-color: #f8fafc;
            border-top: 1.5px solid #1a237e;
            border-bottom: 1.5px solid #1a237e;
            padding: 8px;
            margin-bottom: 14px;
            text-align: center;
        }
        .main-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 2px 0;
        }

        /* Secciones */
        .section-heading {
            font-size: 10.5px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin: 12px 0 6px 0;
            text-transform: uppercase;
        }

        /* Tablas */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
        }

        /* Resumen Kpis */
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        .kpi-card {
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 6px;
            text-align: center;
            border-radius: 4px;
        }
        .kpi-val {
            font-size: 14px;
            font-weight: bold;
        }

        /* Firmas */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .signature-box {
            border: 1px solid #94a3b8;
            background-color: #ffffff;
            padding: 8px;
            text-align: center;
            vertical-align: top;
            width: 50%;
        }
        .signature-img {
            max-height: 70px;
            max-width: 90%;
            margin: 4px auto;
            display: block;
        }

        /* Footer */
        .footer-note {
            margin-top: 15px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 8.5px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Membrete --}}
    <table class="header-table">
        <tr>
            <td style="width: 22%; vertical-align: middle;">
                @if(!empty($logoInstitucional))
                    <img src="{{ $logoInstitucional }}" style="max-height: 52px; max-width: 125px; object-fit: contain;">
                @else
                    <div style="background-color: #1a237e; color: #ffffff; font-weight: bold; font-size: 11px; padding: 6px 10px; text-align: center; border-radius: 4px; display: inline-block;">
                        {{ mb_strtoupper(substr($institucion ?? 'IPS', 0, 8)) }}
                    </div>
                @endif
            </td>
            <td style="width: 53%; vertical-align: middle; padding-left: 8px;">
                <div class="header-title">{{ $institucion ?? 'INSTITUTO DE PREVISIÓN SOCIAL' }}</div>
                <div class="header-subtitle">{{ $dependencia ?? 'DIRECCIÓN DE PLANIFICACIÓN' }}</div>
                <div class="text-muted" style="font-size: 8.5px;">Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN)</div>
            </td>
            <td style="width: 25%; text-align: right; vertical-align: middle;">
                <span class="badge-tag">POLÍTICA RIISS (1 de 9)</span>
                <div class="font-weight-bold text-muted" style="font-size: 9px; margin-top: 3px;">ACTA-RIISS-#{{ $evaluacion->id }}</div>
            </td>
        </tr>
    </table>

    {{-- Título Principal --}}
    <div class="title-box">
        <div class="main-title">ACTA DE CONSTANCIA DE VISITA Y RELEVAMIENTO TÉCNICO EN TERRENO</div>
        <div class="text-primary font-weight-bold" style="font-size: 9.5px;">POLÍTICA DE REDES INTEGRADAS E INTEGRALES DE SERVICIOS DE SALUD (RIISS)</div>
        <div class="text-muted font-weight-bold" style="font-size: 8.5px;">MÓDULO N° 1: RELEVAMIENTO DE CARTERA DE SERVICIOS Y CAPACIDAD RESOLUTIVA</div>
    </div>

    {{-- I. Datos del Establecimiento --}}
    <div class="section-heading">I. Identificación del Establecimiento y de la Visita</div>
    <table class="data-table">
        <tr>
            <th style="width: 22%;">Establecimiento:</th>
            <td style="width: 38%; font-weight: bold;">{{ $est->nombre_oficial }}</td>
            <th style="width: 18%;">Código / ID:</th>
            <td style="width: 22%;">{{ $est->id_establecimiento }}</td>
        </tr>
        <tr>
            <th>Marco / Plan PEI:</th>
            <td colspan="3"><strong class="text-primary">{{ $peiNombre }}</strong></td>
        </tr>
        <tr>
            <th>Equipo Relevador:</th>
            <td colspan="3"><strong>{{ $evaluadoresTexto }}</strong></td>
        </tr>
        <tr>
            <th>Tipología / Nivel:</th>
            <td>{{ $est->tipologia_clasificacion }} ({{ $est->nivel_atencion }} / Grado {{ $est->grado_complejidad }})</td>
            <th>Complejidad:</th>
            <td><strong class="text-primary">{{ $est->complejidad }}</strong></td>
        </tr>
        <tr>
            <th>Departamento / Distrito:</th>
            <td>{{ $est->departamento }} {{ $est->distrito ? ' / ' . $est->distrito : '' }}</td>
            <th>Fecha de Cierre:</th>
            <td><strong class="text-primary">{{ $evaluacion->cerrado_at ? $evaluacion->cerrado_at->format('d/m/Y H:i') : ($evaluacion->responsable_firmado_at ? $evaluacion->responsable_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i')) }} hs</strong></td>
        </tr>
    </table>

    {{-- II. Declaración --}}
    <div class="section-heading">II. Recepción y Constancia de la Visita Técnica</div>
    <p class="text-justify" style="margin: 4px 0 8px 0;">
        En el marco de la ejecución del <strong>{{ $peiNombre }}</strong> y la implementación técnica de la <strong>Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)</strong>, en la fecha y hora indicadas, la autoridad o responsable del establecimiento de salud recibió formalmente a la comisión técnica integrada por los profesionales comisionados: <strong>{{ $evaluadoresTexto }}</strong>, pertenecientes a la <strong>Dirección de Planificación del Instituto de Previsión Social (IPS)</strong>. Ambas partes procedieron de manera conjunta al recorrido de las instalaciones, la verificación in situ de los servicios en funcionamiento y el relevamiento de información para el <strong>Módulo 1: Cartera de Servicios de Salud y Capacidad Resolutiva</strong>.
    </p>

    {{-- III. Alcance del Relevamiento --}}
    <div class="section-heading">III. Alcance del Relevamiento de Campo</div>
    <table class="kpi-table">
        <tr>
            <td style="width: 33%; padding-right: 4px;">
                <div class="kpi-card">
                    <div class="text-muted font-weight-bold" style="font-size: 8px;">ÍTEMS AUDITADOS</div>
                    <div class="kpi-val text-primary">{{ $respondidas }} de {{ $totalPreguntas }}</div>
                    <div class="text-muted" style="font-size: 8px;">Preguntas técnicas verificadas</div>
                </div>
            </td>
            <td style="width: 34%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="text-muted font-weight-bold" style="font-size: 8px;">COBERTURA DEL RELEVAMIENTO</div>
                    <div class="kpi-val text-primary">{{ $progreso }}%</div>
                    <div class="text-muted" style="font-size: 8px;">Cuestionario completado</div>
                </div>
            </td>
            <td style="width: 33%; padding-left: 4px;">
                <div class="kpi-card">
                    <div class="text-muted font-weight-bold" style="font-size: 8px;">ESTADO DE LA VISITA</div>
                    <div class="kpi-val text-success" style="font-size: 11px;">
                        RELEVAMIENTO CONCLUIDO
                    </div>
                    <div class="text-muted" style="font-size: 8px;">Jornada presencial en terreno</div>
                </div>
            </td>
        </tr>
    </table>

    @if($evaluacion->cierre_observaciones)
    <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-left: 3px solid #1a237e; padding: 5px 8px; font-size: 9px; margin-bottom: 6px;">
        <strong>Observaciones y Acuerdos de la Jornada de Campo:</strong> {{ $evaluacion->cierre_observaciones }}
    </div>
    @endif

    {{-- IV. Rúbricas y Firmas --}}
    <div class="section-heading">IV. Constancia de Conformidad y Rúbricas Digitales de la Visita</div>
    <p style="font-size: 8.5px; color: #475569; margin: 3px 0 6px 0;">
        Las partes intervinientes ratifican la realización efectiva de la visita técnica presencial y la recepción conforme del equipo comisionado, rubricando al pie en señal de constancia y validación de la jornada de relevamiento de campo:
    </p>

    <table class="signatures-table">
        <tr>
            <td class="signature-box" style="padding-right: 8px;">
                <div class="font-weight-bold text-uppercase" style="font-size: 8.5px; color: #92400e; border-bottom: 1px solid #fde68a; padding-bottom: 2px;">
                    POR EL ESTABLECIMIENTO (RECEPCIÓN Y CONFORMIDAD)
                </div>
                <div style="height: 75px; text-align: center; padding: 4px 0;">
                    @if($evaluacion->responsable_firma)
                        <img src="{{ $evaluacion->responsable_firma }}" class="signature-img" alt="Firma Responsable">
                    @else
                        <div class="text-muted" style="padding-top: 25px; font-style: italic;">Sin firma estampada</div>
                    @endif
                </div>
                <div style="border-top: 1px solid #cbd5e1; padding-top: 4px; font-size: 9px;">
                    <div class="font-weight-bold">{{ $evaluacion->responsable_nombre ?: 'Responsable del Establecimiento' }}</div>
                    <div class="text-primary font-weight-bold" style="font-size: 8.5px;">{{ $evaluacion->responsable_cargo ?: 'Receptor Local' }}</div>
                    @if($evaluacion->responsable_documento)
                        <div class="text-muted" style="font-size: 8px;">C.I.: {{ $evaluacion->responsable_documento }}</div>
                    @endif
                    @if($evaluacion->responsable_telefono)
                        <div class="text-muted" style="font-size: 8px;">Tel: {{ $evaluacion->responsable_telefono }}</div>
                    @endif
                    <div class="text-muted" style="font-size: 7.5px; font-style: italic; margin-top: 2px;">
                        Firmado: {{ $evaluacion->responsable_firmado_at ? $evaluacion->responsable_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}
                    </div>
                </div>
            </td>
            <td class="signature-box" style="padding-left: 8px;">
                <div class="font-weight-bold text-uppercase" style="font-size: 8.5px; color: #1e40af; border-bottom: 1px solid #bfdbfe; padding-bottom: 2px;">
                    POR LA DIRECCIÓN DE PLANIFICACIÓN — IPS
                </div>
                <div style="height: 75px; text-align: center; padding: 4px 0;">
                    @if($evaluadorFirma)
                        <img src="{{ $evaluadorFirma }}" class="signature-img" alt="Firma Evaluador">
                    @else
                        <div class="text-muted" style="padding-top: 25px; font-style: italic;">Sin firma estampada</div>
                    @endif
                </div>
                <div style="border-top: 1px solid #cbd5e1; padding-top: 4px; font-size: 9px;">
                    <div class="font-weight-bold">{{ $evaluadorNombre }}</div>
                    <div class="text-primary font-weight-bold" style="font-size: 8.5px;">{{ $evaluadorCargo }}</div>
                    @if($evaluacion->cerradoPor)
                        <div class="text-muted" style="font-size: 8px;">Usuario: {{ $evaluacion->cerradoPor->name }}</div>
                    @endif
                    <div class="text-muted" style="font-size: 7.5px; font-style: italic; margin-top: 2px;">
                        Cerrado: {{ $evaluacion->cerrado_at ? $evaluacion->cerrado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="footer-note">
        <div style="font-weight: bold; color: #475569;">{{ $footerText ?? '© ' . date('Y') . ' Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.' }}</div>
        @if(!empty($address) || !empty($contactPhone) || !empty($contactEmail))
            <div style="margin-top: 2px; font-size: 8px; color: #64748b;">
                @if(!empty($address)) {{ $address }} @endif
                @if(!empty($contactPhone)) · Tel: {{ $contactPhone }} @endif
                @if(!empty($contactEmail)) · {{ $contactEmail }} @endif
            </div>
        @endif
        <div style="margin-top: 2px; font-size: 7.5px; color: #94a3b8; font-style: italic;">
            Marco Técnico y Legal de la Política de Redes Integradas e Integrales de Servicios de Salud (RIISS) — Módulo N° 1.
        </div>
    </div>

</body>
</html>
