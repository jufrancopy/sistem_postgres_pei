{{-- Partial recursivo para el árbol Drag & Drop de PEI --}}
<ul class="sortable-pei-group list-unstyled mb-0 pl-3 pt-1" data-level="{{ $level }}" data-parent-id="{{ $parentId }}" style="border-left: 2px dashed #cbd5e1; margin-left: 8px; min-height: 8px;">
    @foreach($nodos as $nodo)
    @php
        $hijos = $nodo->children ?? collect();
        $tieneHijos = $hijos->isNotEmpty();

        $badgeMap = [
            'axi'                => ['EJE', 'badge-primary', 'fa-layer-group'],
            'eje'                => ['EJE', 'badge-primary', 'fa-layer-group'],
            'goal'               => ['OBJETIVO', 'badge-info', 'fa-bullseye'],
            'objetivo'           => ['OBJ. ESTRATÉGICO', 'badge-info', 'fa-bullseye'],
            'objetivo_especifico'=> ['OBJ. ESPECÍFICO', 'badge-warning text-dark', 'fa-crosshairs'],
            'accion_estrategica' => ['ACC. ESTRATÉGICA', 'badge-purple text-white', 'fa-bolt'],
            'action'             => ['ACCIÓN OPERATIVA', 'badge-success', 'fa-tasks'],
            'accion_operativa'   => ['ACC. OPERATIVA', 'badge-success', 'fa-tasks'],
        ];

        $levelCfg = $badgeMap[$nodo->level] ?? [strtoupper($nodo->level), 'badge-secondary', 'fa-folder'];
    @endphp
    <li class="nodo-pei-item mb-2" data-id="{{ $nodo->id }}" data-level="{{ $nodo->level }}">
        <div class="nodo-pei-card p-2 rounded border bg-white shadow-xs d-flex align-items-center justify-content-between" style="border-color: #cbd5e1 !important; transition: all 0.2s;">
            <div class="d-flex align-items-center flex-grow-1 mr-2" style="gap: 8px; overflow: hidden;">
                {{-- Grip handle --}}
                <span class="drag-handle-pei text-muted px-1" style="cursor: grab;" title="Arrastrá por aquí para mover de posición">
                    <i class="fa fa-grip-vertical" style="font-size: 0.85rem; color: #94a3b8;"></i>
                </span>

                {{-- Level Badge --}}
                <span class="badge {{ $levelCfg[1] }} px-2 py-1 flex-shrink-0" style="font-size: 0.65rem; border-radius: 4px;">
                    <i class="fa {{ $levelCfg[2] }} mr-1"></i> {{ $levelCfg[0] }}
                </span>

                {{-- Name --}}
                <span class="font-weight-bold text-dark text-truncate" style="font-size: 0.83rem;">
                    {{ $nodo->name }}
                </span>
            </div>

            @if($tieneHijos)
                <button type="button" class="btn btn-xs btn-link p-0 text-muted btn-toggle-pei-children" title="Expandir/Colapsar sub-nodos">
                    <i class="fa fa-chevron-down" style="font-size: 0.75rem; color: #64748b;"></i>
                </button>
            @endif
        </div>

        @if($tieneHijos)
            <div class="nodo-pei-children mt-1">
                @include('admin.planificacion.peis.peis.partials.nodo_pei_draggable', [
                    'nodos' => $hijos,
                    'level' => $hijos->first()->level ?? 'sub',
                    'parentId' => $nodo->id
                ])
            </div>
        @endif
    </li>
    @endforeach
</ul>
