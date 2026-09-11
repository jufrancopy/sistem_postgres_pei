{{-- Desplegable de dependencia bajo el ancla del establecimiento. --}}
@php
    $organoSelected = (int) ($organoSelected ?? 0);
    $cortesList = collect($cortes ?? []);
    $corteRequired = (bool) ($corteRequired ?? $cortesList->isNotEmpty());
    $selectId = $selectId ?? 'bio-organo-corte';
    $selectClass = $selectClass ?? 'bio-select2';
    $helpText = $helpText ?? 'Busque y elija la dependencia (departamento, servicio, sección, etc.).';
@endphp
<div class="form-group {{ $wrapperClass ?? 'col-md-12' }}" data-bio-corte-wrap>
    <label class="{{ $labelClass ?? '' }}">Dependencia <span data-bio-corte-required-mark class="{{ $corteRequired ? '' : 'd-none' }}">*</span></label>
    <select
        class="form-control {{ $selectClass }}"
        name="organo_id"
        id="{{ $selectId }}"
        data-placeholder="Buscar dependencia…"
        data-allow-clear="{{ $corteRequired ? '0' : '1' }}"
        data-bio-corte-select
        @if($corteRequired) required @endif
        @disabled($cortesList->isEmpty() && !($alwaysEnabled ?? false))
    >
        <option value="">{{ $corteRequired ? 'Seleccione' : 'Sin dependencia (establecimiento completo)' }}</option>
        @foreach($cortesList as $corte)
            <option value="{{ $corte['id'] }}" @selected($organoSelected === (int) $corte['id'])>
                {{ $corte['label'] }}
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted" data-bio-corte-help>
        @if($corteRequired || $cortesList->isNotEmpty())
            {{ $helpText }}
        @else
            Este establecimiento no tiene organigrama vinculado; la carga queda a nivel establecimiento.
        @endif
    </small>
</div>
