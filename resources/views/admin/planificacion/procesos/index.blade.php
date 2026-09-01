@extends('layouts.master')
@section('title', 'Mapeo de Circuitos de Atención y Flujogramas')

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-title font-weight-bold mb-0">
                <i class="fas fa-project-diagram mr-2"></i> Mapeo de Circuitos de Atención y Flujo del Paciente
            </h4>
            <p class="card-category text-white-50 mb-0">Herramienta de Diagnóstico Operativo, Cuellos de Botella e Insumo para Organización y Calidad (DOC)</p>
        </div>
        <div>
            <a href="{{ route('pei.procesos.portalDoc') }}" class="btn btn-outline-light btn-sm mr-2 shadow-sm">
                <i class="fas fa-microscope mr-1"></i> Portal Investigadores DOC
            </a>
            <button class="btn btn-warning btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalNuevoRelevamiento">
                <i class="fas fa-plus-circle mr-1"></i> Nuevo Relevamiento
            </button>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item active" aria-current="page">Relevamiento de Procesos y Circuitos</li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped w-100" id="tablaProcesos">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Nombre del Circuito</th>
                        <th>Servicio / Establecimiento</th>
                        <th>Acción PEI Vinculada</th>
                        <th>Responsables de Visita</th>
                        <th>Lead Time</th>
                        <th>Eficiencia</th>
                        <th>Cuellos</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Relevamiento -->
<div class="modal fade" id="modalNuevoRelevamiento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formNuevoRelevamiento" action="{{ route('pei.procesos.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-clipboard-list mr-2"></i> Alta de Relevamiento Técnico del Servicio</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nombre del Circuito / Estudio <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Circuito de Recepción, Agendamiento e Internación - HZ Luque" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Servicio / Establecimiento (Organigrama)</label>
                            <select name="organigrama_id" class="form-control select2-modal" style="width: 100%;">
                                <option value="">-- Seleccionar Servicio / Área --</option>
                                @foreach($organigramas as $org)
                                    <option value="{{ $org->id }}">{{ $org->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Acción del PEI Vinculada</label>
                            <select name="pei_profile_id" class="form-control select2-modal" style="width: 100%;">
                                <option value="">-- Seleccionar Meta/Acción PEI --</option>
                                @foreach($peiProfiles as $pei)
                                    <option value="{{ $pei->id }}">[{{ strtoupper($pei->level) }}] {{ Str::limit($pei->name, 60) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Contexto / Móvil de la Visita de Relevamiento</label>
                        <textarea name="contexto_motivo" class="form-control" rows="2" placeholder="Especificar la Instrucción del Consejo de Administración, Ordenanza o Resolución que motiva el relevamiento..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold text-dark">Responsables / Equipo Relevador de la Visita</label>
                            <select name="responsables[]" class="form-control select2-modal" multiple="multiple" style="width: 100%;">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Seleccionar analistas de Planificación y miembros de Organización y Calidad participantes.</small>
                        </div>
                        <div class="col-md-5 form-group">
                            <label class="font-weight-bold text-dark">Fecha de Visita / Relevamiento</label>
                            <input type="date" name="fecha_relevamiento" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info font-weight-bold"><i class="fas fa-save mr-1"></i> Iniciar Relevamiento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2-modal').select2({
        dropdownParent: $('#modalNuevoRelevamiento')
    });

    var table = $('#tablaProcesos').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pei.procesos.index') }}",
        columns: [
            { data: 'nombre', name: 'nombre', render: function(d, t, r) { return '<strong class="text-primary">'+d+'</strong>'; } },
            { data: 'servicio_label', name: 'servicio_label' },
            { data: 'pei_label', name: 'pei_label' },
            { data: 'responsables_badges', name: 'responsables_badges' },
            { data: 'lead_time_formatted', name: 'lead_time_formatted' },
            { data: 'eficiencia_badge', name: 'eficiencia_badge', className: 'text-center' },
            { data: 'cuellos_count', name: 'cuellos_count', className: 'text-center' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });

    $('#formNuevoRelevamiento').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                $('#modalNuevoRelevamiento').modal('hide');
                toastr.success(res.message);
                if (res.redirect) {
                    window.location.href = res.redirect;
                } else {
                    table.ajax.reload();
                }
            },
            error: function(err) {
                toastr.error('Error al guardar el relevamiento.');
            }
        });
    });
});
</script>
@endpush
