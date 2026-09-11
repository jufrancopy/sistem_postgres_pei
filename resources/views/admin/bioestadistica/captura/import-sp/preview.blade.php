@extends('layouts.master')
@section('title', 'Vista previa — Importar')

@php
    $est = $preview['establecimiento'] ?? null;
    $mes = (int) old('periodo_mes', $preview['periodo_mes'] ?? $detectado['periodo_mes'] ?? 0);
    $anio = (int) old('periodo_anio', $preview['periodo_anio'] ?? $detectado['periodo_anio'] ?? 0);
    $formularioId = (int) old('formulario_id', $preview['formulario_id'] ?? 0);
    $establecimientoId = (int) old('establecimiento_id', $preview['establecimiento_id'] ?? 0);
    $detectadoCodigo = $preview['formulario_codigo_detectado'] ?? $detectado['formulario_codigo'] ?? '—';
    $periodoDetectadoMes = (int) ($detectado['periodo_mes'] ?? $preview['workbook']['contexto']['periodo_mes'] ?? 0);
    $periodoDetectadoAnio = (int) ($detectado['periodo_anio'] ?? $preview['workbook']['contexto']['periodo_anio'] ?? 0);
    $periodoDesfasado = $periodoDetectadoMes > 0 && $periodoDetectadoAnio > 0
        && $mes > 0 && $anio > 0
        && ($periodoDetectadoMes !== $mes || $periodoDetectadoAnio !== $anio);
    $filasMatching = collect($detectado['filas'] ?? []);
    $countSugeridas = $filasMatching->filter(fn ($f) => (int) ($f['nivel'] ?? 4) === 3 && ! empty($f['prestacion_id']))->count();
    $countSinMatch = $filasMatching->filter(fn ($f) => empty($f['prestacion_id']) || (int) ($f['nivel'] ?? 4) >= 4)->count();
    $hojaActiva = $preview['hoja_activa'] ?? $detectado['hoja'] ?? '';
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
                    <small class="text-muted d-block">Período confirmado</small>
                    <strong>{{ $months[$mes] ?? $mes }}/{{ $anio ?: '—' }}</strong>
                    @if($periodoDetectadoMes && $periodoDetectadoAnio)
                        <div class="small text-muted">Detectado en Excel: {{ $months[$periodoDetectadoMes] ?? $periodoDetectadoMes }}/{{ $periodoDetectadoAnio }}</div>
                    @endif
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

        @if($periodoDesfasado)
            <div class="alert alert-warning">
                El período del Excel ({{ $months[$periodoDetectadoMes] ?? $periodoDetectadoMes }}/{{ $periodoDetectadoAnio }})
                no coincide con el confirmado ({{ $months[$mes] ?? $mes }}/{{ $anio }}).
                Revise el contexto: el período estadístico <strong>no</strong> se toma de la fecha de subida.
            </div>
        @endif

        @if(!empty($detectado['advertencias']))
            <div class="alert alert-warning"><ul class="mb-0">@foreach($detectado['advertencias'] as $w)<li>{{ $w }}</li>@endforeach</ul></div>
        @endif

        @if(!($preview['importable'] ?? true))
            <div class="alert alert-warning">
                El SP seleccionado aún no tiene importación de datos implementada. Puede revisar el matching,
                pero solo los formularios marcados como «Importación disponible» permiten confirmar.
            </div>
        @endif

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
                    <select class="form-control bio-select2" name="establecimiento_id" id="bio-import-establecimiento" data-placeholder="Buscar establecimiento…" required>
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
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_anio',
                        'value' => $anio ?: now()->year,
                        'required' => true,
                    ])
                </div>
                <div class="form-group col-md-2">
                    <label>Mes *</label>
                    <select class="form-control bio-select2" name="periodo_mes" data-placeholder="Mes" required>
                        @foreach($months as $num => $label)
                            <option value="{{ $num }}" @selected($mes === (int) $num)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                @include('admin.bioestadistica.captura._organo-corte-select', [
                    'cortes' => $cortes ?? collect(),
                    'organoSelected' => (int) old('organo_id', $preview['organo_id'] ?? 0),
                    'selectId' => 'bio-import-organo',
                    'wrapperClass' => 'col-md-12',
                    'corteRequired' => ($cortes ?? collect())->isNotEmpty(),
                    'alwaysEnabled' => true,
                ])
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-info btn-sm">Actualizar vista previa</button>
                </div>
            </div>
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

        <form method="POST" action="{{ route('bioestadistica.captura.import.confirm') }}" id="bio-import-confirm-form">
            @csrf
            <input type="hidden" name="token" value="{{ $preview['token'] }}">
            <input type="hidden" name="formulario_id" value="{{ $formularioId }}">
            <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
            <input type="hidden" name="periodo_anio" value="{{ $anio ?: now()->year }}">
            <input type="hidden" name="periodo_mes" value="{{ $mes }}">
            @if(!empty($preview['organo_id']))
                <input type="hidden" name="organo_id" value="{{ $preview['organo_id'] }}">
            @endif

            @if($preview['record_existente']['editable'] ?? false)
                <label class="d-block mb-3">
                    <input type="checkbox" name="sobrescribir" value="1" @checked(old('sobrescribir'))>
                    Sobrescribir borrador existente
                </label>
            @endif

            <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                <div>
                    <h5 class="mb-1">Matching de prestaciones</h5>
                    <p class="text-muted small mb-0">
                        Nivel 1–2 = exacto. Nivel 3 = sugerencia fuzzy. Nivel 4 = sin match (elija o descarte).
                        Si el layout está corrido,
                        <a href="{{ route('bioestadistica.captura.import.map', ['hoja' => $hojaActiva]) }}">ajuste el mapeo</a>.
                    </p>
                </div>
                <div class="btn-group btn-group-sm mt-2 mt-md-0" role="group" aria-label="Filtro matching" id="bio-match-filters">
                    <button type="button" class="btn btn-outline-secondary active" data-filter="todas">Todas ({{ $filasMatching->count() }})</button>
                    <button type="button" class="btn btn-outline-secondary" data-filter="sin_match">Sin match ({{ $countSinMatch }})</button>
                    <button type="button" class="btn btn-outline-secondary" data-filter="sugeridas">Sugeridas ({{ $countSugeridas }})</button>
                </div>
            </div>

            @if($countSugeridas > 0)
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-outline-success" id="bio-apply-nivel3">
                        Aplicar todas las sugerencias nivel 3 ({{ $countSugeridas }})
                    </button>
                    <small class="text-muted ml-1">Confirma el enlace fuzzy en las filas sugeridas.</small>
                </div>
            @endif

            <div class="table-responsive mb-3">
                <table class="table table-sm table-bordered" id="bio-match-table">
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
                    @forelse($detectado['filas'] ?? [] as $fila)
                        @php
                            $nivel = (int) ($fila['nivel'] ?? 4);
                            $bucket = ($nivel === 3 && ! empty($fila['prestacion_id']))
                                ? 'sugeridas'
                                : ((empty($fila['prestacion_id']) || $nivel >= 4) ? 'sin_match' : 'exactas');
                        @endphp
                        <tr data-match-bucket="{{ $bucket }}" data-nivel="{{ $nivel }}">
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
                            <td>
                                {{ $nivel }}
                                @if($nivel === 3)
                                    <span class="badge badge-info">Sugerida</span>
                                @elseif($nivel <= 2 && ! empty($fila['prestacion_id']))
                                    <span class="badge badge-success">Exacta</span>
                                @endif
                            </td>
                            <td>{{ $fila['sugerencia'] ?? '—' }} @if(!empty($fila['score']))<small>({{ $fila['score'] }})</small>@endif</td>
                            <td>
                                @if(!empty($fila['prestacion_id']) && $nivel <= 2)
                                    <span class="text-success">Enlazada</span>
                                    <input type="hidden" name="decisiones[{{ $fila['key'] }}]" value="{{ $fila['prestacion_id'] }}">
                                @elseif(!empty($fila['prestacion_id']) && $nivel === 3)
                                    <select class="form-control form-control-sm bio-decision-select bio-nivel3-select"
                                            name="decisiones[{{ $fila['key'] }}]"
                                            data-sugerencia-id="{{ $fila['prestacion_id'] }}">
                                        <option value="">Pendiente</option>
                                        <option value="discard">Descartar fila</option>
                                        @foreach($prestaciones as $prestacion)
                                            <option value="{{ $prestacion->id }}" @selected((string) old('decisiones.'.$fila['key'], $fila['prestacion_id']) === (string) $prestacion->id)>
                                                {{ $prestacion->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-control form-control-sm bio-decision-select" name="decisiones[{{ $fila['key'] }}]">
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">
                                No hay filas parseadas.
                                <a href="{{ route('bioestadistica.captura.import.map', ['hoja' => $hojaActiva]) }}">Ajustar mapeo de esta hoja</a>
                                si el encabezado o las columnas no se detectaron.
                            </td>
                        </tr>
                    @endforelse
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
            <a href="{{ route('bioestadistica.captura.import.map', ['hoja' => $hojaActiva]) }}" class="btn btn-outline-secondary">Ajustar mapeo</a>
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
@include('admin.bioestadistica.captura._organo-corte-select-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const estSelect = document.getElementById('bio-import-establecimiento');
    const organoSelect = document.getElementById('bio-import-organo');
    const contextForm = document.getElementById('bio-import-context-form');
    const cortesUrl = @json($cortesUrl ?? route('bioestadistica.captura.cortes'));
    const preferredOrganoId = @json((string) old('organo_id', $preview['organo_id'] ?? ''));

    if (estSelect && organoSelect) {
        window.BioOrganoCorteSelect.load(organoSelect, cortesUrl, estSelect.value || '', preferredOrganoId);
    }

    if (estSelect && contextForm) {
        estSelect.addEventListener('change', function () {
            window.BioOrganoCorteSelect.load(organoSelect, cortesUrl, estSelect.value || '', '');
            contextForm.submit();
        });

        ['formulario_id', 'periodo_anio', 'periodo_mes'].forEach(function (name) {
            const el = contextForm.querySelector('[name="' + name + '"]');
            if (el) el.addEventListener('change', function () { contextForm.submit(); });
        });
    }

    const filterBar = document.getElementById('bio-match-filters');
    const matchTable = document.getElementById('bio-match-table');
    if (filterBar && matchTable) {
        filterBar.addEventListener('click', function (ev) {
            const btn = ev.target.closest('[data-filter]');
            if (!btn) return;
            const filter = btn.getAttribute('data-filter');
            filterBar.querySelectorAll('[data-filter]').forEach(function (b) {
                b.classList.toggle('active', b === btn);
            });
            matchTable.querySelectorAll('tbody tr[data-match-bucket]').forEach(function (row) {
                const bucket = row.getAttribute('data-match-bucket');
                let show = filter === 'todas';
                if (filter === 'sin_match') show = bucket === 'sin_match';
                if (filter === 'sugeridas') show = bucket === 'sugeridas';
                row.style.display = show ? '' : 'none';
            });
        });
    }

    const applyNivel3 = document.getElementById('bio-apply-nivel3');
    if (applyNivel3) {
        applyNivel3.addEventListener('click', function () {
            const selects = document.querySelectorAll('.bio-nivel3-select');
            if (!selects.length) return;
            if (!window.confirm('¿Aplicar las ' + selects.length + ' sugerencias de nivel 3 al diccionario?')) {
                return;
            }
            selects.forEach(function (sel) {
                const id = sel.getAttribute('data-sugerencia-id');
                if (id) sel.value = id;
            });
        });
    }
});
</script>
@endsection
