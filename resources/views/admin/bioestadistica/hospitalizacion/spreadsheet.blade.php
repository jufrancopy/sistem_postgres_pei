@extends('layouts.master')
@section('title', 'Planilla de hospitalización SP10')

@php
    $canEditPeriod = auth()->user()->can('update', $record);
    $canEditRows = $record->isEditable() && auth()->user()->can('bio.hosp.manage');
    $rows = old('rows');
    if ($rows === null) {
        $rows = $episodios->map(fn ($episode) => [
            'id' => $episode->id,
            'cedula' => auth()->user()->can('bio.hosp.view_pii') ? $episode->cedula : '',
            'cedula_visible' => $episode->cedula_visible,
            'sexo' => $episode->sexo,
            'edad' => $episode->edad,
            'seguro' => $episode->seguro,
            'fecha_ingreso' => optional($episode->fecha_ingreso)->format('Y-m-d'),
            'fecha_egreso' => optional($episode->fecha_egreso)->format('Y-m-d'),
            'servicio' => $episode->servicio,
            'tipo_alta' => $episode->tipo_alta,
            'cie10' => $episode->cie10,
            'diagnostico' => $episode->diagnostico,
            'cirugia' => $episode->cirugia,
            'tipo_cirugia' => $episode->tipo_cirugia,
            'cesarea' => $episode->cesarea,
            'recien_nacido' => $episode->recien_nacido,
        ])->all();
    }
    $targetRows = max(8, count($rows) + 3);
    for ($i = count($rows); $i < $targetRows; $i++) {
        $rows[] = [];
    }
@endphp

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">grid_on</i> {{ $record->formulario->codigo }} — {{ $record->formulario->nombre }}</h4>
        <p class="card-category">
            {{ $establecimiento->nombre }}
            @if($record->corteLabel()) · {{ $record->corteLabel() }} @endif
            ·
            {{ \Carbon\Carbon::create($periodo_anio, $periodo_mes, 1)->translatedFormat('F Y') }} ·
            <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">{{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}</span>
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>No se pudo completar la acción.</strong>
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @if($record->estado === 'objetado')<div class="alert alert-warning"><strong>Observación de la objeción:</strong> {{ $record->observacion }}</div>@endif

        @include('admin.bioestadistica.captura._sp-navigator')

        @if($record->isEditable() && ($unidades ?? collect())->isNotEmpty() && ! $record->estructura_servicio_id)
            <div class="alert alert-warning">
                Este establecimiento tiene departamento y servicio asociados. Selecciónelos abajo y pulse <strong>Aplicar</strong> para que esta carga quede cortada por ese servicio.
            </div>
        @endif

        @include('admin.bioestadistica.captura._period-servicio', [
            'periodHelp' => 'Al aplicarlo se recarga la planilla del establecimiento, período y servicio. Los episodios de esta carga se mueven con el período.',
        ])

        <form
            method="POST"
            action="{{ route('bioestadistica.hospitalizacion.spreadsheet.save') }}"
            id="spreadsheet-form"
            @if($canEditRows) data-autosave-url="{{ route('bioestadistica.hospitalizacion.spreadsheet.autosave') }}" @endif
        >
            @csrf
            <input type="hidden" name="establecimiento_id" value="{{ $establecimientoId }}">
            <input type="hidden" name="periodo_anio" value="{{ $periodo_anio }}">
            <input type="hidden" name="periodo_mes" value="{{ $periodo_mes }}">
            @if($record->estructura_servicio_id)
                <input type="hidden" name="estructura_servicio_id" value="{{ $record->estructura_servicio_id }}">
            @endif

            <div class="table-responsive" style="max-height:65vh">
                <table class="table table-bordered table-sm text-nowrap" id="episodes-grid">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Cédula</th>
                            <th>Sexo</th>
                            <th>Edad</th>
                            <th>Seguro</th>
                            <th>Ingreso *</th>
                            <th>Egreso</th>
                            <th>Servicio</th>
                            <th>Tipo alta</th>
                            <th>CIE-10</th>
                            <th>Diagnóstico</th>
                            <th>Cirugía</th>
                            <th>Tipo cirugía</th>
                            <th>Cesárea</th>
                            <th>R. nacido</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $index => $row)
                        @include('admin.bioestadistica.hospitalizacion._spreadsheet-row', [
                            'index' => $index,
                            'row' => $row,
                        ])
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                @if($canEditRows)
                    <button class="btn btn-outline-info d-block mb-2" type="button" id="add-row">Agregar fila</button>
                    <button class="btn btn-primary" type="submit">Guardar borrador</button>
                    <span id="bio-autosave-status" class="text-muted small ml-2" aria-live="polite"></span>
                @endif
            </div>
        </form>

        @if($record->isEditable() && auth()->user()->can('bio.record.submit'))
            <form method="POST" action="{{ route('bioestadistica.captura.submit', $record) }}" class="mt-2 bio-confirm-form" data-confirm="Se enviará el consolidado SP10 de este establecimiento y período. ¿Enviar para aprobación?">
                @csrf
                <button class="btn btn-success" type="submit">Enviar para aprobación</button>
            </form>
        @endif

        @if($record->estado === 'enviado' && auth()->user()->can('bio.record.approve'))
            <div class="mt-3 border-top pt-3">
                <form method="POST" action="{{ route('bioestadistica.captura.approve', $record) }}" class="d-inline">@csrf<button class="btn btn-success">Aprobar</button></form>
                <form method="POST" action="{{ route('bioestadistica.captura.reject', $record) }}" class="d-inline ml-2">
                    @csrf
                    <input class="form-control d-inline-block" style="width:300px" name="observacion" placeholder="Motivo de objeción" required>
                    <button class="btn btn-danger">Objetar</button>
                </form>
            </div>
        @endif
        <a href="{{ route('bioestadistica.captura.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
    </div>
</div>

<template id="row-template">
    @include('admin.bioestadistica.hospitalizacion._spreadsheet-row', [
        'index' => '__INDEX__',
        'row' => [],
    ])
</template>

@if($canEditRows)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.querySelector('#episodes-grid tbody');
    const template = document.getElementById('row-template').innerHTML;
    const addButton = document.getElementById('add-row');
    let nextIndex = {{ count($rows) }};

    const renumber = function () {
        body.querySelectorAll('tr').forEach(function (row, position) {
            row.querySelector('[data-row-number]').textContent = position + 1;
        });
    };

    addButton.addEventListener('click', function () {
        if (body.querySelectorAll('tr').length >= 200) {
            alert('La planilla admite hasta 200 filas por guardado.');
            return;
        }
        body.insertAdjacentHTML('beforeend', template.replaceAll('__INDEX__', nextIndex++));
        renumber();
        if (typeof window.bioSp10ScheduleAutosave === 'function') {
            window.bioSp10ScheduleAutosave();
        }
    });

    renumber();

    (function () {
        const form = document.getElementById('spreadsheet-form');
        const url = form && form.getAttribute('data-autosave-url');
        if (!form || !url) {
            return;
        }

        const DEBOUNCE_MS = 3000;
        const MAX_INTERVAL_MS = 30000;
        const statusEl = document.getElementById('bio-autosave-status');
        let dirty = false;
        let saving = false;
        let debounceTimer = null;
        let maxTimer = null;
        let queued = false;

        const snapshot = function () {
            return new URLSearchParams(new FormData(form)).toString();
        };
        let lastSaved = snapshot();

        const setStatus = function (text, kind) {
            if (!statusEl) {
                return;
            }
            statusEl.textContent = text;
            statusEl.className = 'small ml-2 ' + (kind === 'error' ? 'text-danger' : (kind === 'saving' ? 'text-info' : 'text-muted'));
        };

        const payload = function () {
            return new FormData(form);
        };

        const save = function () {
            const current = snapshot();
            if (saving) {
                queued = true;
                return Promise.resolve();
            }
            if (current === lastSaved) {
                dirty = false;
                return Promise.resolve();
            }
            saving = true;
            dirty = false;
            queued = false;
            setStatus('Guardando…', 'saving');

            return fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': (form.querySelector('[name="_token"]') || {}).value || ''
                },
                body: payload(),
                credentials: 'same-origin'
            }).then(function (res) {
                return res.json().then(function (data) {
                    if (!res.ok || !data.ok) {
                        throw new Error(data.message || 'No se pudo autoguardar');
                    }
                    lastSaved = current;
                    setStatus('Autoguardado ' + (data.saved_at || ''), 'ok');
                });
            }).catch(function (err) {
                dirty = true;
                setStatus(err.message || 'Autoguardado pendiente', 'error');
            }).finally(function () {
                saving = false;
                if (maxTimer && !dirty) {
                    clearTimeout(maxTimer);
                    maxTimer = null;
                }
                if (queued || dirty) {
                    queued = false;
                    return save();
                }
            });
        };

        const schedule = function () {
            dirty = true;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(save, DEBOUNCE_MS);
            if (!maxTimer) {
                maxTimer = setTimeout(function () {
                    maxTimer = null;
                    save();
                }, MAX_INTERVAL_MS);
            }
        };

        window.bioSp10ScheduleAutosave = schedule;

        form.addEventListener('input', schedule);
        form.addEventListener('change', schedule);

        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden' && dirty) {
                save();
            }
        });
        window.addEventListener('pagehide', function () {
            if (!dirty || saving) {
                return;
            }
            if (navigator.sendBeacon) {
                navigator.sendBeacon(url, payload());
            }
        });

        document.querySelectorAll('form[action*="/enviar"]').forEach(function (submitForm) {
            submitForm.addEventListener('submit', function (event) {
                if (!dirty && !saving) {
                    return;
                }
                event.preventDefault();
                save().finally(function () {
                    submitForm.submit();
                });
            });
        });
    })();
});
</script>
@endif
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
