@extends('layouts.master')
@section('title', 'Planilla de hospitalización SP10')

@php
    $canEditPeriod = auth()->user()->can('update', $record);
    $canEditRows = $record->isEditable() && auth()->user()->can('bio.hosp.manage');
    $rows = old('rows');
    if ($rows === null) {
        $rows = $episodios->map(fn ($episode) => [
            'id' => $episode->id,
            'cedula' => auth()->user()->can('bio.hosp.view_pii') ? $episode->cedula : '',
            'cedula_visible' => $episode->cedula_visible,
            'sexo' => $episode->sexo,
            'edad' => $episode->edad,
            'seguro' => $episode->seguro,
            'fecha_ingreso' => optional($episode->fecha_ingreso)->format('Y-m-d'),
            'fecha_egreso' => optional($episode->fecha_egreso)->format('Y-m-d'),
            'servicio' => $episode->servicio,
            'tipo_alta' => $episode->tipo_alta,
            'cie10' => $episode->cie10,
            'diagnostico' => $episode->diagnostico,
            'cirugia' => $episode->cirugia,
            'tipo_cirugia' => $episode->tipo_cirugia,
            'cesarea' => $episode->cesarea,
            'recien_nacido' => $episode->recien_nacido,
        ])->all();
    }
    $targetRows = max(8, count($rows) + 3);
    for ($i = count($rows); $i < $targetRows; $i++) {
        $rows[] = [];
    }
@endphp

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">grid_on</i> {{ $record->formulario->codigo }} — {{ $record->formulario->nombre }}</h4>
        <p class="card-category">
            {{ $establecimiento->nombre }} ·
            {{ \Carbon\Carbon::create($periodo_anio, $periodo_mes, 1)->translatedFormat('F Y') }} ·
            <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">{{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}</span>
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>No se guardó la planilla.</strong>
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @if($record->estado === 'objetado')<div class="alert alert-warning"><strong>Observación de la objeción:</strong> {{ $record->observacion }}</div>@endif

        @include('admin.bioestadistica.captura._sp-navigator')

        <div class="card border mb-3">
            <div class="card-header bg-light"><strong>Período estadístico</strong></div>
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
                        <div class="col-md-2">
                            @if($canEditPeriod)
                                <button class="btn btn-info btn-sm mb-0">Aplicar período</button>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <small class="text-muted">Al aplicarlo se recarga la planilla del establecimiento y período seleccionados. Los episodios existentes se mueven con el período.</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('bioestadistica.hospitalizacion.spreadsheet.save') }}" id="spreadsheet-form">
            @csrf
            <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
            <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
            <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">

            <div class="table-responsive" style="max-height:65vh">
                <table class="table table-bordered table-sm text-nowrap" id="episodes-grid">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Cédula</th>
                            <th>Sexo</th>
                            <th>Edad</th>
                            <th>Seguro</th>
                            <th>Ingreso *</th>
                            <th>Egreso</th>
                            <th>Servicio</th>
                            <th>Tipo alta</th>
                            <th>CIE-10</th>
                            <th>Diagnóstico</th>
                            <th>Cirugía</th>
                            <th>Tipo cirugía</th>
                            <th>Cesárea</th>
                            <th>R. nacido</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $index => $row)
                        @include('admin.bioestadistica.hospitalizacion._spreadsheet-row', [
                            'index' => $index,
                            'row' => $row,
                        ])
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                @if($canEditRows)
                    <button class="btn btn-outline-info" type="button" id="add-row">
                        <i class="material-icons">add</i> Agregar fila
                    </button>
                    <button class="btn btn-success" type="submit">
                        <i class="material-icons">save</i> Guardar planilla
                    </button>
                @endif
                <a class="btn btn-secondary" href="{{ route('bioestadistica.captura.index') }}">Volver al listado</a>
            </div>
        </form>

        @if($record->isEditable() && auth()->user()->can('bio.record.submit'))
            <form method="POST" action="{{ route('bioestadistica.captura.submit', $record) }}" class="mt-2">
                @csrf
                <button class="btn btn-success" onclick="return confirm('Se enviará el consolidado SP10 de este establecimiento y período. ¿Enviar para aprobación?')">Enviar para aprobación</button>
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
    </div>
</div>

<template id="row-template">
    @include('admin.bioestadistica.hospitalizacion._spreadsheet-row', [
        'index' => '__INDEX__',
        'row' => [],
    ])
</template>

@if($canEditRows)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.querySelector('#episodes-grid tbody');
    const template = document.getElementById('row-template').innerHTML;
    const addButton = document.getElementById('add-row');
    let nextIndex = {{ count($rows) }};

    const renumber = function () {
        body.querySelectorAll('tr').forEach(function (row, position) {
            row.querySelector('[data-row-number]').textContent = position + 1;
        });
    };

    addButton.addEventListener('click', function () {
        if (body.querySelectorAll('tr').length >= 200) {
            alert('La planilla admite hasta 200 filas por guardado.');
            return;
        }
        body.insertAdjacentHTML('beforeend', template.replaceAll('__INDEX__', nextIndex++));
        renumber();
    });

    renumber();
});
</script>
@endif
@endsection
