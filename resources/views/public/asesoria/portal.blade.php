<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Portal de Asesoría y Validación — {{ $profile->name }}</title>
<link rel="icon" type="image/png" href="{{ asset('material/img/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f8fafc;--surface:#ffffff;--border:#e2e8f0;
  --blue:#2563eb;--blue-50:#eff6ff;--blue-100:#dbeafe;--blue-700:#1d4ed8;
  --green:#10b981;--green-50:#ecfdf5;--green-100:#d1fae5;--green-700:#047857;
  --purple:#7c3aed;--purple-50:#f5f3ff;
  --amber:#f59e0b;--amber-50:#fffbeb;--amber-100:#fef3c7;--amber-700:#b45309;
  --text:#0f172a;--text-secondary:#475569;--muted:#94a3b8;
  --radius:16px;--radius-sm:12px;--radius-xs:8px;
  --shadow-sm:0 1px 2px rgba(0,0,0,.04);
  --shadow-md:0 4px 20px rgba(0,0,0,.06);
  --shadow-lg:0 12px 40px rgba(0,0,0,.1);
}
html{scroll-behavior:smooth}
body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.6;min-height:100vh}

/* Topbar */
.topbar{position:sticky;top:0;z-index:300;background:rgba(255,255,255,.95);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);height:60px;display:flex;align-items:center;padding:0 clamp(16px,4vw,40px);gap:12px}
.topbar-logo{width:36px;height:36px;border-radius:var(--radius-xs);background:linear-gradient(135deg,#0f172a,#1e293b);display:grid;place-items:center;color:#fff;font-weight:900;font-size:14px;flex-shrink:0}
.topbar-title{font-size:13px;font-weight:700;color:var(--text)}
.topbar-sub{font-size:11px;color:var(--muted)}
.user-chip{margin-left:auto;display:flex;align-items:center;gap:10px}
.user-badge{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;padding:5px 14px;border-radius:100px;background:var(--amber-50);color:var(--amber-700);border:1px solid var(--amber-100)}
.btn-logout{font-size:12px;font-weight:600;color:var(--text-secondary);text-decoration:none;padding:6px 12px;border-radius:var(--radius-xs);border:1px solid var(--border);transition:all .15s}
.btn-logout:hover{background:#f1f5f9;color:var(--text)}

/* Hero */
.hero{background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;padding:clamp(24px,4vw,40px) clamp(16px,4vw,40px);position:relative;overflow:hidden}
.hero-badge{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--amber);background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.3);padding:4px 14px;border-radius:100px;margin-bottom:12px}
.hero h1{font-size:clamp(1.3rem,3vw,1.8rem);font-weight:900;color:#fff;line-height:1.25;margin-bottom:8px}
.hero p{font-size:13px;color:#cbd5e1;max-width:720px;line-height:1.6}

/* Metrics Bar */
.metrics-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.1)}
.metric-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:var(--radius-xs);padding:8px 12px;text-align:center}
.metric-num{font-size:18px;font-weight:800;color:var(--amber)}
.metric-lbl{font-size:11px;color:#94a3b8}

/* Page Container */
.page-wrap{max-width:1100px;margin:0 auto;padding:clamp(16px,3vw,32px) clamp(12px,4vw,28px)}

/* Section Card */
.card-box{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);margin-bottom:24px;overflow:hidden}
.card-box-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;background:#fff;flex-wrap:wrap}
.card-box-title{font-size:15px;font-weight:800;color:var(--text);display:flex;align-items:center;gap:8px}
.card-box-body{padding:20px}

/* Accordion Tree Styling (Responsive Stacked Header) */
.tree-node{border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:12px;background:#fff;overflow:hidden;transition:all .2s ease}
.tree-node-header{padding:12px 16px;display:flex;flex-direction:column;gap:6px;cursor:pointer;background:#fff;user-select:none;transition:background .15s ease}
.tree-node-header:hover{background:#f8fafc}
.tree-node-header-top{display:flex;align-items:center;justify-content:space-between;width:100%;gap:8px;flex-wrap:wrap}
.tree-node-title-text{font-size:13.5px;font-weight:700;color:var(--text);line-height:1.45;word-break:break-word;padding-left:26px}
.tree-node-body{display:none;padding:16px;background:#f8fafc;border-top:1px solid var(--border)}

/* Badges */
.badge-node{font-size:10px;font-weight:700;padding:3px 10px;border-radius:100px;text-transform:uppercase;letter-spacing:.3px;flex-shrink:0}
.badge-eje{background:var(--blue-50);color:var(--blue);border:1px solid var(--blue-100)}
.badge-obj{background:#e0f2fe;color:#0284c7;border:1px solid #bae6fd}
.badge-acc{background:var(--purple-50);color:var(--purple);border:1px solid #ddd6fe}
.badge-ini{background:var(--green-50);color:var(--green-700);border:1px solid #a7f3d0}

.status-badge{font-size:11px;font-weight:600;padding:3px 10px;border-radius:100px;display:inline-flex;align-items:center;gap:4px;flex-shrink:0}
.status-has{background:var(--green-50);color:var(--green-700);border:1px solid #a7f3d0}
.status-no{background:#f1f5f9;color:var(--muted);border:1px solid var(--border)}

/* Indicador Modal Trigger Button */
.btn-ver-ind{display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#e0f2fe,#dbeafe);color:#0369a1;border:1px solid #bae6fd;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:700;cursor:pointer;transition:all .15s ease}
.btn-ver-ind:hover{background:#0284c7;color:#fff;border-color:#0284c7;transform:scale(1.02)}

/* Comment Box */
.comment-box{background:#fff;border:1px solid var(--border);border-radius:var(--radius-xs);padding:14px;margin-bottom:12px;box-shadow:var(--shadow-sm)}
.comment-box label{display:block;font-size:11px;font-weight:700;color:var(--text-secondary);margin-bottom:6px}
.comment-box textarea{width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:var(--radius-xs);font-family:inherit;font-size:12.5px;color:var(--text);outline:none;transition:all .15s ease;resize:vertical;min-height:70px}
.comment-box textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.comment-box-footer{display:flex;justify-content:flex-end;margin-top:8px}

.btn-save-comment{display:inline-flex;align-items:center;gap:6px;padding:6px 16px;border-radius:100px;font-size:11px;font-weight:700;background:var(--blue);color:#fff;border:none;cursor:pointer;transition:all .15s}
.btn-save-comment:hover{background:var(--blue-700);transform:translateY(-1px)}

/* Chevron Animation */
.chevron-icon{transition:transform .2s ease;font-size:11px;color:var(--muted);width:18px;height:18px;display:grid;place-items:center}
.tree-node-header.active .chevron-icon{transform:rotate(90deg);color:var(--blue)}

/* Buttons Bar */
.toolbar-bar{display:flex;gap:8px;margin-bottom:0;flex-wrap:wrap}
.btn-tool{padding:6px 14px;border-radius:100px;font-size:12px;font-weight:600;background:#fff;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;transition:all .15s}
.btn-tool:hover{background:#f1f5f9;color:var(--text)}

/* Toast Notification */
.toast-msg{position:fixed;bottom:24px;right:24px;z-index:999;background:#0f172a;color:#fff;padding:12px 20px;border-radius:var(--radius-xs);font-size:13px;font-weight:600;box-shadow:var(--shadow-lg);display:none;align-items:center;gap:8px}

/* Modal Ficha Técnica Indicador */
.modal-bg-ind{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,.65);backdrop-filter:blur(6px);z-index:999;display:none;align-items:center;justify-content:center;padding:16px}
.modal-card-ind{background:#fff;border-radius:20px;max-width:720px;width:100%;max-height:85vh;overflow:hidden;box-shadow:var(--shadow-lg);display:flex;flex-direction:column}
.modal-header-ind{background:linear-gradient(135deg,#0f172a,#1e293b);padding:18px 24px;color:#fff;display:flex;align-items:center;justify-content:space-between}
.modal-x-ind{background:none;border:none;color:#94a3b8;font-size:24px;cursor:pointer;line-height:1}
.modal-x-ind:hover{color:#fff}
.modal-body-ind{padding:24px;overflow-y:auto;background:#f8fafc}

.ind-grid-4{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:10px;margin-bottom:18px}
.ind-info-box{background:#fff;border:1px solid var(--border);border-radius:var(--radius-xs);padding:10px 12px}
.ind-info-box small{display:block;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase}
.ind-info-box strong{font-size:12.5px;color:var(--text);display:block;margin-top:2px}

.ind-section{background:#fff;border:1px solid var(--border);border-radius:var(--radius-xs);padding:14px;margin-bottom:12px}
.ind-section label{display:block;font-size:11px;font-weight:800;color:var(--text-secondary);margin-bottom:6px;text-transform:uppercase;letter-spacing:.3px}
.ind-val-box{font-size:12.5px;color:var(--text);line-height:1.5}
.ind-metas-grid{display:flex;flex-wrap:wrap;gap:8px}
.ind-meta-pill{background:var(--blue-50);border:1px solid var(--blue-100);color:var(--blue-700);padding:4px 12px;border-radius:8px;font-size:12px;font-weight:700}

.page-footer{text-align:center;padding:32px 16px;font-size:12px;color:var(--muted)}
</style>
</head>
<body>

@php
  $sysLogoRaw  = \App\Models\HomeConfiguration::getSetting('logo_url');
  $sysSiteName = \App\Models\HomeConfiguration::getSetting('site_name', 'Sistema de Planificación Institucional');
  if (!empty($sysLogoRaw)) {
      $sysLogoUrl = (str_starts_with($sysLogoRaw, 'http://') || str_starts_with($sysLogoRaw, 'https://')) ? $sysLogoRaw : url($sysLogoRaw);
  } else {
      $sysLogoUrl = asset('material/img/new_logo.png');
  }

  // Helper para desinfectar y limpiar entidades HTML (&nbsp;) y etiquetas
  $cleanName = function($name) {
      if (empty($name)) return '';
      $decoded = html_entity_decode(strip_tags($name), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      return trim(preg_replace('/\s+/', ' ', $decoded));
  };
@endphp

{{-- TOPBAR --}}
<div class="topbar">
  @if(!empty($sysLogoUrl))
    <img src="{{ $sysLogoUrl }}" alt="{{ $sysSiteName }}" style="max-height: 38px; max-width: 160px; object-fit: contain; flex-shrink:0;">
  @else
    <div class="topbar-logo"><i class="fa fa-user-check"></i></div>
  @endif
  <div>
    <div class="topbar-title">{{ $sysSiteName }}</div>
    <div class="topbar-sub">Portal de Validación Técnica por Asesor</div>
  </div>

  <div class="user-chip">
    <div class="user-badge">
      <i class="fa fa-user-circle"></i>
      <span>{{ $asesoria->nombre }}</span>
    </div>
    <a href="{{ route('asesoria.public.logout') }}" class="btn-logout" title="Cerrar sesión de asesoría">
      <i class="fa fa-sign-out-alt"></i> Salir
    </a>
  </div>
</div>

{{-- HERO --}}
<div class="hero">
  <div class="hero-badge"><i class="fa fa-clipboard-check"></i> Asesoría Externa &amp; Validación de Plan</div>
  <h1>{{ $cleanName($profile->name) }}</h1>
  <p>Explorá la jerarquía del plan haciendo clic en los elementos para desplegar sus componentes. Redactá tus sugerencias de mejora técnicas en cada campo.</p>

  <div class="metrics-row">
    <div class="metric-card">
      <div class="metric-num">{{ $descendants->where('level', 'axi')->count() }}</div>
      <div class="metric-lbl">Objetivos Estratégicos</div>
    </div>
    <div class="metric-card">
      <div class="metric-num">{{ $descendants->whereIn('level', ['goal', 'objetivo_especifico'])->count() }}</div>
      <div class="metric-lbl">Objetivos Específicos</div>
    </div>
    <div class="metric-card">
      <div class="metric-num">{{ $descendants->where('level', 'action')->count() }}</div>
      <div class="metric-lbl">Acciones Estratégicas</div>
    </div>
    <div class="metric-card">
      <div class="metric-num">{{ $iniciativas->count() }}</div>
      <div class="metric-lbl">Acciones Operativas (Mejora)</div>
    </div>
    <div class="metric-card">
      <div class="metric-num" id="cntComentariosRegistrados">{{ count($comentariosMap) }}</div>
      <div class="metric-lbl">Sugerencias Guardadas</div>
    </div>
  </div>
</div>

{{-- MAIN CONTENT --}}
<div class="page-wrap">

  {{-- DICTAMEN GENERAL DEL PLAN --}}
  <div class="card-box" style="border-left: 5px solid var(--amber);">
    <div class="card-box-header">
      <div class="card-box-title">
        <i class="fa fa-file-signature text-amber" style="color: var(--amber);"></i>
        <span>Dictamen u Observaciones Generales del Asesor</span>
      </div>
      <span class="badge-node" style="background: var(--amber-50); color: var(--amber-700); border: 1px solid var(--amber-100);">Síntesis Macro</span>
    </div>
    <div class="card-box-body">
      <p style="font-size: 12px; color: var(--text-secondary); margin-bottom: 12px;">
        Redactá aquí una conclusión macro sobre la factibilidad, alineamiento técnico y coherencia estratégica global del plan.
      </p>
      <div class="comment-box" style="margin-bottom: 16px;">
        <textarea id="areaDictamenGeneral" rows="3" placeholder="Ingresá tus observaciones y conclusiones generales sobre el plan...">{{ $asesoria->dictamen_general }}</textarea>
      </div>
      <div style="display: flex; justify-content: flex-end;">
        <button type="button" class="btn-save-comment" id="btnFinalizarDictamen" style="background: linear-gradient(135deg,#0f172a 0%,#1e293b 100%); padding: 10px 24px; font-size: 12px;">
          <i class="fa fa-check-circle"></i>
          <span>Finalizar Dictamen de Asesoría</span>
        </button>
      </div>
    </div>
  </div>

  {{-- ESTRUCTURA COMPLETA DEL PEI (CERRADA POR DEFECTO) --}}
  <div class="card-box">
    <div class="card-box-header">
      <div class="card-box-title">
        <i class="fa fa-sitemap" style="color: var(--blue);"></i>
        <span>Estructura del Plan Estratégico — Sugerencias por Elemento</span>
      </div>
      <div class="toolbar-bar">
        <button type="button" class="btn-tool" id="btnExpandirTodo"><i class="fa fa-expand-alt mr-1"></i> Expandir Todo</button>
        <button type="button" class="btn-tool" id="btnColapsarTodo"><i class="fa fa-compress-alt mr-1"></i> Colapsar Todo</button>
      </div>
    </div>

    <div class="card-box-body" style="background: #f8fafc;">
      <div id="contenedorArbolPei">
        @foreach($treeNodes as $axi)
          @php
            $hasCommentAxi = isset($comentariosMap[$axi->id]) && !empty($comentariosMap[$axi->id]);
            $indAxi = $axi->indicador;
          @endphp

          {{-- NIVEL 1: OBJETIVO ESTRATÉGICO --}}
          <div class="tree-node" style="border-left: 4px solid var(--blue);">
            <div class="tree-node-header" onclick="toggleNode(this)">
              <div class="tree-node-header-top">
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                  <div class="chevron-icon"><i class="fa fa-chevron-right"></i></div>
                  <span class="badge-node badge-eje">OBJETIVO ESTRATÉGICO</span>
                  @if($indAxi)
                    <button type="button" class="btn-ver-ind" onclick="event.stopPropagation(); abrirModalIndicadorAsesor({{ json_encode($indAxi) }});">
                      <i class="fa fa-ruler-combined"></i> Indicador: {{ $indAxi->codigoCompleto() }}
                    </button>
                  @endif
                </div>
                <span class="status-badge {{ $hasCommentAxi ? 'status-has' : 'status-no' }}" id="status_badge_{{ $axi->id }}">
                  <i class="fa {{ $hasCommentAxi ? 'fa-check-circle' : 'fa-circle' }}"></i>
                  <span>{{ $hasCommentAxi ? 'Sugerencia Registrada' : 'Sin comentarios' }}</span>
                </span>
              </div>
              <div class="tree-node-title-text">
                {{ $cleanName($axi->name) }}
              </div>
            </div>

            <div class="tree-node-body">
              {{-- Comentario del Objetivo Estratégico --}}
              <div class="comment-box">
                <label><i class="fa fa-pencil-alt text-amber" style="color:var(--amber);"></i> Sugerencia técnica sobre este Objetivo Estratégico:</label>
                <textarea class="area-comentario" data-node-id="{{ $axi->id }}" data-node-type="node" placeholder="Escribí aquí tus observaciones o sugerencias de mejora...">{{ $comentariosMap[$axi->id] ?? '' }}</textarea>
                <div class="comment-box-footer">
                  <button type="button" class="btn-save-comment" onclick="guardarComentarioNodo('{{ $axi->id }}', 'node', this)">
                    <i class="fa fa-save"></i> Guardar Sugerencia
                  </button>
                </div>
              </div>

              {{-- OBJETIVOS ESPECÍFICOS --}}
              @if($axi->children->count() > 0)
                <div style="padding-left: 8px; margin-top: 12px; border-left: 2px solid var(--border);">
                  @foreach($axi->children as $goal)
                    @php
                      $hasCommentGoal = isset($comentariosMap[$goal->id]) && !empty($comentariosMap[$goal->id]);
                      $indGoal = $goal->indicador;
                    @endphp

                    <div class="tree-node" style="border-left: 4px solid #0284c7;">
                      <div class="tree-node-header" onclick="toggleNode(this)">
                        <div class="tree-node-header-top">
                          <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            <div class="chevron-icon"><i class="fa fa-chevron-right"></i></div>
                            <span class="badge-node badge-obj">OBJ. ESPECÍFICO</span>
                            @if($indGoal)
                              <button type="button" class="btn-ver-ind" onclick="event.stopPropagation(); abrirModalIndicadorAsesor({{ json_encode($indGoal) }});">
                                <i class="fa fa-ruler-combined"></i> Indicador: {{ $indGoal->codigoCompleto() }}
                              </button>
                            @endif
                          </div>
                          <span class="status-badge {{ $hasCommentGoal ? 'status-has' : 'status-no' }}" id="status_badge_{{ $goal->id }}">
                            <i class="fa {{ $hasCommentGoal ? 'fa-check-circle' : 'fa-circle' }}"></i>
                            <span>{{ $hasCommentGoal ? 'Sugerencia Registrada' : 'Sin comentarios' }}</span>
                          </span>
                        </div>
                        <div class="tree-node-title-text">
                          {{ $cleanName($goal->name) }}
                        </div>
                      </div>

                      <div class="tree-node-body">
                        {{-- Comentario del Objetivo --}}
                        <div class="comment-box">
                          <label><i class="fa fa-pencil-alt text-amber" style="color:var(--amber);"></i> Sugerencia sobre este Objetivo Específico:</label>
                          <textarea class="area-comentario" data-node-id="{{ $goal->id }}" data-node-type="node" placeholder="Recomendaciones técnicas para el objetivo...">{{ $comentariosMap[$goal->id] ?? '' }}</textarea>
                          <div class="comment-box-footer">
                            <button type="button" class="btn-save-comment" style="background:#0284c7;" onclick="guardarComentarioNodo('{{ $goal->id }}', 'node', this)">
                              <i class="fa fa-save"></i> Guardar Sugerencia Objetivo
                            </button>
                          </div>
                        </div>

                        {{-- ACCIONES ESTRATÉGICAS / HIJOS --}}
                        @if($goal->children->count() > 0)
                          <div style="padding-left: 8px; margin-top: 12px; border-left: 2px solid var(--border);">
                            @foreach($goal->children as $action)
                              @php
                                $hasCommentAction = isset($comentariosMap[$action->id]) && !empty($comentariosMap[$action->id]);
                                $inisDeAccion = $iniciativas->where('pei_profile_id', $action->id);
                                $indAction = $action->indicador;
                              @endphp

                              <div class="tree-node" style="border-left: 4px solid var(--purple);">
                                <div class="tree-node-header" onclick="toggleNode(this)">
                                  <div class="tree-node-header-top">
                                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                      <div class="chevron-icon"><i class="fa fa-chevron-right"></i></div>
                                      <span class="badge-node badge-acc">ACC. ESTRATÉGICA</span>
                                      @if($indAction)
                                        <button type="button" class="btn-ver-ind" onclick="event.stopPropagation(); abrirModalIndicadorAsesor({{ json_encode($indAction) }});">
                                          <i class="fa fa-ruler-combined"></i> Indicador: {{ $indAction->codigoCompleto() }}
                                        </button>
                                      @endif
                                    </div>
                                    <span class="status-badge {{ $hasCommentAction ? 'status-has' : 'status-no' }}" id="status_badge_{{ $action->id }}">
                                      <i class="fa {{ $hasCommentAction ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                      <span>{{ $hasCommentAction ? 'Sugerencia Registrada' : 'Sin comentarios' }}</span>
                                    </span>
                                  </div>
                                  <div class="tree-node-title-text">
                                    {{ $cleanName($action->name) }}
                                  </div>
                                </div>

                                <div class="tree-node-body">
                                  {{-- Comentario Acción Estratégica --}}
                                  <div class="comment-box">
                                    <label><i class="fa fa-pencil-alt text-amber" style="color:var(--amber);"></i> Sugerencia sobre la Acción Estratégica:</label>
                                    <textarea class="area-comentario" data-node-id="{{ $action->id }}" data-node-type="node" placeholder="Sugerencias de viabilidad o indicadores...">{{ $comentariosMap[$action->id] ?? '' }}</textarea>
                                    <div class="comment-box-footer">
                                      <button type="button" class="btn-save-comment" style="background:var(--purple);" onclick="guardarComentarioNodo('{{ $action->id }}', 'node', this)">
                                        <i class="fa fa-save"></i> Guardar Sugerencia Acción
                                      </button>
                                    </div>
                                  </div>

                                  {{-- ACCIONES OPERATIVAS VINCULADAS --}}
                                  @if($inisDeAccion->count() > 0)
                                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--border);">
                                      <div style="font-size: 11px; font-weight: 800; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">
                                        <i class="fa fa-tasks" style="color: var(--green);"></i> Acciones Operativas de Mejora Continua ({{ $inisDeAccion->count() }})
                                      </div>

                                      @foreach($inisDeAccion as $ini)
                                        @php
                                          $hasCommentIni = isset($comentariosMap[$ini->id]) && !empty($comentariosMap[$ini->id]);
                                        @endphp
                                        <div class="tree-node" style="border-left: 4px solid var(--green); margin-bottom: 8px;">
                                          <div class="tree-node-header" onclick="toggleNode(this)">
                                            <div class="tree-node-header-top">
                                              <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                <div class="chevron-icon"><i class="fa fa-chevron-right"></i></div>
                                                <span class="badge-node badge-ini">{{ $cleanName($ini->codigo) }}</span>
                                              </div>
                                              <span class="status-badge {{ $hasCommentIni ? 'status-has' : 'status-no' }}" id="status_badge_{{ $ini->id }}">
                                                <i class="fa {{ $hasCommentIni ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                                <span>{{ $hasCommentIni ? 'Sugerencia Registrada' : 'Sin comentarios' }}</span>
                                              </span>
                                            </div>
                                            <div class="tree-node-title-text">
                                              {{ $cleanName($ini->accion) }}
                                            </div>
                                          </div>

                                          <div class="tree-node-body">
                                            <div class="comment-box">
                                              <label><i class="fa fa-pencil-alt text-amber" style="color:var(--amber);"></i> Sugerencia sobre esta Acción Operativa:</label>
                                              <textarea class="area-comentario" data-node-id="{{ $ini->id }}" data-node-type="iniciativa" placeholder="Recomendaciones tácticas u operativas...">{{ $comentariosMap[$ini->id] ?? '' }}</textarea>
                                              <div class="comment-box-footer">
                                                <button type="button" class="btn-save-comment" style="background:var(--green);" onclick="guardarComentarioNodo('{{ $ini->id }}', 'iniciativa', this)">
                                                  <i class="fa fa-save"></i> Guardar Acción Operativa
                                                </button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      @endforeach
                                    </div>
                                  @endif
                                </div>
                              </div>
                            @endforeach
                          </div>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

</div>

{{-- MODAL FICHA TÉCNICA DE INDICADOR ASOCIADO PARA ASESOR --}}
<div class="modal-bg-ind" id="modalIndicadorAsesor">
  <div class="modal-card-ind">
    <div class="modal-header-ind">
      <div>
        <div style="display:flex; align-items:center; gap:8px;">
          <span class="badge-node badge-eje" id="mIndCodigo" style="background:#38bdf8; color:#0f172a; font-weight:800;">-</span>
          <span class="badge-node" id="mIndDimension" style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.25);">-</span>
        </div>
        <h3 id="mIndNombre" style="font-size:15px; font-weight:800; color:#fff; margin-top:6px; line-height:1.35;">-</h3>
      </div>
      <button type="button" class="modal-x-ind" onclick="cerrarModalIndicadorAsesor()">&times;</button>
    </div>
    <div class="modal-body-ind">
      <div class="ind-grid-4">
        <div class="ind-info-box"><small>Dimensión</small><strong id="mIndDimTxt">-</strong></div>
        <div class="ind-info-box"><small>Frecuencia</small><strong id="mIndFrecTxt">-</strong></div>
        <div class="ind-info-box"><small>Cobertura</small><strong id="mIndCobTxt">-</strong></div>
        <div class="ind-info-box"><small>Sentido</small><strong id="mIndSentTxt">-</strong></div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-chart-line" style="color:var(--blue);"></i> Línea de Base</label>
        <div id="mIndLineaBase" class="ind-val-box">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-bullseye" style="color:var(--green);"></i> Metas Anuales Programadas</label>
        <div id="mIndMetasBox" class="ind-metas-grid">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-calculator" style="color:var(--purple);"></i> Fórmula de Cálculo</label>
        <div id="mIndFormula" class="ind-val-box">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-database" style="color:var(--amber);"></i> Variables &amp; Fuente de Información</label>
        <div id="mIndFuente" class="ind-val-box">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-building" style="color:var(--text-secondary);"></i> Unidad de Medida &amp; Responsable</label>
        <div id="mIndResponsable" class="ind-val-box">-</div>
      </div>
    </div>
  </div>
</div>

{{-- TOAST NOTIFICATION --}}
<div class="toast-msg" id="toastAsesor">
  <i class="fa fa-check-circle text-green" style="color: var(--green);"></i>
  <span id="toastAsesorTxt">Sugerencia guardada correctamente</span>
</div>

<div class="page-footer">
  SIPLAN GO — {{ $sysSiteName }} &copy; {{ date('Y') }}
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function toggleNode(headerEl) {
  var $header = $(headerEl);
  var $body = $header.next('.tree-node-body');
  $header.toggleClass('active');
  $body.slideToggle(180);
}

function showToast(msg) {
  var $t = $('#toastAsesor');
  $('#toastAsesorTxt').text(msg || 'Sugerencia guardada correctamente.');
  $t.css('display', 'flex').hide().fadeIn(200);
  setTimeout(function() {
    $t.fadeOut(300);
  }, 2500);
}

function abrirModalIndicadorAsesor(ind) {
  if (!ind) return;
  var codLetters = (ind.codigo_letras || '').toUpperCase();
  var codNumbers = ind.codigo_numeros || '';
  var codigo = (codLetters || codNumbers) ? (codLetters + '-' + codNumbers) : 'IND-' + ind.id;

  $('#mIndCodigo').text(codigo);
  $('#mIndDimension').text((ind.dimension || 'Eficacia').toUpperCase());
  $('#mIndNombre').text(ind.nombre || '-');

  $('#mIndDimTxt').text((ind.dimension || '—').toUpperCase());
  $('#mIndFrecTxt').text((ind.frecuencia || '—').toUpperCase());
  $('#mIndCobTxt').text((ind.cobertura || '—').toUpperCase());
  $('#mIndSentTxt').text(ind.sentido === 'descendente' ? '▼ Descendente (Menor es mejor)' : '▲ Ascendente (Mayor es mejor)');

  var lbAnio = ind.linea_base_anio || '';
  var lbVal = ind.linea_base_valor || '';
  $('#mIndLineaBase').html(lbVal ? ('<strong>Año ' + (lbAnio || 'Base') + ':</strong> ' + lbVal) : 'Sin Línea de Base especificada.');

  // Metas
  var $metas = $('#mIndMetasBox').empty();
  var metasArr = ind.metas;
  if (typeof metasArr === 'string') {
    try { metasArr = JSON.parse(metasArr); } catch(e) { metasArr = []; }
  }
  if (Array.isArray(metasArr) && metasArr.length > 0) {
    metasArr.forEach(function(m) {
      if (m.anio && m.valor) {
        $metas.append('<div class="ind-meta-pill"><strong>' + m.anio + ':</strong> ' + m.valor + '</div>');
      }
    });
  } else {
    $metas.html('<span style="font-size:12px; color:var(--muted);">Sin metas programadas.</span>');
  }

  $('#mIndFormula').text(ind.formula || 'Sin fórmula de cálculo cargada.');
  
  var fuenteTxt = '';
  if (ind.variables) fuenteTxt += '<strong>Variables:</strong> ' + ind.variables + '<br>';
  if (ind.fuente) fuenteTxt += '<strong>Fuente:</strong> ' + ind.fuente;
  $('#mIndFuente').html(fuenteTxt || 'Sin fuente o variables detalladas.');

  var respTxt = '';
  if (ind.unidad_medida) respTxt += '<strong>Unidad de Medida:</strong> ' + ind.unidad_medida + '<br>';
  if (ind.dependencia_responsable) respTxt += '<strong>Responsable:</strong> ' + ind.dependencia_responsable;
  $('#mIndResponsable').html(respTxt || 'Sin unidad de medida ni dependencia especificada.');

  $('#modalIndicadorAsesor').css('display', 'flex').hide().fadeIn(200);
  document.body.style.overflow = 'hidden';
}

function cerrarModalIndicadorAsesor() {
  $('#modalIndicadorAsesor').fadeOut(200);
  document.body.style.overflow = '';
}

$(document).on('click', function(e) {
  if (e.target.id === 'modalIndicadorAsesor') {
    cerrarModalIndicadorAsesor();
  }
});

function guardarComentarioNodo(nodeId, type, btnEl) {
  var $btn = $(btnEl);
  var $container = $btn.closest('.comment-box');
  var comentario = $container.find('textarea').val();

  $btn.prop('disabled', true).css('opacity', '0.7');

  $.ajax({
    url: "{{ route('asesoria.public.comentario', $asesoria->id) }}",
    type: "POST",
    data: {
      _token: "{{ csrf_token() }}",
      node_id: nodeId,
      comentario: comentario,
      type: type
    },
    success: function(resp) {
      $btn.prop('disabled', false).css('opacity', '1');
      showToast(resp.message || 'Sugerencia guardada correctamente.');

      // Actualizar badge de estado
      var $badge = $('#status_badge_' + nodeId);
      if (comentario.trim() !== '') {
        $badge.removeClass('status-no').addClass('status-has')
              .html('<i class="fa fa-check-circle"></i><span>Sugerencia Registrada</span>');
      } else {
        $badge.removeClass('status-has').addClass('status-no')
              .html('<i class="fa fa-circle"></i><span>Sin comentarios</span>');
      }

      if (resp.total_comentarios !== undefined) {
        $('#cntComentariosRegistrados').text(resp.total_comentarios);
      }
    },
    error: function() {
      $btn.prop('disabled', false).css('opacity', '1');
      showToast('Error al guardar la sugerencia.');
    }
  });
}

$(document).ready(function() {
  // Expandir Todo
  $('#btnExpandirTodo').click(function() {
    $('.tree-node-header').addClass('active');
    $('.tree-node-body').slideDown(180);
  });

  // Colapsar Todo
  $('#btnColapsarTodo').click(function() {
    $('.tree-node-header').removeClass('active');
    $('.tree-node-body').slideUp(180);
  });

  // Finalizar Dictamen General
  $('#btnFinalizarDictamen').click(function() {
    var dictamen = $('#areaDictamenGeneral').val();
    var $btn = $(this);

    $btn.prop('disabled', true).css('opacity', '0.7');

    $.ajax({
      url: "{{ route('asesoria.public.finalizar', $asesoria->id) }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        dictamen_general: dictamen
      },
      success: function(resp) {
        $btn.prop('disabled', false).css('opacity', '1');
        showToast(resp.message || 'Dictamen finalizado y guardado.');
      },
      error: function() {
        $btn.prop('disabled', false).css('opacity', '1');
        showToast('Error al guardar el dictamen.');
      }
    });
  });
});
</script>
</body>
</html>
