@extends('layouts.master')
@section('title', 'Bioestadística — Carga de datos')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Carga de datos']]])
<style>
    .bio-captura-accordion .card-header {
        cursor: pointer;
        user-select: none;
    }
    .bio-captura-accordion .card-header .bio-captura-chevron {
        transition: transform .2s ease;
        font-size: 22px;
        line-height: 1;
        color: #64748b;
    }
    .bio-captura-accordion .card-header[aria-expanded="true"] .bio-captura-chevron {
        transform: rotate(180deg);
    }
</style>
<div class="card bio-siplan">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="material-icons">edit_note</i> Carga de datos</h4>
        <p class="card-category">Período estadístico independiente de la fecha de digitación</p>
    </div>
    <div class="card-body">
        @foreach(['success', 'warning'] as $message)
            @if(session($message)) <div class="alert alert-{{ $message === 'success' ? 'success' : 'warning' }}">{{ session($message) }}</div> @endif
        @endforeach

        <div class="d-flex justify-content-between align-items-center bio-toolbar mb-3">
            <div class="d-flex bio-toolbar">
                @can('bio.record.create')
                    <button
                        type="button"
                        class="btn btn-info btn-sm"
                        id="btn-abrir-nueva-carga"
                        @if($establecimientos->isEmpty())
                            data-bio-sin-establecimientos="1"
                            title="No tiene establecimientos asignados para capturar"
                        @endif
                    >
                        <i class="material-icons" style="pointer-events:none">add</i> Nueva carga
                    </button>
                    <a href="{{ route('bioestadistica.captura.import.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="material-icons" style="pointer-events:none">upload_file</i> Importar
                    </a>
                @endcan
                <a href="{{ route('bioestadistica.captura.pending') }}" class="btn btn-outline-warning btn-sm">Períodos pendientes</a>
            </div>
            @can('bio.assignment.manage')
                <a href="{{ route('bioestadistica.asignaciones.index') }}" class="btn btn-outline-info btn-sm">Asignar digitadores</a>
            @endcan
        </div>

        @can('bio.record.create')
            @if($establecimientos->isEmpty())
                <div class="alert alert-warning">
                    No hay establecimientos cargados en el sistema. Configure la geografía antes de iniciar una captura.
                </div>
            @endif
        @endcan

        <form method="GET" class="bio-filters">
            <div class="form-row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Establecimiento</label>
                    <select class="form-control bio-select2" name="establecimiento_id" data-placeholder="Todos los establecimientos" data-allow-clear="1">
                        <option value="">Todos los establecimientos</option>
                        @foreach($establecimientos as $establecimiento)
                            <option value="{{ $establecimiento->id }}" @selected(request('establecimiento_id') == $establecimiento->id)>{{ $establecimiento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Formulario</label>
                    <select class="form-control bio-select2" name="formulario_id" data-placeholder="Todos los formularios" data-allow-clear="1">
                        <option value="">Todos los formularios</option>
                        @foreach($formularios as $formulario)
                            <option value="{{ $formulario->id }}" @selected(request('formulario_id') == $formulario->id)>{{ $formulario->codigo }} — {{ $formulario->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Año</label>
                    @include('admin.bioestadistica._periodo-anio-select', [
                        'name' => 'periodo_anio',
                        'value' => $periodo_anio,
                        'required' => true,
                    ])
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Mes</label>
                    <select class="form-control bio-select2" name="periodo_mes" data-placeholder="Mes">
                        @foreach($months as $number => $month)
                            <option value="{{ $number }}" @selected((int) $periodo_mes === (int) $number)>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Estado</label>
                    <select class="form-control bio-select2" name="estado" data-placeholder="Todos los estados" data-allow-clear="1">
                        <option value="">Todos los estados</option>
                        @foreach(['borrador','enviado','aprobado','objetado'] as $state)
                            <option value="{{ $state }}" @selected(request('estado') === $state)>{{ ucfirst($state) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-2">
                    <button class="btn btn-primary btn-sm" type="submit">Aplicar filtro</button>
                </div>
            </div>
        </form>

        @forelse($groups as $index => $group)
            @php
                $collapseId = 'captura-est-'.$group['establecimiento']->id.'-'.$index;
                $est = $group['establecimiento'];
            @endphp
            <div class="card border mb-2 bio-captura-accordion">
                <div
                    class="card-header bg-light d-flex justify-content-between align-items-center"
                    data-toggle="collapse"
                    data-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    aria-controls="{{ $collapseId }}"
                >
                    <div>
                        <strong>{{ $est->nombre }}</strong>
                        <span class="badge badge-info ml-2">{{ $group['count'] }} {{ $group['count'] === 1 ? 'carga' : 'cargas' }}</span>
                        <div>
                            <small class="text-muted">
                                {{ $est->distrito?->departamento?->nombre }} / {{ $est->distrito?->nombre }}
                            </small>
                        </div>
                    </div>
                    <i class="material-icons bio-captura-chevron">expand_more</i>
                </div>
                <div id="{{ $collapseId }}" class="collapse">
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Formulario</th>
                                        <th>Dependencia</th>
                                        <th>Período del dato</th>
                                        <th>Origen</th>
                                        <th>Estado</th>
                                        <th style="width:100px" class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($group['records'] as $record)
                                    <tr>
                                        <td>{{ $record->formulario->codigo ?? '' }} — {{ $record->formulario->nombre ?? '' }}</td>
                                        <td>{{ $record->corteLabel() ?: '—' }}</td>
                                        <td>{{ ($months[$record->periodo_mes] ?? $record->periodo_mes) }}/{{ $record->periodo_anio }}</td>
                                        <td>@include('admin.bioestadistica.captura._origen_carga', ['record' => $record])</td>
                                        <td>
                                            <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($record->estado) }}">
                                                {{ \App\Models\Bioestadistica\Record::estadoLabel($record->estado) }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            @can('bio.record.view')
                                                <a class="btn btn-outline-primary btn-sm" href="{{ route('bioestadistica.captura.edit', $record) }}">
                                                    {{ $record->isEditable() ? 'Editar' : 'Ver' }}
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-light border mb-0">
                No hay cargas para {{ $months[$periodo_mes] ?? $periodo_mes }}/{{ $periodo_anio }} con los filtros seleccionados.
            </div>
        @endforelse
    </div>
</div>

@can('bio.record.create')
    @include('admin.bioestadistica.captura._modal-nueva-carga')
@endcan
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@can('bio.record.create')
@include('admin.bioestadistica.captura._organo-corte-select-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('modal-nueva-carga');
    var openBtn = document.getElementById('btn-abrir-nueva-carga');
    var form = document.getElementById('form-nueva-carga');
    var establishmentSelect = document.getElementById('captura-establecimiento');
    var organoSelect = document.getElementById('captura-organo');
    var errorsBox = document.getElementById('nueva-carga-errors');
    var errorsList = document.getElementById('nueva-carga-errors-list');
    var submitBtn = document.getElementById('btn-crear-carga');
    var cancelBtn = modal ? modal.querySelector('[data-dismiss="modal"]') : null;
    var shouldOpen = @json($openNuevaCargaModal ?? false);
    var cortesUrl = @json($cortesUrl ?? route('bioestadistica.captura.cortes'));
    var preferredOrganoId = @json((string) ($selectedOrganoId ?? ''));
    var sinEstablecimientos = @json($establecimientos->isEmpty());

    if (modal && window.jQuery && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    function clearErrors() {
        if (!errorsBox || !errorsList) {
            return;
        }
        errorsBox.classList.add('d-none');
        errorsList.innerHTML = '';
    }

    function showErrors(errors) {
        if (!errorsBox || !errorsList) {
            return;
        }
        errorsList.innerHTML = '';
        Object.keys(errors).forEach(function (key) {
            (errors[key] || []).forEach(function (message) {
                var li = document.createElement('li');
                li.textContent = message;
                errorsList.appendChild(li);
            });
        });
        errorsBox.classList.remove('d-none');
    }

    function setButtonState(state) {
        if (!submitBtn) {
            return;
        }
        submitBtn.disabled = state !== 'idle';
        if (cancelBtn) {
            cancelBtn.disabled = state !== 'idle';
        }
        var label = submitBtn.querySelector('.btn-label');
        var spinner = submitBtn.querySelector('.btn-spinner');
        var redirect = submitBtn.querySelector('.btn-redirect');
        if (label) label.classList.toggle('d-none', state !== 'idle');
        if (spinner) spinner.classList.toggle('d-none', state !== 'creating');
        if (redirect) redirect.classList.toggle('d-none', state !== 'redirecting');
    }

    function initModalSelect2() {
        if (!window.jQuery || !window.jQuery.fn.select2 || !modal) {
            return;
        }
        window.jQuery(modal).find('.bio-select2-modal').each(function () {
            if (this.id === 'captura-organo') {
                return;
            }
            var $el = window.jQuery(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            $el.select2({
                width: '100%',
                dropdownParent: window.jQuery(modal),
                placeholder: $el.data('placeholder') || 'Seleccione',
                allowClear: !!$el.data('allow-clear')
            });
        });
    }

    function loadCortes(establecimientoId, preferredId) {
        if (!window.BioOrganoCorteSelect) {
            return;
        }
        window.BioOrganoCorteSelect.load(organoSelect, cortesUrl, establecimientoId || '', preferredId || '', modal);
    }

    function openNuevaCargaModal() {
        if (sinEstablecimientos || (openBtn && openBtn.getAttribute('data-bio-sin-establecimientos') === '1')) {
            var msg = 'No hay establecimientos cargados en el sistema. Configure la geografía antes de iniciar una captura.';
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'warning', title: 'Sin establecimientos', text: msg });
            } else {
                window.alert(msg);
            }
            return;
        }
        if (!modal || !window.jQuery || !window.jQuery.fn.modal) {
            return;
        }
        window.jQuery(modal).modal('show');
    }

    if (openBtn) {
        openBtn.addEventListener('click', function (event) {
            event.preventDefault();
            openNuevaCargaModal();
        });
    }

    if (establishmentSelect) {
        establishmentSelect.addEventListener('change', function () {
            loadCortes(establishmentSelect.value, '');
        });
        if (window.jQuery) {
            window.jQuery(establishmentSelect).on('select2:select select2:clear', function () {
                loadCortes(establishmentSelect.value, '');
            });
        }
    }

    if (window.jQuery && modal) {
        window.jQuery(modal).on('shown.bs.modal', function () {
            setButtonState('idle');
            initModalSelect2();
            loadCortes(establishmentSelect ? establishmentSelect.value : '', preferredOrganoId);
        });
        if (shouldOpen) {
            openNuevaCargaModal();
        }
    }

    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            clearErrors();
            setButtonState('creating');
            var redirecting = false;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, status: response.status, data: data };
                    });
                })
                .then(function (result) {
                    if (result.ok && result.data.redirect) {
                        redirecting = true;
                        setButtonState('redirecting');
                        window.location.href = result.data.redirect;
                        return;
                    }
                    if (result.status === 422 && result.data.errors) {
                        showErrors(result.data.errors);
                        return;
                    }
                    var message = (result.data && result.data.message) || 'No se pudo crear la carga.';
                    showErrors({ general: [message] });
                })
                .catch(function () {
                    showErrors({ general: ['Error de conexión. Intente nuevamente.'] });
                })
                .finally(function () {
                    if (!redirecting) {
                        setButtonState('idle');
                    }
                });
        });
    }
});
</script>
@endcan
@endsection
