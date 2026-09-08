@extends('layouts.master')
@section('title', 'Gestión de Eventos Institucionales & Hitos')

@section('content')
<div class="card">
    {{-- Header Estándar de la Plataforma --}}
    <div class="card-header card-header-info d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h4 class="card-title font-weight-bold">Módulo de Gestión de Eventos Institucionales & Hitos</h4>
            <p class="card-category">Planificación por Fases, Asignación de Responsables, Checklist Operativo y Calendario</p>
        </div>
        <div>
            <a href="{{ route('eventos.calendario') }}" class="btn btn-warning btn-sm font-weight-bold mr-2">
                <i class="fa fa-calendar-alt mr-1"></i> Calendario Integral
            </a>
            <a href="{{ route('eventos.create') }}" class="btn btn-success btn-sm font-weight-bold">
                <i class="fa fa-plus mr-1"></i> + Nuevo Evento
            </a>
        </div>
    </div>

    {{-- Breadcrumbs Estándar --}}
    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4 mx-3 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Eventos Institucionales</li>
        </ol>
    </nav>

    <div class="card-body px-3">

        {{-- Tarjetas KPI --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #6366f1 !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Total Eventos</div>
                            <h2 class="font-weight-bold text-dark mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiTotalEventos }}</h2>
                            <small class="text-primary font-weight-bold"><i class="fa fa-calendar-day mr-1"></i>{{ $kpiEventosEnCurso }} en curso</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#e0e7ff; width:54px; height:54px;">
                            <i class="fa fa-calendar-alt fa-2x text-primary" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #0ea5e9 !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Avance Global</div>
                            <h2 class="font-weight-bold text-dark mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiAvanceGlobal }}%</h2>
                            <small class="text-info font-weight-bold"><i class="fa fa-tasks mr-1"></i>Checklist de Tareas</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#e0f2fe; width:54px; height:54px;">
                            <i class="fa fa-chart-line fa-2x text-info" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #f59e0b !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Tareas Pendientes</div>
                            <h2 class="font-weight-bold text-dark mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiTareasPend }}</h2>
                            <small class="text-warning font-weight-bold"><i class="fa fa-clock mr-1"></i>En ejecución activa</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#fef3c7; width:54px; height:54px;">
                            <i class="fa fa-hourglass-half fa-2x text-warning" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border-left: 5px solid #ef4444 !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size:0.72rem; letter-spacing:0.05em;">Tareas en Alerta / Vencidas</div>
                            <h2 class="font-weight-bold text-danger mb-0 mt-1" style="font-size:1.8rem;">{{ $kpiTareasVencidas }}</h2>
                            <small class="text-danger font-weight-bold"><i class="fa fa-exclamation-triangle mr-1"></i>Requieren atención</small>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background:#fee2e2; width:54px; height:54px;">
                            <i class="fa fa-bell fa-2x text-danger" style="font-size:1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros y Tabla Principal --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                    {{-- Barra de Filtros --}}
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="font-weight-bold text-muted small text-uppercase mb-1">Filtrar por Plan PEI</label>
                                <select id="filtroPei" class="form-control select2" style="width:100%;">
                                    <option value="">-- Todos los Planes PEI --</option>
                                    @foreach($peiPerfiles as $pei)
                                        <option value="{{ $pei->id }}" {{ $selectedPeiId == $pei->id ? 'selected' : '' }}>
                                            {{ strip_tags($pei->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="font-weight-bold text-muted small text-uppercase mb-1">Filtrar por Estado</label>
                                <select id="filtroEstado" class="form-control select2" style="width:100%;">
                                    <option value="">-- Todos los Estados --</option>
                                    <option value="planificado">Planificado</option>
                                    <option value="en_curso">En Curso</option>
                                    <option value="completado">Completado</option>
                                    <option value="en_alerta">En Alerta</option>
                                    <option value="pospuesto">Pospuesto</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="font-weight-bold text-muted small text-uppercase mb-1">Filtrar por Tipo</label>
                                <select id="filtroTipo" class="form-control select2" style="width:100%;">
                                    <option value="">-- Todos los Tipos --</option>
                                    <option value="Taller">Taller</option>
                                    <option value="Jornada">Jornada</option>
                                    <option value="Reunión">Reunión de Alto Nivel</option>
                                    <option value="Congreso">Congreso / Simposio</option>
                                    <option value="Socialización">Socialización PEI</option>
                                    <option value="Evaluación">Evaluación de Avance</option>
                                    <option value="Capacitación">Capacitación</option>
                                </select>
                            </div>
                            <div class="col-md-2 text-md-right mt-3 mt-md-0">
                                <button type="button" class="btn btn-light btn-sm font-weight-bold text-secondary" id="btnLimpiarFiltros" style="border-radius:8px;">
                                    <i class="fa fa-undo mr-1"></i> Resetear
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla DataTables --}}
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100" id="tablaEventos">
                                <thead style="background:#f8fafc; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 28%;">Evento / Sede</th>
                                        <th style="width: 20%;">Plan PEI Vinculado</th>
                                        <th style="width: 14%;">Período</th>
                                        <th style="width: 15%;">Avance Tareas</th>
                                        <th style="width: 10%;">Estado</th>
                                        <th style="width: 8%;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear / Editar Evento --}}
<div class="modal fade" id="modalEventoForm" tabindex="-1" aria-hidden="true" style="z-index: 1050;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                <div>
                    <span class="badge badge-light text-dark font-weight-bold mb-1" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">
                        <i class="fa fa-calendar-check text-primary mr-1"></i> GESTIÓN INSTITUCIONAL
                    </span>
                    <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalEventoTitulo" style="font-size: 1.15rem;">
                        Nuevo Evento Institucional
                    </h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.9;"><span>&times;</span></button>
            </div>
            <form id="formEvento">
                <input type="hidden" id="eventoId" name="evento_id" value="">
                <div class="modal-body p-4" style="background: #f8fafc; max-height: 75vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eventoNombre" name="nombre" placeholder="Ej: Taller de Socialización del Plan Estratégico IPS 2026-2028" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Tipo de Evento</label>
                            <select class="form-control select2" id="eventoTipo" name="tipo" style="width:100%;">
                                <option value="Taller">Taller</option>
                                <option value="Jornada">Jornada</option>
                                <option value="Reunión">Reunión de Alto Nivel</option>
                                <option value="Congreso">Congreso / Simposio</option>
                                <option value="Socialización">Socialización PEI</option>
                                <option value="Evaluación">Evaluación de Avance</option>
                                <option value="Capacitación">Capacitación</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Lugar / Sede</label>
                            <input type="text" class="form-control" id="eventoLugarSede" name="lugar_sede" placeholder="Ej: Centro de Eventos Ykua Satí / Auditorio Central">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Plan PEI Vinculado</label>
                            <select class="form-control select2" id="eventoPeiId" name="pei_profile_id" style="width:100%;">
                                <option value="">-- Seleccionar Plan PEI (Opcional) --</option>
                                @foreach($peiPerfiles as $pei)
                                    <option value="{{ $pei->id }}">{{ strip_tags($pei->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="eventoFechaInicio" name="fecha_inicio">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Fecha de Finalización</label>
                            <input type="date" class="form-control" id="eventoFechaFin" name="fecha_fin">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Estado</label>
                            <select class="form-control select2" id="eventoEstado" name="estado" style="width:100%;">
                                <option value="planificado">Planificado</option>
                                <option value="en_curso">En Curso</option>
                                <option value="completado">Completado</option>
                                <option value="en_alerta">En Alerta</option>
                                <option value="pospuesto">Pospuesto</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Equipo / Responsables del Evento</label>
                            <select class="form-control select2" id="eventoResponsables" name="responsables[]" multiple="multiple" style="width:100%;">
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark mb-1">Color Distintivo</label>
                            <input type="color" class="form-control" id="eventoColor" name="color" value="#6366f1" style="height:38px; padding:2px;">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-1">Descripción / Objetivos del Evento</label>
                        <textarea class="form-control" id="eventoDescripcion" name="descripcion" rows="3" placeholder="Detalla los objetivos, público meta y alcance..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-4 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold" id="btnGuardarEvento" style="border-radius: 8px;">
                        <i class="fa fa-save mr-1"></i> Guardar Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('.select2').select2();

    var tablaEventos = $('#tablaEventos').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("eventos.index") }}',
            data: function(d) {
                d.pei_profile_id = $('#filtroPei').val();
                d.estado = $('#filtroEstado').val();
                d.tipo = $('#filtroTipo').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold text-muted' },
            { data: 'nombre_html', name: 'nombre' },
            { data: 'pei_profile', name: 'peiProfile.name' },
            { data: 'fechas', name: 'fecha_inicio' },
            { data: 'avance_html', name: 'porcentaje_avance', orderable: false },
            { data: 'estado_html', name: 'estado', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: window.dtSpanishEs || {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ eventos",
            infoEmpty: "Sin eventos registrados",
            infoFiltered: "(filtrado de _MAX_ eventos)",
            zeroRecords: "No se encontraron eventos",
            paginate: { first: "Primero", previous: "Anterior", next: "Siguiente", last: "Último" }
        },
        pageLength: 10,
        order: [[3, 'desc']]
    });

    $('#filtroPei, #filtroEstado, #filtroTipo').on('change', function() {
        tablaEventos.ajax.reload();
    });

    $('#btnLimpiarFiltros').on('click', function() {
        $('#filtroPei').val('').trigger('change');
        $('#filtroEstado').val('').trigger('change');
        $('#filtroTipo').val('').trigger('change');
        tablaEventos.ajax.reload();
    });

    // Abrir modal para Crear
    $('#btnCrearEventoModal').on('click', function() {
        $('#formEvento')[0].reset();
        $('#eventoId').val('');
        $('#eventoPeiId').val($('#filtroPei').val() || '').trigger('change');
        $('#eventoTipo').val('Taller').trigger('change');
        $('#eventoEstado').val('planificado').trigger('change');
        $('#eventoResponsables').val([]).trigger('change');
        $('#eventoColor').val('#6366f1');
        $('#modalEventoTitulo').html('<i class="fa fa-plus-circle text-white mr-1"></i> Nuevo Evento Institucional');
        $('#modalEventoForm').modal('show');
    });

    // Abrir modal para Editar
    $(document).on('click', '.btnEditarEvento', function() {
        var id = $(this).data('id');
        $.get('/admin/eventos/' + id + '/edit', function(data) {
            $('#eventoId').val(data.id);
            $('#eventoNombre').val(data.nombre);
            $('#eventoTipo').val(data.tipo).trigger('change');
            $('#eventoLugarSede').val(data.lugar_sede);
            $('#eventoPeiId').val(data.pei_profile_id).trigger('change');
            $('#eventoFechaInicio').val(data.fecha_inicio ? data.fecha_inicio.substring(0, 10) : '');
            $('#eventoFechaFin').val(data.fecha_fin ? data.fecha_fin.substring(0, 10) : '');
            $('#eventoEstado').val(data.estado).trigger('change');
            $('#eventoColor').val(data.color || '#6366f1');
            $('#eventoDescripcion').val(data.descripcion);

            var respIds = data.responsables ? data.responsables.map(function(r) { return r.id; }) : [];
            $('#eventoResponsables').val(respIds).trigger('change');

            $('#modalEventoTitulo').html('<i class="fa fa-edit text-white mr-1"></i> Editar Evento');
            $('#modalEventoForm').modal('show');
        });
    });

    // Guardar Evento (Crear o Actualizar)
    $('#formEvento').on('submit', function(e) {
        e.preventDefault();
        var id = $('#eventoId').val();
        var url = id ? '/admin/eventos/' + id : '{{ route("eventos.store") }}';
        var method = id ? 'PUT' : 'POST';

        var formData = $(this).serialize();
        if (id) formData += '&_method=PUT';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(resp) {
                $('#modalEventoForm').modal('hide');
                tablaEventos.ajax.reload();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: resp.message,
                    showConfirmButton: false,
                    timer: 2500
                });
                if (!id && resp.redirect) {
                    setTimeout(function() {
                        window.location.href = resp.redirect;
                    }, 800);
                }
            },
            error: function(xhr) {
                var msg = 'Ocurrió un error al guardar el evento.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Atención', msg, 'warning');
            }
        });
    });

    // Eliminar Evento
    $(document).on('click', '.btnEliminarEvento', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');

        Swal.fire({
            title: '¿Eliminar Evento?',
            text: 'Se eliminará "' + nombre + '" junto con sus fases y checklist asociado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-trash mr-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/eventos/' + id,
                    type: 'DELETE',
                    success: function(resp) {
                        tablaEventos.ajax.reload();
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: resp.message,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar el evento.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
