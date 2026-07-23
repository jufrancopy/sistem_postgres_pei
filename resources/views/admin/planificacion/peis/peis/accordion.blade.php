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

    <div class="mb-2">
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
                    <span class="badge badge-light text-dark" title="{{ $totalAcciones }} acción(es)">
                        <i class="fa fa-rocket mr-1"></i>{{ $totalAcciones }}
                    </span>
                    @if($marcosAxi->count() > 0)
                    <a href="javascript:void(0)" id="marcos-pill-{{ $axi->id }}"
                       class="badge badge-info"
                       data-toggle="popover" data-trigger="click" data-placement="left"
                       data-html="true" data-title="Marcos Referenciales"
                       data-content="{{ $marcosPopover }}">
                        <i class="fa fa-link mr-1"></i>{{ $marcosAxi->count() }}
                    </a>
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
                    @if($bscPerspectiva && isset($bscLabels[$bscPerspectiva]))
                    <span class="badge {{ $bscColores[$bscPerspectiva]['badge'] ?? 'badge-secondary' }}"
                          style="font-size:.68rem"
                          title="Perspectiva BSC">
                        <i class="fa {{ $bscColores[$bscPerspectiva]['icon'] ?? 'fa-chart-bar' }} mr-1"></i>
                        {{ $bscLabels[$bscPerspectiva] }}
                    </span>
                    @endif
                    <button class="btn btn-sm btn-outline-light py-0 px-1 btnReordenar" data-id="{{ $axi->id }}" data-dir="up" title="Subir">
                        <i class="fa fa-arrow-up" style="font-size:.7rem"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light py-0 px-1 btnReordenar" data-id="{{ $axi->id }}" data-dir="down" title="Bajar">
                        <i class="fa fa-arrow-down" style="font-size:.7rem"></i>
                    </button>
                    <a class="btn btn-sm btn-outline-light py-0 px-2" data-id="{{ $axi->id }}"
                       data-type="edit" href="javascript:void(0)" id="createAxis" title="Editar">
                        <i class="fa fa-edit" style="font-size:.75rem"></i>
                    </a>
                    <a class="btn btn-sm btn-success py-0 px-2 createGoalsButton" data-id="{{ $axi->id }}"
                       data-type="create" href="javascript:void(0)" id="createGoals"
                       title="Agregar {{ $niveles['goal'] ?? 'Meta' }}">
                        <i class="fa fa-plus" style="font-size:.75rem"></i>
                    </a>
                    <a class="btn btn-sm btn-danger py-0 px-2 deleteItem" data-id="{{ $axi->id }}"
                       href="javascript:void(0)" id="deleteProfile" title="Eliminar">
                        <i class="fa fa-trash" style="font-size:.75rem"></i>
                    </a>
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

                {{-- Marcos inline --}}
                @if($marcosAxi->count() > 0)
                @php $colores=['pnd'=>'badge-danger','ods'=>'badge-success','bsc'=>'badge-primary','mecip'=>'badge-warning','pgn'=>'badge-dark','general'=>'badge-secondary']; @endphp
                <div class="mt-1" id="marcos-body-{{ $axi->id }}">
                    @foreach($marcosAxi->groupBy('tipo') as $tipo => $items)
                        @foreach($items as $marco)
                            <span class="badge {{ $colores[$tipo] ?? 'badge-secondary' }} mr-1" style="font-size:.65rem">{{ $marco->nombre }}</span>
                        @endforeach
                    @endforeach
                </div>
                @else
                <div id="marcos-body-{{ $axi->id }}"></div>
                @endif

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
                    <div class="card mb-3 border-left border-primary" style="border-left-width:4px !important">

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
                                <button class="btn btn-sm btn-outline-secondary py-0 px-1 btnReordenar" data-id="{{ $goal->id }}" data-dir="up" title="Subir">
                                    <i class="fa fa-arrow-up" style="font-size:.65rem"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-1 btnReordenar" data-id="{{ $goal->id }}" data-dir="down" title="Bajar">
                                    <i class="fa fa-arrow-down" style="font-size:.65rem"></i>
                                </button>
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
                                <a class="btn btn-sm btn-outline-danger py-0 px-2 deleteItem"
                                   data-id="{{ $goal->id }}" href="javascript:void(0)"
                                   id="deleteProfile" title="Eliminar">
                                    <i class="fa fa-trash" style="font-size:.7rem"></i>
                                </a>
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
                                                        <div class="d-flex align-items-center mb-1">
                                                            <span class="badge badge-{{ $colorFisico }} mr-2" style="font-size:.65rem">
                                                                <i class="fa fa-circle mr-1"></i>{{ strtoupper($semaforoFisico) }}
                                                            </span>
                                                            <span class="badge badge-light text-muted border" style="font-size:.65rem">
                                                                #{{ $action->order_item }}
                                                            </span>
                                                        </div>
                                                        <div class="font-weight-bold text-dark" style="font-size:.88rem; word-break:break-word;">
                                                            {!! $action->name !!}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-shrink-0" style="gap:.3rem">
                                                        <button class="btn btn-sm btn-outline-secondary py-0 px-1 btnReordenar" data-id="{{ $action->id }}" data-dir="up" title="Subir">
                                                            <i class="fa fa-arrow-up" style="font-size:.65rem"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary py-0 px-1 btnReordenar" data-id="{{ $action->id }}" data-dir="down" title="Bajar">
                                                            <i class="fa fa-arrow-down" style="font-size:.65rem"></i>
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
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-success py-0 px-2 btnNotificarAccion"
                                                                data-id="{{ $action->id }}"
                                                                data-profile="{{ $action->parent->parent->id ?? '' }}"
                                                                title="Notificar al responsable de esta acción"
                                                                style="font-size:.7rem">
                                                            <i class="fa fa-paper-plane"></i>
                                                        </button>
                                                    </div>
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

                </div>
            </div>{{-- /collapse objetivo --}}
        </div>
    </div>
    @endforeach
</div>
