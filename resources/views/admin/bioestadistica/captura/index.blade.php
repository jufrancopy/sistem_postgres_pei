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
                    @if($establecimientos->isEmpty())
                        <button type="button" class="btn btn-info btn-sm" disabled title="No tiene establecimientos asignados">
                            <i class="material-icons">add</i> Nueva carga
                        </button>
                    @else
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-nueva-carga">
                            <i class="material-icons">add</i> Nueva carga
                        </button>
                    @endif
                    <a href="{{ route('bioestadistica.captura.import.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="material-icons">upload_file</i> Importar
                    </a>
                @endcan
                <a href="{{ route('bioestadistica.captura.pending') }}" class="btn btn-outline-warning btn-sm">Períodos pendientes</a>
            </div>
            @can('bio.assignment.manage')
                <a href="{{ route('bioestadistica.asignaciones.index') }}" class="btn btn-outline-info btn-sm">Asignar digitadores</a>
            @endcan
        </div>

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
                    <input class="form-control" name="periodo_anio" type="number" min="1990" max="2100" value="{{ $periodo_anio }}">
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
                                        <th>Departamento</th>
                                        <th>Servicio</th>
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
                                        <td>{{ $record->estructuraDepartamento?->nombre ?? '—' }}</td>
                                        <td>{{ $record->estructuraServicio?->nombre ?? '—' }}</td>
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
    @if($establecimientos->isNotEmpty())
        @include('admin.bioestadistica.captura._modal-nueva-carga')
    @endif
@endcan
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@can('bio.record.create')
@if($establecimientos->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('modal-nueva-carga');
    var form = document.getElementById('form-nueva-carga');
    var establishmentSelect = document.getElementById('captura-establecimiento');
    var corteGroup = document.getElementById('captura-corte-group');
    var corteSelect = document.getElementById('captura-corte');
    var errorsBox = document.getElementById('nueva-carga-errors');
    var errorsList = document.getElementById('nueva-carga-errors-list');
    var submitBtn = document.getElementById('btn-crear-carga');
    var cancelBtn = modal ? modal.querySelector('[data-dismiss="modal"]') : null;
    var selectedCorte = '{{ old('estructura_servicio_id') }}';
    var cortesUrl = '{{ route('bioestadistica.estructura.cortes') }}';
    var shouldOpen = @json($openNuevaCargaModal ?? false);

    function clearErrors() {
        errorsBox.classList.add('d-none');
        errorsList.innerHTML = '';
    }

    function showErrors(errors) {
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
        submitBtn.querySelector('.btn-label').classList.toggle('d-none', state !== 'idle');
        submitBtn.querySelector('.btn-spinner').classList.toggle('d-none', state !== 'creating');
        submitBtn.querySelector('.btn-redirect').classList.toggle('d-none', state !== 'redirecting');
    }

    function initModalSelect2() {
        if (!window.jQuery || !window.jQuery.fn.select2 || !modal) {
            return;
        }
        window.jQuery(modal).find('.bio-select2-modal').each(function () {
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

    function loadCortes() {
        if (!establishmentSelect || !corteSelect) {
            return;
        }
        var establishmentId = establishmentSelect.value;
        corteSelect.innerHTML = '<option value="">Cargando...</option>';
        corteSelect.required = false;
        if (!establishmentId) {
            corteGroup.style.display = 'none';
            corteSelect.innerHTML = '<option value="">Seleccione establecimiento</option>';
            return;
        }
        fetch(cortesUrl + '?establecimiento_id=' + encodeURIComponent(establishmentId), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) { return response.json(); })
            .then(function (payload) {
                var cortes = payload.data || [];
                if (cortes.length === 0) {
                    corteGroup.style.display = 'none';
                    corteSelect.innerHTML = '<option value="">Sin corte</option>';
                    corteSelect.required = false;
                    return;
                }
                corteGroup.style.display = '';
                corteSelect.innerHTML = cortes.length > 1 ? '<option value="">Seleccione</option>' : '';
                cortes.forEach(function (corte) {
                    var option = document.createElement('option');
                    option.value = corte.servicio_id;
                    option.textContent = corte.etiqueta;
                    option.selected = String(corte.servicio_id) === String(selectedCorte) || cortes.length === 1;
                    corteSelect.appendChild(option);
                });
                corteSelect.required = true;
            });
    }

    if (establishmentSelect) {
        if (window.jQuery) {
            window.jQuery(establishmentSelect).on('change', loadCortes);
        } else {
            establishmentSelect.addEventListener('change', loadCortes);
        }
    }

    if (window.jQuery && modal) {
        window.jQuery(modal).on('shown.bs.modal', function () {
            setButtonState('idle');
            initModalSelect2();
            loadCortes();
        });
        if (shouldOpen) {
            window.jQuery(modal).modal('show');
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
@endif
@endcan
@endsection
