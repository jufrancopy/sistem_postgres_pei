@extends('layouts.master')
@section('title', "Importación {$importacion->id}")

@php
    $analysis = $importacion->analisis ?? [];
    $firstSheet = $analysis['hojas'][0] ?? [];
    $defaultMapping = $importacion->mapeo ?? [
        'sheet' => $firstSheet['nombre'] ?? null,
        'header_row' => $firstSheet['fila_cabecera'] ?? 1,
        'form_code' => old('form_code', 'IMP_'.$importacion->id),
        'form_name' => old('form_name', pathinfo($importacion->archivo_original, PATHINFO_FILENAME)),
        'periodicidad' => 'ad_hoc',
        'establecimiento_column' => null,
        'columns' => collect($firstSheet['columnas'] ?? [])->map(fn ($column) => [
            'source' => $column['cabecera'] ?? null,
            'code' => $column['codigo'] ?? null,
            'label' => $column['cabecera'] ?? null,
            'type' => $column['tipo'] ?? 'text',
            'values' => $column['opciones'] ?? [],
        ])->values()->all(),
    ];
@endphp

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Importación #{{ $importacion->id }} — {{ $importacion->archivo_original }}</h4>
        <p class="card-category">
            Tipo detectado: <strong>{{ str_replace('_', ' ', $importacion->tipo) }}</strong>
            · Estado: <strong>{{ $importacion->estado }}</strong>
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($importacion->error)<div class="alert alert-danger">{{ $importacion->error }}</div>@endif

        <h5>Análisis</h5>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead><tr><th>Hoja</th><th>Filas</th><th>Columnas</th><th>Encabezado</th></tr></thead>
                <tbody>
                @foreach($analysis['hojas'] ?? [] as $sheet)
                    <tr>
                        <td>{{ $sheet['nombre'] }}</td>
                        <td>{{ $sheet['filas'] }}</td>
                        <td>{{ $sheet['cantidad_columnas'] ?? $sheet['columnas_count'] ?? '—' }}</td>
                        <td>Fila {{ $sheet['fila_cabecera'] ?? $sheet['encabezado_fila'] ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if(!empty($analysis['advertencias']))
            <div class="alert alert-warning"><ul class="mb-0">@foreach($analysis['advertencias'] as $warning)<li>{{ $warning }}</li>@endforeach</ul></div>
        @endif

        @can('bio.import.execute')
            @if(in_array($importacion->tipo, ['variables_salud', 'establecimientos_dim'], true))
                <form method="POST" action="{{ route('bioestadistica.importaciones.confirm', $importacion) }}" class="mt-3">
                    @csrf
                    <p>Este archivo coincide con un importador dedicado. La operación usa upserts y puede reejecutarse sin duplicar entidades.</p>
                    <button class="btn btn-success" @disabled($importacion->estado === 'completado')>Confirmar importación dedicada</button>
                </form>
            @elseif($importacion->tipo === 'formularios_sp')
                <hr>
                <h5>Matching asistido de prestaciones</h5>
                <p class="text-muted">Niveles: 1 exacto, 2 dominio, 3 similitud ≥ 0,75, 4 sin coincidencia. SP10 se importa solo como metadata nominativa (la carga de pacientes queda para F6).</p>
                @if(!empty($spPreview['advertencias']))
                    <div class="alert alert-warning"><ul class="mb-0">@foreach($spPreview['advertencias'] as $warning)<li>{{ $warning }}</li>@endforeach</ul></div>
                @endif
                <form method="POST" action="{{ route('bioestadistica.importaciones.map', $importacion) }}">
                    @csrf @method('PUT')
                    @foreach($spPreview['formularios'] ?? [] as $form)
                        <h6 class="mt-3">{{ $form['codigo'] }} — {{ $form['nombre'] }} <small class="text-muted">({{ $form['hoja'] }})</small></h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead><tr><th>Prestación</th><th>Nivel</th><th>Sugerencia</th><th>Decisión</th></tr></thead>
                                <tbody>
                                @forelse($form['prestaciones'] ?? [] as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        <td>{{ $row['nivel'] }}</td>
                                        <td>{{ $row['sugerencia'] ?? '—' }} @if(!empty($row['score']))<small>({{ $row['score'] }})</small>@endif</td>
                                        <td>
                                            @if($row['catalog_item_id'] && ($row['accion'] ?? '') !== 'pending')
                                                Enlazada
                                                <input type="hidden" name="decisiones[{{ $row['label'] }}]" value="{{ $row['catalog_item_id'] }}">
                                            @else
                                                <select class="form-control form-control-sm" name="decisiones[{{ $row['label'] }}]">
                                                    <option value="">Pendiente</option>
                                                    <option value="create" @selected(($row['accion'] ?? '') === 'create')>Crear prestación</option>
                                                    <option value="discard" @selected(($row['accion'] ?? '') === 'discard')>Descartar fila</option>
                                                </select>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted">Sin filas de prestación detectadas en esta hoja.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                    <button class="btn btn-outline-info">Guardar decisiones</button>
                </form>
                <form method="POST" action="{{ route('bioestadistica.importaciones.confirm', $importacion) }}" class="mt-3">
                    @csrf
                    <button class="btn btn-success" @disabled($importacion->estado === 'completado')>Confirmar importación de formularios SP</button>
                </form>
            @else
                <hr>
                <h5>Mapeo confirmable</h5>
                <p class="text-muted">Revise el JSON. Incluya <code>establecimiento_column</code> si va a cargar datos. Las columnas <code>select</code> pueden incluir <code>values</code> para crear un catálogo.</p>
                <form method="POST" action="{{ route('bioestadistica.importaciones.map', $importacion) }}">
                    @csrf @method('PUT')
                    <textarea class="form-control font-monospace" name="mapeo" rows="18" required>{{ json_encode($defaultMapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                    <button class="btn btn-outline-info mt-2">Guardar mapeo</button>
                </form>
                @if($importacion->mapeo)
                    <form method="POST" action="{{ route('bioestadistica.importaciones.confirm', $importacion) }}" class="mt-3">
                        @csrf
                        <button class="btn btn-success" @disabled($importacion->estado === 'completado')>Confirmar y crear formulario</button>
                    </form>
                @endif
            @endif
        @endcan

        @if(in_array($importacion->estado, ['confirmado', 'completado'], true))
            <hr>
            <h5>Resumen de corrida</h5>
            <pre class="bg-light p-3">{{ json_encode($importacion->resumen, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @if($importacion->tipo === 'generico' && $importacion->mapeo)
                <h5>Segunda pasada: carga de datos</h5>
                <p class="text-muted">El período estadístico es obligatorio. Nunca se toma de la fecha de importación ni del archivo. SP10 nominativo queda excluido.</p>
                <form method="POST" action="{{ route('bioestadistica.importaciones.data', $importacion) }}" class="form-row">
                    @csrf
                    <div class="form-group col-md-4">
                        <label>Formulario</label>
                        <select class="form-control" name="formulario_id" required>
                            @foreach($formularios as $formulario)
                                @continue($formulario->codigo === 'SP10' || $formulario->layout_type === 'nominativo')
                                <option value="{{ $formulario->id }}" @selected(($importacion->resumen['formulario_id'] ?? null) == $formulario->id)>{{ $formulario->codigo }} — {{ $formulario->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2"><label>Año del dato</label><input class="form-control" type="number" name="periodo_anio" value="{{ now()->year }}" required></div>
                    <div class="form-group col-md-2"><label>Mes del dato</label><input class="form-control" type="number" min="1" max="12" name="periodo_mes" required></div>
                    <div class="form-group col-md-2"><label class="d-block">Registro existente</label><label class="mt-2"><input type="checkbox" name="sobrescribir" value="1"> Sobrescribir borrador</label></div>
                    <div class="form-group col-md-2 d-flex align-items-end"><button class="btn btn-warning">Cargar datos</button></div>
                </form>
            @endif
        @endif
        <a class="btn btn-link mt-3" href="{{ route('bioestadistica.importaciones.index') }}">Volver</a>
        @can('bio.import.execute')
            <form method="POST" action="{{ route('bioestadistica.importaciones.destroy', $importacion) }}" class="d-inline mt-3"
                  onsubmit="return confirm('¿Eliminar esta importación y su archivo? No se revierten datos ya confirmados.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger mt-3">Eliminar importación</button>
            </form>
        @endcan
    </div>
</div>
@endsection
