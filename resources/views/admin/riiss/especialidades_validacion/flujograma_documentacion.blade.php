<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flujograma Metodológico y Matriz de Control Cruzado — RIISS IPS</title>
    
    <!-- Fuentes e Iconos -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- html2canvas para exportar JPG directo -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        :root {
            --ips-navy: #0f2744;
            --ips-blue: #1e4b8a;
            --ips-blue-light: #2563eb;
            --ips-teal: #0d9488;
            --ips-teal-dark: #0f766e;
            --ips-emerald: #059669;
            --ips-emerald-light: #10b981;
            --ips-bg: #f8fafc;
            --ips-card: #ffffff;
            --ips-border: #e2e8f0;
            --ips-text: #1e293b;
            --ips-text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--ips-bg);
            color: var(--ips-text);
            line-height: 1.6;
            padding: 30px 15px;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(15, 39, 68, 0.08);
            border: 1px solid var(--ips-border);
            overflow: hidden;
        }

        /* Top Bar de Acciones */
        .action-bar {
            background: #f1f5f9;
            padding: 14px 28px;
            border-bottom: 1px solid var(--ips-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #065f46;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid #a7f3d0;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--ips-navy) 0%, var(--ips-blue) 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(30, 75, 138, 0.25);
        }

        .btn-primary-custom:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn-jpg {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }

        .btn-jpg:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            background: #ffffff;
            color: var(--ips-text);
            border: 1px solid var(--ips-border);
        }

        .btn-outline-custom:hover {
            background: #f8fafc;
        }

        /* Encabezado Principal */
        .header-section {
            padding: 36px 40px 28px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 2px solid #edf2f7;
            position: relative;
        }

        .header-grid {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 24px;
            align-items: center;
        }

        .logo-img {
            max-height: 85px;
            max-width: 200px;
            object-fit: contain;
        }

        .header-title-box h1 {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--ips-navy);
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin-bottom: 6px;
        }

        .header-title-box h2 {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--ips-blue-light);
            margin-bottom: 6px;
        }

        .header-meta {
            font-size: 0.85rem;
            color: var(--ips-text-muted);
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .header-badge-norma {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 10px 16px;
            border-radius: 12px;
            text-align: right;
        }

        .header-badge-norma .norma-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #1e40af;
            letter-spacing: 0.05em;
        }

        .header-badge-norma .norma-val {
            font-size: 0.92rem;
            font-weight: 800;
            color: var(--ips-navy);
        }

        /* Contenido del Documento */
        .content-body {
            padding: 36px 40px;
        }

        /* Banner de Propósito */
        .purpose-card {
            background: linear-gradient(135deg, #0f2744 0%, #1e3a5f 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 26px 30px;
            margin-bottom: 36px;
            box-shadow: 0 8px 24px rgba(15, 39, 68, 0.12);
            position: relative;
            overflow: hidden;
        }

        .purpose-card::after {
            content: '\f477';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 25px;
            bottom: -15px;
            font-size: 7rem;
            opacity: 0.06;
            pointer-events: none;
        }

        .purpose-card h3 {
            font-size: 1.18rem;
            font-weight: 700;
            color: #38bdf8;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .purpose-card p {
            font-size: 0.95rem;
            line-height: 1.65;
            color: #e2e8f0;
            max-width: 980px;
        }

        .purpose-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .purpose-pill {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.85rem;
        }

        .purpose-pill strong {
            color: #7dd3fc;
            display: block;
            margin-bottom: 3px;
        }

        /* Título de Secciones */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eff6ff;
            color: var(--ips-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--ips-navy);
            letter-spacing: -0.01em;
        }

        /* ── FLUJOGRAMA VISUAL (STEP-BY-STEP FLOW) ── */
        .flow-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 40px;
            position: relative;
        }

        .flow-step-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid var(--ips-border);
            padding: 22px 26px;
            display: grid;
            grid-template-columns: 80px 1fr 280px;
            gap: 20px;
            align-items: center;
            transition: all 0.25s ease;
            position: relative;
        }

        .flow-step-card:hover {
            border-color: var(--ips-blue-light);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.08);
            transform: translateY(-2px);
        }

        .flow-number-badge {
            width: 65px;
            height: 65px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            text-align: center;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .bg-step-0 { background: linear-gradient(135deg, #475569, #334155); }
        .bg-step-1 { background: linear-gradient(135deg, #0284c7, #0369a1); }
        .bg-step-2 { background: linear-gradient(135deg, #0d9488, #0f766e); }
        .bg-step-3 { background: linear-gradient(135deg, #059669, #047857); }

        .flow-number-badge span {
            font-size: 0.65rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            opacity: 0.9;
        }

        .flow-number-badge strong {
            font-size: 1.4rem;
            line-height: 1;
        }

        .flow-content h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--ips-navy);
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .flow-content .actor-tag {
            display: inline-block;
            background: #f1f5f9;
            color: #334155;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .flow-content p {
            font-size: 0.88rem;
            color: #475569;
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .flow-points {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .flow-point-item {
            font-size: 0.78rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 3px 10px;
            border-radius: 6px;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .flow-output-box {
            background: #f8fafc;
            border-left: 3.5px solid var(--ips-blue);
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            font-size: 0.82rem;
        }

        .flow-output-box.border-teal { border-left-color: var(--ips-teal); }
        .flow-output-box.border-emerald { border-left-color: var(--ips-emerald); }

        .flow-output-box strong {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--ips-text-muted);
            margin-bottom: 4px;
            letter-spacing: 0.04em;
        }

        .flow-output-box .output-name {
            font-weight: 700;
            color: var(--ips-navy);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .flow-arrow-down {
            display: flex;
            justify-content: center;
            margin: -10px 0;
            color: #94a3b8;
            font-size: 1.2rem;
        }

        /* ── MATRIZ RESUMEN DE DIMENSIONES ── */
        .matrix-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 36px;
        }

        .matrix-dimension-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .matrix-dimension-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .card-dim-1::before { background: #0284c7; }
        .card-dim-2::before { background: #0d9488; }
        .card-dim-3::before { background: #10b981; }

        .dim-number {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ips-text-muted);
            margin-bottom: 4px;
        }

        .dim-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--ips-navy);
            margin-bottom: 8px;
        }

        .dim-desc {
            font-size: 0.82rem;
            color: #475569;
            line-height: 1.45;
        }

        .dim-badge-list {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .dim-badge-item {
            font-size: 0.78rem;
            padding: 5px 10px;
            border-radius: 6px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        /* ── TABLA DE COLUMNAS DE LA MATRIZ OFICIAL ── */
        .custom-table-container {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 36px;
        }

        .table-matrix {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            text-align: left;
        }

        .table-matrix thead {
            background: var(--ips-navy);
            color: #ffffff;
        }

        .table-matrix th {
            padding: 12px 16px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .table-matrix tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .table-matrix tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .table-matrix td {
            padding: 11px 16px;
            color: #334155;
            vertical-align: middle;
        }

        .col-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            font-weight: 600;
            background: #eff6ff;
            color: #1e40af;
            padding: 2px 7px;
            border-radius: 4px;
            border: 1px solid #dbeafe;
        }

        /* Footer Oficial */
        .footer-official {
            background: #f8fafc;
            border-top: 1px solid var(--ips-border);
            padding: 24px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            color: var(--ips-text-muted);
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-sig-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sig-seal {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #e0f2fe;
            border: 2px dashed #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0284c7;
            font-size: 1.1rem;
        }

        /* Responsividad y modo impresión */
        @media (max-width: 900px) {
            .header-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .header-badge-norma {
                text-align: center;
            }
            .flow-step-card {
                grid-template-columns: 1fr;
            }
            .matrix-summary-grid {
                grid-template-columns: 1fr;
            }
            .action-bar, .content-body, .header-section, .footer-official {
                padding: 20px;
            }
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .container {
                box-shadow: none;
                border: none;
                max-width: 100%;
            }
            .flow-step-card {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="container" id="printableDocument">

        {{-- Barra Superior de Acciones --}}
        <div class="action-bar">
            <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 10px;">
                <span class="badge-status">
                    <i class="fa fa-shield-halved"></i> Documento Oficial de Gobernanza RIISS
                </span>
                <span style="font-size: 0.8rem; color: #64748b;">
                    Resolución N° C.A. / Año 2026
                </span>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn-action btn-jpg" onclick="exportarComoJPG()">
                    <i class="fa fa-file-image"></i> Descargar como JPG
                </button>
                <button type="button" class="btn-action btn-primary-custom" onclick="window.print()">
                    <i class="fa fa-print"></i> Imprimir / PDF
                </button>
                <a href="{{ route('riiss.validaciones.index') }}" class="btn-action btn-outline-custom">
                    <i class="fa fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
        </div>

        {{-- Encabezado Institucional con Logo Oficial --}}
        <div class="header-section">
            <div class="header-grid">
                <div>
                    @if(!empty($logoUrl))
                        <img src="{{ $logoUrl }}" alt="Escudo Oficial IPS" class="logo-img">
                    @else
                        <img src="{{ asset('material/img/new_logo.png') }}" alt="Escudo Oficial IPS" class="logo-img">
                    @endif
                </div>

                <div class="header-title-box">
                    <h1>INSTITUTO DE PREVISIÓN SOCIAL</h1>
                    <h2>Dirección de Planificación — Políticas RIISS</h2>
                    <div class="header-meta">
                        <span><i class="fa fa-sitemap mr-1" style="color: var(--ips-blue-light);"></i> Redes Integradas e Integrales de Servicios de Salud</span>
                        <span><i class="fa fa-calendar-check mr-1" style="color: var(--ips-teal);"></i> Período 2026</span>
                        <span><i class="fa fa-map-marked-alt mr-1" style="color: var(--ips-emerald);"></i> Cobertura Nacional</span>
                    </div>
                </div>

                <div class="header-badge-norma">
                    <div class="norma-title">Normativa Vigente</div>
                    <div class="norma-val">Vademécum Oficial Aprobado</div>
                    <small style="color: #64748b; font-size: 11px;">Resolución del Consejo de Administración</small>
                </div>
            </div>
        </div>

        {{-- Cuerpo del Documento --}}
        <div class="content-body">

            {{-- 1. Propósito Estratégico y Para Qué --}}
            <div class="purpose-card">
                <h3><i class="fa fa-bullseye"></i> Propósito y Fin Estratégico del Control Cruzado</h3>
                <p>
                    El objetivo fundamental de este mecanismo de doble validación es consolidar una <strong>Matriz de Control Cruzado</strong> que unifique la oferta médica real en terreno con la pertinencia farmacológica aprobada institucionalmente, <strong>sin alterar ni condicionar el funcionamiento operativo de los sistemas transaccionales locales</strong> (SIH y otros) que utilizan las dependencias hospitalarias.
                </p>

                <div class="purpose-highlights">
                    <div class="purpose-pill">
                        <strong><i class="fa fa-chart-pie mr-1"></i> 1. Optimización Logística y Suministros</strong>
                        Permite a la Dirección de Logística de Suministros de Salud proyectar techos de abastecimiento y redistribución basados en la cartera médica habilitada real de cada hospital.
                    </div>
                    <div class="purpose-pill">
                        <strong><i class="fa fa-clipboard-check mr-1"></i> 2. Transparencia y Trazabilidad Total</strong>
                        Cada especialidad y medicamento cuenta con registro del analista evaluador, fecha/hora exacta, PIN de seguridad y firma digital con valor probatorio institucional.
                    </div>
                    <div class="purpose-pill">
                        <strong><i class="fa fa-balance-scale mr-1"></i> 3. Gobernanza y Pertinencia Terapéutica</strong>
                        Garantiza que la dispensación de fármacos de alta complejidad o uso restringido corresponda estrictamente a las especialidades autorizadas por la Unidad de Regulación Farmacéutica.
                    </div>
                </div>
            </div>

            {{-- 2. Flujograma Metodológico Paso a Paso --}}
            <div class="section-header">
                <div class="section-icon"><i class="fa fa-network-wired"></i></div>
                <div class="section-title">Flujograma Metodológico del Proceso de Validación</div>
            </div>

            <div class="flow-container">

                {{-- Paso 0: Base Normativa --}}
                <div class="flow-step-card">
                    <div class="flow-number-badge bg-step-0">
                        <span>Paso</span>
                        <strong>00</strong>
                    </div>
                    <div class="flow-content">
                        <span class="actor-tag"><i class="fa fa-landmark mr-1"></i> Consejo de Administración / Dirección de Planificación</span>
                        <h4>Catálogos Normativos y Vademécum Institucional Aprobado</h4>
                        <p>
                            Se cargan y homologan en el sistema los dos pilares normativos de referencia: el Catálogo Oficial de <strong>155 Especialidades Médicas</strong> y el <strong>Vademécum Institucional IPS (2026)</strong> formalmente promulgado por Resolución del Consejo.
                        </p>
                        <div class="flow-points">
                            <span class="flow-point-item"><i class="fa fa-check text-success"></i> 155 Especialidades Bioestadística</span>
                            <span class="flow-point-item"><i class="fa fa-check text-success"></i> Vademécum Normado con Formas y Concentraciones</span>
                        </div>
                    </div>
                    <div class="flow-output-box">
                        <strong>Insumo Base</strong>
                        <div class="output-name"><i class="fa fa-database text-primary mr-1"></i> Catálogos Oficiales</div>
                        <small class="text-muted">Marco legal y terapéutico vigente</small>
                    </div>
                </div>

                <div class="flow-arrow-down"><i class="fa fa-arrow-down"></i></div>

                {{-- Paso 1: Validación Territorial --}}
                <div class="flow-step-card">
                    <div class="flow-number-badge bg-step-1">
                        <span>Paso</span>
                        <strong>01</strong>
                    </div>
                    <div class="flow-content">
                        <span class="actor-tag"><i class="fa fa-hospital-user mr-1"></i> Dirección de Hospitales Área Central & Área Interior</span>
                        <h4>Validación Territorial de Especialidades Médicas por Establecimiento</h4>
                        <p>
                            Los Directores y Analistas Técnicos territoriales reciben un enlace seguro tokenizado con PIN individual. Auditan en terreno la oferta médica real de cada hospital (Área Central e Interior), validando o inactivando especialidades según dotación efectiva.
                        </p>
                        <div class="flow-points">
                            <span class="flow-point-item"><i class="fa fa-map-marker-alt text-info"></i> Segmentación Central vs Interior</span>
                            <span class="flow-point-item"><i class="fa fa-key text-warning"></i> PIN Criptográfico Individual</span>
                            <span class="flow-point-item"><i class="fa fa-signature text-primary"></i> Firma Digital de Acta Territorial</span>
                        </div>
                    </div>
                    <div class="flow-output-box">
                        <strong>Entregable Fase 1</strong>
                        <div class="output-name"><i class="fa fa-file-contract text-info mr-1"></i> Acta Territorial Firmada</div>
                        <small class="text-muted">Cartera médica real por hospital</small>
                    </div>
                </div>

                <div class="flow-arrow-down"><i class="fa fa-arrow-down"></i></div>

                {{-- Paso 2: Validación Farmacológica --}}
                <div class="flow-step-card">
                    <div class="flow-number-badge bg-step-2">
                        <span>Paso</span>
                        <strong>02</strong>
                    </div>
                    <div class="flow-content">
                        <span class="actor-tag" style="background:#ccfbf1; color:#0f766e;"><i class="fa fa-prescription-bottle-alt mr-1"></i> Unidad de Regulación Farmacéutica (IPS)</span>
                        <h4>Auditoría y Dictamen Farmacológico por Especialidad Médica</h4>
                        <p>
                            Los Químicos Farmacéuticos ingresan con credencial oficial al Portal de Regulación. Evalúan la correspondencia de los medicamentos del Vademécum para cada una de las especialidades médicas: validan la pertinencia o invalidan con <strong>justificación técnica obligatoria</strong>.
                        </p>
                        <div class="flow-points">
                            <span class="flow-point-item"><i class="fa fa-check-circle text-success"></i> Validación de Pertinencia</span>
                            <span class="flow-point-item"><i class="fa fa-times-circle text-danger"></i> Invalidación con Dictamen Justificado</span>
                            <span class="flow-point-item"><i class="fa fa-plus-circle text-teal"></i> Incorporación de Fármacos Normados</span>
                        </div>
                    </div>
                    <div class="flow-output-box border-teal">
                        <strong>Entregable Fase 2</strong>
                        <div class="output-name"><i class="fa fa-stamp text-teal mr-1"></i> Dictamen Farmacológico Oficial</div>
                        <small class="text-muted">Homologación del Vademécum</small>
                    </div>
                </div>

                <div class="flow-arrow-down"><i class="fa fa-arrow-down"></i></div>

                {{-- Paso 3: Matriz Consolidada --}}
                <div class="flow-step-card" style="border: 2px solid #10b981; background: #f0fdf4;">
                    <div class="flow-number-badge bg-step-3">
                        <span>Paso</span>
                        <strong>03</strong>
                    </div>
                    <div class="flow-content">
                        <span class="actor-tag" style="background:#dcfce7; color:#15803d;"><i class="fa fa-table mr-1"></i> Dirección de Planificación & Dirección de Logística de Suministros</span>
                        <h4>Consolidación de la Matriz de Control Cruzado y Exportación</h4>
                        <p>
                            El sistema cruza en tiempo real las 3 dimensiones: <em>Establecimientos Auditados</em> ↔ <em>Especialidades Validadas en Terreno</em> ↔ <em>Medicamentos Dictaminados por Regulación Farmacéutica</em>, generando la matriz ejecutiva para toma de decisiones y suministro eficiente.
                        </p>
                        <div class="flow-points">
                            <span class="flow-point-item"><i class="fa fa-file-excel text-success"></i> Exportación UTF-8 para Excel</span>
                            <span class="flow-point-item"><i class="fa fa-bolt text-warning"></i> Control Cruzado en Tiempo Real</span>
                            <span class="flow-point-item"><i class="fa fa-cubes text-primary"></i> Asignación Inteligente de Medicamentos</span>
                        </div>
                    </div>
                    <div class="flow-output-box border-emerald">
                        <strong>Resultado Final</strong>
                        <div class="output-name"><i class="fa fa-file-excel text-success mr-1"></i> Matriz Consolidada (.CSV / Excel)</div>
                        <small class="text-muted">Herramienta estratégica de gestión</small>
                    </div>
                </div>

            </div>

            {{-- 3. Las 3 Dimensiones del Cruce --}}
            <div class="section-header">
                <div class="section-icon"><i class="fa fa-cubes"></i></div>
                <div class="section-title">Las 3 Dimensiones de la Matriz de Control Cruzado</div>
            </div>

            <div class="matrix-summary-grid">
                <div class="matrix-dimension-card card-dim-1">
                    <div class="dim-number">Dimensión 1</div>
                    <div class="dim-title"><i class="fa fa-hospital mr-1 text-primary"></i> Red de Establecimientos</div>
                    <div class="dim-desc">
                        Nómina integral de hospitales y centros asistenciales clasificados por Área Central y Área Interior según tipología y departamento.
                    </div>
                    <div class="dim-badge-list">
                        <div class="dim-badge-item"><i class="fa fa-city text-primary"></i> Área Central (Hospital Central y Clínicas)</div>
                        <div class="dim-badge-item"><i class="fa fa-hospital text-info"></i> Área Interior (Hospitales Regionales y Puestos)</div>
                    </div>
                </div>

                <div class="matrix-dimension-card card-dim-2">
                    <div class="dim-number">Dimensión 2</div>
                    <div class="dim-title"><i class="fa fa-user-md mr-1" style="color: #0d9488;"></i> Especialidades en Terreno</div>
                    <div class="dim-desc">
                        Oferta médica real relevada y ratificada por los directores y evaluadores territoriales bajo firma digital.
                    </div>
                    <div class="dim-badge-list">
                        <div class="dim-badge-item"><i class="fa fa-check-circle text-success"></i> Especialidades Activas Validadas</div>
                        <div class="dim-badge-item"><i class="fa fa-times-circle text-danger"></i> Inactivas / Sin Dotación Médica</div>
                    </div>
                </div>

                <div class="matrix-dimension-card card-dim-3">
                    <div class="dim-number">Dimensión 3</div>
                    <div class="dim-title"><i class="fa fa-pills mr-1 text-success"></i> Vademécum Homologado</div>
                    <div class="dim-desc">
                        Pertinencia técnica y terapéutica dictaminada por los profesionales de la Unidad de Regulación Farmacéutica.
                    </div>
                    <div class="dim-badge-list">
                        <div class="dim-badge-item"><i class="fa fa-capsules text-teal"></i> Medicamento Pertinente Aprobado</div>
                        <div class="dim-badge-item"><i class="fa fa-ban text-danger"></i> Medicamento No Pertinente (Justificado)</div>
                    </div>
                </div>
            </div>

            {{-- 4. Estructura de Datos de la Matriz Consolidada --}}
            <div class="section-header">
                <div class="section-icon"><i class="fa fa-columns"></i></div>
                <div class="section-title">Estructura y Columnas Oficiales de la Matriz (Excel / CSV)</div>
            </div>

            <div class="custom-table-container">
                <table class="table-matrix">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 200px;">Nombre de la Columna</th>
                            <th style="width: 130px;">Dimensión</th>
                            <th>Descripción y Significado Operativo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><span class="col-tag">AREA_GESTION</span></td>
                            <td>Establecimiento</td>
                            <td>Segmentación funcional: <strong>Área Central</strong> o <strong>Área Interior</strong>.</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><span class="col-tag">DEPARTAMENTO</span></td>
                            <td>Establecimiento</td>
                            <td>Departamento geográfico de ubicación del establecimiento de salud.</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><span class="col-tag">NOMBRE_ESTABLECIMIENTO</span></td>
                            <td>Establecimiento</td>
                            <td>Denominación oficial del hospital, puesto sanitario o clínica periférica.</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td><span class="col-tag">TIPOLOGIA</span></td>
                            <td>Establecimiento</td>
                            <td>Nivel resolutivo y tipología prestacional según norma RIISS.</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td><span class="col-tag">ESPECIALIDAD_VALIDADA</span></td>
                            <td>Especialidad</td>
                            <td>Especialidad médica con presencia efectiva en el establecimiento.</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td><span class="col-tag">VALIDADOR_TERRITORIAL</span></td>
                            <td>Especialidad</td>
                            <td>Nombre del Director / Analista evaluador que firmó el relevamiento.</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td><span class="col-tag">MEDICAMENTO_VADEMECUM</span></td>
                            <td>Medicamento</td>
                            <td>Fármaco del Vademécum Institucional asignado a la especialidad.</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td><span class="col-tag">DICTAMEN_FARMACEUTICO</span></td>
                            <td>Regulación Farm.</td>
                            <td>Dictamen técnico: <strong>VALIDADO</strong>, <strong>INVALIDADO</strong> o <strong>INCORPORADO</strong>.</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td><span class="col-tag">JUSTIFICACION_DICTAMEN</span></td>
                            <td>Regulación Farm.</td>
                            <td>Fundamentación técnica farmacológica en caso de invalidación o incorporación.</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td><span class="col-tag">FARMACEUTICO_AUDITOR</span></td>
                            <td>Regulación Farm.</td>
                            <td>Nombre y matrícula profesional del Químico Farmacéutico responsable del dictamen.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Pie de Página Oficial con Sello y Firma Institucional --}}
        <div class="footer-official">
            <div class="footer-sig-box">
                <div class="sig-seal">
                    <i class="fa fa-certificate"></i>
                </div>
                <div>
                    <strong style="color: var(--ips-navy); display: block;">Instituto de Previsión Social (IPS)</strong>
                    <small>Dirección de Planificación • Unidad de Regulación Farmacéutica • Dirección de Hospitales</small>
                </div>
            </div>

            <div style="text-align: right;">
                <div style="font-weight: 700; color: var(--ips-navy);">Sistema SIPLAN / RIISS (2026)</div>
                <small>Documentación Técnica de Procesos y Flujogramas de Control Cruzado</small>
            </div>
        </div>

    </div>

    <script>
        function exportarComoJPG() {
            const element = document.getElementById('printableDocument');
            const actionBar = document.querySelector('.action-bar');
            
            // Ocultar temporalmente barra de botones para imagen limpia
            actionBar.style.display = 'none';

            // Feedback visual
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generando JPG...';

            html2canvas(element, {
                scale: 2, // Alta resolución
                useCORS: true,
                backgroundColor: '#f8fafc'
            }).then(canvas => {
                actionBar.style.display = 'flex';
                btn.innerHTML = originalText;

                // Descargar imagen
                const link = document.createElement('a');
                link.download = 'Flujograma_Validacion_RIISS_IPS_2026.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.95);
                link.click();
            }).catch(err => {
                actionBar.style.display = 'flex';
                btn.innerHTML = originalText;
                alert('Ocurrió un error al generar la imagen. Puede usar la opción Imprimir / Guardar como PDF.');
            });
        }
    </script>
</body>
</html>
