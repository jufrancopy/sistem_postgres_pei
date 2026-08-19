<style>
.btn-icon-action-edit {
    background: #f3e8ff !important;
    color: #7e22ce !important;
    border: 1px solid #e9d5ff !important;
    width: 28px;
    height: 28px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px rgba(126, 34, 206, 0.08);
}
.btn-icon-action-edit:hover {
    background: #7e22ce !important;
    color: #ffffff !important;
    border-color: #7e22ce !important;
    transform: translateY(-1.5px);
    box-shadow: 0 4px 10px rgba(126, 34, 206, 0.25);
}

.btn-icon-action-delete {
    background: #ffe4e6 !important;
    color: #e11d48 !important;
    border: 1px solid #fecdd3 !important;
    width: 28px;
    height: 28px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px rgba(225, 29, 72, 0.08);
}
.btn-icon-action-delete:hover {
    background: #e11d48 !important;
    color: #ffffff !important;
    border-color: #e11d48 !important;
    transform: translateY(-1.5px);
    box-shadow: 0 4px 10px rgba(225, 29, 72, 0.25);
}

@keyframes highlightGreenPulse {
    0% {
        background-color: #d1fae5 !important;
        border-color: #059669 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.35);
    }
    50% {
        background-color: #ecfdf5 !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2);
    }
    100% {
        background-color: #ffffff !important;
        box-shadow: none;
    }
}
.highlight-target-edited {
    animation: highlightGreenPulse 2.5s ease-out forwards;
}
</style>
<div>
    @foreach ($profile->children->sortBy('order_item') as $axi)
    @php
        $marcosAxi      = $axi->marcos;
        $coloresInline  = ['pnd'=>'danger','ods'=>'success','bsc'=>'primary','mecip'=>'warning','pgn'=>'dark','general'=>'secondary'];
        $marcosPopover  = $marcosAxi->map(fn($m) => '<span class="badge badge-'.($coloresInline[$m->tipo]??'secondary').' mr-1">'.e($m->nombre).'</span>')->implode(' ');

        $estrategiasAxi   = $axi->strategies;
        $countEstrategias = $estrategiasAxi->count();
        $coloresEstrategia = ['fo'=>'success','fa'=>'warning','do'=>'info','da'=>'danger'];
        $estrategiasPopover = $estrategiasAxi->map(function($s) use ($coloresEstrategia) {
            $tipo   = strtolower($s->tipo ?? 'fo');
            $color  = $coloresEstrategia[$tipo] ?? 'secondary';
            $label  = strtoupper($s->tipo ?? '');
            $texto  = e(\Illuminate\Support\Str::limit(strip_tags($s->estrategia), 80));
            return '<div class="mb-1"><span class="badge badge-'.$color.' mr-1" style="font-size:.65rem">'.$label.'</span><span style="font-size:.78rem">'.$texto.'</span></div>';
        })->implode('');

        $bscPerspectiva = $axi->bsc_perspectiva;
        $bscLabels = \App\Admin\Planificacion\Pei\PeiProfile::BSC_PERSPECTIVAS;
        $bscColores = [
            'financiera'  => ['badge' => 'badge-success',  'icon' => 'fa-dollar-sign'],
            'clientes'    => ['badge' => 'badge-info',     'icon' => 'fa-users'],
            'procesos'    => ['badge' => 'badge-warning',  'icon' => 'fa-cogs'],
            'aprendizaje' => ['badge' => 'badge-primary',  'icon' => 'fa-graduation-cap'],
        ];

        $totalAcciones  = $axi->descendants()->where('level','action')->count();
    @endphp

    <div class="mb-2" id="axi-{{ $axi->id }}">
        <div class="card shadow-sm border-0">

            {{-- Header Objetivo --}}
            <div class="card-header py-2 px-3"
                 style="background:linear-gradient(135deg,#1a237e 0%,#283593 100%); border-radius:.5rem .5rem 0 0;">
                {{-- Fila 1: texto --}}
                <div class="d-flex align-items-center">
                    <button class="btn btn-link text-white text-left p-0 font-weight-bold w-100"
                            type="button" data-toggle="collapse"
                            data-target="#col{{ str_replace('-', '', $axi->id) }}"
                            aria-expanded="false"
                            style="font-size:.95rem; text-decoration:none; white-space:normal; word-break:break-word;">
                        <i class="fa fa-bullseye mr-2"></i>
                        <span class="badge badge-light text-dark mr-2" style="font-size:.7rem">{{ $niveles['axi'] ?? 'Objetivo' }}</span>
                        {!! $axi->name !!}
                    </button>
                </div>
                {{-- Fila 2: botones --}}
                <div class="d-flex align-items-center mt-1 flex-wrap" style="gap:.3rem">
                    <button type="button" class="btn btn-sm btn-outline-light py-0 px-2"
                            onclick="event.stopPropagation(); openChatWithContext('PeiObjective', '{{ $axi->id }}', 'Objetivo: {{ e(strip_tags($axi->name)) }}', '{{ url()->current() }}#axi-{{ $axi->id }}')"
                            title="Consultar sobre este objetivo en el chat">
                        <i class="fa fa-comment-dots mr-1" style="font-size:.75rem"></i> Consultar
                    </button>
                    <span class="badge badge-light text-dark" title="{{ $totalAcciones }} acción(es)">
                        <i class="fa fa-rocket mr-1"></i>{{ $totalAcciones }}
                    </span>
                    @if($marcosAxi->count() > 0)
                        @php 
                            $badgeStyles = [
                                'pnd'     => 'background:#dc3545; color:#fff;',
                                'ods'     => 'background:#10b981; color:#fff;',
                                'bsc'     => 'background:#3b82f6; color:#fff;',
                                'mecip'   => 'background:#f59e0b; color:#1e293b; font-weight:700;',
                                'pgn'     => 'background:#1e293b; color:#fff;',
                                'general' => 'background:#64748b; color:#fff;'
                            ];
                        @endphp
                        @foreach($marcosAxi->groupBy('tipo') as $tipo => $items)
                            @foreach($items as $marco)
                                @php
                                    $odsVal = (preg_match('/(\d+)/', $marco->nombre, $matches)) ? (int)$matches[1] : 3;
                                @endphp
                                <span class="badge shadow-xs mr-1" 
                                      style="{{ $badgeStyles[$tipo] ?? 'background:#64748b; color:#fff;' }} font-size:.68rem; padding: 4px 8px; border-radius: 6px; cursor: pointer;"
                                      title="Haz clic para ver ideas e inspiración de las Naciones Unidas (ODS {{ $odsVal }})"
                                      onclick="event.stopPropagation(); abrirModalInspiracionOds({{ $odsVal }});">
                                    {{ $marco->nombre }}
                                </span>
                            @endforeach
                        @endforeach
                    @endif
                    @if($countEstrategias > 0)
                    <a href="javascript:void(0)"
                       id="estrategias-pill-{{ $axi->id }}"
                       class="badge badge-warning text-dark"
                       data-toggle="popover" data-trigger="click" data-placement="right"
                       data-html="true" data-title="Estrategias FODA vinculadas"
                       data-content="{{ $estrategiasPopover }}">
                        <i class="fa fa-chess mr-1"></i>{{ $countEstrategias }}
                    </a>
                    @endif
                    @php
                        $comentariosAxi = isset($comentariosAsesoria) ? (
                            $comentariosAsesoria->get('node_' . $axi->id)
                            ?? $comentariosAsesoria->get('axi_' . $axi->id)
                            ?? $comentariosAsesoria->get($axi->id)
                            ?? collect()
                        ) : collect();

                        if (!empty($axi->comentario_asesor) && $comentariosAxi->where('comentario', $axi->comentario_asesor)->isEmpty()) {
                            $dummy = (object)[
                                'id' => null,
                                'comentario' => $axi->comentario_asesor,
                                'estado' => 'PENDIENTE',
                                'created_at' => $axi->updated_at,
                                'asesoria' => (object)['nombre' => 'Asesor Técnico', 'institucion' => 'Asesoría Remota']
                            ];
                            $comentariosAxi = $comentariosAxi->concat([$dummy]);
                        }
                    @endphp
                    @if($comentariosAxi->count() > 0)
                    @php
                        $comentariosAxiArray = $comentariosAxi->map(fn($c) => [
                            'id' => $c->id ?? null,
                            'asesor' => $c->asesoria->nombre ?? 'Asesor Externo',
                            'institucion' => $c->asesoria->institucion ?? 'Asesoría Técnica',
                            'comentario' => $c->comentario ?? '',
                            'estado' => $c->estado ?? 'PENDIENTE',
                            'fecha' => isset($c->created_at) && $c->created_at ? (is_string($c->created_at) ? $c->created_at : $c->created_at->format('d/m/Y H:i')) : ''
                        ])->values()->all();
                        $comentariosAxiJson = base64_encode(json_encode($comentariosAxiArray));
                    @endphp
                    <button type="button" class="btn btn-xs text-white font-weight-bold btnVerComentariosNodo shadow-sm"
                            data-title="{{ e(strip_tags($axi->name)) }}"
                            data-level="{{ $niveles['axi'] ?? 'Objetivo Estratégico' }}"
                            data-comments="{{ $comentariosAxiJson }}"
                            onclick="event.preventDefault(); event.stopPropagation(); verComentariosNodo(this);"
                            style="background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%); border: none; border-radius: 20px; padding: 3px 11px; font-size: 0.73rem; cursor: pointer; box-shadow: 0 2px 6px rgba(126, 34, 206, 0.45);">
                        <i class="fa fa-comment-alt mr-1 text-warning"></i> {{ $comentariosAxi->count() }} {{ $comentariosAxi->count() === 1 ? 'Aporte Asesoría' : 'Aportes Asesoría' }}
                    </button>
                    @endif
                    @php
                        $riesgosMecipAxi = $axi->riesgos_mecip;
                        $countRiesgos = is_array($riesgosMecipAxi) ? count($riesgosMecipAxi) : 0;
                    @endphp
                    <button type="button"
                            class="btn btn-sm {{ $countRiesgos > 0 ? 'btn-warning text-dark font-weight-bold' : 'btn-outline-light text-warning' }} py-0 px-2 btn-ver-riesgos-mecip"
                            data-axi-id="{{ $axi->id }}"
                            data-axi-title="{{ e(strip_tags($axi->name)) }}"
                            data-riesgos='@json($riesgosMecipAxi)'
                            title="Ver / Gestionar {{ $countRiesgos }} riesgo(s) MECIP 2015 asociados">
                        <i class="fa fa-shield-alt mr-1"></i> Riesgos MECIP ({{ $countRiesgos }})
                    </button>
                    @if($bscPerspectiva && isset($bscLabels[$bscPerspectiva]))
                    <span class="badge {{ $bscColores[$bscPerspectiva]['badge'] ?? 'badge-secondary' }}"
                          style="font-size:.68rem"
                          title="Perspectiva BSC">
                        <i class="fa {{ $bscColores[$bscPerspectiva]['icon'] ?? 'fa-chart-bar' }} mr-1"></i>
                        {{ $bscLabels[$bscPerspectiva] }}
                    </span>
                    @endif
                    @if($axi->indicador)
                    <button type="button"
                       class="badge badge-light text-primary border btn-ver-indicador"
                       style="font-size:.68rem; cursor:pointer;"
                       data-id="{{ $axi->indicador->id }}"
                       data-profile="{{ $profile->id }}"
                       title="Ver ficha: {{ $axi->indicador->nombre }}">
                        <i class="fa fa-ruler-combined mr-1"></i>[{{ $axi->indicador->codigoCompleto() }}] {{ \Illuminate\Support\Str::limit($axi->indicador->nombre, 35) }}
                    </button>
                    @endif
                    <a class="btn btn-sm btn-outline-light py-0 px-2" data-id="{{ $axi->id }}"
                       data-type="edit" href="javascript:void(0)" id="createAxis" title="Editar">
                        <i class="fa fa-edit" style="font-size:.75rem"></i>
                    </a>
                    <a class="btn btn-sm btn-success py-0 px-2 createGoalsButton" data-id="{{ $axi->id }}"
                       data-type="create" href="javascript:void(0)" id="createGoals"
                       title="Agregar {{ $niveles['goal'] ?? 'Meta' }}">
                        <i class="fa fa-plus" style="font-size:.75rem"></i>
                    </a>
                    @role('Administrador')
                    <a class="btn btn-sm btn-danger py-0 px-2 deleteItem" data-id="{{ $axi->id }}"
                       href="javascript:void(0)" id="deleteProfile" title="Eliminar">
                        <i class="fa fa-trash" style="font-size:.75rem"></i>
                    </a>
                    @endrole
                </div>
                {{-- Editores del Objetivo --}}
                @php $uniqueEditorsAxi = $axi->edits->pluck('user')->filter()->unique('id'); @endphp
                <div class="mt-1 px-1 d-flex flex-wrap align-items-center" style="gap:.25rem">
                    <small class="text-white mr-1" style="opacity:.6;font-size:.62rem;"><i class="fa fa-user-edit mr-1"></i>Editores:</small>
                    @forelse($uniqueEditorsAxi as $uEditor)
                        <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.65rem">{{ $uEditor->name }}</span>
                    @empty
                        <span class="badge" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.5);font-size:.65rem;border: 1px dashed rgba(255,255,255,.3);">Ninguno aún</span>
                    @endforelse
                </div>
                {{-- Resultado Intermedio Institucional --}}
                @if($axi->resultado_intermedio)
                @php
                    $riMetas = is_string($axi->ri_metas) ? json_decode($axi->ri_metas, true) : ($axi->ri_metas ?? []);
                @endphp
                <div class="mt-1 px-1 pb-1" style="border-top:1px solid rgba(255,255,255,.15);padding-top:.4rem">
                    <div class="d-flex align-items-start flex-wrap" style="gap:.5rem">
                        <div style="flex:1 1 200px">
                            <small class="text-white" style="opacity:.55;font-size:.62rem;text-transform:uppercase;letter-spacing:.04em">
                                <i class="fa fa-flag mr-1"></i>Resultado Intermedio
                            </small>
                            <div class="text-white" style="font-size:.78rem;opacity:.9;font-style:italic">
                                {{ $axi->resultado_intermedio }}
                            </div>
                        </div>
                        @if($axi->ri_recursos_gs)
                        <div class="text-right flex-shrink-0">
                            <small class="text-white" style="opacity:.55;font-size:.62rem;text-transform:uppercase;letter-spacing:.04em">Recursos</small>
                            <div class="text-white font-weight-bold" style="font-size:.8rem">
                                Gs. {{ number_format($axi->ri_recursos_gs, 0, ',', '.') }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($axi->ri_programa)
                    <div class="text-white mt-1" style="font-size:.72rem;opacity:.7">
                        <i class="fa fa-coins mr-1"></i>{{ $axi->ri_programa }}
                    </div>
                    @endif
                    @if($riMetas && count($riMetas) > 0)
                    <div class="d-flex flex-wrap mt-1" style="gap:.25rem">
                        @foreach($riMetas as $rm)
                        <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.65rem">
                            {{ $rm['anio'] }}: {{ $rm['valor'] }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- Marcos inline container para actualizaciones JS --}}
                <div id="marcos-body-{{ $axi->id }}"></div>

                {{-- Estrategias FODA inline --}}
                @if($estrategiasAxi->count() > 0)
                @php $coloresEstrategiaInline = ['fo'=>'badge-success','fa'=>'badge-warning','do'=>'badge-info','da'=>'badge-danger']; @endphp
                <div class="mt-1">
                    @foreach($estrategiasAxi as $estrategia)
                    @php
                        $tipoKey = strtolower($estrategia->tipo ?? 'fo');
                        $badgeColor = $coloresEstrategiaInline[$tipoKey] ?? 'badge-secondary';
                        $textoCorto = \Illuminate\Support\Str::limit(strip_tags($estrategia->estrategia), 60);
                    @endphp
                    <span class="badge {{ $badgeColor }} mr-1 mb-1"
                          style="font-size:.65rem; white-space:normal; text-align:left; max-width:100%"
                          title="{{ strip_tags($estrategia->estrategia) }}">
                        <strong>{{ strtoupper($estrategia->tipo ?? '') }}</strong> · {{ $textoCorto }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>{{-- /card-header objetivo --}}

            {{-- Body Objetivo --}}
            <div id="col{{ str_replace('-', '', $axi->id) }}" class="collapse">
                <div class="card-body p-3">

                    @foreach ($axi->children->sortBy('order_item') as $goal)
                    <div class="card mb-3 border-left border-primary" id="goal-{{ $goal->id }}" style="border-left-width:4px !important">

                        {{-- Header Meta --}}
                        <div class="card-header bg-light py-2 px-3">
                            {{-- Fila 1: texto --}}
                            <div class="d-flex align-items-center">
                                <button class="btn btn-link text-dark text-left p-0 font-weight-bold w-100"
                                        type="button" data-toggle="collapse"
                                        data-target="#col{{ str_replace('-', '', $goal->id) }}"
                                        aria-expanded="false"
                                        style="font-size:.88rem; text-decoration:none; white-space:normal; word-break:break-word;">
                                    <i class="fa fa-flag-checkered mr-2 text-primary"></i>
                                    <span class="badge badge-primary mr-1" style="font-size:.65rem">{{ $niveles['goal'] ?? 'Meta' }}</span>
                                    {!! $goal->name !!}
                                </button>
                            </div>
                            {{-- Fila 2: botones --}}
                            <div class="d-flex align-items-center mt-1 flex-wrap" style="gap:.3rem">
                                @if($goal->bsc_perspectiva && isset($bscLabels[$goal->bsc_perspectiva]))
                                <span class="badge {{ $bscColores[$goal->bsc_perspectiva]['badge'] ?? 'badge-secondary' }} mr-1"
                                      style="font-size:.65rem"
                                      title="Perspectiva BSC">
                                    <i class="fa {{ $bscColores[$goal->bsc_perspectiva]['icon'] ?? 'fa-chart-bar' }} mr-1"></i>
                                    {{ $bscLabels[$goal->bsc_perspectiva] }}
                                </span>
                                @endif
                                @if($goal->indicador)
                                <button type="button"
                                   class="badge badge-info text-white mr-1 btn-ver-indicador"
                                   style="font-size:.65rem; cursor:pointer;"
                                   data-id="{{ $goal->indicador->id }}"
                                   data-profile="{{ $profile->id }}"
                                   title="Ver ficha: {{ $goal->indicador->nombre }}">
                                    <i class="fa fa-ruler-combined mr-1"></i>[{{ $goal->indicador->codigoCompleto() }}] {{ \Illuminate\Support\Str::limit($goal->indicador->nombre, 30) }}
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2"
                                        onclick="event.stopPropagation(); openChatWithContext('PeiGoal', '{{ $goal->id }}', 'Meta: {{ e(strip_tags($goal->name)) }}', '{{ url()->current() }}#goal-{{ $goal->id }}')"
                                        title="Consultar sobre esta meta en el chat">
                                    <i class="fa fa-comment-dots mr-1" style="font-size:.7rem"></i> Consultar
                                </button>
                                 @php
                                     $comentariosGoal = isset($comentariosAsesoria) ? (
                                         $comentariosAsesoria->get('node_' . $goal->id)
                                         ?? $comentariosAsesoria->get('goal_' . $goal->id)
                                         ?? $comentariosAsesoria->get($goal->id)
                                         ?? collect()
                                     ) : collect();

                                     if (!empty($goal->comentario_asesor) && $comentariosGoal->where('comentario', $goal->comentario_asesor)->isEmpty()) {
                                         $dummy = (object)[
                                             'id' => null,
                                             'comentario' => $goal->comentario_asesor,
                                             'estado' => 'PENDIENTE',
                                             'created_at' => $goal->updated_at,
                                             'asesoria' => (object)['nombre' => 'Asesor Técnico', 'institucion' => 'Asesoría Remota']
                                         ];
                                         $comentariosGoal = $comentariosGoal->concat([$dummy]);
                                     }
                                 @endphp
                                 @if($comentariosGoal->count() > 0)
                                 @php
                                     $comentariosGoalArray = $comentariosGoal->map(fn($c) => [
                                         'id' => $c->id ?? null,
                                         'asesor' => $c->asesoria->nombre ?? 'Asesor Externo',
                                         'institucion' => $c->asesoria->institucion ?? 'Asesoría Técnica',
                                         'comentario' => $c->comentario ?? '',
                                         'estado' => $c->estado ?? 'PENDIENTE',
                                         'fecha' => isset($c->created_at) && $c->created_at ? (is_string($c->created_at) ? $c->created_at : $c->created_at->format('d/m/Y H:i')) : ''
                                     ])->values()->all();
                                     $comentariosGoalJson = base64_encode(json_encode($comentariosGoalArray));
                                 @endphp
                                 <button type="button" class="btn btn-xs text-white font-weight-bold btnVerComentariosNodo shadow-sm"
                                         data-title="{{ e(strip_tags($goal->name)) }}"
                                         data-level="{{ $niveles['goal'] ?? 'Objetivo Específico' }}"
                                         data-comments="{{ $comentariosGoalJson }}"
                                         onclick="event.preventDefault(); event.stopPropagation(); verComentariosNodo(this);"
                                         style="background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%); border: none; border-radius: 20px; padding: 2px 10px; font-size: 0.71rem; cursor: pointer; box-shadow: 0 2px 5px rgba(126, 34, 206, 0.4);">
                                     <i class="fa fa-comment-alt mr-1 text-warning"></i> {{ $comentariosGoal->count() }} {{ $comentariosGoal->count() === 1 ? 'Aporte Asesoría' : 'Aportes Asesoría' }}
                                 </button>
                                 @endif
                                <a class="btn btn-sm btn-outline-primary py-0 px-2" data-id="{{ $goal->id }}"
                                   data-type="edit" href="javascript:void(0)" id="createGoals" title="Editar">
                                    <i class="fa fa-edit" style="font-size:.7rem"></i>
                                </a>
                                <a class="btn btn-sm btn-success py-0 px-2 createActionsButton"
                                   data-id="{{ $goal->id }}" data-type="create"
                                   href="javascript:void(0)" id="createActions"
                                   title="Agregar {{ $niveles['action'] ?? 'Acción' }}">
                                    <i class="fa fa-plus" style="font-size:.7rem"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold py-0 px-2 ml-1 createActionsButton shadow-xs"
                                        data-id="{{ $goal->id }}" data-type="create" id="createActions"
                                        title="Crear nueva Acción Estratégica e Indicador con Inteligencia Artificial (Llama 3.3 70B)"
                                        style="border-radius: 12px; font-size: 0.72rem;">
                                    <i class="fa fa-robot text-warning mr-1"></i> + Acción IA
                                </button>
                                 @role('Administrador')
                                 <a class="btn btn-sm btn-outline-danger py-0 px-2 deleteItem"
                                    data-id="{{ $goal->id }}" href="javascript:void(0)"
                                    id="deleteProfile" title="Eliminar">
                                     <i class="fa fa-trash" style="font-size:.7rem"></i>
                                 </a>
                                 @endrole
                             </div>
                             {{-- Editores de la Meta --}}
                             @php $uniqueEditorsGoal = $goal->edits->pluck('user')->filter()->unique('id'); @endphp
                             <div class="mt-1 d-flex flex-wrap align-items-center" style="gap:.25rem">
                                 <small class="text-muted mr-1" style="font-size:.62rem;"><i class="fa fa-user-edit mr-1"></i>Editores:</small>
                                 @forelse($uniqueEditorsGoal as $uEditor)
                                     <span class="badge badge-light border" style="font-size:.65rem">{{ $uEditor->name }}</span>
                                 @empty
                                     <span class="badge badge-light border text-muted" style="font-size:.65rem; border-style: dashed !important;">Ninguno aún</span>
                                 @endforelse
                             </div>
                        </div>{{-- /card-header meta --}}

                        {{-- Body Meta → Acciones --}}
                        <div id="col{{ str_replace('-', '', $goal->id) }}" class="collapse">
                            <div class="card-body p-2">

                                @if($goal->children->count() === 0)
                                    <div class="alert alert-warning py-2 mb-0">
                                        <i class="fa fa-exclamation-triangle mr-1"></i>
                                        Sin acciones registradas.
                                        <a href="javascript:void(0)" class="createActionsButton font-weight-bold"
                                           data-id="{{ $goal->id }}" data-type="create" id="createActions">
                                           Agregar una
                                        </a>
                                    </div>
                                @else
                                    @foreach ($goal->children->sortBy('order_item') as $action)
                                    @php
                                        $pgn = null;
                                        if (!$action->relationLoaded('pgnVinculacion')) {
                                            $pgn = \App\Models\Planificacion\PeiAccionPgn::with('nodo.estructura')
                                                ->where('pei_profile_id', $action->id)->first();
                                        } else {
                                            $pgn = $action->pgnVinculacion;
                                        }
                                        $pctFisico = ($action->denominator && $action->denominator > 0)
                                            ? round(($action->numerator / $action->denominator) * 100, 1)
                                            : null;
                                        $semaforoFisico = $action->semaforo ?? 'sin-datos';
                                        $colorFisico = match($semaforoFisico) {
                                            'verde'    => 'success',
                                            'amarillo' => 'warning',
                                            'rojo'     => 'danger',
                                            default    => 'secondary',
                                        };
                                        $colorPgn = 'secondary';
                                        $pctPgn   = null;
                                        if ($pgn) {
                                            $colorPgn = match($pgn->semaforo) {
                                                'verde'    => 'success',
                                                'amarillo' => 'warning',
                                                'rojo'     => 'danger',
                                                default    => 'secondary',
                                            };
                                            $pctPgn = $pgn->pct_ejecucion;
                                        }
                                        $ultimosReportes = \App\Models\Planificacion\PeiAccionReporte::with('usuario')
                                            ->where('pei_profile_id', $action->id)
                                            ->orderByDesc('fecha_reporte')
                                            ->get();
                                        $ultimoReporte = $ultimosReportes->first();
                                        $rpColor = ['bg'=>'#f5f5f5','border'=>'#e0e0e0','text'=>'#666','badge'=>'secondary'];
                                    @endphp

                                    <div class="mb-2" id="actionsBlock_{{ $action->id }}">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header py-2 px-3"
                                                 style="border-left:4px solid; background-color:rgba(0,0,0,.03);
                                                        border-left-color:{{ $colorFisico === 'success' ? '#28a745' : ($colorFisico === 'warning' ? '#ffc107' : ($colorFisico === 'danger' ? '#dc3545' : '#6c757d')) }}">
                                                <div class="d-flex align-items-start flex-wrap" style="gap:.3rem">
                                                    <div style="flex:1 1 180px; min-width:0">
                                                        <div class="d-flex align-items-center mb-1 flex-wrap" style="gap:4px;">
                                                            <span class="badge badge-{{ $colorFisico }}" style="font-size:.65rem">
                                                                <i class="fa fa-circle mr-1"></i>{{ strtoupper($semaforoFisico) }}
                                                            </span>
                                                            <span class="badge badge-light text-muted border" style="font-size:.65rem">
                                                                #{{ $action->order_item }}
                                                            </span>
                                                            @if(!empty($action->creado_con_ia))
                                                                <span class="badge badge-pill shadow-xs" title="Esta Acción fue concebida y redactada con Inteligencia Artificial (Llama 3.3 70B / Groq)" style="background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%); color: #fff; font-size: 0.68rem; padding: 3px 8px; font-weight:600;">
                                                                    <i class="fa fa-robot mr-1 text-warning"></i> Generado con IA
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="font-weight-bold text-dark" style="font-size:.88rem; word-break:break-word;">
                                                            {!! $action->name !!}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-shrink-0" style="gap:.3rem">
                                                        <button type="button" class="btn btn-sm btn-outline-info py-0 px-2"
                                                                onclick="event.stopPropagation(); openChatWithContext('PeiAction', '{{ $action->id }}', 'Acción: {{ e(strip_tags($action->name)) }}', '{{ url()->current() }}#actionsBlock_{{ $action->id }}')"
                                                                title="Consultar sobre esta acción en el chat"
                                                                style="font-size:.7rem">
                                                            <i class="fa fa-comment-dots mr-1"></i> Consultar
                                                        </button>
                                                        <a class="btn btn-sm btn-outline-info py-0 px-2"
                                                           data-id="{{ $action->id }}" data-type="edit"
                                                           href="javascript:void(0)" id="createActions" title="Editar">
                                                            <i class="fa fa-edit" style="font-size:.7rem"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-warning py-0 px-2 reportProgress"
                                                           data-id="{{ $action->id }}" href="javascript:void(0)"
                                                           id="reportProgress" title="Reportar Avance">
                                                            <i class="fa fa-chart-line" style="font-size:.7rem"></i>
                                                        </a>
                                                        @if($colorFisico === 'danger' || $semaforoFisico === 'Rojo')
                                                        <button type="button" class="btn btn-sm btn-danger py-0 px-2 shadow-sm font-weight-bold"
                                                                onclick="event.stopPropagation(); remitirAlertaJunta('{{ $action->id }}', '{{ e(strip_tags($action->name)) }}')"
                                                                title="Remitir Alerta Roja al Consejo de Sabios / Junta Consultiva"
                                                                style="font-size:.7rem">
                                                            <i class="fa fa-landmark mr-1"></i> Remitir a Junta
                                                        </button>
                                                        @endif
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-success py-0 px-2 btnNotificarAccion"
                                                                data-id="{{ $action->id }}"
                                                                data-profile="{{ $action->parent->parent->id ?? '' }}"
                                                                title="Notificar al responsable de esta acción"
                                                                style="font-size:.7rem">
                                                            <i class="fa fa-paper-plane"></i>
                                                        </button>
                                                        @role('Administrador')
                                                        <a class="btn btn-sm btn-outline-danger py-0 px-2 deleteItem"
                                                           data-id="{{ $action->id }}"
                                                           href="javascript:void(0)"
                                                           title="Enviar a la Papelera">
                                                            <i class="fa fa-trash" style="font-size:.7rem"></i>
                                                        </a>
                                                        @endrole
                                                        @php
                                                            $comentariosAction = isset($comentariosAsesoria) ? (
                                                                $comentariosAsesoria->get('node_' . $action->id)
                                                                ?? $comentariosAsesoria->get('action_' . $action->id)
                                                                ?? $comentariosAsesoria->get($action->id)
                                                                ?? collect()
                                                            ) : collect();

                                                            if (!empty($action->comentario_asesor) && $comentariosAction->where('comentario', $action->comentario_asesor)->isEmpty()) {
                                                                $dummy = (object)[
                                                                    'id' => null,
                                                                    'comentario' => $action->comentario_asesor,
                                                                    'estado' => 'PENDIENTE',
                                                                    'created_at' => $action->updated_at,
                                                                    'asesoria' => (object)['nombre' => 'Asesor Técnico', 'institucion' => 'Asesoría Remota']
                                                                ];
                                                                $comentariosAction = $comentariosAction->concat([$dummy]);
                                                            }
                                                        @endphp
                                                        @if($comentariosAction->count() > 0)
                                                        @php
                                                            $comentariosActionArray = $comentariosAction->map(fn($c) => [
                                                                'id' => $c->id ?? null,
                                                                'asesor' => $c->asesoria->nombre ?? 'Asesor Externo',
                                                                'institucion' => $c->asesoria->institucion ?? 'Asesoría Técnica',
                                                                'comentario' => $c->comentario ?? '',
                                                                'estado' => $c->estado ?? 'PENDIENTE',
                                                                'fecha' => isset($c->created_at) && $c->created_at ? (is_string($c->created_at) ? $c->created_at : $c->created_at->format('d/m/Y H:i')) : ''
                                                            ])->values()->all();
                                                            $comentariosActionJson = base64_encode(json_encode($comentariosActionArray));
                                                        @endphp
                                                        <button type="button" class="btn btn-xs text-white font-weight-bold btnVerComentariosNodo shadow-sm"
                                                                data-title="{{ e(strip_tags($action->name)) }}"
                                                                data-level="{{ $niveles['action'] ?? 'Acción Estratégica' }}"
                                                                data-comments="{{ $comentariosActionJson }}"
                                                                onclick="event.preventDefault(); event.stopPropagation(); verComentariosNodo(this);"
                                                                style="background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%); border: none; border-radius: 20px; padding: 2px 10px; font-size: 0.71rem; cursor: pointer; box-shadow: 0 2px 5px rgba(126, 34, 206, 0.4);">
                                                            <i class="fa fa-comment-alt mr-1 text-warning"></i> {{ $comentariosAction->count() }} {{ $comentariosAction->count() === 1 ? 'Aporte Asesoría' : 'Aportes Asesoría' }}
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- Editores de la Acción --}}
                                                @php $uniqueEditorsAction = $action->edits->pluck('user')->filter()->unique('id'); @endphp
                                                <div class="mt-1 d-flex flex-wrap align-items-center px-1" style="gap:.25rem">
                                                    <small class="text-muted mr-1" style="font-size:.62rem;"><i class="fa fa-user-edit mr-1"></i>Editores:</small>
                                                    @forelse($uniqueEditorsAction as $uEditor)
                                                        <span class="badge badge-light border" style="font-size:.65rem">{{ $uEditor->name }}</span>
                                                    @empty
                                                        <span class="badge badge-light border text-muted" style="font-size:.65rem; border-style: dashed !important;">Ninguno aún</span>
                                                    @endforelse
                                                </div>
                                            </div>{{-- /card-header accion --}}

                                            {{-- ── Cuerpo tipo Matriz Integrada ── --}}
                                            <div class="card-body p-0">
                                            @if($action->indicador)
                                            @php
                                                $indObj   = $action->indicador;
                                                $dimCols  = ['eficiencia'=>['bg'=>'#1976d2','label'=>'Eficiencia'],'eficacia'=>['bg'=>'#28a745','label'=>'Eficacia'],'calidad'=>['bg'=>'#17a2b8','label'=>'Calidad'],'economia'=>['bg'=>'#ffc107','label'=>'Economía']];
                                                $dc       = $dimCols[$indObj->dimension] ?? ['bg'=>'#6c757d','label'=>$indObj->dimension];
                                                $riMetas  = is_string($axi->ri_metas ?? null) ? json_decode($axi->ri_metas, true) : ($axi->ri_metas ?? []);
                                            @endphp

                                            {{-- Fila 1: Indicador | Unidad+Fórmula+LB | Metas anuales --}}
                                            <div class="d-flex" style="border-bottom:1px solid #e9ecef;font-size:.78rem">

                                                {{-- Col 1: Indicador --}}
                                                <div class="px-3 py-2" style="flex:0 0 220px;border-right:1px solid #e9ecef;background:#f8f9ff">
                                                    <div class="d-flex align-items-center flex-wrap mb-1" style="gap:.25rem">
                                                        <span class="badge badge-dark" style="font-size:.6rem">{{ $indObj->codigoCompleto() }}</span>
                                                        <span class="badge text-white" style="font-size:.6rem;background:{{ $dc['bg'] }}">{{ $dc['label'] }}</span>
                                                        @if(!empty($indObj->creado_con_ia))
                                                            <span class="badge badge-warning text-dark font-weight-bold" style="font-size:.6rem;" title="Indicador generado con Inteligencia Artificial (Llama 3.3)"><i class="fa fa-robot mr-1"></i>IA</span>
                                                        @endif
                                                        @if($indObj->sentido === 'ascendente')
                                                            <span class="text-success font-weight-bold" style="font-size:.8rem" title="Ascendente">▲</span>
                                                        @else
                                                            <span class="text-danger font-weight-bold" style="font-size:.8rem" title="Descendente">▼</span>
                                                        @endif
                                                    </div>
                                                    <div class="font-weight-bold" style="font-size:.8rem;line-height:1.3;color:#1a237e">
                                                        {{ $indObj->nombre }}
                                                    </div>
                                                </div>

                                                {{-- Col 2: Unidad / Fórmula / Línea base --}}
                                                <div class="px-3 py-2" style="flex:1 1 180px;border-right:1px solid #e9ecef">
                                                    @if($indObj->unidad_medida)
                                                    <div class="mb-1">
                                                        <span class="text-muted text-uppercase d-block" style="font-size:.6rem;letter-spacing:.04em">Unidad</span>
                                                        <span>{{ $indObj->unidad_medida }}</span>
                                                    </div>
                                                    @endif
                                                    @if($indObj->formula)
                                                    <div class="mb-1">
                                                        <span class="text-muted text-uppercase d-block" style="font-size:.6rem;letter-spacing:.04em">Fórmula</span>
                                                        <span style="font-style:italic;font-size:.75rem">{{ $indObj->formula }}</span>
                                                    </div>
                                                    @endif
                                                    @if($indObj->linea_base_anio || $indObj->linea_base_valor)
                                                    <div>
                                                        <span class="text-muted text-uppercase d-block" style="font-size:.6rem;letter-spacing:.04em">Línea base</span>
                                                        <span class="badge badge-light border" style="font-size:.68rem">
                                                            {{ $indObj->linea_base_anio ? $indObj->linea_base_anio.': ' : '' }}{{ $indObj->linea_base_valor ?? '—' }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>

                                                {{-- Col 3: Metas anuales --}}
                                                <div class="px-3 py-2" style="flex:1 1 200px">
                                                    <span class="text-muted text-uppercase d-block mb-1" style="font-size:.6rem;letter-spacing:.04em">Metas anuales</span>
                                                    <div class="d-flex flex-wrap" style="gap:.25rem">
                                                        @if($indObj->metas && count($indObj->metas) > 0)
                                                            @foreach($indObj->metas as $meta)
                                                            <span class="badge" style="background:#e8eaf6;color:#1a237e;font-size:.68rem">
                                                                {{ $meta['anio'] }}: {{ $meta['valor'] }}
                                                            </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted" style="font-size:.73rem">—</span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>{{-- /fila 1 --}}

                                            {{-- Fila 2: Responsable | Fuente | PGN+Avance --}}
                                            <div class="d-flex" style="font-size:.78rem">

                                                {{-- Responsable --}}
                                                <div class="px-3 py-2" style="flex:0 0 220px;border-right:1px solid #e9ecef;background:#f8f9ff">
                                                    <span class="text-muted text-uppercase d-block mb-1" style="font-size:.6rem;letter-spacing:.04em">
                                                        <i class="fa fa-user mr-1"></i>Responsable(s)
                                                    </span>
                                                    @if($action->responsibles->count() > 0)
                                                        @foreach($action->responsibles as $r)
                                                        <span class="badge badge-secondary mr-1 mb-1" style="font-size:.65rem">{{ $r->dependency }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted" style="font-size:.72rem">—</span>
                                                    @endif
                                                </div>

                                                {{-- Fuente --}}
                                                <div class="px-3 py-2" style="flex:1 1 180px;border-right:1px solid #e9ecef">
                                                    <span class="text-muted text-uppercase d-block mb-1" style="font-size:.6rem;letter-spacing:.04em">
                                                        <i class="fa fa-database mr-1"></i>Fuente
                                                    </span>
                                                    <span class="text-muted" style="font-size:.73rem">{{ $indObj->fuente ?? '—' }}</span>
                                                </div>

                                                {{-- PGN + Avance --}}
                                                <div class="px-3 py-2" style="flex:1 1 200px">
                                                    @if($pctFisico !== null)
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="text-muted text-uppercase" style="font-size:.6rem;letter-spacing:.04em">
                                                            <i class="fa fa-tasks mr-1"></i>Avance
                                                        </span>
                                                        <span class="badge badge-{{ $colorFisico }}" style="font-size:.65rem">{{ $pctFisico }}%</span>
                                                    </div>
                                                    <div class="progress mb-2" style="height:6px;border-radius:3px">
                                                        <div class="progress-bar bg-{{ $colorFisico }}" style="width:{{ min($pctFisico,100) }}%"></div>
                                                    </div>
                                                    @endif
                                                    @if($pgn)
                                                    <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:.35rem;padding:.3rem .5rem">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <small class="font-weight-bold text-uppercase" style="font-size:.58rem;color:#856404">
                                                                <i class="fa fa-coins mr-1"></i>PGN
                                                            </small>
                                                            <span class="badge badge-{{ $colorPgn }}" style="font-size:.62rem">
                                                                {{ $pctPgn !== null ? $pctPgn.'%' : 'Sin ejec.' }}
                                                            </span>
                                                        </div>
                                                        <div class="text-truncate" style="font-size:.72rem;color:#343a40" title="{{ $pgn->nodo->nombre }}">
                                                            {{ $pgn->nodo->nombre }}
                                                        </div>
                                                        @if($pgn->monto_vinculado_gs)
                                                        <div style="font-size:.68rem;color:#6c757d">
                                                            Gs. {{ number_format($pgn->monto_vinculado_gs, 0, ',', '.') }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>

                                            </div>{{-- /fila 2 --}}

                                            @else
                                            {{-- Sin indicador vinculado: layout simple --}}
                                            <div class="px-3 py-2 d-flex flex-wrap" style="gap:.5rem;font-size:.78rem">
                                                @if($action->indicator)
                                                <div style="flex:1 1 200px">
                                                    <span class="text-muted text-uppercase d-block" style="font-size:.6rem">Indicador</span>
                                                    {{ $action->indicator }}
                                                    @if($action->baseline)
                                                    <span class="badge badge-light border ml-2" style="font-size:.65rem">Base: {{ $action->baseline }}</span>
                                                    @endif
                                                    @if($action->target)
                                                    <span class="badge badge-dark ml-1" style="font-size:.65rem">Meta: {{ $action->target }}</span>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($action->responsibles->count() > 0)
                                                <div style="flex:1 1 150px">
                                                    <span class="text-muted text-uppercase d-block" style="font-size:.6rem">Responsable(s)</span>
                                                    @foreach($action->responsibles as $r)
                                                    <span class="badge badge-secondary mr-1" style="font-size:.65rem">{{ $r->dependency }}</span>
                                                    @endforeach
                                                </div>
                                                @endif
                                                @if($pgn)
                                                <div style="flex:1 1 150px">
                                                    <span class="text-muted text-uppercase d-block" style="font-size:.6rem"><i class="fa fa-coins mr-1"></i>PGN</span>
                                                    <span style="font-size:.75rem">{{ $pgn->nodo->nombre }}</span>
                                                    @if($pctPgn !== null)
                                                    <span class="badge badge-{{ $colorPgn }} ml-1" style="font-size:.62rem">{{ $pctPgn }}%</span>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($pctFisico !== null)
                                                <div style="flex:0 0 80px">
                                                    <span class="text-muted text-uppercase d-block" style="font-size:.6rem">Avance</span>
                                                    <div class="progress mt-1" style="height:6px">
                                                        <div class="progress-bar bg-{{ $colorFisico }}" style="width:{{ min($pctFisico,100) }}%"></div>
                                                    </div>
                                                    <small class="badge badge-{{ $colorFisico }}" style="font-size:.65rem">{{ $pctFisico }}%</small>
                                                </div>
                                                @endif
                                            </div>
                                            @endif

                                            {{-- Tareas vinculadas --}}
                                            @if($action->activityTasks->count() > 0)
                                            <div class="px-3 py-1 d-flex flex-wrap align-items-center" style="gap:.3rem;border-top:1px solid #e9ecef;background:#f8fff8;font-size:.75rem">
                                                <span class="text-muted text-uppercase flex-shrink-0" style="font-size:.6rem;letter-spacing:.04em">
                                                    <i class="fa fa-check-square mr-1 text-success"></i>Tareas
                                                </span>
                                                @foreach($action->activityTasks as $at)
                                                <span class="badge badge-light border" style="font-size:.68rem">
                                                    <i class="fa fa-circle mr-1" style="font-size:.45rem;color:{{ $at->status == 2 ? '#28a745' : ($at->status == 1 ? '#ffc107' : '#6c757d') }}"></i>
                                                    {{ \Illuminate\Support\Str::limit($at->title, 50) }}
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Proyectos vinculados --}}
                                            @php
                                                $proyectosAccion = \App\Models\Proyectos\ProyectoInstitucional::where('pei_profile_id', $action->id)
                                                    ->whereNull('deleted_at')
                                                    ->get();
                                            @endphp
                                            @if($proyectosAccion->count() > 0)
                                            <div class="px-3 py-2 d-flex flex-wrap align-items-start" style="gap:.4rem;border-top:1px solid #e9ecef;background:#f5f3ff;font-size:.78rem">
                                                <span class="text-muted text-uppercase flex-shrink-0" style="font-size:.6rem;letter-spacing:.04em;margin-top:3px">
                                                    <i class="fa fa-project-diagram mr-1 text-violet" style="color:#7c3aed"></i>Proyectos
                                                </span>
                                                @foreach($proyectosAccion as $proy)
                                                @php
                                                    $badgeCls = \App\Models\Proyectos\ProyectoInstitucional::estadoBadge($proy->estado);
                                                @endphp
                                                <a href="{{ route('proyectos-institucionales.show', $proy->id) }}"
                                                   class="d-inline-flex align-items-center text-decoration-none"
                                                   style="gap:.3rem;background:#ede9fe;border:1px solid #c4b5fd;border-radius:6px;padding:.2rem .55rem;font-size:.72rem;color:#5b21b6">
                                                    <i class="fa fa-folder-open" style="font-size:.6rem"></i>
                                                    <span class="font-weight-600">{{ $proy->codigo }}</span>
                                                    <span style="color:#7c3aed;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $proy->nombre }}</span>
                                                    <span class="badge {{ $badgeCls }}" style="font-size:.58rem;margin-left:.2rem">
                                                        {{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($proy->estado) }}
                                                    </span>
                                                    @if($proy->avance_pct > 0)
                                                        <span style="font-size:.62rem;color:#6d28d9;font-weight:600">{{ $proy->avance_pct }}%</span>
                                                    @endif
                                                </a>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Acciones Operativas (Plan de Gestión 100 Días) --}}
                                            @php
                                                if (is_numeric($action->id)) {
                                                    $iniciativasAccion = \App\Models\PlanMaestro\PlanAccion::where('plan_id', (string)$action->id)
                                                        ->orWhere('pei_profile_id', (string)$action->id)
                                                        ->orderBy('orden')
                                                        ->get();
                                                } else {
                                                    $iniciativasAccion = \App\Models\PlanMaestro\PlanAccion::where('pei_profile_id', (string)$action->id)
                                                        ->orderBy('orden')
                                                        ->get();
                                                }
                                                if ($iniciativasAccion->isEmpty() && method_exists($action, 'iniciativas')) {
                                                    try {
                                                        $iniciativasAccion = $action->iniciativas;
                                                    } catch (\Throwable $e) {
                                                        $iniciativasAccion = collect();
                                                    }
                                                }
                                            @endphp
                                            <div class="px-3 py-2" style="border-top:1px solid #e2e8f0; background:#f8fafc; font-size:.78rem">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="font-weight-bold text-uppercase text-dark" style="font-size:.68rem; letter-spacing:.04em">
                                                        <i class="fa fa-tasks text-info mr-1"></i> Acciones Operativas (Mejora Continua)
                                                        <span class="badge badge-info ml-1">{{ $iniciativasAccion->count() }}</span>
                                                    </span>
                                                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size:.68rem; border-radius:12px;" onclick="abrirModalNuevaIniciativa('{{ $action->id }}', '{{ addslashes(strip_tags($action->name)) }}')" title="Agregar nueva Acción Operativa de Mejora Continua">
                                                        <i class="fa fa-plus-circle mr-1"></i> + Nueva Acción Operativa
                                                    </button>
                                                </div>

                                                @if($iniciativasAccion->count() > 0)
                                                    <div class="d-flex flex-column" style="gap: .5rem;">
                                                        @foreach($iniciativasAccion as $ini)
                                                            @php
                                                                $grpState = $ini->estado_grupo;
                                                                $stBadge = match($grpState) {
                                                                    'EJECUTADO' => ['cls' => 'badge-success', 'icon' => 'fa-check-circle', 'label' => 'EJECUTADO', 'color' => '#10b981'],
                                                                    'EN CURSO'  => ['cls' => 'badge-warning text-dark', 'icon' => 'fa-clock-o', 'label' => 'EN CURSO', 'color' => '#f59e0b'],
                                                                    default     => ['cls' => 'badge-danger', 'icon' => 'fa-hourglass-start', 'label' => 'PENDIENTE', 'color' => '#ef4444'],
                                                                };
                                                                $mom = \App\Models\PlanMaestro\PlanAccion::MOMENTOS[$ini->momento] ?? ['label' => $ini->momento, 'color' => '#64748b'];
                                                            @endphp
                                                            <div class="p-2.5 rounded border bg-white shadow-xs ini-card-item" id="ini_card_{{ $ini->id }}" data-id="{{ $ini->id }}" data-codigo="{{ $ini->codigo }}" data-accion="{{ $ini->accion }}" data-estado="{{ $grpState }}" data-responsable="{{ $ini->responsable ?? '' }}" data-momento="{{ $ini->momento }}" style="border-left: 4px solid {{ $stBadge['color'] }} !important;">
                                                                {{-- Fila Principal --}}
                                                                <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: .5rem;">
                                                                    <div class="d-flex align-items-center flex-wrap flex-grow-1 mr-2" style="gap: .4rem; min-width: 0;">
                                                                        <span class="badge badge-dark font-weight-bold badge-code-ini" style="font-size:.65rem">{{ $ini->codigo }}</span>
                                                                        <span class="badge text-white font-weight-bold" style="font-size:.62rem; background:{{ $mom['color'] }}" title="{{ $mom['label'] }}">{{ $ini->momento }}</span>
                                                                        <div class="font-weight-bold text-dark ini-title" style="font-size:.82rem;" title="{{ $ini->accion }}">
                                                                            {{ $ini->accion }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex align-items-center ml-auto flex-wrap" style="gap: .4rem;">
                                                                        @if($ini->creator)
                                                                        <span class="badge badge-light border text-primary font-weight-bold" style="font-size:.64rem; background:#eff6ff; border-color:#bfdbfe !important;" title="Usuario que registró este aporte">
                                                                            <i class="fa fa-user-circle mr-1 text-primary"></i>Cargado por: {{ \Illuminate\Support\Str::limit($ini->creator->name, 18) }}
                                                                        </span>
                                                                        @endif
                                                                        @if($ini->responsable)
                                                                        <span class="badge badge-light border text-dark" style="font-size:.64rem" title="Responsable Institucional">
                                                                            <i class="fa fa-building-o mr-1 text-muted"></i>{{ \Illuminate\Support\Str::limit($ini->responsable, 22) }}
                                                                            @php
                                                                             $comentariosIni = isset($comentariosAsesoria) ? (
                                                                                 $comentariosAsesoria->get('iniciativa_' . $ini->id)
                                                                                 ?? $comentariosAsesoria->get('ini_' . $ini->id)
                                                                                 ?? $comentariosAsesoria->get($ini->id)
                                                                                 ?? collect()
                                                                             ) : collect();

                                                                             if (!empty($ini->comentario_asesor) && $comentariosIni->where('comentario', $ini->comentario_asesor)->isEmpty()) {
                                                                                 $dummy = (object)[
                                                                                     'id' => null,
                                                                                     'comentario' => $ini->comentario_asesor,
                                                                                     'estado' => 'PENDIENTE',
                                                                                     'created_at' => $ini->updated_at,
                                                                                     'asesoria' => (object)['nombre' => 'Asesor Técnico', 'institucion' => 'Asesoría Remota']
                                                                                 ];
                                                                                 $comentariosIni = $comentariosIni->concat([$dummy]);
                                                                             }
                                                                         @endphp
                                                                         @if($comentariosIni->count() > 0)
                                                                         @php
                                                                             $comentariosIniArray = $comentariosIni->map(fn($c) => [
                                                                                 'id' => $c->id ?? null,
                                                                                 'asesor' => $c->asesoria->nombre ?? 'Asesor Externo',
                                                                                 'institucion' => $c->asesoria->institucion ?? 'Asesoría Técnica',
                                                                                 'comentario' => $c->comentario ?? '',
                                                                                 'estado' => $c->estado ?? 'PENDIENTE',
                                                                                 'fecha' => isset($c->created_at) && $c->created_at ? (is_string($c->created_at) ? $c->created_at : $c->created_at->format('d/m/Y H:i')) : ''
                                                                             ])->values()->all();
                                                                             $comentariosIniJson = base64_encode(json_encode($comentariosIniArray));
                                                                         @endphp
                                                                         <button type="button" class="btn btn-xs text-white font-weight-bold btnVerComentariosNodo shadow-sm"
                                                                                 data-title="{{ e(strip_tags($ini->accion)) }}"
                                                                                 data-level="Acción Operativa (Iniciativa)"
                                                                                 data-comments="{{ $comentariosIniJson }}"
                                                                                 onclick="event.preventDefault(); event.stopPropagation(); verComentariosNodo(this);"
                                                                                 style="background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%); border: none; border-radius: 20px; padding: 2px 10px; font-size: 0.7rem; cursor: pointer; box-shadow: 0 2px 5px rgba(126, 34, 206, 0.4);">
                                                                             <i class="fa fa-comment-alt mr-1 text-warning"></i> {{ $comentariosIni->count() }} {{ $comentariosIni->count() === 1 ? 'Aporte Asesoría' : 'Aportes Asesoría' }}
                                                                         </button>
                                                                         @endif
                                                                        </span>
                                                                        @endif

                                                                        {{-- Grupo Indivisible de Controles --}}
                                                                        <div class="d-flex align-items-center flex-shrink-0" style="gap: .35rem; white-space: nowrap;">
                                                                            {{-- Semáforo interactivo de cambio de estado con 1-clic --}}
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-sm {{ $stBadge['cls'] }} dropdown-toggle py-0 px-2" type="button" data-toggle="dropdown" style="font-size:.65rem; border-radius:12px;">
                                                                                    <i class="fa {{ $stBadge['icon'] }} mr-1"></i> {{ $stBadge['label'] }}
                                                                                </button>
                                                                                <div class="dropdown-menu dropdown-menu-right shadow-sm p-1" style="font-size:.75rem;">
                                                                                    <a class="dropdown-item py-1 text-success font-weight-bold" href="javascript:void(0)" onclick="cambiarEstadoIniciativa('{{ $ini->id }}', 'EJECUTADO')">
                                                                                        🟢 Marcar como EJECUTADO
                                                                                    </a>
                                                                                    <a class="dropdown-item py-1 text-warning font-weight-bold" href="javascript:void(0)" onclick="cambiarEstadoIniciativa('{{ $ini->id }}', 'EN CURSO')">
                                                                                        🟡 Marcar como EN CURSO
                                                                                    </a>
                                                                                    <a class="dropdown-item py-1 text-danger font-weight-bold" href="javascript:void(0)" onclick="cambiarEstadoIniciativa('{{ $ini->id }}', 'PENDIENTE')">
                                                                                        🔴 Marcar como PENDIENTE
                                                                                    </a>
                                                                                </div>
                                                                            </div>

                                                                            {{-- Botones Editar y Eliminar --}}
                                                                            <button type="button" class="btn btn-sm btn-icon-action-edit" onclick="abrirModalEditarIniciativa({{ json_encode($ini) }}, '{{ addslashes(strip_tags($action->name)) }}')" title="Editar esta Acción Operativa">
                                                                                <i class="fas fa-pencil-alt"></i>
                                                                            </button>
                                                                            <button type="button" class="btn btn-sm btn-icon-action-delete" onclick="eliminarIniciativa('{{ $ini->id }}', '{{ $ini->codigo }}')" title="Eliminar esta Acción Operativa">
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Fila Secundaria de Detalles --}}
                                                                @if($ini->justificacion || $ini->detalle || $ini->kpi || $ini->plazo || $ini->indicador_id)
                                                                <div class="mt-2 pt-1.5 border-top d-flex flex-wrap align-items-center" style="gap: .4rem; font-size:.72rem; color:#475569;">
                                                                    @if($ini->justificacion || $ini->detalle)
                                                                    <div class="w-100 mb-1 text-muted" style="font-size:.71rem; font-style:italic;">
                                                                        <i class="fa fa-info-circle mr-1 text-info"></i> {{ $ini->justificacion ?? $ini->detalle }}
                                                                    </div>
                                                                    @endif

                                                                    @if($ini->kpi)
                                                                    <span class="badge badge-light border text-dark" style="font-size:.65rem; background:#f0fdf4; border-color:#bbf7d0 !important;">
                                                                        <i class="fa fa-chart-line text-success mr-1"></i> <strong>Meta/KPI:</strong> {{ $ini->kpi }}
                                                                    </span>
                                                                    @endif

                                                                    @if($ini->plazo)
                                                                    <span class="badge badge-light border text-dark" style="font-size:.65rem; background:#fffbeb; border-color:#fde68a !important;">
                                                                        <i class="fa fa-calendar-alt text-warning mr-1"></i> <strong>Hito/Plazo:</strong> {{ $ini->plazo }}
                                                                    </span>
                                                                    @endif

                                                                    @if($ini->indicador_id && $ini->indicador)
                                                                    <button type="button" class="btn btn-xs btn-outline-info py-0 px-2 btn-ver-indicador" data-id="{{ $ini->indicador_id }}" data-profile="{{ $profile->id }}" style="font-size:.65rem; border-radius:10px;">
                                                                        <i class="fa fa-chart-bar mr-1"></i> Indicador Operativo: [{{ $ini->indicador->codigoCompleto() }}] {{ $ini->indicador->nombre }}
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-muted small italic py-1 text-center" style="font-size:.72rem">
                                                        Sin Acciones Operativas registradas para esta Acción PEI. Presiona <strong>+ Nueva Acción Operativa</strong> para crear una.
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Reportes: todos visibles --}}
                                            <div style="border-top:1px solid #e9ecef;font-size:.78rem">
                                                <div class="px-3 pt-2 pb-1">
                                                    <span class="text-muted text-uppercase" style="font-size:.6rem;letter-spacing:.04em">
                                                        <i class="fa fa-history mr-1"></i>Reportes de avance
                                                    </span>
                                                </div>
                                                @forelse($ultimosReportes as $rp)
                                                @php
                                                    $rColor = match($rp->semaforo ?? 'sin-datos') {
                                                        'verde'    => ['bg'=>'#e8f5e9','text'=>'#1b5e20','badge'=>'success'],
                                                        'amarillo' => ['bg'=>'#fffde7','text'=>'#f57f17','badge'=>'warning'],
                                                        'rojo'     => ['bg'=>'#ffebee','text'=>'#b71c1c','badge'=>'danger'],
                                                        default    => ['bg'=>'#f5f5f5','text'=>'#666','badge'=>'secondary'],
                                                    };
                                                @endphp
                                                <div class="d-flex align-items-center px-3 py-2" style="gap:.5rem;flex-wrap:wrap;background:{{ $rColor['bg'] }};border-top:1px solid #e9ecef">
                                                    <span class="badge badge-{{ $rColor['badge'] }}" style="font-size:.65rem">{{ strtoupper($rp->semaforo ?? 'sin datos') }}</span>
                                                    <span style="color:{{ $rColor['text'] }};font-weight:600;font-size:.75rem">{{ $rp->fecha_reporte->format('d/m/Y') }}</span>
                                                    @if($rp->valor_numerador !== null)
                                                    <span class="badge badge-light border" style="font-size:.65rem">Valor: {{ $rp->valor_numerador }}</span>
                                                    @endif
                                                    @if($rp->pct_avance !== null)
                                                    <span class="badge badge-{{ $rColor['badge'] }}" style="font-size:.65rem">{{ $rp->pct_avance }}%</span>
                                                    @endif
                                                    @if($rp->descripcion_avance)
                                                    <span class="text-muted" style="flex:1;font-style:italic;font-size:.73rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                                                          title="{{ $rp->descripcion_avance }}">"{{ \Illuminate\Support\Str::limit($rp->descripcion_avance, 80) }}"</span>
                                                    @endif
                                                    <span class="text-muted ml-auto flex-shrink-0" style="font-size:.65rem"><i class="fa fa-user mr-1"></i>{{ $rp->usuario?->name ?? '—' }}</span>
                                                </div>
                                                @empty
                                                <div class="px-3 py-2" style="background:#f5f5f5;border-top:1px solid #e9ecef">
                                                    <span class="text-muted" style="font-size:.75rem;font-style:italic"><i class="fa fa-exclamation-circle mr-1"></i>Sin reportes registrados aún</span>
                                                </div>
                                                @endforelse
                                            </div>

                                            </div>{{-- /card-body accion --}}
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>{{-- /collapse meta --}}
                    </div>
                    @endforeach

<script>
if (typeof window.remitirAlertaJunta === 'undefined') {
    window.remitirAlertaJunta = function(actionId, actionName) {
        if (typeof Swal === 'undefined') {
            alert('Remitiendo alerta...');
            return;
        }

        Swal.fire({
            title: '<i class="fas fa-landmark text-danger"></i> Remitir al Consejo de Sabios',
            html: `<div class="text-left small mb-3">Se remitirá la Acción Estratégica <b>"${actionName}"</b> en Alerta Roja a la Junta Consultiva correspondiente para emisión de Dictamen con Firma Hológrafa:</div>` +
                  `<textarea id="swal_notas_remision" class="form-control form-control-sm" rows="3" placeholder="Motivo o detalles de la brecha operativa (opcional)..."></textarea>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: '<i class="fa fa-paper-plane mr-1"></i> Remitir Expediente',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const el = document.getElementById('swal_notas_remision');
                return {
                    notas: el ? el.value : ''
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.juntas.remitirAlerta') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        pei_profile_id: actionId,
                        notas_remision: result.value ? result.value.notas : ''
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('¡Remitido con Éxito!', res.message, 'success');
                        } else {
                            Swal.fire('Atención', res.message || 'No se pudo remitir.', 'error');
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error', 'Ocurrió un fallo en el servidor.', 'error');
                    }
                });
            }
        });
    };
}
</script>

                </div>
            </div>{{-- /collapse objetivo --}}
        </div>
    </div>
    @endforeach
</div>
