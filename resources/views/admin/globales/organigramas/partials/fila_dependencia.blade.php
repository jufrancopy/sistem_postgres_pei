@foreach($nodos as $nodo)
@php
    $indent = $nivel * 20;
    $colores = ['info', 'primary', 'success', 'warning', 'secondary'];
    $color = $colores[min($nivel, count($colores)-1)];
@endphp
<tr>
    <td>
        <div class="d-flex align-items-center" style="padding-left: {{ $indent }}px">
            @if($nivel > 0)
                <span class="text-muted mr-2" style="font-size:.8rem">
                    @for($i = 0; $i < $nivel; $i++) &nbsp; @endfor
                    └
                </span>
            @endif
            <span class="badge badge-{{ $color }} mr-2" style="font-size:.7rem;min-width:20px">
                {{ $nivel + 1 }}
            </span>
            <span class="font-weight-{{ $nivel === 0 ? 'bold' : 'normal' }}">
                {{ $nodo->dependency }}
            </span>
            @if($nodo->children->isNotEmpty())
                <span class="badge badge-light border ml-2" style="font-size:.7rem">
                    {{ $nodo->children->count() }} sub
                </span>
            @endif
        </div>
    </td>
    <td>
        <small>{{ $nodo->manager ?? '—' }}</small>
    </td>
    <td>
        <small class="text-muted">{{ $nodo->email ?? '—' }}</small>
    </td>
    <td>
        @if($nodo->user_id && $nodo->user)
            <span class="badge badge-success">
                <i class="fa fa-user mr-1"></i>{{ $nodo->user->name }}
            </span>
        @else
            <span class="badge badge-light text-muted">Sin asignar</span>
        @endif
    </td>
    <td class="text-center">
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
    </td>
</tr>
{{-- Recursivo: hijos --}}
@if($nodo->children->isNotEmpty())
    @include('admin.globales.organigramas.partials.fila_dependencia', [
        'nodos'  => $nodo->children,
        'nivel'  => $nivel + 1,
    ])
@endif
@endforeach
