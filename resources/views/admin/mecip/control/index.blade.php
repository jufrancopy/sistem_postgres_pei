@extends('layouts.master')

@section('title', 'Control de Cambios & Sincronización MECIP (IPS)')

@section('content')
<div class="content-wrapper p-3 p-md-4 bg-light">
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title text-white font-weight-bold mb-0">
                <i class="fas fa-network-wired mr-2"></i> Control de Cambios & Sincronización MECIP (IPS)
            </h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Control de Cambios MECIP</li>
            </ol>
        </nav>

        {{-- Tarjetas de Estadísticas --}}
        @php
            $totalCasos = $casos->count();
            $pendientesLider = $casos->where('estado_flujo', 'remitido_lider')->count();
            $resueltosLider = $casos->where('estado_flujo', 'resuelto_lider')->count();
            $cerradosAdmin = $casos->where('estado_flujo', 'cerrado_admin')->count();
        @endphp

        <div class="row px-3 mb-3">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #94a3b8 !important;">Total Expedientes</span>
                            <h2 class="font-weight-bold mb-0 text-warning" style="font-size: 1.8rem; color: #fbbf24 !important;">{{ number_format($totalCasos) }}</h2>
                        </div>
                        <i class="fas fa-folder-open fa-2x" style="color: #64748b !important;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #b45309 0%, #92400e 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #fde047 !important;">En Revisión Líder MECIP</span>
                            <h2 class="font-weight-bold mb-0 text-white" style="font-size: 1.8rem; color: #ffffff !important;">{{ number_format($pendientesLider) }}</h2>
                        </div>
                        <i class="fas fa-user-clock fa-2x" style="color: #facc15 !important;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #7dd3fc !important;">Resueltos con Justificación</span>
                            <h2 class="font-weight-bold mb-0 text-white" style="font-size: 1.8rem; color: #ffffff !important;">{{ number_format($resueltosLider) }}</h2>
                        </div>
                        <i class="fas fa-check-double fa-2x" style="color: #38bdf8 !important;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="p-3 shadow-sm rounded-3 text-white" style="border-radius: 12px !important; background: linear-gradient(135deg, #15803d 0%, #166534 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="small font-weight-bold text-uppercase d-block" style="color: #86efac !important;">Aprobados y Cerrados</span>
                            <h2 class="font-weight-bold mb-0 text-white" style="font-size: 1.8rem; color: #ffffff !important;">{{ number_format($cerradosAdmin) }}</h2>
                        </div>
                        <i class="fas fa-file-signature fa-2x" style="color: #4ade80 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row px-3">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between py-3 border-bottom" style="gap: 12px;">
                        <form action="{{ route('admin.mecip.control.index') }}" method="GET" class="form-inline flex-wrap" style="gap: 12px;">
                            <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                                <label class="font-weight-bold mr-2 mb-0 text-dark small"><i class="fa fa-filter text-info mr-1"></i> Estado:</label>
                                <select name="estado" id="selectEstadoFiltro" class="form-control form-control-sm select2" style="width: 200px;" onchange="this.form.submit()">
                                    <option value="TODOS" {{ $estado === 'TODOS' ? 'selected' : '' }}>-- Todos los Estados --</option>
                                    <option value="borrador" {{ $estado === 'borrador' ? 'selected' : '' }}>Borrador / Carga Inicial</option>
                                    <option value="remitido_lider" {{ $estado === 'remitido_lider' ? 'selected' : '' }}>Remitido a Líder MECIP</option>
                                    <option value="resuelto_lider" {{ $estado === 'resuelto_lider' ? 'selected' : '' }}>Resuelto con Justificación</option>
                                    <option value="cerrado_admin" {{ $estado === 'cerrado_admin' ? 'selected' : '' }}>Aprobado y Cerrado</option>
                                </select>
                            </div>
                        </form>

                        <div class="d-flex flex-wrap" style="gap: 10px;">
                            <button type="button" class="btn btn-outline-info font-weight-bold" data-toggle="modal" data-target="#modalImportarJsonMecip">
                                <i class="fas fa-file-import mr-1"></i> Importar Caso JSON (IPS)
                            </button>
                            <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalNuevoCasoMecip">
                                <i class="fas fa-plus-circle mr-1"></i> Capturar Nuevo Caso IPS
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover data-table text-dark" id="tblCasosMecip" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 130px;" class="text-center">Nº Caso / Código</th>
                                        <th>Macroproceso & Subproceso</th>
                                        <th style="width: 70px;" class="text-center">Versión</th>
                                        <th style="width: 170px;">Líder MECIP</th>
                                        <th style="width: 160px;" class="text-center">Estado Flujo</th>
                                        <th style="width: 120px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($casos as $c)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-dark font-mono font-weight-bold px-2 py-1 mb-1 d-block" style="font-family: monospace; font-size: 0.82rem;">
                                                    Caso #{{ $c->numero_caso }}
                                                </span>
                                                <span class="badge badge-light border text-primary font-weight-bold px-2 py-0.5" style="font-size: 0.72rem;">
                                                    {{ $c->codigo_subproceso }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <strong class="text-dark d-block" style="font-size: 0.92rem; line-height: 1.3;">{{ $c->subproceso }}</strong>
                                                <small class="text-muted d-block" style="font-size: 0.78rem;">
                                                    <i class="fas fa-sitemap text-info mr-1"></i> {{ $c->macroproceso }} &bull; {{ $c->proceso }}
                                                </small>
                                            </td>
                                            <td class="align-middle text-center font-weight-bold text-dark">
                                                v{{ $c->version }}
                                            </td>
                                            <td class="align-middle">
                                                @if($c->liderMecip)
                                                    <div class="d-flex align-items-center" style="gap: 8px;">
                                                        <div class="rounded-circle bg-info text-white font-weight-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                            {{ strtoupper(substr($c->liderMecip->name, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <strong class="text-dark d-block small" style="line-height: 1.2;">{{ $c->liderMecip->name }}</strong>
                                                            <small class="text-muted" style="font-size: 0.7rem;">Líder Asignado</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted font-italic small"><i class="fas fa-exclamation-circle text-warning mr-1"></i> Sin Líder Asignado</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge {{ $c->estado_badge }} font-weight-bold px-2.5 py-1" style="font-size: 0.78rem;">
                                                    {{ $c->estado_label }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('admin.mecip.control.show', $c->id) }}" class="btn btn-sm btn-info font-weight-bold px-3 shadow-sm" style="border-radius: 8px;" title="Ver Expediente y Diagrama de Nodos">
                                                    <i class="fas fa-eye mr-1"></i> Expediente
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Capturar Nuevo Caso MECIP --}}
<div class="modal fade" id="modalNuevoCasoMecip" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.mecip.control.store') }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-primary text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fas fa-plus-circle mr-2"></i> Capturar Nuevo Caso MECIP IPS
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small"><i class="fas fa-hashtag text-info mr-1"></i> Nº de Caso IPS (*)</label>
                        <input type="text" name="numero_caso" class="form-control form-control-sm font-weight-bold text-dark" placeholder="Ej. 3782431" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small"><i class="fas fa-code text-info mr-1"></i> Código de Subproceso (*)</label>
                        <input type="text" name="codigo_subproceso" class="form-control form-control-sm font-weight-bold text-dark" placeholder="Ej. GES_002_01" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold text-dark small">Macroproceso (*)</label>
                        <input type="text" name="macroproceso" class="form-control form-control-sm text-dark" placeholder="Ej. GESTIÓN ESTRATÉGICA E INSTITUCIONAL" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold text-dark small">Proceso (*)</label>
                        <input type="text" name="proceso" class="form-control form-control-sm text-dark" placeholder="Ej. Gestión de Calidad y Procesos" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold text-dark small">Subproceso (*)</label>
                        <input type="text" name="subproceso" class="form-control form-control-sm text-dark" placeholder="Ej. Modelado de Procedimientos" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small">Versión (*)</label>
                        <input type="text" name="version" class="form-control form-control-sm text-dark" value="1.0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-dark small">Fecha Elaboración</label>
                        <input type="date" name="fecha_elaboracion" class="form-control form-control-sm text-dark" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="font-weight-bold text-dark small"><i class="fas fa-user-shield text-info mr-1"></i> Asignar Líder MECIP (* Select2)</label>
                        <select name="lider_mecip_id" id="selectLiderMecipModal" class="form-control select2" style="width: 100%;">
                            <option value="">-- Asignar Líder MECIP para revisión --</option>
                            @foreach($lideresMecip as $lider)
                                <option value="{{ $lider->id }}">{{ $lider->name }} ({{ $lider->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-save mr-1"></i> Registrar Caso MECIP
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Importar JSON Estructurado --}}
<div class="modal fade" id="modalImportarJsonMecip" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formImportarJsonMecip" class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
            @csrf
            <div class="modal-header bg-info text-white p-3" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fas fa-file-import mr-2"></i> Importación Rápida Estructurada JSON (IPS)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-2">
                    Pegá aquí la estructura copiada o parseada del expediente IPS para replicar automáticamente las actividades, tareas, insumos y productos en el sistema.
                </p>
                <textarea name="json_data" id="json_data_input" class="form-control font-mono" rows="10" placeholder='{
  "numero_caso": "3782431",
  "codigo_subproceso": "GES_002_01",
  "macroproceso": "GESTIÓN INSTITUCIONAL",
  "proceso": "Gestión de Procesos",
  "subproceso": "Modelado de Procedimientos",
  "actividades": [
    { "codigo": "ACT_01", "nombre": "Recepción del caso en bandeja", "responsable": "Analista IPS", "tareas": [ { "descripcion": "Validar metadatos", "tiempo_minutos": 15 } ] }
  ]
}' style="font-family: monospace; font-size: 0.82rem; background: #0f172a; color: #38bdf8;" required></textarea>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="ejecutarImportacionJson()" class="btn btn-info font-weight-bold px-4 shadow-sm" id="btnEjecutarImportacion">
                    <i class="fas fa-magic mr-1"></i> Replicar Expediente
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2 en todos los selectores
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        dropdownParent: $('#modalNuevoCasoMecip').length ? $('#modalNuevoCasoMecip') : null
    });

    // Inicializar DataTables
    if ($.fn.DataTable) {
        $('#tblCasosMecip').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            order: [],
            language: {
                search: "Buscar caso/subproceso:",
                searchPlaceholder: "Filtrar por Nº de caso o subproceso...",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ casos MECIP",
                infoEmpty: "No hay registros",
                infoFiltered: "(filtrado de _MAX_ casos totales)",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    }

    // Escuchar evento Redis de notificaciones en tiempo real
    if (window.Echo) {
        window.Echo.channel('mecip-notificaciones')
            .listen('.mecip.notificacion', (e) => {
                toastr.info(e.mensaje, '🚀 Notificación MECIP IPS (Redis)');
                setTimeout(() => location.reload(), 2000);
            });
    }
});

function ejecutarImportacionJson() {
    const btn = document.getElementById('btnEjecutarImportacion');
    const jsonVal = document.getElementById('json_data_input').value;

    if (!jsonVal.trim()) {
        toastr.warning('Por favor pegá una estructura JSON válida.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Replicando...';

    $.ajax({
        url: "{{ route('admin.mecip.control.importarJson') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            json_data: jsonVal
        },
        success: function(res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Replicar Expediente';
            if (res.success) {
                toastr.success(res.message);
                window.location.href = res.redirect;
            } else {
                toastr.error(res.message);
            }
        },
        error: function(err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Replicar Expediente';
            toastr.error('Error al importar el JSON: ' + (err.responseJSON?.message || 'Error del servidor'));
        }
    });
}
</script>
@endpush
@endsection
