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
                    <small class="text-muted">{{ $periodHelp ?? 'Al aplicarlo se recarga el formulario.' }}</small>
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
