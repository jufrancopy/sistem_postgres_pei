<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dictamen Técnico Farmacológico — {{ $especialidad->nombre }} — IPS</title>
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
        .btn-primary { background: #0d9488; color: #fff; }
        .btn-primary:hover { background: #0f766e; }
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
            border-bottom: 2px solid #0d9488;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .title-box {
            background: #f0fdfa;
            border-top: 2px solid #0d9488;
            border-bottom: 2px solid #0d9488;
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

        .badge-ok {
            background: #dcfce7;
            color: #15803d;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10.5px;
        }
        .badge-no {
            background: #fee2e2;
            color: #b91c1c;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10.5px;
        }

        .signature-box {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-card {
            width: 320px;
            border-top: 2px solid #0f172a;
            padding-top: 8px;
            text-align: center;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .sheet { border: none; box-shadow: none; padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <div style="font-weight:700; color:#0d9488;">
        <i class="fa fa-file-medical mr-1"></i> Dictamen Técnico Farmacológico Oficial
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa fa-print mr-1"></i> Imprimir / Guardar PDF
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fa fa-times mr-1"></i> Cerrar
        </button>
    </div>
</div>

<div class="sheet">
    <div class="header-box">
        <div>
            <div style="font-size: 15px; font-weight: 800; color: #0f172a;">INSTITUTO DE PREVISIÓN SOCIAL (IPS)</div>
            <div style="font-size: 12px; color: #0d9488; font-weight: 700;">DIRECCIÓN DE PLANIFICACIÓN — UNIDAD DE REGULACIÓN FARMACÉUTICA</div>
            <div style="font-size: 11px; color: #64748b;">Política RIISS — Redes Integradas e Integrales de Servicios de Salud</div>
        </div>
        <div style="text-align: right; font-size: 11.5px;">
            <div><strong>DICTAMEN TÉCNICO</strong></div>
            <div style="color: #64748b;">Fecha: {{ optional($validacion->firmado_at ?? now())->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="title-box">
        <div style="font-size: 14px; font-weight: 800; color: #0f172a; text-transform: uppercase;">
            HOMOLOGACIÓN Y PERTINENCIA DE MEDICAMENTOS DEL VADEMÉCUM OFICIAL
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #0d9488; margin-top: 4px;">
            ESPECIALIDAD MÉDICA: {{ $especialidad->nombre }}
        </div>
    </div>

    {{-- Datos del Dictamen --}}
    <table class="data-table mb-3">
        <tr>
            <th style="width: 25%;">Profesional Evaluador/a:</th>
            <td style="width: 35%; font-weight: 700;">{{ $validacion->firmado_por ?? $sesion->analista_nombre }}</td>
            <th style="width: 20%;">Matrícula / C.I.:</th>
            <td style="width: 20%;">{{ $validacion->firmado_matricula ?? $sesion->matricula_profesional }} / {{ $validacion->firmado_documento ?? $sesion->analista_documento }}</td>
        </tr>
        <tr>
            <th>Dependencia Oficial:</th>
            <td>{{ $sesion->analista_cargo ?? 'Unidad de Regulación Farmacéutica' }}</td>
            <th>Instrumento Base:</th>
            <td>Vademécum IPS (Aprobado por Consejo)</td>
        </tr>
    </table>

    {{-- Observaciones Técnicas --}}
    <div class="section-title">1. Criterio Farmacológico y Observaciones Técnicas</div>
    <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; font-size: 12px; margin-bottom: 14px;">
        {{ $validacion->observaciones_tecnicas ?: 'Se dictamina la conformidad técnica de los medicamentos autorizados para la especialidad médica conforme a la Resolución del Consejo de Administración y los protocolos terapéuticos de la institución.' }}
    </div>

    {{-- Detalle de Medicamentos Evaluados --}}
    <div class="section-title">2. Nómina de Medicamentos Evaluados y Dictamen</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">Cód.</th>
                <th style="width: 38%;">Medicamento / Principio Activo</th>
                <th style="width: 24%;">Concentración / Forma</th>
                <th style="width: 15%;" class="text-center">Dictamen</th>
                <th style="width: 15%;">Justificación / Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicamentos as $m)
                @php
                    $d = $dictamenes->get($m->id);
                    $st = $d ? $d->estado_validacion : 'validado';
                @endphp
                <tr>
                    <td class="text-center font-monospace" style="font-size: 11px;">{{ $m->codigo ?: '—' }}</td>
                    <td style="font-weight: 600;">{{ $m->nombre }}</td>
                    <td>{{ $m->concentracion }} {{ $m->forma_farmaceutica }}</td>
                    <td class="text-center">
                        @if($st === 'validado')
                            <span class="badge-ok">✅ APROBADO</span>
                        @elseif($st === 'invalidado')
                            <span class="badge-no">⛔ INVALIDADO</span>
                        @else
                            <span class="badge-ok">⭐ INCORPORADO</span>
                        @endif
                    </td>
                    <td style="font-size: 10.5px; color: {{ $st === 'invalidado' ? '#b91c1c' : '#475569' }};">
                        {{ $d ? $d->justificacion : 'Conforme a norma' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Firma Digital --}}
    <div class="signature-box">
        <div class="signature-card">
            @if(!empty($validacion->firma_digital))
                <img src="{{ $validacion->firma_digital }}" style="max-height: 70px; max-width: 240px; display: block; margin: 0 auto 6px auto;" alt="Firma Digital">
            @else
                <div style="height: 50px;"></div>
            @endif
            <div style="font-weight: 700; font-size: 12.5px;">{{ $validacion->firmado_por ?? $sesion->analista_nombre }}</div>
            <div style="font-size: 11px; color: #64748b;">{{ $sesion->analista_cargo ?? 'Unidad de Regulación Farmacéutica' }}</div>
            <div style="font-size: 10.5px; color: #94a3b8;">Matrícula Prof.: {{ $validacion->firmado_matricula ?? $sesion->matricula_profesional }}</div>
        </div>
    </div>
</div>

</body>
</html>
