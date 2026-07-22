@php
$nombreNivel = $evaluacion->establecimiento?->complejidadTipo?->nombre ?? 'Sin clasificación';
$tipoLabel   = $evaluacion->establecimiento?->complejidadTipo?->tipo_establecimiento ?? '';
@endphp

{{-- Encabezado del nivel --}}
<div class="d-flex align-items-center mb-3 p-3 rounded"
     style="background:linear-gradient(135deg,#1a237e,#283593);color:#fff">
    <div style="flex:1">
        <div style="font-size:.65rem;opacity:.65;text-transform:uppercase;letter-spacing:.07em">Nivel evaluado</div>
        <div style="font-size:1rem;font-weight:700">{{ $nombreNivel }}</div>
        @if($tipoLabel)<div style="font-size:.75rem;opacity:.75">{{ $tipoLabel }}</div>@endif
    </div>
    <div class="text-right">
        <div style="font-size:.65rem;opacity:.65;text-transform:uppercase">Total servicios</div>
        <div style="font-size:1.5rem;font-weight:800">{{ $servicios->count() }}</div>
    </div>
</div>

{{-- Tabla --}}
<div class="table-responsive" style="max-height:62vh;overflow-y:auto;border:1px solid #e2e8f0;border-radius:.5rem">
<table class="table table-bordered table-sm mb-0"
       style="font-size:.78rem;border-collapse:collapse;color:#212529">
    <thead style="position:sticky;top:0;z-index:10">
        <tr style="background:#1a237e;color:#fff;text-align:center">
            <th rowspan="2" style="text-align:left;min-width:120px;vertical-align:middle;background:#1a237e;border-color:#283593">Tipo de Prestación</th>
            <th rowspan="2" style="text-align:left;min-width:220px;vertical-align:middle;background:#1a237e;border-color:#283593">Servicio</th>
            <th colspan="2" style="background:#1565c0;border-color:#1565c0;font-size:.68rem;padding:.5rem">
                {{ $nombreNivel }}
                @if($tipoLabel)<div style="font-size:.58rem;opacity:.7;font-weight:400;margin-top:.1rem">{{ $tipoLabel }} · ★ Este establecimiento</div>@endif
            </th>
        </tr>
        <tr style="background:#1565c0;color:#fff;text-align:center">
            <th style="width:90px;font-size:.65rem;font-weight:700;background:#1b5e20;border-color:#2e7d32">
                <i class="fa fa-check mr-1" style="font-size:.6rem"></i>Cumple
            </th>
            <th style="width:90px;font-size:.65rem;font-weight:700;background:#b71c1c;border-color:#c62828">
                <i class="fa fa-times mr-1" style="font-size:.6rem"></i>No cumple
            </th>
        </tr>
    </thead>
    <tbody>
    @foreach($servicios->groupBy('tipo_prestacion') as $tipo => $items)
        @foreach($items as $i => $srv)
        @php
            $gap   = $gapItems->get($srv->servicio);
            $rowBg = $gap
                ? ($gap->estado === 'cumple'    ? '#f0fdf4'
                : ($gap->estado === 'no_cumple' ? '#fef2f2' : ''))
                : '';
        @endphp
        <tr style="{{ $rowBg ? 'background:'.$rowBg : '' }}">
            @if($i === 0)
            <td rowspan="{{ $items->count() }}"
                style="font-weight:700;font-size:.72rem;color:#1a237e;vertical-align:middle;
                       background:#e8eaf6;border-right:3px solid #9fa8da;white-space:nowrap">
                {{ $tipo }}
            </td>
            @endif

            {{-- Servicio --}}
            <td style="vertical-align:middle">
                {{ $srv->servicio }}
                @if($srv->requerido)
                <span style="font-size:.58rem;background:#e2e8f0;color:#475569;border-radius:3px;padding:1px 4px;margin-left:3px">req.</span>
                @endif
            </td>

            {{-- Cumple / No cumple / Pendiente --}}
            @if($gap && $gap->estado === 'no_verificable')
            <td colspan="2" style="text-align:center;vertical-align:middle;background:#fffbeb;border-left:1px solid #fde68a;border-right:1px solid #fde68a">
                <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.68rem;color:#92400e;font-weight:600">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#fef3c7;border:1px solid #fcd34d">
                        <i class="fa fa-clock" style="font-size:.55rem;color:#d97706"></i>
                    </span>
                    Pendiente de verificar
                </span>
            </td>
            @else
            <td style="text-align:center;vertical-align:middle;background:#f0fdf4">
                @if($gap && $gap->estado === 'cumple')
                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:#dcfce7">
                    <i class="fa fa-check" style="font-size:.65rem;color:#15803d"></i>
                </span>
                @endif
            </td>
            <td style="text-align:center;vertical-align:middle;background:#fff5f5">
                @if($gap && $gap->estado === 'no_cumple')
                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:#fee2e2">
                    <i class="fa fa-times" style="font-size:.65rem;color:#b91c1c"></i>
                </span>
                @endif
            </td>
            @endif
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</div>

<div class="mt-2 d-flex flex-wrap" style="gap:.6rem;font-size:.7rem;color:#64748b">
    <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#dcfce7"><i class="fa fa-check" style="font-size:.5rem;color:#15803d"></i></span> Cumple</span>
    <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#fee2e2"><i class="fa fa-times" style="font-size:.5rem;color:#b91c1c"></i></span> No cumple</span>
    <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#fef3c7;border:1px solid #fcd34d"><i class="fa fa-clock" style="font-size:.5rem;color:#d97706"></i></span> Pendiente de verificar</span>
    <span style="background:#e2e8f0;padding:1px 5px;border-radius:3px">req.</span> = requerido
</div>
