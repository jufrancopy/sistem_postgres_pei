@php
$semLabel = ['verde'=>'En meta','amarillo'=>'En proceso','rojo'=>'Crítico','sin-datos'=>'Sin datos'];
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
        <div style="font-size:.65rem;opacity:.65;text-transform:uppercase">Establecimiento</div>
        <div style="font-size:.85rem;font-weight:700">{{ $evaluacion->establecimiento?->nombre_oficial ?? '—' }}</div>
    </div>
</div>

{{-- Selector de columnas --}}
<div class="mb-3 p-2 rounded d-flex flex-wrap align-items-center"
     style="background:#f8faff;border:1px solid #e2e8f0;gap:.5rem">
    <span style="font-size:.72rem;font-weight:600;color:#475569;margin-right:.25rem">
        <i class="fa fa-columns mr-1"></i>Columnas visibles:
    </span>
    @foreach($columnas->unique('key') as $col)
    @php $esActual = $col['key'] === $colActual; @endphp
    <label class="mb-0 d-flex align-items-center"
           style="gap:.3rem;cursor:pointer;font-size:.75rem;
                  background:{{ $esActual ? '#e8eaf6' : '#fff' }};
                  border:1px solid {{ $esActual ? '#9fa8da' : '#e2e8f0' }};
                  border-radius:20px;padding:.2rem .6rem;
                  font-weight:{{ $esActual ? '600' : '400' }};
                  color:{{ $esActual ? '#1a237e' : '#64748b' }}">
        <input type="checkbox" class="col-toggle-modal"
               data-col="{{ $col['key'] }}"
               {{ $esActual ? 'checked' : '' }}
               style="cursor:pointer">
        {{ $col['label'] }}
        @if($esActual)<i class="fa fa-star ml-1" style="font-size:.6rem;color:#7986cb"></i>@endif
    </label>
    @endforeach
</div>

{{-- Tabla --}}
<div class="table-responsive" style="max-height:55vh;overflow-y:auto;border:1px solid #e2e8f0;border-radius:.5rem">
<table class="table table-bordered table-sm mb-0" style="font-size:.78rem;border-collapse:collapse;color:#212529">
    <thead style="position:sticky;top:0;z-index:10">
        <tr style="background:#1a237e;color:#fff;text-align:center">
            <th rowspan="2" style="text-align:left;min-width:120px;vertical-align:middle;background:#1a237e;border-color:#283593">Tipo de Prestación</th>
            <th rowspan="2" style="text-align:left;min-width:200px;vertical-align:middle;background:#1a237e;border-color:#283593">Servicio</th>
            @foreach($columnas->unique('key') as $col)
            <th class="col-header-modal col-modal-{{ $col['key'] }}"
                style="background:#283593;border-color:#3949ab;min-width:100px;font-size:.65rem;
                       {{ !($col['key'] === $colActual) ? 'display:none' : '' }}">
                <div style="font-size:.58rem;opacity:.7;font-weight:400">{{ $col['tipo_label'] }}</div>
                <div>{{ $col['label'] }}</div>
                @if($col['key'] === $colActual)
                <div style="font-size:.56rem;color:#90caf9;margin-top:.1rem">★ Este establecimiento</div>
                @endif
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($servicios->groupBy('tipo_prestacion') as $tipo => $items)
        @foreach($items as $i => $srv)
        @php
            $gap   = $gapItems->get($srv->servicio);
            $rowBg = $gap ? ($gap->estado === 'cumple' ? '#f0fdf4' : ($gap->estado === 'no_cumple' ? '#fef2f2' : '')) : '';
        @endphp
        <tr style="{{ $rowBg ? 'background:'.$rowBg : '' }}">
            @if($i === 0)
            <td rowspan="{{ $items->count() }}"
                style="font-weight:700;font-size:.72rem;color:#1a237e;vertical-align:middle;
                       background:#e8eaf6;border-right:3px solid #9fa8da;white-space:nowrap">
                {{ $tipo }}
            </td>
            @endif
            <td style="vertical-align:middle">
                {{ $srv->servicio }}
                @if($srv->requerido)
                <span style="font-size:.58rem;background:#e2e8f0;color:#475569;border-radius:3px;padding:1px 4px;margin-left:3px">req.</span>
                @endif
                @if($gap)
                <span class="ml-1">
                    @if($gap->estado === 'cumple') <i class="fa fa-check-circle text-success" style="font-size:.7rem"></i>
                    @elseif($gap->estado === 'no_cumple') <i class="fa fa-times-circle text-danger" style="font-size:.7rem"></i>
                    @endif
                </span>
                @endif
            </td>
            @foreach($columnas->unique('key') as $col)
            <td class="col-cell-modal col-modal-{{ $col['key'] }}"
                style="text-align:center;vertical-align:middle;
                       {{ $col['key'] === $colActual ? 'background:#e3f2fd' : '' }};
                       {{ !($col['key'] === $colActual) ? 'display:none' : '' }}">
                @if($srv->{$col['key']})
                <span style="display:inline-flex;align-items:center;justify-content:center;
                             width:20px;height:20px;border-radius:50%;background:#dcfce7">
                    <i class="fa fa-check" style="font-size:.58rem;color:#15803d"></i>
                </span>
                @else
                <span style="display:inline-flex;align-items:center;justify-content:center;
                             width:20px;height:20px;border-radius:50%;background:#fee2e2">
                    <i class="fa fa-times" style="font-size:.58rem;color:#b91c1c"></i>
                </span>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</div>

<div class="mt-2 d-flex flex-wrap" style="gap:.6rem;font-size:.7rem;color:#64748b">
    <span><i class="fa fa-check-circle text-success"></i> Cumple</span>
    <span><i class="fa fa-times-circle text-danger"></i> No cumple</span>
    <span style="background:#e2e8f0;padding:1px 5px;border-radius:3px">req. = requerido</span>
    <span><i class="fa fa-star" style="color:#7986cb;font-size:.65rem"></i> = este establecimiento</span>
</div>

<script>
document.querySelectorAll('.col-toggle-modal').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var col = this.dataset.col;
        var show = this.checked;
        document.querySelectorAll('.col-modal-' + col).forEach(function(el) {
            el.style.display = show ? '' : 'none';
        });
    });
});
</script>
