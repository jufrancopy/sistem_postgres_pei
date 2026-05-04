{{-- Partial recursivo para el árbol drag & drop --}}
<ul class="sortable-group list-unstyled mb-0" style="{{ $nivel === 0 ? '' : 'margin-top:4px' }}">
    @foreach($nodos as $nodo)
    @php
        $tieneHijos = $nodo->children->isNotEmpty();
        $colores = ['nivel-badge-0','nivel-badge-1','nivel-badge-2','nivel-badge-3','nivel-badge-4'];
        $colorBadge = $colores[min($nivel, count($colores)-1)];
    @endphp
    <li class="nodo-item" data-id="{{ $nodo->id }}">
        <div class="nodo-row">
            {{-- Handle de arrastre --}}
            <span class="drag-handle" title="Arrastrá para mover">
                <i class="fa fa-grip-vertical"></i>
            </span>

            {{-- Toggle expandir/colapsar --}}
            @if($tieneHijos)
            <button class="btn btn-link btn-toggle p-0 mr-2" style="font-size:.75rem;color:#6c757d;min-width:16px" title="Expandir/Colapsar">
                <i class="fa fa-chevron-down"></i>
            </button>
            @else
            <span style="min-width:24px;display:inline-block"></span>
            @endif

            {{-- Badge de nivel --}}
            <span class="badge text-white mr-2 flex-shrink-0 {{ $colorBadge }}" style="font-size:.65rem;min-width:22px">
                N{{ $nivel + 1 }}
            </span>

            {{-- Nombre --}}
            <span class="dep-nombre flex-grow-1 font-weight-{{ $nivel === 0 ? 'bold' : 'normal' }}"
                  style="font-size:.88rem">
                {{ $nodo->dependency }}
            </span>

            {{-- Responsable --}}
            @if($nodo->manager)
            <span class="text-muted d-none d-md-inline mr-3" style="font-size:.75rem">
                <i class="fa fa-user mr-1"></i>{{ $nodo->manager }}
            </span>
            @endif

            {{-- Usuario asignado --}}
            @if($nodo->user_id && $nodo->user)
            <span class="badge badge-success mr-2 d-none d-lg-inline" style="font-size:.7rem">
                <i class="fa fa-user-check mr-1"></i>{{ $nodo->user->name }}
            </span>
            @endif

            {{-- Acciones --}}
            <div class="flex-shrink-0 ml-2">
                <a href="javascript:void(0)"
                   class="btn btn-success btn-circle btnAgregarSub"
                   data-id="{{ $nodo->id }}"
                   data-nombre="{{ $nodo->dependency }}"
                   title="Agregar sub-dependencia">
                    <i class="fa fa-plus"></i>
                </a>
                <a href="javascript:void(0)"
                   class="btn btn-info btn-circle btnEditarDep ml-1"
                   data-id="{{ $nodo->id }}"
                   title="Editar">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="javascript:void(0)"
                   class="btn btn-danger btn-circle btnEliminarDep ml-1"
                   data-id="{{ $nodo->id }}"
                   data-nombre="{{ $nodo->dependency }}"
                   title="Eliminar">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>

        {{-- Hijos recursivos --}}
        @if($tieneHijos)
        <div class="nodo-children">
            @include('admin.globales.organigramas.partials.nodo_draggable', [
                'nodos' => $nodo->children,
                'nivel' => $nivel + 1,
            ])
        </div>
        @endif
    </li>
    @endforeach
</ul>
