<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Relevamiento de Especialidades Médicas — Área Interior</title>
    <style>
        @page {
            margin: 15mm 15mm 18mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 6px;
        }
        .header-title {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 8.5px;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
        }
        .main-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 8px 0 2px 0;
        }
        .sub-title {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .section-heading {
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 3px 6px;
            border-left: 3px solid #0284c7;
            margin: 8px 0 5px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 8.5px;
        }
        .data-table th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #334155;
            text-align: left;
        }
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .kpi-card {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 6px;
            text-align: center;
            border-radius: 4px;
        }
        .kpi-val {
            font-size: 13px;
            font-weight: bold;
            margin: 2px 0;
        }
        .signature-box {
            width: 50%;
            margin: 0 auto;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            padding: 8px;
            text-align: center;
            border-radius: 4px;
        }
        .signature-img {
            max-height: 60px;
            max-width: 180px;
            object-fit: contain;
        }
        .footer-note {
            margin-top: 15px;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
            font-size: 7.5px;
            color: #64748b;
            text-align: center;
        }
        .text-success { color: #16a34a; }
        .text-danger { color: #dc2626; }
        .text-primary { color: #0284c7; }
        .text-muted { color: #64748b; }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 20%; vertical-align: middle;">
                @if(!empty($logoInstitucional))
                    <img src="{{ $logoInstitucional }}" style="max-height: 45px; max-width: 110px; object-fit: contain;">
                @else
                    <div style="background-color: #0284c7; color: #ffffff; font-weight: bold; font-size: 11px; padding: 5px 8px; text-align: center; border-radius: 4px; display: inline-block;">
                        IPS
                    </div>
                @endif
            </td>
            <td style="width: 60%; vertical-align: middle; padding-left: 8px;">
                <div class="header-title">{{ $institucion }}</div>
                <div class="header-subtitle">{{ $dependencia }}</div>
                <div class="text-muted" style="font-size: 8px;">Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN)</div>
            </td>
            <td style="width: 20%; text-align: right; vertical-align: middle;">
                <span style="background:#0284c7; color:#fff; font-size:8px; font-weight:bold; padding:2px 5px; border-radius:3px;">ÁREA INTERIOR</span>
                <div class="font-weight-bold text-muted" style="font-size: 8px; margin-top: 2px;">CÓDIGO: {{ $sesion->codigo_acceso }}</div>
            </td>
        </tr>
    </table>

    <div style="text-align: center; font-size: 8px; font-weight: bold; color: #0284c7; text-transform: uppercase; margin-bottom: 2px;">
        POLÍTICA DE REDES INTEGRADAS E INTEGRALES DE SERVICIOS DE SALUD (RIISS)
    </div>
    <div class="main-title">ACTA CONSOLIDADA DE RELEVAMIENTO Y VALIDACIÓN DE ESPECIALIDADES MÉDICAS</div>
    <div class="sub-title">{{ $dependencia }}</div>

    {{-- I. Identificación del Validador --}}
    <div class="section-heading">I. Identificación del Validador Técnico Responsable</div>
    <table class="data-table">
        <tr>
            <th style="width: 22%;">Analista / Responsable:</th>
            <td style="width: 40%; font-weight: bold;">{{ $sesion->analista_nombre }}</td>
            <th style="width: 18%;">Código de Acceso:</th>
            <td style="width: 20%; font-weight: bold;" class="text-primary">{{ $sesion->codigo_acceso }}</td>
        </tr>
        <tr>
            <th>Cargo / Función:</th>
            <td>{{ $sesion->analista_cargo ?: 'Analista Técnico de Hospitales Área Interior' }}</td>
            <th>Documento C.I.:</th>
            <td>{{ $sesion->analista_documento ?: 'No registrado' }}</td>
        </tr>
        <tr>
            <th>Alcance Territorial:</th>
            <td>{{ $sesion->departamento_filtro ?: 'Todos los Departamentos (Nacional - Área Interior)' }}</td>
            <th>Fecha de Emisión:</th>
            <td>{{ date('d/m/Y H:i') }} hs</td>
        </tr>
    </table>

    {{-- II. Resumen Cuantitativo --}}
    <div class="section-heading">II. Resumen General del Relevamiento</div>
    <table class="kpi-table">
        <tr>
            <td style="width: 25%; padding-right: 3px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7px; font-weight:bold;">ESTABLECIMIENTOS</div>
                    <div class="kpi-val text-primary">{{ $totalEstablecimientos }}</div>
                    <div class="text-muted" style="font-size: 7px;">Centros auditados</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7px; font-weight:bold;">VALIDADAS ACTIVAS</div>
                    <div class="kpi-val text-success">{{ $totalValidadas }}</div>
                    <div class="text-muted" style="font-size: 7px;">Confirmadas en centro</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7px; font-weight:bold;">INACTIVADAS</div>
                    <div class="kpi-val text-danger">{{ $totalInactivadas }}</div>
                    <div class="text-muted" style="font-size: 7px;">No operativas</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7px; font-weight:bold;">ESTADO SESIÓN</div>
                    <div class="kpi-val text-success" style="font-size: 9.5px;">{{ strtoupper($sesion->estado) }}</div>
                    <div class="text-muted" style="font-size: 7px;">Validador Área Interior</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- III. Detalle de Relevamiento por Establecimiento --}}
    <div class="section-heading">III. Detalle de Especialidades Validadas por Establecimiento</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align:center;">#</th>
                <th style="width: 30%;">Establecimiento de Salud</th>
                <th style="width: 32%;">Especialidad (Bioestadística)</th>
                <th style="width: 13%; text-align:center;">Estado</th>
                <th style="width: 20%;">Observación / Justificación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registros as $idx => $r)
                <tr>
                    <td style="text-align:center;">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $r->establecimiento?->nombre_oficial }}</strong>
                        <div class="text-muted" style="font-size: 7.5px;">{{ $r->establecimiento?->departamento }}</div>
                    </td>
                    <td>
                        <strong>{{ $r->especialidad?->nombre }}</strong>
                        <span class="text-muted" style="font-size: 7.5px;">(#{{ $r->especialidad_id }})</span>
                        @if($r->es_agregada)
                            <span style="color:#d97706; font-size:7px; font-weight:bold;"> [Agregada]</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($r->estado === 'activa')
                            <span class="text-success" style="font-weight:bold;">✔ Activa</span>
                        @else
                            <span class="text-danger" style="font-weight:bold;">✖ Inactiva</span>
                        @endif
                    </td>
                    <td style="font-size: 8px;">
                        {{ $r->justificacion ?: '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;" class="text-muted">No se registran validaciones efectuadas aún bajo esta sesión.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($sesion->notas)
        <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-left: 3px solid #0284c7; padding: 4px 6px; font-size: 8px; margin-bottom: 8px;">
            <strong>Notas del Relevamiento:</strong> {!! $sesion->notas !!}
        </div>
    @endif

    {{-- IV. Firma Digital --}}
    @if($sesion->firma_digital)
        <div class="section-heading">IV. Constancia y Firma Digital del Validador</div>
        <table style="width:100%; margin-top: 4px;">
            <tr>
                <td class="signature-box">
                    <div class="text-primary font-weight-bold" style="font-size: 8px; text-transform:uppercase; border-bottom: 1px solid #cbd5e1; padding-bottom: 2px;">
                        POR LA DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR — IPS
                    </div>
                    <div style="height: 60px; text-align: center; padding: 2px 0;">
                        <img src="{{ $sesion->firma_digital }}" class="signature-img" alt="Firma del Validador">
                    </div>
                    <div style="border-top: 1px solid #cbd5e1; padding-top: 3px; font-size: 8px;">
                        <div style="font-weight: bold;">{{ $sesion->analista_nombre }}</div>
                        <div class="text-primary font-weight-bold" style="font-size: 7.5px;">{{ $sesion->analista_cargo }}</div>
                        <div class="text-muted" style="font-size: 7px; font-style: italic;">
                            Sellado: {{ $sesion->firmado_at ? $sesion->firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }} hs
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    @endif

    {{-- Footer --}}
    <div class="footer-note">
        <div>© {{ date('Y') }} Instituto de Previsión Social (IPS) — Dirección de Hospitales del Área Interior / Dirección de Planificación.</div>
        <div style="margin-top: 1px; font-style: italic;">Documento oficial generado a través del Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN).</div>
    </div>

</body>
</html>
