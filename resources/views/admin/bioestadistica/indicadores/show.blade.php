@extends('layouts.master')
@section('title', "Indicador {$indicador->codigo}")

@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $indicador->codigo }} — {{ $indicador->nombre }}</h4>
        <p class="card-category">{{ $indicador->descripcion }}</p>
    </div>
    <div class="card-body">
        @can('bio.indicator.manage')
            <form method="POST" action="{{ route('bioestadistica.indicadores.update', $indicador) }}">
                @csrf @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-3"><label>Código</label><input class="form-control" name="codigo" value="{{ old('codigo', $indicador->codigo) }}" required></div>
                    <div class="form-group col-md-4"><label>Nombre</label><input class="form-control" name="nombre" value="{{ old('nombre', $indicador->nombre) }}" required></div>
                    <div class="form-group col-md-2"><label>Unidad</label><input class="form-control" name="unidad" value="{{ old('unidad', $indicador->unidad) }}"></div>
                    <div class="form-group col-md-2"><label>Ámbito</label><select class="form-control" name="ambito">@foreach(['establecimiento','distrito','departamento','microred','pais'] as $value)<option value="{{ $value }}" @selected($indicador->ambito === $value)>{{ ucfirst($value) }}</option>@endforeach</select></div>
                    <div class="form-group col-md-1"><label>Dec.</label><input class="form-control" name="decimales" type="number" min="0" max="4" value="{{ $indicador->decimales }}"></div>
                </div>
                <textarea class="form-control" name="descripcion">{{ old('descripcion', $indicador->descripcion) }}</textarea>
                <label class="mt-2"><input type="checkbox" name="activo" value="1" @checked($indicador->activo)> Activo</label>
                <div><button class="btn btn-primary btn-sm">Guardar metadatos</button></div>
            </form>
        @endcan
    </div>
</div>

<div class="row">
    @can('bio.indicator.manage')
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-warning"><h4 class="card-title">Constructor visual</h4></div>
            <div class="card-body">
                <form method="POST" action="{{ route('bioestadistica.indicadores.formulas.store', $indicador) }}">
                    @csrf
                    <input type="hidden" name="formula_mode" value="simple">
                    <div class="form-group"><label>Operación</label><select class="form-control" name="operator">@foreach(['sum'=>'Suma','avg'=>'Promedio','count'=>'Conteo','count_distinct'=>'Valores distintos','max'=>'Máximo','min'=>'Mínimo'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></div>
                    <div class="form-group"><label>Fuente numérica</label><select class="form-control" name="source" required>@foreach($sources as $source)<option value="{{ $source['key'] }}">{{ $source['label'] }}</option>@endforeach</select></div>
                    <div class="form-row">
                        <div class="form-group col-md-6"><label>Vigente desde</label><input class="form-control" type="date" name="vigente_desde"></div>
                        <div class="form-group col-md-6"><label>Vigente hasta</label><input class="form-control" type="date" name="vigente_hasta"></div>
                    </div>
                    <button class="btn btn-success">Guardar fórmula</button>
                </form>
                <hr>
                <button class="btn btn-outline-secondary btn-sm" data-toggle="collapse" data-target="#formulaAvanzada">Modo avanzado AST</button>
                <form method="POST" action="{{ route('bioestadistica.indicadores.formulas.store', $indicador) }}" class="collapse mt-3" id="formulaAvanzada">
                    @csrf
                    <input type="hidden" name="formula_mode" value="advanced">
                    <textarea class="form-control font-monospace" name="expresion" rows="10" placeholder='{"op":"pct","args":[...]}'>{{ old('expresion') }}</textarea>
                    <div class="form-row mt-2">
                        <div class="col-md-6"><input class="form-control" type="date" name="vigente_desde" placeholder="Desde"></div>
                        <div class="col-md-6"><input class="form-control" type="date" name="vigente_hasta" placeholder="Hasta"></div>
                    </div>
                    <button class="btn btn-success">Validar y guardar AST</button>
                </form>
            </div>
        </div>
    </div>
    @endcan

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-success"><h4 class="card-title">Evaluar indicador</h4></div>
            <div class="card-body">
                <form method="POST" action="{{ route('bioestadistica.indicadores.evaluate', $indicador) }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4"><label>Año del dato</label><input class="form-control" type="number" name="periodo_anio" value="{{ old('periodo_anio', $evaluationContext['periodo_anio'] ?? now()->year) }}" required></div>
                        <div class="form-group col-md-4"><label>Mes del dato</label><select class="form-control" name="periodo_mes">@foreach($months as $number=>$month)<option value="{{ $number }}" @selected(($evaluationContext['periodo_mes'] ?? now()->subMonth()->month) == $number)>{{ $month }}</option>@endforeach</select></div>
                    </div>
                    <div class="form-group"><label>Establecimiento</label><select class="form-control" name="establecimiento_id">@if(\App\Models\Bioestadistica\Record::userHasGlobalAccess(auth()->user()))<option value="">Todos (total general)</option>@else<option value="">Seleccione un establecimiento asignado</option>@endif @foreach($establecimientos as $item)<option value="{{ $item->id }}" @selected(($evaluationContext['establecimiento_id'] ?? null) == $item->id)>{{ $item->nombre }}</option>@endforeach</select></div>
                    <button class="btn btn-success">Evaluar</button>
                </form>
                @isset($evaluation)
                    <div class="alert alert-info mt-3">
                        <div class="small">{{ $evaluation['cache'] ? 'Resultado desde caché' : 'Resultado calculado' }}</div>
                        <h2 class="mb-0">{{ $evaluation['valor'] === null ? 'Sin datos' : number_format($evaluation['valor'], $indicador->decimales, ',', '.') }} {{ $indicador->unidad }}</h2>
                        <div class="mt-2">
                            Cobertura:
                            {{ $evaluation['cobertura']['informados'] ?? 0 }} /
                            {{ $evaluation['cobertura']['esperados'] ?? '—' }}
                            @if($evaluation['cobertura']['porcentaje'] !== null)
                                ({{ number_format($evaluation['cobertura']['porcentaje'], 2, ',', '.') }}%)
                            @endif
                        </div>
                    </div>
                    @if($description)
                        <div class="row">
                            <div class="col-6"><strong>Media:</strong> {{ isset($description['media']) ? number_format($description['media'], 2, ',', '.') : '—' }}</div>
                            <div class="col-6"><strong>Mediana:</strong> {{ isset($description['mediana']) ? number_format($description['mediana'], 2, ',', '.') : '—' }}</div>
                            <div class="col-6"><strong>Desv. estándar:</strong> {{ isset($description['desviacion_estandar']) ? number_format($description['desviacion_estandar'], 2, ',', '.') : '—' }}</div>
                            <div class="col-6"><strong>Frecuencia:</strong> {{ $description['frecuencia_absoluta'] ?? 0 }}</div>
                        </div>
                    @endif
                @endisset
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Historial de fórmulas</h4></div>
    <div class="card-body table-responsive">
        <table class="table table-sm">
            <thead><tr><th>ID</th><th>Vigencia</th><th>AST</th><th></th></tr></thead>
            <tbody>@forelse($indicador->formulas as $formula)
                <tr>
                    <td>{{ $formula->id }}</td>
                    <td>{{ $formula->vigente_desde?->format('d/m/Y') ?? 'Sin inicio' }} — {{ $formula->vigente_hasta?->format('d/m/Y') ?? 'Sin fin' }}</td>
                    <td><code>{{ json_encode($formula->expresion, JSON_UNESCAPED_UNICODE) }}</code></td>
                    <td>@can('bio.indicator.manage')<form method="POST" action="{{ route('bioestadistica.indicadores.formulas.destroy', [$indicador, $formula]) }}" onsubmit="return confirm('¿Eliminar esta fórmula?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Eliminar</button></form>@endcan</td>
                </tr>
            @empty<tr><td colspan="4" class="text-center text-muted">Sin fórmulas.</td></tr>@endforelse</tbody>
        </table>
    </div>
</div>
<a href="{{ route('bioestadistica.indicadores.index') }}" class="btn btn-secondary">Volver</a>
@endsection
