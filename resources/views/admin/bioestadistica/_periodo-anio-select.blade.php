{{-- Desplegable de año estadístico (techo: año actual, piso: actual-5). --}}
@php
    use App\Application\Bioestadistica\Reports\PeriodContext;
    $name = $name ?? 'periodo_anio';
    $id = $id ?? $name;
    $required = (bool) ($required ?? false);
    $allowEmpty = (bool) ($allowEmpty ?? false);
    $emptyLabel = $emptyLabel ?? 'Todos';
    $selectClass = $selectClass ?? 'bio-select2';
    $rawValue = $value ?? null;
    $selected = ($rawValue !== null && $rawValue !== '') ? (int) $rawValue : null;
    $years = PeriodContext::selectableYears($selected);
@endphp
<select
    class="form-control {{ $selectClass }}"
    name="{{ $name }}"
    id="{{ $id }}"
    data-placeholder="{{ $placeholder ?? 'Año' }}"
    @if($allowEmpty) data-allow-clear="1" @endif
    @if($required) required @endif
    @disabled($disabled ?? false)
>
    @if($allowEmpty)
        <option value="">{{ $emptyLabel }}</option>
    @endif
    @foreach($years as $year)
        <option value="{{ $year }}" @selected($selected === (int) $year)>{{ $year }}</option>
    @endforeach
</select>
