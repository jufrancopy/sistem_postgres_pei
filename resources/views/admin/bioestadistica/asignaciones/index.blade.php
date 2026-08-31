@extends('layouts.master')
@section('title', 'Bioestadística — Asignaciones de captura')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Asignaciones']], 'showConfigTabs' => true])
<div class="card bio-siplan">
    <div class="card-header card-header-info d-flex justify-content-between align-items-start flex-wrap">
        <div>
            <h4 class="card-title"><i class="material-icons">assignment_ind</i> Asignaciones de captura</h4>
            <p class="card-category mb-0">Formulario SP × establecimiento × digitador. Sin filas aquí, el digitador usa solo <code>usuario_establecimientos</code> (todos los SP activos).</p>
        </div>
        <button type="button" class="btn btn-info btn-sm mt-2 mt-md-0" data-toggle="modal" data-target="#modalNuevaAsignacion">
            <i class="material-icons">add</i> Nueva asignación
        </button>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif

        <form method="GET" class="bio-filters mb-3">
            <div class="form-row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Digitador</label>
                    <select class="form-control bio-select2" name="user_id" data-placeholder="Todos">
                        <option value="">Todos</option>
                        @foreach($digitadores as $digitador)
                            <option value="{{ $digitador->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $digitador->id)>{{ $digitador->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Formulario</label>
                    <select class="form-control bio-select2" name="formulario_id" data-placeholder="Todos">
                        <option value="">Todos</option>
                        <option value="-1" @selected((string) ($filters['formulario_id'] ?? '') === '-1')>Todos los formularios (wildcard)</option>
                        @foreach($formularios as $formulario)
                            <option value="{{ $formulario->id }}" @selected((string) ($filters['formulario_id'] ?? '') === (string) $formulario->id)>{{ $formulario->codigo }} — {{ $formulario->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Establecimiento</label>
                    <select class="form-control bio-select2" name="establecimiento_id" data-placeholder="Todos">
                        <option value="">Todos</option>
                        @foreach($establecimientos as $establecimiento)
                            <option value="{{ $establecimiento->id }}" @selected((string) ($filters['establecimiento_id'] ?? '') === (string) $establecimiento->id)>{{ $establecimiento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Estado</label>
                    <select class="form-control" name="activo">
                        <option value="">Todos</option>
                        <option value="1" @selected(($filters['activo'] ?? '') === '1')>Activas</option>
                        <option value="0" @selected(($filters['activo'] ?? '') === '0')>Inactivas</option>
                    </select>
                </div>
                <div class="col-md-1 mb-2">
                    <button class="btn btn-info btn-sm btn-block">Filtrar</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm table-hover bio-data-table">
                <thead>
                    <tr>
                        <th>Digitador</th>
                        <th>Formulario</th>
                        <th>Establecimiento</th>
                        <th>Estado</th>
                        <th>Actualizado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $asignacion)
                        <tr>
                            <td>
                                <strong>{{ $asignacion->user?->name }}</strong>
                                <div class="small text-muted">{{ $asignacion->user?->email }}</div>
                            </td>
                            <td>
                                @if($asignacion->formulario_id === null)
                                    <span class="badge badge-info">Todos los formularios</span>
                                @else
                                    {{ $asignacion->formulario?->codigo }} — {{ $asignacion->formulario?->nombre }}
                                @endif
                            </td>
                            <td>{{ $asignacion->establecimiento?->nombre }}</td>
                            <td>
                                @if($asignacion->activo)
                                    <span class="badge badge-success">Activa</span>
                                @else
                                    <span class="badge badge-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $asignacion->updated_at?->format('d/m/Y H:i') }}</td>
                            <td class="bio-actions">
                                <button type="button" class="btn btn-outline-info btn-sm btn-edit-asignacion"
                                    data-id="{{ $asignacion->id }}"
                                    data-user-id="{{ $asignacion->user_id }}"
                                    data-formulario-id="{{ $asignacion->formulario_id ?? '' }}"
                                    data-establecimiento-id="{{ $asignacion->establecimiento_id }}"
                                    data-activo="{{ $asignacion->activo ? '1' : '0' }}"
                                ><i class="material-icons">edit</i></button>
                                <form method="POST" action="{{ route('bioestadistica.asignaciones.destroy', $asignacion) }}" class="d-inline bio-confirm-form" data-confirm="¿Eliminar esta asignación?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="material-icons">delete</i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted text-center py-4">Sin asignaciones granulares. Los digitadores siguen usando solo establecimientos (modo legacy).</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $asignaciones->links() }}

        <hr class="my-4">
        <h5 class="font-weight-bold mb-2">Asignación rápida por establecimiento (modo legacy)</h5>
        <p class="text-muted small">Si un digitador <strong>no</strong> tiene filas granulares arriba, puede capturar <strong>todos los formularios activos</strong> en los establecimientos listados aquí.</p>
        @if($digitadores->isEmpty())
            <div class="alert alert-warning">No hay usuarios con el rol Digitador Bioestadística.</div>
        @endif
        @foreach($digitadores as $digitador)
            <form method="POST" action="{{ route('bioestadistica.captura.assignments.update', $digitador) }}" class="card border mb-3">
                @csrf @method('PUT')
                <div class="card-header bg-light"><strong>{{ $digitador->name }}</strong> <small class="text-muted">{{ $digitador->email }}</small></div>
                <div class="card-body">
                    <select class="form-control bio-select2" name="establecimiento_ids[]" multiple data-placeholder="Establecimientos">
                        @foreach($establecimientos as $establecimiento)
                            <option value="{{ $establecimiento->id }}" @selected(in_array($establecimiento->id, $legacyAssignments[$digitador->id] ?? []))>
                                {{ $establecimiento->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-success btn-sm mt-2" type="submit">Guardar establecimientos</button>
                </div>
            </form>
        @endforeach
    </div>
</div>

<div class="modal fade" id="modalNuevaAsignacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('bioestadistica.asignaciones.store') }}" class="modal-content" id="formAsignacion">
            @csrf
            <input type="hidden" name="_method" value="POST" id="asignacionMethod">
            <input type="hidden" name="asignacion_id" value="" id="asignacionId">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAsignacionTitle">Nueva asignación</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Digitador</label>
                    <select class="form-control bio-select2" name="user_id" required>
                        <option value="">Seleccione…</option>
                        @foreach($digitadores as $digitador)
                            <option value="{{ $digitador->id }}">{{ $digitador->name }} ({{ $digitador->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Formulario</label>
                    <select class="form-control bio-select2" name="formulario_id" data-placeholder="Todos los formularios activos">
                        <option value="">Todos los formularios activos</option>
                        @foreach($formularios as $formulario)
                            <option value="{{ $formulario->id }}">{{ $formulario->codigo }} — {{ $formulario->nombre }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Deje vacío para permitir todos los SP activos en el establecimiento.</small>
                </div>
                <div class="form-group">
                    <label>Establecimiento</label>
                    <select class="form-control bio-select2" name="establecimiento_id" required>
                        <option value="">Seleccione…</option>
                        @foreach($establecimientos as $establecimiento)
                            <option value="{{ $establecimiento->id }}">{{ $establecimiento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-0" id="activoGroup" style="display:none;">
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" class="custom-control-input" id="asignacionActivo" name="activo" value="1" checked>
                        <label class="custom-control-label" for="asignacionActivo">Asignación activa</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-info">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-edit-asignacion').forEach(function (button) {
    button.addEventListener('click', function () {
        var form = document.getElementById('formAsignacion');
        var id = button.dataset.id;
        form.action = '{{ url('bioestadistica/asignaciones') }}/' + id;
        document.getElementById('asignacionMethod').value = 'PUT';
        document.getElementById('modalAsignacionTitle').textContent = 'Editar asignación';
        document.getElementById('activoGroup').style.display = '';
        form.querySelector('[name="user_id"]').value = button.dataset.userId;
        form.querySelector('[name="formulario_id"]').value = button.dataset.formularioId;
        form.querySelector('[name="establecimiento_id"]').value = button.dataset.establecimientoId;
        document.getElementById('asignacionActivo').checked = button.dataset.activo === '1';
        if (window.jQuery) {
            jQuery(form).find('.bio-select2').trigger('change');
        }
        jQuery('#modalNuevaAsignacion').modal('show');
    });
});
jQuery('#modalNuevaAsignacion').on('hidden.bs.modal', function () {
    var form = document.getElementById('formAsignacion');
    form.action = '{{ route('bioestadistica.asignaciones.store') }}';
    document.getElementById('asignacionMethod').value = 'POST';
    document.getElementById('modalAsignacionTitle').textContent = 'Nueva asignación';
    document.getElementById('activoGroup').style.display = 'none';
    form.reset();
});
</script>
@endpush
