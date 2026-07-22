<!DOCTYPE html>
<html lang="es">
<head>
@php use Illuminate\Support\Facades\Auth; @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPLAN — Sistema de Planificación Estratégica · IPS Paraguay</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f0f4f8;--surface:#fff;--border:#e2e8f0;
  --blue:#2563eb;--blue-lt:#eff6ff;--blue-dk:#1d4ed8;
  --green:#059669;--green-lt:#ecfdf5;
  --amber:#d97706;--amber-lt:#fffbeb;
  --red:#dc2626;--red-lt:#fef2f2;
  --violet:#7c3aed;--violet-lt:#f5f3ff;
  --text:#0f172a;--muted:#64748b;
  --r:12px;--sh:0 1px 3px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.04);
  --sh-md:0 4px 20px rgba(0,0,0,.1);
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.6;min-height:100vh}

/* NAV */
.nav{position:sticky;top:0;z-index:200;background:rgba(255,255,255,.92);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border-bottom:1px solid var(--border);height:56px;display:flex;align-items:center;padding:0 clamp(12px,4vw,32px);gap:12px;justify-content:space-between}
.nav-brand{display:flex;align-items:center;gap:9px;flex-shrink:0}
.nav-logo{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#1d4ed8,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:12px;letter-spacing:-1px;box-shadow:0 3px 10px rgba(37,99,235,.3)}
.nav-name{font-weight:800;font-size:14px;letter-spacing:-.3px}
.nav-sub{font-size:9px;color:var(--muted);line-height:1}
.nav-right{display:flex;align-items:center;gap:8px}
.live-pill{display:flex;align-items:center;gap:5px;font-size:10px;font-weight:600;padding:3px 9px;border-radius:20px;background:var(--green-lt);color:var(--green);border:1px solid #a7f3d0}
.live-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:blink 1.8s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}
.btn-enter{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:12px;font-weight:700;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;border:none;cursor:pointer;text-decoration:none;box-shadow:0 3px 12px rgba(37,99,235,.3);transition:opacity .15s,transform .1s;white-space:nowrap}
.btn-enter:hover{opacity:.92;transform:translateY(-1px);color:#fff;text-decoration:none}

/* HERO */
.hero{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#1e40af 100%);padding:clamp(32px,5vw,60px) clamp(16px,4vw,32px) 0;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(124,58,237,.25) 0%,transparent 60%),radial-gradient(ellipse 40% 60% at 15% 80%,rgba(37,99,235,.18) 0%,transparent 50%);pointer-events:none}
.hero-grid{position:absolute;inset:0;opacity:.03;background-image:linear-gradient(rgba(255,255,255,.8) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.8) 1px,transparent 1px);background-size:36px 36px;pointer-events:none}
.hero-inner{max-width:1100px;margin:0 auto;position:relative;z-index:1}
.hero-eyebrow{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#93c5fd;background:rgba(147,197,253,.1);border:1px solid rgba(147,197,253,.18);padding:4px 12px;border-radius:20px;margin-bottom:14px}
.hero h1{font-size:clamp(1.6rem,4vw,2.8rem);font-weight:900;color:#fff;line-height:1.1;letter-spacing:-.5px;margin-bottom:10px}
.hero h1 em{color:#93c5fd;font-style:normal}
.hero-desc{font-size:13px;color:rgba(255,255,255,.55);max-width:460px;margin-bottom:24px}
.hero-kpis{display:flex;flex-wrap:wrap;gap:0;margin-bottom:0}
.hero-kpi{display:flex;flex-direction:column;padding:0 20px 0 0;border-right:1px solid rgba(255,255,255,.12);margin-bottom:8px}
.hero-kpi:first-child{padding-left:0}
.hero-kpi:last-child{border-right:none}
.hero-kpi-num{font-size:clamp(1.4rem,2.5vw,1.9rem);font-weight:900;color:#fff;line-height:1}
.hero-kpi-lbl{font-size:9px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-top:3px}
.hero-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:rgba(255,255,255,.08);border-radius:.75rem .75rem 0 0;margin-top:24px;overflow:hidden}
.hero-stat{padding:.75rem .5rem;text-align:center;background:rgba(0,0,0,.15)}
.hero-stat-num{font-size:1.5rem;font-weight:900;line-height:1}
.hero-stat-lbl{font-size:.58rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.06em;margin-top:.15rem}
.c-green{color:#4ade80}.c-amber{color:#fbbf24}.c-red{color:#f87171}.c-white{color:#fff}

/* DEPT TABS */
.dept-wrap{max-width:1100px;margin:0 auto;padding:clamp(16px,3vw,32px) clamp(12px,3vw,24px) 60px}
.dept-nav{display:flex;gap:2px;background:var(--border);border-radius:12px;padding:3px;margin-bottom:24px;overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch}
.dept-nav::-webkit-scrollbar{display:none}
.dept-tab{flex:1;min-width:110px;display:flex;align-items:center;justify-content:center;gap:6px;padding:9px 14px;border-radius:9px;font-size:12px;font-weight:600;color:var(--muted);border:none;background:transparent;cursor:pointer;transition:all .15s;white-space:nowrap}
.dept-tab:hover{color:var(--text);background:rgba(255,255,255,.5)}
.dept-tab.active{background:var(--surface);color:var(--text);box-shadow:0 1px 4px rgba(0,0,0,.09)}
.dept-section{display:none}.dept-section.active{display:block}

/* KPI GRID */
.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px}
.kpi-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:14px 16px;box-shadow:var(--sh);display:flex;align-items:center;gap:12px}
.kpi-ic{width:40px;height:40px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px}
.kpi-num{font-size:1.5rem;font-weight:900;line-height:1}
.kpi-lbl{font-size:10px;color:var(--muted);margin-top:2px}
.ic-blue{background:var(--blue-lt);color:var(--blue)}.ic-green{background:var(--green-lt);color:var(--green)}
.ic-amber{background:var(--amber-lt);color:var(--amber)}.ic-red{background:var(--red-lt);color:var(--red)}
.ic-violet{background:var(--violet-lt);color:var(--violet)}

/* TWIN */
.twin{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px}

/* CARD */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden}
.card-head{padding:12px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:8px}
.card-head-title{font-size:13px;font-weight:700;display:flex;align-items:center;gap:7px}
.card-count{font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:var(--blue-lt);color:var(--blue)}

/* EVALUACIONES — cards en vez de tabla */
.eval-list{display:flex;flex-direction:column;gap:0}
.eval-item{padding:12px 16px;border-bottom:1px solid #f8fafc;display:flex;align-items:center;gap:10px;transition:background .1s}
.eval-item:hover{background:#fafcff}
.eval-item:last-child{border-bottom:none}
.eval-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.eval-nombre{font-size:13px;font-weight:600;line-height:1.3;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.eval-meta{font-size:10px;color:var(--muted);margin-top:1px}
.eval-right{flex-shrink:0;display:flex;align-items:center;gap:6px}
.eval-pct{font-size:11px;font-weight:700}
.eval-badge{font-size:10px;font-weight:600;padding:2px 7px;border-radius:20px}
.badge-green{background:var(--green-lt);color:var(--green)}
.badge-blue{background:var(--blue-lt);color:var(--blue)}
.badge-amber{background:var(--amber-lt);color:var(--amber)}
.badge-gray{background:#f1f5f9;color:var(--muted)}
.badge-red{background:var(--red-lt);color:var(--red)}

/* TABLA NIVELES */
.nivel-table{width:100%;border-collapse:collapse;font-size:.78rem}
.nivel-table th{padding:.5rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);background:var(--bg);border-bottom:2px solid var(--border);text-align:left}
.nivel-table td{padding:.5rem .75rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
.nivel-table tr:last-child td{border-bottom:none}
.grado-badge{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;font-size:.7rem;font-weight:700;color:#fff}

/* SEMÁFORO */
.sem-strip{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border)}
.sem-cell{padding:14px;text-align:center}
.sem-dot{width:12px;height:12px;border-radius:50%;margin:0 auto 7px}
.sem-num{font-size:1.4rem;font-weight:900;line-height:1}
.sem-lbl{font-size:9px;font-weight:700;margin-top:3px;text-transform:uppercase;letter-spacing:.5px}

/* FODA */
.foda-quad{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border)}
.foda-cell{padding:16px;text-align:center}
.foda-num{font-size:1.8rem;font-weight:900;line-height:1}
.foda-lbl{font-size:9px;font-weight:700;margin-top:4px;text-transform:uppercase;letter-spacing:.5px}

/* SIESS */
.siess-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px}
.siess-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:12px 14px;box-shadow:var(--sh)}
.siess-name{font-size:12px;font-weight:700;margin-bottom:2px}
.siess-code{font-size:9px;color:var(--muted);font-family:monospace;margin-bottom:8px}
.siess-pills{display:flex;flex-wrap:wrap;gap:3px}
.siess-pill{font-size:9px;font-weight:600;padding:2px 6px;border-radius:4px}

/* CARD ROWS */
.card-rows{padding:12px 16px;display:flex;flex-direction:column;gap:9px}
.card-row{display:flex;justify-content:space-between;align-items:center;font-size:12px}
.card-row .lbl{color:var(--muted)}
.card-row .val{font-weight:700}

/* BTN MATRIZ */
.btn-matriz{display:inline-flex;align-items:center;gap:3px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:600;background:var(--blue-lt);color:var(--blue);border:1px solid #bfdbfe;cursor:pointer;white-space:nowrap;transition:background .1s,transform .1s}
.btn-matriz:hover{background:#dbeafe;transform:translateY(-1px)}

/* MODAL */
.modal-overlay{display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);overflow-y:auto;padding:16px;backdrop-filter:blur(4px)}
.modal-box{background:var(--surface);border-radius:16px;max-width:960px;margin:0 auto;overflow:hidden;box-shadow:0 24px 80px rgba(0,0,0,.3)}
.modal-head{background:linear-gradient(135deg,#1a237e,#283593);padding:.85rem 1.2rem;display:flex;align-items:center;justify-content:space-between}
.modal-close{background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:7px;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:background .1s}
.modal-close:hover{background:rgba(255,255,255,.25)}
.modal-body{padding:1.5rem 1.75rem;max-height:80vh;overflow-y:auto}

/* FOOTER */
.site-footer{border-top:1px solid var(--border);background:var(--surface);padding:32px clamp(16px,4vw,32px) 24px}
.footer-inner{max-width:1100px;margin:0 auto}
.footer-top{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border)}
.footer-brand-row{display:flex;align-items:center;gap:9px;margin-bottom:7px}
.footer-logo{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#1d4ed8,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:12px;letter-spacing:-1px}
.footer-name{font-size:14px;font-weight:800}
.footer-sub{font-size:9px;color:var(--muted)}
.footer-desc{font-size:11px;color:var(--muted);max-width:300px;line-height:1.6}
.ai-section-lbl{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:8px}
.ai-badges{display:flex;flex-wrap:wrap;gap:6px;justify-content:flex-end;margin-bottom:8px}
.ai-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border-radius:7px;font-size:11px;font-weight:600;border:1px solid var(--border);background:var(--bg);color:var(--text);text-decoration:none;transition:box-shadow .15s,transform .1s}
.ai-badge:hover{box-shadow:0 2px 8px rgba(0,0,0,.1);transform:translateY(-1px);text-decoration:none}
.ai-kiro{border-color:#f59e0b44;background:#fffbeb;color:#92400e}
.ai-q{border-color:#22c55e44;background:#f0fdf4;color:#15803d}
.ai-claude{border-color:#f9731688;background:#fff7ed;color:#9a3412}
.ai-gemini{border-color:#3b82f688;background:#eff6ff;color:#1e40af}
.dev-by{font-size:11px;color:var(--muted);text-align:right}
.dev-by a{color:var(--blue);text-decoration:none;font-weight:600}
.dev-by a:hover{text-decoration:underline}
.footer-bottom{text-align:center;font-size:10px;color:var(--muted)}

/* RESPONSIVE */
@media(max-width:768px){
  .twin{grid-template-columns:1fr}
  .kpi-grid{grid-template-columns:repeat(2,1fr)}
  .hero-stats{grid-template-columns:repeat(2,1fr)}
  .hero-stat:nth-child(n+5){display:none}
  .live-pill,.nav-sub{display:none}
  .ai-badges{justify-content:flex-start}
  .ai-section-lbl,.dev-by{text-align:left}
  .footer-top{flex-direction:column}
}
@media(max-width:480px){
  .kpi-grid{grid-template-columns:repeat(2,1fr)}
  .hero h1{font-size:1.45rem}
  .siess-grid{grid-template-columns:1fr 1fr}
  .dept-tab{font-size:11px;padding:8px 10px}
  .dept-tab i{display:none}
}

/* SPINNER */
@keyframes spin{to{transform:rotate(360deg)}}
.spinner{width:32px;height:32px;border:3px solid #e2e8f0;border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 12px}
</style>
</head>
<body>

{{-- NAV --}}
<nav class="nav">
    <div class="nav-brand">
        <div class="nav-logo">SP</div>
        <div>
            <div class="nav-name">SIPLAN</div>
            <div class="nav-sub">IPS · Planificación Estratégica</div>
        </div>
    </div>
    <div class="nav-right">
        <div class="live-pill"><span class="live-dot"></span>En línea</div>
        @auth
        <a href="{{ route('home') }}" class="btn-enter"><i class="fa fa-th-large"></i><span>Sistema</span></a>
        @else
        <a href="{{ route('login') }}" class="btn-enter"><i class="fa fa-sign-in-alt"></i><span>Ingresar</span></a>
        @endauth
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-grid"></div>
    <div class="hero-inner">
        <div class="hero-eyebrow"><i class="fa fa-shield-alt"></i> Instituto de Previsión Social — Paraguay</div>
        <h1>Sistema de<br><em>Planificación</em><br>Estratégica</h1>
        <p class="hero-desc">Monitoreo en tiempo real de la Red de Salud IPS, planificación estratégica y estadísticas institucionales.</p>
        <div class="hero-kpis">
            <div class="hero-kpi"><span class="hero-kpi-num">{{ $evalTotal }}</span><span class="hero-kpi-lbl">Evaluaciones</span></div>
            <div class="hero-kpi"><span class="hero-kpi-num">{{ $peiPlanes }}</span><span class="hero-kpi-lbl">Planes PEI</span></div>
            <div class="hero-kpi"><span class="hero-kpi-num">{{ $fodaAnalisis }}</span><span class="hero-kpi-lbl">Análisis FODA</span></div>
            <div class="hero-kpi"><span class="hero-kpi-num">{{ $siessAprobados }}</span><span class="hero-kpi-lbl">SIESS aprobados</span></div>
        </div>
        <div class="hero-stats">
            @php $gV=$peiSemaforo->get('verde',0);$gA=$peiSemaforo->get('amarillo',0);$gR=$peiSemaforo->get('rojo',0);$gT=$peiAcciones; @endphp
            <div class="hero-stat"><div class="hero-stat-num c-green">{{ $gV }}</div><div class="hero-stat-lbl">PEI Verde</div></div>
            <div class="hero-stat"><div class="hero-stat-num c-amber">{{ $gA }}</div><div class="hero-stat-lbl">PEI Amarillo</div></div>
            <div class="hero-stat"><div class="hero-stat-num c-red">{{ $gR }}</div><div class="hero-stat-lbl">PEI Rojo</div></div>
            <div class="hero-stat"><div class="hero-stat-num c-white">{{ $evalCompletadas }}</div><div class="hero-stat-lbl">Eval. completas</div></div>
        </div>
    </div>
</section>

{{-- CONTENIDO --}}
<div class="dept-wrap">

{{-- TABS --}}
<nav class="dept-nav">
    <button class="dept-tab active" data-dept="riiss"><i class="fa fa-hospital"></i> Red de Salud RIISS</button>
    <button class="dept-tab" data-dept="planificacion"><i class="fa fa-bullseye"></i> Planificación</button>
    <button class="dept-tab" data-dept="estadisticas"><i class="fa fa-chart-bar"></i> Estadísticas</button>
</nav>

{{-- ══ RIISS ══════════════════════════════════════════════════════════════════ --}}
<div class="dept-section active" id="dept-riiss">

    <div class="kpi-grid">
        <div class="kpi-card"><div class="kpi-ic ic-blue"><i class="fa fa-clipboard-list"></i></div><div><div class="kpi-num">{{ $evalTotal }}</div><div class="kpi-lbl">Evaluaciones</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-green"><i class="fa fa-check-circle"></i></div><div><div class="kpi-num">{{ $evalCompletadas }}</div><div class="kpi-lbl">Completadas</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-amber"><i class="fa fa-spinner"></i></div><div><div class="kpi-num">{{ $evalEnCurso }}</div><div class="kpi-lbl">En curso</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-violet"><i class="fa fa-sitemap"></i></div><div><div class="kpi-num">{{ \App\Models\Riiss\Establecimiento::count() }}</div><div class="kpi-lbl">Establecimientos</div></div></div>
    </div>

    <div class="twin">

        {{-- Lista de evaluaciones --}}
        <div>
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title"><i class="fa fa-clipboard-check" style="color:var(--green)"></i> Últimas evaluaciones</div>
                    <span class="card-count">{{ $evalTotal }}</span>
                </div>
                @if($evalRecientes->count())
                <div class="eval-list">
                @foreach($evalRecientes as $ev)
                @php
                    $p   = (float)($ev->porcentaje_cumplimiento ?? 0);
                    $stc = ['completada'=>'badge-green','en_curso'=>'badge-blue','borrador'=>'badge-gray'][$ev->estado] ?? 'badge-gray';
                    $dc  = $p >= 90 ? '#059669' : ($p >= 70 ? '#d97706' : '#dc2626');
                @endphp
                <div class="eval-item">
                    <div class="eval-dot" style="background:{{ $dc }}"></div>
                    <div style="flex:1;min-width:0">
                        <div class="eval-nombre">{{ $ev->establecimiento?->nombre_oficial ?? '—' }}</div>
                        <div class="eval-meta">
                            {{ $ev->establecimiento?->complejidadTipo?->nombre ?? '' }}
                            @if($ev->fecha_evaluacion) · {{ \Carbon\Carbon::parse($ev->fecha_evaluacion)->format('d/m/Y') }} @endif
                        </div>
                    </div>
                    <div class="eval-right">
                        <span class="eval-badge {{ $stc }}">{{ ucfirst(str_replace('_',' ',$ev->estado)) }}</span>
                        @if($ev->porcentaje_cumplimiento !== null)
                        <span class="eval-pct" style="color:{{ $dc }}">{{ round($p) }}%</span>
                        @endif
                        <button class="btn-matriz"
                                data-eval="{{ $ev->id }}"
                                data-nombre="{{ $ev->establecimiento?->nombre_oficial ?? 'Evaluación #'.$ev->id }}"
                                title="Ver Matriz de Servicios">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            Matriz
                        </button>
                    </div>
                </div>
                @endforeach
                </div>
                @else
                <div style="text-align:center;padding:2rem;color:var(--muted);font-size:12px"><i class="fa fa-hospital" style="display:block;font-size:1.5rem;margin-bottom:.5rem;opacity:.3"></i>Sin evaluaciones</div>
                @endif
            </div>
        </div>

        {{-- Niveles de complejidad --}}
        <div>
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title"><i class="fa fa-layer-group" style="color:var(--violet)"></i> Niveles de complejidad</div>
                </div>
                <div style="overflow-x:auto">
                <table class="nivel-table">
                    <thead><tr><th>Grado</th><th>Clasificación</th><th>Tipo</th><th style="text-align:center">Hosp.</th></tr></thead>
                    <tbody>
                    @foreach($complejidadTipos as $ct)
                    <tr>
                        <td>
                            <span class="grado-badge" style="background:{{ $ct->color ?? '#6b7280' }}">{{ $ct->grado }}</span>
                        </td>
                        <td style="font-weight:600;font-size:.78rem">{{ $ct->nombre }}</td>
                        <td style="font-size:.72rem;color:var(--muted)">{{ $ct->tipo_establecimiento ?? '—' }}</td>
                        <td style="text-align:center">
                            @if($ct->es_hospitalario)
                            <span style="color:var(--blue);font-size:.75rem"><i class="fa fa-check"></i></span>
                            @else
                            <span style="color:#cbd5e1;font-size:.75rem"><i class="fa fa-minus"></i></span>
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

    <div class="kpi-grid">
        <div class="kpi-card"><div class="kpi-ic ic-violet"><i class="fa fa-bullseye"></i></div><div><div class="kpi-num">{{ $peiPlanes }}</div><div class="kpi-lbl">Planes PEI</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-blue"><i class="fa fa-rocket"></i></div><div><div class="kpi-num">{{ $peiAcciones }}</div><div class="kpi-lbl">Acciones</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-amber"><i class="fa fa-th-large"></i></div><div><div class="kpi-num">{{ $fodaAnalisis }}</div><div class="kpi-lbl">Análisis FODA</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-green"><i class="fa fa-chess"></i></div><div><div class="kpi-num">{{ $fodaEstrategias }}</div><div class="kpi-lbl">Estrategias</div></div></div>
    </div>

    <div class="twin">

        {{-- PEI --}}
        <div>
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title"><i class="fa fa-bullseye" style="color:var(--violet)"></i> Planes Estratégicos</div>
                </div>
                <div class="sem-strip">
                    <div class="sem-cell" style="background:#ecfdf5">
                        <div class="sem-dot" style="background:#059669;box-shadow:0 0 8px rgba(5,150,105,.4)"></div>
                        <div class="sem-num" style="color:var(--green)">{{ $peiSemaforo->get('verde',0) }}</div>
                        <div class="sem-lbl" style="color:var(--green)">Verde</div>
                    </div>
                    <div class="sem-cell" style="background:var(--amber-lt)">
                        <div class="sem-dot" style="background:#d97706;box-shadow:0 0 8px rgba(217,119,6,.4)"></div>
                        <div class="sem-num" style="color:var(--amber)">{{ $peiSemaforo->get('amarillo',0) }}</div>
                        <div class="sem-lbl" style="color:var(--amber)">Amarillo</div>
                    </div>
                    <div class="sem-cell" style="background:var(--red-lt)">
                        <div class="sem-dot" style="background:#dc2626;box-shadow:0 0 8px rgba(220,38,38,.4)"></div>
                        <div class="sem-num" style="color:var(--red)">{{ $peiSemaforo->get('rojo',0) }}</div>
                        <div class="sem-lbl" style="color:var(--red)">Rojo</div>
                    </div>
                </div>
                @if($peiRecientes->count())
                <div style="border-top:1px solid var(--border)">
                    @foreach($peiRecientes as $pei)
                    <div style="padding:10px 16px;border-bottom:1px solid #f8fafc;display:flex;align-items:center;gap:8px">
                        <div style="flex:1;min-width:0">
                            <div style="font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ \Illuminate\Support\Str::limit(strip_tags($pei->name),42) }}</div>
                            @if($pei->year_start)<div style="font-size:10px;color:var(--muted)">{{ \Carbon\Carbon::parse($pei->year_start)->format('Y') }}–{{ $pei->year_end ? \Carbon\Carbon::parse($pei->year_end)->format('Y') : '' }}</div>@endif
                        </div>
                        @if($pei->public_token)
                        <a href="{{ route('pei.public.show',$pei->public_token) }}" target="_blank"
                           style="font-size:.68rem;color:var(--blue);text-decoration:none;display:flex;align-items:center;gap:3px;background:var(--blue-lt);padding:2px 7px;border-radius:20px;white-space:nowrap">
                            <i class="fa fa-external-link-alt" style="font-size:.55rem"></i> Ver
                        </a>
                        @endif
                        @if($pei->semaforo)
                        <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:{{ $pei->semaforo==='verde'?'#059669':($pei->semaforo==='amarillo'?'#d97706':'#dc2626') }}"></span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- FODA --}}
        <div>
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title"><i class="fa fa-th-large" style="color:var(--amber)"></i> Análisis FODA</div>
                </div>
                <div class="foda-quad">
                    <div class="foda-cell" style="background:#ecfdf5"><div class="foda-num" style="color:var(--green)">{{ $fodaFortalezas }}</div><div class="foda-lbl" style="color:var(--green)"><i class="fa fa-shield-alt"></i> Fortalezas</div></div>
                    <div class="foda-cell" style="background:var(--blue-lt)"><div class="foda-num" style="color:var(--blue)">{{ $fodaOportunidades }}</div><div class="foda-lbl" style="color:var(--blue)"><i class="fa fa-star"></i> Oportunidades</div></div>
                    <div class="foda-cell" style="background:var(--red-lt)"><div class="foda-num" style="color:var(--red)">{{ $fodaDebilidades }}</div><div class="foda-lbl" style="color:var(--red)"><i class="fa fa-exclamation-triangle"></i> Debilidades</div></div>
                    <div class="foda-cell" style="background:#fff7ed"><div class="foda-num" style="color:#ea580c">{{ $fodaAmenazas }}</div><div class="foda-lbl" style="color:#ea580c"><i class="fa fa-bolt"></i> Amenazas</div></div>
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

    <div class="kpi-grid">
        <div class="kpi-card"><div class="kpi-ic ic-green"><i class="fa fa-check-double"></i></div><div><div class="kpi-num">{{ $siessAprobados }}</div><div class="kpi-lbl">Aprobados</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-amber"><i class="fa fa-clock"></i></div><div><div class="kpi-num">{{ $siessPendientes }}</div><div class="kpi-lbl">Pendientes</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-red"><i class="fa fa-times-circle"></i></div><div><div class="kpi-num">{{ $siessObjetados }}</div><div class="kpi-lbl">Objetados</div></div></div>
        <div class="kpi-card"><div class="kpi-ic ic-blue"><i class="fa fa-database"></i></div><div><div class="kpi-num">{{ $siessModulos->count() }}</div><div class="kpi-lbl">Módulos</div></div></div>
    </div>

    @if($siessModulos->count())
    <div class="siess-grid">
        @foreach($siessModulos as $mod)
        @php $res = $mod->resumenEstados(); @endphp
        <div class="siess-card">
            <div class="siess-name">{{ $mod->nombre }}</div>
            <div class="siess-code">{{ $mod->codigo }} · {{ ucfirst($mod->periodicidad ?? '') }}</div>
            <div class="siess-pills">
                @if(($res['aprobado']??0)+($res['aprobado_silencio']??0)>0)<span class="siess-pill" style="background:var(--green-lt);color:var(--green)"><i class="fa fa-check" style="font-size:8px"></i> {{ ($res['aprobado']??0)+($res['aprobado_silencio']??0) }}</span>@endif
                @if(($res['pendiente_validacion']??0)>0)<span class="siess-pill" style="background:var(--amber-lt);color:var(--amber)"><i class="fa fa-clock" style="font-size:8px"></i> {{ $res['pendiente_validacion'] }}</span>@endif
                @if(($res['objetado']??0)>0)<span class="siess-pill" style="background:var(--red-lt);color:var(--red)"><i class="fa fa-times" style="font-size:8px"></i> {{ $res['objetado'] }}</span>@endif
                @if(($res['borrador']??0)>0)<span class="siess-pill" style="background:#f1f5f9;color:var(--muted)">{{ $res['borrador'] }} borr.</span>@endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card"><div style="text-align:center;padding:2rem;color:var(--muted);font-size:12px"><i class="fa fa-chart-bar" style="display:block;font-size:1.5rem;margin-bottom:.5rem;opacity:.3"></i>Sin módulos activos</div></div>
    @endif

</div>

</div>{{-- /dept-wrap --}}

{{-- FOOTER --}}
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-top">
            <div>
                <div class="footer-brand-row">
                    <div class="footer-logo">SP</div>
                    <div><div class="footer-name">SIPLAN</div><div class="footer-sub">Sistema de Planificación Estratégica · IPS Paraguay</div></div>
                </div>
                <p class="footer-desc">Plataforma de monitoreo estratégico institucional para el <strong>Instituto de Previsión Social del Paraguay</strong>. Datos en tiempo real.</p>
            </div>
            <div>
                <div class="ai-section-lbl">Desarrollado con asistencia de IA</div>
                <div class="ai-badges">
                    <a class="ai-badge ai-kiro" href="https://kiro.dev" target="_blank">
                        <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="7" fill="#F59E0B"/><path d="M12 36L24 12L36 36H28L24 27L20 36H12Z" fill="white"/></svg>
                        Kiro
                    </a>
                    <a class="ai-badge ai-q" href="https://aws.amazon.com/q/" target="_blank">
                        <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="7" fill="#1A9C3E"/><path d="M24 10C16.3 10 10 16.3 10 24C10 31.7 16.3 38 24 38C27.4 38 30.5 36.8 32.9 34.8L36 38L38 36L34.8 32.9C36.8 30.5 38 27.4 38 24C38 16.3 31.7 10 24 10ZM24 34C18.5 34 14 29.5 14 24C14 18.5 18.5 14 24 14C29.5 14 34 18.5 34 24C34 26.6 33 29 31.3 30.8L27 26.5C27.6 25.8 28 24.9 28 24C28 21.8 26.2 20 24 20C21.8 20 20 21.8 20 24C20 26.2 21.8 28 24 28C24.9 28 25.8 27.6 26.5 27L30.8 31.3C29 33 26.6 34 24 34Z" fill="white"/></svg>
                        Amazon Q
                    </a>
                    <a class="ai-badge ai-claude" href="https://claude.ai" target="_blank">
                        <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="7" fill="#F97316"/><path d="M24 8L38 32H10L24 8Z" fill="white" opacity=".9"/></svg>
                        Claude
                    </a>
                    <a class="ai-badge ai-gemini" href="https://gemini.google.com" target="_blank">
                        <svg width="14" height="14" viewBox="0 0 48 48"><rect width="48" height="48" rx="7" fill="#4285F4"/><path d="M24 8C24 8 30 20 30 24C30 28 24 40 24 40C24 40 18 28 18 24C18 20 24 8 24 8Z" fill="white"/><path d="M8 24C8 24 20 18 24 18C28 18 40 24 40 24C40 24 28 30 24 30C20 30 8 24 8 24Z" fill="white" opacity=".7"/></svg>
                        Gemini
                    </a>
                </div>
                <div class="dev-by">
                    Desarrollado por
                    <a href="https://www.linkedin.com/in/jufrancopy/" target="_blank">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#0077b5" style="vertical-align:middle;margin-right:2px"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                        Julio Franco
                    </a> · IPS Paraguay
                </div>
            </div>
        </div>
        <div class="footer-bottom">© {{ date('Y') }} SIPLAN · Instituto de Previsión Social del Paraguay · Todos los derechos reservados</div>
    </div>
</footer>

{{-- MODAL MATRIZ --}}
<div id="modalMatriz" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div style="font-size:.6rem;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.15rem"><i class="fa fa-th" style="margin-right:.3rem"></i>Matriz de Servicios</div>
                <div id="modalMatrizNombre" style="font-size:.9rem;font-weight:700;color:#fff"></div>
            </div>
            <button class="modal-close" onclick="cerrarModalMatriz()">✕</button>
        </div>
        <div id="modalMatrizBody" class="modal-body" style="padding:1rem">
            <div style="text-align:center;padding:3rem;color:var(--muted)"><div class="spinner"></div>Cargando…</div>
        </div>
    </div>
</div>

<script>
// Tabs
document.querySelectorAll('.dept-tab').forEach(function(btn){
    btn.addEventListener('click',function(){
        document.querySelectorAll('.dept-tab').forEach(function(b){b.classList.remove('active')});
        document.querySelectorAll('.dept-section').forEach(function(s){s.classList.remove('active')});
        this.classList.add('active');
        document.getElementById('dept-'+this.dataset.dept).classList.add('active');
        history.replaceState(null,'',location.pathname+'#'+this.dataset.dept);
    });
});
var h=location.hash.replace('#','');
if(h){var bt=document.querySelector('.dept-tab[data-dept="'+h+'"]');if(bt)bt.click();}

// Modal Matriz
function abrirModalMatriz(id,nombre){
    document.getElementById('modalMatrizNombre').textContent=nombre;
    document.getElementById('modalMatrizBody').innerHTML='<div style="text-align:center;padding:3rem;color:#64748b"><div class="spinner"></div>Cargando matriz…</div>';
    document.getElementById('modalMatriz').style.display='block';
    document.body.style.overflow='hidden';
    fetch('/riiss/evaluaciones/'+id+'/matriz-partial',{headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){return r.text();})
    .then(function(html){
        document.getElementById('modalMatrizBody').innerHTML=html;
        document.getElementById('modalMatrizBody').querySelectorAll('script').forEach(function(s){
            var ns=document.createElement('script');ns.textContent=s.textContent;s.parentNode.replaceChild(ns,s);
        });
    })
    .catch(function(){
        document.getElementById('modalMatrizBody').innerHTML='<div style="text-align:center;padding:2rem;color:#dc2626">Error al cargar la matriz.</div>';
    });
}
function cerrarModalMatriz(){
    document.getElementById('modalMatriz').style.display='none';
    document.body.style.overflow='';
}
document.addEventListener('click',function(e){
    var btn=e.target.closest('.btn-matriz');
    if(btn)abrirModalMatriz(btn.dataset.eval,btn.dataset.nombre);
});
document.getElementById('modalMatriz').addEventListener('click',function(e){if(e.target===this)cerrarModalMatriz();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')cerrarModalMatriz();});
</script>
</body>
</html>
