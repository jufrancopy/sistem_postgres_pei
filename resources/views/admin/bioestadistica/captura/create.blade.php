@extends('layouts.master')
@section('title', 'Nueva carga — Bioestadística')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">add_chart</i> Iniciar carga estadística</h4>
        <p class="card-category">Elija el establecimiento, el período y, si corresponde, el departamento y servicio donde se informa la variable.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('warning'))<div class="alert alert-warning">{{ session('warning') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if($establecimientos->isEmpty())
            <div class="alert alert-warning">No tiene establecimientos asignados. Solicite al Analista de Bioestadística que realice la asignación.</div>
        @else
            <form method="POST" action="{{ route('bioestadistica.captura.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Establecimiento *</label>
                        <select class="form-control" name="establecimiento_id" id="captura-establecimiento" required>
                            <option value="">Seleccione</option>
                            @foreach($establecimientos as $establecimiento)
                                <option value="{{ $establecimiento->id }}" @selected((string) $selectedEstablecimientoId === (string) $establecimiento->id)>
                                    {{ $establecimiento->distrito?->departamento?->nombre }} / {{ $establecimiento->distrito?->nombre }} — {{ $establecimiento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Formulario (SP) *</label>
                        <select class="form-control" name="formulario_id" required>
                            <option value="">Seleccione</option>
                            @foreach($formularios as $formulario)
                                <option value="{{ $formulario->id }}" @selected((string) $selectedFormularioId === (string) $formulario->id)>
                                    {{ $formulario->codigo }} — {{ $formulario->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6" id="captura-corte-group" style="display:none">
                        <label>Departamento / servicio *</label>
                        <select class="form-control" name="estructura_servicio_id" id="captura-corte">
                            <option value="">Seleccione establecimiento</option>
                        </select>
                        <small class="text-muted">Obligatorio cuando el establecimiento tiene más de un departamento o servicio.</small>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Año del período *</label>
                        <input class="form-control" name="periodo_anio" type="number" min="1990" max="2100" value="{{ $selectedAnio }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Mes del período *</label>
                        <select class="form-control" name="periodo_mes" required>
                            <option value="">Seleccione</option>
                            @foreach($months as $number => $month)
                                <option value="{{ $number }}" @selected((int) $selectedMes === (int) $number)>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button class="btn btn-success">Crear borrador</button>
                <a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const establishmentSelect = document.getElementById('captura-establecimiento');
    const corteGroup = document.getElementById('captura-corte-group');
    const corteSelect = document.getElementById('captura-corte');
    const selected = '{{ old('estructura_servicio_id') }}';
    if (!establishmentSelect || !corteSelect) {
        return;
    }

    function loadCortes() {
        const establishmentId = establishmentSelect.value;
        corteSelect.innerHTML = '<option value="">Cargando...</option>';
        corteSelect.required = false;
        if (!establishmentId) {
            corteGroup.style.display = 'none';
            corteSelect.innerHTML = '<option value="">Seleccione establecimiento</option>';
            return;
        }
        fetch('{{ route('bioestadistica.estructura.cortes') }}?establecimiento_id=' + encodeURIComponent(establishmentId), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) { return response.json(); })
            .then(function (payload) {
                const cortes = payload.data || [];
                if (cortes.length === 0) {
                    corteGroup.style.display = 'none';
                    corteSelect.innerHTML = '<option value="">Sin corte</option>';
                    corteSelect.required = false;
                    return;
                }
                corteGroup.style.display = '';
                corteSelect.innerHTML = cortes.length > 1 ? '<option value="">Seleccione</option>' : '';
                cortes.forEach(function (corte) {
                    const option = document.createElement('option');
                    option.value = corte.servicio_id;
                    option.textContent = corte.etiqueta;
                    option.selected = String(corte.servicio_id) === String(selected) || cortes.length === 1;
                    corteSelect.appendChild(option);
                });
                corteSelect.required = true;
            });
    }

    establishmentSelect.addEventListener('change', loadCortes);
    loadCortes();
});
</script>
@endpush
