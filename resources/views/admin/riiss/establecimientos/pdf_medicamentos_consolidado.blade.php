<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Auditoría de Farmacia - {{ $est->nombre_oficial }}</title>
    <style>
        @page {
            margin: 1.2cm 1cm 1.5cm 1cm;
        }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.25;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
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
            font-size: 10px;
            font-weight: bold;
            color: #4338ca;
            margin-top: 2px;
        }
        .doc-title {
            font-size: 9.5px;
            color: #b91c1c;
            font-weight: bold;
            margin-top: 2px;
            text-transform: uppercase;
        }
        .info-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }
        .info-box td {
            padding: 4px 6px;
            font-size: 8.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-label {
            font-weight: bold;
            color: #334155;
            width: 18%;
            text-transform: uppercase;
            font-size: 8px;
        }
        .info-val {
            color: #0f172a;
            width: 32%;
        }
        .kpi-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .kpi-card {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 5px 8px;
            text-align: center;
            border-radius: 4px;
        }
        .kpi-card.blue {
            background-color: #e0e7ff;
            border-color: #c7d2fe;
        }
        .kpi-num {
            font-size: 12px;
            font-weight: bold;
            color: #14532d;
        }
        .kpi-card.blue .kpi-num { color: #1e1b4b; }
        .kpi-label {
            font-size: 7.5px;
            text-transform: uppercase;
            font-weight: bold;
            color: #166534;
        }
        .kpi-card.blue .kpi-label { color: #4338ca; }

        .audit-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: auto;
        }
        .audit-table thead {
            display: table-header-group;
        }
        .audit-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .audit-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            border: 1px solid #334155;
            padding: 4px 5px;
            text-align: left;
        }
        .audit-table td {
            border: 1px solid #cbd5e1;
            padding: 3px 5px;
            font-size: 8px;
            vertical-align: middle;
        }
        .audit-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .code-badge {
            font-family: monospace;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 1px 3px;
            border-radius: 2px;
            border: 1px solid #e2e8f0;
            font-size: 8px;
        }
        .esp-pill {
            display: inline-block;
            background-color: #e2e8f0;
            color: #334155;
            font-size: 7px;
            padding: 1px 4px;
            border-radius: 3px;
            margin: 1px 2px 1px 0;
            font-weight: 500;
        }
        .chk-box {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #64748b;
            margin-right: 3px;
            vertical-align: middle;
            background: #fff;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signatures-table td {
            width: 50%;
            text-align: center;
            padding: 10px 25px;
            font-size: 8px;
            color: #334155;
        }
        .signature-line {
            border-top: 1px solid #475569;
            margin-top: 35px;
            padding-top: 4px;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: -0.8cm;
            left: 0;
            right: 0;
            height: 20px;
            border-top: 1px solid #cbd5e1;
            padding-top: 3px;
            font-size: 7.5px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="footer">
        Instituto de Previsión Social (IPS) — Red Integrada e Integral de Servicios de Salud (RIISS) | Planilla de Auditoría de Farmacia emitida el {{ $fecha }}
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="title-ips">Instituto de Previsión Social</div>
                <div class="subtitle-riiss">Redes Integradas e Integrales de Servicios de Salud (RIISS)</div>
                <div class="doc-title">Planilla de Auditoría de Farmacia — Vademécum Consolidado Único</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <div style="font-size: 8px; color: #64748b;">Fecha de emisión:</div>
                <div style="font-size: 9px; font-weight: bold; color: #0f172a;">{{ $fecha }}</div>
                <div style="font-size: 8px; color: #b91c1c; font-weight: bold; margin-top: 2px;">DOCUMENTO DE CONTROL DE CAMPO</div>
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
            <td class="info-val">
                {{ $est->condicion_inmueble ?? 'N/A' }}
                @if(!empty($est->habilita_farmacia_cronicos) || !empty($est->habilita_empadronamiento_cronicos))
                    &nbsp;·&nbsp;<span style="color: #1e40af; font-weight: bold;">[Dispensación Crónicos]</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="kpi-table">
        <tr>
            <td style="width: {{ !empty($totalMedicamentosCronicos) ? '34%' : '50%' }}; padding-right: 4px;">
                <div class="kpi-card">
                    <div class="kpi-num">{{ $totalMedicamentosUnicos }}</div>
                    <div class="kpi-label">Medicamentos Físicos Únicos</div>
                </div>
            </td>
            <td style="width: {{ !empty($totalMedicamentosCronicos) ? '33%' : '50%' }}; padding-left: 2px; padding-right: 2px;">
                <div class="kpi-card blue">
                    <div class="kpi-num">{{ $totalEspecialidades }}</div>
                    <div class="kpi-label">Especialidades Asistenciales</div>
                </div>
            </td>
            @if(!empty($totalMedicamentosCronicos))
                <td style="width: 33%; padding-left: 4px;">
                    <div class="kpi-card" style="background-color: #eff6ff; border-color: #bfdbfe;">
                        <div class="kpi-num" style="color: #1d4ed8;">{{ $totalMedicamentosCronicos }}</div>
                        <div class="kpi-label" style="color: #2563eb;">Patologías Crónicas (RCA 007/22)</div>
                    </div>
                </td>
            @endif
        </tr>
    </table>

    <table class="audit-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">#</th>
                <th style="width: 14%;">Código</th>
                <th style="width: 42%;">Medicamento / Presentación Oficial</th>
                <th style="width: 25%;">Especialidades que lo Utilizan</th>
                <th style="width: 15%; text-align: center;">Auditoría Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicamentosConsolidados as $idx => $m)
                <tr>
                    <td style="text-align: center; color: #64748b; font-weight: bold;">{{ $loop->iteration }}</td>
                    <td><span class="code-badge">{{ $m['codigo'] ?: 'S/C' }}</span></td>
                    <td>
                        <strong style="color: #0f172a;">{{ $m['nombre'] ?: ('MEDICAMENTO CÓDIGO ' . $m['codigo']) }}</strong>
                        @if(!empty($m['es_cronico']))
                            <div style="margin-top: 1px;">
                                <span style="display: inline-block; background-color: #dbeafe; color: #1e40af; font-size: 6.5px; font-weight: bold; padding: 1px 3px; border-radius: 2px;">
                                    CRÓNICO: {{ $m['categoria_terapeutica'] ?? 'RCA 007/22' }}
                                </span>
                                @if(!empty($m['es_psicotropico']))
                                    <span style="display: inline-block; background-color: #ede9fe; color: #6b21a8; font-size: 6.5px; font-weight: bold; padding: 1px 3px; border-radius: 2px;">
                                        PSICOTRÓPICO (Retiro c/ 8d)
                                    </span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td>
                        @foreach($m['especialidades'] as $esp)
                            <span class="esp-pill">{{ $esp }}</span>
                        @endforeach
                    </td>
                    <td style="text-align: left; padding-left: 6px;">
                        <span class="chk-box"></span><span style="font-size: 7.5px;">Disp.</span>&nbsp;&nbsp;
                        <span class="chk-box"></span><span style="font-size: 7.5px;">Falta</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 15px; color: #94a3b8; font-style: italic;">
                        No se registran medicamentos en el catálogo de este establecimiento.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-line">
                    Firma y Aclaración<br>
                    <strong>Técnico Auditor / Evaluador RIISS</strong>
                </div>
            </td>
            <td>
                <div class="signature-line">
                    Firma y Sello<br>
                    <strong>Responsable de Farmacia / Director del Centro</strong>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
