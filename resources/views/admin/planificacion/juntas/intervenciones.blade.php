@extends('layouts.master')

@section('title', 'Bandeja de Intervención de Juntas - Alertas en Rojo')

@section('content')
<div class="content-wrapper p-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h3 class="mb-1 text-danger font-weight-bold">
                <i class="fas fa-exclamation-triangle mr-2"></i> Bandeja de Intervención (Consejo de Sabios)
            </h3>
            <p class="text-muted mb-0 small">
                Gestión de Alertas en Rojo remitidas a las Juntas Consultivas para la emisión de Dictámenes de Mitigación y Mejora Continua.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.juntas.index') }}" class="btn btn-outline-primary font-weight-bold shadow-sm">
                <i class="fas fa-cog mr-1"></i> Configurar Juntas & Firmas
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filtros de Estado -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body py-3">
            <form action="{{ route('admin.juntas.intervenciones') }}" method="GET" class="form-inline justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="font-weight-bold mr-3 text-dark"><i class="fas fa-filter mr-1"></i> Filtrar por Estado:</span>
                    <a href="{{ route('admin.juntas.intervenciones', ['estado' => 'TODOS']) }}" class="btn btn-sm mr-2 {{ $estado === 'TODOS' ? 'btn-primary font-weight-bold' : 'btn-outline-secondary bg-white' }}">Todos</a>
                    <a href="{{ route('admin.juntas.intervenciones', ['estado' => 'PENDIENTE']) }}" class="btn btn-sm mr-2 {{ $estado === 'PENDIENTE' ? 'btn-danger font-weight-bold' : 'btn-outline-secondary bg-white' }}">
                        <i class="fas fa-clock mr-1"></i> Pendientes
                    </a>
                    <a href="{{ route('admin.juntas.intervenciones', ['estado' => 'EMITIDO']) }}" class="btn btn-sm mr-2 {{ $estado === 'EMITIDO' ? 'btn-success font-weight-bold' : 'btn-outline-secondary bg-white' }}">
                        <i class="fas fa-check-double mr-1"></i> Emitidos con Firma
                    </a>
                </div>

                <div>
                    <select name="junta_id" class="form-control form-control-sm bg-white border" onchange="this.form.submit()">
                        <option value="">-- Todas las Juntas --</option>
                        @foreach($juntas as $j)
                            <option value="{{ $j->id }}" {{ $juntaId == $j->id ? 'selected' : '' }}>{{ $j->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Intervenciones -->
    <div class="row">
        @forelse($intervenciones as $item)
            @php
                $badgeEstado = 'badge-danger';
                if ($item->estado === 'EMITIDO') $badgeEstado = 'badge-success';
                elseif ($item->estado === 'EN_APLICACION') $badgeEstado = 'badge-info';
            @endphp
            <div class="col-md-12 mb-3">
                <div class="card shadow-sm border-left-danger border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge badge-dark font-weight-bold mr-2">{{ $item->codigo_expediente }}</span>
                                <span class="badge {{ $badgeEstado }} text-uppercase font-weight-bold px-2 py-1">{{ $item->estado }}</span>
                                <span class="badge badge-warning text-dark ml-2 font-weight-bold"><i class="fas fa-fire mr-1"></i> {{ $item->prioridad }}</span>
                            </div>
                            <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i> Remitido el {{ $item->created_at->format('d/m/Y H:i') }}</small>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <h5 class="font-weight-bold text-primary mb-1">
                                    <i class="fas fa-bullseye text-danger mr-1"></i> {{ $item->accion->name ?? 'Acción Estratégica' }}
                                </h5>
                                <p class="small text-muted mb-2">
                                    <strong>Junta Asignada:</strong> {{ $item->junta->nombre ?? 'Junta Consultiva' }} 
                                    | <strong>Presidente:</strong> {{ $item->junta->presidente_nombre ?? 'N/A' }}
                                </p>

                                <div class="bg-light p-2 rounded border mb-2 small">
                                    <strong class="text-dark"><i class="fas fa-align-left mr-1"></i> Diagnóstico de Remisión:</strong>
                                    <div>{{ $item->diagnostico ?: 'Desviación en el cumplimiento de indicadores en Rojo.' }}</div>
                                </div>

                                @if($item->reporte)
                                    <div class="small text-muted">
                                        <i class="fas fa-user-edit mr-1"></i> Reportado por {{ $item->reporte->usuario->name ?? 'Técnico' }} 
                                        el {{ $item->reporte->created_at->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-5 border-left pl-3">
                                @if($item->estado === 'EMITIDO' && $item->recomendaciones_mitigacion)
                                    <div class="p-2 bg-success-light rounded border border-success mb-2">
                                        <small class="font-weight-bold text-success d-block mb-1">
                                            <i class="fas fa-signature mr-1"></i> Dictamen de Mitigación Emitido:
                                        </small>
                                        <ul class="pl-3 mb-1 small text-dark">
                                            @foreach($item->recomendaciones_mitigacion as $mit)
                                                <li>{{ $mit }}</li>
                                            @endforeach
                                        </ul>
                                        <div class="text-right">
                                            <small class="text-muted font-italic">
                                                Firmado por {{ $item->junta->presidente_nombre }} ({{ $item->firma_estampada_at ? $item->firma_estampada_at->format('d/m/Y H:i') : 'Firmado' }})
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-3 bg-light rounded text-center mb-2 border">
                                        <i class="fas fa-clock text-warning fa-2x mb-2 d-block"></i>
                                        <span class="small font-weight-bold text-muted">Pendiente de Dictamen del Consejo de Sabios</span>
                                    </div>
                                @endif

                                <div class="text-right mt-3">
                                    <button class="btn btn-sm btn-primary font-weight-bold shadow-sm" onclick='abrirModalEmitirDictamen(@json($item))'>
                                        <i class="fas fa-scroll mr-1"></i> {{ $item->estado === 'EMITIDO' ? 'Ver / Editar Dictamen' : 'Emitir Dictamen & Estampar Firma' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h4 class="font-weight-bold text-dark">¡No hay alertas pendientes en esta bandeja!</h4>
                        <p class="text-muted mb-0">No se han registrado reportes en rojo o todos los expedientes han sido atendidos por las Juntas.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-3">
        {{ $intervenciones->links() }}
    </div>
</div>

<!-- Modal para Emitir Dictamen con Firma Hológrafa Registrada -->
<div class="modal fade" id="modalEmitirDictamen" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formEmitirDictamen" method="POST">
            @csrf
            <input type="hidden" id="dictamen_intervencion_id">

            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-scroll mr-2"></i> Emitir Dictamen de Mitigación — <span id="modalExpedienteCode"></span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- IA Helper Header -->
                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border mb-3">
                        <div>
                            <strong class="text-primary"><i class="fas fa-robot mr-1"></i> Asistente de IA (Consejo de Sabios Llama 3.3)</strong>
                            <div class="small text-muted">¿Querés que la IA redacte automáticamente el diagnóstico y 3 plan de contingencias?</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-purple font-weight-bold shadow-sm" id="btnIaMitigacion" onclick="generarMitigacionConIa()">
                            <i class="fas fa-magic mr-1"></i> Redactar con IA
                        </button>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Diagnóstico Técnico del Consejo <span class="text-danger">*</span></label>
                        <textarea name="diagnostico" id="dictamen_diagnostico" class="form-control" rows="3" required placeholder="Escriba el diagnóstico de la causa raíz de la desviación..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-success">
                            <i class="fas fa-list-ol mr-1"></i> Acciones Operativas de Mitigación Sugeridas (Se inyectarán al PEI) <span class="text-danger">*</span>
                        </label>
                        <div id="contenedorMitigaciones">
                            <!-- Filas dinámicas -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success mt-2 font-weight-bold" onclick="agregarFilaMitigacion('')">
                            <i class="fas fa-plus mr-1"></i> Agregar otra Acción de Mitigación
                        </button>
                    </div>

                    <hr>

                    <!-- Preview del Sello y Firma del Presidente -->
                    <div class="p-3 border rounded bg-white text-center shadow-sm position-relative">
                        <div class="small text-muted font-weight-bold uppercase mb-2">Sello Institucional & Firma Registrada del Presidente</div>
                        
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <div class="mr-3">
                                <img id="dictamen_firma_img" src="" alt="Firma Presidente" style="max-height: 60px;" class="d-none">
                                <div id="dictamen_no_firma" class="text-muted small font-italic d-none">[Firma Hológrafa Registrada de la Junta]</div>
                            </div>
                            <div class="text-left border-left pl-3">
                                <div class="font-weight-bold text-dark" id="dictamen_presidente_nombre">Dr. Carlos Gustavo Benítez</div>
                                <div class="small text-muted" id="dictamen_presidente_cargo">Presidente de la Junta Consultiva</div>
                                <div class="small text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Firma Hológrafa Registrada Acreditada</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success font-weight-bold shadow">
                        <i class="fas fa-signature mr-1"></i> Validar & Emitir Dictamen Firmado
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentIntervencionId = null;

function abrirModalEmitirDictamen(item) {
    currentIntervencionId = item.id;
    document.getElementById('dictamen_intervencion_id').value = item.id;
    document.getElementById('modalExpedienteCode').innerText = item.codigo_expediente;
    document.getElementById('dictamen_diagnostico').value = item.diagnostico || '';

    const form = document.getElementById('formEmitirDictamen');
    form.action = "{{ url('/admin/planificacion/juntas/dictamen') }}/" + item.id;

    // Cargar info de Presidente y Firma
    if (item.junta) {
        document.getElementById('dictamen_presidente_nombre').innerText = item.junta.presidente_nombre || 'Presidente de Junta';
        document.getElementById('dictamen_presidente_cargo').innerText = item.junta.presidente_cargo || 'Presidente';
        
        if (item.junta.firma_digital_url) {
            document.getElementById('dictamen_firma_img').src = item.junta.firma_digital_url;
            document.getElementById('dictamen_firma_img').classList.remove('d-none');
            document.getElementById('dictamen_no_firma').classList.add('d-none');
        } else {
            document.getElementById('dictamen_firma_img').classList.add('d-none');
            document.getElementById('dictamen_no_firma').classList.remove('d-none');
        }
    }

    // Cargar Filas de Mitigación
    const container = document.getElementById('contenedorMitigaciones');
    container.innerHTML = '';

    if (item.recomendaciones_mitigacion && Array.isArray(item.recomendaciones_mitigacion) && item.recomendaciones_mitigacion.length > 0) {
        item.recomendaciones_mitigacion.forEach(mit => agregarFilaMitigacion(mit));
    } else {
        agregarFilaMitigacion('Reasignar presupuesto crítico para agilizar la compra de insumos.');
        agregarFilaMitigacion('Establecer mesa técnica semanal de seguimiento operativo.');
    }

    $('#modalEmitirDictamen').modal('show');
}

function agregarFilaMitigacion(val = '') {
    const container = document.getElementById('contenedorMitigaciones');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-success font-weight-bold"><i class="fas fa-check-square"></i></span>
        </div>
        <input type="text" name="recomendaciones_mitigacion[]" class="form-control" value="${val}" required placeholder="Ej: Implementar plan de contingencia operativo...">
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash-alt"></i></button>
        </div>
    `;
    container.appendChild(div);
}

function generarMitigacionConIa() {
    if (!currentIntervencionId) return;

    const btn = document.getElementById('btnIaMitigacion');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Pensando...';

    $.ajax({
        url: "{{ route('admin.juntas.sugerirMitigacionIa') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            intervencion_id: currentIntervencionId
        },
        success: function(res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Redactar con IA';

            if (res.success) {
                if (res.diagnostico) {
                    document.getElementById('dictamen_diagnostico').value = res.diagnostico;
                }

                if (res.mitigaciones && Array.isArray(res.mitigaciones)) {
                    const container = document.getElementById('contenedorMitigaciones');
                    container.innerHTML = '';
                    res.mitigaciones.forEach(m => agregarFilaMitigacion(m));
                }
                toastr.success('¡Diagnóstico y mitigación redactados por la IA!');
            }
        },
        error: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Redactar con IA';
            toastr.error('Ocurrió un error al consultar la IA.');
        }
    });
}
</script>
@endsection
