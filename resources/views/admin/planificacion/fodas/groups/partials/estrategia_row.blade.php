{{--
    Partial: fila de estrategia en el cruce de ambientes
    Variables: $vi, $tipo, $badges1, $color1, $prefix1, $badges2, $color2, $prefix2
--}}
<div class="mb-2 pb-2 border-bottom" style="font-size:.82rem">
    {{-- Badges de aspectos relacionados --}}
    <div class="mb-1">
        @foreach($badges1 as $b)
            <span class="badge badge-{{ $color1 }} mr-1" style="font-size:.65rem"
                  data-toggle="tooltip" title="{{ $b->name }}">
                {{ $prefix1 }}{{ $b->id }}
            </span>
        @endforeach
        @foreach($badges2 as $b)
            <span class="badge badge-{{ $color2 }} mr-1" style="font-size:.65rem"
                  data-toggle="tooltip" title="{{ $b->name }}">
                {{ $prefix2 }}{{ $b->id }}
            </span>
        @endforeach
    </div>

    {{-- Texto de la estrategia --}}
    <div style="color:#374151;line-height:1.4">{{ $vi->estrategia }}</div>

    {{-- Acciones --}}
    <div class="mt-1">
        <a href="{{ route('foda-cruce-ambientes.edit', $vi->id) }}"
           class="btn btn-xs btn-outline-secondary py-0 px-1 mr-1" style="font-size:.7rem">
            <i class="fa fa-edit"></i>
        </a>
        {!! Form::open(['route' => ['foda-cruce-ambientes.destroy', $vi->id], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
        <button type="button" class="btn btn-xs btn-outline-danger py-0 px-1 btnEliminarEstrategia"
                style="font-size:.7rem"
                data-texto="{{ Str::limit($vi->estrategia, 40) }}">
            <i class="fa fa-trash"></i>
        </button>
        {!! Form::close() !!}
    </div>
</div>
