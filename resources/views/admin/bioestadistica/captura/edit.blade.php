@extends('layouts.master')
@section('title', "Captura {$record->formulario->codigo}")

@section('content')
<style>
    .bio-capture-field { position: relative; }
    .bio-capture-field__head { position: relative; z-index: 1; }
    .bio-capture-field__label {
        position: static !important;
        display: block;
        float: none !important;
        transform: none !important;
        top: auto !important;
        left: auto !important;
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: #3c4858;
        line-height: 1.3;
        pointer-events: auto !important;
    }
    .bio-capture-field__help {
        position: static !important;
        display: block;
        line-height: 1.35;
    }
    .bio-capture-field--block { width: 100%; }
</style>
@php $canEditPeriod = auth()->user()->can('update', $record); @endphp
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $record->formulario->codigo }} — {{ $record->formulario->nombre }}</h4>
        <p class="card-category">
            {{ $record->establecimiento->nombre }}
            @if($record->corteLabel()) · {{ $record->corteLabel() }} @endif
            ·
            {{ \Carbon\Carbon::create($record->periodo_anio, $record->periodo_mes, 1)->translatedFormat('F Y') }} ·
            <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">{{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}</span>
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if($record->estado === 'objetado')<div class="alert alert-warning"><strong>Observación de la objeción:</strong> {{ $record->observacion }}</div>@endif

        @include('admin.bioestadistica.captura._sp-navigator')

        @if($record->isEditable() && ($unidades ?? collect())->isNotEmpty() && ! $record->estructura_servicio_id)
            <div class="alert alert-warning">
                Este establecimiento tiene departamento y servicio asociados. Selecciónelos abajo y pulse <strong>Aplicar</strong> para que esta carga quede cortada por ese servicio.
            </div>
        @endif

        @include('admin.bioestadistica.captura._period-servicio', [
            'periodHelp' => 'Al aplicarlo se recarga el formulario; esto ajusta correctamente calendarios como SP11.',
        ])

        <form id="bio-captura-form" method="POST" action="{{ route('bioestadistica.captura.update', $record) }}" @if($record->isEditable() && auth()->user()->can('bio.record.update')) data-autosave-url="{{ route('bioestadistica.captura.autosave', $record) }}" @endif>
            @csrf @method('PUT')
            @foreach($record->formulario->secciones as $seccion)
                <div class="card border mb-3">
                    <div class="card-header bg-light"><strong>{{ $seccion->titulo }}</strong><small class="text-muted ml-2">{{ $seccion->descripcion }}</small></div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($seccion->fields as $field)
                                <div class="col-md-{{ in_array($field->type, ['textarea','tabla','subtabla','matriz']) ? '12' : '6' }}">
                                    @include('admin.bioestadistica.captura._field')
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="form-group"><label>Observación del digitador</label><textarea class="form-control" name="observacion" rows="2" @disabled(!$record->isEditable())>{{ old('observacion', $record->estado === 'objetado' ? '' : $record->observacion) }}</textarea></div>
            @if($record->isEditable() && auth()->user()->can('bio.record.update'))
                <button class="btn btn-primary">Guardar borrador</button>
                <span id="bio-autosave-status" class="text-muted small ml-2" aria-live="polite"></span>
            @endif
        </form>

        @if($record->isEditable() && auth()->user()->can('bio.record.submit'))
            <form method="POST" action="{{ route('bioestadistica.captura.submit', $record) }}" class="mt-2 bio-confirm-form" data-confirm="Se validará el último borrador guardado. ¿Enviar para aprobación?">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bio-tabla[data-totals="1"]').forEach(function (table) {
        const recalculate = function () {
            table.querySelectorAll('[data-total]').forEach(function (cell) {
                const column = cell.getAttribute('data-total');
                let total = 0;
                table.querySelectorAll('.bio-tabla-input[data-column="' + column + '"]').forEach(function (input) {
                    total += parseFloat(input.value) || 0;
                });
                cell.textContent = total.toLocaleString('es-PY');
            });
        };
        table.addEventListener('input', recalculate);
        recalculate();
    });
    document.querySelectorAll('.bio-matriz').forEach(function (table) {
        const recalculate = function () {
            table.querySelectorAll('[data-row-total]').forEach(function (cell) {
                const row = cell.getAttribute('data-row-total');
                let total = 0;
                table.querySelectorAll('.bio-matriz-input[data-row="' + row + '"]').forEach(function (input) {
                    total += parseInt(input.value, 10) || 0;
                });
                cell.textContent = total.toLocaleString('es-PY');
            });
        };
        table.addEventListener('input', recalculate);
        recalculate();
    });

    (function () {
        const form = document.getElementById('bio-captura-form');
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
            const body = new FormData(form);
            body.delete('_method');
            return body;
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
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
