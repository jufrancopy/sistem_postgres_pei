@extends('layouts.master')

@section('title', 'Bandeja de Intervención de Juntas - Alertas en Rojo')

@section('content')
<div class="content-wrapper p-3 p-md-4 bg-light">
    <!-- Header Banner Principal -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3" style="gap: 15px;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1" style="border-radius: 6px; font-size: 0.72rem;">
                            <i class="fas fa-shield-alt mr-1"></i> GESTIÓN DE ALERTAS Y GOBERNANZA
                        </span>
                        <span class="text-white-50 small">SIPLAN GO — IPS</span>
                    </div>
                    <h3 class="font-weight-bold text-white mb-1" style="font-size: 1.45rem;">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i> Bandeja de Intervención (Consejo de Sabios)
                    </h3>
                    <p class="text-white-50 mb-0 small" style="max-width: 750px; line-height: 1.45;">
                        Gestión de Alertas en Rojo remitidas a las Juntas Consultivas para la emisión de Dictámenes de Mitigación, Evaluación de Impacto y Mejora Continua.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.juntas.index') }}" class="btn btn-warning text-dark font-weight-bold rounded-pill px-4 py-2 shadow-sm" style="font-size: 0.85rem;">
                        <i class="fas fa-cog mr-1"></i> Configurar Juntas & Firmas
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #f0fdf4; color: #166534; border-left: 5px solid #22c55e !important;">
            <i class="fas fa-check-circle mr-2 text-success"></i> {{ session('success') }}
            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Barra de Filtros por Estado y Junta -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
        <div class="card-body py-3 px-4">
            <form action="{{ route('admin.juntas.intervenciones') }}" method="GET" class="form-inline justify-content-between flex-wrap" style="gap: 12px;">
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                    <span class="font-weight-bold mr-2 text-dark small"><i class="fas fa-filter text-primary mr-1"></i> Filtrar por Estado:</span>
                    <a href="{{ route('admin.juntas.intervenciones', ['estado' => 'TODOS']) }}" class="btn btn-sm rounded-pill font-weight-bold px-3 {{ $estado === 'TODOS' ? 'btn-primary shadow-sm' : 'btn-light border text-muted' }}">Todos</a>
                    <a href="{{ route('admin.juntas.intervenciones', ['estado' => 'PENDIENTE']) }}" class="btn btn-sm rounded-pill font-weight-bold px-3 {{ $estado === 'PENDIENTE' ? 'btn-danger shadow-sm' : 'btn-light border text-muted' }}">
                        <i class="fas fa-clock mr-1"></i> Pendientes
                    </a>
                    <a href="{{ route('admin.juntas.intervenciones', ['estado' => 'EMITIDO']) }}" class="btn btn-sm rounded-pill font-weight-bold px-3 {{ $estado === 'EMITIDO' ? 'btn-success shadow-sm' : 'btn-light border text-muted' }}">
                        <i class="fas fa-check-double mr-1"></i> Emitidos con Firma
                    </a>
                </div>

                <div>
                    <select name="junta_id" class="form-control form-control-sm bg-white border font-weight-bold text-dark" style="border-radius: 8px; min-width: 220px;" onchange="this.form.submit()">
                        <option value="">-- Todas las Juntas --</option>
                        @foreach($juntas as $j)
                            <option value="{{ $j->id }}" {{ $juntaId == $j->id ? 'selected' : '' }}>{{ $j->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Expedientes de Intervención -->
    <div class="row">
        @forelse($intervenciones as $item)
            @php
                $badgeEstado = 'badge-danger';
                $borderHeader = '#dc2626';
                if ($item->estado === 'EMITIDO') {
                    $badgeEstado = 'badge-success';
                    $borderHeader = '#16a34a';
                } elseif ($item->estado === 'EN_APLICACION') {
                    $badgeEstado = 'badge-info';
                    $borderHeader = '#2563eb';
                }

                // Cargar Presidente estrictamente desde la BD
                $nombrePresidente = $item->junta?->presidente?->name 
                    ?? $item->junta?->presidente_nombre 
                    ?? 'Presidente no asignado';

                $cargoPresidente = $item->junta?->presidente_cargo 
                    ?? 'Presidente de la Junta';

                // Limpiar etiquetas HTML escapadas del título
                $tituloAccion = strip_tags($item->accion?->name ?? 'Acción Estratégica');
            @endphp
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                    
                    {{-- Encabezado Estilizado del Card (Padrón de Diseño del Sistema) --}}
                    <div class="card-header py-3 px-4 bg-dark text-white d-flex flex-wrap justify-content-between align-items-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-left: 5px solid {{ $borderHeader }} !important;">
                        <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                            <span class="badge badge-warning text-dark font-mono font-weight-bold px-3 py-1.5" style="font-family: monospace; font-size: 0.82rem; border-radius: 8px;">
                                {{ $item->codigo_expediente }}
                            </span>
                            <span class="badge {{ $badgeEstado }} text-uppercase font-weight-bold px-3 py-1.5" style="border-radius: 8px; font-size: 0.73rem;">
                                {{ str_replace('_', ' ', $item->estado) }}
                            </span>
                            <span class="badge badge-danger font-weight-bold px-2.5 py-1.5" style="border-radius: 8px; font-size: 0.72rem;">
                                <i class="fas fa-fire mr-1"></i> PRIORIDAD {{ strtoupper($item->prioridad) }}
                            </span>
                        </div>
                        <small class="text-white-50 font-weight-500 mt-2 mt-sm-0">
                            <i class="far fa-calendar-alt mr-1 text-warning"></i> Remitido el {{ $item->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    <div class="card-body p-4 bg-white">
                        <div class="row align-items-center">
                            {{-- Columna Izquierda: Detalle del Problema y Junta --}}
                            <div class="col-lg-7 mb-3 mb-lg-0">
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <span class="badge badge-danger rounded-circle p-2 mr-2" style="width: 32px; height: 32px; display: grid; place-items: center;">
                                        <i class="fas fa-bullseye text-white"></i>
                                    </span>
                                    <div>
                                        <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.05rem; line-height: 1.35;">
                                            {{ $tituloAccion }}
                                        </h5>
                                        <div class="small text-muted mb-2">
                                            <i class="fas fa-building text-primary mr-1"></i> <strong>Junta Asignada:</strong> <span class="text-dark font-weight-bold">{{ $item->junta->nombre ?? 'Junta Consultiva Institucional' }}</span>
                                            <span class="mx-1 text-muted">|</span>
                                            <i class="fas fa-user-shield text-info mr-1"></i> <strong>Presidente (BD):</strong> <span class="text-dark font-weight-bold">{{ $nombrePresidente }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bloque de Diagnóstico de Remisión --}}
                                <div class="p-3 rounded border mb-2" style="background: #f8fafc; border-left: 4px solid #3b82f6 !important; border-radius: 10px;">
                                    <strong class="text-dark small d-block mb-1">
                                        <i class="fas fa-align-left text-primary mr-1"></i> Diagnóstico de Remisión / Inconveniente:
                                    </strong>
                                    <div class="text-dark small" style="line-height: 1.45;">
                                        {{ strip_tags($item->diagnostico ?: 'Desviación en el cumplimiento de indicadores en Rojo.') }}
                                    </div>
                                </div>

                                @if($item->reporte)
                                    <div class="small text-muted mt-2">
                                        <i class="fas fa-user-edit mr-1 text-secondary"></i> Reportado por <strong>{{ $item->reporte->usuario->name ?? 'Técnico' }}</strong> el {{ $item->reporte->created_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </div>

                            {{-- Columna Derecha: Estado de Dictamen y Botón de Acción --}}
                            <div class="col-lg-5 border-left-lg pl-lg-4">
                                @if($item->estado === 'EMITIDO' && $item->recomendaciones_mitigacion)
                                    <div class="p-3 rounded border mb-3" style="background: #f0fdf4; border-color: #86efac !important; border-radius: 12px;">
                                        <small class="font-weight-bold text-success d-block mb-1 font-size-14">
                                            <i class="fas fa-signature mr-1"></i> Dictamen de Mitigación Emitido:
                                        </small>
                                        <ul class="pl-3 mb-2 small text-dark font-weight-500">
                                            @foreach($item->recomendaciones_mitigacion as $mit)
                                                <li>{{ $mit }}</li>
                                            @endforeach
                                        </ul>
                                        <div class="text-right border-top pt-1 mt-1">
                                            <small class="text-muted font-italic">
                                                Firmado por <strong>{{ $nombrePresidente }}</strong> ({{ $item->firma_estampada_at ? $item->firma_estampada_at->format('d/m/Y H:i') : 'Firmado' }})
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-3 rounded text-center mb-3 border" style="background: #fffbeb; border-color: #fde68a !important; border-radius: 12px;">
                                        <i class="fas fa-clock text-warning fa-2x mb-2 d-block"></i>
                                        <strong class="small font-weight-bold text-dark d-block">Pendiente de Dictamen del Consejo de Sabios</strong>
                                        <span class="text-muted" style="font-size: 0.78rem;">Se requiere la emisión del Dictamen y la firma del Presidente para cerrar la alerta.</span>
                                    </div>
                                @endif

                                <div class="text-right">
                                    <button class="btn btn-sm btn-purple font-weight-bold px-4 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #6d28d9 0%, #4c1d95 100%); color: white; border: none;" onclick='abrirModalEmitirDictamen(@json($item))'>
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
                <div class="card shadow-sm border-0 text-center py-5" style="border-radius: 16px;">
                    <div class="card-body py-5">
                        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                        <h4 class="font-weight-bold text-dark mb-1">¡No hay alertas pendientes en esta bandeja!</h4>
                        <p class="text-muted mb-0">No se han registrado reportes en rojo o todos los expedientes han sido atendidos por las Juntas Consultivas.</p>
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
