<!DOCTYPE html>
<html lang="es">
<head>
@php use Illuminate\Support\Facades\Auth; @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPLAN — Sistema de Planificación Estratégica Institucional</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:        #f1f5f9;
    --surface:   #ffffff;
    --surface2:  #f8fafc;
    --border:    #e2e8f0;
    --blue:      #2563eb;
    --blue-lt:   #eff6ff;
    --green:     #059669;
    --green-lt:  #ecfdf5;
    --amber:     #d97706;
    --amber-lt:  #fffbeb;
    --red:       #dc2626;
    --red-lt:    #fef2f2;
    --violet:    #7c3aed;
    --violet-lt: #f5f3ff;
    --text:      #0f172a;
    --muted:     #64748b;
    --r:         14px;
    --sh:        0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --sh-md:     0 4px 20px rgba(0,0,0,.08);
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    font-size: 14px;
    line-height: 1.6;
    min-height: 100vh;
}

/* ── NAV ─────────────────────────────── */
.nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,255,255,.9);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid var(--border);
    padding: 0 clamp(16px, 4vw, 40px);
    height: 60px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px;
}
.nav-brand { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.nav-logo {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, #1d4ed8 0%, #7c3aed 100%);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 900; font-size: 13px; letter-spacing: -1px;
    box-shadow: 0 4px 12px rgba(37,99,235,.35);
}
.nav-name { font-weight: 800; font-size: 15px; color: var(--text); letter-spacing: -.3px; }
.nav-tagline { font-size: 10px; color: var(--muted); line-height: 1; }
.nav-right { display: flex; align-items: center; gap: 10px; }
.live-pill {
    font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px;
    background: var(--green-lt); color: var(--green); border: 1px solid #a7f3d0;
    display: flex; align-items: center; gap: 6px;
}
.live-dot {
    width: 7px; height: 7px; border-radius: 50%; background: var(--green);
    animation: blink 1.8s infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
.btn-enter {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; border-radius: 10px; font-size: 13px; font-weight: 700;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: #fff; border: none; cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 14px rgba(37,99,235,.35);
    transition: opacity .15s, transform .1s;
    white-space: nowrap;
}
.btn-enter:hover { opacity: .92; transform: translateY(-1px); color: #fff; text-decoration: none; }

/* ── HERO ────────────────────────────── */
.hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #1e40af 100%);
    padding: clamp(40px,6vw,72px) clamp(16px,4vw,40px) clamp(36px,5vw,64px);
    position: relative; overflow: hidden;
}
.hero-glow {
    position: absolute; inset: 0; pointer-events: none;
    background:
        radial-gradient(ellipse 60% 80% at 80% 40%, rgba(124,58,237,.3) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at 20% 80%, rgba(37,99,235,.2) 0%, transparent 50%);
}
.hero-grid {
    position: absolute; inset: 0; pointer-events: none; opacity: .04;
    background-image: linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
    background-size: 40px 40px;
}
.hero-inner {
    max-width: 1100px; margin: 0 auto; position: relative; z-index: 1;
    display: grid; grid-template-columns: 1fr auto; gap: 32px; align-items: end;
}
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    color: #93c5fd; background: rgba(147,197,253,.1); border: 1px solid rgba(147,197,253,.2);
    padding: 5px 14px; border-radius: 20px; margin-bottom: 18px;
}
.hero h1 {
    font-size: clamp(1.75rem, 4.5vw, 3.2rem);
    font-weight: 900; color: #fff; line-height: 1.1;
    letter-spacing: -.5px; margin-bottom: 14px;
}
.hero h1 em { color: #93c5fd; font-style: normal; }
.hero-desc { font-size: 14px; color: rgba(255,255,255,.6); max-width: 480px; margin-bottom: 32px; }
.hero-kpis { display: flex; flex-wrap: wrap; gap: 0; }
.hero-kpi {
    display: flex; flex-direction: column; padding: 0 24px 0 0;
    border-right: 1px solid rgba(255,255,255,.15);
    margin-right: 0; margin-bottom: 8px;
}
.hero-kpi:first-child { padding-left: 0; }
.hero-kpi:last-child { border-right: none; }
.hero-kpi-num { font-size: clamp(1.6rem,3vw,2.2rem); font-weight: 900; color: #fff; line-height: 1; }
.hero-kpi-lbl { font-size: 10px; color: rgba(255,255,255,.45); text-transform: uppercase; letter-spacing: .8px; margin-top: 4px; }
.hero-badge-col {
    display: flex; flex-direction: column; gap: 10px; align-self: flex-start;
}
.hero-badge {
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
    border-radius: 12px; padding: 14px 18px; text-align: center; min-width: 110px;
    backdrop-filter: blur(8px);
}
.hero-badge-num { font-size: 1.6rem; font-weight: 900; color: #fff; line-height: 1; }
.hero-badge-lbl { font-size: 10px; color: rgba(255,255,255,.5); margin-top: 4px; text-transform: uppercase; letter-spacing: .5px; }

/* ── WRAPPER ─────────────────────────── */
.wrap { max-width: 1100px; margin: 0 auto; padding: clamp(20px,4vw,40px) clamp(16px,4vw,40px) 80px; }

/* ── SECTION HEADER ──────────────────── */
.sec-header {
    display: flex; align-items: center; gap: 10px; margin-bottom: 16px;
}
.sec-icon {
    width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.sec-title { font-size: 14px; font-weight: 800; color: var(--text); }
.sec-sub    { font-size: 11px; color: var(--muted); margin-left: 4px; }

/* ── KPI STRIP ───────────────────────── */
.kpi-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 32px;
}
.kpi-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r); padding: 18px 20px;
    box-shadow: var(--sh);
    display: flex; align-items: center; gap: 14px;
    transition: box-shadow .2s, transform .15s;
}
.kpi-card:hover { box-shadow: var(--sh-md); transform: translateY(-2px); }
.kpi-ic {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.kpi-ic.blue   { background: var(--blue-lt);   color: var(--blue);   }
.kpi-ic.green  { background: var(--green-lt);  color: var(--green);  }
.kpi-ic.amber  { background: var(--amber-lt);  color: var(--amber);  }
.kpi-ic.red    { background: var(--red-lt);    color: var(--red);    }
.kpi-ic.violet { background: var(--violet-lt); color: var(--violet); }
.kpi-num { font-size: 1.7rem; font-weight: 900; color: var(--text); line-height: 1; }
.kpi-lbl { font-size: 11px; color: var(--muted); margin-top: 3px; }

/* ── ACTIVITIES ──────────────────────── */
.activities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
    margin-bottom: 36px;
}
.act-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r); padding: 18px 20px;
    box-shadow: var(--sh); position: relative; overflow: hidden;
    transition: box-shadow .2s, transform .15s;
}
.act-card:hover { box-shadow: var(--sh-md); transform: translateY(-2px); }
.act-card-accent {
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--blue), var(--violet));
}
.act-type {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    padding: 3px 9px; border-radius: 20px; margin-bottom: 10px;
}
.act-type.scrum  { background: var(--violet-lt); color: var(--violet); }
.act-type.kanban { background: var(--blue-lt);   color: var(--blue);   }
.act-name { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px; line-height: 1.4; }
.act-meta { font-size: 11px; color: var(--muted); display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
.act-progress { height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; margin-bottom: 12px; }
.act-progress-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--blue), var(--violet)); }
.act-footer { display: flex; justify-content: space-between; align-items: center; }
.act-pills { display: flex; gap: 5px; flex-wrap: wrap; }
.act-pill {
    font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px;
    display: flex; align-items: center; gap: 3px;
}
.pill-p { background: var(--amber-lt); color: var(--amber); }
.pill-r { background: var(--blue-lt);  color: var(--blue);  }
.pill-d { background: var(--green-lt); color: var(--green); }
.pill-v { background: var(--red-lt);   color: var(--red);   }
.act-pct { font-size: 12px; font-weight: 800; color: var(--blue); }

/* ── TWIN GRID ───────────────────────── */
.twin { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 36px; }

/* ── CARD ────────────────────────────── */
.card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r); box-shadow: var(--sh); overflow: hidden;
}
.card-head {
    padding: 14px 18px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.card-head-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; gap: 8px;
}
.card-count {
    font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 20px;
    background: var(--blue-lt); color: var(--blue);
}

/* ── TABLE ───────────────────────────── */
table.t { width: 100%; border-collapse: collapse; }
table.t th {
    font-size: 10px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase;
    color: var(--muted); padding: 10px 16px; text-align: left;
    border-bottom: 1px solid var(--border); background: var(--surface2);
}
table.t td {
    font-size: 12px; padding: 11px 16px;
    border-bottom: 1px solid #f1f5f9; color: var(--text); vertical-align: middle;
}
table.t tr:last-child td { border-bottom: none; }
table.t tr:hover td { background: #fafcff; }

/* ── BADGES ──────────────────────────── */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px;
}
.b-green  { background: var(--green-lt);  color: var(--green);  }
.b-blue   { background: var(--blue-lt);   color: var(--blue);   }
.b-amber  { background: var(--amber-lt);  color: var(--amber);  }
.b-red    { background: var(--red-lt);    color: var(--red);    }
.b-violet { background: var(--violet-lt); color: var(--violet); }
.b-gray   { background: #f1f5f9;          color: var(--muted);  }

/* ── PROGRESS BAR ────────────────────── */
.pct-row { display: flex; align-items: center; gap: 8px; }
.pct-track { flex: 1; height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; min-width: 40px; }
.pct-fill { height: 100%; border-radius: 2px; }
.pct-hi { background: var(--green); }
.pct-md { background: var(--amber); }
.pct-lo { background: var(--red);   }
.pct-val { font-size: 11px; font-weight: 700; min-width: 34px; text-align: right; }

/* ── FODA QUAD ───────────────────────── */
.foda-quad {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 1px; background: var(--border);
}
.foda-cell { padding: 18px; text-align: center; }
.foda-num  { font-size: 2rem; font-weight: 900; line-height: 1; }
.foda-lbl  { font-size: 10px; font-weight: 700; margin-top: 5px; text-transform: uppercase; letter-spacing: .5px; }

/* ── SEMAFORO STRIP ──────────────────── */
.sem-strip {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 1px; background: var(--border);
}
.sem-cell { padding: 16px; text-align: center; }
.sem-dot  { width: 14px; height: 14px; border-radius: 50%; margin: 0 auto 8px; }
.sem-num  { font-size: 1.5rem; font-weight: 900; line-height: 1; }
.sem-lbl  { font-size: 10px; font-weight: 700; margin-top: 4px; }

/* ── SIESS MODULES ───────────────────── */
.siess-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}
.siess-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r); padding: 14px 16px; box-shadow: var(--sh);
}
.siess-name { font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 3px; }
.siess-code { font-size: 10px; color: var(--muted); font-family: monospace; margin-bottom: 10px; }
.siess-pills { display: flex; flex-wrap: wrap; gap: 4px; }
.siess-pill  { font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 4px; }

/* ── EXTRAS ──────────────────────────── */
.row-summary {
    padding: 12px 16px; border-top: 1px solid var(--border);
    background: var(--surface2); display: flex; gap: 20px; flex-wrap: wrap;
}
.row-summary span { font-size: 11px; color: var(--muted); }
.row-summary b    { font-weight: 700; }
.card-rows { padding: 14px 18px; display: flex; flex-direction: column; gap: 10px; }
.card-row  { display: flex; justify-content: space-between; align-items: center; font-size: 12px; }
.card-row .lbl { color: var(--muted); }
.card-row .val { font-weight: 700; }

/* ── EMPTY ───────────────────────────── */
.empty { text-align: center; padding: 36px 16px; color: var(--muted); font-size: 12px; }
.empty i { font-size: 26px; display: block; margin-bottom: 8px; opacity: .35; }

/* ── FOOTER ──────────────────────────── */
footer {
    border-top: 1px solid var(--border);
    background: var(--surface);
    padding: 36px 24px 28px;
    color: var(--muted); font-size: 11px; line-height: 1.8;
}
footer strong { color: var(--text); }
.footer-inner { max-width: 1100px; margin: 0 auto; }
.footer-top { display:flex;align-items:flex-start;justify-content:space-between;gap:24px;flex-wrap:wrap;margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--border); }
.footer-brand { display:flex;align-items:center;gap:10px;margin-bottom:8px }
.footer-logo { width:40px;height:40px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,#1d4ed8,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:14px;letter-spacing:-1px }
.footer-brand-name { font-size:14px;font-weight:800;color:var(--text) }
.footer-brand-sub  { font-size:10px;color:var(--muted) }
.footer-dev { text-align:right }
.footer-dev-label { font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:10px;font-weight:600 }
.ai-badges { display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:flex-end }
.ai-badge { display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border-radius:8px;font-size:11px;font-weight:600;border:1px solid var(--border);background:var(--surface2);color:var(--text);text-decoration:none;transition:box-shadow .15s,transform .1s }
.ai-badge:hover { box-shadow:0 2px 8px rgba(0,0,0,.1);transform:translateY(-1px) }
.ai-badge-kiro   { border-color:#f59e0b44;background:#fffbeb;color:#92400e }
.ai-badge-claude { border-color:#f9731688;background:#fff7ed;color:#9a3412 }
.ai-badge-gemini { border-color:#3b82f688;background:#eff6ff;color:#1e40af }
.footer-dev-by { margin-top:8px;font-size:11px;color:var(--muted);text-align:right }
.footer-dev-by a { color:var(--blue);text-decoration:none;font-weight:600 }
.footer-dev-by a:hover { text-decoration:underline }
.footer-bottom { text-align:center;font-size:10px;color:var(--muted) }
.footer-bottom span { margin:0 6px }

/* ════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════ */
@media (max-width: 900px) {
    .twin { grid-template-columns: 1fr; }
    .hero-inner { grid-template-columns: 1fr; }
    .hero-badge-col { flex-direction: row; flex-wrap: wrap; align-self: auto; }
    .hero-badge { min-width: 90px; flex: 1; }
    .kpi-strip { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 600px) {
    .kpi-strip { grid-template-columns: repeat(2, 1fr); }
    .activities-grid { grid-template-columns: 1fr; }
    .hero-kpis { gap: 16px; }
    .hero-kpi { padding-right: 16px; border: none; padding-bottom: 0; }
    .live-pill { display: none; }
    .nav-tagline { display: none; }
    table.t th, table.t td { padding: 8px 10px; }
    .foda-quad, .sem-strip { grid-template-columns: 1fr 1fr; }
    .siess-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 400px) {
    .kpi-strip { grid-template-columns: 1fr 1fr; }
    .siess-grid { grid-template-columns: 1fr; }
    .hero-badge-col { display: none; }
}
</style>
</head>
<body>

{{-- NAV --}}
<nav class="nav">
    <div class="nav-brand">
        <div class="nav-logo">SP</div>
        <div>
            <div class="nav-name">SIPLAN</div>
            <div class="nav-tagline">IPS · Planificación Estratégica</div>
        </div>
    </div>
    <div class="nav-right">
        <div class="live-pill"><span class="live-dot"></span>En línea</div>
        @auth
        <a href="{{ route('home') }}" class="btn-enter">
            <i class="fa fa-th-large"></i><span>Ir al sistema</span>
        </a>
        @else
        <a href="{{ route('login') }}" class="btn-enter">
            <i class="fa fa-sign-in-alt"></i><span>Ingresar</span>
        </a>
        @endauth
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-glow"></div>
    <div class="hero-grid"></div>
    <div class="hero-inner">
        <div>
            <div class="hero-eyebrow"><i class="fa fa-shield-alt"></i> Instituto de Previsión Social — Paraguay</div>
            <h1>Planificación<br><em>Estratégica</em><br>Institucional</h1>
            <p class="hero-desc">Monitoreo en tiempo real de actividades, evaluaciones de la Red de Salud IPS y estadísticas institucionales.</p>
            <div class="hero-kpis">
                <div class="hero-kpi">
                    <span class="hero-kpi-num">{{ $totalTareas }}</span>
                    <span class="hero-kpi-lbl">Tareas</span>
                </div>
                <div class="hero-kpi">
                    <span class="hero-kpi-num">{{ $tareasEnCurso }}</span>
                    <span class="hero-kpi-lbl">En ejecución</span>
                </div>
                <div class="hero-kpi">
                    <span class="hero-kpi-num">{{ $tareasHechas }}</span>
                    <span class="hero-kpi-lbl">Completadas</span>
                </div>
                <div class="hero-kpi">
                    <span class="hero-kpi-num">{{ $evalTotal }}</span>
                    <span class="hero-kpi-lbl">Evaluaciones</span>
                </div>
            </div>
        </div>
        <div class="hero-badge-col">
            <div class="hero-badge">
                <div class="hero-badge-num">{{ $peiPlanes }}</div>
                <div class="hero-badge-lbl">Planes PEI</div>
            </div>
            <div class="hero-badge">
                <div class="hero-badge-num">{{ $siessAprobados }}</div>
                <div class="hero-badge-lbl">SIESS aprobados</div>
            </div>
            <div class="hero-badge">
                <div class="hero-badge-num">{{ $fodaAnalisis }}</div>
                <div class="hero-badge-lbl">Análisis FODA</div>
            </div>
        </div>
    </div>
</section>

<div class="wrap">

{{-- ═══════════════════════════════════════════════════════════════
     TABS DE DEPARTAMENTO
═══════════════════════════════════════════════════════════════ --}}
<style>
/* Dept tabs */
.dept-nav{display:flex;gap:2px;background:var(--border);border-radius:12px;padding:3px;margin-bottom:24px;flex-wrap:wrap}
.dept-tab{flex:1;min-width:120px;display:flex;align-items:center;justify-content:center;gap:7px;
    padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;color:var(--muted);
    border:none;background:transparent;cursor:pointer;transition:all .15s;white-space:nowrap}
.dept-tab:hover{color:var(--text);background:rgba(255,255,255,.6)}
.dept-tab.active{background:var(--surface);color:var(--text);box-shadow:0 1px 4px rgba(0,0,0,.1)}
.dept-tab i{font-size:13px}
.dept-section{display:none}.dept-section.active{display:block}

/* Tabla de niveles */
.nivel-table th{font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);
    padding:.5rem .75rem;background:var(--surface2);border-bottom:2px solid var(--border)}
.nivel-table td{padding:.5rem .75rem;font-size:.8rem;vertical-align:middle;border-bottom:1px solid #f1f5f9}
.nivel-table tr:last-child td{border-bottom:none}
.nivel-tag{display:inline-block;padding:.15rem .5rem;border-radius:20px;font-size:.68rem;font-weight:600}
</style>

{{-- Nav tabs departamentales --}}
<div class="dept-nav" id="deptTabs">
    <button class="dept-tab active" data-dept="riiss">
        <i class="fa fa-hospital"></i> Red de Salud RIISS
    </button>
    <button class="dept-tab" data-dept="planificacion">
        <i class="fa fa-bullseye"></i> Planificación
    </button>
    <button class="dept-tab" data-dept="estadisticas">
        <i class="fa fa-chart-bar"></i> Estadísticas
    </button>
</div>

{{-- ══ RIISS ══════════════════════════════════════════════════════════════════ --}}
<div class="dept-section active" id="dept-riiss">

    {{-- KPIs RIISS --}}
    <div class="kpi-strip" style="margin-bottom:24px">
        <div class="kpi-card">
            <div class="kpi-ic blue"><i class="fa fa-clipboard-list"></i></div>
            <div><div class="kpi-num">{{ $evalTotal }}</div><div class="kpi-lbl">Evaluaciones totales</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic green"><i class="fa fa-check-circle"></i></div>
            <div><div class="kpi-num">{{ $evalCompletadas }}</div><div class="kpi-lbl">Completadas</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic amber"><i class="fa fa-spinner"></i></div>
            <div><div class="kpi-num">{{ $evalEnCurso }}</div><div class="kpi-lbl">En curso</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic violet"><i class="fa fa-sitemap"></i></div>
            <div><div class="kpi-num">{{ \App\Models\Riiss\Establecimiento::count() }}</div><div class="kpi-lbl">Establecimientos</div></div>
        </div>
    </div>

    <div class="twin">

        {{-- Últimas evaluaciones --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:var(--green-lt);color:var(--green)"><i class="fa fa-clipboard-check"></i></div>
                <div><span class="sec-title">Últimas evaluaciones</span><span class="sec-sub">· RIISS Red de Salud IPS</span></div>
            </div>
            <div class="card">
                @if($evalRecientes->count())
                <div style="overflow-x:auto">
                <table class="t">
                    <thead><tr>
                        <th>Establecimiento</th>
                        <th>Nivel</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-right">% Cumpl.</th>
                    </tr></thead>
                    <tbody>
                    @foreach($evalRecientes as $ev)
                    @php
                        $p   = (float)($ev->porcentaje_cumplimiento ?? 0);
                        $cls = $p >= 90 ? 'b-green' : ($p >= 70 ? 'b-amber' : 'b-red');
                        $stc = ['completada'=>'b-green','en_curso'=>'b-blue','borrador'=>'b-gray'][$ev->estado] ?? 'b-gray';
                    @endphp
                    <tr>
                        <td style="font-weight:600;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $ev->establecimiento?->nombre ?? '—' }}
                        </td>
                        <td>
                            <span style="font-size:.68rem;color:var(--muted)">
                                {{ $ev->establecimiento?->complejidadTipo?->nombre ?? '—' }}
                            </span>
                        </td>
                        <td style="white-space:nowrap;color:var(--muted);font-size:11px">
                            {{ $ev->fecha_evaluacion ? \Carbon\Carbon::parse($ev->fecha_evaluacion)->format('d/m/Y') : '—' }}
                        </td>
                        <td><span class="badge {{ $stc }}">{{ ucfirst(str_replace('_',' ',$ev->estado)) }}</span></td>
                        <td class="text-right">
                            <div class="pct-row" style="justify-content:flex-end">
                                <div class="pct-track" style="min-width:50px">
                                    <div class="pct-fill {{ $p>=90?'pct-hi':($p>=70?'pct-md':'pct-lo') }}" style="width:{{ $p }}%"></div>
                                </div>
                                <span class="pct-val {{ $p>=90?'':($p>=70?'':'') }}" style="color:{{ $p>=90?'var(--green)':($p>=70?'var(--amber)':'var(--red)') }}">{{ $p }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                @else
                <div class="empty"><i class="fa fa-hospital"></i>Sin evaluaciones</div>
                @endif
            </div>
        </div>

        {{-- Tabla de niveles de complejidad --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:#fdf4ff;color:#9333ea"><i class="fa fa-layer-group"></i></div>
                <div><span class="sec-title">Niveles de complejidad</span><span class="sec-sub">· Clasificación RIISS</span></div>
            </div>
            <div class="card">
                <div style="overflow-x:auto">
                <table class="t nivel-table" style="width:100%">
                    <thead><tr>
                        <th>Grado</th>
                        <th>Clasificación</th>
                        <th>Tipo de establecimiento</th>
                        <th class="text-center">Hospitalario</th>
                    </tr></thead>
                    <tbody>
                    @foreach($complejidadTipos as $ct)
                    <tr>
                        <td>
                            <span class="nivel-tag"
                                  style="background:{{ $ct->color ?? '#e2e8f0' }}22;
                                         color:{{ $ct->color ?? '#475569' }};
                                         border:1px solid {{ $ct->color ?? '#e2e8f0' }}55">
                                Grado {{ $ct->grado }}
                            </span>
                        </td>
                        <td style="font-weight:600;font-size:.8rem">{{ $ct->nombre }}</td>
                        <td style="font-size:.75rem;color:var(--muted)">{{ $ct->tipo_establecimiento ?? '—' }}</td>
                        <td class="text-center">
                            @if($ct->es_hospitalario)
                                <span class="badge b-blue"><i class="fa fa-hospital" style="font-size:.6rem"></i> Sí</span>
                            @else
                                <span class="badge b-gray">No</span>
                            @endif
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

{{-- ══ PLANIFICACIÓN ══════════════════════════════════════════════════════════ --}}
<div class="dept-section" id="dept-planificacion">

    <div class="kpi-strip" style="margin-bottom:24px">
        <div class="kpi-card">
            <div class="kpi-ic violet"><i class="fa fa-bullseye"></i></div>
            <div><div class="kpi-num">{{ $peiPlanes }}</div><div class="kpi-lbl">Planes PEI</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic blue"><i class="fa fa-rocket"></i></div>
            <div><div class="kpi-num">{{ $peiAcciones }}</div><div class="kpi-lbl">Acciones estratégicas</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic amber"><i class="fa fa-th-large"></i></div>
            <div><div class="kpi-num">{{ $fodaAnalisis }}</div><div class="kpi-lbl">Análisis FODA</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic green"><i class="fa fa-chess"></i></div>
            <div><div class="kpi-num">{{ $fodaEstrategias }}</div><div class="kpi-lbl">Estrategias de cruce</div></div>
        </div>
    </div>

    <div class="twin">

        {{-- Planes PEI --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:var(--violet-lt);color:var(--violet)"><i class="fa fa-bullseye"></i></div>
                <div><span class="sec-title">Planes Estratégicos</span><span class="sec-sub">· PEI activos</span></div>
            </div>
            <div class="card">
                <div class="sem-strip">
                    <div class="sem-cell" style="background:#ecfdf5">
                        <div class="sem-dot" style="background:#059669;box-shadow:0 0 8px rgba(5,150,105,.4)"></div>
                        <div class="sem-num" style="color:var(--green)">{{ $peiSemaforo->get('verde', 0) }}</div>
                        <div class="sem-lbl" style="color:var(--green)">Verde</div>
                    </div>
                    <div class="sem-cell" style="background:var(--amber-lt)">
                        <div class="sem-dot" style="background:#d97706;box-shadow:0 0 8px rgba(217,119,6,.4)"></div>
                        <div class="sem-num" style="color:var(--amber)">{{ $peiSemaforo->get('amarillo', 0) }}</div>
                        <div class="sem-lbl" style="color:var(--amber)">Amarillo</div>
                    </div>
                    <div class="sem-cell" style="background:var(--red-lt)">
                        <div class="sem-dot" style="background:#dc2626;box-shadow:0 0 8px rgba(220,38,38,.4)"></div>
                        <div class="sem-num" style="color:var(--red)">{{ $peiSemaforo->get('rojo', 0) }}</div>
                        <div class="sem-lbl" style="color:var(--red)">Rojo</div>
                    </div>
                </div>
                @if($peiRecientes->count())
                <div style="border-top:1px solid var(--border)">
                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">
                        Planes disponibles
                    </div>
                    @foreach($peiRecientes as $pei)
                    <div style="padding:10px 18px;border-bottom:1px solid #f8fafc;display:flex;align-items:center;justify-content:space-between;gap:8px">
                        <div style="min-width:0;flex:1">
                            <div style="font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ \Illuminate\Support\Str::limit(strip_tags($pei->name), 42) }}
                            </div>
                            @if($pei->year_start)
                            <div style="font-size:10px;color:var(--muted)">
                                {{ \Carbon\Carbon::parse($pei->year_start)->format('Y') }}–{{ $pei->year_end ? \Carbon\Carbon::parse($pei->year_end)->format('Y') : '' }}
                            </div>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;flex-shrink:0">
                            @if($pei->public_token)
                            <a href="{{ route('pei.public.show', $pei->public_token) }}" target="_blank"
                               style="font-size:.68rem;color:var(--blue);text-decoration:none;display:flex;align-items:center;gap:3px;
                                      background:var(--blue-lt);padding:2px 7px;border-radius:20px">
                                <i class="fa fa-external-link-alt" style="font-size:.6rem"></i> Ver
                            </a>
                            @endif
                            @if($pei->semaforo)
                            <span style="width:9px;height:9px;border-radius:50%;display:inline-block;background:{{ $pei->semaforo==='verde'?'#059669':($pei->semaforo==='amarillo'?'#d97706':'#dc2626') }}"></span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- FODA --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:var(--amber-lt);color:var(--amber)"><i class="fa fa-th-large"></i></div>
                <div><span class="sec-title">Análisis FODA</span><span class="sec-sub">· Planificación estratégica</span></div>
            </div>
            <div class="card">
                <div class="foda-quad">
                    <div class="foda-cell" style="background:#ecfdf5">
                        <div class="foda-num" style="color:var(--green)">{{ $fodaFortalezas }}</div>
                        <div class="foda-lbl" style="color:var(--green)"><i class="fa fa-shield-alt"></i> Fortalezas</div>
                    </div>
                    <div class="foda-cell" style="background:var(--blue-lt)">
                        <div class="foda-num" style="color:var(--blue)">{{ $fodaOportunidades }}</div>
                        <div class="foda-lbl" style="color:var(--blue)"><i class="fa fa-star"></i> Oportunidades</div>
                    </div>
                    <div class="foda-cell" style="background:var(--red-lt)">
                        <div class="foda-num" style="color:var(--red)">{{ $fodaDebilidades }}</div>
                        <div class="foda-lbl" style="color:var(--red)"><i class="fa fa-exclamation-triangle"></i> Debilidades</div>
                    </div>
                    <div class="foda-cell" style="background:#fff7ed">
                        <div class="foda-num" style="color:#ea580c">{{ $fodaAmenazas }}</div>
                        <div class="foda-lbl" style="color:#ea580c"><i class="fa fa-bolt"></i> Amenazas</div>
                    </div>
                </div>
                <div class="card-rows">
                    <div class="card-row"><span class="lbl">Perfiles FODA</span><span class="val">{{ $fodaPerfiles }}</span></div>
                    <div class="card-row"><span class="lbl">Aspectos analizados</span><span class="val">{{ $fodaAnalisis }}</span></div>
                    <div class="card-row"><span class="lbl">Estrategias de cruce</span><span class="val" style="color:var(--violet)">{{ $fodaEstrategias }}</span></div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══ ESTADÍSTICAS ══════════════════════════════════════════════════════════ --}}
<div class="dept-section" id="dept-estadisticas">

    <div class="kpi-strip" style="margin-bottom:24px">
        <div class="kpi-card">
            <div class="kpi-ic green"><i class="fa fa-check-double"></i></div>
            <div><div class="kpi-num">{{ $siessAprobados }}</div><div class="kpi-lbl">Extractos aprobados</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic amber"><i class="fa fa-clock"></i></div>
            <div><div class="kpi-num">{{ $siessPendientes }}</div><div class="kpi-lbl">Pendientes</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic red"><i class="fa fa-times-circle"></i></div>
            <div><div class="kpi-num">{{ $siessObjetados }}</div><div class="kpi-lbl">Objetados</div></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic blue"><i class="fa fa-database"></i></div>
            <div><div class="kpi-num">{{ $siessModulos->count() }}</div><div class="kpi-lbl">Módulos activos</div></div>
        </div>
    </div>

    <div class="sec-header">
        <div class="sec-icon" style="background:var(--violet-lt);color:var(--violet)"><i class="fa fa-chart-line"></i></div>
        <div><span class="sec-title">Módulos SIESS</span><span class="sec-sub">· Estadísticas institucionales</span></div>
    </div>

    @if($siessModulos->count())
    <div class="siess-grid">
        @foreach($siessModulos as $mod)
        @php $res = $mod->resumenEstados(); @endphp
        <div class="siess-card">
            <div class="siess-name">{{ $mod->nombre }}</div>
            <div class="siess-code">{{ $mod->codigo }} · {{ ucfirst($mod->periodicidad ?? '') }}</div>
            <div class="siess-pills">
                @if(($res['aprobado'] ?? 0) + ($res['aprobado_silencio'] ?? 0) > 0)
                <span class="siess-pill" style="background:var(--green-lt);color:var(--green)"><i class="fa fa-check" style="font-size:9px"></i> {{ ($res['aprobado'] ?? 0) + ($res['aprobado_silencio'] ?? 0) }}</span>
                @endif
                @if(($res['pendiente_validacion'] ?? 0) > 0)
                <span class="siess-pill" style="background:var(--amber-lt);color:var(--amber)"><i class="fa fa-clock" style="font-size:9px"></i> {{ $res['pendiente_validacion'] }}</span>
                @endif
                @if(($res['objetado'] ?? 0) > 0)
                <span class="siess-pill" style="background:var(--red-lt);color:var(--red)"><i class="fa fa-times" style="font-size:9px"></i> {{ $res['objetado'] }}</span>
                @endif
                @if(($res['borrador'] ?? 0) > 0)
                <span class="siess-pill" style="background:#f1f5f9;color:var(--muted)">{{ $res['borrador'] }} borr.</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card"><div class="empty"><i class="fa fa-chart-bar"></i>Sin módulos activos</div></div>
    @endif

</div>

</div>{{-- /wrap --}}

<footer>
<div class="footer-inner">

    <div class="footer-top">
        {{-- Brand --}}
        <div>
            <div class="footer-brand" style="margin-bottom:8px">
                <div class="footer-logo">SP</div>
                <div>
                    <div class="footer-brand-name">SIPLAN</div>
                    <div class="footer-brand-sub">Sistema de Planificación Estratégica · IPS Paraguay</div>
                </div>
            </div>
            <div style="font-size:11px;color:var(--muted);max-width:340px;line-height:1.6">
                Plataforma de monitoreo estratégico institucional para el
                <strong style="color:var(--text)">Instituto de Previsión Social del Paraguay</strong>.
                Datos en tiempo real.
            </div>
        </div>

        {{-- Desarrollado con IA --}}
        <div class="footer-dev">
            <div class="footer-dev-label">Desarrollado con asistencia de IA</div>
            <div class="ai-badges">
                {{-- Amazon Kiro --}}
                <a class="ai-badge ai-badge-kiro" href="https://kiro.dev" target="_blank" title="Amazon Kiro">
                    <svg width="16" height="16" viewBox="0 0 48 48" fill="none">
                        <rect width="48" height="48" rx="8" fill="#F59E0B"/>
                        <path d="M12 36L24 12L36 36H28L24 27L20 36H12Z" fill="white"/>
                    </svg>
                    Kiro
                </a>
                {{-- Amazon Q --}}
                <a class="ai-badge" href="https://aws.amazon.com/q/" target="_blank"
                   title="Amazon Q"
                   style="border-color:#22927344;background:#ecfdf5;color:#166534">
                    <svg width="16" height="16" viewBox="0 0 48 48" fill="none">
                        <rect width="48" height="48" rx="8" fill="#1A9C3E"/>
                        <path d="M24 10C16.3 10 10 16.3 10 24C10 31.7 16.3 38 24 38C27.4 38 30.5 36.8 32.9 34.8L36 38L38 36L34.8 32.9C36.8 30.5 38 27.4 38 24C38 16.3 31.7 10 24 10ZM24 34C18.5 34 14 29.5 14 24C14 18.5 18.5 14 24 14C29.5 14 34 18.5 34 24C34 26.6 33 29 31.3 30.8L27 26.5C27.6 25.8 28 24.9 28 24C28 21.8 26.2 20 24 20C21.8 20 20 21.8 20 24C20 26.2 21.8 28 24 28C24.9 28 25.8 27.6 26.5 27L30.8 31.3C29 33 26.6 34 24 34Z" fill="white"/>
                    </svg>
                    Amazon Q
                </a>
                {{-- Claude --}}
                <a class="ai-badge ai-badge-claude" href="https://claude.ai" target="_blank" title="Anthropic Claude">
                    <svg width="16" height="16" viewBox="0 0 48 48" fill="none">
                        <rect width="48" height="48" rx="8" fill="#F97316"/>
                        <path d="M24 8L38 32H10L24 8Z" fill="white" opacity=".9"/>
                        <path d="M16 32L24 18L32 32" fill="#F97316"/>
                    </svg>
                    Claude
                </a>
                {{-- Gemini --}}
                <a class="ai-badge ai-badge-gemini" href="https://gemini.google.com" target="_blank" title="Google Gemini">
                    <svg width="16" height="16" viewBox="0 0 48 48" fill="none">
                        <rect width="48" height="48" rx="8" fill="#4285F4"/>
                        <path d="M24 8C24 8 30 20 30 24C30 28 24 40 24 40C24 40 18 28 18 24C18 20 24 8 24 8Z" fill="white"/>
                        <path d="M8 24C8 24 20 18 24 18C28 18 40 24 40 24C40 24 28 30 24 30C20 30 8 24 8 24Z" fill="white" opacity=".7"/>
                    </svg>
                    Gemini
                </a>
            </div>
            <div class="footer-dev-by">
                Desarrollado por
                <a href="https://www.linkedin.com/in/jufrancopy/" target="_blank">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="#0077b5" style="vertical-align:middle;margin-right:2px"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                    Julio Franco
                </a>
                · IPS Paraguay
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <span>© {{ date('Y') }} SIPLAN</span>
        <span>·</span>
        <span>Instituto de Previsión Social del Paraguay</span>
        <span>·</span>
        <span>Todos los derechos reservados</span>
    </div>

</div>
</footer>

<script>
document.querySelectorAll('.dept-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.dept-tab').forEach(function(b){ b.classList.remove('active'); });
        document.querySelectorAll('.dept-section').forEach(function(s){ s.classList.remove('active'); });
        this.classList.add('active');
        document.getElementById('dept-' + this.dataset.dept).classList.add('active');
        window.location.hash = this.dataset.dept;
    });
});
var hash = window.location.hash.replace('#','');
if(hash && document.querySelector('.dept-tab[data-dept="'+hash+'"]')) {
    document.querySelector('.dept-tab[data-dept="'+hash+'"]').click();
}
</script>

</body>
</html>
