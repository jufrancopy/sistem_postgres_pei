<!DOCTYPE html>
<html lang="es">
<head>
@php use Illuminate\Support\Facades\Auth; @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPLAN — Sistema de Planificación Estratégica Institucional</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:        #f0f4f8;
    --surface:   #ffffff;
    --surface2:  #f8fafc;
    --border:    #e2e8f0;
    --blue:      #2563eb;
    --blue-lt:   #eff6ff;
    --blue-mid:  #bfdbfe;
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
    --radius:    12px;
    --shadow:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.12);
}

body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); font-size: 14px; line-height: 1.6; min-height: 100vh; }

/* ── NAV ── */
.nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,255,255,.85);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    padding: 0 32px;
    height: 58px;
    display: flex; align-items: center; justify-content: space-between;
}
.nav-brand { display: flex; align-items: center; gap: 10px; }
.nav-logo {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg, #1d4ed8, #7c3aed);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 800; font-size: 14px; letter-spacing: -1px;
}
.nav-name { font-weight: 700; font-size: 15px; color: var(--text); }
.nav-tagline { font-size: 11px; color: var(--muted); }
.nav-right { display: flex; align-items: center; gap: 10px; }
.nav-pill {
    font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
    background: var(--green-lt); color: var(--green); border: 1px solid #a7f3d0;
    display: flex; align-items: center; gap: 5px;
}
.nav-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--green); animation: pulse-dot 2s infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.4} }
.btn-login {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
    background: var(--blue); color: #fff; border: none; cursor: pointer;
    text-decoration: none; transition: background .15s, box-shadow .15s;
    box-shadow: 0 2px 8px rgba(37,99,235,.3);
}
.btn-login:hover { background: #1d4ed8; color: #fff; text-decoration: none; box-shadow: 0 4px 16px rgba(37,99,235,.4); }

/* ── HERO ── */
.hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #1d4ed8 100%);
    padding: 64px 32px 56px;
    position: relative; overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 70% 50%, rgba(124,58,237,.25) 0%, transparent 60%);
    pointer-events: none;
}
.hero-inner { max-width: 1100px; margin: 0 auto; position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
    color: #93c5fd; background: rgba(147,197,253,.1); border: 1px solid rgba(147,197,253,.25);
    padding: 4px 12px; border-radius: 20px; margin-bottom: 20px;
}
.hero h1 { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 800; color: #fff; line-height: 1.15; letter-spacing: -.5px; margin-bottom: 16px; }
.hero h1 span { color: #93c5fd; }
.hero-sub { font-size: 15px; color: rgba(255,255,255,.65); max-width: 520px; margin-bottom: 36px; }
.hero-stats { display: flex; flex-wrap: wrap; gap: 24px; }
.hero-stat { display: flex; flex-direction: column; }
.hero-stat-num { font-size: 2rem; font-weight: 800; color: #fff; line-height: 1; }
.hero-stat-label { font-size: 11px; color: rgba(255,255,255,.5); margin-top: 3px; text-transform: uppercase; letter-spacing: .5px; }
.hero-divider { width: 1px; background: rgba(255,255,255,.15); align-self: stretch; }

/* ── WRAPPER ── */
.wrapper { max-width: 1100px; margin: 0 auto; padding: 36px 32px 80px; }

/* ── SECTION TITLE ── */
.section-label {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 16px;
}
.section-label-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.section-label h2 { font-size: 15px; font-weight: 700; color: var(--text); }
.section-label span { font-size: 12px; color: var(--muted); margin-left: 4px; }

/* ── ACTIVITY GRID ── */
.activity-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 14px; margin-bottom: 40px; }

.activity-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 18px 20px; box-shadow: var(--shadow);
    transition: box-shadow .2s, transform .15s;
    position: relative; overflow: hidden;
}
.activity-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.activity-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--blue), var(--violet));
}
.activity-type-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    padding: 2px 8px; border-radius: 20px; margin-bottom: 10px;
}
.type-scrum { background: var(--violet-lt); color: var(--violet); }
.type-kanban { background: var(--blue-lt); color: var(--blue); }
.activity-name { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px; line-height: 1.35; }
.activity-meta { font-size: 11px; color: var(--muted); display: flex; align-items: center; gap: 10px; margin-bottom: 14px; flex-wrap: wrap; }
.activity-meta i { font-size: 10px; }
.progress-bar-wrap { height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; margin-bottom: 10px; }
.progress-bar-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, #2563eb, #7c3aed); transition: width .6s; }
.activity-footer { display: flex; justify-content: space-between; align-items: center; }
.task-pills { display: flex; gap: 5px; flex-wrap: wrap; }
.task-pill {
    font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px;
    display: flex; align-items: center; gap: 3px;
}
.pill-pending  { background: var(--amber-lt); color: var(--amber); }
.pill-progress { background: var(--blue-lt);  color: var(--blue);  }
.pill-done     { background: var(--green-lt); color: var(--green); }
.pill-vencida  { background: var(--red-lt);   color: var(--red);   }
.activity-pct  { font-size: 11px; font-weight: 700; color: var(--blue); }

/* ── STATS ROW ── */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 40px; }
.stat-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    padding: 18px 20px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 14px;
}
.stat-icon {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
}
.stat-icon.blue   { background: var(--blue-lt);   color: var(--blue);   }
.stat-icon.green  { background: var(--green-lt);  color: var(--green);  }
.stat-icon.amber  { background: var(--amber-lt);  color: var(--amber);  }
.stat-icon.red    { background: var(--red-lt);    color: var(--red);    }
.stat-icon.violet { background: var(--violet-lt); color: var(--violet); }
.stat-num { font-size: 1.6rem; font-weight: 800; color: var(--text); line-height: 1; }
.stat-lbl { font-size: 11px; color: var(--muted); margin-top: 2px; }

/* ── TWO-COL ── */
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; }

/* ── TABLE CARD ── */
.table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.table-card-header { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.table-card-title { font-size: 13px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
.table-card-count { font-size: 11px; font-weight: 700; background: var(--blue-lt); color: var(--blue); padding: 2px 8px; border-radius: 20px; }

table.pub { width: 100%; border-collapse: collapse; }
table.pub th { font-size: 10px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); padding: 10px 16px; text-align: left; border-bottom: 1px solid var(--border); background: var(--surface2); }
table.pub td { font-size: 12px; padding: 11px 16px; border-bottom: 1px solid #f1f5f9; color: var(--text); vertical-align: middle; }
table.pub tr:last-child td { border-bottom: none; }
table.pub tr:hover td { background: #fafcff; }

.badge-estado {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px;
}
.estado-completada     { background: var(--green-lt); color: var(--green); }
.estado-en_curso       { background: var(--blue-lt);  color: var(--blue);  }
.estado-pendiente      { background: var(--amber-lt); color: var(--amber); }
.estado-borrador       { background: #f1f5f9; color: var(--muted); }
.estado-aprobado       { background: var(--green-lt); color: var(--green); }
.estado-objetado       { background: var(--red-lt);   color: var(--red);   }
.estado-pendiente_validacion { background: var(--amber-lt); color: var(--amber); }
.estado-aprobado_silencio    { background: var(--violet-lt); color: var(--violet); }

.pct-bar { display: flex; align-items: center; gap: 8px; }
.pct-track { flex: 1; height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; min-width: 50px; }
.pct-fill  { height: 100%; border-radius: 2px; }
.pct-fill.high   { background: var(--green); }
.pct-fill.medium { background: var(--amber); }
.pct-fill.low    { background: var(--red);   }

/* ── SIESS MODULES ── */
.siess-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; }
.siess-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px 18px; box-shadow: var(--shadow); }
.siess-card-name { font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
.siess-card-codigo { font-size: 10px; color: var(--muted); font-family: monospace; margin-bottom: 12px; }
.siess-pills { display: flex; flex-wrap: wrap; gap: 4px; }
.siess-pill { font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 4px; }

/* ── FOOTER ── */
.pub-footer { text-align: center; padding: 32px 16px; border-top: 1px solid var(--border); color: var(--muted); font-size: 12px; }
.pub-footer strong { color: var(--text); }

/* ── EMPTY STATE ── */
.empty-state { text-align: center; padding: 32px 16px; color: var(--muted); font-size: 12px; }
.empty-state i { font-size: 24px; display: block; margin-bottom: 8px; opacity: .4; }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .nav { padding: 0 16px; }
    .hero { padding: 40px 16px 36px; }
    .wrapper { padding: 24px 16px 60px; }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
    .two-col { grid-template-columns: 1fr; }
    .activity-grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .stats-row { grid-template-columns: 1fr 1fr; }
    .hero-stats { gap: 16px; }
}
</style>
</head>
<body>

{{-- ── NAV ── --}}
<nav class="nav">
    <div class="nav-brand">
        <div class="nav-logo">SP</div>
        <div>
            <div class="nav-name">SIPLAN</div>
            <div class="nav-tagline">IPS · Planificación Estratégica</div>
        </div>
    </div>
    <div class="nav-right">
        <div class="nav-pill">Sistema activo</div>
        @auth
        <a href="{{ route('home') }}" class="btn-login">
            <i class="fa fa-th-large"></i> Ir al sistema
        </a>
        @else
        <a href="{{ route('login') }}" class="btn-login">
            <i class="fa fa-sign-in-alt"></i> Ingresar al sistema
        </a>
        @endauth
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-eyebrow"><i class="fa fa-shield-alt"></i> Instituto de Previsión Social</div>
        <h1>Sistema de <span>Planificación</span><br>Estratégica Institucional</h1>
        <p class="hero-sub">Monitoreo en tiempo real de actividades, evaluaciones de la Red de Salud IPS y estadísticas institucionales.</p>
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $totalTareas }}</span>
                <span class="hero-stat-label">Tareas totales</span>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $tareasEnCurso }}</span>
                <span class="hero-stat-label">En ejecución</span>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $tareasHechas }}</span>
                <span class="hero-stat-label">Completadas</span>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $evalTotal }}</span>
                <span class="hero-stat-label">Evaluaciones RIISS</span>
            </div>
        </div>
    </div>
</section>

<div class="wrapper">

    {{-- ── STATS CARDS ── --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa fa-tasks"></i></div>
            <div>
                <div class="stat-num">{{ $tareasEnCurso }}</div>
                <div class="stat-lbl">Tareas en progreso</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa fa-check-circle"></i></div>
            <div>
                <div class="stat-num">{{ $tareasHechas }}</div>
                <div class="stat-lbl">Tareas completadas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fa fa-clock"></i></div>
            <div>
                <div class="stat-num">{{ $tareasVencidas }}</div>
                <div class="stat-lbl">Tareas vencidas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon violet"><i class="fa fa-chart-bar"></i></div>
            <div>
                <div class="stat-num">{{ $siessAprobados }}</div>
                <div class="stat-lbl">Extractos SIESS aprobados</div>
            </div>
        </div>
    </div>

    {{-- ── FODA + PEI ── --}}
    <div class="two-col" style="margin-bottom:40px">

        {{-- FODA --}}
        <div>
            <div class="section-label">
                <div class="section-label-icon" style="background:#fffbeb;color:#d97706"><i class="fa fa-th-large"></i></div>
                <div><h2>Análisis FODA <span>· Planificación estratégica</span></h2></div>
            </div>
            <div class="table-card">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border-radius:var(--radius) var(--radius) 0 0;overflow:hidden">
                    <div style="background:#ecfdf5;padding:18px;text-align:center">
                        <div style="font-size:1.8rem;font-weight:800;color:#059669">{{ $fodaFortalezas }}</div>
                        <div style="font-size:11px;font-weight:700;color:#059669;margin-top:3px"><i class="fa fa-shield-alt"></i> Fortalezas</div>
                    </div>
                    <div style="background:#eff6ff;padding:18px;text-align:center">
                        <div style="font-size:1.8rem;font-weight:800;color:#2563eb">{{ $fodaOportunidades }}</div>
                        <div style="font-size:11px;font-weight:700;color:#2563eb;margin-top:3px"><i class="fa fa-star"></i> Oportunidades</div>
                    </div>
                    <div style="background:#fef2f2;padding:18px;text-align:center">
                        <div style="font-size:1.8rem;font-weight:800;color:#dc2626">{{ $fodaDebilidades }}</div>
                        <div style="font-size:11px;font-weight:700;color:#dc2626;margin-top:3px"><i class="fa fa-exclamation-triangle"></i> Debilidades</div>
                    </div>
                    <div style="background:#fff7ed;padding:18px;text-align:center">
                        <div style="font-size:1.8rem;font-weight:800;color:#ea580c">{{ $fodaAmenazas }}</div>
                        <div style="font-size:11px;font-weight:700;color:#ea580c;margin-top:3px"><i class="fa fa-bolt"></i> Amenazas</div>
                    </div>
                </div>
                <div style="padding:14px 18px;display:flex;flex-direction:column;gap:9px">
                    <div style="display:flex;justify-content:space-between;font-size:12px">
                        <span style="color:var(--muted)">Perfiles FODA</span>
                        <span style="font-weight:700">{{ $fodaPerfiles }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12px">
                        <span style="color:var(--muted)">Aspectos analizados</span>
                        <span style="font-weight:700">{{ $fodaAnalisis }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12px">
                        <span style="color:var(--muted)">Estrategias de cruce</span>
                        <span style="font-weight:700;color:var(--violet)">{{ $fodaEstrategias }}</span>
                    </div>
                </div>
                @if($fodaIeaResumen->count())
                <div style="padding:12px 18px;border-top:1px solid var(--border);background:var(--surface2);border-radius:0 0 var(--radius) var(--radius)">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:8px">IEA — Eficiencia de Activos</div>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        @if($fodaIeaResumen->get('fortaleza'))
                        <span class="badge-estado estado-completada"><i class="fa fa-arrow-up" style="font-size:9px"></i> {{ $fodaIeaResumen->get('fortaleza') }} fortaleza</span>
                        @endif
                        @if($fodaIeaResumen->get('neutro'))
                        <span class="badge-estado estado-borrador">– {{ $fodaIeaResumen->get('neutro') }} neutro</span>
                        @endif
                        @if($fodaIeaResumen->get('debilidad'))
                        <span class="badge-estado estado-objetado"><i class="fa fa-arrow-down" style="font-size:9px"></i> {{ $fodaIeaResumen->get('debilidad') }} debilidad</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- PEI --}}
        <div>
            <div class="section-label">
                <div class="section-label-icon" style="background:#f5f3ff;color:#7c3aed"><i class="fa fa-bullseye"></i></div>
                <div><h2>Plan Estratégico <span>· PEI</span></h2></div>
            </div>
            <div class="table-card">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);border-radius:var(--radius) var(--radius) 0 0;overflow:hidden">
                    <div style="background:#ecfdf5;padding:16px;text-align:center">
                        <div style="width:14px;height:14px;border-radius:50%;background:#059669;box-shadow:0 0 8px rgba(5,150,105,.5);margin:0 auto 8px"></div>
                        <div style="font-size:1.5rem;font-weight:800;color:#059669">{{ $peiSemaforo->get('verde', 0) }}</div>
                        <div style="font-size:10px;font-weight:700;color:#059669">Verde</div>
                    </div>
                    <div style="background:#fffbeb;padding:16px;text-align:center">
                        <div style="width:14px;height:14px;border-radius:50%;background:#d97706;box-shadow:0 0 8px rgba(217,119,6,.5);margin:0 auto 8px"></div>
                        <div style="font-size:1.5rem;font-weight:800;color:#d97706">{{ $peiSemaforo->get('amarillo', 0) }}</div>
                        <div style="font-size:10px;font-weight:700;color:#d97706">Amarillo</div>
                    </div>
                    <div style="background:#fef2f2;padding:16px;text-align:center">
                        <div style="width:14px;height:14px;border-radius:50%;background:#dc2626;box-shadow:0 0 8px rgba(220,38,38,.5);margin:0 auto 8px"></div>
                        <div style="font-size:1.5rem;font-weight:800;color:#dc2626">{{ $peiSemaforo->get('rojo', 0) }}</div>
                        <div style="font-size:10px;font-weight:700;color:#dc2626">Rojo</div>
                    </div>
                </div>
                <div style="padding:14px 18px;display:flex;flex-direction:column;gap:9px">
                    <div style="display:flex;justify-content:space-between;font-size:12px">
                        <span style="color:var(--muted)">Planes estratégicos</span>
                        <span style="font-weight:700">{{ $peiPlanes }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12px">
                        <span style="color:var(--muted)">Acciones / indicadores</span>
                        <span style="font-weight:700">{{ $peiAcciones }}</span>
                    </div>
                </div>
                @if($peiRecientes->count())
                <div style="border-top:1px solid var(--border)">
                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">Planes recientes</div>
                    @foreach($peiRecientes as $pei)
                    <div style="padding:10px 18px;border-bottom:1px solid #f8fafc;display:flex;align-items:center;justify-content:space-between;gap:8px">
                        <div style="min-width:0">
                            <div style="font-size:12px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ Str::limit(strip_tags($pei->name), 38) }}
                            </div>
                            @if($pei->year_start)
                            <div style="font-size:10px;color:var(--muted)">{{ $pei->year_start }}{{ $pei->year_end ? ' — '.$pei->year_end : '' }}</div>
                            @endif
                        </div>
                        @if($pei->semaforo)
                        <span style="width:9px;height:9px;border-radius:50%;flex-shrink:0;display:inline-block;background:{{ $pei->semaforo === 'verde' ? '#059669' : ($pei->semaforo === 'amarillo' ? '#d97706' : '#dc2626') }}"></span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>
    {{-- ── ACTIVIDADES ── --}}
    <div class="section-label">
        <div class="section-label-icon" style="background:#eff6ff;color:#2563eb"><i class="fa fa-columns"></i></div>
        <div>
            <h2>Actividades en curso <span>· Tablero de tareas</span></h2>
        </div>
    </div>

    @if($activities->count())
    <div class="activity-grid">
        @foreach($activities as $act)
        @php
            $total  = $act->tasks->count();
            $hechas = $act->tasks->where('status', 2)->count();
            $pct    = $total > 0 ? round(($hechas / $total) * 100) : 0;
            $enCurso   = $act->tasks->where('status', 1)->count();
            $pendientes = $act->tasks->where('status', 0)->count();
            $vencidas  = $act->tasks->filter(fn($t) =>
                $t->status !== 2 && $t->fecha_vencimiento &&
                \Carbon\Carbon::parse($t->fecha_vencimiento)->isPast()
            )->count();
        @endphp
        <div class="activity-card">
            <span class="activity-type-badge {{ $act->type === 'scrum' ? 'type-scrum' : 'type-kanban' }}">
                <i class="fa {{ $act->type === 'scrum' ? 'fa-sync-alt' : 'fa-stream' }}"></i>
                {{ ucfirst($act->type ?? 'kanban') }}
            </span>
            <div class="activity-name">{{ $act->name }}</div>
            <div class="activity-meta">
                @if($act->date_start)
                <span><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($act->date_start)->format('d/m/Y') }}</span>
                @endif
                @if($act->responsibles->count())
                <span><i class="fa fa-user"></i> {{ $act->responsibles->first()->name }}</span>
                @endif
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width:{{ $pct }}%"></div>
            </div>
            <div class="activity-footer">
                <div class="task-pills">
                    @if($pendientes) <span class="task-pill pill-pending"><i class="fa fa-inbox"></i>{{ $pendientes }}</span> @endif
                    @if($enCurso)    <span class="task-pill pill-progress"><i class="fa fa-spinner"></i>{{ $enCurso }}</span> @endif
                    @if($hechas)     <span class="task-pill pill-done"><i class="fa fa-check"></i>{{ $hechas }}</span> @endif
                    @if($vencidas)   <span class="task-pill pill-vencida"><i class="fa fa-exclamation"></i>{{ $vencidas }}</span> @endif
                    @if($total === 0)<span style="font-size:11px;color:var(--muted)">Sin tareas</span> @endif
                </div>
                <span class="activity-pct">{{ $pct }}%</span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="table-card" style="margin-bottom:40px">
        <div class="empty-state"><i class="fa fa-inbox"></i>Sin actividades registradas</div>
    </div>
    @endif

    {{-- ── EVALUACIONES + SIESS ── --}}
    <div class="two-col">

        {{-- EVALUACIONES RIISS --}}
        <div>
            <div class="section-label">
                <div class="section-label-icon" style="background:#ecfdf5;color:#059669"><i class="fa fa-hospital"></i></div>
                <div>
                    <h2>Evaluaciones RIISS <span>· Red de Salud IPS</span></h2>
                </div>
            </div>
            <div class="table-card">
                <div class="table-card-header">
                    <span class="table-card-title"><i class="fa fa-list-check" style="color:#059669"></i> Últimas evaluaciones</span>
                    <span class="table-card-count">{{ $evalTotal }} total</span>
                </div>
                @if($evaluaciones->count())
                <table class="pub">
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
                            $pctEv = (float)($ev->porcentaje_cumplimiento ?? 0);
                            $pctClass = $pctEv >= 70 ? 'high' : ($pctEv >= 40 ? 'medium' : 'low');
                        @endphp
                        <tr>
                            <td style="font-weight:600;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $ev->establecimiento?->nombre_oficial ?? '—' }}
                            </td>
                            <td style="white-space:nowrap;color:var(--muted)">
                                {{ $ev->fecha_evaluacion?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td>
                                @if($ev->porcentaje_cumplimiento !== null)
                                <div class="pct-bar">
                                    <div class="pct-track">
                                        <div class="pct-fill {{ $pctClass }}" style="width:{{ min(100,$pctEv) }}%"></div>
                                    </div>
                                    <span style="font-size:11px;font-weight:700;color:var(--text);min-width:32px">{{ number_format($pctEv,1) }}%</span>
                                </div>
                                @else
                                <span style="color:var(--muted);font-size:11px">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-estado estado-{{ $ev->estado }}">
                                    {{ ucfirst(str_replace('_',' ',$ev->estado ?? 'pendiente')) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state"><i class="fa fa-hospital"></i>Sin evaluaciones registradas</div>
                @endif

                {{-- Resumen --}}
                <div style="padding:12px 16px;border-top:1px solid var(--border);background:var(--surface2);display:flex;gap:16px;flex-wrap:wrap">
                    <span style="font-size:11px;color:var(--muted)"><span style="font-weight:700;color:var(--green)">{{ $evalCompletadas }}</span> completadas</span>
                    <span style="font-size:11px;color:var(--muted)"><span style="font-weight:700;color:var(--blue)">{{ $evalEnCurso }}</span> en curso</span>
                    <span style="font-size:11px;color:var(--muted)"><span style="font-weight:700;color:var(--text)">{{ $evalTotal }}</span> total</span>
                </div>
            </div>
        </div>

        {{-- SIESS --}}
        <div>
            <div class="section-label">
                <div class="section-label-icon" style="background:#f5f3ff;color:#7c3aed"><i class="fa fa-chart-line"></i></div>
                <div>
                    <h2>Módulos SIESS <span>· Estadísticas institucionales</span></h2>
                </div>
            </div>
            <div class="table-card" style="margin-bottom:12px">
                <div class="table-card-header">
                    <span class="table-card-title"><i class="fa fa-database" style="color:#7c3aed"></i> Estado de extractos</span>
                </div>
                <div style="padding:16px 18px;display:flex;gap:12px;flex-wrap:wrap">
                    <div style="flex:1;min-width:100px;background:var(--green-lt);border:1px solid #a7f3d0;border-radius:8px;padding:12px 14px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:800;color:var(--green)">{{ $siessAprobados }}</div>
                        <div style="font-size:10px;color:var(--green);font-weight:600;margin-top:2px">Aprobados</div>
                    </div>
                    <div style="flex:1;min-width:100px;background:var(--amber-lt);border:1px solid #fde68a;border-radius:8px;padding:12px 14px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:800;color:var(--amber)">{{ $siessPendientes }}</div>
                        <div style="font-size:10px;color:var(--amber);font-weight:600;margin-top:2px">Pendientes</div>
                    </div>
                    <div style="flex:1;min-width:100px;background:var(--red-lt);border:1px solid #fecaca;border-radius:8px;padding:12px 14px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:800;color:var(--red)">{{ $siessObjetados }}</div>
                        <div style="font-size:10px;color:var(--red);font-weight:600;margin-top:2px">Objetados</div>
                    </div>
                </div>
            </div>

            @if($siessModulos->count())
            <div class="siess-grid">
                @foreach($siessModulos as $mod)
                @php $resumen = $mod->resumenEstados(); @endphp
                <div class="siess-card">
                    <div class="siess-card-name">{{ $mod->nombre }}</div>
                    <div class="siess-card-codigo">{{ $mod->codigo }} · {{ ucfirst($mod->periodicidad ?? '') }}</div>
                    <div class="siess-pills">
                        @if($resumen['aprobado'] + ($resumen['aprobado_silencio'] ?? 0) > 0)
                        <span class="siess-pill" style="background:var(--green-lt);color:var(--green)">
                            <i class="fa fa-check" style="font-size:9px"></i> {{ $resumen['aprobado'] + ($resumen['aprobado_silencio'] ?? 0) }}
                        </span>
                        @endif
                        @if($resumen['pendiente_validacion'] > 0)
                        <span class="siess-pill" style="background:var(--amber-lt);color:var(--amber)">
                            <i class="fa fa-clock" style="font-size:9px"></i> {{ $resumen['pendiente_validacion'] }}
                        </span>
                        @endif
                        @if($resumen['objetado'] > 0)
                        <span class="siess-pill" style="background:var(--red-lt);color:var(--red)">
                            <i class="fa fa-times" style="font-size:9px"></i> {{ $resumen['objetado'] }}
                        </span>
                        @endif
                        @if($resumen['borrador'] > 0)
                        <span class="siess-pill" style="background:#f1f5f9;color:var(--muted)">
                            {{ $resumen['borrador'] }} borr.
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="table-card">
                <div class="empty-state"><i class="fa fa-chart-bar"></i>Sin módulos SIESS activos</div>
            </div>
            @endif
        </div>

    </div>

</div>


<footer class="pub-footer">
    <strong>SIPLAN</strong> · Sistema de Planificación Estratégica Institucional · Instituto de Previsión Social del Paraguay<br>
    <span style="margin-top:4px;display:inline-block">Datos actualizados en tiempo real</span>
</footer>

</body>
</html>
