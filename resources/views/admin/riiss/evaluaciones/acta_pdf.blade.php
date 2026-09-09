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
        <div class="main-title">ACTA DE CONSTANCIA Y CIERRE DE RELEVAMIENTO EN TERRENO</div>
        <div class="text-primary font-weight-bold" style="font-size: 9.5px;">POLÍTICA DE REDES INTEGRADAS E INTEGRALES DE SERVICIOS DE SALUD (RIISS)</div>
        <div class="text-muted font-weight-bold" style="font-size: 8.5px;">MÓDULO N° 1: CARTERA DE SERVICIOS DE SALUD Y CAPACIDAD RESOLUTIVA</div>
    </div>

    {{-- I. Datos del Establecimiento --}}
    <div class="section-heading">I. Identificación del Establecimiento Auditado</div>
    <table class="data-table">
        <tr>
            <th style="width: 22%;">Establecimiento:</th>
            <td style="width: 38%; font-weight: bold;">{{ $est->nombre_oficial }}</td>
            <th style="width: 18%;">Código / ID:</th>
            <td style="width: 22%;">{{ $est->id_establecimiento }}</td>
        </tr>
        <tr>
            <th>Tipología Oficial:</th>
            <td>{{ $est->tipologia_clasificacion }}</td>
            <th>Complejidad:</th>
            <td><strong class="text-primary">{{ $est->complejidad }}</strong></td>
        </tr>
        <tr>
            <th>Nivel / Grado:</th>
            <td>{{ $est->nivel_atencion }} / Grado {{ $est->grado_complejidad }}</td>
            <th>Departamento / Distrito:</th>
            <td>{{ $est->departamento }} {{ $est->distrito ? ' / ' . $est->distrito : '' }}</td>
        </tr>
        <tr>
            <th>Red / Microred:</th>
            <td>{{ $est->microred ?: ($est->red ?: 'Red Integrada IPS') }}</td>
            <th>Fecha de Cierre:</th>
            <td><strong class="text-primary">{{ $evaluacion->cerrado_at ? $evaluacion->cerrado_at->format('d/m/Y H:i') : ($evaluacion->responsable_firmado_at ? $evaluacion->responsable_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i')) }} hs</strong></td>
        </tr>
    </table>

    {{-- II. Declaración --}}
    <div class="section-heading">II. Declaración Institucional y Objeto de la Visita</div>
    <p class="text-justify" style="margin: 4px 0 8px 0;">
        En el marco de la implementación progresiva de la <strong>Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)</strong> —que comprende 9 módulos estratégicos de gobernanza sanitaria—, el equipo técnico comisionado por la <strong>Dirección de Planificación del IPS</strong> se constituyó formalmente en las instalaciones del establecimiento para la verificación técnica, auditoría in situ y consolidación de la oferta prestacional para el <strong>Módulo 1: Cartera de Servicios de Salud y Capacidad Resolutiva</strong>.
    </p>

    {{-- III. Resultados --}}
    <div class="section-heading">III. Resultados del Relevamiento y Dictamen Técnico</div>
    <table class="kpi-table">
        <tr>
            <td style="width: 33%; padding-right: 4px;">
                <div class="kpi-card">
                    <div class="text-muted font-weight-bold" style="font-size: 8px;">COBERTURA AUDITADA</div>
                    <div class="kpi-val text-primary">{{ $progreso }}%</div>
                    <div class="text-muted" style="font-size: 8px;">{{ $respondidas }} de {{ $totalPreguntas }} preguntas</div>
                </div>
            </td>
            <td style="width: 34%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="text-muted font-weight-bold" style="font-size: 8px;">CUMPLIMIENTO DE CARTERA</div>
                    <div class="kpi-val text-success">{{ $evaluacion->porcentaje_cumplimiento ?? $progreso }}%</div>
                    <div class="text-muted" style="font-size: 8px;">Requisitos según tipología</div>
                </div>
            </td>
            <td style="width: 33%; padding-left: 4px;">
                <div class="kpi-card">
                    <div class="text-muted font-weight-bold" style="font-size: 8px;">DICTAMEN TÉCNICO</div>
                    <div class="kpi-val {{ $clasificacion === 'CUMPLE' ? 'text-success' : ($clasificacion === 'CUMPLE_PARCIALMENTE' ? 'text-warning' : 'text-danger') }}" style="font-size: 11px;">
                        {{ str_replace('_', ' ', $clasificacion) }}
                    </div>
                    <div class="text-muted" style="font-size: 8px;">Veredicto de Cartera</div>
                </div>
            </td>
        </tr>
    </table>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 6px; border-radius: 4px; font-size: 9.5px; margin-bottom: 6px;">
        <strong>Veredicto:</strong> {{ $veredicto }}
    </div>

    @if($evaluacion->cierre_observaciones)
    <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-left: 3px solid #f59e0b; padding: 5px 8px; font-size: 9px; margin-bottom: 6px;">
        <strong>Observaciones y Acuerdos de Campo:</strong> {{ $evaluacion->cierre_observaciones }}
    </div>
    @endif

    {{-- IV. Rúbricas y Firmas --}}
    <div class="section-heading">IV. Constancia de Conformidad y Rúbricas Digitales</div>
    <p style="font-size: 8.5px; color: #475569; margin: 3px 0 6px 0;">
        Las partes intervinientes ratifican la veracidad de los datos relevados en terreno, rubricando en señal de plena conformidad:
    </p>

    <table class="signatures-table">
        <tr>
            <td class="signature-box" style="padding-right: 8px;">
                <div class="font-weight-bold text-uppercase" style="font-size: 8.5px; color: #92400e; border-bottom: 1px solid #fde68a; padding-bottom: 2px;">
                    POR EL ESTABLECIMIENTO AUDITADO (RECEPTOR LOCAL)
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
