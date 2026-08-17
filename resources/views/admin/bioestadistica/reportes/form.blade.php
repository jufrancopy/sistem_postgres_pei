@extends('layouts.master')
@section('title', $reporte->exists ? "Diseñar {$reporte->codigo}" : 'Nuevo reporte')

@php
    $def = old('definicion') ? json_decode(old('definicion'), true) : ($reporte->definicion ?? []);
    $sourceKey = old('source', ($def['form'] ?? null) ? implode(':', array_filter([$def['form'], $def['field'], $def['metric'] ?? null], fn ($v) => $v !== null && $v !== '')) : null);
    $selectedDimensions = old('dimensions', $def['dimensions'] ?? []);
    $filtros = $def['filtros'] ?? [];
@endphp

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $reporte->exists ? 'Diseñar reporte' : 'Nuevo reporte' }}</h4>
        <p class="card-category">Solo fuentes numéricas calificadas. No se acepta SQL ni columnas arbitrarias.</p>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $reporte->exists ? route('bioestadistica.reportes.update', $reporte) : route('bioestadistica.reportes.store') }}">
            @csrf
            @if($reporte->exists) @method('PUT') @endif
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Código</label>
                    <input class="form-control" name="codigo" value="{{ old('codigo', $reporte->codigo) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Nombre</label>
                    <input class="form-control" name="nombre" value="{{ old('nombre', $reporte->nombre) }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label class="d-block">Visible</label>
                    <label class="mt-2"><input type="checkbox" name="publico" value="1" @checked(old('publico', $reporte->publico ?? true))> Público</label>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" name="descripcion">{{ old('descripcion', $reporte->descripcion) }}</textarea>
            </div>

            <h5 class="mt-3">Fuente numérica</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Campo / métrica</label>
                    <select class="form-control" name="source">
                        <option value="">— Usar indicador —</option>
                        @foreach($sources as $source)
                            <option value="{{ $source['key'] }}" @selected($sourceKey === $source['key'])>{{ $source['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Indicador (opcional)</label>
                    <select class="form-control" name="indicator">
                        <option value="">Ninguno</option>
                        @foreach($indicators as $indicator)
                            <option value="{{ $indicator->codigo }}" @selected(old('indicator', $def['indicator'] ?? '') === $indicator->codigo)>{{ $indicator->codigo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Agregación</label>
                    <select class="form-control" name="agg">
                        @foreach($aggregations as $agg)
                            <option value="{{ $agg }}" @selected(old('agg', $def['agg'] ?? 'sum') === $agg)>{{ strtoupper($agg) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h5>Dimensiones</h5>
            <div class="form-row mb-3">
                @foreach($dimensions as $dimension)
                    <div class="col-md-3">
                        <label>
                            <input type="checkbox" name="dimensions[]" value="{{ $dimension }}" @checked(in_array($dimension, $selectedDimensions, true))>
                            {{ str_replace('_', ' ', $dimension) }}
                        </label>
                    </div>
                @endforeach
            </div>

            <h5>Filtros y orden</h5>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label>Desde año</label>
                    <input class="form-control" type="number" name="periodo_desde_anio" value="{{ old('periodo_desde_anio', $filtros['periodo_desde']['anio'] ?? '') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>Desde mes</label>
                    <select class="form-control" name="periodo_desde_mes">
                        <option value="">—</option>
                        @foreach($months as $number => $label)
                            <option value="{{ $number }}" @selected((int) old('periodo_desde_mes', $filtros['periodo_desde']['mes'] ?? 0) === $number)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Hasta año</label>
                    <input class="form-control" type="number" name="periodo_hasta_anio" value="{{ old('periodo_hasta_anio', $filtros['periodo_hasta']['anio'] ?? '') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>Hasta mes</label>
                    <select class="form-control" name="periodo_hasta_mes">
                        <option value="">—</option>
                        @foreach($months as $number => $label)
                            <option value="{{ $number }}" @selected((int) old('periodo_hasta_mes', $filtros['periodo_hasta']['mes'] ?? 0) === $number)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Estado</label>
                    <select class="form-control" name="estado_record">
                        @foreach(['aprobado','enviado','borrador','objetado'] as $estado)
                            <option value="{{ $estado }}" @selected(old('estado_record', $filtros['estado_record'] ?? 'aprobado') === $estado)>{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Límite</label>
                    <input class="form-control" type="number" name="limit" min="1" max="5000" value="{{ old('limit', $def['limit'] ?? 500) }}" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Ordenar por</label>
                    <select class="form-control" name="order_ref">
                        <option value="valor" @selected(old('order_ref', $def['order_by'][0]['ref'] ?? 'valor') === 'valor')>Valor</option>
                        @foreach($dimensions as $dimension)
                            <option value="{{ $dimension }}" @selected(old('order_ref', $def['order_by'][0]['ref'] ?? '') === $dimension)>{{ $dimension }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Dirección</label>
                    <select class="form-control" name="order_dir">
                        <option value="asc" @selected(old('order_dir', $def['order_by'][0]['dir'] ?? 'asc') === 'asc')>Asc</option>
                        <option value="desc" @selected(old('order_dir', $def['order_by'][0]['dir'] ?? '') === 'desc')>Desc</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label class="d-block">Totales</label>
                    <label class="mt-2"><input type="checkbox" name="totales" value="1" @checked(old('totales', $def['totales'] ?? true))> Incluir fila de totales</label>
                </div>
            </div>

            <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#defAvanzada">Modo avanzado JSON</button>
            <div class="collapse mt-3" id="defAvanzada">
                <input type="hidden" name="definition_mode" id="definitionMode" value="visual">
                <textarea class="form-control font-monospace" name="definicion" rows="12">{{ old('definicion', json_encode($reporte->definicion ?? new stdClass(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                <small class="text-muted">Si completa este JSON, marque “Usar JSON” antes de guardar.</small>
                <label class="d-block mt-2"><input type="checkbox" id="useJson"> Usar JSON avanzado</label>
            </div>

            <div class="mt-3">
                <button class="btn btn-success">Guardar definición</button>
                <a class="btn btn-link" href="{{ route('bioestadistica.reportes.index') }}">Cancelar</a>
                @if($reporte->exists)
                    <button class="btn btn-danger float-right" form="deleteReporte">Archivar</button>
                @endif
            </div>
        </form>
        @if($reporte->exists)
            <form id="deleteReporte" method="POST" action="{{ route('bioestadistica.reportes.destroy', $reporte) }}" onsubmit="return confirm('¿Archivar este reporte?')">
                @csrf @method('DELETE')
            </form>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('useJson')?.addEventListener('change', function () {
    document.getElementById('definitionMode').value = this.checked ? 'advanced' : 'visual';
});
</script>
@endsection
