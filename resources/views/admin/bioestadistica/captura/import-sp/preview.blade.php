@extends('layouts.master')
@section('title', 'Vista previa — Importar')

@php
    $est = $preview['establecimiento'] ?? null;
    $mes = (int) old('periodo_mes', $preview['periodo_mes'] ?? $detectado['periodo_mes'] ?? 0);
    $anio = (int) old('periodo_anio', $preview['periodo_anio'] ?? $detectado['periodo_anio'] ?? 0);
    $formularioId = (int) old('formulario_id', $preview['formulario_id'] ?? 0);
    $establecimientoId = (int) old('establecimiento_id', $preview['establecimiento_id'] ?? 0);
    $detectadoCodigo = $preview['formulario_codigo_detectado'] ?? $detectado['formulario_codigo'] ?? '—';
@endphp

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [
    ['label' => 'Carga de datos', 'url' => route('bioestadistica.captura.index')],
    ['label' => 'Importar', 'url' => route('bioestadistica.captura.import.index')],
    ['label' => 'Resumen', 'url' => route('bioestadistica.captura.import.summary')],
    ['label' => 'Vista previa'],
]])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title">Vista previa — {{ $preview['archivo'] ?? 'planilla' }}</h4>
        <p class="card-category">Confirme formulario (SP), establecimiento, período y matching antes de importar.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">SP / hoja activa</small>
                    <strong>{{ $detectadoCodigo }}</strong>
                    <div class="small text-muted">Hoja: {{ $detectado['hoja'] ?? $preview['hoja_activa'] ?? '—' }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Establecimiento (planilla)</small>
                    <div>{{ $detectado['establecimiento_nombre'] ?? '—' }}</div>
                    <div class="small">Código: {{ $detectado['codigo_planilla'] ?? '—' }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Período detectado</small>
                    <strong>{{ $months[$mes] ?? $mes }}/{{ $anio ?: '—' }}</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Filas con datos</small>
                    <strong>{{ $detectado['filas_detectadas'] ?? 0 }}</strong>
                    @if(($detectado['layout'] ?? 'tabular') === 'matriz')
                        <div class="small text-success">Filas matriz: {{ count($detectado['matriz']['rows'] ?? []) }}</div>
                    @elseif(($detectado['layout'] ?? '') === 'nominativo')
                        <div class="small text-success">Episodios: {{ count($detectado['episodios'] ?? []) }}</div>
                    @else
                        <div class="small text-success">Match auto: {{ $detectado['prestaciones_enlazadas'] ?? 0 }}</div>
                        <div class="small text-warning">Sin match: {{ $detectado['prestaciones_sin_match'] ?? 0 }}</div>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($detectado['advertencias']))
            <div class="alert alert-warning"><ul class="mb-0">@foreach($detectado['advertencias'] as $w)<li>{{ $w }}</li>@endforeach</ul></div>
        @endif

        @if(!($preview['importable'] ?? true))
            <div class="alert alert-warning">
                El SP seleccionado aún no tiene importación de datos implementada. Puede revisar el matching,
                pero solo los formularios marcados como «Importación disponible» permiten confirmar.
            </div>
        @endif

        {{-- Actualizar contexto (SP / establecimiento / período) sin re-subir archivo --}}
        <form method="GET" action="{{ route('bioestadistica.captura.import.preview') }}" id="bio-import-context-form" class="border rounded p-3 mb-3 bg-light">
            <h5 class="mb-3">Contexto de la carga</h5>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Formulario (SP) *</label>
                    <select class="form-control" name="formulario_id" id="bio-import-formulario" required>
                        @foreach($formularios as $formulario)
                            <option value="{{ $formulario->id }}" @selected($formularioId === (int) $formulario->id)>
                                {{ $formulario->codigo }} — {{ $formulario->nombre }}
                                @if(in_array($formulario->codigo, \App\Application\Bioestadistica\Imports\SpPlanillaImportService::IMPORTABLE, true))
                                    · importación disponible
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @if($detectadoCodigo !== ($preview['formulario_codigo'] ?? ''))
                        <small class="text-muted">Detectado: {{ $detectadoCodigo }}</small>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label>Establecimiento *</label>
                    <select class="form-control" name="establecimiento_id" id="bio-import-establecimiento" required>
                        <option value="">Seleccione</option>
                        @foreach($establecimientos as $item)
                            <option value="{{ $item->id }}" @selected($establecimientoId === (int) $item->id)>
                                {{ $item->nombre }} ({{ $item->codigo }}@if($item->codigo_sih) · SIH {{ $item->codigo_sih }}@endif)
                            </option>
                        @endforeach
                    </select>
                    @if($est && !($est['distrito_ok'] ?? true))
                        <small class="text-danger">Sin distrito asignado — no podrá capturar.</small>
                    @endif
                </div>
                <div class="form-group col-md-2">
                    <label>Año *</label>
                    <input class="form-control" type="number" name="periodo_anio" min="1990" max="2100" value="{{ $anio ?: now()->year }}" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Mes *</label>
                    <select class="form-control" name="periodo_mes" required>
                        @foreach($months as $num => $label)
                            <option value="{{ $num }}" @selected($mes === (int) $num)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6" id="bio-import-corte-group" style="{{ ($preview['tiene_servicios'] ?? false) ? '' : 'display:none' }}">
                    <label>Departamento / servicio <span id="bio-import-corte-required">*</span></label>
                    <select class="form-control" name="estructura_servicio_id" id="bio-import-corte">
                        <option value="">—</option>
                        @foreach($preview['cortes'] ?? [] as $corte)
                            <option value="{{ $corte['id'] }}" @selected((string) ($preview['estructura_servicio_id'] ?? '') === (string) $corte['id'])>{{ $corte['label'] }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="bio-import-corte-help">Este establecimiento tiene servicios asociados; la carga debe indicar el corte.</small>
                </div>
                <div class="form-group col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-info btn-sm">Actualizar vista previa</button>
                </div>
            </div>
            <p class="text-muted small mb-0" id="bio-import-sin-servicios" style="{{ ($preview['tiene_servicios'] ?? false) ? 'display:none' : '' }}">
                El establecimiento seleccionado no tiene servicios asociados; la carga es a nivel de establecimiento.
            </p>
        </form>

        @if($preview['record_existente'] ?? null)
            <div class="alert alert-{{ ($preview['record_existente']['editable'] ?? false) ? 'warning' : 'danger' }}">
                Ya existe un registro #{{ $preview['record_existente']['id'] }} en estado
                <strong>{{ $preview['record_existente']['estado'] }}</strong> para este SP, establecimiento y período.
                @if($preview['record_existente']['editable'] ?? false)
                    Marque «Sobrescribir borrador» al confirmar.
                @else
                    No se puede importar sobre un registro enviado o aprobado.
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('bioestadistica.captura.import.confirm') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $preview['token'] }}">
            <input type="hidden" name="formulario_id" value="{{ $formularioId }}">
            <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
            <input type="hidden" name="periodo_anio" value="{{ $anio ?: now()->year }}">
            <input type="hidden" name="periodo_mes" value="{{ $mes }}">
            <input type="hidden" name="estructura_servicio_id" id="bio-import-corte-hidden" value="{{ $preview['estructura_servicio_id'] ?? '' }}">

            @if($preview['record_existente']['editable'] ?? false)
                <label class="d-block mb-3">
                    <input type="checkbox" name="sobrescribir" value="1" @checked(old('sobrescribir'))>
                    Sobrescribir borrador existente
                </label>
            @endif

            <h5>Matching de prestaciones</h5>
            <p class="text-muted small">Nivel 1–3 = match automático al diccionario del SP elegido. Nivel 4 = elija manualmente o descarte.</p>
            <div class="table-responsive mb-3">
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>COD</th>
                            <th>Prestación (planilla)</th>
                            <th>Valores</th>
                            <th>Nivel</th>
                            <th>Sugerencia</th>
                            <th>Decisión</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($detectado['filas'] ?? [] as $fila)
                        <tr>
                            <td>{{ $fila['cod_planilla'] ?? '—' }}</td>
                            <td>{{ $fila['prestacion_label'] ?? $fila['especialidad'] }}</td>
                            <td>
                                @if(!empty($fila['metricas']))
                                    @foreach($fila['metricas'] as $code => $val)
                                        <span class="d-block small"><code>{{ $code }}</code>: {{ number_format($val, 0, ',', '.') }}</span>
                                    @endforeach
                                @else
                                    {{ number_format((int) ($fila['total_consultas'] ?? $fila['total'] ?? 0), 0, ',', '.') }}
                                @endif
                            </td>
                            <td>{{ $fila['nivel'] ?? '—' }}</td>
                            <td>{{ $fila['sugerencia'] ?? '—' }} @if(!empty($fila['score']))<small>({{ $fila['score'] }})</small>@endif</td>
                            <td>
                                @if(!empty($fila['prestacion_id']) && (int) ($fila['nivel'] ?? 4) < 4)
                                    <span class="text-success">Enlazada</span>
                                    <input type="hidden" name="decisiones[{{ $fila['key'] }}]" value="{{ $fila['prestacion_id'] }}">
                                @else
                                    <select class="form-control form-control-sm" name="decisiones[{{ $fila['key'] }}]">
                                        <option value="">Pendiente</option>
                                        <option value="discard">Descartar fila</option>
                                        @foreach($prestaciones as $prestacion)
                                            <option value="{{ $prestacion->id }}" @selected((string) old('decisiones.'.$fila['key']) === (string) $prestacion->id)>
                                                {{ $prestacion->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @php
                $canConfirm = ($preview['importable'] ?? false)
                    && ! ($preview['record_existente'] && !($preview['record_existente']['editable'] ?? false));
            @endphp
            <button type="submit" class="btn btn-success" @disabled(! $canConfirm)>
                Confirmar e importar borrador
            </button>
            <a href="{{ route('bioestadistica.captura.import.summary') }}" class="btn btn-outline-info">Volver al resumen</a>
            <a href="{{ route('bioestadistica.captura.import.index') }}" class="btn btn-secondary">Analizar otro archivo</a>
        </form>

        <form method="POST" action="{{ route('bioestadistica.captura.import.discard') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-danger">Descartar análisis</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const estSelect = document.getElementById('bio-import-establecimiento');
    const corteGroup = document.getElementById('bio-import-corte-group');
    const corteSelect = document.getElementById('bio-import-corte');
    const corteHidden = document.getElementById('bio-import-corte-hidden');
    const sinServicios = document.getElementById('bio-import-sin-servicios');
    const contextForm = document.getElementById('bio-import-context-form');

    if (!estSelect || !corteSelect) return;

    function syncCorteHidden() {
        if (corteHidden && corteSelect) {
            corteHidden.value = corteSelect.value || '';
        }
    }

    function loadCortes() {
        const id = estSelect.value;
        if (!id) {
            corteGroup.style.display = 'none';
            sinServicios.style.display = '';
            corteSelect.innerHTML = '<option value="">—</option>';
            corteSelect.required = false;
            syncCorteHidden();
            return;
        }
        fetch('{{ route('bioestadistica.estructura.cortes') }}?establecimiento_id=' + encodeURIComponent(id), {
            headers: { 'Accept': 'application/json' }
        })
            .then(r => r.json())
            .then(payload => {
                const cortes = payload.data || [];
                if (cortes.length === 0) {
                    corteGroup.style.display = 'none';
                    sinServicios.style.display = '';
                    corteSelect.innerHTML = '<option value="">—</option>';
                    corteSelect.required = false;
                } else {
                    corteGroup.style.display = '';
                    sinServicios.style.display = 'none';
                    corteSelect.required = true;
                    let html = '<option value="">Seleccione</option>';
                    cortes.forEach(c => {
                        html += '<option value="' + c.id + '">' + c.label + '</option>';
                    });
                    corteSelect.innerHTML = html;
                }
                syncCorteHidden();
            });
    }

    estSelect.addEventListener('change', function () {
        loadCortes();
        contextForm.submit();
    });
    corteSelect.addEventListener('change', syncCorteHidden);

    ['formulario_id', 'periodo_anio', 'periodo_mes'].forEach(function (name) {
        const el = contextForm.querySelector('[name="' + name + '"]');
        if (el) el.addEventListener('change', function () { contextForm.submit(); });
    });
});
</script>
@endsection
