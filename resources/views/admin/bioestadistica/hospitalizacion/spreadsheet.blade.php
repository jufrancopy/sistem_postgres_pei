@extends('layouts.master')
@section('title', 'Planilla de hospitalización SP10')

@php
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
        <h4 class="card-title"><i class="material-icons">grid_on</i> Planilla de carga SP10</h4>
        <p class="card-category">Edite varias filas y guarde una sola vez. El consolidado se recalcula al finalizar.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>No se guardó la planilla.</strong>
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="GET" action="{{ route('bioestadistica.hospitalizacion.spreadsheet') }}" class="form-row align-items-end mb-4">
            <div class="form-group col-md-6 mb-0">
                <label>Establecimiento a cargar</label>
                <select class="form-control" name="establecimiento_id" required>
                    @foreach($establecimientos as $establecimiento)
                        <option value="{{ $establecimiento->id }}" @selected($establecimientoId == $establecimiento->id)>{{ $establecimiento->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 mb-0">
                <label>Año</label>
                <input class="form-control" type="number" name="periodo_anio" min="1990" max="2100" value="{{ $periodo_anio }}" required>
            </div>
            <div class="form-group col-md-2 mb-0">
                <label>Mes</label>
                <select class="form-control" name="periodo_mes" required>
                    @foreach($months as $number => $month)
                        <option value="{{ $number }}" @selected($periodo_mes == $number)>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-info btn-sm mb-0" type="submit">Cargar período</button>
            </div>
        </form>

        <form method="POST" action="{{ route('bioestadistica.hospitalizacion.spreadsheet.save') }}" id="spreadsheet-form">
            @csrf
            <div class="alert alert-light border py-2">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-6 mb-0">
                        <label>Guardar filas en establecimiento</label>
                        <select class="form-control form-control-sm" name="establecimiento_id" required>
                            @foreach($establecimientos as $establecimiento)
                                <option value="{{ $establecimiento->id }}" @selected(old('establecimiento_id', $establecimientoId) == $establecimiento->id)>{{ $establecimiento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 mb-0">
                        <label>Año del período</label>
                        <input class="form-control form-control-sm" type="number" name="periodo_anio" min="1990" max="2100" value="{{ old('periodo_anio', $periodo_anio) }}" required>
                    </div>
                    <div class="form-group col-md-2 mb-0">
                        <label>Mes del período</label>
                        <select class="form-control form-control-sm" name="periodo_mes" required>
                            @foreach($months as $number => $month)
                                <option value="{{ $number }}" @selected((int) old('periodo_mes', $periodo_mes) === $number)>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Cambiar estos datos mueve todas las filas existentes al nuevo período.</small>
                    </div>
                </div>
            </div>

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
                <button class="btn btn-outline-info" type="button" id="add-row">
                    <i class="material-icons">add</i> Agregar fila
                </button>
                <button class="btn btn-success" type="submit">
                    <i class="material-icons">save</i> Guardar planilla
                </button>
                <a class="btn btn-secondary" href="{{ route('bioestadistica.hospitalizacion.index', [
                    'establecimiento_id' => $establecimientoId,
                    'periodo_anio' => $periodo_anio,
                    'periodo_mes' => $periodo_mes,
                ]) }}">Volver</a>
            </div>
        </form>
    </div>
</div>

<template id="row-template">
    @include('admin.bioestadistica.hospitalizacion._spreadsheet-row', [
        'index' => '__INDEX__',
        'row' => [],
    ])
</template>

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
@endsection
