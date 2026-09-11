@extends('layouts.master')
@section('title', 'Ajustar mapeo — Importar planilla')

@php
    $hoja = $mapping['hoja'] ?? [];
    $grid = $mapping['grid'] ?? ['columns' => [], 'rows' => []];
    $defaults = $mapping['defaults'] ?? [];
    $selectedSp = old('formulario_codigo', $defaults['formulario_codigo'] ?? ($formularios->first()->codigo ?? 'SP1'));
    $roles = $rolesBySp[$selectedSp] ?? $mapping['roles'] ?? [];
    $filaEncabezado = old('fila_encabezado', $defaults['fila_encabezado'] ?? 12);
    $columnasOld = old('columnas', $defaults['columnas'] ?? []);
    $mes = (int) old('periodo_mes', $preview['periodo_mes'] ?? 0);
    $anio = (int) old('periodo_anio', $preview['periodo_anio'] ?? 0);
    $establecimientoId = (int) old('establecimiento_id', $preview['establecimiento_id'] ?? 0);
@endphp

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [
    ['label' => 'Carga de datos', 'url' => route('bioestadistica.captura.index')],
    ['label' => 'Importar', 'url' => route('bioestadistica.captura.import.index')],
    ['label' => 'Resumen', 'url' => route('bioestadistica.captura.import.summary')],
    ['label' => 'Ajustar mapeo'],
]])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">tune</i> Ajustar mapeo</h4>
        <p class="card-category">
            Hoja <strong>{{ $sheetTitle }}</strong>
            @if(!empty($hoja['sp_codigo'])) · Detectado: {{ $hoja['sp_codigo'] }} @endif
            — indique SP, fila de encabezado y columnas si la detección automática falló.
        </p>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        @if(!empty($mapping['calidad']['motivos']))
            <div class="alert alert-warning">
                <strong>Calidad {{ $mapping['calidad']['nivel'] ?? '' }} ({{ $mapping['calidad']['score'] ?? 0 }})</strong>
                <ul class="mb-0 mt-1">
                    @foreach($mapping['calidad']['motivos'] as $motivo)
                        <li>{{ $motivo }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bioestadistica.captura.import.map.apply') }}" id="bio-import-map-form">
            @csrf
            <input type="hidden" name="hoja" value="{{ $sheetTitle }}">
            <input type="hidden" name="hoja_idx" value="{{ $mapping['sheet_index'] ?? '' }}">

            <h5>1. Identidad</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Formulario (SP) *</label>
                    <select class="form-control bio-select2" name="formulario_codigo" id="bio-map-sp" required>
                        @foreach($formularios as $formulario)
                            <option value="{{ $formulario->codigo }}" @selected($selectedSp === $formulario->codigo)>
                                {{ $formulario->codigo }} — {{ $formulario->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Fila de encabezado *</label>
                    <input class="form-control" type="number" name="fila_encabezado" id="bio-map-fila" min="1" max="200" value="{{ $filaEncabezado }}" required>
                    <small class="text-muted">Fila con COD / etiquetas / totales (los datos empiezan en la siguiente).</small>
                </div>
            </div>

            <h5>2. Contexto (opcional)</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Establecimiento</label>
                    <select class="form-control bio-select2" name="establecimiento_id" data-placeholder="Usar el del resumen" data-allow-clear="1">
                        <option value="">Usar el del resumen</option>
                        @foreach($establecimientos as $item)
                            <option value="{{ $item->id }}" @selected($establecimientoId === (int) $item->id)>
                                {{ $item->nombre }} ({{ $item->codigo }}@if($item->codigo_sih) · SIH {{ $item->codigo_sih }}@endif)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Año</label>
                    <input class="form-control" type="number" name="periodo_anio" min="1990" max="2100" value="{{ $anio ?: '' }}" placeholder="Resumen">
                </div>
                <div class="form-group col-md-3">
                    <label>Mes</label>
                    <select class="form-control bio-select2" name="periodo_mes" data-placeholder="Resumen" data-allow-clear="1">
                        <option value="">Resumen</option>
                        @foreach($months as $num => $label)
                            <option value="{{ $num }}" @selected($mes === (int) $num)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h5>3. Columnas</h5>
            <p class="text-muted small">
                Las columnas dependen del SP elegido. Para SP1: una columna <strong>Total consultas</strong>
                (TOTALES → bloque especialidades) <em>o</em> columnas <strong>IPS</strong> + <strong>Convenio</strong>
                (consulta + convenio). Para SP6 use <strong>Prestación / etiqueta</strong> y <strong>Total</strong>
                (no «Total consultas»). Deje «Autodetectar» si solo corrige la fila de encabezado.
            </p>
            <div class="form-row" id="bio-map-roles">
                @foreach($roles as $role)
                    <div class="form-group col-md-3 bio-map-role" data-role="{{ $role['key'] }}">
                        <label>{{ $role['label'] }} @if($role['required'])*@endif</label>
                        <select class="form-control" name="columnas[{{ $role['key'] }}]" @if($role['required']) data-required="1" @endif>
                            <option value="">Autodetectar</option>
                            @foreach($grid['columns'] as $colIndex => $letter)
                                <option value="{{ $letter }}" @selected(($columnasOld[$role['key']] ?? '') === $letter || ($role['key'] === 'total' && ($columnasOld['total_consultas'] ?? '') === $letter))>
                                    {{ $letter }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>

            <h5>4. Vista previa del Excel</h5>
            <div class="table-responsive border rounded mb-3" style="max-height: 360px; overflow: auto;">
                <table class="table table-sm table-bordered mb-0" id="bio-map-grid">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:3rem">#</th>
                            @foreach($grid['columns'] as $letter)
                                <th class="text-center">{{ $letter }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($grid['rows'] as $row)
                        <tr data-row="{{ $row['row'] }}" class="{{ (int) $filaEncabezado === (int) $row['row'] ? 'table-warning' : '' }}">
                            <td class="text-muted small">{{ $row['row'] }}</td>
                            @foreach($row['cells'] as $cell)
                                <td class="small text-nowrap">{{ \Illuminate\Support\Str::limit($cell, 28) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success">Aplicar y reanalizar</button>
            <a href="{{ route('bioestadistica.captura.import.summary') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var rolesBySp = @json($rolesBySp);
    var columns = @json(array_values($grid['columns'] ?? []));
    var spSelect = document.getElementById('bio-map-sp');
    var rolesBox = document.getElementById('bio-map-roles');
    var filaInput = document.getElementById('bio-map-fila');
    var grid = document.getElementById('bio-map-grid');

    function optionHtml(selected) {
        var html = '<option value="">Autodetectar</option>';
        columns.forEach(function (letter) {
            html += '<option value="' + letter + '"' + (selected === letter ? ' selected' : '') + '>' + letter + '</option>';
        });
        return html;
    }

    function renderRoles(sp, preserve) {
        var roles = rolesBySp[sp] || rolesBySp.SP1 || [];
        preserve = preserve || {};
        // Si el usuario mapeó "total_consultas" (roles SP1) y ahora es SP6, reutilizar en "total".
        if (!preserve.total && preserve.total_consultas) {
            preserve.total = preserve.total_consultas;
        }
        rolesBox.innerHTML = '';
        roles.forEach(function (role) {
            var col = document.createElement('div');
            col.className = 'form-group col-md-3 bio-map-role';
            col.innerHTML = '<label>' + role.label + (role.required ? ' *' : '') + '</label>'
                + '<select class="form-control" name="columnas[' + role.key + ']"'
                + (role.required ? ' data-required="1"' : '') + '>'
                + optionHtml(preserve[role.key] || '') + '</select>';
            rolesBox.appendChild(col);
        });
    }

    function currentColumnMap() {
        var map = {};
        rolesBox.querySelectorAll('select[name^="columnas["]').forEach(function (el) {
            var match = el.name.match(/^columnas\[([^\]]+)\]$/);
            if (match && el.value) {
                map[match[1]] = el.value;
            }
        });
        return map;
    }

    function highlightHeaderRow() {
        if (!grid) return;
        var n = parseInt(filaInput.value, 10);
        grid.querySelectorAll('tbody tr').forEach(function (tr) {
            tr.classList.toggle('table-warning', parseInt(tr.getAttribute('data-row'), 10) === n);
        });
    }

    function bindSpChange() {
        if (!spSelect) return;
        var handler = function () {
            renderRoles(spSelect.value, currentColumnMap());
        };
        spSelect.addEventListener('change', handler);
        if (window.jQuery) {
            window.jQuery(spSelect).on('change.select2', handler);
        }
    }

    bindSpChange();
    if (filaInput) {
        filaInput.addEventListener('input', highlightHeaderRow);
        highlightHeaderRow();
    }
});
</script>
@endsection
