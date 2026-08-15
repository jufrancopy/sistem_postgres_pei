{{-- Colores por cuadrante para los badges de cobertura --}}
@php
$cuadranteColor = ['FO'=>'#1a7a4a','DO'=>'#1a5fa0','FA'=>'#b45309','DA'=>'#9b1c1c'];
$totalEstrategias = count($FOs) + count($DOs) + count($FAs) + count($DAs);
@endphp

{{-- ── Cabecera y Métricas del Cruce de Ambientes ── --}}
<div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%); border-radius: 12px;">
    <div class="card-body p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <h5 class="font-weight-bold text-dark mb-1">
                    <i class="fa fa-random text-warning mr-2"></i>Cruce de Ambientes FODA @if(isset($profile) && $profile) — {{ strip_tags($profile->name) }} @endif
                </h5>
                <div class="text-muted small">
                    Estrategias Integradas FO (Ofensivas) · FA (Defensivas) · DO (Reorientación) · DA (Supervivencia)
                    @if(isset($profile) && $profile && $profile->context)
                        <span class="mx-2">•</span><span class="font-italic">{{ Str::limit(strip_tags($profile->context), 80) }}</span>
                    @endif
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex flex-wrap align-items-center gap-2">
                <span class="badge badge-dark p-2 px-3 mr-2" style="border-radius: 20px; font-size: 0.85rem;">
                    <i class="fa fa-layer-group mr-1 text-warning"></i> {{ $totalEstrategias }} Estrategias
                </span>
                <a href="{{ route('foda-cruce-pdf', $idPerfil) }}" class="btn btn-sm btn-outline-danger shadow-xs" target="_blank" title="Descargar Matriz PDF" style="border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    <i class="fa fa-file-pdf mr-1"></i> Descargar PDF
                </a>
            </div>
        </div>

        {{-- Resumen de Aspectos --}}
        <div class="d-flex flex-wrap mt-3 pt-2 border-top">
            <span class="badge badge-light border text-success mr-2 mb-1 p-2" style="border-radius: 8px;">
                <i class="fa fa-arrow-up mr-1"></i> <strong>{{ count($fortalezas) }}</strong> Fortalezas
            </span>
            <span class="badge badge-light border text-danger mr-2 mb-1 p-2" style="border-radius: 8px;">
                <i class="fa fa-arrow-down mr-1"></i> <strong>{{ count($debilidades) }}</strong> Debilidades
            </span>
            <span class="badge badge-light border text-info mr-2 mb-1 p-2" style="border-radius: 8px;">
                <i class="fa fa-star mr-1"></i> <strong>{{ count($oportunidades) }}</strong> Oportunidades
            </span>
            <span class="badge badge-light border text-warning mr-2 mb-1 p-2 text-dark" style="border-radius: 8px;">
                <i class="fa fa-exclamation-triangle mr-1 text-warning"></i> <strong>{{ count($amenazas) }}</strong> Amenazas
            </span>
        </div>
    </div>
</div>

{{-- ── TABLA PRINCIPAL DEL CRUCE DE AMBIENTES ── --}}
<div class="table-responsive shadow-sm" style="border-radius: 12px; overflow: hidden; background: #fff;">
    <table class="table table-bordered mb-0" style="table-layout:fixed; width:100%; border-collapse: separate; border-spacing: 0;">
        <tbody>
            {{-- ── FILA 1: Encabezado vacío | Fortalezas | Debilidades ── --}}
            <tr>
                {{-- Celda vacía esquina --}}
                <td style="width:20%; background:#f8fafc; border-color:#e2e8f0; vertical-align: middle; padding: 12px;">
                    @if(isset($profile) && $profile)
                    <div style="font-size:.8rem; color:#475569">
                        <div class="font-weight-bold text-dark mb-1"><i class="fa fa-building text-secondary mr-1"></i>{{ strip_tags($profile->name) }}</div>
                        <small class="text-muted">Matriz FODA Integrada</small>
                    </div>
                    @endif
                </td>

                {{-- Fortalezas --}}
                <td style="width:40%; background:#f0fdf4; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#16a34a; padding:8px 14px; font-weight:700; font-size:.82rem">
                        <span><i class="fa fa-arrow-up mr-1"></i> Fortalezas</span>
                        <span class="badge badge-light text-success font-weight-bold">{{ count($fortalezas) }}</span>
                    </div>
                    <div style="padding:10px 14px;">
                        @forelse($fortalezas as $v)
                        @php $cuadrantes = $fortalezasCubiertas[$v->aspecto->id] ?? []; @endphp
                        <div class="d-flex align-items-start mb-1">
                            <span class="badge badge-success mr-2 mt-1 flex-shrink-0" style="font-size:.68rem; min-width:26px" title="{{ $v->aspecto->name }}">F{{ $v->aspecto->id }}</span>
                            <div style="font-size:.78rem; color:#374151; line-height:1.4">{{ $v->aspecto->name }}
                                @foreach($cuadrantes as $q)
                                @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                <span class="badge ml-1" style="font-size:.58rem; background:{{ $qc }}; color:#fff" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <small class="text-muted font-italic">Sin fortalezas registradas</small>
                        @endforelse
                    </div>
                </td>

                {{-- Debilidades --}}
                <td style="width:40%; background:#fef2f2; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#dc2626; padding:8px 14px; font-weight:700; font-size:.82rem">
                        <span><i class="fa fa-arrow-down mr-1"></i> Debilidades</span>
                        <span class="badge badge-light text-danger font-weight-bold">{{ count($debilidades) }}</span>
                    </div>
                    <div style="padding:10px 14px;">
                        @forelse($debilidades as $v)
                        @php $cuadrantes = $debilidadesCubiertas[$v->aspecto->id] ?? []; @endphp
                        <div class="d-flex align-items-start mb-1">
                            <span class="badge badge-danger mr-2 mt-1 flex-shrink-0" style="font-size:.68rem; min-width:26px" title="{{ $v->aspecto->name }}">D{{ $v->aspecto->id }}</span>
                            <div style="font-size:.78rem; color:#374151; line-height:1.4">{{ $v->aspecto->name }}
                                @if($v->causa_raiz)
                                <span class="badge badge-warning ml-1 text-dark" style="font-size:.58rem" title="{{ \App\Admin\Planificacion\Foda\FodaAnalisis::CAUSAS_RAIZ[$v->causa_raiz] ?? '' }}"><i class="fa fa-shield-alt"></i></span>
                                @endif
                                @foreach($cuadrantes as $q)
                                @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                <span class="badge ml-1" style="font-size:.58rem; background:{{ $qc }}; color:#fff" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <small class="text-muted font-italic">Sin debilidades registradas</small>
                        @endforelse
                    </div>
                </td>
            </tr>

            {{-- ── FILA 2: Oportunidades | FO | DO ── --}}
            <tr>
                {{-- Oportunidades --}}
                <td style="background:#f0fdfa; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#0891b2; padding:8px 14px; font-weight:700; font-size:.82rem">
                        <span><i class="fa fa-star mr-1"></i> Oportunidades</span>
                        <span class="badge badge-light text-info font-weight-bold">{{ count($oportunidades) }}</span>
                    </div>
                    <div style="padding:10px 14px;">
                        @forelse($oportunidades as $v)
                        @php $cuadrantes = $oportunidadesCubiertas[$v->aspecto->id] ?? []; @endphp
                        <div class="d-flex align-items-start mb-1">
                            <span class="badge badge-info mr-2 mt-1 flex-shrink-0" style="font-size:.68rem; min-width:26px" title="{{ $v->aspecto->name }}">O{{ $v->aspecto->id }}</span>
                            <div style="font-size:.78rem; color:#374151; line-height:1.4">{{ $v->aspecto->name }}
                                @foreach($cuadrantes as $q)
                                @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                <span class="badge ml-1" style="font-size:.58rem; background:{{ $qc }}; color:#fff" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <small class="text-muted font-italic">Sin oportunidades registradas</small>
                        @endforelse
                    </div>
                </td>

                {{-- FO --}}
                <td style="background:#f0fff4; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#15803d; padding:8px 14px">
                        <span style="font-weight:700; font-size:.82rem">
                            FO — Estrategias Ofensivas
                            <span class="badge badge-light text-success ml-1">{{ count($FOs) }}</span>
                        </span>
                        <button type="button" class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="FO"
                                style="background:rgba(255,255,255,.25); color:#fff; width:26px; height:26px; padding:0; line-height:26px; text-align:center; border:none;"
                                title="Agregar estrategia FO">
                            <i class="fa fa-plus" style="font-size:.75rem"></i>
                        </button>
                    </div>
                    <div style="padding:10px 14px;" id="contenedorEstrategiasFO">
                        @forelse($FOs as $vi)
                        <div class="mb-2 pb-2 border-bottom foda-cruce-item" id="cruce-item-{{ $vi->id }}" style="font-size:.82rem">
                            <div class="mb-1">
                                @foreach($vi->fortalezas as $b)
                                <span class="badge badge-success mr-1" style="font-size:.65rem" title="{{ $b->name }}">F{{ $b->id }}</span>
                                @endforeach
                                @foreach($vi->oportunidades as $b)
                                <span class="badge badge-info mr-1" style="font-size:.65rem" title="{{ $b->name }}">O{{ $b->id }}</span>
                                @endforeach
                            </div>
                            <div style="color:#1e293b; line-height:1.4">{{ $vi->estrategia }}</div>
                            <div class="mt-1 d-flex align-items-center">
                                <button type="button" class="btn btn-circle btn-info btnEditarEstrategia mr-1"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" title="Editar estrategia">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-circle btn-danger btnEliminarEstrategiaAjax"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" data-texto="{{ Str::limit($vi->estrategia, 40) }}" title="Eliminar estrategia">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted small font-italic mb-0 py-2">Sin estrategias FO registradas</p>
                        @endforelse
                    </div>
                </td>

                {{-- DO --}}
                <td style="background:#eff6ff; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#1d4ed8; padding:8px 14px">
                        <span style="font-weight:700; font-size:.82rem">
                            DO — Estrategias de Reorientación
                            <span class="badge badge-light text-primary ml-1">{{ count($DOs) }}</span>
                        </span>
                        <button type="button" class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="DO"
                                style="background:rgba(255,255,255,.25); color:#fff; width:26px; height:26px; padding:0; line-height:26px; text-align:center; border:none;"
                                title="Agregar estrategia DO">
                            <i class="fa fa-plus" style="font-size:.75rem"></i>
                        </button>
                    </div>
                    <div style="padding:10px 14px;" id="contenedorEstrategiasDO">
                        @forelse($DOs as $vi)
                        <div class="mb-2 pb-2 border-bottom foda-cruce-item" id="cruce-item-{{ $vi->id }}" style="font-size:.82rem">
                            <div class="mb-1">
                                @foreach($vi->debilidades as $b)
                                <span class="badge badge-danger mr-1" style="font-size:.65rem" title="{{ $b->name }}">D{{ $b->id }}</span>
                                @endforeach
                                @foreach($vi->oportunidades as $b)
                                <span class="badge badge-info mr-1" style="font-size:.65rem" title="{{ $b->name }}">O{{ $b->id }}</span>
                                @endforeach
                            </div>
                            <div style="color:#1e293b; line-height:1.4">{{ $vi->estrategia }}</div>
                            <div class="mt-1 d-flex align-items-center">
                                <button type="button" class="btn btn-circle btn-info btnEditarEstrategia mr-1"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" title="Editar estrategia">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-circle btn-danger btnEliminarEstrategiaAjax"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" data-texto="{{ Str::limit($vi->estrategia, 40) }}" title="Eliminar estrategia">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted small font-italic mb-0 py-2">Sin estrategias DO registradas</p>
                        @endforelse
                    </div>
                </td>
            </tr>

            {{-- ── FILA 3: Amenazas | FA | DA ── --}}
            <tr>
                {{-- Amenazas --}}
                <td style="background:#fffbeb; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#d97706; padding:8px 14px; font-weight:700; font-size:.82rem">
                        <span><i class="fa fa-exclamation-triangle mr-1"></i> Amenazas</span>
                        <span class="badge badge-light text-warning font-weight-bold text-dark">{{ count($amenazas) }}</span>
                    </div>
                    <div style="padding:10px 14px;">
                        @forelse($amenazas as $v)
                        @php $cuadrantes = $amenazasCubiertas[$v->aspecto->id] ?? []; @endphp
                        <div class="d-flex align-items-start mb-1">
                            <span class="badge badge-warning mr-2 mt-1 flex-shrink-0 text-dark font-weight-bold" style="font-size:.68rem; min-width:26px" title="{{ $v->aspecto->name }}">A{{ $v->aspecto->id }}</span>
                            <div style="font-size:.78rem; color:#374151; line-height:1.4">{{ $v->aspecto->name }}
                                @if($v->causa_raiz)
                                <span class="badge badge-warning ml-1 text-dark" style="font-size:.58rem" title="{{ \App\Admin\Planificacion\Foda\FodaAnalisis::CAUSAS_RAIZ[$v->causa_raiz] ?? '' }}"><i class="fa fa-shield-alt"></i></span>
                                @endif
                                @foreach($cuadrantes as $q)
                                @php $qc = $cuadranteColor[$q] ?? '#666'; @endphp
                                <span class="badge ml-1" style="font-size:.58rem; background:{{ $qc }}; color:#fff" title="Cubierto en {{ $q }}">{{ $q }}</span>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <small class="text-muted font-italic">Sin amenazas registradas</small>
                        @endforelse
                    </div>
                </td>

                {{-- FA --}}
                <td style="background:#fff7ed; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#c2410c; padding:8px 14px">
                        <span style="font-weight:700; font-size:.82rem">
                            FA — Estrategias Defensivas
                            <span class="badge badge-light text-warning ml-1 text-dark">{{ count($FAs) }}</span>
                        </span>
                        <button type="button" class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="FA"
                                style="background:rgba(255,255,255,.25); color:#fff; width:26px; height:26px; padding:0; line-height:26px; text-align:center; border:none;"
                                title="Agregar estrategia FA">
                            <i class="fa fa-plus" style="font-size:.75rem"></i>
                        </button>
                    </div>
                    <div style="padding:10px 14px;" id="contenedorEstrategiasFA">
                        @forelse($FAs as $vi)
                        <div class="mb-2 pb-2 border-bottom foda-cruce-item" id="cruce-item-{{ $vi->id }}" style="font-size:.82rem">
                            <div class="mb-1">
                                @foreach($vi->fortalezas as $b)
                                <span class="badge badge-success mr-1" style="font-size:.65rem" title="{{ $b->name }}">F{{ $b->id }}</span>
                                @endforeach
                                @foreach($vi->amenazas as $b)
                                <span class="badge badge-warning mr-1 text-dark font-weight-bold" style="font-size:.65rem" title="{{ $b->name }}">A{{ $b->id }}</span>
                                @endforeach
                            </div>
                            <div style="color:#1e293b; line-height:1.4">{{ $vi->estrategia }}</div>
                            <div class="mt-1 d-flex align-items-center">
                                <button type="button" class="btn btn-circle btn-info btnEditarEstrategia mr-1"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" title="Editar estrategia">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-circle btn-danger btnEliminarEstrategiaAjax"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" data-texto="{{ Str::limit($vi->estrategia, 40) }}" title="Eliminar estrategia">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted small font-italic mb-0 py-2">Sin estrategias FA registradas</p>
                        @endforelse
                    </div>
                </td>

                {{-- DA --}}
                <td style="background:#fdf2f2; vertical-align:top; padding:0; border-color:#e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between text-white" style="background:#991b1b; padding:8px 14px">
                        <span style="font-weight:700; font-size:.82rem">
                            DA — Estrategias de Supervivencia
                            <span class="badge badge-light text-danger ml-1">{{ count($DAs) }}</span>
                        </span>
                        <button type="button" class="btn btn-circle btn-sm btnAgregarEstrategia" data-tipo="DA"
                                style="background:rgba(255,255,255,.25); color:#fff; width:26px; height:26px; padding:0; line-height:26px; text-align:center; border:none;"
                                title="Agregar estrategia DA">
                            <i class="fa fa-plus" style="font-size:.75rem"></i>
                        </button>
                    </div>
                    <div style="padding:10px 14px;" id="contenedorEstrategiasDA">
                        @forelse($DAs as $vi)
                        <div class="mb-2 pb-2 border-bottom foda-cruce-item" id="cruce-item-{{ $vi->id }}" style="font-size:.82rem">
                            <div class="mb-1">
                                @foreach($vi->debilidades as $b)
                                <span class="badge badge-danger mr-1" style="font-size:.65rem" title="{{ $b->name }}">D{{ $b->id }}</span>
                                @endforeach
                                @foreach($vi->amenazas as $b)
                                <span class="badge badge-warning mr-1 text-dark font-weight-bold" style="font-size:.65rem" title="{{ $b->name }}">A{{ $b->id }}</span>
                                @endforeach
                            </div>
                            <div style="color:#1e293b; line-height:1.4">{{ $vi->estrategia }}</div>
                            <div class="mt-1 d-flex align-items-center">
                                <button type="button" class="btn btn-circle btn-info btnEditarEstrategia mr-1"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" title="Editar estrategia">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-circle btn-danger btnEliminarEstrategiaAjax"
                                        style="width:24px; height:24px; padding:0; line-height:24px; font-size:.65rem"
                                        data-id="{{ $vi->id }}" data-texto="{{ Str::limit($vi->estrategia, 40) }}" title="Eliminar estrategia">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted small font-italic mb-0 py-2">Sin estrategias DA registradas</p>
                        @endforelse
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- ══ MODAL INTERNO: Crear / Editar Estrategia FODA ══ --}}
<div class="modal fade" id="modalEstrategia" tabindex="-1" role="dialog" aria-labelledby="modalEstrategiaTitulo" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
            {{-- Header dinámico según tipo --}}
            <div class="modal-header py-3 text-white d-flex align-items-center justify-content-between" id="modalEstrategiaHeader" style="background: #1e293b;">
                <div>
                    <h5 class="modal-title mb-0 text-white font-weight-bold" id="modalEstrategiaTitulo"></h5>
                    <small class="text-white-50" id="modalEstrategiaSubtitulo"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4 bg-white">
                <form id="formEstrategia">
                    <input type="hidden" name="perfil_id" value="{{ $idPerfil }}">
                    <input type="hidden" name="user_id"   value="{{ auth()->id() }}">
                    <input type="hidden" name="tipo"      id="estrategiaTipo">

                    <div class="row">
                        {{-- Columna izquierda: checkboxes --}}
                        <div class="col-md-5 border-right">
                            <div id="checkboxesGrupo1" class="mb-3">
                                <label class="font-weight-bold mb-2 text-dark" id="labelGrupo1" style="font-size:.82rem"></label>
                                <div id="listaGrupo1" class="pl-1" style="max-height:170px; overflow-y:auto"></div>
                            </div>
                            <div id="checkboxesGrupo2">
                                <label class="font-weight-bold mb-2 text-dark" id="labelGrupo2" style="font-size:.82rem"></label>
                                <div id="listaGrupo2" class="pl-1" style="max-height:170px; overflow-y:auto"></div>
                            </div>
                        </div>

                        {{-- Columna derecha: estrategia --}}
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark" style="font-size:.82rem">
                                    Enunciado de la Estrategia <span class="text-danger">*</span>
                                </label>
                                <textarea name="estrategia" id="estrategiaTexto"
                                    class="form-control" rows="5"
                                    style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem;"
                                    placeholder="Redactá la estrategia de forma concreta y accionable...&#10;&#10;Ej: Aprovechar la capacidad tecnológica instalada (F2) para capturar el crecimiento del mercado digital (O1)..."></textarea>
                                <small class="text-muted">
                                    Referenciá los aspectos seleccionados usando F#, O#, D#, A#
                                </small>
                            </div>

                            {{-- Indicador de aspectos seleccionados --}}
                            <div id="seleccionIndicador" class="mt-2" style="min-height:28px"></div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light p-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-secondary btn-round px-3" data-dismiss="modal">Cancelar</button>
                <div>
                    <button type="button" class="btn btn-outline-info btn-round px-3 mr-2" id="btnGenerarIA" title="Generar con IA (Groq / Llama 3)">
                        <i class="fa fa-magic mr-1"></i> Generar con IA
                    </button>
                    <button type="button" class="btn btn-success btn-round px-4" id="btnGuardarEstrategia">
                        <i class="fa fa-save mr-1"></i> Guardar Estrategia
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Script de Interacción FODA Cruce de Ambientes ── --}}
<script>
(function() {
    var aspectos = {
        fortalezas:   @json($fortalezas->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
        debilidades:  @json($debilidades->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
        oportunidades:@json($oportunidades->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
        amenazas:     @json($amenazas->map(fn($v) => ['id' => $v->aspecto->id, 'name' => $v->aspecto->name])->values()),
    };

    var cubiertos = {
        fortaleza:   @json($fortalezasCubiertas),
        debilidad:   @json($debilidadesCubiertas),
        oportunidad: @json($oportunidadesCubiertas),
        amenaza:     @json($amenazasCubiertas),
    };

    var config = {
        FO: { titulo: 'Nueva Estrategia Ofensiva (FO)', sub: 'Fortalezas × Oportunidades', color: '#16a34a',
              g1: { label: 'Fortalezas', name: 'fortaleza_id[]',   prefix: 'F', color: 'success', data: aspectos.fortalezas },
              g2: { label: 'Oportunidades', name: 'oportunidad_id[]', prefix: 'O', color: 'info',    data: aspectos.oportunidades } },
        DO: { titulo: 'Nueva Estrategia de Reorientación (DO)', sub: 'Debilidades × Oportunidades', color: '#1d4ed8',
              g1: { label: 'Debilidades', name: 'debilidad_id[]',   prefix: 'D', color: 'danger', data: aspectos.debilidades },
              g2: { label: 'Oportunidades', name: 'oportunidad_id[]', prefix: 'O', color: 'info',   data: aspectos.oportunidades } },
        FA: { titulo: 'Nueva Estrategia Defensiva (FA)', sub: 'Fortalezas × Amenazas', color: '#c2410c',
              g1: { label: 'Fortalezas', name: 'fortaleza_id[]',   prefix: 'F', color: 'success', data: aspectos.fortalezas },
              g2: { label: 'Amenazas',   name: 'amenaza_id[]',     prefix: 'A', color: 'warning', data: aspectos.amenazas } },
        DA: { titulo: 'Nueva Estrategia de Supervivencia (DA)', sub: 'Debilidades × Amenazas', color: '#991b1b',
              g1: { label: 'Debilidades', name: 'debilidad_id[]', prefix: 'D', color: 'danger',  data: aspectos.debilidades },
              g2: { label: 'Amenazas',    name: 'amenaza_id[]',   prefix: 'A', color: 'warning', data: aspectos.amenazas } },
    };

    function renderCheckboxes(selector, grupo) {
        var $lista = $(selector).empty();
        if (!grupo.data || grupo.data.length === 0) {
            $lista.html('<small class="text-muted font-italic">Sin aspectos disponibles</small>');
            return;
        }

        var clave = grupo.name.replace('_id[]', '');
        var mapaCubiertos = cubiertos[clave] || {};
        var qColor = { FO: '#1a7a4a', DO: '#1a5fa0', FA: '#b45309', DA: '#9b1c1c' };

        grupo.data.forEach(function(asp) {
            var cuadrantesAsp = mapaCubiertos[asp.id] || [];
            var $label = $('<label class="d-flex align-items-center mb-2" style="cursor:pointer; font-size:.82rem">').append(
                $('<input type="checkbox" class="mr-2 aspecto-check">').attr('name', grupo.name).val(asp.id),
                $('<span class="badge badge-' + grupo.color + ' mr-2" style="font-size:.68rem; min-width:26px">')
                    .text(grupo.prefix + asp.id),
                $('<span style="color:#374151; flex:1">').text(asp.name)
            );

            if (cuadrantesAsp.length > 0) {
                var $badges = $('<span class="ml-1">');
                cuadrantesAsp.forEach(function(q) {
                    $badges.append(
                        $('<span class="badge mr-1" style="font-size:.6rem; color:#fff; background:' + (qColor[q]||'#666') + '">')
                            .attr('title', 'Ya cubierto en ' + q)
                            .text(q)
                    );
                });
                $label.append($badges);
                $label.css('opacity', '0.75');
            }

            $lista.append($label);
        });
    }

    // Eventos
    $(document).off('click', '.btnAgregarEstrategia').on('click', '.btnAgregarEstrategia', function(e) {
        e.preventDefault();
        var tipo = $(this).data('tipo');
        var cfg  = config[tipo];

        $('#modalEstrategiaHeader').css('background', cfg.color);
        $('#modalEstrategiaTitulo').text(cfg.titulo);
        $('#modalEstrategiaSubtitulo').text(cfg.sub);
        $('#estrategiaTipo').val(tipo);
        $('#estrategiaTexto').val('');
        $('#seleccionIndicador').empty();
        $('#formEstrategia').data('editId', null).data('modo', 'crear');

        $('#labelGrupo1').html('<i class="fa fa-check-square mr-1"></i>' + cfg.g1.label);
        renderCheckboxes('#listaGrupo1', cfg.g1);

        $('#labelGrupo2').html('<i class="fa fa-check-square mr-1"></i>' + cfg.g2.label);
        renderCheckboxes('#listaGrupo2', cfg.g2);

        $('#modalEstrategia').modal('show');
    });

    $(document).off('change', '.aspecto-check').on('change', '.aspecto-check', function() {
        var badges = [];
        $('#formEstrategia input[type=checkbox]:checked').each(function() {
            var name = $(this).attr('name');
            var val  = $(this).val();
            var prefix = name.startsWith('fortaleza') ? 'F' :
                         name.startsWith('debilidad')  ? 'D' :
                         name.startsWith('oportunidad')? 'O' : 'A';
            var color  = name.startsWith('fortaleza') ? 'success' :
                         name.startsWith('debilidad')  ? 'danger' :
                         name.startsWith('oportunidad')? 'info' : 'warning';
            badges.push('<span class="badge badge-' + color + ' mr-1">' + prefix + val + '</span>');
        });
        $('#seleccionIndicador').html(
            badges.length > 0
                ? '<small class="text-muted font-weight-bold">Seleccionados: </small>' + badges.join('')
                : ''
        );
    });

    // Guardar Estrategia (Crear / Editar)
    $('#btnGuardarEstrategia').off('click').on('click', function() {
        var estrategia = $('#estrategiaTexto').val().trim();
        if (!estrategia) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Atención', 'Escribí el enunciado de la estrategia.', 'warning');
            } else if (typeof toastr !== 'undefined') {
                toastr.warning('Escribí el enunciado de la estrategia.');
            }
            $('#estrategiaTexto').focus();
            return;
        }

        var btn    = $(this);
        var editId = $('#formEstrategia').data('editId');
        var modo   = $('#formEstrategia').data('modo') || 'crear';

        btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        var url  = modo === 'editar'
            ? '{{ url('foda-cruce-ambientes') }}/' + editId
            : '{{ route('foda-cruce-ambientes.store') }}';
        var type = modo === 'editar' ? 'PUT' : 'POST';

        $.ajax({
            url:  url,
            type: type,
            data: $('#formEstrategia').serialize(),
            success: function(resp) {
                $('#modalEstrategia').modal('hide');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: modo === 'editar' ? 'Estrategia actualizada' : 'Estrategia guardada',
                        showConfirmButton: false,
                        timer: 1200
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr.success(modo === 'editar' ? 'Estrategia actualizada.' : 'Estrategia guardada.');
                }
                // Si estamos dentro del modal de FODA crossing en el Coordinador Dashboard, recargarlo
                if (typeof window.recargarModalFodaCrossing === 'function') {
                    window.recargarModalFodaCrossing();
                } else {
                    setTimeout(function() { location.reload(); }, 800);
                }
            },
            error: function(xhr) {
                var err = xhr.responseJSON?.message || 'Error al guardar la estrategia.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', err, 'error');
                } else if (typeof toastr !== 'undefined') {
                    toastr.error(err);
                }
            },
            complete: function() {
                btn.html('<i class="fa fa-save mr-1"></i> Guardar Estrategia').prop('disabled', false);
            }
        });
    });

    // Editar estrategia
    $(document).off('click', '.btnEditarEstrategia').on('click', '.btnEditarEstrategia', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.get('{{ url('foda-cruce-ambientes') }}/' + id + '/edit', function(data) {
            var tipo = data.tipo;
            var cfg  = config[tipo];

            $('#modalEstrategiaHeader').css('background', cfg.color);
            $('#modalEstrategiaTitulo').text('Editar Estrategia ' + tipo);
            $('#modalEstrategiaSubtitulo').text(cfg.sub);
            $('#estrategiaTipo').val(tipo);
            $('#estrategiaTexto').val(data.estrategia);
            $('#seleccionIndicador').empty();
            $('#formEstrategia').data('editId', id);
            $('#formEstrategia').data('modo', 'editar');

            renderCheckboxes('#listaGrupo1', cfg.g1);
            renderCheckboxes('#listaGrupo2', cfg.g2);

            setTimeout(function() {
                var map = { fortaleza: data.fortalezas, debilidad: data.debilidades,
                            oportunidad: data.oportunidades, amenaza: data.amenazas };

                $('#listaGrupo1 input[type=checkbox], #listaGrupo2 input[type=checkbox]').each(function() {
                    var fieldName = $(this).attr('name').replace('_id[]','');
                    var ids = map[fieldName] || [];
                    if (ids.includes(parseInt($(this).val()))) $(this).prop('checked', true);
                });
                $('#listaGrupo1 input, #listaGrupo2 input').trigger('change');
            }, 50);

            $('#modalEstrategia').modal('show');
        });
    });

    // Eliminar estrategia via AJAX
    $(document).off('click', '.btnEliminarEstrategiaAjax').on('click', '.btnEliminarEstrategiaAjax', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var texto = $(this).data('texto') || 'esta estrategia';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Eliminar estrategia?',
                text: '"' + texto + '"',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar'
            }).then(function(r) {
                if (r.isConfirmed) {
                    $.ajax({
                        url: '{{ url('foda-cruce-ambientes') }}/' + id,
                        type: 'DELETE',
                        success: function() {
                            $('#cruce-item-' + id).fadeOut(300, function() { $(this).remove(); });
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: 'Estrategia eliminada correctamente.',
                                timer: 1000,
                                showConfirmButton: false
                            });
                            if (typeof window.recargarModalFodaCrossing === 'function') {
                                setTimeout(window.recargarModalFodaCrossing, 400);
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo eliminar.', 'error');
                        }
                    });
                }
            });
        }
    });

    // Generar con IA
    $('#btnGenerarIA').off('click').on('click', function() {
        var tipo = $('#estrategiaTipo').val();
        var cfg  = config[tipo];

        var grupo1 = [];
        var grupo2 = [];

        $('#listaGrupo1 input[type=checkbox]:checked').each(function() {
            var id   = $(this).val();
            var name = $(this).closest('label').find('span:last').text().trim();
            grupo1.push({ id: id, name: name, prefix: cfg.g1.prefix });
        });

        $('#listaGrupo2 input[type=checkbox]:checked').each(function() {
            var id   = $(this).val();
            var name = $(this).closest('label').find('span:last').text().trim();
            grupo2.push({ id: id, name: name, prefix: cfg.g2.prefix });
        });

        if (grupo1.length === 0 || grupo2.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Atención', 'Seleccioná al menos un aspecto de cada grupo antes de generar.', 'info');
            } else if (typeof toastr !== 'undefined') {
                toastr.warning('Seleccioná al menos un aspecto de cada grupo antes de generar.');
            }
            return;
        }

        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando...').prop('disabled', true);
        $('#estrategiaTexto').val('').attr('placeholder', '⏳ Redactando estrategia con IA...');

        $.ajax({
            url: '{{ route('foda-cruce-ambientes.ia') }}',
            type: 'POST',
            data: {
                tipo:      tipo,
                grupo1:    grupo1,
                grupo2:    grupo2,
                perfil_id: $('input[name=perfil_id]').val(),
            },
            success: function(resp) {
                if (resp.estrategia) {
                    $('#estrategiaTexto').val(resp.estrategia);
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Estrategia generada con IA. Podés ajustarla antes de guardar.');
                    }
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.error || 'Error al conectar con el servicio de IA.';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                }
                $('#estrategiaTexto').attr('placeholder', 'Escribí la estrategia manualmente...');
            },
            complete: function() {
                btn.html('<i class="fa fa-magic mr-1"></i> Generar con IA').prop('disabled', false);
            }
        });
    });
})();
</script>
