<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Técnico — {{ $proceso->nombre }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-size: 12.5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; background-color: #ffffff; }
        .report-header { border-bottom: 3px solid #0284c7; padding-bottom: 14px; margin-bottom: 22px; }
        .box-section { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
        .badge-danger-custom { background-color: #ef4444; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 0.78rem; }
        .badge-success-custom { background-color: #10b981; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 0.78rem; }
        .table-steps th { background-color: #0f172a; color: #fff; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; }
        .table-steps td { font-size: 11.5px; vertical-align: middle; }
        .signature-box { border-top: 1px dashed #94a3b8; margin-top: 45px; padding-top: 12px; text-align: center; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; }
            .box-section, .card { border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
            .table-steps th { background-color: #0f172a !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-danger-custom { background-color: #ef4444 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-success-custom { background-color: #10b981 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .signature-box { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-white p-4">

    <!-- Botones de Acción (no imprimibles) -->
    <div class="no-print mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
        <span class="text-dark font-weight-bold"><i class="fas fa-file-pdf text-danger mr-2"></i> Vista Previa del Reporte Técnico de Relevamiento</span>
        <div>
            <button onclick="window.print()" class="btn btn-primary font-weight-bold shadow-sm mr-2">
                <i class="fas fa-print mr-1"></i> Imprimir Reporte / Guardar PDF
            </button>
            <button onclick="window.close()" class="btn btn-secondary shadow-sm">Cerrar</button>
        </div>
    </div>

    <!-- Encabezado Institucional con Branding SIPLAN GO -->
    <div class="report-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                @php
                    $sysLogoUrl = \App\Models\HomeConfiguration::getSetting('logo', null);
                @endphp
                @if($sysLogoUrl)
                    <img src="{{ $sysLogoUrl }}" alt="SIPLAN GO" style="max-height: 52px; width: auto; object-fit: contain;">
                @else
                    <div class="px-3 py-2 bg-primary text-white font-weight-bold rounded shadow-sm d-inline-block" style="font-size: 1.35rem; letter-spacing: -0.5px;">
                        SIPLAN <span style="color:#38bdf8; font-weight:900;">GO</span>
                    </div>
                @endif
            </div>
            <div>
                <h5 class="font-weight-bold text-dark mb-0" style="letter-spacing: -0.3px;">INSTITUTO DE PREVISIÓN SOCIAL (IPS)</h5>
                <h6 class="font-weight-bold text-primary mb-0" style="font-size: 0.95rem;">
                    {{ $proceso->organigrama ? $proceso->organigrama->dependency : 'Área / Dependencia Relevada' }}
                </h6>
                <small class="text-muted">SIPLAN — Sistema de Planificación Estratégica Institucional</small>
            </div>
        </div>
        <div class="text-right">
            <span class="badge badge-primary px-3 py-2 font-weight-bold" style="font-size: 0.85rem;">REPORTE TÉCNICO</span>
            <div class="small text-muted mt-1"><strong>Emisión:</strong> {{ date('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Título del Documento -->
    <div class="text-center my-3">
        <h4 class="font-weight-bold text-dark uppercase mb-1">{{ $proceso->nombre }}</h4>
        <div class="font-weight-bold text-secondary">
            Servicio / Establecimiento: {{ $proceso->organigrama ? $proceso->organigrama->dependency : 'Servicio General' }}
        </div>
    </div>

    <!-- Metadatos de la Visita y Contexto -->
    <div class="box-section">
        <div class="row">
            <div class="col-md-6 mb-2">
                <strong>📌 Contexto / Móvil de la Visita:</strong>
                <p class="mb-0 text-muted">{{ $proceso->contexto_motivo ?: 'Relevamiento operativo regular de tiempos y cuellos de botella.' }}</p>
            </div>
            <div class="col-md-6 mb-2">
                <strong>🎯 Acción del PEI Vinculada:</strong>
                <p class="mb-0 text-muted">
                    @if($proceso->peiProfile)
                        @php
                            $cleanPeiName = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($proceso->peiProfile->name, ENT_QUOTES, 'UTF-8'))));
                        @endphp
                        [{{ mb_strtoupper($proceso->peiProfile->getLabelNivel()) }}] {{ $cleanPeiName }}
                    @else
                        Sin vinculación PEI registrada
                    @endif
                </p>
            </div>
        </div>
        <hr class="my-2">
        <div class="row">
            <div class="col-md-8">
                <strong>👥 Equipo Relevador (Responsables de la Visita):</strong>
                <div class="mt-1">
                    @forelse($proceso->responsables as $resp)
                        <span class="badge badge-secondary p-1 mr-1">👤 {{ $resp->name }} ({{ $resp->email }})</span>
                    @empty
                        <span class="text-muted">No registrados</span>
                    @endforelse
                </div>
            </div>
            <div class="col-md-4 text-right">
                <strong>🗓️ Fecha Relevamiento:</strong> {{ $proceso->fecha_relevamiento ? $proceso->fecha_relevamiento->format('d/m/Y') : date('d/m/Y') }}
            </div>
        </div>
    </div>

    <!-- Resumen Ejecutivo de Métricas -->
    <div class="row mb-3">
        <div class="col-3 text-center">
            <div class="border rounded p-2 bg-light">
                <small class="text-muted font-weight-bold d-block">LEAD TIME TOTAL</small>
                <strong class="h4 text-dark mb-0">{{ $proceso->lead_time_total }} min</strong>
                <div class="small text-muted">({{ round($proceso->lead_time_total / 60, 1) }} horas)</div>
            </div>
        </div>
        <div class="col-3 text-center">
            <div class="border rounded p-2 bg-light">
                <small class="text-muted font-weight-bold d-block">ATENCIÓN EFECTIVA</small>
                <strong class="h4 text-success mb-0">{{ $proceso->tiempo_atencion_total }} min</strong>
                <div class="small text-muted">Valor agregado</div>
            </div>
        </div>
        <div class="col-3 text-center">
            <div class="border rounded p-2 bg-light">
                <small class="text-muted font-weight-bold d-block">TIEMPO ESPERA</small>
                <strong class="h4 text-danger mb-0">{{ $proceso->tiempo_espera_total }} min</strong>
                <div class="small text-muted">Tiempo muerto en cola</div>
            </div>
        </div>
        <div class="col-3 text-center">
            <div class="border rounded p-2 bg-light">
                <small class="text-muted font-weight-bold d-block">EFICIENCIA & CUELLOS</small>
                <strong class="h4 text-info mb-0">{{ $proceso->eficiencia }}%</strong>
                <div class="small text-danger font-weight-bold">{{ $proceso->conteo_cuellos_botella }} cuello(s) crítico(s)</div>
            </div>
        </div>
    </div>

    <!-- Diagnóstico de Inteligencia Artificial (IA) -->
    <div class="card mb-4 border-info">
        <div class="card-header bg-info text-white font-weight-bold p-2">
            🤖 Informe de Diagnóstico Asistido por Inteligencia Artificial (IA)
        </div>
        <div class="card-body p-3 bg-light">
            {!! \Illuminate\Support\Str::markdown($proceso->analisis_ia ?: 'Sin informe de IA.') !!}
        </div>
    </div>

    <!-- Tabla Detallada del Paso a Paso -->
    <h5 class="font-weight-bold text-dark mb-2">📋 Detalle de Estaciones del Circuito del Paciente</h5>
    <table class="table table-bordered table-sm table-steps mb-4">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Estación / Paso</th>
                <th>Área / Dependencia</th>
                <th>Rol Responsable</th>
                <th class="text-center">Atención</th>
                <th class="text-center">Espera</th>
                <th>Sistema</th>
                <th>Estado</th>
                <th>Propuesta de Mejora</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proceso->pasos as $paso)
                <tr class="{{ $paso->es_cuello_botella ? 'table-danger' : '' }}">
                    <td class="font-weight-bold text-center">{{ $paso->orden }}</td>
                    <td>
                        <strong>{{ $paso->nombre }}</strong>
                        @if($paso->descripcion)<br><small class="text-muted">{{ $paso->descripcion }}</small>@endif
                    </td>
                    <td>{{ $paso->area_nombre }}</td>
                    <td>{{ $paso->rol_responsable ?: 'N/A' }}</td>
                    <td class="text-center font-weight-bold text-success">{{ $paso->tiempo_atencion_min }}m</td>
                    <td class="text-center font-weight-bold text-danger">{{ $paso->tiempo_espera_min }}m</td>
                    <td>{{ $paso->herramienta_sistema ?: 'Manual' }}</td>
                    <td class="text-center">
                        @if($paso->es_cuello_botella)
                            <span class="badge-danger-custom">🚨 CUELLO BOTELLA</span>
                        @else
                            <span class="badge-success-custom">OK</span>
                        @endif
                    </td>
                    <td><small>{{ $paso->propuesta_mejora ?: 'Sin observaciones' }}</small></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Firma de Responsables de la Visita (Dinámicas por Grupo de Trabajo & Firma Digital) -->
    <div class="row mt-5" style="page-break-inside: avoid;">
        @forelse($proceso->responsables as $resp)
            @php
                $depNombre = $resp->grupo_padre ? $resp->grupo_padre->name : ($resp->organigrama ? $resp->organigrama->dependency : null);
                if (!$depNombre && method_exists($resp, 'groups') && $resp->groups->isNotEmpty()) {
                    $depNombre = $resp->groups->pluck('name')->implode(' / ');
                }
                $cargoLabel = $depNombre ?: 'Equipo Relevador de Campo';
            @endphp
            <div class="col-6 mb-4" style="page-break-inside: avoid;">
                <div class="signature-box position-relative">
                    @if($resp->pivot->firma_digital)
                        <div class="mb-2">
                            <img src="{{ $resp->pivot->firma_digital }}" style="max-height: 65px; width: auto;" alt="Firma Digital {{ $resp->name }}">
                        </div>
                        <div class="small text-success font-weight-bold mb-1" style="font-size: 0.72rem;">
                            <i class="fas fa-shield-alt mr-1"></i> Firma Digital Sellada ({{ \Carbon\Carbon::parse($resp->pivot->firmado_at)->format('d/m/Y H:i') }})
                        </div>
                    @else
                        <div class="my-2 text-muted font-italic" style="font-size: 0.78rem;">
                            (Pendiente de Firma Digital en SIPLAN)
                        </div>
                    @endif
                    <strong style="font-size: 0.95rem; color: #0f172a;">{{ $resp->name }}</strong><br>
                    <small class="text-muted d-block">{{ $resp->email }}</small>
                    <small class="font-weight-bold text-primary d-block mt-1">{{ $cargoLabel }}</small>
                </div>
            </div>
        @empty
            <div class="col-6 offset-3" style="page-break-inside: avoid;">
                <div class="signature-box">
                    <strong style="font-size: 1rem;">Equipo de Relevamiento Técnico de Campo</strong><br>
                    <small class="text-muted">{{ $proceso->organigrama ? $proceso->organigrama->dependency : 'Instituto de Previsión Social (IPS)' }}</small>
                </div>
            </div>
        @endforelse
    </div>

</body>
</html>
