<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
    <title>Portal de Auditoría - {{ $est->nombre_oficial }} | RIISS IPS</title>
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Bootstrap 4.5.2 & DataTables CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <!-- Leaflet Maps CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --secondary: #475569;
            --bg-page: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* Barra Superior */
        .top-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 0.75rem 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .hero-banner {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            padding: 1.5rem 1rem;
            position: relative;
        }

        @media (min-width: 768px) {
            .top-navbar { padding: 0.85rem 1.5rem; }
            .hero-banner { padding: 2.25rem 1.5rem 2rem; }
        }

        .stat-card {
            background: white;
            border-radius: 0.85rem;
            padding: 0.85rem 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        @media (min-width: 768px) {
            .stat-card {
                padding: 1.25rem;
                border-radius: 1rem;
            }
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        @media (min-width: 768px) {
            .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 1.4rem;
                border-radius: 0.75rem;
            }
        }

        .stat-val {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        @media (min-width: 768px) {
            .stat-val { font-size: 1.5rem; }
        }

        /* Pestañas */
        .custom-tabs {
            border-bottom: none;
            gap: 0.5rem;
        }
        .custom-tabs .nav-link {
            font-weight: 700;
            color: #475569;
            border: none;
            padding: 0.65rem 1rem;
            border-radius: 0.75rem;
            background: white;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            font-size: 0.88rem;
        }
        @media (min-width: 768px) {
            .custom-tabs .nav-link {
                padding: 0.85rem 1.5rem;
                font-size: 0.95rem;
            }
        }
        .custom-tabs .nav-link.active {
            background: #0284c7 !important;
            color: white !important;
            border-color: #0284c7;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        }

        /* Tarjetas de Especialidad */
        .esp-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            margin-bottom: 0.85rem;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .esp-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        }
        .esp-header-btn {
            width: 100%;
            background: #f8fafc;
            border: none;
            padding: 0.85rem 1rem;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            outline: none !important;
        }
        @media (min-width: 768px) {
            .esp-header-btn {
                padding: 1rem 1.25rem;
            }
        }
        .esp-header-btn.cronico {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
        }

        /* Botones de acción */
        .btn-action-pdf {
            border-radius: 0.65rem;
            font-weight: 700;
            padding: 0.5rem 0.85rem;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        @media (min-width: 768px) {
            .btn-action-pdf {
                padding: 0.6rem 1.1rem;
                font-size: 0.88rem;
            }
        }

        /* DataTables responsive adjustments */
        table.dataTable tbody td {
            vertical-align: middle;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            padding: 0.35rem 0.65rem;
            outline: none;
            width: 100% !important;
            max-width: 350px;
        }
        @media (max-width: 767px) {
            .dataTables_wrapper .dataTables_filter {
                text-align: left !important;
                margin-top: 0.5rem;
            }
            .dataTables_wrapper .dataTables_filter input {
                max-width: 100%;
                width: 100% !important;
                margin-left: 0 !important;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
            .hero-banner {
                background: #0284c7 !important;
                color: black !important;
            }
            .stat-card, .esp-card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
            }
        }
    </style>
</head>
<body>

<!-- Barra Superior -->
<nav class="top-navbar no-print">
    <div class="container-fluid px-2 px-md-3 d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
        <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
            @if(!empty($es_global))
                <a href="{{ $url_volver_red ?? '#' }}" class="btn btn-sm btn-outline-light font-weight-bold mr-1" style="border-radius: 20px; font-size: 0.78rem;">
                    <i class="fas fa-arrow-left mr-1"></i> <span class="d-none d-sm-inline">Volver a Toda la Red</span><span class="d-inline d-sm-none">Red</span>
                </a>
            @endif
            <span class="badge badge-light px-2 py-1 text-dark font-weight-bold" style="font-size: 0.78rem;">
                <i class="fas fa-shield-alt text-primary mr-1"></i> AUDITORÍA
            </span>
            <span class="font-weight-bold text-white d-none d-md-inline" style="font-size: 0.9rem;">
                Instituto de Previsión Social (IPS) • RIISS
            </span>
        </div>
        <div class="d-flex align-items-center" style="gap: 6px;">
            <span class="badge badge-warning px-2 py-1 text-dark font-weight-bold" style="border-radius: 20px; font-size: 0.78rem;">
                <i class="fas fa-hourglass-half mr-1"></i> Quedan {{ $tokenRecord->tiempo_restante_texto }}
            </span>
            <button onclick="window.print()" class="btn btn-sm btn-outline-light font-weight-bold d-none d-md-inline-block" style="border-radius: 20px;">
                <i class="fas fa-print mr-1"></i> Imprimir
            </button>
        </div>
    </div>
</nav>

<!-- Hero Banner con Ficha Principal -->
<div class="hero-banner">
    <div class="container-fluid px-2 px-md-3">
        <div class="row align-items-center m-0">
            <div class="col-lg-8 p-0">
                <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 0.4rem;">
                    @if(!empty($es_global))
                        <a href="{{ $url_volver_red ?? '#' }}" class="badge badge-warning px-2 py-1 font-weight-bold text-dark text-decoration-none" style="font-size: 0.75rem;">
                            <i class="fas fa-th-list mr-1"></i> Directorio Nacional
                        </a>
                    @endif
                    <span class="badge badge-dark px-2 py-1 text-white" style="font-size: 0.75rem; letter-spacing: 0.05em; background: rgba(0,0,0,0.35);">
                        ID: {{ $est->id_establecimiento }}
                    </span>
                    <span class="badge badge-light px-2 py-1 font-weight-bold text-dark" style="font-size: 0.75rem;">
                        <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $est->departamento ?? 'Paraguay' }}
                    </span>
                    @if($est->complejidad)
                        <span class="badge badge-info px-2 py-1 font-weight-bold text-white" style="font-size: 0.75rem;">
                            {{ $est->complejidad }}
                        </span>
                    @endif
                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 0.75rem;">
                        <i class="fas fa-check-circle mr-1"></i> Solo Lectura
                    </span>
                </div>
                
                <h2 class="font-weight-800 text-white mb-1" style="font-size: 1.45rem; letter-spacing: -0.02em;">
                    {{ $est->nombre_oficial }}
                </h2>
                <div class="text-light small" style="opacity: 0.95; line-height: 1.4;">
                    <i class="fas fa-hospital-alt mr-1"></i> {{ $est->tipo_est ?? 'Establecimiento de Salud' }}
                    @if($est->microred)
                        • Microred: <strong>{{ $est->microred }}</strong>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas de Descarga -->
            <div class="col-lg-4 mt-3 mt-lg-0 text-lg-right p-0 no-print">
                <div class="d-flex flex-column flex-sm-row flex-lg-column align-items-stretch justify-content-end" style="gap: 8px;">
                    <a href="{{ route('riiss.portal-auditor.pdf', ['token' => $tokenRecord->token, 'est_id' => $est->id_establecimiento, 'tipo' => 'consolidado']) }}" target="_blank" class="btn btn-light btn-action-pdf shadow-sm text-dark">
                        <i class="fas fa-file-pdf text-danger fa-lg"></i>
                        <div class="text-left" style="line-height: 1.2;">
                            <div class="font-weight-bold">Planilla Auditoría Farmacia</div>
                            <small class="text-muted" style="font-size: 0.7rem;">Consolidado / Checklist con firmas</small>
                        </div>
                    </a>
                    <a href="{{ route('riiss.portal-auditor.pdf', ['token' => $tokenRecord->token, 'est_id' => $est->id_establecimiento, 'tipo' => 'especialidad']) }}" target="_blank" class="btn btn-outline-light btn-action-pdf">
                        <i class="fas fa-stethoscope fa-lg"></i>
                        <div class="text-left" style="line-height: 1.2;">
                            <div class="font-weight-bold">Reporte por Especialidad</div>
                            <small style="opacity: 0.8; font-size: 0.7rem;">Desglose médico detallado</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenedor Principal -->
<div class="container-fluid px-2 px-md-3 py-3 py-md-4">

    <!-- KPI Cards (Grid 2x2 en móvil / 4 columnas en desktop) -->
    <div class="row mb-3 mb-md-4">
        <div class="col-6 col-lg-3 mb-2 mb-lg-0 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Vademécum Único</div>
                    <div class="stat-val text-primary">{{ $totalMedicamentosUnicos }}</div>
                    <div class="d-none d-md-block" style="font-size: 0.72rem; color: #0284c7; font-weight: 600;">Medicamentos sin duplicar</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-2 mb-lg-0 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #dcfce7; color: #16a34a;">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Especialidades</div>
                    <div class="stat-val text-success">{{ $totalEspecialidades }}</div>
                    <div class="d-none d-md-block" style="font-size: 0.72rem; color: #16a34a; font-weight: 600;">Servicios en Cartera</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #fef3c7; color: #d97706;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Asignaciones</div>
                    <div class="stat-val text-warning">{{ $totalAsignaciones }}</div>
                    <div class="d-none d-md-block" style="font-size: 0.72rem; color: #d97706; font-weight: 600;">Líneas por especialidad</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 px-2">
            <div class="stat-card d-flex align-items-center">
                <div class="stat-icon mr-2 mr-md-3" style="background-color: #f1f5f9; color: #475569;">
                    <i class="fas fa-building"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #64748b;" class="text-truncate">Inmueble</div>
                    <div class="stat-val text-dark text-truncate" style="font-size: 0.95rem;">{{ $est->condicion_inmueble ?? 'No especificado' }}</div>
                    <div class="d-none d-md-block" style="font-size: 0.7rem; color: #64748b;">
                        Sup: {{ $est->superficie_construida ? number_format($est->superficie_construida, 0, ',', '.') . ' m²' : 'S/D' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegación por Pestañas -->
    <ul class="nav custom-tabs mb-4 no-print" id="auditorTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tab-vademecum-link" data-toggle="tab" href="#tab-vademecum" role="tab">
                <i class="fas fa-boxes mr-2"></i> 1. Vademécum Único / Farmacia ({{ $totalMedicamentosUnicos }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-especialidades-link" data-toggle="tab" href="#tab-especialidades" role="tab">
                <i class="fas fa-stethoscope mr-2"></i> 2. Cartera por Especialidad ({{ $totalEspecialidades }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-ficha-link" data-toggle="tab" href="#tab-ficha" role="tab">
                <i class="fas fa-info-circle mr-2"></i> 3. Ficha Técnica e Inmueble
            </a>
        </li>
    </ul>

    <!-- Contenido de Pestañas -->
    <div class="tab-content" id="auditorTabsContent">
        
        <!-- PESTAÑA 1: VADEMÉCUM CONSOLIDADO (DATATABLES) -->
        <div class="tab-pane fade show active" id="tab-vademecum" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-header bg-white border-bottom p-3 d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">
                            <i class="fas fa-pills text-primary mr-2"></i> Vademécum Consolidado de Medicamentos
                        </h5>
                        <p class="text-muted small mb-0">
                            Listado unificado sin duplicados. Use el buscador para filtrar en tiempo real por código, descripción o especialidad.
                        </p>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table id="tblAuditorMedicamentos" class="table table-hover table-striped w-100" style="font-size: 0.88rem;">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%; text-align: center;">#</th>
                                    <th style="width: 15%;">Código</th>
                                    <th style="width: 45%;">Descripción del Medicamento / Presentación</th>
                                    <th style="width: 35%;">Especialidades que lo Utilizan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicamentosConsolidados as $idx => $med)
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">{{ $idx + 1 }}</td>
                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-family: monospace; font-size: 0.85rem;">
                                                {{ $med['codigo'] }}
                                            </span>
                                        </td>
                                        <td class="font-weight-600 text-dark">
                                            {{ $med['nombre'] }}
                                        </td>
                                        <td>
                                            @foreach($med['especialidades'] as $espNombre)
                                                <span class="badge badge-primary px-2 py-1 mr-1 mb-1 font-weight-500" style="border-radius: 4px; font-size: 0.78rem; background-color: #0284c7;">
                                                    {{ $espNombre }}
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESTAÑA 2: CARTERA DE SERVICIOS POR ESPECIALIDAD -->
        <div class="tab-pane fade" id="tab-especialidades" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-lg p-3 mb-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">
                            <i class="fas fa-stethoscope text-primary mr-2"></i> Cartera de Servicios Médicos
                        </h5>
                        <p class="text-muted small mb-0">Haga clic en cualquier especialidad para expandir o colapsar su listado de medicamentos asignados.</p>
                    </div>
                    <div class="d-flex" style="gap: 8px;">
                        <button type="button" id="btnExpandirTodos" class="btn btn-sm btn-outline-secondary font-weight-bold">
                            <i class="fas fa-expand-alt mr-1"></i> Expandir Todos
                        </button>
                        <button type="button" id="btnColapsarTodos" class="btn btn-sm btn-outline-secondary font-weight-bold">
                            <i class="fas fa-compress-alt mr-1"></i> Colapsar Todos
                        </button>
                    </div>
                </div>
            </div>

            <!-- Listado de bloques de especialidades -->
            <div id="contenedorEspecialidadesAuditor">
                @forelse($carteraServicios as $sIdx => $servicio)
                    @php
                        $isCronico = stripos($servicio['nombre'], 'crónico') !== false || stripos($servicio['nombre'], 'cronico') !== false;
                        $collapseId = 'collapseAuditorEsp_' . $sIdx;
                    @endphp
                    <div class="esp-card">
                        <button type="button" class="esp-header-btn {{ $isCronico ? 'cronico' : '' }}" data-toggle="collapse" data-target="#{{ $collapseId }}">
                            <div class="d-flex align-items-center">
                                @if($isCronico)
                                    <div class="stat-icon mr-2" style="width: 32px; height: 32px; background: #dbeafe; color: #1d4ed8; font-size: 0.9rem;">
                                        <i class="fas fa-heartbeat"></i>
                                    </div>
                                @else
                                    <div class="stat-icon mr-2" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7; font-size: 0.9rem;">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                @endif
                                <span class="font-weight-bold text-dark" style="font-size: 0.95rem; text-transform: uppercase;">
                                    {{ $servicio['nombre'] }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge {{ $servicio['total_meds'] > 0 ? 'badge-info' : 'badge-secondary' }} px-3 py-1 mr-2" style="border-radius: 20px; font-size: 0.8rem;">
                                    {{ $servicio['total_meds'] }} Medicamentos
                                </span>
                                <i class="fas fa-chevron-down text-muted small"></i>
                            </div>
                        </button>

                        <div id="{{ $collapseId }}" class="collapse show">
                            <div class="p-0 border-top bg-white">
                                @if(count($servicio['medicamentos']) > 0)
                                    <div class="table-responsive mb-0">
                                        <table class="table table-sm table-hover table-striped mb-0" style="font-size: 0.85rem;">
                                            <thead style="background-color: #f1f5f9;">
                                                <tr>
                                                    <th style="width: 7%; text-align: center; color: #475569;">#</th>
                                                    <th style="width: 23%; color: #475569;">Código</th>
                                                    <th style="width: 70%; color: #475569;">Descripción del Medicamento / Presentación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($servicio['medicamentos'] as $mIdx => $mItem)
                                                    <tr>
                                                        <td class="text-center text-muted" style="font-size: 0.8rem;">{{ $mIdx + 1 }}</td>
                                                        <td>
                                                            <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-family: monospace;">
                                                                {{ $mItem['codigo'] ?: 'S/C' }}
                                                            </span>
                                                        </td>
                                                        <td class="font-weight-500 text-dark">{{ $mItem['nombre'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="p-3 text-muted small font-italic text-center">
                                        No hay medicamentos específicos registrados para este servicio.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning text-center">No se encontraron servicios ni medicamentos registrados.</div>
                @endforelse
            </div>
        </div>

        <!-- PESTAÑA 3: FICHA TÉCNICA E INMUEBLE -->
        <div class="tab-pane fade" id="tab-ficha" role="tabpanel">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <!-- Ubicación y Clasificación -->
                    <div class="card border-0 shadow-sm rounded-lg p-3 mb-3">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fas fa-hospital text-primary mr-2"></i> Identificación y Clasificación
                        </h6>
                        <table class="table table-sm table-borderless text-dark" style="font-size: 0.9rem;">
                            <tr>
                                <th style="width: 40%; color: #64748b;">Nombre Oficial:</th>
                                <td class="font-weight-bold">{{ $est->nombre_oficial }}</td>
                            </tr>
                            <tr>
                                <th style="color: #64748b;">Departamento:</th>
                                <td>{{ $est->departamento ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th style="color: #64748b;">Microred:</th>
                                <td>{{ $est->microred ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th style="color: #64748b;">Complejidad:</th>
                                <td><span class="badge badge-info">{{ $est->complejidad ?? 'RIISS' }}</span></td>
                            </tr>
                            <tr>
                                <th style="color: #64748b;">Nivel de Atención:</th>
                                <td>{{ $est->nivel_atencion ? 'Nivel ' . $est->nivel_atencion : 'No asignado' }}</td>
                            </tr>
                            <tr>
                                <th style="color: #64748b;">Tipología:</th>
                                <td>{{ $est->tipologia_clasificacion ?? 'No clasificado' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Situación del Inmueble -->
                    <div class="card border-0 shadow-sm rounded-lg p-3">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fas fa-building text-primary mr-2"></i> Situación del Inmueble
                        </h6>
                        <table class="table table-sm table-borderless text-dark" style="font-size: 0.9rem;">
                            <tr>
                                <th style="width: 40%; color: #64748b;">Condición:</th>
                                <td><span class="badge badge-dark font-weight-bold">{{ $est->condicion_inmueble ?? 'No especificado' }}</span></td>
                            </tr>
                            <tr>
                                <th style="color: #64748b;">Sup. Terreno:</th>
                                <td>{{ $est->superficie_terreno ? number_format($est->superficie_terreno, 0, ',', '.') . ' m²' : 'No cargada' }}</td>
                            </tr>
                            <tr>
                                <th style="color: #64748b;">Sup. Construida:</th>
                                <td>{{ $est->superficie_construida ? number_format($est->superficie_construida, 0, ',', '.') . ' m²' : 'No cargada' }}</td>
                            </tr>
                            @if($est->condicion_inmueble === 'ALQUILADO')
                                <tr>
                                    <th style="color: #64748b;">Propietario:</th>
                                    <td>{{ $est->propietario ?? 'No cargado' }}</td>
                                </tr>
                                <tr>
                                    <th style="color: #64748b;">Canon Mensual:</th>
                                    <td>{{ $est->canon_mensual ? number_format($est->canon_mensual, 0, ',', '.') . ' Gs.' : 'No cargado' }}</td>
                                </tr>
                            @elseif($est->condicion_inmueble === 'CONVENIO')
                                <tr>
                                    <th style="color: #64748b;">Nro. Resolución:</th>
                                    <td>{{ $est->nro_resolucion_convenio ?? 'No cargado' }}</td>
                                </tr>
                                <tr>
                                    <th style="color: #64748b;">Vigencia:</th>
                                    <td>{{ $est->vigencia_convenio_desde }} al {{ $est->vigencia_convenio_hasta }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Mapa Georreferenciado -->
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-lg p-3 h-100">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                            <h6 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-map-marked-alt text-primary mr-2"></i> Ubicación en el Mapa
                            </h6>
                            @if($est->latitude && $est->longitude)
                                <span class="badge badge-success px-2 py-1 font-weight-bold">
                                    <i class="fas fa-check-circle mr-1"></i> Georreferenciado
                                </span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">
                                    Sin coordenadas
                                </span>
                            @endif
                        </div>

                        @if($est->latitude && $est->longitude)
                            <div id="mapAuditorEstablecimiento" style="height: 380px; width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; z-index: 1;"></div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <span class="text-muted small">
                                    <i class="fas fa-map-pin text-danger mr-1"></i> Lat: {{ number_format($est->latitude, 6) }}, Lng: {{ number_format($est->longitude, 6) }}
                                </span>
                                <div class="btn-group">
                                    <a href="https://www.google.com/maps?q={{ $est->latitude }},{{ $est->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold">
                                        <i class="fas fa-external-link-alt mr-1"></i> Google Maps
                                    </a>
                                    <a href="https://waze.com/ul?ll={{ $est->latitude }},{{ $est->longitude }}&navigate=yes" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold">
                                        <i class="fab fa-waze mr-1"></i> Waze
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 p-5 text-center text-muted bg-light rounded">
                                <i class="fas fa-map-marked fa-3x mb-3 text-secondary" style="opacity: 0.5;"></i>
                                <h6 class="font-weight-bold">Coordenadas no registradas</h6>
                                <p class="small mb-0">Este establecimiento aún no cuenta con latitud y longitud cargadas en el sistema.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Footer -->
<footer class="bg-white border-top py-3 mt-5 no-print text-center text-muted small">
    <div class="container-fluid">
        Instituto de Previsión Social (IPS) • Dirección de Planificación / Redes Integradas e Integrales de Servicios de Salud (RIISS)
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#tblAuditorMedicamentos').DataTable({
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        language: {
            search: "🔍 Buscar medicamento / código / especialidad:",
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron medicamentos que coincidan",
            info: "Mostrando _START_ a _END_ de _TOTAL_ medicamentos",
            infoEmpty: "Mostrando 0 a 0 de 0 medicamentos",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        responsive: true
    });

    // Expandir / Colapsar todos
    $('#btnExpandirTodos').on('click', function() {
        $('#contenedorEspecialidadesAuditor .collapse').collapse('show');
    });

    $('#btnColapsarTodos').on('click', function() {
        $('#contenedorEspecialidadesAuditor .collapse').collapse('hide');
    });

    // MAPA LEAFLET
    var estLat = {{ ($est->latitude && $est->longitude) ? (float) $est->latitude : 'null' }};
    var estLng = {{ ($est->latitude && $est->longitude) ? (float) $est->longitude : 'null' }};
    var mapAuditor = null;

    function renderMapaAuditor() {
        if (mapAuditor !== null) return;
        if (!estLat || !estLng) return;
        var mapEl = document.getElementById('mapAuditorEstablecimiento');
        if (!mapEl) return;

        mapAuditor = L.map('mapAuditorEstablecimiento').setView([estLat, estLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(mapAuditor);

        var customIcon = L.divIcon({
            className: 'custom-map-pin',
            html: '<div style="background:#0284c7;color:white;width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 12px rgba(2,132,199,0.7);border:3px solid white;"><i class="fas fa-hospital fa-lg"></i></div>',
            iconSize: [38, 38],
            iconAnchor: [19, 19],
            popupAnchor: [0, -19]
        });

        var popupHtml = '<div style="font-family:\'Plus Jakarta Sans\',sans-serif;min-width:180px;">' +
            '<div style="font-weight:800;color:#0f172a;font-size:0.95rem;margin-bottom:2px;">{{ addslashes($est->nombre_oficial) }}</div>' +
            '<div style="font-size:0.8rem;color:#64748b;margin-bottom:6px;">{{ $est->departamento ?? "Paraguay" }} &bull; {{ $est->complejidad ?? "RIISS" }}</div>' +
            '<a href="https://www.google.com/maps?q=' + estLat + ',' + estLng + '" target="_blank" class="btn btn-sm btn-primary text-white font-weight-bold" style="font-size:0.75rem;padding:3px 8px;border-radius:4px;"><i class="fas fa-directions mr-1"></i> Cómo llegar</a>' +
            '</div>';

        L.marker([estLat, estLng], { icon: customIcon })
            .addTo(mapAuditor)
            .bindPopup(popupHtml)
            .openPopup();
    }

    // Ajustar columnas y mapa al cambiar de tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if (e.target.id === 'tab-vademecum-link') {
            table.columns.adjust().responsive.recalc();
        } else if (e.target.id === 'tab-ficha-link') {
            setTimeout(function() {
                renderMapaAuditor();
                if (mapAuditor) {
                    mapAuditor.invalidateSize();
                }
            }, 200);
        }
    });

    // Heartbeat ping cada 45 segundos para detectar auditor en línea
    function sendHeartbeatPing() {
        $.post('{{ route("riiss.portal-auditor.ping", ["token" => $tokenRecord->token]) }}', {
            _token: '{{ csrf_token() }}'
        });
    }
    setInterval(sendHeartbeatPing, 45000);
});
</script>

</body>
</html>
