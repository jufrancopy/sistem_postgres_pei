<!DOCTYPE html>
<html lang="es">
<head>
@php use Illuminate\Support\Facades\Auth; @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>SIPLAN — Sistema de Planificación Estratégica · IPS Paraguay</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
.btn-resumen{display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:var(--radius-xs);font-size:10px;font-weight:700;background:var(--green-50);color:var(--green);border:1px solid rgba(16,185,129,0.3);cursor:pointer;white-space:nowrap;transition:all .15s}
.btn-resumen:hover{background:var(--green);color:#fff;transform:translateY(-1px);box-shadow:0 2px 6px rgba(16,185,129,0.3)}
.btn-resumen svg{flex-shrink:0}
.eval-name-link{cursor:pointer;transition:color .15s}
.eval-name-link:hover{color:var(--blue);text-decoration:underline}

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
<body>
@php
    $sysLogoUrl  = \App\Models\HomeConfiguration::getSetting('logo_url');
    $sysSiteName = \App\Models\HomeConfiguration::getSetting('site_name', 'SIPLAN');
    $sysEmail    = \App\Models\HomeConfiguration::getSetting('contact_email', 'planificacion@ips.gov.py');
    $sysPhone    = \App\Models\HomeConfiguration::getSetting('contact_phone', '+595 21 219 7000');
    $sysHours    = \App\Models\HomeConfiguration::getSetting('opening_hours', 'Lunes a Viernes de 07:00 a 15:00 hs');
    $sysAddress  = \App\Models\HomeConfiguration::getSetting('address', 'Constitución e/ Herrera y Pettirossi, Asunción - Paraguay');
    $sysFooter   = \App\Models\HomeConfiguration::getSetting('footer_text', '© ' . date('Y') . ' Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.');
@endphp

{{-- NAV --}}
<nav class="topbar" role="navigation" aria-label="Navegación principal">
    <div class="topbar-left">
        <a href="{{ url('/') }}" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:14px">
            @if($sysLogoUrl)
                <img src="{{ $sysLogoUrl }}" alt="SIPLAN GO" style="max-height: 52px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
            @else
                <div class="topbar-logo" aria-hidden="true" style="width:44px;height:44px;font-size:14px;border-radius:12px;background:linear-gradient(135deg,#0f172a,#2563eb);">GO</div>
            @endif
            <div class="topbar-text">
                <div class="topbar-title" style="font-size: 1.15rem; font-weight: 900; letter-spacing: -0.5px;">SIPLAN <span style="color:#2563eb; font-weight:900;">GO</span></div>
                <div class="topbar-sub" style="font-weight: 700; color: #2563eb; font-size: 10.5px; letter-spacing: 0.2px;">Planificar con Propósito</div>
            </div>
        </a>
    </div>
    <div class="topbar-right">
        <a href="#documentacion" onclick="document.querySelector('.tab-btn[data-dept=\'documentacion\']')?.click()" style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:10px; font-size:11.5px; font-weight:700; color:#1e40af; background:#eff6ff; border:1px solid #bfdbfe; text-decoration:none; transition:all 0.2s; box-shadow: 0 2px 6px rgba(37,99,235,0.08);">
            <i class="fa fa-book-open text-primary"></i> <span>Documentación (3 Ejes)</span>
        </a>
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
            <h1 class="anim d1" style="font-family:'Outfit',sans-serif;">SIPLAN <span style="color:#2563eb;">GO</span> · <span>Planificar con Propósito</span></h1>
            <p class="anim d2" style="font-size: 0.98rem; line-height: 1.65; color: #475569;">Monitoreo en tiempo real de la Red de Salud IPS, gestión estratégica e inteligencia de datos para transformar la seguridad social y la atención a nuestros asegurados.</p>
            
            {{-- Flyer Manifiesto & Red Social Laboral Banner --}}
            <div class="anim d3" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; border-left: 5px solid #38bdf8; border-radius: 16px; padding: 18px 20px; margin-top: 20px; box-shadow: 0 12px 28px rgba(15,23,42,0.22); position: relative; overflow: hidden;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; position: relative; z-index: 1;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        @if(!empty($sysLogoUrl))
                            <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px); padding: 8px 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.15); flex-shrink: 0;">
                                <img src="{{ $sysLogoUrl }}" alt="SIPLAN GO Logo" style="max-height: 48px; width: auto; object-fit: contain;">
                            </div>
                        @else
                            <div style="width: 46px; height: 46px; border-radius: 14px; background: linear-gradient(135deg, #2563eb, #7c3aed); color: #fff; display: grid; place-items: center; font-size: 18px; font-weight: 900; flex-shrink: 0; box-shadow: 0 4px 12px rgba(37,99,235,0.4);">
                                GO
                            </div>
                        @endif
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 3px; flex-wrap: wrap;">
                                <strong style="font-size: 0.95rem; color: #fff; line-height: 1.2;">SIPLAN GO — Red Social & Laboral IPS</strong>
                                <span style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-size: 0.68rem; font-weight: 800; padding: 2px 8px; border-radius: 100px; text-transform: uppercase; letter-spacing: 0.5px;">Inteligencia Colectiva</span>
                            </div>
                            <span style="font-size: 0.78rem; color: #cbd5e1; line-height: 1.4; display: block;">Funcionarios del IPS sumando esfuerzos para transformar la seguridad social con tecnología y trabajo mancomunado.</span>
                        </div>
                    </div>
                    <button type="button" onclick="abrirModalFlyerManifiesto()" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border-radius: 20px; border: none; font-size: 0.8rem; font-weight: 800; padding: 8px 20px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 16px rgba(37,99,235,0.4); transition: all 0.2s ease;">
                        <i class="fa fa-users text-warning"></i> <span>Conocer la Red & Manifiesto</span>
                    </button>
                </div>
            </div>
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
    <button class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-documentacion" data-dept="documentacion" style="color:#0284c7;"><i class="fa fa-book-open"></i> Documentación <span class="tab-count" style="background:#e0f2fe; color:#0284c7; font-weight:800;">3 Ejes</span></button>
    <button class="tab-btn" role="tab" aria-selected="false" aria-controls="panel-equipo" data-dept="equipo"><i class="fa fa-users"></i> Equipo de Trabajo <span class="tab-count">{{ isset($topLeaderboard) ? $topLeaderboard->count() : 0 }}</span></button>
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
                <div class="eval-ring btn-resumen-trigger" data-eval="{{ $ev->id }}" data-nombre="{{ $ev->establecimiento?->nombre_oficial ?? 'Ficha Técnica' }}" style="cursor:pointer;" title="Ver Ficha Técnica">
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
                    <div class="eval-name eval-name-link btn-resumen-trigger" data-eval="{{ $ev->id }}" data-nombre="{{ $ev->establecimiento?->nombre_oficial ?? 'Ficha Técnica' }}" title="Ver Ficha Técnica: {{ $ev->establecimiento?->nombre_oficial ?? '' }}">{{ $ev->establecimiento?->nombre_oficial ?? '—' }}</div>
                    <div class="eval-sub">
                        @if($ev->establecimiento?->complejidadTipo?->nombre)<span>{{ $ev->establecimiento?->complejidadTipo?->nombre }}</span>@endif
                        @if($ev->fecha_evaluacion)<span>{{ \Carbon\Carbon::parse($ev->fecha_evaluacion)->format('d/m/Y') }}</span>@endif
                    </div>
                </div>
                <div class="eval-actions">
                    <span class="state-tag {{ $stc }}">{{ ucfirst(str_replace('_',' ',$ev->estado)) }}</span>
                    <button class="btn-resumen btn-resumen-trigger"
                            data-eval="{{ $ev->id }}"
                            data-nombre="{{ $ev->establecimiento?->nombre_oficial ?? 'Ficha Técnica' }}"
                            title="Ver Ficha Técnica y Resumen del Relevamiento">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Ficha
                    </button>
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
            $res = $mod->cached_resumen ?? $mod->resumenEstados();
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

{{-- ═══════════════════════ EQUIPO DE TRABAJO ═══════════════════════ --}}
<div class="tab-panel" id="panel-equipo" role="tabpanel">

    <div class="kpi-strip mb-4">
        <div class="kpi-item ki-amber anim d1">
            <div class="kpi-icon"><i class="fa fa-trophy"></i></div>
            <div><div class="kpi-value">Top 5</div><div class="kpi-label">Líderes Institucionales</div></div>
        </div>
        <div class="kpi-item ki-blue anim d2">
            <div class="kpi-icon"><i class="fa fa-users"></i></div>
            <div><div class="kpi-value">{{ \App\Models\User::count() }}</div><div class="kpi-label">Usuarios Registrados</div></div>
        </div>
        <div class="kpi-item ki-violet anim d3">
            <div class="kpi-icon"><i class="fa fa-award"></i></div>
            <div><div class="kpi-value">{{ isset($topLeaderboard) ? number_format($topLeaderboard->sum('total_points')) : 0 }}</div><div class="kpi-label">Puntos Acumulados</div></div>
        </div>
        <div class="kpi-item ki-green anim d4">
            <div class="kpi-icon"><i class="fa fa-chart-line"></i></div>
            <div><div class="kpi-value">MECIP 2015</div><div class="kpi-label">Gestión Transparente</div></div>
        </div>
    </div>

    <div class="panel bg-white p-4 shadow-sm" style="background:#ffffff; border-radius:16px; border:1px solid #e2e8f0;">
        <div class="panel-header mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center flex-wrap" style="border-bottom:1px solid #e2e8f0;">
            <div>
                <div class="panel-title font-weight-bold text-dark mb-1" style="font-size:1.1rem; color:#0f172a;">
                    <i class="fa fa-trophy text-warning mr-2"></i> Equipo de Trabajo — Colaboradores Destacados
                </div>
                <p class="text-muted small mb-0">
                    Reconocimiento público a las personas que hacen posible el avance estratégico e institucional
                    @if(isset($peiSeleccionado) && $peiSeleccionado)
                    <span class="badge ml-2 font-weight-normal" style="background-color:#e0f7fa; color:#00838f; border:1px solid #b2ebf2; font-size:11px;">
                        📌 {{ strip_tags($peiSeleccionado->name) }}
                    </span>
                    @endif
                </p>
            </div>
            <div class="d-flex align-items-center mt-2 mt-sm-0">
                <a href="#" onclick="abrirGuiaPuntos(); return false;" class="text-info font-weight-bold mr-3" style="font-size: 12px; text-decoration: underline;">
                    ¿Cómo se calculan los puntos?
                </a>
                <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="border-radius:12px; font-size:12px; background-color:#fff8e1; border:1px solid #ffe082;">
                    ⭐ Ranking de Reputación
                </span>
            </div>
        </div>

        @if(isset($topLeaderboard) && $topLeaderboard->count())
        <div class="equipo-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 10px;">
            @foreach($topLeaderboard as $index => $leader)
            @php
                $medal = match($index) {
                    0 => '🥇',
                    1 => '🥈',
                    2 => '🥉',
                    default => '#' . ($index + 1)
                };
                $badgeStyle = match($index) {
                    0 => 'background:#fff8dc; border: 1px solid #ffd700; color: #b8860b;',
                    1 => 'background:#f8fafc; border: 1px solid #cbd5e1; color: #475569;',
                    2 => 'background:#fff5ee; border: 1px solid #cd7f32; color: #a0522d;',
                    default => 'background:#f1f5f9; border: 1px solid #e2e8f0; color: #64748b;'
                };
            @endphp
            <div class="equipo-card-item shadow-sm" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding: 24px 16px 18px; text-align:center; position:relative; transition:transform .2s, box-shadow .2s;">
                <span class="position-absolute font-weight-bold" style="top:12px; left:14px; font-size:18px;">{{ $medal }}</span>
                
                <div class="my-2">
                    <img src="{{ $leader->avatar_url }}" alt="{{ $leader->name }}" class="shadow-sm" style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid #00acc1; display:block; margin: 0 auto;">
                </div>

                <h5 class="font-weight-bold mb-1 text-truncate" style="font-size:13.5px; color:#0f172a; margin-top:8px;" title="{{ $leader->name }}">{{ $leader->name }}</h5>

                @if($leader->group)
                <small class="text-muted d-block text-truncate mb-2" style="font-size:11px; line-height: 1.3;" title="{{ $leader->group->name }}">{{ $leader->group->name }}</small>
                @else
                <small class="text-muted d-block mb-2" style="font-size:11px;">IPS Paraguay</small>
                @endif

                <span class="badge font-weight-bold d-inline-block mb-3 py-1 px-2" style="font-size:10px; background-color:#e0f7fa; color:#00838f; border:1px solid #b2ebf2; border-radius:8px;">
                    {{ $leader->gamification['level_name'] }}
                </span>

                <div class="pt-2 border-top" style="border-top:1px solid #f1f5f9;">
                    <span class="badge badge-pill font-weight-bold py-1 px-3" style="font-size:11px; {{ $badgeStyle }}">
                        ⭐ {{ number_format($leader->total_points) }} pts
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty"><i class="fa fa-users"></i><p>No se encontraron datos de colaboradores</p></div>
        @endif
    </div>
</div>

{{-- ═══════════════════════ DOCUMENTACIÓN (3 DIMENSIONES ESTRATÉGICAS) ═══════════════════════ --}}
<div class="tab-panel" id="panel-documentacion" role="tabpanel">

    <div class="kpi-strip mb-4">
        <div class="kpi-item ki-blue anim d1">
            <div class="kpi-icon" style="background:#e0f2fe; color:#0284c7;"><i class="fa fa-hospital"></i></div>
            <div><div class="kpi-value">9 Caps.</div><div class="kpi-label">Red de Salud & RIISS</div></div>
        </div>
        <div class="kpi-item ki-green anim d2">
            <div class="kpi-icon" style="background:#dcfce7; color:#059669;"><i class="fa fa-bullseye"></i></div>
            <div><div class="kpi-value">6 Caps.</div><div class="kpi-label">Planificación & PEI</div></div>
        </div>
        <div class="kpi-item ki-amber anim d3">
            <div class="kpi-icon" style="background:#fef3c7; color:#d97706;"><i class="fa fa-chart-line"></i></div>
            <div><div class="kpi-value">18 Caps.</div><div class="kpi-label">Bioestadística & SP</div></div>
        </div>
        <div class="kpi-item ki-violet anim d4">
            <div class="kpi-icon" style="background:#f3e8ff; color:#7c3aed;"><i class="fa fa-shield-alt"></i></div>
            <div><div class="kpi-value">33+ Doc.</div><div class="kpi-label">Manuales & Arquitectura</div></div>
        </div>
    </div>

    {{-- Banner Introductorio --}}
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color:#fff; border-radius:18px; padding:24px 28px; margin-bottom:28px; box-shadow:0 10px 30px rgba(15,23,42,0.18); position:relative; overflow:hidden;">
        <div style="position:relative; z-index:2; max-width:850px;">
            <span style="background:rgba(56,189,248,0.18); color:#38bdf8; border:1px solid rgba(56,189,248,0.3); font-size:11px; font-weight:800; padding:4px 12px; border-radius:100px; text-transform:uppercase; letter-spacing:0.5px; display:inline-block; margin-bottom:10px;">
                <i class="fa fa-book-open mr-1"></i> Biblioteca Técnica Oficial SIPLAN
            </span>
            <h3 style="font-size:1.55rem; font-weight:900; color:#fff; margin-bottom:8px; font-family:'Outfit', sans-serif;">
                Documentación Integral de las 3 Dimensiones Estratégicas
            </h3>
            <p style="font-size:0.92rem; color:#cbd5e1; line-height:1.6; margin-bottom:0;">
                Accede a los manuales de arquitectura, diagramas de base de datos PostgreSQL, marco metodológico y guías operativas de los tres pilares del Instituto de Previsión Social: <strong>Red de Salud (RIISS)</strong>, <strong>Planificación & Proyectos (PEI/FODA/Actividades)</strong> y <strong>Bioestadística (Captura SP y Hospitalización)</strong>.
            </p>
        </div>
    </div>

    {{-- Grid de las 3 Dimensiones --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:22px;">

        {{-- ── DIMENSIÓN 1: RED DE SALUD & RIISS ── --}}
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.04); display:flex; flex-direction:column; transition:transform 0.2s, box-shadow 0.2s;" onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 28px rgba(0,0,0,0.08)';" onmouseleave="this.style.transform='none';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.04)';">
            <div style="background:linear-gradient(135deg, #0284c7, #0369a1); color:#fff; padding:20px 22px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; background:rgba(255,255,255,0.2); padding:2px 10px; border-radius:100px;">Eje 1 · Salud</span>
                    <span style="font-size:11px; font-weight:700;"><i class="fa fa-file-lines mr-1"></i> 9 Capítulos</span>
                </div>
                <h4 style="font-size:1.2rem; font-weight:900; margin:0; font-family:'Outfit', sans-serif;">
                    <i class="fa fa-hospital mr-2"></i> Red de Salud & RIISS
                </h4>
                <small style="opacity:0.9; font-size:12px;">Caracterización in situ, equiparación y carteras</small>
            </div>
            <div style="padding:20px; flex-grow:1; display:flex; flex-direction:column; justify-content:space-between;">
                <ul style="list-style:none; padding:0; margin:0 0 18px 0; font-size:12.5px; color:#334155; display:flex; flex-direction:column; gap:8px;">
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>01. Marco Legal & Funcional:</strong> Política Nacional RIISS, 4 niveles y tipología.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>02. Arquitectura de Software:</strong> Controladores, Servicios y Rutas.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>03. Modelo de Datos ER:</strong> Esquema PostgreSQL y llaves foráneas.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>04. 5 Dimensiones Estratégicas:</strong> Drag & Drop con Sortable.js.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>05. Matriz de Equiparación:</strong> Homologación 6 Grados MSPBS ↔ IPS.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>06. Portal Validador & Fármacos:</strong> PIN de seguridad y Vademécum IPS.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>07. Relevamiento & Gap Analysis:</strong> Inspección, fotos y veredictos.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>08. Fichas Públicas con QR:</strong> Actas PDF Base64 y formato impresión.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-info" style="margin-top:2px;"></i>
                        <span><strong>09. Ingestión & Seeders:</strong> PhpSpreadsheet masivo idempotente.</span>
                    </li>
                </ul>
                <button type="button" onclick="abrirVisorDoc('riiss')" style="width:100%; padding:10px; background:#f0f9ff; color:#0284c7; border:1px solid #bae6fd; border-radius:10px; font-weight:800; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all 0.2s;">
                    <i class="fa fa-eye"></i> Explorar Manual de RIISS
                </button>
            </div>
        </div>

        {{-- ── DIMENSIÓN 2: PLANIFICACIÓN & PROYECTOS ── --}}
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.04); display:flex; flex-direction:column; transition:transform 0.2s, box-shadow 0.2s;" onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 28px rgba(0,0,0,0.08)';" onmouseleave="this.style.transform='none';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.04)';">
            <div style="background:linear-gradient(135deg, #059669, #047857); color:#fff; padding:20px 22px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; background:rgba(255,255,255,0.2); padding:2px 10px; border-radius:100px;">Eje 2 · Estrategia</span>
                    <span style="font-size:11px; font-weight:700;"><i class="fa fa-file-lines mr-1"></i> 6 Capítulos</span>
                </div>
                <h4 style="font-size:1.2rem; font-weight:900; margin:0; font-family:'Outfit', sans-serif;">
                    <i class="fa fa-bullseye mr-2"></i> Planificación & Proyectos
                </h4>
                <small style="opacity:0.9; font-size:12px;">Metodología PEI, FODA, PGN, MECIP y Actividades</small>
            </div>
            <div style="padding:20px; flex-grow:1; display:flex; flex-direction:column; justify-content:space-between;">
                <ul style="list-style:none; padding:0; margin:0 0 18px 0; font-size:12.5px; color:#334155; display:flex; flex-direction:column; gap:8px;">
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-success" style="margin-top:2px;"></i>
                        <span><strong>01. Plan Estratégico (PEI):</strong> Cascada Master/Hijos y semáforos.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-success" style="margin-top:2px;"></i>
                        <span><strong>02. Diagnóstico FODA:</strong> Matrices cuantitativas y cruce FO/FA/DO/DA.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-success" style="margin-top:2px;"></i>
                        <span><strong>03. Proyectos & Tareas:</strong> Trazabilidad Kanban, alertas y gamificación.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-success" style="margin-top:2px;"></i>
                        <span><strong>04. Presupuesto PGN:</strong> Alineación de metas físicas y financieras.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-success" style="margin-top:2px;"></i>
                        <span><strong>05. Control Interno MECIP:</strong> Autoevaluación y gestión de riesgos.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-success" style="margin-top:2px;"></i>
                        <span><strong>06. Patrimonio & Estructura:</strong> Bienes y perfiles institucionales.</span>
                    </li>
                </ul>
                <button type="button" onclick="abrirVisorDoc('planificacion')" style="width:100%; padding:10px; background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; border-radius:10px; font-weight:800; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all 0.2s;">
                    <i class="fa fa-eye"></i> Explorar Manual de Planificación
                </button>
            </div>
        </div>

        {{-- ── DIMENSIÓN 3: BIOESTADÍSTICAS & PRODUCCIÓN ── --}}
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.04); display:flex; flex-direction:column; transition:transform 0.2s, box-shadow 0.2s;" onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 28px rgba(0,0,0,0.08)';" onmouseleave="this.style.transform='none';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.04)';">
            <div style="background:linear-gradient(135deg, #d97706, #b45309); color:#fff; padding:20px 22px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; background:rgba(255,255,255,0.2); padding:2px 10px; border-radius:100px;">Eje 3 · Estadísticas</span>
                    <span style="font-size:11px; font-weight:700;"><i class="fa fa-file-lines mr-1"></i> 18 Capítulos</span>
                </div>
                <h4 style="font-size:1.2rem; font-weight:900; margin:0; font-family:'Outfit', sans-serif;">
                    <i class="fa fa-chart-line mr-2"></i> Bioestadísticas & SP
                </h4>
                <small style="opacity:0.9; font-size:12px;">Planillas mensuales SP1-SP14, órganos y hospitalización</small>
            </div>
            <div style="padding:20px; flex-grow:1; display:flex; flex-direction:column; justify-content:space-between;">
                <ul style="list-style:none; padding:0; margin:0 0 18px 0; font-size:12.5px; color:#334155; display:flex; flex-direction:column; gap:8px;">
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-warning" style="margin-top:2px;"></i>
                        <span><strong>01. Modelo Funcional:</strong> Ciclo mensual de captura y consolidación.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-warning" style="margin-top:2px;"></i>
                        <span><strong>03. Diccionario de Variables:</strong> Catálogos clínicos unificados.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-warning" style="margin-top:2px;"></i>
                        <span><strong>06. Importación Planillas SP:</strong> Ingestión de SP1 a SP14.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-warning" style="margin-top:2px;"></i>
                        <span><strong>09. Hospitalización SP10:</strong> Gestión de camas y egresos.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-warning" style="margin-top:2px;"></i>
                        <span><strong>13. Árbol de Órganos:</strong> Cortes del organigrama de la Gerencia.</span>
                    </li>
                    <li style="display:flex; align-items:flex-start; gap:8px;">
                        <i class="fa fa-check-circle text-warning" style="margin-top:2px;"></i>
                        <span><strong>17. Importación Asistida:</strong> Mapeo dinámico y detección de celdas.</span>
                    </li>
                </ul>
                <button type="button" onclick="abrirVisorDoc('bioestadistica')" style="width:100%; padding:10px; background:#fffbeb; color:#d97706; border:1px solid #fde68a; border-radius:10px; font-weight:800; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all 0.2s;">
                    <i class="fa fa-eye"></i> Explorar Manual de Bioestadística
                </button>
            </div>
        </div>

    </div>

</div>

</main>

{{-- FOOTER — OSCURO ════════════════════════════════════════════════════════════ --}}
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-brand" style="display:flex; align-items:center; gap:12px;">
                    @if($sysLogoUrl)
                        <img src="{{ $sysLogoUrl }}" alt="{{ $sysSiteName }}" style="max-height: 48px; object-fit: contain;">
                    @else
                        <div class="footer-logo" aria-hidden="true">SP</div>
                    @endif
                    <div class="footer-brand-text">
                        <div class="fb-name">{{ $sysSiteName }}</div>
                        <div class="fb-sub">Sistema de Planificación Estratégica · IPS Paraguay</div>
                    </div>
                </div>
                <p class="footer-desc" style="margin-top:12px; line-height:1.6;">
                    Plataforma de monitoreo estratégico institucional.<br>
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

{{-- MODAL GUIA DE PUNTOS --}}
<div id="modalGuiaPuntos" class="modal-bg" role="dialog" aria-modal="true" aria-label="Guía de Puntos">
    <div class="modal-card" style="max-width:500px">
        <div class="modal-top" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div style="position:relative;z-index:1">
                <div style="font-size:16px;font-weight:700;color:#fff"><i class="fa fa-info-circle" style="margin-right:6px"></i>Sistema de Puntuación</div>
            </div>
            <button class="modal-x" onclick="cerrarGuiaPuntos()" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-content" style="padding:1.5rem; color:#334155; font-size:14px; line-height:1.6; text-align:left;">
            <p style="margin-bottom:1rem;">El <strong>Ranking de Reputación</strong> reconoce el esfuerzo y compromiso de nuestro equipo. Los puntos se obtienen así:</p>
            
            <h6 style="font-weight:700; color:#0f172a; border-bottom:1px solid #e2e8f0; padding-bottom:5px; margin-bottom:10px;">Módulo RIISS</h6>
            <ul style="padding-left:1.5rem; margin-bottom:1rem; list-style-type:none; margin-left:0; padding-left:0;">
                <li style="margin-bottom:0.5rem;"><strong>🏥 Asignación de Establecimiento <span style="color:#059669; font-weight:bold;">(+50 pts)</span>:</strong> Por recibir la responsabilidad de evaluar en campo.</li>
                <li style="margin-bottom:0;"><strong>✅ Evaluación Terminada <span style="color:#059669; font-weight:bold;">(+100 pts)</span>:</strong> Al completar al 100% el formulario de un establecimiento.</li>
            </ul>

            <h6 style="font-weight:700; color:#0f172a; border-bottom:1px solid #e2e8f0; padding-bottom:5px; margin-bottom:10px;">Módulo PEI (Planificación Estratégica y Operativa)</h6>
            <ul style="padding-left:1.5rem; margin-bottom:0; list-style-type:none; margin-left:0; padding-left:0;">
                <li style="margin-bottom:0.5rem;"><strong>📝 Análisis FODA <span style="color:#059669; font-weight:bold;">(+15 pts)</span>:</strong> Por aportar una fortaleza, debilidad u oportunidad.</li>
                <li style="margin-bottom:0.5rem;"><strong>🎯 Estrategia (Cruce FODA) <span style="color:#059669; font-weight:bold;">(+30 pts)</span>:</strong> Por formular una nueva estrategia de contingencia o ataque.</li>
                <li style="margin-bottom:0.5rem;"><strong>💡 Creación de Tarea <span style="color:#059669; font-weight:bold;">(+15 pts)</span>:</strong> Al cargar una nueva tarea al plan operativo.</li>
                <li style="margin-bottom:0.5rem;"><strong>⚙️ Tareas Completadas <span style="color:#059669; font-weight:bold;">(+25 pts)</span>:</strong> Al finalizar y reportar tareas asignadas en el plan operativo.</li>
                <li style="margin-bottom:0;"><strong>💬 Participación <span style="color:#059669; font-weight:bold;">(+5 pts)</span>:</strong> Al comentar o dar seguimiento a las tareas del equipo.</li>
            </ul>
        </div>
    </div>
</div>

{{-- MODAL MATRIZ --}}
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

{{-- MODAL RESUMEN TÉCNICO / FICHA PÚBLICA --}}
<div id="modalResumenTecnico" class="modal-bg" role="dialog" aria-modal="true" aria-label="Ficha Técnica del Relevamiento">
    <div class="modal-card" style="max-width: 1040px; width: 95vw;">
        <div class="modal-top" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <div style="position:relative;z-index:1">
                <div style="font-size:10px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.1em;font-weight:700;margin-bottom:2px">
                    <i class="fa fa-file-medical-alt mr-1" style="color:#38bdf8;"></i> Resumen Técnico del Relevamiento
                </div>
                <div id="modalResumenNombre" style="font-size:16px;font-weight:800;color:#fff;font-family:'Outfit', sans-serif;"></div>
            </div>
            <button class="modal-x" onclick="cerrarModalResumenTecnico()" aria-label="Cerrar">✕</button>
        </div>
        <div id="modalResumenBody" class="modal-content" style="max-height: 82vh; overflow-y: auto; padding: 24px; background: #f8fafc;">
            <div style="text-align:center;padding:3rem;color:var(--muted)"><div class="spinner"></div>Cargando ficha técnica…</div>
        </div>
    </div>
</div>

<script>
(function(){
    'use strict';

    /* ═══ MODAL GUIA PUNTOS ═══ */
    var modalGuia = document.getElementById('modalGuiaPuntos');
    window.abrirGuiaPuntos = function() {
        modalGuia.classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.cerrarGuiaPuntos = function() {
        modalGuia.classList.remove('open');
        document.body.style.overflow = '';
    };
    if (modalGuia) {
        modalGuia.addEventListener('click', function(e){ if (e.target === modalGuia) window.cerrarGuiaPuntos(); });
    }

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

    /* ═══ MODAL RESUMEN TÉCNICO ═══ */
    var overlayResumen = document.getElementById('modalResumenTecnico');
    var mResumenBody = document.getElementById('modalResumenBody');
    var mResumenNombre = document.getElementById('modalResumenNombre');

    function abrirResumen(id, nombre) {
        prevFocus = document.activeElement;
        mResumenNombre.textContent = nombre;
        mResumenBody.innerHTML = '<div style="text-align:center;padding:3rem;color:var(--muted)"><div class="spinner"></div>Cargando ficha técnica…</div>';
        overlayResumen.classList.add('open');
        document.body.style.overflow = 'hidden';
        fetch('/riiss/evaluaciones/'+id+'/resumen-partial', {headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(function(r){return r.text()})
        .then(function(html){
            mResumenBody.innerHTML = html;
            mResumenBody.querySelectorAll('script').forEach(function(s){
                var ns = document.createElement('script');
                ns.textContent = s.textContent;
                s.parentNode.replaceChild(ns,s);
            });
        })
        .catch(function(){
            mResumenBody.innerHTML = '<div class="empty" style="padding:3rem"><i class="fa fa-exclamation-circle" style="color:var(--red);opacity:.4"></i><p style="color:var(--red)">Error al cargar el resumen técnico.</p></div>';
        });
    }

    function cerrarResumen() {
        overlayResumen.classList.remove('open');
        document.body.style.overflow = '';
        if (prevFocus) { prevFocus.focus(); prevFocus = null; }
    }

    document.addEventListener('click', function(e) {
        var b = e.target.closest('.btn-resumen-trigger');
        if (b) { e.preventDefault(); abrirResumen(b.dataset.eval, b.dataset.nombre); }
    });

    if (overlayResumen) {
        overlayResumen.addEventListener('click', function(e){ if (e.target === overlayResumen) cerrarResumen(); });
    }
    document.addEventListener('keydown', function(e){ if (e.key==='Escape' && overlayResumen && overlayResumen.classList.contains('open')) cerrarResumen(); });
    window.cerrarModalResumenTecnico = cerrarResumen;

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

function abrirModalFlyerManifiesto() {
    var modal = document.getElementById('modalFlyerManifiesto');
    if (modal) {
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
}
function cerrarModalFlyerManifiesto() {
    var modal = document.getElementById('modalFlyerManifiesto');
    if (modal) {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }
}

document.addEventListener('click', function(e) {
    var m = document.getElementById('modalFlyerManifiesto');
    if (m && e.target === m) cerrarModalFlyerManifiesto();
});

window.verFotoLightbox = function(url, desc, fecha, titulo) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: titulo || 'Evidencia Fotográfica',
            text: desc || '',
            imageUrl: url,
            imageAlt: 'Foto Relevamiento',
            imageWidth: '100%',
            imageHeight: 'auto',
            showCloseButton: true,
            showConfirmButton: false,
            background: '#0f172a',
            color: '#fff'
        });
    } else {
        window.open(url, '_blank');
    }
};

window.copiarUrlFicha = function(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Enlace copiado al portapapeles 📋',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                alert('Enlace copiado: ' + url);
            }
        });
    } else {
        prompt('Copie el enlace:', url);
    }
};

window.abrirVisorDoc = function(tipo) {
    var titulos = {
        'riiss': 'Manual Técnico y Metodológico — Red de Salud & RIISS',
        'planificacion': 'Manual Técnico y Metodológico — Planificación & Proyectos',
        'bioestadistica': 'Manual Técnico y Metodológico — Bioestadística & Producción'
    };
    var colores = {
        'riiss': '#0284c7',
        'planificacion': '#059669',
        'bioestadistica': '#d97706'
    };
    var descripciones = {
        'riiss': `
            <div style="line-height:1.7; color:#334155; font-size:13.5px;">
                <h5 style="color:#0284c7; font-weight:900; margin-bottom:12px;">🏥 Eje 1: Redes Integradas e Integrales de Servicios de Salud (RIISS)</h5>
                <p>El módulo <strong>RIISS</strong> del Instituto de Previsión Social tiene como misión estandarizar y auditar la capacidad instalada real de los más de 138 establecimientos sanitarios de la red mediante relevamientos in situ.</p>
                <div style="background:#f0f9ff; border-left:4px solid #0284c7; padding:14px; border-radius:8px; margin:16px 0;">
                    <strong>Estructura en 5 Dimensiones:</strong><br>
                    • 🩺 <strong>Cartera de Servicios:</strong> Más de 2.742 prestaciones clínicas y quirúrgicas.<br>
                    • 🏢 <strong>Infraestructura:</strong> Normas de bioseguridad, rampas y quirófanos.<br>
                    • 👥 <strong>Talento Humano:</strong> Médicos, enfermeros, regencias y guardias 24/7.<br>
                    • 💊 <strong>Medicamentos e Insumos:</strong> Cruce directo con el Vademécum Oficial IPS 2026.<br>
                    • 📋 <strong>Gobernanza:</strong> Habilitación MSPBS, manuales y organigrama.
                </div>
                <p><strong>Marco de Equiparación:</strong> Homologa los 6 Grados normativos del MSPBS con la estructura del IPS, exigiendo criterios estrictos de hospitalización, internación, quirófanos, UTI y urgencias.</p>
                <div style="text-align:right; margin-top:20px;">
                    <a href="/docs/riiss/README.md" target="_blank" class="btn btn-sm btn-primary" style="border-radius:8px; font-weight:700;">
                        <i class="fa fa-external-link-alt mr-1"></i> Abrir Repositorio de Documentación Completa
                    </a>
                </div>
            </div>
        `,
        'planificacion': `
            <div style="line-height:1.7; color:#334155; font-size:13.5px;">
                <h5 style="color:#059669; font-weight:900; margin-bottom:12px;">🎯 Eje 2: Planificación Estratégica & Gestión de Proyectos (PEI)</h5>
                <p>El núcleo de <strong>Planificación Estratégica</strong> permite formular y controlar el Plan Estratégico Institucional (PEI 2024-2028), conectando la visión de la alta gerencia con las metas físicas de cada dirección.</p>
                <div style="background:#ecfdf5; border-left:4px solid #059669; padding:14px; border-radius:8px; margin:16px 0;">
                    <strong>Herramientas Integradas:</strong><br>
                    • 🎯 <strong>Cascada PEI:</strong> Planes Maestros Corporativos y descentralizados por dependencias.<br>
                    • 📊 <strong>Diagnóstico FODA:</strong> Evaluación cuantitativa, ponderaciones y cruce de impacto.<br>
                    • 📋 <strong>Gestión de Tareas:</strong> Tableros Kanban de actividades, alertas y gamificación SIPLAN GO.<br>
                    • 💰 <strong>Alineación PGN:</strong> Control del Presupuesto General de la Nación.<br>
                    • ⚖️ <strong>Marco MECIP:</strong> Cumplimiento de la norma de control interno.
                </div>
                <div style="text-align:right; margin-top:20px;">
                    <a href="/docs/planificacion-proyectos/README.md" target="_blank" class="btn btn-sm btn-success" style="border-radius:8px; font-weight:700;">
                        <i class="fa fa-external-link-alt mr-1"></i> Abrir Repositorio de Planificación
                    </a>
                </div>
            </div>
        `,
        'bioestadistica': `
            <div style="line-height:1.7; color:#334155; font-size:13.5px;">
                <h5 style="color:#d97706; font-weight:900; margin-bottom:12px;">📊 Eje 3: Bioestadísticas & Producción Asistencial en Salud</h5>
                <p>El subsistema de <strong>Bioestadística</strong> procesa mensualmente la producción médica, consultas, urgencias y cirugías de toda la red asistencial del IPS.</p>
                <div style="background:#fffbeb; border-left:4px solid #d97706; padding:14px; border-radius:8px; margin:16px 0;">
                    <strong>Capacidades Principales:</strong><br>
                    • 📈 <strong>Planillas SP1 a SP14:</strong> Captura y validación asistida de hojas de cálculo mensuales.<br>
                    • 🛏️ <strong>Hospitalización SP10:</strong> Registro de ingresos, días de estada, camas y altas.<br>
                    • 🏛️ <strong>Árbol de Órganos:</strong> Cortes jerárquicos del organigrama de la Gerencia de Salud.<br>
                    • 📖 <strong>Diccionarios Clínicos:</strong> Normalización de especialidades y procedimientos.
                </div>
                <div style="text-align:right; margin-top:20px;">
                    <a href="/docs/bioestadistica/README.md" target="_blank" class="btn btn-sm btn-warning font-weight-bold text-dark" style="border-radius:8px; font-weight:800;">
                        <i class="fa fa-external-link-alt mr-1"></i> Abrir Repositorio de Bioestadística
                    </a>
                </div>
            </div>
        `
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: titulos[tipo] || 'Documentación Técnica',
            html: descripciones[tipo] || '',
            width: '780px',
            showCloseButton: true,
            showConfirmButton: false,
            focusConfirm: false,
            customClass: {
                popup: 'rounded-24 shadow-lg'
            }
        });
    } else {
        alert(titulos[tipo]);
    }
};
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- MODAL FLYER MANIFIESTO SIPLAN --}}
<div class="modal-bg" id="modalFlyerManifiesto">
    <div class="modal-card" style="max-width: 860px; border-radius: 26px; overflow: hidden; box-shadow: 0 25px 65px rgba(0,0,0,0.4);">
        <div class="modal-top" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 22px 30px;">
            <div style="display: flex; align-items: center; gap: 16px;">
                @if(!empty($sysLogoUrl))
                    <img src="{{ $sysLogoUrl }}" alt="SIPLAN GO Logo" style="max-height: 52px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));">
                @else
                    <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #2563eb, #7c3aed); color: #fff; display: grid; place-items: center; font-size: 20px; font-weight: 900; box-shadow: 0 4px 14px rgba(37,99,235,0.4);">
                        GO
                    </div>
                @endif
                <div>
                    <h5 style="font-size: 1.25rem; font-weight: 900; color: #fff; margin: 0; font-family: 'Outfit', sans-serif; letter-spacing: -0.3px;">
                        SIPLAN <span style="color:#60a5fa;">GO</span> — Red Laboral & Colaborativa
                    </h5>
                    <small style="color: #38bdf8; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.3px;">Planificar con Propósito · Inteligencia Colectiva IPS</small>
                </div>
            </div>
            <button type="button" class="modal-x" onclick="cerrarModalFlyerManifiesto()">&times;</button>
        </div>
        <div class="modal-content" style="padding: 30px; background: #f8fafc; max-height: 75vh; overflow-y: auto;">
            
            {{-- QUOTE BANNER --}}
            <div style="background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border-left: 6px solid #2563eb; padding: 24px; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); margin-bottom: 26px;">
                <p style="font-size: 1.08rem; font-weight: 600; color: #0f172a; font-style: italic; line-height: 1.65; margin-bottom: 14px;">
                    “No nacimos en escritorios distantes ni en burocracia aislada. Somos funcionarios del IPS que sumamos nuestros esfuerzos para transformar la seguridad social de nuestro país. SIPLAN GO es una Red Social y Laboral donde profesionales, analistas, médicos y coordinadores nos conectamos para aportar nuestro talento, colaborar en tiempo real y cambiar la vida de nuestros asegurados con tecnología y trabajo mancomunado.”
                </p>
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <small style="color: #64748b; font-weight: 800;">— Equipo Técnico de Planificación & Desarrollo · IPS Paraguay</small>
                    <span class="badge badge-info" style="background: #e0f2fe; color: #0369a1; font-weight: 800; padding: 4px 10px; border-radius: 12px; font-size: 0.72rem;">
                        <i class="fa fa-users mr-1"></i> Red de Inteligencia Colectiva
                    </span>
                </div>
            </div>

            <h6 style="font-weight: 900; color: #0f172a; margin-bottom: 14px; font-size: 0.98rem; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Outfit', sans-serif;">
                <i class="fa fa-share-alt text-primary mr-1"></i> Una Red de Trabajo Renovada y Participativa
            </h6>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 28px;">
                <div style="background: #fff; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 3px 10px rgba(0,0,0,0.03);">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: grid; place-items: center; font-size: 16px; margin-bottom: 10px;">
                        <i class="fa fa-comments"></i>
                    </div>
                    <strong style="color: #0f172a; font-size: 0.95rem; display: block; margin-bottom: 6px;">Colaboración en Tiempo Real</strong>
                    <span style="font-size: 0.8rem; color: #64748b; line-height: 1.55;">Chat contextual, debates técnicos por objetivo y canal directo de asesoría libre de jerarquías rígidas.</span>
                </div>

                <div style="background: #fff; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 3px 10px rgba(0,0,0,0.03);">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #f5f3ff; color: #7c3aed; display: grid; place-items: center; font-size: 16px; margin-bottom: 10px;">
                        <i class="fa fa-trophy"></i>
                    </div>
                    <strong style="color: #0f172a; font-size: 0.95rem; display: block; margin-bottom: 6px;">Reconocimiento al Talento Humano</strong>
                    <span style="font-size: 0.8rem; color: #64748b; line-height: 1.55;">Sistema de gamificación con puntos, medallas de mérito y ranking público para destacar el verdadero esfuerzo.</span>
                </div>

                <div style="background: #fff; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 3px 10px rgba(0,0,0,0.03);">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #ecfdf5; color: #10b981; display: grid; place-items: center; font-size: 16px; margin-bottom: 10px;">
                        <i class="fa fa-hospital-user"></i>
                    </div>
                    <strong style="color: #0f172a; font-size: 0.95rem; display: block; margin-bottom: 6px;">Impacto Directo al Asegurado</strong>
                    <span style="font-size: 0.8rem; color: #64748b; line-height: 1.55;">Medicamentos a tiempo, reducción de esperas para cirugías y trato digno a jubilados y familias.</span>
                </div>
            </div>

            <div style="text-align: center; border-top: 1px solid #e2e8f0; padding-top: 22px; margin-top: 10px;">
                <a href="{{ route('siplan.manifesto') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; font-weight: 800; padding: 12px 28px; border-radius: 100px; text-decoration: none; font-size: 0.88rem; box-shadow: 0 6px 20px rgba(15,23,42,0.3); transition: all 0.25s ease;">
                    <i class="fa fa-book-open text-warning"></i> Leer la Declaración Completa de la Red en /nosotros
                </a>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.ticket_modal')

</body>
</html>