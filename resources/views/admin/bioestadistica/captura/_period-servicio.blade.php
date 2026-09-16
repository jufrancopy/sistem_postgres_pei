@include('admin.bioestadistica._siplan-styles')
<div class="bio-filters mb-3">
    <div class="mb-2"><strong>Período y dependencia</strong></div>
    @if($canEditPeriod)
        @php
            $cortesPeriodo = app(\App\Application\Bioestadistica\Organigrama\OrganoCorteService::class)
                ->opcionesParaEstablecimiento((int) $record->establecimiento_id);
        @endphp
        <form method="POST" action="{{ route('bioestadistica.captura.period.update', $record) }}" id="bio-period-form">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="col-6 col-md-2 mb-2">
                    <label class="small text-muted mb-1 d-block">Año</label>
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_anio',
                        'value' => old('periodo_anio', $record->periodo_anio),
                        'required' => true,
                    ])
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <label class="small text-muted mb-1 d-block">Mes</label>
                    <select class="form-control bio-select2" name="periodo_mes" required>
                        @foreach($months as $number => $month)
                            <option value="{{ $number }}" @selected((int) old('periodo_mes', $record->periodo_mes) === $number)>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-8 mb-2">
                    @include('admin.bioestadistica.captura._organo-corte-select', [
                        'cortes' => $cortesPeriodo,
                        'organoSelected' => (int) old('organo_id', $record->organo_id ?? 0),
                        'selectId' => 'bio-period-organo',
                        'wrapperClass' => 'mb-0',
                        'labelClass' => 'small text-muted mb-1 d-block',
                        'corteRequired' => $cortesPeriodo->isNotEmpty(),
                        'alwaysEnabled' => true,
                        'hideHelp' => true,
                        'useFormGroup' => false,
                    ])
                </div>
            </div>
            <div class="mb-1">
                <button class="btn btn-primary btn-sm" type="submit">Aplicar</button>
            </div>
            <small class="text-muted d-block">
                @if($cortesPeriodo->isEmpty())
                    Este establecimiento no tiene organigrama vinculado; la carga queda a nivel establecimiento.
                @else
                    Busque el nombre de la dependencia (departamento, servicio o sección).
                @endif
                {{ ' ' . ($periodHelp ?? 'Al aplicarlo se recarga el formulario.') }}
            </small>
            @if($record->formulario->codigo === 'SP11')
                <small class="text-warning d-block mt-1">Si el mes destino tiene menos días, primero quite los valores de los días que dejarían de existir.</small>
            @endif
        </form>
    @else
        <div class="mb-2">
            @if($record->corteLabel())
                <span class="badge badge-light border text-dark">{{ $record->corteLabel() }}</span>
            @else
                <span class="text-muted small">Sin dependencia</span>
            @endif
        </div>
        <div class="form-row">
            <div class="col-md-2 mb-2">
                <label class="small text-muted mb-1">Año</label>
                <div class="font-weight-bold">{{ $record->periodo_anio }}</div>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small text-muted mb-1">Mes</label>
                <div class="font-weight-bold">{{ $months[$record->periodo_mes] ?? $record->periodo_mes }}</div>
            </div>
        </div>
        <small class="text-muted">Solo lectura: el registro no está editable con su rol/estado actual.</small>
    @endif
</div>
