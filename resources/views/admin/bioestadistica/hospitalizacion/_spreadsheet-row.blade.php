@php
    $value = static fn (string $key, mixed $default = null) => $row[$key] ?? $default;
    $name = static fn (string $key) => "rows[{$index}][{$key}]";
    $inputClass = 'form-control form-control-sm';
@endphp
<tr>
    <td data-row-number></td>
    <td>
        <input type="hidden" name="{{ $name('id') }}" value="{{ $value('id') }}">
        @can('bio.hosp.view_pii')
            <input class="{{ $inputClass }}" style="width:110px" name="{{ $name('cedula') }}" value="{{ $value('cedula') }}" maxlength="30">
        @else
            <input class="{{ $inputClass }}" style="width:110px" value="{{ $value('cedula_visible') }}" disabled>
            <input type="hidden" name="{{ $name('cedula') }}" value="">
        @endcan
    </td>
    <td>
        <select class="{{ $inputClass }}" style="width:65px" name="{{ $name('sexo') }}">
            <option value=""></option>
            <option value="M" @selected($value('sexo') === 'M')>M</option>
            <option value="F" @selected($value('sexo') === 'F')>F</option>
        </select>
    </td>
    <td><input class="{{ $inputClass }}" style="width:70px" type="number" min="0" max="130" name="{{ $name('edad') }}" value="{{ $value('edad') }}"></td>
    <td><input class="{{ $inputClass }}" style="width:100px" name="{{ $name('seguro') }}" value="{{ $value('seguro') }}" maxlength="80"></td>
    <td><input class="{{ $inputClass }}" style="width:135px" type="date" name="{{ $name('fecha_ingreso') }}" value="{{ $value('fecha_ingreso') }}"></td>
    <td><input class="{{ $inputClass }}" style="width:135px" type="date" name="{{ $name('fecha_egreso') }}" value="{{ $value('fecha_egreso') }}"></td>
    <td>
        <select class="{{ $inputClass }}" style="width:145px" name="{{ $name('servicio') }}">
            <option value=""></option>
            @foreach($servicios as $code => $label)
                <option value="{{ $code }}" @selected($value('servicio') === $code)>{{ $label }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select class="{{ $inputClass }}" style="width:150px" name="{{ $name('tipo_alta') }}">
            <option value=""></option>
            @foreach($tiposAlta as $code => $label)
                <option value="{{ $code }}" @selected($value('tipo_alta') === $code)>{{ $label }}</option>
            @endforeach
        </select>
    </td>
    <td><input class="{{ $inputClass }} text-uppercase" style="width:90px" name="{{ $name('cie10') }}" value="{{ $value('cie10') }}" maxlength="10"></td>
    <td><input class="{{ $inputClass }}" style="width:220px" name="{{ $name('diagnostico') }}" value="{{ $value('diagnostico') }}" maxlength="400"></td>
    <td class="text-center">
        <input type="hidden" name="{{ $name('cirugia') }}" value="0">
        <input type="checkbox" name="{{ $name('cirugia') }}" value="1" @checked($value('cirugia'))>
    </td>
    <td>
        <select class="{{ $inputClass }}" style="width:145px" name="{{ $name('tipo_cirugia') }}">
            <option value=""></option>
            @foreach($tiposCirugia as $code => $label)
                <option value="{{ $code }}" @selected($value('tipo_cirugia') === $code)>{{ $label }}</option>
            @endforeach
        </select>
    </td>
    <td class="text-center">
        <input type="hidden" name="{{ $name('cesarea') }}" value="0">
        <input type="checkbox" name="{{ $name('cesarea') }}" value="1" @checked($value('cesarea'))>
    </td>
    <td class="text-center">
        <input type="hidden" name="{{ $name('recien_nacido') }}" value="0">
        <input type="checkbox" name="{{ $name('recien_nacido') }}" value="1" @checked($value('recien_nacido'))>
    </td>
    <td class="text-center">
        <input type="hidden" name="{{ $name('eliminar') }}" value="0">
        <input type="checkbox" name="{{ $name('eliminar') }}" value="1" @checked($value('eliminar'))>
    </td>
</tr>
