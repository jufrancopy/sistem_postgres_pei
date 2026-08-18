@extends('layouts.app', ['activePage' => 'pei-profiles', 'titlePage' => 'Vista de Asesor Externo — ' . $profile->name])

@section('content')
<div class="content" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-fluid py-4">

        {{-- ── ENCABEZADO PRINCIPAL Y PANEL DE VALIDACIÓN ── --}}
        <div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 1rem;">
                    <div>
                        <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                            <span class="badge badge-warning font-weight-bold px-3 py-1 text-dark" style="font-size: 0.8rem; border-radius: 12px;">
                                <i class="fa fa-user-md mr-1"></i> PANEL DE ASESOR EXTERNO
                            </span>
                            <span class="badge badge-outline text-white border-white px-2 py-1" style="font-size: 0.75rem;">
                                Período {{ $profile->year_start ? \Carbon\Carbon::parse($profile->year_start)->format('Y') : '' }} – {{ $profile->year_end ? \Carbon\Carbon::parse($profile->year_end)->format('Y') : '' }}
                            </span>
                        </div>
                        <h2 class="font-weight-bold text-white mb-1" style="font-size: 1.6rem; letter-spacing: -0.02em;">
                            {{ $profile->name }}
                        </h2>
                        <p class="text-slate-300 mb-0 small opacity-8">
                            <i class="fa fa-info-circle mr-1 text-warning"></i> Vista de revisión técnica y validación externa. Redactá sugerencias de mejora en cada elemento del plan.
                        </p>
                    </div>

                    <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
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

                {{-- Métrica de Resumen del Árbol --}}
                @php
                    $cantEjes = $descendants->where('level', 'axi')->count();
                    $cantObj = $descendants->whereIn('level', ['goal', 'objetivo_especifico'])->count();
                    $cantAcc = $descendants->where('level', 'action')->count();
                    $cantInis = $iniciativas->count();
                    $cantComentarios = $descendants->whereNotNull('comentario_asesor')->where('comentario_asesor', '!=', '')->count()
                                      + ($profile->comentario_asesor ? 1 : 0)
                                      + $iniciativas->whereNotNull('comentario_asesor')->where('comentario_asesor', '!=', '')->count();
                @endphp
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="p-2.5 rounded bg-white-10" style="background: rgba(255,255,255,0.06); border-radius: 12px;">
                            <div class="text-warning font-weight-bold" style="font-size: 1.3rem;">{{ $cantEjes }}</div>
                            <div class="small text-slate-300">Ejes Estratégicos</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="p-2.5 rounded bg-white-10" style="background: rgba(255,255,255,0.06); border-radius: 12px;">
                            <div class="text-info font-weight-bold" style="font-size: 1.3rem;">{{ $cantObj }}</div>
                            <div class="small text-slate-300">Objetivos Específicos</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="p-2.5 rounded bg-white-10" style="background: rgba(255,255,255,0.06); border-radius: 12px;">
                            <div class="text-success font-weight-bold" style="font-size: 1.3rem;">{{ $cantInis }}</div>
                            <div class="small text-slate-300">Acciones Operativas (Mejora)</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                        <div class="p-2.5 rounded bg-white-10" style="background: rgba(255,255,255,0.06); border-radius: 12px;">
                            <div class="text-warning font-weight-bold" id="cntTotalComentariosAsesor" style="font-size: 1.3rem;">{{ $cantComentarios }}</div>
                            <div class="small text-slate-300">Sugerencias Guardadas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SECCIÓN 1: DICTAMEN O OBSERVACIONES GENERALES DEL PLAN ── --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border-left: 5px solid #f59e0b !important;">
            <div class="card-header bg-white p-3 border-bottom-0 d-flex align-items-center justify-content-between">
                <h5 class="font-weight-bold text-dark mb-0 d-flex align-items-center">
                    <i class="fa fa-clipboard-check text-warning mr-2" style="font-size: 1.2rem;"></i>
                    <span>Dictamen General y Observaciones del Asesor sobre el PEI</span>
                </h5>
                <span class="badge badge-light border text-muted px-2.5 py-1" style="font-size: 0.75rem;">Visión Global</span>
            </div>
            <div class="card-body pt-0 pb-4 px-4">
                <p class="text-muted small mb-2">
                    Escribí una síntesis o sugerencia macro sobre la estructura general del plan, su factibilidad o alineamiento institucional.
                </p>
                <div class="form-group mb-2">
                    <textarea class="form-control border rounded-lg p-3 area-comentario-asesor" data-node-id="{{ $profile->id }}" data-type="node" rows="3" placeholder="Redactá aquí las observaciones globales del Asesor..." style="font-size: 0.9rem; border-radius: 10px;">{{ $profile->comentario_asesor }}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold rounded-pill px-4 btn-guardar-comentario" data-node-id="{{ $profile->id }}" data-type="node">
                        <i class="fa fa-save mr-1"></i> Guardar Dictamen General
                    </button>
                </div>
            </div>
        </div>

        {{-- ── SECCIÓN 2: ESTRUCTURA COMPLETA DEL PLAN CON SUGERENCIAS POR ELEMENTO ── --}}
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="font-weight-bold text-dark mb-0 d-flex align-items-center">
                    <i class="fa fa-sitemap text-primary mr-2" style="font-size: 1.2rem;"></i>
                    <span>Estructura Jerárquica del Plan — Sugerencias por Elemento</span>
                </h5>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0" id="inputBuscarAsesorNode" placeholder="Filtrar por nombre de elemento...">
                    </div>
                </div>
            </div>

            <div class="card-body p-4 bg-light">
                <div id="contenedorArbolAsesor">
                    @foreach($treeNodes as $axi)
                        @php
                            $iniciativasAxi = $iniciativas->where('pei_profile_id', $axi->id);
                        @endphp
                        {{-- NIVEL 1: EJE ESTRATÉGICO --}}
                        <div class="card border mb-4 shadow-xs nodo-asesor-item" data-search="{{ strtolower($axi->name) }}" style="border-radius: 14px; overflow: hidden; border-left: 5px solid #2563eb !important;">
                            <div class="card-header p-3 bg-white d-flex align-items-center justify-content-between cursor-pointer" onclick="$(this).next('.card-body').slideToggle(150);">
                                <div class="d-flex align-items-center flex-grow-1 mr-2" style="gap: 8px; min-width: 0;">
                                    <button type="button" class="btn btn-xs btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:24px; height:24px;">
                                        <i class="fa fa-chevron-down" style="font-size: 0.7rem;"></i>
                                    </button>
                                    <span class="badge badge-primary font-weight-bold px-2 py-1" style="font-size: 0.72rem;">EJE ESTRATÉGICO</span>
                                    <h6 class="font-weight-bold text-dark mb-0 text-truncate" style="font-size: 0.98rem;" title="{{ strip_tags($axi->name) }}">
                                        {{ strip_tags($axi->name) }}
                                    </h6>
                                </div>
                                <span class="badge {{ $axi->comentario_asesor ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2.5 py-1">
                                    <i class="fa {{ $axi->comentario_asesor ? 'fa-comment' : 'fa-comment-o' }} mr-1"></i>
                                    {{ $axi->comentario_asesor ? 'Con Sugerencia' : 'Sin Comentario' }}
                                </span>
                            </div>

                            <div class="card-body p-3 bg-slate-50">
                                {{-- Caja de Comentario para el Eje --}}
                                <div class="p-3 rounded border bg-white mb-3 shadow-xs" style="border-radius: 10px;">
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
                                    <div class="pl-3 border-left ml-2" style="border-color: #cbd5e1 !important;">
                                        @foreach($axi->children as $goal)
                                            <div class="card border mb-3 shadow-xs nodo-asesor-item" data-search="{{ strtolower($goal->name) }}" style="border-radius: 12px; overflow: hidden; border-left: 4px solid #0284c7 !important;">
                                                <div class="card-header p-2.5 bg-white d-flex align-items-center justify-content-between cursor-pointer" onclick="$(this).next('.card-body').slideToggle(150);">
                                                    <div class="d-flex align-items-center flex-grow-1 mr-2" style="gap: 8px; min-width: 0;">
                                                        <span class="badge badge-info font-weight-bold px-2 py-1" style="font-size: 0.68rem;">OBJ. ESPECÍFICO</span>
                                                        <span class="font-weight-bold text-dark small mb-0 text-truncate" title="{{ strip_tags($goal->name) }}">
                                                            {{ strip_tags($goal->name) }}
                                                        </span>
                                                    </div>
                                                    <span class="badge {{ $goal->comentario_asesor ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2 py-1" style="font-size: 0.68rem;">
                                                        <i class="fa {{ $goal->comentario_asesor ? 'fa-comment' : 'fa-comment-o' }} mr-1"></i>
                                                        {{ $goal->comentario_asesor ? 'Con Sugerencia' : 'Sin Comentario' }}
                                                    </span>
                                                </div>

                                                <div class="card-body p-3 bg-white">
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
                                                        <div class="pl-3 border-left ml-2" style="border-color: #e2e8f0 !important;">
                                                            @foreach($goal->children as $action)
                                                                @php
                                                                    $inisDeAccion = $iniciativas->where('pei_profile_id', $action->id);
                                                                @endphp
                                                                <div class="card border mb-3 shadow-xs nodo-asesor-item" data-search="{{ strtolower($action->name) }}" style="border-radius: 10px; border-left: 4px solid #7c3aed !important;">
                                                                    <div class="card-header p-2.5 bg-white d-flex align-items-center justify-content-between cursor-pointer" onclick="$(this).next('.card-body').slideToggle(150);">
                                                                        <div class="d-flex align-items-center flex-grow-1 mr-2" style="gap: 6px; min-width: 0;">
                                                                            <span class="badge badge-purple text-white font-weight-bold px-2 py-1" style="font-size: 0.65rem; background: #7c3aed;">ACC. ESTRATÉGICA</span>
                                                                            <span class="font-weight-bold text-dark small mb-0 text-truncate" title="{{ strip_tags($action->name) }}">
                                                                                {{ strip_tags($action->name) }}
                                                                            </span>
                                                                        </div>
                                                                        <span class="badge {{ $action->comentario_asesor ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2 py-1" style="font-size: 0.65rem;">
                                                                            {{ $action->comentario_asesor ? 'Con Sugerencia' : 'Sin Comentario' }}
                                                                        </span>
                                                                    </div>

                                                                    <div class="card-body p-3 bg-light">
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
                                                                                <div class="font-weight-bold text-dark text-uppercase small mb-2">
                                                                                    <i class="fa fa-tasks text-info mr-1"></i> Acciones Operativas de Mejora Continua ({{ $inisDeAccion->count() }})
                                                                                </div>
                                                                                @foreach($inisDeAccion as $ini)
                                                                                    <div class="p-2.5 rounded border bg-white mb-2 shadow-xs nodo-asesor-item" data-search="{{ strtolower($ini->codigo . ' ' . $ini->accion) }}" style="border-radius: 8px; border-left: 3px solid #10b981 !important;">
                                                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                                                            <div class="d-flex align-items-center" style="gap: 6px;">
                                                                                                <span class="badge badge-dark font-weight-bold" style="font-size: 0.7rem;">{{ $ini->codigo }}</span>
                                                                                                <span class="badge badge-success font-weight-bold" style="font-size: 0.65rem;">{{ $ini->estado_grupo }}</span>
                                                                                                <span class="font-weight-bold text-dark small">{{ $ini->accion }}</span>
                                                                                            </div>
                                                                                            <span class="badge {{ $ini->comentario_asesor ? 'badge-warning text-dark' : 'badge-light border text-muted' }} font-weight-bold px-2 py-0.5" style="font-size: 0.62rem;">
                                                                                                {{ $ini->comentario_asesor ? 'Con Sugerencia' : 'Sin Comentario' }}
                                                                                            </span>
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
                </div>
            </div>
        </div>

    </div>
</div>

<script>
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
        var comentario = $textarea.val();

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
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Guardar Sugerencia');
                if (window.toastr) {
                    toastr.error('Error al guardar la sugerencia.');
                }
            }
        });
    });

    // ── BUSCADOR DE ELEMENTOS EN VISTA ASESOR ──
    $('#inputBuscarAsesorNode').on('keyup', function() {
        var query = $(this).val().toLowerCase().trim();
        if (!query) {
            $('.nodo-asesor-item').show();
            return;
        }
        $('.nodo-asesor-item').each(function() {
            var searchTxt = $(this).data('search') || '';
            if (searchTxt.indexOf(query) >= 0) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

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
