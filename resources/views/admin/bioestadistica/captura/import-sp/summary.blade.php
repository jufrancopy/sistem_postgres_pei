@extends('layouts.master')
@section('title', 'Resumen — Importar planilla')

@php
    $contexto = $workbook['contexto'] ?? [];
    $mes = (int) old('periodo_mes', $preview['periodo_mes'] ?? $contexto['periodo_mes'] ?? 0);
    $anio = (int) old('periodo_anio', $preview['periodo_anio'] ?? $contexto['periodo_anio'] ?? 0);
    $establecimientoId = (int) old('establecimiento_id', $preview['establecimiento_id'] ?? 0);
    $estConfirmado = $preview['establecimiento'] ?? null;
    $nombreEst = $estConfirmado['nombre']
        ?? $contexto['establecimiento_nombre']
        ?? $preview['detectado']['establecimiento_nombre']
        ?? null;
    $codigoEst = $estConfirmado['codigo']
        ?? $contexto['codigo_planilla']
        ?? $preview['detectado']['codigo_planilla']
        ?? null;
    $codigoSih = $estConfirmado['codigo_sih'] ?? null;
    $departamentoEst = $estConfirmado['departamento']
        ?? $contexto['departamento']
        ?? null;
    $distritoEst = $estConfirmado['distrito'] ?? null;
    $origenEst = $estConfirmado
        ? 'Confirmado para la carga'
        : 'Detectado en la planilla';
@endphp

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [
    ['label' => 'Carga de datos', 'url' => route('bioestadistica.captura.index')],
    ['label' => 'Importar', 'url' => route('bioestadistica.captura.import.index')],
    ['label' => 'Resumen de hojas'],
]])
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title">Resumen — {{ $preview['archivo'] ?? 'planilla' }}</h4>
        <p class="card-category">Se detectaron {{ count($workbook['hojas'] ?? []) }} hojas. Confirme el contexto y seleccione los SP a importar en lote (solo filas con match automático) o revise el detalle / ajuste el mapeo de cada uno.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Establecimiento</small>
                    <div>{{ $nombreEst ?: '—' }}</div>
                    <div class="small">
                        Código: {{ $codigoEst ?: '—' }}
                        @if($codigoSih) · SIH {{ $codigoSih }} @endif
                    </div>
                    <div class="small text-muted">{{ $origenEst }}</div>
                    @if($estConfirmado && !($estConfirmado['distrito_ok'] ?? true))
                        <div class="small text-danger">Sin distrito asignado</div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Período confirmado</small>
                    <strong>{{ $months[$mes] ?? $mes }}/{{ $anio ?: '—' }}</strong>
                    @php
                        $periodoExcelMes = (int) ($contexto['periodo_mes'] ?? 0);
                        $periodoExcelAnio = (int) ($contexto['periodo_anio'] ?? 0);
                        $periodoResumenDesfasado = $periodoExcelMes > 0 && $periodoExcelAnio > 0
                            && $mes > 0 && $anio > 0
                            && ($periodoExcelMes !== $mes || $periodoExcelAnio !== $anio);
                    @endphp
                    @if($periodoExcelMes && $periodoExcelAnio)
                        <div class="small text-muted">Detectado en Excel: {{ $months[$periodoExcelMes] ?? $periodoExcelMes }}/{{ $periodoExcelAnio }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Departamento / distrito</small>
                    <div>{{ $departamentoEst ?: '—' }}</div>
                    @if($distritoEst)
                        <div class="small text-muted">{{ $distritoEst }}</div>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($periodoResumenDesfasado))
            <div class="alert alert-warning">
                El período del Excel no coincide con el confirmado. Ajuste año/mes en el contexto si corresponde;
                el período estadístico no se toma de la fecha de subida.
            </div>
        @endif

        <form method="GET" action="{{ route('bioestadistica.captura.import.summary') }}" id="bio-import-summary-context" class="border rounded p-3 mb-3 bg-light">
            <h5 class="mb-3">Contexto compartido de la carga</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Establecimiento *</label>
                    <select class="form-control bio-select2" name="establecimiento_id" id="bio-summary-establecimiento" data-placeholder="Buscar establecimiento…" required>
                        <option value="">Seleccione</option>
                        @foreach($establecimientos as $item)
                            <option value="{{ $item->id }}" @selected($establecimientoId === (int) $item->id)>
                                {{ $item->nombre }} ({{ $item->codigo }}@if($item->codigo_sih) · SIH {{ $item->codigo_sih }}@endif)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Año *</label>
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_anio',
                        'value' => $anio ?: now()->year,
                        'required' => true,
                    ])
                </div>
                <div class="form-group col-md-3">
                    <label>Mes *</label>
                    <select class="form-control bio-select2" name="periodo_mes" data-placeholder="Mes" required>
                        @foreach($months as $num => $label)
                            <option value="{{ $num }}" @selected($mes === (int) $num)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('admin.bioestadistica.captura._organo-corte-select', [
                'cortes' => $cortes ?? collect(),
                'organoSelected' => (int) old('organo_id', $preview['organo_id'] ?? 0),
                'selectId' => 'bio-summary-organo',
                'wrapperClass' => 'mb-3',
                'corteRequired' => ($cortes ?? collect())->isNotEmpty(),
                'alwaysEnabled' => true,
            ])
            <div class="form-row">
                <div class="form-group col-md-6 d-flex align-items-end mb-0">
                    <button type="submit" class="btn btn-outline-info btn-sm">Actualizar contexto</button>
                </div>
            </div>
        </form>

        @php
            $hojasLote = collect($workbook['hojas'] ?? [])->filter(fn ($h) => $h['listo_lote'] ?? false);
            $hayBorradorExistente = collect($workbook['hojas'] ?? [])->contains(
                fn ($h) => ($h['record_existente']['editable'] ?? false)
            );
        @endphp

        <h5>Hojas detectadas</h5>
        <form method="POST" action="{{ route('bioestadistica.captura.import.confirm-batch') }}" id="bio-import-batch-form">
            @csrf
            <input type="hidden" name="token" value="{{ $preview['token'] }}">
            <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
            <input type="hidden" name="periodo_anio" value="{{ $anio ?: now()->year }}">
            <input type="hidden" name="periodo_mes" value="{{ $mes }}">
            @if(!empty($preview['organo_id']))
                <input type="hidden" name="organo_id" value="{{ $preview['organo_id'] }}">
            @endif

        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered">
                <thead class="thead-light">
                    <tr>
                        @if($hojasLote->isNotEmpty())
                            <th style="width:2rem"><input type="checkbox" id="bio-batch-select-all" title="Seleccionar listos"></th>
                        @endif
                        <th>Hoja</th>
                        <th>SP</th>
                        <th>Filas</th>
                        <th>Match auto</th>
                        <th>Calidad</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($workbook['hojas'] ?? [] as $hojaIdx => $hoja)
                    <tr @if(($preview['hoja_activa'] ?? '') === ($hoja['titulo'] ?? '')) class="table-info" @endif>
                        @if($hojasLote->isNotEmpty())
                            <td>
                                @if($hoja['listo_lote'] ?? false)
                                    <input type="checkbox" class="bio-batch-hoja" name="hojas[]" value="{{ $hoja['titulo'] }}" checked>
                                @endif
                            </td>
                        @endif
                        <td>
                            {{ $hoja['titulo'] ?? '—' }}
                            @if(!empty($hoja['advertencia_hoja']))
                                <div class="small text-muted">{{ $hoja['advertencia_hoja'] }}</div>
                            @endif
                        </td>
                        <td>{{ $hoja['sp_codigo'] ?? '—' }}</td>
                        <td>{{ $hoja['filas_detectadas'] ?? 0 }}</td>
                        <td>
                            @if(isset($hoja['prestaciones_enlazadas']))
                                <span class="text-success">{{ $hoja['prestaciones_enlazadas'] }}</span>
                                @if(($hoja['prestaciones_sin_match'] ?? 0) > 0)
                                    <span class="text-warning small"> / {{ $hoja['prestaciones_sin_match'] }} sin match</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @php $calidad = $hoja['calidad'] ?? null; @endphp
                            @if($calidad)
                                @php
                                    $badge = match($calidad['nivel'] ?? '') {
                                        'alto' => 'badge-success',
                                        'medio' => 'badge-info',
                                        'bajo' => 'badge-warning',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}" title="{{ implode(' · ', $calidad['motivos'] ?? []) }}">
                                    {{ ucfirst($calidad['nivel'] ?? '—') }} ({{ $calidad['score'] ?? 0 }})
                                </span>
                                @if(!empty($calidad['motivos']))
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit(implode(' · ', $calidad['motivos']), 60) }}</div>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($hoja['importable'] ?? false)
                                @if($hoja['listo_lote'] ?? false)
                                    <span class="badge badge-success">Listo en lote</span>
                                @else
                                    <span class="badge badge-success">Disponible</span>
                                @endif
                                @if($hoja['record_existente'] ?? null)
                                    <div class="small">Registro #{{ $hoja['record_existente']['id'] }} ({{ $hoja['record_existente']['estado'] }})</div>
                                @endif
                            @elseif($hoja['parseado'] ?? false)
                                <span class="badge badge-secondary">Solo vista previa</span>
                            @elseif(($hoja['parser_disponible'] ?? false) && ! empty($hoja['error']))
                                <span class="badge badge-warning" title="{{ $hoja['error'] }}">Sin datos parseables</span>
                                <div class="small text-muted">{{ Str::limit($hoja['error'], 80) }}</div>
                            @elseif($hoja['sp_codigo'] ?? null)
                                <span class="badge badge-warning">Parser pendiente</span>
                            @else
                                <span class="text-muted small">{{ $hoja['error'] ?? 'Sin SP detectado' }}</span>
                                <div class="small"><a href="{{ route('bioestadistica.captura.import.map', ['hoja_idx' => $hojaIdx, 'hoja' => $hoja['titulo']]) }}">Ajustar mapeo</a></div>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            @if(($hoja['parseado'] ?? false) && ($hoja['filas_detectadas'] ?? 0) > 0)
                                <a href="{{ route('bioestadistica.captura.import.preview', ['hoja' => $hoja['titulo']]) }}" class="btn btn-sm btn-outline-info">
                                    Detalle
                                </a>
                            @endif
                            <a href="{{ route('bioestadistica.captura.import.map', ['hoja_idx' => $hojaIdx, 'hoja' => $hoja['titulo']]) }}" class="btn btn-sm btn-outline-secondary" title="Asignar SP, fila y columnas">
                                Ajustar mapeo
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-muted">No se detectaron hojas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($hojasLote->isNotEmpty() && $establecimientoId && $mes && $anio && (($cortes ?? collect())->isEmpty() || !empty($preview['organo_id'])))
            <div class="border rounded p-3 mb-3 bg-light">
                <h5 class="mb-2">Importación en lote</h5>
                <p class="text-muted small mb-2">
                    Importa los SP seleccionados usando solo filas con match automático (nivel 1–3).
                    Las filas sin match deben corregirse desde el detalle de cada hoja.
                </p>
                @if($hayBorradorExistente)
                    <label class="d-block mb-2">
                        <input type="checkbox" name="sobrescribir" value="1">
                        Sobrescribir borradores existentes
                    </label>
                @endif
                <button type="submit" class="btn btn-success" id="bio-batch-submit">
                    Importar seleccionados en borrador
                </button>
            </div>
        @elseif($establecimientoId === 0 || ! $mes || ! $anio || (($cortes ?? collect())->isNotEmpty() && empty($preview['organo_id'])))
            <div class="alert alert-warning">Complete establecimiento, período y dependencia (si aplica) en el contexto para habilitar la importación en lote.</div>
        @endif
        </form>

        <a href="{{ route('bioestadistica.captura.import.index') }}" class="btn btn-secondary">Analizar otro archivo</a>
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
    const estSelect = document.getElementById('bio-summary-establecimiento');
    const organoSelect = document.getElementById('bio-summary-organo');
    const contextForm = document.getElementById('bio-import-summary-context');
    if (!estSelect || !contextForm) return;

    const cortesUrl = @json($cortesUrl ?? route('bioestadistica.captura.cortes'));
    const preferredOrganoId = @json((string) old('organo_id', $preview['organo_id'] ?? ''));

    window.BioOrganoCorteSelect.load(organoSelect, cortesUrl, estSelect.value || '', preferredOrganoId);

    estSelect.addEventListener('change', function () {
        window.BioOrganoCorteSelect.load(organoSelect, cortesUrl, estSelect.value || '', '');
        contextForm.submit();
    });
    if (window.jQuery) {
        window.jQuery(estSelect).on('select2:select select2:clear', function () {
            window.BioOrganoCorteSelect.load(organoSelect, cortesUrl, estSelect.value || '', '');
        });
    }
    ['periodo_anio', 'periodo_mes'].forEach(function (name) {
        const el = contextForm.querySelector('[name="' + name + '"]');
        if (el) el.addEventListener('change', function () { contextForm.submit(); });
    });

    const selectAll = document.getElementById('bio-batch-select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.bio-batch-hoja').forEach(function (cb) {
                cb.checked = selectAll.checked;
            });
        });
    }

    const batchForm = document.getElementById('bio-import-batch-form');
    if (batchForm && contextForm) {
        const syncOrgano = function () {
            const src = contextForm.querySelector('[name="organo_id"]');
            let dst = batchForm.querySelector('[name="organo_id"]');
            if (src && !dst) {
                dst = document.createElement('input');
                dst.type = 'hidden';
                dst.name = 'organo_id';
                batchForm.appendChild(dst);
            }
            if (src && dst) dst.value = src.value;
            ['establecimiento_id', 'periodo_anio', 'periodo_mes'].forEach(function (name) {
                const s = contextForm.querySelector('[name="' + name + '"]');
                const d = batchForm.querySelector('[name="' + name + '"]');
                if (s && d) d.value = s.value;
            });
        };
        contextForm.addEventListener('submit', syncOrgano);
        batchForm.addEventListener('submit', syncOrgano);
    }
});
</script>
@endsection
