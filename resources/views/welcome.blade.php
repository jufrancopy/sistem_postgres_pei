<!DOCTYPE html>
<html lang="es">
<head>
@php use Illuminate\Support\Facades\Auth; @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SIPLAN — Documentación Técnica</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #0d0f14;
    --surface: #13161d;
    --surface2: #1a1e28;
    --border: #252a36;
    --accent: #4f8ef7;
    --accent2: #7eeac4;
    --accent3: #f5a623;
    --accent4: #e05c5c;
    --text: #e8eaf0;
    --muted: #7a8299;
    --tag-bg: #1e2333;
    --green: #4ec994;
    --yellow: #f5c842;
    --red: #e05c5c;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Instrument Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    line-height: 1.7;
    font-size: 15px;
  }

  /* ── HERO ── */
  .hero {
    position: relative;
    padding: 80px 60px 60px;
    border-bottom: 1px solid var(--border);
    overflow: hidden;
    max-width: 1200px;
    margin: 0 auto;
  }
  .hero::before {
    content: '';
    position: absolute;
    top: -120px; right: -80px;
    width: 520px; height: 520px;
    background: radial-gradient(circle, rgba(79,142,247,0.10) 0%, transparent 70%);
    pointer-events: none;
  }
  .hero-label {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    letter-spacing: 3px;
    color: var(--accent);
    text-transform: uppercase;
    margin-bottom: 18px;
  }
  .hero h1 {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(2.4rem, 5vw, 4rem);
    line-height: 1.1;
    color: #fff;
    max-width: 700px;
  }
  .hero h1 em {
    font-style: italic;
    color: var(--accent);
  }
  .hero-sub {
    margin-top: 20px;
    color: var(--muted);
    max-width: 560px;
    font-size: 15px;
  }
  .badge-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 32px;
  }
  .badge {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid var(--border);
    color: var(--muted);
    background: var(--surface);
  }
  .badge.blue { border-color: var(--accent); color: var(--accent); }
  .badge.green { border-color: var(--accent2); color: var(--accent2); }
  .badge.amber { border-color: var(--accent3); color: var(--accent3); }

  /* ── LOGIN BTN ── */
  .login-btn {
    position: fixed;
    top: 20px; right: 28px;
    z-index: 999;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 8px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
    border: 1px solid rgba(79,142,247,0.4);
    background: rgba(79,142,247,0.08);
    color: var(--accent);
    backdrop-filter: blur(8px);
  }
  .login-btn:hover {
    background: rgba(79,142,247,0.18);
    border-color: var(--accent);
    color: #fff;
    text-decoration: none;
  }
  .login-btn svg { flex-shrink: 0; }

  /* ── LAYOUT ── */
  .layout {
    display: flex;
    max-width: 1200px;
    margin: 0 auto;
    min-height: 100vh;
  }

  /* ── SIDEBAR ── */
  .sidebar {
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    border-right: 1px solid var(--border);
    padding: 36px 24px;
    background: var(--surface);
    width: 240px;
    flex-shrink: 0;
  }
  .sidebar-title {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 16px;
  }
  .sidebar nav { display: flex; flex-direction: column; gap: 2px; }
  .sidebar a {
    text-decoration: none;
    color: var(--muted);
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 6px;
    transition: all 0.15s;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .sidebar a:hover { background: var(--surface2); color: var(--text); }
  .sidebar a.active { background: rgba(79,142,247,0.12); color: var(--accent); }
  .sidebar-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--border);
    flex-shrink: 0;
  }
  .sidebar a.active .sidebar-dot { background: var(--accent); }
  .sidebar-section { margin-top: 28px; }

  /* ── MAIN CONTENT ── */
  main { padding: 60px 64px 100px; max-width: 860px; flex: 1; min-width: 0; }

  /* ── SECTIONS ── */
  .section { margin-bottom: 72px; }
  .section-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
  }
  .section-num {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    color: var(--accent);
    border: 1px solid rgba(79,142,247,0.3);
    padding: 3px 9px;
    border-radius: 4px;
    white-space: nowrap;
  }
  .section h2 {
    font-family: 'DM Serif Display', serif;
    font-size: 1.7rem;
    color: #fff;
  }
  .section h3 {
    font-family: 'Instrument Sans', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    margin: 28px 0 10px;
    letter-spacing: 0.3px;
  }
  .section p { color: #b0b8cc; margin-bottom: 14px; }
  .section p:last-child { margin-bottom: 0; }

  /* ── DIVIDER ── */
  .divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 48px 0;
  }

  /* ── CARDS ── */
  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 22px 26px;
    margin-bottom: 16px;
  }
  .card-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .card-title .icon { font-size: 15px; }
  .card p { font-size: 13.5px; color: var(--muted); margin: 0; }

  /* ── CALLOUT ── */
  .callout {
    border-radius: 8px;
    padding: 16px 20px;
    margin: 20px 0;
    font-size: 13.5px;
    border-left: 3px solid;
    background: var(--surface);
  }
  .callout.info { border-color: var(--accent); }
  .callout.info .callout-label { color: var(--accent); }
  .callout.warning { border-color: var(--accent3); }
  .callout.warning .callout-label { color: var(--accent3); }
  .callout.danger { border-color: var(--accent4); }
  .callout.danger .callout-label { color: var(--accent4); }
  .callout.success { border-color: var(--accent2); }
  .callout.success .callout-label { color: var(--accent2); }
  .callout-label {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 6px;
  }
  .callout p { color: #b0b8cc; margin: 0; }

  /* ── CODE ── */
  .code-block {
    background: #0a0c10;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px 22px;
    margin: 16px 0;
    overflow-x: auto;
  }
  .code-block code {
    font-family: 'DM Mono', monospace;
    font-size: 12.5px;
    color: #a8b5d8;
    line-height: 1.8;
    white-space: pre;
  }
  .code-block code .kw { color: var(--accent); }
  .code-block code .str { color: var(--accent2); }
  .code-block code .comment { color: #455070; }
  .code-block code .num { color: var(--accent3); }

  /* ── FORMULA ── */
  .formula-box {
    background: linear-gradient(135deg, rgba(79,142,247,0.06), rgba(126,234,196,0.04));
    border: 1px solid rgba(79,142,247,0.25);
    border-radius: 10px;
    padding: 24px 28px;
    margin: 20px 0;
    text-align: center;
  }
  .formula-label {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 14px;
  }
  .formula {
    font-family: 'DM Mono', monospace;
    font-size: 15px;
    color: #fff;
    line-height: 2;
  }
  .formula .frac {
    display: inline-block;
    vertical-align: middle;
    text-align: center;
  }
  .formula .frac span {
    display: block;
    font-size: 13px;
    color: var(--muted);
  }
  .formula .frac .num-f { border-bottom: 1px solid var(--muted); padding-bottom: 4px; color: #fff; }

  /* ── SEMAFORO ── */
  .semaforo {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin: 20px 0;
  }
  .semaforo-item {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 20px 18px;
    text-align: center;
  }
  .semaforo-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    margin: 0 auto 12px;
  }
  .semaforo-dot.verde { background: var(--green); box-shadow: 0 0 14px rgba(78,201,148,0.4); }
  .semaforo-dot.amarillo { background: var(--yellow); box-shadow: 0 0 14px rgba(245,200,66,0.4); }
  .semaforo-dot.rojo { background: var(--red); box-shadow: 0 0 14px rgba(224,92,92,0.4); }
  .semaforo-item h4 { font-size: 13px; font-weight: 600; margin-bottom: 6px; }
  .semaforo-item p { font-size: 12px; color: var(--muted); margin: 0; }

  /* ── RACI TABLE ── */
  .raci-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 13px;
  }
  .raci-table th {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--muted);
    padding: 10px 14px;
    text-align: left;
    border-bottom: 1px solid var(--border);
    background: var(--surface);
  }
  .raci-table td {
    padding: 11px 14px;
    border-bottom: 1px solid rgba(37,42,54,0.6);
    color: #b0b8cc;
    vertical-align: middle;
  }
  .raci-table tr:hover td { background: rgba(255,255,255,0.015); }
  .raci-pill {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 4px;
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    font-weight: 500;
  }
  .pill-r { background: rgba(224,92,92,0.15); color: #e05c5c; }
  .pill-a { background: rgba(245,166,35,0.15); color: #f5a623; }
  .pill-c { background: rgba(79,142,247,0.15); color: #4f8ef7; }
  .pill-i { background: rgba(126,234,196,0.15); color: #7eeac4; }

  /* ── ALERT TABLE ── */
  .alert-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    margin: 16px 0;
  }
  .alert-table th {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--muted);
    padding: 10px 14px;
    text-align: left;
    border-bottom: 1px solid var(--border);
  }
  .alert-table td {
    padding: 11px 14px;
    border-bottom: 1px solid rgba(37,42,54,0.6);
    color: #b0b8cc;
  }
  .alert-icon { font-size: 16px; }

  /* ── IEA SCALE ── */
  .iea-scale {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin: 16px 0;
  }
  .iea-row {
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 14px 18px;
  }
  .iea-val {
    font-family: 'DM Mono', monospace;
    font-size: 13px;
    color: #fff;
    min-width: 80px;
  }
  .iea-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
  .iea-text { font-size: 13px; color: var(--muted); }
  .iea-label { font-size: 12px; font-weight: 600; margin-left: auto; }

  /* ── CADENCE ── */
  .cadence-steps {
    display: flex;
    flex-direction: column;
    gap: 0;
    margin: 20px 0;
    position: relative;
  }
  .cadence-steps::before {
    content: '';
    position: absolute;
    left: 20px; top: 0; bottom: 0;
    width: 1px;
    background: var(--border);
  }
  .cadence-step {
    display: flex;
    gap: 20px;
    padding: 16px 0;
  }
  .cadence-step-num {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: var(--surface2);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-family: 'DM Mono', monospace;
    font-size: 12px;
    color: var(--accent);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
  }
  .cadence-step-content h4 { font-size: 13.5px; font-weight: 600; margin-bottom: 4px; }
  .cadence-step-content p { font-size: 13px; color: var(--muted); margin: 0; }

  /* ── DB SCHEMA ── */
  .schema-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
    margin: 16px 0;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--border);
  }
  .schema-table th {
    background: var(--surface2);
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--muted);
    padding: 10px 14px;
    text-align: left;
  }
  .schema-table td {
    padding: 10px 14px;
    border-bottom: 1px solid rgba(37,42,54,0.6);
    color: #b0b8cc;
  }
  .schema-table tr:last-child td { border-bottom: none; }
  .schema-key { color: var(--accent3); font-family: 'DM Mono', monospace; font-size: 11px; }
  .schema-type { color: var(--accent); font-family: 'DM Mono', monospace; font-size: 11px; }
  .schema-desc { color: var(--muted); }

  /* ── MODULE GRID ── */
  .module-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin: 20px 0;
  }
  .module-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 18px 20px;
    transition: border-color 0.2s;
  }
  .module-card:hover { border-color: rgba(79,142,247,0.4); }
  .module-card-icon { font-size: 20px; margin-bottom: 10px; }
  .module-card h4 { font-size: 13px; font-weight: 600; margin-bottom: 6px; }
  .module-card p { font-size: 12.5px; color: var(--muted); margin: 0; }

  /* ── SCROLL BEHAVIOR ── */
  html { scroll-behavior: smooth; }

  /* ── RESPONSIVE ── */
  @media (max-width: 800px) {
    .layout { flex-direction: column; }
    .sidebar { display: none; }
    main { padding: 32px 24px 80px; }
    .hero { padding: 48px 24px 40px; }
    .semaforo { grid-template-columns: 1fr; }
    .module-grid { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

{{-- ── Botón de acceso ── --}}
@auth
<a href="{{ route('home') }}" class="login-btn">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Ir al sistema
</a>
@else
<a href="{{ route('login') }}" class="login-btn">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    Iniciar sesión
</a>
@endauth

<!-- HERO -->
<div class="hero">
  <div class="hero-label">// README — SIPLAN v2.0</div>
  <h1>Gestión Integral y<br><em>Planificación Estratégica</em></h1>
  <p class="hero-sub">Documentación técnica y reglas de negocio para desarrolladores y analistas. Incluye las reformas estratégicas incorporadas al sistema a partir del análisis IPS 2023–2028.</p>
  <div class="badge-row">
    <span class="badge blue">Laravel · PHP</span>
    <span class="badge blue">MVC + Eloquent ORM</span>
    <span class="badge green">FODA + PEI</span>
    <span class="badge green">Kaplan-Norton BSC</span>
    <span class="badge amber">Covey · Rumelt</span>
    <span class="badge amber">MECIP</span>
  </div>
</div>

<!-- LAYOUT -->
<div class="layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-title">Contenido</div>
    <nav>
      <a href="#arquitectura" class="active"><span class="sidebar-dot"></span>Arquitectura base</a>
      <a href="#modulos"><span class="sidebar-dot"></span>Módulos core</a>

      <div class="sidebar-section">
        <div class="sidebar-title">Reformas estratégicas</div>
      </div>

      <a href="#iea"><span class="sidebar-dot"></span>IEA — Índice FODA</a>
      <a href="#raci"><span class="sidebar-dot"></span>Matriz RACI</a>
      <a href="#semaforo"><span class="sidebar-dot"></span>Semáforo Lead/Lag</a>
      <a href="#alertas"><span class="sidebar-dot"></span>Alertas presupuestarias</a>
      <a href="#cadencia"><span class="sidebar-dot"></span>Cadencia de cuentas</a>

      <div class="sidebar-section">
        <div class="sidebar-title">Referencia</div>
      </div>

      <a href="#schema"><span class="sidebar-dot"></span>Esquema de datos</a>
      <a href="#kpis"><span class="sidebar-dot"></span>KPIs preconfigurados</a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main>

    <!-- 1. ARQUITECTURA -->
    <section class="section" id="arquitectura">
      <div class="section-header">
        <span class="section-num">§ 01</span>
        <h2>Arquitectura base</h2>
      </div>
      <p>SIPLAN utiliza el patrón <strong>MVC (Modelo-Vista-Controlador)</strong> sobre Laravel. Las estructuras jerárquicas (organigramas, grupos, variables FODA) se gestionan mediante <strong>NodeTrait (Nested Sets)</strong>, permitiendo árboles de dependencias eficientes con consultas de ancestros/descendientes en tiempo constante.</p>

      <div class="module-grid">
        <div class="module-card">
          <div class="module-card-icon">🏗️</div>
          <h4>Backend</h4>
          <p>Laravel + Eloquent ORM. Relaciones polimórficas en el módulo Task para vincular tareas a FODA, PEI o Encuestas.</p>
        </div>
        <div class="module-card">
          <div class="module-card-icon">🖼️</div>
          <h4>Frontend</h4>
          <p>Vistas Blade con DataTables para listados dinámicos. Gráficos integrados para resultados y análisis.</p>
        </div>
        <div class="module-card">
          <div class="module-card-icon">🔐</div>
          <h4>Seguridad RBAC</h4>
          <p>Control de acceso basado en roles mediante <code>Spatie/Permission</code>. Roles, permisos y usuarios gestionados desde panel administrativo.</p>
        </div>
        <div class="module-card">
          <div class="module-card-icon">📄</div>
          <h4>Reportes PDF</h4>
          <p>Generación de documentos mediante clase personalizada <code>Pdf</code> (FPDF) y <code>Barryvdh\DomPDF</code>.</p>
        </div>
      </div>
    </section>

    <hr class="divider">

    <!-- 2. MÓDULOS CORE -->
    <section class="section" id="modulos">
      <div class="section-header">
        <span class="section-num">§ 02</span>
        <h2>Módulos del sistema</h2>
      </div>

      <h3>Organigrama y Grupos</h3>
      <p>Gestiona la estructura jerárquica de dependencias institucionales. Permite crear sub-dependencias y visualizar el árbol organizacional completo. Los Grupos definen equipos de trabajo con miembros asignados, organizados jerárquicamente.</p>

      <h3>Actividades y Seguimiento</h3>
      <p>Creación de actividades con fechas de inicio/fin, responsables y tareas asociadas. Las tareas admiten evidencias (archivos o URLs) y notas de cierre.</p>

      <h3>Planificación Estratégica (PEI)</h3>
      <p>Gestiona el ciclo de vida del Plan Estratégico Institucional: Identidad Institucional (Misión, Visión, Valores), estructura en Ejes → Objetivos → Acciones, e indicadores de gestión con numeradores, denominadores y fórmulas de progreso.</p>

      <h3>Encuestas con IA</h3>
      <p>Generación automática de preguntas vía <strong>Gemini API</strong> (opción múltiple) y <strong>OpenAI GPT-3.5</strong> (hasta 5 opciones). Las encuestas se asignan a grupos o dependencias con rastreo de participación y ranking de puntajes.</p>

      <h3>Proyectos EPC y Patrimonio</h3>
      <p>Gestión de servicios/recursos (equipamiento, TTHH, infraestructura, medicamentos) con costos unitarios por tabla pivote. Patrimonio incluye georreferenciación (lat/lng), fotos y estados de documentación.</p>
    </section>

    <hr class="divider">

    <!-- ─── REFORMAS ESTRATÉGICAS ─── -->

    <!-- 3. IEA -->
    <section class="section" id="iea">
      <div class="section-header">
        <span class="section-num">§ 03</span>
        <h2>IEA — Índice de Eficiencia de Activos</h2>
      </div>

      <div class="callout info">
        <div class="callout-label">Reforma · Módulo FODA</div>
        <p>Reemplaza la clasificación subjetiva de aspectos FODA por un índice cuantitativo que cruza inversión histórica con desempeño operativo real, eliminando el sesgo de los talleres participativos.</p>
      </div>

      <h3>¿Qué problema resuelve?</h3>
      <p>Los planes IPS 2023–2028 declaran como <em>fortaleza</em> la disponibilidad de equipos biomédicos de última generación, pero simultáneamente identifican como <em>debilidad</em> los altos tiempos de espera y el stock deficiente de medicamentos. Según la teoría de la Estrategia del Océano Azul, esto es una <strong>contradicción estratégica</strong>: un activo que no apalanca resultados no es una fortaleza.</p>

      <h3>Fórmula del IEA</h3>
      <div class="formula-box">
        <div class="formula-label">Índice de Eficiencia de Activos</div>
        <div class="formula">
          IEA =
          <span class="frac">
            <span class="num-f">Desempeño Operativo (promedio 6 meses)</span>
            <span>Inversión en Recursos / Mantenimiento (histórico 6 meses)</span>
          </span>
        </div>
      </div>

      <h3>Regla de clasificación automática</h3>
      <div class="iea-scale">
        <div class="iea-row">
          <span class="iea-val">IEA &lt; 0.4</span>
          <span class="iea-dot" style="background:var(--red)"></span>
          <span class="iea-text">Inversión alta, resultado bajo</span>
          <span class="iea-label" style="color:var(--red)">→ DEBILIDAD</span>
        </div>
        <div class="iea-row">
          <span class="iea-val">0.4 – 0.8</span>
          <span class="iea-dot" style="background:var(--yellow)"></span>
          <span class="iea-text">Activo en desarrollo o proceso de maduración</span>
          <span class="iea-label" style="color:var(--yellow)">→ NEUTRO</span>
        </div>
        <div class="iea-row">
          <span class="iea-val">IEA &gt; 0.8</span>
          <span class="iea-dot" style="background:var(--green)"></span>
          <span class="iea-text">El recurso apalanca el resultado</span>
          <span class="iea-label" style="color:var(--green)">→ FORTALEZA</span>
        </div>
      </div>

      <div class="callout warning">
        <div class="callout-label">Regla especial · Obsolescencia funcional</div>
        <p>Si el sistema detecta inversión en hardware/software pero el indicador SIH (Sistema Integrado de Salud) no reduce tiempos de espera en el período evaluado, el aspecto se clasifica automáticamente como <strong>debilidad por obsolescencia funcional</strong>, independientemente del IEA calculado.</p>
      </div>

      <h3>Implementación técnica</h3>
      <div class="code-block"><code><span class="comment">// app/Services/FodaIeaService.php</span>
<span class="kw">public function</span> calcularIEA(<span class="str">$aspectoId</span>, <span class="str">$meses</span> = <span class="num">6</span>): float
{
    <span class="str">$inversion</span> = InversionHistorica::where(<span class="str">'aspecto_id'</span>, <span class="str">$aspectoId</span>)
        ->ultimos(<span class="str">$meses</span>)->avg(<span class="str">'monto'</span>);

    <span class="str">$desempeno</span> = IndicadorSIH::where(<span class="str">'aspecto_id'</span>, <span class="str">$aspectoId</span>)
        ->ultimos(<span class="str">$meses</span>)->avg(<span class="str">'valor_normalizado'</span>);

    <span class="kw">if</span> (<span class="str">$inversion</span> == <span class="num">0</span>) <span class="kw">return</span> <span class="num">0</span>;

    <span class="str">$iea</span> = <span class="str">$desempeno</span> / <span class="str">$inversion</span>;

    <span class="comment">// Regla de obsolescencia funcional</span>
    <span class="kw">if</span> (<span class="str">$this</span>->tieneSIHSinMejora(<span class="str">$aspectoId</span>, <span class="str">$meses</span>)) {
        <span class="kw">return</span> <span class="num">0</span>; <span class="comment">// Fuerza clasificación como debilidad</span>
    }

    <span class="kw">return</span> <span class="str">$iea</span>;
}

<span class="kw">public function</span> clasificar(float <span class="str">$iea</span>): string
{
    <span class="kw">return match</span>(<span class="kw">true</span>) {
        <span class="str">$iea</span> < <span class="num">0.4</span>  => <span class="str">'debilidad'</span>,
        <span class="str">$iea</span> >= <span class="num">0.8</span> => <span class="str">'fortaleza'</span>,
        <span class="kw">default</span>    => <span class="str">'neutro'</span>,
    };
}</code></div>
    </section>

    <hr class="divider">

    <!-- 4. RACI -->
    <section class="section" id="raci">
      <div class="section-header">
        <span class="section-num">§ 04</span>
        <h2>Matriz RACI automatizada</h2>
      </div>

      <div class="callout info">
        <div class="callout-label">Reforma · Cruce de Ambientes / PEI</div>
        <p>El sistema rechaza estrategias sin un <em>Accountable</em> asignado. Sin participación formal no hay compromiso medible. Cada estrategia debe tener exactamente un responsable único con autoridad sobre los recursos asociados.</p>
      </div>

      <h3>Definición de roles RACI</h3>
      <table class="raci-table">
        <thead>
          <tr>
            <th>Rol</th>
            <th>Significado</th>
            <th>Regla de negocio</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="raci-pill pill-r">R</span> Responsible</td>
            <td>Ejecuta la tarea</td>
            <td>Puede ser múltiple por estrategia</td>
          </tr>
          <tr>
            <td><span class="raci-pill pill-a">A</span> Accountable</td>
            <td>Aprueba y responde por el resultado</td>
            <td><strong>Exactamente uno</strong> por estrategia. Obligatorio.</td>
          </tr>
          <tr>
            <td><span class="raci-pill pill-c">C</span> Consulted</td>
            <td>Provee información/criterio</td>
            <td>Opcional. Se registra para trazabilidad.</td>
          </tr>
          <tr>
            <td><span class="raci-pill pill-i">I</span> Informed</td>
            <td>Recibe actualizaciones del avance</td>
            <td>Opcional. Notificado automáticamente.</td>
          </tr>
        </tbody>
      </table>

      <h3>Despliegue en cascada</h3>
      <p>Los objetivos estratégicos funcionan como "paraguas" para los operativos. Cada nivel hereda el contexto del nivel superior y restringe quién puede asumir el rol Accountable según el organigrama.</p>

      <div class="callout danger">
        <div class="callout-label">Validación bloqueante</div>
        <p>El sistema <strong>no permite guardar</strong> un enunciado estratégico sin que exista exactamente un usuario con rol <code>A</code> asignado. Si el usuario intenta hacerlo, el formulario devuelve: <em>"La estrategia requiere un responsable único (Accountable) con autoridad sobre los recursos."</em></p>
      </div>

      <h3>Implementación técnica</h3>
      <div class="code-block"><code><span class="comment">// Validación en EstrategiaRequest.php</span>
<span class="kw">public function</span> rules(): array
{
    <span class="kw">return</span> [
        <span class="str">'responsables'</span>        => [<span class="str">'required'</span>, <span class="str">'array'</span>],
        <span class="str">'responsables.*.rol'</span>   => [<span class="str">'required'</span>, <span class="str">'in:R,A,C,I'</span>],
        <span class="str">'responsables.*.user'</span>  => [<span class="str">'required'</span>, <span class="str">'exists:users,id'</span>],
    ];
}

<span class="kw">public function</span> withValidator(<span class="str">$validator</span>): void
{
    <span class="str">$validator</span>->after(<span class="kw">function</span>(<span class="str">$v</span>) {
        <span class="str">$accountables</span> = collect(<span class="str">$this->responsables</span>)
            ->where(<span class="str">'rol'</span>, <span class="str">'A'</span>)->count();

        <span class="kw">if</span> (<span class="str">$accountables</span> !== <span class="num">1</span>) {
            <span class="str">$v</span>->errors()->add(
                <span class="str">'responsables'</span>,
                <span class="str">'Debe existir exactamente un Accountable.'</span>
            );
        }
    });
}</code></div>
    </section>

    <hr class="divider">

    <!-- 5. SEMÁFORO -->
    <section class="section" id="semaforo">
      <div class="section-header">
        <span class="section-num">§ 05</span>
        <h2>Semáforo inteligente Lead/Lag</h2>
      </div>

      <div class="callout info">
        <div class="callout-label">Reforma · Monitoreo · Kaplan-Norton + Covey</div>
        <p>El semáforo es <strong>multidimensional</strong>, no lineal. Evalúa simultáneamente la <em>medida de predicción</em> (Lead) y la <em>medida de resultado</em> (Lag) para detectar cuellos de botella estratégicos invisibles a los reportes tradicionales.</p>
      </div>

      <h3>Los tres estados del semáforo</h3>
      <div class="semaforo">
        <div class="semaforo-item">
          <div class="semaforo-dot verde"></div>
          <h4>Verde — Alineado</h4>
          <p>Lead Measure ✓ y Lag Measure ✓ en sincronía. El proceso funciona correctamente.</p>
        </div>
        <div class="semaforo-item">
          <div class="semaforo-dot amarillo"></div>
          <h4>Amarillo — Cuello de botella</h4>
          <p>Lead al 100% (el equipo trabaja), pero Lag no se mueve. El proceso es ineficiente o la apuesta estratégica es errónea.</p>
        </div>
        <div class="semaforo-item">
          <div class="semaforo-dot rojo"></div>
          <h4>Rojo — Incumplimiento</h4>
          <p>Falta de recursos, falla en ejecución o abandono de la actividad comprometida.</p>
        </div>
      </div>

      <div class="callout warning">
        <div class="callout-label">Regla de negocio crítica</div>
        <p>El IPS solo medía resultados históricos (Lag). El sistema ahora <strong>exige que cada meta tenga al menos una medida de predicción</strong>. Ejemplo: en lugar de solo medir "ingresos recaudados", se exige registrar también "visitas de fiscalización realizadas".</p>
      </div>

      <h3>Semáforo predictivo</h3>
      <p>Si una medida Lead estratégica —como "horas de capacitación en el sistema"— cae por debajo del umbral durante 3 períodos consecutivos, el sistema emite una <strong>alerta predictiva</strong> indicando que la meta de resultado asociada (ej. "tasa de uso del sistema") fallará en el próximo trimestre.</p>

      <div class="code-block"><code><span class="comment">// app/Services/SemaforoService.php</span>
<span class="kw">public function</span> evaluarEstado(Meta <span class="str">$meta</span>): string
{
    <span class="str">$lagOk</span>  = <span class="str">$meta</span>->porcentajeLag() >= <span class="str">$meta</span>->umbral_lag;
    <span class="str">$leadOk</span> = <span class="str">$meta</span>->porcentajeLead() >= <span class="str">$meta</span>->umbral_lead;

    <span class="kw">return match</span>(<span class="kw">true</span>) {
        <span class="str">$lagOk</span> && <span class="str">$leadOk</span>   => <span class="str">'verde'</span>,
        !<span class="str">$lagOk</span> && <span class="str">$leadOk</span>  => <span class="str">'amarillo'</span>, <span class="comment">// Cuello de botella</span>
        <span class="kw">default</span>             => <span class="str">'rojo'</span>,
    };
}

<span class="kw">public function</span> verificarAlertaPredictiva(Meta <span class="str">$meta</span>): bool
{
    <span class="str">$periodosBajos</span> = LeadMeasure::where(<span class="str">'meta_id'</span>, <span class="str">$meta</span>->id)
        ->where(<span class="str">'valor'</span>, <span class="str">'<'</span>, <span class="str">$meta</span>->umbral_lead)
        ->ultimos(<span class="num">3</span>)->count();

    <span class="kw">return</span> <span class="str">$periodosBajos</span> >= <span class="num">3</span>; <span class="comment">// Dispara alerta predictiva</span>
}</code></div>
    </section>

    <hr class="divider">

    <!-- 6. ALERTAS PRESUPUESTARIAS -->
    <section class="section" id="alertas">
      <div class="section-header">
        <span class="section-num">§ 06</span>
        <h2>Alertas de desvío presupuestario</h2>
      </div>

      <div class="callout info">
        <div class="callout-label">Reforma · Costo por Programa</div>
        <p>El sistema vincula en tiempo real la ejecución financiera con el avance de metas. Los desvíos atípicos generan alertas automáticas para evitar tanto el sobreejercicio sin impacto como la subejecución con metas aparentemente cumplidas.</p>
      </div>

      <h3>Tipos de alerta</h3>
      <table class="alert-table">
        <thead>
          <tr>
            <th>Tipo</th>
            <th>Condición</th>
            <th>Diagnóstico probable</th>
            <th>Prioridad</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="alert-icon">🔴</span> <strong>Subejecución</strong></td>
            <td>Meta ≤ 20% · Presupuesto ≥ 80%</td>
            <td>Posible ineficiencia o desvío de fondos</td>
            <td><span class="raci-pill pill-r">CRÍTICA</span></td>
          </tr>
          <tr>
            <td><span class="alert-icon">🟡</span> <strong>Meta sin gasto</strong></td>
            <td>Meta ≥ 100% · Presupuesto ≤ 10%</td>
            <td>Presupuesto mal estimado o meta irrelevante</td>
            <td><span class="raci-pill pill-a">REVISIÓN</span></td>
          </tr>
          <tr>
            <td><span class="alert-icon">🟠</span> <strong>Desvío progresivo</strong></td>
            <td>Δ(meta/gasto) &gt; 40% por 2 períodos</td>
            <td>Descalce creciente que puede escalar a crítico</td>
            <td><span class="raci-pill pill-c">MONITOREO</span></td>
          </tr>
          <tr>
            <td><span class="alert-icon">🟢</span> <strong>Ejecución alineada</strong></td>
            <td>|meta% − gasto%| ≤ 15%</td>
            <td>Planificación correcta</td>
            <td><span class="raci-pill pill-i">OK</span></td>
          </tr>
        </tbody>
      </table>

      <div class="callout success">
        <div class="callout-label">Visibilidad operativa</div>
        <p>El tablero de alertas debe ser comprensible para el personal operativo <strong>en menos de 5 segundos</strong>, no solo para la alta dirección en reportes complejos. Se usa semáforo de colores y texto de diagnóstico en lenguaje llano.</p>
      </div>
    </section>

    <hr class="divider">

    <!-- 7. CADENCIA -->
    <section class="section" id="cadencia">
      <div class="section-header">
        <span class="section-num">§ 07</span>
        <h2>Módulo de cadencia de rendición de cuentas</h2>
      </div>

      <div class="callout info">
        <div class="callout-label">Reforma · Las 4 Disciplinas de la Ejecución (Covey)</div>
        <p>Implementa reuniones semanales cortas (20–30 min) donde el sistema registra compromisos personales de los funcionarios para mover el tablero. La cadencia convierte las metas anuales en compromisos semanales trazables.</p>
      </div>

      <h3>Flujo de la reunión semanal</h3>
      <div class="cadence-steps">
        <div class="cadence-step">
          <div class="cadence-step-num">01</div>
          <div class="cadence-step-content">
            <h4>Rendir cuentas del período anterior</h4>
            <p>Cada participante marca si cumplió su compromiso de la semana pasada. El sistema calcula la tasa de cumplimiento del equipo.</p>
          </div>
        </div>
        <div class="cadence-step">
          <div class="cadence-step-num">02</div>
          <div class="cadence-step-content">
            <h4>Revisar el tablero de metas (WIG)</h4>
            <p>Se visualizan las métricas Lead y Lag actualizadas. El sistema resalta cuáles metas están en amarillo o rojo.</p>
          </div>
        </div>
        <div class="cadence-step">
          <div class="cadence-step-num">03</div>
          <div class="cadence-step-content">
            <h4>Planificar uno o dos compromisos para la semana</h4>
            <p>Cada funcionario registra en el sistema al menos un compromiso específico, medible y con fecha. No pueden ser genéricos ("voy a trabajar en X").</p>
          </div>
        </div>
        <div class="cadence-step">
          <div class="cadence-step-num">04</div>
          <div class="cadence-step-content">
            <h4>Cierre automático de acta</h4>
            <p>El sistema genera el acta de la sesión con compromisos firmados digitalmente. Disponible para auditoría y revisión por la dirección.</p>
          </div>
        </div>
      </div>

      <div class="callout warning">
        <div class="callout-label">Regla de negocio</div>
        <p>Un funcionario con rol <code>A</code> (Accountable) en una estrategia en estado <strong>rojo</strong> por dos semanas consecutivas sin registrar compromisos recibe una notificación escalada automáticamente a su superior jerárquico en el organigrama.</p>
      </div>
    </section>

    <hr class="divider">

    <!-- 8. SCHEMA -->
    <section class="section" id="schema">
      <div class="section-header">
        <span class="section-num">§ 08</span>
        <h2>Esquema de datos — enunciados estratégicos</h2>
      </div>
      <p>La tabla <code>enunciados_estrategicos</code> incorpora metadatos de trazabilidad para garantizar la memoria institucional y la auditoría conforme a MECIP.</p>

      <table class="schema-table">
        <thead>
          <tr>
            <th>Campo</th>
            <th>Tipo</th>
            <th>Descripción</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="schema-key">id_enunciado</span></td>
            <td><span class="schema-type">PK · uuid</span></td>
            <td class="schema-desc">Clave primaria del enunciado</td>
          </tr>
          <tr>
            <td><span class="schema-key">id_dependencia</span></td>
            <td><span class="schema-type">FK · bigint</span></td>
            <td class="schema-desc">Vínculo relacional con el organigrama jerárquico (NodeTrait)</td>
          </tr>
          <tr>
            <td><span class="schema-key">id_autor</span></td>
            <td><span class="schema-type">FK · bigint</span></td>
            <td class="schema-desc">Redactor responsable de la propuesta</td>
          </tr>
          <tr>
            <td><span class="schema-key">contexto_taller</span></td>
            <td><span class="schema-type">enum</span></td>
            <td class="schema-desc">Origen: <code>foda</code> / <code>crisis</code> / <code>top_down</code></td>
          </tr>
          <tr>
            <td><span class="schema-key">id_obstaculo_diagnostico</span></td>
            <td><span class="schema-type">FK · bigint</span></td>
            <td class="schema-desc">Obligatorio. Vínculo a la debilidad/amenaza que el enunciado resuelve (Rumelt)</td>
          </tr>
          <tr>
            <td><span class="schema-key">politica_rectora</span></td>
            <td><span class="schema-type">text</span></td>
            <td class="schema-desc">Obligatorio. El "cómo" se enfrentará el obstáculo antes de fijar la meta</td>
          </tr>
          <tr>
            <td><span class="schema-key">version_timestamp</span></td>
            <td><span class="schema-type">timestamp</span></td>
            <td class="schema-desc">Historial para revisiones periódicas conforme a MECIP</td>
          </tr>
          <tr>
            <td><span class="schema-key">iea_valor</span></td>
            <td><span class="schema-type">decimal(5,4)</span></td>
            <td class="schema-desc">Valor IEA calculado al momento de clasificar el aspecto</td>
          </tr>
          <tr>
            <td><span class="schema-key">iea_clasificacion</span></td>
            <td><span class="schema-type">enum</span></td>
            <td class="schema-desc">Resultado: <code>fortaleza</code> / <code>debilidad</code> / <code>neutro</code></td>
          </tr>
        </tbody>
      </table>
    </section>

    <hr class="divider">

    <!-- 9. KPIs -->
    <section class="section" id="kpis">
      <div class="section-header">
        <span class="section-num">§ 09</span>
        <h2>KPIs preconfigurados para IPS</h2>
      </div>
      <p>El sistema viene con los siguientes indicadores preconfigurados, organizados por perspectiva del Balanced Scorecard (Kaplan-Norton).</p>

      <div class="card">
        <div class="card-title"><span class="icon">🛡️</span>Seguridad Social</div>
        <p>Porcentaje de evasión y mora por sector económico. Fuerza de fiscalización como Lead Measure.</p>
      </div>
      <div class="card">
        <div class="card-title"><span class="icon">🏥</span>Salud Pública</div>
        <p>Tasa de mortalidad intrahospitalaria y cumplimiento de protocolos de prevención. Tiempo de espera para consultas y cirugías como Lag Measure.</p>
      </div>
      <div class="card">
        <div class="card-title"><span class="icon">⚙️</span>Gestión Interna</div>
        <p>Tiempo de resolución de expedientes de jubilación (automatización). Tasa de uso del SIH como resultado de la inversión tecnológica.</p>
      </div>
      <div class="card">
        <div class="card-title"><span class="icon">📈</span>Sostenibilidad Financiera</div>
        <p>Rendimiento real de las reservas técnicas vs. inflación. Desvío presupuestario por programa.</p>
      </div>

      <div class="callout success">
        <div class="callout-label">Antifragilidad · Taleb</div>
        <p>El sistema incluye una escala de riesgo que identifica procesos que <strong>mejoran ante la volatilidad</strong>. Ejemplo: los sistemas de telemedicina se marcan como "antifrágiles" porque su adopción aumenta durante eventos disruptivos como pandemias, convirtiendo la crisis en ventaja operativa.</p>
      </div>
    </section>

  </main>
</div>

<script>
  // Active sidebar link on scroll
  const sections = document.querySelectorAll('section[id]');
  const links = document.querySelectorAll('.sidebar a');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        links.forEach(l => l.classList.remove('active'));
        const active = document.querySelector(`.sidebar a[href="#${entry.target.id}"]`);
        if (active) active.classList.add('active');
      }
    });
  }, { threshold: 0.3 });

  sections.forEach(s => observer.observe(s));
</script>

</body>
</html>