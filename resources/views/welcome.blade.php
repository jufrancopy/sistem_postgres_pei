<!DOCTYPE html>
<html lang="es">
<head>
@php use Illuminate\Support\Facades\Auth; @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>SIPLAN — Sistema de Planificación Estratégica · IPS Paraguay</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f1f5f9;--surface:#ffffff;--surface-alt:#f8fafc;--border:#e2e8f0;--border-light:#f1f5f9;
  --blue:#1d4ed8;--blue-50:#eff6ff;--blue-100:#dbeafe;--blue-600:#2563eb;--blue-700:#1d4ed8;--blue-800:#1e3a8a;--blue-900:#1e2d5f;
  --green:#059669;--green-50:#ecfdf5;--green-400:#34d399;
  --amber:#d97706;--amber-50:#fffbeb;--amber-400:#fbbf24;
  --red:#dc2626;--red-50:#fef2f2;--red-400:#f87171;
  --violet:#7c3aed;--violet-50:#f5f3ff;
  --orange:#ea580c;--orange-50:#fff7ed;
  --text:#0f172a;--text-secondary:#475569;--muted:#94a3b8;
  --radius:16px;--radius-sm:10px;--radius-xs:6px;
  --shadow-sm:0 1px 2px rgba(0,0,0,.04);
  --shadow:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.03);
  --shadow-md:0 4px 16px rgba(0,0,0,.07);
  --shadow-lg:0 12px 40px rgba(0,0,0,.1);
  --safe-b:env(safe-area-inset-bottom,0px);
}
html{-webkit-tap-highlight-color:transparent;scroll-behavior:smooth}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.6;min-height:100vh;overflow-x:hidden}

/* ═══ ANIMACIONES ═══ */
@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes scaleIn{from{opacity:0;transform:scale(.92)}to{opacity:1;transform:scale(1)}}
@keyframes slideRight{from{opacity:0;transform:translateX(-12px)}to{opacity:1;transform:translateX(0)}}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.15}}
@keyframes pulseRing{0%{transform:scale(1);opacity:.5}100%{transform:scale(2.2);opacity:0}}
@keyframes float{0%,100%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-8px) rotate(1deg)}}
@keyframes spin{to{transform:rotate(360deg)}}
@keyframes growBar{from{width:0}}
@keyframes dashDraw{to{stroke-dashoffset:0}}
.anim{animation:fadeUp .5s cubic-bezier(.22,1,.36,1) both}
.d1{animation-delay:.06s}.d2{animation-delay:.12s}.d3{animation-delay:.18s}.d4{animation-delay:.24s}.d5{animation-delay:.3s}.d6{animation-delay:.36s}.d7{animation-delay:.42s}.d8{animation-delay:.48s}

/* ═══ NAV ═══ */
.topbar{position:sticky;top:0;z-index:300;background:rgba(255,255,255,.85);backdrop-filter:blur(24px) saturate(1.6);-webkit-backdrop-filter:blur(24px) saturate(1.6);border-bottom:1px solid var(--border);height:58px;display:flex;align-items:center;padding:0 clamp(14px,4vw,40px);justify-content:space-between}
.topbar-left{display:flex;align-items:center;gap:12px}
.topbar-logo{width:38px;height:38px;border-radius:var(--radius-sm);background:linear-gradient(145deg,var(--blue-700),var(--violet));display:grid;place-items:center;color:#fff;font-weight:900;font-size:11px;letter-spacing:-.8px;box-shadow:0 4px 14px rgba(29,78,216,.3);flex-shrink:0}
.topbar-text{line-height:1.15}
.topbar-title{font-weight:800;font-size:15px;letter-spacing:-.3px}
.topbar-sub{font-size:10px;color:var(--muted);font-weight:500}
.topbar-right{display:flex;align-items:center;gap:10px}
.status-chip{display:flex;align-items:center;gap:6px;font-size:10px;font-weight:600;padding:5px 12px;border-radius:100px;background:var(--green-50);color:var(--green);border:1px solid #a7f3d0}
.status-dot{width:7px;height:7px;border-radius:50%;background:var(--green);animation:blink 2s infinite;position:relative}
.status-dot::after{content:'';position:absolute;inset:-4px;border-radius:50%;border:2px solid var(--green);animation:pulseRing 2s infinite}
.btn-primary{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;border-radius:var(--radius-sm);font-size:12px;font-weight:700;background:var(--blue);color:#fff;border:none;cursor:pointer;text-decoration:none;box-shadow:0 4px 14px rgba(29,78,216,.3);transition:all .2s cubic-bezier(.22,1,.36,1);white-space:nowrap}
.btn-primary:hover{background:var(--blue-700);transform:translateY(-1px);box-shadow:0 6px 20px rgba(29,78,216,.4);color:#fff;text-decoration:none}
.btn-primary:active{transform:translateY(0)}

/* ═══ HERO — NUEVO DISEÑO CLARO ═══ */
.hero{background:var(--surface);border-bottom:1px solid var(--border);padding:clamp(24px,4vw,48px) clamp(14px,4vw,40px);position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;top:-120px;right:-120px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%);pointer-events:none}
.hero::after{content:'';position:absolute;bottom:-80px;left:-80px;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.05) 0%,transparent 70%);pointer-events:none}
.hero-inner{max-width:1140px;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;gap:clamp(24px,4vw,48px);align-items:center}
.hero-content{min-width:0}
.hero-badge{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--blue);background:var(--blue-50);border:1px solid var(--blue-100);padding:5px 14px;border-radius:100px;margin-bottom:16px}
.hero h1{font-size:clamp(1.6rem,3.5vw,2.6rem);font-weight:900;color:var(--text);line-height:1.1;letter-spacing:-.6px;margin-bottom:12px}
.hero h1 span{color:var(--blue);position:relative}
.hero h1 span::after{content:'';position:absolute;bottom:2px;left:0;right:0;height:3px;background:var(--blue);border-radius:2px;opacity:.2}
.hero p{font-size:clamp(12px,1.4vw,14px);color:var(--text-secondary);max-width:420px;line-height:1.7}
.hero-stats-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.hero-stat-card{padding:clamp(14px,2vw,20px);border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);position:relative;overflow:hidden;transition:all .25s cubic-bezier(.22,1,.36,1)}
.hero-stat-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);border-color:#cbd5e1}
.hero-stat-card::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;border-radius:0 4px 4px 0}
.hero-stat-card.hsc-blue::before{background:var(--blue)}.hero-stat-card.hsc-green::before{background:var(--green)}.hero-stat-card.hsc-amber::before{background:var(--amber)}.hero-stat-card.hsc-red::before{background:var(--red)}.hero-stat-card.hsc-violet::before{background:var(--violet)}.hero-stat-card.hsc-white::before{background:var(--text)}
.hero-stat-card .hsc-icon{width:32px;height:32px;border-radius:8px;display:grid;place-items:center;font-size:13px;margin-bottom:10px}
.hero-stat-card .hsc-num{font-size:clamp(1.4rem,2.5vw,2rem);font-weight:900;line-height:1;font-variant-numeric:tabular-nums}
.hero-stat-card .hsc-lbl{font-size:10px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-top:4px}

/* ═══ CONTENIDO PRINCIPAL ═══ */
.main-wrap{max-width:1140px;margin:0 auto;padding:clamp(20px,4vw,36px) clamp(14px,4vw,24px) clamp(40px,6vw,80px);padding-bottom:calc(40px + var(--safe-b))}

/* ═══ TABS — NUEVO ESTILO ═══ */
.tabs-bar{display:flex;gap:4px;margin-bottom:28px;border-bottom:2px solid var(--border);position:relative}
.tab-btn{position:relative;padding:12px 20px;font-size:13px;font-weight:600;color:var(--muted);background:none;border:none;cursor:pointer;transition:color .2s;white-space:nowrap;display:flex;align-items:center;gap:8px;-webkit-user-select:none;user-select:none}
.tab-btn i{font-size:14px}
.tab-btn:hover{color:var(--text-secondary)}
.tab-btn.active{color:var(--blue)}
.tab-btn.active::after{content:'';position:absolute;bottom:-2px;left:0;right:0;height:2.5px;background:var(--blue);border-radius:2px 2px 0 0;animation:scaleIn .25s cubic-bezier(.22,1,.36,1)}
.tab-btn .tab-count{font-size:10px;font-weight:700;background:var(--bg);color:var(--muted);padding:1px 7px;border-radius:100px;margin-left:2px;transition:all .2s}
.tab-btn.active .tab-count{background:var(--blue-50);color:var(--blue)}
.tab-panel{display:none}.tab-panel.active{display:block;animation:fadeUp .3s cubic-bezier(.22,1,.36,1) both}

/* ═══ KPI STRIP — NUEVO: FILA HORIZONTAL COMPACTA ═══ */
.kpi-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:28px}
.kpi-item{display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);transition:all .2s cubic-bezier(.22,1,.36,1);position:relative;overflow:hidden}
.kpi-item:hover{box-shadow:var(--shadow-md);transform:translateY(-2px);border-color:#cbd5e1}
.kpi-icon{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;font-size:17px;flex-shrink:0}
.kpi-value{font-size:1.6rem;font-weight:900;line-height:1;font-variant-numeric:tabular-nums}
.kpi-label{font-size:10.5px;color:var(--muted);font-weight:500;margin-top:2px}
.ki-blue .kpi-icon{background:var(--blue-50);color:var(--blue)}
.ki-green .kpi-icon{background:var(--green-50);color:var(--green)}
.ki-amber .kpi-icon{background:var(--amber-50);color:var(--amber)}
.ki-red .kpi-icon{background:var(--red-50);color:var(--red)}
.ki-violet .kpi-icon{background:var(--violet-50);color:var(--violet)}

/* ═══ LAYOUT DOBLE ═══ */
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px}

/* ═══ PANEL ═══ */
.panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;transition:box-shadow .2s}
.panel:hover{box-shadow:var(--shadow)}
.panel-header{padding:16px 20px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;justify-content:space-between;gap:10px}
.panel-title{font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px}
.panel-title i{font-size:14px}
.panel-badge{font-size:10px;font-weight:700;padding:3px 10px;border-radius:100px;background:var(--blue-50);color:var(--blue);font-variant-numeric:tabular-nums}

/* ═══ EVAL LIST — CON RING DE PROGRESO ═══ */
.eval-list{display:flex;flex-direction:column}
.eval-row{padding:14px 20px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:14px;transition:background .12s}
.eval-row:last-child{border-bottom:none}
.eval-row:hover{background:var(--surface-alt)}
.eval-ring{width:42px;height:42px;flex-shrink:0;position:relative}
.eval-ring svg{width:42px;height:42px;transform:rotate(-90deg)}
.eval-ring circle{fill:none;stroke-width:4;stroke-linecap:round}
.eval-ring .ring-bg{stroke:#f1f5f9}
.eval-ring .ring-fg{transition:stroke-dashoffset .8s cubic-bezier(.22,1,.36,1)}
.eval-ring .ring-pct{position:absolute;inset:0;display:grid;place-items:center;font-size:9px;font-weight:800}
.eval-info{flex:1;min-width:0}
.eval-name{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.eval-sub{font-size:10px;color:var(--muted);margin-top:2px;display:flex;gap:6px;flex-wrap:wrap}
.eval-actions{display:flex;align-items:center;gap:6px;flex-shrink:0}
.state-tag{font-size:9.5px;font-weight:600;padding:3px 9px;border-radius:var(--radius-xs);letter-spacing:.02em;white-space:nowrap}
.tag-green{background:var(--green-50);color:var(--green)}
.tag-blue{background:var(--blue-50);color:var(--blue)}
.tag-gray{background:var(--bg);color:var(--muted)}
.btn-matriz{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:var(--radius-xs);font-size:10px;font-weight:700;background:var(--blue-50);color:var(--blue);border:1px solid var(--blue-100);cursor:pointer;white-space:nowrap;transition:all .15s}
.btn-matriz:hover{background:var(--blue-100);transform:translateY(-1px)}
.btn-matriz svg{flex-shrink:0}

/* ═══ NIVELES — ESCALA VISUAL ═══ */
.nivel-visual{padding:20px;display:flex;flex-direction:column;gap:10px}
.nivel-row{display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:var(--radius-sm);border:1px solid var(--border-light);background:var(--surface-alt);transition:all .15s}
.nivel-row:hover{border-color:var(--border);background:var(--surface);box-shadow:var(--shadow-sm)}
.nivel-badge{width:36px;height:36px;border-radius:50%;display:grid;place-items:center;font-size:13px;font-weight:800;color:#fff;flex-shrink:0;box-shadow:0 3px 8px rgba(0,0,0,.15)}
.nivel-info{flex:1;min-width:0}
.nivel-name{font-size:13px;font-weight:700}
.nivel-type{font-size:11px;color:var(--muted)}
.nivel-hosp{font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px}
.nivel-bar{width:80px;height:6px;background:#f1f5f9;border-radius:3px;overflow:hidden;flex-shrink:0}
.nivel-bar-fill{height:100%;border-radius:3px}

/* ═══ SEMÁFORO — BARRAS HORIZONTALES ═══ */
.semaforo-section{padding:20px}
.semaforo-row{display:flex;align-items:center;gap:14px;margin-bottom:14px}
.semaforo-row:last-child{margin-bottom:0}
.semaforo-label{width:70px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;flex-shrink:0;display:flex;align-items:center;gap:6px}
.semaforo-label .sf-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.semaforo-track{flex:1;height:32px;background:var(--bg);border-radius:var(--radius-xs);overflow:hidden;position:relative}
.semaforo-fill{height:100%;border-radius:var(--radius-xs);display:flex;align-items:center;padding:0 12px;font-size:13px;font-weight:800;color:#fff;animation:growBar .8s cubic-bezier(.22,1,.36,1) both;min-width:fit-content}
.semaforo-total{font-size:11px;color:var(--muted);font-weight:500;flex-shrink:0;width:40px;text-align:right}

/* ═══ FODA — NUEVO LAYOUT CON BARRAS ═══ */
.foda-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:20px}
.foda-item{padding:20px 16px;border-radius:var(--radius-sm);text-align:center;position:relative;overflow:hidden;border:1px solid transparent;transition:all .2s}
.foda-item:hover{transform:translateY(-2px);box-shadow:var(--shadow-sm)}
.foda-item.fi-green{background:var(--green-50);border-color:#bbf7d0;color:var(--green)}
.foda-item.fi-blue{background:var(--blue-50);border-color:#bfdbfe;color:var(--blue)}
.foda-item.fi-red{background:var(--red-50);border-color:#fecaca;color:var(--red)}
.foda-item.fi-orange{background:var(--orange-50);border-color:#fed7aa;color:var(--orange)}
.foda-icon{font-size:20px;margin-bottom:8px;opacity:.6}
.foda-num{font-size:clamp(2rem,3vw,2.8rem);font-weight:900;line-height:1;font-variant-numeric:tabular-nums}
.foda-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-top:6px}
.foda-meta{padding:0 20px 20px;display:flex;flex-direction:column;gap:10px}
.foda-meta-row{display:flex;justify-content:space-between;align-items:center;font-size:12.5px;padding-bottom:10px;border-bottom:1px solid var(--border-light)}
.foda-meta-row:last-child{border-bottom:none;padding-bottom:0}
.foda-meta-row .fm-label{color:var(--muted);font-weight:500}
.foda-meta-row .fm-value{font-weight:800;font-variant-numeric:tabular-nums}

/* ═══ PEI LIST ═══ */
.pei-list{border-top:1px solid var(--border-light)}
.pei-row{padding:12px 20px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:12px;transition:background .12s}
.pei-row:last-child{border-bottom:none}
.pei-row:hover{background:var(--surface-alt)}
.pei-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.pei-body{flex:1;min-width:0}
.pei-name{font-size:12.5px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pei-year{font-size:10px;color:var(--muted)}
.pei-link{font-size:10px;font-weight:600;color:var(--blue);text-decoration:none;display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:var(--radius-xs);background:var(--blue-50);flex-shrink:0;transition:all .15s;white-space:nowrap}
.pei-link:hover{background:var(--blue-100);text-decoration:none}

/* ═══ SIESS GRID ═══ */
.siess-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px}
.siess-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:18px;box-shadow:var(--shadow-sm);transition:all .2s cubic-bezier(.22,1,.36,1);position:relative;overflow:hidden}
.siess-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.siess-card:hover{box-shadow:var(--shadow-md);transform:translateY(-3px);border-color:#cbd5e1}
.siess-name{font-size:13px;font-weight:700;margin-bottom:3px}
.siess-code{font-size:9.5px;color:var(--muted);font-family:'SF Mono',SFMono-Regular,Consolas,monospace;margin-bottom:12px;letter-spacing:.02em}
.siess-pills{display:flex;flex-wrap:wrap;gap:5px}
.siess-pill{font-size:10px;font-weight:600;padding:3px 9px;border-radius:var(--radius-xs);display:inline-flex;align-items:center;gap:4px}
.sc-green::before{background:var(--green)}.sc-amber::before{background:var(--amber)}.sc-red::before{background:var(--red)}.sc-blue::before{background:var(--blue)}

/* ═══ MODAL ═══ */
.modal-bg{display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,.5);backdrop-filter:blur(6px)}
.modal-bg.open{display:flex;align-items:center;justify-content:center;padding:clamp(8px,3vw,24px)}
.modal-card{background:var(--surface);border-radius:20px;width:100%;max-width:980px;overflow:hidden;box-shadow:var(--shadow-lg);display:flex;flex-direction:column;max-height:calc(100dvh - clamp(16px,6vw,48px))}
.modal-top{background:var(--blue-900);padding:18px 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;position:relative}
.modal-top::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(124,58,237,.15),transparent 60%);pointer-events:none}
.modal-x{background:rgba(255,255,255,.1);border:none;color:#fff;width:36px;height:36px;border-radius:var(--radius-sm);cursor:pointer;font-size:15px;display:grid;place-items:center;transition:all .15s;position:relative;z-index:1}
.modal-x:hover{background:rgba(255,255,255,.2);transform:scale(1.05)}
.modal-content{padding:24px;overflow-y:auto;flex:1;-webkit-overflow-scrolling:touch}

/* ═══ FOOTER ═══ */
.site-footer{background:var(--text);color:rgba(255,255,255,.6);padding:40px clamp(14px,4vw,40px) 24px;padding-bottom:calc(24px + var(--safe-b))}
.footer-inner{max-width:1140px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:1.4fr 1fr;gap:40px;margin-bottom:32px;padding-bottom:28px;border-bottom:1px solid rgba(255,255,255,.08)}
.footer-brand{display:flex;align-items:center;gap:10px;margin-bottom:12px}
.footer-logo{width:38px;height:38px;border-radius:var(--radius-sm);background:linear-gradient(145deg,var(--blue-600),var(--violet));display:grid;place-items:center;color:#fff;font-weight:900;font-size:11px;letter-spacing:-.8px}
.footer-brand-text .fb-name{font-size:15px;font-weight:800;color:#fff}
.footer-brand-text .fb-sub{font-size:10px;color:rgba(255,255,255,.4)}
.footer-desc{font-size:12px;line-height:1.7;max-width:340px}
.ai-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.3);margin-bottom:10px}
.ai-list{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}
.ai-chip{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:var(--radius-sm);font-size:11px;font-weight:600;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.7);text-decoration:none;transition:all .2s}
.ai-chip:hover{background:rgba(255,255,255,.1);color:#fff;text-decoration:none;transform:translateY(-1px)}
.dev-credit{font-size:11px;color:rgba(255,255,255,.4)}
.dev-credit a{color:rgba(255,255,255,.7);text-decoration:none;font-weight:600}
.dev-credit a:hover{color:#fff;text-decoration:underline}
.footer-copy{text-align:center;font-size:10px;color:rgba(255,255,255,.25)}

/* ═══ EMPTY STATE ═══ */
.empty{text-align:center;padding:3rem 1rem;color:var(--muted);font-size:12px}
.empty i{display:block;font-size:2rem;margin-bottom:.5rem;opacity:.15}
.empty p{line-height:1.6}

/* ═══ SPINNER ═══ */
.spinner{width:36px;height:36px;border:3px solid var(--border);border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 14px}

/* ═══ RESPONSIVE ═══ */
@media(max-width:900px){
  .hero-inner{grid-template-columns:1fr;gap:28px}
  .hero-stats-grid{grid-template-columns:repeat(3,1fr)}
  .hero-stat-card:nth-child(n+4){display:none}
  .grid-2{grid-template-columns:1fr}
  .kpi-strip{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:640px){
  :root{--radius:14px;--radius-sm:9px}
  .topbar{padding:0 12px;gap:8px}
  .topbar-sub{display:none}
  .status-chip span:not(.status-dot){display:none}
  .btn-primary{padding:8px 14px}
  .btn-primary span{display:none}
  .btn-primary i{font-size:15px}

  .hero{padding:20px 14px}
  .hero-stats-grid{grid-template-columns:1fr 1fr;gap:8px}
  .hero-stat-card{padding:14px}
  .hero-stat-card .hsc-icon{width:28px;height:28px;font-size:11px;margin-bottom:8px}
  .hero-stat-card .hsc-num{font-size:1.3rem}

  .main-wrap{padding:16px 12px 28px}
  .tabs-bar{overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch}
  .tabs-bar::-webkit-scrollbar{display:none}
  .tab-btn{padding:10px 14px;font-size:12px}
  .tab-btn .tab-count{display:none}

  .kpi-strip{grid-template-columns:1fr 1fr;gap:8px;margin-bottom:20px}
  .kpi-item{padding:12px 14px;gap:10px}
  .kpi-icon{width:38px;height:38px;font-size:15px;border-radius:10px}
  .kpi-value{font-size:1.3rem}
  .kpi-label{font-size:9.5px}

  .panel-header{padding:12px 14px}
  .panel-title{font-size:12px}

  /* Eval rows mobile */
  .eval-row{padding:12px 14px;gap:10px;flex-wrap:wrap}
  .eval-ring{width:38px;height:38px;order:-1}
  .eval-ring svg{width:38px;height:38px}
  .eval-ring .ring-pct{font-size:8px}
  .eval-info{min-width:0;flex:1}
  .eval-name{font-size:12px}
  .eval-actions{width:100%;justify-content:flex-end;padding-top:6px;border-top:1px solid var(--border-light);margin-top:4px}
  .btn-matriz{font-size:9px;padding:4px 8px}

  /* Niveles */
  .nivel-visual{padding:14px}
  .nivel-row{padding:10px 12px;gap:10px}
  .nivel-bar{display:none}
  .nivel-badge{width:32px;height:32px;font-size:11px}

  /* Semáforo mobile */
  .semaforo-section{padding:14px}
  .semaforo-row{gap:10px;margin-bottom:10px}
  .semaforo-label{width:56px;font-size:9px}
  .semaforo-label .sf-dot{width:8px;height:8px}
  .semaforo-track{height:26px}
  .semaforo-fill{font-size:11px;padding:0 8px}
  .semaforo-total{font-size:10px;width:32px}

  /* FODA mobile */
  .foda-grid{grid-template-columns:1fr 1fr;gap:8px;padding:14px}
  .foda-item{padding:14px 10px}
  .foda-icon{font-size:16px;margin-bottom:5px}
  .foda-num{font-size:1.5rem}
  .foda-label{font-size:8px;letter-spacing:.3px}
  .foda-meta{padding:0 14px 14px}

  /* PEI */
  .pei-row{padding:10px 14px;gap:8px}

  /* SIESS */
  .siess-grid{grid-template-columns:1fr 1fr;gap:8px}
  .siess-card{padding:14px}
  .siess-name{font-size:12px}
  .siess-code{font-size:9px;margin-bottom:10px}
  .siess-pill{font-size:9px;padding:2px 7px}

  /* Footer */
  .footer-grid{grid-template-columns:1fr;gap:24px;margin-bottom:24px;padding-bottom:20px}
  .ai-list{justify-content:flex-start}
  .footer-desc{max-width:100%}
}
@media(max-width:380px){
  .hero-stats-grid{grid-template-columns:1fr 1fr}
  .siess-grid{grid-template-columns:1fr}
  .tab-btn i{display:none}
  .tab-btn{font-size:11px;padding:9px 10px}
}
@media print{
  .topbar,.tabs-bar,.btn-matriz,.btn-primary,.status-chip,.modal-bg{display:none!important}
  .tab-panel{display:block!important}
  .panel{break-inside:avoid;box-shadow:none;border:1px solid #ccc}
}
</style>
</head>
<body>

{{-- NAV --}}
<nav class="topbar" role="navigation" aria-label="Navegación principal">
    <div class="topbar-left">
        <a href="{{ url('/') }}" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:12px">
            <div class="topbar-logo" aria-hidden="true">SP</div>
            <div class="topbar-text">
                <div class="topbar-title">SIPLAN</div>
                <div class="topbar-sub">IPS · Planificación Estratégica</div>
            </div>
        </a>
    </div>
    <div class="topbar-right">
        <div class="status-chip" aria-label="Sistema en línea"><span class="status-dot" aria-hidden="true"></span><span>En línea</span></div>
        @auth
        <a href="{{ route('home') }}" class="btn-primary" aria-label="Ir al sistema"><i class="fa fa-th-large"></i><span>Sistema</span></a>
        @else
        <a href="{{ route('login') }}" class="btn-primary" aria-label="Iniciar sesión"><i class="fa fa-sign-in-alt"></i><span>Ingresar</span></a>
        @endauth
    </div>
</nav>

{{-- HERO — DISEÑO CLARO CON STATS INTEGRADOS --}}
<section class="hero" aria-label="Resumen general">
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge anim"><i class="fa fa-shield-alt"></i> Instituto de Previsión Social</div>
            <h1 class="anim d1">Sistema de <span>Planificación</span> Estratégica</h1>
            <p class="anim d2">Monitoreo en tiempo real de la Red de Salud IPS, planificación estratégica y estadísticas institucionales.</p>
        </div>
        <div class="hero-stats-grid">
            @php $gV=$peiSemaforo->get('verde',0);$gA=$peiSemaforo->get('amarillo',0);$gR=$peiSemaforo->get('rojo',0); @endphp
            <div class="hero-stat-card hsc-blue anim d3">
                <div class="hsc-icon" style="background:var(--blue-50);color:var(--blue)"><i class="fa fa-clipboard-list"></i></div>
                <div class="hsc-num">{{ $evalTotal }}</div>
                <div class="hsc-lbl">Evaluaciones</div>
            </div>
            <div class="hero-stat-card hsc-green anim d4">
                <div class="hsc-icon" style="background:var(--green-50);color:var(--green)"><i class="fa fa-bullseye"></i></div>
                <div class="hsc-num">{{ $peiPlanes }}</div>
                <div class="hsc-lbl">Planes PEI</div>
            </div>
            <div class="hero-stat-card hsc-amber anim d5">
                <div class="hsc-icon" style="background:var(--amber-50);color:var(--amber)"><i class="fa fa-th-large"></i></div>
                <div class="hsc-num">{{ $fodaAnalisis }}</div>
                <div class="hsc-lbl">Análisis FODA</div>
            </div>
            <div class="hero-stat-card hsc-violet anim d6">
                <div class="hsc-icon" style="background:var(--violet-50);color:var(--violet)"><i class="fa fa-check-double"></i></div>
                <div class="hsc-num">{{ $siessAprobados }}</div>
                <div class="hsc-lbl">SIESS aprob.</div>
            </div>
            <div class="hero-stat-card hsc-green anim d7">
                <div class="hsc-icon" style="background:var(--green-50);color:var(--green)"><i class="fa fa-check-circle"></i></div>
                <div class="hsc-num" style="color:var(--green)">{{ $gV }}</div>
                <div class="hsc-lbl">PEI Verde</div>
            </div>
            <div class="hero-stat-card hsc-red anim d8">
                <div class="hsc-icon" style="background:var(--red-50);color:var(--red)"><i class="fa fa-exclamation-circle"></i></div>
                <div class="hsc-num" style="color:var(--red)">{{ $gR }}</div>
                <div class="hsc-lbl">PEI Rojo</div>
            </div>
        </div>
    </div>
</section>

{{-- CONTENIDO --}}
<main class="main-wrap">

{{-- TABS --}}
@php
    $tabRiissActive = $config->show_riiss;
    $tabPlanActive = !$tabRiissActive && ($config->show_pei || $config->show_foda);
    $tabStatsActive = !$tabRiissActive && !($config->show_pei || $config->show_foda);
@endphp
<nav class="tabs-bar" role="tablist" aria-label="Secciones">
    @if($config->show_riiss)
    <button class="tab-btn {{ $tabRiissActive ? 'active' : '' }}" role="tab" aria-selected="{{ $tabRiissActive ? 'true' : 'false' }}" aria-controls="panel-riiss" data-dept="riiss"><i class="fa fa-hospital"></i> Red de Salud <span class="tab-count">{{ $evalTotal }}</span></button>
    @endif
    @if($config->show_pei || $config->show_foda)
    <button class="tab-btn {{ $tabPlanActive ? 'active' : '' }}" role="tab" aria-selected="{{ $tabPlanActive ? 'true' : 'false' }}" aria-controls="panel-planificacion" data-dept="planificacion"><i class="fa fa-bullseye"></i> Planificación <span class="tab-count">{{ $peiPlanes + $fodaAnalisis }}</span></button>
    @endif
    <button class="tab-btn {{ $tabStatsActive ? 'active' : '' }}" role="tab" aria-selected="{{ $tabStatsActive ? 'true' : 'false' }}" aria-controls="panel-estadisticas" data-dept="estadisticas"><i class="fa fa-chart-bar"></i> Estadísticas <span class="tab-count">{{ $siessModulos->count() }}</span></button>
</nav>

{{-- ═══════════════════════ RED DE SALUD ═══════════════════════ --}}
@if($config->show_riiss)
<div class="tab-panel {{ $tabRiissActive ? 'active' : '' }}" id="panel-riiss" role="tabpanel">

    <div class="kpi-strip">
        <div class="kpi-item ki-blue anim d1"><div class="kpi-icon"><i class="fa fa-clipboard-list"></i></div><div><div class="kpi-value">{{ $evalTotal }}</div><div class="kpi-label">Evaluaciones</div></div></div>
        <div class="kpi-item ki-green anim d2"><div class="kpi-icon"><i class="fa fa-check-circle"></i></div><div><div class="kpi-value">{{ $evalCompletadas }}</div><div class="kpi-label">Completadas</div></div></div>
        <div class="kpi-item ki-amber anim d3"><div class="kpi-icon"><i class="fa fa-spinner"></i></div><div><div class="kpi-value">{{ $evalEnCurso }}</div><div class="kpi-label">En curso</div></div></div>
        <div class="kpi-item ki-violet anim d4"><div class="kpi-icon"><i class="fa fa-sitemap"></i></div><div><div class="kpi-value">{{ \App\Models\Riiss\Establecimiento::count() }}</div><div class="kpi-label">Establecimientos</div></div></div>
    </div>

    <div class="grid-2">

        {{-- Evaluaciones con ring --}}
        <div class="panel anim d5">
            <div class="panel-header">
                <div class="panel-title"><i class="fa fa-clipboard-check" style="color:var(--green)"></i> Últimas evaluaciones</div>
                <span class="panel-badge">{{ $evalTotal }}</span>
            </div>
            @if($evalRecientes->count())
            <div class="eval-list">
            @foreach($evalRecientes as $ev)
            @php
                $p   = (float)($ev->porcentaje_cumplimiento ?? 0);
                $stc = ['completada'=>'tag-green','en_curso'=>'tag-blue','borrador'=>'tag-gray'][$ev->estado] ?? 'tag-gray';
                $dc  = $p >= 90 ? 'var(--green)' : ($p >= 70 ? 'var(--amber)' : 'var(--red)');
                $circumference = 2 * pi() * 17;
                $offset = $circumference - ($p / 100) * $circumference;
            @endphp
            <div class="eval-row">
                <div class="eval-ring">
                    <svg viewBox="0 0 42 42">
                        <circle class="ring-bg" cx="21" cy="21" r="17"/>
                        <circle class="ring-fg" cx="21" cy="21" r="17"
                            stroke="{{ $p >= 90 ? '#059669' : ($p >= 70 ? '#d97706' : '#dc2626') }}"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $offset }}"/>
                    </svg>
                    <div class="ring-pct" style="color:{{ $dc }}">{{ round($p) }}%</div>
                </div>
                <div class="eval-info">
                    <div class="eval-name" title="{{ $ev->establecimiento?->nombre_oficial ?? '' }}">{{ $ev->establecimiento?->nombre_oficial ?? '—' }}</div>
                    <div class="eval-sub">
                        @if($ev->establecimiento?->complejidadTipo?->nombre)<span>{{ $ev->establecimiento?->complejidadTipo?->nombre }}</span>@endif
                        @if($ev->fecha_evaluacion)<span>{{ \Carbon\Carbon::parse($ev->fecha_evaluacion)->format('d/m/Y') }}</span>@endif
                    </div>
                </div>
                <div class="eval-actions">
                    <span class="state-tag {{ $stc }}">{{ ucfirst(str_replace('_',' ',$ev->estado)) }}</span>
                    <button class="btn-matriz"
                            data-eval="{{ $ev->id }}"
                            data-nombre="{{ $ev->establecimiento?->nombre_oficial ?? 'Evaluación #'.$ev->id }}"
                            title="Ver Matriz de Servicios"
                            aria-label="Ver matriz de {{ $ev->establecimiento?->nombre_oficial ?? '' }}">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                        Matriz
                    </button>
                </div>
            </div>
            @endforeach
            </div>
            @else
            <div class="empty"><i class="fa fa-hospital"></i><p>Sin evaluaciones registradas</p></div>
            @endif
        </div>

        {{-- Niveles de complejidad — escala visual --}}
        <div class="panel anim d6">
            <div class="panel-header">
                <div class="panel-title"><i class="fa fa-layer-group" style="color:var(--violet)"></i> Niveles de complejidad</div>
                <span class="panel-badge">{{ $complejidadTipos->count() }}</span>
            </div>
            <div class="nivel-visual">
            @php $maxGrado = $complejidadTipos->max('grado') ?: 1; @endphp
            @foreach($complejidadTipos as $ct)
            @php $barW = round(($ct->grado / $maxGrado) * 100); @endphp
            <div class="nivel-row">
                <span class="nivel-badge" style="background:{{ $ct->color ?? '#6b7280' }}">{{ $ct->grado }}</span>
                <div class="nivel-info">
                    <div class="nivel-name">{{ $ct->nombre }}</div>
                    <div class="nivel-type">{{ $ct->tipo_establecimiento ?? '—' }}</div>
                    <div class="nivel-hosp">
                        @if($ct->es_hospitalario)
                        <span style="color:var(--blue)"><i class="fa fa-hospital"></i> Hospitalario</span>
                        @else
                        <span style="color:var(--muted)"><i class="fa fa-building"></i> No hospitalario</span>
                        @endif
                    </div>
                </div>
                <div class="nivel-bar"><div class="nivel-bar-fill" style="width:{{ $barW }}%;background:{{ $ct->color ?? '#6b7280' }}"></div></div>
            </div>
            @endforeach
            </div>
        </div>

    </div>
</div>

@endif

{{-- ═══════════════════════ PLANIFICACIÓN ═══════════════════════ --}}
@if($config->show_pei || $config->show_foda)
<div class="tab-panel {{ $tabPlanActive ? 'active' : '' }}" id="panel-planificacion" role="tabpanel">

    <div class="kpi-strip">
        <div class="kpi-item ki-violet anim d1"><div class="kpi-icon"><i class="fa fa-bullseye"></i></div><div><div class="kpi-value">{{ $peiPlanes }}</div><div class="kpi-label">Planes PEI</div></div></div>
        <div class="kpi-item ki-blue anim d2"><div class="kpi-icon"><i class="fa fa-rocket"></i></div><div><div class="kpi-value">{{ $peiAcciones }}</div><div class="kpi-label">Acciones</div></div></div>
        <div class="kpi-item ki-amber anim d3"><div class="kpi-icon"><i class="fa fa-th-large"></i></div><div><div class="kpi-value">{{ $fodaAnalisis }}</div><div class="kpi-label">Análisis FODA</div></div></div>
        <div class="kpi-item ki-green anim d4"><div class="kpi-icon"><i class="fa fa-chess"></i></div><div><div class="kpi-value">{{ $fodaEstrategias }}</div><div class="kpi-label">Estrategias</div></div></div>
    </div>

    <div class="grid-2">

        @if($config->show_pei)
        {{-- PEI — semáforo como barras --}}
        <div class="panel anim d5">
            <div class="panel-header">
                <div class="panel-title"><i class="fa fa-bullseye" style="color:var(--violet)"></i> Planes Estratégicos</div>
                <span class="panel-badge">{{ $peiPlanes }}</span>
            </div>
            @php $stTotal = max($gV + $gA + $gR, 1); @endphp
            <div class="semaforo-section">
                <div class="semaforo-row">
                    <div class="semaforo-label" style="color:var(--green)"><span class="sf-dot" style="background:var(--green)"></span> Verde</div>
                    <div class="semaforo-track"><div class="semaforo-fill" style="width:{{ round(($gV/$stTotal)*100) }}%;background:var(--green)">{{ $gV }}</div></div>
                    <div class="semaforo-total">{{ $stTotal }}</div>
                </div>
                <div class="semaforo-row">
                    <div class="semaforo-label" style="color:var(--amber)"><span class="sf-dot" style="background:var(--amber)"></span> Amarillo</div>
                    <div class="semaforo-track"><div class="semaforo-fill" style="width:{{ round(($gA/$stTotal)*100) }}%;background:var(--amber)">{{ $gA }}</div></div>
                    <div class="semaforo-total"></div>
                </div>
                <div class="semaforo-row">
                    <div class="semaforo-label" style="color:var(--red)"><span class="sf-dot" style="background:var(--red)"></span> Rojo</div>
                    <div class="semaforo-track"><div class="semaforo-fill" style="width:{{ round(($gR/$stTotal)*100) }}%;background:var(--red)">{{ $gR }}</div></div>
                    <div class="semaforo-total"></div>
                </div>
            </div>
            @if($peiRecientes->count())
            <div class="pei-list">
                @foreach($peiRecientes as $pei)
                <div class="pei-row">
                    <div class="pei-dot" style="background:{{ $pei->semaforo==='verde'?'#059669':($pei->semaforo==='amarillo'?'#d97706':'#dc2626') }}"></div>
                    <div class="pei-body">
                        <div class="pei-name" title="{{ strip_tags($pei->name) }}">{{ \Illuminate\Support\Str::limit(strip_tags($pei->name),45) }}</div>
                        @if($pei->year_start)<div class="pei-year">{{ \Carbon\Carbon::parse($pei->year_start)->format('Y') }}{{ $pei->year_end ? '–'.\Carbon\Carbon::parse($pei->year_end)->format('Y') : '' }}</div>@endif
                    </div>
                    @if($pei->public_token)
                    <a href="{{ route('pei.public.show',$pei->public_token) }}" target="_blank" rel="noopener" class="pei-link"><i class="fa fa-external-link-alt" style="font-size:9px"></i> Ver</a>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        @if($config->show_foda)
        {{-- FODA — nuevo layout con barras y meta --}}
        <div class="panel anim d6">
            <div class="panel-header">
                <div class="panel-title"><i class="fa fa-th-large" style="color:var(--amber)"></i> Análisis FODA</div>
                <span class="panel-badge">{{ $fodaAnalisis }}</span>
            </div>
            <div class="foda-grid">
                <div class="foda-item fi-green"><div class="foda-icon"><i class="fa fa-shield-alt"></i></div><div class="foda-num">{{ $fodaFortalezas }}</div><div class="foda-label">Fortalezas</div></div>
                <div class="foda-item fi-blue"><div class="foda-icon"><i class="fa fa-star"></i></div><div class="foda-num">{{ $fodaOportunidades }}</div><div class="foda-label">Oportunidades</div></div>
                <div class="foda-item fi-red"><div class="foda-icon"><i class="fa fa-exclamation-triangle"></i></div><div class="foda-num">{{ $fodaDebilidades }}</div><div class="foda-label">Debilidades</div></div>
                <div class="foda-item fi-orange"><div class="foda-icon"><i class="fa fa-bolt"></i></div><div class="foda-num">{{ $fodaAmenazas }}</div><div class="foda-label">Amenazas</div></div>
            </div>
            <div class="foda-meta">
                <div class="foda-meta-row"><span class="fm-label">Perfiles FODA</span><span class="fm-value">{{ $fodaPerfiles }}</span></div>
                <div class="foda-meta-row"><span class="fm-label">Aspectos analizados</span><span class="fm-value">{{ $fodaAnalisis }}</span></div>
                <div class="foda-meta-row"><span class="fm-label">Estrategias de cruce</span><span class="fm-value" style="color:var(--violet)">{{ $fodaEstrategias }}</span></div>
            </div>
        </div>
        @endif

    </div>
</div>

@endif

{{-- ═══════════════════════ ESTADÍSTICAS ═══════════════════════ --}}
<div class="tab-panel {{ $tabStatsActive ? 'active' : '' }}" id="panel-estadisticas" role="tabpanel">

    <div class="kpi-strip">
        <div class="kpi-item ki-green anim d1"><div class="kpi-icon"><i class="fa fa-check-double"></i></div><div><div class="kpi-value">{{ $siessAprobados }}</div><div class="kpi-label">Aprobados</div></div></div>
        <div class="kpi-item ki-amber anim d2"><div class="kpi-icon"><i class="fa fa-clock"></i></div><div><div class="kpi-value">{{ $siessPendientes }}</div><div class="kpi-label">Pendientes</div></div></div>
        <div class="kpi-item ki-red anim d3"><div class="kpi-icon"><i class="fa fa-times-circle"></i></div><div><div class="kpi-value">{{ $siessObjetados }}</div><div class="kpi-label">Objetados</div></div></div>
        <div class="kpi-item ki-blue anim d4"><div class="kpi-icon"><i class="fa fa-database"></i></div><div><div class="kpi-value">{{ $siessModulos->count() }}</div><div class="kpi-label">Módulos</div></div></div>
    </div>

    @if($siessModulos->count())
    <div class="siess-grid anim d5">
        @foreach($siessModulos as $mod)
        @php
            $res = $mod->resumenEstados();
            $aprob = ($res['aprobado']??0)+($res['aprobado_silencio']??0);
            $pend = $res['pendiente_validacion']??0;
            $obje = $res['objetado']??0;
            $borr = $res['borrador']??0;
            $topColor = $obje > 0 ? 'var(--red)' : ($pend > 0 ? 'var(--amber)' : 'var(--green)');
        @endphp
        <div class="siess-card sc-{{ $obje > 0 ? 'red' : ($pend > 0 ? 'amber' : 'green') }}">
            <div class="siess-name">{{ $mod->nombre }}</div>
            <div class="siess-code">{{ $mod->codigo }} · {{ ucfirst($mod->periodicidad ?? '') }}</div>
            <div class="siess-pills">
                @if($aprob > 0)<span class="siess-pill" style="background:var(--green-50);color:var(--green)"><i class="fa fa-check" style="font-size:8px"></i> {{ $aprob }}</span>@endif
                @if($pend > 0)<span class="siess-pill" style="background:var(--amber-50);color:var(--amber)"><i class="fa fa-clock" style="font-size:8px"></i> {{ $pend }}</span>@endif
                @if($obje > 0)<span class="siess-pill" style="background:var(--red-50);color:var(--red)"><i class="fa fa-times" style="font-size:8px"></i> {{ $obje }}</span>@endif
                @if($borr > 0)<span class="siess-pill" style="background:var(--bg);color:var(--muted)"><i class="fa fa-file" style="font-size:7px"></i> {{ $borr }}</span>@endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="panel anim d5"><div class="empty"><i class="fa fa-chart-bar"></i><p>Sin módulos activos en este momento</p></div></div>
    @endif

</div>

</main>

{{-- FOOTER — OSCURO ════════════════════════════════════════════════════════════ --}}
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <div class="footer-logo" aria-hidden="true">SP</div>
                    <div class="footer-brand-text">
                        <div class="fb-name">SIPLAN</div>
                        <div class="fb-sub">Sistema de Planificación Estratégica · IPS Paraguay</div>
                    </div>
                </div>
                <p class="footer-desc">Plataforma de monitoreo estratégico institucional para el <strong style="color:rgba(255,255,255,.8)">Instituto de Previsión Social del Paraguay</strong>. Datos en tiempo real.</p>
            </div>
            <div>
                <div class="ai-label">Desarrollado con asistencia de IA</div>
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
        <div class="footer-copy">© {{ date('Y') }} SIPLAN · Instituto de Previsión Social del Paraguay · Todos los derechos reservados</div>
    </div>
</footer>

{{-- MODAL --}}
<div id="modalMatriz" class="modal-bg" role="dialog" aria-modal="true" aria-label="Matriz de Servicios">
    <div class="modal-card">
        <div class="modal-top">
            <div style="position:relative;z-index:1">
                <div style="font-size:10px;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.1em;font-weight:600;margin-bottom:2px"><i class="fa fa-th" style="margin-right:4px"></i>Matriz de Servicios</div>
                <div id="modalMatrizNombre" style="font-size:15px;font-weight:700;color:#fff"></div>
            </div>
            <button class="modal-x" onclick="cerrarModalMatriz()" aria-label="Cerrar">✕</button>
        </div>
        <div id="modalMatrizBody" class="modal-content">
            <div style="text-align:center;padding:3rem;color:var(--muted)"><div class="spinner"></div>Cargando…</div>
        </div>
    </div>
</div>

<script>
(function(){
    'use strict';

    /* ═══ TABS ═══ */
    var tabs = document.querySelectorAll('.tab-btn');
    var panels = document.querySelectorAll('.tab-panel');

    function activate(tab) {
        tabs.forEach(function(t){ t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
        panels.forEach(function(p){ p.classList.remove('active'); });
        tab.classList.add('active');
        tab.setAttribute('aria-selected','true');
        var panel = document.getElementById('panel-' + tab.dataset.dept);
        if (panel) {
            panel.style.animation = 'none';
            panel.offsetHeight;
            panel.style.animation = '';
            panel.classList.add('active');
        }
        try { history.replaceState(null,'',location.pathname+'#'+tab.dataset.dept); } catch(e){}
    }

    tabs.forEach(function(t){ t.addEventListener('click', function(){ activate(this); }); });

    var h = location.hash.replace('#','');
    if (h) { var m = document.querySelector('.tab-btn[data-dept="'+h+'"]'); if (m) activate(m); }

    /* ═══ MODAL ═══ */
    var overlay = document.getElementById('modalMatriz');
    var mBody = document.getElementById('modalMatrizBody');
    var mNombre = document.getElementById('modalMatrizNombre');
    var prevFocus = null;

    function abrir(id, nombre) {
        prevFocus = document.activeElement;
        mNombre.textContent = nombre;
        mBody.innerHTML = '<div style="text-align:center;padding:3rem;color:var(--muted)"><div class="spinner"></div>Cargando matriz…</div>';
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
        fetch('/riiss/evaluaciones/'+id+'/matriz-partial',{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(function(r){return r.text()})
        .then(function(html){
            mBody.innerHTML = html;
            mBody.querySelectorAll('script').forEach(function(s){
                var ns = document.createElement('script');
                ns.textContent = s.textContent;
                s.parentNode.replaceChild(ns,s);
            });
        })
        .catch(function(){
            mBody.innerHTML = '<div class="empty" style="padding:3rem"><i class="fa fa-exclamation-circle" style="color:var(--red);opacity:.4"></i><p style="color:var(--red)">Error al cargar la matriz.</p></div>';
        });
    }

    function cerrar() {
        overlay.classList.remove('open');
        document.body.style.overflow = '';
        if (prevFocus) { prevFocus.focus(); prevFocus = null; }
    }

    document.addEventListener('click', function(e) {
        var b = e.target.closest('.btn-matriz');
        if (b) { e.preventDefault(); abrir(b.dataset.eval, b.dataset.nombre); }
    });
    overlay.addEventListener('click', function(e){ if (e.target === overlay) cerrar(); });
    document.addEventListener('keydown', function(e){ if (e.key==='Escape' && overlay.classList.contains('open')) cerrar(); });
    window.cerrarModalMatriz = cerrar;

    /* ═══ RING ANIMATION ON SCROLL ═══ */
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function(entries){
            entries.forEach(function(en){
                if (en.isIntersecting) {
                    var rings = en.target.querySelectorAll('.ring-fg');
                    rings.forEach(function(ring){
                        var target = ring.getAttribute('stroke-dashoffset');
                        var total = ring.getAttribute('stroke-dasharray');
                        ring.style.strokeDashoffset = total;
                        requestAnimationFrame(function(){
                            ring.style.transition = 'stroke-dashoffset .8s cubic-bezier(.22,1,.36,1)';
                            ring.style.strokeDashoffset = target;
                        });
                    });
                    io.unobserve(en.target);
                }
            });
        }, {threshold: 0.2});
        document.querySelectorAll('.eval-list').forEach(function(el){ io.observe(el); });
    }
})();
</script>
</body>
</html>