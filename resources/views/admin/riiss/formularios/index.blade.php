@extends('layouts.master')
@section('title', 'Gestor de Formularios y Cartera de Servicios RIISS')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
    :root {
        --dim-cartera: #0284c7;
        --dim-infra: #d97706;
        --dim-talento: #7c3aed;
        --dim-medicamentos: #0d9488;
        --dim-gobernanza: #475569;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* KPI Dimension Cards */
    .dim-kpi-card {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        padding: 14px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .dim-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }
    .dim-kpi-card.active {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .dim-kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 4px;
    }
    .kpi-cartera::before { background: var(--dim-cartera); }
    .kpi-infra::before { background: var(--dim-infra); }
    .kpi-talento::before { background: var(--dim-talento); }
    .kpi-medicamentos::before { background: var(--dim-medicamentos); }
    .kpi-gobernanza::before { background: var(--dim-gobernanza); }

    /* Tipología Buttons */
    .tipologia-pill {
        border: 1.5px solid #cbd5e1;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .tipologia-pill:hover {
        border-color: #0284c7;
        color: #0284c7;
        background: #f8fafc;
    }
    .tipologia-pill.active {
        border-color: #0284c7;
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }

    /* Split Workspace Layout */
    .builder-layout {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 1100px) {
        .builder-layout { grid-template-columns: 1fr; }
    }

    /* Left Pane: Bank of Questions */
    .bank-pane {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
    }
    .bank-scroll-area {
        overflow-y: auto;
        flex: 1;
        padding-right: 4px;
        margin-top: 12px;
    }

    /* Right Pane: Active Form Builder */
    .form-canvas-pane {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }

    /* Drag & Drop Cards */
    .question-drag-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 8px;
        cursor: grab;
        transition: all 0.15s ease;
        position: relative;
    }
    .question-drag-item:hover {
        border-color: #38bdf8;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.12);
        background: #fafcff;
    }
    .question-drag-item:active { cursor: grabbing; }
    .sortable-ghost {
        opacity: 0.4;
        background: #e0f2fe !important;
        border: 2px dashed #0284c7 !important;
    }
    .sortable-chosen {
        background: #f0f9ff;
        box-shadow: 0 8px 24px rgba(2, 132, 199, 0.15);
    }

    /* Section Accordion Card */
    .seccion-accordion-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .seccion-accordion-card:hover {
        border-color: #cbd5e1;
    }
    .seccion-header-bar {
        background: #f8fafc;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        border-bottom: 1px solid #e2e8f0;
    }
    .seccion-header-bar:hover {
        background: #f1f5f9;
    }
    .questions-dropzone {
        min-height: 48px;
        padding: 12px 16px;
        background: #ffffff;
    }
    .questions-dropzone.empty-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        margin: 10px 16px;
        padding: 16px;
        text-align: center;
        color: #94a3b8;
        font-size: 0.82rem;
    }

    /* Badges */
    .badge-dim-cartera { background: #e0f2fe; color: #0369a1; }
    .badge-dim-infra { background: #fef3c7; color: #b45309; }
    .badge-dim-talento { background: #f3e8ff; color: #6b21a8; }
    .badge-dim-medicamentos { background: #ccfbf1; color: #0f766e; }
    .badge-dim-gobernanza { background: #f1f5f9; color: #334155; }

    .grade-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #dbeafe;
    }
</style>
@endpush

@section('content')
<div class="card">
    {{-- Header Principal --}}
    <div class="card-header card-header-info d-flex align-items-center justify-content-between flex-wrap" style="background: linear-gradient(135deg, #0284c7, #0369a1); border-radius: 12px; box-shadow: 0 10px 24px rgba(2, 132, 199, 0.2);">
        <div>
            <h4 class="card-title text-white font-weight-bold mb-0">
                <i class="fa fa-cubes-stacked mr-2"></i> Gestor de Formularios y Cartera de Servicios RIISS
            </h4>
            <p class="card-category text-white-75 mb-0" style="font-size: 0.88rem;">
                Reorganización y empaquetado Drag & Drop por Dimensiones Estratégicas y Grados de Complejidad
            </p>
        </div>
        <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 8px;">
            <button type="button" class="btn btn-sm btn-light font-weight-bold shadow-xs text-dark" onclick="abrirModalNuevaSeccion()">
                <i class="fa fa-folder-plus text-primary mr-1"></i> + Nueva Sección
            </button>
            <button type="button" class="btn btn-sm btn-light font-weight-bold shadow-xs text-dark" onclick="abrirModalNuevaPregunta()">
                <i class="fa fa-circle-question text-success mr-1"></i> + Nueva Pregunta
            </button>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Gestor de Formularios por Dimensión (Drag & Drop)</li>
        </ol>
    </nav>

    <div class="card-body p-4">

        {{-- 1. Tarjetas de Dimensiones Estratégicas (KPI / Filtros Rápidos) --}}
        <div class="row mb-4">
            {{-- Todas las dimensiones --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card active" onclick="filtrarPorDimension('all', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">Todas</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: #0f2744;">{{ $conteos['total_preguntas'] }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['total_secciones'] }} Secciones</small>
                        </div>
                        <i class="fa fa-layer-group fa-lg text-secondary opacity-50"></i>
                    </div>
                </div>
            </div>

            {{-- 1. Cartera de Servicios --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-cartera" onclick="filtrarPorDimension('cartera_servicios', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">1. Cartera</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-cartera);">{{ $conteos['por_dimension']['cartera_servicios']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['cartera_servicios']['secciones'] ?? 0 }} Especialidades</small>
                        </div>
                        <i class="fa fa-stethoscope fa-lg" style="color: var(--dim-cartera); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 2. Infraestructura --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-infra" onclick="filtrarPorDimension('infraestructura', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">2. Infraestructura</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-infra);">{{ $conteos['por_dimension']['infraestructura']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['infraestructura']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-building fa-lg" style="color: var(--dim-infra); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 3. Talento Humano --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-talento" onclick="filtrarPorDimension('talento_humano', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">3. Talento Humano</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-talento);">{{ $conteos['por_dimension']['talento_humano']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['talento_humano']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-users-gear fa-lg" style="color: var(--dim-talento); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 4. Medicamentos e Insumos --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-medicamentos" onclick="filtrarPorDimension('medicamentos_insumos', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">4. Medicamentos</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-medicamentos);">{{ $conteos['por_dimension']['medicamentos_insumos']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['medicamentos_insumos']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-pills fa-lg" style="color: var(--dim-medicamentos); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>

            {{-- 5. Gobernanza --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-2">
                <div class="dim-kpi-card kpi-gobernanza" onclick="filtrarPorDimension('gobernanza_procesos', this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold text-uppercase" style="font-size: 10px;">5. Gobernanza</small>
                            <div class="h5 font-weight-bold mb-0 mt-1" style="color: var(--dim-gobernanza);">{{ $conteos['por_dimension']['gobernanza_procesos']['preguntas'] ?? 0 }}</div>
                            <small class="text-muted" style="font-size: 11px;">{{ $conteos['por_dimension']['gobernanza_procesos']['secciones'] ?? 0 }} Secciones</small>
                        </div>
                        <i class="fa fa-file-shield fa-lg" style="color: var(--dim-gobernanza); opacity: 0.6;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Filtro por Tipología / Nivel Asistencial --}}
        <div class="p-3 mb-4 rounded-lg border bg-light d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                <span class="font-weight-bold text-dark small mr-2">
                    <i class="fa fa-hospital-user text-primary mr-1"></i> Tipología Asignada:
                </span>
                <button class="tipologia-pill active" onclick="filtrarPorTipologia('', this)">
                    Todas las Tipologías
                </button>
                @foreach($tipologias as $tip)
                    <button class="tipologia-pill" onclick="filtrarPorTipologia('{{ $tip }}', this)">
                        {{ $tip }}
                    </button>
                @endforeach
            </div>

            <div class="d-flex align-items-center" style="gap: 8px;">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0"><i class="fa fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="inputBuscarPreguntas" class="form-control border-left-0" placeholder="Buscar pregunta o servicio...">
                </div>
            </div>
        </div>

        {{-- 3. Layout Split: Banco Izquierdo vs Formulario Activo Derecho --}}
        <div class="builder-layout">

            {{-- ── PANEL IZQUIERDO: BANCO DE PREGUNTAS Y SERVICIOS ── --}}
            <div class="bank-pane">
                <div class="d-flex align-items-center justify-content-between pb-2 border-bottom">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">
                            <i class="fa fa-boxes-stacked text-primary mr-1"></i> Banco de Preguntas
                        </h6>
                        <small class="text-muted" id="bancoTotalCount">Cargando catálogo...</small>
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-primary" onclick="recargarBancoPreguntas()" title="Recargar catálogo">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>

                {{-- Filtro de Dimensión del Banco (Independiente del formulario activo a la derecha) --}}
                <div class="mt-2">
                    <label class="small font-weight-bold text-muted mb-1 d-flex justify-content-between align-items-center">
                        <span><i class="fa fa-filter mr-1"></i> Catálogo de origen:</span>
                    </label>
                    <select id="selectDimensionBanco" class="form-control form-control-sm font-weight-bold" onchange="cambiarFiltroBancoDimension()">
                        <option value="all">🌐 Todas las Dimensiones (Catálogo General)</option>
                        <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                        <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                        <option value="talento_humano">👥 Talento Humano</option>
                        <option value="medicamentos_insumos">💊 Medicamentos e Insumos</option>
                        <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                    </select>
                </div>

                {{-- Buscador en vivo en el Banco --}}
                <div class="mt-2">
                    <div class="input-group input-group-sm">
                        <input type="text" id="inputBuscarBanco" class="form-control" placeholder="🔍 Buscar por palabra clave...">
                        <div class="input-group-append" id="btnLimpiarBuscarBanco" style="display: none;">
                            <button class="btn btn-outline-secondary" type="button" onclick="limpiarBuscarBanco()">&times;</button>
                        </div>
                    </div>
                </div>

                {{-- Opciones Rápidas: Solo Evaluadas & Modo Arrastre --}}
                <div class="mt-2 p-2 bg-light rounded border" style="font-size: 11px;">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="checkSoloEvaluadas" onchange="cambiarFiltroSoloEvaluadas()">
                        <label class="custom-control-label font-weight-600 text-dark" for="checkSoloEvaluadas" title="Muestra preguntas que ya fueron respondidas en visitas in situ anteriores">
                            ⭐ Respondidas en visitas previas
                        </label>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-1 border-top mt-1">
                        <span class="text-muted font-weight-bold">Al arrastrar:</span>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <div class="custom-control custom-radio custom-control-inline mb-0 mr-0">
                                <input type="radio" id="modoArrastreCopiar" name="modoArrastre" value="copiar" class="custom-control-input" checked>
                                <label class="custom-control-label" for="modoArrastreCopiar" title="Duplica/reutiliza la pregunta en la sección destino sin borrar la original">Reutilizar</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline mb-0 mr-0">
                                <input type="radio" id="modoArrastreMover" name="modoArrastre" value="mover" class="custom-control-input">
                                <label class="custom-control-label" for="modoArrastreMover" title="Mueve la pregunta original a la sección destino">Mover</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bank-scroll-area" id="bancoPreguntasContainer">
                    <div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                </div>

                <div id="bancoCargarMasContainer" class="text-center pt-2" style="display: none;">
                    <button type="button" class="btn btn-xs btn-light border btn-block text-muted font-weight-bold" onclick="cargarMasBancoPreguntas()">
                        <i class="fa fa-chevron-down mr-1"></i> Cargar más preguntas...
                    </button>
                </div>

                <div class="pt-2 border-top text-center">
                    <small class="text-muted" style="font-size: 11px;">
                        <i class="fa fa-hand-pointer mr-1"></i> Arrastre una pregunta o use el botón <strong>+ Vincular</strong>.
                    </small>
                </div>
            </div>

            {{-- ── PANEL DERECHO: CONSTRUCTOR DE FORMULARIO POR DIMENSIÓN Y SECCIÓN ── --}}
            <div class="form-canvas-pane">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom flex-wrap" style="gap: 10px;">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fa fa-wpforms text-info mr-2"></i> Estructura del Formulario de Visita In Situ
                        </h5>
                        <small class="text-muted">
                            Secciones y preguntas activas organizadas por dimensión para auditoría en terreno
                        </small>
                    </div>

                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <span id="saveStatusIndicator" class="badge badge-light border px-2 py-1 small text-muted" style="display: none;">
                            <i class="fa fa-check text-success mr-1"></i> Cambios guardados
                        </span>
                    </div>
                </div>

                {{-- Contenedor de Secciones y Dropzones --}}
                <div id="seccionesCanvasContainer">
                    <div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-3x"></i><br>Cargando estructura...</div>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════════════ --}}
{{-- MODALES: CREAR/EDITAR PREGUNTA, CREAR SECCIÓN, VINCULAR PREGUNTA --}}
{{-- ═══════════════════════════════════════════════════════════════════════════════ --}}

{{-- Modal Vincular / Reutilizar Pregunta en Sección --}}
<div class="modal fade" id="modalVincularPregunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-link mr-2"></i> Vincular / Reutilizar Pregunta en Sección
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formVincularPregunta" onsubmit="guardarVinculacionPregunta(event)">
                <input type="hidden" id="vincularPregId">
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded border mb-3">
                        <small class="text-muted font-weight-bold text-uppercase d-block mb-1">Pregunta Seleccionada:</small>
                        <div id="vincularPregTexto" class="font-weight-600 text-dark small"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Dimensión de Destino <span class="text-danger">*</span></label>
                        <select id="vincularDimension" class="form-control" onchange="actualizarSeccionesModalVincular()">
                            <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                            <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                            <option value="talento_humano">👥 Talento Humano</option>
                            <option value="medicamentos_insumos">💊 Medicamentos, Insumos y Equipamiento</option>
                            <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Sección de Destino <span class="text-danger">*</span></label>
                        <select id="vincularSeccionId" class="form-control" required>
                            {{-- Poblado dinámicamente --}}
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold small d-block">Acción a Realizar</label>
                        <div class="d-flex align-items-center" style="gap: 15px;">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="vincularModoCopiar" name="vincularModo" value="copiar" class="custom-control-input" checked>
                                <label class="custom-control-label small" for="vincularModoCopiar"><strong>Reutilizar / Duplicar</strong> (conserva la original)</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="vincularModoMover" name="vincularModo" value="mover" class="custom-control-input">
                                <label class="custom-control-label small" for="vincularModoMover"><strong>Mover</strong> (cambia de sección)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background:#0284c7; border:none;">
                        <i class="fa fa-link mr-1"></i> Vincular a Sección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Crear Pregunta --}}
<div class="modal fade" id="modalCrearPregunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-info text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-circle-question mr-2"></i> Agregar Nueva Pregunta al Formulario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formCrearPregunta" onsubmit="guardarNuevaPregunta(event)">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small">Dimensión Estratégica <span class="text-danger">*</span></label>
                            <select id="nuevaPregDimension" class="form-control" required onchange="actualizarSeccionesModal('nueva')">
                                <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                                <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                                <option value="talento_humano">👥 Talento Humano</option>
                                <option value="medicamentos_insumos">💊 Medicamentos, Insumos y Equipamiento</option>
                                <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold small">Sección de Destino <span class="text-danger">*</span></label>
                            <select id="nuevaPregSeccionId" class="form-control" required>
                                {{-- Cargado dinámicamente --}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Enunciado / Pregunta del Formulario <span class="text-danger">*</span></label>
                        <textarea id="nuevaPregTexto" class="form-control" rows="2" required placeholder="Ej: ¿Dispone de consultorio exclusivo para atención cardiológica con electrocardiógrafo?"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Tipo de Respuesta</label>
                            <select id="nuevaPregTipo" class="form-control">
                                <option value="si_no_na" selected>Sí / No / No Aplica</option>
                                <option value="si_no">Sí / No</option>
                                <option value="texto">Texto Libre</option>
                                <option value="numero">Valor Numérico</option>
                                <option value="checklist">Lista de Verificación (Checklist)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Complejidad Mínima Requerida</label>
                            <select id="nuevaPregComplejidad" class="form-control">
                                <option value="1">Grado 1 (Puesto Sanitario)</option>
                                <option value="2">Grado 2 (Clínica Periférica)</option>
                                <option value="3">Grado 3 (Unidad Sanitaria)</option>
                                <option value="4">Grado 4 (Hospital Regional)</option>
                                <option value="5">Grado 5 (Hospital Interregional)</option>
                                <option value="6">Grado 6 (Hospital Especializado)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Ponderación (Peso)</label>
                            <input type="number" id="nuevaPregPeso" class="form-control" value="1.00" step="0.1" min="0.1" max="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background:#0284c7; border:none;">
                        <i class="fa fa-save mr-1"></i> Guardar Pregunta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar Pregunta --}}
<div class="modal fade" id="modalEditarPregunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-edit mr-2"></i> Editar Pregunta del Formulario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formEditarPregunta" onsubmit="guardarEdicionPregunta(event)">
                <input type="hidden" id="editPregId">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Enunciado de la Pregunta <span class="text-danger">*</span></label>
                        <textarea id="editPregTexto" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Tipo de Respuesta</label>
                            <select id="editPregTipo" class="form-control">
                                <option value="si_no_na">Sí / No / No Aplica</option>
                                <option value="si_no">Sí / No</option>
                                <option value="texto">Texto Libre</option>
                                <option value="numero">Valor Numérico</option>
                                <option value="checklist">Lista de Verificación</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Complejidad Mínima</label>
                            <select id="editPregComplejidad" class="form-control">
                                <option value="1">Grado 1 (Puesto Sanitario)</option>
                                <option value="2">Grado 2 (Clínica Periférica)</option>
                                <option value="3">Grado 3 (Unidad Sanitaria)</option>
                                <option value="4">Grado 4 (Hospital Regional)</option>
                                <option value="5">Grado 5 (Hospital Interregional)</option>
                                <option value="6">Grado 6 (Hospital Especializado)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold small">Ponderación</label>
                            <input type="number" id="editPregPeso" class="form-control" step="0.1" min="0.1" max="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Crear Sección --}}
<div class="modal fade" id="modalCrearSeccion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-info text-white" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title font-weight-bold text-white mb-0">
                    <i class="fa fa-folder-plus mr-2"></i> Crear Nueva Sección
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formCrearSeccion" onsubmit="guardarNuevaSeccion(event)">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Dimensión <span class="text-danger">*</span></label>
                        <select id="nuevaSecDimension" class="form-control" required>
                            <option value="cartera_servicios">🏥 Cartera de Servicios</option>
                            <option value="infraestructura">🏗️ Infraestructura e Instalaciones</option>
                            <option value="talento_humano">👥 Talento Humano</option>
                            <option value="medicamentos_insumos">💊 Medicamentos, Insumos y Equipamiento</option>
                            <option value="gobernanza_procesos">📋 Gobernanza y Documentación</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Nombre de la Sección (Grupo Principal) <span class="text-danger">*</span></label>
                        <input type="text" id="nuevaSecNombre" class="form-control" required placeholder="Ej: Consultas de Especialidades Médicas">
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small">Sub-Sección / Detalle</label>
                        <input type="text" id="nuevaSecSubNombre" class="form-control" placeholder="Ej: Cardiología y Métodos Auxiliares">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" style="background:#0284c7; border:none;">
                        <i class="fa fa-save mr-1"></i> Crear Sección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Sortable.js para Drag & Drop fluido -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
let _currentDimension = 'all';
let _currentTipologia = '';
let _currentBuscar = '';
let _seccionesData = [];
let _sortableInstances = [];
let _bankSortable = null;

// Variables de estado del Banco Izquierdo
let _bancoDimension = 'all';
let _bancoBuscar = '';
let _bancoSoloEvaluadas = false;
let _bancoOffset = 0;
const _bancoLimit = 40;
let _bancoTotal = 0;
let _todasLasSeccionesCache = [];

$(document).ready(function() {
    cargarEstructuraFormulario();
    cargarBancoPreguntas(true);
    cargarTodasLasSeccionesParaModales();

    // Búsqueda en vivo del canvas con debounce
    let timerBuscar = null;
    $('#inputBuscarPreguntas').on('input', function() {
        clearTimeout(timerBuscar);
        _currentBuscar = $(this).val().trim();
        timerBuscar = setTimeout(() => {
            cargarEstructuraFormulario();
        }, 300);
    });

    // Búsqueda en vivo del banco con debounce
    let timerBanco = null;
    $('#inputBuscarBanco').on('input', function() {
        clearTimeout(timerBanco);
        _bancoBuscar = $(this).val().trim();
        $('#btnLimpiarBuscarBanco').toggle(_bancoBuscar.length > 0);
        timerBanco = setTimeout(() => {
            cargarBancoPreguntas(true);
        }, 300);
    });
});

// ── Filtros por Dimensión (Canvas Derecho) ──
function filtrarPorDimension(dim, el) {
    $('.dim-kpi-card').removeClass('active');
    $(el).addClass('active');
    _currentDimension = dim;
    cargarEstructuraFormulario();
}

// ── Filtro por Tipología (Canvas Derecho) ──
function filtrarPorTipologia(tip, el) {
    $('.tipologia-pill').removeClass('active');
    $(el).addClass('active');
    _currentTipologia = tip;
    cargarEstructuraFormulario();
}

// ── Cargar Estructura del Formulario (Canvas Derecho) ──
function cargarEstructuraFormulario() {
    const $container = $('#seccionesCanvasContainer');
    $container.html('<div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i><br><small>Cargando secciones...</small></div>');

    // Destruir instancias previas de Sortable
    _sortableInstances.forEach(s => s.destroy());
    _sortableInstances = [];

    $.get('{{ route("riiss.formularios.datos") }}', {
        dimension: _currentDimension,
        tipologia: _currentTipologia,
        buscar: _currentBuscar
    }, function(res) {
        if (!res.ok || !res.data || res.data.length === 0) {
            $container.html(`
                <div class="text-center py-5 text-muted bg-light rounded border">
                    <i class="fa fa-folder-open fa-3x mb-2 text-secondary opacity-50"></i>
                    <p class="font-weight-bold mb-1">No se encontraron secciones para los filtros seleccionados.</p>
                    <small>Intente cambiar de dimensión o quitar los filtros de búsqueda.</small>
                </div>
            `);
            return;
        }

        _seccionesData = res.data;
        let html = '';

        res.data.forEach((sec) => {
            const dimClass = 'badge-dim-' + (sec.dimension || 'cartera_servicios').replace('_', '-');
            const dimInfo = sec.dimension_info || { nombre: 'Cartera', icono: 'fa-stethoscope', color: '#0284c7' };

            html += `
                <div class="seccion-accordion-card" data-seccion-id="${sec.id}" data-dimension="${sec.dimension}">
                    <div class="seccion-header-bar" onclick="toggleSeccionAccordion(${sec.id})">
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <span class="badge ${dimClass} font-weight-bold px-2 py-1" style="font-size: 11px;">
                                <i class="fa ${dimInfo.icono} mr-1"></i> ${dimInfo.nombre}
                            </span>
                            <div>
                                <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">${sec.nombre_completo}</span>
                                <small class="text-muted ml-2 count-sec-${sec.id}">(${sec.preguntas.length} preguntas)</small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <button type="button" class="btn btn-xs btn-outline-secondary" onclick="event.stopPropagation(); abrirModalNuevaPreguntaSeccion(${sec.id}, '${sec.dimension}')" title="Agregar pregunta a esta sección">
                                <i class="fa fa-plus text-success mr-1"></i> Pregunta
                            </button>
                            <i class="fa fa-chevron-down text-muted accordion-arrow-${sec.id}"></i>
                        </div>
                    </div>

                    <div class="seccion-accordion-body" id="seccionBody-${sec.id}">
                        <div class="questions-dropzone ${sec.preguntas.length === 0 ? 'empty-zone' : ''}" id="dropzoneSeccion-${sec.id}" data-seccion-id="${sec.id}" data-dimension="${sec.dimension}">
                            ${sec.preguntas.length === 0 ? '<div class="text-muted text-center py-2 empty-placeholder"><i class="fa fa-hand-pointer mr-1"></i> Zona vacía: Arrastre preguntas aquí</div>' : ''}
                            ${sec.preguntas.map(p => renderPreguntaItem(p)).join('')}
                        </div>
                    </div>
                </div>
            `;
        });

        $container.html(html);

        // Inicializar Sortable.js en cada dropzone
        res.data.forEach(sec => {
            const el = document.getElementById(`dropzoneSeccion-${sec.id}`);
            if (el) {
                const sortable = new Sortable(el, {
                    group: 'riiss-questions-group',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    handle: '.question-drag-item',
                    onAdd: function(evt) {
                        // Pregunta soltada desde el Banco Izquierdo o desde otra sección
                        const itemEl = evt.item;
                        const pId = $(itemEl).data('id');
                        const targetSecId = sec.id;
                        const modo = $('input[name="modoArrastre"]:checked').val() || 'copiar';
                        const newIndex = evt.newIndex + 1;

                        $(el).find('.empty-placeholder').remove();
                        $(el).removeClass('empty-zone');

                        $(itemEl).html('<div class="text-center py-2 text-primary small"><i class="fa fa-spinner fa-spin mr-1"></i> Vinculando pregunta...</div>');

                        $.post('{{ route("riiss.formularios.vincular-pregunta") }}', {
                            _token: '{{ csrf_token() }}',
                            pregunta_id: pId,
                            formulario_seccion_id: targetSecId,
                            modo: modo,
                            orden: newIndex
                        }, function(resp) {
                            if (resp.ok) {
                                const newHtml = renderPreguntaItem(resp.pregunta);
                                $(itemEl).replaceWith(newHtml);

                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: resp.message,
                                    showConfirmButton: false,
                                    timer: 2200
                                });

                                actualizarContadorSeccion(targetSecId);

                                if (modo === 'mover') {
                                    cargarBancoPreguntas(false);
                                }
                                guardarReordenamientoPreguntas(el);
                            }
                        }).fail(function(xhr) {
                            $(itemEl).remove();
                            Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo vincular la pregunta.', 'error');
                        });
                    },
                    onEnd: function(evt) {
                        if (evt.from !== document.getElementById('bancoPreguntasContainer')) {
                            guardarReordenamientoPreguntas(evt.to);
                            if (evt.from !== evt.to) {
                                guardarReordenamientoPreguntas(evt.from);
                                const fromSecId = $(evt.from).data('seccion-id');
                                if (fromSecId) actualizarContadorSeccion(fromSecId);
                                actualizarContadorSeccion(sec.id);
                            }
                        }
                    }
                });
                _sortableInstances.push(sortable);
            }
        });
    });
}

// ── Renderizar Item de Pregunta en el Canvas Derecho ──
function renderPreguntaItem(p) {
    return `
        <div class="question-drag-item" data-id="${p.id}" data-dimension="${p.dimension}">
            <div class="d-flex align-items-start justify-content-between" style="gap: 12px;">
                <div class="d-flex align-items-start" style="gap: 10px; flex: 1;">
                    <i class="fa fa-grip-vertical text-muted mt-1" style="cursor: grab; opacity: 0.6;"></i>
                    <div style="flex: 1;">
                        <div class="text-dark font-weight-500" style="font-size: 0.88rem; line-height: 1.4;">
                            ${p.pregunta}
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-1" style="gap: 6px;">
                            <span class="grade-badge"><i class="fa fa-layer-group mr-1"></i> Grado ${p.grado_complejidad_min || 1}+</span>
                            <span class="badge badge-light border text-muted" style="font-size: 10.5px;">Tipo: ${p.tipo_respuesta}</span>
                            ${p.servicio_cartera_grupo ? `<span class="badge badge-light border text-primary" style="font-size: 10.5px;"><i class="fa fa-tag mr-1"></i>${p.servicio_cartera_grupo}</span>` : ''}
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center" style="gap: 4px;">
                    <button type="button" class="btn btn-xs btn-outline-info" onclick="abrirModalEditarPregunta(${p.id}, '${escapeHtml(p.pregunta)}', '${p.tipo_respuesta}', ${p.grado_complejidad_min || 1})" title="Editar">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary" onclick="duplicarPreguntaAjax(${p.id})" title="Duplicar">
                        <i class="fa fa-copy"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-danger" onclick="eliminarPreguntaAjax(${p.id})" title="Desactivar">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// ── Cargar Banco de Preguntas (Panel Izquierdo - Catálogo Universal) ──
function cargarBancoPreguntas(reset = true) {
    const $container = $('#bancoPreguntasContainer');
    if (reset) {
        _bancoOffset = 0;
        $container.html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i><br><small>Cargando banco...</small></div>');
    }

    $.get('{{ route("riiss.formularios.banco-preguntas") }}', {
        dimension: _bancoDimension,
        buscar: _bancoBuscar,
        solo_evaluadas: _bancoSoloEvaluadas,
        offset: _bancoOffset,
        limite: _bancoLimit
    }, function(res) {
        if (!res.ok) {
            $container.html('<div class="text-center py-4 text-danger small">Error al cargar preguntas</div>');
            return;
        }

        _bancoTotal = res.total;
        $('#bancoTotalCount').text(`${res.total} disponibles`);

        if (res.data.length === 0 && reset) {
            $container.html('<div class="text-center py-4 text-muted"><i class="fa fa-inbox fa-2x mb-2 text-secondary opacity-50"></i><br><small>No hay preguntas coincidentes en el banco.</small></div>');
            $('#bancoCargarMasContainer').hide();
            return;
        }

        let html = '';
        res.data.forEach(p => {
            const badgeDimClass = 'badge-dim-' + (p.dimension || 'cartera_servicios').replace('_', '-');
            const badgeText = p.dimension_info?.nombre || 'Cartera';

            html += `
                <div class="question-drag-item mb-2 bank-item-card" data-id="${p.id}" data-dimension="${p.dimension}" style="background:#ffffff; border: 1.5px solid #e2e8f0; border-left: 4px solid ${p.dimension_info?.color || '#0284c7'}; border-radius: 8px; padding: 10px 12px; transition: all 0.15s ease;">
                    <div class="d-flex align-items-start justify-content-between mb-1" style="gap: 8px;">
                        <div class="font-weight-600 text-dark" style="font-size: 0.84rem; line-height: 1.35;">
                            <i class="fa fa-grip-vertical text-muted mr-1" style="cursor: grab; opacity: 0.6;"></i> ${p.pregunta}
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-1" style="gap: 4px;">
                        <div class="d-flex align-items-center flex-wrap" style="gap: 4px;">
                            <span class="badge ${badgeDimClass}" style="font-size: 10px;">${badgeText}</span>
                            <small class="text-muted text-truncate" style="max-width: 140px; font-size: 10.5px;" title="${p.seccion_nombre}"><i class="fa fa-folder-open mr-1"></i>${p.seccion_nombre}</small>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 4px;">
                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 font-weight-bold" onclick="abrirModalVincular(${p.id}, '${escapeHtml(p.pregunta)}', '${p.dimension}')" title="Vincular / Reutilizar en una sección">
                                <i class="fa fa-link mr-1"></i> + Vincular
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        if (reset) {
            $container.html(html);
        } else {
            $container.append(html);
        }

        // Mostrar / Ocultar botón Cargar más
        $('#bancoCargarMasContainer').toggle(res.has_more);

        // Inicializar o renovar Sortable en el Banco
        if (_bankSortable) {
            _bankSortable.destroy();
        }
        const bankEl = document.getElementById('bancoPreguntasContainer');
        if (bankEl) {
            _bankSortable = new Sortable(bankEl, {
                group: {
                    name: 'riiss-questions-group',
                    pull: 'clone',
                    put: false
                },
                animation: 150,
                sort: false,
                handle: '.bank-item-card'
            });
        }
    });
}

function cambiarFiltroBancoDimension() {
    _bancoDimension = $('#selectDimensionBanco').val();
    cargarBancoPreguntas(true);
}

function cambiarFiltroSoloEvaluadas() {
    _bancoSoloEvaluadas = $('#checkSoloEvaluadas').is(':checked');
    cargarBancoPreguntas(true);
}

function limpiarBuscarBanco() {
    $('#inputBuscarBanco').val('');
    _bancoBuscar = '';
    $('#btnLimpiarBuscarBanco').hide();
    cargarBancoPreguntas(true);
}

function recargarBancoPreguntas() {
    cargarBancoPreguntas(true);
}

function cargarMasBancoPreguntas() {
    _bancoOffset += _bancoLimit;
    cargarBancoPreguntas(false);
}

// ── Cargar todas las secciones para alimentar los selectores de los modales ──
function cargarTodasLasSeccionesParaModales() {
    $.get('{{ route("riiss.formularios.datos") }}', { dimension: 'all' }, function(res) {
        if (res.ok) {
            _todasLasSeccionesCache = res.data;
        }
    });
}

// ── Modal Vincular Pregunta en Sección ──
function abrirModalVincular(id, texto, dimension) {
    $('#vincularPregId').val(id);
    $('#vincularPregTexto').text(texto);
    $('#vincularDimension').val(_currentDimension !== 'all' ? _currentDimension : (dimension || 'cartera_servicios'));
    actualizarSeccionesModalVincular();
    $('#modalVincularPregunta').modal('show');
}

function actualizarSeccionesModalVincular() {
    const dim = $('#vincularDimension').val();
    const $select = $('#vincularSeccionId');
    $select.empty();

    const pool = _todasLasSeccionesCache.length > 0 ? _todasLasSeccionesCache : _seccionesData;
    const filtered = pool.filter(s => s.dimension === dim);

    if (filtered.length === 0) {
        $select.append('<option value="">-- Sin secciones en esta dimensión --</option>');
        return;
    }

    filtered.forEach(s => {
        $select.append(`<option value="${s.id}">${s.nombre_completo}</option>`);
    });
}

function guardarVinculacionPregunta(e) {
    e.preventDefault();
    const pId = $('#vincularPregId').val();
    const secId = $('#vincularSeccionId').val();
    const modo = $('input[name="vincularModo"]:checked').val() || 'copiar';

    if (!secId) {
        Swal.fire('Atención', 'Debe seleccionar una sección de destino.', 'warning');
        return;
    }

    $.post('{{ route("riiss.formularios.vincular-pregunta") }}', {
        _token: '{{ csrf_token() }}',
        pregunta_id: pId,
        formulario_seccion_id: secId,
        modo: modo
    }, function(res) {
        $('#modalVincularPregunta').modal('hide');
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: res.message,
            showConfirmButton: false,
            timer: 2500
        });
        cargarEstructuraFormulario();
        if (modo === 'mover') {
            cargarBancoPreguntas(false);
        }
    }).fail(function(xhr) {
        Swal.fire('Error', xhr.responseJSON?.message || 'Error al vincular la pregunta.', 'error');
    });
}

// ── Persistencia Ajax del Reordenamiento Drag & Drop ──
function guardarReordenamientoPreguntas(targetDropzone) {
    const $zone = $(targetDropzone);
    const seccionId = $zone.data('seccion-id');
    const dimension = $zone.data('dimension');

    const items = [];
    $zone.find('.question-drag-item').each(function(idx) {
        const pId = $(this).data('id');
        if (pId) {
            items.push({
                id: pId,
                formulario_seccion_id: seccionId,
                dimension: dimension,
                orden: idx + 1
            });
        }
    });

    if (items.length === 0) return;

    $('#saveStatusIndicator').fadeIn(150);

    $.post('{{ route("riiss.formularios.reordenar-preguntas") }}', {
        _token: '{{ csrf_token() }}',
        items: items
    }, function(res) {
        setTimeout(() => {
            $('#saveStatusIndicator').fadeOut(300);
        }, 1500);
    }).fail(function() {
        $('#saveStatusIndicator').html('<i class="fa fa-exclamation-triangle text-danger mr-1"></i> Error al guardar').fadeIn(150);
    });
}

function actualizarContadorSeccion(secId) {
    const count = $(`#dropzoneSeccion-${secId} .question-drag-item`).length;
    $(`.count-sec-${secId}`).text(`(${count} preguntas)`);
}

// ── Toggle Acordeón ──
function toggleSeccionAccordion(secId) {
    const $body = $(`#seccionBody-${secId}`);
    const $arrow = $(`.accordion-arrow-${secId}`);
    $body.slideToggle(180);
    $arrow.toggleClass('fa-chevron-down fa-chevron-up');
}

// ── Modal Crear Pregunta ──
function abrirModalNuevaPregunta() {
    actualizarSeccionesModal('nueva');
    $('#modalCrearPregunta').modal('show');
}

function abrirModalNuevaPreguntaSeccion(secId, dimension) {
    $('#nuevaPregDimension').val(dimension);
    actualizarSeccionesModal('nueva', secId);
    $('#modalCrearPregunta').modal('show');
}

function actualizarSeccionesModal(prefix, selectedSecId = null) {
    const dim = $(`#${prefix}PregDimension`).val();
    const $select = $(`#${prefix}PregSeccionId`);
    $select.empty();

    const pool = _todasLasSeccionesCache.length > 0 ? _todasLasSeccionesCache : _seccionesData;
    const filtered = pool.filter(s => s.dimension === dim);

    if (filtered.length === 0) {
        $select.append('<option value="">-- Sin secciones en esta dimensión --</option>');
        return;
    }

    filtered.forEach(s => {
        $select.append(`<option value="${s.id}" ${selectedSecId == s.id ? 'selected' : ''}>${s.nombre_completo}</option>`);
    });
}

function guardarNuevaPregunta(e) {
    e.preventDefault();
    const secId = $('#nuevaPregSeccionId').val();
    if (!secId) {
        Swal.fire('Atención', 'Debe seleccionar una sección de destino.', 'warning');
        return;
    }

    const payload = {
        _token: '{{ csrf_token() }}',
        pregunta: $('#nuevaPregTexto').val().trim(),
        dimension: $('#nuevaPregDimension').val(),
        tipo_respuesta: $('#nuevaPregTipo').val(),
        grado_complejidad_min: $('#nuevaPregComplejidad').val(),
        peso_ponderacion: $('#nuevaPregPeso').val()
    };

    $.post(`/riiss/formularios/secciones/${secId}/preguntas`, payload, function(res) {
        $('#modalCrearPregunta').modal('hide');
        $('#formCrearPregunta')[0].reset();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Pregunta agregada con éxito',
            showConfirmButton: false,
            timer: 2000
        });
        cargarEstructuraFormulario();
        cargarBancoPreguntas(true);
        cargarTodasLasSeccionesParaModales();
    }).fail(function(xhr) {
        Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo guardar la pregunta.', 'error');
    });
}

// ── Modal Editar Pregunta ──
function abrirModalEditarPregunta(id, texto, tipo, comp) {
    $('#editPregId').val(id);
    $('#editPregTexto').val(texto);
    $('#editPregTipo').val(tipo);
    $('#editPregComplejidad').val(comp);
    $('#modalEditarPregunta').modal('show');
}

function guardarEdicionPregunta(e) {
    e.preventDefault();
    const id = $('#editPregId').val();
    const payload = {
        _token: '{{ csrf_token() }}',
        _method: 'PATCH',
        pregunta: $('#editPregTexto').val().trim(),
        tipo_respuesta: $('#editPregTipo').val(),
        grado_complejidad_min: $('#editPregComplejidad').val()
    };

    $.post(`/riiss/formularios/preguntas/${id}`, payload, function(res) {
        $('#modalEditarPregunta').modal('hide');
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Pregunta actualizada',
            showConfirmButton: false,
            timer: 1800
        });
        cargarEstructuraFormulario();
        cargarBancoPreguntas(false);
    });
}

// ── Duplicar Pregunta ──
function duplicarPreguntaAjax(id) {
    $.post(`/riiss/formularios/preguntas/${id}/duplicar`, { _token: '{{ csrf_token() }}' }, function(res) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Pregunta duplicada',
            showConfirmButton: false,
            timer: 1800
        });
        cargarEstructuraFormulario();
        cargarBancoPreguntas(false);
    });
}

// ── Desactivar Pregunta ──
function eliminarPreguntaAjax(id) {
    Swal.fire({
        title: '¿Desactivar esta pregunta?',
        text: 'La pregunta se ocultará del formulario activo para visitas in situ.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-trash-alt mr-1"></i> Sí, desactivar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/riiss/formularios/preguntas/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    cargarEstructuraFormulario();
                    cargarBancoPreguntas(false);
                }
            });
        }
    });
}

// ── Modal Crear Sección ──
function abrirModalNuevaSeccion() {
    $('#modalCrearSeccion').modal('show');
}

function guardarNuevaSeccion(e) {
    e.preventDefault();
    const payload = {
        _token: '{{ csrf_token() }}',
        dimension: $('#nuevaSecDimension').val(),
        seccion: $('#nuevaSecNombre').val().trim(),
        sub_seccion: $('#nuevaSecSubNombre').val().trim()
    };

    $.post('{{ route("riiss.formularios.secciones.store") }}', payload, function(res) {
        $('#modalCrearSeccion').modal('hide');
        $('#formCrearSeccion')[0].reset();
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Sección creada con éxito',
            showConfirmButton: false,
            timer: 2000
        });
        cargarEstructuraFormulario();
        cargarTodasLasSeccionesParaModales();
    }).fail(function(xhr) {
        Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo crear la sección.', 'error');
    });
}

function escapeHtml(str) {
    return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}
</script>
@endpush

