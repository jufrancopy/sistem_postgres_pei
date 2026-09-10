<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acta de Validación — {{ $est->nombre_oficial }}</title>
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
            font-size: 12px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
        }
        table.data-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-align: left;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }
        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }
        .stat-card .num {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .stat-card .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
        }

        .signature-box {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        .sig-card {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 18px;
            width: 320px;
            text-align: center;
            background: #fafafa;
        }
        .sig-card img {
            max-height: 80px;
            max-width: 260px;
            display: block;
            margin: 0 auto 6px auto;
        }
        .sig-card .sig-line {
            border-top: 1px solid #475569;
            margin-top: 8px;
            padding-top: 4px;
            font-size: 11.5px;
            font-weight: 600;
        }

        @media print {
            body { background: #ffffff; padding: 0; }
            .toolbar { display: none; }
            .sheet { border: none; box-shadow: none; padding: 0; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <div>
            <a href="{{ route('riiss.portal-validador.show', $sesion->token) }}" class="btn btn-secondary mr-2">
                <i class="fa fa-arrow-left mr-1"></i> Volver al Portal
            </a>
            <span style="font-weight: 700; font-size: 13px; color: #334155;">
                {{ $est->nombre_oficial }} — Validación Técnica
            </span>
        </div>
        <div>
            <a href="{{ route('riiss.portal-validador.acta-establecimiento-pdf', [$sesion->token, $est->id_establecimiento]) }}" class="btn btn-danger mr-2">
                <i class="fa fa-file-pdf mr-1"></i> Descargar PDF
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa fa-print mr-1"></i> Imprimir
            </button>
        </div>
    </div>

    <div class="sheet">
        {{-- Encabezado Institucional --}}
        <div class="header-box">
            <div style="display: flex; align-items: center; gap: 14px;">
                @if($logoInstitucional)
                    <img src="{{ $logoInstitucional }}" alt="Logo" style="max-height: 50px; max-width: 140px; object-fit: contain;">
                @else
                    <div style="width: 44px; height: 44px; border-radius: 6px; background: #0284c7; color: #fff; font-weight: 900; display: grid; place-items: center; font-size: 14px;">IPS</div>
                @endif
                <div>
                    <div style="font-size: 13px; font-weight: 800; color: #0f172a; text-transform: uppercase;">
                        {{ $institucion }}
                    </div>
                    <div style="font-size: 11.5px; color: #475569; font-weight: 600;">
                        {{ $dependencia }}
                    </div>
                </div>
            </div>
            <div style="text-align: right; font-size: 11px; color: #64748b;">
                <div><strong>Código de Sesión:</strong> {{ $sesion->codigo_acceso }}</div>
                <div><strong>Fecha de Emisión:</strong> {{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        {{-- Título Principal --}}
        <div class="title-box">
            <div style="font-size: 11px; font-weight: 700; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">
                Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)
            </div>
            <div style="font-size: 14px; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px;">
                Acta Individual de Relevamiento y Validación Técnica de Especialidades Médicas
            </div>
            <div style="font-size: 12px; color: #334155; margin-top: 3px; font-weight: 600;">
                {{ $est->nombre_oficial }} · Departamento de {{ $est->departamento }}
            </div>
        </div>

        <div style="padding: 8px 12px; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px; font-size: 11px; color: #0369a1; margin-bottom: 14px; line-height: 1.5;">
            <strong>Marco Institucional:</strong> El presente relevamiento y validación técnica se ejecuta en el marco de la implementación de la <strong>Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)</strong>, a los efectos de auditar, certificar y transparentar la cartera de especialidades médicas operativas en el establecimiento.
        </div>

        {{-- Datos del Establecimiento y Validador --}}
        <table class="data-table" style="margin-bottom: 16px;">
            <tr>
                <td style="width: 25%; font-weight: 700; background: #f8fafc;">Establecimiento:</td>
                <td style="width: 40%; font-weight: 600;">{{ $est->nombre_oficial }}</td>
                <td style="width: 15%; font-weight: 700; background: #f8fafc;">Departamento:</td>
                <td style="width: 20%;">{{ $est->departamento }}</td>
            </tr>
            <tr>
                <td style="font-weight: 700; background: #f8fafc;">Tipología / Complejidad:</td>
                <td>{{ $est->tipologia_clasificacion }} · {{ $est->complejidad_label ?? $est->complejidad }}</td>
                <td style="font-weight: 700; background: #f8fafc;">Área de Gestión:</td>
                <td>{{ $est->area_gestion ?? 'ÁREA INTERIOR' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 700; background: #f8fafc;">Auditor / Validador:</td>
                <td>{{ $valEst?->validador_nombre ?? $sesion->analista_nombre }}</td>
                <td style="font-weight: 700; background: #f8fafc;">Fecha y Hora:</td>
                <td>{{ $valEst?->firmado_at ? $valEst->firmado_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        {{-- Tarjetas de Totales --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="num">{{ $totalDb }}</div>
                <div class="label">Registradas en DB</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color: #15803d;">{{ $activas->count() }}</div>
                <div class="label">Validadas Activas</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color: #b91c1c;">{{ $inactivas->count() }}</div>
                <div class="label">Inactivadas</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color: #0284c7;">{{ $registros->where('es_agregada', true)->count() }}</div>
                <div class="label">Agregadas en Terreno</div>
            </div>
        </div>

        {{-- Tabla de Especialidades Validadas (Activas) --}}
        <div class="section-title">
            <i class="fa fa-check-circle mr-1 text-success" style="color: #10b981;"></i> 1. Especialidades Médicas Validadas y Operativas en el Centro ({{ $activas->count() }})
        </div>
        @if($activas->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">#</th>
                        <th style="width: 12%; text-align: center;">Código</th>
                        <th style="width: 50%;">Nombre de la Especialidad (Bioestadística)</th>
                        <th style="width: 30%;">Observación / Justificación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activas as $i => $reg)
                        <tr>
                            <td style="text-align: center; font-weight: 600; color: #64748b;">{{ $i + 1 }}</td>
                            <td style="text-align: center; font-family: monospace; font-size: 11px;">{{ $reg->especialidad?->codigo ?: '—' }}</td>
                            <td style="font-weight: 600; color: #0f172a;">
                                {{ $reg->especialidad?->nombre ?? 'Especialidad #' . $reg->especialidad_id }}
                                @if($reg->es_agregada)
                                    <span style="display: inline-block; font-size: 9.5px; background: #fef3c7; color: #92400e; padding: 1px 5px; border-radius: 4px; margin-left: 4px; font-weight: 700;">AGREGADA</span>
                                @endif
                            </td>
                            <td style="color: #475569; font-size: 11px;">{{ $reg->justificacion ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; text-align: center; color: #64748b; margin-bottom: 12px;">
                No se registraron especialidades activas para este centro.
            </div>
        @endif

        {{-- Tabla de Especialidades Inactivadas --}}
        @if($inactivas->count() > 0)
            <div class="section-title" style="margin-top: 20px;">
                <i class="fa fa-ban mr-1 text-danger" style="color: #ef4444;"></i> 2. Especialidades Inactivadas / No Disponibles en el Centro ({{ $inactivas->count() }})
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">#</th>
                        <th style="width: 12%; text-align: center;">Código</th>
                        <th style="width: 45%;">Especialidad Inactivada</th>
                        <th style="width: 35%;">Motivo / Justificación Técnica de Inactivación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inactivas as $i => $reg)
                        <tr style="background: #fef2f2;">
                            <td style="text-align: center; color: #991b1b;">{{ $i + 1 }}</td>
                            <td style="text-align: center; font-family: monospace; font-size: 11px; color: #991b1b;">{{ $reg->especialidad?->codigo ?: '—' }}</td>
                            <td style="font-weight: 600; color: #991b1b; text-decoration: line-through;">
                                {{ $reg->especialidad?->nombre ?? 'Especialidad #' . $reg->especialidad_id }}
                            </td>
                            <td style="color: #7f1d1d; font-size: 11px;">
                                <strong>Motivo:</strong> {{ $reg->justificacion ?: 'No se cuenta con el servicio en este establecimiento.' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Observaciones Generales --}}
        @if(!empty($valEst?->notas))
            <div class="section-title">
                <i class="fa fa-comment-dots mr-1 text-primary"></i> 3. Observaciones Generales del Establecimiento
            </div>
            <div style="padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; color: #334155; margin-bottom: 16px;">
                {{ $valEst->notas }}
            </div>
        @endif

        {{-- Certificación y Firma Digital --}}
        <div class="signature-box">
            <div class="sig-card">
                @if(!empty($valEst?->firma_digital))
                    <img src="{{ $valEst->firma_digital }}" alt="Firma Digital">
                @elseif(!empty($sesion->firma_digital))
                    <img src="{{ $sesion->firma_digital }}" alt="Firma Digital">
                @else
                    <div style="height: 60px; display: grid; place-items: center; color: #94a3b8; font-style: italic; font-size: 11px;">
                        [ Firma Digital Registrada ]
                    </div>
                @endif
                <div class="sig-line">
                    {{ $valEst?->validador_nombre ?? $sesion->analista_nombre }}
                </div>
                <div style="font-size: 10.5px; color: #64748b;">
                    {{ $valEst?->validador_cargo ?? $sesion->analista_cargo ?? 'Validador Técnico Institucional' }}
                </div>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 3px;">
                    Certificado el {{ $valEst?->firmado_at ? $valEst->firmado_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 10px;">
            Documento generado a través del Sistema de Monitoreo y Validación RIISS · {{ $institucion }}
        </div>
    </div>

</body>
</html>
