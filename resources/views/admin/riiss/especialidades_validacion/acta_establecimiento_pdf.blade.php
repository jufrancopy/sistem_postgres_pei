<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Acta de Validación — {{ $est->nombre_oficial }}</title>
    <style>
        @page {
            margin: 1.2cm 1.4cm 1.4cm 1.4cm;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.35;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .title-box {
            background: #f1f5f9;
            border-top: 1.5px solid #0284c7;
            border-bottom: 1.5px solid #0284c7;
            padding: 8px;
            text-align: center;
            margin-bottom: 14px;
        }
        .title-box h2 {
            margin: 0;
            font-size: 13px;
            color: #0369a1;
            text-transform: uppercase;
        }
        .title-box .subtitle {
            margin-top: 3px;
            font-size: 10.5px;
            color: #334155;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 7px;
            font-size: 10px;
        }
        .data-table th {
            background: #f8fafc;
            color: #334155;
            font-weight: bold;
            text-align: left;
        }

        .section-header {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin: 14px 0 6px 0;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .stats-table td {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            text-align: center;
            padding: 6px 4px;
            width: 25%;
        }
        .stats-table .stat-val {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }
        .stats-table .stat-lbl {
            font-size: 8.5px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
        }

        .sig-container {
            margin-top: 25px;
            width: 100%;
        }
        .sig-box {
            width: 260px;
            float: right;
            border: 1px solid #cbd5e1;
            background: #fafafa;
            padding: 10px;
            text-align: center;
        }
        .sig-box img {
            max-height: 60px;
            max-width: 200px;
            margin-bottom: 4px;
        }
        .sig-box .line {
            border-top: 1px solid #334155;
            margin-top: 4px;
            padding-top: 3px;
            font-weight: bold;
            font-size: 10px;
        }
        .footer-note {
            margin-top: 20px;
            text-align: center;
            font-size: 8.5px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 6px;
            clear: both;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 60px; vertical-align: middle;">
                @if($logoInstitucional)
                    <img src="{{ $logoInstitucional }}" alt="Logo" style="max-height: 42px; max-width: 100px;">
                @else
                    <div style="font-size: 16px; font-weight: bold; color: #0284c7;">IPS</div>
                @endif
            </td>
            <td style="vertical-align: middle; padding-left: 10px;">
                <div style="font-size: 11px; font-weight: bold; color: #0f172a; text-transform: uppercase;">
                    {{ $institucion }}
                </div>
                <div style="font-size: 9.5px; color: #475569; font-weight: bold;">
                    {{ $dependencia }}
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle; font-size: 9px; color: #64748b;">
                <div><strong>Código de Sesión:</strong> {{ $sesion->codigo_acceso }}</div>
                <div><strong>Emisión:</strong> {{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="title-box">
        <h2>Acta Individual de Validación Técnica</h2>
        <div class="subtitle">{{ $est->nombre_oficial }} — Departamento de {{ $est->departamento }}</div>
    </div>

    <table class="data-table">
        <tr>
            <td style="width: 20%; font-weight: bold; background: #f8fafc;">Establecimiento:</td>
            <td style="width: 45%; font-weight: bold;">{{ $est->nombre_oficial }}</td>
            <td style="width: 15%; font-weight: bold; background: #f8fafc;">Departamento:</td>
            <td style="width: 20%;">{{ $est->departamento }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background: #f8fafc;">Tipología / Nivel:</td>
            <td>{{ $est->tipologia_clasificacion }} ({{ $est->complejidad_label ?? $est->complejidad }})</td>
            <td style="font-weight: bold; background: #f8fafc;">Área de Gestión:</td>
            <td>{{ $est->area_gestion ?? 'ÁREA INTERIOR' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background: #f8fafc;">Auditor Validador:</td>
            <td>{{ $valEst?->validador_nombre ?? $sesion->analista_nombre }}</td>
            <td style="font-weight: bold; background: #f8fafc;">Fecha y Hora:</td>
            <td>{{ $valEst?->firmado_at ? $valEst->firmado_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table class="stats-table">
        <tr>
            <td>
                <div class="stat-val">{{ $totalDb }}</div>
                <div class="stat-lbl">En Base de Datos</div>
            </td>
            <td>
                <div class="stat-val" style="color: #15803d;">{{ $activas->count() }}</div>
                <div class="stat-lbl">Validadas Activas</div>
            </td>
            <td>
                <div class="stat-val" style="color: #b91c1c;">{{ $inactivas->count() }}</div>
                <div class="stat-lbl">Inactivadas</div>
            </td>
            <td>
                <div class="stat-val" style="color: #0284c7;">{{ $registros->where('es_agregada', true)->count() }}</div>
                <div class="stat-lbl">Agregadas</div>
            </td>
        </tr>
    </table>

    {{-- Especialidades Activas --}}
    <div class="section-header">1. Especialidades Médicas Validadas y Operativas ({{ $activas->count() }})</div>
    @if($activas->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 7%; text-align: center;">#</th>
                    <th style="width: 12%; text-align: center;">Código</th>
                    <th style="width: 53%;">Nombre de la Especialidad (Bioestadística)</th>
                    <th style="width: 28%;">Observación / Justificación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activas as $i => $reg)
                    <tr>
                        <td style="text-align: center; color: #64748b;">{{ $i + 1 }}</td>
                        <td style="text-align: center; font-family: monospace;">{{ $reg->especialidad?->codigo ?: '—' }}</td>
                        <td style="font-weight: bold; color: #0f172a;">
                            {{ $reg->especialidad?->nombre ?? 'Especialidad #' . $reg->especialidad_id }}
                            @if($reg->es_agregada)
                                <span style="font-size: 8px; background: #fef3c7; color: #92400e; padding: 1px 3px;">[AGREGADA]</span>
                            @endif
                        </td>
                        <td style="color: #475569; font-size: 9.5px;">{{ $reg->justificacion ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="padding: 6px; text-align: center; color: #64748b; border: 1px dashed #cbd5e1; margin-bottom: 8px;">
            Sin especialidades activas registradas.
        </div>
    @endif

    {{-- Especialidades Inactivadas --}}
    @if($inactivas->count() > 0)
        <div class="section-header" style="margin-top: 10px;">2. Especialidades Inactivadas / No Disponibles ({{ $inactivas->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 7%; text-align: center;">#</th>
                    <th style="width: 12%; text-align: center;">Código</th>
                    <th style="width: 46%;">Especialidad Inactivada</th>
                    <th style="width: 35%;">Motivo Técnico de Inactivación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inactivas as $i => $reg)
                    <tr style="background: #fef2f2;">
                        <td style="text-align: center; color: #991b1b;">{{ $i + 1 }}</td>
                        <td style="text-align: center; font-family: monospace; color: #991b1b;">{{ $reg->especialidad?->codigo ?: '—' }}</td>
                        <td style="color: #991b1b; text-decoration: line-through;">
                            {{ $reg->especialidad?->nombre ?? 'Especialidad #' . $reg->especialidad_id }}
                        </td>
                        <td style="color: #7f1d1d; font-size: 9.5px;">
                            {{ $reg->justificacion ?: 'No se cuenta con el servicio en este establecimiento.' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!empty($valEst?->notas))
        <div class="section-header">3. Observaciones del Establecimiento</div>
        <div style="padding: 6px 8px; background: #f8fafc; border: 1px solid #e2e8f0; font-size: 9.5px; color: #334155; margin-bottom: 10px;">
            {{ $valEst->notas }}
        </div>
    @endif

    <div class="sig-container">
        <div class="sig-box">
            @if(!empty($valEst?->firma_digital))
                <img src="{{ $valEst->firma_digital }}" alt="Firma">
            @elseif(!empty($sesion->firma_digital))
                <img src="{{ $sesion->firma_digital }}" alt="Firma">
            @else
                <div style="height: 40px; line-height: 40px; color: #94a3b8; font-size: 9px;">[ Firma Digital ]</div>
            @endif
            <div class="line">
                {{ $valEst?->validador_nombre ?? $sesion->analista_nombre }}
            </div>
            <div style="font-size: 8.5px; color: #64748b;">
                {{ $valEst?->validador_cargo ?? $sesion->analista_cargo ?? 'Validador Técnico' }}
            </div>
            <div style="font-size: 8px; color: #94a3b8;">
                Certificado el {{ $valEst?->firmado_at ? $valEst->firmado_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <div class="footer-note">
        Sistema de Validación y Auditoría RIISS · {{ $institucion }} · Documento Oficial
    </div>

</body>
</html>
