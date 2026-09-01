@extends('layouts.master')
@section('title', 'Flujograma & Diagnóstico de Circuito')

@push('styles')
<!-- Mermaid JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<style>
    .mermaid-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        overflow-x: auto;
    }
    .metric-card {
        border-left: 4px solid #17a2b8;
        border-radius: 8px;
    }
    .metric-card.danger {
        border-left-color: #dc3545;
    }
    .metric-card.success {
        border-left-color: #28a745;
    }
    .ai-report-box {
        background: #f0f7ff;
        border-left: 4px solid #0056b3;
        border-radius: 8px;
        padding: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Encabezado del Relevamiento -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title font-weight-bold mb-1">
                    <i class="fas fa-project-diagram text-info mr-2"></i> {{ $proceso->nombre }}
                </h3>
                <div class="d-flex flex-wrap align-items-center text-white-50">
                    <span class="mr-3"><i class="fas fa-hospital mr-1"></i> {{ $proceso->organigrama ? $proceso->organigrama->nombre : 'Servicio General' }}</span>
                    <span class="mr-3"><i class="fas fa-calendar-alt mr-1"></i> Visita: {{ $proceso->fecha_relevamiento ? $proceso->fecha_relevamiento->format('d/m/Y') : 'Hoy' }}</span>
                </div>
            </div>
            <div>
                <a href="{{ route('pei.procesos.exportPdf', $proceso->id) }}" target="_blank" class="btn btn-danger font-weight-bold shadow-sm mr-2">
                    <i class="fas fa-file-pdf mr-1"></i> Exportar Reporte PDF
                </a>
                <a href="{{ route('pei.procesos.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary"><i class="fas fa-file-contract mr-1"></i> Contexto / Móvil de la Visita:</h6>
                    <p class="text-dark bg-white p-2 rounded border">{{ $proceso->contexto_motivo ?: 'Relevamiento preventivo de eficiencia operativa.' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-info"><i class="fas fa-bullseye mr-1"></i> Acción / Meta del PEI Vinculada:</h6>
                    <p class="text-dark bg-white p-2 rounded border">
                        @if($proceso->peiProfile)
                            @php
                                $cleanPeiName = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($proceso->peiProfile->name, ENT_QUOTES, 'UTF-8'))));
                            @endphp
                            <span class="badge badge-info mr-1" style="font-size:0.8rem;">{{ mb_strtoupper($proceso->peiProfile->getLabelNivel()) }}</span>
                            <span class="font-weight-bold">{{ $cleanPeiName }}</span>
                        @else
                            <span class="text-muted">Sin vinculación específica al PEI.</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center mt-2">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-users mr-1"></i> Equipo Relevador (Responsables de Visita):</span>
                @forelse($proceso->responsables as $resp)
                    <span class="badge badge-primary px-2 py-1 mr-1 shadow-sm"><i class="fas fa-user-check mr-1"></i> {{ $resp->name }}</span>
                @empty
                    <span class="badge badge-secondary">Sin responsables asignados</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Barra de Tarjetas Métricas KPIs -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card metric-card shadow-sm p-3 bg-white">
                <small class="text-muted font-weight-bold text-uppercase">Lead Time Total Paciente</small>
                <h3 class="font-weight-bold text-dark mb-0">{{ $proceso->lead_time_total }} min</h3>
                <small class="text-info font-weight-bold"><i class="fas fa-clock mr-1"></i> {{ round($proceso->lead_time_total / 60, 1) }} horas de recorrido</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card metric-card success shadow-sm p-3 bg-white">
                <small class="text-muted font-weight-bold text-uppercase">Tiempo Atención Efectiva</small>
                <h3 class="font-weight-bold text-success mb-0">{{ $proceso->tiempo_atencion_total }} min</h3>
                <small class="text-muted">Valor agregado al paciente</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card metric-card danger shadow-sm p-3 bg-white">
                <small class="text-muted font-weight-bold text-uppercase">Tiempo Espera / Latencia</small>
                <h3 class="font-weight-bold text-danger mb-0">{{ $proceso->tiempo_espera_total }} min</h3>
                <small class="text-danger font-weight-bold"><i class="fas fa-hourglass-half mr-1"></i> Tiempo muerto en cola</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card metric-card shadow-sm p-3 bg-white">
                <small class="text-muted font-weight-bold text-uppercase">Eficiencia & Cuellos</small>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <span class="badge badge-pill badge-info px-3 py-2" style="font-size:1.1rem;">{{ $proceso->eficiencia }}% Eficiencia</span>
                    <span class="badge badge-pill badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $proceso->conteo_cuellos_botella }} Cuello(s)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Flujograma Visual Interactivo Mermaid -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-sitemap mr-2"></i> Flujograma Visual del Circuito de Atención
            </h5>
            <small class="text-white-50">🔴 Rojo = Cuello de Botella Crítico | 🟡 Amarillo = Demora | 🟢 Verde = Flujo Óptimo</small>
        </div>
        <div class="card-body">
            <div class="mermaid-container text-center">
                <pre class="mermaid" id="mermaidDiagram">
{!! $mermaidGraph !!}
                </pre>
            </div>
        </div>
    </div>

    <!-- Informe de Diagnóstico Inteligente de IA -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-robot mr-2"></i> Informe de Diagnóstico Asistido por Inteligencia Artificial (IA)
            </h5>
            <button id="btnRegenerarIa" class="btn btn-light btn-sm font-weight-bold text-info">
                <i class="fas fa-sync-alt mr-1"></i> Regenerar Análisis IA
            </button>
        </div>
        <div class="card-body">
            <div class="ai-report-box" id="boxAnalisisIa">
                {!! \Illuminate\Support\Str::markdown($proceso->analisis_ia ?: 'Generando análisis de diagnóstico...') !!}
            </div>
        </div>
    </div>

    <!-- Estaciones / Pasos del Circuito (Tabla interactiva) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-list-ol mr-2"></i> Estaciones del Circuito (Relevamiento Paso a Paso)
            </h5>
            <button class="btn btn-success btn-sm font-weight-bold" onclick="abrirModalPaso()">
                <i class="fas fa-plus-circle mr-1"></i> Agregar Estación
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tablaPasos">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Estación / Paso</th>
                            <th>Área / Dependencia</th>
                            <th>Rol Responsable</th>
                            <th>Atención</th>
                            <th>Espera</th>
                            <th>Sistema</th>
                            <th>Estado</th>
                            <th>Propuesta de Mejora</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proceso->pasos as $paso)
                            <tr class="{{ $paso->es_cuello_botella ? 'table-danger' : '' }}">
                                <td class="font-weight-bold text-center">{{ $paso->orden }}</td>
                                <td>
                                    <strong class="text-dark">{{ $paso->nombre }}</strong>
                                    @if($paso->descripcion)<br><small class="text-muted">{{ $paso->descripcion }}</small>@endif
                                </td>
                                <td>{{ $paso->organigrama ? $paso->organigrama->nombre : 'General' }}</td>
                                <td><span class="badge badge-secondary">{{ $paso->rol_responsable ?: 'N/A' }}</span></td>
                                <td class="text-success font-weight-bold">{{ $paso->tiempo_atencion_min }} min</td>
                                <td class="text-danger font-weight-bold">{{ $paso->tiempo_espera_min }} min</td>
                                <td><small class="text-dark font-weight-bold">{{ $paso->herramienta_sistema ?: 'Manual' }}</small></td>
                                <td>
                                    @if($paso->es_cuello_botella)
                                        <span class="badge badge-danger badge-pill"><i class="fas fa-exclamation-triangle mr-1"></i>CUELLO BOTELLA</span>
                                    @else
                                        <span class="badge badge-success badge-pill"><i class="fas fa-check mr-1"></i>Normal</span>
                                    @endif
                                </td>
                                <td><small class="text-dark font-italic">{{ $paso->propuesta_mejora ?: 'Sin observaciones' }}</small></td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-info" onclick='editarPaso(@json($paso))'><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarPaso('{{ $paso->id }}')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No hay estaciones registradas aún. ¡Haga clic en <strong>Agregar Estación</strong> para comenzar!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Estación / Paso -->
<div class="modal fade" id="modalPaso" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formPaso" action="{{ route('pei.procesos.pasos.store', $proceso->id) }}" method="POST">
                @csrf
                <input type="hidden" name="paso_id" id="paso_id">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title font-weight-bold" id="modalPasoTitle"><i class="fas fa-step-forward mr-2"></i> Registrar Estación del Circuito</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="font-weight-bold text-dark">N° Orden</label>
                            <input type="number" name="orden" id="paso_orden" class="form-control" value="{{ $proceso->pasos->count() + 1 }}" required>
                        </div>
                        <div class="col-md-9 form-group">
                            <label class="font-weight-bold text-dark">Nombre de la Estación <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="paso_nombre" class="form-control" placeholder="Ej: Verificación de Seguro y Aportes en Ventanilla" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Área / Dependencia</label>
                            <select name="organigrama_id" id="paso_organigrama_id" class="form-control select2-modal-paso" style="width:100%;">
                                <option value="">-- Seleccionar Área --</option>
                                @foreach($organigramas as $org)
                                    <option value="{{ $org->id }}">{{ $org->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Rol Responsable de Atención</label>
                            <input type="text" name="rol_responsable" id="paso_rol_responsable" class="form-control" placeholder="Ej: Admisionista / Enfermero Triage">
                        </div>
                    </div>

                    <div class="row bg-light p-2 rounded mb-3 border">
                        <div class="col-md-4 form-group mb-0">
                            <label class="font-weight-bold text-success">Tiempo Atención (min)</label>
                            <input type="number" name="tiempo_atencion_min" id="paso_tiempo_atencion_min" class="form-control" value="5" min="0" required>
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="font-weight-bold text-danger">Tiempo Espera / Cola (min)</label>
                            <input type="number" name="tiempo_espera_min" id="paso_tiempo_espera_min" class="form-control" value="0" min="0" required>
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="font-weight-bold text-info">Tiempo Traslado (min)</label>
                            <input type="number" name="tiempo_traslado_min" id="paso_tiempo_traslado_min" class="form-control" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Sistema Informático / Herramienta</label>
                            <input type="text" name="herramienta_sistema" id="paso_herramienta_sistema" class="form-control" placeholder="Ej: SIH / Ficha Papel / Agendamiento Web">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Diagnóstico de Fricción</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="es_cuello_botella" value="1" id="paso_es_cuello_botella">
                                <label class="form-check-input-label text-danger font-weight-bold" for="paso_es_cuello_botella">
                                    🚨 Marcar como Cuello de Botella Crítico
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Criticidad</label>
                            <select name="criticidad" id="paso_criticidad" class="form-control">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                                <option value="critica">Crítica</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Causa Raíz Principal</label>
                            <select name="causa_raiz" id="paso_causa_raiz" class="form-control">
                                <option value="">-- Sin Causa Específica --</option>
                                <option value="sobredemanda">Sobredemanda de Pacientes</option>
                                <option value="falta_personal">Falta de Personal en Ventanilla</option>
                                <option value="falla_sistema">Caída / Lentitud de Sistema</option>
                                <option value="burocracia_papel">Burocracia Trámites Manuales</option>
                                <option value="espacio_fisico">Espacio Físico Reducido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Observaciones de Campo</label>
                        <textarea name="observacion_campo" id="paso_observacion_campo" class="form-control" rows="2" placeholder="Hallazgos durante la observación en vivo..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Propuesta de Mejora Sugerida</label>
                        <textarea name="propuesta_mejora" id="paso_propuesta_mejora" class="form-control" rows="2" placeholder="Acción correctiva propuesta para esta estación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success font-weight-bold"><i class="fas fa-save mr-1"></i> Guardar Estación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
mermaid.initialize({ startOnLoad: true, theme: 'default' });

$(document).ready(function() {
    $('.select2-modal-paso').select2({
        dropdownParent: $('#modalPaso')
    });

    $('#formPaso').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                $('#modalPaso').modal('hide');
                toastr.success(res.message);
                location.reload();
            },
            error: function(err) {
                toastr.error('Error al guardar la estación.');
            }
        });
    });

    $('#btnRegenerarIa').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Analizando con IA...');
        $.ajax({
            url: "{{ route('pei.procesos.generarIa', $proceso->id) }}",
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-1"></i> Regenerar Análisis IA');
                toastr.success('Análisis de IA actualizado.');
                location.reload();
            }
        });
    });
});

function abrirModalPaso() {
    $('#formPaso')[0].reset();
    $('#paso_id').val('');
    $('#modalPasoTitle').html('<i class="fas fa-step-forward mr-2"></i> Registrar Estación del Circuito');
    $('#modalPaso').modal('show');
}

function editarPaso(paso) {
    $('#paso_id').val(paso.id);
    $('#paso_orden').val(paso.orden);
    $('#paso_nombre').val(paso.nombre);
    $('#paso_organigrama_id').val(paso.organigrama_id).trigger('change');
    $('#paso_rol_responsable').val(paso.rol_responsable);
    $('#paso_tiempo_atencion_min').val(paso.tiempo_atencion_min);
    $('#paso_tiempo_espera_min').val(paso.tiempo_espera_min);
    $('#paso_tiempo_traslado_min').val(paso.tiempo_traslado_min);
    $('#paso_herramienta_sistema').val(paso.herramienta_sistema);
    $('#paso_es_cuello_botella').prop('checked', paso.es_cuello_botella);
    $('#paso_criticidad').val(paso.criticidad);
    $('#paso_causa_raiz').val(paso.causa_raiz);
    $('#paso_observacion_campo').val(paso.observacion_campo);
    $('#paso_propuesta_mejora').val(paso.propuesta_mejora);
    $('#modalPasoTitle').html('<i class="fas fa-edit mr-2"></i> Editar Estación #' + paso.orden);
    $('#modalPaso').modal('show');
}

function eliminarPaso(pasoId) {
    if (confirm('¿Está seguro de eliminar esta estación del circuito?')) {
        $.ajax({
            url: "{{ url('pei/procesos/' . $proceso->id . '/pasos') }}/" + pasoId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                toastr.success(res.message);
                location.reload();
            }
        });
    }
}
</script>
@endpush
