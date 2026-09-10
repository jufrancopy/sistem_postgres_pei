<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acta de Validación de Especialidades Médicas — {{ $est->nombre_oficial }}</title>
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
        .btn-primary { background: #0284c7; color: #fff; }
        .btn-primary:hover { background: #0369a1; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #64748b; color: #fff; }
        .btn-secondary:hover { background: #475569; }

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

        .sig-row {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        .sig-card {
            width: 55%;
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
            <a href="{{ route('riiss.validaciones.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Volver a la Bandeja
            </a>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('riiss.validaciones.acta-pdf', $validacion->id) }}" class="btn btn-danger">
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
                <div style="font-size:11px; font-weight:700; color:#64748b; margin-top:4px;">ACTA-VAL-#{{ $validacion->id }}</div>
            </div>
        </div>

        {{-- Titulo Principal --}}
        <div class="title-box">
            <h3 style="margin:0 0 4px 0; font-size:15px; font-weight:700; text-transform:uppercase; color:#0f172a;">
                ACTA DE VALIDACIÓN Y CONFORMIDAD DE ESPECIALIDADES MÉDICAS
            </h3>
            <div style="font-size:11px; font-weight:700; color:#0284c7; text-transform:uppercase;">
                DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR
            </div>
            <div style="font-size:10px; font-weight:600; color:#64748b;">
                RELEVAMIENTO TÉCNICO DE CAPACIDAD RESOLUTIVA Y SERVICIOS MÉDICOS
            </div>
        </div>

        {{-- I. Identificacion --}}
        <div class="section-title">I. Identificación del Establecimiento de Salud</div>
        <table class="data-table">
            <tr>
                <th style="width:22%;">Establecimiento:</th>
                <td style="width:40%; font-weight:700; color:#0f172a;">{{ $est->nombre_oficial }}</td>
                <th style="width:18%;">Código / ID:</th>
                <td style="width:20%;">{{ $est->id_establecimiento }}</td>
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

        {{-- II. Declaracion --}}
        <div class="section-title">II. Constancia de Revisión y Validación Técnica en Terreno</div>
        <p style="text-align:justify; margin:6px 0 12px 0; font-size:12px; line-height:1.55; color:#334155;">
            En el marco del plan de fortalecimiento de la Red de Servicios de Salud y la supervisión técnica de la <strong>Dirección de Hospitales del Área Interior</strong>, se procedió a la revisión exhaustiva y validación in situ de la nómina de especialidades médicas asignadas al establecimiento <strong>{{ $est->nombre_oficial }}</strong>, determinando las prestaciones activas efectivas y asentando las justificaciones de inactivación en los casos correspondientes.
        </p>

        {{-- III. Resumen Cuantitativo --}}
        <div class="section-title">III. Resumen Cuantitativo del Relevamiento</div>
        <div class="kpi-row">
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">TOTAL AUDITADAS</div>
                <div class="kpi-num text-primary">{{ $validacion->total_especialidades }}</div>
                <div style="font-size:10px; color:#64748b;">Especialidades revisadas</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">VALIDADAS ACTIVAS</div>
                <div class="kpi-num text-success">{{ $validacion->total_validadas }}</div>
                <div style="font-size:10px; color:#64748b;">Confirmadas en centro</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">INACTIVADAS</div>
                <div class="kpi-num text-danger">{{ $validacion->total_inactivadas }}</div>
                <div style="font-size:10px; color:#64748b;">Con justificación técnica</div>
            </div>
            <div class="kpi-col">
                <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase;">ESTADO DEL ACTA</div>
                <div class="kpi-num text-success" style="font-size:13px; margin-top:3px;">
                    SELLADA CON FIRMA
                </div>
                <div style="font-size:10px; color:#64748b;">Validador Área Interior</div>
            </div>
        </div>

        {{-- IV. Especialidades Validadas Activas --}}
        <div class="section-title">IV. Nómina de Especialidades Médicas Validadas y Activas ({{ $itemsValidados->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:8%; text-align:center;">#</th>
                    <th style="width:14%; text-align:center;">ID Maestro</th>
                    <th style="width:50%;">Especialidad Médica</th>
                    <th style="width:28%; text-align:center;">Estado y Origen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($itemsValidados as $idx => $it)
                    <tr>
                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                        <td style="text-align:center; font-weight:700;" class="text-primary">#{{ $it->especialidad_id }}</td>
                        <td><strong>{{ $it->especialidad?->nombre }}</strong></td>
                        <td style="text-align:center;">
                            <span class="text-success" style="font-weight:700;">✓ Activa en Centro</span>
                            @if($it->es_agregada_en_terreno)
                                <span class="text-muted" style="font-size:10px;"> (Agregada en terreno)</span>
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
            <div class="section-title" style="color: #dc2626;">V. Especialidades Médicas Inactivadas y Justificaciones Técnicas ({{ $itemsInactivos->count() }})</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:8%; text-align:center;">#</th>
                        <th style="width:37%;">Especialidad</th>
                        <th style="width:55%;">Motivo / Justificación de Inactivación</th>
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
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-left:4px solid #0284c7; padding:8px 12px; font-size:11.5px; margin-bottom:14px; border-radius:4px;">
                <strong>Observaciones Generales de la Validación:</strong> {{ $validacion->observaciones_cierre }}
            </div>
        @endif

        {{-- VI. Rúbrica Digital --}}
        <div class="section-title">VI. Rúbrica y Constancia Digital del Validador</div>
        <div class="sig-row">
            <div class="sig-card">
                <div style="font-size:10px; font-weight:700; color:#0284c7; text-transform:uppercase; border-bottom:1px solid #cbd5e1; padding-bottom:4px;">
                    POR LA DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR — IPS
                </div>
                <div style="height:85px; display:flex; align-items:center; justify-content:center; padding:4px 0;">
                    @if($validacion->validador_firma)
                        <img src="{{ $validacion->validador_firma }}" class="sig-img" alt="Firma del Validador">
                    @else
                        <span style="color:#94a3b8; font-style:italic; font-size:11px;">Sin firma digital</span>
                    @endif
                </div>
                <div style="border-top:1px solid #cbd5e1; padding-top:6px; font-size:11px;">
                    <div style="font-weight:700; color:#0f172a;">{{ $validacion->validador_nombre ?: 'Validador Área Interior' }}</div>
                    <div style="color:#0284c7; font-weight:600; font-size:10.5px;">{{ $validacion->validador_cargo ?: 'Validador Técnico' }}</div>
                    @if($validacion->validador_documento)
                        <div style="color:#64748b; font-size:10px;">C.I.: {{ $validacion->validador_documento }}</div>
                    @endif
                    <div style="color:#94a3b8; font-size:9.5px; font-style:italic; margin-top:2px;">
                        Validado y Sellado: {{ $validacion->validador_firmado_at ? $validacion->validador_firmado_at->format('d/m/Y H:i') : date('d/m/Y H:i') }} hs
                    </div>
                </div>
            </div>
        </div>

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
