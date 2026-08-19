@extends('layouts.master')
@section('title', "Captura {$record->formulario->codigo}")

@section('content')
@php $canEditPeriod = auth()->user()->can('update', $record); @endphp
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $record->formulario->codigo }} — {{ $record->formulario->nombre }}</h4>
        <p class="card-category">
            {{ $record->establecimiento->nombre }}
            @if($record->corteLabel()) · {{ $record->corteLabel() }} @endif
            ·
            {{ \Carbon\Carbon::create($record->periodo_anio, $record->periodo_mes, 1)->translatedFormat('F Y') }} ·
            <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">{{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}</span>
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if($record->estado === 'objetado')<div class="alert alert-warning"><strong>Observación de la objeción:</strong> {{ $record->observacion }}</div>@endif

        @include('admin.bioestadistica.captura._sp-navigator')

        @if($record->isEditable() && ($unidades ?? collect())->isNotEmpty() && ! $record->estructura_servicio_id)
            <div class="alert alert-warning">
                Este establecimiento tiene departamento y servicio asociados. Selecciónelos abajo y pulse <strong>Aplicar</strong> para que esta carga quede cortada por ese servicio.
            </div>
        @endif

        <div class="card border mb-3">
            <div class="card-header bg-light"><strong>Período y servicio</strong></div>
            <div class="card-body py-2">
                <form method="POST" action="{{ route('bioestadistica.captura.period.update', $record) }}">
                    @csrf @method('PUT')
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-2 mb-0">
                            <label>Año</label>
                            <input class="form-control" type="number" name="periodo_anio" min="1990" max="2100"
                                value="{{ old('periodo_anio', $record->periodo_anio) }}" required
                                @disabled(!$canEditPeriod)>
                        </div>
                        <div class="form-group col-md-3 mb-0">
                            <label>Mes</label>
                            <select class="form-control" name="periodo_mes" required @disabled(!$canEditPeriod)>
                                @foreach($months as $number => $month)
                                    <option value="{{ $number }}" @selected((int) old('periodo_mes', $record->periodo_mes) === $number)>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(($unidades ?? collect())->isNotEmpty())
                            <div class="form-group col-md-4 mb-0">
                                <label>Departamento / servicio</label>
                                <select class="form-control" name="estructura_servicio_id" @disabled(!$canEditPeriod) @required($canEditPeriod)>
                                    <option value="">Seleccione</option>
                                    @foreach($unidades as $unidad)
                                        <option value="{{ $unidad->servicio_id }}" @selected((string) old('estructura_servicio_id', $record->estructura_servicio_id) === (string) $unidad->servicio_id)>
                                            {{ $unidad->etiqueta() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-2">
                            @if($canEditPeriod)
                                <button class="btn btn-info btn-sm mb-0">Aplicar</button>
                            @endif
                        </div>
                        <div class="col-md-12 mt-2">
                            <small class="text-muted">Al aplicarlo se recarga el formulario; esto ajusta correctamente calendarios como SP11.</small>
                            @if($record->formulario->codigo === 'SP11')
                                <br><small class="text-warning">Si el mes destino tiene menos días, primero quite los valores de los días que dejarían de existir.</small>
                            @endif
                            @if(($unidades ?? collect())->isEmpty())
                                <br><small class="text-muted">
                                    Este establecimiento no tiene departamento/servicio asociado.
                                    @can('bio.geo.create')
                                        <a href="{{ route('bioestadistica.estructura.index') }}">Asociarlo</a>
                                    @endcan
                                </small>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('bioestadistica.captura.update', $record) }}">
            @csrf @method('PUT')
            @foreach($record->formulario->secciones as $seccion)
                <div class="card border mb-3">
                    <div class="card-header bg-light"><strong>{{ $seccion->titulo }}</strong><small class="text-muted ml-2">{{ $seccion->descripcion }}</small></div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($seccion->fields as $field)
                                <div class="col-md-{{ in_array($field->type, ['textarea','tabla','subtabla','matriz']) ? '12' : '6' }}">
                                    @include('admin.bioestadistica.captura._field')
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="form-group"><label>Observación del digitador</label><textarea class="form-control" name="observacion" rows="2" @disabled(!$record->isEditable())>{{ old('observacion', $record->estado === 'objetado' ? '' : $record->observacion) }}</textarea></div>
            @if($record->isEditable() && auth()->user()->can('bio.record.update'))
                <button class="btn btn-primary">Guardar borrador</button>
            @endif
        </form>

        @if($record->isEditable() && auth()->user()->can('bio.record.submit'))
            <form method="POST" action="{{ route('bioestadistica.captura.submit', $record) }}" class="mt-2">
                @csrf
                <button class="btn btn-success" onclick="return confirm('Se validará el último borrador guardado. ¿Enviar para aprobación?')">Enviar para aprobación</button>
            </form>
        @endif

        @if($record->estado === 'enviado' && auth()->user()->can('bio.record.approve'))
            <div class="mt-3 border-top pt-3">
                <form method="POST" action="{{ route('bioestadistica.captura.approve', $record) }}" class="d-inline">@csrf<button class="btn btn-success">Aprobar</button></form>
                <form method="POST" action="{{ route('bioestadistica.captura.reject', $record) }}" class="d-inline ml-2">
                    @csrf
                    <input class="form-control d-inline-block" style="width:300px" name="observacion" placeholder="Motivo de objeción" required>
                    <button class="btn btn-danger">Objetar</button>
                </form>
            </div>
        @endif
        <a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bio-tabla[data-totals="1"]').forEach(function (table) {
        const recalculate = function () {
            table.querySelectorAll('[data-total]').forEach(function (cell) {
                const column = cell.getAttribute('data-total');
                let total = 0;
                table.querySelectorAll('.bio-tabla-input[data-column="' + column + '"]').forEach(function (input) {
                    total += parseFloat(input.value) || 0;
                });
                cell.textContent = total.toLocaleString('es-PY');
            });
        };
        table.addEventListener('input', recalculate);
        recalculate();
    });
    document.querySelectorAll('.bio-matriz').forEach(function (table) {
        const recalculate = function () {
            table.querySelectorAll('[data-row-total]').forEach(function (cell) {
                const row = cell.getAttribute('data-row-total');
                let total = 0;
                table.querySelectorAll('.bio-matriz-input[data-row="' + row + '"]').forEach(function (input) {
                    total += parseInt(input.value, 10) || 0;
                });
                cell.textContent = total.toLocaleString('es-PY');
            });
        };
        table.addEventListener('input', recalculate);
        recalculate();
    });
});
</script>
@endsection
