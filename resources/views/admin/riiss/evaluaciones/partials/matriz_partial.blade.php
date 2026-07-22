@php
$nombreNivel = $evaluacion->establecimiento?->complejidadTipo?->nombre ?? 'Sin clasificación';
$tipoLabel   = $evaluacion->establecimiento?->complejidadTipo?->tipo_establecimiento ?? '';
@endphp

{{-- Encabezado nivel --}}
<div style="display:flex;align-items:center;margin-bottom:12px;padding:12px;border-radius:8px;background:linear-gradient(135deg,#1a237e,#283593);color:#fff">
    <div style="flex:1">
        <div style="font-size:.65rem;opacity:.65;text-transform:uppercase;letter-spacing:.07em">Nivel evaluado</div>
        <div style="font-size:1rem;font-weight:700">{{ $nombreNivel }}</div>
        @if($tipoLabel)<div style="font-size:.75rem;opacity:.75">{{ $tipoLabel }}</div>@endif
    </div>
    <div style="text-align:right">
        <div style="font-size:.65rem;opacity:.65;text-transform:uppercase">Total servicios</div>
        <div style="font-size:1.5rem;font-weight:800">{{ $servicios->count() }}</div>
    </div>
</div>

{{-- Filtros --}}
<div style="display:flex;flex-wrap:wrap;align-items:center;gap:.4rem;margin-bottom:8px">
    <span style="font-size:.72rem;font-weight:600;color:#475569">
        <i class="fa fa-filter mr-1"></i>Filtrar:
    </span>
    <button class="btn-filtro-mp active" data-filtro="requeridos">
        <i class="fa fa-star" style="font-size:.6rem"></i> Requeridos
    </button>
    <button class="btn-filtro-mp" data-filtro="todos">
        <i class="fa fa-list" style="font-size:.6rem"></i> Todos
    </button>
    <button class="btn-filtro-mp" data-filtro="opcionales">
        <i class="fa fa-circle" style="font-size:.6rem"></i> Opcionales
    </button>
    <button class="btn-filtro-mp" data-filtro="cumple">
        <i class="fa fa-check" style="font-size:.6rem;color:#15803d"></i> Cumple
    </button>
    <button class="btn-filtro-mp" data-filtro="no_cumple">
        <i class="fa fa-times" style="font-size:.6rem;color:#b91c1c"></i> No cumple
    </button>
    <button class="btn-filtro-mp" data-filtro="no_verificable">
        <i class="fa fa-clock" style="font-size:.6rem;color:#d97706"></i> Pendiente
    </button>
    <div style="margin-left:auto">
        <div style="position:relative">
            <i class="fa fa-search" style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:.65rem;color:#94a3b8"></i>
            <input type="text" id="buscadorMP" placeholder="Buscar servicio…"
                   style="padding:.3rem .5rem .3rem 1.6rem;border:1px solid #e2e8f0;border-radius:20px;font-size:.75rem;width:160px;outline:none;color:#334155">
        </div>
    </div>
</div>

<style>
.btn-filtro-mp{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .65rem;border-radius:20px;font-size:.72rem;font-weight:600;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;transition:all .15s}
.btn-filtro-mp:hover{border-color:#94a3b8;color:#334155}
.btn-filtro-mp.active{background:#1a237e;color:#fff;border-color:#1a237e}
#matrizModalTable tbody tr.mp-oculta{display:none}
</style>

{{-- Tabla --}}
<div style="width:100%;overflow-x:auto;max-height:55vh;overflow-y:auto;border:1px solid #e2e8f0;border-radius:.5rem">
<table style="width:100%;font-size:.78rem;border-collapse:collapse;color:#212529" id="matrizModalTable">
    <thead style="position:sticky;top:0;z-index:10">
        <tr style="background:#1a237e;color:#fff;text-align:center">
            <th rowspan="2" style="text-align:left;min-width:120px;vertical-align:middle;background:#1a237e;border-color:#283593">Tipo de Prestación</th>
            <th rowspan="2" style="text-align:left;min-width:200px;vertical-align:middle;background:#1a237e;border-color:#283593">Servicio</th>
            <th colspan="2" style="background:#1565c0;border-color:#1565c0;font-size:.68rem;padding:.5rem">
                {{ $nombreNivel }}
                @if($tipoLabel)<div style="font-size:.58rem;opacity:.7;font-weight:400;margin-top:.1rem">{{ $tipoLabel }} · ★ Este establecimiento</div>@endif
            </th>
        </tr>
        <tr style="background:#1565c0;color:#fff;text-align:center">
            <th style="width:80px;font-size:.65rem;font-weight:700;background:#1b5e20;border-color:#2e7d32">
                <i class="fa fa-check mr-1" style="font-size:.6rem"></i>Cumple
            </th>
            <th style="width:80px;font-size:.65rem;font-weight:700;background:#b71c1c;border-color:#c62828">
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
                : (!$srv->requerido ? '#fafafa' : '');
        @endphp
        <tr style="{{ $rowBg ? 'background:'.$rowBg : '' }}"
            data-req="{{ $srv->requerido ? '1' : '0' }}"
            data-eval="{{ $gap?->estado ?? '' }}"
            data-nombre="{{ strtolower($srv->servicio) }}">
            {{-- Tipo --}}
            <td style="font-weight:{{ $i === 0 ? '700' : '400' }};font-size:.72rem;
                       color:{{ $i === 0 ? '#1a237e' : 'transparent' }};
                       vertical-align:middle;background:#e8eaf6;
                       border-right:3px solid #9fa8da;white-space:nowrap;
                       {{ $i === 0 ? 'border-top:2px solid #c5cae9' : 'border-top:1px solid #e8eaf6' }};
                       user-select:none">
                {{ $i === 0 ? $tipo : '' }}
            </td>
            {{-- Servicio --}}
            <td style="vertical-align:middle">
                {{ $srv->servicio }}
                @if($srv->requerido)
                <span style="font-size:.58rem;background:#e2e8f0;color:#475569;border-radius:3px;padding:1px 4px;margin-left:3px">req.</span>
                @endif
            </td>
            {{-- Resultado evaluación --}}
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
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:#dcfce7">
                    <i class="fa fa-check" style="font-size:.62rem;color:#15803d"></i>
                </span>
                @elseif(!$gap && !$srv->requerido)
                <span style="font-size:.65rem;color:#cbd5e1" title="Servicio opcional — no evaluado">—</span>
                @endif
            </td>
            <td style="text-align:center;vertical-align:middle;background:#fff5f5">
                @if($gap && $gap->estado === 'no_cumple')
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:#fee2e2">
                    <i class="fa fa-times" style="font-size:.62rem;color:#b91c1c"></i>
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

<div style="display:flex;flex-wrap:wrap;gap:.6rem;font-size:.7rem;color:#64748b;margin-top:8px">
    <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#dcfce7"><i class="fa fa-check" style="font-size:.5rem;color:#15803d"></i></span> Cumple</span>
    <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#fee2e2"><i class="fa fa-times" style="font-size:.5rem;color:#b91c1c"></i></span> No cumple</span>
    <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#fef3c7;border:1px solid #fcd34d"><i class="fa fa-clock" style="font-size:.5rem;color:#d97706"></i></span> Pendiente</span>
    <span style="color:#cbd5e1">—</span> Servicio opcional no evaluado
    <span style="background:#e2e8f0;padding:1px 5px;border-radius:3px">req.</span> = requerido
</div>

<script>
(function(){
    var filtroActual = 'requeridos';
    var busqueda = '';

    function aplicarFiltro(){
        document.querySelectorAll('#matrizModalTable tbody tr').forEach(function(tr){
            var req    = tr.dataset.req   === '1';
            var estado = tr.dataset.eval  || '';
            var nom    = (tr.dataset.nombre || '').toLowerCase();
            var pasaBusqueda = busqueda === '' || nom.indexOf(busqueda) !== -1;
            var pasaFiltro = true;
            if(filtroActual === 'requeridos')     pasaFiltro = req;
            if(filtroActual === 'opcionales')     pasaFiltro = !req;
            if(filtroActual === 'cumple')         pasaFiltro = estado === 'cumple';
            if(filtroActual === 'no_cumple')      pasaFiltro = estado === 'no_cumple';
            if(filtroActual === 'no_verificable') pasaFiltro = estado === 'no_verificable';
            tr.classList.toggle('mp-oculta', !(pasaFiltro && pasaBusqueda));
        });
    }

    document.querySelectorAll('.btn-filtro-mp').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.btn-filtro-mp').forEach(function(b){ b.classList.remove('active'); });
            this.classList.add('active');
            filtroActual = this.dataset.filtro;
            aplicarFiltro();
        });
    });

    var buscador = document.getElementById('buscadorMP');
    if(buscador){
        buscador.addEventListener('input', function(){
            busqueda = this.value.toLowerCase().trim();
            aplicarFiltro();
        });
    }

    aplicarFiltro();
})();
</script>
