<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Validación de Especialidades Médicas — Área Interior</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --secondary: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
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

        .portal-container {
            flex: 1;
            display: flex;
            min-height: calc(100vh - 180px);
            background: var(--bg-body);
        }

        /* Panel Izquierdo: Lista de Establecimientos */
        .sidebar-establecimientos {
            width: 380px;
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            background: #ffffff;
        }

        .lista-est-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .est-item {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1.5px solid #f1f5f9;
            background: #ffffff;
            border-left: 4px solid transparent;
        }

        .est-item:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateX(2px);
        }

        .est-item.active {
            background: #f0f9ff;
            border-color: #bae6fd;
            border-left: 4px solid #0284c7;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.12);
        }

        .est-item.active .est-nombre {
            color: #0369a1;
            font-weight: 700;
        }

        /* Panel Derecho: Área de Trabajo del Establecimiento */
        .workspace-panel {
            flex: 1;
            background: var(--bg-body);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            position: relative;
        }

        .workspace-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 450px;
            padding: 40px 20px;
            text-align: center;
        }

        .workspace-header {
            background: #ffffff;
            padding: 20px 28px;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .workspace-body {
            padding: 24px 28px;
            flex: 1;
        }

        .table-especialidades {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .table-especialidades th {
            background: #f8fafc;
            font-weight: 700;
            font-size: 11.5px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
            padding: 14px 16px;
        }

        .table-especialidades td {
            padding: 13px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13.5px;
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

        .badge-save {
            font-size: 11px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .badge-save.show {
            opacity: 1;
        }

        /* Modal Signature */
        #signature-pad canvas {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background-color: #fafafa;
            cursor: crosshair;
            width: 100%;
            height: 180px;
        }

        /* Select2 Custom Styling */
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            background-color: #ffffff !important;
            font-size: 13.5px !important;
            display: flex !important;
            align-items: center !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
            outline: none;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            line-height: normal !important;
            padding-left: 0 !important;
            font-weight: 500 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 20px !important;
            font-size: 16px !important;
            color: #94a3b8 !important;
        }
        .select2-dropdown {
            border: 1.5px solid #0284c7 !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
            font-size: 13px !important;
            z-index: 9999 !important;
            overflow: hidden !important;
        }
        .select2-container--default .select2-search--dropdown {
            padding: 8px !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            font-size: 13px !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #0284c7 !important;
            outline: none !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0284c7 !important;
            color: #ffffff !important;
        }
        .select2-results__option {
            padding: 8px 12px !important;
        }

        /* ═══ FOOTER DE PORTADA SIPLAN GO ═══ */
        .site-footer {
            background: #0f172a;
            color: rgba(255,255,255,.6);
            padding: 40px clamp(14px,4vw,40px) 24px;
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
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .footer-logo {
            width: 38px;
            height: 38px;
            border-radius: 6px;
            background: linear-gradient(145deg, #0284c7, #7c3aed);
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 900;
            font-size: 11px;
            letter-spacing: -.8px;
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
            transition: all .15s;
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
        .dev-credit a:hover {
            text-decoration: underline;
        }
        .footer-copy {
            text-align: center;
            font-size: 10px;
            color: rgba(255,255,255,.35);
        }
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            .footer-desc {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    {{-- Barra Superior con Logo Institucional del Plan --}}
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
                    {{ $institucion }} · Sistema de Validación de Especialidades Médicas
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

            <a href="{{ route('riiss.portal-validador.acta-imprimir', $sesion->token) }}" target="_blank" class="btn btn-outline-light btn-sm mr-2 shadow-sm font-weight-bold">
                <i class="fa fa-print mr-1"></i> Ver Acta
            </a>

            <button type="button" class="btn btn-success btn-sm shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalFinalizar">
                <i class="fa fa-signature mr-1"></i> Cerrar / Firmar
            </button>
        </div>
    </nav>

    {{-- Contenedor Principal Master-Detail --}}
    <div class="portal-container">

        {{-- Barra Lateral Izquierda: Selector de Establecimientos --}}
        <aside class="sidebar-establecimientos">
            <div class="sidebar-header">
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;"><i class="fa fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="filtroEstablecimiento" class="form-control border-left-0 bg-light" style="border-radius: 0 8px 8px 0;" placeholder="Buscar por nombre...">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <select id="filtroDepartamento" class="form-control form-control-sm" style="font-size: 12px; border-radius: 8px;">
                        <option value="">Todos los Departamentos ({{ $establecimientos->count() }})</option>
                        @foreach($departamentos as $dpto)
                            <option value="{{ $dpto }}">{{ $dpto }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="lista-est-scroll" id="listaEstablecimientos">
                @foreach($establecimientos as $est)
                    @php
                        $totalDb = $conteosEspecialidadesDb->get($est->id_establecimiento)->total_db ?? 0;
                        $res = $resumenValidaciones->get($est->id_establecimiento);
                        $totalReg = $res ? $res->total_registros : 0;
                        $totalActivas = $res ? $res->total_activas : 0;
                        $totalInactivas = $res ? $res->total_inactivas : 0;
                    @endphp
                    <div class="est-item" 
                         data-id="{{ $est->id_establecimiento }}"
                         data-nombre="{{ strtolower($est->nombre_oficial) }}"
                         data-depto="{{ $est->departamento }}"
                         data-totaldb="{{ $totalDb }}"
                         onclick="seleccionarEstablecimiento('{{ $est->id_establecimiento }}')">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="est-nombre font-weight-600 text-dark" style="font-size: 13px;">
                                {{ $est->nombre_oficial }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-muted" style="font-size: 11.5px;">
                                <i class="fa fa-map-marker-alt text-danger mr-1"></i> {{ $est->departamento }}
                            </span>
                            @if($totalReg > 0)
                                @if($totalReg >= $totalDb && $totalDb > 0)
                                    <span class="badge font-weight-bold" id="badge-est-{{ $est->id_establecimiento }}" style="font-size: 10.5px; border-radius: 6px; padding: 4px 8px; background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                                        <i class="fa fa-check-circle mr-1"></i> {{ $totalActivas }} Act. / {{ $totalInactivas }} Inact.
                                    </span>
                                @else
                                    <span class="badge badge-primary font-weight-bold" id="badge-est-{{ $est->id_establecimiento }}" style="font-size: 10.5px; border-radius: 6px; padding: 4px 8px;">
                                        <i class="fa fa-tasks mr-1"></i> {{ $totalReg }}/{{ $totalDb }} Validadas
                                    </span>
                                @endif
                            @else
                                <span class="badge font-weight-bold" id="badge-est-{{ $est->id_establecimiento }}" style="font-size: 10.5px; border-radius: 6px; padding: 4px 8px; background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                    <i class="fa fa-clock mr-1"></i> {{ $totalDb }} en DB (Pendiente)
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        {{-- Panel Derecho: Espacio de Trabajo de Especialidades --}}
        <main class="workspace-panel" id="workspacePanel">
            
            {{-- Estado Inicial / Placeholder --}}
            <div id="workspaceVacio" class="workspace-empty-state">
                <div class="mb-3">
                    <i class="fa fa-hospital fa-4x text-muted" style="opacity: 0.25;"></i>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Seleccione un Establecimiento de Salud</h4>
                <p class="text-muted" style="max-width: 440px; font-size: 13.5px; line-height: 1.6;">
                    Haga clic en cualquiera de los centros del listado de la izquierda para desplegar y validar sus especialidades médicas en tiempo real.
                </p>
            </div>

            {{-- Área Activa de Trabajo --}}
            <div id="workspaceActivo" style="display: none; flex-direction: column; height: 100%;">
                
                {{-- Encabezado del Establecimiento --}}
                <div class="workspace-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <span class="badge badge-info px-2 py-1 text-uppercase font-weight-bold shadow-none" id="estDeptoBadge" style="font-size: 10.5px; border-radius: 6px;">
                            DEPARTAMENTO
                        </span>
                        <h4 class="font-weight-bold text-dark mt-2 mb-1" id="estNombreTitulo" style="letter-spacing: -0.2px;">
                            Nombre del Establecimiento
                        </h4>
                        <div class="text-muted small">
                            <span id="estTipologia">Tipología</span> · <span id="estComplejidad" class="text-primary font-weight-bold">Complejidad</span>
                        </div>
                    </div>

                    <div class="mt-3 mt-md-0 d-flex align-items-center flex-wrap" style="gap: 8px;">
                        <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm" onclick="validarTodasLasPendientes()" style="border-radius: 8px; padding: 8px 16px;">
                            <i class="fa fa-check-double mr-1"></i> Validar Todas las Pendientes
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold shadow-sm" onclick="abrirModalAgregar()" style="border-radius: 8px; padding: 8px 16px;">
                            <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad Faltante
                        </button>
                    </div>
                </div>

                {{-- Cuerpo de la Validación --}}
                <div class="workspace-body">
                    
                    {{-- Tarjetas de Estadísticas en Vivo --}}
                    <div class="row mb-3">
                        <div class="col-6 col-md-3 mb-2 mb-md-0">
                            <div class="bg-white border rounded p-2 d-flex align-items-center justify-content-between shadow-sm" style="border-radius: 10px !important;">
                                <div>
                                    <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">En Base de Datos</div>
                                    <div class="font-weight-bold text-dark" id="statDb" style="font-size: 18px;">0</div>
                                </div>
                                <div class="bg-light text-secondary rounded p-2"><i class="fa fa-database"></i></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2 mb-md-0">
                            <div class="bg-white border rounded p-2 d-flex align-items-center justify-content-between shadow-sm" style="border-left: 3px solid #f59e0b !important; border-radius: 10px !important;">
                                <div>
                                    <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">Pendientes</div>
                                    <div class="font-weight-bold text-warning" id="statPendientes" style="font-size: 18px; color: #d97706 !important;">0</div>
                                </div>
                                <div class="bg-light text-warning rounded p-2"><i class="fa fa-clock"></i></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="bg-white border rounded p-2 d-flex align-items-center justify-content-between shadow-sm" style="border-left: 3px solid #10b981 !important; border-radius: 10px !important;">
                                <div>
                                    <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">Validadas (Activas)</div>
                                    <div class="font-weight-bold text-success" id="statActivas" style="font-size: 18px; color: #16a34a !important;">0</div>
                                </div>
                                <div class="bg-light text-success rounded p-2"><i class="fa fa-check-circle"></i></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="bg-white border rounded p-2 d-flex align-items-center justify-content-between shadow-sm" style="border-left: 3px solid #ef4444 !important; border-radius: 10px !important;">
                                <div>
                                    <div class="text-muted text-uppercase" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">Inactivadas</div>
                                    <div class="font-weight-bold text-danger" id="statInactivas" style="font-size: 18px; color: #dc2626 !important;">0</div>
                                </div>
                                <div class="bg-light text-danger rounded p-2"><i class="fa fa-ban"></i></div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de Especialidades --}}
                    <div class="table-responsive table-especialidades">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 7%;" class="text-center">ID</th>
                                    <th style="width: 30%;">Especialidad Médica (Bioestadística)</th>
                                    <th style="width: 17%;" class="text-center">Estado Actual</th>
                                    <th style="width: 18%;" class="text-center">Acción de Validación</th>
                                    <th style="width: 20%;">Justificación / Observación (Opcional)</th>
                                    <th style="width: 8%;" class="text-center"><i class="fa fa-cloud-upload-alt mr-1"></i> Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEspecialidadesBody">
                                {{-- Filas inyectadas dinámicamente vía JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </main>

    </div>

    {{-- FOOTER — ESTILO PORTADA SIPLAN GO --}}
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
                            <div class="footer-logo" aria-hidden="true">SP</div>
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
                        <a class="ai-chip" href="https://kiro.dev" target="_blank" rel="noopener">
                            <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#F59E0B"/><path d="M12 36L24 12L36 36H28L24 27L20 36H12Z" fill="white"/></svg>
                            Kiro
                        </a>
                        <a class="ai-chip" href="https://aws.amazon.com/q/" target="_blank" rel="noopener">
                            <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#1A9C3E"/><path d="M24 10C16.3 10 10 16.3 10 24C10 31.7 16.3 38 24 38C27.4 38 30.5 36.8 32.9 34.8L36 38L38 36L34.8 32.9C36.8 30.5 38 27.4 38 24C38 16.3 31.7 10 24 10ZM24 34C18.5 34 14 29.5 14 24C14 18.5 18.5 14 24 14C29.5 14 34 18.5 34 24C34 26.6 33 29 31.3 30.8L27 26.5C27.6 25.8 28 24.9 28 24C28 21.8 26.2 20 24 20C21.8 20 20 21.8 20 24C20 26.2 21.8 28 24 28C24.9 28 25.8 27.6 26.5 27L30.8 31.3C29 33 26.6 34 24 34Z" fill="white"/></svg>
                            Amazon Q
                        </a>
                        <a class="ai-chip" href="https://claude.ai" target="_blank" rel="noopener">
                            <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#F97316"/><path d="M24 8L38 32H10L24 8Z" fill="white" opacity=".9"/></svg>
                            Claude
                        </a>
                        <a class="ai-chip" href="https://gemini.google.com" target="_blank" rel="noopener">
                            <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#4285F4"/><path d="M24 8C24 8 30 20 30 24C30 28 24 40 24 40C24 40 18 28 18 24C18 20 24 8 24 8Z" fill="white"/><path d="M8 24C8 24 20 18 24 18C28 18 40 24 40 24C40 24 28 30 24 30C20 30 8 24 8 24Z" fill="white" opacity=".7"/></svg>
                            Gemini
                        </a>
                    </div>
                    <div class="dev-credit">
                        Desarrollado por
                        <a href="https://www.linkedin.com/in/jufrancopy/" target="_blank" rel="noopener">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="#7dd3fc" style="vertical-align:middle;margin-right:2px"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                            Julio Franco
                        </a> · IPS Paraguay
                    </div>
                </div>
            </div>
            <div class="footer-copy">{{ $sysFooter }}</div>
        </div>
    </footer>

    {{-- Modal para Agregar Especialidad con Select2 --}}
    <div class="modal fade" id="modalAgregarEspecialidad" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); padding: 18px 24px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 36px; height: 36px; font-size: 16px;">
                            <i class="fa fa-plus"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" style="font-size: 16px; letter-spacing: 0.2px;">
                                Agregar Especialidad Médica
                            </h5>
                            <span class="small text-white-50">Catálogo Oficial de Bioestadística</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white opacity-75 hover-opacity-100" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 mb-3 border-0" style="border-radius: 8px; font-size: 12.5px; background-color: #f0f9ff; color: #0369a1;">
                        <i class="fa fa-info-circle mr-1"></i> Seleccione una especialidad médica del catálogo canónico para incorporarla al establecimiento.
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            Especialidad Canónica (Bioestadística) <span class="text-danger">*</span>
                        </label>
                        <select id="selectEspecialidadBio" class="form-control" style="width: 100%;">
                            <option value="">Buscar o seleccionar especialidad médica...</option>
                        </select>
                        <small class="form-text text-muted">Escriba el nombre o código de la especialidad para buscar en el catálogo.</small>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            Observación / Motivo de Incorporación (Opcional)
                        </label>
                        <input type="text" id="justificacionAgregar" class="form-control" style="border-radius: 8px; height: 40px; font-size: 13px; border: 1.5px solid #cbd5e1;" placeholder="Ej: Incorporada recientemente en terreno...">
                    </div>
                </div>
                <div class="modal-footer bg-light" style="padding: 14px 24px; border-top: 1px solid #e2e8f0;">
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

    {{-- Modal Finalizar / Firma Digital --}}
    <div class="modal fade" id="modalFinalizar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #10b981 100%); padding: 18px 24px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 36px; height: 36px; font-size: 16px;">
                            <i class="fa fa-signature"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" style="font-size: 16px; letter-spacing: 0.2px;">
                                Cerrar y Sellar Relevamiento
                            </h5>
                            <span class="small text-white-50">Constancia y Firma Digital</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white opacity-75 hover-opacity-100" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Estampe su firma digital táctil como constancia de la validación técnica realizada para la Dirección de Hospitales del Área Interior.
                    </p>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Lienzo de Firma Digital</label>
                        <div id="signature-pad" class="signature-pad">
                            <canvas id="canvasFirma"></canvas>
                        </div>
                        <div class="text-right mt-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" onclick="limpiarFirma()" style="border-radius: 6px; font-size: 12px;">
                                <i class="fa fa-eraser mr-1"></i> Limpiar Firma
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Notas u Observaciones Generales (Opcional)</label>
                        <textarea id="notasCierre" class="form-control" rows="2" style="border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px;" placeholder="Observaciones finales sobre el relevamiento...">{{ $sesion->notas }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="padding: 14px 24px; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-3" data-dismiss="modal" style="border-radius: 8px; font-size: 13px;">
                        Seguir Editando
                    </button>
                    <button type="button" class="btn btn-success font-weight-bold px-4 shadow-sm" onclick="guardarFinalizacion()" style="border-radius: 8px; font-size: 13px; background: linear-gradient(135deg, #10b981, #059669); border: none;">
                        <i class="fa fa-save mr-1"></i> Guardar y Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const TOKEN_SESION = '{{ $sesion->token }}';
        let establecimientoActualId = null;
        let signaturePad = null;
        let especialidadesActuales = [];

        // Configuración de CSRF Token para AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Inicializar Select2 en Modal de Agregar Especialidad
            $('#selectEspecialidadBio').select2({
                dropdownParent: $('#modalAgregarEspecialidad'),
                placeholder: 'Buscar especialidad por nombre o código...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "No se encontraron especialidades en Bioestadística";
                    },
                    searching: function() {
                        return "Buscando en catálogo Bioestadística...";
                    }
                },
                ajax: {
                    url: `/riiss/portal-validador/${TOKEN_SESION}/catalogo-bioestadistica`,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term || ''
                        };
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
                },
                minimumInputLength: 0
            });

            // Inicializar Signature Pad al abrir modal
            $('#modalFinalizar').on('shown.bs.modal', function () {
                var canvas = document.getElementById('canvasFirma');
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                }
                resizeCanvas();
                if (!signaturePad) {
                    signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(250, 250, 250)',
                        penColor: 'rgb(15, 23, 42)'
                    });
                }
            });

            // Filtro de búsqueda en la lista de establecimientos
            $('#filtroEstablecimiento').on('input', function() {
                filtrarListaEstablecimientos();
            });

            $('#filtroDepartamento').on('change', function() {
                filtrarListaEstablecimientos();
            });
        });

        function filtrarListaEstablecimientos() {
            const query = $('#filtroEstablecimiento').val().toLowerCase().trim();
            const depto = $('#filtroDepartamento').val();

            $('#listaEstablecimientos .est-item').each(function() {
                const nombre = $(this).data('nombre');
                const estDepto = $(this).data('depto');

                const matchQuery = !query || nombre.includes(query);
                const matchDepto = !depto || estDepto === depto;

                if (matchQuery && matchDepto) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Cargar y seleccionar un establecimiento
        function seleccionarEstablecimiento(id) {
            establecimientoActualId = id;

            $('#listaEstablecimientos .est-item').removeClass('active');
            $(`#listaEstablecimientos .est-item[data-id="${id}"]`).addClass('active');

            // Ocultar placeholder y mostrar workspace activo
            $('#workspaceVacio').hide();
            $('#workspaceActivo').css('display', 'flex');

            $('#tablaEspecialidadesBody').html(`
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                        <div class="font-weight-600">Cargando catálogo y especialidades del establecimiento...</div>
                    </td>
                </tr>
            `);

            $.get(`/riiss/portal-validador/${TOKEN_SESION}/establecimiento/${id}`, function(res) {
                if (res.success) {
                    $('#estNombreTitulo').text(res.establecimiento.nombre);
                    $('#estDeptoBadge').text(res.establecimiento.departamento);
                    $('#estTipologia').text(res.establecimiento.tipologia);
                    $('#estComplejidad').text(res.establecimiento.complejidad);

                    especialidadesActuales = res.especialidades || [];
                    renderizarEspecialidades(especialidadesActuales);
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de carga',
                    text: 'Error al cargar la información del establecimiento.',
                    confirmButtonColor: '#0284c7'
                });
            });
        }

        // Renderizar las filas de especialidades y estadísticas
        function renderizarEspecialidades(lista) {
            if (!lista || lista.length === 0) {
                $('#tablaEspecialidadesBody').html(`
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa fa-stethoscope fa-2x mb-2 text-secondary" style="opacity: 0.4;"></i>
                            <div class="font-weight-bold">No hay especialidades registradas aún para este establecimiento.</div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-3 font-weight-bold shadow-sm" onclick="abrirModalAgregar()" style="border-radius: 8px;">
                                <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad desde Bioestadística
                            </button>
                        </td>
                    </tr>
                `);
                actualizarMetricasUI(0, 0, 0, 0);
                return;
            }

            let totalDb = lista.length;
            let totalActivas = 0;
            let totalInactivas = 0;
            let totalPendientes = 0;

            let html = '';
            lista.forEach((esp, idx) => {
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
                    <tr id="row-esp-${esp.especialidad_id}" class="${rowClass}">
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
            actualizarMetricasUI(totalDb, totalPendientes, totalActivas, totalInactivas);
            actualizarSidebarBadge(establecimientoActualId, totalDb, totalActivas, totalInactivas);
        }

        // Actualizar contadores superiores
        function actualizarMetricasUI(totalDb, pendientes, activas, inactivas) {
            $('#statDb').text(totalDb);
            $('#statPendientes').text(pendientes);
            $('#statActivas').text(activas);
            $('#statInactivas').text(inactivas);
        }

        // Cambiar estado a 'activa' o 'inactiva'
        function setEstadoEspecialidad(especialidadId, nuevoEstado) {
            if (!establecimientoActualId) return;

            // Actualizar objeto en memoria
            const item = especialidadesActuales.find(e => e.especialidad_id === especialidadId);
            if (item) {
                item.estado = nuevoEstado;
            }

            // Actualizar fila visualmente
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
            enviarActualizacion(especialidadId, nuevoEstado, justificacion);
        }

        // Guardar justificación opcional al salir del campo
        function guardarJustificacion(especialidadId, justificacion) {
            const item = especialidadesActuales.find(e => e.especialidad_id === especialidadId);
            const estado = (item && item.estado) ? item.estado : 'pendiente';

            if (item) {
                item.justificacion = justificacion;
            }

            // Si aún está pendiente y escribió justificación, mantener pendiente o actualizar si ya fue asignado estado
            if (estado !== 'pendiente') {
                enviarActualizacion(especialidadId, estado, justificacion);
            }
        }

        // AJAX para guardar especialidad
        function enviarActualizacion(especialidadId, estado, justificacion) {
            if (!establecimientoActualId) return;

            const $badge = $(`#badge-save-${especialidadId}`);
            $badge.removeClass('badge-light text-muted badge-success badge-danger')
                  .addClass('badge-info text-white')
                  .html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

            $.post(`/riiss/portal-validador/${TOKEN_SESION}/actualizar-especialidad`, {
                establecimiento_id: establecimientoActualId,
                especialidad_id: especialidadId,
                estado: estado,
                justificacion: justificacion
            }, function(res) {
                if (res.success) {
                    mostrarFeedbackGuardado(especialidadId);
                    if (res.total_db !== undefined) {
                        actualizarSidebarBadge(establecimientoActualId, res.total_db, res.total_activas, res.total_inactivas);
                    }
                }
            }).fail(function() {
                $badge.removeClass('badge-info text-white badge-success badge-light text-muted')
                      .addClass('badge-danger text-white')
                      .html('<i class="fa fa-times-circle mr-1"></i> Error');
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

            actualizarMetricasUI(totalDb, totalPendientes, totalActivas, totalInactivas);
            actualizarSidebarBadge(establecimientoActualId, totalDb, totalActivas, totalInactivas);
        }

        function mostrarFeedbackGuardado(especialidadId) {
            const $badge = $(`#badge-save-${especialidadId}`);
            $badge.removeClass('badge-info text-white badge-light text-muted badge-danger')
                  .addClass('badge-success text-white')
                  .html('<i class="fa fa-check mr-1"></i> Guardado');
            setTimeout(function() {
                $badge.removeClass('badge-success text-white')
                      .addClass('badge-light text-muted')
                      .html('<i class="fa fa-cloud text-secondary mr-1"></i> Listo');
            }, 2000);
        }

        function actualizarSidebarBadge(estId, totalDb, totalActivas, totalInactivas) {
            const $badge = $(`#badge-est-${estId}`);
            const totalRevisadas = totalActivas + totalInactivas;

            if (totalRevisadas > 0) {
                if (totalRevisadas >= totalDb && totalDb > 0) {
                    $badge.attr('style', 'font-size: 10.5px; border-radius: 6px; padding: 4px 8px; background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;')
                          .removeClass('badge-primary badge-warning')
                          .addClass('badge font-weight-bold')
                          .html(`<i class="fa fa-check-circle mr-1"></i> ${totalActivas} Act. / ${totalInactivas} Inact.`);
                } else {
                    $badge.attr('style', 'font-size: 10.5px; border-radius: 6px; padding: 4px 8px;')
                          .removeClass('badge-warning')
                          .addClass('badge badge-primary font-weight-bold')
                          .html(`<i class="fa fa-tasks mr-1"></i> ${totalRevisadas}/${totalDb} Validadas`);
                }
            } else {
                $badge.attr('style', 'font-size: 10.5px; border-radius: 6px; padding: 4px 8px; background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;')
                      .removeClass('badge-primary')
                      .addClass('badge font-weight-bold')
                      .html(`<i class="fa fa-clock mr-1"></i> ${totalDb} en DB (Pendiente)`);
            }
        }

        // Validar todas las pendientes como activas en 1 clic
        function validarTodasLasPendientes() {
            if (!establecimientoActualId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Seleccione primero un establecimiento de la lista.',
                    confirmButtonColor: '#0284c7'
                });
                return;
            }

            const pendientes = especialidadesActuales.filter(e => e.estado === 'pendiente' || !e.estado);
            if (pendientes.length === 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Todas las especialidades de este centro ya fueron validadas o inactivadas.',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            Swal.fire({
                title: '¿Validar todas las especialidades pendientes?',
                text: `Se confirmarán y marcarán como ACTIVAS ${pendientes.length} especialidades registradas en la base de datos para este establecimiento.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa fa-check-double mr-1"></i> Sí, validar todas como Activas',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Validando especialidades...',
                        text: 'Registrando validación técnica...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.post(`/riiss/portal-validador/${TOKEN_SESION}/validar-todas`, {
                        establecimiento_id: establecimientoActualId
                    }, function(res) {
                        if (res.success) {
                            // Actualizar lista en memoria
                            especialidadesActuales.forEach(esp => {
                                if (esp.estado === 'pendiente' || !esp.estado) {
                                    esp.estado = 'activa';
                                }
                            });

                            renderizarEspecialidades(especialidadesActuales);

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 2500
                            });
                        }
                    }).fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron validar todas las especialidades.',
                            confirmButtonColor: '#0284c7'
                        });
                    });
                }
            });
        }

        // Modal para agregar especialidad
        function abrirModalAgregar() {
            if (!establecimientoActualId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Seleccione primero un establecimiento de la lista.',
                    confirmButtonColor: '#0284c7'
                });
                return;
            }
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
                    seleccionarEstablecimiento(establecimientoActualId);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Especialidad agregada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }).fail(function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al agregar la especialidad.',
                    confirmButtonColor: '#0284c7'
                });
            });
        }

        // Firma y cierre
        function limpiarFirma() {
            if (signaturePad) signaturePad.clear();
        }

        function guardarFinalizacion() {
            let firmaData = null;
            if (signaturePad && !signaturePad.isEmpty()) {
                firmaData = signaturePad.toDataURL('image/png');
            }

            const notas = $('#notasCierre').val();

            Swal.fire({
                title: '¿Confirmar finalización?',
                text: 'Se sellará y enviará el relevamiento de especialidades de esta red.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa fa-check mr-1"></i> Sí, finalizar y sellar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sellando relevamiento...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.post(`/riiss/portal-validador/${TOKEN_SESION}/finalizar`, {
                        firma_base64: firmaData,
                        notas: notas
                    }, function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Relevamiento finalizado!',
                                text: 'El relevamiento ha sido sellado con éxito.',
                                confirmButtonColor: '#0284c7'
                            }).then(() => {
                                $('#modalFinalizar').modal('hide');
                                window.location.reload();
                            });
                        }
                    }).fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo finalizar el relevamiento.',
                            confirmButtonColor: '#0284c7'
                        });
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
