<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Medicamentos por Especialidad - {{ $est->nombre_oficial }}</title>
    <style>
        @page {
            margin: 1.2cm 1cm 1.5cm 1cm;
        }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.3;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .title-ips {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .subtitle-riiss {
            font-size: 10.5px;
            font-weight: bold;
            color: #4338ca;
            margin-top: 2px;
        }
        .doc-title {
            font-size: 10px;
            color: #475569;
            margin-top: 2px;
        }
        .info-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }
        .info-box td {
            padding: 5px 8px;
            font-size: 9px;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-label {
            font-weight: bold;
            color: #334155;
            width: 18%;
            text-transform: uppercase;
            font-size: 8.5px;
        }
        .info-val {
            color: #0f172a;
            width: 32%;
        }
        .kpi-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }
        .kpi-card {
            background-color: #e0e7ff;
            border: 1px solid #c7d2fe;
            padding: 6px 10px;
            text-align: center;
            border-radius: 4px;
        }
        .kpi-card.green {
            background-color: #dcfce7;
            border-color: #bbf7d0;
        }
        .kpi-num {
            font-size: 13px;
            font-weight: bold;
            color: #1e1b4b;
        }
        .kpi-label {
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
            color: #4338ca;
        }
        .kpi-card.green .kpi-num { color: #14532d; }
        .kpi-card.green .kpi-label { color: #166534; }

        .especialidad-header {
            background-color: #1e293b;
            color: #ffffff;
            padding: 5px 8px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            border-radius: 3px 3px 0 0;
        }
        .especialidad-header.cronico {
            background-color: #b45309;
        }
        .med-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .med-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            text-align: left;
        }
        .med-table td {
            border: 1px solid #e2e8f0;
            padding: 3.5px 6px;
            font-size: 8.5px;
        }
        .med-table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .code-badge {
            font-family: monospace;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
        }
        .footer {
            position: fixed;
            bottom: -0.8cm;
            left: 0;
            right: 0;
            height: 25px;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
            font-size: 8px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="footer">
        Instituto de Previsión Social (IPS) — Red Integrada e Integral de Servicios de Salud (RIISS) | Reporte Desglosado por Servicio emitido el {{ $fecha }}
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="title-ips">Instituto de Previsión Social</div>
                <div class="subtitle-riiss">Redes Integradas e Integrales de Servicios de Salud (RIISS)</div>
                <div class="doc-title">Reporte de Medicamentos por Servicio / Especialidad Médica</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <div style="font-size: 8.5px; color: #64748b;">Fecha de emisión:</div>
                <div style="font-size: 9.5px; font-weight: bold; color: #0f172a;">{{ $fecha }}</div>
                <div style="font-size: 8.5px; color: #0284c7; font-weight: bold; margin-top: 2px;">DOCUMENTO ASISTENCIAL</div>
            </td>
        </tr>
    </table>

    <table class="info-box">
        <tr>
            <td class="info-label">Establecimiento:</td>
            <td class="info-val"><strong>{{ $est->nombre_oficial }}</strong></td>
            <td class="info-label">Código ID:</td>
            <td class="info-val"><code>{{ $est->id_establecimiento }}</code></td>
        </tr>
        <tr>
            <td class="info-label">Tipología:</td>
            <td class="info-val">{{ $est->tipologia_clasificacion ?? $est->tipo_est ?? 'N/A' }}</td>
            <td class="info-label">Departamento:</td>
            <td class="info-val">{{ $est->departamento ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Complejidad:</td>
            <td class="info-val">{{ $est->complejidad_label ?? 'N/A' }}</td>
            <td class="info-label">Condición:</td>
            <td class="info-val">{{ $est->condicion_inmueble ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="kpi-table">
        <tr>
            <td style="width: 50%; padding-right: 6px;">
                <div class="kpi-card">
                    <div class="kpi-num">{{ $totalEspecialidades }}</div>
                    <div class="kpi-label">Especialidades con Cobertura</div>
                </div>
            </td>
            <td style="width: 50%; padding-left: 6px;">
                <div class="kpi-card green">
                    <div class="kpi-num">{{ $totalAsignaciones }}</div>
                    <div class="kpi-label">Total Asignaciones por Servicio</div>
                </div>
            </td>
        </tr>
    </table>

    @forelse($especialidadesMedicamentos as $especialidad => $meds)
        @php
            $isCronico = str_contains($especialidad, 'Crónicos') || str_contains($especialidad, 'Otra');
        @endphp
        <div class="especialidad-header {{ $isCronico ? 'cronico' : '' }}">
            <table style="width: 100%; border-collapse: collapse; color: white;">
                <tr>
                    <td style="font-weight: bold; font-size: 9.5px;">
                        @if($isCronico)
                            ⚠ {{ $especialidad }}
                        @else
                            ⚕ ESPECIALIDAD: {{ $especialidad }}
                        @endif
                    </td>
                    <td style="text-align: right; font-size: 8.5px;">
                        {{ count($meds) }} {{ count($meds) == 1 ? 'Medicamento' : 'Medicamentos' }}
                    </td>
                </tr>
            </table>
        </div>
        <table class="med-table">
            <thead>
                <tr>
                    <th style="width: 6%; text-align: center;">#</th>
                    <th style="width: 20%;">Código</th>
                    <th style="width: 74%;">Descripción del Medicamento / Presentación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($meds as $idx => $m)
                    <tr>
                        <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                        <td><span class="code-badge">{{ $m['codigo'] ?: 'S/C' }}</span></td>
                        <td><strong>{{ $m['nombre'] }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #94a3b8; font-style: italic; padding: 6px;">
                            No registra medicamentos asignados en esta especialidad.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @empty
        <div style="text-align: center; padding: 25px; border: 1px dashed #cbd5e1; color: #64748b; font-style: italic;">
            Este establecimiento no posee especialidades ni medicamentos asignados en la base de datos de RIISS.
        </div>
    @endforelse

</body>
</html>
