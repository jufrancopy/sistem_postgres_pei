<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acta Consolidada de Validación — Área Interior ({{ $sesion->codigo_acceso }})</title>
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
            max-width: 880px;
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
        .btn-primary { background: #0284c7; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-secondary { background: #64748b; color: #fff; }

        .sheet {
            max-width: 880px;
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
            border-bottom: 2px solid #0284c7;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .title-box {
            background: #f8fafc;
            border-top: 2px solid #0284c7;
            border-bottom: 2px solid #0284c7;
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

        .text-success { color: #16a34a; }
        .text-danger { color: #dc2626; }
        .text-primary { color: #0284c7; }
        .text-muted { color: #64748b; }

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
            <a href="{{ route('riiss.portal-validador.show', $sesion->token) }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Portal
            </a>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('riiss.portal-validador.acta-pdf', $sesion->token) }}" class="btn btn-danger">
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
                        <div style="background:#0284c7; color:#fff; font-weight:bold; font-size:16px; padding:8px 16px; border-radius:6px;">
                            IPS
                        </div>
                    @endif
                </div>
                <div style="border-left: 2px solid #e2e8f0; padding-left: 12px;">
                    <div style="font-size:14px; font-weight:700; text-transform:uppercase; color:#0f172a; letter-spacing:0.5px;">{{ $institucion }}</div>
                    <div style="font-size:12px; font-weight:700; color:#0284c7; text-transform:uppercase;">{{ $dependencia }}</div>
                    <div style="font-size:10px; color:#64748b;">Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN)</div>
                </div>
            </div>
            <div style="text-align:right;">
                <span style="background:#0284c7; color:#fff; font-size:10px; font-weight:700; padding:4px 10px; border-radius:4px; text-transform:uppercase;">
                    ÁREA INTERIOR
                </span>
                <div style="font-size:11px; font-weight:700; color:#64748b; margin-top:4px;">CÓDIGO: {{ $sesion->codigo_acceso }}</div>
            </div>
        </div>

        {{-- Titulo Principal --}}
        <div class="title-box">
            <div style="font-size:11px; font-weight:700; color:#0284c7; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px;">
                POLÍTICA DE REDES INTEGRADAS E INTEGRALES DE SERVICIOS DE SALUD (RIISS)
            </div>
            <h3 style="margin:0 0 4px 0; font-size:15px; font-weight:700; text-transform:uppercase; color:#0f172a;">
                ACTA CONSOLIDADA DE RELEVAMIENTO Y VALIDACIÓN DE ESPECIALIDADES MÉDICAS
            </h3>
            <div style="font-size:11px; font-weight:700; color:#0284c7; text-transform:uppercase;">
                {{ $dependencia }}
            </div>
            <div style="font-size:10px; font-weight:600; color:#64748b;">
                RELEVAMIENTO DE CAPACIDAD RESOLUTIVA Y ASISTENCIAL (CATÁLOGO DE BIOESTADÍSTICA)
            </div>
        </div>

        {{-- I. Identificacion --}}
        <div class="section-title">I. Identificación del Validador Técnico Responsable</div>
        <table class="data-table">
            <tr>
                <th style="width:22%;">Analista / Responsable:</th>
                <td style="width:40%; font-weight:700; color:#0f172a;">{{ $sesion->analista_nombre }}</td>
                <th style="width:18%;">Código de Acceso:</th>
                <td style="width:20%; font-weight:700;" class="text-primary">{{ $sesion->codigo_acceso }}</td>
            </tr>
            <tr>
                <th>Cargo / Función:</th>
                <td>{{ $sesion->analista_cargo ?: 'Analista Técnico Área Interior' }}</td>
                <th>Documento C.I.:</th>
                <td>{{ $sesion->analista_documento ?: 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Alcance Territorial:</th>
                <td>{{ $sesion->departamento_filtro ?: 'Todos los Departamentos (Nacional - Área Interior)' }}</td>
                <th>Fecha de Emisión:</th>
                <td><strong class="text-primary">{{ date('d/m/Y H:i') }} hs</strong></td>
            </tr>
        </table>

        {{-- II. Resumen Cuantitativo --}}
        <div class="section-title">II. Resumen General del Relevamiento</div>
        <div class="kpi-row">
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">ESTABLECIMIENTOS</div>
                <div class="kpi-num text-primary">{{ $totalEstablecimientos }}</div>
                <div style="font-size:10px; color:#64748b;">Centros auditados</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">VALIDADAS ACTIVAS</div>
                <div class="kpi-num text-success">{{ $totalValidadas }}</div>
                <div style="font-size:10px; color:#64748b;">Confirmadas en centro</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">INACTIVADAS</div>
                <div class="kpi-num text-danger">{{ $totalInactivadas }}</div>
                <div style="font-size:10px; color:#64748b;">No operativas</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">ESTADO SESIÓN</div>
                <div class="kpi-num text-success" style="font-size:13px; margin-top:3px;">
                    {{ strtoupper($sesion->estado) }}
                </div>
                <div style="font-size:10px; color:#64748b;">Validador Área Interior</div>
            </div>
        </div>

        {{-- III. Detalle de Relevamiento por Establecimiento --}}
        <div class="section-title">III. Detalle de Especialidades Validadas por Establecimiento</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:5%; text-align:center;">#</th>
                    <th style="width:28%;">Establecimiento de Salud</th>
                    <th style="width:30%;">Especialidad (Bioestadística)</th>
                    <th style="width:12%; text-align:center;">Estado</th>
                    <th style="width:25%;">Observación / Justificación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $idx => $r)
                    <tr>
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $r->establecimiento?->nombre_oficial }}</strong>
                            <div class="text-muted" style="font-size:10px;">{{ $r->establecimiento?->departamento }}</div>
                        </td>
                        <td>
                            <strong>{{ $r->especialidad?->nombre }}</strong>
                            <span class="text-muted" style="font-size:10px;">(#{{ $r->especialidad_id }})</span>
                            @if($r->es_agregada)
                                <span style="color:#d97706; font-size:10px; font-weight:700;"> [Agregada]</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($r->estado === 'activa')
                                <span class="text-success font-weight-bold">✔ Activa</span>
                            @else
                                <span class="text-danger font-weight-bold">✖ Inactiva</span>
                            @endif
                        </td>
                        <td style="font-size:11px;">
                            {{ $r->justificacion ?: '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;" class="text-muted font-italic">No se registran validaciones efectuadas aún bajo esta sesión.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- IV. Firma Digital --}}
        @if($sesion->firma_digital)
            <div class="section-title">IV. Constancia y Firma Digital del Validador</div>
            <div style="display:flex; justify-content:center; margin-top:15px;">
                <div style="width:55%; border:1px solid #cbd5e1; background:#f8fafc; padding:12px; border-radius:8px; text-align:center;">
                    <div style="font-size:10px; font-weight:700; color:#0284c7; text-transform:uppercase; border-bottom:1px solid #cbd5e1; padding-bottom:4px;">
                        POR LA DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR — IPS
                    </div>
                    <div style="height:80px; display:flex; align-items:center; justify-content:center; padding:4px 0;">
                        <img src="{{ $sesion->firma_digital }}" style="max-height:75px; max-width:90%; object-fit:contain;" alt="Firma del Validador">
                    </div>
                    <div style="border-top:1px solid #cbd5e1; padding-top:6px; font-size:11px;">
                        <div style="font-weight:700; color:#0f172a;">{{ $sesion->analista_nombre }}</div>
                        <div style="color:#0284c7; font-weight:600; font-size:10.5px;">{{ $sesion->analista_cargo }}</div>
                        <div style="color:#94a3b8; font-size:9.5px; font-style:italic; margin-top:2px;">
                            Sellado: {{ $sesion->firmado_at ? $sesion->firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }} hs
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Footer --}}
        <div style="margin-top:25px; border-top:1px solid #e2e8f0; padding-top:10px; font-size:10.5px; color:#64748b; text-align:center; line-height:1.5;">
            <div style="font-weight:600; color:#475569;">© {{ date('Y') }} Instituto de Previsión Social (IPS) — Dirección de Hospitales del Área Interior / Dirección de Planificación.</div>
            <div style="margin-top:3px; font-size:9px; color:#94a3b8; font-style:italic;">
                Documento oficial generado a través del Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN).
            </div>
        </div>
    </div>

</body>
</html>
