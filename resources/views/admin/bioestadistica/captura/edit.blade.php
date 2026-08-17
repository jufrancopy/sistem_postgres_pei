@extends('layouts.master')
@section('title', "Captura {$record->formulario->codigo}")

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $record->formulario->codigo }} — {{ $record->formulario->nombre }}</h4>
        <p class="card-category">
            {{ $record->establecimiento->nombre }} ·
            {{ \Carbon\Carbon::create($record->periodo_anio, $record->periodo_mes, 1)->translatedFormat('F Y') }} ·
            <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">{{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}</span>
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if($record->estado === 'objetado')<div class="alert alert-warning"><strong>Observación de la objeción:</strong> {{ $record->observacion }}</div>@endif

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
