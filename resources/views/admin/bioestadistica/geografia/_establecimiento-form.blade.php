@php
    $registro = $establecimiento ?? null;
    $departamentoSeleccionado = old('departamento_id', $registro?->distrito?->departamento_id);
    $distritoSeleccionado = old('distrito_id', $registro?->distrito_id);
@endphp

<div class="form-row">
    <div class="form-group col-md-3">
        <label>ID establecimiento *</label>
        <input class="form-control" name="codigo" value="{{ old('codigo', $registro?->codigo) }}" maxlength="30" required>
    </div>
    <div class="form-group col-md-6">
        <label>Establecimiento *</label>
        <input class="form-control" name="nombre" value="{{ old('nombre', $registro?->nombre) }}" maxlength="250" required>
    </div>
    <div class="form-group col-md-3">
        <label>Código SIH</label>
        <input class="form-control" name="codigo_sih" value="{{ old('codigo_sih', $registro?->codigo_sih) }}" maxlength="30">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label>Departamento/región *</label>
        <select class="form-control bio-departamento" name="departamento_id" data-target="{{ $formId }}-distrito" required>
            <option value="">Seleccione</option>
            @foreach($departamentos as $departamento)
                <option value="{{ $departamento->id }}" @selected((string) $departamentoSeleccionado === (string) $departamento->id)>
                    {{ $departamento->codigo }} - {{ $departamento->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Distrito *</label>
        <select class="form-control" id="{{ $formId }}-distrito" name="distrito_id" data-selected="{{ $distritoSeleccionado }}" required>
            <option value="">Seleccione departamento/región</option>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Microred</label>
        <select class="form-control" name="microred_id">
            <option value="">Sin microred</option>
            @foreach($microredes as $microred)
                <option value="{{ $microred->id }}" @selected((string) old('microred_id', $registro?->microred_id) === (string) $microred->id)>{{ $microred->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Área de gestión</label>
        <select class="form-control" name="area_gestion_id">
            <option value="">Sin área</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}" @selected((string) old('area_gestion_id', $registro?->area_gestion_id) === (string) $area->id)>{{ $area->nombre }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label>Tipo de establecimiento</label>
        <select class="form-control" name="tipo_establecimiento_id">
            <option value="">Sin tipo</option>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}" @selected((string) old('tipo_establecimiento_id', $registro?->tipo_establecimiento_id) === (string) $tipo->id)>{{ $tipo->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Nivel de atención</label>
        <input class="form-control" name="nivel_atencion" value="{{ old('nivel_atencion', $registro?->nivel_atencion) }}" list="{{ $formId }}-niveles" maxlength="50">
        <datalist id="{{ $formId }}-niveles">@foreach($niveles as $nivel)<option value="{{ $nivel }}">@endforeach</datalist>
    </div>
    <div class="form-group col-md-3">
        <label>Grado de complejidad</label>
        <select class="form-control" name="grado_complejidad_id">
            <option value="">Sin grado</option>
            @foreach($grados as $grado)
                <option value="{{ $grado->id }}" @selected((string) old('grado_complejidad_id', $registro?->grado_complejidad_id) === (string) $grado->id)>
                    Complejidad {{ $grado->codigo }} — {{ $grado->descripcion }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label>Prestador</label>
        <input class="form-control" name="prestador" value="{{ old('prestador', $registro?->prestador) }}" list="{{ $formId }}-prestadores" maxlength="80">
        <datalist id="{{ $formId }}-prestadores">@foreach($prestadores as $prestador)<option value="{{ $prestador }}">@endforeach</datalist>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="{{ $formId }}-incluye-tercerizado" name="incluye_tercerizado" value="1"
                @checked((string) old('incluye_tercerizado', $registro?->incluye_tercerizado ? '1' : '0') === '1')>
            <label class="custom-control-label" for="{{ $formId }}-incluye-tercerizado">
                Servicio Tercerizado
            </label>
        </div>
        <small class="form-text text-muted">
            Solo aplica si el prestador es IPS: en captura mostrará columnas Servicio Tercerizado + Total (distinto del prestador Tercerizado).
        </small>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label>Latitud</label>
        <input class="form-control" type="number" step="0.0000001" min="-90" max="90" name="latitud" value="{{ old('latitud', $registro?->latitud) }}">
    </div>
    <div class="form-group col-md-3">
        <label>Longitud</label>
        <input class="form-control" type="number" step="0.0000001" min="-180" max="180" name="longitud" value="{{ old('longitud', $registro?->longitud) }}">
    </div>
    <div class="form-group col-md-6">
        <label>Situación inmueble</label>
        <input class="form-control" name="situacion_inmueble" value="{{ old('situacion_inmueble', $registro?->situacion_inmueble) }}" list="{{ $formId }}-situaciones" maxlength="120">
        <datalist id="{{ $formId }}-situaciones">@foreach($situaciones as $situacion)<option value="{{ $situacion }}">@endforeach</datalist>
    </div>
</div>

<div class="form-group">
    <label>Observación</label>
    <textarea class="form-control" name="observacion" rows="3" maxlength="2000">{{ old('observacion', $registro?->observacion) }}</textarea>
</div>

<button class="btn btn-success">{{ $submitLabel }}</button>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bio-departamento').forEach(function (departmentSelect) {
        const districtSelect = document.getElementById(departmentSelect.dataset.target);
        const selected = districtSelect.dataset.selected || '';

        function loadDistricts() {
            const departmentId = departmentSelect.value;
            districtSelect.innerHTML = '<option value="">Cargando...</option>';
            if (!departmentId) {
                districtSelect.innerHTML = '<option value="">Seleccione departamento/región</option>';
                return;
            }

            fetch('{{ route('bioestadistica.geografia.distritos') }}?departamento_id=' + encodeURIComponent(departmentId), {
                headers: { 'Accept': 'application/json' }
            })
                .then(response => response.json())
                .then(payload => {
                    districtSelect.innerHTML = '<option value="">Seleccione</option>';
                    payload.data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.codigo + ' - ' + district.nombre;
                        option.selected = String(district.id) === String(selected);
                        districtSelect.appendChild(option);
                    });
                });
        }

        departmentSelect.addEventListener('change', function () {
            districtSelect.dataset.selected = '';
            loadDistricts();
        });
        loadDistricts();
    });
});
</script>
@endpush
@endonce
