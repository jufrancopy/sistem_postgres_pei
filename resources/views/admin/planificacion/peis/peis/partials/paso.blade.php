@php
    $colores = [
        'completo'  => ['border' => '#28a745', 'badge' => 'badge-success', 'icono_color' => '#28a745', 'label' => 'Completo'],
        'pendiente' => ['border' => '#dee2e6', 'badge' => 'badge-secondary', 'icono_color' => '#adb5bd', 'label' => 'Pendiente'],
    ];
    $c = $colores[$estado];
@endphp

<div class="col-md-6 mb-4">
    <div class="card h-100 shadow-sm" style="border-left: 4px solid {{ $c['border'] }};">
        <div class="card-body">

            {{-- Header del paso --}}
            <div class="d-flex align-items-center mb-3">
                <div class="mr-3" style="
                    width:42px; height:42px; border-radius:50%;
                    background:{{ $estado === 'completo' ? '#e8f5e9' : '#f8f9fa' }};
                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa {{ $icono }}" style="color:{{ $c['icono_color'] }};font-size:1.1rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 font-weight-bold">
                            <span class="text-muted mr-1" style="font-size:.75rem;">Paso {{ $num }}</span>
                            {{ $titulo }}
                        </h6>
                        <span class="badge {{ $c['badge'] }} ml-2" style="font-size:.7rem;">
                            {{ $c['label'] }}
                        </span>
                    </div>
                    <small class="text-muted">{{ $descripcion }}</small>
                </div>
            </div>

            {{-- Métricas --}}
            @if(!empty($metricas))
            <div class="row mb-3">
                @foreach($metricas as $m)
                <div class="col-6 mb-2">
                    <div style="background:#f8f9fa; border-radius:6px; padding:.5rem .75rem;">
                        <div style="font-size:.68rem; text-transform:uppercase; letter-spacing:.05em; color:#6c757d;">
                            {{ $m['label'] }}
                        </div>
                        <div style="font-size:1rem; font-weight:700; color:#343a40;">
                            {{ $m['valor'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Acciones --}}
            @if(!empty($acciones))
            <div class="d-flex flex-wrap gap-1">
                @foreach($acciones as $a)
                <a href="{{ $a['url'] }}" class="btn btn-sm {{ $a['clase'] }} mr-1 mb-1">
                    {{ $a['label'] }}
                </a>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
