<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal de Regulación Farmacéutica — RIISS IPS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --brand-primary: #0d9488;
            --brand-dark: #0f172a;
            --brand-navy: #1e293b;
            --brand-accent: #14b8a6;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        .navbar-portal {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 60%, #115e59 100%);
            box-shadow: 0 4px 20px -2px rgba(13, 148, 136, 0.35);
            padding: 0.9rem 1.5rem;
        }

        .kpi-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            transition: transform 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
        }

        .list-esp-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 8px;
            padding: 12px 14px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .list-esp-item:hover {
            border-color: #0d9488;
            background: #f0fdfa;
            transform: translateX(3px);
        }
        .list-esp-item.active {
            border-color: #0d9488;
            background: #ccfbf1;
            border-left: 5px solid #0d9488;
            font-weight: 700;
        }

        .signature-pad-container {
            border: 2px dashed #94a3b8;
            border-radius: 10px;
            background: #ffffff;
            touch-action: none;
            position: relative;
        }

        .btn-validate-ok {
            background: #10b981;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            padding: 4px 10px;
            font-size: 12px;
        }
        .btn-validate-ok:hover {
            background: #059669;
            color: #ffffff;
        }

        .btn-validate-no {
            background: #ef4444;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            padding: 4px 10px;
            font-size: 12px;
        }
        .btn-validate-no:hover {
            background: #dc2626;
            color: #ffffff;
        }
    </style>
</head>
<body>

{{-- Barra Superior Institucional --}}
<nav class="navbar navbar-dark navbar-portal sticky-top d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <div class="p-2 rounded-circle bg-white text-teal mr-3" style="color: #0d9488; width: 44px; height: 44px; display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-pills fa-lg"></i>
        </div>
        <div>
            <div class="text-white font-weight-bold" style="font-size: 1.1rem; letter-spacing: 0.3px;">
                Portal de Regulación Farmacéutica — IPS
            </div>
            <small class="text-white-50">
                Auditoría y Dictamen Técnico Farmacológico por Especialidad Médica (Resolución del Vademécum Oficial)
            </small>
        </div>
    </div>

    <div class="d-flex align-items-center" style="gap: 14px;">
        <div class="text-right text-white d-none d-md-block">
            <div class="font-weight-bold" style="font-size: 13.5px;">{{ $sesion->analista_nombre }}</div>
            <small class="text-white-50">{{ $sesion->analista_cargo ?? 'Unidad de Regulación Farmacéutica' }} | Mat: {{ $sesion->matricula_profesional ?: 'N/D' }}</small>
        </div>
        <a href="{{ route('riiss.portal-farmaceutico.salir', $sesion->token) }}" class="btn btn-sm btn-outline-light font-weight-bold px-3 py-2 rounded-lg">
            <i class="fa fa-sign-out-alt mr-1"></i> Salir
        </a>
    </div>
</nav>

<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Tarjetas KPI de Avance --}}
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0d9488;">
                <div class="small text-muted font-weight-bold text-uppercase">Total Especialidades</div>
                <div class="h3 font-weight-bold text-dark mb-0 mt-1">{{ $totalEspecialidades }}</div>
                <small class="text-muted">Red de Salud IPS</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #10b981;">
                <div class="small text-muted font-weight-bold text-uppercase">Dictaminadas / Validadas</div>
                <div class="h3 font-weight-bold text-success mb-0 mt-1" id="kpiValidadas">{{ $totalValidadas }}</div>
                <small class="text-success font-weight-bold"><i class="fa fa-check mr-1"></i>Firmadas</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #f59e0b;">
                <div class="small text-muted font-weight-bold text-uppercase">Pendientes de Dictamen</div>
                <div class="h3 font-weight-bold text-warning mb-0 mt-1" id="kpiPendientes">{{ $totalPendientes }}</div>
                <small class="text-warning font-weight-bold"><i class="fa fa-clock mr-1"></i>Por evaluar</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #22c55e;">
                <div class="small text-muted font-weight-bold text-uppercase">Medicamentos Aprobados</div>
                <div class="h3 font-weight-bold text-success mb-0 mt-1" id="kpiMedAprobados">{{ $totalMedicamentosAprobados }}</div>
                <small class="text-muted">Asignación Validada</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #ef4444;">
                <div class="small text-muted font-weight-bold text-uppercase">Invalidados / Retirados</div>
                <div class="h3 font-weight-bold text-danger mb-0 mt-1" id="kpiMedInvalidados">{{ $totalMedicamentosInvalidados }}</div>
                <small class="text-danger font-weight-bold"><i class="fa fa-times mr-1"></i>Con Justificación</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #8b5cf6;">
                <div class="small text-muted font-weight-bold text-uppercase">Incorporados Nuevos</div>
                <div class="h3 font-weight-bold text-purple mb-0 mt-1" style="color: #8b5cf6;" id="kpiMedIncorporados">{{ $totalMedicamentosIncorporados }}</div>
                <small class="text-muted">Del Vademécum IPS</small>
            </div>
        </div>
    </div>

    {{-- Layout Principal de 2 Columnas --}}
    <div class="row">
        {{-- Columna Izquierda: Listado de Especialidades Médicas --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fa fa-stethoscope mr-1 text-teal" style="color: #0d9488;"></i> Especialidades Médicas ({{ $totalEspecialidades }})
                    </h6>
                </div>
                <div class="p-3 bg-light border-bottom">
                    <input type="text" id="buscarEspecialidadInput" class="form-control form-control-sm mb-2" placeholder="🔍 Buscar especialidad...">
                    <div class="d-flex" style="gap: 5px;">
                        <button class="btn btn-xs btn-outline-secondary active btn-filter-esp" data-filter="all">Todas</button>
                        <button class="btn btn-xs btn-outline-warning btn-filter-esp" data-filter="pendiente">Pendientes</button>
                        <button class="btn btn-xs btn-outline-success btn-filter-esp" data-filter="validada">Validadas</button>
                    </div>
                </div>
                <div class="card-body p-3" style="max-height: 680px; overflow-y: auto;" id="contenedorListaEspecialidades">
                    @foreach($especialidades as $esp)
                        @php
                            $val = $validaciones->get($esp->id);
                            $isVal = $val && $val->estado === 'validada';
                        @endphp
                        <div class="list-esp-item item-esp-card {{ $loop->first ? 'active' : '' }}" 
                             data-id="{{ $esp->id }}" 
                             data-nombre="{{ strtolower($esp->nombre) }}"
                             data-estado="{{ $isVal ? 'validada' : 'pendiente' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                        {{ $esp->nombre }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fa fa-pills mr-1"></i> {{ $esp->total_vademecum }} Medicamentos Vademécum
                                    </small>
                                </div>
                                <span class="badge {{ $isVal ? 'badge-success' : 'badge-warning' }} px-2 py-1 badge-status-esp-{{ $esp->id }}" style="font-size: 11px;">
                                    {{ $isVal ? '✅ Dictaminada' : '🕒 Pendiente' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Matriz de Auditoría y Dictamen Farmacéutico --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-0" id="tituloEspecialidadActual">
                            <i class="fa fa-stethoscope mr-2 text-teal" style="color:#0d9488;"></i> Seleccione una especialidad
                        </h5>
                        <small class="text-muted" id="subtituloEspecialidadActual">
                            Evaluación farmacológica y pertinencia según Vademécum Institucional Aprobado
                        </small>
                    </div>
                    <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 8px;">
                        <span class="badge badge-light border px-3 py-2 font-weight-bold text-success" id="badgeValidadosCount">0 Validados</span>
                        <span class="badge badge-light border px-3 py-2 font-weight-bold text-danger" id="badgeInvalidadosCount">0 Invalidados</span>
                        <span class="badge badge-light border px-3 py-2 font-weight-bold text-warning" id="badgePendientesCount">0 Pendientes</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Toolbar: Incorporar Medicamento Faltante --}}
                    <div class="p-3 mb-4 rounded-lg bg-light border">
                        <div class="small font-weight-bold text-uppercase mb-2 text-teal" style="color: #0d9488;">
                            <i class="fa fa-plus-circle mr-1"></i> Incorporar Medicamento del Vademécum Oficial Aprobado a esta Especialidad
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-9 mb-2 mb-md-0">
                                <select id="selectMedicamentoIncorporar" class="form-control" style="width: 100%;">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="btnAbrirModalIncorporar" class="btn btn-sm btn-block font-weight-bold text-white py-2" style="background: #0d9488; border-radius: 6px;">
                                    <i class="fa fa-plus mr-1"></i> Incorporar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de Medicamentos Asignados --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover align-middle mb-0" id="tablaMedicamentosDictamen" style="width:100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 70px;" class="text-center">Código</th>
                                    <th>Medicamento / Principio Activo</th>
                                    <th>Concentración y Forma</th>
                                    <th style="width: 110px;" class="text-center">Vía / Uso</th>
                                    <th style="width: 130px;" class="text-center">Dictamen</th>
                                    <th style="width: 160px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyMedicamentosDictamen">
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa fa-spinner fa-spin fa-2x mb-2 text-teal" style="color: #0d9488;"></i>
                                        <div>Cargando medicamentos de la especialidad...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Sección de Cierre y Dictamen con Firma Digital --}}
                    <div class="card border rounded-lg p-3 bg-light">
                        <h6 class="font-weight-bold text-dark mb-3">
                            <i class="fa fa-file-signature mr-1 text-teal" style="color: #0d9488;"></i> Cierre y Dictamen Técnico Farmacológico de la Especialidad
                        </h6>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-dark">Observaciones Técnicas / Criterio Farmacológico</label>
                            <textarea id="inputObservacionesTecnicas" class="form-control" rows="2" placeholder="Observaciones generales sobre la pertinencia farmacológica de esta especialidad..."></textarea>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold small text-dark d-flex justify-content-between">
                                    <span>Firma Digital del Químico Farmacéutico Responsable</span>
                                    <a href="javascript:void(0)" id="btnLimpiarFirma" class="text-danger small font-weight-bold">Limpiar</a>
                                </label>
                                <div class="signature-pad-container">
                                    <canvas id="signatureCanvas" style="width: 100%; height: 130px; display: block;"></canvas>
                                </div>
                                <small class="text-muted">Dibuje su firma con el dedo, mouse o lápiz táctil.</small>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-white border rounded">
                                    <div class="small font-weight-bold text-muted">Profesional:</div>
                                    <div class="font-weight-bold text-dark">{{ $sesion->analista_nombre }}</div>
                                    <div class="small text-muted">Matrícula: <strong>{{ $sesion->matricula_profesional ?: 'N/D' }}</strong> | C.I.: <strong>{{ $sesion->analista_documento ?: 'N/D' }}</strong></div>
                                </div>

                                <button type="button" id="btnFirmarDictamen" class="btn btn-block font-weight-bold text-white mt-3 py-2 shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border-radius: 8px;">
                                    <i class="fa fa-check-circle mr-1"></i> Firmar y Dictaminar Especialidad
                                </button>
                                
                                <a href="javascript:void(0)" id="btnImprimirDictamen" target="_blank" class="btn btn-outline-secondary btn-block font-weight-bold mt-2 d-none" style="border-radius: 8px;">
                                    <i class="fa fa-print mr-1"></i> Imprimir / Ver Dictamen Oficial (PDF)
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para Invalidar con Justificación Obligatoria --}}
<div class="modal fade" id="modalInvalidarMedicamento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-danger text-white" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title font-weight-bold" id="modalInvalidarTitulo">
                    <i class="fa fa-times-circle mr-2"></i> Invalidar / Retirar Medicamento
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="hiddenInvalidarMedId">
                <div class="mb-3">
                    <div class="small font-weight-bold text-muted">Medicamento:</div>
                    <div class="font-weight-bold text-dark h6" id="labelInvalidarMedNombre"></div>
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark small">
                        Justificación Técnica Farmacológica <span class="text-danger">* (Obligatorio)</span>
                    </label>
                    <textarea id="inputJustificacionInvalidar" class="form-control" rows="3" required placeholder="Ej: No corresponde al perfil terapéutico de la especialidad; su uso está normado para..."></textarea>
                    <small class="text-muted">Explique el motivo técnico por el cual este medicamento no es pertinente para esta especialidad.</small>
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmarInvalidar" class="btn btn-danger font-weight-bold px-4">
                    <i class="fa fa-ban mr-1"></i> Confirmar Invalidación
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal para Incorporar Medicamento con Justificación --}}
<div class="modal fade" id="modalIncorporarJustificacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header text-white" style="background: #0d9488; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-plus-circle mr-2"></i> Incorporar Medicamento del Vademécum
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <div class="small font-weight-bold text-muted">Medicamento seleccionado:</div>
                    <div class="font-weight-bold text-dark h6" id="labelIncorporarMedNombre"></div>
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark small">
                        Criterio / Justificación de Incorporación <span class="text-danger">*</span>
                    </label>
                    <textarea id="inputJustificacionIncorporar" class="form-control" rows="3" required placeholder="Ej: Fármaco normado en el Vademécum Oficial IPS necesario para el protocolo de atención de..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmarIncorporar" class="btn text-white font-weight-bold px-4" style="background: #0d9488;">
                    <i class="fa fa-check mr-1"></i> Confirmar e Incorporar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
$(document).ready(function() {
    const TOKEN = '{{ $sesion->token }}';
    let currentEspecialidadId = {{ $especialidades->first()->id ?? 0 }};
    let currentEspecialidadNombre = '{{ $especialidades->first()->nombre ?? "" }}';
    let dtMedicamentos = null;
    let signaturePad = null;

    // Inicializar Canvas de Firma
    const canvas = document.getElementById('signatureCanvas');
    if (canvas) {
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();
        signaturePad = new SignaturePad(canvas, {
            penColor: 'rgb(15, 23, 42)',
            backgroundColor: 'rgb(255, 255, 255)'
        });
    }

    $('#btnLimpiarFirma').on('click', function() {
        if (signaturePad) signaturePad.clear();
    });

    // Select2 para buscador de medicamentos a incorporar
    $('#selectMedicamentoIncorporar').select2({
        placeholder: '🔍 Buscar medicamento del Vademécum IPS...',
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("riiss.validaciones.buscar-medicamentos") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
            cache: true
        }
    });

    // Cargar Especialidad Inicial
    if (currentEspecialidadId) {
        cargarEspecialidad(currentEspecialidadId, currentEspecialidadNombre);
    }

    // Selección de Especialidad
    $(document).on('click', '.item-esp-card', function() {
        $('.item-esp-card').removeClass('active');
        $(this).addClass('active');

        currentEspecialidadId = $(this).data('id');
        currentEspecialidadNombre = $(this).find('.font-weight-bold').text().trim();
        cargarEspecialidad(currentEspecialidadId, currentEspecialidadNombre);
    });

    // Filtros de Especialidades
    $('#buscarEspecialidadInput').on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        $('.item-esp-card').each(function() {
            const n = $(this).data('nombre');
            $(this).toggle(n.includes(q));
        });
    });

    $('.btn-filter-esp').on('click', function() {
        $('.btn-filter-esp').removeClass('active');
        $(this).addClass('active');
        const filter = $(this).data('filter');

        $('.item-esp-card').each(function() {
            const st = $(this).data('estado');
            if (filter === 'all' || st === filter) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Función principal para cargar medicamentos de la especialidad
    function cargarEspecialidad(espId, espNombre) {
        $('#tituloEspecialidadActual').html(`<i class="fa fa-stethoscope mr-2 text-teal" style="color:#0d9488;"></i> ${espNombre}`);
        $('#selectMedicamentoIncorporar').val(null).trigger('change');
        if (signaturePad) signaturePad.clear();

        if (dtMedicamentos) {
            dtMedicamentos.destroy();
        }

        $('#tbodyMedicamentosDictamen').html(`
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x mb-2 text-teal" style="color: #0d9488;"></i>
                    <div>Cargando medicamentos de ${espNombre}...</div>
                </td>
            </tr>
        `);

        $.get(`{{ url('riiss/portal-regulacion-farmaceutica') }}/${TOKEN}/especialidad/${espId}/medicamentos`, function(res) {
            if (res.success) {
                const esp = res.especialidad;
                $('#badgeValidadosCount').text(`${esp.total_validados} Validados`);
                $('#badgeInvalidadosCount').text(`${esp.total_invalidados} Invalidados`);
                $('#badgePendientesCount').text(`${esp.total_pendientes} Pendientes`);

                $('#inputObservacionesTecnicas').val(esp.observaciones || '');

                if (esp.estado_general === 'validada') {
                    $('#btnImprimirDictamen').removeClass('d-none').attr('href', `{{ url('riiss/portal-regulacion-farmaceutica') }}/${TOKEN}/dictamen/${espId}/imprimir`);
                } else {
                    $('#btnImprimirDictamen').addClass('d-none');
                }

                let html = '';
                res.medicamentos.forEach(function(m) {
                    let badgeDictamen = '<span class="badge badge-light border text-muted px-2 py-1">🕒 Pendiente</span>';
                    let bgRow = '';

                    if (m.estado_dictamen === 'validado') {
                        badgeDictamen = '<span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Aprobado</span>';
                        bgRow = 'style="background-color: #f0fdf4;"';
                    } else if (m.estado_dictamen === 'invalidado') {
                        badgeDictamen = '<span class="badge badge-danger px-2 py-1"><i class="fa fa-times mr-1"></i> Invalidado</span>';
                        bgRow = 'style="background-color: #fef2f2;"';
                    } else if (m.estado_dictamen === 'incorporado') {
                        badgeDictamen = '<span class="badge badge-info px-2 py-1"><i class="fa fa-plus-circle mr-1"></i> Incorporado</span>';
                        bgRow = 'style="background-color: #f0f9ff;"';
                    }

                    html += `
                        <tr id="fila-med-${m.id}" ${bgRow}>
                            <td class="text-center font-monospace font-weight-bold text-muted" style="font-size:12px;">${m.codigo}</td>
                            <td>
                                <div class="font-weight-bold text-dark" style="font-size:13px;">${m.nombre}</div>
                                ${m.justificacion ? `<div class="text-danger small mt-1"><strong>Motivo:</strong> ${m.justificacion}</div>` : ''}
                            </td>
                            <td style="font-size:12px;">
                                ${m.concentracion ? `<strong>${m.concentracion}</strong>` : ''}
                                ${m.forma_farmaceutica ? `<div class="text-muted small">${m.forma_farmaceutica}</div>` : ''}
                            </td>
                            <td class="text-center small">
                                <div>${m.via_administracion || '—'}</div>
                                <span class="badge badge-light border text-info" style="font-size:10.5px;">${m.uso_vademecum}</span>
                            </td>
                            <td class="text-center">${badgeDictamen}</td>
                            <td class="text-center">
                                <button type="button" class="btn-validate-ok btn-accion-validar mr-1" data-id="${m.id}" data-nombre="${m.nombre}" title="Validar / Aprobar este medicamento">
                                    <i class="fa fa-check"></i> Validar
                                </button>
                                <button type="button" class="btn-validate-no btn-accion-invalidar" data-id="${m.id}" data-nombre="${m.nombre}" data-justificacion="${m.justificacion || ''}" title="Invalidar con justificación">
                                    <i class="fa fa-times"></i> Invalidar
                                </button>
                            </td>
                        </tr>
                    `;
                });

                if (res.medicamentos.length === 0) {
                    html = `
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No hay medicamentos registrados para esta especialidad. Use el buscador superior para incorporar medicamentos.
                            </td>
                        </tr>
                    `;
                }

                $('#tbodyMedicamentosDictamen').html(html);

                if (res.medicamentos.length > 0) {
                    dtMedicamentos = $('#tablaMedicamentosDictamen').DataTable({
                        language: {
                            info: 'Mostrando _START_ a _END_ de _TOTAL_ medicamentos',
                            infoEmpty: '0 medicamentos',
                            search: 'Buscar:',
                            paginate: { next: 'Sig', previous: 'Ant' },
                            lengthMenu: '_MENU_ por pág.'
                        },
                        pageLength: 15,
                        order: [[4, 'desc'], [1, 'asc']],
                        columnDefs: [{ orderable: false, targets: [5] }]
                    });
                }
            }
        });
    }

    // Acción 1: Validar / Aprobar Medicamento
    $(document).on('click', '.btn-accion-validar', function() {
        const medId = $(this).data('id');
        const medNombre = $(this).data('nombre');

        $.post(`{{ url('riiss/portal-regulacion-farmaceutica') }}/${TOKEN}/dictaminar-medicamento`, {
            _token: '{{ csrf_token() }}',
            especialidad_id: currentEspecialidadId,
            medicamento_id: medId,
            estado_validacion: 'validado'
        }, function(res) {
            if (res.success) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `"${medNombre}" aprobado`,
                    showConfirmButton: false,
                    timer: 1800
                });
                cargarEspecialidad(currentEspecialidadId, currentEspecialidadNombre);
            }
        });
    });

    // Acción 2: Abrir modal para Invalidar con Justificación Obligatoria
    $(document).on('click', '.btn-accion-invalidar', function() {
        const medId = $(this).data('id');
        const medNombre = $(this).data('nombre');
        const just = $(this).data('justificacion') || '';

        $('#hiddenInvalidarMedId').val(medId);
        $('#labelInvalidarMedNombre').text(medNombre);
        $('#inputJustificacionInvalidar').val(just);
        $('#modalInvalidarMedicamento').modal('show');
    });

    // Confirmar Invalidación
    $('#btnConfirmarInvalidar').on('click', function() {
        const medId = $('#hiddenInvalidarMedId').val();
        const just = $('#inputJustificacionInvalidar').val().trim();

        if (!just) {
            Swal.fire('Atención', 'Debe registrar la justificación técnica para invalidar este medicamento.', 'warning');
            return;
        }

        $.post(`{{ url('riiss/portal-regulacion-farmaceutica') }}/${TOKEN}/dictaminar-medicamento`, {
            _token: '{{ csrf_token() }}',
            especialidad_id: currentEspecialidadId,
            medicamento_id: medId,
            estado_validacion: 'invalidado',
            justificacion: just
        }, function(res) {
            if (res.success) {
                $('#modalInvalidarMedicamento').modal('hide');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Medicamento invalidado con justificación',
                    showConfirmButton: false,
                    timer: 2000
                });
                cargarEspecialidad(currentEspecialidadId, currentEspecialidadNombre);
            }
        }).fail(function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Error al guardar.', 'error');
        });
    });

    // Acción 3: Abrir modal para Incorporar Medicamento
    $('#btnAbrirModalIncorporar').on('click', function() {
        const medId = $('#selectMedicamentoIncorporar').val();
        const medText = $('#selectMedicamentoIncorporar option:selected').text();

        if (!medId) {
            Swal.fire('Atención', 'Seleccione un medicamento del buscador primero.', 'warning');
            return;
        }

        $('#labelIncorporarMedNombre').text(medText);
        $('#inputJustificacionIncorporar').val('');
        $('#modalIncorporarJustificacion').modal('show');
    });

    // Confirmar Incorporación
    $('#btnConfirmarIncorporar').on('click', function() {
        const medId = $('#selectMedicamentoIncorporar').val();
        const just = $('#inputJustificacionIncorporar').val().trim();

        if (!just) {
            Swal.fire('Atención', 'Debe indicar el motivo o justificación técnica de la incorporación.', 'warning');
            return;
        }

        $.post(`{{ url('riiss/portal-regulacion-farmaceutica') }}/${TOKEN}/incorporar-medicamento`, {
            _token: '{{ csrf_token() }}',
            especialidad_id: currentEspecialidadId,
            medicamento_id: medId,
            justificacion: just
        }, function(res) {
            if (res.success) {
                $('#modalIncorporarJustificacion').modal('hide');
                Swal.fire('Éxito', res.message, 'success');
                cargarEspecialidad(currentEspecialidadId, currentEspecialidadNombre);
            }
        }).fail(function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Error al incorporar.', 'error');
        });
    });

    // Acción 4: Firmar y Dictaminar Especialidad
    $('#btnFirmarDictamen').on('click', function() {
        if (!signaturePad || signaturePad.isEmpty()) {
            Swal.fire('Atención', 'Por favor estampe su firma digital en el recuadro antes de cerrar el dictamen.', 'warning');
            return;
        }

        const firmaBase64 = signaturePad.toDataURL();
        const observaciones = $('#inputObservacionesTecnicas').val().trim();
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando Dictamen...');

        $.post(`{{ url('riiss/portal-regulacion-farmaceutica') }}/${TOKEN}/firmar-especialidad`, {
            _token: '{{ csrf_token() }}',
            especialidad_id: currentEspecialidadId,
            observaciones_tecnicas: observaciones,
            firma_digital: firmaBase64,
            firmado_por: '{{ $sesion->analista_nombre }}',
            firmado_documento: '{{ $sesion->analista_documento }}',
            firmado_matricula: '{{ $sesion->matricula_profesional }}'
        }, function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-check-circle mr-1"></i> Firmar y Dictaminar Especialidad');
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Especialidad Dictaminada!',
                    text: 'El dictamen técnico farmacológico ha sido firmado y registrado con validez oficial.',
                    confirmButtonColor: '#0d9488'
                });

                $(`.badge-status-esp-${currentEspecialidadId}`).removeClass('badge-warning').addClass('badge-success').text('✅ Dictaminada');
                $(`.item-esp-card[data-id="${currentEspecialidadId}"]`).attr('data-estado', 'validada');

                cargarEspecialidad(currentEspecialidadId, currentEspecialidadNombre);
            }
        }).fail(function() {
            $btn.prop('disabled', false).html('<i class="fa fa-check-circle mr-1"></i> Firmar y Dictaminar Especialidad');
            Swal.fire('Error', 'No se pudo guardar la firma del dictamen.', 'error');
        });
    });
});
</script>

</body>
</html>
