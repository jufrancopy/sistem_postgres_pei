<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Técnico — {{ $proceso->nombre }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { font-size: 13px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #222; }
        .header-logo { height: 60px; }
        .report-header { border-bottom: 3px solid #0056b3; padding-bottom: 12px; margin-bottom: 20px; }
        .box-section { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 14px; margin-bottom: 18px; }
        .badge-danger-custom { background-color: #dc3545; color: #fff; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .badge-success-custom { background-color: #28a745; color: #fff; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .table-steps th { background-color: #343a40; color: #fff; font-size: 11px; text-transform: uppercase; }
        .table-steps td { font-size: 11px; vertical-align: middle; }
        .signature-box { border-top: 1px dashed #666; margin-top: 50px; padding-top: 10px; text-align: center; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body class="bg-white p-4">

    <!-- Botones de Acción (no imprimibles) -->
    <div class="no-print mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
        <span class="text-dark font-weight-bold"><i class="fas fa-file-pdf text-danger mr-1"></i> Vista Previa del Reporte Técnico de Relevamiento</span>
        <div>
            <button onclick="window.print()" class="btn btn-primary font-weight-bold shadow-sm mr-2">
                <i class="fas fa-print mr-1"></i> Imprimir Reporte / Guardar PDF
            </button>
            <button onclick="window.close()" class="btn btn-secondary shadow-sm">Cerrar</button>
        </div>
    </div>

    <!-- Encabezado Institucional -->
    <div class="report-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="font-weight-bold text-primary mb-1">INSTITUTO DE PREVISIÓN SOCIAL (IPS)</h4>
            <h6 class="font-weight-bold text-dark mb-0">Dirección de Planificación / Dirección de Organización y Calidad (DOC)</h6>
            <small class="text-muted">SIPLAN — Sistema de Planificación Estratégica Institucional</small>
        </div>
        <div class="text-right">
            <span class="badge badge-info px-3 py-2 font-weight-bold" style="font-size:1rem;">REPORTE TÉCNICO</span>
            <div class="small text-muted mt-1">Fecha: {{ date('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Título del Documento -->
    <div class="text-center my-3">
        <h4 class="font-weight-bold text-dark uppercase mb-1">{{ $proceso->nombre }}</h4>
        <div class="font-weight-bold text-secondary">
            Servicio / Establecimiento: {{ $proceso->organigrama ? $proceso->organigrama->nombre : 'Servicio General' }}
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
                        [{{ strtoupper($proceso->peiProfile->level) }}] {{ $proceso->peiProfile->name }}
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
            🤖 Informe de Diagnóstico Asistido por Inteligencia Artificial (IA Planificación & DOC)
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
                <th>Propuesta de Mejora Sugerida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proceso->pasos as $paso)
                <tr class="{{ $paso->es_cuello_botella ? 'table-danger' : '' }}">
                    <td class="text-center font-weight-bold">{{ $paso->orden }}</td>
                    <td>
                        <strong>{{ $paso->nombre }}</strong>
                        @if($paso->descripcion)<br><small class="text-muted">{{ $paso->descripcion }}</small>@endif
                    </td>
                    <td>{{ $paso->organigrama ? $paso->organigrama->nombre : 'General' }}</td>
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

    <!-- Firma de Responsables -->
    <div class="row mt-5">
        @forelse($proceso->responsables as $resp)
            <div class="col-6 mb-4">
                <div class="signature-box">
                    <strong>{{ $resp->name }}</strong><br>
                    <small class="text-muted">{{ $resp->email }}</small><br>
                    <small class="font-weight-bold">Equipo Relevador de Visita (Planificación / DOC)</small>
                </div>
            </div>
        @empty
            <div class="col-6 offset-3">
                <div class="signature-box">
                    <strong>Dirección de Planificación & Dirección de Organización y Calidad</strong><br>
                    <small class="text-muted">Instituto de Previsión Social (IPS)</small>
                </div>
            </div>
        @endforelse
    </div>

</body>
</html>
