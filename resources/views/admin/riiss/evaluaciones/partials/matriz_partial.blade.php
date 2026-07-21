@php
// Filtrar servicios del nivel del establecimiento
$serviciosFiltrados = $colActual
    ? $servicios->filter(fn($s) => (bool)$s->{$colActual})
    : $servicios;
$porTipo = $serviciosFiltrados->groupBy('tipo_prestacion');
$totalServicios = $serviciosFiltrados->count();
$totalCumple    = $serviciosFiltrados->filter(fn($s) => $gapItems->get($s->servicio)?->estado === 'cumple')->count();
$totalNoCumple  = $serviciosFiltrados->filter(fn($s) => $gapItems->get($s->servicio)?->estado === 'no_cumple')->count();
$totalPendiente = $totalServicios - $totalCumple - $totalNoCumple;
$pctCumple = $totalServicios > 0 ? round(($totalCumple / $totalServicios) * 100) : 0;
@endphp

{{-- Header nivel + resumen --}}
<div style="background:linear-gradient(135deg,#1a237e,#283593);border-radius:.75rem;padding:1rem 1.25rem;margin-bottom:1rem;color:#fff">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
        <div>
            <div style="font-size:.62rem;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem">Nivel evaluado</div>
            <div style="font-size:.95rem;font-weight:700">{{ $nombreNivel }}</div>
            @if($tipoLabel)<div style="font-size:.75rem;opacity:.7">{{ $tipoLabel }}</div>@endif
        </div>
        <div style="display:flex;gap:.75rem;text-align:center">
            <div>
                <div style="font-size:1.4rem;font-weight:800;color:#4ade80">{{ $totalCumple }}</div>
                <div style="font-size:.62rem;opacity:.65;text-transform:uppercase">Cumple</div>
            </div>
            <div>
                <div style="font-size:1.4rem;font-weight:800;color:#f87171">{{ $totalNoCumple }}</div>
                <div style="font-size:.62rem;opacity:.65;text-transform:uppercase">No cumple</div>
            </div>
            <div>
                <div style="font-size:1.4rem;font-weight:800;color:#fbbf24">{{ $totalPendiente }}</div>
                <div style="font-size:.62rem;opacity:.65;text-transform:uppercase">Pendiente</div>
            </div>
            <div>
                <div style="font-size:1.4rem;font-weight:800">{{ $totalServicios }}</div>
                <div style="font-size:.62rem;opacity:.65;text-transform:uppercase">Total</div>
            </div>
        </div>
    </div>
    {{-- Barra de progreso --}}
    <div style="margin-top:.75rem">
        <div style="background:rgba(255,255,255,.15);border-radius:4px;height:6px;overflow:hidden">
            <div style="width:{{ $pctCumple }}%;height:100%;background:#4ade80;border-radius:4px;transition:width .5s"></div>
        </div>
        <div style="font-size:.7rem;opacity:.7;margin-top:.25rem">{{ $pctCumple }}% de servicios verificados</div>
    </div>
</div>

{{-- Tabla de servicios --}}
<div style="border:1px solid #e2e8f0;border-radius:.5rem;overflow:hidden">
@forelse($porTipo as $tipo => $items)

{{-- Encabezado de tipo --}}
<div style="background:#e8eaf6;padding:.4rem .9rem;font-size:.72rem;font-weight:700;color:#1a237e;border-bottom:1px solid #c5cae9;display:flex;align-items:center;justify-content:space-between">
    <span><i class="fa fa-layer-group mr-1" style="font-size:.65rem"></i>{{ $tipo }}</span>
    <span style="font-weight:400;color:#64748b;font-size:.68rem">{{ $items->count() }} servicios</span>
</div>

@foreach($items->sortBy('servicio') as $srv)
@php
    $gap    = $gapItems->get($srv->servicio);
    $estado = $gap?->estado ?? 'pendiente';
    $req    = $srv->requerido;
    $rowBg  = $estado === 'cumple' ? '#f0fdf4' : ($estado === 'no_cumple' ? '#fff5f5' : '');
@endphp
<div style="display:flex;align-items:center;padding:.45rem .9rem;border-bottom:1px solid #f1f5f9;background:{{ $rowBg }};gap:.75rem">

    {{-- Icono estado --}}
    <div style="flex-shrink:0;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;
                background:{{ $estado === 'cumple' ? '#dcfce7' : ($estado === 'no_cumple' ? '#fee2e2' : '#f1f5f9') }}">
        @if($estado === 'cumple')
            <i class="fa fa-check" style="font-size:.7rem;color:#15803d"></i>
        @elseif($estado === 'no_cumple')
            <i class="fa fa-times" style="font-size:.7rem;color:#b91c1c"></i>
        @else
            <i class="fa fa-minus" style="font-size:.7rem;color:#94a3b8"></i>
        @endif
    </div>

    {{-- Nombre del servicio --}}
    <div style="flex:1;min-width:0">
        <div style="font-size:.8rem;color:#1e293b;line-height:1.3">
            {{ $srv->servicio }}
        </div>
        @if($srv->grupo_servicio)
        <div style="font-size:.68rem;color:#94a3b8">{{ $srv->grupo_servicio }}</div>
        @endif
    </div>

    {{-- Badges --}}
    <div style="flex-shrink:0;display:flex;align-items:center;gap:.35rem">
        @if($req)
        <span style="font-size:.62rem;background:#fef3c7;color:#92400e;border-radius:20px;padding:.15rem .5rem;font-weight:600;border:1px solid #fde68a">
            req.
        </span>
        @endif
        @if($estado === 'cumple')
        <span style="font-size:.68rem;background:#dcfce7;color:#15803d;border-radius:20px;padding:.15rem .6rem;font-weight:600">Cumple</span>
        @elseif($estado === 'no_cumple')
        <span style="font-size:.68rem;background:#fee2e2;color:#b91c1c;border-radius:20px;padding:.15rem .6rem;font-weight:600">No cumple</span>
        @else
        <span style="font-size:.68rem;background:#f1f5f9;color:#64748b;border-radius:20px;padding:.15rem .6rem;font-weight:600">Sin evaluar</span>
        @endif
    </div>

</div>
@endforeach

@empty
<div style="text-align:center;padding:2rem;color:#94a3b8;font-size:.82rem">
    <i class="fa fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4"></i>
    Sin servicios para este nivel.
</div>
@endforelse
</div>

<div style="margin-top:.75rem;font-size:.7rem;color:#94a3b8;display:flex;flex-wrap:wrap;gap:.6rem">
    <span style="display:inline-flex;align-items:center;gap:.3rem">
        <span style="width:16px;height:16px;border-radius:50%;background:#dcfce7;display:inline-flex;align-items:center;justify-content:center"><i class="fa fa-check" style="font-size:.5rem;color:#15803d"></i></span> Cumple
    </span>
    <span style="display:inline-flex;align-items:center;gap:.3rem">
        <span style="width:16px;height:16px;border-radius:50%;background:#fee2e2;display:inline-flex;align-items:center;justify-content:center"><i class="fa fa-times" style="font-size:.5rem;color:#b91c1c"></i></span> No cumple
    </span>
    <span style="display:inline-flex;align-items:center;gap:.3rem">
        <span style="width:16px;height:16px;border-radius:50%;background:#f1f5f9;display:inline-flex;align-items:center;justify-content:center"><i class="fa fa-minus" style="font-size:.5rem;color:#94a3b8"></i></span> Sin evaluar
    </span>
    <span style="background:#fef3c7;color:#92400e;border-radius:20px;padding:.1rem .4rem;font-size:.65rem;font-weight:600;border:1px solid #fde68a">req.</span> = servicio requerido
</div>
