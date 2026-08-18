@extends('layouts.app', ['activePage' => 'pei-profiles', 'titlePage' => 'Vista de Asesor Externo — ' . $profile->name])

@section('content')
@php
  $cleanName = function($name) {
      if (empty($name)) return '';
      $decoded = html_entity_decode(strip_tags($name), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      return trim(preg_replace('/\s+/', ' ', $decoded));
  };
@endphp

<style>
.btn-ver-ind{display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#e0f2fe,#dbeafe);color:#0369a1;border:1px solid #bae6fd;padding:2px 10px;border-radius:100px;font-size:0.68rem;font-weight:700;cursor:pointer;transition:all .15s ease}
.btn-ver-ind:hover{background:#0284c7;color:#fff;border-color:#0284c7;transform:scale(1.02)}

/* Modal Ficha Técnica Indicador */
.modal-bg-ind{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,.65);backdrop-filter:blur(6px);z-index:1060;display:none;align-items:center;justify-content:center;padding:16px}
.modal-card-ind{background:#fff;border-radius:20px;max-width:720px;width:100%;max-height:85vh;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.2);display:flex;flex-direction:column}
.modal-header-ind{background:linear-gradient(135deg,#0f172a,#1e293b);padding:18px 24px;color:#fff;display:flex;align-items:center;justify-content:space-between}
.modal-x-ind{background:none;border:none;color:#94a3b8;font-size:24px;cursor:pointer;line-height:1}
.modal-x-ind:hover{color:#fff}
.modal-body-ind{padding:24px;overflow-y:auto;background:#f8fafc}

.ind-grid-4{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:10px;margin-bottom:18px}
.ind-info-box{background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px}
.ind-info-box small{display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase}
.ind-info-box strong{font-size:12.5px;color:#0f172a;display:block;margin-top:2px}

.ind-section{background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin-bottom:12px}
.ind-section label{display:block;font-size:11px;font-weight:800;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.3px}
.ind-val-box{font-size:12.5px;color:#0f172a;line-height:1.5}
.ind-metas-grid{display:flex;flex-wrap:wrap;gap:8px}
.ind-meta-pill{background:#eff6ff;border:1px solid #dbeafe;color:#1d4ed8;padding:4px 12px;border-radius:8px;font-size:12px;font-weight:700}
</style>

<div class="content" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-fluid py-3 px-2 px-md-4">

        {{-- ── ENCABEZADO PRINCIPAL Y PANEL DE VALIDACIÓN ── --}}
        <div class="card border-0 shadow-lg mb-3 mb-md-4" style="border-radius: 16px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 1rem;">
                    <div>
                        <div class="d-flex align-items-center mb-2" style="gap: 8px; flex-wrap: wrap;">
                            <span class="badge badge-warning font-weight-bold px-3 py-1 text-dark" style="font-size: 0.8rem; border-radius: 12px;">
                                <i class="fa fa-user-md mr-1"></i> PANEL DE ASESOR EXTERNO
                            </span>
                            <span class="badge badge-outline text-white border-white px-2 py-1" style="font-size: 0.75rem;">
                                Período {{ $profile->year_start ? \Carbon\Carbon::parse($profile->year_start)->format('Y') : '' }} – {{ $profile->year_end ? \Carbon\Carbon::parse($profile->year_end)->format('Y') : '' }}
                            </span>
                        </div>
                        <h2 class="font-weight-bold text-white mb-1" style="font-size: 1.4rem; letter-spacing: -0.02em;">
                            {{ $cleanName($profile->name) }}
                        </h2>
                        <p class="text-slate-300 mb-0 small opacity-8">
                            <i class="fa fa-info-circle mr-1 text-warning"></i> Vista de revisión técnica y validación externa. Redactá sugerencias de mejora en cada elemento del plan.
                        </p>
                    </div>

                    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                        @if(!isset($isPublicAccess) || !$isPublicAccess)
                            <a href="{{ route('pei-profiles.show', $profile->id) }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                                <i class="fa fa-arrow-left mr-1"></i> Volver al PEI
                            </a>
                            @if($profile->asesor_token)
                                <button type="button" class="btn btn-sm btn-info rounded-pill px-3 shadow-xs" onclick="copiarEnlaceAsesor('{{ url('/public/pei-asesor/' . $profile->asesor_token) }}')">
                                    <i class="fa fa-share-alt mr-1"></i> Copiar Enlace Asesor
                                </button>
                            @else
                                <form action="{{ route('pei.asesor.token.generate', $profile->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning font-weight-bold text-dark rounded-pill px-3 shadow-xs">
                                        <i class="fa fa-key mr-1"></i> Generar Enlace Externo
                                    </button>
                                </form>
                            @endif
                        @endif

                        <button type="button" class="btn btn-sm btn-light font-weight-bold rounded-pill px-3 text-dark" id="btnExpandirTodoAsesor">
                            <i class="fa fa-expand mr-1 text-primary"></i> Expandir Todo
                        </button>
                        <button type="button" class="btn btn-sm btn-dark font-weight-bold rounded-pill px-3" id="btnColapsarTodoAsesor">
                            <i class="fa fa-compress mr-1 text-warning"></i> Colapsar Todo
                        </button>
                    </div>
                </div>

                <hr style="border-top: 1px solid rgba(255,255,255,0.15);" class="my-3">

                {{-- Métrica de Resumen del Árbol (5 Niveles) --}}
                @php
                    $cantEjes = $descendants->where('level', 'axi')->count();
                    $cantObj = $descendants->whereIn('level', ['goal', 'objetivo_especifico'])->count();
                    $cantAcc = $descendants->where('level', 'action')->count();
                    $cantInis = $iniciativas->count();
                    $cantComentarios = $descendants->whereNotNull('comentario_asesor')->where('comentario_asesor', '!=', '')->count()
                                      + ($profile->comentario_asesor ? 1 : 0)
                                      + $iniciativas->whereNotNull('comentario_asesor')->where('comentario_asesor', '!=', '')->count();
                @endphp
                <div class="row text-center" style="gap: 0;">
                    <div class="col-6 col-sm-4 col-md mb-2 mb-md-0 px-1">
                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.08); border-radius: 12px;">
                            <div class="text-warning font-weight-bold" style="font-size: 1.25rem;">{{ $cantEjes }}</div>
                            <div class="small text-slate-300" style="font-size: 0.72rem;">Ejes Estratégicos</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md mb-2 mb-md-0 px-1">
                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.08); border-radius: 12px;">
                            <div class="text-info font-weight-bold" style="font-size: 1.25rem;">{{ $cantObj }}</div>
                            <div class="small text-slate-300" style="font-size: 0.72rem;">Objetivos Específicos</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md mb-2 mb-md-0 px-1">
                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.08); border-radius: 12px;">
                            <div class="font-weight-bold" style="font-size: 1.25rem; color: #a78bfa;">{{ $cantAcc }}</div>
                            <div class="small text-slate-300" style="font-size: 0.72rem;">Acciones Estratégicas</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md mb-2 mb-md-0 px-1">
                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.08); border-radius: 12px;">
                            <div class="text-success font-weight-bold" style="font-size: 1.25rem;">{{ $cantInis }}</div>
                            <div class="small text-slate-300" style="font-size: 0.72rem;">Acciones Operativas (Mejora)</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md px-1">
                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.08); border-radius: 12px;">
                            <div class="text-warning font-weight-bold" id="cntTotalComentariosAsesor" style="font-size: 1.25rem;">{{ $cantComentarios }}</div>
                            <div class="small text-slate-300" style="font-size: 0.72rem;">Sugerencias Guardadas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── BARRA DE FILTRO Y BÚSQUEDA RÁPIDA DE ASESORÍA ── --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px;">
            <div class="card-body p-2 p-md-3 d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                    <span class="font-weight-bold text-muted small mr-1" style="font-size: 0.78rem;">
                        <i class="fa fa-filter mr-1 text-primary"></i> Filtrar:
                    </span>
                    <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill px-3 filter-asesor-btn active" data-filter="all">
                        Todos
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-warning text-dark font-weight-bold rounded-pill px-3 filter-asesor-btn" data-filter="commented">
                        <i class="fa fa-comment text-warning mr-1"></i> Con Sugerencia
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary font-weight-bold rounded-pill px-3 filter-asesor-btn" data-filter="pending">
                        <i class="fa fa-comment-o mr-1"></i> Sin Comentario
                    </button>
                </div>
                <div style="flex: 1 1 200px; max-width: 380px;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control form-control-sm border-left-0" id="inputBuscarAsesorNode" placeholder="Buscar elemento por título o código...">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SECCIÓN 1: DICTAMEN O OBSERVACIONES GENERALES DEL PLAN ── --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border-left: 5px solid #f59e0b !important;">
            <div class="card-header bg-white p-3 border-bottom-0 d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                <h5 class="font-weight-bold text-dark mb-0 d-flex align-items-center" style="font-size: 1.05rem;">
                    <i class="fa fa-clipboard-check text-warning mr-2" style="font-size: 1.2rem;"></i>
                    <span>Dictamen General y Observaciones del Asesor sobre el PEI</span>
                </h5>
                <span class="badge badge-light border text-muted px-2.5 py-1" style="font-size: 0.75rem;">Visión Global</span>
            </div>
            <div class="card-body p-3 pt-0">
                <p class="text-muted small mb-2">
                    Escribí un dictamen técnico o resumen ejecutivo de la evaluación del Plan Estratégico Institucional:
                </p>
                <textarea class="form-control border rounded p-3 area-comentario-asesor" data-node-id="{{ $profile->id }}" data-type="node" rows="3" placeholder="Recomendaciones generales, sugerencias de alineación metodológica o comentarios globales..." style="font-size: 0.88rem; border-radius: 12px;">{{ $profile->comentario_asesor }}</textarea>
                <div class="d-flex justify-content-end mt-2">
                    <button type="button" class="btn btn-sm btn-warning text-dark font-weight-bold rounded-pill px-4 shadow-xs btn-guardar-comentario" data-node-id="{{ $profile->id }}" data-type="node">
                        <i class="fa fa-save mr-1"></i> Guardar Dictamen General
                    </button>
                </div>
            </div>
        </div>

        {{-- ── SECCIÓN 2: ESTRUCTURA DEL PLAN (ÁRBOLES Y ELEMENTOS) ── --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white p-3 border-bottom">
                <h5 class="font-weight-bold text-dark mb-0 d-flex align-items-center" style="font-size: 1.05rem;">
                    <i class="fa fa-sitemap text-primary mr-2"></i>
                    <span>Estructura del Plan Estratégico — Sugerencias por Elemento</span>
                </h5>
            </div>
            <div class="card-body p-2 p-md-3" id="contenedorArbolAsesor">

                @if($treeNodes->count() === 0)
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-folder-open mb-2" style="font-size: 2.5rem;"></i>
                        <p class="mb-0 font-weight-bold">No hay Ejes Estratégicos registrados en este PEI.</p>
                    </div>
                @else
                    @foreach($treeNodes as $axi)
                        @php
                            $iniciativasAxi = $iniciativas->where('pei_profile_id', $axi->id);
                            $hasAxiComment = !empty($axi->comentario_asesor);
                            $indAxi = $axi->indicador;
                        @endphp
                        {{-- NIVEL 1: EJE ESTRATÉGICO --}}
                        <div class="card border mb-3 shadow-xs nodo-asesor-item" data-has-comment="{{ $hasAxiComment ? '1' : '0' }}" data-search="{{ strtolower($cleanName($axi->name)) }}" style="border-radius: 14px; overflow: hidden; border-left: 5px solid #2563eb !important;">
                            <div class="card-header p-2.5 p-md-3 bg-white cursor-pointer" onclick="$(this).next('.card-body').slideToggle(150);">
                                <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">
                                    <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                        <span class="badge badge-primary font-weight-bold px-2 py-1" style="font-size: 0.72rem;">EJE ESTRATÉGICO</span>
                                        <span class="badge {{ $hasAxiComment ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2.5 py-1" style="font-size: 0.72rem;">
                                            <i class="fa {{ $hasAxiComment ? 'fa-comment' : 'fa-comment-o' }} mr-1"></i>
                                            {{ $hasAxiComment ? 'Con Sugerencia' : 'Sin Comentario' }}
                                        </span>
                                        @if($indAxi)
                                          <button type="button" class="btn-ver-ind" onclick="event.stopPropagation(); abrirModalIndicadorAsesor({{ json_encode($indAxi) }});">
                                            <i class="fa fa-ruler-combined"></i> Indicador: {{ $indAxi->codigoCompleto() }}
                                          </button>
                                        @endif
                                    </div>
                                    <i class="fa fa-chevron-down text-muted small"></i>
                                </div>
                                <div class="mt-1.5 font-weight-bold text-dark" style="font-size: 0.95rem; line-height: 1.35; word-break: break-word;">
                                    {{ $cleanName($axi->name) }}
                                </div>
                            </div>

                            <div class="card-body p-2 p-md-3 bg-slate-50">
                                {{-- Caja de Comentario para el Eje --}}
                                <div class="p-2.5 p-md-3 rounded border bg-white mb-3 shadow-xs" style="border-radius: 10px;">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        <i class="fa fa-pencil-alt text-warning mr-1"></i> Sugerencia / Comentario del Asesor sobre este Eje:
                                    </label>
                                    <textarea class="form-control border rounded p-2 area-comentario-asesor" data-node-id="{{ $axi->id }}" data-type="node" rows="2" placeholder="Escribí aquí las recomendaciones de mejora para este Eje..." style="font-size: 0.85rem;">{{ $axi->comentario_asesor }}</textarea>
                                    <div class="d-flex justify-content-end mt-2">
                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill px-3 btn-guardar-comentario" data-node-id="{{ $axi->id }}" data-type="node">
                                            <i class="fa fa-save mr-1"></i> Guardar Sugerencia Eje
                                        </button>
                                    </div>
                                </div>

                                {{-- RECURSIÓN HIJOS (OBJETIVOS ESPECÍFICOS) --}}
                                @if($axi->children->count() > 0)
                                    <div class="pl-2 pl-md-3 border-left ml-1 ml-md-2" style="border-color: #cbd5e1 !important;">
                                        @foreach($axi->children as $goal)
                                            @php
                                              $hasGoalComment = !empty($goal->comentario_asesor);
                                              $indGoal = $goal->indicador;
                                            @endphp
                                            <div class="card border mb-3 shadow-xs nodo-asesor-item" data-has-comment="{{ $hasGoalComment ? '1' : '0' }}" data-search="{{ strtolower($cleanName($goal->name)) }}" style="border-radius: 12px; overflow: hidden; border-left: 4px solid #0284c7 !important;">
                                                <div class="card-header p-2.5 bg-white cursor-pointer" onclick="$(this).next('.card-body').slideToggle(150);">
                                                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">
                                                        <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                                            <span class="badge badge-info font-weight-bold px-2 py-1" style="font-size: 0.68rem;">OBJ. ESPECÍFICO</span>
                                                            <span class="badge {{ $hasGoalComment ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2 py-1" style="font-size: 0.68rem;">
                                                                <i class="fa {{ $hasGoalComment ? 'fa-comment' : 'fa-comment-o' }} mr-1"></i>
                                                                {{ $hasGoalComment ? 'Con Sugerencia' : 'Sin Comentario' }}
                                                            </span>
                                                            @if($indGoal)
                                                              <button type="button" class="btn-ver-ind" onclick="event.stopPropagation(); abrirModalIndicadorAsesor({{ json_encode($indGoal) }});">
                                                                <i class="fa fa-ruler-combined"></i> Indicador: {{ $indGoal->codigoCompleto() }}
                                                              </button>
                                                            @endif
                                                        </div>
                                                        <i class="fa fa-chevron-down text-muted small"></i>
                                                    </div>
                                                    <div class="mt-1 font-weight-bold text-dark" style="font-size: 0.9rem; line-height: 1.35; word-break: break-word;">
                                                        {{ $cleanName($goal->name) }}
                                                    </div>
                                                </div>

                                                <div class="card-body p-2.5 p-md-3 bg-white">
                                                    {{-- Comentario del Objetivo --}}
                                                    <div class="mb-3">
                                                        <label class="font-weight-bold text-dark small mb-1">
                                                            <i class="fa fa-pencil-alt text-warning mr-1"></i> Sugerencia del Asesor sobre este Objetivo Específico:
                                                        </label>
                                                        <textarea class="form-control border rounded p-2 area-comentario-asesor" data-node-id="{{ $goal->id }}" data-type="node" rows="2" placeholder="Recomendación técnica para el objetivo..." style="font-size: 0.84rem;">{{ $goal->comentario_asesor }}</textarea>
                                                        <div class="d-flex justify-content-end mt-2">
                                                            <button type="button" class="btn btn-xs btn-info font-weight-bold rounded-pill px-3 btn-guardar-comentario" data-node-id="{{ $goal->id }}" data-type="node">
                                                                <i class="fa fa-save mr-1"></i> Guardar Sugerencia Objetivo
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- ACCIONES ESTRATÉGICAS / NODOS HIJOS --}}
                                                    @if($goal->children->count() > 0)
                                                        <div class="pl-2 pl-md-3 border-left ml-1 ml-md-2" style="border-color: #e2e8f0 !important;">
                                                            @foreach($goal->children as $action)
                                                                @php
                                                                    $inisDeAccion = $iniciativas->where('pei_profile_id', $action->id);
                                                                    $hasActionComment = !empty($action->comentario_asesor);
                                                                    $indAction = $action->indicador;
                                                                @endphp
                                                                <div class="card border mb-3 shadow-xs nodo-asesor-item" data-has-comment="{{ $hasActionComment ? '1' : '0' }}" data-search="{{ strtolower($cleanName($action->name)) }}" style="border-radius: 10px; border-left: 4px solid #7c3aed !important;">
                                                                    <div class="card-header p-2.5 bg-white cursor-pointer" onclick="$(this).next('.card-body').slideToggle(150);">
                                                                        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">
                                                                            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                                                                <span class="badge badge-purple text-white font-weight-bold px-2 py-1" style="font-size: 0.65rem; background: #7c3aed;">ACC. ESTRATÉGICA</span>
                                                                                <span class="badge {{ $hasActionComment ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2 py-1" style="font-size: 0.65rem;">
                                                                                    <i class="fa {{ $hasActionComment ? 'fa-comment' : 'fa-comment-o' }} mr-1"></i>
                                                                                    {{ $hasActionComment ? 'Con Sugerencia' : 'Sin Comentario' }}
                                                                                </span>
                                                                                @if($indAction)
                                                                                  <button type="button" class="btn-ver-ind" onclick="event.stopPropagation(); abrirModalIndicadorAsesor({{ json_encode($indAction) }});">
                                                                                    <i class="fa fa-ruler-combined"></i> Indicador: {{ $indAction->codigoCompleto() }}
                                                                                  </button>
                                                                                @endif
                                                                            </div>
                                                                            <i class="fa fa-chevron-down text-muted small"></i>
                                                                        </div>
                                                                        <div class="mt-1 font-weight-bold text-dark" style="font-size: 0.88rem; line-height: 1.35; word-break: break-word;">
                                                                            {{ $cleanName($action->name) }}
                                                                        </div>
                                                                    </div>

                                                                    <div class="card-body p-2.5 p-md-3 bg-light">
                                                                        {{-- Comentario Acción Estratégica --}}
                                                                        <div class="mb-3">
                                                                            <label class="font-weight-bold text-dark small mb-1">
                                                                                <i class="fa fa-pencil-alt text-warning mr-1"></i> Sugerencia del Asesor sobre la Acción Estratégica:
                                                                            </label>
                                                                            <textarea class="form-control border rounded p-2 area-comentario-asesor" data-node-id="{{ $action->id }}" data-type="node" rows="2" placeholder="Sugerencia de alineación o recursos..." style="font-size: 0.83rem;">{{ $action->comentario_asesor }}</textarea>
                                                                            <div class="d-flex justify-content-end mt-2">
                                                                                <button type="button" class="btn btn-xs btn-purple text-white font-weight-bold rounded-pill px-3 btn-guardar-comentario" data-node-id="{{ $action->id }}" data-type="node" style="background: #7c3aed;">
                                                                                    <i class="fa fa-save mr-1"></i> Guardar Sugerencia Acción
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                        {{-- ACCIONES OPERATIVAS DE MEJORA CONTINUA VINCULADAS --}}
                                                                        @if($inisDeAccion->count() > 0)
                                                                            <div class="mt-3 pt-2 border-top">
                                                                                <div class="font-weight-bold text-dark text-uppercase small mb-2" style="font-size: 0.78rem;">
                                                                                    <i class="fa fa-tasks text-info mr-1"></i> Acciones Operativas de Mejora Continua ({{ $inisDeAccion->count() }})
                                                                                </div>
                                                                                @foreach($inisDeAccion as $ini)
                                                                                    @php $hasIniComment = !empty($ini->comentario_asesor); @endphp
                                                                                    <div class="p-2.5 rounded border bg-white mb-2 shadow-xs nodo-asesor-item" data-has-comment="{{ $hasIniComment ? '1' : '0' }}" data-search="{{ strtolower($cleanName($ini->codigo . ' ' . $ini->accion)) }}" style="border-radius: 8px; border-left: 3px solid #10b981 !important;">
                                                                                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-1.5" style="gap: 6px;">
                                                                                            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                                                                                <span class="badge badge-dark font-weight-bold" style="font-size: 0.68rem;">{{ $cleanName($ini->codigo) }}</span>
                                                                                                <span class="badge badge-success font-weight-bold" style="font-size: 0.65rem;">{{ $ini->estado_grupo }}</span>
                                                                                                <span class="badge {{ $hasIniComment ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2 py-0.5" style="font-size: 0.62rem;">
                                                                                                    {{ $hasIniComment ? 'Con Sugerencia' : 'Sin Comentario' }}
                                                                                                </span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="font-weight-bold text-dark small mb-2" style="line-height: 1.35;">
                                                                                            {{ $cleanName($ini->accion) }}
                                                                                        </div>
                                                                                        <textarea class="form-control border rounded p-2 area-comentario-asesor" data-node-id="{{ $ini->id }}" data-type="iniciativa" rows="2" placeholder="Sugerencia u observación técnica para esta Acción Operativa..." style="font-size: 0.82rem;">{{ $ini->comentario_asesor }}</textarea>
                                                                                        <div class="d-flex justify-content-end mt-2">
                                                                                            <button type="button" class="btn btn-xs btn-success font-weight-bold rounded-pill px-3 btn-guardar-comentario" data-node-id="{{ $ini->id }}" data-type="iniciativa">
                                                                                                <i class="fa fa-save mr-1"></i> Guardar Sugerencia Acción Operativa
                                                                                            </button>
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
                @endif
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
        <label><i class="fa fa-chart-line text-info"></i> Línea de Base</label>
        <div id="mIndLineaBase" class="ind-val-box">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-bullseye text-success"></i> Metas Anuales Programadas</label>
        <div id="mIndMetasBox" class="ind-metas-grid">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-calculator text-primary"></i> Fórmula de Cálculo</label>
        <div id="mIndFormula" class="ind-val-box">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-database text-warning"></i> Variables &amp; Fuente de Información</label>
        <div id="mIndFuente" class="ind-val-box">-</div>
      </div>

      <div class="ind-section">
        <label><i class="fa fa-building text-secondary"></i> Unidad de Medida &amp; Responsable</label>
        <div id="mIndResponsable" class="ind-val-box">-</div>
      </div>
    </div>
  </div>
</div>

<script>
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
    $metas.html('<span style="font-size:12px; color:#94a3b8;">Sin metas programadas.</span>');
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

$(document).ready(function() {
    // ── GUARDAR COMENTARIO INDIVIDUAL ──
    $(document).on('click', '.btn-guardar-comentario', function() {
        var $btn = $(this);
        var nodeId = $btn.data('node-id');
        var type = $btn.data('type') || 'node';
        var $card = $btn.closest('.nodo-asesor-item, .card');
        var $textarea = $card.find('.area-comentario-asesor[data-node-id="' + nodeId + '"]').first();
        if (!$textarea.length) {
            $textarea = $btn.closest('div').find('.area-comentario-asesor').first();
        }
        var comentario = $.trim($textarea.val());

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: "{{ route('pei-profiles.guardar-comentario-asesor', $profile->id) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                node_id: nodeId,
                comentario: comentario,
                type: type
            },
            success: function(resp) {
                $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Guardado!');
                setTimeout(function() {
                    $btn.html('<i class="fa fa-save mr-1"></i> Guardar Sugerencia');
                }, 2000);

                if (window.toastr) {
                    toastr.success(resp.message || 'Sugerencia guardada correctamente.');
                }

                // Actualizar dataset para filtros
                $card.attr('data-has-comment', comentario !== '' ? '1' : '0');
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Sugerencia');
                if (window.toastr) {
                    toastr.error('Error al guardar la sugerencia.');
                }
            }
        });
    });

    // ── FILTROS DE ASESORÍA (Todos / Con Sugerencia / Sin Comentario) ──
    $('.filter-asesor-btn').on('click', function() {
        $('.filter-asesor-btn').removeClass('btn-primary btn-warning btn-secondary active').addClass('btn-outline-secondary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');

        var filter = $(this).data('filter');
        filtrarElementos();
    });

    // ── BUSCADOR DE ELEMENTOS EN VISTA ASESOR ──
    $('#inputBuscarAsesorNode').on('keyup', function() {
        filtrarElementos();
    });

    function filtrarElementos() {
        var query = $('#inputBuscarAsesorNode').val().toLowerCase().trim();
        var currentFilter = $('.filter-asesor-btn.active').data('filter') || 'all';

        $('.nodo-asesor-item').each(function() {
            var searchTxt = ($(this).data('search') || '').toString().toLowerCase();
            var hasComment = $(this).attr('data-has-comment') === '1';

            var matchSearch = !query || searchTxt.indexOf(query) >= 0;
            var matchFilter = true;

            if (currentFilter === 'commented') matchFilter = hasComment;
            if (currentFilter === 'pending') matchFilter = !hasComment;

            if (matchSearch && matchFilter) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // ── BOTONES EXPANDIR / COLAPSAR ──
    $('#btnExpandirTodoAsesor').click(function() {
        $('#contenedorArbolAsesor .card-body').slideDown(150);
    });

    $('#btnColapsarTodoAsesor').click(function() {
        $('#contenedorArbolAsesor .card-body').slideUp(150);
    });
});

function copiarEnlaceAsesor(url) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function() {
            if (window.toastr) toastr.success('Enlace Seguro para Asesor copiado al portapapeles.');
        });
    } else {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(url).select();
        document.execCommand("copy");
        $temp.remove();
        if (window.toastr) toastr.success('Enlace Seguro para Asesor copiado al portapapeles.');
    }
}
</script>
@endsection
