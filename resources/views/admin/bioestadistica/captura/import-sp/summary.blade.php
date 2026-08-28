@extends('layouts.master')
@section('title', 'Resumen — Importar planilla')

@php
    $contexto = $workbook['contexto'] ?? [];
    $mes = (int) old('periodo_mes', $preview['periodo_mes'] ?? $contexto['periodo_mes'] ?? 0);
    $anio = (int) old('periodo_anio', $preview['periodo_anio'] ?? $contexto['periodo_anio'] ?? 0);
    $establecimientoId = (int) old('establecimiento_id', $preview['establecimiento_id'] ?? 0);
    $codigoPlanilla = $contexto['codigo_planilla'] ?? $preview['detectado']['codigo_planilla'] ?? '—';
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
        <p class="card-category">Se detectaron {{ count($workbook['hojas'] ?? []) }} hojas. Confirme el contexto y seleccione los SP a importar en lote (solo filas con match automático) o revise el detalle de cada uno.</p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Establecimiento (planilla)</small>
                    <div>{{ $contexto['establecimiento_nombre'] ?? $preview['detectado']['establecimiento_nombre'] ?? '—' }}</div>
                    <div class="small">Código: {{ $codigoPlanilla }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Período detectado</small>
                    <strong>{{ $months[$mes] ?? $mes }}/{{ $anio ?: '—' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-2 h-100">
                    <small class="text-muted d-block">Departamento</small>
                    <div>{{ $contexto['departamento'] ?? '—' }}</div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('bioestadistica.captura.import.summary') }}" id="bio-import-summary-context" class="border rounded p-3 mb-3 bg-light">
            <h5 class="mb-3">Contexto compartido de la carga</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Establecimiento *</label>
                    <select class="form-control" name="establecimiento_id" id="bio-summary-establecimiento" required>
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
                    <input class="form-control" type="number" name="periodo_anio" min="1990" max="2100" value="{{ $anio ?: now()->year }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Mes *</label>
                    <select class="form-control" name="periodo_mes" required>
                        @foreach($months as $num => $label)
                            <option value="{{ $num }}" @selected($mes === (int) $num)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6" id="bio-summary-corte-group" style="{{ ($preview['tiene_servicios'] ?? false) ? '' : 'display:none' }}">
                    <label>Departamento / servicio <span id="bio-summary-corte-required">*</span></label>
                    <select class="form-control" name="estructura_servicio_id" id="bio-summary-corte">
                        <option value="">—</option>
                        @foreach($preview['cortes'] ?? [] as $corte)
                            <option value="{{ $corte['id'] }}" @selected((string) ($preview['estructura_servicio_id'] ?? '') === (string) $corte['id'])>{{ $corte['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6 d-flex align-items-end">
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
            <input type="hidden" name="estructura_servicio_id" value="{{ $preview['estructura_servicio_id'] ?? '' }}">

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
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($workbook['hojas'] ?? [] as $hoja)
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
                            @endif
                        </td>
                        <td class="text-nowrap">
                            @if(($hoja['parseado'] ?? false) && ($hoja['filas_detectadas'] ?? 0) > 0)
                                <a href="{{ route('bioestadistica.captura.import.preview', ['hoja' => $hoja['titulo']]) }}" class="btn btn-sm btn-outline-info">
                                    Detalle
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted">No se detectaron hojas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($hojasLote->isNotEmpty() && $establecimientoId && $mes && $anio)
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
        @elseif($establecimientoId === 0 || ! $mes || ! $anio)
            <div class="alert alert-warning">Complete establecimiento, año y mes en el contexto para habilitar la importación en lote.</div>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const estSelect = document.getElementById('bio-summary-establecimiento');
    const corteGroup = document.getElementById('bio-summary-corte-group');
    const corteSelect = document.getElementById('bio-summary-corte');
    const contextForm = document.getElementById('bio-import-summary-context');
    if (!estSelect || !contextForm) return;

    function loadCortes() {
        const id = estSelect.value;
        if (!id) {
            corteGroup.style.display = 'none';
            if (corteSelect) {
                corteSelect.innerHTML = '<option value="">—</option>';
                corteSelect.required = false;
            }
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
                    corteSelect.required = false;
                    corteSelect.innerHTML = '<option value="">—</option>';
                } else {
                    corteGroup.style.display = '';
                    corteSelect.required = true;
                    let html = '<option value="">Seleccione</option>';
                    cortes.forEach(c => { html += '<option value="' + c.id + '">' + c.label + '</option>'; });
                    corteSelect.innerHTML = html;
                }
            });
    }

    estSelect.addEventListener('change', function () {
        loadCortes();
        contextForm.submit();
    });
    ['periodo_anio', 'periodo_mes', 'estructura_servicio_id'].forEach(function (name) {
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
        contextForm.addEventListener('submit', function () {
            const map = {
                establecimiento_id: 'establecimiento_id',
                periodo_anio: 'periodo_anio',
                periodo_mes: 'periodo_mes',
                estructura_servicio_id: 'estructura_servicio_id',
            };
            Object.keys(map).forEach(function (name) {
                const src = contextForm.querySelector('[name="' + name + '"]');
                const dst = batchForm.querySelector('[name="' + name + '"]');
                if (src && dst) dst.value = src.value;
            });
        });
    }
});
</script>
@endsection
