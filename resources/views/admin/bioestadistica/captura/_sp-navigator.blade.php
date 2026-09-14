@php
    $establecimientoNombre = $establecimientoNombre
        ?? $record->establecimiento->nombre
        ?? '';
    $periodLabel = \Carbon\Carbon::create($periodo_anio, $periodo_mes, 1)->translatedFormat('F Y');
@endphp
<div class="card border mb-3">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap align-items-center mb-1" style="gap:6px">
            <span class="text-muted mr-1">
                Planillas de <strong>{{ $establecimientoNombre }}</strong>
                · {{ $periodLabel }}
            </span>
        </div>
        <div class="d-flex flex-wrap" style="gap:6px">
            @foreach($siblingPlanillas as $item)
                @if($item['current'])
                    <span class="btn btn-info btn-sm mb-0" disabled>{{ $item['formulario']->codigo }}</span>
                @elseif($item['url'])
                    <a class="btn btn-outline-info btn-sm mb-0" href="{{ $item['url'] }}">{{ $item['formulario']->codigo }}</a>
                @elseif(auth()->user()->can('bio.record.create'))
                    <form method="POST" action="{{ route('bioestadistica.captura.store') }}" class="d-inline mb-0">
                        @csrf
                        <input type="hidden" name="formulario_id" value="{{ $item['formulario']->id }}">
                        <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
                        <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
                        <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">
                        @if(!empty($record->organo_id))
                            <input type="hidden" name="organo_id" value="{{ $record->organo_id }}">
                        @endif
                        <button class="btn btn-outline-secondary btn-sm mb-0" title="Iniciar {{ $item['formulario']->codigo }} en este establecimiento">
                            {{ $item['formulario']->codigo }}
                        </button>
                    </form>
                @else
                    <span class="btn btn-outline-secondary btn-sm mb-0 disabled">{{ $item['formulario']->codigo }}</span>
                @endif
            @endforeach
        </div>
        <small class="text-muted d-block mt-1">Pase a otro SP del mismo establecimiento y período sin volver al listado. El botón con borde gris aún no tiene carga.</small>
    </div>
</div>
