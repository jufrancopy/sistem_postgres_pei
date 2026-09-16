<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Validación de Especialidades Médicas — {{ $est->nombre_oficial }}</title>
    <style>
        @page {
            margin: 15mm 15mm 18mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
        }
        .header-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 9px;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
        }
        .main-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 10px 0 3px 0;
        }
        .sub-title {
            text-align: center;
            font-size: 9.5px;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .section-heading {
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 8px;
            border-left: 3px solid #0284c7;
            margin: 10px 0 6px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 9px;
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
            font-size: 14px;
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
            max-height: 65px;
            max-width: 180px;
            object-fit: contain;
        }
        .footer-note {
            margin-top: 15px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
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
            <td style="width: 22%; vertical-align: middle;">
                @if(!empty($logoInstitucional))
                    <img src="{{ $logoInstitucional }}" style="max-height: 50px; max-width: 120px; object-fit: contain;">
                @else
                    <div style="background-color: #0284c7; color: #ffffff; font-weight: bold; font-size: 11px; padding: 6px 10px; text-align: center; border-radius: 4px; display: inline-block;">
                        IPS
                    </div>
                @endif
            </td>
            <td style="width: 56%; vertical-align: middle; padding-left: 8px;">
                <div class="header-title">{{ $institucion }}</div>
                <div class="header-subtitle">{{ $dependencia }}</div>
                <div class="text-muted" style="font-size: 8.5px;">Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN)</div>
            </td>
            <td style="width: 22%; text-align: right; vertical-align: middle;">
                <span style="background:#0284c7; color:#fff; font-size:8px; font-weight:bold; padding:3px 6px; border-radius:3px;">ÁREA INTERIOR</span>
                <div class="font-weight-bold text-muted" style="font-size: 8.5px; margin-top: 3px;">ACTA-VAL-#{{ $validacion->id }}</div>
            </td>
        </tr>
    </table>

    {{-- Titulo Principal --}}
    <div class="main-title">ACTA DE VALIDACIÓN Y CONFORMIDAD DE ESPECIALIDADES MÉDICAS</div>
    <div class="sub-title">DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR</div>

    {{-- I. Identificación --}}
    <div class="section-heading">I. Identificación del Establecimiento de Salud</div>
    <table class="data-table">
        <tr>
            <th style="width: 22%;">Establecimiento:</th>
            <td style="width: 40%; font-weight: bold; font-size: 9.5px;">{{ $est->nombre_oficial }}</td>
            <th style="width: 18%;">Código / ID:</th>
            <td style="width: 20%;">{{ $est->id_establecimiento }}</td>
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
            <td><strong class="text-primary">{{ $validacion->validador_firmado_at ? $validacion->validador_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }} hs</strong></td>
        </tr>
    </table>

    {{-- II. Declaración --}}
    <div class="section-heading">II. Constancia de Revisión y Validación Técnica en Terreno</div>
    <p style="text-align: justify; margin: 4px 0 8px 0; font-size: 8.8px; line-height: 1.45;">
        En el marco del plan de fortalecimiento de la Red de Servicios de Salud y la supervisión técnica de la <strong>Dirección de Hospitales del Área Interior</strong>, se procedió a la revisión exhaustiva y validación in situ de la nómina de especialidades médicas asignadas al establecimiento <strong>{{ $est->nombre_oficial }}</strong>, determinando las prestaciones activas efectivas y asentando las justificaciones de inactivación en los casos correspondientes.
    </p>

    {{-- III. Resumen Cuantitativo --}}
    <div class="section-heading">III. Resumen Cuantitativo del Relevamiento</div>
    <table class="kpi-table">
        <tr>
            <td style="width: 25%; padding-right: 3px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7.5px; font-weight:bold;">TOTAL AUDITADAS</div>
                    <div class="kpi-val text-primary">{{ $validacion->total_especialidades }}</div>
                    <div class="text-muted" style="font-size: 7.5px;">Especialidades revisadas</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7.5px; font-weight:bold;">VALIDADAS ACTIVAS</div>
                    <div class="kpi-val text-success">{{ $validacion->total_validadas }}</div>
                    <div class="text-muted" style="font-size: 7.5px;">Confirmadas en centro</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 2px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7.5px; font-weight:bold;">INACTIVADAS</div>
                    <div class="kpi-val text-danger">{{ $validacion->total_inactivadas }}</div>
                    <div class="text-muted" style="font-size: 7.5px;">Con justificación técnica</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 3px;">
                <div class="kpi-card">
                    <div class="text-muted" style="font-size: 7.5px; font-weight:bold;">ESTADO DEL ACTA</div>
                    <div class="kpi-val text-success" style="font-size: 10px;">SELLADA CON FIRMA</div>
                    <div class="text-muted" style="font-size: 7.5px;">Validador Área Interior</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- IV. Especialidades Validadas Activas --}}
    <div class="section-heading">IV. Nómina de Especialidades Médicas Validadas y Activas ({{ $itemsValidados->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%; text-align:center;">#</th>
                <th style="width: 12%; text-align:center;">ID Maestro</th>
                <th style="width: 50%;">Especialidad Médica</th>
                <th style="width: 30%; text-align:center;">Estado y Origen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($itemsValidados as $idx => $it)
                <tr>
                    <td style="text-align:center;">{{ $loop->iteration }}</td>
                    <td style="text-align:center; font-weight:bold;" class="text-primary">#{{ $it->especialidad_id }}</td>
                    <td><strong>{{ $it->especialidad?->nombre }}</strong></td>
                    <td style="text-align:center;">
                        <span class="text-success" style="font-weight:bold;">✓ Activa en Centro</span>
                        @if($it->es_agregada_en_terreno)
                            <span class="text-muted" style="font-size:7.5px;"> (Agregada en terreno)</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;" class="text-muted font-italic">No se registraron especialidades activas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- V. Especialidades Inactivadas --}}
    @if($itemsInactivos->count() > 0)
        <div class="section-heading" style="border-left-color: #ef4444;">V. Especialidades Médicas Inactivadas y Justificaciones Técnicas ({{ $itemsInactivos->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align:center;">#</th>
                    <th style="width: 37%;">Especialidad</th>
                    <th style="width: 55%;">Motivo / Justificación de Inactivación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itemsInactivos as $it)
                    <tr>
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td><strong class="text-danger">{{ $it->especialidad?->nombre }}</strong></td>
                        <td>{{ $it->justificacion_inactivacion ?: 'Sin observación asentada' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($validacion->observaciones_cierre)
        <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-left: 3px solid #0284c7; padding: 4px 6px; font-size: 8.5px; margin-bottom: 8px;">
            <strong>Observaciones Generales de la Validación:</strong> {!! $validacion->observaciones_cierre !!}
        </div>
    @endif

    {{-- VI. Rúbrica Digital --}}
    <div class="section-heading">VI. Rúbrica y Constancia Digital del Validador</div>
    <table style="width:100%; margin-top: 6px;">
        <tr>
            <td class="signature-box">
                <div class="text-primary font-weight-bold" style="font-size: 8.5px; text-transform:uppercase; border-bottom: 1px solid #cbd5e1; padding-bottom: 2px;">
                    POR LA DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR — IPS
                </div>
                <div style="height: 65px; text-align: center; padding: 3px 0;">
                    @if($validacion->validador_firma)
                        <img src="{{ $validacion->validador_firma }}" class="signature-img" alt="Firma del Validador">
                    @else
                        <div class="text-muted" style="padding-top: 20px; font-style: italic;">Sin firma digital</div>
                    @endif
                </div>
                <div style="border-top: 1px solid #cbd5e1; padding-top: 4px; font-size: 8.5px;">
                    <div style="font-weight: bold;">{{ $validacion->validador_nombre ?: 'Validador Área Interior' }}</div>
                    <div class="text-primary font-weight-bold" style="font-size: 8px;">{{ $validacion->validador_cargo ?: 'Validador Técnico' }}</div>
                    @if($validacion->validador_documento)
                        <div class="text-muted" style="font-size: 7.5px;">C.I.: {{ $validacion->validador_documento }}</div>
                    @endif
                    <div class="text-muted" style="font-size: 7px; font-style: italic; margin-top: 1px;">
                        Validado y Sellado: {{ $validacion->validador_firmado_at ? $validacion->validador_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }} hs
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="footer-note">
        <div>© {{ date('Y') }} Instituto de Previsión Social (IPS) — Dirección de Hospitales del Área Interior / Dirección de Planificación.</div>
        <div style="margin-top: 1px; font-style: italic;">Documento oficial generado a través del Sistema Integrado de Planificación y Monitoreo Estratégico (SIPLAN).</div>
    </div>

</body>
</html>
