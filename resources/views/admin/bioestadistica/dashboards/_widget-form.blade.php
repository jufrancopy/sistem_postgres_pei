@php
    $widget = $widget ?? null;
    $config = old('query_config', $widget?->query_config ?? []);
    if (! is_array($config)) {
        $config = [];
    }
    $sourceValue = old('source');
    if ($sourceValue === null && ! empty($config['form']) && ! empty($config['field'])) {
        $sourceValue = $config['form'].':'.$config['field'].':'.($config['metric'] ?? '');
    }
    $isEditing = $widget !== null;
    $action = $isEditing
        ? route('bioestadistica.dashboards.widgets.update', [$dashboard, $widget])
        : route('bioestadistica.dashboards.widgets.store', $dashboard);
@endphp
<form method="POST" action="{{ $action }}" class="card card-body bg-light" id="bio-widget-form">
    @csrf
    @if($isEditing) @method('PUT') @endif
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">{{ $isEditing ? 'Editar widget' : 'Agregar widget' }}</h5>
        @if($isEditing)
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('bioestadistica.dashboards.show', $dashboard) }}">Cancelar edición</a>
        @endif
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Título</label>
            <input class="form-control" name="titulo" value="{{ old('titulo', $widget?->titulo) }}" required>
        </div>
        <div class="form-group col-md-2">
            <label>Tipo</label>
            <select class="form-control" name="tipo">
                @foreach($widgetTypes as $tipo)
                    <option value="{{ $tipo }}" @selected(old('tipo', $widget?->tipo) === $tipo)>{{ $tipo }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Fuente</label>
            <select class="form-control" name="source">
                <option value="">—</option>
                @foreach($sources as $source)
                    <option value="{{ $source['key'] }}" @selected((string) $sourceValue === (string) $source['key'])>{{ $source['label'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>Indicador</label>
            <select class="form-control" name="indicator">
                <option value="">—</option>
                @foreach($indicators as $indicator)
                    <option value="{{ $indicator->codigo }}" @selected(old('indicator', $config['indicator'] ?? '') === $indicator->codigo)>{{ $indicator->codigo }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>Reporte</label>
            <select class="form-control" name="reporte_id">
                <option value="">—</option>
                @foreach($reportes as $reporte)
                    <option value="{{ $reporte->id }}" @selected((string) old('reporte_id', $config['reporte_id'] ?? '') === (string) $reporte->id)>{{ $reporte->codigo }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <label>Dimensión</label>
            <select class="form-control" name="dimension">
                <option value="">—</option>
                @foreach($dimensions as $dimension)
                    <option value="{{ $dimension }}" @selected(old('dimension', $config['dimension'] ?? '') === $dimension)>{{ $dimension }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>Heatmap X</label>
            <select class="form-control" name="dimension_x">
                <option value="">—</option>
                @foreach($dimensions as $dimension)
                    <option value="{{ $dimension }}" @selected(old('dimension_x', $config['dimension_x'] ?? '') === $dimension)>{{ $dimension }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>Heatmap Y</label>
            <select class="form-control" name="dimension_y">
                <option value="">—</option>
                @foreach($dimensions as $dimension)
                    <option value="{{ $dimension }}" @selected(old('dimension_y', $config['dimension_y'] ?? '') === $dimension)>{{ $dimension }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-1">
            <label>Ancho</label>
            <input class="form-control" type="number" name="ancho" min="1" max="12" value="{{ old('ancho', $widget?->ancho ?? 4) }}">
        </div>
        <div class="form-group col-md-1">
            <label>Alto</label>
            <input class="form-control" type="number" name="alto" min="1" max="12" value="{{ old('alto', $widget?->alto ?? 3) }}">
        </div>
        <div class="form-group col-md-2">
            <label>Agg</label>
            <select class="form-control" name="agg">
                @foreach(['sum','avg','count','max','min'] as $agg)
                    <option value="{{ $agg }}" @selected(old('agg', $config['agg'] ?? 'sum') === $agg)>{{ $agg }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2 d-flex align-items-end">
            <button class="btn btn-success btn-block">{{ $isEditing ? 'Guardar cambios' : 'Agregar' }}</button>
        </div>
    </div>
</form>
