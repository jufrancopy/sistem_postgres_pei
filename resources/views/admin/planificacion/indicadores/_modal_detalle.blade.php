@php
    $dimColors  = ['eficiencia'=>'primary','eficacia'=>'success','calidad'=>'info','economia'=>'warning'];
    $dimColor   = $dimColors[$indicador->dimension] ?? 'secondary';
    $sentidoIcon = $indicador->sentido === 'ascendente' ? 'fa-arrow-up text-success' : 'fa-arrow-down text-danger';
@endphp

<div class="modal-header" style="background: linear-gradient(135deg, #1e2746, #2d3a6b); color: white;">
    <div>
        <span class="badge badge-{{ $dimColor }} mb-1" style="font-size:.75rem">
            {{ \App\Models\Planificacion\Indicador::DIMENSIONES[$indicador->dimension] ?? $indicador->dimension }}
        </span>
        <h5 class="modal-title mb-0 font-weight-bold" style="font-size:1rem; line-height:1.3">
            <span class="badge badge-dark mr-2">{{ $indicador->codigoCompleto() }}</span>
            {{ $indicador->nombre }}
        </h5>
    </div>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>

<div class="modal-body p-0">

    {{-- Fila superior: ámbito / frecuencia / cobertura / sentido --}}
    <div class="d-flex flex-wrap border-bottom" style="background:#f8fafc;">
        <div class="px-3 py-2 border-right text-center" style="flex:1; min-width:120px;">
            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.05em;">Ámbito</div>
            <div class="font-weight-bold text-dark" style="font-size:.8rem;">
                {{ \App\Models\Planificacion\Indicador::AMBITOS[$indicador->ambito] ?? $indicador->ambito }}
            </div>
        </div>
        <div class="px-3 py-2 border-right text-center" style="flex:1; min-width:100px;">
            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.05em;">Frecuencia</div>
            <div class="font-weight-bold text-dark" style="font-size:.8rem;">
                {{ \App\Models\Planificacion\Indicador::FRECUENCIAS[$indicador->frecuencia] ?? $indicador->frecuencia }}
                @if($indicador->frecuencia === 'otro' && $indicador->frecuencia_otro)
                    — {{ $indicador->frecuencia_otro }}
                @endif
            </div>
        </div>
        <div class="px-3 py-2 border-right text-center" style="flex:1; min-width:100px;">
            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.05em;">Cobertura</div>
            <div class="font-weight-bold text-dark" style="font-size:.8rem;">
                {{ \App\Models\Planificacion\Indicador::COBERTURAS[$indicador->cobertura] ?? $indicador->cobertura }}
            </div>
        </div>
        <div class="px-3 py-2 text-center" style="flex:1; min-width:100px;">
            <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.05em;">Sentido</div>
            <div class="font-weight-bold" style="font-size:.8rem;">
                <i class="fa {{ $sentidoIcon }} mr-1"></i>
                {{ ucfirst($indicador->sentido) }}
            </div>
        </div>
    </div>

    <div class="p-3">

        {{-- Descripción --}}
        @if($indicador->descripcion)
        <div class="mb-3">
            <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                <i class="fa fa-align-left mr-1"></i> Descripción
            </div>
            <p class="mb-0 text-dark" style="font-size:.88rem; line-height:1.5">{{ $indicador->descripcion }}</p>
        </div>
        @endif

        {{-- Variables y Fórmula --}}
        @if($indicador->variables || $indicador->formula)
        <div class="row mb-3">
            @if($indicador->variables)
            <div class="col-md-6">
                <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                    <i class="fa fa-superscript mr-1"></i> Variables
                </div>
                <p class="mb-0 text-dark" style="font-size:.88rem">{{ $indicador->variables }}</p>
            </div>
            @endif
            @if($indicador->formula)
            <div class="col-md-6">
                <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                    <i class="fa fa-calculator mr-1"></i> Fórmula
                </div>
                <code class="text-dark" style="font-size:.88rem; background:#f1f5f9; padding:4px 8px; border-radius:4px; display:inline-block;">
                    {{ $indicador->formula }}
                </code>
            </div>
            @endif
        </div>
        @endif

        {{-- Unidad de medida --}}
        @if($indicador->unidad_medida)
        <div class="mb-3">
            <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                <i class="fa fa-ruler mr-1"></i> Unidad de Medida
            </div>
            <span class="badge badge-light border text-dark" style="font-size:.82rem; padding:.35em .7em;">
                {{ $indicador->unidad_medida }}
            </span>
        </div>
        @endif

        {{-- Línea Base --}}
        @if($indicador->linea_base_valor || $indicador->linea_base_anio)
        <div class="mb-3">
            <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                <i class="fa fa-flag mr-1"></i> Línea Base
            </div>
            <span class="font-weight-bold text-dark" style="font-size:.9rem;">
                {{ $indicador->linea_base_valor ?? '—' }}
                @if($indicador->linea_base_anio)
                    <span class="text-muted font-weight-normal">({{ $indicador->linea_base_anio }})</span>
                @endif
            </span>
        </div>
        @endif

        {{-- Metas anuales --}}
        @if(!empty($indicador->metas))
        <div class="mb-3">
            <div class="text-muted font-weight-bold mb-2" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                <i class="fa fa-bullseye mr-1"></i> Metas Anuales
            </div>
            <div class="d-flex flex-wrap" style="gap:.5rem;">
                @foreach($indicador->metas as $meta)
                <div class="text-center px-3 py-2 rounded border" style="background:#f8fafc; min-width:70px;">
                    <div class="text-muted" style="font-size:.65rem;">{{ $meta['anio'] ?? '—' }}</div>
                    <div class="font-weight-bold text-dark" style="font-size:.9rem;">{{ $meta['valor'] ?? '—' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Fuente y Responsable --}}
        <div class="row">
            @if($indicador->fuente)
            <div class="col-md-6 mb-2">
                <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                    <i class="fa fa-database mr-1"></i> Fuente
                </div>
                <p class="mb-0 text-dark" style="font-size:.85rem;">{{ $indicador->fuente }}</p>
            </div>
            @endif
            @if($indicador->dependencia_responsable)
            <div class="col-md-6 mb-2">
                <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                    <i class="fa fa-building mr-1"></i> Dependencia Responsable
                </div>
                <p class="mb-0 text-dark" style="font-size:.85rem;">{{ $indicador->dependencia_responsable }}</p>
            </div>
            @endif
        </div>

        {{-- Comentarios --}}
        @if($indicador->comentarios)
        <div class="mt-2 p-2 rounded" style="background:#fffbeb; border-left:3px solid #f59e0b;">
            <div class="text-muted font-weight-bold mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">
                <i class="fa fa-comment mr-1 text-warning"></i> Comentarios
            </div>
            <p class="mb-0 text-dark" style="font-size:.85rem;">{{ $indicador->comentarios }}</p>
        </div>
        @endif

    </div>
</div>

<div class="modal-footer justify-content-between" style="background:#f8fafc;">
    <a href="{{ route('pei.indicadores.modulo', $profile->id) }}"
       class="btn btn-sm btn-outline-primary font-weight-bold">
        <i class="fa fa-external-link-alt mr-1"></i> Editar en Módulo
    </a>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
</div>
