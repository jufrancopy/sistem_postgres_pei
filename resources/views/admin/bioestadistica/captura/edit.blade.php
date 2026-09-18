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
    .bio-item-search {
        position: sticky;
        top: 0;
        z-index: 20;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: .75rem 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
    }
    .bio-item-search__row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .5rem .75rem;
    }
    .bio-item-search__input {
        flex: 1 1 240px;
        min-width: 200px;
    }
    .bio-item-search__meta { white-space: nowrap; }
    .bio-item-search.is-filtering { border-color: #00bcd4; }
    .bio-capture-section.is-search-hidden,
    .bio-capture-field-wrap.is-search-hidden,
    .bio-tabla tbody tr.is-search-hidden { display: none !important; }

    /* Resaltar toda la fila en edición (no solo el input activo) */
    .bio-tabla tbody tr.bio-tabla-row:focus-within,
    .bio-matriz tbody tr:focus-within {
        background: #e0f7fa !important;
        box-shadow: inset 3px 0 0 #00acc1;
    }
    .bio-tabla tbody tr.bio-tabla-row:focus-within > td:first-child,
    .bio-matriz tbody tr:focus-within > th,
    .bio-matriz tbody tr:focus-within > td:first-child {
        font-weight: 700;
        color: #006064;
        background: #b2ebf2;
    }
    .bio-tabla tbody tr.bio-tabla-row:focus-within .bio-tabla-input:focus,
    .bio-matriz tbody tr:focus-within .bio-matriz-input:focus {
        background: #fff;
        border-color: #00acc1;
        box-shadow: 0 0 0 0.12rem rgba(0, 172, 193, 0.35);
    }
</style>
@php $canEditPeriod = auth()->user()->can('update', $record); @endphp
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">{{ $record->formulario->codigo }} — {{ $record->formulario->nombre }}</h4>
        <p class="card-category">
            {{ $record->establecimiento->nombre }}
            ·
            {{ \Carbon\Carbon::create($record->periodo_anio, $record->periodo_mes, 1)->translatedFormat('F Y') }}             ·
            <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">{{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}</span>
            @if($record->isImported())
                · <span class="badge badge-info" title="{{ $record->importProcedenciaLabel() }}">Importación</span>
                @if($record->importProcedenciaLabel())
                    <small class="text-white-50">({{ $record->importProcedenciaLabel() }})</small>
                @endif
            @else
                · <span class="badge badge-light text-dark">Manual</span>
            @endif
        </p>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if($record->estado === 'objetado')<div class="alert alert-warning"><strong>Observación de la objeción:</strong> {{ $record->observacion }}</div>@endif

        @include('admin.bioestadistica.captura._sp-navigator')

        @include('admin.bioestadistica.captura._period-servicio', [
            'periodHelp' => 'Al aplicarlo se recarga el formulario; esto ajusta correctamente calendarios como SP11.',
        ])

        @php
            $incluyeTercerizadoCap = (bool) ($record->establecimiento->incluye_tercerizado ?? false);
            $prestadorCap = \App\Application\Bioestadistica\Forms\PrestadorMetricMode::normalizePrestador($record->establecimiento->prestador ?? null);
            $modoCap = \App\Application\Bioestadistica\Forms\PrestadorMetricMode::mode($record->establecimiento->prestador ?? null, $incluyeTercerizadoCap);
            $modoLabel = match ($modoCap) {
                \App\Application\Bioestadistica\Forms\PrestadorMetricMode::MODE_TERCERIZADO => ($incluyeTercerizadoCap && $prestadorCap === 'IPS')
                    ? 'columnas Servicio Tercerizado + Total'
                    : 'columnas Tercerizado + Total',
                \App\Application\Bioestadistica\Forms\PrestadorMetricMode::MODE_CONVENIO => 'columnas IPS + Convenio + Total',
                default => 'solo columna Total',
            };
        @endphp
        <small class="text-muted d-block mb-2">
            Prestador del establecimiento: <strong>{{ $prestadorCap }}</strong>
            @if($incluyeTercerizadoCap && $prestadorCap === 'IPS')
                (Servicio Tercerizado)
            @endif
            · tablas con series: {{ $modoLabel }}.
        </small>

        <div class="bio-item-search" id="bio-item-search" role="search">
            <div class="bio-item-search__row">
                <label class="mb-0 font-weight-bold" for="bio-item-search-input">Buscar ítem</label>
                <input
                    type="search"
                    id="bio-item-search-input"
                    class="form-control bio-item-search__input"
                    placeholder="Filtrar por variable, tipo o prestación…"
                    autocomplete="off"
                    aria-describedby="bio-item-search-meta"
                >
                <button type="button" class="btn btn-sm btn-outline-secondary mb-0" id="bio-item-search-clear" hidden>Limpiar</button>
                <small id="bio-item-search-meta" class="text-muted bio-item-search__meta"></small>
            </div>
        </div>

        <form id="bio-captura-form" method="POST" action="{{ route('bioestadistica.captura.update', $record) }}" @if($record->isEditable() && auth()->user()->can('bio.record.update')) data-autosave-url="{{ route('bioestadistica.captura.autosave', $record) }}" @endif>
            @csrf @method('PUT')
            @foreach($record->formulario->secciones as $seccion)
                <div class="card border mb-3 bio-capture-section" data-search-text="{{ mb_strtolower($seccion->titulo.' '.$seccion->descripcion) }}">
                    <div class="card-header bg-light"><strong>{{ $seccion->titulo }}</strong><small class="text-muted ml-2">{{ $seccion->descripcion }}</small></div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($seccion->fields as $field)
                                <div
                                    class="col-md-{{ in_array($field->type, ['textarea','tabla','subtabla','matriz']) ? '12' : '6' }} bio-capture-field-wrap"
                                    data-search-text="{{ mb_strtolower($field->label.' '.$field->code.' '.($field->help_text ?? '')) }}"
                                    data-field-type="{{ $field->type }}"
                                >
                                    @include('admin.bioestadistica.captura._field')
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
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
    (function () {
        const box = document.getElementById('bio-item-search');
        const input = document.getElementById('bio-item-search-input');
        const clearBtn = document.getElementById('bio-item-search-clear');
        const meta = document.getElementById('bio-item-search-meta');
        const form = document.getElementById('bio-captura-form');
        if (!box || !input || !form) {
            return;
        }

        const normalize = function (value) {
            return String(value || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();
        };

        const applyFilter = function () {
            const query = normalize(input.value);
            const filtering = query.length > 0;
            box.classList.toggle('is-filtering', filtering);
            if (clearBtn) {
                clearBtn.hidden = !filtering;
            }

            let visibleFields = 0;
            let visibleRows = 0;
            let totalRows = 0;

            form.querySelectorAll('.bio-capture-field-wrap').forEach(function (wrap) {
                const fieldText = normalize(wrap.getAttribute('data-search-text'));
                const fieldMatch = !filtering || fieldText.indexOf(query) !== -1;
                const rows = wrap.querySelectorAll('.bio-tabla-row');
                let rowMatch = false;

                rows.forEach(function (row) {
                    totalRows += 1;
                    const rowText = normalize(row.getAttribute('data-search-text'));
                    const showRow = !filtering || fieldMatch || rowText.indexOf(query) !== -1;
                    row.classList.toggle('is-search-hidden', !showRow);
                    if (showRow) {
                        visibleRows += 1;
                        if (filtering && rowText.indexOf(query) !== -1) {
                            rowMatch = true;
                        }
                    }
                });

                const showField = !filtering || fieldMatch || rowMatch || (rows.length === 0 && fieldMatch);
                wrap.classList.toggle('is-search-hidden', !showField);
                if (showField) {
                    visibleFields += 1;
                }
            });

            form.querySelectorAll('.bio-capture-section').forEach(function (section) {
                const hasVisibleField = !!section.querySelector('.bio-capture-field-wrap:not(.is-search-hidden)');
                section.classList.toggle('is-search-hidden', filtering && !hasVisibleField);
            });

            if (!meta) {
                return;
            }
            if (!filtering) {
                meta.textContent = totalRows > 0
                    ? (visibleFields + ' variables · ' + totalRows + ' ítems')
                    : (visibleFields + ' variables');
                return;
            }
            meta.textContent = totalRows > 0
                ? (visibleFields + ' variables · ' + visibleRows + '/' + totalRows + ' ítems')
                : (visibleFields + ' variables');
        };

        let timer = null;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(applyFilter, 120);
        });
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                input.value = '';
                applyFilter();
                input.focus();
            });
        }
        applyFilter();

        (function applyLocatorFocus() {
            let term = '';
            try {
                const params = new URLSearchParams(window.location.search || '');
                term = params.get('item') || '';
            } catch (e) {}
            if (!term) {
                try {
                    term = sessionStorage.getItem('bio-sp-locator-focus') || '';
                    if (term) {
                        sessionStorage.removeItem('bio-sp-locator-focus');
                    }
                } catch (e) {}
            }
            if (!term) {
                return;
            }
            input.value = term;
            applyFilter();
            try {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } catch (e) {}
            input.focus();
        })();
    })();

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
    document.querySelectorAll('.bio-tabla[data-row-total="1"]').forEach(function (table) {
        let rowTotals = [];
        try {
            rowTotals = JSON.parse(table.getAttribute('data-row-totals') || '[]');
        } catch (e) {
            rowTotals = [];
        }
        if (!rowTotals.length) {
            const legacyCode = table.getAttribute('data-row-total-code') || 'total';
            let legacySum = [];
            try {
                legacySum = JSON.parse(table.getAttribute('data-row-total-sum') || '[]');
            } catch (e2) {
                legacySum = [];
            }
            rowTotals = [{ code: legacyCode, sum_columns: legacySum }];
        }

        table.querySelectorAll('tbody tr').forEach(function (row) {
            let suppress = false;
            let manualCodes = [];
            try {
                manualCodes = JSON.parse(row.getAttribute('data-total-manual-codes') || '[]');
            } catch (e) {
                manualCodes = [];
            }
            if (row.getAttribute('data-total-manual') === '1' && manualCodes.length === 0 && rowTotals[0]) {
                manualCodes = [rowTotals[0].code];
            }

            const isManual = function (code) {
                return manualCodes.indexOf(code) !== -1;
            };
            const setManual = function (code, on) {
                if (on) {
                    if (manualCodes.indexOf(code) === -1) {
                        manualCodes.push(code);
                    }
                } else {
                    manualCodes = manualCodes.filter(function (c) { return c !== code; });
                }
                if (manualCodes.length) {
                    row.setAttribute('data-total-manual', '1');
                    row.setAttribute('data-total-manual-codes', JSON.stringify(manualCodes));
                } else {
                    row.removeAttribute('data-total-manual');
                    row.removeAttribute('data-total-manual-codes');
                }
            };

            rowTotals.forEach(function (rt) {
                const totalCode = rt.code;
                const sumColumns = rt.sum_columns || [];
                const totalInput = row.querySelector('.bio-tabla-input[data-column="' + totalCode + '"]');
                if (!totalInput) {
                    return;
                }

                const breakdownSum = function () {
                    let sum = 0;
                    sumColumns.forEach(function (column) {
                        const input = row.querySelector('.bio-tabla-input[data-column="' + column + '"]');
                        sum += parseInt(input && input.value, 10) || 0;
                    });
                    return sum;
                };

                const setTotalValue = function (value) {
                    suppress = true;
                    totalInput.value = value;
                    totalInput.dispatchEvent(new Event('input', { bubbles: true }));
                    suppress = false;
                };

                const clearBreakdown = function () {
                    suppress = true;
                    sumColumns.forEach(function (column) {
                        const input = row.querySelector('.bio-tabla-input[data-column="' + column + '"]');
                        if (input && input.value !== '') {
                            input.value = '';
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    });
                    suppress = false;
                };

                const recalculateRowTotal = function () {
                    if (isManual(totalCode)) {
                        return;
                    }
                    const sum = breakdownSum();
                    if (sum > 0) {
                        if (totalInput.value !== String(sum)) {
                            setTotalValue(String(sum));
                        }
                        return;
                    }
                    if (totalInput.value !== '') {
                        setTotalValue('');
                    }
                };

                sumColumns.forEach(function (column) {
                    const input = row.querySelector('.bio-tabla-input[data-column="' + column + '"]');
                    if (!input) {
                        return;
                    }
                    input.addEventListener('input', function () {
                        if (suppress) {
                            return;
                        }
                        if (breakdownSum() > 0) {
                            setManual(totalCode, false);
                            recalculateRowTotal();
                        }
                    });
                });

                totalInput.addEventListener('input', function () {
                    if (suppress) {
                        return;
                    }
                    setManual(totalCode, true);
                    clearBreakdown();
                });

                if (!isManual(totalCode)) {
                    recalculateRowTotal();
                }
            });
        });
    });
    document.querySelectorAll('.bio-matriz').forEach(function (table) {
        const egresoRows = ['altas', 'traslados', 'obitos', 'abandono'];
        const editableRows = ['principio_dia', 'ingresos', 'altas', 'traslados', 'obitos', 'abandono'];
        let suppress = false;

        const dayInput = function (row, day) {
            return table.querySelector('.bio-matriz-input[data-row="' + row + '"][data-day="' + day + '"]');
        };
        const cellValue = function (row, day) {
            const input = dayInput(row, day);
            return input ? (parseInt(input.value, 10) || 0) : 0;
        };
        const setComputed = function (row, day, value) {
            const input = table.querySelector('.bio-matriz-computed[data-row="' + row + '"][data-day="' + day + '"]');
            if (!input) {
                return;
            }
            input.value = value === 0 || value === null ? '' : String(value);
        };
        const setComputedTotal = function (row, value) {
            const input = table.querySelector('.bio-matriz-computed-total[data-row="' + row + '"]');
            if (!input) {
                return;
            }
            input.value = value === 0 || value === null ? '' : String(value);
        };
        const rowTotalInput = function (row) {
            return table.querySelector('.bio-matriz-input-total[data-row="' + row + '"]');
        };
        const rowEl = function (row) {
            return table.querySelector('tr[data-row-code="' + row + '"]');
        };
        const isManual = function (row) {
            const tr = rowEl(row);
            return tr && tr.getAttribute('data-total-manual') === '1';
        };
        const setManual = function (row, on) {
            const tr = rowEl(row);
            if (!tr) {
                return;
            }
            if (on) {
                tr.setAttribute('data-total-manual', '1');
            } else {
                tr.removeAttribute('data-total-manual');
            }
        };
        const breakdownSum = function (row) {
            let sum = 0;
            table.querySelectorAll('.bio-matriz-input[data-row="' + row + '"]').forEach(function (input) {
                sum += parseInt(input.value, 10) || 0;
            });
            return sum;
        };
        const clearDays = function (row) {
            suppress = true;
            table.querySelectorAll('.bio-matriz-input[data-row="' + row + '"]').forEach(function (input) {
                if (input.value !== '') {
                    input.value = '';
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
            suppress = false;
        };
        const setRowTotalValue = function (row, value) {
            const input = rowTotalInput(row);
            if (!input) {
                return;
            }
            suppress = true;
            input.value = value;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            suppress = false;
        };
        const hasAnyDayBreakdown = function () {
            for (let i = 0; i < editableRows.length; i++) {
                if (breakdownSum(editableRows[i]) > 0) {
                    return true;
                }
            }
            return false;
        };
        const editableTotal = function (row) {
            const input = rowTotalInput(row);
            return input ? (parseInt(input.value, 10) || 0) : 0;
        };

        const recalculateComputed = function () {
            const days = parseInt(table.getAttribute('data-days'), 10) || 31;

            if (!hasAnyDayBreakdown()) {
                // Solo totales del mes.
                for (let day = 1; day <= days; day++) {
                    setComputed('total_egresos', day, null);
                    setComputed('total_pacientes_dia', day, null);
                }
                let egresos = 0;
                egresoRows.forEach(function (row) {
                    egresos += editableTotal(row);
                });
                const pacientes = editableTotal('principio_dia') + editableTotal('ingresos') - egresos;
                setComputedTotal('total_egresos', egresos);
                setComputedTotal('total_pacientes_dia', pacientes);
                return;
            }

            let sumEgresos = 0;
            let sumPacientes = 0;
            for (let day = 1; day <= days; day++) {
                let egresos = 0;
                egresoRows.forEach(function (row) {
                    egresos += cellValue(row, day);
                });
                const pacientes = cellValue('principio_dia', day) + cellValue('ingresos', day) - egresos;
                setComputed('total_egresos', day, egresos);
                setComputed('total_pacientes_dia', day, pacientes);
                sumEgresos += egresos;
                sumPacientes += pacientes;
            }
            setComputedTotal('total_egresos', sumEgresos);
            setComputedTotal('total_pacientes_dia', sumPacientes);
        };

        editableRows.forEach(function (row) {
            const totalInput = rowTotalInput(row);
            if (!totalInput) {
                return;
            }

            const recalculateRowTotal = function () {
                if (isManual(row)) {
                    return;
                }
                const sum = breakdownSum(row);
                if (sum > 0) {
                    if (totalInput.value !== String(sum)) {
                        setRowTotalValue(row, String(sum));
                    }
                    return;
                }
                if (totalInput.value !== '') {
                    setRowTotalValue(row, '');
                }
            };

            table.querySelectorAll('.bio-matriz-input[data-row="' + row + '"]').forEach(function (input) {
                input.addEventListener('input', function () {
                    if (suppress) {
                        return;
                    }
                    if (breakdownSum(row) > 0) {
                        setManual(row, false);
                        recalculateRowTotal();
                    }
                    recalculateComputed();
                });
            });

            totalInput.addEventListener('input', function () {
                if (suppress) {
                    return;
                }
                setManual(row, true);
                clearDays(row);
                recalculateComputed();
            });

            if (!isManual(row)) {
                recalculateRowTotal();
            }
        });

        recalculateComputed();
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
