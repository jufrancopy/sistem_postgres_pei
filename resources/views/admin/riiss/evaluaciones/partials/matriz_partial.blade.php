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

{{-- Selector de columnas --}}
<div style="display:flex;flex-wrap:wrap;align-items:center;gap:.4rem;margin-bottom:8px;padding:8px;border-radius:6px;background:#f8faff;border:1px solid #e2e8f0">
    <span style="font-size:.72rem;font-weight:600;color:#475569;margin-right:.25rem">
        <i class="fa fa-columns" style="margin-right:3px"></i>Columnas visibles:
    </span>
    @foreach($columnas as $col)
    @php $esActual = $col['key'] === $colActual; @endphp
    <label style="margin:0;display:inline-flex;align-items:center;gap:.3rem;cursor:pointer;font-size:.75rem;
                  background:{{ $esActual ? '#e8eaf6' : '#fff' }};
                  border:1px solid {{ $esActual ? '#9fa8da' : '#e2e8f0' }};
                  border-radius:20px;padding:.2rem .6rem;
                  font-weight:{{ $esActual ? '600' : '400' }};
                  color:{{ $esActual ? '#1a237e' : '#64748b' }}">
        <input type="checkbox"
               class="mp-col-toggle"
               data-col="mp-grado-{{ $col['grado'] }}"
               @if($esActual) checked @endif
               style="cursor:pointer">
        Grado {{ $col['grado'] }} · {{ $col['tipo_label'] }}
        @if($esActual)<i class="fa fa-star" style="font-size:.6rem;color:#7986cb;margin-left:2px"></i>@endif
    </label>
    @endforeach
</div>

{{-- Filtros + buscador --}}
<div style="display:flex;flex-wrap:wrap;align-items:center;gap:.4rem;margin-bottom:8px">
    <span style="font-size:.72rem;font-weight:600;color:#475569">
        <i class="fa fa-filter" style="margin-right:3px"></i>Filtrar:
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
    <div style="margin-left:auto;position:relative">
        <i class="fa fa-search" style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:.65rem;color:#94a3b8"></i>
        <input type="text" id="buscadorMP" placeholder="Buscar servicio…"
               style="padding:.3rem .5rem .3rem 1.6rem;border:1px solid #e2e8f0;border-radius:20px;font-size:.75rem;width:160px;outline:none;color:#334155">
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
<table style="width:100%;font-size:.78rem;border-collapse:collapse;color:#212529;border:1px solid #dee2e6" id="matrizModalTable">
    <thead style="position:sticky;top:0;z-index:10">
        {{-- Fila 1: nombre nivel --}}
        <tr style="background:#1a237e;color:#fff;text-align:center">
            <th rowspan="3" style="text-align:left;min-width:110px;vertical-align:middle;background:#1a237e;border-color:#283593;padding:.4rem .5rem">Tipo</th>
            <th rowspan="3" style="text-align:left;min-width:180px;vertical-align:middle;background:#1a237e;border-color:#283593;padding:.4rem .5rem">Servicio</th>
            @foreach($columnas as $col)
            @php $esActual = $col['key'] === $colActual; @endphp
            <th @if($esActual) colspan="2" @endif
                class="mp-grado-{{ $col['grado'] }}"
                style="font-size:.65rem;font-weight:700;padding:.4rem .3rem;min-width:{{ $esActual ? '150px' : '65px' }};
                       background:{{ $esActual ? '#1565c0' : '#283593' }};
                       border-color:{{ $esActual ? '#1565c0' : '#3949ab' }};
                       {{ $esActual ? 'border-left:3px solid #90caf9;border-right:3px solid #90caf9' : '' }}">
                {{ $col['label'] }}
            </th>
            @endforeach
        </tr>
        {{-- Fila 2: tipo establecimiento --}}
        <tr style="background:#283593;color:#fff;text-align:center">
            @foreach($columnas as $col)
            @php $esActual = $col['key'] === $colActual; @endphp
            <th @if($esActual) colspan="2" @endif
                class="mp-grado-{{ $col['grado'] }}"
                style="font-size:.6rem;font-weight:400;padding:.25rem;
                       background:{{ $esActual ? '#1565c0' : '#283593' }};
                       border-color:{{ $esActual ? '#1565c0' : '#3949ab' }}">
                {{ $col['tipo_label'] }}
                @if($esActual)<span style="display:block;font-size:.55rem;opacity:.8">★ Este establecimiento</span>@endif
            </th>
            @endforeach
        </tr>
        {{-- Fila 3: Cumple/No cumple para actual, Aplica para otras --}}
        <tr style="background:#3949ab;color:#fff;text-align:center">
            @foreach($columnas as $col)
            @php $esActual = $col['key'] === $colActual; @endphp
            @if($esActual)
            <th class="mp-grado-{{ $col['grado'] }}" style="width:75px;font-size:.62rem;font-weight:700;background:#1b5e20;border-color:#2e7d32">
                <i class="fa fa-check" style="font-size:.55rem;margin-right:2px"></i>Cumple
            </th>
            <th class="mp-grado-{{ $col['grado'] }}" style="width:75px;font-size:.62rem;font-weight:700;background:#b71c1c;border-color:#c62828">
                <i class="fa fa-times" style="font-size:.55rem;margin-right:2px"></i>No cumple
            </th>
            @else
            <th class="mp-grado-{{ $col['grado'] }}" style="font-size:.6rem;font-weight:400;background:#3949ab;border-color:#3949ab">
                Aplica
            </th>
            @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($servicios->groupBy('tipo_prestacion') as $tipo => $items)
        @foreach($items as $i => $srv)
        @php
            $gap           = $gapItems->get($srv->servicio);
            $aplicaAlNivel = $colActual ? (bool)($srv->{$colActual} ?? false) : false;
            $rowBg = $gap
                ? ($gap->estado === 'cumple'    ? '#f0fdf4'
                : ($gap->estado === 'no_cumple' ? '#fef2f2' : ''))
                : '';
        @endphp
        <tr style="{{ $rowBg ? 'background:'.$rowBg : '' }}"
            data-req="{{ $srv->requerido ? '1' : '0' }}"
            data-eval="{{ $gap?->estado ?? '' }}"
            data-nombre="{{ strtolower($srv->servicio) }}"
            data-aplica="{{ $aplicaAlNivel ? '1' : '0' }}">
            <td style="font-weight:{{ $i === 0 ? '700' : '400' }};font-size:.72rem;
                       color:{{ $i === 0 ? '#1a237e' : 'transparent' }};
                       vertical-align:middle;background:#e8eaf6;
                       border-right:3px solid #9fa8da;white-space:nowrap;
                       {{ $i === 0 ? 'border-top:2px solid #c5cae9' : 'border-top:1px solid #e8eaf6' }};
                       user-select:none;padding:.3rem .5rem">
                {{ $i === 0 ? $tipo : '' }}
            </td>
            <td style="vertical-align:middle;padding:.3rem .5rem">
                {{ $srv->servicio }}
                @if($srv->requerido)
                <span style="font-size:.58rem;background:#e2e8f0;color:#475569;border-radius:3px;padding:1px 4px;margin-left:3px">req.</span>
                @endif
            </td>
            @foreach($columnas as $col)
            @php $aplica = (bool)($srv->{$col['key']} ?? false); @endphp
            @if($col['key'] === $colActual)
                @if($gap && $gap->estado === 'no_verificable')
                <td colspan="2" class="mp-grado-{{ $col['grado'] }}"
                    style="text-align:center;vertical-align:middle;background:#fffbeb">
                    <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.65rem;color:#92400e;font-weight:600">
                        <i class="fa fa-clock" style="color:#d97706"></i> Pendiente de verificar
                    </span>
                </td>
                @elseif(!$srv->requerido && !$gap)
                <td colspan="2" class="mp-grado-{{ $col['grado'] }}"
                    style="text-align:center;vertical-align:middle;background:#f8f9fa;border-left:1px solid #e9ecef;border-right:1px solid #e9ecef">
                    <span style="font-size:.68rem;color:#adb5bd;font-style:italic">
                        <i class="fa fa-info-circle mr-1" style="font-size:.6rem"></i>Servicio opcional · no incluido en la evaluación
                    </span>
                </td>
                @else
                <td class="mp-grado-{{ $col['grado'] }}" style="text-align:center;vertical-align:middle;background:#f0fdf4;border-left:2px solid #90caf9">
                    @if($gap && $gap->estado === 'cumple')
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#dcfce7">
                        <i class="fa fa-check" style="font-size:.58rem;color:#15803d"></i>
                    </span>
                    @elseif(!$aplica)
                    <span style="font-size:.65rem;color:#cbd5e1">—</span>
                    @endif
                </td>
                <td class="mp-grado-{{ $col['grado'] }}" style="text-align:center;vertical-align:middle;background:#fff5f5;border-right:2px solid #90caf9">
                    @if($gap && $gap->estado === 'no_cumple')
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#fee2e2">
                        <i class="fa fa-times" style="font-size:.58rem;color:#b91c1c"></i>
                    </span>
                    @elseif(!$aplica)
                    <span style="font-size:.65rem;color:#cbd5e1">—</span>
                    @endif
                </td>
                @endif
            @else
            <td class="mp-grado-{{ $col['grado'] }}" style="text-align:center;vertical-align:middle">
                @if($aplica)
                <i class="fa fa-check" style="color:#15803d"></i>
                @else
                <i class="fa fa-times" style="color:#dc2626"></i>
                @endif
            </td>
            @endif
            @endforeach
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</div>

<div style="display:flex;flex-wrap:wrap;gap:.6rem;font-size:.7rem;color:#64748b;margin-top:8px">
    <span><i class="fa fa-check" style="color:#15803d"></i> Cumple</span>
    <span><i class="fa fa-times" style="color:#b91c1c"></i> No cumple</span>
    <span><i class="fa fa-clock" style="color:#d97706"></i> Pendiente</span>
    <span style="background:#e2e8f0;padding:1px 5px;border-radius:3px">req.</span> = requerido
    <span style="background:#e8eaf6;padding:1px 5px;border-radius:3px">★</span> = este establecimiento
</div>

<script>
(function(){
    var filtroActual = 'requeridos';
    var busqueda = '';

    function aplicarFiltro(){
        document.querySelectorAll('#matrizModalTable tbody tr').forEach(function(tr){
            var req    = tr.dataset.req    === '1';
            var estado = tr.dataset.eval   || '';
            var aplica = tr.dataset.aplica === '1';
            var nom    = (tr.dataset.nombre || '').toLowerCase();
            var pasaBusqueda = busqueda === '' || nom.indexOf(busqueda) !== -1;
            var pasaFiltro;
            if(filtroActual === 'requeridos')          pasaFiltro = req && aplica;
            else if(filtroActual === 'opcionales')     pasaFiltro = !req && aplica;
            else if(filtroActual === 'cumple')         pasaFiltro = estado === 'cumple';
            else if(filtroActual === 'no_cumple')      pasaFiltro = estado === 'no_cumple';
            else if(filtroActual === 'no_verificable') pasaFiltro = estado === 'no_verificable';
            else pasaFiltro = true;
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
    if(buscador) buscador.addEventListener('input', function(){
        busqueda = this.value.toLowerCase().trim();
        aplicarFiltro();
    });

    // col-toggle: ocultar al inicio las no-checked, luego toggle
    document.querySelectorAll('.mp-col-toggle').forEach(function(cb){
        if(!cb.checked){
            document.querySelectorAll('.' + cb.dataset.col).forEach(function(el){
                el.style.display = 'none';
            });
        }
        cb.addEventListener('change', function(){
            var show = this.checked;
            document.querySelectorAll('.' + this.dataset.col).forEach(function(el){
                el.style.display = show ? '' : 'none';
            });
        });
    });

    aplicarFiltro();
})();
</script>
