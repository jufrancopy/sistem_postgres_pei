<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Validación de Especialidades Médicas — Área Interior</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --secondary: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-portal {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0369a1 100%);
            color: #ffffff;
            padding: 12px 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .portal-container {
            flex: 1;
            display: flex;
            height: calc(100vh - 75px);
            overflow: hidden;
        }

        /* Panel Izquierdo: Lista de Establecimientos */
        .sidebar-establecimientos {
            width: 380px;
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            background: #ffffff;
        }

        .lista-est-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .est-item {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            background: #ffffff;
        }

        .est-item:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .est-item.active {
            background: #e0f2fe;
            border-color: #38bdf8;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.12);
        }

        .est-item.active .est-nombre {
            color: #0369a1;
            font-weight: 700;
        }

        /* Panel Derecho: Área de Trabajo del Establecimiento */
        .workspace-panel {
            flex: 1;
            background: var(--bg-body);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .workspace-header {
            background: #ffffff;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .workspace-body {
            padding: 24px;
            flex: 1;
        }

        .table-especialidades {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .table-especialidades th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 12px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 16px;
        }

        .table-especialidades td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13.5px;
        }

        /* Toggle Switch */
        .custom-switch-lg .custom-control-label {
            padding-top: 2px;
            font-weight: 600;
            cursor: pointer;
        }
        .custom-switch-lg .custom-control-label::before {
            height: 22px;
            width: 44px;
            border-radius: 12px;
        }
        .custom-switch-lg .custom-control-label::after {
            width: 18px;
            height: 18px;
            border-radius: 9px;
            top: calc(0.25rem + 1px);
        }
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: var(--success);
            border-color: var(--success);
        }

        .input-justificacion {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 12.5px;
            padding: 6px 10px;
            transition: all 0.2s;
            width: 100%;
        }
        .input-justificacion:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
            outline: none;
            background: #ffffff;
        }

        .badge-save {
            font-size: 11px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .badge-save.show {
            opacity: 1;
        }

        /* Modal Signature */
        #signature-pad canvas {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background-color: #fafafa;
            cursor: crosshair;
            width: 100%;
            height: 180px;
        }

        @media (max-width: 992px) {
            .portal-container {
                flex-direction: column;
                height: auto;
            }
            .sidebar-establecimientos {
                width: 100%;
                height: 320px;
            }
        }
    </style>
</head>
<body>

    {{-- Barra Superior --}}
    <nav class="navbar-portal d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="bg-white text-dark font-weight-bold rounded px-2 py-1 mr-3 shadow-sm" style="font-size: 13px; color:#0284c7 !important;">
                IPS
            </div>
            <div>
                <div class="font-weight-bold" style="font-size: 15px; letter-spacing: 0.3px;">
                    DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR
                </div>
                <div style="font-size: 12px; color: #94a3b8;">
                    Sistema de Validación de Especialidades Médicas (Catálogo Bioestadística)
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <div class="text-right mr-3 d-none d-md-block">
                <div class="font-weight-bold text-white" style="font-size: 13px;">
                    <i class="fa fa-user-circle mr-1 text-info"></i> {{ $sesion->analista_nombre }}
                </div>
                <div class="badge badge-light px-2 py-1 font-weight-bold" style="font-size: 11px;">
                    Código: {{ $sesion->codigo_acceso }}
                </div>
            </div>

            <a href="{{ route('riiss.portal-validador.acta-imprimir', $sesion->token) }}" target="_blank" class="btn btn-outline-light btn-sm mr-2 shadow-sm font-weight-bold">
                <i class="fa fa-print mr-1"></i> Ver Acta
            </a>

            <button type="button" class="btn btn-success btn-sm shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalFinalizar">
                <i class="fa fa-signature mr-1"></i> Cerrar / Firmar
            </button>
        </div>
    </nav>

    {{-- Contenedor Principal Master-Detail --}}
    <div class="portal-container">

        {{-- Barra Lateral Izquierda: Selector de Establecimientos --}}
        <aside class="sidebar-establecimientos">
            <div class="sidebar-header">
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="filtroEstablecimiento" class="form-control border-left-0 bg-light" placeholder="Buscar establecimiento...">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <select id="filtroDepartamento" class="form-control form-control-sm" style="font-size: 11.5px;">
                        <option value="">Todos los Departamentos ({{ $establecimientos->count() }})</option>
                        @foreach($departamentos as $dpto)
                            <option value="{{ $dpto }}">{{ $dpto }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="lista-est-scroll" id="listaEstablecimientos">
                @foreach($establecimientos as $est)
                    @php
                        $res = $resumenValidaciones->get($est->id_establecimiento);
                        $totalReg = $res ? $res->total_registros : 0;
                        $totalActivas = $res ? $res->total_activas : 0;
                    @endphp
                    <div class="est-item" 
                         data-id="{{ $est->id_establecimiento }}"
                         data-nombre="{{ strtolower($est->nombre_oficial) }}"
                         data-depto="{{ $est->departamento }}"
                         onclick="seleccionarEstablecimiento('{{ $est->id_establecimiento }}')">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="est-nombre font-weight-600 text-dark" style="font-size: 13px;">
                                {{ $est->nombre_oficial }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted" style="font-size: 11px;">
                                <i class="fa fa-map-marker-alt text-secondary"></i> {{ $est->departamento }}
                            </span>
                            <span class="badge {{ $totalReg > 0 ? 'badge-primary' : 'badge-light border' }}" id="badge-est-{{ $est->id_establecimiento }}" style="font-size: 10px;">
                                {{ $totalReg > 0 ? $totalActivas . ' activas' : 'Sin revisar' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        {{-- Panel Derecho: Espacio de Trabajo de Especialidades --}}
        <main class="workspace-panel" id="workspacePanel">
            
            {{-- Estado Inicial / Placeholder --}}
            <div id="workspaceVacio" class="d-flex flex-column align-items-center justify-content-center h-100 p-5 text-center text-muted">
                <i class="fa fa-hospital fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h4 class="font-weight-bold text-dark mb-1">Seleccione un Establecimiento de Salud</h4>
                <p class="small text-muted" style="max-width: 420px;">
                    Haga clic en cualquiera de los centros del listado de la izquierda para desplegar y validar sus especialidades médicas en tiempo real.
                </p>
            </div>

            {{-- Área Activa de Trabajo --}}
            <div id="workspaceActivo" style="display: none;">
                
                {{-- Encabezado del Establecimiento --}}
                <div class="workspace-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <span class="badge badge-info px-2 py-1 text-uppercase font-weight-bold" id="estDeptoBadge" style="font-size: 10.5px;">
                            DEPARTAMENTO
                        </span>
                        <h4 class="font-weight-bold text-dark mt-1 mb-1" id="estNombreTitulo">
                            Nombre del Establecimiento
                        </h4>
                        <div class="text-muted small">
                            <span id="estTipologia">Tipología</span> · <span id="estComplejidad" class="text-primary font-weight-bold">Complejidad</span>
                        </div>
                    </div>

                    <div class="mt-3 mt-md-0 d-flex align-items-center">
                        <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold shadow-sm mr-2" onclick="abrirModalAgregar()">
                            <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad (Bioestadística)
                        </button>
                    </div>
                </div>

                {{-- Cuerpo de la Validación --}}
                <div class="workspace-body">
                    
                    {{-- Barra de Resumen Rápido --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="font-weight-bold text-dark" style="font-size: 14px;">
                            <i class="fa fa-list-ul text-primary mr-1"></i> Especialidades Médicas Registradas
                            <span class="badge badge-secondary ml-1" id="contadorEspecialidades">0</span>
                        </div>
                        <div class="text-muted small">
                            <i class="fa fa-info-circle text-info"></i> El campo de justificación es <strong>opcional</strong>. Los cambios se guardan automáticamente.
                        </div>
                    </div>

                    {{-- Tabla de Especialidades --}}
                    <div class="table-responsive table-especialidades">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 8%;" class="text-center">ID</th>
                                    <th style="width: 32%;">Especialidad Médica (Bioestadística)</th>
                                    <th style="width: 18%;" class="text-center">Estado en Centro</th>
                                    <th style="width: 36%;">Justificación / Observación (Opcional)</th>
                                    <th style="width: 6%;" class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEspecialidadesBody">
                                {{-- Filas inyectadas dinámicamente vía JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </main>

    </div>

    {{-- Modal para Agregar Especialidad desde Catálogo Maestro de Bioestadística --}}
    <div class="modal fade" id="modalAgregarEspecialidad" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%);">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-plus-circle mr-2"></i> Agregar Especialidad Médica
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Seleccione una especialidad médica del catálogo canónico de <strong>Bioestadística</strong> para incorporarla al establecimiento.
                    </p>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Buscar Especialidad en Bioestadística <span class="text-danger">*</span></label>
                        <input type="text" id="buscadorBioestadistica" class="form-control" placeholder="Escriba para filtrar (ej. Cardiología, Pediatría)..." oninput="buscarEspecialidadesBio(this.value)">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Especialidad Canónica <span class="text-danger">*</span></label>
                        <select id="selectEspecialidadBio" class="form-control" size="6" style="font-size: 13px;">
                            <option value="" disabled>Escriba en el buscador para ver coincidencias...</option>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Observación / Motivo de Incorporación (Opcional)</label>
                        <input type="text" id="justificacionAgregar" class="form-control" placeholder="Ej: Incorporada recientemente en terreno...">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="guardarNuevaEspecialidad()">
                        <i class="fa fa-check mr-1"></i> Asignar al Establecimiento
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Finalizar / Firma Digital --}}
    <div class="modal fade" id="modalFinalizar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #10b981 100%);">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-signature mr-2"></i> Cerrar y Sellar Relevamiento
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Estampe su firma digital táctil como constancia de la validación técnica realizada para la Dirección de Hospitales del Área Interior.
                    </p>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Lienzo de Firma Digital</label>
                        <div id="signature-pad" class="signature-pad">
                            <canvas id="canvasFirma"></canvas>
                        </div>
                        <div class="text-right mt-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarFirma()">
                                <i class="fa fa-eraser mr-1"></i> Limpiar Firma
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Notas u Observaciones Generales (Opcional)</label>
                        <textarea id="notasCierre" class="form-control" rows="2" placeholder="Observaciones finales sobre el relevamiento...">{{ $sesion->notas }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Seguir Editando</button>
                    <button type="button" class="btn btn-success font-weight-bold" onclick="guardarFinalizacion()">
                        <i class="fa fa-save mr-1"></i> Guardar y Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        const TOKEN_SESION = '{{ $sesion->token }}';
        let establecimientoActualId = null;
        let signaturePad = null;

        // Configuración de CSRF Token para AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Inicializar Signature Pad al abrir modal
            $('#modalFinalizar').on('shown.bs.modal', function () {
                var canvas = document.getElementById('canvasFirma');
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                }
                resizeCanvas();
                if (!signaturePad) {
                    signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(250, 250, 250)',
                        penColor: 'rgb(15, 23, 42)'
                    });
                }
            });

            // Filtro de búsqueda en la lista de establecimientos
            $('#filtroEstablecimiento').on('input', function() {
                filtrarListaEstablecimientos();
            });

            $('#filtroDepartamento').on('change', function() {
                filtrarListaEstablecimientos();
            });
        });

        function filtrarListaEstablecimientos() {
            const query = $('#filtroEstablecimiento').val().toLowerCase().trim();
            const depto = $('#filtroDepartamento').val();

            $('#listaEstablecimientos .est-item').each(function() {
                const nombre = $(this).data('nombre');
                const estDepto = $(this).data('depto');

                const matchQuery = !query || nombre.includes(query);
                const matchDepto = !depto || estDepto === depto;

                if (matchQuery && matchDepto) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Cargar y seleccionar un establecimiento
        function seleccionarEstablecimiento(id) {
            establecimientoActualId = id;

            $('#listaEstablecimientos .est-item').removeClass('active');
            $(`#listaEstablecimientos .est-item[data-id="${id}"]`).addClass('active');

            $('#workspaceVacio').hide();
            $('#workspaceActivo').show();

            $('#tablaEspecialidadesBody').html(`
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fa fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                        <div>Cargando especialidades médicas desde Bioestadística...</div>
                    </td>
                </tr>
            `);

            $.get(`/riiss/portal-validador/${TOKEN_SESION}/establecimiento/${id}`, function(res) {
                if (res.success) {
                    $('#estNombreTitulo').text(res.establecimiento.nombre);
                    $('#estDeptoBadge').text(res.establecimiento.departamento);
                    $('#estTipologia').text(res.establecimiento.tipologia);
                    $('#estComplejidad').text(res.establecimiento.complejidad);
                    $('#contadorEspecialidades').text(res.especialidades.length);

                    renderizarEspecialidades(res.especialidades);
                }
            }).fail(function() {
                alert('Error al cargar la información del establecimiento.');
            });
        }

        // Renderizar las filas de especialidades
        function renderizarEspecialidades(lista) {
            if (!lista || lista.length === 0) {
                $('#tablaEspecialidadesBody').html(`
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fa fa-stethoscope fa-2x mb-2 text-secondary" style="opacity: 0.5;"></i>
                            <div>No hay especialidades registradas aún para este establecimiento.</div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2 font-weight-bold" onclick="abrirModalAgregar()">
                                <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad desde Bioestadística
                            </button>
                        </td>
                    </tr>
                `);
                return;
            }

            let html = '';
            lista.forEach((esp, idx) => {
                const isActiva = (esp.estado === 'activa');
                const rowClass = isActiva ? '' : 'table-light';

                html += `
                    <tr id="row-esp-${esp.especialidad_id}" class="${rowClass}">
                        <td class="text-center font-weight-bold text-muted" style="font-size: 11.5px;">
                            #${esp.especialidad_id}
                        </td>
                        <td>
                            <div class="font-weight-600 text-dark">
                                ${esp.nombre}
                            </div>
                            ${esp.es_agregada ? '<span class="badge badge-warning text-dark font-weight-bold" style="font-size: 9.5px;"><i class="fa fa-plus mr-1"></i> Agregada en Relevamiento</span>' : ''}
                        </td>
                        <td class="text-center">
                            <div class="custom-control custom-switch custom-switch-lg d-inline-block">
                                <input type="checkbox" 
                                       class="custom-control-input switch-estado" 
                                       id="switch-esp-${esp.especialidad_id}" 
                                       data-id="${esp.especialidad_id}" 
                                       ${isActiva ? 'checked' : ''} 
                                       onchange="cambiarEstadoEspecialidad(${esp.especialidad_id}, this.checked)">
                                <label class="custom-control-label font-weight-bold ${isActiva ? 'text-success' : 'text-danger'}" 
                                       for="switch-esp-${esp.especialidad_id}" 
                                       id="label-esp-${esp.especialidad_id}">
                                    ${isActiva ? '✔ Activa' : '✖ Inactiva'}
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="text" 
                                   class="input-justificacion" 
                                   id="just-esp-${esp.especialidad_id}" 
                                   data-id="${esp.especialidad_id}" 
                                   value="${esp.justificacion ? escapeHtml(esp.justificacion) : ''}" 
                                   placeholder="Justificación / Observación técnica (opcional)..." 
                                   onblur="guardarJustificacion(${esp.especialidad_id}, this.value)">
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success badge-save" id="badge-save-${esp.especialidad_id}">
                                <i class="fa fa-check"></i>
                            </span>
                        </td>
                    </tr>
                `;
            });

            $('#tablaEspecialidadesBody').html(html);
        }

        // Cambiar estado Activa / Inactiva con guardado automático inmediato
        function cambiarEstadoEspecialidad(especialidadId, checked) {
            const estado = checked ? 'activa' : 'inactiva';
            const justificacion = $(`#just-esp-${especialidadId}`).val();

            const $label = $(`#label-esp-${especialidadId}`);
            if (checked) {
                $label.removeClass('text-danger').addClass('text-success').text('✔ Activa');
                $(`#row-esp-${especialidadId}`).removeClass('table-light');
            } else {
                $label.removeClass('text-success').addClass('text-danger').text('✖ Inactiva');
                $(`#row-esp-${especialidadId}`).addClass('table-light');
            }

            enviarActualizacion(especialidadId, estado, justificacion);
        }

        // Guardar justificación opcional al salir del campo
        function guardarJustificacion(especialidadId, justificacion) {
            const checked = $(`#switch-esp-${especialidadId}`).is(':checked');
            const estado = checked ? 'activa' : 'inactiva';

            enviarActualizacion(especialidadId, estado, justificacion);
        }

        // AJAX para guardar
        function enviarActualizacion(especialidadId, estado, justificacion) {
            if (!establecimientoActualId) return;

            $.post(`/riiss/portal-validador/${TOKEN_SESION}/actualizar-especialidad`, {
                establecimiento_id: establecimientoActualId,
                especialidad_id: especialidadId,
                estado: estado,
                justificacion: justificacion
            }, function(res) {
                if (res.success) {
                    mostrarFeedbackGuardado(especialidadId);
                    actualizarBadgeEstablecimiento(establecimientoActualId);
                }
            });
        }

        function mostrarFeedbackGuardado(especialidadId) {
            const $badge = $(`#badge-save-${especialidadId}`);
            $badge.addClass('show');
            setTimeout(function() {
                $badge.removeClass('show');
            }, 1800);
        }

        function actualizarBadgeEstablecimiento(estId) {
            let totalActivas = 0;
            let total = 0;
            $('#tablaEspecialidadesBody .switch-estado').each(function() {
                total++;
                if ($(this).is(':checked')) totalActivas++;
            });

            $(`#badge-est-${estId}`)
                .removeClass('badge-light border')
                .addClass('badge-primary')
                .text(`${totalActivas} activas`);
        }

        // Modal para agregar especialidad
        function abrirModalAgregar() {
            if (!establecimientoActualId) {
                alert('Seleccione primero un establecimiento.');
                return;
            }
            $('#buscadorBioestadistica').val('');
            $('#justificacionAgregar').val('');
            buscarEspecialidadesBio('');
            $('#modalAgregarEspecialidad').modal('show');
        }

        let debounceBuscarBio = null;
        function buscarEspecialidadesBio(query) {
            clearTimeout(debounceBuscarBio);
            debounceBuscarBio = setTimeout(function() {
                $.get(`/riiss/portal-validador/${TOKEN_SESION}/catalogo-bioestadistica?q=${encodeURIComponent(query)}`, function(res) {
                    if (res.success) {
                        let opts = '';
                        if (res.items.length === 0) {
                            opts = '<option value="" disabled>No se encontraron especialidades en Bioestadística</option>';
                        } else {
                            res.items.forEach(it => {
                                opts += `<option value="${it.id}">#${it.id} — ${it.nombre}</option>`;
                            });
                        }
                        $('#selectEspecialidadBio').html(opts);
                    }
                });
            }, 250);
        }

        function guardarNuevaEspecialidad() {
            const especialidadId = $('#selectEspecialidadBio').val();
            const justificacion = $('#justificacionAgregar').val();

            if (!especialidadId) {
                alert('Por favor seleccione una especialidad de la lista.');
                return;
            }

            $.post(`/riiss/portal-validador/${TOKEN_SESION}/agregar-especialidad`, {
                establecimiento_id: establecimientoActualId,
                especialidad_id: especialidadId,
                justificacion: justificacion
            }, function(res) {
                if (res.success) {
                    $('#modalAgregarEspecialidad').modal('hide');
                    seleccionarEstablecimiento(establecimientoActualId);
                }
            }).fail(function(err) {
                alert('Error al agregar la especialidad.');
            });
        }

        // Firma y cierre
        function limpiarFirma() {
            if (signaturePad) signaturePad.clear();
        }

        function guardarFinalizacion() {
            let firmaData = null;
            if (signaturePad && !signaturePad.isEmpty()) {
                firmaData = signaturePad.toDataURL('image/png');
            }

            const notas = $('#notasCierre').val();

            $.post(`/riiss/portal-validador/${TOKEN_SESION}/finalizar`, {
                firma_base64: firmaData,
                notas: notas
            }, function(res) {
                if (res.success) {
                    alert('¡Relevamiento finalizado y sellado con éxito!');
                    $('#modalFinalizar').modal('hide');
                    window.location.reload();
                }
            });
        }

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
</body>
</html>
