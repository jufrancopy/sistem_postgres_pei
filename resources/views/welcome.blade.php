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
    text-align: center; padding: 28px 16px;
    border-top: 1px solid var(--border);
    color: var(--muted); font-size: 11px; line-height: 1.8;
}
footer strong { color: var(--text); }

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

    {{-- KPI STRIP --}}
    <div class="kpi-strip" style="margin-top: 8px;">
        <div class="kpi-card">
            <div class="kpi-ic blue"><i class="fa fa-spinner"></i></div>
            <div>
                <div class="kpi-num">{{ $tareasEnCurso }}</div>
                <div class="kpi-lbl">Tareas en progreso</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic green"><i class="fa fa-check-circle"></i></div>
            <div>
                <div class="kpi-num">{{ $tareasHechas }}</div>
                <div class="kpi-lbl">Tareas completadas</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic red"><i class="fa fa-clock"></i></div>
            <div>
                <div class="kpi-num">{{ $tareasVencidas }}</div>
                <div class="kpi-lbl">Tareas vencidas</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-ic violet"><i class="fa fa-chart-bar"></i></div>
            <div>
                <div class="kpi-num">{{ $siessAprobados }}</div>
                <div class="kpi-lbl">Extractos aprobados</div>
            </div>
        </div>
    </div>

    {{-- ACTIVIDADES --}}
    <div class="sec-header">
        <div class="sec-icon" style="background:var(--blue-lt);color:var(--blue)"><i class="fa fa-columns"></i></div>
        <div>
            <span class="sec-title">Actividades en curso</span>
            <span class="sec-sub">· Tablero de tareas</span>
        </div>
    </div>

    @if($activities->count())
    <div class="activities-grid">
        @foreach($activities as $act)
        @php
            $total    = $act->tasks->count();
            $hechas   = $act->tasks->where('status', 2)->count();
            $enCurso  = $act->tasks->where('status', 1)->count();
            $pend     = $act->tasks->where('status', 0)->count();
            $pct      = $total > 0 ? round(($hechas/$total)*100) : 0;
            $venc     = $act->tasks->filter(fn($t) =>
                $t->status !== 2 && $t->fecha_vencimiento &&
                \Carbon\Carbon::parse($t->fecha_vencimiento)->isPast()
            )->count();
        @endphp
        <div class="act-card">
            <div class="act-card-accent"></div>
            <span class="act-type {{ $act->type === 'scrum' ? 'scrum' : 'kanban' }}">
                <i class="fa {{ $act->type === 'scrum' ? 'fa-sync-alt' : 'fa-stream' }}"></i>
                {{ ucfirst($act->type ?? 'kanban') }}
            </span>
            <div class="act-name">{{ $act->name }}</div>
            <div class="act-meta">
                @if($act->date_start)
                <span><i class="fa fa-calendar" style="font-size:10px"></i> {{ \Carbon\Carbon::parse($act->date_start)->format('d/m/Y') }}</span>
                @endif
                @if($act->responsibles->count())
                <span><i class="fa fa-user" style="font-size:10px"></i> {{ $act->responsibles->first()->name }}</span>
                @endif
            </div>
            <div class="act-progress"><div class="act-progress-fill" style="width:{{ $pct }}%"></div></div>
            <div class="act-footer">
                <div class="act-pills">
                    @if($pend)    <span class="act-pill pill-p"><i class="fa fa-inbox"></i>{{ $pend }}</span>@endif
                    @if($enCurso) <span class="act-pill pill-r"><i class="fa fa-spinner"></i>{{ $enCurso }}</span>@endif
                    @if($hechas)  <span class="act-pill pill-d"><i class="fa fa-check"></i>{{ $hechas }}</span>@endif
                    @if($venc)    <span class="act-pill pill-v"><i class="fa fa-exclamation"></i>{{ $venc }}</span>@endif
                    @if($total === 0)<span style="font-size:10px;color:var(--muted)">Sin tareas</span>@endif
                </div>
                <span class="act-pct">{{ $pct }}%</span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card" style="margin-bottom:32px">
        <div class="empty"><i class="fa fa-inbox"></i>Sin actividades registradas</div>
    </div>
    @endif

    {{-- FODA + PEI --}}
    <div class="twin">

        {{-- FODA --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:var(--amber-lt);color:var(--amber)"><i class="fa fa-th-large"></i></div>
                <div>
                    <span class="sec-title">Análisis FODA</span>
                    <span class="sec-sub">· Planificación estratégica</span>
                </div>
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
                @if($fodaIeaResumen->count())
                <div style="padding:12px 18px;border-top:1px solid var(--border);background:var(--surface2)">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:8px">IEA — Eficiencia de Activos</div>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        @if($fodaIeaResumen->get('fortaleza'))<span class="badge b-green"><i class="fa fa-arrow-up" style="font-size:9px"></i> {{ $fodaIeaResumen->get('fortaleza') }} fortaleza</span>@endif
                        @if($fodaIeaResumen->get('neutro'))<span class="badge b-gray">– {{ $fodaIeaResumen->get('neutro') }} neutro</span>@endif
                        @if($fodaIeaResumen->get('debilidad'))<span class="badge b-red"><i class="fa fa-arrow-down" style="font-size:9px"></i> {{ $fodaIeaResumen->get('debilidad') }} debilidad</span>@endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- PEI --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:var(--violet-lt);color:var(--violet)"><i class="fa fa-bullseye"></i></div>
                <div>
                    <span class="sec-title">Plan Estratégico</span>
                    <span class="sec-sub">· PEI</span>
                </div>
            </div>
            <div class="card">
                <div class="sem-strip">
                    <div class="sem-cell" style="background:#ecfdf5">
                        <div class="sem-dot" style="background:#059669;box-shadow:0 0 8px rgba(5,150,105,.5)"></div>
                        <div class="sem-num" style="color:var(--green)">{{ $peiSemaforo->get('verde', 0) }}</div>
                        <div class="sem-lbl" style="color:var(--green)">Verde</div>
                    </div>
                    <div class="sem-cell" style="background:var(--amber-lt)">
                        <div class="sem-dot" style="background:#d97706;box-shadow:0 0 8px rgba(217,119,6,.5)"></div>
                        <div class="sem-num" style="color:var(--amber)">{{ $peiSemaforo->get('amarillo', 0) }}</div>
                        <div class="sem-lbl" style="color:var(--amber)">Amarillo</div>
                    </div>
                    <div class="sem-cell" style="background:var(--red-lt)">
                        <div class="sem-dot" style="background:#dc2626;box-shadow:0 0 8px rgba(220,38,38,.5)"></div>
                        <div class="sem-num" style="color:var(--red)">{{ $peiSemaforo->get('rojo', 0) }}</div>
                        <div class="sem-lbl" style="color:var(--red)">Rojo</div>
                    </div>
                </div>
                <div class="card-rows">
                    <div class="card-row"><span class="lbl">Planes estratégicos</span><span class="val">{{ $peiPlanes }}</span></div>
                    <div class="card-row"><span class="lbl">Acciones / indicadores</span><span class="val">{{ $peiAcciones }}</span></div>
                </div>
                @if($peiRecientes->count())
                <div style="border-top:1px solid var(--border)">
                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">Planes recientes</div>
                    @foreach($peiRecientes as $pei)
                    <div style="padding:10px 18px;border-bottom:1px solid #f8fafc;display:flex;align-items:center;justify-content:space-between;gap:8px">
                        <div style="min-width:0">
                            <div style="font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ Str::limit(strip_tags($pei->name), 40) }}</div>
                            @if($pei->year_start)<div style="font-size:10px;color:var(--muted)">{{ $pei->year_start }}{{ $pei->year_end ? ' — '.$pei->year_end : '' }}</div>@endif
                        </div>
                        @if($pei->semaforo)
                        <span style="width:9px;height:9px;border-radius:50%;flex-shrink:0;display:inline-block;background:{{ $pei->semaforo==='verde'?'#059669':($pei->semaforo==='amarillo'?'#d97706':'#dc2626') }}"></span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- EVALUACIONES + SIESS --}}
    <div class="twin">

        {{-- EVALUACIONES --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:var(--green-lt);color:var(--green)"><i class="fa fa-hospital"></i></div>
                <div>
                    <span class="sec-title">Evaluaciones RIISS</span>
                    <span class="sec-sub">· Red de Salud IPS</span>
                </div>
            </div>
            <div class="card">
                <div class="card-head">
                    <span class="card-head-title"><i class="fa fa-clipboard-list" style="color:var(--green)"></i> Últimas evaluaciones</span>
                    <span class="card-count">{{ $evalTotal }} total</span>
                </div>
                @if($evaluaciones->count())
                <div style="overflow-x:auto">
                <table class="t">
                    <thead>
                        <tr>
                            <th>Establecimiento</th>
                            <th>Fecha</th>
                            <th>Cumplimiento</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluaciones as $ev)
                        @php
                            $p = (float)($ev->porcentaje_cumplimiento ?? 0);
                            $pc = $p >= 70 ? 'pct-hi' : ($p >= 40 ? 'pct-md' : 'pct-lo');
                            $estado = $ev->estado ?? 'pendiente';
                            $bClass = match($estado) {
                                'completada' => 'b-green',
                                'en_curso'   => 'b-blue',
                                default      => 'b-amber',
                            };
                        @endphp
                        <tr>
                            <td style="font-weight:600;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $ev->establecimiento?->nombre_oficial ?? '—' }}
                            </td>
                            <td style="white-space:nowrap;color:var(--muted)">{{ $ev->fecha_evaluacion?->format('d/m/Y') ?? '—' }}</td>
                            <td>
                                @if($ev->porcentaje_cumplimiento !== null)
                                <div class="pct-row">
                                    <div class="pct-track"><div class="pct-fill {{ $pc }}" style="width:{{ min(100,$p) }}%"></div></div>
                                    <span class="pct-val" style="color:var(--text)">{{ number_format($p,1) }}%</span>
                                </div>
                                @else<span style="color:var(--muted)">—</span>@endif
                            </td>
                            <td><span class="badge {{ $bClass }}">{{ ucfirst(str_replace('_',' ',$estado)) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                @else
                <div class="empty"><i class="fa fa-hospital"></i>Sin evaluaciones</div>
                @endif
                <div class="row-summary">
                    <span><b style="color:var(--green)">{{ $evalCompletadas }}</b> completadas</span>
                    <span><b style="color:var(--blue)">{{ $evalEnCurso }}</b> en curso</span>
                    <span><b>{{ $evalTotal }}</b> total</span>
                </div>
            </div>
        </div>

        {{-- SIESS --}}
        <div>
            <div class="sec-header">
                <div class="sec-icon" style="background:var(--violet-lt);color:var(--violet)"><i class="fa fa-chart-line"></i></div>
                <div>
                    <span class="sec-title">Módulos SIESS</span>
                    <span class="sec-sub">· Estadísticas institucionales</span>
                </div>
            </div>

            <div class="card" style="margin-bottom:12px">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border)">
                    <div style="background:var(--green-lt);padding:16px;text-align:center">
                        <div style="font-size:1.6rem;font-weight:900;color:var(--green)">{{ $siessAprobados }}</div>
                        <div style="font-size:10px;color:var(--green);font-weight:700;margin-top:4px">Aprobados</div>
                    </div>
                    <div style="background:var(--amber-lt);padding:16px;text-align:center">
                        <div style="font-size:1.6rem;font-weight:900;color:var(--amber)">{{ $siessPendientes }}</div>
                        <div style="font-size:10px;color:var(--amber);font-weight:700;margin-top:4px">Pendientes</div>
                    </div>
                    <div style="background:var(--red-lt);padding:16px;text-align:center">
                        <div style="font-size:1.6rem;font-weight:900;color:var(--red)">{{ $siessObjetados }}</div>
                        <div style="font-size:10px;color:var(--red);font-weight:700;margin-top:4px">Objetados</div>
                    </div>
                </div>
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

    </div>

</div>

<footer>
    <strong>SIPLAN</strong> · Sistema de Planificación Estratégica Institucional<br>
    Instituto de Previsión Social del Paraguay · Datos en tiempo real
</footer>

</body>
</html>
