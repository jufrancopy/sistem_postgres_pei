<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acta Oficial RIISS - {{ $est->nombre_oficial }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #1e293b;
            line-height: 1.4;
            font-size: 13px;
        }
        .toolbar {
            max-width: 860px;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }
        .btn-primary { background: #1a237e; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-secondary { background: #64748b; color: #fff; }

        .sheet {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 45px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #1a237e;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .title-box {
            background: #f8fafc;
            border-top: 2px solid #1a237e;
            border-bottom: 2px solid #1a237e;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 4px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin: 18px 0 8px 0;
            letter-spacing: 0.3px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 11.5px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
        }
        table.data-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-align: left;
        }

        .kpi-row {
            display: flex;
            gap: 12px;
            margin: 12px 0;
        }
        .kpi-col {
            flex: 1;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
        }
        .kpi-num {
            font-size: 18px;
            font-weight: 700;
        }

        .sig-row {
            display: flex;
            gap: 16px;
            margin-top: 15px;
        }
        .sig-card {
            flex: 1;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }
        .sig-img {
            max-height: 80px;
            max-width: 90%;
            margin: 8px auto;
            display: block;
            object-fit: contain;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .toolbar { display: none !important; }
            .sheet {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <div>
            <span class="btn btn-secondary" onclick="window.close()"><i class="fa fa-arrow-left mr-1"></i> Volver</span>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('riiss.evaluaciones.acta-pdf', $evaluacion->id) }}" class="btn btn-danger">
                <i class="fa fa-file-pdf mr-1"></i> Descargar PDF Oficial
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fa fa-print mr-1"></i> Imprimir Ahora
            </button>
        </div>
    </div>

    <div class="sheet">
        {{-- Membrete --}}
        <div class="header-box">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="height:55px; width:130px; display:flex; align-items:center; justify-content:center;">
                    @if(!empty($logoInstitucional))
                        <img src="{{ $logoInstitucional }}" alt="Logo Institución" style="max-height:55px; max-width:130px; object-fit:contain;">
                    @else
                        <div style="background:#f1f5f9; padding:8px 12px; border-radius:6px; font-size:24px; color:#1a237e;">
                            <i class="fa fa-hospital-alt"></i>
                        </div>
                    @endif
                </div>
                <div style="border-left: 2px solid #e2e8f0; padding-left: 12px;">
                    <div style="font-size:14px; font-weight:700; text-transform:uppercase; color:#0f172a; letter-spacing:0.5px;">{{ $institucion ?? 'INSTITUTO DE PREVISIÓN SOCIAL' }}</div>
                    <div style="font-size:12px; font-weight:700; color:#1a237e; text-transform:uppercase;">{{ $dependencia ?? 'DIRECCIÓN DE PLANIFICACIÓN' }}</div>
                    <div style="font-size:10px; color:#64748b;">Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN)</div>
                </div>
            </div>
            <div style="text-align:right;">
                <span style="background:#1a237e; color:#fff; font-size:10px; font-weight:700; padding:4px 10px; border-radius:4px; text-transform:uppercase;">
                    POLÍTICA RIISS (1 de 9)
                </span>
                <div style="font-size:11px; font-weight:700; color:#64748b; margin-top:4px;">ACTA-RIISS-#{{ $evaluacion->id }}</div>
            </div>
        </div>

        {{-- Titulo Principal --}}
        <div class="title-box">
            <h3 style="margin:0 0 4px 0; font-size:15px; font-weight:700; text-transform:uppercase; color:#0f172a;">
                ACTA DE CONSTANCIA DE VISITA Y RELEVAMIENTO TÉCNICO EN TERRENO
            </h3>
            <div style="font-size:11px; font-weight:700; color:#1a237e; text-transform:uppercase;">
                POLÍTICA DE REDES INTEGRADAS E INTEGRALES DE SERVICIOS DE SALUD (RIISS)
            </div>
            <div style="font-size:10px; font-weight:600; color:#64748b;">
                MÓDULO N° 1: RELEVAMIENTO DE CARTERA DE SERVICIOS Y CAPACIDAD RESOLUTIVA
            </div>
        </div>

        {{-- I. Identificacion --}}
        <div class="section-title">I. Identificación del Establecimiento y de la Visita</div>
        <table class="data-table">
            <tr>
                <th style="width:22%;">Establecimiento:</th>
                <td style="width:38%; font-weight:700; color:#0f172a;">{{ $est->nombre_oficial }}</td>
                <th style="width:18%;">Código / ID:</th>
                <td style="width:22%;">{{ $est->id_establecimiento }}</td>
            </tr>
            <tr>
                <th>Tipología Declarada:</th>
                <td>{{ $est->tipologia_clasificacion }}</td>
                <th>Complejidad:</th>
                <td><strong style="color:#1a237e;">{{ $est->complejidad }}</strong></td>
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
                <th>Fecha y Hora de Cierre:</th>
                <td><strong style="color:#1a237e;">{{ $evaluacion->cerrado_at ? $evaluacion->cerrado_at->format('d/m/Y H:i') : ($evaluacion->responsable_firmado_at ? $evaluacion->responsable_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i')) }} hs</strong></td>
            </tr>
        </table>

        {{-- II. Declaracion --}}
        <div class="section-title">II. Recepción y Constancia de la Visita Técnica</div>
        <p style="text-align:justify; margin:6px 0 12px 0; font-size:12px; line-height:1.55; color:#334155;">
            En la fecha y hora indicadas, la autoridad o responsable del establecimiento de salud recibió formalmente a los profesionales comisionados por la <strong>Dirección de Planificación del Instituto de Previsión Social (IPS)</strong>, en el marco de la implementación técnica de la <strong>Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)</strong>. Ambas partes procedieron de manera conjunta al recorrido de las instalaciones, la verificación in situ de los servicios en funcionamiento y el levantamiento exhaustivo de información para el <strong>Módulo 1: Cartera de Servicios de Salud y Capacidad Resolutiva</strong>.
        </p>

        {{-- III. Alcance del Relevamiento --}}
        <div class="section-title">III. Alcance del Relevamiento de Campo</div>
        <div class="kpi-row">
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">Ítems Auditados</div>
                <div class="kpi-num" style="color:#1a237e;">{{ $respondidas }} de {{ $totalPreguntas }}</div>
                <div style="font-size:10px; color:#64748b;">Preguntas técnicas verificadas</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">Cobertura del Relevamiento</div>
                <div class="kpi-num" style="color:#1a237e;">{{ $progreso }}%</div>
                <div style="font-size:10px; color:#64748b;">Cuestionario completado</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">Estado de la Visita</div>
                <div class="kpi-num" style="font-size:14px; margin-top:3px; color:#16a34a;">
                    RELEVAMIENTO CONCLUIDO
                </div>
                <div style="font-size:10px; color:#64748b;">Jornada presencial en terreno</div>
            </div>
        </div>

        @if($evaluacion->cierre_observaciones)
        <div style="background:#f8fafc; border:1px solid #cbd5e1; border-left:4px solid #1a237e; padding:8px 12px; font-size:11.5px; margin-bottom:8px;">
            <strong>Observaciones y Acuerdos Asentados en Terreno:</strong> {{ $evaluacion->cierre_observaciones }}
        </div>
        @endif

        {{-- IV. Rubricas --}}
        <div class="section-title">IV. Constancia de Conformidad y Rúbricas Digitales de la Visita</div>
        <p style="font-size:11px; color:#64748b; margin:4px 0 8px 0;">
            Las partes intervinientes ratifican la realización efectiva de la visita técnica presencial y la recepción conforme del equipo comisionado, rubricando al pie en señal de constancia y validación de la jornada de relevamiento de campo:
        </p>

        <div class="sig-row">
            {{-- Receptor Local --}}
            <div class="sig-card">
                <div style="font-size:10px; font-weight:700; color:#92400e; text-transform:uppercase; border-bottom:1px solid #cbd5e1; padding-bottom:4px;">
                    POR EL ESTABLECIMIENTO (RECEPCIÓN Y CONFORMIDAD)
                </div>
                <div style="height:85px; display:flex; align-items:center; justify-content:center; padding:4px 0;">
                    @if($evaluacion->responsable_firma)
                        <img src="{{ $evaluacion->responsable_firma }}" class="sig-img" alt="Firma del Responsable">
                    @else
                        <span style="color:#94a3b8; font-style:italic; font-size:11px;">Sin firma estampada</span>
                    @endif
                </div>
                <div style="border-top:1px solid #cbd5e1; padding-top:6px; font-size:11px;">
                    <div style="font-weight:700; color:#0f172a;">{{ $evaluacion->responsable_nombre ?: 'Responsable del Establecimiento' }}</div>
                    <div style="color:#1a237e; font-weight:600; font-size:10.5px;">{{ $evaluacion->responsable_cargo ?: 'Receptor Local' }}</div>
                    @if($evaluacion->responsable_documento)
                        <div style="color:#64748b; font-size:10px;">C.I.: {{ $evaluacion->responsable_documento }}</div>
                    @endif
                    @if($evaluacion->responsable_telefono)
                        <div style="color:#64748b; font-size:10px;">Tel: {{ $evaluacion->responsable_telefono }}</div>
                    @endif
                    <div style="color:#94a3b8; font-size:9.5px; font-style:italic; margin-top:2px;">
                        Firmado: {{ $evaluacion->responsable_firmado_at ? $evaluacion->responsable_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            {{-- Evaluador IPS --}}
            <div class="sig-card">
                <div style="font-size:10px; font-weight:700; color:#1e40af; text-transform:uppercase; border-bottom:1px solid #cbd5e1; padding-bottom:4px;">
                    POR LA DIRECCIÓN DE PLANIFICACIÓN — IPS
                </div>
                <div style="height:85px; display:flex; align-items:center; justify-content:center; padding:4px 0;">
                    @if($evaluadorFirma)
                        <img src="{{ $evaluadorFirma }}" class="sig-img" alt="Firma del Evaluador">
                    @else
                        <span style="color:#94a3b8; font-style:italic; font-size:11px;">Sin firma estampada</span>
                    @endif
                </div>
                <div style="border-top:1px solid #cbd5e1; padding-top:6px; font-size:11px;">
                    <div style="font-weight:700; color:#0f172a;">{{ $evaluadorNombre }}</div>
                    <div style="color:#1a237e; font-weight:600; font-size:10.5px;">{{ $evaluadorCargo }}</div>
                    @if($evaluacion->cerradoPor)
                        <div style="color:#64748b; font-size:10px;">Usuario: {{ $evaluacion->cerradoPor->name }}</div>
                    @endif
                    <div style="color:#94a3b8; font-size:9.5px; font-style:italic; margin-top:2px;">
                        Cerrado: {{ $evaluacion->cerrado_at ? $evaluacion->cerrado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="margin-top:25px; border-top:1px solid #e2e8f0; padding-top:10px; font-size:10.5px; color:#64748b; text-align:center; line-height:1.5;">
            <div style="font-weight:600; color:#475569;">{{ $footerText ?? '© ' . date('Y') . ' Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.' }}</div>
            @if(!empty($address) || !empty($contactPhone) || !empty($contactEmail))
                <div style="margin-top:3px; font-size:9.5px; color:#64748b;">
                    @if(!empty($address)) {{ $address }} @endif
                    @if(!empty($contactPhone)) · Tel: {{ $contactPhone }} @endif
                    @if(!empty($contactEmail)) · {{ $contactEmail }} @endif
                </div>
            @endif
            <div style="margin-top:3px; font-size:9px; color:#94a3b8; font-style:italic;">
                Marco Técnico y Legal de la Política de Redes Integradas e Integrales de Servicios de Salud (RIISS) — Módulo N° 1.
            </div>
        </div>
    </div>

</body>
</html>
