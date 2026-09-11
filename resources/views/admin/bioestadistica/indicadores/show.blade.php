@extends('layouts.master')
@section('title', "Indicador {$indicador->codigo}")

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._configuraciones_tabs')
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
                <p class="text-muted small mb-3">
                    Puede <strong>actualizar la fórmula vigente</strong> (misma versión) o
                    <strong>guardar una nueva versión</strong> con otra fecha de inicio.
                    La que se usa al evaluar es la vigente del historial (abajo).
                </p>
                @if($currentFormula)
                    <div class="alert alert-light border small">
                        <strong>Fórmula vigente (id {{ $currentFormula->id }}):</strong>
                        <code class="d-block mt-1" style="white-space:pre-wrap">{{ json_encode($currentFormula->expresion, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</code>
                        @unless($simpleConstructor)
                            <span class="d-block mt-2 text-warning">
                                Esta fórmula es compuesta (p. ej. porcentaje u operadores con varios argumentos).
                                Use el <strong>modo avanzado AST</strong> para editarla; el constructor simple solo cubre suma/promedio/conteo de una fuente.
                            </span>
                        @endunless
                    </div>
                @else
                    <div class="alert alert-warning small">Todavía no hay fórmula. Defina una abajo y guarde.</div>
                @endif

                @if($simpleConstructor)
                <form method="POST" action="{{ route('bioestadistica.indicadores.formulas.store', $indicador) }}" class="mb-3">
                    @csrf
                    <input type="hidden" name="formula_mode" value="simple">
                    <div class="form-group">
                        <label>Operación</label>
                        <select class="form-control" name="operator">
                            @foreach(['sum'=>'Suma','avg'=>'Promedio','count'=>'Conteo','count_distinct'=>'Valores distintos','max'=>'Máximo','min'=>'Mínimo'] as $value=>$label)
                                <option value="{{ $value }}" @selected(old('operator', $simpleConstructor['operator'] ?? 'sum') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fuente numérica</label>
                        <select class="form-control" name="source" required>
                            @foreach($sources as $source)
                                <option value="{{ $source['key'] }}" @selected(old('source', $simpleConstructor['source'] ?? '') === $source['key'])>{{ $source['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Vigente desde</label>
                            <input class="form-control" type="date" name="vigente_desde" value="{{ old('vigente_desde') }}">
                            <small class="text-muted">Solo para <em>nueva versión</em>. Vacío = hoy.</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Vigente hasta</label>
                            <input class="form-control" type="date" name="vigente_hasta" value="{{ old('vigente_hasta') }}">
                        </div>
                    </div>
                    <div class="d-flex flex-wrap" style="gap:.5rem">
                        @if($currentFormula)
                            <button class="btn btn-info" type="submit"
                                    data-bio-formula-action="update"
                                    data-bio-formula-url="{{ route('bioestadistica.indicadores.formulas.update', [$indicador, $currentFormula]) }}">
                                Actualizar vigente (id {{ $currentFormula->id }})
                            </button>
                        @endif
                        <button class="btn btn-success" type="submit"
                                data-bio-formula-action="store"
                                data-bio-formula-url="{{ route('bioestadistica.indicadores.formulas.store', $indicador) }}">
                            Guardar nueva versión
                        </button>
                    </div>
                </form>
                <hr>
                @endif

                <button class="btn btn-outline-secondary btn-sm" data-toggle="collapse" data-target="#formulaAvanzada" @if($currentFormula && ! $simpleConstructor) aria-expanded="true" @endif>
                    Modo avanzado AST
                </button>
                <form method="POST" action="{{ route('bioestadistica.indicadores.formulas.store', $indicador) }}" class="collapse mt-3 {{ ($currentFormula && ! $simpleConstructor) || old('formula_mode') === 'advanced' ? 'show' : '' }}" id="formulaAvanzada">
                    @csrf
                    <input type="hidden" name="formula_mode" value="advanced">
                    <textarea class="form-control font-monospace" name="expresion" rows="10" placeholder='{"op":"pct","args":[...]}'>{{ old('expresion', $currentFormula ? json_encode($currentFormula->expresion, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
                    <div class="form-row mt-2">
                        <div class="col-md-6">
                            <input class="form-control" type="date" name="vigente_desde" placeholder="Desde" value="{{ old('vigente_desde', optional($currentFormula?->vigente_desde)->format('Y-m-d')) }}">
                            <small class="text-muted">En “Actualizar vigente” se respetan estas fechas. En “Nueva versión”, vacío = hoy.</small>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="date" name="vigente_hasta" placeholder="Hasta" value="{{ old('vigente_hasta', optional($currentFormula?->vigente_hasta)->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="d-flex flex-wrap mt-2" style="gap:.5rem">
                        @if($currentFormula)
                            <button class="btn btn-info" type="submit"
                                    data-bio-formula-action="update"
                                    data-bio-formula-url="{{ route('bioestadistica.indicadores.formulas.update', [$indicador, $currentFormula]) }}">
                                Actualizar fórmula vigente (id {{ $currentFormula->id }})
                            </button>
                        @endif
                        <button class="btn btn-success" type="submit"
                                data-bio-formula-action="store"
                                data-bio-formula-url="{{ route('bioestadistica.indicadores.formulas.store', $indicador) }}">
                            Guardar como nueva versión
                        </button>
                    </div>
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
                        <div class="form-group col-md-4">
                            <label>Año del dato</label>
                            @include('admin.bioestadistica._periodo-anio-select', [
                                'name' => 'periodo_anio',
                                'value' => old('periodo_anio', $evaluationContext['periodo_anio'] ?? now()->year),
                                'required' => true,
                            ])
                        </div>
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
                    <td class="text-nowrap">
                        @can('bio.indicator.manage')
                            @if($currentFormula && (int) $currentFormula->id === (int) $formula->id)
                                <a class="btn btn-outline-info btn-sm" href="#formulaAvanzada" data-toggle="collapse" data-target="#formulaAvanzada">Editar</a>
                            @endif
                            <form method="POST" action="{{ route('bioestadistica.indicadores.formulas.destroy', [$indicador, $formula]) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta fórmula?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Eliminar</button></form>
                        @endcan
                    </td>
                </tr>
            @empty<tr><td colspan="4" class="text-center text-muted">Sin fórmulas.</td></tr>@endforelse</tbody>
        </table>
    </div>
</div>
<a href="{{ route('bioestadistica.indicadores.index') }}" class="btn btn-secondary">Volver</a>
@endsection

@section('scripts')
<script>
(function () {
    document.querySelectorAll('[data-bio-formula-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var form = btn.closest('form');
            if (!form) return;
            var url = btn.getAttribute('data-bio-formula-url');
            var action = btn.getAttribute('data-bio-formula-action');
            form.setAttribute('action', url);
            var methodInput = form.querySelector('input[name="_method"]');
            if (action === 'update') {
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PUT';
            } else if (methodInput) {
                methodInput.remove();
            }
        });
    });
})();
</script>
@endsection
