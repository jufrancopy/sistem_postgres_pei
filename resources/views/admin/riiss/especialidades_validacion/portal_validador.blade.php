<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Validación de Especialidades Médicas — {{ $sesion->area_gestion ?? 'Área Interior' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --secondary: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-portal {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0369a1 100%);
            color: #ffffff;
            padding: 12px 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .portal-main-container {
            flex: 1;
            padding: 24px clamp(12px, 3vw, 32px);
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
        }

        /* Banner de Resumen Ejecutivo */
        .summary-hero {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            margin-bottom: 24px;
        }

        /* Pestañas Modernas */
        .nav-tabs-custom {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 20px;
            display: flex;
            gap: 8px;
        }
        .nav-tabs-custom .nav-link {
            border: none;
            color: #64748b;
            font-weight: 700;
            font-size: 13.5px;
            padding: 12px 20px;
            border-radius: 10px 10px 0 0;
            transition: all 0.2s ease;
            position: relative;
            background: transparent;
        }
        .nav-tabs-custom .nav-link:hover {
            color: #0284c7;
            background: #f1f5f9;
        }
        .nav-tabs-custom .nav-link.active {
            color: #0284c7;
            background: #ffffff;
            border-bottom: 3px solid #0284c7;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.03);
        }

        /* Tablas DataTables */
        .card-table-wrapper {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            padding: 20px;
            overflow: hidden;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        table.dataTable thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #cbd5e1 !important;
            padding: 12px 14px !important;
        }
        table.dataTable tbody td {
            padding: 12px 14px !important;
            vertical-align: middle !important;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0284c7 !important;
            color: #ffffff !important;
            border: 1px solid #0284c7 !important;
            border-radius: 6px;
        }
        .dataTables_filter input {
            border-radius: 8px !important;
            border: 1.5px solid #cbd5e1 !important;
            padding: 5px 12px !important;
            font-size: 13px !important;
        }

        /* Botones de Acción de Validación */
        .btn-val-group {
            display: inline-flex;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #cbd5e1;
        }
        .btn-val {
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            background: #ffffff;
            color: #64748b;
            transition: all 0.15s ease;
            cursor: pointer;
            outline: none !important;
        }
        .btn-val:hover {
            background: #f1f5f9;
        }
        .btn-val.active-val-validar {
            background-color: #10b981 !important;
            color: #ffffff !important;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.15);
        }
        .btn-val.active-val-inactivar {
            background-color: #ef4444 !important;
            color: #ffffff !important;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.15);
        }
        .row-inactiva {
            background-color: #fef2f2 !important;
        }
        .row-inactiva .esp-nombre-texto {
            text-decoration: line-through;
            color: #991b1b;
        }
        .row-activa {
            background-color: #ffffff;
        }
        .row-pendiente {
            background-color: #fffdf5;
        }

        .input-justificacion {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 12.5px;
            padding: 6px 10px;
            transition: all 0.2s;
            width: 100%;
            background: #ffffff;
        }
        .input-justificacion:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
            outline: none;
            background: #ffffff;
        }

        /* Lienzo de Firma Digital */
        .signature-pad-container {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            background-color: #fafafa;
            position: relative;
            cursor: crosshair;
        }
        .signature-pad-container canvas {
            width: 100%;
            height: 180px;
            display: block;
        }

        /* Tarjeta de Estadísticas de Validación */
        .stat-metric-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        }

        /* Sticky bottom action bar in workspace */
        .sticky-action-bar {
            position: sticky;
            bottom: 12px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 14px 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        /* FOOTER */
        .site-footer {
            background: #0f172a;
            color: rgba(255,255,255,.6);
            padding: 36px clamp(14px,4vw,40px) 24px;
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: auto;
        }
        .footer-inner {
            max-width: 1140px;
            margin: 0 auto;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 40px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .footer-brand-text .fb-name {
            font-size: 15px;
            font-weight: 800;
            color: #fff;
        }
        .footer-brand-text .fb-sub {
            font-size: 10px;
            color: rgba(255,255,255,.4);
        }
        .footer-desc {
            font-size: 12px;
            line-height: 1.7;
            max-width: 380px;
            color: rgba(255,255,255,.6);
        }
        .ai-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: rgba(255,255,255,.4);
            margin-bottom: 8px;
        }
        .ai-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 12px;
        }
        .ai-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 20px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,.7);
            text-decoration: none;
        }
        .ai-chip:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
            text-decoration: none;
        }
        .dev-credit {
            font-size: 10px;
            color: rgba(255,255,255,.35);
        }
        .dev-credit a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 600;
        }
        .footer-copy {
            text-align: center;
            font-size: 10px;
            color: rgba(255,255,255,.35);
        }
        @media (max-width: 768px) {
            .footer-grid { grid-template-columns: 1fr; gap: 24px; }
            .stats-grid-hero { grid-template-columns: repeat(2, 1fr) !important; }
        }
    </style>
</head>
<body>

    {{-- BARRA SUPERIOR INSTITUCIONAL --}}
    <nav class="navbar-portal d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            @if(!empty($logoInstitucional) || !empty($sysLogoUrl))
                <div class="bg-white rounded p-1 mr-3 shadow-sm d-flex align-items-center justify-content-center" style="height: 46px; min-width: 48px; max-width: 140px; border-radius: 8px;">
                    <img src="{{ $logoInstitucional ?: $sysLogoUrl }}" alt="Logo Institucional" style="max-height: 38px; max-width: 130px; object-fit: contain;">
                </div>
            @else
                <div class="bg-white text-dark font-weight-bold rounded px-2 py-1 mr-3 shadow-sm" style="font-size: 13px; color:#0284c7 !important;">
                    IPS
                </div>
            @endif
            <div>
                <div class="font-weight-bold text-white" style="font-size: 14.5px; letter-spacing: 0.3px;">
                    {{ $dependencia }}
                </div>
                <div style="font-size: 11.5px; color: #94a3b8;">
                    {{ $institucion }} · Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <div class="text-right mr-3 d-none d-md-block">
                <div class="font-weight-bold text-white" style="font-size: 13px;">
                    <i class="fa fa-user-circle mr-1 text-info"></i> {{ $sesion->analista_nombre }}
                </div>
                <div class="badge badge-light px-2 py-1 font-weight-bold text-dark" style="font-size: 11px;">
                    Código: {{ $sesion->codigo_acceso }}
                </div>
            </div>

            <a href="{{ route('riiss.portal-validador.acta-imprimir', $sesion->token) }}" target="_blank" class="btn btn-outline-light btn-sm shadow-sm font-weight-bold">
                <i class="fa fa-print mr-1"></i> Ver Acta General
            </a>
        </div>
    </nav>

    {{-- CONTENEDOR PRINCIPAL --}}
    <main class="portal-main-container">

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- VISTA 1: LISTADO DE ESTABLECIMIENTOS (2 PESTAÑAS DATATABLES)    --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div id="vistaListadoEstablecimientos">

            @php
                $totalEst = $establecimientos->count();
                $validadosCount = 0;
                $pendientesCount = 0;

                foreach($establecimientos as $estItem) {
                    $valEst = $validacionesEstablecimientos->get($estItem->id_establecimiento);
                    if ($valEst && $valEst->estado === 'validado') {
                        $validadosCount++;
                    } else {
                        $pendientesCount++;
                    }
                }
                $porcentajeAvance = $totalEst > 0 ? round(($validadosCount / $totalEst) * 100) : 0;
            @endphp

            {{-- Banner Hero de Resumen con Marco de la Política RIISS --}}
            <div class="summary-hero">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <div class="d-flex align-items-center flex-wrap mb-1" style="gap: 6px;">
                            <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 11px; background: linear-gradient(135deg, #0284c7, #0369a1);">
                                <i class="fa fa-network-wired mr-1"></i> Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)
                            </span>
                            <span class="badge badge-info px-2 py-1 text-uppercase font-weight-bold" style="font-size: 11px;">
                                <i class="fa fa-map-marked-alt mr-1"></i> {{ $sesion->area_gestion ?? 'Área Interior' }}
                            </span>
                        </div>
                        <h3 class="font-weight-800 text-dark mt-2 mb-1" style="letter-spacing: -0.3px;">
                            Relevamiento y Validación de Especialidades Médicas
                        </h3>
                        <p class="text-muted small mb-0" style="max-width: 780px; line-height: 1.55;">
                            Relevamiento técnico institucional desarrollado en el marco de la <strong>Política de Redes Integradas e Integrales de Servicios de Salud (RIISS)</strong> para la caracterización, confirmación y certificación de la cartera de especialidades médicas por establecimiento de salud.
                        </p>
                    </div>

                    <div class="mt-3 mt-md-0 d-flex align-items-center stats-grid-hero" style="gap: 16px;">
                        <div class="bg-light border rounded px-3 py-2 text-center" style="min-width: 110px;">
                            <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">Total Centros</div>
                            <div class="font-weight-bold text-dark" style="font-size: 20px;">{{ $totalEst }}</div>
                        </div>
                        <div class="bg-light border rounded px-3 py-2 text-center" style="min-width: 120px; border-left: 3px solid #f59e0b !important;">
                            <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">Por Validar</div>
                            <div class="font-weight-bold text-warning" id="countPendientesBanner" style="font-size: 20px; color: #d97706 !important;">{{ $pendientesCount }}</div>
                        </div>
                        <div class="bg-light border rounded px-3 py-2 text-center" style="min-width: 120px; border-left: 3px solid #10b981 !important;">
                            <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">Validados / Firmados</div>
                            <div class="font-weight-bold text-success" id="countValidadosBanner" style="font-size: 20px; color: #16a34a !important;">{{ $validadosCount }}</div>
                        </div>
                    </div>
                </div>

                {{-- Barra de Progreso --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                        <span class="font-weight-600 text-dark">Progreso Global de Validación</span>
                        <span class="font-weight-bold text-primary" id="porcentajeAvanceTexto">{{ $porcentajeAvance }}% completado</span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #e2e8f0;">
                        <div class="progress-bar bg-success" id="porcentajeAvanceBar" role="progressbar" style="width: {{ $porcentajeAvance }}%;" aria-valuenow="{{ $porcentajeAvance }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            {{-- Navegación de Pestañas --}}
            <ul class="nav nav-tabs nav-tabs-custom" id="tabsEstablecimientos" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-pendientes" data-toggle="tab" data-target="#content-pendientes" type="button" role="tab">
                        <i class="fa fa-clock mr-1 text-warning"></i> Establecimientos Pendientes / En Proceso
                        <span class="badge badge-warning ml-1 text-dark" id="badgeTabPendientes" style="border-radius: 10px; padding: 3px 8px;">{{ $pendientesCount }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-validados" data-toggle="tab" data-target="#content-validados" type="button" role="tab">
                        <i class="fa fa-check-circle mr-1 text-success"></i> Establecimientos Validados y Firmados
                        <span class="badge badge-success ml-1" id="badgeTabValidados" style="border-radius: 10px; padding: 3px 8px;">{{ $validadosCount }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="tabsContentEstablecimientos">
                
                {{-- ═══ PESTAÑA 1: PENDIENTES / POR VALIDAR ═══ --}}
                <div class="tab-pane fade show active" id="content-pendientes" role="tabpanel">
                    <div class="card-table-wrapper">
                        
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                            <div class="font-weight-bold text-dark" style="font-size: 14px;">
                                <i class="fa fa-list text-primary mr-1"></i> Establecimientos Pendientes de Certificación
                            </div>
                            <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 10px;">
                                <select id="filtroDeptoPendientes" class="form-control form-control-sm" style="width: auto; min-width: 200px; border-radius: 8px;">
                                    <option value="">Todos los Departamentos</option>
                                    @foreach($departamentos as $dpto)
                                        <option value="{{ $dpto }}">{{ $dpto }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaPendientes" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Departamento</th>
                                        <th style="width: 35%;">Establecimiento de Salud</th>
                                        <th style="width: 15%;" class="text-center">Esp. en Base de Datos</th>
                                        <th style="width: 20%;" class="text-center">Estado de Validación</th>
                                        <th style="width: 15%;" class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($establecimientos as $est)
                                        @php
                                            $valEst = $validacionesEstablecimientos->get($est->id_establecimiento);
                                            $isFirmado = $valEst && $valEst->estado === 'validado';
                                            if ($isFirmado) continue;

                                            $totalDb = $conteosEspecialidadesDb->get($est->id_establecimiento)->total_db ?? 0;
                                            $res = $resumenValidaciones->get($est->id_establecimiento);
                                            $totalReg = $res ? $res->total_registros : 0;
                                            $totalActivas = $res ? $res->total_activas : 0;
                                            $totalInactivas = $res ? $res->total_inactivas : 0;
                                        @endphp
                                        <tr id="row-est-pend-{{ $est->id_establecimiento }}" data-depto="{{ $est->departamento }}">
                                            <td>
                                                <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size: 11px;">
                                                    <i class="fa fa-map-marker-alt text-danger mr-1"></i> {{ $est->departamento }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                                    {{ $est->nombre_oficial }}
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $est->tipologia_clasificacion }} · <span class="text-primary font-weight-600">{{ $est->complejidad_label ?? $est->complejidad }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="font-size: 12px; border-radius: 6px;">
                                                    {{ $totalDb }} especialidades
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($totalReg > 0)
                                                    <span class="badge badge-primary font-weight-bold px-2 py-1" style="font-size: 11px; border-radius: 6px;">
                                                        <i class="fa fa-tasks mr-1"></i> {{ $totalReg }}/{{ $totalDb }} revisadas ({{ $totalActivas }} Act. / {{ $totalInactivas }} Inact.)
                                                    </span>
                                                @else
                                                    <span class="badge font-weight-bold px-2 py-1" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-size: 11px; border-radius: 6px;">
                                                        <i class="fa fa-clock mr-1"></i> Sin validar aún
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" onclick="abrirEspacioTrabajo('{{ $est->id_establecimiento }}')" style="border-radius: 8px; padding: 6px 14px;">
                                                    <i class="fa fa-bolt mr-1"></i> Validar y Firmar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- ═══ PESTAÑA 2: VALIDADOS Y FIRMADOS ═══ --}}
                <div class="tab-pane fade" id="content-validados" role="tabpanel">
                    <div class="card-table-wrapper">
                        
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                            <div class="font-weight-bold text-dark" style="font-size: 14px;">
                                <i class="fa fa-check-double text-success mr-1"></i> Establecimientos Validados, Certificados y Firmados
                            </div>
                            <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 10px;">
                                <select id="filtroDeptoValidados" class="form-control form-control-sm" style="width: auto; min-width: 200px; border-radius: 8px;">
                                    <option value="">Todos los Departamentos</option>
                                    @foreach($departamentos as $dpto)
                                        <option value="{{ $dpto }}">{{ $dpto }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaValidados" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 14%;">Departamento</th>
                                        <th style="width: 28%;">Establecimiento de Salud</th>
                                        <th style="width: 14%;" class="text-center">Activas</th>
                                        <th style="width: 14%;" class="text-center">Inactivadas</th>
                                        <th style="width: 15%;">Firmado Por / Fecha</th>
                                        <th style="width: 15%;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($establecimientos as $est)
                                        @php
                                            $valEst = $validacionesEstablecimientos->get($est->id_establecimiento);
                                            $isFirmado = $valEst && $valEst->estado === 'validado';
                                            if (!$isFirmado) continue;

                                            $totalActivas = $valEst->total_activas;
                                            $totalInactivas = $valEst->total_inactivas;
                                        @endphp
                                        <tr id="row-est-valid-{{ $est->id_establecimiento }}" data-depto="{{ $est->departamento }}">
                                            <td>
                                                <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size: 11px;">
                                                    <i class="fa fa-map-marker-alt text-danger mr-1"></i> {{ $est->departamento }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                                    {{ $est->nombre_oficial }}
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $est->tipologia_clasificacion }} · <span class="text-primary font-weight-600">{{ $est->complejidad_label ?? $est->complejidad }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge font-weight-bold px-2 py-1" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; font-size: 12px; border-radius: 6px;">
                                                    <i class="fa fa-check-circle mr-1"></i> {{ $totalActivas }} Activas
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($totalInactivas > 0)
                                                    <span class="badge font-weight-bold px-2 py-1" style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; font-size: 12px; border-radius: 6px;">
                                                        <i class="fa fa-ban mr-1"></i> {{ $totalInactivas }} Inactivadas
                                                    </span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="font-weight-600 text-dark" style="font-size: 12.5px;">
                                                    <i class="fa fa-signature text-success mr-1"></i> {{ $valEst->validador_nombre }}
                                                </div>
                                                <div class="text-muted" style="font-size: 11px;">
                                                    {{ $valEst->firmado_at ? $valEst->firmado_at->format('d/m/Y H:i') : '' }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('riiss.portal-validador.acta-establecimiento', [$sesion->token, $est->id_establecimiento]) }}" target="_blank" class="btn btn-outline-primary font-weight-bold" title="Ver Acta Oficial">
                                                        <i class="fa fa-print mr-1"></i> Acta
                                                    </a>
                                                    <button type="button" class="btn btn-outline-info font-weight-bold" onclick="abrirEspacioTrabajo('{{ $est->id_establecimiento }}')" title="Revisar / Modificar">
                                                        <i class="fa fa-edit mr-1"></i> Editar
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger font-weight-bold" onclick="reabrirEstablecimientoConfirm('{{ $est->id_establecimiento }}', '{{ addslashes($est->nombre_oficial) }}')" title="Reabrir / Deshacer Firma">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- VISTA 2: ESPACIO DE VALIDACIÓN Y FIRMA DEL ESTABLECIMIENTO      --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div id="vistaEspacioTrabajo" style="display: none;">
            
            {{-- Barra superior de navegación interna --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                <button type="button" class="btn btn-outline-secondary font-weight-bold shadow-sm" onclick="volverAlListado()" style="border-radius: 8px; font-size: 13px;">
                    <i class="fa fa-arrow-left mr-1"></i> Volver a la Lista de Establecimientos
                </button>
                <div class="mt-2 mt-md-0 d-flex align-items-center flex-wrap" style="gap: 8px;">
                    <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold shadow-sm" onclick="abrirModalAgregar()" style="border-radius: 8px; padding: 7px 14px;">
                        <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad Faltante
                    </button>
                    <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm" onclick="validarTodasLasPendientes()" style="border-radius: 8px; padding: 7px 14px;">
                        <i class="fa fa-check-double mr-1"></i> Validar Todas las Pendientes
                    </button>
                    <button type="button" class="btn btn-danger btn-sm font-weight-bold shadow-sm" onclick="abrirModalFirmaEstablecimiento()" style="border-radius: 8px; padding: 7px 16px;">
                        <i class="fa fa-signature mr-1"></i> Firmar y Completar Centro
                    </button>
                </div>
            </div>

            {{-- Tarjeta Encabezado del Centro --}}
            <div class="summary-hero mb-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <span class="badge badge-info px-2 py-1 text-uppercase font-weight-bold" id="wsDeptoBadge">
                            DEPARTAMENTO
                        </span>
                        <h3 class="font-weight-800 text-dark mt-2 mb-1" id="wsNombreTitulo" style="letter-spacing: -0.3px;">
                            Nombre del Centro
                        </h3>
                        <div class="text-muted small">
                            <span id="wsTipologia">Tipología</span> · <span id="wsComplejidad" class="text-primary font-weight-bold">Complejidad</span>
                        </div>
                    </div>

                    <div class="mt-3 mt-md-0" id="wsEstadoFirmaBadge">
                        {{-- Inyectado dinámicamente si ya está firmado o pendiente --}}
                    </div>
                </div>

                {{-- Tarjetas de Métricas en Vivo --}}
                <div class="row mt-3">
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="stat-metric-card">
                            <div>
                                <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">En Base de Datos</div>
                                <div class="font-weight-bold text-dark" id="statDb" style="font-size: 18px;">0</div>
                            </div>
                            <div class="bg-light text-secondary rounded p-2"><i class="fa fa-database"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="stat-metric-card" style="border-left: 3px solid #f59e0b !important;">
                            <div>
                                <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">Pendientes</div>
                                <div class="font-weight-bold text-warning" id="statPendientes" style="font-size: 18px; color: #d97706 !important;">0</div>
                            </div>
                            <div class="bg-light text-warning rounded p-2"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-metric-card" style="border-left: 3px solid #10b981 !important;">
                            <div>
                                <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">Validadas Activas</div>
                                <div class="font-weight-bold text-success" id="statActivas" style="font-size: 18px; color: #16a34a !important;">0</div>
                            </div>
                            <div class="bg-light text-success rounded p-2"><i class="fa fa-check-circle"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-metric-card" style="border-left: 3px solid #ef4444 !important;">
                            <div>
                                <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700;">Inactivadas</div>
                                <div class="font-weight-bold text-danger" id="statInactivas" style="font-size: 18px; color: #dc2626 !important;">0</div>
                            </div>
                            <div class="bg-light text-danger rounded p-2"><i class="fa fa-ban"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla DataTables de Especialidades Médicas --}}
            <div class="card-table-wrapper">
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div class="font-weight-bold text-dark" style="font-size: 14px;">
                        <i class="fa fa-stethoscope text-primary mr-1"></i> Catálogo de Especialidades Médicas del Establecimiento
                    </div>
                    <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 8px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold filter-esp-btn active" onclick="filtrarTablaEspecialidades('todas', this)">
                            Todas (<span id="btnCountTodas">0</span>)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning font-weight-bold filter-esp-btn text-dark" onclick="filtrarTablaEspecialidades('pendiente', this)">
                            Pendientes (<span id="btnCountPendientes">0</span>)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success font-weight-bold filter-esp-btn" onclick="filtrarTablaEspecialidades('activa', this)">
                            Activas (<span id="btnCountActivas">0</span>)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold filter-esp-btn" onclick="filtrarTablaEspecialidades('inactiva', this)">
                            Inactivadas (<span id="btnCountInactivas">0</span>)
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaEspecialidades" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 7%;" class="text-center">ID</th>
                                <th style="width: 28%;">Especialidad Médica (Bioestadística)</th>
                                <th style="width: 17%;" class="text-center">Estado Actual</th>
                                <th style="width: 18%;" class="text-center">Acción de Validación</th>
                                <th style="width: 22%;">Justificación / Motivo (Opcional)</th>
                                <th style="width: 8%;" class="text-center"><i class="fa fa-cloud-upload-alt mr-1"></i> Sync</th>
                            </tr>
                        </thead>
                        <tbody id="tablaEspecialidadesBody">
                            {{-- Inyectado dinámicamente vía JS --}}
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Barra Inferior Sticky para Firma Rápida --}}
            <div class="sticky-action-bar">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px;">
                        <i class="fa fa-clipboard-check"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                            Certificación Técnica del Centro
                        </div>
                        <div class="text-muted small" id="stickyResumenTexto">
                            0 especialidades validadas como activas.
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-danger font-weight-bold shadow px-4 py-2" onclick="abrirModalFirmaEstablecimiento()" style="border-radius: 8px; font-size: 13.5px;">
                        <i class="fa fa-signature mr-1"></i> Firmar y Completar Establecimiento
                    </button>
                </div>
            </div>

        </div>

    </main>

    {{-- FOOTER PORTADA SIPLAN --}}
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        @if($logoInstitucional || $sysLogoUrl)
                            <div style="background:#ffffff; padding:4px 8px; border-radius:8px; display:inline-flex; align-items:center;">
                                <img src="{{ $logoInstitucional ?: $sysLogoUrl }}" alt="{{ $sysSiteName }}" style="max-height: 42px; width: auto; object-fit: contain;">
                            </div>
                        @else
                            <div class="footer-logo" aria-hidden="true" style="width:38px;height:38px;border-radius:6px;background:linear-gradient(145deg,#0284c7,#7c3aed);display:grid;place-items:center;color:#fff;font-weight:900;">SP</div>
                        @endif
                        <div class="footer-brand-text">
                            <div class="fb-name">{{ $sysSiteName }}</div>
                            <div class="fb-sub">{{ $institucion }} · {{ $dependencia }}</div>
                        </div>
                    </div>
                    <p class="footer-desc" style="margin-top:12px; line-height:1.6;">
                        Plataforma de monitoreo estratégico y validación técnica institucional.<br>
                        <span style="opacity: .85; font-size:11px; display:block; margin-top:6px;">
                            <i class="fa fa-envelope" style="margin-right: 4px; color:#38bdf8;"></i> {{ $sysEmail }} &nbsp;·&nbsp; 
                            <i class="fa fa-phone" style="margin-right: 4px; color:#4ade80;"></i> {{ $sysPhone }}<br>
                            <i class="fa fa-clock" style="margin-right: 4px; color:#fbbf24;"></i> {{ $sysHours }} &nbsp;·&nbsp; 
                            <i class="fa fa-map-marker-alt" style="margin-right: 4px; color:#f87171;"></i> {{ $sysAddress }}
                        </span>
                    </p>
                </div>
                <div>
                    <div class="ai-label">Impulsado con asistencia de IA</div>
                    <div class="ai-list">
                        <a class="ai-chip" href="https://kiro.dev" target="_blank" rel="noopener">Kiro</a>
                        <a class="ai-chip" href="https://aws.amazon.com/q/" target="_blank" rel="noopener">Amazon Q</a>
                        <a class="ai-chip" href="https://claude.ai" target="_blank" rel="noopener">Claude</a>
                        <a class="ai-chip" href="https://gemini.google.com" target="_blank" rel="noopener">Gemini</a>
                    </div>
                    <div class="dev-credit">
                        Desarrollado por
                        <a href="https://www.linkedin.com/in/jufrancopy/" target="_blank" rel="noopener">Julio Franco</a> · IPS Paraguay
                    </div>
                </div>
            </div>
            <div class="footer-copy">{{ $sysFooter }}</div>
        </div>
    </footer>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- MODALES                                                        --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}

    {{-- Modal 1: Firma Digital por Establecimiento --}}
    <div class="modal fade" id="modalFirmarEstablecimiento" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #10b981 100%); padding: 18px 24px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 38px; height: 38px; font-size: 16px;">
                            <i class="fa fa-signature"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" style="font-size: 16px;">
                                Certificar y Firmar Establecimiento
                            </h5>
                            <span class="small text-white-50" id="modalFirmarNombreEst">Establecimiento</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white opacity-75" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    
                    {{-- Resumen de Totales a Certificar --}}
                    <div class="alert alert-light border mb-3 py-2 px-3" style="border-radius: 8px; font-size: 12.5px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-check-circle text-success mr-1"></i> Validadas Activas: <strong id="modalResumenActivas">0</strong></span>
                            <span><i class="fa fa-ban text-danger mr-1"></i> Inactivadas: <strong id="modalResumenInactivas">0</strong></span>
                        </div>
                    </div>

                    {{-- Lienzo de Firma Digital --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            Firma Digital del Validador <span class="text-danger">*</span>
                        </label>
                        <div class="signature-pad-container">
                            <canvas id="canvasFirmaEstablecimiento"></canvas>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <small class="text-muted">Dibuje su firma con el cursor o pantalla táctil.</small>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarFirmaEstablecimiento()" style="font-size: 11.5px; border-radius: 6px;">
                                <i class="fa fa-eraser mr-1"></i> Limpiar Firma
                            </button>
                        </div>
                    </div>

                    {{-- Observaciones Generales --}}
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            Observaciones Generales del Establecimiento (Opcional)
                        </label>
                        <textarea id="notasFirmaEstablecimiento" class="form-control" rows="2" style="border-radius: 8px; font-size: 12.5px;" placeholder="Comentarios técnicos sobre la infraestructura o servicios..."></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light" style="padding: 14px 24px;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-3" data-dismiss="modal" style="border-radius: 8px; font-size: 13px;">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-success font-weight-bold px-4 shadow-sm" onclick="guardarFirmaEstablecimiento()" style="border-radius: 8px; font-size: 13px; background: linear-gradient(135deg, #10b981, #059669); border: none;">
                        <i class="fa fa-save mr-1"></i> Guardar y Certificar Centro
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal 2: Agregar Especialidad Faltante --}}
    <div class="modal fade" id="modalAgregarEspecialidad" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); padding: 18px 24px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 36px; height: 36px; font-size: 16px;">
                            <i class="fa fa-plus"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" style="font-size: 16px;">
                                Agregar Especialidad Médica
                            </h5>
                            <span class="small text-white-50">Catálogo Oficial de Bioestadística</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white opacity-75" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 mb-3 border-0" style="border-radius: 8px; font-size: 12.5px; background-color: #f0f9ff; color: #0369a1;">
                        <i class="fa fa-info-circle mr-1"></i> Seleccione una especialidad médica del catálogo para incorporarla al establecimiento.
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            Especialidad Canónica (Bioestadística) <span class="text-danger">*</span>
                        </label>
                        <select id="selectEspecialidadBio" class="form-control" style="width: 100%;">
                            <option value="">Buscar o seleccionar especialidad médica...</option>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            Observación / Motivo de Incorporación (Opcional)
                        </label>
                        <input type="text" id="justificacionAgregar" class="form-control" style="border-radius: 8px; height: 40px; font-size: 13px;" placeholder="Ej: Incorporada recientemente en terreno...">
                    </div>
                </div>
                <div class="modal-footer bg-light" style="padding: 14px 24px;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-3" data-dismiss="modal" style="border-radius: 8px; font-size: 13px;">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary font-weight-bold px-4 shadow-sm" onclick="guardarNuevaEspecialidad()" style="border-radius: 8px; font-size: 13px; background: linear-gradient(135deg, #0284c7, #0369a1); border: none;">
                        <i class="fa fa-check mr-1"></i> Asignar al Establecimiento
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const TOKEN_SESION = '{{ $sesion->token }}';
        let establecimientoActualId = null;
        let establecimientoActualNombre = '';
        let signaturePadEstablecimiento = null;
        let especialidadesActuales = [];

        let dataTablePendientes = null;
        let dataTableValidados = null;
        let dataTableEspecialidades = null;

        // Configuración de CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Inicializar DataTables de Establecimientos Pendientes
            dataTablePendientes = $('#tablaPendientes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 10,
                order: [[0, 'asc'], [1, 'asc']],
                responsive: true
            });

            // Inicializar DataTables de Establecimientos Validados
            dataTableValidados = $('#tablaValidados').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 10,
                order: [[0, 'asc'], [1, 'asc']],
                responsive: true
            });

            // Filtros de Departamento por DataTables
            $('#filtroDeptoPendientes').on('change', function() {
                dataTablePendientes.column(0).search($(this).val()).draw();
            });

            $('#filtroDeptoValidados').on('change', function() {
                dataTableValidados.column(0).search($(this).val()).draw();
            });

            // Inicializar Select2 en Modal de Agregar
            $('#selectEspecialidadBio').select2({
                dropdownParent: $('#modalAgregarEspecialidad'),
                placeholder: 'Buscar especialidad por nombre o código...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: `/riiss/portal-validador/${TOKEN_SESION}/catalogo-bioestadistica`,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term || '' };
                    },
                    processResults: function(data) {
                        return {
                            results: (data.items || []).map(function(item) {
                                return {
                                    id: item.id,
                                    text: `#${item.id} — ${item.nombre}` + (item.codigo ? ` (${item.codigo})` : '')
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Inicializar SignaturePad al abrir modal de firma
            $('#modalFirmarEstablecimiento').on('shown.bs.modal', function () {
                var canvas = document.getElementById('canvasFirmaEstablecimiento');
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                }
                resizeCanvas();
                if (!signaturePadEstablecimiento) {
                    signaturePadEstablecimiento = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(250, 250, 250)',
                        penColor: 'rgb(15, 23, 42)'
                    });
                }
            });
        });

        // ══════════════════════════════════════════════════════════════
        // NAVEGACIÓN Y ESPACIO DE TRABAJO
        // ══════════════════════════════════════════════════════════════

        function abrirEspacioTrabajo(estId) {
            establecimientoActualId = estId;

            // Ocultar listado y mostrar espacio de trabajo
            $('#vistaListadoEstablecimientos').hide();
            $('#vistaEspacioTrabajo').show();
            window.scrollTo({ top: 0, behavior: 'smooth' });

            $('#tablaEspecialidadesBody').html(`
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                        <div class="font-weight-600">Cargando catálogo y especialidades del establecimiento...</div>
                    </td>
                </tr>
            `);

            $.get(`/riiss/portal-validador/${TOKEN_SESION}/establecimiento/${estId}`, function(res) {
                if (res.success) {
                    establecimientoActualNombre = res.establecimiento.nombre;
                    $('#wsNombreTitulo').text(res.establecimiento.nombre);
                    $('#wsDeptoBadge').text(res.establecimiento.departamento);
                    $('#wsTipologia').text(res.establecimiento.tipologia);
                    $('#wsComplejidad').text(res.establecimiento.complejidad);
                    $('#modalFirmarNombreEst').text(res.establecimiento.nombre);

                    // Badge de firma si ya está firmado
                    if (res.validacion && res.validacion.estado === 'validado') {
                        $('#wsEstadoFirmaBadge').html(`
                            <div class="badge font-weight-bold p-2 text-right" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; border-radius:8px;">
                                <div><i class="fa fa-check-circle mr-1"></i> Establecimiento Certificado y Firmado</div>
                                <div class="small mt-1">${res.validacion.validador_nombre} · ${res.validacion.firmado_at}</div>
                                <a href="/riiss/portal-validador/${TOKEN_SESION}/acta-establecimiento/${estId}" target="_blank" class="btn btn-sm btn-outline-success font-weight-bold mt-2" style="border-radius:6px;">
                                    <i class="fa fa-print mr-1"></i> Ver Acta Individual
                                </a>
                            </div>
                        `);
                    } else {
                        $('#wsEstadoFirmaBadge').html(`
                            <span class="badge font-weight-bold px-3 py-2" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-size:12px; border-radius:8px;">
                                <i class="fa fa-clock mr-1"></i> Pendiente de Firma
                            </span>
                        `);
                    }

                    especialidadesActuales = res.especialidades || [];
                    renderizarTablaEspecialidades(especialidadesActuales);
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de carga',
                    text: 'No se pudo cargar la información del establecimiento.',
                    confirmButtonColor: '#0284c7'
                });
            });
        }

        function volverAlListado() {
            $('#vistaEspacioTrabajo').hide();
            $('#vistaListadoEstablecimientos').show();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ══════════════════════════════════════════════════════════════
        // TABLA Y ESTADOS DE ESPECIALIDADES
        // ══════════════════════════════════════════════════════════════

        function renderizarTablaEspecialidades(lista) {
            if (dataTableEspecialidades) {
                dataTableEspecialidades.destroy();
                dataTableEspecialidades = null;
            }

            if (!lista || lista.length === 0) {
                $('#tablaEspecialidadesBody').html(`
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa fa-stethoscope fa-2x mb-2 text-secondary" style="opacity: 0.4;"></i>
                            <div class="font-weight-bold">No hay especialidades registradas en bioestadística para este centro.</div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-3 font-weight-bold" onclick="abrirModalAgregar()" style="border-radius: 8px;">
                                <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad desde Bioestadística
                            </button>
                        </td>
                    </tr>
                `);
                actualizarMetricas(0, 0, 0, 0);
                return;
            }

            let totalDb = lista.length;
            let totalActivas = 0;
            let totalInactivas = 0;
            let totalPendientes = 0;

            let html = '';
            lista.forEach((esp) => {
                const isActiva = (esp.estado === 'activa');
                const isInactiva = (esp.estado === 'inactiva');
                const isPendiente = (esp.estado === 'pendiente' || !esp.estado);

                if (isActiva) totalActivas++;
                else if (isInactiva) totalInactivas++;
                else totalPendientes++;

                let rowClass = 'row-pendiente';
                let badgeEstadoHtml = '<span class="badge" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-size:11px; padding:5px 8px; border-radius:6px;"><i class="fa fa-clock mr-1"></i> PENDIENTE</span>';

                if (isActiva) {
                    rowClass = 'row-activa';
                    badgeEstadoHtml = '<span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; font-size:11px; padding:5px 8px; border-radius:6px;"><i class="fa fa-check-circle mr-1"></i> VALIDADA (ACTIVA)</span>';
                } else if (isInactiva) {
                    rowClass = 'row-inactiva';
                    badgeEstadoHtml = '<span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; font-size:11px; padding:5px 8px; border-radius:6px;"><i class="fa fa-ban mr-1"></i> INACTIVADA</span>';
                }

                html += `
                    <tr id="row-esp-${esp.especialidad_id}" class="${rowClass}" data-estado="${esp.estado || 'pendiente'}">
                        <td class="text-center font-weight-bold text-muted" style="font-size: 12px;">
                            #${esp.especialidad_id}
                        </td>
                        <td>
                            <div class="font-weight-600 esp-nombre-texto text-dark" style="font-size: 13px;">
                                ${escapeHtml(esp.nombre)}
                            </div>
                            ${esp.codigo ? `<div class="small text-muted" style="font-size: 11px;">Cód: ${escapeHtml(esp.codigo)}</div>` : ''}
                            ${esp.es_agregada ? '<span class="badge badge-warning text-dark font-weight-bold mt-1" style="font-size: 9.5px; border-radius: 4px;"><i class="fa fa-plus mr-1"></i> Agregada en Relevamiento</span>' : ''}
                        </td>
                        <td class="text-center" id="estado-badge-col-${esp.especialidad_id}">
                            ${badgeEstadoHtml}
                        </td>
                        <td class="text-center">
                            <div class="btn-val-group">
                                <button type="button" 
                                        class="btn-val ${isActiva ? 'active-val-validar' : ''}" 
                                        id="btn-val-activa-${esp.especialidad_id}" 
                                        onclick="setEstadoEspecialidad(${esp.especialidad_id}, 'activa')">
                                    <i class="fa fa-check mr-1"></i> Validar
                                </button>
                                <button type="button" 
                                        class="btn-val ${isInactiva ? 'active-val-inactivar' : ''}" 
                                        id="btn-val-inactiva-${esp.especialidad_id}" 
                                        onclick="setEstadoEspecialidad(${esp.especialidad_id}, 'inactiva')">
                                    <i class="fa fa-times mr-1"></i> Inactivar
                                </button>
                            </div>
                        </td>
                        <td>
                            <input type="text" 
                                   class="input-justificacion" 
                                   id="just-esp-${esp.especialidad_id}" 
                                   data-id="${esp.especialidad_id}" 
                                   value="${esp.justificacion ? escapeHtml(esp.justificacion) : ''}" 
                                   placeholder="${isInactiva ? 'Motivo de inactivación (opcional)...' : 'Observación / Justificación (opcional)...'}" 
                                   onblur="guardarJustificacion(${esp.especialidad_id}, this.value)">
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light border text-muted px-2 py-1" id="badge-save-${esp.especialidad_id}" style="font-size: 11px; border-radius: 6px; font-weight: 600;">
                                <i class="fa fa-cloud text-secondary mr-1"></i> Listo
                            </span>
                        </td>
                    </tr>
                `;
            });

            $('#tablaEspecialidadesBody').html(html);

            // Inicializar DataTables
            dataTableEspecialidades = $('#tablaEspecialidades').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                order: [[1, 'asc']],
                responsive: true
            });

            actualizarMetricas(totalDb, totalPendientes, totalActivas, totalInactivas);
        }

        function actualizarMetricas(totalDb, pendientes, activas, inactivas) {
            $('#statDb').text(totalDb);
            $('#statPendientes').text(pendientes);
            $('#statActivas').text(activas);
            $('#statInactivas').text(inactivas);

            $('#btnCountTodas').text(totalDb);
            $('#btnCountPendientes').text(pendientes);
            $('#btnCountActivas').text(activas);
            $('#btnCountInactivas').text(inactivas);

            $('#stickyResumenTexto').text(`${activas} especialidades validadas como activas, ${inactivas} inactivadas.`);
            $('#modalResumenActivas').text(activas);
            $('#modalResumenInactivas').text(inactivas);
        }

        function filtrarTablaEspecialidades(filtro, btn) {
            $('.filter-esp-btn').removeClass('active');
            $(btn).addClass('active');

            if (!dataTableEspecialidades) return;

            if (filtro === 'todas') {
                dataTableEspecialidades.column(2).search('').draw();
            } else if (filtro === 'pendiente') {
                dataTableEspecialidades.column(2).search('PENDIENTE').draw();
            } else if (filtro === 'activa') {
                dataTableEspecialidades.column(2).search('ACTIVA').draw();
            } else if (filtro === 'inactiva') {
                dataTableEspecialidades.column(2).search('INACTIVADA').draw();
            }
        }

        function setEstadoEspecialidad(especialidadId, nuevoEstado) {
            if (!establecimientoActualId) return;

            const item = especialidadesActuales.find(e => e.especialidad_id === especialidadId);
            if (item) {
                item.estado = nuevoEstado;
            }

            const $row = $(`#row-esp-${especialidadId}`);
            const $badgeCol = $(`#estado-badge-col-${especialidadId}`);
            const $btnActiva = $(`#btn-val-activa-${especialidadId}`);
            const $btnInactiva = $(`#btn-val-inactiva-${especialidadId}`);
            const justificacion = $(`#just-esp-${especialidadId}`).val();

            if (nuevoEstado === 'activa') {
                $row.removeClass('row-inactiva row-pendiente').addClass('row-activa');
                $badgeCol.html('<span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; font-size:11px; padding:5px 8px; border-radius:6px;"><i class="fa fa-check-circle mr-1"></i> VALIDADA (ACTIVA)</span>');
                $btnActiva.addClass('active-val-validar');
                $btnInactiva.removeClass('active-val-inactivar');
            } else if (nuevoEstado === 'inactiva') {
                $row.removeClass('row-activa row-pendiente').addClass('row-inactiva');
                $badgeCol.html('<span class="badge" style="background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; font-size:11px; padding:5px 8px; border-radius:6px;"><i class="fa fa-ban mr-1"></i> INACTIVADA</span>');
                $btnInactiva.addClass('active-val-inactivar');
                $btnActiva.removeClass('active-val-validar');
            }

            // Recalcular métricas
            recalcularMetricasLocales();

            // Enviar AJAX
            enviarActualizacionEspecialidad(especialidadId, nuevoEstado, justificacion);
        }

        function guardarJustificacion(especialidadId, justificacion) {
            const item = especialidadesActuales.find(e => e.especialidad_id === especialidadId);
            const estado = (item && item.estado) ? item.estado : 'pendiente';

            if (item) {
                item.justificacion = justificacion;
            }

            if (estado !== 'pendiente') {
                enviarActualizacionEspecialidad(especialidadId, estado, justificacion);
            }
        }

        function enviarActualizacionEspecialidad(especialidadId, estado, justificacion) {
            if (!establecimientoActualId) return;

            const $badge = $(`#badge-save-${especialidadId}`);
            $badge.removeClass('badge-light text-muted badge-success badge-danger')
                  .addClass('badge-info text-white')
                  .html('<i class="fa fa-spinner fa-spin mr-1"></i>');

            $.post(`/riiss/portal-validador/${TOKEN_SESION}/actualizar-especialidad`, {
                establecimiento_id: establecimientoActualId,
                especialidad_id: especialidadId,
                estado: estado,
                justificacion: justificacion
            }, function(res) {
                if (res.success) {
                    $badge.removeClass('badge-info text-white badge-light text-muted badge-danger')
                          .addClass('badge-success text-white')
                          .html('<i class="fa fa-check"></i>');
                    setTimeout(function() {
                        $badge.removeClass('badge-success text-white')
                              .addClass('badge-light text-muted')
                              .html('<i class="fa fa-cloud text-secondary mr-1"></i> Listo');
                    }, 1800);
                }
            }).fail(function() {
                $badge.removeClass('badge-info text-white badge-success badge-light text-muted')
                      .addClass('badge-danger text-white')
                      .html('<i class="fa fa-times"></i>');
            });
        }

        function recalcularMetricasLocales() {
            let totalDb = especialidadesActuales.length;
            let totalActivas = 0;
            let totalInactivas = 0;
            let totalPendientes = 0;

            especialidadesActuales.forEach(esp => {
                if (esp.estado === 'activa') totalActivas++;
                else if (esp.estado === 'inactiva') totalInactivas++;
                else totalPendientes++;
            });

            actualizarMetricas(totalDb, totalPendientes, totalActivas, totalInactivas);
        }

        function validarTodasLasPendientes() {
            if (!establecimientoActualId) return;

            const pendientes = especialidadesActuales.filter(e => e.estado === 'pendiente' || !e.estado);
            if (pendientes.length === 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Todas las especialidades ya fueron revisadas.',
                    showConfirmButton: false,
                    timer: 2500
                });
                return;
            }

            Swal.fire({
                title: '¿Validar todas las pendientes?',
                text: `Se marcarán como ACTIVAS ${pendientes.length} especialidades registradas en la base de datos para este establecimiento.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa fa-check-double mr-1"></i> Sí, validar como Activas',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Validando...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.post(`/riiss/portal-validador/${TOKEN_SESION}/validar-todas`, {
                        establecimiento_id: establecimientoActualId
                    }, function(res) {
                        if (res.success) {
                            especialidadesActuales.forEach(esp => {
                                if (esp.estado === 'pendiente' || !esp.estado) {
                                    esp.estado = 'activa';
                                }
                            });
                            renderizarTablaEspecialidades(especialidadesActuales);

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 2500
                            });
                        }
                    });
                }
            });
        }

        // ══════════════════════════════════════════════════════════════
        // FIRMA DIGITAL POR ESTABLECIMIENTO
        // ══════════════════════════════════════════════════════════════

        function abrirModalFirmaEstablecimiento() {
            if (!establecimientoActualId) return;

            if (signaturePadEstablecimiento) {
                signaturePadEstablecimiento.clear();
            }
            $('#notasFirmaEstablecimiento').val('');
            $('#modalFirmarEstablecimiento').modal('show');
        }

        function limpiarFirmaEstablecimiento() {
            if (signaturePadEstablecimiento) signaturePadEstablecimiento.clear();
        }

        function guardarFirmaEstablecimiento() {
            if (!establecimientoActualId) return;

            let firmaData = null;
            if (signaturePadEstablecimiento && !signaturePadEstablecimiento.isEmpty()) {
                firmaData = signaturePadEstablecimiento.toDataURL('image/png');
            }

            const notas = $('#notasFirmaEstablecimiento').val();

            Swal.fire({
                title: 'Certificando establecimiento...',
                text: 'Registrando firma y sellando acta...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.post(`/riiss/portal-validador/${TOKEN_SESION}/firmar-establecimiento`, {
                establecimiento_id: establecimientoActualId,
                firma_base64: firmaData,
                notas: notas
            }, function(res) {
                if (res.success) {
                    $('#modalFirmarEstablecimiento').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: '¡Establecimiento Certificado y Firmado!',
                        html: `
                            <p class="text-muted">${res.message}</p>
                            <div class="mt-3">
                                <a href="/riiss/portal-validador/${TOKEN_SESION}/acta-establecimiento/${establecimientoActualId}" target="_blank" class="btn btn-primary btn-sm font-weight-bold">
                                    <i class="fa fa-print mr-1"></i> Ver / Imprimir Acta Individual
                                </a>
                            </div>
                        `,
                        confirmButtonText: 'Continuar al Listado',
                        confirmButtonColor: '#0284c7'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo registrar la firma del establecimiento.',
                    confirmButtonColor: '#0284c7'
                });
            });
        }

        function reabrirEstablecimientoConfirm(estId, estNombre) {
            Swal.fire({
                title: '¿Reabrir establecimiento?',
                text: `Se quitará el sello de validación de "${estNombre}" para que pueda ser reeditado y vuelto a firmar.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, reabrir para edición',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/riiss/portal-validador/${TOKEN_SESION}/reabrir-establecimiento`, {
                        establecimiento_id: estId
                    }, function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Establecimiento reabierto',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    });
                }
            });
        }

        // ══════════════════════════════════════════════════════════════
        // AGREGAR ESPECIALIDAD
        // ══════════════════════════════════════════════════════════════

        function abrirModalAgregar() {
            if (!establecimientoActualId) return;
            $('#justificacionAgregar').val('');
            $('#selectEspecialidadBio').val(null).trigger('change');
            $('#modalAgregarEspecialidad').modal('show');
        }

        function guardarNuevaEspecialidad() {
            const especialidadId = $('#selectEspecialidadBio').val();
            const justificacion = $('#justificacionAgregar').val();

            if (!especialidadId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Especialidad requerida',
                    text: 'Por favor seleccione una especialidad de la lista.',
                    confirmButtonColor: '#0284c7'
                });
                return;
            }

            $.post(`/riiss/portal-validador/${TOKEN_SESION}/agregar-especialidad`, {
                establecimiento_id: establecimientoActualId,
                especialidad_id: especialidadId,
                justificacion: justificacion
            }, function(res) {
                if (res.success) {
                    $('#modalAgregarEspecialidad').modal('hide');
                    abrirEspacioTrabajo(establecimientoActualId);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Especialidad agregada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
</body>
</html>
